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

include("config.php"); // includere la connessione al database
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

$message ="";
if (isset ($_REQUEST['message'])){
	$message=$_REQUEST['message'];
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
	$default_title = "Process Loader: Process Models";
}else{
	$default_title = "";
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
			<!--Time Picker -->
			 <script type="text/javascript" src="js/serializejson.js"></script>
 <!-- DATE PICKER -->
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
			<div class="row" >
			<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:45px'>
			<!--<h2>Job Type<span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></h2>-->
                <table id="elenco_job" class="table table-striped table-bordered" ">
						<thead class="dashboardsTableHeader">
				        <tr>
						<th hidden>N</th>
                        <th hidden>File</th>
                        <th>Process Type Name</th>
                        <th>Group</th>
                        <th>Type</th>
                        <th>Creation Date</th>
						<th>New instance</th>
						<th>Show instances</th>
                    </tr>
					</thead>
                </table>
            </div>
			</div>
		</div>
		
<!-- Elenco dei Modal -->
<div class="modal fade" id="info-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Information</h4>
        </div>
        <div class="modal-body">
		This page contains a list of every Process type created by the current user. For each element in the list is available create a new process based on the parameters of the process type.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
<div class="modal fade bd-example-modal-lg" id="show_jobs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header" style="background-color: white">
	<h4 class="modal-title" id="mostra_elenco">Process Instances List</h4>
</div>
<div id="contenuto_elenco">
<table id="elenco" class="table table-striped table-bordered">
		<tr>
                        <th hidden>Id Process Model</th>
                        <th>Process Name</th>
                        <th>Group</th>
                        <th>Type</th>
        </tr>
</table>
</div>
<div class="panel-footer">
<div class="form-group">
<input type="button" name="chiudi_job" id="chiudi_job" class="btn cancelBtn" value="Cancel" data-dismiss="modal">
</div>
</div>
</div>
</div>
</div>
<!--Fine Mostra Job-->
  
<!--Modal Creazione di un nuovo processo-->
<!-- Modal -->
<!-- LoginModal-->
<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    	  <div class="modal-dialog">
              
				<div class="loginmodal-container">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 h4 class="modal-title">Log In</h4><br>
				  <form name="form_login" method="post" action="login.php" accept-charset="UTF-8">
                                    <p>Username</p><input type="text" type="text" name="username" placeholder="Username"></p>
                                    <p>Password <input type="password" name="password" placeholder="Password"></p>
                                    <button class="btn confirmBtn">Confirm</button>
                                    </form>					
				  <div class="login-help">
                                    <!--  <a href="registrazione.php">Register</a>-->
				  </div>
				</div>
			</div>
		  </div>
<!-- fine Login-->
<div class="modal fade" id="data-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    	  <div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header" style="background-color: white">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
			   <h4 class="modal-title" id="titolo_proc"></h4>
			</div>
			<!-- -->
			<form name="param_jobs" method="post" id="param_jobs" action="caricaJob2.php" accept-charset="UTF-8">
     	<!--<form name="param_jobs" method="post" action="carica_processo.php">-->
		<div class="form-parametri" id="Process_0">
		<ul class="nav nav-tabs">
		<li><a data-toggle="tab" href="#job_parameter" class="active">Process Parameters</a></li>
		<li><a data-toggle="tab" href="#job_trigger">Trigger</a></li>
		<li><a data-toggle="tab" href="#job_advanced">Advanced Parameters</a></li>
		</ul>
		<div class="panel-body"><div class="alert alert-success" hidden>New Process has been created correctly.</div>
		<!--<div class="alert alert-danger" hidden> Errore! Il processo non è stato caricato correttamente.</div>-->
		<div class="alert alert-danger" hidden> Error! The process has not been loaded correctly.</div>
		<div class="tab-content">
		<div id="job_parameter" class="tab-pane fade in active">
				<div class="input-group" style="display:none;" readonly><span class="input-group-addon" hidden>id: </span><input id="id" type="text" class="form-control" name="id" hidden readonly></div>
				<div class="input-group" style="display:none;" readonly><span class="input-group-addon" hidden>File: </span><input id="file" type="text" class="form-control" name="file" hidden readonly></div>
				<div class="input-group" style="display:none;" readonly><span class="input-group-addon" hidden>next_job: </span><input id="next_job" type="text" class="form-control" name="next_job" hidden readonly></div>
				<div class="input-group" style="display:none;" readonly><span class="input-group-addon" hidden>job_cons: </span><input id="job_cons" type="text" class="form-control" name="job_cons" hidden readonly></div>
				
				<div class="input-group"><span class="input-group-addon">Name: *</span><input id="nome" type="text" class="form-control" name="nome" required="required"></div>
				<br />
				<div class="input-group"><span class="input-group-addon">Description: *</span>
				<input id="descrizione" type="text" class="form-control" name="descrizione" required="required">
				</div><br />
				<div class="input-group"><span class="input-group-addon">Group: *</span>
				<input id="gruppo" type="text" class="form-control" name="gruppo" required="required">
				</div><br />
				<div class="input-group"><span class="input-group-addon">Scheduler Address: *</span>
				<select id="sc_address" type="text" class="form-control" name="sc_address" required="required"></select>
				</div><br />				
				<div class="input-group" style="display:none;"><span class="input-group-addon">Url: </span>
				<input id="url" type="text" class="form-control" name="url" readonly>
				</div>
				<div class="input-group" style="display:none;"><span class="input-group-addon">Path: </span>
				<input id="path" type="text" class="form-control" name="path" readonly></div>
				<div class="input-group" style="display:none;"><span class="input-group-addon">type: </span>
				<input id="type_j" type="text" class="form-control" name="type_j"  readonly></div>
				<!--
				-->
				<!-- -->
				<div class="input-group" style="display:none;"><span class="input-group-addon" >Process Parameters: </span>
				<input id="ProcParameters" type="text" class="form-control" name="ProcParameters"  readonly>
				</div>
				<!-- -->
				<!-- file position -->
				<div class="input-group" style="display:none;"><span class="input-group-addon" >File position: </span>
				<input id="file_position" type="text" class="form-control" name="file_position"  readonly>
				</div>
				<!-- -->
				<div class="input-group"><span class="input-group-addon">E-mail: </span>
				<input id="email" type="text" class="form-control" name="email"><br />
				</div><br />
				<div class="input-group"><span class="input-group-addon">Time Out: </span>
				<input id="time_out" type="text" class="form-control" name="time_out">
				</div><br />
				<div class="input-group">
					<span class="checkbox-inline"><input id="conc" type="checkbox" name="conc" 	value="1">non Concurrent</span>
				</div><br />
				<div class="input-group">
					<span class="checkbox-inline"><input id="store" type="checkbox" name="store" value="1">Store Durably</span>
				</div><br />
				<div class="input-group">
					<span class="checkbox-inline"><input id="recovery" type="checkbox" name="recovery" value="0">Request Recovery</span>
				</div><br />
				
			</div>
			<div id="job_trigger" class="tab-pane fade">
						<div class="input-group"><span class="input-group-addon">Trigger Name: *</span><input id="nome_trig" type="text" required="required" class="form-control" name="nome_trig"></div><br />
						<div class="input-group"><span class="input-group-addon">Trigger Description: *</span><input id="descrizione_trig" type="text" required="required" class="form-control" name="descrizione_trig"></div><br />
						<div class="input-group"><span class="input-group-addon">Trigger Group: *</span><input id="gruppo_trig" type="text" class="form-control" required="required" name="gruppo_trig"></div><br />	
						<div class="input-group"><span class="input-group-addon">Repeat Count: </span><input id="repeat_trig" type="text" class="form-control" name="repeat_trig" value="0"></div><br />
						<div class="input-group"><span class="input-group-addon">Interval: </span><input id="interval_trig" type="text" class="form-control" name="interval_trig" value="0"></div><br />	
						<div class="input-group"><span class="input-group-addon">Start Time: </span><input id="start" type="text" class="form-control datepicker" name="start" value="0000-00-00 00:00:00"></div><br />
						<div class="input-group"><span class="input-group-addon">End Time: </span><input id="end" type="text" class="form-control datepicker" name="end" value="0000-00-00 00:00:00"></div><br />
					    <div class="input-group"><span class="input-group-addon">Priority: </span><input id="priority" type="text" class="form-control" name="priority" value="0"></div><br />
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
					<!--Advanced parameters-->
					<div id="job_advanced"  class="tab-pane fade">	
						<ul class="list-group">
						
			<li class="list-group-item" >
				<div id="AddNextJob">
				<b>Add Next Job </b><span class="glyphicon glyphicon-plus" id="icon_nextjob"></span>
				<input type="text" id="NextJobText" name="NextJobText" hidden readonly></input>
				</div>
			</li>
			
				<li class="list-group-item">	
				<div id="AddJobConstraint">
				<b>Add Job Constraint </b><span class="glyphicon glyphicon-plus" id="icon_jobConstraint"></span>
				<!--<input type="text" id="JobConstraintText" name="JobConstraintText" hidden readonly></input>
				<select id="JobConstraintText" name="JobConstraintText" hidden readonly class="selectpicker form-control"><option value="OSArchitecture">OS Architecture</option><option value="AvailableProcessors">Available Processors</option><option value="OSName">OS Name</option><option value="SystemLoadAverage">System Load Average</option><option value="OSVersion">OS Version</option><option value="CommittedVirtualMemorySize">Committed Virtual Memory Size</option><option value="FreePhysicalMemorySize">Free Physical Memory Size</option><option value="FreeSwapSpaceSize">Free Swap Space Size</option><option value="ProcessCpuLoad">Process Cpu Load</option><option value="ProcessCpuTime">Process Cpu Time</option><option value="SystemCpuLoad">System Cpu Load</option><option value="TotalPhysicalMemorySize">Total Physical Memory Size</option><option value="TotalSwapSpaceSize">Total Swap Space Size</option><option value="IPAddress">Ip Address</option></select>
				-->
				</div>
			</li>
		</ul>
		</div>
					
			</div>
		</div>

	
		<div class="panel-footer">
		<div class="form-group">
		<!--
		<input type="submit" name="carica_processo" id="carica_processo" class="btn btn-primary" value="Conferma">
		-->
		<input type="button" name="chiudi_job" class="btn cancelBtn" value="Cancel" data-dismiss="modal">
		<input type="submit" name="carica_processo" id="carica_processo" class="btn confirmBtn" value="Confirm">
		</div>
		</div>
		</div>
		</form>
		</div>
	</div>
	</div>
<!-- Fine modal creazione processo -->
<!-- Modal -->
<!-- Fine dei Modal-->
<script type='text/javascript' >
$(function(){
			var dateNow = new Date();
			$('.datepicker').datetimepicker({
				format: 'yyyy-mm-dd hh:ii:ss',
				language: 'it',
				current: dateNow,
				defaultDate: dateNow,
				startDate: dateNow,
				use24hours: true
			});
		});

//generare id
function generateUUID() {
      var d = new Date().getTime();
       var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
         var r = (d + Math.random()*16)%16 | 0;
         d = Math.floor(d/16);
             return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
    return uuid;
};

$(document).ready(function () {
	//$('#elenco_job').dynatable();
		var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#param_jobs').attr('action','caricaJob2.php?showFrame=false');
		}
	
		var error_message = "<?=$message; ?>";
		if (error_message == "error"){
			alert("ERROR DURING PROCESS CREATION!");
		}
		if (error_message == "ok"){
			alert("PROCESS CREATED!");
		}
		//	
	//controllo login
	utente_attivo=$("#nome_ut").text();
		
	if (utente_attivo=='Login'){
		$(document).empty();
		alert("You have to log in!");
		}
	//
	var role_active = $("#role_att").text();
	if (role_active == 'ToolAdmin'){
		$('#sc_mng').show();
	}
	//
	
	//redirect//
	var role="<?=$role_att; ?>";
	
		if (role == ""){
			$(document).empty();
			//window.alert("You need to log in to access to this page!");
			if(window.self !== window.top){
			window.location.href = 'page.php?showFrame=false&pageTitle=Process%20Loader:%20View%20Resources';	
			}
			else{
			window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
			}
		}
	
	//
	////
	var titolo_default = "<?=$default_title; ?>";
		if (titolo_default != ""){
			$('#headerTitleCnt').text(titolo_default);
		}
	///
	$(document).on('click','.close',function(){
		$('#cont_jc').remove();
		$('#cont_nj').remove();
	});
	
	//Caricamneto Schedulers
	var array_schedulers = new Array();
				$.ajax({
                url: "getdata.php",
                data: {action: "get_schedulers_test"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
					for (var i = 0; i < data.length; i++)
                    {
						array_schedulers[i] = {
							id: data[i].schedulers['id'],
                            address: data[i].schedulers['address'],
                            repository: data[i].schedulers['repository'],
                            type: data[i].schedulers['type'],
                            description: data[i].schedulers['description'],
							name: data[i].schedulers['name']
						};
					$("#sc_address").append('<option value="'+array_schedulers[i]['address']+'">'+array_schedulers[i]['name']+'</option>');
					}
					
				}
			});
	//
    var array_jobType = new Array();
			$.ajax({
                url: "getdata.php",
                data: {action: "get_types"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
                    for (var i = 0; i < data.length; i++)
                    {
                        array_jobType[i] = {
							id: data[i].jobtype['id'],
                            name: data[i].jobtype['job_type_name'],
                            group: data[i].jobtype['job_type_group'],
                            date: data[i].jobtype['creation_date'],
                            file: data[i].jobtype['file'],
                            type_j: data[i].jobtype['type'],
							//inserire tutti gli atri dati.
							file_name: data[i].jobtype['file_name'],
							start: data[i].jobtype['start'],
							end: data[i].jobtype['end'],
							interval: data[i].jobtype['interval'],
							job_type_description: data[i].jobtype['job_type_description'],
							url: data[i].jobtype['url'],
							path: data[i].jobtype['path'],
							mail: data[i].jobtype['mail'],
							store: data[i].jobtype['store'],
							conc: data[i].jobtype['conc'],
							recovery: data[i].jobtype['recovery'],
							trig_name: data[i].jobtype['trig_name'],
							trig_group: data[i].jobtype['trig_group'],
							trig_desc: data[i].jobtype['trig_desc'],
							priority: data[i].jobtype['priority'],
							repeat: data[i].jobtype['repeat'],
							process_param: data[i].jobtype['process_param'],
							misfire: data[i].jobtype['misfire'],
							time_out: data[i].jobtype['time_out'],
							datamap: data[i].jobtype['datamap'],
							next_job: data[i].jobtype['next_job'],
							job_cons: data[i].jobtype['job_cons'],
							file_position: data[i].jobtype['file_position']
							};
					$("#elenco_job").append('<tr><td>'+i+'</td><td hidden>'+array_jobType[i]['file']+'</td><td>'+array_jobType[i]['name']+'</td><td>'+array_jobType[i]['group']+'</td><td>'+array_jobType[i]['type_j']+'</td><td>'+array_jobType[i]['date']+'</td><td><button type="button" class="editDashBtn crea_j" data-target="#data-modal" data-toggle="modal">NEW</button></td><td><button class="viewDashBtn mostra_j" data-target="#show_jobs" data-toggle="modal" type="button">VIEW</button></td></tr>');
					}
					$('#elenco_job').dynatable(
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
							perPageOptions: [10,20,50,100],
							sorts: null,
							sortsKeys: null,
							sortTypes: {},
							records: null
						  }
					}
					
					);
                }
                });
				
				
		//CLICK sul pulsante crea job:
		$(document).on('click','.crea_j',function(){
			//Dati di default del JOB_TYPE
			//var ind = ($(this).parent().parent().index())-1;
			//var ind = ($(this).parent().parent().index());
			var ind = $(this).parent().parent().first().children().html();
			//$("#titolo_proc").text("Parametri processo:  " + array_jobType[ind]['file']);
			$("#titolo_proc").text("process parameters from:  " + array_jobType[ind]['name']);
			$("#id").val(array_jobType[ind]['id']);
			$("#nome").val(array_jobType[ind]['name']);
			$("#descrizione").val(array_jobType[ind]['job_type_description']);
			$("#gruppo").val(array_jobType[ind]['group']);
			$("#url").val(array_jobType[ind]['url']);
			$("#path").val(array_jobType[ind]['path']);
			$("#type_j").val(array_jobType[ind]['type_j']);
			$("#email").val(array_jobType[ind]['mail']);
			$("#time_out").val(array_jobType[ind]['time_out']);
			$("#nome_trig").val(array_jobType[ind]['trig_name']);
			$("#descrizione_trig").val(array_jobType[ind]['trig_desc']);
			$("#gruppo_trig").val(array_jobType[ind]['trig_group']);
			$("#repeat_trig").val(array_jobType[ind]['repeat']);
			$("#start").val(array_jobType[ind]['start']);
			$("#end").val(array_jobType[ind]['end']);
			$("#priority").val(array_jobType[ind]['priority']);
			$("#misfire").val(array_jobType[ind]['misfire']);
			$("#interval_trig").val(array_jobType[ind]['interval']);
			$("#file").val(array_jobType[ind]['file']);
			$("#url").val(array_jobType[ind]['url']);
			$("#path").val(array_jobType[ind]['path']);
			$("#ProcParameters").val(array_jobType[ind]['process_param']);
			$("#file_position").val(array_jobType[ind]['file_position']);
			//
			$("#store").val(array_jobType[ind]['store']);
			$("#recovery").val(array_jobType[ind]['recovery']);
			//
			$("#next_job").val(array_jobType[ind]['next_job']);
			$("#job_cons").val(array_jobType[ind]['job_cons']);
			//
			
			///CHECKBOX///
					var conc_v=$("#conc").val();
					var store_v=$("#store").val();
					var recovery_v=$("#recovery").val();
					//
					if (conc_v==1){
						$("#conc").attr('checked', true);
					}else{
						$("#conc").attr('checked', false);
					}
					if (store_v==1){
						$("#store").attr('checked', true);
					}else{
						$("#store").attr('checked', false);
					}
					if(recovery_v==1){
						$("#recovery").attr('checked', true);
					}else{
						$("#recovery").attr('checked', false);
					}
		
			//
			//JobCONS NON vuoto
					var j_cons=$("#job_cons").val();
					console.log("JOB CONSTAINT = "+j_cons);
					if (j_cons !=""){
						var j_cons_dati = JSON.parse(j_cons);
						console.log("J_cons_dati: "+j_cons_dati);
						var cons_select = '<select id="job_cons_k1" name="job_cons_k1" hidden readonly class="selectpicker form-control"><option value="OSArchitecture">OS Architecture</option><option value="AvailableProcessors">Available Processors</option><option value="OSName">OS Name</option><option value="SystemLoadAverage">System Load Average</option><option value="OSVersion">OS Version</option><option value="CommittedVirtualMemorySize">Committed Virtual Memory Size</option><option value="FreePhysicalMemorySize">Free Physical Memory Size</option><option value="FreeSwapSpaceSize">Free Swap Space Size</option><option value="ProcessCpuLoad">Process Cpu Load</option><option value="ProcessCpuTime">Process Cpu Time</option><option value="SystemCpuLoad">System Cpu Load</option><option value="TotalPhysicalMemorySize">Total Physical Memory Size</option><option value="TotalSwapSpaceSize">Total Swap Space Size</option><option value="IPAddress">Ip Address</option></select>';
						$("#AddJobConstraint").append('<div class="input-group" id="cont_jc">'+cons_select+'<select id="job_cons_cond1" name="job_cons_cond1"><option value="=">==</option><option value="!=">!=</option><option value="<"><</option><option value=">">></option><option value="<="><=</option><option value=">=">>=</option></select><input id="job_cons_v1" type="text" name="job_cons_v1" placeholder="value"></div>');
						$("#job_cons_cond1").val(j_cons_dati['condition']);
						$("#job_cons_v1").val(j_cons_dati['value']);
						$("#job_cons_k1").val(j_cons_dati['key']);
					}
					
					//next_job non vuoto
					var j_next=$("#next_job").val();
					console.log("NEXT JOB?=  "+j_next);
					//var J_next = $("#job_cons").val();
					if (j_next !=""){
						var j_next_dati = JSON.parse(j_next);
						console.log("j_next_dati: "+j_next_dati);
						$("#AddNextJob").append('<div class="input-group" id="cont_nj"><span>IF RESULT </span><select name="par_next_job_cond1" id="par_next_job_cond1"><option value="=">==</option><option value="!=">!=</option><option value="<"><</option><option value=">">></option><option value="<="><=</option><option value=">=">>=</option></select><span>THEN TRIGGER</span><input id="next_job_result1" type="text" name="next_job_result1" placeholder="result"><input id="next_job_n1" type="text" name="next_job_n1" placeholder="job name"><input id="next_job_g1" type="text" name="next_job_g1" placeholder="job group"></div>');
						$("#par_next_job_cond1").val(j_next_dati['condition']);
						$("#next_job_result1").val(j_next_dati['result']);
						$("#next_job_n1").val(j_next_dati['name']);
						$("#next_job_g1").val(j_next_dati['group']);
					}
			//
		});
		
		//MOSTRA JOB
		$(document).on('click','.mostra_j',function(){
			var array_process = [];
			//var ind = ($(this).parent().parent().index())-1;
			//var ind = ($(this).parent().parent().index());
			var ind = $(this).parent().parent().first().children().html();
			$("#mostra_elenco").text("Show Jobs:  " + array_jobType[ind]['name']);
			$.ajax({
                url: "getdata.php",
                data: {action: "get_process_j", job:array_jobType[ind]['id']},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
                    for (var i = 0; i < data.length; i++)
                    {
						array_process[i] = {
							id: data[i].process['id'],
                            name: data[i].process['process_name'],
                            group: data[i].process['process_group'],
                            date: data[i].process['creation_date'],
                            file: data[i].process['file'],
							status: data[i].process['status'],
                            type: data[i].process['type']
					};
					$("#elenco").append('<tr><td class="id_job_row" hidden>'+array_process[i]['id']+'</td><td class="name_job_row">'+array_process[i]['name']+'</td><td class="group_job_row">'+array_process[i]['group']+'</td><td>'+array_process[i]['type']+'</td></tr>');
				}}
			//alert("ELENCO DEI JOB COLLEGATI!");
		});
		});
		
		//
		$(document).on('click','#chiudi_job',function(){
			$("#elenco tr td").remove();
			$("#show_jobs").modal('hide');
		});
		//Quando si clicca su "conferma"
		//API RESQUEST DA INVIARE A DISCES PER LA CREAZIONE DEL JOB E DEL Trigger
			//Dati da inviare
			
			$(document).on('click','#carica_processo',function(){
					//variabili NOME e Gruppo
					var id_jt = $("#id").val();
					var file_jt = $("#file").val();
					//console.log(id_jt);
					var nome_job = $("#nome").val();
					var gruppo_job = $("#gruppo").val();
					var myArr;
					var url_data = $("#url").val();
					//
					var interv=$("#interval_trig").val();
					var path_p = $("#path").val();
					var description =$("#descrizione").val();
					var priority =$("#priority").val();
					var dataform = $("#start").val();
					var endform = $("#end").val();
					var myDate2 = new Date(endform);
					var resultEnd = myDate2.getTime();
					//var dataEp = dataform;
					var myDate = new Date(dataform);
					var resultDate = myDate.getTime();
					var misfire = $("#misfire").val();
					var mail=$("#email").val();
					
					//var checked
					var conc_j;
					if ($("#conc").val()=='1'){
						conc_j = 'true';
						$("#conc").is( ":checked" );
					} else {
						conc_j = 'false';
					}
					
					var store_j;
					if ($("#store").val()=='1'){
						store_j = 'true';
						$("#store").is( ":checked" );
					} else {
						store_j = 'false';
					}
					
					var recovery_j;
					if ($("#recovery").val()=='1'){
						recovery_j = 'true';
						$("#recovery").is( ":checked" );
					} else {
						recovery_j = 'false';
					}
					//
					//valori Trigger
					var n_t =$("#nome_trig").val();
					var d_t =$("#descrizione_trig").val();
					var g_t= $("#gruppo_trig").val();
					var ris =0;
					var risTrig=0;
					//
					var parametriProcesso = $("#ProcParameters").val();
					parametriProcesso02= JSON.stringify(parametriProcesso);
					
					var njcond="";
					var njres="";
					var	njname="";
					var njgroup="";
					if ($('#par_next_job_cond1').length){
					var njcond=$('#par_next_job_cond1').val();
					}
					if ($('#next_job_result1').length){
					var njres=$('#next_job_result1').val();
					}
					if ($('#next_job_n1').length){
					var	njname=$('#next_job_n1').val();
					}
					if ($('#next_job_g1').length){
					var njgroup=$('#next_job_g1').val();
					}
					//
					var jconsKey="";
					var jconsCond="";
					var jconsValue="";
					//
					if($('#job_cons_k').length){
						jconsKey=$('#job_cons_k').val();
					}
					if($('#job_cons_v').length){
						jconsValue=$('#job_cons_v').val();
					}
					if($('#job_cons_cond').length){
						jconsCond=$('#job_cons_cond').val();
					}
					
					//CREAZIONE JSON
					///
					var inputJSON = $("#param_jobs").serializeJSON();
						
					
					
					
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
			
			var cons_select = '<select id="job_cons_k'+id2+'" name="job_cons_k'+id2+'" hidden readonly class="selectpicker form-control"><option value="OSArchitecture">OS Architecture</option><option value="AvailableProcessors">Available Processors</option><option value="OSName">OS Name</option><option value="SystemLoadAverage">System Load Average</option><option value="OSVersion">OS Version</option><option value="CommittedVirtualMemorySize">Committed Virtual Memory Size</option><option value="FreePhysicalMemorySize">Free Physical Memory Size</option><option value="FreeSwapSpaceSize">Free Swap Space Size</option><option value="ProcessCpuLoad">Process Cpu Load</option><option value="ProcessCpuTime">Process Cpu Time</option><option value="SystemCpuLoad">System Cpu Load</option><option value="TotalPhysicalMemorySize">Total Physical Memory Size</option><option value="TotalSwapSpaceSize">Total Swap Space Size</option><option value="IPAddress">Ip Address</option></select>';
			$("#AddJobConstraint").append('<div class="input-group">'+cons_select+'<select id="job_cons_cond'+id2+'" name="job_cons_cond'+id2+'"><option value="=">==</option><option value="!=">!=</option><option value="<"><</option><option value=">">></option><option value="<="><=</option><option value=">=">>=</option></select><input id="job_cons_v'+id2+'" type="text" name="job_cons_v'+id2+'" placeholder="value"></div>');
		});
		//
		//CREARE UN AJAX che crei i json
		$(document).on('click','#carica_processo',function(){
			//
			var NextJ = "";
			if (id3>0){
				//NextJ += '{';
			for (var i = 1; i<id3+1; i++){
				console.log(i);
				NextJ += '{';
				NextJ += '"condition":"' + $('#par_next_job_cond'+i).val() + '",';
				NextJ += '"result":"' + $('#next_job_result'+i).val() + '",';
				NextJ += '"name":"' + $('#next_job_n'+i).val() + '",';
				NextJ += '"group":"' + $('#next_job_g'+i).val() + '"';
				NextJ += '}';
				if (i < id3){
					NextJ += ',';
				};
			  }
				//NextJ +='}';
			  console.log(NextJ);
			}
			$('#NextJobText').val(NextJ);
			//
			var ProcP ="";
			if (id4>0){
				ProcP += '{';
			for (var i = 1; i<id4+1; i++){
				console.log(i);
				ProcP += '{';
				ProcP += '"key:"' + $('#process_par_k-'+i).val() + '",';
				ProcP += '"value:"' + $('#process_par_j'+i).val() + '"';
				ProcP += '}';
				if (i < id4){
					ProcP += ',';
				};
			  }
				ProcP +='}';
			  console.log(ProcP);
			}	
			//
		});
		//FINE CREAZIONE JSON
	
					
});
</script>
</body>
</html>