<?php
include_once("../helpers/conn.php");

if (isset($_GET['op'])) {
  $op = $_GET['op'];

  // op => store (POST)
  // op => load (GET)

  if ($_SERVER['REQUEST_METHOD'] == "POST" && $op == "store") {
    if (isset($_POST['code']) && isset($_POST['reading'])) {
      $code = $_POST['code'];
      $reading = $_POST['reading'];

      $sql = "INSERT INTO device_readings (user_device_id, reading) VALUES " .
        "((SELECT user_devices.id FROM user_devices, devices WHERE " .
        "devices.id=user_devices.device_id AND devices.code=:c), :r);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ":c" => $code,
        ":r" => $reading,
      ]);

      $dID = $pdo->lastInsertId();

      $sql = "SELECT * FROM device_calibrations WHERE " .
        "user_device_id=(SELECT user_devices.id FROM user_devices, devices WHERE " .
        "devices.id=user_devices.device_id AND devices.code=:c);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ":c" => $code,
      ]);
      $calibrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $sql = "";
      foreach ($calibrations as $calibration) {
        if ($calibration['operator_id'] == 5 && $reading == $calibration['value']) {
          $sql .= "INSERT INTO device_alarms (device_reading_id, device_calibration_id) VALUES " .
            "($dID, {$calibration['id']});";
        } else if ($calibration['operator_id'] == 1 && $reading < $calibration['value']) {
          $sql .= "INSERT INTO device_alarms (device_reading_id, device_calibration_id) VALUES " .
            "($dID, {$calibration['id']});";
        } else if ($calibration['operator_id'] == 2 && $reading <= $calibration['value']) {
          $sql .= "INSERT INTO device_alarms (device_reading_id, device_calibration_id) VALUES " .
            "($dID, {$calibration['id']});";
        } else if ($calibration['operator_id'] == 3 && $reading > $calibration['value']) {
          $sql .= "INSERT INTO device_alarms (device_reading_id, device_calibration_id) VALUES " .
            "($dID, {$calibration['id']});";
        } else if ($calibration['operator_id'] == 4 && $reading >= $calibration['value']) {
          $sql .= "INSERT INTO device_alarms (device_reading_id, device_calibration_id) VALUES " .
            "($dID, {$calibration['id']});";
        }
      }
      if (strlen($sql) > 0) {
        $pdo->query($sql);
      }


      echo "Success";
      return;

    } else {
      echo "Failure";
      return;
    }
  } else if ($_SERVER['REQUEST_METHOD'] == "GET" && $op == "load") {
    if (isset($_GET['code'])) {
      $code = $_GET['code'];

      $sql = "SELECT * FROM user_devices WHERE device_id=(" .
        "SELECT id FROM devices WHERE code=:c)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ":c" => $code,
      ]);
      $userDevice = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$userDevice) {
        echo "Failure";
        return;
      }

      $isON = $userDevice['is_on'] == "1";

      $sql = "SELECT * FROM device_rules WHERE actuator_id={$userDevice['id']};";
      $stmt = $pdo->query($sql);
      $deviceRules = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $ruleState = false;
      foreach ($deviceRules as $deviceRule) {
        $sql = "SELECT * FROM device_readings WHERE user_device_id={$deviceRule['sensor_id']} " .
          "ORDER BY created_at DESC;";
        $stmt = $pdo->query($sql);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        $reading = $record['reading'];
        if ($deviceRule['operator_id'] == 5 && $reading == $deviceRule['value']) {
          $ruleState = true;
        } else if ($deviceRule['operator_id'] == 1 && $reading < $deviceRule['value']) {
          $ruleState = true;
        } else if ($deviceRule['operator_id'] == 2 && $reading <= $deviceRule['value']) {
          $ruleState = true;
        } else if ($deviceRule['operator_id'] == 3 && $reading > $deviceRule['value']) {
          $ruleState = true;
        } else if ($deviceRule['operator_id'] == 4 && $reading >= $deviceRule['value']) {
          $ruleState = true;
        }
      }

      echo json_encode([
        'deviceState' => $isON,
        'ruleState' => $ruleState,
      ]);
      return;

    } else {
      echo "Failure";
      return;
    }
  } else {
    echo "Failure";
    return;
  }
} else {
  echo "Failure";
  return;
}


?>