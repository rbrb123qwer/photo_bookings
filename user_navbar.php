<?php

@include 'config.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

$query = "SELECT name FROM users WHERE user_type = 'user' AND id = $user_id";
$result = $conn->query($query);

if ($result && $ $result->rowCount() > 0) {
    $row = $result->fetch();
    $userName = $row['name'];
} else {
    $userName = "User";
}
?>

<nav class="navbar navbar-light bg-light p-3">

    <div class="d-flex col-12 col-md-3  col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
        <a class="navbar-brand fw-bold" href="admin_homepage.php">
            RC Studia
        </a>
        <button class="navbar-toggler d-md-none collapsed mb-3" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>

    <div class="col-6 col-md-5 col-lg-8 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">

         <div class="book mr-3">
                 <a href="booking.php" class="btn btn-primary m-3" type="button">
                     Book Now!</a>
         </div>

        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                Hello, <?php echo $userName; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Messages</a></li>
                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>
</nav>
