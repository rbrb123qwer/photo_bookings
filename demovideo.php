<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Video Player</title>
<link rel="icon" href="images/title-img.png">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
<script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">

<style>
    body {
        background-color: black;
    }
    .video-container {
        font-family: sans-serif;
        text-align: center;
    }
    video {
        width: 90vw;
        height: 90vh;
    }
    .back-button {
        position: absolute;
        top: 20px;
        left: 20px;
        color: white;
        text-decoration: none;
        font-size: 24px;
        
    }

    h1, h2{
        color: white;
    }
</style>
</head>
<body>
<a href="user_homepage.php" class="back-button"><i class="fas fa-arrow-left"></i></a>
<div class="video-container">
    <h1>HOW TO USE OUR WEBSITE?</h1>
    <h2>English</h2>
    <video controls>
        <source src="video/bookinghowvideoo.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>


    <h1>PAANO GAMITIN ANG AMING WEBSITE?</h1>
    <h2>Tagalog</h2>
    <video controls>
        <source src="video/tagalogtutorial.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>
</body>
</html>