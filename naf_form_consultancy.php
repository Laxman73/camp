<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/common.php";


$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$prid = (isset($_GET['prid'])) ? $_GET['prid'] : '';
$category = (isset($_GET['category'])) ? $_GET['category'] : '';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0 AND activity_type= '.$category.'', '3');

$ADVANCE_PAYMENT_ARRAY=array('Yes'=>'Yes','No'=>'NO');

$categoryid = $category;

if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}

if(!empty($rid) && $prid==''){
	$prid = GetXFromYID("SELECT cm.id FROM crm_naf_main c
	INNER JOIN crm_naf_main cm ON c.parent_id = cm.naf_no
	WHERE c.id = $rid AND c.deleted = 0  AND cm.deleted =0");	
}

// if($prid!='')
// 	$category = GetXFromYID("select crm_naf_type_master.parent_id from crm_naf_main 
// INNER JOIN crm_naf_type_master ON crm_naf_main.category_id = crm_naf_type_master.pid
// where crm_naf_main.id='$prid' AND crm_naf_type_master.parent_id!=0");

// echo "select crm_naf_type_master.parent_id from crm_naf_main 
// INNER JOIN crm_naf_type_master ON crm_naf_main.category_id = crm_naf_type_master.pid
// where crm_naf_main.id='$prid' AND crm_naf_type_master.parent_id!=0";
// exit;


// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
//$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division
$SPECILITY_ARR = array();


//$query1="select *  from speciality where  in_use=0 AND fyear='".$curr_fyear."' and division=".$division;

$MODE = '';
$selectoption = $readonly = '';							   

if ($PROFILE_ID >= 8 || $PROFILE_ID <= 5) {
	$MODE = 'R';
}

if (!empty($rid) &&  ($PROFILE_ID == 6 || $PROFILE_ID == 7)) {
	$MODE = 'R';
}

$CITY_ARR = GetXArrFromYID("SELECT cityid as id, concat_ws('',cityname,if(city.metrononmetro=1,'(Metro)','(Non-Metro)')) as name FROM city where deleted=0 ORDER BY cityname", '3');

$_q = "select field_id,field_name,typeid from crm_naf_fields_master where deleted=0 AND typeid = '".$category."' ORDER BY sequence";
// echo $_q;
// exit;
$_r = sql_query($_q, "");

// defining variables
$CRM_FILEDS = array();
$startDate = $NAF_NO = $naf_city = $naf_end_date = $initiator = $category_id = $naf_proposed_venue = $naf_activity_name = $naf_estimate_no_participents = $productID = $date = $naf_objective_rational = $rationale_remark = $role_of_advisory = $x_total = '';


if (isset($_GET['prid'])) $MODE = 'A';




$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division
$SPECILITY_ARR = $CRM_FILEDS = $SELECTED_SPECIALITIES = $S_ARRA = array();


// $division = GetXFromYID("select division from users where id='$USER_ID' ");
$curr_dt = date('Y-m-d');
// $financial_qry = "SELECT financial_year FROM financialyear where '".$curr_dt."' between from_date and to_date";
// $financial_qry_res = sql_query($financial_qry);
$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");




$CITY_ARR = GetXArrFromYID("SELECT cityid as id, concat_ws('',cityname,if(city.metrononmetro=1,'(Metro)','(Non-Metro)')) as name FROM city where deleted=0 ORDER BY cityname", '3');

$cond = '';
if (!empty($User_division) && (isset($User_division))) {
	$cond .= " and division=$User_division ";
}


$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear'   " . $cond, '3');

$PRODUCTS_ARR = GetXArrFromYID("select productbrandtypeid as id ,productbrandtype as name from productbrandtype where presence=1 " . $cond . " order by productbrandtype ASC", '3');
$p_selectoption = '';
$p_readonly = '';
$P_NAF_NO = GetXFromYID("select naf_no from crm_naf_main where id='$prid' ");																	 
$p_readonly = 'readonly';

if (empty($rid) && $MODE == 'A') {
	
		
	
	$crm_parent_data = GetDataFromID('crm_naf_main', 'id', $prid, 'and deleted=0');
	
	$p_requestor_ID = isset($crm_parent_data[0]->userid) ? db_output2($crm_parent_data[0]->userid) : '';
	$category_id = isset($crm_parent_data[0]->category_id) ? db_output2($crm_parent_data[0]->category_id) : '';
	$naf_activity_name = isset($crm_parent_data[0]->naf_activity_name) ? db_output2($crm_parent_data[0]->naf_activity_name) : '';	

	$p_division = GetXFromYID("select division from users where id='$USER_ID' ");
	$curr_dt = date('Y-m-d');
	// $financial_qry = "SELECT financial_year FROM financialyear where '".$curr_dt."' between from_date and to_date";
	// $financial_qry_res = sql_query($financial_qry);
	$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");

	$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear' and division='$p_division' ", '3');


	$_sp_q = "select speciality_id from crm_naf_speciality_details where naf_request_id='$prid' ";
	
	$_sq_q_r = sql_query($_sp_q, '');
	while (list($specialityid) = sql_fetch_row($_sq_q_r)) {
		if (!isset($SELECTED_SPECIALITIES[$specialityid]))
			$SELECTED_SPECIALITIES[$specialityid] =  array('specialityid' => $specialityid);
	}

	
}



$SELECTED_SPECIALITIES = array();

$HCP_UNIVERSAL_ID = GetXArrFromYID("select contactid,masterid from contactdetails order by contactid LIMIT 100 ", "3");

// getting data from db, if mode = R
 $readonly = '';
$SELECTED_SPECIALITIES = array();
$selectoption = '';
if ($MODE == 'R' || $MODE == 'E') {
	$doc_upload_path = '';
	if ($MODE == 'R') {
		$NAF_NO = GetXFromYID("select naf_no from crm_naf_main where id='$rid' ");
		$readonly = 'readonly';
		$selectoption = 'disabled';
	}

	 $id = $rid;

	if (empty($id)) {
		//header('location: index_PM.php?userid=' . $USER_ID);
	} 
	$crm_data = GetDataFromID('crm_naf_main', 'id', $id, 'and deleted=0');
	$crm_naf_activity_details = GetDataFromID('crm_naf_activity_details', 'naf_request_id', $id);
	$crm_naf_product_details = GetDataFromID('crm_naf_product_details', 'naf_request_id', $id, 'and deleted=0');
	$crm_naf_speciality_details = GetDataFromID('crm_naf_speciality_details', 'naf_request_id', $id);
	$crm_naf_cost_details = GetDataFromID('crm_naf_cost_details', 'naf_request_id', $id);

	$requestor_ID = isset($crm_data[0]->userid) ? db_output2($crm_data[0]->userid) : ''; //userids
	$doc_upload_path = db_output2($crm_data[0]->doc_upload_path);	

	$division = GetXFromYID("select division from users where id='$requestor_ID' ");
	$curr_dt = date('Y-m-d');
	// $financial_qry = "SELECT financial_year FROM financialyear where '".$curr_dt."' between from_date and to_date";
	// $financial_qry_res = sql_query($financial_qry);
	$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");

	$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear' and division='$division' ", '3');


	$_sp_q = "select speciality_id from crm_naf_speciality_details where naf_request_id='$id' ";
	
	$_sq_q_r = sql_query($_sp_q, '');
	while (list($specialityid) = sql_fetch_row($_sq_q_r)) {
		if (!isset($SELECTED_SPECIALITIES[$specialityid]))
			$SELECTED_SPECIALITIES[$specialityid] =  array('specialityid' => $specialityid);
	}


	$User_division = $division; //Getting division

	$_crm_field_q = "select naf_field_id,naf_expense from crm_naf_cost_details where naf_request_id='$id' ";
	$_crm_field_r = sql_query($_crm_field_q, '');

	while (list($naf_field_id, $naf_expense) = sql_fetch_row($_crm_field_r)) {
		if (!isset($CRM_FILEDS[$naf_field_id]))
			$CRM_FILEDS[$naf_field_id] = array('value' => $naf_expense);
	}


	$startDate = isset($crm_data[0]->naf_start_date) ? db_output2($crm_data[0]->naf_start_date) : '';
	$naf_city = isset($crm_data[0]->naf_city) ? db_output2($crm_data[0]->naf_city) : '';
	$naf_end_date = isset($crm_data[0]->naf_end_date) ? db_output2($crm_data[0]->naf_end_date) : '';
	$initiator = isset($crm_data[0]->initiator) ? db_output2($crm_data[0]->initiator) : '';
	$category_id = isset($crm_data[0]->category_id) ? db_output2($crm_data[0]->category_id) : '';
	$naf_proposed_venue = isset($crm_data[0]->naf_proposed_venue) ? db_output2($crm_data[0]->naf_proposed_venue) : '';
	$naf_activity_name = isset($crm_data[0]->naf_activity_name) ? db_output2($crm_data[0]->naf_activity_name) : '';
	$virtual_physical = isset($crm_data[0]->mode) ? db_output2($crm_data[0]->mode) : '';
	$rationale_remark = isset($crm_data[0]->rationale_remark) ? db_output2($crm_data[0]->rationale_remark) : '';
	$role_of_advisory = isset($crm_data[0]->role_of_advisory) ? db_output2($crm_data[0]->role_of_advisory) : '';
	$naf_estimate_no_participents = isset($crm_data[0]->naf_estimate_no_participents) ? db_output2($crm_data[0]->naf_estimate_no_participents) : '';
	$productID = isset($crm_naf_product_details[0]->product_id) ? db_output2($crm_naf_product_details[0]->product_id) : '';



	$activity = isset($crm_naf_activity_details[0]->activity_id) ? db_output2($crm_naf_activity_details[0]->activity_id) : '';
	$date = isset($crm_data[0]->date) ? db_output2($crm_data[0]->date) : '';
	$naf_objective_rational = isset($crm_data[0]->naf_objective_rational) ? db_output2($crm_data[0]->naf_objective_rational) : '';
	$x_total = GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$id' ");
	
	$cond = '';
	if (!empty($User_division) && (isset($User_division))) {
		$cond .= " and division=$User_division ";
	}
	
	//$PRODUCTS_ARR = GetXArrFromYID("select productbrandtypeid as id ,productbrandtype as name from productbrandtype where presence=1  order by productbrandtype ASC", '3');
	$PRODUCTS_ARR = GetXArrFromYID("select productbrandtypeid as id ,productbrandtype as name from productbrandtype where presence=1 " . $cond . " order by productbrandtype ASC", '3');

	// echo 'hii';
	// exit;
} else {
	$requestor_ID = $USER_ID; //userids
	$division = GetXFromYID("select division from users where id='$requestor_ID' ");
	$curr_dt = date('Y-m-d');
	// $financial_qry = "SELECT financial_year FROM financialyear where '".$curr_dt."' between from_date and to_date";
	// $financial_qry_res = sql_query($financial_qry);
	$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");

	$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear' and division='$division' ", '3');
	$cond = '';
	$User_division = $division;
	if (!empty($User_division) && (isset($User_division))) {
		$cond .= " and division=$User_division ";
	}

	$PRODUCTS_ARR = GetXArrFromYID("select productbrandtypeid as id ,productbrandtype as name from productbrandtype where presence=1 " . $cond . " order by productbrandtype ASC", '3');
}


$HCP_UNIVERSAL_ID = GetXArrFromYID("select contactid,masterid from contactdetails order by contactid LIMIT 100 ", "3");


//DFA($PRODUCTS_ARR);

$sql_naf_doc="SELECT id, naf_main_id,hcp_id,hcp_address,hcp_pan,hcp_qualification,hcp_associated_hospital_id,govt_type,yr_of_experience,role_of_hcp,honorarium_amount,crm_naf_hcp_details.mobile, contactdetails.masterid, CONCAT_WS(' ', contactdetails.firstname, contactdetails.middlename, contactdetails.lastname) AS hcp_name FROM crm_naf_hcp_details
INNER JOIN contactdetails ON crm_naf_hcp_details.hcp_id = contactdetails.contactid 
WHERE crm_naf_hcp_details.deleted = 0 AND crm_naf_hcp_details.naf_main_id = '".$rid."'";
$res_naf_doc=sql_query($sql_naf_doc,'');

?>

<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Microlabs</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
	
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
	<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">
	
	
		
	<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">


	<!--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
	<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
	
</head>

<style>


.modal {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 90%;
  padding: 1.3rem;
  min-height: 90%;
  top: 10%;
  left: 5%;
  right: 5%;
  background-color: white;
  border: 1px solid #ddd;
  border-radius: 15px;
}

.modal .flex {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal input {
  padding: 0.7rem 1rem;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 0.9em;
}

.modal p {
  font-size: 0.9rem;
  color: #777;
  margin: 0.4rem 0 0.2rem;
}

button {
  cursor: pointer;
  border: none;
  font-weight: 600;
}

.btn {
  display: inline-block;
  padding: 0.8rem 1.4rem;
  font-weight: 700;
  background-color: black;
  color: white;
  border-radius: 5px;
  text-align: center;
  font-size: 1em;
}


.btn-close {
  transform: translate(10px, -20px);
  padding: 0.5rem 0.7rem;
  background: #eee;
  border-radius: 50%;
}

.overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(3px);
  z-index: 1;
}

.hidden {
  display: none;
}
</style>

<body>


    <div class="appHeader bg-primary">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle camp">Need Assessment Form (By Activity Requestor)</div>
        <div class="right"></div>
    </div>
	
	<?php 

	include '_tabs_naf_consultancy.php'; 
   ?>

	<div id="appCapsule">
        <div class="tab-content mt-7">

			<form action="save_naf_consultancy_activity.php" id="naf_form" method="post" enctype="multipart/form-data">
				<input type="hidden" name="prid" value="<?php echo $prid; ?>">			
				<input type="hidden" name="parent_id" value="<?php echo $P_NAF_NO; ?>">			
				<input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">			
				<input type="hidden" name="category" value="<?php echo $categoryid; ?>">			
				<input type="hidden" value="" id="selected_hcp" name="selected_hcp" />		
				<div class="section mt-2">
					<div class="card">
						<div class="card-body">  
							<div class="wide-block pt-2 pb-2">
								<div class="row">							
									<div class="col-3">
										<b>Request Date:</b>
									</div>
								
									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="" placeholder="<?php echo "" . date("d/m/Y") . "";?>" disabled>
										</div>										
									</div>
								</div>
							
								<br>
							
								<div class="row">							
									<div class="col-3">
										<b>Product / Therapy:<span style="color:#ff0000">*</span></b>
									</div> 
								
									<div class="col-9">
										<div class="input-wrapper">
											<select class="form-control custom-select" id="Product" onchange="chkothers()" name="productID" required="" <?php echo $selectoption; ?>>
												<option value="">Choose...</option>
												<?php
												foreach ($PRODUCTS_ARR as $key => $value) {
													//echo $value[$key]['iModID'];
													$selected =    ($productID == $key) ? 'selected' : '';
													echo '<option value="' . $key . '" ' . $selected . ' >' . $value . '</option>';
												}
												$k = 0;
												//$selected =    ($productID == $k) ? 'selected' : '';
												echo '<option value="' . $k . '" > Others </option>';
												?>
											</select>
										</div>
										<br>
										<input type="text" class="form-control" id="others" size="70" value="" placeholder="Enter product here " name="others" style="display: none;" <?php echo $readonly; ?>>

									</div>
								</div>
							
								<br>
							
								<div class="row">
							
									<div class="col-3">
										<b>Parent ID:<span style="color:#ff0000">*</span></b>
									</div>
									
									<div class="col-9">
										<div class="input-wrapper">
											<b><?php
												echo $P_NAF_NO; ?></b>
										</div>
									</div>
								</div>
								<br>
							
								<div class="row">
								
									<div class="col-3">
										<b>Event ID:<span style="color:#ff0000">*</span></b>
									</div>
									
									<div class="col-9">
										<div class="input-wrapper">
											<b><?php
												echo ($MODE == 'R') ? $NAF_NO : 'NAF number will be generated once the form is submitted';; ?></b>
										</div>
									</div>
								</div>
								
								<br>								
															
								<div class="row">

									<div class="col-3">
										<b>Name of the Activity:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" name="activity_name" value="<?php echo $naf_activity_name; ?>" class="form-control" required="" id="activity_name" placeholder="" <?php echo $p_readonly; ?>>
										</div>
									</div>
								</div>
								<br>
							
								<div class="row">
								
									<div class="col-3">
										<b>Nature of the Activity:<span style="color:#ff0000">*</span></b>
									</div>
									
									<div class="col-9">
										<div class="input-wrapper">
											<select class="form-control custom-select" id="Nature_of_activity" name="Nature_of_activity" required="" <?php echo $selectoption; ?>>
													<?php
													foreach ($ACTIVITY_ARR as $key => $value) {
														//echo $value[$key]['iModID'];
														$selected =    ($activity == $key) ? 'selected' : '';
														echo '<option value="' . $key . '" ' . $selected . ' >' . $value . '</option>';
													}
													?>
											</select>
										</div>
									</div>
								</div>	
								
								
								<br>
								
								<div class="row">								
									<div class="col-3">
										<b>Date of the Activity :<span style="color:#ff0000">*</span></b>
									</div>
									
									<div class="col-9">
										<div class="input-wrapper">
											<input type="date" min="<?php echo TODAY; ?>" class="form-control" value="<?php echo $date; ?>" id="date_of_activity" name="date_of_activity" <?php echo $readonly; ?>>
										</div>
									</div>
								</div>								
								
								<br>							
								<div class="row">
							
									<div class="col-3">
										<b>City( Metro and Non Metro):</b>
									</div> 
									
									<div class="col-9">
											<div class="input-wrapper">
												<select class="form-control custom-select" id="city" name="city" <?php echo $selectoption; ?>>
													<option selected="" value="">Choose...</option>
													<?php
													foreach ($CITY_ARR as $key => $value) {
														//echo $value[$key]['iModID'];
														$selected =    ($naf_city == $key) ? 'selected' : '';
														echo '<option value="' . $key . '" ' . $selected . ' >' . $value . '</option>';
													}
													?>
												</select>
											</div>
										</div>
								</div>												
								
								<br>
								
								<div class="row">								
									<div class="col-3">
										<b>Proposed Venue:</b>
									</div>
									
									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" name="propose_venue" value="<?php echo $naf_proposed_venue; ?>" class="form-control" id="" placeholder="" <?php echo $readonly; ?>>
										</div>
									</div>
								</div>
								
								<br>
								
								<div class="row">								
									<div class="col-3">
										<b>Estimated Number of Participnts:<span style="color:#ff0000">*</span></b>
									</div>
									
									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" value="<?php echo $naf_estimate_no_participents; ?>" id="Num_of_Participants" name="Num_of_Participants" placeholder="" required="" <?php echo $readonly; ?>>
										</div>
									</div>
								</div>

								<br>
								
								<div class="row">
								
									<div class="col-3">
										<b>Targeted Speciality:<span style="color:#ff0000">*</span></b>
									</div>
				
									
									<?php
										if ($MODE == 'R') {
											$S_ARRA = array();
											//DFA($SELECTED_SPECIALITIES);
											foreach ($SPECILITY_ARR as $key => $value) {
												if (isset($SELECTED_SPECIALITIES[$key]['specialityid'])) {
													# code...
													if ($SELECTED_SPECIALITIES[$key]['specialityid'] == $key) {
														array_push($S_ARRA, $value);
													}
												}
											}
											//DFA($S_ARRA);

											} else {
										?>
									
									<div class="col-9">
												<div class="input-wrapper">
													<select class="form-control custom-select" name="targetedSpeciality[]" multiple id="targetedSpeciality" <?php echo $selectoption; ?> >
														<?php
														foreach ($SPECILITY_ARR as $key => $value) {
															//echo $value[$key]['iModID'];
															$selected =    ($SELECTED_SPECIALITIES[$key]['specialityid'] == $key) ? 'selected' : '';
															echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
														}
														?>
													</select>
												</div>
											</div>

										<?php	}
										?>
								</div>							
														
								<br>
								
								<div class="row">
								
									<div class="col-3">
										<b>Is this an Advance Payment? If yes,please mention the advance amount:</b>
									</div>
									
									<div class="col-9">
										<div class="input-wrapper">
											<?php
											// $MODE= 'A';
											if ($MODE == 'R') { ?>
												<div id="advance_SRC"><img src="<?php echo $doc_upload_path; ?>" alt="PAN Image" style="width:30%;height:auto;"></div>

											<?php	} elseif ($MODE == 'A') { ?>
												<select class="form-control custom-select" name="advance_payment" id="myselection" required="">
													<option selected="" disabled="" value="">Choose...</option>
													<?php
													foreach ($ADVANCE_PAYMENT_ARRAY as $key => $value) {
														echo '<option value="' . $key . '" >' . $value . '</option>';
													}
													?>
												</select>

											<?php	}

											?>
										</div>
									</div>						
								</div>
								<?php	if ($MODE == 'A') { ?>					
								<div id="showYes" class="myDiv" style="display:none;">
										<div class="custom-file-upload">
											<input type="file" id="advance_file" name="advance_file" accept=".png, .jpg, .jpeg">
											<label for="advance_file">
												<span>
													<strong>
														<ion-icon name="cloud-upload-outline"></ion-icon>
														<i>Tap to Upload</i>
													</strong>
												</span>
											</label>
										</div>
								</div>
								<?php } ?>	
							</div>
						</div>
					</div>
						
						
					<div class="section mt-2">
						<div class="card">
						<div class="card-body">
							
							<div class="form-group basic">
								<div class="input-wrapper">
									<label class="label" for="">The objective/rationale/need for conducting an Activity:<span style="color:#ff0000">*</span></label>
									<textarea id="rationals" name="rationals" required="" rows="3" class="form-control" <?php echo $readonly; ?>><?php echo $naf_objective_rational; ?></textarea>
								</div>
							</div>				
						</div>
						</div>
					</div>					
							
					<div class="section mt-2">
						<div class="card">
							<div class="card-body">
								<div class="row">							
									<div class="col-9">
										<b>Budgeted Activity Cost Details</b> Kindly complete all the relevant cost elements to be incurred)
									</div>							
								</div>

								<br>
						
								<div class="wide-block" style="border-top: 1px solid black;border-bottom: 1px solid black;">
									<div class="row">								
										<div class="col-3">
											<div class="form-title">Particulars</div>
										</div>
										
										<div class="col-9">
											<div class="form-title">Amount(INR)</div>
										</div>
									</div>							
								</div>
						
								<?php

									while ($row = sql_fetch_assoc($_r)) {
										//DFA($row);
										 $compulsory = ($row['typeid'] == '8') ? 'required="" ' : '';
										$compulsoryfield = ($row['typeid'] == '8') ? '<span style="color:#ff0000">*</span>' : '';
										$value = (isset($CRM_FILEDS[$row['field_id']]['value'])) ? $CRM_FILEDS[$row['field_id']]['value'] : '0'; 
									?>
								<div class="row">
									<div class="col-3">
										<b><?php echo $row['field_name'] . ':'; ?><?php echo $compulsoryfield; ?></span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" value="<?php echo $value; ?>" td_id="<?php $row['field_id']; ?>" onkeypress="CalculateTotal();" onkeyup="CalculateTotal();" onblur="CalculateTotal();" name="crm_<?php echo $row['field_id']; ?>" class="form-control amountCalculate" id="crm_<?php echo $row['field_id']; ?>" placeholder="" <?php echo $readonly; ?> required>
										</div>
									</div>
								</div>
								<br>

								<?php }	?>

								<div class="row">
									<div class="col-3">
										<b>TOTAL AMOUNT:<span style="color:#ff0000">*</span></b>
									</div>
									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" id="total" value="<?php echo $x_total; ?>" name="total" placeholder="" readonly>
										</div>
									</div>
								</div>								
							</div>
						</div>
					</div>
				
							
					<section class="modal hidden">
						<div class="flex" style="background-color:'#a1bee3'">
							<h4><center><b>ADD HCP</b></center></h4>
							<button class="btn-close">⨉</button>
						</div>
						
						<div class="card-body">
							<div class="wide-block pt-2 pb-2">
								<div class="row">
									<div class="col-3">
										<b>HCP Universal ID:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<div class="form-group searchbox">
												<i class="input-icon">
													<ion-icon name="search-outline"></ion-icon>
												</i>

												<select class="form-control custom-select" onchange="getDeatils();" id="DoctorID" name="DoctorID">
													<option value="">--select--</option>
														<?php
														foreach ($HCP_UNIVERSAL_ID as $key => $value) {
															//echo $value[$key]['iModID'];
															//$selected =    ($SELECTED_AMENITIES[$key]['iVAID'] == $key) ? 'selected' : '';
															echo '<option value="' . $key . '" >' . $value . '</option>';
														}
														?>
												</select>
											</div>
										</div>
									</div>
								</div>

									<br>

								<div class="row">
									<div class="col-3">
										<b>Name of the HCP:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="hcp_name" name="hcp_name">
										</div>
									</div>
								</div>

								<br>
								<div class="row">
									<div class="col-3">
										<b>Address:</b>
									</div>
									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" name="address" class="form-control" id="address" placeholder="">
										</div>
									</div>
								</div>
								<br>

								<div class="row">
									<div class="col-3">
										<b>Mobile Number:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" name="hcp_mobile" class="form-control" id="hcp_mobile" placeholder="">
										</div>
									</div>
								</div>

								<br>

								<div class="row">
									<div class="col-3">
										<b>PAN:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" name="pan" class="form-control" id="pan" >
										</div>
									</div>
								</div>
								
								<br>

								<div class="row">
									<div class="col-3">
										<b>Qualification:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" name="qualification" id="qualification">
										</div>
									</div>
								</div>

								<br>
								<div class="row">
									<div class="col-3">
										<b>Associated Hospital/Clinic:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" name="associated_hospital" id="associated_hospital">
										</div>
									</div>
								</div>

								<br>
								<div class="row">
									<div class="col-3">
										<b>Govt(Yes/No):<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<select class="form-control custom-select" id="govt" name="govt">
												<option value="">Choose...</option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>
											</select>
										</div>
									</div>
								</div>
								<br>

								<div class="row">
									<div class="col-3">
										<b>Years of Experience:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" id="year_of_experience" name="year_of_experience" placeholder="">
										</div>
									</div>
								</div>
								<br>
								
								<div class="row">
									<div class="col-3">
										<b>Honorarium amount:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" name="Honorarium" class="form-control" id="Honorarium" placeholder="">
										</div>
									</div>
								</div>
									
								<br>
								<div class="row">
									<div class="col-3">
										<b>Role of HCP:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" name="role_of_hcp" class="form-control" id="role_of_hcp" placeholder="">
										</div>
									</div>
								</div>
							</div>

							<button type="button" class="exampleBox btn btn-primary rounded me-1" onclick="addDoctor();">Add HCP</button>
						</div>
					</section>														
		
					<div class="overlay hidden"></div>
		
					<div class="section mt-7">									
						<div class="card">         
							<div class="card-body pd-1">						
								<table id="example" class="display mt-2" style="width:100%">								
									<thead>
										<tr >
											<th></th>
											<th>Sr. No</th>
											<th>HCP Universal ID</th>
											<th>Name of the HCP</th>
											<th>Address(city)</th>													
											<th>Mobile Number</th>
											<th>PAN #</th>
											<th>Qualification</th>
											<th>Associated Hospital/Clinic</th>
											<th>Govt.(Yes/No)</th>
											<th>Years of experience</th>
											<th>Honrarium Amount</th>
											<th>Role of HCP</th>												
											<th><button class="exampleBox btn btn-primary rounded me-1 btn-open" <?php echo $selectoption; ?>>ADD</button></th>
										</tr>
									</thead>
									<tbody id="append_data">
										<?php if ($MODE == 'R') { 
										
											$html="";
											$s_no=1;
											for ($i=0; $o=sql_fetch_object($res_naf_doc) ; $i+1) {
												$masterid=$o->masterid;
												$hcp_name=$o->hcp_name;
												$hcp_address=$o->hcp_address;
												$hcp_pan=$o->hcp_pan;
												$hcp_qualification=$o->hcp_qualification;
												$hcp_associated_hospital=$o->hcp_associated_hospital_id;
												$govt_type=$o->govt_type;
												$yr_of_experience=$o->yr_of_experience;
												$honorarium_amount=$o->honorarium_amount;
												$role_of_hcp=$o->role_of_hcp;
												$mobile=$o->mobile;	
												
												$html.="<tr><td></td><td>".$s_no."</td>";
												$html.="<td>".$masterid."</td>";
												$html.="<td>".$hcp_name."</td>";
												$html.="<td>".$hcp_address."</td>";												
												$html.="<td>".$mobile."</td>";
												$html.="<td>".$hcp_pan."</td>";
												$html.="<td>".$hcp_qualification."</td>";
												$html.="<td>".$hcp_associated_hospital."</td>";
												$html.="<td>".$govt_type."</td>";
												$html.="<td>".$yr_of_experience."</td>";
												$html.="<td>".$honorarium_amount."</td>";
												$html.="<td>".$role_of_hcp."</td></tr>";
												$s_no=$s_no+1;
											}
											echo $html;
										}
										?>
									</tbody>			
								</table>
							</div>
						</div>
					</div>	
				
					<div class="section mt-2">
						<div class="card">
							<div class="card-body">
								<div class="form-group basic">
									<div class="input-wrapper">
										<label class="label" for="">rationale for selection:<span style="color:#ff0000">*</span></label>
										<textarea id="rationals_selection" name="rationals_selection" required="" rows="3" class="form-control" <?php echo $readonly; ?>><?php echo $rationale_remark; ?></textarea>
									</div>
								</div>
														
							</div>
						</div>
					</div>	
				</div>				
			
				<?php if ($MODE != 'R') { ?>
				<div class="section full mt-2">
					<div class="wide-block pt-2 pb-2">
						<div class="row">								
							<div class="col">
								<button type="submit" class="exampleBox btn btn-primary rounded me-1" onclick="">Submit</button>
							</div>								
						</div>
					</div>
				</div>
				<?php } ?>
			</form>
		</div>
	</div> 

    <!-- ///////////// Js Files ////////////////////  -->
	<script>
	jQuery('#targetedSpeciality').multiselect({
		columns: 1,
		placeholder: 'Choose...',
		search: true
	});
	</script>
    <!--<script src="assets/js/lib/jquery-3.4.1.min.js"></script>-->
    <!-- Bootstrap-->
    <script src="assets/js/lib/popper.min.js"></script>
    <script src="assets/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="assets/js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>


	<script>
				$(document).ready(function() {
			$('#myselection').on('change', function() {
				var demovalue = $(this).val();
				$("div.myDiv").hide();
				$("#show" + demovalue).show();
			});
		});	
		var addeddocarray =[];
		var selecteddoctorarray = [];
		function GoToPage(page) {
			window.document.location.href = page;
		}
		
		$(document).ready(function () {
			var table = $('#example').DataTable({
				responsive: true,
				ordering: false
			});
		});
		
		$(document).ready(function() {
			var max_chars = 5;

			$('#Num_of_Participants').keydown(function(e) {
				if ($(this).val().length >= max_chars) {
					$(this).val($(this).val().substr(0, max_chars));
				}
			});
		});

		function CalculateTotal() {
			var total = 0;
			$('.amountCalculate').each(function() {
				var numbers = $(this).val();

				if ((numbers != '')) {

					total += parseFloat(numbers);
				}

			});
			$('#total').val(total);
		}
		
		function chkothers() {
			var x = document.getElementById("Product").value;
			if (x ==0) {
				document.getElementById("others").style.display = '';			
			}
			else{
				document.getElementById("others").style.display = 'none';	
			}
		}
	
		$('#activity_name').keypress(function (e) {
			var regex = /^[A-Za-z0-9 _.-]+$/;
			var strigChar = String.fromCharCode(!e.charCode ? e.which : e.charCode);
			if (regex.test(strigChar)) {
				return true;
			}
			return false
		});
		
		function getDeatils() {			
			
			$("#hcp_name").val("");
			$("#address").val("");
			$("#qualification").val("");
			$("#associated_hospital").val("");
			$("#hcp_mobile").val("");
			$("#year_of_experience").val("");
			$("#pan").val("");
			$("#govt").val("");	
			$("#Honorarium").val("");	
			$("#role_of_hcp").val("");	
			
			var conatactid = $('#DoctorID').val();
			$.ajax({
				url: '_getDetails.php',
				method: 'POST',
				data: {
					cid: conatactid
				},
				success: function(res) {
					const myArray = res.split("~~");
					console.log(myArray);
					$('#hcp_name').val(myArray[0] + ' ' + myArray[1]);
					$('#address').val(myArray[5]);
					$('#qualification').val(myArray[2]);
					$('#associated_hospital').val(myArray[4]);
				}

			});
		}
		
		const modal = document.querySelector(".modal");
		const overlay = document.querySelector(".overlay");
		const openModalBtn = document.querySelector(".btn-open");
		const closeModalBtn = document.querySelector(".btn-close");

		// close modal function
		const closeModal = function () {
		  modal.classList.add("hidden");
		  overlay.classList.add("hidden");
		};

		// close the modal when the close button and overlay is clicked
		closeModalBtn.addEventListener("click", closeModal);
		overlay.addEventListener("click", closeModal);

		// close modal when the Esc key is pressed
		document.addEventListener("keydown", function (e) {
		  if (e.key === "Escape" && !modal.classList.contains("hidden")) {
			closeModal();
		  }
		});

		// open modal function
		const openModal = function () {
		  modal.classList.remove("hidden");
		  overlay.classList.remove("hidden");
		};
		// open modal event
		openModalBtn.addEventListener("click", openModal);

		$('#pan').keyup(function(event) {
					var pan = $(this);
					var inputvalues = $(this).val();
					var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
					if (!regex.test(inputvalues)) {
						//$("#pan").val("");
						//alert("invalid PAN no");
						ShowError(pan, "invalid PAN no");
						return regex.test(inputvalues);
					} else {
						HideError(pan);
					}

					//});

				});
				
	function addDoctor(){		
		var DoctorID=document.getElementById("DoctorID").value;	
		var hcp_name=document.getElementById("hcp_name").value;	
		var address=document.getElementById("address").value;		
		var qualification=document.getElementById("qualification").value;		
		var associated_hospital=document.getElementById("associated_hospital").value;		
		var hcp_mobile=document.getElementById("hcp_mobile").value;	
		var pan=document.getElementById("pan").value;	
		var govt=document.getElementById("govt").value;	
		var year_of_experience=document.getElementById("year_of_experience").value;	
		var honorarium =document.getElementById("Honorarium").value;	
		var role_of_hcp=document.getElementById("role_of_hcp").value;
		
		
		var DoctorID_value = $("#DoctorID option:selected").text();
		var DoctorID_passed     = $('#DoctorID').val();
		var hcp_name_passed    = $('#hcp_name').val();
		var address_passed      = $('#address').val();
		var qualification_passed = $('#qualification').val();
		var associated_hospital_passed = $('#associated_hospital').val();
		var pan_passed = $('#pan').val();
		var hcp_mobile_passed = $('#hcp_mobile').val();

		
		
		if (jQuery.trim(DoctorID_passed).length == 0)
		{
			alert("Please select HCP Universal ID !");
			return false;
		}
		if (jQuery.trim(address_passed).length == 0)
		{
			alert("Please enter Address !");
			return false;
		}
		
		if (jQuery.trim(hcp_mobile_passed).length == 0)
		{
			alert("Please enter Mobile Number !");
			return false;
		}
		
		if (jQuery.trim(hcp_mobile_passed).length != 10)
		{
			alert("Please enter valid Mobile Number !");
			return false;
		}
		
		if (jQuery.trim(pan_passed).length == 0)
		{
			alert("Please enter PAN !");
			return false;
		}
		
		if (jQuery.trim(qualification_passed).length == 0)
		{
			alert("Please enter Qualification !");
			return false;
		}

		if (jQuery.trim(associated_hospital_passed).length == 0)
		{
			alert("Please enter Associated Hospital !");
			return false;
		}
		
		if (jQuery.trim(govt).length == 0)
		{
			alert("Please select Govt(Yes/No) !");
			return false;
		}
		
		if (jQuery.trim(year_of_experience).length == 0)
		{
			alert("Please enter Year Of Experience !");
			return false;
		}
		
		if (jQuery.trim(honorarium).length == 0)
		{
			alert("Please enter honorarium amount !");
			return false;
		}
		if (jQuery.trim(role_of_hcp).length == 0)
		{
			alert("Please enter role of hcp !");
			return false;
		}
		
		/* if( honorarium < 1)
		{
			alert("Honorarium amount should be greater than zero!!");
			return false;
		} */
		
		

		
		
		if (DoctorID == "" || address == "" || pan == "" || hcp_mobile == "" || govt == "" || year_of_experience == "")
		{
			alert("Please select all Parameters, marked with * !");
			return false;
		}

		if(addeddocarray.length > 20)
		{
			alert(" Maximum of 20 HCP can be added to the list. Kindly adjust the HCP  List");
			error=1;
			return false;
		}
		
		if(addeddocarray.length == $('#Num_of_Participants').val())
		{
			alert("HCP cannot be more than the Number of Participants");
			error=1;
			return false;
		}
				
		for (var i=0; i<addeddocarray.length; i++)
		{
			if(DoctorID == addeddocarray[i].doctorid)
			{
				alert("HCP already added");
				return false;
			}
		}
		
		
				$("#DoctorID").val(""); 
				$("#hcp_name").val("");
				$("#address").val("");
				$("#qualification").val("");
				$("#associated_hospital").val("");
				$("#hcp_mobile").val("");
				$("#year_of_experience").val("");
				$("#pan").val("");
				$("#govt").val("");
				$("#Honorarium").val("");	
				$("#role_of_hcp").val("");	
							
		var saveDataObj = {};
		
		saveDataObj={
								'doctorid':DoctorID,
								'doctorid_value':DoctorID_value,
								'hcp_name':hcp_name,
								'address':address,
								'qualification':qualification,
								'associated_hospital':associated_hospital,
								'pan':pan,
								'hcp_mobile':hcp_mobile,
								'govt':govt,
								'honorarium_amount':honorarium,
								'role_of_hcp':role_of_hcp,
								'year_of_experience':year_of_experience
					}		
		
		addeddocarray.push(saveDataObj);		
		closeModal();
		selected_doctors();
	}

	function validateName(name) {
		var nameReg = /^[A-Za-z]*$/;
		if (!nameReg.test(name)) {
			return false;
		} else {
			return true;
		}
	}

	function HideError(element) {
		var elemID = $(element).attr('id');
		var spanID = elemID + "_span";

		$(element).removeClass("is-invalid");
		$('#' + spanID).remove();
	}

	function selected_doctors()
	{	
		if(addeddocarray.length > 0)
		{
			var m = 1;
			var htmltable = '';
			for (var k = 0; k<addeddocarray.length; k++)
			{						
				htmltable += '<tr>';
				htmltable += '<th></th><td>'+m+'</td><td>'+addeddocarray[k].doctorid_value+'</td><td>'+addeddocarray[k].hcp_name+'</td><td>'+addeddocarray[k].address+'</td><td>'+addeddocarray[k].hcp_mobile+'</td><td>'+addeddocarray[k].pan+'</td><td>'+addeddocarray[k].qualification+'</td><td>'+addeddocarray[k].associated_hospital+'</td><td>'+addeddocarray[k].govt+'</td><td>'+addeddocarray[k].year_of_experience+'</td><td>'+addeddocarray[k].honorarium_amount+'</td><td>'+addeddocarray[k].role_of_hcp+'</td>';
				htmltable += '<td><a onclick="remove('+k+')" id="del_link"><span class="glyphicon glyphicon-remove"></span></a></td>';
				htmltable += '</tr>';
				m++;
			}
			$("#append_data").empty();
			$("#append_data").append(htmltable);
			
		}
		
		$('#selected_hcp').val(JSON.stringify(addeddocarray)); //store array
		var value = $('#selected_hcp').val(); //retrieve arra
		value = JSON.parse(value);		
	}
	

		function ShowError(element, mesg) {
			var spanID = element + "_span";

			if ($(element).hasClass("is-invalid")) {

			} else {
				$(element).addClass("is-invalid");
				$('<span id="' + spanID + '";" class="invalid-feedback em">' + mesg + '</span>').insertAfter(element);
			}
		}	
		

		$(document).on('click', '#del_link', function () 
		{ 
			$(this).closest('tr').remove();
			return false;
		});

		function remove(element) 
		{
			addeddocarray.splice(element, 1);
			selected_doctors();
		}

		$('#naf_form').submit(function() {
			var ret_val=false;				
			var x = document.getElementById("Product").value;
			var others = document.getElementById("others").value
			var honorarium_amt=document.getElementById("crm_30").value
			
			if( honorarium_amt < 1)
			{
				alert("Honorarium amount should be greater than zero!!");
				error=1;
				return false;
			}
						
				
			if (x ==0 &&  others=='') {
				alert("Please Add other Product/Therapy");
				error=1;
				return false;		
			}
			
			var targetspeciality=$('#targetedSpeciality');
			if(($("select[name='targetedSpeciality[]'] option:selected").length)>0){
				ret_val=true;
			}else{
				alert('Please select the specaility from the dropdown');
				return false;
			}
			
			if(addeddocarray.length == 0)
			{
				alert("Kindly add atleast 1 HCP");
				error=1;
				return false;
			}
			
			if(addeddocarray.length > 20)
			{
				alert(" Maximum of 20 HCP can be added to the list. Kindly adjust the HCP  List");
				error=1;
				return false;
			}	

			if(addeddocarray.length != $('#Num_of_Participants').val())
			{
				alert("HCP added should be equal to the Number of Participants");
				error=1;
				return false;
			}			
			return ret_val;
		});		
	</script>
</body>
</html>