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

include_once 'poimanager_sqlClient.php';
include_once 'datatablemanager_sqlQueryManager.php';

function insertValueName($value_name) {
    // first check if value_name id exists
    $check_value_name_exist_query = getCheckIfValueNameExistQuery($value_name);
    $value_name_id = executeCheckIfValueNameExistQuery($check_value_name_exist_query);
    
    if ($value_name_id==0) {
        // Value name does exists
        $value_name_insert_query = getInsertValueNameQuery($value_name);
        $value_name_id=executeInsertValueNameQuery($value_name_insert_query);
    }
    return $value_name_id;
}

function insertPoiDataTable($final_table, $row_count, $file_name, $elementId, $coord_type, $center_lat, $center_lon, $radius, $nature, $sub_nature,$lang,$org)
{
    $cell_count = count($final_table);
    // echo $final_table_length;
    // die();
    $row_length = $cell_count / $row_count;

//     echo $row_length;
//     die();
    
        // var_dump($each_row_length);
        // die();
        // echo $row_count;
        // echo var_dump($final_table);

        // echo $final_table_length;
        // echo "<br>";
        // echo $row_count;
        // echo $each_row_length;
    
        $cel_index=0;
        $col_pointer=0;
    
    //     for ($row_index = 0; $row_index < $row_count; $row_index ++) {
        while($col_pointer<$cell_count){
        
        $row = array();
        
        for ($cel_index = $col_pointer; $cel_index < $col_pointer+$row_length; $cel_index ++) {
            array_push($row, $final_table[$cel_index]);
        }
        
//         echo $row_length;
//         die();
    
                $col_pointer = $col_pointer + $row_length;

                // insert row
                $sheet_name = $row[0];
                $suri = $row[1];
                $name = str_replace("'", "\'", $row[2]);
                $abbreviation = str_replace("'", "\'", $row[3]);
                $descriptionShort = str_replace("'", "\'", $row[4]);
                $descriptionLong = str_replace("'", "\'", $row[5]);
                $phone = str_replace("'", "\'", $row[6]);
                $fax = str_replace("'", "\'", $row[7]);
                $url = str_replace("'", "\'", $row[8]);
                $email = str_replace("'", "\'", $row[9]);
                $refPerson = str_replace("'", "\'", $row[10]);
                $secondPhone = str_replace("'", "\'", $row[11]);
                $secondFax = str_replace("'", "\'", $row[12]);
                $secondEmail = str_replace("'", "\'", $row[13]);
                $secondCivicNumber = str_replace("'", "\'", $row[14]);
                $secondStreetAddress = str_replace("'", "\'", $row[15]);
                $notes = str_replace("'", "\'", $row[16]);
                $timetable = str_replace("'", "\'", $row[17]);
                $photo = str_replace("'", "\'", $row[18]);
                $other1 = str_replace("'", "\'", $row[19]);
                $other2 = str_replace("'", "\'", $row[20]);
                $other3 = str_replace("'", "\'", $row[21]);
                $postalcode = str_replace("'", "\'", $row[22]);
                $province = str_replace("'", "\'", $row[23]);
                $city = str_replace("'", "\'", $row[24]);
                $streetAddress = str_replace("'", "\'", $row[25]);
                $civicNumber = str_replace("'", "\'", $row[26]);
                $latitude = str_replace("'", "\'", $row[27]);
                $longitude = str_replace("'", "\'", $row[28]);
                // ///////////////////////////////////////////////////////////////
//                 echo '$nameENG: ' . $nameENG;
//                 echo '<br>';
//                 echo '$abbreviationENG: ' . $abbreviationENG;
//                 echo '<br>';
//                 echo '$descriptionShortENG: ' . $descriptionShortENG;
//                 echo '<br>';
//                 echo '$descriptionLongENG: ' . $descriptionLongENG;
//                 echo '<br>';
//                 echo '$phone: ' . $phone;
//                 echo '<br>';
//                 echo '$fax: ' . $fax;
//                 echo '<br>';
//                 echo '$url: ' . $url;
//                 echo '<br>';
//                 echo '$email: ' . $email;
//                 echo '<br>';
//                 echo '$refPerson: ' . $refPerson;
//                 echo '<br>';
//                 echo '$secondPhone: ' . $secondPhone;
//                 echo '<br>';
//                 echo '$secondFax: ' . $secondFax;
//                 echo '<br>';
//                 echo '$secondEmail: ' . $secondEmail;
//                 echo '<br>';
//                 echo '$secondCivicNumber: ' . $secondCivicNumber;
//                 echo '<br>';
//                 echo '$secondStreetAddress: ' . $secondStreetAddress;
//                 echo '<br>';
//                 echo '$notes: ' . $notes;
//                 echo '<br>';
//                 echo '$timetable: ' . $timetable;
//                 echo '<br>';
//                 echo '$photo: ' . $photo;
//                 echo '<br>';
//                 echo '$other1: ' . $other1;
//                 echo '<br>';
//                 echo '$other2: ' . $other2;
//                 echo '<br>';
//                 echo '$other3: ' . $other3;
//                 echo '<br>';
//                 echo '$postalcode: ' . $postalcode;
//                 echo '<br>';
//                 echo '$province: ' . $province;
//                 echo '<br>';
//                 echo '$city: ' . $city;
//                 echo '<br>';
//                 echo '$streetAddress: ' . $streetAddress;
//                 echo '<br>';
//                 echo '$civicNumber: ' . $civicNumber;
//                 echo '<br>';
//                 echo '$latitude: ' . $latitude;
//                 echo '<br>';
//                 echo '$longitude: ' . $longitude;
//                 echo '<br>';
//                 echo '//////////////////////////////////////////////////////////////';
//                 echo '<br>';

                // insert cell
                $insert_cell_query = getPoiInsertCellQuery($file_name, $elementId, $suri, $sheet_name, $coord_type, $center_lat, $center_lon, $radius, $nature, $sub_nature, $name, $abbreviation, $descriptionShort, $descriptionLong, $phone, $fax, $url, $email, $refPerson, $secondPhone, $secondFax, $secondEmail, $secondCivicNumber, $secondStreetAddress, $notes, $timetable, $photo, $other1, $other2, $other3, $postalcode, $province, $city, $streetAddress, $civicNumber, $latitude, $longitude,$lang,$org);
                // echo $insert_cell_query;
                // // die();
                $insert_cell_result = executeInsertCellQuery($row_number, $cell_number, count($row), $insert_cell_query);
                // echo $insert_cell_result;
                // die();
//             }
//     }
    }
}

?>