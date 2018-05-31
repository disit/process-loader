<!DOCTYPE html>
<?php
include('config.php'); // Includes Login Script
include('control.php');

include("curl.php");
// funzione per aggiornare il numero di download
function update_down()
{
	$id = $_POST['id'];
	$down = $_POST['down'];
	$query="UPDATE uploaded_files SET Download_number = down+1 WHERE Id =id ";
	$query_job_type = mysqli_query($connessione_al_server,$query) ;
	$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
	url_get($url);  
	header("location:view_public_processes2.php");
	die;
}

//prendo sessione utente e ruolo
if (isset ($_SESSION['username'])){
  $utente_att = $_SESSION['username'];	
}else{
 $utente_att= "Login";	
}

if (isset ($_SESSION['username'])){
  $role_att = $_SESSION['role'];	
}else{
 $role_att= "";	
}

// ho cercato qualcosa dalla barra di ricerca
if(isset($_SESSION['search_input'])){
	$_SESSION['search']=$_SESSION['search_input'];
}
else{
	$_SESSION['search']='Search';
}

// impostazioni server solr
$core = 'collection1';
$options = array
(
	'hostname' => 'localhost',
	'port'         => '8983',
	'path'         => 'solr/' . $core,
);
//
//
$client = new SolrClient($options);
$query = new SolrQuery('*:*');
$query->setFacet(true);





	$queryutente='';
	$querycategoria='';
	$querylicenza='';
	$queryrisorsa='';
	$queryformato='';
	$queryrealtime='';
	$queryperiodic='';
	$search_input='';

	// recupero valori da checkbox
	if(isset($_POST['cat']) ){                           // se post è settato, session diventa uguale a post
		$aCat = $_POST['cat'];
		$_SESSION['aCat']=$_POST['cat'];
		

		$N = count($_SESSION['aCat']);	
		}
	elseif(isset($_SESSION['aCat'])) {
		$N = count($_SESSION['aCat']);
		}
	else{
		$N =0;
		}
					
	if(isset($_POST['user_id']) ){                           // se post è settato, session diventa uguale a post
		$aCat = $_POST['user_id'];
		$_SESSION['aUser']=$_POST['user_id'];

		$M = count($_SESSION['aUser']);	
		}

	elseif(isset($_SESSION['aUser'])) {
		$M = count($_SESSION['aUser']);
		}
	else{
		$M =0;
		}
	
	if(isset($_POST['format']) ){                           // se post è settato, session diventa uguale a post
		$aCat = $_POST['format'];
		$_SESSION['aFormat']=$_POST['format'];
		$F = count($_SESSION['aFormat']);	
		}
	elseif(isset($_SESSION['aFormat'])) {
		$F = count($_SESSION['aFormat']);
		}
	else{
		$F =0;
		}
			
	if(isset($_POST['resource_input']) ){                           // se post è settato, session diventa uguale a post
		$aCat = $_POST['resource_input'];
		$_SESSION['aResource']=$_POST['resource_input'];
		$RI = count($_SESSION['aResource']);	
		}
	elseif(isset($_SESSION['aResource'])) {
		$RI = count($_SESSION['aResource']);
		}
	else{
		$RI =0;
		}
		
	if(isset($_POST['licence']) ){                           // se post è settato, session diventa uguale a post
		$aCat = $_POST['licence'];
		$_SESSION['aLicence']=$_POST['licence'];
		$L = count($_SESSION['aLicence']);	
		}
	elseif(isset($_SESSION['aLicence'])) {
		$L= count($_SESSION['aLicence']);
		}
	else{
		$L =0;
		}
		
	if(isset($_POST['periodic']) ){                           // se post è settato, session diventa uguale a post
		$aCat = $_POST['periodic'];
		$_SESSION['aPeriodic']=$_POST['periodic'];
		$P = count($_SESSION['aPeriodic']);	
		}
	elseif(isset($_SESSION['aPeriodic'])) {
		$P = count($_SESSION['aPeriodic']);
		}
	else{
		$P =0;
		}
	
	if(isset($_POST['realtime']) ){                           // se post è settato, session diventa uguale a post
		$aCat = $_POST['realtime'];
		$_SESSION['aRealtime']=$_POST['realtime'];
		$RT = count($_SESSION['aRealtime']);	
		}
	elseif(isset($_SESSION['aRealtime'])) {
		$RT = count($_SESSION['aRealtime']);
		}
	else{
		$RT =0;
		}
	// fine recupero valori
	//inizio costruzione filtri solr
	for($i=0; $i < $N; $i++){
		if($i==0){
						$categoria= $_SESSION['aCat'][$i];
			$categoria=str_replace(" ", "\\ ",$categoria);
			$querycategoria = 'Category:' . $categoria;
			}
		else{
									$categoria= $_SESSION['aCat'][$i];
			$categoria=str_replace(" ", "\\ ",$categoria);
			$querycategoria = $querycategoria . ' , '.'Category:'  . $categoria;
			}

	}
	for($i=0; $i < $M; $i++){
		if($i==0){
			
			$utente= $_SESSION['aUser'][$i];
			$utente= str_replace(" ", "\\ ",$utente);
			$queryutente = 'username:' . $utente;
			}
		else{
						$utente= $_SESSION['aUser'][$i];
			$utente= str_replace(" ", "\\ ",$utente);
			$queryutente = $queryutente . ' , '.'username:'  . $utente;
			}

	}


	for($i=0; $i < $F; $i++){
		if($i==0){
									$formato= $_SESSION['aFormat'][$i];
			$formato=str_replace(" ", "\\ ",$formato);
			$queryformato = 'format:' . $formato;
			}
		else{
									$formato= $_SESSION['aFormat'][$i];
			$formato=str_replace(" ", "\\ ",$formato);
			$queryformato = $queryformato . ' , '.'format:'  . $formato;
			}
	
	}
	
	for($i=0; $i < $RI; $i++){
		if($i==0){
												$risorsa= $_SESSION['aResource'][$i];
			$risorsa=str_replace(" ", "\\ ",$risorsa);
			$queryrisorsa = 'Resource_input:' . $risorsa;
			}
		else{
												$risorsa= $_SESSION['aResource'][$i];
			$risorsa=str_replace(" ", "\\ ",$risorsa);
			$queryrisorsa = $queryrisorsa . ' , '.'Resource_input:'  . $risorsa;
			}

	}
	
	for($i=0; $i < $L; $i++){
		if($i==0){
												$licenza= $_SESSION['aLicence'][$i];
			$licenza=str_replace(" ", "\\ ",$licenza);
			$querylicenza = 'license:' . $licenza;
			}
		else{
															$licenza= $_SESSION['aLicence'][$i];
			$licenza=str_replace(" ", "\\ ",$licenza);
			$querylicenza = $querylicenza . ' , '.'license:'  . $licenza;
			}
	
	}
	
	for($i=0; $i < $RT; $i++){
		if ($_SESSION['aRealtime'][$i]=='true'){
			$ret=1;
			}
		else{
			$ret=0;
			}
		if($i==0){
			$realtime=$ret;
			$queryrealtime = $queryrealtime . ' , '.'Realtime:'  . $realtime;
			}
		else{
						$realtime=$ret;
									$queryrealtime = $queryrealtime . ' , '.'Realtime:'  . $ret;


			}

	}
	
	for($i=0; $i < $P; $i++){
		if ($_SESSION['aPeriodic'][$i]=='true'){
			$ret=1;
			}
		else{
			$ret=0;
			}
		if($i==0){
						$periodic=$ret;
			$queryperiodic = $queryperiodic . ' , '.'Periodic:'  . $periodic;
			}
		else{
									$periodic=$ret;
									$queryperiodic = $queryperiodic . ' , '.'Periodic:'  . $ret;
			}

	}
	if(isset($_SESSION['search_input'])){
		$search_input=$_SESSION['search_input'];

	
	}
		if(isset($_POST['search_input']) ){
		$search_input=$_POST['search_input'];

	
	}
	// fine costruzione filtri
    //salvataggio filtri in session
	if($queryutente!=''){
	$_SESSION['queryutente']=$queryutente;
	}
		if($querycategoria!=''){
	$_SESSION['querycategoria']=$querycategoria;
	}
	if($queryrisorsa!=''){
	$_SESSION['queryrisorsa']=$queryrisorsa;
	}
	
		if($querylicenza!=''){
	$_SESSION['querylicenza']=$querylicenza;
	}
			if($queryformato!=''){
	$_SESSION['queryformato']=$queryformato;
	}
		if($queryrealtime!=''){
	$_SESSION['queryrealtime']=$queryrealtime;
	}
			if($queryperiodic!=''){
	$_SESSION['queryperiodic']=$queryperiodic;
	}
				if($search_input!=''){
	$_SESSION['search_input']=$search_input;
    }









