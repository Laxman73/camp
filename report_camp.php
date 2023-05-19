<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/common.php";


$page_title = 'NAF report Camp Activity';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : ''; //naf id
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : ''; //pma id
$category = (isset($_GET['category'])) ? $_GET['category'] : '';
$prid = (isset($_GET['prid'])) ? $_GET['prid'] : ''; //pma id
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

$user_name = GetXFromYID("select user_name from users where id='" . $USER_ID . "'");

if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}
if (empty($rid) || (!empty($rid) && !is_numeric($rid))) {
	echo 'Invalid Access Detected!!!';
	exit;
}

// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
//$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division
$SPECILITY_ARR = $CRM_FILEDS = array();

$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0', '3');

$DATA = GetDataFromID('crm_naf_main', 'id', $rid);

$requestorID = $DATA[0]->userid;
$date = $DATA[0]->date;
$submited_date = $DATA[0]->submitted_on;
$event_date = $DATA[0]->eventdate;
$naf_no = $DATA[0]->naf_no;
$naf_activity_name = $DATA[0]->naf_activity_name;
$proposed_activity = $DATA[0]->proposed_activity;
$proposed_hcp_number = $DATA[0]->proposed_hcp_no;
$naf_city = $DATA[0]->naf_city;
$naf_proposed_venue = $DATA[0]->naf_proposed_venue;
$naf_estimate_no_participents = $DATA[0]->naf_estimate_no_participents;
$proposed_objective = $DATA[0]->proposed_objective;
$naf_objective_rational = $DATA[0]->naf_objective_rational;
$pendingwithid = $DATA[0]->pendingwithid;



$productID = GetXFromYID("select product_id from crm_naf_product_details where naf_request_id='$rid' ");

$User_division = GetXFromYID("select division from users where id='$requestorID' ");
$curr_dt = date('Y-m-d', strtotime($submited_date));
// $financial_qry = "SELECT financial_year FROM financialyear where '".$curr_dt."' between from_date and to_date";
// $financial_qry_res = sql_query($financial_qry);
$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");


$selectoption = $readonly = '';

$CITY_ARR = GetXArrFromYID("SELECT cityid as id, concat_ws('',cityname,if(city.metrononmetro=1,'(Metro)','(Non-Metro)')) as name FROM city where deleted=0 ORDER BY cityname", '3');

$cond = '';
if (!empty($User_division) && (isset($User_division))) {
	$cond .= " and division=$User_division ";
}

$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear'  " . $cond, '3');


$PRODUCTS_ARR = GetXArrFromYID("select productbrandtypeid as id ,productbrandtype as name from productbrandtype where presence=1 " . $cond . " order by productbrandtype ASC", '3');

$_sp_q = "select speciality_id from crm_naf_speciality_details where naf_request_id='$rid' and deleted=0  ";
$SELECTED_SPECIALITIES = array();
$_sq_q_r = sql_query($_sp_q, '');
while (list($specialityid) = sql_fetch_row($_sq_q_r)) {
	if (!isset($SELECTED_SPECIALITIES[$specialityid]))
		$SELECTED_SPECIALITIES[$specialityid] =   $specialityid;
}
//DFA($SELECTED_SPECIALITIES);

$S_ARRA = array();
//DFA($SELECTED_SPECIALITIES);
foreach ($SPECILITY_ARR as $key => $value) {
	if (isset($SELECTED_SPECIALITIES[$key])) {
		# code...
		if ($SELECTED_SPECIALITIES[$key] == $key) {
			array_push($S_ARRA, $value);
		}
	}
}
//DFA($S_ARRA);



$_crm_field_q = "select naf_field_id,naf_expense from crm_naf_cost_details where naf_request_id='$rid' ";
$_crm_field_r = sql_query($_crm_field_q, '');

while (list($naf_field_id, $naf_expense) = sql_fetch_row($_crm_field_r)) {
	if (!isset($CRM_FILEDS[$naf_field_id]))
		$CRM_FILEDS[$naf_field_id] = $naf_expense;
}
//DFA($CRM_FILEDS);


$CRM_REQUEST_LETTER_DATA = GetDataFromCOND('crm_request_camp_letter', " and crm_request_main_id=$pid ");
$doctors_ID = $CRM_REQUEST_LETTER_DATA[0]->hcp_id;
$doctors_name = GetXFromYID("select CONCAT(firstname, ' ', lastname) AS full_name  from contactmaster where id=$doctors_ID limit 500");


