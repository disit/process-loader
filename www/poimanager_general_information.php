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
include_once 'poimanager_myUtil.php';
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
$org = checkPoiLdapOrganization($ds, $ldapUsername, $ldapBaseDN);
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

        <title>POI Loader - General Information Insertion</title>
		
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
		<link rel="stylesheet" href="css/poimanager_general_information.css">
    </head>
	<body class="guiPageBody">
	<?php
	include_once 'datatablemanager_APICient.php';
	include_once 'datatablemanager_APIQueryManager.php';
    require_once ('datatablemanager_SimpleXLSX.php');
    
$configs = parse_ini_file('poimanager_config.ini.php');
$target_dir = $configs['target_dir'];

$file_name_in_target_dir=$_POST['hidden_file_to_uplolad_fromvalue_type_page'];

if(strlen($file_name_in_target_dir)==0){
    $file_name_in_target_dir=$_POST['hidden_file_name'];
}

function load_nature_combo() {
    $natures_array=executeGetNatureQuery();
    $nature_combobox_html = "";
    foreach ($natures_array as $nature) {
        $nature_combobox_html .= '<option value="' . $nature . '">' . $nature . '</option>';
    }
    return $nature_combobox_html;
}

function load_lang_combo() {
    $langs=getLanguages();
    $lang_combobox_html = "";
    foreach ($langs as $lang) {
        $lang_arr=explode(',',$lang);
        $lang_str=preg_replace( "/\r|\n/", "", $lang_arr[0]).' ('.$lang_arr[1].')';
        $lang_combobox_html .= '<option value="' . $lang_str . '">' . $lang_str . '</option>';
    }
    return $lang_combobox_html;
}


?>
	<?php include('functionalities.php'); ?>
