<?php
@include 'config.php';



try {
    $sql = "SELECT * FROM packages";
    $stmt = $pdo->query($sql);
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
  <title>RC Studio</title>
  <style>
    .home {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
    }
    .camera-img {
      width: 100%;
      max-width: 350px;
    }
    .mission-text {
      margin-top: 20px;
    }
   
    @media (max-width: 768px) {
      .mission-text {
        text-align: center;
        margin-top: 20px;
      }
      .home-heading {
        font-size: 2.5rem;
      }
      .home-par {
        font-size: 1.2rem;
      }
    }
    @media (max-width: 576px) {
      .home-heading {
        font-size: 2rem;
      }
      .home-par {
        font-size: 1rem;
      }
    }

    .text-info {
      cursor: pointer;
    }

    .nav-link.active {
      background-color: #40cce1;
      color: white !important;
      border-radius: 15px;
    }
  </style>
</head>
<body>
  
  <!-- header -->
  <header>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top nav-menu">
      <a href="#" class="navbar-brand text-light text-uppercase"><span class="h3 font-weight-bold text-dark">RC </span><br><span class="h3 text-dark">Studio Photobooth</span></a>
      <button class="navbar-toggler nav-button" type="button" data-toggle="collapse" data-target="#myNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end font-weight-bold" id="myNavbar">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="#" class="nav-link m-2 menu-item">Home</a>
          </li>
          <li class="nav-item">
            <a href="#about" class="nav-link m-2 menu-item">About</a>
          </li>
          <li class="nav-item">
            <a href="#works" class="nav-link m-2 menu-item">Works</a>
          </li>
          <li class="nav-item">
            <a href="#packages" class="nav-link m-2 menu-item">Packages</a>
          </li>
          <li class="nav-item">
            <a href="#contacts" class="nav-link m-2 menu-item">Contact</a>
          </li>
          <li class="nav-item">
            <a href="demovideo.php" class="nav-link m-2 menu-item">Demo Video</a>
          </li>
          <li class="nav-item">
            <a href="booking_history.php" id="bookingHistoryLink" class="nav-link m-2 menu-item">Booking History</a>
          </li>
          <li class="nav-item">
            <a href="booking.php"  id="bookNowBtn" class="btn btn-danger nav-link m-2 menu-item-btn">Book Now!</a>
          </li>
          <li>
            <div class="dropdown">
              <button class="btn btn-primary dropdown-toggle mt-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                  Hello, Signup Now!
              </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="register.php">Sign up</a></li>
                    <li><a class="dropdown-item" href="login.php">Sign In</a></li>
                </ul>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End of navbar -->

    <!-- Home -->
    <div class="text-light text-md-right text-center home">
      <h1 class="display-4 home-heading text-dark">Welcome to<br><span class="display-3 text-uppercase text-dark font-weight-bold">RC <span class="display-4 home-heading text-dark">Studio Photobooth</span> </span><span class="display-3 text-dark"></span></h1>
      <p class="lead home-par">Every picture tells a story. Let us help tell yours.</p>
    </div>
    <!-- End of home -->
  </header>
  <!-- End of Header -->

  <!-- About -->
  <section class="p-5 about" id="about">
    <div class="container-fluid">
      <!-- Title -->
      <div class="row text-white text-center">
        <div class="col m-4">
          <h1 class="display-4 mb-4">About Us</h1>
          <div class="underline mb-4"></div>
          <p class="lead">Welcome to RC Studio Photobooth, your premier destination for capturing unforgettable moments with style and fun. Based in Batangas, we specialize in providing high-quality photobooth services for all types of events, from weddings and birthdays to corporate gatherings and social celebrations. Our state-of-the-art equipment, customizable photo layouts, and friendly professional staff ensure a memorable experience for you and your guests. Let us add a touch of excitement and creativity to your special day, creating cherished keepsakes that last a lifetime.</p>
        </div>
      </div>
      <!-- End of title -->
      <div class="row my-5">
        <div class="col-md-4 text-center">
          <i class="fas fa-cogs fa-5x text-light mb-4"></i>
          <h1 class="text-white mb-3">Creativity</h1>
          <p class="text-muted">Our photobooth services are infused with creativity, offering unique and customizable photo experiences that delight and inspire.</p>
        </div>
        <div class="col-md-4 text-center">
          <i class="far fa-thumbs-up fa-5x text-light mb-4"></i>
          <h1 class="text-white mb-3">Quality</h1>
          <p class="text-muted">We are committed to delivering exceptional quality, using the latest equipment and professional-grade prints for stunning, high-resolution photos.</p>
        </div>
        <div class="col-md-4 text-center">
          <i class="far fa-handshake fa-5x text-light mb-4"></i>
          <h1 class="text-white mb-3">Experience</h1>
          <p class="text-muted">With years of experience in the industry, we ensure seamless and enjoyable photobooth sessions for you and your guests.</p>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-log-5 text-center">
          <img src="images/locationphoto.jpg" width="350" class="img-fluid camera-img">
        </div>
        <div class="col-lg-7 text-white text-lg-right text-center about-text">
          <h1>Where are We Located ?</h1>
          <p class="lead">RC Studio Photobooth is located in Bauan, Batangas, specifically at (58 Kapitan Ponso Street Poblacion, 4, Bauan, 4201 Batangas.)</p>
        </div>
      </div>
    </div>
  </section>
  <!-- End of about -->

  <!-- Works -->
  <section class="py-5 works" id="works">
    <div class="container-fluid">
      <!-- Title -->
      <div class="row text-black text-center">
        <div class="col m-4">
          <h1 class="display-4 mb-4">Works</h1>
          <p class="lead">These are examples of our past photobooth services, which have covered a range of events including birthdays, Valentine's celebrations, and weddings.</p>
        </div>
      </div>
      <!-- End of title -->
      <div class="row">
        <div class="col-lg-4 col-sm-6 my-5">
          <div class="card bord-0 card-shadow">
            <img src="images/1work.jpg" class="card-img">
            <div class="card-img-overlay">
              <h5 class="text-white font-weight-bold p-2 heading">BIRTHDAY PHOTOGRAPHY</h5>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6 my-5">
          <div class="card bord-0 card-shadow">
            <img src="images/2work.jpg" class="card-img">
            <div class="card-img-overlay">
              <h5 class="text-white font-weight-bold p-2 heading">BIRTHDAY PHOTOGRAPHY</h5>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6 my-5">
          <div class="card bord-0 card-shadow">
            <img src="images/3work.jpg" class="card-img">
            <div class="card-img-overlay">
              <h5 class="text-white font-weight-bold p-2 heading">BIRTHDAY PHOTOGRAPHY</h5>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6 my-5">
          <div class="card bord-0 card-shadow">
            <img src="images/7work.jpg" class="card-img">
            <div class="card-img-overlay">
              <h5 class="text-white font-weight-bold p-2 heading">WEDDING PHOTOGRAPHY</h5>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6 my-5">
          <div class="card bord-0 card-shadow">
            <img src="images/8work.jpg" class="card-img">
            <div class="card-img-overlay">
              <h5 class="text-white font-weight-bold p-2 heading">VALENTINE'S PHOTOGRAPHY</h5>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6 my-5">
          <div class="card bord-0 card-shadow">
            <img src="images/6work.jpg" class="card-img">
            <div class="card-img-overlay">
              <h5 class="text-white font-weight-bold p-2 heading">BIRTHDAY PHOTOGRAPHY</h5>
            </div>
          </div>
        </div>


        <div class="row text-black text-center">
        <div class="col m-4">
          <h1 class="display-4 mb-4">PACKAGE</h1>
          <p class="lead">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Check out our Packages featuring photos of our different packages, each explained to help you choose the perfect one for your event..</p>
          <img src="images/package.jpg" height="500px">
        </div>
      </div>
      </div>
    </div>
  </section>
  <!-- End of works -->

  <!-- Packages -->
  <section class="text-center p-5 packages" id="packages">
    <div class="container-fluid">
      <!-- Title -->
      <div class="row text-muted text-center">
        <div class="col m-4">
          <h1 class="display-4 text-white mb-4">Our Packages</h1>
          <div class="underline-dark mb-4"></div>
          <p class="lead text-white">Pick the Right Package For your Event</p>
        </div>
      </div>
      <!-- End of title -->
      <div class="row align-items-center row-eq-height">
        <?php foreach ($packages as $package): ?>
        <div class="col-lg-4">
          <div class="card card-1 text-dark py-4 my-4 mx-auto h-100">
            <div class="card-body h-100">
              <h5 class="text-uppercase font-weight-bold mb-5"><?php echo $package['title']; ?></h5>
              <h1 class="display-4 text-warning">₱<?php echo $package['price']; ?></h1>
              <ul class="list-unstyled">
                <li class="font-weight-bold py-3 card-list-item text-left"><?php echo $package['details']; ?></li>
                <li class="font-weight-bold py-3 card-list-item">Hours: <?php echo $package['hours']; ?></li>
              </ul>
              <a href="booking.php" class="btn p-2 text-uppercase font-weight-bold price-card-button text-light text-center mt-auto" style="background-color: #D71313;">Book Now!</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="my-5">
        <h2 class="text-muted mb-4">Boook now to capture every moment of your life</h2>
        <i class="fas fa-smile fa-3x"></i>
      </div>
    </div>
  </section>
  <!-- End of packages -->

 
  <!-- Footer -->
  <footer class="bg-light px-5" id="contacts">
    <div class="container-fluid">
      <div class="row text-dark py-4">
        <div class="col-lg-4 col-sm-6">
          <h5 class="pb-3">Photo Booth Service</h5>
          <p class="small">We are Only Accepting Bookings From Batangas Areas and Cities near Batangas!<br><br>For Inquiries:<br> Call: (043)9806388<br> Call: (043)7271649<br> Call: (043)7271649</p>
        </div>
        <div class="col-lg-2 col-sm-6">
          <h5 class="pb-3">Quick Links</h5>
          <ul class="list-unstyled">
            <li>
              <a href="#" class="footer-link">Home</a>
            </li>
            <li>
              <a href="#about" class="footer-link">About</a>
            </li>
            <li>
              <a href="#works" class="footer-link">Works</a>
            </li>
            <li>
              <a href="#packages" class="footer-link">Package</a>
            </li>
            <li>
              <a href="#contacts" class="footer-link">Contact</a>
            </li>
          </ul>
        </div>
        <div class="col-lg-2 col-sm-6">
          <h5 class="pb-3">Location</h5>
          <ul class="list-unstyled">
            <li>
              <a href="https://www.google.com/search?q=philippines+&sca_esv=44ea874260aa60b7&sca_upv=1&rlz=1C1CHBF_enPH912PH912&ei=iU9xZqKIGZeevr0Px6vv-Qc&ved=0ahUKEwji8Inf5-SGAxUXj68BHcfVO38Q4dUDCBA&uact=5&oq=philippines+&gs_lp=Egxnd3Mtd2l6LXNlcnAiDHBoaWxpcHBpbmVzIDILEAAYgAQYsQMYgwEyERAuGIAEGLEDGNEDGIMBGMcBMggQABiABBixAzILEAAYgAQYsQMYgwEyBRAAGIAEMgsQABiABBixAxiDATIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQABiABRIwQtQAFikCnAAeAGQAQCYAekBoAGUDqoBBjAuMTAuMrgBA8gBAPgBAZgCDKACnA_CAhAQLhiABBjRAxhDGMcBGIoFwgIKEAAYgAQYQxiKBcICChAuGIAEGEMYigXCAh8QLhiABBjRAxhDGMcBGIoFGJcFGNwEGN4EGOAE2AEBwgIWEC4YgAQYsQMY0QMYQxiDARiHARiKBcICDhAAGIAEGLEDGIMBGIoFwgINEC4YgAQYsQMYQxiKBcICCBAuGIAEGLEDwgIQEAAYgAQYsQMYQxiDARiKBcICDRAAGIAEGLEDGEMYigWYAwC6BgYIARABGBSSBwYwLjEwLjKgB4yVAQ&sclient=gws-wiz-serp" class="footer-link text-uppercase">Philippines</a>
            </li>
            <li>
              <a href="https://www.google.com/search?q=batangas+city&sca_esv=44ea874260aa60b7&sca_upv=1&rlz=1C1CHBF_enPH912PH912&ei=j09xZuP0B-vj1e8PtJa3eA&ved=0ahUKEwij-Obh5-SGAxXrcfUHHTTLDQ8Q4dUDCBA&uact=5&oq=batangas+city&gs_lp=Egxnd3Mtd2l6LXNlcnAiDWJhdGFuZ2FzIGNpdHkyBRAuGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQABiABDILEC4YgAQYxwEYrwEyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMhQQLhiABBiXBRjcBBjeBBjgBNgBAUikC1AAWLgJcAB4AZABAJgB1wKgAZkQqgEHMC45LjAuMrgBA8gBAPgBAZgCC6ACjxHCAgsQABiABBixAxiDAcICBBAAGAPCAhEQLhiABBixAxjRAxiDARjHAcICChAuGIAEGEMYigXCAhAQLhiABBjRAxhDGMcBGIoFwgIQEC4YgAQYsQMYQxiDARiKBcICCBAAGIAEGLEDwgINEC4YgAQYsQMYQxiKBcICChAAGIAEGEMYigXCAhkQLhiABBhDGIoFGJcFGNwEGN4EGOAE2AEBwgILEC4YgAQYsQMYgwHCAh8QLhiABBixAxhDGIMBGIoFGJcFGNwEGN4EGOAE2AEBwgIIEC4YgAQY1ALCAhEQLhiABBjHARiYBRiaBRivAZgDALoGBggBEAEYFJIHBzAuOS4wLjKgB_NC&sclient=gws-wiz-serp" class="footer-link text-uppercase">Batangas City</a>
            </li>
            <li>
              <a href="https://www.google.com/search?q=bauan+&sca_esv=44ea874260aa60b7&sca_upv=1&rlz=1C1CHBF_enPH912PH912&ei=oE9xZs7VFq7Bvr0PlYDRuAY&ved=0ahUKEwjOpYPq5-SGAxWuoK8BHRVAFGcQ4dUDCBA&uact=5&oq=bauan+&gs_lp=Egxnd3Mtd2l6LXNlcnAiBmJhdWFuIDIFEC4YgAQyBRAAGIAEMgUQABiABDILEC4YgAQYxwEYrwEyBRAAGIAEMgUQABiABDILEC4YgAQYxwEYrwEyERAuGIAEGMcBGJgFGJkFGK8BMgUQABiABDIFEAAYgAQyFBAuGIAEGJcFGNwEGN4EGOAE2AEBSL4FUABY0gNwAHgBkAEAmAG4AqABrAmqAQcwLjIuMi4xuAEDyAEA-AEBmAIFoAKlCsICChAuGIAEGEMYigXCAgsQABiABBixAxiDAcICChAAGIAEGEMYigXCAgQQABgDwgIREC4YgAQYsQMY0QMYgwEYxwHCAhkQLhiABBhDGIoFGJcFGNwEGN4EGOAE2AEBmAMAugYGCAEQARgUkgcFMi00LjGgB8iIAQ&sclient=gws-wiz-serp" class="footer-link text-uppercase">Bauan</a>
            </li>
            <li>
              <p class="text-info">rcstudiobauan@gmail.com</p>
            </li>
          </ul>
        </div>
        <div class="col-lg-4 col-sm-6" id="con">
          <h5 class="pb-3">Shoot with us</h5>
          <p class="small">PhotoBooth Service in Batangas</p>
          <form class="mb-3">
            <div class="input-group">
              <div class="input-group-append">
                <a href="booking.php"><button type="button" class="btn bg-light text-black text-uppercase font-weight-bold">Book Now!</button></a>
              </div>
            </div>
          </form>
          <ul class="list-inline">
            <a href="https://www.facebook.com/rcstudiophotobooth"><li class="list-inline-item"><i class="fab fa-facebook-square fa-2x text-dark"></i></li></a>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col text-center text-dark border-top pt-3">
          <p>&copy; 2024 Copyright, All Rights Reserved | RC Studio</p>
        </div>
      </div>
    </div>
  </footer>
  <!-- End of footer -->

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      var navItems = document.querySelectorAll('.nav-link');

      navItems.forEach(function(item) {
          item.addEventListener('click', function() {
              // Remove 'active' class from all items
              navItems.forEach(function(nav) {
                  nav.classList.remove('active');
              });

              // Add 'active' class to the clicked item
              this.classList.add('active');
          });
      });
  });

  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('bookNowBtn').addEventListener('click', function(event) {
        var isLoggedIn = <?php echo isset($_SESSION["user_id"]) ? 'true' : 'false'; ?>;
        if (!isLoggedIn) {
            event.preventDefault(); // Prevent the default action (redirecting to booking.php)
            alert('Please register or log in first to access this feature.');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('bookingHistoryLink').addEventListener('click', function(event) {
        var isLoggedIn = <?php echo isset($_SESSION["user_id"]) ? 'true' : 'false'; ?>;
        if (!isLoggedIn) {
            event.preventDefault(); // Prevent the default action (redirecting to booking_history.php)
            alert('Please register or log in first to access this feature.');
        }
    });
});
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <script src="js/script.js"></script>
</body>
</html>