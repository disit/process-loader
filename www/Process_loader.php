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
	$default_title = "Process Loader: Processes In Execution";
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
			<!--<h2>Process In Execution<span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></h2> -->
			
						<table id="elenco_processi" class="table table-striped table-bordered" >
					<thead class="dashboardsTableHeader">
					<tr>
						<th hidden>N</th>
						<th>Ip scheduler</th>
						<th class="user_creator" hidden>Creator</th>
                        <th hidden>File</th>
                        <th hidden>Id Processo</th>
                        <th>Process Name</th>
						<th>Process Model</th>
						<th>File</th>
                        <th>Group</th>
                        <th>Type</th>
                        <th>Creation Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
					</thead>
			   </table>
            </div>
			<!-- Elenco dei Modal -->
<!-- Modal -->
  <div class="modal fade" id="detail_Modal" role="dialog">
    <div class="modal-dialog">
  <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Show Process Execution Details</h4>
        </div>
        <div class="modal-body" style="background-color: white">
			<table id="mostra_dettagli" class="table table-striped table-bordered">
						<tr>
						<th>Trigger Name</th>
						<th hidden>Trigger Group</th>
						<th hidden>Calendar Name</th>
						<th>Description</th>
						<th>End Time</th>
						<th hidden>Final Fire Time</th>
						<th>Misfire Instruction</th>
						<th>Next Fire Time</th>
						<th>Previous Fire Time</th>
						<th hidden>Priority</th>
						<th>Start Time</th>
						<th hidden>May Fire Again</th>
						</tr>
			</table>
        </div>
        <div class="modal-footer" style="background-color: white">
          <button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
        </div>
      </div>
	  </div>
      </div>
    </div>
 <!-- fine details-->
<!-- FIne Button-->
                    
    </div>   
  </div>
</div>
<!-- Nuovo Processo -->
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body" style="background-color: white">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer" style="background-color: white">
          <button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
<!-- Fine caricamento Nuovo Processo -->

<!-- Modal Avvia Processo -->
  <div class="modal fade" id="myModalRunning" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="operation_title"></h4>
        </div>
		<form  method="post" action="process_action.php" id="process_action">
        <div class="modal-body" style="background-color: white">
          <p id="operation_body"></p>
		<input type="text" class="operation_run" name="operation_run" value ="" style="display: none;"></input>
		<input type="text" id="ip_run" name="ip_run" value ="" style="display: none;"></input>
		<input type="text" id="id_run" name="id_run" value ="" style="display: none;"></input>
		<input type="text" id="n_run" name="n_run" value ="" style="display: none;"></input>
		<input type="text" id="g_run" name="g_run" value ="" style="display: none;"></input>
        </div>
		<div class="modal-footer" style="background-color: white">
			<button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
          <!--<button type="submit" class="btn btn-primary" id="run_req" name="run_req" onclick="runF()">Confirm</button>-->
		  <button type="submit" class="btn confirmBtn" id="run_req" name="run_req">Confirm</button>
		</div>
		</form>
      </div>
    </div>
  </div> 
<!--Dettagli -->
  <div class="modal fade" id="myModaldetails" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: white">
          <button type="button" class="close close_details" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Process Details</h4>
        </div>
		<!--<form  method="post" action="detail_job.php">-->
		<form method="post" action="show_detail_job.php">
        <div class="modal-body" style="background-color: white">
          <p>Are you sure you wish to obtain process execution details?</p>
        </div>
		<div id="tabella_dettagli" style="background-color: white">
		</div>
		<input type="text" class="operation_run" name="operation_run" value ="" style="display: none;"></input>
		<input type="text" id="ip_det" name="ip_det" value ="" style="display: none;"></input>
		<input type="text" id="id_det" name="id_det" value ="" style="display: none;"></input>
		<input type="text"id="n_det" name="n_det" value ="" style="display: none;"></input>
		<input type="text" id="g_det" name="g_det" value ="" style="display: none;"></input>
		<input type="text" id="ris_det" name="ris_det" value ="" style="display: none;"></input>
		<div id="table_content" hidden>
		<table id="list_details_jobs" class="table table-striped table-bordered">
		<tr>
		<th>Id</th>
		<th>Date</th>
		<th>Status</th>
		</tr>
		</table>
		</div>
        <div class="modal-footer" style="background-color: white">
          <button type="button" class="btn cancelBtn close_details" data-dismiss="modal">Cancel</button>
		  <button type="button" id="conferma_dettagli" class="btn confirmBtn">Confirm</button>
          <!--<button type="submit" class="btn btn-primary" id="detail_req" name="detail_req" action="detail_req" onclick="detailF()">Confirm</button>-->
        </div>
		</form>
      </div>
      
    </div>
  </div>
  
  <!--DIALOG DETTAGLI-->
