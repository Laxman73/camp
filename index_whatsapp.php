<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once('include/database/PearDatabase.php');
require_once('include/Whatsapp_shortener.php');

require_once('include/utils.php');
require_once('include/ComboUtil.php');

global $current_user;
/*$longURL = $site_URL."index_teleconsent.php?module=WhatsAppConsent&action=consent_capture&id=";
$RXlongURL = $site_URL."index_teleconsent.php?module=WhatsAppConsent&action=whatsapp_rxupload&id=";*/

$aggrementURL = $site_URL."/modules/CRM/hcp_agreement.php?";
$service_aggrementURL = $site_URL."/modules/CRM_demo/service_agreement_advisory.php?";
$service_aggrementURL_camp = $site_URL."/modules/CRM_demo/service_agreement_camp.php?";
$consultancy_aggrementURL = $site_URL."/modules/CRM/service_agreement_consultancy.php?";
$questionnireURL = $site_URL."/modules/CRM/questionnaire.php?";
// $RXlongURL = $site_URL."index_teleconsent.php?module=WhatsAppConsent&action=whatsapp_rxupload&id=";

//$shortURL_Prefix = 'https://gforce.glenmarkpharma.com/API/';
// try{
//     // Get short code of the URL
//     $shortCode = urlToShortCode($longURL,255);
//     // Create short URL
//     $shortURL = $shortURL_Prefix.$shortCode;
//     // Display short URL
//     echo 'Short URL: '.$shortURL;
// }catch(Exception $e){
//     // Display error
//     echo $e->getMessage();
// }
// Retrieve short code from URL
$shortCode = $_GET["url"];
try{
    // Get URL by short code
    $type = checkurltype($shortCode,$crm_type);

    if($type=='agreement')
    {//RX url--------
        // $url = rx_shortCodeToUrl($shortCode);
        $urlid = getUrlFromDB_agr($shortCode);
		$idsdec = json_decode($urlid,true);
        $requestor_id = getRequestorcrmmainID($idsdec['pid']);
        // Redirect to the original URL
        // header("Location: ".$RXlongURL.$url);
		$url = "agraccessshorturl=1&userid=".$requestor_id."&rid=".$idsdec['rid']."&pid=".$idsdec['pid'];
        header("Location: ".$aggrementURL.$url);
        exit;
    }
    elseif($type=='advisory_agreement')
    {
         // $url = rx_shortCodeToUrl($shortCode);
         $urlid = getUrlFromDB_agr($shortCode);
         $idsdec = json_decode($urlid,true);
         $requestor_id = getRequestorcrmmainID($idsdec['pid']);
         // Redirect to the original URL
         // header("Location: ".$RXlongURL.$url);
         $url = "agraccessshorturl=1&userid=".$requestor_id."&rid=".$idsdec['rid']."&pid=".$idsdec['pid'];
         header("Location: ".$service_aggrementURL.$url);
         exit;
    }
    elseif($type=='consultancy_agreement')
    {
         // $url = rx_shortCodeToUrl($shortCode);
         $urlid = getUrlFromDB_agr($shortCode);
         $idsdec = json_decode($urlid,true);
         $requestor_id = getRequestorcrmmainID($idsdec['pid']);
         // Redirect to the original URL
         // header("Location: ".$RXlongURL.$url);
         $url = "agraccessshorturl=1&userid=".$requestor_id."&rid=".$idsdec['rid']."&pid=".$idsdec['pid'];
         header("Location: ".$consultancy_aggrementURL.$url);
         exit;
    }elseif($type=='camp_agreement')
    {
         // $url = rx_shortCodeToUrl($shortCode);
         $urlid = getUrlFromDB_agr($shortCode);
         $idsdec = json_decode($urlid,true);
         $requestor_id = getRequestorcrmmainID($idsdec['pid']);
         // Redirect to the original URL
         // header("Location: ".$RXlongURL.$url);
         $url = "agraccessshorturl=1&userid=".$requestor_id."&rid=".$idsdec['rid']."&pid=".$idsdec['pid'];
         header("Location: ".$service_aggrementURL_camp.$url);
         exit;
    }
    else                 //if its not advicsory nor hcp nor comsultancy then it is questionanaire
    {//consent url-----
        $urlid = shortCodeToUrl($shortCode);
        // Redirect to the original URL
        // header("Location: ".$longURL.$url);
		$idsdec = json_decode($urlid,true); //print_r($idsdec['rid']);exit;
		$requestor_id = getRequestorcrmmainID($idsdec['pid']);
		$url = "qutaccessshorturl=1&userid=".$requestor_id."&rid=".$idsdec['rid']."&pid=".$idsdec['pid'];
        header("Location: ".$questionnireURL.$url);
        exit;
    }

    
}catch(Exception $e){
    // Display error
    echo $e->getMessage();
}

?>
