<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/common.php";


$page_title = 'Approve Camp Activity';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$category = (isset($_GET['category'])) ? $_GET['category'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
    echo 'Invalid Access Detected!!!';
    exit;
}
if (empty($rid) || (!empty($rid) && !is_numeric($rid))) {
    echo 'Invalid Access Detected!!!';
    exit;
}


$display_other_textbox_style = 'display:none';
$Other_textbox = '';

$o_product_id = GetXFromYID("select product_id from crm_naf_product_details Where naf_request_id=$rid ");
if ($o_product_id == 0) {
    $display_other_textbox_style = '';
    $Other_textbox = GetXFromYID("select  naf_product_therapy_others from crm_naf_product_details Where naf_request_id=$rid ");
}

$ADVANCE_PAYMENT_ARRAY = array('Yes' => 'Yes', 'No' => 'NO');

// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
//$User_division = GetXFromYID("select division from users where id='$USER_ID' "); //Getting division
$SPECILITY_ARR = $CRM_FILEDS = array();

$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0', '3');

$DATA = GetDataFromID('crm_naf_main', 'id', $rid);

$requestorID = $DATA[0]->userid;
$date = $DATA[0]->date;
$submited_date = $DATA[0]->submitted_on;
$event_date = $DATA[0]->eventdate;
$naf_no = $DATA[0]->naf_no;
$naf_activity_name = $DATA[0]->naf_activity_name;
$proposed_activity = $DATA[0]->proposed_activity;
$naf_city = $DATA[0]->naf_city;
$naf_proposed_venue = $DATA[0]->naf_proposed_venue;
$naf_estimate_no_participents = $DATA[0]->naf_estimate_no_participents;
$proposed_objective = $DATA[0]->proposed_objective;
$naf_objective_rational = $DATA[0]->naf_objective_rational;
$pendingwithid = $DATA[0]->pendingwithid;
$advance_payment_type = $DATA[0]->advance_payment_type;
$advance_payment = $DATA[0]->advance_payment;
$doc_upload_path = $DATA[0]->doc_upload_path;




$productID = GetXFromYID("select product_id from crm_naf_product_details where naf_request_id='$rid' ");
$naf_product_therapy_others = GetXFromYID("select naf_product_therapy_others from crm_naf_product_details where naf_request_id='$rid' ");

$product_display_style = $advance_amt_display = '';
if ($productID == 0) {
    $product_display_style = 'display: none;';
}

$advance_amt_display = 'display: none;';
if ($advance_payment_type == 'Yes') {
    $advance_amt_display = '';
}

$User_division = GetXFromYID("select division from users where id='$requestorID' ");
$curr_dt = date('Y-m-d', strtotime($submited_date));
// $financial_qry = "SELECT financial_year FROM financialyear where '".$curr_dt."' between from_date and to_date";
// $financial_qry_res = sql_query($financial_qry);
$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");


$selectoption = $readonly = '';

$CITY_ARR = GetXArrFromYID("SELECT cityid as id, concat_ws('',cityname,if(city.metrononmetro=1,'(Metro)','(Non-Metro)')) as name FROM city where deleted=0 ORDER BY cityname", '3');

$cond = '';
if (!empty($User_division) && (isset($User_division))) {
    $cond .= " and division=$User_division ";
}

$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear'  ", '3');


$PRODUCTS_ARR = GetXArrFromYID("select productbrandtypeid as id ,productbrandtype as name from productbrandtype where presence=1 " . $cond . " order by productbrandtype ASC", '3');

$_sp_q = "select speciality_id from crm_naf_speciality_details where naf_request_id='$rid' and deleted=0  ";
$SELECTED_SPECIALITIES = array();
$_sq_q_r = sql_query($_sp_q, '');
while (list($specialityid) = sql_fetch_row($_sq_q_r)) {
    if (!isset($SELECTED_SPECIALITIES[$specialityid]))
        $SELECTED_SPECIALITIES[$specialityid] =   $specialityid;
}
//DFA($SELECTED_SPECIALITIES);

