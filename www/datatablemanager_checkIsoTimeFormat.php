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
$file=$_GET['file'];
$selected_header=$_GET['selected_header'];

$target_dir=$_GET['target_dir'];

require_once ('datatablemanager_SimpleXLSX.php');
$xlsx = SimpleXLSX::parse($target_dir . $file);
$sheet_rows_data = $xlsx->rows(0);
$all_headers_array = $sheet_rows_data[0];

for($index=0;$index<count($all_headers_array);$index++){
    if($all_headers_array[$index]==$selected_header){
        $col_index=$index;
        break;
    }
}


$sheetNames = $xlsx->sheetNames();
$error_cell_index=0;
$error_row_index=0;
$error_sheet_name="";
$result='true';
for ($sheetIndex = 0; $sheetIndex < count($sheetNames); $sheetIndex ++) {
    $sheet_rows_data = $xlsx->rows($sheetIndex);
    // $all_headers_array = $sheetData[0];
    // $rowIndex = 1 ---> ignore header rows
    for ($rowIndex = 1; $rowIndex < count($sheet_rows_data); $rowIndex ++) {
        $all_headers_array = $sheet_rows_data[$rowIndex];
        // for each row, for value_name column, add the associated values of value_names associated with each value name types in each row
        for ($cell_number = 0; $cell_number < count($all_headers_array); $cell_number ++) {
            if ($cell_number == $col_index) {
                $date = $all_headers_array[$cell_number];
                if (validateDate($date)==false) {
                    $result='false';
                    $error_cell_index=$date;
//                     echo $error_cell_index;
                    $error_row_index=$rowIndex;
//                     echo $error_row_index;
                    $error_sheet_name=$sheetNames[$sheetIndex];
//                     echo $error_sheet_name;
                    break 3;
                }
            }
        }
    }
}

echo $result;

function validateDate($date)
{
        // if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $date, $parts) == true) {
        // $time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);
    $tempDate = explode('-', $date);
    $tmp_result= checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
    
    if(!($tmp_result)){
        return false;
    }else{
        $input_time = strtotime($date);
        if ($input_time === false) {
            return false;
        } else {
            return true;
        }
    }

}





?>