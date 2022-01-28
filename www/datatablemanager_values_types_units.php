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
	$default_title = "Choose Value Types and Value Units";
}else{
	$default_title = "";
}
?>

<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Data Table Manager - Setting Value Types and Value Units</title>
		
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
		<link rel="stylesheet" href="css/datatablemanager_values_types_units.css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.0.0.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-3.3.2.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        
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
                    <div class="row" id="title_row" style="display: none;">
                        <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt"><?php echo urldecode($_GET['pageTitle']); ?></div>
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php include "mobMainMenu.php" 
						?></div>
                    </div>
                    <div class="row" >
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:45px'>

						<div id="container">
							<div id="div_email">
								<img id="email_image" src="img/datatablemanager_email_your_question.png" /> 
								<label id="email_label">Do you have a question? Send us an email: </label>
								<a id="href_email" href="mailto:snap4city@disit.org">snap4city@disit.org</a>
							</div>

							<form id="values_types_units" name="value_form" method="POST" action="datatablemanager_compose_value_name.php?showFrame=false"
								onsubmit="return validateForm()">
								<div id="div_navigation_btns" >
									<input id="prevBtn" class="submit" style="font-family: 'Montserrat';" type="submit" value="Back" onclick="clicked='back'" formaction="datatablemanager_coordinates.php?showFrame=false">
									<input class="submit" type="submit" id="submit_button" value="Next" onclick="clicked='next'">
								</div>
								<p class="instructor" style="font-family: Montserrat;font-weight:bold; font-size: 14px;">Please, select value types and value units for each column header</p>

								<div id="vlaue_type_comboboxes">
									<table id="table_value_type_unit" class="blueTable">
										<tr>
											<td style="font-family: Montserrat; font-size: 14px; background: #1C6EA4; font-weight: bold; padding: 20px;width: 900px">Column Header</td>
											<td style="font-family: Montserrat;font-size: 14px; background: #1C6EA4; font-weight: bold; padding: 20px;width: 700px">Value Type</td>
											<td style="font-family: Montserrat; font-size: 14px; background: #1C6EA4; font-weight: bold; padding: 20px;width: 100px">Value Unit</td>
<?php 
session_start();
include 'datatablemanager_APICient.php';
include 'datatablemanager_APIQueryManager.php';
require_once ('datatablemanager_SimpleXLSX.php');
$configs = parse_ini_file('datatablemanager_config.ini.php');
$target_dir = $configs['target_dir'];
$file=$_POST['hidden_file_to_uplolad_fromvalue_type_page'];
if(strlen($file)==0){
    $file=$_POST['hidden_file_name'];
}

