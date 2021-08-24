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

include_once 'datatablemanager_sqlClient.php';
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

function insertDataTable($final_table,$row_count,$elementId,$value_name_type,$value_types,$file_name,$sheet_name_type,$value_types_comboboxes,$value_units_comboboxes,$nature,$sub_nature,$observed_date_type,$observed_date_for_sheets_date_time_pickers,$observed_date_for_file_name,$observed_date_column,$all_headers_array,$sheet_names,$data_types,$coord_type,$lat_file,$lon_file,$lat_sheet,$lon_sheet,$lat_row,$lon_row,$observed_date_none,$context_broker,$lat_row_for_file,$lon_row_for_file,$org,$address_lats,$address_lons,$address_warnings) {

    $final_table_length = count($final_table);
    $each_row_length = $final_table_length / $row_count;
    
    $row_pointer = 0;
    $row_number = 1;
    $observed_date="";
    $address_warning="";
    $preview_table_row_index=0;

//     var_dump($address_lats);
//     echo '<br>';
//     var_dump($address_lons);
//     die();
    
    // insert data rows one by one
    do {
        $row = array();
        for ($cell_number = $row_pointer; $cell_number < $row_pointer+ $each_row_length; $cell_number++) {
            array_push($row, $final_table[$cell_number]);
        }
            
        $value_name=$row[1];
        $sheet_name=$row[0];

        switch ($observed_date_type){
            case 'sheet':
                
                $key = array_search($sheet_name, $sheet_names); //$key = 0
                
                //the correct way
                if (false !== $key)
                {
                    $observed_date=$observed_date_for_sheets_date_time_pickers[$key];
                }
               
                break;
                
            case 'row';
            
            $observed_date_col_index_in_row=0;
            for($col_index=0;$col_index<count($all_headers_array);$col_index++){
                if($all_headers_array[$col_index]==$observed_date_column){
                    $observed_date_col_index_in_row=$col_index+2;
                    break;
                }
            }
            
            $observed_date=$row[$observed_date_col_index_in_row];
            
            break;
            
            case 'file':
                $observed_date=$observed_date_for_file_name;
                break;
        }
        
        ///////////////////////////////////////////////////////////////////////////
        switch ($coord_type){
                
            case 'row';
            
            $lat_col_index_in_row=0;
            $lon_col_index_in_row=0;
            
            for($col_index=0;$col_index<count($all_headers_array);$col_index++){
                if($all_headers_array[$col_index]==$lat_row){
                    $lat_col_index_in_row=$col_index+2;
                    break;
                }
            }
            
            for($col_index=0;$col_index<count($all_headers_array);$col_index++){
                if($all_headers_array[$col_index]==$lon_row){
                    $lon_col_index_in_row=$col_index+2;
                    break;
                }
            }
            
            $lat = str_replace(",",".",trim($row[$lat_col_index_in_row]));
            $lon = str_replace(",",".",trim($row[$lon_col_index_in_row]));
            
            break;
            
            case 'file':
                $lat=trim($lat_file);
                $lon=trim($lon_file);
                
                break;
                
            case 'address':

                $lat=trim($address_lats[$preview_table_row_index]);
                $lon=trim($address_lons[$preview_table_row_index]);
                $address_warning=trim($address_warnings[$preview_table_row_index]);
                
                break;
        }
        
        // insert row
        for($cell_number=2;$cell_number<count($row);$cell_number++){
                // insert cell
            if ($observed_date_type == "row") {
                $insert_cell_query = getInsertCellQuery($elementId, $value_name_type, $value_name, $sheet_name, $value_types[$cell_number - 2], $value_types_comboboxes[$cell_number - 2], $value_units_comboboxes[$cell_number - 2], $file_name, $row[$cell_number], $sheet_name_type, $lat, $lon, $nature, $sub_nature, $observed_date, $observed_date_type, $data_types[$cell_number - 2], $coord_type, $context_broker, $lat_row_for_file, $lon_row_for_file, $lat_row, $lon_row, $org, $observed_date_column,$address_warning);
            } else {
                $insert_cell_query = getInsertCellQuery($elementId, $value_name_type, $value_name, $sheet_name, $value_types[$cell_number - 2], $value_types_comboboxes[$cell_number - 2], $value_units_comboboxes[$cell_number - 2], $file_name, $row[$cell_number], $sheet_name_type, $lat, $lon, $nature, $sub_nature, $observed_date, $observed_date_type, $data_types[$cell_number - 2], $coord_type, $context_broker, $lat_row_for_file, $lon_row_for_file, $lat_row, $lon_row, $org,'',$address_warning);
            }
            $insert_cell_result=executeInsertCellQuery($row_number, $cell_number,count($row),$insert_cell_query);
        }
        $row_pointer = $row_pointer + $each_row_length;
        ++ $row_number;
        ++$preview_table_row_index;
        
    } while ($row_pointer < $final_table_length);
//     die();
}

?>