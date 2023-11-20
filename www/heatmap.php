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

require 'sso/autoload.php';
use Jumbojett\OpenIDConnectClient;    
   
include('config.php'); // Includes Login Script
include('control.php');
include('external_service.php');
include('functionalities.php');

$list_api2 = array();
$list_api = array();
$array_pub = array();
$total_list = 0;
$array_del = array();
$array_delegator = array();
$utente = $_SESSION['username'];
$sum_own = 0;
///INIZIO ERRORE /////

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
		$sf="false";
	}else{
		$hide_menu= "";
		$sf="true";
	}	
}else{
	$hide_menu= "";
	$sf="true";
}

if (isset($_REQUEST['pageTitle'])){
	$default_title = $_REQUEST['pageTitle'];
}else{
	$default_title = "Heatmap Manager";
}
//
$organization = '';
$utente_ownership = '';
//// EXTRACT_HEATMAPS FROM DB ////
$pagina_attuale = $_SERVER['REQUEST_URI'];
$link = mysqli_connect($host_heatmap, $username_heatmap, $password_heatmap) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_heatmap);

if (isset($_REQUEST['orderBy'])){
		$order = $_REQUEST['orderBy'];
}else{
	$order = 'map_name';	
}

if (isset($_REQUEST['order'])){
	$by = $_REQUEST['order'];
}else{
	$by = 'DESC';
}

$start_from = 0;
if (isset($_REQUEST['limit'])|| $_REQUEST['limit']!==""){
$limit=$_REQUEST['limit'];
}else{
$limit = 10;  
}

if ($_REQUEST['limit'] == ""){
$limit = 10;  
}

if (isset($_REQUEST["page"])) { 
		$page  = $_REQUEST["page"]; 
	} else { 
		$page=1; 
	};  
$start_from = ($page-1) * $limit; 

	//$query_n =	"SELECT DISTINCT map_name AS 'name' FROM metadata ORDER BY metadata.".$order." ".$by." LIMIT " . $start_from . ", " . $limit;
	$query_n =	"SELECT DISTINCT map_name AS 'name' FROM metadata";

///
	$count_number = 0;
    $result = mysqli_query($link, $query_n) or die(mysqli_error($link));
	
    $process_list = array();
    $num_rows     = $result->num_rows;
	$num_r = 0;
////////////

/////////////////*******************////////////////		
		$number_rows_pub = 0;
		
		if ($result->num_rows > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
					
					$num_result=$result->num_rows;
					$visibility = '';
					$map_name = $row['name'];
				/***QUERY PER OTTENERE I DATI***/
				
					$organization = '';
					$nature = '';
					$subnature = '';
				
					$count_number = $row['count_number'];
					$min_date = $row['min_date'];
					$max_date = $row['max_date'];
					
					//$query_istances = "SELECT metric_name, min_date, max_date, num AS count_number FROM heatmap.stats WHERE map_name='".$map_name."'";
					//$result_istances = mysqli_query($link, $query_istances) or die(mysqli_error($link));
					//
					$query_mt = "SELECT metadata.org, metadata.metric_name, metadata.nature, metadata.subnature FROM heatmap.metadata where heatmap.metadata.map_name='".$map_name."' order by date desc limit 1";
					//$query_mt = "SELECT metadata.org, metadata.metric_name, metadata.nature, metadata.subnature FROM heatmap.metadata order by date desc limit 1";
					$result_mt = mysqli_query($link, $query_mt) or die(mysqli_error($link));
					//
						while ($row3 = mysqli_fetch_assoc($result_mt)) {
											$organization = $row3['org'];
											$metric_name = $row3['metric_name'];
											$nature = $row3['nature'];
											$subnature = $row3['subnature'];
										}
						
					
										for($x=0; $x<$total_list; $x++){
												$ind = $row['name'];
												$visibility = $array_pub[$ind];
												$a = $list_api[$x];
												$name = $a ->elementId;
												if($name == $row['name']){
													$utente_ownership = $a ->username;
													}				
												}
												$deleg_val='no';
												if(isset($array_del[$ind])){
														$deleg_val='yes';
													}else{
														$deleg_val='no';
													}
												$listFile = array("map_name" => $row['name'],
																  //"metric_name" => $row['metric_name'],
																  "metric_name" => $metric_name,
																  "min_date" => $min_date,
																  "max_date" => $max_date,
																  "count_number" => $count_number,
																  "username" => $utente_ownership,
																  "organization" =>$organization,
																  "visibility" => $visibility,
																  "delegated" => $deleg_val,
																  "nature"=>$nature,
																  "subnature"=>$subnature, 
																  "group_delegated"=> 'no');
												
											array_push($process_list, $listFile);
										
					
        }
    }
	//
	//echo json_encode($process_list);
	//echo('<br />');
	if($role_att =='RootAdmin'){
			
		//
			$query_count =	"SELECT count(distinct map_name) as count FROM metadata";
			$result_count = mysqli_query($link, $query_count) or die(mysqli_error($link));
			while ($row_count  = mysqli_fetch_assoc($result_count)) {	
							$total_rows = $row_count['count'];
						}
						//
		//$total_rows = $result->num_rows;
	}else{
		
		$total_rows = $num_r;	
	}
	/*******/
