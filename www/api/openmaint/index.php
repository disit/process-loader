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


include('../../external_service.php');
//error_reporting(E_PARSE | E_ERROR);
$base_url = 'http://' . $cmdbuild_url . ':' . $cmdbuild_port . '/' . $cmdbuild_name . '/services/rest/v1';
//
if (isset($cmdbuild_users) && isset($cmdbuild_pass)) {

    $name_user = $cmdbuild_users;
    $psw_user = $cmdbuild_pass;

//1. Sessione
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        echo($_SESSION['id']);
    } else {
        $url = $base_url . '/sessions/?scope=s';
        $collection_name = '';
        $arr = array('username' => $name_user, 'password' => $psw_user);
        $data = json_encode($arr);
        $headers = ['Content-Type' => 'application/json'];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($curl);

//2. controllo parametro
        if (!curl_errno($curl)) {
            $info = curl_getinfo($curl);
            $results = json_decode($response);
            if ($results->{'data'}) {
                $data = $results->{'data'};
                $id = $data->{'_id'};
            } else {
                $data = '';
                $id = '';
            }
            $_SESSION['id'] = $id;
        }
    }
    //LISTA DEI PROCESSI//
    if (isset($_REQUEST['get_processes'])) {
        $new_url = $base_url . '/processes/' . $cmdbuild_processname . '/instances';
        //
        $ch1 = curl_init($new_url);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_FAILONERROR, true);
        curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, 'GET');
        $response_02 = curl_exec($ch1);
        //print_r($response_02);
        $arr_result = array();
        $middle_array = array();
        if (!curl_errno($ch1)) {
            $info01 = curl_getinfo($ch1);
            $results01 = json_decode($response_02);
            $lun = $results01->{'meta'};
            $data01 = $results01->{'data'};
            $l = $lun->{'total'};

            $creator = '';
            if (isset($_REQUEST['creator'])) {
                $creator = $_REQUEST['creator'];
            }

            for ($i = 0; $i < $l; $i++) {
                //
                $process_code = $data01[$i]->{'Code'};
                $status = $data01[$i]->{'_status'};
                $currentUser = $data01[$i]->{'user_creator'};
                if ($status == '1') {
                    //if ($process_code !== null) {
                    if ($creator == '') {
                        array_push($arr_result, $data01[$i]);
                    } else {
                        //if ($currentUser == $name_user) {
                        if ($currentUser == $creator) {
                            $middle_array['id'] = $data01[$i]->{'_id'};
                            $middle_array['creator'] = $data01[$i]->{'user_creator'};
                            //$middle_array['status'] = $data01[$i]->{'status_event'};
                            $middle_array['status'] = $data01[$i]->{'status_process'};
                            $middle_array['description'] = $data01[$i]->{'test_description'};
                            $middle_array['event_date'] = $data01[$i]->{'event_date'};
                            $middle_array['plant_code'] = $data01[$i]->{'plant_code'};
                            $middle_array['plant'] = $data01[$i]->{'plant_description'};
                            $middle_array['components'] = $data01[$i]->{'warehouse_description'};
                            $middle_array['team_code'] = $data01[$i]->{'team_code'};
                            $middle_array['team'] = $data01[$i]->{'team_description'};
                            $middle_array['team_assignment_date'] = $data01[$i]->{'team_assignment_date'};
                            $middle_array['work'] = $data01[$i]->{'work_description'};
                            $middle_array['start_working_date'] = $data01[$i]->{'start_working_date'};
                            $middle_array['test_description'] = $data01[$i]->{'test_description'};
                            $middle_array['test_date'] = $data01[$i]->{'test_date'};
                            $middle_array['final_comments'] = $data01[$i]->{'final_comments'};
                            $middle_array['finish_date'] = $data01[$i]->{'end_date'};
                            //work_description
                            array_push($arr_result, $middle_array);
                            //array_push($arr_result, $data01[$i]);
                        }
                    }
                    //}
                }
            }
            $cread_data = date("y-m-d H:i:s.Z");

            $response_message['message'] = 'OK';
            $response_message['code'] = 200;
            $response_message['result'] = $arr_result;

            $res_api = json_encode($response_message);
            echo($res_api);

            //
        } else {
            //
            $response_message['message'] = 'KO';
            $response_message['code'] = 400;
            $response_message['result'] = 'Error during APi execution';
            $res_api = json_encode($response_message);
            echo($res_api);
        }
        curl_close($ch1);
    } else if (isset($_REQUEST['new_process'])) {
        $new_url = $base_url . '/processes/' . $cmdbuild_processname . '/instances';
        $plant_code = "";
        if (isset($_REQUEST['description'])) {
            if (isset($_REQUEST['plant_code'])) {
                $plant_code = $_REQUEST['plant_code'];
            }
            $desc = $_REQUEST['description'];
            $arr = array('Code' => '01 - Segnalazione', 'Description' => $desc, '_advance' => true, 'event_date' => '', 'plant_code' => $plant_code);
            //
            $data2 = json_encode($arr);
            $ch1 = curl_init($new_url);
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch1, CURLOPT_POSTFIELDS, $data2);
            $response_02 = curl_exec($ch1);

            if (!curl_errno($ch1)) {
                $info01 = curl_getinfo($ch1);
                $results01 = json_decode($response_02);
                $data01 = $results01->{'data'};
                //$id01 = $data01->{'_id'};
                //
            $response_message['message'] = 'OK';
                $response_message['code'] = 200;
                $response_message['result'] = 'Process successfully created';
                $res_api = json_encode($response_message);
                echo($res_api);
                //
            } else {

                $response_message['message'] = 'KO';
                $response_message['code'] = 404;
                $response_message['result'] = 'Error during Process creation';
                $res_api = json_encode($response_message);
                echo($res_api);
            }
            curl_close($ch1);
            //
        } else {
            $response_message['message'] = 'KO';
            $response_message['code'] = 400;
            $response_message['result'] = 'Error required parameter';
            $res_api = json_encode($response_message);
            echo($res_api);
        }
        //curl_close($ch1);
    }
    //CREA NUOVO PROCESSO
    else if (isset($_REQUEST['get_status'])) {
        $id_process = $_REQUEST['get_status'];
        //$id_process = $_REQUEST['process_details'];
        $new_url = $base_url . '/processes/' . $cmdbuild_processname . '/instances';
        //
        $ch1 = curl_init($new_url);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_FAILONERROR, true);
        curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, 'GET');
        $response_02 = curl_exec($ch1);
        curl_close($ch1);
        $arr_result = array();
        if (!curl_errno($ch1)) {
            $info01 = curl_getinfo($ch1);

            $results01 = json_decode($response_02);
            $lun = $results01->{'meta'};
            $data01 = $results01->{'data'};
            $l = $lun->{'total'};
            //print_r($response_02);
            for ($i = 0; $i < $l; $i++) {
                $id_corr = $data01[$i]->{'_id'};
                $status = $data01[$i]->{'_status'};
                $currentUser = $data01[$i]->{'user_creator'};
                $status_process['status'] = $data01[$i]->{'status_process'};
                //SWITCH con i valori da inserire//
                //
                    if ($id_corr == $id_process) {
                    if ($status == '1') {
                        //status_process
                        if (($status_process['status'] == '') || ($status_process['status'] == null)) {
                            $arr_par = array('description', 'plant_code', 'event_date');
                            $status_process['required parameters'] = $arr_par;
                        } else if ($status_process['status'] == 'New Accident event') {
                            $arr_par = array('necessary_materials');
                            $status_process['required parameters'] = $arr_par;
                        } else if (($status_process['status'] == 'Check components in warehouse') || ($status_process['status'] == 'Check in warehouse')) {
                            $arr_par = array('team_code', 'team_assignment_date');
                            $status_process['required parameters'] = $arr_par;
                        } else if (($status_process['status'] == 'Team Executor Assignment') || ($status_process['status'] == 'This team is actually not available')) {
                            $arr_par = array('transport_code', 'start_working_date', 'work_description');
                            $status_process['required parameters'] = $arr_par;
                        } else if ($status_process['status'] == 'Work Execution') {
                            $arr_par = array('test_description', 'test_date', 'success');
                            $status_process['required parameters'] = $arr_par;
                        } else if (($status_process['status'] == 'Event solved') || ($status_process['status'] == 'Event not solved')) {
                            $arr_par = array('final_comments');
                            $status_process['required parameters'] = $arr_par;
                        } else {
                            $status_process['required parameters'] = '';
                        }
                        //$name_user
                        array_push($arr_result, $status_process);
                    }
                }
            }

            $json_results = json_encode($arr_result);

            $response_message['message'] = 'OK';
            $response_message['code'] = 200;
            $response_message['result'] = $arr_result;
            $res_api = json_encode($response_message);
            echo($res_api);

            //
        } else {

            $response_message['message'] = 'KO';
            $response_message['code'] = 404;
            $response_message['result'] = 'Error';
            $res_api = json_encode($response_message);
            echo($res_api);
            //
        }
        curl_close($ch1);
        /**/
        /*         * */
    }
    //MOSTRA DETTAGLI PROCESSO
    else if (isset($_REQUEST['process_details'])) {
        $id_process = $_REQUEST['process_details'];
        $new_url = $base_url . '/processes/' . $cmdbuild_processname . '/instances';
        //
        $ch1 = curl_init($new_url);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_FAILONERROR, true);
        curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, 'GET');
        $response_02 = curl_exec($ch1);

        $arr_result = array();
        if (!curl_errno($ch1)) {
            $info01 = curl_getinfo($ch1);

            $results01 = json_decode($response_02);
            $lun = $results01->{'meta'};
            $data01 = $results01->{'data'};
            $l = $lun->{'total'};
            $middle_array = array();
            for ($i = 0; $i < $l; $i++) {
                //$progressivo = $data01[$i]->{'Progressivo'};
                $id_corr = $data01[$i]->{'_id'};
                $status = $data01[$i]->{'_status'};
                if ($id_corr == $id_process) {
                    if ($status == '1') {

                        $middle_array['id'] = $data01[$i]->{'_id'};
                        $middle_array['creator'] = $data01[$i]->{'user_creator'};
                        //$middle_array['status'] = $data01[$i]->{'status_event'};
                        $middle_array['status'] = $data01[$i]->{'status_process'};
                        $middle_array['description'] = $data01[$i]->{'test_description'};
                        $middle_array['event_date'] = $data01[$i]->{'event_date'};
                        $middle_array['plant_code'] = $data01[$i]->{'plant_code'};
                        $middle_array['plant'] = $data01[$i]->{'plant_description'};
                        $middle_array['components'] = $data01[$i]->{'warehouse_description'};
                        $middle_array['team_code'] = $data01[$i]->{'team_code'};
                        $middle_array['team'] = $data01[$i]->{'team_description'};
                        $middle_array['team_assignment_date'] = $data01[$i]->{'team_assignment_date'};
                        $middle_array['work'] = $data01[$i]->{'work_description'};
                        $middle_array['start_working_date'] = $data01[$i]->{'start_working_date'};
                        $middle_array['test_description'] = $data01[$i]->{'test_description'};
                        $middle_array['test_date'] = $data01[$i]->{'test_date'};
                        $middle_array['final_comments'] = $data01[$i]->{'final_comments'};
                        $middle_array['finish_date'] = $data01[$i]->{'end_date'};
                        //work_description
                        array_push($arr_result, $middle_array);
                        //array_push($arr_result, $data01[$i]);
                    }
                }
            }

            $json_results = json_encode($arr_result);

            $response_message['message'] = 'OK';
            $response_message['code'] = 200;
            $response_message['result'] = $arr_result;
            $res_api = json_encode($response_message);
            echo($res_api);

            //
        } else {

            $response_message['message'] = 'KO';
            $response_message['code'] = 404;
            $response_message['result'] = 'Error';
            $res_api = json_encode($response_message);
            echo($res_api);
            //
        }
        curl_close($ch1);
        /**/
    } else if (isset($_REQUEST['edit_process'])) {
        $id_process = $_REQUEST['edit_process'];

        $new_url = $base_url . '/processes/' . $cmdbuild_processname . '/instances/' . $id_process;
        $ch1 = curl_init($new_url);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_FAILONERROR, true);
        curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, 'GET');
        $response_02 = curl_exec($ch1);
        //$curl_errch1 = curl_error($ch1);

        if (!curl_errno($ch1)) {
            $new_array = array();
            //print_r($response_02);
            //
             $results01 = json_decode($response_02);
            $data = $results01->{'data'};
            $code = $data->{'Code'};
            $desc_code = $data->{'Description'};
            $activity = $data->{'ActivityInstanceId'};
            $http_code = "400";
            $http_res = "Bad Request";
            //
            //CONTROLLO
            switch ($code) {
                case '01 - Event to be adressed':
                    $res_cod = "Need Description";
                    //
                    if (isset($_REQUEST['description'])) {
                        $desc_event = $_REQUEST['description'];
                        $new_array['Description'] = $desc_event;
                        $res_cod = $new_array['Description'];
                        $plant_code = "";
                        if (isset($_REQUEST['plant_code'])) {
                            $plant_code = $_REQUEST['plant_code'];
                        }
                        //$new_array = array('Code' => '01 - Event to be adressed', '_activity' => $activity[0], '_advance' => false, 'Description' => $desc_event);
                        $new_array = array('Code' => '01 - Event to be adressed', '_activity' => $activity[0], '_advance' => true, 'Description' => $desc_event, 'plant_code' => $plant_code);
                        $data2 = json_encode($new_array);
                        //RICHIESTA ARRAY PUT
                        $new_url2 = $base_url . '/processes/' . $cmdbuild_processname . '/instances/' . $id_process;
                        $ch001 = curl_init($new_url);
                        curl_setopt($ch001, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
                        curl_setopt($ch001, CURLOPT_RETURNTRANSFER, true);
                        //curl_setopt($ch001, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch001, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch001, CURLOPT_POSTFIELDS, $data2);

                        $response_req = curl_exec($ch001);
                        if (!curl_errno($ch001)) {
                            $info01 = curl_getinfo($ch001);
                            print_r($info01);
                            $http_code = "200";
                            $http_res = "OK";
                            $res_cod = "Process successfully updated";
                            print_r($response_req);
                        } else {
                            $n_er = curl_errno($ch001);
                            $n_er2 = curl_strerror($n_er);
                            $res_cod = $n_er2;
                            $http_code = "400";
                            $http_res = "Error";
                        }
                        curl_close($ch001);
                    }
                    break;
                case '02 - Verification of component list':
                    $res_cod = "Need necessary_materials";
                    //
                    if (isset($_REQUEST['necessary_materials'])) {
                        $desc_event = $_REQUEST['necessary_materials'];
                        $new_array['necessary_materials'] = $desc_event;
                        $res_cod = $new_array['necessary_materials'];
                        ///////status_process = "Check components in warehouse";
                        //$new_array = array('Code' => '02 - Verification of component list', '_activity' => $activity[0], '_advance' => false, 'necessary_materials' => $desc_event);
                        $new_array = array('Code' => '02 - Verification of component list', '_activity' => $activity[0], '_advance' => true, 'necessary_materials' => $desc_event);
                        //////
                        //$data2 = json_encode($new_array);
                        $data2 = json_encode($new_array);
                        //RICHIESTA ARRAY PUT
                        $new_url2 = $base_url . '/processes/' . $cmdbuild_processname . '/instances/' . $id_process;
                        $ch02 = curl_init($new_url);
                        curl_setopt($ch02, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
                        //curl_setopt($ch02, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch02, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch02, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch02, CURLOPT_POSTFIELDS, $data2);

                        $response_req = curl_exec($ch02);
                        print_r($response_req);
                        if (!curl_errno($ch02)) {
                            $info01 = curl_getinfo($ch02);
                            $http_code = "200";
                            $http_res = "OK";
                            $res_cod = "Process successfully updated";
                        } else {
                            $n_er = curl_errno($ch02);
                            $n_er2 = curl_strerror($n_er);
                            $res_cod = $n_er2;
                            $http_code = "400";
                            $http_res = "Error";
                        }
                        curl_close($ch02);
                    }
                    //
                    break;
                case '03 - Decision for Assignment':
                    $res_cod = "Need team_code";
                    //
                    if (isset($_REQUEST['team_code'])) {
                        $desc_event = $_REQUEST['team_code'];
                        //$new_array['team_code'] = $desc_event;
                        //
                        //$new_array = array('Code' => '03 - Decision for Assignment', '_activity' => $activity[0], '_advance' => false, 'Description' => $desc_code, 'team_code' => $desc_event);
                        $new_array = array('Code' => '03 - Decision for Assignment', '_activity' => $activity[0], '_advance' => true, 'team_code' => $desc_event);
                        //
                        $res_cod = $new_array['team_code'];
                        $data2 = json_encode($new_array);
                        //RICHIESTA ARRAY PUT
                        $new_url2 = $base_url . '/processes/' . $cmdbuild_processname . '/instances/' . $id_process;
                        $ch03 = curl_init($new_url);
                        curl_setopt($ch03, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
                        curl_setopt($ch03, CURLOPT_RETURNTRANSFER, true);
                        //curl_setopt($ch03, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch03, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch03, CURLOPT_POSTFIELDS, $data2);

                        $response_req = curl_exec($ch03);
                        if (!curl_errno($ch03)) {
                            $info01 = curl_getinfo($ch03);
                            //echo($info01);
                            $http_code = "200";
                            $http_res = "OK";
                            $res_cod = "Process successfully updated";
                            print_r($response_req);
                        } else {
                            $n_er = curl_errno($ch03);
                            $n_er2 = curl_strerror($n_er);
                            $res_cod = $n_er2;
                            $http_code = "400";
                            $http_res = "Error";
                        }
                        curl_close($ch03);
                    }
                    //
                    break;
                case '03 - Team Assignment':
                    $res_cod = "Need team_code";
                    //
                    if (isset($_REQUEST['team_code'])) {
                        $desc_event = $_REQUEST['team_code'];
                        $new_array['team_code'] = $desc_event;
                        $res_cod = $new_array['team_code'];
                        $data2 = json_encode($new_array);
                        //RICHIESTA ARRAY PUT
                        //
                          //$new_array = array('Code' => '03 - Decision for Assignment', '_activity' => $activity[0], '_advance' => false, 'team_code' => $desc_event);
                        $new_array = array('Code' => '03 - Decision for Assignment', '_activity' => $activity[0], '_advance' => true, 'team_code' => $desc_event);
                        //
                        $new_url2 = $base_url . '/processes/' . $cmdbuild_processname . '/instances/' . $id_process;
                        $ch03 = curl_init($new_url);
                        curl_setopt($ch03, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
                        curl_setopt($ch03, CURLOPT_RETURNTRANSFER, true);
                        //curl_setopt($ch03, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch03, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch03, CURLOPT_POSTFIELDS, $data2);

                        $response_req = curl_exec($ch03);
                        if (!curl_errno($ch03)) {
                            $info01 = curl_getinfo($ch03);
                            //echo($info01);
                            $http_code = "200";
                            $http_res = "OK";
                            $res_cod = "Process successfully updated";
                            print_r($response_req);
                        } else {
                            $n_er = curl_errno($ch03);
                            $n_er2 = curl_strerror($n_er);
                            $res_cod = $n_er2;
                            $http_code = "400";
                            $http_res = "Error";
                        }
                        curl_close($ch03);
                    }
                    //
                    break;
                case '04 - Working':
                    $res_cod = "Need work_description";
                    //
                    if (isset($_REQUEST['work_description'])) {
                        $desc_event = $_REQUEST['work_description'];
                        $new_array['work_description'] = $desc_event;
                        $res_cod = $new_array['work_description'];
                        $data2 = json_encode($new_array);
                        //
                        //$array_field_04 = array ('work_description' => $desc_event);
                        //$new_array = array('Code' => '04 - Working', '_activity' => $activity[0], '_advance' => false, 'work_description' => $desc_event);
                        $new_array = array('Code' => '04 - Working', '_activity' => $activity[0], '_advance' => true, 'work_description' => $desc_event);
                        //
                        $json_array_04 = json_encode($new_array);
                        //RICHIESTA ARRAY PUT
                        $new_url2 = $base_url . '/processes/' . $cmdbuild_processname . '/instances/' . $id_process;
                        $ch04 = curl_init($new_url);
                        curl_setopt($ch04, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
                        curl_setopt($ch04, CURLOPT_RETURNTRANSFER, true);
                        //curl_setopt($ch04, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch04, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch04, CURLOPT_POSTFIELDS, $json_array_04);


                        $response_req = curl_exec($ch04);
                        $error04 = curl_error($ch04);
                        if (!curl_errno($ch04)) {
                            $info01 = curl_getinfo($ch04);

                            $http_code = "200";
                            $http_res = "OK";
                            $res_cod = "Process successfully updated";
                        } else {
                            $n_er = curl_errno($ch04);
                            $n_er2 = curl_strerror($n_er);
                            $res_cod = $n_er2;
                            $http_code = "400";
                            $http_res = "Error n." . $n_er;
                        }
                        curl_close($ch04);
                    }
                    //
                    break;
                case '05 - Completed with success or not':
                    $res_cod = "Need Test Descpription and success";
                    /*                     * */
                    $final_comments = "";
                    if (isset($_REQUEST['test_description'])) {
                        $final_comments = $_REQUEST['test_description'];
                    } else {
                        $final_comments = '';
                    }
                    //

                    if (isset($_REQUEST['success'])) {

                        $success = false;
                        $success_par = $_REQUEST['success'];
                        if (($success_par == 'true') || ($success_par == 'True')) {
                            $success = true;
                            $new_array = array('Code' => '05 - Completed with success or not', '_activity' => $activity[0], '_advance' => true, 'test_description' => $final_comments, 'success' => true);
                        } elseif (($success_par == 'false') || ($success_par == 'False')) {
                            $success = false;
                            $new_array = array('Code' => '05 - Completed with success or not', '_activity' => $activity[0], '_advance' => true, 'test_description' => $final_comments);
                        } else {
                            $success = false;
                            $new_array = array('Code' => '05 - Completed with success or not', '_activity' => $activity[0], '_advance' => true, 'test_description' => $final_comments);
                        }
                        //
                    } else {
                        $new_array = array('Code' => '05 - Completed with success or not', '_activity' => $activity[0], '_advance' => true, 'test_description' => $final_comments);
                    }
                    //$new_array = array('Code' => '05 - Completed with success or not', '_activity' => $activity[0], '_advance' => false, 'test_description' => $final_comments, 'success'=> $success);
                    //$new_array = array('Code' => '05 - Completed with success or not', '_activity' => $activity[0], '_advance' => true, 'test_description' => $final_comments, 'success'=> $success);
                    //
                        //echo($new_array);
                    $data2 = json_encode($new_array);
                    //RICHIESTA ARRAY PUT
                    $new_url2 = $base_url . '/processes/' . $cmdbuild_processname . '/instances/' . $id_process;
                    $ch05 = curl_init($new_url);
                    curl_setopt($ch05, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
                    curl_setopt($ch05, CURLOPT_RETURNTRANSFER, true);
                    //curl_setopt($ch05, CURLOPT_FAILONERROR, true);
                    curl_setopt($ch05, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($ch05, CURLOPT_POSTFIELDS, $data2);

                    $response_req = curl_exec($ch05);
                    if (!curl_errno($ch05)) {
                        $info01 = curl_getinfo($ch05);
                        //echo($info01);
                        $http_code = "200";
                        $http_res = "OK";
                        $res_cod = "Process successfully updated";
                    } else {
                        $n_er = curl_errno($ch05);
                        $n_er2 = curl_strerror($n_er);
                        //print_r($n_er2);
                        $http_code = "400";
                        $http_res = "Error";
                        $res_cod = $n_er2;
                    }
                    curl_close($ch05);

                    /*                     * */
                    break;
                case '06 - Final comments':
                    $res_cod = "Need final comments";
                    if (isset($_REQUEST['final_comments'])) {
                        $final_comments = $_REQUEST['final_comments'];
                        $new_array['final_comments'] = $final_comments;
                        $res_cod = $new_array['final_comments'];
                        //
                        $new_array = array('Code' => '06 - Final comments', '_activity' => $activity[0], '_advance' => true, 'Description' => $desc_code, 'final_comments' => $final_comments);
                        //
                        $data2 = json_encode($new_array);
                        //RICHIESTA ARRAY PUT
                        $new_url2 = $base_url . '/processes/' . $cmdbuild_processname . '/instances/' . $id_process;
                        $ch6 = curl_init($new_url);
                        curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
                        curl_setopt($ch6, CURLOPT_RETURNTRANSFER, true);
                        //curl_setopt($ch6, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch6, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch6, CURLOPT_POSTFIELDS, $data2);

                        $response_req = curl_exec($ch6);
                        if (!curl_errno($ch6)) {
                            $info01 = curl_getinfo($ch6);
                            $http_code = "200";
                            $http_res = "OK";
                            $res_cod = "Process successfully updated";
                        } else {
                            $n_er = curl_errno($ch6);
                            $n_er2 = curl_strerror($n_er);
                            //print_r($n_er2);
                            $http_code = "400";
                            $http_res = "Error";
                            $res_cod = $n_er2;
                        }
                        curl_close($ch6);
                        /////
                        //
                                //$res_cod = $final_comments;
                    }
                    break;
                case '06 - Close Event':
                    $res_cod = "need recreate_event";
                    if (isset($_REQUEST['recreate_event'])) {
                        $final_comments = $_REQUEST['recreate_event'];
                        //$new_array['final_comments'] = $final_comments;
                        //$res_cod = $new_array['recreate_event'];
                        //
                       $success = false;
                        $success_par = $_REQUEST['recreate_event'];
                        if (($success_par == 'true') || ($success_par == 'True')) {
                            $success = true;
                            $new_array = array('Code' => '06 - Close Event', '_activity' => $activity[0], '_advance' => true, 'recreate_event' => true);
                        } elseif (($success_par == 'false') || ($success_par == 'False')) {
                            $success = false;
                            $new_array = array('Code' => '06 - Close Event', '_activity' => $activity[0], '_advance' => true);
                        } else {
                            $success = false;
                            $new_array = array('Code' => '06 - Close Event', '_activity' => $activity[0], '_advance' => true);
                        }
                        //
                        //$new_array = array('Code' => '06 - Close Event', '_activity' => $activity[0], '_advance' => true, 'recreate_event' => $final_comments);
                        //
                        $data2 = json_encode($new_array);
                        //RICHIESTA ARRAY PUT
                        $new_url2 = $base_url . '/processes/' . $cmdbuild_processname . '/instances/' . $id_process;
                        $ch6 = curl_init($new_url);
                        curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
                        curl_setopt($ch6, CURLOPT_RETURNTRANSFER, true);
                        //curl_setopt($ch6, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch6, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch6, CURLOPT_POSTFIELDS, $data2);

                        $response_req = curl_exec($ch6);
                        if (!curl_errno($ch6)) {
                            $info01 = curl_getinfo($ch6);
                            $http_code = "200";
                            $http_res = "OK";
                            $res_cod = "Process successfully updated";
                        } else {
                            $n_er = curl_errno($ch6);
                            $n_er2 = curl_strerror($n_er);
                            //print_r($n_er2);
                            $http_code = "400";
                            $http_res = "Error";
                            $res_cod = $n_er2;
                        }
                        curl_close($ch6);
                        /////
                    }
                    //
                    //$res_cod = $final_comments;

                    break;
            }
            //
            //
            $response_message['message'] = $http_res;
            $response_message['code'] = $http_code;
            $response_message['result'] = $res_cod;
            $res_api = json_encode($response_message);
            echo($res_api);
        } else {
            $response_message['message'] = 'KO';
            $response_message['code'] = 404;
            $mess = curl_error($ch1);
            $response_message['result'] = $mess;
            $res_api = json_encode($response_message);
            echo($res_api);
        }
        curl_close($ch1);
    }
//
    else if (isset($_REQUEST['delete_process'])) {
        $id_process = $_REQUEST['delete_process'];
        //********//

        $new_url = $base_url . '/processes/' . $cmdbuild_processname . '/instances/' . $id_process;
        $ch_del = curl_init($new_url);
        curl_setopt($ch_del, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
        curl_setopt($ch_del, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_del, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $response02 = curl_exec($ch_del);
        //url_close($ch);
        if (!curl_errno($ch_del)) {
            $info01 = curl_getinfo($ch_del);
            $results01 = json_decode($response02);
            //$data01 = $results01[$i]->{'data'};
            //$id01 = $data01[$i]->{'_id'};
            //
                            $response_message['message'] = 'OK';
            $response_message['code'] = 200;
            $response_message['result'] = 'Process Successfully deleted';
            $res_api = json_encode($response_message);
            echo($res_api);
            //
        } else {

            $response_message['message'] = 'KO';
            $response_message['code'] = 404;
            $response_message['result'] = 'Error during Process deleting';
            $res_api = json_encode($response_message);
            echo($res_api);

            //***//
        }
        curl_close($ch_del);
        //*******//
    } else if (isset($_REQUEST['get_plants'])) {
        $new_url = $base_url . '/classes/nr_plants/cards';
        $ch = curl_init($new_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $response_02 = curl_exec($ch);
        if (!curl_errno($ch)) {
            $info01 = curl_getinfo($ch);
            $results01 = json_decode($response_02);
            $data01 = $results01->{'data'};
            //$id01 = $data01->{'_id'};
            //
            $response_message['message'] = 'OK';
            $response_message['code'] = 200;
            $response_message['result'] = $data01;
            $res_api = json_encode($response_message);
            echo($res_api);
            //
        } else {

            $response_message['message'] = 'KO';
            $response_message['code'] = 404;
            $response_message['result'] = 'Error during Process creation';
            $res_api = json_encode($response_message);
            echo($res_api);
        }
        //
    } else if (isset($_REQUEST['get_teams'])) {
        $new_url = $base_url . '/classes/nr_teams/cards';
        $ch = curl_init($new_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $response_02 = curl_exec($ch);
        if (!curl_errno($ch)) {
            $info01 = curl_getinfo($ch);
            $results01 = json_decode($response_02);
            $data01 = $results01->{'data'};
            //$id01 = $data01->{'_id'};
            //
            $response_message['message'] = 'OK';
            $response_message['code'] = 200;
            $response_message['result'] = $data01;
            $res_api = json_encode($response_message);
            echo($res_api);
            //
        } else {

            $response_message['message'] = 'KO';
            $response_message['code'] = 404;
            $response_message['result'] = 'Error during Process creation';
            $res_api = json_encode($response_message);
            echo($res_api);
        }
    } else if (isset($_REQUEST['get_components'])) {
        $new_url = $base_url . '/classes/nr_materials/cards';
        $ch = curl_init($new_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $response_02 = curl_exec($ch);
        if (!curl_errno($ch)) {
            $info01 = curl_getinfo($ch);
            $results01 = json_decode($response_02);
            $data01 = $results01->{'data'};
            //$id01 = $data01->{'_id'};
            //
            $response_message['message'] = 'OK';
            $response_message['code'] = 200;
            $response_message['result'] = $data01;
            $res_api = json_encode($response_message);
            echo($res_api);
            //
        } else {

            $response_message['message'] = 'KO';
            $response_message['code'] = 404;
            $response_message['result'] = 'Error during Process creation';
            $res_api = json_encode($response_message);
            echo($res_api);
        }
    } else {
        //echo('No Data');
        $response_message['message'] = 'KO';
        $response_message['code'] = 404;
        $response_message['result'] = 'Not Valid parameter';
        $res_api = json_encode($response_message);
        echo($res_api);
    }



    $url_delsesssion = $base_url . '/sessions/' . $id;
    $ch_ds = curl_init($url_delsesssion);
    curl_setopt($ch_ds, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'CMDBuild-Authorization: ' . $id . ' '));
    curl_setopt($ch_ds, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_ds, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_exec($ch_ds);
    curl_close($ch_ds);
} else {
    //echo('You need to insert creandial to access');
    $response_message['message'] = 'Error during authentication';
    $response_message['code'] = 401;
    $response_message['result'] = 'Error';
    $res_api = json_encode($response_message);
    echo($res_api);
    exit();
}
//session_destroy();
