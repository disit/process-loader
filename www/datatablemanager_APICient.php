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

$configs = parse_ini_file('datatablemanager_config.ini.php');
$valueTypeValueUnitAction=$configs['valueTypeValueUnitAction'];
$nodered=$configs['valueTypeValueUnitNodered'];
$ownershipListApiUrl=$configs['ownershipListApiUrl'];
$ownershipLimitApiUrl=$configs['ownershipLimitApiUrl'];
$processLoaderContextBrokerURI=$configs['processLoaderContextBrokerURI'];
$ownershipDeleteApiUrl=$configs['ownershipDeleteApiUrl'];
$delegationUrl=$configs['delegationUrl'];
$delegationOrgUrl=$configs['delegationOrgUrl'];

function getUsernameToDelegateQuery($org){
    return $GLOBALS['delegationOrgUrl'].$org;
}

function getDataTablesLimitQuery($access_token){
    return $GLOBALS['ownershipLimitApiUrl'].$access_token;
}

function getCbQuery(){
    return $GLOBALS['processLoaderContextBrokerURI'];
}

function getSelectDataTableQuery($access_token){
    return $GLOBALS['ownershipListApiUrl'].$access_token;
}

// function getDeleteElementIdOwnershipQuery($element_id){
function getDeleteElementIdOwnershipQuery($element_id,$access_token){
    return $GLOBALS['ownershipDeleteApiUrl'].'elementId='.$element_id.'&accessToken='.$access_token;
}

function registerelementTypeIDQuery($elementId,$elementType,$elementName,$elementUrl) {

    $params=array(
        'elementId'=>$elementId,
        'elementType'=>$elementType,
        'elementName'=>$elementName,
        'elementUrl'=>$elementUrl
    );
    return json_encode( $params );
}

function getValueTypeAndValueNamesQuery($access_token) {
    return 'action='.$GLOBALS['valueTypeValueUnitAction'].'&token='.$access_token.'&nodered='.$GLOBALS['nodered'];
}

function getDelegelateuserQuery($username){
    return $GLOBALS['delegationUrl'].$username.'/delegation?sourceRequest=datatablemanager';
}

function getUserDelegelatedListQuery($username){
    return $GLOBALS['delegationUrl'].$username.'/delegated?sourceRequest=datatablemanager&elementType=DataTableID';
}

?>