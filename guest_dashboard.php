<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) header("Location: index.php");

$uid = $_SESSION['user_id'];
$msg = "";

// ==========================================
// ACTION 1: ONLINE CHECK-IN / CHECK-OUT
// ==========================================
if (isset($_GET['action']) && isset($_GET['bid'])) {
    $bid = intval($_GET['bid']);
    $action = $_GET['action'];
    
    if ($action == 'checkin') {
        $conn->query("UPDATE bookings SET check_in_status='Checked-In' WHERE id=$bid AND user_id=$uid");
        $msg = "✅ You have successfully checked in online!";
    } elseif ($action == 'checkout') {
        // Validate Payment before checkout
        $b = $conn->query("SELECT payment_status FROM bookings WHERE id=$bid")->fetch_assoc();
        if ($b['payment_status'] == 'Pending') {
            $msg = "❌ Please pay your bill before checking out.";
        } else {
            $conn->query("UPDATE bookings SET check_in_status='Checked-Out', status='completed' WHERE id=$bid AND user_id=$uid");
            $msg = "✅ Checked out. Thank you for staying!";
        }
    }
}

// ==========================================
// ACTION 2: HANDLE SERVICE REQUESTS
// ==========================================
if (isset($_POST['request_service'])) {
    $bid = $_POST['booking_id'];
    $type = $_POST['service_type']; // Dining, Cleaning, etc.
    $desc = cleanInput($_POST['description']);
    
    // Get Hotel ID from booking to route to correct staff
    $b_row = $conn->query("SELECT hotel_id, room_id FROM bookings WHERE id=$bid")->fetch_assoc();
    $hid = $b_row['hotel_id'];
    $rid = $b_row['room_id'];

    // Assign cost based on type (Mock Logic)
    $cost = 0;
    if ($type == 'Dining') $cost = 25.00; // Flat fee for demo
    if ($type == 'Transport') $cost = 50.00;

    $stmt = $conn->prepare("INSERT INTO service_requests (hotel_id, room_id, user_id, service_type, description, cost) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssd", $hid, $rid, $uid, $type, $desc, $cost);
    $stmt->execute();
    $msg = "✅ Service requested! Routed to " . $type . " department.";
}

// ==========================================
// ACTION 3: PROCESS PAYMENT
// ==========================================
if (isset($_POST['pay_bill'])) {
    $bid = $_POST['booking_id'];
    $amount = $_POST['total_amount'];
    // Simulation
    $conn->query("UPDATE bookings SET payment_status='Paid' WHERE id=$bid");
    $msg = "✅ Payment of $$amount successful. Receipt emailed.";
}

// ACTION 4: CANCEL FUTURE BOOKING
if (isset($_GET['cancel_id'])) {
    $cid = intval($_GET['cancel_id']);
    // Only allow if payment is Pending or it's a future booking
    $conn->query("UPDATE bookings SET status='cancelled' WHERE id=$cid AND user_id=$uid");
    $msg = "✅ Booking cancelled successfully.";
}

