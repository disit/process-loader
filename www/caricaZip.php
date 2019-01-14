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

include('config.php'); 
ini_set('upload-max-filesize', '100M');
ini_set('post_max_size', '100M');
$descrizione = $_POST['filedesc'];
$tipo = $_POST['filetype'];
$percorso = $_FILES['userfile']['tmp_name'];
$nome = $_FILES['userfile']['name'];
$nature= $_POST['filenat'];
$subnat=$_POST['filesubN'];
$fileLic=$_POST['fileLic'];
$fileacc=$_POST['fileacc'];
$fileform=$_POST['fileform'];
if ($nature =="" || $nature == null){$nature = 'ToBeDefined';}
if ($subnat =="" || $subnat == null){$subnat = 'ToBeDefined';}
if ($fileLic == ""|| $fileLic == null){$fileLic = 'Private';}
if ($fileform == ""|| $fileform == null){$fileform = 'ToBeDefined';}
if ($fileacc == ""|| $fileacc == null){$fileacc = 'ToBeDefined';}
$data1=date('Y-m-d');
$time2=date('H');
$time3=date('Y-m-d-H-i-s'); 
$utente_us = $_SESSION['username'];
$ext = explode(".", $nome);
$ext = $ext[count($ext)-1];
if (isset($_POST['help'])){
$help = $_POST['help'];
}else{
$help = null;	
}
if (isset($_POST['url'])){
$url = $_POST['url'];
////Manipolazione ULR//
			$check = strstr($url,'http:');
						if ($check){
									$url=$url;
					}else{
							$check2 = strstr($url,'https');
					if ($check2){
						$url=str_replace('https','http',$url);
					}else{
						$url='http://'.$url;
					}
			}
////
}else{
$url = null;	
}


///DEFINIRE IMMAGINE//////
$uploaddir = "imgUploaded/";
if ($tipo=="ETL"){
	$new_img = $uploaddir . 'default_images/ETL.png';
}else if ($tipo=="R"){
	$new_img = $uploaddir . 'default_images/DataAnalitics.png';
}else if ($tipo=="Java"){
	$new_img = $uploaddir . 'default_images/DataAnalitics.png';
}else if ($tipo=="IoTApp"){
	$new_img = $uploaddir . 'default_images/IoTApp.png';
}else if ($tipo=="IoTBlocks"){
	$new_img = $uploaddir . 'default_images/IoTBlocks.png';
}else if ($tipo=="AMMA"){
	$new_img = $uploaddir . 'default_images/AMMA.png';
}else if ($tipo=="ResDash"){
	$new_img = $uploaddir . 'default_images/ResDash.png';
}else if ($tipo=="DevDash"){
	$new_img = $uploaddir . 'default_images/DevDash.png';
}else if ($tipo=="MicroService"){
	$new_img = $uploaddir . 'default_images/MicroServices.png';
}else if ($tipo=="ControlRoomDashboard"){
	$new_img = $uploaddir . 'default_images/ControlRoomDashboard.png';
}else if($tipo=="Mobile App"){
	$new_img= $uploaddir.'default_images/MobileApp.png';
}else{
	$new_img = '';
}

///BASE///
$base= basename($nome,".zip");
if (($tipo == 'ETL')||($tipo=="R")||($tipo=="Java")){
		$cartella = 'uploads/'.$_SESSION['username'].'/'.$time3.'/'.$base.'/';
	}else{
		$cartella = 'uploads/'.$_SESSION['username'].'/'.$time3.'/';
	}
	
