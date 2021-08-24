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
include('functionalities.php');
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

if (isset($_REQUEST['showFrame'])){
	if ($_REQUEST['showFrame'] == 'false'){
		$hide_menu= "hide";
	}else{
		$hide_menu= "";
	}	
}else{$hide_menu= "";}


if (!isset($_GET['pageTitle'])){
	$default_title = "Process Loader: Dictionary Editor";
}else{
	$default_title = "";
}

if ($role_att ==""){
	echo('You are not authorized to access this page.');
	exit();
}

if($process['functionalities'][$role_att] == 0){
		echo('You are not authorized to access this page.');
		exit();
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
		<script type="text/javascript" charset="utf8" src="lib/datatables.js"></script>
		<script type="text/javascript" src="lib/dataTables.responsive.min.js"></script>
		<script type="text/javascript" src="lib/dataTables.bootstrap4.min.js"></script>
		<script type="text/javascript" src="lib/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="lib/awesomplete-gh-pages/awesomplete.js"></script>
		<link href="lib/responsive.dataTables.css" rel="stylesheet" />
       <!-- Font awesome icons -->
        <link rel="stylesheet" href="fontAwesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="lib/awesomplete-gh-pages/awesomplete.css">
        <!-- Multiselect -->
		<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css">
		<script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
        <!-- Custom CSS -->
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/dashboardList.css" rel="stylesheet">
    </head>
	<body class="guiPageBody">
		<style>
		table { table-layout:auto; width: 100%}
		#mainContentCnt {height:100%}
		td {  overflow: hidden;   text-overflow: ellipsis;  word-wrap: break-word; max-width:67%;}
		
		.paginate_button {padding: 2px;}
		
		.ellipsis {
			text-overflow: ellipsis;
			overflow: hidden;
			white-space: nowrap;
			max-width: 80px;
		}
		body {
			margin: 0
			}
			
		a[title]:hover:after {
			content: attr(title);
			position: absolute;
		}
			
	.tooltip-inner {
		word-break: break-all;
		 max-width: 100%;
		 
			}
	
		  /* Popover Header */
		  .popover-title {
			  color: black;
			  text-align:center;
			  
		  }
		  /* Popover Body */
		  .popover-content {
			word-break: break-all;
			max-width: 500px;
			max-height: 200px;
			overflow-y: scroll;
			overflow-y: auto;   
			text-overflow: ellipsis;
		  }

		  
		  .pop_link {
				color: inherit;
			  text-decoration: inherit;
			}
			
			.pop_link:hover {
				color: inherit;
			  text-decoration: inherit;
			}
  </style>
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
					
				<div id="view-menu" style="margin-left:45px; margin-bottom:20px; margin-top:20px;">
				<div>
				<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#create_element" id="new_voice">
				<i class="fa fa-plus"></i> 
					  Insert new Dictionary element
					</button>
				</div>
				<div class="dropdown">
					  <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Filter by Dictionary type <span class="caret">
					  </button>
					  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<li><a href="#"><input class="select_check" type="checkbox" name="SelectAll" value="select all" onclick="filtroDataAll()" id="select_all">Select all</a></li>
						<li class="divider"></li>
						<li><a href="#"><input class="select_check" type="checkbox" name="nature" value="nature" onclick="filtroData()" id="select_nat">nature</a></li>
						<li><a href="#"><input class="select_check" type="checkbox" name="subnature" value="sub nature" onclick="filtroData()" id="select_subn">sub nature</a></li>
						<li class="divider"></li>
						<li><a href="#"><input class="select_check" type="checkbox" name="value type" value="value type" onclick="filtroData()" id="select_vt">value type</a></li>
						<li><a href="#"><input class="select_check" type="checkbox" name="value unit" value="value unit" onclick="filtroData()" id="select_vu">value unit</a></li>
						<li class="divider"></li>
						<li><a href="#"><input class="select_check" type="checkbox" name="data type" value="data_type" onclick="filtroData()" id="select_dt">data type</a></li>
					  </div>
					</div>
				<!---->
				<!-- -->
				</div>
                <table id="value_table" class="table table-striped table-bordered" style="width: 100%">
					<thead class="dashboardsTableHeader">
                    <tr>
                        <!--<th>Id </th>-->
						<th>Value Name</th>
						<th style="max-width: 150px;">Dictionary Type</th>
						<th>Description</th>
						<th>Data Types</th>
						<th>Parent Value Name</th>
						<th>Child Value Name</th>
                        <th>Controls</th> 
                    </tr>
					</thead>
                </table>


                        
                </div>
				</div>
				
		</div>
		</div>
	<!-- Modal -->
	<!-- Modal -->
<!-- Fine modal creazione processo -->
<!-- FIne Button-->
	
    </div>
    
  </div>
</div>
<!-- Button-->
<!-- MODAL1 -->
<!-- Parametri processi 1-->
	<div class="modal fade fade bd-example-modal-lg" id="create_element" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" style="color:white;">&times;</button>
					<h4 class="modal-title" style="color:white; size:0.8 em">Create New voice in the Dictionary</h4>
				</div>
				<form method="post" id="create_element" action="get_dictionary.php?action=create_voice&showFrame=<?=$_REQUEST['showFrame']; ?>" accept-charset="UTF-8">
				<div class="modal-body">
				<div class="input-group"><span class="input-group-addon">Select type of element: </span>
							<select id="select_type_creation" name="select_type_creation" class="form-control">  
								  <option value="nature">nature</option>
								  <option value="subnature">subnature</option>
								  <option value="value type">value type</option>
								  <option value="value unit">value unit</option>
								  <option value="data type">data type</option>
								  
							</select>
				</div><br />
				<div class="input-group"><span class="input-group-addon">Value Name: </span><input id="vn_create" type="text" class="form-control autocomplete" name="vn_create" oninput="check('create')" list="mylist" data-minchars="3" required/>
				<datalist id="mylist"></datalist>
				</div>
				<div id="message_control" style="color:#AA3939"></div>
				<br />
				<div class="input-group"><span class="input-group-addon">Description: </span><input id="label_create" type="text" class="form-control" name="lb_create" required></input></div><br />
				<div class="input-group" id="list_nature" ><span class="input-group-addon">Select Nature: </span>
							<select id="select_nature" name="select_nature" class="form-control"></select>
				</div>
				<div class="input-group" id="list_vt"><span class="input-group-addon">Select Value Type: </span>
							<select id="select_vtype" name="select_vtype[]" class="form-control" multiple="multiple" size="24" data-show-subtext="true" data-live-search="true">
							</select>
				</div>
				<div class="input-group" id="list_dt"><span class="input-group-addon">Select Data Type: </span>
							<select id="select_dtype" name="select_dtype[]" class="form-control" multiple="multiple" size="24" data-show-subtext="true" data-live-search="true">
							</select>
				</div>
				<br />
				</div>				
				<div class="modal-footer">
					<button type="button" class="btn cancelBtn" data-dismiss="modal" id="close_new_voice">Cancel</button>
					<input type="submit" value="Confirm" id="create_new_voice" class="btn confirmBtn"/>
				</div>
				</form>
		</div>
	</div>

</div>
<!-- -->
<!-- EDIT-->
	<div class="modal fade fade bd-example-modal-lg" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" style="color:white;">&times;</button>
					<h4 class="modal-title" style="color:white;">Edit Dictionary Voice</h4>
				</div>
				<form method="post" id="edit_element" action="get_dictionary.php?action=edit_voice&showFrame=<?=$_REQUEST['showFrame']; ?>" accept-charset="UTF-8">
				<div class="modal-body">
				<div class="input-group" style="display:none;"><span class="input-group-addon">Id: </span><input id="id_create_e" type="text" class="form-control" name="id" readonly></input></div><br />
				<div class="input-group" style="display:none;"><input id="vn_hid" type="text" class="form-control" name="vn_hid" readonly></div>
				<!-- -->
				<div class="input-group" ><span class="input-group-addon">Value Name: </span><input id="vn_create_e" type="text" class="form-control autocomplete" name="vn_create_e" oninput="check('edit')" list="mylist_e" data-minchars="3" readonly/>
				<datalist id="mylist_e"></datalist>
				</div>
				<div id="message_control_e" style="color:#AA3939"></div>
				<br />
				<div class="input-group"><span class="input-group-addon">Description: </span><input id="label_create_e" type="text" class="form-control" name="label_create_e"></input></div><br />
				<div class="input-group"><span class="input-group-addon">Select type of element: </span>
							<input id="select_type_creation_e" type="text" class="form-control" name="select_type_creation" readonly>
							<!--<select id="select_type_creation_e" name="select_type_creation" class="form-control">
								  <option value="nature">nature</option>
								  <option value="subnature">subnature</option>
								  <option value="value type">value type</option>
								  <option value="value unit">value unit</option>
							</select>-->
				</div><br />
				<div class="input-group" id="list_nature_e"><span class="input-group-addon">Select Nature: </span>
							<select id="select_nature_e" name="select_nature_e" class="form-control" >
							</select><br />
				</div>
				<div class="input-group" id="list_vt_e"><span class="input-group-addon">Select Value Type: </span>
							<select id="select_vt_e" name="select_vt_e[]" class="form-control" multiple="multiple" size="24">
							</select>
				</div>
				<div class="input-group" id="list_dt_e"><span class="input-group-addon">Select Data Type: </span>
							<select id="select_dt_e" name="select_dt_e[]" class="form-control" multiple="multiple" size="24">

							</select>
				</div>
				<br /><br />
				</div>				
				<div class="modal-footer">
					<button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
					<input type="submit" value="Confirm" class="btn confirmBtn" id="edit_voice"/>
				</div>
				</form>
		</div>
	</div>

</div>
<!-- DELETE -->
<div class="modal fade fade bd-example-modal-lg" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" style="color:white;">&times;</button>
					<h4 class="modal-title" style="color:white;">Delete Dictionary Voice</h4>
				</div>
				<form method="post" id="delete_element" action="get_dictionary.php?action=delete_voice&showFrame=<?=$_REQUEST['showFrame']; ?>" accept-charset="UTF-8">
				<div class="modal-body" style="color:white;">
				Are you sure you want delete this element from Dictionary?
				</div>
				<input id="id_delete" type="text" name="id" style="display: none;"></input>
				<div class="modal-footer">
					<button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
					<input type="submit" value="Confirm" class="btn confirmBtn"/>
				</div>
				</form>
		</div>
	</div>

</div>
<!-- PUT -->
<div class="modal fade fade bd-example-modal-lg" id="put-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" style="color:white;">&times;</button>
					<h4 class="modal-title" style="color:white;">Put Dictionary Voice</h4>
				</div>
				<form method="post" id="put_element" action="get_dictionary.php" accept-charset="UTF-8">
				<div class="modal-body">
				</div>				
				<div class="modal-footer">
					<button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
					<input type="submit" value="Confirm" class="btn confirmBtn"/>
				</div>
				</form>
		</div>
	</div>
</div>
<!-- DISPATCH -->
<div class="modal fade fade bd-example-modal-lg" id="dispatch-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" style="color:white;">&times;</button>
					<h4 class="modal-title" style="color:white;">Dispatch Dictionary Voice</h4>
				</div>
				<form method="post" id="dispatch_element" action="get_dictionary.php" accept-charset="UTF-8">
				<div class="modal-body">
				</div>				
				<div class="modal-footer">
					<button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
					<input type="submit" value="Confirm" class="btn confirmBtn"/>
				</div>
				</form>
		</div>
	</div>
</div>
<!-- -->
<!-- -->
<script type='text/javascript'>
var nascondi= "<?=$hide_menu; ?>";
var array_check = new Array();
		
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
		}
	
