<?php

// tab urls

if ($display_all == 1) {
	$naf_form_url = "naf_form_consultancy.php?rid=$rid&userid=$USER_ID&display_all=1&category=$category";
	/* $hcp_form_url = "hcp_consultancy_detailview.php?rid=$rid&userid=$USER_ID&pid=$pid&display_all=1";
	$naf_agrmnt_url = "service_agreement_consultancy.php?rid=$rid&userid=$USER_ID&pid=$pid&display_all=1";


	$doc_upld_url = "document_upload_consultancy.php?rid=$rid&userid=$USER_ID&pid=$pid&display_all=1"; */
	$delivery_url = "delivery_service_form_consultancy.php?rid=$rid&userid=$USER_ID&pid=$pid&display_all=1&category=$category";
/* 	$pdf_url  = "pdf_consultancy.php?rid=$rid&userid=$USER_ID&pid=$pid&display_all=1"; */
	$post_comment_url  = "post_activity_consultancy.php?rid=$rid&userid=$USER_ID&display_all=1";
} else {
	$naf_form_url = "naf_form_consultancy.php?rid=$rid&userid=$USER_ID&category=$category";
	/* $hcp_form_url = "hcp_consultancy_detailview.php?rid=$rid&userid=$USER_ID&pid=$pid";
	$naf_agrmnt_url = "service_agreement_consultancy.php?rid=$rid&userid=$USER_ID&pid=$pid";



	$doc_upld_url = "document_upload_consultancy.php?rid=$rid&userid=$USER_ID&pid=$pid"; */
	$delivery_url = "delivery_service_form_consultancy.php?rid=$rid&userid=$USER_ID&category=$category";
/* 	$pdf_url  = "pdf_consultancy.php?rid=$rid&userid=$USER_ID&pid=$pid";*/
	$post_comment_url  = "post_activity_consultancy.php?rid=$rid&userid=$USER_ID"; 
}

$user_tabs = array();

$level = '';
$pid = (isset($pid)) ? $pid : '';

//RM/AM head
if (($PROFILE_ID == 6 || $PROFILE_ID == 7) && !empty($rid)) {

	/* $user_tabs = array(1); */

	$_Qnafmain = sql_query("select level,authorise from crm_naf_main where id='$rid' ");
	list($NAF_level, $NAF_isAuthorise) = sql_fetch_row($_Qnafmain);
	
	array_push($user_tabs, 1,2);
	
	$dos_info = GetXFromYID("select id from crm_naf_delivery_form where crm_naf_main_id = $rid");
			if (!empty($dos_info)) {
				array_push($user_tabs, 3);
			}

	
}

//State Head	24676
if ($PROFILE_ID == 15) {

	$_Qnafmain = sql_query("select level,authorise from crm_naf_main where id='$rid' ");
	list($NAF_level, $NAF_isAuthorise) = sql_fetch_row($_Qnafmain);

	//if ($NAF_level>=2) { //Sujjo 02042023
	if (($NAF_level == 4 && $NAF_isAuthorise == 2) || ($NAF_level > 4)) {
		if (!empty($pid)) {

			$user_tabs = array(1);

			$_qr = sql_query("select level,authorise from crm_request_main where id = $pid");
			list($level, $isAuthorise) = sql_fetch_row($_qr);


			if ($level >= 3) {
				array_push($user_tabs, 1);
			}

			// checking if hcp info form is submitted
			$hcp_info = GetXFromYID("select id from crm_hcp_information where crm_request_main_id = $pid");
			if (!empty($hcp_info)) {
				array_push($user_tabs, 2);
			}

			// checking if hcp agreement is submitted			
		/* 	if (!empty($hcp_info)) {
				$hcp_agreement = GetXFromYID("select count(*) from crm_hcp_agreement where crm_hcp_information_id = $hcp_info");
				if (!empty($hcp_agreement)) {
					array_push($user_tabs, 3);
				}
			} */

			// checking if questioneer is submitted
		/* 	if (!empty($pid)) {
				$ques_submitted = GetXFromYID("select count(*) from crm_questionnaire where crm_request_main_id = $pid");
				if (!empty($ques_submitted)) {
					array_push($user_tabs, 4);
				}
			} */

			//checking if documents are uploaded
			$uploaded = GetXFromYID("select count(*) from crm_naf_document_upload where crm_request_main_id = $pid and deleted = 0");

			if ($uploaded > 0) {
				if ($level == 5) {
					array_push($user_tabs, 5);
				}
			}
		}
	}
}

