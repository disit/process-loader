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
include("curl.php");
if (isset ($_SESSION['username'])&& isset($_SESSION['role'])){
////PARAMETRI ////

//ID del Job Type
$job_type_id0 = $_POST['id3'];
$job_type_id = filter_var($job_type_id0, FILTER_SANITIZE_STRING);
//
$res0 = $_POST['resource3'];
$res = filter_var($res0, FILTER_SANITIZE_STRING);
//
$desc0=$_POST['desc3'];
$desc = filter_var($desc0, FILTER_SANITIZE_STRING);
//
$cat0 = $_POST['category3'];
$cat = filter_var($cat0, FILTER_SANITIZE_STRING);
//
$file_position0 = $_POST['file_position'];
$file_position = filter_var($file_position0, FILTER_SANITIZE_STRING);
//
$file_zip_jb0=$_POST['file_zip3'];
$file_zip_jb = filter_var($file_zip_jb0, FILTER_SANITIZE_STRING);
//
$for0=$_POST['format3'];
$for = filter_var($for0, FILTER_SANITIZE_STRING);
//
$prot0=$_POST['protocol3'];
$prot = filter_var($prot0, FILTER_SANITIZE_STRING);
//
$_POST['realtime3'] = isset($_POST['realtime3']) ? $_POST['realtime3'] : 0;
$_POST['periodic3'] = isset($_POST['periodic3']) ? $_POST['periodic3'] : 0;
$_POST['opensource3'] = isset($_POST['opensource3']) ? $_POST['opensource3'] : 0;
//
$rt0=$_POST['realtime3'];
$rt = filter_var($rt0, FILTER_SANITIZE_STRING);
//
$per0=$_POST['periodic3'];
$per = filter_var($per0, FILTER_SANITIZE_STRING);
//
$lic0 = $_POST['licence3'];
$lic= filter_var($lic0, FILTER_SANITIZE_STRING);
//
$url_nuovo0 = $_POST['url3'];
$url_nuovo= filter_var($url_nuovo0, FILTER_SANITIZE_STRING);
//
$method0 = $_POST['method3'];
$method = filter_var($method0, FILTER_SANITIZE_STRING);
//
$os0 = $_POST['os3'];
$os = filter_var($os0, FILTER_SANITIZE_STRING);
//
$opensource0 = $_POST['opensource3'];
$opensource = filter_var($opensource0, FILTER_SANITIZE_STRING);
//
$help0 = $_POST['help3'];
//$help = filter_var($help0, FILTER_SANITIZE_STRING);
if (strpos($help0, '<script') !== false) {
			echo 'true';
			$help = filter_var($help0 , FILTER_SANITIZE_STRING);
		}else{
			$help = $help0;
		}
//
$creation_date_jb=date("Y-m-d H:i:s");
//
$file_position0 = $_POST['file_position'];
$file_position = filter_var($file_position0, FILTER_SANITIZE_STRING);
///function RESIZE ///
function resize_image($file, $width, $height) {
		list($w, $h) = getimagesize($file);
		$ratio = max($width/$w, $height/$h);
		$h = ceil($height / $ratio);
		$x = ($w - $width / $ratio) / 2;
		$w = ceil($width / $ratio);
		$imgString = file_get_contents($file);
		$image = imagecreatefromstring($imgString);
		$tmp = imagecreatetruecolor($width, $height);
		imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height,  $w, $h);
		imagejpeg($tmp, $file, 100);
		return $file;
		imagedestroy($image);
		imagedestroy($tmp);
	}
