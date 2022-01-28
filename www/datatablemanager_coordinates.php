<!DOCTYPE html>
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
                        // include 'datatablemanager_myUtil.php';
                        // include('functionalities.php');

if (isset($_SESSION['username'])) {
    $utente_att = $_SESSION['username'];
} else {
    $utente_att = "Login";
}

if (isset($_SESSION['username'])) {
    $role_att = $_SESSION['role'];
} else {
    $role_att = "";
}

// if (isset($_REQUEST['showFrame'])){
// if ($_REQUEST['showFrame'] == 'false'){
// //echo ('true');
$hide_menu = "hide";
// }else{
// $hide_menu= "";
// }
// }else{$hide_menu= "";}

if (! isset($_GET['pageTitle'])) {
    $default_title = "Coordinates";
} else {
    $default_title = "";
}
?>

<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard Management System</title>

<!-- jQuery -->
<script src="jquery/jquery-1.10.1.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="bootstrap/bootstrap.min.js"></script>

<!-- Bootstrap Core CSS -->
<link href="bootstrap/bootstrap.css" rel="stylesheet">

<!-- Bootstrap toggle button -->
<link href="bootstrapToggleButton/css/bootstrap-toggle.min.css"
	rel="stylesheet">
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


<!-- My Stuff -->
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.0.0.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.3.2.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Raleway"
	rel="stylesheet">
<link rel="stylesheet" href="css/datatablemanager_coordinates.css">
</head>
<body class="guiPageBody">
	<?php
include_once 'datatablemanager_APICient.php';
include_once 'datatablemanager_APIQueryManager.php';

require_once ('datatablemanager_SimpleXLSX.php');
$configs = parse_ini_file('datatablemanager_config.ini.php');
$target_dir = $configs['target_dir'];

$file = $_POST['hidden_file_to_uplolad_fromvalue_type_page'];

if (strlen($file) == 0) {
    $file = $_POST['hidden_file_name'];
}

// $file='testApi.xlsx';

$xlsx = SimpleXLSX::parse($target_dir . $file);
$sheet_rows_data = $xlsx->rows(0);
$all_headers_array = $sheet_rows_data[0];
$sheet_names = $xlsx->sheetNames();

// }

