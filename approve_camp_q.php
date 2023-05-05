<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/common.php";

$page_title = 'Camp Quarter approval';
$TITLE = SITE_NAME . ' | ' . $page_title;


$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$prid = (isset($_GET['prid'])) ? $_GET['prid'] : '';
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
$SPECILITY_ARR = array();

$ACTIVITY_ARR = GetXArrFromYID('SELECT id as id,`activityname`  as name FROM `crm_naf_activitymaster` where deleted=0', '3');


$QUARTER_ARR = GetXArrFromYID("select quarterid,quarter_name from crm_naf_quarter_master ", '3');
$selectoption = '';
$readonly = 'readonly';

$DATA = GetDataFromID('crm_naf_main', 'id', $rid);
//DFA($DATA);
// [id] => 65
// [initiator] => KhajaMuzammil Ali
// [userid] => 19804
// [emp_code] => 124983
// [date] => 2023-04-15
// [post_comment] => 
// [category_id] => 5
// [submitted_on] => 2023-04-15 15:20:45
// [submitted] => 1
// [pendingwithid] => 124621
// [authorise] => 1
// [approved_date] => 2023-04-15 15:20:45
// [deleted] => 0
// [deleted_on] => 
// [eventdate] => 2023-04-15
// [level] => 1
// [naf_no] => QCAMDTF00610065
// [naf_activity_name] => camp quarter
// [naf_city] => 2
// [naf_proposed_venue] => goa
// [naf_estimate_no_participents] => 10
// [naf_start_date] => 2023-04-15
// [naf_end_date] => 2023-04-15
// [naf_objective_rational] => reer
// [remarks] => 
// [quarter] => 0
// [mode] => 0
// [proposed_activity_count] => 0
// [proposed_hcp_no] => 0
// [proposed_activity] => 
// [proposed_objective] => objective
// [rationale_remark] => 
// [lead_event] => 
// [medical_equipments] => 
// [deviation_amount] => 0
// [parent_id] => 
$requestorID = $DATA[0]->userid;
$quarter = $DATA[0]->quarter;
$pending_with_id=$DATA[0]->pendingwithid;
$proposed_activity = $DATA[0]->proposed_activity;
$naf_activity_name = $DATA[0]->naf_activity_name;
$proposed_activity_count = $DATA[0]->proposed_activity_count;
$proposed_hcp_no = $DATA[0]->proposed_hcp_no;
$naf_estimate_no_participents = $DATA[0]->naf_estimate_no_participents;
$proposed_objective = $DATA[0]->proposed_objective;
$naf_objective_rational = $DATA[0]->naf_objective_rational;
$lead_event = $DATA[0]->lead_event;
$medical_equipments = $DATA[0]->medical_equipments;
$submitted_on = $DATA[0]->submitted_on;
$deviation_amount = $DATA[0]->deviation_amount;
$budget_amount = $DATA[0]->budget_amount;
$event_benefit_society = $DATA[0]->event_benefit_society;



$division = GetXFromYID("select division from users where id='$requestorID' ");
$curr_dt = date('Y-m-d', strtotime($submitted_on));
// $financial_qry = "SELECT financial_year FROM financialyear where '".$curr_dt."' between from_date and to_date";
// $financial_qry_res = sql_query($financial_qry);
$curr_fyear = GetXFromYID("SELECT financial_year FROM financialyear where '" . $curr_dt . "' between from_date and to_date ");

$SPECILITY_ARR = GetXArrFromYID("select specialityid as id,specialityname as name from speciality where  in_use=0 AND fyear='$curr_fyear'  ", '3');


