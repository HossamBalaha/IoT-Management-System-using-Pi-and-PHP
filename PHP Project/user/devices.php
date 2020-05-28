<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['username']))) {
  header("Location: /auth/login.php");
  return;
}

$sql = "SELECT devices.name AS name, devices.description AS description, ".
  "devices.type AS type, devices.code AS code, user_devices.id AS id, user_devices.is_on AS is_on " .
  "FROM user_devices, users, devices " .
  "WHERE user_devices.user_id = users.id AND " .
  "user_devices.device_id = devices.id AND " .
  "users.username=:u ORDER BY user_devices.created_at DESC LIMIT 0, 5;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':u' => $_SESSION["auth"]["username"],
]);
$devicesTable = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once("common.php");
include_once("../views/user/devices.view.php");
?>