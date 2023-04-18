<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/common.php";

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


$params = $cond = $params2 = $txtkeyword = $initiater_name = $txtDfrom = $txtDto = '';
$execute_query = false;
if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
	$txtkeyword = $_POST['naf_number'];
	$initiater_name = $_POST['initiater'];
	$txtDfrom = $_POST['txtDfrom'];
	$txtDto = $_POST['txtDto'];

	$params = '&naf_number=' . $txtkeyword . '&initiater=' . $initiater_name . '&txtDfrom=' . $txtDfrom . '&txtDto=' . $txtDto . '&userid=' . $USER_ID;
	header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
	$is_query = true;

	if (isset($_GET['naf_number'])) $txtkeyword = $_GET['naf_number'];
	if (isset($_GET['initiater'])) $initiater_name = $_GET['initiater'];
	if (isset($_GET['txtDfrom'])) $txtDfrom = $_GET['txtDfrom'];
	if (isset($_GET['txtDto'])) $txtDto = $_GET['txtDto'];


	$params2 =  '&naf_number=' . $txtkeyword . '&initiater=' . $initiater_name . '&txtDfrom=' . $txtDfrom . '&txtDto=' . $txtDto . '&userid=' . $USER_ID;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
	SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($txtkeyword)) {
	$cond .= " and (t2.request_no LIKE '%" . $txtkeyword . "%')";
	$execute_query = true;
}
if (!empty($initiater_name)) {
	$cond .= " and (t1.initiator LIKE '%" . $initiater_name . "%')";
	$execute_query = true;
}
if (!empty($txtDfrom)) {
	$cond .= " and t2.submitted_on>='$txtDfrom' ";
	$execute_query = true;
}
if (!empty($txtDto)) {
	$cond .= " and t2.submitted_on<='$txtDto' ";
	$execute_query = true;
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
		<div class="pageTitle">Need Assessment Form (Quarter activity)</div>
		<div class="right"></div>
	</div>

	<div id="appCapsule">

		<div class="section mt-7">



			<div class="card">
				<div class="card-body">

					<form action="" method="POST">
						<input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
						<div class="wide-block pt-2 pb-2">

							<div class="row">

								<div class="col-4">
									<div style="display:flex;">

										<div class="block1">
											<b>User Name:</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="text" class="form-control" id="username" name="username" placeholder="" required="">
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
								</a>
								<div class="wide-block pt-2 pb-2"><a>



										<div class="row">
											<div class="col col-f">
												<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="naf-btn custom-select" id="" placeholder="Need Assessment Form" required="">
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
					</form>
				</div>
			</div>



		</div>


		<div class="section mt-7">
			<div class="card">

				<div class="card-body pd-1">



					<table id="example" class="display mt-2" style="width:100%">

						<thead>

							<tr>
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