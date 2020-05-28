<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <title>Devices</title>
</head>
<body>

<?php include_once("common/header.view.php"); ?>

<div class="container my-5">
  <div class="row">
    <div class="col-12">
      <h1 class="display-4">
        Devices
      </h1>
      <hr>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/user/dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Devices</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="text-right">
        <a href="/user/add-device.php" class="btn btn-primary">
          Add New Device
        </a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card shadow mt-2">
        <div class="card-body pb-0">
          <?php if (count($devicesTable) > 0) { ?>
          <table class="table table-bordered table-striped table-hover">
            <thead>
            <tr class="text-center">
              <th>#</th>
              <th>Code</th>
              <th>Information</th>
              <th>Operations</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($devicesTable as $k => $device) { ?>
              <tr class="text-center">
                <td class="align-middle"><?php echo $k + 1; ?></td>
                <td class="align-middle"><?php echo $device["code"]; ?></td>
                <td class="align-middle">
                  <b>Name:</b>
                  <?php echo $device["name"]; ?>
                  <br>
                  <b>Type:</b>
                  <?php if ($device["type"] == 1) { ?>
                    Sensor
                  <?php } else if ($device["type"] == 2) { ?>
                    Actuator
                  <?php } else { ?>
                    Device type is not determined
                  <?php } ?>
                  <br>
                  <b>State:</b>
                  <span class="badge badge-pill badge-<?php echo $device["is_on"] == 1? "success": "danger"; ?>">
                    <?php echo $device["is_on"] == 1? "ON": "OFF"; ?>
                  </span>
                  <br>
                  <?php echo $device["description"]; ?>
                </td>
                <td class="align-middle">
                  <a href="/user/device.php?d=<?php echo $device["id"]; ?>&op=show"
                     class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i>
                    Show
                  </a>
                  <a href="/user/device.php?d=<?php echo $device["id"]; ?>&op=delete"
                     class="btn btn-danger btn-sm delete">
                    <i class="fa fa-trash"></i>
                    Delete
                  </a>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
          <?php } else { ?>
            <div class="alert alert-warning">
              <p class="text-center m-0">
                <?php echo NO_DEVICES; ?>
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
<script>
  $(document).ready(function () {
    $('a.delete').click(function (e) {
      var isConfirm = confirm("<?php echo CONFIRM_DELETE; ?>");
      if (!isConfirm) {
        e.preventDefault();
      }
    });
  });
</script>
</body>
</html>