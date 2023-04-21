<?php
include 'includes/common.php';
$rid=(isset($_GET['rid']))?$_GET['rid']:'';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$USER_ID = (isset($_GET['userid']))?$_GET['userid']:'';
$MODE = (isset($_GET['mode']))?$_GET['mode']:'I';
$display_all = (isset($_GET['display_all']))?$_GET['display_all']:'0';
$shortcodemode = (isset($_GET['agraccessshorturl']))?$_GET['agraccessshorturl']:'';
//if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))|| $USER_ID!='2')
if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID)))
{
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
if(!empty($rid) && $PROFILE_ID == 22){
    $MODE = 'R';
    $sign_display = "display:";
    $sign_edit = "display:none";
}

if($PROFILE_ID == 6 || $PROFILE_ID == 7){
    $MODE = 'I';
    $info_id = GetXFromYID("select id from crm_hcp_information where deleted=0 and crm_request_main_id = $pid");
    $sign_display = "display:none";
    $sign_edit = "display:";
    $issubmitted = GetXFromYID("select count(*) from crm_hcp_agreement where deleted=0 and crm_hcp_information_id = $info_id");
    if(!empty($issubmitted)){
        $MODE = 'R';
        $sign_display = "display:";
        $sign_edit = "display:none";
    }   
}else if($PROFILE_ID == 15){
    $MODE = 'R';
    $sign_display = "display:";
    $sign_edit = "display:none";
}

//$hcpid=(isset($_GET['id']))?$_GET['id']:'';
$SPECILITY_ARR = GetXArrFromYID('select specialityid as id,specialityname as name from speciality where in_use=0  order by name ASC', '3');
$data = GetDataFromID('crm_request_details', 'crm_request_main_id', $pid);

$Doctor_id = $data[0]->hcp_id;
$hcp_pan = $data[0]->hcp_pan;
$Honorium=$data[0]->honorarium_amount;
$hcp_role=$data[0]->role_of_hcp;



$HCP_INFORMATION_DATA = GetDataFromID('crm_hcp_information', 'crm_request_main_id', $pid);
$hcp_information_ID=$HCP_INFORMATION_DATA[0]->id;

$hcp_sign=GetXFromYID("select hcp_sign from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_information_ID");
$emp_sign=GetXFromYID("select company_sign from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_information_ID");
$sign_date=GetXFromYID("select  DATE(sign_date) from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_information_ID");

if($sign_date!=''){
    $sign_date = date('d M Y', strtotime($sign_date));
}
else{
    $sign_date = date('d M Y');
}
//$crm_request_id = $data[0]->crm_request_main_id;
$_q1="select firstname,lastname,qualification,mobile,otherstate,othercity from contactmaster where id='$Doctor_id' limit 100";
$_r1=sql_query($_q1,"");

list($firstname,$lastname,$qualificationid,$mobile,$otherstate,$othercity)=sql_fetch_row($_r1);
$state=GetXFromYID("select statename from state where stateid='$otherstate'");
$_c_q="select cityname,pincode from city where cityid='$othercity' ";
$_c_r=sql_query($_c_q,'');
list($city,$pincode)=sql_fetch_row($_c_r);

$qualification_name=(isset($QUALIFICATION_ARR[$qualificationid]))?$QUALIFICATION_ARR[$qualificationid]:'';

$_q2 = "select hoscontactid from approval_doctor_hospital_association where contactid='$Doctor_id' ";
$_r2 = sql_query($_q2);
list($hostcontactid) = sql_fetch_row($_r2);
$hospital_name = (isset($HOSPITAL_ARR[$hostcontactid])) ? $HOSPITAL_ARR[$hostcontactid] : '';

// tab urls
$naf_form_url = "naf_form_pma.php?rid=$rid&userid=$USER_ID&pid=$pid";
$hcp_form_url = "hcp_form.php?rid=$rid&userid=$USER_ID&pid=$pid";
$naf_agrmnt_url = "hcp_agreement.php?rid=$rid&userid=$USER_ID&pid=$pid";
$qusetnr_url = "questionnaire.php?rid=$rid&userid=$USER_ID&pid=$pid";
$doc_upld_url = "document_upload.php?rid=$rid&userid=$USER_ID&pid=$pid";
$pdf_url  = "generate_pdf.php?rid=$rid&userid=$USER_ID&pid=$pid";

$check_questionnire = "select * from crm_agreement_link_sharing  where crm_naf_main_id='".$rid."' and pid='".$pid."' and crm_agreement_link_sharing.deleted=0 and link_share=1";
$check_quest_upload=GetXFromYID($check_questionnire);

$sql_question = "SELECT * FROM crm_hcp_information inner join crm_hcp_agreement on crm_hcp_agreement.crm_hcp_information_id=crm_hcp_information.id where crm_hcp_information.crm_request_main_id='".$pid."' and crm_hcp_information.deleted=0 and crm_hcp_agreement.deleted=0";
$check_quest_sub=GetXFromYID($sql_question);

$sql_mobilenum = "SELECT mobile FROM crm_request_main inner join crm_request_details on crm_request_details.crm_request_main_id=crm_request_main.id where crm_request_main.id='".$pid."' and crm_request_main.deleted=0 and crm_request_details.deleted=0";
$check_mobilenum=GetXFromYID($sql_mobilenum);

$hide="style='display:';";
$submithide="style='display:none';";
$questhide="";

if($check_quest_upload!="" && $shortcodemode==1)
{
    $hide="style='display:none';";
    $submithide="style='display:';";
    
}

// $submithide="style='display:';";
$hidequest = "";
$hidemsg = "";
$hidequest1 = "";                                                          // need to change this part (dont know heirachy yet) -> remove few conds if no heirachy
if($check_quest_upload!="" && $shortcodemode=='' && $check_quest_sub=="")
{
    $hidequest = "style='display:none';";
    $hidemsg = "Aggreement Link has been shared with the Doctor Through SMS";
}
else if($check_quest_sub!="")
{
    $hidequest1 = "style='display:none';";
}

$naf_activity_name=GetXFromYID("select naf_activity_name from crm_naf_main where id=".$rid." and deleted=0 ");
$e_sign_cheque_date=GetXFromYID("select e_sign_cheque_date from crm_request_main where id=".$rid." and deleted=0 ");

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

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
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
<style>
    .custom-signature-upload {
    position: relative;
    display: flex;  
    width: 100%;
    height: 125px;  
}
canvas {
    background: #fff;
    width: 250px;
    height: 100%;
    cursor: crosshair;
}
@media screen and (max-width: 600px) {
  canvas {
    /* background: #FF0; */
    width: 100%;
  }
}
div.p-center {
  text-align: center;
}
.wrapper {
    border: 2px solid #e2e2e2;
    border-right: 0;    
    border-radius: 8px 0 0 8px;
    padding: 5px;   
}

.wrapper1 {
    border: 2px solid #e2e2e2;   
    border-radius: 8px;
    padding: 5px;   
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
</style>
</head>

<body>

    <div class="appHeader bg-primary">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">ADVISORY BOARD SERVICE AGREEMENT</div>
        <div class="right"></div>
    </div>

    <div class="tab-content " >
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                <div class="wide-block pt-2 pb-2 text-center"><h2 style="color:red;"><b><?php echo $hidemsg;?></b></h2></div>

                <div class="section full mt-1">
                   
                    <div class="wide-block pt-2 pb-2" <?php echo $hidequest;?> <?php echo $hidequest1;?>>

                       
                        <ul class="nav nav-tabs style1" role="tablist" <?php echo $hide;?>>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#Yes" role="tab" onclick="createShoutURL();">
                                    Yes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#No" role="tab"  id="nooption" onclick="buttonClickedNo();">
                                    No
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2">
                            <div class="tab-pane fade show " id="Yes" role="tabpanel" <?php echo $hide;?>>

                            <div class="row mt-3" style="width: 100%;justify-content: center;">
                                <div class="col1-2" style="padding: 7px 0px;">
                                    <b >Mobile Number:</b>
                                </div>
                                                
                                <div class="col1-3">
                                    <div class="input-wrapper">
                                        <input type="number" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="+918763872828" required="" value="<?php echo $check_mobilenum;?>" readonly>
                                    </div>
                                </div>
                                
                                <div class="col1-1">
                                        <ion-icon name="pencil-outline" style="    font-size: 30px;"></ion-icon>
                                </div>
                            </div>
                            
                            

                            <div class="copy-text mt-3" style="text-align: center;">
                                <input type="text" class="text" value="" id="shorturl" readonly/>
                                <button type="button" onclick="fn_copyLink()">Copy Link <ion-icon name="copy" style="vertical-align: middle;"></ion-icon></button>                              
                            </div>

                            <div class="row mt-3" style="width: 100%;justify-content: center;"> 
                            <button type="button" class="btn btn-primary btn-lg mr-1 mt-3 bg-green" onclick="saveconsent();">Send SMS</button> 
                            </div>

                            </div>

                    </div>
                </div>
        
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
    

    <div id="appCapsule">

        <div class="tab-content mt-1">

                        
        <div class="section mt-7">
            <div class="card">
                <div class="card-body">
                
                
        <div class="section full mt-2">
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
        </div>
            <br>
            <p class="text-center logo-text">ADVISORY BOARD SERVICE AGREEMENT</p>
            <br>
                <div class="wide-block pt-2 pb-2">
    
        <div>
                <ul class="agg-text">
                
                This <b>ADVISORY BOARD SERVICE AGREEMENT (‘Agreement’) </b>shall become
                effective as on the date of last signature of the parties <b><?php echo FormatDate(TODAY,'H');?></b> by and between:
                <br><br>
                <b>Micro Labs Limited</b>, a company existing under the laws of India, having its registered office at 31,
                Race Course Road, Bangalore - 560 001 (hereinafter referred to as <b>‘Company’</b> and/or <b>‘Micro labs’</b>)
                of the <b>ONE PART</b><br><br>
                <div class="p-center"><b>AND</b><br><br></div>
                <b> Dr. <?php echo $firstname . ' ' . $lastname; ?> </b>, a registered medical practitioner according to the applicable laws of India and guidelines laid down by the relevant regulatory body, an Indian citizen having his/her address at  <b> <?php echo $state,','.$city.',',$pincode; ?> </b> bearing PAN <b> <?php echo $hcp_pan; ?> </b> (hereinafter
                referred to as <b>‘Advisor’</b>) of the <b>OTHER PART</b>.
                <br><br>
                <i>The Company and the Advisor are hereinafter individually referred as ‘Party’ and collectively as
                ‘Parties’.</i>
                <br><br>
                <div class="p-center"><u>WHEREAS</u></div>
                <br><br>
                A. The Company inter alia is engaged in the business of manufacturing, developing, formulating, marketing, distributing, exporting and selling various pharmaceutical products in India and abroad.
                <br><br>
                B. The Advisor has represented to the Company that the he/she is a qualified medical practitioner holding appropriate degree in the field of medicine and is registered with the National Medical Commission of India.
                <br><br>
                C. The Advisor is a specialist in the therapeutic field that the Company is either dealing in and/or plans to deal in. The Company intends to engage the Advisor to provide advisory services, as a member of an advisory board meeting,<i> either physically or virtually.</i>
                <br><br>
                
                NOW, THEREFORE, in consideration of the representations, warranties, covenants and agreements
                contained in this Agreement, the Company and the Advisor do hereby agree as follows:
                <br><br>
                
                <div class="p-center"><b>1. Scope of Services</b><br><br></div>
                1.1 The Company hereby engages the Advisor to provide advisory and consultancy services in the following meeting:
                <br><br>
                <ol type="a">
                <li>TOPIC       :</li>
                <li>VENUE       :</li>
                <li>DATE        :</li>
                </ol>
                <br><br>
                1.2 The objectives of the meeting shall include the following, in entirety or part(s) thereof:
                <br><br>
                <ul>
                <li>• obtain expert scientific advice, </li>
                <li>• assess commercial and/or health-care related issues, </li>
                <li>• better understand the external environment,</li>
                <li>• assess the use of products approved or in development, </li>
                <li>• devise clinical and medical strategies</li>
                <li>• identify scientific projects and initiatives for unmet medical needs and/or untapped areas</li>
                <li>• strategic insights for new product launch and future therapy</li>
                </ul>
                <br><br>

                <div class="p-center"><b>2. Fees</b><br><br></div>
                2.1 In consideration of the services rendered by the Advisor to the Company for the above-mentioned meeting, the Parties have mutually agreed to an amount of INR  <b> <?php echo $Honorium;?></b>
                (Rupees <b><?php echo $get_amount;?></b>) as fee. Parties agree that this would constitute full and
                complete payment for the services to be rendered under this agreement, computed as per the
                Company’s Fair Market Value policies.
                <br><br>
                2.2 Company (or a Company contracted third party) will book and pay for all travel and accommodation costs of the Advisor, as per applicable policies, if travel and accommodation is required for the purpose of this meeting.
                <br><br>
                2.3 The Advisor shall raise an invoice on the Company for the aforementioned fee, along with GST, as applicable. The Advisor shall also be responsible to file his/her GST returns in a timely manner as stipulated under law so that the Company can obtain input credit. All payments made by the Company, shall be subject to deduction of appropriate taxes at source, as per applicable laws.
                <br><br>
                <div class="p-center"><b>3. Representation and Warranties</b></div><br><br>
                
                
                3.1 As an independent contractor, Advisor agrees to the following requirements:
                <br>
                <ol type="a">
                    <li>a.  Provide correct and complete references to any third party’s work used in the advisory material</li>
                    <li>b.  Provide a copy of any material used for the advisory service and permit the Company to use the advisory material without any additional cost.  </li>
                </ol>
                <br><br>
                3.2 Advisor confirms that the services provided by the Advisor, and the payments received by the Advisor under this Agreement are in compliance with the requirements of Advisor’s employer (e.g. hospital management) or any Central or State government health care authority / body.
                <br><br>
                3.3 The Advisor hereby represents and warrants that:
                <br><br>
                <ol type="a">
                    <li>a)  This Agreement casts a valid, legal and binding obligation upon him/her and is enforceable in accordance with its terms, conditions and applicable laws, rules, statutes, guidance, regulations, etc., </li>
                    <li>b)  The performance of the Advisor’s obligation under this Agreement does not conflict with the performance of the Advisor’s other obligations under any other agreement, instrument or understanding, oral or written, to which he/she may be bound. </li>
                    <li>c)  The Advisor has not and will not in future during the Term, enter into any similar, identical arrangement which shall conflict/compete with or prohibit the performance of the obligations under this Agreement.</li>
                </ol><br><br>
                <div class="p-center"><b>4. Term &amp; Termination</b></div><br><br>
				<div style="<?php echo $sign_edit;?>">
                4.1 This Agreement shall commence on the <?php echo FormatDate(TODAY,'H'); ?> shall continue to be in full force and effect until <b><?php echo date('Y-m-d', strtotime(date('Y-m-d'). ' + 90 days')); ?> </b>, unless earlier terminated by either Party in accordance with the provisions of this Section (<b>‘Term’</b>).
				</div>
				<div style="<?php echo $sign_display;?>">
                4.1 This Agreement shall commence on the <?php echo $e_sign_cheque_date; ?> shall continue to be in full force and effect until <b><?php echo date('Y-m-d', strtotime($e_sign_cheque_date. ' + 90 days')); ?></b>, unless earlier terminated by either Party in accordance with the provisions of this Section (<b>‘Term’</b>).
				</div>
                <br><br>
                4.2 This Agreement can be terminated by either side by giving a prior written notice of one (1) week.
                <br><br>
                <div class="p-center"><b>5. Confidentiality</b></div>
                <br><br>
                5.1 The Advisor undertakes and agrees to keep strictly confidential, any information or data disclosed to him/her, whether orally or otherwise, tangible or intangible, directly or indirectly by the Company, during the meeting, and/or while rendering the Services and/or from the other Advisors, and further undertakes not to disclose the same to any third party(ies) by publication, oral dissemination or otherwise or use the same.
                <br><br>
                5.2 The provisions of this clause will survive 10 years post termination of this Agreement.
                <br><br>
                5.3 <b>No Grant of Rights:</b> Nothing in this Agreement, nor any disclosure of information by the Company before or after its execution, shall operate to confer any intellectual property rights on the Advisor, nor be effective to license or transfer to the Advisor any right, title or interest in the Company’s process or information. Further, the Advisor undertakes he shall not claim any rights in the information shared by the Company under the terms of this Agreement. 
                <br><br>
                <div class="p-center"><b>6. Intellectual Property Rights</b></div> 
                <br><br>
                6.1 The Advisor undertakes and assures that the Advisor shall not, at any time during the subsistence of the Agreement or thereafter, infringe or violate or interfere with any patent, copyright, database right, design, trade secret, trademark and/or any intellectual property rights and/or property right and/or any other rights available to or vested in the name of the Company and/or any third party(ies).
                <br><br>
                6.2 The Advisor agrees and acknowledges that in the course of discussion, meetings or while rendering Services to the Company, any and all ideas, concepts, information, analyses, reports, brochures, handouts, documents or other work-product developed, generated or compiled throughout the entire process of rending such Services or incidental thereto, shall at all times and irrevocably be the property of the Company.
                <br><br>
                6.3 The Advisor further acknowledges and agrees that the Company shall be the exclusive owner of and/or shall have sole and exclusive intellectual property rights including moral rights into any invention and discovery made by the Advisor during the performance of its obligations under the Agreement. The Advisor hereby in principal waives of any and all his/her rights in such intellectual property and further agrees to execute all documents necessary to transfer the rights in such invention to the Company without delay or demur, to enable the Company to apply for the said patents/trademarks or to obtain such other intellectual property protection.
                <br><br>
                6.4 <b>THE COMPANY SHALL BE FULLY ENTITLED TO USE ANY AND ALL IDEAS OR SUGGESTIONS AND DATA RECEIVED, CREATED OR GENERATED DURING THE MEETING AND/OR DURING THE TERM OF THIS AGREEMENT.</b>
                <br><br>
                6.5 The recording of talk/interview/live debate/answer to queries/solution sets/promotional material/presentations/seminars, given for the portal or for elsewhere will remain property of the Company and Company may use the same for medical education purposes or at its discretion. The Advisor hereby irrevocably assigns all rights, title and interests that he holds or acquires in the above referred work products to the Company. 
                <br><br>
                <div class="p-center"><b>7. Compliance</b></div>
                <br><br>    

                7.1 During the performance of his/her obligation under this Agreement, the Advisor agrees to comply with all applicable laws and rules, bylaws, statutory orders, guidance’s & regulations made thereunder and/or of any court, governmental or administrative order and or guidelines including, without limitation, guidelines issued by the National Medical Commission, the Code of Conduct and Business Ethics Policies of the Company.
                <br><br>    
                7.2 The Advisor hereby agrees to indemnify and keep indemnified the Company and its directors, officers and employees, assigns, agents, etc., in respect of (i) an actual and/or threatened breach of the terms and conditions (ii) breach of applicable laws (iii) breach of representation and warranties (iv) breach of any third-party intellectual property rights, etc., (v) misrepresentation and misconduct of this Agreement by the Advisor.
                <br><br>    
                 7.3    The Advisor would not hold the Company liable in any manner whatsoever for any damages, financial or otherwise, which may result because of the above-stated reasons or even otherwise.
                <br><br>    
                <b>8. Independent Parties</b> - It is the express understanding and intention of the Parties that the Advisor will be rendering the Services in its capacity as a Consultant and is not an employee, representative, delegate, agent, joint venture, franchise or partner of the Company. Advisor shall have no authority to assume, create, or enlarge any obligation or commitment on behalf of the Company without the prior written consent of the Company.
                <br><br>    
                <b>9. Entire Agreement</b> - This Agreement constitutes this entire agreement between the Parties and
                supersedes all prior agreements and understandings, whether written or oral, relating to the
                subject matter of this Agreement.
                <br><br>    
                <b>10. Modification</b> - No modification or amendment of this Agreement shall be valid or binding
                unless made in writing and, in the case of an amendment, executed by both the Parties.
                <br><br>    
                <div class="p-center"><b>11. Governing Law and Arbitration</b></div>
                <br><br>    
                11.1 This Agreement will be governed, in all respect, by applicable Indian law(s) and the Parties
                hereby submit to the exclusive jurisdiction of the courts in New Delhi.
                <br><br>    
                11.2  Any dispute in connection with this Agreement shall be in the first instance settled by amicable negotiation between the Parties hereto. In default of such amicable settlement for all or any matter within 15 days of the commencement of amicable proceedings, such matters as remain unsettled by negotiation as aforesaid shall be finally settled under the Arbitration and Conciliation Act, 1996, by single arbitrator appointed in accordance with the rules framed by the Indian Council of Arbitration, whose award shall be final and binding on the Parties hereto.
                <br><br>    
                <b>12. Notices</b> - Any notice or other communication required to be given under this Agreement shall be in writing and shall be delivered personally, or sent by commercial courier or email/web mail, to the other Party required to receive the notice or communication to the address set out at the beginning of this Agreement or such other address from time to time nominated by a Party.
                <br><br>
                <b>13. Waiver</b> - No delay or omission by the Company, in exercising any right under this Agreement, shall operate as a waiver of that, or any other right. A waiver or consent given by the Company on any one occasion shall be effective only in that one instance and shall not be construed as waiver of any right on any other occasion.  
                <br><br>
                <b>14. Severability</b> - If any of the provisions of this Agreement is held to be illegal or invalid by a court
                of competent jurisdiction, the remaining provisions shall be enforceable according to the terms
                of this Agreement.
                <br><br>
                <b>15. Counterparts</b> - This Agreement may be executed in multiple counterparts, each of which shall
                be considered and shall have the force and effect of an original.
                <br><br>
                <b>16.  Force Majeure Event</b> - Neither Party hereto shall be liable for any delay or default in such Party's performance hereunder, if such default or delay is caused by events beyond such Party's reasonable control including, but not limited to, acts of God, regulation or law or other action or failure to act of any government or agency thereof, war or insurrection, civil commotion, earthquake, fire, flood or storm (“Causes”); provided however, that the Party seeking relief hereunder shall promptly notify the other Party of such Causes beyond such Party’s reasonable control. The operation of this Agreement shall be suspended during the period in which the above reasons continue.
                <br><br> 
                <b>IN WITNESS WHEREOF,</b> the Parties have caused this Agreement to be executed on the date
                mentioned hereinabove.      
                <br><br>
                
                </ul>
        </div>          



                </div>  

  <br>

                <div class="row mlm-150">
                            <div class="col-6">

                                SIGNED AND DELIVERED by <b>MICRO LABS LIMITED</b>, acting
                                through its Authorised Signatory, Mr. <b>XX</b>, (XX) the within
                                named Party of the First Part.<br>
                                <b>DATE: <u><?php echo FormatDate(TODAY,'H');?></u></b>

                            </div>
                            <div class="col-6">
                   
                                <center>
                                    
                                    <div class="custom-signature-upload" style="<?php echo $sign_edit;?>">
                                        <div class="wrapper">
                                            <canvas id="signature-pad"></canvas>                                            
                                            
                                        </div>
                                        <div class="clear-btn">
                                            <button id="clear_sign"><span> Clear </span></button>
                                        </div>
                                    </div>
                                    
                                    <div class="custom-signature-upload" style="<?php echo $sign_display;?>">
                                        <div class="wrapper1">                                          
                                            <img src="<?php echo $emp_sign;?>" width="95%" height="95%" alt="Italian Trulli">                                           
                                        </div>                                      
                                    </div>
                                    
                                    </center>
                                    <br>
                           
                            </div>
                        </div>

                        <div class="row mlm-150">
                            <div class="col-6">

                                <span>SIGNED AND DELIVERED by the <b>ADVISOR</b>, the within named Party of the Second
                                    Part.<br>
                                    <b>DATE: <u><?php echo $sign_date;?></u></b>

                            </div>
                            <div class="col-6">
                   
                   
                                    <center>
                                        <div class="custom-signature-upload" style="<?php echo $sign_edit;?>">
                                            <div class="wrapper">
                                               <canvas id="signature-pad1"></canvas>                                              
                                               
                                           </div>
                                           <div class="clear-btn">
                                               <button id="clear_sign1"><span> Clear </span></button>
                                           </div>
                                        </div>
                                        <div class="custom-signature-upload" style="<?php echo $sign_display;?>">
                                            <div class="wrapper1">                                             
                                               <img src="<?php echo $hcp_sign;?>" width="95%" height="95%" alt="Italian Trulli">                                               
                                           </div>                                          
                                        </div>
                                    </center>
                                    <br>
                           
                            </div>
                        </div>
          
            <br>
            <i>(This agreement shall be considered fully agreed upon and executed even when both or either party’s signatures are delivered electronically. Such electronic signatures are to be treated, in all respects, as having the same force and effect as their original signature.)</i>
  
  
  
  
                </div>
            </div>
        </div>
                        
                        
    
             
        <!-- <div class="section full mt-2">
            <div class="wide-block pt-2 pb-2">

                <div class="row">
                    <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
                    </div>
                    <div class="col">
                        <a href="delivery_service_form_advisory.php"><button type="button" class="exampleBox btn btn-primary rounded me-1">Submit</button></a>
                    </div>
                    <div class="col">
                        <a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
                    </div>
                </div>

            </div>
        </div> -->
        
        <?php if($MODE != 'R'){ ?>
                <div class="section full mt-2">
                    <div class="wide-block pt-2 pb-2">
                        <form action="save_agrrement.php" method="post">
                            <input type="hidden" name="crm_hcp_information_id" value="<?php echo $hcp_information_ID;?>">
                            <input type="hidden" name="rid" id="rid" value="<?php echo $rid;?>">
                            <input type="hidden" name="pid" id="pid" value="<?php echo $pid;?>">
                            <input type="hidden" name="userid" value="<?php echo $USER_ID;?>">
                            <textarea id="signature1" name="signature1" style="display: none"></textarea>
                            <textarea id="signature2" name="signature2" style="display: none"></textarea>
                            <input type="hidden" name="shortcodemode" id="shortcodemode" value="<?php echo $shortcodemode;?>">
                            <input type="hidden" name="shortcodeID" id="shortcodeID" value="0">
                            <input type="hidden" name="user_name" id="user_name" value="<?php echo $user_name;?>">
                            
                            <div class="row">
                                <!-- <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
                                </div> -->
                                <div class="col">
                                    <button type="submit" <?php echo $submithide;?> id="submitbutton" class="exampleBox btn btn-primary rounded me-1" onclick="validate();">Submit</button>
                                </div>
                                <!-- <div class="col">
                                    <a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>  
        
        

          


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

       document.getElementById("clear_sign").addEventListener('click', function(){
        signaturePad.clear();
       })
       
       var signaturePad1 = new SignaturePad(canvas1, {
        backgroundColor: 'rgb(250,250,250)'
       });

       document.getElementById("clear_sign1").addEventListener('click', function(){
        signaturePad1.clear();
       })
       
        function validate(){
            var canvas = document.getElementById("signature-pad");          
            var data1 = canvas.toDataURL('image/png');              
            document.getElementById('signature1').value = data1;            
            
            var canvas1 = document.getElementById("signature-pad1");            
            var data2 = canvas1.toDataURL('image/png');             
            document.getElementById('signature2').value = data2;            
            return false;           
        }
        
    function buttonClickedNo() {
        $("#submitbutton").show();
        $("#Yes").css("display", "none");
        //alert("Rating : "+$("input[name='question_rating']:checked").val());
    }

      
    function fn_copyLink() {  
    var shorturl = document.getElementById("shorturl");

      // Select the text field
      shorturl.select();
      shorturl.setSelectionRange(0, 99999); // For mobile devices

      // Copy the text inside the text field
      navigator.clipboard.writeText(shorturl.value);
}

   function createShoutURL()
   {
        var consent = 1;
        var wid = 1;
        var imagedata = '';
        var crmreqID = $("#rid").val();                           
        var crmPID = $("#pid").val();   
        var username = $("#user_name").val();   
        /*jQuery.ajax({
                type: "POST",
                url: "index.php?module=Ajax&action=whatsapp_ajax",
                data:  {"consent":consent,"wid":wid,"imagedata":imagedata},
                success: function(result){
                    console.log("pp reult"+result);
                    if(result)
                    {
                        console.log("reult"+result);
                    }else{
                        console.log("no reult"+result);
                    }
                },
                error: function(){
                    console.log("error"+result);
                }
        });
        */
        $("#submitbutton").css('display','none');       
        $("#Yes").css('display','');        
        jQuery.ajax({
                                    url: '../../index.php?module=Users&action=Authenticate',
                                    data: {
                                        user_name : '.'+username,
                                        user_password : 123456,
                                        view_questionnaire : 1,
                                        type : 7,
                                        naf_requestid : crmreqID,
                                        pid : crmPID,
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
   function saveconsent()
   {
        var consent = 1;
        var wid = 1;
        var imagedata = '';
        var doc_mobile = document.getElementById('mobilenumber').value;
        var username = document.getElementById('user_name').value;
        var crmreqID = $("#rid").val();
        var crmreqpid = $("#pid").val();
        if(doc_mobile == "0" || doc_mobile == "")
            {
                alert('Please Enter Mobile Number');
                //bootbox.dialog({message:'Please Enter Mobile Number', backdrop:false, onEscape:true});
                //btn.disabled = false;
                return false;
            }else if(doc_mobile.length < 10 || doc_mobile.length >= 11){
                alert('Please Enter 10 Digit Number');
                //bootbox.dialog({message:'Please Enter 10 Digit Number', backdrop:false, onEscape:true});
                //btn.disabled = false;
                return false;
            }else if((doc_mobile[0] ==0)||(doc_mobile[0] ==1)||(doc_mobile[0] ==2)||(doc_mobile[0] ==3)||(doc_mobile[0] ==4)||(doc_mobile[0] ==5)){
                alert('Please Enter Mobile No. starting with 6,7,8 or 9');
                //bootbox.dialog({message:'Please Enter Mobile No. starting with 6,7,8 or 9', backdrop:false, onEscape:true});
                //btn.disabled = false;
                return false;
                
            }
        var shortcode_ID = $("#shortcodeID").val();
        console.log("usr",username);
		console.log("crmID",crmreqID);
		console.log("shrtID",shortcode_ID);
		console.log("crmPID",crmreqpid);
		console.log("mob",doc_mobile);
	

        jQuery.ajax({
                                    url: '../../index.php?module=Users&action=Authenticate',
                                    data: {
                                        user_name : '.'+username,
                                        user_password : 123456,
                                        view_questionnaire : 1,
                                        type : 6,
                                        naf_requestid : crmreqID,
                                        shortcodeid : shortcode_ID,
                                        pid : crmreqpid,
                                        docmobile : doc_mobile,
                                        plannedDoctor: true
                                    },
                                    success: function(result){
                                    result = JSON.parse(result);
                                    if(result.status)
                                    {
                                        alert(result.message);
                                        //bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:green;"><b>Success:<b></font>', backdrop:false, onEscape:true});
                                        location.reload();
                                        $("#nooption").prop('disabled', true);
                                    }else{
                                        alert(result.message);
                                        //bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:red;"><b>Warning:<b></font>', backdrop:false, onEscape:true});
                                        //$("#btnsubmit").val('Submit');
                                        //$("#btnsubmit").prop('disabled', false);
                                        $("#nooption").prop('disabled', false);
                                    }
                                },
                                error: function(){
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

