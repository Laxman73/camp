<!doctype html>
<?php
// include('modules/CRM/includes/common.php');
require_once('include/utils.php');
require_once('include/ComboUtil.php');
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

$rid=(isset($_GET['id']))?$_GET['id']:'';
$modequetsion_1=(isset($_GET['modequetsion']))?$_GET['modequetsion']:'';

$hide = "display:";$hide1 = "display:none";
$hide_option = "style='pointer-events: none;'";
if($modequetsion_1=='approval')
{
	$hide = "display:none";
	$hide1 = "";
}

// echo "hello";//exit;
/*$sql_question = "SELECT crm_request_main_id AS rid, userid, question, crm_questions_upload.id AS 'questionid', no_of_options, crm_question_type.name AS 'question_type' FROM crm_questions_upload LEFT OUTER JOIN crm_question_type ON crm_questions_upload.question_type_id = crm_question_type.id AND crm_question_type.deleted = 0
WHERE crm_questions_upload.deleted = 0 AND crm_questions_upload.crm_request_main_id = '".$rid."'
ORDER BY crm_questions_upload.question_type_id, questionid";*/
$sql_question = "select crm_request_main_id AS rid, question, crm_approval_questions_upload.id AS 'questionid', no_of_options, crm_question_type.name AS 'question_type' from crm_approval_questions_upload INNER JOIN crm_questionoire_approval cqa on cqa.id=crm_approval_questions_upload.crm_questionoire_approval_id 
LEFT OUTER JOIN crm_question_type ON crm_approval_questions_upload.type = crm_question_type.id AND crm_question_type.deleted = 0 WHERE crm_approval_questions_upload.deleted=0 and cqa.deleted=0 and cqa.authorise=1 and cqa.crm_request_main_id='".$rid."'
ORDER BY crm_approval_questions_upload.type, questionid;";
// $res_question = sql_query($sql_question, "");
$selectQryres=mysql_query($sql_question);



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
    <link rel="icon" type="image/png" href="modules/CRM/assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="modules/CRM/assets/img/icon/192x192.png">
    <link rel="stylesheet" href="modules/CRM/assets/css/style.css">
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
                    <div class="col-2">
                        <a href="questionnaire_pma.php"><div class="tabBox">Questionnaire</div></a>
                    </div>
                    <div class="col-2">
                        <a href="document_upload_pma.php"><div class="tabBox">Documents upload</div></a>
                    </div>
                    <div class="col-2">
                        <a href="generate_pdf_pma.php"><div class="tabBox">Generate PDF</div></a>
                    </div>
                </div>

            </div>
        </div>-->
	

 
