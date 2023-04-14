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
	
	
		
	<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">


	<!--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
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
        <div class="pageTitle camp">Need Assessment Form (By Activity Requestor)</div>
        <div class="right"></div>
    </div>


         <!-- <div class="section full mt-7">
            <div class="wide-block pt-2 pb-2">

                <div class="row">
                    <div class="col-2">
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
                    </div>
                </div>

            </div>
        </div>-->
	

    <div id="appCapsule">


        <div class="tab-content mt-7">


						
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
											<input type="text" class="form-control" id="" placeholder="<?php echo "" . date("d/m/Y") . "";?>" disabled>
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
										<select class="form-control custom-select" id="myselection1" required>
											<option selected="" disabled="" value="">Choose...</option>
											<option value="1">Betagel</option>
											<option value="2">Calosoft</option>
											<option value="3">Coverit</option>
											<option value="4">Diflorate</option>
											<option value="5">Fexofast</option>
											<option value="6">Others</option>
										</select>
									</div>
								</div>
							</div>
							
							
							
							
							
							
							
							


							<div id="showoption6" class="myDiv1">
							<div class="row">
							
								<div class="col-3">
									
								</div>
								
								<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="" placeholder="">
										</div>
										
								</div>
							</div>
							</div>
								
							
							
							
							
							<br>
							
							<div class="row">
							
								<div class="col-3">
									<b>Parent ID:<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" required>
									</div>
								</div>
							</div>
							
							
							
							<br>
							
							<div class="row">
							
								<div class="col-3">
									<b>Event ID:<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" required>
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
										<input type="text" class="form-control" id="" placeholder="" required>
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
										<input type="text" class="form-control" id="" placeholder="" required>
									</div>
								</div>

							</div>
							
							
							
							<br>
							
							<div class="row">
							
								<div class="col-3">
									<b>Date of the Activity :<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="<?php echo "" . date("d/m/Y") . "";?>" disabled>
									</div>
								</div>

							</div>
							
							
							<br>
							
							<div class="row">
							
								<div class="col-3">
									<b>City( Metro and Non Metro):</b>
								</div> 
								
								<div class="col-9">
									<div class="input-wrapper">
										<select class="form-control custom-select" id="" required>
											<option selected="" value="">Choose...</option>
											<option value="1">Mumbai</option>
											<option value="2">Chennai</option>
											<option value="3">pune</option>
											<option value="4">Kolkata</option>
											<option value="5">Kanpur</option>
											<option value="6">Delhi</option>
										</select>
									</div>
								</div>
							</div>
							
							
							<!--<br>
							
														
							<div class="row">
							
								<div class="col-3">
									<b>Virtual/ Offline<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<select class="form-control custom-select" id="" required>
											<option selected="" value="">Choose...</option>
											<option value="1">Virtual</option>
											<option value="2">Offline</option>
										</select>
									</div>
								</div>
								
							</div>
							
                           
					        <br>

							<div class="row">
							
								<div class="col-3">
									<b>Date of the Activity:<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="" placeholder="<?php echo "" . date("d/m/Y") . "";?>" disabled>
										</div>
								</div>
							</div>-->
							

							
							<br>
							
							<div class="row">
							
								<div class="col-3">
									<b>Proposed Venue:</b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" required>
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
										<input type="text" class="form-control" id="" placeholder="" required>
									</div>
								</div>
							</div>


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
									<b>Is this an Advance Payment? If yes,please mention the advance amount:</b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<select class="form-control custom-select" id="myselection" required="">
											<option selected="" disabled="" value="">Choose...</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
									</div>
								</div>
								
							</div>	


							<div id="showYes" class="myDiv">
								<form>
									<div class="custom-file-upload">
										<input type="file" id="fileuploadInput" accept=".png, .jpg, .jpeg">
										<label for="fileuploadInput">
											<span>
												<strong>
													<ion-icon name="cloud-upload-outline"></ion-icon>
													<i>Tap to Upload</i>
												</strong>
											</span>
										</label>
									</div>
								</form>
							</div>
							
							
						
						</div>
						
  
  
  
  
  
  
                </div>
            </div>
        </div>
						
						
		<div class="section mt-2">
			<div class="card">
			<div class="card-body">
				
				<div class="form-group basic">
					<div class="input-wrapper">
						<label class="label" for="">The objective/rationale/need for conducting an Activity:<span style="color:#ff0000">*</span></label>
						<textarea id="" rows="3" class="form-control"></textarea>
					</div>
				</div>				
			</div>
			</div>
		</div>
					
						
						
	<div class="section mt-2">
    <div class="card">
    <div class="card-body">

						<div class="row">
							
								<div class="col-9">
									<b>Budgeted Activity Cost Details</b> Kindly complete all the relevant cost elements to be incurred)
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
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
										<input type="number" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
								
							</div>		
							
							

						</div>
	
       
        </div>
        </div>
        </div>
						
								
						
						
		<div class="section mt-2">
		<div class="card">         
			<div class="card-body pd-1">	
					
					
					
			<table id="example" class="display mt-2" style="width:100%">
				
				<thead>

					<tr >
						<th></th>
						<th>Sr. No</th>
						<th>HCP Universal ID</th>
						<th>Name of the HCP</th>
						<th>Address(city)</th>
						<th>PAN #</th>
						<th>Qualification</th>
						<th>Associated Hospital/Clinic</th>
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
						<td>
							<div class="input-wrapper">
								<select class="form-control custom-select" id="" required>
									<option selected="" value="">Choose...</option>
									<option value="1">Option 1</option>
									<option value="2">Option 2</option>
								</select>
							</div>
						</td>
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
				
		<div class="section mt-2">
			<div class="card">
			<div class="card-body">
				<div class="form-group basic">
					<div class="input-wrapper">
						<label class="label" for="">rationale for selection:<span style="color:#ff0000">*</span></label>
						<textarea id="" rows="3" class="form-control"></textarea>
					</div>
				</div>
										
			</div>
			</div>
		</div>
      	
			
	<div class="section">
        <div class="card">
        <div class="card-body">

            <div class="wide-block pt-2 pb-2">

				<div class="row">
					<div class="col-3">
						Team Head: ___________________
					</div>
					<div class="col-3">
						Stead Head: ___________________
					</div>
					<div class="col-3">
						Marketing Head: ___________________
					</div>
					<div class="col-3">
						BU Head: ___________________
					</div>
				</div>

				<br><br>
				
				<div class="row">
					<div class="col-3">
						Designation: ___________________
					</div>
					<div class="col-3">
						Signature: ___________________
					</div>
					<div class="col-3">
						Signature: ___________________
					</div>
					<div class="col-3">
						Signature: ___________________
					</div>
				</div>
							
				<br><br>
							
				<div class="row">
					<div class="col-3">
						Signature: ___________________
					</div>
					<div class="col-3">
						Date: ___________________
					</div>
					<div class="col-3">
						Date: ___________________
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
				</div>
							
			</div>


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
                        <a href="hcp_form_consultancy.php"><button type="button" class="exampleBox btn btn-primary rounded me-1">Submit</button></a>
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
	<script>
	jQuery('#targetedSpeciality').multiselect({
		columns: 1,
		placeholder: 'Choose...',
		search: true
	});
	</script>
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
		$(document).ready(function() {
		var table = $('#example').DataTable( {
			rowReorder: {
				selector: 'td:nth-child(2)'
			},
			responsive: true
		} );
		} );
	</script>
	
	<script>
	$(document).ready(function(){
		$('#myselection').on('change', function(){
			var demovalue = $(this).val(); 
			$("div.myDiv").hide();
			$("#show"+demovalue).show();
		});
	});
	</script> 
	
	<script>
	$(document).ready(function(){
		$('#myselection1').on('change', function(){
			var demovalue = $(this).val(); 
			$("div.myDiv1").hide();
			$("#showoption"+demovalue).show();
		});
	});
	</script> 
	

	

</body>

</html>