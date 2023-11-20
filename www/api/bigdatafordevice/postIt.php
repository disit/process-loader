<?php
	header("Access-Control-Allow-Origin: *\r\n");
	include('../../config.php');  // mi prendo i valori per la connessione: host username password 
	include('../../curl.php');

	$suri = $_GET["suri"]; //mi prendo il service uri come parametro dalla dall'uri
	$data = file_get_contents('php://input'); //e anche i dati nel body
	$encoded_data = json_encode($data); 
	$response = []; //inizializzo la response da ritornare quando faccio la chiamata

	// Controllo se il parametro URI è presente
	if ($suri === null || $suri === "") {
		$response['result'] = 'KO';
		$response['code'] = 400;
		$response['message'] = 'Missing parameter: suri';
		echo json_encode($response);
		exit();
	}else{
		// Controllo se i dati nel body non sono vuoti
		if (empty($encoded_data) || $encoded_data === "") {
			$response['result'] = 'KO';
			$response['code'] = 400;
			$response['message'] = 'Empty request data';
			echo json_encode($response);
			exit();
		}else{
			//mi connetto al db
			$link =  mysqli_connect($host, $username, $password);
			mysqli_select_db($link, "processloader_db");

			// se la connessione non è andata a buon fine
			if($link == false){
				$response['result'] = 'KO';
				$response['code'] = 500;
				$response['message'] = 'DB Connection error';
				mysqli_close($link); // chiudo la connessione
		    	echo json_encode($response); //mando il messaggio di errore
		    	exit();
		    }else{
		    	// Escape dei dati per evitare SQL injection
				$suri = mysqli_real_escape_string($link, $suri);
				//$encoded_data = mysqli_real_escape_string($link, $encoded_data);    	

		    	//faccio la query
				$sql = "INSERT INTO processloader_db.bigdatafordevice (`suri`, `dateObserved`, `data`) VALUES ('$suri', now(), $encoded_data);";
				$result = mysqli_query($link, $sql);
				if(false === $result) { // se non va a buon fine la query
			    	echo "Errore: impossibile eseguire la query. " . mysqli_error($conn);
			    	$response['result'] = 'KO';
					$response['code'] = 500;
					$response['message'] = 'DB query error';
				}else{ //altrimenti tutto ok
					$response['result'] = 'ok';
					$response['code'] = 200;
					$response['message'] = "ok query andata";    	
				}	
				mysqli_close($link);
				exit();	
		    }

		}
	}

	
	    
?>
