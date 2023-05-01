<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/common.php";

$disp_url = 'index_naf_qtr.php';

$page_title = 'Camp Quarter';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$prid = (isset($_GET['prid'])) ? $_GET['prid'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}
// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");


//$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division
$SPECILITY_ARR = $SELECTED_SPECIALITIES = $S_ARRA = array();

$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0', '3');

$division = GetXFromYID("select division from users where id='$USER_ID' ");
$curr_dt = date('Y-m-d');

$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");

$cond = '';
if (!empty($division) && (isset($division))) {
	$cond .= " and division=$division ";
}

$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear'  ".$cond, '3');
$QUARTER_ARR = GetXArrFromYID("select quarterid,quarter_name from crm_naf_quarter_master ", '3');


// $mode = 'A';

if (empty($prid) || (!empty($prid) && !is_numeric($prid))) {
	$mode = 'A';
}else{
	$mode='E';
}


// if (isset($_GET['rid'])) $mode = 'E';

if ($mode == 'A') {
	$readonly = $selectoption = '';
	$initiator = '';
	$userid = '';
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
	$deviation_amount = '0';
	$budget_amount = '0';
	$event_benefit_society = '';
} elseif ($mode == 'E') {
	$DATA = GetDataFromID('crm_naf_main', 'id', $prid, "and deleted=0 ");
	if (empty($DATA)) {
		header('location: ' . $disp_url . '?userid=' . $USER_ID);
		exit;
	}
	$readonly = 'readonly';
	$selectoption = '';
	$initiator = db_output2($DATA[0]->initiator);
	$requestorID = db_output2($DATA[0]->userid);
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
	$budget_amount = db_output2($DATA[0]->budget_amount);
	$event_benefit_society = db_output2($DATA[0]->event_benefit_society);


	$curr_dt = date('Y-m-d', strtotime($submitted_on));
	$division = GetXFromYID("select division from users where id='$requestorID' ");

	$cond = '';
	if (!empty($division) && (isset($division))) {
		$cond .= " and division=$division ";
	}

	$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");

	$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear' ".$cond, '3');
	$QUARTER_ARR = GetXArrFromYID("select quarterid,quarter_name from crm_naf_quarter_master ", '3');


	$_sp_q = "select speciality_id from crm_naf_speciality_details where naf_request_id='$prid' ";
	//$SELECTED_SPECIALITIES = array();
	$_sq_q_r = sql_query($_sp_q, '');
	while (list($specialityid) = sql_fetch_row($_sq_q_r)) {
		if (!isset($SELECTED_SPECIALITIES[$specialityid]))
			$SELECTED_SPECIALITIES[$specialityid] =  $specialityid;
	}


	//$S_ARRA = array();
	// DFA($SELECTED_SPECIALITIES);
	// DFA($SPECILITY_ARR);
	foreach ($SPECILITY_ARR as $key => $value) {
		if (isset($SELECTED_SPECIALITIES[$key])) {
			# code...
			if ($SELECTED_SPECIALITIES[$key] == $key) {
				array_push($S_ARRA, $value);
			}
		}
	}

	//DFA($S_ARRA);


}

