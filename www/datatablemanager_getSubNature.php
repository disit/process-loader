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
$processLoaderNatureURI=$configs['processLoaderNatureURI'];
$selected_nature=$_GET['selectedValue'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$processLoaderNatureURI);
//     curl_setopt($ch, CURLOPT_POSTFIELDS,$params_String);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec ($ch);
$err = curl_error($ch);
curl_close ($ch);

// further processing ....
if ($err) {
    echo "cURL Error #:" . $err;
}

$json = json_decode($response, true);
$json_content=$json['content'];
$sub_natures=array();

foreach ($json_content as $value){
    //         array_push($sub_natures,$value['value']);
    if($value['parent_value'][0]==$selected_nature){
        array_push($sub_natures,$value['value']);
    }
}

echo implode(",",$sub_natures);

?>