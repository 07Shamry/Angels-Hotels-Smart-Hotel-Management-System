<?php
require 'db.php';

$error = "";
$success = "";

// ==========================================
// 1. BACK-END LOGIC: HANDLE REGISTRATION
// ==========================================
if (isset($_POST['register'])) {
    // A. Sanitize Inputs
    $name = cleanInput($_POST['name']);
    $email = cleanInput($_POST['email']);
    $phone = cleanInput($_POST['phone']);
    $pass = $_POST['password'];
    $cpass = $_POST['confirm_password'];

    // B. Back-End Validations
    if ($pass !== $cpass) {
        $error = "Passwords do not match!";
    } elseif (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // C. Check if Email Exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "This email is already registered.";
        } else {
            // D. Create New Guest (Secure Hash)
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $role = 'guest'; // Hardcoded safety

            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashed_pass, $phone, $role);
            
            if ($stmt->execute()) {
                $success = "Account created! Please login.";
                // Log this action (Audit Trail) - User ID is technically unknown until login, but we record the event
            } else {
                $error = "System Error: " . $conn->error;
            }
        }
    }
}

// ==========================================
// 2. BACK-END LOGIC: HANDLE LOGIN
// ==========================================
if (isset($_POST['login'])) {
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    $expected_role = $_POST['login_type']; // 'guest', 'staff', or 'admin'

    $stmt = $conn->prepare("SELECT id, name, password, role, assigned_hotel_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // A. Verify Password
        if (password_verify($password, $user['password'])) {
            
            // B. Role Validation (Security Check)
            // Prevent a Guest from logging in via the Admin tab
            if ($user['role'] !== $expected_role) {
                $error = "Access Denied: You cannot login here with a " . strtoupper($user['role']) . " account.";
            } else {
                // C. Login Success - Set Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['assigned_hotel_id'] = $user['assigned_hotel_id'];

                // D. Log Audit Trail
                logActivity($conn, $user['id'], "User Logged In");

                // E. Redirect based on Role
                if ($user['role'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($user['role'] == 'staff') {
                    header("Location: staff_panel.php");
                } else {
                    // Guest goes to Hotel Selection
                    header("Location: select_hotel.php"); 
                }
                exit();
            }
        } else {
            $error = "Invalid Password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angel's Hotels | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { width: 100%; max-width: 450px; border: none; shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .nav-pills .nav-link { color: #555; font-weight: 500; }
        .nav-pills .nav-link.active { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>

<div class="card shadow-lg p-4">
    <h3 class="text-center mb-4 fw-bold">🏨 Angel's Hotels</h3>

    <?php if($error): ?>
        <div class="alert alert-danger p-2 text-center"><?= $error ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="alert alert-success p-2 text-center"><?= $success ?></div>
    <?php endif; ?>

    <ul class="nav nav-pills nav-fill mb-4" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="tab-guest" data-bs-toggle="pill" data-bs-target="#pills-guest" type="button">Guest</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tab-staff" data-bs-toggle="pill" data-bs-target="#pills-staff" type="button">Staff</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tab-admin" data-bs-toggle="pill" data-bs-target="#pills-admin" type="button">Admin</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-guest">
            <form method="POST">
                <input type="hidden" name="login_type" value="guest">
                <div class="mb-3">
                    <label>Guest Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="guest@example.com">
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter password">
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login as Guest</button>
            </form>
            <hr>
            <div class="text-center">
                <p class="small text-muted">New here?</p>
                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">Create Guest Account</button>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-staff">
            <div class="alert alert-info py-1 small"><i class="fas fa-info-circle"></i> For authorized personnel only.</div>
            <form method="POST">
                <input type="hidden" name="login_type" value="staff">
                <div class="mb-3">
                    <label>Staff ID (Email)</label>
                    <input type="email" name="email" class="form-control" required placeholder="staff@chain.com">
                </div>
                <div class="mb-3">
                    <label>Secure Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-dark w-100">Login to Staff Portal</button>
            </form>
        </div>

        <div class="tab-pane fade" id="pills-admin">
            <div class="alert alert-danger py-1 small">⚠️ Restricted Area. All actions are logged.</div>
            <form method="POST">
                <input type="hidden" name="login_type" value="admin">
                <div class="mb-3">
                    <label>Admin Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="admin@chain.com">
                </div>
                <div class="mb-3">
                    <label>Master Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-danger w-100">Access Control Panel</button>
            </form>
        </div>

    </div>
</div>

<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Guest Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" onsubmit="return validateRegister()">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required minlength="3">
                    </div>
                    <div class="mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" required pattern="[0-9]{10}" title="10 digit mobile number">
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" id="pass" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" id="cpass" class="form-control" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-success w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // FRONT-END VALIDATION (JAVASCRIPT)
    function validateRegister() {
        var p1 = document.getElementById("pass").value;
        var p2 = document.getElementById("cpass").value;
        if(p1 != p2) {
            alert("❌ Passwords do not match!");
            return false;
        }
        return true;
    }
</script>

</body>
</html>