<?php
/* Resource Manager - Process Loader
 Copyright (C) 2018 DISIT Lab http://www.disit.org - University of Florence
 
 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as
 published by the Free Software Foundation, either version 3 of the
 License, or (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.
 
 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>. */
// include 'datatablemanager_myUtil.php';
$configs = parse_ini_file('poimanager_config.ini.php');
$processLoaderNatureURI=$configs['processLoaderNatureURI'];
$ownershipApiUrl=$configs['ownershipApiUrl'];
$ownershipListApiUrl=$configs['ownershipListApiUrl'];
include_once 'poimanager_sqlClient.php';
include_once 'poimanager_sqlQueryManager.php';

// function executeLoadContextBroker($url){
    
//     $access_token=getAccessToken();
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
    
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//         'Authorization: Bearer '.$access_token,
//         'accept: application/json',
//         'content-type: application/json',
//     ));
    
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     // receive server response ...
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     // receive server response ...
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
//     $response = curl_exec ($ch);
//     $err = curl_error($ch);
//     curl_close ($ch);
    
//     // further processing ....
//     if ($err) {
//         echo "cURL Error #:" . $err;
//     }
    
//     $json = json_decode($response, true);
//     $json_content=$json['data'];
//     $cbs=array();

    
//     foreach ($json_content as $value){
//         if(!in_array($cbs, $value['name'])){
//             array_push($cbs,$value['name']);
//         }
//     }
//     return  $cbs;
// }

function executePoiCheckIfElementIdExists($query,$element_id){
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$query);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec ($ch);
    $err = curl_error($ch);
    curl_close ($ch);
    
    // further processing ....
    if ($err) {
        echo "cURL Error #:" . $err;
    }
    
    $json = json_decode($response, true);
    $found='false';
    
    for($i=count($json)-1;$i>-1;$i--){
        $elementId=$json[$i]['elementId'];
        if($elementId==$element_id){
            $found='true';
            break;
        }
    }
    return $found;
}

function executePoiGetDataTablesLimitQuery($query){
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$query);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec ($ch);
    $err = curl_error($ch);
    curl_close ($ch);
    
    // further processing ....
    if ($err) {
        echo "cURL Error #:" . $err;
    }
    
    $json = json_decode($response, true);
    $role=$json['role'];
    $limits=$json['limits'][0];
    $limit=$limits['limit'];
    $current=$limits['current'];
    return  $role."|".$current."|".$limit;
}


// function executeGetLementIds($query){
    
//     //     echo $GLOBALS['ownershipListApiUrl'].$access_token;
//     //     die();
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL,$query);
//     //     curl_setopt($ch, CURLOPT_POSTFIELDS,$params_String);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     // receive server response ...
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     // receive server response ...
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
//     $response = curl_exec ($ch);
//     $err = curl_error($ch);
//     curl_close ($ch);
    
//     // further processing ....
//     if ($err) {
//         echo "cURL Error #:" . $err;
//     }
    
//     $json = json_decode($response, true);
//     $elementIds = array();
    
//     for($i=count($json)-1;$i>-1;$i--){
//         //     foreach ($json as $value){
//         $elementId=$json[$i]['elementId'];
//         array_push($elementIds,$elementId);
//     }
//     return $elementIds;
// }

// function executeDeleteElementIdOwnershipQuery($query,$access_token){
function executePoiDeleteElementIdOwnershipQuery($query){
    
//     echo $query;
//     die();
    //open connection
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch,CURLOPT_HTTPHEADER ,array('Content-Length: 0'));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    //perform the HTTP DELETE
    $result = curl_exec($ch);
    $json = json_decode($result, true);
    curl_close($ch);
    
    // further processing ....
    if ($json['error']) {
        return $json['error'].', Description: '.$json['error_description'];
    }else{
        return $json['deleted'];
    }
}

// function executeSelectDataTablesByUsernameQuery($query){
    
