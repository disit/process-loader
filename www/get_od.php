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

include('config.php'); // Includes Login Script
include('external_service.php');
//
$link = new PDO("pgsql:host=".$host_od.";dbname=".$dbname_od, $username_od, $password_od) or die("failed to connect to server !!");
$action = $_REQUEST['action'];
if ($action == 'get_date_info') {
    $od_id = $_REQUEST['od_id'];
    $precision = $_REQUEST['precision'];
    $table_id = $_REQUEST['table_id'];
    $query_date = "";
    if ($table_id == 'od_data_mgrs'){
        $query_date = "SELECT MIN(from_date) as min_date, MAX(to_date) as max_date, COUNT(DISTINCT from_date) as count_number FROM ".$table_id." WHERE od_id='".$od_id."' AND precision=".$precision." GROUP BY od_id, precision";
    }else if($table_id == 'od_data'){
        $query_date = "SELECT MIN(from_date) as min_date, MAX(to_date) as max_date, COUNT(DISTINCT from_date) as count_number FROM ".$table_id." WHERE od_id='".$od_id."' AND precision is null GROUP BY od_id, precision";
    }
    if($query_date != ""){
        $result = $link->query($query_date) or die($link->errorInfo());
        $process_list = array();
        foreach ($result as $row) {
            $min_date = $row['min_date'];
            $max_date = $row['max_date'];
            $count_number = $row['count_number'];
            $listFile = array(
                "min_date" => $min_date,
                "max_date" => $max_date,
                "count_number" => $count_number
            );
            array_push($process_list, $listFile);
        }
        echo json_encode($process_list);
    }else{
        echo('ERROR');
    }
}else {
    echo ('ERROR');
}
?>
