<?php
include 'includes/common.php';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
    echo 'Invalid Access Detected!!!';
    exit;
}

$disp_url = 'index_camp_activity.php';
$current_userid = $USER_ID;
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $current_userid . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");

$where = $having = $fdate = $tdate = $event_id = $status_id = $pending_with = $category = $txtDfrom = $txtDto = $params = $cond = $params2 = '';
$execute_query = false;


if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $fdate = $_POST['fdate'];
    $tdate = $_POST['tdate'];
    $event_id = $_POST['event_id'];
    $status_id = $_POST['status_id'];
    $pending_with = $_POST['pending_with'];
    $category = $_POST['category'];

    $params = '&event_id=' . $event_id . '&pending_with=' . $pending_with . '&txtDfrom=' . $fdate . '&txtDto=' . $tdate . '&userid=' . $USER_ID . '&status=' . $status_id . '&category=' . $category;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;

    if (isset($_GET['event_id'])) $event_id = $_GET['event_id'];
    if (isset($_GET['pending_with'])) $pending_with = $_GET['pending_with'];
    if (isset($_GET['txtDfrom'])) $fdate = $_GET['txtDfrom'];
    if (isset($_GET['txtDto'])) $tdate = $_GET['txtDto'];
    if (isset($_GET['status'])) $status_id = $_GET['status'];
    if (isset($_GET['category'])) $category = $_GET['category'];


    $params2 = '&event_id=' . $event_id . '&pending_with=' . $pending_with . '&txtDfrom=' . $fdate . '&txtDto=' . $tdate . '&userid=' . $USER_ID . '&status=' . $status_id . '&category=' . $category;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($event_id)) {
    $cond .= " and (t2.naf_no LIKE '%" . $event_id . "%')";
    $execute_query = true;
}
if ($pending_with != '') {
    $having = " HAVING pending_with LIKE '%" . $pending_with . "%'";
}
if (!empty($fdate)) {
    $cond .= " and t2.eventdate>='$fdate' ";
    $execute_query = true;
}
if (!empty($tdate)) {
    $cond .= " and t2.eventdate<='$tdate' ";
    $execute_query = true;
}

if (!empty($status_id)) {
    $cond .= " and t2.authorise='$status_id' ";
    $execute_query = true;
}

if (!empty($category)) {
    $cond .= " and t2.category_id='$category' ";
    $execute_query = true;
}

$STATUS_ARR = GetXArrFromYID("select pid,status_name from crm_status_master where deleted=0", '3');
$TYPE_ARR = GetXArrFromYID("select pid,type_name from crm_naf_type_master where deleted=0", "3");
$DIVISION_ARR = GetXArrFromYID("select divisionid,name from division ", '3');

