<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/common.php";

$page_title = 'Camp Quarter';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$category = (isset($_GET['category'])) ? $_GET['category'] : '';
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
$employee_id = $type_of_activity = $comment = $mode = $vendor_services = $vendor_name = $vendor_description = $actual_vendor_cost = $naf_travel_flight_cost = $naf_insurance_cost = $naf_flight_cost = $naf_travel_cab_cost = $naf_visa_cost = $naf_stay_hotel_cost = $naf_audio_visual_cost = $activity_name = $naf_meal_snack_cost = $naf_banners_pamphlets_cost = $naf_other_additonal_cost = $MODE = '';
$PARENT_ID_NAF = GetXFromYID("select naf_no from crm_naf_main where id='$rid' and deleted=0 ");
$PARENT_ID = GetXFromYID("select id from crm_naf_main where parent_id='$PARENT_ID_NAF' and deleted=0  order by id DESC limit 1");



$post_commmnet_submitted = GetXFromYID("select post_comment from crm_naf_main where id='$rid' and deleted=0 ");
$post_commmnet_submitted = db_input2($post_commmnet_submitted);

$hcp_tabs='index_naf_camp.php';


if (!empty($post_commmnet_submitted)) {
	$MODE = 'R';
	$readonly = 'readonly';
	$disabled = 'disabled';
} else {
	$MODE = 'A';
	$activity_name = GetXFromYID("select naf_activity_name from crm_naf_main where id='$rid' and deleted=0 ");

}
if ($MODE == 'R' || $MODE == 'E') {
	$DELIVERY_FORM_DATA = GetDataFromID('crm_naf_delivery_form', 'crm_naf_main_id', $PARENT_ID, "and deleted=0 ");

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

	$comment = GetXFromYID("select post_comment from crm_naf_main where id=$rid and deleted=0 ");



	$NAF_COST_ARRAY = GetXArrFromYID("select naf_field_id,naf_expense from crm_naf_cost_details where naf_request_id='$rid'  ", '3');
}


$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,activityname  as name FROM crm_naf_activitymaster where deleted=0', '3');
if ($MODE == 'A') {
	// $activity_ID = GetXFromYID("select activity_id from crm_naf_activity_details where naf_request_id='$rid' ");
	$type_of_activity = GetXFromYID("select proposed_activity from crm_naf_main where id='$rid' and deleted=0 ");
}




$naf_no = GetXFromYID("select naf_no from crm_naf_main where id='$rid'");
$NAF_IDS = GetXArrFromYID(" select id from crm_naf_main where parent_id='$naf_no' ");
$TOTAL_HONORIUM_AMT = GetXFromYID("select sum(honorarium_amount) from crm_naf_hcp_details  where naf_main_id in (" . implode(", ", $NAF_IDS) . ") ");
$pending_with_id = GetXFromYID("select pendingwithid from crm_naf_main where id='$rid' and deleted=0 ");

