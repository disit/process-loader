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
   
include("config.php");
if(isset($_REQUEST['carica_processo'])){

	$id_p = $_POST['id'];
	//
   $name_p = $_POST['nome'];
   $group_p=$_POST['gruppo'];
   $job_type=$_POST['file'];
   $desc_p=$_POST['descrizione'];
   $creation_date_p=date("Y-m-d H:i:s");
   // 
   $url_p=$_POST['url'];
   $path_p=$_POST['path'];
   $type_p=$_POST['type_j'];
  //
   $email_p=$_POST['email'];
   
   if (isset($_POST['time_out'])){
	$time_out_p=$_POST['time_out'];
	$DataMap_p = '{"#timeout":"'.$time_out_p.'"}';
}else {
	$time_out_p=0;
	$DataMap_p ='';
}
   
     if (isset($_POST['conc'])){
	$conc_p=$_POST['conc'];
}else {
	$conc_p=0;
}
   

  if (isset($_POST['store'])){
	$store_p=$_POST['store'];
}else {
	$store_p=0;
}
  

	//
if (isset($_POST['recovery'])){
	$recovery_p=$_POST['recovery'];
}else {
	$recovery_p=0;
}
   
   //
   $nome_trig_p=$_POST['nome_trig'];
   $descrizione_trig_p=$_POST['descrizione_trig'];
   $gruppo_trig_p=$_POST['gruppo_trig'];
   

     if (isset($_POST['repeat_trig'])){
	$repeat_trig_p=$_POST['repeat_trig'];
}else {
	$repeat_trig_p=0;
}  
   
   $interval_trig_p=$_POST['interval_trig'];
   $start_p=$_POST['start'];
   $end_p=$_POST['end'];

 
    if (isset($_POST['priority'])){
	$priority_p=$_POST['priority'];
	}else {
		$priority_p=0;
	}  
   
   
   //
   $misfire_p=$_POST['misfire'];
   //I PARAMETRI AVANZATI SONO ANCORA DA DEFINIRE - vengono passati al db come dei json.

   $NextJob_p =$_POST['NextJobText'];

	$ProcessParameter_p=$_POST['ProcParameters'];
   $JobConstraint_p=$_POST['JobConstraintText'];

	 $query="INSERT INTO `processes` (`Id`, `Process_name`, `Process_group`, `job_type`, `Start_time`, `End_time`, `Time_interval`, `Status`, `Process_Type`, `Creation_date`, `non_concurrent`, `StoreDurably`, `RequestRecovery`, `Process_description`, `url`, `process_path`, `MisfireInstruction`, `Email`, `id_disces`, `trigger_name`, `trigger_group`, `trigger_description`, `priority`, `repeat_count`, `time_out`, `dataMap`, `nextJob`, `JobConstraint`, `ProcessParameter`) VALUES (NULL, '".$name_p."', '".$group_p."', '".$id_p."', '".$start_p."', '".$end_p."', '".$interval_trig_p."', 'CREATED', '".$type_p."', '".$creation_date_p."', '".$conc_p."', '".$store_p."', '".$recovery_p."', '', '".$url_p."', '".$path_p."', '".$misfire_p."', '".$email_p."', '', '".$nome_trig_p."', '".$gruppo_trig_p."', '".$descrizione_trig_p."', '".$priority_p."', '".$repeat_trig_p."', '".$time_out_p."', '".$DataMap_p."', '".$NextJob_p."', '".$JobConstraint_p."', '".$ProcessParameter_p."')";
	 echo ($query);
	 
   $query_process = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
   
   //CARICAMENTO ARCHIVIO -- 
   $sql = "SELECT MAX(`Id`) FROM `processes`";
   $result3 = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
   $id_proc = $result3->fetch_assoc();
   echo (var_dump($id_proc));
   
   
   $query2="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$creation_date_p."','".$id_proc["MAX(`Id`)"]."','".$name_p."','".$group_p."','CREATION')";
  $query_process2 = mysqli_query($connessione_al_server,$query2) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
   
  
   header("location:job_type.php");
   
}else{
    echo ("Errore nella creazione del job".mysqli_error($connessione_al_server));
};
