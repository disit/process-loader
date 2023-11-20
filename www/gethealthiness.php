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

   include('config.php');
/*
$configs = include('config_healthiness.php');

//$host = $configs['host'];
$host = (getenv('DB_HOST') !== false) ? getenv('DB_HOST') : $configs['host'];
//$user = $configs['user'];
$user = (getenv('DB_USER') !== false) ? getenv('DB_USER') : $configs['user'];
//$password = $configs['password'];
$password = (getenv('DB_PW') !== false) ? getenv('DB_PW') : $configs['password'];
//$database = $configs['database'];
$database = (getenv('DB_DATABASE') !== false) ? getenv('DB_DATABASE') : $configs['database'];
//$table =$configs['table'];
$table = (getenv('DB_TABLE') !== false) ? getenv('DB_TABLE') : $configs['table'];
//$port = $configs['port'];
$port = (getenv('DB_PORT') !== false) ? getenv('DB_PORT') : $configs['port'];
// Connect to the database
$link = mysqli_connect($host, $user, $password ,$database,$port);

if (!$link) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}
*/
if(isset($_SESSION['username'])){
    $utente_us = $_SESSION['username'];

}
else{
    $utente_us = '';
}

if (isset($_GET['action']) and $_GET['action'] == 'auth') {
#$auth_db= 'AllowedUsers';
    $auth_table = 'healthiness_users';
    $auth_query = "SELECT * FROM $auth_table WHERE username = '$utente_us' ";
    $auth_result = mysqli_query($connessione_al_server, $auth_query) or die(mysqli_error($link));
    if ($auth_result->num_rows > 0) {
        $auth = 'Allowed';
    } else {
        $auth = 'Not_Allowed';
    }

    echo $auth;
    exit();
}

$columnNames = array('ServiceURI', 'Organization', 'Broker','DeviceName','DeviceModel','Nature','Subnature',
    'VariableName','VariableValue','ExpectedDate','LastMeasureDate','FailuresNumber','LastNotHealthy','Delta','MaxDelta','Percentage');

// Find the use case and create the query

