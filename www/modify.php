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

////PARAMETRI ////

//ID del Job Type
$job_type_id = $_POST['id3'];
$res = $_POST['resource3'];
$desc=$_POST['desc3'];
$cat = $_POST['category3'];
$file_position = $_POST['file_position'];	
$file_zip_jb=$_POST['file_zip3'];
$for=$_POST['format3'];
$prot=$_POST['protocol3'];
$_POST['realtime3'] = isset($_POST['realtime3']) ? $_POST['realtime3'] : 0;
$_POST['periodic3'] = isset($_POST['periodic3']) ? $_POST['periodic3'] : 0;
$_POST['opensource3'] = isset($_POST['opensource3']) ? $_POST['opensource3'] : 0;
$rt=$_POST['realtime3'];
$per=$_POST['periodic3'];
$lic = $_POST['licence3'];
$url = $_POST['url3'];
$method = $_POST['method3'];
$os = $_POST['os3'];
$opensource = $_POST['opensource3'];
$help = $_POST['help3'];
$creation_date_jb=date("Y-m-d H:i:s");
$file_position = $_POST['file_position'];
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



if (!isset($_FILES['img3']) || !is_uploaded_file($_FILES['img3']['tmp_name'])) {
			$query="UPDATE `uploaded_files` SET `Description` = '".$desc."',`Category`='".$cat."', `Resource_input` = '".$res."',`Protocol`='".$prot."',`License`='".$lic."', `Format` = '".$for."', `Periodic` = '".$per."', `Realtime` = '".$rt."',`Url` = '".$url."',`Method` = '".$method."',`OpenSource` = '".$opensource."',`OS` = '".$os."',`Help` = '".$help."' WHERE Id = '".$job_type_id."'";
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
				$query="UPDATE uploaded_files SET Description = '".$desc."',Category='".$cat."', Resource_input = '".$res."', Img='".$new_img."',Protocol='".$prot."',License='".$lic."', Format = '".$for."', Periodic = '".$per."', Realtime = '".$rt."',`Url` = '".$url."',`Method` = '".$method."',`OpenSource` = '".$opensource."',`OS` = '".$os."',`Help` = '".$help."' WHERE Id = '".$job_type_id."' ";
				$query_job_type = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
				$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
				url_get($url);
}
if (isset($_REQUEST['showFrame'])){
				if ($_REQUEST['showFrame'] == 'false'){
					header ("location:file_archive.php?showFrame=false");
				}else{
					header ("location:file_archive.php");
				}	
			}else{
				header ("location:file_archive.php");
}
