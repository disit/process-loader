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
include('external_service.php');
//include('functionalities.php');
$duplicated = '';


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
	   <!--
       <link rel="stylesheet" href="dynatable/jquery.dynatable.css">
       <script src="dynatable/jquery.dynatable.js"></script> 
		-->
		<script type="text/javascript" charset="utf8" src="lib/datatables.js"></script>
		<script type="text/javascript" src="lib/dataTables.responsive.min.js"></script>
		<script type="text/javascript" src="lib/dataTables.bootstrap4.min.js"></script>
		<script type="text/javascript" src="lib/jquery.dataTables.min.js"></script>
		<link href="lib/responsive.dataTables.css" rel="stylesheet" />
		<!-- -->
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
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php 
						include "mobMainMenu.php" 
						?></div>
                    </div>
                    <div class="row" >
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1);'></div>
           
				<div id="types">
				<div id="element_type">
				<div id="select_element_type" style="margin: 5%;">
				<div id="buotton_files" style="width: 75%; padding-bottom:50px;">
								<!-- -->
								<button type="button" class="btn btn-warning new_rule" data-toggle="modal" data-target="#myModal_new" style="float:left; margin-right: 5px;">
				<i class="fa fa-plus"></i> 
					 Create New Rule
					</button>
							<!-- 	  -->
							
								<div class="dropdown" style="float:left; margin-right: 5px;">
  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="title_button" type_sel="">Select for Element Type	</span><i class="fa fa-caret-down" aria-hidden="true"></i>
  </button>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="list_dash">
  </div>
</div>
<div class="dropdown" style="float:left; margin-right: 5px;">
  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonorg" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="title_button_org" org_sel="">Select for Organization	</span><i class="fa fa-caret-down" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="list_orgs">
</div>
</div>
<button type="button" class="btn btn-primary rest" id="rest_butt" style="float:left; margin-right: 5px;">
					 Reset
					</button>
</div>
<div id="View_rules" style="width: auto; margin: 5%;">
<!--<textarea rows = "value"></textarea>-->
<div class="form-group">
	<!-- -->
	<div class="table-responsive-xl scrollit">
<table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" id="exampleFormControlSelect1" class="scroll">
  <thead>
    <tr>
      <th scope="col" onclick="order_column('elementType')">Element type  <span id="status_elementType" status="void" class="caret_sign"></span></th>
      <th scope="col" onclick="order_column('organization')">Organization  <span id="status_organization" status="void" class="caret_sign"></span></th>
      <th scope="col" onclick="order_column('username')">Username  <span id="status_username" status="void" class="caret_sign"></span></th>
	  <th scope="col" onclick="order_column('role')">Role  <span id="status_role" status="void" class="caret_sign"></span></th>
      <th scope="col" onclick="order_column('maxCount')">Limits  <span id="status_maxCount" status="void" class="caret_sign"></span></th>
	  <th scope="col" >Controls</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
    <tfoot>
  </tfoot>
  </table>
  </div>
<!-- -->
  </div>
</div>
</div>

<!-- -->

				</div>
				
<!--- USERS -->
<div id="users" style="display:none;">
<div id="manage_element" style="margin: 5%; " class="panel panel-default">
				<div class="panel-heading"><span>Manage Limit number on Users</span>
				</div>
				<div id="element_type">
				<div class="panel-body">
				<div id="select_element_users" style="float: left; width: 45%; margin-left: 5%;"><h4>Select for Users:</h4>
								<!-- -->
								<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Users	 <i class="fa fa-caret-down" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="list_users">
  </div>
</div>
</div>
<div id="View_rules_users" style="float: right; width: 45%; margin-right: 5%;">
<!--<textarea rows = "value"></textarea>-->
<div class="form-group">
    <label for="exampleFormControlSelect2">View the actual rules/limits:</label>
<!-- -->
<div class="table-responsive-xl">
<table id="exampleFormControlSelect2" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th scope="col">organization</th>
      <th scope="col">elementType</th>
	  <th scope="col">role</th>
      <th scope="col">maxCount</th>
      <th scope="col">Controls</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
  <tfoot>
  </tfoot>
  </table>
  </div>
<!-- -->	
  </div>
</div>
</div>
</div>
</div>
</div> 
<!-- -->
		</div>
	<!-- Modal -->
	<!-- Modal -->

<!-- Fine modal creazione processo -->
<!-- FIne Button-->
	
    </div>
    
  </div>
