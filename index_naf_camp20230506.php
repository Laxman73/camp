<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/common.php";

$disp_url = 'index.php';


$page_title = 'Camp Activity';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$prid = (isset($_GET['prid'])) ? $_GET['prid'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$category = (isset($_GET['category'])) ? $_GET['category'] : '';

$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

$ADVANCE_PAYMENT_ARRAY = array('Yes' => 'Yes', 'No' => 'NO');

if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}

// if (empty($prid) || (!empty($prid) && !is_numeric($prid))) {
// 	echo 'Invalid Access Detected!!!';
// 	exit;
// }

if (empty($rid) || (!empty($rid) && !is_numeric($rid))) {
	$mode = 'A';
} else {
	$mode = 'E';
}

//if (isset($_GET['prid'])) $mode = 'A';

$HCP_DATA = $REQUEST_LETTER = array();


// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");



$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division
$SPECILITY_ARR = $CRM_FILEDS = $SELECTED_SPECIALITIES = $S_ARRA = array();

$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0', '3');

// $division = GetXFromYID("select division from users where id='$USER_ID' ");
$curr_dt = date('Y-m-d');
// $financial_qry = "SELECT financial_year FROM financialyear where '".$curr_dt."' between from_date and to_date";
// $financial_qry_res = sql_query($financial_qry);
$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");


$selectoption = $readonly = '';

$CITY_ARR = GetXArrFromYID("SELECT cityid as id, concat_ws('',cityname,if(city.metrononmetro=1,'(Metro)','(Non-Metro)')) as name FROM city where deleted=0 ORDER BY cityname", '3');

$cond = '';
if (!empty($User_division) && (isset($User_division))) {
	$cond .= " and division=$User_division ";
}


$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear'   " . $cond, '3');

$PRODUCTS_ARR = GetXArrFromYID("select productbrandtypeid as id ,productbrandtype as name from productbrandtype where presence=1 " . $cond . " order by productbrandtype ASC", '3');