<div class="modal fade" id="detail_table_modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color: white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Detail table</h4>
        </div>
        <div class="modal-body" id="corpo dettagli" style="background-color: white">
				<!--
					 <table id="table_detailed">
                </table>
				-->
        </div>
        <div class="modal-footer" style="background-color: white">
          <button type="button" class="btn btn-cancelBtn" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      

  <div class="modal fade" id="info-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Information</h4>
        </div>
        <div class="modal-body">
          <p>This page lists the running processes that you have created. For each of these processes, you can use these options:</p>
		  <p>To show the details about the execution of the process in the scheduler, click on this icon: <span class="glyphicon glyphicon-th-list"></span></p>
		  <p>To Start the execution of the process in the scheduler, click on this icon: <span class="glyphicon glyphicon-play"></span></p>
		  <p>To Interrupt the execution of the process in the scheduler, click on this icon: <span class="glyphicon glyphicon-pause"></span></p>
		  <p>To Remove process in the scheduler, click on this icon: <span class="glyphicon glyphicon-remove"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
			</div>
		</div>


    </div>
  </div>
<!---->
	
	<input type="hidden" id="detail_response" value='<?php 
											if(isset($_REQUEST['detail_response']))
											{
												echo $_REQUEST['detail_response'];
										    }
										  ?>' />
