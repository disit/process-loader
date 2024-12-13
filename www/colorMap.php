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
include('functionalities.php');

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
	$default_title = "Color Map Editor";
}
//
//// EXTRACT_HEATMAPS FROM DB ////
$pagina_attuale = $_SERVER['REQUEST_URI'];
$link = mysqli_connect($host_heatmap, $username_heatmap, $password_heatmap) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_heatmap);

if (isset($_REQUEST['orderBy'])){
$order = $_REQUEST['orderBy'];
}else{
$order = 'metric_name';	
}

if (isset($_REQUEST['order'])){
	$by = $_REQUEST['order'];
}else{
	$by = 'ASC';
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

$filter = '';
if (isset($_REQUEST["filter"])) { 
	$filter  = $_REQUEST["filter"]; 
} else { 
	$filter= ''; 
};  
$start_from = ($page-1) * $limit; 
if($filter != ''){
$query_n = "SELECT DISTINCT colors.metric_name FROM heatmap.colors WHERE colors.metric_name LIKE '%" .$filter."%' ORDER BY colors.metric_name ".$by." LIMIT " . $start_from . ", " . $limit;
}else{
	$query_n = "SELECT DISTINCT colors.metric_name FROM heatmap.colors ORDER BY colors.metric_name ".$by." LIMIT " . $start_from . ", " . $limit;
}

    $result = mysqli_query($link, $query_n) or die(mysqli_error($link));
    $process_list = array();
    $num_rows     = $result->num_rows;
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {	
            $listFile = array(
                "metric_name" => $row['metric_name'],
            );
            array_push($process_list, $listFile);
        }
    }

//
if($filter != ''){
	$query_n0 = "SELECT DISTINCT colors.metric_name FROM heatmap.colors WHERE colors.metric_name LIKE '%" .$filter."%'";
}else{
	$query_n0 = "SELECT DISTINCT colors.metric_name FROM heatmap.colors";
}
$total_rows_query = $query_n0;
$result0 = mysqli_query($link, $total_rows_query) or die(mysqli_error($link));
if ($result0->num_rows >0){
$total_rows = $result0->num_rows;
}

//LIST OF USERS
$query_users = "SELECT * FROM heatmap.colormapUsers";
$result_users = mysqli_query($link, $query_users) or die(mysqli_error($link));
$data_users = array(); // Array vuoto per i risultati
// Controlla se ci sono risultati
if ($result_users->num_rows > 0) {
    while($row = $result_users->fetch_assoc()) {
        $data_users[] = $row;
    }
}
// Converti l'array in JSON
$json_users = json_encode($data_users);

