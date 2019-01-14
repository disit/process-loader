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
	include('../config.php'); 
	include('../curl.php');
	$link = mysqli_connect($host, $username, $password) or die(mysqli_error($link));
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname);
//
//
if ((isset($_REQUEST['label']))){
	$richiesta=$_REQUEST['label'];
		if ($richiesta !=""){
						$query_job_type = "SELECT * FROM processloader_db.Help_manager WHERE label ='".$richiesta."'";
						$resultjobType = mysqli_query($link, $query_job_type) or die(mysqli_error($link));
						$jb_list = array();
						////////////
						$num_rows = $resultjobType->num_rows;
							if ($resultjobType ->num_rows > 0) {
								while($row = mysqli_fetch_assoc($resultjobType)){
										array_push($jb_list, $row);
									}
								}
								$url=$jb_list[0]['url'];
								$click=$jb_list[0]['accesses'];
								$click2=$click +1;
								$id=$jb_list[0]['Id'];
								$type=$jb_list[0]['type'];
								
					if (($url !="")||($url !=null)){
							$url = str_replace("\/","/",$url);
							$url = str_replace("\/","/",$url);
							$response['result']= 'OK';
							$response['code']= 200;
							$response['url']= $url;
							$response['type']= $type;
							$query_nuova_file_pos = "UPDATE processloader_db.Help_manager SET accesses = '".$click2."' WHERE Help_manager.Id = ".$id;
							$result_nuobo_file_pos = mysqli_query($link, $query_nuova_file_pos) or die('Error select job');
						mysqli_close($link);
						}else{
							$response['result']= 'ERROR';
							$response['code']= 400;
							$response['message']= 'Not found';
							mysqli_close($link);
						}
				echo json_encode($response);
		}else{
			$response['result']= 'ERROR';
			$response['code']= 405;
			$response['message']= 'Method Not Allowed';
			mysqli_close($link);
			echo json_encode($response);
		}
}else{
	$response['result']= 'ERROR';
	$response['code']= 406;
	$response['message']= 'Not Acceptable';
	mysqli_close($link);
	echo json_encode($response);
}
?>