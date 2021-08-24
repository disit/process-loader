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
include('config.php'); // Includes Login Script
require 'sso/autoload.php';
use Jumbojett\OpenIDConnectClient;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$oidc = new OpenIDConnectClient($ssoEndpZoint, $ssoClientId, $ssoClientSecret);
$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));

try {
    $tkn = $oidc->refreshToken($_SESSION['refreshToken']);
} catch (Exception $e) {
    print_r($e);
}

$tk= $tkn->access_token;

function getAccessToken(){
return $GLOBALS['tk'];
}

function checkLdapOrganization($connection, $userDn, $baseDn)
{

    $result = ldap_search($connection, $baseDn, '(&(objectClass=organizationalUnit)(l=' . $userDn . '))');
    
    // $result = ldap_search($connection, $baseDn, '(&(objectClass=organizationalUnit))');
    $entries = ldap_get_entries($connection, $result);
    $orgsArray = [];
    foreach ($entries as $key => $value)
    {
        if(is_numeric($key))
        {
            if($value["ou"]["0"] != '' )
            {
                array_push($orgsArray, $value["ou"]["0"]);
            }
        }
    }
    
    if (sizeof($orgsArray) > 1 || sizeof($orgsArray)==0) {
        return "DISIT";
    } else {
        return $orgsArray[0];
    }
    // return "";
}

// function getDataType($value_unit){
//     $configs = parse_ini_file('datatablemanager_config.ini.php');
//     $float_datatype = explode(",",$configs['float_datatype']);
//     $time_datatype = explode(",",$configs['time_datatype']);
//     $string_datatype = explode(",",$configs['string_datatype']);
//     $timestamp_datatype= explode(",",$configs['timestamp_datatype']);
//     $integer_datatype = explode(",",$configs['integer_datatype']);
//     $binary_datatype = explode(",",$configs['binary_datatype']);
//     $url_datatype = explode(",",$configs['url_datatype']);
    
//     $data_type="";
  
//         if(in_array($value_unit, $float_datatype)){
//             $data_type='float';
//         }else if(in_array($value_unit, $time_datatype)){
//             $data_type='time';
//         }else if(in_array($value_unit, $string_datatype)){
//             $data_type='string';
//         }else if(in_array($value_unit, $timestamp_datatype)){
//             $data_type='timestamp';
//         }else if(in_array($value_unit, $integer_datatype)){
//             $data_type='integer';
//         }else if(in_array($value_unit, $binary_datatype)){
//             $data_type='binary';
//         }else if(in_array($value_unit, $url_datatype)){
//             $data_type='url';
//         }else{
//             $data_type='string';
//         }
//     return $data_type;
// }

function getIndices($all_headers_array, $value_name_types_array){
    $indices=array();
//     var_dump($all_headers_array);
    foreach ($value_name_types_array as $value_name){
        for ($index=0;$index<count($all_headers_array); $index++) {
            if($all_headers_array[$index]==$value_name){
                array_push($indices,$index);  
            }
        }
    }
    return $indices;
}


function getDofDataTypes($data_types,$value_name_types_array){
    
    foreach ($value_name_types_array as $value){
        unset($data_types[array_search($value, $value_name_types_array)]);
    }
    return $data_types;
}


function has_dupes($array) {
    $dupe_array = array();
    foreach ($array as $val) {
        if (++$dupe_array[$val] > 1) {
            return 'true';
        }
    }
    return 'false';
}

function getDataTypes($target_dir, $file,$dateObserved_column)
{
    require_once ('datatablemanager_SimpleXLSX.php');
    $xlsx = SimpleXLSX::parse($target_dir . $file);
    $sheet_rows = $xlsx->rows(0);
    $cols = $sheet_rows[0];
    $sheetNames = $xlsx->sheetNames();
    $data_types=array();
    $dateObserved_type_row=false;
    $sheetZeroData = $xlsx->rows(0);
    $all_headers_array = $sheetZeroData[0];
    
    if(count($dateObserved_column)!=0){
        $dateObserved_type_row=true;
    for($index=0;$index<count($all_headers_array);$index++){
        if($all_headers_array[$index]==$dateObserved_column){
            $dateObserved_column_index=$index;
            break;
        }
    }
    }
    
    for ($col_index = 0; $col_index < count($cols); $col_index ++) {
        $col_sheet_values = array();
        for ($sheetIndex = 0; $sheetIndex < count($sheetNames); $sheetIndex ++) {
            $sheet_rows = $xlsx->rows($sheetIndex);
            for ($rowIndex = 1; $rowIndex < count($sheet_rows); $rowIndex ++) {
                $sheet_row = $sheet_rows[$rowIndex];
                $sheet_row_col = $sheet_row[$col_index];
                array_push($col_sheet_values,$sheet_row_col);
            }
        }
        
        if($dateObserved_type_row== true && $col_index==$dateObserved_column_index){
            array_push($data_types,'time');
        }else{
            array_push($data_types,get_data_type($col_sheet_values));
        }
        
    }
    return $data_types;
}

