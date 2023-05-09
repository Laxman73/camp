<?php
// Set error reporting to capture PHP errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/common.php";
include_once (DOCROOT."MPDF57/mpdf.php");
$rid=(isset($_GET['rid']))?$_GET['rid']:'';
$pid=(isset($_GET['pid']))?$_GET['pid']:'';
$ishtml=(isset($_GET['html']))?$_GET['html']:'';
$HCP_UNIVERSAL_ID = GetXArrFromYID("select contactid,masterid from contactdetails order by contactid LIMIT 500 ", "3");


$data = GetDataFromID('crm_request_details', 'crm_request_main_id', $pid,'and deleted=0 ');

$CRM_NAF_MAIN=GetDataFromID('crm_naf_main','id',$rid);
$comment=$CRM_NAF_MAIN[0]->post_comment;
//$hcp_informationid=GetXFromYID("select ")

$PMA_REQUEST_MAIN=GetDataFromID('crm_request_main','id',$pid);

$Doctor_SIGN=$PMA_REQUEST_MAIN[0]->e_sign_doctor;

$hcp_id = $data[0]->hcp_id;
$hcp_pan = $data[0]->hcp_pan;
$hcp_qualification = $data[0]->hcp_qualification;
$Honorium=$data[0]->honorarium_amount;
$hcp_address=$data[0]->hcp_address;
$mobile=$data[0]->mobile;

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



$sql_question = "SELECT crm_questions_upload.crm_request_main_id AS rid, userid, question, crm_questions_upload.id AS 'questionid', no_of_options, crm_question_type.name AS 'question_type',group_concat(crm_questionnaire.answer_id) as answer_id,answers_ranking FROM crm_request_main inner join crm_questions_upload on SUBSTR(crm_request_main.naf_no,-2, 2) = crm_questions_upload.crm_request_main_id LEFT OUTER JOIN crm_question_type ON crm_questions_upload.question_type_id = crm_question_type.id AND crm_question_type.deleted = 0
left outer join crm_questionnaire on crm_request_main.id = crm_questionnaire.crm_request_main_id and crm_questions_upload.id = crm_questionnaire.question_id 
WHERE crm_questions_upload.deleted = 0 AND crm_request_main.id = '".$pid."' and SUBSTR(crm_request_main.naf_no,-2, 2) = ".$rid." group by questionid ORDER BY crm_questions_upload.question_type_id, questionid";


$res_question = sql_query($sql_question, "");

