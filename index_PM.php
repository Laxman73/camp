<?php
include 'includes/common.php';
include "sql.inc.php";
$USER_ID = (isset($_GET['userid'])) ? $_GET['userid'] : '';
if (empty($USER_ID) || (!empty($USER_ID) && !is_numeric($USER_ID))) {
  echo 'Invalid Access Detected!!!';
  exit;
}

$where = $having = $fdate = $tdate = $event_id = $status_id = $pending_with = $category = $txtDfrom = $txtDto = '';

$current_userid = $USER_ID;
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $current_userid . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");
$_q="select * from crm_naf_main where deleted=0 order by submitted_on DESC";

	if (isset($_POST['fdate'])) $fdate = $_POST['fdate'];
	if (isset($_POST['tdate'])) $tdate = $_POST['tdate'];
	if (isset($_POST['event_id'])) $event_id = $_POST['event_id'];
	if (isset($_POST['status_id'])) $status_id = $_POST['status_id'];
	if (isset($_POST['pending_with'])) $pending_with = $_POST['pending_with'];
	if (isset($_POST['category'])) $category = $_POST['category'];
	

	if ($PROFILE_ID!='11' && $PROFILE_ID!='7'  && $PROFILE_ID!='6' ) {
		// $where.= " AND (userid='" . $current_userid . "' OR crm_naf_main.pendingwithid = '" . $current_userid . "')";
		$where.= " AND (crm_naf_main.userid='" . $current_userid . "' OR crm_naf_main.pendingwithid = '" . $current_userid . "' OR cqa.pendingwithid = '" . $current_userid . "')";
	}
	
	if ($PROFILE_ID=='7' || $PROFILE_ID=='6' ) {		
		$where.= " AND ( crm_naf_type_master.parent_id != 0 OR crm_naf_main.userid='" . $current_userid . "')";
	}
	
	if($fdate!='' || $tdate!=''){
		$where.= " AND crm_naf_main.eventdate BETWEEN '".$fdate."' AND '".$tdate."'";
	}
	
	if($event_id!=''){
		$where.= " AND crm_naf_main.naf_no LIKE '%".$event_id."%'";
	}
	
	if($status_id!=''){
		$where.= " AND crm_naf_main.authorise = ".$status_id."";
	}
	
	if($pending_with!=''){
		$having = " HAVING pending_with LIKE '%".$pending_with."%'";
	}
	if($category!=''){
		$having = " HAVING category LIKE '%".$category."%'";
	}
	
	$inner_join = "LEFT OUTER JOIN crm_questionoire_approval cqa on cqa.crm_request_main_id = crm_naf_main.id and cqa.deleted=0 and cqa.authorise <> 4/*and cqa.authorise <> 3*/";
	$_q="select crm_naf_main.id, crm_naf_main.naf_no, crm_naf_main.eventdate, division.name AS 'div_name', crm_naf_main.category_id, crm_naf_type_master.type_name as category, crm_naf_type_master.detailview_filename, crm_naf_type_master.approve_filename, '40000' AS estimated_cost, 'Approved' as status, IF(CONCAT_WS(' ', u1.first_name, u1.last_name) IS NULL or CONCAT_WS(' ', u1.first_name, u1.last_name) = '', ' ', CONCAT_WS(' ', u1.first_name, u1.last_name,'(',`profile`.profilename,')'))AS pending_with, CONCAT_WS(' ', u2.first_name, u2.last_name) AS approved_b, CONCAT_WS(' ', u3.first_name, u3.last_name) AS pendingwithquestionnire, if(crm_naf_main.authorise=1,'Pending', if(crm_naf_main.authorise=2,'Accepted', if(crm_naf_main.authorise=3,'Approved', if(crm_naf_main.authorise=4,'Rejected','')))) AS approval_status, crm_naf_main.authorise,crm_naf_main.pendingwithid,csm.status_name as question_status,cqa.authorise as quest_auth,crm_naf_main.level,crm_naf_main.budget_amount  from `crm_naf_main`  
	INNER JOIN users ON crm_naf_main.userid = users.id
	INNER JOIN division ON users.division = division.divisionid
	LEFT OUTER JOIN crm_naf_type_master on crm_naf_main.category_id=crm_naf_type_master.pid and crm_naf_main.deleted=0
	LEFT OUTER JOIN users u1 ON crm_naf_main.pendingwithid = u1.id
	LEFT OUTER JOIN user2role ON user2role.userid=u1.id
	LEFT OUTER JOIN role2profile ON role2profile.roleid = user2role.roleid
	LEFT OUTER JOIN profile on `profile`.profileid=role2profile.profileid
	LEFT OUTER JOIN users u2 ON crm_naf_main.pendingwithid = u2.id	
	".$inner_join."
	LEFT OUTER JOIN users u3 ON cqa.pendingwithid = u3.id
	LEFT OUTER JOIN crm_status_master csm ON csm.pid = cqa.authorise
	where crm_naf_main.deleted=0 ".$where.$having." order by crm_naf_main.id DESC";
	$_r=sql_query($_q,'');
						

	$_sql="select type_name from crm_naf_type_master where request_type='naf_type'";
	$category_dropdown=sql_query($_sql,'');

	$_sql2="select pid as 'category', if(pid=8||pid=5, 'pma_search.php' , file_name) as file_name,type_name from crm_naf_type_master where request_type='naf_type'";
	$form_dropdown=sql_query($_sql2,'');


	$_sql2="select pid as 'category', if(pid=8||pid=5, 'pma_search.php' , file_name) as file_name,type_name from crm_naf_type_master where request_type='naf_type' AND (profile LIKE '% $PROFILE_ID' || profile LIKE '% $PROFILE_ID,%')";
	$form_dropdown=sql_query($_sql2,'');