</div>
 </div>
<!-- Button-->
<!-- Trigger the modal with a button -->
<!-- FIne Button-->
<!-- Fine modal creazione processo -->
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
	<!--<form action="getuserlimits.php?showFrame=<?=$_REQUEST['showFrame']; ?>" method="post">-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="edit_title">Edit User Limits</h4>
      </div>
      <div class="modal-body"  style="display:none;">
		  <input id="action_edit" name="action" type="text" class="form-control" value="mod_type" style="display:none;"/>
		  <div class="input-group"><span class="input-group-addon">Element type: </span><input id="elementtype" name="elementtype" type="text" class="form-control" readonly/></div><br />
	       <div class="input-group" ><span class="input-group-addon">User: </span><input id="user" name="user" type="text" class="form-control" readonly/></div><br />
		   
		   <div class="input-group"><span class="input-group-addon">Organization: </span><input id="organization" name="organization" type="text" class="form-control" readonly/></div><br />
		   <div class="input-group"><span class="input-group-addon">Role: </span><input id="role" type="text" name="role" class="form-control" readonly/></div><br />
			<div class="input-group"><span class="input-group-addon">Limits: </span><input id="limits" name="limits"  type="text" class="form-control" /></div><br />
				
      </div>
	  <div class="modal-body">
	  
		  <div class="input-group"><span class="input-group-addon">Element type: </span><input id="elementtype_n" name="elementtype_n" type="text" class="form-control" readonly/></div><br />
           <div class="input-group"><span class="input-group-addon">Organization: </span><input id="organization_n" name="organization_n" type="text" class="form-control" readonly/></div><br />		  
		  <div class="input-group"><span class="input-group-addon">Username: </span><input id="user_n" name="user_n" type="text" class="form-control" readonly/></div><br />	   
		   <div class="input-group"><span class="input-group-addon">Role: </span><input id="role_n" type="text" name="role_n" class="form-control" readonly/></div><br />
			<div class="input-group"><span class="input-group-addon">Limits: </span><input id="limits_n" name="limits_n" type="text" class="form-control" /></div><br />
				
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" class="btn btn-primary" id="edit_rule" value="Confirm" />
      </div>
    </div>
	<!--</form>-->
  </div>
</div>
<!-- Modal -->
<div id="myModal_new" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create User Limits Rule</h4>
      </div>
	  <!--<form action="getuserlimits.php?showFrame=<?=$_REQUEST['showFrame']; ?>" method="post"> -->
      <div class="modal-body">
			<input id="action_new" name="action" type="text" class="form-control" value="new_type" style="display:none;"/>
			<div class="input-group"><span class="input-group-addon">Element type: </span><select id="elementtype_c" name="elementtype_c"  class="form-control input-group-addon"></select></div><br />
	       <div class="input-group"><span class="input-group-addon">User: </span><input id="user_c" name="user_c" type="text" class="form-control" /></div><br />
		   <div class="input-group"><span class="input-group-addon">Organization: </span><select id="organization_c" name="organization_c" class="form-control input-group-addon"></select></div><br />
		   <div class="input-group"><span class="input-group-addon">Role: </span><select id="role_c" name="role_c" onchange="changhe_rule()"   class="form-control input-group-addon"><option value="any">any</option><option value="Manager">Manager</option><option value="AreaManager">AreaManager</option><option value="ToolAdmin">ToolAdmin</option><option value="RootAdmin">RootAdmin</option></select></div><br />
			<div class="input-group"><span class="input-group-addon">Limits: </span><input id="limits_c" name="limits_c" type="text" class="form-control" /></div><br />
				
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" class="btn btn-primary" id="create_rule" value="Confirm" />
      </div>
	  		<!--</form>	-->
    </div>

  </div>
</div>
<!-- Modal -->
<div id="myModal_delete" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<!--<form action="getuserlimits.php?showFrame=<?=$_REQUEST['showFrame']; ?>" method="post">-->
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete relation</h4>
      </div>
      <div class="modal-body">
	  Are you sure you want delete this rule?					
      </div>
      
	  <div style="display:none;">
			<input id="action_del" name="action" type="text" class="form-control" value="del_type" />
			<div class="input-group"><span class="input-group-addon">Element type: </span><input id="elementtype_d" name="elementtype_d" type="text" class="form-control" /></div><br />
			<div class="input-group"><span class="input-group-addon">User: </span><input id="user_d" name="user_d" type="text" class="form-control" /></div><br />
		    <div class="input-group"><span class="input-group-addon">Organization: </span><input id="organization_d" name="organization_d" type="text" class="form-control" /></div><br />
		    <div class="input-group"><span class="input-group-addon">Role: </span><input id="role_d" name="role_d" type="text" class="form-control" /></div><br />
			<div class="input-group"><span class="input-group-addon">Limits: </span><input id="limits_d" name="limits_d" type="text" class="form-control" /></div><br />
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary" id="delete_rule">Confirm</button>
      </div>
    </div>
	<!--</form>-->
  </div>
