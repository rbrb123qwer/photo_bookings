<?php
@include 'config.php';

session_start();

if (!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit(); // Stop further execution
}

// Fetch user information based on user_id
$user_id = $_SESSION['user_id'];

$query = "SELECT name, phone, email FROM users WHERE user_type = 'user' AND id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$userName = $row['name'];
$userPhone = $row['phone'];
$userEmail = $row['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST['package'];
    $bookingDate = $_POST['bookingDate'];
    $bookingTime = $_POST['bookingTime'];
    $bookingEventDateTimeEnd = $_POST['bookingEventDateTimeEnd'];
    $event = $_POST['event'];
    $location = $_POST['location'];
    $venueName = $_POST['bookingEventVenueName'];
    $locationCity = $_POST['bookingEventLocationCity'];
    $eventType = $_POST['bookingEventType'];

    // Check if the selected city is "Others" and use the value from the "Other City" input field
    if ($locationCity === "Others") {
        $locationCity = $_POST['otherCity'];
    }

    // Check if the selected event type is "Others" and use the value from the "Other Event Type" input field
    if ($eventType === "Others") {
        $eventType = $_POST['otherEventType'];
    }

    // Fetch package_id based on title
    $query = "SELECT package_id FROM Packages WHERE title = :title";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $package_id = $row['package_id'];

    // Prepare insert statement
    $query = "INSERT INTO bookings (id, package_id, booking_date, booking_date_time_start, booking_date_time_end, booking_event_name, booking_event_type, booking_event_location_main_address, booking_event_venue_name, booking_event_location_city) VALUES (:user_id, :package_id, :bookingDate, :bookingTime, :bookingEventDateTimeEnd, :event, :eventType, :location, :venueName, :locationCity)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':package_id', $package_id);
    $stmt->bindParam(':bookingDate', $bookingDate);
    $stmt->bindParam(':bookingTime', $bookingTime);
    $stmt->bindParam(':bookingEventDateTimeEnd', $bookingEventDateTimeEnd);
    $stmt->bindParam(':event', $event);
    $stmt->bindParam(':eventType', $eventType);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':venueName', $venueName);
    $stmt->bindParam(':locationCity', $locationCity);

    // Execute the statement
    if ($stmt->execute()) {
        // Insert successful, redirect user
        header("Location: user_homepage.php");
        exit(); // Stop further execution
    } else {
        // Insert failed, handle error
        echo "Error: " . $stmt->errorInfo();
    }
}

