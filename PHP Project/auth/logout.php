<?php
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['username']))) {
  header("Location: /auth/login.php");
  return;
}

$sql = "DELETE FROM user_tokens WHERE token=:t;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ":t" => $_SESSION['auth']['token'],
]);


unset($_SESSION['auth']);
session_destroy();
header("Location: /auth/login.php");
return;