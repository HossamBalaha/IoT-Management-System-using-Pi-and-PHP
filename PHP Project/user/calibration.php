<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['username']))) {
  header("Location: /auth/login.php");
  return;
}

if (!isset($_GET['op']) || !isset($_GET['d'])) {
  header("Location: /user/devices.php");
  return;
}

$op = $_GET['op'];
$d = $_GET['d'];

$sql = "SELECT COUNT(*) AS K " .
  "FROM users, user_devices, device_calibrations " .
  "WHERE users.id = user_devices.user_id AND " .
  "device_calibrations.user_device_id = user_devices.id AND ".
  "users.username=:u AND device_calibrations.id=:d;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ":u" => $_SESSION['auth']['username'],
  ':d' => $d,
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$K = $record['K'];
if ($K <= 0) {
  header("Location: /user/devices.php");
  return;
}

$sql = "SELECT user_device_id FROM device_calibrations WHERE id=:d;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':d' => $d,
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$deviceID = $record['user_device_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (strcmp($op, "update") != 0) {
    header("Location: /user/devices.php");
    return;
  }

  $errors = [];
  $reading = null;
  $message = null;
  $operator = null;


  if (!isset($_POST['reading'])) {
    array_push($errors, VALID_READING_REQ);
  } else {
    $reading = $_POST['reading'];
    if (strlen($reading) > 5) {
      array_push($errors, VALID_READING_MAX);
    } else if (!is_numeric($reading)) {
      array_push($errors, VALID_READING_NUM);
    }
  }

  if (!isset($_POST['message'])) {
    array_push($errors, VALID_MESSAGE_REQ);
  } else {
    $message = $_POST['message'];
    if (strlen($message) > 125) {
      array_push($errors, VALID_MESSAGE_MAX);
    }
  }

  if (!isset($_POST['operator'])) {
    array_push($errors, VALID_OPERATOR_REQ);
  } else {
    $operator = $_POST['operator'];
  }

  if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header("Location: /user/calibration.php?d=$d&op=edit");
    return;
  } else {
    $sql = "SELECT COUNT(*) AS K FROM operators WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':id' => $operator,
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $noOfOperators = $record["K"];
    if ($noOfOperators <= 0) {
      array_push($errors, VALID_OPERATOR_REQ);
    }

    if (count($errors) > 0) {
      $_SESSION['errors'] = $errors;
      header("Location: /user/calibration.php?d=$d&op=edit");
      return;
    } else {
      $sql = "UPDATE device_calibrations SET value=:v, operator_id=:o, message=:m " .
        "WHERE id=:d;";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':m' => $message,
        ':v' => $reading,
        ':o' => $operator,
        ':d' => $d,
      ]);

      $_SESSION['success'] = CALIBRATION_UPDATE_SUCCESS;
      header("Location: /user/device.php?d=$deviceID&op=show");
      return;
    }
  }
}



if (strcmp($op, "delete") == 0) {
  $sql = "DELETE FROM device_calibrations WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);

  $_SESSION['success'] = DELETE_SUCCESS;
  header("Location: /user/device.php?d=$deviceID&op=show");
  return;
} else if (strcmp($op, "edit") == 0) {
  $sql = "SELECT * FROM device_calibrations WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);
  $calibration = $stmt->fetch(PDO::FETCH_ASSOC);

  $sql = "SELECT * FROM devices, user_devices WHERE " .
    "devices.id = user_devices.device_id AND user_devices.id = :d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $deviceID,
  ]);
  $deviceInfo = $stmt->fetch(PDO::FETCH_ASSOC);

  $sql = "SELECT * FROM operators;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':u' => $_SESSION['auth']['username'],
  ]);
  $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);

  include_once("common.php");
  include_once("../views/user/edit-calibration.view.php");
}
?>