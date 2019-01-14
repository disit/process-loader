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
   


header("Access-Control-Allow-Origin: *\r\n");
include('../config.php'); 
include('../curl.php');

   $link = mysqli_connect($host, $username, $password);
   mysqli_select_db($link, $dbname);
   	$response = [];
	
if($link == false){
    //try to reconnect
	$response['result'] = 'KO';
	$response['code'] = 500;
	$response['message'] = 'DB Connection error';
	mysqli_close($link);
    echo json_encode($response);
}else{


if(isset($_POST)){
	/////////////////////INSERIRE L'UPLOAD NUOVO //////
	if(isset($_POST['request'])){
							// prendo la request in json decodificata
		//$request = json_decode(file_get_contents('php://input'));
		$request = json_decode($_POST['request']);
		///////////////////////////////////////
								//ACCESS_TOKEN
									$url_accessToken = $access_token_userinfo;
									$token=$request->user;	
									if ($request->user){
									$token_auth= "Authorization: Bearer ".$token;
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, $url_accessToken);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

									$headers = array();
									$headers[] = $token_auth;
									curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
									$result = curl_exec($ch);
									if (curl_errno($ch)) {
										echo 'Error:' . curl_error($ch);
									}
									curl_close ($ch);
									$json = (array) json_decode($result);
									//$userToken = $json['preferred_username'];
									$userToken = $json['username'];
									//echo ($userToken);
									if ($userToken ==""){
										 //try to reconnect
										$response['result'] = 'KO';
										$response['code'] = 500;
										$response['message'] = 'Error: Not Valid Token';
										mysqli_close($link);
										echo json_encode($response);
										exit;
									}
									//
									}else{
										$userToken ="";
										 //try to reconnect
										$response['result'] = 'KO';
										$response['code'] = 400;
										$response['message'] = 'Error: Not Valid Token';
										mysqli_close($link);
										echo json_encode($response);
										exit;
									}
		///
		/////////////////////////////
		if($request){//fai tutto, il json e quindi l'array sono ben formati 
			//if(is_array($json)){
			$app_type = mysqli_real_escape_string($link, $request->resource_type);
			
			if( $app_type!="IoTApp" && $app_type!="IoTBlocks" && $app_type!="DevDash" && $app_type!="AMMA"  && $app_type!="ResDash" &&  $app_type!="MicroService" && $app_type!="ETL" && $app_type!="R" && $app_type!="Java"){	
				$response['result'] = 'KO';
				$response['code'] = 401;
				$response['message'] = 'file type not recognised';
				
			}
			else
			{
				if($app_type=="IoTApp"){
							$img='imgUploaded/default_images/IoTApp.png';
						if(isset($request->name) && isset($request->sub_nature) && isset($request->nature) && isset($request->licence) && isset($request->description) && isset($request->user) && isset($request->data) && $request->name!="" && $request->nature!="" && $request->sub_nature!="" && $request->licence!="" && $request->user!="" && $request->description!="" && $request->data!=""  )
						{	
							$name = mysqli_real_escape_string($link, $request->name);
							$sub_nature = mysqli_real_escape_string($link, $request->sub_nature);
							$nature = mysqli_real_escape_string($link, $request->nature);
							
							$licence = mysqli_real_escape_string($link, $request->licence);
							$description = mysqli_real_escape_string($link, $request->description);
							//$user = mysqli_real_escape_string($link, $request->user);       // TODO aggiungere sicurezza per l'user
							$user = mysqli_real_escape_string($link, $userToken);
							/////
							$data =  $request->data;
							$file_type = $app_type;

							$date=date('Y-m-d H:i:s');
							$data1=date('Y-m-d');
							$time2=date('H');
							$time3=date('Y-m-d-H-i-s'); 
							$this_dir = dirname(__FILE__);
							$parent_dir = realpath($this_dir . '/..');


							$cartella = $parent_dir .  '/uploads/'.$user.'/'.$time3.'/';
							$creaCartella=mkdir($cartella, 0777, true);
							$target_path = $cartella . '/'.$name.'.json';
							//$json = $_GET['data'];
							// prendo il json dall'url che contiene i dati
							$fp = fopen($target_path, 'w') or die("Unable to open file!");;
							fwrite($fp, json_encode($data));
							fclose($fp);
							if(!isset($request->format))
							{

								$format=null;
								$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Resource_input,Protocol,Img)VALUES (NULL,'".$name.".json','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$sub_nature."','ToBeDefined','".$img."')";

							}
							else
							{
								$format=$request->format;
								$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Format,Resource_input,Protocol,Img)VALUES (NULL,'".$name.".json','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$format."','".$sub_nature."','ToBeDefined','".$img."')";

							}
							//aggiungo riga al DB con i metadati del nuovo file

							$query_caricamento = mysqli_query($connessione_al_server,$query) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
							$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
							url_get($url);
							if($query_caricamento)
							{
								mysqli_commit($link);
								$response['result'] = 'Ok';
								$response['code'] = 200;


							}
							else
							{
								mysqli_rollback($link);
								$response['result'] = 'Ko';
								$response['message'] = 'Database problem';
								$response['code'] = 501;
						
							}
						}
						else
						{
							$response['result'] = 'KO';
							$response['code'] = 402;
							$response['message'] = 'Insert NOT done due to lack of mandatory data';
						}
						
					
				}
				elseif($app_type=="ResDash" || $app_type =="DevDash" || $app_type=="AMMA")		
				{
						if($app_type=="ResDash"){ $img='imgUploaded/default_images/ResDash.png';}
						if($app_type =="DevDash"){$img='imgUploaded/default_images/DevDash.png';}
						if($app_type=="AMMA"){$img='imgUploaded/default_images/AMMA.png';}
						if(isset($request->name) && isset($request->sub_nature) && isset($request->nature) && isset($request->licence) && isset($request->description) && isset($request->user) && isset($request->data) && $request->name!="" && $request->nature!="" && $request->sub_nature!="" && $request->licence!="" && $request->user!="" && $request->description!="" && $request->data!="")
						{	
							$name = mysqli_real_escape_string($link, $request->name);
							$sub_nature = mysqli_real_escape_string($link, $request->sub_nature);
							$nature = mysqli_real_escape_string($link, $request->nature);
							
							$licence = mysqli_real_escape_string($link, $request->licence);
							$description = mysqli_real_escape_string($link, $request->description);
							//$user = mysqli_real_escape_string($link, $request->user);       // TODO aggiungere sicurezza per l'user
							$user=$userToken;
							//
							$data =  $request->data;
							$file_type = $app_type;

							$date=date('Y-m-d H:i:s');
							$data1=date('Y-m-d');
							$time2=date('H');
							$time3=date('Y-m-d-H-i-s'); 
							$this_dir = dirname(__FILE__);
							$parent_dir = realpath($this_dir . '/..');


							$cartella = $parent_dir .  '/uploads/'.$user.'/'.$time3.'/';
							$creaCartella=mkdir($cartella, 0777, true);
							$target_path = $cartella . '/'.$name.'.json';
							//$json = $_GET['data'];
							// prendo il json dall'url che contiene i dati
							$fp = fopen($target_path, 'w') or die("Unable to open file!");;
							fwrite($fp, json_encode($data));
							fclose($fp);
							
							if(!isset($request->format))
							{

								$format=null;
								$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Resource_input,Protocol,Img)VALUES (NULL,'".$name.".json','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$sub_nature."','ToBeDefined','".$img."')";

							}
							else
							{
								$format=$request->format;
								$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Format,Resource_input,Protocol,Img)VALUES (NULL,'".$name.".json','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$format."','".$sub_nature."','ToBeDefined','".$img."')";

							}
							
							//aggiungo riga al DB con i metadati del nuovo file

							$query_caricamento = mysqli_query($connessione_al_server,$query) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
							$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
							url_get($url);
							if($query_caricamento)
							{
								mysqli_commit($link);
								$response['result'] = 'Ok';
								$response['code'] = 200;


							}
							else
							{
								mysqli_rollback($link);
								$response['result'] = 'Ko';
								$response['message'] = 'Database problem';
								$response['code'] = 501;
						
							}
						}
						else
						{
							$response['result'] = 'KO';
							$response['code'] = 402;
							$response['message'] = 'Insert NOT done due to lack of mandatory data';
						}
						
					
				
				}
				elseif($app_type=="IoTBlocks")	
				{
					$img='imgUploaded/default_images/IoTBlocks.png';
					if(isset($_FILES['resource']))
					{
						if(isset($request->sub_nature) && isset($request->nature) && isset($request->licence) && isset($request->description) && isset($request->user) && $request->nature!="" && $request->sub_nature!="" && $request->licence!="" && $request->user!="" && $request->description!=""  )
						{	
							$percorso = $_FILES['resource']['tmp_name'];
							$nome = $_FILES['resource']['name'];
							$data1=date('Y-m-d');
							$time2=date('H');
							$time3=date('Y-m-d-H-i-s'); 
							//$utente_us= mysqli_real_escape_string($link,$request->user);  
							$utente_us= $userToken;
							$ext = explode(".", $nome);
							$ext = $ext[count($ext)-1];
							if($ext=='zip'){
								$base= basename($nome,".zip");
							}
							elseif($ext=='rar'){
								$base= basename($nome,".zip");
							}
							if ($ext == 'zip' || $ext == 'rar'){
								//$cartella = '../uploads/'.$utente_us.'/'.$time3.'/'.$base.'/';
								$cartella = '../uploads/'.$utente_us.'/'.$time3.'/';
							//}
						/* 	else{
							
							}  */

								$creaCartella=mkdir($cartella, 0777, true);
								$data=date('Y-m-d H:i:s');
						
								if (move_uploaded_file($percorso, $cartella . $nome)){
									//$name = mysqli_real_escape_string($link, $request->name);
									$sub_nature = mysqli_real_escape_string($link, $request->sub_nature);
									$nature = mysqli_real_escape_string($link, $request->nature);
									
									$licence = mysqli_real_escape_string($link, $request->licence);
									$description = mysqli_real_escape_string($link, $request->description);
									$file_type = $app_type;
									$user=$utente_us;
									$name=$nome;
									$date=date('Y-m-d H:i:s');
									if(!isset($request->format))
									{
										$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Resource_input,Protocol,Img)VALUES (NULL,'".$name."','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$sub_nature."','ToBeDefined','".$img."')";

									}
									else
									{
										$format=$request->format;
										$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Format,Resource_input,Protocol,Img)VALUES (NULL,'".$name."','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$format."','".$sub_nature."','ToBeDefined','".$img."')";

									}
									$query_caricamento = mysqli_query($connessione_al_server,$query) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
									$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
									url_get($url);
									if($query_caricamento)
									{
										mysqli_commit($link);
										$response['result'] = 'Ok';
										$response['code'] = 200;


									}
									else
									{
										mysqli_rollback($link);
										$response['result'] = 'Ko';
										$response['message'] = 'Database problem';
										$response['code'] = 501;

									}

								
								
								}
								else{
									$response['result'] = 'KO';
									$response['code'] = 502;
									$response['message'] = 'upload failed';
								}
						}
						else{
								$response['result'] = 'KO';
								$response['code'] = 503;
								$response['message'] = 'file not zip!';
						}
						}
						else
						{
							$response['result'] = 'KO';
							$response['code'] = 402;
							$response['message'] = 'Insert NOT done due to lack of mandatory data';
						}

					

					}
					else{
						$response['result'] = 'KO';
						$response['code'] = 504;
						$response['message'] = 'missing input resource!';
						
					}
				}
				
				elseif(($app_type=="ETL")||($app_type=="R")||($app_type=="Java")){
										if ($app_type=="ETL"){
											$img='imgUploaded/default_images/ETL.png';
											}else{
											$img='imgUploaded/default_images/DataAnalitics.png';
										}
										
										
									if(isset($_FILES['resource']))
									{
										if(isset($request->sub_nature) && isset($request->nature) && isset($request->licence) && isset($request->description) && isset($request->user) && $request->nature!="" && $request->sub_nature!="" && $request->licence!="" && $request->user!="" && $request->description!=""  )
										{	
											$percorso = $_FILES['resource']['tmp_name'];
											$nome = $_FILES['resource']['name'];
											$data1=date('Y-m-d');
											$time2=date('H');
											$time3=date('Y-m-d-H-i-s'); 
											//$utente_us= mysqli_real_escape_string($link,$request->user); 
											$utente_us=$userToken;											
											$ext = explode(".", $nome);
											$ext = $ext[count($ext)-1];
											if($ext=='zip'){
												$base= basename($nome,".zip");
											}
											elseif($ext=='rar'){
												$base= basename($nome,".zip");
											}
											if ($ext == 'zip' || $ext == 'rar'){
												$cartella = '../uploads/'.$utente_us.'/'.$time3.'/'.$base.'/';
												

												$creaCartella=mkdir($cartella, 0777, true);
												$data=date('Y-m-d H:i:s');
										
												if (move_uploaded_file($percorso, $cartella . $nome)){
													//$name = mysqli_real_escape_string($link, $request->name);
													$sub_nature = mysqli_real_escape_string($link, $request->sub_nature);
													$nature = mysqli_real_escape_string($link, $request->nature);
													
													$licence = mysqli_real_escape_string($link, $request->licence);
													$description = mysqli_real_escape_string($link, $request->description);
													$file_type = $app_type;
													$user=$utente_us;
													$name=$nome;
													$date=date('Y-m-d H:i:s');
													if(!isset($request->format))
													{
														$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Resource_input,Protocol,Img)VALUES (NULL,'".$name."','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$sub_nature."','ToBeDefined','".$img."')";

													}
													else
													{
														$format=$request->format;
														$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Format,Resource_input,Protocol,Img)VALUES (NULL,'".$name."','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$format."','".$sub_nature."','ToBeDefined','".$img."')";

													}
													$query_caricamento = mysqli_query($connessione_al_server,$query) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
													$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
													url_get($url);
													if($query_caricamento)
													{
														mysqli_commit($link);
														$response['result'] = 'Ok';
														$response['code'] = 200;


													}
													else
													{
														mysqli_rollback($link);
														$response['result'] = 'Ko';
														$response['message'] = 'Database problem';
														$response['code'] = 501;
													}

												
												
												}
												else{
													$response['result'] = 'KO';
													$response['code'] = 502;
													$response['message'] = 'upload failed';
												}
										}
										else{
												$response['result'] = 'KO';
												$response['code'] = 503;
												$response['message'] = 'file not zip!';
										}
										}
										else
										{
											$response['result'] = 'KO';
											$response['code'] = 402;
											$response['message'] = 'Insert NOT done due to lack of mandatory data';
										}

									

									}
									else{
										$response['result'] = 'KO';
										$response['code'] = 504;
										$response['message'] = 'missing input resource!';
										
									}
				}
				
				else{
						$response['result'] = 'KO';
						$response['code'] = 500;
						$response['message'] = 'file type not yet supported';
				}
				
				
				

			}
		}
		else{
			$response['result'] = 'KO';
			$response['code'] = 505;
			$response['message'] = 'cannot decode json';
			
		} 
		
		
	}else{
		//se non c'è il request//
	
	///////////////////////////

// prendo la request in json decodificata
		$request = json_decode(file_get_contents('php://input'));
		///QUI VA MESSO IL CURL//
					//ACCESS_TOKEN
									$url_accessToken = $access_token_userinfo;
									$token=$request->user;	
									if ($request->user){
									$token_auth= "Authorization: Bearer ".$token;
									$ch = curl_init();
									//
									//
									curl_setopt($ch,CURLOPT_URL, $url_accessToken);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

									$headers = array();
									$headers[] = $token_auth;
									curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
									$result = curl_exec($ch);
									if (curl_errno($ch)) {
										echo 'Error:' . curl_error($ch);
									}
									curl_close ($ch);
									$json = (array) json_decode($result);
									//$userToken = $json['preferred_username'];
									$userToken = $json['username'];
									//echo ($userToken);
									if ($userToken ==""){
										 //try to reconnect
										$response['result'] = 'KO';
										$response['code'] = 500;
										$response['message'] = 'Error: Not Valid Token';
										mysqli_close($link);
										echo json_encode($response);
										exit;
									}
									//
									}else{
										$userToken ="";
									}
		///
		if($request){
			//fai tutto, il json e quindi l'array sono ben formati 
			//if(is_array($json)){
			$app_type = mysqli_real_escape_string($link, $request->resource_type);            

			if($app_type=="IoTBlocks" || $app_type=="ETL"){				
				$response['result'] = 'KO';
				$response['code'] = 400;
				$response['message'] = 'file type not recognised';
				
			}
			else
			{
				if($app_type=="IoTApp"){
					$img='imgUploaded/default_images/IoTApp.png';

						if(isset($request->name) && isset($request->sub_nature) && isset($request->nature) && isset($request->licence) && isset($request->description) && isset($request->user) && isset($request->data) &&(($request->user)!=""))
						{	
							$name = mysqli_real_escape_string($link, $request->name);
							$sub_nature = mysqli_real_escape_string($link, $request->sub_nature);
							$nature = mysqli_real_escape_string($link, $request->nature);
							
							$licence = mysqli_real_escape_string($link, $request->licence);
							$description = mysqli_real_escape_string($link, $request->description);
							//$user = mysqli_real_escape_string($link, $request->user);       // TODO aggiungere sicurezza per l'user
							$user = $userToken;
							//
							$data =  $request->data;
							$file_type = $app_type;
							if(!isset($request->format))
							{

								$format=null;
							}
							else
							{
								$format=$request->format;
							}
							$date=date('Y-m-d H:i:s');
							$data1=date('Y-m-d');
							$time2=date('H');
							$time3=date('Y-m-d-H-i-s'); 
							$this_dir = dirname(__FILE__);
							$parent_dir = realpath($this_dir . '/..');


							$cartella = $parent_dir .  '/uploads/'.$user.'/'.$time3.'/';
							$creaCartella=mkdir($cartella, 0777, true);
							$target_path = $cartella . '/'.$name.'.json';
							//$json = $_GET['data'];
							$fp = fopen($target_path, 'w') or die("Unable to open file!");;
							fwrite($fp, json_encode($data));
							fclose($fp);
							

							$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Format,Resource_input,Protocol,Img)VALUES (NULL,'".$name.".json','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$format."','".$sub_nature."','ToBeDefined','".$img."')";
							$query_caricamento = mysqli_query($connessione_al_server,$query) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
							$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
							url_get($url);
							if($query_caricamento)
							{
								mysqli_commit($link);
								$response['result'] = 'Ok';
								$response['code'] = 200;


							}
							else
							{
								mysqli_rollback($link);
								$response['result'] = 'Ko';
								$response['message'] = 'Database problem';
								$response['code'] = 500;
						//header('Access-Control-Allow-Origin: *');
						//http_response_code(500);
						
							}
						}
						else
						{
							$response['result'] = 'KO';
							$response['code'] = 400;
							$response['message'] = 'Insert NOT done due to incorrect json formatting or lack of mandatory data';
						}
						
						
						
					
				} elseif(($app_type=="ResDash") || ($app_type =="DevDash") || ($app_type=="AMMA")){
							if($app_type=="ResDash"){ $img='imgUploaded/default_images/ResDash.png';}
							if($app_type =="DevDash"){$img='imgUploaded/default_images/DevDash.png';}
							if($app_type=="AMMA"){$img='imgUploaded/default_images/AMMA.png';}
							///////////
							if(isset($request->name) && isset($request->sub_nature) && isset($request->nature) && isset($request->licence) && isset($request->description) && isset($request->user) && isset($request->data) &&(($request->user)!=""))
									{	
										$name = mysqli_real_escape_string($link, $request->name);
										$sub_nature = mysqli_real_escape_string($link, $request->sub_nature);
										$nature = mysqli_real_escape_string($link, $request->nature);
										
										$licence = mysqli_real_escape_string($link, $request->licence);
										$description = mysqli_real_escape_string($link, $request->description);
										//$user = mysqli_real_escape_string($link, $request->user);       // TODO aggiungere sicurezza per l'user
										$user = $userToken;
										//
										$data =  $request->data;
										$file_type = $app_type;
										if(!isset($request->format))
										{

											$format=null;
										}
										else
										{
											$format=$request->format;
										}
										$date=date('Y-m-d H:i:s');
										$data1=date('Y-m-d');
										$time2=date('H');
										$time3=date('Y-m-d-H-i-s'); 
										$this_dir = dirname(__FILE__);
										$parent_dir = realpath($this_dir . '/..');


										$cartella = $parent_dir .  '/uploads/'.$user.'/'.$time3.'/';
										$creaCartella=mkdir($cartella, 0777, true);
										$target_path = $cartella . '/'.$name.'.json';
										//$json = $_GET['data'];
										$fp = fopen($target_path, 'w') or die("Unable to open file!");;
										fwrite($fp, json_encode($data));
										fclose($fp);
										

										$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Format,Resource_input,Protocol,Img)VALUES (NULL,'".$name.".json','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$format."','".$sub_nature."','ToBeDefined','".$img."')";
										$query_caricamento = mysqli_query($connessione_al_server,$query) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
										$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
										url_get($url);
										if($query_caricamento)
										{
											mysqli_commit($link);
											$response['result'] = 'Ok';
											$response['code'] = 200;


										}
										else
										{
											mysqli_rollback($link);
											$response['result'] = 'Ko';
											$response['message'] = 'Database problem';
											$response['code'] = 500;
									//header('Access-Control-Allow-Origin: *');
									//http_response_code(500);
									
										}
									}
									else
									{
										$response['result'] = 'KO';
										$response['code'] = 400;
										$response['message'] = 'Insert NOT done due to incorrect json formatting or lack of mandatory data';
									}
							

							//////////////
				}
				elseif($app_type=="MicroService")		
				{
					$img='imgUploaded/default_images/MicroServices.png';
					//
					$decode_param = $request->parameters;
					if (is_object($decode_param[0])){
						$response['result'] = 'KO';
										$response['code'] = 400;
										$response['message'] = 'Parameters Not acceptable';
										mysqli_close($link);
										echo json_encode($response);
										exit;
					}
						//echo('NON OGGETTO');
					//}
					//var_dump($decode_param[0]);
					
						if(isset($request->sub_nature) && isset($request->nature) && isset($request->licence) && isset($request->description) && isset($request->user) && isset($request->url) && isset($request->method) && isset($request->help) && isset($request->parameters) && $request->url!="" && $request->nature!="" && $request->sub_nature!="" && $request->licence!="" && $request->user!="" && $request->method!="" && $request->help!="" && $request->parameters!="" && is_array($request->parameters))
						{	
							//$percorso = $_FILES['resource']['tmp_name'];
							//$nome = $_FILES['resource']['name'];
							$data1=date('Y-m-d');
							$time2=date('H');
							$time3=date('Y-m-d-H-i-s'); 
							//$utente_us= mysqli_real_escape_string($link,$request->user);  
							
							$utente_us=$userToken;
							//$ext = explode(".", $nome);
							//$ext = $ext[count($ext)-1];

								//$cartella = '../uploads/'.$utente_us.'/'.$time3.'/'.$base.'/';
								$cartella = '../uploads/'.$utente_us.'/'.$time3.'/';
							//}
						/* 	else{
							
							}  */

								$creaCartella=mkdir($cartella, 0777, true);
								$data=date('Y-m-d H:i:s');
						
									$sub_nature = mysqli_real_escape_string($link, $request->sub_nature);
									$nature = mysqli_real_escape_string($link, $request->nature);
									$title = mysqli_real_escape_string($link, $request->name);
									$licence = mysqli_real_escape_string($link, $request->licence);
									$description = mysqli_real_escape_string($link, $request->description);
									$file_type = $app_type;
									$url=mysqli_real_escape_string($link,$request->url);
									$method=mysqli_real_escape_string($link,$request->method);
									$help=mysqli_real_escape_string($link,$request->help);
									$authentication  = mysqli_real_escape_string($link,$request->authentication);
									$user=$utente_us;
									$parameters_request = $request->parameters;
									//
									if (is_object($parameters_request)){
										$response['result'] = 'KO';
										$response['code'] = 400;
										$response['message'] = 'Parameters Not acceptable';
										mysqli_close($link);
										echo json_encode($response);
										exit;
									}
									

									//$name=$nome;
									$date=date('Y-m-d H:i:s');
									///CREAZIONE MICROSERVIZIO
									$auth_check = isset($authentication) ? $authentication : 'no';
													//$fileLic = "Public";
													$fileform = "json";
													$fileacc = "http";
													///
													if (isset($parameters_request)){
														$array_parametri = $parameters_request;												
													}else{
														$response['result'] = 'KO';
															$response['code'] = 400;
															$response['message'] = 'Parameters Not acceptable';
															mysqli_close($link);
															echo json_encode($response);
															exit;
													}
													//
													 if(is_array($array_parametri) && !is_numeric(array_shift(array_keys($array_parametri)))){
													//RETURN TRUE;
															$response['code'] = 400;
															$response['message'] = 'Parameters Not acceptable';
															mysqli_close($link);
															echo json_encode($response);
															exit;
													//
													}
													//
													if (isset($title)){
															$micro_name =$title;
															}else{
															$micro_name = "GenericMicroService";	
														}
													
													$utente = $user;
													//$utente = 'Prova_microservice';
													$cartellaMicro = 'uploads/'.$utente.'/'.$time3.'/';

													//
													if (($method == "GET")||($method =="POST")){
														//ok
													}else{
															$response['result'] = 'KO';
															$response['code'] = 500;
															$response['message'] = 'Method Not acceptable';
															mysqli_close($link);
															echo json_encode($response);
															exit;
													}
													
													//control if array is associative
													
													$key_param = (array_keys($array_parametri) !== range(0, count($array_parametri) - 1));
													if ($key_param > 0){
														$response['result'] = 'KO';
															$response['code'] = 400;
															$response['message'] = 'Parameters Not acceptable';
															mysqli_close($link);
															echo json_encode($response);
															exit;
													}
													
													//$json_a = json_decode($string, true);
													//$parameters = $json_a['parameterList'];
													$parameters = $array_parametri;
													$num = count($parameters);
													//
													$baseMicro= basename($nome,".json");
													//$nome_funzione = preg_replace("[^A-Za-z0-9 ]", "", $micro_name);
													$nome_funzione = preg_replace('/[^A-Za-z0-9]/', '', $micro_name);
													$nomeHTML = $micro_name.".html";
													$myfileHtml = fopen($nomeHTML,"w");
													$color = '#FFFFFF';
													$category = 'UserCreated';
													
													$contentHtml = '<script type="text/javascript"> RED.nodes.registerType("'.$micro_name.'", {category: "'.$category.'", color: "'.$color.'", defaults: { name:{value: ""}';
													//HELP CREATION
													//
													//
													//$help = '<div id="palette_node_service-info" class="palette_node ui-draggable" style="background-color: rgb(255, 255, 255); height: 28px;" onclick="loadContent(service-info.html);"><div class="palette_label" dir="">'.$micro_name.'</div><div class="palette_icon_container"><div class="palette_icon" style="background-image: url(icons/white-globe.png)"></div></div><div class="palette_port palette_port_output" style="top: 9px;"></div><div class="palette_port palette_port_input" style="top: 9px;"></div></div>'.$help;
													//	
													for ($j=0;$j<$num;$j++){
													$contentHtml = $contentHtml . ','.$parameters[$j].':{value:"",required:false}';
													}
													//if checked
													if (($auth_check == 'Yes')||($auth_check == 'yes')){
															$contentHtml = $contentHtml . ',username:{value:"",required:false},password:{value:"",required:false}';
													}
													//
													$contentHtml = $contentHtml.'}, outputs: 1,inputs: 1,outputLabels: ["response"],icon: "white-globe.png",label: function() {return this.name || "'.$micro_name.'"; } });</script>';
													$contentHtml = $contentHtml . '<script type="text/x-red" data-template-name="'.$micro_name.'"><div class="form-row"><label for="node-input-name">Name</label><input type="text" id="node-input-name" placeholder="'.$baseMicro.'"></div>';	
													for ($i=0;$i<$num;$i++){
														$htmlParam = '<div class="form-row"><label for="node-input-'.$parameters[$i].'">'.$parameters[$i].'</label><input type="text" id="node-input-'.$parameters[$i].'" placeholder="'.$parameters[$i].'"></div>';
														$contentHtml = $contentHtml . $htmlParam;
													}
													//If checked
													if (($auth_check == 'Yes')||($auth_check == 'yes')){
														$contentHtml = $contentHtml . '<div class="form-row"><label for="node-input-username">Username</label><input type="text" id="node-input-username" placeholder="Username"></div>';
														$contentHtml = $contentHtml . '<div class="form-row"><label for="node-input-password">Password</label><input type="password" id="node-input-password" placeholder="password"></div>';
													}
													//
													$contentHtml = $contentHtml . '</script><script type="text/x-red" data-help-name="'.$micro_name.'">'.$help.'</script>';
													
													fwrite($myfileHtml, $contentHtml);
													fclose($myfileHtml);
													///// FIne HTML /////
													
													////scrittura Java script///
													$nomeJS = $micro_name.".js";
													$myfileJs = fopen($nomeJS,"w");
													
													$contentjs = 'module.exports = function(RED) {function eventLog(inPayload, outPayload, config, _agent, _motivation, _ipext, _modcom) {var os = require("os"); var ifaces = os.networkInterfaces(); var uri = "http://192.168.1.43/RsyslogAPI/rsyslog.php";var pidlocal = RED.settings.APPID;var iplocal = null;Object.keys(ifaces).forEach(function (ifname) {ifaces[ifname].forEach(function (iface) {if ("IPv4" !== iface.family || iface.internal !== false) { return;}iplocal = iface.address;}); });';
													$contentjs =  $contentjs . ' iplocal = iplocal + ":" + RED.settings.uiPort;var timestamp = new Date().getTime(); var modcom = _modcom;var ipext = _ipext; var payloadsize = JSON.stringify(outPayload).length / 1000;var agent = _agent; var motivation = _motivation; var lang = (inPayload.lang ? inPayload.lang : config.lang); var lat = (inPayload.lat ? inPayload.lat : config.lat); var lon = (inPayload.lon ? inPayload.lon : config.lon);'; 
													$contentjs =  $contentjs . 'var serviceuri = (inPayload.serviceuri ? inPayload.serviceuri : config.serviceuri); var message = (inPayload.message ? inPayload.message : config.message); var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest; var xmlHttp = new XMLHttpRequest(); console.log(encodeURI(uri + "?p=log" + "&pid=" + pidlocal + "&tmstmp=" + timestamp + "&modCom=" + modcom + "&IP_local=" + iplocal + "&IP_ext=" + ipext +"&payloadSize=" + payloadsize + "&agent=" + agent + "&motivation=" + motivation + "&lang=" + lang + "&lat=" + (typeof lat != "undefined" ? lat : 0.0) + "&lon=" + (typeof lon != "undefined" ? lon : 0.0) + "&serviceUri=" + serviceuri + "&message=" + message));';
													$contentjs =  $contentjs . 'xmlHttp.open("'.$method.'", encodeURI(uri + "?p=log" + "&pid=" + pidlocal + "&tmstmp=" + timestamp + "&modCom=" + modcom + "&IP_local=" + iplocal + "&IP_ext=" + ipext +"&payloadSize=" + payloadsize + "&agent=" + agent + "&motivation=" + motivation + "&lang=" + lang + "&lat=" + (typeof lat != "undefined" ? lat : 0.0) + "&lon=" + (typeof lon != "undefined" ? lon : 0.0) + "&serviceUri=" + serviceuri + "&message=" + message), true);  xmlHttp.send(null); }';
													$contentjs =  $contentjs . 'function '.$nome_funzione.'(config) { RED.nodes.createNode(this, config); var node = this;node.on("input", function(msg) {';
													$contentjs = $contentjs . 'var uri = "'.$url.'"; ';
													
													for ($y=0;$y<$num;$y++){
														$contentjs = $contentjs . 'var '.$parameters[$y].' = (msg.payload.'.$parameters[$y].' ? msg.payload.'.$parameters[$y].' : config.'.$parameters[$y].');';
														}
														//if checked
														if (($auth_check == 'Yes')||($auth_check == 'yes')){
															$contentjs = $contentjs .'var username = (msg.payload.username ? msg.payload.username : config.username);';
															$contentjs = $contentjs .'var password = (msg.payload.password ? msg.payload.username : config.password);';
														}
														//
														$contentjs = $contentjs . 'var inPayload = msg.payload; var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;'; 
														if (($auth_check == 'Yes')||($auth_check == 'yes')){
														$contentjs = $contentjs . 'var btoa = require("btoa");';	
														}
														$contentjs = $contentjs .'var xmlHttp = new XMLHttpRequest();console.log(encodeURI(uri + "?'.$parameters[0].'="+'. $parameters[0];
														for ($z=1;$z<$num;$z++){
															$contentjs = $contentjs.'+(typeof '.$parameters[$z].' != "undefined" && '.$parameters[$z].' != "" ? "&'.$parameters[$z].'=" + '.$parameters[$z].' : "")';
														}

														$contentjs = $contentjs . '));xmlHttp.open("'.$method.'", encodeURI(uri + "?'.$parameters[0].'=" + '.$parameters[0];
														for ($x=1;$x<$num;$x++){
														$contentjs = $contentjs.' +(typeof '.$parameters[$x].' != "undefined" && '.$parameters[$x].' != "" ? "&'.$parameters[$x].'=" + '.$parameters[$x].' : "")';
														}

														$contentjs = $contentjs . '), false);'; 
														if (($auth_check == 'Yes')||($auth_check == 'yes')){
														$contentjs = $contentjs . 'xmlHttp.setRequestHeader("Authorization","Basic " + btoa (username + ":" + password));';	
														}
														$contentjs = $contentjs .'xmlHttp.send(null); if (xmlHttp.responseText != "") { try { msg.payload = JSON.parse(xmlHttp.responseText); } catch (e) { msg.payload = xmlHttp.responseText;}} else {msg.payload = JSON.parse("{\"status\": \"There was some problem\"}"); }eventLog(inPayload, msg, config, "Node-Red", "MicroserviceUserCreated", uri, "RX"); node.send(msg);});}RED.nodes.registerType("'.$micro_name.'", '.$nome_funzione.');}';
														
														fwrite($myfileJs, $contentjs);
														fclose($myfileJs);
													//////fine js /////
													/////PACKAGE
													$packageContent = '{"name": "'.$micro_name.'","version": "0.0.1","description": "","dependencies": {"xmlhttprequest": "^1.8.0", "btoa": "1.2.1" },"node-red": {"nodes": {"'.$micro_name.'": "'.$micro_name.'.js"}}}';
													$myfilePackage = fopen("package.json","w");
													fwrite($myfilePackage, $packageContent);
													fclose($myfilePackage);
													/////
													
													///Spostare i files in una cartella e zipparla.
													$zip = new ZipArchive();
													//$nome_download = $baseMicro.".zip";
													$nome_download = $micro_name.".zip";
													$nomeZip = $cartella.$micro_name.".zip";
													if (($zip->open($nomeZip, ZipArchive::CREATE)) === TRUE) {
																$zip->addFile($nomeHTML,$micro_name.'/'.$nomeHTML);
																$zip->addFile($nomeJS,$micro_name.'/'.$nomeJS);
																$zip->addFile('package.json',$micro_name.'/package.json');
																$zip->addFile('xmlhttprequest/README.md',$micro_name.'/node_modules/xmlhttprequest/README.md');
																$zip->addFile('xmlhttprequest/LICENSE',$micro_name.'/node_modules/xmlhttprequest/LICENSE');
																$zip->addFile('xmlhttprequest/package.json',$micro_name.'/node_modules/xmlhttprequest/package.json');
																$zip->addFile('xmlhttprequest/lib/XMLHttpRequest.js',$micro_name.'/node_modules/xmlhttprequest/lib/XMLHttpRequest.js');
																//copiare contenuto di btoa
																if ($auth_check == 'Yes'){
																$zip->addFile('btoa/README.md',$micro_name.'/node_modules/btoa/README.md');
																$zip->addFile('btoa/LICENSE',$micro_name.'/node_modules/btoa/LICENSE');
																$zip->addFile('btoa/package.json',$micro_name.'/node_modules/btoa/package.json');
																$zip->addFile('btoa/LICENSE.DOCS',$micro_name.'/node_modules/btoa/LICENSE.DOCS');
																$zip->addFile('btoa/index.js',$micro_name.'/node_modules/btoa/index.js');
																$zip->addFile('btoa/test.js',$micro_name.'/node_modules/btoa/test.js');
																$zip->addFile('btoa/bin/btoa.js',$micro_name.'/node_modules/btoa/bin/btoa.js');
																}
													}else{
													//$zipM->addFile($nome);
															$response['result'] = 'KO';
															$response['code'] = 400;
															$response['message'] = 'Error during MicroService Creation';
															mysqli_close($link);
															echo json_encode($response);
															exit;
													//echo ('ERROR DURING ZIP CREATION');
													}
													$zip->close();
													//echo ('zip finished');
													//cancella files temporanei
													unlink($nomeHTML);
													unlink($nomeJS);
													unlink('package.json');
													/////
													$fileform = 'json';
													$fileacc = 'http';
													/////////CARICA SU DATABASE//////////////	
									////FINE MICROSERVIZIO
									if(!isset($request->access))
									{

										//$access=null;
										$access = 'http';
									}
									else
									{
										$access=$request->acces;
									}
									if(!isset($request->format))
									{
										$format="json";
										$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Format,Resource_input,Protocol,Method,Url,Help,Img,Html,Js)VALUES (NULL,'".$nome_download."','".$description."','0','".$date."','".$file_type."','OK - ".$date."','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$format."','".$sub_nature."','".$access."','".$method."','".$url."','".$help."','".$img."','".$contentHtml."','".$contentjs."')";

										
									}
									else
									{
										$format=$request->format;
										$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Format,Resource_input,Protocol,Method,Url,Help,Img,Html,Js)VALUES (NULL,'".$nome_download."','".$description."','0','".$date."','".$file_type."','OK - ".$date."','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$format."','".$sub_nature."','".$access."','".$method."','".$url."','".$help."','".$img."','".$contentHtml."','".$contentjs."')";

									}
									$query_caricamento = mysqli_query($connessione_al_server,$query) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
									$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
									url_get($url);
									if($query_caricamento)
									{
										mysqli_commit($link);
										$response['result'] = 'Ok';
										$response['code'] = 200;
										//$response['authentication']= 'MicroService Successfully Uploaded';


									}
									else
									{
										mysqli_rollback($link);
										$response['result'] = 'Ko';
										$response['message'] = 'Database problem';
										$response['code'] = 501;
								
									}

								
							

						}
						else
						{
							$response['result'] = 'KO';
							$response['code'] = 402;
							$response['message'] = 'Insert NOT done due to lack of mandatory data';
						}

					
				}
				else{
					//$response_request = $request;
						$response['result'] = 'KO';
						$response['code'] = 500;
						$response['message'] = 'file type not yet supported';
				}
				
				
				

			}
		}
		else{
			$response['result'] = 'KO';
			$response['code'] = 500;
			$response['message'] = 'cannot decode json';
			
		} 
	
		}

   
	}
	else{
		$response['result'] = 'KO3';
					$response['code'] = 500;
				$response['message'] = 'request not set';
	}
    mysqli_close($link);
    echo json_encode($response);
}
?>