$('body').on('click', function (e) {
			//only buttons
		if ($(e.target).data('toggle') !== 'popover' && $(e.target).parents('.popover.in').length === 0) { 
			$('[data-toggle="popover"]').popover('hide');
			//console.log('NOO!');
		}
	});


	$(function () {
				$("[data-toggle='tooltip']").tooltip();
				});
				
				$('body').on('click', function (e) {
					//only buttons
					if ($(e.target).data('toggle') !== 'popover'
						&& $(e.target).parents('.popover.in').length === 0) { 
						$('[data-toggle="popover"]').popover('hide');
					}
				});	
		
function myFunction() {
	// Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("myInput");
	filter = input.value.toUpperCase();
	table = document.getElementById("value_table");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[3];
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
	table = document.getElementById("value_table");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[1];
		if (td) {
		if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
		tr[i].style.display = "";
		} else {
		tr[i].style.display = "none";
		}
    } 
  }
}

function editData(id, value, label, type, parent, data_type){
	//alert('data_type: '+data_type);
	$('#id_create_e').val(id);
	$('#vn_create_e').val(value);
	$('#vn_hid').val(value);
	$('#label_create_e').val(label);
	$('#select_type_creation_e').val(type);
	var vn_create_e=	$('#vn_create_e').val();
	console.log('vn_create_e:	'+	vn_create_e);
	console.log('parent: '+parent);
	//
	if(type == "subnature"){
		var array_act = new Array();
		$("#list_nature_e").show();
	$.ajax({
									url: "get_dictionary.php",
										data: { action: "get_values",
												value_unit: 0,
												select_all: 0,
												nature:1,
												subnature:0,
												value_type: 0,
												data_type: 0
											 },
										type: "GET",
										async: true,
										dataType: 'json',
										success: function (data) {
											
											for (var i = 0; i < data.length; i++)
											{
												 array_act[i] = {
												 id: data[i]['id'],
												 value: data[i]['value']
											};
											$("#select_nature_e").append('<option value="'+array_act[i]['id']+'">'+array_act[i]['value']+'</option>');
											
										}
										$("#select_nature_e").val(parent);
								}
							/////////////
						});
	}else{
		$("#list_nature_e").hide();
	}
//select_vt_e	
	if(type == "value unit"){
		var array_act = new Array();
		$("#list_vt_e").show();
	$.ajax({
									url: "get_dictionary.php",
										data: { action: "get_values",
												value_unit: 0,
												select_all: 0,
												nature:0,
												subnature:0,
												value_type: 1,
												data_type: 0
											 },
										type: "GET",
										async: true,
										dataType: 'json',
										success: function (data) {
											for (var i = 0; i < data.length; i++)
											{
												 array_act[i] = {
												 id: data[i]['id'],
												 value: data[i]['value']
											};
											$("#select_vt_e").append('<option id="v_'+array_act[i]['id']+'" value="'+array_act[i]['id']+'">'+array_act[i]['value']+'</option>');
										}
										//
										var list_parent = parent.split(', ');
										var l = list_parent.length;
										//
										for (var y = 0; y < l; y++){
														$('#v_'+list_parent[y]).attr('selected', 'selected');
											}
										$('#select_vt_e').multiselect('rebuild');
										//
								}
							/////////////
							
							//
						});
			//
	}else{
		$("#list_vt_e").hide();
	}
	if(type == "value type"){
		var array_act = new Array();
		$("#list_dt_e").show();
				$.ajax({
									url: "get_dictionary.php",
										data: { action: "dt_voice"},
										type: "GET",
										async: true,
										dataType: 'json',
										success: function (data) {
											for (var i = 0; i < data.length; i++){
												 array_act[i] = {
												 id: data[i]['id'],
												 value: data[i]['value'],
												 //data_type: data[i]['data_type']
											};
											//select_dt_e
											$("#select_dt_e").append('<option id="v_'+array_act[i]['value']+'" name="select_dt_e" value="'+array_act[i]['id']+'">'+array_act[i]['value']+'</option>');

											
										}
										//
										var list_parent = data_type.split(',');
										var l = list_parent.length;
										//
										for (var y = 0; y < l; y++){
											console.log(list_parent[y]);
														$('#v_'+list_parent[y]).attr('selected', 'selected');
											}
										$('#select_dt_e').multiselect('rebuild');
										//
								}
							/////////////
							
							//
						});
		//   //SELECT DATA TYPE//
		//var nameArr = data_type.split(',');
		/*var lang_Arr = nameArr.length;
		$('#select_dt_e').multiselect('rebuild');
		for(i = 0; i < lang_Arr; i++){
			var d1 = nameArr[i];
			$(":checkbox[value="+d1+"]").prop("checked","true");
		}*/
		//
		//
		//$('#select_dt_e').multiselect('rebuild');
	}else{
		$("#list_dt_e").hide();
	}
	//
}