$HCP_UNIVERSAL_ID = GetXArrFromYID("select contactid,masterid from contactdetails order by contactid LIMIT 100 ", "3");
$display_style = '';
if ($mode == 'A') {
	$initiator = '';
	$REQid = '';
	$emp_code = '';
	$date = '';
	$post_comment = '';
	$category_id = '';
	$submitted_on = '';
	$submitted = '';
	$pendingwithid = '';
	$authorise = '';
	$approved_date = '';
	$deleted = '';
	$deleted_on = '';
	$eventdate = TODAY;
	$level = '';
	$naf_no = '';
	$naf_activity_name = '';
	$naf_city = '';
	$naf_proposed_venue = '';
	$naf_estimate_no_participents = '';
	$naf_start_date = '';
	$naf_end_date = '';
	$naf_objective_rational = '';
	$remarks = '';
	$quarter = '';
	$nafmode = '';
	$proposed_activity_count = '';
	$proposed_hcp_no = '';
	$proposed_activity = '';
	$proposed_objective = '';
	$rationale_remark = '';
	$lead_event = '';
	$medical_equipments = '';
	$deviation_amount = '';
	$parent_id = '';
	$event_benefit_society = '';
	$budget_amount = '';
	$role_of_advisory = '';
	$doc_upload_path = '';
	$status = '';
	$advance_payment = '';
	$productID = '';
	$x_total = '';
} elseif ($mode == 'E') {
	$display_style = 'display:none';
	$DATA = GetDataFromID('crm_naf_main', 'id', $rid, "and deleted=0 and category_id=5 ");
	if (empty($DATA)) {
		header('location: ' . $disp_url . '?userid=' . $USER_ID);
		exit;
	}
	$initiator = db_output2($DATA[0]->initiator);
	$REQid = db_output2($DATA[0]->userid);
	$emp_code = db_output2($DATA[0]->emp_code);
	$date = db_output2($DATA[0]->date);
	$post_comment = db_output2($DATA[0]->post_comment);
	$category_id = db_output2($DATA[0]->category_id);
	$submitted_on = db_output2($DATA[0]->submitted_on);
	$submitted = db_output2($DATA[0]->submitted);
	$pendingwithid = db_output2($DATA[0]->pendingwithid);
	$authorise = db_output2($DATA[0]->authorise);
	$approved_date = db_output2($DATA[0]->approved_date);
	$deleted = db_output2($DATA[0]->deleted);
	$deleted_on = db_output2($DATA[0]->deleted_on);
	$eventdate = db_output2($DATA[0]->eventdate);
	$level = db_output2($DATA[0]->level);
	$naf_no = db_output2($DATA[0]->naf_no);
	$naf_activity_name = db_output2($DATA[0]->naf_activity_name);
	$naf_city = db_output2($DATA[0]->naf_city);
	$naf_proposed_venue = db_output2($DATA[0]->naf_proposed_venue);
	$naf_estimate_no_participents = db_output2($DATA[0]->naf_estimate_no_participents);
	$naf_start_date = db_output2($DATA[0]->naf_start_date);
	$naf_end_date = db_output2($DATA[0]->naf_end_date);
	$naf_objective_rational = db_output2($DATA[0]->naf_objective_rational);
	$remarks = db_output2($DATA[0]->remarks);
	$quarter = db_output2($DATA[0]->quarter);
	$nafmode = db_output2($DATA[0]->mode);
	$proposed_activity_count = db_output2($DATA[0]->proposed_activity_count);
	$proposed_hcp_no = db_output2($DATA[0]->proposed_hcp_no);
	$proposed_activity = db_output2($DATA[0]->proposed_activity);
	$proposed_objective = db_output2($DATA[0]->proposed_objective);
	$rationale_remark = db_output2($DATA[0]->rationale_remark);
	$lead_event = db_output2($DATA[0]->lead_event);
	$medical_equipments = db_output2($DATA[0]->medical_equipments);
	$deviation_amount = db_output2($DATA[0]->deviation_amount);
	$parent_id = db_output2($DATA[0]->parent_id);
	$event_benefit_society = db_output2($DATA[0]->event_benefit_society);
	$budget_amount = db_output2($DATA[0]->budget_amount);
	$role_of_advisory = db_output2($DATA[0]->role_of_advisory);
	$doc_upload_path = db_output2($DATA[0]->doc_upload_path);
	$status = db_output2($DATA[0]->status);
	$advance_payment = db_output2($DATA[0]->advance_payment);
	$productID = GetXFromYID("select product_id from crm_naf_product_details where naf_request_id='$rid' ");

	$curr_dt = date('Y-m-d', strtotime($submitted_on));
	// $financial_qry = "SELECT financial_year FROM financialyear where '".$curr_dt."' between from_date and to_date";
	// $financial_qry_res = sql_query($financial_qry);
	$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");

	$User_division = GetXFromYID("select division from users where id='$REQid' "); //Getting division


	$selectoption = $readonly = 'readonly';

	$cond = '';
	if (!empty($User_division) && (isset($User_division))) {
		$cond .= " and division=$User_division ";
	}


	$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear'   " . $cond, '3');

	$PRODUCTS_ARR = GetXArrFromYID("select productbrandtypeid as id ,productbrandtype as name from productbrandtype where presence=1 " . $cond . " order by productbrandtype ASC", '3');


	$_sp_q = "select speciality_id from crm_naf_speciality_details where naf_request_id='$rid' and deleted=0  ";
	$SELECTED_SPECIALITIES = array(); //selected speciality ids
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


	$_crm_field_q = "select naf_field_id,naf_expense from crm_naf_cost_details where naf_request_id='$rid' ";
	$_crm_field_r = sql_query($_crm_field_q, '');

	while (list($naf_field_id, $naf_expense) = sql_fetch_row($_crm_field_r)) {
		if (!isset($CRM_FILEDS[$naf_field_id]))
			$CRM_FILEDS[$naf_field_id] = $naf_expense;
	}

	$x_total = GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$rid' "); //total amount

	$HCP_DATA = GetDataFromID('crm_naf_hcp_details', 'naf_main_id', $rid, "and deleted=0 ");

	$hcp_details_ID = GetXFromYID("select id from crm_naf_hcp_details where naf_main_id='$rid' and deleted=0 ");

	$REQUEST_LETTER = GetDataFromID('crm_naf_camp_letter', 'crm_naf_hcp_details_id', $hcp_details_ID);


	//DFA($S_ARRA);
}




