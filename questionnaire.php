<!doctype html>
<?php 
$page_red='http://88.99.140.102/MicrolabReplicav3/modules/CRM/index_PM.php?userid='.$current_user->id;

include 'includes/common.php';
$rid=(isset($_GET['rid']))?$_GET['rid']:'';
$pid=(isset($_GET['pid']))?$_GET['pid']:'';
$USER_ID = (isset($_GET['userid']))?$_GET['userid']:'';
$mode = (isset($_GET['mode']))?$_GET['mode']:'';
$display_all = (isset($_GET['display_all']))?$_GET['display_all']:'0';
$shortcodemode = (isset($_GET['qutaccessshorturl']))?$_GET['qutaccessshorturl']:'';
//if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))|| $USER_ID!='2')
if(empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID)))
{
	echo 'Invalid Access Detected!!!';
	exit;
}
$hcpid=(isset($_GET['id']))?$_GET['id']:'';

// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$user_name = GetXFromYID("select user_name from users where id='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");

$sql_question = "SELECT crm_request_main_id AS rid, userid, question, crm_questions_upload.id AS 'questionid', no_of_options, crm_question_type.name AS 'question_type' FROM crm_questions_upload
LEFT OUTER JOIN crm_question_type ON crm_questions_upload.question_type_id = crm_question_type.id AND crm_question_type.deleted = 0
WHERE crm_questions_upload.deleted = 0 AND crm_questions_upload.crm_request_main_id = '".$rid."'
ORDER BY crm_questions_upload.question_type_id, questionid";
$res_question = sql_query($sql_question, "");


$check_questionnire = "select * from crm_questionoire_link_sharing inner join crm_link_sharing_flag on crm_link_sharing_flag.crm_questionoire_link_sharing_id=crm_questionoire_link_sharing.id where crm_naf_main_id='".$rid."' and pid='".$pid."' and crm_link_sharing_flag.deleted=0 and crm_questionoire_link_sharing.deleted=0";
$check_quest_upload=GetXFromYID($check_questionnire);

$sql_question = "SELECT * FROM crm_questionnaire where crm_questionnaire.crm_request_main_id='".$pid."' and deleted=0";
$check_quest_sub=GetXFromYID($sql_question);

$sql_mobilenum = "SELECT mobile FROM crm_request_main inner join crm_request_details on crm_request_details.crm_request_main_id=crm_request_main.id where crm_request_main.id='".$pid."' and crm_request_main.deleted=0 and crm_request_details.deleted=0";
$check_mobilenum=GetXFromYID($sql_mobilenum);

$hide="style='display:';";
$submithide="style='display:none';";
$questhide="";
if($check_quest_upload!="" && $shortcodemode==1)
{
	$hide="style='display:none';";
	//$submithide="style='display:';";
	?>
	<script>
	window.onload = function(){
			document.getElementById('nooption').click();
	}
	</script>
	<?
}
$hidequest = "";
$hidemsg = "";
if($check_quest_upload!="" && $shortcodemode=='' && $check_quest_sub=="")
{
	$hidequest = "style='display:none';";
	$hidemsg = "Questionnire Survey Link has been shared with the Doctor Through SMS";
}
else if($check_quest_sub!="")
{
	header('location: questionnaire_view.php?qutaccessshorturl='.$shortcodemode.'&userid='.$USER_ID.'&rid='.$rid.'&pid='.$pid);
}

