<?php
require 'db.php';

if (!isset($_GET['hotel_id'])) {
    header("Location: select_hotel.php");
    exit();
}

$hotel_id = intval($_GET['hotel_id']); // Sanitize URL input

// Fetch Hotel Name for display
$hotel_stmt = $conn->prepare("SELECT name FROM hotels WHERE id = ?");
$hotel_stmt->bind_param("i", $hotel_id);
$hotel_stmt->execute();
$hotel_name = $hotel_stmt->get_result()->fetch_assoc()['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rooms at <?= $hotel_name ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <a href="select_hotel.php" class="btn btn-outline-secondary mb-3">&larr; Back to Locations</a>
    <h2 class="mb-4">Available Rooms at <b><?= $hotel_name ?></b></h2>

    <div class="row">
        <?php
        // SMART QUERY: Only show Available rooms for THIS hotel
        $sql = "SELECT * FROM rooms WHERE hotel_id = ? AND status = 'available'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hotel_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0):
            while($room = $result->fetch_assoc()):
        ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-bold">Room <?= $room['room_number'] ?></div>
                    <div class="card-body">
                        <h4 class="card-title"><?= $room['type'] ?></h4>
                        <h2 class="text-primary">$<?= $room['price'] ?> <small class="fs-6 text-muted">/night</small></h2>
                        <ul class="list-unstyled mt-3">
                            <li>✔️ Free WiFi</li>
                            <li>✔️ Smart TV</li>
                            <li>✔️ 24/7 Service</li>
                        </ul>
                        <a href="booking.php?room_id=<?= $room['id'] ?>&price=<?= $room['price'] ?>" class="btn btn-success w-100 mt-2">Book This Room</a>
                    </div>
                </div>
            </div>
        <?php 
            endwhile; 
        else:
            echo "<div class='alert alert-warning'>Sorry, no rooms are currently available at this location.</div>";
        endif;
        ?>
    </div>
</div>
</body>
</html>