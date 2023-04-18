<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/common.php";


$page_title = 'Request Letter';
$TITLE = SITE_NAME . ' | ' . $page_title;

$rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : '';
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
$display_all = (isset($_GET['display_all'])) ? $_GET['display_all'] : '0';

if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
	echo 'Invalid Access Detected!!!';
	exit;
}

// getting role and profile id of user
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $USER_ID . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");

?>
<!doctype html>
<html lang="en">

<head>
    <?php include 'load.header.php';?>

</head>

<body>


    <div class="appHeader bg-primary">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Camp Request letter</div>
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
            
                </div>

            </div>
        </div>-->
	
	


    <div id="appCapsule">


        <div class="tab-content mt-1">


						
		<div class="section mt-7">
            <div class="card">
                <div class="card-body">

                <div class="wide-block pt-2 pb-2">

						
							
							<div class="row">
							
								
							
								<div class="col-3">
									<b>Name of HCP:</b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" required>
									</div>
								</div>
							</div>
							
							<br>
							
							<div class="row">
							
								
							
								<div class="col-3">
									<b>Nature of the camp:</b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" required>
									</div>
								</div>
							</div>
							
							<br>
							
														
							<div class="row">
							
							
							
								<div class="col-3">
									<b>Proposed Camp Date:</b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" required>
									</div>
								</div>
								
							</div>



							<br>
							
							<div class="row">
							
							
							
								<div class="col-3">
									<b>Proposed Camp Location:</b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper"><input type="text" class="form-control" id="" required></div>
								</div>
								
							</div>
							
							<br>


							<div class="row">
							
							
								<div class="col-3">
									<b>Proposed Camp Duration:</b>
								</div>
								
								<div class="col-9">
									<div class="input-wrapper">
										<input type="text" class="form-control" id="" placeholder="" >
									</div>
								</div>
								
							</div>

				
		
		
		
				</div>	


  
                </div>
            </div>
        </div>
						
			
		<div class="section mt-7">
        <div class="card">
        <div class="card-body">

            <!-- <div class="wide-block pt-2 pb-2">

				<div class="row">
					<div class="col-3">
						HCP Sign  & Sign: ___________________
					</div>
				</div>
      
				<br><br>
  
				<div class="row">
					<div class="col-3">
						Dated: ___________________
					</div>
				</div>
							
			</div> -->


	        </div>
        </div>
		</div>
		</div>		



	<div class="section full mt-2">
  

		
		<div class="section full mt-2">
            <div class="wide-block pt-2 pb-2">

                <div class="row">
                    <div class="col"><button type="button" class="exampleBox btn btn-primary rounded me-1">Save</button>
                    </div>
                    <div class="col">
                        <a href="hcp_form_camp.php"><button type="button" class="exampleBox btn btn-primary rounded me-1">Submit</button></a>
                    </div>
                    <div class="col">
                        <a href="#"><button type="button" class="exampleBox btn btn-primary rounded me-1">Cancel</button></a>
                    </div>
                </div>

            </div>
        </div>
		
		
		
        


          



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


</body>

</html>