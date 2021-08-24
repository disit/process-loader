<?php
// include 'datatablemanager_myUtil.php';
$configs = parse_ini_file('datatablemanager_config.ini.php');
$conn = getConnection($configs);

function executeCheckIfValueNameExistQuery($query){
    $value_name_id=0;
    $check_value_name_exist_result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $check_value_name_exist_result->fetch_assoc()) {
            // Value name exists
            $value_name_id = $row["id"];
        }
    }
    return $value_name_id;
}

function executeGetElementsToProcessQuery($query){
    $element_ids=array();
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            array_push($element_ids, $row['element_id']);
        }
    }
    return $element_ids;
}

function executeElementIdsToProcessQuery($query,$element_ids_of_delegated_user){
    
    $all_element_Ids_To_process=array();
    $element_of_user_to_process_ids=array();
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            array_push($all_element_Ids_To_process, $row['element_id']);
        }
    }
    
    $index=0;
    foreach ($all_element_Ids_To_process as $value) {
        if(in_array($value, $element_ids_of_delegated_user)){
            $element_of_user_to_process_ids[$index]=$value;
            ++$index;
        }
    }
    
    $newar = array(
        'element_id'=>$element_of_user_to_process_ids
    );
    return json_encode($newar);
}

function executeGetDateObservedType($query){
    $dateObserved_type="";
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            $dateObserved_type = $row["dateObserved_type"];
        }
    }
    return $dateObserved_type;
}

function executeGetDeviceListQuery($query){

    $get_data_query = mysqli_query($GLOBALS['conn'], $query) or die(mysqli_error($query));
    $device_list=array();
    if(mysqli_num_rows($get_data_query)!=0){
        while($r = mysqli_fetch_array($get_data_query)){
        extract($r);
        array_push($device_list,$r[0]);
    }
}
return $device_list;
}

function executeGetDeateObsrevedforRowsQuery($query){
    $get_data_query = mysqli_query($GLOBALS['conn'], $query) or die(mysqli_error($query));
    $dateObsrevedforRows_list=array();
    if(mysqli_num_rows($get_data_query)!=0){
        while($r = mysqli_fetch_array($get_data_query)){
            extract($r);
            array_push($dateObsrevedforRows_list,$r[0]);
        }
    }
    return $dateObsrevedforRows_list;
}

function executeGetDeviceSheetNameQuery($query){
    
    $device_sheet_name="";
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            $device_sheet_name = $row['sheet_name'];
        }
    }
    return $device_sheet_name;
}

function executeGetDeviceLatQuery($query, $coord_type)
{
    $lat = "";
    $result = $GLOBALS['conn']->query($query);

    if ($coord_type != "address") {
        if (mysqli_query($GLOBALS['conn'], $query)) {
            while ($row = $result->fetch_assoc()) {
                $lat = $row['lat'];
            }
        }
    } else {
        if (mysqli_query($GLOBALS['conn'], $query)) {
            while ($row = $result->fetch_assoc()) {
                if ($row['lat'] != "") {
                    $lat = $row['lat'];
                    break;
                }
            }
        }
    }
    return $lat;
}


function executeGetDeviceLonQuery($query, $coord_type)
{
    $lon = "";
    $result = $GLOBALS['conn']->query($query);

    if ($coord_type != "address") {
        if (mysqli_query($GLOBALS['conn'], $query)) {
            while ($row = $result->fetch_assoc()) {
                $lon = $row['lon'];
            }
        }
    } else {
        if (mysqli_query($GLOBALS['conn'], $query)) {
            while ($row = $result->fetch_assoc()) {
                if ($row['lon'] != "") {
                    $lon = $row['lon'];
                    break;
                }
            }
        }
    }
    return $lon;
}

function executeGetRowInstanceQuery($query){
    $row_instance=array();
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            array_push($row_instance, $row['value']);
        }
    }
    return $row_instance;
}

