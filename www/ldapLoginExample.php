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

function ldaplLogin($usr, $pwd)
{
  $ldapServer = 'localhost';
  $ldapPort = 389;
  $username = $usr;
  $ldapUsername = "cn=". $usr . ",dc=ldap,dc=disit,dc=org";
  $password = $pwd;
  $ldapPassword = $pwd;
  
  $ds = @ldap_connect($ldapServer, $ldapPort);
  @ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
  $bind = @ldap_bind($ds);
 
  if($ds && $bind)
  {
    //if(checkLdapMembership($ds, $ldapUsername, "Dashboard"))
	  if(checkLdapMembership($ds,$ldapUsername,"ProcessLoader"))
    {
	  if(checkLdapRole($ds, $ldapUsername, "ToolAdmin"))
	  {
		 return "ToolAdmin";
	  }
	  else
	  {
		  if(checkLdapRole($ds, $ldapUsername, "AreaManager"))
		  {
			 return "AreaManager";
		  }
		  else
		  {
			 if(checkLdapRole($ds, $ldapUsername, "Manager"))
			 {
				return "Manager";
			 }
		  }
	  }
    } 
	
	//Se esce dal ciclo in questo punto significa che non ha trovato utenti autorizzati
	return "Ko";
  }
  else
  {
	 return "Ko";
  }
}