<?php 
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit(); // Stop further execution
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="images/title-img.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1JddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <title>Booking</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
  <style>
      .sidebar {
          position: fixed;
          top: 0;
          bottom: 0;
          left: 0;
          z-index: 100;
          padding: 90px 0 0;
          box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
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

      .table-responsive {
          overflow-x: auto;
      }

      table.table {
          white-space: nowrap; /* Prevent text wrapping */
      }

      table.table th, table.table td {
          min-width: 150px; /* Adjust this value based on your needs */
          white-space: nowrap; /* Prevent text wrapping within table cells */
      }
  </style>
</head>
<body>
    <?php @include('admin_navbar.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <?php @include('admin_sidebar.php'); ?>

            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_homepage.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Location</li>
                    </ol>
                </nav>
                <h1 class="h2">Locations</h1>
                <p>Here we can view all the Locations</p>

                <div class="card">
                    <div class="card-header">
                        Locations Details
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">Booking ID</th>
                                        <th scope="col">User ID</th>
                                        <th scope="col">Booking Venue Name</th>
                                        <th scope="col">Location Address</th>
                                        <th scope="col">Booking Location City</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
$query = "SELECT B.booking_id, B.id AS user_id, B.booking_event_venue_name AS venue_name,  B.booking_event_location_main_address AS location, B.booking_event_location_city AS location_city
FROM Bookings B
JOIN users U ON B.id = U.id
JOIN Packages P ON B.package_id = P.package_id
ORDER BY B.booked_at DESC";

$stmt = $pdo->query($query);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result) > 0) {
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>{$row['booking_id']}</td>";
        echo "<td>{$row['user_id']}</td>";
        echo "<td>{$row['venue_name']}</td>";
        echo "<td>{$row['location']}</td>";
        echo "<td>{$row['location_city']}</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='13'>No bookings found</td></tr>";
}
?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
</body>
</html>