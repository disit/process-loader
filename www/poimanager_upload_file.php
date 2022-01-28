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
// include_once 'datatablemanager_myUtil.php';
include_once 'poimanager_myUtil.php';
$configs = parse_ini_file('poimanager_config.ini.php');
$target_dir = $configs['target_dir'];

error_reporting(E_ALL);
// ini_set('error_log', 'path_to_log_file');
// ini_set('log_errors_max_len', 0);
// ini_set('log_errors', true);
//include('functionalities.php');
ini_set('max_execution_time', 0); 
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
$organization = checkPoiLdapOrganization($ds, $ldapUsername, $ldapBaseDN);

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

        <title>POI Loader - File Upload</title>
		
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
		<link rel="stylesheet" href="css/poimanager_upload_file.css">
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
							<form enctype="multipart/form-data" method="POST"  name="upload_file" id="upload_file" action="poimanager_upload_file.php?showFrame=false" >
								<div id="div_navigation_btns">
									<input id="submit_button" style="font-family: 'Montserrat';" class="submit" type="submit" value="Next"  formaction="poimanager_general_information.php?showFrame=false" disabled>
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
											<div class="modal-body" id="modal-body">
												<div class="modalBodyInnerDiv">
													<span id="span_delete_file">	</span>
												</div>
											</div>
											<div id="div_deleteFileWaitingDescription"
												class="modalBodyInnerDiv" style="display: none;position: relative;top:-15px">
												<h5 id="h5_deleteFileWaitingDescription">File deleted successfully!</h5>
											</div>
<!-- 											<div id="div_deleteFileWaitingCircle" -->
<!-- 												class="modalBodyInnerDiv" style="display: none;"> -->
<!-- 												<i class="fa-li fa fa-check" style="font-size: 36px"></i>-->
<!-- 											</div> -->
											<div class="modal-footer"  id="modal-footer" style="height: 50px;background: orange;">
<!-- 												<button type="button" id="deleteDeviceOkBtn" -->
<!-- 													class="btn btn-primary" data-dismiss="modal" -->
<!-- 													style="display: none;">Ok</button> -->
												<img alt="info" src="img/datatablemanager_info.png"
													style="width: 20px; left: 20px; position: absolute;">
												<span id="span_delete_desc">The associated RDF file must be also deleted!</span>
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
										<legend class="guideline">General Guidelines</legend>
										<ul class="guideline">
<!-- 										<li class="li_guideline">A Point of Interest (POI), is a specific point location that someone may find it useful or interesting for a place</li> -->
<!-- 										<li class="li_guideline">Typically, POIs are non-date-related information because they describe a place of interest in the city that is static or unlikely to be moved (For example, churches, museums, historic squares)</li> -->
<!-- 										<li class="li_guideline">A GPS POI specifies, at minimum, the latitude and longitude of POI</li>	 -->
<!-- 										<li class="li_guideline">A file to be uploaded must follow the template provided and described <a href="">here</a></li>	 -->
										
										<li class="li_guideline">Use "Previous" and "Next/Save" (not browser navigation) buttons to move to previous and next pages</li>
