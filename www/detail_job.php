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
//EFFETTUARE query sul database Quartz
//
$id_det = $_REQUEST['id_det'];
$ip_det = $_REQUEST['ip_det'];
$n_det = $_REQUEST['n_det'];
$g_det = $_REQUEST['g_det'];
//
if ($ip_det ==""){
	$ip_SCE1 = explode(":", $ip_SCE1);
	$ip_det  = $ip_SCE1[0];
	
}

$data = 'http://'.$ip_det.':8080/SmartCloudEngine/index.jsp?json='. urlencode('{"id":"listJobTriggers","jobName":"'.$n_det.'","jobGroup":"'.$g_det.'"}');
echo $data;
$options = array(
		'http' => array(
			'header'  => "Content-type: application/json",
			'method'  => 'POST',
		)
	);

	$context  = stream_context_create($options);
	$result = file_get_contents($data, false, $context);
	
	$pattern = '/\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n/';
	$replacement = '';
	$result = preg_replace($pattern, $replacement, $result);
	$pattern = '/<p>/';
	$replacement = '';
	$result = preg_replace($pattern, $replacement, $result);
	$pattern = '/<\/p>/';
	$replacement = '';
	$result = preg_replace($pattern, $replacement, $result);
	$pattern = '/\\r\\n/';
	$replacement = '';
	$result = preg_replace($pattern, $replacement, $result);
	echo $result;
	//
	/*
	$myfile = fopen("Nuovo documento di testo.txt", "w") or die("Unable to open file!");
	fwrite($myfile, "Senza URL encode:\n");
	fwrite($myfile, $result);
	fwrite($myfile, "Con URL encode:\n");
	fwrite($myfile, urlencode($result));
	fclose($myfile);
	*/
	//
	
	if(strpos($http_response_header[0], '200') !== false){
		//echo "OK";
			if(strpos($result, '1') == true){
				echo "OK";	
				$location = "location:Process_loader.php?detail_response=".urlencode($result);
				header($location);
			}else{
				echo "NO DATA";	
				//$_SESSION['detail_response']='no_data';
				header("location:Process_loader.php?detail_response=no_data");
			}
		//
	}else{
		echo "NOT FOUND";
		//$_SESSION['detail_response']='no_data';
		header("location:Process_loader.php?detail_response=no_data");
		//
	}
?>