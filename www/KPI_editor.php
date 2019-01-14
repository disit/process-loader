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
   
include("config.php");
include('external_service.php');
include("curl.php");

if (isset ($_SESSION['username'])){
  $utente_att = $_SESSION['username'];	
}else{
 $utente_att= "Login";	
}

if (isset($_GET['error_unique_key'])){
	$error = $_GET['error_unique_key'];
}else{
	$error  = '';
}

if (isset ($_SESSION['username'])){
  $role_att = $_SESSION['role'];	
}else{
 $role_att= "";	
}

$pagina_attuale = $_SERVER['REQUEST_URI'];

if (isset($_GET['orderBy'])){
$order = $_GET['orderBy'];
}else{
$order = 'id';	
}

if (isset($_GET['order'])){
	$by = $_GET['order'];
}else{
	$by = 'DESC';
}

if (isset($_REQUEST['showFrame'])){
	if ($_REQUEST['showFrame'] == 'false'){
		$hide_menu= "hide";
	}else{
		$hide_menu= "";
	}	
}else{$hide_menu= "";}


if (!isset($_GET['pageTitle'])){
	$default_title = "KPI Editor";
}else{
	$default_title = "";
}

$start_from = 0;

if (isset($_GET['limit'])|| $_GET['limit']!==""){
$limit=$_GET['limit'];
}else{
$limit = 10;  
}
if ($_GET['limit'] == ""){
$limit = 10;  
}

if (isset($_GET["page"])) { 
		$page  = $_GET["page"]; 
	} else { 
		$page=1; 
	};
$start_from = ($page-1) * $limit; 
//	
$query_n = "SELECT DashboardWizard.* FROM processloader_db.DashboardWizard";
//

$total_rows_query = $query_n;
$query_n = $query_n . "	ORDER BY ".$order." ".$by." LIMIT ".$start_from.", ".$limit.";";
//
$link = mysqli_connect($host_kpi, $username_kpi, $password_kpi) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_kpi);

$result = mysqli_query($link, $query_n) or die(mysqli_error($link));
$process_list = array();
$num_rows = $result->num_rows;

    if ($result->num_rows > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
						array_push($process_list, $row);
				}
	}

$result0 = mysqli_query($link, $total_rows_query) or die(mysqli_error($link));
		$total_rows = $result0->num_rows;
	mysqli_close($link);
