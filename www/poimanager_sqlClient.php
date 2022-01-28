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
include_once 'poimanager_myUtil.php';

function getPoiSelectElementIdsToProcessQuery(){
    return "select distinct(element_id) from poi_data where status!='YES'";
}

function getPoiDeviceSheetNameQuery($element_id,$device_name){
    return "select distinct(sheet_name) from sheet_data where element_id='".$element_id."' and value_name='".$device_name."'";  
}

function getPoiDeviceLatQuery($element_id, $device_name){
    return "select distinct(lat) from sheet_data where element_id='".$element_id."' and value_name='".$device_name."'";
}

function getPoiDeviceLonQuery($element_id, $device_name){
    return "select distinct(lon) from sheet_data where element_id='".$element_id."' and value_name='".$device_name."'";
}


function getPoiDeateObsrevedforRowsQuery($element_id){
    return "select distinct(dateObserved) from `sheet_data` where element_id='".$element_id."'";
}

function getPoiDeviceListQuery($element_id){
    return "select distinct(value_name) from `sheet_data` where element_id='".$element_id."'";
}

function getPoiDateObservedTypeQuery($element_id){
    return "SELECT dateObserved_type FROM `sheet_data` WHERE element_id='".$element_id."'";
}

function getPoiRowInstanceQuery($element_id,$device_name,$model_features){
    return "SELECT value FROM `sheet_data` WHERE element_id='".$element_id."' AND value_type_name in ('" . implode("','", $model_features) . "') AND value_name='".$device_name."'";
}

function getPoiElementsToProcessQuery(){
    return "SELECT element_id FROM `sheet_data` WHERE processed='No'";
}

function getPoiStatusElementQuery($element_id){
    return "select distinct(status) from poi_data where element_id='".$element_id."'";
}

function getPoiOrgQuery($element_id){
    return "select distinct(organization) from poi_data where element_id='".$element_id."'";
}

function getPoiUpdateProcessStatusQuery($element_id,$status){
    return "UPDATE `poi_data` SET status='".$status."' WHERE element_id='".$element_id."'";
}

function getPoiUpdateAllProcessStatusQuery($status){
    return "UPDATE `sheet_data` SET processed='".$status."'";
}

function getPoiDeleteElementIdQuery($element_id){
    return "DELETE from `sheet_data` where element_id='".$element_id."'";
}

function getPoiCheckIfValueNameExistQuery($value_name){
    return "select id from value_name where value='$value_name'";
}


function getPoiSelectDataTableByUsernameAndFilenameQuery($element_id){
    return "SELECT * FROM `poi_data` WHERE element_id='".$element_id."'";
}

function getPoiInsertValueNameQuery($value_name) {
        return "INSERT INTO value_name(value) VALUES('$value_name')";
}

function getPoiInsertCellQuery($file_name,$elementId, $suri,$sheet_name,$coord_type,$center_lat, $center_lon, $radius, $nature, $sub_nature,$name,$abbreviation,$descriptionShort,$descriptionLong,$phone,$fax,$url,$email,$refPerson,$secondPhone,$secondFax,$secondEmail,$secondCivicNumber,$secondStreetAddress,$notes,$timetable,$photo,$other1,$other2,$other3,$postalcode,$province,$city,$streetAddress,$civicNumber,$latitude,$longitude,$lang,$org,$calculated_poi_lat,$calculated_poi_lon){
    $upload_date_time=get_poi_current_time_dat_utc_format();
    $query="INSERT INTO poi_data ";
    $query.="(`status`,`element_id`,`file_name`,`suri`,`sheet_name`,`coord_type`,`center_lat`,`center_lon`,`radius`,`nature`,`sub_nature`,`name`,`abbreviation`,`descriptionShort`,`descriptionLong`,`phone`,`fax`,`url`,`email`,`refPerson`,`secondPhone`,`secondFax`,`secondEmail`,`secondCivicNumber`,`secondStreetAddress`,`notes`,`timetable`,`photo`,`other1`,`other2`,`other3`,`postalcode`,`province`,`city`,`streetAddress`,`civicNumber`,`latitude`,`longitude`,`upload_date_time`,`language`,`organization`,`calculated_lat`,`calculated_lon`) ";
    $query.=" VALUES('NO','$elementId','$file_name','$suri','$sheet_name','$coord_type','$center_lat','$center_lon','$radius','$nature','$sub_nature','$name','$abbreviation','$descriptionShort','$descriptionLong','$phone','$fax','$url','$email','$refPerson','$secondPhone','$secondFax','$secondEmail','$secondCivicNumber','$secondStreetAddress','$notes','$timetable','$photo','$other1','$other2','$other3','$postalcode','$province','$city','$streetAddress','$civicNumber','$latitude','$longitude','$upload_date_time','$lang','$org','$calculated_poi_lat','$calculated_poi_lon')";
    return $query;
}

function getPoiSelectUploadedFilesQuery(){
    return "select distinct(file_name) from poi_data";
}

?>