if (SimpleXLSX::parse($target_dir . $file)) {
    $xlsx = SimpleXLSX::parse($target_dir . $file);
    $sheet_rows_data = $xlsx->rows(0);
    $all_headers_array = $sheet_rows_data[0];
    
    $dateObserved_col_header=$_POST['hidden_observed_date_for_row_header'];
    $dateObserved_col_header_type= $_POST['hidden_observed_date_type'];

    for ($header_index=0;$header_index<count($all_headers_array);$header_index++) {

        if(($dateObserved_col_header_type=='row' || $dateObserved_col_header_type=='row_converted') && $dateObserved_col_header==$all_headers_array[$header_index]){
            $date_observed_row_index=$header_index;
            ?>
										<tr id="tr_<?php echo $header_index;?>" style="display: none;">
											<td style="text-align: left;width: 700px"><label class="label_header" for="combo_value_type"><?php echo $all_headers_array[$header_index];?></label></td>
											<td style="width: 700px;"><select id="combo_value_type_<?php echo $header_index; ?>" class="combo_value_type" onchange="load_value_unit_combo('<?php echo $header_index;?>')"></select></td>
											<td style="width: 480px;"><select id="combo_value_unit_<?php echo $header_index; ?>" class="combo_value_unit"></select></td>
										</tr>
<?php }else{?>
    									<tr id="tr_<?php echo $header_index;?>">
											<td style="text-align: left;width:600px"><label class="label_header" for="combo_value_type"><?php echo $all_headers_array[$header_index];?></label></td>
											<td style="width: 600px;"><select id="combo_value_type_<?php echo $header_index; ?>" class="combo_value_type" onchange="load_value_unit_combo('<?php echo $header_index;?>')"></select></td>
											<td style="width: 580px;"><select id="combo_value_unit_<?php echo $header_index; ?>" class="combo_value_unit"></select></td>
										</tr>
<?php }
    }
}?> 
			</table>
								</div>
								
								<!--Hidden Inputs from the current page-->
								<input  type="hidden" name="hidden_value_unit_comboboxes" id="hidden_value_unit_comboboxes"	value=""> 
								<input  type="hidden" name="hidden_value_type_comboboxes" id="hidden_value_type_comboboxes"	value=""> 
								
								<!-- 		Hidden Inputs   from Previous pages-->
                                                        		            
                        		<input type="hidden" name="hidden_address_column" id="hidden_address_column" value="<?php echo $_POST['hidden_address_column']; ?>">
								<input type="hidden" name="hidden_address_lat" id="hidden_address_lat" value="<?php echo $_POST['hidden_address_lat']; ?>"> 
								<input type="hidden" name="hidden_address_lon" id="hidden_address_lon" value="<?php echo $_POST['hidden_address_lon']; ?>"> 
								<input type="hidden" name="hidden_address_radius" id="hidden_address_radius" value="<?php echo $_POST['hidden_address_radius']; ?>"> 
                                
                                <input type="hidden" name="hidden_coordinate_type" id="hidden_coordinate_type" value="<?php echo $_POST['hidden_coordinate_type']; ?>"> 
                                
                                <input id="hidden_lat_file" type="hidden" name="hidden_lat_file" value="<?php echo $_POST['hidden_lat_file']; ?>">
                    			<input id="hidden_lon_file" type="hidden" name="hidden_lon_file" value="<?php echo $_POST['hidden_lon_file']; ?>">
                    			
                                <input id="hidden_lat_sheet" type="hidden" name="hidden_lat_sheet" value="<?php echo $_POST['hidden_lat_sheet']; ?>">
                        		<input id="hidden_lon_sheet" type="hidden" name="hidden_lat_sheet" value="<?php echo $_POST['hidden_lat_sheet']; ?>">
                        		
                                <input id="hidden_lat_row" type="hidden" name="hidden_lat_row" value="<?php echo $_POST['hidden_lat_row']; ?>">
                        		<input id="hidden_lon_row" type="hidden" name="hidden_lon_row" value="<?php echo $_POST['hidden_lon_row']; ?>">
                        		            
                        		<input id="hidden_lat_row_for_file" type="hidden" name="hidden_lat_row_for_file" value="<?php echo $_POST['hidden_lat_row_for_file']; ?>">
    							<input id="hidden_lon_row_for_file" type="hidden" name="hidden_lon_row_for_file" value="<?php echo $_POST['hidden_lon_row_for_file']; ?>">            
                    			
                    			<input type="hidden" name="hidden_observed_date_for_file_name_tmp" id="hidden_observed_date_for_file_name_tmp" value=""> 
                    			<input type="hidden" name="hidden_file_name" id="hidden_file_name" value="<?php echo $_POST['hidden_file_name']; ?>"> 
                    			<input type="hidden" id="hidden_access_token" name="hidden_access_token" value="<?php echo $_POST['hidden_access_token']; ?>"> 
                    			<input id="hidden_sheet_name" type="hidden" name="hidden_sheet_name" value="<?php echo $_POST['hidden_sheet_name']; ?>">

                    			<input id="hidden_nature" type="hidden" name="hidden_nature" value="<?php echo $_POST['hidden_nature']; ?>">
                    			<input id="hidden_sub_nature" type="hidden" name="hidden_sub_nature" value="<?php echo $_POST['hidden_sub_nature']; ?>">
                    			<input id="hidden_context_broker" type="hidden" name="hidden_context_broker" value="<?php echo $_POST['hidden_context_broker']; ?>">
                    			<input id="hidden_all_headers" type="hidden" name="hidden_all_headers" value="<?php echo $_POST['hidden_all_headers']; ?>">
                                <input type="hidden" name="hidden_observed_date_type" id="hidden_observed_date_type" value="<?php echo $_POST['hidden_observed_date_type']; ?>"> 
                                <input id="hidden_observed_date_none" type="hidden" name="hidden_observed_date_none" value="<?php echo $_POST['hidden_observed_date_none']; ?>">
                                <input id="hidden_observed_date_for_sheets_date_time_pickers" type="hidden" name="hidden_observed_date_for_sheets_date_time_pickers" value="<?php echo $_POST['hidden_observed_date_for_sheets_date_time_pickers']; ?>">
                        		<input id="hidden_observed_date_for_sheets_date_time_pickers_for_show" type="hidden" name="hidden_observed_date_for_sheets_date_time_pickers_for_show" value="<?php echo $_POST['hidden_observed_date_for_sheets_date_time_pickers_for_show']; ?>">
                        		<input id="hidden_observed_date_for_file_name" type="hidden" name="hidden_observed_date_for_file_name" value="<?php echo $_POST['hidden_observed_date_for_file_name']; ?>">
                        		<input id="hidden_observed_date_for_row_header" type="hidden" name="hidden_observed_date_for_row_header" value="<?php echo $_POST['hidden_observed_date_for_row_header']; ?>">
								<!--Hidden Divs from Next pages-->
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
///////////////////////////////////
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

