<?php

// tab urls
$no_of_participants = (isset($no_of_participants)) ? $no_of_participants : '';
$pid = (isset($pid)) ? $pid : '';
$prid = (isset($prid)) ? $prid : '';
$qusetnr_url = (isset($qusetnr_url)) ? $qusetnr_url : '';
$NUm_of_PMAraised = (isset($NUm_of_PMAraised)) ? $NUm_of_PMAraised : 0;
if ($display_all == 1) {
	$naf_form_q_url = "index_naf_qtr.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$naf_form_camp_url = "index_naf_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$naf_request_letter_url = "request_letter_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$naf_agrmnt_url = "service_agreement_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&display_all=1&category=$category";


	$doc_upld_url = "document_upload_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&display_all=1&category=$category";
	$delivery_form_url = "delivery_service_form_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&category=$category";
	$delivery_form_ho_url = "mt_delivery_service_form_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&category=$category";
	$pdf_url  = "generate_pdf.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&display_all=1&category=$category";
	$camp_report_url  = "report_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&display_all=1&category=$category";
	$post_comment_url  = "post_activity.php?rid=$rid&prid=$prid&userid=$USER_ID&display_all=1&category=$category";
} else {
	$naf_form_q_url = "index_naf_qtr.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$naf_form_camp_url = "index_naf_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$naf_request_letter_url = "request_letter_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$hcp_form_url = "hcp_form_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$naf_agrmnt_url = "service_agreement_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";



	$doc_upld_url = "document_upload_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$delivery_form_url = "delivery_service_form_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$delivery_form_ho_url = "mt_delivery_service_form_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$pdf_url  = "generate_pdf.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$camp_report_url  = "report_camp.php?rid=$rid&prid=$prid&userid=$USER_ID&pid=$pid&category=$category";
	$post_comment_url  = "post_activity.php?rid=$rid&prid=$prid&userid=$USER_ID&category=$category";
}

$user_tabs = array();

$level = '';
$pid = (isset($pid)) ? $pid : '';

//RM/AM head
if ($PROFILE_ID == 6 || $PROFILE_ID == 7) {

	$user_tabs = array(2);

	$_Qnafmain = sql_query("select level,authorise from crm_naf_main where id='$rid' ");
	list($NAF_level, $NAF_isAuthorise) = sql_fetch_row($_Qnafmain);

	//if (($NAF_level==3 && $NAF_isAuthorise==3) || ($NAF_level >1 && $NAF_isAuthorise == 2)) { // Sujjo 02042023
	if (($NAF_level == 4 && $NAF_isAuthorise == 2) || ($NAF_level >= 4)) {
		if (!empty($pid)) {
			
			$_qr = sql_query("select level,authorise from crm_request_main where id = $pid ");
			list($level, $isAuthorise) = sql_fetch_row($_qr);
	
			if ($NAF_level >= 4 && $NAF_isAuthorise != 4) {
				array_push($user_tabs, 3);
			}
	
			$is_request_letter = GetXFromYID("select count(*) from crm_request_camp_letter where crm_request_main_id='$pid'");
			// checking if request letter form is submitted
			if ($is_request_letter > 0) {
				array_push($user_tabs, 4); //remove 4
			}
	
			$hcp_info = GetXFromYID("select id from crm_hcp_information where crm_request_main_id = $pid and deleted=0 ");
	
			if (!empty($hcp_info)) {
				//hcp info is submitted
				array_push($user_tabs, 5);
				$hcp_agreement = GetXFromYID("select count(*) from crm_hcp_agreement where crm_hcp_information_id = $hcp_info and deleted=0 ");
				// checking if hcp agreement is submitted		
				if ($hcp_agreement > 0) {
					array_push($user_tabs, 6);
				}
			}
	
	
	
			// 	//checking if documents are uploaded
			$uploaded = GetXFromYID("select count(*) from crm_naf_document_upload where crm_request_main_id = $pid and deleted = 0 ");
	
			if ($uploaded > 0) {
				// 		// if (($level == 4 && $isAuthorise == 2) || ($level > 4)) { //Sujjo 020420203
				array_push($user_tabs,8);
			}
	
	
			$is_camp_report_submitted=GetXFromYID("select count(*) from crm_naf_camp_report where crm_request_id=$pid ");
			if ($is_camp_report_submitted>0) {
				array_push($user_tabs,9);
			}
		}






	}//end of main loop
	//}
}

