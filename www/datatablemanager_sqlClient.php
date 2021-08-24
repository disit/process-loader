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
include_once 'datatablemanager_myUtil.php';

function getSelectElementIdsToProcessQuery($org){
    return "select distinct(element_id) from sheet_data where processed!='YES' and organization='".$org."'";
}

function getDeviceSheetNameQuery($element_id,$device_name){
    return "select distinct(sheet_name) from sheet_data where element_id='".$element_id."' and value_name='".$device_name."'";  
}

function getDeviceLatQuery($element_id, $device_name, $coord_type)
{
    if ($coord_type != 'address') {
        return "select distinct(lat) from sheet_data where element_id='" . $element_id . "' and value_name='" . $device_name . "'";
    } else {
        // return the first non-empty lat,lon
        return "select lat from sheet_data where element_id='" . $element_id . "' and value_name='" . $device_name . "'";
    }
}

function getDeviceLonQuery($element_id, $device_name, $coord_type)
{
    if ($coord_type != 'address') {
        return "select distinct(lon) from sheet_data where element_id='" . $element_id . "' and value_name='" . $device_name . "'";
    } else {
        // return the first non-empty lat,lon
        return "select lon from sheet_data where element_id='" . $element_id . "' and value_name='" . $device_name . "'";
    }
}

function getDeateObsrevedforRowsQuery($element_id){
    return "select distinct(dateObserved) from `sheet_data` where element_id='".$element_id."'";
}

function getDeviceListQuery($element_id){
    return "select distinct(value_name) from `sheet_data` where element_id='".$element_id."'";
}

function getDateObservedTypeQuery($element_id){
    return "SELECT dateObserved_type FROM `sheet_data` WHERE element_id='".$element_id."'";
}

function getRowInstanceQuery($element_id,$device_name,$model_features){
    return "SELECT value FROM `sheet_data` WHERE element_id='".$element_id."' AND value_type_name in ('" . implode("','", $model_features) . "') AND value_name='".$device_name."'";
}

function getDeviceAddressLatsQuery($element_id, $device_name){
    return "SELECT distinct(lat) FROM `sheet_data` WHERE element_id='".$element_id."' AND value_name='".$device_name."'";
}

function getDeviceAddressLonsQuery($element_id, $device_name){
    return "SELECT distinct(lon) FROM `sheet_data` WHERE element_id='".$element_id."' AND value_name='".$device_name."'";
}

function getDeviceAddressWarningsQuery($element_id, $device_name){
    return "SELECT distinct(address_warning) FROM `sheet_data` WHERE element_id='".$element_id."' AND value_name='".$device_name."'";
}

function executeGetDeviceAddressCoords($query){
    
    $get_data_query = mysqli_query($GLOBALS['conn'], $query) or die(mysqli_error($query));
    $coord_list=array();
    if(mysqli_num_rows($get_data_query)!=0){
        while($r = mysqli_fetch_array($get_data_query)){
            extract($r);
            array_push($coord_list,$r[0]);
        }
    }
    return $coord_list;
}


function executeGetDeviceAddressWarnings($query){
    $get_data_query = mysqli_query($GLOBALS['conn'], $query) or die(mysqli_error($query));
    $warning_list=array();
    if(mysqli_num_rows($get_data_query)!=0){
        while($r = mysqli_fetch_array($get_data_query)){
            extract($r);
            array_push($warning_list,$r[0]);
        }
    }
    return $warning_list;
}

function getElementsToProcessQuery(){
    return "SELECT element_id FROM `sheet_data` WHERE processed='No'";
}

function getStatusElementQuery($element_id){
    return "select distinct(processed) from sheet_data where element_id='".$element_id."'";  
}

function getOrgQuery($element_id){
    return "select distinct(organization) from sheet_data where element_id='".$element_id."'";
}

function getUpdateProcessStatusQuery($element_id,$status){
    return "UPDATE `sheet_data` SET processed='".$status."' WHERE element_id='".$element_id."'";
}

function getUpdateAllProcessStatusQuery($status){
    return "UPDATE `sheet_data` SET processed='".$status."'";
}

function getDeleteElementIdQuery($element_id){
    return "DELETE from `sheet_data` where element_id='".$element_id."'";
}

// function getPoiDeleteElementIdQuery($element_id){
//     return "DELETE from `poi_data` where element_id='".$element_id."'";
// }

function getSelectDataTableByUsernameAndFilenameQuery($element_id){
    return "SELECT * FROM `sheet_data` WHERE element_id='".$element_id."'";
}

function getCheckIfValueNameExistQuery($value_name){
    return "select id from value_name where value='$value_name'";
}

function getInsertValueNameQuery($value_name) {
        return "INSERT INTO value_name(value) VALUES('$value_name')";
}

function getInsertCellQuery($elementId,$value_name_type,$value_name,$sheet_name,$value_type_name,$value_type, $value_unit,$file_name,$cell_value,$sheet_name_type,$lat,$lon,$nature,$sub_nature,$observed_date,$observed_date_type,$data_type,$coord_type,$context_broker,$lat_row_for_file,$lon_row_for_file,$lat_row,$lon_row,$org,$observed_date_for_row_header,$address_warning){
    $upload_date_time=get_current_time_dat_utc_format();
    $query="INSERT INTO sheet_data ";
    $query.="(`element_id`,`value_name_type`,`value_name`,`value_type_name`,`value_type`,`file_name`,`sheet_name`,`value`,`sheet_name_type`,`upload_date_time`,`lat`,`lon`,`nature`,`sub_nature`,`dateObserved`,`value_unit`,`dateObserved_type`,`data_type`,`processed`,`coord_type`,`context_broker`,`lat_row_file`,`lon_row_file`,`lat_row_col`,`lon_row_col`,`organization`,`dateObserved_column`,`address_warning`) ";
    $query.=" VALUES('$elementId','$value_name_type','$value_name','$value_type_name','$value_type','$file_name','$sheet_name','$cell_value','$sheet_name_type','$upload_date_time','$lat','$lon','$nature','$sub_nature','$observed_date','$value_unit','$observed_date_type','$data_type','No','$coord_type','$context_broker','$lat_row_for_file','$lon_row_for_file','$lat_row','$lon_row','$org','$observed_date_for_row_header','$address_warning')";
    return $query;
}

function getUsernameToDelegateQuery($org){
    return "select username from user where organization='".$org."'; ";
}

function getDtmSelectUploadedFilesQuery(){
    return "select distinct(file_name) from sheet_data";
}

function getDtmDelegatorUsernameQuery($org){
    return "select username from sheet_data where organization='".$org."'; ";
}

?>