function get_data_type($col_values){
    
    $col_data_types=array();
    $data_type="";
    
    foreach ($col_values as $value){
        array_push($col_data_types,gettype($value)=='double' ? 'float' : gettype($value));
    }
    
    //if all data types are the same
    if(count(array_unique($col_data_types)) === 1){
        $data_type= $col_data_types[0];
    }else{
        
        if(in_array('string', $col_data_types)&& !in_array(null, $col_values)){
            $data_type='string';
        }elseif((in_array('float', $col_data_types)&& in_array(null, $col_values))||(in_array('float', $col_data_types)&& in_array('integer', $col_data_types))){
            $data_type= 'float';
        }elseif (in_array('integer', $col_data_types)&& in_array(null, $col_values)){
            $data_type= 'integer';
        }else{
            $data_type='boolean';
        }
    }
    return $data_type;
}

function getDataTableJson($conn,$query,$dateObserved_type,$model_features,$model_features_value_types,$model_features_value_units,$device_list,$headerValueTypes,$headerValueUnits,$headers,$headerDataTypes,$model_features_data_types){
   
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $cell_in_db = mysqli_fetch_array($get_data_query);
    $element_id = $cell_in_db['element_id'];
    $processed = $cell_in_db['processed'];
    $file_name = substr(explode("|", $element_id)[0], 0, strpos(explode("|", $element_id)[0], "."));
    $upload_timestamp = explode("|", $element_id)[1];
    $model_name = str_replace(" ", "", $file_name . "_" . $upload_timestamp);
    $model_type = $cell_in_db['nature'] . "_" . $cell_in_db['sub_nature'];
    $file_name = $cell_in_db['file_name'];
    $upload_date_time = $cell_in_db['upload_date_time'];
    $lat = $cell_in_db['lat'];
    $lon = $cell_in_db['lon'];
    $nature = $cell_in_db['nature'];
    $sub_nature = $cell_in_db['sub_nature'];
    $sheet_name_type = $cell_in_db['sheet_name_type'];
    $value_name_type = $cell_in_db['value_name_type'];
    $context_broker = $cell_in_db['context_broker'];
    $coord_type=$cell_in_db['coord_type'];
    $lat_row_file=$cell_in_db['lat_row_file'];
    $lon_row_file=$cell_in_db['lon_row_file'];
    $lat_row_col=$cell_in_db['lat_row_col'];
    $lon_row_col=$cell_in_db['lon_row_col'];
    $dateObserved_column=$cell_in_db['dateObserved_column'];
    $address_warning=$cell_in_db['address_warning'];

//     echo $lat_row_file; 
//     echo $lon_row_file;
//     die();
    
    $result = array(
        'result' => 'OK',
        'code' => '200'
    );

    if($dateObserved_type=='file'){
    $result['File'] = array(
        'file_name' => $file_name,
        'upload_date_time' => $upload_date_time,
        'dateObserved_type' => $dateObserved_type,
        'sheet_name_type' => $sheet_name_type,
        'coord_type'=>$coord_type,
        'headers' => implode("|", $headers),
        'header_value_types' => implode("|", $headerValueTypes),
        'header_value_units' => implode("|", $headerValueUnits),
        'header_data_types' => implode("|", $headerDataTypes),
        'processed' =>$processed
    );
    }else if($dateObserved_type=='row'){
        $result['File'] = array(
            'file_name' => $file_name,
            'upload_date_time' => $upload_date_time,
            'dateObserved_type' => $dateObserved_type,
            'dateObserved_column' => $dateObserved_column,
            'sheet_name_type' => $sheet_name_type,
            'coord_type'=>$coord_type,
            'headers' => implode("|", $headers),
            'header_value_types' => implode("|", $headerValueTypes),
            'header_value_units' => implode("|", $headerValueUnits),
            'header_data_types' => implode("|", $headerDataTypes),
            'processed' =>$processed
        );
    }

    $values = array();
    for ($model_feature_index = 0; $model_feature_index < count($model_features); $model_feature_index ++) {

        $value = array();

        if($dateObserved_type=='row' && $model_features[$model_feature_index]==$dateObserved_column){
        $value['name'] = "dateObserved";
        $value['value_type'] = 'timestamp';
        $value['value_unit'] = 'timestamp';
        $value['data_type'] = 'time';
        }else{
            $value['name']=$model_features[$model_feature_index];
            $value['value_type'] = $model_features_value_types[$model_feature_index];
            $value['value_unit'] = $model_features_value_units[$model_feature_index];
            $value['data_type'] = $model_features_data_types[$model_feature_index];
        }
        array_push($values, $value);
    }

    if ($dateObserved_type == 'file') {

        $value['name'] = 'dateObserved';
        $value['value_type'] = 'timestamp';
        $value['value_unit'] = 'timestamp';
        $value['data_type'] = 'time';
        array_push($values, $value);

        if ($coord_type == 'file') {
            $result['Model'] = array(
                'name' => $model_name,
                'type' => 'DataTable',
                'element_id' => $element_id,
                'name' => $element_id,
                'dateObserved' => $cell_in_db['dateObserved'],
                'nature' => $nature,
                'sub_nature' => $sub_nature,
                'lat' => $lat,
                'lon' => $lon,
                'description' => $file_name,
                'value_name_type' => $value_name_type,
                'context_broker' => $context_broker,
                'Values' => $values
            );
        } else if ($coord_type == 'address') {
            $value['name'] = 'latitude';
            $value['value_type'] = 'latitude';
            $value['value_unit'] = 'deg';
            $value['data_type'] = 'float';
            array_push($values, $value);

            $value['name'] = 'longitude';
            $value['value_type'] = 'longitude';
            $value['value_unit'] = 'deg';
            $value['data_type'] = 'float';
            array_push($values, $value);

            $result['Model'] = array(
                'name' => $model_name,
                'type' => $model_type,
                'element_id' => $element_id,
                'name' => $element_id,
                'dateObserved' => $cell_in_db['dateObserved'],
                'nature' => $nature,
                'lat' => $cell_in_db['lat_row_file'],
                'lon' => $cell_in_db['lon_row_file'],
                'nature' => $nature,
                'sub_nature' => $sub_nature,
                'description' => $file_name,
                'value_name_type' => $value_name_type,
                'context_broker' => $context_broker,
                'Values' => $values
            );
        } else {
            $result['Model'] = array(
                'name' => $model_name,
                'type' => $model_type,
                'element_id' => $element_id,
                'name' => $element_id,
                'dateObserved' => $cell_in_db['dateObserved'],
                'nature' => $nature,
                'lat' => $cell_in_db['lat_row_file'],
                'lon' => $cell_in_db['lon_row_file'],
                'nature' => $nature,
                'sub_nature' => $sub_nature,
                'description' => $file_name,
                'value_name_type' => $value_name_type,
                'context_broker' => $context_broker,
                'Values' => $values
            );
        }
    } else if ($dateObserved_type == 'row') {

        if ($coord_type == 'file') {

            $result['Model'] = array(
                'name' => $model_name,
                'type' => $model_type,
                'element_id' => $element_id,
                'nature' => $nature,
                'sub_nature' => $sub_nature,
                'lat' => $lat,
                'lon' => $lon,
                'description' => $file_name,
                'value_name_type' => $value_name_type,
                'context_broker' => $context_broker,
                'Values' => $values
            );
        } else if ($coord_type == 'address') {

            $value['name'] = 'latitude';
            $value['value_type'] = 'latitude';
            $value['value_unit'] = 'deg';
            $value['data_type'] = 'float';
            array_push($values, $value);

            $value['name'] = 'longitude';
            $value['value_type'] = 'longitude';
            $value['value_unit'] = 'deg';
            $value['data_type'] = 'float';
            array_push($values, $value);

            $result['Model'] = array(
                'name' => $model_name,
                'type' => $model_type,
                'element_id' => $element_id,
                'nature' => $nature,
                'sub_nature' => $sub_nature,
                'lat' => $cell_in_db['lat_row_file'],
                'lon' => $cell_in_db['lon_row_file'],
                'description' => $file_name,
                'value_name_type' => $value_name_type,
                'context_broker' => $context_broker,
                'Values' => $values
            );
        } else {
            $result['Model'] = array(
                'name' => $model_name,
                'type' => $model_type,
                'element_id' => $element_id,
                'nature' => $nature,
                'sub_nature' => $sub_nature,
                'lat' => $cell_in_db['lat_row_file'],
                'lon' => $cell_in_db['lon_row_file'],
                'description' => $file_name,
                'value_name_type' => $value_name_type,
                'context_broker' => $context_broker,
                'Values' => $values
            );
        }
    }

    for ($device_index = 0; $device_index < count($device_list); $device_index ++) {

        $device_sheet_name_query = getDeviceSheetNameQuery($element_id, $device_list[$device_index]);
        $device_sheet_name = executeGetDeviceSheetNameQuery($device_sheet_name_query);

            $device_lat_query = getDeviceLatQuery($element_id, $device_list[$device_index],$coord_type);
            $device_lat = executeGetDeviceLatQuery($device_lat_query,$coord_type);

            $device_lon_query = getDeviceLonQuery($element_id, $device_list[$device_index],$coord_type);
            $device_lon = executeGetDeviceLonQuery($device_lon_query,$coord_type);

            $result['Devices'][$device_index] = array(
                'name' => str_replace(" ", "", $device_list[$device_index]),
                'sheet_name' => $device_sheet_name,
                'lat' => $device_lat,
                'lon' => $device_lon
            );
    }

    //Device instance/////////////////////////////
    for ($device_index = 0; $device_index < count($device_list); $device_index ++) {

        $result['Instances'][$device_index]['Values'] = array();
        $device_rows = array();
        $device_rows_index = 0;

        $row_instance_query = getRowInstanceQuery($element_id, $device_list[$device_index], $model_features);
        $device_rows_instances = executeGetRowInstanceQuery($row_instance_query);
        
        if($coord_type=="address"){
            
            $result['Instances'][$device_index]['Warnings'] = array();
            
            $device_address_lats_query = getDeviceAddressLatsQuery($element_id, $device_list[$device_index]);
            $device_address_lats = executeGetDeviceAddressCoords($device_address_lats_query);
            
            $device_address_lons_query = getDeviceAddressLonsQuery($element_id, $device_list[$device_index]);
            $device_address_lons = executeGetDeviceAddressCoords($device_address_lons_query);
        
            $device_address_warnings_query = getDeviceAddressWarningsQuery($element_id, $device_list[$device_index]);
            $device_address_warnings = executeGetDeviceAddressWarnings($device_address_warnings_query);
        
        }
        
        $cell_pointer = 0;
        $device_rows_count = count($device_rows_instances) / count($model_features);

        for ($device_rows_index = 0; $device_rows_index < $device_rows_count; $device_rows_index ++) {
            $row = array_slice($device_rows_instances, $cell_pointer, count($model_features));
            
            for($row_item_index=0;$row_item_index<count($row);$row_item_index++){
                $row[$row_item_index]=str_replace("(", "[", $row[$row_item_index]);
                $row[$row_item_index]=str_replace(")", "]", $row[$row_item_index]);
            }
            
            $row_string = implode("|", $row);
            
            if($dateObserved_type == 'file'){
                $row_string.="|";
                $row_string.=$cell_in_db['dateObserved'];
            }
            
            if($coord_type=="address"){
                $row_string.="|";
                $row_string.=$device_address_lats[$device_rows_index];
                
                $row_string.="|";
                $row_string.=$device_address_lons[$device_rows_index];
            }
            
            array_push($device_rows, $row_string);
            $cell_pointer = $cell_pointer + count($model_features);
            $result['Instances'][$device_index]['Values'][$device_rows_index] = $row_string;
            
            if($coord_type=="address"){
                $result['Instances'][$device_index]['Warnings'][$device_rows_index]=$device_address_warnings[$device_rows_index];
            }
        }
    }

    return $result;
}

