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
    $auth_query = "SELECT 1 FROM healthiness_users WHERE username = ? LIMIT 1";
    $auth_stmt = mysqli_prepare($connessione_al_server, $auth_query);
    if (!$auth_stmt) {
        die(mysqli_error($connessione_al_server));
    }
    mysqli_stmt_bind_param($auth_stmt, 's', $utente_us);
    mysqli_stmt_execute($auth_stmt);
    $auth_result = mysqli_stmt_get_result($auth_stmt);
    if ($auth_result->num_rows > 0) {
        $auth = 'Allowed';
    } else {
        $auth = 'Not_Allowed';
    }

    mysqli_stmt_close($auth_stmt);
    echo $auth;
    exit();
}

$columnNames = array('ServiceURI', 'Organization', 'Broker','DeviceName','DeviceModel','Nature','Subnature',
    'VariableName','VariableValue','ExpectedDate','LastMeasureDate','FailuresNumber','LastNotHealthy','Delta','MaxDelta','Percentage');

// The table name is server-side configuration: it must never come from a request.
$table_healthiness = getenv('DB_TABLE');
$table_healthiness = ($table_healthiness !== false && $table_healthiness !== '') ? $table_healthiness : 'healthiness';
if (!preg_match('/^[A-Za-z0-9_]+$/', $table_healthiness)) {
    die('Nome tabella non valido.');
}

// Find the use case and create the query. Request values are validated here and
// bound below; column names and sort directions are selected from whitelists.

if (isset($_GET['search']) and isset($_GET['filter']) and isset($_GET['order'])) {
        $search = $_GET['search'] === 'true';
        $filter = $_GET['filter'] === 'true';
        $order = $_GET['order'] === 'true';
        $offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT);
        $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);
        $device = isset($_GET['device']) && $_GET['device'] === 'true' ? 'true' : 'false';

        if ($offset === false || $offset < 0 || $limit === false || $limit < 1 || $limit > 1000) {
            http_response_code(400);
            exit('Parametri di paginazione non validi.');
        }

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
        $allowedColumns = array_flip($columnNames);
        $conditions = array();
        $params = array();
        $types = '';

        if ($search) {
            $fieldParam = $_GET['column'] ?? null;
            $field = isset($allowedColumns[$fieldParam]) ? $fieldParam : null;
            if ($field === null || !isset($_GET['value'])) {
                http_response_code(400); exit('Parametro di ricerca non valido.');
            }
            $conditions[] = "$field LIKE ?";
            $params[] = '%' . $_GET['value'] . '%';
            $types .= 's';
        }
        if ($filter) {
            $filterColumnParam = $_GET['filtercol'] ?? null;
            $columnFilter = isset($allowedColumns[$filterColumnParam]) ? $filterColumnParam : null;
            $threshold = filter_var($_GET['threshold'] ?? null, FILTER_VALIDATE_FLOAT);
            if ($columnFilter === null || $threshold === false || !isset($_GET['type']) || !in_array($_GET['type'], array('under', 'over'), true)) {
                http_response_code(400); exit('Parametro di filtro non valido.');
            }
            $conditions[] = "$columnFilter " . ($_GET['type'] === 'under' ? '<' : '>') . ' ?';
            $params[] = $threshold;
            $types .= 'd';
        }

        $where = count($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';
        $orderSql = 'FailuresNumber DESC, ServiceURI ASC';
        if ($order) {
            $clickedColumn = filter_input(INPUT_GET, 'clickedColumn', FILTER_VALIDATE_INT);
            $columnName = ($clickedColumn !== false && $clickedColumn !== null && isset($columnNames[$clickedColumn])) ? $columnNames[$clickedColumn] : null;
            $direction = ($_GET['direction'] ?? '') === 'DESC' ? 'DESC' : (($_GET['direction'] ?? '') === 'ASC' ? 'ASC' : null);
            if ($columnName === null || $direction === null) {
                http_response_code(400); exit('Parametro di ordinamento non valido.');
            }
            $orderSql = "$columnName $direction, $orderSql";
        }

        $query = "SELECT $select_distinct,
            (SELECT COUNT($select_query) FROM $table_healthiness$where) AS count,
            (SELECT COUNT(DISTINCT ServiceURI) FROM $table_healthiness" .
            (count($conditions) ? ' WHERE FailuresNumber >= 1 AND ' . implode(' AND ', $conditions) : ' WHERE FailuresNumber >= 1') .
            ") AS nothealthyCount
            FROM $table_healthiness$where
            ORDER BY $orderSql" . ($variableOrd === ', VariableName ASC' ? ', VariableName ASC' : '') .
            " LIMIT ?, ?";

        // Each subquery and the main query has its own parameter placeholders.
        $bindValues = array_merge($params, $params, $params, array($offset, $limit));
        $bindTypes = $types . $types . $types . 'ii';
        $stmt = mysqli_prepare($connessione_al_server, $query);
        if (!$stmt) {
            die(mysqli_error($connessione_al_server));
        }
        $bindArgs = array($stmt, $bindTypes);
        foreach ($bindValues as $key => $value) {
            $bindValues[$key] = $value;
            $bindArgs[] = &$bindValues[$key];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bindArgs);
        mysqli_stmt_execute($stmt);
        $results = mysqli_stmt_get_result($stmt);


// Retrieve the results of the query
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

