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
	$default_title = "Process Loader: Schedulers";
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
		<?php //include('functionalities.php'); ?>
        
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
			<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1)'>
			<!--<h2>Scheduler Manager Node <span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></h2>-->
			<div style="margin-left:45px; margin-bottom:20px; margin-top:20px;">
           <button type="button" class="btn btn-warning"  data-toggle="modal" id="add_sch_btn" data-target="#newScheduler"><span class="glyphicon glyphicon-plus" data-toggle="modal" data-target="#info-modal"></span>Add new Scheduler</button>
</button>
<br />
</div>
			<table id="elenco_sched" class="table table-striped table-bordered">
					<thead class="dashboardsTableHeader">
                    <tr>
                        <th data-dynatable-column="title_header" hidden>Id Scheduler node</th>
						<th data-dynatable-column="name">Name</th>
                        <th data-dynatable-column="address">Ip Address</th>  
						<th data-dynatable-column="path">Repository path</th>
						<th data-dynatable-column="type">Type</th>
						<th data-dynatable-column="description">Description</th>
                    </tr>
					</thead>
                    <tbody></tbody>
                </table>
			</div>
		</div>
		</div>
<!-- Elenco dei Modal -->
<div class="modal fade" id="info-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" style="background-color: white">
        <div class="modal-header" style="background-color: white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Information</h4>
        </div>
        <div class="modal-body" style="background-color: white">
		The access to this page is reserved for Tool Admin Users and it's used for the external scheduler node application address' management.
		  <br /><br />
		  For the creation of the processes it's necessary specify the ip_address of the scheduler application where the process will be executed.
        </div>
        <div class="modal-footer" style="background-color: white">
          <button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      
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
									<button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
                                    <button class="btn confirmBtn">Confirm</button>
                                    </form>
					
				  <div class="login-help">
                                    <!--  <a href="registrazione.php">Register</a>-->
				  </div>
				</div>
			</div>
		  </div>
<!-- fine Login-->

<!-- Elenco dei Job Type-->
<!-- -->
<!-- Trigger the modal with a button -->
<!-- Modal -->
<div id="newScheduler" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
	<form name="new_sched" method="post" id="new_sched" action="new_scheduler.php">
      <div class="modal-header" style="background-color: white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Scheduler</h4>
		
      </div>
      <div class="modal-body" style="background-color: white">
			<div class="input-group"><span class="input-group-addon">Name: *</span>
				<input id="name" type="text" class="form-control" name="name" required="required">
				</div><br />
			<div class="input-group"><span class="input-group-addon">Address: *</span>
				<input id="address" type="text" class="form-control" name="address" required="required">
				</div><br />
			<div class="input-group"><span class="input-group-addon">Description: </span>
				<input id="desc" type="text" class="form-control" name="desc">
				</div><br />
			<div class="input-group"><span class="input-group-addon">Repository Name: *</span>
				<input id="repository" type="text" class="form-control" name="repository" required="required" value="<?=$server_share; ?>">
				</div><br />
			<div class="input-group"><span class="input-group-addon">Process Path: *</span>
			<input id="path" type="text" class="form-control" name="path">
			</div><br />
			<div class="input-group"><span class="input-group-addon">DDI HOME: *</span>
			<input id="home" type="text" class="form-control" name="home">
			</div><br />
			<div class="input-group"><span class="input-group-addon">data integration path: *</span>
			<input id="data_integration_path" type="text" class="form-control" name="data_integration_path">
			</div><br />
			<div class="input-group"><span class="input-group-addon">Type: *</span>
				<!--<input id="descrizione" type="text" class="form-control" name="descrizione" required="required">-->
				<select name="type" id="type" class="selectpicker form-control">
				<option value="test">Test</option>
				<option value="production">Production</option>
				</select>
				</div><br />
      </div>
      <div class="modal-footer" style="background-color: white">
        <button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
		<input type="submit" name="add_scheduler" id="add_scheduler" class="btn confirmBtn" value="Confirm">
      </div>
	  </form>
    </div>

  </div>
</div>
<!-- Fine dei Modal-->
<script type='text/javascript'>
    $(document).ready(function () {
		//$('#elenco_sched').dynatable();
		utente_attivo=$("#nome_ut").text();
		
	if (utente_attivo=='Login'){
		$(document).empty();
		alert("You have to log in!");
		}
	var role_active = $("#role_att").text();
	if (role_active == 'ToolAdmin'){
		$('#sc_mng').show();
	}
	
	//
	var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#new_sched').attr('action','new_scheduler.php?showFrame=false');
		}
	//
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
	
	var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
	
	
	//
	var array_schedulers = new Array();
				$.ajax({
                url: "getdata.php",
                data: {action: "get_schedulers"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
					for (var i = 0; i < data.length; i++)
                    {
						array_schedulers[i] = {
							id: data[i].schedulers['id'],
							name: data[i].schedulers['name'],
                            address: data[i].schedulers['address'],
                            repository: data[i].schedulers['repository'],
                            type: data[i].schedulers['type'],
                            description: data[i].schedulers['description']
						};
					$("#elenco_sched").append('<tr><td hidden>'+array_schedulers[i]['id']+'</td><td>'+array_schedulers[i]['name']+'</td><td>'+array_schedulers[i]['address']+'</td><td>'+array_schedulers[i]['repository']+'</td><td>'+array_schedulers[i]['type']+'</td><td>'+array_schedulers[i]['description']+'</td></tr>');
					}
					$('#elenco_sched').dynatable(
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
	
	});
</script>
</body>
</html>
</body>