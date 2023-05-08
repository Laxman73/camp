<?php

ini_set("display_errors",1);
	require_once('include/uifromdbutil.php');
	require_once('include/ComboUtil.php');
	require_once('include/Whatsapp_shortener.php');

	global $adb;

// $patient_id = $_REQUEST['wid'];
$type_call= $_REQUEST['type'];
if($type_call==1)
{
	$imagedata= 1;

	$currentmonth=date("m");
	$currentyear=date("Y");
	$currentdatenew=date("Y-m-d h:i:sa");
	//$ConsentType = 'WhatsApp';
	$pvSMDate = date("Y-m-01", strtotime("- 6 months"));
	$chk_flag = 0;
	$errorTxt = '';

	$counsellor_name="Vaishnavi";
	$patient_fname	="Testing Developer Vaishnavi";
	$patient_lname	="Parab";
	// $doctor_mobile	="";
	$doctor_mobile	=$_REQUEST['docmobile'];
	$counllr_id 	= 0;
	$product_type 	=1;
	
	$naf_request_id 	= $_REQUEST['naf_requestid'];
	
	$sql = "SELECT hcp_id from crm_request_details where crm_request_main_id ='".$naf_request_id."'";
	$result = $adb->query($sql)or die('Error 4'.mysql_error());
	$contactid = (int)$adb->query_result($result,0,"hcp_id");
	
	
	$sql_naf = "SELECT crm_naf_main.naf_activity_name FROM crm_request_main 
	INNER JOIN crm_naf_main ON crm_request_main.naf_no = crm_naf_main.naf_no
	WHERE crm_request_main.id ='".$naf_request_id."' AND crm_request_main.deleted = 0 AND crm_naf_main.deleted = 0";
	$result_naf = $adb->query($sql_naf)or die('Error 4'.mysql_error());
	$naf_activity_name = $adb->query_result($result_naf,0,"naf_activity_name");
	
	$contactid 	=1;
	
	
	//Added By Saish Naik------
	$ffid 	=1;
	$contactid 	=1;
	//END Added By Saish Naik------
	$user_mobile = 1;

	
	$patient_id = $_REQUEST['shortcodeid'];
	$sq = "Select AES_DECRYPT(doctor_mobile,'".$encryption_key."')as doctor_mobile,consentshort_url from crm_questionoire_link_sharing where id ='".$patient_id."'";
	// $result = $adb->query($sq)or die('get phone Error:'.mysql_error());
	// $p_mobile=$adb->query_result($result,0,"doctor_mobile");
	$p_mobile="";

	$shortURL_Prefix = "http://88.99.140.102/MicrolabReplicav3/WAP/";
	$shortCode = $adb->query_result($result,0,"consentshort_url");;
	// Create short URL
	$shortURL = $shortURL_Prefix.$shortCode;
	$shortURL = "http://r.micronavdisha.com/AP/884RXr4";
	//$shortURL = "http://s.anant.co.in/APIE/884RXr4";
	

	
	$message = "Dear Doctor, Greetings from Microlabs please click on the link {#var1#} to provide your insights on {#var#} -Repforce";
    $handle = curl_init();
    $message=str_replace("{#var#}",$naf_activity_name,$message);
	
    $handle = curl_init();
    $message=str_replace("{#var1#}",$shortURL,$message);

    $url = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=380197&To=".urlencode($doctor_mobile)."&Text=".urlencode($message)."&senderid=REPFOR&username=9623448744&password=Pgmag@123";
        curl_setopt_array($handle,
        array(
            CURLOPT_URL => $url,
            CURLOPT_NOBODY => 1,
            // CURLOPT_POST => true,
            // CURLOPT_POSTFIELDS => $postData,
        )
    );

    $data = curl_exec($handle);
    $err = curl_error($handle);
    curl_close($handle);
	
	$insert_link_share= "INSERT INTO `crm_link_sharing_flag`
		(
			`link_share`,
			`crm_request_main_id`,
			`crm_questionoire_link_sharing_id`,
			`submitted`
			
		)
	VALUES (
			'1',
			'".$naf_request_id."',
			'".$patient_id."',
			'1'
		)";
		
	
	$result3=$adb->query($insert_link_share) or die('Error 6'.mysql_error());	
	
	// echo $id."@@".$id."@@".$message;
	// echo "<br> SMS SENT  -->>".$data."<-->>".$err."<<--1-->>".$message."---**--".$p_mobile."---**--".$shortURL;
	echo json_encode(array("status" => TRUE,"message"=>"Thank You for your Consent..."));
}

