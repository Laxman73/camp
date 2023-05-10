<?php
include 'includes/common.php';
$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$prid = (isset($_GET['prid'])) ? $_GET['prid'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$MODE = (isset($_GET['mode'])) ? $_GET['mode'] : 'I';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';
$shortcodemode = (isset($_GET['agraccessshorturl'])) ? $_GET['agraccessshorturl'] : '';
//if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))|| $USER_ID!='2')
if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
    echo 'Invalid Access Detected!!!';
    exit;
}
//getting role and profile of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$user_name = GetXFromYID("select user_name from users where id='" . $USER_ID . "'");

// defining mode
$sign_display = "display:";
$sign_edit = "display:none";
if (!empty($rid) && $PROFILE_ID == 22) {
    $MODE = 'R';
    $sign_display = "display:";
    $sign_edit = "display:none";
}

if (($PROFILE_ID == 6 || $PROFILE_ID == 7) && !empty($pid)) {
    $MODE = 'I';
    $info_id = GetXFromYID("select id from crm_hcp_information where deleted=0 and crm_request_main_id = $pid");
    $sign_display = "display:none";
    $sign_edit = "display:";
    $issubmitted = GetXFromYID("select count(*) from crm_hcp_agreement where deleted=0 and crm_hcp_information_id = $info_id");
    if (!empty($issubmitted)) {
        $MODE = 'R';
        $sign_display = "display:";
        $sign_edit = "display:none";
    }
} else if ($PROFILE_ID == 15) {
    $MODE = 'R';
    $sign_display = "display:";
    $sign_edit = "display:none";
}


//$hcpid=(isset($_GET['id']))?$_GET['id']:'';
$SPECILITY_ARR = GetXArrFromYID('select specialityid as id,specialityname as name from speciality where in_use=0  order by name ASC', '3');

$NAF_NO = GetXFromYID("select naf_no from crm_request_main where id=$pid and deleted=0 ");

$NAF_MAIN_ID = GetXFromYID("select id from crm_naf_main where naf_no='$NAF_NO' and deleted=0 ");

$data = GetDataFromID('crm_naf_hcp_details', 'naf_main_id', $NAF_MAIN_ID);

$Doctor_id = $data[0]->hcp_id;
$hcp_pan = $data[0]->hcp_pan;
$Honorium = $data[0]->honorarium_amount;
$hcp_role = $data[0]->role_of_hcp;
$hcp_address = $data[0]->hcp_address;

$HCP_INFORMATION_DATA = GetDataFromID('crm_hcp_information', 'crm_request_main_id', $pid);
$hcp_information_ID = $HCP_INFORMATION_DATA[0]->id;

$hcp_sign = GetXFromYID("select hcp_sign from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_information_ID");
$emp_sign = GetXFromYID("select company_sign from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_information_ID");
$sign_date = GetXFromYID("select  DATE(sign_date) from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_information_ID");
$topic=GetXFromYID("select  topic from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_information_ID");
$venue=GetXFromYID("select  venue from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_information_ID");
$meeting_date=GetXFromYID("select  meeting_date from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_information_ID");

$READ_MODE='A';
$is_agreement_submitted=GetXFromYID("select count(*)  from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_information_ID");
if ($is_agreement_submitted>0) {
    $READ_MODE='E';
}


if ($sign_date != '') {
    $sign_date = date('d M Y', strtotime($sign_date));
    $till_date = date('d-M-Y', strtotime($sign_date . ' + 90 days'));
} else {
    $sign_date = date('d M Y');
}
//$crm_request_id = $data[0]->crm_request_main_id;
$_q1 = "select firstname,lastname,qualification,mobile,otherstate,othercity from contactmaster where id='$Doctor_id' limit 100";
$_r1 = sql_query($_q1, "");

list($firstname, $lastname, $qualificationid, $mobile, $otherstate, $othercity) = sql_fetch_row($_r1);
$state = GetXFromYID("select statename from state where stateid='$otherstate'");
$_c_q = "select cityname,pincode from city where cityid='$othercity' ";
$_c_r = sql_query($_c_q, '');
list($city, $pincode) = sql_fetch_row($_c_r);

