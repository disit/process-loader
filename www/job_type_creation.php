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
if (isset ($_SESSION['username'])&& isset($_SESSION['role'])){
if(isset($_REQUEST['Crea_job_type'])){
	//ID del Job Type
	$job_type_id = mysqli_real_escape_string($connessione_al_server,$_POST['id']);
	$job_type_id = filter_var($job_type_id, FILTER_SANITIZE_STRING);
	//
	$user_zip= mysqli_real_escape_string($connessione_al_server,$_POST['user_zip']);
	$user_zip = filter_var($user_zip, FILTER_SANITIZE_STRING);
	//
	$tipo_zip = mysqli_real_escape_string($connessione_al_server,$_POST['tipo_zip']);
	$tipo_zip = filter_var($tipo_zip, FILTER_SANITIZE_STRING);
	//
   $name_jb = mysqli_real_escape_string($connessione_al_server,$_POST['nome']);
   $name_jb = filter_var($name_jb, FILTER_SANITIZE_STRING);
   //
   $group_jb= mysqli_real_escape_string($connessione_al_server,$_POST['gruppo']);
   $group_jb= filter_var($group_jb, FILTER_SANITIZE_STRING);
   //
   $desc_jb= mysqli_real_escape_string($connessione_al_server,$_POST['descrizione']);
   $desc_jb= filter_var($desc_jb, FILTER_SANITIZE_STRING);
   //
   $file_zip_jb= mysqli_real_escape_string($connessione_al_server,$_POST['file_zip']);
   $file_zip_jb= filter_var($file_zip_jb, FILTER_SANITIZE_STRING);
   //
   $data_zip= mysqli_real_escape_string($connessione_al_server,$_POST['data_zip']);
   $data_zip= filter_var($data_zip, FILTER_SANITIZE_STRING);
   //
   $creation_date_jb=date("Y-m-d H:i:s");
   //
   $url_jb= mysqli_real_escape_string($connessione_al_server,$_POST['url']);
   $url_jb= filter_var($url_jb, FILTER_SANITIZE_STRING);
   //
   $path_jb= mysqli_real_escape_string($connessione_al_server,$_POST['path']);
   $path_jb= filter_var($path_jb, FILTER_SANITIZE_STRING);
   //
   $type_jb= mysqli_real_escape_string($connessione_al_server,$_POST['type']);
   $type_jb= filter_var($type_jb, FILTER_SANITIZE_STRING);
   //
   $email_jb= mysqli_real_escape_string($connessione_al_server,$_POST['email']);
   $email_jb= filter_var($email_jb, FILTER_SANITIZE_STRING);
   //
   $nome_trig_jb= mysqli_real_escape_string($connessione_al_server,$_POST['nome_trig']);
   $nome_trig_jb= filter_var($nome_trig_jb, FILTER_SANITIZE_STRING);
   //
   $descrizione_trig_jb=mysqli_real_escape_string($connessione_al_server,$_POST['descrizione_trig']);
   $descrizione_trig_jb= filter_var($descrizione_trig_jb, FILTER_SANITIZE_STRING);
   //
   $gruppo_trig_jb= mysqli_real_escape_string($connessione_al_server,$_POST['gruppo_trig']);
   $gruppo_trig_jb= filter_var($gruppo_trig_jb, FILTER_SANITIZE_STRING);
   //
   $interval_trig_jb= mysqli_real_escape_string($connessione_al_server,$_POST['interval_trig']);
   $interval_trig_jb= filter_var($interval_trig_jb, FILTER_SANITIZE_STRING);
   //
   $start_jb= mysqli_real_escape_string($connessione_al_server,$_POST['start']);
   $start_jb= filter_var($start_jb, FILTER_SANITIZE_STRING);
   //
   $end_jb= mysqli_real_escape_string($connessione_al_server,$_POST['end']);
   $end_jb= filter_var($end_jb, FILTER_SANITIZE_STRING);
   //
   $misfire_jb= mysqli_real_escape_string($connessione_al_server,$_POST['misfire']);
   $misfire_jb= filter_var($misfire_jb, FILTER_SANITIZE_STRING);
   //
   
   //priority
       if (isset($_POST['priority'])){
	$priority_jb=mysqli_real_escape_string($connessione_al_server,$_POST['priority']);
	$priority_jb= filter_var($priority_jb, FILTER_SANITIZE_STRING);
	}else {
		$priority_jb=0;
	}  
   
   //echo('TIPO ZIP: '.$tipo_zip.'	!!!	');
   //repeat_trig
        if (isset($_POST['repeat_trig'])){
		$repeat_trig_jb=mysqli_real_escape_string($connessione_al_server,$_POST['repeat_trig']);
		$repeat_trig_jb= filter_var($repeat_trig_jb, FILTER_SANITIZE_STRING);
		}else {
			$repeat_trig_jb=0;
		}  
   
   //time_out
      if (isset($_POST['time_out']) && $_POST['time_out'] != ""){
	$time_out_jb=mysqli_real_escape_string($connessione_al_server,$_POST['time_out']);
	$time_out_jb= filter_var($time_out_jb, FILTER_SANITIZE_STRING);
	$DataMap_jb = '{"#timeout":"'.$time_out_jb.'"}';
	}else {
		$time_out_jb= "";
		$DataMap_jb="";
	}
   
   //check
        if (isset($_POST['conc'])){
			$conc_jb=mysqli_real_escape_string($connessione_al_server,$_POST['conc']);
			$conc_jb= filter_var($conc_jb, FILTER_SANITIZE_STRING);
		}else {
			$conc_jb=0;
		}

		if (isset($_POST['store'])){
		$store_jb=mysqli_real_escape_string($connessione_al_server,$_POST['store']);
		$store_jb= filter_var($store_jb, FILTER_SANITIZE_STRING);
		}else {
			$store_jb=0;
		}
  
		if (isset($_POST['recovery'])){
			$recovery_jb=mysqli_real_escape_string($connessione_al_server,$_POST['recovery']);
			$recovery_jb= filter_var($recovery_jb, FILTER_SANITIZE_STRING);
		}else {
			$recovery_jb=0;
		}
	//posizione file//
	$file_position = mysqli_real_escape_string($connessione_al_server,$_POST['file_position']);
	$file_position= filter_var($file_position, FILTER_SANITIZE_STRING);
	//
   
   //I PARAMETRI AVANZATI SONO ANCORA DA DEFINIRE - vengono passati al db come dei json.
	//$DataMap_jb = $_POST['datamapText'];
	//
	$NextJob_jb= mysqli_real_escape_string($connessione_al_server,$_POST['NextJobText']);
	$NextJob_jb= filter_var($NextJob_jb, FILTER_SANITIZE_STRING);
	
	$JobConstraint_jb= mysqli_real_escape_string($connessione_al_server,$_POST['JobConstraintText']);
	$JobConstraint_jb= filter_var($JobConstraint_jb, FILTER_SANITIZE_STRING);
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
	 
	 //echo('$path_file:	'.$path_file);
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
   $par_next_job_cond=mysqli_real_escape_string($connessione_al_server,$_POST['par_next_job_cond1']);
   $par_next_job_cond= filter_var($par_next_job_cond, FILTER_SANITIZE_STRING);
   
   $next_job_result=mysqli_real_escape_string($connessione_al_server,$_POST['next_job_result1']);
   $next_job_result= filter_var($next_job_result, FILTER_SANITIZE_STRING);
   
   $next_job_n=mysqli_real_escape_string($connessione_al_server,$_POST['next_job_n1']);
   $next_job_n= filter_var($next_job_n, FILTER_SANITIZE_STRING);
   
   $next_job_g=mysqli_real_escape_string($connessione_al_server,$_POST['next_job_g1']);
   $next_job_g= filter_var($next_job_g, FILTER_SANITIZE_STRING);
   
   $NextJob = array('condition' =>$par_next_job_cond,'result'=>$next_job_result,'name'=>$next_job_n,'group'=>$next_job_g);
   $NextJob_jb = json_encode($NextJob);
   echo ("NEXT_JOB:  ".$NextJob_jb);
   }else {
	   $NextJob_jb='';
	   }
   
   //Job Constraint
   if((isset($_POST['job_cons_cond1']))&&(isset($_POST['job_cons_v1']))){
   $job_cons_k=mysqli_real_escape_string($connessione_al_server,$_POST['job_cons_k1']);
   $job_cons_k= filter_var($job_cons_k, FILTER_SANITIZE_STRING);
   
   $job_cons_cond=mysqli_real_escape_string($connessione_al_server,$_POST['job_cons_cond1']);
   $job_cons_cond= filter_var($job_cons_cond, FILTER_SANITIZE_STRING);
   
   $job_cons_v=mysqli_real_escape_string($connessione_al_server,$_POST['job_cons_v1']);
   $job_cons_v= filter_var($job_cons_v, FILTER_SANITIZE_STRING);
   
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

}else{
	header ("location:page.php");
}