if($type_call==2)
{
	$currentmonth=date("m");
	$currentyear=date("Y");
	$currentdatenew=date("Y-m-d h:i:sa");
	//$ConsentType = 'WhatsApp';
	$pvSMDate = date("Y-m-01", strtotime("- 6 months"));
	$chk_flag = 0;
	$errorTxt = '';

	$counsellor_name="Vaishnavi";
	$patient_fname	="Testing Developer Vaishnavi";
	$patient_lname	="Parab";
	$doctor_mobile=$_REQUEST['docmobile'];
	$counllr_id 	= 0;
	$product_type 	=1;
	
	$naf_request_id 	= $_REQUEST['naf_requestid'];
	$pid 	= $_REQUEST['pid'];
	
	$sql = "SELECT hcp_id from crm_request_details where crm_request_main_id ='".$naf_request_id."'";
	$result = $adb->query($sql)or die('Error 4'.mysql_error());
	$contactid = (int)$adb->query_result($result,0,"hcp_id");
	
	$contactid 	=1;

	//Added By Saish Naik------
	$ffid 	=1;
	$contactid 	=1;
	//END Added By Saish Naik------
	$user_mobile = 1;
	
	$product_id = 2;
	
	$sql2 = "SELECT * from crm_questionoire_link_sharing where crm_naf_main_id ='".$naf_request_id."' and pid ='".$pid."' and deleted=0";
	$result2 = $adb->query($sql2)or die('Error 4'.mysql_error());
	$countrecord = $adb->num_rows($result2);
	$shortCode = $adb->query_result($result2,0,"consentshort_url");
	$id = $adb->query_result($result2,0,"id");
	
	if($countrecord <= 0)
	{
	
		$insert_resc= "INSERT INTO `crm_questionoire_link_sharing`
			(
				`crm_type`,
				`crm_naf_main_id`,
				`contactid`,
				`doctor_mobile`,
				`pid`
				
				
			)
		VALUES (
				'questionnaire',
				'".$naf_request_id."',
				'".$contactid."',
				'".$doctor_mobile."',
				'".$pid."'
			)";
			
		
		$result2=$adb->query($insert_resc) or die('Error 5'.mysql_error());
		$id = mysql_insert_id();
		
		$consentlink = $site_URL."/index_teleconsent.php?module=WhatsAppConsent&action=consent_capture&id=".$id;
		//$shortURL_Prefix = "http://o.anant.co.in/";
		// $shortURL_Prefix = "https://www.saath7psp.com/";
		//$shortURL_Prefix = "http://88.99.140.102/Sanofi_Projects/Saath7Reports_Dev/WhatsappAPI/";
		
		$shortCode = urlToShortCode($consentlink,$id);
		// Create short URL
	}
	
	$shortURL_Prefix = "http://88.99.140.102/MicrolabReplicav3/WAP/";
	
	$shortURL = $shortURL_Prefix.$shortCode;
	
	echo json_encode(array("shourtURL" => $shortURL,"shourtURLId" => $id));
}

if($type_call==3)
{
	$currentmonth=date("m");
	$currentyear=date("Y");
	$currentdatenew=date("Y-m-d h:i:sa");
	//$ConsentType = 'WhatsApp';
	$pvSMDate = date("Y-m-01", strtotime("- 6 months"));
	$chk_flag = 0;
	$errorTxt = '';

	$counsellor_name="Vaishnavi";
	$patient_fname	="Testing Developer Vaishnavi";
	$patient_lname	="Parab";
	// $doctor_mobile = "";
	$doctor_mobile = $_REQUEST['docmobile'];
	$pid_val = $_REQUEST['pid'];
	$counllr_id 	= 0;
	$product_type 	=1;
	
	$naf_request_id 	= $_REQUEST['naf_requestid'];
	
	$sql2 = "SELECT * from crm_agreement_link_sharing where crm_naf_main_id ='".$naf_request_id."' and pid ='".$pid_val."' and deleted=0";
	$result2 = $adb->query($sql2)or die('Error 4'.mysql_error());
	$countrecord = $adb->num_rows($result2);
	$shortCode = $adb->query_result($result2,0,"consentshort_url");
	$id = $adb->query_result($result2,0,"id");
	
	$contactid 	=1;
	
	if($countrecord <= 0)
	{
		$insert_resc= "INSERT INTO `crm_agreement_link_sharing`
			(
				`crm_type`,
				`crm_naf_main_id`,
				`contactid`,
				`doctor_mobile`,
				pid
				
			)
		VALUES (
				'agreement',
				'".$naf_request_id."',
				'".$contactid."',
				'".$doctor_mobile."',
				'".$pid_val."'
			)";
		
		$result2=$adb->query($insert_resc) or die('Error 5'.mysql_error());
		$id = mysql_insert_id();
		
		$consentlink = $site_URL."/index.php?module=CRM&action=hcp_agreement&id=".$id;
		$shortCode = urlToShortCode_agr($consentlink,$id);
	}
	

	$shortURL_Prefix = "http://88.99.140.102/MicrolabReplicav3/WAP/";
	
	// Create short URL
	$shortURL = $shortURL_Prefix.$shortCode;
	
	echo json_encode(array("shourtURL" => $shortURL,"shourtURLId" => $id));
	
}

