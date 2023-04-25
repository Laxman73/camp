<?php
//set_time_limit(0);
/* error_reporting(E_ALL);
ini_set('display_errors', '1'); */
include 'includes/common.php';

$disp_url = 'index_pma_camp.php';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}


$DIVISION_ARR = GetXArrFromYID("select divisionid,name from division ", '3');

$current_userid = $USER_ID;
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $current_userid . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$CONTACT_DETAILS_ARR = GetXArrFromYID("select contactid,masterid from contactdetails order by contactid limit 500", '3');
$STATUS_ARR = GetXArrFromYID("select pid,status_name from crm_status_master ", '3');


// if(  $PROFILE_ID!='15' || $PROFILE_ID!='7' || $PROFILE_ID!='6' )
// {
// 	echo 'Invalid Access Detected!!!';
// 	exit;
// }

$params = $cond = $params2 = $txtkeyword = $initiater_name = $naf_activity_name = $txtDfrom = $txtDto = '';
$execute_query = false;
if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
	$txtkeyword = $_POST['naf_number'];
	$initiater_name = $_POST['initiater'];
	$naf_activity_name= $_POST['naf_activity_name'];
	$txtDfrom = $_POST['txtDfrom'];
	$txtDto = $_POST['txtDto'];

	$params = '&naf_number=' . $txtkeyword . '&initiater=' . $initiater_name . '&naf_activity_name=' . $naf_activity_name . '&txtDfrom=' . $txtDfrom . '&txtDto=' . $txtDto . '&userid=' . $USER_ID;
	header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
	$is_query = true;

	if (isset($_GET['naf_number'])) $txtkeyword = $_GET['naf_number'];
	if (isset($_GET['initiater'])) $initiater_name = $_GET['initiater'];
	if (isset($_GET['naf_activity_name'])) $naf_activity_name = $_GET['naf_activity_name'];
	if (isset($_GET['txtDfrom'])) $txtDfrom = $_GET['txtDfrom'];
	if (isset($_GET['txtDto'])) $txtDto = $_GET['txtDto'];


	$params2 =  '&naf_number=' . $txtkeyword . '&initiater=' . $initiater_name . '&naf_activity_name=' . $naf_activity_name . '&txtDfrom=' . $txtDfrom . '&txtDto=' . $txtDto . '&userid=' . $USER_ID;
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
if (!empty($naf_activity_name)) {
	$cond .= " and (t1.naf_activity_name LIKE '%" . $naf_activity_name . "%')";
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

$CONTACT_DETAILS = array();
$_Q = "select id,firstname,lastname from contactmaster limit 500";
$_R = sql_query($_Q, '');
if (sql_num_rows($_R)) {
	while (list($id, $firstname, $lastname) = sql_fetch_row($_R)) {
		if (!isset($CONTACT_DETAILS[$id])) {
			$CONTACT_DETAILS[$id] = array('id' => $id, 'firstname' => $firstname, 'lastname' => $lastname);
		}
	}
}


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
		<div class="pageTitle">PMA LISTING</div>
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
											<b>PMA Reference Number</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="text" value="<?php echo $txtkeyword; ?>" name="naf_number" class="form-control" id="naf_number" placeholder="">
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
												<input type="text" value="<?php echo $initiater_name; ?>" name="initiater" class="form-control" id="initiater" placeholder="">
											</div>
										</div>

									</div>
								</div>	
								<div class="col-6">
									<div style="display:flex;">

										<div class="block1">
											<b>NAF Activity Name:</b>
										</div>

										<div class="block2">
											<div class="input-wrapper">
												<input type="text" value="<?php echo $naf_activity_name; ?>" name="naf_activity_name" class="form-control" id="naf_activity_name" placeholder="">
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
												<input type="date" value="<?php echo $txtDfrom; ?>" name="txtDfrom" class="form-control" id="txtDfrom" placeholder="">
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
												<input type="date" value="<?php echo $txtDto; ?>" name="txtDto" class="form-control" id="txtDto" placeholder="">
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
											<div class="col"><button type="submit" class="exampleBox btn btn-primary rounded me-1">SEARCH</button>
											</div>
											<div class="col">
												<button type="reset"  onclick="GoToPage('<?php echo $disp_url.'?userid='.$USER_ID; ?>');" class="exampleBox btn btn-primary rounded me-1">RESET</button>
											</div>
											<div class="col">

											</div>
										</div>

								</div>
							</div>

							<div class="row">
								<!-- <div class="col col-f">
						<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="naf-btn custom-select" id="" placeholder="Need Assessment Form"  required="">
							<option selected="" disabled="" value="">Need Assessment Form</option>
							<option value="naf_form_pma.php?userid=<?php echo $USER_ID; ?>">NAF</option>
						</select>
                    </div> -->

								<?php

								if (($PROFILE_ID == 6) || ($PROFILE_ID == 7)) { ?>

									<div class="col col-f">
										<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="naf-btn custom-select" id="">
											<option selected="" disabled="" value="">New Request</option>
											<option value="pma_search.php?userid=<?php echo $USER_ID; ?>">PMA</option>
										</select>
									</div>
								<?php	}
								?>

								<div class="col col-f">
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
								<th>Event ID</th>
								<th>NAF Activity Name</th>
								<th>Date of NAF initiation</th>
								<th>Dr Name</th>
								<th>PMA Reference Number</th>
								<th>Category</th>
								<th>HCP Universal ID</th>
								<th>Division</th>
								<th>Requestor Name</th>
								<th>Pending with</th>

								<!-- <th>Approved by</th> -->
								<th>Status</th>

							</tr>
						</thead>
						<tbody>
							<?php

							$_q = "select t1.id ,t2.level,t1.initiator,t1.date,t1.naf_no,t2.request_no,t2.authorise,t2.submitted_on,t2.requestor_id,t2.pendingwithid,t2.id as pmaID,t1.naf_activity_name from  crm_naf_main t1 inner join crm_request_main t2 on t1.naf_no=t2.naf_no  where 1 and  t2.deleted=0 and t2.category_id=12 " . $cond . "   order by t2.id DESC";


							$_r = sql_query($_q, "");
							if (sql_num_rows($_r)) {
								for ($i = 1; $o = sql_fetch_object($_r); $i++) {
									$crm_naf_ID=$o->id;
									$subitted_on = $o->submitted_on;
									$pendingwithID = $o->pendingwithid;
									$naf_no = $o->naf_no;
									$PMA_level = $o->level;
									$requestor_id = $o->requestor_id;
									$PMArefno = $o->request_no; //Pma reference NUmber
									$PMAID = $o->pmaID; //id of crm_request_main
									$naf_date = $o->date;
									$PMauthorise = $o->authorise;
									$NAFinitiator = $o->initiator;
									$rid = $o->id; //ID of crm_naf_main table
									$naf_activity_name= $o->naf_activity_name;

									$hcpid = GetXFromYID("select hcp_id from  crm_request_camp_letter where crm_request_main_id='$PMAID' ");
									// $PENDING_WITH_ID=GetXFromYID("select pendingwithid from crm_request_main where naf_no='$naf_no' ");
									// $REQ_ID=GetXFromYID("select requestor_id from crm_request_main where naf_no='$naf_no' ");

									$fname = GetXFromYID("select CONCAT(first_name, ' ', last_name) AS full_name from users where id='$pendingwithID' ");

									$division = GetXFromYID("SELECT division FROM users WHERE id='$requestor_id' ");
									$x_total = GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$rid' ");
									$url = 'request_letter_camp.php?rid='.$crm_naf_ID.'&userid='.$USER_ID;

									//state head	24676
									// if ($PROFILE_ID == '15') {
									// 	if (($PMA_level == 1 && $USER_ID==$pendingwithID && $PMauthorise == 1)) {
									// 		//PMA Approval
									// 		$url = 'pma_approve.php?rid=' . $rid . '&userid=' . $USER_ID . '&pid=' . $PMAID; 
									// 	} elseif ($PMA_level == 2) {
									// 		//Approved/Rejected
									// 		$url = 'pma_details.php?rid=' . $rid . '&userid=' . $USER_ID . '&pid=' . $PMAID; 
									// 	} elseif ($PMA_level == 3 && $PMauthorise == 1 && $USER_ID==$pendingwithID) {
									// 		// HCP Approval
									// 		$url = 'naf_form_pma.php?rid=' . $rid . '&userid=' . $USER_ID . '&pid=' . $PMAID;
									// 	} elseif ($PMA_level >= 4) {
									// 		$url = 'naf_form_pma.php?rid=' . $rid . '&userid=' . $USER_ID . '&pid=' . $PMAID;
									// 	} 
									// } elseif ($PROFILE_ID == '6' || $PROFILE_ID == '7') { 
									// 	//AM user										
									// 	if (($PMA_level == 1) || ($PMA_level == 2 && $PMauthorise == 4) ) { 
									// 		// Rejected
									// 		$url = 'pma_details.php?rid=' . $rid . '&userid=' . $USER_ID . '&pid=' . $PMAID;
									// 	} elseif (($PMA_level == 2 && $PMauthorise == 2) || ($PMA_level >= 3)) {
									// 		$url = 'hcp_form.php?rid=' . $rid . '&userid=' . $USER_ID . '&pid=' . $PMAID;
									// 	} 
									// }
							?>
									<tr>
										<td><?php echo $naf_no; ?></td>
										<td><?php echo $naf_activity_name; ?></td>
										<td><?php echo $naf_date; ?></td>
										<td><a href="<?php echo $url; ?>"><?php echo 'Dr ' . $CONTACT_DETAILS[$hcpid]['firstname'] . ' ' . $CONTACT_DETAILS[$hcpid]['lastname']; ?></a></td>
										<td><?php echo $PMArefno; ?></td>
										<td>PMA <?php //echo 'level=>'.$PMA_level.' Authorise=>'.$PMauthorise.'url=>'.$url;
												?></td>
										<td><?php echo 'HCP-' . $CONTACT_DETAILS_ARR[$hcpid]; ?></td>
										<td><?php echo (isset($DIVISION_ARR[$division])) ? $DIVISION_ARR[$division] : ''; ?></td>
										<td><?php echo $NAFinitiator; ?></td>
										<td><?php
											if ($PMauthorise == '1') {

												echo $fname;
											}

											?></td>

										<!-- <td class="text-center"></td> -->
										<td><?php echo isset($STATUS_ARR[$PMauthorise]) ? $STATUS_ARR[$PMauthorise] : ''; ?></td>

									</tr>


								<?php	} ?>

							<?php	} else {
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
		$(document).ready(function() {
			var table = $('#example').DataTable({
				responsive: true,
				ordering: false
			});
		});
	</script>

</body>

</html>