$x_total = GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$rid' "); //total amount

$_q = "select field_id,field_name from crm_naf_fields_master where deleted=0 AND typeid=6 "; //fetch typeid from the category id passed in url
$_r = sql_query($_q, "");

$mode = $readonly = '';

$is_report_submitted = GetXFromYID("select count(*) from crm_naf_camp_report where crm_request_id='$pid' ");
if ($is_report_submitted > 0) {
	$mode = 'E';
} else {
	$mode = 'A';
}

$sign_edit = $sign_display = $sign_date = $hcp_sign = $otp_verify_div = $registration_number = '';

if ($mode == 'A') {
	$otp_verify_div = 'display:block';
	$sign_display = 'display:none';
	$readonly = '';
	$sign_date = TODAY;
	$objective = '';
	$camp_duration = GetXFromYID("select proposed_camp_duration from crm_request_camp_letter where crm_request_main_id='$pid' ");
	$type_of_diagnostic = '';
	$diagnostic_charges = '';
	$total_no_ind = '';
	$camp_organised = '';
	$camp_received = '';
	$remarks = '';
	$registration_number = GetXFromYID("select registration_no from contactmaster where id='$doctors_ID' ");
} elseif ($mode == 'E') {
	$otp_verify_div = 'display:none';
	$sign_edit = 'display:none';
	$DATA = GetDataFromID('crm_naf_camp_report', 'crm_request_id', $pid);
	//DFA($DATA);
	$hcp_sign = GetXFromYID("select e_sign_doctor from crm_request_main where id='$pid' ");
	$sign_date = GetXFromYID("select e_sign_cheque_date from crm_request_main where id='$pid' ");
	$readonly = 'readonly';
	$objective = db_output2($DATA[0]->objective);
	$camp_duration = db_output2($DATA[0]->camp_duration);
	$type_of_diagnostic = db_output2($DATA[0]->type_of_diagnostic);
	$diagnostic_charges = db_output2($DATA[0]->diagnostic_charges);
	$total_no_ind = db_output2($DATA[0]->total_no_ind);
	$camp_organised = db_output2($DATA[0]->camp_organised);
	$registration_number = db_output2($DATA[0]->hcp_reg_no);
	$camp_received = db_output2($DATA[0]->camp_received);
	$remarks = db_output2($DATA[0]->remarks);
	$hcp_sign = GetXFromYID("select e_sign_doctor from crm_request_main where id='$pid' and deleted=0 ");
}

$CHECKBOXES = array('1' => 'Excellent', '2' => 'Good', '3' => 'Average', '4' => 'Poor');


$HCP_NAF_DETAILS = GetDataFromID('crm_naf_hcp_details', 'naf_main_id', $rid, "");
$hcp_id = $HCP_NAF_DETAILS[0]->hcp_id;
$hcp_name = GetXFromYID("SELECT CONCAT(firstname, ' ', lastname) AS full_name FROM contactmaster where  id='$hcp_id' ");
$hcp_address = $HCP_NAF_DETAILS[0]->hcp_address;
$hcp_associated_hospital_id = $HCP_NAF_DETAILS[0]->hcp_associated_hospital_id;
$crm_naf_hcp_details_id = $HCP_NAF_DETAILS[0]->id;
$collaboration_with_diag = GetXFromYID("select diagnostic_lab from crm_naf_camp_letter where crm_naf_hcp_details_id='$crm_naf_hcp_details_id' ");
$medical_cost = GetXFromYID("select naf_expense from crm_naf_cost_details where naf_field_id=24 and naf_request_id='$rid' ");

