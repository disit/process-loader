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

//include('config.php'); 
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
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
*/
$id=$_REQUEST['id'];

if (isset($_REQUEST['limit'])){
	$limit = $_REQUEST['limit'];
}else{
	$limit = 10;
}

if (isset($_REQUEST['currente_page'])){
	$currente_page = $_REQUEST['currente_page'];
}else{
	$currente_page = 1;
}

if (isset($_REQUEST['filter'])){
	
	if (isset($_REQUEST['date']) && ($_REQUEST['date'] !="")){
		$date = "	AND date>='".$_REQUEST['date']."'	";
		$date01 = "	AND last_date>='".$_REQUEST['date']."'	";
	}else{
		$date = "";
		$date01 = "";
	}
	
	if (isset($_REQUEST['date2']) && ($_REQUEST['date2'] !="")){
		$date2 = "	AND date<='".$_REQUEST['date2']."'	";
		$date02 = "	AND last_date<='".$_REQUEST['date2']."'	";
	}else{
		$date2 = "";
		$date02 = "";
	}
	
	if (isset($_REQUEST['filter']) && ($_REQUEST['filter'] !="")){
		$value_filter = "	AND value LIKE '%".$_REQUEST['filter']."%'	";
		$value_filter0 = "	AND last_value LIKE '%".$_REQUEST['filter']."%'	";
	}else{
		$value_filter = "";
		$value_filter0 = "";
	}

	if ($currente_page > 0){

		$start = (($currente_page) * $limit);
		$end = $start + $limit;

	}else{
		$start = 0;
		$end = $limit;
	}

		$query="SELECT * FROM processloader_db.kpi_values WHERE kpi='".$id."' ".$date." ".$date2." ".$value_filter." ORDER BY date DESC LIMIT ".$limit." OFFSET ".$start;
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
				$process_list = array();
				if ($result->num_rows > 0) {
					while ($row = mysqli_fetch_array($result)) {
						$process = array("process" => array(
								"id" => $row['id'],
								"kpi" => $row['kpi'],
								"date" => $row['date'],
								"value" => $row['value']
						)
						);
						array_push($process_list, $process);
					}
				}else{
					//
					$query2="SELECT * FROM processloader_db.DashboardWizard WHERE id='".$id."' ".$date01." ".$date02." ".$value_filter0." ORDER BY last_date DESC LIMIT ".$limit." OFFSET ".$start;
					$result2 = mysqli_query($link, $query2) or die(mysqli_error($link));
							if ($result2->num_rows > 0) {
									while ($row = mysqli_fetch_array($result2)) {
										$process = array("process" => array(
												"id" => $row['id'],
												"kpi" => $row['id'],
												"date" => $row['last_date'],
												"value" => $row['last_value']
										)
										);
										array_push($process_list, $process);
									}
								}
					//
				}

				echo json_encode($process_list);
				mysqli_close($link);
}else{

		if (isset($_REQUEST['value_filter'])){
			$value_filter = $_REQUEST['value_filter'];
		}else{
			$value_filter = "";
		}
		if ($currente_page > 0){

		$start = (($currente_page) * $limit);
		$end = $start + $limit;
	}else{
		$start = 0;
		$end = $limit;
	}
		
		$query="SELECT * FROM processloader_db.kpi_values WHERE kpi='".$id."' ".$value_filter." ORDER BY date DESC LIMIT ".$limit;

		$result = mysqli_query($link, $query) or die(mysqli_error($link));
				$process_list = array();
				if ($result->num_rows > 0) {
					while ($row = mysqli_fetch_array($result)) {
						$process = array("process" => array(
								"id" => $row['id'],
								"kpi" => $row['kpi'],
								"date" => $row['date'],
								"value" => $row['value']
						)
						);
						array_push($process_list, $process);
					}
				}else{
					//
					$query2="SELECT * FROM processloader_db.DashboardWizard WHERE id='".$id."' ORDER BY last_date DESC LIMIT ".$limit;
					$result2 = mysqli_query($link, $query2) or die(mysqli_error($link));
							if ($result2->num_rows > 0) {
									while ($row = mysqli_fetch_array($result2)) {
										$process = array("process" => array(
												"id" => $row['id'],
												"kpi" => $row['id'],
												"date" => $row['last_date'],
												"value" => $row['last_value']
										)
										);
										array_push($process_list, $process);
									}
								}
					//
				}
				echo json_encode($process_list);
				mysqli_close($link);
}
?>