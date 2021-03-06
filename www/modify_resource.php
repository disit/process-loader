<?php
/* 
Resource Manager - Process Loader
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
along with this program.  If not, see <http://www.gnu.org/licenses/>. 
*/
   
include("config.php");
include("curl.php");  
if (isset ($_SESSION['username'])&& isset($_SESSION['role'])){  
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

  
if (isset($_POST['id'])){
$id0= mysqli_real_escape_string($connessione_al_server,$_POST['id']);
$id= filter_var($id0, FILTER_SANITIZE_STRING);
}

if ((isset($_FILES['image'])) && ($_FILES['image']!="")){
//if (isset($_FILES['image'])){
				$uploaddir = "imgUploaded/";
				$userfile_tmp = $_FILES['image']['tmp_name'];
				$userfile_name = $_FILES['image']['name'];
				$new_img = $uploaddir . $userfile_name;
				move_uploaded_file($userfile_tmp, $new_img);					
				$pos_img=$userfile_name;
				$new_img0=$new_img;
				$new_img = resize_image($new_img0, 'auto', 200);
				//$new_img = $new_img0;
	//$image=$_POST['image'];
	if (($userfile_name !="")||($userfile_name != null)){
		$par_image='Img ="' .$new_img.'", ';
	}else{
		$par_image='';	
	}
}else{
		$par_image='';
}
//;

//
if (isset($_POST['sub_nature'])){
$sub_nature0=mysqli_real_escape_string($connessione_al_server,$_POST['sub_nature']);
$sub_nature= filter_var($sub_nature0, FILTER_SANITIZE_STRING);
$par_sub_n= 'Resource_input = "'.$sub_nature.'",	';
}else{
	$par_sub_n='';
}

if (isset($_POST['nature'])){
$nature0=mysqli_real_escape_string($connessione_al_server,$_POST['nature']);
$nature= filter_var($nature0, FILTER_SANITIZE_STRING);
$par_nat= 'Category = "'.$nature.'",	';
}else{
 $par_nat = '';	
}

if (isset($_POST['descr'])){
$desc0 = mysqli_real_escape_string($connessione_al_server,$_POST['descr']);
$desc = filter_var($desc0, FILTER_SANITIZE_STRING);
$par_desc= 'Description = "'.$desc.'"	';
}else{
	$par_desc='';
}

if (isset($_POST['OS'])){
$OS0 = mysqli_real_escape_string($connessione_al_server,$_POST['OS']);
$OS = filter_var($OS0, FILTER_SANITIZE_STRING);
$OS= 'OS = "'.$OS.'",	';
}else{
	$par_OS='';
}

if (isset($_POST['protocol'])){
$prot0 = mysqli_real_escape_string($connessione_al_server, $_POST['protocol']);
$prot = filter_var($prot0, FILTER_SANITIZE_STRING);
$par_prot= 'Protocol = "'.$prot.'",	';
}else{
	$par_prot='';
}

if (isset($_POST['licence'])){
	$lic0 = mysqli_real_escape_string($connessione_al_server, $_POST['licence']);
	$lic = filter_var($lic0, FILTER_SANITIZE_STRING);
	$par_lic = 'License = "'.$lic.'",	';
}else{
	$par_lic = '';
}

if (isset($_POST['OpenSource'])){
	$opensource0 = mysqli_real_escape_string($connessione_al_server, $_POST['OpenSource']);
	$opensource = filter_var($opensource0, FILTER_SANITIZE_STRING);
	$par_opensource = 'OpenSource = "'.$opensource.'",	';
}else{
	$par_opensource = '';
}

if (isset($_POST['realtime'])){
	$realtime0 = mysqli_real_escape_string($connessione_al_server, $_POST['realtime']);
	$realtime = filter_var($realtime0, FILTER_SANITIZE_STRING);
	$par_realtime = 'Realtime = "'.$realtime.'",	';
}else{
	$par_realtime = '';
}

if (isset($_POST['periodic'])){
	$periodic0 = mysqli_real_escape_string($connessione_al_server, $_POST['periodic']);
	$periodic = filter_var($periodic0, FILTER_SANITIZE_STRING);
	$par_periodic = 'Periodic = "'.$periodic.'",	';
}else{
	$par_periodic = '';
}

if (isset($_POST['Format'])){
	$format0 = mysqli_real_escape_string($connessione_al_server, $_POST['Format']);
	$format = filter_var($format0, FILTER_SANITIZE_STRING);
	$par_format = 'Format = "'.$format.'",	';
}else{
	$par_format = '';
}


$query = 'UPDATE uploaded_files SET	'.$par_lic.''.$par_prot.''.$par_periodic.''.$par_realtime.''.$par_nat.''.$par_opensource.''.$par_format.''.$par_sub_n.''.$par_OS.''.$par_image.''.$par_desc.'  WHERE Id ="'.$id.'"';
		$query_job_type = mysqli_query($connessione_al_server,$query) or die ("query di creazione del job type non riuscita    ".mysqli_error($connessione_al_server));
		$url = "http://localhost:8983/solr/collection1/dataimport?command=full-import";
		//$url = "http://localhost:8983/solr/collection1/dataimport?command=delta-import";
		url_get($url);
		//file_get_contents($url);
/////
if (isset($_REQUEST['showFrame'])){
				if ($_REQUEST['showFrame'] == 'false'){
					header ("location:page.php?showFrame=false&success_modify=ok");
					//header( "refresh:1;url=page.php?showFrame=false&success_modify=ok" );
				}else{
					header ("location:page.php?success_modify=ok");
					//header( "refresh:1;url=page.php?success_modify=ok" );
				}	
			}else{
				header ("location:page.php?success_modify=ok");
				//header( "refresh:1;url=page.php?success_modify=ok" );
}
}else{
	header ("location:page.php");
}

?>