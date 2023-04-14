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
    <meta name="description" content="Expense Module">
    <meta name="keywords" content="bootstrap 5, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
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
        <div class="pageTitle">Need Assessment Form</div>
        <div class="right"></div>
    </div>
	
    <div id="appCapsule">

	<div class="section mt-7">
		
		
		
        <div class="card">
        <div class="card-body">
  
  
			<div class="wide-block pt-2 pb-2">

							<div class="row">
							
								<div class="col-4">
									<div style="display:flex;">
									
										<div class="block1">
											<b>User Name:</b>
										</div>
										
										<div class="block2">
											<div class="input-wrapper">
												<input type="text" class="form-control" id="" placeholder="" required="">
											</div>
										</div>
									
									</div>
								</div>
								
								<div class="col-4">
								
									<div style="display:flex;">
									
									<div class="block1">
										<b>Pending with User:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="" placeholder="" required="">
										</div>
									</div>
									
									</div>
									
								</div>
								
								<div class="col-4">
								</div>
								
							</div>
							
							<br>
							

							<div class="row">
							
								<div class="col-4">
									<div style="display:flex;">
									
									<div class="block1">
										<b>Application From Date:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
											<input type="date" class="form-control" id="" placeholder="" required="">
										</div>
									</div>
									
									</div>
								</div>
								
								<div class="col-4">
									<div style="display:flex;">
									
									<div class="block1">
										<b>Application To Date:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
											<input type="date" class="form-control" id="" placeholder="" required="">
										</div>
									</div>
									
									</div>
								</div>
								
								<div class="col-4">
								
									<div style="display:flex;">
									
									<div class="block1">
										<b>PSA Ref.No:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="" placeholder="" required="">
										</div>
									</div>
									
									</div>
									
								</div>
								
							</div>
							
							<br>
							
							<div class="row">
							
						
								
								<div class="col-4">
								
									<div style="display:flex;">
									
									<div class="block1">
										<b>HCP Name:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="" placeholder="" required="">
										</div>
									</div>
									
									</div>
									
								</div>
								
								<div class="col-4">
								
																
									<div style="display:flex;">
									
									<div class="block1">
										<b>Status:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
										<select class="form-control custom-select" id="" required="">
											<option selected="" disabled="" value="">Choose...</option>
											<option value="1">Option 1</option>
											<option value="2">Option 2</option>
											<option value="3">Option 3</option>
										</select>
										</div>
									</div>
									
									</div>
									
									
								</div>
								
								<div class="col-4">
								</div>
								
							</div>
							
							
							
		<div class="section full mt-2"><a>
            </a><div class="wide-block pt-2 pb-2"><a>

                <!--<div class="row">
                    <div class="col"><a href="naf_form.html"><button type="button" class="exampleBox btn btn-primary rounded me-1">Need Assessment Form</button></a>
                    </div>
                    <div class="col">
                        <a href="pma.html"><button type="button" class="exampleBox btn btn-primary rounded me-1">PMA</button></a>
                    </div>
                    <div class="col">
                        
                    </div>
                </div>-->
				
				<div class="row">
                    <div class="col col-f">
						<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="naf-btn custom-select" id="" placeholder="Need Assessment Form"  required="">
							<option selected="" disabled="" value="">Need Assessment Form</option>
							<option value="naf_form_pma.php">NAF</option>
						</select>
                    </div>
					
					<div class="col col-f">
						<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="naf-btn custom-select" id="" required="">
							<option selected="" disabled="" value="">Project Market Analysis</option>
							<option value="search_pma.php">PMA</option>
						</select>
                    </div>
					
					<div class="col col-f">
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
				
					<div class="card-body pd-1">	
					
					
					
			<table id="example" class="display mt-2" style="width:100%">
				
				<thead>

					<tr >
						<th class="bg-purple"></th>
						<th class="bg-purple">Event ID</th>
						<th class="bg-purple">Date of NAF initiation</th>
						<th class="bg-purple">Division</th>
						<th class="bg-purple">Category</th>
						<th class="bg-purple">Total Estimated<br>Cost of the NAF(Rs)</th>
						<th class="bg-purple">Status</th>
						<th class="bg-purple">Current Pending with</th>
						<th class="bg-purple">Approved by</th>
						<th class="bg-purple">Edit/Delete</th>
						<th class="bg-purple">Add Questionnaire</th>
						<th class="bg-purple">Status</th>
						<th class="bg-purple">Pending with</th>
					</tr>
				</thead>
				<tbody>
                    <tr>
						<th></th>
						<td>APMACAR10004</td>
						<td>13/01/23</td>			  
						<td>Micro Gratia</td>
						<td>PMA</td>
						<td>30,000</td>
                        <td>Approved</td>
						<td></td>
						<td>BU Head</td>
						<td><a href="#"><button type="button" class="btn btn-upload shadowed  mt-1 me-1 mb-1">Edit/Delete</button></a></td>
						<td class="text-center"><i class="fa fa-plus-circle" aria-hidden="true"></i></td>
						<td>Approved</td>
						<td></td>
					</tr> 
					<tr>
						<th></th>
						<td>APMACAR10002</td>
						<td>14/01/23</td>			  
						<td>Micro Gratia</td>
						<td>PMA</td>
						<td>40,000</td>
                        <td>Approved</td>
						<td></td>
						<td>BU Head</td>
						<td><a href="#"><button type="button" class="btn btn-upload shadowed  mt-1 me-1 mb-1">Edit/Delete</button></a></td>
						<td class="text-center"><i class="fa fa-plus-circle" aria-hidden="true"></i></td>
						<td>Approved</td>
						<td></td>
					</tr> 
					<tr>
						<th></th>
						<td>APMACAR10026</td>
						<td>15/01/23</td>			  
						<td>Micro Gratia</td>
						<td>PMA</td>
						<td>35,000</td>
                        <td>Approved</td>
						<td></td>
						<td>BU Head</td>
						<td><a href="#"><button type="button" class="btn btn-upload shadowed  mt-1 me-1 mb-1">Edit/Delete</button></a></td>
						<td class="text-center"><i class="fa fa-plus-circle" aria-hidden="true"></i></td>
						<td>Approved</td>
						<td></td>
					</tr> 
					<tr>
						<th></th>
						<td>APMACAR10001</td>
						<td>08/02/23</td>			  
						<td>Micro Gratia</td>
						<td>PMA</td>
						<td>1,00,000</td>
                        <td>Pending</td>
						<td>Medical Head</td>
						<td></td>
						<td><a href="#"><button type="button" class="btn btn-upload shadowed  mt-1 me-1 mb-1">Edit/Delete</button></a></td>
						<td class="text-center"><i class="fa fa-plus-circle" aria-hidden="true"></i></td>
						<td></td>
						<td></td>
					</tr> 
			
	
				
				</tbody>
				</table>
					

					</div>
				</div>
			</div>
				
	


    </div>
  

    <!-- ============== Js Files ==============  -->
    <!-- Bootstrap -->
    <script src="assets/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="assets/js/plugins/splide/splide.min.js"></script>
    <!-- ProgressBar js -->
    <script src="assets/js/plugins/progressbar-js/progressbar.min.js"></script>
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

</body>

</html>