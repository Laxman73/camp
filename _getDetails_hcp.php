<?php
include 'includes/common.php';
$QUALIFICATION_ARR=GetXArrFromYID("select qualificationid,qualificationname from qualification","3");
$HOSPITAL_ARR=GetXArrFromYID("select contactid,hosname from hospitalcontactdetails","3");
//DFA($HOSPITAL_ARR);
if(isset($_POST['cid'])){
    $cid=db_input($_POST['cid']);
    $rid=db_input($_POST['rid']);
    $_q1="select contactmaster.firstname,contactmaster.lastname,contactmaster.qualification,contactmaster.mobile,contactmaster.otherstate,contactmaster.othercity, hcp_address, hcp_qualification from contactmaster  
	INNER JOIN crm_naf_hcp_details ON contactmaster.id = crm_naf_hcp_details.hcp_id	
	where crm_naf_hcp_details.naf_main_id = '$rid' AND  contactmaster.id='$cid' ";
    $_r1=sql_query($_q1,"");

    list($firstname,$lastname,$qualificationid,$mobile,$otherstate,$othercity,$hcp_address,$hcp_qualification)=sql_fetch_row($_r1);
    $state=GetXFromYID("select statename from state where stateid='$otherstate'");
    $_c_q="select cityname,pincode from city where cityid='$othercity' ";
    $_c_r=sql_query($_c_q,'');
    list($city,$pincode)=sql_fetch_row($_c_r);

    $qualification_name=(isset($QUALIFICATION_ARR[$qualificationid]))?$QUALIFICATION_ARR[$qualificationid]:'';
    $_q2="select hoscontactid from approval_doctor_hospital_association where contactid='$cid' ";
    $_r2=sql_query($_q2);
    list($hostcontactid)=sql_fetch_row($_r2);

    $hospital_name=(isset($HOSPITAL_ARR[$hostcontactid]))?$HOSPITAL_ARR[$hostcontactid]:'';
    echo $firstname.'~~'.$lastname.'~~'.$qualification_name.'~~'.$mobile.'~~'.$hospital_name.'~~'.$state.','.$city.','.''.$pincode.'~~'.$hcp_address.'~~'.$hcp_qualification;
    exit;

}
