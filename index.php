<?php
  include('dbcon.php');

  $sql1 = "select * from cust_order where or_status = 5";
  $run1 = mysqli_query($link, $sql1);
  $loads = mysqli_num_rows($run1);

  $sql2 = "select * from truck_owners where to_verified = 1";
  $run2 = mysqli_query($link, $sql2);
  $owners = mysqli_num_rows($run2);

  $sql3 = "select * from trucks where trk_verified = 1";
  $run3 = mysqli_query($link, $sql3);
  $trucks = mysqli_num_rows($run3);

$hold = '
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="icon" href="assets/img/truckwale-logo-favicon.png" type="image/gif" sizes="32x32">

    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
      integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
      crossorigin="anonymous"
    />
    <!-- fontawesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
      integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
      crossorigin="anonymous"
    />
    <!-- Custom css -->
    <link rel="stylesheet" href="assets/css/home.css" />

    <title>Truck Wale</title>
    <style>
    .count
    {
      font-size:50px !important;
      text-align:center !important;
    }
</style>
  </head>
  <body>
    <!-- Navbar -->
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">
          <img src="assets/img/truck-logo-sm.png" alt="" style="width: 70px;"/>
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon">
            <i class="fas fa-bars" style="color: #d71e48; font-size: 28px"></i>
          </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="#pricing">About Us</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="#why-us"
                >Why us? <span class="sr-only">(current)</span></a
              >
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#feature">Features</a>
            </li>            
            <li class="nav-item">
              <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Download</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>

    <!-- section1 -->
    <div class="section1 container">
      <div class="row">
        <div class="col-md-7">
          <h2>
            ORGANIZING, DIGITIZING <br />
            AND SIMPLIFYING <br />
            TRUCKING
          </h2>
          <h2>
            BlackBuck marks the beginning of a new path in trucking - a path
            that is organized and makes trucking simple for every shipper and
            trucker.
          </h2>
          <div class="section1__btn">
            <a href="#" class="btn btn-danger">Try for free</a>
            <a href="#" class="btn btn-link">Watch demo video</a>
          </div>
        </div>
        <div class="col-md-5">
          <div class="section1__mobile"></div>
        </div>
      </div>
    </div>

    <!-- section2 -->
    <div class="container section2" id="why-us">
      <div class="row">
        <div class="col-md-3">
          <div class="section2__icon">
            <i class="fas fa-newspaper"></i>
          </div>
          <div class="section2__title">
            <div class="count" data-count="<?php echo $loads; ?>"><?php echo $loads; ?></div>
          </div>
          <div class="section2__text">
            <p>Deliveries Completed</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="section2__icon">
            <i class="fas fa-crown"></i>
          </div>
          <div class="section2__title">
          <div class="count" data-count="<?php echo $owners; ?>"><?php echo $owners; ?></div>
          </div>
          <div class="section2__text">
            <p>Verified Truck Owners</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="section2__icon">
            <i class="fas fa-truck-monster"></i>
          </div>
          <div class="section2__title">
          <div class="count" data-count="<?php echo $trucks; ?>"><?php echo $trucks; ?></div>
          </div>
          <div class="section2__text">
            <p>Verified Trucks</p>
          </div>
        </div>
      </div>
    </div>

    <!-- section3 -->
    <div class="container section3" id="feature">
      <div class="row section3__heading">
        <h2>ADVANCED FEATURES</h2>
      </div>
      <div class="row section3__main">
        <div class="col-md-3">
          <div class="section3__left__item">
            <div>
              <h2 class="text-right">Lorem Ipsum</h2>
              <p class="text-left">
                Sed ut perspiciatis unde omnis iste natus error sit
              </p>
            </div>
            <div class="section3__icon">
              <i class="far fa-dot-circle"></i>
            </div>
          </div>
          <div class="section3__left__item">
            <div>
              <h2 class="text-right">Lorem Ipsum</h2>
              <p class="text-left">
                Sed ut perspiciatis unde omnis iste natus error sit
              </p>
            </div>
            <div class="section3__icon">
              <i class="far fa-dot-circle"></i>
            </div>
          </div>
          <div class="section3__left__item">
            <div>
              <h2 class="text-right">Lorem Ipsum</h2>
              <p class="text-left">
                Sed ut perspiciatis unde omnis iste natus error sit
              </p>
            </div>
            <div class="section3__icon">
              <i class="far fa-dot-circle"></i>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="section3__mobile"></div>
        </div>
        <div class="col-md-3">
          <div class="section3__right__item">
            <div class="section3__icon">
              <i class="far fa-dot-circle"></i>
            </div>
            <div>
              <h2 class="text-left">Lorem Ipsum</h2>
              <p class="text-left">
                Sed ut perspiciatis unde omnis iste natus error sit
              </p>
            </div>
          </div>
          <div class="section3__right__item">
            <div class="section3__icon">
              <i class="far fa-dot-circle"></i>
            </div>
            <div>
              <h2 class="text-left">Lorem Ipsum</h2>
              <p class="text-left">
                Sed ut perspiciatis unde omnis iste natus error sit
              </p>
            </div>
          </div>
          <div class="section3__right__item">
            <div class="section3__icon">
              <i class="far fa-dot-circle"></i>
            </div>
            <div>
              <h2 class="text-left">Lorem Ipsum</h2>
              <p class="text-left">
                Sed ut perspiciatis unde omnis iste natus error sit
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- section5 -->
    <section class="section5 container-fluid">
      <div class="container">
        <h2 class="text-center">
          Start using <strong>BlackBuck App</strong> now!
        </h2>
        <p class="text-center">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim
        </p>
        <div class="d-flex">
          <button class="btn">
            Download App&nbsp;<i class="fas fa-arrow-right"></i>
          </button>
        </div>
      </div>
    </section>

    <!-- footer -->
    <footer>
      <div class="container">
        <div class="footer__left">&copy;&nbsp;BlackBuck</div>
        <div class="footer__right">
          <div><i class="fab fa-facebook"></i></div>
          <div><i class="fab fa-instagram"></i></div>
          <div><i class="fab fa-twitter"></i></div>
          <div><i class="fab fa-youtube"></i></div>
        </div>
      </div>
    </footer>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    
  </body>
