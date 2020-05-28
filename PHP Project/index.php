<?php
include_once("helpers/conn.php");

$sql = "SELECT info_value FROM website_information WHERE info_key='name';";
$stmt = $pdo->query($sql);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$websiteName = $record['info_value'];

$sql = "SELECT info_value FROM website_information WHERE info_key='logo';";
$stmt = $pdo->query($sql);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$websiteLogo = $record['info_value'];

$sql = "SELECT info_value FROM website_information WHERE info_key='description';";
$stmt = $pdo->query($sql);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$websiteDesc = $record['info_value'];

include_once ("views/index.view.php");
?>