<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <title>User Dashboard</title>
</head>
<body>

<?php include_once("common/header.view.php"); ?>

<div class="container my-5">
  <div class="row">
    <div class="col-12">
      <h1 class="display-4">
        Dashboard
      </h1>
      <hr>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <?php if (isset($_SESSION["success"]) && strlen($_SESSION['success']) > 0) { ?>
        <div class="alert alert-success">
          <p class="m-0">
            <i class="fa fa-caret-right mr-1"></i>
            <?php echo $_SESSION['success']; ?>
          </p>
          <?php unset($_SESSION["success"]); ?>
        </div>
      <?php } ?>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-md-6 col-lg-4">
      <a href="/user/devices.php" class="a-no-style">
        <div class="card shadow border">
          <div class="card-body">
            <h2 class="text-center card-title">
              Devices
            </h2>
            <p class="card-subtitle text-center font-weight-bold">
              <?php echo $devicesCount; ?>
            </p>
          </div>
      </a>
    </div>
  </div>

  <div class="col-12 col-md-6 col-lg-4">
    <a href="/user/alarms.php" class="a-no-style">
      <div class="card shadow border">
        <div class="card-body">
          <h2 class="text-center card-title">
            Alarms (Notifications)
          </h2>
          <p class="card-subtitle text-center font-weight-bold">
            <?php echo $alarmsCount; ?>
          </p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-12 col-md-6 col-lg-4">
    <a href="/user/rules.php" class="a-no-style">
      <div class="card shadow border">
        <div class="card-body">
          <h2 class="text-center card-title">
            Rules
          </h2>
          <p class="card-subtitle text-center font-weight-bold">
            <?php echo $rulesCount; ?>
          </p>
        </div>
      </div>
    </a>
  </div>
</div>

<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/all.min.js"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>