<!DOCTYPE html>
<?php
include('config.php'); // Includes Login Script
include('control.php');
//
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

$errore ="";
if (isset($_REQUEST['errore'])){
	$errore =$_REQUEST['errore'];
}
?>

<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Process Loader</title>
		
		<!-- jQuery -->
        <script src="jquery/jquery-1.10.1.min.js"></script>

		<!-- Bootstrap Core JavaScript -->
        <script src="bootstrap/bootstrap.min.js"></script>
			
        <!-- Bootstrap Core CSS -->
        <link href="bootstrap/bootstrap.css" rel="stylesheet">

        <!-- Bootstrap toggle button -->
        <link href="bootstrapToggleButton/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="bootstrapToggleButton/js/bootstrap-toggle.min.js"></script>
       
       <!-- Dynatable -->
       <link rel="stylesheet" href="dynatable/jquery.dynatable.css">
       <script src="dynatable/jquery.dynatable.js"></script>
        
       <!-- Font awesome icons -->
        <link rel="stylesheet" href="fontAwesome/css/font-awesome.min.css">
        
        <!-- Custom CSS -->
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/dashboardList.css" rel="stylesheet">
        
    </head>
<body class="guiPageBody">
<div class="container-fluid">
<div class="row mainRow" style='background-color: rgba(138, 159, 168, 1)'>
		<?php include "mainMenu.php" ?>
		<div class="col-xs-12 col-md-10" id="mainCnt">
					<div class="row hidden-md hidden-lg">
                        <div id="mobHeaderClaimCnt" class="col-xs-12 hidden-md hidden-lg centerWithFlex">
                            Snap4City
                        </div>
                    </div>
					<div class="row">
                        <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt">Process Loader: Upload New File</div>
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php include "mobMainMenu.php" ?></div>
                    </div>
					<div class="row" hidden>
		<div class="col-xs-12">	
		<button data-toggle="collapse" class="btn btn-primary" data-target="#demo">Info <i class="fa fa-info-circle"></i></button>
		</div>		
	</div>
					<div class="row">
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1)'>
					<!--<h2>Upload New File	<span class="fa fa-info-sign" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;">	 <span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></h2>-->
	
 <!--Spiegazione su cosa deve fare l'utente--> 
<div id="demo" class="collapse">
    <div class="panel panel-primary">
      <div class="panel-heading">Advice</div>
      <div class="panel-body">Use the "Upload" command to load a new file into the application.
The files you want to upload have to be in .zip format.
<br /><br />
Files in any other format will not be accepted by the application.
<br />
		<br />
		For each of the types file there is a specific formalism that defines how a file should be structured to be accepted by the application:
		<ul>
			<li>In the case of ETL files, the files must have a directory named Ingestion and a file called Main.kjb.</li>
			<li>In the case of JAR files, files must have a file named Main.java.</li>
			<li>In the case of R files, the files must have a file named Main.R.</li>
		</ul>
	</div>
</div> 
</div>
	<!-- -->
<!--Form Loader-->
<div class="col-xs-12 col-md-6 col-md-offset-3" id="loginFormContainer">
<form enctype="multipart/form-data" action="caricaZip.php" method="POST" accept-charset="UTF-8">
<!--<form enctype="multipart/form-data" action="carica2.php" method="POST">-->
					<div class="col-xs-12" id="loginFormTitle" class="centerWithFlex">
                               Upload new file
                            </div>
    <!-- MAX_FILE_SIZE deve precedere campo di input del nome file -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000000" />
    <!-- Il nome dell'elemento di input determina il nome nell'array $_FILES -->
	<div>
   <!-- <p>Send this file: </p>-->
	<input name="userfile" type="file" class="btn btn-secondary" accept="application/zip"/>
	</div>
	<br />
	<div>
	<div class="input-group"><span class="input-group-addon">Description : </span><input name="filedesc" type="text" class="form-control"/></div>
	</div>
	<br />
	<div>
	<!--<p>Tipo file :   <p><input name="filetype" type="text"  class="form-control"/>-->
	<div class="input-group"><span class="input-group-addon">File Type:</span><select name="filetype" class="selectpicker form-control" id="filetype">
					<option value="AMMA">AMMA</option>
					<!--<option value="Banana Library">Banana Library</option>-->
					<option value="DevDash">DevDash</option>
					<option value="ETL">ETL</option>
					<option value="Java">Jar</option>
					<option value="Microservice">Microservice</option>
					<option value="Mobile App">Mobile App</option>
					<option value="NodeRed Application">NodeRed Application</option>
					<option value="NodeRed Library">NodeRed Library</option>
					<option value="R">R</option>
					<option value="ResDash">ResDash</option>
				</select></div>
	</div>
	<br />
	<div>
    <input type="submit" value="Send File" class="btn btn-primary"/>
	</div>
</form>
</div>
<!--Fine Form-->


<!-- 
  
  
<div class="container-fluid text-center">    
  <div class="row content">
    
    <div class="col-sm-8 text-left">

<!-- Button-->
<!-- Trigger the modal with a button -->
<!-- FIne Button-->

		</div>
		</div>
		</div>
<!--Modal Login -->
</div>
</div>
<!-- Fine modal creazione processo -->
<!-- Fine modal creazione processo -->
<script type='text/javascript'>
 var errore = "<?=$errore; ?>";
	$(document).ready(function () {
		utente_attivo=$("#utente_att").text();
		
	if (utente_attivo=='Login'){
		$(document).empty();
		}
		
	var role_active = $("#role_att").text();
	if (role_active == 'ToolAdmin'){
		$('#sc_mng').show();
	}
	
	//
	if(errore =="no_zip"){
		alert("THIS FILE IS NOT A ZIP ARCHIVE!");
	}
	else if(errore =="errore_caricamento"){
		alert("Error occurred during file loading, please try again!");
	}
	//
	});

</script>
</body>
</html>
