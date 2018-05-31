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
$table = "mappingtable";
if (isset($_GET["source"])) {
    $output = array();
    $query = "SELECT destination FROM " . $dbname. "." . $table . " WHERE source = '" . $_GET["source"] . "'";
    $result = mysqli_query($connessione_al_server, $query);
    while ($row = mysqli_fetch_array($result)) {
            $output['destination'] = $row['destination'];
    }
    echo json_encode($output);
}
$connessione_al_server->close();
?>
