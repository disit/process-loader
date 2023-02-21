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
$query = "SELECT value as 'nature', label
FROM processloader_db.dictionary_table where type='nature' and delete_time is null order by nature";
		
$result = mysqli_query($link, $query) or die(mysqli_error($link));
echo "
SPARQL
PREFIX km4c:<http://www.disit.org/km4city/schema#>
PREFIX owl:<http://www.w3.org/2002/07/owl#>
PREFIX rdf-schema:<http://www.w3.org/2000/01/rdf-schema#>
DELETE {
GRAPH <urn:ontology> {
  ?s a owl:Class.
  ?s rdf-schema:label ?o.
  ?s rdf-schema:subClassOf km4c:Service.
}}
WHERE {
  ?s ?p ?o.
  VALUES (?s) {\n";
while ($row = mysqli_fetch_assoc($result)) {
$vt=str_replace(' ','_',$row['nature']);
echo("(<http://www.disit.org/km4city/schema#". $vt.">)\n");
}
echo "  }
};
";

$query = "SELECT value as subnature, label,
( SELECT dt2.value FROM processloader_db.dictionary_relations dr join processloader_db.dictionary_table dt2 on dt2.id=dr.parent where dr.child=dt.id LIMIT 1) as nature
FROM processloader_db.dictionary_table dt where dt.type='subnature' and delete_time is null order by subnature";
$subresult = mysqli_query($link, $query) or die(mysqli_error($link));	

while ($row = mysqli_fetch_assoc($subresult)) {
$vt=str_replace(' ','_',$row['subnature']);
echo "
SPARQL
PREFIX km4c:<http://www.disit.org/km4city/schema#>
PREFIX owl:<http://www.w3.org/2002/07/owl#>
PREFIX rdf-schema:<http://www.w3.org/2000/01/rdf-schema#>
DELETE {
GRAPH <urn:ontology> {
   ?s a owl:Class.
   ?s rdf-schema:label ?o.
   ?s rdf-schema:subClassOf ?subClass.
}}
WHERE {
   ?s ?p ?o.
   VALUES (?s ?subClass) {\n";
echo("(<http://www.disit.org/km4city/schema#". $vt."> <http://www.disit.org/km4city/schema#".$row['nature'].">)\n");
echo "  }
};";
}