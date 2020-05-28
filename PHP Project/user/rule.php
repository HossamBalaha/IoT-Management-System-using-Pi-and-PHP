<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['username']))) {
  header("Location: /auth/login.php");
  return;
}

if (!isset($_GET['op']) || !isset($_GET['d'])) {
  header("Location: /user/rules.php");
  return;
}

$op = $_GET['op'];
$d = $_GET['d'];

$sql = "SELECT COUNT(*) AS K " .
  "FROM device_rules, users, user_devices " .
  "WHERE device_rules.sensor_id = user_devices.id AND " .
  "users.id = user_devices.user_id AND " .
  "users.username=:u AND device_rules.id=:d;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ":u" => $_SESSION['auth']['username'],
  ':d' => $d,
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$K = $record['K'];
if ($K <= 0) {
  header("Location: /user/rules.php");
  return;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (strcmp($op, "update") != 0) {
    header("Location: /user/rules.php");
    return;
  }

  $errors = [];
  $reading = null;
  $state = null;
  $reading = null;
  $sensor = null;
  $actuator = null;

  if (!isset($_POST['sensor'])) {
    array_push($errors, VALID_SENSOR_REQ);
  } else {
    $sensor = $_POST['sensor'];
  }

  if (!isset($_POST['actuator'])) {
    array_push($errors, VALID_ACTUATOR_REQ);
  } else {
    $actuator = $_POST['actuator'];
  }

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

  if (!isset($_POST['operator'])) {
    array_push($errors, VALID_OPERATOR_REQ);
  } else {
    $operator = $_POST['operator'];
  }

  if (!isset($_POST['state'])) {
    array_push($errors, VALID_STATE_REQ);
  } else {
    $state = $_POST['state'];
    if (!in_array($state, [1, 0])) {
      array_push($errors, VALID_STATE_REQ);
    }
  }

  if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header("Location: /user/rule.php?d=$d&op=edit");
    return;
  } else {
    $sql = "SELECT COUNT(*) AS K " .
      "FROM user_devices, devices, users " .
      "WHERE user_devices.device_id = devices.id AND " .
      "user_devices.user_id = users.id AND " .
      "users.username = :u AND devices.type=1 AND user_devices.id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':u' => $_SESSION['auth']['username'],
      ':id' => $sensor,
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $noOfSensors = $record["K"];
    if ($noOfSensors <= 0) {
      array_push($errors, VALID_SENSOR_REQ);
    }

    $sql = "SELECT COUNT(*) AS K " .
      "FROM user_devices, devices, users " .
      "WHERE user_devices.device_id = devices.id AND " .
      "user_devices.user_id = users.id AND " .
      "users.username = :u AND devices.type=2 AND user_devices.id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':u' => $_SESSION['auth']['username'],
      ':id' => $actuator,
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $noOfActuators = $record["K"];
    if ($noOfActuators <= 0) {
      array_push($errors, VALID_ACTUATOR_REQ);
    }

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
      header("Location: /user/rule.php?d=$d&op=edit");
      return;
    } else {
      $sql = "UPDATE device_rules SET sensor_id=:s, actuator_id=:a, value=:r, state=:st, " .
        "operator_id=:o WHERE id=:d;";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':s' => $sensor,
        ':a' => $actuator,
        ':r' => $reading,
        ':o' => $operator,
        ':st' => $state,
        ':d' => $d,
      ]);

      $_SESSION['success'] = RULE_UPDATE_SUCCESS;
      header("Location: /user/rules.php");
      return;
    }
  }
}

if (strcmp($op, "edit") == 0) {
  $sql = "SELECT user_devices.id AS id, devices.code AS code, devices.name AS name " .
    "FROM user_devices, devices, users " .
    "WHERE user_devices.device_id = devices.id AND " .
    "user_devices.user_id = users.id AND " .
    "users.username = :u AND devices.type=1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':u' => $_SESSION['auth']['username'],
  ]);
  $sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sql = "SELECT user_devices.id AS id, devices.code AS code, devices.name AS name " .
    "FROM user_devices, devices, users " .
    "WHERE user_devices.device_id = devices.id AND " .
    "user_devices.user_id = users.id AND " .
    "users.username = :u AND devices.type=2";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':u' => $_SESSION['auth']['username'],
  ]);
  $actuators = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sql = "SELECT * FROM operators;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':u' => $_SESSION['auth']['username'],
  ]);
  $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sql = "SELECT * FROM device_rules WHERE id=:r;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':r' => $d,
  ]);
  $selectedRule = $stmt->fetch(PDO::FETCH_ASSOC);

  include_once("common.php");
  include_once("../views/user/edit-rule.view.php");
  unset($_SESSION["form"]);
} else if (strcmp($op, "delete") == 0) {
  $sql = "DELETE FROM device_rules WHERE id=:r;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':r' => $d,
  ]);
  header("Location: /user/rules.php");
  return;
}
?>