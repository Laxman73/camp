<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/common.php";


$page_title = 'NAF report Camp Activity';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : ''; //naf id
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : ''; //pma id
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

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
//[id] => 2
// [crm_request_main_id] => 102
// [hcp_id] => 6																			
// [nature_of_camp] => camp activity
// [proposed_camp_date] => 2023-04-26
// [proposed_camp_location] => Goa
// [proposed_camp_duration] => 121
// DFA($CRM_REQUEST_LETTER_DATA);
// exit;

$x_total = GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$rid' "); //total amount

$_q = "select field_id,field_name from crm_naf_fields_master where deleted=0 AND typeid=6 "; //fetch typeid from the category id passed in url
$_r = sql_query($_q, "");


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

		<form action="save_camp_report.php" method="post" id="camp_report">
			<input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
			<input type="hidden" name="pid" value="<?php echo $pid; ?>">
			<input type="hidden" name="rid" value="<?php echo $rid; ?>">



			<div class="tab-content mt-1">



				<div class="section mt-7">
					<div class="card">
						<div class="card-body">

							<div class="wide-block pt-2 pb-2">



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
										<b>Nature of the camp<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="details_activity" placeholder="" value="<?php echo $proposed_activity; ?>">
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
											<input type="text" class="form-control" name="camp_objective" id="camp_objective" placeholder="" required>
										</div>
									</div>

								</div>


								<br>

								<div class="row">


									<div class="col-3">
										<b>Camp Date:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="date" name="camp_date" value="<?php echo $event_date; ?>" class="form-control" id="camp_date" required></div>
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
											<input type="date" class="form-control" name="camp_duration" id="camp_duration" placeholder="" required>
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
										<div class="input-wrapper"><input type="text" class="form-control" id="" value="<?php echo $doctors_name; ?>" required></div>
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
										<div class="input-wrapper"><input type="text" class="form-control" id=""></div>
									</div>

								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Hospital/Clinic name or address:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" id="hospital_name" name="hospital_name"></div>
									</div>

								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Type of Diagnostic test:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" id="type_of_diagnostic" name="type_of_diagnostic" required></div>
									</div>

								</div>

								<br>

								<div class="row">

									<div class="col-3">
										<b>Whether collaboration with Diagnostic Labs:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" id="collab" name="collab" required></div>
									</div>

								</div>



								<br>

								<div class="row">

									<div class="col-3">
										<b>Diagnostic charges, if any:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="number" class="form-control" id="diagnostic_charges" name="diagnostic_charges" required></div>
									</div>

								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Medical equipment cost, if any:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="number" class="form-control" id="medical_equipment_cost" name="medical_equipment_cost" required></div>
									</div>

								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Total no. of individuals screened at the camp:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="number" class="form-control" name="total_no_of_ind" id="total_no_of_ind" required></div>
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

								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="customRadio-1" value="1" name="camp_organised" class="custom-control-input">
									<label class="custom-control-label" for="customRadio-1">Excellent</label>
								</div>
								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="customRadio-2" value="2" name="camp_organised" class="custom-control-input">
									<label class="custom-control-label" for="customRadio-2">Good</label>
								</div>
								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="customRadio-3" value="3" name="camp_organised" class="custom-control-input">
									<label class="custom-control-label" for="customRadio-3">Average</label>
								</div>
								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="customRadio-4" value="4" name="camp_organised" class="custom-control-input">
									<label class="custom-control-label" for="customRadio-4">Poor</label>
								</div>

							</div>



							<div class="wide-block pt-2 pb-2">

								<p><b>2) Was this camp well received i.e., attended by good audience?<span style="color:#ff0000">*</span></b></p>

								<div class="custom-control custom-radio mb-1">
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
								</div>

							</div>



							<div class="wide-block pt-2 pb-2">
								<form>
									<div class="form-group basic">
										<div class="input-wrapper">
											<label class="label" for="">HCP Comments/Suggestions (to be completed by HCP)</label>
											<textarea id="remarks" name="remarks" rows="3" class="form-control"></textarea>
										</div>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>





				<br>

				<br>





				<div class="section full mt-2">
					<div class="wide-block pt-2 pb-2">

						<div class="row">
							<!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
                    </div> -->
							<div class="col">
								<button type="submit" class="exampleBox btn btn-primary rounded me-1">Submit</button>
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


</body>

</html>