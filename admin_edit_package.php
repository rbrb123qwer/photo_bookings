<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$query = "SELECT name FROM users WHERE user_type = 'admin' AND id = :admin_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':admin_id', $admin_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $adminName = $row['name'];
} else {
    $adminName = "Admin";
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    
    // Delete the package
    $delete_query = "DELETE FROM packages WHERE package_id = :delete_id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->bindParam(':delete_id', $delete_id);
    if ($stmt->execute()) {
        header('location: admin_edit_package.php');
        exit();
    } else {
        echo "Error deleting record: " . $stmt->errorInfo();
    }
}

// Fetch packages from the database
$query = "SELECT * FROM packages";
$stmt = $pdo->prepare($query);
$stmt->execute();

if (!$stmt) {
    $errorInfo = $pdo->errorInfo();
    die("Query failed: " . $errorInfo[2]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 90px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            z-index: 99;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                top: 11.5rem;
                padding: 0;
            }
        }
        .navbar {
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .1);
        }
        @media (min-width: 767.98px) {
            .navbar {
                top: 0;
                position: sticky;
                z-index: 999;
            }
        }
        .sidebar .nav-link {
            color: #333;
        }
        .sidebar .nav-link.active {
            color: #0d6efd;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-light p-3">
    <div class="d-flex col-12 col-md-3  col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
        <a class="navbar-brand fw-bold" href="admin_homepage.php">
            RC Studio Dashboard
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

    <div class="container-fluid">
        <div class="row">
            <?php include('admin_sidebar.php'); ?>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_homepage.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Package</li>
                    </ol>
                </nav>
                <h1 class="h2">Remove Package</h1>
                <p>Here you can edit and remove packages</p>

                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($row['details'])); ?></p>
                            <p class="card-text"><strong>Price:</strong> <?php echo htmlspecialchars($row['price']); ?></p>
                            <p class="card-text"><strong>Hours:</strong> <?php echo htmlspecialchars($row['hours']); ?></p>
                            <hr>
                            <a href="update_package.php?package_id=<?php echo $row['package_id']; ?>" class="btn btn-primary">Update</a>
                            <a href="admin_edit_package.php?delete=<?php echo $row['package_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this package?');">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>

            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>
</html>
