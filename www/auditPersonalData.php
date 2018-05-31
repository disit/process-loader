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

if (isset ($_SESSION['username'])){
  $role_att = $_SESSION['role'];	
}else{
 $role_att= "";	
}

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
		//echo ('true');
		$hide_menu= "hide";
	}else{
		$hide_menu= "";
	}	
}else{$hide_menu= "";}


if (!isset($_GET['pageTitle'])){
	$default_title = "Audit Personal Data";
}else{
	$default_title = "";
}

if (isset($_GET['user'])||$_GET['user'] !==""){
	//$user = $_GET['user'];
	$user_lab = $_GET['user'];
	$user_trim = str_replace(' ','',$user_lab); 
	//$user = "username LIKE '%".$_GET['user']."%' ";
	$user = "username LIKE '%".$user_trim."%' ";
}else{
	//$user = "username LIKE '%%'	";
	$user = "";
}
if ($_GET['user'] ==""){
	//$user = "username LIKE '%%' ";
	$user = "";
	$user_lab ="";
}

if (isset($_GET['appName'])||$_GET['appName'] !==""){
	$appN_lab = $_GET['appName'];
	$appN_trim = str_replace(' ','',$appN_lab); 
	$appN = " app_name LIKE '%".$appN_trim."%'	";
	//$appN = " app_name LIKE '%".$_GET['appName']."%'	";
}else{
	$appN = "";
}
if ($_GET['appName'] == ""){
	$appN = "";
	$appN_lab = "";
}

if(isset($_GET['accesstype'])||$_GET['accesstype'] !==""){
	$accessType_lab=$_GET['accesstype'];
	$accessType_lab_trim = str_replace(' ','',$accessType_lab); 
	$accesstype = " access_type ='".$accessType_lab_trim."'";
	//$accesstype = " access_type ='".$_GET['accesstype']."'";
}else{
	$accesstype = "";
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
}

if ($_GET['sourceRequest'] ==""){
	$sourceRequest = "";
	$sourceRequest_lab = "";
}

if (isset($_GET['varN'])||$_GET['varN'] !==""){
	$varN_lab = $_GET['varN'];
	$varN_lab_trim = str_replace(' ','',$varN_lab); 
	//$varN = " variable_name LIKE'%".$_GET['varN']."%'	";
	$varN = " variable_name LIKE'%".$varN_lab_trim."%'	";
}else{
	$varN = "";
	$varN_lab = "";
}

if(isset($_GET['domain'])||$_GET['domain'] !==""){
	$domain= "domain='".$_GET['domain']."'	";
	$domain_lab=$_GET['domain'];
}else{
	$domain= "";
	$domain_lab="";
}

if ($_GET['domain'] == ""){
	$domain = "";
	$domain_lab="";
}

if ($_GET['varN'] == ""){
	$varN = "";
	$varN_lab = "";
}

if (isset($_GET['mot'])||$_GET['mot'] !==""){
	$mot_lab = $_GET['mot'];
	$mot = "motivation LIKE '%".$_GET['mot']."%'	";
}else{
	$mot = "";
	$mot_lab ="";
}

if ($_GET['mot'] == ""){
	$mot = "";
	$mot_lab="";
}

if (isset($_GET['delUser'])||$_GET['delUser'] !==""){
	//$delUser = " delegated_username LIKE '%".$_GET['delUser']."%'	";
	$delUser_lab = $_GET['delUser'];
	$delUser_trim = str_replace(' ','',$delUser_lab); 
	$delUser = " delegated_username LIKE '%".$delUser_trim."%'	";
}else{
	$delUser = "";
	$delUser_lab ="";
}

if (isset($_GET['delApp'])||$_GET['delApp'] !==""){
	$delApp_lab=$_GET['delApp'];
	$delApp_trim = str_replace(' ','',$delApp_lab); 
	$delApp = " delegated_app_name LIKE '%".$delApp_trim."%'	";
	
}else{
	$delApp = "";
	$delApp_lab="";
}

