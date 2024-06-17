<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $package_id = intval($_POST['package_id']);
    $title = $_POST['title'];
    $details = $_POST['details'];
    $price = floatval($_POST['price']);
    $hours = intval($_POST['hours']);

    $update_query = "UPDATE packages SET title = :title, details = :details, price = :price, hours = :hours WHERE package_id = :package_id";

    try {
        $stmt = $pdo->prepare($update_query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':details', $details);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':hours', $hours);
        $stmt->bindParam(':package_id', $package_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header('Location: admin_edit_package.php');
            exit();
        } else {
            echo "No rows were updated.";
        }
    } catch (PDOException $e) {
        echo "Error updating record: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}