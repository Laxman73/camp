<?php

    //include('include/database/PearDatabase.php');
     function urlToShortCode($url,$id){
        $checkUrlExists = false;
        if(empty($url)){
            throw new Exception("No URL was supplied.");
        }

        if(validateUrlFormat($url) == false){
            throw new Exception("URL does not have a valid format.");
        }

        if($checkUrlExists){
            if (!verifyUrlExists($url)){
                throw new Exception("URL does not appear to exist.");
            }
        }

        // $shortCode = urlExistsInDB($url);
        //
        // if($shortCode == false){
            $shortCode = createShortCode($url,2,$id);
      //  }

        return $shortCode;
    }
	
	function urlToShortCode_agr($url,$id,$crm_type){
        $checkUrlExists = false;
        if(empty($url)){
            throw new Exception("No URL was supplied.");
        }

        if(validateUrlFormat($url) == false){
            throw new Exception("URL does not have a valid format.");
        }

        if($checkUrlExists){
            if (!verifyUrlExists($url)){
                throw new Exception("URL does not appear to exist.");
            }
        }

        // $shortCode = urlExistsInDB($url);
        //
        // if($shortCode == false){
            $shortCode = createShortCode_agr($url,2,$id,$crm_type);
      //  }

        return $shortCode;
    }

     function urlToShortCode_rx($url,$id){
        $checkUrlExists = false;
        if(empty($url)){
            throw new Exception("No URL was supplied.");
        }

        if(validateUrlFormat($url) == false){
            throw new Exception("URL does not have a valid format.");
        }

        if($checkUrlExists){
            if (!verifyUrlExists($url)){
                throw new Exception("URL does not appear to exist.");
            }
        }
        $shortCode = createShortCode_rx($url,2,$id);

        return $shortCode;
    }

     function validateUrlFormat($url){
        return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }

     function verifyUrlExists($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }

  function urlExistsInDB($url){
      global $adb;
      $query ="SELECT consentshort_url FROM `crm_questionoire_link_sharing` WHERE consentshort_url = '".$url."'";
      $res=$adb->query($query);
      $consentshort_url=$adb->query_result($res,0,"consentshort_url");
      return (empty($consentshort_url)) ? false : $consentshort_url;
    }

     function SCExistsInDB($shortcode){ // shortCodeExistsInDB
       global $adb;
       $query ="SELECT count(consentshort_url) as cnt FROM `crm_questionoire_link_sharing` WHERE consentshort_url = '".$shortcode."' LIMIT 1";
       $res=$adb->query($query);
       $cnt=$adb->query_result($res,0,"cnt");
        return $cnt;
    }

     function RXExistsInDB($shortcode){ // shortCodeExistsInDB
       global $adb;
       $query ="SELECT count(rxshort_url) as cnt FROM `crm_questionoire_link_sharing` WHERE rxshort_url = '".$shortcode."' LIMIT 1";
       $res=$adb->query($query);
       $cnt=$adb->query_result($res,0,"cnt");
        return $cnt;
    }
	
	function AGRExistsInDB($shortcode,$crm_type){ // shortCodeExistsInDB
       global $adb;
       $query ="SELECT count(consentshort_url) as cnt FROM `crm_agreement_link_sharing` WHERE consentshort_url = '".$shortcode."' and crm_type='".$crm_type."' LIMIT 1";
       $res=$adb->query($query);
       $cnt=$adb->query_result($res,0,"cnt");
        return $cnt;
    }

     function createShortCode($url,$i,$id){
        $codeLength = 7;
        $shortCode = generateRandomString($codeLength);
        $shortCodeCheck = SCExistsInDB($shortCode);
        if($shortCodeCheck == 0){
            $id = insertUrlInDB($url, $shortCode,$id);
            return $shortCode;
        }else{
            $shortCode = createShortCode($url);
        }
    }
	
	function createShortCode_agr($url,$i,$id,$crm_type){
        $codeLength = 7;
        $shortCode = generateRandomString($codeLength);
        $shortCodeCheck = AGRExistsInDB($shortCode,$crm_type);
        if($shortCodeCheck == 0){
            $id = agrinsertInDB($url, $shortCode,$id);
            return $shortCode;
        }else{
            $shortCode = createShortCode_agr($url);
        }
    }

     function createShortCode_rx($url,$i,$id){
        $codeLength = 5;
        $shortCode = generateRandomString($codeLength);
        $shortCodeCheck = RXExistsInDB($shortCode);
        if($shortCodeCheck == 0){
            $id = rxinsertInDB($url, $shortCode,$id);
            return $shortCode;
        }else{
            $shortCode = createShortCode_rx($url);
        }
    }

     function generateRandomString($length = 6){
        $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
        $sets = explode('|', $chars);
        $all = '';
        $randString = '';
        foreach($sets as $set){
            $randString .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++){
            $randString .= $all[array_rand($all)];
        }
        $randString = str_shuffle($randString);
        return $randString;
    }

     function insertUrlInDB($url, $code, $id){
       global $adb;
       $upd_query="update crm_questionoire_link_sharing set consentshort_url='".$code."' where id='".$id."'";
   	   $res_upd_query=$adb->query($upd_query);

       return $id;
    }

     function rxinsertInDB($url, $code, $id){
       global $adb;
       $upd_query="update crm_questionoire_link_sharing set rxshort_url='".$code."' where id='".$id."'";
   	   $res_upd_query=$adb->query($upd_query);

       return $id;
    }
	
	function agrinsertInDB($url, $code, $id){
       global $adb;
       $upd_query="update crm_agreement_link_sharing set consentshort_url='".$code."' where id='".$id."'";
   	   $res_upd_query=$adb->query($upd_query);

       return $id;
    }

    function shortCodeToUrl($code){
        if(empty($code)) {
            throw new Exception("No URL was supplied.");
        }

        if(validateShortCode($code) == false){
            throw new Exception("URL does not have a valid format.");
        }

        $urlRow = getUrlFromDB($code);
        if(empty($urlRow)){
            throw new Exception("URL does not appear to exist.");
        }
        return $urlRow;
    }
    function rx_shortCodeToUrl($code){
        if(empty($code)) {
            throw new Exception("No URL was supplied.");
        }

        if(validateShortCode($code) == false){
            throw new Exception("URL does not have a valid format.");
        }

        $urlRow = getUrlFromDB_rx($code);
        if(empty($urlRow)){
            throw new Exception("URL does not appear to exist.");
        }
        return $urlRow;
    }

     function validateShortCode($code){
        $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
        $rawChars = str_replace('|', '', $chars);
        return preg_match("|[".$rawChars."]+|", $code);
    }

     function getUrlFromDB($code){
       global $adb;
       $query ="SELECT crm_naf_main_id,pid FROM `crm_questionoire_link_sharing` WHERE consentshort_url = '".$code."' and deleted=0 LIMIT 1";
       $res=$adb->query($query);
       $id=$adb->query_result($res,0,"crm_naf_main_id");
       $pid=$adb->query_result($res,0,"pid");

        $rid = (empty($id)) ? false : $id;
        $pidp = (empty($pid)) ? false : $pid;
        return json_encode(array("rid" => $rid,"pid" => $pidp));
    }

     function getUrlFromDB_rx($code){
       global $adb;
       $query ="SELECT id FROM `crm_questionoire_link_sharing` WHERE rxshort_url = '".$code."' LIMIT 1";
       $res=$adb->query($query);
       $id=$adb->query_result($res,0,"id");

        return (empty($id)) ? false : $id;
    }
	
	function getUrlFromDB_agr($code){
       global $adb;
       echo $query ="SELECT crm_naf_main_id,pid FROM `crm_agreement_link_sharing` WHERE consentshort_url = '".$code."' and deleted = 0 LIMIT 1";
       $res=$adb->query($query);
       $id=$adb->query_result($res,0,"crm_naf_main_id");
		$pid=$adb->query_result($res,0,"pid");

        $rid = (empty($id)) ? false : $id;
        $pidp = (empty($pid)) ? false : $pid;
        return json_encode(array("rid" => $rid,"pid" => $pidp));
        // return (empty($id)) ? false : $id;
    }

    function checkurltype($code,$crm_type){
       global $adb;
       $query ="SELECT crm_type FROM `crm_agreement_link_sharing` WHERE consentshort_url = '".$code."' LIMIT 1";
       $res=$adb->query($query);
       $type=$adb->query_result($res,0,"crm_type");
       return $type;

    }

	function getRequestorcrmmainID($code){
       global $adb;
       $query ="SELECT requestor_id as cnt FROM `crm_request_main` WHERE id = '".$code."' LIMIT 1";
       $res=$adb->query($query);
       $cnt=$adb->query_result($res,0,"cnt");
       return $cnt;

    }

?>