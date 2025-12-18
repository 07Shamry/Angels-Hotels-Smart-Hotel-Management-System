<?php
// 1. DATABASE CONNECTION
$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password
$dbname = "hotelchain_db"; // As per your setup

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. SECURITY HELPER: Sanitize Inputs (Prevents XSS & SQL Injection)
function cleanInput($data) {
    $data = trim($data);            // Remove extra spaces
    $data = stripslashes($data);    // Remove backslashes
    $data = htmlspecialchars($data);// Convert special chars to HTML entities
    return $data;
}

// 3. AUDIT TRAIL HELPER: Log important actions
function logActivity($conn, $user_id, $action) {
    // Get the user's IP address
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Use Prepared Statement for security
    $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, ip_address) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $action, $ip);
    $stmt->execute();
    $stmt->close();
}

session_start();
?>