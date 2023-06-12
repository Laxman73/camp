<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/common.php";

$page_title = 'Camp Quarter';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$prid = (isset($_GET['prid'])) ? $_GET['prid'] : '';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$category = (isset($_GET['category'])) ? $_GET['category'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}
// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division

// getting name of employee from db
$employee_name = GetXFromYID("select concat(first_name,' ',last_name) from users where id = $USER_ID");


$readonly = $disabled = '';
$employee_id = $activity_name = $details_of_activity = $type_of_activity = $mode = $vendor_services = $vendor_name = $vendor_description = $actual_vendor_cost = $naf_travel_flight_cost = $naf_insurance_cost = $naf_flight_cost = $naf_travel_cab_cost = $naf_visa_cost = $naf_stay_hotel_cost = $naf_audio_visual_cost = $naf_meal_snack_cost = $naf_banners_pamphlets_cost = $naf_other_additonal_cost = $MODE = '';
$Delivery_form_count = GetXFromYID("select count(*) from crm_naf_delivery_form where crm_naf_main_id='$rid' and deleted=0 ");


$CRM_MAIN_DATA = GetDataFromID('crm_naf_main', 'id', $rid, "and deleted=0 and category_id=5 ");

$activity_name = db_output2($CRM_MAIN_DATA[0]->naf_activity_name);
$type_of_activity = db_output2($CRM_MAIN_DATA[0]->proposed_activity);
$pending_with_id = db_output2($CRM_MAIN_DATA[0]->pendingwithid);

if ($Delivery_form_count > 0) {
	$MODE = 'R';
	$readonly = 'readonly';
	$disabled = 'disabled';
} else {
	$MODE = 'A';
}
if ($MODE == 'R' || $MODE == 'E') {
	$DELIVERY_FORM_DATA = GetDataFromID('crm_naf_delivery_form', 'crm_naf_main_id', $rid, "and deleted=0 ");

	$naf_delivery_form_id = $DELIVERY_FORM_DATA[0]->id;
	$type_of_activity = $DELIVERY_FORM_DATA[0]->type_of_activity;
	$mode = $DELIVERY_FORM_DATA[0]->mode;
	$vendor_name = $DELIVERY_FORM_DATA[0]->vendor_name;
	$vendor_services = $DELIVERY_FORM_DATA[0]->vendor_services;
	$vendor_description = $DELIVERY_FORM_DATA[0]->vendor_description;
	$activity_name = $DELIVERY_FORM_DATA[0]->name_of_activity;
	$details_of_activity = $DELIVERY_FORM_DATA[0]->details_of_activity;


	$DELIVERY_COST_DETAILS = GetDataFromID('crm_naf_delivery_form_cost_details', 'naf_delivery_form_id', $naf_delivery_form_id, "and deleted=0 ");
	$actual_vendor_cost = $DELIVERY_COST_DETAILS[0]->actual_vendor_cost;
	$naf_travel_flight_cost = $DELIVERY_COST_DETAILS[0]->naf_travel_flight_cost;
	$naf_insurance_cost = $DELIVERY_COST_DETAILS[0]->naf_insurance_cost;
	$naf_flight_cost = $DELIVERY_COST_DETAILS[0]->naf_flight_cost;
	$naf_travel_cab_cost = $DELIVERY_COST_DETAILS[0]->naf_travel_cab_cost;
	$naf_visa_cost = $DELIVERY_COST_DETAILS[0]->naf_visa_cost;
	$naf_stay_hotel_cost = $DELIVERY_COST_DETAILS[0]->naf_stay_hotel_cost;
	$naf_audio_visual_cost = $DELIVERY_COST_DETAILS[0]->naf_audio_visual_cost;
	$naf_meal_snack_cost = $DELIVERY_COST_DETAILS[0]->naf_meal_snack_cost;
	$naf_banners_pamphlets_cost = $DELIVERY_COST_DETAILS[0]->naf_banners_pamphlets_cost;
	$naf_other_additonal_cost = $DELIVERY_COST_DETAILS[0]->naf_other_additonal_cost;


	$NAF_COST_ARRAY = GetXArrFromYID("select naf_field_id,naf_expense from crm_naf_cost_details where naf_request_id='$rid'  ", '3');
}


