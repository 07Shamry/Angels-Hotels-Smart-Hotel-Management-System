<?php
require 'db.php';

// SECURITY: Only Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$msg = "";
$error = "";

// ==========================================
// ACTION 1: ADD NEW STAFF
// ==========================================
if (isset($_POST['add_staff'])) {
    $name = cleanInput($_POST['name']);
    $email = cleanInput($_POST['email']);
    $phone = cleanInput($_POST['phone']);
    $pass = $_POST['password'];
    $job = $_POST['job_title'];
    $hotel = intval($_POST['assigned_hotel_id']);
    
    // Check if email exists
    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "❌ Error: Email already exists.";
    } else {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, job_title, assigned_hotel_id) VALUES (?, ?, ?, ?, 'staff', ?, ?)");
        $stmt->bind_param("sssssi", $name, $email, $hashed, $phone, $job, $hotel);
        
        if ($stmt->execute()) {
            $msg = "✅ New Staff Member Added Successfully!";
            logActivity($conn, $_SESSION['user_id'], "Added Staff: $email");
        } else {
            $error = "Database Error: " . $conn->error;
        }
    }
}

// ==========================================
// ACTION 2: DELETE STAFF
// ==========================================
if (isset($_GET['delete_id'])) {
    $did = intval($_GET['delete_id']);
    $conn->query("DELETE FROM users WHERE id=$did AND role='staff'"); // Safety: Only delete staff
    $msg = "✅ Staff member removed.";
    logActivity($conn, $_SESSION['user_id'], "Deleted Staff ID: $did");
}

// ==========================================
// ACTION 3: EDIT STAFF (Handle Form Submission)
// ==========================================
if (isset($_POST['edit_staff'])) {
    $uid = $_POST['user_id'];
    $name = cleanInput($_POST['name']);
    $phone = cleanInput($_POST['phone']);
    $job = $_POST['job_title'];
    $hotel = intval($_POST['assigned_hotel_id']);
    
    $conn->query("UPDATE users SET name='$name', phone='$phone', job_title='$job', assigned_hotel_id=$hotel WHERE id=$uid");
    $msg = "✅ Staff details updated.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Staff | HR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-danger px-4">
    <a class="navbar-brand" href="admin_dashboard.php">🛡️ Admin Dashboard</a>
    <span class="navbar-text text-white">HR Management</span>
</nav>

<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fas fa-users-cog"></i> Staff Management</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStaffModal">
            <i class="fas fa-plus"></i> Add New Staff
        </button>
    </div>

    <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Job Title</th>
                        <th>Assigned Hotel</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all staff and their hotel names
                    $sql = "SELECT u.*, h.name as hotel_name 
                            FROM users u 
                            JOIN hotels h ON u.assigned_hotel_id = h.id 
                            WHERE u.role = 'staff' 
                            ORDER BY u.assigned_hotel_id, u.job_title";
                    $result = $conn->query($sql);

                    if ($result->num_rows == 0) echo "<tr><td colspan='5' class='text-center'>No staff found.</td></tr>";

                    while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td>
                            <strong><?= $row['name'] ?></strong><br>
                            <small class="text-muted"><?= $row['phone'] ?></small>
                        </td>
                        <td><?= $row['email'] ?></td>
                        <td><span class="badge bg-info text-dark"><?= $row['job_title'] ?></span></td>
                        <td><?= $row['hotel_name'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="manage_staff.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this staff member?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header"><h5>Edit Staff: <?= $row['name'] ?></h5></div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                        <div class="mb-2">
                                            <label>Name</label>
                                            <input type="text" name="name" value="<?= $row['name'] ?>" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Phone</label>
                                            <input type="text" name="phone" value="<?= $row['phone'] ?>" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Job Title</label>
                                            <select name="job_title" class="form-select">
                                                <option <?= $row['job_title']=='Receptionist'?'selected':'' ?>>Receptionist</option>
                                                <option <?= $row['job_title']=='Housekeeper'?'selected':'' ?>>Housekeeper</option>
                                                <option <?= $row['job_title']=='Maintenance'?'selected':'' ?>>Maintenance</option>
                                                <option <?= $row['job_title']=='Kitchen'?'selected':'' ?>>Kitchen</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label>Transfer to Hotel</label>
                                            <select name="assigned_hotel_id" class="form-select">
                                                <?php
                                                // Fetch hotels again for dropdown
                                                $h_res = $conn->query("SELECT * FROM hotels");
                                                while($h = $h_res->fetch_assoc()) {
                                                    $sel = ($h['id'] == $row['assigned_hotel_id']) ? 'selected' : '';
                                                    echo "<option value='{$h['id']}' $sel>{$h['name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" name="edit_staff" class="btn btn-primary w-100 mt-3">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">&larr; Back to Dashboard</a>
</div>

<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white"><h5>Add New Staff Member</h5></div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="col"><label>Phone</label><input type="text" name="phone" class="form-control" required></div>
                    </div>
                    <div class="mb-3 mt-2">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label>Job Title</label>
                            <select name="job_title" class="form-select">
                                <option>Receptionist</option>
                                <option>Housekeeper</option>
                                <option>Maintenance</option>
                                <option>Kitchen</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>Assign to Hotel</label>
                            <select name="assigned_hotel_id" class="form-select">
                                <?php
                                $h_res->data_seek(0); // Reset pointer
                                while($h = $h_res->fetch_assoc()) echo "<option value='{$h['id']}'>{$h['name']}</option>";
                                ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="add_staff" class="btn btn-success w-100 mt-4">Create Staff Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>