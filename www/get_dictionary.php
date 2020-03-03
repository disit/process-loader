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
                $query = $query . " " . $subnature." order by value";
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
						ORDER BY value;";
            }
        }
		
        //echo($query);
		
		
        $process_list = array();
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        while ($row = mysqli_fetch_assoc($result)) {
			//
			$child_value = "";
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
						  	 AND    dictionary_table.id = dictionary_relations.child";
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
			//
            $process = array(
                "id" => $row['id'],
                "value" => $row['value'],
                "label" => $row['label'],
                "type" => $row['type'],
				"parent_id"=>$parent_id,
				"parent_value"=>$parent_value,
				"child_value"=>$child_value
            );
            array_push($process_list, $process);
        }
        echo json_encode($process_list);
        mysqli_close($link);
        //
    } else if ($action == 'create_voice') {
        //
        $vn_create            = mysqli_real_escape_string($connessione_al_server, $_REQUEST['vn_create']);
        $lb_create            = mysqli_real_escape_string($connessione_al_server, $_REQUEST['lb_create']);
        $select_type_creation = mysqli_real_escape_string($connessione_al_server, $_REQUEST['select_type_creation']);
		$select_nature = null;
		//
		$query_check="Select * FROM dictionary_table WHERE value LIKE'".$vn_create."' "; 
		$result_check = mysqli_query($link, $query_check) or die('ERROR NELLA QUERY');
		$n_row = mysqli_num_rows($result_check);
		
		if($n_row >0){
			echo($n_row);
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
		//
		$vn_create = filter_var($vn_create , FILTER_SANITIZE_STRING);
		$lb_create = filter_var($lb_create , FILTER_SANITIZE_STRING);
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
		
		if($select_type_creation == 'value unit'){
		   //
			//
			
			$query_retrieve_max = "SELECT MAX(id) as Id FROM dictionary_table";
			$result_max = mysqli_query($link, $query_retrieve_max) or die(mysqli_error($link));
			///
			
			print_r($result_max);
			$id_r = "";
			while ($row0 = mysqli_fetch_assoc($result_max)) {
				$id_r = $row0['Id'];
				echo($id_r);
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
		//
		$vn_create = filter_var($vn_create , FILTER_SANITIZE_STRING);
		$lb_create = filter_var($lb_create , FILTER_SANITIZE_STRING);
		$select_type_creation = filter_var($select_type_creation , FILTER_SANITIZE_STRING);
        //
		//
		$select_nature = null;
		if($select_type_creation == 'subnature'){
		$select_nature = mysqli_real_escape_string($connessione_al_server, $_REQUEST['select_nature_e']);
		//
		 $query  = "UPDATE dictionary_table SET value='" . $vn_create . "', label='" . $lb_create . "', type='" . $select_type_creation . "', parent_id='".$select_nature."'  WHERE id=" . $id;
		//
		}else{
		$select_nature = null;
		//
		 $query  = "UPDATE dictionary_table SET value='" . $vn_create . "', label='" . $lb_create . "', type='" . $select_type_creation . "', parent_id=null  WHERE id=" . $id;
		//
		}
		
		//
		if($select_type_creation == 'value unit'){
		//
		$query  = "UPDATE dictionary_table SET value='" . $vn_create . "', label='" . $lb_create . "', type='" . $select_type_creation . "'  WHERE id=" . $id;
		//
		}else{
		$select_vt_e = null;
		//
		 $query  = "UPDATE dictionary_table SET value='" . $vn_create . "', label='" . $lb_create . "', type='" . $select_type_creation . "', parent_id=null  WHERE id=" . $id;
		//
		}
		
		if(($select_type_creation == 'nature')||($select_type_creation == 'value type')){
			 $query  = "UPDATE dictionary_table SET value='" . $vn_create . "', label='" . $lb_create . "', type='" . $select_type_creation . "', parent_id=null  WHERE id=" . $id;
		}
		//
		///
		 $result = mysqli_query($link, $query) or die(mysqli_error($link));
		///
		//
		if($select_type_creation == 'value unit'){
			/*ELIMINA TUTTI I LINK CHE CI SONO?*/
			//
			$select_vt_e = filter_var_array($_REQUEST['select_vt_e'], FILTER_SANITIZE_STRING);
			$c_rel = count($select_vt_e);
			print_r($select_vt_e);
			echo($c_rel);
			//
			$query_rel = "DELETE FROM dictionary_relations WHERE child=".$id;
			echo($query_rel);
			$result_rel = mysqli_query($link, $query_rel) or die(mysqli_error($link));
			/*CREARLI EX_NOVO*/
			for ($r = 0; $r < $c_rel; $r++){
				$vt = $select_vt_e[$r];
				$query_relations='INSERT INTO dictionary_relations (child, parent) VALUE ("'.$id.'","'.$vt.'")';
				$result_relations = mysqli_query($link, $query_relations) or die(mysqli_error($link));
				}
		}
		
		//
       
    header("location:dictionary_editor.php".$showFrame);
        //
    } else if (($action == 'put_voice')) {
        
    } else {
        echo ('ERROR');
    }
} else {
    header("location:dictionary_editor.php".$showFrame);
}
?>