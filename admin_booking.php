<?php 
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit(); // Stop further execution
}

if (isset($_POST['update_btn'])) {
    $booking_id = $_POST['booking_id'];
    $update_status = $_POST['update_status'];
    $stmt = $pdo->prepare("UPDATE `Bookings` SET status = :update_status WHERE booking_id = :booking_id");
    $stmt->bindParam(':update_status', $update_status);
    $stmt->bindParam(':booking_id', $booking_id);
    $stmt->execute();
    $message[] = 'Booking status has been updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM `Bookings` WHERE booking_id = :delete_id");
    $stmt->bindParam(':delete_id', $delete_id);
    $stmt->execute();
    header('location:admin_booking.php');
}

if (!empty($row) && isset($row['status']) && isset($row['payment_status'])) {
    $status = $row['status'];
    $payment_status = $row['payment_status'];

    // Determine the display status based on payment status if available, else use booking status
    if ($payment_status == 'completed') {
        $display_status = 'Completed';
        $color = 'green';
    } elseif ($payment_status == 'pending') {
        $display_status = 'Pending';
        $color = 'yellow';
    } else {
        $display_status = ucfirst($status);
        $color = match ($status) {
            'completed' => 'green',
            'pending' => 'orange',
            'confirmed' => 'blue',
            'canceled' => 'red',
            default => 'black',
        };
    }

    // Display the status
    echo "<td><span style='color: $color;'>$display_status</span></td>";
} else {
    // Handle case where $row is empty or does not contain the expected keys
    echo "<td>Status not available</td>";
}


$pendingBookingsAmountQuery = "SELECT SUM(pk.price) AS pending_amount FROM bookings b JOIN packages pk ON b.package_id = pk.package_id WHERE b.status = 'pending'";
$pendingBookingsAmountResult = $pdo->query($pendingBookingsAmountQuery);
$pendingBookingsAmountRow = $pendingBookingsAmountResult->fetch(PDO::FETCH_ASSOC);
$pendingBookingsAmount = $pendingBookingsAmountRow['pending_amount'] ?? 0;




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
  <title>booking</title>
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
                        <li class="breadcrumb-item active" aria-current="page">Booking</li>
                    </ol>
                </nav>
                <h1 class="h2">Booking</h1>
                <p>Here we can view all the bookings</p>

  

                <div class="card">
                    <div class="card-header">
                        Booking Details
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped text-center">
                                <thead>
                                    <tr>
                                    <th scope="col">Booking ID</th>
                                        <th scope="col">User ID</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Package Name</th>
                                        <th scope="col">Booking Date</th>
                                        <th scope="col">Booking Time Start</th>
                                        <th scope="col">Booking Time End</th>
                                        <th scope="col">Event Name</th>
                                        <th scope="col">Event Type</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
$query = "SELECT B.booking_id, B.id AS user_id, U.name AS customer_name, P.title AS package_name, B.booking_date, B.booking_date_time_start AS booking_time, B.booking_date_time_end AS booking_end_time, B.booking_event_name, B.booking_event_type, B.status, B.booked_at
FROM Bookings B
JOIN users U ON B.id = U.id
JOIN Packages P ON B.package_id = P.package_id
ORDER BY B.booked_at DESC";

$stmt = $pdo->query($query);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result) > 0) {
    foreach ($result as $row) {
        $status = $row['status'];
        $color = match ($status) {
            'completed' => 'green',
            'pending' => 'orange',
            'confirmed' => 'blue',
            'canceled' => 'red',
            default => 'black',
        };
        echo "<tr>";
        echo "<td>{$row['booking_id']}</td>";
        echo "<td>{$row['user_id']}</td>";
        echo "<td>{$row['customer_name']}</td>";
        echo "<td>{$row['package_name']}</td>";
        echo "<td>{$row['booking_date']}</td>";
        echo "<td>{$row['booking_time']}</td>";
        echo "<td>{$row['booking_end_time']}</td>";
        echo "<td>{$row['booking_event_name']}</td>";
        echo "<td>{$row['booking_event_type']}</td>";
        echo "<td><span style='color: $color;'>{$row['status']}</span></td>";
        echo "<td>{$row['booked_at']}</td>";
        echo "<td class='d-flex align-items-center'>";
        echo "<form action='' method='post' class='d-flex'>";
        echo "<input type='hidden' name='booking_id' value='{$row['booking_id']}'>";
        echo "<select class='form-select me-2' name='update_status'>";
        echo "<option value='pending' " . ($status == 'pending' ? 'selected' : '') . ">Pending</option>";
        echo "<option value='confirmed' " . ($status == 'confirmed' ? 'selected' : '') . ">Confirmed</option>";
        echo "<option value='completed' " . ($status == 'completed' ? 'selected' : '') . ">Completed</option>";
        echo "<option value='canceled' " . ($status == 'canceled' ? 'selected' : '') . ">Canceled</option>";
        echo "</select>";
        echo "<button type='submit' name='update_btn' class='btn btn-primary ml-2 mr-2'>Update</button>";
        echo "</form>";
        echo "<form action='' method='get'>";
        echo "<input type='hidden' name='delete' value='{$row['booking_id']}'>";
        echo "<button type='submit' class='btn btn-danger'>Delete</button>";
        echo "</form>";
        echo "</td>";
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