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
   include('external_service.php');
   //session_start();
  include('curl.php');
	$link = mysqli_connect($host_photo, $username_photo, $password_photo) or die("failed to connect to server !!");
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname_photo);
if(isset($_POST['values'])){
	$values=$_POST['values'];
}
else{
    $values=null;
}
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
   if(isset($_SESSION['showFrame'])){
   $_SESSION['showFrame']=$_REQUEST['showFrame'];
   }
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

    if (isset($_GET['limit'])){
        $limit=$_GET['limit'];
        $limit0=$_GET['limit'];
//
//        else if($_GET['limit']!==""){
//            $limit=$_GET['limit'];
//            $limit0=$_GET['limit'];
//        }
    }else{
    $limit = 5;
    $limit0 = 5;
    }
if (isset($_GET['limit'])) {
    if ($_GET['limit'] == "") {
        $limit = 5;
        $limit0 = 5;
    }
}
if (isset($_GET["page"])) { 
		$page  = $_GET["page"]; 
	} else { 
		$page=1; 
	};  
$start_from = ($page-1) * $limit; 


if($values!=null){
    $value="";
    for($i=0;$i<count($values);$i++){
        if($i==count($values)-1){
            $value=$value."'".$values[$i]."'";
        } else{$value=$value."'".$values[$i]."'"." OR status=";}

    }
		$query = "SELECT * FROM ServicePhoto WHERE status=$value ";
    }
	    else{$query = "SELECT * FROM ServicePhoto ";}


    $result = mysqli_query($link, $query) or die(mysqli_error($link));
	$list = array();
	$num = $result->num_rows;

if (isset($_GET["page_comment"])) { 
		$page0  = $_GET["page_comment"]; 
	} else { 
		$page0=1; 
	};
if(isset($_GET["page_comment"])){
    if ($_GET["page_comment"] == ""){
    $page0=1;
    }
}
$values0 = "";
$start_from0 = ($page0-1) * $limit0; 
if($values0!=null){
    $value0="";
    for($i=0;$i<count($values0);$i++){
        if($i==count($values0)-1){
            $value0=$value0."'".$values0[$i]."'";
        } else{$value0=$value0."'".$values0[$i]."'"." OR status=";}

    }
		$query0 = "SELECT * FROM ServiceComment WHERE status=$value ";
    }
	    else{$query0 = "SELECT * FROM ServiceComment ";}


    $result0 = mysqli_query($link, $query0) or die(mysqli_error($link));
	$num0 = $result0->num_rows;

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
		 <link href="css/loader.css" rel="stylesheet">
		<!-- Rating -->
		<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
		<!-- --> 
		<link rel="stylesheet" href="foto_service2/photo_service.css" />
		<link rel="stylesheet" href="foto_service2/leaflet/leaflet.css" />
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"   integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="   crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"   integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="	   crossorigin=""></script>
		 <script src="foto_service2/leaflet/leaflet.js"></script>
		 <script src='https://api.mapbox.com/mapbox-gl-js/v0.44.2/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v0.44.2/mapbox-gl.css' rel='stylesheet' />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
	<!-- -->
	<script type="text/javascript" src="js/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
    <link href="js/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<!-- -->	
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
                        <div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin: 0; padding-top: 0;padding-bottom:0;margin-top: 0px;'>
                            <div class="row mainContentRow" id="dashboardsListTableRow" style="padding-top: 0; padding-bottom:0;">
                                <div class="col-xs-12 mainContentCellCnt" style='background-color: rgba(138, 159, 168, 1)'>
									<!--MAppa-->
										<div class="container-fluid" style="margin-bottom: 0.2%;">
											<div id="mapid" style=" height: 300px; width: 100%;"></div>
										</div>
									<!-- -->            
									<div class="container-fluid" style="height: 25%; margin-bottom: 0.2%; margin-right: 0px; margin-left: 0px; padding-left: 15px; padding-right: 15px;" id="list_dashboard_cards">
									<div class="header_title" style="margin-bottom: 20px;">
									<!--
									<h3 style="display: inline;">Photos: </h3>
									<form style="display: inline;">
									<!--<div class="btn btn-xs btn-group" aria-label="Basic example">
										<input type="button" name="filter" id="all" value="Reset" onclick="reload_photo();" class="btn btn-primary">

										  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Filters
										  </a>
										<div class="dropdown-menu btn btn-xs" aria-labelledby="dropdownMenuLink ">
										<a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="validated" value=""  onchange="ffilterfunc(value,id)"> Validated</a>
										<a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="submitted" value=""  onchange="ffilterfunc(value,id)"> Submitted</a>
										<a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="rejected" value="" onchange="ffilterfunc(value,id)"> Rejected</a>
										</div>
									</div>
									Example single danger button
										<div class="btn-group" role="group" aria-label="Basic example">
										  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Filters
										  </button>
										  <div class="dropdown-menu btn btn-xs" aria-labelledby="dropdownMenuLink">
											<li><a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="validated" value=""  onchange="ffilterfunc(value,id)"> Validated</a></li>
											<li><a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="submitted" value=""  onchange="ffilterfunc(value,id)"> Submitted</a></li>
											<li><a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="rejected" value="" onchange="ffilterfunc(value,id)"> Rejected</a></li>
										  </div>
										</div>
									 -->
									 <h3 style="display: inline;">Photos: </h3>
									 <form class="form-inline" style="display: inline;">
                                        <div class="btn-group" role="group" aria-label="Basic example">


                                            <input type="button" name="Resetfilter" id="all" value="Reset" onclick="reload_photo();" class="btn btn-primary">
                                                                                  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    Filters
                                                                                  </a>
                                                                                <div class="dropdown-menu btn btn-xs" aria-labelledby="dropdownMenuLink">
                                                                                    <li><a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="validated" value=""  onchange="ffilterfunc(value,id)"> Validated</a></li>
                                                                                    <li><a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="submitted" value=""  onchange="ffilterfunc(value,id)"> Submitted</a></li>
                                                                                    <li><a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="rejected" value="" onchange="ffilterfunc(value,id)"> Rejected</a></li>
                                                                                </div>
    <!--                                                                            </a>-->
    <!--    																		<a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuDate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
    <!--    																			Search by date-->
    <!--    																		</a>-->
    <!--    																		<div class="dropdown-menu btn btn-xs" aria-labelledby="dropdownMenuDate">-->
    <!--    																		<li><a class="dropdown-item" href="#">Date: <input id="foto_date" type="text" class="datepicker" name="end" value=""></a></li>-->
    <!--    																		</div>-->
    <!--                                                                            <input id="foto_date" type="text" class="datepicker" name="end" value="">-->
                                         </div>
                                             <label for="inlineFormInputGroup" class="col-2 col-form-label"></label>
                                                 <input class="form-control" type="date" value="" id="example-date-input" onchange="dateSearch(value)">
                                     </form>


