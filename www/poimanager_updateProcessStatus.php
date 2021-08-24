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
// include 'datatablemanager_APICient.php';
// include 'datatablemanager_APIQueryManager.php';
// $configs = parse_ini_file('datatablemanager_config.ini.php');

$element_id=$_GET['elementId'];
$status=$_GET['status'];
header('Content-type: application/json');

if (isset($element_id) && $element_id!="") {
    $query = getPoiUpdateProcessStatusQuery($element_id,$status);
    $result = executePoiUpdateProcessStatusQuery($query);
    
    if($result=="ok"){
        $response=array();
        $response["status"] = "ok";
        $response["message"] = 'The status of elementId: ' . $element_id . ' updated to '.$status.' successfully!';
    }else{
        $response=array();
        $response["status"] = "ko";
        $response["message"] = $result;
    }
    echo json_encode($response);
    exit;
} else {
    $response=array();
    $response["status"] = "ko";
    $response["message"] = "No Element ID found!";
    echo json_encode($response);
    exit;
}

?>