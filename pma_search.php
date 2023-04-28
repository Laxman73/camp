<?php
include 'includes/common.php';
$disp_url = 'pma_search.php';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}

$current_userid = $USER_ID;
$typeid = (isset($_GET['typeid'])) ? $_GET['typeid'] : ''; // Added by Nitin Dahale for ticket id 0146686


$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $current_userid . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$DIVISION_ARR = GetXArrFromYID("select divisionid,name from division ", '3');


$parent_id = GetXFromYID("SELECT parent_id FROM `crm_naf_type_master` WHERE `pid` = '".$typeid."'");


$params = $cond = $params2 = $txtkeyword = $initiater_name = $txtDfrom = $txtDto = '';
$execute_query = false;
if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
	$txtkeyword = $_POST['naf_number'];
	$initiater_name = $_POST['initiater'];
	$txtDfrom = $_POST['txtDfrom'];
	$txtDto = $_POST['txtDto'];
	$params = '&naf_number=' . $txtkeyword . '&initiater=' . $initiater_name . '&txtDfrom=' . $txtDfrom . '&txtDto=' . $txtDto . '&userid=' . $USER_ID;
	header('location: ' . $disp_url .'?userid='.$current_userid.'3&typeid='.$typeid.'&srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
	$is_query = true;

	if (isset($_GET['naf_number']))
		$txtkeyword = $_GET['naf_number'];
	if (isset($_GET['initiater']))
		$initiater_name = $_GET['initiater'];
	if (isset($_GET['txtDfrom']))
		$txtDfrom = $_GET['txtDfrom'];
	if (isset($_GET['txtDto']))
		$txtDto = $_GET['txtDto'];


	$params2 = '&naf_number=' . $txtkeyword . '&initiater=' . $initiater_name . '&txtDfrom=' . $txtDfrom . '&txtDto=' . $txtDto . '&userid=' . $USER_ID;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
	SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($txtkeyword)) {
	$cond .= " and (naf_no LIKE '%" . $txtkeyword . "%')";
	$execute_query = true;
}
if (!empty($initiater_name)) {
	$cond .= " and (initiator LIKE '%" . $initiater_name . "%')";
	$execute_query = true;
}
if (!empty($txtDfrom)) {
	$cond .= " and submitted_on>='$txtDfrom' ";
	$execute_query = true;
}
if (!empty($txtDto)) {
	$cond .= " and submitted_on<='$txtDto' ";
	$execute_query = true;
}