var dateObserved_type='<?php echo $_POST['hidden_observed_date_type']; ?>';
//Initially fill combo_value_units with "-"
var combo_value_units = document.getElementsByClassName('combo_value_unit');
// LOOP THROUGH EACH ELEMENT.
	for (j = 0; j < combo_value_units.length; j++) {
		id= document.getElementsByClassName('combo_value_unit')[j].id;
		var option = document.createElement("option");
// 		option.text = "";
// 		option.value = "";
		var select = document.getElementById(id);
		select.appendChild(option);
		}
/////////////////////File Value_type Comboboxes///////////////////////////////
var combo_value_types = document.getElementsByClassName('combo_value_type');
var combo_value_units = document.getElementsByClassName('combo_value_unit');
var label_headers = document.getElementsByClassName('label_header');

var s="<option value='-'>-</option>";
// LOOP THROUGH EACH ELEMENT.
	$.ajax({  
	    type:"GET",  
	    url:"datatablemanager_getValueType.php",  
	    success:function(data){  
        	var value_type_combo_id=data.split(",");
        	for (var i = 0; i < value_type_combo_id.length; i++) {  
        	    s += '<option value="' + value_type_combo_id[i] + '">' + value_type_combo_id[i]+ '</option>';  
        	}  

        	for (j = 0; j < combo_value_types.length; j++) {
        		id= document.getElementsByClassName('combo_value_type')[j].id;
        		var dateObserved_for_row_value_type_combo_id='combo_value_type_<?php echo $date_observed_row_index; ?>';
        		if(dateObserved_for_row_value_type_combo_id==id &&  (dateObserved_type=='row' || dateObserved_type=='row_converted')){
					
        			var option = document.createElement("option");
        			option.text = "timestamp";
        			option.value = "timestamp";
        			var select = document.getElementById(dateObserved_for_row_value_type_combo_id);
        			select.appendChild(option);
        			select.disabled=true;
        			}else{
        				$("#"+id).html(s);
        	    		}

				//Set the selected value of vt combobox if previousely set
            	var hidden_value_type_comboboxes = "<?php echo $_POST['hidden_value_type_comboboxes']; ?>";
            	var hidden_value_unit_comboboxes = "<?php echo $_POST['hidden_value_unit_comboboxes']; ?>";
            	if(hidden_value_type_comboboxes.length!=0){
            			hidden_value_type_comboboxes_array=hidden_value_type_comboboxes.split("|");
            			document.getElementById(id).value=hidden_value_type_comboboxes_array[j];
            			//Set the selected value of vu combobox if previousely set
            			var hidden_value_unit_comboboxes_array=hidden_value_unit_comboboxes.split("|");
						var combo_vu_id= document.getElementsByClassName('combo_value_unit')[j].id;
						var value_type=document.getElementsByClassName('label_header')[j].innerHTML;
 						load_value_unit_combo(j);
            			document.getElementById(combo_vu_id).value=hidden_value_unit_comboboxes_array[j];
                	}
            	}
	    }
	 });  

function load_value_unit_combo(col_index) {
	
	var id='combo_value_type_'+ col_index;
    var selectedValue = document.getElementById(id).value;
    $.ajax({  
        type:"GET",  
        url:"datatablemanager_getValueUnit.php",  
        data:"selectedValue="+selectedValue,  
        success:function(data){  
        value_unit_combo_id='combo_value_unit_'+col_index;	

        if(data.includes(",")){
        	var value_units_array=data.split(",");
        	var s="";
            for (var i = 0; i < value_units_array.length; i++) {  
                s += '<option value="' + value_units_array[i] + '">' + value_units_array[i]+ '</option>';  
            }  
    		$("#"+value_unit_combo_id).html(s); 
	   }else if(data.length==0){
			var option = document.createElement("option");
// 			option.text = "-";
// 			option.value = "-";
			var select = document.getElementById(value_unit_combo_id);
			select.appendChild(option);
		}else{
        	var s="";
        	s='<option value="' + data+ '">' + data+ '</option>';
        	$("#"+value_unit_combo_id).html(s);  
    	}
        }  
     }); 
}

