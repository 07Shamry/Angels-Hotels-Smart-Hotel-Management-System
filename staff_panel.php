<?php
require 'db.php';

// SECURITY: Only Staff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') { header("Location: index.php"); exit(); }

$uid = $_SESSION['user_id'];
$hid = $_SESSION['assigned_hotel_id'];

if (!$hid) die("Error: You are not assigned to any hotel branch.");

// FETCH STAFF DETAILS
$me = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
$my_job = $me['job_title'];

// ==========================================
// ACTION 1: UPDATE ROOM STATUS (Housekeeping)
// ==========================================
if (isset($_POST['update_room_status'])) {
    $rid = $_POST['room_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE rooms SET status='$status' WHERE id=$rid AND hotel_id=$hid");
    $msg = "✅ Room status updated to $status.";
    logActivity($conn, $uid, "Changed Room $rid status to $status");
}

// ==========================================
// ACTION 2: COMPLETE TASK (Fulfillment)
// ==========================================
if (isset($_GET['complete_task'])) {
    $tid = intval($_GET['complete_task']);
    $conn->query("UPDATE service_requests SET status='Completed' WHERE id=$tid AND hotel_id=$hid");
    $msg = "✅ Task marked as completed.";
}

// ==========================================
// ACTION 3: RECEPTION - CHECK IN GUEST (Allocation)
// ==========================================
if (isset($_GET['check_in_id'])) {
    $bid = intval($_GET['check_in_id']);
    $conn->query("UPDATE bookings SET check_in_status='Checked-In' WHERE id=$bid");
    $msg = "✅ Guest Checked-In Successfully.";
    logActivity($conn, $uid, "Checked-in Booking #$bid");
}

// ==========================================
// ACTION 4: RECEPTION - LOG REQUEST (Make Request)
// ==========================================
if (isset($_POST['log_request'])) {
    $rid = $_POST['room_id'];
    $type = $_POST['type'];
    $desc = "Front Desk: " . cleanInput($_POST['desc']);
    
    // Find User ID for this room (Optional, can be 0 if unknown)
    $u_res = $conn->query("SELECT user_id FROM bookings WHERE room_id=$rid AND status='confirmed' LIMIT 1");
    $guest_id = ($u_res->num_rows > 0) ? $u_res->fetch_assoc()['user_id'] : 0;

    $stmt = $conn->prepare("INSERT INTO service_requests (hotel_id, room_id, user_id, service_type, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $hid, $rid, $guest_id, $type, $desc);
    $stmt->execute();
    $msg = "✅ Request logged and sent to $type department.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Staff Portal | <?= $my_job ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-secondary px-4">
    <a class="navbar-brand">🛠️ Staff Operations</a>
    <div class="text-white">
        <?= $me['name'] ?> (<?= $my_job ?>) @ Hotel #<?= $hid ?>
        <a href="index.php" class="btn btn-sm btn-dark ms-3">Logout</a>
    </div>
</nav>

<div class="container-fluid mt-4">
    <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

    <?php if($my_job == 'Receptionist'): ?>
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow border-primary">
                <div class="card-header bg-primary text-white">🛎️ Front Desk - Room Allocations & Arrivals</div>
                <div class="card-body">
                    <h5>Today's Arrivals</h5>
                    <table class="table table-sm">
                        <thead><tr><th>Guest</th><th>Room</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php
                            $today = date("Y-m-d");
                            // Show bookings starting today or active pending check-ins
                            $sql = "SELECT b.*, u.name as guest, r.room_number 
                                    FROM bookings b 
                                    JOIN users u ON b.user_id = u.id 
                                    JOIN rooms r ON b.room_id = r.id 
                                    WHERE b.hotel_id=$hid AND b.status='confirmed' AND b.check_in_status='Pending'";
                            $res = $conn->query($sql);
                            if($res->num_rows == 0) echo "<tr><td colspan='4'>No pending arrivals.</td></tr>";
                            while($row = $res->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?= $row['guest'] ?></td>
                                <td><?= $row['room_number'] ?></td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>
                                    <a href="staff_panel.php?check_in_id=<?= $row['id'] ?>" class="btn btn-sm btn-success">
                                        <i class="fas fa-key"></i> Check In
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-info">
                <div class="card-header bg-info text-white">📞 Log Guest Request</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-2">
                            <label>Room Number</label>
                            <select name="room_id" class="form-select" required>
                                <?php
                                $r_res = $conn->query("SELECT id, room_number FROM rooms WHERE hotel_id=$hid");
                                while($r = $r_res->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['room_number']}</option>";
                                ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Type</label>
                            <select name="type" class="form-select">
                                <option value="Cleaning">Housekeeping</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Food">Kitchen</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="desc" class="form-control" placeholder="Description..." required>
                        </div>
                        <button type="submit" name="log_request" class="btn btn-info w-100 text-white">Create Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <span>📋 Active Tasks (Routed to <?= $my_job ?>)</span>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead><tr><th>Room</th><th>Request</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php
                            // SMART ROUTING
                            $filter = "";
                            if ($my_job == 'Housekeeper') $filter = "AND service_type IN ('Cleaning')";
                            elseif ($my_job == 'Maintenance') $filter = "AND service_type IN ('Maintenance')";
                            elseif ($my_job == 'Kitchen') $filter = "AND service_type IN ('Food', 'Dining')";
                            // Reception sees everything
                            
                            $sql = "SELECT r.room_number, s.* FROM service_requests s 
                                    JOIN rooms r ON s.room_id = r.id 
                                    WHERE s.hotel_id=$hid AND s.status='Pending' $filter 
                                    ORDER BY s.created_at ASC";
                            $res = $conn->query($sql);
                            
                            if ($res->num_rows == 0) echo "<tr><td colspan='4' class='text-center text-muted'>No tasks.</td></tr>";
                            while($row = $res->fetch_assoc()):
                            ?>
                            <tr>
                                <td><span class="badge bg-dark"><?= $row['room_number'] ?></span></td>
                                <td><strong><?= $row['service_type'] ?></strong>: <?= $row['description'] ?></td>
                                <td><small><?= date('H:i', strtotime($row['created_at'])) ?></small></td>
                                <td><a href="staff_panel.php?complete_task=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success">Done</a></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">📅 My Shifts</div>
                <div class="card-body">
                    <?php
                    $today = date("Y-m-d");
                    $rost_res = $conn->query("SELECT * FROM staff_roster WHERE user_id=$uid AND shift_date >= '$today' ORDER BY shift_date ASC LIMIT 3");
                    if ($rost_res->num_rows == 0) echo "No upcoming shifts.";
                    while($shift = $rost_res->fetch_assoc()):
                    ?>
                    <div class="border-bottom py-1">
                        <?= $shift['shift_date'] ?>: <strong><?= $shift['shift_time'] ?></strong>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <?php if($my_job == 'Housekeeper' || $my_job == 'Receptionist'): ?>
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">🛏️ Room Status</div>
                <div class="card-body">
                    <?php
                    $r_res = $conn->query("SELECT * FROM rooms WHERE hotel_id=$hid ORDER BY room_number");
                    while($room = $r_res->fetch_assoc()):
                        $badge = ($room['status']=='available') ? 'success' : (($room['status']=='dirty') ? 'warning text-dark' : 'secondary');
                    ?>
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <span>Room <strong><?= $room['room_number'] ?></strong> <span class="badge bg-<?= $badge ?>"><?= $room['status'] ?></span></span>
                        <form method="POST" class="d-flex gap-1">
                            <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                            <select name="status" class="form-select form-select-sm" style="width:90px;">
                                <option value="available">Clean</option>
                                <option value="dirty">Dirty</option>
                                <option value="maintenance">Maint.</option>
                            </select>
                            <button type="submit" name="update_room_status" class="btn btn-sm btn-light border">Set</button>
                        </form>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
