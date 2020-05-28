<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <title>Profile</title>
</head>
<body>

<?php include_once("common/header.view.php"); ?>

<div class="container my-5">
  <div class="row">
    <div class="col-12">
      <div class="text-center">
        <div class="center-middle">
          <form action="/user/profile.php" method="POST"
                id="avatarForm"
                enctype="multipart/form-data">
            <input type="file" name="avatar" id="avatar" class="d-none">
            <label for="avatar" id="avatarLabel" class="d-none">
              <i class="fa fa-edit fa-2x text-white"></i>
            </label>
          </form>
        </div>
        <img
            src="/uploads/avatars/<?php echo $_SESSION['auth']['avatar'] ? $_SESSION['auth']['avatar'] : 'default.png'; ?>"
            width="200" height="200" id="avatarImage"
            class="rounded-circle shadow shadow-lg border border-dark"
            alt="">
        <h1 class="display-4 text-center">
          <?php echo $_SESSION['auth']['fullName']; ?>
        </h1>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-12">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="text-center card-title">
            My Information
          </h3>
          <hr width="75%">
          <div class="row">
            <div class="col-12 col-md-8 offset-0 offset-md-2">
              <?php if (isset($_SESSION["success"]) && strlen($_SESSION['success']) > 0) { ?>
                <div class="alert alert-success">
                  <p class="m-0">
                    <i class="fa fa-caret-right mr-1"></i>
                    <?php echo $_SESSION['success']; ?>
                  </p>
                  <?php unset($_SESSION["success"]); ?>
                </div>
              <?php } ?>

              <div
                  class="alert alert-danger <?php echo (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) ? '' : 'd-none'; ?>"
                  id="errorsContainer">
                <?php if (isset($_SESSION["errors"]) && count($_SESSION['errors']) > 0) { ?>
                  <?php foreach ($_SESSION["errors"] as $error) { ?>
                    <p class="m-0">
                      <i class="fa fa-caret-right mr-1"></i>
                      <?php echo $error; ?>
                    </p>
                  <?php } ?>
                  <?php unset($_SESSION["errors"]); ?>
                <?php } ?>
              </div>
              <form action="/user/profile.php" method="POST">
                <div class="form-group row">
                  <label for="firstName" class="col-form-label col-12 col-md-3">
                    First Name
                  </label>
                  <div class="col-12 col-md-9">
                    <span id="firstNameSpan" class="toggle-span">
                      <?php echo $userInfo['first_name']; ?>
                    </span>
                    <input type="text" name="firstName"
                           value="<?php echo $userInfo['first_name']; ?>"
                           id="firstName" class="form-control d-none toggle-input">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="lastName" class="col-form-label col-12 col-md-3">
                    Last Name
                  </label>
                  <div class="col-12 col-md-9">
                    <span id="lastNameSpan" class="toggle-span">
                      <?php echo $userInfo['last_name']; ?>
                    </span>
                    <input type="text" name="lastName"
                           value="<?php echo $userInfo['last_name']; ?>"
                           id="lastName" class="form-control d-none toggle-input">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="birthDate" class="col-form-label col-12 col-md-3">
                    Birth Date
                  </label>
                  <div class="col-12 col-md-9">
                    <span id="birthDateSpan" class="toggle-span">
                      <?php echo $userInfo['birthdate']; ?>
                    </span>
                    <input type="date" name="birthDate"
                           value="<?php echo $userInfo['birthdate']; ?>"
                           id="birthDate" class="form-control d-none toggle-input">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="gender" class="col-form-label col-12 col-md-3">
                    Gender
                  </label>
                  <div class="col-12 col-md-9">
                    <span id="genderSpan" class="toggle-span">
                      <?php echo $genderName; ?>
                    </span>
                    <select name="gender" id="gender"
                            class="form-control d-none toggle-input">
                      <?php foreach ($genders as $gender) { ?>
                        <option <?php echo ($gender['id'] == $userInfo['gender_id']) ? "selected" : "" ?>
                            value="<?php echo $gender['id']; ?>">
                          <?php echo $gender['name']; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="bio" class="col-form-label col-12 col-md-3">
                    Bio.
                  </label>
                  <div class="col-12 col-md-9">
                    <span id="bioSpan" class="toggle-span">
                      <?php echo $userInfo['bio']; ?>
                    </span>
                    <textarea name="bio" id="bio"
                              class="form-control d-none toggle-input"
                              cols="30" rows="2"><?php echo $userInfo['bio']; ?></textarea>
                  </div>
                </div>

                <div class="text-right">
                  <button class="btn btn-primary toggle-span" id="editBtn">
                    <i class="fa fa-edit"></i>
                    Edit
                  </button>
                  <button class="btn btn-danger d-none toggle-input" id="cancelBtn">
                    <i class="fa fa-trash"></i>
                    Cancel
                  </button>
                  <button class="btn btn-success d-none toggle-input"
                          type="submit"
                          id="updateBtn">
                    <i class="fa fa-check"></i>
                    Update
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/all.min.js"></script>
<script src="/assets/js/app.js"></script>

<script>
  $(document).ready(function () {
    var editBtn = $("#editBtn");
    var updateBtn = $("#updateBtn");
    var cancelBtn = $("#cancelBtn");
    var avatarImage = $("#avatarImage");
    var avatarLabel = $("#avatarLabel");
    var avatar = $("#avatar");
    var firstName = $("#firstName");
    var lastName = $("#lastName");
    var gender = $("#gender");
    var birthDate = $("#birthDate");
    var bio = $("#bio");

    updateBtn.click(function (e) {
      var errors = [];
      var errorsContainer = $("#errorsContainer");
      errorsContainer.html("");
      if (!errorsContainer.hasClass("d-none"))
        errorsContainer.addClass("d-none");

      var firstNameValue = firstName.val().trim().length;
      var lastNameValue = lastName.val().trim().length;
      var genderValue = gender.val().trim().length;
      var birthDateValue = birthDate.val().trim().length;
      var bioValue = bio.val().trim().length;

      if (firstNameValue <= 0) {
        errors.push("<?php echo VALID_FIRSTNAME_REQ; ?>");
      } else if (firstNameValue > 100) {
        errors.push("<?php echo VALID_FIRSTNAME_MAX; ?>");
      }

      if (lastNameValue > 100) {
        errors.push("<?php echo VALID_LASTNAME_MAX; ?>");
      }

      if (birthDateValue > 10) {
        errors.push("<?php echo VALID_BIRTHDATE_MAX; ?>");
      }

      if (bioValue > 1000) {
        errors.push("<?php echo VALID_BIO_MAX; ?>");
      }

      if (errors.length > 0) {
        e.preventDefault();

        var allErrorsHTML = "";
        for (var i = 0; i < errors.length; i++) {
          allErrorsHTML += generateErrorItem(errors[i]);
        }
        errorsContainer.html(allErrorsHTML);
        errorsContainer.removeClass("d-none");
      }
    });

    avatar.change(function () {
      var fileName = $(this).val();
      if (fileName.trim().length > 0) {
        var isConfirm = confirm("<?php echo CONFIRM_AVATAR_UPDATE; ?>");
        if (isConfirm) {
          var file = $(this)[0].files[0];
          var nameArr = file["name"].split(".");
          var extension = nameArr[nameArr.length - 1];
          var size = file["size"] / (1024 * 1024);
          if (!(extension == "jpg" || extension == "jpeg" || extension == "png")) {
            alert("<?php echo VALID_AVATAR_EXT; ?>");
            return;
          }
          if (size > 2) {
            alert("<?php echo VALID_AVATAR_MAX; ?>");
            return;
          }
          $("#avatarForm").submit();
        }
      }
    });

    avatarImage.mouseenter(function () {
      avatarLabel.removeClass("d-none");
    });
    avatarImage.mouseleave(function () {
      avatarLabel.addClass("d-none");
    });

    editBtn.click(function (e) {
      e.preventDefault();
      $(".toggle-input").removeClass("d-none");
      $(".toggle-span").addClass("d-none");
    });

    cancelBtn.click(function (e) {
      e.preventDefault();
      $(".toggle-input").addClass("d-none");
      $(".toggle-span").removeClass("d-none");
    });
  });
</script>
</body>
</html>