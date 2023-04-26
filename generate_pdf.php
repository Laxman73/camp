<?php
include 'includes/common.php';
$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$prid = (isset($_GET['prid'])) ? $_GET['prid'] : '';//parent id
$USER_ID = (isset($_GET['userid']))?$_GET['userid']:'';
$MODE = (isset($_GET['mode']))?$_GET['mode']:'';
$display_all = (isset($_GET['display_all']))?$_GET['display_all']:'0';

//if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))|| $USER_ID!='2')
if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID)))
{
	echo 'Invalid Access Detected!!!';
	exit;
}
$disp_url = 'pma_search.php';
if (empty($rid)) {
    header('location:' . $disp_url);
}

//getting role and profile of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");

$sign_display = "display:";
$sign_edit = "display:none";

$naf_no=GetXFromYID("select naf_no from crm_naf_main where id='$rid' ");
$e_sign_doctor=GetXFromYID("select e_sign_doctor from crm_request_main where id='$pid' ");

// defining mode
if(!empty($rid) && $PROFILE_ID == 22){
	$MODE = 'R';
	$sign_display = "display:";
	$sign_edit = "display:none";
}
else{
	
	if(!empty($e_sign_doctor))
	{
		$sign_display = "display:";
		$sign_edit = "display:none";
		$MODE = 'R';
	}
	else
	{
		$sign_display = "display:none";
		$sign_edit = "display:";
	}
}

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
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Microlabs</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
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
        <div class="pageTitle">Documents Upload</div>
        <div class="right"></div>
    </div>

<form action="save_acknowledge.php" method="POST" enctype="multipart/form-data" id="form1" novalidate>
<input type="hidden" name="action" id="action" value="save_acknowledge">
<input type="hidden" name="module" id="module" value="CRM">
<input type="hidden" name="rid" id="rid" value="<?php echo $rid;?>">
<input type="hidden" name="rid" id="pid" value="<?php echo $pid;?>">
<input type="hidden" name="userid" id="userid" value="<?php echo $USER_ID;?>">

       <?php /* <div class="section full mt-7">
            <div class="wide-block pt-2 pb-2">

                <div class="row">
                    <div class="col-2">
                        <a href="naf_form.php"><div class="tabBox">NAF Form</div></a>
                    </div>
                    <div class="col-2">
						<a href="hcp_form.php"><div class="tabBox">HCP Information Form</div></a>
                    </div>
                    <div class="col-2">
                        <a href="hcp_agreement.php"><div class="tabBox">HCP Agreement</div></a>
                    </div>
                    <div class="col-2">
                        <a href="questionnaire.php"><div class="tabBox">Questionnaire</div></a>
                    </div>
                    <div class="col-2">
                        <a href="document_upload.php"><div class="tabBox">Documents upload</div></a>
                    </div>
                    <div class="col-2">
                        <a href="generate_pdf.php"><div class="tabBox">Generate PDF</div></a>
                    </div>
                </div>

            </div>
        </div> */
		include '_tabs.php';
		?>
	

  

    <div id="appCapsule">


        <div class="tab-content mt-1">


						
		<div class="section mt-2">
            <div class="card">
                <div class="card-body">
				
			<!--	<div class="wide-block pt-2 pb-2">
	
				<a href="pdf_pma.php?rid=<?php echo $rid;?>&userid=<?php echo $USER_ID;?>&pid=<?php echo $pid;?>" target="_blank" rel="noopener">Click here to download PDF file</a>
                <!--p class="blue-text"><b><u>Click here to download PDF file</u></b></p-->

				<!--</div>-->
				
				
				<div class="wide-block pt-2 pb-2">

                <p><b>E-Signature of Doctor:</b></p>

					
							<div class="col-6">
                   
				   
									<center>
										<div class="custom-signature-upload" style="<?php echo $sign_edit;?>">
											<div class="wrapper">
											   <canvas id="signature-pad1"></canvas>
											   <textarea id="signature64" name="signed" style="display: none"></textarea>
										   </div>
										   <div class="clear-btn">
											   <button id="clear_sign1"><span> Clear </span></button>
										   </div>
										</div>
										
										<div class="custom-signature-upload" style="<?php echo $sign_display;?>">
											<div class="wrapper1">											   
											   <img src="<?php echo $e_sign_doctor;?>" width="95%" height="95%" alt="Italian Trulli">											   
										   </div>										   
										</div>
										
									</center>
									<br>
						   
							</div>
				</div>
				
				
				<!--div class="wide-block pt-2 pb-2">

                <p><b>Upload Image of a Honararium cheque here:<span style="color:#ff0000">*</span></b></p>

					
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
				</div-->
				
				
                </div>
            </div>
        </div>
						
						

		
		<?php if($MODE != 'R'){ ?>
			<div class="section full mt-2">
				<div class="wide-block pt-2 pb-2">

					<div class="row">
						
						<div class="col">
							<a href="#"><button type="submit" class="exampleBox btn btn-primary rounded me-1" onclick="validate();">Submit</button></a>
						</div>
						
					</div>

				</div>
			</div>
		<?php } ?>
		
		
		
        



        </div>



    </div>
</form>

 

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
       
       var canvas1 = document.getElementById("signature-pad1");

       function resizeCanvas() {
           var ratio = Math.max(window.devicePixelRatio || 1, 1);
           
		   canvas1.width = canvas1.offsetWidth * ratio;
           canvas1.height = canvas1.offsetHeight * ratio;
           canvas1.getContext("2d").scale(ratio, ratio);
       }
       window.onresize = resizeCanvas;
       resizeCanvas();

       	   
	   var signaturePad1 = new SignaturePad(canvas1, {
        backgroundColor: 'rgb(250,250,250)'
       });

       document.getElementById("clear_sign1").addEventListener('click', function(){
        signaturePad1.clear();
		$("#signature64").val('');
       })
	   
	   
	   //var sig = $('#signature-pad1').signature({syncField: '#signature64', syncFormat: 'PNG'});
	   
	   function validate(){
			var canvas1 = document.getElementById("signature-pad1");			
			var data1 = canvas1.toDataURL('image/png');				
			document.getElementById('signature64').value = data1;
			
			var rid = document.getElementById("rid").value;
			var userid = document.getElementById("userid").value;
			var signatureurl = document.getElementById("signature64").value;
			
			
		/* 	$.ajax({
					url: 'save_acknowledge.php',
					type: 'POST',
					data: { rid: rid, userid : userid, signatureurl : signatureurl} ,
					contentType: 'application/json; charset=utf-8',
					success: function (response) {
						alert(response);
					},
					error: function () {
						alert("error");
					}
				});  */	

$.ajax({
    url: 'save_acknowledge.php',
    type: 'POST',
    data: jQuery.param({ rid: rid, userid : userid, signatureurl : signatureurl}) ,
    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    success: function (response) {
       // alert(response);
	   var result = response.split('||');
		window.location.href = 'index_pma.php?rid='+result[0]+'&userid='+result[1];
    },
    error: function () {
        alert("error");
    }
}); 
				
	   }
   </script>

</body>

</html>