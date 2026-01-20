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
    $od_id = isset($_REQUEST['od_id']) ? $_REQUEST['od_id'] : '';
    $precision = isset($_REQUEST['precision']) ? (int)$_REQUEST['precision'] : 0;
    $table_id = isset($_REQUEST['table_id']) ? $_REQUEST['table_id'] : '';
    $allowed_tables = array('od_data_mgrs', 'od_data');
    if (!in_array($table_id, $allowed_tables, true)) {
        echo('ERROR');
        return;
    }
    if ($table_id === 'od_data_mgrs'){
        $query_date = "SELECT MIN(from_date) as min_date, MAX(to_date) as max_date, COUNT(DISTINCT from_date) as count_number FROM od_data_mgrs WHERE od_id=:od_id AND precision=:precision GROUP BY od_id, precision";
        $stmt = $link->prepare($query_date);
        $stmt->bindValue(':od_id', $od_id, PDO::PARAM_STR);
        $stmt->bindValue(':precision', $precision, PDO::PARAM_INT);
    } else {
        $query_date = "SELECT MIN(from_date) as min_date, MAX(to_date) as max_date, COUNT(DISTINCT from_date) as count_number FROM od_data WHERE od_id=:od_id AND precision is null GROUP BY od_id, precision";
        $stmt = $link->prepare($query_date);
        $stmt->bindValue(':od_id', $od_id, PDO::PARAM_STR);
    }
    if ($stmt->execute()) {
        $process_list = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $listFile = array(
                "min_date" => $row['min_date'],
                "max_date" => $row['max_date'],
                "count_number" => $row['count_number']
            );
            array_push($process_list, $listFile);
        }
        echo json_encode($process_list);
    } else {
        echo('ERROR');
    }
}else {
    echo ('ERROR');
}
?>
