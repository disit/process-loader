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

if (isset($_REQUEST['n_det'])){
$n_det = $_REQUEST['n_det'];
}else{
$n_det = "";
}

if (isset($_REQUEST['g_det'])){
$g_det = $_REQUEST['g_det'];
}else{
	$g_det = "";
}

if (isset($_REQUEST['ip_det'])){
	$ip_det = $_REQUEST['ip_det'];
	
}else{
	$ip_det = $ip_SCE;
}

$hostQ = $ip_det;
$usernameQ = $username;
$passwordQ = $password;

$dbnameQ = $SCE_dbname;


$linkQ = mysqli_connect($hostQ, $usernameQ, $passwordQ) or die("failed to connect to server !!");
mysqli_set_charset($linkQ, 'utf8');
mysqli_select_db($linkQ, $dbnameQ);
$today=Date('Y-m-d');


//$query="SELECT * FROM quartz.QRTZ_LOGS WHERE JOB_NAME='".$n_det."' AND JOB_GROUP='".$g_det."' AND DATE LIKE'".$today."%'";
$query="SELECT * FROM quartz.QRTZ_LOGS WHERE JOB_NAME='".$n_det."' AND JOB_GROUP='".$g_det."' ORDER BY ID DESC LIMIT 10 ";

//echo $query;
$result = mysqli_query($linkQ, $query) or die(mysqli_error($linkQ));

$detail_list = array();
		if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $sched= array(
							"details" => array(
								 "id" => $row['ID'],
                                 "date" => $row['DATE'],
                                 "status" => $row['STATUS']
								)
							);
                array_push($detail_list, $sched);
            }
        }
		echo json_encode($detail_list);
        mysqli_close($linkQ);
		
?>