?>
	<?php include('functionalities.php'); ?>
        <div class="container-fluid">
		<div class="row mainRow"
			style='background-color: rgba(138, 159, 168, 1)'>
                <?php include "mainMenu.php" ?>
				<div class="col-xs-12 col-md-10" id="mainCnt">
				<div class="row hidden-md hidden-lg">
					<div id="mobHeaderClaimCnt"
						class="col-xs-12 hidden-md hidden-lg centerWithFlex">Snap4City</div>
				</div>
				<div class="row" id="title_row">
					<div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt"><?php echo urldecode($_GET['pageTitle']); ?></div>
					<div class="col-xs-2 hidden-md hidden-lg centerWithFlex"
						id="headerMenuCnt"><?php
    include "mobMainMenu.php"?></div>
				</div>
				<div class="row">
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top: 45px'>
						<div id="container">
							<div id="div_email">
								<img id="email_image" src="img/datatablemanager_email_your_question.png" /> 
								<label id="email_label">Do you have a question? Send us an email: </label>
								<a id="href_email" href="mailto:snap4city@disit.org">snap4city@disit.org</a>
							</div>
							<form id="coordinates" name="coordinates" method="POST" action="datatablemanager_values_types_units.php?showFrame=false" onsubmit="return validateForm()">
								<div id="div_navigation_btns">
									<input id="prevBtn" class="submit" type="submit" value="Back" formaction="datatablemanager_general_information.php?showFrame=false" onclick="clicked='back'"> <input class="submit" type="submit" onclick="document.getElementById('mainContentCnt').onClick=null;clicked='next'" id="submit_button" value="Next" style="font-family: 'Montserrat'; background-color: #4CAF50; color: #ffffff; border: none; padding: 10px 20px; font-size: 17px; font-family: Raleway; cursor: pointer; width: 84px; position: relative; top: 14px; left: 374%;">
								</div>

								<p class="instructor" style="font-family: Montserrat; font-weight: bold; font-size: 14px;">Please, provide the coordinate information</p>
								<!-- 	START OF MAIN TABLE -->
								<div>
									<fieldset>
										<legend id="legend_coordinate">Do you have a latitude and longitude for each row</legend>
										<input type="radio" id="radiobutton_row_yes" name="radiobutton_coordinate" onchange="set_visibility('row')" style="position: relative; top: 5px; left: 81px;" checked> 
										<label class="label_coordinate" for="radiobutton_row_yes" style="position: relative; left: -110px;; top: 3px;">Yes</label>

										<input type="radio" id="radiobutton_row_no" name="radiobutton_coordinate" class="radio_button_coordinate" onclick="set_visibility('address')" style="position: relative; left: 130px; top: 5px;"> 
										<label class="label_coordinate" for="radiobutton_row_no" style="position: relative; left: -70px; top: 3px;">No</label>
									</fieldset>

									<!--field set address -->
									<fieldset id="fieldset_address" style="left: 790px; display: none">
										<legend id="legend_coordinate">Do you have an address for each row</legend>
										<input type="radio" id="radiobutton_address_no" name="radiobutton_address" class="radio_button_address" onclick="set_visibility('file')" style="position: relative; top: 5px; left: 585px;">
										<label class="label_coordinate" for="radiobutton_address_no" style="position: relative; left: 395px; top: 3px;">No</label>

										<input type="radio" id="radiobutton_address_yes" name="radiobutton_address" onchange="set_visibility('address')" style="position: relative; left: -390px; top: 5px;"> 
										<label class="label_coordinate" for="radiobutton_address" style="position: relative; left: -580px; top: 3px;">Yes</label>
									</fieldset>
								</div>
								<!-- 			START OF table observed dates for file -->
												<div id="div_coordinate_file" class="divs"  style="display: none">
													<table id="table_coordinate_files" class="blueTable" style="width: 700px; left: 30px;">
														<tr>
															<td style="border: 1px solid black; background: #1C6EA4; font-family: Montserrat; font-size: 14px; border-right: solid 1px; font-weight: bold;">Latitude</td>
															<td style="border: 1px solid black; background: #1C6EA4; font-family: Montserrat; font-size: 14px; border-right: solid 1px; font-weight: bold;">Longitude</td>
														</tr>
														<tr>
															<td style="border: 1px solid black;">
															<input type="text" placeholder="(e.g., 64.8443)" name="lat" id="input_lat_file"></td>
															<td style="border: 1px solid black;">
															<input type="text" placeholder="(e.g., 38.8951)" name="lon" id="input_lon_file"></td>
														</tr>
														</tbody>
													</table>
													<!-- 			END OF SECOND TABLE -->
													<fieldset class="guideline">
														<legend class="guideline">Guildelines for setting latitude and longitude for a file</legend>
														<ul class="guideline">
															<li class="li_guideline">Select columns that contain latitude and longitude values</li>
															<li class="li_guideline">The values of selected columns must be float</li>
															<li class="li_guideline">The values of selected columns must use point (.), not comma, to separate the integral part of a number from the decimal part</li>
														</ul>
													</fieldset>
											</div>
												<!--   div for div_coordinate_for_each_row -->
												<div id="div_coordinate_row" class="divs">
													<!-- 			START OF table_coordinate_for_each_row -->
													<table id="table_coordinate_row" class="blueTable" style="width: 700px; left: 30px;">
														<tr>
															<td style="background: #1C6EA4; width: 400px; border: 1px solid black; background: #1C6EA4; font-size: 14px; border-right: solid 1px; font-weight: bold; font-family: Montserrat;">Latitude</td>
															<td style="background: #1C6EA4; width: 400px; border: 1px solid black; background: #1C6EA4; font-size: 14px; border-right: solid 1px; font-weight: bold; font-family: Montserrat;">Longitude</td>
														</tr>
														<tr>
															<td style="border: 1px solid black;">
															<select class="combo" id="combo_lat_row" style="width: 100%;" name="combo_lat_row" onchange="check_col_float('combo_lat_row')">
													<?php
            $headers = '<option value="" selected disabled hidden selected>-</option>';
            foreach ($all_headers_array as $value) {
                // Add category option to category combo-box
                $headers .= '<option value="' . $value . '">' . $value . '</option>';
            }
            echo $headers;
            ?>
                			</select></td>
													<td><select class="combo" id="combo_lon_row" style="width: 100%;" name="combo_lon_row" onchange="check_col_float('combo_lon_row')">
													<?php
            $headers = '<option value="" selected disabled hidden selected>-</option>';
            foreach ($all_headers_array as $value) {
                // Add category option to category combo-box
                $headers .= '<option value="' . $value . '">' . $value . '</option>';
            }
            echo $headers;
            ?>
                			</select></td></tr></tbody></table>
													<!--END OF div for observed dates for each sheet -->
													<fieldset class="guideline">
														<legend class="guideline">Guildelines for selecting latitude and longtitude columns</legend>
														<ul class="guideline">
															<li class="li_guideline">Select columns that contain latitude and longitude values</li>
															<li class="li_guideline">The values of selected columns must be float (e.g., 38.8951)</li>
															<li class="li_guideline">The values of selected columns should use point (not comma) to separate the integral part of a number from the decimal part. Otherwise, they are replaced with point (.)</li>
														</ul>
													</fieldset>
												</div>

								<div id="div_address" class="divs" style="display: none">
                                                    <!--Table address column -->
									<table id="table_address_column" class="blueTable" style="width: 700px; left: 30px;">
										<tr>
											<td
												style="background: #1C6EA4; width: 400px; border: 1px solid black; background: #1C6EA4; font-size: 14px; border-right: solid 1px; font-weight: bold; font-family: Montserrat;">Address</td>
										</tr>
										<tr>
											<td style="border: 1px solid black;"><select class="combo"
												id="combo_address" style="width: 100%;" name="combo_address"
												onchange="check_col_string()">
			<?php
            $headers = '<option value="" selected disabled hidden selected>-</option>';
            foreach ($all_headers_array as $value) {
                // Add category option to category combo-box
                $headers .= '<option value="' . $value . '">' . $value . '</option>';
            }
            echo $headers;
            ?>
                			</select></td>
										</tr>
										</tbody>
									</table>
									
									<fieldset class="guideline" style="height: 270px;">
										<legend class="guideline">Guildelines for setting up the coordinates of devices (or instances)</legend>
										<ul class="guideline">
											<li class="li_guideline">The goal is to assign a coordinate to each row (device or instance)</li>
											<li class="li_guideline">To do so, select the column that describe the address for each row</li>
											<li class="li_guideline">Also, an area of search is used to resolve devices (or instances) described in the file</li>
											<li class="li_guideline">For this, a latitude and a longitude for the center of search circle and a search radius must be provided</li>
											<li class="li_guideline">A possible coordinate for the row (device or instance) is calculated by the tool that can be viewed and edited later, during the data ingestion</li>
											<li class="li_guideline">Empty address cells are not recommended becuase the associated device (or instance) will not be created</li>
										</ul>
									</fieldset>
									
									<!--end Table address column -->
									<fieldset class="guideline" style="left: 0px; width: 747px; height: 300px;top: -155px;">
										<legend class="guideline">Search area setup</legend>
									<table id="table_address_coord" class="blueTable" style="top: 10px; width: 700px; left: 15px; position: inherit;">
										<!-- 	START OF Coord Table -->
										<tbody>
											<tr>
												<td style="background: #1C6EA4; width: 400px; border: 1px solid black; background: #1C6EA4; font-size: 14px; border-right: solid 1px; font-weight: bold; font-family: Montserrat;">Center Latitude</td>
												<td style="background: #1C6EA4; width: 400px; border: 1px solid black; background: #1C6EA4; font-size: 14px; border-right: solid 1px; font-weight: bold; font-family: Montserrat;">Center Longitude</td>
											</tr>
											<tr>
												<td style="border: 1px solid black;">
												<input type="text" placeholder="(e.g., 34.2675)" name="address_lat" id="address_lat"></td>
												<td style="border: solid 1px black;">
												<input type="text" placeholder="(e.g., 21.5624)" name="address_lon" id="address_lon"></td>
											</tr>

										</tbody>
									</table>
									<!-- 			Radius TABLE -->
									<table id="table_address_radius" class="blueTable" style="top: 40px; left: 15px; width: 701px; position: inherit;">
										<!-- 	START OF Coord Table -->
										<tbody>
											<tr>
												<td id="td_typical" style="background: #1C6EA4; width: 400px; border: 1px solid black; background: #1C6EA4; font-size: 14px; border-right: solid 1px; font-weight: bold; font-family: Montserrat;">Radius (in km)</td>
											</tr>
											<tr>
												<td style="border: 1px solid black;"><input type="text" placeholder="(e.g., 2km)" name="address_radius" id="address_radius"></td>
											</tr>
										</tbody>
									</table>
                                    <!-- 			END OF Radius TABLE -->
								</fieldset>
								</div>

								<!-- 		Hidden Inputs-->
								<input type="hidden" name="hidden_address_column" id="hidden_address_column" value="">
								<input type="hidden" name="hidden_address_lat" id="hidden_address_lat" value=""> 
								<input type="hidden" name="hidden_address_lon" id="hidden_address_lon" value=""> 
								<input type="hidden" name="hidden_address_radius" id="hidden_address_radius" value=""> 
								<input type="hidden" name="hidden_coordinate_type" id="hidden_coordinate_type" value=""> 
								<input id="hidden_lat_file" type="hidden" name="hidden_lat_file" value=""> 
								<input id="hidden_lon_file" type="hidden" name="hidden_lon_file" value=""> 
								<input id="hidden_lat_sheet" type="hidden" name="hidden_lat_sheet" value=""> 
								<input id="hidden_lon_sheet" type="hidden" name="hidden_lat_sheet" value=""> 
								<input id="hidden_lat_row" type="hidden"
									name="hidden_lat_row" value=""> <input id="hidden_lon_row"
									type="hidden" name="hidden_lon_row" value=""> <input
									id="hidden_lat_row_for_file" type="hidden"
									name="hidden_lat_row_for_file" value=""> <input
									id="hidden_lon_row_for_file" type="hidden"
									name="hidden_lon_row_for_file" value="">

								<!-- 			From next pages -->
								<input type="hidden"
									name="hidden_observed_date_for_file_name_tmp"
									id="hidden_observed_date_for_file_name_tmp" value=""> <input
									type="hidden" name="hidden_file_to_uplolad_fromvalue_type_page"
									id="hidden_file_to_uplolad_fromvalue_type_page"
									value="<?php echo $_POST['hidden_file_to_uplolad_fromvalue_type_page']; ?>">
								<input type="hidden" name="hidden_file_name"
									id="hidden_file_name"
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

								<!-- 			From next pages -->
								<input id="hidden_selected_value_names" type="hidden"
									name="hidden_selected_value_names"
									value="<?php echo $_REQUEST['hidden_selected_value_names']; ?>">
								<input type="hidden" name="hidden_value_unit_comboboxes"
									id="hidden_value_unit_comboboxes"
									value="<?php echo $_POST['hidden_value_unit_comboboxes']; ?>">
								<input type="hidden" name="hidden_value_type_comboboxes"
									id="hidden_value_type_comboboxes"
									value="<?php echo $_POST['hidden_value_type_comboboxes']; ?>">


								<script
									src="https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"
									type="text/javascript"></script>
								<script
									src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"
									type="text/javascript"></script>
								<link
									href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css"
									rel="Stylesheet" type="text/css" />
								<span class="label warning" id="warning_span">Cell values must
									be float!</span>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type='text/javascript'>

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
			if (role_active === 0){
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

<?php
session_start();
?>

<script type="text/javascript">
// $("#radiobutton_row_yes").attr('checked',true);
// document.getElementById("radiobutton_row_yes").checked = true;
// document.getElementById("radiobutton_address_yes").checked = true;
// document.getElementById("radiobutton_row_no").checked = false;
// document.getElementById("hidden_coordinate_type").value = 'file';
function check_col_float(select_id){
	var selected_header = document.getElementById(select_id).value;
	var file = '<?php echo $file; ?>';
	var target_dir = '<?php echo $target_dir; ?>';
	
	$.ajax({  
	 type:"GET",  
	 url:"datatablemanager_checkFloatType.php",  
	 data:"selected_header="+selected_header+"&file=" +file+"&target_dir=" +  target_dir,  
	 success:function(data){  
	 	if(data=='false'){
	 				alert("The column values must be float!");
					document.getElementById("submit_button").disabled = true;
					document.getElementById(select_id).style.background = "#ffdddd";
	     	}else{
				document.getElementById(select_id).style.background = "#f1f1f1";
				document.getElementById("submit_button").disabled = false;
	         	}
	 }  
	}); 
}

function check_col_string(){
	var selected_header = document.getElementById("combo_address").value;
	var file = '<?php echo $file; ?>';
	var target_dir = '<?php echo $target_dir; ?>';
	
	$.ajax({  
	 type:"GET",  
	 url:"datatablemanager_checkStringType.php",  
	 data:"selected_header="+selected_header+"&file=" +file+"&target_dir=" +  target_dir,  
	 success:function(data){  
	 	if(data=='false'){
	 				alert("The addresses must be string, not numbers or float!");
					document.getElementById("submit_button").disabled = true;
					document.getElementById("combo_address").style.background = "#ffdddd";
	     	}else{
				document.getElementById("combo_address").style.background = "#f1f1f1";
				document.getElementById("submit_button").disabled = false;
	         	}
	 }  
	}); 
}

function set_visibility(param) {
		var set_from_the_next_page=true;
		switch(param) {
		  case 'file':

				document.getElementById("radiobutton_address_yes").checked = false;
				document.getElementById("radiobutton_address_no").checked = true;
				document.getElementById("radiobutton_row_no").checked = true;
				document.getElementById("radiobutton_row_yes").checked = false;

			    document.getElementById("submit_button").disabled = false;
				
			  var div_coordinate_for_file=document.getElementById('div_coordinate_file');
			  div_coordinate_for_file.style.display='block';

			  var div_coordinate_for_each_row=document.getElementById('div_coordinate_row');
			  div_coordinate_for_each_row.style.display='none';	

			  var div_address=document.getElementById('div_address');
			  div_address.style.display='none';
			  
			  document.getElementById("hidden_coordinate_type").value = 'file';

		      break;

		  case 'row':

			  document.getElementById("radiobutton_address_yes").checked = false;
			  document.getElementById("radiobutton_address_no").checked = false;
			  document.getElementById("radiobutton_row_no").checked = false;
				  
			  var div_coordinate_row=document.getElementById('div_coordinate_row');
			  div_coordinate_row.style.display='block';

			  var div_coordinate_file=document.getElementById('div_coordinate_file');
			  div_coordinate_file.style.display='none';

			  var div_coordinate_file=document.getElementById('fieldset_address');
			  div_coordinate_file.style.display='none';


			  document.getElementById('div_address').style.display = 'none'
				  
			  document.getElementById("hidden_coordinate_type").value = 'row';

			  var hidden_lat_row_header_fromNextPages = "<?php  echo $_POST['hidden_lat_row']; ?>";
			  var hidden_lon_row_header_fromNextPages = "<?php  echo $_POST['hidden_lon_row']; ?>";

			  if(hidden_lat_row_header_fromNextPages.length!="-" && set_from_the_next_page==true){
				  	document.getElementById("combo_lat_row").value = hidden_lat_row_header_fromNextPages;
				  	document.getElementById("combo_lon_row").value = hidden_lon_row_header_fromNextPages;
		  			set_from_the_next_page=false;
				  	}else{
				  		$("#combo_lat_row").val($("#combo_lat_row option:first").val());
				  		$("#combo_lon_row").val($("#combo_lon_row option:first").val());
					}
			     		break;

		  		case 'address':
					document.getElementById("radiobutton_address_no").checked = false;
					document.getElementById("radiobutton_row_no").checked = true;
					document.getElementById("radiobutton_row_yes").checked = false;
					document.getElementById("radiobutton_address_yes").checked = true;
						
				  	document.getElementById('fieldset_address').style.display = 'block';
				  	document.getElementById('div_address').style.display = 'block';

					var div_coordinate_row=document.getElementById('div_coordinate_row');
					div_coordinate_row.style.display='none';	

					var div_coordinate_row=document.getElementById('div_coordinate_file');
					div_coordinate_row.style.display='none';	

					document.getElementById("hidden_coordinate_type").value = 'address';
					     }
}
/////////////Check if lat and lon are float////////////////////////////////////////////
	function validateForm() {

	    if (clicked == 'back') {

	        //getting and setting the selected value of sheet name
	        var input_lat_file = document.forms["coordinates"]["input_lat_file"].value;
	        document.getElementById("hidden_lat_file").value = input_lat_file;

	        //getting and setting the selected value of sheet name
	        var input_lon_file = document.forms["coordinates"]["input_lon_file"].value;
	        document.getElementById("hidden_lon_file").value = input_lon_file;

	        //getting and setting the selected value of sheet name
	        var e = document.getElementById("combo_lat_row");
	        var combo_lat_row_text = e.options[e.selectedIndex].text;
	        document.getElementById("hidden_lat_row").value = combo_lat_row_text;

	        var e = document.getElementById("combo_lon_row");
	        var combo_lon_row_text = e.options[e.selectedIndex].text;
	        document.getElementById("hidden_lon_row").value = combo_lon_row_text;

	        var e = document.getElementById("combo_address");
	        var combo_address = e.options[e.selectedIndex].text;
	        document.getElementById("hidden_address_column").value = combo_address;

	        var e = document.getElementById("address_lat");
	        var address_lat = e.options[e.selectedIndex].text;
	        document.getElementById("hidden_address_lat").value = address_lat;

	        var e = document.getElementById("address_lon");
	        var address_lon = e.options[e.selectedIndex].text;
	        document.getElementById("hidden_address_lon").value = address_lon;

	        var e = document.getElementById("address_radius");
	        var address_radius = e.options[e.selectedIndex].text;
	        document.getElementById("hidden_address_radius").value = address_radius;

	        if (confirm('You will probably lose the inserted data. Are you sure?')) {
	            return true;
	        } else {
	            return false;
	        }
	    } else {

	        var radio_buttons = document.getElementsByName('radiobutton_coordinate');
	        for (var i = 0; i < radio_buttons.length; i++) {
	            if (radio_buttons[i].checked) {
	                id = radio_buttons[i].id;
	            if(id == "radiobutton_row_yes") {
	                    if ((document.getElementById("combo_lat_row").value == "-") || (document.getElementById("combo_lat_row").value == "")) {
	                        alert("Please, select a column that contains latitude for rows!");
	                        document.getElementById("combo_lat_row").style.background = "#ffdddd";
	                        return false;
	                    } else {
	                        //set hidden_lat_row
	                        var e = document.getElementById("combo_lat_row");
	                        var combo_lat_row_text = e.options[e.selectedIndex].text;
	                        document.getElementById("hidden_lat_row").value = combo_lat_row_text;
	                        //set lat for the file when coord_type='row'
	                        var file_name = '<?php echo $file?>';
	                        var target_dir = '<?php echo $target_dir?>';

	                        $.ajax({
	                            type: "GET",
	                            url: "datatablemanager_getFileCoord.php",
	                            data: "file_name=" + file_name + "&target_dir=" + target_dir + "&column=" + combo_lat_row_text,
	                            dataType: 'json',
	                            async: false, //IGNORED!!
	                            success: function (data) {
	                                document.getElementById("hidden_lat_row_for_file").value = data;
	                            },
	                            error: function (xhr, error) {
	                                console.debug(xhr);
	                                console.debug(error);
	                            }
	                        });
	                    }
	                    if ((document.getElementById("combo_lon_row").value == "-") || (document.getElementById("combo_lon_row").value == "")) {
	                        alert("Please, select a column that contains longitude for rows!");
	                        document.getElementById("combo_lon_row").style.backgroundColor = "#ffdddd";
	                        return false;
	                    } else {
	                        //set hidden_lon_row
	                        var e = document.getElementById("combo_lon_row");
	                        var combo_lon_row_text = e.options[e.selectedIndex].text;
	                        document.getElementById("hidden_lon_row").value = combo_lon_row_text;
	                        //set lon for the file when coord_type='row'
	                        var file_name = '<?php echo $file?>';
	                        var target_dir = '<?php echo $target_dir?>';

	                        $.ajax({
	                            type: "GET",
	                            url: "datatablemanager_getFileCoord.php",
	                            data: "file_name=" + file_name + "&target_dir=" + target_dir + "&column=" + combo_lon_row_text,
	                            dataType: 'json',
	                            async: false, //IGNORED!!
	                            success: function (data) {
	                                document.getElementById("hidden_lon_row_for_file").value = data;
	                            },
	                            error: function (xhr, error) {
	                                console.debug(xhr);
	                                console.debug(error);
	                            }
	                        });
	                    }
	                }else {
	                	var address_rbs = document.getElementsByName('radiobutton_address');
	                    for (var i = 0; i < address_rbs.length; i++) {
	                        if (address_rbs[i].checked) {
	                            id = address_rbs[i].id;
	                	if(id == "radiobutton_address_yes"){
	                    
					var address_lat=document.getElementById("address_lat");
	                var address_lon=document.getElementById("address_lon");
	                var address_radius=document.getElementById("address_radius");
	                
	                var address_lat_value = Number(document.getElementById("address_lat").value);
	                var address_lon_value = Number(document.getElementById("address_lon").value);
	                var address_radius_value = Number(document.getElementById("address_radius").value);
	                
	                var is_address_lat_value_float = address_lat_value === +address_lat_value && address_lat_value !== (address_lat_value | 0);
	                var is_address_lon_value_float = address_lon_value === +address_lon_value && address_lon_value !== (address_lon_value | 0);

	                if (address_lat_value == "") {
	                    alert("Please, insert a latitiude for the center of search circle!");
	                    address_lat.style.backgroundColor = "#ffdddd";
	                    return false;
	                } else if (is_address_lat_value_float == false) {
	                    alert("Latitude value must be float!");
	                    address_lat.style.backgroundColor = "#ffdddd";
	                    return false;
	                } else {
	                    //setting hidden_lat_file
	                    var address_lat_value = document.forms["coordinates"]["address_lat"].value.trim();
	                    document.getElementById("hidden_address_lat").value = address_lat_value;
	                }

	                if (address_lon_value == "") {
	                    alert("Please, insert a longitude for the center of search circle!");
	                    address_lon.style.backgroundColor = "#ffdddd";
	                    return false;
	                } else if (is_address_lon_value_float == false) {
	                    alert("Longitude value must be float!");
	                    address_lon.style.backgroundColor = "#ffdddd";
	                    return false;
	                }else{
	                    var address_lon_value = document.forms["coordinates"]["address_lon"].value.trim();
	                    document.getElementById("hidden_address_lon").value = address_lon_value;
	                    }

	            	  if (!Number.isInteger(address_radius_value) && !(address_radius_value === +address_radius_value && address_radius_value !== (address_radius_value|0))) {
	              		  alert("Radius must be integer or float!");
	              	      document.getElementById("address_radius").style.backgroundColor = "#ffdddd";
	              		  document.forms["coordinates"]["address_radius"].value=address_radius_value;
	              		  return false;
	              		  }else{
	                          document.getElementById("hidden_address_radius").value = address_radius_value;
	                  		  }
	                
	                if ((document.getElementById("combo_address").value == "-") || (document.getElementById("combo_address").value == "")) {
	                    alert("Please, select a column that contains address for rows!");
	                    document.getElementById("combo_address").style.background = "#ffdddd";
	                    return false;
	                } else {
	                    //set hidden_lat_row
	                    var e = document.getElementById("combo_address");
	                    var combo_address_text = e.options[e.selectedIndex].text;
	                    document.getElementById("hidden_address_column").value = combo_address_text;
	                    //set lat for the file when coord_type='row'
	                    }
	                	}else{

	                        var input_lat_file=document.getElementById("input_lat_file");
	                        var input_lon_file=document.getElementById("input_lon_file");
	                        var input_lat_file_value = Number(document.getElementById("input_lat_file").value);
	                        var input_lon_file_value = Number(document.getElementById("input_lon_file").value);
	                        var is_lat_file_value_float = input_lat_file_value === +input_lat_file_value && input_lat_file_value !== (input_lat_file_value | 0);
	                        var is_lon_file_value_float = input_lon_file_value === +input_lon_file_value && input_lon_file_value !== (input_lon_file_value | 0);

	                        if (input_lat_file_value == "") {
	                            alert("Please, insert a latitiude for the file!");
	                            input_lat_file.style.backgroundColor = "#ffdddd";
	                            return false;
	                        } else if (is_lat_file_value_float == false) {
	                            alert("Latitude value must be float!");
	                            input_lat_file.style.backgroundColor = "#ffdddd";
	                            return false;
	                        } else {
	                            //setting hidden_lat_file
	                            var lat_text_box_value = document.forms["coordinates"]["input_lat_file"].value.trim();
	                            document.getElementById("hidden_lat_file").value = lat_text_box_value;
	                        }

	                        if (input_lon_file_value == "") {
	                            alert("Please, insert a longitude for the file!");
	                            input_lon_file.style.backgroundColor = "#ffdddd";
	                            return false;
	                        } else if (is_lon_file_value_float == false) {
	                            alert("Longitude value must be float!");
	                            input_lon_file.style.backgroundColor = "#ffdddd";
	                            return false;
	                        } else {
	                            //setting hidden_lon_file
	                            var lon_text_box_value = document.forms["coordinates"]["lon"].value.trim();
	                            document.getElementById("hidden_lon_file").value = lon_text_box_value;
	                        }

	                    	}
	                        }
	                }
	            }
	        }
	    }
	    }
	        return true;
	    }

</script>

</body>
</html>

<!-- function isNumberKey(evt, element) { -->
<!-- 	  var charCode = (evt.which) ? evt.which : event.keyCode -->
<!-- 	  if (charCode>31 && (charCode<48 || charCode>57) && !(charCode == 46 || charCode == 8)) -->
<!-- 	    return false; -->
<!-- 	  else { -->
<!-- 	    var len = $(element).val().length; -->
<!-- 	    var index = $(element).val().indexOf('.'); -->
<!-- 	    if (index > 0 && charCode == 46) { -->
<!-- 	      return false; -->
<!-- 	    } -->
<!-- 	    if (index > 0) { -->
<!-- 	      var CharAfterdot = (len + 1) - index; -->
<!-- 	    } -->
<!-- 	  } -->
<!-- 	  return true; -->
<!-- 	} -->
