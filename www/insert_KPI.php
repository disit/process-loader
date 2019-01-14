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
   
//include("config.php");
include("curl.php");
include('external_service.php');
/*
$link = mysqli_connect($host_kpi $username_kpi, $password_kpi);
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname_kpi);
	*/
$link = mysqli_connect($host_kpi, $username_kpi, $password_kpi) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_kpi);	
/*
if (isset($_REQUEST['datatime_of_insert'])){
	$datatime_of_insert = $_REQUEST['datatime_of_insert'];
}else{
	$datatime_of_insert = "";
}*/

$datatime_of_insert = date('Y-m-d H:i:s'); ;

if (isset($_REQUEST['other_nature'])&&($_REQUEST['nature']=='Other...')){
	$nature = $_REQUEST['other_nature'];	
}else{
	if (isset($_REQUEST['nature'])){
	$nature = $_REQUEST['nature'];	
	}else{
		$nature = "";
	}
}
//
if (isset($_REQUEST['other_subnature'])&&($_REQUEST['subnature'] =='Other...')){
	$subnature = $_REQUEST['other_subnature'];
}else{
		if (isset($_REQUEST['subnature'])){
		$subnature = $_REQUEST['subnature'];
		}else{
		$subnature = "";	
		}
}
//
if (isset($_REQUEST['valuename'])){
$valuename = $_REQUEST['valuename'];	
}else{
$valuename = "";	
}

if (isset($_REQUEST['valuetype'])){
$valuetype = $_REQUEST['valuetype'];	
}else{
$valuetype = "";	
}

if(isset($_REQUEST['datatype'])){
$datatype = $_REQUEST['datatype'];	
}else{
$datatype = "";	
}

if(isset($_REQUEST['ownership'])){
$ownership = $_REQUEST['ownership'];
}else{
$ownership = "";
}

if (isset($_REQUEST['description'])){
	$description = $_REQUEST['description'];
}else{
	$description = "";
}

if(isset($_REQUEST['info'])){
	$info = $_REQUEST['info'];
}else{
	$info = "";
}

if (isset($_REQUEST['latitudes'])){
	$latitude = $_REQUEST['latitudes'];
}else{
	$latitude = "";
}

if(isset($_REQUEST['longitudes'])){
	$longitudes = $_REQUEST['longitudes'];
}else{
	$longitudes = "";
}
if(isset($_REQUEST['paramters'])){
	$parameters = $_REQUEST['paramters'];
}else{
	$parameters = "";
}
//
//ESISTE GIà?
$verify = "select COUNT(*) FROM processloader_db.DashboardWizard WHERE high_level_type='KPI' AND sub_nature='".$subnature."' AND nature='".$nature."' AND unique_name_id='".$valuename ."'";
$res_ver = mysqli_query($link,$verify) or die ("Operation failed".mysqli_error());
$count_ver = array();
				if ($res_ver->num_rows > 0) {
					while($row = mysqli_fetch_assoc($res_ver)){
						array_push($count_ver, $row);
					}
				}
				//var_dump($count_list);
				$total_rows = $count_ver[0]["COUNT(*)"];
if ($total_rows > 0){
		if (isset($_REQUEST['showFrame'])){
			if ($_REQUEST['showFrame'] == 'false'){
				header ("location:KPI_editor.php?showFrame=false&error_unique_key=duplicated");
		}else{
				header ("location:KPI_editor.php?showFrame=true&error_unique_key=duplicated");
			}	
		}else{
			header ("location:KPI_editor.php?showFrame=true&error_unique_key=duplicated");
		}
}else{
//
		$max = "SELECT MAX(id) FROM processloader_db.DashboardWizard;";
		$res_max = mysqli_query($link,$max) or die ("Operation failed".mysqli_error());
		$count_list = array();
						if ($res_max->num_rows > 0) {
							while($row = mysqli_fetch_assoc($res_max)){
								array_push($count_list, $row);
							}
						}
						//var_dump($count_list);
						$total_rows = $count_list[0]["MAX(id)"];
						$new_id = $total_rows+1;
		//
		$query_reg="INSERT INTO processloader_db.DashboardWizard (id,nature,high_level_type,sub_nature,low_level_type,unique_name_id,instance_uri,datetime_of_insert,ownership,Description,Info,latitudes,longitudes,parameters,healthiness,unit) VALUES (".$new_id.",'".$nature."','KPI','".$subnature."','".$valuetype."','".$valuename."',NULL,'".$datatime_of_insert."','".$ownership."','".$description."','".$info."','".$latitude."','".$longitudes."','".$parameters."','false','".$datatype."')";
		echo ($query_reg);

		$query_registrazione = mysqli_query($link,$query_reg) or die ("Operation failed".mysqli_error());

		if (isset($_REQUEST['showFrame'])){
			if ($_REQUEST['showFrame'] == 'false'){
				header ("location:KPI_editor.php?showFrame=false");
			}else{
				header ("location:KPI_editor.php?showFrame=true");
			}	
		}else{
			header ("location:KPI_editor.php?showFrame=true");
		}
}
?>