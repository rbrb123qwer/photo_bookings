<?php

@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$user_info = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$user_info->bindParam(':user_id', $user_id);
$user_info->execute();

if ($user_info->rowCount() > 0) {
    $user = $user_info->fetch(PDO::FETCH_ASSOC);
} else {
    echo "User not found.";
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $update_query = $pdo->prepare("UPDATE users SET name=:name, phone=:phone, email=:email WHERE id=:user_id");
    $update_query->bindParam(':name', $name);
    $update_query->bindParam(':phone', $phone);
    $update_query->bindParam(':email', $email);
    $update_query->bindParam(':user_id', $user_id);

    if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $update_password_query = $pdo->prepare("UPDATE users SET password=:hashed_password WHERE id=:user_id");
                $update_password_query->bindParam(':hashed_password', $hashed_password);
                $update_password_query->bindParam(':user_id', $user_id);
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
        if (empty($message)) { // Ensure that a message was not already set by the password update logic
            $message = "Profile updated successfully!";
        }
    } else {
        $message = "Error updating profile.";
    }

    // Refresh user info after update
    $user_info = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
    $user_info->bindParam(':user_id', $user_id);
    $user_info->execute();
    $user = $user_info->fetch(PDO::FETCH_ASSOC);
}

?>

<style>
.back-button {
        position: absolute;
        top: 20px;
        left: 20px;
        color: white;
        text-decoration: none;
        font-size: 24px;
    }

</style>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="images/title-img.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <title>User Profile</title>
</head>
<body class="bg-dark text-white">
<a href="user_homepage.php" class="back-button"><i class="fas fa-arrow-left"></i></a>
    <div class="container mt-5 text-white">
        <h2>User Profile</h2>
        <?php if (!empty($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
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
                <p><?php echo $user['created_at']; ?></p>
            </div>
            <a href="user_homepage.php" class="btn btn-danger">Go Back</a>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>