<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/common.php';
// DFA($_FILES);
// DFA($_POST);
$pdo = connectToDatabase();
$rid = db_input($_POST['rid']);
$pid = db_input($_POST['pid']);
$mode = db_input($_POST['mode']);
$userid = db_input($_POST['userid']);
$error = array();
$pendingStateHeadID = GetXFromYID("select statehead_id from crm_statehead_details");

$User_division = GetXFromYID("select division from users where id='$userid' "); //Getting division

$Current_level = GetXFromYID("select level from crm_request_main where id=$pid "); //current level from crm_naf_main table
$New_level = $Current_level + 1; // increment by 1


$PENDING_WITH_ID = $STATUS = '';
$crm_workflow = crm_workflow($userid, 3, $New_level, $User_division); //Getting the details from crm_workflow 
if (!empty($crm_workflow)) {
    $PENDING_WITH_ID = $crm_workflow['pending_with_id'];
    $STATUS = $crm_workflow['status'];
}


$current_userid = $userid;
$ROLE_ID = GetXFromYID("select roleid from user2role where userid='" . $current_userid . "'");
$PROFILE_ID = GetXFromYID("select profileid from role2profile where roleid='" . $ROLE_ID . "'");

$stmt = $pdo->prepare("insert into crm_naf_document_upload (id,crm_request_main_id,document_type_id,file_name,file_path,uploaded_on,deleted) values(?,?,?,?,?,?,?)");
//$stmt2=$pdo->prepare("update crm_naf_document_upload set document_type_id=?,file_name=?,file_path=? where crm_request_main_id=? ");
if ($mode == 'I') {
    if (isset($_FILES)) {

        if (is_uploaded_file($_FILES['pan']["tmp_name"])) {
            $uploaded_pic = $_FILES['pan']["name"];
            $name = basename($_FILES['pan']['name']);
            $file_type = $_FILES['pan']['type'];
            $size = $_FILES['pan']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);

            if (IsValidFile($file_type, $extension, 'P') && $size <= 2000000) {
                $newname = NormalizeFilename($uploaded_pic); // normalize the file name
                $pic_name =  $rid . $pid . "_CRM_Attahment_pan" . NOW3 . '.' . $newname;
                $fileName =  $rid . $pid . '_CRM_Attahment_pan' . NOW3 . '.' . $extension;

                $dir = opendir(CRM_ATTACHMENT_UPLOAD);
                move_uploaded_file($_FILES['pan']["tmp_name"],  CRM_ATTACHMENT_UPLOAD . $fileName);
                //copy($_FILES['file_attach_'.$j]["tmp_name"], CRM_ATTACHMENT_UPLOAD.$fileName);
                closedir($dir);   // close the directory

                $filePath = CRM_ATTACHMENT_PATH . $fileName;
                $id = NextID('id', 'crm_naf_document_upload');
                $stmt->execute(array($id, $pid, 1, $fileName, $filePath, NOW, 0));


                //insert into history table
                $HID = NextID('pid', 'crm_naf_document_upload_history');
                $pdo->prepare("insert into crm_naf_document_upload_history (pid,id,crm_request_main_id,document_type_id,file_name,file_path,uploaded_on,deleted,from_date,to_date,updated_by) values(?,?,?,?,?,?,?,?,?,?,?) ")->execute(array($HID, $id, $pid, 1, $fileName, $filePath, NOW, 0, NOW,NULL, $userid));
                //echo 'success';


            } else {
                $error['pan'] = 'Pan file should be less than 2MB ';
            }
        }
        if (is_uploaded_file($_FILES['cancel_check']["tmp_name"])) {
            $uploaded_pic = $_FILES['cancel_check']["name"];
            $name = basename($_FILES['cancel_check']['name']);
            $file_type = $_FILES['cancel_check']['type'];
            $size = $_FILES['cancel_check']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);

            if (IsValidFile($file_type, $extension, 'P') && $size <= 3000000) {
                $newname = NormalizeFilename($uploaded_pic); // normalize the file name
                $pic_name =  $rid . "_CRM_Attahment_cancel_check" . NOW3 . '.' . $newname;
                $fileName =  $rid . '_CRM_Attahment_cancel_check' . NOW3 . '.' . $extension;

                $dir = opendir(CRM_ATTACHMENT_UPLOAD);
                move_uploaded_file($_FILES['cancel_check']["tmp_name"],  CRM_ATTACHMENT_UPLOAD . $fileName);
                //copy($_FILES['file_attach_'.$j]["tmp_name"], CRM_ATTACHMENT_UPLOAD.$fileName);
                closedir($dir);   // close the directory

                $filePath = CRM_ATTACHMENT_PATH . $fileName;
                $id = NextID('id', 'crm_naf_document_upload');
                $stmt->execute(array($id, $pid, 2, $fileName, $filePath, NOW, 0));

                //insert into history table
                $HID = NextID('pid', 'crm_naf_document_upload_history');
                $pdo->prepare("insert into crm_naf_document_upload_history (pid,id,crm_request_main_id,document_type_id,file_name,file_path,uploaded_on,deleted,from_date,to_date,updated_by) values(?,?,?,?,?,?,?,?,?,?,?) ")->execute(array($HID, $id, $pid, 2, $fileName, $filePath, NOW, 0, NOW, NULL, $userid));
                //echo 'success';


            } else {
                $error['cancel_check'] = 'Cancel check file should be less than  2 MB';
            }
        }
        if (is_uploaded_file($_FILES['visiting_card']["tmp_name"])) {
            $uploaded_pic = $_FILES['visiting_card']["name"];
            $name = basename($_FILES['visiting_card']['name']);
            $file_type = $_FILES['visiting_card']['type'];
            $size = $_FILES['visiting_card']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);

            if (IsValidFile($file_type, $extension, 'P') && $size <= 3000000) {
                $newname = NormalizeFilename($uploaded_pic); // normalize the file name
                $pic_name =  $rid . "_CRM_Attahment_visiting_card" . NOW3 . '.' . $newname;
                $fileName =  $rid . '_CRM_Attahment_visiting_card' . NOW3 . '.' . $extension;

                $dir = opendir(CRM_ATTACHMENT_UPLOAD);
                move_uploaded_file($_FILES['visiting_card']["tmp_name"],  CRM_ATTACHMENT_UPLOAD . $fileName);
                //copy($_FILES['file_attach_'.$j]["tmp_name"], CRM_ATTACHMENT_UPLOAD.$fileName);
                closedir($dir);   // close the directory

                $filePath = CRM_ATTACHMENT_PATH . $fileName;
                $id = NextID('id', 'crm_naf_document_upload');
                $stmt->execute(array($id, $pid, 3, $fileName, $filePath, NOW, 0));
                //echo 'success';

                //insert into history table
                $HID = NextID('pid', 'crm_naf_document_upload_history');
                $pdo->prepare("insert into crm_naf_document_upload_history (pid,id,crm_request_main_id,document_type_id,file_name,file_path,uploaded_on,deleted,from_date,to_date,updated_by) values(?,?,?,?,?,?,?,?,?,?,?) ")->execute(array($HID, $id, $pid, 3, $fileName, $filePath, NOW, 0, NOW, NULL, $userid));

            } else {
                $error['visitinng_card'] = 'Visiting card file should be less than 2 MB';
            }
        }
    }
} else if ($mode == 'E') {
    $stmt2 = $pdo->prepare("delete from crm_naf_document_upload where crm_request_main_id=? and document_type_id=?");
    if (isset($_FILES)) {

        if (is_uploaded_file($_FILES['pan']["tmp_name"])) {
            $stmt2->execute(array($pid, 1));
            $uploaded_pic = $_FILES['pan']["name"];
            $name = basename($_FILES['pan']['name']);
            $file_type = $_FILES['pan']['type'];
            $size = $_FILES['pan']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);

            if (IsValidFile($file_type, $extension, 'P') && $size <= 2000000) {
                $newname = NormalizeFilename($uploaded_pic); // normalize the file name
                $pic_name =  $rid . $pid . "_CRM_Attahment_pan" . NOW3 . '.' . $newname;
                $fileName =  $rid . $pid . '_CRM_Attahment_pan' . NOW3 . '.' . $extension;

                $dir = opendir(CRM_ATTACHMENT_UPLOAD);
                move_uploaded_file($_FILES['pan']["tmp_name"],  CRM_ATTACHMENT_UPLOAD . $fileName);
                //copy($_FILES['file_attach_'.$j]["tmp_name"], CRM_ATTACHMENT_UPLOAD.$fileName);
                closedir($dir);   // close the directory

                $filePath = CRM_ATTACHMENT_PATH . $fileName;
                $id = NextID('id', 'crm_naf_document_upload');
                $stmt->execute(array($id, $pid, 1, $fileName, $filePath, NOW, 0));

                $last_ID = GetXFromYID("select pid from crm_naf_document_upload_history where crm_request_main_id='$pid' and document_type_id='1' order by pid  DESC limit 1 ");

                if (!empty($last_ID)) {
                    $pdo->prepare("update crm_naf_document_upload_history set to_date=? where pid=? ")->execute(array(NOW, $last_ID));
                }

                 //insert into history table
                 $HID = NextID('pid', 'crm_naf_document_upload_history');
                 $pdo->prepare("insert into crm_naf_document_upload_history (pid,id,crm_request_main_id,document_type_id,file_name,file_path,uploaded_on,deleted,from_date,to_date,updated_by) values(?,?,?,?,?,?,?,?,?,?,?) ")->execute(array($HID, $id, $pid, 1, $fileName, $filePath, NOW, 0, NOW, NULL, $userid));
                //echo 'success';


            } else {
                $error['pan'] = 'Pan file should be less than 2MB ';
            }
        }
        if (is_uploaded_file($_FILES['cancel_check']["tmp_name"])) {
            $stmt2->execute(array($pid, 2));
            $uploaded_pic = $_FILES['cancel_check']["name"];
            $name = basename($_FILES['cancel_check']['name']);
            $file_type = $_FILES['cancel_check']['type'];
            $size = $_FILES['cancel_check']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);

            if (IsValidFile($file_type, $extension, 'P') && $size <= 3000000) {
                $newname = NormalizeFilename($uploaded_pic); // normalize the file name
                $pic_name =  $rid . "_CRM_Attahment_cancel_check" . NOW3 . '.' . $newname;
                $fileName =  $rid . '_CRM_Attahment_cancel_check' . NOW3 . '.' . $extension;

                $dir = opendir(CRM_ATTACHMENT_UPLOAD);
                move_uploaded_file($_FILES['cancel_check']["tmp_name"],  CRM_ATTACHMENT_UPLOAD . $fileName);
                //copy($_FILES['file_attach_'.$j]["tmp_name"], CRM_ATTACHMENT_UPLOAD.$fileName);
                closedir($dir);   // close the directory

                $filePath = CRM_ATTACHMENT_PATH . $fileName;
                $id = NextID('id', 'crm_naf_document_upload');
                $stmt->execute(array($id, $pid, 2, $fileName, $filePath, NOW, 0));
                //echo 'success';


                $last_ID = GetXFromYID("select pid from crm_naf_document_upload_history where crm_request_main_id='$pid' and document_type_id='2' order by pid  DESC limit 1 ");

                if (!empty($last_ID)) {
                    $pdo->prepare("update crm_naf_document_upload_history set to_date=? where pid=? ")->execute(array(NOW, $last_ID));
                }

                 //insert into history table
                 $HID = NextID('pid', 'crm_naf_document_upload_history');
                 $pdo->prepare("insert into crm_naf_document_upload_history (pid,id,crm_request_main_id,document_type_id,file_name,file_path,uploaded_on,deleted,from_date,to_date,updated_by) values(?,?,?,?,?,?,?,?,?,?,?) ")->execute(array($HID, $id, $pid, 2, $fileName, $filePath, NOW, 0, NOW, NULL, $userid));


            } else {
                $error['cancel_check'] = 'Cancel check file should be less than  2 MB';
            }
        }
        if (is_uploaded_file($_FILES['visiting_card']["tmp_name"])) {
            $stmt2->execute(array($pid, 3));
            $uploaded_pic = $_FILES['visiting_card']["name"];
            $name = basename($_FILES['visiting_card']['name']);
            $file_type = $_FILES['visiting_card']['type'];
            $size = $_FILES['visiting_card']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);

            if (IsValidFile($file_type, $extension, 'P') && $size <= 3000000) {
                $newname = NormalizeFilename($uploaded_pic); // normalize the file name
                $pic_name =  $rid . "_CRM_Attahment_visiting_card" . NOW3 . '.' . $newname;
                $fileName =  $rid . '_CRM_Attahment_visiting_card' . NOW3 . '.' . $extension;

                $dir = opendir(CRM_ATTACHMENT_UPLOAD);
                move_uploaded_file($_FILES['visiting_card']["tmp_name"],  CRM_ATTACHMENT_UPLOAD . $fileName);
                //copy($_FILES['file_attach_'.$j]["tmp_name"], CRM_ATTACHMENT_UPLOAD.$fileName);
                closedir($dir);   // close the directory

                $filePath = CRM_ATTACHMENT_PATH . $fileName;
                $id = NextID('id', 'crm_naf_document_upload');
                $stmt->execute(array($id, $pid, 3, $fileName, $filePath, NOW, 0));
                //echo 'success';


                $last_ID = GetXFromYID("select pid from crm_naf_document_upload_history where crm_request_main_id='$pid' and document_type_id='3' order by pid  DESC limit 1 ");

                if (!empty($last_ID)) {
                    $pdo->prepare("update crm_naf_document_upload_history set to_date=? where pid=? ")->execute(array(NOW, $last_ID));
                }

                 //insert into history table
                 $HID = NextID('pid', 'crm_naf_document_upload_history');
                 $pdo->prepare("insert into crm_naf_document_upload_history (pid,id,crm_request_main_id,document_type_id,file_name,file_path,uploaded_on,deleted,from_date,to_date,updated_by) values(?,?,?,?,?,?,?,?,?,?,?) ")->execute(array($HID, $id, $pid, 3, $fileName, $filePath, NOW, 0, NOW, NULL, $userid));


            } else {
                $error['visitinng_card'] = 'Visiting card file should be less than 2 MB';
            }
        }
    }
}