function getModelFeatureDataTypes($model_features,$headers,$headerDataTypes){
    
    $model_features_data_types=array();
    
    foreach($model_features as $value){
        $model_feature_index_in_headers = array_search($value, $headers);
        $model_feature_data_type=$headerDataTypes[$model_feature_index_in_headers];
        array_push($model_features_data_types,$model_feature_data_type);
    }
    return $model_features_data_types;
}

function getModelFeatureValueUnits($model_features,$headers,$headerValueUnits){
    
    $model_features_value_units=array();
    
    foreach($model_features as $value){
        $model_feature_index_in_headers = array_search($value, $headers);
        $model_feature_value_unit=$headerValueUnits[$model_feature_index_in_headers];
        array_push($model_features_value_units,$model_feature_value_unit);
    }
    return $model_features_value_units;
}

function getModelFeatureValueTypes($model_features,$headers,$headerValueTypes){
    
    $model_features_value_types=array();
    
    foreach($model_features as $value){
        $model_feature_index_in_headers = array_search($value, $headers);
        $model_feature_value_type=$headerValueTypes[$model_feature_index_in_headers];
        array_push($model_features_value_types,$model_feature_value_type);
    }
    return $model_features_value_types;
}

function updateHeaders($headers,$lat_row_col,$lon_row_col){
    
    if ($key = array_search($lat_row_col, $headers)) {
        unset($headers[$key]);
        }
        
        if ($key = array_search($lon_row_col, $headers)) {
            unset($headers[$key]);
        }
        return $headers;
}

