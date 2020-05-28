<?php
if (strcmp(realpath(__FILE__),
    realpath($_SERVER["SCRIPT_FILENAME"])) == 0) {
  header("Location: /");
  return;
}

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['username']))) {
  header("Location: /auth/login.php");
  return;
}

$sql = "SELECT devices.name AS name, user_devices.id AS id " .
  "FROM user_devices, users, devices " .
  "WHERE user_devices.user_id = users.id AND " .
  "user_devices.device_id = devices.id AND " .
  "users.username=:u ORDER BY user_devices.created_at DESC LIMIT 0, 5;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':u' => $_SESSION["auth"]["username"],
]);
$devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
$_SESSION["devices"] = $devices;


$sql = "SELECT info_value FROM website_information WHERE info_key='logo';";
$stmt = $pdo->query($sql);
$logo = $stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION["logo"] = $logo['info_value'];

?>