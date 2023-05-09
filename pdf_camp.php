<?php
// Set error reporting to capture PHP errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/common.php";
include_once (DOCROOT."MPDF57/mpdf.php");
$rid=(isset($_GET['rid']))?$_GET['rid']:'';
$prid=(isset($_GET['prid']))?$_GET['prid']:'';
$pid=(isset($_GET['pid']))?$_GET['pid']:'';
$ishtml=(isset($_GET['html']))?$_GET['html']:'';
$HCP_UNIVERSAL_ID = GetXArrFromYID("select contactid,masterid from contactdetails order by contactid LIMIT 500 ", "3");


$data = GetDataFromID('crm_naf_hcp_details', 'naf_main_id', $pid,'and deleted=0 ');

$CRM_NAF_MAIN=GetDataFromID('crm_naf_main','id',$rid,"and deleted=0 ");
$comment=$CRM_NAF_MAIN[0]->post_comment;
//$hcp_informationid=GetXFromYID("select ")

$PMA_REQUEST_MAIN=GetDataFromID('crm_request_main','id',$pid,'and deleted=0 ');

$Doctor_SIGN=$PMA_REQUEST_MAIN[0]->e_sign_doctor;

$hcp_id = $data[0]->hcp_id;
$hcp_pan = $data[0]->hcp_pan;
$hcp_qualification = $data[0]->hcp_qualification;
$Honorium=$data[0]->honorarium_amount;
$hcp_address=$data[0]->hcp_address;
$mobile=$data[0]->mobile;



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




$_q1="select firstname,lastname,qualification,registration_no,otherstate,othercity from contactmaster where id='$hcp_id'";
$_r1=sql_query($_q1,"");
list($firstname,$lastname,$qualificationid,$regno,$otherstate,$othercity)=sql_fetch_row($_r1);
$Doctor_Qualification=GetXFromYID("select qualificationname from qualification where qualificationid='$qualificationid'");
$state=GetXFromYID("select statename from state where stateid='$otherstate'");
$_c_q="select cityname,pincode from city where cityid='$othercity' ";
$_c_r=sql_query($_c_q,'');
list($city,$pincode)=sql_fetch_row($_c_r);



$_q="select document_type_id,file_path from crm_naf_document_upload where crm_request_main_id='$pid' ";
$_R=sql_query($_q,'');
$ATTACHMENT_ARR=array();
if (sql_num_rows($_R)) {
	while (list($type,$filepath)=sql_fetch_row($_R)) {
		if (!isset($ATTACHMENT_ARR[$type])) {
			$ATTACHMENT_ARR[$type]=$filepath;
			
		}
	}
}

$sDate=GetXFromYID("select t2.sign_date  from crm_hcp_information t1 inner join crm_hcp_agreement t2 on t1.id=t2.crm_hcp_information_id where t1.crm_request_main_id='$pid' and t1.deleted =0 and t2.deleted=0");

if ($sDate != '') {
	$sDate = date('d M Y', strtotime($sDate));
} else {
	$sDate = date('d M Y');
}

$hcp_information=GetDataFromID('crm_hcp_information','crm_request_main_id',$pid);
$hcp_info_id=$hcp_information[0]->id;
$hcp_agrrement_data=GetDataFromID('crm_hcp_agreement','crm_hcp_information_id',$hcp_info_id);
$hcp_SIGN=$hcp_agrrement_data[0]->hcp_sign;
$CMP_SIGN=$hcp_agrrement_data[0]->company_sign;

$naf_activity_name = GetXFromYID("select naf_activity_name from crm_naf_main where id=" . $rid . " and deleted=0 ");

$topic=GetXFromYID("select topic from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_info_id");
$venue=GetXFromYID("select venue from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_info_id");
$meeting_date=GetXFromYID("select meeting_date from crm_hcp_agreement where deleted = 0 AND crm_hcp_information_id = $hcp_info_id");



$html='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>camp pdf</title>

