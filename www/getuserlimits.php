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
include('external_service.php');
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'RootAdmin') {
        $link = mysqli_connect($host_limits, $username_limits, $password_limits) or die("failed to connect to server !!");
        mysqli_set_charset($link, 'utf8');
        mysqli_select_db($link, $dbname_limits);
        $action = $_REQUEST['action'];
        if ($action == 'get_values') {

            $content1 = $types_limits;
            echo json_encode($content1);
        } elseif ($action == 'get_users') {
            $query = "SELECT username FROM limits";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));

            $list = array();
            if ($result->num_rows > 0) {
                while ($row0 = mysqli_fetch_assoc($result)) {

                    array_push($list, $row0['username']);
                }
            }
            $list1 = array_unique($list);
            $content1 = array_values($list1);
            echo json_encode($content1);
        } elseif ($action == 'get_orgs') {

            $response01 = file_get_contents($org_limits_api);
            $response = json_decode($response01);
            $list = array();
            foreach ($response as $value) {
                //$value = $value * 2;
                $value1 = $value->organizationName;
                array_push($list, $value1);
                //print_r($v1);
            }
            //echo json_encode($response[0]);


            echo json_encode($list);
        } elseif ($action == 'details_orgs') {
            //$type=$_REQUEST['type'];
            //$sel_org=$_REQUEST['org_sel'];
            //
	    $type = mysqli_real_escape_string($link, $_REQUEST['type']);
            $type = filter_var($type, FILTER_SANITIZE_STRING);
            //
            $sel_org = mysqli_real_escape_string($link, $_REQUEST['org_sel']);
            $sel_org = filter_var($sel_org, FILTER_SANITIZE_STRING);
            //

            if (($sel_org !== null) && ($sel_org !== '')) {
                $query = "SELECT * FROM limits WHERE (organization=? OR organization='any') AND elementType=?";
                $stmt = mysqli_prepare($link, $query);
                if (!$stmt) {
                    die(mysqli_error($link));
                }
                mysqli_stmt_bind_param($stmt, "ss", $type, $sel_org);
            } else {
                $query = "SELECT * FROM limits WHERE (organization=? OR organization='any')";
                $stmt = mysqli_prepare($link, $query);
                if (!$stmt) {
                    die(mysqli_error($link));
                }
                mysqli_stmt_bind_param($stmt, "s", $type);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $list = array();
            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    array_push($list, $row);
                }
            }
            mysqli_stmt_close($stmt);
            //$list1 = array_unique($list);
            //$content1 = array_values($list1);
            echo json_encode($list);
        } elseif ($action == 'order') {

            $type_sel = mysqli_real_escape_string($link, $_REQUEST['type_sel']);
            $type_sel = filter_var($type_sel, FILTER_SANITIZE_STRING);
            //
            $sel_org = mysqli_real_escape_string($link, $_REQUEST['org_sel']);
            $sel_org = filter_var($sel_org, FILTER_SANITIZE_STRING);
            //
            $order = mysqli_real_escape_string($link, $_REQUEST['order']);
            $order = filter_var($order, FILTER_SANITIZE_STRING);
            //
            $column = mysqli_real_escape_string($link, $_REQUEST['column']);
            $column = filter_var($column, FILTER_SANITIZE_STRING);
            //
            $list = array();
            $allowed_columns = array('username', 'organization', 'role', 'elementType', 'maxCount');
            $column_safe = in_array($column, $allowed_columns, true) ? $column : 'username';
            $order_safe = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
            $query = "SELECT * FROM limits WHERE ";
            $params = array();
            $types = "";
            if (($type_sel !== null) && ($type_sel !== '')) {
                $query .= "elementType=? ";
                $params[] = $type_sel;
                $types .= "s";
            } else {
                $query .= "elementType !='' ";
            }
            if (($sel_org !== null) && ($sel_org !== '')) {
                $query .= "AND (organization=? OR organization='any') ";
                $params[] = $sel_org;
                $types .= "s";
            }
            $query .= "ORDER BY " . $column_safe . " " . $order_safe;
            $stmt = mysqli_prepare($link, $query);
            if (!$stmt) {
                die(mysqli_error($link));
            }
            if ($types !== "") {
                $bind_params = array_merge(array($types), $params);
                $refs = array();
                foreach ($bind_params as $k => &$v) {
                    $refs[$k] = &$v;
                }
                call_user_func_array('mysqli_stmt_bind_param', $refs);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $list = array();
            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    array_push($list, $row);
                }
            }
            mysqli_stmt_close($stmt);
            echo json_encode($list);
        } elseif ($action == 'details') {
            //$type=$_REQUEST['type'];
            $type = mysqli_real_escape_string($link, $_REQUEST['type']);
            $type = filter_var($type, FILTER_SANITIZE_STRING);
            //
            $sel_org = mysqli_real_escape_string($link, $_REQUEST['org_sel']);
            $sel_org = filter_var($sel_org, FILTER_SANITIZE_STRING);

            if (($sel_org !== null) && ($sel_org !== '')) {
                $query = "SELECT * FROM limits WHERE elementType=? AND (organization=? OR organization='any')";
                $stmt = mysqli_prepare($link, $query);
                if (!$stmt) {
                    die(mysqli_error($link));
                }
                mysqli_stmt_bind_param($stmt, "ss", $type, $sel_org);
            } else {
                $query = "SELECT * FROM limits WHERE elementType=?";
                $stmt = mysqli_prepare($link, $query);
                if (!$stmt) {
                    die(mysqli_error($link));
                }
                mysqli_stmt_bind_param($stmt, "s", $type);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $list = array();
            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    array_push($list, $row);
                }
            }
            mysqli_stmt_close($stmt);
            //$list1 = array_unique($list);
            //$content1 = array_values($list1);
            echo json_encode($list);
        } elseif ($action == 'details_username') {
            //$type=$_REQUEST['type'];
            $type = mysqli_real_escape_string($link, $_REQUEST['type']);
            $type = filter_var($type, FILTER_SANITIZE_STRING);
            //
            $query = "SELECT * FROM limits WHERE username=?";
            $stmt = mysqli_prepare($link, $query);
            if (!$stmt) {
                die(mysqli_error($link));
            }
            mysqli_stmt_bind_param($stmt, "s", $type);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $list = array();
            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    array_push($list, $row);
                }
                echo json_encode($list);
            }
            mysqli_stmt_close($stmt);
        } elseif ($action == 'mod_type') {
            //
            $user = mysqli_real_escape_string($link, $_POST['user']);
            $user = filter_var($user, FILTER_SANITIZE_STRING);

            $organization = mysqli_real_escape_string($link, $_POST['organization']);
            $organization = filter_var($organization, FILTER_SANITIZE_STRING);

            $role = mysqli_real_escape_string($link, $_POST['role']);
            $role = filter_var($role, FILTER_SANITIZE_STRING);

            $elementtype = mysqli_real_escape_string($link, $_POST['elementtype']);
            $elementtype = filter_var($elementtype, FILTER_SANITIZE_STRING);

            $limits = mysqli_real_escape_string($link, $_POST['limits']);
            $limits = filter_var($limits, FILTER_SANITIZE_STRING);

			$limits_n = mysqli_real_escape_string($link, $_POST['limits_n']);
			$limits_n = filter_var($limits_n, FILTER_SANITIZE_STRING);
            //$limits_n = filter_var($limits_n, FILTER_SANITIZE_NUMBER_INT);
            //
            $response01 = file_get_contents($org_limits_api);
            $response = json_decode($response01);
            $check_org = array();
            foreach ($response as $value) {
                //
                $value1 = $value->organizationName;
                array_push($check_org, $value1);
            }
            array_push($check_org, 'any');
            //

			//echo('limits_n'.gettype($limits_n));

            if (($role == 'any') || ($role == 'Manager') || ($role == 'AreaManager') || ($role == 'ToolAdmin') || ($role == 'RootAdmin') || ($role == 'Observer')) {

                if (in_array($elementtype, $types_limits)) {
                    if (in_array($organization, $check_org)) {
                        if (is_numeric($limits_n)) {
                            $limits_n_int = (int)$limits_n;
                            $limits_int = (int)$limits;
                            $query = "UPDATE limits SET maxCount=? WHERE username=? AND organization=? AND role=? AND elementType=? AND maxCount=?";
                            $stmt = mysqli_prepare($link, $query);
                            if (!$stmt) {
                                die(mysqli_error($link));
                            }
                            mysqli_stmt_bind_param($stmt, "issssi", $limits_n_int, $user, $organization, $role, $elementtype, $limits_int);
                            $result = mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);

                            if ($result) {
                                $message['result'] = 'ok';
                                $message['message'] = 'modified';
                                echo json_encode($message);
                            }
                        } else {
                            $message['result'] = 'error';
                            $message['message'] = 'not valid Limits parameter';
                            echo json_encode($message);
                        }
                    } else {
                        $message['result'] = 'error';
                        $message['message'] = 'not valid Organization parameter';
                        echo json_encode($message);
                    }
                } else {
                    $message['result'] = 'error';
                    $message['message'] = 'not valid Elementtype parameter';
                    echo json_encode($message);
                }
            } else {
                $message['result'] = 'error';
                $message['message'] = 'not valid Role parameter';
                echo json_encode($message);
            }
            //echo($query);
        //
} elseif ($action == 'new_type') {
            //

            $user = mysqli_real_escape_string($link, $_POST['user_c']);
            $user = filter_var($user, FILTER_SANITIZE_STRING);

            $organization = mysqli_real_escape_string($link, $_POST['organization_c']);
            $organization = filter_var($organization, FILTER_SANITIZE_STRING);

            $role = mysqli_real_escape_string($link, $_POST['role_c']);
            $role = filter_var($role, FILTER_SANITIZE_STRING);

            $elementtype = mysqli_real_escape_string($link, $_POST['elementtype_c']);
            $elementtype = filter_var($elementtype, FILTER_SANITIZE_STRING);

            $limits = mysqli_real_escape_string($link, $_POST['limits_c']);
            $limits = filter_var($limits, FILTER_SANITIZE_STRING);

			///
            $query_select = "SELECT * FROM limits WHERE username=? AND organization=? AND role=? AND elementType=?";
            $stmt_select = mysqli_prepare($link, $query_select);
            if (!$stmt_select) {
                die(mysqli_error($link));
            }
            mysqli_stmt_bind_param($stmt_select, "ssss", $user, $organization, $role, $elementtype);
            mysqli_stmt_execute($stmt_select);
            $result_select = mysqli_stmt_get_result($stmt_select);
            $num = $result_select->num_rows;
            mysqli_stmt_close($stmt_select);
            //
            //echo($num);
            //
            //$num_rows = mysql_num_rows($result_select);
			if (($user=="")||($user== null)){
				 $message['result'] = 'error';
                $message['message'] = 'username not valid';
                echo json_encode($message);
			}else{
            if ($num > 0) {
                $message['result'] = 'error';
                $message['message'] = 'duplicated';
                echo json_encode($message);
            } else {
                //-------//
                $response01 = file_get_contents($org_limits_api);
                $response = json_decode($response01);
                $check_org = array();
                foreach ($response as $value) {
                    //
                    $value1 = $value->organizationName;
                    array_push($check_org, $value1);
                    //print_r($v1);
                }
                 array_push($check_org, 'any');
                //-------//

                //
				if (($role == 'any') || ($role == 'Manager') || ($role == 'AreaManager') || ($role == 'ToolAdmin') || ($role == 'RootAdmin') || ($role == 'Observer')) {
                    //
                    if (in_array($elementtype, $types_limits)) {
                        if (in_array($organization, $check_org)) {
                            if (is_numeric($limits)) {
                                $limits_int = (int)$limits;
                                $query = "INSERT INTO limits(username,organization, role, elementType, maxCount) VALUES (?,?,?,?,?)";
                                $stmt = mysqli_prepare($link, $query);
                                if (!$stmt) {
                                    die(mysqli_error($link));
                                }
                                mysqli_stmt_bind_param($stmt, "ssssi", $user, $organization, $role, $elementtype, $limits_int);
                                $result = mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);
                                //
                                $message['result'] = 'ok';
                                $message['message'] = 'created';
                                echo json_encode($message);
                            } else {
                                $message['result'] = 'error';
                                $message['message'] = 'not valid Limit parameter';
                                echo json_encode($message);
                            }
                        } else {
                            $message['result'] = 'error';
                            $message['message'] = 'not valid Organization parameter';
                            echo json_encode($message);
                        }
                    } else {
                        $message['result'] = 'error';
                        $message['message'] = 'not valid Elementtype parameter';
                        echo json_encode($message);
                    }
                } else {

                    //
                    $message['result'] = 'error';
                    $message['message'] = 'not valid Role parameter';
                    echo json_encode($message);
                    //
                }
            }
            //
			}
        } elseif ($action == 'del_type') {
            //
            //
	$user = mysqli_real_escape_string($link, $_POST['user_d']);
            $user = filter_var($user, FILTER_SANITIZE_STRING);

            $organization = mysqli_real_escape_string($link, $_POST['organization_d']);
            $organization = filter_var($organization, FILTER_SANITIZE_STRING);

            $role = mysqli_real_escape_string($link, $_POST['role_d']);
            $role = filter_var($role, FILTER_SANITIZE_STRING);

            $elementtype = mysqli_real_escape_string($link, $_POST['elementtype_d']);
            $elementtype = filter_var($elementtype, FILTER_SANITIZE_STRING);

            $limits = mysqli_real_escape_string($link, $_POST['limits_d']);
            $limits = filter_var($limits, FILTER_SANITIZE_NUMBER_INT);
            //
            $response01 = file_get_contents($org_limits_api);
            $response = json_decode($response01);
            $check_org = array();
            foreach ($response as $value) {
                //
                $value1 = $value->organizationName;
                array_push($check_org, $value1);
                //print_r($v1);
            }
             array_push($check_org, 'any');
            //

            if (($role == 'any') || ($role == 'Manager') || ($role == 'AreaManager') || ($role == 'ToolAdmin') || ($role == 'RootAdmin') || ($role == 'Observer')) {
                //
                if (in_array($elementtype, $types_limits)) {
                    if (in_array($organization, $check_org)) {
                        $limits_int = (int)$limits;
                        $query_del = "DELETE FROM limits WHERE username=? AND organization=? AND role=? AND elementType=? AND maxCount=?";
                        $stmt = mysqli_prepare($link, $query_del);
                        if (!$stmt) {
                            die(mysqli_error($link));
                        }
                        mysqli_stmt_bind_param($stmt, "ssssi", $user, $organization, $role, $elementtype, $limits_int);
                        $result = mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        //
                        if ($result) {
                            $message['result'] = 'ok';
                            $message['message'] = 'deleted';
                            echo json_encode($message);
                        } else {
                            $message['result'] = 'error';
                            $message['message'] = 'not deleted';
                            echo json_encode($message);
                        }
                    } else {
                        $message['result'] = 'error';
                        $message['message'] = 'not valid Organization parameter';
                        echo json_encode($message);
                    }
                } else {
                    $message['result'] = 'error';
                    $message['message'] = 'not valid Elementtype parameter';
                    echo json_encode($message);
                }
            } else {
                $message['result'] = 'error';
                $message['message'] = 'not valid Role parameter';
                echo json_encode($message);
            }
            //
        //
	//
} else {
            //nothing
            //
	if (isset($_REQUEST['showFrame'])) {
                if ($_REQUEST['showFrame'] == 'false') {
                    header("location:userlimits.php?showFrame=false");
                } else {
                    header("location:userlimits.php");
                }
            } else {
                header("location:userlimits.php");
            }
            //
        //
}
    } else {
        exit();
    }
} else {
    exit();
}
?>