//disable and hide comboboxes for dateObserved column

if(dateObserved_type=='row' || dateObserved_type=='row_converted'){

	var dateObserved_for_row_value_unit_combo_id='combo_value_unit_<?php echo $date_observed_row_index ?>';
	var dateObserved_for_row_value_unit_combo= document.getElementById(dateObserved_for_row_value_unit_combo_id);
	dateObserved_for_row_value_unit_combo.disabled=true;

    $.ajax({  
        type:"GET",  
        url:"datatablemanager_getValueUnit.php",  
        data:"selectedValue=timestamp",  
        success:function(data){  
        	
        if(data.includes(",")){
	
	var value_units_array=data.split(",");
	var s="";
    for (var i = 0; i < value_units_array.length; i++) {  
        s += '<option value="' + value_units_array[i] + '">' + value_units_array[i]+ '</option>';  
    }  
    $("#"+dateObserved_for_row_value_unit_combo_id).html(s); 
}else if(data.length==0){
	var option = document.createElement("option");
// 	option.text = "-";
// 	option.value = "-";
	var select = document.getElementById(dateObserved_for_row_value_unit_combo_id);
	select.appendChild(option);
}else{
	var s="";
		s='<option value="' + data+ '">' + data+ '</option>';
		$("#"+dateObserved_for_row_value_unit_combo_id).html(s);  
}
        }  
     }); 
    var tr_for_row_id='tr_<?php echo $date_observed_row_index ?>';
    document.getElementById(tr_for_row_id).style.display = 'none';
}

//////////////Adjust form height//////////////////////////////////////////////////
var height = document.getElementById('vlaue_type_comboboxes'). offsetHeight;
var height_int= parseInt(height);
var height_int_plus_50=height_int+200;
var height_int_plus_50_string=height_int_plus_50.toString();
height_int_plus_50_string+="px";
document.getElementById('values_types_units').style.height =height_int_plus_50_string;

$(document).ready(function(){
    $('input[type="radio"]').click(function(){
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
        $(targetBox).show();
    });
});

function validateForm() {
	if(clicked=='next'){
		var dateObserved_type="<?php echo $_POST['hidden_observed_date_type']?>";

if(dateObserved_type=='row' || dateObserved_type=='row_converted'){
	var frm = document.getElementById('values_types_units') || null;
	if(frm) {
	   frm.action = 'datatablemanager_preview.php?showFrame=false';
	}
}	
		//check a value type for the all columns are set
		var combo_value_types = document.getElementsByClassName('combo_value_type');
		// LOOP THROUGH EACH ELEMENT.
		for (i = 0; i < combo_value_types.length; i++) {
					
		        	tmp=combo_value_types[i].value;
					if(tmp=="-" ||tmp==""){
						alert("Please, select a value type name for each column header!");
						return false;
					}
						}

		// GET ALL THE INPUT Value Type comboboxes.
		var combo_value_types = document.getElementsByClassName('combo_value_type');
		var selected_value_types=[];
		// LOOP THROUGH EACH ELEMENT.
		for (i = 0; i < combo_value_types.length; i++) {
		    // CHECK THE ELEMENT TYPE.
		        for (j = 0; j < combo_value_types[i].attributes.length; j++) {
		        	selected_value_types[i]=combo_value_types[i].value;
		        }
		}
		
		selected_value_types_string=selected_value_types.join('|');
		document.getElementById("hidden_value_type_comboboxes").value = selected_value_types_string;	

		// GET ALL THE INPUT Value Unit comboboxes.
		var combo_value_units = document.getElementsByClassName('combo_value_unit');
		var selected_value_units=[];
		// LOOP THROUGH EACH ELEMENT.
		for (i = 0; i < combo_value_units.length; i++) {
		    // CHECK THE ELEMENT TYPE.
		        for (j = 0; j < combo_value_units[i].attributes.length; j++) {
		        	selected_value_units[i]=combo_value_units[i].value;
		        }
		}

		selected_value_units_string=selected_value_units.join('|');
		document.getElementById("hidden_value_unit_comboboxes").value = selected_value_units_string;

// 			///////Set and send hidden_all_headers_array ////////////////
// 			var all_headers_array_string=all_headers_array.join('|');
// 			document.getElementById("hidden_all_columns").value = all_headers_array_string;		
	  		return true;
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