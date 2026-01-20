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

	$id_file = isset($_POST["id_file_del"]) ? (int)$_POST["id_file_del"] : 0;
	$path_del = isset($_POST["path_del"]) ? $_POST["path_del"] : '';
	$role_att = isset($_SESSION['role']) ? $_SESSION['role'] : '';
	$session_user = isset($_SESSION['username']) ? $_SESSION['username'] : '';
	$admin_roles = array('ToolAdmin', 'AreaManager', 'Manager', 'RootAdmin');

	$stmt_check = mysqli_prepare($link, "SELECT Username FROM `uploaded_files` WHERE `Id`=?");
	if (!$stmt_check) {
		die(mysqli_error($link));
	}
	mysqli_stmt_bind_param($stmt_check, "i", $id_file);
	mysqli_stmt_execute($stmt_check);
	$result_check = mysqli_stmt_get_result($stmt_check);
	$row_check = mysqli_fetch_assoc($result_check);
	mysqli_stmt_close($stmt_check);
	if (!$row_check) {
		mysqli_close($link);
		header("location:file_archive.php");
		exit;
	}
	$owner = $row_check['Username'];
	if (!in_array($role_att, $admin_roles, true) && $owner !== $session_user) {
		mysqli_close($link);
		http_response_code(403);
		exit;
	}
	//DELETE FILE
	if ($path_del !== '') {
		$uploads_root = realpath(__DIR__ . '/uploads');
		$target = realpath($path_del);
		if ($uploads_root && $target && strpos($target, $uploads_root . DIRECTORY_SEPARATOR) === 0 && is_file($target)) {
			unlink($target);
			$dir = dirname($target);
			if ($dir !== $uploads_root && strpos($dir, $uploads_root . DIRECTORY_SEPARATOR) === 0) {
				@rmdir($dir);
			}
		}
	}
//
$stmt = mysqli_prepare($link, "DELETE FROM `uploaded_files` WHERE `uploaded_files`.`Id`=?");
if (!$stmt) {
	die(mysqli_error($link));
}
	$id_file_int = (int) $id_file;
	mysqli_stmt_bind_param($stmt, "i", $id_file_int);
$result3 = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
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
}else{
	header ("location:page.php");
}
?>
