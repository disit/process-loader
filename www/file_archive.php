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
include('aggiornaUplaoadedFiles.php');
include('control.php');
//
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

$message ="";
if (isset ($_REQUEST['elimination'])){
	$message=$_REQUEST['elimination'];
}

$errore ="";
if (isset($_REQUEST['errore'])){
	$errore =$_REQUEST['errore'];
}

if (isset($_REQUEST['showFrame'])){
	if ($_REQUEST['showFrame'] == 'false'){
				$hide_menu= "hide";
			}else{
				$hide_menu= "";
			}	
	}else{
		$hide_menu= "";
	}

if (!isset($_GET['pageTitle'])){
	$default_title = "Process Loader: Uploaded Resources";
}else{
	$default_title = "";
}

if (isset($_REQUEST['not_valid'])){
	$not_valid = "error";
}else{
	$not_valid ="";
}

if (isset($_REQUEST['error'])){
	$error_message="error_during_upload";
}else{
	$error_message="";
}
?>

<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <title>Process Loader</title>
		
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
       <link rel="stylesheet" href="dynatable/jquery.dynatable.css">
       <script src="dynatable/jquery.dynatable.js"></script>        
       <!-- Font awesome icons -->
        <link rel="stylesheet" href="fontAwesome/css/font-awesome.min.css">       
        <!-- Custom CSS -->
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/dashboardList.css" rel="stylesheet">
			
		<script type="text/javascript" src="js/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
        <link href="js/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
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
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php include "mobMainMenu.php" ?></div>
                    </div>
			
			<div class="row">
			<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1)'>
			<!-- Upload New File-->
			<div id="view-menu" style="margin-left:45px; margin-bottom:20px; margin-top:20px;">
				<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#Upload">
				<i class="fa fa-upload"></i> 
					  upload New Resource
					</button>
			</div>
			<!-- Fine Upload New File-->
			<!--<h2>Uploaded File list	 <span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></h2>-->
				<table id="elenco_files" class="table table-striped table-bordered">
					<thead class="dashboardsTableHeader">
					<tr>
						<th hidden>N</th>
						<th hidden>id</th>
						<th>file Name</th>
						<th class="username_td" hidden>Username</th>
						<th>Upload Date</th>
						<th>Description</th> 
						<th>App type</th>
						<th>Control Status</th>
						<th>Process Model</th>
						<th>Process Model list</th>
						<th>Metadata</th>   <!-- agg -->
						<th>Published</th>
						<th>Delete</th>
					</tr>
					</thead>
			   </table>
		
		
			</div>
			</div>
		</div>
	</div>
	</div>
<!-- Nuovo Processo -->
<!-- Parametri processi 1-->
	<div class="modal fade" id="data-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color: white">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="titolo_proc"></h4>
				</div>
				<form name="param_login_0" method="post" id="create_jobType" action="job_type_creation.php" accept-charset="UTF-8">
					<div class="form-parametri" id="Process_0">
						<ul class="nav nav-tabs">
							<li><a data-toggle="tab" href="#job_parameter" class="active">Process Parameters</a></li>
							<li><a data-toggle="tab" href="#job_trigger">Trigger</a></li>
							<li><a data-toggle="tab" href="#job_advanced">Advanced Paramters</a></li>
						</ul>
						<div class="panel-body">
							<div class="tab-content">
								<div id="job_parameter" class="tab-pane fade in active">
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>Id: </span><input id="id" type="text" class="form-control" name="id" hidden readonly></div>
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>File: </span><input id="file_zip" type="text" class="form-control" name="file_zip" hidden readonly></div>
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>Creation Date: </span><input id="data_zip" type="text" class="form-control" name="data_zip" hidden readonly></div>
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>File Type: </span><input id="tipo_zip" type="text" class="form-control" name="tipo_zip" hidden readonly></div>
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>Utente_zip: </span><input id="user_zip" type="text" class="form-control" name="user_zip" hidden readonly></div>
									<div class="input-group"><span class="input-group-addon">Name *: </span><input id="nome" type="text" class="form-control" name="nome" required="required"></div>
									<br />
									<div class="input-group"><span class="input-group-addon">Description *: </span><input id="descrizione" type="text" class="form-control" name="descrizione" required="required"></div>
									<br />
									<div class="input-group"><span class="input-group-addon">Group *: </span><input id="gruppo" type="text" class="form-control" name="gruppo" required="required"></div>
									<br />		
									<div class="input-group" style="display:none;"><span class="input-group-addon" >Url: </span><input id="url" type="text" class="form-control" name="url" readonly></div>
									<div class="input-group" style="display:none;"><span class="input-group-addon" >Path: </span><input id="path" type="text" class="form-control" name="path" readonly></div>
									<div class="input-group" style="display:none;"><span class="input-group-addon" >type: </span><input id="type" type="text" class="form-control" name="type" readonly></div>		
				<!--ProcParameters-->
									<div class="input-group" style="display:none;"><span class="input-group-addon" >Process Parameters: </span><input id="ProcParameters" type="text" class="form-control" name="ProcParameters" readonly></div>
				<!-- file position-->
									<div class="input-group" style="display:none;"><span class="input-group-addon" >File position: </span><input id="file_position" type="text" class="form-control" name="file_position" readonly></div>
				<!-- -->
									<div class="input-group"><span class="input-group-addon">E-mail: </span><input id="email" type="text" class="form-control" name="email"><br /></div><br />
									<div class="input-group"><span class="input-group-addon">Time Out: </span><input id="time_out" type="text" class="form-control" name="time_out"></div><br />
									<div class="input-group"><span class="checkbox-inline"><input id="conc" type="checkbox" name="conc"  checked="checked" value="1">non Concurrent</span></div><br />
									<div class="input-group"><span class="checkbox-inline"><input id="store" type="checkbox" name="store"  checked="checked" value="1">Store Durably</span></div><br />
									<div class="input-group"><span class="checkbox-inline"><input id="recovery" type="checkbox" name="recovery" value="1">Request Recovery</span></div><br />		
								</div>
								<div id="job_trigger" class="tab-pane fade">
									<div class="input-group"><span class="input-group-addon">Trigger Name: </span><input id="nome_trig" type="text" class="form-control" name="nome_trig"></div><br />
									<div class="input-group"><span class="input-group-addon">Trigger Description: </span><input id="descrizione_trig" type="text" class="form-control" name="descrizione_trig"></div><br />
									<div class="input-group"><span class="input-group-addon">Trigger Group: </span><input id="gruppo_trig" type="text" class="form-control" name="gruppo_trig"></div><br />	
									<div class="input-group"><span class="input-group-addon">Repeat Count: </span><input id="repeat_trig" type="text" class="form-control" name="repeat_trig" value="0"></div><br />
									<div class="input-group"><span class="input-group-addon">Interval: </span><input id="interval_trig" type="text" class="form-control" name="interval_trig" value="60"></div><br />	
									<div class="input-group"><span class="input-group-addon">Start Time: </span><input id="start" type="text" class="form-control datepicker" name="start" value=""></div><br />
									<div class="input-group"><span class="input-group-addon">End Time: </span><input id="end" type="text" class="form-control datepicker" name="end" value=""></div><br />
									<div class="input-group"><span class="input-group-addon">Priority: </span><input id="priority" type="text" class="form-control" name="priority" value="5"></div><br />
									<div class="input-group"><span class="input-group-addon">Misfire Instruction: </span>
									<select name="misfire" class="selectpicker form-control">
										<option value="DEFAULT">DEFAULT</option>
										<option value="IGNORE_MISFIRE_POLICY">IGNORE_MISFIRE_POLICY</option>
										<option value="FIRE_NOW">FIRE_NOW</option>
										<option value="RESCHEDULE_NOW_WITH_EXISTING_REPEAT_COUNT">RESCHEDULE_NOW_WITH_EXISTING_REPEAT_COUNT</option>
										<option value="RESCHEDULE_NOW_WITH_REMAINING_REPEAT_COUNT">RESCHEDULE_NOW_WITH_REMAINING_REPEAT_COUNT</option>
										<option value="RESCHEDULE_WITH_NEXT_REMAINING_COUNT">RESCHEDULE_WITH_NEXT_REMAINING_COUNT</option>
										<option value="RESCHEDULE_WITH_NEXT_EXISTING_COUNT">RESCHEDULE_WITH_NEXT_EXISTING_COUNT</option>
									</select></div><br />					
								</div>
			<!-- NUOVO ADVANCED -->
								<div id="job_advanced"  class="tab-pane fade">	
									<ul class="list-group">
										<li class="list-group-item" style="background-color: white">
											<div id="AddNextJob">
												<b>Add Next Job </b><span class="glyphicon glyphicon-plus" id="icon_nextjob"></span>
												<input type="text" id="NextJobText" name="NextJobText" hidden readonly></input>
											</div>
										</li>
										<li class="list-group-item" style="background-color: white">	
									<div id="AddJobConstraint">
										<b>Add Job Constraint </b><span class="glyphicon glyphicon-plus" id="icon_jobConstraint"></span>
											<input type="text" id="JobConstraintText" name="JobConstraintText" hidden readonly></input>
													<!--
													</select>
													-->
									</div>
								</div>
										</li>
									</ul>
		<!--<input type="button" name="carica_processo" id="carica_processo" class="btn btn-primary" value="Crea">-->
							</div>
			<!-- FINE ADVANCED-->		
						</div>
					</div>
					<div class="panel-footer">
						<div class="form-group">
						<input type="button" name="chiudi_job" class="btn cancelBtn" value="Cancel" data-dismiss="modal">
						<input type="submit" name="Crea_job_type" class="btn confirmBtn" id="Crea_job_type" value="Confirm">
						</div>
					</div>
			</div>
				</form>
		</div>
	</div>

