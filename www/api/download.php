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
//include ('access_token.php');
 ///
/*$value_request = $_GET;
$handle1 = fopen("file_query.txt", "w");
fwrite($handle1, var_export($_GET, true));
fclose($handle1);*/
//// 
$link = mysqli_connect($host, $username, $password);
mysqli_select_db($link, $dbname); 
//ACCESS_TOKEN
$url_accessToken = $access_token_userinfo;
//$token=$_GET['token'];	
$userToken ="";
$public =0;	
if(isset($_GET['id'])) {
	$query_public = "SELECT public FROM processloader_db.uploaded_files WHERE Id=".mysqli_real_escape_string($link,$_GET['id']);
	$result_public = mysqli_query($link, $query_public) or die(mysqli_error($link));
	$list_public = 0;
	 $num = $result_public->num_rows;
					if ($num > 0) 
					{
						
						while ($row = mysqli_fetch_array($result_public)) 
						{
							$list_public=$row['public'];
					}
					}
						
	/*$handle1 = fopen("file_query.txt", "w");
	  fwrite($handle1, $list_public." ".$_GET['id']);
	  fclose($handle1);*/
	if ($list_public=='1'){
		$public =1;		
	}
}

//if ($_GET['token']){
$token=$_GET['user'];	
if ($_GET['user'] && $public !=1 ){
$token_auth= "Authorization: Bearer ".$token;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_accessToken);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
//
//error_log(var_export($_GET, true), 3, "/var/log/my-errors.log");
//
$headers = array();
$headers[] = $token_auth;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);
$json = (array) json_decode($result);
$userToken = $json['preferred_username'];
//$userToken = $json['username'];
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


   	$response = [];

