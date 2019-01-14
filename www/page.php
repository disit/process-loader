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

   include('config.php');
    
   //session_start();
 
if (isset($_REQUEST['showFrame'])){
	if ($_REQUEST['showFrame'] == 'false'){
		$hide_menu= "hide";
		$_SESSION['showFrame']=$_REQUEST['showFrame'];
		$sf = "?showFrame=false";
	}else{
		$hide_menu= "";
		$sf="";
	}	
}else{$hide_menu= "";} 
   
   $_SESSION['showFrame']=$_REQUEST['showFrame'];
   
if (!isset($_GET['pageTitle'])){
	$default_title = "Process Loader: View Resources";
}else{
	$default_title = "";
}

if (isset($_REQUEST['redirect'])){
	$access_denied = "denied";
}else{
	$access_denied = "";
}

if (isset($_REQUEST['success_modify'])){
	$success_modify="OK";
			sleep(1);
}else{
	$success_modify="";
}
if (isset($_REQUEST['modified_status'])){
	$modified_status="OK";
			sleep(1);
}else{
	$modified_status="";
}

if(isset($_REQUEST['modify_ownership'])){
	$modify_ownership="OK";
			sleep(1);
}else{
	$modify_ownership="";
}

if(isset($_REQUEST['error_status'])){
	$error_status="NO";
			sleep(1);
}else{
	$error_status="";
}

if(isset($_REQUEST['message'])){
	$error_message="OK";
			sleep(1);
}else{
	$error_message="";
}
?>


<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Process Loader</title>
		
		<!-- jQuery -->
        <script src="jquery/jquery-1.10.1.min.js"></script>
		<script src="js/raterater.jquery.js"></script>
		<!-- Bootstrap Core JavaScript -->
        <script src="bootstrap/bootstrap.min.js"></script>			
        <!-- Bootstrap Core CSS -->
        <link href="bootstrap/bootstrap.css" rel="stylesheet">
		<!-- Dynatable -->
       <link rel="stylesheet" href="dynatable/jquery.dynatable.css">
       <script src="dynatable/jquery.dynatable.js"></script>   
       <!-- Font awesome icons -->
        <link rel="stylesheet" href="fontAwesome/css/font-awesome.min.css">
		<link href="https://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">    
        <!-- Custom CSS -->
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/dashboardList.css" rel="stylesheet">
		<link href="css/raterater.css" rel="stylesheet">
		<!-- Rating -->
		<script src="js/star-rating.min.js" type="text/javascript"></script>
		<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
		<link href="css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="jquery.raty.css"><script src="jquery.raty.js"></script>   
			
    </head>
    <body class="guiPageBody">
	<?php include('functionalities.php'); ?>
        <div class="container-fluid">
            <div class="row mainRow" style='background-color: rgba(138, 159, 168, 1)'>
               <?php 
						include ("mainMenu.php");	
			   ?>
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
                        <div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin: 0; padding:15'>
                            <div class="row mainContentRow" id="dashboardsListTableRow">
                                <div class="col-xs-12 mainContentCellCnt" style='background-color: rgba(138, 159, 168, 1)'>
                                    <div id="dashboardsListMenu" class="row" style='margin-left:0; margin-right:0;'>
                                        <div id="dashboardListsViewMode" class="hidden-xs col-sm-6 col-md-2 dashboardsListMenuItem">
                                           
                                        </div>
                                        <div id="dashboardListsPages" class="col-xs-12 col-sm-6 col-md-4 dashboardsListMenuItem">
                                            <div class="dashboardsListMenuItemContent centerWithFlex col-xs-12">     
                                            </div>
                                        </div>
                                        <div id="dashboardListsSearchFilter" class="col-xs-12 col-sm-6 col-md-4 dashboardsListMenuItem">
                                            <div class="dashboardsListMenuItemContent centerWithFlex col-xs-12">
                                                <div class="input-group">
                                                    <div class="input-group-btn">
                                                      <button type="button" id="searchDashboardBtn" class="btn"><i class="fa fa-search"></i></button>
                                                      <button type="button" id="resetSearchDashboardBtn" class="btn"><i class="fa fa-close"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div id="facet-menu"  class="container-fluid btn-group">
                                       <button type="button" class="btn btn-warning" style= "margin-left:45px; margin-bottom:10px; " id="facet-reset-button">Reset</button> 
                                    </div>
									<div id="view-menu">
									</div>            
                                    <div id="list_dashboard_cards" class="container-fluid">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modale creazione dashboard -->
        


