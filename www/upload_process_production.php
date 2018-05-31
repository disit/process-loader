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
	$default_title = "Process Loader: Process: Test vs Production";
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
			<!--<h2>Upload Process in production Scheduler<span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></h2>-->
			
                <table id="elenco_processi" class="table table-striped table-bordered">
						<thead class="dashboardsTableHeader">
				        <tr>
						<th hidden>N</th>
						<th>ip</th>
                        <th hidden>File</th>
                        <th hidden>Id Processo</th>
                        <th>Process Name</th>
						<!--<th>Process Type</th>-->
						<th hidden>File</th>
                        <th>Group</th>
                        <th>Type</th>
                        <th>Creation Date</th>
                        <th>Status</th>
						<th>Load on production scheduler</th>
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
        <div class="modal-body" style="background-color: white">
		<p>In this page a ToolAdmin user can upload a new process in a 'production' scheduler application using based on the data of an yet existing process in execution in a 'test' scheduler.</p>
		<p>From the command 'Load test process' the user can select an ip address of a 'production' scheduler from a list and create a new process using ther parameters of the test process.</p>
        </div>
        <div class="modal-footer" style="background-color: white">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<!-- Fine dei Modal-->
<!-- Modal -->
<div class="modal fade" id="load_test" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	<form name="form_login" method="post" action="load_process.php" id="load_process">
      <div class="modal-header" style="background-color: white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="modal_title">Load Process</h4>
        </div>
      <div class="modal-body" style="background-color: white">
	  <div class="input-group" style="display:none;"><span class="input-group-addon" >Process Id: </span>
						<input id="process_id" type="text" class="form-control" name="process_id" readonly hidden>
		</div><br />
	  <div class="input-group"><span class="input-group-addon">Process Name: </span>
						<input id="process_name" type="text" class="form-control" name="process_name" readonly>
	</div><br />
	   <div class="input-group"><span class="input-group-addon">Current Scheduler: </span>
						<input id="current_scheduler" type="text" class="form-control" name="current_scheduler" readonly>
	</div><br />
	<div class="input-group"><span class="input-group-addon">Select Production Node Scheduler: </span>
						<select name="name_sched" id="name_sched" class="selectpicker form-control">
						</select>			
	</div><br />
	
      </div>
      <div class="modal-footer" style="background-color: white">
        <button type="button" class="btn cancelBtn" id="close_menu" data-dismiss="modal">Cancel</button>
        <!--<button type="button" class="btn btn-primary" onclick="load_process()">Confirm</button>-->
		<button type="submit" class="btn confirmBtn">Confirm</button>
      </div>
	  </form>
    </div>
  </div>
</div>
<!-- Fine modal creazione processo -->
<script type='text/javascript'>
//Copiare e incollare la parte js
	var ip = "";
	//
	 var messaggio = "<?=$message; ?>";
	///SPOSTA PROCESSO///
	//
	//
    $(document).ready(function () {
		//$('#elenco_processi').dynatable();
		if(messaggio =='ok'){
			alert("New process has been created!");
		}else if(messaggio == 'error'){
			alert("Error during process creation!");
		}else if(messaggio == 'no_exist'){
			alert("Selected process not found!");
		}else if(messaggio == 'error_file_upload'){
			alert("Error during upload file to Production node");
		}
		///
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
		//////
			var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#load_process').attr('action','load_process.php?showFrame=false');
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
		
		
		////
		utente_attivo=$("#nome_ut").text();
		
	if (utente_attivo=='Login'){
		$(document).empty();
		alert("You have to log in!");
		}
	var role_active = $("#role_att").text();
	if (role_active == 'ToolAdmin'){
		$('#sc_mng').show();
	}
	////
			var array_process = new Array();
            $.ajax({
                url: "getdata.php",
                data: {action: "test_process"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
					console.log(data);
                    for (var i = 0; i < data.length; i++)
                    {
                        array_process[i] = {	
							id: data[i].test['id'],
                            name: data[i].test['process_name'],
                            group: data[i].test['process_group'],
                            date: data[i].test['creation_date'],
							status: data[i].test['status'],
							type: data[i].test['type'],
							ip: data[i].test['ip']
                    }
						if (array_process[i]['status'] == "COMPLETE"){
						$("#elenco_processi").append('<tr><td>'+i+'</td><td class="ip_job_row">'+array_process[i]['ip']+'</td><td class="name_job_row">'+array_process[i]['name']+'</td><td class="id_job_row" hidden>'+array_process[i]['id']+'</td><td class="group_job_row">'+array_process[i]['group']+'</td><td>'+array_process[i]['type']+'</td><td>'+array_process[i]['date']+'</td><td>'+array_process[i]['status']+'</td><td><button type="button" id="load_menu" class="editDashBtn load_menu" data-toggle="modal" data-target="#load_test">EDIT</button></td></tr>');
						}
					}
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
							perPageDefault: 10,
							perPageOptions: [10,20,50,100],
							sorts: null,
							sortsKeys: null,
							sortTypes: {},
							records: null
						  }
					});
		    	}
             });                 
	////
	$(document).on('click','#load_menu',function(){
		//var ind = ($(this).parent().parent().index())-1;
		//var ind = ($(this).parent().parent().index());
		var ind = $(this).parent().parent().first().children().html();
		$("#current_scheduler").val(array_process[ind]['ip']);
		$("#process_name").val(array_process[ind]['name']);
		$("#modal_title").text("Load process "+array_process[ind]['name']+"	in a Production Scheduler");
		$("#process_id").val(array_process[ind]['id']);
		//id_job_row
	//Macchine di produzione
	//
	var array_production = new Array();
            $.ajax({
				url: "getdata.php",
                data: {action: "get_production_sched"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
					console.log("Successo"+ data);
					for (var j = 0; j < data.length; j++)
                    {
						array_production[j]={
							name: data[j].production['name'],
							id: data[j].production['id'],
							address: data[j].production['address'],
							repository: data[j].production['repository'],
							type: data[j].production['type'],
							description: data[j].production['description']
						}
						$("#name_sched").append('<option value="'+array_production[j]['name']+'">'+array_production[j]['name']+'</option>');
					};
				}
			});
	//
		});
	//	
		$(document).on('click','#close_menu',function(){
			$("#prod_sched").empty();
		});
	//
	});
</script>
</body>
</html>