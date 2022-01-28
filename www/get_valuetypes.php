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

header("Content-type: text/plain");

include('config.php'); // Includes Login Script
include('external_service.php');

$link = mysqli_connect($host_valueunit, $username_valueunit, $password_valueunit) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_valueunit);
$query = "SELECT dt.value as 'value_type',
( SELECT value from processloader_db.dictionary_table dt2 join dictionary_relations dr on dr.child=dt2.id where dr.parent=dt.id order by dt2.id LIMIT 1) as value_unit
FROM processloader_db.dictionary_table dt where dt.type='value type' and status=1 order by value_type";
		
$result = mysqli_query($link, $query) or die(mysqli_error($link));
echo "SPARQL
CLEAR GRAPH <urn:test:value_types>;
SPARQL
PREFIX sosa:<http://www.w3.org/ns/sosa/>
PREFIX km4cvt:<http://www.disit.org/km4city/resource/value_type/>
INSERT {
GRAPH <urn:test:value_types> {
  ?s a ssn:Property.
  ?s km4c:value_unit ?u.
}}
WHERE {
  VALUES (?s ?u) {\n";
while ($row = mysqli_fetch_assoc($result)) {
$vt=str_replace(' ','_',$row['value_type']);
echo("(<http://www.disit.org/km4city/resource/value_type/". $vt."> \"".$row['value_unit']."\")\n");
}
echo "  }
};";
