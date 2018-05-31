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
	$default_title = "Process Loader: Process Execution Archive";
}else{
	$default_title = "";
}
?>

<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dashboard Management System</title>
		
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
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php 
						include "mobMainMenu.php" 
						?></div>
                    </div>
                    <div class="row" >
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:45px'>
					<!--
                        <div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1)'>
                            <div class="row mainContentRow" id="dashboardsListTableRow">
                                <div class="col-xs-12 mainContentRowDesc">List</div>
						</div>
                    </div>
					-->			

              <!--  <h2>Process Activity Archive	<span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></h2> -->
			<!--	
			<div class="panel panel-default">
			<div class="input-group">
				<span class="input-group-addon">Search For Name</span>
				<input type="text" id="myInput" onkeyup="myFunction()" class="form-control" placeholder="process name...">
			
			</div>
			<br />
			<div class="input-group">
				<span class="input-group-addon">Search For Date</span>
				<input type="text" id="myInputDate" onkeyup="myFunctionDate()" class="form-control" placeholder="YYYY-MM-DD">
			
			</div>
			</div>
			-->
			
                <table id="storico" class="table table-striped table-bordered">
					<thead class="dashboardsTableHeader">
                    <tr>
                        <th>Activity </th>
                        <th>Date</th>
						<th>Operation</th>
                        <th>Process Name</th> 
						<th>Process Model</th>
						<th>File</th>
						<th>Group</th>
                    </tr>
					</thead>
                </table>


                        
                </div>
				</div>
				
		</div>
		</div>
	<!-- Modal -->
	<!-- Modal -->
  <div class="modal fade" id="info-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Information</h4>
        </div>
        <div class="modal-body">
		This page contains a archive of every activity 
performed by users. Every time an user (both managers o ToolAdmins) creates a new process, removes o modify an existing process status, this activity is saved in the archive.
Users can only view information about theri own processes.
<br><br>
It's available a Search box to filter the list content according to process name.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<!-- Fine modal creazione processo -->
<!-- FIne Button-->
	
    </div>
    
  </div>
</div>
<!-- Button-->
<!-- Trigger the modal with a button -->
<!-- FIne Button-->
<!-- Fine modal creazione processo -->
<!-- Fine modal creazione processo -->
<script type='text/javascript'>
function myFunction() {
	// Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("myInput");
	filter = input.value.toUpperCase();
	table = document.getElementById("storico");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[3];
		//td = tr[i].getElementsByTagName("td")[0];
		if (td) {
		if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
		tr[i].style.display = "";
		} else {
		tr[i].style.display = "none";
		}
    } 
  }
}

function myFunctionDate() {
	// Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("myInputDate");
	filter = input.value.toUpperCase();
	table = document.getElementById("storico");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[1];
		//td = tr[i].getElementsByTagName("td")[0];
		if (td) {
		if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
		tr[i].style.display = "";
		} else {
		tr[i].style.display = "none";
		}
    } 
  }
}



    $(document).ready(function () {
		//$('#storico').dynatable();
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

       var array_act = new Array();
		$.ajax({
			url: "getdata.php",
                data: {action: "get_activity"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
					//console.log(data);
                    for (var i = 0; i < data.length; i++)
                    {
						 array_act[i] = {
						 id: data[i].activity['id'],
                         process_id: data[i].activity['process_id'],
						 name: data[i].activity['name'],
						 group: data[i].activity['group'],
                         desAct: data[i].activity['desAct'],
						 date: data[i].activity['date'],
						 job_type_name: data[i].activity['job_type_name'],
						 file: data[i].activity['file']
					};
					//alert(array_act);
					$("#storico").append('<tr><td>'+array_act[i]['id']+'</td><td>'+array_act[i]['date']+'</td><td>'+array_act[i]['desAct']+'</td><td>'+array_act[i]['name']+'</td><td>'+array_act[i]['job_type_name']+'</td><td>'+array_act[i]['file']+'</td><td>'+array_act[i]['group']+'</td></tr>');
					}
					$('#storico').dynatable(
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
							headRowSelector: 'thead tr', // or e.g. tr:first-child
							bodyRowSelector: 'tbody tr',
						  },
						inputs: {
							
							queryEvent: 'blur change',
							recordCountPlacement: 'after',
							paginationLinkPlacement: 'after',
							paginationPrev: 'Previous',
							paginationNext: 'Next',
							paginationGap: [1,2,2,1],
							searchPlacement: 'before',
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
							perPageDefault: 10,
							perPageOptions: [10,20,50,100],

							sortTypes: {},
						  }
					}
					);
					$('#dynatable-pagination-links-storico').show();
				}
		});
		
	});
</script>
</body>
</html>