////
$error_name="";
if (isset($_GET['error'])){
	$error_name="OK";
}
?>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Process Loader System</title>
		
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
		<link href="css/bootstrap.css" rel="stylesheet">
         <link href="css/loader.css" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link href="css/dashboard.css" rel="stylesheet">
       <link href="css/dashboardList.css" rel="stylesheet">
	   <!-- -->
	<script type="text/javascript" src="js/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/restables-master/restables.js"></script>
	<!-- 
	<script type="text/javascript" src="js/Easy-Table-List-Pagination-Plugin-With-jQuery-Paginathing/paginathing.min.js"></script>
	 -->
    <link href="js/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<!-- -->
	<!-- -->
    </head>
	<body class="guiPageBody">
	<style>
	@media screen and (max-width: 1980px) {
			.responsive {
				//display: none;
				 font-size: 0.8 em;
			}
		}
		
	@media screen and (max-width: 1400px) {
			.responsive {
				display: none;
				font-size: 0.8 em;
			}
		}
		
	@media screen and (max-width: 1600px) {
			.responsive {
				//display: none;
				font-size: 0.5 em;
			}
		}
	

	a {color: #337ab7;}
	td i {text-align:center;}
	table {table-layout:auto; max-width: 100% min-height: .01%; overflow-x: auto;}
	td {max-width: 100px; border:1px solid #000000; word-wrap:break-word;}
	th {max-width: 100px; word-wrap:break-word;}
	
	@media screen and (max-width: 767px){
    #DataTypes {
        width: 100%;
        margin-bottom: 15px;
        overflow-y: hidden;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border: 1px solid #ddd;
		
		}
	}

	table.restables-clone {
    display: none;
}

@media (max-width: 750px) {
    table.restables-origin {
        display: none;
    }
	
	.responsive {
				display: visible;
			}
    
    table.restables-clone {
        display: table;
    }
}

	
</style>
	<?php include('functionalities.php'); ?>
        <div class="container-fluid">
		<div class="row mainRow" style='background-color: rgba(138, 159, 168, 1)'>
		<?php 
		if ($hide_menu != "hide"){
                include "mainMenu.php"; 
				echo ('<div class="col-xs-12 col-md-10" id="mainCnt">');
			}else{
							echo('<div class="col-xs-12 col-md-12" id="mainCnt">');
							
						}
			?>
                    <div class="row hidden-md hidden-lg">
                        <div id="mobHeaderClaimCnt" class="col-xs-12 hidden-md hidden-lg centerWithFlex">
                            Snap4City
                        </div>
                    </div>
                    <?php
				if ($hide_menu != "hide"){
                    echo ('<div class="row" id="title_row">
                        <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt">'.$_GET['pageTitle'].'</div>
                        <!--<div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"></div>-->
						<div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php include "mobMainMenu.php" ?></div>
                    </div>');
				}
					?>
                    <div class="row" id="contenitore_table">
					<div class="col col-lg-2 panel-group" style="margin-top:35px; width:100%">
					<button id="edit" class="btn btn-warning" data-toggle="modal"  type="reset" value="" data-target="#myModal">Add New</button>
					</div>
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:5px'>
					<!---->
					<?php include('functionalities.php'); 
					?>
					<!-- -->
						<div class="modal fade" id="myModal" role="dialog">
							<form class="change_ownership" id="add_modal" method="post" action="insert_KPI.php">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Add new KPI:</h4></div>
													<div class="modal-body">
													<div class="row">
														<ul class="list-group">
															<li class="list-group-item" style="display:none;"><b>Id:	 	</b><input type="text" name="id" id="label_id0" value=""></input></li>				
															<li class="list-group-item"><b>Nature: *	    	     </b><select name="nature" id="select0" required></select></li>
															<!-- -->
															<li class="list-group-item" id="other_nature_select0" style="display:none;"><b>Nature:       	*</b><input type="text" name="other_nature" value=""></input></li>	
															<li class="list-group-item"><b>Subnature: *           	</b><select name="subnature" id="select_s0"></select></li>
															<!-- -->
															<li class="list-group-item" id="other_nature_select_s0" style="display:none;"><b>Sub Nature:       	</b><input type="text" name="other_subnature" value=""></input></li>
															<li class="list-group-item"><b>Valuetype: *</b><input type="text" name="valuetype" value="" autocomplete="off" size="50" required></input></li>
															<li class="list-group-item"><b>valueName: *       	</b><input type="text" name="valuename" value="" autocomplete="off" size="50" required></input></li>
															<!--<li class="list-group-item"><b>Data type:    		</b><input type="text" name="datatype" id="id_link0" autocomplete="off" required></input></li>-->
															<li class="list-group-item"><b>Data Type: * </b><select name="datatype" id="id_link0" required><option value="integer">integer</option><option value="float">float</option><option value="percentage">percentage</option><option value="status">status</option></select></li>
															<!---->															
															<!--<li class="list-group-item"><b>Ownership:   		</b><input type="text" name="ownership" id="id_own0" autocomplete="off" required></input></li>-->
															<li class="list-group-item"><b>Ownership: *   		</b><select name="ownership" id="id_own0" autocomplete="off" required><option value="private">private</option><option value="public">public</option></select></li>
															<!-- -->
															<li class="list-group-item"><b>Description:     	</b><input type="text" name="description" id="id_des0" size="50" value="" autocomplete="off"></input></li>
															<li class="list-group-item"><b>Info:		        </b><input type="text" name="info" id="id_info0" size="50" autocomplete="off"></input></li>
															<li class="list-group-item"><b>Latitudes:	    	</b><input type="text" name="latitudes" id="id_lat0" size="50" autocomplete="off"></input></li>
															<li class="list-group-item"><b>Longitudes:	    	</b><input type="text" name="longitudes" id="id_lon0" size="50" autocomplete="off"></input></li>
															<li class="list-group-item"><b>Parameters:	    	</b><input type="text" name="paramters" id="id_par0" size="50" autocomplete="off"></input></li>
														</ul>
													</div>
													</div>
												<div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											<input type="submit" value="Submit" class="btn btn-secondary">
										</div>
									</div>
								</div>
							</form>
						</div>
					<!-- -->
					<div class="modal fade" id="data-modal3" role="dialog">
							<form class="change_ownership" id="edit_modal" method="post" action="modify_KPI.php">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
												<h4 class="modal-title">Edit KPI Metadata:</h4></div>
													<div class="modal-body">
													<div class="row">
														<ul class="list-group">
															<li class="list-group-item" style="display:none;"><b>Id:	 	</b><input type="text" name="id" id="label_id" value=""></input></li>
															<li class="list-group-item" style="display:none;"><b>type:	 	</b><input type="text" name="type" id="label_id_type_static" class="form-control" value="st" size="50"></input></li>
															<!--<li class="list-group-item"><b>Datetime of insert:	</b><input type="text" name="datatime_of_insert" id="label_name" value=""></input></li>-->
															<li class="list-group-item"><b>Nature:	    	     </b><select name="nature" id="select"></select></li>
															<!--<li class="list-group-item"><b>Nature:	    	     </b><select name="nature" id="select"><option value="Infrastructure">Infrastructure</option><option value="Mobility and Transport">Mobility and Transport</option><option value="Social">Social</option><option>Other...</option></select></li>-->
															<li class="list-group-item" id="other_nature_select" style="display:none;"><b>Nature:       	</b><input type="text" name="other_nature" value=""></input></li>
															<!--<li class="list-group-item"><b>Subnature:           	</b><select name="subnature" id="select_s"><option class="inf" value="DISCES">DISCES</option><option class="inf" value="Engaged">Engaged</option><option class="inf" value="Mobile App">Mobile App</option><option class="inf" value="Notifications">Notifications</option><option class="inf" value="Smart City API">Smart City API</option><option class="mob" value="Parking Free Spaces">Parking Free Spaces</option><option class="mob" value="Public Transportation">Public Transportation</option><option class="soc" value="Wi-Fi">Wi-Fi</option><option>Other...</option></select></li>-->
															<li class="list-group-item"><b>Subnature:           	</b><select name="subnature" id="select_s"></select></li>
															<!-- -->
															<li class="list-group-item" id="other_nature_select_s" style="display:none;"><b>Sub Nature:       	</b><input type="text" name="other_subnature" value=""></input></li>
															<li class="list-group-item"><b>value Name:       	</b><input type="text" name="valuename" id="value_name" value="" autocomplete="off" size="50"></input></li>
															<li class="list-group-item"><b>value Type:       	</b><input type="text" name="valuetype" id="value_type" value="" autocomplete="off" size="50"></input></li>
															<!--<li class="list-group-item"><b>Data type:    		</b><input type="text" name="datatype" id="id_link" autocomplete="off"></input></li>-->
															<li class="list-group-item"><b>Data Type: </b><select name="datatype" id="id_link" required><option value="integer">integer</option><option value="float">float</option><option value="percentage">percentage</option><option value="status">status</option></select></li>
															<!---->	
															<!--<li class="list-group-item"><b>Ownership:   		</b><input type="text" name="ownership" id="id_own" autocomplete="off"></input></li>-->
															<li class="list-group-item"><b>Ownership:   		</b><select name="ownership" id="id_own" autocomplete="off" required><option value="private">private</option><option value="public">public</option></select></li>
															<!-- -->
															<!-- -->
															<li class="list-group-item"><b>Description:     	</b><input type="text" name="description" id="id_des" value="" autocomplete="off" size="50"></input></li>
															<li class="list-group-item"><b>Info:		        </b><input type="text" name="info" id="id_info" autocomplete="off" size="50"></input></li>
															<li class="list-group-item"><b>Latitudes:	    	</b><input type="text" name="latitudes" id="id_lat" autocomplete="off" size="50"></input></li>
															<li class="list-group-item"><b>Longitudes:	    	</b><input type="text" name="longitudes" id="id_lon" autocomplete="off" size="50"></input></li>
															<li class="list-group-item"><b>Parameters:	    	</b><input type="text" name="paramters" id="id_par" autocomplete="off" size="50"></input></li>
														</ul>
													</div>
													</div>
												<div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											<input type="submit" value="Submit" class="btn btn-secondary">
										</div>
									</div>
								</div>
							</form>
						</div>
					<!---->
					<div class="modal fade" id="data-modal_rt" role="dialog">
							<form class="change_ownership" id="edit_modal_rt" method="post" action="modify_KPI.php">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
												<h4 class="modal-title">Insert Real Time Data:</h4></div>
													<div class="modal-body">
													<div class="row">
														<ul class="list-group">
															<li class="list-group-item" style="display:none;"><b>Id:	 	</b><input type="text" name="id" id="label_id_rt" class="form-control" value=""></input></li>
															<li class="list-group-item" style="display:none;"><b>type:	 	</b><input type="text" name="type" id="label_id_type" class="form-control" value="rt"></input></li>
															<li class="list-group-item" style="display:none;"><b>type val:	 	</b><input type="text" name="type_val" id="type_val" class="form-control" value=""></input></li>
															<li class="list-group-item">
															<b>Last Date:	</b>
																<div class="input-group">
																<input type="text" name="last_date" id="last_date" class="form-control datepicker" value="" autocomplete="off" required></input>
																	<span class="input-group-btn">
																		<button class="btn btn-default" id="now" type="button">Get date</button>
																   </span>
																</div>
															</li>
															<li class="list-group-item"><b>Last Value:		</b><input type="text" class="form-control" name="last_value" id="last_value" step="any" autocomplete="off" required></input></li>
														</ul>
													</div>
													</div>
												<div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal" id="close_rt">Close</button>
											<input type="submit" value="Submit" id="rt_data_insert" class="btn btn-secondary">
										</div>
									</div>
								</div>
							</form>
						</div>
					<!---->
					<div class="modal fade" id="data-modal_rt2" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
												<h4 class="modal-title">View KPI Values:</h4></div>
													<div class="modal-body">													
													<div class="row" id="filter_row">
													<input type="text" name="id" id="label_id_filter_view" class="form-control" value="" style="display:none;"></input>
														<div>
														<p>
														<span>Filter from date:</span><input type="text" name="date" id="date" onchange="myFunction()" class="datepicker" autocomplete="off" size="50"></input>
														</p>
														<p>
														<span>Filter to date	:</span><input type="text" name="date2" id="date2" onchange="myFunction()" class="datepicker" autocomplete="off" size="50"></input>
														</p>
														<p>
														<span>Filter by value: </span><input type="text" name="filter" id="filter" onchange="myFunction()" autocomplete="off" size="50"></input>
														</p>
														</div>
													</div>
													<div class="row">
													<input type="text" name="currente_page" id="currente_page" value=""  style="display:none;"></input>
													<div>
														<select name="limit" id="limit_select2" onchange="myFunction()">
															<option value="5">5</option>
															<option value="10">10</option>
															<!--<option value="20">20</option>-->
														</select>
													</div>
													
													
														<table id="kpi_values" class="table table-striped table-bordered">
														<thead>
														<th>Date</th>
														<th>Value</th>
														</thead>
														<tbody id="emp_body">
														<tr>
														</tr>
														</tbody>
														</table>
														<div id="pagination-container">
														<a href="#" id="view_prev"  onclick="prev_page()"><< Prev Page	</a>
														<span></span>
														<a href="#" id="view_next"  onclick="next_page()">	Next Page >></a>
														</div>
														 														 
													</div>
														<div class="row" id="void_values" style="display:none;">
															<span>There are no values for this KPI. </span>
														</div>
													</div>
												<div class="modal-footer"><button  type="button" class="btn btn-default" data-dismiss="modal" id="close_view">Close</button>
										</div>
									</div>
								</div>
						</div>
					<!---->
				<div>
				<select name="limit" id="limit_select">
				<option value="5">5</option>
				<option value="10">10</option>
				<!--<option value="15">15</option>-->
				</select>
				</div>
                <table id="DataTypes" class="table table-striped table-bordered">
					<thead class="dashboardsTableHeader">
					<?php 
					if ($by == 'DESC'){
						$by_par = 'ASC';
						
						$icon_by = '<i class="fa fa-caret-down" style="color:white"></i>';
					}else{
						$by_par = 'DESC';
						$icon_by = '<i class="fa fa fa-caret-up" style="color:white"></i>';
					}
				
					echo('
					<tr>
                        <th class="Id" style="display:none;"><a href="'.$pagina_attuale.'&orderBy=Id&order='.$by_par.'">Id '.$icon_by.'</a></th>
						<th class="click"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=datetime_of_insert">Datetime of insert '.$icon_by.'</a></th>
						<th><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=last_date">Last Date '.$icon_by.'</a></th>
                        <th class="Tool"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=nature">Nature '.$icon_by.'</a></th>
						<th class="url"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=sub_nature">Sub Nature '.$icon_by.'</a></th>
                        <th class="click"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=low_level_type">Value Type '.$icon_by.'</a></th>
						<th class="click"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=unique_name_id">Value Name '.$icon_by.'</a></th>
						<th class="click"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=unit">Data Type '.$icon_by.'</a></th>
						<th><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=last_value">Last Value '.$icon_by.'</a></th>
						<th><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=ownership">Ownership '.$icon_by.'</a></th>
						<th class="responsive"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=Description">Description '.$icon_by.'</a></th>
						<th class="responsive"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=Info">Info '.$icon_by.'</a></th>
						<th class="responsive"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=latitudes">latitudes '.$icon_by.'</a></th>
						<th class="responsive"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=longitudes">longitudes '.$icon_by.'</a></th>
						<th class="responsive"><a href="'.$pagina_attuale.'&order='.$by_par.'&orderBy=parameters">parameters '.$icon_by.'</a></th>
						<th class="edit"><div>Edit</div></th>
						<th class="add_value"><div>Value</div></th>
						<th class="view"><div>View</div></th>
                    </tr>
					');
					?>
					</thead>	
					<tbody>
					<?php
					
					
					
					for ($i = 0; $i <= $num_rows; $i++) {	
						if ($process_list[$i]['id'] == ""){
									$edit_button="";
									$delete_button="";
									$view_button="";
								}else{
									$edit_button="<button type='button' class='editDashBtn modify_jt' data-target='#data-modal3' data-toggle='modal'>EDIT</button>";
									$delete_button="<button type='button' class='editDashBtn delete_jt' data-target='#data-modal_rt' data-toggle='modal'>ADD</button>";
									$view_button="<button type='button' class='viewDashBtn mostra_jt' data-target='#data-modal_rt2' data-toggle='modal'>VIEW</button>";
								}
					
							echo ("<tr>
										<td style='display:none;'>".$process_list[$i]['id']."</td>
									    <td>".$process_list[$i]['datetime_of_insert']."</td>
										<td>".$process_list[$i]['last_date']."</td>
										<td>".$process_list[$i]['nature']."</td>
										<td>".$process_list[$i]['sub_nature']."</td>
										<td>".$process_list[$i]['low_level_type']."</td>
										<td>".$process_list[$i]['unique_name_id']."</td>
										<td>".$process_list[$i]['unit']."</td>
										<td>".$process_list[$i]['last_value']."</td>
										<td>".$process_list[$i]['ownership']."</td>
										<td class='responsive'>".$process_list[$i]['Description']."</td>
										<td class='responsive'>".$process_list[$i]['Info']."</td>
										<td class='responsive'>".$process_list[$i]['latitudes']."</td>
										<td class='responsive'>".$process_list[$i]['longitudes']."</td>
										<td class='responsive'>".$process_list[$i]['parameters']."</td>
										<td>".$edit_button."</td>
										<td>".$delete_button."</td>
										<td>".$view_button."</td>
								</tr>");
							}
					
					?>
					</tbody>
                </table>
				<!------>
						<?php 
							$total_records = $total_rows;
							$total_pages = ceil($total_records / $limit);
								$prev_page = $_GET["page"] -1;
								$suc_page = $_GET["page"] +1;
								$corr_page= $_GET["page"];
								$array_link = array ();
								/////
							if ($prev_page >=1){
							echo ('	<div class="pagination" value="'.$prev_page.'">&#09;<a href="KPI_editor.php?elementTool='.$_GET['elementTool'].'&elementUrl='.$_GET['elementUrl'].'&page='.$prev_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'"><< 	Prev</a></div>');
							}
										if ($corr_page >11){
										$init_j = $corr_page -10;
										}else{$init_j = 1; 
										}
							////
							for ($j=$init_j; $j<=$total_pages; $j++) {  
										if (($j<11)||(($corr_page-$j)>=0)||(($corr_page == $j)&&($corr_page < $total_pages-3))||(($corr_page >= $total_pages-3))){
											echo ("&#09;<a class='page_n' value='".$j."' href='KPI_editor.php?elementTool=".$_GET['elementTool'].'&elementUrl='.$_GET['elementUrl']."&page=".$j."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$j."</a>&#09;");
										}else{echo(" ");}
							}; 
							//
							
									$last_pages = $total_pages-3;
								if (($total_pages > 13)&&($corr_page < $last_pages)){
											echo ("...");
											for ($y=$last_pages; $y<=$total_pages; $y++) {  
												echo ("&#09;<a class='page_n' value='".$y."' href='KPI_editor.php?elementTool=".$_GET['elementTool'].'&elementUrl='.$_GET['elementUrl']."&page=".$y."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$y."</a>&#09;");	
											};
									}
							//
							if (($suc_page <=$total_pages)&&($total_pages > 1)){
									echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="KPI_editor.php?elementTool='.$_GET['elementTool'].'&elementUrl='.$_GET['elementUrl'].'&page='.$suc_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'">Next 	>></a></div>');
							}
						?>
                 <!----->
						<?php 
				if ($num_rows == 0){
					echo ('<div class="panel panel-default"><div class="panel-body">There are not KPI elements</div></div>');
				}
				?>
                </div>
				</div>
				
		</div>
		</div>
		
    </div>
    
  </div>
</div>

<script type='text/javascript'>

$(document).ready(function () {
	//
	var error = "<?=$error; ?>";
	if (error !=""){
		alert('UNIQUE KEY yet used');
	}
	//
	$('#DataTypes').resTables();	
	/////
	$(function(){	
			var dateNow = new Date();
			$('.datepicker').datetimepicker({
				dateFormat: 'yyyy-mm-dd',
				timeFormat:  "HH:mm:ss",
				language: 'it',
				use24hours: true
			});
		});
		//
		$('#select0').change(function() {
			var selected = $('#select0').val();
			$("#select_s0 option:not(."+selected +")").hide();
			$("."+selected).show();
		});
		
		$('#select').change(function() {
			var selected = $('#select').val();
			///

			//AGGIUNTA DEL FILTRO DELLE SUBNATURE//
					 var array_process = new Array();
		
							$.ajax({
								url: "nature_list.php",
								type:"GET",
								data: {nature:'nature1'},
								async: true,
								dataType: 'json',
								success: function (data1) {
									$('#select_s').empty();
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
										$('#select_s').append('<option value="'+array_process[y]['sub_nature']+'" class="'+array_process[y]['nature']+'" >'+array_process[y]['sub_nature']+'</option>');
										}
									}
								}
							});
			$("#select_s option:not(."+selected +")").hide();
			$("."+selected).show();
			//
			///
		});
		
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
					
			utente_attivo=$("#utente_att").text();
			if (utente_attivo=='Login'){
					$(document).empty();
					alert("Effettua il login!");
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
		//
		var error_name="<?=$error_name; ?>";
		var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('.ellipsis').css("width","150 px");
			$('#edit_modal').attr("action","modify_KPI.php?showFrame=false");
			$('#add_modal').attr("action","insert_KPI.php?showFrame=false");
			$('#edit_modal_rt').attr("action","modify_KPI.php?showFrame=false");
			//$('#delete_modal').attr("action","delete_help.php?showFrame=false");
			
		}
		
		//
		//CREAZIONE DELLA LISTA DELLE NATURE//		
		$.ajax({
			url: "nature_list.php",
			type:"GET",
			data: {list:'list'},
			async: true,
            dataType: 'json',
            success: function (data) {
				for (var i = 0; i < data.length; i++){
				$('#select').append('<option value="'+data[i]+'">'+data[i]+'</option>');
				$('#select0').append('<option value="'+data[i]+'">'+data[i]+'</option>');
								}
			}
		});
		
		//
		//LISTA DELLE SOTTO NATURE//
		 var array_process = new Array();
		
		$.ajax({
			url: "nature_list.php",
			type:"GET",
			data: {nature:'nature1'},
			async: true,
            dataType: 'json',
            success: function (data1) {
				
				for (var y = 0; y < data1.length; y++){
					//
					array_process[y] = {	
							id: data1[y].process['id'],
                            nature: data1[y].process['nature'],
                            sub_nature: data1[y].process['sub_nature']
						};
					//
					$('#select_s').append('<option value="'+array_process[y]['sub_nature']+'" class="'+array_process[y]['nature']+'" >'+array_process[y]['sub_nature']+'</option>');
					$('#select_s0').append('<option value="'+array_process[y]['sub_nature']+'" class="'+array_process[y]['nature']+'" >'+array_process[y]['sub_nature']+'</option>');
				}
				//
			}
		});
		//

		var limit_default= "<?=$limit; ?>";
		$('#limit_select').val(limit_default);	

var pagina_corrente="<?=$corr_page; ?>";
		$("a.page_n[value='"+pagina_corrente+"']").css("text-decoration","underline");
	
		/////////////
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
		
	
		$(document).on('click','.delete_jt',function(){
			var ind = $(this).parent().parent().first().children().html();
			var date = $(this).parent().parent().children().eq(2).html();
			var id_link = $(this).parent().parent().children().eq(7).html();
			$('#label_id_rt').val(ind);
			$('#type_val').val(id_link);
			$('#last_value').attr('type',id_link);
		});
	
		$(document).on('click','.mostra_jt',function(){
			var ind = $(this).parent().parent().first().children().html();
			$('#filter').val();
			$('#currente_page').val(0);
			var limit = $('#limit_select2').val();
			var currente_page = $('#currente_page').val();
			console.log("currente_page:"+currente_page);
			$('#label_id_filter_view').val(ind);
			var count = 0;
			if (currente_page == 0){
				$('#view_prev').hide();
			}else{
				$('#view_prev').show();
			}

			var array_act = new Array();
				$.ajax({
				url: "get_KPI_values.php",
                data: {id:ind, limit:limit, currente_page:currente_page},
                type: "GET",
                async: true,
                dataType: 'json',
                success: function (data) {
							console.log(data);
							//
							for (var i = 0; i < data.length; i++){
									 array_act[i] = {
									 id: data[i].process['id'],
									 kpi: data[i].process['kpi'],
									 date: data[i].process['date'],
									 value: data[i].process['value'],
									 
									}
								//console.log(array_act[i]);
								$('#kpi_values tbody').append('<tr><td>'+array_act[i]['date']+'</td><td>'+array_act[i]['value']+'</td></tr>');
								count = count + 1;
								}
							console.log('COUNT:	'+count);
									if(count < limit){
									$('#view_next').hide();
								}else{
									$('#view_next').show();
								}
								if (count == 0){
									$('#kpi_values').hide();
									$('#limit_select2').hide();
									$('#filter_row').hide();
									$('#void_values').show();
								}else{
									$('#kpi_values').show();
									$('#limit_select2').show();
									$('#filter_row').show();
									$('#void_values').hide();
								}
						}
				});
		});
	//
	$(document).on('click','#close_view',function(){
		$('#kpi_values tbody').empty();
		$('#currente_page').val(0);
		$('#limit_select2').val(5);
		$('#filter').val(null);
		$('#date').val(null);
		$('#date2').val(null);
	});
	
	$(document).on('click','#close_rt',function(){
		$('#last_date').val(null);
		$('#last_value').val(null);
	});
	//
	/////
	//////
	$(document).on('click','#now',function(){
		//
					var today = new Date();
					var dd = today.getDate();
					var mm = today.getMonth()+1; //January is 0!
					var yyyy = today.getFullYear();
					var hh = today.getHours();
					var min = today.getMinutes();
					var ss = today.getSeconds();
					if(dd<10) {
						dd = '0'+dd
					} 
					if(mm<10) {
						mm = '0'+mm
					} 
					if(min<10) {
						min = '0'+min
					} 
					if(ss<10) {
						ss = '0'+ss
					} 
					
					today = yyyy+'-'+ mm + '-' + dd +' '+hh+':'+min+':'+ss;
				$('#last_date').val(today);
			});
	//////
		$(document).on('click','.modify_jt',function(){
			var ind = $(this).parent().parent().first().children().html();
			var link = $(this).parent().parent().children().eq(2).html();
			var insert = $(this).parent().parent().children().eq(1).html();
			var sub_nature = $(this).parent().parent().children().eq(4).html();
			var nature = $(this).parent().parent().children().eq(3).html();
			var value_name = $(this).parent().parent().children().eq(6).html();
			var id_link = $(this).parent().parent().children().eq(7).html();
			var id_own =  $(this).parent().parent().children().eq(9).html();
			var id_des =  $(this).parent().parent().children().eq(10).html();
			var id_info = $(this).parent().parent().children().eq(11).html();
			//
			var id_lat =  $(this).parent().parent().children().eq(12).html();
			var id_lon =  $(this).parent().parent().children().eq(13).html();
			var id_par = $(this).parent().parent().children().eq(14).html();
			var value_type = $(this).parent().parent().children().eq(5).html();
			//	
			//
			$('#label_id').val(ind);
			//
			$('#label_name').val(insert);
			$('#value_name').val(value_name);
			$('#id_link').val(id_link);
			//$('#id_own').val(id_own);
			$('#select option[value="'+nature+'"]').attr('selected','selected');
			$('#select_s option[value="'+sub_nature+'"]').attr('selected','selected');
			console.log(nature);
			console.log(sub_nature);
			$('#id_own option[value="'+id_own+'"]').attr('selected','selected');
			//
			$('#id_des').val(id_des);
			$('#id_info').val(id_info);
			//
			$('#id_lat').val(id_lat);
			$('#id_lon').val(id_lon);
			$('#id_par').val(id_par);
			$('#value_type').val(value_type);
			//
			//AGGIUNTA DEL FILTRO DELLE SUBNATURE//
					 var array_process = new Array();
		
							$.ajax({
								url: "nature_list.php",
								type:"GET",
								data: {nature:'nature1'},
								async: true,
								dataType: 'json',
								success: function (data1) {
									$('#select_s').empty();
									for (var y = 0; y < data1.length; y++){
										//
										
										//
										array_process[y] = {	
												id: data1[y].process['id'],
												nature: data1[y].process['nature'],
												sub_nature: data1[y].process['sub_nature']
											};
										//
										if (array_process[y]['nature'] == nature){
										$('#select_s').append('<option value="'+array_process[y]['sub_nature']+'" class="'+array_process[y]['nature']+'" >'+array_process[y]['sub_nature']+'</option>');
										}
									}
									$('#select_s option[value="'+sub_nature+'"]').attr('selected','selected');
									//
								}
							});
			//
		});
	
        //Versione client side ma ben funzionanete del caricamento
		var array_act = [];
	
		//Unico Button///
		$('#limit_select').change(function() {
			var limit_val = $('#limit_select').val();
			var filterElId = $('#filterId').val();
			var elementType = $('#filterTool').val();
			var elementUrl = $('#filterUrl').val();
			if((window.self !== window.top)||(nascondi == 'hide')){	
							window.location.href = 'KPI_editor.php?showFrame=false&page=1&elementTool='+elementType+'&elementUrl='+elementUrl+'&limit='+limit_val;						
						}
						else{
							window.location.href = 'KPI_editor.php?showFrame=true&page=1&elementTool='+elementType+'&elementUrl='+elementUrl+'&limit='+limit_val;	
					}
		});
		
		if (error_name=='OK'){
			alert('Operation failed. You can not use this Label name, because is already used.');
		}
	
			//	
			//
				$(document).on('click','#rt_data_insert',function(){
					var type_val =$('#type_val').val();
					var valore=$('#last_value').val();
					var last_date=$('#last_date').val();
					var label_id_type=$('#label_id_type').val();
					if (type_val=='integer'){
						var result = is_int(valore);
						console.log(result);
						if (result == false){
								alert('Value not valid! Please insert a  '+type_val+' variable');
								event.preventDefault();
							}else{
								console.log('VERO');
								///
							}

							}
					
					
					else if (type_val=='float'){
						var result1 = is_float(valore);
							console.log(result);
							if (result1 == false){
								alert('Value not valid! Please insert a  '+type_val+' variable');
								event.preventDefault();
							}else{
								console.log('VERO');
							}

							
					} else {
						//controllioamo se è un numero
							var result2 = is_float(valore);
							console.log(result);
							if (result2 == false){
								alert('Value not valid! Please insert a  '+type_val+' variable');
								event.preventDefault();
							}else{
								console.log('VERO');
							}
						}
					
				});
			
					function is_numeric(n) {
					  return !isNaN(parseFloat(n)) && isFinite(n);
					}
					
					function is_int(n){
					  if (!is_numeric(n)) return false
					  else return (n % 1 == 0);
					}

					function is_float(n){

					 val = parseFloat(n);
						if(isNaN(val))
							return false
						else return true;
					}
});

window.onload = preselect_subnature;

function preselect_subnature(){
			var selected = $('#select0').val();
			$("#select_s0 option:not(."+selected +")").hide();
			$("#select_s0 ."+selected).show();
			//
			var selected1 = $('#select').val();
			$("#select_s option:not(."+selected1 +")").hide();
			$("#select_s ."+selected1).show();
			
}

function prev_page(){
	var currente_page = $('#currente_page').val();
	var prec = Number(currente_page)-1;
	if (prec < 0){
		prec = 0;
	}
	$('#currente_page').val(prec);
	myFunction();
}

function next_page(){
	var currente_page = $('#currente_page').val();
	var nex = Number(currente_page)+1;
	$('#currente_page').val(nex);
	myFunction();
}


	function myFunction() {
			var ind = $('#label_id_filter_view').val();
			$('#kpi_values tbody').empty();
			var val = $('#filter').val();			
			var date = $('#date').val();
			var date2 = $('#date2').val();
			var array_act2 = new Array();
			var limit = $('#limit_select2').val();
			var currente_page = $('#currente_page').val();
			//
			var start = (Number(currente_page) + Number(limit));
			var end = (Number(start) + Number(limit) );
			//
			var count = 0;
				$.ajax({
					url: "get_KPI_values.php",
					type: "GET",
					async: true,
					dataType: 'json',
					data: {filter: val, id:ind, date:date, date2:date2, limit:limit, currente_page:currente_page},
					success:function (data) {
								if (currente_page == 0){
										$('#view_prev').hide();
									}else{
										$('#view_prev').show();
									}
								for (var i = 0; i < data.length; i++)
									{							
										array_act2[i] = {
										 id: data[i].process['id'],
										 kpi: data[i].process['kpi'],
										 date: data[i].process['date'],
										 value: data[i].process['value'],
										 
										}			
									$('#kpi_values tbody').append('<tr class="selector"><td>'+array_act2[i]['date']+'</td><td>'+array_act2[i]['value']+'</td></tr>');
									count = count + 1;
									}
						console.log('COUNT: '+ count);
								if(count < limit){
									$('#view_next').hide();
								}else{
									$('#view_next').show();
								}
								if (count == 0){
									$('#kpi_values').hide();
									$('#limit_select2').hide();
									$('#void_values').show();
								}else{
									$('#kpi_values').show();
									$('#limit_select2').show();
									$('#void_values').hide();
								}
					}
				});
						
		}
</script>
</body>
</html>