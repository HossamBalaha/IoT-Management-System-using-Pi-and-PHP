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

  if (count($errors)) {
    $_SESSION["form"] = [
      "username" => $username,
    ];
    $_SESSION["errors"] = $errors;
    header("Location: /auth/login.php");
    return;
  } else {

    $sql = "SELECT * FROM users WHERE username=:u;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":u" => $username,
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$record) {
      array_push($errors, WRONG_USERNAME);
      $_SESSION["errors"] = $errors;
      header("Location: /auth/login.php");
      return;
    } else {
      if (password_verify($password, $record["password"])) {
        $sql = "SELECT * FROM avatars WHERE user_id=:uid ORDER BY id DESC;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          ":uid" => $record["id"],
        ]);
        $avatar = $stmt->fetch(PDO::FETCH_ASSOC);

        $token = md5(time());
        $_SESSION["auth"] = [
          "token" => $token,
          "username" => $record["username"],
          "avatar" => $avatar["name"],
          "fullName" => $record["first_name"] . " " . $record["last_name"],
        ];

        $sql = "INSERT INTO user_tokens(user_id, token) VALUES(:uid, :t);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          ":uid" => $record["id"],
          ":t" => $token,
        ]);

        unset($_SESSION["form"]);
        $_SESSION["success"] = LOGIN_SUCCESS;
        header("Location: /user/dashboard.php");
        return;
      } else {
        array_push($errors, WRONG_AUTH);
        $_SESSION["errors"] = $errors;
        header("Location: /auth/login.php");
        return;
      }
    }
  }
}

include_once('../views/auth/login.view.php');
unset($_SESSION["form"]);
?>