?>
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
		<div class="pageTitle">NAF SEARCH</div>
		<div class="right">
			<a href="http://88.99.140.102/MicrolabReplicav3/index.php?action=index&module=Home">
				<ion-icon name="home-outline" class="icon-color"></ion-icon>
			</a>
		</div>
	</div>

	<div id="appCapsule">

		<div class="section mt-7">



			<div class="card">
				<div class="card-body">

					<form action="" method="POST">
						<input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
						<div class="wide-block pt-2 pb-2">

							<div class="row">

								<div class="col-6">
									<div style="display:flex;">

										<div class="block1">
											<b>NAF Reference Number</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="text" value="<?php echo $txtkeyword; ?>" name="naf_number"
													class="form-control" id="naf_number">
											</div>
										</div>

									</div>
								</div>


							</div>


							<br>


							<div class="row">

								<div class="col-6">

									<div style="display:flex;">

										<div class="block1">
											<b>Initiator Name:</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="text" value="<?php echo $initiater_name; ?>"
													name="initiater" class="form-control" id="initiater">
											</div>
										</div>

									</div>
								</div>


							</div>





							<br>


							<div class="row">

								<div class="col-6">
									<div style="display:flex;">

										<div class="block1">
											<b>From Date:</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="date" value="<?php echo $txtDfrom; ?>" name="txtDfrom"
													class="form-control" id="txtDfrom">
											</div>
										</div>

									</div>
								</div>

								<div class="col-6">
									<div style="display:flex;">

										<div class="block1">
											<b>To Date:</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="date" value="<?php echo $txtDto; ?>" name="txtDto"
													class="form-control" id="txtDto">
											</div>
										</div>

									</div>
								</div>

							</div>

							<br>





							<div class="section full mt-2"><a>
								</a>
								<div class="wide-block pt-2 pb-2"><a>

										<div class="row">
											<div class="col"><button type="submit"
													class="exampleBox btn btn-primary rounded me-1">SEARCH</button>
											</div>
											<div class="col">
												<button type="reset"
													onclick="GoToPage('<?php echo $disp_url . '?userid=' . $USER_ID; ?>');"
													class="exampleBox btn btn-primary rounded me-1">RESET</button>
											</div>
											<div class="col">

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
								<th></th>
								<th>Event ID</th>
								<th>Date of NAF initiation</th>
								<th>Division</th>
								<th>Category</th>
								<th>Total Estimated<br>Cost of the NAF(Rs)</th>

								<th>Approved by</th>
								<!-- <th>Edit/Delete</th> -->
								<!-- <th>Add Questionnaire</th> -->
								<th>Status</th>
								<!-- <th>Pending with</th> -->
							</tr>
						</thead>
						<tbody>
							<?php

							//$_q="select id ,naf_no,submitted_on,authorise,pendingwithid from  crm_naf_main  where 1 and deleted=0 and authorise=2 and level=4    ".$cond ." order by submitted_on desc";
							
							//Modified by Nitin Dahale for Ticket id 0146686
							 $_q = "select id ,naf_no,submitted_on,authorise,pendingwithid,type_name
									from  crm_naf_main 
									INNER JOIN crm_naf_type_master ON crm_naf_main.category_id = crm_naf_type_master.pid
									where crm_naf_main.deleted=0
									and crm_naf_type_master.deleted=0
									and authorise = 2
									and category_id = $parent_id
									and level=4    " . $cond . " ORDER BY submitted_on desc";


							$_r = sql_query($_q, "");
							if (sql_num_rows($_r)) {
								for ($i = 1; $o = sql_fetch_object($_r); $i++) {

									$crm_request_main_ID = $o->id;
									$subitted_on = $o->submitted_on;
									$naf_no = $o->naf_no;
									$pedingwithid = $o->pendingwithid;	
									$rid = $o->id;
									//$stat = $o->status;
									$status = ($o->authorise == '1') ? 'Pending' : 'Approve';
									$type_name = $o->type_name;
									$url = '';
									//added by Nitin Dahale for Ticket id 0146686
									 $select_crm_naf_type = "SELECT pid,file_name FROM `crm_naf_type_master` WHERE request_type = 'request_type' UNION SELECT pid,file_name FROM `crm_naf_type_master` WHERE request_type = 'naf_type' AND pid IN (5,8)"; 
									/* $select_crm_naf_type = "SELECT pid,file_name FROM `crm_naf_type_master` WHERE pid=$typeid"; */
									$res_crm_naf_type_query = sql_query($select_crm_naf_type, '');

									/* if ($PROFILE_ID == '6' || $PROFILE_ID == '7')  */
								/* 	if ($PROFILE_ID == '15'){ */
										
										if ($o->authorise == '2') {
											//$url='hcp_form.php?rid='. $crm_request_main_ID.'&userid='.$USER_ID;		
							
											//$url='pma.php?rid='.$rid.'&userid='.$USER_ID; //Removed by Nitin

											//added by Nitin Dahale for Ticket id 0146686
											while ($row = sql_fetch_array($res_crm_naf_type_query)) {
												if ($typeid == $row['pid']) {
													//type_name.php from crm_naf_type_master without space and lowercase
													if ($typeid==5 || $typeid==8)													
														$url = $row['file_name'].'?prid='.$rid.'&userid='.$USER_ID.'&typeid='.$typeid;
													else	
														$url = $row['file_name'].'?rid='.$rid.'&userid='.$USER_ID.'&typeid='.$typeid;
													
													break;
												}
											}
										}


							/* 		} */
									/* if ($PROFILE_ID == '15') {
										$url = 'pma_approve.php?rid=' . $crm_request_main_ID . '&userid=' . $USER_ID;
									} */

									$_user_q = "SELECT division FROM users WHERE id = $pedingwithid";
									$_user_r = sql_query($_user_q, '');
									list($division) = sql_fetch_row($_user_r);
									$fname = GetXFromYID("select CONCAT(first_name, ' ', last_name) AS full_name from users where id='$pedingwithid' ");


									$x_total = GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$rid' ");
									?>
									<tr>
										<th></th>
										
											<td><a href="<?php echo $url; ?>"><?php echo $naf_no; ?></a></td>
									
										<td>
											<?php echo FormatDate($subitted_on, 'H'); ?>
										</td>
										<td>
											<?php echo $DIVISION_ARR[$division]; ?>
										</td>
										<td>
											<?php echo $type_name; ?>
										</td>
										<td>
											<?php echo $x_total; ?>
										</td>
										<td>
											<?php echo $fname; ?>
										</td>

										<!-- <td><a href="#"><button type="button" class="btn btn-upload shadowed  mt-1 me-1 mb-1">Edit/Delete</button></a></td> -->
										<!-- <td class="text-center"><i class="fa fa-plus-circle" aria-hidden="true"></i></td> -->
										<td>
											<?php echo $status; ?>
										</td>
										<!-- <td></td> -->
									</tr>


								<?php } ?>

							<?php } else {
								echo 'No records Found';
							} ?>


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

		function GoToPage(page) {
			window.document.location.href = page;
		}
		$(document).ready(function () {
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