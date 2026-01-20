<?php
	include ('config.php');
	include('curl.php');
	include('external_service.php');
	$link = mysqli_connect($host_photo, $username_photo, $password_photo) or die("failed to connect to server !!");
	//$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname_photo);
	//mysqli_select_db($link, $dbname);

if(isset($_POST['date'])){
    $dateSearch = $_POST['date'];
}
else{
    $dateSearch = '1970/01/01';
}

if(isset($_POST['values'])){
    if($_POST['values']==""){
        $values=null;
    }else{
    $values=$_POST['values'];
    }
}
else{
    $values=null;
}

	
 if (isset($_POST['limit'])){
	$limit = (int) $_POST['limit'];
 }else{
	$limit = 5; 
 }
	
if (isset($_POST["page"])) { 
	$page  = (int) $_POST["page"]; 
} else { 
	$page=1; 
}; 
    if(isset($_POST['limit'])){
        if ($_POST['limit'] == ""){
        $limit = 5;
        }
    }
$start_from = ($page-1) * $limit; 

if (($values !== null) && !empty($values)) {
	$placeholders = implode(",", array_fill(0, count($values), "?"));
	$query = "SELECT * FROM ServicePhoto WHERE status IN (" . $placeholders . ") AND timestamp > ? ORDER BY timestamp DESC LIMIT ?, ?";
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
} else {
	$query = "SELECT * FROM ServicePhoto WHERE timestamp > ? ORDER BY timestamp DESC LIMIT ?, ?";
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
                    $url=$url.'&format=json';
					$preUrl='https://servicemap.disit.org/WebAppGrafo/api/v1/?serviceUri=';
					$url=$preUrl.$url;
                    $ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$result2 = curl_exec($ch);
					if (curl_errno($ch)) {
						echo 'Error:' . curl_error($ch);
					}
					curl_close ($ch);
                    $jsonfinale=json_decode($result2, true);
                    if(isset($jsonfinale['Service'])){
                        $coord[1]=$jsonfinale['Service']['features']['0']['geometry']['coordinates']['0'];
                        $coord[0]=$jsonfinale['Service']['features']['0']['geometry']['coordinates']['1'];
                        $city=$jsonfinale['Service']['features']['0']['properties']['city'];
                        $province=$jsonfinale['Service']['features']['0']['properties']['province'];
                        $address=$jsonfinale['Service']['features']['0']['properties']['address'];
                    }
					$time = $row1['timestamp'];				
					$newtimestamp = strtotime("$time + 1 minute");
					$data1=date('Y-m-d H:i:s', $newtimestamp);					
					$newtimestamp2 = strtotime("$time - 1 minute");
					$data2=date('Y-m-d H:i:s', $newtimestamp2);					
					$currentDate = strtotime($time );
					$pastDate = $currentDate-(60*1);
					$formatDate2 = date("Y-m-d H:i:s", $pastDate);	
					$futureDate = $currentDate+(60*1);
					$formatDate1 = date("Y-m-d H:i:s", $futureDate);						
					$query_comment = "SELECT * FROM ServiceComment WHERE timestamp < ? AND timestamp > ?";
					$stmt_comment = mysqli_prepare($link, $query_comment);
					if (!$stmt_comment) {
						die(mysqli_error($link));
					}
					mysqli_stmt_bind_param($stmt_comment, "ss", $data1, $data2);
					mysqli_stmt_execute($stmt_comment);
					$comment_res = mysqli_stmt_get_result($stmt_comment);
					if($comment_res->num_rows>0){
						$row = mysqli_fetch_array($comment_res);
						$comment=$row['comment'];
						$comment_id=$row['id'];
						$comment_status=$row['status'];
						
					}
					mysqli_stmt_close($stmt_comment);
					else{
						$comment='no comments';
						$comment_id='null';
						$comment_status='null';
					}

				$listFile = array(
					"id" => $row1['id'],
					"serviceUri" => $row1['serviceUri'],
					"serviceName" => $row1['serviceName'],
					"uid" => $row1['uid'],
					"file" => $row1['file'],
					"status" => $row1['status'],
					"ip" => $row1['ip'],
					"timestamp" => $row1['timestamp'],
					"lat" => $coord[0],
					"lon" => $coord[1],
					"comment"=>$comment,
					"comment_id"=>$comment_id,
					"address"=>$address,
					"district"=>$province,
					"city"=>$city,
					"comment_status"=>$comment_status
				);
		array_push($list, $listFile);
			}
		}
		if (isset($stmt)) {
			mysqli_stmt_close($stmt);
		}
		echo json_encode($list);


	
	