//LIST OF AUTHORIZED_USERS
$query_Auth_users = "SELECT * FROM heatmap.authorizedUsers";
$result_Auth_user = mysqli_query($link, $query_Auth_users) or die(mysqli_error($link));
$data_Auth_users = array(); // Array vuoto per i risultati
// Controlla se ci sono risultati
if ($result_Auth_user->num_rows > 0) {
    while($row = $result_Auth_user->fetch_assoc()) {
        $data_Auth_users[] = $row;
    }
}
// Converti l'array in JSON
$json_Auth_users = json_encode($data_Auth_users);
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
		<script type="text/javascript" charset="utf8" src="lib/datatables.js"></script>
		<script type="text/javascript" src="lib/dataTables.responsive.min.js"></script>
		<script type="text/javascript" src="lib/dataTables.bootstrap4.min.js"></script>
		<script type="text/javascript" src="lib/jquery.dataTables.min.js"></script>
		<link href="lib/responsive.dataTables.css" rel="stylesheet" />
		<!-- -->
    </head>
	<style>
	.hidden{
		display:none;
	}
	
	td{
		vertical-align: middle;
	}
	
	#heatmap_table{
		margin-bottom: 0px;
	}
	
	.mainContentCnt{
		margin-top: 0px;
	}
	
	#view-modal{
		width: auto;
	}
	
	#value_table {
		width: auto;
	}
	
	#typology_table{
			text-align: center;
	}
	
	.fa-circle{
			font-size: 24px;
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
                        <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt"><?php echo urldecode($_REQUEST['pageTitle']); ?></div>
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php 
						include "mobMainMenu.php" 
						?></div>
                    </div>
                    <div class="row" >
					<!-- -->
					<div id="view-menu" style="margin-right:45px; margin-top:20px; float: right;">
						<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#colormap-modal">
							Color Map Editor
						</button>
					</div>
					<!-- -->
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); padding-top:20px;'>
						<!---->	
							<div id="selector" style="float:left">
								<select name="limit" id="limit_select" >
								<option value="5">5</option>
								<option value="10">10</option>
								<option value="15">15</option>
								</select>
							</div>
							<!-- -->
							<div id="search" style="float:right">
							<span style="font-weight:bold">Search:</span>
							<input type="search" id="search-value" placeholder="search..." onkeydown="handleSearchOnEnter(event)">
							</div>
							<!-- -->
								<table id="heatmap_table" class="table table-striped table-bordered" style="width: 100%;">
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
					$pagina_attuale2='colorMap.php?showFrame='.$sf.'&page='.$corr_page1.'&orderBy=id&order='.$by_par.'&limit='.$limit.'';
					echo ('	<tr>
							<th class="metric_name"><div><a href="colorMap.php?showFrame='.$sf.'&page='.$page.'&orderBy=metric_name&order='.$by_par.'&limit='.$limit.'">Color Map	</a>'.$icon_by .'</div></th>
							<th class="edit"><div><a>Edit color map</a></div></th>
							<th class="view"><div><a>View color map</a></div></th>
							<th class="delete"><a><div>Delete</a></div></th>
						</tr>'); 
						?>
					</thead>	
					<tbody>
					<?php
							$found = false;
							$found_user = false;

							// Usa in_array o isset se $element_auth è un array associativo
							$json_Auth_users2 = json_decode($json_Auth_users);
							//var_dump($json_Auth_users2);
							foreach ($json_Auth_users2 as $element_auth) {
								$elementJson = $element_auth->username;
								if ($utente_att === $elementJson) {
									$found = true;
									break;
								}
							}

							$arrayData = json_decode($json_users, true); // Decodifica JSON una sola volta

							for ($i = 0; $i < $num_rows; $i++) {
								if (isset($process_list[$i]['metric_name'])) { // Verifica se 'metric_name' esiste
									foreach ($arrayData as $element) {
										if ((($element['username'] == $utente_att) && ($element['colormap'] == $process_list[$i]['metric_name']))) {
											$found_user = true;
											break;
										}else{
											$found_user = false;
										}
									}
									$view_legend = "<button type='button' class='viewDashBtn viewLegend' data-target='#legend-modal' data-toggle='modal' value='" . $process_list[$i]['metric_name'] . "'>VIEW LEGENDA</button>";

									if ($found_user || $role_att == 'RootAdmin' || $found) {
										echo ("<tr><td><p>" . $process_list[$i]['metric_name'] . "</p></td>");
										echo ("<td><button type='button' class='editDashBtn editColor' data-target='#edit-colormap' data-toggle='modal' value='" . $process_list[$i]['metric_name'] . "'>EDIT</button>	<button type='button' class='viewDashBtn loadColor' data-target='#load-colormap' data-toggle='modal' value='" . $process_list[$i]['metric_name'] . "'>LOAD LEGENDA</button> <button type='button' class='editDashBtn clone_colormap' data-target='#clone-colormap' data-toggle='modal' value='" . $process_list[$i]['metric_name'] . "'>SAVE AS</button></td>");
										echo ("<td><button type='button' class='viewDashBtn viewType' data-target='#typology-modal' data-toggle='modal' value='" . $process_list[$i]['metric_name'] . "'>VIEW</button> ".$view_legend."</td>");
										echo ("<td>	<button type='button' class='delDashBtn del_metdata' data-target='#delete-colormap' data-toggle='modal' value='" . $process_list[$i]['metric_name'] . "'>DEL</button></td>");
										echo ("</tr>");
									} else {
										echo ("<tr><td><p>" . $process_list[$i]['metric_name'] . "</p></td>");
										echo ("<td><button type='button' class='editDashBtn clone_colormap' data-target='#clone-colormap' data-toggle='modal' value='" . $process_list[$i]['metric_name'] . "'>SAVE AS</button></td>");
										echo ("<td><button type='button' class='viewDashBtn viewType' data-target='#typology-modal' data-toggle='modal' value='" . $process_list[$i]['metric_name'] . "'>VIEW</button> ".$view_legend."</td>");
										echo ("<td></td>");
										echo ("</tr>");
									}
								}
							}
							?>

						</tbody>
						</table>
						<!------>
						<?php 
							$total_records = $total_rows;
							$total_pages = ceil($total_records / $limit);
								$prev_page = $_REQUEST["page"] -1;
								$suc_page = $_REQUEST["page"] +1;
								$corr_page= $_REQUEST["page"];
								$filter_string = $REQUEST["filter"];
								$array_link = array ();
								/////
							if ($prev_page >=1){
							echo ('	<div class="pagination" value="'.$prev_page.'">&#09;<a href="colorMap.php?showFrame='.$_REQUEST['showFrame'].'&page=1&orderBy='.$order.'&order='.$by.'&limit='.$_REQUEST['limit'].'&filter='.$filter_string.'">First 	</a></div>');
							echo ('	<div class="pagination" value="'.$prev_page.'">&#09;<a href="colorMap.php?showFrame='.$_REQUEST['showFrame'].'&page='.$prev_page.'&orderBy='.$order.'&order='.$by.'&limit='.$_REQUEST['limit'].'">	<< 	Prev</a></div>');
							}		
										if ($corr_page >11){
										$init_j = $corr_page -10;
										}else{$init_j = 1; 
										}
							for ($j=$init_j; $j<=$total_pages; $j++) { 
									if ($j == $page){
										$style = 'text-decoration: underline';
									}else{
										$style='';
									}
										if (($j<11)||(($corr_page-$j)>=0)||(($corr_page == $j)&&($corr_page < $total_pages-3))||(($corr_page >= $total_pages-3))){
											echo ("&#09;<a class='page_n' value='".$j."' href='colorMap.php?showFrame=".$_REQUEST['showFrame']."&page=".$j."&orderBy=".$order."&order=".$by."&limit=".$_REQUEST['limit']."&filter=".$filter_string."' style='".$style."'>".$j."</a>&#09;");
										}else{echo(" ");}
											
							}; 
								$last_pages = $total_pages-3;
								if (($total_pages > 13)&&($corr_page < $last_pages)){
											echo ("...");
											for ($y=$last_pages; $y<=$total_pages; $y++) {  
												
												echo ("&#09;<a class='page_n' value='".$y."' href='colorMap.php?showFrame=".$_REQUEST['showFrame']."&page=".$y."&orderBy=".$order."&order=".$by."&limit=".$_REQUEST['limit']."&filter=".$filter_string."' >".$y."</a>&#09;");		 
											};
									}
							if ($suc_page <=$total_pages){
							
									echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="colorMap.php?showFrame='.$_REQUEST['showFrame'].'&page='.$suc_page.'&orderBy='.$order.'&order='.$by.'&limit='.$_REQUEST['limit']."&filter=".$filter_string.'" >Next 	>>	</a></div>');
									echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="colorMap.php?showFrame='.$_REQUEST['showFrame'].'&page='.$total_pages.'&orderBy='.$order.'&order='.$by.'&limit='.$_REQUEST['limit'].'&filter='.$filter_string.'" >   	Last</a></div>');
							}
						?>
						<!----->

					</div>
					<!--  -->
					<div class="modal fade bd-example-modal-lg" id="delete-colormap" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
							<div class="modal-dialog">
									<form name="Modify Metadata" method="post" action="#" id="delete_Heatmap">
									<div class="modal-content">
									<div class="modal-header" style="background-color: white">Delete Colormap</div>
									<div class="modal-body" style="background-color: white">
									Are you sure do you want delete this Color map? 
									<div>
									<input type="text" id="id_heat" class="hidden">
									</div>
									</div>
									<div class="modal-footer" style="background-color: white">
									<button type="button" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
									<input type="button" value="Confirm" class="btn confirmBtn" id="delete_colormap"/>
									</div>
									</div>
									</form>
							</div>
					  </div>
					<!-- -->			
					<!-- View -->
					<div class="modal fade bd-example-modal-lg" id="typology-modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
							<div class="modal-dialog modal-lg">
									<form name="Modify Metadata" method="post" action="#" id="typology_Heatmap">
									<div class="modal-content">
									<div class="modal-header" style="background-color: white" id="typology_header">Color map </div>
									<div class="modal-body" style="background-color: white">
									<div>
									<table id="typology_table" class="table table-striped table-bordered">
										<thead class="dashboardsTableHeader">
											<th class="hidden"><div>Index</div></th>
											<th class="min"><div>Minimum</div></th>
											<th class="max"><div>Maximum</div></th>
											<th class="rgb"><div>Rgb</div></th>	
											<th class="color"><div>Color descr.</div></th>	
											<th class="order"><div>Order</div></th>											
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
					<!-- view modal -->
					<div class="modal fade bd-example-modal-sm" id="legend-modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
							<div class="modal-dialog modal-sm">
									<div class="modal-content">
									<div class="modal-header" style="background-color: white" id="leged_header">View Legenda</div>
									<div class="modal-body" style="background-color: white">
									<div id="image_leged" style="display: flex; align-items: center; justify-content: center;">
									</div>
									</div>
									<div class="modal-footer" style="background-color: white">
									<button type="button" class="btn cancelBtn" id="typology_close" data-dismiss="modal">Cancel</button>
									<!--<input type="submit" value="Confirm" class="btn confirmBtn"/>-->
									</div>
									</div>
							</div>
					  </div>
					<!-- -->
					<div class="modal fade bd-example-modal-lg" id="edit-colormap" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
							<div class="modal-dialog modal-lg">
									<form name="Modify Color Map" method="post" action="get_heatmap.php?showFrame=<?php echo($sf); ?>&page=<?php echo($page); ?>&orderBy=<?php echo($order); ?>&order=<?php echo($by); ?>&limit=<?php echo($limit); ?>&action=modify_colormap" id="color_Heatmap">
									<div class="modal-content">
									<div class="modal-header" style="background-color: white" id="color_header">Edit Color map: </div>
									<div class="modal-body" style="background-color: white">
									<input type="text" name="metric_name" id="metric_name" readonly hidden></input>
									<input type="text" name="count_rows" id="count_rows" readonly hidden></input>
									<div>
									<table id="colormap_table" class="table table-striped table-bordered">
										<thead class="dashboardsTableHeader">
											<th class="hidden"><div>Index</div></th>
											<th class="min"><div>Minimum Limit</div></th>
											<th class="max"><div>Maximum Limit</div></th>
											<th class="rgb"><div>Rgb</div></th>	
											<th class="color"><div>Color descr.</div></th>	
											<th class="order"><div>Order</div></th>	
											<th class="delete"><div>Delete</div></th>												
										</thead>
										<tbody>
										</tbody>
									</table>
									</div>
									<div id="list_deleted">
									</div>
										<div>
											<p>
											<button type="button" class="btn confirmBtn" id="add_color">Add Color</button>
											</p>
										</div>
									</div>
									<div class="modal-footer" style="background-color: white">
									<button type="button" class="btn cancelBtn" id="color_close" data-dismiss="modal">Cancel</button>
									<input type="submit" value="Confirm" class="btn confirmBtn"/>
									</div>
									</div>
									</form>
							</div>
					  </div>
					<!-- -->
					<!-- CREATE COLOR MAP -->
					<div class="modal fade bd-example-modal-lg" id="colormap-modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
							<div class="modal-dialog modal-lg">
									<!--<form name="Modify Metadata" method="post" action="get_heatmap.php?showFrame=false&page=1&orderBy=map_name&order=ASC&limit=15" id="color_Heatmap_create">-->
									<!-- $sf -->
									<form name="Modify Metadata" method="post" action="get_heatmap.php?showFrame=<?php echo($sf); ?>&page=<?php echo($page); ?>&orderBy=<?php echo($order); ?>&order=<?php echo($by); ?>&limit=<?php echo($limit); ?>" id="color_Heatmap_create">
									<div class="modal-content">
									<div class="modal-header" style="background-color: white" id="color_header_create">Create New Color map </div>
									<div class="modal-body" style="background-color: white">
									<!-- -->
									<div class="input-group"><span class="input-group-addon">Color map Name: </span><input name="name_color_map" id="name_color_map" type="text" class="form-control" required="required"/></div>
									<br />
									<input id="filetype1" name="action" value="color_map_create" style="display: none;"></input>
									<input type="text" name="count_rows0" id="count_rows0" value="0" readonly hidden></input>									
									<!-- -->
									<div>
									<table id="colormap_table_create" class="table table-striped table-bordered">
										<thead class="dashboardsTableHeader">
											<th class="hidden"><div>Index</div></th>
											<th class="min"><div>Minimum Limit</div></th>
											<th class="max"><div>Maximum Limit</div></th>
											<th class="rgb"><div>Rgb</div></th>	
											<th class="color"><div>Color descr.</div></th>	
											<th class="order"><div>Order</div></th>	
											<th class="delete"><div>Delete</div></th>												
										</thead>
										<tbody>
										</tbody>
									</table>
									</div>
										<div>
											<p>
											<button type="button" class="btn confirmBtn" id="add_color0">Add Color</button>
											</p>
										</div>
									</div>
									<div class="modal-footer" style="background-color: white">
									<button type="button" class="btn cancelBtn" id="color_close0" data-dismiss="modal">Cancel</button>
									<input type="submit" value="Confirm" class="btn confirmBtn" id="color_map_create"/>
									</div>
									</div>
									</form>
							</div>
					  </div>
					<!-- --> 
						<!-- CLONE MODAL-->
						<div class="modal fade bd-example-modal-lg" id="clone-colormap" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
							<div class="modal-dialog modal-lg">
									<div class="modal-content">
									<div class="modal-header" style="background-color: white" id="color_header_create">Clone Color map</div>
									<div class="modal-body" style="background-color: white">
									<!-- -->
									<div class="input-group"><span class="input-group-addon">Color map Name: </span><input name="name_color_map_ro" id="name_color_map_ro" type="text" class="form-control" readonly/></div>
									<br />
									<div class="input-group"><span class="input-group-addon">Save as: </span><input name="name_color_map_new" id="name_color_map_new" type="text" class="form-control"/></div>
									<div class="modal-footer" style="background-color: white">
									<button type="button" class="btn cancelBtn" id="color_close0" data-dismiss="modal">Cancel</button>
									<input type="submit" value="Confirm" class="btn confirmBtn" id="command_clone"/>
									</div>
									</div>
							</div>
						</div>
						</div>
						<!-- FINE CLONE MODAL -->
										<!-- SAVE MODAL-->
							<div class="modal fade bd-example-modal-lg" id="load-colormap" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
									<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header" style="background-color: white" id="color_header_create">Load colormap in repository</div>
												<div class="modal-body" style="background-color: white">
												<div>Are you sure you want to load this color map in the repository?</div>
												<br />
													<!-- -->
													<div class="input-group" style="display:none;"><input name="colormap_to_load" id="colormap_to_load" type="text" class="form-control" readonly/></div>
													<div class="input-group">
															<label class="form-label" for="fileInput">Upload: </label>
															<input type="file" name="form-control" class="form-control" id="fileInput" />
													</div>
													<div class="input-group" id="fileLinkDiv" style="display:none;">
													</div>
													<br />
													<!-- -->
												</div>
												<div class="modal-footer" style="background-color: white">
													<button type="button" class="btn cancelBtn" id="color_close0" data-dismiss="modal">Cancel</button>
													<input type="submit" value="Confirm" class="btn confirmBtn" id="command_save"/>
												</div>
											</div>
									</div>
								</div>
						<!-- FINE SAVE MODAL -->
		</div>
		</div>

    </div>
    
  </div>
</div>
<script type='text/javascript'>

var nascondi= "<?=$hide_menu; ?>";
var corrent_page = "<?=$page; ?>";
var titolo_default = "<?=$default_title; ?>";
var start_from = "<?=$start_from; ?>";
var limit_val = $('#limit_select').val();
	
$(document).ready(function () {
	
		var table = $('#heatmap_table').DataTable({
								"searching": false,
								"paging": false,
								"ordering": false,
								"info": false,
								"responsive": true
							});
	
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
		}

		var role_active = $("#role_att").text();
	if (role_active == 'ToolAdmin'){
		$('#sc_mng').show();
	}
	var search_val ="<?=$filter; ?>";
	
	if(search_val !== ''){
		$('#search-value').val(search_val);
		$('#search-value').focus();
	}
	//
	var limit_default= "<?=$limit; ?>";
	$('#limit_select').val(limit_default);
	
	var role="<?=$role_att; ?>";
	
		if (role == ""){
			$(document).empty();
			//window.alert("You need to log in to access to this page!");
			if(window.self !== window.top){
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
						window.location.href='https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';	
						}else{
						window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
						}
					}
		//
		
		//
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
	   /****/
	   /****/
	   $(document).on('click','.viewList',function(){
			var map_name = $(this).parent().parent().first().children().eq(0).html();
			$('#list_header').text('Heatmap Instances List:	'+map_name);		
			list_scroll(0, map_name); 
			
	  });
	  /****/
	  $(document).on('click','#list_close',function(){
		  $('#value_table tbody').empty();
		  $('#list_header').empty();
		  $('#link_list').empty();
		  location.reload();
	  });
	  /*****/
	  $(document).on('click','.viewLegend', function(){
		$('#image_leged').empty();
		var metric = $(this).val();
		//
		$.ajax({
				url:'get_heatmap.php',
				data: {metric:metric, action:'view_legend'},
				type: "POST",
				async: true,
				dataType: 'json',
				success: function (data) {
					if(data.status == 'success'){
						$('#image_leged').html('<img src="'+data.link+'" width="auto" height="auto" style="text-align: center;">');
					}else{
						$('#image_leged').html('<p>Currently there is not a legenda image for this colormap</p>');
					}
				}
		});
		//
		console.log(metric);
	  });

	  /*****/
	  $(document).on('click','.viewType',function(){
			//var metric = $(this).parent().parent().first().children().eq(0).children().eq(0).html();
			var metric = $(this).val();
			$('#typology_header').text('Color Map:	'+metric);
			var array = new Array();
			$.ajax({
				url:'get_heatmap.php',
				data: {metric:metric, action:'color_map'},
				type: "POST",
				async: true,
				dataType: 'json',
				success: function (data) {
					for (var i = 0; i < data.length; i++) {
							var data_id= data[i]['id'];
							var rgb= data[i]['rgb'];	
							var color=data[i]['color'];
							var order=data[i]['order'];
							var min=data[i]['min'];
							var max=data[i]['max'];
							if (min == null){
								min='';
							}
							if (max == null){
								max='';
							}
							
							rgb0 = rgb.replace("[","(");
							rgb = rgb0.replace("]",")");
							$('#typology_table tbody').append('<tr><td hidden>'+data_id+'</td><td>'+min+'</td><td>'+max+'</td><td class="hidden"></td><td>'+rgb+' <p><i class="fa fa-circle" style="color: rgb'+rgb+'"></i></p></td><td>'+color+'</td><td>'+order+'</td></tr>');
						}
					
				}
			});
	  });
	  
	  /*****/
	  $(document).on('click','#typology_close',function(){
			 $('#typology_table tbody').empty();
			//			 
			});
     /*****/
	 
	//btn cancelBtn	
	$(document).on('click','.cancelBtn',function(){
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
					var searchTerm_label = document.getElementById("search-value").value;
					links = $("a.page_n");
					for(var i=0; i < links.length; i++){
						var str = links[i].href;
						var new_link = str.replace('&limit='+limit_default, '&limit='+limit_val);
						links[i].href = new_link;
					}
					if((window.self !== window.top)||(nascondi == 'hide')){	
							window.location.href = 'colorMap.php?showFrame=false&page='+corrent_page+'&limit='+limit_val+'&filter='+searchTerm_label;						
						}
						else{
							window.location.href = 'colorMap.php?showFrame=true&page='+corrent_page+'&limit='+limit_val+'&filter='+searchTerm_label;	
					}
			});
	/*****/
	//det_data
	$(document).on('click','.det_data',function(){
		var id= $(this).parent().parent().first().children().html();
		var data= $(this).parent().parent().first().children().eq(3).html();
		var curr_data = $('.current_link').text();
		$('#id_data').val(id);
		$('#date_data').val(data);
		$('#current_data').val((curr_data)-1);
	});	
	/*****/
	$(document).on('click','#confirmDeletedData',function(){
		var id=$('#id_data').val();
		var date=$('#date_data').val();
		var page = $('#current_data').val();
		if (page < 0){
			page = 0;
		}		
				$.ajax({
						url:'get_heatmap.php',
						data: {id:id, date:date, action:'delete_data'},
						type: "POST",
						async: true,
						success: function (data) {
							$('#data_elimination').modal('hide');
							list_scroll(page, id); 
						}
					});	
	});
	/****/
	$(document).on('click','.del_metdata',function(){
		//var id= $(this).parent().parent().first().children().html();
		//var id = $(this).parent().parent().first().children().eq(0).children().eq(0).html();
		var id = $(this).val();
		$('#id_heat').val(id);
	});	
	
/****/
$(document).on('click','#color_close', function(){
	$('#colormap_table tbody').empty();
	$('#list_deleted').empty();
});
/****/
$(document).on('click','#color_close0', function(){
	$('#colormap_table_create tbody').empty();
	$('#name_color_map').val('');
});

//delete_heatmap
$(document).on('click','#delete_colormap', function(){
	var id_heat = $('#id_heat').val();
	////
	$.ajax({
			url:'get_heatmap.php',
			data: {id:id_heat, action:'delete_colormap'},
			type: "POST",
			async: true,
			success: function (data) {
				$('#delete_Heatmap').modal('hide');
				alert('Colormap successfully deleted');
				location.reload();
			}
		});
	////
});
/**confirmEditData**/
$(document).on('click','#confirmEditData', function(){
		var status = $('#status').val();
		var indexed = $('#indexed').val();
		var id = $('#map_name_val').val();
		var date_val=$('#date_val').val();
		var corr = ($('#current_data_val').val()-1);
		if (corr < 0){
			corr = 0;
		}
		
		$.ajax({
				url:'get_heatmap.php',
							data: {id:id, date_val:date_val, status:status, indexed:indexed, action:'edit_metadata'},
							type: "POST",
							async: true,
							success: function (data) {
									$('#edit-valus').modal('hide');										
									list_scroll(corr, id);
									//
							}
					});

		
});
/****/
//#edit-modal
$(document).on('click','.editColor',function(){
			//var metric = $(this).parent().parent().first().children().eq(0).children().eq(0).html();
			var metric = $(this).val();
			$('#color_header').text('Edit Color Map:	'+metric);
			$('#metric_name').val(metric);
			//
			var array = new Array();
			$.ajax({
				url:'get_heatmap.php',
				data: {metric:metric, action:'color_map'},
				type: "POST",
				async: true,
				dataType: 'json',
				success: function (data) {
					for (var i = 0; i < data.length; i++) {
							var data_id= data[i]['id'];
							var rgb= data[i]['rgb'];	
							var color=data[i]['color'];
							var order=data[i]['order'];
							var min=data[i]['min'];
							var max=data[i]['max'];
							if (min == null){
								min='';
							}
							if (max == null){
								max='';
							}
							
							rgb0 = rgb.replace("[","(");
							rgb = rgb0.replace("]",")");
							$('#colormap_table tbody').append('<tr><td class="hidden"><input type="text" class="form-control val_min" name="paramId['+i+']" value="'+data_id+'" readonly>'+data_id+'</input></td><td><input type="text" class="form-control val_min" name="paramMin['+i+']" value="'+min+'" readonly></input></td><td><input name="paramMax['+i+']" type="text" class="form-control val_max mod_max" value="'+max+'"></input></td><td class="hidden"></td><td><div id="color-picker-rgb" class="input-group rgb_color"><input type="text" value="rgb'+rgb+'" class="form-control" name="paramRgb['+i+']"/><span class="input-group-addon"><i></i></span></div></td><td><input name="paramColor['+i+']" type="text" class="form-control" class="val_rgb" value="'+color+'"></input></td><td><input name="paramOrder['+i+']" type="text" class="form-control" class="val_order" value="'+order+'"></input></td><td><button type="button" class="delDashBtn del_color1" value="'+i+'" data-target="" data-toggle="modal">DEL</button></td></tr>');
						}
					$('#count_rows').val(i);
					$('.rgb_color').colorpicker();
				}
			});
	  });
/****/
//add_color
$(document).on('click','#add_color',function(){
	var count = $('#colormap_table tbody tr').length;
	var count_rows = $('#count_rows').val();
	//
	var value0 = ""; 
	var min = count-1;
	console.log('min:',min);
	if((min >= 0) && !isNaN(min)){
			 value0 = $('input[name="paramMax['+min+']"]').val();
			 console.log('value0:',value0);
		if((value0 == null)&&(typeof value0 !== 'undefined')){
				value0 = $('input[name="paramMax['+min+']"]').val();
				//paramMaxMod
			}else if(typeof value0 == 'undefined'){
				value0 = ""; 
			}else{

			}
	}
	
	//$('#colormap_table tbody').append('<tr><td class="hidden"></td><td><input name="paramMinMod['+count+']" type="text" class="form-control val_min mod_min"  value="'+value0+'" readonly></input></td><td><input type="text" class="form-control val_max mod_max" name="paramMaxMod['+count+']" value=""></input></td><td class="hidden"></td><td><div id="color-picker-rgb" class="input-group rgb_color" ><input type="text" value="rgb(255,255,255)" class="form-control" name="paramRgbMod['+count+']"/><span class="input-group-addon"><i></i></span></div></td><td><input name="paramColorMod['+count+']" type="text" class="form-control" class="val_rgb"></input></td><td><input name="paramOrderMod['+count+']" type="text" class="form-control" class="val_order"></input></td><td><button type="button" class="delDashBtn del_color1" value="'+count+'" data-target="" data-toggle="modal">DEL</button></td></tr>');
	$('#colormap_table tbody').append('<tr><td class="hidden"></td><td><input name="paramMin['+count_rows+']" type="text" class="form-control val_min mod_min"  value="'+value0+'" readonly></input></td><td><input type="text" class="form-control val_max mod_max" name="paramMax['+count_rows+']" value=""></input></td><td class="hidden"></td><td><div id="color-picker-rgb" class="input-group rgb_color" ><input type="text" value="rgb(255,255,255)" class="form-control" name="paramRgbMod['+count_rows+']"/><span class="input-group-addon"><i></i></span></div></td><td><input name="paramColorMod['+count_rows+']" type="text" class="form-control" class="val_rgb"></input></td><td><input name="paramOrderMod['+count_rows+']" type="text" class="form-control" class="val_order"></input></td><td><button type="button" class="delDashBtn del_color1" value="'+count_rows+'" data-target="" data-toggle="modal">DEL</button></td></tr>');
	var new_val = Number(count_rows);
	new_val = new_val +1;
	$('#count_rows').val(new_val);
	$('.rgb_color').colorpicker();
	//
});
//
//add_color
$(document).on('click','#add_color0',function(){
	//
	var count_rows0 = $('#count_rows0').val();
	var count = $('#colormap_table_create tbody tr').length;
	var min = count-1;
		var cont2 = count+1;
	
	if(min >= 0){
			var value0 = $('input[name="paramMax0['+min+']"]').val();
			if(value0 == null){
				value0 = "";
			}
	}else{
		value0 = "";
	}
	
	//$('#colormap_table_create tbody').append('<tr><td class="hidden"></td><td><input type="text" class="form-control val_min ed_min" name="paramMin0['+count+']" id="val_min0" value="'+value0+'" readonly></input></td><td><input type="text" class="form-control val_max ed_max" id="val_max0" name="paramMax0['+count+']" value=""></input></td><td><div id="color-picker-rgb" class="input-group rgb_color"><input type="text" value="rgb(255,255,255)" class="form-control" name="paramRgb0['+count+']"/><span class="input-group-addon"><i></i></span></div></td><td><input name="paramColor0['+count+']" type="text" class="form-control" class="val_rgb" value="" required></input></td><td><input type="text" class="form-control" class="val_order" name="paramOrder0['+count+']" value="'+cont2+'"></input></td><td><button type="button" class="delDashBtn del_color1" value="'+count+'" data-target="" data-toggle="modal">DEL</button></td></tr>');
	$('#colormap_table_create tbody').append('<tr><td class="hidden"></td><td><input type="text" class="form-control val_min ed_min" name="paramMin0['+count_rows0+']" id="val_min0" value="'+value0+'" readonly></input></td><td><input type="text" class="form-control val_max ed_max" id="val_max0" name="paramMax0['+count_rows0+']" value=""></input></td><td><div id="color-picker-rgb" class="input-group rgb_color"><input type="text" value="rgb(255,255,255)" class="form-control" name="paramRgb0['+count_rows0+']"/><span class="input-group-addon"><i></i></span></div></td><td><input name="paramColor0['+count_rows0+']" type="text" class="form-control" class="val_rgb" value="" required></input></td><td><input type="text" class="form-control" class="val_order" name="paramOrder0['+count_rows0+']" value="'+cont2+'"></input></td><td><button type="button" class="delDashBtn del_color0" value="'+count_rows0+'" data-target="" data-toggle="modal">DEL</button></td></tr>');
	$('.rgb_color').colorpicker();
	$('#count_rows0').val(count + 1);
	//
});
////
/*$(document).on('click','#edit_colorMap', function(){
	//modify_colormap
	alert('send_data');
	var metric_name = $('#metric_name').val();
	//
	var paramColor = [];
	var paramId = [];
	var paramMin = [];
	var paramMax = [];
	var paramRgb = [];
	var paramOrder = [];
	var paramMinMod = [];
	var paramMaxMod = [];
	var paramRgbMod = [];
	var paramColorMod = [];
	var paramOrderMod = [];

		$('.paramId').each(function() {
			paramId.push($(this).val());
		});
		$('.paramMin').each(function() {
			paramMin.push($(this).val());
		});
		$('.paramMax').each(function() {
			paramMax.push($(this).val());
		});
		$('.paramRgb').each(function() {
			paramRgb.push($(this).val());
		});
		$('.paramColor').each(function() {
			paramColor.push($(this).val());
		});
		$('.paramOrder').each(function() {
			paramOrder.push($(this).val());
		});
		$('.paramMinMod').each(function() {
			paramMinMod.push($(this).val());
		});
		$('.paramMaxMod').each(function() {
			paramMaxMod.push($(this).val());
		});
		$('.paramRgbMod').each(function() {
			paramRgbMod.push($(this).val());
		});
		$('.paramColorMod').each(function() {
			paramColorMod.push($(this).val());
		});
		$('.paramOrderMod').each(function() {
			paramOrderMod.push($(this).val());
		});
	//
	$.ajax({
				url:'get_heatmap.php',
				data: {metric_name: metric_name,
						paramId: paramId,
						paramMin: paramMin,
						paramMax: paramMax,
						paramRgb: paramRgb,
						paramColor: paramColor,
						paramOrder: paramOrder,
						paramMinMod: paramMinMod,
						paramMaxMod: paramMaxMod,
						paramRgbMod: paramRgbMod,
						paramColorMod: paramColorMod,
						paramOrderMod: paramOrderMod,
					  action:'modify_colormap'
				},
				type: "POST",
				async: false,
				dataType: 'json',
				success: function (data) {
						console.log(data);
						alert(data.message);
						location.reload();
				}
			});
});*/
///
$(document).on('click','.clone_colormap', function(){
	var metric = $(this).val();
	$('#name_color_map_ro').val(metric);
	//var name_color_map = $('#name_color_map').val();
});

$(document).on('click','.loadColor',function(){
	var metric = $(this).val();
	console.log(metric);
	$('#fileLinkDiv').css('display','none');
	$('#fileLinkDiv').empty();
	$('#colormap_to_load').val(metric);
});

$(document).on('click','#command_save', function(){
const fileInput = document.getElementById('fileInput');
const file = fileInput.files[0];
const metric = $('#colormap_to_load').val();

if (file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('metric', metric);
    formData.append('action', 'load_colormap'); // Aggiungi tutti i parametri direttamente a formData

    $.ajax({
        url: 'get_heatmap.php',
        type: 'POST',
        data: formData,
        processData: false, // Impostato a false per evitare che jQuery trasformi i dati in una stringa
        contentType: false, // Impostato a false per permettere a jQuery di determinare il contentType corretto
        dataType: 'json',
        success: function (data) {
            console.log(data.message);
			//$('#fileLinkDiv').css('display','block');
			//$('#fileLinkDiv').html('<a href="'+data.link+'" target="_blank">Open Link</a>');
            alert(data.message);
            location.reload();
        },
        error: function (error) {
			alert(error.responseText);
			
            console.error("Errore durante l'invio del file:", error);
        }
    });
}


});

//command_clone
$(document).on('click','#command_clone', function(){
	var metric = $('#name_color_map_ro').val();
	var name_color_map = $('#name_color_map_new').val();
	//
	$.ajax({
				url:'get_heatmap.php',
				data: {metric:metric, 
					  name_color_map:name_color_map, 
					  action:'clone_colormap'
				},
				type: "POST",
				async: false,
				dataType: 'json',
				success: function (data) {
						console.log(data);
						alert(data.message);
						location.reload();
				}
			});

	//
});

$(document).on('click','.del_color0',function(){
	var id= $(this).parent().parent();
	var id2=$(this).parent().parent().first().children().text();
	var id4= $(this).val();
	var min= Number(id4)-1;
	var max= Number(id4)+1;
	console.log(id4+' '+min+' '+max);
	$(id).remove();
	//
	var el_min = document.getElementsByName("paramMin0["+max+"]");	
	var el_max = document.getElementsByName("paramMax0["+min+"]");
	var data_min = $(el_max).val();
	$(el_min).val(data_min);
	//
	 
	id3= id2.replace('DEL','');
	//
	var count = $('.delete_rows').length;
	//
	$('#list_deleted').append('<input type="text" class="delete_rows hidden" name="paramdel0['+count+']" value="'+id3+'"></input>');
});

//****////Eliminare il td corrente.
$(document).on('click','.del_color1',function(){
	var id= $(this).parent().parent();
	var id2=$(this).parent().parent().first().children().text();
	var id4= $(this).val();
	var min= Number(id4)-1;
	var max= Number(id4)+1;
	console.log(id4+' '+min+' '+max);
	$(id).remove();
	//
	var el_min = document.getElementsByName("paramMin["+max+"]");	
	var el_max = document.getElementsByName("paramMax["+min+"]");
	var data_min = $(el_max).val();
	$(el_min).val(data_min);
	//
	 
	id3= id2.replace('DEL','');
	//
	var count = $('.delete_rows').length;
	//
	$('#list_deleted').append('<input type="text" class="delete_rows hidden" name="paramdel['+count+']" value="'+id3+'"></input>');
});
});
//
//
$(document).on('keyup','.ed_max',function(){
	var str = $(this).attr('name');
	var val = $(this).val();
	var mySubString = str.substring(
    str.lastIndexOf("[") + 1, 
    str.lastIndexOf("]")
	);
	console.log('mySubString: '+mySubString);
	var count =  Number(mySubString)+1;
	console.log('val: '+val);
	var el = document.getElementsByName("paramMin0["+count+"]");
	$(el).val(val);
	//console.log('el:'+el);
	
});
//
$(document).on('keyup','.mod_max',function(){
	var str = $(this).attr('name');
	var val = $(this).val();
	var mySubString = str.substring(
    str.lastIndexOf("[") + 1, 
    str.lastIndexOf("]")
	);
	console.log('mySubString: '+mySubString);
	var count =  Number(mySubString)+1;
	console.log('val: '+val);
		var el = document.getElementsByName("paramMin["+count+"]");
	 var el2 = document.getElementsByName("paramMinMod["+count+"]");
	$(el).val(val);
	$(el2).val(val);
	//console.log('el:'+el);
	
});
//
function list_scroll(page, name) {
  var array = new Array();
  $('#corrent').text(page);
  $.ajax({
		url:'get_heatmap.php',
		data: {page:page, map_name:name, action:'get_values'},
		type: "POST",
		async: true,
		dataType: 'json',
		success: function (data) {
					//
					$('#value_table tbody').empty();
					$('#link_list').empty();
					$('#data_content').empty();
					//
					var count=0;
					if(data.length > 0){
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
						var status= array[i]['status'];
						var index = array[i]['index'];
						var id_completed=name;
						//
						var style='';
						var status_text='';
						if(status == '1'){
							status_text='Completed';
						}else if(status == '0'){
							status_text='Not Completed';
						}else{
							status_text='Error';
							style='Style="color:red"';
						}
						
						//var a1= $('.current_link').text();
						//date_val
						var function_a = "function_data('"+id_completed+"','"+array[i]['date']+"',"+status+","+index+")";
						//
						$('#value_table tbody').append('<tr><td class="hidden">'+name+'</td><td class="hidden">'+status+'</td><td class="hidden">'+index+'</td><td>'+array[i]['date']+'</td><td>'+array[i]['description']+'</td><td '+style+'>'+status_text+'</td><td>'+array[i]['bbox']+'</td><td>'+array[i]['value']+'</td><td><button type="button" class="editDashBtn editValues" data-target="#edit-valus" onclick="'+function_a+'" data-toggle="modal">EDIT</button></td><td><button type="button" class="delDashBtn det_data" data-target="#data_elimination" data-toggle="modal">DEL</button></td></tr>');
						count++;
						}
						//if(count >= 10){
							var total_rows= array[0]['total'];
							if(total_rows > 10){
							var count_rows = Math.floor(array[0]['total']/10);
									if(array[0]['total'] > 10){
										if (page > 0){
											var m=page-1;
											$('#link_list').append('	<a class="page_n_link" value="0" href="#" onclick=list_scroll(0,"'+name+'") >'+' First  '+'</a>	');
											$('#link_list').append('	<a class="page_n_link" value="'+m+'" href="#" onclick=list_scroll('+m+',"'+name+'") >'+'  <<  '+'</a>	');
									}
										
									if (page < 11){
										var start=0;
									}else{
										var start=page-10;
									}
									var corrent_val= $('#corrent').val();
									for(var y=start; y<start+10; y++){
										n=y+1;
										var style = '';
										var current_link='';
										//
										if (y == page){
											style = 'text-decoration: underline';
											current_link='current_link';
										}else{
											style='';
											current_link='';
										}
										//
										
									if(y <= count_rows){
									$('#link_list').append('	<a class="page_n_link '+current_link+'" value="'+y+'" href="#" onclick=list_scroll('+y+',"'+name+'") style="'+style+'">'+n+'</a>	');
									}
								}
								if(page < count_rows){
								var p=page+1;
								$('#link_list').append('	<a class="page_n_link" value="'+p+'" href="#" onclick=list_scroll('+p+',"'+name+'") >'+'  >>  '+'</a>	');
								$('#link_list').append('	<a class="page_n_link" value="'+count_rows+'" href="#" onclick=list_scroll('+count_rows+',"'+name+'") >'+' Latest '+'</a>	');
							}
							}
						}else{
							console.log('nothing');
						}
				}else{
					$('#data_content').append('<div class="panel panel-default"><div class="panel-body">There are no data</div></div>');					
				}
			}
		
	});
}

