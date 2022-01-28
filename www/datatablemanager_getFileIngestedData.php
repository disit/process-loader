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
<body class="guiPageBody" style="background-color: white" onresize="resize()">
	<?php include('functionalities.php'); ?>
  		<div id="div_table"
			style="height:106%; overflow: scroll; width: 100%; top: -45px; position: relative; left: -16px;">
			<table id="table_preview" style=" left: 20px; top: 50px;">
              <?php

            include 'datatablemanager_sqlClient.php';
            include 'datatablemanager_sqlQueryManager.php';
            include 'datatablemanager_APICient.php';
            include 'datatablemanager_APIQueryManager.php';

            $element_id = $_GET['elementId'];
            $dateObserved_type_query = getDateObservedTypeQuery($element_id);
            $dateObserved_type = executeGetDateObservedType($dateObserved_type_query);

            $device_list_query = getDeviceListQuery($element_id);
            $device_list = executeGetDeviceListQuery($device_list_query);

            $query = getSelectDataTableByUsernameAndFilenameQuery($element_id);
            $result = json_decode(executeSelectDataTableByElementIdQuery($query, $device_list, $dateObserved_type), true);
            
            $sheet_name_type = $result['File']['sheet_name_type'];
            
            $coordinate_type = $result['File']['coord_type'];

            $context_broker=$result['Model']['context_broker'];
            $nature=$result['Model']['nature'];
            $sub_nature=$result['Model']['sub_nature'];
            
            $value_name_types_concat = $result['Model']['value_name_type'];
            
            if($coordinate_type=='file'){
                $coord_lat_file=$result['Model']['lat'];
                $coord_lon_file=$result['Model']['lon'];
            }
            
            $feat_names = array();
            $feat_value_types = array();
            $feat_value_units = array();
            $feat_data_types = array();
            $features = $result['Model']['Values'];

            for ($feature_index = 0; $feature_index < count($features); $feature_index ++) {
                $feat_names[$feature_index] = $features[$feature_index]['name'];
                $feat_value_types[$feature_index] = $features[$feature_index]['value_type'];
                $feat_value_units[$feature_index] = $features[$feature_index]['value_unit'];
                $feat_data_types[$feature_index] = $features[$feature_index]['data_type'];
            }

            // Make an array to put the composed value name type, the sheet name type, and the all headers
            $value_name_types_concat_sheet_name_type_all_headers_array = array();
            $value_name_types_concat_sheet_name_type_all_headers_array[0] = $value_name_types_concat;
            $value_name_types_concat_sheet_name_type_all_headers_array[1] = $sheet_name_type;

            $rest_header_array = array();
            for ($header_index = 0; $header_index < count($feat_names); $header_index ++) {
                array_push($rest_header_array, $feat_names[$header_index]);
                $value_name_types_concat_sheet_name_type_all_headers_array[$header_index + 2] = $feat_names[$header_index];
            }

            $value_name_types_concat_sheet_name_type_all_headers_array_for_table = array_slice($value_name_types_concat_sheet_name_type_all_headers_array, 2);

            echo "<tr class=header ><td id=\"cell_first_second_row\" colspan=\"2\" style=\"background-color: white;\"></td><td style=\"background: darkcyan;font-family: Montserrat;font-weight: 400;font-size: 14px;\"  colspan=";
            echo count($feat_names);
            echo "><b>Value Type</b></td></tr>";
            echo "<tr><td colspan=\"2\" style=\"background-color: white;border-top-style: hidden;border-left-style: hidden;border-bottom-style: hidden;\"></td><td style=\"background: cadetblue;font-family: Montserrat;font-weight: 400;font-size: 14px;\">" . implode("</td><td style=\"background: cadetblue;font-family: Montserrat; font-weight: 400;font-size: 14px;\">", $feat_value_types) . "</td></tr>";

            echo "<tr class=header><td id=\"cell_first_second_row\" colspan=\"2\" style=\"background-color: white;\"></td><td style=\"background: darkcyan;font-family: Montserrat;font-weight: 400;font-size: 14px;\"  colspan=";
            echo count($feat_names);
            echo "><b>Value Unit</b></td></tr>";
            echo "<tr><td colspan=\"2\" style=\"background-color: white;border-top-style: hidden;border-left-style: hidden;border-bottom-style: hidden;\"></td><td style=\"background: cadetblue;font-family: Montserrat;font-weight: 400;font-size: 14px;\">" . implode("</td><td style=\"background: cadetblue;font-family: Montserrat; font-weight: 400;font-size: 14px;\">", $feat_value_units) . "</td></tr>";
            echo "<tr class=header><td id=\"cell_first_second_row\" colspan=\"2\" style=\"background-color: white;\"></td><td style=\"background: darkcyan;font-family: Montserrat;font-weight: 400;font-size: 14px;\"  colspan=";
            echo count($feat_names);
            echo "><b>Data Type</b></td></tr>";
            echo "<tr><td colspan=\"2\" style=\"background-color: white;border-top-style: hidden;border-left-style: hidden;\"></td><td style=\"background: cadetblue;font-family: Montserrat;font-weight: 400;font-size: 14px;\">" . implode("</td><td style=\"background: cadetblue;font-family: Montserrat;font-weight: 400;font-size: 14px;\">", $feat_data_types) . "</td></tr>";
            
            //////////////////////////////////////////////////
            $header_row="";
            $header_row.= '<tr><td style="background: #1C70D2;font-weight: bold">Device Name</td><td style="background: #1C70D2;font-weight: bold">Sheet Name</td><th style="color: black;">' . implode('</th><th style="color: black;">', $value_name_types_concat_sheet_name_type_all_headers_array_for_table) . '</th>';
            
            if($coordinate_type=="file"){
            $header_row.='<td class=\"header\" style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;">' .'Latitude'. '</td><td class=\"header\" style="color: black;font-family: Montserrat;font-weight:400; font-size: 14px;background: #1C70D2;">'.'Longitude'.'</td>';
            }
            
            $header_row.='<td class=\"header\" style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;">'.'Nature'.'</td>';
            
            $header_row.='<td class=\"header\" style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;">'.'Sub-Nature'.'</td>';
            $header_row.='<td class=\"header\" style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;">'.'Context Broker'.'</td></tr>';
            
            if($coordinate_type=="address"){
                $header_row = substr($header_row, 0, count($header_row) - 6);
                $header_row.='<td class=\"header\" style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width:160px">'.'Device/Instance will be created?'.'</td></tr>';
//                 $header_row.='<td class=\"header\" style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width:160px">'.'Device/Instance will be created?'.'</td>';
//                 $header_row.='<td class=\"header\" style="background: #1C70D2;color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width:420px">'.'Comment'.'</td></tr>';
            }
            
            echo $header_row;
            // Append rows//////////////////////////////////////////////////////////////

            $device_names = array();
            $sheet_names = array();
            $devices = $result['Devices'];

            for ($device_index = 0; $device_index < count($devices); $device_index ++) {
                
                $device_names[$device_index] = $devices[$device_index]['name'];
                $sheet_names[$device_index] = $devices[$device_index]['sheet_name'];

                $device_name = $device_names[$device_index];
                $sheet_name = $sheet_names[$device_index];
                
//                 $device_row_lat = $devices[$device_index]['lat'];
//                 $device_row_lon = $devices[$device_index]['lon'];

                $row_array_to_be_inseted = array();

                $row_array_to_be_inseted[1] = $device_name;
                $row_array_to_be_inseted[0] = $sheet_name;

                $rows = $result['Instances'];
//                 $address_lon="";
//                 $address_lat="";
                
                for ($row_index = 0; $row_index < count($rows[$device_index]['Values']); $row_index ++) {
                        $row = explode("|", $rows[$device_index]['Values'][$row_index]);
                        
                        $red_cancel=false;
                        
                        if($coordinate_type=="address"){
                            
                            if(strlen($row[count($row)-2])==0){
                                $row[count($row)-2]="<img style='width:25px;background: orangered;' src='img/datatabemanager_na.png'/>";
                                $red_cancel=true;
                            }
                            
                            if(strlen($row[count($row)-1])==0){
                                $row[count($row)-1]="<img style='width:25px;background: orangered;' src='img/datatabemanager_na.png'/>";
                                $red_cancel=true;
                            }
                        }
                        
                        for ($cell_index = 0; $cell_index < count($row); $cell_index ++) {
                            $row_array_to_be_inseted[$cell_index + 2] = $row[$cell_index];
                        }
                        
                    if ($coordinate_type == 'file') {
                        $row_array_to_be_inseted[count($features) + 2] = $coord_lat_file;
                        $row_array_to_be_inseted[count($features) + 3] = $coord_lon_file;
                                  
                        $row_array_to_be_inseted[count($features) + 4] = $nature;
                        $row_array_to_be_inseted[count($features) + 5] = $sub_nature;
                        $row_array_to_be_inseted[count($features) + 6] = $context_broker;
                    } else{
                        
                        $row_array_to_be_inseted[count($features) + 2] = $nature;
                        $row_array_to_be_inseted[count($features) + 3] = $sub_nature;
                        $row_array_to_be_inseted[count($features) + 4] = $context_broker;

                        if ($coordinate_type == "address" && $red_cancel) {
                            $row_array_to_be_inseted[count($features) + 5] = "<img style='width:25px' src='img/datatabemanager_red_cancel.png'/>";
                        } else if($coordinate_type == "address" && !$red_cancel) {
                            $row_array_to_be_inseted[count($features) + 5] = "<img style='width:25px' src='img/datatablemanager_address_coord_ok.png'/>";
//                          $row_array_to_be_inseted[count($features) + 6] = "Address field is empty <b>OR</b> no coordinate found by the provided address";
                        }
                    }
                        echo '<tr><td>' . implode('</td><td>', $row_array_to_be_inseted) . '</td></tr>';
                    }
                }
            ?>
			</table>

		</div>
<!-- 	</div> -->

	<script type='text/javascript'>

	function resize() {
		  var w = window.innerWidth;
		  var h = window.innerHeight;

		//alert('window.outerWidth :'+h);
		//alert('document.getElementById("div_table").style.height :'+document.getElementById("div_table").style.height);
		
		  //document.getElementById("container").style.height = h+"px";
		  document.getElementById("div_table").style.width = w+"px";
		  //alert("height:"+h+"px");
		  document.getElementById("div_table").style.height = h+"px";
		  //alert(document.getElementById('div_table').style.height);
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
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}

		
	});
</script>
</body>
</html>