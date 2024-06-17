<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $query = "UPDATE users SET name=:name, phone=:phone, email=:email, password=:password, user_type=:user_type WHERE id=:id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':user_type', $user_type);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('location:admin_user.php');
    } else {
        echo "Error updating record: " . $stmt->errorInfo();
    }
}
?>