$_q = "select CONCAT(t1.first_name, ' ', t1.last_name) AS pending_with,t2.pendingwithid,t2.id,t2.eventdate,t2.naf_no,t2.userid,t2.category_id,t2.level,t2.authorise,t2.budget_amount from users t1 left join crm_naf_main t2 on t2.pendingwithid=t1.id where 1 and t2.deleted=0 " . $cond . $having;
$_r = sql_query($_q, "seacrh query");

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
                <ion-icon name="chevron-back-outline" class="icon-color"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Need Assessment Form (Camp Activity)</div>
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


                                <div class="col-4">

                                    <div style="display:flex;">

                                        <div class="block1">
                                            <b>Pending with User:</b>
                                        </div>

                                        <div class="block2">
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control" id="pending_with" name="pending_with" placeholder="" value="<?php echo $pending_with; ?>" minlength="4">
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-4">
                                    <div style="display:flex;">

                                        <div class="block1">
                                            <b>Category:</b>
                                        </div>

                                        <div class="block2">
                                            <div class="input-wrapper">
                                                <?php echo FillCombo($category, 'category', 'COMBO', '0', $TYPE_ARR, '', 'form-control custom-select'); ?>
                                            </div>
                                        </div>

                                    </div>
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
                                                <input type="date" class="form-control" id="fdate" name="fdate" placeholder="" value="<?php echo $fdate; ?>">
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
                                                <input type="date" class="form-control" id="tdate" name="tdate" placeholder="" value="<?php echo $tdate; ?>">
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>

                            <br>

                            <div class="row">



                                <div class="col-4">

                                    <div style="display:flex;">

                                        <div class="block1">
                                            <b>NAF Ref.No:</b>
                                        </div>

                                        <div class="block2">
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control" id="event_id" name="event_id" placeholder="">
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-4">


                                    <div style="display:flex;">

                                        <div class="block1">
                                            <b>Status:</b>
                                        </div>

                                        <div class="block2">
                                            <div class="input-wrapper">
                                                <!-- <select class="form-control custom-select" id="status_id" name="status_id">
                                                    <option selected="" disabled="" value=""></option>
                                                    <option value='1'>Pending</option>
                                                    <option value="2">Accepted</option>
                                                    <option value="3">Approved</option>
                                                    <option value="4">Rejected</option>
                                                    <option value="5">Closed</option>
                                                </select> -->
                                                <?php echo FillCombo($status_id, 'status_id', 'COMBO', '0', $STATUS_ARR, '', 'form-control custom-select'); ?>
                                            </div>
                                        </div>

                                    </div>


                                </div>

                                <div class="col-4">
                                </div>

                            </div>

                            <div class="section full mt-2"><a>
                                    <div class="row" style="text-align: center;">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-upload shadowed  mt-1 me-1 mb-1" id="psa_search" class="btn" value="SEARCH" />
                                            <input type="button" class="btn btn-secondary" class="btn" onclick="clear_form(this.form);" value="CLEAR" />
                                        </div>
                                    </div>
                            </div>


                            <div class="section full mt-2"><a>
                                </a>
                                <div class="wide-block pt-2 pb-2"><a>

                                        <!--<div class="row">
                    <div class="col"><a href="naf_form.html"><button type="button" class="exampleBox btn btn-primary rounded me-1">Need Assessment Form</button></a>
                    </div>
                    <div class="col">
                        <a href="pma.html"><button type="button" class="exampleBox btn btn-primary rounded me-1">PMA</button></a>
                    </div>
                    <div class="col">
                        
                    </div>
                </div>-->

                                        <?php
                                        if ($PROFILE_ID == '22') { ?>
                                            <div class="row">
                                                <div class="col col-f">
                                                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="naf-btn custom-select" id="" placeholder="Need Assessment Form">
                                                        <option selected="" disabled="" value="">Need Assessment Form</option>
                                                        <option value="naf_quarter_approve.php?userid=<?php echo $USER_ID; ?>">NAF</option>
                                                    </select>
                                                </div>

                                                <!-- <div class="col col-f">
								<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="naf-btn custom-select" id="" required="">
									<option selected="" disabled="" value="">Project Market Analysis</option>
									<option value="pma_search.php?userid=<?php echo $USER_ID; ?>">PMA</option>
								</select>
							</div> -->

                                                <div class="col col-f">
                                                </div>
                                            </div>

                                        <?php }

                                        ?>



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
                                <!-- <th class="bg-purple">Status</th> -->
                                <th class="bg-purple">Pending with</th>
                                <!--th class="bg-purple">Approved by</th-->
                                <th class="bg-purple">Status</th>

                                <!--th class="bg-purple">Pending with</th-->
                                <!--th class="bg-purple">Edit/Delete</th-->
                            </tr>
                        </thead>
                        <tbody id="nafLisview_body">
                            <?php
                            for ($i = 0; $o = sql_fetch_object($_r); $i + 1) {
                                $naf_no = $o->naf_no;
                                $x_id = $o->id;
                                $event_date = $o->eventdate;
                                $requestorID = $o->userid;
                                $category = $o->category_id;
                                // $status=$o->authorise;
                                $pending_with_name = $o->pending_with;
                                $pendingwithID = $o->pendingwithid;
                                // $pendingwithquestionnire = $o->pendingwithquestionnire;
                                // $pending_with_id = $o->pendingwithid;
                                $approved_status_id = $o->authorise;
                                $division = GetXFromYID("SELECT division FROM users WHERE id='$requestorID' ");
                                $budget_amt = $o->budget_amount;
                                $x_total = GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$x_id' ");
                                $level = $o->level;
                                $event_url="";
									if (($approved_status_id == 1 || $approved_status_id==2 ) && $USER_ID==$pendingwithID) {
										$event_url='<a href="approve_camp_a.php?rid='.$x_id.'&userid='.$USER_ID.'">'.strtoupper($naf_no).'</a>';
									}else {
										$event_url='<a href="javascript:void(0)">'.strtoupper($naf_no).'</a>';
									}


                            ?>
                                <tr>
                                 
                                    <td><?php echo $event_url; ?></td>
                                    <td><?php echo $event_date; ?></td>
                                    <td><?php echo (isset($DIVISION_ARR[$division])) ? $DIVISION_ARR[$division] : ''; ?></td>
                                    <td><?php echo (isset($TYPE_ARR[$category])) ? $TYPE_ARR[$category] : ''; ?></td>
                                    <td><?php echo $x_total; ?></td>
                                    <td><?php echo $pending_with_name; ?></td>
                                    <!--td>BU Head</td-->
                                    <td><?php echo $STATUS_ARR[$approved_status_id]; ?></td>

                                    <!--td><button type="button" class="btn btn-upload shadowed  mt-1 me-1 mb-1">Edit/Delete</button></td-->

                                </tr>


                            <?php    }

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
        $(document).ready(function() {
            var table = $('#example').DataTable({

                responsive: true
            });
        });

        function clear_form(form) {
            for (j = 0; j < form.elements.length; j++) {
                if (form.elements[j].type == 'text' || form.elements[j].type == 'select-one') {
                    form.elements[j].value = '';
                }
            }
            $("input[type=date]").val("");
        }

        function validate() {
            var pending_with = document.getElementById("pending_with").value;
            var category = document.getElementById("category").value;
            var event_id = document.getElementById("event_id").value;
            var status_id = document.getElementById("status_id").value;
            var naf_from_date = document.getElementById("fdate").value;
            var userid = document.getElementById("userid").value;
            var naf_to_date = document.getElementById("tdate").value;
            var from_date = new Date(naf_from_date);
            var to_date = new Date(naf_to_date);
            var error = 0;

            if (naf_from_date == '' && naf_to_date == '' && pending_with == '' && event_id == '' && status_id == '' && category == '') {
                alert("Kindly select atleast one search criteria");
                error = 1;
                return false;
            }

            if (naf_from_date == '' && naf_to_date != '') {
                alert("Kindly select from date");
                error = 1;
                return false;
            }

            if (naf_from_date != '' && naf_to_date == '') {
                alert("Kindly select to date");
                error = 1;
                return false;
            }


            if (from_date > to_date) {
                alert("From Date Cannot be greater than To Date!!");
                error = 1;
                return false;
            }

            if (error == 0) {
                //window.document.location.href = 'index_PM_krishna.php?userid='+userid+'&fdate='+naf_from_date+'&tdate='+naf_to_date;
            }

        }
    </script>

</body>

</html>