function getModelFeatures($conn, $query,$headers){

    $features=$headers;
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $r = mysqli_fetch_array($get_data_query);
    extract($r);
    
//     var_dump($r);
//     die();

    $value_name_type=$r['value_name_type'];
    $lat_row_col=$r['lat_row_col'];
    $lon_row_col=$r['lon_row_col'];
    $coord_type=$r['coord_type'];
    
    $value_name_type_array=explode("__", $value_name_type);
    $value_name_type_minus_fileName_and_sheet_name_array = array_slice($value_name_type_array, 2, count($value_name_type_array)-2);
    
//     var_dump($headers);
//     die();
    
    foreach($value_name_type_minus_fileName_and_sheet_name_array as $value){
            unset($features[array_search($value, $features)]);
    }

//     var_dump($features);
//     die();
    
    
    if($coord_type=='row'){
            unset($features[array_search($lat_row_col, $features)]);
            unset($features[array_search($lon_row_col, $features)]);
    }
    return array_values($features);
}

function removeElements($array,$index_array_to_remove){
    foreach($index_array_to_remove as $index){
        unset($array[$index]);
    }
    return $array;
}


function getDateObservedType($conn, $query){
    
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $r = mysqli_fetch_array($get_data_query);
    extract($r);
    
    return $r['dateObserved_type'];
}

