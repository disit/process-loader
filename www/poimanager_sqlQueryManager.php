<?php
// include 'datatablemanager_myUtil.php';
$configs = parse_ini_file('datatablemanager_config.ini.php');
$conn = getPoiConnection($configs);

// function executeCheckIfValueNameExistQuery($query){
//     $value_name_id=0;
//     $check_value_name_exist_result = $GLOBALS['conn']->query($query);
//     if (mysqli_query($GLOBALS['conn'], $query)) {
//         while ($row = $check_value_name_exist_result->fetch_assoc()) {
//             // Value name exists
//             $value_name_id = $row["id"];
//         }
//     }
//     return $value_name_id;
// }

// function executeGetElementsToProcessQuery($query){
//     $element_ids=array();
//     $result = $GLOBALS['conn']->query($query);
//     if (mysqli_query($GLOBALS['conn'], $query)) {
//         while ($row = $result->fetch_assoc()) {
//             array_push($element_ids, $row['element_id']);
//         }
//     }
//     return $element_ids;
// }

// function executeElementIdsToProcessQuery($query,$all_element_Ids_of_user){
    
    
//     $all_element_Ids_To_process=array();
//     $element_of_user_to_process_ids=array();
//     $result = $GLOBALS['conn']->query($query);
//     if (mysqli_query($GLOBALS['conn'], $query)) {
//         while ($row = $result->fetch_assoc()) {
//             array_push($all_element_Ids_To_process, $row['element_id']);
//         }
//     }
    
//     $index=0;
//     foreach ($all_element_Ids_To_process as $value) {
//         if(in_array($value, $all_element_Ids_of_user)){
//             $element_of_user_to_process_ids[$index]=$value;
//             ++$index;
//         }
//     }
    
//     $newar = array(
//         'element_id'=>$element_of_user_to_process_ids
//     );
    
//     return json_encode($newar);
// }

function executePoiUpdateProcessStatusQuery($query){
    if ($GLOBALS['conn']->query($query) === TRUE) {
        return "ok";
    } else {
        return "Error updating record: " . $GLOBALS['conn']->error;
    }
}


function executePoiDeleteElementIdQuery($query){
    if ($GLOBALS['conn']->connect_error) {
        die("Connection failed: " . $GLOBALS['conn']->connect_error);
    }
    
    $msg="";
    
    if ($GLOBALS['conn']->query($query) === TRUE) {
        $msg="Record deleted successfully";
    } else {
        $msg="Error deleting record: " . $GLOBALS['conn']->error;
    }
    return $msg;
}


function executePoiStatusElementQuery($query){
    ini_set('max_execution_time', '0'); //300 seconds = 5 minutes
    set_time_limit(0);
    $status="";
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            $status = $row['status'];
        }
    }
    return $status;
}

function executePoiGetOrgQuery($query){
    ini_set('max_execution_time', '0'); //300 seconds = 5 minutes
    set_time_limit(0);
    $organization="";
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            $organization = $row['organization'];
        }
    }
    return $organization;
}

function executePoiSelectDataTableByElementIdQuery($query){
    require_once 'poimanager_myUtil.php';
    $configs = parse_ini_file('poimanager_config.ini.php');
    $conn = getPoiConnection($configs);
    $get_data_query = mysqli_query($conn, $query) or die(mysqli_error($query));
    
    if(mysqli_num_rows($get_data_query)!=0){
        $headers=$configs['poi_template_column'];
        $json=getPoiDataTableJson($conn,$query,$headers);
    }
    else{
        $json = array("status" => 0, "error" => "not found!");
    }
    return json_encode($json);
}

// function executeInsertCellQuery($row_number, $cell_number,$cell_count,$query)
// {
//     set_time_limit( 0 );
//     if (mysqli_query($GLOBALS['conn'], $query)) {
//         $cell_value_id = mysqli_insert_id($GLOBALS['conn']);
// //         echo $msg = "New record created successfully. cell_value_id is: " . $cell_value_id;
//     } else {
//         echo $msg_error = "Error for inserting value_name: " . $query . "<br>" . mysqli_error($GLOBALS['conn']);
//         return $msg_error;
//     }
// //     return "Row #" . $row_number . " Cell ".$cell_number."/".$cell_count." inserted sucessfully!";
// }

// function executeInsertValueNameQuery($query){
//     $value_name_id = 0;
    
//     if (mysqli_query($GLOBALS['conn'], $query)) {
//         $value_name_id = mysqli_insert_id($GLOBALS['conn']);
// //         echo $msg1 = "New record created successfully. value_name ID is: " . $value_name_id;
//         } else {
//             echo $msg_error = "Error for inserting value_name: " . $query . "<br>" . mysqli_error($GLOBALS['conn']);
//         }
//     return $value_name_id;
// }

function getPoiConnection($configs)
{
    $servername = $configs['servername'];
    $username = $configs['username'];
    $password = $configs['password'];
    $dbname = $configs['dbname'];
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

?>