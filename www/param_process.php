<!DOCTYPE html>
<?php
include('processform.php'); // Includes Login Script

if (isset ($_SESSION['username'])){
  $utente_att = $_SESSION['username'];	
}else{
 $utente_att= "Login";	
}
?>

<html lang="en">
<head>
  <title>Process Loader</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
  <script type="text/javascript" src="/lib/zip.js"></script>
      <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/loader.css" rel="stylesheet">
</head>
<body>

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
        <li><a href="#" id="login"> <span aria-hidden="true" data-toggle="modal" data-target="#login-modal"><?=$utente_att;?></span></a></li>
                <li id="logout" hidden><a href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<!--Modal Login -->
  
  
<div class="container-fluid text-center">    
  <div class="row content">
    <div class="col-sm-2 sidenav">
    <p><h3>Operazioni</h3></p>
    <p><a href="Process_loader.php">Elenco Processi</a></p>
    <p><a href="upload.php">Carica Nuovo Processo</a></p>
	<p><a href="job_type.php">Job Types</a></p>
    <p><a href="file_archive.php">Visualizza file caricati</a></p>
    <p><a href="archive.php">Archivio Storico</a></p>
    </div>
    <div class="col-sm-8 text-left">

<!-- Button-->
<!-- Trigger the modal with a button -->
<!-- FIne Button-->
<div>
<h2>Creazione Job type</h2>
<div>
<!--Modal Creazione di un nuovo processo-->
<!-- Modal -->
<!-- LoginModal-->
<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    	  <div class="modal-dialog">
              
				<div class="loginmodal-container">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Effettua il login</h4><br>
				  <form>
					<input type="text" name="user" placeholder="Username">
					<input type="password" name="pass" placeholder="Password">
					<input type="submit" name="login" class="btn btn-primary" value="Login">
				  </form>		
				  <div class="login-help">
					<a href="#">Register</a> - <a href="#">Forgot Password</a>
				  </div>
				</div>
			</div>
		  </div>
