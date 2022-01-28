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

require 'sso/autoload.php';
use Jumbojett\OpenIDConnectClient;  

include('config.php'); // Includes Login Script
include('external_service.php');
//
$link = mysqli_connect($host_heatmap, $username_heatmap, $password_heatmap) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_heatmap);
//
$action = $_REQUEST['action'];
if ($action == 'get_values') {
    $map_name0   = mysqli_real_escape_string($link, $_REQUEST['map_name']);
	$map_name  = filter_var($map_name0, FILTER_SANITIZE_STRING);
    //
    $list        = array();
    $page        = $_REQUEST['page'];
    $start_page  = $page * 10;
    //
	$order        = $_REQUEST['order'];
	//QUERY COUNT2
    $query_count2 = "SELECT DISTINCT COUNT(*) as 'tot'
    FROM  (SELECT DISTINCT
                data.map_name,
                data.date AS 'data_date'          
                FROM heatmap.data WHERE map_name='" . $map_name . "' GROUP BY date) t1
			JOIN (
				
				SELECT a.map_name, a.date FROM heatmap.metadata a LEFT JOIN heatmap.maps_completed b ON a.map_name=b.map_name AND a.date= b.date
				WHERE a.map_name = '" . $map_name . "'
			   
			   )t2 ON t1.map_name = t2.map_name AND t1.data_date = t2.date ORDER BY t1.data_date ";
				////////             
				/////
				$result_count2 = mysqli_query($link, $query_count2) or die(mysqli_error($link));
				
			   $total  = $result_count2->num_rows;
			   if ($result_count2->num_rows > 0) {
					while ($row02 = mysqli_fetch_assoc($result_count2)) {
							$total  =  $row02['tot'];
					}
			   }
	//
    $query_count = "SELECT DISTINCT *
    FROM  (SELECT DISTINCT
                data.map_name,
                Count(data.value)    AS 'number',
                data.date AS 'data_date',          
                min(data.latitude)   AS 'min_lat', 
                max(data.latitude)   AS 'max_lat', 
                min(data.longitude)  AS 'min_lon', 
                max(data.longitude) AS 'max_lon' 
                FROM heatmap.data WHERE map_name='" . $map_name . "' GROUP BY date ORDER BY date ".$order." LIMIT " . $start_page . ",10) t1
JOIN (
    
    SELECT a.map_name, a.description, b.completed, b.indexed, a.date, a.nature, a.subnature FROM heatmap.metadata a LEFT JOIN heatmap.maps_completed b ON a.map_name=b.map_name AND a.date= b.date
    WHERE a.map_name = '" . $map_name . "'
   
   )t2 ON t1.map_name = t2.map_name AND t1.data_date = t2.date ORDER BY t1.data_date ".$order;
    //AND data.date = maps_completed.date         
    /////////////
    $result_count = mysqli_query($link, $query_count) or die(mysqli_error($link));
	
   //$total  = mysql_result($result_count2,0);
    //
    $bbox   = '';
    $status = 1;
    if ($result_count->num_rows > 0) {
        while ($row0 = mysqli_fetch_assoc($result_count)) {
            
            $bbox     = '{"min_lat":"' . $row0['min_lat'] . '", "min_lon":"' . $row0['min_lon'] . '", "max_lat":"' . $row0['max_lat'] . '", "max_lon":"' . $row0['max_lon'] . '"}';
            $listFile = array(
                "value" => $row0['number'],
                "description" => $row0['description'],
                "status" => $row0['completed'],
                "date" => $row0['date'],
                "indexed" => $row0['indexed'],
                "bbox" => $bbox,
                //"id_completed" => $row0['id_completed'],
                //"data_id" => $row0['data_id'],
                "total" => $total
            );
            array_push($list, $listFile);
        }
    }
    
    echo json_encode($list);
    //
} elseif ($action == 'get_heatmaps') {
    $start_from = $_REQUEST['start_from'];
    $limit      = $_REQUEST['limit'];
    $query_n    = "SELECT metadata.map_name, maps_completed.completed, metadata.metric_name, metadata.description, metadata.date, metadata.nature, metadata.subnature FROM heatmap.metadata,heatmap.maps_completed WHERE metadata.map_name = maps_completed.map_name AND metadata.date = maps_completed.date ORDER BY metadata.date DESC LIMIT " . $start_from . ", " . $limit . ";";
    //
    $result = mysqli_query($link, $query_n) or die(mysqli_error($link));
    $process_list = array();
    $num_rows     = $result->num_rows;
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            
            $querybb = "SELECT map_name, MAX(longitude) AS 'max_long', MAX(latitude) AS 'max_lat', MIN(longitude) AS 'min_long', MIN(latitude) AS 'min_lat' FROM heatmap.data WHERE map_name='" . $row['map_name'] . "' GROUP BY map_name LIMIT 1";
            $resultbb = mysqli_query($link, $querybb) or die(mysqli_error($link));
            while ($rowbb = mysqli_fetch_assoc($resultbb)) {
                $bbox = $rowbb['min_lat'] . ',' . $rowbb['min_long'] . ', ' . $rowbb['max_lat'] . ',' . $rowbb['min_long'] . ', ' . $rowbb['min_lat'] . ',' . $rowbb['max_long'] . ', ' . $rowbb['max_lat'] . ',' . $rowbb['max_long'];
            }
            $bbox     = '';
            //
            $listFile = array(
                "map_name" => $row['map_name'],
                "metric_name" => $row['metric_name'],
                "description" => $row['description'],
                "completed" => $row['completed'],
				"nature" => $row['nature'],
                "subnature" => $row['subnature'],
                "date" => $row['date'],
                "bbox" => $bbox
            );
            array_push($process_list, $listFile);
        }
    }
    
    echo json_encode($process_list);
    //
}else if($action == 'edit_metadata'){
	//heatmap: heatmap,
	//nature: nature,
	//subnature: subnature,
	 $heatmap = $_REQUEST['heatmap'];
	$heatmap  = filter_var($heatmap, FILTER_SANITIZE_STRING);
	//
    $nature  = $_REQUEST['nature'];
	$nature  = filter_var($nature, FILTER_SANITIZE_STRING);
	//
    //$indexed       = $_REQUEST['indexed'];
    $subnature    = $_REQUEST['subnature'];
	$subnature = filter_var($subnature, FILTER_SANITIZE_STRING);
	
	$query1 = "UPDATE heatmap.metadata SET nature='" . $nature . "', subnature='" . $subnature . "' WHERE map_name='" . $heatmap  . "'";
	//
	echo($query1);
	//
	$result1 = mysqli_query($link, $query1) or die(mysqli_error($link));
	//
	//
	//
} else if ($action == 'delete_data') {
    $id        = $_REQUEST['id'];
    $date      = $_REQUEST['date'];
    $query_del = 'DELETE FROM heatmap.data WHERE data.map_name = "' . $id . '" AND data.date="' . $date . '"';
    $result_del = mysqli_query($link, $query_del) or die(mysqli_error($link));
    echo ('OK');
    
} else if ($action == 'get_type') {
    $metric    = $_REQUEST['metric'];
    $queryType = "SELECT * FROM heatmap.colors WHERE metric_name='" . $metric . "' ORDER BY colors.order ASC;";
    $resultType = mysqli_query($link, $queryType) or die(mysqli_error($link));
    //echo($resultType);
    $process_list = array();
    $num_rows     = $resultType->num_rows;
    if ($resultType->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($resultType)) {
            //
            $listFile = array(
                "id" => $row['id'],
                "metric_name" => $row['metric_name'],
                "min" => $row['min'],
                "max" => $row['max'],
                "rgb" => $row['rgb'],
                "color" => $row['color'],
                "order" => $row['order']
            );
            array_push($process_list, $listFile);
            //
        }
    }
    echo json_encode($process_list);
    //
} else if ($action == 'get_pages') {
    $query_n0         = "SELECT COUNT(*) AS 'number' FROM metadata";
    $total_rows_query = $query_n0;
    $result0 = mysqli_query($link, $total_rows_query) or die(mysqli_error($link));
    if ($result0->num_rows > 0) {
        while ($row0 = mysqli_fetch_assoc($result0)) {
            $total_rows = $row0['number'];
        }
    }
} else if ($action == 'delete_metadata') {
    //
    $id        = $_REQUEST['id'];
    $query_del = 'DELETE FROM heatmap.metadata WHERE metadata.map_name = "' . $id . '"';
    $result_del = mysqli_query($link, $query_del) or die(mysqli_error($link));
    if ($result_del) {
        echo ("OK");
    } else {
        echo ("KO");
    }
    //
} else if ($action == 'edit_metadata') {
    $id0 = $_REQUEST['id'];
	$id  = filter_var($id0, FILTER_SANITIZE_STRING);
	//
    $status0        = $_REQUEST['status'];
	$status  = filter_var($status0, FILTER_SANITIZE_STRING);
	//
    //$indexed       = $_REQUEST['indexed'];
    $data0    = $_REQUEST['date_val'];
	$data  = filter_var($data0, FILTER_SANITIZE_STRING);
	//
    $query_update2 = "UPDATE heatmap.maps_completed SET maps_completed.completed='" . $status . "' WHERE maps_completed.map_name='" . $id . "' AND maps_completed.date='" . $data . "'";
    $result_update2 = mysqli_query($link, $query_update2) or die(mysqli_error($link));
    echo ('OK');
} else if ($action == 'color_map') {
    $metric = $_REQUEST['metric'];
    
    $query_color = "SELECT * FROM heatmap.colors WHERE metric_name='" . $metric . "' order by colors.order";
    $result_color = mysqli_query($link, $query_color) or die(mysqli_error($link));
    $process_list = array();
    if ($result_color->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result_color)) {
            $listFile = array(
                "id" => $row['id'],
                "metricName" => $row['metric_name'],
                "min" => $row['min'],
                "max" => $row['max'],
                "rgb" => $row['rgb'],
                "color" => $row['color'],
                "order" => $row['order']
            );
            array_push($process_list, $listFile);
        }
    }
    echo json_encode($process_list);
    ////
} else if ($action == 'change_color_map') {
    $metric0 = $_REQUEST['valore'];
	$metric = filter_var($metric0, FILTER_SANITIZE_STRING);
	//
    $id0     = $_REQUEST['id'];
	$id = filter_var($id0, FILTER_SANITIZE_STRING);
	//
    $query1 = "UPDATE heatmap.metadata SET metric_name='" . $metric . "' WHERE map_name='" . $id . "'";
    $query2 = "UPDATE heatmap.maps_completed SET metric_name='" . $metric . "' WHERE map_name='" . $id . "'";
    $query3 = "UPDATE heatmap.data SET metric_name='" . $metric . "' WHERE map_name='" . $id . "'";
    //
    $result1 = mysqli_query($link, $query1) or die(mysqli_error($link));
    $result2 = mysqli_query($link, $query2) or die(mysqli_error($link));
    $result3 = mysqli_query($link, $query3) or die(mysqli_error($link));
    echo ('OK');
} else if ($action == 'color_map_create') {
    $name_color_map0 = $_REQUEST['name_color_map'];
	$name_color_map = filter_var($name_color_map0, FILTER_SANITIZE_STRING);
	//
    $array_min0 = $_REQUEST['paramMin0'];
	$array_min = filter_var_array($array_min0, FILTER_SANITIZE_STRING);
	//
    $array_max0      = $_REQUEST['paramMax0'];
	$array_max = filter_var_array($array_max0, FILTER_SANITIZE_STRING);
	//
    $array_rgb0      = $_REQUEST['paramRgb0'];
	$array_rgb = filter_var_array($array_rgb0, FILTER_SANITIZE_STRING);
	//
    $array_color0   = $_REQUEST['paramColor0'];
	$array_color  = filter_var_array($array_color0, FILTER_SANITIZE_STRING);
	//
    $array_order0   = $_REQUEST['paramOrder0'];
	$array_order  = filter_var_array($array_order0, FILTER_SANITIZE_STRING);
	//
    $lun            = count($array_rgb);
    //
    for ($i = 0; $i < $lun; $i++) {
        //
        $val_rgb  = explode('(', $array_rgb[$i]);
        $val_rgb2 = explode(')', $val_rgb[1]);
        $rgb      = '[' . $val_rgb2[0] . ']';
        //
        $min      = $array_min[$i];
        $max      = $array_max[$i];
        //
		
        $query    = "INSERT INTO heatmap.colors (`id`,`metric_name`, `min`, `max`, `rgb`, `color`, `order`)VALUES(NULL,'" . $name_color_map . "','" . $min . "','" . $max . "','" . $rgb . "','" . mysqli_real_escape_string($link, $array_color[$i]) . "'," . mysqli_real_escape_string($link, $array_order[$i]) . ")";
        if ($min == ''){
			$query    = "INSERT INTO heatmap.colors (`id`,`metric_name`, `max`, `rgb`, `color`, `order`)VALUES(NULL,'" . $name_color_map . "','" . $max . "','" . $rgb . "','" . mysqli_real_escape_string($link, $array_color[$i]) . "'," . mysqli_real_escape_string($link, $array_order[$i]) . ")";
		}
		if($max == ''){
			$query    = "INSERT INTO heatmap.colors (`id`,`metric_name`, `min`, `rgb`, `color`, `order`)VALUES(NULL,'" . $name_color_map . "','" . $min . "','" . $rgb . "','" . mysqli_real_escape_string($link, $array_color[$i]) . "'," . mysqli_real_escape_string($link, $array_order[$i]) . ")";
		}
		echo ($query.'<br />');
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
    }
    $sf    = $_REQUEST['showFrame'];
    $limit = $_REQUEST['limit'];
    $page  = $_REQUEST['page'];
    $by    = $_REQUEST['order'];
    $order = $_REQUEST['orderBy'];
    //
    header("location:colorMap.php?showFrame=" . $sf . "&page=" . $page . "&orderBy=" . $order . "&order=" . $by . "&limit=" . $limit);
    ///
} else if ($action == 'delete_colormap') {
    $id        = $_REQUEST['id'];
    $query_del = 'DELETE FROM heatmap.colors WHERE colors.metric_name= "' . $id . '"';
    $result_del = mysqli_query($link, $query_del) or die(mysqli_error($link));
    if ($result_del) {
        echo ("OK");
    } else {
        echo ("KO");
    }
    
} elseif ($action == 'modify_colormap') {
    $metric_name = $_REQUEST['metric_name'];
    echo ($metric_name);
    $paramId    = $_REQUEST['paramId'];
	$paramId   = filter_var_array($paramId, FILTER_SANITIZE_STRING);
	//
    $paramMin   = $_REQUEST['paramMin'];
	$paramMin   = filter_var_array($paramMin, FILTER_SANITIZE_STRING);
	//
    $paramMax   = $_REQUEST['paramMax'];
	$paramMax   = filter_var_array($paramMax, FILTER_SANITIZE_STRING);
	//
    $paramRgb   = $_REQUEST['paramRgb'];
	$paramRgb  = filter_var_array($paramRgb, FILTER_SANITIZE_STRING);
	//
    $paramColor = $_REQUEST['paramColor'];
	$paramColor = filter_var_array($paramColor, FILTER_SANITIZE_STRING);
	//
    $paramOrder = $_REQUEST['paramOrder'];
	$paramOrder = filter_var_array($paramOrder, FILTER_SANITIZE_STRING);
    ////
    $lun        = count($paramId);
    
    for ($i = 0; $i < $lun; $i++) {
        //
        $val_rgb  = explode('(', $paramRgb[$i]);
        $val_rgb2 = explode(')', $val_rgb[1]);
        $rgb      = '[' . $val_rgb2[0] . ']';
        //
        $query1   = "UPDATE heatmap.colors SET colors.min='" . $paramMin[$i] . "', colors.max='" . $paramMax[$i] . "', colors.rgb='" . $rgb . "', colors.color='" . mysqli_real_escape_string($link,$paramColor[$i]) . "', colors.order='" . mysqli_real_escape_string($link,$paramOrder[$i]) . "' WHERE colors.id='" . $paramId[$i] . "'";
        //
		if($paramMax[$i]==''){
			$query1   = "UPDATE heatmap.colors SET colors.min='" . $paramMin[$i] . "', colors.max=NULL, colors.rgb='" . $rgb . "', colors.color='" . mysqli_real_escape_string($link, $paramColor[$i]) . "', colors.order='" . mysqli_real_escape_string($link,$paramOrder[$i]) . "' WHERE colors.id='" . $paramId[$i] . "'";
		}
		if($paramMin[$i] == ''){
			$query1   = "UPDATE heatmap.colors SET colors.min=NULL, colors.max='" . $paramMax[$i] . "', colors.rgb='" . $rgb . "', colors.color='" . mysqli_real_escape_string($link, $paramColor[$i]) . "', colors.order='" . mysqli_real_escape_string($link,$paramOrder[$i]) . "' WHERE colors.id='" . $paramId[$i] . "'";
		}
		//
        $result1 = mysqli_query($link, $query1) or die(mysqli_error($link));
    }
    
    $paramMinMod = $_REQUEST['paramMinMod'];
    var_dump($paramMinMod);
    $paramMaxMod = $_REQUEST['paramMaxMod'];
    var_dump($paramMaxMod);
    $paramRgbMod   = $_REQUEST['paramRgbMod'];
    $paramColorMod = $_REQUEST['paramColorMod'];
    $paramOrderMod = $_REQUEST['paramOrderMod'];
    ////
    $lun2          = (count($paramRgbMod)) + $lun;
	
    for ($j = $lun; $j < $lun2; $j++) {
        //
        $val_rgb  = explode('(', $paramRgbMod[$j]);
        $val_rgb2 = explode(')', $val_rgb[1]);
        $rgb      = '[' . $val_rgb2[0] . ']';
        //
        $min      = $paramMinMod[$j];
        echo ($min);
        $max = $paramMaxMod[$j];
        echo ($max);
        //
        $query2 = "INSERT INTO heatmap.colors (`id`,`metric_name`, `min`, `max`, `rgb`, `color`, `order`)VALUES(NULL,'" . $metric_name . "','" . $paramMinMod[$j] . "','" . $paramMaxMod[$j] . "','" . $rgb . "','" . $paramColorMod[$j] . "','" . $paramOrderMod[$j] . "')";
        //
		if ($min==''){
			$query2 = "INSERT INTO heatmap.colors (`id`,`metric_name`, `max`, `rgb`, `color`, `order`)VALUES(NULL,'" . $metric_name . "','" . $paramMaxMod[$j] . "','" . $rgb . "','" . $paramColorMod[$j] . "','" . $paramOrderMod[$j] . "')";
		}
		//
		if ($max==''){
			$query2 = "INSERT INTO heatmap.colors (`id`,`metric_name`, `min`, `rgb`, `color`, `order`)VALUES(NULL,'" . $metric_name . "','" . $paramMinMod[$j] . "','" . $rgb . "','" . $paramColorMod[$j] . "','" . $paramOrderMod[$j] . "')";
		}
		//
		echo ($query2);
        $result2 = mysqli_query($link, $query2) or die(mysqli_error($link));
    }
    ////
	$paramdel = $_REQUEST['paramdel'];
	$lun3 = count($paramdel);
	for ($z = 0; $z < $lun3; $z++) {
			$id = $paramdel[$z];
			$query_del = 'DELETE FROM heatmap.colors WHERE colors.id= "' . $id . '"';
			$result_del = mysqli_query($link, $query_del) or die(mysqli_error($link));
	}
	/////
    $sf    = $_REQUEST['showFrame'];
    $limit = $_REQUEST['limit'];
    $page  = $_REQUEST['page'];
    $by    = $_REQUEST['order'];
    $order = $_REQUEST['orderBy'];
    header("location:colorMap.php?showFrame=".$sf."&page=".$page."&orderBy=".$order."&order=".$by."&limit=".$limit);
    ///////
}elseif($action == 'getdetails') {
	$id = $_REQUEST['id'];
	//$querybb = "SELECT map_name, MAX(longitude) AS 'max_long', MAX(latitude) AS 'max_lat', MIN(longitude) AS 'min_long', MIN(latitude) AS 'min_lat' FROM heatmap.data WHERE map_name='" . $id . "' GROUP BY map_name LIMIT 1";
	$query_istances = "SELECT metric_name, min_date, max_date, num AS count_number FROM heatmap.stats WHERE map_name='".$id."'";
	$result_istances = mysqli_query($link, $query_istances) or die(mysqli_error($link));
	//
	$list        = array();
	$count = mysqli_num_rows($result_istances);
	//
	if ($count > 0){	
	while ($row0 = mysqli_fetch_assoc($result_istances)) {
		//echo("$row0['min_date']: ".$row0['min_date']);
			$listFile = array(
                "min_date" => $row0['min_date'],
                "max_date" => $row0['max_date'],
                "count_number" => $row0['count_number']
            );
            array_push($list, $listFile);	
        }
	}else{
		$listFile = array(
                "min_date" => '',
                "max_date" => '',
                "count_number" => ''
            );
            array_push($list, $listFile);
	}
	//
	echo json_encode($list);
	//
}else if($action == 'get_owner'){
	$id_dash=$_REQUEST['id'];
	$list_api2 = array();
$list_api = array();
$array_pub = array();
$total_list = 0;
$array_del = array();
$utente = $_SESSION['username'];
//
$owner = "";
///INIZIO ERRORE /////

if (isset($_SESSION['accessToken'])){
$token = $_SESSION['accessToken'];
//echo($_SESSION['refreshToken']);
//
//if(($json_api == null)||($json_api == "")){
	//OPERAZIONI PER IL REFRESH//
			$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);	
            $oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
			$tkn = $oidc->refreshToken($_SESSION['refreshToken']);
			$accessToken = $tkn->access_token;
            $_SESSION['refreshToken'] = $tkn->refresh_token;
			$url_api =($personalDataApiBaseUrl.'/v1/list/?type=HeatmapID&accessToken='.$token);
			//DELEGATION PUBBLICHE + DELEGATION ALL'UTENTE
			//
			$json_api = file_get_contents($url_api);
	/*}else{
		    $url_api =($personalDataApiBaseUrl.'v1/list/?type=HeatmapID&accessToken='.$token);
		    $json_api = file_get_contents($url_api);
	}*/
	
	$list_api = json_decode($json_api);
	$total_list = count($list_api);
	//$owner['url'] = $url_api;
		for($c=0; $c<$total_list; $c++){
			//echo($c);
			$elementId = $list_api[$c]->elementId; 
			$username = $list_api[$c]->username;
			$list_api2[$elementId]=$username;
			if ($elementId == $id_dash){
				$owner['user'] = $username;
			}
		}			
}
echo json_encode($owner);
///
} else {
    echo ('ERROR');
}
?>