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


//include('config.php'); // Includes Login Script
include ('external_service.php');
if (isset ($_SESSION['username'])){
  $utente_att = $_SESSION['username'];	
}else{
 $utente_att= "Login";	
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
	$default_title = "Audit Personal Data Violation";
}else{
	$default_title = "";
}

if (isset($_GET['user'])||$_GET['user'] !==""){
	//$user = $_GET['user'];
	$user_lab= $_GET['user'];
	$user_lab_trim = str_replace(' ','',$user_lab);
	$user = "username LIKE '%".$user_lab_trim."%' ";
	//$user_lab= $_GET['user'];
}else{
	$user = "username LIKE '%%'	";
	$user_lab = "";
}
if ($_GET['user'] ==""){
	//$user = "username LIKE '%%' ";
	$user = "";
	$user_lab ="";
}

if (isset($_GET['appName'])||$_GET['appName'] !==""){
	$appN_lab = $_GET['appName'];
	//$appN = " AND app_name LIKE '%".$_GET['appName']."%'	";
	$appN = "	app_name LIKE '%".$_GET['appName']."%'	";
}else{
	$appN = "";
	$appN_lab = "";
}
if ($_GET['appName'] == ""){
	$appN = "";
	$appN_lab = "";
}


if(isset($_GET['accesstype'])||$_GET['accesstype'] !==""){
	//$accesstype = " AND access_type ='".$_GET['accesstype']."'";
	$accesstype = " access_type ='".$_GET['accesstype']."'";
	$accessType_lab = $_GET['accesstype'];
}else{
	$accesstype = "";
	$accessType_lab = "";
}

if ($_GET['accesstype']==""){
	$accesstype = "";
	$accessType_lab = "";
}

if (isset($_GET['sourceRequest'])||$_GET['sourceRequest'] !==""){
	$sourceRequest = " source_request ='".$_GET['sourceRequest']."'";
	$sourceRequest_lab = $_GET['sourceRequest'];
}else{
	$sourceRequest = "";
	$sourceRequest_lab="";
}

if ($_GET['sourceRequest'] ==""){
	$sourceRequest = "";
	$sourceRequest_lab="";
}

if (isset($_GET['varN'])||$_GET['varN'] !==""){
	$varN_lab = $_GET['varN'];
	$varN_lab_trim = str_replace(' ','',$varN_lab);
	//$varN = " AND variable_name LIKE'%".$_GET['varN']."%'	";
	$varN = "	variable_name LIKE'%".$varN_lab_trim."%'	";
}else{
	$varN = "";
	$varN_lab = "";
}

if(isset($_GET['domain'])||$_GET['domain'] !==""){
	$domain= "AND domain='".$_GET['domain']."'";
}else{
	$domain= "";
}

if ($_GET['domain'] == ""){
	$domain = "";
}

if ($_GET['varN'] == ""){
	$varN = "";
	$varN_lab = "";
}

if (isset($_GET['mot'])||$_GET['mot'] !==""){
	$mot_lab = $_GET['mot'];
	$mot_lab_trim = str_replace(' ','',$mot_lab);
	$mot = " motivation LIKE '%".$mot_lab_trim."%'	";
	//$mot = " AND motivation LIKE '%".$_GET['mot']."%'	";
}else{
	$mot = "";
	$mot_lab = "";
}

if ($_GET['mot'] == ""){
	$mot = "";
	$mot_lab = "";
}

if (isset($_GET['delUser'])||$_GET['delUser'] !==""){
	$delUser_lab = $_GET['delUser'];
	$delUser_trim = str_replace(' ','',$delUser_lab); 
	$delUser = " AND delegated_username LIKE '%".$delUser_trim."%'	";
}else{
	$delUser = "";
}

if (isset($_GET['delApp'])||$_GET['delApp'] !==""){
	$delApp_lab=$_GET['delApp'];
	$delApp = " AND delegated_app_name LIKE '%".$_GET['delApp']."%'	";
	
}else{
	$delApp = "";
	$delApp_lab="";
}

if ($_GET['delUser'] ==""){
	$delUser = "";
}

if ($_GET['delApp'] ==""){
	$delApp = "";
	$delApp_lab="";
}

//$total_rows = 0;
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

