<?php
include 'includes/common.php';
$pdo = connectToDatabase();
$rid = (isset($_POST['rid'])) ? $_POST['rid'] : '';
$prid = (isset($_POST['prid'])) ? $_POST['prid'] : '';
$userid = (isset($_POST['userid'])) ? $_POST['userid'] : '';


$Current_level=GetXFromYID("select level from crm_naf_main where id=$rid ");//current level from crm_naf_main table
$New_level=$Current_level+1;// increment by 1



$USER_ID = (isset($_POST['userid'])) ? $_POST['userid'] : '';
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division



$empcode=GetXFromYID("select user_name from users where id='$USER_ID' and deleted=0 ");

$PENDING_WITH_ID = $STATUS = '';

$crm_workflow = crm_workflow($USER_ID, 5, $New_level, $User_division);//Getting the details from crm_workflow 
if (!empty($crm_workflow)) {
    $PENDING_WITH_ID = $crm_workflow['pending_with_id'];
    $STATUS = $crm_workflow['status'];
}


// [userid] => 19560
// [rid] => 96
// [activity_name] => camp activity
// [VO] => 2
// [details_of_activity] => camp details
// [Nature_of_activity] => 4
// [vendor_service] => 2

$vo = db_input2($_POST['VO']);
$details_of_activity = db_input2($_POST['details_of_activity']);
$activity_name=db_input($_POST['activity_name']);
$nature_of_activity = db_input2($_POST['Nature_of_activity']);
$vendor_service = db_input2($_POST['vendor_service']);
$v_payee_name = db_input2($_POST['v_payee_name']);
$descr = db_input2($_POST['descr']);

$nature_of_actual_cost = db_input2($_POST['nature_of_actual_cost']);
$travel_flights = db_input2($_POST['travel_flights']);
$insurance = db_input2($_POST['insurance']);
$travel_cab = db_input2($_POST['travel_cab']);
$visa = db_input2($_POST['visa']);
$stay_hotel = db_input2($_POST['stay_hotel']);
$audio_v = db_input2($_POST['audio_v']);
$meal = db_input2($_POST['meal']);
$banners = db_input2($_POST['banners']);
$other = db_input2($_POST['other']);


$stmt = $pdo->prepare("insert into crm_naf_delivery_form (id,crm_naf_main_id,employee_id,name_of_activity,details_of_activity,type_of_activity,mode,vendor_services,vendor_name,vendor_description) values(?,?,?,?,?,?,?,?,?,?) ");
$id = NextID('id', 'crm_naf_delivery_form');
$stmt->execute(array($id, $rid, $empcode,$activity_name,$details_of_activity, $nature_of_activity, $vo, $vendor_service, $v_payee_name, $descr));

$stmt = $pdo->prepare("insert into crm_naf_delivery_form_cost_details (pid,naf_delivery_form_id,actual_vendor_cost,naf_travel_flight_cost,naf_insurance_cost,naf_flight_cost,naf_travel_cab_cost,naf_visa_cost,naf_stay_hotel_cost,naf_audio_visual_cost,naf_meal_snack_cost,naf_banners_pamphlets_cost,naf_other_additonal_cost) values(?,?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt->execute(array(NULL, $id, $nature_of_actual_cost, $travel_flights, $insurance, $travel_flights, $travel_cab, $visa, $stay_hotel, $audio_v, $meal, $banners, $other));

//update crm_naf_main table
$pdo->prepare("update crm_naf_main set level=?,authorise=?,remarks=?,pendingwithid=? where id=? ")->execute(array($New_level,$STATUS,'pending',$PENDING_WITH_ID,$rid));


header('location:mt_delivery_service_form_camp.php?rid=' . $rid . '&userid=' . $userid);
?>
