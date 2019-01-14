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
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);

$id_file=$_POST["id_file_del"];
$path_del=$_POST["path_del"];
echo $id_file;
echo $path_del;
//DELETE FILE
if (file_exists($path_del)) {
unlink($path_del);
$new_del= explode('/', $path_del,2);
rmdir($new_del);
}
//
$sql3 = "DELETE FROM `uploaded_files` WHERE `uploaded_files`.`Id`='".$id_file."'";
$result3 = mysqli_query($link, $sql3) or die(mysqli_error($link));
$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
url_get($url);

mysqli_close($link);

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

?>