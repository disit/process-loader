<?php
	header("Access-Control-Allow-Origin: *\r\n");
	include('../../config.php');  // mi prendo i valori per la connessione: host username password 
	include('../../curl.php');
	require ('../../sso/autoload.php');
	use Jumbojett\OpenIDConnectClient;	

$accessToken = null;
$loggedUsername = null;
$loggedRole = null;
//

if(isset($_REQUEST['accessToken'])) {
	//echo($_GET['accessToken']);
	$accessToken = $_GET['accessToken'];
	//echo($oicd_address);
	$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);

	$oidc->setVerifyHost(false);
	$oidc->setVerifyPeer(false);
	$oidc->setAccessToken($accessToken);

	$oidc->providerConfigParam(array('authorization_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/auth'));
	$oidc->providerConfigParam(array('token_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
	$oidc->providerConfigParam(array('userinfo_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/userinfo'));
	$oidc->providerConfigParam(array('jwks_uri'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/certs'));
	$oidc->providerConfigParam(array('issuer'=>$oicd_address.'/auth/realms/master'));
	$oidc->providerConfigParam(array('end_session_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/logout'));

	//$oidc->addScope(array('openid','username','profile'));
    //$oidc->setRedirectURL($appUrl . '/ssoLogin.php');
	try {
		$requestUserInfo = $oidc->requestUserInfo("preferred_username");
		if($requestUserInfo  !== null){

	$suri = $_GET["suri"]; //mi prendo il service uri come parametro dalla dall'uri
	$data = file_get_contents('php://input'); //e anche i dati nel body
	$encoded_data = json_encode($data); 
	$response = []; //inizializzo la response da ritornare quando faccio la chiamata

	// Controllo se il parametro URI � presente
	if ($suri === null || $suri === "") {
		$response['result'] = 'KO';
		$response['code'] = 400;
		$response['message'] = 'Missing parameter: suri';
		echo json_encode($response);
		exit();
	}else{
		// Controllo se i dati nel body non sono vuoti
		if (empty($encoded_data) || $encoded_data === "" || $encoded_data === null ) {
			$response['result'] = 'KO';
			$response['code'] = 400;
			$response['message'] = 'Empty request data';
			echo json_encode($response);
			exit();
		}else{
			//mi connetto al db
			$link =  mysqli_connect($host, $username, $password);
			mysqli_select_db($link, "processloader_db");

			// se la connessione non � andata a buon fine
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
				$dateObserved = '';
					if(isset($_REQUEST['dateObserved'])) {
						if (($_REQUEST['dateObserved'] != null)&&($_REQUEST['dateObserved'] !== '')){
							$dateObserved = $_REQUEST['dateObserved'];
						}
						
					}   	
		    	//faccio la query
				
				if (isJsonWithElement($data, "grandidati")) {
					//echo "L'elemento 'grandidati' esiste nel JSON.";
					if($dateObserved !=''){
						$sql = "INSERT INTO processloader_db.bigdatafordevice (`suri`, `dateObserved`, `data`) VALUES ('$suri', '$dateObserved', $encoded_data);";
					}else{
						$sql = "INSERT INTO processloader_db.bigdatafordevice (`suri`, `dateObserved`, `data`) VALUES ('$suri', now(), $encoded_data);";
					}
					////
						$result = mysqli_query($link, $sql);
						if(false === $result) { // se non va a buon fine la query
							//echo "Errore: impossibile eseguire la query. " . mysqli_error($conn);
							$response['result'] = 'KO';
							$response['code'] = 500;
							$response['message'] = 'DB query error';
						}else{ //altrimenti tutto ok
							$response['result'] = 'ok';
							$response['code'] = 200;
							$response['message'] = "ok query executed";    	
						}	
						mysqli_close($link);
						echo json_encode($response); 
						exit();
			} else {
				//echo "L'elemento 'grandidati' non esiste nel JSON o il JSON non è valido.";
				//echo($encoded_data);
				$response['result'] = 'ok';
				$response['code'] = 500;
				$response['message'] = "Not correct data";
				mysqli_close($link);
				exit();	 
				///////////
			}
				
		    }
		

		}
	}

}else{
	$response['result'] = 'KO';
				$response['code'] = 401;
				$response['message'] = 'Not found user: '.$requestUserInfo; // chiudo la connessione
    			echo json_encode($response);
}

} catch(Exception $ex) {
	$response['result'] = 'Error';
	$response['code'] = 404;
	$response['message'] = $ex; // chiudo la connessione
	echo json_encode($response); //mando il messaggio di errore
}

}else{
	$response['result'] = 'KO';
		$response['code'] = 401;
		$response['message'] = 'Not authorized'; // chiudo la connessione
    	echo json_encode($response); //mando il messaggio di errore
}

function isJsonWithElement($encoded_data1, $element) {
    // Decodifica il JSON
    $decoded_data = json_decode($encoded_data1, true);
    
    // Controlla se la decodifica è riuscita e se l'elemento esiste
    if (json_last_error() === JSON_ERROR_NONE && isset($decoded_data[$element])) {
        return true;
    } else {
        return false;
    }
}    
?>
