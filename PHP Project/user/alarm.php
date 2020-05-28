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

if (strcmp($op, "delete-all") == 0) {
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

  $sql = "DELETE FROM device_alarms WHERE device_reading_id IN " .
    "(SELECT device_readings.id FROM user_devices, device_readings WHERE ".
    "user_devices.id=device_readings.user_device_id AND user_devices.id=:d);";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);

  $_SESSION['success'] = DELETE_ALL_SUCCESS;
  header("Location: /user/device.php?d=$d&op=show");
  return;
} else if (strcmp($op, "delete") == 0) {
  $sql = "SELECT COUNT(*) AS K FROM user_devices, device_readings, device_alarms, users ".
    "WHERE device_alarms.device_reading_id = device_readings.id ".
    "AND user_devices.id = device_readings.user_device_id AND device_alarms.id=:d ".
    "AND users.id = user_devices.user_id AND users.username=:u;";
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

  $sql = "SELECT user_devices.id AS did FROM user_devices, device_readings, device_alarms ".
    "WHERE device_alarms.device_reading_id = device_readings.id ".
    "AND user_devices.id = device_readings.user_device_id AND device_alarms.id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  $deviceID = $record['did'];

  $sql = "DELETE FROM device_alarms WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);

  $_SESSION['success'] = DELETE_SUCCESS;
  header("Location: /user/device.php?d=$deviceID&op=show");
  return;
}
?>