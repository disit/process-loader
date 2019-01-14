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
/*
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
*/
////
}else{
$url = null;	
}

//
function addFolderToZip($dir, $zipArchive, $zipdir = ''){
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {

            //Add the directory
            if(!empty($zipdir)) $zipArchive->addEmptyDir($zipdir);
          
            // Loop through all the files
            while (($file = readdir($dh)) !== false) {
          
                //If it's a folder, run the function again!
                if(!is_file($dir . $file)){
                    // Skip parent and root directories
                    if( ($file !== ".") && ($file !== "..")){
                        addFolderToZip($dir . $file . "/", $zipArchive, $zipdir . $file . "/");
                    }
                  
                }else{
                    // Add the files
                    $zipArchive->addFile($dir . $file, $zipdir . $file);
                  
                }
            }
        }
    }
} 

///DEFINIRE IMMAGINE//////
$uploaddir = "imgUploaded/";
if ($tipo=="ETL"){
	$new_img = $uploaddir . 'default_images/ETL.png';
}else if (($tipo=="R")){
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
}else if ($tipo=="DataAnalyticMicroService"){
	$new_img = $uploaddir . 'default_images/iot-data-analytic-ms.png';
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
if (($tipo == 'MicroService')||($tipo == 'DataAnalyticMicroService')){
	
	
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
	
	$contentHtml = '<script type="text/javascript"> RED.nodes.registerType("'.$micro_name.'", {category: "'.$category.'", color: "'.$color.'", defaults: { name:{value: "",required: true}, authentication: {type: "snap4city-authentication",required: false}';
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
	/*
	if ($auth_check == 'Yes'){
			$contentHtml = $contentHtml . ',username:{value:"",required:false},password:{value:"",required:false}';
	}*/
	//
	$contentHtml = $contentHtml.'}, outputs: 1,inputs: 1,outputLabels: ["response"],icon: "white-globe.png",label: function() {return this.name || "'.$micro_name.'"; },';
	$contentHtml = $contentHtml.'oneditprepare: function () {
            $.ajax({
                url: "isThereLocalRefreshToken/",
                type: "GET",
                async: false,
                success: function (resp) {
                    if (resp.result) {
                        $("#rowAuthentication").hide();
                        $("#tipAuthentication").hide();
                    }
                }
            });
        },
        oneditresize: function () {
            if ($("#node-input-authentication").children().length > 1) {
                $("#node-input-authentication option[value=\"_ADD_\"]").remove();
            }
        }';
	$contentHtml = $contentHtml.'});</script>';
	$contentHtml = $contentHtml . '<script type="text/x-red" data-template-name="'.$micro_name.'"><div class="form-row" id="rowAuthentication">
	<label for="node-input-authentication">Authentication</label>
	<input type="text" id="node-input-authentication">
</div><div class="form-row"><div class="form-tips"  id="tipAuthentication" style="max-width: none">
	You must have an account with Snap4city to use this node. You can register for one
	<a href="https://www.snap4city.org"
		target="_blank">here</a>.
</div><div class="form-row"><label for="node-input-name">Name</label><input type="text" id="node-input-name" placeholder="'.$baseMicro.'"></div>';	
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
	
	$contentjs = 'module.exports = function(RED) {function '.$nome_funzione.'(config) { RED.nodes.createNode(this, config); var node = this;';
	//
	$contentjs = $contentjs . 'const fs = require("fs");
        const request = require("request");
        const s4cUtility = require("./snap4city-utility.js");
        var uid = s4cUtility.retrieveAppID(RED);
        var accessToken = ""; 
        if (fs.existsSync("/data/refresh_token") || RED.nodes.getNode(config.authentication) != null) {
            accessToken = s4cUtility.retrieveAccessToken(RED, node, config.authentication, uid);
            if (accessToken != "" && typeof accessToken != "undefined") {
                var plumberUri = encodeURI((RED.settings.plumberCreationUrl ? RED.settings.plumberCreationUrl : "https://www.snap4city.org/snap4city-application-api/") +
                    "v1/?op=new_plumber&name=" + node.name + "&id=pl" + node.id.replace(".",
                        "") + "&iotappid=" + uid + "&accessToken=" + accessToken);
                const r = request.post(plumberUri, function optionalCallback(err, httpResponse, body) {
                    if (err) {
                        node.error("Some Problems With This Data Analytic Microservice");
                        return console.error("Some Problems With This Data Analytic Microservice", err);
                    }
                    var parsedBody = JSON.parse(body);
                    if (typeof parsedBody.url != "undefined") {
                        config.uri = parsedBody.url + "/'.$url.'"
                    } else if (typeof parsedBody.error != "undefined"){
                        node.error(parsedBody.error);
                    }
                })
                const form = r.form();
                form.append("R_file", fs.createReadStream(__dirname + "/'.$_FILES['micro_scriptR']['name'].'"), {
                    filename: "'.$_FILES['micro_scriptR']['name'].'"
                });
            } else {
                node.error("Some Problems With This Data Analytic Microservice. Maybe authentication problem.");
            }
        }  else if (!fs.existsSync("/data/refresh_token") && RED.nodes.getNode(config.authentication) == null){
            node.error("You need to install node-red-contrib-snap4city-user module. If it is installed you must insert authentication data.");
        }';
	//
	$contentjs = $contentjs . 'node.on("input", function(msg) {';
	$contentjs = $contentjs . 'if (typeof config.uri != "undefined") {';
	
	
	for ($y=0;$y<$num;$y++){
		if (($parameters[$y] != "")||($parameters[$y] != null)){
		$contentjs = $contentjs . 'var '.$parameters[$y].' = (msg.payload.'.$parameters[$y].' ? msg.payload.'.$parameters[$y].' : config.'.$parameters[$y].');';
		}
	}
		//if checked
		/*
		if ($auth_check == 'Yes'){
			$contentjs = $contentjs .'var username = (msg.payload.username ? msg.payload.username : config.username);';
			$contentjs = $contentjs .'var password = (msg.payload.password ? msg.payload.username : config.password);';
		}*/
		//
		$contentjs = $contentjs . 'var inPayload = msg.payload; var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;'; 
		/*
		if ($auth_check == 'Yes'){
		$contentjs = $contentjs . 'var btoa = require("btoa");';	
		}
		*/
		$contentjs = $contentjs .'var xmlHttp = new XMLHttpRequest();';
		/*if (($parameters[0] != "")||($parameters[0] != null)){
		$contentjs = $contentjs . '));xmlHttp.open("'.$method.'", encodeURI(uri + "?'.$parameters[0].'=" + '.$parameters[0];
		}else{
		//$contentjs = $contentjs . '));xmlHttp.open("'.$method.'", encodeURI(uri + "?'.$parameters[0].'=" + '.$parameters[0];	
		}*/
		$first = true;
		$contentjs = $contentjs . 'xmlHttp.open("'.$method.'", encodeURI(config.uri';
		for ($x=0;$x<$num;$x++){
			if (($parameters[$x] != "")||($parameters[$x] != null)){
				if ($first){
					$contentjs = $contentjs . '+ "?'.'" +(typeof '.$parameters[$x].' != "undefined" && '.$parameters[$x].' != "" ? "'.$parameters[$x].'=" + '.$parameters[$x].' : "")';
					$first = false;
				}else{
					$contentjs = $contentjs.' +(typeof '.$parameters[$x].' != "undefined" && '.$parameters[$x].' != "" ? "&'.$parameters[$x].'=" + '.$parameters[$x].' : "")';
				}
			}
		}

		$contentjs = $contentjs . '), true);'; 
		/*if ($auth_check == 'Yes'){
		$contentjs = $contentjs . 'xmlHttp.setRequestHeader("Authorization","Basic " + btoa (username + ":" + password));';	
		}*/
		$contentjs = $contentjs .'xmlHttp.onload = function (e) {
                if (xmlHttp.readyState === 4) {
                    if (xmlHttp.status === 200) { if (xmlHttp.responseText != "") { try { msg.payload = JSON.parse(xmlHttp.responseText); } catch (e) { msg.payload = xmlHttp.responseText;}} else {msg.payload = JSON.parse("{\"status\": \"There was some problem\"}"); }s4cUtility.eventLog(RED, inPayload, msg, config, "Node-Red", "DAMicroserviceUserCreated", config.uri, "RX"); node.send(msg);
                    } else {
                        console.error(xmlHttp.statusText);
                        
                        if (xmlHttp.status == 502){
                            node.error("Something went wrong. Retry few times (the service must be installed) and then check the correctness of the R script. Also the annotation of plumber.");
                        } else {
                            node.error(xmlHttp.responseText);
                        }
                    }
                }
            };
            xmlHttp.onerror = function (e) {
                console.error(xmlHttp.statusText);
                node.error(xmlHttp.responseText);
            };
            xmlHttp.send(null);';
		$contentjs = $contentjs . '} else { node.error("Some Problems With This Data Analytic Microservice"); }';
		$contentjs = $contentjs . '});}RED.nodes.registerType("'.$micro_name.'", '.$nome_funzione.');}';
		
		fwrite($myfileJs, $contentjs);
		fclose($myfileJs);
	//////fine js /////
	/////PACKAGE
	$packageContent = '{"name": "'.$micro_name.'","version": "0.0.1","description": "","dependencies": {"xmlhttprequest": "^1.8.0","request": "^2.88.0", "btoa": "1.2.1" },"node-red": {"nodes": {"'.$micro_name.'": "'.$micro_name.'.js"}}}';
	$myfilePackage = fopen("package.json","w");
	fwrite($myfilePackage, $packageContent);
	fclose($myfilePackage);
	/////
	/////SCRIPT R
	if (move_uploaded_file($_FILES['micro_scriptR']['tmp_name'], $_FILES['micro_scriptR']['name'])) {
		// file uploaded succeeded echo ('script uploaddeded');
	  } else {
		  //errorenella creazione echo ('zip finished');
	  }
	////////
	
	///Spostare i files in una cartella e zipparla.
	$zip = new ZipArchive();
	//$nome_download = $baseMicro.".zip";
	$nome_download = $micro_name.".zip";
	$nomeZip = $cartella.$micro_name.".zip";
	if (($zip->open($nomeZip, ZipArchive::CREATE)) === TRUE) {
				$zip->addFile($nomeHTML,$micro_name.'/'.$nomeHTML);
				$zip->addFile($nomeJS,$micro_name.'/'.$nomeJS);
				$zip->addFile('package.json',$micro_name.'/package.json');
				$zip->addFile($_FILES['micro_scriptR']['name'],$micro_name.'/'.$_FILES['micro_scriptR']['name']);
				//$zip->addFile('xmlhttprequest/README.md',$micro_name.'/node_modules/xmlhttprequest/README.md');
				//$zip->addFile('xmlhttprequest/LICENSE',$micro_name.'/node_modules/xmlhttprequest/LICENSE');
				//$zip->addFile('xmlhttprequest/package.json',$micro_name.'/node_modules/xmlhttprequest/package.json');
				//$zip->addFile('xmlhttprequest/lib/XMLHttpRequest.js',$micro_name.'/node_modules/xmlhttprequest/lib/XMLHttpRequest.js');
				//copiare contenuto di btoa
				$zip->addFile('data_analytics/snap4city-utility.js',$micro_name.'/snap4city-utility.js');
				addFolderToZip('data_analytics/node_modules/',$zip,$micro_name.'/node_modules/');
				//$zip->addFile('data_analytics/node_modules',$micro_name.'/node_modules');
				/*
				if ($auth_check == 'Yes'){
				$zip->addFile('btoa/README.md',$micro_name.'/node_modules/btoa/README.md');
				$zip->addFile('btoa/LICENSE',$micro_name.'/node_modules/btoa/LICENSE');
				$zip->addFile('btoa/package.json',$micro_name.'/node_modules/btoa/package.json');
				$zip->addFile('btoa/LICENSE.DOCS',$micro_name.'/node_modules/btoa/LICENSE.DOCS');
				$zip->addFile('btoa/index.js',$micro_name.'/node_modules/btoa/index.js');
				$zip->addFile('btoa/test.js',$micro_name.'/node_modules/btoa/test.js');
				$zip->addFile('btoa/bin/btoa.js',$micro_name.'/node_modules/btoa/bin/btoa.js');
				}
				*/
	}else{
	//$zipM->addFile($nome);	
	//echo ('ERROR DURING ZIP CREATION');
	}
	$zip->close();
	unlink($_FILES['micro_scriptR']['name']);
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
								header ("location:dataAnalyticMicroService_editor.php?showFrame=false&result=ok");
						}else{
								header ("location:dataAnalyticMicroService_editor.php?result=ok");
							}	
				}else{
					header ("location:dataAnalyticMicroService_editor.php?result=ok");
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
