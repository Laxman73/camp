<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'includes/common.php';
$pdo=connectToDatabase();

// DFA($_POST);
// exit;
$mode = (isset($_POST['mode'])) ? db_input($_POST['mode']) : 'I';
$rid = (isset($_POST['rid'])) ? db_input($_POST['rid']) : '';
$prid = (isset($_POST['prid'])) ? db_input($_POST['prid']) : '';
$pid = (isset($_POST['pid'])) ? db_input($_POST['pid']) : '';
$USER_ID = (isset($_POST['userid'])) ? db_input($_POST['userid']) : '';

//if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))|| $USER_ID!='2')
if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
    echo 'Invalid Access Detected!!!';
    exit;
}
//getting role and profile of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$user_name = GetXFromYID("select user_name from users where id='" . $USER_ID . "'");

$camp_objective = (isset($_POST['camp_objective'])) ? db_input($_POST['camp_objective']) : '';
$camp_duration = (isset($_POST['camp_duration'])) ? db_input($_POST['camp_duration']) : '';
$type_of_diagnostic = (isset($_POST['type_of_diagnostic'])) ? db_input($_POST['type_of_diagnostic']) : '';
$diagnostic_charges = (isset($_POST['diagnostic_charges'])) ? db_input($_POST['diagnostic_charges']) : '';
$camp_organised = (isset($_POST['camp_organised'])) ? db_input($_POST['camp_organised']) : '';
$camp_received = (isset($_POST['camp_received'])) ? db_input($_POST['camp_received']) : '';
$remarks = (isset($_POST['remarks'])) ? db_input($_POST['remarks']) : '';
$total_no_of_ind = (isset($_POST['total_no_of_ind'])) ? db_input($_POST['total_no_of_ind']) : '';
$registration_number = (isset($_POST['registration_number'])) ? db_input($_POST['registration_number']) : '';

$folderPath = "upload/";
	// Upload Signature 
    $image_parts = explode(";base64,", $_POST['signature1']);        
    $image_type_aux = explode("image/", $image_parts[0]);      
    $image_type = $image_type_aux[1];      
    $image_base64 = base64_decode($image_parts[1]);

	$imageData=$_POST['signature1'];
	$filteredData=substr($imageData, strpos($imageData, ",")+1);
 
	// Need to decode before saving since the data we received is already base64 encoded
	$unencodedData=base64_decode($filteredData);
 
	$file = $folderPath . uniqid() . '.'.$image_type;
 
	$fp = fopen( $file, 'wb' ) or die("File does not exist!");;
	fwrite( $fp, $unencodedData);
	fclose( $fp );

$pdo->prepare("update crm_request_main set e_sign_doctor=?,e_sign_cheque_date=? where id=? ")->execute(array($file,TODAY,$pid));



$ID=NextID('pid','crm_naf_camp_report');
$pdo->prepare("insert crm_naf_camp_report values(?,?,?,?,?,?,?,?,?,?,?)")->execute(array($ID,$pid,$camp_objective,$camp_duration,$type_of_diagnostic,$diagnostic_charges,$total_no_of_ind,$camp_organised,$camp_received,$registration_number,$remarks));

header('location:delivery_service_form_camp.php?rid='.$rid.'&userid='.$USER_ID.'&pid='.$pid);
// echo 'success';
exit;

?>