<script type='text/javascript'>
	<!-- FUNZIONI API -->
	
	var archive_type ="";

	<!-- FINE FUNZIONI API-->
    $(document).ready(function () {
		//$('#elenco_processi').dynatable();
		var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#process_action').attr('action','process_action.php?showFrame=false');
		}
		//
		
		//redirect
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
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
		//
		var url_att=window.location.href;
		var url = new URL(url_att);
		var ris_action = url.searchParams.get("action");
	//
	var user_active = $("#nome_ut").text();
	var logout_active =  ris_action;
	//
	var role_active = $("#role_att").text();
	/*
	if (role_active == 'ToolAdmin'){
		$('#sc_mng').show();
		$('.user_creator').show();
	}
	*/
	if ((user_active == "Login") && (logout_active !="out")){
		//alert ("Unauthenticated user: please click on " + ip + " link!");
		$("#menu_lat").hide();
		$("#elenco_processi").hide();
		$("#menu_lat_public_for_all").show();
		$("#menu_lat_public").hide();
		$("#scritta").hide();
		$("#msg_no_login").show();
	}
	
	if((user_active == "Login") && (logout_active =="out")){
		$("#menu_lat").hide();
		$("#elenco_processi").hide();
		$("#menu_lat_public_for_all").show();
		$("#menu_lat_public").hide();
		$("#scritta").hide();
		$("#msg_no_login").show();
	}
	
	
	////
	if((user_active =="Login")){
						$("#unLogged").append('<div class="panel panel-primary"><div class="panel-heading">You are not authenticated</div><div class="panel-body">The process management is reserved for authenticated users. please, click on Login link.</div></div>');

					}
		
		
		//
		var myTimerProcess = setInterval(function() { 
			$.ajax({
				url:'aggiornaProcessi.php',
				async: true,
				type: "GET",
				success: function(updatedStatuses){
					var newStatusesJson = JSON.parse(updatedStatuses);
					for(var processId in newStatusesJson)
					{
						$('#elenco_processi tr').each(function(i){
							if($(this).find("td.id_job_row").html() === processId)
							{
								$(this).find("td.status_ref").html(newStatusesJson[processId]);
							}
						});
					}

				}
			});
		}, 60000);
		
		//	
		//
		var detail_response_JSON;
		var detail_response = $("#detail_response").val(); 
		if (detail_response !="no_data" && detail_response !=""){
					detail_response_JSON = JSON.parse(detail_response);
					console.log("OGGETTO JSON: " + JSON.stringify(detail_response_JSON));
					//
					var num_det =Object.keys(detail_response_JSON).length;
					var text_dett="";
					for (var i=1;i<num_det;i++){
							$("#mostra_dettagli").append('<tr><td>'+detail_response_JSON[i][0]+'</td><td hidden>'+detail_response_JSON[i][1]+'</td><td hidden>'+detail_response_JSON[i][2]+'</td><td>'+detail_response_JSON[i][3]+'</td><td>'+detail_response_JSON[i][4]+'</td><td hidden>'+detail_response_JSON[i][5]+'</td><td>'+detail_response_JSON[i][6]+'</td><td>'+detail_response_JSON[i][7]+'</td><td>'+detail_response_JSON[i][8]+'</td><td hidden>'+detail_response_JSON[i][9]+'</td><td>'+detail_response_JSON[i][10]+'</td><td hidden>'+detail_response_JSON[i][11]+'</td></tr>');
						  }
				    	$("#detail_Modal").modal("toggle");
				    	console.log("text_dett: "+text_dett);
				    	$("#detail_response").val("");
				    	detail_response="";
					///MOSTRA DMODAL DETTAGLI.
					//
		} else if (detail_response =="no_data"){
			alert("NOT DATA FOUND!");
		}
		

    var array_process = new Array();
            $.ajax({
                url: "getdata.php",
                data: {action: "get_process"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
					console.log(data);
                    for (var i = 0; i < data.length; i++)
                    {
                        array_process[i] = {	
							id: data[i].process['id'],
                            name: data[i].process['process_name'],
                            group: data[i].process['process_group'],
                            date: data[i].process['creation_date'],
                            file: data[i].process['file'],
							status: data[i].process['status'],
                            type: data[i].process['type'],
							jb: data[i].process['jb_name'],
							ip: data[i].process['ip'],
							type_sched: data[i].process['type_sched'],
							username: data[i].process['username'],
							name_sched: data[i].process['name_sched']
						};

						$("#elenco_processi").append('<tr><td>'+i+'</td><td class="ip_job_row" ><a href="http://'+array_process[i]['ip']+'/sce/" class="file_archive_link" target="_blank">'+array_process[i]['name_sched']+'</a>	('+array_process[i]['type_sched']+')</td><td class="user_creator_td" hidden>'+array_process[i]['username']+'</td><td hidden>'+array_process[i]['file']+'</td><td class="id_job_row" hidden>'+array_process[i]['id']+'</td><td class="name_job_row">'+array_process[i]['name']+'</td><td>'+array_process[i]['jb']+'</td><td>'+array_process[i]['file']+'</td><td class="group_job_row">'+array_process[i]['group']+'</td><td>'+array_process[i]['type']+'</td><td>'+array_process[i]['date']+'</td><td class="status_ref">'+array_process[i]['status']+'</td><td><span class="fa fa-th-list view_det" data-toggle="modal" data-target="#myModaldetails" style="display: inline-block; margin: 2px;"></span><span class="fa fa-play start_pause" data-toggle="modal" data-target="#myModalRunning" style="display: inline-block; margin: 2px;"></span><span class="fa fa-pause start_pause" data-toggle="modal" data-target="#myModalRunning" style="display: inline-block; margin: 2px;"></span><span class="fa fa-remove rem_proc" data-toggle="modal" style="display: inline-block; margin: 2px;" data-target="#myModalRunning"></span></td></tr>');	

					}
				//PANNELLO VUOTO
					if((array_process.length == 0)&&(user_active!="Login")){
						$("#void").append('<div class="panel panel-primary"><div class="panel-heading">This process list is empty</div><div class="panel-body">There aren&#146t any process. You can upload a new file in <a class="file_archive_link" href="upload.php">this</a> page</div></div>');

					}
					//
					$('#elenco_processi').dynatable(
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
							perPageDefault: 5,
							perPageOptions: [5,10,20,50,100],
							sorts: null,
							sortsKeys: null,
							sortTypes: {},
							records: null
						  }
					}
					);
                }
                    
                });
		////

	
	//COMANDI DEL MODAL
	$(document).on('click','.fa-remove',function(){
		//var ind = ($(this).parent().parent().index())-1;
		//var ind = ($(this).parent().parent().index());
		var ind = $(this).parent().parent().first().children().html();
		console.log(ind);
		$(".operation_run").val("deleteJob");
		archive_type = "remove";
		$("#operation_title").text("Delete process");
		$("#operation_body").text("Are you sure you wish remove this process from Scheduler?");
		$("#ip_run").val(array_process[ind].ip);
		$("#id_run").val(array_process[ind].id);
		$("#n_run").val(array_process[ind].name);
		$("#g_run").val(array_process[ind].group);
		//
	});
	
	$(document).on('click','.fa-play',function(){
		//var ind = ($(this).parent().parent().index())-1;
		var ind = ($(this).parent().parent().index());
		console.log(ind);
		//$("#operation_run").val("triggerJob");
		$(".operation_run").val("resumeJob");
		archive_type = "run";
		$("#operation_title").text("Execute process");
		$("#operation_body").text("Are you sure you wish execute this process?");
		$("#ip_run").val(array_process[ind].ip);
		$("#id_run").val(array_process[ind].id);
		$("#n_run").val(array_process[ind].name);
		$("#g_run").val(array_process[ind].group);
	});
	
	$(document).on('click','.fa-pause',function(){
		//var ind = ($(this).parent().parent().index())-1;
		var ind = ($(this).parent().parent().index());
		console.log(ind);
		$(".operation_run").val("pauseJob");
		archive_type = "stop";
		$("#operation_title").text("Stop process execution");
		$("#operation_body").text("Are you sure you wish to interrupt this process execution?");
		$("#ip_run").val(array_process[ind].ip);
		$("#id_run").val(array_process[ind].id);
		$("#n_run").val(array_process[ind].name);
		$("#g_run").val(array_process[ind].group);
		//
	});
	
	$(document).on('click','.fa-th-list',function(){
		//var ind = ($(this).parent().parent().index())-1;
		var ind = ($(this).parent().parent().index());
		console.log(ind);
		$("#ip_det").val(array_process[ind].ip);
		$("#id_det").val(array_process[ind].id);
		$("#n_det").val(array_process[ind].name);
		$("#g_det").val(array_process[ind].group);
	});
	
	
	//AJAX_DETTAGLI
	 $(document).on('click','#conferma_dettagli',function(){
		  var array_dettagli = new Array();
		  var n_det1 = $("#n_det").val();
		  console.log(n_det1);
		  var g_det1 = $("#g_det").val();
		  console.log(g_det1);
		  var ip_det1 = $("#ip_det").val();
		$.ajax({
		url:"show_detail_job.php",
		data:{
			n_det: $("#n_det").val(),
			g_det: $("#g_det").val(),
			ip_det: $("#ip_det").val()
			},
		type: "GET",
		async: true,
		dataType:'json',
		success: function(data){
					for (var i = 0; i < data.length; i++)
                    {
						array_dettagli[i] = {	
							id: data[i].details['id'],
                            date: data[i].details['date'],
                            status: data[i].details['status']
						};
						console.log(array_dettagli[i]);
						$('#list_details_jobs').append('<tr class="riga_dettagli"><td>'+array_dettagli[i]['id']+'</td><td>'+array_dettagli[i]['date']+'</td><td>'+array_dettagli[i]['status']+'</td></tr>');
					}
					//AGGIUNGI UNA TABELLA AL MODAL
					$('#table_content').show();
					$('#conferma_dettagli').hide();
					
			
			}
		});
	});
	
	$(document).on('click','.close_details',function(){
		$('.riga_dettagli').empty();
		$('#table_content').hide();
		$('#conferma_dettagli').show();
	});
		
		
});


</script>
</body>

</html>