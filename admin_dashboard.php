<?php
require 'db.php';

// SECURITY: Only Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { header("Location: index.php"); exit(); }

$filter_hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : 'all';
$where_clause = ($filter_hotel_id != 'all') ? "WHERE hotel_id = $filter_hotel_id" : "";

// ==========================================
// ACTION 1: HANDLE MANUAL ALLOCATION
// ==========================================
if (isset($_POST['allocate_resource'])) {
    $rid = $_POST['room_id'];
    $cin = $_POST['check_in'];
    $cout = $_POST['check_out'];
    $status = $_POST['alloc_status'];

    // Conflict Detection
    $check = $conn->query("SELECT id FROM bookings WHERE room_id=$rid AND status!='cancelled' AND ((check_in <= '$cin' AND check_out >= '$cin') OR (check_in <= '$cout' AND check_out >= '$cout'))");
    
    if ($check->num_rows > 0) {
        $msg = "❌ Error: CONFLICT DETECTED. Resource is unavailable.";
    } else {
        $uid = $_SESSION['user_id'];
        $hid_res = $conn->query("SELECT hotel_id FROM rooms WHERE id=$rid")->fetch_assoc();
        $hid = $hid_res['hotel_id']; // Fix possible null issue
        
        $stmt = $conn->prepare("INSERT INTO bookings (hotel_id, user_id, room_id, check_in, check_out, total_price, status) VALUES (?, ?, ?, ?, ?, 0.00, ?)");
        $stmt->bind_param("iissss", $hid, $uid, $rid, $cin, $cout, $status);
        $stmt->execute();
        $msg = "✅ Resource Allocated Successfully.";
    }
}

// ==========================================
// ACTION 2: SEND NOTIFICATIONS
// ==========================================
if (isset($_POST['send_notification'])) {
    $title = cleanInput($_POST['title']);
    $message = cleanInput($_POST['message']);
    $type = $_POST['type'];
    $target = $_POST['target_hotel'];
    
    $hid_val = ($target == 'all') ? "NULL" : "'$target'";
    $conn->query("INSERT INTO notifications (hotel_id, title, message, type) VALUES ($hid_val, '$title', '$message', '$type')");
    $msg = "✅ Notification Sent!";
}

// FETCH ANALYTICS DATA
// 1. Revenue
$revenue = $conn->query("SELECT SUM(total_price) as total FROM bookings $where_clause")->fetch_assoc()['total'] ?? 0;

// 2. Service Performance
$service_stats = $conn->query("SELECT service_type, COUNT(*) as count FROM service_requests $where_clause GROUP BY service_type");

// 3. Guest Preferences (FIXED AMBIGUITY HERE)
// We replace 'WHERE hotel_id' with 'WHERE b.hotel_id' specifically for this joined query
$join_where = str_replace("WHERE hotel_id", "WHERE b.hotel_id", $where_clause);
$pref_stats = $conn->query("SELECT r.type, COUNT(*) as count FROM bookings b JOIN rooms r ON b.room_id = r.id $join_where GROUP BY r.type");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Command Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-danger px-4">
    <a class="navbar-brand">🛡️ Chain Command Center</a>
    <a href="manage_staff.php" class="btn btn-outline-dark flex-fill">
        <i class="fas fa-users-cog"></i> Manage Staff
    </a>
    <a href="index.php" class="btn btn-sm btn-light text-danger">Logout</a>
    
</nav>

<div class="container-fluid mt-4">
    <?php if(isset($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>

    <div class="d-flex justify-content-between mb-4">
        <h3>Dashboard</h3>
        <form method="GET">
            <select name="hotel_id" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= $filter_hotel_id == 'all' ? 'selected' : '' ?>>All Hotels</option>
                <?php
                $h_res = $conn->query("SELECT * FROM hotels");
                while($h = $h_res->fetch_assoc()) echo "<option value='{$h['id']}' ".($filter_hotel_id == $h['id'] ? 'selected' : '').">{$h['name']}</option>";
                ?>
            </select>
        </form>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">Guest Preferences</div>
                <div class="card-body"><canvas id="prefChart"></canvas></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">Service Performance</div>
                <div class="card-body"><canvas id="servChart"></canvas></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h5>Total Revenue</h5>
                    <h1 class="display-4">$<?= number_format($revenue) ?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white">Resource Allocation</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Select Resource</label>
                            <select name="room_id" class="form-control" required>
                                <?php
                                // Also fix ambiguity here just in case: use r.hotel_id
                                $r_where = str_replace("WHERE hotel_id", "WHERE r.hotel_id", $where_clause);
                                $r_sql = "SELECT r.id, r.room_number, r.type, h.name FROM rooms r JOIN hotels h ON r.hotel_id = h.id $r_where";
                                $r_res = $conn->query($r_sql);
                                while($r = $r_res->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['name']} - {$r['room_number']} ({$r['type']})</option>";
                                ?>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col"><input type="date" name="check_in" class="form-control" required></div>
                            <div class="col"><input type="date" name="check_out" class="form-control" required></div>
                        </div>
                        <select name="alloc_status" class="form-control mb-3">
                            <option value="confirmed">Internal Booking</option>
                            <option value="maintenance">Maintenance Block</option>
                        </select>
                        <button type="submit" name="allocate_resource" class="btn btn-warning w-100">Allocate</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">Guest Communication</div>
                <div class="card-body">
                    <form method="POST">
                        <select name="target_hotel" class="form-control mb-3">
                            <option value="all">All Guests</option>
                            <?php
                            $h_res->data_seek(0);
                            while($h = $h_res->fetch_assoc()) echo "<option value='{$h['id']}'>Guests at {$h['name']}</option>";
                            ?>
                        </select>
                        <select name="type" class="form-control mb-3">
                            <option value="promo">🎉 Promotion</option>
                            <option value="alert">⚠️ Alert</option>
                        </select>
                        <input type="text" name="title" placeholder="Title" class="form-control mb-3" required>
                        <textarea name="message" placeholder="Message..." class="form-control mb-3" required></textarea>
                        <button type="submit" name="send_notification" class="btn btn-success w-100">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// CHART DATA
const prefLabels = []; const prefData = [];
<?php while($row = $pref_stats->fetch_assoc()) { echo "prefLabels.push('{$row['type']}'); prefData.push({$row['count']});"; } ?>

const servLabels = []; const servData = [];
<?php while($row = $service_stats->fetch_assoc()) { echo "servLabels.push('{$row['service_type']}'); servData.push({$row['count']});"; } ?>

if(document.getElementById('prefChart')) {
    new Chart(document.getElementById('prefChart'), { type: 'pie', data: { labels: prefLabels, datasets: [{ data: prefData, backgroundColor: ['#ff6384', '#36a2eb', '#ffce56'] }] } });
}
if(document.getElementById('servChart')) {
    new Chart(document.getElementById('servChart'), { type: 'bar', data: { labels: servLabels, datasets: [{ label: 'Requests', data: servData, backgroundColor: '#4bc0c0' }] } });
}
</script>

</body>
</html>