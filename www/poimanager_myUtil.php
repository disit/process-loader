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
include ('config.php'); // Includes Login Script
                        // include('control.php');
require 'sso/autoload.php';
use Jumbojett\OpenIDConnectClient;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$oidc = new OpenIDConnectClient($ssoEndpZoint, $ssoClientId, $ssoClientSecret);
$oidc->providerConfigParam(array(
    'token_endpoint' => $oicd_address . '/auth/realms/master/protocol/openid-connect/token'
));

try {
    $tkn = $oidc->refreshToken($_SESSION['refreshToken']);
} catch (Exception $e) {
    print_r($e);
}

$tk = $tkn->access_token;

function getPoiAccessToken()
{
    return $GLOBALS['tk'];
}

function checkPoiLdapOrganization($connection, $userDn, $baseDn)
{
    $result = ldap_search($connection, $baseDn, '(&(objectClass=organizationalUnit)(l=' . $userDn . '))');
    echo $result;
    // $result = ldap_search($connection, $baseDn, '(&(objectClass=organizationalUnit))');
    $entries = ldap_get_entries($connection, $result);
    $orgsArray = [];
    foreach ($entries as $key => $value) {
        if (is_numeric($key)) {
            if ($value["ou"]["0"] != '') {
                array_push($orgsArray, $value["ou"]["0"]);
            }
        }
    }

    if (sizeof($orgsArray) > 1 || sizeof($orgsArray) == 0) {
        return "DISIT";
    } else {
        return $orgsArray[0];
    }
    // return "";
}

function getPoiIndices($all_headers_array, $value_name_types_array)
{
    $indices = array();
    foreach ($value_name_types_array as $value_name) {
        for ($index = 0; $index < count($all_headers_array); $index ++) {
            if ($all_headers_array[$index] == $value_name) {
                array_push($indices, $index);
            }
        }
    }
    return $indices;
}

function getPoiDofDataTypes($data_types, $value_name_types_array)
{
    foreach ($value_name_types_array as $value) {
        unset($data_types[array_search($value, $value_name_types_array)]);
    }
    return $data_types;
}

function has_Poi_dupes($array)
{
    $dupe_array = array();
    foreach ($array as $val) {
        if (++ $dupe_array[$val] > 1) {
            return 'true';
        }
    }
    return 'false';
}

function getPoiDataTypes($target_dir, $file)
{
    require_once ('datatablemanager_SimpleXLSX.php');
    $xlsx = SimpleXLSX::parse($target_dir . $file);
    $sheet_rows = $xlsx->rows(0);
    $cols = $sheet_rows[0];
    $sheetNames = $xlsx->sheetNames();
    $data_types = array();

    for ($col_index = 0; $col_index < count($cols); $col_index ++) {
        $col_name = $cols[$col_index];
        $col_sheet_values = array();
        for ($sheetIndex = 0; $sheetIndex < count($sheetNames); $sheetIndex ++) {
            $sheet_rows = $xlsx->rows($sheetIndex);
            for ($rowIndex = 1; $rowIndex < count($sheet_rows); $rowIndex ++) {
                $sheet_row = $sheet_rows[$rowIndex];
                $sheet_row_col = $sheet_row[$col_index];
                array_push($col_sheet_values, $sheet_row_col);
            }
        }
        // // var_dump($col_sheet_values);
        // // echo '<br>';
        // echo empty($col_sheet_values);
        // echo '<br>';

        if (! checkIfArrayEmpty($col_sheet_values)) {
            array_push($data_types, 'null');
        } else if ($col_name == 'url' || $col_name == 'photo') {
            array_push($data_types, checkUrlDataType($col_sheet_values));
        } else if ($col_name == 'email' || $col_name == 'secondEmail') {
            array_push($data_types, checkEmailDataType($col_sheet_values));
        } else {
            array_push($data_types, get_poi_data_type($col_sheet_values, $col_name));
        }
    }
    return $data_types;
}

function checkIfArrayEmpty($arr)
{
    foreach ($arr as $key => $value) {
        if (empty($value)) {
            unset($arr[$key]);
        }
    }

    if (empty($arr)) {
        return false;
    } else {
        return true;
    }
}

function checkUrlDataType($col_sheet_values)
{
    $data_type = "URL";
    $problematic_cells = array();
    foreach ($col_sheet_values as $value) {
        if (! filter_var(trim($value), FILTER_VALIDATE_URL) && ($value != "")) {
            $data_type = 'false';
            array_push($problematic_cells, $value);
        }
    }

    if ($data_type == 'false') {
        return $problematic_cells;
    } else {
        return $data_type;
    }
}

function checkEmailDataType($col_sheet_values)
{
    $data_type = "email";
    foreach ($col_sheet_values as $value) {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL) && ($value != "")) {
            $data_type = 'false';
        }
        break;
    }
    return $data_type;
}

function get_poi_data_type($col_values, $col_name)
{
    $col_data_types = array();
    $data_type = "";

    foreach ($col_values as $value) {
        array_push($col_data_types, gettype($value) == 'double' ? 'float' : gettype($value));
    }

    // if all data types are the same
    if (count(array_unique($col_data_types)) === 1) {
        $data_type = $col_data_types[0];
    } else {

        if (in_array('string', $col_data_types) && ! in_array(null, $col_values)) {
            $data_type = 'string';
        } elseif ((in_array('float', $col_data_types) && in_array(null, $col_values)) || (in_array('float', $col_data_types) && in_array('integer', $col_data_types))) {
            $data_type = 'float';
        } elseif (in_array('integer', $col_data_types) && in_array(null, $col_values)) {
            $data_type = 'integer';
        } else {
            $data_type = 'boolean';
        }
    }

    if (($data_type == 'integer') && ($col_name == 'postalcode')) {
        $data_type = 'string';
    }

    if (($data_type == 'integer') && ($col_name == 'civicNumber')) {
        $data_type = 'string';
    }

    if (($data_type == 'integer') && ($col_name == 'secondCivicNumber')) {
        $data_type = 'string';
    }

    if (($data_type == 'integer') && ($col_name == 'secondPhone')) {
        $data_type = 'string';
    }

    if (($data_type == 'integer') && ($col_name == 'secondFax')) {
        $data_type = 'string';
    }
    return $data_type;
}

