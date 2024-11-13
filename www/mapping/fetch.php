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
   
include("../config.php");
if (($_SESSION['role'] !== '')&&($_SESSION['role']!= null)){
$table = "mappingtable";
$query = '';
$data = array();
$records_per_page = 10;
$start_from = 0;
$current_page_number = 0;
if (isset($_POST["rowCount"])) {
    $records_per_page = $_POST["rowCount"];
} else {
    $records_per_page = 10;
}
if (isset($_POST["current"])) {
    $current_page_number = $_POST["current"];
} else {
    $current_page_number = 1;
}
$start_from = ($current_page_number - 1) * $records_per_page;
$query .= "SELECT * FROM " . $dbname . "." . $table . " ";
if (!empty($_REQUEST["searchPhrase"])) {
    $fields = json_decode(urldecode($_REQUEST["fields"]));
    for ($i = 0; $i < count($fields); $i++) {
        //
        $i_field = filter_var($fields[$i] , FILTER_SANITIZE_STRING);

        if ($i == 0) {
            $query .= 'WHERE (`' . $i_field . '` LIKE "%' . $_REQUEST["searchPhrase"] . '%" ';
        } else {
            $query .= 'OR `' . $i_field . '` LIKE "%' . $_REQUEST["searchPhrase"] . '%" ';
        }
    }
    $query .= ') ';
}
$order_by = '';
if (isset($_POST["sort"]) && is_array($_POST["sort"])) {
    //
    $sort_test = mysqli_real_escape_string($connessione_al_server,$_POST["sort"]);
    $sort = filter_var($sort_test , FILTER_SANITIZE_STRING);
    //
    foreach ($sort as $key => $value) {
        $order_by .= " $key $value, ";
    }
} else {
    $query .= 'ORDER BY id DESC ';
}
if ($order_by != '') {
    $query .= ' ORDER BY ' . substr($order_by, 0, -2);
}

if ($records_per_page != -1) {
    $query .= " LIMIT " . $start_from . ", " . $records_per_page;
}
//echo $query;
//file_put_contents("prova.txt", $query);
$result = mysqli_query($connessione_al_server, $query);
//echo $result;
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

$query1 = "SELECT * FROM " . $dbname . "." . $table;
$result1 = mysqli_query($connessione_al_server, $query1);
$total_records = mysqli_num_rows($result1);

$output = array(
    'current' => intval($_POST["current"]),
    'rowCount' => 10,
    'total' => intval($total_records),
    'rows' => $data
);
//$filename2 = 'prova.txt';
//file_put_contents($filename2, $query, FILE_APPEND);
$connessione_al_server->close();
echo json_encode($output);
}
?>