//aggiunta dei filtri in funzione delle selezioni delle checkbox
if (isset($_SESSION['queryutente'])){
	$query->addFilterQuery($_SESSION['queryutente']);
}
if (isset($_SESSION['querycategoria'])){
	$query->addFilterQuery($_SESSION['querycategoria']);
}
if (isset($_SESSION['querylicenza'])){
	$query->addFilterQuery($_SESSION['querylicenza']);
}
if (isset($_SESSION['queryformato'])){
	$query->addFilterQuery($_SESSION['queryformato']);
}
if (isset($_SESSION['queryrisorsa'])){
	$query->addFilterQuery($_SESSION['queryrisorsa']);
}
if (isset($_SESSION['queryrealtime'])){
	$query->addFilterQuery($_SESSION['queryrealtime']);
}
if (isset($_SESSION['queryperiodic'])){
	$query->addFilterQuery($_SESSION['queryperiodic']);
}
 //gestione caso in cui nella barra di ricerca si inserisce una stringa vuota
	if(isset($_POST['search_input'])){
		if($_POST['search_input']=="")   //se ho messo una stringa vuota...
		{
			$_POST['search_input']=" ";
		}
		$_SESSION['search_input']=$_POST['search_input'];   // in session si mette il post per non perderlo


	}
if (isset($_SESSION['search_input'])){
	$query->addFilterQuery($_SESSION['search_input']);

}
//definizione faccette e query al solr
$query->addFacetField('format')->addFacetField('username')->addFacetField('Category')->addFacetField('license')->addFacetField('Realtime')->addFacetField('Periodic')->addFacetField('Realtime')->addFacetField('Resource_input');
try{
$updateResponse = $client->query($query);
$response_array = $updateResponse->getResponse();
//per la paginazione
$total=$response_array->offsetGet('response')->numFound;
	
		$limit = 6;
		if ($total==0){
			//echo('<p>Nothing to display!</p>');
		$total2=1;
		}		
		else $total2=$total;
		
	// quante pagine
	$pages = ceil($total2 / $limit);
	
	 // in quale pagina sono ora
	$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
		'options' => array(
			'default'   => 1,
			'min_range' => 1,
		),
	)));

	// offset della query
	$offset = ($page - 1)  * $limit;

	// info da visualizzare all'utente
	$start = $offset + 1;
		$end = min($limit,$total2-$offset);

	//metodi di solr
	$query->setRows($limit);
	$query->setStart($offset);
	