</div>








<div class="modal fade" id="data-modal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color: white">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="titolo_proc3"></h4>
				</div>
				<form name="param_login_1" method="post" action="modify.php" id="modal_modify" enctype="multipart/form-data" >    <!-- da agg -->
					<div class="form-parametri" id="Process_1">
						<ul class="nav nav-tabs">
							<li><a data-toggle="tab" href="#process_mandatory_parameter" class="active">Process Mandatory Metadata</a></li>

						</ul>
						<div class="panel-body">
							<div class="tab-content">
								<div id="process_mandatory_parameter" class="tab-pane fade in active">
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>Id: </span><input id="id3" type="text" class="form-control" name="id3" hidden readonly></div>
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>File: </span><input id="file_zip3" type="text" class="form-control" name="file_zip3" hidden readonly></div>
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>Publication Date: </span><input id="data_pub3" type="text" class="form-control" name="data_pub3" hidden readonly></div>
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>App Type: </span><input id="tipo_zip3" type="text" class="form-control" name="tipo_zip3" hidden readonly></div>
									<div class="input-group"><span class="input-group-addon">Nature: </span>
									<input id="category3" type="text" class="form-control" name="category3" >
									
									<!--
									<select id="category3" name="category3" class="form-control" value=""></select>-->
									<!---->
									</div>
									<div class="input-group"><span class="input-group-addon">Sub Nature: </span>
									<input id="resource3" type="text" class="form-control" name="resource3" required="required">
									<!--
									<select id="resource3" name="resource3" class="form-control" value=""></select>
									-->
									</div>
									<div class="input-group" id="div-format3"><span class="input-group-addon">Format: </span>
									<input id="format3" type="text" class="form-control" name="format3" >
									</div>	
									<div class="input-group" id="div-protocol3"><span class="input-group-addon">Access: </span>
									<input id="protocol3" type="text" class="form-control" name="protocol3" id="protocol3">
									</div>		
									<div class="input-group"><span class="input-group-addon">Licence: </span>
									<input id="licence3" type="text" class="form-control" name="licence3" >
									</div>			
									<div class="input-group"><span class="input-group-addon">Description: </span>
									<input id="desc3" type="text" class="form-control" name="desc3" >
									</div>	
									<div class="input-group" id="div-help3"><span class="input-group-addon">Help: </span>
									<input id="help3" type="text" class="form-control" name="help3" >
									</div>
									<div class="input-group" id="div-method3"><span class="input-group-addon">Method: </span>
									<input id="method3" type="text" class="form-control" name="method3" >
									</div>		
									<div class="input-group" id="div-url3"><span class="input-group-addon">Url: </span>
									<input id="url3" type="text" class="form-control" name="url3" >
									</div>		
									<div class="input-group" id="div-os3"><span class="input-group-addon">OS: </span>
									<input id="os3" type="text" class="form-control" name="os3" >
									</div>									
									<div class="input-group" id="div-realtime3">
										<span> Real-time: <select name="realtime3" class="form-control">
											  <option value="0">No</option>
											  <option value="1">Yes</option>
											</select></span>
										</div>
									<div class="input-group" id="div-periodic3">
										<span >	Periodic:	<select name="periodic3" class="form-control">
										  <option value="0">No</option>
										  <option value="1">Yes</option>
										</select></span>
										</div>	
									<div class="input-group" id="div-opensource3">
										<span >	OpenSource:	<select name="opensource3" class="form-control">
										  <option value="0">No</option>
										  <option value="1">Yes</option>
										</select></span>
									</div>	
									
									<!--
									<div class="input-group"><span class="input-group-addon">Select Image: </span>
									<input id="img3" type="file" class="form-control" name="img3"  accept="image/*">
									</div>
										-->	
									<div>
									<span>Select Image: </span>
									<input id="img3" type="file" name="img3"  accept="image/*">
									</div>	
									<!--	-->
					
								</div>

							</div>
				<!-- FINE ADVANCED-->		
						</div>
					</div>
					<div class="panel-footer">
						<div class="form-group">
							<input type="button" name="chiudi_job" class="btn cancelBtn" value="Cancel" data-dismiss="modal">
							<input type="submit" name="Modify_process" class="btn confirmBtn" id="Modify_process" value="Confirm">
						</div>
					</div>
					
			</div>
				</form>
		
		
		
	
		</div>
		
		
		
		
	</div>


