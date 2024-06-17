<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if ($booking_id == 0) {
    echo "Invalid booking ID.";
    exit();
}

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
        B.booked_at, 
        P.price AS package_price
    FROM 
        bookings B
    JOIN 
        Packages P ON B.package_id = P.package_id
    WHERE 
        B.booking_id = ?";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$booking_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            echo "Booking not found.";
            exit();
        }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $amount = $row['package_price'];
    $image = $_FILES['payment_image'];
    $reference_number = isset($_POST['reference_number']) ? $_POST['reference_number'] : 'N/A';  // Default value or handle accordingly

    $target_dir = "images/";
    $target_file = $target_dir . basename($image["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($image["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $upload_ok = 0;
    }

    // Check file size (e.g., 5MB maximum)
    if ($image["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $upload_ok = 0;
    }

    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = 0;
    }

    if ($upload_ok == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            $insert_query = "
    INSERT INTO payments (booking_id, amount, payment_method, image, reference_number)
    VALUES (:booking_id, :amount, :payment_method, :image, :reference_number)";
$insert_stmt = $pdo->prepare($insert_query);
$insert_stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
$insert_stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
$insert_stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
$insert_stmt->bindParam(':image', $target_file, PDO::PARAM_STR);
$insert_stmt->bindParam(':reference_number', $reference_number, PDO::PARAM_STR);

            if ($insert_stmt->execute()) {
                header('Location: booking_history.php');
                exit();
            } else {
                echo "Error: " . $insert_stmt->errorInfo()[2];
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
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
    .receipt {
      max-width: 800px;
      margin: auto;
      padding: 20px;
      background: #fff;
      border-radius: 10px;
    }
    .receipt h1 {
      text-align: center;
      margin-bottom: 20px;
    }
    .receipt table {
      width: 100%;
      margin-bottom: 20px;
    }
    .receipt table th, .receipt table td {
      padding: 10px;
      text-align: left;
    }
    .separator {
      border-top: 2px solid #333;
      margin: 20px 0;
    }
    .total {
      font-size: 1.2em;
      font-weight: bold;
      text-align: right;
    }
    .back-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      color: #fff;
      font-size: 24px;
    }
  </style>
  <title>Payment Receipt</title>
</head>
<body class="bg-dark">

<a href="booking_history.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>

<?php
if (isset($message)) {
    echo '
    <div class="alert alert-' . $message['type'] . ' alert-dismissible fade show text-center fw-bold alert-fixed" role="alert">
        <span>' . $message['text'] . '</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    ';
}
?>

<div class="receipt">
    <h1>Payment Receipt</h1>
    <table class="table table-bordered">
        <tr>
            <th>Booking ID</th>
            <td><?php echo $row['booking_id']; ?></td>
        </tr>
        <tr>
            <th>Package Title</th>
            <td><?php echo $row['package_title']; ?></td>
        </tr>
        <tr>
            <th>Booking Date</th>
            <td><?php echo $row['booking_date']; ?></td>
        </tr>
        <tr>
            <th>Start Time</th>
            <td><?php echo $row['booking_date_time_start']; ?></td>
        </tr>
        <tr>
            <th>End Time</th>
            <td><?php echo $row['booking_date_time_end']; ?></td>
        </tr>
        <tr>
            <th>Event Name</th>
            <td><?php echo $row['booking_event_name']; ?></td>
        </tr>
        <tr>
            <th>Venue Name</th>
            <td><?php echo $row['booking_event_venue_name']; ?></td>
        </tr>
        <tr>
            <th>Location Address</th>
            <td><?php echo $row['booking_event_location_main_address']; ?></td>
        </tr>
        <tr>
            <th>Location City</th>
            <td><?php echo $row['booking_event_location_city']; ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <tr>
            <th>Booked At</th>
            <td><?php echo $row['booked_at']; ?></td>
        </tr>
        <tr>
            <th>Amount</th>
            <td><?php echo '₱' . $row['package_price']; ?></td>
        </tr>
    </table>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select id="payment_method" name="payment_method" class="form-control">
                <option value="Gcash">Gcash (0000-000-0000) - (Jang Wonyoung) </option>
            </select>
        </div>
        <div class="form-group">
            <label for="payment_image">Upload Payment Image</label>
            <input type="file" id="payment_image" name="payment_image" class="form-control" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="reference_number">Reference Number</label>
            <input type="number" id="reference_number" name="reference_number" class="form-control" placeholder="Enter Reference Number" required>
        </div>
        <p class="text-danger">*Following your payment, please allow up to 24 hours for your booking to be processed and finalized. Upon completion, your booking will be confirmed and recorded in our schedule.*</p>
        <div class="separator"></div>
        <div class="total">
            Total: <?php echo '₱' . $row['package_price']; ?>
        </div>
        <button type="submit" class="btn btn-primary text-white text-center" onclick="showNotification()">Confirm Payment</button>

    </form>
</div>

<script>
   function showNotification() {
    var inputs = document.getElementsByTagName("input");
    var isEmpty = false;

    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].value === "") {
            isEmpty = true;
            break;
        }
    }

    if (isEmpty) {
        alert("Please fill out all fields.");
    } else {
        alert("Payment Successful");
    }
}
</script>


<?php
$stmt->closeCursor();
$conn = null;
?>

</body>
</html>
