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
    $_SESSION['form'] = [
      'actuator' => $actuator,
      'sensor' => $sensor,
      'operator' => $operator,
      'state' => $state,
      'reading' => $reading,
    ];
    $_SESSION['errors'] = $errors;
    header("Location: /user/add-rule.php");
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
      $_SESSION['form'] = [
        'actuator' => $actuator,
        'sensor' => $sensor,
        'operator' => $operator,
        'state' => $state,
        'reading' => $reading,
      ];
      $_SESSION['errors'] = $errors;
      header("Location: /user/add-rule.php");
      return;
    } else {
      $sql = "INSERT INTO device_rules (sensor_id, actuator_id, value, operator_id, state) VALUES " .
        "(:s, :a, :r, :o, :st);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':s' => $sensor,
        ':a' => $actuator,
        ':r' => $reading,
        ':o' => $operator,
        ':st' => $state,
      ]);

      unset($_SESSION["form"]);
      $_SESSION['success'] = RULE_SUCCESS;
      header("Location: /user/rules.php");
      return;
    }
  }
}

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

include_once("common.php");
include_once("../views/user/add-rule.view.php");
unset($_SESSION["form"]);
?>