// $stmt=$pdo->prepare("update crm_naf_main set level=? where id=? ");
// $stmt->execute(array(3,$rid));

if ($PROFILE_ID == '6' || $PROFILE_ID == '7') {
    $stmt = $pdo->prepare("update crm_request_main set authorise=?,pendingwithid=?,level=? where id=?"); ///sql injection
    if ($stmt->execute(array($STATUS, $PENDING_WITH_ID, $New_level, $pid))) {
        $ID = NextID('id', 'crm_pma_approvereject');
        $stmt = $pdo->prepare("insert into crm_pma_approvereject (id,pma_request_id,userid,pendingwithid,authorise,level,remark,submitted_on,deleted,deleted_on) values(?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute(array($ID, $pid, $userid, $PENDING_WITH_ID, $STATUS, 3, 'pending', TODAY, 0, TODAY));
    }
}
//added by rene for pdf saving in folder //4th may 
$url = $_REQUEST['url'];
$pdf_url="http://88.99.140.102/MicrolabReplicav3/modules/CRM/".$url;        //since pdf is generated dynamically, curl is used tp fetch and save content.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pdf_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$pdf_content = curl_exec($ch);
curl_close($ch);

$hcp_id=GetXFromYID("select hcp_id from crm_request_details where deleted=0 and crm_request_main_id ='".$pid."' ");
$naf_no = GetXFromYID("select naf_no from crm_naf_main where id='".$rid."' and deleted=0 ");
$state=GetXFromYID("select statename from contactmaster 
inner join state on contactmaster.otherstate=state.stateid
where id='".$hcp_id."' and deleted=0");

$file_name=$naf_no.'_'.$state.'.pdf';
$dir = opendir(CRM_ATTACHMENT_PDF);
file_put_contents(CRM_ATTACHMENT_PDF.$file_name, $pdf_content);
closedir($dir);   
//////////////////////////////////////////////////




// if ($PROFILE_ID == '15') { 
//     $stmt=$pdo->prepare("update crm_request_main set authorise=? and level=? where pma_request_id=?");///sql injection
//     if($stmt->execute(array(2,3,$pid))){
//         $ID = NextID('id', 'crm_pma_approvereject');
//         $stmt=$stmt=$pdo->prepare("insert into crm_pma_approvereject (id,pma_request_id,userid,pendingwithid,authorise,level,remark,submitted_on,deleted,deleted_on) values(?,?,?,?,?,?,?,?,?,?)");
//         $stmt->execute(array($ID, $pid, $userid, $pendingStateHeadID, 1, 3, 'pending', TODAY, 0, TODAY));

//     }
// }
if ($PROFILE_ID == 15){
    header('location:document_upload.php?userid=' . $userid.'&rid='.$rid.'&pid='.$pid);
    exit;
}else{

    header('location:index_pma.php?userid=' . $userid);
    exit;
}
?>