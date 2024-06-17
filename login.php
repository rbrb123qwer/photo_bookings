"<?php

@include 'config.php';

session_start();

if(isset($_POST['login_btn'])){
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = $pdo->quote($filter_email); // Use $pdo instead of $conn
    $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);

    $select_users = $pdo->query("SELECT * FROM `users` WHERE email = $email");
    
    if($select_users->rowCount() > 0){
        $row = $select_users->fetch(PDO::FETCH_ASSOC);
        
        if(password_verify($filter_pass, $row['password'])){
            if($row['user_type'] == 'admin'){
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id'];
                header('location:admin_homepage.php');
            }elseif($row['user_type'] == 'user'){
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];
                header('location:user_homepage.php');
            }else{
                $message[] = 'No user found!';
            }
        }else{
            $message[] = 'Incorrect email or password!';
        }
    }else{
        $message[] = 'Incorrect email or password!';
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
    <title>Login</title>

<style>
  html,body {
    height: 100%;
}
body{
    background-color: white;
    background-size: cover;
    display: flex;
    



}
#screen{
    background-color: white;
    border-radius: 30px;
    padding: 1.5rem;
    margin-top: 100px;
    margin-left: 450px;
    margin-right: auto;
    align-items: center;

   

    
}
#login{
    margin: 20px;
}
#login .form-control{
    border-radius: 20px;
}
.btn-login{
    margin-top: 15px;
    background-image: linear-gradient(90deg,#8B33FF,#6A00F5);
    width: 100%;
    height: 40px;
    color:white;
    border-radius: 20px;
}
.btn-login:hover{
    color:white;
    box-shadow:0 2rem 3rem rgba(0,0,0,.175)!important;
}
.other-login{
    margin:20px 0 20px;
    text-align: center;
}
.other-login .btn-other-login{
    border:1px solid #ced4da;
    border-radius: 20px;
   margin: 5px 4px 5px;
}
.other-login .btn-other-login:hover{
    background-color: #ced4da;
}

.dev{
    margin-top: 5px;
}
.dev a,.n-psw a {
    text-decoration: none;
}

.row.g-1 {
    gap: 0.2rem; 
}

.alert-container {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: auto;
    z-index: 1000;
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
     <?php foreach ($message as $msg): ?>
         <div class="alert alert-warning alert-dismissible fade show" role="alert">
             <?php echo $msg; ?>
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>
     <?php endforeach; ?>
 </div>
<?php endif; ?>


    <div class="container" id="screen">
        <div class="row">
            <div class="col-lg-6 my-auto">
                <div id="login">

                    <form method="POST" action="">
                        <h4>
                            Welcome to Rc Studio Photobooth
                        </h4>
                        <p class="text-muted">
                            Please login to use the platform
                        </p>
                        <div class="form-floating mb-3 text-black">
                            <input type="email" class="form-control" name="email" id="floatingInput" placeholder="Enter Email" required>
                            <label for="floatingInput">
                                Enter Email
                            </label>
                        </div>

                        <div class="form-floating mb-2 text-black">
                            <input type="password" class="form-control" name="pass" id="floatingPassword" placeholder="Enter Password" required>
                            <label for="floatingPassword">
                                Enter Password
                            </label>

                        </div>
                        <div class="n-psw text-end">
                            <a href="change_password.php" class="text-primary">
                                <span style="font-size:14px;">
                                    Forget Password?
                                </span>
                            </a>
                        </div>
                        <button type="submit" class="btn btn-login" name = "login_btn">
                            SIGN IN
                        </button>
                    </form>
                    <div class="text-center text-muted mt-2 mb-0" style="font-size:14px;"> 
                        Don't have an account? 
                        <a href="register.php" class="text-primary text-decoration-none">
                            Sign up
                        </a>
                    </div>
                    <div class="other-login">
                        <button class="btn btn-other-login btn-light">
                            <img src="img/google-icon.png" alt="Google-icon">
                            Login with Google
                        </button>
                        <button class="btn btn-other-login btn-primary">
                            <img src="img/facebook-icon.png" alt="Facebook-icon">
                            Login with Facebook
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>