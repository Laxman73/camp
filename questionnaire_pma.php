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


       <div class="section full mt-7">
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
        </div>
	


    <div id="appCapsule">


        <div class="tab-content mt-1">


						
		<div class="section mt-2">
		
		
		    <div class="card">
            <div class="card-body">
			<div class="wide-block pt-2 pb-2 text-center">
		
				<h2 style="color:red;"><b>Note:Would you like to send the questionnaire survey to the doctor as a SMS link?</b></h2>
		
		
		



        <div class="tab-content ">


          
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">

                <div class="section full mt-1">
                   
                    <div class="wide-block pt-2 pb-2">

                        <ul class="nav nav-tabs style1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#Yes" role="tab">
                                    Yes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#No" role="tab">
                                    No
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2">
                            <div class="tab-pane fade show " id="Yes" role="tabpanel">


							<div class="row mt-3" style="width: 100%;justify-content: center;">
								<div class="col1-2" style="padding: 7px 0px;">
									<b >Mobile Number:</b>
								</div>
												
								<div class="col1-3">
									<div class="input-wrapper">
										<input type="number" class="form-control" id="" placeholder="+918763872828" required="">
									</div>
								</div>
								
								<div class="col1-1">
										<ion-icon name="pencil-outline" style="    font-size: 30px;"></ion-icon>
								</div>
							</div>
							
							


							<div class="copy-text mt-3">
								<input type="text" class="text" value="https://www.questionnairesurevy/microlabs" />
								<button>Copy link <ion-icon name="copy" style="vertical-align: middle;"></ion-icon></button>
							</div>


							<button type="button" class="btn btn-primary btn-lg mr-1 mt-3 bg-green">Send SMS</button> 



                            </div>
							
							
							
						<div class="tab-pane fade text-left" id="No" role="tabpanel">




			<div class="wide-block pt-2 pb-2">

                <p><b>Which age group suffers from depression the most, based on your clinical practice?(Tick all that is applicable)</b></p>

				
				<div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio-1" name="customRadio1" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio-1">Children</label>
                </div>
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio-2" name="customRadio1" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio-2">Teenagers</label>
                </div>
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio-3" name="customRadio1" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio-3">Young Adults</label>
                </div>
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio-4" name="customRadio1" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio-4">Middle-aged</label>
                </div>
				<div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio-5" name="customRadio1" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio-5">Elderly</label>
                </div>

				</div>
				
				
				
				<div class="wide-block pt-2 pb-2">

                <p><b>Based on your clinical experience, which class of antidepressants work best on patients with depression?</b></p>

				
				<div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio1">SSRIs</label>
                </div>
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio2">SNRIs</label>
                </div>
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio3" name="customRadio" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio3">TCAs</label>
                </div>
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio4" name="customRadio" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio4">Any other, please specify</label>
                </div>

				</div>
				
				
				
				
				<div class="wide-block pt-2 pb-2">

                <p><b>Based on your clinical experience, what type of symptoms are most commonly shown by individuals with depression?</b></p>

				
				<div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio5" name="customRadio2" class="custom-control-input">
                    <label class="custom-control-label " for="customRadio5">Behavioural symptoms</label>
                </div>
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio6" name="customRadio2" class="custom-control-input">
                    <label class="custom-control-label " for="customRadio6">Physical symptoms</label>
                </div>
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio7" name="customRadio2" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio7">Cognitive symptoms</label>
                </div>
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" id="customRadio8" name="customRadio2" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio8">Any other, please specify</label>
                </div>

				</div>

				<div class="row mt-3" style="width: 100%;justify-content: center;">
					<button type="button" class="btn btn-primary btn-lg mr-1 mt-3 bg-green" onclick="buttonClicked()">Submit</button> 
				</div>



				<div class="row mt-3" id="hideDiv">
					
						<b>Verify Your Phone Number(+918763872828) <ion-icon name="pencil-outline" style="font-size: 20px;"></ion-icon> : <u class="blue-text">Click here for OTP</u></b>
					
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
                    <div class="col">
						<button type="button" class="exampleBox btn btn-primary rounded me-1" data-toggle="modal" data-target="#actionSheetContent">Save</button>
					</div>
                    <div class="col">
                        <a href="document_upload_pma.php"><button type="button" class="exampleBox btn btn-primary rounded me-1">Submit</button></a>
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
	
	
<script>
function buttonClicked() {
	$("#hideDiv").show();
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