<!-- 	<div class="container-fluid"> -->
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
							<form id="general_information" name="general_information" method="POST" action="poimanager_preview.php?showFrame=false"
								onsubmit="return validateForm()">
								<div id="div_navigation_btns">
									<input id="prevBtn" class="submit" type="submit" value="Back"
										formaction="poimanager_upload_file.php?showFrame=false"
										onclick="clicked='back'"> <input class="submit" type="submit"
										onclick="document.getElementById('mainContentCnt').onClick=null;clicked='next'"
										id="submit_button" value="Next"
										style="font-family: Montserrat; background-color: #4CAF50; color: #ffffff; border: none; padding: 10px 20px; font-size: 17px; font-family: Montserrat; cursor: pointer; width: 84px; position: relative; top: 14px; left: 374%;">
								</div>

								<p class="instructor">Please, provide the following information.</p>

							<div>
								<table id="table_language" class="blueTable">
									<tbody>
										<tr>
											<td id="td_typical">Language</td>
										</tr>
										<tr>
											<td><select id="combo_lang" name="combo_lang" class="combo"><?php echo load_lang_combo();?></select></td>
										</tr>
									</tbody>
									<!--END OF THIRD TABLE -->
								</table>
								<fieldset class="guideline" style="position: relative; top: -31px; left: 50px; height: 117px;">
									<legend class="guideline">Guildelines for language setting</legend>
									<ul class="guideline">
										<li class="li_guideline">Select the language of file content</li>
										<li class="li_guideline">You can select only one main language. Please, contact us, if your file contains
											multi-language content</li>
									</ul>
								</fieldset>
							</div>

							<div id="div_nature">
								<table id="table_nature_sub_nature" class="blueTable">
									<tbody>
										<tr>
											<td id="td_typical" >Nature</td>
											<td id="td_typical" >Sub-Nature</td>
										</tr>
										<tr>
											<td style="border: solid 1px black;"><select  id="combo_nature" name="combo_nature" class="combo" onchange="load_sub_nature_combo('')"><?php echo load_nature_combo();?></select></td>
											<td><select id="sub_nature_combo" name="sub_nature_combo"  class="combo"></select></td>
										</tr>
									</tbody>
									<!--END OF THIRD TABLE -->
								</table>
								<fieldset class="guideline" style="left: 50px;height: 120px">
									<legend class="guideline">Guildelines for Nature and Sub-Nature setting</legend>
									<ul class="guideline">
									<li class="li_guideline">All POIs in a file must have the same Nature and Sub-Nature</li>
									<li class="li_guideline">If a file contains POIs with different Natures and Sub-Natures, the file must be splitted to two or more files</li>
									</ul>
								</fieldset>
							</div>

							<div id="div_coord">
								<fieldset id="fieldset_coord">
									<legend>Search area setup</legend>
								</fieldset>	
									<table id="table_coord" style="background: none;top: -297px;left: 15px;position: relative;">
										<tr>
											<td style="width: 800px; padding: 0px;">
												<!-- 	START OF Coord Table -->
												<table class="blueTable" style="position: relative; left: 15px;">
													<tbody>
														<tr>
															<td id="td_typical">Center Latitude</td>
															<td id="td_typical">Center Longitude</td>
														</tr>
														<tr>
															<td><input type="text" placeholder="(e.g., 34.2675)" name="latitude" id="latitude"></td>
															<td style="border: solid 1px black;"><input type="text" placeholder="(e.g., 21.5624)" name="longitude"
																id="longitude"></td>
														</tr>
													</tbody>
												</table> <!-- 	START OF Radius Table -->
												<table class="blueTable" style="top: 25px;width: 800px; position: relative; left: 15px;">
													<tbody>
														<tr>
															<td id="td_typical">Radius (in km)</td>
														</tr>
														<tr>
															<td><input type="text" placeholder="(e.g., 2km)"
																name="radius" id="radius"></td>
														</tr>
													</tbody>
												</table> <!-- 			END OF Radius TABLE -->
											</td>
											<td>
												<fieldset class="guideline" style="height: 240px;">
													<legend class="guideline">Guildelines for search area set up</legend>
													<ul class="guideline" style="font-weight: 400; top: -25px;">
														<li class="li_guideline">The file does not contain latitude and longitude for each POI</li>
														<li class="li_guideline">An area of search for ust be specfied for resolving POIs described in the file</li>
														<li class="li_guideline">To do so, a latitude and a longitude for the center of search circle and a search radius must be provided</li>
														<li class="li_guideline">A possible coordinate for each POI is calculated by the tool that can be viewed and edited later</li>
														<li class="li_guideline">Empty address cells are not recommended becuase the associated RDF file will not be created</li>
													</ul>
												</fieldset>
											</td>
										</tr>
									</table>
							</div>

							<!-- 		Hidden Inputs-->
						<input type="hidden" id="hidden_poi_file_type" name="hidden_poi_file_type" value="<?php echo $_POST['hidden_poi_file_type']; ?>">
					      <input type="hidden" name="hidden_file_to_uplolad_fromvalue_type_page"
							id="hidden_file_to_uplolad_fromvalue_type_page"
							value="<?php echo $file_name_in_target_dir; ?>"> <input type="hidden"
							name="hidden_file_name" id="hidden_file_name"
							value="<?php echo $_POST['hidden_file_name']; ?>"> <input
							type="hidden" id="hidden_access_token" name="hidden_access_token"
							value="<?php echo $_POST['hidden_access_token']; ?>"> 
							
							<input id="hidden_lang" type="hidden" name="hidden_lang" value=""> 
							<input id="hidden_nature" type="hidden" name="hidden_nature" value=""> 
							<input 	id="hidden_sub_nature" type="hidden" name="hidden_sub_nature" value=""> 
							<input 	id="hidden_latitude" type="hidden" name="hidden_latitude" value=""> 
							<input 	id="hidden_longitude" type="hidden" name="hidden_longitude" value=""> 
							<input  id="hidden_radius" type="hidden" name="hidden_radius" value=""> 
							
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

	var poi_type="<?php echo $_POST['hidden_poi_file_type']; ?>";

	if(poi_type=="caseb"){
		document.getElementById("div_coord").style.display='block';
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
// 			else{
// 			window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
// 			}
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


var hidden_lang="<?php echo $_POST['hidden_lang']; ?>";

if(hidden_lang.length!=0){
document.getElementById("combo_lang").value=hidden_lang;
}else{
$("#combo_lang").prop("selectedIndex", 0); 	
}

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

	var set_from_the_next_page=false;
	var hidden_nature_fromNextPages = "<?php echo $_POST['hidden_nature']; ?>";
	var hidden_sub_nature_fromNextPages = "<?php echo $_POST['hidden_sub_nature']; ?>";
	
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

function validateForm() {
	
	if(clicked=='back'){ 

		//getting and setting the selected value of latitude
		var sheet_name_value = document.getElementById("latitude").value;
		document.getElementById("hidden_latitude").value = sheet_name_value;

		//getting and setting the selected value of longitude
		var sheet_name_value = document.getElementById("longitude").value;
		document.getElementById("hidden_longitude").value = sheet_name_value;

		//getting and setting the selected value of radies
		var sheet_name_value = document.getElementById("radius").value;
		document.getElementById("hidden_radius").value = sheet_name_value;
		
		//getting and setting the selected value of combo_nature
		var e = document.getElementById("combo_nature");
  		var combo_nature_text = e.options[e.selectedIndex].text;
		document.getElementById("hidden_nature").value = combo_nature_text;
		
		//getting and setting the selected value of sheet name
		var e = document.getElementById("sub_nature_combo");
  		var sub_nature_combo_text = e.options[e.selectedIndex].text;
		document.getElementById("hidden_sub_nature").value = sub_nature_combo_text;

		//getting and setting the selected language
// 		var lang_value = document.getElementById("combo_lang").value.replace(/(\r\n|\n|\r)/gm, "|");
		var lang_value = document.getElementById("combo_lang").value;
		document.getElementById("hidden_lang").value = lang_value;

			if(confirm('You will probably lose the inserted data. Are you sure?')){
				return true;
				}else{
					return false;
					}
		}else{

	 var coord_type="<?php echo $_POST['hidden_poi_file_type']?>"		

	 if(coord_type=="caseb"){
					 
	  var  lat_text_box_value= document.forms["general_information"]["latitude"].value;
	  if (lat_text_box_value == "" || lat_text_box_value == null) {
	  alert("Please, enter a latitude for the center of search area");
      document.getElementById("latitude").style.backgroundColor = "#ffdddd";
	  return false;
	  }

	  var  lon_text_box_value= document.forms["general_information"]["longitude"].value;
	  if (lon_text_box_value == "" || lon_text_box_value == null) {
	  alert("Please, enter a longitude for the center of search area");
      document.getElementById("longitude").style.backgroundColor = "#ffdddd";
	  return false;
	  }

	  var  radius_text_box_value= document.forms["general_information"]["radius"].value;
	  if (radius_text_box_value == "" || radius_text_box_value == null) {
	  alert("Please, enter a radius for the search area");
      document.getElementById("radius").style.backgroundColor = "#ffdddd";
	  return false;
	  }

      var input_lat_file=document.getElementById("latitude");
      var input_lat_file_value = Number(document.getElementById("latitude").value);
      var is_lat_float = input_lat_file_value === +input_lat_file_value && input_lat_file_value !== (input_lat_file_value | 0);
      if (is_lat_float == false) { 
          alert("Center latitude value must be float!");
          document.getElementById("latitude").style.backgroundColor = "#ffdddd";
          return false;
          }

      var input_lon_file_value = Number(document.getElementById("longitude").value);
      var is_lon_float = input_lon_file_value === +input_lon_file_value && input_lon_file_value !== (input_lon_file_value | 0);
      if (is_lon_float == false) { 
          alert("Center longitude value must be float!");
          document.getElementById("longitude").backgroundColor = "#ffdddd";
          return false;
          }

      var  radius_text_box_value= document.forms["general_information"]["radius"].value;
	  var radius=Number(radius_text_box_value);
	  if (!Number.isInteger(radius) && !(radius === +radius && radius !== (radius|0))) {
	  alert("Radius must be integer or float!");
      document.getElementById("radius").style.backgroundColor = "#ffdddd";
	  document.forms["general_information"]["radius"].value=radius_text_box_value;
	  return false;
	  }

		//getting and setting the selected value of language
// 		var lang_value = document.getElementById("combo_lang").value.replace(/(\r\n|\n|\r)/gm, "|");
		var lang_value = document.getElementById("combo_lang").value;
		document.getElementById("hidden_lang").value = lang_value;

		//getting and setting the selected value of latitude
		var sheet_name_value = document.getElementById("latitude").value;
		document.getElementById("hidden_latitude").value = sheet_name_value;

		//getting and setting the selected value of longitude
		var sheet_name_value = document.getElementById("longitude").value;
		document.getElementById("hidden_longitude").value = sheet_name_value;

		//getting and setting the selected value of radies
		var sheet_name_value = document.getElementById("radius").value;
		document.getElementById("hidden_radius").value = sheet_name_value;
	 }

		//getting and setting the selected value of language
// 		var lang_value = document.getElementById("combo_lang").value.replace(/(\r\n|\n|\r)/gm, "|");
		var lang_value = document.getElementById("combo_lang").value;

		document.getElementById("hidden_lang").value = lang_value;
		
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

</script>

</body>
</html>
