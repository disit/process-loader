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
include("curl.php");

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
		$hide_menu= "hide";
	}else{
		$hide_menu= "";
	}	
}else{$hide_menu= "";}


if (!isset($_GET['pageTitle'])){
	$default_title = "Help Manager";
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
$query_n = "SELECT Help_manager.* FROM processloader_db.Help_manager";
//

///////PARAMETRI/////////////
if ((isset($_REQUEST['elementId']))||(isset($_REQUEST['elementUrl']))||(isset($_REQUEST['elementTool']))){
	$query_n = $query_n."	WHERE	id > 0";
		if (isset($_REQUEST['elementId'])&&($_REQUEST['elementId'] !="")&&($_REQUEST['elementId'] !=null)&&($_REQUEST['elementId'] !='undefined')){
			$query_n = $query_n." AND id LIKE '%".$_REQUEST['elementId']."%'";
		}
		////////////////////
		if (isset($_REQUEST['elementUrl'])&&($_REQUEST['elementUrl'] !="")&&($_REQUEST['elementUrl'] !=null)&&($_REQUEST['elementUrl'] !='undefined')){
			$query_n = $query_n." AND url LIKE '%".$_REQUEST['elementUrl']."%'";
		}
		//////////////////
		if (isset($_REQUEST['elementTool'])&&($_REQUEST['elementTool'] !="")&&($_REQUEST['elementTool'] !=null)&&($_REQUEST['elementTool'] !='undefined')){
			$query_n = $query_n." AND label LIKE '%".$_REQUEST['elementTool']."%'";
		}
		/////////////////
}
//////////
$total_rows_query = $query_n;

$query_n = $query_n . "	ORDER BY ".$order." ".$by." LIMIT ".$start_from.", ".$limit.";";
//
//echo ($query_n);
//
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
					<div class="col col-lg-2 panel-group" style="margin-top:35px; width:100%">
					<div class="btn-group" role="group" aria-label="Basic example">
					<button id="edit" class="btn btn-warning" data-toggle="modal"  type="reset" value="" data-target="#myModal">Add New Link</button>
					<button id="reset" class="btn btn-warning" type="reset" value="">Reset Filters</button>	
					</div>
					</div>
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:5px'>
					<!---->
					<?php include('functionalities.php'); 
					?>
					<!-- -->
						<div class="modal fade" id="myModal" role="dialog">
							<form class="change_ownership" id="add_modal" method="post" action="add_new_help.php">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Add new Help link:</h4></div>
													<div class="modal-body">
													<div class="row">
														<ul class="list-group">
															<li class="list-group-item"><b>Label:	</b><input type="text" name="tool" class="form-control" value=""></input></li>
															<li class="list-group-item"><b>Url:		</b><input type="text" class="form-control" name="url"></input></li>
															<li class="list-group-item"><b>Type:	</b><select class="selectpicker" name="type"><option value="popup">Popup</option><option value="page">Page</option></select></li>
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
							<form class="change_ownership" id="edit_modal" method="post" action="edit_help.php">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Modify existing link:</h4></div>
													<div class="modal-body">
													<div class="row">
														<ul class="list-group">
															<li class="list-group-item" style="display:none;"><b>Id:	 	</b><input type="text" name="id" id="label_id" class="form-control" value=""></input></li>
															<li class="list-group-item"><b>Label:	</b><input type="text" name="tool" id="label_name" class="form-control" value=""></input></li>
															<li class="list-group-item"><b>Url:		</b><input type="text" class="form-control" name="url" id="id_link"></input></li>
															<li class="list-group-item"><b>Type:	</b><select id="select_type" class="selectpicker" name="type"><option value="popup">Popup</option><option value="page">Page</option></select></li>
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
					<div class="modal fade" id="delete_link" role="dialog">
							<form class="change_ownership" id="delete_modal" method="post" action="delete_help.php">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Delete existing link:</h4></div>
													<div class="modal-body">
													<div class="row">
														<input type="text" name="id" id="label_id2" class="form-control" value="" style="display:none;"></input>
														<p>Are you sure do you want delete this link?</p>
													</div>
													</div>
													
												<div class="modal-footer">
												<input type="submit" value="Yes" class="btn btn-secondary">
												<button  type="button" class="btn btn-default" data-dismiss="modal">No</button>
											</div>
									</div>
								</div>
							</form>
						</div>
					<!---->
				<div>
				<select name="limit" id="limit_select">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="15">15</option>
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
					//&orderBy_control
					
					echo('
					<tr>
                        <th class="Id" style="display:none;"><div><a href="'.$pagina_attuale.'&orderBy=Id&order='.$by_par.'">Id '.$icon_by .'</a></div><div class="input-group mb-3"><input  class="form-control"type="text" style="color: black;" size="18" id="filterId" placeholder="Search..." /><span class="input-group-btn"><button id="filterId" type="button btn btn-default" class="filter">Search</button></span></div></th>
                        <th class="Tool"><div><a href="'.$pagina_attuale.'&orderBy=label&order='.$by_par.'">Label '.$icon_by .'</a></div><div class="input-group mb-3"><input type="text" class="form-control" style="color: black;" size="18" id="filterTool" placeholder="Search..." /><span class="input-group-btn"><button id="filterTool" type="button" class="filter btn btn-default">Search</button></span></div></th>
						<th class="url"><div><a href="'.$pagina_attuale.'&orderBy=url&order='.$by_par.'">Url '.$icon_by .'</a></div><div class="input-group mb-3"><input type="text" class="form-control" style="color: black;" size="36" id="filterUrl" placeholder="Search..." /><span class="input-group-btn"><button id="filterUrl" type="button" class="filter btn btn-default">Search</button></span></div></th>
                        <th class="click"><div><a href="'.$pagina_attuale.'&orderBy=accesses&order='.$by_par.'">Number of accesses '.$icon_by .'</a></div></th>
						<th class="type"><div>Type</div></th>
						<th class="edit"><div>Edit</div></th>
						<th class="edit"><div>Delete</div></th>
                    </tr>
					');
					?>
					</thead>	
					<tbody>
					<?php
					
					
					
					for ($i = 0; $i <= $num_rows; $i++) {	
						if ($process_list[$i]['Id'] == ""){
									$edit_button="";
									$delete_button="";
								}else{
									$edit_button="<button type='button' class='editDashBtn modify_jt' data-target='#data-modal3' data-toggle='modal'>EDIT</button>";
									$delete_button="<button type='button' class='delDashBtn delete_jt' data-target='#delete_link' data-toggle='modal'>DEL</button>";
								}
					
							echo ("<tr>
										<td style='display:none;'>".$process_list[$i]['Id']."</td>
									    <td>".$process_list[$i]['label']."</td>
										<td><a href='".$process_list[$i]['url']."' target='_blank'>".$process_list[$i]['url']."</a></td>
										<td>".$process_list[$i]['accesses']."</td>
										<td>".$process_list[$i]['type']."</td>
										<td>".$edit_button."</td>
										<td>".$delete_button."</td>
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
								////
								$array_link = array ();
								/////
							if ($prev_page >=1){
							echo ('	<div class="pagination" value="'.$prev_page.'">&#09;<a href="help_manager.php?elementTool='.$_GET['elementTool'].'&elementUrl='.$_GET['elementUrl'].'&page='.$prev_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'"><< 	Prev</a></div>');
							}
							////
							//$corr_page
										if ($corr_page >11){
										$init_j = $corr_page -10;
										}else{$init_j = 1; 
										}
							////
							for ($j=$init_j; $j<=$total_pages; $j++) {  
										if (($j<11)||(($corr_page-$j)>=0)||(($corr_page == $j)&&($corr_page < $total_pages-3))||(($corr_page >= $total_pages-3))){
											echo ("&#09;<a class='page_n' value='".$j."' href='help_manager.php?elementTool=".$_GET['elementTool'].'&elementUrl='.$_GET['elementUrl']."&page=".$j."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$j."</a>&#09;");
										}else{echo(" ");}
							}; 
							//
							
									$last_pages = $total_pages-3;
								if (($total_pages > 13)&&($corr_page < $last_pages)){
											echo ("...");
											for ($y=$last_pages; $y<=$total_pages; $y++) {  
												echo ("&#09;<a class='page_n' value='".$y."' href='help_manager.php?elementTool=".$_GET['elementTool'].'&elementUrl='.$_GET['elementUrl']."&page=".$y."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$y."</a>&#09;");	
											};
									}
							//
							if ($suc_page <=$total_pages){
									echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="help_manager.php?elementTool='.$_GET['elementTool'].'&elementUrl='.$_GET['elementUrl'].'&page='.$suc_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'">Next 	>></a></div>');
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

<script type='text/javascript'>

$(document).ready(function () {
		//$('#storico').dynatable();
		//var actual_role = "<?=$role_att;?>";
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
					
			utente_attivo=$("#utente_att").text();
			if (utente_attivo=='Login'){
					console.log("VUOTO");
					$(document).empty();
					alert("Effettua il login!");
				}
				
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
		var error_name="<?=$error_name; ?>";
		var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('.ellipsis').css("width","150 px");
			$('#edit_modal').attr("action","edit_help.php?showFrame=false");
			$('#add_modal').attr("action","add_new_help.php?showFrame=false");
			$('#delete_modal').attr("action","delete_help.php?showFrame=false");
			
		}

		var limit_default= "<?=$limit; ?>";
		//console.log(limit_default);
		$('#limit_select').val(limit_default);	

var pagina_corrente="<?=$corr_page; ?>";
		//trovare il link con value uguale alla pagina e colorarlo di bianco.
		$("a.page_n[value='"+pagina_corrente+"']").css("text-decoration","underline");
	
		/////////////
		var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
				}
		
	
		$(document).on('click','.delete_jt',function(){
			var ind = $(this).parent().parent().first().children().html();
			$('#label_id2').val(ind);
		});
	
	
		$(document).on('click','.modify_jt',function(){
			var ind = $(this).parent().parent().first().children().html();
			var link = $(this).parent().parent().children().eq(2).children().html();
			var label = $(this).parent().parent().children().eq(1).html();
			//console.log(label);
			//var type = $(this).parent().parent().children().eq(3).children().html();
			var type = $(this).parent().parent().children().eq(4).html();
			$('#label_id').val(ind);
			$('#label_name').val(label);
			$('#id_link').val(link);
			$('#select_type').val(type);
		});
	
        //Versione client side ma ben funzionanete del caricamento
		var array_act = [];
		
		
		$(document).on('click','#reset',function(){
			var user = $('#filterUser').val("");
			var filterElId = $('#filterId').val("");
			var elementType = $('#filterTool').val("");
			var elementUrl = $('#filterUrl').val("");
			var href = document.location.href;
			var lastPathSegment = href.substr(href.lastIndexOf('/') + 1);
			if((window.self !== window.top)||(nascondi == 'hide')){
						window.location.href = "help_manager.php?showFrame=false&page=1&limit=10";		
					}
				else{
		      			window.location.href = "help_manager.php?showFrame=true&page=1&limit=10";
				}
		});
		
		$().on('click','#edit',function(){
			//edit
			if((window.self !== window.top)||(nascondi == 'hide')){
						window.location.href = "add_new_help.php?showFrame=false";		
					}
				else{
		      			window.location.href = "add_new_help.php?showFrame=true";
				}
		});
		
		//Unico Button///
		$(document).on('click','.filter',function(){
			var limit_val = $('#limit_select').val();
			var filterElId = $('#filterId').val();
			var elementType = $('#filterTool').val();
			var elementUrl = $('#filterUrl').val();
			if((window.self !== window.top)||(nascondi == 'hide')){	
							window.location.href = 'help_manager.php?showFrame=false&page=1&elementTool='+elementType+'&elementUrl='+elementUrl+'&limit='+limit_val;						
						}
						else{
							window.location.href = 'help_manager.php?showFrame=true&page=1&elementTool='+elementType+'&elementUrl='+elementUrl+'&limit='+limit_val;	
					}
		});
		
		$('#limit_select').change(function() {
			var limit_val = $('#limit_select').val();
			var filterElId = $('#filterId').val();
			var elementType = $('#filterTool').val();
			var elementUrl = $('#filterUrl').val();
			if((window.self !== window.top)||(nascondi == 'hide')){	
							window.location.href = 'help_manager.php?showFrame=false&page=1&elementTool='+elementType+'&elementUrl='+elementUrl+'&limit='+limit_val;						
						}
						else{
							window.location.href = 'help_manager.php?showFrame=true&page=1&elementTool='+elementType+'&elementUrl='+elementUrl+'&limit='+limit_val;	
					}
		});
		
		if (error_name=='OK'){
			alert('Operation failed. You can not use this Label name, because is already used.');
		}
		
});
</script>
</body>
</html>