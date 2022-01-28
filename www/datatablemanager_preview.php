<!DOCTYPE html>
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
include('control.php');
//include('functionalities.php');

if (isset ($_SESSION['username'])){
  $utente_att = $_SESSION['username'];	
}else{
 $utente_att= "Login";	
}

if (isset ($_SESSION['username'])){
  $role_att = $_SESSION['role'];	
}else{
 $role_att= "";	
}

if (isset($_REQUEST['showFrame'])){
	if ($_REQUEST['showFrame'] == 'false'){
		//echo ('true');
		$hide_menu= "hide";
	}else{
		$hide_menu= "";
	}	
}else{$hide_menu= "";}


if (!isset($_GET['pageTitle'])){
	$default_title = "Preview";
}else{
	$default_title = "";
}
?>

<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Data Table Manager - Preview</title>
		
		<!-- jQuery -->
        <script src="jquery/jquery-1.10.1.min.js"></script>

		<!-- Bootstrap Core JavaScript -->
        <script src="bootstrap/bootstrap.min.js"></script>
			
        <!-- Bootstrap Core CSS -->
        <link href="bootstrap/bootstrap.css" rel="stylesheet">

        <!-- Bootstrap toggle button -->
        <link href="bootstrapToggleButton/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="bootstrapToggleButton/js/bootstrap-toggle.min.js"></script>
       
       <!-- Dynatable -->
		<script type="text/javascript" charset="utf8" src="lib/datatables.js"></script>
		<script type="text/javascript" src="lib/dataTables.responsive.min.js"></script>
		<script type="text/javascript" src="lib/dataTables.bootstrap4.min.js"></script>
		<script type="text/javascript" src="lib/jquery.dataTables.min.js"></script>
		<link href="lib/responsive.dataTables.css" rel="stylesheet" />
       <!-- Font awesome icons -->
        <link rel="stylesheet" href="fontAwesome/css/font-awesome.min.css">
        
        <!-- Custom CSS -->
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/dashboardList.css" rel="stylesheet">
        
<!--         My Stuff -->
<link href="https://fonts.googleapis.com/css?family=Titillium+Web&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.0.0.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.3.2.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<link rel="stylesheet" href="css/datatablemanager_preview.css">
        
    </head>
	<body class="guiPageBody">
	<?php include('functionalities.php'); ?>
        <div class="container-fluid">
		<div class="row mainRow" style='background-color: rgba(138, 159, 168, 1)'>
                <?php include "mainMenu.php" ?>
				<div class="col-xs-12 col-md-10" id="mainCnt">
                    <div class="row hidden-md hidden-lg">
                        <div id="mobHeaderClaimCnt" class="col-xs-12 hidden-md hidden-lg centerWithFlex">
                            Snap4City
                        </div>
                    </div>
                    <div class="row" id="title_row">
                        <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt"><?php echo urldecode($_GET['pageTitle']); ?></div>
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php  include "mobMainMenu.php" 
						?></div>
                    </div>
                    <div class="row" >
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:45px'>

						<div id="container"
							style="width: 1600px; position: fixed; top: 3%; height: fit-content;">
							<div id="div_please_wait" class="loading" style="top: 20px;">
								<p style="font-family: Montserrat;font-size: x-large; width: 154%; left: -14%;">Please wait</p>
								<span><i></i><i></i></span>
							</div>

<!-- Notification div							 -->
<div class="modal" id="notification" aria-modal="true" role="dialog" style="display: block;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header warning">
        <h3 class="modal-title" style="position: relative;left: -125px;">Attention</h3>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="document.getElementById('notification').style.display = 'none'" style="position: relative; left: 125px; width: 30px; padding-right: 0px; padding-left: 0px; height: 30px; padding-bottom: 0px; padding-top: 0px;background-color: darkgrey">X</button>
										</div>
      <div class="modal-body" style="font-size: 15px;background-color: #EFC050">
Please, verify value types, value units, and data types carefully!
      </div>
    </div>
  </div>
</div>			
							<div id="div_email" >
								<img id="email_image" src="img/datatablemanager_email_your_question.png"> <label
									id="email_label">Do you have a question? Send us an email: </label>
								<a id="href_email" href="mailto:snap4city@disit.org">snap4city@disit.org</a>
							</div>
							<form id="preview" method="POST" action="datatablemanager_save_table.php?showFrame=false"
								onsubmit="return validateForm(this)">

								<div id="div_navigation_btns"									>
									<input id="prevBtn" class="submit" type="submit" value="Back"
										onclick="clicked='back'"> <input class="submit" type="submit"
										style="font-family: 'Montserrat';" id="submit_button"
										value="Save" onclick="clicked='Save'">
								</div>

								<p class="instructor" style="width: 102%;">Summary View</p>

								<div id="div_table">
									<table id="table_preview">
              <?php
            require_once ('datatablemanager_APIQueryManager.php');
            require_once ('datatablemanager_myUtil.php');
            $configs = parse_ini_file('datatablemanager_config.ini.php');
            $target_dir = $configs['target_dir'];
            $final_table_value_name_Sheet_name_rest = "";
            session_start();
            $_SESSION['accessToken'] = $_POST['hidden_access_token'];
            $file_name = $_POST['hidden_file_name'];
//             $dateObserved_col_index=0;
    
            if (strlen($file_name) == 0) {
                $file_name = $_POST['hidden_file_to_uplolad_fromvalue_type_page'];
            }
            
            $data_types=getDataTypes($target_dir,$file_name,$_POST['hidden_observed_date_for_row_header']);

            $sheet_name_type = $_POST['hidden_sheet_name'];
            $value_names_types_json_obj = explode("|",$_POST["hidden_selected_value_names"]);
            // make value_name_types_array and value_name_types_concat first file name (if selected), second sheet name (if selected), and then the selected headers
            $value_name_types_concat = '';
            $value_name_types_array = array();
            $value_name_types_concat = 'File_Name' . '__' . $sheet_name_type;
            array_push($value_name_types_array, $file_name, $sheet_name_type);
            
            $coordinate_type=$_POST['hidden_coordinate_type'];
            $lat_row_for_file="";
            $lon_row_for_file="";
            
            if($coordinate_type=='file'){
                $coord_lat_file=$_POST['hidden_lat_file'];
                $coord_lon_file=$_POST['hidden_lon_file'];
            }
            
            if($coordinate_type!="address"){
                $lat_row_for_file=$_POST['hidden_lat_row_for_file'];
                $lon_row_for_file=$_POST['hidden_lon_row_for_file'];
            }
            
            $dateObserved_type= $_POST['hidden_observed_date_type'];
            $dateObserved_column=$_POST['hidden_observed_date_for_row_header'];