?>
<!doctype html>
<html lang="en">
<style>
	.custom-signature-upload {
		position: relative;
		display: flex;
		width: 100%;
		height: 200px;
	}


	.wrapper {
		border: 2px solid #e2e2e2;
		border-right: 0;
		border-radius: 8px 0 0 8px;
		padding: 5px;
	}

	canvas {
		background: #fff;
		width: 100%;
		height: 100%;
		cursor: crosshair;
	}

	button#clear_sign {
		height: 100%;
		background: #4b00ff;
		border: 1px solid transparent;
		border-left: 0;
		border-radius: 0 8px 8px 0;
		color: #fff;
		font-weight: 600;
		cursor: pointer;
	}

	button#clear_sign span {
		transform: rotate(90deg);
		display: block;
	}

	button#clear_sign1 {
		height: 100%;
		background: #4b00ff;
		border: 1px solid transparent;
		border-left: 0;
		border-radius: 0 8px 8px 0;
		color: #fff;
		font-weight: 600;
		cursor: pointer;
	}

	button#clear_sign1 span {
		transform: rotate(90deg);
		display: block;
	}

	.wrapper1 {
		border: 2px solid #e2e2e2;
		border-radius: 8px;
		padding: 5px;
	}
</style>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
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

</head>

<body>


	<div class="appHeader bg-primary">
		<div class="left">
			<a href="javascript:;" class="headerButton goBack">
				<ion-icon name="chevron-back-outline"></ion-icon>
			</a>
		</div>
		<div class="pageTitle">CAMP REPORT</div>
		<div class="right"></div>
	</div>
	<?php include '_tabscamp.php'; ?>

	<!--<div class="section full mt-7">
            <div class="wide-block pt-2 pb-2">

                <div class="row">
                    <div class="col-2">
                        <a href="naf_form_pma.php"><div class="tabBox">NAF Form</div></a>
                    </div>
                    <div class="col-2">
						<a href="hcp_form_pma.php"><div class="tabBox">HCP Information Form</div></a>
                    </div>
            
                </div>

            </div>
        </div>-->




	<div id="appCapsule">

		<form action="save_camp_report.php" method="post" id="camp_report_form">
			<input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
			<input type="hidden" name="pid" value="<?php echo $pid; ?>">
			<input type="hidden" name="rid" id="rid" value="<?php echo $rid; ?>">
			<input type="hidden" name="prid" value="<?php echo $prid; ?>">
			<textarea id="signature1" name="signature1" style="display: none"></textarea>



			<div class="tab-content mt-1">



				<div class="section mt-7">
					<div class="card">
						<div class="card-body">

							<div class="wide-block pt-2 pb-2">



								<?php if ($mode == 'E') { ?>
									<a href="pdf_camp.php?rid=<?php echo $rid; ?>&pid=<?php echo $pid; ?>" class="btn btn-success" target="_blank" rel="noopener noreferrer" style="float: right;"> view Pdf</a>
									<br><br>


								<?php	}  ?>





								<div class="row">



									<div class="col-3">
										<b>Camp Name<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="camp_name" name="camp_name" placeholder="" value="<?php echo $naf_activity_name; ?>">
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
											<select class="form-control custom-select" id="Nature_of_activity" name="Nature_of_activity" disabled>
												<?php
												foreach ($ACTIVITY_ARR as $key => $value) {
													//echo $value[$key]['iModID'];
													$selected = ($proposed_activity == $key) ? 'selected' : '';
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
										<b>Objective of the camp:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" name="camp_objective" id="camp_objective" value="<?php echo $proposed_objective; ?>" placeholder="" required <?php echo $readonly; ?>>
										</div>
									</div>

								</div>


								<br>

								<div class="row">


									<div class="col-3">
										<b>Camp Date:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="date" name="camp_date" value="<?php echo $event_date; ?>" class="form-control" id="camp_date" required <?php echo $readonly; ?>></div>
									</div>

								</div>







								<br>

								<div class="row">



									<div class="col-3">
										<b>Camp Location:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" id="" value="<?php echo $naf_proposed_venue; ?>" required></div>
									</div>

								</div>

								<br>


								<div class="row">


									<div class="col-3">
										<b>Camp Duration:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" name="camp_duration" value="<?php echo $camp_duration; ?>" id="camp_duration" placeholder="" required>
										</div>
									</div>

								</div>


								<br>






								<div class="row">


									<div class="col-3">
										<b>No. of HCP(s) involved:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" id="" value="<?php echo $proposed_hcp_number; ?>" required></div>
									</div>

								</div>


								<br>

								<div class="row">



									<div class="col-3">
										<b>Name of HCP(s):<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" id="" value="<?php echo $hcp_name; ?>" required></div>
									</div>

								</div>

								<br>

								<div class="row">


									<div class="col-3">
										<b>Specialty:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" value="<?php echo implode(",", $S_ARRA); ?>" placeholder="" readonly></div>
									</div>

								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>HCP Registration number:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" value="<?php echo $registration_number; ?>" name="registration_number" class="form-control" id="registration_number" required> </div>
									</div>

								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Hospital/Clinic name or address:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" value="<?php echo $hcp_associated_hospital_id; ?>" class="form-control" id="hospital_name" name="hospital_name" required></div>
									</div>

								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Type of Diagnostic test:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" id="type_of_diagnostic" name="type_of_diagnostic" value="<?php echo $type_of_diagnostic; ?>" required></div>
									</div>

								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Whether collaboration with Diagnostic Labs:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" id="collab" value="<?php echo $collaboration_with_diag; ?>" name="collab" required></div>
									</div>

								</div>



								<br>

								<div class="row">

									<div class="col-3">
										<b>Diagnostic charges, if any:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="number" value="<?php echo $diagnostic_charges; ?>" class="form-control" id="diagnostic_charges" name="diagnostic_charges" ></div>
									</div>

								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Medical equipment cost, if any:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="number" class="form-control" id="medical_equipment_cost" name="medical_equipment_cost" value="<?php echo $medical_cost; ?>" required></div>
									</div>

								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Total no. of individuals screened at the camp:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="number" value="<?php echo $total_no_ind; ?>" class="form-control" name="total_no_of_ind" id="total_no_of_ind" required></div>
									</div>

								</div>




							</div>



						</div>
					</div>
				</div>




				<div class="section mt-7">
					<div class="card">
						<div class="card-body">



							<div class="wide-block pt-2 pb-2">

								<b>HCP Feedback (to be completed by the HCP)</b>
								<br><br>
								<p>Please provide your feedback considering various aspects:</p>
								<br>

								<p><b>1) Was the camp well organized/arranged?<span style="color:#ff0000">*</span></b></p>

								<?php

								foreach ($CHECKBOXES as $key => $value) {
									# code...
									//echo '<div class="custom-control custom-radio mb-1">';
									$checked = ($camp_organised == $key) ? 'checked' : '';
									echo '<input type="radio" class=""  name="camp_organised" value="' . $key . '" ' . $checked . '> ' . $value . '<br>';
								}


								?>

								<!-- <div class="custom-control custom-radio mb-1">
									<input type="radio" id="excellent" value="1" name="camp_organised" class="custom-control-input">
									<label class="custom-control-label" for="excellent">Excellent</label>
								</div>
								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="good" value="2" name="camp_organised" class="custom-control-input">
									<label class="custom-control-label" for="good">Good</label>
								</div>
								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="average" value="3" name="camp_organised" class="custom-control-input">
									<label class="custom-control-label" for="average">Average</label>
								</div>
								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="poor" value="4" name="camp_organised" class="custom-control-input">
									<label class="custom-control-label" for="poor">Poor</label>
								</div> -->

							</div>



							<div class="wide-block pt-2 pb-2">

								<p><b>2) Was this camp well received i.e., attended by good audience?<span style="color:#ff0000">*</span></b></p>
								<?php
								foreach ($CHECKBOXES as $key => $value) {
									$checked = ($camp_received == $key) ? 'checked' : '';

									# code...
									//echo '<div class="custom-control custom-radio mb-1">';
									echo '<input type="radio" class="" name="camp_received" value="' . $key . '" ' . $checked . '> ' . $value . '<br>';
								}
								?>


								<!-- <div class="custom-control custom-radio mb-1">
									<input type="radio" id="customRadio1" value="1" name="camp_received" class="custom-control-input">
									<label class="custom-control-label" for="customRadio1">Excellent</label>
								</div>
								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="customRadio2" value="2" name="camp_received" class="custom-control-input">
									<label class="custom-control-label" for="customRadio2">Good</label>
								</div>
								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="customRadio3" value="3" name="camp_received" class="custom-control-input">
									<label class="custom-control-label" for="customRadio3">Average</label>
								</div>
								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="customRadio4" value="4" name="camp_received" class="custom-control-input">
									<label class="custom-control-label" for="customRadio4">Poor</label>
								</div> -->

							</div>



							<div class="wide-block pt-2 pb-2">

								<div class="form-group basic">
									<div class="input-wrapper">
										<label class="label" for="">HCP Comments/Suggestions (to be completed by HCP)</label>
										<textarea id="remarks" name="remarks" rows="3" class="form-control" required><?php echo $remarks; ?></textarea>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>





				<br>

				<br>
				<div class="row mlm-150">
					<div class="col-6">

						SIGNED AND DELIVERED by <b></b>, acting
						through its Authorised Signatory, Mr. <b><?php echo $hcp_name; ?></b>, the within
						named Party of the First Part.<br>
						<b>DATE:<?php echo $sign_date; ?></b>

					</div>
					<div class="col-6">


						<center>

							<div class="custom-signature-upload" style="<?php echo $sign_edit; ?>">
								<div class="wrapper">
									<canvas id="signature-pad"></canvas>

								</div>
								<div class="clear-btn">
									<button id="clear_sign"><span> Clear </span></button>
								</div>
							</div>

							<div class="custom-signature-upload" style="<?php echo $sign_display; ?>">
								<div class="wrapper1">
									<img src="<?php echo $hcp_sign; ?>" width="95%" height="95%" alt="Italian Trulli">
								</div>
							</div>

						</center>
					</div>
				</div>

				<div style="<?php echo $otp_verify_div; ?>">

					<div class="row mt-3" id="hideDiv" style="display:block;">
						<input type="hidden" id="questionID" name="questionID" value="1">
						<input type="hidden" name="user_name" id="user_name" value="<?php echo $user_name; ?>">

						<label for="otpmobilenumber" class="col-sm-3 col-form-label"><b>Verify Your Phone Number</b></label>
						<div class="col-sm-3">
							<input type="number" class="form-control" id="otpmobilenumber" name="otpmobilenumber" placeholder="" required="">

						</div>
						<div class="col-sm-3">
							<button type="button" class="btn btn-primary" onclick="createOTP()">Generate OTP</button>
						</div>
					</div>

					<div class="row mt-3" id="enterOtp_div" style="display:block;">
						<label for="enter_otp" class="col-sm-3 col-form-label"><b>Enter OTP</b></label>
						<div class="col-sm-3">
							<input type="number" class="form-control" id="enter_otp" name="enter_otp" placeholder="" required="">

						</div>
						<div class="col-sm-3">
							<button type="button" class="btn btn-primary" onclick="validateOTP()">Confirm</button>
						</div>
					</div>
				</div>






				<div class="section full mt-2">
					<div class="wide-block pt-2 pb-2">

						<div class="row">
							<!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
                    </div> -->
							<div class="col">
								<button type="submit" style="display: none;" id="submitbutton" class="exampleBox btn btn-primary rounded me-1">Submit</button>
								<!-- <a href="delivery_service_form_camp.php"><button type="button" class="exampleBox btn btn-primary rounded me-1">Submit</button></a> -->
							</div>
							<!-- <div class="col">
                        <a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
                    </div> -->
						</div>

					</div>
				</div>
			</div>

		</form>

	</div>




	<!-- ///////////// Js Files ////////////////////  -->
	<!-- Jquery -->
	<script src="assets/js/lib/jquery-3.4.1.min.js"></script>
	<!-- Bootstrap-->
	<script src="assets/js/lib/popper.min.js"></script>
	<script src="assets/js/lib/bootstrap.min.js"></script>
	<!-- Ionicons -->
	<script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
	<!-- Owl Carousel -->
	<script src="assets/js/plugins/owl-carousel/owl.carousel.min.js"></script>
	<!-- Base Js File -->
	<script src="assets/js/base.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.5/signature_pad.min.js" integrity="sha512-kw/nRM/BMR2XGArXnOoxKOO5VBHLdITAW00aG8qK4zBzcLVZ4nzg7/oYCaoiwc8U9zrnsO9UHqpyljJ8+iqYiQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		var canvas = document.getElementById("signature-pad");

		function resizeCanvas() {
			var ratio = Math.max(window.devicePixelRatio || 1, 1);
			canvas.width = canvas.offsetWidth * ratio;
			canvas.height = canvas.offsetHeight * ratio;
			canvas.getContext("2d").scale(ratio, ratio);

			// canvas1.width = canvas1.offsetWidth * ratio;
			// canvas1.height = canvas1.offsetHeight * ratio;
			// canvas1.getContext("2d").scale(ratio, ratio);
		}
		window.onresize = resizeCanvas;
		resizeCanvas();

		var signaturePad = new SignaturePad(canvas, {
			backgroundColor: 'rgb(250,250,250)'
		});

		document.getElementById("clear_sign").addEventListener('click', function() {
			signaturePad.clear();
		})

		function validate() {
			var canvas = document.getElementById("signature-pad");
			var data1 = canvas.toDataURL('image/png');
			document.getElementById('signature1').value = data1;

			// var canvas1 = document.getElementById("signature-pad1");
			// var data2 = canvas1.toDataURL('image/png');
			// document.getElementById('signature2').value = data2;
			return false;
		}


		$('#camp_report_form').submit(function() {
			var ret_val = true;
			validate();

			var camp_organised = $("input[name='camp_organised']:checked").val();
			var camp_received = $("input[name='camp_received']:checked").val();

			if (camp_organised === undefined) {
				alert("Please select a camp_organised option.");
				return false; // Stop further processing
				ret_val = false;
			}

			if (camp_received === undefined) {
				alert("Please select a camp_received option.");
				return false; // Stop further processing
				ret_val = false;
			}

			if (signaturePad.isEmpty()) {
				alert("Please provide a signature first.");
				return false;
				ret_val=false;
			}



			return ret_val;
		});


		function createOTP() {

			var questionid = $("#questionID").val();
			var mobileOTPnum = $("#otpmobilenumber").val();
			var username = $("#user_name").val();
			var rid = $("#rid").val();



			if (mobileOTPnum == '' || mobileOTPnum == 0) {
				//bootbox.dialog({message:'Please Enter Mobile Number', backdrop:false, onEscape:true});
				alert('Please Enter Mobile Number');
				//bootbox.dialog({message:'Please Enter Mobile Number', title:'<font style="font-size: 16px;color:red;"><b>Warning:<b></font>', backdrop:false, onEscape:true});			
			} else {
				jQuery.ajax({
					url: '../../index.php?module=Users&action=Authenticate',
					data: {
						user_name: '.' + username,
						user_password: 123456,
						view_questionnaire: 1,
						type: 14,
						question_requestid: rid,
						naf_request_id: rid,
						docmobile: '+91' + mobileOTPnum,
						plannedDoctor: true
					},
					success: function(response) {
						result = JSON.parse(response);
						//$("#shorturl").val(result.shourtURL);
						//$("#shortcodeID").val(result.shourtURLId);
						if (result.status) {
							alert(result.message);
							//bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:green;"><b>Success:<b></font>', backdrop:false, onEscape:true});
							//location.reload();
							$("#enterOtp_div").show();
						} else {
							alert(result.message);
							//bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:red;"><b>Warning:<b></font>', backdrop:false, onEscape:true});
							//$("#btnsubmit").val('Submit');
							//$("#btnsubmit").prop('disabled', false);
						}

					}
				});
			}
		}

		function validateOTP() {

			var questionid = $("#questionID").val();
			var enter_otp = $("#enter_otp").val();
			var username = $("#user_name").val();
			var rid = $("#rid").val();

			if (enter_otp == '' || enter_otp == 0) {
				//bootbox.dialog({message:'Please Enter Mobile Number', backdrop:false, onEscape:true});	
				alert('Please Enter Mobile Number');
				//bootbox.dialog({message:'Please Enter Mobile Number', title:'<font style="font-size: 16px;color:red;"><b>Warning:<b></font>', backdrop:false, onEscape:true});			
			} else {

				jQuery.ajax({
					url: '../../index.php?module=Users&action=Authenticate',
					data: {
						user_name: '.' + username,
						user_password: 123456,
						view_questionnaire: 1,
						type: 15,
						question_requestid: rid,
						mobileOTP: enter_otp,
						plannedDoctor: true
					},
					success: function(response) {
						result = JSON.parse(response);

						if (result.status) {
							alert(result.message);
							$("#submitbutton").show();
							//bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:green;"><b>Success:<b></font>', backdrop:false, onEscape:true});										
						} else {
							alert(result.message);
							//bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:red;"><b>Warning:Invalid OTP<b></font>', backdrop:false, onEscape:true});											
						}

					}
				});
			}
		}
	</script>

</body>

</html>