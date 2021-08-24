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

            include 'poimanager_sqlClient.php';
            include 'poimanager_sqlQueryManager.php';
            include 'poimanager_APICient.php';
            include 'poimanager_APIQueryManager.php';

            $element_id = $_GET['elementId'];
            
//             echo $element_id;
//             die();
            
            $query = getPoiSelectDataTableByUsernameAndFilenameQuery($element_id);
            $result = json_decode(executePoiSelectDataTableByElementIdQuery($query), true);
            
            $headers=explode(",", $result['File']['headers']);
            $coord_type = $result['File']['coord_type'];
            $nature=$result['Model']['nature'];
            $sub_nature=$result['Model']['sub_nature'];
            $center_lat=$result['Model']['center_lat'];
            $center_lon=$result['Model']['center_lon'];
            $radius=$result['Model']['radius'];
            $lang=$result['Model']['language'];
            
            $header_row_css = 'style="color: black;font-family: Montserrat;font-weight: 400;font-size: 14px;width: 150px;background: mediumseagreen;"';
            $style="style=\"width: max-content;background: #1C70D2;font-weight: bold\"";
            $style_black="style=\"color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 120px;background: #1C70D2;\"";
            
            if($coord_type=="caseb"){
                echo '<tr><td style="background: white;border-top: none;border-left: none;" colspan="29"></td><td class=header style="background: darkcyan;width: 100px;font-weight: bold;height: 50px;" colspan="3">Search Area</td><td style="background: white; border-top: none; border-right: none;" colspan="2"></td></tr>';
                echo '<tr class=header><td '.$style.'>Sheet Name</td><td '.$style.'>Service URI</td><th style="color: black;">' . implode('</th><th style="color: black;">', $headers) . '</th><td class=\"header\" '.$header_row_css.'>Center Latitude</td><td class=\"header\" '.$header_row_css.'>Center Longitude</td><td class=\"header\" '.$header_row_css.'>Radius (km)</td><td class=\"header\" '.$style_black.'> Nature</td><td class=\"header\" '.$style_black.'> Sub Nature</td><td class=\"header\" '.$style_black.'>Language</td></tr>';
            }else{
                echo '<tr class=header><td '.$style.'>Sheet Name</td><td '.$style.'>Service URI</td><th style="color: black;">' . implode('</th><th style="color: black;">', $headers) . '</th><td class=\"header\" '.$style_black.'> Nature</td><td class=\"header\" '.$style_black.'> Sub Nature</td><td class=\"header\" '.$style_black.'>Language</td></tr>';
            }
            
            // Append rows//////////////////////////////////////////////////////////////
            $rows = $result['Instances'];

            for ($row_index = 0; $row_index < count($rows); $row_index ++) {
                
                $row_array_to_be_inseted = array();
                $row=$rows[$row_index];
                
                $row_array_to_be_inseted[0] = $row['Values']['sheet_name'];
                $row_array_to_be_inseted[1] = $row['Values']['suri'];
                $nameENG=$row['Values']['name'];
                
                $abbreviationENG=$row['Values']['abbreviation'];
                $descriptionShortENG=$row['Values']['descriptionShort'];
                $descriptionLongENG=$row['Values']['descriptionLong'];
                $phone=$row['Values']['phone'];
                $fax=$row['Values']['fax'];
                $url=$row['Values']['url'];
                $email=$row['Values']['email'];
                $refPerson=$row['Values']['refPerson'];
                $secondPhone=$row['Values']['secondPhone'] ;
                $secondFax=$row['Values']['secondFax'];
                $secondEmail=$row['Values']['secondEmail'];
                $secondCivicNumber=$row['Values']['secondCivicNumber'];
                $secondStreetAddress=$row['Values']['secondStreetAddress'] ;
                $notes=$row['Values']['notes'] ;
                $timetable=$row['Values']['timetable'] ;
                $photo=$row['Values']['photo']  ;
                $other1=$row['Values']['other1'];
                $other2=$row['Values']['other2'];
                $other3=$row['Values']['other3'] ;
                $postalcode=$row['Values']['postalcode'] ;
                $province=$row['Values']['province'] ;
                $city=$row['Values']['city'] ;
                $streetAddress=$row['Values']['streetAddress'] ;
                $civicNumber=$row['Values']['civicNumber'] ;
                $latitude=$row['Values']['latitude'] ;
                $longitude=$row['Values']['longitude'] ;
                
                $row_array_to_be_inseted[2] =$nameENG;
                $row_array_to_be_inseted[3] =$abbreviationENG;
                $row_array_to_be_inseted[4] =$descriptionShortENG;
                $row_array_to_be_inseted[5] =$descriptionLongENG;
                $row_array_to_be_inseted[6] =$phone;
                $row_array_to_be_inseted[7] =$fax;
                $row_array_to_be_inseted[8] =$url;
                $row_array_to_be_inseted[9] =$email;
                $row_array_to_be_inseted[10] =$refPerson;
                $row_array_to_be_inseted[11] =$secondPhone ;
                $row_array_to_be_inseted[12]=$secondFax;
                $row_array_to_be_inseted[13] =$secondEmail;
                $row_array_to_be_inseted[14] =$secondCivicNumber;
                $row_array_to_be_inseted[15] =$secondStreetAddress;
                $row_array_to_be_inseted[16] =$notes;
                $row_array_to_be_inseted[17] =$timetable;
                $row_array_to_be_inseted[18] =$photo;
                $row_array_to_be_inseted[19] =$other1;
                $row_array_to_be_inseted[20] =$other2;
                $row_array_to_be_inseted[21] =$other3 ;
                $row_array_to_be_inseted[22] =$postalcode ;
                $row_array_to_be_inseted[23] =$province ;
                $row_array_to_be_inseted[24] =$city ;
                $row_array_to_be_inseted[25] =$streetAddress;
                $row_array_to_be_inseted[26] =$civicNumber;
                $row_array_to_be_inseted[27] =$latitude;
                $row_array_to_be_inseted[28] =$longitude;
                
                        
                        if($coord_type=='caseb'){
                            $row_array_to_be_inseted[29]=$center_lat;
                            $row_array_to_be_inseted[30]=$center_lon;
                            $row_array_to_be_inseted[31]=$radius;
                            $row_array_to_be_inseted[32]=$nature;
                            $row_array_to_be_inseted[33]=$sub_nature;
                            $row_array_to_be_inseted[34] =$lang;
                        }else{
                            $row_array_to_be_inseted[29]=$nature;
                            $row_array_to_be_inseted[30]=$sub_nature;
                            $row_array_to_be_inseted[31] =$lang;
                        }
                        echo '<tr><td>' . implode('</td><td>', $row_array_to_be_inseted) . '</td></tr>';
                    }
            ?>
			</table>
		</div>

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