$creaCartella=mkdir($cartella, 0777, true);
$data=date('Y-m-d H:i:s');
//////IF MICROSERVICE /////
if ($tipo == 'MicroService'){
	
	
	$auth_check = isset($_POST['auth_check']) ? $_POST['auth_check'] : 'no';
	//$fileLic = "Public";
	$fileform = "json";
	$fileacc = "http";
	
	if (isset($_POST['metod'])){
		$method = $_POST['metod'];
	}else{
		$method = "GET";
	}
	
	
	if (isset($_POST['paramList'])){
		$array_parametri = $_POST['paramList'];
	}else{
		$array_parametri = array();
	}
	if (isset($_POST['micro_name'])){
			$micro_name = $_POST['micro_name'];
			}else{
			$micro_name = "GenericMicroService";	
		}
	
	$utente = $_SESSION['username'];
	//$utente = 'Prova_microservice';
	$cartellaMicro = 'uploads/'.$utente.'/'.$time3.'/';
	if (isset($_POST['micro_param'])){
		$string_value= $_POST['micro_param'];
		$string=explode(",",$string_value);
	}else{
		$string = array();
	}
	if (isset($_POST['micro_user'])){
		$micro_user = $_POST['micro_user'];
	}else{
		$micro_user = "";
	}
	
	if (isset($_POST['micro_pass'])){
		$micro_pass = $_POST['micro_pass'];
	}else{
		$micro_pass = "";
	}
	
	
	
	$json_a = json_decode($string, true);
	//$parameters = $json_a['parameterList'];
	$parameters = $array_parametri;
	$num = count($parameters);
	$baseMicro= basename($nome,".json");
	//$nome_funzione = preg_replace("[^A-Za-z0-9 ]", "", $micro_name);
	$nome_funzione = preg_replace('/[^A-Za-z0-9]/', '', $micro_name);
	$nomeHTML = $micro_name.".html";
	$myfileHtml = fopen($nomeHTML,"w");
	$color = '#FFFFFF';
	$category = 'UserCreated';
	
	$contentHtml = '<script type="text/javascript"> RED.nodes.registerType("'.$micro_name.'", {category: "'.$category.'", color: "'.$color.'", defaults: { name:{value: ""}';
	//HELP CREATION
	//
	//
	//$help = '<div id="palette_node_service-info" class="palette_node ui-draggable" style="background-color: rgb(255, 255, 255); height: 28px;" onclick="loadContent(service-info.html);"><div class="palette_label" dir="">'.$micro_name.'</div><div class="palette_icon_container"><div class="palette_icon" style="background-image: url(icons/white-globe.png)"></div></div><div class="palette_port palette_port_output" style="top: 9px;"></div><div class="palette_port palette_port_input" style="top: 9px;"></div></div>'.$help;
	//	
	for ($j=0;$j<$num;$j++){
		if (($parameters[$j] != "")||($parameters[$j] != null)){
				$contentHtml = $contentHtml . ','.$parameters[$j].':{value:"",required:false}';
		}
	}
	//if checked
	if ($auth_check == 'Yes'){
			$contentHtml = $contentHtml . ',username:{value:"",required:false},password:{value:"",required:false}';
	}
	//
	$contentHtml = $contentHtml.'}, outputs: 1,inputs: 1,outputLabels: ["response"],icon: "white-globe.png",label: function() {return this.name || "'.$micro_name.'"; } });</script>';
	$contentHtml = $contentHtml . '<script type="text/x-red" data-template-name="'.$micro_name.'"><div class="form-row"><label for="node-input-name">Name</label><input type="text" id="node-input-name" placeholder="'.$baseMicro.'"></div>';	
	for ($i=0;$i<$num;$i++){
		if (($parameters[$i] != "")||($parameters[$i] != null)){
		$htmlParam = '<div class="form-row"><label for="node-input-'.$parameters[$i].'">'.$parameters[$i].'</label><input type="text" id="node-input-'.$parameters[$i].'" placeholder="'.$parameters[$i].'"></div>';
		$contentHtml = $contentHtml . $htmlParam;
		}
	}
	//If checked
	if ($auth_check == 'Yes'){
		$contentHtml = $contentHtml . '<div class="form-row"><label for="node-input-username">Username</label><input type="text" id="node-input-username" placeholder="Username"></div>';
		$contentHtml = $contentHtml . '<div class="form-row"><label for="node-input-password">Password</label><input type="password" id="node-input-password" placeholder="password"></div>';
	}
	//
	$contentHtml = $contentHtml . '</script><script type="text/x-red" data-help-name="'.$micro_name.'">'.$help.'</script>';
	
	fwrite($myfileHtml, $contentHtml);
	fclose($myfileHtml);
	///// FIne HTML /////
	
	////scrittura Java script///
	$nomeJS = $micro_name.".js";
	$myfileJs = fopen($nomeJS,"w");
	
	$contentjs = 'module.exports = function(RED) {function eventLog(inPayload, outPayload, config, _agent, _motivation, _ipext, _modcom) {var os = require("os"); var ifaces = os.networkInterfaces(); var uri = "http://192.168.1.43/RsyslogAPI/rsyslog.php";var pidlocal = RED.settings.APPID;var iplocal = null;Object.keys(ifaces).forEach(function (ifname) {ifaces[ifname].forEach(function (iface) {if ("IPv4" !== iface.family || iface.internal !== false) { return;}iplocal = iface.address;}); });';
	$contentjs =  $contentjs . ' iplocal = iplocal + ":" + RED.settings.uiPort;var timestamp = new Date().getTime(); var modcom = _modcom;var ipext = _ipext; var payloadsize = JSON.stringify(outPayload).length / 1000;var agent = _agent; var motivation = _motivation; var lang = (inPayload.lang ? inPayload.lang : config.lang); var lat = (inPayload.lat ? inPayload.lat : config.lat); var lon = (inPayload.lon ? inPayload.lon : config.lon);'; 
	$contentjs =  $contentjs . 'var serviceuri = (inPayload.serviceuri ? inPayload.serviceuri : config.serviceuri); var message = (inPayload.message ? inPayload.message : config.message); var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest; var xmlHttp = new XMLHttpRequest(); console.log(encodeURI(uri + "?p=log" + "&pid=" + pidlocal + "&tmstmp=" + timestamp + "&modCom=" + modcom + "&IP_local=" + iplocal + "&IP_ext=" + ipext +"&payloadSize=" + payloadsize + "&agent=" + agent + "&motivation=" + motivation + "&lang=" + lang + "&lat=" + (typeof lat != "undefined" ? lat : 0.0) + "&lon=" + (typeof lon != "undefined" ? lon : 0.0) + "&serviceUri=" + serviceuri + "&message=" + message));';
	$contentjs =  $contentjs . 'xmlHttp.open("'.$method.'", encodeURI(uri + "?p=log" + "&pid=" + pidlocal + "&tmstmp=" + timestamp + "&modCom=" + modcom + "&IP_local=" + iplocal + "&IP_ext=" + ipext +"&payloadSize=" + payloadsize + "&agent=" + agent + "&motivation=" + motivation + "&lang=" + lang + "&lat=" + (typeof lat != "undefined" ? lat : 0.0) + "&lon=" + (typeof lon != "undefined" ? lon : 0.0) + "&serviceUri=" + serviceuri + "&message=" + message), true);  xmlHttp.send(null); }';
	$contentjs =  $contentjs . 'function '.$nome_funzione.'(config) { RED.nodes.createNode(this, config); var node = this;node.on("input", function(msg) {';
	$contentjs = $contentjs . 'var uri = "'.$url.'"; ';
	
	
	for ($y=0;$y<$num;$y++){
		if (($parameters[$y] != "")||($parameters[$y] != null)){
		$contentjs = $contentjs . 'var '.$parameters[$y].' = (msg.payload.'.$parameters[$y].' ? msg.payload.'.$parameters[$y].' : config.'.$parameters[$y].');';
		}
	}
		//if checked
		if ($auth_check == 'Yes'){
			$contentjs = $contentjs .'var username = (msg.payload.username ? msg.payload.username : config.username);';
			$contentjs = $contentjs .'var password = (msg.payload.password ? msg.payload.username : config.password);';
		}
		//
		$contentjs = $contentjs . 'var inPayload = msg.payload; var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;'; 
		if ($auth_check == 'Yes'){
		$contentjs = $contentjs . 'var btoa = require("btoa");';	
		}
		$contentjs = $contentjs .'var xmlHttp = new XMLHttpRequest();';
		//console.log(encodeURI(uri + "?'.$parameters[0].'="+'. $parameters[0];
		/*
		for ($z=1;$z<$num;$z++){
			if (($parameters[$z] != "")||($parameters[$z] != null)){
			$contentjs = $contentjs.'+(typeof '.$parameters[$z].' != "undefined" && '.$parameters[$z].' != "" ? "&'.$parameters[$z].'=" + '.$parameters[$z].' : "")';
			}
		}*/
		/*if (($parameters[0] != "")||($parameters[0] != null)){
		$contentjs = $contentjs . '));xmlHttp.open("'.$method.'", encodeURI(uri + "?'.$parameters[0].'=" + '.$parameters[0];
		}else{
		//$contentjs = $contentjs . '));xmlHttp.open("'.$method.'", encodeURI(uri + "?'.$parameters[0].'=" + '.$parameters[0];	
		}*/
		$first = true;
		//$contentjs = $contentjs . '));xmlHttp.open("'.$method.'", encodeURI(uri';
		$contentjs = $contentjs . 'xmlHttp.open("'.$method.'", encodeURI(uri';
		for ($x=0;$x<$num;$x++){
			if (($parameters[$x] != "")||($parameters[$x] != null)){
				if ($first){
					$contentjs = $contentjs . '+ "?'.$parameters[$x].'=" + '.$parameters[$x];
					$first = false;
				}else{
					$contentjs = $contentjs.' +(typeof '.$parameters[$x].' != "undefined" && '.$parameters[$x].' != "" ? "&'.$parameters[$x].'=" + '.$parameters[$x].' : "")';
				}
			}
		}

		$contentjs = $contentjs . '), false);'; 
		if ($auth_check == 'Yes'){
		$contentjs = $contentjs . 'xmlHttp.setRequestHeader("Authorization","Basic " + btoa (username + ":" + password));';	
		}
		$contentjs = $contentjs .'xmlHttp.send(null); if (xmlHttp.responseText != "") { try { msg.payload = JSON.parse(xmlHttp.responseText); } catch (e) { msg.payload = xmlHttp.responseText;}} else {msg.payload = JSON.parse("{\"status\": \"There was some problem\"}"); }eventLog(inPayload, msg, config, "Node-Red", "MicroserviceUserCreated", uri, "RX"); node.send(msg);});}RED.nodes.registerType("'.$micro_name.'", '.$nome_funzione.');}';
		
		fwrite($myfileJs, $contentjs);
		fclose($myfileJs);
	//////fine js /////
	/////PACKAGE
	$packageContent = '{"name": "'.$micro_name.'","version": "0.0.1","description": "","dependencies": {"xmlhttprequest": "^1.8.0", "btoa": "1.2.1" },"node-red": {"nodes": {"'.$micro_name.'": "'.$micro_name.'.js"}}}';
	$myfilePackage = fopen("package.json","w");
	fwrite($myfilePackage, $packageContent);
	fclose($myfilePackage);
	/////
	
	///Spostare i files in una cartella e zipparla.
	$zip = new ZipArchive();
	//$nome_download = $baseMicro.".zip";
	$nome_download = $micro_name.".zip";
	$nomeZip = $cartella.$micro_name.".zip";
	if (($zip->open($nomeZip, ZipArchive::CREATE)) === TRUE) {
				$zip->addFile($nomeHTML,$micro_name.'/'.$nomeHTML);
				$zip->addFile($nomeJS,$micro_name.'/'.$nomeJS);
				$zip->addFile('package.json',$micro_name.'/package.json');
				$zip->addFile('xmlhttprequest/README.md',$micro_name.'/node_modules/xmlhttprequest/README.md');
				$zip->addFile('xmlhttprequest/LICENSE',$micro_name.'/node_modules/xmlhttprequest/LICENSE');
				$zip->addFile('xmlhttprequest/package.json',$micro_name.'/node_modules/xmlhttprequest/package.json');
				$zip->addFile('xmlhttprequest/lib/XMLHttpRequest.js',$micro_name.'/node_modules/xmlhttprequest/lib/XMLHttpRequest.js');
				//copiare contenuto di btoa
				if ($auth_check == 'Yes'){
				$zip->addFile('btoa/README.md',$micro_name.'/node_modules/btoa/README.md');
				$zip->addFile('btoa/LICENSE',$micro_name.'/node_modules/btoa/LICENSE');
				$zip->addFile('btoa/package.json',$micro_name.'/node_modules/btoa/package.json');
				$zip->addFile('btoa/LICENSE.DOCS',$micro_name.'/node_modules/btoa/LICENSE.DOCS');
				$zip->addFile('btoa/index.js',$micro_name.'/node_modules/btoa/index.js');
				$zip->addFile('btoa/test.js',$micro_name.'/node_modules/btoa/test.js');
				$zip->addFile('btoa/bin/btoa.js',$micro_name.'/node_modules/btoa/bin/btoa.js');
				}
	}else{
	//$zipM->addFile($nome);
	//echo ('ERROR DURING ZIP CREATION');
	}
	$zip->close();
	echo ('zip finished');
	//cancella files temporanei
	unlink($nomeHTML);
	unlink($nomeJS);
	unlink('package.json');
	/////
	$fileform = 'json';
	$fileacc = 'http';
	/////////CARICA SU DATABASE//////////////
	$query_zip="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Resource_input,Img,Category,Format,Protocol,Realtime,Periodic,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,OpenSource,Url,Help,Html,Js,Method)VALUES (NULL,'".$nome_download."','".$descrizione."','0','".$data."','".$tipo."','Awaiting approval','".$utente_us."','".$subnat."','".$new_img ."','".$nature."','".$fileform."','".$fileacc."','0','0','0',NULL,'".$fileLic."','0','0','0','0','0','".$url."','".$help."','".$contentHtml."','".$contentjs."','".$method."')";
	$query_caricamento = mysqli_query($connessione_al_server,$query_zip) or die ("Error during resource Upload	".mysqli_error($connessione_al_server));
		if (isset($_REQUEST['editor'])){
			if (isset($_REQUEST['showFrame'])){
						if ($_REQUEST['showFrame'] == 'false'){
								header ("location:MicroService_editor.php?showFrame=false&result=ok");
						}else{
								header ("location:MicroService_editor.php?result=ok");
							}	
				}else{
					header ("location:MicroService_editor.php?result=ok");
				}
		}else{
		if (isset($_REQUEST['showFrame'])){
						if ($_REQUEST['showFrame'] == 'false'){
								header ("location:file_archive.php?showFrame=false");
						}else{
								header ("location:file_archive.php");
							}	
				}else{
					header ("location:file_archive.php");
				}
		
		}
	////////////////////
}else {
	if (move_uploaded_file($percorso, $cartella . $nome)){
				if (($tipo == 'ETL')||($tipo=="R")||($tipo=="Java")){
					echo ("NOT! ETL");
					///copiare pezzo ETL
									$zip = new ZipArchive;
									 if ($zip->open($cartella.'/'.$nome) === TRUE){
										$query_zip="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Resource_input,Img,Category,Format,Protocol,Realtime,Periodic,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,OpenSource,Url,Help)VALUES (NULL,'".$nome."','".$descrizione."','0','".$data."','".$tipo."','Awaiting approval','".$utente_us."','".$subnat."','".$new_img ."','".$nature."','".$fileform."','".$fileacc."','0','0','0',NULL,'".$fileLic."','0','0','0','0','0','".$url."','".$help."')";
										 $query_caricamento = mysqli_query($connessione_al_server,$query_zip) or die ("Error during resource Upload	".mysqli_error($connessione_al_server));
										 if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
														header ("location:file_archive.php?showFrame=false&result=ok");
													}else{
														header ("location:file_archive.php?result=ok");
													}	
												}else{
													header ("location:file_archive.php?result=ok");
												}
							}else{ 
								echo('<div>Error during upload in repository! Click on <a href="file_archive.php">Qui</a> To redo this operation!</div>');
										 if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
														header ("location:file_archive.php?showFrame=false&error=error_during_upload");
													}else{
														header ("location:file_archive.php?error=error_during_upload");
													}	
												}else{
													header ("location:file_archive.php?error=error_during_upload");
												}
							}
					///
				}else{
					echo ("NOT!");
					///Copiare ALTRO
							$query_zip="INSERT INTO processloader_db.uploaded_files (Id,File_name,Description,User,Creation_date,file_type,status,Username,Resource_input,Img,Category,Format,Protocol,Realtime,Periodic,Public,Date_of_publication,License,Download_number,Votes,Average_stars,Total_stars,OpenSource,Url,Help)VALUES (NULL,'".$nome."','".$descrizione."','0','".$data."','".$tipo."','Awaiting approval','".$utente_us."','".$subnat."','".$new_img ."','".$nature."','".$fileform."','".$fileacc."','0','0','0',NULL,'".$fileLic."','0','0','0','0','0','".$url."','".$help."')";
							$query_caricamento = mysqli_query($connessione_al_server,$query_zip) or die ("Error during resource Upload	".mysqli_error($connessione_al_server));
										 if (isset($_REQUEST['showFrame'])){
													if ($_REQUEST['showFrame'] == 'false'){
														header ("location:file_archive.php?showFrame=false&result=ok");
													}else{
														header ("location:file_archive.php?result=ok");
													}	
												}else{
													header ("location:file_archive.php?result=ok");
												}
		}
	////
}else{
	echo ("ERROR DURING DATA UPLOAD!!!");
	/////////////ERROR DURING UPLOAD ////
			if (isset($_REQUEST['showFrame'])){
						if ($_REQUEST['showFrame'] == 'false'){
								header ("location:file_archive.php?showFrame=false&error=error_during_upload");
						}else{
								header ("location:file_archive.php?error=error_during_upload");
							}	
				}else{
					header ("location:file_archive.php?error=error_during_upload");
				}
	///////////
}
	
	
}





//////ELSE//////








?>
