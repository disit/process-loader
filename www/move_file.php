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
	
	
	$id=$_POST['file_id'];
	$user_vecchio=$_POST['user_vecchio'];
	$user_nuovo=$_POST['user_nuovo'];
	$creation_date=$_POST['creation_date'];
	$file_name=$_POST['file_name'];
	$stato=$_POST['file_type'];
	$data_C_mod = $creation_date;
	$data_C_mod2 = str_replace(":","-",$data_C_mod);
	$data_C_mod3 = str_replace(":","-",$data_C_mod2);
	$data_C_mod4 = str_replace(" ","-",$data_C_mod3);
	
	//Sposta_zip
	$vecchia_pos='uploads/'.$user_vecchio.'/'.$data_C_mod4.'/';
	$nuova_pos='uploads/'.$user_nuovo.'/'.$data_C_mod4.'/';
	
	
	//Sposta_share
	$vecchia_rep='/'.$repository_destination.$user_vecchio.'/'.$data_C_mod4.'/';
	$nuova_rep='/'.$repository_destination.$user_nuovo.'/'.$data_C_mod4.'/';
	

	$move1 = rename($vecchia_pos,$nuova_pos);
	//
	if($move1){	
		//Trasferisce zip sul repository.
					if ($stato == 'ETL'){
								$nome = "";
								$zip = new ZipArchive;
												$cartella = 'uploads/'.$user_nuovo.'/'.$data_C_mod4;
												$n1 = explode(".", $file_name);
												$nome = $n1[0];
												$posizione = $cartella.'/'.$nome.'/'.$file_name;											
												echo $posizione;
												if ($zip->open($posizione) === TRUE){
													opendir($repository_destination);
													$zip->extractTo($repository_destination.$user_nuovo.'/'.$data_C_mod4.'/');
													$zip->close(); 
													echo 'Fatto!';
										}else{
											echo 'Trasferimento non riuscito!';
										}
									}
		//
		$query="UPDATE `uploaded_files` SET `Username` = '".$user_nuovo."' WHERE `Id` = '".$id."'" ;
		$resultUser = mysqli_query($link, $query) or die(mysqli_error($link));
		echo ('ok - modifica utente');
		
		$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
						url_get($url);
		//header("location:transfer_file_property.php");
			if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:transfer_file_property.php?showFrame=false");
													}else{
													header ("location:transfer_file_property.php");
													}	
												}else{
												header ("location:transfer_file_property.php");
												}
	}else{
		//echo ('location:transfer_file_property.php?message=error');
		if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:transfer_file_property.php?message=error&showFrame=false");
													}else{
													header ("location:transfer_file_property.php?message=error");
													}	
												}else{
												header ("location:transfer_file_property.php?message=error");
												}
	}
	
?>