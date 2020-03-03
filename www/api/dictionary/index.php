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

include('../../config.php'); // Includes Login Script
include('../../external_service.php');


$link = mysqli_connect($host_valueunit, $username_valueunit, $password_valueunit) or die("failed to connect to server !!");
		mysqli_set_charset($link, 'utf8');
		mysqli_select_db($link, $dbname_valueunit);
		
if($link == false){
	 //try to reconnect
	$response['result'] = 'KO';
	$response['code'] = 500;
	$response['message'] = 'DB Connection error';
	mysqli_close($link);
    echo json_encode($response);
}else{
	if (isset($_GET['get_all'])){	
							
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
						WHERE tab1.type='value unit' AND status=1 AND (tab1.parent_id = '' OR tab1.parent_id = 0 OR tab1.parent_id is null) order by Id;";
				
				$process_list = array();
				$result = mysqli_query($link, $query) or die(mysqli_error($link));
				
					while ($row = mysqli_fetch_assoc($result)) {
													
													$parent_id = "";
													$parent_value = "";
													$children_id = "";
													$children_value = "";
													//
													$id_row = $row['id'];
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
													//
													if(($row['type'] == 'value type')){
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

														$result_value = mysqli_query($link, $query_value) or die(mysqli_error($link));
																while ($row1 = mysqli_fetch_assoc($result_value)) {
																	$parent_id1  = $row1['child'];
																	$parent_vl1 =  $row1['value'];
																	//
																	array_push($process_parent_id, $parent_id1);
																	array_push($process_parent_vl, $parent_vl1);
																	//
																}
														//
														$children_id = $process_parent_id;
														$children_value = $process_parent_vl;
														//
													}
													//
													if(($row['type'] == 'nature')){
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

														$result_value = mysqli_query($link, $query_value) or die(mysqli_error($link));
																while ($row1 = mysqli_fetch_assoc($result_value)) {
																	$parent_id1  = $row1['child'];
																	$parent_vl1 =  $row1['value'];
																	//
																	array_push($process_parent_id, $parent_id1);
																	array_push($process_parent_vl, $parent_vl1);
																	//
																}

														$children_id = $process_parent_id;
														$children_value = $process_parent_vl;
														//
													}
													//
													if(($row['type'] == 'subnature')){
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
													//
													$process = array(
														"id" => $row['id'],
														"value" => $row['value'],
														"label" => $row['label'],
														"type" => $row['type'],
														"parent_id"=>$parent_id,
														"parent_value"=>$parent_value,
														"children_id"=>$children_id,
														"children_value"=>$children_value
													);
													array_push($process_list, $process);
												}
					
					//
					$content = $process_list;
					mysqli_close($link);
					//
					$response['result'] = 'OK';
					$response['code'] = 200;
					$response['content'] = $content;
					echo json_encode($response);
					//mysqli_close($link);
	}elseif (isset($_GET['type'])){
		/////
		$value_type = mysqli_real_escape_string($connessione_al_server,$_REQUEST['type']);
		$value_type = filter_var($value_type , FILTER_SANITIZE_STRING);
		$parent = "";
		///
		if (($value_type=="valuetype")||($value_type=="value_type")){
			$value_type="value type";
		}
		if (($value_type=="valueunit")||($value_type=="value_unit")){
			$value_type="value unit";
		}
		if (($value_type=="sub nature")||($value_type=="sub_nature")){
			$value_type="subnature";
		}
		//
		$parent0 = "";
		$child0 = "";
		if(isset($_REQUEST['child'])){
					$child_value0 = mysqli_real_escape_string($connessione_al_server,$_REQUEST['child']);
					$child_value0 = filter_var($child_value0 , FILTER_SANITIZE_STRING);
					$child0 = '	AND child='.$child_value0;
				}
		if(isset($_REQUEST['parent'])){
					$parent_value0 = mysqli_real_escape_string($connessione_al_server,$_REQUEST['parent']);
					$parent_value0 = filter_var($parent_value0 , FILTER_SANITIZE_STRING);
					$parent0 = '	AND tab1.parent='.$parent_value0;
				}
		
		$query = "SELECT * FROM dictionary_table AS tab1 WHERE status = 1 AND type='".$value_type."'";
		$process_list = array();
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
					
			/***/		
		while ($row = mysqli_fetch_assoc($result)) {
			//
			//
			$parent_id = "";
			$parent_value = "";
			$children_id = "";
			$children_value = "";
			$id_row = $row['id'];
			//
							if(($row['type'] == 'value unit')||($row['type'] == 'subnature')){
									//
									$process_parent_id = array();
									$process_parent_vl = array();
									//
									if(isset($_REQUEST['parent'])){
										$parent_value0 = mysqli_real_escape_string($connessione_al_server,$_REQUEST['parent']);
										$parent_value0 = filter_var($parent_value0 , FILTER_SANITIZE_STRING);
										$parent0 = '	AND tab1.parent='.$parent_value0;
									}
									
												$query_value =	"SELECT tab1.*,
																			dictionary_table.value
																	 FROM   dictionary_relations AS tab1,
																			dictionary_table
																	 WHERE  tab1.child='".$id_row."'
																	 AND    dictionary_table.id = tab1.parent".$parent0;
																	 
									$result_value = mysqli_query($link, $query_value) or die(mysqli_error($link));
											while ($row1 = mysqli_fetch_assoc($result_value)) {
												$parent_id1  = $row1['parent'];
												$parent_vl1 =  $row1['value'];
												array_push($process_parent_id, $parent_id1);
												array_push($process_parent_vl, $parent_vl1);
											}
									$parent_id = $process_parent_id;
									$parent_value = $process_parent_vl;
									//
									if($parent0 ==""){
									$process = array(
												"id" => $row['id'],
												"value" => $row['value'],
												"label" => $row['label'],
												"type" => $row['type'],
												"parent_id"=>$parent_id,
												"parent_value"=>$parent_value,
												"children_id"=>$children_id,
												"children_value"=>$children_value
											);
										array_push($process_list, $process);
									}else{
										if(sizeof($parent_id)>0){
												$process = array(
												"id" => $row['id'],
												"value" => $row['value'],
												"label" => $row['label'],
												"type" => $row['type'],
												"parent_id"=>$parent_id,
												"parent_value"=>$parent_value,
												"children_id"=>$children_id,
												"children_value"=>$children_value
											);
										array_push($process_list, $process);
										}
									}

								}elseif(($row['type'] == 'nature')||($row['type'] == 'value type')){
									/*****/
										$process_parent_id = array();
										$process_parent_vl = array();
										//
										if(isset($_REQUEST['child'])){
											$child_value0 = mysqli_real_escape_string($connessione_al_server,$_REQUEST['child']);
											$child_value0 = filter_var($child_value0 , FILTER_SANITIZE_STRING);
											$child0 = '	AND child='.$child_value0;
										}
										
												$query_value =	"SELECT tab1.*,
																		dictionary_table.value
																 FROM   dictionary_relations AS tab1,
																		dictionary_table
																 WHERE  tab1.parent='".$id_row."'
																 AND    dictionary_table.id = tab1.child".$child0;
										//
										$result_value = mysqli_query($link, $query_value) or die(mysqli_error($link));
												while ($row1 = mysqli_fetch_assoc($result_value)) {
													$parent_id1  = $row1['child'];
													$parent_vl1 =  $row1['value'];
													//
													array_push($process_parent_id, $parent_id1);
													array_push($process_parent_vl, $parent_vl1);
													//
												}
										$children_id = $process_parent_id;
										$children_value = $process_parent_vl;
										//
										if($child0 ==""){
														$process = array(
																	"id" => $row['id'],
																	"value" => $row['value'],
																	"label" => $row['label'],
																	"type" => $row['type'],
																	"parent_id"=>$parent_id,
																	"parent_value"=>$parent_value,
																	"children_id"=>$children_id,
																	"children_value"=>$children_value
																);
															array_push($process_list, $process);
														}else{
															if(sizeof($children_id)>0){
																	$process = array(
																	"id" => $row['id'],
																	"value" => $row['value'],
																	"label" => $row['label'],
																	"type" => $row['type'],
																	"parent_id"=>$parent_id,
																	"parent_value"=>$parent_value,
																	"children_id"=>$children_id,
																	"children_value"=>$children_value
																);
															array_push($process_list, $process);
															}
														}
										//
									}

        }		
					
					$content = $process_list;
					mysqli_close($link);
					//
					$response['result'] = 'OK';
					$response['code'] = 200;
					$response['content'] = $content;
					echo json_encode($response);
					
					
	}elseif (isset($_GET['id'])){
		//
		$parent_id = "";
		$parent_value = "";
		$children_id = "";
		$children_value = "";
		//
			$id_value = mysqli_real_escape_string($connessione_al_server,$_GET['id']);
			$id_value = filter_var($id_value , FILTER_SANITIZE_STRING);
			$query = "SELECT * FROM dictionary_table WHERE status = 1 AND id='".$id_value."'";
			//echo($query);
			$process_list = array();
			$result = mysqli_query($link, $query) or die(mysqli_error($link));
			/********/
								while ($row = mysqli_fetch_assoc($result)) {
								//
								$parent_id = $row['parent_id'];
								$parent_value = $row['parent_value'];
								$id_row = $row['id'];
									if(($row['type'] == 'value unit')||($row['type'] == 'subnature')){
									//
									$process_parent_id = array();
									$process_parent_vl = array();
									//
									
								$query_value =	"SELECT tab1.*,
														dictionary_table.value
												 FROM   dictionary_relations AS tab1,
														dictionary_table
												 WHERE  tab1.child='".$id_row."'
												 AND    dictionary_table.id = tab1.parent";
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
									$children_id = "";
									$children_value = "";
									//
								}else{
										//
									$process_parent_id = array();
									$process_parent_vl = array();
									//
									
								$query_value =	"SELECT tab1.*,
														dictionary_table.value
												 FROM   dictionary_relations AS tab1,
														dictionary_table
												 WHERE  tab1.parent='".$id_row."'
												 AND    dictionary_table.id = tab1.child";
									//
									$result_value = mysqli_query($link, $query_value) or die(mysqli_error($link));
											while ($row1 = mysqli_fetch_assoc($result_value)) {
												$parent_id1  = $row1['child'];
												$parent_vl1 =  $row1['value'];
												//
												array_push($process_parent_id, $parent_id1);
												array_push($process_parent_vl, $parent_vl1);
												//
											}
									$parent_id = "";
									$parent_value = "";
									$children_id = $process_parent_id;
									$children_value = $process_parent_vl;
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
									"children_id"=>$children_id,
									"children_value"=>$children_value
								);

								array_push($process_list, $process);
							}
			/*******/
					$content = $process_list;
					mysqli_close($link);
			//
			$response['result'] = 'OK';
			$response['code'] = 200;
			$response['content'] = $content;
		echo json_encode($response);
		//
	}else{
					$response['result'] = 'ERROR';
					$response['code'] = 404;
					$response['content'] = 'Not found data';
					echo json_encode($response);
	}
}
	
?>