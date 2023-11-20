<?php
	header("Access-Control-Allow-Origin: *\r\n");
	include('../../config.php'); // mi prendo i valori per la connessione: host username password 
	include('../../curl.php');
	
	$suri = $_GET["suri"]; //mi prendo il service uri come parametro dalla dall'uri
   	$response = []; //inizializzo la response da ritornare quando faccio la chiamata
   	$myArray = array(); //inizializzo se trovo valori
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
    }else{
	    //faccio la query
		$sql = "SELECT * FROM processloader_db.bigdatafordevice where suri= '$suri' order by dateObserved desc ";
		$result = mysqli_query($link, $sql);
		if(false === $result) { // se non va a buon fine
	    	echo "Errore: impossibile eseguire la query. " . mysqli_error($conn);
	    	$response['result'] = 'KO';
			$response['code'] = 500;
			$response['message'] = 'DB query error';
		}else{ //altrimenti metto tutto in myArray e lo restituisco come json encoded
			while($row = $result->fetch_assoc()) {
	    		$myArray[] = $row;
			}
			echo json_encode($myArray);
			$response['result'] = 'ok';
			$response['code'] = 200;
			$response['message'] = json_encode($myArray);
		}
		// chiudo la connessione
		mysqli_close($link);
	}
?>