function getPoiDataTableJson($conn, $query, $headers)
{
    $result = $conn->query($query);
    $rows = array();

    while ($row = $result->fetch_array()) {
        array_push($rows, $row);
    }
    $r = $rows[0];
    $element_id = $r['element_id'];
    $status = $r['status'];
    $upload_timestamp = explode("|", $element_id)[1];

    $model_type = 'PoiTable';
    $file_name = $r['file_name'];
    $file_name_no_space = str_replace(" ", "", $file_name);
    $model_name = substr(trim($file_name_no_space), 0, count(trim($file_name_no_space)) - 6) . "_" . $upload_timestamp;
    $upload_date_time = $r['upload_date_time'];
    $nature = $r['nature'];
    $sub_nature = $r['sub_nature'];
    $coord_type = $r['coord_type'];
    $center_lat = $r['center_lat'];
    $center_lon = $r['center_lon'];
    $radius = $r['radius'];
    $lang = $r['language'];
    $org = $r['organization'];

    $result = array(
        'result' => 'OK',
        'code' => '200'
    );

    $result['File'] = array(
        'file_name' => $file_name,
        'upload_date_time' => $upload_date_time,
        'coord_type' => $coord_type,
        'headers' => $headers,
        'status' => $status
    );

    if ($coord_type == 'caseb') {
        $result['Model'] = array(
            'name' => $model_name,
            'organization' => $org,
            'type' => $model_type,
            'rdf_file_name' => substr($file_name_no_space, 0, count($file_name_no_space) - 5) . 'n3',
            'element_id' => $element_id,
            'nature' => $nature,
            'sub_nature' => $sub_nature,
            'center_lat' => $center_lat,
            'center_lon' => $center_lon,
            'radius' => $radius,
            'language' => $lang
        );
    } else {
        $result['Model'] = array(
            'name' => $model_name,
            'organization' => $org,
            'type' => $model_type,
            'rdf_file_name' => substr($file_name_no_space, 0, count($file_name_no_space) - 5) . 'n3',
            'element_id' => $element_id,
            'nature' => $nature,
            'sub_nature' => $sub_nature,
            'language' => $lang
        );
    }

    // Rows/////////////////////////////
    for ($poi_index = 0; $poi_index < count($rows); $poi_index ++) {

        $row = $rows[$poi_index];
        $result['Instances'][$poi_index]['Values'] = array();

        $result['Instances'][$poi_index]['Values']['suri'] = $row['suri'];
        $result['Instances'][$poi_index]['Values']['sheet_name'] = $row['sheet_name'];

        $result['Instances'][$poi_index]['Values']['name'] = $row['name'];
        $result['Instances'][$poi_index]['Values']['abbreviation'] = $row['abbreviation'];
        $result['Instances'][$poi_index]['Values']['descriptionShort'] = $row['descriptionShort'];
        $result['Instances'][$poi_index]['Values']['descriptionLong'] = $row['descriptionLong'];
        $result['Instances'][$poi_index]['Values']['phone'] = $row['phone'];
        $result['Instances'][$poi_index]['Values']['fax'] = $row['fax'];
        $result['Instances'][$poi_index]['Values']['url'] = $row['url'];
        $result['Instances'][$poi_index]['Values']['email'] = $row['email'];
        $result['Instances'][$poi_index]['Values']['refPerson'] = $row['refPerson'];
        $result['Instances'][$poi_index]['Values']['secondPhone'] = $row['secondPhone'];
        $result['Instances'][$poi_index]['Values']['secondFax'] = $row['secondFax'];
        $result['Instances'][$poi_index]['Values']['secondEmail'] = $row['secondEmail'];
        $result['Instances'][$poi_index]['Values']['secondCivicNumber'] = $row['secondCivicNumber'];
        $result['Instances'][$poi_index]['Values']['secondStreetAddress'] = $row['secondStreetAddress'];
        $result['Instances'][$poi_index]['Values']['notes'] = $row['notes'];
        $result['Instances'][$poi_index]['Values']['timetable'] = $row['timetable'];
        $result['Instances'][$poi_index]['Values']['photo'] = $row['photo'];
        $result['Instances'][$poi_index]['Values']['other1'] = $row['other1'];
        $result['Instances'][$poi_index]['Values']['other2'] = $row['other2'];
        $result['Instances'][$poi_index]['Values']['other3'] = $row['other3'];
        $result['Instances'][$poi_index]['Values']['postalcode'] = $row['postalcode'];
        $result['Instances'][$poi_index]['Values']['province'] = $row['province'];
        $result['Instances'][$poi_index]['Values']['city'] = $row['city'];
        $result['Instances'][$poi_index]['Values']['streetAddress'] = $row['streetAddress'];
        $result['Instances'][$poi_index]['Values']['civicNumber'] = $row['civicNumber'];

        if ($coord_type == "caseb") {
            $result['Instances'][$poi_index]['Values']['latitude'] = $row['calculated_lat'];
            $result['Instances'][$poi_index]['Values']['longitude'] = $row['calculated_lon'];
        } else {
            $result['Instances'][$poi_index]['Values']['latitude'] = $row['latitude'];
            $result['Instances'][$poi_index]['Values']['longitude'] = $row['longitude'];
        }
    }
    return $result;
}

