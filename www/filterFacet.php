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
	// impostazioni server solr
	$core = 'collection1';
	$options = array
	(
		'hostname' => 'localhost',
		'port'         => '8983',
		'path'         => 'solr/' . $core,
	);
	
	
		$queryutente='';
		$querycategoria='';
		$queryformat='';
		$queryresource='';
		$queryfiletype='';
		$querylicense='';
			$client = new SolrClient($options);
	//$query = new SolrQuery('*:*');
	//$query->setFacet(true);
	$role_att1 = $_REQUEST['role_att'];
	//$utente_att = $_REQUEST['utente_att'];
	
	////////////////////View privileges///////////////////
				include 'config.php';
			if (isset ($_SESSION['username'])){
				  $utente_att = $_SESSION['username'];	
				}else{
				 $utente_att= "Login";	
}	
				
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
				$role_att='Not_Qualified';
			}
	//////////
	if($role_att =='Qualified'){
		$query = new SolrQuery('*:*');
	}else{
			if ($utente_att == 'Login'|| $utente_att == ''){
				//$query = new SolrQuery('*:*');
				$query = new SolrQuery('Public:true');
			}else{
				//$query = new SolrQuery('username:'.$utente);
				//$query = new SolrQuery('Public:true');
				$query = new SolrQuery('Public:true OR username:"'.$utente_att.'"');
				//$query = new SolrQuery('Public:true UNION (username:"'.$utente_att.'" AND Public:false)');
				//$query = new SolrQuery('username:"'.$utente.'"');
			}
	}
	$query->setFacet(true);
	
		$query->addFacetField('format')->addFacetField('username')->addFacetField('Category')->addFacetField('license')->addFacetField('file_type')->addFacetField('Resource_input');

		
	
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
		if(isset($_POST['list'][4])){
		$resource=$_POST['list'][4];
		$P = count($resource);
		}
		else{
			$P =0;
		}
		
		if(isset($_POST['list'][3])){
		$filetype=$_POST['list'][3];
		$Q = count($filetype);
		}
		else{
			$Q =0;
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
			$categoria=str_replace(":", "\:",$categoria);
			$categoria=str_replace("(", "\(",$categoria);
			$categoria=str_replace(")", "\)",$categoria);
			$categoria=str_replace("[", "\[",$categoria);
			$categoria=str_replace("]", "\]",$categoria);
			$categoria=str_replace("*", "\*",$categoria);
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
			$utente=str_replace("*", "\*",$utente);
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
			$formato=str_replace("*", "\*",$formato);
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
			$risorsa=str_replace("*", "\*",$risorsa);
		if($i==0){
			$queryresource = 'Resource_input:' . $risorsa;
			}
		else{
			$queryresource = $queryresource . ' , '.'Resource_input:'  . $risorsa;
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
			$tipo=str_replace("*", "\*",$tipo);
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
			$licenza= str_replace(" ", "\\ ",$licenza);;
			$licenza= str_replace(":", "\:",$licenza);
			$licenza=str_replace("(", "\(",$licenza);
			$licenza=str_replace(")", "\)",$licenza);
			$licenza=str_replace("[", "\[",$licenza);
			$licenza=str_replace("]", "\]",$licenza);
			$licenza=str_replace("*", "\*",$licenza);
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
	
	

	$updateResponse = $client->query($query);
	
	$response_array = $updateResponse->getResponse();
	$resp3=$response_array->facet_counts->facet_fields->username->getPropertyNames();
	$resp4=$response_array->facet_counts->facet_fields->username;
	$resp5=$response_array->facet_counts->facet_fields->format->getPropertyNames();
$resp6=$response_array->facet_counts->facet_fields->format;
		$resp8=$response_array->facet_counts->facet_fields->Category->getPropertyNames();
	$resp7=$response_array->facet_counts->facet_fields->Category;
$resp9=$response_array->facet_counts->facet_fields->license->getPropertyNames();
$resp10=$response_array->facet_counts->facet_fields->license;


$resp15=$response_array->facet_counts->facet_fields->Resource_input->getPropertyNames();
$resp16=$response_array->facet_counts->facet_fields->Resource_input;

$resp19=$response_array->facet_counts->facet_fields->file_type->getPropertyNames();
$resp20=$response_array->facet_counts->facet_fields->file_type;

		$facet_list = array();
		$facet = array();
		
 	foreach($resp3 as $item2):
 		$username = array("facet"=>array(
			"facet_name" => $item2,
			"value"=>$resp4->$item2	,
				"cat"=>"username"
		)
		); 
		array_push($facet_list, $username); 
/* 				array_push($facet_name, $item2); 

				array_push($facet_value, $resp4->$item2);  */

	endforeach;
/* 		$var =array("username"=> array(
		"name"=>$facet_name,
			"value"=>$facet_value 
			)
		);
		array_push($facet_list,$var); */
		array_push($facet,$facet_list);
		
		$facet_list = array();
		 	foreach($resp8 as $item2):
 		$category = array("facet"=>array(
			"facet_name" => $item2,
			"value"=>$resp7->$item2,
             //"cat"=>"Category"
			"cat"=>"nature"
		)
		); 
		array_push($facet_list, $category); 
	endforeach;

		

		array_push($facet,$facet_list);
		
		
				$facet_list = array();
		 	foreach($resp15 as $item2):
 		$resource = array("facet"=>array(
			"facet_name" => $item2,
			"value"=>$resp16->$item2,	
            // "cat"=>"Resource"
				"cat"=>"sub_nature"
			
		)
		); 
		array_push($facet_list, $resource); 
	endforeach;
	
		array_push($facet,$facet_list);
		
				
		
				$facet_list = array();
		 	foreach($resp9 as $item2):
 		$license = array("facet"=>array(
			"facet_name" => $item2,
			"value"=>$resp10->$item2,
             "cat"=>"License"			
			
		)
		); 
		array_push($facet_list, $license); 
	endforeach;

		array_push($facet,$facet_list);
		

		
		

		
		
				$facet_list = array();
		 	foreach($resp19 as $item2):
 		$file_type = array("facet"=>array(
			"facet_name" => $item2,
			"value"=>$resp20->$item2,
             //"cat"=>"file_type"
				//"cat"=>"app_type"
				"cat"=>"resource_type"
			
		)
		); 
		array_push($facet_list, $file_type); 
	endforeach;

		array_push($facet,$facet_list); 
		
		$facet_list = array();
		 	foreach($resp5 as $item2):
 		$format = array("facet"=>array(
			"facet_name" => $item2,
			"value"=>$resp6->$item2,		
             "cat"=>"Format"			
			
		)
		); 
		array_push($facet_list, $format); 
	endforeach;

		array_push($facet,$facet_list);
		
		
    echo json_encode($facet);
	
	
	





?>