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
if(isset($_REQUEST['add_scheduler'])){
	$address_sc = $_POST['address'];
	$repository_sc=$_POST['repository'];
	$desc_sc=$_POST['desc'];
	$type_sc=$_POST['type'];
	//
	$name=$_POST['name'];
	$path=$_POST['path'];
	$home=$_POST['home'];
	$data_integration=$_POST['data_integration_path'];
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

?>