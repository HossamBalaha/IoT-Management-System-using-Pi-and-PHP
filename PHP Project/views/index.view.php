<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <title>Index</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark navy-bg">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link white-color" href="#ourCompany">Our Company</a>
      </li>
      <li class="nav-item">
        <a class="nav-link white-color" href="#sponsors">Sponsors</a>
      </li>
    </ul>
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">
          <img src="<?php echo $websiteLogo; ?>"
               width="100" height="100"
               class="rounded-circle"
               alt="<?php echo $websiteName; ?>">
        </a>
      </li>
    </ul>
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link white-color" href="#testimonials">Testimonials</a>
      </li>
      <li class="nav-item">
        <a class="nav-link white-color" href="#ourTeam">Our Team</a>
      </li>
    </ul>
  </div>
</nav>

<div class="jumbotron jumbotron-fluid my-0 navy-bg">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="text-center display-4 white-color">
          <?php echo $websiteName; ?>
        </h1>
        <div class="text-center mt-3">
          <a href="/auth/login.php"
             class="btn btn-primary px-3 white-color">
            Login
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="ourCompany" class="jumbotron jumbotron-fluid my-0 white-bg">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="text-center navy-color">
          Our Company
        </h2>
        <p class="text-center m-0 navy-color">
          <?php echo $websiteDesc; ?>
        </p>
      </div>
    </div>
  </div>
</div>

<div id="sponsors" class="jumbotron jumbotron-fluid my-0 bg-6FA8DC">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="text-center white-color">
          Sponsors
        </h2>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-3">
        <div class="text-center">
          <img src="/assets/images/Desert.jpg"
               class="rounded-circle shadow img-fluid"
               alt="">
        </div>
        <p class="m-0 mt-2 text-center white-color">Sponsor Name</p>
      </div>
      <div class="col-12 col-md-3">
        <div class="text-center">
          <img src="/assets/images/Desert.jpg"
               class="rounded-circle shadow img-fluid"
               alt="">
        </div>
        <p class="m-0 mt-2 text-center white-color">Sponsor Name</p>
      </div>
      <div class="col-12 col-md-3">
        <div class="text-center">
          <img src="/assets/images/Desert.jpg"
               class="rounded-circle shadow img-fluid"
               alt="">
        </div>
        <p class="m-0 mt-2 text-center white-color">Sponsor Name</p>
      </div>
      <div class="col-12 col-md-3">
        <div class="text-center">
          <img src="/assets/images/Desert.jpg"
               class="rounded-circle shadow img-fluid"
               alt="">
        </div>
        <p class="m-0 mt-2 text-center white-color">Sponsor Name</p>
      </div>
    </div>
  </div>
</div>

<div id="testimonials" class="jumbotron jumbotron-fluid my-0 white-bg">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="text-center navy-color">
          Testimonials
        </h2>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-12 col-md-6">
        <div class="card shadow">
          <div class="card-body">
            <div class="media align-items-center">
              <div class="media-body">
                <h5 class="mt-0 mb-1 font-weight-bold">Person Name</h5>
                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus
                odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate
                fringilla. Donec lacinia congue felis in faucibus.
              </div>
              <img src="/assets/images/flower.jpg"
                   width="100" height="100"
                   class="ml-3 rounded-circle" alt="...">
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2 mt-md-0">
        <div class="card shadow">
          <div class="card-body">
            <div class="media align-items-center">
              <div class="media-body">
                <h5 class="mt-0 mb-1 font-weight-bold">Person Name</h5>
                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus
                odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate
                fringilla. Donec lacinia congue felis in faucibus.
              </div>
              <img src="/assets/images/flower.jpg"
                   width="100" height="100"
                   class="ml-3 rounded-circle" alt="...">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="ourTeam" class="jumbotron jumbotron-fluid my-0 bg-6FA8DC">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="text-center white-color">
          Our Team
        </h2>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-3">
        <div class="text-center">
          <img src="/assets/images/Desert.jpg"
               class="rounded-circle shadow img-fluid"
               alt="">
        </div>
        <p class="m-0 mt-2 text-center white-color">Team Member</p>
      </div>
      <div class="col-12 col-md-3">
        <div class="text-center">
          <img src="/assets/images/Desert.jpg"
               class="rounded-circle shadow img-fluid"
               alt="">
        </div>
        <p class="m-0 mt-2 text-center white-color">Team Member</p>
      </div>
      <div class="col-12 col-md-3">
        <div class="text-center">
          <img src="/assets/images/Desert.jpg"
               class="rounded-circle shadow img-fluid"
               alt="">
        </div>
        <p class="m-0 mt-2 text-center white-color">Team Member</p>
      </div>
      <div class="col-12 col-md-3">
        <div class="text-center">
          <img src="/assets/images/Desert.jpg"
               class="rounded-circle shadow img-fluid"
               alt="">
        </div>
        <p class="m-0 mt-2 text-center white-color">Team Member</p>
      </div>
    </div>
  </div>
</div>

<div class="jumbotron jumbotron-fluid my-0">
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-6">
        <h4>Quick Links</h4>
        <hr>
        <ul class="list-unstyled">
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">FAQ</a></li>
        </ul>
      </div>
      <div class="col-12 col-md-6">
        <h4>Contact Information</h4>
        <hr>
        <ul class="list-unstyled">
          <li>
            <i class="fa fa-phone"></i>
            (000) 000-000-0000
          </li>
          <li>
            <i class="fa fa-at"></i>
            company@company.com
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/all.min.js"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>