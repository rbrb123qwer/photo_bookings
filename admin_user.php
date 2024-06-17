<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    $stmt = $conn->prepare("UPDATE orders SET payment_status = :update_payment WHERE id = :order_id");
    $stmt->bindParam(':update_payment', $update_payment);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $message[] = 'Payment status has been updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM payments WHERE booking_id IN (SELECT booking_id FROM bookings WHERE id = :delete_id)");
    $stmt->bindParam(':delete_id', $delete_id);
    $stmt->execute();

    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = :delete_id");
    $stmt->bindParam(':delete_id', $delete_id);
    $stmt->execute();

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :delete_id");
    $stmt->bindParam(':delete_id', $delete_id);
    $stmt->execute();

    header('location:admin_user.php');
    exit();
}

$search_query = "";
$limit = null;
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

if (isset($_POST['entries'])) {
    $limit = intval($_POST['entries']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Customers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap5.min.css">

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
                        <li class="breadcrumb-item active" aria-current="page">Customer</li>
                    </ol>
                </nav>
                <h1 class="h2">Customer</h1>
                <p>In this page we can view all the users including customer and admin</p>

                <form method="post" class="mb-4">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search customers..." value="<?php echo htmlspecialchars($search_query); ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                    <div class="form-group">
                        <label for="entries">Show entries:</label>
                        <select name="entries" class="form-select" id="entries" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                            <option value="25" <?php if ($limit == 25) echo 'selected'; ?>>25</option>
                            <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
                            <option value="100" <?php if ($limit == 100) echo 'selected'; ?>>100</option>
                        </select>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="customerTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Email</th>
                                <th scope="col">Password</th>
                                <th scope="col">User Type</th>
                                <th scope="col">Registered Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT id, name, phone, email, password, user_type, created_at FROM users";
                            if ($search_query) {
                                $query .= " WHERE name LIKE '%$search_query%' OR phone LIKE '%$search_query%' OR email LIKE '%$search_query%' OR user_type LIKE '%$search_query%'";
                            }
                            if ($limit) {
                                $query .= " LIMIT $limit";
                            }
                            $stmt = $pdo->prepare($query);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($result) > 0) {
                                foreach ($result as $row) {
                                    echo "<tr>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td>{$row['name']}</td>";
                                    echo "<td>{$row['phone']}</td>";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td>{$row['password']}</td>";
                                    echo "<td>{$row['user_type']}</td>";
                                    echo "<td>{$row['created_at']}</td>";
                                    echo "<td>";
                                    echo "<a href='admin_edit_user.php?id={$row['id']}' class='btn btn-primary btn-sm mr-2'>Update</a>";
                                    echo "<a href='admin_user.php?delete={$row['id']}' onclick=\"return confirm('Delete this user?');\" class='btn btn-danger btn-sm'>Delete</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXlqk3o4zFsTF24abPYHu6j5JdZwpxtf+gx9rjovb5A1RWt4xk/tPBI5J7m3" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-7Ryfvc0yaKRjaJ6A+v1+os6z3biQ9bq9vv43TfW85n9kAVL+aEn2pZVgkOA0yYEN" crossorigin="anonymous"></script>
</body>
</html>