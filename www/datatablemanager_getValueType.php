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
$processLoaderValueUnitURI=$configs['processLoaderValueUnitURI'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$processLoaderValueUnitURI);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec ($ch);
$err = curl_error($ch);
curl_close ($ch);

if ($err) {
    echo "cURL Error #:" . $err;
}

$json = json_decode($response, true);
$json_content=$json['content'];
$value_type_array=array();

for($index=0;$index<count($json_content);$index++){
    $elem=$json_content[$index];
    $elem_array=$elem['parent_value'];
    
    foreach ($elem_array as $value){
        if(!in_array($value, $value_type_array)){
            array_push($value_type_array,$value);
        }
    }
}
echo implode(",",$value_type_array);
?>