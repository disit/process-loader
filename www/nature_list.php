<?php
header("Access-Control-Allow-Origin: *\r\n");
include('config.php'); 
include('curl.php');
include('external_service.php');
/*
$link = mysqli_connect($host, $username, $password);
mysqli_select_db($link, $dbname); 
*/
$link = mysqli_connect($host_kpi, $username_kpi, $password_kpi) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_kpi);
///
//// LISTA DI TUTTE LE NATURE /////
if((isset($_GET['list']))||(isset($_GET['nature']))) {
if(isset($_GET['list'])) {

$query_list ="SELECT distinct nature FROM processloader_db.nature";
$result_list = mysqli_query($link, $query_list) or die(mysqli_error($link));
$list = 0;
$array = array();
	 $num = $result_list ->num_rows;
				if ($num > 0) {
				while ($row = mysqli_fetch_array($result_list )) {
					$list=$row['nature'];
					array_push($array,$list);
					}	
				}
	//mysqli_close($link);
	echo json_encode($array);
//
//

}
//
if(isset($_GET['nature'])){
	//// LISTA DELLE SUBnatureE /////
	$nature = $_GET['nature'];
	//
//$query_selected="SELECT * FROM processloader_db.nature WHERE nature.nature='".$nature."'";
//$query_selected="SELECT * FROM processloader_db.nature WHERE nature.nature='Social'";
$query_selected="SELECT * FROM processloader_db.nature";
//
$result_selected = mysqli_query($link, $query_selected) or die(mysqli_error($link));
//
$list1 = 0;
$array1 = array();
	 $num1 = $result_selected ->num_rows;
				if ($num1 > 0) {
				
				while ($row = mysqli_fetch_array($result_selected)) {
							$process = array("process" => array(
									"id" => $row['Id'],
									"nature" => $row['nature'],
									"sub_nature" => $row['sub_nature']
							)
							);
                array_push($array1, $process);
				}
				
				/*
				while ($row1 = mysqli_fetch_array($result_selected )) {
								$list1=$row1['sub_nature'];
								array_push($array1,$list1);
						}
				*/
				//						
				}
	//
	echo json_encode($array1);
//
//
	}
	
}else{
	mysqli_close($link);
	echo json_encode($array);
}

?>