//             echo $_POST['hidden_observed_date_for_row_header'];
//             die();
            
            if($dateObserved_type=='file'){
                $date_observed_file_none=$_POST['hidden_observed_date_for_file_name'];
                for ($x = 0; $x < count($value_names_types_json_obj); $x ++) {
                    // if the item in the selected value types json object is not file name or sheet name type (because they are already included)
                    $item = $value_names_types_json_obj[$x];
                    array_push($value_name_types_array, $item);
                    // Concat selected value name types (composed in the previous step)
                    $value_name_types_concat = $value_name_types_concat . '__' . $item;
                }
            }
            
            ltrim('_', $value_name_types_concat);
            rtrim('_', $value_name_types_concat);

            // Make an array to put the composed value name type, the sheet name type, and the all headers
            $value_name_types_concat_sheet_name_type_all_headers_array = array();
            $value_name_types_concat_sheet_name_type_all_headers_array[0] = $value_name_types_concat;
            $value_name_types_concat_sheet_name_type_all_headers_array[1] = $sheet_name_type;
            
            if ($xlsx = SimpleXLSX::parse($target_dir . $file_name)) {
                $sheet_rows_data = $xlsx->rows(0);
                $all_headers_array = $sheet_rows_data[0];
                
//                 for($col_index=0;$col_index<count($all_headers_array);$col_index++){
//                     if($all_headers_array[$col_index]==$dateObserved_column){
//                         $dateObserved_col_index=$col_index;
//                     }
//                 }
                
                $sheet_names = $xlsx->sheetNames();
                $value_type_comboboxes_array = explode("|", $_POST['hidden_value_type_comboboxes']);
                $value_unit_comboboxes_array = explode("|", $_POST['hidden_value_unit_comboboxes']);
                $rest_header_array = array();
                $all_headers_dof_array = array();
                $value_name_type_dof_indices_array = array();
                

                for ($header_index = 0; $header_index < count($all_headers_array); $header_index ++) {
                    array_push($rest_header_array, $all_headers_array[$header_index]);
                    $value_name_types_concat_sheet_name_type_all_headers_array[$header_index + 2] = $all_headers_array[$header_index];
                }

                // $value_name_types_concat_sheet_name_type_all_headers_array_for_table = array_slice($value_name_types_concat_sheet_name_type_all_headers_array, 2);
                if ($dateObserved_type == 'file') {
                    $value_name_type_dof_indices_array = getIndices($all_headers_array, array_slice($value_name_types_array, 2));
                    $value_type_comboboxes_dof_array = removeElements($value_type_comboboxes_array, $value_name_type_dof_indices_array);
                    $value_unit_comboboxes_dof_array = removeElements($value_unit_comboboxes_array, $value_name_type_dof_indices_array);
                    $data_types_dof_array = getDofDataTypes($data_types, array_slice($value_name_types_array, 2));
                    $all_headers_dof_array = array_diff($all_headers_array, array_slice($value_name_types_array, 2));
                }

                // value type
                echo "<tr class=header ><td id=\"cell_first_second_row\" colspan=\"2\"></td><td style=\"background: darkcyan;font-weight: bold;font-family: Montserrat;font-weight:bold; font-size: 14px;\"  colspan=";

                echo $dateObserved_type == 'file' ? count($value_type_comboboxes_dof_array) : count($value_type_comboboxes_array);
                echo "><b>Value Type</b></td></tr>";
                echo "<tr><td colspan=\"2\" style=\"background-color: white;border-top-style: hidden;border-left-style: hidden;border-bottom-style: hidden;\"></td><td style=\"background: cadetblue;font-family: Montserrat;font-weight:400; font-size: 14px;\">" . implode("</td><td style=\"background: cadetblue;font-family: Montserrat;font-weight:400; font-size: 14px;\">", $dateObserved_type == 'file' ? $value_type_comboboxes_dof_array : $value_type_comboboxes_array) . "</td></tr>";

                // Value unit
                echo "<tr class=header><td id=\"cell_first_second_row\" colspan=\"2\"></td><td style=\"background: darkcyan;font-weight: bold;font-family: Montserrat;font-weight:bold; font-size: 14px;\"  colspan=";
                echo $dateObserved_type == 'file' ? count($value_type_comboboxes_dof_array) : count($value_type_comboboxes_array);
                echo "><b>Value Unit</b></td></tr>";
                echo "<tr><td colspan=\"2\" style=\"background-color: white;border-top-style: hidden;border-left-style: hidden;border-bottom-style: hidden;\"></td><td style=\"background: cadetblue;font-family: Montserrat;font-weight:400; font-size: 14px;\">" . implode("</td><td style=\"background: cadetblue;font-family: Montserrat;font-weight:400; font-size: 14px;\">", $dateObserved_type == 'file' ? $value_unit_comboboxes_dof_array : $value_unit_comboboxes_array) . "</td></tr>";

                // Data type
                echo "<tr class=header ><td id=\"cell_first_second_row\" colspan=\"2\"></td><td style=\"background: darkcyan;font-weight: bold;font-family: Montserrat;font-weight:bold; font-size: 14px;\"  colspan=";
                echo $dateObserved_type == 'file' ? count($value_type_comboboxes_dof_array) : count($value_type_comboboxes_array);
                echo "><b>Data Type</b></td></tr>";
                echo "<tr><td id=\"cell_first_second_row\" colspan=\"2\"><td style=\"background: cadetblue;font-family: Montserrat;font-weight:400; font-size: 14px;\">" . implode("</td><td style=\"background: cadetblue;font-family: Montserrat;font-weight:400; font-size: 14px;\">", $dateObserved_type == 'file' ? $data_types_dof_array : $data_types) . "</td></tr>";

                $header_row = "";
                if ($dateObserved_type == 'file') {
                    $header_row .= '<tr class=header><td style="background: #1C70D2;font-weight: bold">Sheet Name</td><td style="background: #1C70D2;font-weight: bold">Device Name</td><th style="color: black;">' . implode('</th><th style="color: black;">', $all_headers_dof_array) . '</th><td class=\"header\" style="color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 120px;background: #1C70D2;"> dateObserved</td></tr>';
                } else {
                    $header_row .= '<tr class=header><td style="background: #1C70D2;font-weight: bold">Sheet Name</td><td style="background: #1C70D2;font-weight: bold">Device Name</td>';
                    // $header_row.=implode('</th><th style="color: black;">', $all_headers_array);
                    foreach ($all_headers_array as $header) {
                        if ($header == $dateObserved_column && $dateObserved_column == "dateObserved") {
                            $header_row .= '<th style="color: black;width:160px;">' . $dateObserved_column . '</th>';
                        } else if ($header == $dateObserved_column && $dateObserved_column != "dateObserved") {
                            $header_row .= '<th style="color: black;width:160px;">' . $dateObserved_column . '<br><span style="color:red;">(dateObserved)</span></th>';
                        } else {
                            $header_row .= '<th style="color: black;">' . $header . '</th>';
                        }
                    }
                    $header_row.='</th></tr>';
                }

                if ($coordinate_type == 'file') {
                    $header_row = substr($header_row, 0, count($header_row) - 6);
                    $header_row .= '<td style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;">' . 'Latitude' . '</td><td style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;">' . 'Longitude' . '</td>';
                }else if($coordinate_type == 'address'){
                    $header_row = substr($header_row, 0, count($header_row) - 6);
                    $header_row .= '<td style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 200px;background: mediumseagreen;">' . 'Calculated Latitude' . '</td><td style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 200px;background: mediumseagreen;">' . 'Calculated Longitude' . '</td>';
                }

                $header_row = substr($header_row, 0, count($header_row) - 6);
                $header_row .= '<td style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;">' . 'Nature' . '</td>';
                $header_row .= '<td style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;">' . 'Sub-Nature' . '</td>';
                
                $header_row .= '<td style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 155px;">' . 'Context Broker' . '</td></tr>';
                
//                 if($coordinate_type == 'address'){
//                     $header_row = substr($header_row, 0, count($header_row) - 6);
// //                     $header_row .= '<td style="background: #1C70D2;width:100px;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width:130px;">' . 'Device/Instance will be created?' . '</td>';
//                     $header_row .= '<td style="background: #1C70D2;width:100px;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width:175px;">' . 'Comment' . '</td>';
//                 }
                
                
                echo $header_row;

                // Append rows//////////////////////////////////////////////////////////////
                $row_count = 0;
                $address_lat="";
                $address_lon="";
                $address_warning="";
                $address_lats=array();
                $address_lons=array();
                $address_warnings=array();
                
                for ($sheetIndex = 0; $sheetIndex < count($sheet_names); $sheetIndex ++) {
                    $sheet_rows_data = $xlsx->rows($sheetIndex);
                    // $rowIndex = 1 ---> ignore header rows
                    for ($rowIndex = 1; $rowIndex < count($sheet_rows_data); $rowIndex ++) {
                        $sheetRowData = $sheet_rows_data[$rowIndex];
                        $sheet_name_in_file = $sheet_names[$sheetIndex];
                        $row_array_to_be_inseted = array();
                        $row_array_to_be_inseted_dof = array();
                        $dateObserved_type = $_POST['hidden_observed_date_type'];

                        if ($dateObserved_type == 'row' || $dateObserved_type == 'row_converted') {
                            $value_names_cell_of_row_to_be_inserted = $file_name . '__' . $sheet_name_in_file;
                        } else {
                            $value_names_cell_of_row_to_be_inserted = $file_name . '__' . $sheet_name_in_file . '__';
                            // for each row, for value_name column, add the associated values of value_names associated with each value name types in each row
                            for ($cell_number = 0; $cell_number < count($value_name_types_array); $cell_number ++) {
                                // if the header item is part of value_name_type
                                if (in_array($value_name_types_array[$cell_number], $all_headers_array)) {
                                    // fine the cell value associated with the header item ($sheetRow[$header_index]) and append it to the value associated with the value_name cell in each row
                                    $header_index = array_search(trim($value_name_types_array[$cell_number]), $all_headers_array);
                                    if ($cell_number == count($value_name_types_array) - 1) {
//                                      $value_names_cell_of_row_to_be_inserted .= str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9_ ]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', trim($sheetRowData[$header_index]))));
                                        $value_names_cell_of_row_to_be_inserted .=  trim($sheetRowData[$header_index]);
                                    } else {
//                                      $value_names_cell_of_row_to_be_inserted = $value_names_cell_of_row_to_be_inserted . str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9_ ]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', trim($sheetRowData[$header_index])))) . '__';
                                        $value_names_cell_of_row_to_be_inserted = $value_names_cell_of_row_to_be_inserted . trim($sheetRowData[$header_index]) . '__';
                                    }
                                }
                            }
                        }

                        // insert sheet name value as second item in row
                        $row_array_to_be_inseted[0] = trim($sheet_name_in_file);
                        $row_array_to_be_inseted_dof[0] = trim($sheet_name_in_file);
                        // in each row, assign the value of value_name cell
                        $row_array_to_be_inseted[1] = str_replace(" ", "", $value_names_cell_of_row_to_be_inserted);
                        $row_array_to_be_inseted_dof[1] = str_replace(" ", "", $value_names_cell_of_row_to_be_inserted);
                            // Insert the the rest of values in the row array
                        for ($cell_index = 0; $cell_index < count($all_headers_array); $cell_index ++) {
                            if ($coordinate_type == 'row') {
                                if ($dateObserved_type == 'row' || $dateObserved_type == 'row_converted') {
                                    if ($dateObserved_type == 'row_converted' && $all_headers_array[$cell_index] == $_POST['hidden_observed_date_for_row_header']) {
                                        $row_array_to_be_inseted[$cell_index + 2] = convertStandardFormatTimeToUTC($sheetRowData[$cell_index], $coordinate_type,$lat_row_for_file,$lon_row_for_file,$coord_lat_file, $coord_lon_file);
                                    } else {
                                        if ($all_headers_array[$cell_index] == $_POST['hidden_lat_row'] || $all_headers_array[$cell_index] == $_POST['hidden_lon_row']) {
                                            $row_array_to_be_inseted[$cell_index + 2] = trim(str_replace(',', '.', $sheetRowData[$cell_index]));
                                        } else {
                                            $row_array_to_be_inseted[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                        }
                                    }
                                } else {
                                    if (!in_array($cell_index, $value_name_type_dof_indices_array)) {
                                        if ($all_headers_array[$cell_index] == $_POST['hidden_lat_row'] || $all_headers_array[$cell_index] == $_POST['hidden_lon_row']) {
                                            $row_array_to_be_inseted_dof[$cell_index + 2] = trim(str_replace(',', '.', $sheetRowData[$cell_index]));
                                            $row_array_to_be_inseted[$cell_index + 2] = trim(str_replace(',', '.', $sheetRowData[$cell_index]));
                                        } else {
                                            $row_array_to_be_inseted_dof[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                            $row_array_to_be_inseted[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                        }
                                    } else {
                                        if ($all_headers_array[$cell_index] == $_POST['hidden_lat_row'] || $all_headers_array[$cell_index] == $_POST['hidden_lon_row']) {
                                            $row_array_to_be_inseted[$cell_index + 2] = trim(str_replace(',', '.', $sheetRowData[$cell_index]));
                                        } else {
                                            $row_array_to_be_inseted[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                        }
                                    }
                                }
                            }else if($coordinate_type == 'address'){
                                //////////////////////ADDRESS////////////////////////////
                                if ($dateObserved_type == 'row' || $dateObserved_type == 'row_converted') {
                                    if ($dateObserved_type == 'row_converted' && $all_headers_array[$cell_index] == $_POST['hidden_observed_date_for_row_header']) {
                                        $row_array_to_be_inseted[$cell_index + 2] = convertStandardFormatTimeToUTC($sheetRowData[$cell_index], $coordinate_type,$lat_row_for_file,$lon_row_for_file,$coord_lat_file, $coord_lon_file);
                                    } else {
                                    $row_array_to_be_inseted[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                    }
                                } else {
                                    if (!in_array($cell_index, $value_name_type_dof_indices_array)) {
                                        $row_array_to_be_inseted_dof[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                        $row_array_to_be_inseted[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                    } else {
                                        $row_array_to_be_inseted[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                    }
                                }
                                // ////////////////////ADDRESS////////////////////////////
                                $address_column = $_POST['hidden_address_column'];
                                $address_index = array_search($address_column, $all_headers_array);
                                if ($cell_index == $address_index) {
                                    if (strlen($sheetRowData[$cell_index]) == 0) {
                                        $address_lat = "";
                                        $address_lon = "";
                                        $address_warning = "The Address cell is empty!";
                                    } else {
                                        $address_coord = getAddressCoord($sheetRowData[$cell_index], $_POST['hidden_address_lat'], $_POST['hidden_address_lon'], $_POST['hidden_address_radius']);
                                        if (count($address_coord[0]) == 0) {
                                            $address_lat = "<img style='width:25px' src='img/datatablemanager_address_coord_ko.png'/>";
                                            $address_lon = "<img style='width:25px' src='img/datatablemanager_address_coord_ko.png'/>";
                                            $address_warning = "No Coordinate found by the provided adress!";
                                        } else {
                                            $address_lat = $address_coord[0];
                                            $address_lon = $address_coord[1];
                                            $address_warning = "-";
                                            
                                            $lat_row_for_file=$address_lat;
                                            $lon_row_for_file=$address_lon;
                                        }
                                    }
                                    
                                    array_push($address_lats,$address_lat);
                                    array_push($address_lons,$address_lon);
                                    array_push($address_warnings,$address_warning);
                                }
                            }else {
                                if ($dateObserved_type == 'row' || $dateObserved_type == 'row_converted') {
                                    if ($dateObserved_type == 'row_converted' && $all_headers_array[$cell_index] == $_POST['hidden_observed_date_for_row_header']) {
                                        $row_array_to_be_inseted[$cell_index + 2] = convertStandardFormatTimeToUTC($sheetRowData[$cell_index], $coordinate_type,$lat_row_for_file,$lon_row_for_file,$coord_lat_file, $coord_lon_file);
                                    }else{
                                    $row_array_to_be_inseted[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                    }
                                } else {
                                    if (!in_array($cell_index, $value_name_type_dof_indices_array)) {
                                        $row_array_to_be_inseted[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                        $row_array_to_be_inseted_dof[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                    }else{
                                        $row_array_to_be_inseted[$cell_index + 2] = trim($sheetRowData[$cell_index]);
                                    }
                                }
                            }
                        }
                        
                        // update table values to be sent to the next form
                        $final_table_value_name_Sheet_name_rest .= implode("|", $row_array_to_be_inseted) . "|";

                        if ($dateObserved_type == 'file') {
                            $observed_date_file = getFormatedFileDateObserved($date_observed_file_none, $dateObserved_type, $coordinate_type, $lat_row_for_file, $lon_row_for_file, $coord_lat_file, $coord_lon_file);
                            $row_array_to_be_inseted[count($all_headers_array) + 2] = $observed_date_file;
                            $row_array_to_be_inseted_dof[count($all_headers_array) + 2] = $observed_date_file;
                        }

                        if ($coordinate_type == 'file') {
                            $row_array_to_be_inseted[count($all_headers_array) + 3] = $coord_lat_file;
                            $row_array_to_be_inseted[count($all_headers_array) + 4] = $coord_lon_file;

                            $row_array_to_be_inseted_dof[count($all_headers_array) + 3] = $coord_lat_file;
                            $row_array_to_be_inseted_dof[count($all_headers_array) + 4] = $coord_lon_file;
                        }
//                         }else if($coordinate_type == 'address'){
//                             $row_array_to_be_inseted[count($all_headers_array) + 3] = $address_lat;
//                             $row_array_to_be_inseted[count($all_headers_array) + 4] = $address_lon;
                            
//                             $row_array_to_be_inseted_dof[count($all_headers_array) + 3] = $address_lat;
//                             $row_array_to_be_inseted_dof[count($all_headers_array) + 4] = $address_lon;
//                         }

                        if($coordinate_type!="address"){
                        $row_array_to_be_inseted[count($all_headers_array) + 5] = $_POST['hidden_nature'];
                        $row_array_to_be_inseted_dof[count($all_headers_array) + 5] = $_POST['hidden_nature'];
                        
                        $row_array_to_be_inseted[count($all_headers_array) + 6] = $_POST['hidden_sub_nature'];
                        $row_array_to_be_inseted_dof[count($all_headers_array) + 6] = $_POST['hidden_sub_nature'];
                        
                        $row_array_to_be_inseted[count($all_headers_array) + 7] = $_POST['hidden_context_broker'];
                        $row_array_to_be_inseted_dof[count($all_headers_array) + 7] = $_POST['hidden_context_broker'];
                        }
//                          if($coordinate_type == 'address'){
//                              if($address_lat!=""){
//                                  $row_array_to_be_inseted[count($all_headers_array) + 8] = "<img style='width:25px' src='img/datatablemanager_address_coord_ok.png'/>";
//                                  $row_array_to_be_inseted_dof[count($all_headers_array) + 8] = "<img style='width:25px' src='img/datatablemanager_address_coord_ok.png'/>";
                                 
//                                  $row_array_to_be_inseted[count($all_headers_array) + 9] = $address_warning;
//                                  $row_array_to_be_inseted_dof[count($all_headers_array) + 9] = $address_warning;
                                 
//                              }else{
//                                  $row_array_to_be_inseted[count($all_headers_array) + 8] = "<img style='width:25px' src='img/datatablemanager_address_coord_ko.png'/>";
//                                  $row_array_to_be_inseted_dof[count($all_headers_array) + 8] = "<img style='width:25px' src='img/datatablemanager_address_coord_ko.png'/>";
                                 
//                                  $row_array_to_be_inseted[count($all_headers_array) + 9] = $address_warning;
//                                  $row_array_to_be_inseted_dof[count($all_headers_array) + 9] = $address_warning;
//                              }
//                         }
                        
                        // insert rest of values that are not part of value name in the row array
                        if($coordinate_type=="address"){
                            $row_number=$rowIndex+6;
                            if ($dateObserved_type == 'file') {
                                echo '<tr id="row_'.$row_number.'"><td>' . implode('</td><td>', $row_array_to_be_inseted_dof);
                            }else{
                                echo '<tr id="row_'.$row_number.'"><td>' . implode('</td><td>', $row_array_to_be_inseted);
                            }
                                
                            echo '<td id="lat_'.$row_number.'">'.$address_lat.'</td>';
                            echo '<td id="lon_'.$row_number.'">'.$address_lon.'</td>';
                            echo '<td>'. $_POST['hidden_nature'].'</td>';
                            echo '<td>'. $_POST['hidden_sub_nature'].'</td>';
                            echo '<td>'.$_POST['hidden_context_broker'].'</td>';
                            
                                if ($dateObserved_type == 'file') {
                                    $headers_dof_plus_1=count($all_headers_dof_array) +1;
                                    echo '<td class="list_table_buttons"  id="edit_td_'.$row_number.'"><input id="edit_btn_'.$row_number.'" value="Edit Coordinate" type="button" class="list_table_buttons"  style="background-color: orange;top: -3px;position: relative;width: 200px;" onclick="edit_coord('.$row_number.','.$headers_dof_plus_1.')"></td>';
                                }else{
                                    echo '<td class="list_table_buttons"  id="edit_td_'.$row_number.'"><input id="edit_btn_'.$row_number.'" value="Edit Coordinate" type="button" class="list_table_buttons"  style="background-color: orange;top: -3px;position: relative;width: 200px;" onclick="edit_coord('.$row_number.','.count($all_headers_array).')"></td>';
                                }
                                echo '</tr>';

                        }else{
                            if ($dateObserved_type == 'file') {
                                echo '<tr><td>' . implode('</td><td>', $row_array_to_be_inseted_dof) . '</tr>';
                            } else {
                                echo '<tr><td>' . implode('</td><td>', $row_array_to_be_inseted) . '</tr>';
                            }
                        }
                        ++ $row_count;
                    }
                }
            } else {
                echo SimpleXLSX::parseError();
            }
            ?>
			</table>
								</div>
								<!-- Hidden Inputs  -->
								<input type="hidden" name="hidden_address_lats" id="hidden_address_lats" value="">
								<input type="hidden" name="hidden_address_lons" id="hidden_address_lons" value="">
								<input type="hidden" name="hidden_address_warnings" id="hidden_address_warnings" value="">

								<input type="hidden" name="final_table_value_name_Sheet_name_rest" id="final_table_value_name_Sheet_name_rest" value=""> 
								<input type="hidden" name="final_cols_name_Sheet_name_rest" id="final_cols_name_Sheet_name_rest" value=""> 
									<input
									type="hidden" name="hidden_value_unit_comboboxes"
									id="hidden_value_unit_comboboxes"
									value="<?php echo $_POST['hidden_value_unit_comboboxes']; ?>">
								<input type="hidden" name="hidden_value_type_comboboxes"
									id="hidden_value_type_comboboxes"
									value="<?php echo $_POST['hidden_value_type_comboboxes']; ?>">
								<!-- 		Hidden Inputs   from Previous pages-->
								<input type="hidden" name="hidden_coordinate_type" id="hidden_coordinate_type" value="<?php echo $_POST['hidden_coordinate_type']; ?>">
								<input id="hidden_lat_file" type="hidden" name="hidden_lat_file" value="<?php echo $_POST['hidden_lat_file']; ?>">
								<input id="hidden_lon_file" type="hidden" name="hidden_lon_file" value="<?php echo $_POST['hidden_lon_file']; ?>">
								<input id="hidden_lat_sheet" type="hidden" name="hidden_lat_sheet" value="<?php echo $_POST['hidden_lat_sheet']; ?>">
								<input id="hidden_lon_sheet" type="hidden" name="hidden_lat_sheet" value="<?php echo $_POST['hidden_lat_sheet']; ?>">
								<input id="hidden_lat_row" type="hidden" name="hidden_lat_row" value="<?php echo $_POST['hidden_lat_row']; ?>">
								<input id="hidden_lon_row" type="hidden" name="hidden_lon_row" value="<?php echo $_POST['hidden_lon_row']; ?>">
								<input id="hidden_lat_row_for_file" type="hidden" name="hidden_lat_row_for_file" value="">
    							<input id="hidden_lon_row_for_file" type="hidden" name="hidden_lon_row_for_file" value="">            
								
								<!-- 			From previous pages -->
								
								<input type="hidden" name="hidden_address_column" id="hidden_address_column" value="<?php echo $_POST['hidden_address_column']; ?>">
								<input type="hidden" name="hidden_address_lat" id="hidden_address_lat" value="<?php echo $_POST['hidden_address_lat']; ?>"> 
								<input type="hidden" name="hidden_address_lon" id="hidden_address_lon" value="<?php echo $_POST['hidden_address_lon']; ?>"> 
								<input type="hidden" name="hidden_address_radius" id="hidden_address_radius" value="<?php echo $_POST['hidden_address_radius']; ?>"> 
								
								<input type="hidden"
									name="hidden_observed_date_for_file_name_tmp"
									id="hidden_observed_date_for_file_name_tmp" value=""> <input
									type="hidden" name="hidden_file_name" id="hidden_file_name"
									value="<?php echo $_POST['hidden_file_name']; ?>"> <input
									type="hidden" id="hidden_access_token"
									name="hidden_access_token"
									value="<?php echo $_POST['hidden_access_token']; ?>"> <input
									id="hidden_sheet_name" type="hidden" name="hidden_sheet_name"
									value="<?php echo $_POST['hidden_sheet_name']; ?>"> <input
									id="hidden_nature" type="hidden" name="hidden_nature"
									value="<?php echo $_POST['hidden_nature']; ?>"> <input
									id="hidden_sub_nature" type="hidden" name="hidden_sub_nature"
									value="<?php echo $_POST['hidden_sub_nature']; ?>"> <input
									id="hidden_context_broker" type="hidden"
									name="hidden_context_broker"
									value="<?php echo $_POST['hidden_context_broker']; ?>"> <input
									id="hidden_all_headers" type="hidden" name="hidden_all_headers"
									value="<?php echo $_POST['hidden_all_headers']; ?>"> <input
									type="hidden" name="hidden_observed_date_type"
									id="hidden_observed_date_type"
									value="<?php echo $_POST['hidden_observed_date_type']; ?>"> <input
									id="hidden_observed_date_none" type="hidden"
									name="hidden_observed_date_none"
									value="<?php echo $_POST['hidden_observed_date_none']; ?>"> <input
									id="hidden_observed_date_for_sheets_date_time_pickers"
									type="hidden"
									name="hidden_observed_date_for_sheets_date_time_pickers"
									value="<?php echo $_POST['hidden_observed_date_for_sheets_date_time_pickers']; ?>">
								<input
									id="hidden_observed_date_for_sheets_date_time_pickers_for_show"
									type="hidden"
									name="hidden_observed_date_for_sheets_date_time_pickers_for_show"
									value="<?php echo $_POST['hidden_observed_date_for_sheets_date_time_pickers_for_show']; ?>">
								<input id="hidden_observed_date_for_file_name" type="hidden"
									name="hidden_observed_date_for_file_name"
									value="<?php echo $_POST['hidden_observed_date_for_file_name']; ?>">
								<input id="hidden_observed_date_for_row_header" type="hidden"
									name="hidden_observed_date_for_row_header"
									value="<?php echo $_POST['hidden_observed_date_for_row_header']; ?>">
								<input id="hidden_selected_value_names" type="hidden"
									name="hidden_selected_value_names"
									value="<?php echo $_POST['hidden_selected_value_names']; ?>"> <input
									type="hidden" id="hidden_row_count" name="hidden_row_count"> <input
									id="hidden_sheet_names" type="hidden" name="hidden_sheet_names"
									value="<?php echo join("|",$sheet_names); ?>">

							</form>
						</div>
					</div>
				</div>
		</div>
		</div>
    </div>
    

<script type='text/javascript'>

function edit_coord(row_number,columns_count){

	var rows = document.getElementById('table_preview').rows;
	var row = rows[row_number];
	
	lat_td_id="lat_".concat(row_number);
	lon_td_id="lon_".concat(row_number);
	edit_td_id="edit_td_".concat(row_number);
	edit_btn_id="edit_btn_".concat(row_number);
	var edit_btn=document.getElementById(edit_btn_id);
	var cancel_btn=document.getElementById ("cancel_btn_".concat(row_number));
	
	if(edit_btn.value!="Save"){

	//save initial lat and lons
	var init_td_lat=document.getElementById(lat_td_id).innerText;
	var init_td_lon=document.getElementById(lon_td_id).innerText;	
	//create lat textbox
	var lat_input=document.createElement('input');
	lat_input.setAttribute('type','text');
	lat_input.setAttribute("id","tb_lat_".concat(row_number));
	lat_input.value=document.getElementById(lat_td_id).innerText;
	document.getElementById(lat_td_id).innerText="";
	row.cells[columns_count+2].appendChild(lat_input);

	//create lon textbox
	var lon_input=document.createElement('input');
	lon_input.setAttribute('type','text');
	lon_input.setAttribute("id","tb_lon_".concat(row_number));
	lon_input.value=document.getElementById(lon_td_id).innerText;
	document.getElementById(lon_td_id).innerText="";
	row.cells[columns_count+3].appendChild(lon_input);

	//change edit button to save
	edit_btn.value="Save";
	edit_btn.style.backgroundColor = "#4CAF50";
	edit_btn.onclick = function(){save_coord(row_number,columns_count)};

	//Add cancel button
	var cancel_button=document.createElement('button');
	cancel_button.innerHTML = "Cancel"; 
	cancel_button.setAttribute("id","cancel_btn_".concat(row_number));
	cancel_button.style.width="200px";
	cancel_button.style.textAlignLast = "center";
	cancel_button.style.backgroundColor = "darkgrey";
	cancel_button.onclick = function(){cancel_coord(row_number,init_td_lat,init_td_lon,columns_count)};
	document.getElementById(edit_td_id).appendChild(cancel_button);     
	document.getElementById("cancel_btn_".concat(row_number)).className = "cancel_btn";
	}
}

function save_coord(row_number,columns_count){

	var tb_lat_id="tb_lat_".concat(row_number);
	var tb_lon_id="tb_lon_".concat(row_number);

	var tb_lat=document.getElementById(tb_lat_id);
	var tb_lon=document.getElementById(tb_lon_id);
	
	var tb_lat_exists=false;
	var tb_lon_exists=false;
	
	if(typeof(tb_lat) != 'undefined' && tb_lat != null){
		tb_lat_exists=true;
	}

	if(typeof(tb_lon) != 'undefined' && tb_lon != null){
		tb_lon_exists=true;
	}

	var lat_tb_remove=true;
	var lon_tb_remove=true;
	var edit_button_change=true;
	var lat_td_id="lat_".concat(row_number);
	var lon_td_id="lon_".concat(row_number);
	var edit_btn_id="edit_btn_".concat(row_number);

	var cancel_btn_id="cancel_btn_".concat(row_number);
	
	var edit_btn=document.getElementById(edit_btn_id);
	var td_lat=document.getElementById(lat_td_id);
	var td_lon=document.getElementById(lon_td_id);
	var cancel_btn=document.getElementById(cancel_btn_id);

	if(tb_lat_exists==true){
	var tb_lat_value=tb_lat.value;
	var tb_lat_value_number = Number(tb_lat_value);
    var is_tb_lat_value_number_float = tb_lat_value_number === +tb_lat_value_number && tb_lat_value_number !== (tb_lat_value_number | 0);

	if(tb_lat_value.length==0){
		var img = document.createElement('img');
	    img.src = "img/datatablemanager_address_coord_ko.png";
	    img.style.width="25px";
	    td_lat.appendChild(img);
	    tb_lat.remove();
		}else if (is_tb_lat_value_number_float == false) {
	        alert("Latitude value must be float!");
	        tb_lat.style.backgroundColor = "#ffdddd";
	        edit_button_change=false;
		}else{
		document.getElementById(lat_td_id).innerText=tb_lat_value;
        tb_lat.remove();
		}
	}

	if(tb_lon_exists==true){ 
	var tb_lon_value=tb_lon.value;
    var tb_lon_value_number = Number(tb_lon_value);
    var is_tb_lon_value_number_float = tb_lon_value_number === +tb_lon_value_number && tb_lon_value_number !== (tb_lon_value_number | 0);

	if(tb_lon_value.length==0){
		var img = document.createElement('img');
	    img.src = "img/datatablemanager_address_coord_ko.png";
	    img.style.width="25px";
	    td_lon.appendChild(img);
	    tb_lon.remove();
	}else if (is_tb_lon_value_number_float == false) {
        alert("Longitude value must be float!");
        tb_lon.style.backgroundColor = "#ffdddd";    
        edit_button_change=false;
	}else if(edit_button_change==true){
		document.getElementById(lon_td_id).innerText=tb_lon_value;
        tb_lat.remove();
	}
	}

	if(edit_button_change==true){
		cancel_btn.remove();
		//change save button to edit
		edit_btn.value="Edit Coordinate";
		edit_btn.style.backgroundColor = "orange";
		edit_btn.onclick = function(){edit_coord(row_number,columns_count)};
		}
}

function cancel_coord(row_number,td_lat_value,td_lon_value,columns_count){

	var tb_lat_id="tb_lat_".concat(row_number);
	var tb_lon_id="tb_lon_".concat(row_number);

	var tb_lat=document.getElementById(tb_lat_id);
	var tb_lon=document.getElementById(tb_lon_id);
	
	var tb_lat_exists=false;
	var tb_lon_exists=false;
	
	if(typeof(tb_lat) != 'undefined' && tb_lat != null){
		tb_lat_exists=true;
	}

	if(typeof(tb_lon) != 'undefined' && tb_lon != null){
		tb_lon_exists=true;
	}
	
	var lat_td_id="lat_".concat(row_number);
	var lon_td_id="lon_".concat(row_number);
	var edit_td_id="edit_td_".concat(row_number);
	var edit_btn_id="edit_btn_".concat(row_number);
	var cancel_btn_id="cancel_btn_".concat(row_number)
	
	var edit_btn=document.getElementById(edit_btn_id);
	var cancel_btn=document.getElementById(cancel_btn_id);

	var td_lat=document.getElementById(lat_td_id);
	var td_lon=document.getElementById(lon_td_id);
	
	if(td_lat_value==""){

		var img = document.createElement('img');
	    img.src = "img/datatablemanager_address_coord_ko.png";
	    img.style.width="25px";
	    
	    td_lat.appendChild(img);

		}else{
		document.getElementById(lat_td_id).innerText=td_lat_value;
		}

	if(td_lon_value==""){

		var img = document.createElement('img');
	    img.src = "img/datatablemanager_address_coord_ko.png";
	    img.style.width="25px";
	    
	    td_lon.appendChild(img);

		}else{
		document.getElementById(lon_td_id).innerText=td_lon_value;
		}

	//change save button to edit
	edit_btn.value="Edit Coordinate";
	edit_btn.style.backgroundColor = "orange";
	edit_btn.onclick = function(){edit_coord(row_number,columns_count)};

	if(tb_lat_exists==true){
		tb_lat.remove();
		}
	
	if(tb_lat_exists==true){
		tb_lon.remove();
		}
	
	cancel_btn.remove();
}

    $(document).ready(function () {

		var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
		}

		var role_active = $("#role_att").text();
	if (role_active == 'ToolAdmin'){
		$('#sc_mng').show();
	}
	
		//redirect
		var role="<?=$role_att; ?>";
	
		if (role == ""){
			$(document).empty();
			//window.alert("You need to log in to access to this page!");
			if(window.self !== window.top){
			//window.location.href = 'page.php?showFrame=false&pageTitle=Process%20Loader:%20View%20Resources';	
			window.location.href='https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';	
			}
			else{
			window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
			}
		}
		//
		var role_active = "<?=$process['functionalities'][$role]; ?>";

			//console.log(role_active);
			if (role_active == 0){
						$(document).empty();
						if(window.self !== window.top){
						//window.location.href = 'page.php?showFrame=false&pageTitle=Process%20Loader:%20View%20Resources';
						window.location.href='https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';	
						}
						else{
						window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
						}
					}
		//
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
	});
</script>
	<script type="text/javascript">

function validateForm() {

    	if(clicked=='Save'){
	        //check if No cancel button exists
	        var cancel_btn_exists = document.getElementsByClassName('cancel_btn');
			var coord_type="<?php echo $coordinate_type; ?>";
			if (coord_type=='address'){
		         if(cancel_btn_exists.length > 0) {
		            alert("Please, complete editing the coordinates before saving your ingested data!");
		            return false;
		        } else {
					//get the edited lat and lon
					var dateObserved_type="<?php echo $dateObserved_type; ?>";
			        var all_headers_array_count=0;
					var lat_col_index=0;
					var lon_col_index=0;
					
					if(dateObserved_type=='file'){
				         all_headers_array_count="<?php echo count($all_headers_dof_array); ?>";
						 lat_col_index=Number(all_headers_array_count)+3;
						 lon_col_index=Number(all_headers_array_count)+4;
						}else{
						 all_headers_array_count ="<?php echo count($all_headers_array); ?>";
						 lat_col_index=Number(all_headers_array_count)+2;
						 lon_col_index=Number(all_headers_array_count)+3;
					}
			        
					var rows = document.getElementById('table_preview').rows;
					var poi_lats=[];
					var poi_lons=[];

					for (var row_index = 7; row_index < rows.length; row_index++) {
						var row = rows[row_index];
						var lat=row.cells[lat_col_index].innerHTML;
						
						if(lat=='<img style="width:25px" src="img/datatablemanager_address_coord_ko.png">' || lat=='<img src="img/datatablemanager_address_coord_ko.png" style="width: 25px;">'){
							lat="";
						}

						poi_lats[row_index-7]=lat;

						var lon=row.cells[lon_col_index].innerHTML;
						if(lon=='<img style="width:25px" src="img/datatablemanager_address_coord_ko.png">' || lon=='<img src="img/datatablemanager_address_coord_ko.png" style="width: 25px;">'){
							lon="";
							}
						poi_lons[row_index-7]=lon;
						}

		            $('#hidden_address_lats').val(poi_lats.join());
		            $('#hidden_address_lons').val(poi_lons.join());
		            $('#hidden_row_count').val("<?php echo $row_count; ?>");
		    		$('#hidden_file_name').val("<?php echo $file_name; ?>"); 
		            $('#final_table_value_name_Sheet_name_rest').val("<?php echo substr($final_table_value_name_Sheet_name_rest, 0,strlen($final_table_value_name_Sheet_name_rest)-1); ?>");
		    		$('#final_cols_name_Sheet_name_rest').val("<?php echo implode(",",$value_name_types_concat_sheet_name_type_all_headers_array); ?>"); 
		    		$('#hidden_address_warnings').val("<?php echo implode(",",$address_warnings); ?>");
					$('#hidden_lat_row_for_file').val("<?php echo $lat_row_for_file; ?>"); 
					$('#hidden_lon_row_for_file').val("<?php echo $lon_row_for_file; ?>"); 
					
		            if (confirm('Do you really want to save data to the database?')) {
		                document.getElementById("div_please_wait").style.display = 'block';
		                return true;
		            } else {
		                return false;
		            }
			        }
			        }else{

		$('#hidden_row_count').val("<?php echo $row_count; ?>"); 
		$('#hidden_file_name').val("<?php echo $file_name; ?>"); 
        $('#final_table_value_name_Sheet_name_rest').val("<?php echo substr($final_table_value_name_Sheet_name_rest, 0,strlen($final_table_value_name_Sheet_name_rest)-1); ?>"); 
		$('#final_cols_name_Sheet_name_rest').val("<?php echo implode(",",$value_name_types_concat_sheet_name_type_all_headers_array); ?>"); 
		$('#hidden_address_lats').val("<?php echo implode(",",$address_lats); ?>"); 
		$('#hidden_address_lons').val("<?php echo implode(",",$address_lons); ?>"); 
		$('#hidden_address_warnings').val("<?php echo implode(",",$address_warnings); ?>");

		$('#hidden_lat_row_for_file').val("<?php echo $_POST['hidden_lat_row_for_file']; ?>"); 
		$('#hidden_lon_row_for_file').val("<?php echo $_POST['hidden_lon_row_for_file'];  ?>"); 

		if(confirm('Do you really want to save data to the database?')){
			document.getElementById("div_please_wait").style.display='block';
			return true;
			}else{
				return false;
				}
			        }
    	}else{
    		if( confirm('You will probably lose the inserted data. Are you sure?')){
    			var dateObserved_type="<?php echo $_POST['hidden_observed_date_type']?>";
    			var frm = document.getElementById('preview') || null;
    			if(dateObserved_type=='row' || $dateObserved_type == 'row_converted'){
    			   frm.action = 'datatablemanager_values_types_units.php?showFrame=false'; 
    			}else{
    				frm.action='datatablemanager_compose_value_name.php?showFrame=false';
        			}
    			return true;
        	}else{
				return false;
			}
}
}
</script>
</body>
</html>