<?php
include ('external_service.php');
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

$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
//echo $dbname;

$query = "SELECT ownership.* FROM profiledb.ownership;";

$result = mysqli_query($link, $query) or die(mysqli_error($link));
$process_list = array();
        if ($result->num_rows > 0) {
while($row = mysqli_fetch_assoc($result)){
				$process = array("process" => array(
                        "id" => $row['id'],
                        "username" => $row['username'],
                        "elementId" => $row['elementId'],
                        "elementType" => $row['elementType'],
                        "elementUrl" => $row['elementUrl'],
						"elementDetails" => $row['elementDetails'],
						"lastCheck"=>$row['lastCheck']
						)
					);
                array_push($process_list, $process);
		}
	}
	echo json_encode($process_list);
	mysqli_close($link);

?>