$_sp_q = "select speciality_id from crm_naf_speciality_details where naf_request_id='$rid' ";
$SELECTED_SPECIALITIES = array();
$_sq_q_r = sql_query($_sp_q, '');
while (list($specialityid) = sql_fetch_row($_sq_q_r)) {
    if (!isset($SELECTED_SPECIALITIES[$specialityid]))
        $SELECTED_SPECIALITIES[$specialityid] =  $specialityid;
}


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
        <div class="pageTitle">Quarterly Need Assessment Form</div>
        <div class="right"></div>
    </div>


    <div class="section full">
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

        <form action="_Approve_c_q.php" id="CAMP_Q_Approve_FORM" method="post">
            <input type="hidden" name="rid" value="<?php echo $rid; ?>">
            <input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
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
                                            <input type="text" class="form-control" name="reqDate" id="reqDate" value="<?php echo TODAY; ?>" readonly>
                                        </div>

                                    </div>
                                </div>

                                <br>

                                <div class="row">

                                    <div class="col-3">
                                        <b>Quarter:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <select class="form-control custom-select" name="Quarter" id="Quarter" disabled>
                                                <?php
                                                foreach ($QUARTER_ARR as $key => $value) {
                                                    //echo $value[$key]['iModID'];
                                                    $selected = ($quarter == $key) ? 'selected' : '';
                                                    echo '<option value="' . $key . '" ' . $selected . ' >' . $value . '</option>';
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>

                                </div>



                                <br>

                                <!--<div class="row">
							
								<div class="col-3">
									<b>Name of the Activity:<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" required="">
									</div>
								</div>
							</div>
							
							<br>-->


                                <div class="row">

                                    <div class="col-3">
                                        <b>Name of activity:</b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">

                                            <b></b>

                                            <div class="custom-control" style="padding-left: 0px;">
                                                <input type="text" class="form-control" name="activty_name" value="<?php echo $naf_activity_name; ?>" id="activty_name" readonly>
                                            </div>


                                        </div>
                                    </div>

                                </div>


                                <br>

                                <!-- <div class="row">

								<div class="col-3">
									<b>Please tick the appropriate box:</b>
								</div>
								<div class="col-9">
									<div class="input-wrapper">


										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio-1" name="customRadio1" class="custom-control-input">
											<label class="custom-control-label" for="customRadio-1">Continuous Medical Education Program</label>
										</div>
										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio-2" name="customRadio1" class="custom-control-input">
											<label class="custom-control-label" for="customRadio-2">Speakership Program</label>
										</div>
										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio-3" name="customRadio1" class="custom-control-input">
											<label class="custom-control-label" for="customRadio-3">Consulting Services</label>
										</div>
										<div class="custom-control custom-radio mb-1">
											<input type="radio" id="customRadio-4" name="customRadio1" class="custom-control-input">
											<label class="custom-control-label" for="customRadio-4">Others, please specify</label>
										</div>


										<input type="text" class="form-control" id="" placeholder="" required="">


									</div>
								</div>

							</div> -->


                                <br>


                                <div class="row">

                                    <div class="col-3">
                                        <b>Proposed Count of Activities:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="number" name="proposed_count" class="form-control" id="proposed_count" value="<?php echo  $proposed_activity_count; ?>" readonly>
                                        </div>
                                    </div>
                                </div>


                                <br>


                                <div class="row">

                                    <div class="col-3">
                                        <b>Proposed Number of HCPS's:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="number" class="form-control" name="number_of_HCP" id="number_of_HCP" value="<?php echo $proposed_hcp_no; ?>" readonly>
                                        </div>
                                    </div>
                                </div>


                                <br>

                                <div class="row">

                                    <div class="col-3">
                                        <b>Proposed Nature of the Activity:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <select class="form-control custom-select" id="Nature_of_activity" name="Nature_of_activity" disabled>
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


                                <!--<br>
							
							<div class="row">
							
								<div class="col-3">
									<b>Proposed Targeted Speciality:<span style="color:#ff0000">*</span></b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<select class="form-control custom-select" id="" required="">
											<option selected="" disabled="" value="">Choose...</option>
											<option value="1">Option 1</option>
											<option value="2">Option 2</option>
											<option value="3">Option 3</option>
											<option value="4">Option 4</option>
											<option value="5">Option 5</option>
											<option value="6">Option 6</option>
										</select>
									</div>
								</div>
							</div>-->



                                <br>


                                <div class="row">

                                    <div class="col-3">
                                        <b>Estimated Number of Participants:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="number" class="form-control" id="number_of_participants" value="<?php echo $naf_estimate_no_participents; ?>" name="number_of_participants" readonly>
                                        </div>
                                    </div>
                                </div>


                                <br>
                                <div class="row">

                                    <div class="col-3">
                                        <b>Budgeted Amount in the Quarter:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="number" class="form-control" id="budget_amt" name="budget_amt" value="<?php echo $budget_amount; ?>">
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <br>

                                <div class="row">

                                    <div class="col-3">
                                        <b>Targeted Speciality:<span style="color:#ff0000">*</span></b>
                                    </div>


                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="text" class="form-control" value="<?php echo implode(",", $S_ARRA); ?>" id="Num_of_Participants" name="Num_of_Participants" placeholder="" required="" <?php echo $readonly; ?>>
                                        </div>
                                    </div>

                                </div>

                                <br>
                                <div class="row">

                                    <div class="col-3">
                                        <b>The proposed objective/rationale/need for an Activity:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="text" class="form-control" name="proposed_obj" id="proposed_obj" value="<?php echo $proposed_objective; ?>">
                                        </div>
                                    </div>
                                </div>


                                <br>
                                <div class="row">

                                    <div class="col-3">
                                        <b>Rationale for selection of a particular Class of HCPs in comparison to other HCP’s:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="text" class="form-control" id="rationale" name="rationale" value="<?php echo $naf_objective_rational; ?>">
                                        </div>
                                    </div>
                                </div>


                                <br>
                                <div class="row">

                                    <div class="col-3">
                                        <b>Will the event/activity benefit the society/Therapy/Patient in any manner? If yes, how?<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="text" value="<?php echo $event_benefit_society; ?>" class="form-control" id="activity_benefit" name="activity_benefit">
                                        </div>
                                    </div>
                                </div>



                                <br>
                                <div class="row">

                                    <div class="col-3">
                                        <b>Who will lead the event from the Business Unit?<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="text" class="form-control" id="event_lead" name="event_lead" value="<?php echo $lead_event; ?>">
                                        </div>
                                    </div>
                                </div>


                                <br>
                                <div class="row">

                                    <div class="col-3">
                                        <b>What is the medical equipment’s needed to be procured for the event/activity?<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="text" class="form-control" id="medical_equipment_needed" name="medical_equipment_needed" value="<?php echo $medical_equipments; ?>">
                                        </div>
                                    </div>
                                </div>


                                <br>
                                <div class="row">

                                    <div class="col-3">
                                        <b>Deviation from the approved amount,if any:<span style="color:#ff0000">*</span></b>
                                    </div>

                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <input type="number" class="form-control" name="deviation_amount" id="deviation_amount" value="<?php echo $deviation_amount; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="remark">

                                </div>

                                <br><br>

                                <?php 
                                if ($pending_with_id==$USER_ID) { ?>
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
                                    
                               <?php }
                                ?>


                                <!-- <div class="row">
								<div class="col-3">
									<b>Signature</b>
								</div>
							</div> -->

                                <br><br>

                                <!-- <div class="row">
								<div class="col-3">
									Initiator’s Name: ___________________
								</div>
								
								<div class="col-3">
									Marketing Head: ___________________
								</div>
							</div>

							<br><br>

							<div class="row">
								<div class="col-3">
									Designation: ___________________ 
								</div>
								
								<div class="col-3">
									Date: ___________________
								</div>
							</div>

							<br><br>

							<div class="row">
								<div class="col-3">
									Date: ___________________
								</div>
							</div> -->

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
                                if ($pending_with_id==$USER_ID) { ?>
                                <button type="submit" class="exampleBox btn btn-primary rounded me-1">Submit</button>

                                <?php }?>
                            </div>
                            <!-- <div class="col">
								<a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
							</div> -->
                        </div>

                    </div>







                </div>


        </form>
    </div>


    <script>
        function addRemark() {
            var choice = $('input[name="choice"]:checked').val();
            if (choice == 'R') {
                $('.remark').after(`<div class="form-group basic Remark">
									<div class="input-wrapper">
												<label><b>Remark:<span style="color:#ff0000">*</span></b></label>
													<input type="text" value="" class="form-control" id="remark" name="remark" placeholder="" required="">
											
											</div>
									</div>`);

            } else {
                $('.Remark').empty();

            }


        }
        jQuery('#targetedSpeciality').multiselect({
            columns: 1,
            placeholder: 'Choose...',
            search: true
        });
    </script>

    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script>
        // jQuery('#Quarter').multiselect({
        // 	columns: 1,
        // 	placeholder: 'Choose...',
        // 	search: true
        // });
    </script>
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
        $(document).ready(function() {
            $('#CAMP_Q_Approve_FORM').submit(function() {
                if ($("input[name='choice']:checked").val()) {
                    return true;

                } else {
                    alert('please select your choice');
                    return false;
                }

                //return false;
            });

        });
    </script>


</body>

</html>