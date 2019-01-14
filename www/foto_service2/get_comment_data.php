<?php
/**
 * Created by PhpStorm.
 * User: Simone Manetti
 * Date: 25/06/18
 * Time: 15:48
 */
include 'config.php';
include('curl.php');


 if (isset($_POST['limit'])){
		$limit=$_POST['limit'];
	 }else{
		$limit=5; 
	 }
	
	if (isset($_POST["page_comment"])) { 
		$page  = $_POST["page_comment"]; 
	} else { 
		$page=1; 
	}; 

	if ($_POST['limit'] == ""){
	$limit = 5;  
	}
	
$start_from = ($page-1) * $limit; 

if($values!=null){
    $value="";
    for($i=0;$i<count($values);$i++){


        if($i==count($values)-1){
            $value=$value."'".$values[$i]."'";
        } else{$value=$value."'".$values[$i]."'"." OR status=";}

    }
}

$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
//$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname);
$values=$_POST['values'];

if($values!=null){
    $value="";
    for($i=0;$i<count($values);$i++){
//        echo json_encode($values2[$i]);

        if($i==count($values)-1){
            $value=$value."'".$values[$i]."'";
        } else{$value=$value."'".$values[$i]."'"." OR status=";}

    }
//    echo json_encode($value);


//	    $query2 = "SELECT * FROM ServicePhoto WHERE status='$value' ORDER BY timestamp DESC LIMIT 18";
    $query = "SELECT * FROM ServiceComment WHERE status=$value ORDER BY timestamp DESC LIMIT $start_from, $limit";
//        $query=$query."ORDER BY timestamp DESC LIMIT 18";

}
else{$query = "SELECT * FROM ServiceComment ORDER BY timestamp DESC LIMIT $start_from, $limit";}

$result = mysqli_query($link, $query) or die(mysqli_error($link));
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
echo json_encode($list);

