<?php 
@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// Fetch admin details
$admin_info = $pdo->prepare("SELECT * FROM users WHERE id = :admin_id AND user_type = 'admin'");
$admin_info->bindParam(':admin_id', $admin_id);
$admin_info->execute();

if ($admin_info->rowCount() > 0) {
    $admin = $admin_info->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Admin not found.";
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $update_query = $pdo->prepare("UPDATE users SET name=:name, email=:email WHERE id=:admin_id AND user_type = 'admin'");
    $update_query->bindParam(':name', $name);
    $update_query->bindParam(':email', $email);
    $update_query->bindParam(':admin_id', $admin_id);

    if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (password_verify($current_password, $admin['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $update_password_query = $pdo->prepare("UPDATE users SET password=:hashed_password WHERE id=:admin_id AND user_type = 'admin'");
                $update_password_query->bindParam(':hashed_password', $hashed_password);
                $update_password_query->bindParam(':admin_id', $admin_id);
                if ($update_password_query->execute()) {
                    $message = "Password updated successfully!";
                } else {
                    $message = "Error updating password.";
                }
            } else {
                $message = "New passwords do not match.";
            }
        } else {
            $message = "Current password is incorrect.";
        }
    }

    if ($update_query->execute()) {
        if (empty($message)) {
            $message = "Profile updated successfully!";
        }
    } else {
        $message = "Error updating profile.";
    }

    // Refresh admin info after update
    $admin_info = $pdo->prepare("SELECT * FROM users WHERE id = :admin_id AND user_type = 'admin'");
    $admin_info->bindParam(':admin_id', $admin_id);
    $admin_info->execute();
    $admin = $admin_info->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RC Studio Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <h2>Admin Profile</h2>
        <?php if (!empty($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>">
            </div>
            <hr>
            <h5>Change Password</h5>
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password">
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
            <hr>
            <div class="form-group">
                <label>Account Created:</label>
                <p><?php echo $admin['created_at']; ?></p>
            </div>
            <hr>
            <a href="admin_homepage.php" class="btn btn-danger">Go Back</a>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