if (isset($_GET["start"])||$_GET["start"] !=""){
	//$start_d = " AND time>='". $_GET["start"]."'	";
	$start_lab = $_GET['start'];
	$start_d = " time>='". $_GET["start"]."'	";
}else{
	$start_d = "";
	$start_lab = "";
}

if ($_GET["start"] == ""){
	$start_d ="";
	$start_lab="";
}

if (isset($_GET["end"])|| $_GET["end"] !=""){
	//$end_d ="AND time<='".$_GET["end"]."'";
	$end_lab = $_GET['end'];
	$end_d =" time<='".$_GET["end"]."'";
}else{
	$end_d = "";
	$end_lab = "";
	//$end_d =" AND time<='5018-12-31 17:25:44'";
}
if ($_GET["end"] == ""){
	$end_d ="";
	$end_lab ="";
}

if (isset($_GET['ip_address'])){
	$ip_address_lab=$_GET['ip_address'];
	$ip_address_lab_trim = str_replace(' ','',$ip_address_lab); 
	$ip_address="ip_address LIKE '%".$ip_address_lab_trim."%'";
}else{
		$ip_address_lab="";
		$ip_address="";
}

if ($_GET['ip_address']==""){
	$ip_address_lab="";
		$ip_address="";
}

$query_n = "SELECT activity_violation.* FROM profiledb.activity_violation";
//Controlla//
//if (isset($_GET['user'])||isset($_GET['appName'])||isset($_GET['varN'])||isset($_GET['mot'])||isset($_GET["start"])||isset($_GET["end"])){
if (($user_lab != "")||($appN_lab !="")||($varN_lab !="")||($mot_lab !="")||($start_lab !="")||($end_lab !="")||($accessType_lab !="")||($sourceRequest_lab !="")||($ip_address_lab != "")){
	//$query_n = $query_n . "WHERE activity.id > -1";
	$query_n = $query_n . "	WHERE ";
///
if ($user != ""){
	$query_n = $query_n . $user;
}
if ($start_d !=""){
	if ($user != ""){
		$query_n = $query_n . 'AND';
	}
	$query_n = $query_n . $start_d;
}
if ($end_d !=""){
		if (($user != "")||($start_d !="")){
		$query_n = $query_n . 'AND';
	}
	$query_n = $query_n . $end_d;
}
if ($varN !=""){
	if (($user != "")||($start_d !="")||($end_d !="")){
		$query_n = $query_n . 'AND';
	}
	$query_n = $query_n . $varN;
}
if ($appN !=""){
	if (($user != "")||($start_d !="")||($varN !="")||($end_d !="")){
		$query_n = $query_n . 'AND';
	}
	$query_n = $query_n . $appN;
}
if ($mot !=""){
	if (($user != "")||($start_d !="")||($varN !="")||($end_d !="")||($appN !="")){
		$query_n = $query_n . 'AND';
	}
	$query_n = $query_n . $mot;
}
if ($accesstype != ""){
	if (($user != "")||($start_d !="")||($varN !="")||($end_d !="")||($appN !="")||($mot !="")){
		$query_n = $query_n . 'AND';
	}
	$query_n = $query_n . $accesstype;
}
/*
if ($delApp != ""){
	$query_n = $query_n . $delApp;
}
if ($delUser != ""){
	$query_n = $query_n . $delUser;
}
*/
if ($ip_address != ""){
	if (($user != "")||($start_d !="")||($varN !="")||($end_d !="")||($appN !="")||($mot !="")||($accesstype != "")){
		$query_n = $query_n . 'AND ';
	}
	$query_n = $query_n . $ip_address;
}

if ($sourceRequest != ""){
	if (($user != "")||($start_d !="")||($varN !="")||($end_d !="")||($appN !="")||($mot !="")||($ip_address != "")||($accesstype != "")){
		$query_n = $query_n . 'AND ';
	}
	$query_n = $query_n . $sourceRequest;
}
/////  OTTENRERE IL TOTAL ROWS  //////
}

$total_rows_query = $query_n;
/////
$query_n = $query_n . "	ORDER BY ".$order." ".$by." LIMIT ".$start_from.", ".$limit.";";
 
//echo ($query_n);

$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);

