<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'includes/common.php';
//DFA($_POST);
$pdo = connectToDatabase();

$USER_ID = (isset($_POST['userid'])) ? $_POST['userid'] : '';
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division

$DIVISION_ARR = GetXArrFromYID("select divisionid,name from division ", '3');
$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0', '3');
$empcode=GetXFromYID("select user_name from users where id='$USER_ID' and deleted=0 ");
$initiater=GetXFromYID("SELECT CONCAT(first_name, ' ', last_name) AS result from users where id='$USER_ID' and deleted=0 ");


$PENDING_WITH_ID = $STATUS = '';
$crm_workflow = crm_workflow($USER_ID, 2, 1, 0);

if (!empty($crm_workflow)) {
    $PENDING_WITH_ID = $crm_workflow['pending_with_id'];
    $STATUS = $crm_workflow['status'];
}

$reqDate = db_input($_POST['reqDate']);
$Quarter = db_input($_POST['Quarter']);
$activty_name = db_input($_POST['activty_name']);
$proposed_count = db_input($_POST['proposed_count']);
$number_of_HCP = db_input($_POST['number_of_HCP']);
$Nature_of_activity = db_input($_POST['Nature_of_activity']);
$number_of_participants = db_input($_POST['number_of_participants']);
$budget_amt = db_input($_POST['budget_amt']);
$proposed_obj = db_input($_POST['proposed_obj']);
$rationale = db_input($_POST['rationale']);
$activity_benefit = db_input($_POST['activity_benefit']);
$event_lead = db_input($_POST['event_lead']);
$medical_equipment_needed = db_input($_POST['medical_equipment_needed']);
$deviation_amount = db_input($_POST['deviation_amount']);
$targetedSpeciality_Arr = $_POST['targetedSpeciality'];


$CrmID = NextID('id', 'crm_naf_main');
$EVENT_ID = 'Q' . 'CAM' . substr($DIVISION_ARR[$User_division], 0, 3) . str_pad($CrmID, 4, '0', STR_PAD_LEFT);//Generating event ID 
$Name = GetXFromYID("select first_name from users where id='$USER_ID' ");
$pdo->prepare("insert into crm_naf_main (id,initiator,userid,emp_code,date,post_comment,category_id,submitted_on,submitted,pendingwithid,authorise,approved_date,deleted,deleted_on,eventdate,level,naf_no,naf_activity_name,naf_city,naf_proposed_venue,naf_estimate_no_participents,naf_start_date,naf_end_date,naf_objective_rational,remarks,quarter,mode,proposed_activity_count,proposed_hcp_no,proposed_activity,proposed_objective,rationale_remark,lead_event,medical_equipments,deviation_amount,budget_amount) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")->execute(array($CrmID,$initiater,$USER_ID,$empcode,NOW,'',2,NOW,1,$PENDING_WITH_ID,$STATUS,NOW,0,NULL,$reqDate,1,$EVENT_ID,$activty_name,0,'',$number_of_participants,NOW,NOW,$rationale,'',$Quarter,0,$proposed_count,$number_of_HCP,$Nature_of_activity,$proposed_obj,'',$event_lead,$medical_equipment_needed,$deviation_amount,$budget_amt));

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


echo 'success';
?>