if (isset($_GET['search']) and isset($_GET['filter']) and isset($_GET['order'])) {
        $offset = $_GET['offset'];
        $limit = $_GET['limit'];
        $device = $_GET['device'];

        if($device == "false"){
            $select_query="*";
            $select_distinct ="*";
            $variableOrd=", VariableName ASC";
        }
        else{
            $select_query="DISTINCT ServiceURI";
            $select_distinct = " DISTINCT ServiceURI, Organization, Broker, DeviceName, DeviceModel,Nature,Subnature,ExpectedDate,LastMeasureDate,FailuresNumber,LastNotHealthy,Delta,MaxDelta,Percentage";
            $variableOrd=" ";
        }
        if ($_GET['search'] == "true" and $_GET['filter'] == "true" and $_GET['order'] == "true") {
            // Searching params
            $field = $_GET['column'];
            $value = $_GET['value'];

            // Filtering params
            $columnFilter = $_GET["filtercol"];
            if ($_GET["type"] == 'under') {
                $type = '<';
            } else {
                $type = '>';
            }
            $threshold = $_GET["threshold"];

            // Ordering params
            $columnName = $columnNames[$_GET['clickedColumn']];
            $colOrder = $_GET['direction'];

            $query = "SELECT $select_distinct, (SELECT COUNT($select_query) FROM $table_healthiness WHERE $columnFilter $type $threshold AND $field LIKE '%$value%' ) " .
                " as count, (SELECT COUNT(DISTINCT ServiceURI) FROM $table_healthiness where FailuresNumber >= 1 AND $columnFilter $type $threshold AND $field LIKE '%$value%' ) as nothealthyCount FROM $table_healthiness WHERE $columnFilter $type $threshold AND $field LIKE '%$value%' ORDER BY " .
                " $columnName $colOrder, FailuresNumber DESC, ServiceURI ASC $variableOrd LIMIT $offset, $limit ";

        } elseif ($_GET['search'] == "true" and $_GET['filter'] == "true") {
            // Searching params
            $field = $_GET['column'];
            $value = $_GET['value'];

            // Filtering params
            $columnFilter = $_GET["filtercol"];
            if ($_GET["type"] == 'under') {
                $type = '<';
            } else {
                $type = '>';
            }
            $threshold = $_GET["threshold"];

            $query = "SELECT $select_distinct, (SELECT COUNT($select_query) FROM $table_healthiness WHERE $columnFilter $type $threshold AND $field LIKE '%$value%' ) " .
                " as count, (SELECT COUNT(DISTINCT ServiceURI) FROM $table_healthiness where FailuresNumber >= 1 AND $columnFilter $type $threshold AND $field LIKE '%$value%') as nothealthyCount FROM $table_healthiness WHERE $columnFilter $type $threshold AND $field LIKE '%$value%' ORDER BY " .
                " FailuresNumber DESC, ServiceURI ASC $variableOrd  LIMIT $offset, $limit ";

        } elseif ($_GET['order'] == "true" and $_GET['filter'] == "true") {
            // Ordering params
            $columnName = $columnNames[$_GET['clickedColumn']];
            $colOrder = $_GET['direction'];

            // Filtering params
            $columnFilter = $_GET["filtercol"];
            if ($_GET["type"] == 'under') {
                $type = '<';
            } else {
                $type = '>';
            }
            $threshold = $_GET["threshold"];

            $query = "SELECT $select_distinct, (SELECT COUNT($select_query) FROM $table_healthiness WHERE $columnFilter $type $threshold ) " .
                " as count, (SELECT COUNT(DISTINCT ServiceURI) FROM $table_healthiness where FailuresNumber >= 1 AND $columnFilter $type $threshold) as nothealthyCount FROM $table_healthiness WHERE $columnFilter $type $threshold  ORDER BY " .
                " $columnName $colOrder, FailuresNumber DESC, ServiceURI ASC $variableOrd  LIMIT $offset, $limit ";
        } elseif ($_GET['order'] == "true" and $_GET['search'] == "true") {
            // Ordering params
            $columnName = $columnNames[$_GET['clickedColumn']];
            $colOrder = $_GET['direction'];

            // Searching params
            $field = $_GET['column'];
            $value = $_GET['value'];

            $query = "SELECT $select_distinct, (SELECT COUNT($select_query) FROM $table_healthiness WHERE $field LIKE '%$value%' ) " .
                " as count, (SELECT COUNT(DISTINCT ServiceURI) FROM $table_healthiness where FailuresNumber >= 1 AND $field LIKE '%$value%') as nothealthyCount FROM $table_healthiness WHERE  $field LIKE '%$value%'  ORDER BY " .
                " $columnName $colOrder, FailuresNumber DESC, ServiceURI ASC $variableOrd  LIMIT $offset, $limit ";

        } elseif ($_GET['search'] == "true") {
            // Searching params
            $field = $_GET['column'];
            $value = $_GET['value'];

            $query = "SELECT $select_distinct, (SELECT COUNT($select_query) FROM $table_healthiness WHERE $field LIKE '%$value%' ) " .
                " as count, (SELECT COUNT(DISTINCT ServiceURI) FROM $table_healthiness where FailuresNumber >= 1 AND $field LIKE '%$value%' ) as nothealthyCount FROM $table_healthiness WHERE  $field LIKE '%$value%'  ORDER BY " .
                " FailuresNumber DESC, ServiceURI ASC $variableOrd  LIMIT $offset, $limit ";

        } elseif ($_GET['order'] == "true") {
            // Ordering params
            $columnName = $columnNames[$_GET['clickedColumn']];
            $colOrder = $_GET['direction'];

            $query = "SELECT $select_distinct, (SELECT COUNT($select_query) FROM $table_healthiness  ) " .
                " as count, (SELECT COUNT(DISTINCT ServiceURI) FROM $table_healthiness where FailuresNumber >= 1 ) as nothealthyCount FROM $table_healthiness  ORDER BY " .
                " $columnName $colOrder, FailuresNumber DESC, ServiceURI ASC $variableOrd  LIMIT $offset, $limit ";

        } elseif ($_GET['filter'] == "true") {
            // Filtering params
            $columnFilter = $_GET["filtercol"];
            if ($_GET["type"] == 'under') {
                $type = '<';
            } else {
                $type = '>';
            }
            $threshold = $_GET["threshold"];

            $query = "SELECT $select_distinct, (SELECT COUNT($select_query) FROM $table_healthiness WHERE $columnFilter $type $threshold ) " .
                " as count, (SELECT COUNT(DISTINCT ServiceURI) FROM $table_healthiness where FailuresNumber >= 1 AND $columnFilter $type $threshold  ) as nothealthyCount FROM $table_healthiness WHERE $columnFilter $type $threshold  ORDER BY " .
                " FailuresNumber DESC, ServiceURI ASC $variableOrd  LIMIT $offset, $limit ";
        } else {
            $query = "SELECT $select_distinct, (SELECT COUNT($select_query) FROM $table_healthiness ) as count, (SELECT COUNT(DISTINCT ServiceURI) FROM $table_healthiness where FailuresNumber >= 1 ) as nothealthyCount  FROM $table_healthiness " .
                "  ORDER BY FailuresNumber DESC, ServiceURI $variableOrd  LIMIT  $offset ,$limit ;";

        }


// Retrieve the results of the query
$results = mysqli_query($connessione_al_server, $query) or die(mysqli_error($connessione_al_server));
$record_list = array();

// Create an array of the results
$record_list = array();

if ($results->num_rows > 0) {

    while ($row = mysqli_fetch_array($results)) {
        $record = array(
            "record" => array(
                "uri"=>$row['ServiceURI'],
                "organization" => $row['Organization'],
                "broker"=>$row['Broker'],
                "name"=>$row['DeviceName'],
                "model"=>$row['DeviceModel'],
                "nature"=>$row['Nature'],
                "subnature"=>$row['Subnature'],
                //"variable"=>$row['VariableName'],
                //"value"=>$row['VariableValue'],
                "exp_date"=>$row['ExpectedDate'],
                "last_date"=>$row['LastMeasureDate'],
                "failures"=>$row['FailuresNumber'],
                "timestamp"=>$row['LastNotHealthy'],
                "delta"=>$row['Delta'],
                "max_delta"=>$row['MaxDelta'],
                "percentage"=>$row['Percentage'],
                "count"=>$row['count'],
                "nothealthy"=>$row['nothealthyCount']
            )
        );
        if ($device=="false") {
            $record['record']['variable'] = $row['VariableName'];
            $record['record']['value'] = $row['VariableValue'];
        }
        array_push($record_list, $record);

    }
    // Encode the results as json
    $json_data = json_encode($record_list);
}
else {
    echo "Nessun record trovato nel database.";
    }

mysqli_free_result($results);

echo $json_data;
}
// Close the connection to the database
mysqli_close($connessione_al_server);

?>