// ACTION 5: UPDATE PASSWORD
if (isset($_POST['update_pass'])) {
    $new_pass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password='$new_pass' WHERE id=$uid");
    $msg = "✅ Password updated.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Guest Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">👋 Welcome, <?= $_SESSION['name'] ?></span>
        
        <div class="d-flex">
            <a href="select_hotel.php" class="btn btn-warning btn-sm me-3">
                <i class="fas fa-home"></i> Go to Home
            </a>
        
        <button class="btn btn-outline-light btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#profileModal">
            <i class="fas fa-user-cog"></i> Profile
        </button>

            <a href="index.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?php if($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#stays">My Stays</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#billing">Billing & Payments</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#history">Request History</button></li>

        <li class="nav-item">
            <button class="nav-link text-danger fw-bold" data-bs-toggle="tab" data-bs-target="#notifications">
                🔔 Notifications
            </button>
        </li>
    </ul>

    <div class="tab-content bg-white p-4 shadow-sm border border-top-0">
        
        <div class="tab-pane fade show active" id="stays">
            <h3>Active Reservations</h3>
            <?php
            $sql = "SELECT b.*, h.name as hotel_name, r.room_number 
                    FROM bookings b 
                    JOIN hotels h ON b.hotel_id = h.id 
                    JOIN rooms r ON b.room_id = r.id 
                    WHERE b.user_id = $uid AND b.status != 'cancelled'";
            $res = $conn->query($sql);
            
            if ($res->num_rows == 0) echo "<p>No active bookings.</p>";
            
            while($row = $res->fetch_assoc()):
                $today = date("Y-m-d");
            ?>
                <div class="card mb-3 border-primary">
                    <div class="card-header d-flex justify-content-between">
                        <strong><?= $row['hotel_name'] ?> - Room <?= $row['room_number'] ?></strong>
                        <span class="badge bg-<?= $row['check_in_status']=='Checked-In'?'success':'secondary' ?>">
                            <?= $row['check_in_status'] ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Check-In:</strong> <?= $row['check_in'] ?></p>
                                <p><strong>Check-Out:</strong> <?= $row['check_out'] ?></p>
                                
                                <?php if($row['check_in_status'] == 'Pending'): ?>
                                    <a href="guest_dashboard.php?action=checkin&bid=<?= $row['id'] ?>" 
                                       class="btn btn-success btn-sm <?= ($today < $row['check_in']) ? 'disabled' : '' ?>">
                                       Online Check-In
                                    </a>
                                <?php elseif($row['check_in_status'] == 'Checked-In'): ?>
                                    <a href="guest_dashboard.php?action=checkout&bid=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Online Check-Out</a>
                                <?php endif; ?>

                                <?php if($row['status'] == 'confirmed' && $row['check_in_status'] == 'Pending'): ?>
                                    <a href="guest_dashboard.php?cancel_id=<?= $row['id'] ?>" 
                                    class="btn btn-outline-danger btn-sm mt-2"
                                    onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                    Cancel Reservation
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8 border-start">
                                <h5>Request Services</h5>
                                <form method="POST">
                                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                                    <div class="input-group mb-2">
                                        <select name="service_type" class="form-select" required>
                                            <option value="">Select Service...</option>
                                            <option value="Dining">Dining (Kitchen)</option>
                                            <option value="Cleaning">Housekeeping</option>
                                            <option value="Transport">Transport (Concierge)</option>
                                            <option value="Maintenance">Maintenance</option>
                                        </select>
                                        <input type="text" name="description" class="form-control" placeholder="Details (e.g. Burger, Extra Towels)" required>
                                        <button type="submit" name="request_service" class="btn btn-primary">Request</button>
                                    </div>
                                    <small class="text-muted">*Charges may apply for Dining/Transport</small>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="tab-pane fade" id="billing">
            <h3>Billing Details</h3>
            <?php
            // Reuse query for active bookings
            $res->data_seek(0); // Reset pointer
            while($row = $res->fetch_assoc()):
                // 1. Calculate Room Cost
                $room_cost = $row['total_price'];
                
                // 2. Calculate Service Costs
                $s_sql = "SELECT SUM(cost) as s_total FROM service_requests WHERE user_id=$uid AND room_id={$row['room_id']}";
                $s_res = $conn->query($s_sql);
                $service_cost = $s_res->fetch_assoc()['s_total'] ?? 0.00;
                
                $grand_total = $room_cost + $service_cost;
            ?>
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">Invoice #INV-<?= $row['id'] ?></div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <td>Room Charges (<?= $row['check_in'] ?> to <?= $row['check_out'] ?>)</td>
                            <td class="text-end">$<?= number_format($room_cost, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Additional Services (Dining, Transport, etc.)</td>
                            <td class="text-end">$<?= number_format($service_cost, 2) ?></td>
                        </tr>
                        <tr class="table-active fw-bold">
                            <td>TOTAL PAYABLE</td>
                            <td class="text-end">$<?= number_format($grand_total, 2) ?></td>
                        </tr>
                    </table>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Status: </strong>
                            <?php if($row['payment_status']=='Paid'): ?>
                                <span class="badge bg-success">PAID</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">PENDING</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($row['payment_status'] == 'Pending'): ?>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#payModal<?= $row['id'] ?>">
                                Pay Now ($<?= $grand_total ?>)
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="payModal<?= $row['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"><h5>Secure Payment</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body">
                            <p>Total: $<?= $grand_total ?></p>
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="total_amount" value="<?= $grand_total ?>">
                                <input type="text" class="form-control mb-2" placeholder="Card Number (Fake)" required>
                                <div class="row">
                                    <div class="col"><input type="text" class="form-control" placeholder="MM/YY"></div>
                                    <div class="col"><input type="text" class="form-control" placeholder="CVC"></div>
                                </div>
                                <button type="submit" name="pay_bill" class="btn btn-success w-100 mt-3">Confirm Payment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="tab-pane fade" id="history">
            <h3>Request History</h3>
            <table class="table">
                <tr><th>Service</th><th>Description</th><th>Status</th><th>Cost</th></tr>
                <?php
                $h_res = $conn->query("SELECT * FROM service_requests WHERE user_id=$uid ORDER BY id DESC");
                while($h = $h_res->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $h['service_type'] ?></td>
                    <td><?= $h['description'] ?></td>
                    <td><span class="badge bg-secondary"><?= $h['status'] ?></span></td>
                    <td>$<?= $h['cost'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="tab-pane fade" id="notifications">
            <h3>Updates & Offers</h3>
            <div class="list-group">
                <?php
                // Fetch Broadcasts (NULL) OR Specific Hotel Messages
                // We need to find which hotel the guest is currently booked at to show relevant alerts
                // For simplicity, we show Chain-Wide (NULL) + Alerts for any hotel they have EVER booked
                
                $n_sql = "SELECT * FROM notifications 
                        WHERE hotel_id IS NULL 
                        OR hotel_id IN (SELECT DISTINCT hotel_id FROM bookings WHERE user_id=$uid) 
                        ORDER BY created_at DESC";
                $n_res = $conn->query($n_sql);

                if($n_res->num_rows == 0) echo "<p class='text-muted'>No new notifications.</p>";

                while($note = $n_res->fetch_assoc()):
                    $color = ($note['type'] == 'promo') ? 'success' : (($note['type'] == 'alert') ? 'danger' : 'info');
                    $icon = ($note['type'] == 'promo') ? '🎁' : (($note['type'] == 'alert') ? '⚠️' : '⏰');
                ?>
                <div class="list-group-item list-group-item-action border-start border-4 border-<?= $color ?> mb-2 shadow-sm">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1 text-<?= $color ?>"><?= $icon ?> <?= $note['title'] ?></h5>
                        <small class="text-muted"><?= $note['created_at'] ?></small>
                    </div>
                    <p class="mb-1"><?= $note['message'] ?></p>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>My Profile</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p><strong>Name:</strong> <?= $_SESSION['name'] ?></p>
                <p><strong>Email:</strong> (Cannot be changed)</p>
                <hr>
                <form method="POST">
                    <label>New Password</label>
                    <input type="password" name="new_pass" class="form-control mb-2" required>
                    <button type="submit" name="update_pass" class="btn btn-dark w-100">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>


</body>
</html>