$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,activityname  as name FROM crm_naf_activitymaster where deleted=0', '3');
if ($MODE == 'E') {
	$activity_ID = GetXFromYID("select activity_id from crm_naf_activity_details where naf_request_id='$rid' ");
	//$type_of_activity = $ACTIVITY_ARR[$activity_ID];
}
$naf_no = GetXFromYID("select naf_no from crm_naf_main where id='$rid'");
$TOTAL_HONORIUM_AMT = GetXFromYID("select sum(honorarium_amount) from crm_naf_hcp_details  where naf_main_id='$rid' ");

$HCP_DATA = GetDataFromID('crm_naf_hcp_details', 'naf_main_id', $rid, "and deleted=0 ");

?>
<!doctype html>
<html lang="en">

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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">-->
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


<body>


	<div class="appHeader bg-primary">
		<div class="left">
			<a href="javascript:;" class="headerButton goBack">
				<ion-icon name="chevron-back-outline"></ion-icon>
			</a>
		</div>
		<div class="pageTitle">Delivery of Service Form</div>
		<div class="right">
			<a href="index_PM.php?userid=<?php echo $USER_ID; ?>">
				<ion-icon name="home-outline" class="icon-color"></ion-icon>
			</a>
		</div>
	</div>


	<?php /* <div class="section full mt-7">
		<div class="wide-block pt-2 pb-2">

			<div class="row">
				<div class="col-2">
					<a href="naf_form.php">
						<div class="tabBox">NAF FORM</div>
					</a>
				</div>
				<div class="col-2">
					<a href="#">
						<div class="tabBox">DELIVERY OF SERVICE FORM</div>
					</a>
				</div>
				<!--<div class="col-2">
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
			</div>

		</div>
	</div> */
	include '_tabscamp.php';
	?>


	<div id="appCapsule">

		<form action="save_serviceForm.php" method="post" id="SERVICE_FORM">
			<input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
			<input type="hidden" id="rid" name="rid" value="<?php echo $rid; ?>">
			<div class="tab-content mt-1">



				<div class="section mt-2">
					<div class="card">
						<div class="card-body">


							<div class="wide-block pt-2 pb-2">

								<div class="row">

									<div class="col-3">
										<b>Name of the Employee:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="" value="<?php echo $employee_name; ?>" required="" readonly>
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
											<input type="text" class="form-control" id="activity_name" name="activity_name" value="<?php echo (isset($activity_name)) ? $activity_name : ''; ?>" required="" <?php echo $readonly; ?>>
										</div>
									</div>
								</div>



								<br>

								<div class="row">

									<div class="col-3">
										<b>Virtual/Offline:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<select class="form-control custom-select" name="VO" id="VO" required="" <?php echo $disabled; ?>>
												<option selected="" disabled="" value="">Choose...</option>
												<?php
												foreach ($VO_ARR as $key => $value) {
													$selected = ($key == $mode) ? 'selected' : '';
												?>
													<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>

												<?php	}


												?>

											</select>
										</div>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Details of the Activity:</b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="details_of_activity" value="<?php echo $details_of_activity; ?>" name="details_of_activity" required="" <?php echo $readonly; ?>>
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
											<select class="form-control custom-select" id="Nature_of_activity" name="Nature_of_activity" required="" <?php echo $readonly; ?>>
												<?php
												foreach ($ACTIVITY_ARR as $key => $value) {
													//echo $value[$key]['iModID'];
													$selected =    ($type_of_activity == $key) ? 'selected' : '';
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
										<b>Vendor services used:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<select class="form-control custom-select" name="vendor_service" id="vendor_service" onchange="DisableFields();" required="" <?php echo $disabled; ?>>
												<option selected="" disabled="" value="">Choose...</option>
												<?php
												foreach ($VENDOR_SERVICES as $key => $value) {
													$selected = ($key == $vendor_services) ? 'selected' : '';
												?>
													<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>

												<?php	}


												?>
												<!-- <option value="1">Yes</option>
												<option value="2">No</option> -->
											</select>
										</div>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Vendor Payee Name:</b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" value="<?php echo $vendor_name; ?>" name="v_payee_name" id="v_payee_name" placeholder="" required="" <?php echo $readonly; ?>>
										</div>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Description of the Vendor:</b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" value="<?php echo $vendor_description; ?>" class="form-control" id="descr" name="descr" placeholder="" required="" <?php echo $readonly; ?>>
										</div>
									</div>
								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Nature of Actual Cost Vendor:</b>
										<input type="text" class="form-control amountCalculate" value="<?php echo $actual_vendor_cost; ?>" onkeyup="CalculateTotal();" name="nature_of_actual_cost" id="nature_of_actual_cost" placeholder="" <?php echo $readonly; ?>>
									</div>

									<div class="col-9">
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Travel-Flights:</b>
										<input type="text" class="form-control amountCalculate" value="<?php echo $naf_travel_flight_cost; ?>" onkeyup="CalculateTotal();" name="travel_flights" id="travel_flights" placeholder="" <?php echo $readonly; ?>>
									</div>

									<div class="col-9">
										<b>Insurance:</b>
										<input type="text" class="form-control amountCalculate" value="<?php echo $naf_insurance_cost; ?>" onkeyup="CalculateTotal();" id="insurance" name="insurance" placeholder="" <?php echo $readonly; ?>>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Travel-Cab:</b>
										<input type="text" class="form-control amountCalculate" value="<?php echo $naf_travel_cab_cost; ?>" onkeyup="CalculateTotal();" id="travel_cab" name="travel_cab" placeholder="" <?php echo $readonly; ?>>
									</div>

									<div class="col-9">
										<b>Visa:</b>
										<input type="text" class="form-control amountCalculate" value="<?php echo $naf_visa_cost; ?>" onkeyup="CalculateTotal();" id="visa" name="visa" placeholder="" <?php echo $readonly; ?>>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Stay/Hotel:</b>
										<input type="text" class="form-control amountCalculate" value="<?php echo $naf_stay_hotel_cost; ?>" onkeyup="CalculateTotal();" id="stay_hotel" name="stay_hotel" placeholder="" <?php echo $readonly; ?>>
									</div>

									<div class="col-9">
										<b>Audio/Visual:</b>
										<input type="text" class="form-control amountCalculate" value="<?php echo $naf_audio_visual_cost; ?>" onkeyup="CalculateTotal();" name="audio_v" id="audio_v" placeholder="" <?php echo $readonly; ?>>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Meal/Snacks:</b>
										<input type="text" class="form-control amountCalculate" value="<?php echo $naf_meal_snack_cost; ?>" onkeyup="CalculateTotal();" id="meal" name="meal" placeholder="" <?php echo $readonly; ?>>
									</div>

									<div class="col-9">
										<b>Banners/Pamphelts:</b>
										<input type="text" class="form-control amountCalculate" value="<?php echo $naf_banners_pamphlets_cost; ?>" onkeyup="CalculateTotal();" id="banners" name="banners" placeholder="" <?php echo $readonly; ?>>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Others(Please Specify):</b>
										<input type="text" class="form-control amountCalculate" value="<?php echo $naf_other_additonal_cost; ?>" onkeyup="CalculateTotal();" id="other" name="other" placeholder="" <?php echo $readonly; ?>>
									</div>

									<div class="col-9">
									</div>
								</div>
								<br>

								<div class="row">


									<b>Actual Activity Cost Details</b>(Kindly complete all the relevant cost elements to be incurred)

								</div>
							</div>

							<div class="wide-block" style="border-top: 1px solid black;border-bottom: 1px solid black;">

								<div class="row">

									<div class="col-3">
										<div class="form-title"><b>Particulars</b></div>
									</div>

									<div class="col-9">
										<div class="form-title"><b>Amount(INR)</b></div>
									</div>
								</div>


							</div>

							<div class="wide-block pt-2 pb-2">

								<div class="row">

									<div class="col-3">
										<b>HCP Honorrium (fill 'Appendix-1' with HCP details):<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control amountCalculate" value="<?php echo $TOTAL_HONORIUM_AMT; ?>" id="total_honorium" placeholder="" readonly>
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
											<input type="number" class="form-control" value="<?php echo isset($NAF_COST_ARRAY[2]) ? $NAF_COST_ARRAY[2] : ''; ?>" id="total_local_travel" placeholder="" readonly>
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
											<input type="text" class="form-control" value="<?php echo isset($NAF_COST_ARRAY[3]) ? $NAF_COST_ARRAY[3] : ''; ?>" id="total_accom" placeholder="" readonly>
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
											<input type="text" class="form-control" value="<?php echo isset($NAF_COST_ARRAY[4]) ? $NAF_COST_ARRAY[4] : ''; ?>" id="total_flight" placeholder="" readonly>
										</div>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Meals / Snack:</b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="total_meal" value="<?php echo isset($NAF_COST_ARRAY[5]) ? $NAF_COST_ARRAY[5] : ''; ?>" placeholder="" readonly>
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
											<input type="text" class="form-control" value="<?php echo isset($NAF_COST_ARRAY[6]) ? $NAF_COST_ARRAY[6] : ''; ?>" id="total_venue_charges" placeholder="" readonly>
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
											<input type="text" class="form-control" value="<?php echo isset($NAF_COST_ARRAY[7]) ? $NAF_COST_ARRAY[7] : ''; ?>" id="total_AV" placeholder="" readonly>
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
											<input type="text" class="form-control" value="<?php echo isset($NAF_COST_ARRAY[8]) ? $NAF_COST_ARRAY[8] : ''; ?>" id="totalBP" placeholder="" readonly>
										</div>
									</div>
								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Sponsorship/Participations:</b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="total_SP" value="<?php echo isset($NAF_COST_ARRAY[9]) ? $NAF_COST_ARRAY[9] : ''; ?>" placeholder="" readonly>
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
											<input type="number" class="form-control" value="<?php echo isset($NAF_COST_ARRAY[10]) ? $NAF_COST_ARRAY[10] : ''; ?>" id="total_DG" placeholder="" readonly>
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
											<input type="text" value="<?php echo isset($NAF_COST_ARRAY[11]) ? $NAF_COST_ARRAY[11] : ''; ?>" class="form-control" id="total_MD" placeholder="" readonly>
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
											<input type="text" value="<?php echo isset($NAF_COST_ARRAY[12]) ? $NAF_COST_ARRAY[12] : ''; ?>" class="form-control" id="total_other" placeholder="" readonly>
										</div>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>TOTAL AMOUNT:</b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" id="total_AMT" readonly>
										</div>
									</div>

								</div>







							</div>







						</div>
					</div>
				</div>

				<?php
				if ($MODE == 'R' && $pending_with_id == $USER_ID) { ?>
					<div class="section full mt-2">
						<div class="wide-block pt-2 pb-2 text-center">
							<!--	<a href="approve.php?rid=<?php echo $rid; ?>&userid=<?php echo $USER_ID; ?>&pid=<?php echo $pid; ?>&mode=A"><button type="button" class="btn btn-success mr-1 mb-1 pd-sr">Approve</button></a>
							<a href="approve.php?rid=<?php echo $rid; ?>&userid=<?php echo $USER_ID; ?>&pid=<?php echo $pid; ?>&mode=R"><button type="button" class="btn btn-danger mr-1 mb-1 pd-sr">Reject</button></a>
						 -->

							<a href="<?php echo '_approve_dos_camp_amrm.php?userid=' . $USER_ID . '&mode=A&rid=' . $rid . '&category=5'; ?>"><button type="button" class="btn btn-success mr-1 mb-1 pd-sr">Approve</button></a>

							<a href="<?php echo '_approve_dos_camp_amrm.php?userid=' . $USER_ID . '&mode=R&rid=' . $rid . '&category=5'; ?>"><button type="button" class="btn btn-danger mr-1 mb-1 pd-sr">Reject</button></a>



						</div>
					</div>


				<?php   }
				?>

				<div class=" pt-2 pb-2" style="width: 100%;justify-content: center;text-align: center;">


					<?php
					if (($MODE == 'A') && ($PROFILE_ID == 6 || $PROFILE_ID == 7)) { ?>

						<button type="submit" class="btn btn-primary rounded me-1 "><b>Submit</b></button>
					<?php	}
					?>


				</div>




			</div>

		</form>



		<div class="section mt-7">
			<div class="card">
				<div class="card-body">

					<div class="wide-block pt-2 pb-2">

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
										$hcp_name = GetXFromYID("SELECT CONCAT(firstname, ' ', lastname) AS full_name FROM contactmaster where id='$hcp_id' ");
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


								<?php    }
								}
								?>




							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>

	</div>






	<!-- ///////////// Js Files ////////////////////  -->
	<!-- Jquery -->


	<!-- <script src="assets/js/lib/jquery-3.4.1.min.js"></script> -->
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
		function CalculateTotal() {
			var total = 0;
			$('.amountCalculate').each(function() {
				var numbers = $(this).val();

				if ((numbers != '')) {

					total += parseFloat(numbers);
				}

			});

			//console.log(parseInt(total));
			$('#total_AMT').val(total);
		}

		$(document).ready(function() {

			var table = $('#example').DataTable({

				responsive: true
			});


			$('#SERVICE_FORM').on('submit', function() {
				var ret = true;
				var rid = $('#rid').val();
				// $.ajax({
				// 	url: '_checkServiceForm.php',
				// 	method: 'POST',
				// 	data: {
				// 		rid: rid
				// 	},
				// 	async: false,
				// 	success: function(res) {
				// 		if (res == 1) {
				// 			ret = false;
				// 			alert('Number of participants is less than the NAF raised for the PMA requests');

				// 		}
				// 	}
				// })

				return ret;
			});
			var Final_total = 0;
			var total_honorium = $('#total_honorium').val();
			var total_local_travel = ($('#total_local_travel').val() != '') ? $('#total_local_travel').val() : 0;
			var total_accom = ($('#total_accom').val() != '') ? $('#total_accom').val() : 0;
			var total_flight = ($('#total_flight').val() != '') ? $('#total_flight').val() : 0;
			var total_meal = ($('#total_meal').val() != '') ? $('#total_meal').val() : 0;
			var total_venue_charges = ($('#total_venue_charges').val() != '') ? $('#total_venue_charges').val() : 0;
			var total_AV = ($('#total_AV').val() != '') ? $('#total_AV').val() : 0;
			var totalBP = ($('#totalBP').val() != '') ? $('#totalBP').val() : 0;
			var total_SP = ($('#total_SP').val() != '') ? $('#total_SP').val() : 0;
			//var total_grants = ($('#total_grants').val() != '') ? $('#total_grants').val() : 0;
			var total_DG = ($('#total_DG').val() != '') ? $('#total_DG').val() : 0;
			var total_MD = ($('#total_MD').val() != '') ? $('#total_MD').val() : 0;
			var total_other = ($('#total_other').val() != '') ? $('#total_other').val() : 0;
			Final_total = parseFloat(total_honorium) + parseFloat(total_local_travel) + parseFloat(total_accom) + parseFloat(total_flight) + parseFloat(total_meal) + parseFloat(total_venue_charges) + parseFloat(total_AV) + parseFloat(totalBP) + parseFloat(total_SP) + parseFloat(total_DG) + parseFloat(total_MD) + parseFloat(total_other) + parseFloat(Final_total);
			console.log(Final_total);
			$('#total_AMT').val(Final_total);
		});

		function DisableFields() {
			var serviceused = $('#vendor_service').val();
			//alert(serviceused);
			console.log(serviceused);
			if (serviceused == '2') {
				$("#nature_of_actual_cost").prop('disabled', true);
				$("#travel_flights").prop('disabled', true);
				$("#insurance").prop('disabled', true);
				$("#travel_cab").prop('disabled', true);
				$("#visa").prop('disabled', true);
				$("#stay_hotel").prop('disabled', true);
				$("#audio_v").prop('disabled', true);
				$("#meal").prop('disabled', true);
				$("#banners").prop('disabled', true);
				$("#other").prop('disabled', true);
				$("#v_payee_name").prop('disabled', true);
				$("#descr").prop('disabled', true);

				//making fields required 
				$("#nature_of_actual_cost").prop('required', false);
				$("#travel_flights").prop('required', false);
				$("#insurance").prop('required', false);
				$("#travel_cab").prop('required', false);
				$("#visa").prop('required', false);
				$("#stay_hotel").prop('required', false);
				$("#audio_v").prop('required', false);
				$("#meal").prop('required', false);
				$("#banners").prop('required', false);
				$("#other").prop('required', false);
				$("#v_payee_name").prop('required', false);
				$("#descr").prop('required', false);

			} else {
				$("#nature_of_actual_cost").prop('disabled', false);
				$("#travel_flights").prop('disabled', false);
				$("#insurance").prop('disabled', false);
				$("#travel_cab").prop('disabled', false);
				$("#visa").prop('disabled', false);
				$("#stay_hotel").prop('disabled', false);
				$("#audio_v").prop('disabled', false);
				$("#meal").prop('disabled', false);
				$("#banners").prop('disabled', false);
				$("#other").prop('disabled', false);
				$("#v_payee_name").prop('disabled', false);
				$("#descr").prop('disabled', false);

				//making fields required

				$("#nature_of_actual_cost").prop('required', true);
				$("#travel_flights").prop('required', true);
				$("#insurance").prop('required', true);
				$("#travel_cab").prop('required', true);
				$("#visa").prop('required', true);
				$("#stay_hotel").prop('required', true);
				$("#audio_v").prop('required', true);
				$("#meal").prop('required', true);
				$("#banners").prop('required', true);
				$("#other").prop('required', true);
				$("#v_payee_name").prop('required', true);
				$("#descr").prop('required', true);

			}
		}
	</script>



</body>

</html>