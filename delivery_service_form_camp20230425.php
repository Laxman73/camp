<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/common.php";

$page_title = 'Camp Quarter';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
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
$division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division

// getting name of employee from db
$employee_name = GetXFromYID("select concat(first_name,' ',last_name) from users where id = $USER_ID");


$readonly = $disabled = '';
$employee_id = $type_of_activity = $mode = $vendor_services = $vendor_name = $vendor_description = $actual_vendor_cost = $naf_travel_flight_cost = $naf_insurance_cost = $naf_flight_cost = $naf_travel_cab_cost = $naf_visa_cost = $naf_stay_hotel_cost = $naf_audio_visual_cost = $naf_meal_snack_cost = $naf_banners_pamphlets_cost = $naf_other_additonal_cost = $MODE = '';
$Delivery_form_count = GetXFromYID("select count(*) from crm_naf_delivery_form where crm_naf_main_id='$rid' and deleted=0 ");
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
	$type_of_activity = $ACTIVITY_ARR[$activity_ID];
}

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

	<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">


	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
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
		<div class="pageTitle">DELIVERY OF SERVICE FORM</div>
		<div class="right"></div>
	</div>


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


		<div class="tab-content mt-1">



			<div class="section mt-7">
				<div class="card">
					<div class="card-body">

						<div class="wide-block pt-2 pb-2">



							<div class="row">



								<div class="col-3">
									<b>Name of the Employee<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="emp_name" name="emp_name" value="<?php echo $employee_name; ?>" placeholder="" required>
									</div>
								</div>
							</div>

							<br>

							<div class="row">



								<div class="col-3">
									<b>Type of the Activity<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" value="<?php echo $type_of_activity; ?>" required="" <?php echo $readonly; ?>>
									</div>
								</div>
							</div>

							<br>


							<div class="row">

								<div class="col-3">
									<b>Virtual/ Offline<span style="color:#ff0000">*</span></b>
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
									<b>Details of the Activity:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="details_of_activity" value="" name="details_of_activity" required="" <?php echo $readonly; ?>>
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
												echo '<option value="' . $key . '" >' . $value . '</option>';
											}
											?>
										</select>
									</div>
								</div>
							</div>


							<br>


							<div class="row">



								<div class="col-3">
									<b>Vendor Services used</b>
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
									<input type="text" class="form-control" value="<?php echo $actual_vendor_cost; ?>" name="nature_of_actual_cost" id="nature_of_actual_cost" placeholder="" <?php echo $readonly; ?>>
								</div>

								<div class="col-9">
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Travel-Flights:</b>
									<input type="text" class="form-control" value="<?php echo $naf_travel_flight_cost; ?>" name="travel_flights" id="travel_flights" placeholder="" <?php echo $readonly; ?>>
								</div>

								<div class="col-9">
									<b>Insurance:</b>
									<input type="text" class="form-control" value="<?php echo $naf_insurance_cost; ?>" id="insurance" name="insurance" placeholder="" <?php echo $readonly; ?>>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Travel-Cab:</b>
									<input type="text" class="form-control" value="<?php echo $naf_travel_cab_cost; ?>" id="travel_cab" name="travel_cab" placeholder="" <?php echo $readonly; ?>>
								</div>

								<div class="col-9">
									<b>Visa:</b>
									<input type="text" class="form-control" value="<?php echo $naf_visa_cost; ?>" id="visa" name="visa" placeholder="" <?php echo $readonly; ?>>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Stay/Hotel:</b>
									<input type="text" class="form-control" value="<?php echo $naf_stay_hotel_cost; ?>" id="stay_hotel" name="stay_hotel" placeholder="" <?php echo $readonly; ?>>
								</div>

								<div class="col-9">
									<b>Audio/Visual:</b>
									<input type="text" class="form-control" value="<?php echo $naf_audio_visual_cost; ?>" name="audio_v" id="audio_v" placeholder="" <?php echo $readonly; ?>>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Meal/Snacks:</b>
									<input type="text" class="form-control" value="<?php echo $naf_meal_snack_cost; ?>" id="meal" name="meal" placeholder="" <?php echo $readonly; ?>>
								</div>

								<div class="col-9">
									<b>Banners/Pamphelts:</b>
									<input type="text" class="form-control" value="<?php echo $naf_banners_pamphlets_cost; ?>" id="banners" name="banners" placeholder="" <?php echo $readonly; ?>>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Others(Please Specify):</b>
									<input type="text" class="form-control" value="<?php echo $naf_other_additonal_cost; ?>" id="other" name="other" placeholder="" <?php echo $readonly; ?>>
								</div>

								<div class="col-9">
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-9">
									<b>Actual Activity Cost Details (Kindly complete all the relevant cost elements to be incurred)
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

							<div class="wide-block pt-2 pb-2">

								<div class="row">

									<div class="col-3">
										<b>HCP Honorrium (fill 'Appendix-1' with HCP details):<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" value="<?php echo $TOTAL_HONORIUM_AMT; ?>" id="total_honorium" placeholder="" readonly>
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
			</div>


			<div class="section mt-7">
				<div class="card">
					<div class="card-body">

						<div class="wide-block pt-2 pb-2">

							<table id="example" class="display mt-2" style="width:100%">

								<thead>

									<tr>
										<th></th>
										<th>Sr. No</th>
										<th>Name of the HCP</th>
										<th>Address(city)</th>
										<th>PAN #</th>
										<th>Qualification</th>
										<th>Associated Hospital</th>
										<th>Govt.(Yes/No)</th>
										<th>Years of experience</th>
										<th>Honorarium</th>
										<th>Role of the HCP</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th></th>
										<td>1</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>




								</tbody>
							</table>

						</div>
					</div>
				</div>
			</div>











			<div class="section full mt-2">
				<div class="wide-block pt-2 pb-2">

					<div class="row">
						<div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
						</div>
						<div class="col">
							<a href="mt_delivery_service_form_camp.php"><button type="button" class="exampleBox btn btn-primary rounded me-1">Submit</button></a>
						</div>
						<div class="col">
							<a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
						</div>
					</div>

				</div>
			</div>










		</div>



	</div>




	<!-- ///////////// Js Files ////////////////////  -->
	<!-- Jquery -->
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

		$(document).ready(function() {
			var table = $('#example').DataTable({
				rowReorder: {
					selector: 'td:nth-child(2)'
				},
				responsive: true
			});
		});
	</script>
</body>

</html>