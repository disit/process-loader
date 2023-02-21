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

include('config.php'); // Includes Login Script
include('external_service.php');
$showFrame="";
	if (isset($_REQUEST['showFrame'])) {
		if ($_REQUEST['showFrame'] == 'false') {
                    $showFrame="?showFrame=false";
                }else{
					$showFrame="?showFrame=true";
				}
	}

function missingParameters($requiredParams) {
    $toreturn = array();

    foreach ($requiredParams as $param)
        if (!isset($_REQUEST[$param]))
            array_push($toreturn, $param);

    return $toreturn;
}

function retrieveAvailableStaticAttribute($url, $subnature, &$result) {
    $local_result = "";
    if (!array_key_exists("msg", $result))
        $result["msg"] = "";
    if (!array_key_exists("log", $result))
        $result["log"] = "";
    try {
        $url .= "list-static-attr?subnature=" . $subnature;

        $local_result = file_get_contents($url);
        $result["log"] .= $local_result;

        //TODO how to catch an 504
        if (($local_result !== FALSE) && (strpos($http_response_header[0], '200') == true || strpos($http_response_header[0], '204') == true)) {
            $result["status"] = 'ok';
            $result["content"] = $local_result;
            $result["msg"] .= '\n ok, returning dictionary';
            $result["log"] .= '\n ok, returning dictionary';
        } else {
            $result["status"] = 'ko';
            $result["error_msg"] = " ServiceMap not reacheable NOT reacheable";
            $result["msg"] .= '\n ko SM not reacheable';
            $result["log"] .= '\n ko SM not reacheable';
        }
    } catch (Exception $ex) {
        $result["status"] = 'ko';
        $result["error_msg"] = ' Error in accessing the SM. ';
        $result["msg"] .= '\n error in accessing the SM ';
        $result["log"] .= '\n error in accessing the SM ' . $ex;
    }
    return $result;
}

function my_log($result) {
    simple_log($result);
    echo json_encode($result);
}