if ($_GET['delUser'] ==""){
	$delUser = "";
	$delUser_lab = "";
}

if ($_GET['delApp'] ==""){
	$delApp = "";
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
	$start_lab = $_GET["start"];
	$start_d = "time>='". $_GET["start"]."'	";
}else{
	$start_d = "";
	$start_lab ="";
}

if ($_GET["start"] == ""){
	$start_d ="";
	$start_lab ="";
}

if (isset($_GET["end"])|| $_GET["end"] !=""){
	$end_d ="time<='".$_GET["end"]."'";
	$end_lab=$_GET['end'];
}else{
	$end_d = "";
}
if ($_GET["end"] == ""){
	$end_d ="";
	$end_lab="";
}

$query_n = "SELECT activity.* FROM profiledb.activity";
//Controlla//
//if (isset($_GET['user'])||isset($_GET['appName'])||isset($_GET['varN'])||isset($_GET['mot'])||isset($_GET["start"])||isset($_GET["end"])){
if (($user_lab !="")||($appN_lab != "")||($varN_lab !="")||($mot_lab !="")||($start_lab !="")||($end_lab !="")||($domain_lab!="")||($accessType_lab!="")||($sourceRequest_lab!="")||($delApp_lab!="")||($delUser_lab !="")){
	//$query_n = $query_n . "WHERE activity.id >0 ";
	$query_n = $query_n . "	WHERE ";
///
if ($user != ""){
	$query_n = $query_n . $user;
}
if ($start_d !=""){
	if ($user != ""){
	$query_n = $query_n . 'AND	';	
	}
	$query_n = $query_n . $start_d;
}
if ($end_d !=""){
	if (($user != "")||($start_d !="")){
	$query_n = $query_n . 'AND	';	
	}
	$query_n = $query_n . $end_d;
}
if ($varN !=""){
	if (($user != "")||($start_d !="")||($end_d !="")){
	$query_n = $query_n . 'AND	';	
	}
	$query_n = $query_n . $varN;
}
if ($appN !=""){
	if (($user != "")||($start_d !="")||($end_d !="")||($varN !="")){
	$query_n = $query_n . 'AND	';	
	}
	$query_n = $query_n . $appN;
}
if ($mot !=""){
	if (($user != "")||($start_d !="")||($end_d !="")||($appN !="")||($varN !="")){
	$query_n = $query_n . 'AND	';	
	}
	$query_n = $query_n . $mot;
}
if ($domain !=""){
	if (($user != "")||($start_d !="")||($end_d !="")||($appN !="")||($varN !="")||($mot !="")){
	$query_n = $query_n . 'AND	';	
	}
	$query_n = $query_n . $domain;
}
if ($accesstype != ""){
	if (($user != "")||($start_d !="")||($end_d !="")||($appN !="")||($varN !="")||($mot !="")||($domain !="")){
	$query_n = $query_n . 'AND	';	
	}
	$query_n = $query_n . $accesstype;
}
if ($delApp != ""){
	if (($user != "")||($start_d !="")||($end_d !="")||($appN !="")||($varN !="")||($mot !="")||($domain !="")||($accesstype != "")){
	$query_n = $query_n . 'AND	';	
	}
	$query_n = $query_n . $delApp;
}
if ($delUser != ""){
	if (($user != "")||($start_d !="")||($end_d !="")||($appN !="")||($varN !="")||($mot !="")||($domain !="")||($delApp != "")||($accesstype != "")){
	$query_n = $query_n . 'AND	';	
	}
	$query_n = $query_n . $delUser;
}

if ($sourceRequest != ""){
	if (($user != "")||($start_d !="")||($end_d !="")||($appN !="")||($varN !="")||($mot !="")||($domain !="")||($delUser != "")||($delApp != "")||($accesstype != "")){
	$query_n = $query_n . 'AND	';	
	}
	$query_n = $query_n . $sourceRequest;
}
/////  OTTENRERE IL TOTAL ROWS  //////
}

