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
        $limit = $_REQUEST['limit'];
    } else {
        $limit = 7;
    }
    
    if (isset($_REQUEST["page"])) {
        $page = $_REQUEST["page"];
    } else {
        $page = 1;
    }
    
    $start_from = ($page - 1) * $limit;
    
    $value = "";
    for ($i = 0; $i < count($values); $i++) {
        if ($i == count($values) - 1) {
            $value = $value . "'" . $values[$i] . "'";
        } else {
            $value = $value . "'" . $values[$i] . "'" . " OR status=";
        }
    }
    
    if (($values != null) || ($values != "") || (!empty($values))) {
        $query  = "SELECT DISTINCT * FROM ServicePhoto WHERE status=" . $value . " AND timestamp > '" . $dateSearch . "' ORDER BY timestamp DESC LIMIT " . $start_from . ", " . $limit;
        $query1 = "SELECT COUNT(*) FROM ServicePhoto WHERE timestamp > '$dateSearch' AND status=$value";
    } else {
        $query  = "SELECT DISTINCT * FROM ServicePhoto WHERE timestamp > '" . $dateSearch . "' ORDER BY timestamp DESC LIMIT " . $start_from . ", " . $limit . "";
        $query1 = "SELECT COUNT(*) FROM ServicePhoto WHERE timestamp > '$dateSearch'";
    }
    
    $result1 = mysqli_query($link, $query1) or die(mysqli_error($link));
    $result1     = $result1->fetch_array();
    $result1     = intval($result1[0]);
    $total_pages = ceil($result1 / $limit);
    
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $list = array();
    $num  = $result->num_rows;
    if ($num > 0) {
        while ($row1 = mysqli_fetch_array($result)) {
            $url    = $row1['serviceUri'];
            $url    = $url . '&format=json';
            $preUrl = 'https://servicemap.disit.org/WebAppGrafo/api/v1/?serviceUri=';
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
                    $city     = $jsonfinale['Service']['features']['0']['properties']['city'];
                    $province = $jsonfinale['Service']['features']['0']['properties']['province'];
                    $address  = $jsonfinale['Service']['features']['0']['properties']['address'];
                } else {
                    $coord[0] = 43.773;
                    $coord[1] = 11.252;
                    $city     = "";
                    $province = "";
                    $address  = "";
                }
                
                //
                $query0 = "UPDATE ServicePhoto SET latitude = '" . $coord[0] . "', longitude = '" . $coord[1] . "', city='" . $city . "', province='" . $province . "', address='" . $address . "' WHERE id='" . $row1['id'] . "'";
                $result0 = mysqli_query($link, $query0) or die(mysqli_error($link));
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
                "comment_status" => $comment_status
            );
            
            array_push($list, $listFile);
        }
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
        $limit = $_REQUEST['limit'];
    } else {
        $limit = 7;
    }
    if (isset($_REQUEST["page_comment"])) {
        $page = $_REQUEST["page_comment"];
    } else {
        $page = 1;
    }
    
    $start_from = ($page - 1) * $limit;
    
    if ($values != null) {
        $value = "";
        for ($i = 0; $i < count($values); $i++) {
            if ($i == count($values) - 1) {
                $value = $value . "'" . $values[$i] . "'";
            } else {
                $value = $value . "'" . $values[$i] . "'" . " OR status=";
            }
            
        }
    }
    
    $link = mysqli_connect($host_photo, $username_photo, $password_photo) or die("failed to connect to server !!");
    mysqli_set_charset($link, 'utf8');
    
    mysqli_set_charset($link, 'utf8');
    mysqli_select_db($link, $dbname_photo);
    if (isset($_REQUEST['values'])) {
        $values = $_REQUEST['values'];
        
    }
    
    $value = "";
    for ($i = 0; $i < count($values); $i++) {
        if ($i == count($values) - 1) {
            $value = $value . "'" . $values[$i] . "'";
        } else {
            $value = $value . "'" . $values[$i] . "'" . " OR status=";
        }
        
    }
    
    if ($values != null) {
        $query  = "SELECT DISTINCT * FROM ServiceComment WHERE status=$value AND timestamp>'" . $dateSearch . "' ORDER BY timestamp DESC LIMIT " . $start_from . ", " . $limit;
        $query1 = "SELECT COUNT(*) FROM ServiceComment WHERE timestamp > '" . $dateSearch . "' AND status=" . $value . "";
    } else {
        $query  = "SELECT DISTINCT * FROM ServiceComment WHERE timestamp > '" . $dateSearch . "' ORDER BY timestamp DESC LIMIT " . $start_from . ", " . $limit;
        $query1 = "SELECT COUNT(*) FROM ServiceComment WHERE timestamp > '" . $dateSearch . "'";
    }
    
    
    $result1 = mysqli_query($link, $query1) or die(mysqli_error($link));
    $result1 = $result1->fetch_array();
    $result1 = intval($result1[0]);
    
    $total_pages1 = ceil($result1 / $limit);
    
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $list = array();
    $num  = $result->num_rows;
    if ($num > 0) {
        while ($row1 = mysqli_fetch_array($result)) {
            
            $url    = $row1['serviceUri'];
            $url    = $url . '&format=json';
            $preUrl = 'https://servicemap.disit.org/WebAppGrafo/api/v1/?serviceUri=';
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
                    $city     = $jsonfinale['Service']['features']['0']['properties']['city'];
                    $province = $jsonfinale['Service']['features']['0']['properties']['province'];
                    $address  = $jsonfinale['Service']['features']['0']['properties']['address'];
                } else {
                    $value_rand = rand(0, 15);
                    $coord[0]   = '43.77' . $value_rand;
                    $coord[1]   = '11.252' . $value_rand;
                    $city       = "";
                    $province   = "";
                    $address    = "";
                    
                }
                $coord0 = $coord[0];
                $coord1 = $coord[1];
                //
                $query0 = "UPDATE ServiceComment SET latitude = '" . $coord[0] . "', longitude = '" . $coord[1] . "', city='" . $city . "', province='" . $province . "', address='" . $address . "' WHERE id='" . $row1['id'] . "'";
                $result0 = mysqli_query($link, $query0) or die(mysqli_error($link));
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
    echo json_encode($list);
    
} elseif ($action == 'change-status') {
    $id         = intval($_REQUEST['id']);
    $new_status = $_REQUEST['new_status'];
    $query      = "UPDATE ServicePhoto SET status = '" . $new_status . "' WHERE id='" . $id . "'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    echo ($id);
} elseif ($action == 'statusComment') {
    $id         = intval($_REQUEST['id']);
    $new_status = $_REQUEST['new_status'];
    $query3     = "UPDATE ServiceComment SET status = '" . $new_status . "' WHERE id='" . $id . "'";
    $result = mysqli_query($link, $query3) or die(mysqli_error($link));
    echo ($id);
} else {
    //nothing
    echo ('ERROR');
}
?>