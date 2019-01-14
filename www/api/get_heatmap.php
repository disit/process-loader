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
	if(isset($_GET)){
		//echo ('OK!');
			if (isset($_REQUEST['list'])){
				//
				$query_list = "SELECT * FROM heatmap";
				$result_list = mysqli_query($link, $query_list) or die(mysqli_error($link));
				$list_all=array();
				$num0=$result_list->num_rows;
						if ($num0 > 0) {		
								while ($row0 = mysqli_fetch_array($result_list)) {
									$list_all_content = array(
										"id" => $row0['id'],
										"heatmap_name" => $row0['name'],
										"heatmap_type" => $row0['type'],
										"comment" => $row0['comment'],
										"x1y1" => $row0['x1y1'],
										"x2y2" => $row0['x2y2']
									);
									array_push($list_all,$list_all_content);
								}
						}
				//
				$response = $list_all;
				mysqli_close($link);
				echo json_encode($response);
				exit();
			}
		//
		if ((isset($_REQUEST['id']) && $_REQUEST['id']!="") || (isset($_REQUEST['heatmap_name']) && $_REQUEST['heatmap_name'] !="") || (isset($_REQUEST['type']) && $_REQUEST['type'] !="" ) || (isset($_REQUEST['unit']) && $_REQUEST['unit'] !="" ) || (isset($_REQUEST['latitude_min']) && $_REQUEST['latitude_min'] !="" ) || (isset($_REQUEST['longitude_max']) && $_REQUEST['longitude_max'] !="") || (isset($_REQUEST['longitude_min']) && $_REQUEST['longitude_min'] !="") || (isset($_REQUEST['date_min']) && $_REQUEST['date_min'] !="") || (isset($_REQUEST['date_max']) && $_REQUEST['date_max']!="" )|| (isset($_REQUEST['days']) && $_REQUEST['days'] !="")|| (isset($_REQUEST['date']) && $_REQUEST['date'] !="")|| (isset($_REQUEST['value']) && $_REQUEST['value'] !="")){
		//
		//HEATMAP_NAME= MANDATORY
		if(!isset($_REQUEST['heatmap_name']) && $_REQUEST['heatmap_name'] ==""){
			//OK
			$response['result'] = 'ERROR';
			$response['code'] = 400;
			$response['message'] = 'Lack of mandatory parameter';
			mysqli_close($link);
			echo json_encode($response);
		}
		//
		if(isset($_REQUEST['id']) && $_REQUEST['id'] != ""){
			$request_id = "AND id='".$_REQUEST['id']."'";
		}else{
			$request_id = "";
		}
		
		if(isset($_REQUEST['heatmap_name']) && $_REQUEST['heatmap_name'] !=""){
			$request_name = "AND name='".$_REQUEST['heatmap_name']."'";
		}else{
			$request_name = "";
		}
		
		if (isset($_REQUEST['type']) && $_REQUEST['type'] !=""){
			$request_type = "AND type='".$_REQUEST['type']."'";
		}else{
			$request_type = "";
		}
		
		if (isset($_REQUEST['unit']) && $_REQUEST['unit'] !=""){
			$request_unit = "AND unit='".$_REQUEST['unit']."'";
		}else{
			$request_unit = "";
		}
		
		//
		$query = "SELECT * FROM heatmap WHERE id IS NOT NULL	".$request_id." ".$request_name." ".$request_type." ".$request_unit.";";
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
		$list=array();
		$num=$result->num_rows;		
		$list=array();
			if ($num > 0) {		
					while ($row = mysqli_fetch_array($result)) {
						$query_point = "SELECT * FROM processloader_db.heatmap_values WHERE heatmap_id=".$row['id']."";
						//CONTROLLI SU LATITUDINE. LONGITUDINE E DATA
						if (isset($_REQUEST['value']) && $_REQUEST['value'] !=""){
						$query_point = $query_point. "	AND value = '".$_REQUEST['value']."'";
						}
						if (isset($_REQUEST['latitude_min']) && $_REQUEST['latitude_min'] !=""){
						$query_point = $query_point. "	AND lat >= '".$_REQUEST['latitude_min']."'";
						}
						if (isset($_REQUEST['latitude_max']) && $_REQUEST['latitude_max'] !=""){
						$query_point = $query_point. "	AND lat <= '".$_REQUEST['latitude_max']."'";
						}
						if (isset($_REQUEST['longitude_min']) && $_REQUEST['longitude_min'] !=""){
						$query_point = $query_point. "	AND lng >= '".$_REQUEST['longitude_min']."'";
						}
						if (isset($_REQUEST['longitude_max']) && $_REQUEST['longitude_max']!= ""){
						$query_point = $query_point. "	AND lng <= '".$_REQUEST['longitude_max']."'";
						}										
						if (isset($_REQUEST['date_min']) && $_REQUEST['date_min'] != ""){
						$query_point = $query_point. "	AND date >= '".$_REQUEST['date_min']."'";	
						}
						if (isset($_REQUEST['date_max']) && $_REQUEST['date_max'] != ""){
						$query_point = $query_point. "	AND date <= '".$_REQUEST['date_max']."'";		
						}
						if (isset($_REQUEST['days']) && $_REQUEST['days'] != ""){
								$actual_date = date("Y-m-d");
								$days = date( "Y-m-d", strtotime( $actual_date." -".$_REQUEST['days']." day" ) );	
								$add_date =" AND date BETWEEN '".$days."' AND '".$actual_date."'";
								$query_point = $query_point.$add_date;	
						}
						if (isset($_REQUEST['date']) && $_REQUEST['date'] != ""){
							$day0 = $_REQUEST['date'];
							$day1= date( "Y-m-d", strtotime( $day0." +1 day" ) );
							$add_date0 =" AND date BETWEEN '".$day0."' AND '".$day1."'";
							$query_point = $query_point.$add_date0;
						} 
						//
						//echo ($query_point);
						//
							$result_point = mysqli_query($link, $query_point) or die(mysqli_error($link));
							$list2=array();
							$num2=$result_point->num_rows;
							if ($num2 > 0) {
								while ($row2 = mysqli_fetch_array($result_point)) {
										$listFile2= array(
													"lat"=>$row2['lat'],
													"lng"=>$row2['lng'],
													"value"=>$row2['value'],
													//"date"=>$row2['date']
											);
										array_push($list2, $listFile2);
								}
							}
							//
								//
						
						
						
							$listFile = array(
										"id" => $row['id'],
										"heatmap_name" => $row['name'],
										"heatmap_type" => $row['type'],
										"comment" => $row['comment'],
										"x1y1" => $row['x1y1'],
										"x2y2" => $row['x2y2'],
										"points" => $list2
									);			
															
							//$listFile["points"] => $list2;
							
							array_push($list, $listFile);
							//array_push($list, $list2);
						}
						//$response['files']=$list;
						//$response['result'] = 'OK';
						//$response['code'] = 200;
						$response=$list;
						mysqli_close($link);
						echo json_encode($response);
				}else{
						$response['files']='No Content';
						$response['result'] = 'OK';
						$response['code'] = 204;
						mysqli_close($link);
						echo json_encode($response);
				}
			//
			}else{
					$response['result'] = 'KO';
					$response['code'] = 400;
					$response['message'] = 'input not valid: some parameters not recognised';
					mysqli_close($link);
					echo json_encode($response);
			}
		//
	//
	}
	//
	else{
		$response['result'] = 'KO';
		$response['code'] = 500;
		$response['message'] = 'request not set';
		mysqli_close($link);
		echo json_encode($response);
	}
	///
}
?>