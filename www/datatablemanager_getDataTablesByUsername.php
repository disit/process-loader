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
include 'datatablemanager_APICient.php';
include 'datatablemanager_APIQueryManager.php';

$access_token=$_GET['access_token'];

if (isset($access_token) && $access_token!="") {
    $access_token = $_GET['access_token'];
    $query = getSelectDataTablesUsernameQuery($access_token);
    $result = executeSelectDataTablesByUsernameQuery($query);
    echo $result;
} else {
    $response["status"] = "false";
    $response["message"] = "No access token found!";
}

// exit;

?>