//State Head	24676
if ($PROFILE_ID == 15) {

	$user_tabs = array(2);

	$_Qnafmain = sql_query("select level,authorise from crm_naf_main where id='$rid' ");
	list($NAF_level, $NAF_isAuthorise) = sql_fetch_row($_Qnafmain);

	//if (($NAF_level==3 && $NAF_isAuthorise==3) || ($NAF_level >1 && $NAF_isAuthorise == 2)) { // Sujjo 02042023
	if (($NAF_level == 4 && $NAF_isAuthorise == 2) || ($NAF_level >= 4)) {

		if (!empty($pid)) {

		$_qr = sql_query("select level,authorise from crm_request_main where id = $pid ");
		list($level, $isAuthorise) = sql_fetch_row($_qr);

		if ($NAF_level >= 4 && $NAF_isAuthorise != 4) {
			array_push($user_tabs, 3);
		}

		$is_request_letter = GetXFromYID("select count(*) from crm_request_camp_letter where crm_request_main_id='$pid'");
		// checking if request letter form is submitted
		if ($is_request_letter > 0) {
			array_push($user_tabs, 4); //remove 4
		}

		$hcp_info = GetXFromYID("select id from crm_hcp_information where crm_request_main_id = $pid and deleted=0 ");

		if (!empty($hcp_info)) {
			//hcp info is submitted
			array_push($user_tabs, 5);
			$hcp_agreement = GetXFromYID("select count(*) from crm_hcp_agreement where crm_hcp_information_id = $hcp_info and deleted=0 ");
			// checking if hcp agreement is submitted		
			if ($hcp_agreement > 0) {
				array_push($user_tabs, 6);
			}
		}



		// 	//checking if documents are uploaded
		$uploaded = GetXFromYID("select count(*) from crm_naf_document_upload where crm_request_main_id = $pid and deleted = 0 ");

		if ($uploaded > 0) {
			// 		// if (($level == 4 && $isAuthorise == 2) || ($level > 4)) { //Sujjo 020420203
			array_push($user_tabs,8);
		}


		$is_camp_report_submitted=GetXFromYID("select count(*) from crm_naf_camp_report where crm_request_id=$pid ");
		if ($is_camp_report_submitted>0) {
			array_push($user_tabs,9);
		}



	}

	}//end of main loop
	//}
}

//Marketing Head 
if ($PROFILE_ID == 22) {
	$user_tabs = array(1);

	//echo "$no_of_participants /  $NUm_of_PMAraised"; 
	//exit;

	if (!empty($rid)) {
		// $naf_no = GetXFromYID("select naf_no from crm_naf_main where id='$rid'");
		// $no_of_participants=GetXFromYID("select naf_estimate_no_participents from crm_naf_main where id=$rid ");
		// $NUm_of_PMAraised = GetXFromYID("select count(*) from crm_request_main t1 inner join crm_request_details t2 on t1.id=t2.crm_request_main_id where  t1.naf_no='$naf_no' and (t1.level=5   and t1.authorise=3 )"); 
		// if ($no_of_participants==$NUm_of_PMAraised) {
		$level = GetXFromYID("select level from crm_naf_main where id='$rid' and deleted=0 ");
		$Authorise = GetXFromYID("select authorise from crm_naf_main where id='$rid' and deleted=0 ");
		$Delivery_form_count = GetXFromYID("select count(*) from crm_naf_delivery_form where crm_naf_main_id='$rid' and deleted=0 ");

		if (($level == 4 && $Authorise != 4) || ($level > 3)) { // Darshan 20230608
			array_push($user_tabs, 10);
		}
		// if ($Delivery_form_count > 0) {
		// 	array_push($user_tabs, 8);
		// }

		//}
	}
}

