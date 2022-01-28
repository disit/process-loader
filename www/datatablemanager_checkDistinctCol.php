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
$file = $_GET['file_name'];
$target_dir = $_GET['target_dir'];
$selected_headers = explode(",", $_GET['selected_headers']);
require_once ('datatablemanager_myUtil.php');
require_once ('datatablemanager_SimpleXLSX.php');
$xlsx = SimpleXLSX::parse($target_dir . $file);
$sheet_rows_data = $xlsx->rows(0);

$sheetNames = $xlsx->sheetNames();
$all_headers_array = $sheet_rows_data[0];

$ColsWithSpecialCharsOrNonDistinct = array();
$selected_header_indexes = array();

foreach ($selected_headers as $selected_header) {
    for ($index = 0; $index < count($all_headers_array); $index ++) {
        if ($all_headers_array[$index] == $selected_header) {
            array_push($selected_header_indexes, $index);
            break;
        }
    }
}

for ($selected_header_index = 0; $selected_header_index < count($selected_header_indexes); $selected_header_index ++) {
    for ($sheetIndex = 0; $sheetIndex < count($sheetNames); $sheetIndex ++) {
        $sheet_rows_data = $xlsx->rows($sheetIndex);
        // $rowIndex = 1 ---> ignore header rows
        $sheet_col_data = array();
        for ($rowIndex = 1; $rowIndex < count($sheet_rows_data); $rowIndex ++) {
            $all_headers_array = $sheet_rows_data[$rowIndex];
            for ($cell_number = 0; $cell_number < count($all_headers_array); $cell_number ++) {
                if ($cell_number == $selected_header_index) {
                    array_push($sheet_col_data, $all_headers_array[$cell_number]);
                }
            }
        }
    }

    if (checkIfColIsUniqOrHasSpecialChars($sheet_col_data)) {
        array_push($ColsWithSpecialCharsOrNonDistinct, $selected_header);
    }
}

if (count($ColsWithSpecialCharsOrNonDistinct) >= 1) {
    echo 'true';
} else {
    var_dump($ColsWithSpecialCharsOrNonDistinct);
}

function checkIfColIsUniqOrHasSpecialChars($col_data){
    foreach ($col_data as $value) {
        if (1 < count(array_keys($col_data, $value))||detectDtmUtf8($value) == 1 || checkIfStringContainsSpecialCharacters($value)) {
            return true;
        }
    }
    return false;
}

?>