<!-- 											<li class="li_guideline">In multi-sheet files, coulmn headers -->
<!-- 												in all sheets must be the same. If they are different and -->
<!-- 												describe a different kind of data, the file can be split -->
<!-- 												into more than one</li> -->
<!-- 											<li class="li_guideline">In multi-sheet files, the number of -->
<!-- 												columns in all sheets must be the same</li> -->
											<li class="li_guideline">Avoid using special characters in cells (For example,|,?)</li>

											<li class="li_guideline">Avoid using special characters in
												file name (For example,|,/,#,@,%,','[',']',',')</li>
											<li class="li_guideline">Avoid using special characters in
												sheet name(s) and column headers (For example, blank
												space,|,/,#,@,%,','[',']',',')</li>
											<li class="li_guideline">Avoid using too long names for file
												name, sheet name(s), and column headers (maximum 50
												characters)</li>
											<li class="li_guideline">Avoid using line breaks in column
												headers</li>
											<li class="li_guideline">Empty columns
												(those without headers that contain values, including blank
												spaces) are not allowed</li>
											<li class="li_guideline">Columns with number values must be
												stored with number (not string) format</li>
											<li class="li_guideline">Avoid using rounded values if they
												are supposed to be integer ones. Otherwise, they are
												considered as float</li>
										</ul>
									</fieldset>
								</div>

								<div id="div_upload_file">
									<input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" name="input_fileToUpload" id="input_fileToUpload"	style="color: transparent;" /> 
									<label id="fileLabel" for="fileLabel">No file chosen</label>
								</div>

								<span class="label warning" id="warning_span">The file is not well-formated! Please, revise it according to the following guidelines and re-upload it</span>
								<div id="div_warninglist">
									<ul id="warninglist"></ul>
								</div>

								<div id="div_uploaded_files_table"
									style="height: fit-content; left: 715px; position: absolute; top: 330px; width: 1476px; padding-bottom: 30px;">
									<table id="table_uploaded_files" style="max-width: 106%; overflow-x: hidden; width: 1510px; height: 300px; position: relative; top: 0px; left: -700px; overflow-y: scroll; display: block; background: padding-box;"
										class="blueTable">
									</table>
								</div>

								<!-- 		Hidden Inputs-->
								<input type="hidden" id="hidden_file_name" name="hidden_file_name" value="<?php echo $_POST['hidden_file_name']; ?>"> 
								<input type="hidden" id="hidden_poi_file_type" name="hidden_poi_file_type" value="<?php echo $_POST['hidden_poi_file_type']; ?>"> 
								<input type="hidden" id="hidden_access_token" name="hidden_access_token" value="<?php echo $access_token; ?>">
                                <!-- From next pages -->
								<input id="hidden_lang" type="hidden" name="hidden_lang" value="<?php echo $_POST['hidden_lang']; ?>"> 
                                <input id="hidden_nature" type="hidden" name="hidden_nature" value="<?php echo $_POST['hidden_nature']; ?>"> 
    							<input 	id="hidden_sub_nature" type="hidden" name="hidden_sub_nature" value="<?php echo $_POST['hidden_sub_nature']; ?>"> 
    							<input 	id="hidden_latitude" type="hidden" name="hidden_latitude" value="<?php echo $_POST['hidden_latitude']; ?>"> 
    							<input 	id="hidden_longitude" type="hidden" name="hidden_longitude" value="<?php echo $_POST['hidden_longitude']; ?>"> 
    							<input  id="hidden_radius" type="hidden" name="hidden_radius" value="<?php echo $_POST['hidden_radius']; ?>"> 
                    			<input type="hidden" name="hidden_observed_date_type" id="hidden_observed_date_type" value="<?php echo $_POST['hidden_observed_date_type']; ?>"> 
                    			<input type="hidden" name="hidden_file_to_uplolad_fromvalue_type_page" id="hidden_file_to_uplolad_fromvalue_type_page" value="<?php echo $_POST['hidden_file_to_uplolad_fromvalue_type_page']; ?>"> 
								<input type="hidden" name="hidden_nature" id="hidden_nature" value="<?php echo $_POST['hidden_nature']; ?>"> 
                    			<input id="hidden_sub_nature" type="hidden" name="hidden_sub_nature" value="<?php echo $_POST['hidden_sub_nature']; ?>">
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
				///////////////////Get upload limit
				$.ajax({  
				    type:"GET",  
				    url:"poimanager_getDataTablesLimitByUsername.php",
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
						 document.getElementById('instructor').innerHTML = 'Please, upload your file, following the <a style="color:white" href="https://www.snap4city.org/drupal/node/731">manual</a> and the <a style="color:white" href="https://www.snap4city.org/drupal/sites/default/files/files/POI-loader-template-new.zip">Refrence Template</a>! (You have uploaded ' +current+' files (Maximum: '+limit+'))';
						}
				    },
				    error: function (xhr, error) {
				        console.debug(xhr); 
				        console.debug(error);
				      }
					});
		//////////////List Table////////////////////////////////////
		$.ajax({  
	    type:"GET",  
	    url:"poimanager_getDataTableList.php",
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
			
			if(role=='RootAdmin'){
    			var s='<tr><td style="background:white"><th style="background:#1C6EA4;color: black;border: black 1px solid;" colspan="5">Uploaded Files ('+uploaded_files_count+')</th><td  colspan="3" style="background:white;border-left: none; border-right: none;"></td></tr>';
    			s+='<tr style="background: #1C70D2; color: black;">';
    			s+='<td style="background:white"></td>';
    			s+='<td class="td_excel_file" style="width: 200px;">Organization';
    			s+='<td class="td_excel_file" style="width: 650px;">File Name';
    			s+='</td><td class="td_excel_file" style="width: 360px;">Triple Status';
    			s+='</td><td class="td_excel_file" style="width: 450px;">RDF File';
    			s+='</td><td class="td_excel_file" style="width: 500px;">Upload Date & Time';
			}else{
    			var s='<tr><th style="background:#1C6EA4;color: black;border: black 1px solid;" colspan="4">Uploaded Files ('+uploaded_files_count+')</th><td  colspan="3" style="background:white;border-left: none; border-right: none;"></td></tr>';
    			s+='<tr style="background: #1C70D2; color: black;">';	
				s+='<td class="td_excel_file" style="width: 850px;">Excel File';
				s+='</td><td class="td_excel_file" style="width: 360px;">Triple Status';
				s+='</td><td class="td_excel_file" style="width: 600px;">RDF File';
				s+='</td><td class="td_excel_file" style="width: 450px;">Upload Date & Time';
				}
			s+='</td><td style="background: white;" colspan="3"></td>';
			s+="</tr>";
			
	    	for (var i=0;i<jsonObject.length;++i){
		    	var file=data[i].file; 
		    	var file_without_ext=file.substring(0,file.indexOf('.'));
		    	var rdf_file_name=data[i].rdf_file_name;
		    	var upload_date_time=data[i].upload_date_time; 
		    	var upload_date_time_utc=data[i].upload_date_time_utc;
				var status=data[i].process_status;
				var org=data[i].organization;

				var status_color=status=='Created'?'green':'red';
		    	var html_element_id=file_without_ext.replace(/\s/g, '').concat('|'.toString(),upload_date_time_utc.toString()); 
		    	var element_id=file.concat('|'.toString(),upload_date_time_utc.toString()); 
				var view_details_onlcick='onclick="getFileIngestedData('.concat("'",element_id,"')\"");
				var delete_onlcick='onclick="deleteElementId('.concat("'",status,"','",file,"','",element_id,"','",access_token,"'",')"');
				var change_status_onlcick='onclick="changeStatus('.concat("'",status,"','",element_id,"','",rdf_file_name,"'",')"');
				var dl_onlcick='onclick="dl_file('.concat("'",file,"'",')"');
				
				s+="<tr>";
				
				if(role=='RootAdmin'){
					s+='<td style="border: 1px solid black;"><button class="dl_btn"' +dl_onlcick+'><i class="fa fa-download"></i> </button></td>';
					s+='<td class="td_excel_file">'+org+'</td>';
					}
				
				s+='<td class="td_excel_file" style="text-align-last: left;" >'+file+'</td>';

				if(status=="Created"){
				s+='<td class="td_excel_file" style="font-weight: 600;">';
				s+='<span style="color:'+status_color +'">'+status+'</span><br></td>';
				s+='<td class="td_excel_file" style="text-align-last: left;">';
				s+='<span>'+rdf_file_name+'</span><br></td>';
				}else{
					s+='<td class="td_excel_file" style="font-weight: 600;">';
					s+='<span style="color:'+status_color +'">'+status+'</span><br></td>';
					s+='<td class="td_excel_file" style="font-weight: 600;">';
					s+='<span>-</span><br></td>';
					}
				
				s+='</td><td class="td_excel_file">'+upload_date_time;

				if(role=='RootAdmin'){
				s+='<td class="list_table_buttons"><input id=detail_'+html_element_id+' '+view_details_onlcick+' value="View Details" class="view_detail list_table_buttons" type="button" style="background-color: rgb(69, 183, 175);" >';
				s+='<td class="list_table_buttons"><input id=change_status_'+html_element_id+' '+change_status_onlcick+' value="Change Status" type="button" class="list_table_buttons" style="background-color: orange;" >';
				s+='<td class="list_table_buttons"><input id=delete_'+html_element_id+' '+delete_onlcick+' value="DELETE" type="button" class="list_table_buttons" style="background-color: #e37777;" >';
				}else{
				s+='<td class="list_table_buttons"><input id=detail_'+html_element_id+' '+view_details_onlcick+' value="View Details" class="view_detail list_table_buttons" type="button" style="background-color: rgb(69, 183, 175);" >';
				s+='<td class="list_table_buttons"><input id=delete_'+html_element_id+' '+delete_onlcick+' value="DELETE" type="button" class="list_table_buttons" style="background-color: #e37777;" >';
				}
				s+='</td></tr>';
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
		}
		////////////////////////////////////////
		var file = document.getElementById('input_fileToUpload');

		file.onchange = function() {
			var _file = this.files[0].name;

			if (_file) {
				//A new file uploaded .. check if it has been already uploaded
        		$.ajax({  
        			    type:"GET",  
        			    url:"poimanager_checkFileUploaded.php",
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
        				document.getElementById("hidden_lang").value =  "";
        				document.getElementById("hidden_nature").value =  "";
        				document.getElementById("hidden_latitude").value =  "";
        				document.getElementById("hidden_longitude").value =  "";
        				document.getElementById("hidden_longitude").value =  "";
        				document.getElementById("hidden_radius").value =  "";
        				document.getElementById("hidden_file_to_uplolad_fromvalue_type_page").value =  "";
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
		}		

		function changeTripleStatus(element_id,rdf_file){
            $("#span_delete_file").html("Please wait..");
            
	    	$.ajax({  
			    type:"GET",  
			    url:"poimanager_updateProcessStatus.php",
			    data:"elementId="+element_id+"&status=YES",    
			    success:function(data){ 
	    	if(data["status"]=='ok'){
	       	 	$("#span_delete_file").html("");
	            $("#span_delete_file").html("The status of triples was set to <b>Created</b> successfully!");
	            $("#deleteFileModal").delay(2500).fadeOut(500);

	            setTimeout(function() {
	            	document.getElementById("upload_file").submit();}, 4200);
	            
			    	}else{
			       	 	$("#span_delete_file").html("");
			            document.getElementById("div_deleteFileWaitingDescription").style.display='block';
			            $("#div_deleteFileWaitingDescription").html(data["message"]+' Please, contact us!');
			            document.getElementById("ConfirmBtn").disabled = true;
				    	}
			    },
			    error: function (xhr, error) {
			        console.debug(xhr); 
			        console.debug(error);
			      }
				});
			}

	    function changeStatus(status,element_id,rdf_file){
            document.getElementById("ConfirmBtn").disabled = false;
            $("#deleteDeviceModalLabel").html("Status Change");
            document.getElementById("div_deleteFileWaitingDescription").style.display='none';
            document.getElementById("deleteFileModal").style.display='block';
            document.getElementById("CancelBtn").onclick = function() { cancelDeleteFile(); }
            
			if(status=='Created'){
	            document.getElementById("modal-body").style.background='orange';
	            document.getElementById("modal-footer").style.display='none';
	            document.getElementById("div_deleteFileWaitingDescription").style.display='none';
	            $("#span_delete_file").html("The status of triples is already set to <b>Created</b>!");
	            document.getElementById("ConfirmBtn").disabled = true;
				}else{
		            $("#span_delete_file").html("Are you sure you want to change the status of triples to <b>Created</b>?");
		            document.getElementById("ConfirmBtn").onclick = function() { changeTripleStatus(element_id,rdf_file); }
					}	
			}

		function deleteFile(element_id,access_token){
            $("#span_delete_file").html("Please wait..");
            
	    	$.ajax({  
			    type:"GET",  
			    url:"poimanager_deleteElementId.php",
			    data:"elementId="+element_id+"&access_token="+access_token,    
			    success:function(data){ 
	    	if(data["status"]=='ok'){
	       	 	$("#span_delete_file").html("");
		    	
	            $("#span_delete_file").html("File deleted successfully!");
	            $("#deleteFileModal").delay(2500).fadeOut(500);

	            setTimeout(function() {
	            	document.getElementById("upload_file").submit();}, 4200);
	            
			    	}else{
// 				    	alert(data["message"]);
			       	 	$("#span_delete_file").html("");
			            document.getElementById("div_deleteFileWaitingDescription").style.display='block';
			            $("#div_deleteFileWaitingDescription").html(data["message"]+' Please, contact us!');
			            document.getElementById("ConfirmBtn").disabled = true;
				    	}
			    },
			    error: function (xhr, error) {
			        console.debug(xhr); 
			        console.debug(error);
			      }
				});
			}

	    function deleteElementId(status, file,element_id,access_token){
            document.getElementById("ConfirmBtn").disabled = false;
            document.getElementById("div_deleteFileWaitingDescription").style.display='none';
            document.getElementById("deleteFileModal").style.display='block';
            document.getElementById("CancelBtn").onclick = function() { cancelDeleteFile(); }
			if(status=='YES'){
	            $("#span_delete_file").html("You cannot delete file <b>"+file+"</b> because the associated RDF file has been created!");
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
    	var url="poimanager_getFileIngestedData.php?elementId="+elementId;
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

	$configs = parse_ini_file('poimanager_config.ini.php');
	$target_dir = $configs['target_dir'];
	$file_name = basename($_FILES["input_fileToUpload"]["name"]);
	$file = $_FILES['input_fileToUpload']['name'];
    $path = pathinfo($file);
    $filename = $path['filename'];
        
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

		if ((!in_array($_FILES['input_fileToUpload']['type'], $acceptable)) && (!empty($_FILES['input_fileToUpload']['type']))) {
			$errors[] = 'Invalid file type. Only .XLSX types are accepted.';
		}

		if (!empty($errors)) {
			foreach ($errors as $err) {
				echo '<script>alert("' . $err . '");</script>';
			}

			echo "<script>";
			echo " document.getElementById(\"submit_button\").disabled = true;";
			echo "</script>";
			sendPoiFailedUploadEmail($file_name, $errors,$organization,$utente_att);
		} else {
			// check if the file well-formated
			$warnings = getPoiWarningArray($target_dir, $file_name);
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
				echo " document.getElementById(\"upload_file\").action = \"poimanager_upload_file.php?showFrame=false\";";
				echo " var completelist= document.getElementById(\"warninglist\");";
				sendPoiFailedUploadEmail($file_name, $warnings,$organization,$utente_att);
				do {
					echo " completelist.innerHTML += \"<li class=warning_item_uploading_file> $warnings[$warning_counter] </li>\";";
					++$warning_counter;
				} while ($warning_counter < count($warnings));

				echo " document.getElementById(\"submit_button\").disabled = true;";
				echo "</script>";
			} else {
			    
			    $error=checkFileType($target_dir,$file_name);
			    
			    if($error=="casea" || $error=="caseb"){
			        
    				echo "<script> ";
    				echo "var  file_label=document.getElementById(\"fileLabel\");";
    				echo " document.getElementById('fileLabel') .innerHTML =\"$file_name\";";
    				echo " document.getElementById(\"submit_button\").disabled = false;";
    				echo " document.getElementById(\"hidden_file_name\").value = \"$file_name\";";
    				echo " document.getElementById(\"hidden_poi_file_type\").value = \"$error\";";
    				echo "</script>";
			    }else{
			        echo "<script>";
			        echo " x=document.getElementById(\"warning_span\");";
			        echo " x.style.display = \"block\";";
			        echo " x=document.getElementById(\"div_warninglist\");";
			        echo " x.style.display = \"block\";";
			        echo " var  file_label=document.getElementById(\"fileLabel\");";
			        echo " file_label.style.color = \"red\";";
			        echo " document.getElementById(\"fileLabel\").innerHTML =\"$file_name\";";
			        echo " document.getElementById(\"upload_file\").action = \"poimanager_upload_file.php?showFrame=false\";";
			        echo " var completelist= document.getElementById(\"warninglist\");";
			        echo " completelist.innerHTML += \"<li class=warning_item_uploading_file> $error </li>\";";
			        echo " document.getElementById(\"submit_button\").disabled = true;";
			        echo "</script>";
			    }
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
// 						else{
// 						window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
// 						}
					}
		//
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
	});


</script>

	<input id="hidden_all_headers" type="hidden" name="hidden_all_headers" value="<?php echo htmlspecialchars(json_encode($all_headers_array)); ?>">

</body>
</html>


<!-- onclick="document.getElementById('mainContentCnt').onClick=null;" -->
