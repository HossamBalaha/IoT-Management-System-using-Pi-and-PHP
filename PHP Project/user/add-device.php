<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['username']))) {
  header("Location: /auth/login.php");
  return;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $errors = [];

  if (isset($_POST["deviceCard"])) {
    $deviceCard = $_POST["deviceCard"];
    if (strlen($deviceCard) <= 0) {
      array_push($errors, VALID_DEVICECARD_REQ);
    } else if (strlen($deviceCard) > 15) {
      array_push($errors, VALID_DEVICECARD_MAX);
    } else if (strlen($deviceCard) < 8) {
      array_push($errors, VALID_DEVICECARD_MIN);
    }
  } else {
    array_push($errors, VALID_DEVICECARD_REQ);
  }

  if (count($errors)) {
    $_SESSION["form"] = [
      "deviceCard" => $deviceCard,
    ];
    $_SESSION["errors"] = $errors;
    header("Location: /user/add-device.php");
    return;
  } else {
    $sql = "SELECT COUNT(*) AS K FROM devices WHERE code=:c;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':c' => $deviceCard,
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $noOfDevices = $record["K"];
    if ($noOfDevices <= 0) {
      array_push($errors, VALID_DEVICECARD_FOUND);
    }

    if (count($errors) > 0) {
      $_SESSION["form"] = [
        "deviceCard" => $deviceCard,
      ];
      $_SESSION["errors"] = $errors;
      header("Location: /user/add-device.php");
      return;
    }

    $sql = "SELECT COUNT(*) AS K FROM user_devices WHERE ".
      "device_id=(SELECT id FROM devices WHERE code=:c);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':c' => $deviceCard,
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $noOfUsedDevices = $record["K"];
    if ($noOfUsedDevices > 0) {
      array_push($errors, VALID_DEVICECARD_USED);
    }

    if (count($errors) > 0) {
      $_SESSION["form"] = [
        "deviceCard" => $deviceCard,
      ];
      $_SESSION["errors"] = $errors;
      header("Location: /user/add-device.php");
      return;
    }

    $sql = "INSERT INTO user_devices (user_id, device_id) VALUES " .
    "((SELECT id FROM users WHERE username=:u), (SELECT id FROM devices WHERE code=:c));";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':u' => $_SESSION['auth']['username'],
      ':c' => $deviceCard,
    ]);

    unset($_SESSION["form"]);
    $_SESSION['success'] = DEVICE_ADDED;
    if (isset($_POST["btnChoice"]) && $_POST["btnChoice"] == 1) {
      $sql = "SELECT id FROM user_devices WHERE ".
        "device_id=(SELECT id FROM devices WHERE code=:c);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':c' => $deviceCard,
      ]);
      $record = $stmt->fetch(PDO::FETCH_ASSOC);
      $d = $record["id"];
      header("Location: /user/device.php?d=$d&op=show");
    } else {
      header("Location: /user/devices.php");
    }
    return;
  }
}

include_once("common.php");
include_once("../views/user/add-device.view.php");
unset($_SESSION["form"]);
?>