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
include 'curl.php';
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
$utente_us = $_SESSION['username'];
$updatedStatuses = [];

$query1 = "SELECT * FROM `uploaded_files` WHERE `status` = 'Awaiting approval'";
//
$result1 = mysqli_query($link, $query1) or die(mysqli_error($link));
$list = array();
$num = $result1->num_rows;
if ($result1->num_rows > 0) {
            while ($row1 = mysqli_fetch_array($result1)) {
			$listFile = array(
				"id" => $row1['Id'],
				"name" => $row1['File_name'],
				"desc" => $row1['Description'],
				"user" => $row1['User'],
				"date" => $row1['Creation_date'],
				"type" => $row1['file_type'],
				"status" => $row1['status'],
				"username" => $row1['Username']
			);
	array_push($list, $listFile);
	}
}


//AGGIORNAMENTO
for ($i = 0; $i <= $num-1; $i++) {
	$val = $list[$i]['id'];
	$data_C_mod = $list[$i]['date'];
	$data_C_mod2 = str_replace(":","-",$data_C_mod);
	$data_C_mod3 = str_replace(":","-",$data_C_mod2);
	$data_C_mod4 = str_replace(" ","-",$data_C_mod3);
	$stato = $list[$i]['type'];
	$data = date('Y-m-d H:i:s');
	$data2 =date('Y-m-d H-i-s');
	$findme="";
	if ($stato == "ETL"){
		$findme="/Ingestion/Main.kjb";
	} elseif($stato == "R"){
		$findme="Main.R";
	}elseif($stato == "Java"){
		$findme="Main.java";
	}
	else{
		$findme="altro";
	}
	//1)TROVARE IL FILE
	//2)ANALIZZARLO
	//3)EFFETTUARE LA QUERY
	
	if($findme ==""){
		$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='Not specified File Format-".$data."' WHERE `id`='".$val."'";
		$result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
			$updatedStatuses[$val] ='Not specified File Format-"'.$data.'"';
		//echo ("Il file appartiene a un formalismo sconoscito!<br><br>");
	}
	
	elseif($findme =="altro"){
			$cartella = 'uploads/'.$list[$i]['username'].'/'.$data_C_mod4;
					$posizione = $cartella.'/'.$list[$i]['name'];

			//$moved=move_uploaded_file($posizione,$repository_destination);
			//if($moved){
			$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='OK - ".$data."' WHERE `id`='".$val."'";
			$result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
			//}
			$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
			url_get($url);
	}
	else{
		//echo ("Il file appartiene a un formalismo CONOSCIUTO!<br><br>");
		$zip = new ZipArchive;
		$cartella = 'uploads/'.$list[$i]['username'].'/'.$data_C_mod4;
		//$creaCartella=mkdir($cartella, 0777, true);
		$n1 = explode(".", $list[$i]['name']);
		$nome = $n1[0];
		$posizione = $cartella.'/'.$nome.'/'.$list[$i]['name'];
		//echo $posizione;
		if ($zip->open($posizione) === TRUE){
			//echo ("Il file è stato trovato in 	".$cartella.'/'.$nome."<br><br>");
			//Il file è stato trovato. Ora controlliamo il formalismo
						$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='OK - ".$data."' WHERE `id`='".$val."'";
					 $result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
					 //
					 $url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
					url_get($url);
					 //
					 $updatedStatuses[$val] ='OK - '.$data;
						$array_lista = [];
						 $count = 0;
                           for ($j = 0; $j < $zip->numFiles; $j++) {
										
                                       $filename = $zip->getNameIndex($j);
									   $array_lista[$j] = $filename;
									   $pos = strpos($filename, $findme);
									   if ($pos!== false){
										   $count++;
									   }	   
                              }
							  if ($count >0){
											//echo ("Il file: ".$findme." Esiste");
										  //ATTUALMEMNTE ONLINE//
										  //$zip->extractTo('/srv/share/jobs/'.$utente_us.'/'.$data_C_mod4.'/');
										 opendir($repository_destination);
										 //$zip->extractTo($repository_destination.$utente_us.'/'.$data_C_mod4.'/');
										 $zip->extractTo($repository_destination.$list[$i]['username'].'/'.$data_C_mod4.'/');
										 //echo $repository_destination.$list[$i]['username'].'/'.$data_C_mod4.'/';
										  //
										 // $zip->extractTo($repository_files.$utente_us.'/'.$data_C_mod4.'/');
										 // $zip->extractTo('../srv/share/jobs/'.$utente_us.'/'.$data_C_mod4.'/');
										  //////
											$zip->close(); 
											}else{
												$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='File format not correct - ".$data."' WHERE `id`='".$val."'";
												$result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
												$updatedStatuses[$val] ='File format not correct - '.$data;
											}
			//
		}else{
			//echo ("Il file NON è stato trovato in	".$cartella.'/'.$nome."<br><br>");
			//Il file NON è stato trovato
			$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='File not found-".$data."' WHERE `id`='".$val."'";
			 $result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
			$updatedStatuses[$val] ='File not found-'.$data;
			// echo ("Formalismo errato!");
		}
	}
	//
}
//echo json_encode($updatedStatuses);

