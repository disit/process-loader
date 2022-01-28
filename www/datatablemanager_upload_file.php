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
//Get the list of files that are already uploaded by the same access token
include_once 'datatablemanager_myUtil.php';
$configs = parse_ini_file('datatablemanager_config.ini.php');
$target_dir = $configs['target_dir'];

include_once 'datatablemanager_sqlClient.php';
include_once 'datatablemanager_sqlQueryManager.php';

//Get refresh access token
require 'sso/autoload.php';
use Jumbojett\OpenIDConnectClient;

$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);
$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));

// $oidc->providerConfigParam(array('authorization_endpoint' => 'https://www.disit.org/auth/realms/master/protocol/openid-connect/auth'));
// $oidc->providerConfigParam(array('userinfo_endpoint' => 'https://www.disit.org/auth/realms/master/protocol/openid-connect/userinfo'));
// $oidc->providerConfigParam(array('jwks_uri' => 'https://www.disit.org/auth/realms/master/protocol/openid-connect/certs'));
// $oidc->providerConfigParam(array('issuer' => 'https://www.disit.org/auth/realms/master'));
// $oidc->providerConfigParam(array('end_session_endpoint' => 'https://www.disit.org/auth/realms/master/protocol/openid-connect/logout'));

// $oidc->addScope(array('openid', 'username', 'profile'));
// $oidc->setRedirectURL($appUrl . '/datatablemanager_upload_file.php');


// $oidc->authenticate();

try {
    $tkn = $oidc->refreshToken($_SESSION['refreshToken']);
} catch (Exception $e) {
    print_r($e);
}

$access_token= $tkn->access_token;

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
$organization = checkLdapOrganization($ds, $ldapUsername, $ldapBaseDN);

if (isset ($_SESSION['username'])){
  $role_att = $_SESSION['role'];	
}else{
 $role_att= "";	
}


// if (isset($_REQUEST['showFrame'])){
// 	if ($_REQUEST['showFrame'] == 'false'){
		//echo ('true');
		$hide_menu= "hide";
// 	}else{
// 		$hide_menu= "";
// 	}	
// }else{$hide_menu= "";}


if (!isset($_GET['pageTitle'])){
	$default_title = "Upload File";
}else{
	$default_title = "";
}
?>

<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Data Table Manager - Upload File</title>
		
		<!-- jQuery -->
        <script src="jquery/jquery-1.10.1.min.js"></script>

		<!-- Bootstrap Core JavaScript -->
        <script src="bootstrap/bootstrap.min.js"></script>
			
        <!-- Bootstrap Core CSS -->
        <link href="bootstrap/bootstrap.css" rel="stylesheet">

        <!-- Bootstrap toggle button -->
        <link href="bootstrapToggleButton/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="bootstrapToggleButton/js/bootstrap-toggle.min.js"></script>
       
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
		<link rel="stylesheet" href="css/datatablemanager_upload_file.css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.0.0.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-3.3.2.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
		
    </head>