function getHeaderDataTypes($conn, $query,$header_count){
    
    $data_types=array();
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $header_index=0;
    
    while(($r = mysqli_fetch_array($get_data_query)) &&($header_index<$header_count)){
        extract($r);
        $data_type=$r['data_type'];
        array_push($data_types,$data_type);
        ++$header_index;
    }
    return $data_types;
}

function getHeaderValueUnits($conn, $query,$header_count){
    
    $value_units=array();
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $header_index=0;
    
    while(($r = mysqli_fetch_array($get_data_query)) &&($header_index<$header_count)){
        extract($r);
        $value_unit=$r['value_unit'];
        array_push($value_units,extractStringBetweenTwoCharacters($value_unit,"(",")"));
        ++$header_index;
    }
    return $value_units;
}

function getHeaderValueTypes($conn, $query,$header_count){
    
    $value_types=array();
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $header_index=0;
//     echo $header_count;
//     die();
    while(($r = mysqli_fetch_array($get_data_query)) &&($header_index<$header_count)){
        extract($r);
        $value_type=$r['value_type'];
        array_push($value_types,$value_type);
        ++$header_index;
    }
    return $value_types;
}

function getHeaders($get_data_query){
    
    $header0="";
    $headers=array();
    $r = mysqli_fetch_array($get_data_query);
    
    extract($r);
    $header0=$r['value_type_name'];

    array_push($headers,$header0);
    
    while($r = mysqli_fetch_array($get_data_query)){
        extract($r);
        $header=$r['value_type_name'];
        if(($header!=$header0) &&  (!in_array($header, $headers))){
            array_push($headers,$header);
        }
    }
    return $headers;
}

function extractStringBetweenTwoCharacters($string,$start,$end){
    
    $ini = strpos($string,$start);
    
    if ($ini == 0){
        return "";
    }
    
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

function get_current_time_dat_utc_format(){
    $configs = parse_ini_file('datatablemanager_config.ini.php');
    $timezone = $configs['time_zone'];
    date_default_timezone_set($timezone);
    return date('c', date_timestamp_get(date_create()));
}

function get_current_date() {
    $configs = parse_ini_file('datatablemanager_config.ini.php');
    date_default_timezone_set($configs['time_zone']);
    $date = date('d-m-Y');
    
    return $date;
}

function get_current_time(){
    $configs = parse_ini_file('datatablemanager_config.ini.php');
    date_default_timezone_set($configs['time_zone']);
    $time = date("h:i:sa");
    
    return $time;
}

function castCsvStringToArray($csvString) {
    $cols_string = explode(',', $_POST['final_cols_name_Sheet_name_rest'],true);
    $cols_csv=str_getcsv($csvString[0]);
    
    
    for ($i=0; $i < count($cols_csv); $i++) {
        echo $cols_csv[$i];
    }
}

function getFileHeaders($file_name,$target_dir){

    require_once ('datatablemanager_SimpleXLSX.php');
    $xlsx = SimpleXLSX::parse($target_dir.$file_name);
    return $xlsx->rows(0)[0];
}

function rename_file_in_target_dir($old_name,$new_name){
    
    if(file_exists($new_name))
    {
        echo "Error While Renaming $old_name" ;
    }
    else
    {
        if(rename($old_name, $new_name))
        {
//             echo "Successfully Renamed $old_name to $new_name" ;
        }
        else
        {
//             echo "A File With The Same Name Already Exists" ;
        }
    }
}

function getFormatedFileDateObserved($hidden_observed_date_for_file_name,$observed_date_type,$coord_type,$lat_row_for_file,$lon_row_for_file,$lat_file,$lon_file){

         $formatted_lat="";
        $formatted_lon="";
        
        if($coord_type=="row" || $coord_type=="address"){
            $formatted_lat=$lat_row_for_file;
            $formatted_lon=$lon_row_for_file;
        }else{
            $formatted_lat=$lat_file;
            $formatted_lon=$lon_file;
        }
        
        $observed_date_for_file_name=$hidden_observed_date_for_file_name.':00.000'.toGmtOffset(get_nearest_timezone((float) $formatted_lat,(float) $formatted_lon));
    
        return $observed_date_for_file_name;
}

function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') {
    $timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code)
    : DateTimeZone::listIdentifiers();
    
    if($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {
        
        $time_zone = '';
        $tz_distance = 0;
        
        //only one identifier?
        if (count($timezone_ids) == 1) {
            $time_zone = $timezone_ids[0];
        } else {
            
            foreach($timezone_ids as $timezone_id) {
                $timezone = new DateTimeZone($timezone_id);
                $location = $timezone->getLocation();
                $tz_lat   = $location['latitude'];
                $tz_long  = $location['longitude'];
                
                $theta    = $cur_long - $tz_long;
                $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat)))
                + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                $distance = acos($distance);
                $distance = abs(rad2deg($distance));
                // echo '<br />'.$timezone_id.' '.$distance;
                
                if (!$time_zone || $tz_distance > $distance) {
                    $time_zone   = $timezone_id;
                    $tz_distance = $distance;
                }
                
            }
        }
        return  $time_zone;
    }
    return 'unknown';
}