<!-- fine login-->
<!-- Fine modal creazione processo -->
<!-- Parametri processi 1-->
<div id="Elenco_processi">
     	<form method="post" action="job_type_creation.php">
		<div class="form-parametri" id="Process_0">
		<div class="panel panel-primary">
		<div class="panel-heading">Parametri <?=$_SESSION['nome_file'];?> </div>
		<ul class="nav nav-tabs">
		<li><a data-toggle="tab" href="#job_parameter" class="active" color="#357ae8">Parametri Processo</a></li>
		<li><a data-toggle="tab" href="#job_trigger" color="#357ae8">Trigger</a></li>
		<li><a data-toggle="tab" href="#job_advanced" color="#357ae8">Parametri avanzati</a></li>
		</ul>
		<div class="panel-body"><div class="alert alert-success" hidden>Caricamento processo avvenuto con successo.</div>
		<div class="alert alert-danger" hidden> Errore! Il processo non è stato caricato correttamente.</div>
		<div class="tab-content">
		<div id="job_parameter" class="tab-pane fade in active">
				<div class="input-group"><span class="input-group-addon">Nome: </span><input id="nome" type="text" class="form-control" name="nome"></div>
				<br />
				<div class="input-group"><span class="input-group-addon">Descrizione: </span>
				<input id="descrizione" type="text" class="form-control" name="descrizione">
				</div><br />
				<div class="input-group"><span class="input-group-addon">Gruppo: </span>
				<input id="gruppo" type="text" class="form-control" name="gruppo">
				</div><br />		
				<div class="input-group"><span class="input-group-addon">Url: </span>
				<input id="url" type="text" class="form-control" name="url">
				</div><br />
				<div class="input-group"><span class="input-group-addon">Path: </span>
				<input id="path" type="text" class="form-control" name="path"></div><br />
				<div class="input-group"><span class="input-group-addon">type: </span>
				<select name="type" class="selectpicker form-control" name="type">
					<option value="REST">REST</option>
					<option value="ProcessExecutor">Process Executor</option>
				</select>
				</div><br />
				<div class="input-group"><span class="input-group-addon">E-mail: </span>
				<input id="email" type="text" class="form-control" name="email"><br />
				</div><br />
				<div class="input-group"><span class="input-group-addon">Time Out: </span>
				<input id="time_out" type="text" class="form-control" name="time_out">
				</div><br />
				<div class="input-group">
					<span class="checkbox-inline"><input id="conc" type="checkbox" name="conc"  checked="checked">non Concurrent</span>
				</div><br />
				<div class="input-group">
					<span class="checkbox-inline"><input id="store" type="checkbox" name="store"  checked="checked">Store Durably</span>
				</div><br />
				<div class="input-group">
					<span class="checkbox-inline"><input id="recovery" type="checkbox" name="recovery" checked="unchecked">Request Recovery</span>
				</div><br />
				
			</div>
			<div id="job_trigger" class="tab-pane fade">
						<div class="input-group"><span class="input-group-addon">Nome Trigger: </span><input id="nome_trig" type="text" class="form-control" name="nome_trig"></div><br />
						<div class="input-group"><span class="input-group-addon">Descrizione Trigger: </span><input id="descrizione_trig" type="text" class="form-control" name="descrizione_trig"></div><br />
						<div class="input-group"><span class="input-group-addon">Gruppo Trigger: </span><input id="gruppo_trig" type="text" class="form-control" name="gruppo_trig"></div><br />	
						<div class="input-group"><span class="input-group-addon">Repeat Count: </span><input id="repeat_trig" type="text" class="form-control" name="repeat_trig"></div><br />
						<div class="input-group"><span class="input-group-addon">Interval: </span><input id="interval_trig" type="text" class="form-control" name="interval_trig" value="<?=$_SESSION['data_zip'];?>"></div><br />	
						<div class="input-group"><span class="input-group-addon">Start Time: </span><input id="start" type="text" class="form-control" name="start" value="<?=$_SESSION['data_zip'];?>"></div><br />
						<div class="input-group"><span class="input-group-addon">End Time: </span><input id="end" type="text" class="form-control" name="end" value="<?=$_SESSION['data_zip'];?>"></div><br />
					    <div class="input-group"><span class="input-group-addon">Priority: </span><input id="priority" type="text" class="form-control" name="priority"></div><br />
						<div class="input-group"><span class="input-group-addon">Misfire Instruction: </span>
						<select id="misfire" name="misfire" class="selectpicker form-control">
						<option value="DEFAULT">DEFAULT</option>
						<option value="IGNORE_MISFIRE_POLICY">IGNORE_MISFIRE_POLICY</option>
						<option value="FIRE_NOW">FIRE_NOW</option>
						<option value="RESCHEDULE_NOW_WITH_EXISTING_REPEAT_COUNT">RESCHEDULE_NOW_WITH_EXISTING_REPEAT_COUNT</option>
						<option value="RESCHEDULE_NOW_WITH_REMAINING_REPEAT_COUNT">RESCHEDULE_NOW_WITH_REMAINING_REPEAT_COUNT</option>
						<option value="RESCHEDULE_NEXT_REMAINING_COUNT">RESCHEDULE_WITH_NEXT_REMAINING_COUNT</option>
						<option value="RESCHEDULE_NEXT_EXISTING_COUNT">RESCHEDULE_WITH_NEXT_EXISTING_COUNT</option>
						</select></div><br />					
			</div>
					<div id="job_advanced" class="tab-pane fade">	
							 <ul class="list-group">
								<li class="list-group-item">
								<h4 class="add">Add Data Map</h4>
								<div class="input-group">
								<input id="dataMap_k" type="text" name="dataMap_k" placeholder="key">
								<input id="dataMap_v" type="text" name="dataMap_v" placeholder="value">
									
								<span class="glyphicon glyphicon-remove" data-toggle="modal" data-target="#"></span>
								</div>
								</li>
								<li class="list-group-item">
								<h4 class="add">Add Next Job</h4>
								<div class="input-group">
										<span>IF RESULT </span>
										<select name="par_next_job_cond">
										<option value="=">==</option>
										<option value="!=">!=</option>
										<option value="<"><</option>
										<option value=">">></option>
										<option value="<="><=</option>
										<option value=">=">>=</option>
										</select> 
										<span>THEN TRIGGER</span><input id="next_job_result" type="text" name="next_job_result" placeholder="result">
										<input id="next_job_n" type="text" name="next_job_n" placeholder="job name">
										<input id="next_job_g" type="text" name="next_job_g" placeholder="job group">	
										<span class="glyphicon glyphicon-remove" data-toggle="modal" data-target="#"></span>
										</div>
								</li>
								<li class="list-group-item">			
								<h4 class="add">Add Process Parameter</h4>
								<div class="input-group">
								<input id="process_par_k" type="text" name="process_par_k" placeholder="key">
								<input id="process_par_v" type="text" name="process_par_v" placeholder="value">	
								<span class="glyphicon glyphicon-remove" data-toggle="modal" data-target="#"></span>
								</div>
								</li>
								<li class="list-group-item">			
								<h4 class="add">Add Job Constraint</h4>
								<div class="input-group">
								<input id="job_cons_k" type="text" name="job_cons_k" placeholder="key">
								<select name="job_cons_cond">
								<option value="=">==</option>
								<option value="!=">!=</option>
								<option value="<"><</option>
								<option value=">">></option>
								<option value="<="><=</option>
								<option value=">=">>=</option>
								</select>
								<input id="job_cons_v" type="text" name="job_cons_v" placeholder="value">	
								<span class="glyphicon glyphicon-remove" data-toggle="modal" data-target="#"></span>
								</div>
								</li>
							</ul> 
					</div>		
			</div>
		</div>

	
		<div class="panel-footer">
		<div class="form-group">
		<input type="submit" name="Crea_job_type" class="btn btn-primary" value="Conferma">
		</div>
		</div>
		</div>
		</div>
		</form>
      </div>
  </div>
