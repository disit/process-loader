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
include('curl.php');
$link = mysqli_connect($host_photo, $username_photo, $password_photo) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_photo);
if (isset($_POST['values'])) {
    $values = $_POST['values'];
} else {
    $values = null;
}


$sf = "";
if (isset($_SESSION['username'])) {
    $utente_att = $_SESSION['username'];
} else {
    $utente_att = "Login";
}

if (isset($_SESSION['username'])) {
    $role_att = $_SESSION['role'];
} else {
    $role_att = "";
}
//
if (isset($_REQUEST['showFrame'])) {
    if ($_REQUEST['showFrame'] == 'false') {
        $hide_menu             = "hide";
        $_SESSION['showFrame'] = $_REQUEST['showFrame'];
        $sf                    = "&showFrame=false";
    } else {
        $hide_menu = "";
        $sf        = "";
    }
} else {
    $hide_menu = "";
}
if (isset($_SESSION['showFrame'])) {
    $_SESSION['showFrame'] = $_REQUEST['showFrame'];
}

if (!isset($_GET['pageTitle'])) {
    $default_title = "Photo Service";
} else {
    $default_title = $_GET['pageTitle'];
}

if (isset($_REQUEST['redirect'])) {
    $access_denied = "denied";
} else {
    $access_denied = "";
}

if (isset($_GET['limit'])) {
    $limit  = $_GET['limit'];
    $limit0 = $_GET['limit'];
} else {
    $limit  = 7;
    $limit0 = 7;
}
if (isset($_GET['limit'])) {
    if ($_GET['limit'] == "") {
        $limit  = 7;
        $limit0 = 7;
    }
}
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
;
$start_from = ($page - 1) * $limit;


if ($values != null) {
    $value = "";
    for ($i = 0; $i < count($values); $i++) {
        if ($i == count($values) - 1) {
            $value = $value . "'" . $values[$i] . "'";
        } else {
            $value = $value . "'" . $values[$i] . "'" . " OR status=";
        }
        
    }
    $query = "SELECT * FROM ServicePhoto WHERE status=$value ";
} else {
    $query = "SELECT * FROM ServicePhoto ";
}


$result = mysqli_query($link, $query) or die(mysqli_error($link));
$list = array();
$num  = $result->num_rows;

if (isset($_GET["page_comment"])) {
    $page0 = $_GET["page_comment"];
} else {
    $page0 = 1;
}


$values0     = "";
$start_from0 = ($page0 - 1) * $limit0;
if ($values0 != null) {
    $value0 = "";
    for ($i = 0; $i < count($values0); $i++) {
        if ($i == count($values0) - 1) {
            $value0 = $value0 . "'" . $values0[$i] . "'";
        } else {
            $value0 = $value0 . "'" . $values0[$i] . "'" . " OR status=";
        }
        
    }
    $query0 = "SELECT * FROM ServiceComment WHERE status=$value ";
} else {
    $query0 = "SELECT * FROM ServiceComment ";
}


$result0 = mysqli_query($link, $query0) or die(mysqli_error($link));
$num0 = $result0->num_rows;



$total_records = $num;
$total_pages   = ceil($total_records / $limit);
$prev_page     = $page - 1;
$suc_page      = $page + 1;
$corr_page     = $page;


$total_records0 = $num0;
$total_pages0   = ceil($total_records0 / $limit);
$prev_page0     = $page0 - 1;
$suc_page0      = $page0 + 1;
$corr_page0     = $page0;
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
	   <!--
       <link rel="stylesheet" href="dynatable/jquery.dynatable.css">
       <script src="dynatable/jquery.dynatable.js"></script>
	   -->
        
       <!-- Font awesome icons -->
        <link rel="stylesheet" href="fontAwesome/css/font-awesome.min.css">
        <link href="https://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">  
        <!-- Custom CSS -->
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/dashboardList.css" rel="stylesheet">
		<link href="css/photo_service.css" rel="stylesheet">
		<!-- --> 
		<!-- -->
		<script type="text/javascript" src="js/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.js"></script>
		<link href="js/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.css" rel="stylesheet">
		<!-- -->
		<!-- INIT LEAFLET -->
		<link rel="stylesheet" href="leaflet/leaflet.css" />
		<script type="text/javascript" src="leaflet/leaflet.js"></script>
		<!-- END LEAFLET -->
	<!-- -->
    </head>
	<style>
		th {    
			background-color:transparent;
		}
		table{
			font-size: 0.9em;
			text-decoration:none;
		}
		
		
	</style>
	<body class="guiPageBody">
	<?php
include('functionalities.php');
?>
        <div class="container-fluid">
            <div class="row mainRow" style='background-color: rgba(138, 159, 168, 1)'>
               <?php
include("mainMenu.php");
?>
                <div class="col-xs-12 col-md-10" id="mainCnt">
                     <div class="row hidden-md hidden-lg">
                        <div id="mobHeaderClaimCnt" class="col-xs-12 hidden-md hidden-lg centerWithFlex">
                            Snap4City
                        </div>
                    </div>
                    <div class="row" id="title_row">
                        <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt"><?php