function getPoiModelFeatureDataTypes($model_features, $headers, $headerDataTypes)
{
    $model_features_data_types = array();

    foreach ($model_features as $value) {
        $model_feature_index_in_headers = array_search($value, $headers);
        $model_feature_data_type = $headerDataTypes[$model_feature_index_in_headers];
        array_push($model_features_data_types, $model_feature_data_type);
    }
    return $model_features_data_types;
}

function getPoiModelFeatureValueUnits($model_features, $headers, $headerValueUnits)
{
    $model_features_value_units = array();

    foreach ($model_features as $value) {
        $model_feature_index_in_headers = array_search($value, $headers);
        $model_feature_value_unit = $headerValueUnits[$model_feature_index_in_headers];
        array_push($model_features_value_units, $model_feature_value_unit);
    }
    return $model_features_value_units;
}

function getPoiModelFeatureValueTypes($model_features, $headers, $headerValueTypes)
{
    $model_features_value_types = array();

    foreach ($model_features as $value) {
        $model_feature_index_in_headers = array_search($value, $headers);
        $model_feature_value_type = $headerValueTypes[$model_feature_index_in_headers];
        array_push($model_features_value_types, $model_feature_value_type);
    }
    return $model_features_value_types;
}

function updatePoiHeaders($headers, $lat_row_col, $lon_row_col)
{
    if ($key = array_search($lat_row_col, $headers)) {
        unset($headers[$key]);
    }

    if ($key = array_search($lon_row_col, $headers)) {
        unset($headers[$key]);
    }
    return $headers;
}

function getPoiModelFeatures($conn, $query, $headers)
{
    $features = $headers;
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $r = mysqli_fetch_array($get_data_query);
    extract($r);

    // var_dump($r);
    // die();

    $value_name_type = $r['value_name_type'];
    $lat_row_col = $r['lat_row_col'];
    $lon_row_col = $r['lon_row_col'];
    $coord_type = $r['coord_type'];

    $value_name_type_array = explode("__", $value_name_type);
    $value_name_type_minus_fileName_and_sheet_name_array = array_slice($value_name_type_array, 2, count($value_name_type_array) - 2);

    // var_dump($headers);
    // die();

    foreach ($value_name_type_minus_fileName_and_sheet_name_array as $value) {
        unset($features[array_search($value, $features)]);
    }

    // var_dump($features);
    // die();

    if ($coord_type == 'row') {
        unset($features[array_search($lat_row_col, $features)]);
        unset($features[array_search($lon_row_col, $features)]);
    }
    return array_values($features);
}

function removePoiElements($array, $index_array_to_remove)
{
    foreach ($index_array_to_remove as $index) {
        unset($array[$index]);
    }
    return $array;
}

function getPoiDateObservedType($conn, $query)
{
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $r = mysqli_fetch_array($get_data_query);
    extract($r);

    return $r['dateObserved_type'];
}

function getPoiHeaderDataTypes($conn, $query, $header_count)
{
    $data_types = array();
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $header_index = 0;

    while (($r = mysqli_fetch_array($get_data_query)) && ($header_index < $header_count)) {
        extract($r);
        $data_type = $r['data_type'];
        array_push($data_types, $data_type);
        ++ $header_index;
    }
    return $data_types;
}

function getPoiHeaderValueUnits($conn, $query, $header_count)
{
    $value_units = array();
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $header_index = 0;

    while (($r = mysqli_fetch_array($get_data_query)) && ($header_index < $header_count)) {
        extract($r);
        $value_unit = $r['value_unit'];
        array_push($value_units, extractStringBetweenTwoCharacters($value_unit, "(", ")"));
        ++ $header_index;
    }
    return $value_units;
}

function getPoiHeaderValueTypes($conn, $query, $header_count)
{
    $value_types = array();
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    $header_index = 0;
    // echo $header_count;
    // die();
    while (($r = mysqli_fetch_array($get_data_query)) && ($header_index < $header_count)) {
        extract($r);
        $value_type = $r['value_type'];
        array_push($value_types, $value_type);
        ++ $header_index;
    }
    return $value_types;
}

function getPoiHeaders($get_data_query)
{
    $header0 = "";
    $headers = array();
    $r = mysqli_fetch_array($get_data_query);

    extract($r);
    $header0 = $r['value_type_name'];

    array_push($headers, $header0);

    while ($r = mysqli_fetch_array($get_data_query)) {
        extract($r);
        $header = $r['value_type_name'];
        if (($header != $header0) && (! in_array($header, $headers))) {
            array_push($headers, $header);
        }
    }
    return $headers;
}

