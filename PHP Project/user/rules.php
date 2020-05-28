<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['username']))) {
  header("Location: /auth/login.php");
  return;
}

$rulesSQL = "SELECT devices.code AS code, user_devices.id AS id, " .
  "device_rules.actuator_id AS actID, operators.name AS operator, " .
  "(SELECT code FROM devices, user_devices WHERE devices.id = user_devices.device_id AND user_devices.id=actID) AS actCode, " .
  "device_rules.value AS value, device_rules.state AS state, " .
  "device_rules.created_at AS created_at, device_rules.id AS ruleID " .
  "FROM user_devices, users, devices, device_rules, operators " .
  "WHERE user_devices.user_id = users.id AND " .
  "user_devices.device_id = devices.id AND " .
  "operators.id = device_rules.operator_id AND ".
  "user_devices.id = device_rules.sensor_id AND " .
  "users.username=:u;";
$stmt = $pdo->prepare($rulesSQL);
$stmt->execute([
  ':u' => $_SESSION["auth"]["username"],
]);
$rulesTable = $stmt->fetchAll(PDO::FETCH_ASSOC);
include_once("common.php");
include_once("../views/user/rules.view.php");
?>