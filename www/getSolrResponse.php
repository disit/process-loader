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

   //**//
   /* at the top of 'check.php' */
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        /* 
           Up to you which header to send, some prefer 404 even if 
           the files does exist for security
        */
        header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

        /* choose the appropriate page to redirect users */
        die( header( 'location: page.php' ) );

    }
	//$utente_att = $_REQUEST['utente_att'];
	$role_att1 = $_REQUEST['role_att'];

	//View all resources///
	include 'config.php';
	//
	if (isset ($_SESSION['username'])){
  $utente_att = $_SESSION['username'];	
}else{
 $utente_att= "Login";	
}
	//
	$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname);
	////
	
	if ($utente_att !== 'Login'){
				$privilege=$role_att1;
				}else{
				$privilege='link';
				}
	
	///
	$queryRole = "SELECT functionalities.* FROM processloader_db.functionalities WHERE functionalities.".$privilege." = '1' AND functionalities.functionality = 'view all resources from solr';";
	$resultRole = mysqli_query($link, $queryRole) or die(mysqli_error($link));
			if ($resultRole->num_rows > 0) {
				$role_att ='Qualified';
			}else{
				$role_att='NOT_Qualified';
			}
	
	/////////////////////////
	
	// impostazioni server solr
	$core = 'collection1';
	$options = array
	(
		'hostname' => 'localhost',
		'port'         => '8983',
		'path'         => 'solr/' . $core,
	);
	$client = new SolrClient($options);
	
	
	//CONTROLLO//
	if ($role_att == "Qualified"){
		$query = new SolrQuery('*:*');
	}
	else{
		//controllo utente
			if ($utente_att == 'Login'|| $utente_att == ''){
		//$query = new SolrQuery('*:*');
				  $query = new SolrQuery('Public:true');
			}else{

				$query = new SolrQuery('Public:true OR username:"'.$utente_att.'"');

			}
	}
	//
	$query->setFacet(true);
	//
	
		
		$queryutente='';
		$querycategoria='';
		$queryformat='';
		$queryresource='';
		$queryfiletype='';
		$querylicense='';