if($type_call==4)
{
	$question_id = $_REQUEST['question_requestid'];
	$doctor_mobile = $_REQUEST['docmobile'];
	$naf_request_id = $_REQUEST['naf_requestid'];
	$otpforupload = rand(10000,99999);
    if($question_id!="")
    {
        $upd_query="INSERT INTO crm_questionnaire_OTP (crm_questionnaire_id,question_otp) VALUES ('".$question_id."','".$otpforupload."')";
        $res_upd_query=$adb->query($upd_query) or die('Error 5'.mysql_error());
    }
	
	$sql_naf = "SELECT crm_naf_main.naf_activity_name FROM crm_naf_main 	
	WHERE crm_naf_main.id ='".$naf_request_id."' AND crm_naf_main.deleted = 0";
	$result_naf = $adb->query($sql_naf)or die('Error 4'.mysql_error());
	$naf_activity_name = $adb->query_result($result_naf,0,"naf_activity_name");
	
    $message = "Dear Doctor, your one-time password (OTP) is {#var#} to confirm your participation in {#var1#} -Repforce";
    $handle = curl_init();
    $message=str_replace("{#var#}",$otpforupload,$message);
	
	$handle = curl_init();
    $message=str_replace("{#var1#}",$naf_activity_name,$message);

    $url = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=380197&To=".urlencode($doctor_mobile)."&Text=".urlencode($message)."&senderid=REPFOR&username=9623448744&password=Pgmag@123";
        curl_setopt_array($handle,
        array(
            CURLOPT_URL => $url,
            CURLOPT_NOBODY => 1,
        )
    );
    $data = curl_exec($handle);
    $err = curl_error($handle);
    curl_close($handle);
	
	echo json_encode(array("status" => TRUE,"message"=>"OTP Generated successfully..."));
}

if($type_call==5)
{
	$question_id = $_REQUEST['question_requestid'];
	$mobileOTP = $_REQUEST['mobileOTP'];
	$otpforupload = rand(10000,99999);
    if($question_id!="")
    {
        $upd_query1="SELECT * FROM crm_questionnaire_OTP, (SELECT id FROM crm_questionnaire_OTP WHERE crm_questionnaire_id = '".$question_id."' AND deleted = 0 ORDER BY id DESC LIMIT 1)k1 WHERE crm_questionnaire_OTP.id = k1.id AND crm_questionnaire_OTP.question_otp = '".$mobileOTP."'";
        $res_upd_query1=$adb->query($upd_query1) or die('Error 5'.mysql_error());
		$otp_count = $adb->num_rows($res_upd_query1);
		$question_otp = (int)$adb->query_result($res_upd_query1,0,"question_otp");
		if($otp_count!=0){
			echo json_encode(array("status" => TRUE,"message"=>"OTP validated successfully.."));
		}
		else{
			echo json_encode(array("status" => FALSE,"message"=>"Invalid OTP.."));
		}
    }
}