// $financial_qry = "SELECT financial_year FROM financialyear where '".$curr_dt."' between from_date and to_date";
// $financial_qry_res = sql_query($financial_qry);




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
		<div class="pageTitle">Quarterly Need Assessment Form</div>
		<div class="right"></div>
	</div>



	<?php include '_tabscamp.php'; ?>
	<!--<div class="col-2">
                        <a href="#"><div class="tabBox">NAF Form</div></a>
                    </div>
                    <div class="col-2">
						<a href="hcp_form.html"><div class="tabBox">HCP Information Form</div></a>
                    </div>
                    <div class="col-2">
                        <a href="hcp_agreement.html"><div class="tabBox">HCP Agreement</div></a>
                    </div>
                    <div class="col-2">
                        <a href="questionnaire.html"><div class="tabBox">Questionnaire</div></a>
                    </div>
                    <div class="col-2">
                        <a href="document_upload.html"><div class="tabBox">Documents upload</div></a>
                    </div>
                    <div class="col-2">
                        <a href="generate_pdf.html"><div class="tabBox">Generate PDF</div></a>
                    </div>-->



	<div id="appCapsule">

		<form action="save_nafQtr.php" method="post">
			<input type="hidden" name="userid" id="userid" value="<?php echo $USER_ID; ?>">
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
										<b>Quarter:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<select class="form-control custom-select" name="Quarter" id="Quarter">
												<?php
												foreach ($QUARTER_ARR as $key => $value) {
													//echo $value[$key]['iModID'];
													$selected = ($quarter == $key) ? 'selected' : '';
													echo '<option value="' . $key . '" ' . $selected . ' >' . $value . '</option>';
												}
												?>

											</select>
										</div>
									</div>

								</div>



								<br>

								<!--<div class="row">
							
								<div class="col-3">
									<b>Name of the Activity:<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>
							
							<br>-->


								<div class="row">

									<div class="col-3">
										<b>Name of activity:</b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<div class="custom-control " style="padding-left: 0px;">
												<input type="text" class="form-control pd-0" value="<?php echo $naf_activity_name; ?>"  name="activty_name" id="activty_name"  required>
											</div>


										</div>
									</div>

								</div>


								<br>

								<!-- <div class="row">

								<div class="col-3">
									<b>Please tick the appropriate box:</b>
								</div>
								<div class="col-9">
									<div class="input-wrapper">


										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio-1" name="customRadio1" class="custom-control-input">
											<label class="custom-control-label" for="customRadio-1">Continuous Medical Education Program</label>
										</div>
										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio-2" name="customRadio1" class="custom-control-input">
											<label class="custom-control-label" for="customRadio-2">Speakership Program</label>
										</div>
										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio-3" name="customRadio1" class="custom-control-input">
											<label class="custom-control-label" for="customRadio-3">Consulting Services</label>
										</div>
										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio-4" name="customRadio1" class="custom-control-input">
											<label class="custom-control-label" for="customRadio-4">Others, please specify</label>
										</div>


										<input type="text" class="form-control" id="" placeholder="" required="">


									</div>
								</div>

							</div> -->


								<br>


								<div class="row">

									<div class="col-3">
										<b>Proposed Count of Activities:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" name="proposed_count" class="form-control" id="proposed_count" value="<?php echo $proposed_activity_count; ?>" placeholder="" required="">
										</div>
									</div>
								</div>


								<br>


								<div class="row">

									<div class="col-3">
										<b>Proposed Number of HCP's:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" name="number_of_HCP" id="number_of_HCP" value="<?php echo $proposed_hcp_no; ?>" placeholder="" required="">
										</div>
									</div>
								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Proposed Nature of the Activity:<span style="color:#ff0000">*</span></b>
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


								<!--<br>
							
							<div class="row">
							
								<div class="col-3">
									<b>Proposed Targeted Speciality:<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<select class="form-control custom-select" id="" required="">
											<option selected="" disabled="" value="">Choose...</option>
											<option value="1">Option 1</option>
											<option value="2">Option 2</option>
											<option value="3">Option 3</option>
											<option value="4">Option 4</option>
											<option value="5">Option 5</option>
											<option value="6">Option 6</option>
										</select>
									</div>
								</div>
							</div>-->



								<br>


								<div class="row">

									<div class="col-3">
										<b>Estimated Number of Participants:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" id="number_of_participants" value="<?php echo $naf_estimate_no_participents; ?>" name="number_of_participants" placeholder="" required="">
										</div>
									</div>
								</div>


								<br>
								<div class="row">

									<div class="col-3">
										<b>Budgeted Amount in the Quarter:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" id="budget_amt" value="<?php echo $budget_amount; ?>" name="budget_amt" placeholder="" required="">
										</div>
									</div>
								</div>

								<br>
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
										<b>The proposed objective/rationale/need for an Activity:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" name="proposed_obj" value="<?php echo $proposed_objective; ?>" id="proposed_obj" placeholder="" required="">
										</div>
									</div>
								</div>


								<br>
								<div class="row">

									<div class="col-3">
										<b>Rationale for selection of a particular Class of HCPs in comparison to other HCP’s:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" value="<?php echo $naf_objective_rational; ?>" id="rationale" name="rationale" placeholder="" required="">
										</div>
									</div>
								</div>


								<br>
								<div class="row">

									<div class="col-3">
										<b>Will the event/activity benefit the society/Therapy/Patient in any manner? If yes, how?<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="activity_benefit" name="activity_benefit" value="<?php echo $event_benefit_society; ?>" placeholder="" required="">
										</div>
									</div>
								</div>



								<br>
								<div class="row">

									<div class="col-3">
										<b>Who will lead the event from the Business Unit?<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" value="<?php echo $lead_event; ?>" id="event_lead" name="event_lead" placeholder="" required="">
										</div>
									</div>
								</div>


								<br>
								<div class="row">

									<div class="col-3">
										<b>What is the medical equipment’s needed to be procured for the event/activity?<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" value="<?php echo $medical_equipments; ?>" id="medical_equipment_needed" name="medical_equipment_needed" placeholder="" required="">
										</div>
									</div>
								</div>


								<br>
								<div class="row">

									<div class="col-3">
										<b>Deviation from the approved amount,if any:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" value="<?php echo $deviation_amount; ?>" name="deviation_amount" id="deviation_amount" placeholder="" required="">
										</div>
									</div>
								</div>

								<br><br>

								<!-- <div class="row">
								<div class="col-3">
									<b>Signature</b>
								</div>
							</div> -->

								<br><br>

								<!-- <div class="row">
								<div class="col-3">
									Initiator’s Name: ___________________
								</div>
								
								<div class="col-3">
									Marketing Head: ___________________
								</div>
							</div>

							<br><br>

							<div class="row">
								<div class="col-3">
									Designation: ___________________ 
								</div>
								
								<div class="col-3">
									Date: ___________________
								</div>
							</div>

							<br><br>

							<div class="row">
								<div class="col-3">
									Date: ___________________
								</div>
							</div> -->

							</div>





						</div>
					</div>
				</div>















				<div class="section full mt-2">
					<div class="wide-block pt-2 pb-2">

						<div class="row">
							<!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
							</div> -->
							<div class="col">
								<?php
								if ($mode!='E') { ?>
									<button type="submit" class="exampleBox btn btn-primary rounded me-1">Submit</button>
									
							<?php	}
								?>
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


	<script>
		jQuery('#targetedSpeciality').multiselect({
			columns: 1,
			placeholder: 'Choose...',
			search: true
		});
	</script>

	<!-- ///////////// Js Files ////////////////////  -->
	<!-- Jquery -->
	<script>
		// jQuery('#Quarter').multiselect({
		// 	columns: 1,
		// 	placeholder: 'Choose...',
		// 	search: true
		// });
	</script>
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


</body>

</html>