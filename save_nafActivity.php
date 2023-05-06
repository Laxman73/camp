<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'includes/common.php';
// DFA($_POST);
// DFA($_FILES);
// exit;
$pdo = connectToDatabase();

$USER_ID = (isset($_POST['userid'])) ? $_POST['userid'] : '';
$category= (isset($_POST['category'])) ? $_POST['category'] : '';
$PR_ID = (isset($_POST['prid'])) ? $_POST['prid'] : '';
$R_ID = (isset($_POST['rid'])) ? $_POST['rid'] : '';
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division

$DIVISION_ARR = GetXArrFromYID("select divisionid,name from division ", '3');
$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0', '3');
$empcode=GetXFromYID("select user_name from users where id='$USER_ID' and deleted=0 ");
$initiater=GetXFromYID("SELECT CONCAT(first_name, ' ', last_name) AS result from users where id='$USER_ID' and deleted=0 ");


$PENDING_WITH_ID = $STATUS = '';
$crm_workflow = crm_workflow($USER_ID, $category, 1, 0);

if (!empty($crm_workflow)) {
    $PENDING_WITH_ID = $crm_workflow['pending_with_id'];
    $STATUS = $crm_workflow['status'];
}

$reqDate = (isset($_POST['reqDate']))?db_input($_POST['reqDate']):'';
$activity_Date = (isset($_POST['activity_Date'])) ? db_input($_POST['activity_Date']):'';
$product_id = (isset($_POST['productID']))?db_input($_POST['productID']):'';
$Quarter = (isset($_POST['Quarter'])) ? db_input($_POST['Quarter']):'';
$activty_name = (isset($_POST['activty_name']))?db_input($_POST['activty_name']):'';
$proposed_count = (isset($_POST['proposed_count'])) ? db_input($_POST['proposed_count']):'';
$number_of_HCP = (isset($_POST['number_of_HCP']))? db_input($_POST['number_of_HCP']):'';
$Nature_of_activity = (isset($_POST['Nature_of_activity'])) ? db_input($_POST['Nature_of_activity']):'';
$number_of_participants = (isset($_POST['no_of_p'])) ? db_input($_POST['no_of_p']):'';
$budget_amt = (isset($_POST['budget_amt']))? db_input($_POST['budget_amt']):'';
$proposed_obj = (isset($_POST['objective'])) ? db_input($_POST['objective']):'';
$rationale = (isset($_POST['rationale']))? db_input($_POST['rationale']):'';
$activity_benefit = (isset($_POST['activity_benefit'])) ? db_input($_POST['activity_benefit']):'';
$event_lead = (isset($_POST['event_lead'])) ? db_input($_POST['event_lead']):'';
$city = (isset($_POST['city'])) ? db_input($_POST['city']):'';
$venue = (isset($_POST['venue'])) ? db_input($_POST['venue']):'';
$medical_equipment_needed = (isset($_POST['medical_equipment_needed'])) ? db_input($_POST['medical_equipment_needed']):'';
$deviation_amount = (isset($_POST['deviation_amount'])) ? db_input($_POST['deviation_amount']):'';
$targetedSpeciality_Arr = (isset($_POST['targetedSpeciality']))? $_POST['targetedSpeciality']:array();
$selected_hcp = (isset($_POST['selected_hcp']))? $_POST['selected_hcp']:'';
$selected_request_letter = (isset($_POST['selected_request_letter']))? $_POST['selected_request_letter']:'';
$advance_payment = (isset($_POST['advance_payment']))? $_POST['advance_payment']:'';
$advance_payment_type = (isset($_POST['advance_payment_type']))? $_POST['advance_payment_type']:'';

$naf_product_therapy_others = (isset($_POST['others']))? db_input($_POST['others']):'';
$upload_path='';

if (is_uploaded_file($_FILES['advance_file']["tmp_name"])) {
    
    $uploaded_pic = $_FILES['advance_file']["name"];
    $name = basename($_FILES['advance_file']['name']);
    $file_type = $_FILES['advance_file']['type'];
    $size = $_FILES['advance_file']['size'];
    $extension = substr($name, strrpos($name, '.') + 1);

    if (IsValidFile($file_type, $extension, 'P') && $size <= 3000000) {
        $newname = NormalizeFilename($uploaded_pic); // normalize the file name
        $pic_name =  $rid . "_CRM_Attahment_advance_file" . NOW3 . '.' . $newname;
        $fileName =  $rid . '_CRM_Attahment_advance_file' . NOW3 . '.' . $extension;

        $dir = opendir(CRM_ADVANCE_ATTACH_UPLOAD);
        move_uploaded_file($_FILES['advance_file']["tmp_name"],  CRM_ADVANCE_ATTACH_UPLOAD . $fileName);
        //copy($_FILES['file_attach_'.$j]["tmp_name"], CRM_ATTACHMENT_UPLOAD.$fileName);
        closedir($dir);   // close the directory

        $upload_path = CRM_ADVANCE_ATTACH_PATH . $fileName;
    } else {
        $error['advance_file'] = 'Cancel check file should be less than  2 MB';
    }
}

$PARENT_ID=GetXFromYID("select naf_no from crm_naf_main where id='$PR_ID' and deleted=0 ");

$CrmID = NextID('id', 'crm_naf_main');//Main ID from the crm_naf_main table
$EVENT_ID = 'Q' . 'CAM' . substr($DIVISION_ARR[$User_division], 0, 3) . str_pad($PR_ID, 4, '0', STR_PAD_LEFT). str_pad($CrmID, 4, '0', STR_PAD_LEFT);//Generating event ID 

