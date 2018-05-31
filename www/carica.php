<?php
include('config.php'); // Includes Login Script

   $descrizione = $_POST['filedesc'];
   $tipo = $_POST['filetype'];
    $percorso = $_FILES['userfile']['tmp_name'];
    $nome = $_FILES['userfile']['name'];
	//print ($nome);
	//prova informazioni
	$data1=date('Y-m-d');
   $time2=date('H'); 
	//
	$utente = $_SESSION['id'];
	$utente_us = $_SESSION['username'];
	$ext = explode(".", $nome);
	$ext = $ext[count($ext)-1];
  
  //cartella
  //$cartella = 'uploads/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/'.$base.'/';
  //
	if($ext == 'zip'){
		//ETL
	if ($tipo=="ETL"){
	//
	$base= basename($nome,".zip");
	//cartella
	$cartella = 'uploads/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/'.$base.'/';
	$creaCartella=mkdir($cartella, 0777, true);
	//
	
	if (move_uploaded_file($percorso, $cartella . $nome))
		{
			print 'Upload eseguito con successo<br>';
			$data=date('Y-m-d H:i:s');
			$query_zip="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status)VALUES (NULL,'".$nome."','".$descrizione."','0','".$data."','".$tipo."','NO','".$utente_us."')";
			$query_caricamento = mysqli_query($connessione_al_server,$query_zip) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
				$zip = new ZipArchive;
				if ($zip->open($cartella.'/'.$nome) === TRUE) {
					//$zip->extractTo('jobs/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/'.$base.'/','/Ingestion/'.'main.kjb');
					$zip->extractTo('jobs/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/');
					$zip->close();
					//verificare se effettivamente è presente un file main.kjb
					echo 'ok, il file '.$nome.' è stato carcinato nella cartella:   '.'jobs/'.$_SESSION['username'].'/'.$data.'/';
					//inviare il nome del file come se fosse una vabile GET a param_process.php
					$_SESSION['nome_file'] = $base;
					$_SESSION['file_zip'] = $nome;
					$_SESSION['data_zip']= $data;
					//Modifica stato database
					$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='OK - ".date('Y-m-d H:i:s')."' WHERE `File_name`='".$nome."' AND `Creation_date`='".$data."'";
					$result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
					//
					header("location:file_archive.php");
					//reindirizzato//
			} else {
					$sql2 = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='ERRORE VERIFICA' WHERE `File_name`='".$nome."' AND `Creation_date`='".$data."'";
					$result2 = mysqli_query($connessione_al_server, $sql2) or die(mysqli_error($connessione_al_server));
					print "Il file 'main.kjb' non è presente";
					header("location:upload.php");
			}

    }
    else
    {
        print "Si sono verificati dei problemi durante l'Upload di ETL";
	print_r($_FILES);
    }
	
	} else if ($tipo=="R") {
		//Formalismo R
		//
		$base= basename($nome,".zip");
		//cartella
		$cartella = 'uploads/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/'.$base.'/';
		$creaCartella=mkdir($cartella, 0777, true);
		//Formalismo Java
		if (move_uploaded_file($percorso, $cartella . $nome))
		{
			print 'Upload eseguito con successo<br>';
			$data=date('Y-m-d H:i:s');
			$query_zip="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status)VALUES (NULL,'".$nome."','".$descrizione."','".$utente."','".$data."','".$tipo."','NO')";
			$query_caricamento = mysqli_query($connessione_al_server,$query_zip) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
				$zip = new ZipArchive;
				if ($zip->open($cartella.'/'.$nome) === TRUE) {
					//$zip->extractTo('jobs/'.$_SESSION['username'].'/'.$data.'/'.$base.'/','main.kjb');
					$zip->extractTo('jobs/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/');
					$zip->close();
					//verificare se effettivamente è presente un file main.kjb
					echo 'ok, il file '.$nome.' è stato carcinato nella cartella:   '.'jobs/'.$_SESSION['username'].'/'.$data.'/';
					//inviare il nome del file come se fosse una vabile GET a param_process.php
					$_SESSION['nome_file'] = $base;
					$_SESSION['file_zip'] = $nome;
					$_SESSION['data_zip']= $data;
					//Modifica stato database
					$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='OK - ".date('Y-m-d H:i:s')."' WHERE `File_name`='".$nome."' AND `Creation_date`='".$data."'";
					$result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
					//
					header("location:file_archive.php");
					//reindirizzato//
			} else {
					$sql2 = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='ERRORE VERIFICA' WHERE `File_name`='".$nome."' AND `Creation_date`='".$data."'";
					$result2 = mysqli_query($connessione_al_server, $sql2) or die(mysqli_error($connessione_al_server));
					print "Il file 'main.kjb' non è presente";
					header("location:upload.php");
			}

    } else
    {
        print "Si sono verificati dei problemi durante l'Upload di R";
	print_r($_FILES);
    }

	} else if ($tipo=="Java") {
		//
		$base= basename($nome,".zip");
		//cartella
		$cartella = 'uploads/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/'.$base.'/';
		$creaCartella=mkdir($cartella, 0777, true);
		//Formalismo Java
		if (move_uploaded_file($percorso, $cartella . $nome))
		{
			print 'Upload eseguito con successo<br>';
			$data=date('Y-m-d H:i:s');
			$query_zip="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status)VALUES (NULL,'".$nome."','".$descrizione."','".$utente."','".$data."','".$tipo."','NO')";
			$query_caricamento = mysqli_query($connessione_al_server,$query_zip) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
				$zip = new ZipArchive;
				if ($zip->open($cartella.'/'.$nome) === TRUE) {
					//$zip->extractTo('jobs/'.$_SESSION['username'].'/'.$data.'/'.$base.'/','main.kjb');
					$zip->extractTo('jobs/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/');
					$zip->close();
					//verificare se effettivamente è presente un file main.kjb
					echo 'ok, il file '.$nome.' è stato carcinato nella cartella:   '.'jobs/'.$_SESSION['username'].'/'.$data.'/';
					//inviare il nome del file come se fosse una vabile GET a param_process.php
					$_SESSION['nome_file'] = $base;
					$_SESSION['file_zip'] = $nome;
					$_SESSION['data_zip']= $data;
					//Modifica stato database
					$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='OK - ".date('Y-m-d H:i:s')."' WHERE `File_name`='".$nome."' AND `Creation_date`='".$data."'";
					$result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
					//
					header("location:file_archive.php");
					//reindirizzato//
			} else {
					$sql2 = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='ERRORE VERIFICA' WHERE `File_name`='".$nome."' AND `Creation_date`='".$data."'";
					$result2 = mysqli_query($connessione_al_server, $sql2) or die(mysqli_error($connessione_al_server));
					print "Il file 'main.kjb' non è presente";
					header("location:upload.php");
			}

    } else
    {
        print "Si sono verificati dei problemi durante l'Upload di JAVA";
	print_r($_FILES);
    }

	}
	else {
		header("location:upload.php");
	}
	//
	}
	//
	else {
	echo('<div>Il file caricato non è uno zip! Cliccare <a href="upload.php">Qui</a> per rirovare il caricamento!</div>');
	//print "Il file non è uno zip";
	//print_r($_FILES);
	}
?>