function extractPoiStringBetweenTwoCharacters($string, $start, $end)
{
    $ini = strpos($string, $start);

    if ($ini == 0) {
        return "";
    }

    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function get_poi_current_time_dat_utc_format()
{
    $configs = parse_ini_file('poimanager_config.ini.php');
    $timezone = $configs['time_zone'];
    date_default_timezone_set($timezone);
    return date('c', date_timestamp_get(date_create()));
}

function get_poi_current_date()
{
    $configs = parse_ini_file('datatablemanager_config.ini.php');
    date_default_timezone_set($configs['time_zone']);
    $date = date('d-m-Y');

    return $date;
}

function get_poi_current_time()
{
    $configs = parse_ini_file('datatablemanager_config.ini.php');
    date_default_timezone_set($configs['time_zone']);
    $time = date("h:i:sa");

    return $time;
}

function castPoiCsvStringToArray($csvString)
{
    $cols_string = explode(',', $_POST['final_cols_name_Sheet_name_rest'], true);
    $cols_csv = str_getcsv($csvString[0]);

    for ($i = 0; $i < count($cols_csv); $i ++) {
        echo $cols_csv[$i];
    }
}

function getPoiFileHeaders($file_name, $target_dir)
{
    require_once ('datatablemanager_SimpleXLSX.php');
    $xlsx = SimpleXLSX::parse($target_dir . $file_name);
    return $xlsx->rows(0)[0];
}

function rename_poi_file_in_target_dir($old_name, $new_name)
{
    if (file_exists($new_name)) {
        echo "Error While Renaming $old_name";
    } else {
        if (rename($old_name, $new_name)) {
            // echo "Successfully Renamed $old_name to $new_name" ;
        } else {
            // echo "A File With The Same Name Already Exists" ;
        }
    }
}

function getPoiFormatedFileDateObserved($hidden_observed_date_for_file_name, $observed_date_type, $coord_type, $lat_row_for_file, $lon_row_for_file, $lat_file, $lon_file)
{
    $formatted_lat = "";
    $formatted_lon = "";
    if ($coord_type == "row") {
        $formatted_lat = $lat_row_for_file;
        $formatted_lon = $lon_row_for_file;
    } else {
        $formatted_lat = $lat_file;
        $formatted_lon = $lon_file;
    }

    $observed_date_for_file_name = $hidden_observed_date_for_file_name . ':00.000' . toGmtOffset(get_nearest_timezone((float) $formatted_lat, (float) $formatted_lon));

    return $observed_date_for_file_name;
}

function get_poi_nearest_timezone($cur_lat, $cur_long, $country_code = '')
{
    $timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code) : DateTimeZone::listIdentifiers();

    if ($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {

        $time_zone = '';
        $tz_distance = 0;

        // only one identifier?
        if (count($timezone_ids) == 1) {
            $time_zone = $timezone_ids[0];
        } else {

            foreach ($timezone_ids as $timezone_id) {
                $timezone = new DateTimeZone($timezone_id);
                $location = $timezone->getLocation();
                $tz_lat = $location['latitude'];
                $tz_long = $location['longitude'];

                $theta = $cur_long - $tz_long;
                $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat))) + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                $distance = acos($distance);
                $distance = abs(rad2deg($distance));
                // echo '<br />'.$timezone_id.' '.$distance;

                if (! $time_zone || $tz_distance > $distance) {
                    $time_zone = $timezone_id;
                    $tz_distance = $distance;
                }
            }
        }
        return $time_zone;
    }
    return 'unknown';
}

function toPoiGmtOffset($timezone)
{
    $userTimeZone = new DateTimeZone($timezone);
    $offset = $userTimeZone->getOffset(new DateTime("now", new DateTimeZone('GMT'))); // Offset in seconds
    $seconds = abs($offset);
    $sign = $offset > 0 ? '+' : '-';
    $hours = floor($seconds / 3600);
    $mins = floor($seconds / 60 % 60);
    $secs = floor($seconds % 60);
    return sprintf("$sign%02d:%02d", $hours, $mins, $secs);
}

function getPoiFileNameOnly($filename)
{
    $filename = explode('_', $filename);
    array_pop($filename);
    $filename = implode('_', $filename);

    return $filename;
}