</div>
<!-- Fine modal creazione processo -->
<script type='text/javascript'>
			   var nascondi = "<?=$hide_menu; ?>";
                var corrent_page = "<?=$page; ?>";
                var titolo_default = "<?=$default_title; ?>";
                var start_from = "<?=$start_from; ?>";
                var limit_val = $('#limit_select').val();
				var role = "<?=$role_att; ?>";
				var array_del="<?=$array_del; ?>";
				var duplicated = "<?=$duplicated; ?>";
				
    $(document).ready(function () {
		//
		//window.location.href =  window.location.href.split("?")[0];
		
		//$('#storico').dynatable();
		if (nascondi == 'hide') {
			console.log('Hide');
                        $('#mainMenuCnt').hide();
                        $('#title_row').hide();
                    }

		var role_active = $("#role_att").text();
	if (role_active == 'ToolAdmin'){
		$('#sc_mng').show();
	}
	
	/*if (duplicated == 'yes'){
		alert("You can't create a new rule with these parameters beacause is yet existing.");
	}*/
	
	/*$('#exampleFormControlSelect1').DataTable({
								"searching": false,
								"paging": false,
								"ordering": false,
								"info": false,
								"responsive": true
							});*/
	
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
		//
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}

   
	//
	   var array_act = new Array();
	   $.ajax({
			url: "getuserlimits.php",
                data: {action: "get_values"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
						///array_act = JSON.parse(data);
					for (var i = 0; i < data.length; i++){
						$('#list_dash').append("<li class='dropdown-item' onclick=search_type('"+data[i]+"')><a href='#' >"+data[i]+"</a></li>");
					}
					
				}
	   });
	   

	   
	   var array_act2 = new Array();
	   $.ajax({
			url: "getuserlimits.php",
                data: {action: "get_orgs"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
						///array_act = JSON.parse(data);
					for (var i = 0; i < data.length; i++){
						$('#list_orgs').append("<li class='dropdown-item' onclick=search_orgs('"+data[i]+"')><a href='#' >"+data[i]+"</a></li>");
					}
					
				}
	   });
	   
	   //CREATE RULE
	    $(document).on('click','#create_rule',function(){
			var elementtype_c = $('#elementtype_c').val();
			var user_c = $('#user_c').val();
			var organization_c = $('#organization_c').val();
			var role_c = $('#role_c').val();
			var limits_C = $('#limits_c').val();
			//
			 $.ajax({
						url: "getuserlimits.php",
						data: {action: "new_type", 
							   elementtype_c: elementtype_c,
							   user_c: user_c,
							   organization_c: organization_c,
							   role_c: role_c,
							   limits_c: limits_C
							   },
						type: "POST",
						async: true,
						dataType: 'json',
						success: function (data) {
								///array_act = JSON.parse(data);
								console.log(data.result);
								var message = data.message;
								var result = data.result;
								if (result == 'ok'){
									///REFRESH TABELLA.
									$('#myModal_new').modal('hide');
									$('#title_button_org').attr('org_sel', organization_c);
									search_type(elementtype_c);
									search_orgs(organization_c);
									//
								}else if(result =='duplicated'){
									alert("You can't create a new rule with these parameters beacause is yet existing.");
								}else if (result == 'username not valid'){
									alert("You can't edit a rule with these parameters beacause the username is not valid.");
								}else{
									alert(message);
								}						
						}
				});
			//
			
		});
		
		//EDIT RULE
		$(document).on('click','#edit_rule',function(){
			var elementtype = $('#elementtype').val();
			var user = $('#user').val();
			var organization = $('#organization').val();
			var role = $('#role').val();
			var limits = $('#limits').val();
			//
			var elementtype_n = $('#elementtype_n').val();
			var user_n = $('#user_n').val();
			var organization_n = $('#organization_n').val();
			var role_n = $('#role_n').val();
			var limits_n = $('#limits_n').val();
			$.ajax({
						url: "getuserlimits.php",
						data: {action: "mod_type", 
							   elementtype_n: elementtype_n,
							   user_n: user_n,
							   organization_n: organization_n,
							   role_n: role_n,
							   limits_n: limits_n,
							   elementtype: elementtype,
							   user: user,
							   organization: organization,
							   role: role,
							   limits: limits
							   },
						type: "POST",
						async: true,
						dataType: 'json',
						success: function (data) {
								///array_act = JSON.parse(data);
								console.log(data.result);
								var message = data.message;
								var result = data.result;
								if (result == 'ok'){
									///REFRESH TABELLA.
									$('#myModal').modal('hide');
									$('#title_button_org').attr('org_sel', organization_n);
									search_type(elementtype_n);
									search_orgs(organization_n);
									//
								}else if(result =='duplicated'){
									alert("You can't edit a rule with these parameters beacause is yet existing.");
								}else{
									alert(message);
								}						
						}
				});
			////
			////
		});
		
		//DELETE RULE
		$(document).on('click','#delete_rule',function(){
			var elementtype_d = $('#elementtype_d').val();
			var user_d = $('#user_d').val();
			var organization_d = $('#organization_d').val();
			var role_d = $('#role_d').val();
			var limits_d = $('#limits_d').val();
					//--------------//
					 $.ajax({
						url: "getuserlimits.php",
						data: {action: "del_type", 
							   elementtype_d: elementtype_d,
							   user_d: user_d,
							   organization_d: organization_d,
							   role_d: role_d,
							   limits_d: limits_d
							   },
						type: "POST",
						async: true,
						dataType: 'json',
						success: function (data) {
								///array_act = JSON.parse(data);
								console.log(data.result);
								var message = data.message;
								var result = data.result;
								if (result == 'ok'){
									///REFRESH TABELLA.
									$('#myModal_delete').modal('hide');
									//$('#title_button_org').attr('org_sel', organization_d);
									search_type(elementtype_d);
									search_orgs(organization_d);
									//myModal_delete
									//
								}else{
									alert("Error during rule deleting.");
									}						
								}
							});
//--------------//			
		});
	   
	   
	   
	   $(document).on('click','.modify_jt',function(){
		   console.log('OK');
		   var org = $(this).parent().parent().get(0).children[1].innerText;
		   var user = $(this).parent().parent().get(0).children[2].innerText;
		   var role = $(this).parent().parent().get(0).children[3].innerText;
		   var limits = $(this).parent().parent().get(0).children[4].innerText;
		   var elementType= $(this).parent().parent().get(0).children[0].innerText;
		   console.log(org);
		   $('#user').val(user);
		   $('#organization').val(org);
		   $('#limits').val(limits);
		   $('#role').val(role);
		   $('#elementtype').val(elementType);
		   $('#edit_title').text('Edit User Limits: '+elementType);
		   
		   //title_button
		   //
		  $.ajax({
			url: "getuserlimits.php",
                data: {action: "get_orgs"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
						///array_act = JSON.parse(data);
						$('#organization_n').empty();
					for (var i = 0; i < data.length; i++){
						$('#organization_n').append("<option value='"+data[i]+"'>"+data[i]+"</option>");
					}
					
				}
				});
		   //
		   $('#user_n').val(user);
		   $('#organization_n').val(org);
		   //$('#organization_n option[value="'+org+'"]').attr("selected", "selected");
		   $('#limits_n').val(limits);
		   $('#role_n').val(role);
		   $('#elementtype_n').val(elementType);
		   //
		   //
	   });
	   
	   $(document).on('click','#rest_butt',function(){
		   $('#exampleFormControlSelect1 tbody').empty();
		   $("#title_button_org").attr('org_sel','');
$("#title_button_org").text('Select for Organization	');
$("#title_button").attr('type_sel','');
$("#title_button").text('Select for Element Type	');
$('.caret_sign').empty();
$(".caret_sign").attr('status','void');
	   });
	   
	   
	   $(document).on('click','.delete_file',function(){
		   var org = $(this).parent().parent().get(0).children[1].innerText;
		   var user = $(this).parent().parent().get(0).children[2].innerText;
		   var limits = $(this).parent().parent().get(0).children[4].innerText;
		   var role = $(this).parent().parent().get(0).children[3].innerText;
		   var elementType= $(this).parent().parent().get(0).children[0].innerText;
		   console.log(elementType);
		   $('#user_d').val(user);
		   $('#organization_d').val(org);
		   $('#limits_d').val(limits);
		   $('#role_d').val(role);
		   $('#elementtype_d').val(elementType);
	   });
	   
	    $(document).on('click','.new_rule',function(){
		  
		   var array_act = new Array();
		   $('#elementtype_c').empty();
		   
	   $.ajax({
			url: "getuserlimits.php",
                data: {action: "get_orgs"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
						///array_act = JSON.parse(data);
						$('#organization_c').empty();
						$('#organization_c').append("<option value='any'>any</option>");
					for (var i = 0; i < data.length; i++){
						$('#organization_c').append("<option value='"+data[i]+"'>"+data[i]+"</option>");
					}
					
				}
	   });
	    $.ajax({
			url: "getuserlimits.php",
                data: {action: "get_values"},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
						///array_act = JSON.parse(data);
					for (var i = 0; i < data.length; i++){
						$('#elementtype_c').append("<option value='"+data[i]+"'>"+data[i]+"</option>");
					}
					
				}
	   });
		   //$('#elementtype_c').val(elementType);
	   });
	   
});
	   
	   function search_users(type){
		   console.log(type);
		   $.ajax({
			url: "getuserlimits.php",
                data: {action: "details_username", type: type},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
						///array_act = JSON.parse(data);
					console.log(data);
					$('#exampleFormControlSelect2 tbody').empty();
					$('#exampleFormControlSelect2 tfoot').empty();
					for (var i = 0; i < data.length; i++){
						///
						$('#exampleFormControlSelect2 tbody').append('<tr role='+data[i]['role']+'><td>'+data[i]['organization']+'</td><td>'+data[i]['elementType']+'</td><td>'+data[i]['maxCount']+'</td><td><button class="editDashBtn modify_jt" data-target="#data-modal3" data-toggle="modal">EDIT</button>	<button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal" value="12">DEL</button></td></tr>');
					}
					//$('#exampleFormControlSelect2 tfoot').append('<tr><td><button class="viewDashBtn mostra_j" data-target="#show_jobs" data-toggle="modal" type="button" value="1" file="900">NEW</button></td></tr>');
					//$('#exampleFormControlSelect2 tbody').append('<option></option>');
				}
	   });
	   }

	
	
	function search_type(type){
		   console.log(type);
		   var org_sel = $('#title_button_org').attr('org_sel');
		   $.ajax({
			url: "getuserlimits.php",
                data: {action: "details", type: type, org_sel:org_sel},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
						///array_act = JSON.parse(data);
					console.log(data[0]);
					$('#title_button').text('Element type: '+type);
					$('#title_button').attr('type_sel',type);
					$('#exampleFormControlSelect1 tbody').empty();
					$('#exampleFormControlSelect1 tfoot').empty();
					//$('#exampleFormControlSelect1').append('<option>'+data[0]['username']+'</option>');
					//$('#exampleFormControlSelect1').append('<option>'+data[0]['organization']+'</option>');
					for (var i = 0; i < data.length; i++){
						//$('#exampleFormControlSelect1 tbody').append('<option>'+data[i]['organization']+'	 '+data[i]['username']+'	 '+data[i]['maxCount']+'	 <button class="editDashBtn modify_jt" data-target="#data-modal3" data-toggle="modal">EDIT</button>	<button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal" value="12">DEL</button></option>');
						$('#exampleFormControlSelect1 tbody').append('<tr role='+data[i]['role']+'  elementType='+data[i]['elementType']+' ><td>'+data[i]['elementType']+'</td><td>'+data[i]['organization']+'</td><td>'+data[i]['username']+'</td><td>'+data[i]['role']+'</td><td>'+data[i]['maxCount']+'</td><td><button class="editDashBtn modify_jt" data-toggle="modal" data-target="#myModal">EDIT</button>	<button type="button" class="delDashBtn delete_file" data-target="#myModal_delete" data-toggle="modal" value="12">DEL</button></td></tr>');
					}
					//$('#exampleFormControlSelect1 tbody').append('<option></option>');
					//maxCount
					//$('#exampleFormControlSelect1 tfoot').append('<tr><td><button class="viewDashBtn new_rule" elementType='+data[0]['elementType']+' data-target="#myModal_new" data-toggle="modal" type="button" value="1" file="900">NEW</button></td></tr>');
					
				}
	   });
	   }
	   
	   function changhe_rule(){
		   var role = $('#role_c').val();
		   if (role !== 'any'){
			   $('#user_c').val('any');
			   $('#user_c').prop('readonly', true);
		   }else{
			    $('#user_c').val();
				$('#user_c').prop('readonly', false);
			   //
		   }
		   console.log(role);
	   }
	   
	  
	  function order_column(column){
		  //exampleFormControlSelect1
		  var x = document.getElementById("exampleFormControlSelect1").getElementsByTagName("td").length; 
		  console.log(x);
		  //var tbl = document.getElementById(x);
		if (x > 0) {	
		  var column1 = column;
		  var order = 'ASC';
		  var status_col = $('#status_'+column1).attr('status');
		  console.log('#status_'+column1);
		  console.log(status_col);
		  $('.caret_sign').empty();
		  $(".caret_sign").attr('status','void');
		  if (status_col == "void"){
			   order = 'DESC';
				 $('#status_'+column1).html('<i class="fa fa-caret-down" aria-hidden="true"></i>');
				 $('#status_'+column1).attr('status', order);
		  }else if(status_col == "DESC"){
			  order = 'ASC';
			  $('#status_'+column1).html('<i class="fa fa-caret-up" aria-hidden="true"></i>');
			  $('#status_'+column1).attr('status', order);
		  }else if(status_col == "ASC"){
				order = 'DESC';
				 $('#status_'+column1).html('<i class="fa fa-caret-down" aria-hidden="true"></i>');
				 $('#status_'+column1).attr('status', order);
		  }else{
			  //
			  console.log('nothing');
		  }
		  
		  var type_sel = $('#title_button').attr('type_sel');
		  var org_sel = $('#title_button_org').attr('org_sel');
		   $.ajax({
			url: "getuserlimits.php",
                data: {action: "order",  type_sel: type_sel, org_sel: org_sel, order: order, column: column},
                type: "POST",
                async: true,
                dataType: 'json',
                success: function (data) {
						///array_act = JSON.parse(data);
					console.log(data[0]);
					$('#exampleFormControlSelect1 tbody').empty();
					$('#exampleFormControlSelect1 tfoot').empty();
					for (var i = 0; i < data.length; i++){
					
						$('#exampleFormControlSelect1 tbody').append('<tr role='+data[i]['role']+'  elementType='+data[i]['elementType']+' ><td>'+data[i]['elementType']+'</td><td>'+data[i]['organization']+'</td><td>'+data[i]['username']+'</td><td>'+data[i]['role']+'</td><td>'+data[i]['maxCount']+'</td><td><button class="editDashBtn modify_jt" data-toggle="modal" data-target="#myModal">EDIT</button>	<button type="button" class="delDashBtn delete_file" data-target="#myModal_delete" data-toggle="modal" value="12">DEL</button></td></tr>');
					}
					
					
				}
	   });
		  }
	  }
	   
	  function search_orgs(type){
			var type_sel = $('#title_button').attr('type_sel');
					 $.ajax({
			url: "getuserlimits.php",
                data: {action: "details_orgs", type: type, org_sel:type_sel},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
						///array_act = JSON.parse(data);
					console.log(data[0]);
					$('#title_button_org').text('Organization: '+type);
					$('#title_button_org').attr('org_sel',type);
					$('#exampleFormControlSelect1 tbody').empty();
					$('#exampleFormControlSelect1 tfoot').empty();
					//
					for (var i = 0; i < data.length; i++){
						//$('#exampleFormControlSelect1 tbody').append('<option>'+data[i]['organization']+'	 '+data[i]['username']+'	 '+data[i]['maxCount']+'	 <button class="editDashBtn modify_jt" data-target="#data-modal3" data-toggle="modal">EDIT</button>	<button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal" value="12">DEL</button></option>');
						$('#exampleFormControlSelect1 tbody').append('<tr role='+data[i]['role']+'  elementType='+data[i]['elementType']+' ><td>'+data[i]['elementType']+'</td><td>'+data[i]['organization']+'</td><td>'+data[i]['username']+'</td><td>'+data[i]['role']+'</td><td>'+data[i]['maxCount']+'</td><td><button class="editDashBtn modify_jt" data-toggle="modal" data-target="#myModal">EDIT</button>	<button type="button" class="delDashBtn delete_file" data-target="#myModal_delete" data-toggle="modal" value="12">DEL</button></td></tr>');
					}
					//
					
				}
	   });
	  }
	   

</script>
</body>
</html>