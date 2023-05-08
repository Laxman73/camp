<?php
ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'includes/common.php';
$pdo = connectToDatabase();
$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0', '3');
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';

//if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))|| $USER_ID!='1')
if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}

// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division

$_crm_field_q = "select naf_field_id,naf_expense from crm_naf_cost_details where naf_request_id='$rid' ";
$_crm_field_r = sql_query($_crm_field_q, '');
$CRM_FILEDS = array();
while (list($naf_field_id, $naf_expense) = sql_fetch_row($_crm_field_r)) {
	if (!isset($CRM_FILEDS[$naf_field_id]))
		$CRM_FILEDS[$naf_field_id] = array('value' => $naf_expense);
}

$_q = "select field_id,field_name from crm_naf_fields_master where deleted=0 ";
$_r = sql_query($_q, "");

$x_total = GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$rid' ");

// getting view all HCP table data
//$table_data = GetDataFromID('crm_request_details', 'crm_request_main_id', $rid);
$naf_no = GetXFromYID("select naf_no from crm_naf_main where id='$rid'");


//$table_data = GetDataFromQuery("select t2.*,t2.id as PMA_ID from crm_request_main t1 inner join crm_request_details t2 on t1.id=t2.crm_request_main_id where  t1.naf_no='$naf_no' and t1.authorise=3 and t1.level=3 ");
$table_data = GetDataFromQuery("select t2.*,t2.id as PMA_ID,t1.request_no as request_Num from crm_request_main t1 inner join crm_request_details t2 on t1.id=t2.crm_request_main_id where  t1.naf_no='$naf_no' and t1.level=5 and t1.authorise=3");


// getting name of employee from db
$employee_name = GetXFromYID("select concat(first_name,' ',last_name) from users where id = $USER_ID");
//echo $naf_no;

//$naf_no=GetXFromYID("select naf_no from crm_naf_main where id='$rid' ");

$TOTAL_HONORIUM_AMT = GetXFromYID("select sum(t2.honorarium_amount) from crm_request_main t1 inner join crm_request_details t2 on t1.id=t2.crm_request_main_id where t1.naf_no='$naf_no' and t1.deleted=0 and t2.deleted=0  and t1.authorise!=4 ");

// echo $TOTAL_HONORIUM_AMT;
// exit;
//definging variable
$hcp_tabs = 'naf_form_pma.php';
$employee_id = $type_of_activity = $mode = $vendor_services = $vendor_name = $vendor_description = $actual_vendor_cost = $naf_travel_flight_cost = $naf_insurance_cost = $naf_flight_cost = $naf_travel_cab_cost = $naf_visa_cost = $naf_stay_hotel_cost = $naf_audio_visual_cost = $naf_meal_snack_cost = $naf_banners_pamphlets_cost = $naf_other_additonal_cost = $MODE = '';
$Delivery_form_count = GetXFromYID("select count(*) from crm_naf_delivery_form where crm_naf_main_id='$rid' and deleted=0 ");
if ($Delivery_form_count > 0) {
	$MODE = 'R';
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
}
// echo 'mode='.$MODE;
// echo ($MODE != 'R' || $MODE != 'E');
// exit;

