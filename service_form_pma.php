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
        <div class="right"></div>
    </div>


       <div class="section full mt-7">
            <div class="wide-block pt-2 pb-2">

                <div class="row">
				    <div class="col-2">
                        <a href="naf_form_pma.php"><div class="tabBox">NAF FORM</div></a>
                    </div>
                    <div class="col-2">
                        <a href="#"><div class="tabBox">DELIVERY OF SERVICE FORM</div></a>
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
										<input type="text" class="form-control" id="" placeholder="Balbima Madar" required="">
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
										<input type="text" class="form-control" id="" placeholder="PMA" required="">
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
										<select class="form-control custom-select" id="Product" required="">
											<option selected="" disabled="" value="">Choose...</option>
											<option value="1">Virtual</option>
											<option value="2">Offline</option>
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
										<input type="text" class="form-control" id="" placeholder="PMA" required="">
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
										<input type="text" class="form-control" id="" placeholder="Activity 1" required="">
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
										<select class="form-control custom-select" id="Product" required="">
											<option selected="" disabled="" value="">Choose...</option>
											<option value="1">Yes</option>
											<option value="2">No</option>
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
										<input type="text" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>
							
							<br>
	
							<div class="row">
							
								<div class="col-3">
									<b>Description of the Vendor:<span style="color:#ff0000">*</span></b>
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
									<b>Nature of Actual Cost Vendor:</b>
									<input type="text" class="form-control" id="" placeholder="" required="">
								</div>
								
								<div class="col-9">
								</div>
							</div>
							
							<br>
	
							<div class="row">
							
								<div class="col-3">
									<b>Travel-Flights:</b>
									<input type="text" class="form-control" id="" placeholder="" required="">
								</div>
								
								<div class="col-9">
									<b>Insurance:</b>
									<input type="text" class="form-control" id="" placeholder="" required="">
								</div>
							</div>
							
							<br>
	
							<div class="row">
							
								<div class="col-3">
									<b>Travel-Cab:</b>
									<input type="text" class="form-control" id="" placeholder="" required="">
								</div>
								
								<div class="col-9">
									<b>Visa:</b>
									<input type="text" class="form-control" id="" placeholder="" required="">
								</div>
							</div>
							
							<br>
	
							<div class="row">
							
								<div class="col-3">
									<b>Stay/Hotel:</b>
									<input type="text" class="form-control" id="" placeholder="" required="">
								</div>
								
								<div class="col-9">
									<b>Audio/Visual:</b>
									<input type="text" class="form-control" id="" placeholder="" required="">
								</div>
							</div>
							
							<br>
	
							<div class="row">
							
								<div class="col-3">
									<b>Meal/Snacks:</b>
									<input type="text" class="form-control" id="" placeholder="" required="">
								</div>
								
								<div class="col-9">
									<b>Banners/Pamphelts:</b>
									<input type="text" class="form-control" id="" placeholder="" required="">
								</div>
							</div>
							
							<br>
							
							<div class="row">
							
								<div class="col-3">
									<b>Others(Please Specify):</b>
									<input type="text" class="form-control" id="" placeholder="" required="">
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
									<b>Meals / Snack:</b>
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
									<b>Sponsorship/Participations:</b>
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
									<b>Grants:</b>
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
							
							<br>
							
							<div class="row">
							
								<div class="col-3">
									<b>TOTAL AMOUNT:<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
								
							</div>		
							
							

						</div>
						
  
  
  
  
  
  
                </div>
            </div>
        </div>
						
						
						
					
						
						
						
						
						
			

      	
             
			 
		
	
            <div class=" pt-2 pb-2" style="width: 100%;justify-content: center;text-align: center;">

            <a href="post_activity_pma.php"><button type="button" class="btn btn-primary rounded me-1 "><b>View all HCP</b></button></a>
                   

            </div>
        
		
		
		
        



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


</body>

</html>