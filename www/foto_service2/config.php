<?php
session_start();
$username = "root";
$password = "toor";
//$password = "";
$host="localhost";
$dbname = "processloader_db";
//SERVER LDAP
$ldapServer = '192.168.0.137';
//nome repository files
$repository_files = 'jobs/';
$repository_destination = '/srv/share/jobs/';
$server_share = '/srv/share/jobs/';
$ip_SCE = 'localhost';
$SCE_password_database = "toor";
$SCE_dbname = "quartz";

//JAVA PARAMETERS
$java_path ='/usr/lib/jvm/java-8-openjdk-amd64/bin/java';

//ETL PARAMETERS
//$DDI_HOME = '-DDI_HOME=/home/ubuntu/programs/data-integration/';
$DDI_HOME = '-DDI_HOME=/home/programs/data-integration/';
$classpath= '-classpath';
$pentaho = 'org.pentaho.di.kitchen.Kitchen';
$xms = '-Xmx512m';
$data_integration_path = ':/home/programs/data-integration/lib/*';
//$data_integration_path = ':/home/ubuntu/programs/data-integration/lib/*';
$level = 'Debug';
//Access_token
$access_token_userinfo = "http://192.168.0.47:8088/auth/realms/master/protocol/openid-connect/userinfo";
//R PARAMETERS
$r_path = '/usr/bin/Rscript';

$connessione_al_server= mysqli_connect($host, $username, $password) or die("Errore di Connessione!!");
if(!$connessione_al_server){
die ('Non riesco a connettermi: errore '.mysqli_error()); // questo apparirà solo se ci sarà un errore
}
 
$db_selected=mysqli_select_db($connessione_al_server,$dbname); // dove io ho scritto "prova" andrà inserito il nome del db
if(!$db_selected){
die ('Errore nella selezione del database: errore '.mysqli_error()); // se la connessione non andrà a buon fine apparirà questo messaggio
}

?>
