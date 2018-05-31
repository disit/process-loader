<?php
include'config.php';
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);

if(isset($_REQUEST['run_req'])) {
	$id_run = $_POST['id_run'];
	$n_run = $_POST['n_run'];
	$g_run = $_POST['g_run'];
	$sql = "UPDATE  `processes` SET `processes`.`Status`='RUNNING' WHERE `id`='".$id_run."'";
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	//archivio 
	$date_run = date("Y-m-d H:i:s");
	$query="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$date_run."','".$id_run."','".$n_run."','".$g_run."','RUN')";
	$archive = mysqli_query($link, $query) or die(mysqli_error($link));
	mysqli_close($link);
	header("location:process_loader.php");
}elseif(isset($_REQUEST['detail_req'])){
	$id_det = $_POST['id_det'];
	$n_det = $_POST['n_det'];
	$g_det = $_POST['g_det'];
	//echo '<div>'.$id_det.'</div>';
	header("location:process_loader.php");
} elseif(isset($_REQUEST['remove_req'])){	
	$id_rem = $_POST['id_rem'];
	$n_rem = $_POST['n_rem'];
	$g_rem = $_POST['g_rem'];
	$sql3 = "DELETE FROM `processes` WHERE `processes`.`id`='".$id_rem."'";
	$result3 = mysqli_query($link, $sql3) or die(mysqli_error($link));
	//archivio 
	$date_rem = date("Y-m-d H:i:s");
	$query3="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$date_rem."','".$id_rem."','".$n_rem."','".$g_rem."','DELETE')";
	$archive3 = mysqli_query($link, $query3) or die(mysqli_error($link));
	mysqli_close($link);
	header("location:process_loader.php");
} elseif(isset($_REQUEST['stop_req'])){
	$id_pause = $_POST['id_pause'];
	$n_pause = $_POST['n_pause'];
	$g_pause = $_POST['g_pause'];
	$sql4 = "UPDATE  `processes` SET `status`='PAUSE' WHERE `id`='".$id_pause."'";
	$result4 = mysqli_query($link, $sql4) or die(mysqli_error($link));
	//archivio 
	$date_pause = date("Y-m-d H:i:s");
	$query4="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$date_pause."','".$id_pause."','".$n_pause."','".$g_pause."','PAUSE')";
	$archive4 = mysqli_query($link, $query4) or die(mysqli_error($link));
	mysqli_close($link);
	header("location:process_loader.php");
} else {
	echo '<div>ERRORE INVIO DEI DATI</div>';
}
?>