$S_ARRA = array();
//DFA($SELECTED_SPECIALITIES);
foreach ($SPECILITY_ARR as $key => $value) {
    if (isset($SELECTED_SPECIALITIES[$key])) {
        # code...
        if ($SELECTED_SPECIALITIES[$key] == $key) {
            array_push($S_ARRA, $value);
        }
    }
}
//DFA($S_ARRA);



$_crm_field_q = "select naf_field_id,naf_expense from crm_naf_cost_details where naf_request_id='$rid' ";
$_crm_field_r = sql_query($_crm_field_q, '');

while (list($naf_field_id, $naf_expense) = sql_fetch_row($_crm_field_r)) {
    if (!isset($CRM_FILEDS[$naf_field_id]))
        $CRM_FILEDS[$naf_field_id] = $naf_expense;
}
//DFA($CRM_FILEDS);

$x_total = GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$rid' "); //total amount

$_q = "select field_id,field_name from crm_naf_fields_master where deleted=0 AND typeid=6 "; //fetch typeid from the category id passed in url
$_r = sql_query($_q, "");


$HCP_DATA = GetDataFromID('crm_naf_hcp_details', 'naf_main_id', $rid, "and deleted=0 ");

$hcp_details_ID = GetXFromYID("select id from crm_naf_hcp_details where naf_main_id='$rid' and deleted=0 ");