//QUERY COLOR_MAP// 
$process_cm = array();
$query_cm = "SELECT DISTINCT metric_name FROM heatmap.colors";
$result_cm = mysqli_query($link, $query_cm) or die(mysqli_error($link));
	if ($result_cm->num_rows >0){
		while ($row_cm = mysqli_fetch_assoc($result_cm)) {	
				$listFile_cm = array(
					"metric_name" => $row_cm['metric_name']
				);
				array_push($process_cm, $listFile_cm);
			}
	$total_cm=$result_cm->num_rows;	
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

        <!-- Color Picker -->
        <!--
		<link href="//cdn.rawgit.com/twbs/bootstrap/v4.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
		-->
        <script src="bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>
        <script src="bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        <!-- -->
        <link href="bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css" rel="stylesheet" />
        <link href="bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
		<!-- -->
		
		<!-- Tabella -->
		<!--<link rel="stylesheet" type="text/css" href="lib/datatables.css">-->
		<script type="text/javascript" charset="utf8" src="lib/datatables.js"></script>
		<script type="text/javascript" src="lib/dataTables.responsive.min.js"></script>
		<script type="text/javascript" src="lib/dataTables.bootstrap4.min.js"></script>
		<script type="text/javascript" src="lib/jquery.dataTables.min.js"></script>
		<link href="lib/responsive.dataTables.css" rel="stylesheet" />
		<!--	
		<script src="lib/jquery-3.3.1.js"></script>	
		-->
		<!-- -->
    </head>
    <style>
        .hidden {
            display: none;
        }
		
		.paginate_button{
			padding: 2px;
		}
        
        td {
            vertical-align: middle;
        }
        
        #heatmap_table {
            margin-bottom: 0px;
        }
        
        .mainContentCnt {
            margin-top: 0px;
        }
        
        #view-modal {
            width: auto;
        }
        
        #value_table {
            width: 100%;
        }
        
        #typology_table {
            text-align: center;
        }
		
		.fa-circle{
			font-size: 24px;
		}
		
		.fa-times{
			font-size: 18px;
			color: red;
		}
		
		#delegationsModalRightCnt{
			padding: 15px;
		}
		
		.loader {
				  border: 16px solid #f3f3f3; /* Light grey */
				  border-top: 16px solid #3498db; /* Blue */
				  border-radius: 50%;
				  width: 120px;
				  height: 120px;
				  animation: spin 2s linear infinite;
				  position: absolute;
				  left: 50%;
				  right: 50%;
				}

				@keyframes spin {
				  0% { transform: rotate(0deg); }
				  100% { transform: rotate(360deg); }
				}
				
		#delegationsForm{
			background-color: rgba(138, 159, 168, 1);
		}
		
		#delegationsModalBody{
			background-color: rgba(138, 159, 168, 1);
		}
		
		#colormap_header{
			background-color: rgba(138, 159, 168, 1);
		}
		
		.active{
			background-color: rgba(138, 159, 168, 1);
		}
		
		#delegationsTable{
			width: 100%;
		}
		
		#groupDelegationsTable{
			width: 100%;
		}

		iframe {
  width: 100vw;
  height: 100vh;
}
		
		
    </style>

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
                                <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt">
                                    <?php echo urldecode($_REQUEST['pageTitle']); ?>
                                </div>
                                <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt">
                                    <?php 
						include "mobMainMenu.php" 
						?>
                                </div>
                            </div>
                            <div class="row" style="width: 98%;">
                                <!-- -->
                                <div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); padding-top:20px;'>
									
									 <div>
                                <table id="heatmap_table" class="table table-striped table-bordered display responsive no-wrap" style="width: 100%;">
                                <thead class="dashboardsTableHeader">
                             <?php
									//
									$icon_by = '<i class="fa fa-caret-down" style="color:white"></i>';
									//
									if ($by == 'DESC'){
										$by_par = 'ASC';

										$icon_by = '<i class="fa fa-caret-down" style="color:white"></i>';
									}else{
										$by_par = 'DESC';
										$icon_by = '<i class="fa fa-caret-up" style="color:white"></i>';
									}
									//$list_types
									$corr_page1= $_REQUEST["page"];
									$pagina_attuale2='heatmap.php?showFrame='.$sf.'&page='.$corr_page1.'&orderBy=id&order='.$by_par.'&limit='.$limit.'';
												
										
												if(($role == 'RootAdmin')){
												echo ('<tr>		
													<th class="map_name"><div><a>Map name</a></div></th>
													<th class="metric_name"><div><a>Color Map</a></div></th>
													<th class="user"><div><a>Owner</a></div></th>
													<th class="nature"><div><a>Nature</a></div></th>
													<th class="subnature"><div><a>Subnature</a></div></th>
													<th class="organization"><div><a>Organization</a></div></th>
													<th><div><a>Details</a></div></th>
													<th><a>Management</a></th>
													<th class="view"><div><a>View Data</a></div></th>
													<th class="delete"><div><a>Delete</a></div></th>
													</tr>');
												}else{
												echo ('<tr>		
													<th class="map_name"><div><a>Map name</a></div></th>
													<th class="metric_name"><div><a>Color Map</a></div></th>
													<th class="nature"><div><a>Nature</a></div></th>
													<th class="subnature"><div><a>Subnature</a></div></th>
													<th class="organization"><div><a>Organization</a></div></th>
													<th><div><a>Details</a></div></th>
													<th class="view"><div><a>View Data</a></div></th>
													</tr>');	
												}
												
												//////////
							?>
                            </thead>
                            <tbody>
                            <?php
									
									if(($role == 'RootAdmin')){
											for ($i = 0; $i <= $num_rows; $i++) {
												
												if ($process_list[$i]['map_name']){
													if($process_list[$i] != $process_list[$i-1]){
															$del = '';
															if ($process_list[$i]['delegated'] == 'yes'){
																$del = '	(Delegated)';
															}else{
																$del = '';	
															}
															$group_del = '';
															if($process_list[$i]['group_delegated']=='yes'){
																$group_del = '	(Group Delegated)';
															}else{
																$group_del = '';
															}
															$viewOwner ="<button type='button' class='viewDashBtn viewList' data-target='#view_user-modal' data-toggle='modal' onclick='viewOwner(\"".$process_list[$i]['map_name']."\")'>VIEW</button>";
															
													echo ("<tr>
																<td>".$process_list[$i]['map_name']."".$del."".$group_del."</td>
																<td>
																<button type='button' class='viewDashBtn viewType' data-target='#typology-modal' data-toggle='modal' value='".$process_list[$i]['metric_name']."'>VIEW</button>  
																<button type='button' class='editDashBtn editColor' data-target='#edit-colormap' data-toggle='modal' map_name='".$process_list[$i]['map_name']."' value='".$process_list[$i]['metric_name']."'>EDIT</button>
																<p style='display: inline; margin-left: 2%;'>".$process_list[$i]['metric_name']."</p>
																</td>");
															echo("<td>".$viewOwner."</td>");
															echo("<td>".$process_list[$i]['nature']."</td>");
															echo("<td>".$process_list[$i]['subnature']."</td>");
															echo("<td>".$process_list[$i]['organization']."</td>");
															//echo("<td>".$process_list[$i]['min_date']."</td>");
															//echo("<td>".$process_list[$i]['max_date']."</td>");
															//echo("<td>".$process_list[$i]['count_number']."</td>");
															echo("<td><button type='button' class='viewDashBtn viewList' data-target='#view-details' data-toggle='modal' onclick=viewdetails('".$process_list[$i]['map_name']."') value='".$process_list[$i]['map_name']."'>VIEW</button></td>");
															echo("<td><button type='button' class='editDashBtn delegateBtn' data-target='#delegationsModal' data-toggle='modal' value='".$process_list[$i]['map_name']."' user='".$process_list[$i]['username']."' visibility='".$process_list[$i]['visibility']."' org='".$process_list[$i]['organization']."' nature='".$process_list[$i]['nature']."' subnature='".$process_list[$i]['subnature']."'>EDIT</button></td>");
															echo("<td><button type='button' class='viewDashBtn viewList' data-target='#view-modal' data-toggle='modal' value='".$process_list[$i]['map_name']."'>VIEW</button>	<button type='button' class='editDashBtn previewList' data-target='#preview-modal' data-toggle='modal' value='".$process_list[$i]['map_name']."' org='".$process_list[$i]['organization']."'>PREVIEW</button></td>");
															echo("<td><button type='button' class='delDashBtn del_metdata' data-target='#delete-modal' data-toggle='modal' value='".$process_list[$i]['map_name']."'>DEL</button></td>");
															echo("</tr>");
														}
													}
												}
									}else{
											for ($i = 0; $i <= $num_rows; $i++) {
												
												if ($process_list[$i]['map_name']){
													if($process_list[$i] != $process_list[$i-1]){
															$del = '';
															if ($process_list[$i]['delegated'] == 'yes'){
																$del = '	(Delegated)';
															}else{
																$del = '';	
															}
															$group_del = '';
															if($process_list[$i]['group_delegated']=='yes'){
																$group_del = '	(Group Delegated)';
															}else{
																$group_del = '';
															}
															$viewOwner ="<button type='button' class='viewDashBtn viewList' data-target='#view_user-modal' data-toggle='modal' onclick='viewOwner(\"".$process_list[$i]['map_name']."\")'>VIEW</button>";
															
													echo ("<tr>
																<td>".$process_list[$i]['map_name']."".$del."".$group_del."</td>
																<td>
																<button type='button' class='viewDashBtn viewType' data-target='#typology-modal' data-toggle='modal' value='".$process_list[$i]['metric_name']."'>VIEW</button>  
																<p style='display: inline; margin-left: 2%;'>".$process_list[$i]['metric_name']."</p>
																</td>");
															//echo("<td>".$viewOwner."</td>");
															echo("<td>".$process_list[$i]['nature']."</td>");
															echo("<td>".$process_list[$i]['subnature']."</td>");
															echo("<td>".$process_list[$i]['organization']."</td>");
															//echo("<td>".$process_list[$i]['min_date']."</td>");
															//echo("<td>".$process_list[$i]['max_date']."</td>");
															//echo("<td>".$process_list[$i]['count_number']."</td>");
															echo("<td><button type='button' class='viewDashBtn viewList' data-target='#view-details' data-toggle='modal' onclick=viewdetails('".$process_list[$i]['map_name']."') value='".$process_list[$i]['map_name']."'>VIEW</button></td>");
															//echo("<td><button type='button' class='editDashBtn delegateBtn' data-target='#delegationsModal' data-toggle='modal' value='".$process_list[$i]['map_name']."' user='".$process_list[$i]['username']."' visibility='".$process_list[$i]['visibility']."' org='".$process_list[$i]['organization']."' nature='".$process_list[$i]['nature']."' subnature='".$process_list[$i]['subnature']."'>EDIT</button></td>");
															//echo("<td><button type='button' class='viewDashBtn viewList' data-target='#view-modal' data-toggle='modal' value='".$process_list[$i]['map_name']."'>VIEW</button></td>");
															echo("<td><button type='button' class='viewDashBtn viewList' data-target='#view-modal' data-toggle='modal' value='".$process_list[$i]['map_name']."'>VIEW</button>	<button type='button' class='editDashBtn previewList' data-target='#preview-modal' data-toggle='modal' value='".$process_list[$i]['map_name']."' org='".$process_list[$i]['organization']."'>PREVIEW</button></td>");
															//echo("<td><button type='button' class='delDashBtn del_metdata' data-target='#delete-modal' data-toggle='modal' value='".$process_list[$i]['map_name']."'>DEL</button></td>");
															echo("</tr>");
														}
													}
												}
									}

							?>
                                 </tbody>
                             </table>   
                            </div>
						</div>
                                        <!----->
										
                                <div class="modal fade bd-example-modal-lg" id="delete-modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form name="Modify Metadata" method="post" action="#" id="delete_Heatmap">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: white">Delete Heatmap</div>
                                                <div class="modal-body" style="background-color: white">
                                                    Are you sure do you want delete this Heatmap?
                                                    <div>
                                                        <input type="text" id="id_heat" class="hidden">
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background-color: white">
                                                    <button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
                                                    <input type="button" value="Confirm" class="btn confirmBtn" id="delete_heatmap" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- -->
								      <!--VIEW DETAILS--->
										
                                <div class="modal fade bd-example-modal-lg" id="view-details" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form name="Modify Metadata" method="post" action="#" id="view_metadata">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: white">View Details</div>
                                                <div class="modal-body" style="background-color: white">
                                                    <div>
                                                        <input type="text" id="id_heat" class="hidden" />
														<div class="container" id="spin1"></div>
														<div id="metadatadiv">
														<div class="input-group"><span class="input-group-addon">Minimum Date:</span><input id="min_d" type="text" class="form-control" name="min_d" readonly></div><br />
														<div class="input-group"><span class="input-group-addon">Maximum Date:</span><input id="max_d" type="text" class="form-control" name="max_d" readonly></div><br />
														<div class="input-group"><span class="input-group-addon">Instances:   </span><input id="inst" type="text" class="form-control" name="inst" readonly></div><br />
														</div>
														<br />
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background-color: white">
                                                    <button type="button" class="btn cancelBtn" data-dismiss="modal" id="close_metadata">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- View -->
                                <div class="modal fade bd-example-modal-lg" id="view-modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <form name="Modify Metadata" method="post" action="#" id="list_Heatmap">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: white" id="list_header">Heatmap Instances List: </div>
                                                <div class="modal-body" style="background-color: white">
                                                    <div>
                                                        <table id="value_table" class="table table-striped table-bordered" style="width: 100%;">
                                                            <thead class="dashboardsTableHeader">
															<?php
															if(($role == 'RootAdmin')){
                                                                echo('<th class="date_value">
                                                                    <div><a>Date</a></div>
                                                                </th>
                                                                <th class="description">
                                                                    <div><a>Description</a></div>
                                                                </th>
                                                                <th class="status">
                                                                    <div><a>Status</a></div>
                                                                </th>
																<th class="indexed">
                                                                    <div><a>Indexed</a></div>
                                                                </th>
                                                                <th class="bbox">
                                                                    <div><a>BBox</a></div>
                                                                </th>
                                                                <th class="value_value">
                                                                    <div><a>Size</a></div>
                                                                </th>
                                                                <th class="edit">
                                                                    <div><a>Edit</a></div>
                                                                </th>
                                                                <th class="delete_value">
                                                                    <div><a>Delete</a></div>
                                                                </th>
																<th class="reload_value">
                                                                    <div><a>Reload</a></div>
                                                                </th>');
															}else{
																 echo('<th class="date_value">
                                                                    <div><a>Date</a></div>
                                                                </th>
                                                                <th class="description">
                                                                    <div><a>Description</a></div>
                                                                </th>
                                                                <th class="status">
                                                                    <div><a>Status</a></div>
                                                                </th>
																<th class="indexed">
                                                                    <div><a>Indexed</a></div>
                                                                </th>
                                                                <th class="bbox">
                                                                    <div><a>BBox</a></div>
                                                                </th>
                                                                <th class="value_value">
                                                                    <div><a>Size</a></div>
                                                                </th>
                                                                ');
															}
																?>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div>
                                                        <p id="data_content"></p>
                                                    </div>
                                                </div>
												<div class="loader"></div>
                                                <p id="corrent" style="display:none;"></p>
                                                <div id="link_list" style="text-align: center;">
                                                </div>
                                                <div class="modal-footer" style="background-color: white">
                                                    <button type="button" class="btn cancelBtn" id="list_close" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- -->
                                <!-- View -->
                                <div class="modal fade bd-example-modal-lg" id="typology-modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form name="Modify Metadata" method="post" action="#" id="typology_Heatmap">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: white" id="typology_header">Color map </div>
                                                <div class="modal-body" style="background-color: white">
                                                    <div>
                                                        <table id="typology_table" class="table table-striped table-bordered" style="width: 100%;">
                                                            <thead class="dashboardsTableHeader">
                                                                <th class="min">
                                                                    <div><a>Minimum</a></div>
                                                                </th>
                                                                <th class="max">
                                                                    <div><a>Maximum</a></div>
                                                                </th>
                                                                <th class="rgb">
                                                                    <div><a>Rgb</a></div>
                                                                </th>
                                                                <th class="color">
                                                                    <div><a>Color</a></div>
                                                                </th>
                                                                <th class="order">
                                                                    <div><a>Order</a></div>
                                                                </th>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background-color: white">
                                                    <button type="button" class="btn cancelBtn" id="typology_close" data-dismiss="modal">Cancel</button>
                                                    <!--<input type="submit" value="Confirm" class="btn confirmBtn"/>-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- -->
                                <div class="modal fade bd-example-modal-lg" id="edit-colormap" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-sm">
                                        <form name="Modify Metadata" method="post" action="#" id="color_Heatmap">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: white" id="color_header">Select Color map: </div>
                                                <div class="modal-body" style="background-color: white">
                                                    <input type="text" id="id_colormap" class="hidden">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect1">Select Color Map:</label>
                                                        <select class="form-control" id="colorMapList">
                                                            <?php 
															  for ($z=0; $z<$total_cm; $z++) {
																	echo('<option>'.$process_cm[$z]['metric_name'].'</option>');
															  }
												  ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background-color: white">
                                                    <button type="button" class="btn cancelBtn" id="color_close" data-dismiss="modal">Cancel</button>
                                                    <input type="button" value="Confirm" class="btn confirmBtn" id="edit_color_map"/>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- -->
                                <!-- #edit-valus -->
                                <div class="modal bd-example-modal-lg" id="edit-valus" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form name="Modify Metadata" method="post" action="#" id="valus_editor">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: white" id="colormap_header">Edit Metadata</div>
                                                <div class="modal-body" style="background-color: white">
                                                    <!-- -->
                                                    <input name="map_name_val" type="text" class="form-control hidden" id="map_name_val"></input>
                                                    <input name="date_val" type="text" class="form-control hidden" id="date_val"></input>
                                                    <input type="text" id="current_data_val" class="hidden">
                                                    <!-- -->
                                                    <div class="input-group" id="description_status"><span class="input-group-addon">Status: </span>
                                                        <select class="form-control" id="status">
                                                            <option value='-1'>Not in Geoserver</option>
                                                            <option value='1'>Completed</option>
                                                            <option value='0'>Not Completed</option>
                                                        </select>
                                                    </div>
													<br />				
                                                </div>
                                                <div class="modal-footer" style="background-color: white">
                                                    <button type="button" class="btn cancelBtn" id="valus_close" data-dismiss="modal">Cancel</button>
                                                    <input type="button" value="Confirm" class="btn confirmBtn" id="confirmEditData" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- #data_elimination -->
                                <div class="modal bd-example-modal-lg" id="data_elimination" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form name="Modify Metadata" method="post" action="#">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: white" id="colormap_header">Delete Data</div>
                                                <div class="modal-body" style="background-color: white">
                                                    Are you sure do you want delete this Data from the Heatmap?
                                                    <div>
                                                        <input type="text" id="id_data" class="hidden">
                                                        <input type="text" id="date_data" class="hidden">
                                                        <input type="text" id="current_data" class="hidden">
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background-color: white">
                                                    <button type="button" class="btn cancelBtn" id="deleteData_close" data-dismiss="modal">Cancel</button>
                                                    <input type="button" value="Confirm" class="btn confirmBtn" id="confirmDeletedData" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
								<!-- -->
								 <!-- ##view_user-modal -->
                                <div class="modal bd-example-modal-lg" id="view_user-modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form name="Modify Metadata" method="post" action="#">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: white" id="colormap_header">View Ownership</div>
                                                <div class="modal-body" style="background-color: white">
                                                    <div>
														<div class="input-group"><span class="input-group-addon">Owner:</span><input id="own_d" type="text" class="form-control" name="own_d" readonly=""></div>
                                                    </div>
                                                </div>
												<br />
                                                <div class="modal-footer" style="background-color: white">
                                                    <button type="button" class="btn cancelBtn" id="owner_close" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- -->
								<!-- Modale gestione deleghe dashboard -->
										<div class="modal fade" id="delegationsModal" tabindex="-1" role="dialog" aria-labelledby="modalAddWidgetTypeLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg" role="document">
											  <div class="modal-content" style="background-color: rgba(138, 159, 168, 1)">
												<div class="modal-header" id="colormap_header">Management</div>
												<form id="delegationsForm" class="form-horizontal" name="delegationsForm" role="form" method="post" action="" data-toggle="validator">
													<div id="delegationsModalBody" class="modal-body modalBody">
														<!-- Tabs -->
														<ul id="delegationsTabsContainer" class="nav nav-tabs nav-justified">
															<li id="ownershipTab" class="active"><a data-toggle="tab" href="#ownershipCnt" class="dashboardWizardTabTxt">Ownership</a></li>
															<li id="visibilityTab"><a data-toggle="tab" href="#visibilityCnt" class="dashboardWizardTabTxt">Visibility</a></li>
															<li id="delegationsTab"><a data-toggle="tab" href="#delegationsCnt" class="dashboardWizardTabTxt">Delegations</a></li>
															<li id="groupTab"><a data-toggle="tab" href="#groupDelegationsCnt" class="dashboardWizardTabTxt">Group Delegation</a></li>
															<li id="metadataTab"><a data-toggle="tab" href="#metadataCnt" class="dashboardWizardTabTxt">Metadata</a></li>
														</ul> 
														<!-- Fine tabs -->
														
														
														<div id="delegationsModalLeftCnt" class="col-xs-12 col-sm-4">
															<div class="col-xs-12 centerWithFlex delegationsModalTxt modalFirstLbl" id="delegationsDashboardTitle">
															</div>
															
															<div id="delegationsDashPic" class="modalDelObjName col-xs-12 hidden"></div>
														</div><!-- Fine delegationsModalLeftCnt-->    
														
														<div id="delegationsModalRightCnt" class="col-xs-12 col-sm-7 col-sm-offset-1" style="background-color: rgba(138, 159, 168, 1)">
															<!-- Tab content -->
															<div class="tab-content">
																<!-- Ownership cnt -->
																<div id="ownershipCnt" class="tab-pane fade in active">
																	<div class="row" id="ownershipFormRow">
																		<div class="col-xs-12 centerWithFlex delegationsModalLbl modalFirstLbl" id="changeOwnershipLbl">
																			<b>Change ownership</b>
																		</div>
																		<div class="col-xs-12" id="newOwnershipCnt">
																			<div class="input-group">
																				<input type="text" class="form-control" id="newOwner" placeholder="New owner username">
																				<span class="input-group-btn">
																				  <button type="button" id="newOwnershipConfirmBtn" class="btn confirmBtn">Confirm</button>
																				</span>
																			</div>
																			<div class="col-xs-12 centerWithFlex delegationsModalMsg" id="newOwnerMsg">
																				<i>New owner username can't be empty</i>
																			</div>    
																		</div>
																		<div class="col-xs-12 centerWithFlex" id="newOwnershipResultMsg">
																			
																		</div>    
																	</div>    
																</div>
																<!-- Fine ownership cnt -->
																
																
																<!-- Visibility cnt -->
																<div id="visibilityCnt" class="tab-pane fade in">
																	<div class="row" id="visibilityFormRow">
																		<div class="col-xs-12 centerWithFlex delegationsModalLbl modalFirstLbl" id="changeOwnershipLbl">
																			<b>Change visibility</b>
																		</div>
																		<div class="col-xs-12" id="newVisibilityCnt">
																			<div class="input-group">
																				<select id="newVisibility" class="form-control">
																					<option value="public">Public</option>
																					<option value="private">Private</option>
																				</select>
																				<span class="input-group-btn">
																				  <button type="button" id="newVisibilityConfirmBtn" class="btn confirmBtn">Confirm</button>
																				</span>
																			</div>
																		</div>
																		<div class="col-xs-12 centerWithFlex" id="newVisibilityResultMsg">
																			
																		</div>  
																	</div>    
																</div>
																<!-- Fine visibility cnt -->
																
																<!-- Delegations cnt -->
																<div id="delegationsCnt" class="tab-pane fade in">																
																	<div class="row" id="delegationsFormRow">
																		<div class="col-xs-12 centerWithFlex modalFirstLbl" id="newDelegationLbl">
																			<b>Add new delegation</b>																		</div>
																		<div class="col-xs-12" id="newDelegationCnt">
																			<div class="input-group">
																				<input type="text" class="form-control" id="newDelegation" placeholder="Delegated username">
																				<span class="input-group-btn">
																				  <button type="button" id="newDelegationConfirmBtn" class="btn confirmBtn">Confirm</button>
																				</span>
																			</div>
																			<div class="col-xs-12 centerWithFlex delegationsModalMsg" id="newDelegatedMsg">
																				<i>Delegated username can't be empty</i>
																			</div>    
																		</div>
																		<br /><br />
																		<div class="col-xs-12 centerWithFlex" id="currentDelegationsLbl">
																			Current delegations
																		</div>
																		<div class="col-xs-12" id="delegationsTableCnt">
																		
																			<table id="delegationsTable">
																				<thead>
																				  <th>Delegated user</th>
																				  <th>Remove</th>
																				</thead>
																				<tbody>
																				</tbody>
																			</table>
																		</div>
																	</div>
																</div>
																<!-- Fine delegations cnt -->
																<!-- Group Delegation -->
																<div id="groupDelegationsCnt" class="tab-pane fade in">
																	   <div class="row centerWithFlex modalFirstLbl" id="groupDelegationsNotAvailableRow" style="display: none;">
																		  Delegations are not possibile on a public dashboard
																	   </div>
																	   <div class="row" id="groupDelegationsFormRow">
																		  <div class="col-xs-12 centerWithFlex modalFirstLbl" id="newDelegationLbl">
																			 Add new group delegation
																		  </div>
																		  <div class="col-xs-12" id="newGroupDelegationCnt">
																			 <div class="col-xs-4">
																				<input type="text" id="current_group" hidden />
																				<select name="newDelegationOrganization" id="newDelegationOrganization" class="modalInputTxt" > 
																				</select>
																				<!--  <option value="Antwerp">Antwerp</option></select>   -->
																			 </div>
																			 <div class="col-xs-4">
																				<select name="newDelegationGroup" id="newDelegationGroup" class="modalInputTxt">
																					<!--
																				   <option value="Operativo">Operativo</option>
																				   <option value="Developer">Developer</option>
																				   -->
																				</select>
																				
																			 </div>
																			 <div class="col-xs-4">
																				<span class="input-group-btn">
																				<button type="button" id="newGroupDelegationConfirmBtn" class="btn confirmBtn">Confirm</button>
																				</span>
																			 </div>
																			 
																			 <div class="col-xs-12 centerWithFlex delegationsModalMsg" id="newGroupDelegatedMsg">
																				<!-- Delegated group/organization name can't be empty    -->
																			 </div>
																		  </div>
																		  <div class="col-xs-12 centerWithFlex" id="currentGroupDelegationsLbl">
																			 Current group delegations
																		  </div>
																		  <div class="col-xs-12" id="groupDelegationsTableCnt">
																			 <table id="groupDelegationsTable">
																				<thead>
																				   <tr>
																					  <th>Delegated group</th>
																					  <th>Remove</th>
																				   </tr>
																				</thead>
																				<tbody></tbody>
																			 </table>
																		  </div>
																	   </div>
																	</div>
																<!-- -->
																<!-- Metadata cnt -->
																<div id="metadataCnt" class="tab-pane fade in">																
																	<div class="row" id="delegationsFormRow">
																		<div class="col-xs-12 centerWithFlex modalFirstLbl" id="newDelegationLbl">
																			<b>Edit Metadata</b>																		</div>
																		<div class="col-xs-12" id="editmetadataCnt">
																			<div class="input-group">
																				
																			</div>  
																		</div>
																		<br /><br />
																		<div class="col-xs-12" id="currentMetadata">
																			<label for="fnature">Nature:</label>
																			<select id="fnature" name="fnature"></select>
																			<br />
																			<label for="fsubnature">Subnature:</label>
																			<select id="fsubnature" name="fsubnature"></select>
																		</div>
																		<button type="button" id="confirmEditMetadata" class="btn confirmBtn">Confirm</button>
																	</div>
																</div>
																<!-- Ownership cnt -->
																<div id="groupCnt" class="tab-pane fade in active">
																	<div class="row" id="ownershipFormRow">
																		    
																	</div>    
																</div>
																<!-- Fine ownership cnt -->
																
															</div>    
															<!-- Fine tab content -->
															<input type="hidden" id="delegationsDashId">
														</div><!-- Fine delegationsModalRightCnt-->
													</div>
													<div id="delegationsModalFooter" class="modal-footer" style="background-color: rgba(138, 159, 168, 1)">
													  <button type="button" id="delegationsCancelBtn" class="btn cancelBtn" data-dismiss="modal">Close</button>
													</div>
												</form>    
											  </div>
											</div>
										</div>
										<!-- Fine modale gestione deleghe dashboard -->
										 <!-- ##view_user-modal -->
										 <div class="modal bd-example-modal-lg" id="preview-modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
												<div class="modal-dialog modal-xl" style="width: 80%; height: auto">
													<form name="Preview Heatmap" method="post" action="#">
														<div class="modal-content">
															<div class="modal-header" style="background-color: white" id="preview_header">Preview Heatmap</div>
															<div class="modal-body" style="background-color: white">
																<div class="embed-responsive embed-responsive-16by9">	
																	<iframe class="embed-responsive-item" id="iframeContainer" src="" frameborder="0"></iframe>
																</div>
															</div>
															<div class="modal-footer" style="background-color: white">
																<button type="button" class="btn cancelBtn" id="owner_close" data-dismiss="modal">Cancel</button>
															</div>
														</div>
													</form>
												</div>
											</div>
											<!-- -->
										<!--- ---->
                            </div>
                        </div>
                </div>

            </div>

            </div>
            </div>
            <script type='text/javascript'>
                var nascondi = "<?=$hide_menu; ?>";
                var corrent_page = "<?=$page; ?>";
                var titolo_default = "<?=$default_title; ?>";
                var start_from = "<?=$start_from; ?>";
                var limit_val = $('#limit_select').val();
				var role = "<?=$role_att; ?>";
				var array_del="<?=$array_del; ?>";
				var preview_path = "<?=$preview_path; ?>";
				

                $(document).ready(function() {
					
					var table = $('#heatmap_table').DataTable({
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
					
                    if (nascondi == 'hide') {
                        $('#mainMenuCnt').hide();
                        $('#title_row').hide();
                    }

                    var role_active = $("#role_att").text();

                    if (role_active == 'ToolAdmin') {
                        $('#sc_mng').show();
                    }
                    //
                    var limit_default = "<?=$limit; ?>";
                    $('#limit_select').val(limit_default);

                    var role = "<?=$role_att; ?>";

                    if (role == "") {
                        $(document).empty();
                        //window.alert("You need to log in to access to this page!");
                        if (window.self !== window.top) {
                            window.location.href = 'https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';
                        } else {
                            window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
                        }
                    }
                    //
                    var role_active = "<?=$process['functionalities'][$role]; ?>";

                    if (role_active == 0) {
                        $(document).empty();
                        if (window.self !== window.top) {
                            window.location.href = 'https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';
                        } else {
                            window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
                        }
                    }
                   
                    if (titolo_default != "") {
                        $('#headerTitleCnt').text(titolo_default);
                    }
                    /****/
                    /****/
                    $(document).on('click', '.viewList', function() {
						var map_name = $(this).val();
                        $('#list_header').text('Heatmap Instances List:	' + map_name);
						var order_value = "data_date";
						var order = "DESC";
                        list_scroll(0, map_name, order, order_value);

                    });
                    /****/
                    $(document).on('click', '#list_close', function() {
                        $('#value_table tbody').empty();
                        $('#list_header').empty();
                        $('#link_list').empty();
						$('.modal-backdrop').remove();
                        $('.loader').show();
                    });
                    /*****/
					$(document).on('click','.previewList', function() {
						// Event listener for the button
								var myModal = document.getElementById('preview-modal');
								var myIframe = document.getElementById('iframeContainer');
								var map_name = $(this).val();
								var heatmap = $(this).val();
								var layers = $(this).val();
								var org = $(this).attr('org');
								//myIframe.src = 'http://dashboard/dashboardSmartCity/view/preview.php?heatmap='+heatmap+'&layers='+layers+'&organization='+org;
								myIframe.src = preview_path+'?heatmap='+heatmap+'&layers='+layers+'&organization='+org;
								$(myModal).modal('show');
							});
					/******/
                    $(document).on('click', '.viewType', function() {
						var metric = $(this).val();
                        $('#typology_header').text('Color Map:	' + metric);
                        var array = new Array();
                        $.ajax({
                            url: 'get_heatmap.php',
                            data: {
                                metric: metric,
                                action: 'color_map'
                            },
                            type: "POST",
                            async: true,
                            dataType: 'json',
                            success: function(data) {

                                for (var i = 0; i < data.length; i++) {
                                    var data_id = data[i]['id'];
                                    var rgb = data[i]['rgb'];
                                    var color = data[i]['color'];
                                    var order = data[i]['order'];
                                    var min = data[i]['min'];
                                    var max = data[i]['max'];
                                    if (min == null) {
                                        min = '';
                                    }
                                    if (max == null) {
                                        max = '';
                                    }

                                    rgb0 = rgb.replace("[", "(");
                                    rgb = rgb0.replace("]", ")");
                                    
                                    $('#typology_table tbody').append('<tr><td>' + min + '</td><td>' + max + '</td><td>' + rgb + ' <p><i class="fa fa-circle" style="color: rgb' + rgb + '"></i></p></td><td>' + color + '</td><td>' + order + '</td></tr>');
                                }
								//
								var table2 = $('#typology_table').DataTable({
								"searching": false,
								"paging": false,
								"ordering": false,
								"info": false,
								"responsive": true,
								"bDestroy": true
								});
								//
								
                            }
                        });
                    });

                    /*****/
                    $(document).on('click', '#typology_close', function() {
                        $('#typology_table tbody').empty();
                        //			 
                    });
                    /*****/

                    //btn cancelBtn	
                    $(document).on('click', '.cancelBtn', function() {
                        $('#map_name').val('');
                        $('#metric_name').val('');
                        $('#description').val('');
                        /////
                        $('#x_length').val('');
                        $('#y_length').val('');
                        $('#clustered').val('');
                        $('#days').val('');
                        /////
                    });
                    /*****/
                    $('#limit_select').change(function() {
                        var limit_val = $('#limit_select').val();
                        var links = [];
                        links = $("a.page_n");
                        for (var i = 0; i < links.length; i++) {
                            var str = links[i].href;
                            var new_link = str.replace('&limit=' + limit_default, '&limit=' + limit_val);
                            links[i].href = new_link;
                        }
                        if ((window.self !== window.top) || (nascondi == 'hide')) {
                            window.location.href = 'heatmap.php?showFrame=false&page=' + corrent_page + '&limit=' + limit_val;
                        } else {
                            window.location.href = 'heatmap.php?showFrame=true&page=' + corrent_page + '&limit=' + limit_val;
                        }
                    });
                    /*****/
                    //det_data
                    $(document).on('click', '.det_data', function() {
                        var id = $(this).parent().parent().first().children().html();
                        var data = $(this).parent().parent().first().children().eq(3).html();
                        var curr_data = $('.current_link').text();
                        $('#id_data').val(id);
                        $('#date_data').val(data);
                        $('#current_data').val((curr_data) - 1);
                    });
                    /*****/
                    $(document).on('click', '#confirmDeletedData', function() {
                        var id = $('#id_data').val();
                        var date = $('#date_data').val();
                        var page = $('#current_data').val();
                        if (page < 0) {
                            page = 0;
                        }
                        $.ajax({
                            url: 'get_heatmap.php',
                            data: {
                                id: id,
                                date: date,
                                action: 'delete_data'
                            },
                            type: "POST",
                            async: true,
                            success: function(data) {
                                $('#data_elimination').modal('hide');
                                list_scroll(page, id,"DESC","data_date");
                            }
                        });
                    });
                    /****/
                    $(document).on('click', '.del_metdata', function() {
						var id = $(this).val();
                        $('#id_heat').val(id);
                    });

                    /****/
                    $(document).on('click', '#color_close', function() {
                        $('#colormap_table tbody').empty();
                    });
                    /****/
					
					/****/
					$(document).on('click', '.delegateBtn', function() {
						var map_name = $(this).val();
						var org = $(this).attr('org');
						var nature = $(this).attr('nature');
						var subnature = $(this).attr('subnature');
						var visibility = $(this).attr('visibility');
						if(visibility == 'PUBLIC'){
							$('#newVisibility').val('public');
							$('#groupDelegationsFormRow').hide();
							$('#groupDelegationsNotAvailableRow').show();
						}else{
							$('#newVisibility').val('private');
							$('#groupDelegationsFormRow').show();
							$('#groupDelegationsNotAvailableRow').hide();
						}
						var user_name = $(this).attr('user');
						$('#delegationsDashboardTitle').text(map_name);
						$('#delegationsDashPic').text(user_name);
						$('#newDelegationOrganization').val(org);
						//
						//console.log('Nature: '+nature+', Subnature: '+subnature);
						//
						$('#current_group').text(org);
						if((role == 'RootAdmin')||(role == 'ToolAdmin')){
								console.log(role);
						}else{
							$('#newDelegationOrganization').attr('disabled',true);
						}
						
						
						///		groupDelegationsTable		///
						/*********/
						var ou = org;
						var group_default = '';
						$.ajax({
							url: 'delegation.php',
							dataType: 'json',
							data: {
									heatmap: map_name,
									user: user_name,
									ou: ou,
									action: 'getDelegatedGroupsTable'
							},
							type: "GET",
							async: true,
							success: function(data) {
									var $dropdown = $("#groupDelegationsTable");									
									for (var l = 0; l < data.length; l++) {	
											 var group_del = data[l]['delegatedGroup'];
											if(group_del){
													var id = data[l]['delegationId'];
													var res = group_del.split('	- ');													
													var delegated = user_name;
													$dropdown.append("<tr><td>"+group_del+"</td><td><i class='fa fa-times' onclick=delete_group('"+id+"','"+delegated+"')></i></td></tr>");
													group_default = group_del;
												}
									}								
									
									}
								});

						$.ajax({
									url: 'delegation.php',
									dataType: 'json',
									data: {
										heatmap: map_name,
										action: 'getDelegation'
									},
									type: "GET",
									async: true,
									success: function(data) {
										
										if(data.length > 0){
												var obj = JSON.parse(data);
												console.log(obj.length);
												for(var h=0; h<obj.length; h++){
													var id = obj[h]['id'];
													var value = obj[h]['usernameDelegated'];
													var delegator = obj[h]['usernameDelegator'];
													if(value !='ANONYMOUS'){
													$('#delegationsTable').append("<tr><td>"+value+"</td><td><i class='fa fa-times' onclick=delete_delegation('"+id+"','"+value+"','"+delegator+"','"+map_name+"');></i></td></tr>");
													}
													if(value =='ANONYMOUS'){
														$('#newVisibility').val('public');
													}
												}
											//
										}else{
											console.log('VUOTO');
										}
									}
						});
						
						var listGroup = [];
						var check = false;

								$.ajax({
									url: 'delegation.php',
									dataType: 'json',
									data: {
										heatmap: map_name,
										user: user_name,
										action: 'getGroups'
									},
									type: "GET",
									async: true,
									success: function(data) {
										console.log('data:	');
										console.log(data['content']);
										var content = data['content'];

										if(data["status"] === 'ko'){
												$('#newDelegatedMsgGroup').css('color', '#f3cf58');
												$('#newDelegatedMsgGroup').html(data["msg"]);
										}else if (data["status"] === 'ok'){
												var $dropdown = $("#newDelegationOrganization");
												$.each(data['content'], function() {
													$dropdown.append($("<option />").val(this).text(this));
												});
										}


										$("#newDelegationOrganization").val(org);
										var ou = org;

										$.ajax({
													url: 'delegation.php',
													dataType: 'json',
													data: {
														heatmap: map_name,
														user: user_name,
														ou: ou,
														action: 'getDelegatedGroups'
													},
													type: "GET",
													async: true,
													success: function(data) {
														console.log('data:	');
														console.log(data['content']);

																if(data["status"] === 'ko'){

																}else if (data["status"] === 'ok'){
																		var $dropdown = $("#newDelegationGroup");
																		$.each(data['content'], function() {
																			$dropdown.append($("<option />").val(this).text(this));
																		});
																	}
																}
													});
								
											}
						});
						
						///GET NATURE AND SUBNATURE
								$.ajax({
													url: 'api/dictionary/index.php',
													dataType: 'json',
													data: {
														type: 'nature'
														
													},
													type: "GET",
													async: true,
													success: function(data) {
																		if(data.code == '200'){
																			var array_nat = [];
																			array_nat = data.content;
																			var lung = array_nat.length;
																			for(var i=0; i<lung; i++){
																				var valnat = array_nat[i];
																				//console.log(valnat);
																				$('#fnature').append('<option value="'+valnat.value+'" >'+valnat.label+'</option>');		
																			}
																			//console.log('nature: '+nature);
																			$('#fnature').val(nature).change();													
																		}
															}
													});
								$.ajax({
													url: 'api/dictionary/index.php',
													dataType: 'json',
													data: {
														type: 'subnature'
														
													},
													type: "GET",
													async: true,
													success: function(data) {
																		if(data.code == '200'){
																			var array_nat = [];
																			array_nat = data.content;
																			var lung = array_nat.length;
																			for(var i=0; i<lung; i++){
																				var valnat = array_nat[i];
																				$('#fsubnature').append('<option value="'+valnat.value+'">'+valnat.label+'</option>');	
																			}
																			$('#fsubnature').val(subnature).change();
																		}
															}
													});
						////
						/*********/
						//			
                    // Function to open the modal and insert the iframe
							function openModalWithIframe(url) {
							// Get the iframe container element
							var iframeContainer = document.getElementById('iframeContainer');

							// Create an iframe element
							var iframe = document.createElement('iframe');
							iframe.src = url;
							iframe.width = '100%';
							iframe.height = '400px';
							iframe.frameborder = '0';

							// Append the iframe to the container
							iframeContainer.appendChild(iframe);

							// Open the modal
							//$('#m').modal('show');
							}

					//***CHANGE NATURE//
					$("select#fnature").on("change",function(event){
											$.ajax({
												url: "api/dictionary/index.php?get_all",
												type: "GET",
												async: true,
												dataType: "JSON",
												success: function(cats) 
												{
													if(cats.result == "OK") {
														cats.content.forEach(function(cat){
															if( $("select#fnature").val() == cat["value"] && cat["type"] == "nature") {
																$("#fsubnature").empty();
																//$("select#fsubnature").append($("<option value=\"\"></option>"));
																cat["children_id"].forEach(function(childCatId) {
																	cats.content.forEach(function(childCat){
																		if(childCat.id == childCatId) {
																			$("#fsubnature").append($('<option value="'+childCat["value"]+'">'+childCat["label"]+'</option>'));
																		}
																	});									
																});
																$('#fsubnature').val(subnature).change();
															}
														});
													}
												}
											});
										});
					
					///****//
					});
					
					
						$(document).on('change','#newDelegationOrganization', function() {
										var ou = $('#newDelegationOrganization').val();
										$("#newDelegationGroup").empty();
										 console.log(ou);
										 $.ajax({
													url: 'delegation.php',
													dataType: 'json',
													data: {
														ou: ou,
														action: 'getDelegatedGroups'
													},
													type: "GET",
													async: true,
													success: function(data) {
															if(data["status"] === 'ko'){
																}else if (data["status"] === 'ok'){
																		var $dropdown = $("#newDelegationGroup");
																		$.each(data['content'], function() {
																	$dropdown.append($("<option />").val(this).text(this));
																});
														}
													}
												});
								});
                    //****//
					//Eliminare il td corrente.
                    $(document).on('click', '#delegationsCancelBtn', function() {
                        $('#delegationsDashboardTitle').empty();
						$('#newVisibility').val('public');
						$('#delegationsTable tbody').empty();
						$('#groupDelegationsTable tbody').empty();
						$('#newDelegationOrganization').empty();
						$('#newDelegationGroup').empty();
						$('#fnature').empty();
						$('#fsubnature').empty();
                    });
					/****/
					$(document).on('click','#close_metadata', function(){
							$('#min_d').empty();
							$('#max_d').empty();
							$('#inst').empty();
					});
					/*****/
					//change color_map
					//edit_color_map
					$(document).on('click', '#edit_color_map', function() {
						var valore = $('#colorMapList').val();
						var id= $('#id_colormap').val();
									$.ajax({
											url: 'get_heatmap.php',
											data: {
												id:id,
												valore: valore,
												action: 'change_color_map'
											},
											type: "POST",
											async: true,
											success: function(data) {
												$('#color_Heatmap').modal('hide');
												location.reload();
											}
										});
                    });

                    //delete_heatmap
                    $(document).on('click', '#delete_heatmap', function() {
                        var id_heat = $('#id_heat').val();
                        ////
                        $.ajax({
                            url: 'get_heatmap.php',
                            data: {
                                id: id_heat,
                                action: 'delete_metadata'
                            },
                            type: "POST",
                            async: true,
                            success: function(data) {
                                $('#delete_Heatmap').modal('hide');
                                alert('Heatmap successfully deleted');
                                location.reload();
                            }
                        });
                        ////
                    });
                    /**confirmEditData**/
                    $(document).on('click', '#confirmEditData', function() {
                        var status = $('#status').val();
                        //var indexed = $('#indexed').val();
                        var id = $('#map_name_val').val();
                        var date_val = $('#date_val').val();
                        var corr = ($('#current_data_val').val() - 1);
                        if (corr < 0) {
                            corr = 0;
                        }

                        $.ajax({
                            url: 'get_heatmap.php',
                            data: {
                                id: id,
                                date_val: date_val,
                                status: status,
                                action: 'edit_metadata'
                            },
                            type: "POST",
                            async: true,
                            success: function(data) {
                                //edit-valus
                                $('#edit-valus').modal('hide');
                                list_scroll(corr, id,"DESC","data_date");
                            }
                        });

                    });
                    /****/
                    //#edit-modal
                    $(document).on('click', '.editColor', function() {
						var metric = $(this).val();
						var map_name = $(this).attr('map_name');
                        $('#color_header').text('Edit Color Map:	' + map_name);
						$('#id_colormap').val(map_name);
						$('#colorMapList').val(metric);
                      });
					//
                    /****/
                    //add_color
                    $(document).on('click', '#add_color', function() {
                        $('#colormap_table tbody').append('<tr><td class="hidden"></td><td><input name="val_min" type="text" class="form-control" id="val_min" value=""></input></td><td class="hidden"></td><td><div id="color-picker-rgb" class="input-group rgb_color"><input type="text" value="rgb(255,255,255)" class="form-control"/><span class="input-group-addon"><i></i></span></div></td><td><input name="val_rgb" type="text" class="form-control" class="val_rgb" value=""></input></td><td><input name="val_order" type="text" class="form-control" class="val_order" value=""></input></td><td><button type="button" class="delDashBtn del_color1" data-target="" data-toggle="modal">DEL</button></td></tr>');
                        $('.rgb_color').colorpicker();
                    });
                    //****////Eliminare il td corrente.
                    $(document).on('click', '.del_color1', function() {
                        var id = $(this).parent().parent();
                        $(id).remove();
                    });
					
					/***************************/
					//newOwnershipConfirmBtn
				 $(document).on('click', '#newOwnershipConfirmBtn', function() {
					 var heatmap = $('#delegationsDashboardTitle').text();
					 var user = $('#delegationsDashPic').text();
					 var newOwner= $('#newOwner').val();
					  $.ajax({
                            url: 'delegation.php',
                            data: {
                                 heatmap: heatmap,
								 user: user,
								 role: role,
								 newOwner: newOwner,
                                 action: 'changeOwnership'
                            },
                            type: "POST",
                            async: true,
                            success: function(data) {
                                console.log('OK');
								location.reload();
								//
                            }
                        });
					 
				 });
				 //newVisibilityConfirmBtn
				$(document).on('click', '#newVisibilityConfirmBtn', function() {
					var heatmap = $('#delegationsDashboardTitle').text();
					var newVisibility = $('#newVisibility').val();
					var user = $('#delegationsDashPic').text();		
					 $.ajax({
                            url: 'delegation.php',
                            data: {
                                heatmap: heatmap,
                                newVisibility: newVisibility,
                                user: user,
                                action: 'changePublic'
                            },
                            type: "POST",
                            async: true,
                            success: function(data) {
                                console.log('OK');
								location.reload();
                                //edit-valus
                            }
                        });
				});
                //
				//newDelegationConfirmBtn
				$(document).on('click', '#newDelegationConfirmBtn', function() {
					var heatmap = $('#delegationsDashboardTitle').text();
					var newDelegation = $('#newDelegation').val();
					var user = $('#delegationsDashPic').text();
					 $.ajax({
                            url: 'delegation.php',
                            data: {
                                heatmap: heatmap,
                                newDelegation: newDelegation,
                                user: user,
                                action: 'addDelegation'
                            },
                            type: "POST",
                            async: true,
                            success: function(data) {
                                console.log('OK');
								location.reload();
                                //edit-valus
                            }
                        });
				});
				
				//newGroupDelegationConfirmBtn
						$(document).on('click', '#newGroupDelegationConfirmBtn', function() {
							var heatmap = $('#delegationsDashboardTitle').text();
							var newDelegationOrg = $('#newDelegationOrganization').val();
							var newDelegationGroup = $('#newDelegationGroup').val();
							var user = $('#delegationsDashPic').text();
							var current_group = $('#current_group').text(); 
							 $.ajax({
									url: 'delegation.php',
									data: {
										heatmap: heatmap,
										user: user,
										role: role,
										oldDelegationGroup: current_group,
										newDelegationOrg: newDelegationOrg,
										newDelegationGroup: newDelegationGroup,
										action: 'delegateGroup'
									},
									type: "POST",
									async: true,
									success: function(data) {
											if(data.includes('Ok')){
												location.reload();
											}else{
												alert('Error occurred during operation exection. Please, control parameters correctness');
											}
									}
								});
						});
						
						//confirmEditMetadata
						$(document).on('click', '#confirmEditMetadata', function() {
							//
							var heatmap = $('#delegationsDashboardTitle').text();
							var nature = $('#fnature').val();
							var subnature = $('#fsubnature').val();
							//
							 $.ajax({
									url: 'get_heatmap.php',
									data: {
										heatmap: heatmap,
										nature: nature,
										subnature: subnature,
										action: 'edit_metadata'
									},
									type: "POST",
									async: true,
									success: function(data) {
										location.reload();
											/*if(data.includes('Ok')){
												location.reload();
											}else{
												alert('Error occurred during operation exection. Please, control parameters correctness');
											}*/
									}
								});
						});
						
			    /*************/
				/**************************/

                });
				//
				$(document).on('click', '#owner_close', function() {
							$('#own_d').empty();
						});
                //
                function list_scroll(page, name,order,order_value) {
					$('.loader').show();
                    var array = new Array();
                    $('#corrent').text(page);
                    $.ajax({
                        url: 'get_heatmap.php',
                        data: {
                            page: page,
                            map_name: name,
                            action: 'get_values',
							order_value: order_value,
							order: order
                        },
                        type: "POST",
                        async: true,
                        dataType: 'json',
                        success: function(data) {
                            //
                            $('#value_table tbody').empty();
                            $('#link_list').empty();
                            $('#data_content').empty();
							$('.loader').hide();
                            //
                            var count = 0;
                            if (data.length > 0) {
                                for (var i = 0; i < data.length; i++) {
                                    array[i] = {
                                        date: data[i]['date'],
                                        value: data[i]['value'],
                                        bbox: data[i]['bbox'],
                                        status: data[i]['status'],
                                        index: data[i]['indexed'],
                                        description: data[i]['description'],
                                        total: data[i]['total']
                                    }
                                    var status = array[i]['status'];
                                    var index = array[i]['index'];
                                    var id_completed = name;
                                    //
                                    var style = '';
                                    var status_text = '';
                                    if (status == '1') {
                                        status_text = 'Completed';
                                    } else if (status == '0') {
                                        status_text = 'Not Completed';
                                    } else {
                                        status_text = 'Not in GeoServer';
                                    }
									//
									var index_text = '';
                                    if (index == '1') {
                                        index_text = 'Indexed';
                                    } else if (index == '0') {
                                        index_text = 'Not Indexed';
                                    } else if (index == '-1'){
										index_text = 'Failed';
									}else if (index == '-2'){
										index_text = 'Too failures';
									} else{
                                        index_text = 'Not in GeoServer';
                                    }
									//status_text = status;
                                    var function_a = "function_data('" + id_completed + "','" + array[i]['date'] + "'," + status + "," + index + ")";
                                    //
									if (role_active == 'RootAdmin'){
                                    $('#value_table tbody').append('<tr><td>' + array[i]['date'] + '</td><td>' + array[i]['description'] + '</td><td ' + style + '>' + status_text + '</td><td>'+index_text+'</td><td>' + array[i]['bbox'] + '</td><td>' + array[i]['value'] + '</td><td><button type="button" class="editDashBtn editValues"data-target="#edit-valus" onclick="' + function_a + '" data-toggle="modal">EDIT</button></td><td><button type="button" class="delDashBtn det_data" data-target="#data_elimination" data-toggle="modal">DEL</button></td><td><button class="viewDashBtn" data-toggle="modal">RELOAD</button></td></tr>');
									}else{
									$('#value_table tbody').append('<tr><td>' + array[i]['date'] + '</td><td>' + array[i]['description'] + '</td><td ' + style + '>' + status_text + '</td><td>'+index_text+'</td><td>' + array[i]['bbox'] + '</td><td>' + array[i]['value'] + '</td></tr>');
									}
                                    count++;
                                }
								//
                                var total_rows = array[0]['total'];
                                if (total_rows > 10) {
                                    var count_rows = Math.floor(array[0]['total'] / 10);
                                    if (array[0]['total'] > 10) {
                                        if (page > 0) {
                                            var m = page - 1;
                                            $('#link_list').append('	<a class="page_n_link" value="0" href="#" onclick=list_scroll(0,"' + name + '","DESC","data_date") >' + ' First  ' + '</a>	');
                                            $('#link_list').append('	<a class="page_n_link" value="' + m + '" href="#" onclick=list_scroll(' + m + ',"' + name + '","DESC","data_date") >' + '  <<  ' + '</a>	');
                                        }

                                        if (page < 11) {
                                            var start = 0;
                                        } else {
                                            var start = page - 10;
                                        }
                                        var corrent_val = $('#corrent').val();
                                        for (var y = start; y < start + 10; y++) {
                                            n = y + 1;
                                            var style = '';
                                            var current_link = '';
                                            //
                                            if (y == page) {
                                                style = 'text-decoration: underline';
                                                current_link = 'current_link';
                                            } else {
                                                style = '';
                                                current_link = '';
                                            }
                                            //

                                            if (y <= count_rows) {
                                                $('#link_list').append('	<a class="page_n_link ' + current_link + '" value="' + y + '" href="#" onclick=list_scroll(' + y + ',"' + name + '","DESC","data_date") style="' + style + '">' + n + '</a>	');
                                            }
                                        }
                                        if (page < count_rows) {
                                            var p = page + 1;
                                            $('#link_list').append('	<a class="page_n_link" value="' + p + '" href="#" onclick=list_scroll(' + p + ',"' + name + '","DESC","data_date") >' + '  >>  ' + '</a>	');
                                            $('#link_list').append('	<a class="page_n_link" value="' + count_rows + '" href="#" onclick=list_scroll(' + count_rows + ',"' + name + '","DESC","data_date") >' + ' Latest ' + '</a>	');
                                        }
                                    }
                                } else {
                                    console.log('nothing');
                                }
                            } else {
                                $('#data_content').append('<div class="panel panel-default"><div class="panel-body">There are no data</div></div>');
                            }
							////****////
									var new_order = 'DESC';
									var arrow = '<i class="arrow fa fa-caret-down"></i>';
									if (order == 'DESC'){
										new_order = 'ASC';
										arrow = '<i class="arrow fa fa-caret-down"></i>';
									}else{
										new_order = 'DESC';
										arrow = '<i class="arrow fa fa-caret-up"></i>';
									}
									
									if (order == 'ASC'){
										new_order = 'DESC';	
										arrow = '<i class="arrow fa fa-caret-up"></i>';
										
									}else{
										new_order = 'ASC';
										arrow = '<i class="arrow fa fa-caret-down"></i>';
									}
									
									$('.date_value').attr("onClick","list_scroll("+page+", '"+name+"','"+new_order+"','data_date')");
									$('.status').attr("onClick","list_scroll("+page+", '"+name+"','"+new_order+"','completed')");
									$('.indexed').attr("onClick","list_scroll("+page+", '"+name+"','"+new_order+"','indexed')");
									$('.bbox').attr("onClick","list_scroll("+page+", '"+name+"','"+new_order+"','bbox')");
									$('.value_value').attr("onClick","list_scroll("+page+", '"+name+"','"+new_order+"','number')");
									$('.description').attr("onClick","list_scroll("+page+", '"+name+"','"+new_order+"','description')");
									///
									$('.arrow').remove();
									if(order_value =='data_date'){
										$('.date_value').append(arrow);
									}
									if(order_value =='description'){
										$('.description').append(arrow);
									}
									if(order_value =='completed'){
										$('.status').append(arrow);
									}
									if(order_value =='indexed'){
										$('.indexed').append(arrow);
									}
									if(order_value =='number'){
										$('.value_value').append(arrow);
									}
									if(order_value =='bbox'){
										$('.bbox').append(arrow);
									}
									//$('.'+order_value+'').append(arrow);
									//
							////****////
							
							//<i class="fas fa-caret-up"></i>

                        }

                    });
                }

                function function_data(id_completed, data, status, index) {
							$('#map_name_val').val(id_completed);
							$('#date_val').val(data);
							$('#status').val(status);
							$('#indexed').val(index);
							//valore corrente link
							var current_data_val = $('.current_link').text();
							$('#current_data_val').val(current_data_val);
						}
				
				function delete_delegation(id,delegator, delegated, heatmap){
					
					/////
							$.ajax({
									url: 'delegation.php',
									data: {
										 id: id,
										 heatmap: heatmap,
										 delegated: delegated,
										 delegator: delegator,
										 action: 'deleteDelegation'
									},
									type: "POST",
									async: true,
									success: function(data) {
										console.log('OK');
										location.reload();
									}
								});
					/////
				}
				
				
				function delete_group(id,delegated){
							$.ajax({
									url: 'delegation.php',
									data: {
										 id: id,
										 delegated: delegated,
										 action: 'deleteGroups'
									},
									type: "POST",
									async: true,
									success: function(data) {
										console.log('OK');
										location.reload();
									}
								});
				}

                function removeParam(key, sourceURL) {
                    var rtn = sourceURL.split("?")[0],
                        param,
                        params_arr = [],
                        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
                    if (queryString !== "") {
                        params_arr = queryString.split("&");
                        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                            param = params_arr[i].split("=")[0];
                            if (param === key) {
                                params_arr.splice(i, 1);
                            }
                        }
                        rtn = rtn + "?" + params_arr.join("&");
                    }
                    return rtn;
                }
				////
				//viewdetails
				function viewdetails(id) {
					console.log(id);
					//$('#id_heat').text(id);
					$('#min_d').val('');
					$('#max_d').val('');
					$('#inst').val('');
					var x = document.getElementById("metadatadiv");
					x.style.display = "none";
					$('#spin1').html('<div><p>Loading metadata, please wait...</p><i class="fa fa-circle-o-notch fa-spin" style="font-size:48px; color:#1E90FF"></i></div><br>');
					//
					$.ajax({
						url: 'get_heatmap.php',
						data: {
							action: 'getdetails',
							id: id
						},
						type: "GET",
						async: true,
						success: function(data) {
							//
							var obj = JSON.parse(data);
										console.log(obj);
										$('#min_d').val(obj[0]['min_date']);
										$('#max_d').val(obj[0]['max_date']);
										$('#inst').val(obj[0]['count_number']);
										$('#spin1').empty();
										x.style.display = "block";
									}
					});
                    /*var rtn = sourceURL.split("?")[0],
                        param,
                        params_arr = [],
                        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
                    if (queryString !== "") {
                        params_arr = queryString.split("&");
                        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                            param = params_arr[i].split("=")[0];
                            if (param === key) {
                                params_arr.splice(i, 1);
                            }
                        }
                        rtn = rtn + "?" + params_arr.join("&");
                    }
                    return rtn;*/
                }
				
				function viewOwner(id){
					console.log('View Owner:	'+id);
					$.ajax({
						url: 'get_heatmap.php',
						data: {
							action: 'get_owner',
							id: id
						},
						type: "GET",
						async: true,
						success: function(data) {
							//
							var obj = JSON.parse(data);
							$('#own_d').val(obj.user);
							}
					});
				}
                ///
                $(window).on('load', function() {
                    var sf = ''
                    if ((window.self !== window.top) || (nascondi == 'hide')) {
                        sf = 'false';
                    } else {
                        sf = 'true';
                    }
                    var originalURL = window.location.href;
                    var alteredURL = removeParam("order", originalURL);
                    alteredURL = removeParam("orderBy", alteredURL);
                    window.location.replace = alteredURL;

                });
            </script>
		</body>
    </html>
    <?php
	mysqli_close($link);
?>