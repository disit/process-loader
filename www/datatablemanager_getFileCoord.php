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

$file = $_GET['file_name'];
$target_dir = $_GET['target_dir'];
$column = $_GET['column'];

require_once ('datatablemanager_SimpleXLSX.php');
$xlsx = SimpleXLSX::parse($target_dir . $file);
$sheet_rows_data = $xlsx->rows(0);
$all_headers_array = $sheet_rows_data[0];
$coord_to_return = "";

for ($index = 0; $index < count($all_headers_array); $index ++) {
    if ($all_headers_array[$index] == $column) {
        $col_index = $index;
        break;
    }
}

$sheetZeroRowOneData = $sheet_rows_data[1];
// $rowIndex = 1 ---> ignore header rows
for ($rowIndex = 0; $rowIndex < count($sheetZeroRowOneData); $rowIndex ++) {
    if ($rowIndex == $col_index) {
        $coord_to_return = $sheetZeroRowOneData[$col_index];
        break;
    }
}
echo $coord_to_return;
?>