$qualification_name = (isset($QUALIFICATION_ARR[$qualificationid])) ? $QUALIFICATION_ARR[$qualificationid] : '';


$_q2 = "select hoscontactid from approval_doctor_hospital_association where contactid='$Doctor_id' ";
$_r2 = sql_query($_q2);
list($hostcontactid) = sql_fetch_row($_r2);
$hospital_name = (isset($HOSPITAL_ARR[$hostcontactid])) ? $HOSPITAL_ARR[$hostcontactid] : '';

// tab urls
$naf_form_url = "naf_form_pma.php?rid=$rid&userid=$USER_ID&pid=$pid";
$hcp_form_url = "hcp_form_camp.php?rid=$rid&userid=$USER_ID&pid=$pid";
$naf_agrmnt_url = "service_agreement_camp.php?rid=$rid&userid=$USER_ID&pid=$pid";
$qusetnr_url = "questionnaire.php?rid=$rid&userid=$USER_ID&pid=$pid";
$doc_upld_url = "document_upload.php?rid=$rid&userid=$USER_ID&pid=$pid";
$pdf_url  = "generate_pdf.php?rid=$rid&userid=$USER_ID&pid=$pid";


$check_questionnire = "select * from crm_agreement_link_sharing  where crm_naf_main_id='" . $rid . "' and pid='" . $pid . "' and crm_agreement_link_sharing.deleted=0 /*and link_share=1*/";
$check_quest_upload = GetXFromYID($check_questionnire);

$sql_question = "SELECT * FROM crm_hcp_information inner join crm_hcp_agreement on crm_hcp_agreement.crm_hcp_information_id=crm_hcp_information.id where crm_hcp_information.crm_request_main_id='" . $pid . "' and crm_hcp_information.deleted=0 and crm_hcp_agreement.deleted=0";
$check_quest_sub = GetXFromYID($sql_question);

$sql_mobilenum = "SELECT mobile FROM crm_request_main inner join crm_request_details on crm_request_details.crm_request_main_id=crm_request_main.id where crm_request_main.id='" . $pid . "' and crm_request_main.deleted=0 and crm_request_details.deleted=0";
$check_mobilenum = GetXFromYID($sql_mobilenum);

$hide = "style='display:';";
$submithide = "style='display:none';";
$questhide = "";


if ($check_quest_upload != "" && $shortcodemode == 1) {
    $hide = "style='display:none';";
    $submithide = "style='display:';";
}
// $submithide="style='display:';";
$hidequest = "";
$hidemsg = "";
$hidequest1 = "";
if ($check_quest_upload != "" && $shortcodemode == '' && $check_quest_sub == "") {
    $hidequest = "style='display:none';";
} else if ($check_quest_sub != "") {
    $hidequest1 = "style='display:none';";
}
$hidemsg = "Aggreement Link has been shared with the Doctor Through SMS";

$naf_activity_name = GetXFromYID("select naf_activity_name from crm_naf_main where id=" . $rid . " and deleted=0 ");

$HCP_NAF_DETAILS=GetDataFromID('crm_naf_hcp_details','naf_main_id',$rid,"");
$hcp_id=$HCP_NAF_DETAILS[0]->hcp_id;
$hcp_name=GetXFromYID("SELECT CONCAT(firstname, ' ', lastname) AS full_name FROM contactmaster where  id='$hcp_id' ");
$hcp_address=$HCP_NAF_DETAILS[0]->hcp_address;
// $sql_res=$adb->query($sql);
// $naf_activity_name=$adb->query_result($sql_res,0,'naf_activity_name');
function AmountInWords($number)
{
   $no = floor($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  return $result ;
}

$get_amount= AmountInWords($Honorium);
?>
<!doctype html>
<html lang="en">
<style>
    .custom-signature-upload {
        position: relative;
        display: flex;
        width: 100%;
        height: 200px;
    }


    .wrapper {
        border: 2px solid #e2e2e2;
        border-right: 0;
        border-radius: 8px 0 0 8px;
        padding: 5px;
    }

    canvas {
        background: #fff;
        width: 100%;
        height: 100%;
        cursor: crosshair;
    }

    button#clear_sign {
        height: 100%;
        background: #4b00ff;
        border: 1px solid transparent;
        border-left: 0;
        border-radius: 0 8px 8px 0;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
    }

    button#clear_sign span {
        transform: rotate(90deg);
        display: block;
    }

    button#clear_sign1 {
        height: 100%;
        background: #4b00ff;
        border: 1px solid transparent;
        border-left: 0;
        border-radius: 0 8px 8px 0;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
    }

    button#clear_sign1 span {
        transform: rotate(90deg);
        display: block;
    }

    .wrapper1 {
        border: 2px solid #e2e2e2;
        border-radius: 8px;
        padding: 5px;
    }
