<?php

@include 'config.php';

session_start();

if (isset($_POST['change_password_btn'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $current_password = filter_var($_POST['current_password'], FILTER_SANITIZE_STRING);
    $new_password = filter_var($_POST['new_password'], FILTER_SANITIZE_STRING);
    $confirm_password = filter_var($_POST['confirm_password'], FILTER_SANITIZE_STRING);

    $select_user = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $select_user->bindParam(':email', $email);
    $select_user->execute();

    if ($select_user->rowCount() > 0) {
        $row = $select_user->fetch(PDO::FETCH_ASSOC);
        if (password_verify($current_password, $row['password'])) {
            if ($new_password === $confirm_password) {
                $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
                $update_password = $pdo->prepare("UPDATE users SET password = :new_password_hashed WHERE email = :email");
                $update_password->bindParam(':new_password_hashed', $new_password_hashed);
                $update_password->bindParam(':email', $email);
                if ($update_password->execute()) {
                    $message[] = 'Password updated successfully!';
                } else {
                    $message[] = 'Failed to update password!';
                }
            } else {
                $message[] = 'New password and confirm password do not match!';
            }
        } else {
            $message[] = 'Current password is incorrect!';
        }
    } else {
        $message[] = 'No user found with this email!';
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login_register.css">
    <title>Change Password</title>

<style>
html, body {
    height: 100%;
    background-color: #ffffff;
    color: #000000;
}
body {
    display: flex;
}
#screen {
    background-color: #f0f0f0;
    border-radius: 10px;
    padding: 1.5rem;
    margin: auto;
    position: relative; 
}
#login {
    margin: 20px;
}
#login .form-control {
    border-radius: 10px;
    border: 1px solid #000000;
    background-color: #ffffff;
    color: #000000;
}
.btn-login {
    margin-top: 15px;
    background-color: #000000;
    color: #ffffff;
    border-radius: 10px;
}
.btn-login:hover {
    background-color: #333333;
}
.alert-container {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: auto;
    z-index: 1000;
}
.back-arrow {
    position: absolute;
    top: 20px;
    left: 20px;
    color: #000000;
    font-size: 1.5rem;
    text-decoration: none;
}
@media (max-width: 576px) {
    .alert-container {
        top: 50px;
    }
    #screen {
        padding: 1rem; 
    }
    #login {
        margin: 10px;
    }
}
</style>

</head>
<body>

<?php if (!empty($message)): ?>
 <div class="alert-container">
     <div class="alert alert-warning alert-dismissible fade show" role="alert">
         <?php echo implode('<br>', $message); ?>
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
     </div>
 </div>
<?php endif; ?>


    <div class="container" id="screen">
        <a href="login.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
        <div class="row">
            <div class="col-lg-6 my-auto">
                <div id="login">

                    <form method="POST" action="">
                        <h4>
                            Change Your Password
                        </h4>
                        <div class="form-floating mb-3 text-black">
                            <input type="email" class="form-control" name="email" id="floatingInput" placeholder="Enter Email" required>
                            <label for="floatingInput">
                                Enter Email
                            </label>
                        </div>

                        <div class="form-floating mb-3 text-black">
                            <input type="password" class="form-control" name="current_password" id="floatingCurrentPassword" placeholder="Current Password" required>
                            <label for="floatingCurrentPassword">
                                Current Password
                            </label>
                        </div>

                        <div class="form-floating mb-3 text-black">
                            <input type="password" class="form-control" name="new_password" id="floatingNewPassword" placeholder="New Password" required>
                            <label for="floatingNewPassword">
                                New Password
                            </label>
                        </div>

                        <div class="form-floating mb-3 text-black">
                            <input type="password" class="form-control" name="confirm_password" id="floatingConfirmPassword" placeholder="Confirm Password" required>
                            <label for="floatingConfirmPassword">
                                Confirm Password
                            </label>
                        </div>

                        <button type="submit" class="btn btn-login" name="change_password_btn">
                            CHANGE PASSWORD
                        </button>
                    </form>
                </div>
            </div>
         
        </div>
    </div>

    <script src="https://kit.fontawesome.com/b931534883.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>