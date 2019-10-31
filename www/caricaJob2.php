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
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);

//echo "Dati ricevuti?";
//echo $dati;
//$row = Json_encode($dati);
//echo $row;

	//$dati = $_REQUEST['dati'];
	$id_p = $_REQUEST['id'];
	//echo $id_p;
   $name_p0 = $_REQUEST['nome'];
   $name_p = filter_var($name_p0, FILTER_SANITIZE_STRING);
   //
   $group_p0 = $_REQUEST['gruppo'];
   $group_p = filter_var($group_p0, FILTER_SANITIZE_STRING);
   //
   $job_type0 = $_REQUEST['file'];
   $job_type = filter_var($job_type0, FILTER_SANITIZE_STRING);
   //
   $desc_p0 = mysqli_real_escape_string($connessione_al_server,$_REQUEST['descrizione']);
   $desc_p = filter_var($desc_p0, FILTER_SANITIZE_STRING);
   //
   $creation_date_p = date("Y-m-d H:i:s");
   
   //echo "<b>DESCRIZIONE:</b>".$desc_p."<br />";
   
   if ((isset($_REQUEST['url']))&&($_REQUEST['url'] !="")){
	//   
   $url_p0 = $_REQUEST['url'];
   $url_p = filter_var($url_p0, FILTER_SANITIZE_STRING);
   //
   $url_data='"#url":"'.$url_p.'",';
   }else{
	 $url_p="";
	 $url_data="";
   }
   //
   if ((isset($_REQUEST['path']))&&($_REQUEST['path'] !="")){
   $path_p = $_REQUEST['path'];
   }else{
	$path_p = "";
   }
   //
   $path_p=str_replace ('"','\"',$path_p);
   $path_p=str_replace ('/','\\\/',$path_p);
  // echo $path_p;
   $type_p = $_REQUEST['type_j'];
   //controllo tipo
   if ($type_p == "REST"){
	   $type_p ='RESTJob';
   }else{
	   $type_p ='ProcessExecutorJob';
   }
   
   //
   if(isset($_REQUEST['email'])&&($_REQUEST['email'] != "")){
   $email_p0 = $_REQUEST['email'];
   $email_p = filter_var($email_p0, FILTER_SANITIZE_EMAIL);
   $mail_data='"#notificationEmail":"'.$email_p.'",';
   }else{
	  $email_p=""; 
	  $mail_data="";
   }
   
   if(isset($_REQUEST['time_out'])&&($_REQUEST['time_out'] != ""))
   {
	  $time_out_p0 = $_REQUEST['time_out'];
	  $time_out_p = filter_var($time_out_p0, FILTER_SANITIZE_STRING);
	  
	  $DataMap_p = '"#jobTimeout":"'. $time_out_p .'",';
   }
   else 
   {
	  $time_out_p = 0;
	  $DataMap_p = '';
   }
   
   if(isset($_REQUEST['conc']))
   {
	  $conc_p0 = $_REQUEST['conc'];
	  $conc_p = filter_var($conc_p0, FILTER_VALIDATE_INT);
	  $conc_p1 = 'true';
   }
   else
   {
	  $conc_p = 0;
	  $conc_p1 = 'false';
   }
   
  if(isset($_REQUEST['store']))
  {
	$store_p0 = $_REQUEST['store'];
	$store_p = filter_var($store_p0, FILTER_VALIDATE_INT);
	$store_p1 = 'true';
  }
  else 
  {
	$store_p1 = 'false';
	$store_p = 0;
  }
  
  if(isset($_REQUEST['recovery']))
  {
	 $recovery_p0 = $_REQUEST['recovery'];
	 $recovery_p = filter_var($recovery_p0, FILTER_SANITIZE_STRING);
	 $recovery_p1 = 'true';
  }
  else 
  {
	$recovery_p = 0;
	$recovery_p1 = 'false';
  }
  
   $nome_trig_p0 = $_REQUEST['nome_trig'];
   $nome_trig_p = filter_var($nome_trig_p0, FILTER_SANITIZE_STRING);
   ///
   
   $descrizione_trig_p0 = $_REQUEST['descrizione_trig'];
   $descrizione_trig_p = filter_var($descrizione_trig_p0, FILTER_SANITIZE_STRING);
   //
   $gruppo_trig_p0 = $_REQUEST['gruppo_trig'];
   $gruppo_trig_p = filter_var($gruppo_trig_p0, FILTER_SANITIZE_STRING);
   //
   
    if (isset($_REQUEST['repeat_trig']))
	{
	   $repeat_trig_p0 = $_REQUEST['repeat_trig'];
	   $repeat_trig_p = filter_var($repeat_trig_p0, FILTER_SANITIZE_STRING);
	   $repeat_trig_p1 = 'true';
	}
	else 
	{
	   $repeat_trig_p = 0;
	   $repeat_trig_p1 = 'false';
	}  
   
   $interval_trig_p0 = $_REQUEST['interval_trig'];
   $interval_trig_p = filter_var($interval_trig_p0, FILTER_SANITIZE_STRING);
   
   if(isset($_REQUEST['start'])){
   $start_p = mysqli_real_escape_string($connessione_al_server,$_REQUEST['start']);
   echo "START AT	".$start_p."<br>";
   echo $start_p_sec = date_create($start_p)->getTimestamp();
   $start_p_sec = $start_p_sec.'000';
   }else{
	$start_p ="";
   }
   
   if(isset($_REQUEST['end'])&& $_REQUEST['end'] != ""){
   $end_p = mysqli_real_escape_string($connessione_al_server,$_REQUEST['end']);
   echo "END AT:	".$end_p."<br>";
   echo $end_p_sec = date_create($end_p)->getTimestamp();
   $end_p_sec = $end_p_sec.'000';
   $end_p_sec = '"endAt":"'.$end_p_sec.'",';
   }else{
	$end_p=""; 
    $end_p_sec ="";	
   }
    if(isset($_REQUEST['priority']))
	{
		$priority_p0 = $_REQUEST['priority'];
		$priority_p = filter_var($priority_p0, FILTER_VALIDATE_INT);
	}
	else 
	{
		$priority_p = 0;
	}  
   
   $misfire_p0 = $_REQUEST['misfire'];
   $misfire_p = filter_var($misfire_p0, FILTER_SANITIZE_STRING);
   //GESTIOJNE DEI MisfireInstruction
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
   //file position//
   if (isset($_REQUEST['file_position']) && $_REQUEST['file_position'] !=""){
   $file_position = mysqli_real_escape_string($connessione_al_server,$_REQUEST['file_position']);
   }else{
	$file_position = "";   
   }
   
   
   $NextJob_p0 = $_REQUEST['NextJobText']; 
   $NextJob_p = filter_var($NextJob_p0, FILTER_SANITIZE_STRING);
   //
   //
   
   if (isset($_REQUEST['ProcParameters'])){
   $ProcessParameter_p = $_REQUEST['ProcParameters']; 
   //$ProcessParameter_p = filter_var($ProcessParameter_p0, FILTER_SANITIZE_STRING);
   }else{
	  $ProcessParameter_p = '{}'; 
   }

   
   //VALORE DA SOSTITUIRE//
   //$ProcessParameter_p=str_replace ('":',' ":',$ProcessParameter_p);
   $ProcessParameter_p=str_replace ('","-','"},{"-',$ProcessParameter_p);
	$ProcessParameter_p=str_replace ('","','"},{"',$ProcessParameter_p);
   //
   $ProcessParameter_p=str_replace ('"','\"',$ProcessParameter_p);
   $ProcessParameter_p=str_replace ('/','\\\/',$ProcessParameter_p);
   
   
   
   
   //echo $ProcessParameter_p.'<br><br>';
   $JobConstraint_p = '';
   
   
   if(isset($_REQUEST['sc_address']))
   {
	   $indirizzo0 = $_REQUEST['sc_address'];
	   $indirizzo = filter_var($indirizzo0, FILTER_SANITIZE_STRING);
   }
   else
   {
	   //VALORE DI DEFAULT SI PUò mettere in config
	  $ip_default= explode(":", $ip_SCE);
	  $indirizzo = $ip_default[0];
   }
   

   $jsonSce = [];
   //$jsonSce['startAt'] = date_create($start_p)->getTimestamp();
   //$jsonSce['endAt'] = date_create($end_p)->getTimestamp();
   $jsonSce['withJobIdentityNameGroup'] = [$name_p, $group_p];
   $jsonSce['jobClass'] = 'RESTJob';
   //$jsonSce['jobClass'] = $type_p;
   $jsonSce['withDescription'] = $descrizione_trig_p;
   $jsonSce['withJobDescription'] = $desc_p;
   $jsonSce['withIdentityNameGroup'] = [$nome_trig_p, $gruppo_trig_p];
   $jsonSce['id'] = "scheduleJob";
   $jsonSce['withPriority'] = $priority_p;
   $jsonSce['repeatForever'] = true;
   $jsonSce['storeDurably'] = $store_p1;
   $jsonSce['requestRecovery'] = $recovery_p1;
   $jsonSce['withIntervalInSeconds'] = $interval_trig_p; 
   $jsonSce['misfire'] = $misfire_p;
   $jsonSce['notificationEmail'] = $email_p;
   $jsonSce['restUrl'] = $url_p;
   $jobDataMap = [];
   $jobDataMap['#url'] = $url_p;
   $jobDataMap['#isNonConcurrent'] = $conc_p1;
   $jsonSce['jobDataMap'] = $jobDataMap;
   $jsonSce['jobConstraints']= $JobConstraint_p;
   $jsonSce['nextJobs'] = $NextJob_p;
   $jsonSce['processParameters'] = $ProcessParameter_p;
   
   ///
   if(isset($_REQUEST['next_job_n1'])&& $_REQUEST['next_job_n1'] != ""){
   $next_j_cond0=$_REQUEST['par_next_job_cond1'];
   $next_j_cond = filter_var($next_j_cond0, FILTER_SANITIZE_STRING);
   //
   $next_j_res0=$_REQUEST['next_job_result1'];
   $next_j_res = filter_var($next_j_res0, FILTER_SANITIZE_STRING);
   //
   $next_job_n10=$_REQUEST['next_job_n1'];
   $next_job_n1 = filter_var($next_job_n10, FILTER_SANITIZE_STRING);
   //
   $next_job_g10=$_REQUEST['next_job_g1'];
   $next_job_g1 = filter_var($next_job_g10, FILTER_SANITIZE_STRING);
   //
   $next_job_data='"#nextJobs":"[{\"operator\":\"'.$next_j_cond.'\",\"result\":\"'.$next_j_res.'\",\"jobName\":\"'.$next_job_n1.'\",\"jobGroup\":\"'.$next_job_g1.'\"}]",';
   }else{
	   $next_j_cond="";
   $next_j_res="";
   $next_job_n1="";
   $next_job_g1=""; 
   $next_job_data="";
   }
   ////
    ///
	if(isset($_REQUEST['job_cons_k1'])){
    $job_cons_k10=$_REQUEST['job_cons_k1'];
	$job_cons_k1 = filter_var($job_cons_k10, FILTER_SANITIZE_STRING);
	//
	} else {
		$job_cons_k1="";
	}
	if(isset($_REQUEST['job_cons_cond1'])){
    $job_cons_cond10=$_REQUEST['job_cons_cond1'];
	$job_cons_cond1= filter_var($job_cons_cond10, FILTER_SANITIZE_STRING);
	//
	}else{
		$job_cons_cond1="";
	}
	if(isset($_REQUEST['job_cons_v1'])&& $_REQUEST['job_cons_v1'] != ""){
    $job_cons_v10=$_REQUEST['job_cons_v1'];
	$job_cons_v1= filter_var($job_cons_v10, FILTER_SANITIZE_STRING);
	//
	$JobConstraint_p = ',"#jobConstraints":"[{\"systemParameterName\":\"'.$job_cons_k1.'\",\"operator\":\"'.$job_cons_cond1.'\",\"value\":\"'.$job_cons_v1.'\"}]"';
	}else{
	$job_cons_v1="";	
	$JobConstraint_p = "";
	}
	//
	
   ///// CORRETTO/////////////////////////////////////////////////////
	$data = '?json=' . urlencode('{"startAt":"'.$start_p_sec.'","endAt":"'.$end_p_sec.'","withJobIdentityNameGroup":["'.$name_p.'","'.$group_p.'"],"jobClass":"'.$type_p.'","withDescription":"'.$descrizione_trig_p.'","withJobDescription":"'.$desc_p.'","withIdentityNameGroup":["'.$nome_trig_p.'","'.$gruppo_trig_p.'"],"withPriority":"'.$priority_p.'","repeatForever":"true","storeDurably":"'.$store_p1.'","requestRecovery":"'.$recovery_p1.'","withIntervalInSeconds":"'.$interval_trig_p.'","id":"scheduleJob","'.$resultMisfire.'":"","restUrl":"'.$url_p.'","jobDataMap":{"#notificationEmail":"'.$email_p.'","#url":"'.$url_p.'","#isNonConcurrent":"'.$conc_p1.'","#nextJobs":"[{\"operator\":\"'.$next_j_cond.'\",\"result\":\"'.$next_j_res.'\",\"jobName\":\"'.$next_job_n1.'\",\"jobGroup\":\"'.$next_job_g1.'\"}]","#processParameters":"[{\"processPath\":\"'.$path_p.'\"},'.$ProcessParameter_p.']","#jobConstraints":"[{\"systemParameterName\":\"'.$job_cons_k1.'\",\"operator\":\"'.$job_cons_cond1.'\",\"value\":\"'.$job_cons_v1.'\"}]"}}');
   //
   //[PARAMETRI NASCOSTI]//
   //
   $data = '?json=' . urlencode('{"startAt":"'.$start_p_sec.'",'.$end_p_sec.'"withJobIdentityNameGroup":["'.$name_p.'","'.$group_p.'"],"jobClass":"'.$type_p.'","withDescription":"'.$descrizione_trig_p.'","withJobDescription":"'.$desc_p.'","withIdentityNameGroup":["'.$nome_trig_p.'","'.$gruppo_trig_p.'"],"withPriority":"'.$priority_p.'","repeatForever":"true","storeDurably":"'.$store_p1.'","requestRecovery":"'.$recovery_p1.'","withIntervalInSeconds":"'.$interval_trig_p.'","id":"scheduleJob","'.$resultMisfire.'":"","restUrl":"'.$url_p.'","jobDataMap":{'.$mail_data.''.$url_data.'"#isNonConcurrent":"'.$conc_p1.'",'.$next_job_data.''.$DataMap_p.'"#processParameters":"[{\"processPath\":\"'.$path_p.'\"},'.$ProcessParameter_p.']"'.$JobConstraint_p.'}}');
   //
   $apiUrl = 'http://'.$indirizzo.':8080/SmartCloudEngine/index.jsp';

	//$url = $url.$data;
	$apiUrl = $apiUrl.$data;

	echo $apiUrl;
	
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/json\r\n",
			'method'  => 'POST',
		)
	);

	$context  = stream_context_create($options);
	$result = file_get_contents($apiUrl, false, $context);
	
	
	//fwrite($file, print_r($result, true) . "\n");
	//fwrite($file, print_r($http_response_header, true));
	
	if(strpos($http_response_header[0], '200') !== false)
	{
		
		$query="INSERT INTO `processes` (`Id`, `Process_name`, `Process_group`, `job_type`, `Start_time`, `End_time`, `Time_interval`, `Status`, `Process_Type`, `Creation_date`, `non_concurrent`, `StoreDurably`, `RequestRecovery`, `Process_description`, `url`, `process_path`, `MisfireInstruction`, `Email`, `id_disces`, `trigger_name`, `trigger_group`, `trigger_description`, `priority`, `repeat_count`, `time_out`, `dataMap`, `nextJob`, `JobConstraint`, `ProcessParameter`, `file_position`) VALUES (NULL, '".$name_p."', '".$group_p."', '".$id_p."', '".$start_p."', '".$end_p."', '".$interval_trig_p."', 'CREATED', '".$type_p."', '".$creation_date_p."', '".$conc_p."', '".$store_p."', '".$recovery_p."', '".$desc_p."', '".$url_p."', '".$path_p."', '".$misfire_p."', '".$email_p."', '".$indirizzo."', '".$nome_trig_p."', '".$gruppo_trig_p."', '".$descrizione_trig_p."', '".$priority_p."', '".$repeat_trig_p."', '".$time_out_p."', '".$DataMap_p."', '".$NextJob_p."', '".$JobConstraint_p."', '".$ProcessParameter_p."', '".$file_position."')"; 
	    $query_process = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
	   
	   //CARICAMENTO ARCHIVIO -- Trovare un modo di ottenere l'ide del processo nuovo.
	   
	   $sql = "SELECT MAX(`Id`) FROM `processes`";
	   $result3 = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
	   $id_proc = $result3->fetch_assoc();
	   echo (var_dump($id_proc));
	   
	   //$id_proc = $result3[0];
	   
	   $query2="INSERT INTO `process_archive`(`Id`,`Activity_date`,`Process_id`,`Process_name`,`Process_group`,`Description_activity`)VALUES(NULL,'".$creation_date_p."','".$id_proc["MAX(`Id`)"]."','".$name_p."','".$group_p."','CREATION')";
	   $query_process2 = mysqli_query($connessione_al_server,$query2) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
	   
	  //header("location:job_type.php?message=ok");
							if (isset($_REQUEST['showFrame'])){
					if ($_REQUEST['showFrame'] == 'false'){
						header ("location:job_type.php?showFrame=false");
					}else{
						header ("location:job_type.php?done=0");
					}	
				}else{
					header ("location:job_type.php?done=1");
				}
	  //
	}
	else
	{
		//header("location:job_type.php?message=error");
							if (isset($_REQUEST['showFrame'])){
	if ($_REQUEST['showFrame'] == 'false'){
		header ("location:job_type.php?showFrame=false");
					}else{
						header ("location:job_type.php?done=2");
					}	
				}else{
					header ("location:job_type.php?done=3");
				}
		
		//
	}
}else{
	header ("location:page.php");
}	


?>