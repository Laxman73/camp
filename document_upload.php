<?php
include 'includes/common.php';
$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$prid = (isset($_GET['prid'])) ? $_GET['prid'] : '';//Parent Id
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$MODE = (isset($_GET['mode'])) ? $_GET['mode'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

//if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))|| $USER_ID!='2')
if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}
$disp_url = 'index_pma.php';
if (empty($rid)) {
	header('location:' . $disp_url);
}

//getting role and profile of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");

// defining mode
if (!empty($rid) && $PROFILE_ID == 22) {
	$MODE = 'R';
}

if ($PROFILE_ID == 6 || $PROFILE_ID == 7) {
	$MODE = 'E';
	$issubmitted = GetXFromYID("select count(*) from crm_naf_document_upload where crm_request_main_id = $pid  and deleted=0");
	if ($issubmitted == 0) {
		$MODE = 'I';
	}
} else if ($PROFILE_ID == 15 || $PROFILE_ID == 11) {
	$MODE = 'E';
}


$_qr = sql_query("select level,authorise from crm_request_main where id = $pid");
list($Plevel, $PisAuthorise) = sql_fetch_row($_qr);

// defining variables
$pan_image = $chaque_image = $visiting_image = '';

if ($MODE == 'R' || $MODE == 'E') {

	// code to get the attachments
	$_q = "select document_type_id,file_path from crm_naf_document_upload where crm_request_main_id='$pid' and deleted=0 ";
	$_R = sql_query($_q, '');
	$ATTACHMENT_ARR = array();
	if (sql_num_rows($_R)) {
		while (list($type, $filepath) = sql_fetch_row($_R)) {
			if (!isset($ATTACHMENT_ARR[$type])) {
				$ATTACHMENT_ARR[$type] = $filepath;
			}
		}
	}

	// getting data from the array
	if (!empty($ATTACHMENT_ARR)) {
		$pan_image = isset($ATTACHMENT_ARR[1]) && !empty($ATTACHMENT_ARR[1]) ? $ATTACHMENT_ARR[1] : '';
		$chaque_image = isset($ATTACHMENT_ARR[2]) && !empty($ATTACHMENT_ARR[2]) ? $ATTACHMENT_ARR[2] : '';
		$visiting_image = isset($ATTACHMENT_ARR[3]) && !empty($ATTACHMENT_ARR[3]) ? $ATTACHMENT_ARR[3] : '';
	}
	// DFA($ATTACHMENT_ARR);
	// exit;
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
	<meta name="description" content="Mobilekit HTML Mobile UI Kit">
	<meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="manifest" href="__manifest.json">
</head>

<body>


	<div class="appHeader bg-primary">
		<div class="left">
			<a href="javascript:;" class="headerButton goBack">
				<ion-icon name="chevron-back-outline"></ion-icon>
			</a>
		</div>
		<div class="pageTitle">Documents Upload</div>
		<div class="right">
			<a href="index_pma.php?userid=<?php echo $USER_ID; ?>">
				<ion-icon name="home-outline" class="icon-color"></ion-icon>
			</a>
		</div>
	</div>


	<?php include '_tabscamp.php'; ?>


	<div id="appCapsule">

		<form action="upload_doc.php" id="DOC_FORM" method="post" enctype="multipart/form-data">
			<input type="hidden" name="rid" value="<?php echo $rid; ?>">
			<input type="hidden" name="pid" value="<?php echo $pid; ?>">
			<input type="hidden" name="mode" value="<?php echo $MODE; ?>">
			<input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
			<div class="tab-content mt-1">



				<div class="section mt-2">
					<div class="card">
						<div class="card-body">

							<div class="wide-block pt-2 pb-2">

								<p><b>Upload attendance  file here:<span style="color:#ff0000">*(Max 3 MB)</span></b></p>
								<div class=" PANDIV">
									<?php if ($MODE != 'R') {
										if (!empty($pan_image)) {
											if ($PROFILE_ID==15) {
												echo '<div id="pan_SRC"><img src="' . $pan_image . '"  alt="PAN Image"  style="width:30%;height:auto;"><button type="button"  class="btn btn-danger DeletePansrc">Delete</button></div>';
											}else{
												echo '<div id="pan_SRC"><img src="' . $pan_image . '"  alt="PAN Image"></div>';

											}
										} else { ?>
											<input type="file" id="attendance_file" name="attendance_file"  class="form-control">
										<?php	}

										?>
									<?php
									} else { ?>
										<input type="file" id="attendance_file" name="attendance_file"  class="form-control">
										

									<?php	}
									?>
								</div>
								<?php  ?>
							</div>


						
						</div>
					</div>
				</div>



				<?php //if (($PROFILE_ID == 15 && $Plevel == 3) || (($PROFILE_ID == '6' || $PROFILE_ID == '7') && ($Plevel == 2))) { ?>
					<div class="section full mt-2">
						<div class="wide-block pt-2 pb-2">

							<div class="row">
								<!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
								</div> -->
								<div class="col">
									<button type="submit" id="BtnSUBMIT" class="exampleBox btn btn-primary rounded me-1">Submit</button>
								</div>
								<!-- <div class="col">
									<a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
								</div> -->
							</div>

						</div>
					</div>
				<?php //} ?>
			</div>
		</form>

		<?php if ($PROFILE_ID == 15 && $Plevel == 3) { ?>
			<div class="section full mt-2">
				<div class="wide-block pt-2 pb-2 text-center">
					<a href="approve_hcp_final.php?rid=<?php echo $rid; ?>&userid=<?php echo $USER_ID; ?>&pid=<?php echo $pid; ?>&mode=A"><button type="button" class="btn btn-success mr-1 mb-1 pd-sr">Approve</button></a>
					<a href="approve_hcp_final.php?rid=<?php echo $rid; ?>&userid=<?php echo $USER_ID; ?>&pid=<?php echo $pid; ?>&mode=R"><button type="button" class="btn btn-danger mr-1 mb-1 pd-sr">Reject</button></a>
				</div>
			</div>
		<?php } ?>


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
	<script>
		function ShowError(element, mesg) {
			var spanID = element + "_span";

			if ($(element).hasClass("is-invalid")) {

			} else {
				$(element).addClass("is-invalid");
				$('<span id="' + spanID + '";" class="invalid-feedback em">' + mesg + '</span>').insertAfter(element);
			}
		}

		function HideError(element) {
			var elemID = $(element).attr('id');
			var spanID = elemID + "_span";

			$(element).removeClass("is-invalid");
			$('#' + spanID).remove();
		}






		$('#DOC_FORM').submit(function() {
			var ret = true;
			console.log($('#pan').length);
			if ($('#pan').length) {
				var pan = $('#pan')[0].files;
				if (pan.length > 0){
					if (pan[0].size > 3000000) {
					alert('Pan Card size should be less than 3 MB');
					ret = false;
				}

				}else {
					alert('Please select the file');
					ret = false;
					
				}
			}

			if ($('#cancel_check').length) {
				var cancel_check = $('#cancel_check')[0].files;
				if (cancel_check.length > 0) {
					if (cancel_check[0].size > 3000000) {
					alert('Cancel check size should be less than 3 MB');
					ret = false;
				}
					
				}else {
					alert('Please select the file');
					ret = false;
					
				}
				
			}


			if ($('#visiting_card').length) {
				if ($('#visiting_card').length>0) {
					var visiting_card = $('#visiting_card')[0].files;
					if (visiting_card[0].size > 3000000) {
						alert('Visiting card size should be less than 3 MB');
						ret = false;
					}				
					
				} else {
					alert('Please select the file');
					ret = false;
					
				}
			}

			// if ($('#visiting_card').length || $('#cancel_check').length || $('#pan').length ) {
				
			// }else{
			// 	alert('Please upload the files before submitting');
			// 	ret=false;
			// }



			// if (pan.length > 0 && cancel_check.length > 0 && visiting_card.length > 0) {
			// 	if (pan[0].size > 3000000) {
			// 		alert('Pan Card size should be less than 3 MB');
			// 		ret = false;
			// 	}
			// 	if (cancel_check[0].size > 3000000) {
			// 		alert('Cancel check size should be less than 3 MB');
			// 		ret = false;
			// 	}
			// 	if (visiting_card[0].size > 3000000) {
			// 		alert('Visiting card size should be less than 3 MB');
			// 		ret = false;
			// 	}
			// } else {
			// 	alert('Please select  all the  files ');
			// 	ret = false;
			// }

			if (ret) {
				//BtnSUBMIT
				$('#BtnSUBMIT').prop('disabled', true); //disable button upload existing		
			}


			return ret;

		});

		$(document).ready(function() {
			$(document).on('click', '.DeletePansrc', function() {
				$('#pan_SRC').remove();
				$(".PANDIV").empty();
				$(".PANDIV").append(`<input type="file" id="pan" name="pan" accept=".png, .jpg, .jpeg" class="form-control">`);


			});

			$(document).on('click', '.DeleteCancelSrc', function() {
				//$('#pan_SRC').remove();
				$(".CANCEL_CHECKDIV").empty();
				$(".CANCEL_CHECKDIV").append(`<input type="file" id="cancel_check" name="cancel_check" accept=".png, .jpg, .jpeg" class="form-control">`);


			});

			$(document).on('click', '.DeleteVisitingSrc', function() {
				//$('#pan_SRC').remove();
				$(".VISITING_CARD").empty();
				$(".VISITING_CARD").append(`<input type="file" id="visiting_card" name="visiting_card" accept=".png, .jpg, .jpeg" class="form-control">`);


			});
		});
	</script>


</body>

</html>