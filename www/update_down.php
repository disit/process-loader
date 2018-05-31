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
include('curl.php');
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);

    $idx = intval($_POST['id']);
	$downx = intval($_POST['down'])+1;
	
/*  	$query_down = "SELECT Download_number FROM uploaded_files WHERE Id ='$idx'";
	$result = mysqli_query($link, $query_down) or die(mysqli_error($link));
	$downloads = 17; */

    		 $query="UPDATE uploaded_files SET Download_number = '$downx' WHERE Id ='$idx'" ;
			$query_job_type = mysqli_query($connessione_al_server,$query) ;
   						 

			$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
			url_get($url); 
			usleep(200000);

			
   die;
//header("location:file_archive.php");
?>