if($type_call==6)
{
	$patient_id = $_REQUEST['shortcodeid'];
	$naf_request_id = $_REQUEST['naf_requestid'];
	$pid = $_REQUEST['pid'];
	
	$sql_naf = "SELECT crm_naf_main.naf_activity_name FROM crm_naf_main 	
	WHERE crm_naf_main.id ='".$naf_request_id."' AND crm_naf_main.deleted = 0";
	$result_naf = $adb->query($sql_naf)or die('Error 4'.mysql_error());
	$naf_activity_name = $adb->query_result($result_naf,0,"naf_activity_name");
	
	
	$sq = "Select AES_DECRYPT(doctor_mobile,'".$encryption_key."')as doctor_mobile,consentshort_url from crm_agreement_link_sharing_flag where id ='".$patient_id."'";
	//$result = $adb->query($sq)or die('get phone Error:'.mysql_error());
	// $p_mobile=$adb->query_result($result,0,"doctor_mobile");
	$p_mobile="";
	$doctor_mobile = $_REQUEST['docmobile'];
	$shortURL_Prefix = "http://88.99.140.102/MicrolabReplicav3/WAP/";
	// $shortCode = $adb->query_result($result,0,"consentshort_url");;
	// Create short URL
	$shortURL = $shortURL_Prefix.$shortCode;
	
	$shortURL = "http://r.micronavdisha.com/AP/884RXr4";
	
	$message = "Dear Doctor, Greetings from Microlabs please click on the link {#var1#} for your consent and Agreement for {#var#} -Repforce";
	$handle = curl_init();
    $message=str_replace("{#var#}",$naf_activity_name,$message);
	
    $handle = curl_init();
    $message=str_replace("{#var1#}",$shortURL,$message);
	
    $url = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=380197&To=".urlencode($doctor_mobile)."&Text=".urlencode($message)."&senderid=REPFOR&username=9623448744&password=Pgmag@123";
        curl_setopt_array($handle,
        array(
            CURLOPT_URL => $url,
            CURLOPT_NOBODY => 1,
            // CURLOPT_POST => true,
            // CURLOPT_POSTFIELDS => $postData,
        )
    );

    $data = curl_exec($handle);
    $err = curl_error($handle);
    curl_close($handle);
	
	/*$insert_link_share= "INSERT INTO `crm_agreement_link_sharing_flag`
		(
			`link_share`,
			`crm_request_main_id`,
			`crm_agreement_link_sharing_id`,
			`submitted`
			
		)
	VALUES (
			'1',
			'".$naf_request_id."',
			'".$patient_id."',
			'1'
		)";
	*/	
	$insert_link_share="UPDATE crm_agreement_link_sharing set link_share=1,link_shared_date=NOW() where crm_naf_main_id='".$naf_request_id."' and pid='".$pid."' and id='".$patient_id."' and deleted=0";
	$result3=$adb->query($insert_link_share) or die('Error 6'.mysql_error());	
	
	// echo $id."@@".$id."@@".$message;
	// echo "<br> SMS SENT  -->>".$data."<-->>".$err."<<--1-->>".$message."---**--".$p_mobile."---**--".$shortURL;
	echo json_encode(array("status" => TRUE,"message"=>"Thank You for your Consent..."));
}
if($type_call==7)
{
	$currentmonth=date("m");
	$currentyear=date("Y");
	$currentdatenew=date("Y-m-d h:i:sa");
	//$ConsentType = 'WhatsApp';
	$pvSMDate = date("Y-m-01", strtotime("- 6 months"));
	$chk_flag = 0;
	$errorTxt = '';

	$counsellor_name="Vaishnavi";
	$patient_fname	="Testing Developer Vaishnavi";
	$patient_lname	="Parab";
	// $doctor_mobile = "";
	$doctor_mobile = $_REQUEST['docmobile'];
	$pid_val = $_REQUEST['pid'];
	$counllr_id 	= 0;
	$product_type 	=1;
	
	$naf_request_id 	= $_REQUEST['naf_requestid'];
	
	$sql2 = "SELECT * from crm_agreement_link_sharing where crm_naf_main_id ='".$naf_request_id."' and pid ='".$pid_val."' and crm_type='advisory_agreement' and deleted=0";
	$result2 = $adb->query($sql2)or die('Error 4'.mysql_error());
	$countrecord = $adb->num_rows($result2);
	$shortCode = $adb->query_result($result2,0,"consentshort_url");
	$id = $adb->query_result($result2,0,"id");
	
	$sql = "SELECT hcp_id from crm_request_details where crm_request_main_id ='".$naf_request_id."'";
	$result = $adb->query($sql)or die('Error 4'.mysql_error());
	$contactid = (int)$adb->query_result($result,0,"hcp_id");
	
	if($countrecord <= 0)
	{
		$insert_resc= "INSERT INTO `crm_agreement_link_sharing`
			(
				`crm_type`,
				`crm_naf_main_id`,
				`contactid`,
				`doctor_mobile`,
				pid
				
			)
		VALUES (
				'advisory_agreement',
				'".$naf_request_id."',
				'".$contactid."',
				'".$doctor_mobile."',
				'".$pid_val."'
			)";
		
		$result2=$adb->query($insert_resc) or die('Error 5'.mysql_error());
		$id = mysql_insert_id();
		
		$consentlink = $site_URL."/index.php?module=CRM&action=service_agreement_advisory&id=".$id;
		$crm_type='advisory_agreement';
		$shortCode = urlToShortCode_agr($consentlink,$id,$crm_type);
	}
	

	$shortURL_Prefix = "http://88.99.140.102/MicrolabReplicav3/WAP/";
	
	// Create short URL
	$shortURL = $shortURL_Prefix.$shortCode;
	
	echo json_encode(array("shourtURL" => $shortURL,"shourtURLId" => $id));
	
}
if($type_call==8)
{
	$currentmonth=date("m");
	$currentyear=date("Y");
	$currentdatenew=date("Y-m-d h:i:sa");
	//$ConsentType = 'WhatsApp';
	$pvSMDate = date("Y-m-01", strtotime("- 6 months"));
	$chk_flag = 0;
	$errorTxt = '';

	$counsellor_name="Vaishnavi";
	$patient_fname	="Testing Developer Vaishnavi";
	$patient_lname	="Parab";
	// $doctor_mobile = "";
	$doctor_mobile = $_REQUEST['docmobile'];
	$pid_val = $_REQUEST['pid'];
	$counllr_id 	= 0;
	$product_type 	=1;
	
	$naf_request_id 	= $_REQUEST['naf_requestid'];
	
	$sql2 = "SELECT * from crm_agreement_link_sharing where crm_naf_main_id ='".$naf_request_id."' and pid ='".$pid_val."' and crm_type='advisory_agreement' and deleted=0";
	$result2 = $adb->query($sql2)or die('Error 4'.mysql_error());
	$countrecord = $adb->num_rows($result2);
	$shortCode = $adb->query_result($result2,0,"consentshort_url");
	$id = $adb->query_result($result2,0,"id");
	
	$sql = "SELECT hcp_id from crm_request_details where crm_request_main_id ='".$naf_request_id."'";
	$result = $adb->query($sql)or die('Error 4'.mysql_error());
	$contactid = (int)$adb->query_result($result,0,"hcp_id");
	
	if($countrecord <= 0)
	{
		$insert_resc= "INSERT INTO `crm_agreement_link_sharing`
			(
				`crm_type`,
				`crm_naf_main_id`,
				`contactid`,
				`doctor_mobile`,
				pid
				
			)
		VALUES (
				'camp_agreement',
				'".$naf_request_id."',
				'".$contactid."',
				'".$doctor_mobile."',
				'".$pid_val."'
			)";
		
		$result2=$adb->query($insert_resc) or die('Error 5'.mysql_error());
		$id = mysql_insert_id();
		
		$consentlink = $site_URL."/index.php?module=CRM&action=service_agreement_camp&id=".$id;
		$crm_type='camp_agreement';
		$shortCode = urlToShortCode_agr($consentlink,$id,$crm_type);
	}
	

	$shortURL_Prefix = "http://88.99.140.102/MicrolabReplicav3/WAP/";
	
	// Create short URL
	$shortURL = $shortURL_Prefix.$shortCode;
	
	echo json_encode(array("shourtURL" => $shortURL,"shourtURLId" => $id));
	
}
if($type_call==9)
{
	$patient_id = $_REQUEST['shortcodeid'];
	$naf_request_id = $_REQUEST['naf_requestid'];
	$pid = $_REQUEST['pid'];
	
	$sql_naf = "SELECT crm_naf_main.naf_activity_name FROM crm_naf_main 	
	WHERE crm_naf_main.id ='".$naf_request_id."' AND crm_naf_main.deleted = 0";
	$result_naf = $adb->query($sql_naf)or die('Error 4'.mysql_error());
	$naf_activity_name = $adb->query_result($result_naf,0,"naf_activity_name");
	
	
	$sq = "Select AES_DECRYPT(doctor_mobile,'".$encryption_key."')as doctor_mobile,consentshort_url from crm_agreement_link_sharing_flag where id ='".$patient_id."'";
	//$result = $adb->query($sq)or die('get phone Error:'.mysql_error());
	// $p_mobile=$adb->query_result($result,0,"doctor_mobile");
	$p_mobile="";
	$doctor_mobile = $_REQUEST['docmobile'];
	$shortURL_Prefix = "http://88.99.140.102/MicrolabReplicav3/WAP/";
	// $shortCode = $adb->query_result($result,0,"consentshort_url");;
	// Create short URL
	$shortURL = $shortURL_Prefix.$shortCode;
	
	$shortURL = "http://r.micronavdisha.com/AP/884RXr4";
	
	$message = "Dear Doctor, Greetings from Microlabs please click on the link {#var1#} for your consent and Agreement for {#var#} -Repforce";
	$handle = curl_init();
    $message=str_replace("{#var#}",$naf_activity_name,$message);
	
    $handle = curl_init();
    $message=str_replace("{#var1#}",$shortURL,$message);
	
    $url = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=380197&To=".urlencode($doctor_mobile)."&Text=".urlencode($message)."&senderid=REPFOR&username=9623448744&password=Pgmag@123";
        curl_setopt_array($handle,
        array(
            CURLOPT_URL => $url,
            CURLOPT_NOBODY => 1,
            // CURLOPT_POST => true,
            // CURLOPT_POSTFIELDS => $postData,
        )
    );

    $data = curl_exec($handle);
    $err = curl_error($handle);
    curl_close($handle);
	
	/*$insert_link_share= "INSERT INTO `crm_agreement_link_sharing_flag`
		(
			`link_share`,
			`crm_request_main_id`,
			`crm_agreement_link_sharing_id`,
			`submitted`
			
		)
	VALUES (
			'1',
			'".$naf_request_id."',
			'".$patient_id."',
			'1'
		)";
	*/	
	$insert_link_share="UPDATE crm_agreement_link_sharing set link_share=1,link_shared_date=NOW() where crm_naf_main_id='".$naf_request_id."' and pid='".$pid."' and id='".$patient_id."' and deleted=0";
	$result3=$adb->query($insert_link_share) or die('Error 6'.mysql_error());	
	
	// echo $id."@@".$id."@@".$message;
	// echo "<br> SMS SENT  -->>".$data."<-->>".$err."<<--1-->>".$message."---**--".$p_mobile."---**--".$shortURL;
	echo json_encode(array("status" => TRUE,"message"=>"Thank You for your Consent..."));
}
if($type_call==10)
{
	$currentmonth=date("m");
	$currentyear=date("Y");
	$currentdatenew=date("Y-m-d h:i:sa");
	//$ConsentType = 'WhatsApp';
	$pvSMDate = date("Y-m-01", strtotime("- 6 months"));
	$chk_flag = 0;
	$errorTxt = '';

	$counsellor_name="Vaishnavi";
	$patient_fname	="Testing Developer Vaishnavi";
	$patient_lname	="Parab";
	// $doctor_mobile = "";
	$doctor_mobile = $_REQUEST['docmobile'];
	$pid_val = $_REQUEST['pid'];
	$counllr_id 	= 0;
	$product_type 	=1;
	
	$naf_request_id 	= $_REQUEST['naf_requestid'];
	
	$sql2 = "SELECT * from crm_agreement_link_sharing where crm_naf_main_id ='".$naf_request_id."' and pid ='".$pid_val."' and crm_type='consultancy_agreement' and deleted=0";
	$result2 = $adb->query($sql2)or die('Error 4'.mysql_error());
	$countrecord = $adb->num_rows($result2);
	$shortCode = $adb->query_result($result2,0,"consentshort_url");
	$id = $adb->query_result($result2,0,"id");
	
	$sql = "SELECT hcp_id from crm_request_details where crm_request_main_id ='".$naf_request_id."'";
	$result = $adb->query($sql)or die('Error 4'.mysql_error());
	$contactid = (int)$adb->query_result($result,0,"hcp_id");
	
	if($countrecord <= 0)
	{
		$insert_resc= "INSERT INTO `crm_agreement_link_sharing`
			(
				`crm_type`,
				`crm_naf_main_id`,
				`contactid`,
				`doctor_mobile`,
				pid
				
			)
		VALUES (
				'consultancy_agreement',
				'".$naf_request_id."',
				'".$contactid."',
				'".$doctor_mobile."',
				'".$pid_val."'
			)";
		
		$result2=$adb->query($insert_resc) or die('Error 5'.mysql_error());
		$id = mysql_insert_id();
		
		$consentlink = $site_URL."/index.php?module=CRM&action=service_agreement_consultancy&id=".$id;
		$crm_type='consultancy_agreement';
		$shortCode = urlToShortCode_agr($consentlink,$id,$crm_type);
	}
	

	$shortURL_Prefix = "http://88.99.140.102/MicrolabReplicav3/WAP/";
	
	// Create short URL
	$shortURL = $shortURL_Prefix.$shortCode;
	
	echo json_encode(array("shourtURL" => $shortURL,"shourtURLId" => $id));
	
}