?>

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
    <meta name="description" content="Expense Module">
    <meta name="keywords" content="bootstrap 5, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">


	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
	
	
	
</head>

<body>


    <div class="appHeader bg-primary">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline" class="icon-color"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Need Assessment Form</div>
        <div class="right">
			<a href="http://88.99.140.102/MicrolabReplicav3/index.php?action=index&module=Home">
				<ion-icon name="home-outline" class="icon-color"></ion-icon>
			</a>
		</div>
	</div>
	
    <div id="appCapsule">

	<div class="section mt-7">
		
		
		
        <div class="card">
        <div class="card-body">
		<input type="hidden" name="userid" id="userid" value="<?php echo $USER_ID;?>" />
		
		<form action="" method="POST">
  
			<div class="wide-block pt-2 pb-2">

							<div class="row">					
								
								
								<div class="col-4">
								
									<div style="display:flex;">
									
									<div class="block1">
										<b>Pending with User:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="pending_with" name="pending_with" placeholder="" minlength="4">
										</div>
									</div>
									
									</div>
									
								</div>
								
								<div class="col-4">
									<div style="display:flex;">
										
										<div class="block1">
											<b>Category:</b>
										</div>
										
										<div class="block2">
											<div class="input-wrapper">
												<select type="text" class="form-control" id="category" name="category" placeholder="" minlength="4">
												<option value=''></option> 
													<?php	for ($i=0; $o=sql_fetch_object($category_dropdown) ; $i+1) {?>
														<option> <?php echo $o->type_name;?> </option>	
													<?php	}	?>				
											
												</select>
											</div>
										</div>
										
										</div>
			   
		 
			   
								</div>
								
							</div>
							
							<br>
							

							<div class="row">
							
								<div class="col-4">
									<div style="display:flex;">
									
									<div class="block1">
										<b>Application From Date:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
											<input type="date" class="form-control" id="fdate" name="fdate" placeholder="" >
										</div>
									</div>
									
									</div>
								</div>
								
								<div class="col-4">
									<div style="display:flex;">
									
									<div class="block1">
										<b>Application To Date:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
											<input type="date" class="form-control" id="tdate" name="tdate" placeholder="" >
										</div>
									</div>
									
									</div>
								</div>							
								
								
							</div>
							
							<br>
							
							<div class="row">
							
						
								
								<div class="col-4">
								
									<div style="display:flex;">
									
									<div class="block1">
										<b>NAF Ref.No:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
											<input type="text" class="form-control" id="event_id" name="event_id" placeholder="" minlength="4">
										</div>
									</div>
									
									</div>
									
								</div>
								
								<div class="col-4">
								
																
									<div style="display:flex;">
									
									<div class="block1">
										<b>Status:</b>
									</div>
									
									<div class="block2">
										<div class="input-wrapper">
										<select class="form-control custom-select" id="status_id" name="status_id" >
											<option selected="" disabled="" value=""></option>
											<option value='1'>Pending</option>
											<option value="2">Accepted</option>											
											<option value="3">Approved</option>											
											<option value="4">Rejected</option>											
											<option value="5">Closed</option>											
										</select>
										</div>
									</div>
									
									</div>
									
									
								</div>
								
								<div class="col-4">
								</div>
								
							</div>
							
							<div class="section full mt-2"><a>		
									<div class="row" style="text-align: center;">									
										<div class="form-group">
											<input type="submit" class="btn btn-upload shadowed  mt-1 me-1 mb-1" id="psa_search" class="btn" value="SEARCH" onclick="return validate();"/> 
											<input type="button" class="btn btn-secondary" class="btn" onclick="clear_form(this.form);" value="CLEAR" />		
										</div>						
									</div>
								</div>	
								
							
		<div class="section full mt-2"><a>
            </a><div class="wide-block pt-2 pb-2"><a>

                <!--<div class="row">
                    <div class="col"><a href="naf_form.html"><button type="button" class="exampleBox btn btn-primary rounded me-1">Need Assessment Form</button></a>
                    </div>
                    <div class="col">
                        <a href="pma.html"><button type="button" class="exampleBox btn btn-primary rounded me-1">PMA</button></a>
                    </div>
                    <div class="col">
                        
                    </div>
                </div>-->

				<?php 
					if ($PROFILE_ID=='22' || $PROFILE_ID=='7' || $PROFILE_ID=='6' ) { ?>
						<div class="row">
							<div class="col col-f">
								<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="naf-btn custom-select" id="" placeholder="Need Assessment Form" >
									<option selected="" disabled="" value="">Need Assessment Form</option>
										<?php	for ($i=0; $o=sql_fetch_object($form_dropdown) ; $i+1) {?>
													<option value="<?php echo $o->file_name;?>?userid=<?php echo $USER_ID;?>&category=<?php echo $o->category;?>&typeid=<?php echo $o->category;?>"> <?php echo $o->type_name;?> </option>	
										<?php	}	?>
								</select>
							</div>
							
							<!-- <div class="col col-f">
								<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="naf-btn custom-select" id="" required="">
									<option selected="" disabled="" value="">Project Market Analysis</option>
									<option value="pma_search.php?userid=<?php echo $USER_ID;?>">PMA</option>
								</select>
							</div> -->
							
							<div class="col col-f">
							</div>
						</div>
						
			<?php }

				?>
				


            </div>
        </div>
		
  
  
            </div>
			</form>
        </div>
        </div>

		
		
        </div>
		
		
			<div class="section mt-7">
				<div class="card">         
				
					<div class="card-body pd-1">	
					
					
					
			<table id="example" class="display mt-2" style="width:100%">
				
				<thead>

					<tr>
						<th class="bg-purple"></th>
						<th class="bg-purple">Event ID</th>
						<th class="bg-purple">Date of NAF initiation</th>
						<th class="bg-purple">Division</th>
						<th class="bg-purple">Category</th>
						<th class="bg-purple">Total Estimated<br>Cost of the NAF(Rs)</th>
						<!-- <th class="bg-purple">Status</th> -->
						<th class="bg-purple">Pending with</th>
						<!--th class="bg-purple">Approved by</th-->					
						<th class="bg-purple">Status</th>
						<th class="bg-purple">Add Questionnaire</th>
						<!--th class="bg-purple">Pending with</th-->
						<!--th class="bg-purple">Edit/Delete</th-->
					</tr>
				</thead>
				<tbody id="nafLisview_body">
					<?php
					for ($i=0; $o=sql_fetch_object($_r) ; $i+1) {
						$naf_no=$o->naf_no;
						$x_id=$o->id;
						$event_date=$o->eventdate;
						$div_name=$o->div_name;
						$category=$o->category;
						$category_id=$o->category_id;
						$pending_with=$o->pending_with;
						$pendingwithquestionnire=$o->pendingwithquestionnire;
						$pending_with_id=$o->pendingwithid;
						$approved_status_id=$o->authorise;
						$quest_auth=$o->quest_auth;
						$approval_status=$o->approval_status;
						$question_status=$o->question_status;
						$detailview_filename=$o->detailview_filename;
						$approve_filename=$o->approve_filename;
						$level=$o->level;
						$x_total=GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$x_id' ");
						$approved=GetXFromYID("select sum(naf_expense) from crm_naf_cost_details where naf_request_id='$x_id' ");
						
						if($category_id == 7 || $category_id==2 )
						{
						$x_total=$o->budget_amount;	
						}
						
						$check_questionnire = "select * from crm_questions_upload where crm_request_main_id='".$x_id."' and deleted=0";
						$check_quest_upload=GetXFromYID($check_questionnire);
						// echo "<br>---****----".$check_quest_upload."--***--".$check_questionnire;
						
						$check_questionnire_app = "select pendingwithid from crm_questionoire_approval where crm_request_main_id='".$x_id."' and deleted=0 and crm_questionoire_approval.authorise = 1 order by id DESC";
						$pendingwithid_state=GetXFromYID($check_questionnire_app);
						
						$status=($o->authorise=='1')?'Pending':'Approve';
						$url='javascript:void(0)';
						
								$file_name = GetXFromYID("SELECT filename 
									FROM crm_workflow 
									WHERE level = '$level'
									AND typeid = '$category_id'
									AND `status` = '$approved_status_id'
									AND pending_with_id = '$pending_with_id'");
						
						$event_url='<a href="'.$file_name.'?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
						
					/* 	if ($PROFILE_ID=='15') {
							
							if ($approved_status_id == 2 && $current_userid==$pending_with_id && $level>=6)
							{
								if($category_id==6)
									$event_url='<a href="dsf_advisory_approval_form.php?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
								else if($category_id==7)
								$event_url='<a href="post_activity_quarter_consultancy.php?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
								else
									$event_url='<a href="post_activity.php?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
							}
							else
							{
							$url=$approve_filename.'?rid='.$x_id.'&userid='.$USER_ID;
							$event_url='<a href="'.$approve_filename.'?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
							}
						}
						else if ($PROFILE_ID=='11' && $approved_status_id == 2 && $current_userid==$pending_with_id ) {
				
										
											$event_url='<a href="document_upload_advisory.php?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
						}
						else if ($PROFILE_ID=='27' && $approved_status_id == 1 && $current_userid==$pending_with_id && $level==1) {
				
										
											$event_url='<a href="'.$approve_filename.'?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
						}
					   else if ($PROFILE_ID=='27' && $approved_status_id == 1 && $current_userid==$pending_with_id && $level==5) {
				
							
							if($category_id==6)
								$event_url='<a href="post_activity_advisory.php?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
							else if($category_id==7)
								$event_url='<a href="post_activity_quarter_consultancy.php?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
							else	
								$event_url='<a href="post_activity.php?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
						}
						else if(($current_userid==$pending_with_id) && ($approved_status_id==1 || $approved_status_id==2 )){
							
							$event_url='<a href="'.$approve_filename.'?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
						}
						else{
							
							if ($PROFILE_ID=='22') {
							    if($approved_status_id==3)
								{
								$event_url='<a href="document_upload_advisory.php?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
								}
								else if($level>=8 && $category_id==6)
								{
									$event_url='<a href="post_activity_advisory.php?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
								}
							}
							else{
								
								$event_url='<a href="'.$detailview_filename.'?rid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
							}
						} */
						
					/* 	if(($PROFILE_ID=='6' || $PROFILE_ID=='7') && $approved_status_id==2 && $level==4){
							$event_url='<a href="naf_form_consultancy.php?prid='.$x_id.'&userid='.$USER_ID.'&category='.$category_id.'">'.strtoupper($naf_no).'</a>';
						}
                    */
                       $approve_url=$approval_status;						
                      // $approve_url='<a href=''>'.$approval_status.'</a>';					
						// echo "<br>---".$approved_status_id."---**---". $event_url."--***--".$PROFILE_ID;
						$showstatus_quest = $question_status;
						if($quest_auth==1)
						{
							$showstatus_quest = $question_status.' For </i><i>Approval With '.$pendingwithquestionnire.'</i>';
						}
						if ($PROFILE_ID=='11' && $category_id==1) {
							
							//$questionnaire_link = '../../index.php?action=upload_questionnaire&module=CRM&id='.$x_id.'&userid='.$USER_ID;
							/*if($check_quest_upload!="")
							{
								$questionnaire_link = '<i>'.$question_status.'</i>';
								$status_quest='';
							}
							else
							{*/
						
								if($approved_status_id==2 && $quest_auth!=3){
									// echo "<br>---".$approved_status_id."---**---". $event_url;
									if($quest_auth==1)
									{
										$questionnaire_link = '<a href="../../index.php?action=upload_questionnaire_view&modequetsion=delete&module=CRM&id='.$x_id.'&userid='.$USER_ID.'"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>';
									}
									else
									{
										$questionnaire_link = '<a href="../../index.php?action=upload_questionnaire&modequetsion=upload&module=CRM&id='.$x_id.'&userid='.$USER_ID.'"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>';
									
									}
									$status_quest='<i>'.$showstatus_quest.'</i>';
								}
								else{
									// $questionnaire_link = '<i class="fa fa-plus-circle" aria-hidden="true"></i>';
									$questionnaire_link = '';
									$status_quest='<i>'.$showstatus_quest.'</i>';
								}
							//}
						}
						else{
								
								if($approved_status_id==2 && $pendingwithid_state==$USER_ID){
									$questionnaire_link = '<a href="../../index.php?action=upload_questionnaire&modequetsion=approval&module=CRM&id='.$x_id.'&userid='.$USER_ID.'"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>';
									$status_quest='<i>'.$question_status.' For </i><i>Approval With '.$pendingwithquestionnire.'</i>';
								}
								else{
									$questionnaire_link = '';
									$status_quest='<i>'.$showstatus_quest.'</i>';
								}
						}
						
						?>
 					<tr>
						<td></td>						
						<td><?php echo $event_url;?></td>			  
						<td><?php echo $event_date;?></td>			  
						<td><?php echo $div_name;?></td>
						<td><?php echo $category;?></td>						
						<td><?php echo $x_total;?></td>
						<td><?php echo $pending_with;?></td>	
<?
if ($approve_url=='Pending'){
	$color = "rgba(255,0,0,0.6)";
}else if($approve_url=='Accepted'){
	$color = "rgba(255,255,0,0.6)";
}else if($approve_url=='Approved'){
	$color = "rgba(0,255,0,0.6)";
}else if($approve_url=='Acknowledged '){
	$color = "rgba(0,0,255,0.6)";
}
?>						
						<!--td>BU Head</td-->
						<!--<td style="background:<
						?php echo $color;?>;color:black;"><a href='' style="color:black;"></a></td>						-->
						<td style="background:<?php echo $color;?>;color:black;" onclick = "showmodal('<?php echo $naf_no;?>','<?php echo $x_id;?>');"><?php echo $approve_url;?></td>						
						<td class="text-center"><?echo $questionnaire_link;echo $status_quest;?> </td>
						<!--td><button type="button" class="btn btn-upload shadowed  mt-1 me-1 mb-1">Edit/Delete</button></td-->
					
					</tr> 
						
						
				<?php	}

					?>
                   
					
			
	
				
				</tbody>
				</table>
					

					</div>
				</div>
			</div>
			

<div class="container">			

	  <div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id = "title"></h4>
        <button type="button" class="close" id = "close" data-bs-dismiss="container">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
          <table class="table table-striped">
    <thead>
      <tr>
        <th>Pending With</th>
        <th>Status</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody name="thead" id="thead">

    </tbody>
  </table>
  </div>

      <!-- Modal footer 
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>-->

    </div>
  </div>
</div>
</div>


    </div>


    <!-- ============== Js Files ==============  -->
    <!-- Bootstrap -->
    <script src="assets/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="assets/js/plugins/splide/splide.min.js"></script>
    <!-- ProgressBar js -->
    <script src="assets/js/plugins/progressbar-js/progressbar.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>
	
	
	<script>
		$(document).ready(function() {
		var table = $('#example').DataTable( {
			
			responsive: true
		} );
		} );
		
		function clear_form(form) {			
			for (j = 0; j < form.elements.length; j++) {
				if (form.elements[j].type == 'text' || form.elements[j].type == 'select-one') {
					form.elements[j].value = '';
				}	
			}
			$("input[type=date]").val("");			
		}
		
		function validate(){			
			var pending_with = document.getElementById("pending_with").value;
			var category = document.getElementById("category").value;
			var event_id = document.getElementById("event_id").value;
			var status_id = document.getElementById("status_id").value;
			var naf_from_date = document.getElementById("fdate").value;
			var userid = document.getElementById("userid").value;
			var naf_to_date = document.getElementById("tdate").value;
			var from_date = new Date(naf_from_date);
			var to_date = new Date(naf_to_date);			
			var error=0;
			
			if(naf_from_date== '' && naf_to_date=='' && pending_with=='' && event_id=='' && status_id=='' && category==''){
				alert("Kindly select atleast one search criteria");
				error=1;
				return false;
			}
			
			if(naf_from_date== '' && naf_to_date!=''){
				alert("Kindly select from date");
				error=1;
				return false;
			}
			
			if(naf_from_date!= '' && naf_to_date==''){
				alert("Kindly select to date");
				error=1;
				return false;
			}
			
			
			if(from_date > to_date){				
				alert("From Date Cannot be greater than To Date!!");
				error=1;
				return false;
			}
			
			if(error==0){
				//window.document.location.href = 'index_PM_krishna.php?userid='+userid+'&fdate='+naf_from_date+'&tdate='+naf_to_date;
			}
			
		}
		
		function showmodal(x,y){
			
			 
			 document.getElementById("title").innerHTML ="Naf No: "+x;
			//document.getElementById("pmaid").value =y;
			var id = y;
			var pmid = x;
			$.ajax({
				url: 'pendingwithdata.php',
				method: 'POST',
				data: {"id":id,"case":"naf"},
					success: function(result)
					{
						if(result!='')
						{
							$('#thead').html(result);
						}
					}

			});
			document.getElementById("myModal").style.display="block";
		}
		
		$('#close').on('click', function () {
            $('#myModal').hide();
        })
	</script>

</body>

</html>