function getPoiWarningArray($target_dir, $file_name)
{
    require_once ('datatablemanager_SimpleXLSX.php');
    $configs = parse_ini_file('poimanager_config.ini.php');
    $warnings = array();
    $xlsx = SimpleXLSX::parse($target_dir . $file_name);
    $sheetNames = $xlsx->sheetNames();
    $sheetColHeaders = array();

    $poi_template_column = explode(",", $configs['poi_template_column']);
    $poi_datatypes = explode(",", $configs['poi_datatypes']);
    $columns = array_map('trim', $xlsx->rows(0)[0]);

    for ($sheet_index = 0; $sheet_index < count($sheetNames); $sheet_index ++) {
        $sheet_row_zero = array();
        for ($col_index = 0; $col_index < count($poi_template_column); $col_index ++) {
            $sheet_row_zero[$col_index] = $xlsx->rows($sheet_index)[0][$col_index];
        }
        $sheetColHeaders[$sheet_index] = $sheet_row_zero;
    }

    // check if columns are equal
    if (Count($col_eql_warns = getEqlColWarn($columns, $poi_template_column)) != 0) {
        foreach ($col_eql_warns as $col_eql_warn) {
            array_push($warnings, $col_eql_warn);
        }
    }

    // Check if there is extra (empty) column
    // if ((count($columns)!=count($poi_template_column))&&(count($col_ext_warns = getExtColWarn($columns, $poi_template_column)) != 0)) {
    // foreach ($col_ext_warns as $col_ext_warn) {
    // array_push($warnings, $col_ext_warn);
    // }
    // }
    if (is_int(array_search('name', $columns))) {
        $names = getPoiColValues($target_dir, $file_name, array_search('name', $columns));
        foreach ($names as $name) {
            $result = checkPoiIfFileNameContainsSpecialCharacters($name);

            if ($result) {
                // if (in_array('line break', $result)) {
                // $name = str_replace(array("\r","\n"), ' ', $name);
                // }

                $special_chars_string = implode(" ", checkPoiIfFileNameContainsSpecialCharacters($name));
                array_push($warnings, "Check <b>" . $name . "</b> in <b>name</b> column if it contains: <b>" . $special_chars_string . "</b>");
            }
        }
    } else {
        array_push($warnings, "No column <b>name</b> found!");
    }

    $datatypes = getPoiDataTypes($target_dir, $file_name);
    if ($poi_datatypes != $datatypes) {
        for ($col_index = 0; $col_index < count($poi_template_column); $col_index ++) {
            if ($poi_datatypes[$col_index] != $datatypes[$col_index] && $datatypes[$col_index] != 'null') {
                if ($columns[$col_index] == "url" || $columns[$col_index] == 'image' && $datatypes[$col_index] != 'null') {
                    array_push($warnings, "Column <b>" . $columns[$col_index] . "</b> does not seem to contain only <b>" . $poi_datatypes[$col_index]) . "</b>. ";
                } else {
                    array_push($warnings, "Column <b>" . $columns[$col_index] . "</b> must only contain <b>" . $poi_datatypes[$col_index]) . "</b>";
                }
            }
        }
    }

    if (checkPoiIfFileNameContainsSpecialCharacters($file_name) != false) {
        $special_chars_string = implode(" ", checkIfFileNameContainsSpecialCharacters($file_name));
        array_push($warnings, "Check file name if it contains: <b>" . $special_chars_string . "</b>");
    }

    for ($sheet_index = 0; $sheet_index < count($sheetNames); $sheet_index ++) {
        $sheet_col_header = $sheetColHeaders[$sheet_index];
        for ($sheet_col_header_index = 0; $sheet_col_header_index < count($sheet_col_header); $sheet_col_header_index ++) {
            $special_chars_result = checkPoiIfStringContainsSpecialCharacters($sheet_col_header[$sheet_col_header_index]);

            if ($special_chars_result != false) {
                if (in_array('line break', $special_chars_result)) {
                    $sheet_col_header[$sheet_col_header_index] = str_replace(array(
                        "\r",
                        "\n"
                    ), '', $sheet_col_header[$sheet_col_header_index]);
                }
                $special_chars_string = implode(" ", $special_chars_result);
                array_push($warnings, "Check sheet <b>" . $sheetNames[$sheet_index] . "</b>, column header <b>" . $sheet_col_header[$sheet_col_header_index] . "</b>, if it contains:s <b>" . $special_chars_string . "</b>");
            }
        }
    }

    for ($sheet_index = 0; $sheet_index < count($sheetNames); $sheet_index ++) {
        for ($y = 0; $y < count($sheetNames); $y ++) {
            if ($sheetColHeaders[$sheet_index] != $sheetColHeaders[$y]) {
                array_push($warnings, "Column headers for sheets <b>" . $sheetNames[$sheet_index] . "</b> and <b>" . $sheetNames[$y] . "</b> are not identical");
                break 1;
            }
        }
    }

    for ($cell_number = 0; $cell_number < count($sheetNames); $cell_number ++) {
        for ($y = 0; $y < count($sheetNames); $y ++) {
            if (count($sheetColHeaders[$cell_number]) != count($sheetColHeaders[$y])) {
                array_push($warnings, "The number of columns must be the same in all sheets!");
                break 2;
            }
        }
    }

    // Check if each cell contains ?
    for ($sheetIndex = 0; $sheetIndex < count($sheetNames); $sheetIndex ++) {
        $sheetData = $xlsx->rows($sheetIndex);
        $sheet_col_header = $sheetColHeaders[$sheetIndex];
        for ($rowIndex = 0; $rowIndex < count($sheetData); $rowIndex ++) {
            $sheetRow = $sheetData[$rowIndex];
            for ($sheet_col_header_index = 0; $sheet_col_header_index < count($sheet_col_header); $sheet_col_header_index ++) {
                $value = $sheetRow[$sheet_col_header_index];
                $cell_value_spec_chars = checkIfCellValuesContainSepacialCharacters($value, $sheet_col_header[$sheet_col_header_index]);
                if (count($cell_value_spec_chars) != 0) {
                    foreach ($cell_value_spec_chars as $cell_value_spec_char) {
                        array_push($warnings, "Check <b>" . $sheet_col_header[$sheet_col_header_index] . "</b> column, if it contains <b>" . $cell_value_spec_char . "</b>");
                    }
                }
            }
        }
    }

    // Check if image urls are valid
    if (is_int(array_search('photo', $columns))) {
        $photos = getPoiColValues($target_dir, $file_name, array_search('photo', $columns));
        $row_num = 2;
        foreach ($photos as $photo) {
            $result = validImage($photo);

            if (! $result) {
                array_push($warnings, "Check the url in row <b>#" . $row_num . " </b> in <b>photo</b> column if it leads to an image");
            }
            ++ $row_num;
        }
    } else {
        array_push($warnings, "No column <b>photo</b> found!");
    }

    return $warnings;
}

function validImage($url)
{
    $size = getimagesize($url);
    return (strtolower(substr($size['mime'], 0, 5)) == 'image' ? true : false);
}

function checkIfCellValuesContainSepacialCharacters($cell_value, $col)
{
    $configs = parse_ini_file('poimanager_config.ini.php');
    $cell_special_characters = explode(',', $configs['cell_special_characters']);
    $exempt_cols = array(
        'url',
        'photo'
    );
    $to_return = array();

    foreach ($cell_special_characters as $cell_special_character) {
        if (strpos(trim($cell_value), $cell_special_character) && (! in_array($col, $exempt_cols))) {
            array_push($to_return, $cell_special_character);
        }
    }
    return $to_return;
}

// function getExtColWarn($columns, $poi_template_columns)
// {
// echo count($poi_template_columns);
// echo '<br>';
// echo count($columns);

// $msg = array();
// // check if collumn headers follow the template
// for ($col_index = count($poi_template_columns); $col_index < count($columns); $col_index ++) {
// // echo count($poi_template_columns);
// // echo '<br>';
// // echo $col_index;
// // echo '<br>';
// // echo count($columns);
// // echo '<br>';
// array_push($msg, "Extra character found in column #" . $col_index . "</b>");
// }
// var_dump($msg);
// return msg;

// // foreach ($columns as $column){

