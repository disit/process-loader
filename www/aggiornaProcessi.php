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
   
include'config.php';
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
$utente_us = $_SESSION['username'];
error_reporting(E_ERROR | E_NOTICE);
$updatedStatuses = [];

$query1 = "SELECT * FROM `processes`";
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
			);
	array_push($list, $listFile);
	}
}

for ($i = 0; $i <= $num-1; $i++) {
	$val = $list[$i]['id'];
	$val_name =  $list[$i]['name'];
	$val_group =  $list[$i]['group'];
	$val_trg_name = $list[$i]['trigger_name'];
	$val_trg_group = $list[$i]['trigger_group'];
	if ($list[$i]['ip'] ==""){
            $ip_SCE1 = explode(":", $ip_SCE1);
		$val_ip = $ip_SCE1[0];
	}else{
		$val_ip = $list[$i]['ip'];
	}
	
	$url = 'http://'.$val_ip.':8080/SmartCloudEngine/index.jsp?json=';
	$data = urlencode('{"id":"checkExistJob","jobName":"'.$val_name.'","jobGroup":"'.$val_group.'"}');
	$apiUrl = $url.$data;
	//AGGIORNA LO STATO//
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/json\r\n",
			'method'  => 'POST',
		)
	);	
	//$section = file_get_contents($url, FILE_USE_INCLUDE_PATH);
	$context  = stream_context_create($options);
	$section = file_get_contents($apiUrl, false, $context);
	//echo "RESPONCE HEADER: " . $http_response_header[0] . "\n";
	if(strpos($http_response_header[0], '200') !== false){

						if(strpos($section, 'true') !== false){
		
										$url5 ='http://'.$val_ip.':8080/SmartCloudEngine/index.jsp?json=';
										$data5 = urlencode('{"id":"getTriggerState","triggerName":"'.$val_trg_name.'","triggerGroup":"'.$val_trg_group.'"}');
										$apiUrl5 = $url5.$data5;

									//AGGIORNA LO STATO//
										$options = array(
										'http' => array(
												'header'  => "Content-type: application/json\r\n",
												'method'  => 'POST',
												)
									);	
												//$section = file_get_contents($url, FILE_USE_INCLUDE_PATH);
													$options0 = array(
																'http' => array(
																'header'  => "Content-type: application/json\r\n",
																'method'  => 'POST',
																)
															);	
													$context1  = stream_context_create($options0);
													$section5 = file_get_contents($apiUrl5, false, $context1);
													//echo $section0;
													//
													//echo "RESPONCE HEADER FIRE TIMES: " . $http_response_header[0] . "<br>";
													//
													if(strpos($http_response_header[0], '200') !== false){
													
												       
																$pattern = '/\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n/';
																$replacement = '';
																$section5 = preg_replace($pattern, $replacement, $section5);
																$pattern = '/<p>/';
																$replacement = '';
																$section5 = preg_replace($pattern, $replacement, $section5);
																$pattern = '/<\/p>/';
																$replacement = '';
																$section5 = preg_replace($pattern, $replacement, $section5);
																$pattern = '/\\r\\n/';
																$replacement = '';
																$section5 = preg_replace($pattern, $replacement, $section5);
																//echo $section5;
																//
																$stato_att=$section5;
																$sql = "UPDATE `processes` SET `processes`.`Status`='".$stato_att."' WHERE `id`='".$val."'";
																$result = mysqli_query($link, $sql) or die(mysqli_error($link));
																$updatedStatuses[$val] = $stato_att;
																//ESTRAZIONE DEL VALORE DI STATE
																//
																//
																}else{
												//
																		$sectionJSON = JSON_decode($section0,true);
																		//$stato_att = $sectionJSON['state'];
																		$sql = "UPDATE  `processes` SET `processes`.`Status`='ERROR SERVER COMMUNICATION' WHERE `id`='".$val."'";
																		$result = mysqli_query($link, $sql) or die(mysqli_error($link));
																		$updatedStatuses[$val] = 'ERROR SERVER COMMUNICATION';
																		//echo ("RICHIESTA FALLITA<br>");
												//
											}	
							///////////////////////////////
						}else{
						$sectionJSON = JSON_decode($section,true);
												//$stato_att =$sectionJSON['state'];
												$sql = "UPDATE  `processes` SET `processes`.`Status`='NOT FOUND' WHERE `id`='".$val."'";
												$result = mysqli_query($link, $sql) or die(mysqli_error($link));
												$updatedStatuses[$val] = 'NOT FOUND';
												//echo ("ERRORE NELLA RICHIESTA API - RISPOSTA FALSE<br>");
						//
						}
		////////////
	}else{

		$sectionJSON = JSON_decode($section0,true);
			$stato_att = $sectionJSON['state'];
			$sql = "UPDATE  `processes` SET `processes`.`Status`='NOT FOUND' WHERE `id`='".$val."'";
			$result = mysqli_query($link, $sql) or die(mysqli_error($link));
			$updatedStatuses[$val] = 'NOT FOUND';
			//echo ("ERRORE NELLA RICHIESTA API<br>");
		//
		//
	}	
}

echo json_encode($updatedStatuses);
?>