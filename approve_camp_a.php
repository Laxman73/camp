<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/common.php";


$page_title = 'Approve Camp Activity';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
    echo 'Invalid Access Detected!!!';
    exit;
}
if (empty($rid) || (!empty($rid) && !is_numeric($rid))) {
    echo 'Invalid Access Detected!!!';
    exit;
}

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



$productID = GetXFromYID("select product_id from crm_naf_product_details where naf_request_id='$rid' ");

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

                                                ?>
                                            </select>
                                        </div>
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
                                            <select class="form-control custom-select" id="myselection">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>


                                <div id="showYes" class="myDiv">
                                    <form>
                                        <div class="custom-file-upload">
                                            <input type="file" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                                            <label for="fileuploadInput">
                                                <span>
                                                    <strong>
                                                        <ion-icon name="cloud-upload-outline"></ion-icon>
                                                        <i>Tap to Upload</i>
                                                    </strong>
                                                </span>
                                            </label>
                                        </div>
                                    </form>
                                </div>



                            </div>


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
                                if ($pendingwithid==$USER_ID) { ?>
                                   
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

