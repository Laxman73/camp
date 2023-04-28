<?php

// tab urls

if ($display_all == 1) {
	$naf_form_url = "approve_naf_consultancy.php?rid=$rid&userid=$USER_ID&display_all=1&category=$category";
	$delivery_url = "ho_delivery_service_form_consultancy.php?rid=$rid&userid=$USER_ID&pid=$pid&display_all=1&category=$category";
	$post_comment_url  = "post_activity_quarter_consultancy.php?rid=$rid&userid=$USER_ID&display_all=1&category=$category";
} else {
	$naf_form_url = "approve_naf_consultancy.php?rid=$rid&userid=$USER_ID&category=$category";
	$delivery_url = "ho_delivery_service_form_consultancy.php?rid=$rid&userid=$USER_ID&category=$category";
	$post_comment_url  = "post_activity_quarter_consultancy.php?rid=$rid&userid=$USER_ID&category=$category"; 
}

$user_tabs = array();

$level = '';
$pid = (isset($pid)) ? $pid : '';

/* echo "PROFILE_ID".$PROFILE_ID;
exit(); */

//RM/AM head
/* if (($PROFILE_ID == 6 || $PROFILE_ID == 7) && !empty($rid)) {

	

	$_Qnafmain = sql_query("select level,authorise from crm_naf_main where id='$rid' ");
	list($NAF_level, $NAF_isAuthorise) = sql_fetch_row($_Qnafmain);
	
	array_push($user_tabs, 1,2);
	
	$dos_info = GetXFromYID("select id from crm_naf_delivery_form where crm_naf_main_id = $rid");
			if (!empty($dos_info)) {
				array_push($user_tabs, 3);
			}

	
} */

//State Head	24676
if ($PROFILE_ID == 15) {

	$user_tabs = array(1);

	$level = GetXFromYID("select level from crm_naf_main where id='$rid' ");
	$Authorise = GetXFromYID("select authorise from crm_naf_main where id='$rid' ");

	if ($level >= 5) { // Sujjo 02042023
		array_push($user_tabs,2,3);
	}
}

//Medical Team 
/* if (($PROFILE_ID == 11 || $PROFILE_ID == 22)  && !empty($rid)) {

array_push($user_tabs, 1);
	$naf_info = GetXFromYID("select id from crm_naf_main where id = $rid and deleted=0");
			if (!empty($naf_info)) {
				array_push($user_tabs, 2);
			}
			
		
	$dos_info = GetXFromYID("select id from crm_naf_delivery_form where crm_naf_main_id = $rid and deleted=0");
			if (!empty($dos_info)) {
				array_push($user_tabs, 3);
			}

} */

//BU Team 	124621
if ($PROFILE_ID == 27) {

	$user_tabs = array(1);

	$level = GetXFromYID("select level from crm_naf_main where id='$rid' ");
	$Authorise = GetXFromYID("select authorise from crm_naf_main where id='$rid' ");

	if ($level >= 5) { // Sujjo 02042023
		array_push($user_tabs,2,3);
	}
}

//Compliance Head 	324
/* if ($PROFILE_ID == 15) {

	$level = GetXFromYID("select level from crm_naf_main where id='$rid' ");
	$Authorise = GetXFromYID("select authorise from crm_naf_main where id='$rid' ");
	
	if ($level >= 6) {	//Sujjo 02042023
		$user_tabs = array(1);
		array_push($user_tabs, 6, 7);
	}
}
 */
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
			 exit;   */  
			foreach ($TABS_DATA as $tab_id => $tab_value) {

				if (in_array($tab_id, $user_tabs)) {
					echo $tab_value;
				}
			}  ?>
		</div>

	</div>
</div>