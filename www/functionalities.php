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

include 'config.php';
$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
$file_name = basename($_SERVER['PHP_SELF']);
if (isset($_SESSION['role'])){
$role= $_SESSION['role'];
}else{
	$role='Public';
}
$query = "SELECT * FROM processloader_db.functionalities WHERE link='".$file_name."' ORDER BY id DESC";
$style = "";
$result = mysqli_query($link, $query) or die(mysqli_error($link));

        $function_list = array();
        if ($result->num_rows > 0) {
		while ($row = mysqli_fetch_array($result)) {
					$process = array("functionalities" => array(
                        "id" => $row['id'],
                        "functionality" => $row['functionality'],
                        "ToolAdmin" => $row['ToolAdmin'],
                        "AreaManager" => $row['AreaManager'],
                        "Manager"=> $row['Manager'],
						"Public"=>$row['Public'],
                        "link"=>$row['link'],
						"view"=>$row['view'],
						"class"=>$row['class'],
						"RootAdmin"=>$row['RootAdmin']
                )
                );
                array_push($function_list, $process);
				//OPERAZIONI
				if ($file_name==$process['functionalities']['link']){
					//echo ($process['functionalities']['link']);
				//if (($process['functionalities'][$role]==0)&&($process['functionalities']['Public']==0)){
					//redicrect
					//header("location:page.php?pageTitle=Process%20Loader:%20View%20Resources&redirect=access%20denied");
					//
				//}else{
					
							if ($process['functionalities'][$role]==1){
								$element = $process['functionalities']['class'];
								//echo ($process['functionalities']['class'].'<br>');
								?>
								<style type="text/css">
										<?=$element; ?> {display: block;}
								</style>
								<script type='text/javascript'>
										//$(document).ready(function () {	
										$(function(){
													var element = "<?=$element; ?>";
													//$(element).show();
													$(element).css('display','block');
													//$(element).css('display','inline');
										});
								</script>
								<?php
							}else{
								$element = $process['functionalities']['class'];
								//echo ($element.'<br>');
								?>
								<style type="text/css">
										<?=$element; ?> {display: none;}
								</style>
								<script type='text/javascript'>
										//$(document).ready(function () {	
										$(function(){
													var element = "<?=$element; ?>";
													//$(element).hide();
													$(element).css("display","none");
										});
								</script>
								<?php
							}
						//}
				}
		}
		}
		json_encode($function_list);
        mysqli_close($link);
?>

