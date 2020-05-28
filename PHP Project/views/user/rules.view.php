<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <title>Rules</title>
</head>
<body>

<?php include_once("common/header.view.php"); ?>

<div class="container my-5">
  <div class="row">
    <div class="col-12">
      <h1 class="display-4">
        Rules
      </h1>
      <hr>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/user/dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Rules</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="text-right">
        <a href="/user/add-rule.php" class="btn btn-primary">
          Add New Rule
        </a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card shadow mt-2">
        <div class="card-body pb-0">

          <?php if (isset($_SESSION["success"]) && strlen($_SESSION['success']) > 0) { ?>
            <div class="alert alert-success">
              <p class="m-0">
                <i class="fa fa-caret-right mr-1"></i>
                <?php echo $_SESSION['success']; ?>
              </p>
              <?php unset($_SESSION["success"]); ?>
            </div>
          <?php } ?>

          <?php if (count($rulesTable) > 0) { ?>
          <table class="table table-bordered table-striped table-hover">
            <thead>
            <tr class="text-center">
              <th>#</th>
              <th>Sensor</th>
              <th>Actuator</th>
              <th>Operator</th>
              <th>Value</th>
              <th>State</th>
              <th>Created At</th>
              <th>Operations</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($rulesTable as $k => $rule) { ?>
            <tr class="text-center">
              <td><?php echo $k + 1; ?></td>
              <td>
                <a href="/user/device.php?d=<?php echo $rule['id']; ?>&op=show">
                  <?php echo $rule['code']; ?>
                </a>
              </td>
              <td>
                <a href="/user/device.php?d=<?php echo $rule['actID']; ?>&op=show">
                  <?php echo $rule['actCode']; ?>
                </a>
              </td>
              <td><?php echo $rule['operator']; ?></td>
              <td><?php echo $rule['value']; ?></td>
              <td><?php echo $rule['state']? "ON" : "OFF"; ?></td>
              <td><?php echo $rule['created_at']; ?></td>
              <td>
                <a href="/user/rule.php?d=<?php echo $rule['ruleID']; ?>&op=edit"
                   class="btn btn-info btn-sm">
                  <i class="fa fa-edit"></i>
                  Edit
                </a>
                <a href="/user/rule.php?d=<?php echo $rule['ruleID']; ?>&op=delete"
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
              <?php echo NO_RULES; ?>
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