</html>

';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truck Wale</title>
    <link rel="icon" href="assets/img/truckwale-logo-favicon.png" type="image/gif" sizes="32x32">
    <style>
        html, body {
            margin: 0 auto;
            font-family: 'Roboto', sans-serif;
            height: 100%;
            background: #f4f6f9;
            font-weight: 100;
            -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                    user-select: none;
            }

            main {
            height: 100%;
            display: -webkit-box;
            display: flex;
            margin: 0 20px;
            text-align: center;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
                    flex-direction: column;
            -webkit-box-align: center;
                    align-items: center;
            -webkit-box-pack: center;
                    justify-content: center;
            }
            main h1 {
            font-size: 3em;
            font-weight: 100;
            color: #0a0a0a;
            margin: 0;
            }
            main h2 {
            font-size: 1.5em;
            font-weight: 100;
            margin-bottom: 0;
            }
            main h3 {
            font-size: 1.5em;
            font-weight: 100;
            margin-top: 0;
            }
            main a {
            font-size: 1.5em;
            font-weight: 300;
            color: #0a0a0a;
            text-decoration: none;
            }

            footer {
            position: absolute;
            bottom: 0;
            margin: 10px;
            font-weight: 300;
            }
    </style>
</head>
<body>
    <main>
        <div style="width: 100%; text-align: center;"><img src="assets/img/truck-logo-sm.png" style="width: 100px; height: 100px; margin-bottom: 50px;" alt=""></div>
    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="70" viewBox="0 0 100 68">
        <g id="large">
        <path fill="none" stroke="#0a0a0a" d="M55.8 38.5l6.2-1.2c0-1.8-.1-3.5-.4-5.3l-6.3-.2c-.5-2-1.2-4-2.1-6l4.8-4c-.9-1.6-1.9-3-3-4.4l-5.6 3c-1.3-1.6-3-3-4.7-4.1l2-6A30 30 0 0 0 42 8l-3.3 5.4c-2-.7-4.2-1-6.2-1.2L31.3 6c-1.8 0-3.5.1-5.3.4l-.2 6.3c-2 .5-4 1.2-6 2.1l-4-4.8c-1.6.9-3 1.9-4.4 3l3 5.6c-1.6 1.3-3 3-4.1 4.7l-6-2A32.5 32.5 0 0 0 2 26l5.4 3.3c-.7 2-1 4.2-1.2 6.2L0 36.7c0 1.8.1 3.5.4 5.3l6.3.2c.5 2 1.2 4 2.1 6l-4.8 4c.9 1.6 1.9 3 3 4.4l5.6-3c1.4 1.6 3 3 4.7 4.1l-2 6A30.5 30.5 0 0 0 20 66l3.4-5.4c2 .7 4 1 6.1 1.2l1.2 6.2c1.8 0 3.5-.1 5.3-.4l.2-6.3c2-.5 4-1.2 6-2.1l4 4.8c1.6-.9 3-1.9 4.4-3l-3-5.6c1.6-1.3 3-3 4.1-4.7l6 2A32 32 0 0 0 60 48l-5.4-3.3c.7-2 1-4.2 1.2-6.2zm-13.5 4a12.5 12.5 0 1 1-22.6-11 12.5 12.5 0 0 1 22.6 11z"/>
        <animateTransform attributeName="transform" begin="0s" dur="3s" from="0 31 37" repeatCount="indefinite" to="360 31 37" type="rotate"/>
        </g>
        <g id="small">
        <path fill="none" stroke="#0a0a0a" d="M93 19.3l6-3c-.4-1.6-1-3.2-1.7-4.8L90.8 13c-.9-1.4-2-2.7-3.4-3.8l2.1-6.3A21.8 21.8 0 0 0 85 .7l-3.6 5.5c-1.7-.4-3.4-.5-5.1-.3l-3-5.9c-1.6.4-3.2 1-4.7 1.7L70 8c-1.5 1-2.8 2-3.9 3.5L60 9.4a20.6 20.6 0 0 0-2.2 4.6l5.5 3.6a15 15 0 0 0-.3 5.1l-5.9 3c.4 1.6 1 3.2 1.7 4.7L65 29c1 1.5 2.1 2.8 3.5 3.9l-2.1 6.3a21 21 0 0 0 4.5 2.2l3.6-5.6c1.7.4 3.5.5 5.2.3l2.9 5.9c1.6-.4 3.2-1 4.8-1.7L86 34c1.4-1 2.7-2.1 3.8-3.5l6.3 2.1a21.5 21.5 0 0 0 2.2-4.5l-5.6-3.6c.4-1.7.5-3.5.3-5.1zM84.5 24a7 7 0 1 1-12.8-6.2 7 7 0 0 1 12.8 6.2z"/>
        <animateTransform attributeName="transform" begin="0s" dur="2s" from="0 78 21" repeatCount="indefinite" to="-360 78 21" type="rotate"/>
        </g>
    </svg>
    <h1>Under Maintanance</h1>
    <h2>Sorry for the inconvenience.</h2>
    <h3>To contact us in the meantime please email:</h3>
    <a href="mailto:contact@truckwale.co.in">contact@truckwale.co.in</a>
    <br><br>
    <a style="font-size: 14px; margin-top: 50px;" href="privacy-policy">Privacy Policy</a>
    </main>
</body>
</html>