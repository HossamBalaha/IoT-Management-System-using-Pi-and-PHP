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
  $passwordNoErrors = false;
  $rePasswordNoErrors = false;

  if (isset($_POST["oldPassword"])) {
    $oldPassword = $_POST["oldPassword"];
    if (strlen($oldPassword) <= 0) {
      array_push($errors, VALID_OLDPASSWORD_REQ);
    } else if (strlen($oldPassword) > 125) {
      array_push($errors, VALID_OLDPASSWORD_MAX);
    } else if (strlen($oldPassword) < 6) {
      array_push($errors, VALID_OLDPASSWORD_MIN);
    }
  } else {
    array_push($errors, VALID_OLDPASSWORD_REQ);
  }

  if (isset($_POST["password"])) {
    $password = $_POST["password"];
    if (strlen($password) <= 0) {
      array_push($errors, VALID_PASSWORD_REQ);
    } else if (strlen($password) > 125) {
      array_push($errors, VALID_PASSWORD_MAX);
    } else if (strlen($password) < 6) {
      array_push($errors, VALID_PASSWORD_MIN);
    } else {
      $passwordNoErrors = true;
    }
  } else {
    array_push($errors, VALID_PASSWORD_REQ);
  }

  if (isset($_POST["retypePassword"])) {
    $retypePassword = $_POST["retypePassword"];
    if (strlen($retypePassword) <= 0) {
      array_push($errors, VALID_REPASSWORD_REQ);
    } else if (strlen($retypePassword) > 125) {
      array_push($errors, VALID_REPASSWORD_MAX);
    } else if (strlen($retypePassword) < 6) {
      array_push($errors, VALID_REPASSWORD_MIN);
    } else {
      $rePasswordNoErrors = true;
    }
  } else {
    array_push($errors, VALID_REPASSWORD_REQ);
  }

  if ($passwordNoErrors && $rePasswordNoErrors) {
    if (strcmp($password, $retypePassword) != 0) {
      array_push($errors, VALID_PASSWORD_MATCH);
    }
  }

  if (count($errors)) {
    $_SESSION["errors"] = $errors;
    header("Location: /user/settings.php");
    return;
  } else {

    $sql = "SELECT * FROM users WHERE username=:u;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":u" => $_SESSION['auth']['username'],
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($oldPassword, $record["password"])) {
      $sql = "UPDATE users SET password=:p WHERE username=:un;";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ":p" => password_hash($password, PASSWORD_BCRYPT),
        ":un" => $_SESSION['auth']['username'],
      ]);
      $_SESSION["success"] = UPDATE_SETTINGS_SUCCESS;
      header("Location: /user/settings.php");
      return;
    } else {
      array_push($errors, WRONG_OLD_PASSWORD);
      $_SESSION["errors"] = $errors;
      header("Location: /user/settings.php");
      return;
    }
  }
}


$sql = "SELECT * FROM users WHERE username=:u;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ":u" => $_SESSION["auth"]["username"],
]);
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

include_once("common.php");
include_once('../views/user/settings.view.php');
unset($_SESSION["form"]);
?>
