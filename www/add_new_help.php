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
$query = "SELECT COUNT(*) FROM processloader_db.Help_manager WHERE label='".$_POST['tool']."'";
	$query_result = mysqli_query($connessione_al_server,$query) or die ("Operation failed".mysqli_error());
	$count_list = array();
				if ($query_result->num_rows > 0) {
					while($row = mysqli_fetch_assoc($query_result)){
						array_push($count_list, $row);
					}
				}
	$total_rows = $count_list[0]["COUNT(*)"];
	//
	if ($total_rows > 0){
		//
				if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:help_manager.php?showFrame=false&error");
													}else{
													header ("location:help_manager.php?error");
													}	
												}else{
												header ("location:help_manager.php?error");
												}
		//
	}else{
////////////
$query_reg="INSERT INTO processloader_db.Help_manager (id,label,url,accesses,type)VALUES (NULL,'".$_POST['tool']."','".$_POST['url']."','0','".$_POST['type']."')";
$query_registrazione = mysqli_query($connessione_al_server,$query_reg) or die ("Operation failed".mysqli_error());

										if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:help_manager.php?showFrame=false");
													}else{
													header ("location:help_manager.php?showFrame=true");
													}	
												}else{
												header ("location:help_manager.php?showFrame=true");
												}
		//////////
	}
?>