<!--                                        </form>-->
											<div id="dynatable-pagination-links-cards">
																		<?php 
																		$total_records = $num;
																		$total_pages = ceil($total_records / $limit);
																			$prev_page = $page-1;
																			$suc_page = $page+1;
																			$corr_page= $page;

																	//	if ($prev_page >=1){
																	//	echo ('<a href="photo_service.php?page='.$prev_page.'&page_comment='.$page0.'"><< 	Prev</a>');
																	//	}
																	//				if ($corr_page >6){
																	//				$init_j = $corr_page -5;
																	//				}else{$init_j = 1; 
																	//				}
																	//	for ($j=$init_j; $j<=$total_pages; $j++) { 
																		//					if (($j<6)||(($corr_page-$j)>=0)||(($corr_page == $j)&&($corr_page < $total_pages-3))||(($corr_page >= $total_pages-3))){
																		//		//		echo ("&#09;<a class='page_n' value='".$j."' href='photo_service.php?page=".$j."&page_comment=".$page0."'>".$j."</a>&#09;");
																		//					}
																	//	}; 
																		
																		//$last_pages = $total_pages-3;
																		//if (($total_pages > 8)&&($corr_page < $last_pages)){
																		//echo ("...");
																		//for ($y=$last_pages; $y<=$total_pages; $y++) {  
																				//		echo ("&#09;<a class='page_n' value='".$y."' href='photo_service.php?page=".$y."&page_comment=".$page0."'>".$y."</a>&#09;");	
																		//			};
																		//		}
																		//if ($suc_page <=$total_pages){
																		//echo ('	<a href="photo_service.php?page='.$suc_page.'&page_comment='.$page0.'">Next 	>></a>');
																		//}
																	?>
														</div>
														</div>														
														<div class="row row-horizon" id="cards" style="width: 100%; text-align: center; float: left;">
													</div>
											</div>	
									<!-- -->
											<div class="container-fluid" style="height: 25%; margin-bottom: 0.2%; margin-right: 0px; margin-left: 0px; padding-left: 15px; padding-right: 15px; width: 100%;" id="list_dashboard_comments">
																	<div class="header_title" style="margin-bottom: 20px;">
																	<h3 style="display: inline;">Comments: </h3>
                                                                        <form class="form-inline" style="display: inline;">
                                                                            <div class="btn-group" role="group" aria-label="Basic example">


                                                                                <input type="button" name="Resetfilter" id="call" value="Reset" onclick="reload_comment();" class="btn btn-success">
                                                                                <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    Filters
                                                                                </a>
                                                                                <div class="dropdown-menu btn btn-xs" aria-labelledby="dropdownMenuLink">
                                                                                    <li><a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="cvalidated" value=""  onchange="filterfunc(value,id)"> Validated</a></li>
                                                                                    <li><a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="csubmitted" value=""  onchange="filterfunc(value,id)"> Submitted</a></li>
                                                                                    <li><a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="crejected" value="" onchange="filterfunc(value,id)"> Rejected</a></li>
                                                                                </div>
                                                                                <!--                                                                            </a>-->
                                                                                <!--    																		<a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuDate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                                                                                <!--    																			Search by date-->
                                                                                <!--    																		</a>-->
                                                                                <!--    																		<div class="dropdown-menu btn btn-xs" aria-labelledby="dropdownMenuDate">-->
                                                                                <!--    																		<li><a class="dropdown-item" href="#">Date: <input id="foto_date" type="text" class="datepicker" name="end" value=""></a></li>-->
                                                                                <!--    																		</div>-->
                                                                                <!--                                                                            <input id="foto_date" type="text" class="datepicker" name="end" value="">-->
                                                                            </div>
                                                                            <label for="inlineFormInputGroup" class="col-2 col-form-label"></label>
                                                                            <input class="form-control" type="date" value="" id="cexample-date-input" onchange="cdateSearch(value)">
                                                                        </form>

