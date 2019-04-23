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
  
  include 'config.php';
  require 'sso/autoload.php';
  use Jumbojett\OpenIDConnectClient; 
   
  
  $ldapPort = 389;
  $ldapRole = null;
  $ldapOk = false;

  $page = "page.php";
  if(isset($_REQUEST['redirect'])) {
    $page = $_REQUEST['redirect'];
  }
  
	$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);

    $oidc->setVerifyHost(false);
    $oidc->setVerifyPeer(false);

//https://www.disit.org/auth/
	$oidc->providerConfigParam(array('authorization_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/auth'));
    $oidc->providerConfigParam(array('token_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
    $oidc->providerConfigParam(array('userinfo_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/userinfo'));
    $oidc->providerConfigParam(array('jwks_uri'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/certs'));
    $oidc->providerConfigParam(array('issuer'=>$oicd_address.'/auth/realms/master'));
    $oidc->providerConfigParam(array('end_session_endpoint'=>$oicd_address.'/auth/realms/master/protocol/openid-connect/logout'));
//
    $oidc->addScope(array('openid','username','profile'));
    $oidc->setRedirectURL($appUrl . '/ssoLogin.php?redirect='.$page);
    try {
      $oidc->authenticate();
    } catch(Exception $ex) {
      header("Location: ssoLogin.php?exception");
    }
    //
	//echo($oidc->requestUserInfo);
	//
    $username = $oidc->requestUserInfo("preferred_username");
	$ldapUsername = "cn=". $username . ",".$ldapParamters;
	//echo($ldapUsername);

    $ds = ldap_connect($ldapServer, $ldapPort);
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    $bind = ldap_bind($ds);
    if($ds && $bind)
    {
        if(checkLdapMembership($ds, $ldapUsername, "ProcessLoader",$ldapParamters))
        {
			//
           if(checkLdapRole($ds, $ldapUsername, "RootAdmin",$ldapParamters))
           {
              $ldapRole = "RootAdmin";
              $ldapOk = true;
           }
           else if(checkLdapRole($ds, $ldapUsername, "ToolAdmin",$ldapParamters))
           {
              $ldapRole = "ToolAdmin";
              $ldapOk = true;
           } else if(checkLdapRole($ds, $ldapUsername, "AreaManager",$ldapParamters)) {
              $ldapRole = "AreaManager";
              $ldapOk = true;
           } else if(checkLdapRole($ds, $ldapUsername, "Manager",$ldapParamters)) {
              $ldapRole = "Manager";
              $ldapOk = true;
           } else {
              $msg="user ".$username." does not have a valid role";
           }
        } else {
          $msg="user ".$username." cannot access to ProcessLoader! 	".$ldapUsername;
        }
    } else {
	  $ldapOk = false;
      $msg="cannot bind to LDAP server";
    }

    if($ldapOk)
    {
        $_SESSION['username'] = $username;
        $_SESSION["role"] = $ldapRole;
        $_SESSION['refreshToken']=$oidc->getRefreshToken();
        $_SESSION['accessToken']=$oidc->getAccessToken();
        
        header("location: $page");	
    }
    else
    {
        echo $msg;

    }

      //Definizioni di funzione
function checkLdapMembership($connection, $userDn, $tool,$node) 
{
	 $result = ldap_search($connection, $node, '(&(objectClass=posixGroup)(memberUid=' . $userDn . '))');
	 $entries = ldap_get_entries($connection, $result);
	 foreach ($entries as $key => $value) 
	 {
		if(is_numeric($key)) 
		{
		   if($value["cn"]["0"] == $tool) 
		   {
			  return true;
		   }
		}
	 }
	 return false;
 }

function checkLdapRole($connection, $userDn, $role, $node) 
{
  $result = ldap_search($connection, $node, '(&(objectClass=organizationalRole)(cn=' . $role . ')(roleOccupant=' . $userDn . '))');
  $entries = ldap_get_entries($connection, $result);
  foreach ($entries as $key => $value) 
  {
	 if(is_numeric($key)) 
	 {
		if($value["cn"]["0"] == $role) 
		{
		   return true;
		}
	 }
  }
  return false;
}
