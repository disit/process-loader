<?php
/* 
Resource Manager - Process Loader
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
along with this program.  If not, see <http://www.gnu.org/licenses/>. 
*/
   
include("config.php");
include("curl.php");  


$control = 0;
/*if (isset($_POST['protocol'])){
	//ok
}*/

if (isset($_POST['lic'])){
	//ok
	$lic_pub=$_POST['lic'];
	if (($lic_pub=="Private")||($lic_pub=="")||($lic_pub==null)){
	$control = 1;
	}
}

if (isset($_POST['form'])){
	//ok
	$format_pub=$_POST['form'];
	if (($format_pub=="Others")||($format_pub=="")||($format_pub==null)||($format_pub=="ToBeDefined")){
	$control = 1;
	}
}

if (isset($_POST['id'])){
$id=$_POST['id'];
}

if (isset($_POST['status'])){
$status=$_POST['status'];
}

if ($status == 'false'){
	$mod_s = 1;
}else{
	$mod_s = 0;
}

if ($status == 'true'){
	$mod_s = 0;
}else{
	$mod_s = 1;
}

$creation_date_jb=date("Y-m-d H:i:s");

if ($control == 1){
		if (isset($_REQUEST['showFrame'])){
							if ($_REQUEST['showFrame'] == 'false'){
								header ("location:page.php?showFrame=false&error_status=ok");
								die();
							}else{
								header ("location:page.php?error_status=ok");
								die();
							}	
						}else{
							header ("location:page.php?error_status=ok");

			}
	///////////////////	
}else{
			$query = 'UPDATE uploaded_files SET	Public="'.$mod_s.'", Date_of_publication="'.$creation_date_jb.'" WHERE Id ="'.$id.'"';
					$query_job_type = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
					$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
					url_get($url);
			/////
			if (isset($_REQUEST['showFrame'])){
							if ($_REQUEST['showFrame'] == 'false'){
								header ("location:page.php?showFrame=false&modified_status=ok");
								die();
							}else{
								header ("location:page.php?modified_status=ok");
								die();
							}	
						}else{
							header ("location:page.php?modified_status=ok");
							die();
			}
}
?>