//////
///////////////////MODIFCA FILE MISCROSERVIZI//////////////
////////////////////////////////////////////
$tipo0 = $_POST['tipo_zip3'];
$tipo = filter_var($tipo0, FILTER_SANITIZE_STRING);
//
if (($tipo == 'DataAnalyticMicroService')&&(isset($_REQUEST['editor']))){
	$nuovo_help0 = $_POST['help3'];
	$nuovo_help  = filter_var($nuovo_help0, FILTER_SANITIZE_STRING);
	//echo ("Nuovo Help:	".$nuovo_help);
	$nuovo_url0 = $_POST['url3'];
	$nuovo_url  = filter_var($nuovo_url0, FILTER_SANITIZE_STRING);
	//
	$parameters0 = $_POST['paramList3'];
	$parameters  = filter_var_array($parameters0, FILTER_SANITIZE_STRING);
	//
	$parameters_vecchi0 = $_POST['paramList_vecchi3'];
	$parameters_vecchi  = filter_var_array($parameters_vecchi0, FILTER_SANITIZE_STRING);
	//Selezionare tutto dal db
	$query_micro = "SELECT * FROM processloader_db.uploaded_files WHERE Id='".$job_type_id."';";
	$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname);

			$result_micro = mysqli_query($link, $query_micro) or die(mysqli_error($link));
			$process_list = array();
			$num_rows = $result_micro->num_rows;
			if ($result_micro ->num_rows > 0) {
				while($row = mysqli_fetch_assoc($result_micro)){
						array_push($process_list, $row);
				}
			}
	
		mysqli_close($link);
	//
	$vecchio_html = $process_list[0]['Html'];
	$vecchio_js = $process_list[0]['Js'];
	$vecchio_help = $process_list[0]['Help'];
	echo ("Vecchio Help:	".$vecchio_help);
	$vecchio_url = $process_list[0]['Url'];
	$vecchio_title = str_replace(".zip","",$process_list[0]['File_name']);
	$nuovo_title = $vecchio_title;
	$user_dir = $process_list[0]['Username'];
	///////////////////////////
	print_r($parameters);
	print_r($parameters_vecchi);
	$num = count($parameters);
	$time0 = $process_list[0]['Creation_date'];
	$time1 = str_replace(":","-",$time0);
	$time2 = str_replace(":","-",$time1);
	$time3 = str_replace(" ","-",$time2);
	$auth_check = isset($_POST['auth_check3']) ? $_POST['auth_check3'] : 'no';
	$cartella = 'uploads/'.$user_dir.'/'.$time3.'/';
	/////////////
	$color = '#FFFFFF';
	$nr_categoty = 'UserCreated';
	//Help
	/*
	if ($vecchio_help != $help){
		//$help = 
		$help = '<div id="palette_node_service-info" class="palette_node ui-draggable" style="background-color: rgb(255, 255, 255); height: 28px;" onclick="loadContent(service-info.html);"><div class="palette_label" dir="">'.$nuovo_title.'</div><div class="palette_icon_container"><div class="palette_icon" style="background-image: url(icons/white-globe.png)"></div></div><div class="palette_port palette_port_output" style="top: 9px;"></div><div class="palette_port palette_port_input" style="top: 9px;"></div></div>'.$help;
	}
	*/
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
	echo ($parameters);
	///////////////////////////////MODIFICA O CREAZIONE FILES //////////////////////////////////////////////////
	
	if (($nuovo_title.'.zip' != $nuovo_title)||($vecchio_help != $help)||($vecchio_url != $url_nuovo)){
		///CREAZIONE DA CAPO DEI FILES///
		///Creazione del HTML///
		$nome_funzione = preg_replace('/[^A-Za-z0-9]/', '', $nuovo_title);
		$nomeHTML = $nuovo_title.".html";
		$myfileHtml = fopen($nomeHTML,"w");
		$contentHtml = '<script type="text/javascript"> RED.nodes.registerType("'.$nuovo_title.'", {category: "'.$nr_categoty .'", color: "'.$color.'", defaults: { name:{value: "",required: true}, authentication: {type: "snap4city-authentication",required: false}';
		for ($j=0;$j<$num;$j++){
			if (($parameters[$j] != null)||($parameters[$j] != "")){
				$contentHtml = $contentHtml . ','.$parameters[$j].':{value:"",required:false}';
			}
		}
		/*
		if ($auth_check == 'Yes'){
				$contentHtml = $contentHtml . ',username:{value:"",required:false},password:{value:"",required:false}';
		}*/
		$contentHtml = $contentHtml.'}, outputs: 1,inputs: 1,outputLabels: ["response"],icon: "white-globe.png",label: function() {return this.name || "'.$nuovo_title.'"; },';
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
		$contentHtml = $contentHtml . '<script type="text/x-red" data-template-name="'.$nuovo_title.'"><div class="form-row" id="rowAuthentication">
										<label for="node-input-authentication">Authentication</label>
										<input type="text" id="node-input-authentication">
										</div><div class="form-row"><div class="form-tips"  id="tipAuthentication" style="max-width: none">
											You must have an account with Snap4city to use this node. You can register for one
										<a href="https://www.snap4city.org"
										target="_blank">here</a>.
								</div><div class="form-row"><label for="node-input-name">Name</label><input type="text" id="node-input-name" placeholder="'.$baseMicro.'"></div>';	
		for ($i=0;$i<$num;$i++){
			if (($parameters[$i] != null)||($parameters[$i] != "")){
			$htmlParam = '<div class="form-row"><label for="node-input-'.$parameters[$i].'">'.$parameters[$i].'</label><input type="text" id="node-input-'.$parameters[$i].'" placeholder="'.$parameters[$i].'"></div>';
			$contentHtml = $contentHtml . $htmlParam;
			}
		}
		if ($auth_check == 'Yes'){
			$contentHtml = $contentHtml . '<div class="form-row"><label for="node-input-username">Username</label><input type="text" id="node-input-username" placeholder="Username"></div>';
			$contentHtml = $contentHtml . '<div class="form-row"><label for="node-input-password">Password</label><input type="password" id="node-input-password" placeholder="password"></div>';
		}
		$contentHtml = $contentHtml . '</script><script type="text/x-red" data-help-name="'.$nuovo_title.'">'.$help.'</script>';	
		fwrite($myfileHtml, $contentHtml);
		fclose($myfileHtml);
		///////////////
		$nomeJS = $nuovo_title.".js";
		$myfileJs = fopen($nomeJS,"w");
		/*
		$contentjs = 'module.exports = function(RED) {function eventLog(inPayload, outPayload, config, _agent, _motivation, _ipext, _modcom) {var os = require("os"); var ifaces = os.networkInterfaces(); var uri = "http://192.168.1.43/RsyslogAPI/rsyslog.php";var pidlocal = RED.settings.APPID;var iplocal = null;Object.keys(ifaces).forEach(function (ifname) {ifaces[ifname].forEach(function (iface) {if ("IPv4" !== iface.family || iface.internal !== false) { return;}iplocal = iface.address;}); });';
		$contentjs =  $contentjs . ' iplocal = iplocal + ":" + RED.settings.uiPort;var timestamp = new Date().getTime(); var modcom = _modcom;var ipext = _ipext; var payloadsize = JSON.stringify(outPayload).length / 1000;var agent = _agent; var motivation = _motivation; var lang = (inPayload.lang ? inPayload.lang : config.lang); var lat = (inPayload.lat ? inPayload.lat : config.lat); var lon = (inPayload.lon ? inPayload.lon : config.lon);'; 
		$contentjs =  $contentjs . 'var serviceuri = (inPayload.serviceuri ? inPayload.serviceuri : config.serviceuri); var message = (inPayload.message ? inPayload.message : config.message); var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest; var xmlHttp = new XMLHttpRequest(); console.log(encodeURI(uri + "?p=log" + "&pid=" + pidlocal + "&tmstmp=" + timestamp + "&modCom=" + modcom + "&IP_local=" + iplocal + "&IP_ext=" + ipext +"&payloadSize=" + payloadsize + "&agent=" + agent + "&motivation=" + motivation + "&lang=" + lang + "&lat=" + (typeof lat != "undefined" ? lat : 0.0) + "&lon=" + (typeof lon != "undefined" ? lon : 0.0) + "&serviceUri=" + serviceuri + "&message=" + message));';
		$contentjs =  $contentjs . 'xmlHttp.open("'.$method.'", encodeURI(uri + "?p=log" + "&pid=" + pidlocal + "&tmstmp=" + timestamp + "&modCom=" + modcom + "&IP_local=" + iplocal + "&IP_ext=" + ipext +"&payloadSize=" + payloadsize + "&agent=" + agent + "&motivation=" + motivation + "&lang=" + lang + "&lat=" + (typeof lat != "undefined" ? lat : 0.0) + "&lon=" + (typeof lon != "undefined" ? lon : 0.0) + "&serviceUri=" + serviceuri + "&message=" + message), true);  xmlHttp.send(null); }';
		$contentjs =  $contentjs . 'function '.$nome_funzione.'(config) { RED.nodes.createNode(this, config); var node = this;node.on("input", function(msg) {';
		$contentjs = $contentjs . 'var uri = "'.$url_nuovo.'"; ';
		*/	
		///////////////////////
		$contentjs = 'module.exports = function(RED) {function '.$nome_funzione.'(config) { RED.nodes.createNode(this, config); var node = this;';
	//
	$contentjs = $contentjs . 'const fs = require("fs");
        const request = require("request");
        const s4cUtility = require("./snap4city-utility.js");
        var uid = s4cUtility.retrieveAppID(RED);
        var accessToken = ""; 
        if (fs.existsSync("/data/refresh_token") ||RED.nodes.getNode(config.authentication) != null) {
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
                        config.uri = parsedBody.url + "/'.$url_nuovo.'"
                    } else if (typeof parsedBody.error != "undefined"){
                        node.error(parsedBody.error);
                    }
                })
                const form = r.form();
                form.append("R_file", fs.createReadStream(__dirname + "/'.$_FILES['micro_scriptR3']['name'].'"), {
                    filename: "'.$_FILES['micro_scriptR3']['name'].'"
                });
            } else {
                node.error("Some Problems With This Data Analytic Microservice. Maybe authentication problem.");
            }
        } else if (!fs.existsSync("/data/refresh_token") && RED.nodes.getNode(config.authentication) == null){
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
		$contentjs = $contentjs . 'var inPayload = msg.payload; var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;'; 
		$contentjs = $contentjs .'var xmlHttp = new XMLHttpRequest();';
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
		$contentjs = $contentjs .'xmlHttp.onload = function (e) {
                if (xmlHttp.readyState === 4) {
                    if (xmlHttp.status === 200) { if (xmlHttp.responseText != "") { try { msg.payload = JSON.parse(xmlHttp.responseText); } catch (e) { msg.payload = xmlHttp.responseText;}} else {msg.payload = JSON.parse("{\"status\": \"There was some problem\"}"); }s4cUtility.eventLog(RED, inPayload, msg, config, "Node-Red", "DAMicroserviceUserCreated", config.uri, "RX"); node.send(msg);
                    } else {
                        console.error(xmlHttp.statusText);
                        
                        if (xmlHttp.status == 502){
                            node.error("Something went wrong.  Retry few times (the service must be installed) and then check the correctness of the R script. Also the annotation of plumber.");
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
		$contentjs = $contentjs . '});}RED.nodes.registerType("'.$nuovo_title.'", '.$nome_funzione.');}';
		
		///////////////////////
		fwrite($myfileJs, $contentjs);
		fclose($myfileJs);
		///////////////
		/////PACKAGE//////
			$packageContent = '{"name": "'.$nuovo_title.'","version": "0.0.1","description": "","dependencies": {"xmlhttprequest": "^1.8.0", "btoa": "1.2.1" },"node-red": {"nodes": {"'.$nuovo_title.'": "'.$nuovo_title.'.js"}}}';
			$myfilePackage = fopen("package.json","w");
			fwrite($myfilePackage, $packageContent);
			fclose($myfilePackage);
			///////////////
			if (move_uploaded_file($_FILES['micro_scriptR3']['tmp_name'], $_FILES['micro_scriptR3']['name'])) {
		// file uploaded succeeded echo ('script uploaddeded');
	  } else {
		  //errorenella creazione echo ('zip finished');
	  }
						///Spostare i files in una cartella e zipparla.
	$zip = new ZipArchive();
	$nome_download = $nuovo_title.".zip";	
	$nomeZip = $cartella.$nuovo_title.".zip";
	unlink($nomeZip);
	if (($zip->open($nomeZip, ZipArchive::CREATE)) === TRUE) {
						$zip->addFile($nomeHTML,$nuovo_title.'/'.$nomeHTML);
						$zip->addFile($nomeJS,$nuovo_title.'/'.$nomeJS);
						$zip->addFile('package.json',$nuovo_title.'/package.json');
						$zip->addFile($_FILES['micro_scriptR3']['name'],$nuovo_title.'/'.$_FILES['micro_scriptR3']['name']);
						//$zip->addFile('xmlhttprequest/README.md',$nuovo_title.'/node_modules/xmlhttprequest/README.md');
						//$zip->addFile('xmlhttprequest/LICENSE',$nuovo_title.'/node_modules/xmlhttprequest/LICENSE');
						//$zip->addFile('xmlhttprequest/package.json',$nuovo_title.'/node_modules/xmlhttprequest/package.json');
						//$zip->addFile('xmlhttprequest/lib/XMLHttpRequest.js',$nuovo_title.'/node_modules/xmlhttprequest/lib/XMLHttpRequest.js');
						$zip->addFile('data_analytics/snap4city-utility.js',$nuovo_title.'/snap4city-utility.js');
							addFolderToZip('data_analytics/node_modules/',$zip,$nuovo_title.'/node_modules/');
				//copiare contenuto di btoa
						/*
						if ($auth_check == 'Yes'){
						$zip->addFile('btoa/README.md',$nuovo_title.'/node_modules/btoa/README.md');
						$zip->addFile('btoa/LICENSE',$nuovo_title.'/node_modules/btoa/LICENSE');
						$zip->addFile('btoa/package.json',$nuovo_title.'/node_modules/btoa/package.json');
						$zip->addFile('btoa/LICENSE.DOCS',$nuovo_title.'/node_modules/btoa/LICENSE.DOCS');
						$zip->addFile('btoa/index.js',$nuovo_title.'/node_modules/btoa/index.js');
						$zip->addFile('btoa/test.js',$nuovo_title.'/node_modules/btoa/test.js');
						$zip->addFile('btoa/bin/btoa.js',$nuovo_title.'/node_modules/btoa/bin/btoa.js');
						}	
						*/
				}else{
				////////////echo ('ERROR DURING ZIP CREATION');
				}
				$zip->close();
				echo ('zip finished');
				unlink($nomeHTML);
				unlink($nomeJS);
				unlink('package.json');
				unlink($_FILES['micro_scriptR3']['name']);
				/////
			///////////////////
	}else{
		//do nothing
	}
	///////////

	////////////////////////////////// FINE CREAZIONE O MODIFICA FILES //////////////////////////////////////////
	/////NUOVA query /////
		if (!isset($_FILES['img3']) || !is_uploaded_file($_FILES['img3']['tmp_name'])) {
			$query="UPDATE `uploaded_files` SET `File_name` = '".$nuovo_title.".zip', `Description` = '".$desc."',`Category`='".$cat."', `Resource_input` = '".$res."',`Protocol`='".$prot."',`License`='".$lic."', `Format` = '".$for."', `Periodic` = '".$per."', `Realtime` = '".$rt."',`Url` = '".$url_nuovo."',`Method` = '".$method."',`OpenSource` = '".$opensource."',`OS` = '".$os."',`Help` = '".$help."',`Html` = '".$contentHtml."',`Js` = '".$contentjs."' WHERE Id = '".$job_type_id."'";
			$query_job_type = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
			$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
			url_get($url);
		}else{
				$uploaddir = "imgUploaded/";
				$userfile_tmp = $_FILES['img3']['tmp_name'];
				$userfile_name = $_FILES['img3']['name'];
				$new_img = $uploaddir . $userfile_name;
				move_uploaded_file($userfile_tmp, $new_img);					
				$pos_img=$userfile_name;
				$new_img0=$new_img;
				$new_img = resize_image($new_img0, 'auto', 200);
				$query="UPDATE uploaded_files SET File_name = '".$nuovo_title.".zip', Description = '".$desc."',Category='".$cat."', Resource_input = '".$res."', Img='".$new_img."',Protocol='".$prot."',License='".$lic."', Format = '".$for."', Periodic = '".$per."', Realtime = '".$rt."',`Url` = '".$url_nuovo."',`Method` = '".$method."',`OpenSource` = '".$opensource."',`OS` = '".$os."',`Help` = '".$help."', Html = '".$contentHtml."', Js = '".$contentjs."' WHERE Id = '".$job_type_id."' ";
				$query_job_type = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
				$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
				url_get($url);
		}
	/////////////////////
}else{
/////////////////////////////////////////////
	if (!isset($_FILES['img3']) || !is_uploaded_file($_FILES['img3']['tmp_name'])) {
				$query="UPDATE `uploaded_files` SET `Description` = '".$desc."',`Category`='".$cat."', `Resource_input` = '".$res."',`Protocol`='".$prot."',`License`='".$lic."', `Format` = '".$for."', `Periodic` = '".$per."', `Realtime` = '".$rt."',`Url` = '".$url_nuovo."',`Method` = '".$method."',`OpenSource` = '".$opensource."',`OS` = '".$os."',`Help` = '".$help."' WHERE Id = '".$job_type_id."'";
				$query_job_type = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
				$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
				url_get($url);
	}else{
					$uploaddir = "imgUploaded/";
					$userfile_tmp = $_FILES['img3']['tmp_name'];
					$userfile_name = $_FILES['img3']['name'];
					$new_img = $uploaddir . $userfile_name;
					move_uploaded_file($userfile_tmp, $new_img);					
					$pos_img=$userfile_name;
					$new_img0=$new_img;
					$new_img = resize_image($new_img0, 'auto', 200);
					$query="UPDATE uploaded_files SET Description = '".$desc."',Category='".$cat."', Resource_input = '".$res."', Img='".$new_img."',Protocol='".$prot."',License='".$lic."', Format = '".$for."', Periodic = '".$per."', Realtime = '".$rt."',`Url` = '".$url_nuovo."',`Method` = '".$method."',`OpenSource` = '".$opensource."',`OS` = '".$os."',`Help` = '".$help."' WHERE Id = '".$job_type_id."' ";
					$query_job_type = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
					$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
					url_get($url);
	}
}
//editor
if (isset($_REQUEST['editor'])){
			if ($_REQUEST['editor'] == 'MicroService'){
							if (isset($_REQUEST['showFrame'])){
										if ($_REQUEST['showFrame'] == 'false'){
												header ("location:MicroService_editor.php?showFrame=false");
										}else{
												header ("location:MicroService_editor.php");
											}	
								}else{
									header ("location:MicroService_editor.php");
								}
					}else{
							 if ($_REQUEST['editor'] == 'DataAnalyticMicroService'){
									if (isset($_REQUEST['showFrame'])){
										if ($_REQUEST['showFrame'] == 'false'){
												header ("location:dataAnalyticMicroService_editor.php?showFrame=false");
										}else{
												header ("location:dataAnalyticMicroService_editor.php");
											}	
								}else{
									header ("location:dataAnalyticMicroService_editor.php");
								}
							 } 
					}
		}else{
		if (isset($_REQUEST['showFrame'])){
						if ($_REQUEST['showFrame'] == 'false'){
								header ("location:dataAnalyticMicroService_editor?showFrame=false");
						}else{
								header ("location:dataAnalyticMicroService_editore.php");
							}	
				}else{
					header ("location:dataAnalyticMicroService_editor.php");
				}
		
		}
}else{
	header ("location:page.php");
}