<body>';
  $html.='
        <table width="800" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff"  >
         
					
            <tbody>
	            <tr>
                    <td height="5" colspan="8"></td>
                </tr>
			
			 
				<tr>
					<th align="center" colspan="8" style="font-size:20px;line-height: 1;text-decoration:underline">
                       Medical Survey Consultant Details
                    </th>
                </tr>
				
				<tr>
                    <td height="20" colspan="8"></td>
                </tr>
				
				<tr>
                    <td style="font-size:14px;line-height:1.8;" colspan="8">
						<b>Name of Doctor:</b>'.$firstname.' '.$lastname.'<br>
						<b>Address:</b>'.$hcp_address.'<br>
						<b>Mobile:</b>'.$mobile.'<br>
						<b>Qualification:</b>'.$hcp_qualification.'<br>
						
						<b>PAN Number:</b>.' .$hcp_pan.'<br>
						<b>UIN Number:</b> HCP-'.$HCP_UNIVERSAL_ID[$hcp_id].'<br>
						<b>MCI Registration Number:</b>'.$regno.'<br>
						<b>MCI Registration State:</b>'.$state.'<br>
						<b>Pincode:</b> '.$pincode.'<br>
						<b>Survey Name:</b> CAMP
					</td>
                </tr>
				
				<tr>
                    <td height="40" colspan="8"></td>
                </tr>';

				$html.='</tbody>';
   				$html.='</table>';
				
				// if width is less than height , image is uploaded in landscape mode ..
				// $arrays = exif_read_data($ATTACHMENT_ARR[2]);

				// //Output
				// print_r($arrays);
				
				// exit();
	
   
   
   $html.='
        <table width="800" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff"  >
         
					
            <tbody>
	            <tr>
                    <td height="5" colspan="8"></td>
                </tr>
			
			 
				<tr>
					<th align="center" colspan="8" style="font-size:20px;line-height: 1;text-decoration:underline">
					AGREEMENT FOR DISEASE AWARENESS/DIAGNOSTIC CAMP
                    </th>
                </tr>
				
				<tr>
                    <td height="20" colspan="8"></td>
                </tr>
				
				<tr>
                    <td style="font-size:14px;line-height:1.3;" colspan="8">
								This <b>DISEASE AWARENESS / DIAGNOSTIC CAMP AGREEMENT (Agreement) </b>shall become
								effective as on the date of last signature of the parties <b>'.$sDate.'</b> by and between:
								<br><br>
								<b>Micro Labs Limited</b>, a company existing under the laws of India, having its registered office at 31,
								Race Course Road, Bangalore - 560 001 (hereinafter referred to as <b>Company</b> and/or <b>Micro labs</b>)
								of the <b>ONE PART</b><br><br>
						</td>
                </tr>
				<tr>
                    <td style="font-size:14px;line-height:1.3;text-align:center;" colspan="8">
							<b>AND</b>
					</td>
                </tr>

				<tr>
                    <td style="font-size:14px;line-height:1.3;" colspan="8">
								<br>
								<b>Dr.'.$firstname.' '.$lastname.' </b>,a healthcare professional, an Indian citizen, having his/her address  at<b> '. $hcp_address.' </b>, bearing PAN <b>'.$hcp_pan.' </b>(hereinafter
								referred to as <b>Healthcare Professional</b> or <b>HCP</b>) of the <b>OTHER PART</b>.<br><br>
								<i>The Company and the HCP are hereinafter individually referred as Party and collectively as
									Parties.</i>
								<br><br>
								
								<span style="font-size:16px;"><b><u>Summary </u></b></span>
							
								<br><br>
								The purpose of this written agreement is to sign off further to an oral agreement between the Parties – for the Recipient-HCP to support in organizing/conducting a Micro Labs initiated awareness, counselling and/or diagnostic camp (hereinafter referred to as ‘camp’), as titled hereinunder:
								<br><br>
								<span>TITLE OF THE CAMP		:'.$topic.' </span><br>
								<span>DATE				:'.$meeting_date.'</span><br>
								<span>VENUE				:'.$venue.'</span><br>


					</td>
                </tr>	

				<tr>
					
                    <td style="font-size:14px;line-height:1.3;" colspan="8">
					<br>
					As compensation of the Recipient-HCP’s time and efforts devoted in conducting the above-mentioned camp and/or sharing his/her advice with patients in the above-mentioned camp, the Parties have mutually agreed to an amount of INR <b>' .$Honorium.'</b>
					(Rupees <b>'.$get_amount.'</b>).
									
					<br>
					</td>
                </tr>

				

				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">
				
								<br><br>
								<b>MUTUAL INTEREST OF THE PARTIES:</b>This Camp is of mutual interest to the parties because it:
								<br><br>
								a.	creates awareness to general public or provide counsel on various disease, treatment options, mental/physical wellbeing, dietary, etc. and/or <br><br>
								b.	involve diagnosis of patient\'\'s medical condition to ascertain the medical needs of the patients to enable Micro Labs in its research and development. <br><br>
				</td>
                </tr>

				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				
								<b>RESPONSIBILITIES OF THE PARTIES: </b><br><br>
							
								1.	Micro Labs shall provide the Recipient with support, goods and/or services in accordance with the purpose of this Agreement and implementing arrangements, as necessary.
								<br><br>
								2.	Micro Labs’s support or the compensation paid hereof indicates no incentive or obligation for the recipient-hcp to prescribe, recommend or otherwise support Micro Labs’s products or services.
								<br><br>
								

									
	
				</td>
                </tr>

				
				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				<br><br>
				3.	Recipient-HCP hereby agrees to: Further to what is required and necessary in organizing/conducting the camp, including but not limited to, (a) broadcasting information of the camp via pamphlets, notice, banners etc. (b) setting up the required infrastructure to hold/conduct the camp, (c) distributing Patient awareness booklets/pamphlets etc., the Recipient-HCP shall: 
								<br>
				</td>
                </tr>				


				
				<tr>
					<td  colspan="1"></td>
                    <td style="font-size:14px;line-height:1.3;" colspan="7">
					<br>
								a. 	In case of an awareness camp, provide educational and interactive activities for the attendees and supervise the camp. Any and all information communicated by the Recipient-HCP in a camp shall be to enhance knowledge and be disease or health condition specific, factual, clear and accurate and comply with the applicable laws and codes;
										<br><br>
									
								b. b.	In case of a <b>diagnostic camp </b>, provide free or nominally charged consultation to attendees’ basis their initial diagnosis, supervise the camp and provide adequate counsel to the Patients.
										<br><br>
									
								
					<br>
					</td>
                </tr>				
								
					
					
				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				    <br>
							<b>OTHER TERMS AND CONDITIONS:</b><br><br>
							
							1.	<b>Camp Notification:</b> Recipient-HCP shall ensure that there is no promotion of the Recipient-HCP or Micro Labs products and brands while promoting/advertising the camp. The content of the banner/pamphlet should not come across as Recipient-HCP advertisement/promotion in any manner.
								<br><br>
							 2.	<b>Camp Venue:</b> Recipient-HCP shall ensure that camps are set up in a location/venue that is convenient for majority of the patients to meet the goals and are not held at venues which could reasonably be perceived as lavish or extravagant.
							<br><br>
							
							3.	<b>Personal Data:</b> Recipient-HCP shall ensure there is no transfer of patient’s personal identifiable information such as name, contact number or email address, etc. to Micro Labs.<br><br>
							4.	<b>Term & Termination</b>- This Agreement shall be effective until 31 March 20XX and can be terminated by either Party by giving a prior written notice of one (1) week to the other Party.<br><br>
							5.	<b>Representations and Warranties</b> - The Recipient-HCP hereby confirms that (a) the services provided by the him/her, and the compensation received by him/her under this Agreement follow the requirements of the Recipient-HCP’s employer or any Central or State government health care authority/body and (b) the performance of the Recipient-HCP’s obligation under this Agreement does not conflict with the performance of his/her other obligations under any other agreement.<br><br>
							6.	<b>Compliance</b> - During the performance of his/her obligation under this Agreement, the Recipient-HCP agrees to comply with all applicable laws and rules, bylaws, statutory orders, guidance’s & regulations made thereunder and/or of any court, governmental or administrative order and/or guidelines including, without limitation, guidelines issued by the National Medical Commission, the Code of Conduct and Business Ethics Policies of the Company. <br><br>
							7.	<b>Governing Law and Arbitration</b> - This Agreement shall be governed, in all respect, by applicable Indian law(s) and the Parties hereby submit to the exclusive jurisdiction of the courts in New Delhi. Any dispute in connection with this Agreement shall be settled by amicable negotiation between the Parties hereto. <br><br>

							8.	<b>Severability</b> - If any of the provisions of this Agreement is held to be illegal or invalid by a court of competent jurisdiction, the remaining provisions shall be enforceable according to the terms of this Agreement.<br><br>

					
					
					
					</td>
                </tr>';
				
				
					
					
            $html.='</tbody>';
   $html.='</table>';
   
   
    $html.='<table width="800" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff"  >
         
					
            <tbody>
	            <tr>
                    <td height="5" colspan="8"></td>
                </tr>
			
				
				<tr>
                    <td style="font-size:14px;line-height:1.3;" colspan="8">
						SIGNED AND DELIVERED by <b>MICRO LABS LIMITED</b>, acting
						through its Authorised Signatory, Mr. <b>XX</b>, (XX) the within
						named Party of the First Part.<br><br>
						<b>DATE: <u>'.$sDate.'</u></b>
					</td>
                </tr>
				
			
				
				<tr>
					<td align="center" colspan="8"><img src="'.$CMP_SIGN.'"  border="0" /></td>
				</tr>
				
				<tr>
                    <td style="font-size:14px;line-height:1.3;" colspan="8">
						SIGNED AND DELIVERED by the <b>Dr '.$firstname.' '.$lastname.'
								PROFESSIONAL</b>, the within named Party of the Second
							Part.<br><br>
							<b>DATE: <u>'.$sDate.'</u></b>
					</td>
                </tr>
				
			
				
				<tr>
					<td align="center" colspan="8"><img src="'.$hcp_SIGN.'"  border="0" /></td>
				</tr>
				
				
				';
				
				
					
					
    $html.='</tbody>';
    $html.='</table>';
   

$html.='</body>';



if (!empty($ishtml)) {
	echo $html;
	exit;
	
}


$mpdf=new mPDF();
// Set error log options for mPDF
// $mpdf->showImageErrors = true;
// $mpdf->ignore_invalid_utf8 = true;
// $mpdf->allow_charset_conversion = true;
// $mpdf->charset_in = 'UTF-8';
// $mpdf->SetImportUse();
// $logFilePath = 'mpdf_errors.log';

// // Set custom error handler
// set_error_handler(function ($severity, $message, $file, $line) use ($logFilePath) {
//     $logMessage = date('Y-m-d H:i:s') . " [{$severity}] {$message} in {$file} on line {$line}\n";
//     file_put_contents($logFilePath, $logMessage, FILE_APPEND);
// });


$mpdf->SetFont('mulish');
$mpdf->WriteHTML($html);

// Restore default error handler
// restore_error_handler();

// Output a PDF file directly to the browser
$mpdf->Output('pmarpt.pdf','I');
?>