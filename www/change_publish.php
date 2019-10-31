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
include("curl.php");
if (isset ($_SESSION['username'])&& isset($_SESSION['role'])){
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);

$id_pub=$_POST["id_pub"];
$status_pub=$_POST["status_pub"];
echo $id_file;
echo $status_pub;
//fare un check sui parametri
$nature_pub=$_POST["nature_pub"];
$subnature_pub=$_POST["subnature_pub"];
$access_pub=$_POST["access_pub"];
$format_pub=$_POST["format_pub"];
$lic_pub=$_POST["lic_pub"];
$tip_pub=$_POST["tip_pub"];

if ($status_pub == '0'){
			$change = 1;
		}else {
			$change = 0;
		}

$control = 0;
if (($nature_pub == null)|| ($nature_pub == "")||($nature_pub=="ToBeDefined")){
	$control = 1;
}
if (($subnature_pub == null)|| ($subnature_pub == "")||($subnature_pub=="ToBeDefined")){
	$control = 1;
}
if (($lic_pub=="Private")||($lic_pub=="")||($lic_pub==null)){
	$control = 1;
}
if (($format_pub=="Others")||($format_pub=="")||($format_pub==null)||($format_pub=="ToBeDefined")){
	$control = 1;
}
if (($access_pub == null)|| ($access_pub == "")||($access_pub=="ToBeDefined")){
	if (($tip_pub=='ETL')||($tip_pub=='R')||($tip_pub=='Java')){
	$control = 1;
	}
}


if (($control == 1) && ($change == 1)){
	if (isset($_REQUEST['editor'])){
		 if ($_REQUEST['editor'] == 'MicroService'){
					if (isset($_REQUEST['showFrame'])){
								if ($_REQUEST['showFrame'] == 'false'){
										header ("location:MicroService_editor.php?showFrame=false&not_valid=error");
								}else{
										header ("location:MicroService_editor.php?not_valid=error");
									}	
						}else{
							header ("location:MicroService_editor.php?not_valid=error");
						}
			}else{
					 if ($_REQUEST['editor'] == 'DataAnalyticMicroService'){
							if (isset($_REQUEST['showFrame'])){
								if ($_REQUEST['showFrame'] == 'false'){
										header ("location:dataAnalyticMicroService_editor.php?showFrame=false&not_valid=error");
								}else{
										header ("location:dataAnalyticMicroService_editor.php?not_valid=error");
									}	
						}else{
							header ("location:dataAnalyticMicroService_editor.php?not_valid=error");
						}
					 } 
			}
		}else{
					if (isset($_REQUEST['showFrame'])){
							if ($_REQUEST['showFrame'] == 'false'){
								header ("location:file_archive.php?showFrame=false&not_valid=error");
							}else{
								header ("location:file_archive.php?not_valid=error");
							}	
						}else{
							header ("location:file_archive.php?not_valid=error");
						}
		}
}
else {
//ELIMINAZIONE ZIP DALL'ARCHIVIO.
		
		$creation_date_jb=date("Y-m-d H:i:s");

		$sql3 = "UPDATE `uploaded_files` SET `Date_of_publication` = '".$creation_date_jb."', `uploaded_files`.`Public`='".$change."' WHERE `uploaded_files`.`id`='".$id_pub."'";
		$result3 = mysqli_query($link, $sql3) or die(mysqli_error($link));

		mysqli_close($link);
		$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
		url_get($url);
		//header("location:file_archive.php");
	if (isset($_REQUEST['editor'])){
					if ($_REQUEST['editor'] == 'MicroService'){
					if (isset($_REQUEST['showFrame'])){
								if ($_REQUEST['showFrame'] == 'false'){
										header ("location:MicroService_editor.php?showFrame=false");
								}else{
										header ("location:MicroService_editor.php");
									}	
						}else{
							header ("location:MicroService_editor.php");
						}
			}else{
					 if ($_REQUEST['editor'] == 'DataAnalyticMicroService'){
							if (isset($_REQUEST['showFrame'])){
								if ($_REQUEST['showFrame'] == 'false'){
										header ("location:dataAnalyticMicroService_editor.php?showFrame=false");
								}else{
										header ("location:dataAnalyticMicroService_editor.php");
									}	
						}else{
							header ("location:dataAnalyticMicroService_editor.php");
						}
					 } 
			}
		}else{
		if (isset($_REQUEST['showFrame'])){
			if ($_REQUEST['showFrame'] == 'false'){
				header ("location:file_archive.php?showFrame=false");
			}else{
				header ("location:file_archive.php");
			}	
		}else{
			header ("location:file_archive.php");
		}
	}
}
}else{
	header ("location:page.php");
}
?>