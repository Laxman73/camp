<?php
include 'includes/common.php';

$pdo = connectToDatabase();

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$category = (isset($_GET['category'])) ? $_GET['category'] : '';

//if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))|| $USER_ID!='1')
if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}

$data = GetDataFromID('crm_naf_main', 'id', $rid, ' and deleted=0');
$naf_activity_name = $data[0]->naf_activity_name;
$userid = $data[0]->userid;

$full_name = GetXFromYID('select CONCAT(first_name, " ", last_name) AS full_name from users where id=' . $userid . ' and deleted=0');

$activity_name = GetXFromYID("SELECT activityname FROM crm_naf_activity_details inner join crm_naf_activitymaster on crm_naf_activity_details.activity_id = crm_naf_activitymaster.id WHERE naf_request_id = '$rid'");

$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");





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

$NAF_COST_ARRAY = GetXArrFromYID("select naf_field_id,naf_expense from crm_naf_cost_details where naf_request_id='$rid'", '3');


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
		<div class="pageTitle camp">HO DELIVERY OF SERVICE FORM</div>
		<div class="right"></div>
	</div>
	
	<?php 
    include '_tabs_naf_quarter_consultancy.php';
    ?>


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
		<form action="save_ho_delivery_consultancyFORM.php" id="ho_delivery_consultancyFORM" method="POST">

			<div class="tab-content mt-1">

				<div class="section mt-7">
					<div class="card">
						<div class="card-body">

							<div class="wide-block pt-2 pb-2">

							<input type="hidden" name="rid" id="rid" value="<?php echo $rid; ?>">
							<input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
							<input type="hidden" name="category" value="<?php echo $category; ?>">

								<div class="row">
									<div class="col-3">
										<b>Name of the Employee<span style="color:#ff0000">*</span></b>
									</div>
									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="EmployeeName" name="nameOfEmp"
												placeholder="" value='<?php echo $full_name; ?>' readonly>
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
											<input type="text" class="form-control" id="ActivityType"
												name="ActivityType" placeholder=""
												value='<?php echo $naf_activity_name; ?>' readonly>
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
											<input type="text" class="form-control" id="ActivityName"
												name="ActivityName" placeholder="" value='<?php echo $activity_name; ?>'
												readonly>
										</div>
									</div>
								</div>

								<br>


								<div class="wide-block"
									style="border-top: 1px solid black;border-bottom: 1px solid black;">
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
											<b>HCP Honorrium (fill 'Appendix-1' with HCP details):<span
													style="color:#ff0000">*</span></b>
										</div>
										<div class="col-9">
											<div class="input-wrapper">
												<input type="number" class="form-control" id="Honorrium"
													name="Honorrium" placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[14]) ? $NAF_COST_ARRAY[14] : 0; ?>"
													onchange="updateAMT()"
													readonly>
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
												<input type="number" class="form-control" id="LocalTravel"
													name="LocalTravel" placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[15]) ? $NAF_COST_ARRAY[15] : 0; ?>"
													onchange="updateAMT()"
													required>
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
												<input type="number" class="form-control" id="Stay" name="Stay"
													placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[16]) ? $NAF_COST_ARRAY[16] : 0; ?>"
													onchange="updateAMT()"
													required>
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
												<input type="number" class="form-control" id="Flight" name="Flight"
													placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[17]) ? $NAF_COST_ARRAY[17] : 0; ?>"
													onchange="updateAMT()"
													id="total_flight" placeholder="" required>
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
												<input type="number" class="form-control" id="Food" name="Food"
													placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[18]) ? $NAF_COST_ARRAY[18] : 0; ?>"
													onchange="updateAMT()"
													required>
											</div>
										</div>
									</div>


									<br>

									<div class="row">
										<div class="col-3">
											<b>Venue charges:</b>
										</div>
										<div class="col-9">
											<div class="input-wrapper">
												<input type="number" class="form-control" id="VenueCharges"
													name="VenueCharges" placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[19]) ? $NAF_COST_ARRAY[19] : 0; ?>"
													onchange="updateAMT()"
													required>
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
												<input type="number" class="form-control" id="AV" name="AV"
													placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[20]) ? $NAF_COST_ARRAY[20] : 0; ?>"
													onchange="updateAMT()"
													required>
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
												<input type="number" class="form-control" id="Banners" name="Banners"
													placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[21]) ? $NAF_COST_ARRAY[21] : 0; ?>"
													onchange="updateAMT()"
													required>
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
												<input type="number" class="form-control" id="Sponsorship"
													name="Sponsorship" placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[22]) ? $NAF_COST_ARRAY[22] : 0; ?>"
													onchange="updateAMT()"
													required>
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
												<input type="number" class="form-control" id="Grants" name="Grants"
													placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[29]) ? $NAF_COST_ARRAY[29] : 0; ?>"
													onchange="updateAMT()"
													required>
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
												<input type="number" class="form-control" id="DiagnosticCost"
													name="DiagnosticCost" placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[23]) ? $NAF_COST_ARRAY[23] : 0; ?>"
													onchange="updateAMT()"
													required>
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
												<input type="number" class="form-control" id="MedEquipmentCost"
													name="MedEquipmentCost" placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[24]) ? $NAF_COST_ARRAY[24] : 0; ?>"
													onchange="updateAMT()"
													required>
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
												<input type="number" class="form-control" id="OtherCost"
													name="OtherCost" placeholder=""
													value="<?php echo isset($NAF_COST_ARRAY[25]) ? $NAF_COST_ARRAY[25] : 0; ?>"
													onchange="updateAMT()"
													required>
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
												<input type="number" class="form-control" id="TotalAMT" name="TotalAMT"
													placeholder="" readonly>
											</div>
										</div>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>


				<div class="section mt-4">
					<div class="card">
						<div class="card-body">
							<div class="wide-block pt-2 pb-2">

								<div class="wide-block pt-2 pb-2">
							
										<div class="form-group basic">
											<div class="input-wrapper">
												<label class="label" for="">Overall Outcome</label>
												<textarea id="OverallOutcome" name="OverallOutcome" rows="3"
													class="form-control"></textarea>
											</div>
										</div>
									
								</div>

							</div>
						</div>
					</div>
				</div>



				<!-- <div class="section mt-7">
					<div class="card">
						<div class="card-body">
							<div class="wide-block pt-2 pb-2">

								<div class="row">
									<div class="col-4">
										Initiator’s Name: ___________________
									</div>

									<div class="col-4">
										BU Head: ___________________
									</div>

									<div class="col-4">
										Business Compliance Team:: ___________________
									</div>
								</div>

								<br><br>

								<div class="row">
									<div class="col-4">
										Designation: ___________________
									</div>

									<div class="col-4">
										Signature: ___________________
									</div>

									<div class="col-4">
										Signature: ___________________
									</div>
								</div>

								<br><br>

								<div class="row">
									<div class="col-4">
										Signature: ___________________
									</div>

									<div class="col-4">
										Date: ___________________
									</div>

									<div class="col-4">
										Date: ___________________
									</div>
								</div>

								<br><br>

								<div class="row">

									<div class="col-4">
										Date: ___________________
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			 -->
			</div>



			<div class="section full mt-2">
				<div class="wide-block pt-2 pb-2">

					<div class="row">
						<!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
					</div> -->
						<div class="col">
							<button type="submit"
									class="exampleBox btn btn-primary rounded me-1">Submit</button>
						</div>
						<!-- <div class="col">
						<a href="#"><button type="button"
								class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
					</div> -->
					</div>
				</div>
			</div>
		</form>
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
		$(document).ready(function () {
			var table = $('#example').DataTable({
				rowReorder: {
					selector: 'td:nth-child(2)'
				},
				responsive: true
			});

			var Final_total = 0;

			var total_honorium = document.getElementById("Honorrium").value;
			var total_local_travel = document.getElementById("LocalTravel").value;
			var total_accom = document.getElementById("Stay").value;
			var total_flight = document.getElementById("Flight").value;
			var total_meal = document.getElementById("Food").value;
			var total_venue_charges = document.getElementById("VenueCharges").value;
			var total_AV = document.getElementById("AV").value;
			var totalBP = document.getElementById("Banners").value;
			var total_SP = document.getElementById("Sponsorship").value;
			var total_grants = document.getElementById("Grants").value;
			var total_DG = document.getElementById("DiagnosticCost").value;
			var total_MD = document.getElementById("MedEquipmentCost").value;
			var total_other = document.getElementById("OtherCost").value;
			Final_total = parseFloat(total_honorium) + parseFloat(total_local_travel) + parseFloat(total_accom) + parseFloat(total_flight) + parseFloat(total_meal) + parseFloat(total_venue_charges) + parseFloat(total_AV) + parseFloat(totalBP) + parseFloat(total_SP) + parseFloat(total_DG) + parseFloat(total_MD) + parseFloat(total_other) + parseFloat(total_grants);
			console.log(Final_total);
			$('#TotalAMT').val(Final_total);

		});

		function updateAMT() {
			var Final_total = 0;

			var total_honorium = document.getElementById("Honorrium").value;
			var total_local_travel = document.getElementById("LocalTravel").value;
			var total_accom = document.getElementById("Stay").value;
			var total_flight = document.getElementById("Flight").value;
			var total_meal = document.getElementById("Food").value;
			var total_venue_charges = document.getElementById("VenueCharges").value;
			var total_AV = document.getElementById("AV").value;
			var totalBP = document.getElementById("Banners").value;
			var total_SP = document.getElementById("Sponsorship").value;
			var total_grants = document.getElementById("Grants").value;
			var total_DG = document.getElementById("DiagnosticCost").value;
			var total_MD = document.getElementById("MedEquipmentCost").value;
			var total_other = document.getElementById("OtherCost").value;
			Final_total = parseFloat(total_honorium) + parseFloat(total_local_travel) + parseFloat(total_accom) + parseFloat(total_flight) + parseFloat(total_meal) + parseFloat(total_venue_charges) + parseFloat(total_AV) + parseFloat(totalBP) + parseFloat(total_SP) + parseFloat(total_DG) + parseFloat(total_MD) + parseFloat(total_other) + parseFloat(total_grants);
			console.log(Final_total);
			$('#TotalAMT').val(Final_total);
		}
	</script>
</body>

</html>