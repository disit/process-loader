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
include_once 'poimanager_APICient.php';
include_once 'poimanager_APIQueryManager.php';
include_once 'datatablemanager_APIQueryManager.php';
ini_set('max_execution_time', 0);
$access_token=$_GET['access_token'];
if (isset($access_token) && $access_token!="") {
    
    // get delegated username
    $iot_app_username = getUploaderUsername($access_token);
    $iot_app_username_org=getOrg($iot_app_username, $ldapServer, $ldapParamters);

    $element_Ids_delegated_query = getPoiUserDelegelatedListQuery($iot_app_username);
    $element_Ids_delegated = getElementIdsByDelegatedUsers($element_Ids_delegated_query, $access_token);
    
    $query = getPoiSelectElementIdsToProcessQuery($iot_app_username_org);
    $result = executeElementIdsToProcessQuery($query, $element_Ids_delegated);
    
//     $query = getPoiSelectDataTableQuery($access_token);
//     $result = executePoiSelectDataTablesByUsernameQuery($query);
    echo $result;
} else {
    $response=array();
    $response["status"] = "false";
    $response["message"] = "No valid access token found!";
    echo $response;
}

// exit;

?>