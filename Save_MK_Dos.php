<?php
include 'includes/common.php';
$pdo = connectToDatabase();
$rid = (isset($_POST['rid'])) ? $_POST['rid'] : '';
$userid = (isset($_POST['userid'])) ? $_POST['userid'] : '';


$USER_ID = (isset($_POST['userid'])) ? $_POST['userid'] : '';
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division



?>