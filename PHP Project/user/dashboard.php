<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['username']))) {
  header("Location: /auth/login.php");
  return;
}

$devicesSQL = "SELECT COUNT(*) AS K " .
  "FROM user_devices, users, devices " .
  "WHERE user_devices.user_id = users.id AND " .
  "user_devices.device_id = devices.id AND " .
  "users.username=:u;";
$stmt = $pdo->prepare($devicesSQL);
$stmt->execute([
  ':u' => $_SESSION["auth"]["username"],
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$devicesCount = $record["K"];

$alarmsSQL = "SELECT COUNT(*) AS K " .
  "FROM user_devices, users, devices, device_alarms, device_readings, device_calibrations " .
  "WHERE user_devices.user_id = users.id AND " .
  "user_devices.device_id = devices.id AND " .
  "user_devices.id = device_readings.user_device_id AND " .
  "device_readings.id = device_alarms.device_reading_id AND " .
  "device_calibrations.id = device_alarms.device_calibration_id AND " .
  "users.username=:u;";
$stmt = $pdo->prepare($alarmsSQL);
$stmt->execute([
  ':u' => $_SESSION["auth"]["username"],
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$alarmsCount = $record["K"];

$rulesSQL = "SELECT COUNT(*) AS K " .
  "FROM user_devices, users, devices, device_rules " .
  "WHERE user_devices.user_id = users.id AND " .
  "user_devices.device_id = devices.id AND " .
  "user_devices.id = device_rules.sensor_id AND " .
  "users.username=:u;";
$stmt = $pdo->prepare($rulesSQL);
$stmt->execute([
  ':u' => $_SESSION["auth"]["username"],
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$rulesCount = $record["K"];

include_once("common.php");
include_once ('../views/user/dashboard.view.php');
?>