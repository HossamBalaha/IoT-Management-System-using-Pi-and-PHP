<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['username']))) {
  header("Location: /auth/login.php");
  return;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && count($_FILES) <= 0) {

  $errors = [];

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
  } else {
    $lastName = null;
  }

  if (isset($_POST["birthDate"])) {
    $birthDate = $_POST["birthDate"];
    if (strlen($birthDate) > 10) {
      array_push($errors, VALID_BIRTHDATE_MAX);
    }
  } else {
    $birthDate = null;
  }

  if (isset($_POST["bio"])) {
    $bio = $_POST["bio"];
    if (strlen($bio) > 1000) {
      array_push($errors, VALID_BIO_MAX);
    }
  } else {
    $bio = null;
  }

  if (count($errors)) {
    $_SESSION["errors"] = $errors;
    header("Location: /user/profile.php");
    return;
  } else {

    $gender = null;
    if (isset($_POST['gender'])) {
      $gender = $_POST['gender'];
      $sql = "SELECT COUNT(*) AS K FROM genders WHERE id=:gid;";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ":gid" => $gender,
      ]);
      $K = $stmt->fetch(PDO::FETCH_ASSOC)["K"];
      if ($K <= 0) {
        array_push($errors, VALID_GENDER_WRONG);
      }
    }

    if (count($errors) > 0) {
      $_SESSION["errors"] = $errors;
      header("Location: /user/profile.php");
      return;
    } else {
      $sql = "UPDATE users SET first_name=:fn, last_name=:ln, gender_id=:gid, " .
        "bio=:b, birthdate=:bd WHERE username=:un;";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ":fn" => $firstName,
        ":ln" => $lastName,
        ":b" => $bio,
        ":bd" => $birthDate,
        ":gid" => $gender,
        ":un" => $_SESSION['auth']['username'],
      ]);
      $_SESSION['auth']['fullName'] = $firstName . " " . $lastName;
      $_SESSION["success"] = UPDATE_PROFILE_SUCCESS;
      header("Location: /user/profile.php");
      return;
    }
  }
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && count($_FILES) > 0) {

  $errors = [];

  if (isset($_FILES["avatar"])) {
    $avatar = $_FILES['avatar'];
    $size = $avatar['size'] / (1024 * 1024);
    $extension = pathinfo($avatar['name'], PATHINFO_EXTENSION);
    $extension = strtolower($extension);

    if ($size > 2) {
      array_push($errors, VALID_AVATAR_MAX);
    } else if (!in_array($extension, ['jpg', 'png', 'jpeg'])) {
      array_push($errors, VALID_AVATAR_EXT);
    }
  } else {
    array_push($errors, VALID_AVATAR_REQ);
  }

  if (count($errors) > 0) {
    $_SESSION["errors"] = $errors;
    header("Location: /user/profile.php");
    return;
  } else {
    $avatarName = md5(time()) . "." . $extension;
    $isMoved = move_uploaded_file($avatar['tmp_name'],
      "../uploads/avatars/" . $avatarName);
    if (!$isMoved) {
      array_push($errors, SOMETHING_WRONG);
      $_SESSION["errors"] = $errors;
      header("Location: /user/profile.php");
      return;
    }

    $sql = "INSERT INTO avatars (user_id, name, size, extension) VALUES " .
      "((SELECT id FROM users WHERE username=:u), :n, :s, :e);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':u' => $_SESSION['auth']['username'],
      ':n' => $avatarName,
      ':s' => $avatar['size'],
      ':e' => $extension,
    ]);

    $_SESSION['auth']['avatar'] = $avatarName;
    $_SESSION["success"] = AVATAR_SUCCESS;
    header("Location: /user/profile.php");
    return;
  }
}


$sql = "SELECT * FROM users WHERE username=:u;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ":u" => $_SESSION["auth"]["username"],
]);
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM genders;";
$stmt = $pdo->query($sql);
$genders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$genderName = "";
foreach ($genders as $gender) {
  if ($gender['id'] == $userInfo['gender_id']) {
    $genderName = $gender['name'];
  }
}

include_once("common.php");
include_once('../views/user/profile.view.php');
unset($_SESSION["form"]);
?>
