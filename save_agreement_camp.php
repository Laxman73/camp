<?php
include 'includes/common.php';
$pdo=connectToDatabase();

$rid=(isset($_POST['rid']))?$_POST['rid']:'';
$pid=(isset($_POST['pid']))?$_POST['pid']:'';
$prid=(isset($_POST['prid']))?$_POST['prid']:'';
$userid=(isset($_POST['userid']))?$_POST['userid']:'';
$shortcode_mode = db_input($_POST['shortcodemode']);

$crm_hcp_information_id=(isset($_POST['crm_hcp_information_id']))?$_POST['crm_hcp_information_id']:'';
$disp_url='index_pma.php?userid'.$userid;

if (empty($crm_hcp_information_id)) {
    header('location:'.$disp_url);	
}

	$folderPath = "upload/";
	// Upload Signature 
    $image_parts = explode(";base64,", $_POST['signature1']);        
    $image_type_aux = explode("image/", $image_parts[0]);      
    $image_type = $image_type_aux[1];      
    $image_base64 = base64_decode($image_parts[1]);

	$imageData=$_POST['signature1'];
	$filteredData=substr($imageData, strpos($imageData, ",")+1);
 
	// Need to decode before saving since the data we received is already base64 encoded
	$unencodedData=base64_decode($filteredData);
 
	$file = $folderPath . uniqid() . '.'.$image_type;
 
	$fp = fopen( $file, 'wb' ) or die("File does not exist!");;
	fwrite( $fp, $unencodedData);
	fclose( $fp );
	
	//Upload Signature
	$image_parts1 = explode(";base64,", $_POST['signature2']);        
    $image_type_aux1 = explode("image/", $image_parts1[0]);      
    $image_type1 = $image_type_aux1[1];      
    $image_base641 = base64_decode($image_parts1[1]);

	$imageData1=$_POST['signature2'];
	$filteredData1=substr($imageData1, strpos($imageData1, ",")+1);
 
	// Need to decode before saving since the data we received is already base64 encoded
	$unencodedData1=base64_decode($filteredData1);
 
	$file1 = $folderPath . uniqid() . '.'.$image_type1;
 
	$fp1 = fopen( $file1, 'wb' ) or die("File does not exist!");;
	fwrite( $fp1, $unencodedData1);
	fclose( $fp1 );
	
	
$stmt=$pdo->prepare("insert into crm_hcp_agreement (id,crm_hcp_information_id,hcp_sign,company_sign,sign_date,submitted_on,submitted,deleted,deleted_on) values(?,?,?,?,?,?,?,?,?)");
$id=NextID('id','crm_hcp_agreement');




	 if($stmt->execute(array($id,$crm_hcp_information_id,$file1,$file,NOW,NOW,1,0,NOW))){
		if($shortcode_mode=='')
		{
			//http://88.99.140.102/MicrolabReplicav3/modules/CRM_demo/service_agreement_camp.php?rid=140&prid=&userid=19804&pid=134
			header('location: service_agreement_camp.php?rid='.$rid.'&userid='.$userid.'&pid='.$pid.'&prid='.$prid);
            //echo 'success';
			exit;  
		}
		else
		{
				
			header('location: service_agreement_camp.php?rid='.$rid.'&userid='.$userid.'&pid='.$pid.'&prid='.$prid);
            //echo 'success';
			exit;		
		}
	}
?>