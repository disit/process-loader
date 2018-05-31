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

include 'config.php';
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
//$utente=$_SESSION['id'];
 $action = $_GET['action'];
/////VERIFICA UTENTE //////////

$utente_us=$_SESSION['username'];
//echo ($utente_us);
		if (isset($_SESSION['role'])){
				$privilege=$_SESSION['role'];
				}else{
				$privilege='Public';
				}
	
$queryRole = "SELECT functionalities.* FROM processloader_db.functionalities WHERE functionalities.".$privilege." = '1' AND functionalities.functionality = 'getAllResult';";
$resultRole = mysqli_query($link, $queryRole) or die(mysqli_error($link));

if ($resultRole->num_rows > 0) {
	$ruolo ='Qualified';
}else{
	$ruolo='Not_Qualified';
}

 ///////
 /*
 if ($action=="get_public_processes"){} elseif( $action=="select_facet"){ $query_facet=$_GET['query_facet'];
}
		 else {
		$utente_us=$_SESSION['username'];

		if (isset($_SESSION['role'])){
				$ruolo=$_SESSION['role'];
				 if ($ruolo=='RootAdmin'){
					 $ruolo ='ToolAdmin';
				 }
		} else {
		$ruolo='Manager';
		}
 }
*/
//$utente = $_SESSION['username'];
if (isset($_GET['action']) && !empty($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == "get_process") {
		
		if ($ruolo =='Qualified'){
			//$query = "SELECT processes.*, job_type.job_type_name, job_type.file_name FROM job_type,processes,uploaded_files WHERE processes.job_type=job_type.Id AND job_type.file_zip=uploaded_files.Id ORDER BY processes.Id DESC";
			$query = "SELECT processes.*, job_type.job_type_name, job_type.file_name, schedulers.type AS type_sched, uploaded_files.Username, schedulers.name AS name_sched FROM job_type,processes,uploaded_files, schedulers WHERE processes.job_type=job_type.Id AND job_type.file_zip=uploaded_files.Id AND schedulers.Ip_address = processes.id_disces ORDER BY processes.Id DESC ";
		} else {
			//$query = "SELECT processes.*, job_type.job_type_name, job_type.file_name FROM job_type,processes,uploaded_files WHERE processes.job_type=job_type.Id AND job_type.file_zip=uploaded_files.Id AND uploaded_files.Username='".$utente_us."' ORDER BY `Id` DESC";
			$query = "SELECT processes.*, job_type.job_type_name, job_type.file_name, schedulers.type AS type_sched, uploaded_files.Username, schedulers.name AS name_sched FROM job_type,processes,uploaded_files, schedulers WHERE processes.job_type=job_type.Id AND job_type.file_zip=uploaded_files.Id AND schedulers.Ip_address = processes.id_disces AND uploaded_files.Username='".$utente_us."' ORDER BY processes.Id DESC ";
			//$query = "SELECT processes.*, `uploaded_files`.`User` FROM uploaded_files INNER JOIN processes ON processes.job_type=uploaded_files.File_name WHERE `uploaded_files`.`User`='".$utente."' ORDER BY processes.Id DESC";
		}

        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $process_list = array();
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $process = array("process" => array(
                        "id" => $row['Id'],
                        "process_name" => $row['Process_name'],
                        "process_group" => $row['Process_group'],
                        "file" => $row['file_name'],
                        "creation_date"=> $row['Creation_date'],
						"type"=>$row['Process_Type'],
                        "status"=>$row['Status'],
						"jb_name"=>$row['job_type_name'],
						"ip"=>$row['id_disces'],
						"type_sched"=>$row['type_sched'],
						"username"=>$row['Username'],
						"name_sched"=>$row['name_sched']
                )
                );
                array_push($process_list, $process);
            }
        }
		echo json_encode($process_list);
        mysqli_close($link);
        
    } 
    elseif ($action =="get_files") {
		////View all resources////
		/*
				$queryRole0 = "SELECT functionalities.* FROM processloader_db.functionalities WHERE functionalities.".$privilege." = '1' AND functionalities.functionality = 'View all resources';";
							$resultRole0 = mysqli_query($link, $queryRole0) or die(mysqli_error($link));

							if ($resultRole0->num_rows > 0) {
								$ruolo ='Qualified';
							}else{
								$ruolo='Not_Qualified';
							}
		*/
		///////
		if ($ruolo =='Qualified'){
		//$query2 = "SELECT uploaded_files.*, users.Username FROM processloader_db.uploaded_files, processloader_db.users WHERE users.Id=uploaded_files.user ORDER BY 'Id' DESC";
		$query2 = "SELECT * FROM processloader_db.uploaded_files ORDER BY Id DESC";
		}else{
		//$query2 = "SELECT uploaded_files.*, users.Username FROM processloader_db.uploaded_files, processloader_db.users WHERE uploaded_files.Username='".$utente_us."' AND users.Username=uploaded_files.Username ORDER BY 'Username' DESC";
		$query2 = "SELECT * FROM processloader_db.uploaded_files WHERE uploaded_files.Username='".$utente_us."' ORDER BY Id DESC";
		//echo ($query2);
		//$query2 = "SELECT uploaded_files.* FROM processloader_db.uploaded_files WHERE uploaded_files.User='".$utente."' ORDER BY 'Id' DESC";
		}

        $result2 = mysqli_query($link, $query2) or die(mysqli_error($link));
        $files_list = array();
        if ($result2->num_rows > 0) {
            while ($row2 = mysqli_fetch_array($result2)) {
                $files= array(
                     //lista attibuti files zip
					 "file" => array(
						"id" => $row2['Id'],
						"name" => $row2['File_name'],
						"desc" => $row2['Description'],
						"date" => $row2['Creation_date'],	
						"type" => $row2['file_type'],
						"status" => $row2['status'],
						"utente" => $row2['Username'],
						"pub"=> $row2['Public'],
						"category"=> $row2['Category'],
						"resource"=> $row2['Resource_input'],
						"image"=> $row2['Img'],
						"format"=> $row2['Format'],
						"realtime"=> $row2['Realtime'],
						"periodic"=> $row2['Periodic'],
						"licence"=> $row2['License'],
						"protocol"=> $row2['Protocol'],
						"url"=> $row2['Url'],
						"help"=> $row2['Help'],
						"OS"=> $row2['OS'],
						"protocol"=> $row2['Protocol'],
						"method"=> $row2['Method'],
						"protocol"=> $row2['Protocol'],
						"opensource"=> $row2['OpenSource']





						
					 )
                );
                array_push($files_list, $files);
            }
        }
        mysqli_close($link);
        echo json_encode($files_list);
    } 
    elseif ($action=="get_activity"){
        //$query3 = "SELECT process_archive.* FROM processloader_db.process_archive, processloader_db.Processes,processloader_db.uploaded_files WHERE process_archive.Process_id=Processes.Id AND processes.job_type=uploaded_files.File_name AND uploaded_files.User='".$utente."' ";
		//$query3 ="SELECT * FROM processloader_db.process_archive";
		if ($ruolo =='Qualified'){
		$query3 = "SELECT process_archive.Id, process_archive.Activity_date, process_archive.Process_id, process_archive.Process_group,process_archive.Description_activity, processes.Process_name, job_type.job_type_name, job_type.file_name FROM processloader_db.process_archive, processloader_db.processes, processloader_db.job_type WHERE process_archive.Process_id=processes.Id AND processes.job_type = job_type.Id ORDER BY process_archive.Id DESC"; 
		}else{
		//echo ($query3);
		$query3 = "SELECT process_archive.Id, process_archive.Activity_date, process_archive.Process_id, process_archive.Process_group,process_archive.Description_activity, processes.Process_name, job_type.job_type_name, job_type.file_name, uploaded_files.Username FROM processloader_db.process_archive, processloader_db.processes, processloader_db.job_type, processloader_db.uploaded_files WHERE process_archive.Process_id=processes.Id AND processes.job_type = job_type.Id AND uploaded_files.Username='".$utente_us."' AND uploaded_files.Id=job_type.file_zip ORDER BY process_archive.Id DESC";
		}
		
        $result3 = mysqli_query($link, $query3) or die(mysqli_error($link));
        $activity_list = array();
        if ($result3->num_rows > 0) {
            while ($row3 = mysqli_fetch_array($result3)) {
                $activity = array(
                     //lista attributi attività
					 "activity" => array(
					       "id" => $row3['Id'],
						   "process_id" => $row3['Process_id'],
						   "name" => $row3['Process_name'],
						   "group" => $row3['Process_group'],
						   "desAct" => $row3['Description_activity'],
						   "date" => $row3['Activity_date'],
						   "job_type_name" => $row3['job_type_name'],
						   "file" => $row3['file_name']
						   //Id 	Activity_date 	Process_group 	Description_activity 	job_type_name 	Process_name 	file_name 
	   
					 )
                );
                array_push($activity_list, $activity);
				//echo ('Lista Archivio: '.$activity_list);
            }
        }
        mysqli_close($link);
        echo json_encode($activity_list);
    } 
    elseif ($action=="get_public_processes"){
		
        //$query3 = "SELECT process_archive.* FROM processloader_db.process_archive, processloader_db.Processes,processloader_db.uploaded_files WHERE process_archive.Process_id=Processes.Id AND processes.job_type=uploaded_files.File_name AND uploaded_files.User='".$utente."' ";
		//$query3 ="SELECT * FROM processloader_db.process_archive";
		
		//echo ($query3);
		$query3b = "SELECT uploaded_files.Id, uploaded_files.File_name, uploaded_files.Description, uploaded_files.Username, uploaded_files.Creation_date, uploaded_files.Download_number FROM processloader_db.uploaded_files WHERE uploaded_files.Public=1 ORDER BY Id DESC;";
		
		
        $result3b = mysqli_query($link, $query3b) or die(mysqli_error($link));
        $public_processes_list = array();
		
        if ($result3b->num_rows > 0) {
            while ($row3b = mysqli_fetch_array($result3b)) {
                $process = array(
                     //lista attributi attività
					 "process" => array(
					       "id" => $row3b['Id'],
						   "name" => $row3b['File_name'],
						   "description" => $row3b['Description'],
						   "user" => $row3b['Username'],
						   "date" => $row3b['Creation_date'],
						   "downloads" =>$row3b['Download_number']
						   

						   //Id 	Activity_date 	Process_group 	Description_activity 	job_type_name 	Process_name 	file_name 
	   
					 )
                );
                array_push($public_processes_list, $process);
				
				//echo ('Lista Archivio: '.$activity_list);
            }
        }
        mysqli_close($link);
        echo json_encode($public_processes_list);
    } 
	
	 elseif ($action=="select_facet"){
        //$query3 = "SELECT process_archive.* FROM processloader_db.process_archive, processloader_db.Processes,processloader_db.uploaded_files WHERE process_archive.Process_id=Processes.Id AND processes.job_type=uploaded_files.File_name AND uploaded_files.User='".$utente."' ";
		//$query3 ="SELECT * FROM processloader_db.process_archive";
		 //$query = $_GET['query'];

		//echo ($query3);
		//$query5 = $query;
		
$query10=$query_facet;
        $result10 = mysqli_query($link, $query10) or die(mysqli_error($link));
        $facet_list = array();
		
        if ($result10->num_rows > 0) {
            while ($row10 = mysqli_fetch_array($result10)) {
                $facets = array(
                     //lista attributi attività
					 "facets" => array(
					       "id" => $row10['Id']

						   

						   //Id 	Activity_date 	Process_group 	Description_activity 	job_type_name 	Process_name 	file_name 
	   
					 )
                );
                array_push($facet_list, $facets);
				
				//echo ('Lista Archivio: '.$activity_list);
            }
        }
        mysqli_close($link);
        echo json_encode($facet_list);
    } 
	
	
	
	
	elseif ($action=="get_types"){
		
		if ($ruolo =='Qualified'){
		$query4="SELECT `job_type`.* FROM processloader_db.`job_type`, processloader_db.`uploaded_files`  WHERE `job_type`.`file_zip` = `uploaded_files`.`Id` ORDER BY Id DESC";	
		}else{
		$query4 = "SELECT `job_type`.* FROM processloader_db.`job_type`, processloader_db.`uploaded_files`  WHERE `job_type`.`file_zip` = `uploaded_files`.`Id` AND `uploaded_files`.`Username` = '".$utente_us."' ORDER BY Id DESC";
		}
		//echo ($query4);
		$result4 = mysqli_query($link, $query4) or die(mysqli_error($link));
		//print($result4);
		$jobtype_list = array();
		if ($result4->num_rows > 0) {
            while ($row4 = mysqli_fetch_array($result4)) {
                $jobtype = array(
							"jobtype" => array(
								 "id" => $row4['Id'],
                                 "job_type_name" => $row4['job_type_name'],
                                 "job_type_group" => $row4['job_type_group'],
                                 "file" => $row4['file_zip'],
                                 "creation_date"=> $row4['creation_date'],
					             "type"=>$row4['type'],
								 //dati invisibili
								 "file_name"=>$row4['file_name'],
								 "start"=>$row4['start_time'],
								 "end"=>$row4['end_time'],
								 "interval"=>$row4['Time_interval'],
								 "job_type_description"=>$row4['job_type_description'],
								 "url"=>$row4['url'],
								 "path"=>$row4['path'],
								 "mail"=>$row4['e-mail'],
								 "store"=>$row4['storeDurably'],
								 "conc"=>$row4['non_concurrent'],
								 "recovery"=>$row4['requestRecovery'],
								 "trig_name"=>$row4['Trigger_name'],
								 "trig_group"=>$row4['Trigger_group'],
								 "trig_desc"=>$row4['Trigger_description'],
								 "priority"=>$row4['Priority'],
								 "repeat"=>$row4['RepeatCount'],
								 "process_param"=>$row4['ProcessParameter'],
								 "misfire"=>$row4['MisfireInstruction'],
								 "time_out"=>$row4['Time_out'],
								 "datamap"=>$row4['DataMap'],
								 "next_job"=>$row4['NextJob'],
								 "job_cons"=>$row4['JobConstraint'],
								 "file_position"=>$row4['file_position']
								)
							);
                array_push($jobtype_list, $jobtype);
            }
        }
		mysqli_close($link);
        echo json_encode($jobtype_list);
	}
	elseif ($action == "get_process_j") {
		$job_type=$_GET['job'];
		//PARAMETRO DA PASSARE: id del type Job
			$query5 = "SELECT * FROM processloader_db.processes WHERE job_type=".$job_type." ORDER BY processes.Id DESC";

        $result5 = mysqli_query($link, $query5) or die(mysqli_error($link));
        $process_list = array();
        if ($result5->num_rows > 0) {
            while ($row5 = mysqli_fetch_array($result5)) {
                $process = array("process" => array(
                        "id" => $row5['Id'],
                        "process_name" => $row5['Process_name'],
                        "process_group" => $row5['Process_group'],
                        "file" => $row5['job_type'],
                        "creation_date"=> $row5['Creation_date'],
						"type"=>$row5['Process_Type'],
                        "status"=>$row5['Status']
                )
                );
                array_push($process_list, $process);
            }
        }
		echo json_encode($process_list);
        mysqli_close($link);
        
    } elseif ($action == "get_process_jt") {
		$file=$_GET['jobt'];
		$id_file=$_GET['jobtId'];
		//PARAMETRO DA PASSARE: id del type Job
			$query6 = "SELECT `job_type`.* FROM processloader_db.`job_type` WHERE `job_type`.`file_zip` = '".$id_file."' ORDER BY Id DESC;";

        $result6 = mysqli_query($link, $query6) or die(mysqli_error($link));
        $process_list = array();
        if ($result6->num_rows > 0) {
            while ($row6 = mysqli_fetch_array($result6)) {
                $process = array(
							"jobtype" => array(
								 "id" => $row6['Id'],
                                 "job_type_name" => $row6['job_type_name'],
                                 "job_type_group" => $row6['job_type_group'],
                                 "type" => $row6['type']
								)
							);
                array_push($process_list, $process);
            }
        }
		echo json_encode($process_list);
        mysqli_close($link);
        
    } elseif ($action == "get_schedulers") {
		$query7 = "SELECT `schedulers`.* FROM processloader_db.`schedulers` ORDER BY Id DESC;";
		$result7 = mysqli_query($link, $query7) or die(mysqli_error($link));
		$scheduler_list = array();
		if ($result7->num_rows > 0) {
            while ($row7 = mysqli_fetch_array($result7)) {
                $sched= array(
							"schedulers" => array(
								"name" => $row7['name'],
								 "id" => $row7['Id'],
                                 "address" => $row7['Ip_address'],
                                 "repository" => $row7['repository'],
                                 "type" => $row7['type'],
								 "description" => $row7['Description']
								)
							);
                array_push($scheduler_list, $sched);
            }
        }
		echo json_encode($scheduler_list);
        mysqli_close($link);
	} elseif ($action == "get_schedulers_test") {
		$query7 = "SELECT `schedulers`.* FROM processloader_db.`schedulers` WHERE `type`='test' ORDER BY Id DESC;";
		$result7 = mysqli_query($link, $query7) or die(mysqli_error($link));
		$scheduler_list = array();
		if ($result7->num_rows > 0) {
            while ($row7 = mysqli_fetch_array($result7)) {
                $sched= array(
							"schedulers" => array(
								 "name" => $row7['name'],
								 "id" => $row7['Id'],
                                 "address" => $row7['Ip_address'],
                                 "repository" => $row7['repository'],
                                 "type" => $row7['type'],
								 "description" => $row7['Description'],
								)
							);
                array_push($scheduler_list, $sched);
            }
        }
		echo json_encode($scheduler_list);
        mysqli_close($link);
	} elseif($action == "test_process"){
		$query8 = "SELECT `processes`.* FROM processloader_db.`schedulers`,processloader_db.`processes` WHERE `processes`.`id_disces`=`schedulers`.`Ip_address` AND `schedulers`.`type`='test' AND `processes`.`Status`='COMPLETE' ORDER BY Id DESC;";
		//$query8 = "SELECT `processes`.`Id`,`processes`.`Process_name`, `processes`.`Process_group`, `processes`.`Creation_date`, `processes`.`Process_Type`, `processes`.`Status`, `processes`.`id_disces` FROM processloader_db.`schedulers`, processloader_db.`processes` WHERE `processes`.`id_disces`=`schedulers`.`Ip_address` AND `schedulers`.`type`='test'";
		$result8 = mysqli_query($link, $query8) or die(mysqli_error($link));
		$process_list_test = array();
        if ($result8->num_rows > 0) {
            while ($row8 = mysqli_fetch_array($result8)) {
                $process_test = array(
						"test" => array(
							"id" => $row8['Id'],
							"process_name" => $row8['Process_name'],
							"process_group" => $row8['Process_group'],
							"creation_date"=> $row8['Creation_date'],
							"type"=>$row8['Process_Type'],
							"status"=>$row8['Status'],
							"ip"=>$row8['id_disces']
							)
						);
					array_push($process_list_test, $process_test);
					}
				}
			echo json_encode($process_list_test);
			mysqli_close($link);
		//
	} elseif($action == "get_production_sched"){
		$query9 = "SELECT `schedulers`.`*` FROM processloader_db.`schedulers` WHERE `schedulers`.`type`='production' ORDER BY Id DESC;";
		$result9 = mysqli_query($link, $query9) or die(mysqli_error($link));
		$list_production = array();
        if ($result9->num_rows > 0) {
            while ($row9 = mysqli_fetch_array($result9)) {
                $production_sched = array(
						"production" => array(
							"name" => $row9['name'],
							"id" => $row9['Id'],
							"address" => $row9['Ip_address'],
							"repository" => $row9['repository'],
							"type" => $row9['type'],
							"description" => $row9['Description']
							)
						);
					array_push($list_production, $production_sched);
					}
				}
			echo json_encode($list_production);
			mysqli_close($link);
		//
	}
    else{
        echo 'invalid action ' . $action;
    }
}
