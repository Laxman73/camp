<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'includes/common.php';
$pdo = connectToDatabase();
$rid = (isset($_POST['rid'])) ? $_POST['rid'] : '';
$userid = (isset($_POST['userid'])) ? $_POST['userid'] : '';


$USER_ID = (isset($_POST['userid'])) ? $_POST['userid'] : '';
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division
// DFA($_POST);
// exit;

$Current_level = GetXFromYID("select level from crm_naf_main where id=$rid "); //current level from crm_naf_main table
$New_level = $Current_level + 1;// increment by 1

$PENDING_WITH_ID = $STATUS = '';

$crm_workflow = crm_workflow($USER_ID, 2, $New_level, 0); //Getting the details from crm_workflow 
if (!empty($crm_workflow)) {
    $PENDING_WITH_ID = $crm_workflow['pending_with_id'];
    $STATUS = $crm_workflow['status'];
}

$comment=(isset($_POST['comment']))?db_input2($_POST['comment']):'';

$pdo->prepare("update crm_naf_main set post_comment=?,level=?,authorise=?,pendingwithid=? where id=? ")->execute(array($comment,$New_level,$STATUS,$PENDING_WITH_ID,$rid));
header('location: index_PM.php?userid='.$USER_ID);
exit;

?>