if($type_call==11)
{
	$act_reqid = $_REQUEST['act_reqid'];
	$doctor_mobile = $_REQUEST['docmobile'];
	$naf_request_id = $_REQUEST['naf_requestid'];
	$otpforupload = rand(10000,99999);
    if($naf_request_id!="")
    {
        $upd_query="INSERT INTO crm_acknowledgement_OTP (naf_request_id,otp) VALUES ('".$naf_request_id."','".$otpforupload."')";
        $res_upd_query=$adb->query($upd_query) or die('Error 5'.mysql_error());
    }
	
	$sql_naf = "SELECT crm_naf_main.naf_activity_name FROM crm_naf_main 	
	WHERE crm_naf_main.id ='".$naf_request_id."' AND crm_naf_main.deleted = 0";
	$result_naf = $adb->query($sql_naf)or die('Error 4'.mysql_error());
	$naf_activity_name = $adb->query_result($result_naf,0,"naf_activity_name");
	
    $message = "Dear Doctor, your one-time password (OTP) is {#var#} to confirm your participation in {#var1#} -Repforce";
    $handle = curl_init();
    $message=str_replace("{#var#}",$otpforupload,$message);
	
	$handle = curl_init();
    $message=str_replace("{#var1#}",$naf_activity_name,$message);

    $url = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=380197&To=".urlencode($doctor_mobile)."&Text=".urlencode($message)."&senderid=REPFOR&username=9623448744&password=Pgmag@123";
        curl_setopt_array($handle,
        array(
            CURLOPT_URL => $url,
            CURLOPT_NOBODY => 1,
        )
    );
    $data = curl_exec($handle);
    $err = curl_error($handle);
    curl_close($handle);
	
	echo json_encode(array("status" => TRUE,"message"=>"OTP Generated successfully..."));
}

