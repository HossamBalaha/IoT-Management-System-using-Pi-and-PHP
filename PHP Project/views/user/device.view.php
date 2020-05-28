<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <title>
    <?php echo $userDevice['code']; ?>
    (<?php echo $userDevice['name']; ?>)
  </title>
</head>
<body>

<?php include_once("common/header.view.php"); ?>

<div class="container my-5">
  <div class="row">
    <div class="col-12">
      <h1 class="display-4">
        <?php echo $userDevice['code']; ?>
        (<?php echo $userDevice['name']; ?>)
      </h1>
      <p class="<?php echo $userDevice['is_on'] == 1 ? "text-success" : "text-danger"; ?>">
        You device state is <?php echo $userDevice['is_on'] == 1 ? "ON" : "OFF"; ?>
      </p>
      <hr>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/user/dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/user/devices.php">Devices</a></li>
          <li class="breadcrumb-item active" aria-current="page">
            <?php echo $userDevice['code']; ?>
            (<?php echo $userDevice['name']; ?>)
          </li>
        </ol>
      </nav>
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
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card shadow">
        <div class="card-body">
          <div class="text-center">
            <a href="/user/device.php?d=<?php echo $d; ?>&op=toggle"
               class="btn <?php echo $userDevice['is_on'] == 1 ? "btn-warning" : "btn-success"; ?>">
              Turn <?php echo $userDevice['is_on'] == 1 ? "OFF" : "ON"; ?>
            </a>
            <a href="/user/device.php?d=<?php echo $d; ?>&op=delete"
               class="btn btn-danger delete">
              Delete
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-2 align-items-center">
    <div class="col-12 col-md-4">
      <div class="card shadow mt-2">
        <div class="card-body">
          <h3 class="card-title font-weight-bold">
            Device Information
          </h3>
          <hr>
          <ul class="list-unstyled mb-0">
            <li>Code: <?php echo $userDevice['code']; ?></li>
            <li>Name: <?php echo $userDevice['name']; ?></li>
            <li>
              Type:
              <?php if ($userDevice["type"] == 1) { ?>
                Sensor
              <?php } else if ($userDevice["type"] == 2) { ?>
                Actuator
              <?php } else { ?>
                Device type is not determined
              <?php } ?>
            </li>
            <li>Description: <?php echo $userDevice['description'] ? $userDevice['description'] : "-"; ?></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-8">
      <div class="card shadow mt-2">
        <div class="card-body">
          <h3 class="card-title font-weight-bold">
            Live Readings
            <span class="badge badge-success badge-pill d-none"
                  id="updateBadge"
                  style="font-size:12px;">
              UPDATED
            </span>
          </h3>
          <hr>
          <div class="text-center w-100" id="liveChart" style="height: 300px;">

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-2">
    <div class="col-12">
      <div class="card shadow mt-2">
        <div class="card-body pb-0">
          <div>
            <div class="float-right">
              <button id="loadReadingsBtn"
                      class="btn btn-info btn-sm">
                <i id="loadReadingsSpinner" class="fa fa-spin fa-spinner d-none"></i>
                Load More
              </button>
              <a href="/user/reading.php?d=<?php echo $d; ?>&op=delete-all"
                 class="btn btn-danger btn-sm delete-all">
                Delete All
              </a>
            </div>
            <h3 class="card-title font-weight-bold">
              Device Readings
            </h3>
            <hr>
          </div>
          <table id="readingsTable"
                 class="table table-bordered table-striped table-hover <?php echo count($readingsTable) > 0 ? '' : 'd-none'; ?>">
            <thead>
            <tr class="text-center">
              <th>#</th>
              <th>Reading</th>
              <th>Created At</th>
              <th>Operations</th>
            </tr>
            </thead>
            <tbody id="readingsTableBody">
            <?php foreach ($readingsTable as $k => $reading) { ?>
              <tr class="text-center">
                <td><?php echo $k + 1; ?></td>
                <td><?php echo $reading['reading']; ?></td>
                <td><?php echo $reading['created_at']; ?></td>
                <td>
                  <a href="/user/reading.php?d=<?php echo $reading['did']; ?>&op=delete"
                     class="btn btn-danger btn-sm delete">
                    <i class="fa fa-trash"></i>
                    Delete
                  </a>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>

          <div id="readingsAlert" class="alert alert-warning <?php echo count($readingsTable) <= 0 ? '' : 'd-none'; ?>">
            <p class="text-center m-0">
              <?php echo NO_READINGS; ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-2">
    <div class="col-12">
      <div class="card shadow mt-2">
        <div class="card-body pb-0">
          <div>
            <div class="float-right">
              <button id="loadAlarmsBtn"
                      class="btn btn-info btn-sm">
                <i id="loadAlarmsSpinner" class="fa fa-spin fa-spinner d-none"></i>
                Load More
              </button>
              <a href="/user/alarm.php?d=<?php echo $d; ?>&op=delete-all"
                 class="btn btn-danger btn-sm delete-all">
                Delete All
              </a>
            </div>
            <h3 class="card-title font-weight-bold">
              Device Alarms (Notifications)
            </h3>
            <hr>
          </div>

          <table id="alarmsTable"
                 class="table table-bordered table-striped table-hover <?php echo count($alarmsTable) > 0 ? '' : 'd-none'; ?>">
            <thead>
            <tr class="text-center">
              <th>#</th>
              <th>Reading</th>
              <th>Alarm</th>
              <th>Created At</th>
              <th>Operations</th>
            </tr>
            </thead>
            <tbody id="alarmsTableBody">
            <?php foreach ($alarmsTable as $k => $alarm) { ?>
              <tr class="text-center">
                <td><?php echo $k + 1; ?></td>
                <td><?php echo $alarm['reading']; ?></td>
                <td><?php echo $alarm['message']; ?></td>
                <td><?php echo $alarm['created_at']; ?></td>
                <td>
                  <a href="/user/alarm.php?d=<?php echo $alarm['aid']; ?>&op=delete"
                     class="btn btn-danger btn-sm delete">
                    <i class="fa fa-trash"></i>
                    Delete
                  </a>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>

          <div class="alert alert-warning <?php echo count($alarmsTable) <= 0 ? '' : 'd-none'; ?>"
               id="alarmsAlert">
            <p class="text-center m-0">
              <?php echo NO_ALARMS; ?>
            </p>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="row mt-2">
    <div class="col-12">
      <div class="card shadow mt-2">
        <div class="card-body pb-0">
          <div>
            <div class="float-right">
              <a href="/user/add-calibration.php?d=<?php echo $d; ?>"
                 class="btn btn-info btn-sm">
                Add New Calibration
              </a>
            </div>
            <h3 class="card-title font-weight-bold">
              Device Calibrations
            </h3>
            <hr>
          </div>
          <?php if (count($calibrationsTable) > 0) { ?>
            <table class="table table-bordered table-striped table-hover">
              <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Value</th>
                <th>Message</th>
                <th>Operator</th>
                <th>Created At</th>
                <th>Operations</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($calibrationsTable as $k => $calibration) { ?>
                <tr class="text-center">
                  <td><?php echo $k + 1; ?></td>
                  <td><?php echo $calibration['value']; ?></td>
                  <td><?php echo $calibration['message']; ?></td>
                  <td><?php echo $calibration['name']; ?></td>
                  <td><?php echo $calibration['created_at']; ?></td>
                  <td>
                    <a href="/user/calibration.php?d=<?php echo $calibration['cid']; ?>&op=edit"
                       class="btn btn-info btn-sm">
                      <i class="fa fa-edit"></i>
                      Edit
                    </a>
                    <a href="/user/calibration.php?d=<?php echo $calibration['cid']; ?>&op=delete"
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
                <?php echo NO_CALIBRATIONS; ?>
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
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<script src="/assets/js/app.js"></script>
<script>
  $(document).ready(function () {

    setInterval(function () {

      var token = "<?php echo $_SESSION['auth']['token']; ?>";
      var link = "/user/reading.ajax.php?d=<?php echo $d;?>&op=live&token=" + token;
      $.ajax({
        method: "GET",
        url: link,
        success: function (data) {
          $("#updateBadge").removeClass("d-none");
          setTimeout(function () {
            $("#updateBadge").addClass("d-none");
          }, 750);
          if (data) {
            var records = JSON.parse(data);
            var formattedRecords = [];
            for (var i = 0; i < records.length; i++) {
              formattedRecords.push({
                x: new Date(records[i]["x"]),
                y: parseFloat(records[i]["y"]),
              });
            }
            var options = {
              animationEnabled: true,
              axisX: {
                valueFormatString: "YYYY-MM-DD h:m:s"
              },
              axisY: {
                includeZero: false
              },
              data: [{
                //yValueFormatString: "$#,###",
                //xValueFormatString: "MMMM",
                type: "spline",
                dataPoints: formattedRecords,
              }]
            };
            $("#liveChart").CanvasJSChart(options);
          }
        }
      });
    }, 2000);


    function ReadingHTMLRecord($k, reading, createdAt, dID) {
      var html = '<tr class="text-center"><td>' + $k + '</td>' +
        '<td>' + reading + '</td>' +
        '<td>' + createdAt + '</td>' +
        '<td><a href="/user/reading.php?d=' + dID + '&op=delete" ' +
        'class="btn btn-danger btn-sm delete">' +
        '<i class="fa fa-trash mr-1"></i>Delete</a></td>';
      return html;
    }

    function AlarmHTMLRecord($k, reading, message, createdAt, aID) {
      var html = '<tr class="text-center"><td>' + $k + '</td>' +
        '<td>' + reading + '</td>' +
        '<td>' + message + '</td>' +
        '<td>' + createdAt + '</td>' +
        '<td><a href="/user/alarm.php?d=' + aID + '&op=delete" ' +
        'class="btn btn-danger btn-sm delete">' +
        '<i class="fa fa-trash mr-1"></i>Delete</a></td>';
      return html;
    }

    var readingsOffset = "<?php echo count($readingsTable); ?>";
    readingsOffset = parseInt(readingsOffset);
    var alarmsOffset = "<?php echo count($alarmsTable); ?>";
    alarmsOffset = parseInt(alarmsOffset);

    $("#loadReadingsBtn").click(function () {
      var token = "<?php echo $_SESSION['auth']['token']; ?>";
      var link = "/user/reading.ajax.php?d=<?php echo $d;?>&op=load&offset="
        + readingsOffset + "&token=" + token;
      $(this).attr("disabled", "disabled");
      $("#loadReadingsSpinner").removeClass("d-none");
      $.ajax({
        url: link,
        method: "GET",
        success: function (data) {
          if (!data) {
            alert("<?php echo SOMETHING_WRONG; ?>");
            return;
          }
          var records = JSON.parse(data);
          if (records == null || !records) {
            alert("<?php echo SOMETHING_WRONG; ?>");
          } else if (records.length <= 0) {
            alert("<?php echo NO_DATA; ?>");
          } else {
            $("#readingsTable").removeClass("d-none");
            $("#readingsAlert").addClass("d-none");

            var allRecordsHTML = "";
            for (var i = 0; i < records.length; i++) {
              var html = ReadingHTMLRecord(readingsOffset + i + 1,
                records[i]['reading'],
                records[i]['created_at'], records[i]['did']);
              allRecordsHTML += html;
            }
            $("#readingsTableBody").append(allRecordsHTML);
            readingsOffset += records.length;
          }
        },
        error: function () {
          alert("<?php echo SOMETHING_WRONG; ?>");
        },
        complete: function () {
          $("#loadReadingsSpinner").addClass("d-none");
          $("#loadReadingsBtn").removeAttr("disabled");
        }
      });
    });

    $("#loadAlarmsBtn").click(function () {
      var token = "<?php echo $_SESSION['auth']['token']; ?>";
      var link = "/user/alarm.ajax.php?d=<?php echo $d;?>&op=load&offset="
        + alarmsOffset + "&token=" + token;

      $(this).attr("disabled", "disabled");
      $("#loadAlarmsSpinner").removeClass("d-none");
      $.ajax({
        url: link,
        method: "GET",
        success: function (data) {
          if (!data) {
            alert("<?php echo SOMETHING_WRONG; ?>");
            return;
          }
          var records = JSON.parse(data);
          if (records == null || !records) {
            alert("<?php echo SOMETHING_WRONG; ?>");
          } else if (records.length <= 0) {
            alert("<?php echo NO_DATA; ?>");
          } else {
            $("#alarmsTable").removeClass("d-none");
            $("#alarmsAlert").addClass("d-none");

            var allRecordsHTML = "";
            for (var i = 0; i < records.length; i++) {
              var html = AlarmHTMLRecord(alarmsOffset + i + 1,
                records[i]['reading'],
                records[i]['message'],
                records[i]['created_at'], records[i]['aid']);
              allRecordsHTML += html;
            }
            $("#alarmsTableBody").append(allRecordsHTML);
            alarmsOffset += records.length;
          }
        },
        error: function () {
          alert("<?php echo SOMETHING_WRONG; ?>");
        },
        complete: function () {
          $("#loadAlarmsSpinner").addClass("d-none");
          $("#loadAlarmsBtn").removeAttr("disabled");
        }
      });
    });

    $('a.delete').click(function (e) {
      var isConfirm = confirm("<?php echo CONFIRM_DELETE; ?>");
      if (!isConfirm) {
        e.preventDefault();
      }
    });
    $('a.delete-all').click(function (e) {
      var isConfirm = confirm("<?php echo CONFIRM_DELETE_ALL; ?>");
      if (!isConfirm) {
        e.preventDefault();
      }
    });
  });
</script>
</body>
</html>