//$query = "SELECT activity.* FROM profiledb.activity ORDER BY id DESC LIMIT ".$start_from.", ".$limit.";";
//$result = mysqli_query($link, $query) or die(mysqli_error($link));
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
	   <!-- Data Picker-->
	   <script type="text/javascript" src="js/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
       <link href="js/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	   <!-- DATA TABLE-->
	   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
	  <!-- -->
    </head>
	<body class="guiPageBody">
	<style>
		a {color: #337ab7;}
		table { table-layout:auto; width: 100%}
		input {width: 100%;}
		#mainContentCnt {height:100%}
		td {  overflow: hidden;   text-overflow: ellipsis;  word-wrap: break-word;}
		.ellipsis {
			text-overflow: ellipsis;
			overflow: hidden;
			white-space: nowrap;
			max-width: 100px;
		}
		body {
			margin: 0
			}
			
		a[title]:hover:after {
			content: attr(title);
			position: absolute;
		}
		
		
	</style>
	<style>
	.tooltip-inner {
		word-break: break-all;
		 max-width: 100%;
		 
			}
	

	</style>
	<style>
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
					<?php 
					if ($hide_menu != "hide"){
							include "mainMenu.php"; 
							echo ('<div class="col-xs-12 col-md-10" id="mainCnt">');
						}else{
										echo('<div class="col-xs-12 col-md-12" id="mainCnt">');
										//echo ('<style>#contenitore_table {margin-left: 5%;margin-right: 5%;width: 100%}</style>');
										
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
                    </div>');
				}
					?>
                    <div class="row" id="contenitore_table">
					<div class="col col-lg-2 panel-group" style="margin-top:25px">
						<button id="reset" class="btn cancelBtn" type="reset" value="">Reset Filters</button>
						</div>
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1);'>
					
				<div>
				<select name="limit" id="limit_select">
				<!--<option value=""></option>-->
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="15">15</option>
				<!--<option value="50">50</option>-->
				</select>
				</div>
                <table id="DataTypes" class="table table-striped table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
						<thead class="dashboardsTableHeader">
						
						<?php
						if ($by == 'DESC'){
						$by_par = 'ASC';
						
						$icon_by = '<i class="fa fa-caret-down" style="color:white"></i>';
					}else{
						$by_par = 'DESC';
						$icon_by = '<i class="fa fa fa-caret-up" style="color:white"></i>';
					}
									/*if ($by == 'DESC'){
									$by_par = 'ASC';
									$icon_by = '<i class="fa fa fa-caret-up" style="color:white"></i>';
								}else if ($by == 'ASC'){
									$by_par = 'DESC';
									$icon_by = '<i class="fa fa-caret-down" style="color:white"></i>';
								}else{
									$by_par = '';
									$icon_by = '';
									}*/
						echo ('
						<tr><th class="id"><a href="'.$pagina_attuale.'&orderBy=Id&order='.$by_par.'">Id '.$icon_by.'</a></th>
							<th class="time"><div><a href="'.$pagina_attuale.'&orderBy=time&order='.$by_par.'">Date and Time '.$icon_by.'</a></div><div><input id="start" style="color: black; width:auto;" type="text" name="start" value="" class="datepicker titleCol" placeholder="From..."><input id="end" type="text" name="end" style="color: black; width:auto;" value="" class="datepicker titleCol" placeholder="To..." ></div><div><button id="filter" type="button" class="filter">Search</button></div></th>
							<th class="username"><div><a href="'.$pagina_attuale.'&orderBy=username&order='.$by_par.'">Username '.$icon_by.'</a></div><div class="input-group mb-3"><input  class="titleCol" type="text" style="color: black;"   id="filterUser" placeholder="Search..." /></div><div><button id="filterUserBtn" type="button" class="filter">Search</button></div></th>
							<th class="app_name"><div><a href="'.$pagina_attuale.'&orderBy=app_name&order='.$by_par.'">App Name '.$icon_by.'</a></div><div class="input-group mb-3"><input class="titleCol" type="text" style="color: black;"  id="filterAppName" placeholder="Search..." /></div><div><button id="filterAppN" type="button" class="filter">Search</button></div></th>
							<th class="source_request"><div><a href="'.$pagina_attuale.'&orderBy=source_request&order='.$by_par.'">Source request '.$icon_by.'</a></div><div class="input-group mb-3"><select name="sourceReq" class="selectpicker form-control titleCol" id="sourceReq" style="color: black; width:auto;"><option value=""></option><option value="dashboardwizard">dashboardwizard</option><option value="dashboardmanager">dashboardmanager</option><option value="iotapp">iotapp</option></select><button id="filterSource" type="button" class="filter">Search</button></div></th>
							<th class="variable_name"><div><a href="'.$pagina_attuale.'&orderBy=variable_name&order='.$by_par.'">Variable name '.$icon_by.'</a></div><div class="input-group mb-3"><input type="text" style="color: black;"  class="titleCol"  id="filterVarN" placeholder="Search..." /></div><div><button id="filterVarNBtn" type="button" class="filter">Search</button></div></th>
							<th class="motivation"><div><a href="'.$pagina_attuale.'&orderBy=motivation&order='.$by_par.'">Motivation '.$icon_by.'</a></div><div class="input-group mb-3"><input type="text" style="color: black;"  id="filterMot" placeholder="Search..." /></div><div><button id="filterMotBtn" type="button" class="filter">Search</button></div></th>
							<th class="access_type"><div><a href="'.$pagina_attuale.'&orderBy=access_type&order='.$by_par.'">Access Type '.$icon_by.'</a></div><select name="AccessType" class="selectpicker form-control titleCol" id="AccessType" style="color: black; width:auto;"><option value=""></option><option value="READ">READ</option><option value="WRITE">WRITE</option></select><button id="filterAccType" type="button" class="filter">Search</button></th>
							<th class="query"><div><a href="'.$pagina_attuale.'&orderBy=query&order='.$by_par.'">Query '.$icon_by.'</a></div></th>
							<th class="error_message"><div><a href="'.$pagina_attuale.'&orderBy=error_message&order='.$by_par.'">Error Message '.$icon_by.'</a></div></th>
							<th class="stacktrace"><div><a href="'.$pagina_attuale.'&orderBy=stacktrace&order='.$by_par.'">Stacktrace '.$icon_by.'</a></div></th>
							<th class="ip_address"><div><a href="'.$pagina_attuale.'&orderBy=ip_address&order='.$by_par.'">ip_address '.$icon_by.'</a></div><div class="input-group mb-3"><input type="text" style="color: black;"  id="ip_address" placeholder="Search..." /></div><div><button id="filterMotBtn" type="button" class="filter">Search</button></div></th>
							<th class"delete_time"><div><a href="'.$pagina_attuale.'&orderBy=ip_address&order='.$by_par.'">delete_time '.$icon_by.'</a></div></th></tr>
							');
							?>
							
						</thead>	
					<tbody>
					<?php 
							for ($i = 0; $i <= $num_rows; $i++) {
								//primi 500 caratteri 
								if (strlen($process_list[$i]['query'])>15){
								$sub_string = substr($process_list[$i]['query'], 0, 15);
								$query_mes= "<a href='#' class='pop_link ellipsis' data-toggle='popover' title='' data-content='".$process_list[$i]['query']."'>".$sub_string."</a>";
								}else{
								$sub_string = $process_list[$i]['query'];
								$query_mes= "<a class='pop_link ellipsis'>".$process_list[$i]['query']."</a>";								
								}
								//$sub_string = $sub_string.'...';
								if (strlen($process_list[$i]['stacktrace'])>15){
								$sub_string_strack = substr($process_list[$i]['stacktrace'], 0, 15);
								}else{
								$sub_string_strack = $process_list[$i]['stacktrace'];	
								}
								
								if (strlen($process_list[$i]['error_message'])>25){
								$sub_string_mess = substr($process_list[$i]['error_message'], 0, 25);
								$message = "<a href='#' class='pop_link ellipsis' data-toggle='popover' title='' data-content='".$process_list[$i]['error_message']."'>".$sub_string_mess."</a>";
								}else{
								//$sub_string_mess = 	$process_list[$i]['error_message'];
								$message = "<a class='pop_link ellipsis'>".$process_list[$i]['error_message']."</a>";
								}
								//$sub_string_strack = $sub_string_strack.'...';

							//echo ("<tr><td>".$process_list[$i]['id']."</td><td>".$process_list[$i]['time']."</td><td>".$process_list[$i]['username']."</td><td>".$process_list[$i]['app_name']."</td><td>".$process_list[$i]['source_request']."</td><td>".$process_list[$i]['variable_name']."</td><td>".$process_list[$i]['motivation']."</td><td>".$process_list[$i]['access_type']."</td><td><p class='ellipsis' title='".$process_list[$i]['query']."'>".$process_list[$i]['query']."</p></td><td><p class='ellipsis' title='".$process_list[$i]['error_message']."'>".$process_list[$i]['error_message']."</p></td><td><p class='ellipsis' title='".$process_list[$i]['stacktrace']."'>".$process_list[$i]['stacktrace']."<p></td><td>".$process_list[$i]['ip_address']."</td><td>".$process_list['i']['delete_time']."</td></tr>");
							echo ("<tr>
									<td>".$process_list[$i]['id']."</td>
									<td>".$process_list[$i]['time']."</td>
									<td>".$process_list[$i]['username']."</td>
									<td>".$process_list[$i]['app_name']."</td>
									<td>".$process_list[$i]['source_request']."</td>
									<td>".$process_list[$i]['variable_name']."</td>
									<td>".$process_list[$i]['motivation']."</td>
									<td>".$process_list[$i]['access_type']."</td>
									<!--<td><a href='#' class='pop_link ellipsis' data-toggle='popover' title='' data-content='".$process_list[$i]['query']."'>".$sub_string."</a></td>-->
									<td>".$query_mes."</td>
									<!--<td><a href='#' class='pop_link ellipsis' data-toggle='popover' title='' data-content='".$process_list[$i]['error_message']."'>".$sub_string_mess."</a></td>-->
									<td>".$message."</td>
									<td><a href='#' class='pop_link ellipsis' data-toggle='popover' title='' data-content='".$process_list[$i]['stacktrace']."'>".$sub_string_strack."</a></td>
									<td>".$process_list[$i]['ip_address']."</td><td>".$process_list['i']['delete_time']."</td>
									</tr>");
							}
					?>	
					</tbody>
					
                </table>
						<?php 
							$total_records = $total_rows;
							$total_pages = ceil($total_records / $limit);
								$prev_page = $_GET["page"] -1;
								$suc_page = $_GET["page"] +1;
								$corr_page= $_GET["page"];
								///Controlli se i paramteri sono attivi////
								////
							if ($prev_page >=1){
							//echo ('<div class="pagination" value="'.$prev_page.'"><a href="auditPersonalData.php?page='.$prev_page.'"><<	Prev</a>&#09;</div>	');
							//echo ('<div class="pagination" value="'.$prev_page.'"><a href="auditPersonalData.php?page='.$prev_page.'"><<	Prev</a>&#09;</div>	');
							echo ('	<div class="pagination" value="'.$prev_page.'">&#09;<a href="audit_violation.php?mot='.$_GET['mot'].'&appName='.$_GET['appName'].'&user='.$_GET['user'].'&start='.$_GET['start'].'&end='.$_GET['end'].'&varN='.$_GET["varN"].'&sourceRequest='.$_GET['sourceRequest'].'&accesstype='.$_GET['accesstype'].'&ip_address='.$_GET['ip_address'].'&page='.$prev_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'"><< 	Prev</a></div>');
							}
							//$corr_page
										if ($corr_page >11){
										$init_j = $corr_page -10;
										}else{$init_j = 1; 
										}
							//for ($j=1; $j<=$total_pages; $j++) { 
							for ($j=$init_j; $j<=$total_pages; $j++) { 
											//echo ("&#09;<a href='auditPersonalData.php?page=".$j."'>".$j."</a>&#09;");
											//if (($j<11)||(($corr_page == $j)&&($corr_page < $total_pages-3))){
												//$lim = $corr_page -10;
											//if (($j<11)||(($corr_page == $j)&&($corr_page < $total_pages-3))){
												if (($j<11)||(($corr_page-$j)>=0)||(($corr_page == $j)&&($corr_page < $total_pages-3))||(($corr_page >= $total_pages-3))){
											echo ("&#09;<a class='page_n' value='".$j."' href='audit_violation.php?mot=".$_GET['mot']."&appName=".$_GET['appName']."&user=".$_GET['user']."&start=".$_GET['start']."&end=".$_GET['end']."&varN=".$_GET['varN']."&sourceRequest=".$_GET['sourceRequest']."&accesstype=".$_GET['accesstype']."&ip_address=".$_GET['ip_address']."&page=".$j."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$j."</a>&#09;");
												}
											// }else{echo(" ");}
											 //echo ("&#09;<a href='auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&accesstype='+AccessType+'&domain='+optDomain+"&page=".$j.">".$j."</a>&#09;");
							}; 
							
							$last_pages = $total_pages-3;
							if (($total_pages > 13)&&($corr_page < $last_pages)){
							echo ("...");
							for ($y=$last_pages; $y<=$total_pages; $y++) {  
											//echo ("&#09;<a href='auditPersonalData.php?page=".$j."'>".$j."</a>&#09;");
											echo ("&#09;<a class='page_n' value='".$y."' href='audit_violation.php?mot=".$_GET['mot']."&appName=".$_GET['appName']."&user=".$_GET['user']."&start=".$_GET['start']."&end=".$_GET['end']."&varN=".$_GET['varN']."&sourceRequest=".$_GET['sourceRequest']."&accesstype=".$_GET['accesstype']."&ip_address=".$_GET['ip_address']."&page=".$y."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$y."</a>&#09;");	
											 //echo ("&#09;<a href='auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&accesstype='+AccessType+'&domain='+optDomain+"&page=".$j.">".$j."</a>&#09;");
										};
									}
							if ($suc_page <=$total_pages){
							//echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="auditPersonalData.php?page='.$suc_page.'">Next 	>></a></div>');
							echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="audit_violation.php?mot='.$_GET['mot'].'&appName='.$_GET['appName'].'&user='.$_GET['user'].'&start='.$_GET['start'].'&end='.$_GET['end'].'&varN='.$_GET["varN"].'&sourceRequest='.$_GET['sourceRequest'].'&accesstype='.$_GET['accesstype'].'&ip_address='.$_GET['ip_address'].'&page='.$suc_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'">Next 	>></a></div>');
							}
						?>
						<?php 
				if ($num_rows == 0){
					echo ('<div class="panel panel-default"><div class="panel-body">There are no results for this search</div></div>');
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
			var Paramter_end = "";
			
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
		$(document).ready(function () {
		//$('#storico').dynatable();
		var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#contenitore_table').css("width","100%");
			//$('#mainContentCnt').css("margin","auto auto");
			//$('#contenitore_table').css("margin-left","5%");
			//$('#contenitore_table').css("margin-right","5%");
			//$('#DataTypes').css("font-size","0.95em");
			$('.ellipsis').css("max-width","100px");
			$('td').css("overflow","visible");
			//td {  overflow: hidden;   text-overflow: ellipsis;  word-wrap: break-word;}
		}
		
		//popover
		$('[data-toggle="popover"]').popover({placement: "left", html: 'true'}); 
;
		
		//
		var pagina_corrente="<?=$corr_page; ?>";
		//trovare il link con value uguale alla pagina e colorarlo di bianco.
		//$("a.page_n[value='"+pagina_corrente+"']").css("color","white");
		$("a.page_n[value='"+pagina_corrente+"']").css("text-decoration","underline");
		//
		
		var limit_default= "<?=$limit; ?>";
		console.log(limit_default);
		$('#limit_select').val(limit_default);
		///Mostrare valori della ricerca precedente
		/////DATE PICKER /////
		$(function(){
			//var dateNow = new Date();
			$('.datepicker').datetimepicker({
				format: 'yyyy-mm-dd hh:ii:ss',
				use24hours: true
			});
		});
		
		var user_lab= "<?=$user_lab; ?>";
		var appN_lab ="<?=$appN_lab; ?>";
		var varN_lab ="<?=$varN_lab; ?>";
		var mot_lab ="<?=$mot_lab; ?>";
		var accessType_lab="<?=$accessType_lab; ?>";
		var start_lab="<?=$start_lab; ?>";
		var end_lab="<?=$end_lab; ?>";
		var sourceRequest_lab="<?=$sourceRequest_lab; ?>";
		var ip_address_lab="<?=$ip_address_lab; ?>";
		$('#filterUser').val(user_lab);
		$('#filterAppName').val(appN_lab);
		$('#AccessType').val(accessType_lab);
		$("#start").val(start_lab);
		$("#end").val(end_lab);
		$("#sourceReq").val(sourceRequest_lab);
		$("#filterMot").val(mot_lab);
		$("#filterVarN").val(varN_lab);
		$("#ip_address").val(ip_address_lab);
		
		var role_active = $("#role_att").text();
	//redirect
		var role="<?=$role_att; ?>";
	/*
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
		*/
		//
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
		
        //Versione client side ma ben funzionanete del caricamento
		var array_act = [];
		
		
		$(document).on('click','#reset',function(){
			var user = $('#filterUser').val();
			var start = $("#start").val();
			var end = $("#end").val();
			var $appN = $('#filterAppName').val();
			var filterVarN= $('#filterVarN').val();
			var filterMot= $('#filterMot').val();
			var AccessType = $('#AccessType').val();
			var sourceReq = $('#sourceReq').val();
			var ip_address = $("#ip_address").val();
			//var optDomain = $('#optDomain').val();
			//var filterDelAp = $('#filterDelAp').val();
			//var filterDelUs = $('#filterDelUs').val();
			var href = document.location.href;
			var lastPathSegment = href.substr(href.lastIndexOf('/') + 1);
			if((window.self !== window.top)||(nascondi == 'hide')){
			window.location.href = "audit_violation.php?showFrame=false&mot=&appName=&user=&start=&end=&varN=&sourceRequest=&accesstype=&page=1";
			//window.location.href = 'auditPersonalData.php?showFrame=false&mot=&appName=&user=&start=&end=varN=&sourceRequest=&accesstype=&domain=&delApp=&delUser=&page=1';			
			}
			else{
			window.location.href = "audit_violation.php?showFrame=true&mot=&appName=&user=&start=&end=&varN=&sourceRequest=&accesstype=&page=1";
			//window.location.href = 'auditPersonalData.php?showFrame=true&mot=&appName=&user=&start=&end=varN=&sourceRequest=&accesstype=&domain=&delApp=&delUser=&page=1';	
			}
		});
		
		//Unico Button///
		$(document).on('click','.filter',function(){
			var limit_val = $('#limit_select').val();
			var user = $('#filterUser').val();
			var start = $("#start").val();
			var end = $("#end").val();
			var $appN = $('#filterAppName').val();
			var filterVarN= $('#filterVarN').val();
			var filterMot= $('#filterMot').val();
			var AccessType = $('#AccessType').val();
			var sourceReq = $('#sourceReq').val();
			var ip_address = $("#ip_address").val();
			//var optDomain = $('#optDomain').val();
			//var filterDelAp = $('#filterDelAp').val();
			//var filterDelUs = $('#filterDelUs').val();
			if((window.self !== window.top)||(nascondi == 'hide')){	
						//window.location.href = 'auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
							window.location.href = 'audit_violation.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&ip_address='+ip_address+'&accesstype='+AccessType+'&limit='+limit_val+'&page=1';						
						}
						else{
						//window.location.href = 'auditPersonalData.php?mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
						window.location.href = 'audit_violation.php?showFrame=true&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&ip_address='+ip_address+'&accesstype='+AccessType+'&limit='+limit_val+'&page=1';	
					}
		});
		
		$('#limit_select').change(function() {
			var limit_val = $('#limit_select').val();
			var user = $('#filterUser').val();
			var start = $("#start").val();
			var end = $("#end").val();
			var $appN = $('#filterAppName').val();
			var filterVarN= $('#filterVarN').val();
			var filterMot= $('#filterMot').val();
			var AccessType = $('#AccessType').val();
			var sourceReq = $('#sourceReq').val()
			var ip_address = $("#ip_address").val();
			//var optDomain = $('#optDomain').val();
			//var filterDelAp = $('#filterDelAp').val();
			//var filterDelUs = $('#filterDelUs').val();
			if((window.self !== window.top)||(nascondi == 'hide')){	
						//window.location.href = 'auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
							window.location.href = 'audit_violation.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&ip_address='+ip_address+'&accesstype='+AccessType+'&limit='+limit_val+'&page=1';						
						}
						else{
						//window.location.href = 'auditPersonalData.php?mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
						window.location.href = 'audit_violation.php?showFrame=true&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&ip_address='+ip_address+'&accesstype='+AccessType+'&limit='+limit_val+'&page=1';	
					}
					//alert(limit_val);
		});
		
	});
</script>
</body>
</html>