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



if(isset($_REQUEST['Publish_process'])){
	//ID del Job Type
	$job_type_id = mysqli_real_escape_string($connessione_al_server,$_POST['id2']);
	echo $job_type_id;

	//
	$tipo_zip=mysqli_real_escape_string($connessione_al_server,$_POST['tipo_zip2']);
/* 	var_dump($_FILES); */


/* 	CHECK DIMENSIONE IMG 1024 X 1024 */
	
	//TODO

	$_POST['realtime'] = isset($_POST['realtime']) ? $_POST['realtime'] : 0;
	$_POST['periodic'] = isset($_POST['periodic']) ? $_POST['periodic'] : 0;
	$_POST['opensource'] = isset($_POST['opensource']) ? $_POST['opensource'] : 0;
	var_dump($_POST);

	$uploaddir = "imgUploaded/";

	//Recupero il percorso temporaneo del file
	$userfile_tmp = $_FILES['img2']['tmp_name'];

	//recupero il nome originale del file caricato
	$userfile_name = $_FILES['img2']['name'];
	
	if ($userfile_name ==""){
		if ($tipo_zip=="ETL"){
			$new_img = $uploaddir . 'default_images/ETL.png';
		}else if ($tipo_zip=="R"){
			$new_img = $uploaddir . 'default_images/DataAnalitics.png';
		}else if ($tipo_zip=="Java"){
			$new_img = $uploaddir . 'default_images/DataAnalitics.png';
		}else if ($tipo_zip=="IoTApp"){
			$new_img = $uploaddir . 'default_images/IoTApp.png';
		}else if ($tipo_zip=="IoTBlocks"){
			$new_img = $uploaddir . 'default_images/IoTBlocks.png';
		}else if ($tipo_zip=="AMMA"){
			$new_img = $uploaddir . 'default_images/AMMA.png';
		}else if ($tipo_zip=="ResDash"){
			$new_img = $uploaddir . 'default_images/ResDash.png';
		}else if ($tipo_zip=="DevDash"){
			$new_img = $uploaddir . 'default_images/DevDash.png';
		}else if ($tipo_zip=="MicroService"){
			$new_img = $uploaddir . 'default_images/MicroServices.png';
		}else if ($tipo_zip=="ControlRoomDashboard"){
			$new_img = $uploaddir . 'default_images/ControlRoomDashboard.png';
		}else if($tipo_zip=="Mobile App"){
			$new_img= $uploaddir.'default_images/MobileApp.png';
		}else{
			$new_img = $uploaddir . 'default_images/DataAnalitics.png';
		}
		//$new_img = $uploaddir . 'Places-folder-grey-icon.png';
	}else{
		$new_img = $uploaddir . $userfile_name;
	}
	
	//RESIZE IMMAGINI
	function resize_image($file, $width, $height) {
    list($w, $h) = getimagesize($file);
    /* calculate new image size with ratio */
    $ratio = max($width/$w, $height/$h);
    $h = ceil($height / $ratio);
    $x = ($w - $width / $ratio) / 2;
    $w = ceil($width / $ratio);
    /* read binary data from image file */
    $imgString = file_get_contents($file);
    /* create image from string */
    $image = imagecreatefromstring($imgString);
    $tmp = imagecreatetruecolor($width, $height);
    imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height,  $w, $h);
    imagejpeg($tmp, $file, 100);
    return $file;
    /* cleanup memory */
    imagedestroy($image);
    imagedestroy($tmp);
}


		//
	//copio il file dalla sua posizione temporanea alla mia cartella upload
	if (move_uploaded_file($userfile_tmp, $new_img)) {
	  //Se l'operazione è andata a buon fine...
	  echo 'Success';
	}else{
	  //Se l'operazione è fallta...
	  echo 'Upload NOT valid!'; 
	}

	$pos_img=$userfile_name;
	$new_img0=$new_img;
	
	$new_img = resize_image($new_img0, 200, 'auto');
	
	
	
   $cat = mysqli_real_escape_string($connessione_al_server,$_POST['category2']);
   $res = mysqli_real_escape_string($connessione_al_server,$_POST['resource']);
      $url = mysqli_real_escape_string($connessione_al_server,$_POST['url2']);
   $method = mysqli_real_escape_string($connessione_al_server,$_POST['method2']);
      $os = mysqli_real_escape_string($connessione_al_server,$_POST['os2']);
   $opensource = $_POST['opensource2'];
      $help = mysqli_real_escape_string($connessione_al_server,$_POST['help2']);
   echo $cat;
   $for=mysqli_real_escape_string($connessione_al_server,$_POST['format2']);
   if (($for==Null)||($for=="")){
	   $for = "Other";
   }
   echo $for;
   $prot=mysqli_real_escape_string($connessione_al_server,$_POST['protocol2']);
   //$file_zip_jb=$_POST['file_zip'];
   $lic = mysqli_real_escape_string($connessione_al_server,$_POST['licence2']);
   echo $lic;
   $desc=mysqli_real_escape_string($connessione_al_server,$_POST['desc2']);
   $rt=mysqli_real_escape_string($connessione_al_server,$_POST['realtime']);
   $per=mysqli_real_escape_string($connessione_al_server,$_POST['periodic']);
   $file_zip_jb=mysqli_real_escape_string($connessione_al_server,$_POST['file_zip']);
   //$creation_date_jb=$_POST['data_zip'];
   $creation_date_jb=date("Y-m-d H:i:s");

	//posizione file//
	$file_position = mysqli_real_escape_string($connessione_al_server,$_POST['file_position']);
	//
   
	 $query="UPDATE `uploaded_files` SET `Date_of_publication` = '".$creation_date_jb."', `Description` = '".$desc."',`Category`='".$cat."', `Resource_input` = '".$res."', `Img`='".$new_img."',`Protocol`='".$prot."',`License`='".$lic."', `Format` = '".$for."', `Public` = 1, `Periodic` = '".$per."', `Realtime` = '".$rt."',`Url` = '".$url."',`Method` = '".$method."',`OpenSource` = '".$opensource."',`OS` = '".$os."',`Help` = '".$help."' WHERE `Id` = '".$job_type_id."'" ;
	 echo ($query);
	 
   $query_job_type = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
   						 

						$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
						url_get($url);
   header("location:file_archive.php");
}else{
  echo ("Errore nella creazione del jobtype".mysqli_error($connessione_al_server));
}
}else{
	header ("location:page.php");
}
