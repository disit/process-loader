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
include 'datatablemanager_sqlClient.php';
include 'datatablemanager_sqlQueryManager.php';
include 'datatablemanager_APICient.php';
include 'datatablemanager_APIQueryManager.php';
$configs = parse_ini_file('datatablemanager_config.ini.php');

$access_token=$_GET['access_token'];

if (isset($access_token) && $access_token!="") {
    
//     $dateObserved_type_query=getDateObservedTypeQuery($elementId);
//     $dateObserved_type=executeGetDateObservedTypeQuery($dateObserved_type_query);

//     $dateObserved_type_query=getDateObservedTypeQuery($element_id);
//     $dateObserved_type=executeGetDateObservedType($dateObserved_type_query);

//     $device_list_query=getDeviceListQuery($element_id);
//     $device_list=executeGetDeviceListQuery($device_list_query);
    
    $query = getElementsToProcessQuery();
    $result = executeGetElementsToProcessQuery($query);
    
} else {
    $response["status"] = "false";
    $response["message"] = "No Username or filename found!";
}

echo $result; exit;

?>