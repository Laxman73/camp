<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/common.php";


$page_title = 'Request Letter';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$prid = (isset($_GET['prid'])) ? $_GET['prid'] : '';
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

if ($mode=='A') {

	//crm_request_main
  $request_no='';
  $naf_no='';
  $category_id='';
  $requestor_id='';
  $requestor_empcode='';
  $submitted_on='';
  $submitted='';
  $pendingwithid='';
  $authorise='';
  $approved_date='';
  $e_sign_doctor='';
  $cheque_path='';
  $e_sign_cheque_date='';
  $remarks='';

  //crm_request_camp_letter
 
  $hcp_id='';
  $nature_of_camp='';
  $proposed_camp_date=TODAY;
  $proposed_camp_location='';
  $proposed_camp_duration='';

}elseif ($mode=='E') {
	$CRM_CAMP_LETTER=GetDataFromID('crm_request_camp_letter','crm_request_main_id',$pid,"");
	$hcp_id=db_output2($CRM_CAMP_LETTER[0]->hcp_id);
	$nature_of_camp=db_output2($CRM_CAMP_LETTER[0]->nature_of_camp);
	$proposed_camp_date=db_output2($CRM_CAMP_LETTER[0]->proposed_camp_date);
	$proposed_camp_location=db_output2($CRM_CAMP_LETTER[0]->proposed_camp_location);
	$proposed_camp_duration=db_output2($CRM_CAMP_LETTER[0]->proposed_camp_duration);
}



// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");

$HCP_UNIVERSAL_ID = GetXArrFromYID("select contactid,masterid from contactdetails order by contactid LIMIT 500 ", "3");

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
		<div class="pageTitle">Camp Request letter</div>
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


		<?php include '_tabscamp.php'; ?>

	<div id="appCapsule">

<form action="save_camp_letter.php" method="post">
	<input type="hidden" name="userid" value="<?php echo $USER_ID;?>">
	<input type="hidden" name="rid" value="<?php echo $rid;?>">
	<input type="hidden" name="pid" value="<?php echo $pid;?>">
		<div class="tab-content mt-1">



			<div class="section mt-7">
				<div class="card">
					<div class="card-body">

						<div class="wide-block pt-2 pb-2">



							<div class="row">



								<div class="col-3">
									<b>HCP Universal ID:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<select class="form-control custom-select"  id="DoctorID" name="DoctorID" required="">
											<option value="">--select--</option>
											<?php
											foreach ($HCP_UNIVERSAL_ID as $key => $value) {
												//echo $value[$key]['iModID'];
												$selected =    ($hcp_id == $key) ? 'selected' : '';
												echo '<option value="' . $key . '"'.$selected.' >' . $value . '</option>';
											}
											?>
										</select>
									</div>
								</div>
							</div>

							<br>

							<div class="row">



								<div class="col-3">
									<b>Nature of the camp:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="nature_of_camp" value="<?php echo $nature_of_camp;?>" name="nature_of_camp" placeholder="" required>
									</div>
								</div>
							</div>

							<br>


							<div class="row">



								<div class="col-3">
									<b>Proposed Camp Date:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="date" class="form-control" value="<?php echo $proposed_camp_date;?>" name="camp_date" id="" min="<?php echo TODAY;?>" required>
									</div>
								</div>

							</div>



							<br>

							<div class="row">



								<div class="col-3">
									<b>Proposed Camp Location:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper"><input type="text" value="<?php echo $proposed_camp_location;?>" class="form-control" id="camp_location" name="camp_location" required></div>
								</div>

							</div>

							<br>


							<div class="row">


								<div class="col-3">
									<b>Proposed Camp Duration:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" value="<?php echo $proposed_camp_duration;?>" id="camp_duration" name="camp_duration" placeholder="" required>
									</div>
								</div>

							</div>





						</div>



					</div>
				</div>
			</div>


			
			<div class="section full mt-2">
	
	
	
				<div class="section full mt-2">
					<div class="wide-block pt-2 pb-2">
	
						<div class="row">
							<!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
							</div> -->
							<div class="col">
								<button type="submit" class="exampleBox btn btn-primary rounded me-1">Submit</button>
							</div>
							<!-- <div class="col">
								<a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
							</div> -->
						</div>
	
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