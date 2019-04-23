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
		<?php //include('functionalities.php'); ?>
		<!-- Datatable -->
	    <script type="text/javascript" charset="utf8" src="lib/datatables.js"></script>
		<script type="text/javascript" src="lib/dataTables.responsive.min.js"></script>
		<script type="text/javascript" src="lib/dataTables.bootstrap4.min.js"></script>
		<script type="text/javascript" src="lib/jquery.dataTables.min.js"></script>
		<link href="lib/responsive.dataTables.css" rel="stylesheet" />
        
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
			
					<!--<h2>Transfer File Property<span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></h2>-->
						<div>
				<table id="elenco_files" class="table table-striped table-bordered" style="width: 100%">
				<thead class="dashboardsTableHeader">
					<tr>
						<th>file Name</th>
						<th>Upload Date</th>
						<th>Description</th>
						<th>Username</th>
						<th>File type</th>
						<th>Published</th>   <!-- agg -->
						<th>Ownership</th>
					</tr>
				</thead>
			   </table>
		
		
			</div>
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
		You can use this page to modify property of a file. 
        </div>
        <div class="modal-footer" style="background-color: white">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<!-- Fine dei Modal-->
<div class="modal fade" id="transfer-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
	<form name="form_login" method="post" action="move_file.php" id="move_file">
	  <div class="modal-body" style="background-color: white">
		<!--<div class="loginmodal-container">-->
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Transfer process property</h4><br>

			<p>Change process property from user </p>
				<input type="text" type="text" name="file_id" id ="file_id" hidden></p>
				<input type="text" type="text" name="user_vecchio" id ="user_vecchio" readonly></p>
				<input type="text" type="text" name="creation_date" id="creation_date" hidden></p>
				<input type="text" type="text" name="file_name" id="file_name" hidden></p>
				<input type="text" type="text" name="file_type" id="file_type" hidden></p>
			<p>To user</p>
				<input type="text" type="text" name="user_nuovo" id="user_nuovo"></p>
			<!--</div>-->
	</div>
	<div class="modal-footer" style="background-color: white">
		<button class="btn confirmBtn">Confirm</button>
		
		</div>
	</form>
</div>
<!-- Parametri processi 1-->

<!---->
<script type='text/javascript'>
//Copiare e incollare la parte js
$(document).ready(function () {
		//eliminazione
		//$('#elenco_processi').dynatable();
		var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#move_file').attr('action','move_file.php?showFrame=false');
		}
		//
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
		//$('#elenco_files').dynatable();
			var error_message = "<?=$message; ?>";
		if (error_message == "ok"){
			console.log("File non corretto:"+error_message);
			alert("Uploaded fileile deleted!");
		}
		if (error_message == "error"){
			console.log("File non corretto:"+error_message);
			alert("Error during file deleting!");
		}
		//
		//
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
		
		/////
		utente_attivo=$("#utente_att").text();
			if (utente_attivo=='Login'){
					console.log("VUOTO");
					$(document).empty();
					alert("Effettua il login!");
				}else{

		//
		var role_active = $("#role_att").text();
	if (role_active == 'ToolAdmin'){
		$('#sc_mng').show();
	}
		//$(".data:input").datepicker();
		
		
		
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
						 //user:data[i].file['user'],
						 utente:data[i].file['utente'],
						 status:data[i].file['status'],
						 pub: data[i].file['pub'],
						 licence: data[i].file['licence'],
						 resource:data[i].file['resource'],
						 image: data[i].file['image'],
						 realtime:data[i].file['realtime'],
						 periodic: data[i].file['periodic'],
						 format: data[i].file['format'],
						 protocol: data[i].file['protocol']		 
						 
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
					if (array_files[i]['type'] == 'ETL'||array_files[i]['type'] == 'R'||array_files[i]['type']=='Java'){
					$("#elenco_files").append('<tr><td><a href="uploads/'+us+'/'+data4+'/'+file1[0]+'/'+array_files[i]['name']+'" class="file_archive_link" download>'+array_files[i]['name']+'</a></td><td>'+array_files[i]['date']+'</td><td>'+array_files[i]['desc']+'</td><td>'+us+'</td><td>'+array_files[i]['type']+'</td><td>'+pubblicato+'</td><td><button type="button" class="editDashBtn transfer" data-toggle="modal" data-target="#transfer-modal" value="'+i+'">EDIT</button></td></tr>');
					}else{
						$("#elenco_files").append('<tr><td><a href="uploads/'+us+'/'+data4+'/'+array_files[i]['name']+'" class="file_archive_link" download>'+array_files[i]['name']+'</a></td><td>'+array_files[i]['date']+'</td><td>'+array_files[i]['desc']+'</td><td>'+us+'</td><td>'+array_files[i]['type']+'</td><td>'+pubblicato+'</td><td><button type="button" class="editDashBtn transfer" data-toggle="modal" data-target="#transfer-modal" value="'+i+'">EDIT</button></td></tr>');
					}
				}
				
				var table =  $('#elenco_files').DataTable({
								"searching": true,
								"paging": true,
								"ordering": true,
								"info": false,
								"responsive": true,
								"lengthMenu": [5,10,15],
								"iDisplayLength": 10,
								"pagingType": "full_numbers",
								"dom": '<"pull-left"l><"pull-right"f>tip',
								"language":{"paginate": {
											"first":      "First",
											"last":       "Last",
											"next":       "Next >>",
											"previous":   "<< Prev"
										},
										"lengthMenu":     "Show	_MENU_ ",
								}
							});
				/*$('#elenco_files').dynatable(
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
				);*/
				
				}
		});
		
		
		//Quando si clicca sul modal di eliminazione

		
		//
		//CREAZIONE JSON
		var id1 = 0;
		var id2 = 0;
		var id3 = 0;
		var id4 = 0;
		//NextJob

		
		//Job Constraint
			$(document).on('click','.transfer',function(){
				//var ind = ($(this).parent().parent().index())-1;
				//var ind = ($(this).parent().parent().index());
				//var ind = $(this).parent().parent().first().children().html();
				var ind = $(this).val();
				var id= array_files[ind]['id'];
				var us= array_files[ind]['utente'];
				var file_n =array_files[ind]['name'];
				var file_type=array_files[ind]['type'];
				var data12 = array_files[ind]['date'];
				var data22 = data12.replace(':','-');
				var data32 = data22.replace(':','-');
				var data42 = data32.replace(' ','-');
				$('#file_id').val(id);
				$('#user_vecchio').val(us);
				$('#creation_date').val(data42);
				$('#file_name').val(file_n);
				$('#file_type').val(file_type);
			});
		

				}	
		
	});
</script>
</body>
</html>
</html>