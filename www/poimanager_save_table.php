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
	$default_title = "Save Data Result";
}else{
	$default_title = "";
}
?>

<?php

include_once 'datatablemanager_APICient.php';
include_once 'poimanager_APICient.php';
include_once 'datatablemanager_APIQueryManager.php';
include_once 'poimanager_dbManager.php';
include 'config.php';
require 'sso/autoload.php';
use Jumbojett\OpenIDConnectClient;

$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);
$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));

try {
    $tkn = $oidc->refreshToken($_SESSION['refreshToken']);
} catch (Exception $e) {
    print_r($e);
}

$accessToken = $tkn->access_token;

$error=false;
$error_show="";

if(strlen($accessToken)==0){
    $error=true;
    $error_show="Access token is not valid or available! Please, re-login and try again!";
}else{
    
// $accessToken=$_POST['hidden_access_token'];
$configs = parse_ini_file('poimanager_config.ini.php');
$org = $_POST['hidden_org'];

//get username to delegate
$usernameQuery=getUsernameToDelegateQuery($org);
$usernames_to_delegate=executeGetUsernameToDelegate($usernameQuery);

$file_name = $_POST['hidden_file_name'];
$coord_type = $_POST['hidden_poi_file_type'];
$center_lat = ($coord_type=="caseb" ? $_POST['hidden_latitude'] : "");
$center_lon = ($coord_type=="caseb" ? $_POST['hidden_longitude'] : "");
$radius= ($coord_type=="caseb" ? $_POST['hidden_radius'] : "");
$nature = $_POST['hidden_nature'];
$sub_nature = $_POST['hidden_sub_nature'];
$lang= extractPoiStringBetweenTwoCharacters($_POST['hidden_lang'],'(',')'); 

if(strlen($file_name)==0){
    $file_name=$_POST['hidden_file_to_uplolad_fromvalue_type_page'];
}

$poi_lats= explode(",", $_POST['hidden_poi_lats']);
$poi_lons= explode(",", $_POST['hidden_poi_lons']);

$file_name_in_table_interface=$file_name;

$elementType = $configs['elementType'];
$elementUrl = $configs['elementUrl'];
$elementName = $configs['elementName'];

// Insert elemenetTypeId in the ownership table
$elementId = $file_name . "|" . date_timestamp_get(date_create());
$insertElementTypeIDParams = registerelementTypeIDQuery($elementId, $elementType, $elementName, $elementUrl);
$my_result = executeRegisterDataTableID($insertElementTypeIDParams, $accessToken);

if (substr($my_result, 0, 5) == "Error") {
        $error = true;
        if (strpos($my_result, 'token') !== false) {
            $error_show = $my_result . ". Please, re-login and try again!";
        } else if (strpos($my_result, 'limit') !== false) {
            $error_show = $my_result . ". Please, contact us!";
        }
    } else {

        $id = $my_result["id"];
        $delegator_username = $my_result["username"];
        if (! $error) {
            // delegate users
            if (strlen($usernames_to_delegate[0]) != 0) {
                foreach ($usernames_to_delegate as $username_to_delegate) {

                    $delegateUserQuery = getPoiDelegelateuserQuery($delegator_username);
                    $delegate_result = delegateUser($delegateUserQuery, $username_to_delegate, $elementId, $delegator_username);

                    if (!$delegate_result) {
                        $error = true;
                        $error_show = "User delegation failed: " . $delegate_result;
                        sendPoiErrorUserDelegationEmail($file_name, $org, $elementId, $delegator_username, $username_to_delegate, $delegate_result);
                        break;
                    }
                }
            }

            if (!$error) {
                // insert data table
                $final_table = explode('|', $_POST['final_table_value_name_Sheet_name_rest']);
                $row_count = $_POST['hidden_row_count'];

                insertPoiDataTable($final_table, $row_count, $file_name, $elementId, $coord_type, $center_lat, $center_lon, $radius, $nature, $sub_nature, $lang, $org,$poi_lats,$poi_lons);
                sendPoiSuccessUploadEmail($file_name, $org, $delegator_username, $id);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>POI Loader - Save Data</title>
		
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
	<link rel="stylesheet" href="css/datatablemanager_save_table.css">
        
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
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php include "mobMainMenu.php" 
						?></div>
                    </div>
                    <div class="row" >
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:45px'>
					<div id="container" style="width: 1600px; position: fixed; top: 3%; height: fit-content;">
							<div id="div_email">
								<img id="email_image" src="img/datatablemanager_email_your_question.png" /> 
								<label id="email_label">Do you have a question? Send us an email: </label>
								<a id="href_email" style="color: #337ab7;" href="mailto:snap4city@disit.org">snap4city@disit.org</a>
							</div>
							<form id="save_table" name="save_table" method="POST">
								<div id="div_navigation_btns" >
									<input id="prevBtn" name="prevBtn" class="submit" type="button" value="Upload another file" onclick="document.location.href='poimanager_upload_file.php?showFrame=false';">
								</div>

								<p id="instructor" class="instructor">POI data uploaded successfuly to the Snap4city database! It is now
									possible to be represented in the Snap4city dashboards.</p>
								<!--Result Table   -->
								<div id="result_table" class="divTable blueTable" style="padding: 10px;top: 150px;">
									<div class="divTableHeading">
										<div class="divTableRow">
											<div class="divTableHead" style="padding: 10px;">ID</div>
											<div class="divTableHead" style="padding: 10px;">File Name</div>
											<div class="divTableHead" style="padding: 10px;">Username</div>
										</div>
									</div>

									<div class="divTableBody">
										<div class="divTableRow">
											<div class="divTableCell" style="padding: 10px;"><?php echo  $id?></div>
											<div class="divTableCell" style="padding: 10px;"><?php echo  $file_name_in_table_interface?></div>
											<div class="divTableCell" style="padding: 10px;"><?php echo  $delegator_username ?></div>
										</div>
									</div>
								</div>
								<!--End Result Table   -->
							</form>
						</div>
					</div>
				</div>
		</div>
		</div>
    </div>

    <script type='text/javascript'>
    		//////////////Get upload limit
    		var access_token="<?php echo $accessToken?>";
// 			if(accessToken.length==0){
// 				document.getElementById("instructor").innerHTML = 'Access token is not valid or available! Please, re-login and try again!';
// 		        document.getElementById("instructor").style.backgroundColor ="#f44336";
// 				}else{
			if(access_token.length!=0){
			$.ajax({  
		    type:"GET",  
		    url:"datatablemanager_getDataTablesLimitByUsername.php",
		    data:"access_token="+access_token,    
		    success:function(data){ 

			var array=data.split("|"); 
			var role= array[0];
			var current=Number(array[1]);
			var limit=Number(array[2]);   

			if(current+1>=limit){
				document.getElementById("instructor").innerHTML = 'Considering your role ('+role+'), you have reached the maximum limit ('+limit+') of files that can be uploaded. Therefore, You cannot upload more files!';
		        document.getElementById("instructor").style.backgroundColor ="#f44336";
				document.getElementById("prevBtn").disabled = true;
		        document.getElementById("prevBtn").style.backgroundColor ="lightslategrey";
				}
		    },
		    error: function (xhr, error) {
		        console.debug(xhr); 
		        console.debug(error);
		      }
			});
	}
    //////////////Handle Errors
	 var result=<?php echo json_encode($error_show); ?>;
 	 var error="<?=$error; ?>";
 	 if(error=="1"){
         document.getElementById("instructor").innerHTML =result;
         document.getElementById("instructor").style.backgroundColor ="#f44336";
         $("#result_table").hide();
         document.getElementById("prevBtn").value = "Upload Again";
	     document.getElementById("prevBtn").style.backgroundColor ="lightslategrey";
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
    
</body>
</html>


						