<form action="index.php" method="POST" enctype="multipart/form-data" id="form1">
<input type="hidden" name="action" id="action" value="Crm_QuestionUpload">
<input type="hidden" name="module" id="module" value="CRM">
<input type="hidden" name="userid" id="userid" value="<?php echo $_GET['userid'];?>">
<input type="hidden" name="id" id="id" value="<?php echo $_GET['id'];?>">
<input type="hidden" name="modequetsion" id="modequetsion" value="<?php echo $modequetsion_1;?>">
    <div id="appCapsule">


        <div class="tab-content mt-7" style="<?php echo $hide;?>">


						
		<div class="section mt-2">
            <div class="card">
                <div class="card-body">
				
				<div class="wide-block pt-2 pb-2 text-center">

					<h2 style="color:#fe0000"><b>To send the Questionnaire kindly upload the question in xlsx or csv format.</b></h2>

					<p><a href="modules/CRM/CRMQuestionnireUploadSimpleFile.csv">Questionnaire Upload Sample File Link</a></p>
							<form>
								<div class="custom-file-upload" style="display: inline-flex;">
									<input type="file" id="fileuploadInput" name="file0" accept=".csv, .xlsx, " onchange="ValidateSize(this)">
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
						<p id="error_msg" style="color:red;font-weight:bold;margin-center:300px;width:100%;"></p>
				</div>
				
	                   

				        
				
				
                </div>
            </div>
        </div>
						
						

		
		
		<div class="section full mt-2">
            <div class="wide-block pt-2 pb-2">

                <div class="row" >
                    <div class="col" style="display:none;"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
                    </div>
                    <div class="col">
                        <button type="submit" id="submitbtn" class="exampleBox btn btn-primary rounded me-1" disabled>Submit</button>
                    </div>
                    <div class="col">
                        <a href="<?php echo $site_URL;?>/modules/CRM/index_PM.php?userid=<?php echo $_GET['userid'];?>"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
                    </div>
                </div>

            </div>
        </div>
		
		
		
        



        </div>
		
		<div class="tab-content " style="<?php echo $hide1;?>">


          
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">

                <div class="section full mt-1">
                   
                    <div class="wide-block pt-2 pb-2">

                        <!--ul class="nav nav-tabs style1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#Yes" role="tab" onclick="createShoutURL();">
                                    Yes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#No" role="tab">
                                    No
                                </a>
                            </li>
                        </ul-->
                        <div class="tab-content mt-2">
                            <div class="tab-pane fade show " id="Yes" role="tabpanel">



                            </div>
							
							
							
						<div class="text-left" role="tabpanel">




			

			<?php
				$html='';
				$num_question=0;
				for ($i=0; $o=mysql_fetch_object($selectQryres) ; $i+1) {					
					$rid=$o->rid;
					$userid=$o->userid;
					$question=$o->question;
					$qid=$o->questionid;
					$question_type=$o->question_type;
					$no_of_options=$o->no_of_options;
						
					$html.='<div class="wide-block pt-2 pb-2" '.$hide_option.'>';
					$html.='<p><b>'.$question.'</b></p>';
					$html.='<input type="hidden" id="question_id" name="question_id_'.$num_question.'" value="'.$qid.'">';
					$html.='<input type="hidden" id="question_type_'.$num_question.'" name="question_type_'.$num_question.'" value="'.$question_type.'">';
					
					$sql_option = "SELECT crm_approval_questions_upload.id AS 'option_id', option1,option2,option3,option4,option5 
						FROM crm_approval_questions_upload							
						WHERE crm_approval_questions_upload.id = '".$qid."'";
					$res_option = mysql_query($sql_option);
					$options = array();
					for($j=0;$j<mysql_num_rows($res_option);$j++)
					{
						$options[]= mysql_result($res_option,$j,'option1');
						$options[]= mysql_result($res_option,$j,'option2');
						$options[]= mysql_result($res_option,$j,'option3');
						$options[]=mysql_result($res_option,$j,'option4');
						$options[]= mysql_result($res_option,$j,'option5');
					}
					
					if($question_type=='Ranking'){
						$html.='<div class="custom-control custom-radio mb-1"><table>';
					}
					
					if($question_type!='Rating'){
						$option_num=0;
						for ($j=0; $j<count($options) ; $j++) {				
							$option_id=mysql_result($res_option,$j,'id');
							$option=$options[$j];
							if(!empty($option))
							{
								//$sequnce_id=$n->sequnce_id;
								
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

                            </div>
                        </div>

                    </div>
                </div>
				
				<div class="section full mt-2">
					<div class="wide-block pt-2 pb-2 text-center">
					
					<span class="btn btn-success mr-1 mb-1 pd-sr">
						<input type="radio" id="approve" onchange="addRemark();" name="choice" value="A">
						<label for="approve" style="margin-bottom:0px;">Approve</label><br>
					</span>
						
					<span class="btn btn-danger mr-1 mb-1 pd-sr">	
						<input type="radio" onchange="addRemark();" id="reject" name="choice" value="R" >
						<label for="reject" style="margin-bottom:0px;">Reject</label><br>
					</span>	
					
					<div class="remark">

					</div>
					
					<div class="section full mt-2">
						<button type="submit" class="btn">Update</button>
					</div>
					
					</div>
					
				
					
				</div>



    </div>

</form>
 

    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="modules/CRM/assets/js/lib/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="modules/CRM/assets/js/lib/popper.min.js"></script>
    <script src="modules/CRM/assets/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="modules/CRM/assets/js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- Base Js File -->
    <script src="modules/CRM/assets/js/base.js"></script>

	<script>
		function addRemark() {
			var choice = $('input[name="choice"]:checked').val();
			if (choice == 'R') {
				$('.remark').append('<div class="row Remark"><div class="col-3 mt-2"><b>Remark:<span style="color:#ff0000">*</span></b>	<div class="input-wrapper"><input type="text" value="" class="form-control" id="remark" name="remark" placeholder="" required=""></div></div></div>');

			} else {
				$('.Remark').empty();

			}


		}
		
function ValidateSize(input) {
			//console.log(input)
		document.getElementById('error_msg').innerHTML = '';
		$('#submitbtn').prop('disabled', false);
		 if (input.files && input.files[0]) {
		 let reader = new FileReader();
				reader.readAsBinaryString(input.files[0]);
		 reader.onload = function (e) {
		 //console.log(e);
		 obj_csv.size = e.total;
		 obj_csv.dataFile = e.target.result
		//console.log(obj_csv.dataFile)
		parseData(obj_csv.dataFile)
					
		 }
		 }
}
var obj_csv = {
			size:0,
			dataFile:[]
};
function parseData(data){
    let csvData = [];
    let lbreak = data.split("\n");
    lbreak.forEach(res => {
        csvData.push(res.split(","));
    });
    // console.table(csvData);
	// console.log(csvData[1]);
	//question,option1,option2,option3,option4,option5,type,no_of_options
	var header_array=['question', 'option1', 'option2', 'option3', 'option4', 'option5', 'type', 'no_of_options\r'];
	var is_same = (csvData[0].length == header_array.length) && csvData[0].every(function(element, index) {
    return element === header_array[index]; 
});
console.log("--"+is_same);
//to chk header of file
if(csvData[0].length!==header_array.length){
	document.getElementById('error_msg').innerHTML = 'File Header format is not correct';
	$('#fileuploadInput').val('');
	$('#submitbtn').prop('disabled', true);
	return false;
}

/*comapare header position*/
var csv_header=csvData[0];
console.log(csv_header);
if(csv_header.length==header_array.length)
{
    for (var i = 0;i< header_array.length; i++)
	{
		
        if (header_array[i] !== csv_header[i])
		{
			console.table(header_array[i]+"---"+csv_header[i]);
            document.getElementById('error_msg').innerHTML = 'File header invalid';
			$('#fileuploadInput').val(''); 
			$('#submitbtn').prop('disabled', true);
			return false;
			
        }
    }
	
}

//to chk whether file is empty or not
if(csvData[1]==''){
	
	document.getElementById('error_msg').innerHTML = 'File is empty';
	$('#fileuploadInput').val('');
	$('#submitbtn').prop('disabled', true);
	return false;
}
else{
	document.getElementById('error_msg').innerHTML = '';
	//$('#submitbtn').prop('disabled', true);
	return true;
}
}


 		</script>

</body>

</html>