// // if(strlen($column)==0 || strpos($column , "")){
// // array_push($warnings, "Empty cell(s) found after the last column. Please, remove it!");
// // }
// // }

// // foreach ($columns as $column){

// // if (strlen(str_replace(' ', '', $column)) == 0) {
// // echo 'YES';
// // }

// // if(strlen($column)==0 || strpos($column , "")){
// // array_push($warnings, "Empty cell(s) found after the last column. Please, remove it!");
// // }
// // }
// }
function getEqlColWarn($columns, $poi_template_column)
{
    $msg = array();

    // check if collumn headers follow the template
    for ($col_index = 0; $col_index < count($poi_template_column); $col_index ++) {
        if (trim($columns[$col_index]) != $poi_template_column[$col_index]) {
            array_push($msg, "The header of column #" . $col_index . " must be <b>" . $poi_template_column[$col_index] . "</b> not " . trim($columns[$col_index]));
        }
    }
    return $msg;
}

function getSuri($sheetRowData_0, $org, $file_name_for_suri)
{
    $configs = parse_ini_file('poimanager_config.ini.php');
    $name_orig = trim($sheetRowData_0);
    $name = str_replace('.', '_', $name_orig);
    $name = str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9_ ]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', $name)));

    if (strlen($name) == 0 || detectUTF8($name_orig) == 1) {
        $name = md5($name_orig);
    }
    return $configs['base_suri'] . $org . "/" . $file_name_for_suri . "_" . $name;
}

function detectUTF8($string)
{
    return preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )+%xs', $string);
}

function cleanCellValue($value)
{
    $cv = "";
    $cv = str_replace('"', ' ', $value);
    $cv = str_replace("'", ' ', $cv);
    return trim($cv);
}

function checkFileType($target_dir, $file_name)
{
    require_once ('datatablemanager_SimpleXLSX.php');
    $to_return = "";
    $xlsx = SimpleXLSX::parse($target_dir . $file_name);
    $row_count = count($xlsx->rows(0)) - 1;
    $columns = $xlsx->rows(0)[0];

    $province_index = array_search('province', $columns);
    $city_index = array_search('city', $columns);
    $streetAddress_index = array_search('streetAddress', $columns);
    $civicNumber_index = array_search('civicNumber', $columns);
    $lat_index = array_search('latitude', $columns);
    $lon_index = array_search('longitude', $columns);

    $province_values = getPoiColValues($target_dir, $file_name, $province_index);
    $city_values = getPoiColValues($target_dir, $file_name, $city_index);
    $streetAddress_values = getPoiColValues($target_dir, $file_name, $streetAddress_index);
    $civicNumber_values = getPoiColValues($target_dir, $file_name, $civicNumber_index);
    $lat_values = getPoiColValues($target_dir, $file_name, $lat_index);
    $lon_values = getPoiColValues($target_dir, $file_name, $lon_index);

    if (count($lat_values) == $row_count && count($lon_values) == $row_count) {
        $to_return = "casea";
    } else if (((count($lat_values) > 0 || count($lon_values) > 0) && (count($lat_values) < $row_count && count($lon_values) < $row_count)) && (count($province_values) == $row_count && count($city_values) == $row_count && count($streetAddress_values) == $row_count && count($civicNumber_values) == $row_count)) {
        $to_return = '<b>{latitude , longitude}</b> columns contain empty cells. You must either 1) fill the empty cells, 2) make <b>{latitude , longitude}</b> columns empty and fill <b>{province, city, streetAddress, civicNumber}</b> columns, or 3) split the file to two or more files (<a href=\"\">More Information</a>)';
    } else if ((count($lat_values) == 0 && count($lon_values) == 0) && (count($province_values) < $row_count || count($city_values) < $row_count || count($streetAddress_values) < $row_count || count($civicNumber_values) < $row_count)) {
        $to_return = "Either (or both values) for two column groups <b>{latitude , longitude}</b> OR <b>{province, city, streetAddress, civicNumber}</b> must <b>not be null</b> for all rows";
    } else if (((count($lat_values) > 0 || count($lon_values) > 0) && (count($lat_values) < $row_count && count($lon_values) < $row_count)) && ((count($province_values) > 0 && count($province_values) < $row_count) || (count($city_values) > 0 && count($city_values) < $row_count) || (count($streetAddress_values) > 0 && count($streetAddress_values) < $row_count) || (count($civicNumber_values) > 0 && count($civicNumber_values) < $row_count))) {
        $to_return = "Either (or both values) for two column groups <b>{latitude , longitude}</b> OR <b>{province, city, streetAddress, civicNumber}</b> must <b>not be null</b> for all rows";
    } else if ((count($province_values) == $row_count) && (count($city_values) == $row_count) && (count($streetAddress_values) == $row_count) && (count($civicNumber_values) == $row_count)) {
        $to_return = "caseb";
    }
    return $to_return;
}

function getPoiColValues($target_dir, $file_name, $col_index)
{
    require_once ('datatablemanager_SimpleXLSX.php');
    $xlsx = SimpleXLSX::parse($target_dir . $file_name);
    $col_values = array();

    for ($row_index = 1; $row_index < count($xlsx->rows(0)); $row_index ++) {
        $row = $xlsx->rows(0)[$row_index];
        if (count($row[$col_index]) != 0 && $row[$col_index] != "") {
            array_push($col_values, $row[$col_index]);
        }
    }
    return $col_values;
}