<!-- LoginModal-->
<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
	  
		<div class="loginmodal-container">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 h4 class="modal-title">Log In</h4><br>
			<form name="form_login" method="post" action="login.php">
				<p>Username</p><input type="text" type="text" name="username" placeholder="Username"></p>
				<p>Password <input type="password" name="password" placeholder="Password"></p>
				<button class="btn confirmBtn">Confirm</button>
			</form>
			
			<div class="login-help">
			</div>
		</div>
	</div>
</div>
<!-- fine Login-->

<!-- Elenco dei Job Type-->
<div class="modal fade bd-example-modal-lg" id="show_jobType" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background-color: white">
				<h4 class="modal-title" id="mostra_elenco">Process Model List</h4>
			</div>
			<div id="contenuto_elenco">
				<table id="elenco" class="table table-striped table-bordered">
					<tr>
						<th>Process Model Id</th>
						<th>Name</th>
						<th>Group</th>
						<th>Type</th>
					</tr>
				</table>
			</div>
			<div class="panel-footer">
				<div class="form-group">
					<input type="submit" name="chiudi_job" id="chiudi_job" class="btn cancelBtn" data-dismiss="modal" value="Cancel">
				</div>
			</div>
		</div>
	</div>
</div>
<!--FIne elenco JobType-->
<!-- Modal -->
<!-- Fine modal creazione processo -->
<!-- delete-modal-->
<div id="delete-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background-color: white">
				<form id="delete_file" method="post" action="delete_file.php">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete uncorrected file</h4>
			</div>
			<div class="panel-body">
				<p>Are you sure do you want delete this uncorrected file from application?</p>
			</div>
			<div id="param_del" hidden>
				<input id="id_file_del" type="text" class="form-control" name="id_file_del" hidden readonly></input>
				<input id="path_del" type="text" class="form-control" name="path_del" hidden readonly></input>
			</div>
			<div class="panel-footer">
				<button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn confirmBtn" value="Confirm">  
			</div>
		</div>
				</form>
	</div>
</div>
<!-- Delete Modal-->
 <!--Form Loader-->
 <div class="modal fade bd-example-modal-lg" id="Upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog">
  
    <div class="modal-content">
<!--<div class="col-xs-12 col-md-6 col-md-offset-3" id="loginFormContainer">-->
<div class="modal-header" style="background-color: white"> Upload new file <span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></div>
<div class="modal-body" style="background-color: white">
<form enctype="multipart/form-data" action="caricaZip.php" id="uploadRes" method="POST" accept-charset="UTF-8">
					
    <!-- MAX_FILE_SIZE deve precedere campo di input del nome file -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000000" />
    <!-- Il nome dell'elemento di input determina il nome nell'array $_FILES -->
	<div>
	<!--<p>Tipo file :   <p><input name="filetype" type="text"  class="form-control"/>-->
	<div class="input-group"><span class="input-group-addon">App Type:</span><select name="filetype" class="selectpicker form-control" id="filetype" onchange="FunctionMicro()">
					<option value="AMMA">AMMA</option>
					<!--<option value="Banana Library">Banana Library</option>-->
					<option value="DevDash">DevDash</option>
					<option value="ETL">ETL</option>
					<option value="Java">Jar</option>
					<!--<option value="MicroService">MicroService</option>-->
					<option value="Mobile App">Mobile App</option>
					<option value="IoTApp">IoTApp</option>
					<option value="IoTBlocks">IoTBlocks</option>
					<option value="R">R</option>
					<option value="ResDash">ResDash</option>
				</select></div>
	</div>
	<br />
	<div>
	<div class="input-group"><span class="input-group-addon">Description : </span><input name="filedesc" type="text" class="form-control" required="required" /></div>
	</div>
	<br />
	<div class="input-group"><span class="input-group-addon">Nature : </span>
	<input name="filenat" type="text" class="form-control" required="required"/>
	<!--
		<select name="filenat" class="selectpicker form-control" id="filenat" required="required"></select>
	-->
	</div>
	<br />
	<div class="input-group"><span class="input-group-addon">SubNature : </span>
	<input name="filesubN" type="text" class="form-control" required="required"/>
	<!--
		<select name="filesubN" class="selectpicker form-control" id="filesubN" required="required"></select>
	<!---->
	</div>
	<br/>
	<div>
	<input name="userfile" type="file" id="upload_file" class="btn btn-secondary"/>
	</div>
	<div class="input-group" id ="micro_name" ><span class="input-group-addon">MicroService Title: </span><input name="micro_name" type="text" class="form-control" id="title_micro"></input>
	</div>
	<br />
	<div class="input-group" id="micro_help" ><span class="input-group-addon">Help: </span><input name="help" type="text" class="form-control" id="help_input"></input>
	</div>
	<br />
	<div class="input-group" id ="micro_url" ><span class="input-group-addon">Url: </span><input name="url" type="text" class="form-control" id="url_input"></input>
	</div>
	<br />

	<div id="elenco_parametri">
	<div id="param_container">
	<div class="input-group">
	<span class="input-group-addon">parameter 1: </span><input type="Text" class="form-control paramItem" name="paramList[0]"></input>
	</div>
	<br />
	</div>
	<input type="button" id="Add_par" class="btn btn-primary btn-sm" value="Add Parameter" />
	<br />
	</div>
	<br />
	<div class="input-group" id ="micro_method" ><span class="input-group-addon">Method: </span><select name="metod" type="text" class="form-control"><option>GET</option><option>POST</option></select>
	</div>
	<br />
	<div class="input-group" id ="micro_check" >
	Do you want create a Microservice with Authentication?		<input type="checkbox" name="auth_check" value="Yes"/> 
	</div>
	<br/>
	<div class="modal-footer" style="background-color: white">
	<input type="button" name="chiudi_job" class="btn cancelBtn" value="Cancel" data-dismiss="modal">
    <input type="submit" value="Confirm" class="btn confirmBtn"/>
	</div>
</form>
</div>
</div>
</div>
</div>
<!--Fine Form-->
 