<!--																	<form style="display: inline;">-->
<!--																	<!--<div class="btn btn-xs btn-group" role="group" aria-label="Basic example">-->
<!--																	<div class="btn-group" role="group" aria-label="Basic example">-->
<!--																		<input type="button" name="cfilter" id="call" value="Reset" onclick="reload_comment();" class="btn btn-success">-->
<!--																		  <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLinkDate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--																			Filters-->
<!--																		  </a>-->
<!--																		<div class="dropdown-menu btn btn-xs" aria-labelledby="dropdownMenuLinkDate">-->
<!--																		<li><a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="cvalidated" value=""  onchange="filterfunc(value,id)"> Validated</a></li>-->
<!--																		<li><a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="csubmitted" value=""  onchange="filterfunc(value,id)"> Submitted</a></li>-->
<!--																		<li><a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="crejected" value="" onchange="filterfunc(value,id)"> Rejected</a></li>-->
<!--																		</div>-->
<!--																		<!--<input id="comment_date" type="text" class="datepicker" name="end" value="">-->
<!--																		<a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuComment" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--																			Search by date-->
<!--																		</a>-->
<!--																		<div class="dropdown-menu btn btn-xs" aria-labelledby="dropdownMenuComment">-->
<!--																		<li><a class="dropdown-item" href="#">Date: <input id="comment_date2" type="text" class="datepicker" name="end" value=""></a></li>-->
<!--																		</div>-->
<!--																	</div>-->
<!--																	<!--</div>-->
<!--																	</form>-->
																	<div id="dynatable-pagination-links-comments">
																										<?php 
																										
																										$total_records0 = $num0;
																										$total_pages0 = ceil($total_records0 / $limit);
																											$prev_page0 = $page0-1;
																											$suc_page0 = $page0+1;
																											$corr_page0= $page0;
																									//	if ($prev_page0 >=1){
																									//	echo ('<a href="photo_service.php?page_comment='.$prev_page0.'&page='.$page.'"><< 	Prev</a>');
																										//}
																												//	if ($corr_page0 >6){
																												//	$init_j0 = $corr_page0 -5;
																												//	}else{$init_j0 = 1; 
																												//	}
																										//for ($i=$init_j0; $i<=$total_pages0; $i++) { 
																											//				if (($i<6)||(($corr_page0-$i)>=0)||(($corr_page0 == $i)&&($corr_page0 < $total_pages0-3))||(($corr_page0 >= $total_pages0-3))){
																													//	echo ("&#09;<a class='page_n2' value='".$i."' href='photo_service.php?page_comment=".$i."&page=".$page."'>".$i."</a>&#09;");
																											//				}
																										//}; //
																										
																										//$last_pages0 = $total_pages0-3;
																									//	if (($total_pages0 > 8)&&($corr_page0 < $last_pages0)){
																										//echo ("...");
																									//	for ($z=$last_pages0; $z<=$total_pages0; $z++) {  
																										//			//	echo ("&#09;<a class='page_n2' value='".$z."' href='photo_service.php?page_comment=".$z."&page=".$page."'>".$z."</a>&#09;");	
																											//		};
																										//		}
																									//	if ($suc_page0 <=$total_pages0){
																										//echo ('	<a href="photo_service.php?page_comment='.$suc_page0.'&page='.$page.'">Next 	>></a>');
																									//	}
																									?>
																		</div>
																		</div>
																	<div class="row row-horizon" id="comments" style="width: 100%; text-align: center; float: left;"></div>
																</div>
									<!-- -->
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
        
		<script>
			mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlc3NhbmRyb2JhY2Npb3R0aW5pIiwiYSI6ImNqaWxoNjZ6MDJvdG4zd3BleDB5aGkxMjkifQ.ddcO3RVomvLGJZBQzu5quw';
			var map = new mapboxgl.Map({
			container: 'mapid',
			style: 'mapbox://styles/mapbox/streets-v10'
			});
			var mymap = L.map('mapid').setView([43.797, 11.252],8);
							L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
								attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
								maxZoom: 18,
								id: 'mapbox.streets',
								accessToken: 'pk.eyJ1IjoiYWxlc3NhbmRyb2JhY2Npb3R0aW5pIiwiYSI6ImNqaWxoNjZ6MDJvdG4zd3BleDB5aGkxMjkifQ.ddcO3RVomvLGJZBQzu5quw'
							}).addTo(mymap);
			</script>
			 </body>
        <!-- Modale creazione dashboard -->
 
	<script type='text/javascript'>
		//var ut_att = "<?//=$utente_att;?>//";
		//var role_att = "<?//=$role_att;?>//";
		//var success_modify = "<?//=$success_modify; ?>//";
		//var modified_status = "<?//=$modified_status; ?>//";
		//var modify_ownership = "<?//=$modify_ownership; ?>//";
		//var error_message = "<?//=$error_message; ?>//";
		//var error_status = "<?//=$error_status; ?>//";
		var nascondi= "<?=$hide_menu; ?>";
		var limit_init="<?=$limit; ?>";
		var page_init="<?=$page; ?>";
		var limit_init0="<?=$limit0; ?>";
		var page_init0="<?=$page0; ?>";
		//
		var prev_page="<?=$prev_page; ?>";
		var prev_page0="<?=$prev_page0; ?>";
		var page0="<?=$page0; ?>";
		var suc_page="<?=$suc_page; ?>";
		var suc_page0="<?=$suc_page0; ?>";
		var totale_pagine="<?=$total_pages;?>";
		var totale_pagine0="<?=$total_pages0;?>";
		var api_img="<?=$photo_api; ?>";	
			

		function ruota1(id,grado){
				deg=grado - 90;
				idimg= "img"+id;
				left="left_"+id;
				document.getElementById(idimg).style = 'width:160px; height:160px; margin-bottom:10px; margin-top:10px; transform: rotate(' + deg + 'deg)';
				$("#"+left).attr("onclick","ruota1("+id+","+deg+")");
		}

		function changeStatus(id,new_status) {
            $.ajax({
                url: "foto_service2/change-status.php",
                data: {new_status: new_status, id:id},
                type: "POST",
                async: true,

                success: function (data) {
                    alert('Status changed!');
					$('#'+id+' option[value='+new_status+']').attr("selected",true);
                }
            });
        }
		
		function changeStatus2(id,new_status) {
				id = id.replace("comment-sel","");
            $.ajax({
                url: "foto_service2/change-status-comment.php",
                data: {new_status: new_status, id:id},
                type: "POST",
                async: true,
                success: function (data) {
                    alert('Status changed!');
					$('#comment-sel'+id+' option[value='+new_status+']').attr("selected",true);
                }
            });
        }
	
		function ruota2(id,grado){
				deg=grado + 90;
				idimg= "img"+id;
				right="right_"+id;
				document.getElementById(idimg).style = 'width:160px; height:160px; margin-bottom:10px; margin-top:10px; transform: rotate(' + deg + 'deg)';
				$("#"+right).attr("onclick","ruota2("+id+","+deg+")");
		}	
		var pagina="<?=$page; ?>";
				$("a.page_n[value='"+pagina+"']").css("text-decoration","underline");		
		var page_comment="<?=$page0; ?>";
				$("a.page_n2[value='"+page_comment+"']").css("text-decoration","underline");


	   function filterfunc(value,id) {
            id=id.slice(1,id.length);
            if(value==""){

                document.getElementById(id).value=id;
            }else{
                document.getElementById(id).value="";
            }
            var values=[];
            document.getElementsByName('filter').forEach(function (item) {
                if((item.value)!=""){
                    values.push(item.value);
                }
            });

            $('#comments').empty();
            var array = new Array();
			$.ajax({
                            url: "get_comment_data.php",
                            data: {values: values,limit:limit_init0, page_comment:page_init0},
                            type: "POST",
                            async: true,
                            dataType: 'json',
                            success: function (data){
								////// 
								var count=0;
										 var prev_arrow ='<div class="row_card" style="display: inline-block; margin-left: 40px;"><a href="photo_service.php?page_comment='+prev_page0+'&page='+page_init+'"><i class="fa fa-chevron-left" style="font-size: 140px;"></i></a></i></div>';
										 $('#comments').append($(prev_arrow));
								/////
                                for (var i = 0; i < data.length; i++) {
                                    array[i] = {
                                        id: data[i]['id'],
                                        serviceUri: data[i]['serviceUri'],
                                        serviceName: data[i]['serviceName'],
                                        uid: data[i]['uid'],
                                        status: data[i]['status'],
                                        timestamp: data[i]['timestamp'],
                                        lat: data[i]['lat'],
                                        lon: data[i]['lon'],
                                        comment: data[i]['comment']
                                    }
													//
													var array2 = new Array(10);
														for (var t = 0; t < 10; t++) {
															array2[t] = new Array(10);
														}
														
														// if(count==0){
														//
														// 	array2[0][0]=array[i].lat;
														// 	array2[0][1]=array[i].lon;
														//
														// }
														// else{
															for (var k = 0; k < count; k++){
																if ((array[i].lat == array[k][0]) && (array[i].lon == array[k][1])) {
																	array[count][0] = array[i].lat + (Math.random() - .4) / 1000;
																	array[count][1] =array[i].lon+ (Math.random() - .4) / 1000;
																	array[i].lat = array[count][0];
																	array[i].lon = array[count][1];

																}
																else{
																	array[count][0] = array[i].lat;
																	array[count][1] = array[i].lon;
																}
															}
														// }
														count++;
													//

									var greenIcon = new L.Icon({
											iconUrl: 'img/marker-icon-2x-green.png',
											shadowUrl: 'img/marker-shadow.png',
											iconSize: [25, 41],
											iconAnchor: [12, 41],
											popupAnchor: [1, -34],
											shadowSize: [41, 41]
										});
									
										
									var div_comments = document.getElementById('comments');
									var cardComments= '<div class="row_card" style="display: inline-block; "><div class="card col-md-4" style="background-color: rgb(108, 135, 147);"> <div class="card-body"> <p class="card-text" style="font-size:0.7em; display:inline-block; word-wrap:break-word; max-width: 200px;">'+array[i].timestamp+'</p><br /><div style="display:inline-block; word-wrap:break-word; max-width: 200px;"><p style="display:inline-block; word-wrap:break-word;" class="card-text">'+array[i].comment+'</p><br /><form> <fieldset> <select id="comment-sel'+array[i].id+'" name="status" placeholder="'+array[i].status+'" index="'+i+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted">submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" style="font-size:0.7em;" id="loc'+i+'" placeholder="'+array[i].lat+', '+array[i].lon+'" >'+array[i].serviceName+'</p><p onclick="click_loc(this)"  onmouseover="underline(this)" onmouseout="normal(this)" class="card-text" id="location'+i+'" placeholder="'+array[i].lat+', '+array[i].lon+'" >view on map</p></div></div></div> '; 									
                                    $('#comments').append($(cardComments));
									//set Marker on Map
                                    $('#'+array[i].id+' option[value='+array[i].status+']').attr('selected', 'selected');
                                    $('#comm-sel'+i+' option[value='+array[i].status+']').attr('selected', 'selected');
									
									if (((array[i].lat != null)&&(array[i].lon != null))){
										var marker=L.marker([array[i].lat,array[i].lon],{icon:greenIcon}).addTo(mymap);
											marker.bindPopup('<b>Comment:</b>'+array[i].comment);
									}
                                }
									var suc_arrow ='<div class="row_card" style="display: inline-block; vertical-align: middle; display: inline-block;  margin-left: 50px; margin-right: 50px;"><a href="photo_service.php?page_comment='+suc_page0+'&page='+page_init+'"><i class="fa fa-chevron-right" style="font-size: 130px;"></i></a></i></div>';
										 $('#comments').append($(suc_arrow));

                            }
                        });
            }
		
		////////////////
					function underline(p){
							$(p).css("text-decoration", "underline");
							$(p).css("cursor", "pointer");
							}
							
		function normal(p){
							$(p).css("text-decoration", "none");
							$(p).css("cursor", "auto");
							}
		function click_loc(p){
							var loc = $(p).attr('placeholder');					
							var loc_array = JSON.parse("[" + loc + "]");
							var greenIcon = new L.Icon({
											iconUrl: 'img/marker-icon-2x-green.png',
											shadowUrl: 'img/marker-shadow.png',
											iconSize: [25, 41],
											iconAnchor: [12, 41],
											popupAnchor: [1, -34],
											shadowSize: [41, 41]
										});

							var marker = L.marker(loc_array,{icon:greenIcon}).addTo(mymap);
							marker.bindPopup('<b>Comment:</b>'+array[i].comment);
						}

			///////////////
		
	$(window).on('load', function(){
		//
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
					
		//
		$(function(){	
			var dateNow = new Date();
			$('.datepicker').datetimepicker({
				format: 'yyyy-mm-dd',
				language: 'en',
				current: dateNow,
				defaultDate: dateNow,
				startDate: dateNow
			});
		});
		
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
		///////////
					var array = new Array();
                        $.ajax({
                        url: "get_photo_data.php",
                        data: {values: "", limit:limit_init, page:page_init},
                        type: "POST",
                        async: true,
                        dataType: 'json',
                        success: function (data) {
                           // alert(); <i class="fas fa-chevron-right"></i>
						  		   
						 //  var prev_arrow ='<div class="row_card" style="display: inline-block; margin-left: 40px; vertical-align: middle;"><a href="photo_service.php?page='+prev_page+'&page_comment='+page0+'"><i class="fa fa-chevron-left fa-lg" style="size: 150px;"></i></a></i></div>';
							if ((page_init == '1')||(page_init == '') ) {
								var prev_arrow = '<div class="row_card" style="display: inline-block; margin-left: 40px;"><a href="#"></a></i></div>';
							}else{
						   var prev_arrow ='<div class="row_card" style="display: inline-block; margin-left: 40px;"><a href="photo_service.php?page='+prev_page+'&page_comment='+page0+'"><i class="fa fa-chevron-left" style="font-size: 140px;"></i></a></i></div>';
							}
						$('#cards').append($(prev_arrow));
							
						var count =0;
						
                        for (var i = 0; i < data.length; i++) {
                                 array[i] = {
                                     id: data[i]['id'],
                                     serviceUri: data[i]['serviceUri'],
                                     serviceName: data[i]['serviceName'],
                                     uid: data[i]['uid'],
                                     file: data[i]['file'],
                                     status: data[i]['status'],
                                     ip: data[i]['ip'],
                                     timestamp: data[i]['timestamp'],
                                     lat: data[i]['lat'],
                                     lon: data[i]['lon'],
                                     comment: data[i]['comment'],
                                     comment_id: data[i]['comment_id'],
                                     comment_status:data[i]['comment_status'],
									 address: data[i]['address'],
                                     district: data[i]['district'],
                                     city:data[i]['city']
                                    }
									//
											var array2 = new Array(10);
									for (var t = 0; t < 10; t++) {
										array2[t] = new Array(10);
									}
									
									// if(count==0){
									
										array2[0][0]=array[i].lat;
										array2[0][1]=array[i].lon;
									
									// }
									// else{
										for (var k = 0; k < count; k++){
											// if ((array[i].lat == array[k][0]) && (array[i].lon == array[k][1])) {
												array[count][0] = array[i].lat + (Math.random() - .4) / 1000;
												array[count][1] =array[i].lon+ (Math.random() - .4) / 1000;
												array[i].lat = array[count][0];
												array[i].lon = array[count][1];

											// }
											// else{
												array[count][0] = array[i].lat;
												array[count][1] = array[i].lon;
											// }
										}
									// }
									count++;
									
									//

                                var div = document.getElementById('cards');
								var cardModal = '<div id="myModal'+i+'" class="modal fade" role="dialog" ><div class="modal-dialog" ><div class="modal-content"><div class="modal-body"><img id="img'+i+'" src="'+api_img+array[i].file+'" alt="Card image" style="width:100%; height:auto; max-height:800px;"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal" >Close</button></div></div></div></div>';
								var cardDiv = '<div class="row_card" style="display: inline-block; vertical-align: middle;"> <div class="card col-md-4" style="background-color: rgb(108, 135, 147);"><a data-toggle="modal" href="#myModal'+i+'" class="click_image" value="'+i+'"><img id="img'+i+'" class="card-img-top" style="width:160px; height:160px; margin-top:10px; margin-bottom:10px;" src="'+api_img+array[i].file+'" alt="Card image"></a><div class="card-body"><form style="display:inline-block"><fieldset><img placeholder="img'+i+'" id="left_'+i+'"  src="foto_service2/rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('+i+',0);"> 	<select id="'+array[i].id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option> <option value="validated" >validated</option> <option value="rejected" selected="selected">rejected </option> </select>	<img placeholder="img'+i+'" id="right_'+i+'"  src="foto_service2/rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('+i+',0);"> </fieldset> </form> <p class="card-text" style="font-size:0.7em;">'+array[i].timestamp+'</p> <p style="display:inline-block" class="card-text"> </div></div></div>'+cardModal;
								$('#cards').append($(cardDiv));

						if ((array[i].lat != null)&&(array[i].lon != null)){
							var marker=L.marker([array[i].lat,array[i].lon]).addTo(mymap);
								//marker.bindPopup('<center><img src="'+api_img+array[i].file+'" style="max-width:200px;max-height:200px;"></img></center><br/><b>Comment:</b>'+array[i].comment+'<br/><b>Location:</b>'+array[i].serviceName+'<br>'+'<b>Address:</b>'+array[i].address+'<br>'+'<b>City:</b>'+array[i].city+'('+array[i].district+')');
								marker.bindPopup('<center><img src="'+api_img+array[i].file+'" style="max-width:200px;max-height:200px;"></img></center><br/><b>Comment:</b>'+array[i].comment+'<br/><b>Location:</b>'+array[i].serviceName+'<br>'+'<b>Address:</b>'+array[i].address+'<br>'+'<b>City:</b>'+array[i].city+'('+array[i].district+')');
						
						}
                                $('#'+array[i].id+' option[value='+array[i].status+']').attr('selected', true);
                                $('#comm-sel'+i+' option[value='+array[i].comment_status+']').attr('selected', true);
								
								$('#'+array[i].id).change(function(){
											var new_status = $(this).find(":selected").text();
											var id = $(this).attr('id');
											$.ajax({
												url: "./foto_service2/change-status.php",
												data: {new_status: new_status, id:id},
												type: "POST",
												async: true,

												success: function (data) {
													alert('Status changed!');
												}
											});
										});
								
                            }
							//
				
							var next_arrow ='<div class="row_card" style="display: inline-block;"><a href="photo_service.php?page='+suc_page+'&page_comment='+page0+'"><i class="fa fa-chevron-right" style="font-size: 140px;"></i></a></div>';
						 $('#cards').append($(next_arrow));
						
									//
                        }
                    });
		//////////
				$.ajax({
                            url: "get_comment_data.php",
                            data: {values: "",limit:limit_init0, page_comment:page_init0},
                            type: "POST",
                            async: true,
                            dataType: 'json',
                            success: function (data){
									if ((page_init0 == '1')||(page_init0 == '') ) {
									var prev_arrow ='<div class="row_card" style="display: inline-block; margin-left: 40px;"><a href="#"></a></div>';	
									}else{
									var prev_arrow ='<div class="row_card" style="display: inline-block; margin-left: 40px;"><a href="photo_service.php?page_comment='+prev_page0+'&page='+page_init+'"><i class="fa fa-chevron-left" style="font-size: 140px;"></i></a></div>';
									}
									$('#comments').append($(prev_arrow));

                                for (var i = 0; i < data.length; i++) {
                                    array[i] = {
                                        id: data[i]['id'],
                                        serviceUri: data[i]['serviceUri'],
                                        serviceName: data[i]['serviceName'],
                                        uid: data[i]['uid'],
                                        status: data[i]['status'],
                                        timestamp: data[i]['timestamp'],
                                        lat: data[i]['lat'],
                                        lon: data[i]['lon'],
                                        comment: data[i]['comment']
                                    }  

									var greenIcon = new L.Icon({
											iconUrl: 'img/marker-icon-2x-green.png',
											shadowUrl: 'img/marker-shadow.png',
											iconSize: [25, 41],
											iconAnchor: [12, 41],
											popupAnchor: [1, -34],
											shadowSize: [41, 41]
										});
									
										
									var div_comments = document.getElementById('comments');
									var cardComments= '<div class="row_card" style="display: inline-block; vertical-align: middle;"><div class="card col-md-4" style="background-color: rgb(108, 135, 147);"> <div class="card-body" style="width: 160px;"> <p class="card-text" style="font-size:0.7em; display:inline-block; word-wrap:break-word; max-width: 160px;">'+array[i].timestamp+'</p><br /><div style="display:inline-block; word-wrap:break-word; max-width: 160px;"><p style="display:inline-block; word-wrap:break-word;" class="card-text" style="display:inline-block; word-wrap:break-word; max-width: 160px;">'+array[i].comment+'</p><br /><form> <fieldset> <select id="comment-sel'+array[i].id+'" name="status" placeholder="'+array[i].status+'" index="'+i+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted" selected="selected">submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form> </div><br /><p class="card-text" style="font-size:0.7em; display:inline-block; word-wrap:break-word; max-width: 160px;" id="loc'+i+'" placeholder="'+array[i].lat+', '+array[i].lon+'" >'+array[i].serviceName+'</p><p onclick="click_loc(this)"  onmouseover="underline(this)" onmouseout="normal(this)" class="card-text" id="location'+i+'" placeholder="'+array[i].lat+', '+array[i].lon+'" >view on map</p></div></div></div> '; 									
                                    $('#comments').append($(cardComments));
									//set Marker on Map
                                    $('#'+array[i].id+' option[value='+array[i].status+']').attr('selected', 'selected');
                                    $('#comm-sel'+i+' option[value='+array[i].status+']').attr('selected', 'selected');
									
									if (((array[i].lat != null)&&(array[i].lon != null))){
										var marker=L.marker([array[i].lat,array[i].lon],{icon:greenIcon}).addTo(mymap);
											marker.bindPopup('<b>Comment:</b>'+array[i].comment);
									}
                                }
										var suc_arrow ='<div class="row_card" style="display: inline-block; vertical-align: middle; display: inline-block;  margin-right: 50px;"><a href="photo_service.php?page_comment='+suc_page0+'&page='+page_init+'"><i class="fa fa-chevron-right" style="font-size: 130px;"></i></a></i></div>';
										 $('#comments').append($(suc_arrow));

                            }
                        });
	});

	$(document).on('click','#all',function(){
		window.location.href="photo_service.php?page_comment="+page_init0;
	});
	
	$(document).on('click','#call',function(){
		window.location.href="photo_service.php?page="+page_init;
	});
	
	function ffilterfunc(value,id) {

            if(value==""){
                document.getElementById(id).value=id;
            }else{
                document.getElementById(id).value="";
            }
            var date=document.getElementById('example-date-input').value;
           // alert(date);

            var values=[];
            document.getElementsByName('filter').forEach(function (item) {
                if((item.value)!=""){
                    values.push(item.value);
                }
            });

            $('#cards').empty();
            var array = new Array();

			//effettuare un controllo qui?
			if ( values === undefined ||  values.length == 0) {
						 values = "";
					}
			//
       // alert(JSON.stringify(values));
            $.ajax({
                        url: "get_photo_data.php",
                        data: {values: values, limit:limit_init, page:page_init,date:date},
                        type: "POST",
                        async: true,
                        dataType: 'json',
                        success: function (data) {
                           // alert(); <i class="fas fa-chevron-right"></i>
						  	if ((page_init == '1')||(page_init == '') ) {
								var prev_arrow = '<div class="row_card" style="display: inline-block; margin-left: 40px;"><a href="#"></a></i></div>';
							}else{
						   var prev_arrow ='<div class="row_card" style="display: inline-block; margin-left: 40px;"><a href="photo_service.php?page='+prev_page+'&page_comment='+page0+'"><i class="fa fa-chevron-left" style="font-size: 140px;"></i></a></i></div>';
							}
						 $('#cards').append($(prev_arrow));
							
                        for (var i = 0; i < data.length; i++) {
                                 array[i] = {
                                     id: data[i]['id'],
                                     serviceUri: data[i]['serviceUri'],
                                     serviceName: data[i]['serviceName'],
                                     uid: data[i]['uid'],
                                     file: data[i]['file'],
                                     status: data[i]['status'],
                                     ip: data[i]['ip'],
                                     timestamp: data[i]['timestamp'],
                                     lat: data[i]['lat'],
                                     lon: data[i]['lon'],
                                     comment: data[i]['comment'],
                                     comment_id: data[i]['comment_id'],
                                     comment_status:data[i]['comment_status'],
									 address: data[i]['address'],
                                     district: data[i]['district'],
                                     city:data[i]['city']
                                    }

                                var div = document.getElementById('cards');
								var cardModal = '<div id="myModal'+i+'" class="modal fade" role="dialog" ><div class="modal-dialog" ><div class="modal-content"><div class="modal-body"><img id="img'+i+'" src="'+api_img+array[i].file+'" alt="Card image" style="width:100%; height:auto"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal" >Close</button></div></div></div></div>';
								var cardDiv = '<div class="row_card" style="display: inline-block; "> <div class="card col-md-4" style="background-color: rgb(108, 135, 147);"><a data-toggle="modal" href="#myModal'+i+'" class="click_image" value="'+i+'"><img id="img'+i+'" class="card-img-top" style="width:160px; height:160px; margin-top:10px; margin-bottom:10px;" src="'+api_img+array[i].file+'" alt="Card image"></a><div class="card-body"><form style="display:inline-block"><fieldset><img placeholder="img'+i+'" id="left_'+i+'"  src="foto_service2/rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('+i+',0);"> 	<select id="'+array[i].id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option> <option value="validated" >validated</option> <option value="rejected" selected="selected">rejected </option> </select>	<img placeholder="img'+i+'" id="right_'+i+'"  src="foto_service2/rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('+i+',0);"> </fieldset> </form> <p class="card-text" style="font-size:0.7em;">'+array[i].timestamp+'</p> <p style="display:inline-block" class="card-text"> </div></div></div>'+cardModal;
								$('#cards').append($(cardDiv));

						if ((array[i].lat != null)&&(array[i].lon != null)){
							var marker=L.marker([array[i].lat,array[i].lon]).addTo(mymap);
								marker.bindPopup('<center><img src="'+api_img+array[i].file+'" style="max-width:200px;max-height:200px;"></img></center><br/><b>Comment:</b>'+array[i].comment+'<br/><b>Location:</b>'+array[i].serviceName+'<br>'+'<b>Address:</b>'+array[i].address+'<br>'+'<b>City:</b>'+array[i].city+'('+array[i].district+')');
						}
                                $('#'+array[i].id+' option[value='+array[i].status+']').attr('selected', true);
                                $('#comm-sel'+i+' option[value='+array[i].comment_status+']').attr('selected', true);
								
								$('#'+array[i].id).change(function(){
											var new_status = $(this).find(":selected").text();
											var id = $(this).attr('id');
											$.ajax({
												url: "change-status.php",
												data: {new_status: new_status, id:id},
												type: "POST",
												async: true,

												success: function (data) {
													alert('Status changed!');
												}
											});
										});
								
                            }
			
							var next_arrow ='<div class="row_card" style="display: inline-block; margin-right: 40px; "><a href="photo_service.php?page='+suc_page+'&page_comment='+page0+'"><i class="fa fa-chevron-right" style="font-size: 140px;"></i></a></div>';
						 $('#cards').append($(next_arrow));
						
						
									//
                        }
                    });
            }
	
	</script>
