<?php

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
	$query = new SolrQuery('*:*');
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