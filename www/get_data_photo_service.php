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

include('config.php');
include('curl.php');
include('external_service.php');
if (isset ($_SESSION['username'])&& isset($_SESSION['role'])){
$link = mysqli_connect($host_photo, $username_photo, $password_photo) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_photo);

$action = $_REQUEST['action'];

if ($action == 'get_photo_data') {
    
    if (isset($_REQUEST['date'])) {
        $dateSearch = $_REQUEST['date'];
    } else {
        $dateSearch = '1970-01-01';
    }
    
    if (isset($_REQUEST['values'])) {
        if ($_REQUEST['values'] == "") {
            $values = null;
        } else {
            $values = $_REQUEST['values'];
        }
    } else {
        $values = null;
    }
    
    
    if (isset($_REQUEST['limit'])) {
        $limit = (int) $_REQUEST['limit'];
    } else {
        $limit = 7;
    }
    
    if (isset($_REQUEST["page"])) {
        $page = (int) $_REQUEST["page"];
    } else {
        $page = 1;
    }
    
    $start_from = ($page - 1) * $limit;
    
    if (($values != null) && !empty($values)) {
        $values = array_values($values);
        $placeholders = implode(",", array_fill(0, count($values), "?"));
        $query  = "SELECT DISTINCT * FROM ServicePhoto WHERE status IN (" . $placeholders . ") AND timestamp > ? ORDER BY timestamp DESC LIMIT ?, ?";
        $query1 = "SELECT COUNT(*) FROM ServicePhoto WHERE timestamp > ? AND status IN (" . $placeholders . ")";
        $params = $values;
        $params[] = $dateSearch;
        $params[] = $start_from;
        $params[] = $limit;
        $types = str_repeat("s", count($values)) . "sii";
        $stmt = mysqli_prepare($link, $query);
        if (!$stmt) {
            die(mysqli_error($link));
        }
        $bind_names = [];
        $bind_names[] = $types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bind_names);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $stmt_count = mysqli_prepare($link, $query1);
        if (!$stmt_count) {
            die(mysqli_error($link));
        }
        $params_count = $values;
        $params_count[] = $dateSearch;
        $types_count = str_repeat("s", count($values)) . "s";
        $bind_names_count = [];
        $bind_names_count[] = $types_count;
        for ($i = 0; $i < count($params_count); $i++) {
            $bind_names_count[] = &$params_count[$i];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bind_names_count);
        mysqli_stmt_execute($stmt_count);
        $result1 = mysqli_stmt_get_result($stmt_count);
    } else {
        $query  = "SELECT DISTINCT * FROM ServicePhoto WHERE timestamp > ? ORDER BY timestamp DESC LIMIT ?, ?";
        $query1 = "SELECT COUNT(*) FROM ServicePhoto WHERE timestamp > ?";
        $stmt = mysqli_prepare($link, $query);
        if (!$stmt) {
            die(mysqli_error($link));
        }
        mysqli_stmt_bind_param($stmt, "sii", $dateSearch, $start_from, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $stmt_count = mysqli_prepare($link, $query1);
        if (!$stmt_count) {
            die(mysqli_error($link));
        }
        mysqli_stmt_bind_param($stmt_count, "s", $dateSearch);
        mysqli_stmt_execute($stmt_count);
        $result1 = mysqli_stmt_get_result($stmt_count);
    }
    
    $result1     = $result1->fetch_array();
    $result1     = intval($result1[0]);
    $total_pages = ceil($result1 / $limit);
    
    $list = array();
    $num  = $result->num_rows;
    if ($num > 0) {
        while ($row1 = mysqli_fetch_array($result)) {
            $url    = $row1['serviceUri'];
            $url    = $url . '&format=json';
            $preUrl = $default_serviceUri;
            $url    = $preUrl . $url;
			if (($row1['latitude'] == Null) && ($row1['longitude'] == Null)) {
                //echo ('Trovato');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result2 = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                $jsonfinale = json_decode($result2, true);
                if (isset($jsonfinale['Service'])) {
                    $coord[1] = $jsonfinale['Service']['features']['0']['geometry']['coordinates']['0'];
                    $coord[0] = $jsonfinale['Service']['features']['0']['geometry']['coordinates']['1'];
                    //$city     = $jsonfinale['Service']['features']['0']['properties']['city'];
                    //$province = $jsonfinale['Service']['features']['0']['properties']['province'];
                    //$address  = $jsonfinale['Service']['features']['0']['properties']['address'];
					$city       = "";
                    $province   = "";
                    $address    = "Not Found";
                } else {
                    $coord[0] = $default_latitude;
                    $coord[1] = $default_longitude;
                    $city     = "";
                    $province = "";
                    $address  = "Not Found";
                }
                
                //
                $stmt_update = mysqli_prepare($link, "UPDATE ServicePhoto SET latitude = ?, longitude = ?, city = ?, province = ?, address = ? WHERE id = ?");
				if (!$stmt_update) {
					die(mysqli_error($link));
				}
				$city_safe = mysqli_real_escape_string($link, $city);
				$province_safe = mysqli_real_escape_string($link, $province);
				$address_safe = mysqli_real_escape_string($link, $address);
				$id_row = (int) $row1['id'];
				mysqli_stmt_bind_param($stmt_update, "ddsssi", $coord[0], $coord[1], $city_safe, $province_safe, $address_safe, $id_row);
				$result0 = mysqli_stmt_execute($stmt_update);
				mysqli_stmt_close($stmt_update);
                //                    
                
            } else {
                //echo ('Non trovato');
                $coord[0] = $row1['latitude'];
                $coord[1] = $row1['longitude'];
				$address = $row1['address'];
				$province = $row1['province'];
				$city = $row1['city'];
            }
            
			
					$time          = $row1['timestamp'];
					$newtimestamp  = strtotime("$time + 1 minute");
					$data1         = date('Y-m-d H:i:s', $newtimestamp);
					$newtimestamp2 = strtotime("$time - 1 minute");
					$data2         = date('Y-m-d H:i:s', $newtimestamp2);
					$currentDate   = strtotime($time);
					$pastDate      = $currentDate - (60 * 1);
					$formatDate2   = date("Y-m-d H:i:s", $pastDate);
					$futureDate    = $currentDate + (60 * 1);
					$formatDate1   = date("Y-m-d H:i:s", $futureDate);
					$query_comment = "SELECT DISTINCT * FROM ServiceComment WHERE timestamp<'" . $data1 . "' AND timestamp>'" . $data2 . "'";
					$comment_res = mysqli_query($link, $query_comment) or die(mysqli_error($link));
					if ($comment_res->num_rows > 0) {
						$row            = mysqli_fetch_array($comment_res);
						$comment        = $row['comment'];
						$comment_id     = $row['id'];
						$comment_status = $row['status'];
						
					} else {
						$comment        = 'no comments';
						$comment_id     = 'null';
						$comment_status = 'null';
					}
						//
						//
			
            //
            $url_check = ($photo_api . $row1['file']);
            $headers   = @get_headers($url_check);
            if (strpos($headers[0], '404') === false) {
                $url_file = $row1['file'];
            } else {
                $url_file = "";
            }
            //
            $listFile = array(
                "id" => $row1['id'],
                "serviceUri" => $row1['serviceUri'],
                "serviceName" => $row1['serviceName'],
                "uid" => $row1['uid'],
                "total_pages" => $total_pages,
                "file" => $url_file,
                "status" => $row1['status'],
                "ip" => $row1['ip'],
                "timestamp" => $row1['timestamp'],
                "lat" => $coord[0],
                "lon" => $coord[1],
                "comment" => $comment,
                "comment_id" => $comment_id,
                "address" => $address,
                "district" => $province,
                "city" => $city,
                "comment_status" => $comment_status,
				"url_check" => $url_check,
            );
            
            array_push($list, $listFile);
        }
    }
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    if (isset($stmt_count)) {
        mysqli_stmt_close($stmt_count);
    }
    echo json_encode($list);
    
} elseif ($action == 'get_comment_data') {
    
    if (isset($_REQUEST['date'])) {
        $dateSearch = $_REQUEST['date'];
    } else {
        $dateSearch = '1970/01/01';
    }
    if (isset($_REQUEST['values'])) {
        $values = $_REQUEST['values'];
    } else {
        $values = null;
    }
    if (isset($_REQUEST['limit'])) {
        $limit = (int) $_REQUEST['limit'];
    } else {
        $limit = 7;
    }
    if (isset($_REQUEST["page_comment"])) {
        $page = (int) $_REQUEST["page_comment"];
    } else {
        $page = 1;
    }
    
    $start_from = ($page - 1) * $limit;
    
    $link = mysqli_connect($host_photo, $username_photo, $password_photo) or die("failed to connect to server !!");
    mysqli_set_charset($link, 'utf8');
    
    mysqli_set_charset($link, 'utf8');
    mysqli_select_db($link, $dbname_photo);
    if ($values != null && !empty($values)) {
        $values = array_values($values);
        $placeholders = implode(",", array_fill(0, count($values), "?"));
        $query  = "SELECT DISTINCT * FROM ServiceComment WHERE status IN (" . $placeholders . ") AND timestamp > ? ORDER BY timestamp DESC LIMIT ?, ?";
        $query1 = "SELECT COUNT(*) FROM ServiceComment WHERE timestamp > ? AND status IN (" . $placeholders . ")";
        $params = $values;
        $params[] = $dateSearch;
        $params[] = $start_from;
        $params[] = $limit;
        $types = str_repeat("s", count($values)) . "sii";
        $stmt = mysqli_prepare($link, $query);
        if (!$stmt) {
            die(mysqli_error($link));
        }
        $bind_names = [];
        $bind_names[] = $types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bind_names);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $stmt_count = mysqli_prepare($link, $query1);
        if (!$stmt_count) {
            die(mysqli_error($link));
        }
        $params_count = $values;
        $params_count[] = $dateSearch;
        $types_count = str_repeat("s", count($values)) . "s";
        $bind_names_count = [];
        $bind_names_count[] = $types_count;
        for ($i = 0; $i < count($params_count); $i++) {
            $bind_names_count[] = &$params_count[$i];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bind_names_count);
        mysqli_stmt_execute($stmt_count);
        $result1 = mysqli_stmt_get_result($stmt_count);
    } else {
        $query  = "SELECT DISTINCT * FROM ServiceComment WHERE timestamp > ? ORDER BY timestamp DESC LIMIT ?, ?";
        $query1 = "SELECT COUNT(*) FROM ServiceComment WHERE timestamp > ?";
        $stmt = mysqli_prepare($link, $query);
        if (!$stmt) {
            die(mysqli_error($link));
        }
        mysqli_stmt_bind_param($stmt, "sii", $dateSearch, $start_from, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $stmt_count = mysqli_prepare($link, $query1);
        if (!$stmt_count) {
            die(mysqli_error($link));
        }
        mysqli_stmt_bind_param($stmt_count, "s", $dateSearch);
        mysqli_stmt_execute($stmt_count);
        $result1 = mysqli_stmt_get_result($stmt_count);
    }
    
    $result1 = $result1->fetch_array();
    $result1 = intval($result1[0]);
    
    $total_pages1 = ceil($result1 / $limit);
    
    $list = array();
    $num  = $result->num_rows;
    if ($num > 0) {
        while ($row1 = mysqli_fetch_array($result)) {
            
            $url    = $row1['serviceUri'];
            $url    = $url . '&format=json';
            $preUrl = $default_serviceUri;
            $url    = $preUrl . $url;
            if (($row1['latitude'] == Null) && ($row1['longitude'] == Null)) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result2 = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                $jsonfinale = json_decode($result2, true);
                if (isset($jsonfinale['Service'])) {
                    $coord[1] = $jsonfinale['Service']['features']['0']['geometry']['coordinates']['0'];
                    $coord[0] = $jsonfinale['Service']['features']['0']['geometry']['coordinates']['1'];
                    //$city     = $jsonfinale['Service']['features']['0']['properties']['city'];
                    //$province = $jsonfinale['Service']['features']['0']['properties']['province'];
                    //$address  = $jsonfinale['Service']['features']['0']['properties']['address'];
					$city       = "";
                    $province   = "";
                    $address    = "Not Found";
                } else {
                    $value_rand = rand(0, 15);
                    $coord[0]   = $default_latitude  . $value_rand;
                    $coord[1]   = $default_longitude . $value_rand;
                    $city       = "";
                    $province   = "";
                    $address    = "Not Found";
                    
                }
                $coord0 = $coord[0];
                $coord1 = $coord[1];
                //
				$stmt_update = mysqli_prepare($link, "UPDATE ServiceComment SET latitude = ?, longitude = ?, city = ?, province = ?, address = ? WHERE id = ?");
				if (!$stmt_update) {
					die(mysqli_error($link));
				}
				$city_safe = mysqli_real_escape_string($link, $city);
				$province_safe = mysqli_real_escape_string($link, $province);
				$address_safe = mysqli_real_escape_string($link, $address);
				$id_row = (int) $row1['id'];
				mysqli_stmt_bind_param($stmt_update, "ddsssi", $coord[0], $coord[1], $city_safe, $province_safe, $address_safe, $id_row);
                $result0 = mysqli_stmt_execute($stmt_update);
				mysqli_stmt_close($stmt_update);
                //
            } else {
                $coord0 = $row1['latitude'];
                $coord1 = $row1['longitude'];
            }
            
            $listFile = array(
                "id" => $row1['id'],
                "serviceUri" => $row1['serviceUri'],
                "serviceName" => $row1['serviceName'],
                "uid" => $row1['uid'],
                "status" => $row1['status'],
                "timestamp" => $row1['timestamp'],
                "lat" => $coord0,
                "lon" => $coord1,
                "comment" => $row1['comment'],
                "total_pages" => $total_pages1
            );
            
            array_push($list, $listFile);
        }
    }
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    if (isset($stmt_count)) {
        mysqli_stmt_close($stmt_count);
    }
    echo json_encode($list);
    
} elseif ($action == 'change-status') {
    $id         = intval($_REQUEST['id']);
    $new_status = $_REQUEST['new_status'];
    $stmt = mysqli_prepare($link, "UPDATE ServicePhoto SET status = ? WHERE id = ?");
    if (!$stmt) {
        die(mysqli_error($link));
    }
    mysqli_stmt_bind_param($stmt, "si", $new_status, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo ($id);
} elseif ($action == 'statusComment') {
    $id         = intval($_REQUEST['id']);
    $new_status = $_REQUEST['new_status'];
    $stmt = mysqli_prepare($link, "UPDATE ServiceComment SET status = ? WHERE id = ?");
    if (!$stmt) {
        die(mysqli_error($link));
    }
    mysqli_stmt_bind_param($stmt, "si", $new_status, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo ($id);
} elseif($action == 'rotate-image'){
	$image_name = $_REQUEST['image_name'];
	$directions =  $_REQUEST['direction'];
	//
	$stmt = mysqli_prepare($link, "SELECT id, ip FROM ServicePhoto WHERE file = ?");
	if (!$stmt) {
		die(mysqli_error($link));
	}
	mysqli_stmt_bind_param($stmt, "s", $image_name);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$num  = $result->num_rows;
	$list = array();
	$ip = '';
	$id = '';
    if ($num > 0) {
			while ($row1 = mysqli_fetch_array($result)) {
				//$listFile = array(
						$id = $row1['id'];
						$ip = $row1['ip'];
						//);
				//array_push($list, $listFile);
		}
	mysqli_stmt_close($stmt);
	}
	$url = $photo_service_api.'update-photo.jsp?id='.$id.'&rotate='.$directions;
	//echo ($url);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
} else {
    //nothing
    echo ('ERROR');
}
}else{
	header ("location:page.php");
}
?>