<script type="text/javascript">

    function dateSearch(date) {

        var values=[];
        document.getElementsByName('filter').forEach(function (item) {
            if((item.value)!=""){
                values.push(item.value);
            }
        });

        $('#cards').empty();
        var array = new Array();
        alert(JSON.stringify(values));
        $.ajax({
            url: "get_photo_data.php",
            data: {values:values, limit:limit_init, page:page_init,date: date},
            type: "POST",
            async: true,
            dataType: 'json',
            success: function (data) {
                // alert(); <i class="fas fa-chevron-right"></i>
                if ((page_init == '1')||(page_init == '') ) {
                    var prev_arrow = '<div class="row_card" style="display: inline-block; margin-left: 40px;"><a href="#"></a></i></div>';
                }else{
                    var prev_arrow ='<div class="row_card" style="display: inline-block; margin-left: 40px;"><a href="photo_service.php?page='+prev_page+'&page_comment='+page0+'"><i class="fa fa-chevron-left" style="font-size: 140px;"></i></a></i></div>';
                }
                $('#cards').append($(prev_arrow));

                for (var i = 0; i < data.length; i++) {
                    array[i] = {
                        id: data[i]['id'],
                        serviceUri: data[i]['serviceUri'],
                        serviceName: data[i]['serviceName'],
                        uid: data[i]['uid'],
                        file: data[i]['file'],
                        status: data[i]['status'],
                        ip: data[i]['ip'],
                        timestamp: data[i]['timestamp'],
                        lat: data[i]['lat'],
                        lon: data[i]['lon'],
                        comment: data[i]['comment'],
                        comment_id: data[i]['comment_id'],
                        comment_status:data[i]['comment_status'],
                        address: data[i]['address'],
                        district: data[i]['district'],
                        city:data[i]['city']
                    }

                    var div = document.getElementById('cards');
                    var cardModal = '<div id="myModal'+i+'" class="modal fade" role="dialog" ><div class="modal-dialog" ><div class="modal-content"><div class="modal-body"><img id="img'+i+'" src="'+api_img+array[i].file+'" alt="Card image" style="width:100%; height:auto"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal" >Close</button></div></div></div></div>';
                    var cardDiv = '<div class="row_card" style="display: inline-block; "> <div class="card col-md-4" style="background-color: rgb(108, 135, 147);"><a data-toggle="modal" href="#myModal'+i+'" class="click_image" value="'+i+'"><img id="img'+i+'" class="card-img-top" style="width:160px; height:160px; margin-top:10px; margin-bottom:10px;" src="'+api_img+array[i].file+'" alt="Card image"></a><div class="card-body"><form style="display:inline-block"><fieldset><img placeholder="img'+i+'" id="left_'+i+'"  src="foto_service2/rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('+i+',0);"> 	<select id="'+array[i].id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option> <option value="validated" >validated</option> <option value="rejected" selected="selected">rejected </option> </select>	<img placeholder="img'+i+'" id="right_'+i+'"  src="foto_service2/rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('+i+',0);"> </fieldset> </form> <p class="card-text" style="font-size:0.7em;">'+array[i].timestamp+'</p> <p style="display:inline-block" class="card-text"> </div></div></div>'+cardModal;
                    $('#cards').append($(cardDiv));

                    if ((array[i].lat != null)&&(array[i].lon != null)){
                        var marker=L.marker([array[i].lat,array[i].lon]).addTo(mymap);
                        marker.bindPopup('<center><img src="'+api_img+array[i].file+'" style="max-width:200px;max-height:200px;"></img></center><br/><b>Comment:</b>'+array[i].comment+'<br/><b>Location:</b>'+array[i].serviceName+'<br>'+'<b>Address:</b>'+array[i].address+'<br>'+'<b>City:</b>'+array[i].city+'('+array[i].district+')');
                    }
                    $('#'+array[i].id+' option[value='+array[i].status+']').attr('selected', true);
                    $('#comm-sel'+i+' option[value='+array[i].comment_status+']').attr('selected', true);

                    $('#'+array[i].id).change(function(){
                        var new_status = $(this).find(":selected").text();
                        var id = $(this).attr('id');
                        $.ajax({
                            url: "change-status.php",
                            data: {new_status: new_status, id:id},
                            type: "POST",
                            async: true,

                            success: function (data) {
                                alert('Status changed!');
                            }
                        });
                    });

                }

                var next_arrow ='<div class="row_card"><a href="photo_service.php?page='+suc_page+'&page_comment='+page0+'"><i class="fa fa-chevron-right" style="font-size: 130px"></i></a></div>';
                $('#cards').append($(next_arrow));


                //
            }
        });
    }
    function cdateSearch(date) {

        var values=[];
        document.getElementsByName('cfilter').forEach(function (item) {
            if((item.value)!=""){
                values.push(item.value);
            }
        });

        $('#comments').empty();
        var array = new Array();
       // alert(JSON.stringify(values));
        $.ajax({
            url: "get_comment_data.php",
            data: {values: values,limit:limit_init0, page_comment:page_init0,date:date},
            type: "POST",
            async: true,
            dataType: 'json',
            success: function (data){
                //////
                var prev_arrow ='<div class="row_card" style="display: inline-block; margin-left: 40px;"><a href="photo_service.php?page_comment='+prev_page0+'&page='+page_init+'"><i class="fa fa-chevron-left" style="font-size: 140px;"></i></a></i></div>';
                $('#comments').append($(prev_arrow));
                /////
                for (var i = 0; i < data.length; i++) {
                    array[i] = {
                        id: data[i]['id'],
                        serviceUri: data[i]['serviceUri'],
                        serviceName: data[i]['serviceName'],
                        uid: data[i]['uid'],
                        status: data[i]['status'],
                        timestamp: data[i]['timestamp'],
                        lat: data[i]['lat'],
                        lon: data[i]['lon'],
                        comment: data[i]['comment']
                    }

                    var greenIcon = new L.Icon({
                        iconUrl: 'img/marker-icon-2x-green.png',
                        shadowUrl: 'img/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });


                    var div_comments = document.getElementById('comments');
                    var cardComments= '<div class="row_card" style="display: inline-block; "><div class="card col-md-4" style="background-color: rgb(108, 135, 147);"> <div class="card-body"> <p class="card-text" style="font-size:0.7em; display:inline-block; word-wrap:break-word; max-width: 200px;">'+array[i].timestamp+'</p><br /><div style="display:inline-block; word-wrap:break-word; max-width: 200px;"><p style="display:inline-block; word-wrap:break-word;" class="card-text">'+array[i].comment+'</p><br /><form> <fieldset> <select id="comment-sel'+array[i].id+'" name="status" placeholder="'+array[i].status+'" index="'+i+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted">submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" style="font-size:0.7em;" id="loc'+i+'" placeholder="'+array[i].lat+', '+array[i].lon+'" >'+array[i].serviceName+'</p><p onclick="click_loc(this)"  onmouseover="underline(this)" onmouseout="normal(this)" class="card-text" id="location'+i+'" placeholder="'+array[i].lat+', '+array[i].lon+'" >view on map</p></div></div></div> ';
                    $('#comments').append($(cardComments));
                    //set Marker on Map
                    $('#'+array[i].id+' option[value='+array[i].status+']').attr('selected', 'selected');
                    $('#comm-sel'+i+' option[value='+array[i].status+']').attr('selected', 'selected');

                    if (((array[i].lat != null)&&(array[i].lon != null))){
                        var marker=L.marker([array[i].lat,array[i].lon],{icon:greenIcon}).addTo(mymap);
                        marker.bindPopup('<b>Comment:</b>'+array[i].comment);
                    }
                }
                var suc_arrow ='<div class="row_card" style="display: inline-block; vertical-align: middle; display: inline-block;  margin-left: 50px; margin-right: 50px;"><a href="photo_service.php?page_comment='+suc_page0+'&page='+page_init+'"><i class="fa fa-chevron-right" style="font-size: 130px;"></i></a></i></div>';
                $('#comments').append($(suc_arrow));

            }
        });

    }
</script>

</html>