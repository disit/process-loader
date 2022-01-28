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
// include('control.php');
include_once 'datatablemanager_myUtil.php';
//include('functionalities.php');


if (isset ($_SESSION['username'])){
  $utente_att = $_SESSION['username'];	
}else{
 $utente_att= "Login";	
}

$usernameD = $utente_att;
$ldapUsername = "cn=" . $usernameD . "," . $ldapParamters;
$ds = ldap_connect($ldapServer, '389');
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
$bind = ldap_bind($ds);

$ldapBaseDN=$ldapParamters;

$org = checkLdapOrganization($ds, $ldapUsername, $ldapBaseDN);
// echo $org;


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
	$default_title = "General Information";
}else{
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

        
        <!-- My Stuff -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

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
		<link rel="stylesheet" href="css/datatablemanager_general_information.css">
    </head>
	<body class="guiPageBody">
	<?php
	include_once 'datatablemanager_APICient.php';
	include_once 'datatablemanager_APIQueryManager.php';

require_once ('datatablemanager_SimpleXLSX.php');
$configs = parse_ini_file('datatablemanager_config.ini.php');
$target_dir = $configs['target_dir'];

$file_name_in_target_dir=$_POST['hidden_file_to_uplolad_fromvalue_type_page'];

if(strlen($file_name_in_target_dir)==0){
    $file_name_in_target_dir=$_POST['hidden_file_name'];
}

$xlsx = SimpleXLSX::parse($target_dir . $file_name_in_target_dir);
$sheet_rows_data = $xlsx->rows(0);
$all_headers_array = $sheet_rows_data[0];

$all_headers_array=getFileHeaders($file_name_in_target_dir, $target_dir);


function load_nature_combo() {
    $natures_array=executeGetNatureQuery();
    $nature_combobox_html = "";
    foreach ($natures_array as $nature) {
        $nature_combobox_html .= '<option value="' . $nature . '">' . $nature . '</option>';
    }
    return $nature_combobox_html;
}

function load_cb_combo(){
    
    $cb_query=getCbQuery();

    $cb_array=executeLoadContextBroker($cb_query);
    $cb_combobox_html =  '<option value="" selected disabled hidden selected>-</option>';
    foreach ($cb_array as $cb) {
        if($cb!="" || $cb!=" "){
        $cb_combobox_html .= '<option value="' . $cb . '">' . $cb . '</option>';
        }
    }
    return $cb_combobox_html;
}

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
					<div class="col-xs-12" id="mainContentCnt"
						style='background-color: rgba(138, 159, 168, 1); margin-top: 45px'>
						<div id="container">
							<div id="div_email">
								<img id="email_image"
									src="img/datatablemanager_email_your_question.png" /> <label
									id="email_label">Do you have a question? Send us an email: </label>
								<a id="href_email" href="mailto:snap4city@disit.org">snap4city@disit.org</a>
							</div>
							<form id="general_information" name="general_information" method="POST" action="datatablemanager_coordinates.php?showFrame=false"
								onsubmit="return validateForm()">
								<div id="div_navigation_btns">
									<input id="prevBtn" class="submit" type="submit" value="Back"
										formaction="datatablemanager_upload_file.php?showFrame=false"
										onclick="clicked='back'"> <input class="submit" type="submit"
										onclick="document.getElementById('mainContentCnt').onClick=null;clicked='next'"
										id="submit_button" value="Next"
										style="font-family: Montserrat; background-color: #4CAF50; color: #ffffff; border: none; padding: 10px 20px; font-size: 17px; font-family: Montserrat; cursor: pointer; width: 84px; position: relative; top: 14px; left: 374%;">
								</div>

								<p class="instructor">Please, provide the following information.</p>
								<!-- 	START OF MAIN TABLE -->
								<fieldset class="guideline" id="fieldset_general_information";>
								<legend>More general inforamtion</legend>
								</fieldset>
								<table id="main_table">
									<tr>
										<td style="width: 400px;">
											<!-- 			START OF FIRST TABLE -->
											<table id="table_sheet_name" class="blueTable">
												<tr>
													<td
														style="font-size: 14px; font-weight: bold; background: #1C6EA4; border: 1px solid black; font-family: Montserrat;">Sheet
														Name</td>
												</tr>
												<tr>
													<td style="border: 1px solid black;"><input type="text"
														placeholder="(e.g., Year, Country)" name="sheet_name"
														id="sheet_name"></td>
												</tr>
												<!-- 			END OF FIRST TABLE -->
											</table>
										</td>
										<td style="width: 400px;">
											<table id="table_coordinates" class="blueTable">
												<tr>
													<td
														style="font-size: 14px; font-weight: bold; background: #1C6EA4; border: 1px solid black; font-family: Montserrat;">Context
														Broker</td>
												</tr>
												<tr>
													<td style="border: 1px solid black;"><select
														id="combo_context_broker" name="combo_context_broker"
														class="combo"><?php echo load_cb_combo();?></select></td>
												</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table id="table_nature_sub_nature" class="blueTable">
												<tbody>
													<tr>
														<td
															style="font-size: 14px; font-family: Montserrat; font-weight: bold; background: #1C6EA4; border: 1px solid black;">Nature</td>
														<td
															style="font-size: 14px; font-family: Montserrat; font-weight: bold; background: #1C6EA4; border: 1px solid black;">Sub-Nature</td>
													</tr>
													<tr>
														<td><select id="combo_nature" name="combo_nature"
															class="combo" style="border: 1px solid black;"
															onchange="load_sub_nature_combo('')"><?php echo load_nature_combo();?></select></td>
														<td style="border: solid 1px black;"><select
															id="sub_nature_combo" name="sub_nature_combo"
															style="border: 1px solid black;" class="combo"></select></td>
													</tr>
												</tbody>
												<!--END OF THIRD TABLE -->
											</table>
										</td>
									</tr>
									<!-- 			END OF MAIN TABLE -->
								</table>

							<div id="div_dateObserved">
							<fieldset id="fieldset_general">
							<legend id="legend_question">dateObserved setup</legend>
							</fieldset>
								<div id="div_question">
									<fieldset >
										<legend id="legend_question">Do you have a dateObserved for
											each row in each sheet in your Excel file?</legend>
										<label class="radio-inline" for="radiobutton_yes"
											style="position: absolute; left: 110px; top: 5px;"> <input
											type="radio" id="radiobutton_yes" name="radiobutton_question"
											class="radio_button"
											onchange="setDateObservedDivVisibility('yes')" checked> Yes
										</label> 
										<label class="radio-inline" for="radiobutton_no" style="position: absolute; left: 1090px; top: 5px;"> 
											<input type="radio" id="radiobutton_no" name="radiobutton_question"
											onclick="setDateObservedDivVisibility('no')"> No
										</label>
									</fieldset>
								</div>

								<div id="div_date_observed_for_file">
										<table id="table_date_observed_for_file" class="blueTable">
											<tr>
												<td
													style="font-size: 14px; font-family: Montserrat; font-weight: bold; background: #1C6EA4;">dateObserved</td>
											</tr>
											<tr>
												<td><input type="datetime-local" class="date_time_picker"
													id="date_time_picker_for_file"
													name="date_time_picker_for_file"></td>
											</tr>
											<!-- 			END OF table observed dates for file -->
										</table>

										<fieldset class="guideline" style="height: 150px;">
										<legend class="guideline">Guildelines for selecting a dateObserved</legend>
										<ul class="guideline">
											<li class="li_guideline">Select a dateObserved for the whole file, using the DateTimePicker</li>
											<li class="li_guideline">It will be used as the dateObserved for the all devices that will be created, using the uploaded file</li>
											<li class="li_guideline">Since each dateObserved for a device can have a different time offset, depending on the device location, make sure to pick a time in the morning on the selected date to avoid shifting to the next day</li>
										
										</ul>
									</fieldset>
							</div>

						<!--   div for div_date_observed_for_each_row -->
						<div id="div_date_observed_for_each_row">
							<!-- 			START OF table_observed_date_for_each_row -->
							<table id="table_date_observed_for_each_row" class="blueTable">
								<tr>
									<td style="font-size: 14px; font-family: Montserrat; font-weight: bold; background: #1C6EA4; padding-left: 0px; padding-right: 0px;">dateObserved</td>
								</tr>
								<tr>
									<td><select class="combo" id="combo_observed_date_column" style="width: 100%;" name="combo_observed_date_column" 
										onchange="check_if_is_in_utc_format()">
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
								<!--END OF Table for observed dates for each sheet -->
							</table>

										<fieldset class="guideline" >
										<legend class="guideline">Guildelines for setting dateObserved for each row</legend>
										<ul class="guideline">
											<li class="li_guideline">Select a column that contains dateObserved for each row, using the combobox</li>
											<li class="li_guideline">The selected column values must follow <a href="https://www.w3.org/TR/NOTE-datetime">ISO 8601 standard</a></li>
											<li class="li_guideline">They must follow <strong> YYYY-MM-DDThh:mm:ss.000+Offset </strong> format for setting dateObserved (Example: 2016-12-31T12:00:00.000+02:00)</li>
											<li class="li_guideline">Considering a time-zone, the time-offset is defined as the difference in hours and minutes from UTC for a particular place and date (<a href="https://en.wikipedia.org/wiki/List_of_UTC_time_offsets">More information</a>)</li>
										</ul>
									</fieldset>

						</div>
						</div>
						<!--END OF div_observed_date_tables -->

						<!-- 		Hidden Inputs-->
						<input type="hidden" name="hidden_observed_date_for_file_name_tmp"
							id="hidden_observed_date_for_file_name_tmp" value=""> <input
							type="hidden" name="hidden_file_to_uplolad_fromvalue_type_page"
							id="hidden_file_to_uplolad_fromvalue_type_page"
							value="<?php echo $file; ?>"> <input type="hidden"
							name="hidden_file_name" id="hidden_file_name"
							value="<?php echo $_POST['hidden_file_name']; ?>"> <input
							type="hidden" id="hidden_access_token" name="hidden_access_token"
							value="<?php echo $_POST['hidden_access_token']; ?>"> <input
							id="hidden_sheet_name" type="hidden" name="hidden_sheet_name"
							value=""> <input type="hidden" name="hidden_coordinate_type"
							id="hidden_coordinate_type"
							value="<?php echo $_POST['hidden_coordinate_type']; ?>"> <input
							id="hidden_lat_file" type="hidden" name="hidden_lat_file"
							value="<?php echo $_POST['hidden_lat_file']; ?>"> <input
							id="hidden_lon_file" type="hidden" name="hidden_lon_file"
							value="<?php echo $_POST['hidden_lon_file']; ?>"> <input
							id="hidden_lat_sheet" type="hidden" name="hidden_lat_sheet"
							value="<?php echo $_POST['hidden_lat_sheet']; ?>"> <input
							id="hidden_lon_sheet" type="hidden" name="hidden_lat_sheet"
							value="<?php echo $_POST['hidden_lat_sheet']; ?>"> <input
							id="hidden_lat_row" type="hidden" name="hidden_lat_row"
							value="<?php echo $_POST['hidden_lat_row']; ?>"> <input
							id="hidden_lon_row" type="hidden" name="hidden_lon_row"
							value="<?php echo $_POST['hidden_lon_row']; ?>"> <input
							id="hidden_nature" type="hidden" name="hidden_nature" value=""> <input
							id="hidden_sub_nature" type="hidden" name="hidden_sub_nature"
							value=""> <input id="hidden_context_broker" type="hidden"
							name="hidden_context_broker" value=""> <input type="hidden"
							name="hidden_observed_date_type" id="hidden_observed_date_type"
							value="<?php echo $_POST['hidden_observed_date_type']; ?>"> <input
							id="hidden_observed_date_none" type="hidden"
							name="hidden_observed_date_none"
							value="<?php echo $_POST['hidden_observed_date_none']?>"> <input
							id="hidden_observed_date_for_sheets_date_time_pickers"
							type="hidden"
							name="hidden_observed_date_for_sheets_date_time_pickers" value="">
						<input
							id="hidden_observed_date_for_sheets_date_time_pickers_for_show"
							type="hidden"
							name="hidden_observed_date_for_sheets_date_time_pickers_for_show"
							value=""> <input id="hidden_observed_date_for_file_name"
							type="hidden" name="hidden_observed_date_for_file_name" value="">
						<input id="hidden_observed_date_for_row_header" type="hidden"
							name="hidden_observed_date_for_row_header" value="">
						<!-- 			From next pages -->
						<input id="hidden_selected_value_names" type="hidden"
							name="hidden_selected_value_names"
							value="<?php echo $_REQUEST['hidden_selected_value_names']; ?>">
						<input type="hidden" name="hidden_value_unit_comboboxes"
							id="hidden_value_unit_comboboxes"
							value="<?php echo $_POST['hidden_value_unit_comboboxes']; ?>"> <input
							type="hidden" name="hidden_value_type_comboboxes"
							id="hidden_value_type_comboboxes"
							value="<?php echo $_POST['hidden_value_type_comboboxes']; ?>"> <input
							type="hidden" name="hidden_all_headers" id="hidden_all_headers"
							value="<?php echo implode("|",$all_headers_array); ?>">

						<script
							src="https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"
							type="text/javascript"></script>
						<script
							src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"
							type="text/javascript"></script>
						<link
							href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css"
							rel="Stylesheet" type="text/css" />
						</form>
						</div>
					</div>
				</div>
			</div>
		</div>

	<script type='text/javascript'>

    $(document).ready(function () {

    	document.getElementById("hidden_observed_date_type").value = 'none'; 
		
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

<?php
session_start();
?>

<script type="text/javascript">

//////////////////Show Observed date for file Combobox
var div_date_observed_for_row=document.getElementById('div_date_observed_for_each_row');
div_date_observed_for_row.style.display='block';

var div_date_observed_for_file=document.getElementById('div_date_observed_for_file');
div_date_observed_for_file.style.display='none';	

//Set inital sub nature combo
var hidden_sub_nature_fromNextPages = "<?php echo $_POST['hidden_sub_nature']; ?>";
if(hidden_sub_nature_fromNextPages.length==0){
                    $.ajax({  
                        type:"GET",  
                        url:"datatablemanager_getSubNature.php",  
                        data:"selectedValue=Accommodation",  
                        success:function(data){  
                           var sub_nature_combo=document.getElementById("sub_nature_combo");
                    		var sub_natures=data.split(",");
                    		var s="";
                            for (var i = 0; i < sub_natures.length; i++) {
                                s += '<option value="' + sub_natures[i] + '">' + sub_natures[i]+ '</option>';  
                            }  
                            $("#sub_nature_combo").html(s);  
                        }  
 });  
}
// /////////////////////////////////
var obj = "<?php echo json_decode(htmlspecialchars_decode($_POST['hidden_all_headers'],true)); ?>";
//Set values if coming from the next page
	var hidden_observed_date_type_fromNextPages = "<?php echo $_POST['hidden_observed_date_type']; ?>";
	var set_from_the_next_page=false;

	var hidden_observed_date_for_file_name_fromNextPages = "<?php echo $_POST['hidden_observed_date_for_file_name_for_show']; ?>";
	document.getElementById("date_time_picker_for_file").value =  hidden_observed_date_for_file_name_fromNextPages; 

	var hidden_observed_date_for_row_header_fromNextPages = "<?php echo $_POST['hidden_observed_date_for_row_header']; ?>";
	var hidden_sheet_name_fromNextPages = "<?php echo $_POST['hidden_sheet_name']; ?>";
	var hidden_context_broker_fromNextPages = "<?php echo $_POST['hidden_context_broker']; ?>";
	var hidden_nature_fromNextPages = "<?php echo $_POST['hidden_nature']; ?>";
	var hidden_sub_nature_fromNextPages = "<?php echo $_POST['hidden_sub_nature']; ?>";

// 	document.getElementById("combo_context_broker").value = hidden_context_broker_fromNextPages;
// 	document.getElementById("sheet_name").value = hidden_sheet_name_fromNextPages;
	document.getElementById("combo_observed_date_column").value = "<?php // echo $_POST['hidden_observed_date_for_row_header']; ?>";
	
	load_sub_nature_combo(hidden_nature_fromNextPages);
	set_from_the_next_page=true;
	///////Load Sub nature combobox///////
	function load_sub_nature_combo(tmp_selectedValue) {
    var selectedValue = tmp_selectedValue;

    if(tmp_selectedValue.length==0){
	selectedValue = document.getElementById("combo_nature").value;
    }
    
    $.ajax({  
        type:"GET",  
        url:"datatablemanager_getSubNature.php",  
        data:"selectedValue="+selectedValue,  
        success:function(data){  
           	var sub_nature_combo=document.getElementById("sub_nature_combo");
			var sub_natures=data.split(",");
			var s="";
            for (var i = 0; i < sub_natures.length; i++) {  
                s += '<option value="' + sub_natures[i] + '">' + sub_natures[i]+ '</option>';  
//             	s += '<option ></option>'; 
            }  
            
            $("#sub_nature_combo").html(s); 

    		if (tmp_selectedValue.length!=0){
    			 document.getElementById("combo_nature").value = "<?php echo $_POST['hidden_nature']; ?>";
    			 document.getElementById("sub_nature_combo").value="<?php echo $_POST['hidden_sub_nature']; ?>";
    			}
        }
     });
}
/////////////////////////////////////////////////////////

function setDateObservedDivVisibility(param){

	switch (param) {
	case 'yes':
		  
		  var div_date_observed_for_each_row=document.getElementById('div_date_observed_for_each_row');
		  div_date_observed_for_each_row.style.display='block';

		  var div_date_observed_for_file=document.getElementById('div_date_observed_for_file');
		  div_date_observed_for_file.style.display='none';

//      	  document.getElementById("hidden_observed_date_type").value = 'row'; 

//      	x=document.getElementById("warning_span");
// 		x.style.display = "none";
		document.getElementById("submit_button").disabled = false;

	  var hidden_observed_date_for_row_header_fromNextPages = "<?php echo $_POST['hidden_observed_date_for_row_header']; ?>";
	  if(hidden_observed_date_for_row_header_fromNextPages.length!=0 && set_from_the_next_page==true){
		  	document.getElementById("combo_observed_date_column").value = hidden_observed_date_for_row_header_fromNextPages;
// 			x=document.getElementById("warning_span");
// 			x.style.display = "none";
			document.getElementById("submit_button").disabled = false;
  			set_from_the_next_page=false;
		  	}else{
		  		$("#combo_observed_date_column").val($("#combo_observed_date_column option:first").val());
		  		//check_if_is_in_utc_format();				  
			}
		
		break;

	case 'no':

	 var div_date_observed_for_file=document.getElementById('div_date_observed_for_file');
	 div_date_observed_for_file.style.display='block';

	 var div_date_observed_for_each_row=document.getElementById('div_date_observed_for_each_row');
	 div_date_observed_for_each_row.style.display='none';
	  
	document.getElementById("hidden_observed_date_type").value = 'file'; 

// 	 x=document.getElementById("warning_span");
// 	 x.style.display = "none";
	 document.getElementById("submit_button").disabled = false;

	var hidden_observed_date_for_file_name_fromNextPages = "<?php echo $_POST['hidden_observed_date_for_file_name']; ?>";
	
	break;
	}
}

function validateForm() {

	if (document.getElementById('radiobutton_no').checked) {
			document.getElementById("hidden_observed_date_type").value = 'file'; 
		}
	
	if(clicked=='back'){
		
		//getting the selected value of observed_date_for_file_name
		var tmp_observed_for_file_value = document.getElementById("date_time_picker_for_file").value;
		tmp_observed_for_file_value.toLocaleString('en-US', { timeZone: '<?php echo $configs['time_zone']?>' });
		observed_for_file_value=moment(tmp_observed_for_file_value).utc().format('YYYY-MM-DDTHH:mm:ssZZ');
		document.getElementById("hidden_observed_date_for_file_name").value = observed_for_file_value;
		var e = document.getElementById("combo_observed_date_column");
  		var text = e.options[e.selectedIndex].text;
		document.getElementById("hidden_observed_date_for_row_header").value = text;

		//getting and setting the selected value of sheet name
		var sheet_name_value = document.getElementById("sheet_name").value;
		document.getElementById("hidden_sheet_name").value = sheet_name_value;

		//getting and setting the selected value of sheet name
		var e = document.getElementById("combo_nature");
  		var combo_nature_text = e.options[e.selectedIndex].text;
		document.getElementById("hidden_nature").value = combo_nature_text;
		
		//getting and setting the selected value of sheet name
		var e = document.getElementById("sub_nature_combo");
  		var sub_nature_combo_text = e.options[e.selectedIndex].text;
		document.getElementById("hidden_sub_nature").value = sub_nature_combo_text;

		//getting and setting the selected value of sheet name
		var e = document.getElementById("combo_context_broker");
  		var context_broker_combo_text = e.options[e.selectedIndex].text;
		document.getElementById("hidden_context_broker").value = context_broker_combo_text;

			if(confirm('You will probably lose the inserted data. Are you sure?')){
				return true;
				}else{
					return false;
					}
		}else{
	
	  var sheet_name_text_box_value = document.forms["general_information"]["sheet_name"].value;
	  if (sheet_name_text_box_value == "" || sheet_name_text_box_value == null) {
	  alert("Please, enter a name for sheets");
      var sheet_name_text_box = document.getElementById("sheet_name");
      sheet_name_text_box.style.backgroundColor = "#ffdddd";
	    return false;
	  }

	  var e = document.getElementById("combo_context_broker");
      var context_broker_text = e.options[e.selectedIndex].text;

	  if (context_broker_text == "-") {
	  alert("Please, select a context broker");
      var sheet_name_text_box = document.getElementById("sheet_name");
      e.style.background= "#ffdddd";
	  return false;
	  }else{
			document.getElementById("hidden_context_broker").value = context_broker_text;
	  }

	if(document.getElementById("radiobutton_yes").checked && (document.getElementById("combo_observed_date_column").value=="-" || document.getElementById("combo_observed_date_column").value=="")){
		  alert("Please, select the column that contains the dateObserved");
	      document.getElementById("combo_observed_date_column").style.background= "#ffdddd";
	      return false;
// 	}else if(document.getElementById("radiobutton_yes").checked){
	      
		}else if(document.getElementById("radiobutton_no").checked && document.getElementById("date_time_picker_for_file").value==""){
			  alert("Please, select a dateObserved for the file");
		      document.getElementById("date_time_picker_for_file").style.background= "#ffdddd";
		      return false;	
			}

// check Sheet Distinct Date Observed
if(document.getElementById("radiobutton_yes").checked){

// 	alert('here');
	var file = '<?php echo $_POST['hidden_file_name']; ?>';
	var target_dir = '<?php echo $target_dir; ?>';
	var selected_header = document.getElementById("combo_observed_date_column").value;
	var distinct_sheet_ObservedDates='false';

		$.ajax({  
		 type:"GET",  
		 url:"datatablemanager_checkSheetDistinctDateObserved.php",  
		 data:"selected_header="+selected_header+"&file=" +file+"&target_dir=" +  target_dir,  
		 async: false,
		 success:function(data){  
		 	if(data=='true'){
			 	distinct_sheet_ObservedDates='true';
		     	}
		 }  
		}) 

		if(distinct_sheet_ObservedDates=='true'){
		 	alert("The values of dateObserved column must be disticnt in a sheet!");
		 	return false;
		}
}

		//getting the selected value of observed_date_for_file_name
		var tmp_observed_for_file_value = document.getElementById("date_time_picker_for_file").value;
		//tmp_observed_for_file_value.toLocaleString('en-US', { timeZone: '<?php echo $configs['time_zone']?>' });
// 		observed_for_file_value=moment(tmp_observed_for_file_value).utc().format('YYYY-MM-DDTHH:mm:ssZZ');
		observed_for_file_value=moment(tmp_observed_for_file_value).utc().format('YYYY-MM-DDTHH:mm:ss');
		document.getElementById("hidden_observed_date_for_file_name").value = tmp_observed_for_file_value;
// 		alert(observed_for_file_value);
		//getting and setting the selected value of observed_date_for_row_header
		var e = document.getElementById("combo_observed_date_column");
  		var text = e.options[e.selectedIndex].text;
		document.getElementById("hidden_observed_date_for_row_header").value = text;

		//getting and setting the selected value of sheet name
		var sheet_name_value = document.getElementById("sheet_name").value;
		document.getElementById("hidden_sheet_name").value = sheet_name_value;

		//getting and setting the selected value of sheet name
		var e = document.getElementById("combo_nature");
  		var combo_nature_text = e.options[e.selectedIndex].text;
		document.getElementById("hidden_nature").value = combo_nature_text;
		
		//getting and setting the selected value of sheet name
		var e = document.getElementById("sub_nature_combo");
  		var sub_nature_combo_text = e.options[e.selectedIndex].text;
		document.getElementById("hidden_sub_nature").value = sub_nature_combo_text;

		return true;
		}
		}

//check if the value of date time picker is in utc format
function check_if_is_in_utc_format(){

var selected_header = document.getElementById("combo_observed_date_column").value;
var file = '<?php echo $_POST['hidden_file_name']; ?>';
var target_dir = '<?php echo $target_dir; ?>';

$.ajax({  
 type:"GET",  
 url:"datatablemanager_checkIsoTimeFormat.php",  
 data:"selected_header="+selected_header+"&file=" +file+"&target_dir=" +  target_dir,  
 success:function(data){  
 	if(data=='false'){
				alert('The column values must be convertable to or in UTC format!');
		  		$("#combo_observed_date_column").val($("#combo_observed_date_column option:first").val());
				document.getElementById("submit_button").disabled = true;
     	}else if(data=='true'){
				document.getElementById("submit_button").disabled = false;
         		document.getElementById("hidden_observed_date_type").value = 'row'; 
         		
         	}else{
         		document.getElementById("submit_button").disabled = false;
         		document.getElementById("hidden_observed_date_type").value = 'row_converted'; 
             	}
 }  
});  
}

</script>

</body>
</html>
