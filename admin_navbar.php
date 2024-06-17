<?php

@include 'config.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

$query = "SELECT name FROM users WHERE user_type = 'admin' AND id = :admin_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':admin_id', $admin_id);
$stmt->execute();

if ($stmt && $stmt->rowCount() > 0) {
    $row = $stmt->fetch();
    $adminName = $row['name'];
} else {
    $adminName = "Admin";
}
?>

<nav class="navbar navbar-light bg-light p-3">
    <div class="d-flex col-12 col-md-3  col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
        <a class="navbar-brand fw-bold" href="admin_homepage.php">
            RC Studio PhotoBooth Dashboard
        </a>
        <button class="navbar-toggler d-md-none collapsed mb-3" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="col-12 col-md-5 col-lg-8 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                Hello, <?php echo $adminName; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="admin_profile.php?admin_id={$row['admin_id']}">Profile</a></li>
                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>
</nav>
