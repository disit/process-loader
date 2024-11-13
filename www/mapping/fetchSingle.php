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
if (($_SESSION['role'] !== '')&&($_SESSION['role']!= null)){
        if (isset($_POST["id"])) {
            $output = array();
            //
            $id_test = mysqli_real_escape_string($connessione_al_server,$_POST["id"]);
            $id = json_decode(urldecode($id_test));
            //
            $query = "SELECT * FROM " . $dbname . "." . $table . " WHERE id = '" . $id. "'";
            $result = mysqli_query($connessione_al_server, $query);
            while ($row = mysqli_fetch_array($result)) {
                
                $fields = json_decode(urldecode($_REQUEST["fields"]));
                //error_log(json_last_error());
                //file_put_contents("prova.txt", urldecode($_REQUEST["fields"]));
                foreach ($fields as $field) {
                    $i_field = filter_var($row[$field] , FILTER_SANITIZE_STRING);
                    $output[$field] = $i_field;
                }
            }
            echo json_encode($output);
        }
}
$connessione_al_server->close();
?>