$NAF_ARRAY = GetXArrFromYID("select  t2.id
from crm_naf_main t1 inner join crm_naf_main t2 on t1.naf_no=t2.parent_id 
where t1.id='$rid' and t1.deleted=0");

$HCP_DATA = GetDataFromID('crm_naf_hcp_details', 'naf_main_id', $rid, "and deleted=0 ");

$_q = "select * from crm_naf_hcp_details where naf_main_id in (" . implode(",", $NAF_ARRAY) . ") and deleted=0 ";
$_r = sql_query($_q, "");

//echo $MODE;

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
		<div class="pageTitle camp">DELIVERY OF SERVICE FORM (Marketing team)</div>
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


	<?php include '_tabscamp2.php'; ?>

	<div id="appCapsule">

		<form action="Save_MK_Dos.php" method="post" id="FORM_MKDOS">
			<input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
			<input type="hidden" id="rid" name="rid" value="<?php echo $rid; ?>">
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
											<input type="text" class="form-control" id="emp_name" value="<?php echo $employee_name; ?>" placeholder="">
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
										<b>Name of the Activity:<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="activity_name" name="activity_name" value="<?php echo (isset($activity_name)) ? $activity_name : ''; ?>" required="" <?php echo $readonly; ?>>
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
												<input type="number" class="form-control" value="<?php echo $TOTAL_HONORIUM_AMT; ?>" id="total_AMT" readonly>
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

								<div class="wide-block pt-2 pb-2">
									<form>
										<div class="form-group basic">
											<div class="input-wrapper">
												<label class="label" for="">Overall Outcome</label>
												<textarea id="" rows="3" name="comment" class="form-control"><?php echo $comment; ?></textarea>
											</div>
										</div>
									</form>
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

							<a href="<?php echo '_approve_dos_camp.php?userid=' . $USER_ID . '&mode=A&rid=' . $rid; ?>"><button type="button" class="btn btn-success mr-1 mb-1 pd-sr">Approve</button></a>

							<a href="<?php echo '_approve_dos_camp.php?userid=' . $USER_ID . '&mode=R&rid=' . $rid; ?>"><button type="button" class="btn btn-danger mr-1 mb-1 pd-sr">Reject</button></a>



						</div>
					</div>


				<?php   }
				?>



				<div class="section full mt-2">
					<div class="wide-block pt-2 pb-2">

						<div class="row">
							<!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
						</div> -->
							<button type="button" class="btn btn-primary rounded me-1 " onclick="showAllHCP()"><b>View all HCP</b></button>

							<?php

							if ($MODE != 'R') { ?>
								<div class="col-4">
									<button type="submit" class="exampleBox btn btn-primary rounded me-1">Submit</button>
								</div>

							<?php	}

							?>



							<!-- <div class="col">
							<a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
						</div> -->
						</div>

					</div>
				</div>


			</div>

		</form>

		<div class="section mt-7">
			<div class="card">
				<div class="card-body">

					<div class="wide-block pt-2 pb-2 showhcp" style="display: none;">

						<table id="example" class="display mt-2" style="width:100%">

							<thead>

								<tr>
									<th></th>
									<th>Sr. No</th>
									<th>HCP Universal ID</th>
									<th>Event ID</th>
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
								if (sql_num_rows($_r)) {
									for ($i = 0; $o = sql_fetch_object($_r); $i++) {
										$k = $i + 1;
										$hcp_id = $o->hcp_id;
										$masterid = GetXFromYID("select masterid from contactdetails where contactid='$hcp_id' ");
										$hcp_name = GetXFromYID("SELECT CONCAT(firstname, ' ', lastname) AS full_name FROM contactmaster where id='$hcp_id' ");
										$hcp_address = $o->hcp_address;
										$hcp_pan = $o->hcp_pan;
										$hcp_qualification = $o->hcp_qualification;
										$hcp_associated_hospital_id = $o->hcp_associated_hospital_id;
										$govt_type = $o->govt_type;
										$yr_of_experience = $o->yr_of_experience;
										$naf_main_id=$o->naf_main_id;
										$naf_no=GetXFromYID("select naf_no from crm_naf_main where id='$naf_main_id' and deleted=0 ");
										$naf_request_main_id=GetXFromYID("SELECT t2.id FROM crm_naf_main t1 INNER JOIN crm_request_main t2 ON t2.naf_no = t1.naf_no where t1.id=$naf_main_id ");
										$role_of_hcp = $o->role_of_hcp;
										$honorarium_amount = $o->honorarium_amount;
										$mobile = $o->mobile;
										$url = $hcp_tabs . "?rid=$naf_main_id&userid=$USER_ID&pid=$naf_request_main_id&display_all=1";
								?>
										<tr>
											<td></td>
											<td><?php echo $k; ?></td>
											<td><?php echo $masterid; ?></td>
											<td><?php echo $naf_no;?></td>
											<td><a href="<?php echo $url;?>" target="_blank" rel="noopener noreferrer"><?php echo $hcp_name; ?></a></td>
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
		function showAllHCP() {
			document.getElementsByClassName("showhcp")[0].style.display = "block";
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