//BU Team 	124621
if ($PROFILE_ID == 27) {

	$user_tabs = array(1);

	$level = GetXFromYID("select level from crm_naf_main where id='$rid' ");
	$Authorise = GetXFromYID("select authorise from crm_naf_main where id='$rid' ");
	$Delivery_form_count = GetXFromYID("select count(*) from crm_naf_delivery_form where crm_naf_main_id='$rid' and deleted=0 ");

	if ($level >= 5) { // Sujjo 02042023
		array_push($user_tabs, 7);
	}
	if ($Delivery_form_count > 0) {
		array_push($user_tabs, 8);
	}
}

//Compliance Head 	324
if ($PROFILE_ID == 15) {

	$level = GetXFromYID("select level from crm_naf_main where id='$rid' ");
	$Authorise = GetXFromYID("select authorise from crm_naf_main where id='$rid' ");

	if (($level >= 6) && ($Authorise != 4)) {	//Sujjo 02042023
		$user_tabs = array(1);
		array_push($user_tabs, 7, 8);
	}
}

//Medical Head
if ($PROFILE_ID == 11) {
	$user_tabs = array(1, 9);
}


$TABS_DATA[1] = '<div class="col-2 py-2">
					<a href="' . $naf_form_q_url . '">
					<div class="tabBox">NAF Form Quarter</div>
				</a>
				</div>';

$TABS_DATA[2] = '<div class="col-2 py-2">
					<a href="' . $naf_form_camp_url . '">
					<div class="tabBox">NAF Form Activity</div>
				</a>
				</div>';

$TABS_DATA[3] = '<div class="col-2 py-2">
					<a href="' . $naf_request_letter_url . '">
					<div class="tabBox">Request Letter</div>
				</a>
				</div>';


// $TABS_DATA[4] = '<div class="col-2">
// 					<a href="' . $naf_form_camp_url . '">
// 					<div class="tabBox">NAF Form Activity</div>
// 				</a>
// 				</div>';



$TABS_DATA[4] = '<div class="col-2 py-2">
				<a href="' . $hcp_form_url . '">
					<div class="tabBox">HCP Information Form</div>
				</a>
				</div>';

$TABS_DATA[5] = '<div class="col-2 py-2">
				<a href="' . $naf_agrmnt_url . '">
					<div class="tabBox">HCP Agreement</div>
				</a>
			</div>';

// $TABS_DATA[5] = '<div class="col-2">
// 				<a href="' . $qusetnr_url . '">
// 					<div class="tabBox">Questionnaire</div>
// 				</a>
// 			</div>';

$TABS_DATA[6] = '<div class="col-2 py-2">
				<a href="' . $doc_upld_url . '">
					<div class="tabBox">Documents upload</div>
				</a>
			</div>';

// $TABS_DATA[7] = '<div class="col-2 py-2">
// 				<a href="' . $pdf_url . '">
// 					<div class="tabBox">Acknowledgement/PDF</div>
// 				</a>
// 			</div>';

$TABS_DATA[8] = '<div class="col-2 py-2">
				<a href="' . $camp_report_url . '">
					<div class="tabBox">Camp Report</div>
				</a>
			</div>';

$TABS_DATA[9] = '<div class="col-2 py-2">
				<a href="' . $delivery_form_url . '">
					<div class="tabBox">Delivery of service form</div>
				</a>
			</div>';

$TABS_DATA[10] = '<div class="col-2 py-2">
				<a href="' . $delivery_form_ho_url . '">
					<div class="tabBox">Delivery of service form (HO)</div>
				</a>
			</div>';





?>

<div class="section full mt-7">
	<div class="wide-block pt-2 pb-2">

		<div class="row">
			<?php
			// echo $PROFILE_ID;
			//DFA($user_tabs);
			// exit;
			foreach ($TABS_DATA as $tab_id => $tab_value) {

				if (in_array($tab_id, $user_tabs)) {
					echo $tab_value;
				}
			}  ?>
		</div>

	</div>
</div>