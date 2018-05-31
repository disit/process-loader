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

// prendo la request in json decodificata
		$request = json_decode(file_get_contents('php://input'));
	
		
		if($request){//fai tutto, il json e quindi l'array sono ben formati 
			//if(is_array($json)){
			$app_type = mysqli_real_escape_string($link, $request->app_type);            //per capire da quale applicazione arriva la richiesta per l'API, TODO: switch tra app_type diverse
			//if($app_type!="NodeRed" && $app_type!="ResDash" && $app_type!="DevDash" && $app_type!="AMMA" && $app_type!="NodeRed" && $app_type!="Microservice"){
			//if($app_type!="IoTBlocks" && $app_type!="ResDash" && $app_type!="DevDash" && $app_type!="AMMA" && $app_type!="NodeRed" && $app_type!="Microservice"){	
			if($app_type=="IoTBlocks" || $app_type=="ResDash" || $app_type =="DevDash" || $app_type=="AMMA" || $app_type=="MicroService"){	
				$response['result'] = 'KO';
				$response['code'] = 400;
				$response['message'] = 'file type not recognised';
				
			}
			else
			{
				if($app_type=="IoTApp"){
					//$app_details = mysqli_real_escape_string($link, $request->app_details);
					/*
					if($app_details=="Blocks" ){
						
						$response['result'] = 'KO';
						$response['code'] = 400;
						$response['message'] = 'File not yet supported';
					}
					*/
					//else if($app_details=="App"){
						
						if(isset($request->name) && isset($request->sub_nature) && isset($request->nature) && isset($request->licence) && isset($request->description) && isset($request->user) && isset($request->data) )
						{	
							$name = mysqli_real_escape_string($link, $request->name);
							$sub_nature = mysqli_real_escape_string($link, $request->sub_nature);
							$nature = mysqli_real_escape_string($link, $request->nature);
							
							$licence = mysqli_real_escape_string($link, $request->licence);
							$description = mysqli_real_escape_string($link, $request->description);
							$user = mysqli_real_escape_string($link, $request->user);       // TODO aggiungere sicurezza per l'user
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
							// prendo il json dall'url che contiene i dati
							$fp = fopen($target_path, 'w') or die("Unable to open file!");;
							fwrite($fp, json_encode($data));
							fclose($fp);
							
							//aggiungo riga al DB con i metadati del nuovo file

							$query="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,Category,Format,Resource_input,Protocol)VALUES (NULL,'".$name.".json','".$description."','0','".$date."','".$file_type."','Awaiting approval','".$user."','0',NULL,'".$licence."','0','0','0','0','".$nature."','".$format."','".$sub_nature."',NULL)";
							$query_caricamento = mysqli_query($connessione_al_server,$query) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
							
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
						
						
						
					//}
					/*
					else{
						$response['result'] = 'KO';
						$response['code'] = 500;
						$response['message'] = 'file type not expected';
					}
					*/
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
			$response['code'] = 500;
			$response['message'] = 'cannot decode json';
			
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