// //     echo $GLOBALS['ownershipListApiUrl'].$access_token;
// //     die();
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL,$query);
//     //     curl_setopt($ch, CURLOPT_POSTFIELDS,$params_String);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     // receive server response ...
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     // receive server response ...
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
//     $response = curl_exec ($ch);
//     $err = curl_error($ch);
//     curl_close ($ch);
    
//     // further processing ....
//     if ($err) {
//         echo "cURL Error #:" . $err;
//     }
    
//     $json = json_decode($response, true);
//     $obj = array();
    
//     for($i=count($json)-1;$i>-1;$i--){
// //     foreach ($json as $value){
//         $processed="";
//         $elementId=$json[$i]['elementId'];
//         $pos = strpos($elementId, '|');
//         $file=substr($elementId, 0,$pos);
//         $upload_date_time_utc=substr($elementId,$pos+1,strlen($elementId));
//         $upload_date_time=gmdate('D, d M Y H:i:s T', $upload_date_time_utc);
        
//         $statusElementQuery=getStatusElementQuery($elementId);
//         $element_proceses_status=executeStatusElementQuery($statusElementQuery);

//         if($element_proceses_status=="model"){
//             $processed="Created|Not Created|Not Created";
//         }else if($element_proceses_status=="device"){
//             $processed="Created|Created|Not Created";
//         }else if($element_proceses_status=="YES"){
//             $processed="Created|Created|Created";
//         }else{
//             $processed="Not Created|Not Created|Not Created";
//         }

//         $item= array(
//             "elementId" =>$elementId,
//             "file" => $file,
//             "upload_date_time" => $upload_date_time,
//             "upload_date_time_utc" => $upload_date_time_utc,
//             "process_status"=>$processed
//         );
//         array_push($obj,$item);
//     }
//     return json_encode($obj);
// }

function executePoiSelectDataTablesByUsernameQuery($query){
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$query);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec ($ch);
    $err = curl_error($ch);
    curl_close ($ch);
    
    // further processing ....
    if ($err) {
        echo "cURL Error #:" . $err;
    }
    
    $json = json_decode($response, true);
    $obj = array();
    
    for ($i = count($json) - 1; $i > - 1; $i --) {
        $processed = "";
        $elementId = $json[$i]['elementId'];

//         if (in_array($elementId, $element_ids_by_delegated_user)) {

            $pos = strpos($elementId, '|');
            $file = substr($elementId, 0, $pos);
            $file_name_no_space = str_replace(" ", "", $file);
            $upload_date_time_utc = substr($elementId, $pos + 1, strlen($elementId));
            $upload_date_time = gmdate('D, d M Y H:i:s T', $upload_date_time_utc);

            $statusElementQuery = getPoiStatusElementQuery($elementId);
            $element_proceses_status = executePoiStatusElementQuery($statusElementQuery);

            $orgQuery = getPoiOrgQuery($elementId);
            $org = executePoiGetOrgQuery($orgQuery);

            if ($element_proceses_status == "NO") {
                $processed = "Not Created";
            } else if ($element_proceses_status == "YES") {
                $processed = "Created";
            }

            $item = array(
                "elementId" => $elementId,
                "file" => $file,
                "rdf_file_name" => substr($file_name_no_space, 0, count($file_name_no_space) - 5) . 'n3',
                "upload_date_time" => $upload_date_time,
                "upload_date_time_utc" => $upload_date_time_utc,
                "process_status" => $processed,
                "organization" => $org
            );
            array_push($obj, $item);
//         }
    }
    return json_encode($obj);
}

// function executeGetNatureQuery(){
    
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL,$GLOBALS['processLoaderNatureURI']);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
//     $response = curl_exec ($ch);
//     $err = curl_error($ch);
//     curl_close ($ch);
    
//     if ($err) {
//         echo "cURL Error #:" . $err;
//     }
    
//     $json = json_decode($response, true);
//     $json_content=$json['content'];
//     $natures=array();

//     foreach ($json_content as $value){
//         if(!in_array($value['parent_value'][0], $natures)){
//         array_push($natures,$value['parent_value'][0]);
//         }
//     }
//     return  $natures;
// }