<body class="guiPageBody">
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
				<div class="row" id="title_row" style="display: none">
					<div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt"><?php echo urldecode($_GET['pageTitle']); ?></div>
					<div class="col-xs-2 hidden-md hidden-lg centerWithFlex"
						id="headerMenuCnt"><?php  include "mobMainMenu.php"?></div>
				</div>
				<div class="row">
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top: 45px;position: fixed;'>
						<div id="container">
							<div id="div_email">
								<img id="email_image" src="img/datatablemanager_email_your_question.png" /> 
								<label id="email_label">Do you have a question? Send us an email: </label>
								<a id="href_email" href="mailto:snap4city@disit.org">snap4city@disit.org</a>
							</div>
							<form enctype="multipart/form-data" method="POST"  name="upload_file" id="upload_file" action="datatablemanager_upload_file.php?showFrame=false" >
								<div id="div_navigation_btns">
									<input id="submit_button" style="font-family: 'Montserrat';" class="submit" type="submit" value="Next" formaction="datatablemanager_general_information.php?showFrame=false" disabled>
								</div>

                                <!-- 				///////REMOVE FILE DIALOG////////// -->
								<div class="modal fade in" id="deleteFileModal" tabindex="-1"
									role="dialog" aria-labelledby="deleteDeviceModalLabel"
									aria-hidden="true" >
									<div class="modal-dialog"  id="div_delete_element_id_dialog">
										<div class="modal-content" style="width: 830px;">
											<div class="modal-header">
												<h5 class="modal-title" id="deleteDeviceModalLabel">File Deletion</h5>
											</div>
											<div class="modal-body">
												<div class="modalBodyInnerDiv">
													<span id="span_delete_file">	</span>
												</div>
											</div>
											<div id="div_deleteFileWaitingDescription" class="modalBodyInnerDiv" style="display: none;">
												<h5 id="h5_deleteFileWaitingDescription">File deleted successfully!</h5>
											</div>
											<div class="modal-footer" style="height: 50px;background: orange;">
												<img alt="info" src="img/datatablemanager_info.png" style="width: 20px; left: 20px; position: absolute;">
												<span id="span_delete_file_warning">The associated model, devices, and instances must be deleted, using Snap4City platform</span>
											</div>
											<div class="modal-footer">
												<button type="button" id="CancelBtn" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
												<button type="button" id="ConfirmBtn" class="btn btn-primary">Confirm</button>
											</div>
										</div>
									</div>
								</div>
								<!-- 						/////////////////////// -->
								<p id="instructor" class="instructor" ></p>
								<div class="guideline">
									<fieldset class="guideline" id="fieldset_guidelines">
										<legend class="guideline">General guildelines</legend>
										<ul class="guideline">
											<li class="li_guideline">Use "Previous" and "Next/Save" (not browser navigation) buttons to move to previous and next pages</li>
											<li class="li_guideline">In multi-sheet files, coulmn headers
												in all sheets must be the same. If they are different and
												describe a different kind of data, the file can be split
												into more than one</li>
											<li class="li_guideline">In multi-sheet files, the number of
												columns in all sheets must be the same</li>
											<li class="li_guideline">Avoid using special characters in
												file name (For example,|,/,#,@,%,,[,])</li>
											<li class="li_guideline">Avoid using non-UTF-8 (e.g., non-English) letters in the file name and column headers (For example,Π,Ž,ć)</li>	
											<li class="li_guideline">Avoid using special characters in
												sheet name(s) and column headers (For example, blank
												space,|,/,#,@,%,',[,])</li>
											<li class="li_guideline">Avoid using too long names for file
												name, sheet name(s), and column headers (maximum 50
												characters)</li>
											<li class="li_guideline">Avoid using line breaks in column
												headers</li>
											<li class="li_guideline">There must not be empty columns
												(those without headers that contain values, including blank
												spaces) in the file</li>
											<li class="li_guideline">Columns with number values must be
												stored with number (not string) format</li>
											<li class="li_guideline">Avoid using rounded values if they
												are supposed to be integer ones. Otherwise, they are
												considered as float</li>
										</ul>
									</fieldset>
								</div>

								<div id="div_upload_file">
									<input type="file"
										accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
										name="input_fileToUpload" id="input_fileToUpload"
										style="color: transparent;" /> <label id="fileLabel"
										for="fileLabel">No file chosen</label>
								</div>

								<span class="label warning" id="warning_span">The file is not
									well-formated! Please, revise it according to the following
									guidelines and re-upload it</span>
								<div id="div_warninglist">
									<ul id="warninglist"></ul>
								</div>

								<div id="div_uploaded_files_table"
									style="height: fit-content; left: 715px; position: absolute; top: 330px; width: 1476px; padding-bottom: 30px;">
									<table id="table_uploaded_files"
										style="max-width: 106%; overflow-x: hidden; width: 1510px; height: 300px; position: relative; top: 0px; left: -700px; overflow-y: scroll; display: block; background: padding-box;"
										class="blueTable">
									</table>
								</div>

								<!-- 		Hidden Inputs-->
								<input type="hidden" id="hidden_file_name" name="hidden_file_name" value="">
								<input type="hidden" id="hidden_access_token" name="hidden_access_token" value="<?php echo $access_token; ?>">
                                <!-- From next pages -->
                    			<input type="hidden" name="hidden_observed_date_type" id="hidden_observed_date_type" value="<?php echo $_POST['hidden_observed_date_type']; ?>"> 
                    			<input type="hidden" name="hidden_file_to_uplolad_fromvalue_type_page" id="hidden_file_to_uplolad_fromvalue_type_page" value="<?php echo $_POST['hidden_file_to_uplolad_fromvalue_type_page']; ?>"> 
                    			<input id="hidden_sheet_name" type="hidden" name="hidden_sheet_name" value="<?php echo $_POST['hidden_sheet_name']; ?>">
								<input id="hidden_context_broker" type="hidden" name="hidden_context_broker" value="<?php echo $_POST['hidden_context_broker']; ?>">
								
								<input type="hidden" name="hidden_coordinate_type" id="hidden_coordinate_type" value="<?php echo $_POST['hidden_coordinate_type']; ?>"> 
                                
                                <input id="hidden_lat_file" type="hidden" name="hidden_lat_file" value="<?php echo $_POST['hidden_lat_file']; ?>">
                    			<input id="hidden_lon_file" type="hidden" name="hidden_lon_file" value="<?php echo $_POST['hidden_lon_file']; ?>">
                    			
                                <input id="hidden_lat_sheet" type="hidden" name="hidden_lat_sheet" value="<?php echo $_POST['hidden_lat_sheet']; ?>">
                        		<input id="hidden_lon_sheet" type="hidden" name="hidden_lat_sheet" value="<?php echo $_POST['hidden_lat_sheet']; ?>">
                        		
                                <input id="hidden_lat_row" type="hidden" name="hidden_lat_row" value="<?php echo $_POST['hidden_lat_row']; ?>">
                        		<input id="hidden_lon_row" type="hidden" name="hidden_lon_row" value="<?php echo $_POST['hidden_lon_row']; ?>">
                        		
                        		<input id="hidden_observed_date_none" type="hidden" name="hidden_observed_date_none" value="<?php echo $_POST['hidden_observed_date_none']; ?>">
                        		
								<input type="hidden" name="hidden_nature" id="hidden_nature"value="<?php echo $_POST['hidden_nature']; ?>"> 
                    			<input id="hidden_sub_nature" type="hidden" name="hidden_sub_nature" value="<?php echo $_POST['hidden_sub_nature']; ?>">
                                <input id="hidden_observed_date_for_sheets_date_time_pickers" type="hidden" value="<?php echo $_POST['hidden_observed_date_for_sheets_date_time_pickers']; ?>"> 
                                <input id="hidden_observed_date_for_file_name" type="hidden" name="hidden_observed_date_for_file_name" value="<?php echo $_POST['hidden_observed_date_for_file_name']; ?>"> 
                        		<input type="hidden" name="hidden_observed_date_for_file_name_for_show" id="hidden_observed_date_for_file_name_for_show" value="<?php echo $_POST['hidden_observed_date_for_file_name_tmp']; ?>"> 
                        		<input id="hidden_observed_date_for_row_header" type="hidden" name="hidden_observed_date_for_row_header" value="<?php echo $_POST['hidden_observed_date_for_row_header']; ?>"> 
                                <!-- 			From next pages -->
                    			<input id="hidden_selected_value_names" type="hidden" name="hidden_selected_value_names" value="<?php echo $_REQUEST['hidden_selected_value_names']; ?>"> 
                    			<input  type="hidden" name="hidden_value_unit_comboboxes" id="hidden_value_unit_comboboxes"	value="<?php echo $_REQUEST['hidden_value_unit_comboboxes']; ?>"> 
                    			<input  type="hidden" name="hidden_value_type_comboboxes" id="hidden_value_type_comboboxes"	value="<?php echo $_REQUEST['hidden_value_type_comboboxes']; ?>"> 
                    			<input  type="hidden" name="hidden_all_columns" id="hidden_all_columns"  value="<?php echo $_POST['hidden_all_columns']; ?>">
							</form>

							<button name="Close" id="Close"  value="Close"	onClick="closeWin()">
								<span id="span_close_iframe" >X</span>
							</button>
							<iframe name="show_digested_data" id="show_digested_data" src="" ></iframe>
						</div>
						<!-- End div container -->
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">

	var file_name="<?php echo $_POST['hidden_file_to_uplolad_fromvalue_type_page']?>";

	if(file_name.length==0){
		file_name="<?php echo $_POST['hidden_file_name']?>";
		}

	if(file_name.length!=0){
		document.getElementById("submit_button").disabled = false;
		}

	var  file_label=document.getElementById("fileLabel");
	document.getElementById("fileLabel").textContent =file_name;
	
	function closeWin()   // Tested Code
	{
	var someIframe = window.parent.document.getElementById('show_digested_data');
	someIframe.style.display='none';

	var close=document.getElementById("Close");
	close.style.display='none';
	}
		//Get the list of files uploaded already by the same access token (user)
		var access_token="<?php echo $access_token;?>";
		if(access_token==""){
			document.getElementById("instructor").innerHTML = 'Access token is not valid or available! Please, re-login and try again!';
	        document.getElementById("instructor").style.backgroundColor ="#f44336";
			document.getElementById("submit_button").disabled = true;
	        document.getElementById("submit_button").style.backgroundColor ="lightslategrey";
			document.getElementById("input_fileToUpload").disabled = true;
			}else{
		$.ajax({  
	    type:"GET",  
	    url:"datatablemanager_getDataTableList.php",
	    data:"access_token="+access_token,  
	    dataType: 'json',  
	    success:function(data){  

	    	var file="";
	    	var file_without_ext="";
			
			var jsonString=JSON.stringify(data);
	    	var jsonObject = JSON.parse(jsonString);
	    	var propertyNames = Object.keys(jsonObject);
	    	var propertyNames = Object.keys(jsonObject);
			var uploaded_files_count=propertyNames.length;

	    	
	    	if(uploaded_files_count!=0){
			
			var role="<?=$role_att; ?>";
// 			var role="RootAdmin";
			var s="";
			if(role=='RootAdmin'){
			s='<tr><td style="background:white"></td><th style="background:#1C6EA4;font-size: 14px;font-weight: bold;color: black;font-family: Montserrat;border: black 1px solid;" colspan="4">Uploaded Files ('+uploaded_files_count+')</th><td  style="background:white;border-left: none; border-right: none;" colspan="1"></td></tr>';
			s+='<tr style="background: #1C70D2; color: black;">';
			s+='<td style="background:white"></td>';
			s+='<td class="td_excel_file" style="font-weight: bold;text-align-last: center;width: 200px;font-family: Montserrat;font-size: 14px;">Organization';
			s+='<td class="td_excel_file" style="font-weight: bold;text-align-last: center;font-family: Montserrat;font-size: 14px;">File Name';
			s+='</td><td class="td_excel_file" style="width: 440px;">Status';
			s+='</td><td class="td_excel_file" style="width: 440px;">Upload Date & Time';
			s+='</td><td style="text-align-last: left;width: 110px;background: white;"></td>';
			s+="</tr>";
			}else{
				s='<tr><th style="background:#1C6EA4;font-size: 14px;font-weight: bold;color: black;font-family: Montserrat;border: black 1px solid;" colspan="3">Uploaded Files ('+uploaded_files_count+')</th><td  style="background:white;border-left: none; border-right: none;" colspan="1"></td></tr>';
				s+='<tr style="background: #1C70D2; color: black;">';
				s+='<td class="td_excel_file" style="font-weight: bold;text-align-last: center;width: 970px;font-family: Montserrat;font-size: 14px;">File Name';
				s+='</td><td class="td_excel_file" style="width: 440px;">Status';
				s+='</td><td class="td_excel_file" style="width: 440px;">Upload Date & Time';
				s+='</td><td style="text-align-last: left;width: 110px;background: white;"></td>';
				s+="</tr>";
				}
			
	    	for (var i=0;i<jsonObject.length;++i){
		    	var file=data[i].file; 
		    	var file_without_ext=file.substring(0,file.indexOf('.'));
		    	var upload_date_time=data[i].upload_date_time; 
		    	var upload_date_time_utc=data[i].upload_date_time_utc;
				var status=data[i].process_status.split("|");
				var org=data[i].organization;

				var model_status=status[0];
				var device_status=status[1];
				var instance_status=status[2];

				var model_color=model_status=='Created'?'green':'red';
				var device_color=device_status=='Created'?'green':'red';
				var instance_color=instance_status=='Created'?'green':'red';

		    	var html_element_id=file_without_ext.replace(/\s/g, '').concat('|'.toString(),upload_date_time_utc.toString()); 
		    	var element_id=file.concat('|'.toString(),upload_date_time_utc.toString()); 
				var view_details_onlcick='onclick="getFileIngestedData('.concat("'",element_id,"')\"");
				var delete_onlcick='onclick="deleteElementId('.concat("'",instance_status,"','",file,"','",element_id,"','",access_token,"'",')"');
				var dl_onlcick='onclick="dl_file('.concat("'",file,"'",')"');
				
				s+="<tr>";

				if(role=='RootAdmin'){
					
					s+='<td style="border: 1px solid black;"><button class="dl_btn"' +dl_onlcick+'><i class="fa fa-download"></i> </button></td>';
					s+='<td style="border: 1px solid black;">'+org+'</td>';
					s+='<td class="td_excel_file" style="text-align-last: left;width: 900px;font-family: Montserrat;font-weight: 400;font-size: 14px;" >'+file+'</td>';
					s+='<td class="td_excel_file" style="width: 256px; font-family: Montserrat;font-weight: 600;font-size: 14px;">';

					s+='Model: <span style="color:'+model_color +'">'+model_status+'</span><br>';
					s+='Device(s): <span style="color:' +device_color+';">'+device_status+'</span><br>';
					s+='Instance(s): <span style="color: '+instance_color+';">'+instance_status+'</span>';

					s+='</td><td class="td_excel_file"  style="width: 370px; font-family: Montserrat;font-weight: 400;font-size: 14px;">'+upload_date_time;
					s+='<td style="text-align-last: left;width: 110px;border: 1px solid black;"><input id=detail_'+html_element_id+' '+view_details_onlcick+' value="View Details"  class="view_detail" type="button" style="background-color: rgb(69, 183, 175);border: none;color: white;font-family: Montserrat;font-weight: bold; text-transform: uppercase;height: 48px;width: 100%;position: relative;font-size: 14px;text-align-last: center;" >';

//	 				if(role=='Tool Admin' || role=='Tool Admin'){
					s+='<td style="text-align-last: left;width: 110px;border: 1px solid black;"><input id=delete_'+html_element_id+' '+delete_onlcick+' value="DELETE"  class="view_detail" type="button" style="background-color: #e37777;border: none;color: white;font-family: Montserrat;font-weight: bold; text-transform: uppercase;height: 48px;width: 100%;position: relative;font-size: 14px;text-align-last: center;" >';
//	 				}
					
					s+='</td></tr>';
					}else{
					s+='<td class="td_excel_file" style="text-align-last: left;width: 970px;font-family: Montserrat;font-weight: 400;font-size: 14px;" >'+file+'</td>';
					s+='<td class="td_excel_file" style="width: 256px; font-family: Montserrat;font-weight: 600;font-size: 14px;">';

					s+='Model: <span style="color:'+model_color +'">'+model_status+'</span><br>';
					s+='Device(s): <span style="color:' +device_color+';">'+device_status+'</span><br>';
					s+='Instance(s): <span style="color: '+instance_color+';">'+instance_status+'</span>';

					s+='</td><td class="td_excel_file"  style="width: 370px; font-family: Montserrat;font-weight: 400;font-size: 14px;">'+upload_date_time;
					s+='<td style="text-align-last: left;width: 110px;border: 1px solid black;"><input id=detail_'+html_element_id+' '+view_details_onlcick+' value="View Details"  class="view_detail" type="button" style="background-color: rgb(69, 183, 175);border: none;color: white;font-family: Montserrat;font-weight: bold; text-transform: uppercase;height: 48px;width: 100%;position: relative;font-size: 14px;text-align-last: center;" >';

					var role="<?=$role_att; ?>";

//	 				if(role=='Tool Admin' || role=='Tool Admin'){
					s+='<td style="text-align-last: left;width: 110px;border: 1px solid black;"><input id=delete_'+html_element_id+' '+delete_onlcick+' value="DELETE"  class="view_detail" type="button" style="background-color: #e37777;border: none;color: white;font-family: Montserrat;font-weight: bold; text-transform: uppercase;height: 48px;width: 100%;position: relative;font-size: 14px;text-align-last: center;" >';
//	 				}
					s+='</td></tr>';
					}
        	}  

	      $("#table_uploaded_files").html(s);
	  	  var table_uploaded_files=document.getElementById('table_uploaded_files');
		  var height = table_uploaded_files.offsetHeight;
		  var height_int= parseInt(height);
		  var height_int_plus_350=height_int+350;
		  var height_int_plus_350_string=height_int_plus_350.toString();
		  height_int_plus_350_string+="px";
		  document.getElementById("upload_file").style.height =height_int_plus_350_string;
	    	}else{
		    	var s='<tr><td style="text-align-last: left;width: 120px;background: white;"></td><th style="background:lightgray;font-size: 14px;font-weight: bold;color: black;font-family: Montserrat;border: black 1px solid;width:1289px;" colspan="3">No File has been uploaded yet!</th><td  style="background:white;border-left: none; border-right: none;" colspan="1"></td></tr>';
		    	 $("#table_uploaded_files").html(s);
		    	}
	   
	    },
	    error: function (xhr, error) {
	        console.debug(xhr); 
	        console.debug(error);
	      }
		});
		///////////////////Get upload limit
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
				document.getElementById("input_fileToUpload").disabled = true;
				document.getElementById("submit_button").disabled = true;
		        document.getElementById("submit_button").style.backgroundColor ="lightslategrey";
				}else{
				 document.getElementById('instructor').innerHTML = 'Please, upload your file! (You have uploaded ' +current+' files (Maximum: '+limit+'))';
				}
		    },
		    error: function (xhr, error) {
		        console.debug(xhr); 
		        console.debug(error);
		      }
			});
		}
		////////////////////////////////////////
		var file = document.getElementById('input_fileToUpload');

			file.onchange = function() {
			var _file = this.files[0].name;

        	if (_file) {
        		//A new file uploaded .. check if it has been already uploaded
        		$.ajax({  
        			    type:"GET",  
        			    url:"datatablemanager_checkFileUploaded.php",
        			    data:"file="+_file,    
        			    success:function(data){ 
        	    	if(data=='true'){
                		    	//file already uploaded
                	            alert('File with the same name has been already uploaded!');
        			    	}else{
        			    		//file has not been uploaded
        						document.getElementById('fileLabel').textContent = _file;
        						$('#hidden_file_name').val(_file);
        						
        						document.getElementById('fileLabel').style.color = "black";
        						document.getElementById("submit_button").disabled = false;
        						document.getElementById("hidden_sheet_name").value = "";
        						document.getElementById("hidden_nature").value =  "";
        						document.getElementById("hidden_sub_nature").value =  "";
        						document.getElementById("hidden_observed_date_type").value =  "";
        						document.getElementById("hidden_observed_date_for_sheets_date_time_pickers").value =  "";
        						document.getElementById("hidden_observed_date_for_file_name").value =  "";
        						document.getElementById("hidden_file_to_uplolad_fromvalue_type_page").value =  "";
        						document.getElementById("hidden_observed_date_for_row_header").value =  "";
        						document.getElementById("hidden_selected_value_names").value =  "";
        						document.getElementById("hidden_value_unit_comboboxes").value =  "";
        						document.getElementById("hidden_value_type_comboboxes").value =  "";
        						document.getElementById("hidden_all_columns").value =  "";
        						document.getElementById("hidden_observed_date_for_file_name_for_show").value =  "";

        						document.getElementById("hidden_coordinate_type").value =  "";
        						document.getElementById("hidden_lat_file").value =  "";
        						document.getElementById("hidden_lon_file").value =  "";
        						document.getElementById("hidden_lat_sheet").value =  "";
        						document.getElementById("hidden_lon_sheet").value =  "";
        						document.getElementById("hidden_lat_row").value =  "";
        						document.getElementById("hidden_observed_date_for_file_name_for_show").value =  "";
        						document.getElementById("hidden_observed_date_none").value =  "";
        						
        						document.getElementById("upload_file").submit();
        						}		
        			    },
        			    error: function (xhr, error) {
        			        console.debug(xhr); 
        			        console.debug(error);
        			      }
        		});
        	} else {
				alert("Please, insert a file!");
			}
		};


		function deleteFile(element_id,access_token){
            $("#span_delete_file").html("Please wait..");
            
	    	$.ajax({  
			    type:"GET",  
			    url:"datatablemanager_deleteElementId.php",
			    data:"elementId="+element_id+"&access_token="+access_token,    
			    success:function(data){ 
	    	if(data["status"]=='ok'){
	       	 	$("#span_delete_file").html("");
		    	
	            $("#span_delete_file").html("File deleted successfully!");
	            $("#deleteFileModal").delay(2500).fadeOut(500);

	            setTimeout(function() {
	            	document.getElementById("upload_file").submit();}, 3200);
	            
			    	}else{
			       	 	$("#span_delete_file").html("");
			            document.getElementById("div_deleteFileWaitingDescription").style.display='none';
			            $("#h5_deleteFileWaitingDescription").html(data["message"]);
				    	}
			    },
			    error: function (xhr, error) {
			        console.debug(xhr); 
			        console.debug(error);
			      }
				});
			}

	    function deleteElementId(instance_status, file,element_id,access_token){
            document.getElementById("ConfirmBtn").disabled = false;
            document.getElementById("div_deleteFileWaitingDescription").style.display='none';
            document.getElementById("deleteFileModal").style.display='block';
            document.getElementById("CancelBtn").onclick = function() { cancelDeleteFile(); }
			if(instance_status=='Created'){
	            $("#span_delete_file").html("You cannot delete file <b>"+file+"</b> because the associated instances with the file have been created!");
	            document.getElementById("ConfirmBtn").disabled = true;
				}else{
		            $("#span_delete_file").html("Are you sure you want to delete file <b>"+file+"</b>?");
		            document.getElementById("ConfirmBtn").onclick = function() { deleteFile(element_id,access_token); }
					}	
			}

		function cancelDeleteFile(){
            document.getElementById("deleteFileModal").style.display='none';
			}
			
    function getFileIngestedData(elementId){
        var close=document.getElementById("Close");
	 	var left = 350;
		var top = 780;
    	var url="datatablemanager_getFileIngestedData.php?elementId="+elementId;
		var iframe=document.getElementById("show_digested_data");
		iframe.setAttribute("src",url);
   		window.open(url, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=1, resizable=yes, copyhistory=no, width='+1235+', height='+510+', top='+top+', left='+left);
    		}


    function checkFileExist(urlToFile) {
        var xhr = new XMLHttpRequest();
        xhr.open('HEAD', urlToFile, false);
        xhr.send();
         
        if (xhr.status == "404") {
            return false;
        } else {
            return true;
        }
    }
   
	function dl_file(file){
		var target_dir='<?php echo $target_dir ?>';
		var link = document.createElement("a");
	    link.download = name;
	    link.href = target_dir+file;
	    link.click();
		}

	</script>
	
	<?php

	$configs = parse_ini_file('datatablemanager_config.ini.php');
	$target_dir = $configs['target_dir'];
	$file_name = basename($_FILES["input_fileToUpload"]["name"]);
    $file = $_FILES['input_fileToUpload']['name'];
    $path = pathinfo($file);
    $filename = $path['filename'];
        
        $file_name=basename($_FILES["input_fileToUpload"]["name"]);
        if (count($file_name)==0){
            $file_name=$_POST['hidden_file_to_uplolad_fromvalue_type_page'];
        }
        
        $ext = $path['extension'];
        $temp_name = $_FILES['input_fileToUpload']['tmp_name'];
        $path_filename_ext = $target_dir.$filename.".".$ext;
        move_uploaded_file($temp_name,$path_filename_ext);
    	$warnings = array();
    	$errors = array();
    	$acceptable = array(
    		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    		'application/vnd.ms-excel'
    	);

	   require_once('datatablemanager_SimpleXLSX.php');

		if ((!in_array($_FILES['input_fileToUpload']['type'], $acceptable)) && (!empty($_FILES['input_fileToUpload']['type']))) {
			$errors[] = 'Invalid file type. Only .XLSX types are accepted.';
		}

		if (!empty($errors)) {
			foreach ($errors as $error) {
				echo '<script>alert("' . $error . '");</script>';
			}

			echo "<script>";
			echo " document.getElementById(\"submit_button\").disabled = true;";
			echo "</script>";
			sendDtmFailedUploadEmail($file_name, $errors,$organization,$utente_att);
		} else {
			// check if the file well-formated
			$warnings = getWarningArray($target_dir, $file_name);
			$warning_counter = 0;

			if (!empty($warnings)) {
			 
				echo "<script>";
				echo " x=document.getElementById(\"warning_span\");";
				echo " x.style.display = \"block\";";
				echo " x=document.getElementById(\"div_warninglist\");";
				echo " x.style.display = \"block\";";
				echo " var  file_label=document.getElementById(\"fileLabel\");";
				echo " file_label.style.color = \"red\";";
				echo " document.getElementById(\"fileLabel\").innerHTML =\"$file_name\";";
				echo " document.getElementById(\"upload_file\").action = \"datatablemanager_upload_file.php?showFrame=false\";";
				echo " var completelist= document.getElementById(\"warninglist\");";
				do {
					echo " completelist.innerHTML += \"<li class=warning_item_uploading_file> $warnings[$warning_counter] </li>\";";
					++$warning_counter;
				} while ($warning_counter < count($warnings));

				echo " document.getElementById(\"submit_button\").disabled = true;";
				echo "</script>";
				sendDtmFailedUploadEmail($file_name, $warnings,$organization,$utente_att);
			} else {
				echo "<script>";
				echo " var  file_label=document.getElementById(\"fileLabel\");";
				echo " document.getElementById('fileLabel') .innerHTML =\"$file_name\";";
				echo " document.getElementById(\"submit_button\").disabled = false;";
				echo " document.getElementById(\"hidden_file_name\").value = \"$file_name\";";
				echo "</script>";
			}
		}
?>

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
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
	});

//     function validateForm() {
//     	alert('here');
//     	if(document.getElementById("input_fileToUpload").value==""){
//     		alert('Please, select a file!');
//     		return false;
//     		}
// //     	var file_name = document.getElementById('fileLabel').textContent;
// //    		alert(file_name);
// //     	$('#hidden_file_name').val(file_name);
    	
//     	return true;
//     }

</script>

	<input id="hidden_all_headers" type="hidden" name="hidden_all_headers" value="<?php echo htmlspecialchars(json_encode($all_headers_array)); ?>">

</body>
</html>


<!-- onclick="document.getElementById('mainContentCnt').onClick=null;" -->
