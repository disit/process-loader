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


    $idx = intval($_POST['id']);
	$valuex = intval($_POST['value']);
	echo "<script type='text/javascript'>alert('$downx');</script>";

    						 $query="UPDATE uploaded_files SET Total_stars = '$valuex'+Total_stars, Votes=Votes+1 WHERE Id ='$idx'" ;
	 echo($query);
			$query_job_type = mysqli_query($connessione_al_server,$query) ;
   			   $query2="UPDATE uploaded_files SET Average_stars = Total_stars/Votes WHERE Id ='$idx'" ;
			$query_job_type = mysqli_query($connessione_al_server,$query2) ;


			$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
			url_get($url);  
			
    die;
//header("location:file_archive.php");
?>