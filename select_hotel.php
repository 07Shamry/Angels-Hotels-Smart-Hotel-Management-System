<?php
require 'db.php';


// SECURITY: Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Select Destination | Angel's Hotels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand">🌎 Angel's Hotels</a>
        <span class="text-white">Welcome, <?= $_SESSION['name'] ?></span>
        
        <div class="d-flex align-items-center">
            <a href="guest_dashboard.php" class="btn btn-primary btn-sm me-3">
                <i class="fas fa-user-circle"></i> My Dashboard
            </a>
        
            
            <a href="index.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>    
    </div>
</nav>

<div class="container">
    <h2 class="text-center mb-5">Where would you like to stay?</h2>
    
    <div class="row">
        <?php
        // Fetch all hotels
        $result = $conn->query("SELECT * FROM hotels");
        while($hotel = $result->fetch_assoc()):
        ?>
        <div class="col-md-4">
            <div class="card shadow h-100 hover-shadow">
                <img src="<?= $hotel['image_url'] ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                <div class="card-body text-center">
                    <h3><?= $hotel['name'] ?></h3>
                    <p class="text-muted"><i class="fas fa-map-marker-alt"></i> <?= $hotel['location'] ?></p>
                    <a href="rooms.php?hotel_id=<?= $hotel['id'] ?>" class="btn btn-primary w-100">View Rooms</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>