// function executeGetElementIdByUserNameQuery($query,$file_name){

//     $ch = curl_init();
//     $headers = array(
//         'Accept: application/json',
//         'Content-Type: application/json',
        
//     );
//     curl_setopt($ch, CURLOPT_URL, $query);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_HEADER, 0);
//     $body = '{}';
    
//     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
//     curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
//     $response = curl_exec ($ch);
//     $err = curl_error($ch);
//     curl_close ($ch);
    
//     // further processing ....
//     if ($err) {
//         echo "cURL Error #:" . $err;
//     }

//     $json = json_decode($response, true);
//     $found_elementId=array();
//     $j=0;
//     for($x=0;$x<count($json);$x++){
//         $elemendId=$json[$x]['elementId'];
//         $pos = strpos($elemendId, '|');
//         $file=substr($elemendId, 0,$pos);
//         if($file==$file_name){
//             $found_elementId[$j]=$elemendId;
//             ++$j;
//         }
//     }
//     return $found_elementId;
// }


// function executeGetValueTypeAndValueNamesQuery($params_String){
// //     echo $GLOBALS['valueTypeValueUnitApiUrl'];
// //     echo "<br>";
// //     echo $params_String;
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL,$GLOBALS['valueTypeValueUnitApiUrl']);
//     curl_setopt($ch, CURLOPT_POSTFIELDS,$params_String);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     // receive server response ...
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     // receive server response ...
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
//     $response = curl_exec ($ch);
//     $err = curl_error($ch);
//     curl_close ($ch);

//     // further processing ....
//     if ($err) {
//         echo "cURL Error #:" . $err;
//     }

//     $json = json_decode($response, true);
//     $value_types=$json['data_type'];
//     return  $value_types;
// }

// function retrieveFromDictionary($param){
     
//     $url= $GLOBALS['processLoaderURI'] . "dictionary/?type=".$param;
//         $curl = curl_init($url);
//         curl_setopt($curl, CURLOPT_FAILONERROR, true);
//         curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
//         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//         $result = curl_exec($curl);
        
//         $json = json_decode($result, true);
//             $value_types=$json['content'];
//             echo $value_types;
//             $value_types_values=array();
//             foreach ($value_types as $value){
//                 array_push($value_types_values,$value['value']);
//             }
//             return $value_types_values;
// }


// function executeRegisterDataTableID($params_json_encoded, $access_token)
// {

   
//     $api_url = $GLOBALS['ownershipApiUrl'] . "accessToken=" . $access_token;
    
//     // Prepare new cURL resource
//     $ch = curl_init($api_url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $params_json_encoded);

//     // Set HTTP Header for POST request
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//         'Content-Type: application/json',
//         'Content-Length: ' . strlen($params_json_encoded)
//     ));

//     // Submit the POST request
//     $response = curl_exec($ch);
//     // Close cURL session handle
//     curl_close($ch);
//     $err = curl_error($ch);
    
//     if ($err) {
//         echo 'here';
//         echo "cURL Error #:" . $err;
//     }
    
//     if( json_decode($response,true)['error']){
   
//         $error_description=json_decode($response, true)['error_description']?json_decode($response, true)['error_description']:json_decode($response, true)['error'];
//         if($error_description)
//         {
//             $to_return_error="Error: ". $error_description;
//         }else{
//             $to_return_error="Error";
//         }
//         return $to_return_error;
//     }else{
//         return  json_decode($response, true);
//     }
// }

function getPoiElementIdsByDelegatedUsers($url){
    
    $access_token=getAccessToken();
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer '.$access_token,
        'accept: application/json',
        'content-type: application/json',
    ));
    
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec ($ch);
    $err = curl_error($ch);
    curl_close ($ch);
    
    if ($err) {
        echo "cURL Error #:" . $err;
    }
    
    $items = json_decode($response, true);
    $delegated_users=array();
    
    foreach ($items as $item){
        array_push($delegated_users,$item['elementId']);
    }
    return $delegated_users;
}

?>