// $_crm_field_q = "select naf_field_id,naf_expense from crm_naf_cost_details where naf_request_id='$id' ";
// $_crm_field_r = sql_query($_crm_field_q, '');

// while (list($naf_field_id, $naf_expense) = sql_fetch_row($_crm_field_r)) {
// 	if (!isset($CRM_FILEDS[$naf_field_id]))
// 		$CRM_FILEDS[$naf_field_id] = $naf_expense;
// }
//DFA($CRM_FILEDS);



$_q = "select field_id,field_name from crm_naf_fields_master where deleted=0 AND typeid=6 "; //fetch typeid from the category id passed in url
$_r = sql_query($_q, "");

?>
<!doctype html>
<html lang="en">

<head>
	<?php include 'load.header.php'; ?>
</head>

<body>


	<div class="appHeader bg-primary">
		<div class="left">
			<a href="javascript:;" class="headerButton goBack">
				<ion-icon name="chevron-back-outline"></ion-icon>
			</a>
		</div>
		<div class="pageTitle camp">Need Assessment Form(By Activity Requestor)</div>
		<div class="right"></div>
	</div>


	<?php include '_tabscamp.php'; ?>


	<div id="appCapsule">

		<form action="save_nafActivity.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
			<input type="hidden" name="prid" value="<?php echo $prid; ?>">
			<input type="hidden" name="category" value="<?php echo $category; ?>">
			<input type="hidden" name="rid" value="<?php echo $rid; ?>">
			<input type="hidden" name="selected_hcp" id="selected_hcp" value="">
			<input type="hidden" name="selected_request_letter" id="selected_request_letter" value="">

			<div class="tab-content mt-1">



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
											<input type="text" class="form-control" name="reqDate" id="reqDate" value="<?php echo $eventdate; ?>" readonly>
										</div>

									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Product:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<select class="form-control custom-select" id="Product" name="productID" required="" <?php echo $selectoption; ?>>
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
										<b>Event ID No<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" value="<?php echo $naf_no; ?>" id="eventID" name="eventID" placeholder="" readonly>
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
											<input type="text" class="form-control" name="activty_name" value="<?php echo $naf_activity_name; ?>" id="activty_name" required="">
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
										<b>Date of the Activity:</b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="date" class="form-control" min="<?php echo TODAY; ?>" required="" value="<?php echo TODAY; ?>" id="activity_Date" name="activity_Date">
										</div>

									</div>
								</div>

								<br>


								<div class="row">

									<div class="col-3">
										<b>City(Metro and Non Metro):<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<select class="form-control custom-select" id="city" name="city" <?php echo $selectoption; ?>>
												<option selected="" value="">Choose...</option>
												<?php
												foreach ($CITY_ARR as $key => $value) {
													//echo $value[$key]['iModID'];
													$selected = ($naf_city == $key) ? 'selected' : '';
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
										<b>Proposed Venue:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="venue" value="<?php echo $naf_proposed_venue; ?>" name="venue" placeholder="" required="">
										</div>
									</div>
								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Estimated Number of Participants:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="no_of_p" name="no_of_p" value="<?php echo $naf_estimate_no_participents; ?>" placeholder="" required="">
										</div>
									</div>
								</div>



								<br>

								<div class="row">

									<div class="col-3">
										<b>Targeted Speciality:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<?php

											if ($mode == 'A') {
												echo '<select class="form-control custom-select" name="targetedSpeciality[]" multiple id="targetedSpeciality">';
												foreach ($SPECILITY_ARR as $key => $value) {
													echo '<option value="' . $key . '" >' . $value . '</option>';
												}
												echo '</select>';
											} elseif ($mode == 'E') { ?>
												<input type="text" class="form-control" value="<?php echo implode(",", $S_ARRA); ?>" placeholder="" required="" <?php echo $readonly; ?>>
											<?php }
											?>
										</div>
									</div>
								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>The objective/rationale/need for conducting an Activity:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<textarea id="objective" name="objective" rows="3" class="form-control"><?php echo $proposed_objective; ?></textarea>
										</div>
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


								<br>
								<?php

								while ($row = sql_fetch_assoc($_r)) {
									//DFA($row);
									$compulsory = ($row['field_id'] == '1') ? 'required="" ' : '';
									$compulsoryfield = ($row['field_id'] == '1') ? '<span style="color:#ff0000">*</span>' : '';
									$value = (isset($CRM_FILEDS[$row['field_id']])) ? $CRM_FILEDS[$row['field_id']] : '';
								?>


									<div class="row">

										<div class="col-3">
											<b><?php echo $row['field_name'] . ':'; ?><?php echo $compulsoryfield; ?></span></b>
										</div>

										<div class="col-9">
											<div class="input-wrapper">
												<input type="number" value="<?php echo $value; ?>" td_id="<?php $row['field_id']; ?>" onkeypress="CalculateTotal();" onkeyup="CalculateTotal();" onblur="CalculateTotal();" name="crm_<?php echo $row['field_id']; ?>" class="form-control amountCalculate" id="crm_<?php echo $row['field_id']; ?>" placeholder="" <?php echo $readonly; ?> <?php echo $compulsory; ?>>
											</div>
										</div>
									</div>
									<br>



								<?php }	?>

								<!-- 
							<div class="row">

								<div class="col-3">
									<b>HCP Honorrium (fill 'Appendix-1' with HCP details):<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Local Travel:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>


							<br>

							<div class="row">

								<div class="col-3">
									<b>Accommodation / Stay:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Flight:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Meal / Snack:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>


							<br>

							<div class="row">

								<div class="col-3">
									<b>Venue charges</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Audio Visual / Webex:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Banners / Pamphlets:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>


							<br>

							<div class="row">

								<div class="col-3">
									<b>Sponsorship:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>


							<br>

							<div class="row">

								<div class="col-3">
									<b>Grants:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Diagnostic cost:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Medical equipment cost:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Other additional cost, if any:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div> -->

								<br>

								<div class="row">

									<div class="col-3">
										<b>TOTAL AMOUNT:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" id="total" value="<?php echo $x_total; ?>" name="total" placeholder="" required="" readonly>
										</div>
									</div>

								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Is this an Advance Payment? If yes,please mention the advance amount:</b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<?php

											if ($mode == 'E') { ?>
												<div id="advance_SRC"><img src="<?php echo $doc_upload_path; ?>" alt="PAN Image" style="width:30%;height:auto;"></div>

											<?php	} elseif ($mode == 'A') { ?>
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


								<div id="showYes" class="myDiv">
									
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



							</div>


						</div>
					</div>
				</div>






				<div class="section mt-2">
					<div class="card">
						<div class="card-body pd-1">

							<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addhcp" style="<?php echo $display_style; ?>">ADD</button>


							<table id="example" class="display mt-2" style="width:100%">

								<thead>

									<tr>
										<th></th>
										<th>Sr. No</th>
										<th>HCP Universal ID</th>
										<th>Name of the HCP</th>
										<th>Address(city)</th>
										<th>Honrarium Amount</th>
										<th>Role of HCP</th>
										<th>Mobile Number</th>
										<th>PAN #</th>
										<th>Qualification</th>
										<th>Associated Hospital/Clinic</th>
										<th>Govt.(Yes/No)</th>
										<th>Years of experience</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="append_data">
									<?php
									if (!empty($HCP_DATA)) {
										for ($i = 0; $i < count($HCP_DATA); $i++) {
											$k = $i + 1;
											$hcp_id = $HCP_DATA[$i]->hcp_id;
											$masterid = GetXFromYID("select masterid from contactdetails where contactid='$hcp_id' ");
											$hcp_name = GetXFromYID("SELECT CONCAT(firstname, ' ', lastname) AS full_name FROM contactdetails where contactid='$hcp_id' ");
											$hcp_address = $HCP_DATA[$i]->hcp_address;
											$hcp_pan = $HCP_DATA[$i]->hcp_pan;
											$hcp_qualification = $HCP_DATA[$i]->hcp_qualification;
											$hcp_associated_hospital_id = $HCP_DATA[$i]->hcp_associated_hospital_id;
											$govt_type = $HCP_DATA[$i]->govt_type;
											$yr_of_experience = $HCP_DATA[$i]->yr_of_experience;
											$role_of_hcp = $HCP_DATA[$i]->role_of_hcp;
											$honorarium_amount = $HCP_DATA[$i]->honorarium_amount;
											$mobile = $HCP_DATA[$i]->mobile;
									?>
											<tr>
												<td></td>
												<td><?php echo $k; ?></td>
												<td><?php echo $masterid; ?></td>
												<td><?php echo $hcp_name; ?></td>
												<td><?php echo $hcp_address; ?></td>
												<td><?php echo $honorarium_amount; ?></td>
												<td><?php echo $role_of_hcp; ?></td>
												<td><?php echo $mobile; ?></td>
												<td><?php echo $hcp_pan; ?></td>
												<td><?php echo $hcp_qualification; ?></td>
												<td><?php echo $hcp_associated_hospital_id; ?></td>
												<td><?php echo $govt_type; ?></td>
												<td><?php echo $yr_of_experience; ?></td>
												<td></td>

											</tr>


									<?php	}
									}
									?>




								</tbody>
							</table>


							<br><br><br><br>

							<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addrequestletterMODAL" style="<?php echo $display_style; ?>">ADD</button>
							<table id="example1" class="display" style="width:100%">

								<thead>

									<tr>
										<th></th>
										<th>Sr. No</th>
										<th>Nature of Camps</th>
										<th>Proposed Date</th>
										<th>Venue</th>
										<th>Estimated Cost, if any</th>
										<th>Diagnostic labs collaboration</th>
									</tr>
								</thead>
								<tbody id="addrequestletter">
									<?php
									if (!empty($REQUEST_LETTER)) {
										for ($i = 0; $i < count($REQUEST_LETTER); $i++) {
											$k = $i + 1;
											$nature_of_camp = $REQUEST_LETTER[$i]->nature_of_camp;
											$proposed_camp_date = $REQUEST_LETTER[$i]->proposed_camp_date;
											$proposed_camp_location = $REQUEST_LETTER[$i]->proposed_camp_location;
											$estimated_cost = $REQUEST_LETTER[$i]->estimated_cost;
											$diagnostic_lab = $REQUEST_LETTER[$i]->diagnostic_lab; ?>
											<tr>
												<td></td>
												<td><?php echo $k; ?></td>
												<td><?php echo $nature_of_camp; ?></td>
												<td><?php echo $proposed_camp_date; ?></td>
												<td><?php echo $proposed_camp_location; ?></td>
												<td><?php echo $estimated_cost; ?></td>
												<td><?php echo $diagnostic_lab; ?></td>
											</tr>


									<?php	}
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
									<textarea id="rationale" name="rationale" rows="3" class="form-control"><?php echo $naf_objective_rational; ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>




				<div class="section mt-2">
					<div class="card">
						<div class="card-body">





						</div>
					</div>
				</div>




				<div class="section full mt-2">
					<div class="wide-block pt-2 pb-2">

						<div class="row">
							<!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
						</div> -->
							<div class="col">
								<button type="submit" class="exampleBox btn btn-primary rounded me-1" style="<?php echo $display_style; ?>">Submit</button>
							</div>
							<!-- <div class="col">
							<a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
						</div> -->
						</div>

					</div>
				</div>







			</div>
		</form>




		<!-- Content Action Sheet -->
		<div class="modal fade action-sheet" id="addhcp" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add HCP</h5>
					</div>
					<div class="modal-body">
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
										<input type="text" name="pan" class="form-control" id="pan">
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

						</div>
						<div class="row">
							<div class="float-left py-5">
								<button type="button" class="exampleBox btn btn-primary rounded me-1" onclick="addDoctor();">Add HCP</button>

							</div>
							<div class="float-right py-5">
								<a href="#" class="btn btn-secondary btn-block btn-lg" data-dismiss="modal">Close</a>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade action-sheet" id="addrequestletterMODAL" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add Request Letter</h5>
					</div>
					<div class="modal-body">
						<div class="wide-block pt-2 pb-2">




							<div class="row">

								<div class="col-3">
									<b>Nature of Camps:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="nature_of_camp" name="nature_of_camp">
									</div>
								</div>
							</div>

							<br>


							<div class="row">

								<div class="col-3">
									<b>Proposed camp date:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="date" name="proposed_camp_date" class="form-control" id="proposed_camp_date" placeholder="">
									</div>
								</div>
							</div>



							<br>
							<div class="row">

								<div class="col-3">
									<b>Proposed camp location:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" name="proposed_camp_location" class="form-control" id="proposed_camp_location" placeholder="">
									</div>
								</div>
							</div>



							<br>
							<div class="row">

								<div class="col-3">
									<b>Estimated cost:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" name="estimated_cost" class="form-control" id="estimated_cost" placeholder="">
									</div>
								</div>
							</div>



							<br>

							<div class="row">

								<div class="col-3">
									<b>Diagnostic lab:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" name="diagnostic_lab" class="form-control" id="diagnostic_lab" placeholder="">
									</div>
								</div>
							</div>

							<br>

						</div>
						<div class="row">
							<div class="float-left py-5">
								<button type="button" class="exampleBox btn btn-primary rounded me-1" onclick="addRequestLetter();">Add </button>

							</div>
							<div class="float-right py-5">
								<a href="#" class="btn btn-secondary btn-block btn-lg" data-dismiss="modal">Close</a>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- <button class="exampleBox btn btn-primary rounded me-1 btn-open" <?php echo $selectoption; ?>>ADD</button> -->

		<!-- * Content Action Sheet -->
	</div>


	<script>
		jQuery('#targetedSpeciality').multiselect({
			columns: 1,
			placeholder: 'Choose...',
			search: true
		});
	</script>



	<script>
		$(document).ready(function() {
			$('#myselection').on('change', function() {
				var demovalue = $(this).val();
				$("div.myDiv").hide();
				$("#show" + demovalue).show();
			});
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
		function chkothers() {
			var x = document.getElementById("Product").value;
			//alert(x);
			if (x == 0) {
				document.getElementById("others").style.display = '';
			} else {
				document.getElementById("others").style.display = 'none';
			}
		}

		var addeddocarray = [];
		var added_request_letter = [];
		var selecteddoctorarray = [];

		function addRequestLetter() {
			var nature_of_camp = $('#nature_of_camp').val();
			var proposed_camp_date = $('#proposed_camp_date').val();
			var proposed_camp_location = $('#proposed_camp_location').val();
			var estimated_cost = $('#estimated_cost').val();
			var diagnostic_lab = $('#diagnostic_lab').val();
			var saveDataObj = {};

			saveDataObj = {
				'nature_of_camp': nature_of_camp,
				'proposed_camp_date': proposed_camp_date,
				'proposed_camp_location': proposed_camp_location,
				'estimated_cost': estimated_cost,
				'diagnostic_lab': diagnostic_lab
			}

			added_request_letter.push(saveDataObj);
			closeRequestletter();
			selected_request_letter();


		}

		function addDoctor() {
			var DoctorID = document.getElementById("DoctorID").value;
			var hcp_name = document.getElementById("hcp_name").value;
			var address = document.getElementById("address").value;
			var qualification = document.getElementById("qualification").value;
			var associated_hospital = document.getElementById("associated_hospital").value;
			var hcp_mobile = document.getElementById("hcp_mobile").value;
			var pan = document.getElementById("pan").value;
			var govt = document.getElementById("govt").value;
			var year_of_experience = document.getElementById("year_of_experience").value;
			var honorarium = document.getElementById("Honorarium").value;
			var role_of_hcp = document.getElementById("role_of_hcp").value;


			var DoctorID_value = $("#DoctorID option:selected").text();
			var DoctorID_passed = $('#DoctorID').val();
			var hcp_name_passed = $('#hcp_name').val();
			var address_passed = $('#address').val();
			var qualification_passed = $('#qualification').val();
			var associated_hospital_passed = $('#associated_hospital').val();
			var pan_passed = $('#pan').val();
			var hcp_mobile_passed = $('#hcp_mobile').val();

			if (honorarium < 1) {
				alert("Honorarium amount should be greater than zero!!");
				return false;
			}

			if (jQuery.trim(DoctorID_passed).length == 0) {
				alert("Please select HCP Universal ID !");
				return false;
			}
			if (jQuery.trim(address_passed).length == 0) {
				alert("Please enter Address !");
				return false;
			}

			if (jQuery.trim(hcp_mobile_passed).length == 0) {
				alert("Please enter Mobile Number !");
				return false;
			}

			if (jQuery.trim(hcp_mobile_passed).length != 10) {
				alert("Please enter valid Mobile Number !");
				return false;
			}

			if (jQuery.trim(pan_passed).length == 0) {
				alert("Please enter PAN !");
				return false;
			}

			if (jQuery.trim(qualification_passed).length == 0) {
				alert("Please enter Qualification !");
				return false;
			}

			if (jQuery.trim(associated_hospital_passed).length == 0) {
				alert("Please enter Associated Hospital !");
				return false;
			}

			if (jQuery.trim(govt).length == 0) {
				alert("Please select Govt(Yes/No) !");
				return false;
			}
			if (jQuery.trim(honorarium).length == 0) {
				alert("Please enter honorarium amount !");
				return false;
			}
			if (jQuery.trim(role_of_hcp).length == 0) {
				alert("Please enter role of hcp !");
				return false;
			}
			if (jQuery.trim(year_of_experience).length == 0) {
				alert("Please enter Year Of Experience !");
				return false;
			}





			if (DoctorID == "" || address == "" || pan == "" || hcp_mobile == "" || govt == "" || year_of_experience == "") {
				alert("Please select all Parameters, marked with * !");
				return false;
			}

			if (addeddocarray.length > 20) {
				alert(" Maximum of 20 HCP can be added to the list. Kindly adjust the HCP  List");
				error = 1;
				return false;
			}

			if (addeddocarray.length == $('#Num_of_Participants').val()) {
				alert("HCP cannot be more than the Number of Participants");
				error = 1;
				return false;
			}

			for (var i = 0; i < addeddocarray.length; i++) {
				if (DoctorID == addeddocarray[i].doctorid) {
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

			saveDataObj = {
				'doctorid': DoctorID,
				'doctorid_value': DoctorID_value,
				'hcp_name': hcp_name,
				'address': address,
				'qualification': qualification,
				'associated_hospital': associated_hospital,
				'pan': pan,
				'hcp_mobile': hcp_mobile,
				'govt': govt,
				'honorarium_amount': honorarium,
				'role_of_hcp': role_of_hcp,
				'year_of_experience': year_of_experience
			}


			addeddocarray.push(saveDataObj);


			closeModal();
			selected_doctors();
		}

		const modal = document.querySelector(".modal");
		const overlay = document.querySelector(".overlay");
		const openModalBtn = document.querySelector(".btn-open");
		const closeModalBtn = document.querySelector(".btn-close");

		// close modal function
		const closeModal = function() {
			$('#addhcp').modal('hide');
		};

		// close modal function
		const closeRequestletter = function() {
			$('#addrequestletterMODAL').modal('hide');
		};

		// close the modal when the close button and overlay is clicked
		// closeModalBtn.addEventListener("click", closeModal);
		// overlay.addEventListener("click", closeModal);

		// close modal when the Esc key is pressed
		// document.addEventListener("keydown", function(e) {
		// 	if (e.key === "Escape" && !modal.classList.contains("hidden")) {
		// 		closeModal();
		// 	}
		// });

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

		function selected_doctors() {
			if (addeddocarray.length > 0) {
				var m = 1;
				var htmltable = '';
				for (var k = 0; k < addeddocarray.length; k++) {
					htmltable += '<tr>';
					htmltable += '<th></th><td>' + m + '</td><td>' + addeddocarray[k].doctorid_value + '</td><td>' + addeddocarray[k].hcp_name + '</td><td>' + addeddocarray[k].address + '</td><td>' + addeddocarray[k].honorarium_amount + '</td><td>' + addeddocarray[k].role_of_hcp + '</td><td>' + addeddocarray[k].hcp_mobile + '</td><td>' + addeddocarray[k].pan + '</td><td>' + addeddocarray[k].qualification + '</td><td>' + addeddocarray[k].associated_hospital + '</td><td>' + addeddocarray[k].govt + '</td><td>' + addeddocarray[k].year_of_experience + '</td>';
					htmltable += '<td><button type="button" onclick="remove(' + k + ')" id="del_link">Delete</button></td>';
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

		function selected_request_letter() {
			if (added_request_letter.length > 0) {
				var m = 1;
				var htmltable = '';
				for (var k = 0; k < added_request_letter.length; k++) {
					htmltable += '<tr>';
					htmltable += '<th></th><td>' + m + '</td><td>' + added_request_letter[k].nature_of_camp + '</td><td>' + added_request_letter[k].proposed_camp_date + '</td><td>' + added_request_letter[k].proposed_camp_location + '</td><td>' + added_request_letter[k].estimated_cost + '</td><td>' + added_request_letter[k].diagnostic_lab + '</td>';
					htmltable += '<td><button type="button" onclick="removeRq(' + k + ')" id="del_link">Delete</button></td>';
					htmltable += '</tr>';
					m++;
				}
				$("#addrequestletter").empty();
				$("#addrequestletter").append(htmltable);

			}
			$('#selected_request_letter').val(JSON.stringify(added_request_letter)); //store array
			var value = $('#selected_request_letter').val(); //retrieve arra
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


		$(document).on('click', '#del_link', function() {
			$(this).closest('tr').remove();
			return false;
		});

		function remove(element) {
			addeddocarray.splice(element, 1);
			selected_doctors();
		}

		function removeRq(element) {
			added_request_letter.splice(element, 1);
			selected_request_letter();
		}


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

		function CalculateTotal() {
			var total = 0;
			$('.amountCalculate').each(function() {
				var numbers = $(this).val();

				if ((numbers != '')) {

					total += parseFloat(numbers);
				}

			});

			//console.log(parseInt(total));
			$('#total').val(total);
		}

		$(document).ready(function() {
			var table = $('#example').DataTable({
				rowReorder: {
					selector: 'td:nth-child(2)'
				},
				responsive: true
			});
		});
	</script>

	<script>
		$(document).ready(function() {
			var table = $('#example1').DataTable({
				responsive: true
			});
		});
	</script>

</body>

</html>