// $crm_workflow=crm_workflow($USER_ID,1,1,9);
// //echo 'hii';
// DFA($crm_workflow);
// exit;

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
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
	<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">

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
	include '_tabs.php';
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
											<input type="text" class="form-control" id="" value="<?php echo $employee_name; ?>" required="">
										</div>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Type of the Activity:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="" value="<?php echo $type_of_activity;?>" required="">
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
											<select class="form-control custom-select" name="VO" id="VO" required="">
												<option selected="" disabled="" value="">Choose...</option>
												<?php
												foreach ($VO_ARR as $key => $value) {
													$selected=($key==$mode)?'selected':'';
													?>
												<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
													
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
											<input type="text" class="form-control" id="details_of_activity" value="" name="details_of_activity" required="">
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
											<select class="form-control custom-select" id="Nature_of_activity" name="Nature_of_activity" required="">
												<?php
												foreach ($ACTIVITY_ARR as $key => $value) {
													//echo $value[$key]['iModID'];
													$selected =    ($type_of_activity== $key) ? 'selected' : '';
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
										<b>Vendor services used:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<select class="form-control custom-select" name="vendor_service" id="vendor_service" onchange="DisableFields();" required="">
												<option selected="" disabled="" value="">Choose...</option>
												<?php
												foreach ($VENDOR_SERVICES as $key => $value) {
													$selected=($key==$vendor_services)?'selected':'';
													?>
												<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
													
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
											<input type="text" class="form-control" value="<?php echo $vendor_name;?>"  name="v_payee_name" id="v_payee_name" placeholder="" required="">
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
											<input type="text" value="<?php echo $vendor_description;?>" class="form-control" id="descr" name="descr" placeholder="" required="">
										</div>
									</div>
								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Nature of Actual Cost Vendor:</b>
										<input type="text" class="form-control"  name="nature_of_actual_cost" id="nature_of_actual_cost" placeholder="">
									</div>

									<div class="col-9">
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Travel-Flights:</b>
										<input type="text" class="form-control" name="travel_flights" id="travel_flights" placeholder="">
									</div>

									<div class="col-9">
										<b>Insurance:</b>
										<input type="text" class="form-control" id="insurance" name="insurance" placeholder="">
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Travel-Cab:</b>
										<input type="text" class="form-control" id="travel_cab" name="travel_cab" placeholder="">
									</div>

									<div class="col-9">
										<b>Visa:</b>
										<input type="text" class="form-control" id="visa" name="visa" placeholder="">
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Stay/Hotel:</b>
										<input type="text" class="form-control" id="stay_hotel" name="stay_hotel" placeholder="">
									</div>

									<div class="col-9">
										<b>Audio/Visual:</b>
										<input type="text" class="form-control" name="audio_v" id="audio_v" placeholder="">
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Meal/Snacks:</b>
										<input type="text" class="form-control" id="meal" name="meal" placeholder="">
									</div>

									<div class="col-9">
										<b>Banners/Pamphelts:</b>
										<input type="text" class="form-control" id="banners" name="banners" placeholder="">
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Others(Please Specify):</b>
										<input type="text" class="form-control" id="other" name="other" placeholder="">
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
											<input type="text" class="form-control" value="<?php echo $TOTAL_HONORIUM_AMT; ?>" id="total_honorium" placeholder="">
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
											<input type="number" class="form-control" id="total_local_travel" placeholder="">
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
											<input type="text" class="form-control" id="total_accom" placeholder="">
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
											<input type="text" class="form-control" id="total_flight" placeholder="">
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
											<input type="text" class="form-control" id="total_meal" placeholder="">
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
											<input type="text" class="form-control" id="total_venue_charges" placeholder="">
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
											<input type="text" class="form-control" id="total_AV" placeholder="">
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
											<input type="text" class="form-control" id="totalBP" placeholder="">
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
											<input type="text" class="form-control" id="total_SP" placeholder="">
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
											<input type="text" class="form-control" id="total_grants" placeholder="">
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
											<input type="number" class="form-control" id="total_DG" placeholder="">
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
											<input type="text" class="form-control" id="total_MD" placeholder="">
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
											<input type="text" class="form-control" id="total_other" placeholder="">
										</div>
									</div>
								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>TOTAL AMOUNT:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" id="total_AMT">
										</div>
									</div>

								</div>







							</div>







						</div>
					</div>
				</div>

				<div class=" pt-2 pb-2" style="width: 100%;justify-content: center;text-align: center;">

					<button type="button" class="btn btn-primary rounded me-1 " onclick="showAllHCP()"><b>View all HCP</b></button>
					<?php
					if (empty($MODE)) { ?>

						<button type="submit" class="btn btn-primary rounded me-1 "><b>Submit</b></button>
					<?php	}
					?>


				</div>


				<table id="example" class="display mt-2 table table-responsive table-striped table-bordered" style="width:100%; display:none">

					<thead>

						<tr>
							<th class="bg-purple"></th>
							<th class="bg-purple">Request ID</th>
							<th class="bg-purple">Name of HCP</th>
							<th class="bg-purple">Address(City)</th>
							<th class="bg-purple">Pan Card Number</th>
							<th class="bg-purple">Qualification</th>
							<th class="bg-purple">Associated Hospital</th>
							<th class="bg-purple">Govt(Yes/No)</th>
							<th class="bg-purple">Years of Experience</th>
							<th class="bg-purple">honorarium (INR)</th>
							<th class="bg-purple">Role of the HCP</th>
						</tr>
					</thead>

					<tbody>
						<?php
						if (!empty($table_data) && count($table_data)) {
							foreach ($table_data as $data) {
								$id = $data->id;
								$PMA_ID = $data->crm_request_main_id;
								$hcp_id = $data->hcp_id;
								$REQUEST_NUM=$data->request_Num;
								//$PMA_ID=$data->PMA_ID;
								$hcp_address = $data->hcp_address;
								$hcp_pan = $data->hcp_pan;
								$hcp_qualification = $data->hcp_qualification;
								$govt_type = $data->govt_type;
								$yr_of_experience = $data->yr_of_experience;
								$honorarium_amount = $data->honorarium_amount;
								$role_of_hcp = $data->role_of_hcp;
								$hospital_name = $data->hcp_associated_hospital_id;

								$req_id = 'MG-' . str_pad($id, 6, '0', STR_PAD_LEFT);

								$hcp_name = GetXFromYID("select concat(firstname,' ',lastname) from contactmaster where id='$hcp_id' ");
								// $hospital_name = GetXFromYID("select hosname from hospitalcontactdetails where contactid = $hospital_id");

								//$url = $hcp_tabs."?userid=$USER_ID&rid=$rid&display_all=1&pid=$PMA_ID";
								$url = $hcp_tabs . "?rid=$rid&userid=$USER_ID&pid=$PMA_ID&display_all=1";
						?>
								<tr>
									<td></td>
									<td><a href="<?php echo $url; ?>" target="_blank"><?php echo $REQUEST_NUM; ?></a></td>
									<td><?php echo $hcp_name; ?></td>
									<td><?php echo $hcp_address; ?></td>
									<td><?php echo $hcp_pan; ?></td>
									<td><?php echo $hcp_qualification; ?></td>
									<td><?php echo $hospital_name; ?></td>
									<td><?php echo $govt_type; ?></td>
									<td><?php echo $yr_of_experience; ?></td>
									<td><?php echo $honorarium_amount; ?></td>
									<td><?php echo $role_of_hcp; ?></td>
								</tr>
						<?php
							}
						}
						?>
					</tbody>

				</table>

			</div>

		</form>

	</div>




	<!-- ///////////// Js Files ////////////////////  -->
	<!-- Jquery -->
	<script>
		jQuery('#targetedSpeciality').multiselect({
			columns: 1,
			placeholder: 'Choose...',
			search: true
		});
	</script>

	<script>
		$(document).ready(function() {

			$('#SERVICE_FORM').on('submit',function(){
				var ret=true;
				var rid=$('#rid').val();
				$.ajax({
					url:'_checkServiceForm.php',
					method:'POST',
					data:{
						rid:rid
					},
					async:false,
					success:function(res){
						if (res==1) {
							ret=false;
							//alert('Number of participants is less than the NAF raised for the PMA requests');
	
						}
					}
				})
				
				return true;
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
			var total_grants = ($('#total_grants').val() != '') ? $('#total_grants').val() : 0;
			var total_DG = ($('#total_DG').val() != '') ? $('#total_DG').val() : 0;
			var total_MD = ($('#total_MD').val() != '') ? $('#total_MD').val() : 0;
			var total_other = ($('#total_other').val() != '') ? $('#total_other').val() : 0;
			Final_total = parseFloat(total_honorium) + parseFloat(total_local_travel) + parseFloat(total_accom) + parseFloat(total_flight) + parseFloat(total_meal) + parseFloat(total_venue_charges) + parseFloat(total_AV) + parseFloat(totalBP) + parseFloat(total_SP) + parseFloat(total_grants) + parseFloat(total_DG) + parseFloat(total_MD) + parseFloat(total_other) + parseFloat(Final_total);
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

		function showAllHCP() {
			document.getElementById("example").style.display = "block";
		}
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