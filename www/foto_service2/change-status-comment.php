<?php
	include ('../config.php');
	include('../curl.php');
	include('../external_service.php');
	$link = mysqli_connect($host_photo, $username_photo, $password_photo) or die("failed to connect to server !!");
	//$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname_photo);
	//mysqli_select_db($link, $dbname);

	$id=intval($_POST['id']);
	$new_status=$_POST['new_status'];


	//$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	//mysqli_set_charset($link, 'utf8');
	//mysqli_select_db($link, $dbname);
	$query = "UPDATE ServiceComment SET status = '$new_status' WHERE id='$id'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
	echo($id);




