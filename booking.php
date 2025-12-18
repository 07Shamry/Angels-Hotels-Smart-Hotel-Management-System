<?php
require 'db.php';
// SECURITY: Check login
if (!isset($_SESSION['user_id'])) header("Location: index.php");

// 1. Get Room ID
if (!isset($_GET['room_id'])) {
    die("Error: No room selected.");
}
$room_id = intval($_GET['room_id']);
$error = "";

// 2. AUTO-DETECT HOTEL ID & PRICE (Fixes your Fatal Error)
// We query the DB to find which hotel owns this room. 
// We do NOT rely on the URL for this anymore.
$stmt = $conn->prepare("SELECT price, hotel_id FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$room_data = $stmt->get_result()->fetch_assoc();

if (!$room_data) {
    die("Error: Invalid Room ID.");
}

$hotel_id = $room_data['hotel_id']; // <--- FIXED: Fetched directly from DB
$price_per_night = $room_data['price'];

// 3. Handle Reservation
if (isset($_POST['reserve_room'])) {
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $today = date("Y-m-d");

    // Validations
    if ($check_in < $today) {
        $error = "Check-in cannot be in the past.";
    } elseif ($check_out <= $check_in) {
        $error = "Check-out must be after check-in.";
    } else {
        // Conflict Detection
        $check = $conn->prepare("SELECT id FROM bookings WHERE room_id=? AND status='confirmed' AND ((check_in <= ? AND check_out >= ?) OR (check_in <= ? AND check_out >= ?) OR (check_in >= ? AND check_out <= ?))");
        $check->bind_param("issssss", $room_id, $check_in, $check_in, $check_out, $check_out, $check_in, $check_out);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            $error = "❌ Room unavailable for these dates.";
        } else {
            // Calculate Total Cost
            $d1 = new DateTime($check_in);
            $d2 = new DateTime($check_out);
            $nights = $d1->diff($d2)->days;
            $total = $nights * $price_per_night;

            // Reserve
            // Now we use the valid $hotel_id we found earlier
            $stmt = $conn->prepare("INSERT INTO bookings (hotel_id, user_id, room_id, check_in, check_out, total_price, status, payment_status) VALUES (?, ?, ?, ?, ?, ?, 'confirmed', 'Pending')");
            $stmt->bind_param("iiissd", $hotel_id, $_SESSION['user_id'], $room_id, $check_in, $check_out, $total);
            
            if($stmt->execute()) {
                echo "<script>alert('Room Reserved Successfully! Please manage payment in your Dashboard.'); window.location='guest_dashboard.php';</script>";
            } else {
                $error = "Database Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reserve Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
<div class="card mx-auto shadow" style="max-width:500px;">
    <div class="card-header bg-primary text-white">Reserve Your Stay</div>
    <div class="card-body">
        
        <h5 class="card-title text-center mb-4">Price: $<?= $price_per_night ?> / night</h5>
        
        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label>Check In</label>
                <input type="date" name="check_in" class="form-control" required min="<?= date('Y-m-d') ?>">
            </div>
            <div class="mb-3">
                <label>Check Out</label>
                <input type="date" name="check_out" class="form-control" required>
            </div>
            <button type="submit" name="reserve_room" class="btn btn-primary w-100">Confirm Reservation</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="select_hotel.php" class="text-muted">Cancel</a>
        </div>
    </div>
</div>
</body>
</html>