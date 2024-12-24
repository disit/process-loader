<?php
	include 'config.php';
	include('curl.php');
	$link = mysqli_connect($host, $username, $password) or die("failed to connect to server !!");
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname);
     $values=$_POST['values'];
  if(($values!=null)||($values!="")||(!empty($values))){
    $value="";
    for($i=0;$i<count($values);$i++){
        if($i==count($values)-1){
            $value=$value."'".$values[$i]."'";
        } else{$value=$value."'".$values[$i]."'"." OR status=";}
    }
	    $query = "SELECT * FROM ServicePhoto WHERE status=$value ORDER BY timestamp DESC";
    }
    else{$query = "SELECT * FROM ServicePhoto ORDER BY timestamp DESC";}

    $result = mysqli_query($link, $query) or die(mysqli_error($link));
	$list = array();
	$num = $result->num_rows;
	if ($num > 0) {
		while ($row1 = mysqli_fetch_array($result)) {
					$url = $row1['serviceUri'];
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$result2 = curl_exec($ch);
					if (curl_errno($ch)) {
						echo 'Error:' . curl_error($ch);
					}
					curl_close ($ch);
				if (strpos($result2,'POINT') !== false) {
					$pos=strrpos($result2,'POINT');
					$pos=$pos+6;
					$final_pos = strlen($result2)-1;
					$res=substr($result2,$pos,31);
					$res2=str_replace(')','',$res);
					$res2  = str_replace('<', '', $res2);
					$res2  = str_replace('>', '', $res2);
					$res2=str_replace('/literal','',$res2);
					$coord = explode(' ',$res2);
				}else{
					$coord = "";
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
					$query_comment = "SELECT * FROM ServiceComment WHERE timestamp<'$data1' AND timestamp>'$data2'";
					$comment_res = mysqli_query($link, $query_comment) or die(mysqli_error($link));
					if($comment_res->num_rows>0){
						$row = mysqli_fetch_array($comment_res);
						$comment=$row['comment'];
						$comment_id=$row['id'];
						$comment_status=$row['status'];
						
					}
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
					"lat" => $coord[1],
					"lon" => $coord[0],
					"comment"=>$comment,
					"comment_id"=>$comment_id,
					"comment_status"=>$comment_status
				);
		array_push($list, $listFile);
		}
	}
		echo json_encode($list);


	
	






