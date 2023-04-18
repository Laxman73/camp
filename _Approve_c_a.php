<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'includes/common.php';


$pdo = connectToDatabase();

$USER_ID = (isset($_POST['userid'])) ? $_POST['userid'] : '';
$rid = (isset($_POST['rid'])) ? $_POST['rid'] : '';
$mode = (isset($_POST['choice'])) ? $_POST['choice'] : '';
$remark=(isset($_POST['remark']))?$_POST['remark']:'';

$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division

$DIVISION_ARR = GetXArrFromYID("select divisionid,name from division ", '3');
$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0', '3');
$empcode=GetXFromYID("select user_name from users where id='$USER_ID' and deleted=0 ");
$initiater=GetXFromYID("SELECT CONCAT(first_name, ' ', last_name) AS result from users where id='$USER_ID' and deleted=0 ");

$Current_level=GetXFromYID("select level from crm_naf_main where id=$rid ");//current level from crm_naf_main table
$New_level=$Current_level+1;// increment by 1


$PENDING_WITH_ID = $STATUS = '';
$crm_workflow = crm_workflow($USER_ID, 2, $New_level, 0);//Getting the details from crm_workflow 

// DFA($crm_workflow);
// exit;
if (!empty($crm_workflow)) {
    $PENDING_WITH_ID = $crm_workflow['pending_with_id'];
    $STATUS = $crm_workflow['status'];
}else {
    $PENDING_WITH_ID = 0;
    $STATUS = 3;
    $New_level=$Current_level;
}


if ($mode=='A') {
   
    //crm_naf_main update status
    $stmt=$pdo->prepare("update crm_naf_main set level=?,authorise=?,remarks=?,pendingwithid=? where id=? ");
    $stmt->execute(array($New_level,$STATUS,$remark,$PENDING_WITH_ID,$rid));
    //upadate log of crm_naf_approvereject
    $stmt=$pdo->prepare("insert into crm_naf_approvereject (id,pma_request_id,userid,pendingwithid,authorise,level,remark,submitted_on,deleted) values(?,?,?,?,?,?,?,?,?) ");
    $ID=NextID('id','crm_naf_approvereject');
    $stmt->execute(array($ID,$rid,$USER_ID,$PENDING_WITH_ID,$STATUS,$New_level,'approve',NOW,0));

}elseif ($mode=='R') {
     //crm_naf_main update status
     $stmt=$pdo->prepare("update crm_naf_main set level=?,authorise=?,remarks=?,pendingwithid=? where id=? ");
     $stmt->execute(array($New_level,4,$remark,0,$rid));
   //upadate log of crm_naf_approvereject
   $stmt=$pdo->prepare("insert into crm_naf_approvereject (id,pma_request_id,userid,pendingwithid,authorise,level,remark,submitted_on,deleted) values(?,?,?,?,?,?,?,?,?) ");
   $ID=NextID('id','crm_naf_approvereject');
   $stmt->execute(array($ID,$rid,$USER_ID,0,4,$New_level,$remark,NOW,0));  
}


echo 'success';
?>