function deleteData(id){
	$('#id_delete').val(id);
	//
}
/***************************/
function filtroDataAll(){
	//
	var tab = $('#value_table').DataTable();
	tab.clear().destroy();
	 var array_act = new Array();
	 var select_all = "";
	 var value_unit = "";
	 var nature = "";
	 var subnature = "";
	 var value_type = "";
	 //
	 if ($('#select_all').is(':checked')) {
		select_all = 1;
		$('#select_nat').prop('checked', false);
		$('#select_subn').prop('checked', false);
		$('#select_vu').prop('checked', false);
		$('#select_vt').prop('checked', false);
	 }else{
		 select_all = 0;
	 }
	//
	$.ajax({
			url: "get_dictionary.php",
                data: { action: "get_values",
						value_unit: 0,
						select_all: select_all,
						value_type: 0,
						nature: 0,
						subnature: 0,
						data_type: 0
					 },
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
					
                    for (var i = 0; i < data.length; i++)
                    {
						 array_act[i] = {
						 id: data[i]['id'],
                         value: data[i]['value'],
						 label: data[i]['label'],
						 type: data[i]['type'],
						 parent_id: data[i]['parent_id'],
						 parent_value: data[i]['parent_value'],
						 child_value: data[i]['child_value'],
					};
					
					var parV = "";
					//
					var string_type = String(array_act[i]['type']);
					var string_value = String(array_act[i]['value']);
					var string_label = String(array_act[i]['label']);
					//var edit_finction= array_act[i]['id']+",'"+string_value+"','"+string_label+"','"+string_type+"'","'"+parV+"'";
					var parV = array_act[i]['parent_value'];
					var parDt = "";
					//
					if(parV == null){
						parV = "";
					}else{
						parV = parV.toString().replace(/,/g, ', ');
					}
					var id_prent = array_act[i]['parent_id'];
					if (id_prent == null){
						id_prent ="";
					}else{
						id_prent = id_prent.toString().replace(/,/g, ', ');
					}
					
					var childV = array_act[i]['child_value'];
					if (childV == null){
						childV = "";
					}else{
						childV = childV.toString().replace(/,/g, ', ');
					}
					if((parDt == null)||(parDt == "undefined")){
						parDt = "";
					}else{
						parDt = parDt.toString().replace(/,/g, ', ');
					}
					
					var edit_finction= array_act[i]['id']+",'"+string_value+"','"+string_label+"','"+string_type+"','"+id_prent+"','"+parDt+"'";
					//alert(array_act);
					//var button_put = '<button type="button" class="pubDashBtn put_file" data-target="#put-modal" data-toggle="modal" value="'+i+'" onclick="">PUT</button>';
					var button_edit = '<button type="button" class="editDashBtn edit_file" data-target="#edit-modal" data-toggle="modal" value="'+i+'" onclick="editData('+edit_finction+')">EDIT</button>';
					var button_del = '<button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal" value="'+i+'" onclick="deleteData('+array_act[i]['id']+')">DELETE</button>';
					//var button_dispacth = '<button type="button" class="viewDashBtn dispatch_file" data-target="#dispatch-modal" data-toggle="modal" value="'+i+'" onclick="">DISPATCH</button>';
					var button_put ="";
					var button_dispacth = "";
					$("#value_table").append('<tr><td class="pop_link ellipsis">'+array_act[i]['value']+'</td><td class="pop_link ellipsis">'+array_act[i]['type']+'</td><td>'+array_act[i]['label']+'</td><td></td><td class="pop_link ellipsis" title="'+parV+'" data-content="'+parV+'" data-toggle="popover" data-original-title>'+parV+'</td><td href="#" class="pop_link ellipsis" data-toggle="popover" data-content="'+childV+'" title="'+childV+'"  data-original-title>'+childV+'</td><td>'+button_edit+' '+button_dispacth+' '+button_put+' ' +button_del+'</td></tr>');
					}
					var table = $('#value_table').DataTable({
						"searching": true,
						"paging": true,
						"ordering": true,
						"bSort": true,
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
				}
		});
	//
}

function check(type_action){
	$('.checkoptc').hide();
	$('.checkopte').hide();
	var menu = type_action;
	first = $('#vn_create_e').val();
	console.log(menu);
	if(menu == 'create'){
		//var test_control = array_check.includes(check);
	var select_type_creation = $('#select_type_creation').val();
	//
		$('.c_'+select_type_creation).show();
	var check = $('#vn_create').val();
		checkL = check.toLowerCase();
	var test_control = array_check.includes(checkL);
		
	if(test_control){
		  	console.log('yet exist');
			$('#create_new_voice').attr("disabled", true);
			$('#message_control').html("<p>This Value name is yet used in another element, you can't use it</p>");
	}else{
			console.log('not exist');
			$('#create_new_voice').attr("disabled", false);	
			$('#message_control').empty('');
	}
		//
	//if((select_type_creation=='subnature')||(select_type_creation=='value unit')){
		
		
			$.ajax({
				url: "get_dictionary.php",
				data: { action: "check_values",
						check: check,
						check_type: select_type_creation
				},
				type: "GET",
				async: true,
				dataType: 'json',
				success: function (data) {
					var len = data.length;
					var array_act = new Array();
					//
					if(len > 0){
								 array_act[0] = {
								 id: data[0]['id'],
								 value: data[0]['value'],
								 label: data[0]['label'],
								 type: data[0]['type'],
								 parent_id: data[0]['parent_id'],
								};
								$('#label_create').val(array_act[0]['label']);
								if((select_type_creation=='subnature')){
										$('#select_nature').val(array_act[0]['parent_id']);
								}
								if((select_type_creation=='value unit')){
											var array_id = new Array();
											for (var i = 0; i < array_act[0]['parent_id'].length; i++){
												 var id_check = array_act[0]['parent_id'][i];
														$("#select_vtype option[value='"+id_check+"']").prop( "checked", true );
											}
										}
						}
				}
			});
		//}
	} else if(menu == 'edit'){
		//edit_voice
		var vn_hid = $('#vn_hid').val();
			vn_hid = vn_hid.toLowerCase();
		var select_type_creation = $('#select_type_creation_e').val();
		var check = $('#vn_create_e').val();
			checkL = check.toLowerCase();
			console.log('checkL: '+checkL);
		console.log('vn_hid: '+vn_hid);
	var test_control = array_check.includes(checkL);
		
	if(test_control){
		  	console.log('yet exist');
			console.log('first:'+first);
			console.log('check:	'+check);
			if(vn_hid == check){
				$('#edit_voice').attr("disabled", false);
				$('#message_control_e').empty('');
			}else{
				$('#edit_voice').attr("disabled", true);
				$('#message_control_e').html("<p>This Value name is yet used in another element, you can't use it</p>");
			}
	}else{
			console.log('not exist');
			$('#edit_voice').attr("disabled", false);
			$('#message_control_e').empty('');			
	}
		//
	
	//console.log('EDIT:	'+select_type_creation);
	//if((select_type_creation=='subnature')||(select_type_creation=='value unit')){
		//var check = $('#vn_create_e').val();
			//checkL = check.toLowerCase();
			$.ajax({
				url: "get_dictionary.php",
				data: { action: "check_values",
						check: check,
						check_type: select_type_creation
				},
				type: "GET",
				async: true,
				dataType: 'json',
				success: function (data) {
					var len = data.length;
					var array_act = new Array();
					//
					if(len > 0){
								 array_act[0] = {
								 id: data[0]['id'],
								 value: data[0]['value'],
								 label: data[0]['label'],
								 type: data[0]['type'],
								 parent_id: data[0]['parent_id'],
								 data_type: data[0]['data_type'],
								};
								$('#label_create_e').val(array_act[0]['label']);
								if((select_type_creation=='subnature')){
										$('#select_nature_e').val(array_act[0]['parent_id']);
								}
											if((select_type_creation=='value unit')){
											var array_id = new Array();
											for (var i = 0; i < array_act[0]['parent_id'].length; i++){
												 var id_check = array_act[0]['parent_id'][i];
														$("#select_vt_e option[value='"+id_check+"']").prop( "checked", true );
											}
										}
						}
				}
			});
		//}
	}
}

//********//
function filtroData(){
	//
	var tab = $('#value_table').DataTable();
	tab.clear().destroy();
	 var array_act = new Array();
	 var select_all = "";
	 var value_unit = "";
	 var nature = "";
	 var subnature = "";
	 var value_type = "";
	 //
	 if ($('#select_all').is(':checked')) {
		select_all = 1;
	 }else{
		 select_all = 0;
	 }
	 
	 if ($('#select_vu').is(':checked')) {
		 value_unit = 1;
		 select_all = 0;
		 $('#select_all').prop('checked', false);
	 }else{
		 value_unit = 0;
	 }
	 if ($('#select_nat').is(':checked')) {
		 nature = 1;
		 select_all = 0;
		 $('#select_all').prop('checked', false);
	 }else{
		 nature = 0;
	 }
	 if ($('#select_subn').is(':checked')) {
		 subnature = 1;
		 select_all = 0;
		 $('#select_all').prop('checked', false);
	 }else{
		 subnature = 0;
	 }
	 
	 if($('#select_vt').is(':checked')){
		 value_type = 1;
		 select_all = 0;
		 $('#select_all').prop('checked', false);
	 }else{
		 value_type = 0;
	 }
	 if($('#select_dt').is(':checked')){
		 data_type = 1;
		 select_all = 0;
		 $('#select_all').prop('checked', false);
	 }else{
		 data_type = 0;
	 }
	 
	//
	$.ajax({
			url: "get_dictionary.php",
                data: { action: "get_values",
						value_unit: value_unit,
						select_all: select_all,
						value_type:value_type,
						nature:nature,
						subnature:subnature,
						data_type:data_type
					 },
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
					
                    for (var i = 0; i < data.length; i++)
                    {
						 array_act[i] = {
						 id: data[i]['id'],
                         value: data[i]['value'],
						 label: data[i]['label'],
						 type: data[i]['type'],
						 parent_id: data[i]['parent_id'],
						 parent_value: data[i]['parent_value'],
						 child_value: data[i]['child_value'],
						 data_type: data[i]['data_type']
					};
					var parV = "";
					//
					var string_type = String(array_act[i]['type']);
					var string_value = String(array_act[i]['value']);
					var string_label = String(array_act[i]['label']);
					var parV = array_act[i]['parent_value'];
					if(parV == null){
						parV = "";
					}else{
						parV = parV.toString().replace(/,/g, ', ');
					}
					
					var id_prent = array_act[i]['parent_id'];
					if (id_prent == null){
						id_prent ="";
					}else{
						id_prent = id_prent.toString().replace(/,/g, ', ');
					}
					
					var childV = array_act[i]['child_value'];
					if (childV == null){
						childV = "";
					}else{
						childV = childV.toString().replace(/,/g, ', ');
					}
					
					//var edit_finction= array_act[i]['id']+",'"+string_value+"','"+string_label+"','"+string_type+"','"+id_prent+"'";
					var edit_finction= array_act[i]['id']+",'"+string_value+"','"+string_label+"','"+string_type+"','"+id_prent+"','"+array_act[i]['data_type']+"'";
					var button_edit = '<button type="button" class="editDashBtn edit_file" data-target="#edit-modal" data-toggle="modal" value="'+i+'" onclick="editData('+edit_finction+')">EDIT</button>';
					var button_del = '<button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal" value="'+i+'" onclick="deleteData('+array_act[i]['id']+')">DELETE</button>';
					var button_put ="";
					var button_dispacth = "";
					$("#value_table").append('<tr><td class="pop_link ellipsis">'+array_act[i]['value']+'</td><td class="pop_link ellipsis">'+array_act[i]['type']+'</td><td>'+array_act[i]['label']+'</td><td></td><td class="pop_link ellipsis" title="'+parV+'"  data-content="'+parV+'" data-toggle="popover" data-original-title>'+parV+'</td><td href="#" class="pop_link ellipsis" data-toggle="popover" data-content="'+childV+'" title="'+childV+'"  data-original-title>'+childV+'</td><td>'+button_edit+' '+button_dispacth+' '+button_put+' ' +button_del+'</td></tr>');
					}
					var table = $('#value_table').DataTable({
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
										"next":       "	Next >>",
										"previous":   "<< Prev	"
										},
						"lengthMenu":     "Show	_MENU_ ",
						}
					});	
					
				}
		});
	//
	//
}
//********//


    $(document).ready(function () {
		var nascondi= "<?=$hide_menu; ?>";
		$("#list_nature").hide();
		$("#list_vt").hide();
		$("#list_dt").hide();
		//
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('.ellipsis').css("max-width","100px");
			$('td').css("overflow","visible");
		}
			
		/**************************/
		$(function () { 
		$('#select_vt_e').multiselect({
			includeSelectAllOption: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			maxHeight: 450,
			maxWidth: 300 
			});
			});
			
		$(function () { 
		$('#select_vtype').multiselect({
			includeSelectAllOption: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			maxHeight: 450,
			maxWidth: 300 
			});
			});
		$(function () { 
		$('#select_dtype').multiselect({
			includeSelectAllOption: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			maxHeight: 450,
			maxWidth: 300 
			});
			});
		/*************************/

		var role_active = $("#role_att").text();
	if (role_active == 'ToolAdmin'){
		$('#sc_mng').show();
	}
	
	
	/*
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
		//
		var role_active = "<?=$process['functionalities'][$role]; ?>";

			if (role_active == 0){
						$(document).empty();
						if(window.self !== window.top){
						window.location.href='https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';	
						}
						else{
						window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
						}
					}
					*/
		//
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
		//
       var array_act = new Array();
	   //
	   $.ajax({
			url: "get_dictionary.php",
                data: {action: "get_values", select_all:1},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
					
                    for (var i = 0; i < data.length; i++)
                    {
						 array_act[i] = {
						 id: data[i]['id'],
                         value: data[i]['value'],
						 label: data[i]['label'],
						 type: data[i]['type'],
						 parent_id: data[i]['parent_id'],
						 parent_value: data[i]['parent_value'],
						 child_value: data[i]['child_value'],
						 data_type: data[i]['data_type']
					};
					//alert(array_act);
					var parV = "";
					var childV = "";
					//
					var string_type = String(array_act[i]['type']);
					var string_value = String(array_act[i]['value']);
					var string_label = String(array_act[i]['label']);
					var string_datatype = String(array_act[i]['data_type']);
					
					if ((string_datatype == null)||(string_datatype == "undefined")){
						string_datatype ="";
					}
					//var edit_finction= array_act[i]['id']+",'"+string_value+"','"+string_label+"','"+string_type+"'";
					var parent_id = array_act[i]['parent_id'];
					if (parent_id == null){
						parent_id = "";
					}else{
						parent_id = parent_id.toString().replace(/,/g, ', ');
					}
					
					var parV = array_act[i]['parent_value'];
					if (parV == null){
						parV = "";
					}else{
						parV = parV.toString().replace(/,/g, ', ');
					}
					//
					var childV = array_act[i]['child_value'];
					if (childV == null){
						childV = "";
					}else{
						childV = childV.toString().replace(/,/g, ', ');
					}
					//
					var edit_finction= array_act[i]['id']+",'"+string_value+"','"+string_label+"','"+string_type+"','"+parent_id+"','"+string_datatype+"'";
					//
					//var button_put = '<button type="button" class="pubDashBtn put_file" data-target="#put-modal" data-toggle="modal" value="'+i+'" onclick="">PUT</button>';
				    var button_edit = '<button type="button" class="editDashBtn edit_file" data-target="#edit-modal" data-toggle="modal" value="'+i+'" onclick="editData('+edit_finction+')">EDIT</button>';
					var button_del = '<button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal" value="'+i+'" onclick="deleteData('+array_act[i]['id']+')">DELETE</button>';
					//var button_dispacth = '<button type="button" class="viewDashBtn dispatch_file" data-target="#dispatch-modal" data-toggle="modal" value="'+i+'" onclick="">DISPATCH</button>';
					var button_dispacth ="";
					var button_put = "";
					//
					array_check[i] = array_act[i]['value'].toLowerCase();
					//
					$("#mylist").append('<option class="checkoptc c_'+array_act[i]['type']+'" readonly>'+array_act[i]['value']+'</option>');
					$("#mylist_e").append('<option class="checkopte e_'+array_act[i]['type']+'" readonly>'+array_act[i]['value']+'</option>');
					$("#value_table").append('<tr><td class="pop_link ellipsis">'+array_act[i]['value']+'</td><td class="pop_link ellipsis">'+array_act[i]['type']+'</td><td>'+array_act[i]['label']+'</td><td>'+string_datatype+'</td><td href="#" class="pop_link ellipsis" data-toggle="popover" data-content="'+parV+'" title="'+parV+'"  data-original-title>'+parV+'</td><td href="#" class="pop_link ellipsis" data-toggle="popover" data-content="'+childV+'" title="'+childV+'"  data-original-title>'+childV+'</td><td>'+button_edit+' '+button_dispacth+' '+button_put+' ' +button_del+'</td></tr>');
					}
					var table = $('#value_table').DataTable({
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
				}
		});
		//
		$( "#select_all" ).prop("checked", true);
	   ////////////////////////	
	   $('#create_element').on('hidden.bs.modal', function () {
			  $("#select_nature").empty();
			  $("#select_vt_e").empty();
			  $('#list_vt').hide();
			  $("#list_dt").hide();
			  $('#list_nature').hide();
			  $('#message_control').empty();
			  $('#create_new_voice').attr("disabled", false);
			});
		////////////
		$('#edit-modal').on('hidden.bs.modal', function () {
			  $("#select_nature_e").empty();
			  $("#select_vt_e").empty();
			  $('#message_control_e').empty();
			  $('#edit_voice').attr("disabled", false);
			  $('#select_dt_e').empty();
			});
	   /////////
	   $('#close_new_voice').click(function() {
		   $('#vn_create').val('');
		   $('#label_create').val('');
		   $('#select_type_creation').val('nature');
		   $('#list_vt').hide();
		   $("#list_dt").hide();
		   $('#list_nature').hide();
	   });
	   ///////
	   /*$("#edit_voice").click(function() {
			//
			console.log('edit_click');
			var id_create_e = $('#id_create_e').val();
			var vn_create_e = $('#vn_create_e').val();
			var label_create_e = $('#label_create_e').val();
			var select_type_creation_e = $('#select_type_creation_e').val();
			var list_nature_e = $('#list_nature_e').val();
			var select_nature_e = $('#select_nature_e').val();
			var list_vt_e = $('#list_vt_e').val();
			var list_dt_e = $('#list_dt_e').val();
			console.log(list_dt_e);
			//
			$.ajax({
				url: "get_dictionary.php",
				data: { action: "edit_voice",
							id: id_create_e,
							vn_create_e: vn_create_e,
							label_create_e: label_create_e,
							select_type_creation: select_type_creation_e,
			                select_nature_e: select_nature_e,
						    select_dt_e: list_dt_e,
							list_vt_e: list_vt_e,
				 },
				type: "POST",
				async: true,
				dataType: 'json',
				success: function (data) {
								console.log('OK');
						}
			});
			//
	   });*/
	   /////
	   /*
	    $("#select_type_creation_e").change(function () { 
							var type = $("#select_type_creation_e").val();
							console.log('type:	'+type);
							if(type == "subnature"){
								var array_act = new Array();
									$("#list_nature_e").show();
													$.ajax({
															url: "get_dictionary.php",
																data: { action: "get_values",
																		value_unit: 0,
																		value_type: 0,
																		select_all: 0,
																		nature:1,
																		subnature:0
																	 },
																type: "GET",
																async: true,
																dataType: 'json',
																success: function (data) {
																	
																	for (var i = 0; i < data.length; i++)
																	{
																		 array_act[i] = {
																		 id: data[i]['id'],
																		 value: data[i]['value']
																	};
																	$("#select_nature_e").append('<option value="'+array_act[i]['id']+'">'+array_act[i]['value']+'</option>');
																	
																}
														}
												});
									///////////
							}else{
								$("#select_nature_e").empty();
								$("#list_nature_e").hide();
							}
							if(type == "value unit"){
								var multiselect = $('.multiselect').val();
								console.log('multiselect: '+multiselect);
								var array_act = new Array();
									$("#list_vt_e").show();
													$.ajax({
															url: "get_dictionary.php",
																data: { action: "get_values",
																		value_unit: 0,
																		select_all: 0,
																		nature:0,
																		subnature:0,
																		value_type: 1
																	 },
																type: "GET",
																async: true,
																dataType: 'json',
																success: function (data) {
																	
																	for (var i = 0; i < data.length; i++)
																	{
																		 array_act[i] = {
																		 id: data[i]['id'],
																		 value: data[i]['value']
																	};
																	//
																	
																	var list_parent = parent.split(', ');
																	var l = list_parent.length;
																	//
																	for (var y = 0; y < l; y++){
																					$('#v_'+list_parent[y]).attr('selected', 'selected');
																		}
																	$("#select_vt_e").append('<option value="'+array_act[i]['id']+'">'+array_act[i]['value']+'</option>');
																	
																}
																$("#select_vt_e").multiselect('rebuild');
														}
												});
									///////////
							}else{
								$("#select_vt_e").empty();
								$("#list_vt_e").hide();
							}
					
				});
		*/
		
	   //
	   
	   //$("#new_voice").click(function() {
							//$( "#target" ).click();
		$("#select_type_creation").change(function () {
							var type = $("#select_type_creation").val();
							console.log(type);
							if(type == "subnature"){
								var array_act = new Array();
									$("#list_nature").show();
													$.ajax({
															url: "get_dictionary.php",
																data: { action: "get_values",
																		value_unit: 0,
																		value_type: 0,
																		select_all: 0,
																		nature:1,
																		subnature:0,
																		data_type: 0
																	 },
																type: "GET",
																async: true,
																dataType: 'json',
																success: function (data) {
																	
																	for (var i = 0; i < data.length; i++)
																	{
																		 array_act[i] = {
																		 id: data[i]['id'],
																		 value: data[i]['value']
																	};
																	$("#select_nature").append('<option value="'+array_act[i]['id']+'">'+array_act[i]['value']+'</option>');
																	
																}
														}
												});
									///////////
							}else{
								$("#select_nature").empty();
								$("#list_nature").hide();
							}
							////
							if(type == "value unit"){
								var array_act0 = new Array();
									$("#list_vt").show();
													$.ajax({
															url: "get_dictionary.php",
																data: { action: "get_values",
																		value_unit: 0,
																		value_type: 1,
																		select_all: 0,
																		nature:0,
																		subnature:0,
																		data_type: 0
																	 },
																type: "GET",
																async: true,
																dataType: 'json',
																success: function (data0) {	
																	console.log(data0);
																	for (var y = 0; y < data0.length; y++){
																		 array_act0[y] = {
																		 id: data0[y]['id'],
																		 value: data0[y]['value']
																		};
																		$("#select_vtype").append('<option value="'+array_act0[y]['id']+'">'+array_act0[y]['value']+'</option>');
																}
																$('#select_vtype').multiselect('rebuild');
																$('#select_dtype').multiselect('rebuild');
														}
												});
									///////////
							}else{
								$("#select_vtype").empty();
								$("#list_vt").hide();
								$("#list_dt").hide();
							}
							$('#select_vtype').multiselect('rebuild');
							if (type == "data type"){
								var array_act0 = new Array();
									$("#list_vt").show();
													$.ajax({
															url: "get_dictionary.php",
																data: { action: "get_values",
																		value_unit: 0,
																		value_type: 1,
																		select_all: 0,
																		nature:0,
																		subnature:0,
																		data_type: 0
																	 },
																type: "GET",
																async: true,
																dataType: 'json',
																success: function (data0) {	
																	console.log(data0);
																	for (var y = 0; y < data0.length; y++){
																		 array_act0[y] = {
																		 id: data0[y]['id'],
																		 value: data0[y]['value']
																		};
																		$("#select_vtype").append('<option value="'+array_act0[y]['id']+'">'+array_act0[y]['value']+'</option>');
																}
																$('#select_vtype').multiselect('rebuild');
																$('#select_dtype').multiselect('rebuild');
														}
												});
									///////////
								/*$("#list_dt").show();
								//
								var array_act0 = new Array();
											$.ajax({
															url: "get_dictionary.php",
																data: { action: "get_values",
																		value_unit: 0,
																		value_type: 0,
																		select_all: 0,
																		nature:0,
																		subnature:0,
																		data_type: 1
																	 },
																type: "GET",
																async: true,
																dataType: 'json',
																success: function (data0) {	
																console.log(data0);
																	for (var y = 0; y < data0.length; y++){
																		 array_act0[y] = {
																		 id: data0[y]['id'],
																		 value: data0[y]['value']
																		};
																		$("#select_dtype").append('<option value="'+array_act0[y]['id']+'">'+array_act0[y]['value']+'</option>');
																}
																$('#select_vtype').multiselect('rebuild');
																$('#select_dtype').multiselect('rebuild');
														}
												});
								//
								$("#list_vt").hide();
								$('#select_dtype').multiselect('rebuild');*/
							}else{
								$("#list_dt").hide();
							}
					
				});
	   
	   ////
	});
	
</script>
</body>