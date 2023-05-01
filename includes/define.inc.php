<?php
define('CURRENT_YEAR',date('Y'));
define('NOW',date("Y-m-d H:i:s"));
define('NOW3', date("Ymd.Hi"));
define('TODAY', date("Y-m-d"));

define('CRM_ATTACHMENT_PATH',SITE_ADDRESS.'CRMATTACHMENT/');
define('CRM_ATTACHMENT_UPLOAD',DOCROOT.'CRMATTACHMENT/');


define('CRM_ADVANCE_ATTACH_PATH',SITE_ADDRESS.'advance_attachment/');

define('CRM_ADVANCE_ATTACH_UPLOAD',DOCROOT.'advance_attachment/');

define('CRM_CAMP_ADVANCE_ATTACH_UPLOAD',DOCROOT.'advance_attachment/');

define('IMAGE_PATH',SITE_ADDRESS.'images/');
define('IMAGE_UPLOAD',DOCROOT.'images/');

define("EDIT_IMG", "<img src='" . IMAGE_PATH . "edit.gif' alt='Edit Record' border='0' align='absmiddle'>");
define("VIEW_IMG", "<img src='" . IMAGE_PATH . "view.gif' alt='Delete Record' border='0' align='absmiddle'>");
define("DELETE_IMG", "<img src='" . IMAGE_PATH . "delete.gif' alt='Delete Record' border='0' align='absmiddle'>");
define('SQL_ERROR','rfff');


$GENDER_ARR = array('Male'=>'Male', 'Female'=>'Female', 'Other'=>'Other');
$VO_ARR=array('1'=>'Virtual','2'=>'Offline');
$VENDOR_SERVICES=array('1'=>'Yes','2'=>'No');
$IMG_TYPE = array('gif','png','pjpeg','jpeg','jpg','JPG');
$DOC_TYPE = array('txt','doc','docx','pdf','xls','xlsx');
$IMG_FILE_TYPE = array('image/gif','image/png','image/pjpeg','image/jpeg','image/jpg');
$DOC_FILE_TYPE = array('text/plain','application/msword','application/vnd.ms-word','application/pdf','application/vnd.ms-excel');
?>
