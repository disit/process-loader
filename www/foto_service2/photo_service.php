<?php
		include ('config.php');
	include('curl.php');
	$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname);
//     $values=$_POST['values'];
     $values="";

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
	<!-- Dynatable -->
       <link rel="stylesheet" href="dynatable/jquery.dynatable.css" />
       <script src="dynatable/jquery.dynatable.js"></script>   
	<!-- -->
	<!-- Custom CSS -->
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/dashboardList.css" rel="stylesheet">
		<link href="css/raterater.css" rel="stylesheet">
		<!-- -->
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
	
	<div class="container-fluid">
		<div class="row mainRow" style='background-color: rgba(138, 159, 168, 1)'>
		<?php include "mainMenu.php" ?>
		<div class="col-xs-12 col-md-10" id="mainCnt" style="width: 100%;">
		<div class="row" id="title_row">
                        <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt">Photo Service</div>
                    </div>
        <div id="mapid" style="width: 100%; height: 30%;"></div>

    <div class="container-fluid" style="width: 100%; height: 30%;" id="list_dashboard_cards">
        <form>
		<div class="btn-group" role="group" aria-label="Basic example">
            <input type="button" name="filter" id="all" value="Reset" onclick="reload();" class="btn btn-primary">
			<div class="dropdown show">
			  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Filters
			  </a>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="validated" value=""  onchange="ffilterfunc(value,id)"> Validated</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="submitted" value=""  onchange="ffilterfunc(value,id)"> Submitted</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="rejected" value="" onchange="ffilterfunc(value,id)"> Rejected</a>
			</div>
			</div>
			</div>
        </form>
        
            <div class="card-columns" id="cards" >
	
			</div>
    </div>
	<!-- -->
    <div class="container-fluid" style="width: 100%; height: 30%;">
        <form>
		<div class="btn-group" role="group" aria-label="Basic example">
            <input type="button" name="cfilter" id="call" value="Reset" onclick="reload();" class="btn btn-primary">
			<div class="dropdown show">
			  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Filters
			  </a>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="cvalidated" value=""  onchange="filterfunc(value,id)"> Validated</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="csubmitted" value=""  onchange="filterfunc(value,id)"> Submitted</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="crejected" value="" onchange="filterfunc(value,id)"> Rejected</a>
			</div>
			</div>
			</div>
        </form>
        <div class="card-columns" id="comments"></div>
    </div>
	<!-- -->
     <script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlc3NhbmRyb2JhY2Npb3R0aW5pIiwiYSI6ImNqaWxoNjZ6MDJvdG4zd3BleDB5aGkxMjkifQ.ddcO3RVomvLGJZBQzu5quw';
    var map = new mapboxgl.Map({
    container: 'mapid',
    style: 'mapbox://styles/mapbox/streets-v10'
    });
    </script>
	</div>
	</div>
	</div>
    </body>
    <script type='text/javascript'>
			function myCardsWriter(rowIndex, record, columns, cellWriter){
			if(record.comment_id != 'null' ){
			var display = 'inline-block';
			}else{
			var display = 'none';
			}
			//var cardDiv = '<div class="row_card"><div class="card" style="width:200px; height:300px;"><center> <img id="img'+rowIndex+'" class="card-img-top" style="max-width:150px;max-height:150px; margin-top:50px; margin-bottom:20px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+record.file+'" alt="Card image"><div class="card-body"><p><img placeholder="img'+rowIndex+'" id="left_'+rowIndex+'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('+rowIndex+',0);"><img placeholder="img'+rowIndex+'" id="right_'+rowIndex+'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('+rowIndex+',0);"></p></center></div></div></div> ';
			//var cardDiv = '';
			
		var cardDiv = 	'<div class="row_card" style="background-color: white;">' +
						//'<center>'+
						'<img  src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+record.file+'" alt="Card image cap" style="max-width:150px;max-height:150px; margin-top:50px; margin-bottom:20px;">' +
						//'</center>'+
						'<p style="background-color: white;">Date: '+record.timestamp+'</p>'+
						//'<div class="card-body">'+
						//'<h5 class="card-title">'+record.timestamp+'</h5>' +
						//'<p class="card-text"><b>Comment:	</b>'+record.comment+'</p>' +
						//'<p class="card-text"><b>Date:	</b>'+record.timestamp+'</p>' +
						//'<p class="card-text"><b>Address:	</b>'+record.address+'</p>' +
						//'<p class="card-text"><b>Location:	</b>'+record.serviceName+'</p>' +
						///'<a href="#" class="btn btn-primary">Go somewhere</a>' +
						//'</div>' +
						'</div>';		
			return cardDiv;
			}
			
		function myCardsWriter2(rowIndex, record, columns, cellWriter){
			var cardDiv = '<div><div>'+record.comment+'</div></div>';
			if (record.status=='rejected'){
			 var cardDiv = '<div class="row"> <div class="col-md-4"> <div class="card" style="width:500px; height:100px;"> <div class="card-body"> <p class="card-text">'+record.timestamp+'</p><div><p style="display:inline-block" class="card-text">'+record.comment+'</p><form> <fieldset> <select id="comment-sel'+record.id+'" name="status" placeholder="'+record.status+'" index="'+rowIndex+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted">submitted  </option> <option value="rejected" selected="selected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" id="loc'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >'+record.serviceName+'</p> </div> </div> </div> </div> '; 
			}else if (record.status=='validated'){
			 var cardDiv = '<div class="row"> <div class="col-md-4"> <div class="card" style="width:500px; height:100px;"> <div class="card-body"> <p class="card-text">'+record.timestamp+'</p><div><p style="display:inline-block" class="card-text">'+record.comment+'</p><form> <fieldset> <select id="comment-sel'+record.id+'" name="status" placeholder="'+record.status+'" index="'+rowIndex+'" onchange="changeStatus2(id,value)"> <option value="validated" selected="selected">validated</option> <option value="submitted">submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" id="loc'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >'+record.serviceName+'</p> </div> </div> </div> </div> '; 
			}else{} 
			 var cardDiv = '<div class="row_card"><div class="card" style="width:500px; height:100px;"> <div class="card-body"> <p class="card-text">'+record.timestamp+'</p><div><p style="display:inline-block" class="card-text">'+record.comment+'</p><form> <fieldset> <select id="comment-sel'+record.id+'" name="status" placeholder="'+record.status+'" index="'+rowIndex+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted" selected="selected">submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" id="loc'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >'+record.serviceName+'</p> </div> </div> </div> '; 
			return cardDiv;
			}

		function ruota1(id,grado){
				deg=grado - 90;
				idimg= "img"+id;
				left="left_"+id;
				document.getElementById(idimg).style = 'max-width:150px; max-height:150px; margin-bottom:20px; margin-top:50px; transform: rotate(' + deg + 'deg)';
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
				//var id = id;
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
				document.getElementById(idimg).style = 'max-width:150px; max-height:150px; margin-bottom:20px; margin-top:50px; transform: rotate(' + deg + 'deg)';
				$("#"+right).attr("onclick","ruota2("+id+","+deg+")");
		}

        $(window).on('load', function(){

				var mymap = L.map('mapid').setView([43.797100067139, 11.252900123596], 14);
					L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
						attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
						maxZoom: 18,
						id: 'mapbox.streets',
						accessToken: 'pk.eyJ1IjoiYWxlc3NhbmRyb2JhY2Npb3R0aW5pIiwiYSI6ImNqaWxoNjZ6MDJvdG4zd3BleDB5aGkxMjkifQ.ddcO3RVomvLGJZBQzu5quw'
					}).addTo(mymap);

                var array = new Array();
                        $.ajax({
                        url: "get_photo_data.php",
                        data: {values: ""},
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
									// "address"=>$address,
									//"district"=>$province,
									//"city"=>$city,
                                    }

                                var div = document.getElementById('cards');
							//console.log(i+")	POSIZIONE:"+array[i].id+",	Longitudine:"+array[i].lat+",	Latitudine:"+array[i].lon);
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
                                    search: true,
                                    paginate:true,
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
							//
							//
                       // var marker=L.marker([array[i].lat,array[i].lon]).addTo(mymap);
                             //   marker.bindPopup(array[i].serviceName).openPopup();
								
						if ((array[i].lat != null)&&(array[i].lon != null)){
							var marker=L.marker([array[i].lat,array[i].lon]).addTo(mymap);
                              marker.bindPopup(array[i].serviceName).openPopup();
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
                            data: {values: ""},
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
																		perPageDefault: 3,
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
																		search: true,
																		paginate:true,
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
                data: {values: values},
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
																		search: true,
																		paginate:true,
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
                data: {values: values},
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
																		perPageDefault: 3,
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
																		search: true,
																		paginate:true,
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
        function reload() {
            window.location.href="index.html";
        }
		
    </script>

    </html>
