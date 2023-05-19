<?php
include 'includes/common.php';
$pdo = connectToDatabase();

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$userid = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$mode = (isset($_GET['mode'])) ? $_GET['mode'] : '';

$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division

$Current_level=GetXFromYID("select level from crm_naf_main where id=$rid ");//current level from crm_naf_main table
$New_level=$Current_level+1;// increment by 1

//$PENDING_WITH_ID = $STATUS = '';
// $crm_workflow = crm_workflow($userid, 4, 2, 9);
// if (!empty($crm_workflow)) {
//     $PENDING_WITH_ID = $crm_workflow['pending_with_id'];
//     $STATUS = $crm_workflow['status'];
// }

$PENDING_WITH_ID = $STATUS = '';
$crm_workflow = crm_workflow($USER_ID, 2, $New_level, 0);//Getting the details from crm_workflow
if (!empty($crm_workflow)) {
    $PENDING_WITH_ID = $crm_workflow['pending_with_id'];
    $STATUS = $crm_workflow['status'];
}


if ($mode == 'A') {
    //crm_naf_main update status
    $stmt = $pdo->prepare("update crm_naf_main set level=?,authorise=?,remarks=?,pendingwithid=? where id=? ");
    $stmt->execute(array($New_level, $STATUS, 'approve', $PENDING_WITH_ID, $rid));
     //upadate log of crm_naf_approvereject
     $stmt=$pdo->prepare("insert into crm_naf_approvereject (id,pma_request_id,userid,pendingwithid,authorise,level,remark,submitted_on,deleted) values(?,?,?,?,?,?,?,?,?) ");
     $ID=NextID('id','crm_naf_approvereject');
     $stmt->execute(array($ID,$rid,$USER_ID,$PENDING_WITH_ID,$STATUS,$New_level,'approve',NOW,0));

} elseif ($mode == 'R') {
    //crm_naf_main update status
    $stmt = $pdo->prepare("update crm_naf_main set level=?,authorise=?,remarks=?,pendingwithid=? where id=? ");
    $stmt->execute(array($New_level, $STATUS, 'approve', $PENDING_WITH_ID, $rid));
    //upadate log of crm_naf_approvereject
   $stmt=$pdo->prepare("insert into crm_naf_approvereject (id,pma_request_id,userid,pendingwithid,authorise,level,remark,submitted_on,deleted) values(?,?,?,?,?,?,?,?,?) ");
   $ID=NextID('id','crm_naf_approvereject');
   $stmt->execute(array($ID,$rid,$USER_ID,0,4,$New_level,'reject',NOW,0));  
}



header('location:index_PM.php?userid=' . $userid);
exit;
?>