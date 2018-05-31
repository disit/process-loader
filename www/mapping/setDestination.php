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
if (isset($_GET["source"]) && isset($_GET["destination"])) {

        // field names
        $f = "";
        // field values
        $v = "";
        $source = urldecode($_GET["source"]);
		$destination = urldecode($_GET["destination"]);
        $f .= "`source`" . "," . "`destination`";
        $v .= "'" . $source . "'" . "," . "'" . $destination . "'";
           
   
        $query = "INSERT INTO " . $dbname . "." . $table . " (" . $f . ") VALUES (" . $v . ")";
        //file_put_contents("prova.txt", $query);
        if (mysqli_query($connessione_al_server, $query)) {
            echo 'Row Inserted';
        }
}
$connessione_al_server->close();
?>
