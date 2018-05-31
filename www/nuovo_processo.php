<?php
include("config.php"); // includere la connessione al database
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);

$url = 'http://'+$val_ip+':8080/SmartCloudEngine/index.jsp?json='.urlencode('{"id":"checkExistJob","jobName":"job_type01","jobGroup":"TRASFORMAZIONI"}');
//
$file = file_get_contents($url, FILE_USE_INCLUDE_PATH);

echo $file;
/*
$ch = curl_init();   
curl_setopt($ch, CURLOPT_URL, $url);   
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$output = curl_exec($ch);  
curl_close($ch);  

echo "PROVA".$output;
*/
?>
