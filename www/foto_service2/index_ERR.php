    <!DOCTYPE html>
	<?php
	include ('config.php');
	include('curl.php');
	$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname);
     $values="";
	 if (isset($_POST['limit'])){
		$limit=$_POST['limit'];
	 }else{
		$limit=6; 
	 }
	
if (isset($_GET["page"])) { 
		$page  = $_GET["page"]; 
	} else { 
		$page=1; 
	};  
	
$start_from = ($page-1) * $limit; 	
	 
	 
  if(($values!=null)||($values!="")||(!empty($values))){
    $value="";
    for($i=0;$i<count($values);$i++){
        if($i==count($values)-1){
            $value=$value."'".$values[$i]."'";
        } else{$value=$value."'".$values[$i]."'"." OR status=";}
    }
	    $query = "SELECT * FROM ServicePhoto WHERE status=$value ORDER BY timestamp DESC LIMIT $start_from, $limit";
    }
    else{$query = "SELECT * FROM ServicePhoto ORDER BY timestamp DESC LIMIT $start_from, $limit";}

    $result = mysqli_query($link, $query) or die(mysqli_error($link));
	$list = array();
	$num = $result->num_rows;
	if ($num > 0) {
		while ($row1 = mysqli_fetch_array($result)) {
					$url = $row1['serviceUri'];
                    $url=$url.'&format=json';
					$preUrl='https://servicemap.disit.org/WebAppGrafo/api/v1/?serviceUri=';
					$url=$preUrl.$url;
                    $ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$result2 = curl_exec($ch);
					if (curl_errno($ch)) {
						echo 'Error:' . curl_error($ch);
					}
					curl_close ($ch);
                    $jsonfinale=json_decode($result2, true);

                    $coord[1]=$jsonfinale['Service']['features']['0']['geometry']['coordinates']['0'];
                    $coord[0]=$jsonfinale['Service']['features']['0']['geometry']['coordinates']['1'];
                    $city=$jsonfinale['Service']['features']['0']['properties']['city'];
                    $province=$jsonfinale['Service']['features']['0']['properties']['province'];
                    $address=$jsonfinale['Service']['features']['0']['properties']['address'];

					$time = $row1['timestamp'];				
					$newtimestamp = strtotime("$time + 1 minute");
					$data1=date('Y-m-d H:i:s', $newtimestamp);					
					$newtimestamp2 = strtotime("$time - 1 minute");
					$data2=date('Y-m-d H:i:s', $newtimestamp2);					
					$currentDate = strtotime($time );
					$pastDate = $currentDate-(60*1);
					$formatDate2 = date("Y-m-d H:i:s", $pastDate);	
					$futureDate = $currentDate+(60*1);
					$formatDate1 = date("Y-m-d H:i:s", $futureDate);						
					$query_comment = "SELECT * FROM ServiceComment WHERE timestamp<'$data1' AND timestamp>'$data2'";
					$comment_res = mysqli_query($link, $query_comment) or die(mysqli_error($link));
					if($comment_res->num_rows>0){
						$row = mysqli_fetch_array($comment_res);
						$comment=$row['comment'];
						$comment_id=$row['id'];
						$comment_status=$row['status'];
						
					}
					else{
						$comment='no comments';
						$comment_id='null';
						$comment_status='null';
					}

				$listFile = array(
					"id" => $row1['id'],
					"serviceUri" => $row1['serviceUri'],
					"serviceName" => $row1['serviceName'],
					"uid" => $row1['uid'],
					"file" => $row1['file'],
					"status" => $row1['status'],
					"ip" => $row1['ip'],
					"timestamp" => $row1['timestamp'],
					"lat" => $coord[0],
					"lon" => $coord[1],
					"comment"=>$comment,
					"comment_id"=>$comment_id,
					"address"=>$address,
					"district"=>$province,
					"city"=>$city,
					"comment_status"=>$comment_status
				);
		array_push($list, $listFile);
		}
	}
	
	json_encode($list);
	$num_rows = $list->num_rows;
	
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
	<!--<div id="title_div"  class="container-fluid"><h1>Photo Service</h1></div>-->
    <div class="container-fluid" style="height: 100%;">
       <div id="mapid" style="width: 100%; height: 300px;"></div>
    </div>


    <div class="container-fluid" style="width: 100%; height: 30%;" id="list_dashboard_cards">
        <!--<h2>Photos: </h2>-->
        <form>
		<div class="btn btn-sm btn-group" role="group" aria-label="Basic example">
            <input type="button" name="filter" id="all" value="Reset" onclick="reload();" class="btn btn-primary btn-sm">
			<div class="dropdown show">
			  <a class="btn btn-primary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
        
            <div class="row row-horizon" id="cards" >
			<?php
			$max = sizeof($list);
			for ($i=0;$i<$max;$i++){
			if ($list[$i]['status']=='rejected'){
					echo('<div class="row"> <div class="col-md-4"><div class="card" style="width:300px; height:350px;"><center> <img id="img'.$list[$i]['id'].'" class="card-img-top" style="max-width:150px;max-height:150px; margin-top:50px; margin-bottom:20px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'.$list[$i]['file'].'" alt="Card image"></center><div class="card-body"><p><label><b>Rotate: </b></label><img placeholder="img'.$list[$i]['id'].'" id="left_'.$list[$i]['id'].'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('.$list[$i]['id'].',0);"><img placeholder="img'.$list[$i]['id'].'" id="right_'.$list[$i]['id'].'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('.$list[$i]['id'].',0);"></p><label><b>Status: </b></label><form style="display:inline-block"> <fieldset> <select id="'.$list[$i]['id'].'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option><option value="validated" >validated</option><option value="rejected" selected="selected">rejected </option> </select> </fieldset></form><p class="card-text"><label><b>Date: </b></label>'.$list[$i]['timestamp'].'</p><p style="display:inline-block" class="card-text"></div></div></div></div> ');	
				}
			else if($list[$i]['status']=='validated'){
					echo('<div class="row"> <div class="col-md-4"><div class="card" style="width:300px; height:350px;"><center> <img id="img'.$list[$i]['id'].'" class="card-img-top" style="max-width:150px;max-height:150px; margin-top:50px; margin-bottom:20px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'.$list[$i]['file'].'" alt="Card image"></center><div class="card-body"><p><label><b>Rotate: </b></label><img placeholder="img'.$list[$i]['id'].'" id="left_'.$list[$i]['id'].'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('.$list[$i]['id'].',0);"><img placeholder="img'.$list[$i]['id'].'" id="right_'.$list[$i]['id'].'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('.$list[$i]['id'].',0);"></p><label><b>Status: </b></label><form style="display:inline-block"> <fieldset> <select id="'.$list[$i]['id'].'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option><option value="validated" selected="selected">validated</option><option value="rejected">rejected </option> </select> </fieldset></form><p class="card-text"><label><b>Date: </b></label>'.$list[$i]['timestamp'].'</p><p style="display:inline-block" class="card-text"></div></div></div></div> ');	
				}
			else{
					echo('<div class="row"> <div class="col-md-4"><div class="card" style="width:300px; height:350px;"><center> <img id="img'.$list[$i]['id'].'" class="card-img-top" style="max-width:150px;max-height:150px; margin-top:50px; margin-bottom:20px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'.$list[$i]['file'].'" alt="Card image"></center><div class="card-body"><p><label><b>Rotate: </b></label><img placeholder="img'.$list[$i]['id'].'" id="left_'.$list[$i]['id'].'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('.$list[$i]['id'].',0);"><img placeholder="img'.$list[$i]['id'].'" id="right_'.$list[$i]['id'].'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('.$list[$i]['id'].',0);"></p><label><b>Status: </b></label><form style="display:inline-block"> <fieldset> <select id="'.$list[$i]['id'].'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option><option value="validated">validated</option><option value="rejected">rejected </option> </select> </fieldset></form><p class="card-text"><label><b>Date: </b></label>'.$list[$i]['timestamp'].'</p><p style="display:inline-block" class="card-text"></div></div></div></div> ');	
				}
			}
			?>
			
			</div>
			<?php 
							$total_records = 50;
							$total_pages = ceil($total_records / $limit);
								$prev_page = $_GET["page"] -1;
								$suc_page = $_GET["page"] +1;
								$corr_page= $_GET["page"];
								///Controlli se i paramteri sono attivi////
								////
							if ($prev_page >=1){
							echo ('	<div class="pagination" value="'.$prev_page.'">&#09;<a href="index.php?page='.$prev_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'"><< 	Prev</a></div>');
							}
										if ($corr_page >11){
										$init_j = $corr_page -10;
										}else{$init_j = 1; 
										}
							for ($j=$init_j; $j<=$total_pages; $j++) { 

												if (($j<11)||(($corr_page-$j)>=0)||(($corr_page == $j)&&($corr_page < $total_pages-3))||(($corr_page >= $total_pages-3))){
											echo ("&#09;<a class='page_n' value='".$j."' href='index.php?page=".$j."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$j."</a>&#09;");
												}
											
							}; 							
							$last_pages = $total_pages-3;
							if (($total_pages > 13)&&($corr_page < $last_pages)){
							echo ("...");
							for ($y=$last_pages; $y<=$total_pages; $y++) {  
											echo ("&#09;<a class='page_n' value='".$y."' href='index.php?page=".$y."&limit=".$_GET['limit']."&showFrame=".$_REQUEST['showFrame']."&orderBy=".$order."&order=".$by."'>".$y."</a>&#09;");	
										};
									}
							if ($suc_page <=$total_pages){
							echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="index.php?page='.$suc_page.'&limit='.$_GET['limit'].'&showFrame='.$_REQUEST['showFrame'].'&orderBy='.$order.'&order='.$by.'">Next 	>></a></div>');
							}
						?>
    </div>

    <div class="container-fluid" style="width: 100%; height: 30%;">
        <!--<h2>Comments: </h2>-->
        <form>
		<div class="btn btn-sm btn-group" role="group" aria-label="Basic example">
            <input type="button" name="cfilter" id="call" value="Reset" onclick="reload();" class="btn btn-primary btn-sm">
			<div class="dropdown show">
			  <a class="btn btn-primary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
        <div class="row row-horizon" id="comments"></div>
    </div>

     <script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlc3NhbmRyb2JhY2Npb3R0aW5pIiwiYSI6ImNqaWxoNjZ6MDJvdG4zd3BleDB5aGkxMjkifQ.ddcO3RVomvLGJZBQzu5quw';
    var map = new mapboxgl.Map({
    container: 'mapid',
    style: 'mapbox://styles/mapbox/streets-v10'
    });
    </script>


    </body>

    <script type='text/javascript'>
	
			function myCardsWriter(rowIndex, record, columns, cellWriter){
			if(record.comment_id != 'null' ){
			var display = 'inline-block';
			}else{
			var display = 'none';
			}

			if (record.status=='rejected'){
			 var cardDiv = '<div class="row"> <div class="col-md-4"><div class="card" style="width:300px; height:350px;"><center> <img id="img'+rowIndex+'" class="card-img-top" style="max-width:150px;max-height:150px; margin-top:50px; margin-bottom:20px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+record.file+'" alt="Card image"></center><div class="card-body"><p><label><b>Rotate: </b></label><img placeholder="img'+rowIndex+'" id="left_'+rowIndex+'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('+rowIndex+',0);"><img placeholder="img'+rowIndex+'" id="right_'+rowIndex+'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('+rowIndex+',0);"></p><label><b>Status: </b></label><form style="display:inline-block"> <fieldset> <select id="'+record.id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option><option value="validated" >validated</option><option value="rejected" selected="selected">rejected </option> </select> </fieldset></form><p class="card-text"><label><b>Date: </b></label>'+record.timestamp+'</p><p style="display:inline-block" class="card-text"></div></div></div></div> ';
			}else if (record.status=='validated'){
			 var cardDiv = '<div class="row"> <div class="col-md-4"><div class="card" style="width:300px; height:350px;"><center> <img id="img'+rowIndex+'" class="card-img-top" style="max-width:150px;max-height:150px; margin-top:50px; margin-bottom:20px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+record.file+'" alt="Card image"></center><div class="card-body"><p><label><b>Rotate: </b></label><img placeholder="img'+rowIndex+'" id="left_'+rowIndex+'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('+rowIndex+',0);"><img placeholder="img'+rowIndex+'" id="right_'+rowIndex+'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('+rowIndex+',0);"></p><label><b>Status: </b></label><form style="display:inline-block"> <fieldset> <select id="'+record.id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted">submitted</option><option value="validated" selected="selected">validated</option><option value="rejected">rejected </option> </select> </fieldset></form><p class="card-text"><label><b>Date: </b></label>'+record.timestamp+'</p><p style="display:inline-block" class="card-text"></div></div></div></div> ';
			}else{
			 var cardDiv = '<div class="row"> <div class="col-md-4"><div class="card" style="width:300px; height:350px;"><center> <img id="img'+rowIndex+'" class="card-img-top" style="max-width:150px;max-height:150px; margin-top:50px; margin-bottom:20px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+record.file+'" alt="Card image"></center><div class="card-body"><p><label><b>Rotate: </b></label><img placeholder="img'+rowIndex+'" id="left_'+rowIndex+'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('+rowIndex+',0);"><img placeholder="img'+rowIndex+'" id="right_'+rowIndex+'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('+rowIndex+',0);"></p><label><b>Status: </b></label><form style="display:inline-block"> <fieldset> <select id="'+record.id+'" name="status" onchange="changeStatus(id,value)"> <option value="submitted" selected="selected">submitted</option><option value="validated" >validated</option><option value="rejected">rejected </option> </select> </fieldset></form><p class="card-text"><label><b>Date: </b></label>'+record.timestamp+'</p><p style="display:inline-block" class="card-text"></div></div></div></div> ';
			}
				return cardDiv;
			}
			
		function myCardsWriter2(rowIndex, record, columns, cellWriter){
		
			if (record.status=='rejected'){
			 var cardDiv = '<div class="row"> <div class="col-md-4"> <div class="card" style="width:300px; height:200px;"> <div class="card-body"> <p class="card-text">'+record.timestamp+'</p><div><p style="display:inline-block" class="card-text">'+record.comment+'</p><form> <fieldset> <select id="comment-sel'+record.id+'" name="status" placeholder="'+record.status+'" index="'+rowIndex+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted">submitted  </option> <option value="rejected" selected="selected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" id="loc'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >'+record.serviceName+'</p> </div> </div> </div> </div> '; 
			}else if (record.status=='validated'){
			 var cardDiv = '<div class="row"> <div class="col-md-4"> <div class="card" style="width:300px; height:200px;"> <div class="card-body"> <p class="card-text">'+record.timestamp+'</p><div><p style="display:inline-block" class="card-text">'+record.comment+'</p><form> <fieldset> <select id="comment-sel'+record.id+'" name="status" placeholder="'+record.status+'" index="'+rowIndex+'" onchange="changeStatus2(id,value)"> <option value="validated" selected="selected">validated</option> <option value="submitted">submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" id="loc'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >'+record.serviceName+'</p> </div> </div> </div> </div> '; 
			}else{
			 var cardDiv = '<div class="row"> <div class="col-md-4"> <div class="card" style="width:300px; height:200px;"> <div class="card-body"> <p class="card-text">'+record.timestamp+'</p><div><p style="display:inline-block" class="card-text">'+record.comment+'</p><form> <fieldset> <select id="comment-sel'+record.id+'" name="status" placeholder="'+record.status+'" index="'+rowIndex+'" onchange="changeStatus2(id,value)"> <option value="validated" >validated</option> <option value="submitted" selected="selected">submitted  </option> <option value="rejected">rejected </option> </select> </fieldset> </form> </div> <p class="card-text" id="loc'+rowIndex+'" placeholder="'+record.lat+', '+record.lon+'" >'+record.serviceName+'</p> </div> </div> </div> </div> '; 
			} 
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
			/*
        $(window).on('load', function(){
				//
				var limit_init = 12;
				//
				var mymap = L.map('mapid').setView([43.797100067139, 11.252900123596], 13);
					L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
						attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
						maxZoom: 18,
						id: 'mapbox.streets',
						accessToken: 'pk.eyJ1IjoiYWxlc3NhbmRyb2JhY2Npb3R0aW5pIiwiYSI6ImNqaWxoNjZ6MDJvdG4zd3BleDB5aGkxMjkifQ.ddcO3RVomvLGJZBQzu5quw'
					}).addTo(mymap);

                var array = new Array();
                        $.ajax({
                        url: "get_photo_data.php",
                        data: {values: "",limit:limit_init},
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
								
						if ((array[i].lat != null)&&(array[i].lon != null)){
							var marker=L.marker([array[i].lat,array[i].lon]).addTo(mymap);
								marker.bindPopup('<center><img src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'+array[i].file+'" style="max-width:200px;max-height:200px;"></img></center><br/><b>Comment:</b>'+array[i].comment+'<br/><b>Location:</b>'+array[i].serviceName+'<br>'+'<b>Address:</b>'+array[i].address+'<br>'+'<b>City:</b>'+array[i].city+'('+array[i].district+')').openPopup();
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
        });*/

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
            window.location.href="index.php";
        }
		
    </script>

    </html>