function simple_log($result) {
    //TODO rotate
    $fp = fopen($GLOBALS["pathLog"], "a");
    if (!$fp) {
        //TODO create
        $result["status"] = 'ko';
        $result["error_msg"] = "\n Unable to open LOG file. Please contact an administrator";
    } else {
        flock($fp, LOCK_EX);
        $output = date("Y-m-d h:i:sa") . ": " . $result["log"] . "\r\n";
        fwrite($fp, $output);
        unset($result["log"]);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}

if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $link = mysqli_connect($host_valueunit, $username_valueunit, $password_valueunit) or die("failed to connect to server !!");
    mysqli_set_charset($link, 'utf8');
    mysqli_select_db($link, $dbname_valueunit);
    //
	//
    $action = mysqli_real_escape_string($connessione_al_server,$_REQUEST['action']);
	$action = filter_var($action , FILTER_SANITIZE_STRING);
	//
	if($action == 'check_values'){
		$check = strtolower($_REQUEST['check']);
		$check_type = $_REQUEST['check_type'];
		$process_list = array();
		$query_check = "SELECT * FROM processloader_db.dictionary_table WHERE value LIKE '%".$check."%' AND type='".$check_type."' AND status = 1";
		//$query_check = "SELECT tab1.*, tab2.parent FROM processloader_db.dictionary_table as tab1, dictionary_relations as tab2 WHERE tab1.value ='".$check."' AND tab1.type='".$check_type."' AND tab1.status = 1 and tab2.child = tab1.id";
		///////
		
		
		$result_check = mysqli_query($link, $query_check) or die(mysqli_error($link));
		while ($row = mysqli_fetch_assoc($result_check)) {
		/***/
		$id_row = $row['id'];
		$parent_id  = $row['parent'];
		$parent_value  = $row['parent'];
		//
		$process_parent_id = '';
		$process_parent_vl = '';
		/***/
		//if(($row['type'] == 'value unit')){
				//
				$process_parent_id = array();
				$process_parent_vl = array();
				$query_value =	"SELECT dictionary_relations.*,
									    dictionary_table.value
								 FROM   dictionary_relations,
										dictionary_table
								 WHERE  dictionary_relations.child='".$id_row."'
								 AND    dictionary_table.id = dictionary_relations.parent";
				
				$result_value = mysqli_query($link, $query_value) or die(mysqli_error($link));
						while ($row1 = mysqli_fetch_assoc($result_value)) {
							$parent_id1  = $row1['parent'];
							$parent_vl1 =  $row1['value'];
							//
							array_push($process_parent_id, $parent_id1);
							array_push($process_parent_vl, $parent_vl1);
							//
						}
				$parent_id = $process_parent_id;
				$parent_value = $process_parent_vl;
		//}
		/***/
		$process = array(
                "id" => $row['id'],
                "value" => $row['value'],
                "label" => $row['label'],
                "type" => $row['type'],
				"parent_id"=>$parent_id,
				"parent_value"=>$parent_value
            );
            array_push($process_list, $process);
        }
        echo json_encode($process_list);
		//
	}elseif ($action == 'get_values') {
        //
        //
		$value_type = mysqli_real_escape_string($connessione_al_server,$_REQUEST['value_type']);
		$value_type = filter_var($value_type , FILTER_SANITIZE_STRING);
		//
        if ($value_type == 1) {
			$value_type = "SELECT *, parent_id AS parent_value FROM dictionary_table WHERE type = 'value type' AND status = 1";
        } else {
            $value_type = "";
        }
		//
        //
        $value_unit = mysqli_real_escape_string($connessione_al_server,$_REQUEST['value_unit']);
		$value_unit= filter_var($value_unit , FILTER_SANITIZE_STRING);
		//
        if ($value_unit == 1) {
		
			$value_unit = "SELECT tab1.*, tab2.value AS parent_value
							FROM dictionary_table AS tab1
							INNER JOIN dictionary_table AS tab2 ON tab1.status=1 AND tab1.type='value unit' AND tab1.parent_id = tab2.id
							UNION
							SELECT DISTINCT tab1.*, tab1.parent_id AS parent_value
							FROM dictionary_table AS tab1
							WHERE tab1.type='value unit' AND (tab1.parent_id = '' OR tab1.parent_id = 0 OR tab1.parent_id is null) AND status=1";
			
        } else {
            $value_unit = "";
        }
		//
        $nature = mysqli_real_escape_string($connessione_al_server,$_REQUEST['nature']);
		$nature = filter_var($nature , FILTER_SANITIZE_STRING);
		//
        if ($nature == 1) {
			$nature = "SELECT tab1.*, tab2.value AS parent_value
							FROM dictionary_table AS tab1
							INNER JOIN dictionary_table AS tab2 ON tab1.status=1 AND tab1.type='nature' AND tab1.parent_id = tab2.id
							UNION
							SELECT DISTINCT tab1.*, tab1.parent_id AS parent_value
							FROM dictionary_table AS tab1
							WHERE tab1.type='nature' AND (tab1.parent_id = '' OR tab1.parent_id = 0 OR tab1.parent_id is null) AND status=1";
        } else {
            $nature = "";
        }
		//
        $subnature = mysqli_real_escape_string($connessione_al_server,$_REQUEST['subnature']);
		$subnature = filter_var($subnature , FILTER_SANITIZE_STRING);
		//
        if ($subnature == 1) {
           //
		   //
		   
		   $subnature = "SELECT tab1.*, tab2.value AS parent_value
					FROM dictionary_table AS tab1
					INNER JOIN dictionary_table AS tab2 ON tab1.status=1 AND tab1.type='subnature' AND tab1.parent_id = tab2.id";
			
        } else {
            $subnature = "";
        }
		//
		$data_type = mysqli_real_escape_string($connessione_al_server,$_REQUEST['data_type']);
		$data_type = filter_var($data_type , FILTER_SANITIZE_STRING);
		 if ($data_type == 1) {
           //
		   //
			$data_type = "SELECT tab1.*, tab2.value AS parent_value
							FROM dictionary_table AS tab1
							INNER JOIN dictionary_table AS tab2 ON tab1.status=1 AND tab1.type='data type' AND tab1.parent_id = tab2.id
							UNION
							SELECT DISTINCT tab1.*, tab1.parent_id AS parent_value
							FROM dictionary_table AS tab1
							WHERE tab1.type='data type' AND (tab1.parent_id = '' OR tab1.parent_id = 0 OR tab1.parent_id is null) AND status=1";
			////////////		
			
        } else {
            $data_type = "";
        }
        //
        $select_all = mysqli_real_escape_string($connessione_al_server,$_REQUEST['select_all']);
		$action = filter_var($action , FILTER_SANITIZE_STRING);
		//
		
        if ($select_all == 1) {

			$query = "SELECT tab1.*, tab2.value AS parent_value FROM dictionary_table AS tab1
						INNER JOIN dictionary_table AS tab2 ON tab1.status=1 AND tab1.type='subnature' AND tab1.parent_id = tab2.id
						UNION
						SELECT tab1.*, tab2.value AS parent_value FROM dictionary_table AS tab1
						INNER JOIN dictionary_table AS tab2 ON tab1.status=1 AND tab1.type='value unit' AND tab1.parent_id = tab2.id
						UNION
						SELECT tab1.*, tab1.parent_id AS parent_value
						FROM dictionary_table AS tab1 WHERE tab1.status=1 AND tab1.type <>'subnature' AND tab1.type <>'value unit'
						UNION
						SELECT DISTINCT tab1.*, tab1.parent_id AS parent_value
						FROM dictionary_table AS tab1
						WHERE tab1.type='value unit' AND status=1 AND (tab1.parent_id = '' OR tab1.parent_id = 0 OR tab1.parent_id is null)
						UNION
						SELECT DISTINCT tab1.*, tab1.parent_id AS data_type
						FROM dictionary_table AS tab1
						WHERE tab1.type='data type' AND status=1 AND (tab1.parent_id = '' OR tab1.parent_id = 0 OR tab1.parent_id is null)
						order by value;";
			
		} else {
            $query = "";
            if ($value_unit !== "") {
                if ($query !== "") {
                    $query = $query . " UNION ";
                }
                $query = $query . " " . $value_unit;
            }
			if ($value_type !== "") {
                if ($query !== "") {
                    $query = $query . " UNION ";
                }
                $query = $query . " " . $value_type;
            }
            if ($nature !== "") {
                if ($query !== "") {
                    $query = $query . " UNION ";
                }
                $query = $query . " " . $nature;
            }
            if ($subnature !== "") {
                if ($query !== "") {
                    $query = $query . " UNION ";
                }
				$query = $query . " " . $subnature;
            }
			if ($data_type !== "") {
                if ($query !== "") {
                    $query = $query . " UNION ";
                }
                $query = $query . " " . $data_type;
            }
            
            if ($query == "") {
                
				$query = "SELECT tab1.*, tab2.value AS parent_value FROM dictionary_table AS tab1
						INNER JOIN dictionary_table AS tab2 ON tab1.status=1 AND tab1.type='subnature' AND tab1.parent_id = tab2.id
						UNION
						SELECT tab1.*, tab2.value AS parent_value FROM dictionary_table AS tab1
						INNER JOIN dictionary_table AS tab2 ON tab1.status=1 AND tab1.type='value unit' AND tab1.parent_id = tab2.id
						UNION
						SELECT tab1.*, tab1.parent_id AS parent_value
						FROM dictionary_table AS tab1 WHERE tab1.status=1 AND tab1.type <>'subnature' AND tab1.type <>'value unit'
						UNION
						SELECT DISTINCT tab1.*, tab1.parent_id AS parent_value
						FROM dictionary_table AS tab1
						WHERE tab1.type='value unit' AND status=1 AND (tab1.parent_id = '' OR tab1.parent_id = 0 OR tab1.parent_id is null)
						UNION
						SELECT DISTINCT tab1.*, tab1.parent_id AS data_type
						FROM dictionary_table AS tab1
						WHERE tab1.type='data type' AND status=1 AND (tab1.parent_id = '' OR tab1.parent_id = 0 OR tab1.parent_id is null)
						order by value;";
            }else{
				//echo($query);
				$query = $query . " order by value;";
			}
        }
		
		
		
        $process_list = array();
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        while ($row = mysqli_fetch_assoc($result)) {
			//
			$child_value = "";
			$dt_value = "";
			$parent_id = $row['parent_id'];
			$parent_value = $row['parent_value'];
			$id_row = $row['id'];
			//if(($row['type'] =='subnature')||($row['type'] == 'value unit')){
				if(($row['type'] == 'value unit')){
				//
				$process_parent_id = array();
				$process_parent_vl = array();
				//
			$query_value =	"SELECT dictionary_relations.*,
									dictionary_table.value
							 FROM   dictionary_relations,
									dictionary_table
							 WHERE  dictionary_relations.child='".$id_row."'
						  	 AND    dictionary_table.id = dictionary_relations.parent";
				//
				//
				$result_value = mysqli_query($link, $query_value) or die(mysqli_error($link));
						while ($row1 = mysqli_fetch_assoc($result_value)) {
							$parent_id1  = $row1['parent'];
							$parent_vl1 =  $row1['value'];
							//
							array_push($process_parent_id, $parent_id1);
							array_push($process_parent_vl, $parent_vl1);
							//
						}
				$parent_id = $process_parent_id;
				$parent_value = $process_parent_vl;
				//
			}
			
			if(($row['type'] == 'nature')||($row['type'] == 'value type')){
				//
				$process_parent_id = array();
				$process_parent_vl = array();
				//
			$query_value =	"SELECT dictionary_relations.*,
									dictionary_table.value
							 FROM   dictionary_relations,
									dictionary_table
							 WHERE  dictionary_relations.parent='".$id_row."'
						  	 AND    dictionary_table.id = dictionary_relations.child
							 AND (dictionary_table.type ='value unit' OR dictionary_table.type ='subnature')";
				//
				//
				$result_value = mysqli_query($link, $query_value) or die(mysqli_error($link));
						while ($row1 = mysqli_fetch_assoc($result_value)) {
							$parent_id1  = $row1['parent'];
							$parent_vl1 =  $row1['value'];
							//
							array_push($process_parent_id, $parent_id1);
							array_push($process_parent_vl, $parent_vl1);
							//
						}
				$parent_id = "";
				$parent_value = "";
				$child_value = $process_parent_vl;
				//
			}
			/////////////DATA TYPE PER I VALUE TYPE
			if(($row['type'] == 'value type')){
				//
				$process_dt_id = array();
				$process_dt_vl = array();
				//
			$query_value =	"SELECT dictionary_relations.*,
									dictionary_table.value
							 FROM   dictionary_relations,
									dictionary_table
							 WHERE  dictionary_relations.parent='".$id_row."'
						  	 AND    dictionary_table.id = dictionary_relations.child
							 AND dictionary_table.type ='data type' AND dictionary_table.status=1";
				//
				//
				$result_value = mysqli_query($link, $query_value) or die(mysqli_error($link));
						while ($row1 = mysqli_fetch_assoc($result_value)) {
							$parent_id1  = $row1['parent'];
							$parent_vl1 =  $row1['value'];
							//
							array_push($process_dt_id, $parent_id1);
							array_push($process_dt_vl, $parent_vl1);
							//
						}
				//$parent_id = "";
				//$parent_value = "";
				$dt_value = $process_dt_vl;
				//
			}
			//////////////DATA TYPE////
			if(($row['type'] == 'data type')){
				//
				$process_dt_id = array();
				$process_dt_vl = array();
				//
			$query_value =	"SELECT dictionary_relations.*,
									dictionary_table.value
							 FROM   dictionary_relations,
									dictionary_table
							 WHERE  dictionary_relations.child='".$id_row."'
						  	 AND    dictionary_table.id = dictionary_relations.parent";
				//
				//
				$result_value = mysqli_query($link, $query_value) or die(mysqli_error($link));
						while ($row1 = mysqli_fetch_assoc($result_value)) {
							$parent_id1  = $row1['parent'];
							$parent_vl1 =  $row1['value'];
							//
							array_push($process_dt_id, $parent_id1);
							array_push($process_dt_vl, $parent_vl1);
							//
						}
				$parent_id = $process_dt_id;
				$parent_value = $process_dt_vl;
				//$dt_value = $process_parent_vl;
				//
			}
			///////////////FINE DATA TYPE////
			//
            $process = array(
                "id" => $row['id'],
                "value" => $row['value'],
                "label" => $row['label'],
                "type" => $row['type'],
				"parent_id"=>$parent_id,
				"parent_value"=>$parent_value,
				"child_value"=>$child_value,
				"data_type" => $dt_value
            );
            array_push($process_list, $process);
        }
        echo json_encode($process_list);
						mysqli_close($link);
						//
        //
    } else if ($action == 'create_voice') {
        //
        $vn_create            = mysqli_real_escape_string($connessione_al_server, $_REQUEST['vn_create']);
        $lb_create            = mysqli_real_escape_string($connessione_al_server, $_REQUEST['lb_create']);
        $select_type_creation = mysqli_real_escape_string($connessione_al_server, $_REQUEST['select_type_creation']);
		//
		$select_nature = null;
		//
		//
		$query_check="Select * FROM dictionary_table WHERE value LIKE'".$vn_create."' AND status = 1";
		$result_check = mysqli_query($link, $query_check) or die('ERROR NELLA QUERY');
		$n_row = mysqli_num_rows($result_check);
		
		if($n_row >0){
			//echo($n_row);
			header("location:dictionary_editor.php".$showFrame);
		}else{
        //
		if($select_type_creation == 'subnature'){
		$select_nature = mysqli_real_escape_string($connessione_al_server, $_REQUEST['select_nature']);
		}else{
			$select_nature = null;
		}
		if($select_type_creation == 'value unit'){
			//
			$select_vtype = filter_var_array($_REQUEST['select_vtype'], FILTER_SANITIZE_STRING);
			
			//
		}else{
			$select_vtype = null;
		}
		if($select_type_creation == 'data type'){
			//
			$select_vtype = filter_var_array($_REQUEST['select_vtype'], FILTER_SANITIZE_STRING);
			
			//
		}else{
			$select_vtype = null;
		}
		//
		$vn_create = filter_var($vn_create , FILTER_SANITIZE_STRING);
		$vn_create = str_replace(' ', '', $vn_create);
		//
		$lb_create = filter_var($lb_create , FILTER_SANITIZE_STRING);
		$lb_create = str_replace(' ', '', $lb_create);
		//
		$select_type_creation = filter_var($select_type_creation , FILTER_SANITIZE_STRING);
		$insert_date = date("Y-m-d h:i:s");
		//
		if($select_type_creation == 'subnature'){
			$query = 'INSERT INTO dictionary_table (value, label, type, status, parent_id, insert_time) VALUE ("' . $vn_create . '","' . $lb_create . '","' . $select_type_creation . '", 1, '.$select_nature.', "'.$insert_date.'")';
        }else if($select_type_creation == 'value unit'){
			$query = 'INSERT INTO dictionary_table (value, label, type, status, parent_id, insert_time)
					  VALUE 
					 ("' . $vn_create . '","' . $lb_create . '","' . $select_type_creation . '",1,0, "'.$insert_date.'")';
        }else{
			$query = 'INSERT INTO dictionary_table (value, label, type, status, insert_time) VALUE ("' . $vn_create . '","' . $lb_create . '","' . $select_type_creation . '", 1, "'.$insert_date.'")';	
		}
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
		//
		//
		
		////VALUE UNIT dictionary_relations
		if($select_type_creation == 'value unit'){
		   //
			//
			
			$query_retrieve_max = "SELECT MAX(id) as Id FROM dictionary_table";
			$result_max = mysqli_query($link, $query_retrieve_max) or die(mysqli_error($link));
			///
			$id_r = "";
			while ($row0 = mysqli_fetch_assoc($result_max)) {
				$id_r = $row0['Id'];
			}
			//
			$lun = count($select_vtype);
			
			//
			for ($r = 0; $r < $lun; $r++){
				$vt = $select_vtype[$r];
				$query_relations='INSERT INTO dictionary_relations (child, parent) VALUE ("'.$id_r.'","'.$vt.'")';
				$result_relations = mysqli_query($link, $query_relations) or die(mysqli_error($link));
				
			}
			
		}

		////DATA TYPE dictionary_relations
		if($select_type_creation == 'data type'){
		   //
			//
			
			$query_retrieve_max = "SELECT MAX(id) as Id FROM dictionary_table";
			$result_max = mysqli_query($link, $query_retrieve_max) or die(mysqli_error($link));
			///
			
			print_r($result_max);
			$id_r = "";
			while ($row0 = mysqli_fetch_assoc($result_max)) {
				$id_r = $row0['Id'];
				//
			}
			//
			$lun = count($select_vtype);
			//
			for ($r = 0; $r < $lun; $r++){
				$vt = $select_vtype[$r];
				$query_relations='INSERT INTO dictionary_relations (child, parent) VALUE ("'.$id_r.'","'.$vt.'")';
				$result_relations = mysqli_query($link, $query_relations) or die(mysqli_error($link));
				//echo($query_relations);
				
			}
			
		}
		
		////SUBNATURE dictionary_relations
		if($select_type_creation == 'subnature'){
			//
			//
			
			$query_retrieve_max = "SELECT MAX(id) as Id FROM dictionary_table";
			$result_max = mysqli_query($link, $query_retrieve_max) or die(mysqli_error($link));
			///
			
			print_r($result_max);
			$id_r = "";
			while ($row0 = mysqli_fetch_assoc($result_max)) {
				$id_r = $row0['Id'];
				//
			}
			//
			//$lun = count($select_vtype);
			//
			//for ($r = 0; $r < $lun; $r++){
				$vt = $select_nature;
				$query_relations='INSERT INTO dictionary_relations (child, parent) VALUE ("'.$id_r.'","'.$vt.'")';
				$result_relations = mysqli_query($link, $query_relations) or die(mysqli_error($link));
				echo($query_relations);
				
			//}
			
		}
		
		
		//
     header("location:dictionary_editor.php".$showFrame);
	}
        //
    } else if ($action == 'delete_voice') {
        //
        $id    = mysqli_real_escape_string($connessione_al_server, $_REQUEST['id']);
		$delete_date = date("Y-m-d h:i:s");
		$query                = "UPDATE dictionary_table SET delete_time='".$delete_date."', status=0 WHERE id=" . $id;
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        header("location:dictionary_editor.php".$showFrame);
        //
    } else if ($action == 'edit_voice') {
        //
        $id                   = mysqli_real_escape_string($connessione_al_server, $_REQUEST['id']);
        $vn_create            = mysqli_real_escape_string($connessione_al_server, $_REQUEST['vn_create_e']);
        $lb_create            = mysqli_real_escape_string($connessione_al_server, $_REQUEST['label_create_e']);
        $select_type_creation = mysqli_real_escape_string($connessione_al_server, $_REQUEST['select_type_creation']);
		$select_dt_e          = $_REQUEST['select_dt_e'];
		//

		//
		$vn_create = filter_var($vn_create , FILTER_SANITIZE_STRING);
		$vn_create = str_replace(' ', '', $vn_create);
		//
		$lb_create = filter_var($lb_create , FILTER_SANITIZE_STRING);
		$lb_create = str_replace(' ', '', $lb_create);
		//
		$select_type_creation = filter_var($select_type_creation , FILTER_SANITIZE_STRING);
		$select_dt_e = filter_var($select_dt_e , FILTER_SANITIZE_STRING);
		//
        //
		//
		$select_nature = null;
		if($select_type_creation == 'subnature'){
		$select_nature = mysqli_real_escape_string($connessione_al_server, $_REQUEST['select_nature_e']);
		//
		 $query  = "UPDATE dictionary_table SET label='" . $lb_create . "', parent_id='".$select_nature."'  WHERE id=" . $id;
		//
		}

		
		//
		if($select_type_creation == 'value unit'){
		//
		$query  = "UPDATE dictionary_table SET label='" . $lb_create . "' WHERE id=" . $id;
		//
		}

		
		if(($select_type_creation == 'nature')||($select_type_creation == 'value type')||($select_type_creation == 'data type')){
			 $query  = "UPDATE dictionary_table SET label='" . $lb_create . "'  WHERE id=" . $id;
			 //
		}
		//
		///
		 $result = mysqli_query($link, $query) or die(mysqli_error($link));
		///
		//
		//if($select_type_creation == 'value unit'){
		if(($select_type_creation == 'value unit')||($select_type_creation == 'data type')){
		//if(($select_type_creation == 'value unit')||($select_type_creation == 'data type')||($select_type_creation == 'subnature')){
			/*ELIMINA TUTTI I LINK CHE CI SONO?*/
			//
			$select_vt_e = filter_var_array($_REQUEST['select_vt_e'], FILTER_SANITIZE_STRING);
			$c_rel = count($select_vt_e);
			//print_r($select_vt_e);
			//echo($c_rel);
			//
			$query_rel = "DELETE FROM dictionary_relations WHERE child=".$id;
			//echo($query_rel);
			$result_rel = mysqli_query($link, $query_rel) or die(mysqli_error($link));
			/*CREARLI EX_NOVO*/
			for ($r = 0; $r < $c_rel; $r++){
				$vt = $select_vt_e[$r];
				$query_relations='INSERT INTO dictionary_relations (child, parent) VALUE ("'.$id.'","'.$vt.'")';
				$result_relations = mysqli_query($link, $query_relations) or die(mysqli_error($link));
				}
		}
		if($select_type_creation == 'value type'){
			/*ELIMINA TUTTI I LINK CHE CI SONO?*/
			//
			$select_dt_e = filter_var_array($_REQUEST['select_dt_e']);
			$c_rel = count($select_dt_e);
			//
			$query_rel = "DELETE FROM dictionary_relations WHERE parent='".$id."' AND child IN (SELECT id FROM dictionary_table WHERE type = 'data type')";
							$result_rel = mysqli_query($link, $query_rel) or die(mysqli_error($link));
			//
			for ($r = 0; $r < $c_rel; $r++){
				$vt = $select_dt_e[$r];
				//
				$query_selection='SELECT * FROM dictionary_relations WHERE parent="'.$id.'" AND child="'.$vt.'"';
				$result_selection = mysqli_query($link, $query_selection) or die(mysqli_error($link));
				//
				$query_rel = "DELETE FROM dictionary_relations WHERE parent='".$id."' AND child='".$vt."'";
				$result_rel = mysqli_query($link, $query_rel) or die(mysqli_error($link));
				//
				//
						if($result_selection->num_rows === 0){
							$query_relations='INSERT INTO dictionary_relations (parent, child) VALUE ("'.$id.'","'.$vt.'")';
							$result_relations = mysqli_query($link, $query_relations) or die(mysqli_error($link));
						}
				}
		}
		
		/////////////////////MODIFY NATURE for SUBNATURE//////////////////////////////////////////
		if($select_type_creation == 'subnature'){
			/*ELIMINA TUTTI I LINK CHE CI SONO?*/
			//
			$nat = $_REQUEST['select_nature_e'];
			$c_rel = count($select_vt_e);
			echo('nat: '.$nat);
			//echo($c_rel);
			//
			$query_rel = "DELETE FROM dictionary_relations WHERE child=".$id;
			//echo($query_rel);
			$result_rel = mysqli_query($link, $query_rel) or die(mysqli_error($link));
			/*CREARLI EX_NOVO*/
				$query_relations='INSERT INTO dictionary_relations (child, parent) VALUE ("'.$id.'",'.$nat.')';
				echo($query_relations);
				$result_relations = mysqli_query($link, $query_relations) or die(mysqli_error($link));
		}
		/////////////
       
    header("location:dictionary_editor.php".$showFrame);
        //
    } elseif ($action == 'dt_voice') {
		//
		//
		$process_list = array();
        $query = "SELECT DISTINCT * FROM dictionary_table WHERE type='data type' AND status = 1";
		$result_rel = mysqli_query($link, $query) or die(mysqli_error($link));
		//
		while ($row = mysqli_fetch_assoc($result_rel)) {
				$process = array(
						"id" => $row['id'],
						"value" => $row['value'],
						"label" => $row['label'],
						"type" => $row['type']
					);
					array_push($process_list, $process);
				}
			echo json_encode($process_list);
		//
    }
	 
	 // GET STATIC ATTRIBUTES
    else if ($action == 'get_available_static') {
		$missingParams = missingParameters(array('subnature'));

		$newresult = array("status" => "", "msg" => "", "content" => "", "log" => "", "error_msg" => "");
		$newresult['status'] = 'ok';

		if (!empty($missingParams)) {
			$newresult["status"] = "ko";
			$newresult['msg'] = "Missing Parameters";
			$newresult["error_msg"] .= "Problem getting available static (Missing parameters: " . implode(", ", $missingParams) . " )";
			$newresult["log"] = "action=get_available_static - error Missing Parameters: " . implode(", ", $missingParams) . " \r\n";
		} else {
			$subnature = mysqli_real_escape_string($link, $_REQUEST['subnature']);

			$urls = explode(";", $kb_urls);
			foreach ($urls as &$url) {
				retrieveAvailableStaticAttribute($url, $subnature, $result);
				if ($result["status"] == 'ok') {
					break;
				} 
			}

			if ($result["status"] == 'ok') {
					$newresult['availibility'] = $result["content"];
					$newresult['log'] .= "\n Returning " . $result["content"];
			} else {
					$newresult['status'] = 'ko';
					$newresult['error_msg'] = 'Problem contacting the Snap4City server (Service map list-static-attr). Please try later';
					$newresult['log'] .= '\n Problem contacting the Snap4City server (Service map list-static-attr)';
			}

			$newresult['log'] .= '\n\naction:get_available_static';
		}
		my_log($newresult);
    }
    
    // INSERT STATIC ATTRIBUTES
    else if ($action == 'insert_static_attr') {
      $urls = explode(";", $kb_urls);
      if (isset($_REQUEST['graph']) && isset($_REQUEST['subnature']) && isset($_REQUEST['attribute']) && isset($_REQUEST['range']) && isset($_REQUEST['label']) ){
			$graph = $_REQUEST['graph'];
			$subnature = $_REQUEST['subnature'];
			$attribute = $_REQUEST['attribute'];
			$range = $_REQUEST['range'];
			$label = $_REQUEST['label'];
			$accessToken = $_SESSION['accessToken'];
			foreach ($urls as &$url) {
				$url = $url."insert-static-attr";
				$data = array(
					"graph" => $graph,
					"subnature" => $subnature,
					"attribute" => $attribute,
					"range" => $range,
					"label" => $label
				);
				$data_string = json_encode($data);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($data_string),
					'Authorization: Bearer ' . $accessToken
				));
				$result = curl_exec($ch);
				if ($result !== $attribute) {
					echo $url.'\n'.$result;
				}
				
				curl_close($ch);
			}
			echo json_encode('OK');
		} else {
			echo ('ERROR');
		}
    }

	 // DELETE STATIC ATTRIBUTES
    else if ($action == 'delete_static_attr') {
      $urls = explode(";", $kb_urls);
      if (isset($_REQUEST['graph']) && isset($_REQUEST['subnature']) && isset($_REQUEST['attribute']) && isset($_REQUEST['label']) ){
			$graph = $_REQUEST['graph'];
			$subnature = $_REQUEST['subnature'];
			$attribute = $_REQUEST['attribute'];
			$label = $_REQUEST['label'];
			$accessToken = $_SESSION['accessToken'];
			foreach ($urls as &$url) {
				$url = $url."delete-static-attr";
				$data = array(
					"graph" => $graph,
					"subnature" => $subnature,
					"attribute" => $attribute,
					"label" => $label
				);
				$data_string = json_encode($data);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($data_string),
					'Authorization: Bearer ' . $accessToken
				));
				$result = curl_exec($ch);
				if ($result !== $attribute) {
					echo $url.'\n'.$result;
				}
				
				curl_close($ch);
			}
			echo json_encode('OK');
		} else {
			echo ('ERROR');
		}
    }
 
	 else {
        echo ('ERROR');
    } 
} else {
    header("location:dictionary_editor.php".$showFrame);
}
?>