// Fetch package titles
$query = "SELECT title FROM Packages";
$stmt = $pdo->prepare($query);
$stmt->execute();
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <title>RC Studio Booking</title>
</head>
<body class="bg-dark">
<a href="user_homepage.php" class="back-button"><i class="fas fa-arrow-left"></i></a>
  <div class="container">
    <div class="card glass-card shadow-sm mt-5">
      <div class="card-body">
        <h1 class="mt-4 text-center">Booking Form</h1>
        <hr>

        <!-- User Information -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
            <!-- User Information -->
            <div class="mb-3">
                <label for="userName" class="form-label">Name</label>
                <input type="text" class="form-control" id="userName" value="<?php echo $userName; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="userPhone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="userPhone" value="<?php echo $userPhone; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="userEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="userEmail" value="<?php echo $userEmail; ?>" readonly>
            </div>

            <hr>

            <!-- Booking Form -->
            <h2 class="mt-4 mb-3 text-center">Booking Details</h2>
            <div class="mb-3">
                <label for="package" class="form-label">Package: </label>
                <select class="form-select" id="package" name="package">
                    <?php foreach ($packages as $package) : ?>
                        <option value="<?php echo $package['title']; ?>"><?php echo $package['title']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="bookingDate" class="form-label">Booking Date</label>
                <input type="date" class="form-control" id="bookingDate" name="bookingDate" min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="mb-3">
                <label for="bookingTime" class="form-label">Booking Time Start</label>
                <input type="time" class="form-control" id="bookingTime" name="bookingTime" placeholder="00:00 am/pm">
            </div>

            <div class="mb-3">
                <label for="bookingEventDateTimeEnd" class="form-label">Booking Time End</label>
                <input type="time" class="form-control" id="bookingEventDateTimeEnd" name="bookingEventDateTimeEnd" placeholder="00:00 am/pm">
            </div>

            <div class="mb-3">
                <label for="event" class="form-label">Event Name</label>
                <input type="text" class="form-control" id="event" name="event">
            </div>

            <div class="mb-3">
                <label for="bookingEventVenueName" class="form-label">Venue Name</label>
                <input type="text" class="form-control" id="bookingEventVenueName" name="bookingEventVenueName">
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location Main Address</label>
                <input type="text" class="form-control" id="location" name="location">
            </div>

            <div class="mb-3">
                <label for="bookingEventLocationCity" class="form-label">Location City</label>
                <select class="form-select" id="bookingEventLocationCity" name="bookingEventLocationCity">
                    <option value="Antipolo City (Rizal)">Antipolo City (Rizal)</option>
                    <option value="Bacoor City (Cavite)">Bacoor City (Cavite)</option>
                    <option value="Batangas City (Batangas)">Batangas City (Batangas)</option>
                    <option value="Biñan City (Laguna)">Biñan City (Laguna)</option>
                    <option value="Cabuyao City (Laguna)">Cabuyao City (Laguna)</option>
                    <option value="Calamba City (Laguna)">Calamba City (Laguna)</option>
                    <option value="Cavite City (Cavite)">Cavite City (Cavite)</option>
                    <option value="Dasmariñas City (Cavite)">Dasmariñas City (Cavite)</option>
                    <option value="General Trias City (Cavite)">General Trias City (Cavite)</option>
                    <option value="Lipa City (Batangas)">Lipa City (Batangas)</option>
                    <option value="San Pablo City (Laguna)">San Pablo City (Laguna)</option>
                    <option value="Santa Rosa City (Laguna)">Santa Rosa City (Laguna)</option>
                    <option value="Tanauan City (Batangas)">Tanauan City (Batangas)</option>
                    <option value="Tayabas City (Quezon)">Tayabas City (Quezon)</option>
                    <option value="Trece Martires City (Cavite)">Trece Martires City (Cavite)</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div id="otherCityInput" class="mb-3 d-none">
                <label for="otherCity" class="form-label">Other City</label>
                <input type="text" class="form-control" id="otherCity" name="otherCity" placeholder="Enter other city">
            </div>

            <div class="mb-3">
                <label for="bookingEventType" class="form-label">Event Type</label>
                <select class="form-select" id="bookingEventType" name="bookingEventType">
                    <option value="Birthday">Birthday</option>
                    <option value="Wedding">Wedding</option>
                    <option value="Party">Party</option>
                    <option value="Valentine's Day">Valentine's Day</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div id="otherEventTypeInput" class="mb-3 d-none">
                <label for="otherEventType" class="form-label">Other Event Type</label>
                <input type="text" class="form-control" id="otherEventType" name="otherEventType" placeholder="Enter other event type">
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>

        <script>
            function validateForm() {
                var package = document.getElementById('package').value;
                var bookingDate = document.getElementById('bookingDate').value;
                var bookingTime = document.getElementById('bookingTime').value;
                var bookingEventDateTimeEnd = document.getElementById('bookingEventDateTimeEnd').value;
                var event = document.getElementById('event').value;
                var location = document.getElementById('location').value;
                var venueName = document.getElementById('bookingEventVenueName').value;
                var locationCity = document.getElementById('bookingEventLocationCity').value;
                var eventType = document.getElementById('bookingEventType').value;

                if (locationCity === "Others") {
                    locationCity = document.getElementById('otherCity').value;
                }

                if (eventType === "Others") {
                    eventType = document.getElementById('otherEventType').value;
                }

                if (package === "" || bookingDate === "" || bookingTime === "" || bookingEventDateTimeEnd === "" || event === "" || location === "" || venueName === "" || locationCity === "" || eventType === "") {
                    alert("Please fill out all the fields.");
                    return false;
                } else {
                    alert("Booking successful!");
                    return true;
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                var locationCitySelect = document.getElementById('bookingEventLocationCity');
                var otherCityInput = document.getElementById('otherCityInput');
                var otherCityInputField = document.getElementById('otherCity');

                locationCitySelect.addEventListener('change', function() {
                    if (locationCitySelect.value === 'Others') {
                        otherCityInput.classList.remove('d-none');
                        otherCityInputField.required = true;
                    } else {
                        otherCityInput.classList.add('d-none');
                        otherCityInputField.required = false;
                    }
                });

                var eventTypeSelect = document.getElementById('bookingEventType');
                var otherEventTypeInput = document.getElementById('otherEventTypeInput');
                var otherEventTypeInputField = document.getElementById('otherEventType');

                eventTypeSelect.addEventListener('change', function() {
                    if (eventTypeSelect.value === 'Others') {
                        otherEventTypeInput.classList.remove('d-none');
                        otherEventTypeInputField.required = true;
                    } else {
                        otherEventTypeInput.classList.add('d-none');
                        otherEventTypeInputField.required = false;
                    }
                });
            });
        </script>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-datatables@1.10.21/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap5.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>
