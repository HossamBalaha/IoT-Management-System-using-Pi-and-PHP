<?php
if (strcmp(realpath(__FILE__),
    realpath($_SERVER["SCRIPT_FILENAME"])) == 0) {
  header("Location: /");
  return;
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">
    <img src="<?php echo $_SESSION['logo']; ?>"
         width="50" height="50"
         class="rounded-circle"
         alt="">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/user/dashboard.php">Dashboard</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
          Devices
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php if (count($_SESSION['devices']) > 0) { ?>
            <?php foreach ($_SESSION['devices'] as $device) { ?>
              <a class="dropdown-item"
                 href="/user/device.php?d=<?php echo $device['id']; ?>&op=show">
                <?php echo $device['name']; ?>
              </a>
            <?php } ?>
          <?php } else { ?>
            <div class="text-center text-danger">
              <?php echo NO_DEVICES; ?>
            </div>
          <?php } ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/user/devices.php">All Devices</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/user/add-device.php">Add New Device</a>
        </div>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
          <?php echo $_SESSION['auth']['fullName']; ?>
          <img
              src="/uploads/avatars/<?php echo $_SESSION['auth']['avatar'] ? $_SESSION['auth']['avatar'] : 'default.png'; ?>"
              width="50" height="50"
              class="rounded-circle"
              alt="">
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/user/profile.php">Profile</a>
          <a class="dropdown-item" href="/user/settings.php">Settings</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/auth/logout.php">Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>