</style>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Microlabs</title>
    <meta name="description" content="">
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
        <div class="pageTitle">AGREEMENT FOR DISEASE AWARENESS/DIAGNOSTIC CAMP</div>
        <div class="right"></div>
    </div>


    <!--<div class="section full mt-7">
            <div class="wide-block pt-2 pb-2">

                <div class="row">
                    <div class="col-2">
                        <a href="naf_form_pma.php"><div class="tabBox">NAF Form</div></a>
                    </div>
                    <div class="col-2">
						<a href="hcp_form_pma.php"><div class="tabBox">HCP Information Form</div></a>
                    </div>
                    <div class="col-2">
                        <a href="hcp_agreement_pma.php"><div class="tabBox">HCP Agreement</div></a>
                    </div>
                </div>

            </div>
        </div>-->

    <?php include '_tabscamp.php'; ?>

    <div id="appCapsule">


        <div class="tab-content mt-1">
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                <div class="wide-block pt-2 pb-2 text-center">
                    <h2 style="color:red;"><b><?php echo $hidemsg; ?></b></h2>
                </div>

                <div class="section full mt-1">

                    <div class="wide-block pt-2 pb-2" <?php echo $hidequest; ?> <?php echo $hidequest1; ?>>


                        <ul class="nav nav-tabs style1" role="tablist" <?php echo $hide; ?>>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#Yes" role="tab" onclick="createShoutURL();">
                                    Yes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#No" role="tab" id="nooption" onclick="buttonClickedNo();">
                                    No
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2">
                            <div class="tab-pane fade show " id="Yes" role="tabpanel" <?php echo $hide; ?>>

                                <div class="row mt-3" style="width: 100%;justify-content: center;">
                                    <div class="col1-2" style="padding: 7px 0px;">
                                        <b>Mobile Number:</b>
                                    </div>

                                    <div class="col1-3">
                                        <div class="input-wrapper">
                                            <input type="number" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="+918763872828" required="" value="<?php echo $check_mobilenum; ?>">
                                        </div>
                                    </div>

                                    <div class="col1-1">
                                        <ion-icon name="pencil-outline" style="    font-size: 30px;"></ion-icon>
                                    </div>
                                </div>



                                <div class="copy-text mt-3" style="text-align: center;">
                                    <input type="text" class="text" value="" id="shorturl" readonly />
                                    <button type="button" onclick="fn_copyLink()">Copy Link <ion-icon name="copy" style="vertical-align: middle;"></ion-icon></button>
                                </div>

                                <div class="row mt-3" style="width: 100%;justify-content: center;">
                                    <button type="button" class="btn btn-primary btn-lg mr-1 mt-3 bg-green" onclick="saveconsent();">Send SMS</button>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>



                <div class="section mt-7">
                    <div class="card">
                        <div class="card-body">


                            <!--<div class="section full mt-2">
            <div class="wide-block pt-2 pb-2">

                <div class="row">
                    <div class="col-6 text-center">
                        <div class="text-center lh"><i>Private and Confidential</i></div>
                    </div>
                    <div class="col-6">
                        <div class="logo-div"><span class="logo-text">Micro Labs to add logo</span></div>
                    </div>
                </div>

            </div>
        </div>-->
                            <br>
                            <p class="text-center logo-text">AGREEMENT FOR DISEASE AWARENESS/DIAGNOSTIC CAMP</p>
                            <br>
                            <div class="wide-block pt-2 pb-2">

                                <div>
                                    <ul class="agg-text">

                                        This <b>DISEASE AWARENESS / DIAGNOSTIC CAMP AGREEMENT (‘Agreement’)</b>shall become
                                        effective as on the date of last signature of the parties <b><?php echo FormatDate(TODAY, 'H'); ?></b> by and between:<?php echo date('Y-m-d', strtotime(date('Y-m-d'). ' + 90 days')); ?>
                                        <br><br>
                                        <b>Micro Labs Limited</b>, a company existing under the laws of India, having its registered office at 31,
                                        Race Course Road, Bangalore - 560 001 (hereinafter referred to as <b>‘Company’</b> and/or <b>‘Micro labs’</b>)
                                        of the <b>ONE PART</b><br><br>
                                        <b>AND</b><br><br>

                                        <b>Dr.</b><?php echo $hcp_name; ?>, a healthcare professional, an Indian citizen having his/her address at <?php echo $hcp_address; ?>, bearing PAN <?php echo $hcp_pan; ?> (hereinafter referred to as the <b>‘Recipient-HCP’)</b> of the <b>OTHER PART</b>.
                                        <br><br>
                                        <i>The Company and the Recipient-HCP are hereinafter individually referred as ‘Party’ and collectively as ‘Parties’.</i>
                                        <br><br>
                                        <b><u>Summary</u></b>
                                        <br>
                                        The purpose of this written agreement is to sign off further to an oral agreement between the Parties – for the Recipient-HCP to support in organizing/conducting a Micro Labs initiated awareness, counselling and/or diagnostic camp (hereinafter referred to as <b>‘camp’</b>), as titled hereinunder:
                                        <br>
                                        <br>
                                        <b>TITLE OF THE CAMP: <input type="text" name="title" value="<?php echo $topic;?>" id="title" style="border: none;border-bottom: 1px solid lightgrey;"> </b><br><br>
                                        <b>DATE: <input type="date" name="mdate" id="mdate" value="<?php echo $meeting_date;?>" style="border: none;border-bottom: 1px solid lightgrey;"></b><br><br>
                                        <b>VENUE: <input type="text" name="venue" id="venue" value="<?php echo $venue;?>" style="border: none;border-bottom: 1px solid lightgrey;"></b><br>
                                        <br>
                                        As compensation of the Recipient-HCP’s time and efforts devoted in conducting the above-mentioned camp and/or sharing his/her advice with patients in the above-mentioned camp, the Parties have mutually agreed to an amount of INR <?php echo $Honorium;?></b>(Rupees <b><?php echo $get_amount;?></b>).
                                        <br><br>
                                        <b>MUTUAL INTEREST OF THE PARTIES</b>: This Camp is of mutual interest to the parties because it:<br>
                                        • creates awareness to general public or provide counsel on various disease, treatment options, mental/physical wellbeing, dietary, etc. and/or <br>
                                        • involve diagnosis of patient's medical condition to ascertain the medical needs of the patients to enable Micro Labs in its research and development.
                                        <br>
                                        <br>


                                        <b>RESPONSIBILITIES OF THE PARTIES: </b><br>
                                        • Micro Labs shall provide the Recipient with support, goods and/or services in accordance with the purpose of this Agreement and implementing arrangements, as necessary.<br>
                                        • Micro Labs’s support or the compensation paid hereof indicates no incentive or obligation for the recipient-hcp to prescribe, recommend or otherwise support Micro Labs’s products or services.<br>
                                        • Recipient-HCP hereby agrees to: Further to what is required and necessary in organizing/conducting the camp, including but not limited to, (a) broadcasting information of the camp via pamphlets, notice, banners etc. (b) setting up the required infrastructure to hold/conduct the camp, (c) distributing Patient awareness booklets/pamphlets etc., the Recipient-HCP shall:
                                        <br>


                                        <ul>
                                            <li>• In case of an awareness camp, provide educational and interactive activities for the attendees and supervise the camp. Any and all information communicated by the Recipient-HCP in a camp shall be to enhance knowledge and be disease or health condition specific, factual, clear and accurate and comply with the applicable laws and codes;</li>
                                            <li>• In case of a diagnostic camp, provide free or nominally charged consultation to attendees’ basis their initial diagnosis, supervise the camp and provide adequate counsel to the Patients.</li>
                                        </ul>

                                        <br>
                                        <b>OTHER TERMS AND CONDITIONS:</b><br>
                                        • <b>Camp Notification:</b> Recipient-HCP shall ensure that there is no promotion of the Recipient-HCP or Micro Labs products and brands while promoting/advertising the camp. The content of the banner/pamphlet should not come across as Recipient-HCP advertisement/promotion in any manner.<br><br>

                                        • <b>Camp Venue:</b> Recipient-HCP shall ensure that camps are set up in a location/venue that is convenient for majority of the patients to meet the goals and are not held at venues which could reasonably be perceived as lavish or extravagant.<br><br>

                                        • <b>Personal Data:</b> Recipient-HCP shall ensure there is no transfer of patient’s personal identifiable information such as name, contact number or email address, etc. to Micro Labs.<br><br>

                                        • <b>Term & Termination</b> - This Agreement shall be effective until <b><?php echo date('d-m-Y', strtotime($sign_date. ' + 90 days')); ?></b> and can be terminated by either Party by giving a prior written notice of one (1) week to the other Party.<br><br>

                                        • <b>Representations and Warranties</b> - The Recipient-HCP hereby confirms that (a) the services provided by the him/her, and the compensation received by him/her under this Agreement follow the requirements of the Recipient-HCP’s employer or any Central or State government health care authority/body and (b) the performance of the Recipient-HCP’s obligation under this Agreement does not conflict with the performance of his/her other obligations under any other agreement.<br><br>

                                        • <b>Compliance</b> - During the performance of his/her obligation under this Agreement, the Recipient-HCP agrees to comply with all applicable laws and rules, bylaws, statutory orders, guidance’s & regulations made thereunder and/or of any court, governmental or administrative order and/or guidelines including, without limitation, guidelines issued by the National Medical Commission, the Code of Conduct and Business Ethics Policies of the Company.<br><br>

                                        • <b>Governing Law and Arbitration</b> - This Agreement shall be governed, in all respect, by applicable Indian law(s) and the Parties hereby submit to the exclusive jurisdiction of the courts in New Delhi. Any dispute in connection with this Agreement shall be settled by amicable negotiation between the Parties hereto. <br><br>

                                        • <b>Severability</b> - If any of the provisions of this Agreement is held to be illegal or invalid by a court of competent jurisdiction, the remaining provisions shall be enforceable according to the terms of this Agreement.<br><br>



                                    </ul>
                                </div>




                            </div>

                            <br>

                            <div class="row mlm-150">
                                <div class="col-6">

                                    SIGNED AND DELIVERED by <b>MICRO LABS LIMITED</b>, acting
                                    through its Authorised Signatory, Mr. <b>XX</b>, (XX) the within
                                    named Party of the First Part.<br>
                                    <b>DATE:</b><?php echo $sign_date; ?>

                                </div>
                                <div class="col-6">


                                    <center>

                                        <div class="custom-signature-upload" style="<?php echo $sign_edit; ?>">
                                            <div class="wrapper">
                                                <canvas id="signature-pad"></canvas>

                                            </div>
                                            <div class="clear-btn">
                                                <button id="clear_sign"><span> Clear </span></button>
                                            </div>
                                        </div>

                                        <div class="custom-signature-upload" style="<?php echo $sign_display; ?>">
                                            <div class="wrapper1">
                                                <img src="<?php echo $emp_sign; ?>" width="95%" height="95%" alt="Italian Trulli">
                                            </div>
                                        </div>

                                    </center>
                                </div>
                            </div>


                            <div class="row mlm-150">
                                <div class="col-6">

                                    <span>Signed and Delivered by <b><?php echo $hcp_name;?></b>, the within named Party of the Second Part.
                                        Part.<br>
                                        <b>DATE:</b><?php echo $sign_date; ?>

                                </div>
                                <div class="col-6">


                                    <center>
                                        <div class="custom-signature-upload" style="<?php echo $sign_edit; ?>">
                                            <div class="wrapper">
                                                <canvas id="signature-pad1"></canvas>

                                            </div>
                                            <div class="clear-btn">
                                                <button id="clear_sign1"><span> Clear </span></button>
                                            </div>
                                        </div>
                                        <div class="custom-signature-upload" style="<?php echo $sign_display; ?>">
                                            <div class="wrapper1">
                                                <img src="<?php echo $hcp_sign; ?>" width="95%" height="95%" alt="Italian Trulli">
                                            </div>
                                        </div>
                                    </center>
                                    </center>

                                </div>
                            </div>


                            <br>





                        </div>
                    </div>
                </div>




                <div class="section full mt-2">
                    <div class="wide-block pt-2 pb-2">
                        <form action="save_agreement_camp.php" method="post">
                            <input type="hidden" name="crm_hcp_information_id" value="<?php echo $hcp_information_ID; ?>">
                            <input type="hidden" name="rid" id="rid" value="<?php echo $rid; ?>">
                            <input type="hidden" name="prid" id="prid" value="<?php echo $prid; ?>">
                            <input type="hidden" name="pid" id="pid" value="<?php echo $pid; ?>">
                            <input type="hidden" name="userid" value="<?php echo $USER_ID; ?>">
                            <textarea id="signature1" name="signature1" style="display: none"></textarea>
                            <textarea id="signature2" name="signature2" style="display: none"></textarea>
                            <input type="hidden" name="shortcodemode" id="shortcodemode" value="<?php echo $shortcodemode; ?>">
                            <input type="hidden" name="shortcodeID" id="shortcodeID" value="0">
                            <input type="hidden" name="user_name" id="user_name" value="<?php echo $user_name; ?>">
                            <input type="hidden" name="Atitle" id="Atitle">
                            <input type="hidden" name="Adate" id="Adate">
                            <input type="hidden" name="Avenue" id="Avenue">

                            <div class="row">
                                <!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
								</div> -->
                                <div class="col">
                                    <?php
                                    if ($READ_MODE!='E') { ?>
                                        <button type="submit" id="submitbutton" class="exampleBox btn btn-primary rounded me-1" onclick="validate();">Submit</button>
                                 <?php   }

                                    ?>
                                </div>
                                <!-- <div class="col">
									<a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
								</div> -->
                            </div>
                        </form>


                    </div>
                </div>
            </div>



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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.5/signature_pad.min.js" integrity="sha512-kw/nRM/BMR2XGArXnOoxKOO5VBHLdITAW00aG8qK4zBzcLVZ4nzg7/oYCaoiwc8U9zrnsO9UHqpyljJ8+iqYiQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            var canvas = document.getElementById("signature-pad");
            var canvas1 = document.getElementById("signature-pad1");

            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);

                canvas1.width = canvas1.offsetWidth * ratio;
                canvas1.height = canvas1.offsetHeight * ratio;
                canvas1.getContext("2d").scale(ratio, ratio);
            }
            window.onresize = resizeCanvas;
            resizeCanvas();

            var signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(250,250,250)'
            });

            document.getElementById("clear_sign").addEventListener('click', function() {
                signaturePad.clear();
            })

            var signaturePad1 = new SignaturePad(canvas1, {
                backgroundColor: 'rgb(250,250,250)'
            });

            document.getElementById("clear_sign1").addEventListener('click', function() {
                signaturePad1.clear();
            })

            function validate() {
                var title=$('#title').val();
                var mdate=$('#mdate').val();
                var venue=$('#venue').val();
                var canvas = document.getElementById("signature-pad");
                var data1 = canvas.toDataURL('image/png');
                document.getElementById('signature1').value = data1;

                var canvas1 = document.getElementById("signature-pad1");
                var data2 = canvas1.toDataURL('image/png');
                document.getElementById('signature2').value = data2;

                $('#Atitle').val(title);
                $('#Adate').val(mdate);
                $('#Avenue').val(venue);
                return false;
            }

            function buttonClickedNo() {
                $("#submitbutton").show();
                $("#Yes").css("display", "none");
                //alert("Rating : "+$("input[name='question_rating']:checked").val());
            }


            function fn_copyLink() {
                var shorturl = document.getElementById("shorturl");

                document.getElementById("shorturl").select();
                document.execCommand('copy');


            }

            function createShoutURL() {
                var consent = 1;
                var wid = 1;
                var imagedata = '';
                var crmreqID = $("#rid").val();
                var crmPID = $("#pid").val();
                var username = $("#user_name").val();

                $("#submitbutton").css('display', 'none');
                $("#Yes").css('display', '');
                jQuery.ajax({
                    url: '../../index.php?module=Users&action=Authenticate',
                    data: {
                        user_name: '.' + username,
                        user_password: 123456,
                        view_questionnaire: 1,
                        type: 8,
                        naf_requestid: crmreqID,
                        pid: crmPID,
                        plannedDoctor: true
                    },
                    success: function(response) {
                        result = JSON.parse(response);
                        $("#shorturl").val(result.shourtURL);
                        $("#shortcodeID").val(result.shourtURLId);

                    }
                });

                console.log("hello ooo");
                //$("#btnsubmit").val('Submit');
                //return true;
            }

            function saveconsent() {
                var consent = 1;
                var wid = 1;
                var imagedata = '';
                var doc_mobile = document.getElementById('mobilenumber').value;
                var username = document.getElementById('user_name').value;
                var crmreqID = $("#rid").val();
                var crmreqpid = $("#pid").val();
                if (doc_mobile == "0" || doc_mobile == "") {
                    alert('Please Enter Mobile Number');
                    //bootbox.dialog({message:'Please Enter Mobile Number', backdrop:false, onEscape:true});
                    //btn.disabled = false;
                    return false;
                } else if (doc_mobile.length < 10 || doc_mobile.length >= 11) {
                    alert('Please Enter 10 Digit Number');
                    //bootbox.dialog({message:'Please Enter 10 Digit Number', backdrop:false, onEscape:true});
                    //btn.disabled = false;
                    return false;
                } else if ((doc_mobile[0] == 0) || (doc_mobile[0] == 1) || (doc_mobile[0] == 2) || (doc_mobile[0] == 3) || (doc_mobile[0] == 4) || (doc_mobile[0] == 5)) {
                    alert('Please Enter Mobile No. starting with 6,7,8 or 9');
                    //bootbox.dialog({message:'Please Enter Mobile No. starting with 6,7,8 or 9', backdrop:false, onEscape:true});
                    //btn.disabled = false;
                    return false;

                }
                var shortcode_ID = $("#shortcodeID").val();

                jQuery.ajax({
                    url: '../../index.php?module=Users&action=Authenticate',
                    data: {
                        user_name: '.' + username,
                        user_password: 123456,
                        view_questionnaire: 1,
                        type: 9,
                        naf_requestid: crmreqID,
                        shortcodeid: shortcode_ID,
                        pid: crmreqpid,
                        docmobile: doc_mobile,
                        plannedDoctor: true
                    },
                    success: function(result) {
                        result = JSON.parse(result);
                        if (result.status) {
                            alert(result.message);
                            //bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:green;"><b>Success:<b></font>', backdrop:false, onEscape:true});
                            location.reload();
                            $("#nooption").prop('disabled', true);
                        } else {
                            alert(result.message);
                            //bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:red;"><b>Warning:<b></font>', backdrop:false, onEscape:true});
                            //$("#btnsubmit").val('Submit');
                            //$("#btnsubmit").prop('disabled', false);
                            $("#nooption").prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('There was some error in uploading consent, Please try again');
                        //bootbox.dialog({message:'There was some error in uploading consent, Please try again', title:'<font style="font-size: 16px;color:red;"><b>Warning:<b></font>', backdrop:false, onEscape:true});
                        //$("#btnsubmit").val('Submit');
                        //$("#btnsubmit").prop('disabled', false);
                        $("#nooption").prop('disabled', false);
                    }
                });

                console.log("hello ooo");
                //$("#btnsubmit").val('Submit');
                //return true;
            }
        </script>

</body>

</html>