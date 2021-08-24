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
include_once 'datatablemanager_sqlClient.php';
include_once 'datatablemanager_sqlQueryManager.php';
include_once 'datatablemanager_APICient.php';
include_once 'datatablemanager_APIQueryManager.php';
$response=array();

$element_id=$_GET['elementId'];
$access_token=$_GET['access_token'];

if (isset($element_id) && $element_id != "") {
    if (isset($access_token) && $access_token != "") {
        // mySql
        $delete_mySql_query = getDeleteElementIdQuery($element_id);
        $mySql_result = executeDeleteElementIdQuery($delete_mySql_query);

        // Ownership table
        
        if($mySql_result=='Record deleted successfully'){
        $delete_ownership_query = getDeleteElementIdOwnershipQuery(rawurlencode($element_id), rawurlencode($access_token));
        $ownership_result = executeDeleteElementIdOwnershipQuery($delete_ownership_query);

        if ($ownership_result == '1') {
            $response["status"] = "ok";
            $response["message"] = 'Element ID: ' . $element_id . ' deleted successfully!';
        } else {
            $response["status"] = "ko";
            $response["message"] = 'Error: ' . $ownership_result;
        }
        
        }else{
            $response["status"] = "ko";
            $response["message"] = 'Error: ' . $mySql_result;
        }

        header('Content-type: application/json');
        echo json_encode($response);
        exit();
    } else {
        $response["status"] = "ko";
        $response["message"] = 'Error: No access_token provided!';
        header('Content-type: application/json');
        echo json_encode($response);
        exit();
    }
} else {
    $response["status"] = "ko";
    $response["message"] = "Error: No element_id provided!";
    header('Content-type: application/json');
    echo json_encode($response);
    exit();
}

?>