?>
<html lang="en">
<style>
        .rating {			
            margin-top: 5%;
            border: 1px #999 solid;
            display: flex;
            font-size:0;
            justify-content: center;
            align-items: center;
            flex-direction: row-reverse;
            background: linear-gradient(to right, #f00 , #ff0, #0f0)
        }
        .rating input {
            display: none
        }
        .rating label {
            display: flex;
            width: 100%;
            height: 40px;
            font-size: 15px;
            justify-content: center;
            align-items: center;
            background: #fff;
            color: #000;
          cursor: pointer;
        }
        .rating label {
            border-left: 1px #999 solid;
            transition: .3s;
			margin-bottom: 0;
        }
        .rating input[type='radio']:hover~label, .rating input[type='radio']:checked~label{
            background: none;
            color: #fff;
        }


#div1, #div2 {
  float: left;
  width: 100px;
  height: 35px;
  margin: 10px;
  padding: 10px;
  border: 1px solid black;
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
        <div class="pageTitle">Questionnaire</div>
        <div class="right"></div>
    </div>


       <?php /*<div class="section full mt-7">
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
                    <div class="col-2">
                        <a href="questionnaire_pma.php"><div class="tabBox">Questionnaire</div></a>
                    </div>
                    </div>
                </div>

				

            </div>
        </div> */
		if($shortcodemode=='')
		{
		include '_tabs.php';} ?>
	

<form action="save_questionnire.php" method="POST" enctype="multipart/form-data" id="form1" novalidate>
<input type="hidden" name="action" id="action" value="save_questionnire">
<input type="hidden" name="module" id="module" value="CRM">
<input type="hidden" name="rid" id="rid" value="<?php echo $rid;?>">
<input type="hidden" name="pid" id="pid" value="<?php echo $pid;?>">
<input type="hidden" name="userid" id="userid" value="<?php echo $USER_ID;?>">
<input type="hidden" name="user_name" id="user_name" value="<?php echo $user_name;?>">
<input type="hidden" name="shortcodemode" id="shortcodemode" value="<?php echo $shortcodemode;?>">
<input type="hidden" name="shortcodeID" id="shortcodeID" value="0">																   

    <div id="appCapsule">


        <div class="tab-content mt-1">

			<div class="wide-block pt-2 pb-2 text-center"><h2 style="color:red;"><b><?php echo $hidemsg;?></b></h2></div>
						
		<div class="section mt-2" <?php echo $hidequest;?>>
		
		
		    <div class="card">
            <div class="card-body">
			<div class="wide-block pt-2 pb-2 text-center">
		
				<h2 style="color:red;"><b <?php echo $hide;?>>Note:Would you like to send the questionnaire survey to the doctor as a SMS link?</b></h2>
		
		
		



        <div class="tab-content ">


          
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">

                <div class="section full mt-1">
                   
                    <div class="wide-block pt-2 pb-2">

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
										<input type="number" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="+918763872828" required="" value="<?php echo $check_mobilenum;?>">
									</div>
								</div>
								
								<div class="col1-1">
										<ion-icon name="pencil-outline" style="    font-size: 30px;"></ion-icon>
								</div>
							</div>
							
							


							<div class="copy-text mt-3">
								<input type="text" class="text" value="" id="shorturl" readonly/>
								<button type="button" onclick="fn_copyLink()">Copy Link <ion-icon name="copy" style="vertical-align: middle;"></ion-icon></button>								
							</div>


							<button type="button" class="btn btn-primary btn-lg mr-1 mt-3 bg-green" onclick="saveconsent();">Send SMS</button> 



                            </div>
							
							
							
						<div class="tab-pane fade text-left" id="No" role="tabpanel">




			

			<?php
				$html='';
				$num_question=0;
				for ($i=0; $o=sql_fetch_object($res_question) ; $i+1) {					
					$rid=$o->rid;
					$userid=$o->userid;
					$question=$o->question;
					$qid=$o->questionid;
					$question_type=$o->question_type;
					$no_of_options=$o->no_of_options;
						
					$html.='<div class="wide-block pt-2 pb-2">';
					$html.='<p><b>'.$question.'</b></p>';
					$html.='<input type="hidden" id="question_id" name="question_id_'.$num_question.'" value="'.$qid.'">';
					$html.='<input type="hidden" id="question_type_'.$num_question.'" name="question_type_'.$num_question.'" value="'.$question_type.'">';
					
					$sql_option = "SELECT crm_questions_options.id AS 'option_id', crm_questions_options.options, crm_questions_options.sequnce_id 
						FROM crm_questions_options							
						WHERE crm_questions_options.deleted = 0 AND crm_questions_options.question_id = '".$qid."'
						ORDER BY sequnce_id";
					$res_option = sql_query($sql_option, "");
					
					if($question_type=='Ranking'){
						$html.='<div class="custom-control custom-radio mb-1"><table>';
					}
					
					if($question_type!='Rating'){
						$option_num=0;
						for ($j=0; $n=sql_fetch_object($res_option) ; $j+1) {				
							$option_id=$n->option_id;
							$option=$n->options;
							$sequnce_id=$n->sequnce_id;
							
							if($question_type=='Single'){
								$html.='<div class="custom-control custom-radio mb-1">
									<input type="radio" id="option_'.$num_question.'_'.$option_num.'" name="option_'.$num_question.'" class="custom-control-input" value="'.$option_id.'">
									<label class="custom-control-label" for="option_'.$num_question.'_'.$option_num.'">'.$option.'</label>
								</div>';
							}
							if($question_type=='Multiple'){
								$html.='<div class="custom-control custom-radio mb-1">
									<input type="checkbox" id="option_'.$num_question.'_'.$option_num.'" name="option_'.$num_question.'_'.$option_num.'" class="custom-control-input" value="'.$option_id.'">
									<label class="custom-control-label" for="option_'.$num_question.'_'.$option_num.'">'.$option.'</label>
								</div>';								
							}
							if($question_type=='Ranking'){
								 $html.='<tr>
									<td><label>'.$option.'</label></td>
									<td>
										<input type="hidden" id="rank_'.$num_question.'_'.$option_num.'" name="rank_'.$num_question.'_'.$option_num.'" value="">
										<input type="hidden" id="sel_option_'.$num_question.'_'.$option_num.'" name="sel_option_'.$num_question.'_'.$option_num.'" value="'.$option_id.'">
										<select id="option_'.$num_question.'_'.$option_num.'" name="option_'.$num_question.'_'.$option_num.'" onchange="fn_rank_option(this.value,'.$num_question.','.$option_num.');">>
											<option></option>
											<option value=1>1</option>
											<option value=2>2</option>
											<option value=3>3</option>
											<option value=4>4</option>						
										</select>
									</td>
								</tr>';	 
							}
							$option_num=$option_num+1;
						}
						$html.='<input type="hidden" id="option_num_'.$num_question.'" name="option_num_'.$num_question.'" value="'.$option_num.'">';	
					}
					else{
						$html.='<div class="rating">
							<input type="radio" name="option_'.$num_question.'" id="option_'.$num_question.'_10" value="10"><label for="option_'.$num_question.'_10">10</label>
							<input type="radio" name="option_'.$num_question.'" id="option_'.$num_question.'_9" value="9"><label for="option_'.$num_question.'_9">9</label>
							<input type="radio" name="option_'.$num_question.'" id="option_'.$num_question.'_8" value="8"><label for="option_'.$num_question.'_8">8</label>
							<input type="radio" name="option_'.$num_question.'" id="option_'.$num_question.'_7" value="7"><label for="option_'.$num_question.'_7">7</label>
							<input type="radio" name="option_'.$num_question.'" id="option_'.$num_question.'_6" value="6"><label for="option_'.$num_question.'_6">6</label>
							<input type="radio" name="option_'.$num_question.'" id="option_'.$num_question.'_5" value="5"><label for="option_'.$num_question.'_5">5</label>
							<input type="radio" name="option_'.$num_question.'" id="option_'.$num_question.'_4" value="4"><label for="option_'.$num_question.'_4">4</label>
							<input type="radio" name="option_'.$num_question.'" id="option_'.$num_question.'_3" value="3"><label for="option_'.$num_question.'_3">3</label>
							<input type="radio" name="option_'.$num_question.'" id="option_'.$num_question.'_2" value="2"><label for="option_'.$num_question.'_2">2</label>
							<input type="radio" name="option_'.$num_question.'" id="option_'.$num_question.'_1" value="1"><label for="option_'.$num_question.'_1">1</label>    
						</div>';
					}
					
					if($question_type=='Ranking'){
						$html.='</table></div>';
					}
					
					$html.='</div>';	
					$num_question=$num_question+1;
				}
				$html.='<input type="hidden" id="num_question" name="num_question" value="'.$num_question.'">';	
				echo $html;
			?>
					
				

				</div>

							<div class="row mt-3" id="nextBtn_div" style="width: 100%;justify-content: center;display:none;">
								<button type="button" class="btn btn-primary btn-lg mr-1 mt-3 bg-green" onclick="buttonClicked()">Next</button> 
							</div>



							<div class="row mt-3" id="hideDiv" style="display:none;">
								<input type="hidden" id="questionID" name="questionID" value="1">	
									
								<label for="otpmobilenumber" class="col-sm-3 col-form-label"><b>Verify Your Phone Number</b></label>
								<div class="col-sm-3">									
									<input type="number" class="form-control" id="otpmobilenumber" name="otpmobilenumber" placeholder="" required=""> 
									
								</div>
								<div class="col-sm-3">
									<button type="button" class="btn btn-primary" onclick="createOTP()">Generate OTP</button>									
								</div>
							</div>
				
							<div class="row mt-3" id="enterOtp_div" style="display:none;">																
								<label for="enter_otp" class="col-sm-3 col-form-label"><b>Enter OTP</b></label>
								<div class="col-sm-3">									
									<input type="number" class="form-control" id="enter_otp" name="enter_otp" placeholder="" required=""> 
									
								</div>
								<div class="col-sm-3">
									<button type="button" class="btn btn-primary" onclick="validateOTP()">Confirm</button>									
								</div>
							</div>
                            </div>
                        </div>

                    </div>
                </div>

             



            
		
		
		
		
		        <!--<div class="row mt-3" style="width: 100%;justify-content: center;">
				
                    <div class="col1-2">
                         <button type="button" class="btn btn-primary btn-lg mr-1"><b>&nbsp;&nbsp;Yes&nbsp;&nbsp;</b></button> 
                    </div>
                    <div class="col1-3">
						 <button type="button" class="btn btn-primary btn-lg mr-1" disabled><b>&nbsp;&nbsp;No&nbsp;&nbsp;</b></button> 
                    </div>
                 
                </div>-->
		
	
		
		    </div>
		    </div>
         
		

				
				
				
				
				
				
				
            
        </div>
						
						

			 
		
		<div class="section full mt-2">
            <div class="wide-block pt-2 pb-2">

                <div class="row">
                    <div class="col" style="display:none;">
						<button type="button" class="exampleBox btn btn-primary rounded me-1" data-toggle="modal" data-target="#actionSheetContent">Save</button>
					</div>
                    <div class="col" <?php echo $submithide;?> id="submitbutton">
                        <!--a href="document_upload_pma.php"><button type="button" class="exampleBox btn btn-primary rounded me-1" onclick="buttonClicked()">Submit</button></a-->					
                        <button type="submit" class="exampleBox btn btn-primary rounded me-1">Submit</button>					
                    </div>
                    <div class="col">
                        <a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
                    </div>
                </div>

            </div>
        </div>
		
		

        <div class="modal fade action-sheet" id="actionSheetContent" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="action-sheet-content text-center">
                            <p>
                                Questionnaire sent to Medical Head approval.
                            </p>
                            <a href="#" class="btn btn-primary" data-dismiss="modal">OK</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        </div>



    </div>
	
</form>
<script>
function buttonClicked() {
	
	var num_question = $("#num_question").val();
	
	var error=0;
	
	for(var i=0;i<num_question;i++)
	{
		var question_type = $("#question_type_"+i).val();
		
		if(question_type=='Multiple' || question_type=='Ranking'){
			var option_num = $("#option_num_"+i).val();
			
			var err = 1;
			for(var j=0;j<option_num;j++)
			{
				var option_multiple = $("#option_"+i+"_"+j);
				
				if(question_type=='Multiple'){					
					if(option_multiple.prop('checked') == true){
						err = 0;
					}					
				}				
						
				if(option_multiple.val()=='' || typeof option_multiple.val() === 'undefined'){
					error=1;
				}						
			}
			
			if(question_type=='Multiple' && err == 1){
				error=1;
			}
		}
		else{	
			var option_num = $("#option_num_"+i).val();
			var option = $("input[type='radio'][name='option_"+i+"']:checked").val();
			
			if(option=='' || typeof option === 'undefined'){
				error=1;
			}			
		}	
	}
	
	if(error==1){
		alert("Kindly complete the questionnaire..");
		$("#hideDiv").hide();
		return false;
	}
	else{
		$("#hideDiv").show();
	}	
}

function buttonClickedNo() {
	//$("#submitbutton").show();
	$("#nextBtn_div").show();
	
	//alert("Rating : "+$("input[name='question_rating']:checked").val());
}
function buttonClickedproceed(rid) {		
	var num_question = document.getElementById('num_question').value;
	
}
</script>	
	
<script>
    let copyText = document.querySelector(".copy-text");
copyText.querySelector("button").addEventListener("click", function () {
	let input = copyText.querySelector("input.text");
	input.select();
	document.execCommand("copy");
	copyText.classList.add("active");
	window.getSelection().removeAllRanges();
	setTimeout(function () {
		copyText.classList.remove("active");
	}, 2500);
});
 
	function saveconsent()
   {
		var consent = 1;
		var wid = 1;
		var imagedata = '';
		var doc_mobile = document.getElementById('mobilenumber').value;
		var username = document.getElementById('user_name').value;
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
		
		jQuery.ajax({
									url: '../../index.php?module=Users&action=Authenticate',
									data: {
										user_name : '.'+username,
										user_password : 123456,
										view_questionnaire : 1,
										type : 1,
										naf_requestid : 1,
										shortcodeid : shortcode_ID,
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
		$("#nextBtn_div").css('display','none');
		jQuery.ajax({
									url: '../../index.php?module=Users&action=Authenticate',
									data: {
										user_name : '.'+username,
										user_password : 123456,
										view_questionnaire : 1,
										type : 2,
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
   
   function createOTP()
   {
	   
	   var questionid = $("#questionID").val(); 
	   var mobileOTPnum = $("#otpmobilenumber").val();
	   var username = $("#user_name").val();
	  
   
	  
		if(mobileOTPnum=='' || mobileOTPnum==0){
			//bootbox.dialog({message:'Please Enter Mobile Number', backdrop:false, onEscape:true});
			alert('Please Enter Mobile Number');			
			//bootbox.dialog({message:'Please Enter Mobile Number', title:'<font style="font-size: 16px;color:red;"><b>Warning:<b></font>', backdrop:false, onEscape:true});			
		}
		else{   
					jQuery.ajax({
									url: '../../index.php?module=Users&action=Authenticate',
									data: {
										user_name : '.'+username,
										user_password : 123456,
										view_questionnaire : 1,
										type : 4,
										question_requestid : questionid,
										docmobile : '+91'+mobileOTPnum,
										plannedDoctor: true
									},
									success: function(response) {
										result = JSON.parse(response);
										//$("#shorturl").val(result.shourtURL);
										//$("#shortcodeID").val(result.shourtURLId);
										if(result.status)
										{
											alert(result.message);
											//bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:green;"><b>Success:<b></font>', backdrop:false, onEscape:true});
											//location.reload();
											$("#enterOtp_div").show();
										}else{
											alert(result.message);
											//bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:red;"><b>Warning:<b></font>', backdrop:false, onEscape:true});
											//$("#btnsubmit").val('Submit');
											//$("#btnsubmit").prop('disabled', false);
										}
										
									}
								});
		}
   }
   
   function validateOTP()
   {
	   
	   var questionid = $("#questionID").val(); 
	   var enter_otp = $("#enter_otp").val();
	   var username = $("#user_name").val();
	  
		if(enter_otp=='' || enter_otp==0){
			//bootbox.dialog({message:'Please Enter Mobile Number', backdrop:false, onEscape:true});	
			alert('Please Enter Mobile Number');
			//bootbox.dialog({message:'Please Enter Mobile Number', title:'<font style="font-size: 16px;color:red;"><b>Warning:<b></font>', backdrop:false, onEscape:true});			
		}
		else{
			
			jQuery.ajax({
									url: '../../index.php?module=Users&action=Authenticate',
									data: {
										user_name : '.'+username,
										user_password : 123456,
										view_questionnaire : 1,
										type : 5,
										question_requestid : questionid,
										mobileOTP: enter_otp,
										plannedDoctor: true
									},
									success: function(response) {
										result = JSON.parse(response);
										
										if(result.status)
										{
											alert(result.message);
											$("#submitbutton").show();
											//bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:green;"><b>Success:<b></font>', backdrop:false, onEscape:true});										
										}else{
											alert(result.message);
											//bootbox.dialog({message:result.message, title:'<font style="font-size: 16px;color:red;"><b>Warning:Invalid OTP<b></font>', backdrop:false, onEscape:true});											
										}
										
									}
								});
		}
   }
   
/*    $("#rank_option").on("change",function(){
    $(this).find("option").show();
    $("option:selected", $(this)).hide();
}); */



function fn_copyLink() {  
	var shorturl = document.getElementById("shorturl");

	  // Select the text field
	  shorturl.select();
	  shorturl.setSelectionRange(0, 99999); // For mobile devices

	  // Copy the text inside the text field
	  navigator.clipboard.writeText(shorturl.value);
}
function fn_rank_option(id,qid,option_id) {  
	//var x = $('#option_'+qid+'_'+option_id+'_'+cnt+':selected').text();
	
	var num = $("#option_num_"+qid).val();	
	var selectobject1=document.getElementById("option_"+qid+"_"+option_id+"");
	var rank=document.getElementById("rank_"+qid+"_"+option_id+"").value;
	
	for(var k=0;k<num;k++){		
		var selectobject2=document.getElementById("option_"+qid+"_"+k+"");		
		for(var m=0;m<=num;m++){			
			if(k!=option_id){				
				if (selectobject2.options[m].value == id ){
					$('#option_'+qid+'_'+k+' option[value="'+id+'"]').attr("disabled", true);
				}
				if (selectobject2.options[m].value == "" ){
					$('#option_'+qid+'_'+k+' option[value="'+rank+'"]').attr("disabled", false);
				}
			}
		}					
	}
	
	document.getElementById("rank_"+qid+"_"+option_id+"").value=id;
}
	


</script>
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


</body>

</html>