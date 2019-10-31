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

include('config.php'); // Includes Login Script
include('control.php');
if (isset ($_SESSION['username'])&& isset($_SESSION['role'])){
if(isset($_REQUEST['add_scheduler'])){
	$address_sc0 = mysqli_real_escape_string($connessione_al_server,$_POST['address']);
	$address_sc = filter_var($address_sc0, FILTER_SANITIZE_STRING);
	//
	$repository_sc0=mysqli_real_escape_string($connessione_al_server,$_POST['repository']);
	$repository_sc = filter_var($repository_sc0, FILTER_SANITIZE_STRING);
	//
	$desc_sc0=mysqli_real_escape_string($connessione_al_server,$_POST['desc']);
	$desc_sc = filter_var($desc_sc0, FILTER_SANITIZE_STRING);
	//
	$type_sc0=mysqli_real_escape_string($connessione_al_server,$_POST['type']);
	$type_sc = filter_var($type_sc0, FILTER_SANITIZE_STRING);
	//
	$name0=mysqli_real_escape_string($connessione_al_server,$_POST['name']);
	$name = filter_var($name0, FILTER_SANITIZE_STRING);
	//
	$path0=mysqli_real_escape_string($connessione_al_server,$_POST['path']);
	$path = filter_var($path0, FILTER_SANITIZE_STRING);
	//
	$home0=mysqli_real_escape_string($connessione_al_server,$_POST['home']);
	$home = filter_var($home0, FILTER_SANITIZE_STRING);
	//
	$data_integration0=mysqli_real_escape_string($connessione_al_server,$_POST['data_integration_path']);
	$data_integration = filter_var($data_integration0, FILTER_SANITIZE_STRING);
   //
   $query='INSERT INTO `schedulers` (`Id`,`Ip_address`,`repository`,`type`,`Description`,`name`,`data_integration_path`,`process_path`,`DDI_HOME`)VALUES (NULL,"'.$address_sc.'","'.$repository_sc.'","'.$type_sc.'","'.$desc_sc.'","'.$name.'","'.$data_integration.'","'.$path.'","'.$home.'")';
   $query_job_type = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
  /// header("location:schedulers_mng.php");
							if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:schedulers_mng.php?showFrame=false");
													}else{
													header ("location:schedulers_mng.php");
													}	
												}else{
												header ("location:schedulers_mng.php");
												}
  
  ////
}else{
	///header("location:schedulers_mng.php?message=error");
										if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:schedulers_mng.php?showFrame=false&message=error");
													}else{
													header ("location:schedulers_mng.php&message=error");
													}	
												}else{
												header ("location:schedulers_mng.php&message=error");
												}
	
	/////
}
}else{
	header ("location:page.php");
}	
?>