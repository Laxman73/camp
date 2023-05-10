<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'includes/common.php';
// 
$pdo = connectToDatabase();

$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';

$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
echo 'profileID=>'.$PROFILE_ID;
?>