$html='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pdf</title>

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
						<b>Survey Name:</b> PMA
					</td>
                </tr>
				
				<tr>
                    <td height="40" colspan="8"></td>
                </tr>
				<tr>
					<th align="center" colspan="8" style="font-size:18px;line-height: 1;text-decoration:underline">
                       Uploaded Documets
                    </th>
                </tr>
				
				<tr>
                    <td style="font-size:16px;line-height:1.3;" colspan="8">
						<b>Pan Card:</b>
					</td>
                </tr>
				
				<tr>
                    <td height="20" colspan="8"></td>
                </tr>';
				// if width is less than height , image is uploaded in landscape mode ..
				// $arrays = exif_read_data($ATTACHMENT_ARR[2]);

				// //Output
				// print_r($arrays);
				
				// exit();
				list($width, $height) = getimagesize($ATTACHMENT_ARR[1]);
				if($width < $height && (substr($ATTACHMENT_ARR[1], -3)!='png'))
				{
					$exif = exif_read_data($ATTACHMENT_ARR[1]);
					if (!empty($exif['Orientation'])) {
						switch ($exif['Orientation']) {
						case 3:
							$angle = 180 ;
							break;
					
						case 6:
							$angle = -90;
							break;
					
						case 8:
							$angle = 90; 
							break;
						default:
							$angle = 0;
							break;
						}   
					}   
					$source = imagecreatefromjpeg($ATTACHMENT_ARR[1]);            //rotate image by 90deg
					$rotate = imagerotate($source, $angle, 0);
					imagejpeg($rotate, "rotated-image.jpeg");

					$html.='<tr>
								<td align="left"><img src="rotated-image.jpeg"  border="0" /></td>
							</tr>';
				}
				else
				{
					if ($ATTACHMENT_ARR[1] != '') 	
					{
					$html.='<tr>
						<td align="left"><img src="'.$ATTACHMENT_ARR[1].'"  border="0" /></td>
					</tr>';
					}
					else
					{
						$html.=	'<tr>
						<td align="left"><p>no file attached</p></td>
					</tr>';
					}
				}
				$html.='
				<tr>
                    <td height="40" colspan="8"></td>
                </tr>
				
				
				
				<tr>
                    <td style="font-size:16px;line-height:1.3;" colspan="8">
						<b>Cancel Cheque: </b>
					</td>
                </tr>
				
				<tr>
                    <td height="20" colspan="8"></td>
                </tr>';
				list($width, $height) = getimagesize($ATTACHMENT_ARR[2]);
				if($width < $height && (substr($ATTACHMENT_ARR[2], -3)!='png'))
				{
					$exif = exif_read_data($ATTACHMENT_ARR[2]);
					if (!empty($exif['Orientation'])) {
						switch ($exif['Orientation']) {
						case 3:
							$angle = 180 ;
							break;
					
						case 6:
							$angle = -90;
							break;
					
						case 8:
							$angle = 90; 
							break;
						default:
							$angle = 0;
							break;
						}   
					}   

					$source = imagecreatefromjpeg($ATTACHMENT_ARR[2]);
					$rotate = imagerotate($source, $angle, 0);
					imagejpeg($rotate, "rotated-image.jpeg");
					$html.='<tr>
								<td align="left"><img src="rotated-image.jpeg"  border="0" /></td>
							</tr>';
				}
				else
				{
				if ($ATTACHMENT_ARR[2] != '') 	
				{
					$html.='<tr>
                     			<td align="left"><img src="'.$ATTACHMENT_ARR[2].'"  border="0" /></td>
                			</tr>';
				}	
				else
				{
					$html.=	'<tr>
                    		<td align="left"><p>no file attached</p></td>
                			</tr>';	
				}	
				}
				$html.='
				<tr>
                    <td height="40" colspan="8"></td>
                </tr>	
					
					
				<tr>
                    <td style="font-size:16px;line-height:1.3;" colspan="8">
						<b>Rxn Pad/ Letter Head/ Visiting Card:</b>
					</td>
                </tr>
				
				<tr>
                    <td height="20" colspan="8"></td>
                </tr>';
				list($width, $height) = getimagesize($ATTACHMENT_ARR[3]);
				if($width < $height && (substr($ATTACHMENT_ARR[3], -3)!='png'))
				{
					$exif = exif_read_data($ATTACHMENT_ARR[3]);
					if (!empty($exif['Orientation'])) {
						switch ($exif['Orientation']) {
						case 3:
							$angle = 180 ;
							break;
					
						case 6:
							$angle = -90;
							break;
					
						case 8:
							$angle = 90; 
							break;
						default:
							$angle = 0;
							break;
						}   
					}   
					$source = imagecreatefromjpeg($ATTACHMENT_ARR[3]);
					$rotate = imagerotate($source, $angle, 0);
					imagejpeg($rotate, "rotated-image.jpeg");
					$html.='<tr>
								<td align="left"><img src="rotated-image.jpeg"  border="0" /></td>
							</tr>';
				}
				else
				{
				if ($ATTACHMENT_ARR[3] != '') 	
				{
					$html.='<tr>
                     			<td align="left"><img src="'.$ATTACHMENT_ARR[3].'"  border="0" /></td>
                			</tr>';	
				}
				else
				{
					$html.=	'<tr>
								<td align="left"><p>no file attached</p></td>
							</tr>';	
				}
				}	
				$html.='
				<tr>
                    <td height="80" colspan="8"></td>
                </tr>';	
					
					
            $html.='</tbody>';
   $html.='</table>';
   
   
   
   $html.='
        <table width="800" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff"  >
         
					
            <tbody>
	            <tr>
                    <td height="5" colspan="8"></td>
                </tr>
			
			 
				<tr>
					<th align="center" colspan="8" style="font-size:20px;line-height: 1;text-decoration:underline">
                       PROJECT MARKET ANALYSIS SERVICE AGREEMENT
                    </th>
                </tr>
				
				<tr>
                    <td height="20" colspan="8"></td>
                </tr>
				
				<tr>
                    <td style="font-size:14px;line-height:1.3;" colspan="8">
								This <b>PROJECT MARKET ANALYSIS SERVICE AGREEMENT (Agreement) </b>shall become
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
								<b>Dr.'.$firstname.' '.$lastname.' </b>, an Indian citizen, a registered medical practitioner according to the
								applicable laws of India and guidelines laid down by the relevant regulatory body and/or a Healthcare
								Professional, residing at<b> '. $hcp_address.' </b> holding PAN <b>'.$hcp_pan.' </b>(hereinafter
								referred to as <b>Healthcare Professional</b> or <b>HCP</b>) of the <b>OTHER PART</b>.<br><br>
								<i>The Company and the HCP are hereinafter individually referred as Party and collectively as
									Parties.</i>
								<br><br>
								
								<span style="text-align:center;"><u>WHEREAS</u></span>
							
								<br><br>
								A. The Company inter alia is engaged in the business of manufacturing, developing, formulating,
								marketing, distributing, exporting and selling various drugs and pharmaceutical products.
								<br><br>
								B. The Company desires to conduct a market research for a pre-defined purpose, to work towards
								better patient care. In that, the market research may result in:
								<br><br>
					</td>
                </tr>	

				<tr>
					<td  colspan="1"></td>
                    <td style="font-size:14px;line-height:1.3;" colspan="7">
					<br>
								a. Gaining market insights, gathering knowledge on existing molecules, understanding on
										general disease management, understanding newer advances in therapy &amp; product primary
										research and/or,
										<br><br>
									
								b. Gathering knowledge on the needs of patients, compliance trend by Patients, requirement
										for expansion of disease awareness activities, and allied data that ultimately results into
										providing better patient care and improved services by the Company and/or,
										<br><br>
									
								c. Understanding HCP perspective and areas of improvements that the Company requires to
										focus on.
										<br><br>
										(Collectively, hereinafter referred to as the <b>Purpose</b>)
									
					<br>
					</td>
                </tr>

				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">
						<br>
								C. The Company seeks to engage the HCP for the purpose of this Agreement on the basis of his/her
								knowledge and expertise in the concerned therapeutic area.
								<br><br>

								D. The HCP has represented to the Company that the HCP is a qualified and registered medical
								practitioner holding appropriate degree in the field of medicine and is interested in entering into
								this Agreement with the Company.
								<br><br>

								NOW, THEREFORE, in consideration of the representations, warranties, covenants and agreements
								contained in this Agreement, the Company and the HCP do hereby agree as follows:
								<br><br>
				</td>
                </tr>	

				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">
				<br><br>
				1. Scope of Services
				<br><br>
								Subject to the terms and conditions of this Agreement, the HCP agrees to participate in the
								Companys market research titled <b>'.$naf_activity_name.'</b>, that has been designed and compiled
								by the Company to achieve the Purpose. The HCP has agreed to invest his time and contribute
								data as required in the market research, on the timeline set forth and communicated by the
								Company, through one or more data collection methods such as reviewing documents, multi
								choice questions, online tools and interviews conducted by the Company, at a mutually agreeable
								time (hereinafter referred to as <b>Services</b>).
								<br><br>
								<b>THE HCP HEREBY REPRESENTS AND WARRANTS THAT THE RESPONSES AND
									DATA THAT WOULD BE PROVIDED FOR THE PURPOSE OF THIS AGREEMENT
									ARE ACCURATE, TRUTHFUL AND BALANCED.</b>
								<br><br>
				</td>
                </tr>

				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				
								<b>2. Fees</b><br><br>
							
								2.1 In consideration of the services rendered by the HCP to the Company for participating in the
								above-mentioned research, the Parties have mutually agreed to an amount of INR<b></b> as fee. Parties agree that this would constitute full and
								complete payment for the services to be rendered under this agreement, computed as per the
								Companys Fair Market Value policies.
								<br><br>
								2.2 The HCP shall raise an invoice on the Company for the aforementioned fee, along with GST, as
								applicable. The HCP shall also be responsible to file its GST returns in a timely manner as
								stipulated under law so that the Company can obtain input credit. All payments made by the
								Company, shall be subject to deduction of appropriate taxes at source, as per applicable laws.
								<br><br>
				</td>
                </tr>

				
				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				<br><br>
							<b>3. Representation and Warranties</b><br><br>
							

								3.1 HCP confirms that the services provided by the HCP, and the payments received by the HCP
								under this Agreement follow the requirements of HCPs employer (e.g. hospital management) or
								any Central or State government health care authority / body.
								<br><br>
								3.2 The HCP hereby represents and warrants that:
								<br>
				</td>
                </tr>				


				
				<tr>
					<td  colspan="1"></td>
                    <td style="font-size:14px;line-height:1.3;" colspan="7">
					<br>
								a. This Agreement casts a valid, legal and binding obligation upon him and is enforceable
										in accordance with the terms of this Agreement.
										<br><br>
									
								b. The performance of the HCPs obligation under this Agreement does not conflict with
										the performance of the HCPs other obligations under any other agreement, instrument
										or understanding, oral or written, to which he may be bound.
										<br><br>
									
								c. The HCP has not and will not in future during the Term, enter into any arrangement,
										which shall conflict/compete with or prohibit the performance of the obligations under
										this Agreement.
									
					<br>
					</td>
                </tr>				
								
					
					
				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				    <br>
							<b>4. Term &amp; Termination</b><br><br>
							
								4.1 This Agreement shall commence on the <b>'.$sDate.'</b> and shall continue until <b>31 March 2023</b>
								unless terminated by either Party in accordance with the provisions of this Section.
								<br><br>
								4.2 This Agreement can be terminated by either side by giving a prior written notice of one (1) week.
							<br><br>
					
					
					
					</td>
                </tr>
				
				
				
				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				    <br>
							<b>5. Confidentiality</b>
								<br><br>
								5.1 The HCP shall keep confidential any information or data disclosed to him, whether orally or
								otherwise, directly or indirectly by the Company, while rendering the Services and/or from the
								other HCPs and will not disclose the same to third parties by publication or otherwise or use the
								same.
								<br><br>
								5.2 The provisions of this clause will survive 10 years post termination of this Agreement.
								<br><br>
					
					
					
					</td>
                </tr>
				
				
				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				    <br>
							<b>6. Intellectual Property Rights</b> - The HCP confirm that the HCP shall not, at any time during
								the subsistence of the Agreement, infringe or violate or interfere with any patent, copyright,
								database right, design, trade secret, trademark and/or any intellectual property rights and/or
								property right and/or any other rights available to or vested in any third party(ies).
								<br><br>
					
					</td>
                </tr>
				
				
				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				    <br>
					<b>7. Compliance</b>
								<br><br>

								7.1 During the performance of his/her obligation under this Agreement, the HCP agrees to comply
								with all applicable laws and rules &amp; regulations made thereunder and/or any court,
								governmental or administrative order and or guidelines including without limitation guidelines
								issued by the National Medical Commission, the Code of Conduct and Business Ethics Policies of
								the Company.
								<br><br>
								7.2 The HCP agrees to indemnify and keep indemnified the Company and its directors, officers and
								employees, in respect of breach of this clause by the HCP, under this Agreement.
								<br><br>
								7.3 The HCP would not hold the Company liable in any manner whatsoever for any damages,
								financial or otherwise, which may result because of the above-stated reasons.
								<br><br>
					</td>
                </tr>
				
				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				<br>
				    <b>8. Independent Parties</b> - It is the express understanding and intention of the Parties that the
								HCP will be rendering the Services in its capacity as a consultant and is not an employee,
								representative, delegate, agent, joint venture, franchise or partner of the Company. HCP shall
								have no authority to assume, create, or enlarge any obligation or commitment on behalf of the
								Company without the prior written consent of the Company.
								<br><br>
								<b>9. Entire Agreement</b> - This Agreement constitutes this entire agreement between the Parties and
								supersedes all prior agreements and understandings, whether written or oral, relating to the
								subject matter of this Agreement.
								<br><br>
								<b>10. Modification</b> - No modification or amendment of this Agreement shall be valid or binding
								unless made in writing and, in the case of an amendment, executed by both the Parties.
								<br><br>
					</td>
                </tr>
				
				
				
				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				<br>
				    <b>11. Governing Law and Arbitration</b>
							
								<br><br>
								11.1 This Agreement will be governed, in all respect, by applicable Indian law(s) and the Parties
								hereby submit to the exclusive jurisdiction of the courts in New Delhi.
								<br><br>
								11.2 Any dispute in connection with this Agreement shall be in the first instance settled by amicable
								negotiation between the Parties hereto. In default of such amicable settlement for all or any
								matter within 15 days of the commencement of amicable proceedings, such matters as remain
								unsettled by negotiation as aforesaid shall be finally settled under the Arbitration and
								Conciliation Act, 1996, by single arbitrator appointed in accordance with the rules framed by the
								Indian Council of Arbitration, whose award shall be final and binding on the Parties hereto.
								<br><br>
					</td>
                </tr>
				
				
				<tr>
                <td style="font-size:14px;line-height:1.3;" colspan="8">	
				<br>
				    <b>12. Notices</b> - Any notice or other communication required to be given under this Agreement shall
								be in writing and shall be delivered personally or sent by commercial courier or email/web mail,
								to the other Party required to receive the notice or communication to the address set out at the
								beginning of this Agreement or such other address from time to time nominated by a Party.
								<br><br>
								<b>13. Waiver</b> - No delay or omission by the Company, in exercising any right under this Agreement,
								shall operate as a waiver of that or any other right. A waiver or consent given by the Company on
								any one occasion shall be effective only in that one instance and shall not be construed as a
								waiver of any other right.
								<br><br>
								<b>14. Severability</b> - If any of the provisions of this Agreement is held to be illegal or invalid by a court
								of competent jurisdiction, the remaining provisions shall be enforceable according to the terms
								of this Agreement.
								<br><br>
								<b>15. Counterparts</b> - This Agreement may be executed in multiple counterparts, each of which shall
								be considered and shall have the force and effect of an original.
								<br><br>
							<b>IN WITNESS WHEREOF,</b> the Parties have caused this Agreement to be executed on the date
							mentioned hereinabove.
							<br><br>
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
   
   
     $html.='
        <table width="800" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff"  >
         
					
            <tbody>
	            <tr>
                    <td height="10" colspan="8"></td>
                </tr>
			
			 
				<tr>
					<th align="center" colspan="8" style="font-size:20px;line-height: 1;text-decoration:underline">
                       Survey Questions
                    </th>
                </tr>
				
				<tr>
				<td style="font-size:18px;line-height:1.3;" colspan="8">
				<br>
					Q-1.which is the best way to eat healthier
					<br>
					<ul>
						<li>Vegan diet</li>
						<li>Protein-rich food</li>
						<li>Balanced diet</li>
						<li>Only fresh fruits</li>
						<li>Junck food</li>
					</ul>
				</td>
				</tr>


				<tr>
				<td style="font-size:18px;line-height:1.3;" colspan="8">
				<br>
					Q-2. Which gender group suffer from depression (Tick all options applicable)
					<br>
					<ul>
						<li>Less than 5</li>
						<li>6 – 10</li>
						<li>11 – 15</li>
					</ul>
				</td>
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
