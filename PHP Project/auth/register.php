<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (isset($_SESSION['auth']) && isset($_SESSION['auth']['username'])) {
  header("Location: /user/dashboard.php");
  return;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $errors = [];
  $passwordNoErrors = false;
  $rePasswordNoErrors = false;

  if (isset($_POST["firstName"])) {
    $firstName = $_POST["firstName"];
    if (strlen($firstName) <= 0) {
      array_push($errors, VALID_FIRSTNAME_REQ);
    } else if (strlen($firstName) > 100) {
      array_push($errors, VALID_FIRSTNAME_MAX);
    }
  } else {
    array_push($errors, VALID_FIRSTNAME_REQ);
  }

  if (isset($_POST["lastName"])) {
    $lastName = $_POST["lastName"];
    if (strlen($lastName) > 100) {
      array_push($errors, VALID_LASTNAME_MAX);
    }
  }

  if (isset($_POST["username"])) {
    $username = $_POST["username"];
    if (strlen($username) <= 0) {
      array_push($errors, VALID_USERNAME_REQ);
    } else if (strlen($username) > 125) {
      array_push($errors, VALID_USERNAME_MAX);
    } else if (strlen($username) < 6) {
      array_push($errors, VALID_USERNAME_MIN);
    }
  } else {
    array_push($errors, VALID_USERNAME_REQ);
  }

  if (isset($_POST["email"])) {
    $email = $_POST["email"];
    if (strlen($email) <= 0) {
      array_push($errors, VALID_EMAIL_REQ);
    } else if (strlen($email) > 125) {
      array_push($errors, VALID_EMAIL_MAX);
    } else if (strlen($email) < 5) {
      array_push($errors, VALID_EMAIL_MIN);
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      array_push($errors, VALID_EMAIL_FORMAT);
    }
  } else {
    array_push($errors, VALID_EMAIL_REQ);
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
    $_SESSION["form"] = [
      "firstName" => $firstName,
      "lastName" => $lastName,
      "username" => $username,
      "email" => $email,
    ];
    $_SESSION["errors"] = $errors;
    header("Location: /auth/register.php");
    return;
  } else {
    $sql = "SELECT COUNT(*) AS K FROM users WHERE username=:u;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":u" => $username,
    ]);
    $K = $stmt->fetch(PDO::FETCH_ASSOC)["K"];
    //var_dump($K);
    if ($K > 0) {
      array_push($errors, VALID_USERNAME_EXIST);
    }

    $sql = "SELECT COUNT(*) AS K FROM users WHERE email=:e;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":e" => $email,
    ]);
    $K = $stmt->fetch(PDO::FETCH_ASSOC)["K"];
    //var_dump($K);
    if ($K > 0) {
      array_push($errors, VALID_EMAIL_EXIST);
    }

    if (count($errors)) {
      $_SESSION["form"] = [
        "firstName" => $firstName,
        "lastName" => $lastName,
        "username" => $username,
        "email" => $email,
      ];
      $_SESSION["errors"] = $errors;
      header("Location: /auth/register.php");
      return;
    } else {
      $sql = "INSERT INTO users (first_name, last_name, username, email, password) " .
        "VALUES(:f, :l, :u, :e, :p);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ":f" => $firstName,
        ":l" => $lastName,
        ":e" => $email,
        ":u" => $username,
        ":p" => password_hash($password, PASSWORD_BCRYPT),
      ]);
      unset($_SESSION["form"]);
      $_SESSION["success"] = REGISTER_SUCCESS;
      header("Location: /auth/login.php");
      return;
    }
  }
}

include_once('../views/auth/register.view.php');
unset($_SESSION["form"]);
?>