<script type='text/javascript'>
	var ut_att = "<?=$utente_att; ?>";
	var role_att = "<?=$role_att;?>";
	var success_modify = "<?=$success_modify; ?>";
	var modified_status = "<?=$modified_status; ?>";
	var modify_ownership = "<?=$modify_ownership; ?>";
	var error_message = "<?=$error_message; ?>";
	var error_status = "<?=$error_status; ?>";
	var nascondi= "<?=$hide_menu; ?>";
	///
	function myCardsWriter(rowIndex, record, columns, cellWriter)
	{
		var title = record.file_name;	 
		var username=record.username;
		var file_type=record.resource_type;
		var category=record.nature;
		var description=record.description;
		var Id=record.id;
		var imge=record.image;
		//CONTROLLO PER INSERIRE UN IMMAGINE DI DEFAULT SE IMG è NULL
		if ((imge == null)||(imge == '')){
			if (record.resource_type=="ETL"){ record.image='imgUploaded/default_images/ETL.png';}
			else if (record.resource_type=="R"||record.app_type=="Java"){record.image='imgUploaded/default_images/DataAnalitics.png';}
			else if (record.resource_type=="IoTApp"){record.image='imgUploaded/default_images/IoTApp.png';}
			else if (record.resource_type=="IoTBlocks"){record.image='imgUploaded/default_images/IoTBlocks.png';}
			else if (record.resource_type=="AMMA"){record.image='imgUploaded/default_images/AMMA.png';}
			else if (record.resource_type=="ResDash"){record.image='imgUploaded/default_images/ResDash.png';}
			else if (record.resource_type=="DevDash"){record.image='imgUploaded/default_images/DevDash.png';}
			else if (record.resource_type=="MicroService"){record.image='imgUploaded/default_images/MicroServices.png';}
			else if (record.resource_type=="DataAnalyticMicroService"){record.image='imgUploaded/default_images/iot-data-analytic-ms.png';}
			else if (record.resource_type=="Mobile App"){record.image='imgUploaded/default_images/MobileApp.png';}
			else if(record.resource_type=="ControlRoomDashboard"){record.image='imgUploaded/default_images/ControlRoomDashboard.png';}
			else {
					if (file_type =='MicroService'){
					record.image='imgUploaded/default_images/MicroServices.png';	
					}else{
					record.image='imgUploaded/default_images/'+record.resource_type+'.png';
					}
				}
		}
		//

		var border = "";
		var status_pub = "";
		var var_publication = "";
		if (record.Public == false){
			status_pub ='<li class="list-group-item" id="status_pub" hidden><b>Status: </b>Not Published</li>';
			var_publication = "Private";
		}else{
			status_pub ='<li class="list-group-item" id="status_pub" hidden><b>Status: </b>Published</li>';
			var_publication = "Public";
		}

		
		if(title.length > 100)
		{
			title = title.substr(0, 100) + " ...";
		} 

		if (description == null){
				description = "";
			}
				
		var headerColor = record.color_header;
		headerColor='#ffffff';
		var headerFontColor='#ffffff';
			
		if((headerColor === '#ffffff')||(headerColor === 'rgb(255, 255, 255)')||(headerColor === 'rgba(255, 255, 255, 1)')||(headerColor === 'white')||(headerColor.includes(',0)')))
		{
			headerColor = '#e6f9ff';
		} 
			
		//var	status_label = 'Publish';
		if (record.Public == false){
		var	status_label = 'Publish';
		}	
		if (record.Public == true){
		var	status_label = 'Unpublish';
		}
		
		if (record.realtime == 'yes'){
			//var options_rt = '<option value="1">Yes</option><option value="0">No</option>';
			var selected_rt1='selected="selected"';
			var selected_rt0='';
		}else{
			var selected_rt0='selected="selected"';
			var selected_rt1='';
		}
		
		if (record.periodic == 'yes'){
			var selected_pr1='selected="selected"';
			var selected_pr0='';
		}else{
			var selected_pr0='selected="selected"';
			var selected_pr1='';
		}
		
		var data_pubblicazione = "Not Published";
		if (record.dateofpublication != null){
			data_pubblicazione = record.dateofpublication;
		}
			
		var sf = '<?=$sf; ?>';
		var bg_color='#6C8793';
		var file_type = record.resource_type;
		if(file_type=="ETL" || file_type=="R" || file_type=="Java"){			
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body" style="padding:0px;"><center><div class="dashboardsListCardImgDiv" style="background-image:url('+record.image+'); overflow: auto; margin-top: 10px; margin-bottom: 5px;"></div></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin" id="ratin_'+record.id+'"><input name="input" id="rating-loading'+record.id+'"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div></center><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>Nature: </b>'+record.nature+'</li><li class="list-group-item"><b>Sub Nature: </b>'+record.sub_nature+'</li><li class="list-group-item"><b>Protocol: </b>'+record.protocol+'</li><li class="list-group-item"><b>Real-time: </b>'+record.realtime+'</li><li class="list-group-item"><b>Periodic: </b>'+record.periodic+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+data_pubblicazione+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul><div class="space-ten"></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div></div>';
			var modal_edit='<form class="change_edit" method="post" action="modify_resource.php'+sf+'" enctype="multipart/form-data"><div class="modal fade" id="edit-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px; color:white;"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Edit Resource Parameter: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="160px" width="auto" border="0" ></center></a><div class="row"><input type="text" name="id" value="'+record.id+'" hidden></input><br /><ul class="list-group" ><li class="list-group-item"><b>Image:</b><input name="image" type="file" class="btn btn-secondary" value="'+record.image+'" accept="image/*" ></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Description: </span><input type="text" name="descr" value="'+description+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Nature: </span><input type="text" name="nature" value="'+record.nature+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Sub Nature: </span><input type="text" name="sub_nature" value="'+record.sub_nature+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Protocol: </span><input type="text" name="protocol" value="'+record.protocol+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Format: </span><input type="text" name="Format" value="'+record.format+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><span><b>Real-time: </b><select name="realtime"><option value="1" '+selected_rt1+'>Yes</option><option value="0" '+selected_rt0+'>No</option></select></span></li><li class="list-group-item"><span><b>Periodic: </b><select name="periodic"><option value="1" '+selected_pr1+'>Yes</option><option value="0" '+selected_pr0+'>No</option></select></span></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">License: </span><input type="text" name="licence" value="'+record.license+'" class="form-control input-sm"></div></input></li></ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button><input type="submit" value="Submit" class="btn btn-secondary"></div></div></div></div></form>';		
			var modal_publish='<form class="change_publish" method="post" action="modify_status_resource.php'+sf+'"><div class="modal fade" id="publish-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Change status of publication: '+record.file_name+'</h4></div><div class="modal-body">Are you sure do you want change status of publication?<input type="text" name="id" value="'+record.id+'" hidden></input><input type="text" name="status" value="'+record.Public+'" hidden><input type="text" name="lic" value="'+record.license+'" hidden></input><input type="text" name="form" value="'+record.format+'" hidden><input type="text" name="access" value="'+record.protocol+'" hidden></input></input></input></div><div class="modal-footer"><input type="submit" value="Yes" class="btn btn-secondary"><button  type="button" class="btn btn-default" data-dismiss="modal">No</button></div></div></div></div></form>';
		}
		if(file_type=="IoTBlocks" || file_type=="IoTApp"){	
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body" style="padding:0px;""><center><div class="dashboardsListCardImgDiv" style="background-image:url('+record.image+'); overflow: auto; margin-top: 10px; margin-bottom: 5px;"></div></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin" id="ratin_'+record.id+'"><input name="input" id="rating-loading'+record.id+'" class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div></center><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>Nature: </b>'+record.nature+'</li><li class="list-group-item"><b>Sub Nature: </b>'+record.sub_nature+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+data_pubblicazione+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div></div>';
			var modal_edit='<form class="change_edit" method="post" action="modify_resource.php'+sf+'" enctype="multipart/form-data"><div class="modal fade" id="edit-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px; color:white;"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Edit Resource Parameter: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="160px" width="auto" border="0" ></center></a><div class="row"><input type="text" name="id" value="'+record.id+'" hidden></input><br /><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Image:</b><input name="image" type="file" class="btn btn-secondary" value="'+record.image+'" accept="image/*" ></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Description: </span><input type="text" name="descr" value="'+description+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Nature: </span><input type="text" name="nature" value="'+record.nature+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Sub Nature: </span><input type="text" name="sub_nature" value="'+record.sub_nature+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">License: </span><input type="text" name="licence" value="'+record.license+'" class="form-control input-sm"></input></div></li></ul></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button><input type="submit" value="Submit" class="btn btn-secondary"></div></div></div></div></form>';		
			var modal_publish='<form class="change_publish" method="post" action="modify_status_resource.php'+sf+'"><div class="modal fade" id="publish-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Change status of publication: '+record.file_name+'</h4></div><div class="modal-body">Are you sure do you want change status of publication?<input type="text" name="id" value="'+record.id+'" hidden></input><input type="text" name="status" value="'+record.Public+'" hidden><input type="text" name="lic" value="'+record.license+'" hidden></input><input type="text" name="access" value="'+record.protocol+'" hidden></input></input></input></div><div class="modal-footer"><input type="submit" value="Yes" class="btn btn-secondary"><button  type="button" class="btn btn-default" data-dismiss="modal">No</button></div></div></div></div></form>';
		}
		if(file_type=="Mobile App"){
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body" style="padding:0px;"><center><div class="dashboardsListCardImgDiv" style="background-image:url('+record.image+'); overflow: auto; margin-top: 10px; margin-bottom: 5px;"></div></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin" id="ratin_'+record.id+'"><input name="input" id="rating-loading'+record.id+'" class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div></center><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>OS: </b>'+record.OS+'</li><li class="list-group-item"><b>Category: </b>'+record.nature+'</li><li class="list-group-item"><b>OpenSource: </b>'+record.OpenSource+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+data_pubblicazione+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul><div class="space-ten"></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div></div>';		
			var modal_edit='<form class="change_edit" method="post" action="modify_resource.php'+sf+'" enctype="multipart/form-data"><div class="modal fade" id="edit-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px; color:white;"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Edit Resource Parameter: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="160px" width="auto" border="0" ></center></a><div class="row"><input type="text" name="id" value="'+record.id+'" hidden></input><br /><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Image:</b><input name="image" type="file" class="btn btn-secondary" value="'+record.image+'" accept="image/*" ></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Description: </span><input type="text" name="descr" value="'+description+'" class="form-control"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">OS: </span><input type="text" name="OS" value="'+record.OS+'" class="form-control"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Category: </span><input type="text" name="nature" value="'+record.nature+'" class="form-control"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">OpenSource: </span><input type="text" name="OpenSource" value="'+record.OpenSource+'" class="form-control"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">License: </span><input type="text" name="licence" value="'+record.license+'" class="form-control input-sm"></input></span></li></ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button><input type="submit" value="Submit" class="btn btn-secondary"></div></div></div></div></form>';	
			var modal_publish='<form class="change_publish" method="post" action="modify_status_resource.php'+sf+'"><div class="modal fade" id="publish-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Change status of publication: '+record.file_name+'</h4></div><div class="modal-body">Are you sure do you want change status of publication?<input type="text" name="id" value="'+record.id+'" hidden></input><input type="text" name="status" value="'+record.Public+'" hidden><input type="text" name="lic" value="'+record.license+'" hidden></input><input type="text" name="form" value="'+record.format+'" hidden><input type="text" name="access" value="'+record.protocol+'" hidden></input></input></input></div><div class="modal-footer"><input type="submit" value="Yes" class="btn btn-secondary"></div><button  type="button" class="btn btn-default" data-dismiss="modal">No</button></div></div></div></form>';
		}
		if(file_type=="AMMA" || file_type=="DevDash" || file_type=="ResDash" ){
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body" style="padding:0px;"><center><div class="dashboardsListCardImgDiv" style="background-image:url('+record.image+'); overflow: auto; margin-top: 10px; margin-bottom: 5px;"></div></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin" id="ratin_'+record.id+'"><input name="input" id="rating-loading'+record.id+'" class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div></center><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>Nature: </b>'+record.nature+'</li><li class="list-group-item"><b>Sub Nature: </b>'+record.sub_nature+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+data_pubblicazione+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul><div class="space-ten"></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div></div>';			
			var modal_edit='<form class="change_edit" method="post" action="modify_resource.php'+sf+'" enctype="multipart/form-data"><div class="modal fade" id="edit-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px; color:white;"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Edit Resource Parameter: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="160px" width="auto" border="0" ></center></a><div class="row"><input type="text" name="id" value="'+record.id+'" hidden></input><br /><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Image:</b><input name="image" type="file" class="btn btn-secondary" value="'+record.image+'" accept="image/*" ></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Description: </span><input type="text" name="descr" value="'+description+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><b>Nature: </b><input type="text" name="nature" value="'+record.nature+'" class="form-control input-sm"></input></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Sub Nature: </span><input type="text" name="sub_nature" value="'+record.sub_nature+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">License: </span><input type="text" name="licence" value="'+record.license+'" class="form-control input-sm"></input></div></li></ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button><input type="submit" value="Submit" class="btn btn-secondary"></div></div></div></div></form>';
			var modal_publish='<form class="change_publish" method="post" action="modify_status_resource.php'+sf+'"><div class="modal fade" id="publish-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Change status of publication: '+record.file_name+'</h4></div><div class="modal-body">Are you sure do you want change status of publication?<input type="text" name="id" value="'+record.id+'" hidden></input><input type="text" name="status" value="'+record.Public+'" hidden><input type="text" name="lic" value="'+record.license+'" hidden></input><input type="text" name="access" value="'+record.protocol+'" hidden></input></input></input></div><div class="modal-footer"><input type="submit" value="Yes" class="btn btn-secondary"><button  type="button" class="btn btn-default" data-dismiss="modal">No</button></div></div></div></div></form>';
		}
		if(file_type=="Banana Library" ){
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body" style="padding:0px;"><center><div class="dashboardsListCardImgDiv" style="background-image:url('+record.image+'); overflow: auto; margin-top: 10px; margin-bottom: 5px;"></div></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin" id="ratin_'+record.id+'"><input name="input" id="rating-loading'+record.id+'" class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div></center><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>Nature: </b>'+record.nature+'</li><li class="list-group-item"><b>Sub Nature: </b>'+record.sub_nature+'</li><li class="list-group-item"><b>Real-time: </b>'+record.realtime+'</li><li class="list-group-item"><b>Periodic: </b>'+record.periodic+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+data_pubblicazione+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul><div class="space-ten"></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div></div>';		
			var modal_edit='<form class="change_edit" method="post" action="modify_resource.php'+sf+'" enctype="multipart/form-data"><div class="modal fade" id="edit-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px; color:white;"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Edit Resource Parameter: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="160px" width="auto" border="0" ></center></a><div class="row"><input type="text" name="id" value="'+record.id+'" hidden></input><br /><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Image:</b><input name="image" type="file" class="btn btn-secondary" value="'+record.image+'" accept="image/*" ></li><li class="list-group-item"><b>Description: </b><input type="text" name="descr" value="'+description+'" class="form-control input-sm"></input></li><li class="list-group-item"><b>Nature: </b><input type="text" name="nature" value="'+record.nature+'" class="form-control input-sm"></input></li><li class="list-group-item"><b>Sub Nature: </b><input type="text" name="sub_nature" value="'+record.sub_nature+'" class="form-control"></input></li><li class="list-group-item"><span><b>Real-time: </b><select name="realtime"><option value="1" '+selected_rt1+'>Yes</option><option value="0" '+selected_rt0+'>No</option></select></span></li><li class="list-group-item"><span><b>Periodic: </b><select name="periodic"><option value="1" '+selected_pr1+'>Yes</option><option value="0" '+selected_pr0+'>No</option></select></span></li><li class="list-group-item"><b>License: </b><input type="text" name="licence" value="'+record.license+'" class="form-control input-sm"></input></li></ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button><input type="submit" value="Submit" class="btn btn-secondary"></div></div></div></div></form>';	
			var modal_publish='<form class="change_publish" method="post" action="modify_status_resource.php'+sf+'"><div class="modal fade" id="publish-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Change status of publication: '+record.file_name+'</h4></div><div class="modal-body">Are you sure do you want change status of publication?<input type="text" name="id" value="'+record.id+'" hidden></input><input type="text" name="status" value="'+record.Public+'" hidden><input type="text" name="lic" value="'+record.license+'" hidden></input><input type="text" name="access" value="'+record.protocol+'" hidden></input></input></input></div><div class="modal-footer"><input type="submit" value="Yes" class="btn btn-secondary"><button  type="button" class="btn btn-default" data-dismiss="modal">No</button></div></div></div></div></form>';
		}
		if(file_type=="MicroService" || file_type=="Microservice" || file_type=="DataAnalyticMicroService"){	
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body" style="padding:0px;"><center><div class="dashboardsListCardImgDiv" style="background-image:url('+record.image+'); overflow: auto; margin-top: 10px; margin-bottom: 5px;"></div></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin" id="ratin_'+record.id+'"><input  name="input" id="rating-loading'+record.id+'" class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div></center><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>Nature: </b>'+record.nature+'</li><li class="list-group-item"><b>Sub Nature: </b>'+record.sub_nature+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+data_pubblicazione+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul><div class="space-ten"></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div></div>';
			var modal_edit='<form class="change_edit" method="post" action="modify_resource.php'+sf+'" enctype="multipart/form-data"><div class="modal fade" id="edit-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="padding-bottom: 10px; color:white;"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Edit Resource Parameter: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="160px" width="auto" border="0" ></center></a><div class="row"><input type="text" name="id" value="'+record.id+'" hidden></input><br /><ul class="list-group" style="margin-bottom: 0px;"><li class="list-group-item"><b>Image:</b><input name="image" type="file" class="btn btn-secondary" value="'+record.image+'" accept="image/*" ></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Description: </span><input type="text" name="descr" value="'+description+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Nature: </span><input type="text" name="nature" value="'+record.nature+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">Sub Nature: </span><input type="text" name="sub_nature" value="'+record.sub_nature+'" class="form-control input-sm"></input></div></li><li class="list-group-item"><div class="input-group"><span class="input-group-addon">License: </span><input type="text" name="licence" value="'+record.license+'" class="form-control input-sm"></input></div></li></ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button><input type="submit" value="Submit" class="btn btn-secondary"></div></div></div></div></form>';
			var modal_publish='<form class="change_publish" method="post" action="modify_status_resource.php'+sf+'"><div class="modal fade" id="publish-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Change status of publication: '+record.file_name+'</h4></div><div class="modal-body">Are you sure do you want change status of publication?<input type="text" name="id" value="'+record.id+'" hidden></input><input type="text" name="status" value="'+record.Public+'" hidden><input type="text" name="lic" value="'+record.license+'" hidden></input><input type="text" name="access" value="'+record.protocol+'" hidden></input></input></input></div><div class="modal-footer"><input type="submit" value="Yes" class="btn btn-secondary"><button  type="button" class="btn btn-default" data-dismiss="modal">No</button></div></div></div></div></form>';
		}	
			
		var modal_ownership='<form class="change_ownership" method="post" action="modify_ownership.php'+sf+'"><div class="modal fade" id="owner-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Change Ownership: '+record.file_name+'</h4></div><div class="modal-body"><div class="row"><ul class="list-group"><li class="list-group-item"><b>Current User:	</b><input type="text" name="user_old" readonly value="'+record.username+'"></input></li><input type="text" name="date" value="'+record.creation_date+'" hidden></input><li class="list-group-item"><b>New User:	</b><input type="text" name="user_new"></input></li></ul></div><input type="text" name="id" value="'+record.id+'" hidden></input><input type="text" name="type" value="'+record.resource_type+'" hidden></input><input type="text" name="file_name" value="'+record.file_name+'" hidden></input></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button><input type="submit" value="Submit" class="btn btn-secondary"></div></div></div></div></form>';
		var myOwn = "";
		var visible_myown = "";
		var visible_publish = "";
		var visible_ownership_manager="";
		 if ((username == ut_att)){ 
			myOwn = '<div class="myOwn_status" style="color:white;text-align:center;font-size:0.5;">' + "MyOwn: " + var_publication +'</div>'; 
		 }else {
			 myOwn = '<div class="myOwn_status" style="color:white;text-align:center;font-size:0.5;">'+ var_publication +'</div>';
			 visible_myown = 'style="display:none"';
		 }
		 
		if ((username == ut_att)){ 
			myOwn = '<div class="myOwn_status" style="color:white;text-align:center;font-size:0.5;">' + "MyOwn: " + var_publication +'</div>'; 
		 }else{
			 visible_publish = 'style="display:none"';
		 }
		 if ( (role_att == 'RootAdmin')||(role_att == 'ToolAdmin')){
			 visible_myown = '';
			 visible_publish = "";
			 var visible_ownership_manager="";
		 }
		 //show_frame
		 var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			//showFrame
			$('.change_edit').attr("action","modify_resource.php?showFrame=false");
			$('.change_publish').attr("action","modify_status_resource.php?showFrame=false");
			$('.change_ownership').attr("action","modify_ownership.php?showFrame=false");
			//
		}
		
		 //
		 if ((username == ut_att)){ 
		 UserStatus = '<div class="user_status" style="color:white;text-align:center;font-size:0.5;"> MyOwn: '+ var_publication +'</div>'; 
		 }else{
		 UserStatus = '<div class="user_status" style="color:white;text-align:center;font-size:0.5;">' + username +': '+ var_publication +'</div>'; 
		 }
		 //
		var cardDiv = '<div data-uniqueid="' + record.Id + '" data-headerColor = "' + headerColor + '" data-headerFontColor="' + record.headerFontColor + '"   class="dashboardsListCardDiv col-xs-12 col-sm-4 col-md-3">' +
					  '<div class="dashboardsListCardInnerDiv" style="background-color:'+bg_color+';'+border+'">' +
					  '<div class="dashboardsListCardTitleDiv col-xs-12 centerWithFlex"><a " href="'+record.link+'" class="link-file" download> '+title+'</a></div>' +
						//					  
					  '<div class="dashboardsListCardImgDiv" style="background-image:url('+record.image+'); overflow: auto;"></div>' + myOwn + UserStatus +
					  //
					   '<div class="dashboardsListUsernameDiv" hidden>' + "Username: " + username +'</div>' +
					   '<div class="dashboardsListfile_typeDiv">'+ "Resource type: " + file_type + '</div>' +
					   '<div class="dashboardsListCategoryDiv">'+ "Nature: " + category + '</div>' +
					   '<div class="dashboardsListDescriptionDiv" style="text-overflow:ellipsis;">'+ "Description: " + description + '</div>' +
						'<div class="raty" id="rate-'+record.id+'">Rate: </div>'+
						'<div class="dashboardsListCardClick2EditDiv col-xs-12 centerWithFlex">' +
						//
						'<div class="btn-group" role="toolbar" aria-label="Basic example" >'+
						//			
						'<button type="button" data-toggle="modal" data-target="#info-modal_proc'+record.id+'"class="btn btn-xs editDashBtnCard" style="border-radius: 0; color: white;" style="width: 80%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">View</button>	' +
						//
						'	<button type="button" data-toggle="modal" data-target="#edit-modal_proc'+record.id+'" class="btn btn-xs editDashBtnCard_modify" '+visible_myown+' style="border-radius: 0; color: white;" style="width: 80%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">Edit</button>' +
						//change_publish
						'	<button type="button" data-toggle="modal" data-target="#publish-modal_proc'+record.id+'" class="btn btn-xs editDashBtnCard_publish" '+visible_publish+' style="border-radius: 0; color: white;" style="width: 80%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">'+status_label+'</button>' +
						//change ownership
						'	<button type="button" data-toggle="modal" data-target="#owner-modal_proc'+record.id+'" class="btn btn-xs editDashBtnCard_owner" '+visible_ownership_manager+' style="border-radius: 0; color: white;" style="width: 80%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">Owner</button>' +
						//
						'</div>'+
						//
						'</div>' +  
						'</div>' +modal+
						'</div>' +modal_edit +modal_publish+modal_ownership+
						'</div>';
		return cardDiv;

	}


	//inizializzazione visualizzazione plugin di rating
	$('.raty').raty({ starType: 'img',
		  half        : true,
		   halfShow    : true,
			starHalf    : 'star-half.png',
			starOff     : 'star-off.png',
			starOn      : 'star-on.png'
	});


	$("#facet-reset-button").click(function(){
		checklist=document.getElementsByClassName("check");
		for(j=0;j<checklist.length;j++){
			checklist[j].checked=true;
		}
		if (nascondi == 'hide'){
			window.location.assign("page.php?showFrame=false");
		}else{
			window.location.assign("page.php");
		}
		//filterFunction();
		//page.php?showFrame=false
		//location.reload();
	});
	
	
	
	
	//$( window ).load(function() {
	$(document).ready(function () {
		
		//
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#mainCnt').removeClass('col-md-10');
			$('#mainCnt').addClass('col-md-12');
			//showFrame
			$('.change_edit').attr("action","modify_resource.php?showFrame=false");
			$('.change_publish').attr("action","modify_status_resource.php?showFrame=false");
			$('.change_ownership').attr("action","modify_ownership.php?showFrame=false");
			//
		}
		
		
				

	$('#list_dashboard_cards').empty();
	//Titolo Default
	var titolo_default = "<?=$default_title; ?>";
	if (titolo_default != ""){
		$('#headerTitleCnt').text(titolo_default);
	}
		
	
	
	
	
	//
	var access_denied = "<?=$access_denied; ?>";
	if (access_denied != ""){
		alert('You need to log in with the right credentials before to access to this page!');
	}
	//
		// ajax per tirare fuori nomi e valori delle faccette
		$.ajax({
			url: "filterFacet.php",
			data: {utente_att:ut_att, role_att:role_att},
			type: "POST",
			async: true,
			dataType: 'json',
			success: function (data) {
				$count=0;
				for($i=0;$i<data.length;$i++){
					$facet=data[$i][0].facet['cat'];
					facet_uc=$facet.charAt(0).toUpperCase() + $facet.slice(1);
					max= data[$i].length;
					$append='<div class="btn-group dropdown"><button class="btn btn-warning dropdown-toggle btn-group drop-'+facet_uc+'" type="button" data-toggle="dropdown">'+facet_uc+'  <span class="caret"></span></button><ul class="dropdown-menu" id="control-group-'+$facet+'" style="height: auto;  max-height: 200px;  overflow-x: hidden;"><li><a href="#" ><input class="check select-'+$facet+'" type="checkbox" checked  onchange="selectAllFunction(value)" value="'+$facet+'">Select All</a></li><li class="divider"></li></ul></div>';
					$("#facet-menu").append($append);
					for($j=0;$j<data[$i].length;$j++){
						$name=data[$i][$j].facet["facet_name"];
						$value=data[$i][$j].facet["value"];
						$count=$count+1;
						var name_norm=$name.replace(/ /g,"");
						name_norm=name_norm.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g,"");
						var name_div='div-'+name_norm+'-'+facet_uc;
						$append2='<li  class="check_link" value="'+$value+'"><a href="#"><input class="check '+$facet+'" id="check'+$count+'" type="checkbox" onchange="filterFunction(value,name)" name="'+$facet+'"value="'+$name+'" checked><div style="display:inline;" id="'+name_div+'">'+$name+' ('+$value+') </div></a></li>';
						$string = '#control-group-'+$facet+'';
						$($string).append($append2);
						if($value == 0){
							$('#' + name_div).parents('li.check_link').hide();
						}else{
							$('#' + name_div).parents('li.check_link').show();
						}	
					}
				}
				
			}
		});

		$('#mainMenuCnt').css("padding", "0"); 
		var dashboardsList = null;  
		$('#mainContentCnt').height($('#mainMenuCnt').height() - $('#headerTitleCnt').height());
      
		$(window).resize(function(){
			$('#mainContentCnt').height($('#mainMenuCnt').height() - $('#headerTitleCnt').height());
			$('#sessionExpiringPopup').css("top", parseInt($('body').height() - $('#sessionExpiringPopup').height()) + "px");
			$('#sessionExpiringPopup').css("left", parseInt($('body').width() - $('#sessionExpiringPopup').width()) + "px");
		});
         
		var tableFirstLoad = true;

        //Reperimento dati per popolare tabella e griglia
        $.ajax({
            url: "getSolrResponse.php",
            type: "GET",
			data: {utente_att:ut_att,role_att:role_att},
            async: true,
            dataType: 'json',
            success: function(data) 
            {
				//alert(JSON.stringify(data));
                dashboardsList = data;
                //Ricordati di metterlo PRIMA dell'istanziamento della tabella
				$('#list_dashboard_cards').unbind('dynatable:afterProcess');
				$('#list_dashboard_cards').bind('dynatable:afterProcess', function(e, dynatable){
					$('#dashboardsListTableRow').css('padding-top', '0px');
					$('#dashboardsListTableRow').css('padding-bottom', '0px');
					$("#dynatable-pagination-links-list_dashboard_cards").appendTo("#dashboardListsPages div.dashboardsListMenuItemContent");
					$('#dashboardListsPages div.dashboardsListMenuItemContent').css("font-family", "Montserrat");
					$('#dashboardListsPages div.dashboardsListMenuItemContent').css("font-weight", "bold");
					$('#dashboardListsPages div.dashboardsListMenuItemContent').css("color", "white");
					$('#dashboardListsPages div.dashboardsListMenuItemContent a').css("font-family", "Montserrat");
					$('#dashboardListsPages div.dashboardsListMenuItemContent a').css("font-weight", "bold");
					$('#dashboardListsPages div.dashboardsListMenuItemContent a').css("color", "white");
					$("ul#dynatable-pagination-links-list_dashboard_cards").css("-webkit-padding-start", "0px");
					$("ul#dynatable-pagination-links-list_dashboard_cards").css("-webkit-margin-before", "0px");
					$("ul#dynatable-pagination-links-list_dashboard_cards").css("-webkit-margin-after", "0px");
					$("ul#dynatable-pagination-links-list_dashboard_cards").css("padding", "0px");	
					$("#dynatable-query-search-list_dashboard_cards").prependTo("#dashboardListsSearchFilter div.dashboardsListMenuItemContent div.input-group");
					$('#dynatable-search-list_dashboard_cards').remove();
					$("#dynatable-query-search-list_dashboard_cards").css("border", "none");
					$("#dynatable-query-search-list_dashboard_cards").attr("placeholder", "Filter by title, author...");
					$("#dynatable-query-search-list_dashboard_cards").css("width", "100%");
					$("#dynatable-query-search-list_dashboard_cards").addClass("form-control");
							
					var count=0;

					$('#list_dashboard_cards div.dashboardsListCardDiv').each(function(i){
						$(this).find('div.dashboardsListCardImgDiv').css("background-size", "contain");
						$(this).find('div.dashboardsListCardImgDiv').css("background-repeat", "no-repeat");
						$(this).find('div.dashboardsListCardImgDiv').css("background-position", "center center");
						$(this).find('div.dashboardsListCardInnerDiv').css("height","100%");
						$(this).find('div.dashboardsListCardInnerDiv').css("height", $(this).height() + "px");
						$(this).find('div.dashboardsListCardOverlayDiv').css("height", $(this).find('div.dashboardsListCardImgDiv').height() + "px");
						$(this).find('div.dashboardsListCardOverlayTxt').css("height", $(this).find('div.dashboardsListCardImgDiv').height() + "px");
						$(this).find('div.dashboardsListUsernameDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListUsernameDiv').css("color", "white");
						$(this).find('div.dashboardsListUsernameDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListUsernameDiv').css("-webkit-padding-start", "30px");
						$(this).find('div.dashboardsListUsernameDiv').css("width","80%");
						$(this).find('div.dashboardsListUsernameDiv').css("overflow","hidden");
						$(this).find('div.dashboardsListUsernameDiv').css("white-space","nowrap");
						$(this).find('div.dashboardsListUsernameDiv').css("text-overflow","ellipsis");	
						$(this).find('div.dashboardsListfile_typeDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListfile_typeDiv').css("color", "white");
						$(this).find('div.dashboardsListfile_typeDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListfile_typeDiv').css("-webkit-padding-start", "30px");
						$(this).find('div.dashboardsListfile_typeDiv').css("display","block");
						$(this).find('div.dashboardsListfile_typeDiv').css("width","80%");
						$(this).find('div.dashboardsListfile_typeDiv').css("overflow","hidden");
						$(this).find('div.dashboardsListfile_typeDiv').css("white-space","nowrap");
						$(this).find('div.dashboardsListfile_typeDiv').css("text-overflow","ellipsis");
						$(this).find('div.dashboardsListCategoryDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListCategoryDiv').css("color", "white");
						$(this).find('div.dashboardsListCategoryDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListCategoryDiv').css("-webkit-padding-start", "30px");
						$(this).find('div.dashboardsListCategoryDiv').css("display","block");
						$(this).find('div.dashboardsListCategoryDiv').css("width","80%");
						$(this).find('div.dashboardsListCategoryDiv').css("overflow","hidden");
						$(this).find('div.dashboardsListCategoryDiv').css("white-space","nowrap");
						$(this).find('div.dashboardsListCategoryDiv').css("text-overflow","ellipsis");
						$(this).find('div.dashboardsListDescriptionDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListDescriptionDiv').css("color", "white");
						$(this).find('div.dashboardsListDescriptionDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListDescriptionDiv').css("-webkit-padding-start", "30px");
						$(this).find('div.dashboardsListDescriptionDiv').css("display","block");
						$(this).find('div.dashboardsListDescriptionDiv').css("width","80%");
						$(this).find('div.dashboardsListDescriptionDiv').css("overflow","hidden");
						$(this).find('div.dashboardsListDescriptionDiv').css("white-space","nowrap");
						$(this).find('div.dashboardsListDescriptionDiv').css("text-overflow","ellipsis");
						$(this).find('div.dashboardsListRatingDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListRatingDiv').css("color", "white");
						$(this).find('div.dashboardsListRatingDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListRatingDiv').css("-webkit-padding-start", "30px");
						$(this).find('div.dashboardsListCardDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListCardDiv').css("color", "white");
						$(this).find('div.dashboardsListCardDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListCardDiv').css("-webkit-padding-start", "30px");
						$(this).find('div.dashboardsListCardTitleDiv').css("display","block");
						$(this).find('div.dashboardsListCardTitleDiv').css("white-space","nowrap");
						$(this).find('a.link-file').css("display","block");
						$(this).find('a.link-file').css("overflow","hidden");
						$(this).find('a.link-file').css("white-space","nowrap");
						$(this).find('a.link-file').css("text-overflow","ellipsis");
						$(this).find('div.raty').css("font-family", "Montserrat");
						$(this).find('div.raty').css("color", "white");
						$(this).find('div.raty').css("font-weight", "bold");
						$(this).find('div.raty').css("-webkit-padding-start", "30px");
						$(this).find('div.modal-dialog').css("font-family", "Montserrat");
						$(this).find('div.modal-dialog').css("color", "white");
						$(this).find('div.modal-dialog').css("font-weight", "bold");
		
						/// BEGIN change background color of "View Details" modal
						$('#info-modal_proc'+data[count].id+' > div > div').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(1)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(2)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(3)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(4)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(5)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(6)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(7)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(8)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(9)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(10)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(11)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(1)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(2)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(3)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(4)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(5)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(6)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(7)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > > div > ul > li:nth-child(8)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(9)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(10)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(11)').css("background-color", "rgba(108, 135, 147, 1)");
						
						
						////BACKGROUND COLOR of "Edit" modal
						/// BEGIN change background color of "View Details" modal
						//$('#edit-modal_proc'+data[count].id+' > div > div').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(1)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(2)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(3)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(4)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(5)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(6)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(7)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(8)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(9)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(10)').css("background-color", "rgba(108, 135, 147, 1)");
						//$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(11)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body  > div > ul > li:nth-child(1)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(2)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(3)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(4)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(5)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(6)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(7)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(8)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(9)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(10)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#edit-modal_proc'+data[count].id+' > div > div > div.modal-body > div > ul > li:nth-child(11)').css("background-color", "rgba(108, 135, 147, 1)");
						/////

						/// END change background color of "View Details" modal								
						$('.rating-loading').rating({displayOnly: true, step: 0.1});
						$('.raty').raty({ starType: 'img',
							half        : false,
							halfShow    : false,
							starHalf    : 'star-half.png',
							starOff     : 'star-off.png',
							starOn      : 'star-on.png',
							click:function(score,evt){
								var score=score;
								var parents=$(this).closest('div').parent().find("div.raty").attr('id');
								var id= parents.replace("rate-","");
								if (confirm('Are you sure you want to rate ' + score +' this file?')) {
									$.ajax({
										type:'POST',
										url:'update_votes.php',
										data: {id : id, value: score},
										success: function(data){
											alert("rating saved");
										}

									});
								}

							}						
						});
	 
						$(this).find('.dashboardsListCardImgDiv').off('mouseenter');
						$(this).find('.dashboardsListCardImgDiv').off('mouseleave');
						$(this).find('.dashboardsListCardOverlayTxt').hover(function(){
									$(this).parents('.dashboardsListCardDiv').find('div.dashboardsListCardOverlayTxt').css("opacity", "1");
									$(this).parents('.dashboardsListCardDiv').find('div.dashboardsListCardOverlayDiv').css("opacity", "0.8");
									$(this).css("cursor", "pointer");
						}, function(){
									$(this).parents('.dashboardsListCardDiv').find('div.dashboardsListCardOverlayTxt').css("opacity", "0");
									$(this).parents('.dashboardsListCardDiv').find('div.dashboardsListCardOverlayDiv').css("opacity", "0.05");
									$(this).css("cursor", "normal");
						});
								
						count++;
						$(this).find('.dashboardsListCardOverlayTxt').off('click');
				   
					});
							
					$('#searchDashboardBtn').off('click');
					$('#searchDashboardBtn').click(function(){
						var dynatable = $('#list_dashboard_cards').data('dynatable');
						dynatable.queries.run();
					}); 
							
					$('#resetSearchDashboardBtn').off('click');
					$('#resetSearchDashboardBtn').click(function(){
						var dynatable = $('#list_dashboard_cards').data('dynatable');
						$("#dynatable-query-search-list_dashboard_cards").val("");
						dynatable.queries.runSearch("");
					}); 
				});
				
                    
				$('#list_dashboard_cards').dynatable({
					table: {
						bodyRowSelector: 'div'
					},
					dataset: {
						records: data,
						perPageDefault: 8,
						perPageOptions: [4, 8, 12, 16, 20, 24, 28, 32],
					},
					writers: {
						_rowWriter: myCardsWriter
					},
					inputs: {
						paginationLinkPlacement: 'before'
					},
					features: {
						recordCount: false,
						perPageSelect: false,
						search: true,
						paginate:true,
						pushState: false
					}
				});
                      
				var dynatable = $('#list_dashboard_cards').data('dynatable');
				dynatable.sorts.clear();
				dynatable.sorts.add('title_header', 1); 
				dynatable.process();
			},
                
			error: function(errorData)
			{
				alert(JSON.stringify(errorData)); 
			}
		});
			
	});
	
	
	function arrayBuild(value,facet){
		var username_list = new Array();
		var category_list = new Array();
		var format_list = new Array();
		var type_list = new Array();
		var resource_list = new Array();
		var license_list = new Array();
		checklist=document.getElementsByClassName("check");
		for(j=0;j<checklist.length;j++){
			if(checklist[j].checked==true){
				if(checklist[j].name=='username'){
					username_list.push(checklist[j].value);
				}
				else if(checklist[j].name=='nature'){
					category_list.push(checklist[j].value);
				}	
				else if(checklist[j].name=='Format'){
					format_list.push(checklist[j].value);
				}		
				else if(checklist[j].name=='resource_type'){
					type_list.push(checklist[j].value);
				}
				else if(checklist[j].name=='sub_nature'){
					resource_list.push(checklist[j].value);
				}
				else if(checklist[j].name=='License'){
					license_list.push(checklist[j].value);
				}
			}
		}
		var data = new Array();
	
		data.push(username_list);
		data.push(category_list);
		data.push(format_list);
		data.push(type_list);
		data.push(resource_list);
		data.push(license_list);
		return data;
	}
	
	function filterFunction(value,facet)
	{
		var value = value;
		var facet = facet;
		var count=0;
		var checked = new Array();
		checklist=document.getElementsByClassName("check");
		for(j=0;j<checklist.length;j++){
			if(checklist[j].checked==true){
				checked[count]=checklist[j].value;
				count++;
			}
		}

		var data = new Array();
		data = arrayBuild(value,facet);
		$.ajax({
			url: "filterFacet.php",
			data:{list: data,utente_att:ut_att,role_att:role_att},
			type: "POST",
			async: true,
			dataType: 'json',
			success: function (data) {
				var $count=0;
				for($i=0;$i<data.length;$i++){
					$facet=data[$i][0].facet['cat'];
					facet_uc=$facet.charAt(0).toUpperCase() + $facet.slice(1);
					for($j=0;$j<data[$i].length;$j++){
						name=data[$i][$j].facet["facet_name"];
						value=data[$i][$j].facet["value"];						
						$count=$count+1;
						name_norm=name.replace(/ /g, "");
						name_norm=name_norm.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g,"");
						string='div-'+name_norm+'-'+facet_uc;
								if (document.getElementById(string) != null){
										document.getElementById(string).innerHTML=''+name+' ('+value+')';
								}
						}
				}

				for(j=0;j<checked.length;j++){
					var elem = document.getElementsByClassName('check');
					for(i=0;i<elem.length;i++){
						if(elem[i].value==checked[0]){
							elem[i].checked=true;
						}
					}
				}
			}
		});
	
        var internalDest = false;
        var tableFirstLoad = true;
			console.log(data);
        $.ajax({
			url: "getSolrResponse.php",
			data:{list:data,utente_att:ut_att,role_att:role_att},
            type: "POST",
            async: true,
            dataType: 'json',
            success: function(data) 
            {
				dashboardsList = data;

				$('#list_dashboard_cards').dynatable({
					table: {
						bodyRowSelector: 'div'
					},
					dataset: {
						records: data,
						perPageDefault: 8,
						perPageOptions: [4, 8, 12, 16, 20, 24, 28, 32]
					},
					writers: {
						_rowWriter: myCardsWriter
					},
					inputs: {
						paginationPrev: 'Previous',
						paginationNext: 'Next',
						paginationLinkPlacement: 'before'
                    },
					features: {
						recordCount: false,
						perPageSelect: false,
						search: true,
						paginate:true,
						pushState: false
					}
				});

				var dynatable = $('#list_dashboard_cards').data('dynatable');
				dynatable.sorts.clear();
                dynatable.sorts.add('title_header', 1); // 1=ASCENDING, -1=DESCENDING
				dynatable.settings.dataset.originalRecords = data;
				dynatable.process();			
				dynatable.paginationPage.set(1);
				dynatable.process();
				
                    
                $('#list_dashboard').bind('dynatable:afterProcess', function(e, dynatable)
				{
                    $("#dynatable-pagination-links-list_dashboard").appendTo("#dashboardListsPages div.dashboardsListMenuItemContent");
                    $("#dynatable-pagination-links-list_dashboard li").eq(0).remove();
                    $("#dynatable-pagination-links-list_dashboard li").eq(0).remove();
                    $("#dynatable-pagination-links-list_dashboard li").eq($("#dynatable-pagination-links-list_dashboard li").length - 1).remove();
                    $('#dashboardListsPages div.dashboardsListMenuItemContent').css("font-family", "Montserrat");
                    $('#dashboardListsPages div.dashboardsListMenuItemContent').css("font-weight", "bold");
                    $('#dashboardListsPages div.dashboardsListMenuItemContent').css("color", "white");
                    $('#dashboardListsPages div.dashboardsListMenuItemContent a').css("font-family", "Montserrat");
                    $('#dashboardListsPages div.dashboardsListMenuItemContent a').css("font-weight", "bold");
                    $('#dashboardListsPages div.dashboardsListMenuItemContent a').css("color", "white");
                    $("ul#dynatable-pagination-links-list_dashboard").css("-webkit-padding-start", "0px");
                    $("ul#dynatable-pagination-links-list_dashboard").css("-webkit-margin-before", "0px");
                    $("ul#dynatable-pagination-links-list_dashboard").css("-webkit-margin-after", "0px");
                    $("ul#dynatable-pagination-links-list_dashboard").css("padding", "0px");
					
					$('.rating-loading').rating({displayOnly: true, step: 0.1});
					$('.raty').raty({ starType: 'img',
						half        : false,
						halfShow    : false,
						starHalf    : 'star-half.png',
						starOff     : 'star-off.png',
						starOn      : 'star-on.png',
						click:function(score,evt){
							var score=score;
							var parents=$(this).closest('div').parent().find("div.raty").attr('id');
							var id= parents.replace("rate-","");
							if (confirm('Are you sure you want to rate ' + score +' this file?')) {
								$.ajax({
									type:'POST',
									url:'update_votes.php',
									data: {id : id, value: score},
									success: function(data){
										alert("rating saved");
									}
								});
		  
							}			
						}	
					});		
                    $("#dynatable-query-search-list_dashboard").prependTo("#dashboardListsSearchFilter div.dashboardsListMenuItemContent div.input-group");
                    $('#dynatable-search-list_dashboard').remove();
                    $("#dynatable-query-search-list_dashboard").css("border", "none");
                    $("#dynatable-query-search-list_dashboard").attr("placeholder", "Filter by title, author...");
                    $("#dynatable-query-search-list_dashboard").css("width", "100%");
                    $("#dynatable-query-search-list_dashboard").addClass("form-control");
                });
				$("#dynatable-pagination-links-list_dashboard").hide();
            },
			error: function(errorData)
            {
                    
            }
        });
	}
	
	function selectAllFunction(value){
		var facet = value;
		var valore = 'select-'+facet;
		var valore_check=document.getElementsByClassName(valore);
		var status = valore_check[0].checked;
		if (!status){
			checklist=document.getElementsByName(value);
			for(j=0;j<checklist.length;j++){
				checklist[j].checked=false;
			}
		}else{
			checklist=document.getElementsByName(value);
			for(j=0;j<checklist.length;j++){
				checklist[j].checked=true;
			}
			filterFunction();
		}	
	}
	
	$(window).bind("load", function() { 
	///////////
			if ((success_modify == 'OK')||(modified_status == 'OK')||(modify_ownership =='OK')||(error_status =='NO')||(error_message =='OK')){
						if (nascondi == 'hide'){
									$('#mainMenuCnt').hide();
									$('#title_row').hide();
									$('#mainCnt').removeClass('col-md-10');
									$('#mainCnt').addClass('col-md-12');
									$('.change_edit').attr("action","modify_resource.php?showFrame=false");
									$('.change_publish').attr("action","modify_status_resource.php?showFrame=false");
									$('.change_ownership').attr("action","modify_ownership.php?showFrame=false");
								}
				doRedirect();
			}
	});
	
	function doRedirect() {
						if (success_modify == 'OK'){
								alert('Resource successfully modified!');
							}
							if (modified_status == 'OK'){
								alert('Resource status successfully modified!');
							}
							if (modify_ownership =='OK'){	
								alert('Resource Ownership successfully modified!');
							}
							if (error_status =='NO'){
								alert('This resource cannot be published because any metadata are not corrected!');
							}
							if (error_message =='OK'){
									alert('Error during Ownership change');
							}
  }

</script>  
    </body>
</html>