function function_data(id_completed, data, status,index){
			$('#map_name_val').val(id_completed);
			$('#date_val').val(data);		
			$('#status').val(status);
			$('#indexed').val(index);
			var current_data_val = $('.current_link').text();
			$('#current_data_val').val(current_data_val);
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
function handleSearchOnEnter(event) {
	search_val = document.getElementById("search-value").value;
        if (event.key === 'Enter') { // Verifica se è stato premuto Enter
            handleSearch();
        }
    }

function handleSearch() {
            const searchTerm = document.getElementById("search-value").value;
			////
			var limit_select = $('#limit_select').val();
			const currentURL = window.location.href;
			var new_current = currentURL + '&filter='+searchTerm;
			console.log("new_current: ", new_current);
			if((window.self !== window.top)||(nascondi == 'hide')){	
							window.location.href = 'colorMap.php?showFrame=false&page='+corrent_page+'&limit='+limit_select+ '&filter='+searchTerm;						
						}
						else{
							window.location.href = 'colorMap.php?showFrame=true&page='+corrent_page+'&limit='+limit_select+ '&filter='+searchTerm;	
					}
            // Aggiungi qui la logica per gestire la ricerca
        }
///
$(window).on('load', function() {
	var sf =''
	if((window.self !== window.top)||(nascondi == 'hide')){	
							sf ='false';							
						}
						else{
							sf='true';
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