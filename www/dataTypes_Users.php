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
//include('control.php');
include ('external_service.php');
session_start();
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
		//echo ('true');
		$hide_menu= "hide";
	}else{
		$hide_menu= "";
	}	
}else{$hide_menu= "";}


if (!isset($_GET['pageTitle'])){
	$default_title = "DataTypes vs Users";
}else{
	$default_title = "";
}


$start_from = 0;
//$limit = 10;

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

if (isset($_GET["user"])){
	//$user = $_GET["user"];
	$user_lab=$_GET["user"];
	$user_lab_trim = str_replace(' ','',$user_lab);
	$user = "username LIKE '%".$user_lab_trim."%' ";
}else{
	$user_lab = "";
	$user = "username LIKE '%%'	";
	//$user = "";
	
}

if (isset($_GET['elementId'])){
	$element_id_lab=$_GET['elementId'];
	$element_id_lab_trim = str_replace(' ','',$element_id_lab);
	$element_id = " elementId LIKE '%".$element_id_lab_trim."%'	";
}else{
	$element_id_lab="";
	$element_id = "	elementId LIKE '%%'	";
}

if (isset($_GET['elementType'])){
	//$elementType="AND elementType LIKE '%".$_GET['elementType']."%'	";
	$elementType=" elementType='".$_GET['elementType']."' ";
	//$elementType=" elementType='DashboardID' ";
	$elementType_lab=$_GET['elementType'];
}else{
	//$elementType="	AND elementType LIKE '%%'	";
	$elementType="";
	$elementType_lab="";
}

if (isset($_GET['elementUrl'])){
	$elementUrl_lab=$_GET['elementUrl'];
	$elementUrl_lab_trim = str_replace(' ','',$elementUrl_lab);
	$elementUrl=" elementUrl LIKE '%".$elementUrl_lab_trim."%'	";
	
}else{
	$elementUrl="	elementUrl LIKE '%%'";
	//$elementUrl="";
	$elementUrl_lab="";
}
//
$query_n = "SELECT ownership.* FROM profiledb.ownership";
/////////////////

if (($user_lab != "")||($element_id_lab != "")||($elementType_lab !="")||($elementUrl_lab !="")){
	
	$query_n = $query_n . " WHERE ";

			if ($user_lab != ""){
				$query_n = $query_n . $user;
			}

			if ($element_id_lab != ""){
				//if c'e' qualcosa prima//
				if (($user_lab != "")){
					$query_n = $query_n . ' AND ';
				}
				//
				$query_n = $query_n . $element_id;
			}
			
			if ($elementType_lab != ""){
				//
				if (($user_lab != "")||($element_id_lab != "")){
					$query_n = $query_n . ' AND ';
				}
				//
				$query_n = $query_n . $elementType;
			}
			
			if ($elementUrl_lab != ""){
				//
				if (($user_lab != "")||($element_id_lab != "")||($elementType_lab != "")){
					$query_n = $query_n . ' AND ';
				}
				//
				$query_n = $query_n . $elementUrl;
			}

}

////////
$total_rows_query = $query_n;
$query_n = $query_n . "	ORDER BY ".$order." ".$by." LIMIT ".$start_from.", ".$limit.";";

//echo ($query_n);
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
	
