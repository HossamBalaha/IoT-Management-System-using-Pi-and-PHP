<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");

if (!isset($_GET['d']) || !isset($_GET['op']) || !isset($_GET['token'])) {
  echo null;
  return;
}

$op = $_GET['op'];
$d = $_GET['d'];
$token = $_GET['token'];

$sql = "SELECT user_id FROM user_tokens WHERE token=:t;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ":t" => $token,
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$record) {
  echo null;
  return;
}
$userID = $record['user_id'];

if (strcmp($op, "load") == 0) {
  if (!isset($_GET['offset'])) {
    $offset = 0;
  } else {
    $offset = $_GET['offset'];
  }

  $sql = "SELECT device_readings.reading AS reading, device_calibrations.message AS message,  " .
    "device_alarms.created_at AS created_at, user_devices.id AS id, device_alarms.id AS aid " .
    "FROM user_devices, users, devices, device_alarms, device_readings, device_calibrations " .
    "WHERE user_devices.user_id = users.id AND " .
    "user_devices.device_id = devices.id AND " .
    "user_devices.id = device_readings.user_device_id AND " .
    "device_readings.id = device_alarms.device_reading_id AND " .
    "device_calibrations.id = device_alarms.device_calibration_id AND " .
    "users.id=:uid AND user_devices.id=:d LIMIT $offset, 10;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
    ':uid' => $userID,
  ]);
  $alarmsTable = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($alarmsTable);
  return;

} else {
  echo null;
  return;
}

?>