</div>
</div>
</div>
<!-- Parametri processi 2-->
<!-- Fine modal creazione processo -->
<script type='text/javascript'>
    $(document).ready(function () {
		/*
		//INSERIRE UN AJAX CHE ESTRAGGA I DATI DEI FILE NEL PROCESSO.	
		var numero=2;
	   for (var i = 0; i < numero; i++){
		   i2 = i+1;
		 //  $("#Elenco_processi").append('<form name="param_login_'+i+'" method="post" action="carica_processo.php"><div class="form-parametri" id=Process"_'+i+'"><div class="panel panel-primary"><div class="panel-heading">Parametri processo '+i+' ('+i2+'/'+numero+') </div><div class="panel-body"><div class="alert alert-success" hidden>Caricamento processo avvenuto con successo.</div><div class="alert alert-danger" hidden> Errore! Il processo non è stato caricato correttamente.</div><div class="input-group"><span class="input-group-addon">Nome: </span><input id="nome" type="text" class="form-control" name="nome"></div><br /><div class="input-group"><span class="input-group-addon">Descrizione: </span><input id="descrizione" type="text" class="form-control" name="descrizione"></div><br /><div class="input-group"><span class="input-group-addon">Gruppo: </span><input id="gruppo" type="text" class="form-control" name="gruppo"></div><br /><div class="input-group"><span class="input-group-addon">Url: </span><input id="url" type="text" class="form-control" name="url"></div><br /><div class="input-group"><span class="input-group-addon">Path: </span><input id="path" type="text" class="form-control" name="path"></div><br /><div class="input-group"><span class="input-group-addon">type: </span><input id="type" type="text" class="form-control" name="type"></div><br /><div class="input-group"><span class="input-group-addon">Start Time: </span><input id="start" type="text" class="form-control" name="start"></div><br /><div class="input-group"><span class="input-group-addon">End Time: </span><input id="end" type="text" class="form-control" name="end"></div><br /><div class="input-group"><span class="input-group-addon">Priority: </span><input id="priority" type="text" class="form-control" name="priority"></div><br /><div class="input-group"><span class="checkbox-inline"><input id="conc" type="checkbox" name="conc">Concurrent</span><span class="checkbox-inline"><input id="store" type="checkbox" name="store">Store Durably</span><span class="checkbox-inline"><input id="recovery" type="checkbox" name="recovery">Request Recovery</span></div></div><div class="panel-footer"><div class="form-group"><input type="submit" name="confirm" class="btn btn-primary" value="Conferma"></div></div></div></div></form>');
	   }
       //EVENTO BUTTON
	  */
	  /*
	if(isset($_SESSION['nome_file'])){
		$('#Elenco_processi').show();
	}
	*/
    });
</script>
</body>
</html>