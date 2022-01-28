<?php
/*
 * Resource Manager - Process Loader
 * Copyright (C) 2018 DISIT Lab http://www.disit.org - University of Florence
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
// header("Content-Type:application/json");
include_once 'datatablemanager_APICient.php';
include_once 'datatablemanager_APIQueryManager.php';
include_once 'datatablemanager_sqlClient.php';
include_once 'datatablemanager_sqlQueryManager.php';

$access_token = $_GET['access_token'];

if (isset($access_token) && $access_token != "") {

    // $query = getSelectDataTableQuery($access_token);
    // $element_Ids = executeGetLementIds($query);

    // get delegated username
    $iot_app_username = getUploaderUsername($access_token);
    $iot_app_username_org=getOrg($iot_app_username, $ldapServer, $ldapParamters);

    $element_Ids_delegated_query = getUserDelegelatedListQuery($iot_app_username);
    $element_Ids_delegated = getElementIdsByDelegatedUsers($element_Ids_delegated_query, $access_token);
    
    $query = getSelectElementIdsToProcessQuery($iot_app_username_org);
    $result = executeElementIdsToProcessQuery($query, $element_Ids_delegated);
    
} else {
    $response = array();
    $response["status"] = "false";
    $response["message"] = "No access token found!";
    echo $response;
}

header('Content-type: application/json');
echo $result;
exit();

?>