//fine inizio paginazione
	
	$updateResponse = $client->query($query);


	$response_array = $updateResponse->getResponse();
	// numeri e valori delle faccette nel menu a sx
	$facet_data = $response_array->facet_counts->facet_fields;
	$resp=$facet_data;
	$resp3=$response_array->facet_counts->facet_fields->username->getPropertyNames();
	$resp4=$response_array->facet_counts->facet_fields->username;
	$resp5=$response_array->facet_counts->facet_fields->format->getPropertyNames();
	$resp6=$response_array->facet_counts->facet_fields->format;
	$resp8=$response_array->facet_counts->facet_fields->Category->getPropertyNames();
	$resp7=$response_array->facet_counts->facet_fields->Category;
	$resp9=$response_array->facet_counts->facet_fields->license->getPropertyNames();
	$resp10=$response_array->facet_counts->facet_fields->license;
	$resp11=$response_array->facet_counts->facet_fields->Periodic->getPropertyNames();
	$resp12=$response_array->facet_counts->facet_fields->Periodic;
	$resp13=$response_array->facet_counts->facet_fields->Realtime->getPropertyNames();
	$resp14=$response_array->facet_counts->facet_fields->Realtime;
	$resp15=$response_array->facet_counts->facet_fields->Resource_input->getPropertyNames();
	$resp16=$response_array->facet_counts->facet_fields->Resource_input;
	$resp17=$response_array->facet_counts->facet_fields->Realtime->getPropertyNames();
	$resp18=$response_array->facet_counts->facet_fields->Realtime;

	$check_tot=0;
	$count=0;
		}
		//in caso di errore nella query...
	catch(SolrException $e){ 
		$message=$e->getMessage();
		echo('
			<script type="text/javascript">
				alert("error: '.$message.'");
			</script>
		');
		$limit=6;
		//echo('<p>Nothing to display!</p>');
		$total=0;
					
		$total2=1;
			
		// quante pagine
		$pages = ceil($total2 / $limit);
		
		 // in quale pagina sono ora
		$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
			'options' => array(
				'default'   => 1,
				'min_range' => 1,
			),
		)));

		// offset della query
		$offset = ($page - 1)  * $limit;

		// info da visualizzare all'utente
		$start = $offset + 1;
	   // $end = min(($offset + $limit), $total);
			$end = min($limit,$total2-$offset);
			
		$check_tot=0;
	$count=0;
		
	}
?>

<html lang="en">
<style>
#hovers:hover {text-decoration: underline; cursor:pointer;}
 .product_view .modal-dialog{max-width: 1800px; width: 100%;}
</style>
<head>
	<title>Process Loader</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
	<!-- FOR STAR RATING-->
	<script src="js/star-rating.min.js" type="text/javascript"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
	<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
	<link href="css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />
	<link href="css/starrr.css" media="all" rel="stylesheet" type="text/css" />
	<script src ="js/jquery.cookie.js"></script>
	<script src ="js/controllerCookies.js"></script>
	<!-- <script src ="js/controllerChangeCheckbox.js"></script> -->

	<link href="css/bootstrap.css" rel="stylesheet">
	<link  href="css/loader.css" rel="stylesheet">


