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
include("ssoLogin.php");

if($ldapOk == false){
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