function toGmtOffset($timezone){
    $userTimeZone = new DateTimeZone($timezone);
    $offset = $userTimeZone->getOffset(new DateTime("now",new DateTimeZone('GMT'))); // Offset in seconds
    $seconds = abs($offset);
    $sign = $offset > 0 ? '+' : '-';
    $hours = floor($seconds / 3600);
    $mins = floor($seconds / 60 % 60);
    $secs = floor($seconds % 60);
    return sprintf("$sign%02d:%02d", $hours, $mins, $secs);
}

function getFileNameOnly($filename){
    
    $filename = explode('_', $filename);
    array_pop($filename);
    $filename = implode('_', $filename);
    
    return $filename;
}

function getWarningArray($target_dir,$file_name) {
    //     echo $file_name;
    require_once ('datatablemanager_SimpleXLSX.php');
    $warnings=array();
    $xlsx = SimpleXLSX::parse($target_dir.$file_name);
    $sheetNames = $xlsx->sheetNames();
    $sheetColHeaders = array();
    
    for ($sheet_number = 0; $sheet_number < count($sheetNames); $sheet_number ++) {
        $sheetColHeaders[$sheet_number] = $xlsx->rows($sheet_number)[0];
    }
    
    if(checkIfFileNameContainsSpecialCharacters($file_name)!=false){
        $special_chars_string=implode(" ", checkIfFileNameContainsSpecialCharacters($file_name));
        array_push($warnings, "Check file name if it contains: <b>". $special_chars_string."</b>");
    }
    
//     if(detectDtmUtf8($file_name)==1){
//         array_push($warnings, "The file name includes non-UTF-8 (e.g., non-English) letters!");
//     }
    
    for ($sheet_number = 0; $sheet_number < count($sheetNames); $sheet_number ++) {
        $sheet_col_header = $sheetColHeaders[$sheet_number];
        for ($sheet_col_header_index = 0; $sheet_col_header_index < count($sheet_col_header); $sheet_col_header_index ++) {
            $special_chars_result = checkIfStringContainsSpecialCharacters($sheet_col_header[$sheet_col_header_index]);
            
            if ($special_chars_result != false) {
                if (in_array('line break', $special_chars_result)) {
                    $sheet_col_header[$sheet_col_header_index] = str_replace(array("\r","\n"), '', $sheet_col_header[$sheet_col_header_index]);
                }
                $special_chars_string = implode(" ", $special_chars_result);
                array_push($warnings, "Check sheet <b>" . $sheetNames[$sheet_number] . "</b>, column header <b>" . $sheet_col_header[$sheet_col_header_index] . "</b>, if it contains: <b>" . $special_chars_string . "</b>");
            }
        }
    }
    
    for ($sheet_number = 0; $sheet_number < count($sheetNames); $sheet_number ++) {
        for ($y = 0; $y < count($sheetNames); $y ++) {
            if ($sheetColHeaders[$sheet_number] != $sheetColHeaders[$y]) {
                array_push($warnings, "Column headers for sheets <b>" . $sheetNames[$sheet_number] . "</b> and <b>" . $sheetNames[$y] . "</b> are not the same");
                break 1;
            }
        }
    }

    // check column headers include non-english letters
//     for ($y = 0; $y < count($sheetNames); $y ++) {
//         $column_headers = $sheetColHeaders[$y];
//         for ($column_header_index = 0; $column_header_index < count($column_headers); $column_header_index ++) {
//             $column_header = $column_headers[$column_header_index];
//             if (detectDtmUtf8($column_header) == 1) {
//                 array_push($warnings, "Column header <b>" . $column_header . "</b> in sheet <b>" . $sheetNames[$y] . "</b> includes non-UTF-8 (e.g., non-English) letters");
//             }
//         }
//     }
    
//     for ($sheetIndex = 0; $sheetIndex < count($sheetNames); $sheetIndex ++) {
//         $sheetData = $xlsx->rows($sheetIndex);
//         for ($rowIndex = 0; $rowIndex < count($sheetData); $rowIndex ++) {
//             $sheetRow = $sheetData[$rowIndex];
//             foreach ($sheetRow as $value) {
//                 if(checkIfStringContainsSpecialCharacters($value)){
//                     array_push($warnings, "The column headers should not contain special characters (For example, blank space, |, @, #, &, %)!");
//                     break 3;
//                 }
//             }
//         }
//     }
    
    
//     for ($sheetIndex = 0; $sheetIndex < count($sheetNames); $sheetIndex ++) {
//         $sheetData = $xlsx->rows($sheetIndex);
//         for ($rowIndex = 0; $rowIndex < count($sheetData); $rowIndex ++) {
//             $sheetRow = $sheetData[$rowIndex];
//             foreach ($sheetRow as $value) {
//                 if($value==""){
//                     array_push($warnings, "The cells should not be empty!");
//                     break 3;
//                 }
//             }
//         }
//     }
    
//     for ($sheet_number = 0; $sheet_number < count($sheetNames); $sheet_number ++) {
//         $sheet_col_header=$sheetColHeaders[$sheet_number];
//         for ($sheet_col_header_index = 0; $sheet_col_header_index< count($sheet_col_header);$sheet_col_header_index++) {
//             if (checkIfStringLengthExceedThreshold($sheet_col_header[$sheet_col_header_index])) {
//                 array_push($warnings, "The length of column headers should not be more than 50 characters!");
//                 break 2;
//             }
//         }
//     }
    
    for ($cell_number = 0; $cell_number < count($sheetNames); $cell_number ++) {
        for ($y = 0; $y < count($sheetNames); $y ++) {
            if (count($sheetColHeaders[$cell_number]) != count($sheetColHeaders[$y])) {
                array_push($warnings, "The number of columns must be the same in all sheets!");
                break 2;
            }
        }
    }
    return $warnings;
}

