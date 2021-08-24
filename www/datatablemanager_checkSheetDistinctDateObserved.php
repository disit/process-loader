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
require_once('datatablemanager_myUtil.php');
// echo $target_dir;
// die();
$xlsx = SimpleXLSX::parse($target_dir . $file);
$sheet_rows_data = $xlsx->rows(0);
$all_headers_array = $sheet_rows_data[0];
$dateObserved_column_index="";
$sheet_rows = $xlsx->rows(0);
$cols = $sheet_rows[0];
$sheetNames = $xlsx->sheetNames();
$result='false';

for($index=0;$index<count($all_headers_array);$index++){
    if($all_headers_array[$index]==$selected_header){
        $dateObserved_column_index=$index;
        break;
    }
}

for ($sheetIndex = 0; $sheetIndex < count($sheetNames); $sheetIndex ++) {
    for ($col_index = 0; $col_index < count($cols); $col_index ++) {
        $col_sheet_values = array();
        $sheet_rows = $xlsx->rows($sheetIndex);
        for ($rowIndex = 1; $rowIndex < count($sheet_rows); $rowIndex ++) {
            $sheet_row = $sheet_rows[$rowIndex];
            if($col_index==$dateObserved_column_index){
                $sheet_row_col = $sheet_row[$col_index];
                array_push($col_sheet_values, $sheet_row_col);
            }
            
        }
    }
    if(has_dupes($col_sheet_values)=='true'){
        $result='true';
        break;
    }
}

echo $result;

?>