<?php

@include 'config.php';

session_start();

$alertMessage = "";
$alertClass = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Securely hash the password
    $user_type = 'user'; // Default user type

    // Check for existing email
    $checkQuery = "SELECT * FROM users WHERE email=?";
    $stmt = $pdo->prepare($checkQuery);
    $stmt->execute([$email]);
    $result = $stmt->fetchAll();

    if (count($result) > 0) {
        $alertMessage = "Email already exists.";
        $alertClass = "alert-danger";
    } else {
        // Use prepared statements to insert data
        $sql = "INSERT INTO users (name, phone, email, password, user_type, created_at) VALUES (?, ?, ?, ?, ?, CURDATE())";
        $stmt = $pdo->prepare($sql);
        $full_name = $first_name . ' ' . $last_name;
        $stmt->execute([$full_name, $phone_number, $email, $password, $user_type]);

        if ($stmt->rowCount() > 0) {
            $alertMessage = "Registered Successfully";
            $alertClass = "alert-success";
        } else {
            $alertMessage = "Error: " . $stmt->errorInfo()[2];
            $alertClass = "alert-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <link rel="stylesheet" href="css/login_register.css">
    <title>Register</title>

<style>
 html,body {
    height: 100%;
}
body{
    background-color: #ffffff;
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

<?php if (!empty($alertMessage)): ?>
        <div class="alert-container">
            <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                <?php echo $alertMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
<?php endif; ?>



    <div class="container " id="screen">
        <div class="row">   
            <div class="col-lg-6 my-auto">  
                <div id="login">
                    <form method="POST" action="">   
                        <h4 class="text-black">
                            Welcome to Rc Studio PhotoBooth 
                        </h4>
                        <p class="text-black">
                            Please sign up to use our platform
                        </p>

                        <div class="row mb-2 g-1">
                            <div class="col">
                                <div class="form-floating text-black">
                                    <input type="text" class="form-control" id="floatingFirstName" name="first_name" placeholder="Enter First Name" required>
                                    <label for="floatingFirstName">First Name</label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-floating text-black">
                                    <input type="text" class="form-control" id="floatingLastName" name="last_name" placeholder="Enter Last Name" required>
                                    <label for="floatingLastName">Last Name</label>
                                </div>
                            </div>

                        </div>

                        <div class="form-floating mb-2 text-black">
                            <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="Enter Email" required>
                            <label for="floatingEmail">Email</label>
                        </div>

                        <div class="form-floating mb-2 text-black">
                            <input type="password" class="form-control" id="floatingEmail" name="password" placeholder="Enter Password" required>
                            <label for="floatingEmail">Password</label>
                        </div>

                        <div class="form-floating mb-2 text-black">
                            <input type="text" class="form-control" id="floatingPhoneNumber" name="phone_number" placeholder="Enter Phone Number" required>
                            <label for="floatingPhoneNumber">Phone Number</label>
                        </div>

                        <button type="submit" class="btn btn-login">
                            SIGN UP
                        </button>
                    </form>  <!-- Form End -->
                    
                    <!--Sign IN-->
                    <div class="text-center text-muted mt-2 mb-0" style="font-size:14px;"> 
                        Already have an account? 
                        <a href="login.php" class="text-primary text-decoration-none">
                            Sign In
                        </a>
                    </div>  
                    <!--Sign In End-->
                </div>
            </div>  
            
            
        </div>  <!--row End-->

     
      

    </div> <!-- Container End-->

 <!-- Option 1: Bootstrap Bundle with Popper -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>    
</body>
</html>
