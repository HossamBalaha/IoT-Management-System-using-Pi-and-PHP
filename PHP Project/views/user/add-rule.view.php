<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <title>Add New Rule</title>
</head>
<body>

<?php include_once("common/header.view.php"); ?>

<div class="container my-5">
  <div class="row">
    <div class="col-12">
      <h1 class="display-4">
        Add New Rule
      </h1>
      <hr>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/user/dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/user/rules.php">Rules</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add New Rule</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card shadow mt-2">
        <div class="card-body">
          <div
              class="alert alert-danger <?php echo (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) ? '' : 'd-none'; ?>"
              id="errorsContainer">
            <?php if (isset($_SESSION["errors"]) && count($_SESSION['errors']) > 0) { ?>
              <?php foreach ($_SESSION["errors"] as $error) { ?>
                <p class="m-0">
                  <i class="fa fa-caret-right mr-1"></i>
                  <?php echo $error; ?>
                </p>
              <?php } ?>
              <?php unset($_SESSION["errors"]); ?>
            <?php } ?>
          </div>
          <form action="/user/add-rule.php" method="POST">
            <div class="form-group row text-center">
              <label for="sensor" class="col-form-label col-12 col-md-3">
                Sensor
              </label>
              <div class="col-12 col-md-9">
                <select name="sensor" id="sensor" class="form-control" required>
                  <?php foreach($sensors as $sensor) { ?>
                  <option <?php echo (isset($_SESSION['form']) && $sensor['id'] == $_SESSION['form']['sensor']) ? "selected" : "" ?>
                      value="<?php echo $sensor['id']; ?>">
                    <?php echo $sensor['code']; ?>
                    (<?php echo $sensor['name']; ?>)
                  </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group row text-center">
              <label for="actuator" class="col-form-label col-12 col-md-3">
                Actuator
              </label>
              <div class="col-12 col-md-9">
                <select name="actuator" id="actuator" class="form-control" required>
                  <?php foreach($actuators as $actuator) { ?>
                    <option <?php echo (isset($_SESSION['form']) && $actuator['id'] == $_SESSION['form']['actuator']) ? "selected" : "" ?>
                        value="<?php echo $actuator['id']; ?>">
                      <?php echo $actuator['code']; ?>
                      (<?php echo $actuator['name']; ?>)
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group row text-center">
              <label for="reading" class="col-form-label col-12 col-md-3">
                Reading
              </label>
              <div class="col-12 col-md-9">
                <input type="text" name="reading"
                       maxlength="5"
                       value="<?php echo isset($_SESSION['form']['reading']) ? $_SESSION['form']['reading'] : '' ?>"
                       id="reading" class="form-control">
              </div>
            </div>
            <div class="form-group row text-center">
              <label for="operator" class="col-form-label col-12 col-md-3">
                Operator
              </label>
              <div class="col-12 col-md-9">
                <select name="operator" id="operator" class="form-control" required>
                  <?php foreach($operators as $operator) { ?>
                    <option <?php echo (isset($_SESSION['form']) && $operator['id'] == $_SESSION['form']['operator']) ? "selected" : "" ?>
                        value="<?php echo $operator['id']; ?>">
                      <?php echo $operator['name']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group row text-center">
              <label for="state" class="col-form-label col-12 col-md-3">
                State
              </label>
              <div class="col-12 col-md-9">
                <select name="state" id="state" class="form-control" required>
                  <option <?php echo (isset($_SESSION['form']) && $_SESSION['form']['state'] == 1) ? "selected" : "" ?>
                      value="1">ON</option>
                  <option <?php echo (isset($_SESSION['form']) && $_SESSION['form']['state'] == 0) ? "selected" : "" ?>
                      value="0">OFF</option>
                </select>
              </div>
            </div>
            <div class="text-center">
              <button id="addBtn"
                      type="submit"
                      class="btn btn-success">
                Add Rule
              </button>
            </div>
          </form>
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
    var sensor = $("#sensor");
    var actuator = $("#actuator");
    var reading = $("#reading");
    var state = $("#state");
    var operator = $("#operator");
    var addBtn = $("#addBtn");

    addBtn.click(function (e) {
      var errors = [];
      var errorsContainer = $("#errorsContainer");
      errorsContainer.html("");
      if (!errorsContainer.hasClass("d-none"))
        errorsContainer.addClass("d-none");

      var sensorValue = sensor.val().trim().length;
      var actuatorValue = actuator.val().trim().length;
      var readingValue = reading.val().trim();
      var stateValue = state.val().trim().length;
      var operatorValue = operator.val().trim().length;

      if (sensorValue <= 0) {
        errors.push("<?php echo VALID_SENSOR_REQ; ?>");
      }

      if (actuatorValue <= 0) {
        errors.push("<?php echo VALID_ACTUATOR_REQ; ?>");
      }

      if (readingValue.length <= 0) {
        errors.push("<?php echo VALID_READING_REQ; ?>");
      } else if (readingValue.length > 5) {
        errors.push("<?php echo VALID_READING_MAX; ?>");
      } else if (isNaN(parseFloat(readingValue))) {
        errors.push("<?php echo VALID_READING_NUM; ?>");
      }

      if (operatorValue <= 0) {
        errors.push("<?php echo VALID_OPERATOR_REQ; ?>");
      }

      if (stateValue <= 0) {
        errors.push("<?php echo VALID_STATE_REQ; ?>");
      }

      if (errors.length > 0) {
        e.preventDefault();

        var allErrorsHTML = "";
        for (var i = 0; i < errors.length; i++) {
          allErrorsHTML += generateErrorItem(errors[i]);
        }
        errorsContainer.html(allErrorsHTML);
        errorsContainer.removeClass("d-none");
      }
    });
  });
</script>
</body>
</html>