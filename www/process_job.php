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
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
echo ('Archivio = '.$_GET['archive']);

if (isset($_GET['archive']) && !empty($_GET['archive'])) {
		$archive = $_GET['archive'];
		//echo ('Archivio = '.$archive);
				if ($archive == 'run'){
					$id_run = isset($_GET['id_run']) ? (int)$_GET['id_run'] : 0;
					$n_run = isset($_GET['n_run']) ? $_GET['n_run'] : '';
					$g_run = isset($_GET['g_run']) ? $_GET['g_run'] : '';
					echo ('$id_run: '.$id_run);
					echo ('$n_run:  '.$n_run);
					echo ('$g_run:  '.$g_run);
					$sql = "UPDATE  `processes` SET `processes`.`Status`='RUNNING' WHERE `id`=?";
					$stmt = mysqli_prepare($link, $sql);
					if (!$stmt) {
						die(mysqli_error($link));
					}
					mysqli_stmt_bind_param($stmt, "i", $id_run);
					$result = mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
					//archivio 
					$date_run = date("Y-m-d H:i:s");
					$query="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,?,?,?,?,?)";
					$stmt = mysqli_prepare($link, $query);
					if (!$stmt) {
						die(mysqli_error($link));
					}
					$desc = 'RUN';
					mysqli_stmt_bind_param($stmt, "sisss", $date_run, $id_run, $n_run, $g_run, $desc);
					$archive = mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
					mysqli_close($link);
					header("location:process_loader.php");
				} elseif($archive == 'stop'){
					$id_pause = isset($_GET['id_pause']) ? (int)$_GET['id_pause'] : 0;
					$n_pause = isset($_GET['n_pause']) ? $_GET['n_pause'] : '';
					$g_pause = isset($_GET['g_pause']) ? $_GET['g_pause'] : '';
					$sql4 = "UPDATE  `processes` SET `status`='PAUSE' WHERE `id`=?";
					$stmt = mysqli_prepare($link, $sql4);
					if (!$stmt) {
						die(mysqli_error($link));
					}
					mysqli_stmt_bind_param($stmt, "i", $id_pause);
					$result4 = mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
					//archivio 
					$date_pause = date("Y-m-d H:i:s");
					$query4="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,?,?,?,?,?)";
					$stmt = mysqli_prepare($link, $query4);
					if (!$stmt) {
						die(mysqli_error($link));
					}
					$desc = 'PAUSE';
					mysqli_stmt_bind_param($stmt, "sisss", $date_pause, $id_pause, $n_pause, $g_pause, $desc);
					$archive4 = mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
					mysqli_close($link);
					header("location:process_loader.php");
				}
				elseif ($archive == 'remove'){
					$id_rem = isset($_GET['id_rem']) ? (int)$_GET['id_rem'] : 0;
					$n_rem = isset($_GET['n_rem']) ? $_GET['n_rem'] : '';
					$g_rem = isset($_GET['g_rem']) ? $_GET['g_rem'] : '';
					$sql3 = "DELETE FROM `processes` WHERE `processes`.`id`=?";
					$stmt = mysqli_prepare($link, $sql3);
					if (!$stmt) {
						die(mysqli_error($link));
					}
					mysqli_stmt_bind_param($stmt, "i", $id_rem);
					$result3 = mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
						//archivio 
					$date_rem = date("Y-m-d H:i:s");
					$query3="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,?,?,?,?,?)";
					$stmt = mysqli_prepare($link, $query3);
					if (!$stmt) {
						die(mysqli_error($link));
					}
					$desc = 'DELETE';
					mysqli_stmt_bind_param($stmt, "sisss", $date_rem, $id_rem, $n_rem, $g_rem, $desc);
					$archive3 = mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
					mysqli_close($link);
					header("location:process_loader.php");
				}
			}else{
			echo ("ERRORE CREAZIONE");
}
?>
