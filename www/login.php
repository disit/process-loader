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
   
include("config.php");

error_reporting(E_ERROR | E_NOTICE);
//session_start();
$past = time() - 3600;
foreach ( $_COOKIE as $key => $value )
{
	if($value == 'true'){
    setcookie( $key, $value, $past, '/' );
	}
}

$ldapPort="389";
$loggedLdap=false;





//Definizioni di funzione
function checkLdapMembership($connection, $userDn, $tool) 
{
	 $result = ldap_search($connection, 'dc=ldap,dc=disit,dc=org', '(&(objectClass=posixGroup)(memberUid=' . $userDn . '))');
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


function checkLdapRole($connection, $userDn, $role) 
{
  $result = ldap_search($connection, 'dc=ldap,dc=disit,dc=org', '(&(objectClass=organizationalRole)(cn=' . $role . ')(roleOccupant=' . $userDn . '))');
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

//Script principale
//$username = mysqli_real_escape_string($ldapServer, $_POST['username']);
//echo($username);
$ldapUsername = "cn=". $_POST['username'] . ",dc=ldap,dc=disit,dc=org";
//$password = mysqli_real_escape_string($ldapServer, $_POST['password']);
$ldapPassword = $_POST['password'];
//echo($ldapPassword);

$ds = ldap_connect($ldapServer, $ldapPort);
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
$bind = ldap_bind($ds, $ldapUsername, $ldapPassword);
 //echo ("ds".$ds);
 //echo ("bind".$bind);

if($ds && $bind)
{
	//echo "sono entrato"; 
	if(checkLdapMembership($ds, $ldapUsername, "ProcessLoader"))
    {
		//Login OK
		$_SESSION['username']= $_POST['username'];
		$loggedLdap=true;
		//echo('<script type="text/javascript">alert(LDAP);</script>');
		
		//Se serve si può fare un'ulteriore query LDAP per verificare il ruolo dell'utente
		if(checkLdapRole($ds, $ldapUsername, "ToolAdmin"))
        {
			//L'utente è un tool admin
			$_SESSION['role']= "ToolAdmin";
		}
		else
		{
			//if(checkLdapRole($ds, $ldapUsername, "Manager")||checkLdapRole($ds, $ldapUsername, "AreaManager"))
				if(checkLdapRole($ds, $ldapUsername, "AreaManager"))
			{
				//L'utente viene rimappato come un manager, sia che su LDAP sia un manager che un area manager, per retrocompatibilità col codice pregresso
				$_SESSION['role']= "AreaManager";
			}
			else
			{
				if(checkLdapRole($ds, $ldapUsername, "Manager"))
				{
					//L'utente è un area manager
					$_SESSION['role']= "Manager";
				}else{ 
							//L'utente è un observer
							if(checkLdapRole($ds, $ldapUsername, "RootAdmin"))
													{
														//L'utente è un area manager
														$_SESSION['role']= "RootAdmin";
													}else{ 
																//L'utente è un observer
														}
					}
				
			}
			
			/*if(checkLdapRole($ds, $ldapUsername, "AreaManager"))
			{
				//L'utente è un area manager
				//$_SESSION['role']= "Manager";
			}
			else
			{
				if(checkLdapRole($ds, $ldapUsername, "Manager")||checkLdapRole($ds, $ldapUsername, "AreaManager"))
				{
					//L'utente è un manager
					$_SESSION['role']= "Manager";
				}
				else
				{
					//L'utente è un observer
				}
			}*/
		}
		header("location:page.php?pageTitle=Process%20Loader:%20View%20Resources");
	}
	else
	{
		header("location:page.php?pageTitle=Process%20Loader:%20View%20Resources");
		//Utenza presente ma non abilitata per l'applicazione in esame
	}
	
}
else
{
	//Connessione rifiutata con le credenziali fornite
	header("location:page.php?pageTitle=Process%20Loader:%20View%20Resources");
}

if($loggedLdap==false){
	echo('<script type="text/javascript">alert(no LDAP);</script>');
	$query= ("SELECT * FROM users WHERE Username='".$_POST["username"]."' AND password ='".md5($_POST["password"])."'");
	$query_login = mysqli_query($connessione_al_server,$query) or die ("Login non riuscito".mysqli_error($connessione_al_server));
	if(mysqli_num_rows($query_login)>0){
	while ($row = mysqli_fetch_array($query_login)) {
		//creazione variabile $_SESSION
		$_SESSION['id']= $row['Id'];
		$_SESSION['username']= $row['Username'];
		$_SESSION['role']= $row['Role'];
	}
	if(isset($_SESSION['username'])){
		header("location:page.php?pageTitle=Process%20Loader:%20View%20Resources"); 
}
}else{
	$_SESSION['error_log']="Error Log";
	header("location:page.php?pageTitle=Process%20Loader:%20View%20Resources");
    echo "non ti sei registrato con successo"; 
	
}
}

?>