//Medical Team 
if ($PROFILE_ID == 11 || $PROFILE_ID == 22) {

	if ($display_all == 1) {
      array_push($user_tabs,1,2);

	} else {
		
		$hcp_info = GetXFromYID("select id from crm_hcp_information where crm_request_main_id = $pid");
		if (!empty($hcp_info)) {
			array_push($user_tabs, 1,2,3);
		}
		/*$user_tabs = array(1);

	 	if (!empty($rid)) {
			
			$level = GetXFromYID("select level from crm_naf_main where id='$rid' ");
			$Authorise = GetXFromYID("select authorise from crm_naf_main where id='$rid' ");
			if (($level > 4)) { 
				 $user_tabs = array();
				array_push($user_tabs,4);
				array_push($user_tabs,5, 6);
			}
		} */
	}
}

//BU Team 	124621
if ($PROFILE_ID == 27) {

	$user_tabs = array(1);

	$level = GetXFromYID("select level from crm_naf_main where id='$rid' ");
	$Authorise = GetXFromYID("select authorise from crm_naf_main where id='$rid' ");

	if ($level >= 5) { // Sujjo 02042023
		array_push($user_tabs, 6, 7);
	}
}

//Compliance Head 	324
if ($PROFILE_ID == 15) {

	$level = GetXFromYID("select level from crm_naf_main where id='$rid' ");
	$Authorise = GetXFromYID("select authorise from crm_naf_main where id='$rid' ");
	
	if ($level >= 6) {	//Sujjo 02042023
		$user_tabs = array(1);
		array_push($user_tabs, 6, 7);
	}
}

/* $TABS_DATA[1] = '<div class="col-2">
					<a href="' . $naf_form_url . '">
					<div class="tabBox">NAF Form</div>
				</a>
				</div>'; */

/* $TABS_DATA[1] = '<div class="col-2">
				<a href="' . $hcp_form_url . '">
					<div class="tabBox">HCP Information Form</div>
				</a>
				</div>';

$TABS_DATA[2] = '<div class="col-2">
				<a href="' . $naf_agrmnt_url . '">
					<div class="tabBox">HCP Agreement</div>
				</a>
			</div>'; */

/* $TABS_DATA[3] = '<div class="col-2">
				<a href="' . $qusetnr_url . '">
					<div class="tabBox">Questionnaire</div>
				</a>
			</div>'; */

/* $TABS_DATA[3] = '<div class="col-2">
				<a href="' . $doc_upld_url . '">
					<div class="tabBox">Documents upload</div>
				</a>
			</div>'; */
/* $TABS_DATA[3] = '<div class="col-2">
				<a href="' . $doc_upld_url . '">
					<div class="tabBox">Document Upload</div>
				</a>
			</div>'; 

$TABS_DATA[4] = '<div class="col-2">
				<a href="' . $pdf_url . '">
					<div class="tabBox">PDF Generation</div>
				</a>
			</div>';
			 */
$TABS_DATA[1] = '<div class="col-2">
					<a href="' . $naf_form_url . '">
					<div class="tabBox">NAF Form</div>
				</a>
				</div>';

			
$TABS_DATA[2] = '<div class="col-2">
				<a href="' . $delivery_url . '">
					<div class="tabBox">Delivery of service form</div>
				</a>
			</div>'; 
			
$TABS_DATA[3] = '<div class="col-2">
				<a href="' . $post_comment_url . '">
					<div class="tabBox">Post Activity & Comment</div>
				</a>
			</div>'; 
					
			

?>

<div class="section full mt-7">
	<div class="wide-block pt-2 pb-2">

		<div class="row">
			<?php
			/*  echo DFA($user_tabs);
			 exit;    */
			foreach ($TABS_DATA as $tab_id => $tab_value) {

				if (in_array($tab_id, $user_tabs)) {
					echo $tab_value;
				}
			}  ?>
		</div>

	</div>
</div>