function checkPoiIfFileNameContainsSpecialCharacters($string)
{
    $special_chars = array(
        "|",
        "/",
        "#",
        "@",
        "%",
        "&",
        "?",
        "'",
        "[",
        "]",
        ","
    );
    $string_contains_special_char = 'false';
    $special_char_found = array();
    for ($x = 0; $x < count($special_chars); $x ++) {
        if (strpos($string, $special_chars[$x])) {
            array_push($special_char_found, $special_chars[$x]);
            $string_contains_special_char = 'true';
        }
    }

    if (preg_match('/\R/', trim($string))) {
        $string_contains_special_char = 'true';
        array_push($special_char_found, 'line break');
    }

    if ($string_contains_special_char == 'true') {
        return $special_char_found;
    } else {
        return false;
    }
}

function checkPoiIfStringContainsSpecialCharacters($string)
{
    $special_chars = array(
        "|",
        "/",
        "#",
        "@",
        "%",
        "&",
        "?",
        " ",
        "'",
        "[",
        "]",
        ","
    );
    $string_contains_special_char = 'false';
    $special_char_found = array();
    for ($x = 0; $x < count($special_chars); $x ++) {
        if (strpos(trim($string), $special_chars[$x])) {
            $sc = $special_chars[$x];
            if ($sc == " ") {
                $sc = "blank space";
            }
            array_push($special_char_found, $sc);
            $string_contains_special_char = 'true';
        }
    }

    if (preg_match('/\R/', $string)) {
        $string_contains_special_char = 'true';
        array_push($special_char_found, 'line break');
    }

    if ($string_contains_special_char == 'true') {
        return $special_char_found;
    } else {
        return false;
    }
}

function checkPoiIfCellContainsSpecialCharacters($string)
{
    $special_chars = array(
        "|",
        "/",
        "#",
        "%",
        "&",
        "?",
        "'",
        "[",
        "]",
        ","
    );
    $string_contains_special_char = 'false';
    $special_char_found = array();
    for ($x = 0; $x < count($special_chars); $x ++) {
        if (strpos(trim($string), $special_chars[$x])) {
            $sc = $special_chars[$x];
            array_push($special_char_found, $sc);
            $string_contains_special_char = 'true';
        }
    }

    if (preg_match('/\R/', $string)) {
        $string_contains_special_char = 'true';
        array_push($special_char_found, 'line break');
    }

    if ($string_contains_special_char == 'true') {
        return $special_char_found;
    } else {
        return false;
    }
}

function checkPoiIfStringLengthExceedThreshold($string)
{
    $string_exceeds_threshold = false;
    // echo count($string).",";
    if (strlen($string) > 50) {
        $string_exceeds_threshold = true;
    }
    return $string_exceeds_threshold;
}

function getLanguages()
{
    $configs = parse_ini_file('poimanager_config.ini.php');
    $path = $configs['language_file'];
    $langs = array();
    $csvFile = file($path);
    foreach ($csvFile as $line) {
        $line_arr = explode(',', $line);
        array_push($langs, $line_arr[1] . ',' . $line_arr[0]);
    }
    return $langs;
}