echo ($default_title);
?></div>
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php
include "mobMainMenu.php";
?></div>
                    </div>
                    <div class="row">
                        <div id="mainContentCnt">
                            <div class="row mainContentRow" id="dashboardsListTableRow" style="padding-top: 0; padding-bottom:0;">
                                <div class="col-xs-12 mainContentCellCnt" style='background-color: rgba(138, 159, 168, 1)'>  
									<div class="map-wrap">
											<div id="mapid"></div>
										</div>
										<!-- -->
									<div class="container-fluid"  id="list_dashboard_cards">
										<div class="header_title">
										<div style="float:left;margin-right: 1%;">
											Photos:
										</div>
										<!-- </div> -->
											<div>
												<form class="form-inline">
													<!-- inizio nuovo -->
													<div class="btn-group">
													<input type="button" name="Resetfilter" id="all" value="Reset" onclick="reset_photo()" class="btn btn-primary btn-sm">
														        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">Filters   <span class="caret"></span>
																</button>
																<ul class="dropdown-menu">
																	<li><a class="dropdown-item" ><input type="checkbox" name="filter" id="validated" value=""  onchange="filtro(0,value,id)"> Validated</a></li>
																	<li><a class="dropdown-item" ><input type="checkbox" name="filter" id="submitted" value=""  onchange="filtro(0,value,id)"> Submitted</a></li>
																	<li><a class="dropdown-item" ><input type="checkbox" name="filter" id="rejected" value="" onchange="filtro(0,value,id)"> Rejected</a></li>																
																</ul>
													</div>
													<!-- fine nuovo -->
													<div class="form-group-sm">
												<label for="inlineFormInputGroup" class="col-2 col-form-label"></label>
												<input class="form-control form-control-sm datepicker col-xs-2" value="" readonly id="example-date-input" onchange="filtroData(0)" placeholder="Filter from date...">
													</div>
												</form>
											</div>
										 </div> 
										<div class="row row-horizon" id="cards" >
										</div>
									</div>	 
									<!-- -->
									<div class="container-fluid" id="list_dashboard_comments">

										<div class="header_title">
											<div style="float:left;margin-right: 0.5%;">
												Comments:
											</div>
											<div>
												<form class="form-inline">											
														<div class="btn-group">
															<input type="button" name="Resetfilter" id="call" value="Reset" onclick="reset_comment()" class="btn btn-success btn-sm">
															<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">Filters   <span class="caret"></span>
															</button>
															<ul class="dropdown-menu">
																<li><a class="dropdown-item" ><input type="checkbox" name="cfilter" id="cvalidated" value=""  onchange="filtro(1,value,id)"> Validated</a></li>
																<li><a class="dropdown-item" ><input type="checkbox" name="cfilter" id="csubmitted" value=""  onchange="filtro(1,value,id)"> Submitted</a></li>
																<li><a class="dropdown-item" ><input type="checkbox" name="cfilter" id="crejected" value="" onchange="filtro(1,value,id)"> Rejected</a></li>																
															</ul>
														</div>
													<div class="form-group-sm">
													<label for="inlineFormInputGroup" class="col-2 col-form-label"></label>
													<input class="form-control form-control-sm datepicker col-xs-2" value="" readonly id="cexample-date-input" onchange="filtroData(1)" placeholder="Filter from date...">
													</div>
												</form>
											</div>

										</div> 
										<div class="row row-horizon" id="comments" ></div>
									</div>
									<!-- -->
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
        
	<script type='text/javascript'>
	
	var mymap = new L.Map('mapid');
		mymap.setView([43.769789, 11.255694],10);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
               attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
               maxZoom: 18,
			   minZoom: 3,
			   center: [43.769789, 11.255694],
			   closePopupOnClick: false
            }).addTo(mymap);
		mymap.attributionControl.setPrefix('');	
		setTimeout(mymap.invalidateSize.bind(mymap));
	//VARIABILI INIZIALI
	var nascondi= "<?= $hide_menu; ?>";
	var limit_init="<?= $limit; ?>";
	var page_init="<?= $page; ?>";
	var limit_init0="<?= $limit0; ?>";
	var page_init0="<?= $page0; ?>";
	var prev_page="<?= $prev_page; ?>";
	var prev_page0="<?= $prev_page0; ?>";
	var page0="<?= $page0; ?>";
	var page="<?= $page; ?>";
	var suc_page="<?= $suc_page; ?>";
	var suc_page0="<?= $suc_page0; ?>";
	var totale_pagine="<?= $total_pages; ?>";
	var totale_pagine0="<?= $total_pages0; ?>";
	var api_img="<?= $photo_api; ?>";
	var fs="<?= $sf ?>";	
	var markerGroupPhoto = [];
	var markerGroupComment = [];
			
		

	function ruota1(id,grado){
		deg=grado - 90;
		idimg= "img"+id;
		idimg_popup= "img"+id+"_popup";
		left="left_"+id;
		modal= 'modal_body'+id;
		modal_content = 'modal_content'+id;
		//document.getElementById(idimg).style = ' margin-bottom:15px; margin-top:15px; transform: rotate(' + deg + 'deg); vertical-align: middle;';
		/////
			var id_file = document.getElementById(idimg);
			var file_img = id_file.getAttribute('src');
			var elem_pop = document.getElementById(idimg_popup);
			var pop_img = elem_pop.getAttribute('src');
			var res = file_img.split("/");
			var l = res.length;
			var res2 = res[l-1].split("?");
			var image = res2[0];
			$.ajax({
				url: "get_data_photo_service.php",
				data: {direction:'left', image_name:image, action:'rotate-image'},
				success: function (data) {
						console.log(data);
						if (data.indexOf('OK') >= 0){
							var id_pop = idimg+'_popup';
							var id_modal = "modal_body"+id;
							//$('#'+idimg).attr('src',file_img);
							$('#'+idimg_popup).attr('src', pop_img +'?' + new Date().getTime());
							$("#"+left).attr("onclick","ruota1("+id+","+deg+")");
							$('#'+idimg).attr('src',file_img +'?' + new Date().getTime());
							//
							var marker = markerGroupPhoto[id];
							var popup = marker.getPopup();
							var content = popup.getContent();
							//MODIFICA
							var mySubString = content.substring(
													content.lastIndexOf("<center>"), 
													content.lastIndexOf("</center>")
												);
							console.log(mySubString);
							var new_string = ('<center><img class="pop_image" src="'+file_img+'?' + new Date().getTime() + '"></center>');
							var content2 = content.replace(mySubString, new_string);
							marker.bindPopup(content2);
							$('.pop_image').attr('src',file_img +'?' + new Date().getTime());
							//
						}else{
							console.log("ERROR");
						}
					}
			});
			////
			$('.pop_image').load(location.href + '.pop_image');
			//$('#'+idimg).load();

		
	}

	function changeStatus(id,new_status) {
		$.ajax({
			url: "get_data_photo_service.php",
			data: {new_status: new_status, id:id, action:'change-status'},
			type: "POST",
			async: true,
			success: function (data) {
				alert('Status changed');
				$('#'+id+' option[value='+new_status+']').attr("selected",true);
			}
		});
	}
	
	function changeStatus2(id,new_status) {
		id = id.replace("comment-sel","");
		$.ajax({
			url: "get_data_photo_service.php",
			data: {new_status: new_status, id:id, action:'statusComment'},
			type: "POST",
			async: true,
			success: function (data) {
				alert('Status Comment changed');
				$('#comment-sel'+id+' option[value='+new_status+']').attr("selected",true);
			}
		});
	}
	
	function ruota2(id,grado){
			deg=grado + 90;
			idimg= "img"+id;
			idimg_popup= "img"+id+"_popup";
			modal= 'modal_body'+id;
			modal_content = 'modal_content'+id;
			right="right_"+id;		
			//document.getElementById(idimg).style = ' margin-bottom:15px; margin-top:15px; transform: rotate(' + deg + 'deg); vertical-align: middle;';
			/////
			var id_file = document.getElementById(idimg);
			var file_img = id_file.getAttribute('src');
			var elem_pop = document.getElementById(idimg_popup);
			var pop_img = elem_pop.getAttribute('src');
			var res = file_img.split("/");
			var l = res.length;
			var res2 = res[l-1].split("?");
			var image = res2[0];
			$.ajax({
				url: "get_data_photo_service.php",
				data: {direction:'right', image_name:image, action:'rotate-image'},
				success: function (data) {
						console.log(data);
						if (data.indexOf('OK') >= 0){
							var id_pop = idimg+'_popup';
							var id_modal = "modal_body"+id;
							//
							$('#'+idimg_popup).attr('src', pop_img +'?' + new Date().getTime());
							$("#"+right).attr("onclick","ruota2("+id+","+deg+")");
							$('#'+idimg).attr('src',file_img +'?' + new Date().getTime());
							//
							var marker = markerGroupPhoto[id];
							var popup = marker.getPopup();
							var content = popup.getContent();
							marker.bindPopup(content);
							//MODIFICA
							var mySubString = content.substring(
													content.lastIndexOf("<center>"), 
													content.lastIndexOf("</center>")
												);
							console.log(mySubString);
							var new_string = ('<center><img class="pop_image" src="'+file_img+'?' + new Date().getTime() + '"></center>');
							var content2 = content.replace(mySubString, new_string);
							marker.bindPopup(content2);
							$('.pop_image').attr('src',file_img +'?' + new Date().getTime());
							//marker.closePopup();
							//
						}else{
							console.log("ERROR");
						}
					}
			});
			////
			$('.pop_image').load(location.href + '.pop_image');
			//$('#'+idimg).load();
	}	
	var pagina="<?= $page; ?>";
			$("a.page_n[value='"+pagina+"']").css("text-decoration","underline");		
	var page_comment="<?= $page0; ?>";
			$("a.page_n2[value='"+page_comment+"']").css("text-decoration","underline");

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
		var serviceName= $(p).attr('serviceName');
		var loc_array = JSON.parse("[" + loc + "]");
		var greenIcon = new L.Icon({
						iconUrl: 'img/marker-icon-2x-green.png',
						iconSize: [25, 41],
						iconAnchor: [12, 41],
						popupAnchor: [1, -34],
						shadowSize: [41, 41]
					});

		var marker = L.marker(loc_array,{icon:greenIcon}).addTo(mymap);
		var content = $(p).attr('content');
		marker.bindPopup('<b>Service Name:</b>'+serviceName+'<br /><b>Comment:</b>'+content);
		marker.openPopup();
	}

	$(document).ready(function () {
		//
		setTimeout(mymap.invalidateSize.bind(mymap),200)
		//
		var role="<?= $role_att; ?>";
		if (role == ""){
			$(document).empty();
			if(window.self !== window.top){
				window.location.href='https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';	
			}
			else
			{
			window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
			}
		}
		var role_active = "<?= $process['functionalities'][$role]; ?>";

			if (role_active == 0){
						$(document).empty();
						if(window.self !== window.top){
						window.location.href='https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';						
						}
						else{
						window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
						}
					}
					
		$(function(){	
			$('.datepicker').datetimepicker({
				format: 'yyyy-mm-dd',
				language: 'en',
				pickTime: false,
				minView: "month",
				pickerPosition: "top-left"
			});
		});
		
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#mainCnt').removeClass('col-md-10');
			$('#mainCnt').addClass('col-md-12');
		}
		
		var array = new Array();
		
			$.ajax({
			url:'get_data_photo_service.php',
			data: {limit:limit_init, page:page_init, action:'get_photo_data'},
			type: "POST",
			async: true,
			dataType: 'json',
			success: function (data) {
				if ((page_init == '1')||(page_init == '')||(page_init == '0')) {
					var prev_arrow ='<div class="row_card arrow"  onclick="move('+prev_page+','+page0+','+0+')"><a><i class="fa fa-chevron-left" style="visibility:hidden;"></i></a></div>';
				}else{
					var prev_arrow ='<div class="row_card arrow" onclick="move('+prev_page+','+page0+','+0+')"><a><i class="fa fa-chevron-left" ></i></a></div>';
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
					
					
					for (var k = 0; k < count; k++){	
							if((array[i].lat == array[k].lat)&&(array[i].lon == array[k].lon)){
									array[i].lat = Number(array[i].lat) + Number((Math.random() - .4) / 1000);
									array[i].lon = Number(array[i].lon) + Number((Math.random() - .4) / 1000);
								}else{
									array[i].lat = array[i].lat;
									array[i].lon = array[i].lon;
								}
							}
					count++;
					
					var div = document.getElementById('cards');
					if (array[i].file == ""){
						var n_api = "";
					}else{
						var n_api = api_img+array[i].file;
					}
					//
					var string_file = n_api.replace("thumbs/","");
					var color_fly = '#cc3333';
					var tooltip_text = 'Position not found';
								if ((array[i].lat != null)&&(array[i].lon != null)){
									color_fly = '#33cc33';
									tooltip_text = 'View on map';
								}else{
									color_fly = '#cc3333';
									tooltip_text = 'Position not found';
								}
					//
					var cardModal = '<div id="myModal'+i+'" class="modal fade" role="dialog" style="max-height:100%; height: auto;">';
						cardModal = cardModal + '<div class="modal-dialog" ><div class="modal-content" id="modal_content'+i+'">';
						cardModal = cardModal +'<div class="modal-body" id="modal_body'+i+'"><img id="img'+i+'_popup" src="'+string_file+'" class="modal_image" alt="Card image" ></div>';
						cardModal = cardModal +'<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal" >Close</button></div></div></div></div>';
					
					var cardDiv = '<div class="row_card" > <div class="card col-md-4">';
						cardDiv = cardDiv +'<div class="card-body">';	
						cardDiv = cardDiv +' <p class="card-text-card">'+array[i].timestamp+'</p>';
						cardDiv = cardDiv +'<form><fieldset>';
						cardDiv = cardDiv +'<img placeholder="img'+i+'" id="left_'+i+'"  src="img/rotate2.svg" class="card-img-rot1" alt="Card image rotation" grado="0" onclick="ruota1('+i+',0);"> 	';
						cardDiv = cardDiv +'<select id="'+array[i].id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option> <option value="validated" >validated</option> <option value="rejected" selected="selected">rejected</option> </select>	';
						cardDiv = cardDiv +'<img placeholder="img'+i+'" id="right_'+i+'"  src="img/rotate1.svg" grado="0" class="card-img-rot2" alt="Card image rotation" onclick="ruota2('+i+',0);"> </fieldset></form>';
						cardDiv = cardDiv +'<a href="#"><i class="fa fa-circle" style="font-size:12px; color:'+color_fly+'; vertical-align: top; margin-top: 10px; float:left;" onclick="func_fly('+array[i].lat+','+array[i].lon+','+i+')" data-toggle="tooltip" data-placement="bottom" title="'+tooltip_text+'"></i></a>';
						if(n_api !=""){
						cardDiv = cardDiv + '<a data-toggle="modal" href="#myModal'+i+'" class="click_image" value="'+i+'">';
						cardDiv = cardDiv +'<img id="img'+i+'" class="card-img-top" src="'+n_api+'" alt="Card image"></a>';												
						}
						cardDiv = cardDiv +'<p style="display:inline-block" class="card-text-card"> </div></div></div>'+cardModal;
					$('#cards').append($(cardDiv));

					if ((array[i].lat != null)&&(array[i].lon != null)){
						var marker = L.marker([array[i].lat,array[i].lon],{id:i});
							marker.bindPopup('<center><img class="pop_image" src="'+api_img+array[i].file+'"></img></center><br/><b>Comment:</b>'+array[i].comment+'<br/><b>Location:</b>'+array[i].serviceName+'<br>'+'<b>Address:</b>'+array[i].address+'<br>'+'<b>City:</b>'+array[i].city+'('+array[i].district+')');
							markerGroupPhoto.push(marker);
					}
					$('#'+array[i].id+' option[value='+array[i].status+']').attr('selected', true);
					$('#comm-sel'+i+' option[value='+array[i].comment_status+']').attr('selected', true);
						
					$('#'+array[i].id).change(function(){
						var new_status = $(this).find(":selected").text();
						var id = $(this).attr('id');
						$.ajax({
							url:'get_data_photo_service.php',
							data: {new_status: new_status, id:id, action:'change-status'},
							type: "POST",
							async: true,
							success: function (data) {
								//alert('Status changed!');
							}
						});
					});
					
				}
				//				
				var next_arrow ='<div class="row_card arrow" onclick="move('+suc_page+','+page0+',0);" ><a><i class="fa fa-chevron-right"></i></a></div>';
				$('#cards').append($(next_arrow));
				photoMarkers = L.layerGroup(markerGroupPhoto).addTo(mymap);
				//
			}
		});
		
		//////////
		var count0=0;
		var array0=new Array();
		$.ajax({
			url:'get_data_photo_service.php',
			data: {limit:limit_init0, page_comment:page_init0, action:'get_comment_data'},
			type: "POST",
			async: true,
			dataType: 'json',
			success: function (data){
				if ((page_init0 == '1')||(page_init0 == '')|| (page_init0 == '0')) {
						var prev_arrow ='<div class="row_card card_comment arrow" onclick="move('+page+','+prev_page0+','+1+')"><a><i class="fa fa-chevron-left" style="visibility:hidden;"></i></a></div>';

				}
				else{
					var prev_arrow ='<div class="row_card card_comment arrow" onclick="move('+page+','+prev_page0+','+1+')"><a><i class="fa fa-chevron-left"></i></a></div>';
				}
				$('#comments').append($(prev_arrow));

				for (var i = 0; i < data.length; i++) {
					array0[i] = {
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
				// PER "SPARPAGLIARE" I MARKER CON STESSE COORDINATE
						for (var k = 0; k < count0; k++){	
								if((array0[i].lat == array0[k].lat)&&(array0[i].lon == array0[k].lon)){
										array0[i].lat = Number(array0[i].lat) + Number((Math.random() - .4) / 1000);
										array0[i].lon = Number(array0[i].lon) + Number((Math.random() - .4) / 1000);
									}else{
										array0[i].lat = array0[i].lat;
										array0[i].lon = array0[i].lon;
									}
							}
						count0++;

					var greenIcon = new L.Icon({
						iconUrl: 'img/marker-icon-2x-green.png',
						shadowUrl: 'img/marker-shadow.png',
						iconSize: [25, 41],
						iconAnchor: [12, 41],
						popupAnchor: [1, -34],
						shadowSize: [41, 41]
					});
				
					
					var div_comments = document.getElementById('comments');									
					var cardComments= '<div class="row_card card_comment_div"><div class="card col-md-4" >';
						cardComments= cardComments+'<div class="card-body card-body-cm"> <p class="card-text">'+array0[i].timestamp+'</p><br />';
						cardComments= cardComments+'<form> <fieldset> <select id="comment-sel'+array0[i].id+'" name="status" placeholder="'+array0[i].status+'" index="'+i+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted" >submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form>';
						cardComments= cardComments+'<div><p class="card-text serviceName_comm" id="loc'+i+'" placeholder="'+array0[i].lat+', '+array0[i].lon+'" >'+array0[i].serviceName+'</p><br />';
						cardComments= cardComments+'<p class="card-text text_comm" serviceName="'+array0[i].serviceName+'" onclick="click_loc(this)"  onmouseover="underline(this)" onmouseout="normal(this)" class="card-text" id="location'+i+'" content="'+array0[i].comment+'" placeholder="'+array0[i].lat+', '+array0[i].lon+'">'+array0[i].comment+'</p>';					
						cardComments= cardComments+'</div></div></div></div> '; 
						$('#comments').append($(cardComments));
					//set Marker on Map
					$('#'+array0[i].id+' option[value='+array0[i].status+']').attr('selected', 'selected');
					$('#comment-sel'+array0[i].id+' option[value='+array0[i].status+']').attr('selected', 'selected');
				
					if (((array0[i].lat != null)&&(array0[i].lon != null))){
						var marker = L.marker([array0[i].lat,array0[i].lon],{icon:greenIcon});
					
						marker.bindPopup('<b>Service Name:</b>'+array0[i].serviceName+'</br><b>Comment:</b>'+array0[i].comment+'');
						markerGroupComment.push(marker);
					}
				}				
				var suc_arrow ='<div class="row_card card_comment arrow" onclick="move('+page+','+suc_page0+',1)" ><a><i class="fa fa-chevron-right"></i></a></div>';
				$('#comments').append($(suc_arrow));
				commentMarkers = L.layerGroup(markerGroupComment).addTo(mymap);
			}
		});
		
			
	});

	
	function reset_photo(){
		$("input[id=example-date-input]").val("");
		document.getElementsByName('filter').forEach(function (item) {
			item.checked=false;
			item.value='';
		});
		mymap.removeLayer(photoMarkers);
		move(1,page0,0);
	}
	
	function reset_comment(){
		$("input[id=cexample-date-input]").val("");
		document.getElementsByName('cfilter').forEach(function (item) {
			item.checked=false;
			item.value='';
		});
		mymap.removeLayer(commentMarkers);
		move(page,1,1);
	}
	
	function filtro(mode,value,id){
		if(mode==0){
			if(value==""){
				document.getElementById(id).value=id;
			}
			else{
				document.getElementById(id).value="";
			}
			var date=document.getElementById('example-date-input').value;
			mymap.removeLayer(photoMarkers);
			move(1,page0,0);		
		}
		else{
			id2=id.slice(1,id.length);
			if(value==""){
				document.getElementById(id).value=id2;
			}else{
				document.getElementById(id).value="";
			}
			var cdate = document.getElementById('cexample-date-input').value;
			mymap.removeLayer(commentMarkers);
			move(page,1,1);
		}
	}
	
	function filtroData(mode){
		if(mode==0){
			mymap.removeLayer(photoMarkers);
			move(1,page0,0);
		}
		else{
			mymap.removeLayer(commentMarkers);
			move(page,1,1);
		}
	}
	
	function move(page_p,page_c,mode){     // MODE: 0 -> photo    1 -> comment
		if(mode==0)
		{	
			suc_page = page_p + 1;
			prev_page = page_p -1;
			page = page_p;
			markerGroupPhoto = [];
			tot_page = 0;		
			var im_filters=[];
			document.getElementsByName('filter').forEach(function (item) {
				if((item.value)!=""){
					im_filters.push(item.value);
				}
			});

			var date=document.getElementById('example-date-input').value;
			//
			if (date ==""){
				date = '1970-01-01';
			}
			//
			$.ajax({
				url:'get_data_photo_service.php',
				data: {values: im_filters, limit:limit_init, page:page_p, date:date, action: 'get_photo_data'},
				type: "POST",
				async: true,
				dataType: 'json',
				success: function (data) {
						if ((page == '1')||(page == '')||(page == '0')) {
							var prev_arrow ='<div class="row_card arrow" onclick="move('+prev_page+','+page0+','+0+')"><a><i class="fa fa-chevron-left" style="visibility:hidden;"></i></a></div>';
						}
						else{		  
							var prev_arrow ='<div class="row_card arrow" onclick="move('+prev_page+','+page0+','+0+')"><a><i class="fa fa-chevron-left"></i></a></div>';
						
						}
					$('#cards').empty();
					var array = new Array();
					$('#cards').append($(prev_arrow));
					var count=0;
					//
					tot_page = data[0]['total_pages'];
					//
					mymap.removeLayer(photoMarkers);					
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
								
								for (var k = 0; k < count; k++){	
								if((array[i].lat == array[k].lat)&&(array[i].lon == array[k].lon)){
										array[i].lat = Number(array[i].lat) + Number((Math.random() - .4) / 1000);
										array[i].lon = Number(array[i].lon) + Number((Math.random() - .4) / 1000);
									}else{
										array[i].lat = array[i].lat;
										array[i].lon = array[i].lon;
									}
								}
								count++;
								//
									
								var div = document.getElementById('cards');
								if (array[i].file == ""){
											var n_api = "";
										}else{
											var n_api = api_img+array[i].file;
										}
								//
								var color_fly = '#cc3333';
								var tooltip_text = 'Position not found';
											if ((array[i].lat != null)&&(array[i].lon != null)){
												color_fly = '#33cc33';
												tooltip_text = 'View on map';
											}else{
												color_fly = '#cc3333';
												tooltip_text = 'Position not found';
											}
								//
								var string_file = n_api.replace("thumbs/","");
								var cardModal = '<div id="myModal'+i+'" class="modal fade" role="dialog" style="max-height:100%; height: auto;"><div class="modal-dialog" ><div class="modal-content" id="modal_content'+i+'"><div class="modal-body" id="modal_body'+i+'"><img id="img'+i+'_popup" src="'+string_file+'" class="modal_image" alt="Card image"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal" >Close</button></div></div></div></div>';				
								var cardDiv = '<div class="row_card"> <div class="card col-md-4">';
								cardDiv = cardDiv +'<div class="card-body">';	
								cardDiv = cardDiv +' <p class="card-text-card">'+array[i].timestamp+'</p>';
								cardDiv = cardDiv +'<form class="form_card"><fieldset>';
								cardDiv = cardDiv +'<img placeholder="img'+i+'" id="left_'+i+'"  src="img/rotate2.svg" class="card-img-rot1" alt="Card image rotation" grado="0" onclick="ruota1('+i+',0);"> 	';
								cardDiv = cardDiv +'<select id="'+array[i].id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option> <option value="validated" >validated</option> <option value="rejected" selected="selected">rejected</option> </select>	';
								cardDiv = cardDiv +'<img placeholder="img'+i+'" id="right_'+i+'"  src="img/rotate1.svg" grado="0" class="card-img-rot2" alt="Card image rotation" onclick="ruota2('+i+',0);"> </fieldset></form>';
								cardDiv = cardDiv +'<a href="#"><i class="fa fa-circle" style="font-size:12px; color:'+color_fly+'; vertical-align: top; margin-top: 10px; float:left;" onclick="func_fly('+array[i].lat+','+array[i].lon+','+i+')" data-toggle="tooltip" data-placement="bottom" title="'+tooltip_text+'"></i></a>';
								if(n_api !=""){
								cardDiv = cardDiv + '<a data-toggle="modal" href="#myModal'+i+'" class="click_image" value="'+i+'">';
								cardDiv = cardDiv +'<img id="img'+i+'" class="card-img-top"  src="'+n_api+'" alt="Card image"></a>';
								}								
								cardDiv = cardDiv +'<p style="display:inline-block" class="card-text-card"> </div></div></div>'+cardModal;
								$('#cards').append($(cardDiv));
								//	
							
								if ((array[i].lat != null)&&(array[i].lon != null)){
									var marker = L.marker([array[i].lat,array[i].lon],{id:i});
									
										marker.bindPopup('<center><img src="'+api_img+array[i].file+'" class="pop_image"></img></center><br/><b>Comment:</b>'+array[i].comment+'<br/><b>Location:</b>'+array[i].serviceName+'<br>'+'<b>Address:</b>'+array[i].address+'<br>'+'<b>City:</b>'+array[i].city+'('+array[i].district+')');
										markerGroupPhoto.push(marker);
								}
								
								$('#'+array[i].id+' option[value='+array[i].status+']').attr('selected', true);
								$('#comm-sel'+i+' option[value='+array[i].comment_status+']').attr('selected', true);
								
								$('#'+array[i].id).change(function(){
									var new_status = $(this).find(":selected").text();
									var id = $(this).attr('id');
									$.ajax({
										url: "get_data_photo_service.php",
										data: {new_status: new_status, id:id, action:'change-status'},
										type: "POST",
										async: true,
										success: function (data) {
											//alert('Status changed!');
										}
									});
								});
								
							}

						if(page+1 > tot_page){
							suc_page=page+1;		
							var next_arrow ='<div class="row_card arrow" onclick="move('+suc_page+','+page0+','+0+')"><a><i class="fa fa-chevron-right" style="visibility:hidden;"></i></a></div>';
							$('#cards').append($(next_arrow));
						}else{
							suc_page=page+1;		
							var next_arrow ='<div class="row_card arrow" onclick="move('+suc_page+','+page0+','+0+')"><a><i class="fa fa-chevron-right"></i></a></div>';
							$('#cards').append($(next_arrow));
						}
					photoMarkers = L.layerGroup(markerGroupPhoto).addTo(mymap);				

				}
			});

			
		}
		else{
			suc_page0 = page_c + 1;
			prev_page0 = page_c -1;
			page0 = page_c;
			markerGroupComment = [];
			tot_page0 = 0;
			var comm_filters=[];
			document.getElementsByName('cfilter').forEach(function (item) {
				if((item.value)!=""){
					comm_filters.push(item.value);
				}
			});
			
			var cdate=document.getElementById('cexample-date-input').value;
			if (cdate ==""){
				cdate = '1970-01-01';
			}
			var array0 = new Array();
			$.ajax({
				url:'get_data_photo_service.php',
				data: {values: comm_filters,limit:limit_init0, page_comment:page_c, date:cdate, action:'get_comment_data'},
				type: "POST",
				async: true,
				dataType: 'json',
				success: function (data){	
					if ((page_c == '1')||(page_c == '')||(page_c == '0')) {
						var prev_arrow ='<div class="row_card card_comment arrow" onclick="move('+page+','+prev_page0+','+1+')"><a><i class="fa fa-chevron-left" style="visibility:hidden;"></i></a></div>';
					}
					else{
						var prev_arrow ='<div class="row_card card_comment arrow" onclick="move('+page+','+prev_page0+','+1+')"><a><i class="fa fa-chevron-left" ></i></a></div>';
					}
					$('#comments').empty();
					var count0=0;
					$('#comments').append($(prev_arrow));
					mymap.removeLayer(commentMarkers);
					tot_page0 = data[0]['total_pages'];
					//
					for (var i = 0; i < data.length; i++) {
						array0[i] = {
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
						// PER "SPARPAGLIARE" I MARKER CON STESSE COORDINATE
						
						for (var k = 0; k < count0; k++){	
								if((array0[i].lat == array0[k].lat)&&(array0[i].lon == array0[k].lon)){
										array0[i].lat = Number(array0[i].lat) + Number((Math.random() - .4) / 1000);
										array0[i].lon = Number(array0[i].lon) + Number((Math.random() - .4) / 1000);
									}else{
										array0[i].lat = array0[i].lat;
										array0[i].lon = array0[i].lon;
									}
							}
						count0++;
						
						var greenIcon = new L.Icon({
								iconUrl: 'img/marker-icon-2x-green.png',
								shadowUrl: 'img/marker-shadow.png',
								iconSize: [25, 41],
								iconAnchor: [12, 41],
								popupAnchor: [1, -34],
								shadowSize: [41, 41]
						});
							
						var div_comments = document.getElementById('comments');
						var cardComments= '<div class="row_card card_comment_div"><div class="card col-md-4">';
						cardComments= cardComments+'<div class="card-body card-body-cm"> <p class="card-text">'+array0[i].timestamp+'</p><br />';
						cardComments= cardComments+'<form> <fieldset> <select id="comment-sel'+array0[i].id+'" name="status" placeholder="'+array0[i].status+'" index="'+i+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted" >submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form>';
						cardComments= cardComments+'<div><p class="card-text serviceName_comm" id="loc'+i+'" placeholder="'+array0[i].lat+', '+array0[i].lon+'" >'+array0[i].serviceName+'</p><br />';
						cardComments= cardComments+'<p class="card-text text_comm"   onclick="click_loc(this)" serviceName="'+array0[i].serviceName+'"  onmouseover="underline(this)" onmouseout="normal(this)" class="card-text" id="location'+i+'" content="'+array0[i].comment+'" placeholder="'+array0[i].lat+', '+array0[i].lon+'">'+array0[i].comment+'</p>';					
						cardComments= cardComments+'</div></div></div></div> '; 
						$('#comments').append($(cardComments));
						//set Marker on Map
						$('#'+array0[i].id+' option[value='+array0[i].status+']').attr('selected', 'selected');
						$('#comment-sel'+array0[i].id+' option[value='+array0[i].status+']').attr('selected', 'selected');
						
						if (((array0[i].lat != null)&&(array0[i].lon != null))){

							var marker = L.marker([array0[i].lat,array0[i].lon],{icon:greenIcon});
							
								marker.bindPopup('<b>Service Name:</b>'+array0[i].serviceName+'</br><b>Comment:</b>'+array0[i].comment+'');
								markerGroupComment.push(marker);
						}
					}
					if(page0+1<=tot_page0){
						suc_page0=page0+1;
						var suc_arrow ='<div class="row_card card_comment arrow" onclick="move('+page+','+suc_page0+','+1+')"><a><i class="fa fa-chevron-right"></i></a></div>';
						$('#comments').append($(suc_arrow));
					}else{
						suc_page0=page0+1;
						var suc_arrow ='<div class="row_card card_comment arrow" onclick="move('+page+','+suc_page0+','+1+')"><a><i class="fa fa-chevron-right" style="visibility:hidden"></i></a></div>';
						$('#comments').append($(suc_arrow));
					}
					commentMarkers = L.layerGroup(markerGroupComment).addTo(mymap);
				}
			});

		}
		
	}
	
	function func_fly(lat,lon,index){
				var pos = [lat,lon];
				mymap.flyTo(pos, 14);
				var marker = markerGroupPhoto[index];
				var popup = marker.getPopup();
				var content = popup.getContent();
				marker.openPopup();
				var elem_pop = document.getElementsByClassName('pop_image');
				var file_img = elem_pop[0].getAttribute('src');
				$('.pop_image').attr('src',file_img +'?' + new Date().getTime());
				//
				
				
		}
		
	
       
	   
setTimeout(mymap.invalidateSize.bind(mymap));

</script>
</body>
</html>