<?php

	include ('config.php');
	include('curl.php');
	$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname);
	$values=$_POST['values'];

if (isset($_GET['limit'])|| $_GET['limit']!==""){
$limit=$_GET['limit'];
$limit0=$_GET['limit'];
}else{
$limit = 6; 
$limit0 = 6;  
}
if ($_GET['limit'] == ""){
$limit = 6; 
$limit0 = 6;  
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
if ($_GET["page_comment"] == ""){
$page0=1;   
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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/v0.44.2/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v0.44.2/mapbox-gl.css' rel='stylesheet' />
       <link rel="stylesheet" href="dynatable/jquery.dynatable.css" />
       <script src="dynatable/jquery.dynatable.js"></script>   
	<!-- -->
	<link rel="stylesheet" href="photo_service.css" />
    <link rel="stylesheet" href="leaflet/leaflet.css" />
     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
       integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
       crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
       integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
       crossorigin=""></script>
	   
    <script src="leaflet/leaflet.js"></script>

    </head>
    <body>
	<!-- -->
	<!-- -->
    <div class="container-fluid" style="margin-bottom: 0.2%;">
       <div id="mapid" style=" height: 300px;"></div>
    </div>

    <div class="container-fluid" style="height: 25%; margin-bottom: 0.2%; margin-right: 0px; margin-left: 0px; " id="list_dashboard_cards">
        <h3 style="display: inline;">Photos: </h3>
        <form style="display: inline;">
		<div class="btn btn-xs btn-group" role="group" aria-label="Basic example">
            <input type="button" name="filter" id="all" value="Reset" onclick="reload_photo();" class="btn btn-primary btn-xs">
			<div class="dropdown show">
			  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Filters
			  </a>
			<div class="dropdown-menu btn btn-xs" aria-labelledby="dropdownMenuLink ">
            <a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="validated" value=""  onchange="ffilterfunc(value,id)"> Validated</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="submitted" value=""  onchange="ffilterfunc(value,id)"> Submitted</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="rejected" value="" onchange="ffilterfunc(value,id)"> Rejected</a>
			</div>
			</div>
			</div>
        </form>
		<!---->
				<div id="dynatable-pagination-links-cards">
											<?php 
											$total_records = $num;
											$total_pages = ceil($total_records / $limit);
												$prev_page = $page-1;
												$suc_page = $page+1;
												$corr_page= $page;

											if ($prev_page >=1){
											echo ('<a href="index.php?page='.$prev_page.'&page_comment='.$page0.'"><< 	Prev</a>');
											}
														if ($corr_page >6){
														$init_j = $corr_page -5;
														}else{$init_j = 1; 
														}
											for ($j=$init_j; $j<=$total_pages; $j++) { 
																if (($j<6)||(($corr_page-$j)>=0)||(($corr_page == $j)&&($corr_page < $total_pages-3))||(($corr_page >= $total_pages-3))){
															echo ("&#09;<a class='page_n' value='".$j."' href='index.php?page=".$j."&page_comment=".$page0."'>".$j."</a>&#09;");
																}
											}; 
											
											$last_pages = $total_pages-3;
											if (($total_pages > 8)&&($corr_page < $last_pages)){
											echo ("...");
											for ($y=$last_pages; $y<=$total_pages; $y++) {  
															echo ("&#09;<a class='page_n' value='".$y."' href='index.php?page=".$y."&page_comment=".$page0."'>".$y."</a>&#09;");	
														};
													}
											if ($suc_page <=$total_pages){
											echo ('	<a href="index.php?page='.$suc_page.'&page_comment='.$page0.'">Next 	>></a>');
											}
										?>
						</div>
		<!-- -->	
		<div class="row row-horizon" id="cards" style="width: 100%; text-align: center; float: left;"></div>
	    <!-- -->
		<!-- Modal -->
			
<!-- -->
    </div>	

    <div class="container-fluid" style="height: 25%; margin-bottom: 0.2%; margin-right: 0px; margin-left: 0px;" id="list_dashboard_comments">
        <h3 style="display: inline;">Comments: </h3>
        <form style="display: inline;">
		<div class="btn btn-xs btn-group" role="group" aria-label="Basic example">
            <input type="button" name="cfilter" id="call" value="Reset" onclick="reload_comment();" class="btn btn-primary btn-sm">
			<div class="dropdown show">
			  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Filters
			  </a>
			<div class="dropdown-menu btn btn-xs" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="cvalidated" value=""  onchange="filterfunc(value,id)"> Validated</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="csubmitted" value=""  onchange="filterfunc(value,id)"> Submitted</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="crejected" value="" onchange="filterfunc(value,id)"> Rejected</a>
			</div>
			</div>
			</div>
        </form>
		<!---->
				<div id="dynatable-pagination-links-comments">
											<?php 
											$total_records0 = $num0;
											$total_pages0 = ceil($total_records0 / $limit);
												$prev_page0 = $page0-1;
												$suc_page0 = $page0+1;
												$corr_page0= $page0;
											if ($prev_page0 >=1){
											echo ('<a href="index.php?page_comment='.$prev_page0.'&page='.$page.'"><< 	Prev</a>');
											}
														if ($corr_page0 >6){
														$init_j0 = $corr_page0 -5;
														}else{$init_j0 = 1; 
														}
											for ($i=$init_j0; $i<=$total_pages0; $i++) { 
																if (($i<6)||(($corr_page0-$i)>=0)||(($corr_page0 == $i)&&($corr_page0 < $total_pages0-3))||(($corr_page0 >= $total_pages0-3))){
															echo ("&#09;<a class='page_n2' value='".$i."' href='index.php?page_comment=".$i."&page=".$page."'>".$i."</a>&#09;");
																}
											}; 
											
											$last_pages0 = $total_pages0-3;
											if (($total_pages0 > 8)&&($corr_page0 < $last_pages0)){
											echo ("...");
											for ($z=$last_pages0; $z<=$total_pages0; $z++) {  
															echo ("&#09;<a class='page_n2' value='".$z."' href='index.php?page_comment=".$z."&page=".$page."'>".$z."</a>&#09;");	
														};
													}
											if ($suc_page0 <=$total_pages0){
											echo ('	<a href="index.php?page_comment='.$suc_page0.'&page='.$page.'">Next 	>></a>');
											}
										?>
						</div>
		<!-- -->
        <div class="row row-horizon" id="comments"></div>
    </div>

     <script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlc3NhbmRyb2JhY2Npb3R0aW5pIiwiYSI6ImNqaWxoNjZ6MDJvdG4zd3BleDB5aGkxMjkifQ.ddcO3RVomvLGJZBQzu5quw';
    var map = new mapboxgl.Map({
    container: 'mapid',
    style: 'mapbox://styles/mapbox/streets-v10'
    });
	var mymap = L.map('mapid').setView([43.797, 11.252],3.5);
					L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
						attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
						maxZoom: 18,
						id: 'mapbox.streets',
						accessToken: 'pk.eyJ1IjoiYWxlc3NhbmRyb2JhY2Npb3R0aW5pIiwiYSI6ImNqaWxoNjZ6MDJvdG4zd3BleDB5aGkxMjkifQ.ddcO3RVomvLGJZBQzu5quw'
					}).addTo(mymap);
    </script>


    </body>

    <script type='text/javascript'>
			var limit_init=<?=$limit; ?>;
			var page_init=<?=$page; ?>;
			<!-- -->
			var limit_init0=<?=$limit0; ?>;
			var page_init0=<?=$page0; ?>;
			<!-- -->
			function myCardsWriter(rowIndex, record, columns, cellWriter){
			if(record.comment_id != 'null' ){
			var display = 'inline-block';
			}else{
			var display = 'none';
			}
			//
			var cardModal = '<div id="myModal'+rowIndex+'" class="modal fade" role="dialog" ><div class="modal-dialog" ><div class="modal-content" style="background-color: white;"><div class="modal-body" style="background-color: white;"><img id="img'+rowIndex+'" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+record.file+'" alt="Card image" style="width:100%; height:auto"></div><div class="modal-footer" style="background-color: white;"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			//
			if (record.status=='rejected'){		
				var cardDiv = '<div class="row_card" style="display: inline-block; "><div class="col-md-4"> <div class="card" ><center><a data-toggle="modal" href="#myModal'+rowIndex+'" class="click_image" value="'+rowIndex+'"><img id="img'+rowIndex+'" class="card-img-top" style="width:160px; height:160px; margin-top:10px; margin-bottom:10px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+record.file+'" alt="Card image"></a></center><div class="card-body"><form style="display:inline-block"><fieldset><img placeholder="img'+rowIndex+'" id="left_'+rowIndex+'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('+rowIndex+',0);"> 	<select id="'+record.id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option> <option value="validated" >validated</option> <option value="rejected" selected="selected">rejected </option> </select>	<img placeholder="img'+rowIndex+'" id="right_'+rowIndex+'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('+rowIndex+',0);"> </fieldset> </form> <p class="card-text" style="font-size:0.7em;">'+record.timestamp+'</p> <p style="display:inline-block" class="card-text"> </div></div></div></div>'+cardModal;
			}else if (record.status=='validated'){	
				var cardDiv = '<div class="row_card" style="display: inline-block; "><div class="col-md-4"> <div class="card" ><center><a data-toggle="modal" href="#myModal'+rowIndex+'" class="click_image" value="'+rowIndex+'"><img id="img'+rowIndex+'" class="card-img-top" style="width:160px; height:160px; margin-top:10px; margin-bottom:10px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+record.file+'" alt="Card image"></a></center><div class="card-body"><form style="display:inline-block"><fieldset><img placeholder="img'+rowIndex+'" id="left_'+rowIndex+'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('+rowIndex+',0);"> 	<select id="'+record.id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted" selected="selected">submitted</option> <option value="validated" >validated</option> <option value="rejected" >rejected </option> </select>	<img placeholder="img'+rowIndex+'" id="right_'+rowIndex+'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('+rowIndex+',0);"> </fieldset> </form> <p class="card-text" style="font-size:0.7em;">'+record.timestamp+'</p> <p style="display:inline-block" class="card-text"> </div></div></div></div>'+cardModal;
			}else if (record.status=='submitted'){	
				var cardDiv = '<div class="row_card" style="display: inline-block; "><div class="col-md-4"> <div class="card" ><center><a data-toggle="modal" href="#myModal'+rowIndex+'" class="click_image" value="'+rowIndex+'"><img id="img'+rowIndex+'" class="card-img-top" style="width:160px; height:160px; margin-top:10px; margin-bottom:10px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+record.file+'" alt="Card image"></a></center><div class="card-body"><form style="display:inline-block"><fieldset><img placeholder="img'+rowIndex+'" id="left_'+rowIndex+'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('+rowIndex+',0);"> 	<select id="'+record.id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option> <option value="validated" selected="selected">validated</option> <option value="rejected">rejected </option> </select>	<img placeholder="img'+rowIndex+'" id="right_'+rowIndex+'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('+rowIndex+',0);"> </fieldset> </form> <p class="card-text" style="font-size:0.7em;">'+record.timestamp+'</p> <p style="display:inline-block" class="card-text"> </div></div></div></div>'+cardModal;
			}
				return cardDiv;
			}
			
		function myCardsWriter2(rowIndex, record, columns, cellWriter){
		
			if (record.status=='rejected'){
			 var cardDiv = '<div class="row_card" style="display: inline-block; "> <div class="col-md-4"> <div class="card" > <div class="card-body"> <p class="card-text" style="font-size:0.7em;">'+record.timestamp+'</p><div><p style="display:inline-block" class="card-text">'+record.comment+'</p><form> <fieldset> <select id="comment-sel'+record.id+'" name="status" placeholder="'+record.status+'" index="'+rowIndex+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted">submitted  </option> <option value="rejected" selected="selected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" style="font-size:0.7em;" id="loc'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >'+record.serviceName+'</p><p onclick="click_loc(this)"  onmouseover="underline(this)" onmouseout="normal(this)" class="card-text" id="location'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >view on map</p> </div> </div> </div> </div> '; 
			}else if (record.status=='validated'){
			 var cardDiv = '<div class="row_card" style="display: inline-block; "> <div class="col-md-4"> <div class="card" > <div class="card-body"> <p class="card-text" style="font-size:0.7em;">'+record.timestamp+'</p><div><p style="display:inline-block" class="card-text">'+record.comment+'</p><form> <fieldset> <select id="comment-sel'+record.id+'" name="status" placeholder="'+record.status+'" index="'+rowIndex+'" onchange="changeStatus2(id,value)"> <option value="validated" selected="selected">validated</option> <option value="submitted">submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" style="font-size:0.7em;" id="loc'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >'+record.serviceName+'</p><p onclick="click_loc(this)"  onmouseover="underline(this)" onmouseout="normal(this)" class="card-text" id="location'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >view on map</p> </div> </div> </div> </div> '; 
			}else if (record.status=='submitted'){
			 var cardDiv = '<div class="row_card" style="display: inline-block; "> <div class="col-md-4"> <div class="card" > <div class="card-body"> <p class="card-text" style="font-size:0.7em;">'+record.timestamp+'</p><div><p style="display:inline-block" class="card-text">'+record.comment+'</p><form> <fieldset> <select id="comment-sel'+record.id+'" name="status" placeholder="'+record.status+'" index="'+rowIndex+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted" selected="selected">submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" style="font-size:0.7em;" id="loc'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >'+record.serviceName+'</p><p onclick="click_loc(this)"  onmouseover="underline(this)" onmouseout="normal(this)" class="card-text" id="location'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >view on map</p> </div> </div> </div> </div> '; 
			} 
				return cardDiv;
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

							var marker = L.marker(loc_array,{
							color: 'red'}).addTo(mymap);
						}

			///////////////
		function ruota1(id,grado){
				deg=grado - 90;
				idimg= "img"+id;
				left="left_"+id;
				document.getElementById(idimg).style = 'width:160px; height:160px; margin-bottom:10px; margin-top:10px; transform: rotate(' + deg + 'deg)';
				$("#"+left).attr("onclick","ruota1("+id+","+deg+")");
		}

		function changeStatus(id,new_status) {
            $.ajax({
                url: "change-status.php",
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
                url: "change-status-comment.php",
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

        $(window).on('load', function(){
		//$( document ).ready(function() {
				//
				//var limit_init = 60;
				var pagina="<?=$page; ?>";
				//trovare il link con value uguale alla pagina e colorarlo di bianco.
				$("a.page_n[value='"+pagina+"']").css("text-decoration","underline");
				
				var page_comment="<?=$page0; ?>";
				//trovare il link con value uguale alla pagina e colorarlo di bianco.
				$("a.page_n2[value='"+page_comment+"']").css("text-decoration","underline");
				//
				

                var array = new Array();
                        $.ajax({
                        url: "get_photo_data.php",
                        data: {values: "", limit:limit_init, page:page_init},
                        type: "POST",
                        async: true,
                        dataType: 'json',
                        success: function (data) {
                           // alert();
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
                            $('#cards').dynatable({
                                table: {
                                    bodyRowSelector: 'div'
                                },
                                dataset: {
                                    records: [],
                                    perPageDefault: 6,
                                    perPageOptions: [4, 6, 12, 16, 20, 24, 28, 32]
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
                                    search: false,
                                    paginate:false,
                                    pushState: false
                                }
                            });

                        var dynatable = $('#cards').data('dynatable');
                            dynatable.sorts.clear();
                            dynatable.sorts.add('title_header', 1); // 1=ASCENDING, -1=DESCENDING
                            dynatable.settings.dataset.originalRecords = data;
                            dynatable.process();
                            dynatable.paginationPage.set(1);
                            dynatable.process();
								/*
							var myIcon = L.icon ({
								iconUrl: "verde.ico",
								 iconSize:     [60, 95],
								shadowSize:   [50, 64],
								iconAnchor:   [22, 94],
								shadowAnchor: [4, 62],
								popupAnchor:  [-3, -76]
							});*/
							/////////
							
							///////
						if ((array[i].lat != null)&&(array[i].lon != null)){
							var marker=L.marker([array[i].lat,array[i].lon]).addTo(mymap);
								marker.bindPopup('<center><img src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+array[i].file+'" style="max-width:200px;max-height:200px;"></img></center><br/><b>Comment:</b>'+array[i].comment+'<br/><b>Location:</b>'+array[i].serviceName+'<br>'+'<b>Address:</b>'+array[i].address+'<br>'+'<b>City:</b>'+array[i].city+'('+array[i].district+')');
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

                        }
                    });
                        $.ajax({
                            url: "get_comment_data.php",
                            data: {values: "",limit:limit_init0, page:page_init0},
                            type: "POST",
                            async: true,
                            dataType: 'json',
                            success: function (data){
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
										$('#comments').dynatable({
																	table: {
																		bodyRowSelector: 'div'
																	},
																	dataset: {
																		records: data,
																		perPageDefault: 6,
																		perPageOptions: [6, 12, 18, 24]
																	},
																	writers: {
																		_rowWriter: myCardsWriter2
																	},
																	inputs: {
																		paginationPrev: 'Previous',
																		paginationNext: 'Next',
																		paginationLinkPlacement: 'before'
																	},
																	features: {
																		recordCount: false,
																		perPageSelect: false,
																		search: false,
																		paginate:false,
																		pushState: false
																	}
															});

										var dynatable = $('#comments').data('dynatable');
										dynatable.sorts.clear();
										dynatable.sorts.add('title_header', 1); // 1=ASCENDING, -1=DESCENDING
										dynatable.settings.dataset.originalRecords = data;
										dynatable.process();			
										dynatable.paginationPage.set(1);
										dynatable.process();
                                    //set Marker on Map
                                    $('#'+array[i].id+' option[value='+array[i].status+']').attr('selected', 'selected');
                                    $('#comm-sel'+i+' option[value='+array[i].status+']').attr('selected', 'selected');
									

                                }

                            }
                        });
        });

    //TODO:Quando faccio comparire un marker dovrei andare direttamente su nella pagina.
    //TODO: controllare che compaia il valore dello status del commento.
    //TODO:Possibilità di mostrare più risultati.
    //TODO: Le foto invece che immagini, essere dei div che hanno come backgroud un immagine.
			/*$(".click_image").click(function(){
				//
				var value = $(this).attr('value');
				console.log('VLUE	:'+value);
			});*/
   
        function ffilterfunc(value,id) {

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

            $('#cards').empty();
            var array = new Array();

			//effettuare un controllo qui?
			if ( values === undefined ||  values.length == 0) {
						 values = "";
					}
			//
            $.ajax({
                url: "get_photo_data.php",
                data: {values: values, limit:limit_init, page:page_init},
                type: "POST",
                async: true,
                dataType: 'json',
                success: function (data) {
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
							//
							address: data[i]['address'],
                            district: data[i]['district'],
                            city:data[i]['city']
							//
                        }

                        var div = document.getElementById('cards');
													$('#cards').dynatable({
																	table: {
																		bodyRowSelector: 'div'
																	},
																	dataset: {
																		records: [],
																		perPageDefault: 6,
																		perPageOptions: [4, 6, 12, 16, 20, 24, 28, 32]
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
																		search: false,
																		paginate:false,
																		pushState: false
																	}
																});

																var dynatable = $('#cards').data('dynatable');
																dynatable.sorts.clear();
																dynatable.sorts.add('title_header', 1); // 1=ASCENDING, -1=DESCENDING
																dynatable.settings.dataset.originalRecords = data;
																dynatable.process();			
																dynatable.paginationPage.set(1);
																dynatable.process();

						}  
                }

                });
            }
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
                data: {values: values,limit:limit_init0, page:page_init0},
                type: "POST",
                async: true,
                dataType: 'json',
                success: function (data) {
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
											$('#comments').dynatable({
																	table: {
																		bodyRowSelector: 'div'
																	},
																	dataset: {
																		records: data,
																		perPageDefault: 6,
																		perPageOptions: [6, 12, 18, 24]
																	},
																	writers: {
																		_rowWriter: myCardsWriter2
																	},
																	inputs: {
																		paginationPrev: 'Previous',
																		paginationNext: 'Next',
																		paginationLinkPlacement: 'before'
																	},
																	features: {
																		recordCount: false,
																		perPageSelect: false,
																		search: false,
																		paginate:false,
																		pushState: false
																	}
															});

																var dynatable = $('#comments').data('dynatable');
																dynatable.sorts.clear();
																dynatable.sorts.add('title_header', 1); // 1=ASCENDING, -1=DESCENDING
																dynatable.settings.dataset.originalRecords = data;
																dynatable.process();			
																dynatable.paginationPage.set(1);
																dynatable.process();
                        
															}
												}
									});
            }
        function reload_photo() {
            window.location.href="index.php?page_comment="+page_init0;
        }
		
		function reload_comment() {
            window.location.href="index.php?page="+page_init;
        }
		
    </script>

    </html>