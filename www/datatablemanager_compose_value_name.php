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
	$default_title = "Compose Value Name";
}else{
	$default_title = "";
}
?>

<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Data Table Manager - Compose Value Name</title>
		
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
        <script src="https://code.jquery.com/jquery-3.0.0.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-3.3.2.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link rel="stylesheet" href="css/datatablemanager_compose_value_name.css">
        
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
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php 
						include "mobMainMenu.php" 
						?></div>
                    </div>
                    <div class="row" >
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:45px'>

						<div id="container" style="height: fit-content; width: 1600px; position: fixed; top: 3%;">
							<div id="div_email">
								<img id="email_image" src="img/datatablemanager_email_your_question.png" /> 
								<label id="email_label">Do you have a question? Send us an email: </label>
								<a id="href_email" href="mailto:snap4city@disit.org">snap4city@disit.org</a>
							</div>
							<form id="compose_value_name" method="POST" action="datatablemanager_preview.php?showFrame=false" onsubmit="return validateForm()">

								<div id="div_navigation_btns">
									<input id="prevBtn"   class="submit" type="submit" value="Back" onclick="clicked='Back'" formaction="datatablemanager_values_types_units.php?showFrame=false"> 
									<input class="submit" style="font-family: 'Montserrat';" type="submit" id="submit_button" value="Next" onclick="clicked='Next'">
								</div>

								<p class="instructor" style="font-family: Montserrat;font-weight:bold; font-size: 14px;">Please, compose a value name.</p>

								<div class="guideline">
									<fieldset class="guideline">
										<legend class="guideline">Suggested guildelines for composing a value name</legend>
    										<ul class="guideline">
    											<li class="li_guideline">A value name is used to indentify an IOT device</li>
    											<li class="li_guideline">By default, file name and sheet name (defined in the previous step) are included in value name</li>
    											<li class="li_guideline">At least, the values of one column, among the selected columns, must be distinct</li>
    											<li class="li_guideline">The values of selected columns must not contain special characters including (For example,?,#,@,%)</li>
    											<li class="li_guideline">The values of selected columns should not contain parentheses. Otherwise, they are replaced with brackets</li>
    											<li class="li_guideline">The values of selected columns should not contain blank spaces. Otherwise, they are removed</li>
    											<li class="li_guideline">The values of selected columns should not contain non-UTF-8 (e.g., non-English). Otherwise, they are replaced with their equivalent English letters </li>
    										</ul>
									</fieldset>
								</div>

								<div id="div_selectors">
									<label id="label_select1">Column Headers</label>
									<ul>
										<li><select id="select1" name="select1" multiple></select></li>
										<li id="li_add_remove_btns" class="relative">
										<input type="button" id="add" value=">" onclick="addOptions( 'select1', 'select2','add' )" /> 
										<input type="button" id="remove" value="<" onclick="addOptions( 'select2', 'select1','remove' )" /></li>
										<li><select id="select2" name="select2" multiple></select></li>
									</ul>
									<label id="label_select2">Selected Column Headers</label>
								</div>
                                <!-- Hidden Inputs -->
							     <!--Hidden Inputs from the current page-->
								<input  type="hidden" name="hidden_value_unit_comboboxes" id="hidden_value_unit_comboboxes"	value="<?php echo $_POST['hidden_value_unit_comboboxes']; ?>"> 
								<input  type="hidden" name="hidden_value_type_comboboxes" id="hidden_value_type_comboboxes"	value="<?php echo $_POST['hidden_value_type_comboboxes']; ?>"> 
								<!-- 		Hidden Inputs   from Previous pages-->
                                <input type="hidden" name="hidden_coordinate_type" id="hidden_coordinate_type" value="<?php echo $_POST['hidden_coordinate_type']; ?>"> 
                                
                                <input id="hidden_lat_file" type="hidden" name="hidden_lat_file" value="<?php echo $_POST['hidden_lat_file']; ?>">
                    			<input id="hidden_lon_file" type="hidden" name="hidden_lon_file" value="<?php echo $_POST['hidden_lon_file']; ?>">
                    			
                                <input id="hidden_lat_sheet" type="hidden" name="hidden_lat_sheet" value="<?php echo $_POST['hidden_lat_sheet']; ?>">
                        		<input id="hidden_lon_sheet" type="hidden" name="hidden_lat_sheet" value="<?php echo $_POST['hidden_lat_sheet']; ?>">
                        		
                                <input id="hidden_lat_row" type="hidden" name="hidden_lat_row" value="<?php echo $_POST['hidden_lat_row']; ?>">
                        		<input id="hidden_lon_row" type="hidden" name="hidden_lon_row" value="<?php echo $_POST['hidden_lon_row']; ?>">
                        		            
                        		<input id="hidden_lat_row_for_file" type="hidden" name="hidden_lat_row_for_file" value="<?php echo $_POST['hidden_lat_row_for_file']; ?>">
    							<input id="hidden_lon_row_for_file" type="hidden" name="hidden_lon_row_for_file" value="<?php echo $_POST['hidden_lon_row_for_file']; ?>">            
                        		            
                        		            <!-- 			From previous pages -->
                        		            
                        		<input type="hidden" name="hidden_address_column" id="hidden_address_column" value="<?php echo $_POST['hidden_address_column']; ?>">
								<input type="hidden" name="hidden_address_lat" id="hidden_address_lat" value="<?php echo $_POST['hidden_address_lat']; ?>"> 
								<input type="hidden" name="hidden_address_lon" id="hidden_address_lon" value="<?php echo $_POST['hidden_address_lon']; ?>"> 
								<input type="hidden" name="hidden_address_radius" id="hidden_address_radius" value="<?php echo $_POST['hidden_address_radius']; ?>"> 
								            
                    			<input type="hidden" name="hidden_observed_date_for_file_name_tmp" id="hidden_observed_date_for_file_name_tmp" value=""> 
                    			<input type="hidden" name="hidden_file_name" id="hidden_file_name" value="<?php echo $_POST['hidden_file_name']; ?>"> 
                    			<input type="hidden" id="hidden_access_token" name="hidden_access_token" value="<?php echo $_POST['hidden_access_token']; ?>"> 
                    			<input id="hidden_sheet_name" type="hidden" name="hidden_sheet_name" value="<?php echo $_POST['hidden_sheet_name']; ?>">

                    			<input id="hidden_nature" type="hidden" name="hidden_nature" value="<?php echo $_POST['hidden_nature']; ?>">
                    			<input id="hidden_sub_nature" type="hidden" name="hidden_sub_nature" value="<?php echo $_POST['hidden_sub_nature']; ?>">
                    			<input id="hidden_context_broker" type="hidden" name="hidden_context_broker" value="<?php echo $_POST['hidden_context_broker']; ?>">
                    			<input id="hidden_all_headers" type="hidden" name="hidden_all_headers" value="<?php echo $_POST['hidden_all_headers'] ?>">
                                <input type="hidden" name="hidden_observed_date_type" id="hidden_observed_date_type" value="<?php echo $_POST['hidden_observed_date_type']; ?>"> 
                                <input id="hidden_observed_date_none" type="hidden" name="hidden_observed_date_none" value="<?php echo $_POST['hidden_observed_date_none']; ?>">
                                <input id="hidden_observed_date_for_sheets_date_time_pickers" type="hidden" name="hidden_observed_date_for_sheets_date_time_pickers" value="<?php echo $_POST['hidden_observed_date_for_sheets_date_time_pickers']; ?>">
                        		<input id="hidden_observed_date_for_sheets_date_time_pickers_for_show" type="hidden" name="hidden_observed_date_for_sheets_date_time_pickers_for_show" value="<?php echo $_POST['hidden_observed_date_for_sheets_date_time_pickers_for_show']; ?>">
                        		<input id="hidden_observed_date_for_file_name" type="hidden" name="hidden_observed_date_for_file_name" value="<?php echo $_POST['hidden_observed_date_for_file_name']; ?>">
                        		<input id="hidden_observed_date_for_row_header" type="hidden" name="hidden_observed_date_for_row_header" value="<?php echo $_POST['hidden_observed_date_for_row_header']; ?>">
<!-- 								<input id="hidden_selected_value_names" type="hidden" name="hidden_selected_value_names">  -->
							
								<input type="hidden" id="hidden_file_to_uplolad_fromvalue_type_page" name="hidden_file_to_uplolad_fromvalue_type_page"	value="<?php echo $_POST['hidden_file_to_uplolad_fromvalue_type_page']; ?>">
								<input type="hidden" name="hidden_selected_value_names" id="hidden_selected_value_names" value="<?php echo $_POST['hidden_selected_value_names']; ?>"> 
								<input id="hidden_selected_value_names_dateobserved_file" type="hidden" name="hidden_selected_value_names_dateobserved_file"> 
								
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
header('Content-Type: application/json');
// Initializing the session
session_start();
include('datatablemanager_myUtil.php');
$configs = parse_ini_file('datatablemanager_config.ini.php');
$observed_date_type=$_POST['hidden_observed_date_type'];
$hidden_observed_date_for_row_header=$_POST['hidden_observed_date_for_row_header'];
$previousely_set_select2 = explode("|",$_POST['hidden_selected_value_names']);
$target_dir = $configs['target_dir'];

$file_name = $_POST['hidden_file_name'];

if (strlen($file_name) == 0) {
    $file_name = $_POST['hidden_file_to_uplolad_fromvalue_type_page'];
}

$all_headers_array = getFileHeaders($file_name, $target_dir);
//if select 1 and 2 previouselt set
// echo $_POST['hidden_selected_value_names'];
if(!empty($_POST['hidden_selected_value_names'])){
    //remove from select 1
    foreach ($previousely_set_select2 as $value){
        unset($all_headers_array[array_search($value, $all_headers_array)]); // remove item at index 0
        $all_headers_array= array_values($all_headers_array); // 'reindex' array
    }
    //add to select 2
    $list_counter = 0;
    $list_len = count($previousely_set_select2);
    echo "<script>";
    do {
        echo "  $('#select2').append('<option >$previousely_set_select2[$list_counter]</option>');";
        ++$list_counter;
    } while ($list_counter < $list_len);
    echo "</script>";
}

if($observed_date_type=='row' || $observed_date_type=='row_converted'){
    unset($all_headers_array[array_search($hidden_observed_date_for_row_header, $all_headers_array)]); // remove item at index 0
    $all_headers_array = array_values($all_headers_array); // 'reindex' array
}

$select1_list = array();

for ($cell_number = 0; $cell_number < count($all_headers_array); $cell_number ++) {
    $select1_list[$cell_number] = $all_headers_array[$cell_number];
}

$list_counter = 0;
$list_len = count($select1_list);
echo "<script>";
do {
    echo "  $('#select1').append('<option >$select1_list[$list_counter]</option>');";
    ++ $list_counter;
} while ($list_counter < $list_len);

echo "</script>";
?>

<script type="text/javascript">
$("#select2 option[value='']").remove();
function addOptions( fromId, toId,mode ) {

    var fromEl = document.getElementById( fromId ),
    toEl = document.getElementById( toId );

    if ( fromEl.selectedIndex >= 0 ) {
        var index = toEl.options.length;

        for ( var i = 0; i < fromEl.options.length; i++ ) {
            if ( fromEl.options[ i ].selected ) {
                toEl.options[ index ] = fromEl.options[ i ];
                i--;
                index++
            }
        }
    }
    
// 	if(mode=='add'){

//     	var e = document.getElementById("select1");
//     	var selected_header=e.options[e.selectedIndex].text;//get the selected option text

// 		$.ajax({  
// 		    type:"GET",  
// 		    url:"datatablemanager_checkDistinctCol.php",
// 		    data:"target_dir="+target_dir+"&file_name="+file_name+"&selected_header="+selected_header,
// 		    async:false,     
// 		    success:function(data){ 
// 				alert(data);
// 				if(data=="false"){
// 					alert('here');
// 					alert("Following the provided guidlines, the selected column must contain unique data!")
// 					}else{
// 					    if ( fromEl.selectedIndex >= 0 ) {
// 					        var index = toEl.options.length;

// 					        for ( var i = 0; i < fromEl.options.length; i++ ) {
// 					            if ( fromEl.options[ i ].selected ) {
// 					                toEl.options[ index ] = fromEl.options[ i ];
// 					                i--;
// 					                index++
// 					            }
// 					        }
// 					    }
// 						}
// 		    },
// 		    error: function (xhr, error) {
// 		        console.debug(xhr); 
// 		        console.debug(error);
// 		      }
// 			});

// 	}else{
// 	    if ( fromEl.selectedIndex >= 0 ) {
// 	        var index = toEl.options.length;

// 	        for ( var i = 0; i < fromEl.options.length; i++ ) {
// 	            if ( fromEl.options[ i ].selected ) {
// 	                toEl.options[ index ] = fromEl.options[ i ];
// 	                i--;
// 	                index++
// 	            }
// 	        }
// 	    }
// 		}
}

function validateForm() {

//     var x = document.getElementById("select1");
//     var i;
//     all_columns = [];
//     for (i = 0; i < x.length; i++) {
//     	all_columns[i]=x.options[i].text;
//     }

	if(clicked=='Next'){
	$("#select2 option").map(function(){ return this.value }).get();

	var selected_value_types_array = [];
	$('#select2').children().each(function () {    
		selected_value_types_array.push($(this).val()); //put them in array
	});

	var hidden_all_headers_string="<?php echo implode("|", $all_headers_array)?>";
	document.getElementById("hidden_all_headers").value = hidden_all_headers_string;
	
	var selected_value_types_array_string=selected_value_types_array.join(",");
	var selectIsValid = true;
	var target_dir="<?php echo $target_dir?>";
	var file_name="<?php echo $file_name?>";

	//check Columns have special characters
// 	$.ajax({  
// 	    type:"GET",  
// 	    url:"datatablemanager_checkSpecialCharacterCol.php",
// 	    data:"target_dir="+target_dir+"&file_name="+file_name+"&selected_headers="+selected_value_types_array_string,
// 	    async:false,     
// 	    success:function(data){ 
// 			if(data=='true'){
// 				var non_distinct_headers=data.split(","); 
// 				console. log(non_distinct_headers);
// 				alert("Following the provided guidlines, selected columns must not contain contain special characters!")
// 				selectIsValid = false;
// 				}else{
// 					var selected_value_types_string= selected_value_types_array.join('|');
// 					$('#hidden_selected_value_names').val(selected_value_types_string);
// 					}
// 	    },
// 	    error: function (xhr, error) {
// 	        console.debug(xhr); 
// 	        console.debug(error);
// 	      }
// 		});

		//check Columns have distinct values	
		$.ajax({  
		    type:"GET",  
		    url:"datatablemanager_checkDistinctCol.php",
		    data:"target_dir="+target_dir+"&file_name="+file_name+"&selected_headers="+selected_value_types_array_string,
		    async:false,     
		    success:function(data){ 

				if(data=='true'){
					var non_distinct_headers=data.split(","); 
					console.log(non_distinct_headers);
					alert("Selected columns must not contain duplicate data or special characters!")
					selectIsValid = false;
					}else{
						var selected_value_types_string= selected_value_types_array.join('|');
						$('#hidden_selected_value_names').val(selected_value_types_string);
						}
		    },
		    error: function (xhr, error) {
		        console.debug(xhr); 
		        console.debug(error);
		      }
			});

	if ($('#select2').has('option').length ==0) {
	      alert("Please select at least one header column for composing the value name!");
	      return false;
	    }else{
	    return selectIsValid;
	    }
	}else{
		if( confirm('You will probably lose the inserted data. Are you sure?')){
			return true;
			}else{
				return false;
			}
	}
}
</script>

</body>
</html>