<?php
        header("Access-Control-Allow-Origin: *");
        include('../../config.php'); // mi prendo i valori per la connessione: host username password
        include('../../curl.php');
        require ('../../sso/autoload.php');
        use Jumbojett\OpenIDConnectClient;

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

///////////
$accessToken = null;
$loggedUsername = null;
$loggedRole = null;
//

if(isset($_GET['accessToken'])) {
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
                if($requestUserInfo == null){
                    $requestUserInfo = $oidc->requestUserInfo("username");
                }
                $requestUserInfo_string= json_encode($requestUserInfo);
                //var_dump($requestUserInfo);
                if($requestUserInfo  !== null){
///////////////////
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
                                $dateObserved = '';
                                if(isset($_GET['dateObserved'])) {
                                        if (($_GET['dateObserved'] != null)&&($_GET['dateObserved'] !== '')){
                                                $dateObserved = $_GET['dateObserved'];
                                        }

                                }

                                if($dateObserved !=''){
                                        $sql = "SELECT * FROM processloader_db.bigdatafordevice where suri= '$suri' and dateObserved = '$dateObserved' order                                                                          by dateObserved desc ";
                                }else{
                                        $sql = "SELECT * FROM processloader_db.bigdatafordevice where suri= '$suri' order by dateObserved desc ";
                                }

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
                                        $response['userInfo'] = $requestUserInfo;
                                }
                                // chiudo la connessione
                                mysqli_close($link);
                        }
            }else{
                    $response['result'] = 'KO';
                    $response['code'] = 401;
                    $response['message'] = 'Not found user: '.$requestUserInfo; // chiudo la connessione
                    echo json_encode($response);
            }

} catch(Exception $ex) {
                $response['result'] = 'KO';
                $response['code'] = 401;
                $response['message'] = 'Not authorized'; // chiudo la connessione
        echo json_encode($response); //mando il messaggio di errore
  }


}else{
        $response['result'] = 'KO';
                $response['code'] = 401;
                $response['message'] = 'Not authorized'; // chiudo la connessione
        echo json_encode($response); //mando il messaggio di errore
}
?>
