<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'includes/common.php';
$pdo=connectToDatabase();

$mode = (isset($_POST['mode'])) ? db_input($_POST['mode']) : 'I';
$rid = (isset($_POST['rid'])) ? db_input($_POST['rid']) : '';
$pid = (isset($_POST['pid'])) ? db_input($_POST['pid']) : '';
$USER_ID = (isset($_POST['userid'])) ? db_input($_POST['userid']) : '';

$hcp_name=(isset($_POST['hcp_name'])) ? db_input($_POST['hcp_name']) : '';
$hcp_address=(isset($_POST['hcp_address'])) ? db_input($_POST['hcp_address']) : '';
$hcp_qual=(isset($_POST['hcp_qual'])) ? db_input($_POST['hcp_qual']) : '';
$yr_of_registration=(isset($_POST['yr_of_registration'])) ? db_input($_POST['yr_of_registration']) : '';
$NUm_of_yr_exp=(isset($_POST['NUm_of_yr_exp'])) ? db_input($_POST['NUm_of_yr_exp']) : '';
$speciality_id=(isset($_POST['speciality_id'])) ? db_input($_POST['speciality_id']) : '';
$no_of_publication=(isset($_POST['NUM_of_publications'])) ? db_input($_POST['NUM_of_publications']) : '';
$part_of=(isset($_POST['part_of_national'])) ? db_input($_POST['part_of_national']) : '';
$speaker=(isset($_POST['speaker'])) ? db_input($_POST['speaker']) : '';
$part_of_peer=(isset($_POST['part_of_peer'])) ? db_input($_POST['part_of_peer']) : '';
$position=(isset($_POST['position'])) ? db_input($_POST['position']) : '';
$no_of_yr_experience_clinic=(isset($_POST['num_of_years_of_clinical_expr'])) ? db_input($_POST['num_of_years_of_clinical_expr']) : '';


if($mode == 'I'){

    $id=NextID('id','crm_hcp_information');
    $stmt=$pdo->prepare("insert into crm_hcp_information (id,crm_request_main_id,yr_of_registration,no_of_yr_experience_doctor,speciality_id,no_of_publication,part_of,speaker,part_of_peer,position,no_of_yr_experience_clinic,hcp_sign,hcp_sign_date,emp_sign,emp_sign_date,submitted_on,submitted,deleted,deleted_on) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ");
    $stmt->execute(array($id,$pid,$yr_of_registration,$NUm_of_yr_exp,$speciality_id,$no_of_publication,$part_of,$speaker,$part_of_peer,$position,$no_of_yr_experience_clinic,'','','','',NOW,1,0,NOW));

    $HID=NextID('pid','crm_hcp_information_history');

    //Insert into Hcp history
    $pdo->prepare("insert into crm_hcp_information_history(pid,id,crm_request_main_id,yr_of_registration,no_of_yr_experience_doctor,speciality_id,no_of_publication,part_of,speaker,part_of_peer,position,no_of_yr_experience_clinic,hcp_sign,hcp_sign_date,emp_sign,emp_sign_date,submitted_on,submitted,deleted,deleted_on,from_date,to_date,updated_by) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")->execute(array($HID,$id,$pid,$yr_of_registration,$NUm_of_yr_exp,$speciality_id,$no_of_publication,$part_of,$speaker,$part_of_peer,$position,$no_of_yr_experience_clinic,'','','','',NOW,1,0,NULL,NOW,NULL,$USER_ID));

  
    header('location: service_agreement_camp.php?rid='.$rid.'&userid='.$USER_ID.'&pid='.$pid);
    exit;

}else if($mode == 'E'){

    $hcp_info_id=db_input($_POST['hcp_info_id']);

   

    $stmt=$pdo->prepare("update crm_hcp_information set  yr_of_registration ='$yr_of_registration',no_of_yr_experience_doctor = '$NumOfQ', speciality_id = '$speciality_id', no_of_publication = '$no_of_publication', part_of ='$part_of', speaker = '$speaker', part_of_peer='$part_of_peer', position='$position', no_of_yr_experience_clinic='$no_of_yr_experience_clinic' where id= $hcp_info_id ");
    $stmt->execute();

    $last_ID = GetXFromYID("select pid from crm_hcp_information_history where id='$hcp_info_id' and deleted=0 order by pid DESC limit 1 ");

    if (!empty($last_ID)) {
        $pdo->prepare("update crm_hcp_information_history set to_date=? where pid=? ")->execute(array(NOW, $last_ID));
    }

    $HID=NextID('pid','crm_hcp_information_history');
     //Insert into Hcp history
     $pdo->prepare("insert into crm_hcp_information_history(pid,id,crm_request_main_id,yr_of_registration,no_of_yr_experience_doctor,speciality_id,no_of_publication,part_of,speaker,part_of_peer,position,no_of_yr_experience_clinic,hcp_sign,hcp_sign_date,emp_sign,emp_sign_date,submitted_on,submitted,deleted,deleted_on,from_date,to_date,updated_by) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")->execute(array($HID,$hcp_info_id,$pid,$yr_of_registration,$NumOfQ,$speciality_id,$no_of_publication,$part_of,$speaker,$part_of_peer,$position,$no_of_yr_experience_clinic,'','','','',NOW,1,0,NULL,NOW,NULL,$USER_ID));

    header('location: service_agreement_camp.php?rid='.$rid.'&userid='.$USER_ID.'&pid='.$pid);
    exit;


}
?>
