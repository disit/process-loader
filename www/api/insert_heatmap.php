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
   
header("Access-Control-Allow-Origin: *\r\n");  
include('../external_service.php');
include("../curl.php");
   $link = mysqli_connect($host_heatmap, $username_heatmap, $password_heatmap);
   mysqli_select_db($link, $dbname_heatmap);
   	$response = [];
	
if($link == false){
    //try to reconnect
	$response['result'] = 'KO';
	$response['code'] = 500;
	$response['message'] = 'DB Connection error';
	mysqli_close($link);
    echo json_encode($response);
}else{
$request = json_decode(file_get_contents('php://input'));
				if($request){
					if (isset($request->heatmap_name)&& isset($request->unit)&& isset($request->heatmap_type) && isset($request->comment) && isset($request->x1y1) && isset($request->x2y2) && isset($request->heatmap_date) && isset($request-> points) ){
						//
						$name =$request->heatmap_name;
						$unit = $request->unit;
						$comment = $request->comment;
						$type = $request->heatmap_type;
						$x1y1 = $request->x1y1;
						$x2y2 = $request->x2y2;
						$date = $request->heatmap_date;
						$points = $request-> points;
						//
						$query = "INSERT INTO ".$dbname_heatmap.".heatmap (id, name, unit, type, comment,x1y1,x2y2) VALUES (NULL,'".$name."','".$unit."','".$type."','".$comment."','".$x1y1."','".$x2y2."')";
						$result = mysqli_query($link, $query) or die(mysqli_error($link));
						/////
						if($result == 1){
						$query_max = "SELECT max(id) FROM ".$dbname_heatmap.".heatmap;";
						$result_max = mysqli_query($link, $query_max) or die(mysqli_error($link));
						$id_max=0;
						//
						//
						if ($result_max == 1){
							while ($row = mysqli_fetch_array($result_max)) {
								$id_max = $row['max(id)'];
							}
							//
							if(!is_array($points)){
									$response['code'] = 400;
									$response['message'] = 'Parameters Not acceptable';
									mysqli_close($link);
									echo json_encode($response);
									exit;
								}
							//
							$num = count($points);						
							for ($i=0;$i<$num;$i++){
								//echo ('PUNTO');
								//var_dump ($points[$i]);
								$valore = $points[$i] -> value;
								$lat = $points[$i] -> lat;
								$lng = $points[$i] -> lng;
								if (isset($points[$i] -> date)){
									$date_point = $points[$i] -> date;
								}else{
									$date_point = $date;
								}
								//
								$query2 = "INSERT INTO ".$dbname_heatmap.".heatmap_values (id, heatmap_id, value,lat,lng,date) VALUES (NULL, '".$id_max."', '".$valore."','".$lat."','".$lng."','".$date_point."')";
								$result2 = mysqli_query($link, $query2) or die(mysqli_error($link));
								//
							}
							
							//
							//echo($id_max);
							$response['result'] = 'Ok';
							$response['code'] = 200;
							mysqli_close($link);
							echo json_encode($response);
							//
						}
						//
						
						/////
								
							}else{
								mysqli_commit($link);
								$response['result'] = 'Ok';
								$response['code'] = 400;
								$response['message'] = 'Error during data upload';
								mysqli_close($link);
								echo json_encode($response);
							}
					}else{
							$response['result'] = 'KO';
							$response['code'] = 400;
							$response['message'] = 'Insert NOT done due to incorrect json formatting or lack of mandatory data';
							mysqli_close($link);
							echo json_encode($response);
					}
				/*
				if(isset($request->heatmap_id) && isset($request->value)&&(($request->heatmap_id)!="")&&(($request->value)!="") && isset($request->point) && (($request->point)!="") ){
							$query = "INSERT INTO ".$dbname_heatmap.".heatmap_values (id, heatmap_id, value,point,date) VALUES (NULL, '".$type."', '".$value."','".$cluster_center."','".$date."')";
							$result = mysqli_query($link, $query) or die(mysqli_error($link));
									if($result == 1){
											mysqli_commit($link);
											$response['result'] = 'Ok';
											$response['code'] = 200;
											mysqli_close($link);
											echo json_encode($response);
											}else{
																	mysqli_commit($link);
																	$response['result'] = 'Ok';
																	$response['code'] = 400;
																	$response['message'] = 'Error during data upload';
																	mysqli_close($link);
																	echo json_encode($response);
															}
											////
											}else{
												$response['result'] = 'KO';
												$response['code'] = 400;
												$response['message'] = 'Insert NOT done due to incorrect json formatting or lack of mandatory data';
												mysqli_close($link);
												echo json_encode($response);
											}
						*/
									//
							}else{
								//
								$response['result'] = 'KO';
									$response['code'] = 400;
									$response['message'] = 'Not Request Done';
									mysqli_close($link);
									echo json_encode($response);
							}

	}

?>