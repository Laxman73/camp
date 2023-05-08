<?php
include "includes/common.php";
$SPECILITY_ARR = GetXArrFromYID('select specialityid as id,specialityname as name from speciality where in_use=0  order by name ASC', '3');
$PRODUCTS_ARR=GetXArrFromYID('select productid as id ,productname as name from products where deleted=0 order by productname ASC','3');
$CITY_ARR=GetXArrFromYID("SELECT cityid as id, concat_ws('',cityname,if(city.metrononmetro=1,'(Metro)','(Non-Metro)')) as name FROM city where deleted=0 ORDER BY cityname",'3');
$ACTIVITY_ARR=GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0','3');
$_q="select field_id,field_name from crm_naf_fields_master where deleted=0 ";
$_r=sql_query($_q,"");

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
		<div class="pageTitle">Need Assessment Form</div>
		<div class="right"></div>
	</div>


	<div class="section full mt-7">
		<div class="wide-block pt-2 pb-2">

			<div class="row">
				<div class="col-2">
					<a href="#">
						<div class="tabBox">NAF Form</div>
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
	</div>


	<div id="appCapsule">


		<div class="tab-content mt-1">

			<form action="save_naf.php" method="POST" >


			<div class="section mt-2">
				<div class="card">
					<div class="card-body">


						<div class="wide-block pt-2 pb-2">

							<div class="row">

								<div class="col-3">
									<b>Request Date:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="request_date" name="request_date" placeholder="<?php echo "" . date("d/m/Y") . ""; ?>" disabled>
									</div>

								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Product / Therapy:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<select class="form-control custom-select" id="Product" name="productID" required="">
											<option  disabled="" value="">Choose...</option>
											<?php
											foreach ($PRODUCTS_ARR as $key => $value) {
												//echo $value[$key]['iModID'];
												//$selected =    ($SELECTED_AMENITIES[$key]['iVAID'] == $key) ? 'selected' : '';
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
									<b>ParentID:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<b>NAF number will be generated once the form is submitted</b>
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
										<input type="text" name="Details_of_event" class="form-control" id="" placeholder="" required="">
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
										<select name="Nature_of_activity" class="form-control custom-select" id="" required="">
										<?php
											foreach ($ACTIVITY_ARR as $key => $value) {
												//echo $value[$key]['iModID'];
												//$selected =    ($SELECTED_AMENITIES[$key]['iVAID'] == $key) ? 'selected' : '';
												echo '<option value="' . $key . '" >' . $value . '</option>';
											}

											?>
										</select>
									</div>
								</div>
							</div>

						</div>
						<!--1st--->


						<!--2nd--->
						<div class="wide-block pt-2 pb-2">

							<div class="row">

								<div class="col-3">
									<b>Date of the Activity:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="date" name="date_of_activity" class="form-control" id="" required="">
									</div>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>City :</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<select class="form-control custom-select" id="city" name="city" required="">
										<?php
											foreach ($CITY_ARR as $key => $value) {
												//echo $value[$key]['iModID'];
												//$selected =    ($SELECTED_AMENITIES[$key]['iVAID'] == $key) ? 'selected' : '';
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
									<b>Proposed Venue:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" name="propose_value" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>

							<br>

							<div class="row">

								<div class="col-3">
									<b>Estimated Number of Participnts:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" name="Num_of_Participants" placeholder="" required="">
									</div>
								</div>
							</div>


							<br>

							<div class="row">

								<div class="col-3">
									<b>Targeted Speciality:<span style="color:#ff0000">*</span></b>
								</div>

								<!--<div class="col-9">
									<div class="input-wrapper">
										<select class="form-control custom-select" id="" required="">
											<option selected="" disabled="" value="">Choose...</option>
											<option value="1">option 1</option>
											<option value="2">option 2</option>
											<option value="3">option 3</option>
										</select>
									</div>
								</div>-->

								<div class="col-9">
									<div class="input-wrapper">
										<select class="form-control custom-select" name="targetedSpeciality[]" multiple id="targetedSpeciality">
											<?php
											foreach ($SPECILITY_ARR as $key => $value) {
												//echo $value[$key]['iModID'];
												//$selected =    ($SELECTED_AMENITIES[$key]['iVAID'] == $key) ? 'selected' : '';
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
									<b>Budgeted Activity Cost Details</b><!--<br>Kindly complete all the relevant cost elements to be incurred):<span style="color:#ff0000">*</span>-->
								</div>

								<div class="col-9">
								</div>

							</div>

						</div>
						<!--2nd--->



						<!--3rd--->
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
						<!--3rd--->


						<!--4th--->
						<div class="wide-block pt-2 pb-2">

														<?php

								foreach ($_r as $row) { ?>
									

									<div class="row">
									
										<div class="col-3">
											<b><?php echo $row['field_name'];?><span style="color:#ff0000">*</span></b>
										</div>
										
										<div class="col-9">
											<div class="input-wrapper">
												<input type="number" value="0" onkeyup="CalculateTotal();" name="crm_<?php echo $row['field_id'];?>" class="form-control" id="crm_<?php echo $row['field_id'];?>" placeholder="" required="">
											</div>
										</div>
									</div>
									<br>

								

								<?php }	?>

							<!-- <div class="row">

								<div class="col-3">
									<b>HCP Honorrium (fill 'Appendix-1' with HCP details):<span style="color:#ff0000">*</span></b>
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
									<b>Local Travel:</b>
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
									<b>Accommodation / Stay:</b>
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
									<b>Flight:</b>
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
									<b>Meal / Snack:</b>
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
									<b>Venue charges</b>
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
									<b>Audio Visual / Webex:</b>
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
									<b>Banners / Pamphlets:</b>
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
									<b>Sponsorship:</b>
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
									<b>Diagnostic cost:</b>
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
									<b>Medical equipment cost:</b>
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
									<b>Other additional cost, if any:</b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>

							<br> -->

							<div class="row">

								<div class="col-3">
									<b>TOTAL AMOUNT:<span style="color:#ff0000">*</span></b>
								</div>

								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" id="total" name="total" class="form-control"  placeholder="" required="">
									</div>
								</div>

							</div>



						</div>
						<!--4th--->





						
					</div>
				</div>
			</div>


			<!--1st--->












			<div class="section full mt-2">
				<div class="wide-block pt-2 pb-2">

					<div class="row">
						<div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
						</div>
						<div class="col">
							<button type="submit" class="exampleBox btn btn-primary rounded me-1">Submit</button>
						</div>
						<div class="col">
							<a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
						</div>
					</div>

				</div>
			</div>



			</form>



		</div>



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
	<script>
		//CalculateTotal();
		function CalculateTotal(){
			let total=0;
			<?php
			 foreach ($_r as $row) {
			?>
			total+=parseFloat($(`#crm_<?php echo $row['field_id'];?>`).val());
		<?php	 }


			?>
			console.log(parseInt(total));
			$('#total').val(total);
		}
	</script>

</body>

</html>