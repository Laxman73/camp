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
	
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
	<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">
	
</head>

<body>


    <div class="appHeader bg-primary">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Post Activity & Comment</div>
        <div class="right"></div>
    </div>


       <div class="section full mt-7">
            <div class="wide-block pt-2 pb-2">

                <div class="row">
				    <div class="col-2">
                        <a href="naf_form_pma.php"><div class="tabBox">NAF FORM</div></a>
                    </div>
                    <div class="col-2">
                        <a href="service_form_pma.php"><div class="tabBox">DELIVERY OF SERVICE FORM</div></a>
                    </div>
					<div class="col-2">
                        <a href="#"><div class="tabBox">POST ACTIVITY & COMMENT</div></a>
                    </div>
                    <!--<div class="col-2">
						<a href="hcp_form.html"><div class="tabBox">HCP Information Form</div></a>
                    </div>
                    <div class="col-2">
                        <a href="hcp_agreement.html"><div class="tabBox">HCP Agreement</div></a>
                    </div>
                    <div class="col-2">
                        <a href="questionnaire.html"><div class="tabBox">Questionnaire</div></a>
                    </div>
                    <div class="col-2">
                        <a href="document_upload.html"><div class="tabBox">Documents upload</div></a>
                    </div>
                    <div class="col-2">
                        <a href="generate_pdf.html"><div class="tabBox">Generate PDF</div></a>
                    </div>-->
                </div>

            </div>
        </div>
	

    <div id="appCapsule">


        <div class="tab-content mt-1">


						
		<div class="section mt-2">
            <div class="card">
                <div class="card-body">
  
				
				<form>
             

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="">Enter Post Activity Outcome Comments Here<span style="color:#ff0000">*</span></label>
                            <textarea id="" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
					
					
					
                </form>		
							
							

  
                </div>
            </div>
        </div>
						

	
            <div class=" pt-2 pb-2" style="width: 100%;justify-content: center;text-align: center;">

            <a href="index_pma.php"><button type="button" class="btn btn-primary rounded me-1 "><b>&nbsp;&nbsp;Submit&nbsp;&nbsp;</b></button></a>
                   

            </div>
        
		
		
		   
        

 

        </div>



    </div>


 

    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
	<script>
	jQuery('#targetedSpeciality').multiselect({
		columns: 1,
		placeholder: 'Choose...',
		search: true
	});
	</script>
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