$total_rows_query = $query_n;
/////
$query_n = $query_n . "	ORDER BY ".$order." ".$by." LIMIT ".$start_from.", ".$limit.";";
//echo ($query_n);
///
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);

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
	table { table-layout:auto;}
	#mainContentCnt {height:100%}
	input {width: 100%;}
	td { 
    overflow: hidden; 
    text-overflow: ellipsis; 
    word-wrap: break-word;
}
	</style>
	<style>
	.tooltip-inner {
		word-break: break-all;
		 max-width: 100%;
		 
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
					<div class="col col-lg-2 panel-group" style="margin-top:35px">
						<button id="reset" class="btn cancelBtn" type="reset" value="">Reset Filters</button>
						</div>
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:5px'>
					
				
				<div>
				<select name="limit" id="limit_select">
				<!--<option value=""></option>-->
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="15">15</option>
				<!--<option value="50">50</option>-->
				</select>
				</div>
                <table id="DataTypes" class="table table-striped table-bordered">
						<thead class="dashboardsTableHeader">
						<?php 
						if ($by == 'DESC'){
						$by_par = 'ASC';
						
						$icon_by = '<i class="fa fa-caret-down" style="color:white"></i>';
					}else if ($by == 'ASC'){
						$by_par = 'DESC';
						$icon_by = '<i class="fa fa fa-caret-up" style="color:white"></i>';
					} else {
						$icon_by = "";
					}
						echo ('
						<tr>
							<th class="id"><div><a href="'.$pagina_attuale.'&orderBy=id&order='.$by_par.'">Id '.$icon_by.'</a></div></th>
							<th class="time"><div><a href="'.$pagina_attuale.'&orderBy=time&order='.$by_par.'">Date and Time '.$icon_by.'</a></div><div><input id="start" style="color: black; width:auto"  type="text" name="start" value="" class="datepicker" placeholder="From..."><input id="end" type="text" name="end" style="color: black; width:auto;" value="" class="datepicker" placeholder="To..."></div><button id="filter" type="button" class="filter">Search</button></th>
							<th class="username"><div><a href="'.$pagina_attuale.'&orderBy=username&order='.$by_par.'">Username '.$icon_by.'</a></div><div class="input-group mb-3"><input type="text" style="color: black;" size="18"  id="filterUser" placeholder="Search..." /></div><div><button id="filterUserBtn" type="button" class="filter">Search</button></div></th>
							<th class="app_name"><div><a href="'.$pagina_attuale.'&orderBy=app_name&order='.$by_par.'">App Name '.$icon_by.'</a></div><div class="input-group mb-3"><input type="text" style="color: black;" size="18" id="filterAppName" placeholder="Search..." /></div><div><button id="filterAppN" type="button" class="filter">Search</button></div></th>
							<th class="delegated_username"><div><a href="'.$pagina_attuale.'&orderBy=delegated_username&order='.$by_par.'">Delegated Username '.$icon_by.'</a></div><div class="input-group mb-3"><input type="text" style="color: black;" size="18"  id="filterDelUs" placeholder="Search..." /></div><div><button id="filterDelUs" type="button" class="filter">Search</button></div></th>
							<th class="delegated_app_name"><div><a href="'.$pagina_attuale.'&orderBy=delegated_app_name&order='.$by_par.'">Delegated AppName '.$icon_by.'</a></div><div class="input-group mb-3"><input type="text" style="color: black;" size="18"  id="filterDelAp" placeholder="Search..." /></div><div><button id="filterDelApp" type="button" class="filter">Search</button></div></th>
							<th class="source_request"><div><a href="'.$pagina_attuale.'&orderBy=source_request&order='.$by_par.'">Source request '.$icon_by.'</a></div><select name="sourceReq" class="selectpicker form-control" id="sourceReq" style="color: black; width:auto;"><option value=""></option><option value="dashboardwizard">dashboardwizard</option><option value="dashboardmanager">dashboardmanager</option><option value="iotapp">iotapp</option></select><button id="filterSource" type="button" class="filter">Search</button></th>
							<th class="variable_name"><div><a href="'.$pagina_attuale.'&orderBy=variable_name&order='.$by_par.'">Variable name '.$icon_by.'</a></div><div class="input-group mb-3"><input type="text" style="color: black;" size="18"  id="filterVarN" placeholder="Search..." /></div><div><button id="filterVarNBtn" type="button" class="filter">Search</button></div></th>
							<th class="motivation"><div><a href="'.$pagina_attuale.'&orderBy=motivation&order='.$by_par.'">Motivation '.$icon_by.'</a></div><div class="input-group mb-3"><input type="text" style="color: black;" size="18"  id="filterMot" placeholder="Search..." /></div><div><button id="filterMotBtn" type="button" class="filter">Search</button></div></th>
							<th class="access_type"><div><a href="'.$pagina_attuale.'&orderBy=access_type&order='.$by_par.'">Access Type '.$icon_by.'</a></div><select name="AccessType" class="selectpicker form-control" id="AccessType" style="color: black; width:auto;"><option value=""></option><option value="READ">READ</option><option value="WRITE">WRITE</option></select><button id="filterAccType" type="button" class="filter">Search</button></th>
							<th class="domain"><div><a href="'.$pagina_attuale.'&orderBy=domain&order='.$by_par.'">Domain '.$icon_by.'</a></div><select name="optDomain" class="selectpicker form-control" id="optDomain" style="color: black; width:auto;"><option value=""></option><option value="DELEGATION">DELEGATION</option><option value="DATA">DATA</option></select><button id="filterDomain" type="button" class="filter">Search</button></th>	
						</tr>');
						?>
						</thead>	
					<tbody>
					<?php 
							for ($i = 0; $i <= $num_rows; $i++) {
							
							echo ("<tr>
									<td>".$process_list[$i]['id']."</td>
									<td>".$process_list[$i]['time']."</td>
									<td>".$process_list[$i]['username']."</td>
									<td>".$process_list[$i]['app_name']."</td>
									<td>".$process_list[$i]['delegated_username']."</td>
									<td>".$process_list[$i]['delegated_app_name']."</td>
									<td>".$process_list[$i]['source_request']."</td>
									<td>".$process_list[$i]['variable_name']."</td>
									<td>".$process_list[$i]['motivation']."</td>
									<td>".$process_list[$i]['access_type']."</td>
									<td>".$process_list[$i]['domain']."</td>
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
								$array_link = array ();
								/////
							if ($prev_page >=1){
							echo ('	<div class="pagination" value="'.$prev_page.'">&#09;<a href="auditPersonalData.php?mot='.$_GET['mot'].'&appName='.$_GET['appName'].'&user='.$_GET['user'].'&start='.$_GET['start'].'&end='.$_GET['end'].'&varN='.$_GET["varN"].'&sourceRequest='.$_GET['sourceRequest'].'&accesstype='.$_GET['accesstype'].'&domain='.$_GET['domain'].'&delUser='.$_GET['delUser'].'&delApp='.$_GET['delApp'].'&page='.$prev_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'"><< 	Prev</a></div>');
							}
							//
							if ($corr_page >11){
										$init_j = $corr_page -10;
										}else{$init_j = 1; 
										}
							//
							//for ($j=1; $j<=$total_pages; $j++) {  
							for ($j=$init_j; $j<=$total_pages; $j++) { 
							//$mostra_pages = $corr_page + 9;
							//for ($j=$corr_page; $j<=$mostra_pages; $j++) {  
											/////
											//echo ("&#09;<a href='auditPersonalData.php?page=".$j."'>".$j."</a>&#09;");
										//if (($j<11)||(($corr_page == $j)&&($corr_page < $total_pages-3))){
										if (($j<11)||(($corr_page-$j)>=0)||(($corr_page == $j)&&($corr_page < $total_pages-3))||(($corr_page >= $total_pages-3))){
											echo ("&#09;<a class='page_n' value='".$j."' href='auditPersonalData.php?mot=".$_GET['mot']."&appName=".$_GET['appName']."&user=".$_GET['user']."&start=".$_GET['start']."&end=".$_GET['end']."&varN=".$_GET['varN']."&sourceRequest=".$_GET['sourceRequest']."&accesstype=".$_GET['accesstype']."&domain=".$_GET['domain']."&delApp=".$_GET['delApp']."&delUser=".$_GET['delUser']."&page=".$j."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$j."</a>&#09;");
										}else{echo(" ");}
											 //echo ("&#09;<a href='auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&accesstype='+AccessType+'&domain='+optDomain+"&page=".$j.">".$j."</a>&#09;");
							}; 
							//
							
									$last_pages = $total_pages-3;
									if (($total_pages > 13)&&($corr_page < $last_pages)){
										echo ("...");
										for ($y=$last_pages; $y<=$total_pages; $y++) {  
											//echo ("&#09;<a href='auditPersonalData.php?page=".$j."'>".$j."</a>&#09;");
											echo ("&#09;<a class='page_n' value='".$y."' href='auditPersonalData.php?mot=".$_GET['mot']."&appName=".$_GET['appName']."&user=".$_GET['user']."&start=".$_GET['start']."&end=".$_GET['end']."&varN=".$_GET['varN']."&sourceRequest=".$_GET['sourceRequest']."&accesstype=".$_GET['accesstype']."&page=".$y."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$y."</a>&#09;");	
											 //echo ("&#09;<a href='auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&accesstype='+AccessType+'&domain='+optDomain+"&page=".$j.">".$j."</a>&#09;");
										};
									}
							//
							if ($suc_page <=$total_pages){
							//echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="auditPersonalData.php?page='.$suc_page.'">Next 	>></a></div>');
									echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="auditPersonalData.php?mot='.$_GET['mot'].'&appName='.$_GET['appName'].'&user='.$_GET['user'].'&start='.$_GET['start'].'&end='.$_GET['end'].'&varN='.$_GET["varN"].'&sourceRequest='.$_GET['sourceRequest'].'&accesstype='.$_GET['accesstype'].'&domain='.$_GET['domain'].'&delApp='.$_GET['delApp'].'&delUser='.$_GET['delUser'].'&page='.$suc_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'">Next 	>></a></div>');
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
			$('.ellipsis').css("width","150 px");
			
		}
		
		var pagina_corrente="<?=$corr_page; ?>";
		//trovare il link con value uguale alla pagina e colorarlo di bianco.
		//$("a.page_n[value='"+pagina_corrente+"']").css("color","white");
		$("a.page_n[value='"+pagina_corrente+"']").css("text-decoration","underline");
		//
		
		///Mostrare valori della ricerca precedente
		/////DATE PICKER /////
		$(function(){
			//var dateNow = new Date();
			$('.datepicker').datetimepicker({
				format: 'yyyy-mm-dd hh:ii:ss',
				use24hours: true
			});
		});
		
		var limit_default= "<?=$limit; ?>";
		//console.log(limit_default);
		$('#limit_select').val(limit_default);
		var user_lab= "<?=$user_lab; ?>";
		var appN_lab ="<?=$appN_lab ?>";
		var accessType_lab="<?=$accessType_lab ?>";
		var start_lab="<?=$start_lab; ?>";
		var end_lab="<?=$end_lab; ?>";
		var delUser_lab="<?=$delUser_lab; ?>";
		var sourceRequest_lab="<?=$sourceRequest_lab; ?>";
		var domain_lab="<?=$domain_lab; ?>";
		var delApp_lab="<?=$delApp_lab; ?>";
		var filterMot="<?=$mot_lab; ?>";
		var sourceReq="<?=$sourceRequest_lab; ?>";
		var filterVarN="<?=$varN_lab; ?>";
		$('#filterUser').val(user_lab);
		$('#filterAppName').val(appN_lab);
		$('#AccessType').val(accessType_lab);
		$("#start").val(start_lab);
		$("#end").val(end_lab);
		$('#optDomain').val(domain_lab);
		$('#filterDelUs').val(delUser_lab);
		$('#filterDelAp').val(delApp_lab);
		$('#filterMot').val(filterMot);
		$('#sourceReq').val(sourceReq);
		$('#filterVarN').val(filterVarN);
		
		
		var role_active = $("#role_att").text();
	//redirect
		/*var role="<?=$role_att; ?>";
	
		if (role == ""){
			$(document).empty();
			//window.alert("You need to log in to access to this page!");
			if(window.self !== window.top){
			window.location.href = 'page.php?showFrame=false&pageTitle=Process%20Loader:%20View%20Resources';	
			}
			else{
			window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
			}
		}*/
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
			var optDomain = $('#optDomain').val();
			var filterDelAp = $('#filterDelAp').val();
			var filterDelUs = $('#filterDelUs').val();
			var href = document.location.href;
			var lastPathSegment = href.substr(href.lastIndexOf('/') + 1);
			if((window.self !== window.top)||(nascondi == 'hide')){
			window.location.href = "auditPersonalData.php?showFrame=false&mot=&appName=&user=&start=&end=&varN=&sourceRequest=&accesstype=&domain=&delApp=&delUser=&page=1";
			//window.location.href = 'auditPersonalData.php?showFrame=false&mot=&appName=&user=&start=&end=varN=&sourceRequest=&accesstype=&domain=&delApp=&delUser=&page=1';			
			}
			else{
			window.location.href = "auditPersonalData.php?showFrame=true&mot=&appName=&user=&start=&end=&varN=&sourceRequest=&accesstype=&domain=&delApp=&delUser=&page=1";
			//window.location.href = 'auditPersonalData.php?showFrame=true&mot=&appName=&user=&start=&end=varN=&sourceRequest=&accesstype=&domain=&delApp=&delUser=&page=1';	
			}
		});
		
		//Unico Button///
		$(document).on('click','.filter',function(){
			var user = $('#filterUser').val();
			var limit_val = $('#limit_select').val();
			var start = $("#start").val();
			var end = $("#end").val();
			var $appN = $('#filterAppName').val();
			var filterVarN= $('#filterVarN').val();
			var filterMot= $('#filterMot').val();
			var AccessType = $('#AccessType').val();
			var sourceReq = $('#sourceReq').val();
			var optDomain = $('#optDomain').val();
			var filterDelAp = $('#filterDelAp').val();
			var filterDelUs = $('#filterDelUs').val();
			if((window.self !== window.top)||(nascondi == 'hide')){	
						//window.location.href = 'auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
							window.location.href = 'auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&accesstype='+AccessType+'&domain='+optDomain+'&delApp='+filterDelAp+'&delUser='+filterDelUs+'&limit='+limit_val+'&page=1';						
						}
						else{
						//window.location.href = 'auditPersonalData.php?mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
						window.location.href = 'auditPersonalData.php?showFrame=true&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&accesstype='+AccessType+'&domain='+optDomain+'&delApp='+filterDelAp+'&delUser='+filterDelUs+'&limit='+limit_val+'&page=1';	
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
			var sourceReq = $('#sourceReq').val();
			var optDomain = $('#optDomain').val();
			var filterDelAp = $('#filterDelAp').val();
			var filterDelUs = $('#filterDelUs').val();
			if((window.self !== window.top)||(nascondi == 'hide')){	
						//window.location.href = 'auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
							window.location.href = 'auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&accesstype='+AccessType+'&domain='+optDomain+'&delApp='+filterDelAp+'&delUser='+filterDelUs+'&limit='+limit_val+'&page=1';						
						}
						else{
						//window.location.href = 'auditPersonalData.php?mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
						window.location.href = 'auditPersonalData.php?showFrame=true&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&accesstype='+AccessType+'&domain='+optDomain+'&delApp='+filterDelAp+'&delUser='+filterDelUs+'&limit='+limit_val+'&page=1';	
					}
					//alert(limit_val);
		});
		
	});
</script>
</body>
</html>