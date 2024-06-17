<?php
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

// Include the PDO connection
include 'config.php';

$query = "
    SELECT 
        B.booking_id, 
        P.title AS package_title, 
        B.booking_date, 
        B.booking_date_time_start, 
        B.booking_date_time_end, 
        B.booking_event_name, 
        B.booking_event_venue_name, 
        B.booking_event_location_main_address, 
        B.booking_event_location_city, 
        B.status, 
        B.booked_at
    FROM 
        bookings B
    JOIN 
        Packages P ON B.package_id = P.package_id
    WHERE 
        B.id = :user_id
    ORDER BY 
        B.booked_at DESC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
  <style>
    .back-button {
        position: absolute;
        top: 20px;
        left: 20px;
        color: white;
        text-decoration: none;
        font-size: 24px;
    }
    .table-responsive {
      overflow-x: auto;
      width: 100%;
    }
    .table th, .table td {
      white-space: nowrap;
      text-align: center;
    }
    .card-body {
      padding: 20px;
    }
    .btn-disabled {
        pointer-events: none;
        opacity: 0.6;
    }

 
  </style>
  <title>Booking History</title>
</head>
<body class="bg-dark">
    
    <div class="card bg-dark border text-white">
        <a href="user_homepage.php" class="back-button"><i class="fas fa-arrow-left"></i></a>
        <div class="card-header border text-center">
            <h1 class="text-light">Booking History</h1>
        </div>
        <div class="card-body bg-dark">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-warning">
                            <th scope="col">Package Title</th>
                            <th scope="col">Booking Date</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            <th scope="col">Event Name</th>
                            <th scope="col">Venue Name</th>
                            <th scope="col">Location Address</th>
                            <th scope="col">Location City</th>
                            <th scope="col">Status</th>
                            <th scope="col">Booked At</th>
                            <th scope="col">Pay Here!</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if (count($result) > 0) {
                            foreach ($result as $row) {
                                $buttonText = '';
                                $buttonClass = '';
                                $buttonDisabled = '';

                                switch ($row['status']) {
                                    case 'pending':
                                        $buttonText = 'Pay After Booking has been Confirmed';
                                        $buttonClass = 'btn-warning';
                                        $buttonDisabled = 'btn-disabled';
                                        $statusColor = 'yellow';
                                        break;
                                    case 'confirmed':
                                        $buttonText = 'Pay Now';
                                        $buttonClass = 'btn-primary';
                                        $statusColor = 'blue';
                                        break;
                                    case 'completed':
                                        $buttonText = 'Payment Successful';
                                        $buttonClass = 'btn-success';
                                        $buttonDisabled = 'btn-disabled';
                                        $statusColor = 'green';
                                        break;
                                    case 'canceled':
                                        $buttonText = 'Cancelled Booking';
                                        $buttonClass = 'btn-danger';
                                        $buttonDisabled = 'btn-disabled';
                                        $statusColor = 'red';
                                        break;
                                }

                                echo "<tr>";
                                echo "<td>{$row['package_title']}</td>";
                                echo "<td>{$row['booking_date']}</td>";
                                echo "<td>{$row['booking_date_time_start']}</td>";
                                echo "<td>{$row['booking_date_time_end']}</td>";
                                echo "<td>{$row['booking_event_name']}</td>";
                                echo "<td>{$row['booking_event_venue_name']}</td>";
                                echo "<td>{$row['booking_event_location_main_address']}</td>";
                                echo "<td>{$row['booking_event_location_city']}</td>";
                                echo "<td style='color: {$statusColor};'>{$row['status']}</td>";
                                echo "<td>{$row['booked_at']}</td>";
                                echo "<td><a href='payment.php?booking_id={$row['booking_id']}' class='btn {$buttonClass} {$buttonDisabled}'>{$buttonText}</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11'>No bookings found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php
$stmt->closeCursor();
$conn = null;
?>

</body>
</html>