<!-- Modal Pulish2 -->
  <div class="modal fade bd-example-modal-lg" id="publish" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
		<div class="modal-dialog">
				<form name="change_publish" method="post" action="change_publish.php" id="change_pub">
				<div class="modal-content">
				<div class="modal-header" style="background-color: white">Publish Resource</div>
				<div class="modal-body" style="background-color: white">Are you sure you want change the publish resource status?</div>
				<div>
				<input id="id_pub" type="text" name="id_pub" hidden readonly></input>
				<input id="status_pub" type="text" name="status_pub" hidden readonly></input>
				<input id="nature_pub" type="text" name="nature_pub" hidden readonly></input>
				<input id="subnature_pub" type="text" name="subnature_pub" hidden readonly></input>
				<input id="access_pub" type="text" name="access_pub" hidden readonly></input>
				<input id="format_pub" type="text" name="format_pub" hidden readonly></input>
				<input id="lic_pub" type="text" name="lic_pub" hidden readonly></input>
				<input id="tip_pub" type="text" name="tip_pub" hidden readonly></input>
				</div>
				<div class="modal-footer" style="background-color: white">
				<button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
				<input type="submit" value="Confirm" class="btn confirmBtn"/>
				</div>
				</div>
				</form>
		</div>
  </div>
 <!-- Modal -->
  <div class="modal fade" id="info-modal" role="dialog">
    <div class="modal-dialog" style="background-color: white;">
    
      <!-- Modal content-->
      <div class="modal-content" style="background-color: white;">
        <div class="modal-header" style="background-color: white;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Information</h4>
        </div>
        <div class="modal-body" style="background-color: white;">
		In this page the user can uplaod the files for the new processes as compressed archives.
		The archives have to be mandatorily in .zip format, in files in every other format will be not accepted. The user also have to specify the type of the files in the uploaded archive.
		<br />
		<br />
		For any of the types file there is a specific formalism that defines how a file should be structured to be accepted by the application:
		<ul>
			<li>In the case of ETL files, the ProcessName must be named as the directory, the files must have a directory named Ingestion and a file called Main.kjb.</li>
			<li>In the case of JAR files, files must have a file named Main.java.</li>
			<li>In the case of R files, the files must have a file named Main.R.</li>
		</ul>
        </div>
        <div class="modal-footer" style="background-color: white;">
          <input type="button" name="chiudi_job" class="btn cancelBtn" value="Cancel" data-dismiss="modal">
        </div>
      </div>
      
    </div>
  </div>
