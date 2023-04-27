<?php
ini_set('display_errors',1);
include 'includes/common.php';
$pdo = connectToDatabase();
													 
$USER_ID = (isset($_POST['userid'])) ? $_POST['userid'] : '';
$category = (isset($_POST['category'])) ? $_POST['category'] : '';
$selected_hcp = (isset($_POST['selected_hcp'])) ? $_POST['selected_hcp'] : '';

$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division

$PENDING_WITH_ID = $STATUS = '';
$crm_workflow = crm_workflow($USER_ID, 6, 1, $User_division);

if (!empty($crm_workflow)) {
    $PENDING_WITH_ID = $crm_workflow['pending_with_id'];
    $STATUS = $crm_workflow['status'];
}

$_q = "select field_id,field_name from crm_naf_fields_master where deleted=0 ";
$_r = sql_query($_q, "");
$DIVISION_ARR = GetXArrFromYID("select divisionid,name from division ", '3');
$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0', '3');
$activty_type = 'PMA';
$_user_q = "SELECT division FROM users WHERE id = $USER_ID";
$_user_r = sql_query($_user_q, '');
list($division) = sql_fetch_row($_user_r);
$_crm_fields_q = "select field_id,field_name from crm_naf_fields_master where deleted=0 ";
$_crm_fields_r = sql_query($_crm_fields_q, "");


/* Array
(
    [userid] => 16920
    [category] => 6
    [selected_hcp] => [{"doctorid":"9","doctorid_value":"20829","hcp_name":"  AG Bharathi -","address":"KARNATAKA,RAICHUR,","qualification":"Diplomate N.B. (Paed.)","associated_hospital":"PON Hospital","pan":"ABCDE1234A","hcp_mobile":"498797878","govt":"Yes","year_of_experience":"1"},{"doctorid":"1","doctorid_value":"30405","hcp_name":"  Alka Rangari","address":"MAHARASHTRA,Nallasopara,0","qualification":"MBBS","associated_hospital":"PON Hospital","pan":"ABCDE1234A","hcp_mobile":"9999999999","govt":"Yes","year_of_experience":"1"}]
    [txtSdate] => 2023-04-18
    [txtTdate] => 2023-04-22
    [productID] => 0
    [others] => test
    [activity_name] => Activity Testing1
    [Nature_of_activity] => 1
    [virtual_physical] => 1
    [date_of_activity] => 2023-04-28
    [city] => 15
    [propose_venue] => AIT
    [Num_of_Participants] => 1
    [targetedSpeciality] => Array
        (
            [0] => 7829
        )

    [rationals] => test
    [crm_14] => 1000
    [crm_15] => 1
    [crm_16] => 1
    [crm_17] => 1
    [crm_18] => 1
    [crm_19] => 1
    [crm_20] => 1
    [crm_21] => 11
    [crm_22] => 1
    [crm_29] => 1
    [crm_23] => 1
    [crm_24] => 1
    [crm_25] => 1
    [total] => 1022
    [rationals_selection] => tests
    [role_of_Advisor] => tetsrs
) */



$productID = db_input($_POST['productID']);
$txtSdate = db_input($_POST['txtSdate']);
$txtTdate = db_input($_POST['txtTdate']);
$activity_name = db_input(($_POST['activity_name']));
$naf_product_therapy_others = db_input(($_POST['others']));
$nature_of_activity = db_input(($_POST['Nature_of_activity']));
$date_of_activity = db_input($_POST['date_of_activity']);
$city = db_input($_POST['city']);
$propose_venue = db_input($_POST['propose_venue']);
$Num_of_Participants = db_input($_POST['Num_of_Participants']);
$targetedSpeciality_Arr = $_POST['targetedSpeciality'];
$rationals = $_POST['rationals'];
$virtual_physical = $_POST['virtual_physical'];
$rationals_selection = $_POST['rationals_selection'];
$role_of_Advisor = $_POST['role_of_Advisor'];

$pendingStateHeadID = GetXFromYID("select statehead_id from crm_statehead_details"); //Getting pending stateheadID

$Name = GetXFromYID("select first_name from users where id='$USER_ID' ");

												