</head>
<body >

	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>                        
		  </button>
			<a class="navbar-brand" href="Process_loader.php"><h1>Process Loader<h1/></a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
		  <ul class="nav navbar-nav navbar-right">
			<li><a href="#"> <span aria-hidden="true" data-toggle="modal" data-target="#" id="role_att"><?=$role_att;?></span></a></li>
			<li><a href="#"> <span aria-hidden="true" data-toggle="modal" data-target="#login-modal" id="utente_att"><?=$utente_att;?></span></a></li>
					<li><a href="logout.php">Logout</a></li>
		  </ul>
		</div>
	  </div>
	</nav>
 
	<div class="container-fluid text-center">    
	  <div class="row content">
		<div class="col-sm-2 sidenav">
		<p><h3>Operations</h3></p>
		<div  id="menu_lat">
		<p><a href="Process_loader.php">Processes in Execution list</a></p>
		<p><a href="upload.php">Upload new file</a></p>
		<p><a href="job_type.php">Process Types list</a></p>
		<p><a href="file_archive.php">uploaded files list</a></p>
		<p><a href="archive.php">Activity Archive</a></p>
		</div>
			<div  id="menu_lat_public">
		<p><a href="view_public_processes2.php">View public processes</a></p>
		</div>
		<div id="sc_mng" hidden>
		<p><h3>Admin operations</h3></p>
		<p><a href="schedulers_mng.php">Scheduler management</a></p>
		<p><a href="upload_process_production.php">Upload test in production</a></p>
		<p><a href="registrazione.php">User Register</a></p>
		</div>
		<div class="span2" >
		<!-- creazione form per ricerca sfaccettata -->
			<form method="POST" action="" class="facet_form" name="facet_form" id="facet_form">
				<br> <br> <br> <br>
				<!-- creazione menù con le faccette -->
				<fieldset>
					<legend onclick="apri_menu(this)" id="find-u-n">Username</legend>
					<div class="control-group" id="control-group-u-n" style="display:<?php if (!empty($_COOKIE['control-group-u-n'])){echo $_COOKIE['control-group-cat'];}else echo 'none';?>;">
						<?php foreach($resp3 as $item2):
							$check_tot=$check_tot + 1;
														echo('<label class="radio"> <input class="check_user" id="'.$item2.'"');echo('" type="checkbox"  placeholder="username" name="user_id[]"
							value="'.$item2.'">'.$item2.' ('.$resp4->$item2.') </label> ');
						endforeach;?>
					</div>
				</fieldset>
				<fieldset>
					<legend id="find-cat" onclick="apri_menu(this)"  >Category</legend>
					<div class="control-group" id="control-group-cat" style="display:<?php if (!empty($_COOKIE['control-group-cat'])){echo $_COOKIE['control-group-cat'];}else echo 'none';?>;">
						<?php foreach($resp8 as $item2):
							$check_tot=$check_tot + 1;

							echo('<label class="radio"> <input class="check_cat" id="'.$item2.'"');echo('" type="checkbox"  placeholder="category" name="cat[]"
							value="'.$item2.'">'.$item2.' ('.$resp7->$item2.') </label> ');
						endforeach;?>
					</div>
				</fieldset>
				<fieldset>
					<legend id="find-for" onclick="apri_menu(this)">Format</legend>
					<div class="control-group" id="control-group-for" style="display:<?php if (!empty($_COOKIE['control-group-for'])){echo $_COOKIE['control-group-for'];}else echo 'none';?>;">
						<?php foreach($resp5 as $item2):
							$check_tot=$check_tot + 1;
							echo('<label class="radio"> <input class="check_format" name="format[]" id="'.$item2.'"');echo('" type="checkbox"  
							value="'.$item2.'">'.$item2.' ('.$resp6->$item2.') </label> ');
						endforeach;?>
					</div>
				</fieldset>
				<fieldset>
					<legend id="find-ri" onclick="apri_menu(this)">Resource Input</legend>
					<div class="control-group" id="control-group-ri" style="display:<?php if (!empty($_COOKIE['control-group-ri'])){echo $_COOKIE['control-group-ri'];}else echo 'none';?>;">
						<?php foreach($resp15 as $item2):
							$check_tot=$check_tot + 1;
							echo('<label class="radio"> <input class="check_ri" name="resource_input[]" id="'.$item2.'"');echo('" type="checkbox"   
							value="'.$item2.'"
							>
							'.$item2.' ('.$resp16->$item2.') </label> ');
						endforeach;?>
					</div>
				</fieldset>
				<fieldset>
					<legend id="find-lic" onclick="apri_menu(this)">License</legend>
					<div class="control-group" id="control-group-lic" style="display:<?php if (!empty($_COOKIE['control-group-lic'])){echo $_COOKIE['control-group-lic'];}else echo 'none';?>;">
						<?php foreach($resp9 as $item2):
							$check_tot=$check_tot + 1;
							echo('<label class="radio"> <input class="check_lic" name="licence[]" id="'.$item2.'"');echo('" type="checkbox"  
							value="'.$item2.'"
							>
							'.$item2.' ('.$resp10->$item2.') </label> ');
						endforeach;?>
					</div>
				</fieldset>
				<fieldset>
					<legend id="find-per" onclick="apri_menu(this)">Periodic</legend>
					<div class="control-group" id="control-group-per" style="display:<?php if (!empty($_COOKIE['control-group-per'])){echo $_COOKIE['control-group-per'];}else echo 'none';?>;">
						<?php foreach($resp11 as $item2):
							$check_tot=$check_tot + 1;
							$stringa=$item2.'periodic';
							echo('<label class="radio"> <input class="check_per" name="periodic[]" id="'.$stringa.'"');echo('" type="checkbox"  
							value="'.$item2.'"
							>
							'.$item2.' ('.$resp12->$item2.') 
							</label> ');
						endforeach;?>
					</div>
				</fieldset>
				<fieldset>
					<legend id="find-rt" onclick="apri_menu(this)">Realtime</legend>
					<div class="control-group" id="control-group-rt" style="display:<?php if (!empty($_COOKIE['control-group-rt'])){echo $_COOKIE['control-group-rt'];}else echo 'none';?>;">
					<?php foreach($resp17 as $item2):
						$check_tot=$check_tot + 1;
						$stringa=$item2.'realtime';
						echo('<label class="radio"> <input class="check_rt" name="realtime[]" id="'.$stringa.'"');echo('" type="checkbox"  
						value="'.$item2.'"
						>
						'.$item2.' ('.$resp18->$item2.') 
						</label> ');
					endforeach;?>
					</div>
					    <div>
      <input style="display:none;" type="submit" id="submit_form"></input>
    </div>

				</fieldset>
			</form>
			<input type="button" id="reset-search" name="reset_brand_id" value="Reset" data-target="brand_id" class="btn btn-primary">
		</div>
	</div> 
	<!-- fine creazione menù faccette -->
	<div class="col-sm-8 text-left">
		<div>  
			<h2>Public Processes List	<span class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></h2>
		</div>
		
		<form enctype="multipart/form-data" action="view_public_processes2.php" id="search_input_form" method="POST" accept-charset="UTF-8">
				<input type="text" id="search_input"  name="search_input" class="form-control"  placeholder="<?php if(!isset($_SESSION['search_input'])) echo("Search"); else echo($_SESSION['search_input']);?>" >
<div><h3>
				<input type="submit" value="Search" class="btn btn-primary" onclick="set_check();"/>
				<input type="button" id="reset-search2"  value="Reset"  class="btn btn-primary" />
				<span class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#info-modal2"  style="color:#337ab7;font-size:24px;"></h3>
</div>
		</form>
	
				
				
		<div id="comando_nuovo_Processo2" hidden>
			<button type="button" id="button_new_process" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
				Carica nuovo processo
			</button>
		</div>
	<!-- contenitore principale visualizzazione risultati ricerca -->
	<div style="margin-top:50px;"> 
			
	<?php 
	// costruzione filtri da aggiungere in base ai valori selezionati nelle checkbox 



	//paginazione

	// "back" link
	$prevlink = ($page > 1) ? '<a class="a_page" href="?page=1" title="First page">&laquo;</a> <a class="a_page" href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
	// The "forward" link
	$nextlink = ($page < $pages) ? '<a class="a_page" href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a class="a_page" href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

	//inizio visualizzazione del risultato della query al solr con i filtri
	   if ($total > 0) {
		   
		for($x=0;$x<$end;$x++){
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
		$rt='no';
		$per='no';
		if ($row3b->Realtime==true){
			$rt='yes';}
		if ($row3b->Periodic==true){
			$per='yes';}

	// echo che stampa html con visualizzazione risultati ricerca
		echo('<div  class="container" >
		<div class="row" >
			<div class="list-group" >
				<div class="list-group-item clearfix " >
					<div class="profile-teaser-left" style="float: left; width: 25%; margin-right: 1%;">
						<div class="profile-img"><img style="width: 180px; height: 180px;" src="'.$row3b['Img'].'"/></div>
					</div>
					<div class="profile-teaser-main" style="    float: left; width: 74%;">
						<h2 class="profile-name"><a onclick="agg_down('.$row3b->Id.','.$row3b->Download_number.')" href="uploads/'.$us.'/'.$data6.'/'.$file1[0].'/'.$row3b->File_name.' " class="file_archive_link">'.$row3b->File_name.'</a></h2>
						<div class="profile-info">
							<div class="user_id" name='.$row3b->username.' style={display:"none"}></div>
							<div class="proc_id" name='.$row3b->Id.' style={display:"none"}></div>
							<div class="info" style="display: inline-block; margin-right: 10px; color: #777; "><span class="" style="font-weight: bold;">User:</span> ' .$row3b->username. '</div>
							<div class="info" style="display: inline-block; margin-right: 10px; color: #777; "><span class="" style="font-weight: bold;">Description:</span> ' .$row3b->Description. '</div>
							<div class="info" style="display: inline-block; margin-right: 10px; color: #777; "><span class="" style="font-weight: bold;">Downloads:</span> ' .$row3b->Download_number. '</div>
							<div class="info" style="display: inline-block; margin-right: 10px; color: #777; "><span class="" style="font-weight: bold;">Votes:</span> ' .$row3b->Votes. '</div>
							<div>    <input id="'.$row3b->Id.'" "value="0" onclick="agg_down('.$row3b->Id.','.$row3b->Download_number.')" type="number" class="rating" min=0 max=5 step=0.5 data-size="xs" data-stars="5" data-show-caption="false"></div>
							<div ><span style="color:blue" data-toggle="modal" data-target="#info-modal_proc'.$row3b->Id.'" id="hovers"">View Details</span></div>
						</div>
					</div>
				</div><!-- item -->
				
		<!-- Modal info sul processo-->
		
		<div class="modal fade" id="info-modal_proc'.$row3b->Id.'" role="dialog">
		<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Details of file: '.$row3b->File_name.'</h4>
			</div>
			<div class="modal-body">
					 <center>
						<img src="'.$row3b->Img.'" height="50%" width="50%" border="0" ></a>
						 <div class="row">
							<h4>File name: <span>'.$row3b->File_name.'</span></h4>
							<h4>Rating:</h4>
							<div class="rating">
							 <input id="input" name="input"  class="rating-loading" step="0.1 "value="'.$row3b->Average_stars.'">
								( mean '.$row3b->Average_stars.' on '.$row3b->Votes.' votes)
							</div>
							<!--
							<h4>Description:</h4>
							<p>'.$row3b->Description.'</p>
							<h4>User:</h4>
							<p>'.$row3b->username.'</p>
							<h4>File type:</h4>
														<p>'.count($row3b->file_type).'</p>

							<h4>Resource:</h4>
							<p>'.$row3b->Resource_input.'</p>
							<h4>Category:</h4>
							<p>'.$row3b->Category.'</p>
							<h4>Format:</h4>
							<p>'.$row3b->format.'</p>
							<h4>Protocol:</h4>
							<p>'.$row3b->Protocol.'</p>	
							<h4>Real-time:</h4>
							<p>'.$rt.'</p>
							<h4>Periodic:</h4>
							<p>'.$per.'</p>
							<h4>Date of publication:</h4>
							<p>'.$row3b->Date_of_publication.'</p>
							<h4>License:</h4>
							<p>'.$row3b->license.'</p>
							 -->
							<!--Organizzazione DATI -->
							<ul class="list-group">
									<li class="list-group-item"><b>Description: </b>'.$row3b->Description.'</li>
									<li class="list-group-item"><b>User: </b>'.$row3b->username.'</li>
									<li class="list-group-item"><b>File type: </b>'.$row3b->file_type[0].'</li>
									<li class="list-group-item"><b>Resource: </b>'.$row3b->Resource_input.'</li>
									<li class="list-group-item"><b>Category: </b>'.$row3b->Category.'</li>
									<li class="list-group-item"><b>Protocol: </b>'.$row3b->Protocol.'</li>
									<li class="list-group-item"><b>Real-time: </b>'.$rt.'</li>
									<li class="list-group-item"><b>Periodic: </b>'.$per.'</li>
									<li class="list-group-item"><b>Date_of_publication: </b>'.$data3.'</li>
									<li class="list-group-item"><b>License: </b>'.$row3b->license.'</li>
								</ul>
							
							<!-- Fine Organizzazione-->
							<div class="space-ten"></div>
					</div>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  
		</div>
	  </div>
	
	');

			}
		}else{echo("<p>Nothing to display!</p>");};

		
//paginazione
   echo '<div style="margin-top:20px;" id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $offset+$end, ' of ', $total, ' results ', $nextlink, ' </p></div>';

 ?>
			
			</div>
		
		
	</div>
	
  </div>
</div>

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		  <div class="modal-dialog">
			  
				<div class="loginmodal-container">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 h4 class="modal-title">Log In</h4><br>
				  <form name="form_login" method="post" action="login.php">
									<p>Username</p><input type="text" type="text" name="username" placeholder="Username"></p>
									<p>Password <input type="password" name="password" placeholder="Password"></p>
									<button class="btn btn-primary">Confirm</button>
									</form>
					
				  <div class="login-help">
				  </div>
				</div>
			</div>
		  </div>
<!-- fine Login-->
<!-- Modal -->
  <div class="modal fade" id="info-modal" role="dialog">
	<div class="modal-dialog">
	
	  <!-- Modal content con info sul Process Loader-->
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Information</h4>
		</div>
		<div class="modal-body">
			This page contains a list of every published process, the user can filter the processes according to their characteristics. The user an also download a process file or expressing a vote.
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	  </div>
	  
	  

	  
	  
	</div>
  </div>
  
  
  <!-- query Modal -->
  <div class="modal fade" id="info-modal2" role="dialog">
	<div class="modal-dialog">
			<div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Query tutorial</h4>
		</div>
		<div class="modal-body">
		
			<p>
			The text bar allows the user to make queries to search for results based on the metadata value.
			To be considered acceptable by the application, the queries must have the following syntax:
			</p>
			<p><i>
				[field name]: [value]
			</i></p>
			<p>
			for example, the query
			</p>
			<p>
			<i>
			Protocol: http
</i>
</p>
<p>
will return as a result the list of published processes that use the http protocol.
Queries can also use logical operators (AND, OR, NOT, &&, ||,!, *, \), for example the query
</p>
<p><i>
Category: geolocated AND format: csv
</i></p>
<p>
will return all public processes that have the format "csv" and "geolocated" as category.
</p>
<p>
If the user enters incorrect fields in the text search, it will be returned an error message and nothing result.
</p>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	  </div>
  
	 </div>
  </div>

  

<script type='text/javascript'>



$(document).ready(function(){  
 //funzione per mantenere i menù aperti
   $("div.control-group").each(function() {
		 var mycookie = $.cookie($(this).attr('id'));
		  if (mycookie ) {
		  $(this).css('display', mycookie);
			}
		  $.cookie($(this).attr("id"), $(this).css("display"));
	});
});


$(document).on('ready', function(){
	$('.rating-loading').rating({displayOnly: true, step: 0.1});
});
  
function agg_down($id,$down){        //funzione per aggiornare download
			$.ajax({
				type:'POST',
				url:'update_down.php',
				data: {id : $id, down: $down},
				success: function(data){
					location.reload(true);
				}
			});
}
		

$(document).ready(function () {
		var role_active = $("#role_att").text();
		if (role_active == 'ToolAdmin'){
			$('#sc_mng').show();
		}
		else {
			$('.span2').css('margin-top','200px');
		}

	   var array_act = new Array();
		$.ajax({
			
			url: "getdata.php",
				data: {action: "get_public_processes"},
				type: "GET",
				async: true, 
				dataType: 'json',
				
				success: function (data) {
					console.log(data);
					
					for (var i = 0; i < data.length; i++)
					{
						 array_act[i] = {
							 id: data[i].process['id'],
							 name: data[i].process['name'],
							 description: data[i].process['description'],
							 user: data[i].process['user'],
							 date: data[i].process['date'],
							 downloads: data[i].process['downloads']

								};
					var data1 = array_act[i]['date'];
					var data2 = data1.replace(':','-');
					var data3 = data2.replace(':','-');
					var data4 = data3.replace(' ','-');
					var us= array_act[i]['user'];
					var file_n =array_act[i]['name'];
					var file1 = file_n.split(".");					
					
					$("#storico2").append('<tr><td class="file_id" >'+array_act[i]['id']+'</td><td><a onclick="agg_down('+array_act[i]['id']+','+array_act[i]['downloads']+')" href="uploads/'+us+'/'+data4+'/'+file1[0]+'/'+array_act[i]['name']+' " class="file_archive_link">'+array_act[i]['name']+'</a></td><td>'+array_act[i]['description']+'</td><td class="file_id" >'+array_act[i]['user']+'</td><td class="file_id" >'+array_act[i]['downloads']+'</td></tr>');
					}
				}
		});
		
});
		
$(function(){
	$('.rating').on('rating.change', function(event, value) {
		id = $(this).attr('id');
		if (confirm('Are you sure you want to rate ' + value +' this file?')) {
			console.log(value);
			$.ajax({
				type:'POST',
				url:'update_votes.php',
				data: {id : id, value: value},
				success: function(data){
					alert("rating saved");
					location.reload();
				}
				
			});
  
		}
		else{location.reload();}
	});
		
});
		
function filterFunction(element) {
	checklist=document.getElementsByClassName("check");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
			}
			}
  				

			
	
	if(count==checklist.length){
		
		
					$.ajax({
		type:'POST',
		url:'session.php',	
				complete: function(event){
					
event.preventDefault();
					document.facet_form.submit();
					//document.submit_form.submit();
					
				}	
	});  
		
		
		document.cookie.split(";").forEach(function(c) {
					if (c.indexOf("PHPSESSID") < 0){
				 document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
			}

		});
		
		
	}

	document.facet_form.submit();
	document.submit_form.click();
	
};	

function apri_menu(element){
	if(element.getAttribute('id')=='find-u-n'){
		if(document.getElementById('control-group-u-n').style.display=='none'){
			document.getElementById('control-group-u-n').style.display='block';
			$.cookie('control-group-u-n', 'block');
			}
		else{
			document.getElementById('control-group-u-n').style.display='none';
			$.cookie('control-group-u-n','none');
			}
		}
		if(element.getAttribute('id')=='find-rt'){
			if(document.getElementById('control-group-rt').style.display=='none'){
				document.getElementById('control-group-rt').style.display='block';
				$.cookie('control-group-rt', 'block');
			}
			else{
				document.getElementById('control-group-rt').style.display='none';
				$.cookie('control-group-rt','none');
			}
		}
		if(element.getAttribute('id')=='find-ri'){
			if(document.getElementById('control-group-ri').style.display=='none'){
				document.getElementById('control-group-ri').style.display='block';
				$.cookie('control-group-ri', 'block');
			}
			else{
				document.getElementById('control-group-ri').style.display='none';
				$.cookie('control-group-ri','none');
			}
		}
		if(element.getAttribute('id')=='find-lic'){
			if(document.getElementById('control-group-lic').style.display=='none'){
				document.getElementById('control-group-lic').style.display='block';
				$.cookie('control-group-lic', 'block');
			}
			else{
				document.getElementById('control-group-lic').style.display='none';
				$.cookie('control-group-lic','none');
			}
		}
		if(element.getAttribute('id')=='find-per'){
			if(document.getElementById('control-group-per').style.display=='none'){
				document.getElementById('control-group-per').style.display='block';
				$.cookie('control-group-per', 'block');
			}
			else{
				document.getElementById('control-group-per').style.display='none';
				$.cookie('control-group-per','none');
			}
		}
		if(element.getAttribute('id')=='find-cat'){
			if(document.getElementById('control-group-cat').style.display=='none'){
				document.getElementById('control-group-cat').style.display='block';
				$.cookie('control-group-cat', 'block');
			}
			else{
				document.getElementById('control-group-cat').style.display='none';
				$.cookie('control-group-cat', 'none');
			}
		}	
		if(element.getAttribute('id')=='find-for'){
			if(document.getElementById('control-group-for').style.display=='none'){
				document.getElementById('control-group-for').style.display='block';
				$.cookie('control-group-for', 'block');
			}
			else{
				document.getElementById('control-group-for').style.display='none';
				$.cookie('control-group-for', 'none');
			}
		}	
}


 $('#reset-search').on('click',function(){
//function deleteCookies(){
	$.cookie('control-group-for','none');
	$.cookie('control-group-u-n','none');
	$.cookie('control-group-cat','none');
	$.cookie('control-group-ri','none');
	$.cookie('control-group-per','none');
	$.cookie('control-group-lic','none');
	$.cookie('control-group-rt','none');

	
	document.cookie.split(";").forEach(function(c) { 

			if (c.indexOf("PHPSESSID") < 0){
			document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
		   }
		});
		
		checklist=document.getElementsByTagName("input");
			for(j=0;j<checklist.length;j++){
				if(checklist[j].type=="checkbox"){
		checklist[j].checked=false;
				}
	}
		

	
		$.ajax({
			type:'POST',
			url:'session_clear.php',	
			complete: function(e){
			//e.preventDefault();
			document.facet_form.submit();					
			}	
		}); 

	//document.facet_form.submit();
	    $('#submit_form').click();

});


$('#reset-search2').on('click',function(){
//function deleteCookies(){
	$.cookie('control-group-for','none');
	$.cookie('control-group-u-n','none');
	$.cookie('control-group-cat','none');
	$.cookie('control-group-ri','none');
	$.cookie('control-group-per','none');
	$.cookie('control-group-lic','none');
	$.cookie('control-group-rt','none');

	
	document.cookie.split(";").forEach(function(c) { 
			if (c.indexOf("PHPSESSID") < 0){
			document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
		   }
		});
		
		checklist=document.getElementsByTagName("input");
			for(j=0;j<checklist.length;j++){
				if(checklist[j].type=="checkbox"){
		checklist[j].checked=false;
				}
	}
		

	
		$.ajax({
			type:'POST',
			url:'session_clear.php',	
			complete: function(e){
			//e.preventDefault();
			document.facet_form.submit();					
			}	
		}); 

	//document.facet_form.submit();
	    $('#submit_form').click();

});

document.getElementById("facet_form").addEventListener("submit", myFunction);

function myFunction() {
    //alert("The form was submitted");
}

//funzioni definite sui vari check che rispondono all'evento "change"
$('.check_user').change(function()
 {
	checklist=document.getElementsByClassName("check_user");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		<?php
		unset($_SESSION['aUser']);
		unset($_SESSION['queryutente']);
		?>
		/*
		$.ajax({
			type:'POST',
			url:'./sessions/session_user.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
		*/
	}
    $('#submit_form').click();
});


$('.check_cat').change(function()
 {
	checklist=document.getElementsByClassName("check_cat");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
			<?php
			unset($_SESSION['aCat']);
			unset($_SESSION['querycategoria']); 
		?>
		/*
		$.ajax({
			type:'POST',
			url:'./sessions/session_category.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		});
		*/		
	}
    $('#submit_form').click();
});
 
 
 
 $('.check_format').change(function()
 {
	checklist=document.getElementsByClassName("check_format");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		<?php
				unset($_SESSION['aFormat']);
				unset($_SESSION['queryformato']);
			?>
		/*
		$.ajax({
			type:'POST',
			url:'./sessions/session_format.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		});
		*/		
	}
    $('#submit_form').click();
});


$('.check_ri').change(function()
 {
	checklist=document.getElementsByClassName("check_ri");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		<?php
			unset($_SESSION['aResource']);
			unset($_SESSION['queryrisorsa']);
		?>
		/*
		$.ajax({
			type:'POST',
			url:'./sessions/session_ri.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
		*/
	}
    $('#submit_form').click();
});
 
 
 $('.check_lic').change(function()
 {
	checklist=document.getElementsByClassName("check_lic");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		<?php
				unset($_SESSION['aLicence']);
				unset($_SESSION['querylicenza']);
		?>
		/*
		$.ajax({
			type:'POST',
			url:'./sessions/session_lic.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
		*/
	}
    $('#submit_form').click();
});


$('.check_rt').change(function()
 {
	checklist=document.getElementsByClassName("check_rt");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		/*
		$.ajax({
			type:'POST',
			url:'./sessions/session_rt.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
		*/
		<?php
			unset($_SESSION['aRealtime']);
			unset($_SESSION['queryrealtime']);
		?>
	}
    $('#submit_form').click();
});


$('.check_per').change(function()
 {
	checklist=document.getElementsByClassName("check_per");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		<?php
			unset($_SESSION['aPeriodic']);
			unset($_SESSION['queryperiodic']);
		?>	
		/*
		$.ajax({
			type:'POST',
			url:'./sessions/session_periodic.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
		*/
	}
    $('#submit_form').click();
});
	

</script>
</body>
</html>