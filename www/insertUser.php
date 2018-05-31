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


include("config.php"); // includo il file di connessione al database
if($_POST["username_reg"] != "" && $_POST["password_reg"]!= "" && $_POST["password_reg2"]!="" && $_POST["password_reg"]==$_POST["password_reg2"]){  
$us_reg=$_POST["username_reg"];
$pw_reg=$_POST["password_reg"];
$pw_reg2=$_POST["password_reg2"];
$em_reg=$_POST["mail"];
$nt_reg=$_POST["notes"];

$rl_reg=$_POST["role_reg"];
//$ladp_reg=$_POST["ldap_reg"];
/*
$query_count ="SELECT COUNT(*) FROM processloader_db.users WHERE Username='".$us_reg."'";
$count_res = mysqli_query($connessione_al_server,$query_count) or die ("query di registrazione non riuscita".mysqli_error()); 
$row = mysql_fetch_assoc($count_res);
$count = $row['count'];
if ($count > 0){
//
header('location:registrazione.php;');
$message = 'This User yes exist.';
echo "<SCRIPT> //not showing me this
alert('$message');
</SCRIPT>";
 mysqli_close();
//	
}else{
	*/
//




//$query_reg="INSERT INTO processloader_db.users (Id,Username,Password,Role,Email,Notes)VALUES (NULL,'".$us_reg."','".$pw_reg."','".$rl_reg."','".$em_reg."','".$nt_reg."')";
$query_reg="INSERT INTO processloader_db.users (Id,Username,Password,Role,Email,Notes)VALUES (NULL,'".$us_reg."','".md5($pw_reg)."','".$rl_reg."','".$em_reg."','".$nt_reg."')";
$query_registrazione = mysqli_query($connessione_al_server,$query_reg) or die ("Operation failed".mysqli_error()); // se la query fallisce mostrami questo errore
//crea tabella
//$nome_dir='./jobs/'.$us_reg;
$nome_dir = $repository_files.$us_reg;
mkdir($nome_dir, 0700);
//$_SESSION['username']= $us_reg;
//$_SESSION['role']=$rl_reg;
//
}else{
//header('location:registrazione.php?action=registration&errore=Non hai compilato tutti i campi obbligatori'); // se le prime condizioni non vanno bene entra in questo ramo else
										if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:registrazione.php?showFrame=false");
													}else{
													header ("location:registrazione.php");
													}	
												}else{
												header ("location:registrazione.php");
												}

	//$_SESSION['message'] = 'error';
	$_SESSION['Error_message'] = 'Error during data field compilation';
//
/*
header('location:registrazione.php?');
echo '<script language="javascript">';
echo 'alert("You are not filled all required fields.")';
echo '</script>';
*/
//
}
if(isset($query_registrazione)){ //se la reg è andata a buon fine
$_SESSION["logged"]=true; //restituisci vero alla chiave logged in SESSION
//header("location:Process_loader.php");
		if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:registrazione.php?showFrame=false");
													}else{
													header ("location:registrazione.php");
													}	
												}else{
												header ("location:registrazione.php");
												}
///
}else{
//
//echo "non ti sei registrato con successo"; // altrimenti esce scritta a video questa stringa
//header('location:registrazione.php?');
			if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
													header ("location:registrazione.php?showFrame=false");
													}else{
													header ("location:registrazione.php");
													}	
												}else{
												header ("location:registrazione.php");
												}
//echo '<script language="javascript">';
//echo 'alert("ERROR!")';
//echo '</script>';
//
}
//
//}
?>