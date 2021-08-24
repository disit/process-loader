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

        <title>POI Loader - Preview</title>
		
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
<link rel="stylesheet" href="css/poimanager_preview.css">
        
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

							<div id="div_email" >
								<img id="email_image" src="img/datatablemanager_email_your_question.png"> <label
									id="email_label">Do you have a question? Send us an email: </label>
								<a id="href_email" href="mailto:snap4city@disit.org">snap4city@disit.org</a>
							</div>
							<form id="preview" method="POST" action="poimanager_save_table.php?showFrame=false"
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
            require_once ('datatablemanager_SimpleXLSX.php');
            require_once ('poimanager_myUtil.php');
            $configs = parse_ini_file('poimanager_config.ini.php');
            $template_columns=explode(',', $configs['poi_template_column']);
            $target_dir = $configs['target_dir'];
            $poi_file_type = $_POST['hidden_poi_file_type'];
            $lang = $_POST['hidden_lang'];
            session_start();
            $_SESSION['accessToken'] = $_POST['hidden_access_token'];
            $file_name = $_POST['hidden_file_name'];
            if (strlen($file_name) == 0) {
                $file_name = $_POST['hidden_file_to_uplolad_fromvalue_type_page'];
            }
            
            $file_name_for_suri=str_replace('.','_',$file_name);
            $file_name_for_suri=str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9_ ]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', trim($file_name_for_suri))));
            
            //get org
            $usernameD = $utente_att;
            $ldapUsername = "cn=" . $usernameD . "," . $ldapParamters;
            $ds = ldap_connect($ldapServer, '389');
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            $bind = ldap_bind($ds);
            $ldapBaseDN=$ldapParamters;
            $org = checkPoiLdapOrganization($ds, $ldapUsername, $ldapBaseDN);
            
            if ($xlsx = SimpleXLSX::parse($target_dir . $file_name)) {
                $sheet_names = $xlsx->sheetNames();
                $sheet_rows_data = $xlsx->rows(0);
//                 $all_headers_array = $sheetZeroData[0];
                $header_row_css = 'style="color: black;font-family: Montserrat;font-weight: 400;font-size: 14px;width: 150px;background: mediumseagreen;"';
                
                if($poi_file_type=="caseb"){
                    echo '<tr><td style="background: white;border-top: none;border-left: none;" colspan="29"></td><td class=header style="background: darkcyan;width: 100px;font-weight: bold;height: 50px;" colspan="3">Search Area</td><td style="background: white; border-top: none; border-right: none;" colspan="2"></td></tr>';
                    echo '<tr class=header><td style="width: max-content;background: #1C70D2;font-weight: bold">Sheet Name</td><td style="width: max-content;background: #1C70D2;font-weight: bold">Service URI</td><th style="color: black;">' . implode('</th><th style="color: black;">', $template_columns) . '</th><td class=\"header\" '.$header_row_css.'>Center Latitude</td><td class=\"header\" '.$header_row_css.'>Center Longitude</td><td class=\"header\" '.$header_row_css.'>Radius (km)</td><td class=\"header\" style="color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 120px;background: #1C70D2;"> Nature</td><td class=\"header\" style="color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 120px;background: #1C70D2;"> Sub Nature</td><td class=\"header\" style="color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 120px;background: #1C70D2;">Language</td></tr>';
                }else{
                    echo '<tr class=header><td style="width: max-content;background: #1C70D2;font-weight: bold">Sheet Name</td><td style="width: max-content;background: #1C70D2;font-weight: bold">Service URI</td><th style="color: black;">' . implode('</th><th style="color: black;">', $template_columns) . '</th><td class=\"header\" style="color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 120px;background: #1C70D2;"> Nature</td><td class=\"header\" style="color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 120px;background: #1C70D2;"> Sub Nature</td><td class=\"header\" style="color: black;font-family: Montserrat;font-weight:400; font-size: 14px;width: 120px;background: #1C70D2;">Language</td></tr>';
                }
                
                // Append rows//////////////////////////////////////////////////////////////
                $row_count = 0;
                for ($sheetIndex = 0; $sheetIndex < count($sheet_names); $sheetIndex ++) {
                    $sheet_rows_data = $xlsx->rows($sheetIndex);
                    // $rowIndex = 1 ---> ignore header rows
                    for ($rowIndex = 1; $rowIndex < count($sheet_rows_data); $rowIndex ++) {
                        $sheetRowData = $sheet_rows_data[$rowIndex];
                        $suri=getSuri($sheetRowData[0],$org,$file_name_for_suri);
                        
                        $sheet_name_in_file = $sheet_names[$sheetIndex];
                        $row_array_to_be_inseted = array();
                        
                        // insert sheet name value as second item in row
                        $row_array_to_be_inseted[0] = trim($sheet_name_in_file);
                        // in each row, assign the value of value_name cell
                        $row_array_to_be_inseted[1] = $suri;
                        // Insert the the rest of values in the row array
                        for ($cell_index = 0; $cell_index < count($template_columns); $cell_index ++) {
                            $cleaned_cell_value=cleanCellValue($sheetRowData[$cell_index]);
                            $row_array_to_be_inseted[$cell_index + 2] = $cleaned_cell_value;
                        }

                            // update table values to be sent to the next form
                        $final_table_value_name_Sheet_name_rest .= implode("|", $row_array_to_be_inseted) . "|";

                        if ($poi_file_type == 'caseb') {
                            $row_array_to_be_inseted[count($template_columns) + 3] = $_POST['hidden_latitude'];
                            $row_array_to_be_inseted[count($template_columns) + 4] = $_POST['hidden_longitude'];
                            $row_array_to_be_inseted[count($template_columns) + 5] = $_POST['hidden_radius'];
                            $row_array_to_be_inseted[count($template_columns) + 6] = $_POST['hidden_nature'];
                            $row_array_to_be_inseted[count($template_columns) + 7] = $_POST['hidden_sub_nature'];
                            $row_array_to_be_inseted[count($template_columns) + 8] = $_POST['hidden_lang'];
                            
                        }else{
                            $row_array_to_be_inseted[count($template_columns) + 3] = $_POST['hidden_nature'];
                            $row_array_to_be_inseted[count($template_columns) + 4] = $_POST['hidden_sub_nature'];
                            $row_array_to_be_inseted[count($template_columns) + 5] = $_POST['hidden_lang'];
                            
                        }

                        // insert rest of values that are not part of value name in the row array
//                         if ($dateObserved_type == 'file') {
//                             echo '<tr><td>' . implode('</td><td>', $row_array_to_be_inseted_dof) . '</tr>';
//                         } else {
                            echo '<tr><td>' . implode('</td><td>', $row_array_to_be_inseted) . '</tr>';
//                         }
                        ++ $row_count;
                    }
                }
            } else {
                echo SimpleXLSX::parseError();
            }
            ?>
			</table>
									<!-- End div div_table  -->
								</div>
								<!-- End div tab  -->

								<!-- Hidden Inputs  -->
								<input type="hidden" name="hidden_org" id="hidden_org" value=""> 
								<input type="hidden" name="final_table_value_name_Sheet_name_rest" id="final_table_value_name_Sheet_name_rest" value=""> 
								<input type="hidden" name="hidden_file_name" id="hidden_file_name" value="">
								<input type="hidden" id="hidden_row_count" name="hidden_row_count" value="">	

        						<input type="hidden" name="hidden_poi_file_type" id="hidden_poi_file_type"  value="<?php echo $_POST['hidden_poi_file_type']; ?>">
        					    <input type="hidden" name="hidden_file_to_uplolad_fromvalue_type_page" 	id="hidden_file_to_uplolad_fromvalue_type_page" value="<?php echo $_POST['hidden_file_to_uplolad_fromvalue_type_page']; ?>"> 
        						<input type="hidden" name="hidden_access_token" id="hidden_access_token" value="<?php echo $_POST['hidden_access_token']; ?>"> 
        							
        						<input id="hidden_lang" type="hidden" name="hidden_lang" value="<?php echo $_POST['hidden_lang']; ?>"> 
    							<input id="hidden_nature" type="hidden" name="hidden_nature" value="<?php echo $_POST['hidden_nature']; ?>"> 
    							<input 	id="hidden_sub_nature" type="hidden" name="hidden_sub_nature" value="<?php echo $_POST['hidden_sub_nature']; ?>">
    							<input 	id="hidden_latitude" type="hidden" name="hidden_latitude" value="<?php echo $_POST['hidden_latitude']; ?>">
    							<input 	id="hidden_longitude" type="hidden" name="hidden_longitude" value="<?php echo $_POST['hidden_longitude']; ?>">
    							<input  id="hidden_radius" type="hidden" name="hidden_radius" value="<?php echo $_POST['hidden_radius']; ?>">

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
	<script type="text/javascript">

function validateForm() {

    	if(clicked=='Save'){

    	$('#hidden_org').val("<?php echo $org; ?>"); 
		$('#hidden_row_count').val("<?php echo $row_count; ?>"); 
		$('#file_name').val("<?php echo $file_name; ?>"); 
        $('#final_table_value_name_Sheet_name_rest').val("<?php echo substr($final_table_value_name_Sheet_name_rest, 0,strlen($final_table_value_name_Sheet_name_rest)-1); ?>"); 


        
		if( confirm('Do you really want to save data to the database?')){
			document.getElementById("div_please_wait").style.display='block';
			return true;
			}else{
				return false;
				}
    	}else{
    		if( confirm('You will probably lose the inserted data. Are you sure?')){
    			var frm = document.getElementById('preview') || null;
 			   	frm.action = 'poimanager_general_information.php?showFrame=false'; 
   			
    			return true;
        	}else{
				return false;
			}
}
}
</script>
</body>
</html>