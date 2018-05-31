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

if(isset($_REQUEST['Crea_job_type'])){
	//ID del Job Type
	$job_type_id = $_POST['id'];
	echo $job_type_id;
	//
	$user_zip=$_POST['user_zip'];
	$tipo_zip = $_POST['tipo_zip'];
   $name_jb = $_POST['nome'];
   $group_jb=$_POST['gruppo'];
   $desc_jb=$_POST['descrizione'];
   $file_zip_jb=$_POST['file_zip'];
   $data_zip=$_POST['data_zip'];
   $creation_date_jb=date("Y-m-d H:i:s");
   $url_jb=$_POST['url'];
   $path_jb=$_POST['path'];
   $type_jb=$_POST['type'];
   $email_jb=$_POST['email'];
   $nome_trig_jb=$_POST['nome_trig'];
   $descrizione_trig_jb=$_POST['descrizione_trig'];
   $gruppo_trig_jb=$_POST['gruppo_trig'];
   $interval_trig_jb=$_POST['interval_trig'];
   $start_jb=$_POST['start'];
   $end_jb=$_POST['end'];
   $misfire_jb=$_POST['misfire'];
   
   //priority
       if (isset($_POST['priority'])){
	$priority_jb=$_POST['priority'];
	}else {
		$priority_jb=0;
	}  
   
   
   //repeat_trig
        if (isset($_POST['repeat_trig'])){
		$repeat_trig_jb=$_POST['repeat_trig'];
		}else {
			$repeat_trig_jb=0;
		}  
   
   //time_out
      if (isset($_POST['time_out']) && $_POST['time_out'] != ""){
	$time_out_jb=$_POST['time_out'];
	$DataMap_jb = '{"#timeout":"'.$time_out_jb.'"}';
	}else {
		$time_out_jb= "";
		$DataMap_jb="";
	}
   
   //check
        if (isset($_POST['conc'])){
			$conc_jb=$_POST['conc'];
		}else {
			$conc_jb=0;
		}

		if (isset($_POST['store'])){
		$store_jb=$_POST['store'];
		}else {
			$store_jb=0;
		}
  
		if (isset($_POST['recovery'])){
			$recovery_jb=$_POST['recovery'];
		}else {
			$recovery_jb=0;
		}
	//posizione file//
	$file_position = $_POST['file_position'];
	//
   
   //I PARAMETRI AVANZATI SONO ANCORA DA DEFINIRE - vengono passati al db come dei json.
	//$DataMap_jb = $_POST['datamapText'];
	//
	$NextJob_jb= $_POST['NextJobText'];
	
	$JobConstraint_jb= $_POST['JobConstraintText'];
   //////
   ///////PROCESS PARAMETER
		$data_zip2 = str_replace(":","-", $data_zip);
	 $data_zip3 = str_replace(":","-", $data_zip2);
	 $data_zip4 = str_replace(" ","-", $data_zip3);
	 $destintion_serv = $server_share;
	 
	 //echo $destintion_serv;
	 $file_name01 = str_replace(".zip","",$file_zip_jb); 
	 $path_prova = $destintion_serv.$user_zip.'/'.$data_zip4.'/'.$file_name01;
	 
	 $path01 = $path_prova.'/Ingestion/Main.kjb';
	 $path02 = $path_prova.'/RealTime/Ingestion/Main.kjb';
	 $path03= $path_prova.'/RT/Ingestion/Main.kjb';
	 $path04= $path_prova.'/Real_Time/Ingestion/Main.kjb';
	 $path05= $path_prova.'/realtime/Ingestion/Main.kjb';
	 
	 //Scandire la cartella $path_prova
	 $array_scan = scandir($path_prova);
	 
	 echo $path03;
	 $found = false;
	 if (file_exists($path01)){
		 $path_file = $path01;
		 $file_position =$user_zip.'/'.$data_zip4.'/'.$file_name01;
		 $found = true;
		 
	 }
	 if (file_exists($path02)){
		 $path_file = $path02;
		 $file_position =$user_zip.'/'.$data_zip4.'/'.$file_name01.'/RealTime';
		 $found = true;
	 }
	 if (file_exists($path03)){
		 $path_file = $path03;
		  $file_position =$user_zip.'/'.$data_zip4.'/'.$file_name01.'/RT';
		  $found = true;
	 }
	 if (file_exists($path04)){
		 $path_file = $path04;
		  $file_position =$user_zip.'/'.$data_zip4.'/'.$file_name01.'/Real_Time';
		  $found = true;
	 }
	 if (file_exists($path05)){
		 $path_file = $path05;
		  $file_position =$user_zip.'/'.$data_zip4.'/'.$file_name01.'/realtime';
		  $found = true;
	 }
	 
	 /*
	if ($found == false) {
		$m = count($array_scan);
		$findme = '/Ingestion/Main.kjb'
		for ($i = 0; $i < $m; $i++) {
			$pos = strpos($array_scan[$i], $findme);
						if ($pos!== false){
							 $path_file = $array_scan[$i];
							 $file_position =$user_zip.'/'.$data_zip4.'/'.$file_name01;
						 }
				}
			}
		*/
		
	if ($tipo_zip=='ETL'){
		$ProcessParameter_jb = '{"'.$xms.'":"'.$xms.'","-classpath":"-classpath","'.$data_integration_path.'":"'.$data_integration_path.'","-DDI_HOME":"'.$DDI_HOME.'","'.$pentaho.'":"'.$pentaho.'","-file":"-file='.$path_file.'","-level":"-level='.$level.'","-param:processName":"-param:processName='.$file_name01.'"}';
	}
	if ($tipo_zip=='R'){
		//$ProcessParameter_jb = '{"R":"'.$r_path.'","'.$file_name01.'":"'.$server_share.$user_zip.'/'.$data_zip4.'/'.$file_name01.'/Main.R"}';
		$ProcessParameter_jb = '{"'.$file_name01.'":"'.$server_share.$user_zip.'/'.$data_zip4.'/'.$file_name01.'/Main.R"}';
	}
	if($tipo_zip=='Java'){
		//$ProcessParameter_jb = '{"'.$file_name01.'":"'.$repository_files.$user_zip.'/'.$data_zip4.'/'.$file_name01.'/Main.java"}';
		$ProcessParameter_jb = '{"-classpath":"-classpath","-'.$file_name01.'":":'.$path_prova.'/*","Main":"Main"}';
	}
	
	//$file_position =$user_zip.'/'.$data_zip4.'/'.$file_name01;
   //////
   ///
   //next job
   
   if ((isset($_POST['par_next_job_cond1']))&&(isset($_POST['next_job_result1']))&&(isset($_POST['next_job_n1']))&&(isset($_POST['next_job_g1']))){
   $par_next_job_cond=$_POST['par_next_job_cond1'];
   $next_job_result=$_POST['next_job_result1'];
   $next_job_n=$_POST['next_job_n1'];
   $next_job_g=$_POST['next_job_g1'];
   $NextJob = array('condition' =>$par_next_job_cond,'result'=>$next_job_result,'name'=>$next_job_n,'group'=>$next_job_g);
   $NextJob_jb = json_encode($NextJob);
   echo ("NEXT_JOB:  ".$NextJob_jb);
   }else {
	   $NextJob_jb='';
	   }
   
   //Job Constraint
   if((isset($_POST['job_cons_cond1']))&&(isset($_POST['job_cons_v1']))){
   $job_cons_k=$_POST['job_cons_k1'];
   $job_cons_cond=$_POST['job_cons_cond1'];
   $job_cons_v=$_POST['job_cons_v1'];
   $JobConstraint=array('key'=>$job_cons_k,'condition'=>$job_cons_cond,'value'=>$job_cons_v);
   $JobConstraint_jb=json_encode($JobConstraint);
   }else{
	$JobConstraint_jb='';   
   }
   
   //
   
	 $query="INSERT INTO `job_type` (`Id`, `job_type_name`, `job_type_group`, `file_zip`, `file_name`, `type`, `start_time`, `end_time`, `Time_interval`, `creation_date`, `job_type_description`, `url`, `path`, `e-mail`, `storeDurably`, `non_concurrent`, `requestRecovery`, `Trigger_name`, `Trigger_group`, `Trigger_description`, `Priority`, `RepeatCount`, `ProcessParameter`, `MisfireInstruction`, `Time_out`, `DataMap`, `NextJob`, `JobConstraint`,`file_position`) VALUES (NULL,'".$name_jb."','".$group_jb."','".$job_type_id."','".$file_zip_jb."','".$type_jb."','".$start_jb."','".$end_jb."','".$interval_trig_jb."','".$creation_date_jb."','".$desc_jb."','".$url_jb."','".$path_jb."','".$email_jb."','".$store_jb."','".$conc_jb."','".$recovery_jb."','".$nome_trig_jb."', '".$gruppo_trig_jb."','".$descrizione_trig_jb."','".$priority_jb."','".$repeat_trig_jb."','".$ProcessParameter_jb."','".$misfire_jb."','".$time_out_jb."','".$DataMap_jb."','".$NextJob_jb."','".$JobConstraint_jb."','".$file_position."')";
	 echo ($query);
	 
   $query_job_type = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
   //header("location:job_type.php");
		if (isset($_REQUEST['showFrame'])){
	if ($_REQUEST['showFrame'] == 'false'){
		header ("location:job_type.php?showFrame=false");
	}else{
		header ("location:job_type.php");
	}	
}else{
	header ("location:job_type.php");
}
		//
}else{
    echo ("Errore nella creazione del jobtype".mysqli_error($connessione_al_server));
}
