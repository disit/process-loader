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
$id = (int) $_REQUEST['id'];

if (isset($_REQUEST['limit'])){
		$limit = (int) $_REQUEST['limit'];
	}else{
		$limit = 10;
	}

if (isset($_REQUEST['currente_page'])){
		$currente_page = (int) $_REQUEST['currente_page'];
	}else{
		$currente_page = 1;
	}

	if (isset($_REQUEST['filter'])){
		
		if (isset($_REQUEST['date']) && ($_REQUEST['date'] !="")){
			$date = $_REQUEST['date'];
			$date01 = $_REQUEST['date'];
		}else{
			$date = null;
			$date01 = null;
		}
	
		if (isset($_REQUEST['date2']) && ($_REQUEST['date2'] !="")){
			$date2 = $_REQUEST['date2'];
			$date02 = $_REQUEST['date2'];
		}else{
			$date2 = null;
			$date02 = null;
		}
	
		if (isset($_REQUEST['filter']) && ($_REQUEST['filter'] !="")){
			$value_filter = $_REQUEST['filter'];
			$value_filter0 = $_REQUEST['filter'];
		}else{
			$value_filter = null;
			$value_filter0 = null;
		}

	if ($currente_page > 0){

		$start = (($currente_page) * $limit);
		$end = $start + $limit;

	}else{
		$start = 0;
		$end = $limit;
	}

			$conditions = ["kpi = ?"];
			$params = [$id];
			$types = "i";
			if ($date !== null) {
				$conditions[] = "date >= ?";
				$params[] = $date;
				$types .= "s";
			}
			if ($date2 !== null) {
				$conditions[] = "date <= ?";
				$params[] = $date2;
				$types .= "s";
			}
			if ($value_filter !== null) {
				$conditions[] = "value LIKE ?";
				$params[] = "%" . $value_filter . "%";
				$types .= "s";
			}
			$query = "SELECT * FROM processloader_db.kpi_values WHERE " . implode(" AND ", $conditions) . " ORDER BY date DESC LIMIT ? OFFSET ?";
			$params[] = $limit;
			$params[] = $start;
			$types .= "ii";
			$stmt = mysqli_prepare($link, $query);
			if (!$stmt) {
				die(mysqli_error($link));
			}
			$bind_names = [];
			$bind_names[] = $types;
			for ($i = 0; $i < count($params); $i++) {
				$bind_names[] = &$params[$i];
			}
			call_user_func_array('mysqli_stmt_bind_param', $bind_names);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
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
						$conditions2 = ["id = ?"];
						$params2 = [$id];
						$types2 = "i";
						if ($date01 !== null) {
							$conditions2[] = "last_date >= ?";
							$params2[] = $date01;
							$types2 .= "s";
						}
						if ($date02 !== null) {
							$conditions2[] = "last_date <= ?";
							$params2[] = $date02;
							$types2 .= "s";
						}
						if ($value_filter0 !== null) {
							$conditions2[] = "last_value LIKE ?";
							$params2[] = "%" . $value_filter0 . "%";
							$types2 .= "s";
						}
						$query2 = "SELECT * FROM processloader_db.DashboardWizard WHERE " . implode(" AND ", $conditions2) . " ORDER BY last_date DESC LIMIT ? OFFSET ?";
						$params2[] = $limit;
						$params2[] = $start;
						$types2 .= "ii";
						$stmt2 = mysqli_prepare($link, $query2);
						if (!$stmt2) {
							die(mysqli_error($link));
						}
						$bind_names2 = [];
						$bind_names2[] = $types2;
						for ($i = 0; $i < count($params2); $i++) {
							$bind_names2[] = &$params2[$i];
						}
						call_user_func_array('mysqli_stmt_bind_param', $bind_names2);
						mysqli_stmt_execute($stmt2);
						$result2 = mysqli_stmt_get_result($stmt2);
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
						mysqli_stmt_close($stmt2);
						//
					}

					echo json_encode($process_list);
					mysqli_stmt_close($stmt);
					mysqli_close($link);
	}else{

		if (isset($_REQUEST['value_filter'])){
			$value_filter = $_REQUEST['value_filter'];
		}else{
			$value_filter = null;
		}
		if ($currente_page > 0){

		$start = (($currente_page) * $limit);
		$end = $start + $limit;
	}else{
		$start = 0;
		$end = $limit;
	}
		
		$conditions = ["kpi = ?"];
		$params = [$id];
		$types = "i";
		if ($value_filter !== null && $value_filter !== "") {
			$conditions[] = "value LIKE ?";
			$params[] = "%" . $value_filter . "%";
			$types .= "s";
		}
		$query="SELECT * FROM processloader_db.kpi_values WHERE " . implode(" AND ", $conditions) . " ORDER BY date DESC LIMIT ?";
		$params[] = $limit;
		$types .= "i";
		$stmt = mysqli_prepare($link, $query);
		if (!$stmt) {
			die(mysqli_error($link));
		}
		$bind_names = [];
		$bind_names[] = $types;
		for ($i = 0; $i < count($params); $i++) {
			$bind_names[] = &$params[$i];
		}
		call_user_func_array('mysqli_stmt_bind_param', $bind_names);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
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
						$query2="SELECT * FROM processloader_db.DashboardWizard WHERE id=? ORDER BY last_date DESC LIMIT ?";
						$stmt2 = mysqli_prepare($link, $query2);
						if (!$stmt2) {
							die(mysqli_error($link));
						}
						mysqli_stmt_bind_param($stmt2, "ii", $id, $limit);
						mysqli_stmt_execute($stmt2);
						$result2 = mysqli_stmt_get_result($stmt2);
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
						mysqli_stmt_close($stmt2);
						//
					}
					echo json_encode($process_list);
					mysqli_stmt_close($stmt);
					mysqli_close($link);
	}
?>