function checkIfFileNameContainsSpecialCharacters($string) {
    $special_chars=array("|","/","#","@","%","&","?","'","[","]",",");
    $string_contains_special_char='false';
    $special_char_found=array();
    for($x=0;$x<count($special_chars);$x++){
        if( strpos($string , $special_chars[$x])){
            array_push($special_char_found, $special_chars[$x]);
            $string_contains_special_char='true';
        }
    }
    
    if($string_contains_special_char=='true'){
        return $special_char_found;
    }else{
        return false;
    }
}

function checkIfStringContainsSpecialCharacters($string) {
    $special_chars=array("|","/","#","@","%","&", "?"," ","'","[","]",",");
    $string_contains_special_char='false';
    $special_char_found=array();
    for($x=0;$x<count($special_chars);$x++){
        if(strpos(trim($string) , $special_chars[$x])){
            $sc=$special_chars[$x];
            if($sc==" "){
                $sc="blank space";
            }
            array_push($special_char_found, $sc);
            $string_contains_special_char='true';
        }
    }
    
    if(preg_match('/\R/', $string)){
        $string_contains_special_char='true';
        array_push($special_char_found, 'line break');
    }
    
    if($string_contains_special_char=='true'){
        return $special_char_found;
    }else{
        return false;
    }
}

function checkIfStringLengthExceedThreshold($string) {
    $string_exceeds_threshold=false;
    //     echo count($string).",";
    if(strlen($string)>50){
        $string_exceeds_threshold=true;
    }
    return $string_exceeds_threshold;
}

function getOrg($usernameD,$ldapServer,$ldapParamters){
    include 'config.php';
    $ldapUsername = "cn=" . $usernameD . "," . $ldapParamters;
    $ds = ldap_connect($ldapServer, '389');
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    return checkLdapOrganization($ds, $ldapUsername, $ldapParamters);
}