<!-- Fine modal creazione processo -->
 <!-- -->
	<script type='text/javascript'>
		function FunctionMicro(){
			var valore_select = $('#filetype').val();
			if(valore_select == 'MicroService'){
				$('#micro_help').show();
				$('#micro_url').show();
				$('#url_input').required=true;
				$('#title_micro').required=true;
				$('#upload_file').hide();
				$('#micro_name').show();
				$('#micro_param').show();
				$("#micro_user").show();
				$("#micro_pass").show();
				$('#elenco_parametri').show();
				$('#micro_method').show();
				$('#micro_check').show();
				
			}else{
				$('#micro_help').hide();
				$('#micro_url').hide();
				$('#url_input').required=false;
				$('#title_micro').required=false;
				$('#upload_file').show();
				$('#micro_name').hide();
				$('#micro_param').hide();
				$("#micro_user").hide();
				$("#micro_pass").hide();
				$('#elenco_parametri').hide();
				$('#micro_method').hide();
				$('#micro_check').hide();
			}
		}
	
	$(document).ready(function () {
		//NASCONDI CAMPI
		$('#micro_help').hide();
		$('#micro_url').hide();
		$('#micro_url').required=false;
		$('#micro_name').hide();
		$('#micro_param').hide();
		$("#micro_user").hide();
		$("#micro_pass").hide();
		$('#elenco_parametri').hide();
		$('#micro_method').hide();
		$('#micro_check').hide();
		///////
		$(document).on('click','#Add_par',function(){
		var cont = $('.paramItem').length;
		var cont2 = cont +1; 
		var attuale = $(".contatore_param").val();
		$("#param_container").append('<div class="input-group"><span class="input-group-addon">parameter '+cont2+': </span><input type="Text" class="form-control paramItem" name="paramList['+cont+']"></input></div><br />');
		$(".contatore_param").text(attuale +' 1');		
		});
		/////
		var errore = "<?=$errore; ?>";
		if(errore =="errore_caricamento"){
		alert("Error occurred during file loading, please try again!");
		}
		//eliminazione
		var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#delete_file').attr("action","delete_file.php?showFrame=false");
			$('#modal_modify').attr("action","modify.php?showFrame=false");
			$('#change_pub').attr("action","change_publish.php?showFrame=false");
			$('#create_jobType').attr("action","job_type_creation.php?showFrame=false");
			$('#uploadRes').attr("action","caricaZip.php?showFrame=false");
		}
		

		
		///////
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
		//
		var dati_not_valid = "<?=$not_valid;?>";
		 if (dati_not_valid  != ""){
			 alert('Any Metadata fields are not valids for publication. Please, insert valid values before to publish using the Edit menu under Metadata column!');
		 }
		
		//redirect
		var role="<?=$role_att; ?>";
	
		if (role == ""){
			$(document).empty();
			if(window.self !== window.top){
			window.location.href='https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';
			}
			else{
			window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
			}
		}
		var role_active = "<?=$process['functionalities'][$role]; ?>";

			//console.log(role_active);
			if (role_active == 0){
						$(document).empty();
						if(window.self !== window.top){
						window.location.href='https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';						
						}
						else{
						window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
						}
					}
			
		var error_message = "<?=$message; ?>";
		if (error_message == "ok"){
			//console.log("File non corretto:"+error_message);
			alert("Uploaded file deleted!");
		}
		if (error_message == "error"){
			//console.log("File non corretto:"+error_message);
			alert("Error during file deleting!");
		}
		//
		$('.pubblicato').change(function() {	
			$('#publish').show();
		});
		//
		var error_upload = "<?=$error_message; ?>";
		if (error_upload =="error_during_upload"){
			alert("Error during resource upload");
		}
		
		/////
		utente_attivo=$("#utente_att").text();
			if (utente_attivo=='Login'){
					alert("Effettua il login!");
				}else{

		//
		var role_active = $("#role_att").text();
		
		$(function(){
			
			var dateNow = new Date();
			$('.datepicker').datetimepicker({
				format: 'yyyy-mm-dd HH:ii:ss',
				language: 'it',
				current: dateNow,
				defaultDate: dateNow,
				startDate: dateNow
			});
		});
		
		
		var array_files = new Array();

		$.ajax({
			url: "getdata.php",
				data: {action: "get_files"},
				type: "GET",
				async: true,
				dataType: 'json',
				success: function (data) {

					//console.log("DATI:" + data);
					for (var i = 0; i < data.length; i++)
					{
						array_files[i] = {
						id: data[i].file['id'],
						category: data[i].file['category'],
						name: data[i].file['name'],
						desc: data[i].file['desc'],
						 date: data[i].file['date'],
						 type:data[i].file['type'],
						 utente:data[i].file['utente'],
						 status:data[i].file['status'],
						 pub: data[i].file['pub'],
						 licence: data[i].file['licence'],
						 resource:data[i].file['resource'],
						 image: data[i].file['image'],
						 realtime:data[i].file['realtime'],
						 periodic: data[i].file['periodic'],
						 format: data[i].file['format'],

						 protocol: data[i].file['protocol'],
						 opensource: data[i].file['opensource'],
						 OS: data[i].file['OS'],
						 help: data[i].file['help'],
						 method: data[i].file['method'],
						 url: data[i].file['url']

						 
					};
					
					var pubblicato = 'no';
					if (array_files[i]['pub']==1)
					{
						pubblicato = 'yes';
					}
 					var realtime;
					if(array_files[i]['realtime']==1){
						realtime='yes';
					}
					else{
						realtime='no';
					} 
				
				     var opensource;

					if(array_files[i]['opensource']==1){
						opensource='yes';
					}
					else{
						opensource='no';
					} 
					
					var data1 = array_files[i]['date'];
					var data2 = data1.replace(':','-');
					var data3 = data2.replace(':','-');
					var data4 = data3.replace(' ','-');
					var datacompleta = data1.split(" ");
					var ora = datacompleta[1].split(":");
					var us= array_files[i]['utente'];
					var file_n =array_files[i]['name'];
					var file1 = file_n.split(".");
					var stato =array_files[i]['status'];
					//
					var substr1 = stato.search("Awaiting");
					var substr = stato.search("OK");
					if (array_files[i]['type']=='ETL' || array_files[i]['type']=='JAVA' || array_files[i]['type']=='R'){
						var href = 'uploads/'+us+'/'+data4+'/'+file1[0]+'/'+array_files[i]['name'];
						var button_create_jt ='<button type="button" class="editDashBtn crea_jt" data-target="#data-modal" data-toggle="modal" >NEW</button>';
						var button_show_jt ='<button type="button" class="viewDashBtn mostra_jt" data-target="#show_jobType" data-toggle="modal" >VIEW</button>';
					}
					else{
						var href = 'uploads/'+us+'/'+data4+'/'+array_files[i]['name'];
						var button_create_jt ="";
						var button_show_jt ="";
					}
					
			
					if (array_files[i]['pub']==0)
					{
						if (substr == 0)
						{
							$("#elenco_files").append('<tr><td>'+i+'</td><td class="file_id" value="'+array_files[i]['id']+'">'+array_files[i]['id']+'</td><td><a href="'+href+'" class="file_archive_link" download>'+array_files[i]['name']+'</a></td><td class="username_td">'+array_files[i]['utente']+'</td><td>'+array_files[i]['date']+'</td><td>'+array_files[i]['desc']+'</td><td>'+array_files[i]['type']+'</td><td class="status" >'+array_files[i]['status']+'</td><td class="actions">'+button_create_jt+'</td><td class="show_button">'+button_show_jt+'</td><td><button type="button" class="editDashBtn modify_jt" data-target="#data-modal3" data-toggle="modal">EDIT</button></td><td><button type="button" value="0" data-toggle="modal" class="pubDashBtn pubblicato" data-target="#publish">No</button></td><td><button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal">DEL</button></td></tr>');	<!-- agg --!>	
	
						}
						else
						{
							if (substr1 == 0)
							{
								$("#elenco_files").append('<tr><td>'+i+'</td><td class="file_id">'+array_files[i]['id']+'</td><td><a href="'+href+'" download class="file_archive_link">'+array_files[i]['name']+'</a></td><td class="username_td">'+array_files[i]['utente']+'</td><td>'+array_files[i]['date']+'</td><td>'+array_files[i]['desc']+'</td><td>'+array_files[i]['type']+'</td><td> no </td><td class="status" >'+array_files[i]['status']+'	</td><td class="actions"></td><td class="show_button"></td><td class="actions"></td><td></td><td><button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal">DEL</button></td></tr>');	
							}
							else
							{
								$("#elenco_files").append('<tr><td>'+i+'</td><td class="file_id">'+array_files[i]['id']+'</td><td><a href="'+href+'" download class="file_archive_link">'+array_files[i]['name']+'</a></td><td class="username_td">'+array_files[i]['utente']+'</td><td>'+array_files[i]['date']+'</td><td>'+array_files[i]['desc']+'</td><td>'+array_files[i]['type']+'</td><td> no </td><td class="status" >'+array_files[i]['status']+' </td><td class="actions"></td><td class="show_button"></td><td></td><td><button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal">DEL</button></td></tr>');			
							}
						}

					}
					else {
						if (substr == 0)
						{
							
							$("#elenco_files").append('<tr><td>'+i+'</td><td class="file_id">'+array_files[i]['id']+'</td><td><a href="'+href+'" download class="file_archive_link">'+array_files[i]['name']+'</a></td><td class="username_td">'+array_files[i]['utente']+'</td><td>'+array_files[i]['date']+'</td><td>'+array_files[i]['desc']+'</td><td>'+array_files[i]['type']+'</td><td class="status" >'+array_files[i]['status']+'</td><td class="actions">'+button_create_jt+'</td><td class="show_button">'+button_show_jt+'</td><td><button type="button" class="editDashBtn modify_jt" data-target="#data-modal3" data-toggle="modal">EDIT</button></td><td><button type="button" value="1" data-toggle="modal" class="pubDashBtn pubblicato" data-target="#publish">Yes</button></td><td><button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal">DEL</button></td></tr>');	<!-- agg --!>	
						}
						else
						{
							if (substr1 == 0)
							{
								$("#elenco_files").append('<tr><td>'+i+'</td><td class="file_id">'+array_files[i]['id']+'</td><td><a href="'+href+'" download class="file_archive_link">'+array_files[i]['name']+'</a></td><td class="username_td">'+array_files[i]['utente']+'</td><td>'+array_files[i]['date']+'</td><td>'+array_files[i]['desc']+'</td><td>'+array_files[i]['type']+'</td><td> no </td><td class="status" >'+array_files[i]['status']+'	</td><td class="actions"></td><td class="show_button"></td><td class="actions"></td><td></td><td><button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal">DEL</button></td></tr>');	
							}
							else
							{
								
									$("#elenco_files").append('<tr><td>'+i+'</td><td class="file_id">'+array_files[i]['id']+'</td><td><a  href="'+href+'" download class="file_archive_link">'+array_files[i]['name']+'</a></td><td class="username_td">'+array_files[i]['utente']+'</td><td>'+array_files[i]['date']+'</td><td>'+array_files[i]['desc']+'</td><td>'+array_files[i]['type']+'</td><td> no </td><td class="status" >'+array_files[i]['status']+'</td><td class="actions"></td><td></td><td class="show_button"></td><td><button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal">DEL</button></td></tr>');
							}
						}
					}
				}
				$('#elenco_files').dynatable(
							{
							features: {
									paginate: true,
									sort: true,
									pushState: true,
									search: true,
									recordCount: true,
									perPageSelect: true
								},
						  table: {
							defaultColumnIdStyle: 'camelCase',
							columns: null,
							headRowSelector: 'thead tr', // or e.g. tr:first-child
							bodyRowSelector: 'tbody tr',
							headRowClass: null
						  },
						inputs: {
							queries: null,
							sorts: null,
							multisort: ['ctrlKey', 'shiftKey', 'metaKey'],
							page: null,
							queryEvent: 'blur change',
							recordCountTarget: null,
							recordCountPlacement: 'after',
							paginationLinkTarget: null,
							paginationLinkPlacement: 'after',
							paginationPrev: 'Previous',
							paginationNext: 'Next',
							paginationGap: [1,2,2,1],
							searchTarget: null,
							searchPlacement: 'before',
							perPageTarget: null,
							perPagePlacement: 'before',
							perPageText: 'Show: ',
							recordCountText: 'Showing ',
							processingText: 'Processing...'
						  },
						dataset: {
							ajax: false,
							ajaxUrl: null,
							ajaxCache: null,
							ajaxOnLoad: false,
							ajaxMethod: 'GET',
							ajaxDataType: 'json',
							totalRecordCount: null,
							queries: null,
							queryRecordCount: null,
							page: null,
							perPageDefault: 10,
							perPageOptions: [5, 10,20,50,100],
							sorts: null,
							sortsKeys: null,
							sortTypes: {},
							records: null
						  }
					}
				
				);
				}
		});
		
		
		//Quando si clicca sul modal di eliminazione
		$(document).on('click','.delete_file',function(){
			var ind = $(this).parent().parent().first().children().html();
			var valore_el = array_files[ind]['id'];
			$("#id_file_del").val(valore_el);
			//ricostruisci path
			var data1 = array_files[ind]['date'];
			var data2 = data1.replace(':','-');
			var data3 = data2.replace(':','-');
			var data4 = data3.replace(' ','-');
			var us= array_files[ind]['utente'];
			var file_n =array_files[ind]['name'];
			var file1 = file_n.split(".");
			//
			var type = array_files[ind]['type'];
			if (type=='ETL'|| type=='R'|| type =='Java'){
			var path_file = 'uploads/'+us+'/'+data4+'/'+file1[0]+'/'+array_files[ind]['name'];
			}
			else{
				var path_file = 'uploads/'+us+'/'+data4+'/'+array_files[ind]['name'];
			}
			
			$("#path_del").val(path_file);
		});
		
		
		//Status
		$(document).on('click','.pubblicato',function(){
			var ind = $(this).parent().parent().first().children().html();
			var valore_el = array_files[ind]['id'];
			var valolre_pub=array_files[ind]['pub'];
			var valNat_pub=array_files[ind]['category'];
			var subNat_pub=array_files[ind]['resource'];
			var acc_pub=array_files[ind]['protocol'];
			var form_pub=array_files[ind]['format'];
			var lic_pub=array_files[ind]['licence'];
			var tip_pub=array_files[ind]['type'];
			$("#id_pub").val(valore_el);
			$("#status_pub").val(valolre_pub);
			$("#nature_pub").val(valNat_pub);
			$("#subnature_pub").val(subNat_pub);
			$("#access_pub").val(acc_pub);
			$("#format_pub").val(form_pub);
			$("#lic_pub").val(lic_pub);
			$("#tip_pub").val(tip_pub);
		});
		
		
		//QUANDO SI CLICCA SUL MODAL del process type
		$(document).on('click','.crea_jt',function(){
			var ind = $(this).parent().parent().first().children().html();
			//console.log(ind);
			$("#titolo_proc").text("CREATE NEW PROCESS TYPE FROM FILE: " + array_files[ind]['name']);
			$("#file_zip").val(array_files[ind]['name']);
			$("#data_zip").val(array_files[ind]['date']);
			$("#tipo_zip").val(array_files[ind]['type']);
			$("#user_zip").val(array_files[ind]['utente']);
			$("#id").val(array_files[ind]['id']);

			var currentdate = new Date(); 
				var datetime = 
				 currentdate.getFullYear() +"-"+ 
				+ (currentdate.getMonth()+1)  + "-" 
				+ currentdate.getDate() + " "  
				+ currentdate.getHours() + ":"  
				+ currentdate.getMinutes() + ":" 
				+ currentdate.getSeconds();
			console.log(datetime);

				var data2 = array_files[ind]['date'];
				var data3 = data2.replace(':','-');
				var data4 = data3.replace(':','-');
				var data5 = data4.replace(' ','-');
			if (array_files[ind]['type'] === 'ETL'){

				$("#type").val('Process Executor');
				$("#url").val('');
				var typeJt = $("#type").val();
				console.log("Tipo Job Type: " + typeJt);
				var urlJt = $("#url").val();
				console.log("Url:	" + urlJt);

				var us1= array_files[ind]['utente'];
				var file_n1 =array_files[ind]['name'];
				var file2 = file_n1.split(".");	
				$("#path").val("<?=$java_path; ?>");


				var pathJt = $("#path").val();
				console.log("path:	" + pathJt);
				$("#file_position").val(file_position);
			}else if (array_files[ind]['type'] === 'R'){
				$("#type").val('Process Executor');
				$("#path").val("<?=$r_path; ?>");
				$("#url").val('');
				//
				var typeJt = $("#type").val();
				var pathJt = $("#path").val();
				console.log("path:	" + pathJt);

				var us1= array_files[ind]['utente'];
				var file_n1 =array_files[ind]['name'];
				var file2 = file_n1.split(".");

				var file_position = us1+'/'+data5+'/'+file2[0];
				$("#file_position").val(file_position);
			}else if(array_files[ind]['type'] === 'Java'){

				$("#type").val('Process Executor');
				$("#path").val("<?=$java_path; ?>");
				var typeJt = $("#type").val();
				var pathJt = $("#path").val();
				var us1= array_files[ind]['utente'];
				var file_n1 =array_files[ind]['name'];
				var file2 = file_n1.split(".");

				var file_position = us1+'/'+data5+'/'+file2[0];

			}
		});
		
		
		
		
		
		//QUANDO SI CLICCA SUL MODAL di publish    agg da lavorare su questo
		$(document).on('click','.publish_jt',function(){
			var ind = $(this).parent().parent().first().children().html();

			
			$("#titolo_proc2").text("PUBLISH PROCESS FROM FILE: " + array_files[ind]['name'] );
			$("#file_zip2").val(array_files[ind]['name']);
			$("#category2").val(array_files[ind]['category']);
			$("#resource").val(array_files[ind]['resource']);
			$("#format2").val(array_files[ind]['format']);
			$("#licence2").val(array_files[ind]['licence']);
			$("#protocol2").val(array_files[ind]['protocol']);
			$("#desc2").val(array_files[ind]['desc']);
			$("#periodic").val(array_files[ind]['periodic']);
			$("#realtime").val(array_files[ind]['realtime']);
			$("#data_zip2").val(array_files[ind]['date']);
			$("#tipo_zip2").val(array_files[ind]['type']);
			$("#id2").val(array_files[ind]['id']);
			//console.log();
			

			var currentdate = new Date(); 
				var datetime = 
				 currentdate.getFullYear() +"-"+ 
				+ (currentdate.getMonth()+1)  + "-" 
				+ currentdate.getDate() + " "  
				+ currentdate.getHours() + ":"  
				+ currentdate.getMinutes() + ":" 
				+ currentdate.getSeconds();
			//console.log(datetime);

				var data2 = array_files[ind]['date'];
				var data3 = data2.replace(':','-');
				var data4 = data3.replace(':','-');
				var data5 = data4.replace(' ','-');
				
				
				
			var type = array_files[ind]['type'];
	
			if(type=='ETL'|| type=='R'||type=='Java'){
				document.getElementById("help2").required=false;
				$("#div-help2").hide();
				document.getElementById("url2").required=false;
				$("#div-url2").hide();
				document.getElementById("opensource2").required=false;
				$("#div-opensource2").hide();
				document.getElementById("method2").required=false;		
				$("#div-method2").hide();
				document.getElementById("os2").required=false;
				$("#div-os2").hide();
				document.getElementById("format2").required=true;
				$("#div-format2").show();
				document.getElementById("periodic2").required=true;
				$("#div-periodic2").show();
				document.getElementById("realtime2").required=true;
				$("#div-realtime2").show();
				document.getElementById("protocol2").required=true;
				$("#div-protocol2").show();
			}
			// TODO finire i vari tipi di file dove nascondere la roba
			if(type=='DevDash'|| type =='AMMA'|| type=='ResDash'){
				document.getElementById("url2").required=false;
				$("#div-url2").hide();		
				document.getElementById("format2").required=false;
				$("#div-format2").show();
				document.getElementById("periodic2").required=false;
				$("#div-periodic2").hide();
				document.getElementById("realtime2").required=false;
				$("#div-realtime2").hide();
				document.getElementById("opensource2").required=false;
				$("#div-opensource2").hide();
				document.getElementById("method2").required=false;		
				$("#div-method2").hide();
				document.getElementById("help2").required=false;
				$("#div-help2").hide();
				document.getElementById("url2").required=false;
				$("#div-url2").hide();
				document.getElementById("os2").required=false;
				$("#div-os2").hide();
				document.getElementById("protocol2").required=false;
				$("#div-protocol2").hide();
			}
			if(type=='IoTApp' || type=='IoTBlocks'){
				document.getElementById("protocol2").required=false;
				$("#div-protocol2").hide();
				document.getElementById("format2").required=false;
				$("#div-format2").show();
				document.getElementById("periodic2").required=false;
				$("#div-periodic2").hide();
				document.getElementById("realtime2").required=false;
				$("#div-realtime2").hide();
				document.getElementById("url2").required=false;
				$("#div-url2").hide();
				document.getElementById("method2").required=false;		
				$("#div-method2").hide();
				document.getElementById("opensource2").required=false;
				$("#div-opensource2").hide();
				document.getElementById("os2").required=false;
				$("#div-os2").hide();

			}
			if(type=='MicroService'){
				document.getElementById("url2").required=true;
				$("#div-url2").show();
				document.getElementById("method2").required=true;
				$("#div-method2").show();
				document.getElementById("protocol2").required=false;
				$("#div-protocol2").hide();
				document.getElementById("format2").required=false;
				$("#div-format2").hide();
				document.getElementById("periodic2").required=true;
				$("#div-periodic2").show();
				document.getElementById("realtime2").required=true;
				$("#div-realtime2").show();
				document.getElementById("opensource2").required=false;
				$("#div-opensource2").hide();
				document.getElementById("os2").required=false;
				$("#div-os2").hide();
				document.getElementById("help2").required=true;
				$("#div-help2").show();
			}
			if(type=='Mobile App'){
				document.getElementById("url2").required=true;
				$("#div-url2").show();
				document.getElementById("method2").required=true;
				$("#div-method2").show();
				document.getElementById("protocol2").required=true;
				$("#div-protocol2").show();
				document.getElementById("format2").required=false;
				$("#div-format2").hide();
				document.getElementById("periodic2").required=false;
				$("#div-periodic2").hide();
				document.getElementById("realtime2").required=false;
				$("#div-realtime2").hide();
				document.getElementById("opensource2").required=true;
				$("#div-opensource2").show();
				document.getElementById("os2").required=true;
				$("#div-os2").show();
			}

		});
		
		
				
		//QUANDO SI CLICCA SUL MODAL di modify    agg da lavorare su questo
		$(document).on('click','.modify_jt',function(){
			var ind = $(this).parent().parent().first().children().html();
			var id_mic = array_files[ind]['id'];
			//alert(ind);
			var type = array_files[ind]['type'];
			if(type=='ETL'|| type=='R'||type=='Java'){
				$("#div-help3").hide();
				$("#div-url3").hide();
				$("#div-opensource3").hide();
				$("#div-method3").hide();
				$("#div-os3").hide();
				$("#div-format3").show();
				$("#div-periodic3").show();
				$("#div-realtime3").show();
				$("#div-protocol3").show();
			}
			// TODO finire i vari tipi di file dove nascondere la roba
			if(type=='DevDash'|| type =='AMMA'|| type=='ResDash'){
				$("#div-url3").hide();
				$("#div-format3").show();
				$("#div-periodic3").hide();
				$("#div-realtime3").hide();
				$("#div-help3").hide();
				$("#div-url3").hide();
				$("#div-opensource3").hide();
				$("#div-method3").hide();
				$("#div-os3").hide();
				$("#div-protocol3").hide();
			}
			if(type=='IoTBlocks' || type=='IoTApp'){
				$("#div-protocol3").show();
				$("#div-periodic3").hide();
				$("#div-realtime3").hide();
				$("#div-url3").hide();
				$("#div-format3").show();
				$("#div-help3").hide();
				$("#div-url3").hide();
				$("#div-method3").hide();
				$("#div-os3").hide();
				$("#div-opensource3").hide();
			}
			if(type=='MicroService'){
				if (nascondi == 'hide'){
					window.location.replace("MicroService_editor.php?microServ="+id_mic+"&showFrame=false");
				}else{
					window.location.replace("MicroService_editor.php?microServ="+id_mic);
				}
				$("#div-url3").show();
				$("#div-method3").hide();
				$("#div-protocol3").hide();
				$("#div-os3").hide();
				$("#div-opensource3").hide();
				$("#div-periodic3").hide();
				$("#div-realtime3").hide();	
				$("#div-format3").hide();
				$("#div-help3").show();
			}
			if(type=='Mobile App'){
				$("#div-url3").show();
				$("#div-method3").show();
				$("#div-protocol3").show();
				$("#div-os3").show();
				$("#div-opensource3").show();
				$("#div-periodic3").hide();
				$("#div-realtime3").hide();	
				$("#div-help3").show();
			}
			
			$("#titolo_proc3").text("MODIFY METADATA OF PROCESS FROM FILE: " + array_files[ind]['name'] );
			$("#file_zip3").val(array_files[ind]['name']);
			//$('#category3 option[value="'+array_files[ind]['category']+'"]').attr('selected','selected');
			//$('#resource3 option[value="'+array_files[ind]['resource']+'"]').attr('selected','selected');
			$('#category3').val(array_files[ind]['category']);
			$('#resource3').val(array_files[ind]['resource']);
			//
			$("#format3").val(array_files[ind]['format']);
			

			$("#licence3").val(array_files[ind]['licence']);
			$("#protocol3").val(array_files[ind]['protocol']);
			$("#desc3").val(array_files[ind]['desc']);
			$("#help3").val(array_files[ind]['help']);
			$("#url3").val(array_files[ind]['url']);

			$("#method3").val(array_files[ind]['method']);
			$("#os3").val(array_files[ind]['OS']);
			
			$("select[name=opensource3]").val(array_files[ind]['opensource']);
			$("select[name=realtime3]").val(array_files[ind]['realtime']);
			$("select[name=periodic3]").val(array_files[ind]['periodic']);		
			$("#tipo_zip3").val(array_files[ind]['type']);
			$("#id3").val(array_files[ind]['id']);

		});
		
		
		
		
		
		//Chiudi elenco jobtypes
		$(document).on('click','#chiudi_job',function(){
			$("#elenco tr td").remove();
			$("#show_jobType").modal('hide');
		});
		
		//MOSTRA JOB TYPE
		$(document).on('click','.mostra_jt',function(){
			var array_process = [];
			var ind = $(this).parent().parent().first().children().html();
			$("#mostra_elenco").text("Process Model list:  "+array_files[ind]['name']);
			$.ajax({
				url: "getdata.php",
				data: {action: "get_process_jt", jobt:array_files[ind]['name'], jobtId:array_files[ind]['id']},
				type: "GET",
				async: true,
				dataType: 'json',
				success: function (data) {
					for (var i = 0; i < data.length; i++)
					{
						array_process[i] = {
							id: data[i].jobtype['id'],
							name: data[i].jobtype['job_type_name'],
							group: data[i].jobtype['job_type_group'],
							type: data[i].jobtype['type']
					};
					$("#elenco").append('<tr><td class="id_job_row">'+array_process[i]['id']+'</td><td class="name_job_row">'+array_process[i]['name']+'</td><td class="group_job_row">'+array_process[i]['group']+'</td><td>'+array_process[i]['type']+'</td></tr>');
				}}
		});
		});
		
		//
		//CREAZIONE JSON
		var id1 = 0;
		var id2 = 0;
		var id3 = 0;
		var id4 = 0;
		//NextJob
		$(document).on('click','#icon_nextjob',function(){
			id3++;
			$("#AddNextJob").append('<div class="input-group"><span>IF RESULT </span><select name="par_next_job_cond'+id3+'" id="par_next_job_cond'+id3+'"><option value="=">==</option><option value="!=">!=</option><option value="<"><</option><option value=">">></option><option value="<="><=</option><option value=">=">>=</option></select><span>THEN TRIGGER</span><input id="next_job_result'+id3+'" type="text" name="next_job_result'+id3+'" placeholder="result"><input id="next_job_n'+id3+'" type="text" name="next_job_n'+id3+'" placeholder="job name"><input id="next_job_g'+id3+'" type="text" name="next_job_g'+id3+'" placeholder="job group"></div>');
		});
		//Job Constraint
		$(document).on('click','#icon_jobConstraint',function(){
			id2++;
			var select_cons='<select id="job_cons_k'+id2+'" name="job_cons_k'+id2+'" hidden readonly class="selectpicker form-control"><option value="OSArchitecture">OS Architecture</option><option value="AvailableProcessors">Available Processors</option><option value="OSName">OS Name</option><option value="SystemLoadAverage">System Load Average</option><option value="OSVersion">OS Version</option><option value="CommittedVirtualMemorySize">Committed Virtual Memory Size</option><option value="FreePhysicalMemorySize">Free Physical Memory Size</option><option value="FreeSwapSpaceSize">Free Swap Space Size</option><option value="ProcessCpuLoad">Process Cpu Load</option><option value="ProcessCpuTime">Process Cpu Time</option><option value="SystemCpuLoad">System Cpu Load</option><option value="TotalPhysicalMemorySize">Total Physical Memory Size</option><option value="TotalSwapSpaceSize">Total Swap Space Size</option><option value="IPAddress">Ip Address</option></select>';
			$("#AddJobConstraint").append('<div class="input-group">'+select_cons+'<select id="job_cons_cond'+id2+'" name="job_cons_cond'+id2+'"><option value="=">==</option><option value="!=">!=</option><option value="<"><</option><option value=">">></option><option value="<="><=</option><option value=">=">>=</option></select><input id="job_cons_v'+id2+'" type="text" name="job_cons_v'+id2+'" placeholder="value"></div>');
			//
			//$("#AddJobConstraint").append();
			
			
		});
		//clik sul plus///
		
		//
		//CREARE UN AJAX che crei i json
		$(document).on('click','#Crea_job_type',function(){
			
			$('#datamapText').val(dataM);
			//
			var NextJ = "";
			if (id3>0){
				NextJ += '{';
			for (var i = 1; i<id3+1; i++){
				console.log(i);
				NextJ += '{';
				NextJ += '"condition":"' + $('#par_next_job_cond'+i).val() + '",';
				NextJ += '"result":"' + $('#next_job_result'+i).val() + '",';
				NextJ += '"name":"' + $('#next_job_n'+i).val() + '"';
				NextJ += '"group":"' + $('#next_job_g'+i).val() + '"';
				NextJ += '}';
				if (i < id3){
					NextJ += ',';
				};
			  }
				NextJ +='}';
			  console.log(NextJ);
			  alert(NextJ);
			}
			$('#NextJobText').val(NextJ);
			//
			
			//
			var jobC ="";
			if (id2>0){
			for (var i = 1; i<id2+1; i++){
				console.log(i);
				jobC += '{';
				jobC += '"key:"' + $('#job_cons_k'+i).val() + '",';
				jobC += '"condition:"' + $('#job_cons_cond'+i).val()+'",';
				jobC += '"value:"' + $('#job_cons_v'+i).val() + '"';
				jobC += '}';
				if(i < id2){
					jobC +=',';
				};
			  }
			  console.log(jobC);
			  alert(jobC);
			}
			$('#ProcParText').val(jobC);
			//
		});
		//FINE CREAZIONE JSON
				}	
		
	});
</script>
</body>
</html>