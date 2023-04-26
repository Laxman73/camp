<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/common.php";


$page_title = 'HCP Form';
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

if (empty($rid) || (!empty($rid) && !is_numeric($rid))) {
	echo 'Invalid Access Detected!!!';
	exit;
}

if (empty($pid) || (!empty($pid) && !is_numeric($pid))) {
	$mode='A';
}else {
	$mode='E';
}

// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");

$HCP_UNIVERSAL_ID = GetXArrFromYID("select contactid,masterid from contactdetails order by contactid LIMIT 500 ", "3");

$years = range(1900, strftime("%Y", time()));


$requestorID=GetXFromYID("select userid from crm_naf_main where id='$rid' and deleted=0");
$submited_date =GetXFromYID("select submitted_on from crm_naf_main where id='$rid' and deleted=0 ");

$User_division = GetXFromYID("select division from users where id='$requestorID' ");//Getting user division 
$curr_dt = date('Y-m-d', strtotime($submited_date));//current date from the date of sub,ission of NAF
$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");

$cond = '';
if (!empty($User_division) && (isset($User_division))) {
    $cond .= " and division=$User_division ";
}

$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear'  " . $cond, '3');

$PMA_DATA = GetDataFromID('crm_request_camp_letter', 'crm_request_main_id', $pid);

$DoctorID = $PMA_DATA[0]->hcp_id; //Doctors ID


$_q1 = "select firstname,lastname,qualification,mobile,otherstate,othercity from contactmaster where id='$DoctorID' limit 500";
$_r1 = sql_query($_q1, "");

list($firstname, $lastname, $qualificationid, $mobile, $otherstate, $othercity) = sql_fetch_row($_r1);
$state = GetXFromYID("select statename from state where stateid='$otherstate'");
$_c_q = "select cityname,pincode from city where cityid='$othercity' ";
$_c_r = sql_query($_c_q, '');
list($city, $pincode) = sql_fetch_row($_c_r);

$qualification_name=GetXFromYID("select qualificationname from qualification where qualificationid='$qualificationid'");
$_q2 = "select hoscontactid from approval_doctor_hospital_association where contactid='$DoctorID' ";
$_r2 = sql_query($_q2);
list($hostcontactid) = sql_fetch_row($_r2);
$hospital_name = (isset($HOSPITAL_ARR[$hostcontactid])) ? $HOSPITAL_ARR[$hostcontactid] : '';

if ($mode=='A') {
	$yr_of_registration='';
  $no_of_yr_experience_doctor='';
  $speciality_id='';
  $no_of_publication='';
  $part_of='';
  $speaker='';
  $part_of_peer='';
  $position='';
  $no_of_yr_experience_clinic='';
  $hcp_sign='';
  $hcp_sign_date='';
  $emp_sign='';
  `emp_sign_date` datetime NOT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `submitted` int(1) NOT NULL,
}


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
		<div class="pageTitle">HCP INFORMATION FORM</div>
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

		<?php include '_tabscamp.php';?>


	<div id="appCapsule">

		<form action="save_hcp_camp.php" method="POST">
			<input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
			<input type="hidden" name="rid" value="<?php echo $rid; ?>">
			<input type="hidden" name="pid" value="<?php echo $pid; ?>">
			<div class="tab-content mt-1">



				<div class="section mt-7">
					<div class="card">
						<div class="card-body">

							<div class="wide-block pt-2 pb-2">



								<div class="row">



									<div class="col-3">
										<b>HCP'S Name<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" name="hcp_name" id="hcp_name" value="<?php echo $firstname . ' ' . $lastname; ?>" required>
										</div>
									</div>
								</div>

								<br>

								<div class="row">



									<div class="col-3">
										<b>HCP'S Address<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="hcp_address" name="hcp_address" required>
										</div>
									</div>
								</div>

								<br>


								<div class="row">



									<div class="col-3">
										<b>Education Qualification<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" name="hcp_qual" id="hcp_qual" value="<?php echo $qualification_name; ?>" required>
										</div>
									</div>

								</div>


								<br>

								<div class="row">


									<div class="col-3">
										<b>Year of Registration with Medical Council<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<select name="yr_of_registration" class="form-control" required>
												<option>Select Year</option>
												<?php foreach ($years as $year) {
													$selected =  '';
												?>
													<option value="<?php echo $year; ?>" <?php echo $selected; ?>><?php echo $year; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

								</div>







								<br>

								<div class="row">



									<div class="col-3">
										<b>No. of years of experience after completion of highest qualification<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" id="NUm_of_yr_exp" name="NUm_of_yr_exp" required>
										</div>
									</div>

								</div>

								<br>


								<div class="row">


									<div class="col-3">
										<b>Area of specialization(Therapeutic area)<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
										<select class="form-control custom-select" name="speciality_id" id="speciality_id" required="">
												<option value="">Choose...</option>
												<?php
												foreach ($SPECILITY_ARR as $key => $value) {
													//echo $value[$key]['iModID'];
													$selected =  '';
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
										<b>Number of Publications (in peer reviewed publications or textbook authorship)<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper">
											<input type="number" class="form-control" id="NUM_of_publications" name="NUM_of_publications" required="">
										</div>
									</div>

								</div>


								<br>

								<div class="row">



									<div class="col-3">
										<b>Part of National / International / Regional Medical Scientific Society or Sub-committes<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" name="part_of_national" class="form-control" id="part_of_national" required></div>
									</div>

								</div>

								<br>

								<div class="row">


									<div class="col-3">
										<b>Speaker at International / National / Regional forums (in past 3 years)<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" name="speaker" id="speaker" required></div>
									</div>

								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Part of Peer Reviewed Editorial Board<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" name="part_of_peer" class="form-control" id="part_of_peer" required></div>
									</div>

								</div>


								<br>

								<div class="row">

									<div class="col-3">
										<b>Current or previous position as full-time Professor / Assistant Professor at a Teaching Institution / Corporate Hospital<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="text" class="form-control" name="position" id="position" ></div>
									</div>

								</div>

								<br>

								<div class="row">


									<div class="col-3">
										<b>No. of years of clinical trial experience<span style="color:#ff0000">*</span></b>
									</div>

									<div class="col-9">
										<div class="input-wrapper"><input type="number" name="num_of_years_of_clinical_expr" class="form-control" id="num_of_years_of_clinical_expr" required></div>
									</div>

								</div>




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
								<!-- <a href="service_agreement_camp.php"> -->
								<button type="submit" class="exampleBox btn btn-primary rounded me-1">Submit</button>
								<!-- </a> -->
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