$REQUEST_LETTER = GetDataFromID('crm_naf_camp_letter', 'crm_naf_hcp_details_id', $hcp_details_ID);




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
        <div class="pageTitle camp">Need Assessment Form(By Activity Requestor)</div>
        <div class="right"></div>
    </div>


    <div class="section full ">
        <div class="wide-block pt-2 pb-2">

            <div class="row">
                <!--<div class="col-2">
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
                    </div>-->
            </div>

        </div>
    </div>


    <div id="appCapsule">

        <form action="_Approve_c_a.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
            <input type="hidden" name="rid" value="<?php echo $rid; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">

            <div class="tab-content mt-1">



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
                                            <input type="text" class="form-control" name="reqDate" id="reqDate" value="<?php echo $event_date; ?>" readonly>
                                        </div>

                                    </div>
                                </div>

                                <br>

                                <div class="row">

                                    <div class="col-3">
                                        <b>Product:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <select class="form-control custom-select" id="Product" name="productID" disabled>
                                                <option value="">Choose...</option>
                                                <?php
                                                foreach ($PRODUCTS_ARR as $key => $value) {
                                                    //echo $value[$key]['iModID'];
                                                    $selected =    ($productID == $key) ? 'selected' : '';
                                                    echo '<option value="' . $key . '" ' . $selected . ' >' . $value . '</option>';
                                                }

                                                //$selected =    ($productID == $k) ? 'selected' : '';
                                                $k = 0;
                                                $selected =    ($productID == $k) ? 'selected' : '';
                                                echo '<option value="' . $k . '" ' . $selected . ' > Others </option>';

                                                ?>
                                            </select>
                                        </div>
                                        <br>
                                        <input type="text" class="form-control" id="others" size="70" value="<?php echo $naf_product_therapy_others; ?>" placeholder="Enter product here " name="others" style="<?php echo  $display_other_textbox_style; ?>" <?php echo $readonly; ?>>
                                    </div>
                                </div>

                                <br>

                                <div class="row">

                                    <div class="col-3">
                                        <b>Event ID No<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="text" class="form-control" id="eventID" name="eventID" value="<?php echo $naf_no; ?>" readonly>
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
                                            <input type="text" class="form-control" name="activty_name" id="activty_name" value="<?php echo $naf_activity_name; ?>">
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
                                            <select class="form-control custom-select" id="Nature_of_activity" name="Nature_of_activity" required="" <?php echo $selectoption; ?>>
                                                <?php
                                                foreach ($ACTIVITY_ARR as $key => $value) {
                                                    //echo $value[$key]['iModID'];
                                                    $selected = ($proposed_activity == $key) ? 'selected' : '';
                                                    echo '<option value="' . $key . '" ' . $selected . ' >' . $value . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <br>

                                <div class="row">

                                    <div class="col-3">
                                        <b>Date of the Activity:</b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="date" class="form-control" value="<?php echo $date; ?>" id="activity_Date" name="activity_Date" readonly>
                                        </div>

                                    </div>
                                </div>

                                <br>


                                <div class="row">

                                    <div class="col-3">
                                        <b>City(Metro and Non Metro):<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <select class="form-control custom-select" id="city" name="city" <?php echo $selectoption; ?>>
                                                <option selected="" value="">Choose...</option>
                                                <?php
                                                foreach ($CITY_ARR as $key => $value) {
                                                    //echo $value[$key]['iModID'];
                                                    $selected = ($naf_city == $key) ? 'selected' : '';
                                                    echo '<option value="' . $key . '" ' . $selected . ' >' . $value . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <br>

                                <div class="row">

                                    <div class="col-3">
                                        <b>Proposed Venue:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="text" class="form-control" id="venue" name="venue" value="<?php echo $naf_proposed_venue; ?>">
                                        </div>
                                    </div>
                                </div>


                                <br>

                                <div class="row">

                                    <div class="col-3">
                                        <b>Estimated Number of Participants:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="text" class="form-control" id="no_of_p" name="no_of_p" value="<?php echo $naf_estimate_no_participents; ?>">
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
                                            <input type="text" class="form-control" value="<?php echo implode(",", $S_ARRA); ?>" placeholder="" readonly>
                                        </div>
                                    </div>
                                </div>


                                <br>

                                <div class="row">

                                    <div class="col-3">
                                        <b>The objective/rationale/need for conducting an Activity:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <textarea id="objective" name="objective" rows="3" class="form-control" readonly><?php echo $proposed_objective; ?></textarea>
                                        </div>
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


                                <br>
                                <?php

                                while ($row = sql_fetch_assoc($_r)) {
                                    //DFA($row);
                                    $compulsory = ($row['field_id'] == '1') ? 'required="" ' : '';
                                    $compulsoryfield = ($row['field_id'] == '1') ? '<span style="color:#ff0000">*</span>' : '';
                                    $value = (isset($CRM_FILEDS[$row['field_id']])) ? $CRM_FILEDS[$row['field_id']] : '';
                                ?>


                                    <div class="row">

                                        <div class="col-3">
                                            <b><?php echo $row['field_name'] . ':'; ?><?php echo $compulsoryfield; ?></span></b>
                                        </div>

                                        <div class="col-9">
                                            <div class="input-wrapper">
                                                <input type="number" value="<?php echo $value; ?>" td_id="<?php $row['field_id']; ?>" onkeypress="CalculateTotal();" onkeyup="CalculateTotal();" onblur="CalculateTotal();" name="crm_<?php echo $row['field_id']; ?>" class="form-control amountCalculate" id="crm_<?php echo $row['field_id']; ?>" placeholder="" <?php echo $readonly; ?> <?php echo $compulsory; ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <br>



                                <?php }    ?>

                                <!-- 
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
									<b>Sponsorship:</b>
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
							</div> -->

                                <br>

                                <div class="row">

                                    <div class="col-3">
                                        <b>TOTAL AMOUNT:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="number" class="form-control" id="total" name="total" value="<?php echo $x_total; ?>" readonly>
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
                                            <select class="form-control custom-select" name="advance_payment_type" id="dropdown" required="">
                                                <option value="">Choose...</option>
                                                <?php
                                                foreach ($ADVANCE_PAYMENT_ARRAY as $key => $value) {
                                                    $selected = ($advance_payment_type == $key) ? 'selected' : '';
                                                    echo '<option value="' . $key . '" ' . $selected . ' >' . $value . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>


                                <div id="form" style="<?php echo $advance_amt_display; ?>">
                                    <label for="file">Upload file:</label>
                                    <?php if (!empty($doc_upload_path)) {
                                        echo '<a href="' . $doc_upload_path . '" > view file</a></div>';
                                    } ?>


                                    <br>
                                    <label for="amount">Enter amount:</label>
                                    <input type="number" class="form-control" value="<?php echo $advance_payment; ?>" id="advance_payment" name="advance_payment" readonly>
                                </div>



                            </div>


                        </div>
                    </div>
                </div>


                <div class="section mt-2">
                    <div class="card">
                        <div class="card-body pd-1">

                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addhcp" style="<?php echo $display_style; ?>">ADD</button>


                            <table id="example" class="display mt-2" style="width:100%">

                                <thead>

                                    <tr>
                                        <th></th>
                                        <th>Sr. No</th>
                                        <th>HCP Universal ID</th>
                                        <th>Name of the HCP</th>
                                        <th>Address(city)</th>
                                        <th>Honrarium Amount</th>
                                        <th>Role of HCP</th>
                                        <th>Mobile Number</th>
                                        <th>PAN #</th>
                                        <th>Qualification</th>
                                        <th>Associated Hospital/Clinic</th>
                                        <th>Govt.(Yes/No)</th>
                                        <th>Years of experience</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="append_data">
                                    <?php
                                    if (!empty($HCP_DATA)) {
                                        for ($i = 0; $i < count($HCP_DATA); $i++) {
                                            $k = $i + 1;
                                            $hcp_id = $HCP_DATA[$i]->hcp_id;
                                            $masterid = GetXFromYID("select masterid from contactdetails where contactid='$hcp_id' ");
                                            $hcp_name = GetXFromYID("SELECT CONCAT(firstname, ' ', lastname) AS full_name FROM contactmaster where id='$hcp_id' ");
                                            $hcp_address = $HCP_DATA[$i]->hcp_address;
                                            $hcp_pan = $HCP_DATA[$i]->hcp_pan;
                                            $hcp_qualification = $HCP_DATA[$i]->hcp_qualification;
                                            $hcp_associated_hospital_id = $HCP_DATA[$i]->hcp_associated_hospital_id;
                                            $govt_type = $HCP_DATA[$i]->govt_type;
                                            $yr_of_experience = $HCP_DATA[$i]->yr_of_experience;
                                            $role_of_hcp = $HCP_DATA[$i]->role_of_hcp;
                                            $honorarium_amount = $HCP_DATA[$i]->honorarium_amount;
                                            $mobile = $HCP_DATA[$i]->mobile;
                                    ?>
                                            <tr>
                                                <td></td>
                                                <td><?php echo $k; ?></td>
                                                <td><?php echo $masterid; ?></td>
                                                <td><?php echo $hcp_name; ?></td>
                                                <td><?php echo $hcp_address; ?></td>
                                                <td><?php echo $honorarium_amount; ?></td>
                                                <td><?php echo $role_of_hcp; ?></td>
                                                <td><?php echo $mobile; ?></td>
                                                <td><?php echo $hcp_pan; ?></td>
                                                <td><?php echo $hcp_qualification; ?></td>
                                                <td><?php echo $hcp_associated_hospital_id; ?></td>
                                                <td><?php echo $govt_type; ?></td>
                                                <td><?php echo $yr_of_experience; ?></td>
                                                <td></td>

                                            </tr>


                                    <?php    }
                                    }
                                    ?>




                                </tbody>
                            </table>


                            <br><br><br><br>

                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addrequestletterMODAL" style="<?php echo $display_style; ?>">ADD</button>
                            <table id="example1" class="display" style="width:100%">

                                <thead>

                                    <tr>
                                        <th></th>
                                        <th>Sr. No</th>
                                        <th>Nature of Camps</th>
                                        <th>Proposed Date</th>
                                        <th>Venue</th>
                                        <th>HCP</th>
                                        <th>Estimated Cost, if any</th>
                                        <th>Diagnostic labs collaboration</th>
                                    </tr>
                                </thead>
                                <tbody id="addrequestletter">
                                    <?php
                                    if (!empty($REQUEST_LETTER)) {
                                        for ($i = 0; $i < count($REQUEST_LETTER); $i++) {
                                            $k = $i + 1;
                                            $hcp_id = $HCP_DATA[$i]->hcp_id;
											$hcp_name = GetXFromYID("SELECT CONCAT(firstname, ' ', lastname) AS full_name FROM contactmaster where id='$hcp_id' ");
                                            $nature_of_camp = $REQUEST_LETTER[$i]->nature_of_camp;
                                            $proposed_camp_date = $REQUEST_LETTER[$i]->proposed_camp_date;
                                            
                                            $proposed_camp_location = $REQUEST_LETTER[$i]->proposed_camp_location;
                                            $estimated_cost = $REQUEST_LETTER[$i]->estimated_cost;
                                            $diagnostic_lab = $REQUEST_LETTER[$i]->diagnostic_lab; ?>
                                            <tr>
                                                <td></td>
                                                <td><?php echo $k; ?></td>
                                                <td><?php echo $nature_of_camp; ?></td>
                                                <td><?php echo $proposed_camp_date; ?></td>
                                                <td><?php echo $proposed_camp_location; ?></td>
                                                <td><?php echo $hcp_name; ?></td>
                                                <td><?php echo $estimated_cost; ?></td>
                                                <td><?php echo $diagnostic_lab; ?></td>
                                            </tr>


                                    <?php    }
                                    }

                                    ?>





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
                                    <textarea id="rationale" name="rationale" rows="3" class="form-control" readonly><?php echo $naf_objective_rational; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="section mt-2">
                    <div class="card">
                        <div class="card-body">

                            <?php
                            if ($pendingwithid == $USER_ID) { ?>
                                <div class="row" style="justify-content: center">
                                    <span class="btn btn-success mr-1 mb-1 pd-sr">
                                        <input type="radio" id="approve" onchange="addRemark();" name="choice" value="A">
                                        <label for="approve" style="margin-bottom:0px;">Approve</label><br>
                                    </span>

                                    <span class="btn btn-danger mr-1 mb-1 pd-sr">
                                        <input type="radio" onchange="addRemark();" id="reject" name="choice" value="R">
                                        <label for="reject" style="margin-bottom:0px;">Reject</label><br>
                                    </span>
                                </div>

                            <?php   }
                            ?>




                        </div>
                    </div>
                </div>




                <div class="section full mt-2">
                    <div class="wide-block pt-2 pb-2">

                        <div class="row">
                            <!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
						</div> -->
                            <div class="col">
                                <?php
                                if ($pendingwithid == $USER_ID) { ?>

                                    <button type="submit" class="exampleBox btn btn-primary rounded me-1">Submit</button>
                                <?php  } ?>
                            </div>
                            <!-- <div class="col">
							<a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
						</div> -->
                        </div>

                    </div>
                </div>







            </div>
        </form>


    </div>


    <script>
        jQuery('#targetedSpeciality').multiselect({
            columns: 1,
            placeholder: 'Choose...',
            search: true
        });
    </script>



    <script>
        $(document).ready(function() {
            $('#myselection').on('change', function() {
                var demovalue = $(this).val();
                $("div.myDiv").hide();
                $("#show" + demovalue).show();
            });
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
        function CalculateTotal() {
            var total = 0;
            $('.amountCalculate').each(function() {
                var numbers = $(this).val();

                if ((numbers != '')) {

                    total += parseFloat(numbers);
                }

            });

            //console.log(parseInt(total));
            $('#total').val(total);
        }

        $(document).ready(function() {
            var table = $('#example').DataTable({
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#example1').DataTable({
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true
            });
        });
    </script>

</body>

</html>