if($link == false){
    //try to reconnect
	$response['result'] = 'KO';
	$response['code'] = 500;
	$response['message'] = 'DB Connection error';
	mysqli_close($link);
    echo json_encode($response);
}
else
{


	if(count($_GET)>0)
	{
		// qui invece del get[resource_type] si può metter un ciclo su tutti i parametri del get e costruire una stringa da attaccare nel where della query, del tipo WHERE category=$_GET['nature'] and resource_type=$GET['resource_type'] and ....
		if(isset($_GET['id']))
		{
						$id_query=mysqli_real_escape_string($link,$_GET['id']);
			if ($userToken !=""){
			//$query = "SELECT * FROM processloader_db.uploaded_files WHERE Username='".$userToken."' and Id='".$id_query."'";	
				$query = "SELECT * FROM processloader_db.uploaded_files WHERE Id='".$id_query."' AND ((Username='".$userToken."' AND public=0) OR (public=1))";	
			}else{
			$query = "SELECT * FROM processloader_db.uploaded_files WHERE public=1 and Id='".$id_query."'";
			}
			$result = mysqli_query($link, $query) or die(mysqli_error($link));
			if(!$result)
			{

				mysqli_rollback($link);
				$response['result'] = 'Ko';
				$response['message'] = 'Database problem';
				$response['code'] = 500;

			}
			else{
				$list=array();
				$num=$result->num_rows;
				if($num==1){
					$row = mysqli_fetch_array($result); //Qui ho il record(unico)
					if($row['file_type']=="IoTApp"){
								$listFile = array(
									"id" => $row['Id'],
									"name" => $row['File_name'],
									"downloads" => $row['Download_number'],
									"creation_date" => $row['Creation_date'],
									"average_stars"=>$row['Average_stars'],
									"votes"=>$row['Votes'],
									"description"=>$row['Description'],
									"resource_type"=>$row['file_type'],
									//"username"=>$row['Username'],
									"sub_nature"=>$row['Resource_input'],
									"nature"=>$row['Category'],
									"format"=>$row['Format'],
									"licence"=>$row['License'],
									"Public"=>$row['Public']
									
								);
							array_push($list, $listFile);
							
							$data1 = $row['Creation_date'];

							$data2 = str_replace('T',' ',$data1);
							$data3 = str_replace('Z','',$data2);
							$data4 = str_replace(':','-',$data3);
							$data5 = str_replace(' ','-',$data4);
							$data6 = str_replace('.0','',$data5);
							$file1 = explode('.',$row['File_name']);
							$link_file="../uploads/".$row['Username']."/".$data6."/".$row['File_name'];
							$str = json_decode(file_get_contents($link_file));
							
							$link_local = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
							$link = str_replace('api/download.php','',$link_local);
							$link_file = $link . "/uploads/".$row['Username']."/".$data6."/".$row['File_name'];
							
						$response['result'] = 'Ok';
						$response['file_link'] = $link_file;
						$response['code'] = 200;
						$response['file_metadata'] = $list;
						$response['data'] = $str;
						

					}
					elseif($row['file_type']=="IoTBlocks"){
								$listFile = array(
									"id" => $row['Id'],
									"name" => $row['File_name'],
									"downloads" => $row['Download_number'],
									"creation_date" => $row['Creation_date'],
									"average_stars"=>$row['Average_stars'],
									"votes"=>$row['Votes'],
									"description"=>$row['Description'],
									"resource_type"=>$row['file_type'],
									//"username"=>$row['Username'],
									"sub_nature"=>$row['Resource_input'],
									"nature"=>$row['Category'],
									"format"=>$row['Format'],
									"licence"=>$row['License'],
									"Public"=>$row['Public']
									
								);
							array_push($list, $listFile);
							// da qui, per crearmi il link del file
							$data1 = $row['Creation_date'];

							$data2 = str_replace('T',' ',$data1);
							$data3 = str_replace('Z','',$data2);
							$data4 = str_replace(':','-',$data3);
							$data5 = str_replace(' ','-',$data4);
							$data6 = str_replace('.0','',$data5);
							$file1 = explode('.',$row['File_name']);
							$link_local = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
							$link = str_replace('api/download.php','',$link_local);
							//$link_file = $link . "/uploads/".$row['Username']."/".$data6."/".$file1[0]."/".$row['File_name'];
							$link_file = $link . "/uploads/".$row['Username']."/".$data6."/".$row['File_name'];

						$response['result'] = 'Ok';
						$response['code'] = 200;
						$response['file_link'] = $link_file;
						$response['file_metadata'] = $list;
						

					}
					elseif($row['file_type']=="MicroService" || $row['file_type']=="DataAnalyticMicroService")
					{
								$listFile = array(
									"id" => $row['Id'],
									"name" => $row['File_name'],
									"downloads" => $row['Download_number'],
									"creation_date" => $row['Creation_date'],
									"average_stars"=>$row['Average_stars'],
									"votes"=>$row['Votes'],
									"description"=>$row['Description'],
									"resource_type"=>$row['file_type'],
									//"username"=>$row['Username'],
									"sub_nature"=>$row['Resource_input'],
									"nature"=>$row['Category'],
									"format"=>$row['Format'],
									"licence"=>$row['License'],
									"url"=>$row['Url'],
									"method"=>$row['Method'],
									"help"=>$row['Help'],
									"access"=>$row['Protocol'],
									"Public"=>$row['Public']
									
								);
							array_push($list, $listFile);
							
							$data1 = $row['Creation_date'];

							$data2 = str_replace('T',' ',$data1);
							$data3 = str_replace('Z','',$data2);
							$data4 = str_replace(':','-',$data3);
							$data5 = str_replace(' ','-',$data4);
							$data6 = str_replace('.0','',$data5);
							$file1 = explode('.',$row['File_name']);
							
							$current = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
							$current2 = htmlspecialchars( $current, ENT_QUOTES, 'UTF-8' );



							$link_local = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
							$link = str_replace('api/download.php','',$link_local);
							//$link_file = $link . "/uploads/".$row['Username']."/".$data6."/".$file1[0]."/".$row['File_name'];
							$link_file = $link . "/uploads/".$row['Username']."/".$data6."/".$row['File_name'];
									
						$response['result'] = 'Ok';
						$response['code'] = 200;
						$response['file_link'] = $link_file;
						$response['file_metadata'] = $list;
						

					}elseif($row['file_type']=="DataAnalyticMicroService"){
						$listFile = array(
									"id" => $row['Id'],
									"name" => $row['File_name'],
									"downloads" => $row['Download_number'],
									"creation_date" => $row['Creation_date'],
									"average_stars"=>$row['Average_stars'],
									"votes"=>$row['Votes'],
									"description"=>$row['Description'],
									"resource_type"=>$row['file_type'],
									//"username"=>$row['Username'],
									"sub_nature"=>$row['Resource_input'],
									"nature"=>$row['Category'],
									"format"=>$row['Format'],
									"licence"=>$row['License'],
									"url"=>$row['Url'],
									"method"=>$row['Method'],
									"help"=>$row['Help'],
									"access"=>$row['Protocol'],
									"Public"=>$row['Public']
									
								);
							array_push($list, $listFile);
							
							$data1 = $row['Creation_date'];

							$data2 = str_replace('T',' ',$data1);
							$data3 = str_replace('Z','',$data2);
							$data4 = str_replace(':','-',$data3);
							$data5 = str_replace(' ','-',$data4);
							$data6 = str_replace('.0','',$data5);
							$file1 = explode('.',$row['File_name']);
							
							$current = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
							$current2 = htmlspecialchars( $current, ENT_QUOTES, 'UTF-8' );



							$link_local = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
							$link = str_replace('api/download.php','',$link_local);
							//$link_file = $link . "/uploads/".$row['Username']."/".$data6."/".$file1[0]."/".$row['File_name'];
							$link_file = $link . "/uploads/".$row['Username']."/".$data6."/".$row['File_name'];
									
						$response['result'] = 'Ok';
						$response['code'] = 200;
						$response['file_link'] = $link_file;
						$response['file_metadata'] = $list;
						
					}elseif($row['file_type']=="ETL"||$row['file_type']=="R"||$row['file_type']=="Java"){
						$listFile = array(
									"id" => $row['Id'],
									"name" => $row['File_name'],
									"downloads" => $row['Download_number'],
									"creation_date" => $row['Creation_date'],
									"average_stars"=>$row['Average_stars'],
									"votes"=>$row['Votes'],
									"description"=>$row['Description'],
									"resource_type"=>$row['file_type'],
									//"username"=>$row['Username'],
									"sub_nature"=>$row['Resource_input'],
									"nature"=>$row['Category'],
									"format"=>$row['Format'],
									"licence"=>$row['License'],
									"Public"=>$row['Public']
									
								);
							array_push($list, $listFile);
							// da qui, per crearmi il link del file
							$data1 = $row['Creation_date'];

							$data2 = str_replace('T',' ',$data1);
							$data3 = str_replace('Z','',$data2);
							$data4 = str_replace(':','-',$data3);
							$data5 = str_replace(' ','-',$data4);
							$data6 = str_replace('.0','',$data5);
							$file1 = explode('.',$row['File_name']);
							$link_local = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
							$link = str_replace('api/download.php','',$link_local);
							$link_file = $link . "/uploads/".$row['Username']."/".$data6."/".$file1[0]."/".$row['File_name'];
							//$link_file = $link . "/uploads/".$row['Username']."/".$data6."/".$row['File_name'];

						$response['result'] = 'Ok';
						$response['code'] = 200;
						$response['file_link'] = $link_file;
						$response['file_metadata'] = $list;
					}
					elseif($row['file_type']=="ResDash" || $row['file_type'] =="DevDash" || $row['file_type']=="AMMA")
					{
						$listFile = array(
									"id" => $row['Id'],
									"name" => $row['File_name'],
									"downloads" => $row['Download_number'],
									"creation_date" => $row['Creation_date'],
									"average_stars"=>$row['Average_stars'],
									"votes"=>$row['Votes'],
									"description"=>$row['Description'],
									"resource_type"=>$row['file_type'],
									//"username"=>$row['Username'],
									"sub_nature"=>$row['Resource_input'],
									"nature"=>$row['Category'],
									"format"=>$row['Format'],
									"licence"=>$row['License'],
									"Public"=>$row['Public']
							);
						array_push($list, $listFile);
						
													
							$data1 = $row['Creation_date'];

							$data2 = str_replace('T',' ',$data1);
							$data3 = str_replace('Z','',$data2);
							$data4 = str_replace(':','-',$data3);
							$data5 = str_replace(' ','-',$data4);
							$data6 = str_replace('.0','',$data5);
							$file1 = explode('.',$row['File_name']);
							$link_file="../uploads/".$row['Username']."/".$data6."/".$row['File_name'];
							$str = json_decode(file_get_contents($link_file));
							
							$link_local = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
							$link = str_replace('api/download.php','',$link_local);
							$link_file = $link . "/uploads/".$row['Username']."/".$data6."/".$row['File_name'];
									
						$response['result'] = 'Ok';
						$response['code'] = 200;
						$response['file_link'] = $link_file;
						$response['file_metadata'] = $list;
						$response['file_data'] = $str;
						
						
					}
					else{
						$response['result'] = 'KO3';
						$response['code'] = 500;
						$response['message'] = 'file type not yet implemented';
					}
					
				}
				else{
						$response['result'] = 'OK';
						$response['code'] = 200;
						$response['message'] = 'no file founded';
				}
				
				
							
		
			}

			


			
		}
		else
		{
			$n=count($_GET);
			$keys=array_keys($_GET);
			if ($userToken ==""){
			$filter =  'WHERE public=1	';
			$allowed_keys=['nature','sub_nature','resource_type','licence','format','access','method'];
			}else{
			$filter = 'WHERE Username !=""	';
			$allowed_keys=['nature','sub_nature','resource_type','licence','format','access','method','user','status'];
			}
			$tok = 'n';
			if(count(array_intersect($keys, $allowed_keys)) == count($keys))
			{
				
				if (isset ($_GET['nature'])){
					$var2='Category';
					$filter = $filter . " AND Category = '".$_GET['nature']."'";
				}
				if (isset ($_GET['sub_nature'])){
					$var2='Resource_input';
					$filter = $filter . " AND Resource_input = '".$_GET['sub_nature']."'";
				}
				if(isset ($_GET['resource_type'])){
						$var2='file_type';
						$filter = $filter . " AND file_type = '".$_GET['resource_type']."'";
					}
				if (isset ($_GET['access'])){
						$var2='Protocol';
						$filter = $filter . " AND Protocol = '".$_GET['access']."'";
					}
				if(isset ($_GET['licence'])){
						$var2='license';
						$filter = $filter . " AND license = '".$_GET['licence']."'";
					}
				if(isset ($_GET['format'])){
						$var2='format';
						$filter = $filter . " AND format = '".$_GET['format']."'";
					}
				if (isset ($_GET['user'])){
					$var2='Username';
					if (isset ($_GET['status'])){
							$var3=$_GET['status'];
								 if ($var3 == 'Public'){
									 $filter = $filter . " AND Username ='".$userToken."' AND public = 1"; 
								 }
								 elseif($var3 == 'Private'){
									 $filter = $filter . " AND Username ='".$userToken."' AND public = 0";
								 }
								 elseif($var3 == 'All'){
									 $filter = $filter . " AND (( public=1 ) OR (Username ='".$userToken."' AND public = 0))";
								 }
					}else{
						$filter = $filter . " AND Username ='".$userToken."'";
					}
				}
				$query = "SELECT * FROM processloader_db.uploaded_files " .$filter ;
				  $handle = fopen("file.txt", "w");
					fwrite($handle, $query);
					fclose($handle);
				$currente_date = date('Y-m-d-H-i-s');  
				$result = mysqli_query($link, $query) or die(mysqli_error($link));
				if(!$result)
				{

					mysqli_rollback($link);
					$response['result'] = 'Ko';
					$response['message'] = 'Database problem';
					$response['code'] = 500;

				}
				else
				{
					
					
					$num = $result->num_rows;
					$list=array();
					if ($num > 0) 
					{
						
						while ($row = mysqli_fetch_array($result)) 
						{
							
							$resource_type=$row['file_type'];
							
							
							
						
						
							if($resource_type=="IoTApp"){
									$listFile = array(
										"id" => $row['Id'],
										"name" => $row['File_name'],
										"downloads" => $row['Download_number'],
										"creation_date" => $row['Creation_date'],
										"average_stars"=>$row['Average_stars'],
										"votes"=>$row['Votes'],
										"description"=>$row['Description'],
										"resource_type"=>$row['file_type'],
										//"username"=>$row['Username'],
										"sub_nature"=>$row['Resource_input'],
										"nature"=>$row['Category'],
										"format"=>$row['Format'],
										"licence"=>$row['License'],
										"Public"=>$row['Public']
										
									);
								array_push($list, $listFile);
								
							}
							elseif($resource_type=="IoTBlocks"){
									$listFile = array(
										"id" => $row['Id'],
										"name" => $row['File_name'],
										"downloads" => $row['Download_number'],
										"creation_date" => $row['Creation_date'],
										"average_stars"=>$row['Average_stars'],
										"votes"=>$row['Votes'],
										"description"=>$row['Description'],
										"resource_type"=>$row['file_type'],
										//"username"=>$row['Username'],
										"sub_nature"=>$row['Resource_input'],
										"nature"=>$row['Category'],
										"format"=>$row['Format'],
										"licence"=>$row['License'],
										"Public"=>$row['Public']
										
									);
								array_push($list, $listFile);
								
							}
							elseif($resource_type=="MicroService"){
									$listFile = array(
										"id" => $row['Id'],
										"name" => $row['File_name'],
										"downloads" => $row['Download_number'],
										"creation_date" => $row['Creation_date'],
										"average_stars"=>$row['Average_stars'],
										"votes"=>$row['Votes'],
										"description"=>$row['Description'],
										"resource_type"=>$row['file_type'],
										//"username"=>$row['Username'],
										"sub_nature"=>$row['Resource_input'],
										"nature"=>$row['Category'],
										"format"=>$row['Format'],
										"licence"=>$row['License'],
										"url"=>$row['Url'],
										"method"=>$row['Method'],
										"help"=>$row['Help'],
										"access"=>$row['Protocol'],
										"Public"=>$row['Public']
										
									);
								array_push($list, $listFile);
								
							}elseif($resource_type=="DataAnalyticMicroService"){
									$listFile = array(
										"id" => $row['Id'],
										"name" => $row['File_name'],
										"downloads" => $row['Download_number'],
										"creation_date" => $row['Creation_date'],
										"average_stars"=>$row['Average_stars'],
										"votes"=>$row['Votes'],
										"description"=>$row['Description'],
										"resource_type"=>$row['file_type'],
										//"username"=>$row['Username'],
										"sub_nature"=>$row['Resource_input'],
										"nature"=>$row['Category'],
										"format"=>$row['Format'],
										"licence"=>$row['License'],
										"url"=>$row['Url'],
										"method"=>$row['Method'],
										"help"=>$row['Help'],
										"access"=>$row['Protocol'],
										"Public"=>$row['Public']
										
									);
								array_push($list, $listFile);
								
							}elseif($resource_type=="ETL" || $resource_type=="R"||$resource_type=="Java"){
								$listFile = array(
										"id" => $row['Id'],
										"name" => $row['File_name'],
										"downloads" => $row['Download_number'],
										"creation_date" => $row['Creation_date'],
										"average_stars"=>$row['Average_stars'],
										"votes"=>$row['Votes'],
										"description"=>$row['Description'],
										"resource_type"=>$row['file_type'],
										//"username"=>$row['Username'],
										"sub_nature"=>$row['Resource_input'],
										"nature"=>$row['Category'],
										"format"=>$row['Format'],
										"licence"=>$row['License'],
										"Public"=>$row['Public']
										
									);
								array_push($list, $listFile);
							}
							elseif($resource_type=="ResDash" || $resource_type =="DevDash" || $resource_type=="AMMA"){
									$listFile = array(
										"id" => $row['Id'],
										"name" => $row['File_name'],
										"downloads" => $row['Download_number'],
										"creation_date" => $row['Creation_date'],
										"average_stars"=>$row['Average_stars'],
										"votes"=>$row['Votes'],
										"description"=>$row['Description'],
										"resource_type"=>$row['file_type'],
										//"username"=>$row['Username'],
										"sub_nature"=>$row['Resource_input'],
										"nature"=>$row['Category'],
										"format"=>$row['Format'],
										"licence"=>$row['License'],
										"Public"=>$row['Public']
									);
								array_push($list, $listFile);
							}
						}
						$response['files']=$list;
						$response['result'] = 'Ok';
						$response['code'] = 200;

					}
					else{
							$response['result'] = 'Ok';
							$response['code'] = 200;
							$response['message'] = 'not file founded';
					}
				
				}
				
			}
			else{
				$response['result'] = 'KO';
				$response['code'] = 500;
				$response['message'] = 'input not valid: some parameters not recognised';
			}

			
			
		}


		
			

		
		

	   
	}
	
	else{
		$response['result'] = 'KO3';
		$response['code'] = 500;
		$response['message'] = 'request (GET) not set';
	}
//mysqli_close($link);
  echo json_encode($response);
}


?>



