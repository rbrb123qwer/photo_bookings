<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['update_payment'])) {
    $payment_id = $_POST['payment_id'];
    $update_status = $_POST['update_status'];
    $stmt = $pdo->prepare("UPDATE `payments` SET payment_status = :update_status WHERE payment_id = :payment_id");
    $stmt->bindParam(':update_status', $update_status);
    $stmt->bindParam(':payment_id', $payment_id);
    $stmt->execute();
    $message[] = 'Payment status has been updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM `payments` WHERE payment_id = :delete_id");
    $stmt->bindParam(':delete_id', $delete_id);
    $stmt->execute();
    header('location:admin_payment.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
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
                        <li class="breadcrumb-item"><a href="admin_homepage.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page" href="admin_payment.php">Payment</li>
                    </ol>
                </nav>

                <div class="container-fluid text-center mt-3 mb-4">
                    <h2 class="text-white fw-bold">Payment History</h2>
                </div>

                <div class="container">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Payment ID</th>
                                            <th>Booking ID</th>
                                            <th>Amount</th>
                                            <th>Payment Method</th>
                                            <th>Payment Date</th>
                                            <th>Payment Status</th>
                                            <th>Image</th>
                                            <th>Reference Number</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    <?php
    $select_payments = $pdo->query("SELECT * FROM `payments` ORDER BY payment_date DESC");
    if ($select_payments->rowCount() > 0) {
        while ($fetch_payments = $select_payments->fetch(PDO::FETCH_ASSOC)) {
?>
        <tr>
            <td><?php echo $fetch_payments['payment_id']; ?></td>
            <td><?php echo $fetch_payments['booking_id']; ?></td>
            <td><?php echo $fetch_payments['amount']; ?></td>
            <td><?php echo $fetch_payments['payment_method']; ?></td>
            <td><?php echo $fetch_payments['payment_date']; ?></td>
            <td>
                <?php 
                $payment_status = $fetch_payments['payment_status']; 
                $color = '';
                if ($payment_status == 'completed') {
                    $color = 'green';
                } elseif ($payment_status == 'pending') {
                    $color = 'red';
                }
                ?>
                                                        <span style="color: <?php echo $color; ?>"><?php echo $payment_status; ?></span>
                                                    </td>
                                                    <td><img src="<?php echo $fetch_payments['image']; ?>" width="100" height="100" alt="Payment Image"></td>
                                                    <td><?php echo $fetch_payments['reference_number']; ?></td>
                                                    <td>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="payment_id" value="<?php echo $fetch_payments['payment_id']; ?>">
                                                            
                                                            <select name="update_status" class="form-select mb-2">
                                                                <option value="pending" <?php if ($fetch_payments['payment_status'] == 'pending') echo 'selected'; ?>>Not Paid</option>
                                                                <option value="completed" <?php if ($fetch_payments['payment_status'] == 'completed') echo 'selected'; ?>>Paid</option>
                                                            </select>

                                                            <input type="submit" name="update_payment" value="Update" class="btn btn-success me-2 p-2 m-2">

                                                            <a href="admin_payment.php?delete=<?php echo $fetch_payments['payment_id']; ?>" class="btn btn-danger m-2" onclick="return confirm('Delete this payment?');">Delete</a>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="9" class="text-center">No payments yet!</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Bootstrap 5 JavaScript dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>
</html>
