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

  $sql = "SELECT device_readings.reading AS reading, device_readings.created_at AS created_at, " .
    "device_readings.id AS did " .
    "FROM device_readings, user_devices " .
    "WHERE device_readings.user_device_id=user_devices.id AND " .
    "user_devices.id=:d AND user_devices.user_id=:uid LIMIT $offset, 10;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
    ':uid' => $userID,
  ]);
  $readingsTable = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($readingsTable);
  return;

} else if (strcmp($op, "live") == 0) {

  $sql = "SELECT device_readings.created_at AS x, device_readings.reading AS y " .
    "FROM device_readings, user_devices " .
    "WHERE device_readings.user_device_id=user_devices.id AND " .
    "user_devices.id=:d AND user_devices.user_id=:uid ORDER BY device_readings.created_at DESC LIMIT 0, 20;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
    ':uid' => $userID,
  ]);
  $readings = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($readings);
  return;

} else {
  echo null;
  return;
}

?>