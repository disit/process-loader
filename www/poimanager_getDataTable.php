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
// header("Content-Type:application/json");
include_once 'poimanager_sqlClient.php';
include_once 'poimanager_sqlQueryManager.php';
include_once 'poimanager_APICient.php';
include_once 'poimanager_APIQueryManager.php';
include_once 'datatablemanager_APIQueryManager.php';

$access_token=$_GET['access_token'];
$element_id=$_GET['elementId'];

if (isset($access_token) && $access_token != "") {
    if (isset($element_id) && $element_id != "") {

//         $list_query = getPoiSelectDataTableQuery($access_token);
//         $element_id_found = executePoiCheckIfElementIdExists($list_query, $element_id);

//         if ($element_id_found == 'true') {
//             $query = getPoiSelectDataTableByUsernameAndFilenameQuery($element_id);
//             $result = executePoiSelectDataTableByElementIdQuery($query);
//         } else {
//             $result = json_encode(array(
//                 "status" => "ko",
//                 "error" => "No element_id found or access token is not valid!"
//             ));
//         }

//////////////////////////////
        $element_id_found='false';
        
        // get delegated username
        $iot_app_username = getUploaderUsername($access_token);
        
        $element_Ids_delegated_query = getPoiUserDelegelatedListQuery($iot_app_username);
        $element_Ids_delegated = getElementIdsByDelegatedUsers($element_Ids_delegated_query, $access_token);
        
        if(in_array($element_id, $element_Ids_delegated)){
            $element_id_found='true';
        }
        
        if ($element_id_found == 'true') {
            $query = getPoiSelectDataTableByUsernameAndFilenameQuery($element_id);
            $result = executePoiSelectDataTableByElementIdQuery($query);
        } else {
            $result = json_encode(array(
                "status" => "ko",
                "error" => "No element_id found or access token is not valid!"
            ));
        }
        
    } else {
        $result = json_encode(array(
            "status" => "ko",
            "error" => "elementId has not been provided!"
        ));
    }
} else {
    $result = json_encode(array(
        "status" => "ko",
        "error" => "access_token has not been provided!"
    ));
}

header('Content-type: application/json');
echo $result;
exit();

?>