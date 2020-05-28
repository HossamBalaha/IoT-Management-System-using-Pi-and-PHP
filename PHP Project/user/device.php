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
  "FROM users, user_devices " .
  "WHERE users.id = user_devices.user_id AND " .
  "users.username=:u AND user_devices.id=:d;";
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

if (strcmp($op, "show") == 0) {

  $sql = "SELECT * " .
    "FROM users, user_devices, devices " .
    "WHERE users.id = user_devices.user_id AND " .
    "devices.id = user_devices.device_id AND " .
    "users.username=:u AND user_devices.id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":u" => $_SESSION['auth']['username'],
    ':d' => $d,
  ]);
  $userDevice = $stmt->fetch(PDO::FETCH_ASSOC);

  $sql = "SELECT *, device_readings.id AS did FROM device_readings, user_devices " .
    "WHERE device_readings.user_device_id=user_devices.id AND " .
    "user_devices.id=:d ORDER BY device_readings.created_at DESC LIMIT 0, 10;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);
  $readingsTable = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $alarmsSQL = "SELECT device_readings.reading AS reading, device_calibrations.message AS message,  " .
    "device_alarms.created_at AS created_at, user_devices.id AS id, device_alarms.id AS aid " .
    "FROM user_devices, users, devices, device_alarms, device_readings, device_calibrations " .
    "WHERE user_devices.user_id = users.id AND " .
    "user_devices.device_id = devices.id AND " .
    "user_devices.id = device_readings.user_device_id AND " .
    "device_readings.id = device_alarms.device_reading_id AND " .
    "device_calibrations.id = device_alarms.device_calibration_id AND " .
    "users.username=:u AND user_devices.id=:d ORDER BY device_alarms.created_at DESC LIMIT 0, 10;";
  $stmt = $pdo->prepare($alarmsSQL);
  $stmt->execute([
    ':u' => $_SESSION["auth"]["username"],
    ':d' => $d,
  ]);
  $alarmsTable = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $calibrationsSQL = "SELECT device_calibrations.id AS cid, ".
    "device_calibrations.value AS value, device_calibrations.message AS message, ".
    "device_calibrations.created_at AS created_at, operators.name AS name ".
    "FROM users, user_devices, device_calibrations, operators " .
    "WHERE user_devices.id = device_calibrations.user_device_id AND " .
    "users.id = user_devices.user_id AND users.username=:u AND " .
    "device_calibrations.operator_id = operators.id AND user_devices.id=:d";
  $stmt = $pdo->prepare($calibrationsSQL);
  $stmt->execute([
    ':u' => $_SESSION["auth"]["username"],
    ':d' => $d,
  ]);
  $calibrationsTable = $stmt->fetchAll(PDO::FETCH_ASSOC);

  include_once("common.php");
  include_once("../views/user/device.view.php");
} else if (strcmp($op, "delete") == 0) {
  $sql = "DELETE FROM user_devices WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);
  header("Location: /user/devices.php");
  return;
}else if (strcmp($op, "toggle") == 0) {
  $sql = "UPDATE user_devices SET is_on=not is_on WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);
  header("Location: /user/device.php?d=$d&op=show");
  return;
}
?>