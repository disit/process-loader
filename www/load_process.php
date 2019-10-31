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
   
include'config.php';
if (isset ($_SESSION['username'])&& isset($_SESSION['role'])){  
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
$utente_us = mysqli_real_escape_string($link,$_SESSION['username']);
$process_id = mysqli_real_escape_string($link,$_REQUEST['process_id']);
$process_name = mysqli_real_escape_string($link,$_REQUEST['process_name']);
$current_scheduler = mysqli_real_escape_string($link,$_REQUEST['current_scheduler']);
//$prod_sched = $_REQUEST['prod_sched'];
$name_prod=mysqli_real_escape_string($link,$_REQUEST['name_sched']);
$prod_sched="";
$prod_path="";
$prod_home="";
$prod_integration="";
$prod_repository="";

//QUERY TROVA IP PRDOUZIONE 
$queryIp = "SELECT * FROM `schedulers` WHERE `name` = '".$name_prod."'";
$resultIp = mysqli_query($link, $queryIp) or die(mysqli_error($link));
$address_list = array();
if ($resultIp->num_rows > 0) {
	while ($rowIp = mysqli_fetch_array($resultIp)) {
		$address = array(
		"repository" => $rowIp['repository'],
		"prod_sched" => $rowIp['Ip_address'],
		"data_integration_path" =>$rowIp['data_integration_path'],
		"process_path"=> $rowIp['process_path'],
		"DDI_HOME"=> $rowIp['DDI_HOME'],
		);
		array_push($address_list, $address);
	}
	json_encode($address_list);
	$prod_sched = $address_list[0]["prod_sched"];
	$prod_path = $address_list[0]["process_path"];
	$prod_home = $address_list[0]["DDI_HOME"];
	$prod_integration = $address_list[0]["data_integration_path"];
	$prod_repository = $address_list[0]["repository"];
}
//
$query = "SELECT processes.* FROM processes WHERE processes.Id='".$process_id."' AND processes.id_disces='".$current_scheduler."'";

$result = mysqli_query($link, $query) or die(mysqli_error($link));
        $process_list = array();
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $process = array(
                        "id" => $row['Id'],
						"process_name" => $row['Process_name'],
                        "process_group" => $row['Process_group'],
						"job_type" => $row['job_type'],
                        "creation_date"=> $row['Creation_date'],
						"type"=>$row['Process_Type'],
                        "status"=>$row['Status'],
						"ip"=>$row['id_disces'],
						"time_interval" =>$row['Time_interval'],
						"start" => $row['Start_time'],
						"End" => $row['End_time'],
						"non_concurrent" => $row['non_concurrent'],
						"StoreDurably" => $row['StoreDurably'],
						"RequestRecovery" => $row['RequestRecovery'],
						"Process_description"=>$row['Process_description'],
						"url"=>$row['url'],
						"process_path"=>$row['process_path'],
						"MisfireInstruction"=>$row['MisfireInstruction'],
						"Email"=>$row['Email'],
						"trigger_name"=>$row['trigger_name'],
						"trigger_group"=>$row['trigger_group'],
						"trigger_description"=>$row['trigger_description'],
						"priority"=>$row['priority'],
						"repeat_count"=>$row['repeat_count'],
						"time_out"=>$row['time_out'],
						"dataMap"=>$row['dataMap'],
						"nextJob"=>$row['nextJob'],
						"JobConstraint"=>$row['JobConstraint'],
						"ProcessParameter"=>$row['ProcessParameter'],
						"file_position"=>$row['file_position']
                );
                array_push($process_list, $process);
            }
			json_encode($process_list);
			
			//POSIZIONE FILE
			if (isset($process_list[0]['file_position']) && ($process_list[0]['file_position']!="")){
			$file_position = $process_list[0]['file_position'];
			}else{
				$file_position = "";
			}
			//
			
			if ($process_list[0]['start']==""){
			$startAt = 000;
			}else{
			$startAt = date_create($process_list[0]['start'])->getTimestamp();
			$startAt = $startAt.'000';
			}
			//echo "<b>Start At:</b>".$startAt."<br>";

			if ($process_list[0]['End']==""){
			$endAt = 000;
			}else{
			$endAt = date_create($process_list[0]['End'])->getTimestamp();
			$endAt = $endAt.'000';
			}
			//echo "<b>End At:</b>".$endAt."<br>";
			//
			if ($process_list[0]['StoreDurably']==1){
				$store_p1='true';
			}else{
				$store_p1='false';
			}
			if($process_list[0]['RequestRecovery']==1){
				$recovery_p1='true';
			}else{
				$recovery_p1='false';
			}
			//echo "<b>recovery_p1:</b>".$recovery_p1."<br>";
			
			if($process_list[0]['non_concurrent']==1){
				$conc_p1='true';
			}else{
				$conc_p1='false';
			}
			//echo "<b>conc_p1:</b>".$conc_p1."<br>";
			
			if ($process_list[0]['nextJob'] ==""){
				//$nextJob = '{}';
				$nextJob = "";
			}else{
				//[{}]
				$nextJob = '['.$process_list[0]['nextJob'].']';
				$nextJob=str_replace ('"','\"',$nextJob);
				$nextJob=str_replace ('/','\\\/',$nextJob);
			}
			//echo "<b>nextJob:</b>".$nextJob."<br>";
			
			//NOMI
			if($process_list[0]['process_name']==""){
				$name_process ='';
			}else{
				$name_process = $process_list[0]['process_name'];
			}
			//echo "<b>process_name:</b>".$process_name."<br>";
			//GRUPPO
			if ($process_list[0]['process_group']==""){
				$process_group ='';
			}else{
				$process_group = $process_list[0]['process_group'];
			}
			//echo "<b>process_group:</b>".$process_group."<br>";
			
			//JOB CLASS
			if ($process_list[0]['type']==""){
				$process_type ='';
			}else{
				$process_type = $process_list[0]['type'];
							 if ($process_type == "REST"){
										$process_type ='RESTJob';
										}else{
								$process_type ='ProcessExecutorJob';
				}
   
			}
			//echo "<b>process_type:</b>	".$process_type."<br>";
			
			//Description_activity
			if ($process_list[0]['Process_description']==""){
				$process_description = '';
			}else{
				$process_description = $process_list[0]['Process_description'];
			}
			//echo "<b>process_description:</b>	".$process_description."<br>";
			
			//TRIGGER NAME trigger_name
			if ($process_list[0]['trigger_name']==''){
				$trigger_name ='';
			}else{
				$trigger_name = $process_list[0]['trigger_name'];
			}
			//echo "<b>trigger_name:</b>	".$trigger_name."<br>";
			
			//TRIGGER GROUP
			if ($process_list[0]['trigger_group']==''){
				$trigger_group ='';
			}else{
				$trigger_group = $process_list[0]['trigger_group'];
			}
			//echo "<b>trigger_group:</b>	".$trigger_group."<br>";
			
			//PRIORITY
			if ($process_list[0]['priority']==''){
				$priority ='';
			}else{
				$priority = $process_list[0]['priority'];
			}
			//echo "<b>priority:</b>		".$priority."<br>";
			
			//TRIGGER Description
			if ($process_list[0]['trigger_description']==''){
				$trigger_description ='';
			}else{
				$trigger_description = $process_list[0]['trigger_description'];
			}
			//echo "<b>trigger_description</b>:	".$trigger_description."<br>";
			
			//MisfireInstruction
			/*
			if ($process_list[0]['MisfireInstruction']==''){
				$MisfireInstruction='DEFAULT';
			}else{
				$MisfireInstruction=$process_list[0]['MisfireInstruction'];

			}
			*/
			$misfire_p = $process_list[0]['MisfireInstruction'];
			$resultMisfire = "";
			switch ($misfire_p) {
			case "FIRE_NOW":
				$resultMisfire = "withMisfireHandlingInstructionFireNow";
				break;
			case "IGNORE_MISFIRE_POLICY":
				$resultMisfire = "withMisfireHandlingInstructionIgnoreMisfires";
				break;
			case "RESCHEDULE_NEXT_WITH_EXISTING_COUNT":
				$resultMisfire = "withMisfireHandlingInstructionNextWithExistingCount";
				break;
			case "RESCHEDULE_NEXT_WITH_REMAINING_COUNT":
				$resultMisfire = "withMisfireHandlingInstructionNextWithRemainingCount";
				break;
			case "RESCHEDULE_NOW_WITH_EXISTING_REPEAT_COUNT":
				$resultMisfire = "withMisfireHandlingInstructionNowWithExistingCount";
				break;
			case "RESCHEDULE_NOW_WITH_REMAINING_REPEAT_COUNT":
				$resultMisfire = "withMisfireHandlingInstructionNowWithRemainingCount";
				break;
			default: //SMART_POLICY
				$resultMisfire = "";
				break;
         }		
			//Email
			if ($process_list[0]['Email'] ==""){
				$email = '';
			}else{
				$email = $process_list[0]['Email'];
			}
	
			//url
			if ($process_list[0]['url']==""){
				$url="";
			}else{
				$url = $process_list[0]['url'];
			}
			
			//ProcessParameter
			if ($process_list[0]['ProcessParameter']==""){
				$parameters = '{}';
			}else{
				$parameters = $process_list[0]['ProcessParameter'];
			}
				/*
				if ($file_position != ""){
					 echo "repository_destination:".$repository_destination."<br>";
					 echo "file_position:".$file_position."<br>";
					 echo "prod_sched:".$prod_sched."<br>";

					$vecchiaPosizione = $repository_destination.$file_position;
					//$nuovaPosizione = '//'.$prod_sched.$prod_repository.$file_position;
					//$nuovaPosizione = $prod_repository.$file_position;
					$nuovaPosizione = '\\\192.168.56.200\share\jobs\\'.$file_position;
					$file_da_copiare = explode("/",$file_position);
					//$struttura_da_creare = '//'.$prod_sched.$prod_repository;
					$struttura_da_creare = $prod_repository;
					//var_dump($file_da_copiare);
					foreach ($file_da_copiare as $cartella){
						if ($cartella !=""){
							//echo $cartella.'<br>';
							$struttura_da_creare = $struttura_da_creare.'/'.$cartella;
							echo $struttura_da_creare.'<br>';
							if (!is_dir($struttura_da_creare)){
							mkdir($struttura_da_creare, 0777, true);
							}
						}
					}
					////////////////CREAZIONE TABELLE//////////////
					echo "file_position:".$file_position."</br>";
					echo "Vecchia posizione:".$vecchiaPosizione."</br>";
					echo "Nuova posizione:".$nuovaPosizione."</br>";
					///		
					function recurse_copy($src, $dst) 
					{ 
						$openDirResult = opendir($src);
						if (!is_dir($dst)){
						mkdir($dst, 0777, true);	
						}
						do
						{
							$item = readdir($openDirResult);
							
							echo 'Item: ' . $item . '<br/>';
							
							if(($item != '.')&&($item != '..')&&($item != FALSE)) 
							{ 
								if(is_dir($src . '/' . $item )) 	
								{
									$newDirName = $dst . '/' . $item;
									echo "New dir name: " . $newDirName . "<br/>";
									@mkdir($newDirName, 0777, true);									
									recurse_copy( $src . '/' . $item, $dst . '/' . $item );  
								}	 
								else 
								{ 
									echo 'Arrivati ad un file: ' . $item . '<br/>';
									copy( $src . '/' . $item, $dst . '/' . $item); 
								} 
							} 
						}
						while(($item !== false));  //Scansionamento del contenuto di una directory, elemento per elemento	
						closedir($openDirResult); 
					}
					
					recurse_copy($vecchiaPosizione, $nuovaPosizione);
					////////////////FINE CREAZIONE TABELLE//////////////////
				} else {
					echo "POSIZIONE DEL FILE NON TROVATA!";
					//$trova_poszione = $parameters;
					echo $parameters;
					header("location:upload_process_production.php?message=error_file_upload");
				}
				*/
				//COntrolla tipo del processo //
				
				$testo_parameters = $parameters;
				//CASO_R
				if (strpos($testo_parameters,'Main.R')!== false){
					$posizione_file = $prod_repository.$file_position.'/Main.R';
					//$posizione_file = $repository_destination.$file_position.'/Main.R';
					$parameters='{"R":"/usr/bin/Rscript","RNEW":"'.$posizione_file.'"}';
					$parameters=str_replace ('"','\"',$parameters);
					$parameters=str_replace ('/','\\\/',$parameters);
				}
				//CASO ETL
				if (strpos($testo_parameters,'Main.kjb')!== false){
				$posizione_file = $prod_repository.$file_position.'/Ingestion/Main.kjb';	
				//$parameters='{"-Xmx512m":"-Xmx512m","-classpath":"-classpath","'.$prod_integration.'":"'.$prod_integration.'","-DDI_HOME":"'.$prod_home.'","org.pentaho.di.kitchen.Kitchen":"org.pentaho.di.kitchen.Kitchen","-file":"-file='.$posizione_file.'","-level":"-level=Debug"}';
				$parameters='{"-Xmx512m":"-Xmx512m"},{"-classpath":"-classpath"},{"'.$prod_integration.'":"'.$prod_integration.'"},{"-DDI_HOME":"'.$prod_home.'"},{"org.pentaho.di.kitchen.Kitchen":"org.pentaho.di.kitchen.Kitchen"},{"-file":"-file='.$posizione_file.'"},{"-level":"-level=Debug"}';
				//
				$parameters=str_replace ('"','\"',$parameters);
				$parameters=str_replace ('/','\\\/',$parameters);
				}
				//CASO JAVA
				if (strpos($testo_parameters,'Main.java')!== false){
					$posizione_file = $prod_repository.$file_position.'/Main.java';
					//$posizione_file = $repository_destination.$file_position.'/Main.java';
					$parameters='{"JAR":"'.$posizione_file.'/Main.java"}';
					$parameters=str_replace ('"','\"',$parameters);
					$parameters=str_replace ('/','\\\/',$parameters);
				}
			//
			
			//JobConstraint
			if($process_list[0]['JobConstraint']==""){
				$jobConstraint = "";
			}else{
				$jobConstraint = $process_list[0]['JobConstraint'];
				//$jobConstraint=str_replace ('"','\"',$jobConstraint);
				//$jobConstraint=str_replace ('/','\\\/',$jobConstraint);
				
			}
			
			//Path
			//$path_p=$process_list[0]['process_path'];
			$path_p=$prod_path;
			
			//Interval
			if($process_list[0]['time_interval']==""){
				$time_interval='';
			}else{
				$time_interval=$process_list[0]['time_interval'];
			}
			
			//CARICAMENTO DEL PROCESSO		
			$creation_date_p = date("Y-m-d H:i:s");
			
					$data = '?json=' . urlencode('{"startAt":"'.$startAt.'","endAt":"'.$endAt.'","withJobIdentityNameGroup":["'.$name_process.'","'.$process_group.'"],"jobClass":"'.$process_type.'","withDescription":"'.$trigger_description.'","withJobDescription":"'.$process_description.'","withIdentityNameGroup":["'.$trigger_name.'","'.$trigger_group.'"],"withPriority":"'.$priority.'","repeatForever":"true","storeDurably":"'.$store_p1.'","requestRecovery":"'.$recovery_p1.'","withIntervalInSeconds":"'.$time_interval.'","repeatForever":"true","id":"scheduleJob","'.$resultMisfire.'":"","restUrl":"'.$url.'","jobDataMap":{"#notificationEmail":"'.$email.'","#url":"'.$url.'","#isNonConcurrent":"'.$conc_p1.'","#processParameters":"[{\"processPath\":\"'.$path_p.'\"},'.$parameters.']","#jobConstraints":"['.$jobConstraint.']"}}');
					//echo 'http://'.$prod_sched.':8080/SmartCloudEngine/index.jsp?json={"startAt":"'.$startAt.'","endAt":"'.$endAt.'","withJobIdentityNameGroup":["'.$name_process.'","'.$process_group.'"],"jobClass":"'.$process_type.'","withDescription":"'.$trigger_description.'","withJobDescription":"'.$process_description.'","withIdentityNameGroup":["'.$trigger_name.'","'.$trigger_group.'"],"withPriority":"'.$priority.'","repeatForever":"true","storeDurably":"'.$store_p1.'","requestRecovery":"'.$recovery_p1.'","withIntervalInSeconds":"'.$time_interval.'","repeatForever":"true","id":"scheduleJob","'.$resultMisfire.'":"","restUrl":"'.$url.'","jobDataMap":{"#notificationEmail":"'.$email.'","#url":"'.$url.'","#isNonConcurrent":"'.$conc_p1.'","#nextJobs":"'.$nextJob.'","#processParameters":"[{\"processPath\":\"'.$path_p.'\"},'.$parameters.']","#jobConstraints":"['.$jobConstraint.']"}}';
					$apiUrl = 'http://'.$prod_sched.':8080/SmartCloudEngine/index.jsp';
					$apiUrl = $apiUrl.$data;
					echo $apiUrl;
					//
					
					$options = array(
								'http' => array(
								'header'  => "Content-type: application/json\r\n",
								'method'  => 'POST',
							)
						);
					
						$context  = stream_context_create($options);
						$result = file_get_contents($apiUrl, false, $context);
								if(strpos($http_response_header[0], '200') !== false){
									
									   $query2="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$creation_date_p."','".$process_id."','".$process_name."','".$process_list[0]['process_group']."','LOADED IN PRODUCTION')";
									   $query_process2 = mysqli_query($connessione_al_server,$query2) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
									   //////MOSTRA NEI PROCESSI IN ESECUZIONE
									   
									   $query3="INSERT INTO `processes` (`Id`, `Process_name`, `Process_group`, `job_type`, `Start_time`, `End_time`, `Time_interval`, `Status`, `Process_Type`, `Creation_date`, `non_concurrent`, `StoreDurably`, `RequestRecovery`, `Process_description`, `url`, `process_path`, `MisfireInstruction`, `Email`, `id_disces`, `trigger_name`, `trigger_group`, `trigger_description`, `priority`, `repeat_count`, `time_out`, `dataMap`, `nextJob`, `JobConstraint`, `ProcessParameter`, `file_position`) VALUES (NULL, '".$name_process."', '".$process_group."', '".$process_list[0]['job_type']."', '".$process_list[0]['start']."', '".$process_list[0]['End']."', '".$time_interval."', 'LOADED IN PRODUCTION', '".$process_type."', '".$creation_date_p."', '".$process_list[0]['non_concurrent']."', '".$process_list[0]['StoreDurably']."', '".$process_list[0]['RequestRecovery']."', '', '".$url."', '".$process_list[0]['process_path']."', '".$misfire_p."', '".$email."', '".$prod_sched."', '".$trigger_name."', '".$trigger_group."', '".$trigger_description."', '".$priority."', '".$process_list[0]['repeat_count']."', '".$process_list[0]['time_out']."', '".$process_list[0]['dataMap']."', '".$nextJob."', '".$jobConstraint."', '".$parameters."','".$file_position."')"; 
									   $query_process3 = mysqli_query($connessione_al_server,$query3) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
									   
									   //////
									  //// header("location:upload_process_production.php?message=ok");
											if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:upload_process_production.php?message=ok&showFrame=false");
													}else{
													header ("location:upload_process_production.php?message=ok");
													}	
												}else{
												header ("location:upload_process_production.php?message=ok");
												}
									  
									  /////////////////////
									}
									else
									{
										//Restituire qualche fail alla GUI post
										//////header("location:upload_process_production.php?message=error");
											if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:upload_process_production.php?message=error&showFrame=false");
													}else{
													header ("location:upload_process_production.php?message=error");
													}	
												}else{
												header ("location:upload_process_production.php?message=error");
												}
										
										///////////////
									}
				
			//FINE CARICAMENTO DEL PROCESSO
        }else {
			/////////////header("location:upload_process_production.php?message=no_exist");
				if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:upload_process_production.php?message=no_exist&showFrame=false");
													}else{
													header ("location:upload_process_production.php?message=no_exist");
													}	
												}else{
												header ("location:upload_process_production.php?message=no_exist");
												}
			
			///////////
		}
}else{
	header ("location:page.php");
}
?>