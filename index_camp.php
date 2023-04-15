<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/common.php";


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
//$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division
$SPECILITY_ARR = array();

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


	<div class="section full">
		<div class="wide-block pt-2 pb-2">

			<div class="row">
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
			</div>

		</div>
	</div>


	<div id="appCapsule">


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
										<input type="text" class="form-control" id="" placeholder="<?php echo "" . date("d/m/Y") . ""; ?>" disabled>
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
										<select class="form-control custom-select" name="Quarter[]" multiple id="Quarter">
											<option value="1">January to March</option>
											<option value="2">April to June</option>
											<option value="3">July to September</option>
											<option value="4">October to December</option>
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
									<b>Activity Details (for Blanket Approval):</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">

										<b>Type of Activity (Please tick the appropriate box)</b>

										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
											<label class="custom-control-label" for="customRadio1">Project Market Analysis</label>
										</div>
										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
											<label class="custom-control-label" for="customRadio2">Round Table Meeting</label>
										</div>
										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio3" name="customRadio" class="custom-control-input">
											<label class="custom-control-label" for="customRadio3">Consultancy Engagement</label>
										</div>

									</div>
								</div>

							</div>


							<br>

							<div class="row">

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

							</div>


							<br>


							<div class="row">

								<div class="col-3">
									<b>Proposed Count of Activities:<span style="color:#ff0000">*</span></b>
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
									<b>Proposed Number of HCPS's:<span style="color:#ff0000">*</span></b>
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
									<b>Proposed Nature of the Activity:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<select class="form-control custom-select" name="targetedSpeciality[]" multiple id="targetedSpeciality">
											<option value="1">Consultant Physician</option>
											<option value="2">Dermatologist</option>
											<option value="3">General Physician</option>
											<option value="4">Gynaecologist</option>
											<option value="5">Plastic Surgeon</option>
										</select>
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
										<input type="text" class="form-control" id="" placeholder="" required="">
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
										<input type="text" class="form-control" id="" placeholder="" required="">
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
										<input type="text" class="form-control" id="" placeholder="" required="">
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
										<input type="text" class="form-control" id="" placeholder="" required="">
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
										<input type="text" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>


							<!--<br>							
							<div class="row">
							
								<div class="col-3">
									<b>Deviation from the approved amount,if any:<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>-->

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
						<div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
						</div>
						<div class="col">
							<a href="naf_camp.php"><button type="button" class="exampleBox btn btn-primary rounded me-1">Submit</button></a>
						</div>
						<div class="col">
							<a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
						</div>
					</div>

				</div>
			</div>







		</div>



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
		jQuery('#Quarter').multiselect({
			columns: 1,
			placeholder: 'Choose...',
			search: true
		});
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