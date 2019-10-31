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
if (isset ($_SESSION['username'])&& isset($_SESSION['role'])){
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
//error_reporting(E_ERROR | E_NOTICE);
$ip_run=$_REQUEST['ip_run'];	
//
$operation_run=mysqli_real_escape_string($link,$_REQUEST['operation_run']);
$id_run=mysqli_real_escape_string($link,$_REQUEST['id_run']);
$n_run=mysqli_real_escape_string($link,$_REQUEST['n_run']);
$g_run=mysqli_real_escape_string($link,$_REQUEST['g_run']);
//echo ("ip_run: ".$ip_run."<br>");
//echo ("operation_run: ".$operation_run."<br>");
//echo ("id_run: ".$id_run."<br>");
//echo ("n_run: ".$n_run."<br>");
//echo ("g_run: ".$g_run."<br>");


//SELEZIONARE IL VALORE DEL TRIGGER
$query1 = "SELECT * FROM `processes` WHERE `Id`='".$id_run."'";
$result1 = mysqli_query($link, $query1) or die(mysqli_error($link));
//print_r ($result1);
//
$list = array();
$num = $result1->num_rows;
if ($result1->num_rows > 0) {
            while ($row1 = mysqli_fetch_array($result1)) {
			$listFile = array(
				"id" => $row1['Id'],
				"name" => $row1['Process_name'],
				"group" => $row1['Process_group'],
				"status" => $row1['Status'],
				"trigger_name" => $row1['trigger_name'],
				"trigger_group" => $row1['trigger_group'],
				"ip" => $row1['id_disces'],
				"Start_time" => $row1['Start_time']
			);
	array_push($list, $listFile);
	}
}

//for ($i = $num-1; $i <= $num-1; $i++) {
	echo $val = $list[0]['id'];
	echo $val_name =  $list[0]['name'];
	echo $val_group =  $list[0]['group'];
	echo $val_trg_name = $list[0]['trigger_name'];
	echo $val_trg_group = $list[0]['trigger_group'];
	echo $val_trg_start = $list[0]['Start_time'];
	$opening_date = $val_trg_start;
	$current_date = date("Y-m-d H:i:s");
		if ($opening_date > $current_date){
			$operation_run ="triggerJob";
			}
	//
	if ($list[0]['ip'] ==""){
		$ip_SCE1 = explode(":", $ip_SCE1);
		$val_ip = $ip_SCE1[0];
	}else{
		$val_ip = $list[0]['ip'];
	}
//}
//$url = 'http://'.$ip_run.':8080/SmartCloudEngine/index.jsp';
$data = 'http://'.$ip_run.':8080/SmartCloudEngine/index.jsp?json='. urlencode('{"id":"'.$operation_run.'","jobName":"'.$n_run.'","jobGroup":"'.$g_run.'"}');
$options = array(
		'http' => array(
			'header'  => "Content-type: application/json\r\n",
			'method'  => 'POST',
		)
	);

	$context  = stream_context_create($options);
	$result = file_get_contents($data, false, $context);
	//echo $result."<br>";
	$resultNuovo = JSON_decode($result,true);
	//if($resultNuovo[1] == 'true'){
	if(strpos($http_response_header[0], '200') !== false){
		//
		//$url01 = 'http://'.$ip_run.':8080/SmartCloudEngine/index.jsp?json=';
		//$data01 = urlencode('{"id":"getJobFireTimes","jobName":"'.$n_run.'","jobGroup":"'.$g_run.'"}');	
		//$apiUrl01 = $url01.$data01;
		/////TRIGGER////
		$url5 ='http://'.$val_ip.':8080/SmartCloudEngine/index.jsp?json=';
		$data5 = urlencode('{"id":"getTriggerState","triggerName":"'.$val_trg_name.'","triggerGroup":"'.$val_trg_group.'"}');
		$apiUrl5 = $url5.$data5;
		//
		$context0  = stream_context_create($options);
		//$section01 = file_get_contents($apiUrl01, false, $context0);
		$section01 = file_get_contents($apiUrl5, false, $context0);
		echo "RESULT".$section01."<br>";
		$pattern = '/\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n/';
		$replacement = '';
		$section01 = preg_replace($pattern, $replacement, $section01);
		$pattern = '/<p>/';
		$replacement = '';
		$section01 = preg_replace($pattern, $replacement, $section01);
		$pattern = '/<\/p>/';
		$replacement = '';
		$section01 = preg_replace($pattern, $replacement, $section01);
		$pattern = '/\\r\\n/';
		$replacement = '';
		$section01 = preg_replace($pattern, $replacement, $section01);
		echo $section01;
		
		$stato_att = $section01;
		/*
		$sectionJSON = JSON_decode($section01,true);
		$stato_att =$sectionJSON['state'];
		*/
		if (!$stato_att){
			$stato_att = "ERROR SERVER COMMUNICATION";
		}
		echo "RESULTATO GET TRIGGER DI ".$val.": ".$stato_att; 
		//
		$date_run = date("Y-m-d H:i:s");

							//if ($operation_run == 'triggerJob'){
									if ($operation_run == 'resumeJob'|| $operation_run == "triggerJob"){
								//$sql = "UPDATE  `processes` SET `processes`.`Status`='RUNNING' WHERE `id`='".$id_run."'";
								//
								$sql = "UPDATE  `processes` SET `processes`.`Status`='".$stato_att."' WHERE `id`='".$id_run."'";
								$result = mysqli_query($link, $sql) or die(mysqli_error($link));
									//archivio 
									if ($stato_att != "ERROR SERVER COMMUNICATION"){
										$query="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$date_run."','".$id_run."','".$n_run."','".$g_run."','RUN')";
										$archive = mysqli_query($link, $query) or die(mysqli_error($link));
										mysqli_close($link);
									}
										//header("location:Process_loader.php?process_run=ok");
										if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:Process_loader.php?process_run=ok&showFrame=false");
													}else{
													header ("location:Process_loader.php?process_run=ok");
													}	
												}else{
												header ("location:Process_loader.php?process_run=ok");
												}
										//
						} elseif($operation_run == 'pauseJob'){
								//$sql4 = "UPDATE  `processes` SET `status`='PAUSE' WHERE `id`='".$id_run."'";
								$sql4 = "UPDATE  `processes` SET `processes`.`Status`='".$stato_att."' WHERE `id`='".$id_run."'";
								$result4 = mysqli_query($link, $sql4) or die(mysqli_error($link));
							//archivio 
							if ($stato_att != "ERROR SERVER COMMUNICATION"){
							$query4="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$date_run."','".$id_run."','".$n_run."','".$g_run."','PAUSE')";
							$archive4 = mysqli_query($link, $query4) or die(mysqli_error($link));
							mysqli_close($link);
							}
							//header("location:Process_loader.php?process_run=ok");
							if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:Process_loader.php?process_run=ok&showFrame=false");
													}else{
													header ("location:Process_loader.php?process_run=ok");
													}	
												}else{
												header ("location:Process_loader.php?process_run=ok");
												}
							//
						}
							elseif ($operation_run=='deleteJob'){
								$sql3 = "DELETE FROM `processes` WHERE `processes`.`id`='".$id_run."'";
								$result3 = mysqli_query($link, $sql3) or die(mysqli_error($link));
					//archivio 
								$query3="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$date_run."','".$id_run."','".$n_run."','".$g_run."','DELETE')";
								$archive3 = mysqli_query($link, $query3) or die(mysqli_error($link));
								mysqli_close($link);
								//header("location:Process_loader.php?process_run=ok");
								/////////
											if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:Process_loader.php?process_run=ok&showFrame=false");
													}else{
													header ("location:Process_loader.php?process_run=ok");
													}	
												}else{
												header ("location:Process_loader.php?process_run=ok");
												}
								////////
								}else{
									//header("location:process_loader.php?process_run=no_type");
									if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
														header ("location:Process_loader.php?process_run=no_type&showFrame=false");
													}else{
													header ("location:Process_loader.php?process_run=no_type");
													}	
												}else{
													header ("location:Process_loader.php?process_run=no_type");
												}
									///////////////////////
								}
	}else{
		//Operazioni sul DB
		echo "Operazione non trovata";
	    //////////header("location:Process_loader.php?process_run=no_operation");
							if (isset($_REQUEST['showFrame'])){
										if ($_REQUEST['showFrame'] == 'false'){
												header ("location:Process_loader.php?process_run=no_operation&showFrame=false");
											}else{
												header ("location:Process_loader.php?process_run=no_operation");
											}	
												}else{
													header ("location:Process_loader.php?process_run=no_operation");
											}
		///////////		
	}
}else{
	header ("location:page.php");
}	
?>