if($type_call==12)
{
	$naf_requestid = $_REQUEST['naf_requestid'];
	$mobileOTP = $_REQUEST['mobileOTP'];
	$otpforupload = rand(10000,99999);
    if($naf_requestid!="")
    {
        $upd_query1="SELECT * FROM crm_acknowledgement_OTP, (SELECT id FROM crm_acknowledgement_OTP WHERE naf_request_id = '".$naf_requestid."' AND deleted = 0 ORDER BY id DESC LIMIT 1)k1 WHERE crm_acknowledgement_OTP.id = k1.id AND crm_acknowledgement_OTP.otp = '".$mobileOTP."'";
        $res_upd_query1=$adb->query($upd_query1) or die('Error 5'.mysql_error());
		$otp_count = $adb->num_rows($res_upd_query1);
		$question_otp = (int)$adb->query_result($res_upd_query1,0,"question_otp");
		if($otp_count!=0){
			echo json_encode(array("status" => TRUE,"message"=>"OTP validated successfully.."));
		}
		else{
			echo json_encode(array("status" => FALSE,"message"=>"Invalid OTP.."));
		}
    }
}

if($type_call==13)
{
	$doctor_mobile = $_REQUEST['docmobile'];
	$pid_val = $_REQUEST['pid'];
	
	$naf_request_id 	= $_REQUEST['naf_requestid'];
	
	$sql2 = "SELECT * from crm_agreement_link_sharing where crm_naf_main_id ='".$naf_request_id."' and pid ='".$pid_val."' and crm_type='acknowledgement' and deleted=0";
	$result2 = $adb->query($sql2)or die('Error 4'.mysql_error());
	$countrecord = $adb->num_rows($result2);
	$shortCode = $adb->query_result($result2,0,"consentshort_url");
	$id = $adb->query_result($result2,0,"id");
	
	$sql = "SELECT hcp_id from crm_request_details where crm_request_main_id ='".$pid."'";
	$result = $adb->query($sql)or die('Error 4'.mysql_error());
	$contactid = (int)$adb->query_result($result,0,"hcp_id");
	
	if($countrecord <= 0)
	{
		$insert_resc= "INSERT INTO `crm_agreement_link_sharing`
			(
				`crm_type`,
				`crm_naf_main_id`,
				`contactid`,
				`doctor_mobile`,
				pid
				
			)
		VALUES (
				'acknowledgement',
				'".$naf_request_id."',
				'".$contactid."',
				'".$doctor_mobile."',
				'".$pid_val."'
			)";
		
		$result2=$adb->query($insert_resc) or die('Error 5'.mysql_error());
		$id = mysql_insert_id();
		
		$consentlink = $site_URL."/index.php?module=CRM_demo&action=generate_pdf_krishna&id=".$id;
		$crm_type='acknowledgement';
		$shortCode = urlToShortCode_agr($consentlink,$id,$crm_type);
	}
	

	$shortURL_Prefix = "http://88.99.140.102/MicrolabReplicav3/WAP/";
	
	// Create short URL
	$shortURL = $shortURL_Prefix.$shortCode;
	
	echo json_encode(array("shourtURL" => $shortURL,"shourtURLId" => $id));
	
}
if ($type_call == 14) {
	$question_id = $_REQUEST['question_requestid'];
	$doctor_mobile = $_REQUEST['docmobile'];
	$naf_request_id = $_REQUEST['naf_requestid'];
	$otpforupload = rand(10000, 99999);
	//$crm_camp_id=NextID('id','crm_camp_otp');
	if ($question_id != "") {
		$upd_query = "INSERT INTO crm_camp_otp (naf_request_id,otp) VALUES ('".$question_id."','" . $otpforupload . "')";
		$res_upd_query = $adb->query($upd_query) or die('Error 5' . mysql_error());
	}

	$sql_naf = "SELECT crm_naf_main.naf_activity_name FROM crm_naf_main 	
	WHERE crm_naf_main.id ='" . $naf_request_id . "' AND crm_naf_main.deleted = 0";
	$result_naf = $adb->query($sql_naf) or die('Error 4' . mysql_error());
	$naf_activity_name = $adb->query_result($result_naf, 0, "naf_activity_name");

	$message = "Dear Doctor, your one-time password (OTP) is {#var#} to confirm your participation in {#var1#} -Repforce";
	$handle = curl_init();
	$message = str_replace("{#var#}", $otpforupload, $message);

	$handle = curl_init();
	$message = str_replace("{#var1#}", $naf_activity_name, $message);

	$url = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=380197&To=" . urlencode($doctor_mobile) . "&Text=" . urlencode($message) . "&senderid=REPFOR&username=9623448744&password=Pgmag@123";
	curl_setopt_array(
		$handle,
		array(
			CURLOPT_URL => $url,
			CURLOPT_NOBODY => 1,
		)
	);
	$data = curl_exec($handle);
	$err = curl_error($handle);
	curl_close($handle);

	echo json_encode(array("status" => TRUE, "message" => "OTP Generated successfully..."));
}
if ($type_call == 15) {
	$question_id = $_REQUEST['question_requestid'];
	$mobileOTP = $_REQUEST['mobileOTP'];
	$otpforupload = rand(10000, 99999);
	if ($question_id != "") {
		$upd_query1 = "SELECT * FROM crm_camp_otp, (SELECT id FROM crm_camp_otp WHERE naf_request_id = '" . $question_id . "' AND deleted = 0 ORDER BY id DESC LIMIT 1)k1 WHERE crm_camp_otp.id = k1.id AND crm_camp_otp.otp = '" . $mobileOTP . "'";
		$res_upd_query1 = $adb->query($upd_query1) or die('Error 5' . mysql_error());
		$otp_count = $adb->num_rows($res_upd_query1);
		$question_otp = (int)$adb->query_result($res_upd_query1, 0, "question_otp");
		if ($otp_count != 0) {
			echo json_encode(array("status" => TRUE, "message" => "OTP validated successfully.."));
		} else {
			echo json_encode(array("status" => FALSE, "message" => "Invalid OTP.."));
		}
	}
}

 ?>