function executeUpdateAllProcessStatusQuery($query){
    if ($GLOBALS['conn']->query($query) === TRUE) {
        echo "All Records updated successfully!";
    } else {
        echo "Error updating record: " . $GLOBALS['conn']->error;
    }
}

function executeUpdateProcessStatusQuery($query){
    if ($GLOBALS['conn']->query($query) === TRUE) {
        echo "Status updated successfully!";
    } else {
        echo "Error updating record: " . $GLOBALS['conn']->error;
    }
}

function executeDeleteElementIdQuery($query){
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

function executeStatusElementQuery($query){
    
    $status="";
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            $status = $row['processed'];
        }
    }
    return $status;
}

function executeGetOrgQuery($query){
    
    $org="";
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            $org = $row['organization'];
        }
    }
    return $org;
}

function executeSelectDataTableByElementIdQuery($query,$device_list,$dateObserved_type){

    $get_data_query = mysqli_query($GLOBALS['conn'], $query) or die(mysqli_error($query));

    if(mysqli_num_rows($get_data_query)!=0){
        
        $headers=getHeaders($get_data_query);

        $header_count=count($headers);
        $headerValueTypes=getHeaderValueTypes($GLOBALS['conn'],$query,$header_count);
        $headerValueUnits=getHeaderValueUnits($GLOBALS['conn'],$query,$header_count);
        $headerDataTypes=getHeaderDataTypes($GLOBALS['conn'],$query,$header_count);

        $model_features=getModelFeatures($GLOBALS['conn'],$query,$headers);
        
        $model_features_value_types=getModelFeatureValueTypes($model_features,$headers,$headerValueTypes);
        $model_features_value_units=getModelFeatureValueUnits($model_features,$headers,$headerValueUnits);
        $model_features_data_types=getModelFeatureDataTypes($model_features,$headers,$headerDataTypes);
        
        $json=getDataTableJson($GLOBALS['conn'],$query,$dateObserved_type,$model_features,$model_features_value_types,$model_features_value_units,$device_list,$headerValueTypes,$headerValueUnits,$headers,$headerDataTypes,$model_features_data_types);
//         echo json_encode($json);
//         die();
    }
    else{
        $json = array("status" => 0, "error" => "not found!");
    }
//     header('Content-Type: text/html; charset=utf-8');
// header('Content-type: application/json');
    return json_encode($json);
}

function executeInsertCellQuery($row_number, $cell_number,$cell_count,$query)
{
    set_time_limit( 0 );
    if (mysqli_query($GLOBALS['conn'], $query)) {
        $cell_value_id = mysqli_insert_id($GLOBALS['conn']);
//         echo $msg = "New record created successfully. cell_value_id is: " . $cell_value_id;
    } else {
        echo $msg_error = "Error for inserting value_name: " . $query . "<br>" . mysqli_error($GLOBALS['conn']);
        return $msg_error;
    }
//     return "Row #" . $row_number . " Cell ".$cell_number."/".$cell_count." inserted sucessfully!";
}

function executeInsertValueNameQuery($query){
    $value_name_id = 0;
    
    if (mysqli_query($GLOBALS['conn'], $query)) {
        $value_name_id = mysqli_insert_id($GLOBALS['conn']);
//         echo $msg1 = "New record created successfully. value_name ID is: " . $value_name_id;
        } else {
            echo $msg_error = "Error for inserting value_name: " . $query . "<br>" . mysqli_error($GLOBALS['conn']);
        }
    return $value_name_id;
}

function getConnection($configs)
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

function executeGetUsernameToDelegate($query){
    
    $user_names=array();
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            array_push($user_names, $row['username']);
        }
    }
    return $user_names;
}

function executeGetUploadedFiles($query){
    
    $user_names=array();
    $result = $GLOBALS['conn']->query($query);
    if (mysqli_query($GLOBALS['conn'], $query)) {
        while ($row = $result->fetch_assoc()) {
            array_push($user_names, $row['file_name']);
        }
    }
    return $user_names;
}



?>