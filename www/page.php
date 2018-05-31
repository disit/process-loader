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
		//echo ('true');
		$hide_menu= "hide";
		$_SESSION['showFrame']=$_REQUEST['showFrame'];
	}else{
		$hide_menu= "";
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

		
		
			<script src="js/star-rating.min.js" type="text/javascript"></script>
	<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
	<link href="css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />
		
		
		<link rel="stylesheet" href="jquery.raty.css">
<script src="jquery.raty.js"></script>



        
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
                        <div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin: 0; padding:15'>
                            <div class="row mainContentRow" id="dashboardsListTableRow">
                                <!--<div class="col-xs-12 mainContentRowDesc">List</div>-->
                                
                                <div class="col-xs-12 mainContentCellCnt" style='background-color: rgba(138, 159, 168, 1)'>
                                    <div id="dashboardsListMenu" class="row" style='margin-left:0; margin-right:0;'>
                                        <div id="dashboardListsViewMode" class="hidden-xs col-sm-6 col-md-2 dashboardsListMenuItem">
                                            <!--<div class="dashboardsListMenuItemTitle centerWithFlex col-xs-4">
                                                View<br>mode
                                            </div>-->
											<!--
                                            <div class="dashboardsListMenuItemContent centerWithFlex col-xs-12">
                                                <input id="dashboardListsViewModeInput" type="checkbox">
                                            </div>
											-->
                                        </div>
                                        <div id="dashboardListsPages" class="col-xs-12 col-sm-6 col-md-4 dashboardsListMenuItem">
                                           <!--<div class="dashboardsListMenuItemTitle centerWithFlex col-xs-4">
                                                List<br>pages
                                            </div>-->
                                            <div class="dashboardsListMenuItemContent centerWithFlex col-xs-12">
                                                
                                            </div>
                                        </div>
                                        <div id="dashboardListsSearchFilter" class="col-xs-12 col-sm-6 col-md-4 dashboardsListMenuItem">
                                            <!--<div class="dashboardsListMenuItemTitle centerWithFlex col-xs-3">
                                                Search
                                            </div>-->
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
										<!--<button type="button" class="btn btn-warning" style= "margin-left:45px; margin-bottom:20px; " id="facet-button">View facet menu</button>-->
										<!--<button type="button" class="btn btn-warning" style= "margin-left:45px; margin-bottom:20px; " id="facet-reset-button">Reset</button>-->
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
	function myCardsWriter(rowIndex, record, columns, cellWriter)
	{
		var title = record.file_name;	 
		var username=record.username;
		//var file_type=record.file_type;
		//var file_type=record.resource_type;
		var file_type=record.resource_type;
		//var category=record.category;
		var category=record.nature;
		var description=record.description;
		var Id=record.id; 
		
		//CONTROLLO PER INSERIRE UN IMMAGINE DI DEFAULT SE IMG è NULL
		if (record.image == null||record.image == ''){
			if (record.app_type=="ETL"){ record.image='imgUploaded/default_images/ETL.png';}
			else if (record.app_type=="R"||record.app_type=="Java"){record.image='imgUploaded/default_images/DataAnalitics.png';}
			else if (record.app_type=="IoTApp"){record.image='imgUploaded/default_images/IoTApp.png';}
			else if (record.app_type=="IoTBlocks"){record.image='imgUploaded/default_images/IoTBlocks.png';}
			else if (record.app_type=="AMMA"){record.image='imgUploaded/default_images/AMMA.png';}
			else if (record.app_type=="ResDash"){record.image='imgUploaded/default_images/ResDash.png';}
			else if (record.app_type=="DevDash"){record.image='imgUploaded/default_images/DevDash.png';}
			else if (record.app_type=="MicroService"){record.image='imgUploaded/default_images/MicroService.png';}
			else if (record.app_type=="Mobile App"){record.image='imgUploaded/default_images/MobileApp.png';}
			else if(record.app_type=="ControlRoomDashboard"){record.image='imgUploaded/default_images/ControlRoomDashboard.png';}
			else {record.image='imgUploaded/default_images/DataAnalitics.png';}
		}
		//

		var border = "";
		var status_pub = "";
		if (record.Public == false){
			border = 'border-style:solid; border-color:#00bfff; border-width:2px;'
			status_pub ='<li class="list-group-item" id="status_pub hidden"><b>Status: </b>Not Published</li>';
		}else{
			status_pub ='<li class="list-group-item" id="status_pub" hidden><b>Status: </b>Published</li>';
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
		
		

		//var file_type = record.file_type;
		//var file_type = record.resource_type;
		var bg_color='#6C8793';
		var file_type = record.resource_type;
		if(file_type=="ETL" || file_type=="R" || file_type=="Java"){
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="50%" width="50%" border="0" ></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin"><input name="input"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div><ul class="list-group"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>Nature: </b>'+record.nature+'</li><li class="list-group-item"><b>Sub Nature: </b>'+record.sub_nature+'</li><li class="list-group-item"><b>Protocol: </b>'+record.protocol+'</li><li class="list-group-item"><b>Real-time: </b>'+record.realtime+'</li><li class="list-group-item"><b>Periodic: </b>'+record.realtime+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+record.dateofpublication+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			
			//var bg_color='#515151';
		}
		if(file_type=="IoTBlocks" || file_type=="IoTApp"){
				
			//var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="50%" width="50%" border="0" ></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin"><input name="input"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div><ul class="list-group"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item"><b>User: </b>'+record.username+'</li><li class="list-group-item"><b>File type: </b>'+record.file_type+'</li><li class="list-group-item"><b>Resource: </b>'+record.resource+'</li><li class="list-group-item"><b>Category: </b>'+record.category+'</li><li class="list-group-item"><b>Protocol: </b>'+record.protocol+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+record.dateofpublication+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li></ul></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="50%" width="50%" border="0" ></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin"><input name="input"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div><ul class="list-group"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>Nature: </b>'+record.nature+'</li><li class="list-group-item"><b>Sub Nature: </b>'+record.sub_nature+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+record.dateofpublication+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			//var bg_color="#6C8793";
		}
		if(file_type=="Mobile App"){
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="50%" width="50%" border="0" ></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin"><input name="input"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div><ul class="list-group"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>OS: </b>'+record.OS+'</li><li class="list-group-item"><b>Category: </b>'+record.nature+'</li><li class="list-group-item"><b>OpenSource: </b>'+record.OpenSource+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+record.dateofpublication+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			//var bg_color="#6C8793";
		}
		if(file_type=="AMMA" || file_type=="DevDash" || file_type=="ResDash" ){
			//var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="50%" width="50%" border="0" ></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin"><input name="input"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div><ul class="list-group"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item"><b>User: </b>'+record.username+'</li><li class="list-group-item"><b>File type: </b>'+record.file_type+'</li><li class="list-group-item"><b>Resource: </b>'+record.resource+'</li><li class="list-group-item"><b>Category: </b>'+record.category+'</li><li class="list-group-item"><b>Real-time: </b>'+record.realtime+'</li><li class="list-group-item"><b>Periodic: </b>'+record.realtime+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+record.dateofpublication+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li></ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="50%" width="50%" border="0" ></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin"><input name="input"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div><ul class="list-group"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>Nature: </b>'+record.nature+'</li><li class="list-group-item"><b>Sub Nature: </b>'+record.sub_nature+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+record.dateofpublication+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			//var bg_color="#6C8793";
		}
		if(file_type=="Banana Library" ){
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="50%" width="50%" border="0" ></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin"><input name="input"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div><ul class="list-group"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>Nature: </b>'+record.nature+'</li><li class="list-group-item"><b>Sub Nature: </b>'+record.sub_nature+'</li><li class="list-group-item"><b>Real-time: </b>'+record.realtime+'</li><li class="list-group-item"><b>Periodic: </b>'+record.realtime+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+record.dateofpublication+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			//var bg_color="#6C8793";
		}
		if(file_type=="MicroService" || file_type=="Microservice" ){
			//var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="50%" width="50%" border="0" ></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin"><input  name="input"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div><ul class="list-group"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item"><b>User: </b>'+record.username+'</li><li class="list-group-item"><b>File type: </b>'+record.file_type+'</li><li class="list-group-item"><b>Resource: </b>'+record.resource+'</li><li class="list-group-item"><b>Category: </b>'+record.category+'</li><li class="list-group-item"><b>Real-time: </b>'+record.realtime+'</li><li class="list-group-item"><b>Periodic: </b>'+record.realtime+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+record.dateofpublication+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li></ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			var modal='<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="50%" width="50%" border="0" ></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin"><input  name="input"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div><ul class="list-group"><li class="list-group-item"><b>Description: </b>'+description+'</li><li class="list-group-item" id="itemUsername" hidden><b>Username: </b>'+record.username+'</li><li class="list-group-item"><b>Resource Type: </b>'+record.resource_type+'</li><li class="list-group-item"><b>Nature: </b>'+record.nature+'</li><li class="list-group-item"><b>Sub Nature: </b>'+record.sub_nature+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+record.dateofpublication+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li>'+status_pub+'</ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			//var bg_color="#6C8793";
		}
			
			
		var myOwn = "";
		 if (username == ut_att){ myOwn = '<i class="fa fa-user-circle" style="font-size: 1.0em; color:#00bfff;" data-toggle="tooltip" title="My Own"/>'; }else {myOwn = "";}
		//

		var cardDiv = '<div data-uniqueid="' + record.Id + '" data-headerColor = "' + headerColor + '" data-headerFontColor="' + record.headerFontColor + '"   class="dashboardsListCardDiv col-xs-12 col-sm-4 col-md-3">' +
					  '<div class="dashboardsListCardInnerDiv" style="background-color:'+bg_color+';'+border+'">' +
					  '<div class="dashboardsListCardTitleDiv col-xs-12 centerWithFlex"><a " href="'+record.link+'" class="link-file" download>'+myOwn+' '+title+'</a></div>' +	
					  //'<div class="col-xs-12 centerWithFlex">' +myOwn+'</div>'+					  
					   // '<div class="dashboardsListCardOverlayDiv col-xs-12 centerWithFlex"></div>' +
					   // '<div class="dashboardsListCardOverlayTxt col-xs-12 centerWithFlex">View Details</div>' +
						// alert(record.image);
					   '<div class="dashboardsListCardImgDiv" style="background-image:url('+record.image+'); overflow: auto;"></div>' +
					  // '<div class="dashboardsListCardImgDiv"><img src="'+record.image+'" style="max-height: 100%; max-width: 100%; margin-left: auto; margin-right: auto; background-size: auto 100%; background-repeat: no-repeat; background-position: center top;"></div>' +
					   '<div class="dashboardsListUsernameDiv" hidden>' + "Username: " + username +'</div>' +
					   '<div class="dashboardsListfile_typeDiv">'+ "Resource type: " + file_type + '</div>' +
					   '<div class="dashboardsListCategoryDiv">'+ "Nature: " + category + '</div>' +
					   '<div class="dashboardsListDescriptionDiv" style="text-overflow:ellipsis;">'+ "Description: " + description + '</div>' +
						'<div class="raty" id="rate-'+record.id+'">Rate: </div>'+
						'<div class="dashboardsListCardClick2EditDiv col-xs-12 centerWithFlex">' +
						'<button type="button" data-toggle="modal" data-target="#info-modal_proc'+record.id+'"class="editDashBtnCard">View Details</button>' +
						'</div>' +  
						'</div>' +
							   //QUI VA GESTITO IL DIFFERENTE MODAL A SECONDA DEL FILE_TYPE
						// '<div class="modal fade" id="info-modal_proc'+record.id+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Details of Resource: '+record.file_name+'</h4></div><div class="modal-body"><center><img src="'+record.image+'" height="50%" width="50%" border="0" ></a><div class="row"><h4>Resource name: <span>'+record.file_name+'</span></h4><h4>Rating:</h4><div class="ratin"><input  name="input"  class="rating-loading" step="0.1 "value="'+record.average_stars+'">( mean '+record.average_stars+' on '+record.votes+' votes)</div><ul class="list-group"><li class="list-group-item"><b>Description: </b>'+record.description+'</li><li class="list-group-item"><b>User: </b>'+record.username+'</li><li class="list-group-item"><b>File type: </b>'+record.file_type+'</li><li class="list-group-item"><b>Resource: </b>'+record.resource+'</li><li class="list-group-item"><b>Category: </b>'+record.category+'</li><li class="list-group-item"><b>Protocol: </b>'+record.protocol+'</li><li class="list-group-item"><b>Real-time: </b>'+record.realtime+'</li><li class="list-group-item"><b>Periodic: </b>'+record.realtime+'</li><li class="list-group-item"><b>Date_of_publication: </b>'+record.dateofpublication+'</li><li class="list-group-item"><b>License: </b>'+record.license+'</li></ul><div class="space-ten"></div></div></div><div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>'+
						modal+
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
		//filterFunction();
		location.reload();
	});
	
	$(document).ready(function () 
	{ 
	//
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
	
	var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#mainCnt').removeClass('col-md-10');
			$('#mainCnt').addClass('col-md-12');
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
					//alert(JSON.stringify(data[$i][0].facet["facet_name"]));
					$facet=data[$i][0].facet['cat'];
					facet_uc=$facet.charAt(0).toUpperCase() + $facet.slice(1);
					max= data[$i].length;
					//$append='<div class="facet-field col-xs-6 col-sm-2 col-md-2"><fieldset><legend onclick="apri_menu(this)" id="find-'+$facet+'">'+facet_uc+'</legend><div class="control-group" id="control-group-'+$facet+'" ></div></fieldset></div>';
					$append='<div class="btn-group dropdown"><button class="btn btn-warning dropdown-toggle btn-group drop-'+facet_uc+'" type="button" data-toggle="dropdown">'+facet_uc+'  <span class="caret"></span></button><ul class="dropdown-menu" id="control-group-'+$facet+'" style="height: auto;  max-height: 200px;  overflow-x: hidden;"><li><a href="#" ><input class="check select-'+$facet+'" type="checkbox" checked  onchange="selectAllFunction(value)" value="'+$facet+'">Select All</a></li><li class="divider"></li></ul></div>';
					$("#facet-menu").append($append);
					for($j=0;$j<data[$i].length;$j++){
						$name=data[$i][$j].facet["facet_name"];
						$value=data[$i][$j].facet["value"];
						$count=$count+1;
						/*
						if ($name == null || $name == ''){
							$name= "Others";
						}
						*/
						var name_norm=$name.replace(/ /g,"");
						name_norm=name_norm.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g,"");

						var name_div='div-'+name_norm+'-'+facet_uc;
						//var name_div='div-'+$count;
						//$append2='<label class="radio"> <input class="check" id="check'+$count+'" type="checkbox" onchange="filterFunction(value,name)" name="'+$facet+'"value="'+$name+'"><div style="display:inline;" id="'+name_div+'">'+$name+' ('+$value+') </div> </label> ';
						$append2='<li  class="check_link" value="'+$value+'"><a href="#"><input class="check '+$facet+'" id="check'+$count+'" type="checkbox" onchange="filterFunction(value,name)" name="'+$facet+'"value="'+$name+'" checked><div style="display:inline;" id="'+name_div+'">'+$name+' ('+$value+') </div></a></li>';
						$string = '#control-group-'+$facet+'';
						$($string).append($append2);
						//nascondere il facet se è 0
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

						//$(this).find('div.dashboardsListCardImgDiv').css("background-size", "auto 100%");// 100% 100% la distorce  fittare le immagini nel quadrato
						$(this).find('div.dashboardsListCardImgDiv').css("background-size", "contain");
						//
						$(this).find('div.dashboardsListCardImgDiv').css("background-repeat", "no-repeat");
						$(this).find('div.dashboardsListCardImgDiv').css("background-position", "center center");
						//$(this).find('div.dashboardsListCardInnerDiv').css("width", "100%");
						$(this).find('div.dashboardsListCardInnerDiv').css("height","100%");
						$(this).find('div.dashboardsListCardInnerDiv').css("height", $(this).height() + "px");
						$(this).find('div.dashboardsListCardOverlayDiv').css("height", $(this).find('div.dashboardsListCardImgDiv').height() + "px");
						$(this).find('div.dashboardsListCardOverlayTxt').css("height", $(this).find('div.dashboardsListCardImgDiv').height() + "px");
						$(this).find('div.dashboardsListUsernameDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListUsernameDiv').css("color", "white");
						$(this).find('div.dashboardsListUsernameDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListUsernameDiv').css("-webkit-padding-start", "30px");
						//overflow
						//$(this).find('div.dashboardsListUsernameDiv').css("display","block");
						//$(this).find('div.dashboardsListUsernameDiv').css("width","200px");
						$(this).find('div.dashboardsListUsernameDiv').css("width","80%");
						$(this).find('div.dashboardsListUsernameDiv').css("overflow","hidden");
						$(this).find('div.dashboardsListUsernameDiv').css("white-space","nowrap");
						$(this).find('div.dashboardsListUsernameDiv').css("text-overflow","ellipsis");
						

						$(this).find('div.dashboardsListfile_typeDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListfile_typeDiv').css("color", "white");
						$(this).find('div.dashboardsListfile_typeDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListfile_typeDiv').css("-webkit-padding-start", "30px");
						//overflow
						$(this).find('div.dashboardsListfile_typeDiv').css("display","block");
						//$(this).find('div.dashboardsListfile_typeDiv').css("width","200px");
						$(this).find('div.dashboardsListfile_typeDiv').css("width","80%");
						$(this).find('div.dashboardsListfile_typeDiv').css("overflow","hidden");
						$(this).find('div.dashboardsListfile_typeDiv').css("white-space","nowrap");
						$(this).find('div.dashboardsListfile_typeDiv').css("text-overflow","ellipsis");
						

						$(this).find('div.dashboardsListCategoryDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListCategoryDiv').css("color", "white");
						$(this).find('div.dashboardsListCategoryDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListCategoryDiv').css("-webkit-padding-start", "30px");
						//overflow
						$(this).find('div.dashboardsListCategoryDiv').css("display","block");
						//$(this).find('div.dashboardsListCategoryDiv').css("width","200px");
						$(this).find('div.dashboardsListCategoryDiv').css("width","80%");
						$(this).find('div.dashboardsListCategoryDiv').css("overflow","hidden");
						$(this).find('div.dashboardsListCategoryDiv').css("white-space","nowrap");
						$(this).find('div.dashboardsListCategoryDiv').css("text-overflow","ellipsis");

						$(this).find('div.dashboardsListDescriptionDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListDescriptionDiv').css("color", "white");
						$(this).find('div.dashboardsListDescriptionDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListDescriptionDiv').css("-webkit-padding-start", "30px");
						//overflow
						$(this).find('div.dashboardsListDescriptionDiv').css("display","block");
						//$(this).find('div.dashboardsListDescriptionDiv').css("width","200px");
						$(this).find('div.dashboardsListDescriptionDiv').css("width","80%");
						$(this).find('div.dashboardsListDescriptionDiv').css("overflow","hidden");
						$(this).find('div.dashboardsListDescriptionDiv').css("white-space","nowrap");
						$(this).find('div.dashboardsListDescriptionDiv').css("text-overflow","ellipsis");

						$(this).find('div.dashboardsListRatingDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListRatingDiv').css("color", "white");
						$(this).find('div.dashboardsListRatingDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListRatingDiv').css("-webkit-padding-start", "30px");
						
						//overflow dashboardsListCardDiv
						$(this).find('div.dashboardsListCardDiv').css("font-family", "Montserrat");
						$(this).find('div.dashboardsListCardDiv').css("color", "white");
						$(this).find('div.dashboardsListCardDiv').css("font-weight", "bold");
						$(this).find('div.dashboardsListCardDiv').css("-webkit-padding-start", "30px");
						//
						
						//OverflowTITOLO
						$(this).find('div.dashboardsListCardTitleDiv').css("display","block");
						//$(this).find('div.dashboardsListCardTitleDiv').css("overflow","hidden");
						$(this).find('div.dashboardsListCardTitleDiv').css("white-space","nowrap");
						//$(this).find('div.dashboardsListCardTitleDiv').css("text-overflow","ellipsis");
						//
						$(this).find('a.link-file').css("display","block");
						$(this).find('a.link-file').css("overflow","hidden");
						$(this).find('a.link-file').css("white-space","nowrap");
						$(this).find('a.link-file').css("text-overflow","ellipsis");
						//

						$(this).find('div.raty').css("font-family", "Montserrat");
						$(this).find('div.raty').css("color", "white");
						$(this).find('div.raty').css("font-weight", "bold");
						$(this).find('div.raty').css("-webkit-padding-start", "30px");

						$(this).find('div.modal-dialog').css("font-family", "Montserrat");
						$(this).find('div.modal-dialog').css("color", "white");
						$(this).find('div.modal-dialog').css("font-weight", "bold");
		
						/// BEGIN change background color of "View Details" modal
						$('#info-modal_proc'+data[count].id+' > div > div').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(1)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(2)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(3)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(4)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(5)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(6)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(7)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(8)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(9)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(10)').css("background-color", "rgba(108, 135, 147, 1)");
						$('#info-modal_proc'+data[count].id+' > div > div > div.modal-body > center > div > ul > li:nth-child(11)').css("background-color", "rgba(108, 135, 147, 1)");

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
						//ajax: true,
						//ajaxUrl: 'page.php',
						//ajaxOnLoad: true,
						//records: []
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
				dynatable.sorts.add('title_header', 1); // 1=ASCENDING, -1=DESCENDING
				dynatable.process();
					  
				$('#list_dashboard').bind('dynatable:afterProcess', function(e, dynatable){
					$("#dynatable-pagination-links-list_dashboard").appendTo("#dashboardListsPages div.dashboardsListMenuItemContent");
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
				//APPEND NEL MENù A SINISTRA E POI ALTRO AJAX PER TIRARE FUORI I NUMERI AGGIORNATI?
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

						document.getElementById(string).innerHTML=''+name+' ('+value+')';
						

						
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
		//alert(facet);
		var valore = 'select-'+facet;
		//alert (valore);
		var valore_check=document.getElementsByClassName(valore);
		var status = valore_check[0].checked;
		//alert (status);
		//checklist = $(this).parent().html;
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

</script>  
    </body>
</html>