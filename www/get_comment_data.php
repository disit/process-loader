<?php
/**
 * Created by PhpStorm.
 * User: Simone Manetti
 * Date: 25/06/18
 * Time: 15:48
 */
include 'config.php';
include('external_service.php');
include('curl.php');
if(isset($_POST['date'])){
    $dateSearch=$_POST['date'];
}else{
    $dateSearch='1970/01/01';
}
if(isset($_POST['values'])){
    $values=$_POST['values'];
}
else{
    $values=null;
}
 if (isset($_POST['limit'])){
		$limit=(int) $_POST['limit'];
	 }else{
		$limit=5; 
	 }
	
	if (isset($_POST["page_comment"])) { 
		$page  = (int) $_POST["page_comment"]; 
	} else { 
		$page=1; 
	}; 
    if(isset($_POST['limit'])){
        if ($_POST['limit'] == ""){
        $limit = 5;
        }
    }
	
$start_from = ($page-1) * $limit; 

if($values!=null){
    $values = array_values($values);
}

$link = mysqli_connect($host_photo, $username_photo, $password_photo) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
	
//$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
//$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_photo);
//mysqli_select_db($link, $dbname);
if(isset($_POST['values'])){
    $values=$_POST['values'];

}

if($values!=null && !empty($values)){
	$placeholders = implode(",", array_fill(0, count($values), "?"));
    $query = "SELECT * FROM ServiceComment WHERE status IN (" . $placeholders . ") AND timestamp > ? ORDER BY timestamp DESC LIMIT ?, ?";
	$stmt = mysqli_prepare($link, $query);
	if (!$stmt) {
		die(mysqli_error($link));
	}
	$params = $values;
	$params[] = $dateSearch;
	$params[] = $start_from;
	$params[] = $limit;
	$types = str_repeat("s", count($values)) . "sii";
	$bind_names = [];
	$bind_names[] = $types;
	for ($i = 0; $i < count($params); $i++) {
		$bind_names[] = &$params[$i];
	}
	call_user_func_array('mysqli_stmt_bind_param', $bind_names);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
}
else{
	$query = "SELECT * FROM ServiceComment WHERE timestamp > ? ORDER BY timestamp DESC LIMIT ?, ?";
	$stmt = mysqli_prepare($link, $query);
	if (!$stmt) {
		die(mysqli_error($link));
	}
	mysqli_stmt_bind_param($stmt, "sii", $dateSearch, $start_from, $limit);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
}
$list = array();
$num = $result->num_rows;
if ($num > 0) {
        while ($row1 = mysqli_fetch_array($result)) {
            $url = $row1['serviceUri'];

            //$result2=url_get($url);
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result2 = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch); 
            $pos=strrpos($result2,'POINT');
            $pos=$pos+6;
            $final_pos = strlen($result2)-1;
            $res=substr($result2,$pos,31);
            $res2=str_replace(')','',$res);
            $coord = explode(' ',$res2);
			if ($row1['serviceName'] == "Massaia"){
				$coord[1] = '43.797500610352';
				$coord[0] = '11.253999710083';
			}
			
            $listFile = array(
                "id" => $row1['id'],
                "serviceUri" => $row1['serviceUri'],
                "serviceName" => $row1['serviceName'],
                "uid" => $row1['uid'],
                "status" => $row1['status'],
                "timestamp" => $row1['timestamp'],
                "lat" => $coord[1],
                "lon" => $coord[0],
                "comment"=>$row1['comment']
            );
            array_push($list, $listFile);
    }
}
if (isset($stmt)) {
	mysqli_stmt_close($stmt);
}
echo json_encode($list);
