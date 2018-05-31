<?php
include('config.php'); 
$descrizione = $_POST['filedesc'];
$tipo = $_POST['filetype'];
$percorso = $_FILES['userfile']['tmp_name'];
$nome = $_FILES['userfile']['name'];
$data1=date('Y-m-d');
$time2=date('H');
//
$time3=date('Y-m-d H-i-s'); 
//$utente = $_SESSION['id'];
$utente_us = $_SESSION['username'];
$ext = explode(".", $nome);
$ext = $ext[count($ext)-1];


//SE IL FILE è uno ZIP
if($ext == 'zip'){
    //COME AGIRE SE IL FILE è effettivamente uno ZIP
    //Disponiamo dei dati che si servono per il caricamento.
    $base= basename($nome,".zip");
    //$cartella = 'uploads/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/'.$base.'/';
	$cartella = 'uploads/'.$_SESSION['username'].'/'.$time3.'/'.$base.'/';
	$creaCartella=mkdir($cartella, 0777, true);
    $data=date('Y-m-d H:i:s');
    //CARICAMENTO FILE ZIP
    //$query_zip="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status)VALUES (NULL,'".$nome."','".$descrizione."','".$utente."','".$data."','".$tipo."','NO')";
   // $query_caricamento = mysqli_query($connessione_al_server,$query_zip) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
	//
	if (move_uploaded_file($percorso, $cartella . $nome)){
			
    //FINE ZIP.
    //
                if($tipo=='ETL'){
                  //Cosa fare se il file è un ETL 
                     //Effettuare una verifica sulla regolarità del file. Se la verifica è positiva procedere al caricamento e all'unzip
                     $zip = new ZipArchive;
                     if ($zip->open($cartella.'/'.$nome) === TRUE){
                         //il file è stato aperto, ora inizia l'analisi.
						 $query_zip="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username)VALUES (NULL,'".$nome."','".$descrizione."','0','".$data."','".$tipo."','NO','".$utente_us."')";
						 $query_caricamento = mysqli_query($connessione_al_server,$query_zip) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
						 //
						 $array_lista = [];
						 $findme="/Ingestion/Main.kjb";
						 $count = 0;
                           for ($i = 0; $i < $zip->numFiles; $i++) {
										
                                       $filename = $zip->getNameIndex($i);
									   $array_lista[$i] = $filename;
                                      // echo ($i.')'.$array_lista[$i].'</br>');
									   $pos = strpos($filename, $findme);
									   if ($pos!== false){
										   $count++;
									   }
									   
                               // ...
                              }
							  if ($count >0){
											echo ("Il file: ".$findme." Esiste");
										   //$zip->extractTo('jobs/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/');
										   $zip->extractTo('jobs/'.$_SESSION['username'].'/'.$time3.'/');
											$zip->close();
											//verificare se effettivamente è presente un file main.kjb
											echo 'ok, il file '.$nome.' è stato carcinato nella cartella:   '.'jobs/'.$_SESSION['username'].'/'.$data.'/';
											//inviare il nome del file come se fosse una vabile GET a param_process.php
											$_SESSION['nome_file'] = $base;
											$_SESSION['file_zip'] = $nome;
											$_SESSION['data_zip']= $data;
											//Modifica stato database
											$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='OK - ".date('Y-m-d H:i:s')."' WHERE `File_name`='".$nome."' AND `Creation_date`='".$data."'";
											$result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
											//
											header("location:file_archive.php");  
											} else {
												echo('<div>IL FILE CARICATO NON é CONFORME AL FORMALISMO SCELTO! cliccate<a href="upload.php">Qui</a> per riprovare il caricamento!</div>');
											}
                     }else{
                        echo('<div>Errore verifica zip! File'.$nome.' Cliccare <a href="upload.php">Qui</a> per rirovare il caricamento!</div>'); 
                     }
                     //fine verifica
                     //
                  //fine del caso di un ETL
                }else if($tipo=='R'){
                  //Cosa fare se il file è un R
                    //Effettuare una verifica sulla regolarità del file. Se la verifica è positiva procedere al caricamento e all'unzip
                     $zip = new ZipArchive;
                     if ($zip->open($cartella.'/'.$nome) === TRUE){
                        //il file è stato aperto, ora inizia l'analisi.
						 $query_zip="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username)VALUES (NULL,'".$nome."','".$descrizione."','0','".$data."','".$tipo."','NO','".$utente_us."')";
						 $query_caricamento = mysqli_query($connessione_al_server,$query_zip) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
						 // 
						$array_lista = [];
						$findme="/Main.R";
						 $count = 0;
                          for ($i = 0; $i < $zip->numFiles; $i++) {
										
                                       $filename = $zip->getNameIndex($i);
									   $array_lista[$i] = $filename;
                                      // echo ($i.')'.$array_lista[$i].'</br>');
									   $pos = strpos($filename, $findme);
									   if ($pos!== false){
										   $count++;
									   }
									   
                               // ...
                              }
							  if ($count >0){
											echo ("Il file: ".$findme." Esiste");
										   //$zip->extractTo('jobs/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/');
										   $zip->extractTo('jobs/'.$_SESSION['username'].'/'.$time3.'/');
											$zip->close();
											//verificare se effettivamente è presente un file main.kjb
											echo 'ok, il file '.$nome.' è stato carcinato nella cartella:   '.'jobs/'.$_SESSION['username'].'/'.$data.'/';
											//inviare il nome del file come se fosse una vabile GET a param_process.php
											$_SESSION['nome_file'] = $base;
											$_SESSION['file_zip'] = $nome;
											$_SESSION['data_zip']= $data;
											//Modifica stato database
											$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='OK - ".date('Y-m-d H:i:s')."' WHERE `File_name`='".$nome."' AND `Creation_date`='".$data."'";
											$result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
											//
											header("location:file_archive.php");  
											} else {
												echo('<div>IL FILE CARICATO NON é CONFORME AL FORMALISMO SCELTO! cliccate<a href="upload.php">Qui</a> per riprovare il caricamento!</div>');
											}
                     }else{
                        echo('<div>Errore verifica zip! File'.$nome.' Cliccare <a href="upload.php">Qui</a> per rirovare il caricamento!</div>'); 
                     }
                     //fine verifica
                  //fine del caso di un R
                }else if($tipo=='Java'){
                  //Cosa fare se il file è un Java
                   //Effettuare una verifica sulla regolarità del file. Se la verifica è positiva procedere al caricamento e all'unzip
                     $zip = new ZipArchive;
                     if ($zip->open($cartella.'/'.$nome) === TRUE){
                        //il file è stato aperto, ora inizia l'analisi.
						 $query_zip="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username)VALUES (NULL,'".$nome."','".$descrizione."','0','".$data."','".$tipo."','NO','".$utente_us."')";
						 $query_caricamento = mysqli_query($connessione_al_server,$query_zip) or die ("Creazione record non riuscita".mysqli_error($connessione_al_server));
						 //
						 $array_lista = [];
						 $findme="/Main.java";
						  $count = 0;
                          for ($i = 0; $i < $zip->numFiles; $i++) {
										
                                       $filename = $zip->getNameIndex($i);
									   $array_lista[$i] = $filename;
                                      // echo ($i.')'.$array_lista[$i].'</br>');
									   $pos = strpos($filename, $findme);
									   if ($pos!== false){
										   $count++;
									   }
									   
                               // ...
                              }
							  if ($count >0){
											echo ("Il file: ".$findme." Esiste");
										   //$zip->extractTo('jobs/'.$_SESSION['username'].'/'.$data1.'/'.$time2.'/');
										   $zip->extractTo('jobs/'.$_SESSION['username'].'/'.$time3.'/');
											$zip->close();
											//verificare se effettivamente è presente un file main.kjb
											echo 'ok, il file '.$nome.' è stato carcinato nella cartella:   '.'jobs/'.$_SESSION['username'].'/'.$data.'/';
											//inviare il nome del file come se fosse una vabile GET a param_process.php
											$_SESSION['nome_file'] = $base;
											$_SESSION['file_zip'] = $nome;
											$_SESSION['data_zip']= $data;
											//Modifica stato database
											$sql = "UPDATE  `uploaded_files` SET `uploaded_files`.`status`='OK - ".date('Y-m-d H:i:s')."' WHERE `File_name`='".$nome."' AND `Creation_date`='".$data."'";
											$result = mysqli_query($connessione_al_server, $sql) or die(mysqli_error($connessione_al_server));
											//
											header("location:file_archive.php");  
											} else {
												echo('<div>IL FILE CARICATO NON é CONFORME AL FORMALISMO SCELTO! cliccate<a href="upload.php">Qui</a> per riprovare il caricamento!</div>');
											}
                     }else{
                        echo('<div>Errore verifica zip! File'.$nome.' Cliccare <a href="upload.php">Qui</a> per riprovare il caricamento!</div>'); 
                     }
                     //fine verifica
                  //fine del caso di un Java
                } else {
                  //il file non è uno dei tipi permessi
                    echo('<div>Il file caricato appartiene a una delle tipologie permessi! Cliccare <a href="upload.php">Qui</a> per rirovare il caricamento!</div>');
                };
   //FINE DEL CASO DI UNO >IP 
			}else{ 
				echo('<div>Errore in fase di caricamento! Cliccare <a href="upload.php">Qui</a> per rirovare il caricamento!</div>');
			}
} else {
    //IL FILE NON é uno zip.
    echo('<div>Il file caricato non è uno zip! Cliccare <a href="upload.php">Qui</a> per rirovare il caricamento!</div>');
}
?>