function sendPoiFailedUploadEmail($file, $reasons, $org, $user)
{

    // Load Composer's autoloader
    require 'vendor/autoload.php';

    $configs = parse_ini_file('datatablemanager_config.ini.php');
    $sendEmail = $configs['sendEmail'];
    
    if ($sendEmail == 'yes') {
        
        try {
            
            $emailTo = $configs['email_to'];
            $host = $configs['host'];
            $port = $configs['port'];
            $smtpOverTLS = $configs['smtpOverTLS'];
            $smtpHost=$configs['smtpServer'];
            $tlsPort = $configs['tlsPort'];
            $smtpUsername=$configs['smtpUsername'];
            $smtpPassword=$configs['smtpPassword'];
            
            // Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);
            
            if($smtpOverTLS=='yes'){
                $mail->isSMTP();
                $mail->SMTPKeepAlive = "true";
                $mail->SMTPAuth = true;     // turn on SMTP authentication
                $mail->Host  = $smtpHost;
                $mail->Port=$tlsPort;
                $mail->setFrom($smtpUsername, 'Data Table Loader');
                
                // Server settings
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                
                if($smtpUsername!="" && $smtpPassword!=""){
                    $mail->Username   = $smtpUsername;  // username
                    $mail->Password   = $smtpPassword;  // password
                }
                
            }else{
                $mail->isSMTP();
                $mail->SMTPAuth = false;
                $mail->Port = $port;
                $mail->Host = $host; // Set the SMTP server to send through
                $mail->setFrom($configs['email_from'], 'Data Table Loader');
            }
            
            //$mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL; //Enable verbose debug output
            $mail->addAddress($emailTo, 'Recipient'); // Add a recipient

        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'File ' . $file . ' failed to be uploaded!';
        $message = "<html><body><table>";
        $message .= "<tr><td colspan='4' ><p style='font-size:15px;;margin-bottom: 0px;font-weight: bold;'>File Name: </p><p style='font-size:13px;margin-bottom: 0px;margin-top: 0px;'>" . $file . "</p></td></tr>";
        $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>User: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $user . "</p></td></tr>";
        $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>Organization: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $org . "</p></td></tr>";
        $message .= "<tr><td>";
        $message .= "<p style='font-size:15px;font-weight: bold;'>Reasons(s):</p><ul style='padding-left: 0px;'>";
        foreach ($reasons as $reason) {
            $message .= "<li>" . $reason . "</li>";
        }
        $message .= "</ul></td></tr></table></td></tr></body></html>";

        $mail->Body = $message;
        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    }
}

function sendPoiSuccessUploadEmail($file, $org, $user, $element_id)
{

    // Load Composer's autoloader
    require 'vendor/autoload.php';

    $configs = parse_ini_file('datatablemanager_config.ini.php');
    $sendEmail = $configs['sendEmail'];
    
    if ($sendEmail == 'yes') {
        
        try {
            
            $emailTo = $configs['email_to'];
            $host = $configs['host'];
            $port = $configs['port'];
            $smtpOverTLS = $configs['smtpOverTLS'];
            $smtpHost=$configs['smtpServer'];
            $tlsPort = $configs['tlsPort'];
            $smtpUsername=$configs['smtpUsername'];
            $smtpPassword=$configs['smtpPassword'];
            
            // Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);
            
            if($smtpOverTLS=='yes'){
                $mail->isSMTP();
                $mail->SMTPKeepAlive = "true";
                $mail->SMTPAuth = true;     // turn on SMTP authentication
                $mail->Host  = $smtpHost;
                $mail->Port=$tlsPort;
                $mail->setFrom($smtpUsername, 'Data Table Loader');
                
                // Server settings
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                
                if($smtpUsername!="" && $smtpPassword!=""){
                    $mail->Username   = $smtpUsername;  // username
                    $mail->Password   = $smtpPassword;  // password
                }
                
            }else{
                $mail->isSMTP();
                $mail->SMTPAuth = false;
                $mail->Port = $port;
                $mail->Host = $host; // Set the SMTP server to send through
                $mail->setFrom($configs['email_from'], 'Data Table Loader');
            }
            
            //$mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL; //Enable verbose debug output
            $mail->addAddress($emailTo, 'Recipient'); // Add a recipient

        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'File ' . $file . '  successfully uploaded!';
        $message = "<html><body><table>";
        $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>File Name: </p><p style='font-size:13px;margin-bottom: 0px;margin-top: 0px;'>" . $file . "</p></td></tr>";
        $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>User: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $user . "</p></td></tr>";
        $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>Organization: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $org . "</p></td></tr>";
        $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>Element ID: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $element_id . "</p></td></tr>";
        $message .= "</table></body></html>";

        $mail->Body = $message;
        $mail->send();
    } catch (Exception $e) {
        
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    }
}

function sendPoiErrorUserDelegationEmail($file, $org, $elementId, $user_name_uploader, $username_to_delegate, $error)
{
    
    // Load Composer's autoloader
    require 'vendor/autoload.php';
    
    $configs = parse_ini_file('datatablemanager_config.ini.php');
    $sendEmail = $configs['sendEmail'];
    
    
    if ($sendEmail == 'yes') {
        
        try {
            
            $emailTo = $configs['email_to'];
            $host = $configs['host'];
            $port = $configs['port'];
            $smtpOverTLS = $configs['smtpOverTLS'];
            $smtpHost=$configs['smtpServer'];
            $tlsPort = $configs['tlsPort'];
            $smtpUsername=$configs['smtpUsername'];
            $smtpPassword=$configs['smtpPassword'];
            
            // Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);
            
            if($smtpOverTLS=='yes'){
                $mail->isSMTP();
                $mail->SMTPKeepAlive = "true";
                $mail->SMTPAuth = true;     // turn on SMTP authentication
                $mail->Host  = $smtpHost;
                $mail->Port=$tlsPort;
                $mail->setFrom($smtpUsername, 'Data Table Loader');
                
                // Server settings
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                
                if($smtpUsername!="" && $smtpPassword!=""){
                    $mail->Username   = $smtpUsername;  // username
                    $mail->Password   = $smtpPassword;  // password
                }
                
            }else{
                $mail->isSMTP();
                $mail->SMTPAuth = false;
                $mail->Port = $port;
                $mail->Host = $host; // Set the SMTP server to send through
                $mail->setFrom($configs['email_from'], 'Data Table Loader');
            }
            
            //$mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL; //Enable verbose debug output
            $mail->addAddress($emailTo, 'Recipient'); // Add a recipient
            
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'User delegation failed!';
            $message = "<html><body><table>";
            $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>File Name: </p><p style='font-size:13px;margin-bottom: 0px;margin-top: 0px;'>" . $file . "</p></td></tr>";
            $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>usernameDelegated: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $username_to_delegate . "</p></td></tr>";
            $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>usernameDelegator: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $user_name_uploader . "</p></td></tr>";
            $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>Organization: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $org . "</p></td></tr>";
            $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>elementId: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $elementId . "</p></td></tr>";
            $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>elementType: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>PoiTableID</p></td></tr>";
            $message .= "<tr><td colspan='4' ><p style='font-size:15px;margin-bottom: 0px;font-weight: bold;'>Error: </p><p style='font-size:13px;margin-top: 0px;margin-bottom: 0px;'>" . $error . "</p></td></tr>";
            
            $mail->Body = $message;
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

function getFirstPoiAddress($sheetRowData, $template_columns)
{
    $streetAddress_index = array_search('streetAddress', $template_columns);
    $civicNumber_index = array_search('civicNumber', $template_columns);
    $city_index = array_search('city', $template_columns);
    $postalcode_index = array_search('postalcode', $template_columns);

    $streetAddress = $sheetRowData[$streetAddress_index];
    $civicNumber = $sheetRowData[$civicNumber_index];
    $city = $sheetRowData[$city_index];
    $postalcode = $sheetRowData[$postalcode_index];

    $address = $streetAddress . ' ' . $civicNumber . ', ' . $city;

    return $address;
}

function getSecondPoiAddress($sheetRowData, $template_columns)
{
    $secondStreetAddress_index = $template_columns['secondStreetAddress'];
    $secondCivicNumber_index = $template_columns['secondCivicNumber'];
    $city_index = $template_columns['city'];

    $secondStreetAddress = $sheetRowData[$secondStreetAddress_index];
    $secondCivicNumber = $sheetRowData[$secondCivicNumber_index];
    $city = $sheetRowData[$city_index];

    $address = $secondStreetAddress . ' ' . $secondCivicNumber . ', ' . $city;

    return $address;
}

?>