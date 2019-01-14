<?php
		include ('config.php');
	include('curl.php');
	$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname);
//     $values=$_POST['values'];
     $values="";
	 $limit_start="1";
	 $limit_end="7";
	 $limit="7";
//CARICAMENTO DATI//
	if(($values!=null)||($values!="")||(!empty($values))){
    $value="";
    for($i=0;$i<count($values);$i++){
        if($i==count($values)-1){
            $value=$value."'".$values[$i]."'";
        } else{$value=$value."'".$values[$i]."'"." OR status=";}
    }
	    $query = "SELECT * FROM ServicePhoto WHERE status=$value ORDER BY timestamp DESC LIMIT $limit_start,$limit_end";
    }
    else{
		$query = "SELECT * FROM ServicePhoto ORDER BY timestamp DESC LIMIT $limit_start,$limit_end";
	}
	$query_count = "select COUNT(*) FROM ServicePhoto";
	$total_rows_query = $query_count;
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

$result0 = mysqli_query($link, $total_rows_query) or die(mysqli_error($link));
		//$total_rows = $result0->num_rows;
		///////////
				$count_list = array();
				if ($result0->num_rows > 0) {
					while($row = mysqli_fetch_assoc($result0)){
						array_push($count_list, $row);
					}
				}
				//var_dump($count_list);
				$total_rows = $count_list[0]["COUNT(*)"];
		////////////
	mysqli_close($link);
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
		<div class="row mainRow" style='width: 100%; background-color: rgba(138, 159, 168, 1)'>
		<?php //include "mainMenu.php" ?>
		<div class="col-xs-12 col-md-10" id="mainCnt" style="width: 100%;">
		
        <div id="mapid" style="width: 100%; height: 30%;"></div>

    <div class="container-fluid" style="width: 100%; height: 40%;" id="list_dashboard_cards">
        <form>
		<h3>Photos:</h3>
		<div class="btn-group" role="group" aria-label="Basic example">	
            <input type="button" name="filter" id="all" value="Reset" onclick="reload();" class="btn btn-primary">
			<div class="dropdown show">
			  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Filters
			  </a>
			<div class="dropdown-menu">
            <a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="validated" value=""  onchange="ffilterfunc(value,id)"> Validated</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="submitted" value=""  onchange="ffilterfunc(value,id)"> Submitted</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="filter" id="rejected" value="" onchange="ffilterfunc(value,id)"> Rejected</a>
			</div>
			</div>
			</div>
        </form>
        
            <div class="row row-horizon" id="cards" >
					<div><a href="#"><i class="fa fa-toggle-left" style="font-size:48px;"></i></a></div>
	        <?php
			for($i = 0; $i < $num; $i++){
				echo ('<div class="card" style="padding: 10px;"><center><img style="width: 150px; height: 150px; padding: 5px;" src="http://www.disit.org/ServiceMap/api/v1/photo/thumbs/'.$list[$i]['file'].'"></img></center><div class="card-body"><p><img placeholder="img'.$list[$i]['id'].'" id="left_'.$list[$i]['id'].'"  src="rotate2.svg" class="card-img-rot1" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" grado="0" onclick="ruota1('.$list[$i]['id'].',0);"><img placeholder="img'.$list[$i]['id'].'" id="right_'.$list[$i]['id'].'"  src="rotate1.svg" grado="0" class="card-img-rot2" style="width:20px;height:20px;display:inline-block" alt="Card image rotation" onclick="ruota2('.$list[$i]['id'].',0);"></p></div></div>');
			}
			?>
					<div><a href="#"><i class="fa fa-toggle-right" style="font-size:48px;"></i></a></div>
			</div>
			<div class="paginator">
			<?php 
			/*
							$total_records = $total_rows;
							$total_pages = ceil($total_records / $limit);
								$prev_page = 0;
								$suc_page = 2;
								$corr_page= 1;
								$array_link = array ();
							if ($prev_page >=1){
							echo ('	<div class="pagination" value="'.$prev_page.'">&#09;<a href="photo_service_map.php"><< 	Prev</a></div>');
							}
							if ($corr_page >11){
										$init_j = $corr_page -10;
										}else{$init_j = 1; 
									}
							for ($j=$init_j; $j<=$total_pages; $j++) { 
										if (($j<11)||(($corr_page-$j)>=0)||(($corr_page == $j)&&($corr_page < $total_pages-3))||(($corr_page >= $total_pages-3))){
											echo ("&#09;<a class='page_n' value='".$j."' href='photo_service_map.php'>".$j."</a>&#09;");
										}else{echo(" ");}
							}; 
									$last_pages = $total_pages-3;
									if (($total_pages > 13)&&($corr_page < $last_pages)){
										echo ("...");
										for ($y=$last_pages; $y<=$total_pages; $y++) {  
											echo ("&#09;<a class='page_n' value='".$y."' href='photo_service_map.php'>".$y."</a>&#09;");	
										};
									}
							if ($suc_page <=$total_pages){
									echo ('	<div class="pagination" value="'.$suc_page.'">&#09;<a href="photo_service_map.php">Next 	>></a></div>');
							}*/
						?>
				</div>
    </div>
	<!-- -->
    <div class="container-fluid" style="width: 100%; height: 30%;">
        <form>
		<h3>Comments:</h3>
		<div class="btn-group" role="group" aria-label="Basic example">		
            <input type="button" name="cfilter" id="call" value="Reset" onclick="reload();" class="btn btn-primary">
			<div class="dropdown show">
			  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Filters
			  </a>
			<div class="dropdown-menu">
            <a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="cvalidated" value=""  onchange="filterfunc(value,id)"> Validated</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="csubmitted" value=""  onchange="filterfunc(value,id)"> Submitted</a>
            <a class="dropdown-item" href="#"><input type="checkbox" name="cfilter" id="crejected" value="" onchange="filterfunc(value,id)"> Rejected</a>
			</div>
			</div>
			</div>
        </form>
        <div class="card-columns" id="comments"></div>
    </div>
	</div>
	</div>
	</div>
    </body>
	<script type='text/javascript'>
	var ut_att = "<?=$utente_att; ?>";
	var role_att = "<?=$role_att;?>";
	////////////
	function reload() {
            window.location.href="index.html";
        }
	/////////
	$(window).on('load', function(){
		//
		var mymap = L.map('mapid').setView([43.797100067139, 11.252900123596], 14);
					L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
						attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
						maxZoom: 18,
						id: 'mapbox.streets',
						accessToken: 'pk.eyJ1IjoiYWxlc3NhbmRyb2JhY2Npb3R0aW5pIiwiYSI6ImNqaWxoNjZ6MDJvdG4zd3BleDB5aGkxMjkifQ.ddcO3RVomvLGJZBQzu5quw'
					}).addTo(mymap);
		//
	});
	
	//
			function ruota1(id,grado){
				deg=grado - 90;
				idimg= "img"+id;
				left="left_"+id;
				document.getElementById(idimg).style = 'max-width:150px; max-height:150px; margin-bottom:20px; margin-top:50px; transform: rotate(' + deg + 'deg)';
				$("#"+left).attr("onclick","ruota1("+id+","+deg+")");
		}
	//
			function ruota2(id,grado){
				deg=grado + 90;
				idimg= "img"+id;
				right="right_"+id;
				document.getElementById(idimg).style = 'max-width:150px; max-height:150px; margin-bottom:20px; margin-top:50px; transform: rotate(' + deg + 'deg)';
				$("#"+right).attr("onclick","ruota2("+id+","+deg+")");
		}
	//
	</script>
 </html>
