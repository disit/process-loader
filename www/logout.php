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

session_start();
include 'config.php';
//include 'process-form.php';
require 'sso/autoload.php';

use Jumbojett\OpenIDConnectClient;

$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);
$oidc->setVerifyHost(false);
$oidc->setVerifyPeer(false);

$oidc->providerConfigParam(array('authorization_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/auth'));
$oidc->providerConfigParam(array('token_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
$oidc->providerConfigParam(array('userinfo_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/userinfo'));
$oidc->providerConfigParam(array('jwks_uri'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/certs'));
$oidc->providerConfigParam(array('issuer'=>$oicd_address.'/auth/realms/master'));
$oidc->providerConfigParam(array('end_session_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/logout'));
/////

if (isset($_SESSION['loggedRole']) || isset($_SESSION['refreshToken'])) {
  //$username = $_SESSION['loggedUsername'];
  
  if(isset($_SESSION['refreshToken'])){
    $refreshToken = $_SESSION['refreshToken'];
	$newLocation = "page.php";
    //$newLocation = 'iframeApp.php?linkUrl=https://www.snap4city.org/drupal&linkId=snap4cityPortalLink&pageTitle=www.snap4city.org&fromSubmenu=false';
  } else{
      $newLocation = "page.php";
  }

$_SESSION = array();

 if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
  }

  session_destroy();

  if (isset($refreshToken)) {
    $tkn = $oidc->refreshToken($refreshToken);
    //$oidc->signOut($tkn->access_token, $appUrl . "/management/" . $newLocation);
	$oidc->signOut($tkn->access_token, $appUrl . "/page.php");
  }

  header("Location: " . $newLocation);
} else {
  $newLocation = "page.php?sessionExpired=true";
  header("Location: " . $newLocation);
}

?>
