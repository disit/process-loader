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
				$id_run = $_GET['id_run'];
				$n_run = $_GET['n_run'];
				$g_run = $_GET['g_run'];
				echo ('$id_run: '.$id_run);
				echo ('$n_run:  '.$n_run);
				echo ('$g_run:  '.$g_run);
				$sql = "UPDATE  `processes` SET `processes`.`Status`='RUNNING' WHERE `id`='".$id_run."'";
				$result = mysqli_query($link, $sql) or die(mysqli_error($link));
				//archivio 
				$date_run = date("Y-m-d H:i:s");
				$query="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$date_run."','".$id_run."','".$n_run."','".$g_run."','RUN')";
				$archive = mysqli_query($link, $query) or die(mysqli_error($link));
				mysqli_close($link);
				header("location:process_loader.php");
			} elseif($archive == 'stop'){
				$id_pause = $_GET['id_pause'];
				$n_pause = $_GET['n_pause'];
				$g_pause = $_GET['g_pause'];
				$sql4 = "UPDATE  `processes` SET `status`='PAUSE' WHERE `id`='".$id_pause."'";
				$result4 = mysqli_query($link, $sql4) or die(mysqli_error($link));
				//archivio 
				$date_pause = date("Y-m-d H:i:s");
				$query4="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$date_pause."','".$id_pause."','".$n_pause."','".$g_pause."','PAUSE')";
				$archive4 = mysqli_query($link, $query4) or die(mysqli_error($link));
				mysqli_close($link);
				header("location:process_loader.php");
			}
			elseif ($archive='remove'){
				$id_rem = $_GET['id_rem'];
				$n_rem = $_GET['n_rem'];
				$g_rem = $_GET['g_rem'];
				$sql3 = "DELETE FROM `processes` WHERE `processes`.`id`='".$id_rem."'";
				$result3 = mysqli_query($link, $sql3) or die(mysqli_error($link));
					//archivio 
				$date_rem = date("Y-m-d H:i:s");
				$query3="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$date_rem."','".$id_rem."','".$n_rem."','".$g_rem."','DELETE')";
				$archive3 = mysqli_query($link, $query3) or die(mysqli_error($link));
				mysqli_close($link);
				header("location:process_loader.php");
			}
			}else{
			echo ("ERRORE CREAZIONE");
}
?>