function detectDtmUtf8($string){
    return preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )+%xs',
        $string);
}

function sendDtmFailedUploadEmail($file,$reasons,$org,$user){
    
    //Load Composer's autoloader
    require 'vendor/autoload.php';
    
    $configs = parse_ini_file('datatablemanager_config.ini.php');
    $emailFrom=$configs['email_from'];
    $emailTo=$configs['email_to'];
    $host=$configs['host'];
    $port= $configs['port'];
    
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    
    try {
        //Server settings
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
//         $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;                    //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $host;          //Set the SMTP server to send through
        $mail->Port       = $port;
        $mail->SMTPAuth   = false;                                   //Enable SMTP authentication
        
        //Recipients
        $mail->setFrom($emailFrom, 'Data Table Loader');
        $mail->addAddress($emailTo, 'Recipient');     //Add a recipient
        
//         $mail->setFrom('ala.arman@unifi.it', 'Data Table Loader');
//         $mail->addAddress('ala.arman@gmail.com', 'Recipient'); // Add a recipient
        
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'File '. $file .' failed to be uploaded!';
        $message = "<html><body><table>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;;margin-bottom: 0px;font-weight: bold;'>File Name: </p><p style='font-size:13px;margin-bottom: 0px;margin-top: 0px;'>" . $file."</p></td></tr>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>User: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $user ."</p></td></tr>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>Organization: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>".$org ."</p></td></tr>";
        $message.= "<tr><td>";
        $message.="<p style='font-size:15px;font-weight: bold;'>Reasons(s):</p><ul style='padding-left: 0px;'>";
        foreach ($reasons as $reason) {
            $message .= "<li>" . $reason . "</li>";
        }
        $message .="</ul></td></tr></table></td></tr></body></html>";
        
        $mail->Body=$message;
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function sendDtmSuccessUploadEmail($file,$org,$user,$element_id){
    
    //Load Composer's autoloader
    require 'vendor/autoload.php';
    
    $configs = parse_ini_file('datatablemanager_config.ini.php');
    $emailFrom=$configs['email_from'];
    $emailTo=$configs['email_to'];
    $host=$configs['host'];
    $port= $configs['port'];
    
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    
    try {
        //Server settings
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        //         $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;                    //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $host;          //Set the SMTP server to send through
        $mail->Port       = $port;
        $mail->SMTPAuth   = false;                                   //Enable SMTP authentication
        
        //Recipients
        $mail->setFrom($emailFrom, 'Data Table Loader');
        $mail->addAddress($emailTo, 'Recipient');     //Add a recipient
        
//         $mail->setFrom('ala.arman@unifi.it', 'Data Table Loader');
//         $mail->addAddress('ala.arman@gmail.com', 'Recipient'); // Add a recipient
        
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'File '.$file.' successfully uploaded!';
        $message = "<html><body><table>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>User: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $user ."</p></td></tr>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>Organization: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>".$org ."</p></td></tr>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>Element ID: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>".$element_id ."</p></td></tr>";
        $message.= "</table></body></html>";
        
        $mail->Body=$message;
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


function sendDTMErrorUserDelegationEmail($file, $org, $elementId,$user_name_uploader, $username_to_delegate, $error){
    
    //Load Composer's autoloader
    require 'vendor/autoload.php';
    
    $configs = parse_ini_file('datatablemanager_config.ini.php');
    $emailFrom=$configs['email_from'];
    $emailTo=$configs['email_to'];
    $host=$configs['host'];
    $port= $configs['port'];
    
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    
    try {
        //Server settings
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        //         $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;                    //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $host;          //Set the SMTP server to send through
        $mail->Port       = $port;
        $mail->SMTPAuth   = false;                                   //Enable SMTP authentication
        
        //Recipients
        $mail->setFrom($emailFrom, 'Data Table Loader');
        $mail->addAddress($emailTo, 'Recipient');     //Add a recipient
        
//                 $mail->setFrom('ala.arman@unifi.it', 'Data Table Loader');
//                 $mail->addAddress('ala.arman@gmail.com', 'Recipient'); // Add a recipient
        
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'User delegation failed!';
        $message = "<html><body><table>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>File Name: </p><p style='font-size:13px;margin-bottom: 0px;margin-top: 0px;'>" . $file."</p></td></tr>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>usernameDelegated: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $username_to_delegate."</p></td></tr>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>usernameDelegator: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>".$user_name_uploader ."</p></td></tr>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>Organization: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>".$org ."</p></td></tr>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>elementId: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>".$elementId ."</p></td></tr>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>elementType: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>DataTableID</p></td></tr>";
        $message.= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>Error: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>".$error."</p></td></tr>";
        
        $mail->Body=$message;
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>