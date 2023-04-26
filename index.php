<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/common.php";

$page_title = 'Camp Quarter Listing';
$TITLE = SITE_NAME . ' | ' . $page_title;


$disp_url = 'index.php';

$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}

$STATUS_ARR = GetXArrFromYID("select pid,status_name from crm_status_master where deleted=0", '3');
$TYPE_ARR=GetXArrFromYID("select pid,type_name from crm_naf_type_master where deleted=0","3");
$DIVISION_ARR = GetXArrFromYID("select divisionid,name from division ", '3');
// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
//$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division


$params = $cond = $params2 = $txtkeyword = $initiater_name = $txtDfrom =$status= $txtDto = '';
$execute_query = false;

if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
	$txtkeyword = $_POST['naf_number'];
	$initiater_name = $_POST['initiater'];
	$txtDfrom = $_POST['txtDfrom'];
	$txtDto = $_POST['txtDto'];
	$status = $_POST['status'];

	$params = '&naf_number=' . $txtkeyword . '&initiater=' . $initiater_name . '&txtDfrom=' . $txtDfrom . '&txtDto=' . $txtDto . '&userid=' . $USER_ID.'&status='.$status;
	header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
	$is_query = true;

	if (isset($_GET['naf_number'])) $txtkeyword = $_GET['naf_number'];
	if (isset($_GET['initiater'])) $initiater_name = $_GET['initiater'];
	if (isset($_GET['txtDfrom'])) $txtDfrom = $_GET['txtDfrom'];
	if (isset($_GET['txtDto'])) $txtDto = $_GET['txtDto'];
	if (isset($_GET['status'])) $status = $_GET['status'];


	$params2 =  '&naf_number=' . $txtkeyword . '&initiater=' . $initiater_name . '&txtDfrom=' . $txtDfrom . '&txtDto=' . $txtDto . '&userid=' . $USER_ID.'&status='.$status;
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

if (!empty($status)) {
	$cond .= " and authorise='$status' ";
	$execute_query = true;
}

$_q="select * from crm_naf_main where 1 ".$cond ."and category_id='2' and deleted=0 ";
$_r=sql_query($_q,"");


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

								<!-- <div class="col-4">
									<div style="display:flex;">

										<div class="block1">
											<b>User Name:</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="text" class="form-control" id="username" name="username" placeholder="" >
											</div>
										</div>

									</div>
								</div> -->

								<div class="col-4">

									<div style="display:flex;">

										<div class="block1">
											<b>Initaiter:</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="text" class="form-control" value="<?php echo $initiater_name; ?>" id="initiater" name="initiater"  >
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
												<input type="date" class="form-control" id="txtDfrom" value="<?php echo $txtDfrom; ?>" name="txtDfrom"  >
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
												<input type="date" class="form-control" id="txtDto" value="<?php echo $txtDto; ?>" name="txtDto">
											</div>
										</div>

									</div>
								</div>

								<div class="col-4">

									<div style="display:flex;">

										<div class="block1">
											<b>NAF Ref.No:</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="text" class="form-control" id="naf_number" value="<?php echo $txtkeyword; ?>"  name="naf_number" >
											</div>
										</div>

									</div>

								</div>

							</div>

							<br>

							<div class="row">



								<!-- <div class="col-4">

									<div style="display:flex;">

										<div class="block1">
											<b>HCP Name:</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="text" class="form-control" id="" placeholder="" >
											</div>
										</div>

									</div>

								</div> -->

								<div class="col-4">


									<div style="display:flex;">

										<div class="block1">
											<b>Status:</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
											<?php echo FillCombo($status, 'status', 'COMBO', '0', $STATUS_ARR, '', 'form-control'); ?>
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
												<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="naf-btn custom-select" id="" placeholder="Need Assessment Form" >
													<option selected="" disabled="" value="">Need Assessment Form</option>
													<option value="index_camp.php?userid=<?php echo $USER_ID;?>">NAF</option>
												</select>
											</div>

											<div class="col col-f">
											<button type="submit" class="exampleBox btn btn-primary rounded me-1">SEARCH</button>
										
											</div>

											<div class="col col-f">
											
												<button type="reset"  onclick="GoToPage('<?php echo $disp_url.'?userid='.$USER_ID; ?>');" class="exampleBox btn btn-primary rounded me-1">RESET</button>
											
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
								
								<th class="bg-purple">Event ID</th>
								<th class="bg-purple">Date of NAF initiation</th>
								<th class="bg-purple">Division</th>
								<th class="bg-purple">Category</th>
								<th class="bg-purple">Total Estimated<br>Cost of the NAF(Rs)</th>
								<th class="bg-purple">Status</th>
								<th class="bg-purple">Pending with</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if (sql_num_rows($_r)) {
								for ($i = 1; $o = sql_fetch_object($_r); $i++) {
									$x_id=$o->id;
									$subitted_on = $o->submitted_on;
									$pendingwithID = $o->pendingwithid;
									$approved_status_id=$o->authorise;
									$naf_no = $o->naf_no;
									$x_rid=GetXFromYID("select id from crm_naf_main where parent_id='$naf_no' ");
									$NAF_level = $o->level;
									$categroy_id=$o->category_id;
									$budget_amount=$o->budget_amount;
									$requestor_id = $o->userid;
									$User_division = GetXFromYID("select division from users where id='$requestor_id' ");
									$fname = GetXFromYID("select CONCAT(first_name, ' ', last_name) AS full_name from users where id='$pendingwithID' ");
									$event_url="";
									if (($approved_status_id == 1 || $approved_status_id==2 ) && $USER_ID==$pendingwithID) {
										$event_url='<a href="approve_camp_q.php?prid='.$x_id.'&userid='.$USER_ID.'">'.strtoupper($naf_no).'</a>';
									}elseif ($approved_status_id==2 && $pendingwithID==0 && $NAF_level==4) {
										$event_url='<a href="naf_camp.php?prid='.$x_id.'&userid='.$USER_ID.'&rid='.$x_rid.'">'.strtoupper($naf_no).'</a>';
									} else{
										$event_url='<a href="javascript:void(0)">'.strtoupper($naf_no).'</a>';
									}
									//$PMArefno = $o->request_no; //Pma reference NUmber
									
									$naf_date = $o->date;
									$NAFauthorise = $o->authorise;
									$NAFinitiator = $o->initiator;
									?>
									<tr>
										
										<td><?php echo $event_url;?></td>
										<td><?php echo $naf_date;?></td>
										<td><?php echo (isset($DIVISION_ARR[$User_division])) ? $DIVISION_ARR[$User_division] : ''; ?></td>
										<td><?php echo $TYPE_ARR[$categroy_id];?></td>
										<td><?php echo $budget_amount;?></td>
										<td><?php echo $STATUS_ARR[$NAFauthorise];?></td>
										<td><?php echo $fname;?></td>
										
									</tr>
									
							<?php	}
							}

							?>
							



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
		$(document).ready(function() {
			var table = $('#example').DataTable({
				
				responsive: true
			});
		});
	</script>

</body>

</html>