<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'includes/common.php';

$pdo = connectToDatabase();

$USER_ID = (isset($_POST['userid'])) ? $_POST['userid'] : '';
$rid = (isset($_POST['rid'])) ? $_POST['rid'] : '';
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division

$DoctorID = (isset($_POST['DoctorID'])) ? $_POST['DoctorID'] : '';
$nature_of_camp = (isset($_POST['nature_of_camp'])) ? $_POST['nature_of_camp'] : '';
$camp_location = (isset($_POST['camp_location'])) ? $_POST['camp_location'] : '';
$camp_duration = (isset($_POST['camp_duration'])) ? $_POST['camp_duration'] : '';
$camp_date = (isset($_POST['camp_date'])) ? $_POST['camp_date'] : '';


$NAF_NO = GetXFromYID("select naf_no from crm_naf_main where id='$rid' ");//NAF NO

$empcode=GetXFromYID("select user_name from users where id='$USER_ID' and deleted=0 ");//getting empcode of user


//Crm_request_main table insertion
$CRM_R_ID = NextID('id', 'crm_request_main');
$RNO = 'MG-' . str_pad($CRM_R_ID, 6, '0', STR_PAD_LEFT);
$stmt = $pdo->prepare("insert into crm_request_main(id,request_no,naf_no,category_id,requestor_id,requestor_empcode,submitted_on,submitted,pendingwithid,authorise,approved_date,level,e_sign_doctor,cheque_path,e_sign_cheque_date) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt->execute(array($CRM_R_ID, $RNO, $NAF_NO, 12, $USER_ID, $empcode, NOW, 1, 24676, 1, NOW, 1, '', '', NOW));


//insert into crm_camp_letter
$CRM_REQ_LETTER_ID=NextID('id','crm_request_camp_letter');
$pdo->prepare("insert into crm_request_camp_letter values(?,?,?,?,?,?,?)")->execute(array($CRM_REQ_LETTER_ID,$CRM_R_ID,$DoctorID,$nature_of_camp,$camp_date,$camp_location,$camp_duration));





echo 'success';
?>