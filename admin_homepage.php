<?php
@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT COUNT(*) as user_count FROM Users WHERE user_type = 'user'";
    $result = $conn->query($query);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $userCount = $row['user_count'];

    $lastMonth = date('Y-m-d', strtotime('first day of last month'));

    $queryLastMonth = "SELECT COUNT(*) as last_month_count FROM Users WHERE user_type = 'user' AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(:lastMonth, '%Y-%m')";
    $stmtLastMonth = $conn->prepare($queryLastMonth);
    $stmtLastMonth->bindParam(':lastMonth', $lastMonth);
    $stmtLastMonth->execute();
    $rowLastMonth = $stmtLastMonth->fetch(PDO::FETCH_ASSOC);
    $lastMonthCount = $rowLastMonth['last_month_count'];

    if ($lastMonthCount > 0) {
        $percentageIncrease = (($userCount - $lastMonthCount) / $lastMonthCount) * 100;
    } else {
        $percentageIncrease = null;
    }

    $totalRevenueQuery = "SELECT SUM(amount) AS total_revenue FROM payments WHERE payment_status = 'completed'";
    $totalRevenueResult = $conn->query($totalRevenueQuery);
    $totalRevenueRow = $totalRevenueResult->fetch(PDO::FETCH_ASSOC);
    $totalRevenue = $totalRevenueRow['total_revenue'] ?? 0;

    $pendingBookingsQuery = "SELECT COUNT(*) AS pending_count FROM bookings WHERE status = 'pending'";
    $pendingBookingsResult = $conn->query($pendingBookingsQuery);
    $pendingBookingsRow = $pendingBookingsResult->fetch(PDO::FETCH_ASSOC);
    $pendingBookingsCount = $pendingBookingsRow['pending_count'] ?? 0;

    $completedBookingsQuery = "SELECT COUNT(*) AS completed_count FROM bookings WHERE status = 'completed'";
    $completedBookingsResult = $conn->query($completedBookingsQuery);
    $completedBookingsRow = $completedBookingsResult->fetch(PDO::FETCH_ASSOC);
    $completedBookingsCount = $completedBookingsRow['completed_count'] ?? 0;

    $completedBookingsDetailsQuery = "SELECT b.booking_id, pk.title, u.name, pk.price, b.booking_date 
                                     FROM bookings b 
                                     JOIN users u ON b.id = u.id 
                                     JOIN packages pk ON b.package_id = pk.package_id 
                                     WHERE b.status = 'completed' 
                                     ORDER BY b.booking_date DESC 
                                     LIMIT 5";
    $completedBookingsDetailsResult = $conn->query($completedBookingsDetailsQuery);
    $completedBookingsDetails = $completedBookingsDetailsResult->fetchAll(PDO::FETCH_ASSOC);

    $startDate = '2024-05-01';
    $endDate = date('Y-m-d');

    $revenueQuery = "SELECT SUM(amount) AS daily_revenue, DATE(payment_date) as date 
                     FROM payments 
                     WHERE payment_status = 'completed' 
                     AND payment_date BETWEEN :startDate AND :endDate 
                     GROUP BY DATE(payment_date)";
    $stmtRevenue = $conn->prepare($revenueQuery);
    $stmtRevenue->bindParam(':startDate', $startDate);
    $stmtRevenue->bindParam(':endDate', $endDate);
    $stmtRevenue->execute();
    $dailyRevenueData = $stmtRevenue->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RC studio Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
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
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Overview</li>
                    </ol>
                </nav>
                <h1 class="h2">Dashboard</h1>
                <p>This is the admin homepage of our Photo Booth Booking System</p>

                <div class="row my-4">

                    <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                        <div class="card">
                            <h5 class="card-header">Customers</h5>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $userCount; ?></h5>
                                <p class="card-text"><?php echo date('M j, Y', strtotime('first day of last month')); ?> - <?php echo date('M j, Y'); ?>, Philippines</p>
                                <?php if ($percentageIncrease !== null): ?>
                                    <p class="card-text text-success"><?php echo number_format($percentageIncrease, 2); ?>% increase since last month</p>
                                <?php else: ?>
                                    <p class="card-text text-warning">No users in the last month</p>
                                <?php endif; ?>
                            </div>
                        </div>         
                    </div>

                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Revenue</h5>
                            <div class="card-body">
                                <h5 class="card-title">₱<?php echo number_format($totalRevenue, 2); ?></h5>
                                <p class="card-text"><?php echo date('M j, Y', strtotime('first day of last month')); ?> - <?php echo date('M j, Y'); ?>, Philippines</p>
                                <p class="card-text text-success">4.6% increase since last month</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Pending Bookings</h5>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $pendingBookingsCount; ?></h5>
                                <p class="card-text"><?php echo date('M j, Y', strtotime('first day of last month')); ?> - <?php echo date('M j, Y'); ?>, Philippines</p>
                                <p class="card-text text-danger">2.6% decrease since last month</p>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Completed Bookings</h5>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $completedBookingsCount; ?></h5>
                                <p class="card-text"><?php echo date('M j, Y', strtotime('first day of last month')); ?> - <?php echo date('M j, Y'); ?>, Philippines</p>
                                <p class="card-text text-success">2.5% increase since last month</p>
                            </div>
                        </div>
                    </div>


                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-lg-0">
                        <div class="card">
                            <h5 class="card-header">Latest Transactions</h5>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                          <tr>
                                            <th scope="col">Order</th>
                                            <th scope="col">Product</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Date</th>
                                            <th scope="col"></th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($completedBookingsDetails as $booking): ?>
                                            <tr>
                                                <th scope="row"><?php echo htmlspecialchars($booking['booking_id']); ?></th>
                                                <td><?php echo htmlspecialchars($booking['title']); ?></td>
                                                <td><?php echo htmlspecialchars($booking['name']); ?></td>
                                                <td>₱<?php echo number_format($booking['price'], 2); ?></td>
                                                <td><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></td>
                                                <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="admin_booking.php" class="btn btn-block btn-light">View all</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="card">
                            <h5 class="card-header">Revenue since May 1st</h5>
                            <div class="card-body">
                                <div id="traffic-chart"></div>
                            </div>
                        </div>
                    </div>


                <footer class="pt-5 d-flex justify-content-between">
                    <ul class="nav m-0">
                        <li class="nav-item">
                          <a class="nav-link text-secondary" aria-current="page" href="#">Privacy Policy</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link text-secondary" href="#">Terms and conditions</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link text-secondary" href="#">Contact</a>
                        </li>
                      </ul>
                </footer>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
 
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
    var dailyRevenueSeries = <?php echo json_encode(array_column($dailyRevenueData, 'daily_revenue')); ?>;
    var dailyRevenueLabels = <?php echo json_encode(array_column($dailyRevenueData, 'date')); ?>;

    new Chartist.Line('#traffic-chart', {
        labels: dailyRevenueLabels,
        series: [dailyRevenueSeries]
    }, {
        low: 0,
        showArea: true,
        axisY: {
            labelInterpolationFnc: function(value) {
                return '$' + value.toFixed(2);
            }
        }
    });
</script>

</body>
</html>