<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <title>Alarms (Notifications)</title>
</head>
<body>

<?php include_once("common/header.view.php"); ?>

<div class="container my-5">
  <div class="row">
    <div class="col-12">
      <h1 class="display-4">
        Alarms (Notifications)
      </h1>
      <hr>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/user/dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Alarms (Notifications)</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card shadow mt-2">
        <div class="card-body pb-0">
          <?php if (count($alarmsTable) > 0) { ?>
            <table class="table table-bordered table-striped table-hover">
              <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Reading</th>
                <th>Alarm</th>
                <th>Created At</th>
                <th>Operations</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($alarmsTable as $k => $alarm) { ?>
                <tr class="text-center">
                  <td><?php echo $k + 1; ?></td>
                  <td><?php echo $alarm['reading']; ?></td>
                  <td><?php echo $alarm['message']; ?></td>
                  <td><?php echo $alarm['created_at']; ?></td>
                  <td>
                    <a href="/user/device.php?d=<?php echo $alarm['id']; ?>&op=show"
                       class="btn btn-info btn-sm">
                      <i class="fa fa-eye"></i>
                      Open Device
                    </a>
                  </td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          <?php } else { ?>
            <div class="alert alert-warning">
              <p class="text-center m-0">
                <?php echo NO_ALARMS; ?>
              </p>
            </div>
          <?php } ?>
        </div>
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