$Name = GetXFromYID("select first_name from users where id='$USER_ID' ");
$pdo->prepare("insert into crm_naf_main (id,initiator,userid,emp_code,date,post_comment,category_id,submitted_on,submitted,pendingwithid,authorise,approved_date,deleted,deleted_on,eventdate,level,naf_no,naf_activity_name,naf_city,naf_proposed_venue,naf_estimate_no_participents,naf_start_date,naf_end_date,naf_objective_rational,remarks,quarter,mode,proposed_activity_count,proposed_hcp_no,proposed_activity,proposed_objective,rationale_remark,lead_event,medical_equipments,deviation_amount,parent_id,advance_payment,advance_payment_type,doc_upload_path) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")->execute(array($CrmID,$initiater,$USER_ID,$empcode,$reqDate,'',5,NOW,1,$PENDING_WITH_ID,$STATUS,NOW,0,NULL,$activity_Date,1,$EVENT_ID,$activty_name,$city,$venue,$number_of_participants,NOW,NOW,$rationale,'',$Quarter,0,$proposed_count,$number_of_HCP,$Nature_of_activity,$proposed_obj,'',$event_lead,$medical_equipment_needed,$deviation_amount,$PARENT_ID,$advance_payment,$advance_payment_type,$upload_path));

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

$_crm_fields_q = "select field_id,field_name from crm_naf_fields_master where deleted=0 and typeid=6 ";
$_crm_fields_r = sql_query($_crm_fields_q, "");
$stmt = $pdo->prepare("insert into crm_naf_cost_details(pid,naf_request_id,naf_field_id,naf_expense) values (?,?,?,?) ");
while ($row = sql_fetch_assoc($_crm_fields_r)) {
    if (isset($_POST['crm_' . $row['field_id']])) {
        $pid = NextID('pid', 'crm_naf_cost_details');
        $field_value = db_input($_POST['crm_' . $row['field_id']]);
        $stmt->execute(array($pid, $naf_request_id, $row['field_id'], $field_value));
    }
}

//Product Details
$crm_naf_product_detailsID = NextID('pid', 'crm_naf_product_details');
$pdo->prepare("insert into crm_naf_product_details (pid,naf_request_id,product_id,naf_product_therapy_others,deleted) values (?,?,?,?,?)")->execute(array($crm_naf_product_detailsID, $CrmID, $product_id, $naf_product_therapy_others, 0));

// Array
// (
//     [userid] => 19804
//     [prid] => 106
//     [rid] => 
//     [selected_hcp] => [{"doctorid":"4","doctorid_value":"39399","hcp_name":"  A Aishwarya -","address":"TAMIL NADU,CHENNAI,600001","qualification":"Diplomate N.B. (Paed.)","associated_hospital":"PON Hospital","pan":"ABCTY1234D","hcp_mobile":"7350807077","govt":"No","honorarium_amount":"4000","role_of_hcp":"role","year_of_experience":"12"}]
//     [selected_request_letter] => [{"nature_of_camp":"camp activity","proposed_camp_date":"2023-04-29","proposed_camp_location":"goa","estimated_cost":"5000","diagnostic_lab":"400"}]
//     [reqDate] => 2023-04-28
//     [productID] => 2818
//     [eventID] => 
//     [activty_name] => camp activity
//     [Nature_of_activity] => 1
//     [activity_Date] => 2023-04-28
//     [city] => 4
//     [venue] => GOA
//     [no_of_p] => 40
//     [targetedSpeciality] => Array
//         (
//             [0] => 7823
//             [1] => 7824
//             [2] => 7825
//         )

//     [objective] => objective
//     [crm_14] => 500
//     [crm_15] => 400
//     [crm_16] => 
//     [crm_17] => 
//     [crm_18] => 
//     [crm_19] => 
//     [crm_20] => 
//     [crm_21] => 
//     [crm_22] => 
//     [crm_23] => 
//     [crm_24] => 
//     [crm_25] => 
//     [crm_29] => 
//     [total] => 900
//     [advance_payment] => No
//     [example_length] => 10
//     [example1_length] => 10
//     [rationale] => rational for selection
// )

$json_hcp = json_decode($selected_hcp, true);

$crm_naf_hcp_details_ID=NextID('id','crm_naf_hcp_details');//HCP DETAILS ID

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
    $pdo->prepare("insert into crm_naf_hcp_details values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")->execute(array($crm_naf_hcp_details_ID,$CrmID,$DoctorID,$address,$pan,$qualification,$associated_hospital,$govt,$year_of_experience,$hcp_role,$hcp_honorium,$hcp_mobile,TODAY,0,TODAY));
}

$json_selected_request_letter = json_decode($selected_request_letter, true);

foreach($json_selected_request_letter as $key => $value)
{
    $nature_of_camp = db_input($json_selected_request_letter[$key]['nature_of_camp']); //
    $proposed_camp_date = db_input($json_selected_request_letter[$key]['proposed_camp_date']);
    $proposed_camp_location = db_input($json_selected_request_letter[$key]['proposed_camp_location']);
    $estimated_cost = db_input($json_selected_request_letter[$key]['estimated_cost']);
    $diagnostic_lab = db_input($json_selected_request_letter[$key]['diagnostic_lab']);

  
    $crm_naf_camp_letters_ID=NextID('id','crm_naf_camp_letter');
    $pdo->prepare("insert into crm_naf_camp_letter values(?,?,?,?,?,?,?)")->execute(array($crm_naf_camp_letters_ID,$crm_naf_hcp_details_ID,$nature_of_camp,$proposed_camp_date,$proposed_camp_location,$estimated_cost,$diagnostic_lab));
}



//index_PM.php?userid=19804

header('location: index_PM.php?userid='.$USER_ID);
exit;
?>