if(isset($_POST['list'])){
		if(isset($_POST['list'][0])){
		$username=$_POST['list'][0];
		$N = count($username);
		}
		else{
			$N =0;
		}
		
		if(isset($_POST['list'][1])){
		$category=$_POST['list'][1];
		$M = count($category);
		}
		else{
			$M =0;
		}
		
		if(isset($_POST['list'][2])){
		$format=$_POST['list'][2];
		$O = count($format);
		}
		else{
			$O =0;
		}

		if(isset($_POST['list'][3])){
		$filetype=$_POST['list'][3];
		$Q = count($filetype);
		}
		else{
			$Q =0;
		}
				if(isset($_POST['list'][4])){
		$resource=$_POST['list'][4];
		$P = count($resource);
		}
		else{
			$P =0;
		}
		
		if(isset($_POST['list'][5])){
		$license=$_POST['list'][5];
		$R = count($license);
		}
		else{
			$R =0;
		}

		
		
		if($M>0){
		for($i=0; $i < $M; $i++){
			$categoria= $category[$i];
			$categoria=str_replace(" ", "\\ ",$categoria);
			$categoria= str_replace(":", "\:",$categoria);
			$categoria=str_replace("(", "\(",$categoria);
			$categoria=str_replace(")", "\)",$categoria);
			$categoria=str_replace("[", "\[",$categoria);
			$categoria=str_replace("]", "\]",$categoria);
		if($i==0){
			$querycategoria = 'Category:' . $categoria;
			}
		else{
			$querycategoria = $querycategoria . ' , '.'Category:'  . $categoria;
			}

		}
		}
		if($N>0){
	for($i=0; $i < $N; $i++){
			$utente= $username[$i];
			$utente= str_replace(" ", "\\ ",$utente);
			$utente= str_replace(":", "\:",$utente);
			$utente=str_replace("(", "\(",$utente);
			$utente=str_replace(")", "\)",$utente);
			$utente=str_replace("[", "\[",$utente);
			$utente=str_replace("]", "\]",$utente);
		if($i==0){
			$queryutente = 'username:' . $utente;
			}
		else{
			$queryutente = $queryutente . ' , '.'username:'  . $utente;
			}

			}
		}
				if($O>0){
	for($i=0; $i < $O; $i++){
			$formato= $format[$i];
			$formato= str_replace(" ", "\\ ",$formato);
			$formato= str_replace(":", "\:",$formato);
			$formato=str_replace("(", "\(",$formato);
			$formato=str_replace(")", "\)",$formato);
			$formato=str_replace("[", "\[",$formato);
			$formato=str_replace("]", "\]",$formato);
		if($i==0){
			$queryformat = 'format:' . $formato;
			}
		else{
			$queryformat = $queryformat . ' , '.'format:'  . $formato;
			}

			}
		}
				if($P>0){
	for($i=0; $i < $P; $i++){
			$risorsa= $resource[$i];
			$risorsa= str_replace(" ", "\\ ",$risorsa);
			$risorsa= str_replace(":", "\:",$risorsa);
			$risorsa=str_replace("(", "\(",$risorsa);
			$risorsa=str_replace(")", "\)",$risorsa);
			$risorsa=str_replace("[", "\[",$risorsa);
			$risorsa=str_replace("]", "\]",$risorsa);
		if($i==0){
			$queryresource = 'Resource_input :' . $risorsa;
			}
		else{
			$queryresource = $queryresource . ' , '.'Resource_input :'  . $risorsa;
			}

			}
		}
				if($Q>0){
	for($i=0; $i < $Q; $i++){
			$tipo= $filetype[$i];
			$tipo= str_replace(" ", "\\ ",$tipo);
			$tipo= str_replace(":", "\:",$tipo);
			$tipo=str_replace("(", "\(",$tipo);
			$tipo=str_replace(")", "\)",$tipo);
			$tipo=str_replace("[", "\[",$tipo);
			$tipo=str_replace("]", "\]",$tipo);
		if($i==0){
			$queryfiletype = 'file_type:' . $tipo;
			}
		else{
			$queryfiletype = $queryfiletype . ' , '.'file_type:'  . $tipo;
			}

			}
		}
				if($R>0){
	for($i=0; $i < $R; $i++){
			$licenza= $license[$i];
			$licenza= str_replace(" ", "\\ ",$licenza);
			$licenza= str_replace(":", "\:",$licenza);
			$licenza=str_replace("(", "\(",$licenza);
			$licenza=str_replace(")", "\)",$licenza);
			$licenza=str_replace("[", "\[",$licenza);
			$licenza=str_replace("]", "\]",$licenza);
		if($i==0){
			$querylicense = 'license:' . $licenza;
			}
		else{
			$querylicense = $querylicense . ' , '.'license:'  . $licenza;
			}

			}
		}
	if($queryutente!=''){
	$query->addFilterQuery($queryutente);
	}
	if($querycategoria!=''){
	$query->addFilterQuery($querycategoria);
	}
	if($queryformat!=''){
	$query->addFilterQuery($queryformat);
	}
	if($querylicense!=''){
	$query->addFilterQuery($querylicense);
	}
	if($queryfiletype!=''){
	$query->addFilterQuery($queryfiletype);
	}
	if($queryresource!=''){
	$query->addFilterQuery($queryresource);
	}	
	}
	
	$query->setRows(9999);
	$updateResponse = $client->query($query);
	$response_array = $updateResponse->getResponse();
	$total=$response_array->offsetGet('response')->numFound;
	
	
	
	$solr_list = array();
	
	for($x=0;$x<$total;$x++){
		$row3b=$response_array->offsetGet('response')->docs[$x];
		$data1 = $row3b->Creation_date;

		$data2 = str_replace('T',' ',$data1);
		$data3 = str_replace('Z','',$data2);
		$data4 = str_replace(':','-',$data3);
		$data5 = str_replace(' ','-',$data4);
		$data6 = str_replace('.0','',$data5);
		
		$us =$row3b->username;
		$file_n = $row3b->File_name;
		$file1 = explode('.',$file_n);
		$link="uploads/".$us."/".$data6."/".$file1[0]."/".$row3b->File_name;
		$link2="uploads/".$us."/".$data6."/".$row3b->File_name;
		$rt='no';
		$per='no';
		if ($row3b->Realtime==true){
			$rt='yes';
			}
		if ($row3b->Periodic==true){
			$per='yes';
			}

	if($row3b->file_type[0]=='ETL' || $row3b->file_type[0]=='R' || $row3b->file_type[0]=='Java'){
		    $solr =  array(
			

			
                        
                        "file_name" => $row3b->File_name,
						"downloads" => $row3b->Download_number,
                        "creation_date" => $data6,
                        "link" => $link,
						"realtime"=>$rt,
                        "periodic"=>$per,
						"id"=>$row3b->Id,
						"image"=>$row3b->Img,
						"average_stars"=>$row3b->Average_stars,
						"votes"=>$row3b->Votes,
						"description"=>$row3b->Description,
						//"file_type"=>$row3b->file_type,
						//"app_type"=>$row3b->file_type,
						"resource_type"=>$row3b->file_type,
						//
						"username"=>$row3b->username,
						//"resource"=>$row3b->Resource_input,
						//"category"=>$row3b->Category,
						"format"=>$row3b->format,
						"license"=>$row3b->license,
						//"category"=>$row3b->Category,
						"nature"=>$row3b->Category,
						"sub_nature"=>$row3b->Resource_input,
						//
						"protocol"=>$row3b->Protocol,
						"dateofpublication"=>$row3b->Date_of_publication,
						"Public" =>$row3b->Public,
                
                );
				array_push($solr_list, $solr);
		
	}
			//if($row3b->file_type[0]!='ETL'){                                                                   //TODO metter gli if per gli altri filetype
		if($row3b->file_type[0]=='NodeRed Application' || $row3b->file_type[0]=='NodeRed Library' || $row3b->file_type[0]=='IoTApp' || $row3b->file_type[0]=='IoTBlocks'){
		    $solr = array(

                        "file_name" => $row3b->File_name,
						"downloads" => $row3b->Download_number,
                        "creation_date" => $data6,
                        "link" => $link2,
						"id"=>$row3b->Id,
						"image"=>$row3b->Img,
						//"resource"=>$row3b->Resource_input,
						"sub_nature"=>$row3b->Resource_input,
						//
						//"category"=>$row3b->Category,
						"nature"=>$row3b->Category,
						//
						"format"=>$row3b->format,
						"license"=>$row3b->license,
						"description"=>$row3b->Description,
						"protocol"=>$row3b->Protocol,
						"average_stars"=>$row3b->Average_stars,
						"votes"=>$row3b->Votes,
						"dateofpublication"=>$row3b->Date_of_publication,
						//"file_type"=>$row3b->file_type,
						//"app_type"=>$row3b->file_type,
						"resource_type"=>$row3b->file_type,
						//
						"username"=>$row3b->username,
						"Public" =>$row3b->Public,

                
                );
				array_push($solr_list, $solr);
		
	}
			if($row3b->file_type[0]=='Mobile App'){
		    $solr = array(
			

			
                        
                        "file_name" => $row3b->File_name,
						"downloads" => $row3b->Download_number,
                        "creation_date" => $data6,
                        "link" => $link2,
						"id"=>$row3b->Id,
						"image"=>$row3b->Img,
						//"category"=>$row3b->Category,
						"nature"=>$row3b->Category,
						//
						"license"=>$row3b->license,
						"description"=>$row3b->Description,
						"average_stars"=>$row3b->Average_stars,
						"votes"=>$row3b->Votes,
						"dateofpublication"=>$row3b->Date_of_publication,
						//"file_type"=>$row3b->file_type,
						//"app_type"=>$row3b->file_type,
						"resource_type"=>$row3b->file_type,
						"username"=>$row3b->username,
						"OS"=>$row3b->OS,
						"OpenSource"=>$row3b->OpenSource,
						"Public" =>$row3b->Public,
						

                
                );
				array_push($solr_list, $solr);
		
	}
	
	if($row3b->file_type[0]=='Banana Library'){
		    $solr =  array(
			
                        "file_name" => $row3b->File_name,
						"downloads" => $row3b->Download_number,
                        "creation_date" => $data6,
                        "link" => $link2,
						"realtime"=>$rt,
                        "periodic"=>$per,
						"id"=>$row3b->Id,
						"image"=>$row3b->Img,
						"average_stars"=>$row3b->Average_stars,
						"votes"=>$row3b->Votes,
						"description"=>$row3b->Description,
						//"file_type"=>$row3b->file_type,
						//"app_type"=>$row3b->file_type,
						"resource_type"=>$row3b->file_type,
						"username"=>$row3b->username,
						//"resource"=>$row3b->Resource_input,
						"sub_nature"=>$row3b->Resource_input,
						"category"=>$row3b->Category,
						"format"=>$row3b->format,
						"license"=>$row3b->license,
						//"category"=>$row3b->Category,
						"nature"=>$row3b->Category,
						"protocol"=>$row3b->Protocol,
						"dateofpublication"=>$row3b->Date_of_publication,
						"Public" =>$row3b->Public,
                
                );
				array_push($solr_list, $solr);
		
	}
	
	if($row3b->file_type[0]=='DevDash'|| $row3b->file_type[0]=='AMMA'|| $row3b->file_type[0]=='ResDash'){
		    $solr =  array(
			
                        "file_name" => $row3b->File_name,
						"downloads" => $row3b->Download_number,
                        "creation_date" => $data6,
                        "link" => $link2,
						"realtime"=>$rt,
						"id"=>$row3b->Id,
						"image"=>$row3b->Img,
						"average_stars"=>$row3b->Average_stars,
						"votes"=>$row3b->Votes,
						"description"=>$row3b->Description,
						//"file_type"=>$row3b->file_type,
						//"app_type"=>$row3b->file_type,
						"resource_type"=>$row3b->file_type,
						"username"=>$row3b->username,
						//"resource"=>$row3b->Resource_input,
						"sub_nature"=>$row3b->Resource_input,
						//"category"=>$row3b->Category,
						"format"=>$row3b->format,
						"license"=>$row3b->license,
						//"category"=>$row3b->Category,
						"nature"=>$row3b->Category,
						"protocol"=>$row3b->Protocol,
						"dateofpublication"=>$row3b->Date_of_publication,
						"Public" =>$row3b->Public,
                
                );
				array_push($solr_list, $solr);
		
	}
	
	if($row3b->file_type[0]=='MicroService' || $row3b->file_type[0]=='Microservice' || $row3b->file_type[0]=='DataAnalyticMicroService'){
		    $solr =  array(
			
                        "file_name" => $row3b->File_name,
						"downloads" => $row3b->Download_number,
                        "creation_date" => $data6,
                        "link" => $link2,
						"realtime"=>$rt,
						"id"=>$row3b->Id,
						"image"=>$row3b->Img,
						"average_stars"=>$row3b->Average_stars,
						"votes"=>$row3b->Votes,
						"description"=>$row3b->Description,
						//"file_type"=>$row3b->file_type,
						//"app_type"=>$row3b->file_type,
						"resource_type"=>$row3b->file_type,
						"username"=>$row3b->username,
						//"resource"=>$row3b->Resource_input,
						"sub_nature"=>$row3b->Resource_input,
						"category"=>$row3b->Category,
						"format"=>$row3b->format,
						"license"=>$row3b->license,
						//"category"=>$row3b->Category,
						"nature"=>$row3b->Category,
						"protocol"=>$row3b->Protocol,
						"dateofpublication"=>$row3b->Date_of_publication,
						"Public" =>$row3b->Public,
                
                );
				array_push($solr_list, $solr);
		
	}
	

		
	}
	echo json_encode($solr_list);






?>