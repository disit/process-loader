<?php
include("config.php");
include("curl.php");
if (isset ($_SESSION['username'])&& isset($_SESSION['role'])){  

	//$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	$link = mysqli_connect($host, $username, $password);
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname);

	$id0 = mysqli_real_escape_string($link, $_POST['id']);
	$id = filter_var($id0, FILTER_SANITIZE_STRING);
	//
	$user_vecchio0 = mysqli_real_escape_string($link, $_POST['user_old']);
	$user_vecchio = filter_var($user_vecchio0, FILTER_SANITIZE_STRING);
	//
	$user_nuovo0 = mysqli_real_escape_string($link,$_POST['user_new']);
	$user_nuovo = filter_var($user_nuovo0, FILTER_SANITIZE_STRING);
	//
	$creation_date0 = mysqli_real_escape_string($link, $_POST['date']);
	$creation_date = filter_var($creation_date0, FILTER_SANITIZE_STRING);
	//
	$file_name0 = mysqli_real_escape_string($link, $_POST['file_name']);
	$file_name = filter_var($file_name0, FILTER_SANITIZE_STRING);
	//
	$stato0 = mysqli_real_escape_string($link, $_POST['type']);
	$stato = filter_var($stato0 , FILTER_SANITIZE_STRING);
	//
	$data_C_mod4 = $creation_date;
	//
	//
	$nuova_pos='uploads/'.$user_nuovo.'/'.$data_C_mod4.'/';
	$vecchia_pos ='uploads/'.$user_vecchio.'/'.$data_C_mod4.'/';
	$creaCartella=mkdir($nuova_pos, 0777, true);
	$move1 = rename($vecchia_pos,$nuova_pos);
		if($move1){
			$query="UPDATE `uploaded_files` SET `Username` = '".$user_nuovo."' WHERE `Id` = '".$id."'" ;
			$resultUser = mysqli_query($link, $query) or die(mysqli_error($link));
			$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
			url_get($url);
				////ETL////
								if ($stato == 'ETL'){
													$nome = "";
													$zip = new ZipArchive;
													$cartella = 'uploads/'.$user_nuovo.'/'.$data_C_mod4;
													$n1 = explode(".", $file_name);
													$nome = $n1[0];
													$posizione = $cartella.'/'.$nome.'/'.$file_name;											
											if ($zip->open($posizione) === TRUE){
													opendir($repository_destination);
													$zip->extractTo($repository_destination.$user_nuovo.'/'.$data_C_mod4.'/');
													$zip->close(); 
												}
										///MODIFICA DEI JOB TYPE///
										$vecchio_file_pos = $user_vecchio.'/'.$data_C_mod4.'/'.$nome;
										$query_job_type = "SELECT * FROM processloader_db.job_type WHERE file_position ='".$vecchio_file_pos."'";
										$resultjobType = mysqli_query($link, $query_job_type) or die('Error celect job_type');
										$jb_list = array();
										$num_rows = $resultjobType->num_rows;
											if ($resultjobType ->num_rows > 0) {
												while($row = mysqli_fetch_assoc($resultjobType)){
													array_push($jb_list, $row);
												}
											
											//echo ($num_rows);
											$nuovo_file_pos = $user_nuovo.'/'.$data_C_mod4.'/'.$file_name;
											for ($i = 0; $i <= $num_rows; $i++){
												$old_process_paramter=$jb_list[$i]['ProcessParameter'];
												$old_file_position=$jb_list[$i]['file_position'];
												$Id=$jb_list[$i]['Id'];
												$new_process_paramter = str_replace($user_vecchio,$user_nuovo,$old_process_paramter);
												$new_file_position = str_replace($user_vecchio,$user_nuovo,$old_file_position);
												$query_nuova_file_pos = "UPDATE processloader_db.job_type SET file_position = '".$new_file_position."', ProcessParameter = '".$new_process_paramter."' WHERE Id = '".$Id."'";
												$result_nuobo_file_pos = mysqli_query($link, $query_nuova_file_pos) or die('Error select job');
											}
											///fine dei Job Type ///
											///MODIFICA DEL JOB///
										$query_job = "SELECT * FROM processloader_db.processes WHERE file_position ='".$vecchio_file_pos."'";
										$resultjob = mysqli_query($link, $query_job) or die('Select Job');
										$proc_list = array();
										$num_rows2 = $resultjob->num_rows;
											if ($resultjob ->num_rows > 0) {
												while($row = mysqli_fetch_assoc($resultjob)){
													array_push($proc_list, $row);
													}
												
											for ($j = 0; $j <= $num_rows2; $j++){
												$old_process_paramter=$proc_list[$j]['ProcessParameter'];
												$old_file_position=$proc_list[$j]['file_position'];
												$Id=$proc_list[$j]['Id'];
												$process_name=$proc_list[$j]['Process_name'];
												$process_group=$proc_list[$j]['Process_group'];
												$new_process_paramter = str_replace($user_vecchio,$user_nuovo,$old_process_paramter);
												$new_file_position = str_replace($user_vecchio,$user_nuovo,$old_file_position);
												$query_nuova_file_pos2 = "UPDATE processloader_db.processes SET file_position = '".$new_file_position."', ProcessParameter = '".$new_process_paramter."' WHERE Id = '".$Id."'";
												$result_nuobo_file_pos2 = mysqli_query($link, $query_nuova_file_pos2) or die('Error update jobs');
													///FINE MODIFICA DEL JOB///
													//USO DELLE API///
													$hostQ = $proc_list[$j]['id_disces'];
													$usernameQ = $username;
													$passwordQ = $SCE_password_database;
													$dbnameQ = $SCE_dbname;
													echo ($hostQ);
													echo($passwordQ);
													echo ($dbnameQ);
													$linkQ = mysqli_connect($hostQ, $usernameQ, $passwordQ);
													mysqli_set_charset($linkQ, 'utf8');
													mysqli_select_db($linkQ, $dbnameQ);
													$query_det="SELECT * FROM quartz.QRTZ_JOB_DETAILS WHERE JOB_NAME='".$process_name."' AND JOB_GROUP='".$process_group."'";
													$result_det = mysqli_query($linkQ, $query_det) or die(mysqli_error('Error, insert query failed to SCE'));
													$detail_list = array();
													$num_rows3 = $result_det->num_rows;
														if ($result_det->num_rows > 0) {
															while ($row = mysqli_fetch_array($result_det)) {
																array_push($detail_list, $row);
																}
																$blob_det = $detail_list[0]['JOB_DATA'];
																$blob_det_nuovo0 =  str_replace($user_vecchio,$user_nuovo,$blob_det);
																$blob_det_nuovo1 =  str_replace(':"','\:"',$blob_det_nuovo0);
																$blob_det_nuovo2 =  str_replace('#processParameters=','\#processParameters=',$blob_det_nuovo1);
																$blob_det_nuovo3 =  str_replace('#isNonConcurrent=','\#isNonConcurrent=',$blob_det_nuovo2);
																$blob_det_nuovo4 =  str_replace('-param:processName','-param\:processName',$blob_det_nuovo3);
																$blob_det_nuovo =  str_replace('\/','\\/',$blob_det_nuovo4);
																$modify_job = "UPDATE quartz.QRTZ_JOB_DETAILS SET JOB_DATA='".$blob_det_nuovo."' WHERE JOB_NAME='".$process_name."' AND JOB_GROUP='".$process_group."'";
																$result_job2 = mysqli_query($linkQ, $modify_job) or die('Error SCE update');
															//}
														}
														mysqli_close($linkQ);
																if (isset($_REQUEST['showFrame'])){
																	if ($_REQUEST['showFrame'] == 'false'){
																			header ("location:page.php?showFrame=false&modify_ownership=ok");
																	}else{
																			header ("location:page.php?modify_ownership=ok");
																			}	
																		}else{
																			header ("location:page.php?modify_ownership=ok");
																		}								
													//////////////
													}
											//fine del caso in cui ci sono i job
											}
											//
										//fine del caso in cui ci sono i job_type
										}
										//
										}
							///Fine ETl ///
							mysqli_close($link);				
								if (isset($_REQUEST['showFrame'])){
									if ($_REQUEST['showFrame'] == 'false'){
											header ("location:page.php?showFrame=false&modify_ownership=ok");
									}else{
											header ("location:page.php?modify_ownership=ok");
											}	
										}else{
											header ("location:page.php?modify_ownership=ok");
										}	
		}else{
			if (isset($_REQUEST['showFrame'])){
			if ($_REQUEST['showFrame'] == 'false'){
				header ("location:page.php?message=error&showFrame=false");
			}else{
				header ("location:page.php?message=error");
			}	
			   }else{
				header ("location:page.php?message=error");
			   }
			}
}else{
	header ("location:page.php");
}
   ?>
