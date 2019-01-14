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
		//echo ('true');
		$hide_menu= "hide";
	}else{
		$hide_menu= "";
	}	
}else{$hide_menu= "";}

if (!isset($_GET['pageTitle'])){
	$default_title = "Micro Service Editor";
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

if (isset($_REQUEST['microServ'])){
	$microServ = $_REQUEST['microServ'];
}else{
	$microServ = "";
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
		
		 <!-- CKEditor --> 
    <!--<script src="http://cdn.ckeditor.com/4.5.10/standard/ckeditor.js"></script>-->
	<script src="ckeditor/ckeditor.js"></script>
	
		<!-- -->
		<!-- -->
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
			
			<div  id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1)'>
			<!-- Upload New File-->
			
			<div id="view-menu" style="margin-left:45px; margin-bottom:20px; margin-top:20px;">
			<button type="button" id="hide_button" value="Hide Table" class="btn btn-warning" data-toggle="modal" data-target="#Hide" onclick="hide_table()">Add MicroService</button>
			<!--
			<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#Upload">
				<i class="fa fa-upload"></i> 
					  upload New Resource
					</button>
			-->
			</div>
			<div id="collapse_editor" style="display: none;">
			  <!--Editor Location-->
			  <!-- -->
			  <div class="container">
				<form enctype="multipart/form-data" action="caricaZip.php" id="upload" method="POST" accept-charset="UTF-8">
				<div class="panel panel-default">		
						<div class="panel-heading"><h3>Add MicroService</h3></div>
					<div class="panel-body">
								
								<div class="col-md-6">
											   <div class="card">
												  <div class="card-body"> 												 
														<input id="filetype" name="filetype" value="MicroService" style="display: none;"></input>
														<input id="editor_page" name="editor" value="MicroService" style="display: none;"></input>
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
														 -->
														</div>
														<br />
														<div class="input-group" id ="micro_name" ><span class="input-group-addon">MicroService Title: </span><input name="micro_name" type="text" class="form-control" id="title_micro0"></input>
														</div>
														<br />
														<div class="input-group" id ="micro_url" ><span class="input-group-addon">Url: </span><input name="url" type="text" class="form-control" id="url_input0" required="required"></input>
														</div>
														<br />
														<div id="elenco_parametri">
														   <div id="param_container">
															  <div class="input-group">
																 <span class="input-group-addon">parameter 1: </span><input type="Text" class="form-control paramItem" name="paramList[0]"></input>
															  </div>
															  
														   </div>
														   <br />
														   <input type="button" id="Add_par" class="btn btn-primary btn-sm" value="Add Parameter" />
														   <input type="button" id="Rem_par" class="btn btn-primary btn-sm" value="Remove Parameter" />
														   <br />
														</div>
														<br />
														<div class="input-group" id ="micro_method" >
														   <span class="input-group-addon">Method: </span>
														   <select name="metod" type="text" class="form-control">
															  <option>GET</option>
															  <option>POST</option>
														   </select>
														</div>
														<br />
														<div class="input-group" id ="micro_check" >
														   Do you want create a Microservice with Authentication?    <input type="checkbox" name="auth_check" value="Yes"/>
														</div>
														<br/>
												  </div>
											   </div>
											</div>
											<div class="col-md-6">
											   <div class="card">
												  <div class="card-body">
														<span class="input-group">Help: </span><textarea name="help" id="help_input"></textarea><!--<input name="help" type="text" class="form-control" id="help_input"></input>-->
													</div>
											</div>
										</div>								
								
							</div>
								<div class = "panel-footer"><input type="button" name="chiudi_job" id="chiudiAdd" class="btn cancelBtn" value="Cancel" data-dismiss="modal"><input type="submit" value="Confirm" class="btn confirmBtn"/></div>
							</div>
							</form>
						</div>
			  <!-- -->
			  <!-- -->
			</div>
			<!-- Modify MicroService -->
					<!-- -->
			<div class="edit_microserv" style="display: none;">
				<form name="param_login_1" method="post" action="modify.php" id="modal_modify" enctype="multipart/form-data" > 
			  <div class="container">
				
				<div class="panel panel-default">		
						<div class="panel-heading"><h4 class="modal-title" id="titolo_proc3"></h4></div>
					<div class="panel-body">
								
								<div class="col-md-6">
											   <div class="card">
												  <div class="card-body"> 												 
					<div class="form-parametri" id="Process_1">
						
						<div class="panel-body">
							<div class="tab-content">
								<div id="process_mandatory_parameter" class="tab-pane fade in active">
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>Id: </span><input id="id3" type="text" class="form-control" name="id3" hidden readonly></div>
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>File: </span><input id="file_zip3" type="text" class="form-control" name="file_zip3" hidden readonly></div>
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>Publication Date: </span><input id="data_pub3" type="text" class="form-control" name="data_pub3" hidden readonly></div>
									<div class="input-group" style="display:none;" hidden><span class="input-group-addon" hidden>App Type: </span><input id="tipo_zip3" type="text" class="form-control" name="tipo_zip3" hidden readonly></div>
									<input id="editor_page3" name="editor" value="MicroService" style="display: none;"></input>	
									<div class="input-group"><span class="input-group-addon">Nature: </span>
									<input id="category3" type="text" class="form-control" name="category3" >
									</div>
									<br />
									<div class="input-group"><span class="input-group-addon">Sub Nature: </span><input id="resource3" type="text" class="form-control" name="resource3" required="required"></div>
									<br />
									<div class="input-group"><span class="input-group-addon">Licence: </span>
									<input id="licence3" type="text" class="form-control" name="licence3" >
									</div>			
									<br />
									<div class="input-group"><span class="input-group-addon">Description: </span>
									<input id="desc3" type="text" class="form-control" name="desc3" >
									</div>											
									<br />
									<!--
									<div class="input-group"><span class="input-group-addon">Select Image: </span>
									<input id="img3" type="file" class="form-control" name="img3"  accept="image/*">
									</div>	
									-->
									<div><span>Select Image: </span>
									<input id="img3" type="file" name="img3"  accept="image/*">
									</div>
									<!-- -->
									<br />
									<div class="input-group" id="div-method3"><span class="input-group-addon">Method: </span>
									<input id="method3" type="text" class="form-control" name="method3" >
									</div>
									<br />
									<div class="input-group" id ="micro_check3" >
										Do you want create a Microservice with Authentication?    <input type="checkbox" id="auth_check3" name="auth_check3" value="Yes"/>
									</div>
									<br />														
									<div class="input-group" id="div-url3"><span class="input-group-addon">Url: </span>
									<input id="url3" type="text" class="form-control" name="url3" required="required">
									</div>
									<br />
									<div class="contatore_param3" hidden></div>
									<div id="param_container3"></div>
									<br />
									<input type="button" id="Rem_par3" class="btn btn-primary btn-sm" value="Remove Parameter" />
									<input type="button" id="Add_par3" class="btn btn-primary btn-sm" value="Add Parameter" />
									<br />
									<div id="param_container3_vecchi" hidden></div>
									
								</div>
								
							</div>	
						</div>
					</div>				
				</div>
				</div>
				 </div>
				<div class="col-md-6">
					 <div class="card">
					 <div class="card-body"> 
					<div class="input-group" id="div-help3"><span>Help: </span>
					</div>
					
							<textarea name="help3" id="help3" class="ckeditor"></textarea>
							</div>
							</div>
							<div style="display: none;">
								<div class="input-group" id="div-format3"><span class="input-group-addon">Format: </span>
								<input id="format3" type="text" class="form-control" name="format3" >
								</div>	
								<div class="input-group" id="div-protocol3"><span class="input-group-addon">Access: </span>
								<input id="protocol3" type="text" class="form-control" name="protocol3" id="protocol3">
								</div>		
								</div>
						</div>
						</div>								
					<div class = "panel-footer"><input type="button" name="chiudi_job" id="chiudiEdit" class="btn cancelBtn" value="Cancel" data-dismiss="modal"><input type="submit" value="Confirm" class="btn confirmBtn"/></div>
							</div>
							</div>			
							</form>
						</div>	
						
			  <!-- -->
			<!-- -->
			<div id="collapse_table">
				<table id="elenco_files" class="table table-striped table-bordered">
					<thead class="dashboardsTableHeader">
					<tr>
						<th hidden>N</th>
						<th hidden>id</th>
						<th>File Name</th>
						<th class="username_td" hidden>Username</th>
						<th>Upload Date</th>
						<th>Description</th> 
						<th>Control Status</th>
						<th>View</th>
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
	</div>
	

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
				<input id="editor_page_del" name="editor" value="MicroService" style="display: none;"></input>	
			</div>
			<div class="panel-footer">
				<button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn confirmBtn" value="Confirm">  
			</div>
		</div>
				</form>
	</div>
</div>
<!-- View Help Modal-->

<div id="view_help" class="modal fade bd-example-modal-lg" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background-color: white">
					<h4 class="modal-title" id="title_view_Help"></h4>
			</div>
			<div class="modal-body"  style="background-color: white">
				<div class="panel panel-default">
					<div class="panel-body" id="view_help_content"></div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="form-group">
					<button type="button" class="btn cancelBtn" data-dismiss="modal" id="Close_help_view">Cancel</button>
				</div>				
			</div>
		</div>
	</div>
</div>
<!-- View Help-->

 <!--Form Loader-->
 <div class="modal fade bd-example-modal-lg" id="Upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog">
  
    <div class="modal-content">
<div class="modal-header" style="background-color: white"> Upload new file <span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></div>
<div class="modal-body" style="background-color: white">
<form enctype="multipart/form-data" action="caricaZip.php" id="uploadRes" method="POST" accept-charset="UTF-8">
	<input id="filetype1" name="filetype" value="MicroService" style="display: none;"></input>	
	<input id="editor_page1" name="editor" value="MicroService" style="display: none;"></input>		
	<div>
	<div class="input-group"><span class="input-group-addon">Description : </span><input name="filedesc" type="text" class="form-control" required="required" /></div>
	</div>
	<br />	
	<div class="input-group"><span class="input-group-addon">Nature : </span><input name="filenat" type="text" class="form-control" required="required"/>
	</div>
	<br />
	<div class="input-group"><span class="input-group-addon">SubNature : </span><input name="filesubN" type="text" class="form-control" required="required"/>
	</div>
	<br/>
	<div class="input-group" id ="micro_name1" ><span class="input-group-addon">MicroService Title: </span><input name="micro_name" type="text" class="form-control" id="title_micro"></input>
	</div>
	<br />
	<div class="input-group" id="micro_help1" ><span>Help: </span><input name="help" type="textarea" id="help_input0" class="ckeditor"></input>
	</div>
	<br />
	<div class="input-group" id ="micro_url1" ><span class="input-group-addon">Url: </span><input name="url" type="text" class="form-control" id="url_input" required="required"></input>
	</div>
	<br />
	<div id="elenco_parametri">
	<div id="param_container">
	<div class="input-group">
	<span class="input-group-addon" id="par_1">parameter 1: </span><input type="Text" class="form-control paramItem" name="paramList0[0]"></input>
	</div>
	</div>
	<br />
	<br />
	<input type="button" id="Add_par1" class="btn btn-primary btn-sm" value="Add Parameter" />
	<br />
	</div>
	<br />
	<div class="input-group" id ="micro_method1" ><span class="input-group-addon">Method: </span><select name="metod" type="text" class="form-control"><option>GET</option><option>POST</option></select>
	</div>
	<br />

	<div class="input-group" id ="micro_check1" >
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
				<input id="editor_page_pub" name="editor" value="MicroService" style="display: none;"></input>	
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
<!-- Fine modal creazione processo -->
 <!-- -->
	<script type='text/javascript'>
	var microServ = "<?=$microServ; ?>";
	
	if (microServ != ""){
					var index_m = 0;
				}
	
	function hide_table() {
		if ($('#collapse_table').is(":visible")){
			$('#collapse_table').hide();
			$('#collapse_editor').show();
			$("#hide_button").attr('value', 'Show MicroService List'); 
			$("#hide_button").text('Show MicroService List'); 
			//
			//var template_default = '<h3 class="palette-header">Description</h3><p>Information about a Microservice.</p><h3 class="palette-header">Inputs</h3><p>MicroService Input Parameter Descritption</p><h3 class="palette-header">Outputs</h3><dl class="message-properties" style="border: none; margin: 0px;"><p>MicroService Output Description</p><h3 class="palette-header">Configuration</h3><p>[Insert Screenshot]</p><h3 class="palette-header">Details</h3><p>Details about Microservice usage.</p>';
			var template_default = '<p>Description of microservice</p><h3>Inputs</h3>Microservice input description:<dl class="message-properties"><dt>Parameter Name<span class="property-type">string</span></dt><dd>Insert text here</dd><dt>Parameter Name <span class="property-type">string</span></dt><dd>Insert text here</dd><dt>Parameter Name<span class="property-type">string</span></dt><dd>Insert text here</dd></dl><h3>Outputs</h3><dl class="message-properties"><dd>Insert text here</dd></dl><h3>Details</h3><p>Insert text here</p>';
			CKEDITOR.instances['help_input'].insertHtml(template_default);
			//
		}else{
			$('#collapse_table').show();
			$('#collapse_editor').hide();
			$("#hide_button").attr('value', 'Add MicroService');
			$("#hide_button").text('Add MicroService'); 	
			CKEDITOR.instances['help_input'].setData('');			
		}
	}
	
	
	$(document).on('click','#chiudiAdd',function(){
		$('#collapse_table').show();
			$('#collapse_editor').hide();
			$("#hide_button").attr('value', 'Add MicroService');
			$("#hide_button").text('Add MicroService'); 	
			CKEDITOR.instances['help_input'].setData('');	
	});
	
	CKEDITOR.replace('help_input', {
						///customConfig : "documentation/index.html",
                        allowedContent: true,
						//extraPlugins : 'stylesheetparser',
						contentsCss : 'ckeditor/MicroService.css',
                        language: 'en',
                        width: '100%',
                        height: '40%'
                    });
					
	CKEDITOR.replace('help_input0', {
                        allowedContent: true,
						//extraPlugins : 'stylesheetparser',
						contentsCss : 'ckeditor/MicroService.css',
                        language: 'en',
                        width: '100%',
                        height: '20%'
                    });
					
	CKEDITOR.replace('help3', {
                        allowedContent: true,
						//extraPlugins : 'stylesheetparser',
						contentsCss : 'ckeditor/MicroService.css',
                        language: 'en',
                        width: '100%',
                        height: '40%'
                    });
		
		
		function FunctionMicro(){
			var valore_select = $('#filetype').val();
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
		}
	
	$(document).ready(function () {
		$(document).on('click','#Add_par',function(){
		var cont = $('.paramItem').length;
		var cont2 = cont -1; 
		var attuale = $(".contatore_param").val();
		//
		//
		$("#param_container").append('<div class="input-group" id="par_'+cont+'"><span class="input-group-addon">parameter '+cont+': </span><input type="Text" class="form-control paramItem" name="paramList['+cont2+']"></input></div>');
		$(".contatore_param").text(attuale +' 1');		
		});
		
		//Rem_par
		$(document).on('click','#Rem_par',function(){
		var cont = $('.paramItem').length;
		var cont0 = cont -1; 
		var cont2 = cont -2; 
		var attuale = $(".contatore_param").val();
		//
		console.log('cont:	'+cont);
		console.log('attuale: '+attuale);
		//
		//$("#param_container").remove('<div class="input-group"><span class="input-group-addon">parameter '+cont0+': </span><input type="Text" class="form-control paramItem" name="paramList['+cont2+']"></input></div>');
		$("#par_"+cont0).remove();
		$(".contatore_param").text(attuale +' 1');		
		});
		
		///////
		//Nature
							$.ajax({
								url: "nature_list.php",
								type:"GET",
								data: {list:'list'},
								async: true,
								dataType: 'json',
								success: function (data1) {
									//$('#select_s').empty();
									for (var y = 0; y < data1.length; y++){
										$('#filenat').append('<option value="'+data1[y]+'" >'+data1[y]+'</option>');
										$('#category3').append('<option value="'+data1[y]+'" >'+data1[y]+'</option>');
									}
									//
								}
							});
		//
								//Sub_Nature
								var array_nature = new Array();
								$.ajax({
									url: "nature_list.php",
									type:"GET",
									data: {nature:'nature1'},
									async: true,
									dataType: 'json',
									success: function (data1) {
										$('#filesubN').empty();
										$('#resource3').empty();
										for (var z = 0; z < data1.length; z++){
											//
											
											array_nature[z] = {	
													id: data1[z].process['id'],
													nature: data1[z].process['nature'],
													sub_nature: data1[z].process['sub_nature']
												};
											//
											$('#filesubN').append('<option value="'+array_nature[z]['sub_nature']+'" class="'+array_nature[z]['nature']+'" >'+array_nature[z]['sub_nature']+'</option>');
											$('#resource3').append('<option value="'+array_nature[z]['sub_nature']+'" class="'+array_nature[z]['nature']+'" >'+array_nature[z]['sub_nature']+'</option>');
										}
										//
									}
								});
								
								//Change
								$('#filenat').change(function() {
									var selected = $('#filenat').val();
									var array_process = new Array();
								
													$.ajax({
														url: "nature_list.php",
														type:"GET",
														data: {nature:'nature1'},
														async: true,
														dataType: 'json',
														success: function (data1) {
															$('#filesubN').empty();
															for (var y = 0; y < data1.length; y++){
																//
																
																//
																array_process[y] = {	
																		id: data1[y].process['id'],
																		nature: data1[y].process['nature'],
																		sub_nature: data1[y].process['sub_nature']
																	};
																//
																if (array_process[y]['nature'] == selected){
																$('#filesubN').append('<option value="'+array_process[y]['sub_nature']+'" class="'+array_process[y]['nature']+'" >'+array_process[y]['sub_nature']+'</option>');
																}
															}
														}
													});
									$("#filesubN option:not(."+selected +")").hide();
									$("."+selected).show();
								});
								///////////	

									$('#category3').change(function() {
									var selected = $('#category3').val();
									var array_process = new Array();
								
													$.ajax({
														url: "nature_list.php",
														type:"GET",
														data: {nature:'nature1'},
														async: true,
														dataType: 'json',
														success: function (data1) {
															$('#resource3').empty();
															for (var y = 0; y < data1.length; y++){
																//
																
																//
																array_process[y] = {	
																		id: data1[y].process['id'],
																		nature: data1[y].process['nature'],
																		sub_nature: data1[y].process['sub_nature']
																	};
																//
																if (array_process[y]['nature'] == selected){
																$('#resource3').append('<option value="'+array_process[y]['sub_nature']+'" class="'+array_process[y]['nature']+'" >'+array_process[y]['sub_nature']+'</option>');
																}
															}
														}
													});
									$("#resource3 option:not(."+selected +")").hide();
									$("."+selected).show();
								});
								///////////	
		
		///////
			$(document).on('click','#Rem_par3',function(){
		var cont = $('.paramItem3').length;
		var cont2 = cont - 1; 
		var attuale = $(".contatore_param3").val();
		$(".param"+cont).remove();
		console.log(cont);
		//$('#param_container3 div:last-child', this).remove();
		});
	
		$(document).on('click','#Add_par3',function(){
		var cont = $('.paramItem3').length;
		var cont2 = cont +1; 
		var attuale = $(".contatore_param3").val();
		//$("#param_container").append('parameter '+cont2+': <input type="Text" class="paramItem" name="paramItem['+cont+']"></input><br />');
		$("#param_container3").append('<div class="input-group param'+cont2+'"><span class="input-group-addon">parameter '+cont2+': </span><input type="Text" class="form-control paramItem3" name="paramList3['+cont+']"></input></div>');
		$(".contatore_param3").text(attuale +' 1');		
		});
		
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
			$('#upload').attr("action","caricaZip.php?showFrame=false");
		}
		
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
			
		var dati_not_valid = "<?=$not_valid;?>";
		 if (dati_not_valid  != ""){
			 alert('Any Metadata fields are not valids for publication. Please, insert valid values before to publish using the Edit menu under Metadata column!');
		 }
		
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
			alert("Uploaded file deleted!");
		}
		if (error_message == "error"){
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

		
		
		var array_files = new Array();

		$.ajax({
			url: "getdata.php",
				data: {action: "get_microservices"},
				type: "GET",
				async: true,
				dataType: 'json',
				success: function (data) {
//
				
				
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
						 url: data[i].file['url'],
						 Html: data[i].file['Html']
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
					var href = 'uploads/'+us+'/'+data4+'/'+array_files[i]['name'];
					var button_create_jt ="";
					var button_show_jt ="";

					
					if (array_files[i]['pub']==0){
						var publish_text = 'No';	
					}else{
						var publish_text = 'Yes';				
					}
					//
					$("#elenco_files").append('<tr><td>'+i+'</td><td class="file_id" value="'+array_files[i]['id']+'">'+array_files[i]['id']+'</td><td><a href="'+href+'" class="file_archive_link" download>'+array_files[i]['name']+'</a></td><td class="username_td">'+array_files[i]['utente']+'</td><td>'+array_files[i]['date']+'</td><td>'+array_files[i]['desc']+'</td><td class="status" >'+array_files[i]['status']+'</td><td><button type="button" class="viewDashBtn viewHelp" data-target="#view_help" data-toggle="modal">VIEW</button></td><td><button type="button" class="editDashBtn modify_jt" data-target="#data-modal3" data-toggle="modal">EDIT</button></td><td><button type="button" value="0" data-toggle="modal" class="pubDashBtn pubblicato" data-target="#publish">'+publish_text+'</button></td><td><button type="button" class="delDashBtn delete_file" data-target="#delete-modal" data-toggle="modal">DEL</button></td></tr>');	
					if (microServ != ""){
						if (array_files[i]['id'] == microServ){	
						index_m = i;
						}
					}
					//
			
				}
				
				/////OPEN MICROSERVICE/////
				if (microServ != ""){
			
			$('.edit_microserv').show();
			$('#collapse_table').hide();
			$('#view-menu').hide();
						//var ind = $(this).parent().parent().first().children().html();
						//var type = array_files[index_m]['type'];
								$("#div-url3").show();
								$("#div-method3").show();
								$("#div-protocol3").hide();
								$("#div-os3").hide();
								$("#div-opensource3").hide();
								$("#div-periodic3").hide();
								$("#div-realtime3").hide();	
								$("#div-format3").hide();
								$("#div-help3").show();
								$("#titolo_proc3").text("Edit MicroService: " + array_files[index_m]['name'] );
								$("#file_zip3").val(array_files[index_m]['name']);
								$("#category3").val(array_files[index_m]['category']);
								$("#resource3").val(array_files[index_m]['resource']);
								$("#format3").val(array_files[index_m]['format']);		
								$("#licence3").val(array_files[index_m]['licence']);
								$("#protocol3").val(array_files[index_m]['protocol']);
								$("#desc3").val(array_files[index_m]['desc']);
								console.log(array_files[index_m]['help']);
								CKEDITOR.instances['help3'].setData(array_files[index_m]['help']);
								$("#url3").val(array_files[index_m]['url']);
								$("#method3").val(array_files[index_m]['method']);
								$("#os3").val(array_files[index_m]['OS']);
								//
								$("select[name=opensource3]").val(array_files[index_m]['opensource']);
								$("select[name=realtime3]").val(array_files[index_m]['realtime']);
								$("select[name=periodic3]").val(array_files[index_m]['periodic']);
								$("#tipo_zip3").val(array_files[index_m]['type']);
								$("#id3").val(array_files[index_m]['id']);
								var array_js = new Array ();
								var array_js2 = new Array ();
							//
							var str= array_files[index_m]['Html'];
							//console.log(str);
							var regex = "var 1 = (msg.payload .1 ? msg.payload .1 : config .1);";
							var url_array = 'node.on("input", function(msg) {var uri = "'+array_files[0]['url']+'";';
							var array_js=str.substring(str.indexOf('</label>'),str.lastIndexOf('</label>'));
							var array_js2=array_js.split("</label>");
							$("#param_container3").empty();
							$("#param_container3_vecchi").empty();
							var lun = array_js2.length;
							console.log("lun: "+lun);
							value_par0 = array_js2[lun-1].substring(array_js2[lun-1].lastIndexOf('>')+1,array_js2[lun-1].lastIndexOf(''));
							console.log(value_par0);
							if ((value_par0=='Password')||(value_par0=='Username')){
								$("#auth_check3").prop( "checked", true );
								lun = lun - 2;
							}else{
								$("#auth_check3").prop( "checked", false );
							}
							console.log("Variabile lun dopo: "+lun);
							for (var i=1; i < lun; i++){
								value_par = array_js2[i].substring(array_js2[i].lastIndexOf('>')+1,array_js2[i].lastIndexOf(''));
								console.log(value_par);
								var cont = i;
								var cont0 = cont -1;
								var cont2 = cont +1; 
								var attuale = $(".contatore_param3").val();
								$("#param_container3").append('<div class="input-group param'+cont+'"><span class="input-group-addon">parameter '+cont+': </span><input type="Text" class="form-control paramItem3" name="paramList3['+cont0+']" value="'+value_par+'"></input></div>');
								$("#param_container3_vecchi").append('<div class="input-group "><span class="input-group-addon">parameter '+cont+': </span><input type="Text" class="form-control paramItem3vecchi" name="paramList_vecchi3['+cont0+']" value="'+value_par+'" readonly></input></div>');
								$(".contatore_param3").text(attuale +' 1');	
							}		
				}
				///FINE OPEN MICROSERVICE
				/////
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
							perPageOptions: [5, 10, 20],
							sorts: null,
							sortsKeys: null,
							sortTypes: {},
							records: null
						  }
					}
				
				);
				}
		});
		
		
		$(document).on('click','.delete_file',function(){
			var ind = $(this).parent().parent().first().children().html();
			var valore_el = array_files[ind]['id'];
			$("#id_file_del").val(valore_el);
			var data1 = array_files[ind]['date'];
			var data2 = data1.replace(':','-');
			var data3 = data2.replace(':','-');
			var data4 = data3.replace(' ','-');
			var us= array_files[ind]['utente'];
			var file_n =array_files[ind]['name'];
			var file1 = file_n.split(".");
			var type = array_files[ind]['type'];
			var path_file = 'uploads/'+us+'/'+data4+'/'+array_files[ind]['name'];
			$("#path_del").val(path_file);
		});
		
		//View 
		$(document).on('click','.viewHelp',function(){
			var ind = $(this).parent().parent().first().children().html();
			//view_help_content
			$('#title_view_Help').text("VIEW HELP: "+ array_files[ind]['name']);
			$('#view_help_content').append(array_files[ind]['help']);
			$('head').append('<link rel="stylesheet" href="ckeditor/MicroService.css" type="text/css" />');
			//
		});
		
		$(document).on('click','#Close_help_view',function(){
			$('#title_view_Help').empty();
			$('#view_help_content').empty();
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

		});
		
		
				
		//QUANDO SI CLICCA SUL MODAL di modify    agg da lavorare su questo
		$(document).on('click','.modify_jt',function(){
			$('.edit_microserv').show();
			$('#collapse_table').hide();
			$('#view-menu').hide();
			var ind = $(this).parent().parent().first().children().html();
			var type = array_files[ind]['type'];
				$("#div-url3").show();
				$("#div-method3").show();
				$("#div-protocol3").hide();
				$("#div-os3").hide();
				$("#div-opensource3").hide();
				$("#div-periodic3").hide();
				$("#div-realtime3").hide();	
				$("#div-format3").hide();
				$("#div-help3").show();

			
			$("#titolo_proc3").text("Edit MicroService: " + array_files[ind]['name'] );
			$("#file_zip3").val(array_files[ind]['name']);
			$("#category3").val(array_files[ind]['category']);
			$("#resource3").val(array_files[ind]['resource']);
			$("#format3").val(array_files[ind]['format']);
			

			$("#licence3").val(array_files[ind]['licence']);
			$("#protocol3").val(array_files[ind]['protocol']);
			$("#desc3").val(array_files[ind]['desc']);
			//
			//$("#help3").val(array_files[ind]['help']);
			var help_content = array_files[ind]['help'];
			CKEDITOR.instances['help3'].insertHtml(help_content);
			
			$("#url3").val(array_files[ind]['url']);

			$("#method3").val(array_files[ind]['method']);
			$("#os3").val(array_files[ind]['OS']);
			//
			
			$("select[name=opensource3]").val(array_files[ind]['opensource']);
			$("select[name=realtime3]").val(array_files[ind]['realtime']);
			$("select[name=periodic3]").val(array_files[ind]['periodic']);
			
			$("#tipo_zip3").val(array_files[ind]['type']);
			$("#id3").val(array_files[ind]['id']);

			//LOAD DEI PARAMETRI
							var array_js = new Array ();
							var array_js2 = new Array ();
							//
							var str= array_files[ind]['Html'];
							//alert(array_files[ind]['Html']);
							var regex = "var 1 = (msg.payload .1 ? msg.payload .1 : config .1);";
							var url_array = 'node.on("input", function(msg) {var uri = "'+array_files[ind]['url']+'";';
							var array_js=str.substring(str.indexOf('</label>'),str.lastIndexOf('</label>'));
							var array_js2=array_js.split("</label>");
							$("#param_container3").empty();
							$("#param_container3_vecchi").empty();
							var lun = array_js2.length;
							console.log("lun: "+lun);
							value_par0 = array_js2[lun-1].substring(array_js2[lun-1].lastIndexOf('>')+1,array_js2[lun-1].lastIndexOf(''));
							console.log(value_par0);
							if ((value_par0=='Password')||(value_par0=='Username')){
								$("#auth_check3").prop( "checked", true );
								lun = lun - 2;
							}else{
								$("#auth_check3").prop( "checked", false );
							}
							console.log("Variabile lun dopo: "+lun);
							for (var i=1; i < lun; i++){
								value_par = array_js2[i].substring(array_js2[i].lastIndexOf('>')+1,array_js2[i].lastIndexOf(''));
								console.log(value_par);
								var cont = i;
								var cont0 = cont -1;
								var cont2 = cont +1; 
								var attuale = $(".contatore_param3").val();
								$("#param_container3").append('<div class="input-group param'+cont+'"><span class="input-group-addon">parameter '+cont+': </span><input type="Text" class="form-control paramItem3" name="paramList3['+cont0+']" value="'+value_par+'"></input></div>');
								$("#param_container3_vecchi").append('<div class="input-group "><span class="input-group-addon">parameter '+cont+': </span><input type="Text" class="form-control paramItem3vecchi" name="paramList_vecchi3['+cont0+']" value="'+value_par+'" readonly></input></div>');
								$(".contatore_param3").text(attuale +' 1');	
							}
			//
			
			
		});
		

		$(document).on('click','#chiudi_job',function(){
			$("#elenco tr td").remove();
			$("#show_jobType").modal('hide');
			$('.edit_microserv').hide();
			//$('.edit_microserv').show();
			console.log('CHIUDI');
		});
		
		$(document).on('click','#chiudiEdit', function(){
			$('.edit_microserv').hide();
			$('#collapse_table').show();
			$('#view-menu').show();
			CKEDITOR.instances['help3'].setData('');
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
					for (var i = 0; i < data.length; i++){
						array_process[i] = {
							id: data[i].jobtype['id'],
							name: data[i].jobtype['job_type_name'],
							group: data[i].jobtype['job_type_group'],
							type: data[i].jobtype['type']
					};
					$("#elenco").append('<tr><td class="id_job_row">'+array_process[i]['id']+'</td><td class="name_job_row">'+array_process[i]['name']+'</td><td class="group_job_row">'+array_process[i]['group']+'</td><td>'+array_process[i]['type']+'</td></tr>');
				}
			  }
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
		});

	}	
		
	});
</script>
</body>
</html>