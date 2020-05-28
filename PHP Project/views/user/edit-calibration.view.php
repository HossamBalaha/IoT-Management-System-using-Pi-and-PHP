<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <title>Edit Calibration</title>
</head>
<body>

<?php include_once("common/header.view.php"); ?>

<div class="container my-5">
  <div class="row">
    <div class="col-12">
      <h1 class="display-4">
        Edit Calibration
      </h1>
      <hr>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/user/dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/user/devices.php">Devices</a></li>
          <li class="breadcrumb-item">
            <a href="/user/device.php?d=<?php echo $deviceID; ?>&op=show">
              <?php echo $deviceInfo['code']; ?>
              (<?php echo $deviceInfo['name']; ?>)
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Edit Calibration</li>
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
          <form action="/user/calibration.php?d=<?php echo $d; ?>&op=update" method="POST">
            <div class="form-group row text-center">
              <label for="reading" class="col-form-label col-12 col-md-3">
                Reading (Value)
              </label>
              <div class="col-12 col-md-9">
                <input type="text" name="reading"
                       maxlength="5"
                       value="<?php echo $calibration['value']; ?>"
                       id="reading" class="form-control">
              </div>
            </div>
            <div class="form-group row text-center">
              <label for="message" class="col-form-label col-12 col-md-3">
                Message
              </label>
              <div class="col-12 col-md-9">
                <input type="text" name="message"
                       maxlength="125"
                       value="<?php echo $calibration['message']; ?>"
                       id="message" class="form-control">
              </div>
            </div>
            <div class="form-group row text-center">
              <label for="operator" class="col-form-label col-12 col-md-3">
                Operator
              </label>
              <div class="col-12 col-md-9">
                <select name="operator" id="operator" class="form-control" required>
                  <?php foreach($operators as $operator) { ?>
                    <option <?php echo ($operator['id'] == $calibration['operator_id']) ? "selected" : "" ?>
                        value="<?php echo $operator['id']; ?>">
                      <?php echo $operator['name']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="text-center">
              <button id="updateBtn"
                      type="submit"
                      class="btn btn-success">
                Update Calibration
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
    var message = $("#message");
    var reading = $("#reading");
    var operator = $("#operator");
    var updateBtn = $("#updateBtn");

    updateBtn.click(function (e) {
      var errors = [];
      var errorsContainer = $("#errorsContainer");
      errorsContainer.html("");
      if (!errorsContainer.hasClass("d-none"))
        errorsContainer.addClass("d-none");

      var messageValue = message.val().trim().length;
      var readingValue = reading.val().trim();
      var operatorValue = operator.val().trim().length;

      if (readingValue.length <= 0) {
        errors.push("<?php echo VALID_READING_REQ; ?>");
      } else if (readingValue.length > 5) {
        errors.push("<?php echo VALID_READING_MAX; ?>");
      } else if (isNaN(parseFloat(readingValue))) {
        errors.push("<?php echo VALID_READING_NUM; ?>");
      }

      if (messageValue <= 0) {
        errors.push("<?php echo VALID_MESSAGE_REQ; ?>");
      } else if (messageValue > 125) {
        errors.push("<?php echo VALID_MESSAGE_MAX; ?>");
      }

      if (operatorValue <= 0) {
        errors.push("<?php echo VALID_OPERATOR_REQ; ?>");
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