/////SELECT DEI TIPI////
$query_tipi="SELECT distinct elementType FROM profiledb.ownership;";
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
$result_tipi = mysqli_query($link, $query_tipi) or die(mysqli_error($link));
$list_types = array();
$num_tipi = $result_tipi->num_rows;

    if ($result_tipi->num_rows > 0) 
	{
		while($row = mysqli_fetch_assoc($result_tipi))
		{
					if (($row !== "")||($row !== null)){
						array_push($list_types, $row);
					}
						//echo ($row);
				}
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
    </head>
	<body class="guiPageBody">
	<style>
	a { color: #337ab7;}
	td i {text-align:center;}
	table { table-layout:auto; width: 100%}
	
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
					}else{
						$by_par = 'DESC';
						$icon_by = '<i class="fa fa fa-caret-up" style="color:white"></i>';
					}
					//$list_types
					//$num_tipi
						echo ('					
                    <tr>
                        <th class="username"><div><a href="'.$pagina_attuale.'&orderBy=username&order='.$by_par.'">Username '.$icon_by .'</a></div><div class="input-group mb-3"><input type="text" style="color: black;" size="15"  id="filterUser" placeholder="Search..." /><button id="filterUserBtn" type="button" class="filter">Search</button></div></th>
                        <th class="elementId"><div><a href="'.$pagina_attuale.'&orderBy=elementId&order='.$by_par.'">Element ID '.$icon_by .'</a></div><div class="input-group mb-3"><input type="text" style="color: black;" size="18"  id="filterElId" placeholder="Search..." /><button id="filterElIdBtn" type="button" class="filter">Search</button></div></th>
						<th class="elementType"><div><a href="'.$pagina_attuale.'&orderBy=elementType&order='.$by_par.'">Element Type '.$icon_by .'</a></div><div class="input-group mb-3"></a><select class="selectpicker form-control titleCol" id="filterelementType"><option style="color: black; width:auto;"></option>');
						for ($z = 0; $z <= $num_tipi; $z++){
							if (($list_types[$z][elementType] != null)|| ($list_types[$z][elementType] != "")){
							echo ('<option style="color: black;">'.$list_types[$z][elementType].'</option>');
							}
						}
						//<input type="text" style="color: black;" size="18"  id="filterelementType" placeholder="Search..." />
					echo ('</select><button id="filterelementTypeBtn" type="button" class="filter">Search</button></div></th>
                        <th class="elementUrl"><div><a href="'.$pagina_attuale.'&orderBy=elementUrl&order='.$by_par.'">Element Url '.$icon_by .'</a></div><div class="input-group mb-3"><input type="text" style="color: black;" size="18"  id="filterelementUrl" placeholder="Search..." /><button id="filterelementUrlBtn" type="button" class="filter">Search</button></div></th>
						<!--<th class="lastCheck"><div><a href="'.$pagina_attuale.'&orderBy=lastCheck&order='.$by_par.'">LastCheck</a></div></th>-->
						<th class="healthiness"><div>Healthiness</div></th>
                    </tr>
					'); ?>
					</thead>	
					<tbody>
					<?php
					for ($i = 0; $i <= $num_rows; $i++) {	
					///
						if ($process_list[$i]['elementType'] == 'DashboardID'){
						$link ='https://main.snap4city.org/view/index.php?iddasboard='.base64_encode($process_list[$i]['elementId']);
						$link_el='<a href="'.$link.'">'.$link.'</a>';
							}else if ($process_list[$i]['elementType'] == 'IOTID'){
								//$link_el = $process_list[$i]['elementUrl'];
								$righe = explode("::",$process_list[$i]['elementId']);
								//$link = 'http://www.disit.org/km4city/resource/iot/'.$righe[0].'/'.$righe[1];
								$link = 'http://www.disit.org/km4city/resource/iot/orionUNIFI/'.$righe[0];
								$link_el='<a href="'.$link.'">'.$link.'</a>';
							}else if(($process_list[$i]['elementType'] == 'ServiceGraphID')||($process_list[$i]['elementType'] == 'ServiceURI')){
								$link = $process_list[$i]['elementId'];
								$link_el='<a href="'.$link.'">'.$link.'</a>';
							}else{
								$link = $process_list[$i]['elementUrl'];
								$link_el='<a href="'.$link.'">'.$link.'</a>';
							}
							
							/////API HEATHINESS
							if ($process_list[$i]['elementType'] == 'AppID'){
							$myUrl="http://".$snap4city_API."/snap4city-application-api/v1/?op=status&id=".$process_list[$i]['elementId']."&username=".$utente_att;
							$shortUrl=file_get_contents($myUrl);
							$encode= json_encode($shortUrl);
							$decode = json_decode($encode);
							//echo $decode['healthiness'];
							//if ($shortUrl=='{"healthiness":true}'){
							if ($decode['healthiness']== true){
											$healthiness='<i class="fa fa-circle mx-auto" style="font-size: 16px; color: green; text-align:center;"/>';
							}else if ($decode['healthiness']== false){
											 $healthiness='<i class="fa fa-circle mx-auto" style="font-size: 16px; color: red; text-align:center;"/>';
										 }else{
											  $healthiness='<i class="fa fa-circle mx-auto" style="font-size: 16px; color: blue; text-align:center;"/>';
										 }
								}else{
								$shortUrl ="";
								$healthiness='<i class="fa fa-circle mx-auto" style="font-size: 16px; color: blue; text-align:center;"/>';
										if ($process_list[$i]['elementType'] == 'IOTID'){
											$myUrl2="http://servicemap.disit.org/WebAppGrafo/api/v1/?serviceUri=".$link."&healthiness=true";
											$shortUrl2=file_get_contents($myUrl2);
											$encode2= json_encode($shortUrl2);
											$decode2 = json_decode($encode2);
											if ($decode2['healthiness']== true){
											$healthiness='<i class="fa fa-circle" style="font-size: 16px; color: green; text-align:center;"/>';
													}else if ($decode2['healthiness']== false){
											 $healthiness='<i class="fa fa-circle mx-auto" style="font-size: 16px; color: red; text-align:center;"/>';
												}else{
											  $healthiness='<i class="fa fa-circle mx-auto" style="font-size: 16px; color: blue; text-align:center;"/>';
												}	
										}else{
											 $healthiness='<i class="fa fa-circle mx-auto" style="font-size: 16px; color: blue; text-align:center;"/>';
										}
							}
							if ($process_list[$i]['username'] == ""){
								$healthiness="";
							}
					////
							echo ("<tr>
										<td>".$process_list[$i]['username']."</td>
									    <td>".$process_list[$i]['elementId']."</td>
										<td>".$process_list[$i]['elementType']."</td>
										<td>".$link_el."</td>
										<!--<td>".$process_list[$i]['lastCheck']."</td>-->
										<td class='mx-auto'><div style='text-align: center;'>".$healthiness."</div></td>
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
								///Controlli se i paramteri sono attivi////
								////
								$array_link = array ();
								/////
							if ($prev_page >=1){
							echo ('	<div class="pagination" value="'.$prev_page.'">&#09;<a href="dataTypes_Users.php?user='.$_GET['user'].'&elementId='.$_GET['elementId'].'&elementType='.$_GET['elementType'].'&elementUrl='.$_GET['elementUrl'].'&page='.$prev_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'"><< 	Prev</a></div>');
							}
							////
							//$corr_page
										if ($corr_page >11){
										$init_j = $corr_page -10;
										}else{$init_j = 1; 
										}
							////
							//for ($j=1; $j<=$total_pages; $j++) { 
							for ($j=$init_j; $j<=$total_pages; $j++) {  
							//$mostra_pages = $corr_page + 9;
							//for ($j=$corr_page; $j<=$mostra_pages; $j++) {  
											/////
											//echo ("&#09;<a href='auditPersonalData.php?page=".$j."'>".$j."</a>&#09;");
										//if (($j<11)||(($corr_page == $j)&&($corr_page < $total_pages-3))){
										if (($j<11)||(($corr_page-$j)>=0)||(($corr_page == $j)&&($corr_page < $total_pages-3))||(($corr_page >= $total_pages-3))){
											echo ("&#09;<a class='page_n' value='".$j."' href='dataTypes_Users.php?user=".$_GET['user']."&elementId=".$_GET['elementId']."&elementType=".$_GET['elementType'].'&elementUrl='.$_GET['elementUrl']."&page=".$j."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$j."</a>&#09;");
										}else{echo(" ");}
											 //echo ("&#09;<a href='auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&accesstype='+AccessType+'&domain='+optDomain+"&page=".$j.">".$j."</a>&#09;");
							}; 
							//
							
									$last_pages = $total_pages-3;
								if (($total_pages > 13)&&($corr_page < $last_pages)){
											echo ("...");
											for ($y=$last_pages; $y<=$total_pages; $y++) {  
												//echo ("&#09;<a href='auditPersonalData.php?page=".$j."'>".$j."</a>&#09;");
												echo ("&#09;<a class='page_n' value='".$y."' href='dataTypes_Users.php?user=".$_GET['user']."&elementId=".$_GET['elementId']."&elementType=".$_GET['elementType'].'&elementUrl='.$_GET['elementUrl']."&page=".$y."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$y."</a>&#09;");	
												 //echo ("&#09;<a href='auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&sourceRequest='+sourceReq+'&accesstype='+AccessType+'&domain='+optDomain+"&page=".$j.">".$j."</a>&#09;");
											};
									}
							//
							if ($suc_page <=$total_pages){
							//echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="auditPersonalData.php?page='.$suc_page.'">Next 	>></a></div>');
									echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="dataTypes_Users.php?user='.$_GET['user'].'&elementId='.$_GET['elementId'].'&elementType='.$_GET['elementType'].'&elementUrl='.$_GET['elementUrl'].'&page='.$suc_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'">Next 	>></a></div>');
							}
						?>
                 <!----->
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
<!-- Button-->
<!-- Trigger the modal with a button -->
<!-- FIne Button-->
<script type='text/javascript'>

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
		
		var limit_default= "<?=$limit; ?>";
		//console.log(limit_default);
		$('#limit_select').val(limit_default);
		var user_lab= "<?=$user_lab; ?>";
		$('#filterUser').val(user_lab);	
		var element_id_lab= "<?=$element_id_lab; ?>";
		$('#filterElId').val(element_id_lab);
		var elementType_lab="<?=$elementType_lab; ?>";
		$('#filterelementType').val(elementType_lab);
		var elementUrl_lab="<?=$elementUrl_lab; ?>";
		$('#filterelementUrl').val(elementUrl_lab);

var pagina_corrente="<?=$corr_page; ?>";
		//trovare il link con value uguale alla pagina e colorarlo di bianco.
		$("a.page_n[value='"+pagina_corrente+"']").css("text-decoration","underline");

		var role="<?=$role_att; ?>";
		var role_active = "<?=$process['functionalities'][$role]; ?>";
		if ((role_active ==0)){
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
		///////////////
		
		/////////////
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
		
        //Versione client side ma ben funzionanete del caricamento
		var array_act = [];
		
		$(document).on('click','#reset',function(){
			var user = $('#filterUser').val("");
			var filterElId = $('#filterElId').val("");
			var limit_val = $('#limit_select').val();
			var elementType = $('#filterelementType').val("");
			var elementUrl = $('#filterelementUrl').val("");
			var href = document.location.href;
			var lastPathSegment = href.substr(href.lastIndexOf('/') + 1);
			if((window.self !== window.top)||(nascondi == 'hide')){
			window.location.href = "dataTypes_Users.php?showFrame=false&page=1&limit=10";
			//window.location.href = 'auditPersonalData.php?showFrame=false&mot=&appName=&user=&start=&end=varN=&sourceRequest=&accesstype=&domain=&delApp=&delUser=&page=1';			
			}
			else{
			window.location.href = "dataTypes_Users.php?showFrame=true&page=1&limit=10";
			//window.location.href = 'auditPersonalData.php?showFrame=true&mot=&appName=&user=&start=&end=varN=&sourceRequest=&accesstype=&domain=&delApp=&delUser=&page=1';	
			}
		});
		
		//Unico Button///
		$(document).on('click','.filter',function(){
			var user = $('#filterUser').val();
			var filterElId = $('#filterElId').val();
			var limit_val = $('#limit_select').val();
			var elementType = $('#filterelementType').val();
			var elementUrl = $('#filterelementUrl').val();
			if((window.self !== window.top)||(nascondi == 'hide')){	
						//window.location.href = 'auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
							window.location.href = 'dataTypes_Users.php?showFrame=false&page=1&user='+user+'&elementId='+filterElId+'&elementType='+elementType+'&elementUrl='+elementUrl+'&limit='+limit_val;						
						}
						else{
						//window.location.href = 'auditPersonalData.php?mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
						window.location.href = 'dataTypes_Users.php?showFrame=true&page=1&user='+user+'&elementId='+filterElId+'&elementType='+elementType+'&elementUrl='+elementUrl+'&limit='+limit_val;	
					}
		});
		
		$('#limit_select').change(function() {
			var limit_val = $('#limit_select').val();
			var user = $('#filterUser').val();
			var filterElId = $('#filterElId').val();
			var elementType = $('#filterelementType').val();
			var elementUrl = $('#filterelementUrl').val();
			if((window.self !== window.top)||(nascondi == 'hide')){	
						//window.location.href = 'auditPersonalData.php?showFrame=false&mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
							window.location.href = 'dataTypes_Users.php?showFrame=false&page=1&user='+user+'&elementId='+filterElId+'&elementType='+elementType+'&elementUrl='+elementUrl+'&limit='+limit_val;						
						}
						else{
						//window.location.href = 'auditPersonalData.php?mot='+filterMot+'&appName='+$appN+'&user='+user+'&start='+start+'&end='+end+'&varN='+filterVarN+'&page=1';
						window.location.href = 'dataTypes_Users.php?showFrame=true&page=1&user='+user+'&elementId='+filterElId+'&elementType='+elementType+'&elementUrl='+elementUrl+'&limit='+limit_val;	
					}
					//alert(limit_val);
		});
});
</script>
</body>
</html>