// prepare and bind
$CrmID = NextID('id', 'crm_naf_main');
$EVENT_ID = 'A' . $activty_type . substr($DIVISION_ARR[$division], 0, 3) . str_pad($CrmID, 4, '0', STR_PAD_LEFT);

$stmt = $pdo->prepare("INSERT INTO crm_naf_main (id, initiator,userid,emp_code,date,category_id,submitted_on,submitted,pendingwithid,authorise,approved_date,deleted,deleted_on,eventdate,level,naf_no,naf_activity_name,naf_city,naf_proposed_venue,naf_estimate_no_participents,naf_start_date,naf_end_date,naf_objective_rational,mode,rationale_remark,role_of_advisory) VALUES (?,?,?,?,?,?,?,?,?,?,?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
//  $arf_no='11';
//  $initiator='Darshan';
//  $userid=2;
$emp_code = 3;
//  $date=TODAY;
//  $submitted_on=TODAY;
$submitted = '1';
//  $pendingwithid='Medical Head';
//  $authorise=1;
//  $approved_date='2023-02-28';
$deleted = '0';
//  $deleted_on='2023-02-28';
$eventdate = TODAY;
//  $level=5;
//  $naf_proposed_venue='ss';
//$naf_estimate_no_participents=$Num_of_Participants;
$stmt->execute(array($CrmID, $Name, $USER_ID, $emp_code, $date_of_activity, $category, TODAY, $submitted, $PENDING_WITH_ID,$STATUS, TODAY, $deleted, TODAY, $eventdate, 1, $EVENT_ID,$activity_name ,$city, $propose_venue, $Num_of_Participants, $txtSdate, $txtTdate, $rationals,$virtual_physical,$rationals_selection,$role_of_Advisor));

$stmt = $pdo->prepare("insert into crm_naf_activity_details (pid,naf_request_id,activity_id,naf_nature_of_activity_others,deleted) values (?,?,?,?,?) ");
$pid = NextID('pid', 'crm_naf_activity_details');
$naf_request_id = $CrmID;
$activityID = $nature_of_activity;
$naf_nature_of_activity_others = '';
$deleted = 0;
$stmt->execute(array($pid, $naf_request_id, $activityID, $naf_nature_of_activity_others, $deleted));



$stmt = $pdo->prepare("insert into crm_naf_product_details (pid,naf_request_id,product_id,naf_product_therapy_others,deleted) values (?,?,?,?,?)");
$pid = NextID('pid', 'crm_naf_product_details');
$naf_request_id = $CrmID;
$product_id = $productID;
$naf_product_therapy_others = $naf_product_therapy_others;
$deleted = 0;
$p = 0;
if($naf_product_therapy_others==''){
	$stmt->execute(array($pid, $naf_request_id, $product_id, $naf_product_therapy_others, $deleted));
}else{
	$stmt->execute(array($pid, $naf_request_id, $p, $naf_product_therapy_others, $deleted));
}
$stmt = $pdo->prepare("insert into crm_naf_speciality_details(pid,naf_request_id,speciality_id,deleted) values(?,?,?,?) ");
//$stmt->bind_param("iiii",$pid,$naf_request_id,$speciality_id,$deleted);
foreach ($targetedSpeciality_Arr as $key => $value) {
  $pid = NextID('pid', 'crm_naf_speciality_details');
  $naf_request_id = $CrmID;
  $speciality_id = $value;
  $deleted = 0;
  //$stmt->execute();
  $stmt->execute(array($pid, $naf_request_id, $speciality_id, $deleted));
}



$stmt = $pdo->prepare("insert into crm_naf_cost_details(pid,naf_request_id,naf_field_id,naf_expense) values (?,?,?,?) ");

$naf_request_id = $CrmID;
$naf_hcp_honorarium_cost = db_input($_POST['crm_1']);
$naf_local_travel_cost = db_input($_POST['crm_2']);
while ($row = sql_fetch_assoc($_crm_fields_r)) {
  if (isset($_POST['crm_' . $row['field_id']])) {
    $pid = NextID('pid', 'crm_naf_cost_details');
    $field_value = db_input($_POST['crm_' . $row['field_id']]);
    $stmt->execute(array($pid, $naf_request_id, $row['field_id'], $field_value));
  }
}

/* if($category==6){
	$stmt = $pdo->prepare("insert into crm_naf_advisory_details (pid,naf_request_id,virtual_physical,rationals_selection,role_of_Advisor,deleted) values (?,?,?,?,?,?) ");
	$pid = NextID('pid', 'crm_naf_advisory_details');
	$naf_request_id = $CrmID;
	$activityID = $nature_of_activity;
	$naf_nature_of_activity_others = '';
	$deleted = 0;
	$stmt->execute(array($pid, $naf_request_id, $virtual_physical, $rationals_selection, $role_of_Advisor, $deleted));
} */



$stmt = $pdo->prepare("insert into crm_naf_approvereject (id,pma_request_id,userid,pendingwithid,authorise,level,remark,submitted_on,deleted) values(?,?,?,?,?,?,?,?,?) ");
$ID = NextID('id', 'crm_naf_approvereject');
$stmt->execute(array($ID, $CrmID, $USER_ID, $PENDING_WITH_ID, $STATUS, 1, 'pending', NOW, 0));

$json_hcp = json_decode($selected_hcp, true);

foreach($json_hcp as $key => $value)
{
	$DoctorID = db_input($json_hcp[$key]['doctorid']); //
	$hcp_name = db_input($json_hcp[$key]['hcp_name']);
	$address = db_input($json_hcp[$key]['address']);
	$pan = db_input($json_hcp[$key]['pan']);
	$associated_hospital = db_input($json_hcp[$key]['associated_hospital']);
	$govt = db_input($json_hcp[$key]['govt']);
  $hcp_honorium = db_input($json_hcp[$key]['honorarium_amount']);
  $hcp_role = db_input($json_hcp[$key]['role_of_hcp']);
	$year_of_experience = db_input($json_hcp[$key]['year_of_experience']);
	$hcp_mobile = db_input($json_hcp[$key]['hcp_mobile']);
	$qualification = db_input($json_hcp[$key]['qualification']);
	$rid = $naf_request_id; //id from crm_naf_main table
	$userid = db_input($_POST['userid']);
	// $hcp_role = 0;
	// $hcp_honorium = 0;	
	
	$pendingStateHeadID = GetXFromYID("select statehead_id from crm_statehead_details"); //Getting pending stateheadID


	$USER_ID = (isset($_POST['userid'])) ? $_POST['userid'] : '';
	$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
	$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
	$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division

	$PENDING_WITH_ID = $STATUS = '';
	$crm_workflow = crm_workflow($USER_ID, 3, 1, $User_division);//Getting the details from crm_workflow 
	if (!empty($crm_workflow)) {
		$PENDING_WITH_ID = $crm_workflow['pending_with_id'];
		$STATUS = $crm_workflow['status'];
	}

	$NAF_NO = GetXFromYID("select naf_no from crm_naf_main where id='$rid' ");
	$category_id = GetXFromYID("SELECT c1.pid AS catergorid FROM crm_naf_type_master 
	INNER JOIN crm_naf_type_master c1 ON crm_naf_type_master.type_name = c1.type_name 
	WHERE crm_naf_type_master.request_type = 'naf_type' AND crm_naf_type_master.deleted = 0 AND crm_naf_type_master.pid='$category' AND c1.request_type = 'request_type' AND c1.deleted = 0");

	//crm_naf_hcp_details
	$Rid = NextID('id', 'crm_naf_hcp_details');
	$submitted_on = NOW;
	$deleted = 0;
	$deleted_on = NOW;

	$stmt = $pdo->prepare("insert into crm_naf_hcp_details (id,naf_main_id,hcp_id,hcp_address,hcp_pan,hcp_qualification,hcp_associated_hospital_id,govt_type,yr_of_experience,role_of_hcp,honorarium_amount,mobile,submitted_on,deleted,deleted_on)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	$stmt->execute(array($Rid, $naf_request_id, $DoctorID, $address, $pan, $qualification, $associated_hospital, $govt, $year_of_experience, $hcp_role, $hcp_honorium, $hcp_mobile, $submitted_on, $deleted, $deleted_on));


}


header('location:index_PM.php?userid=' . $USER_ID);
exit;

?>
