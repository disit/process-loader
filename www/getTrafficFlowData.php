<?php
/* Dashboard Builder.
  Copyright (C) 2018 DISIT Lab https://www.disit.org - University of Florence

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

//This file has been edited to also allow the operations by simply providing the accesstoken instead of only using a session.
//It is the responsability of those who call these APIs to provide the tokens should the session not be used.
//If no session is set and no token is provided, the result is an empty webpage
//If an accesstoken is provided, it will override the operations related to the session.
header("Access-Control-Allow-Origin: *");

session_start();
require "sso/autoload.php";
use Jumbojett\OpenIDConnectClient;
include "config.php"; // Includes Login Script
include "external_service.php";
error_reporting(E_ERROR);
$accessToken = null;
$url_api_filter =$serviceMapBaseUrl . "superservicemap/api/v1/iot-search/?model=trafficFlowModel&maxResults=1000";

if ($_REQUEST["accessToken"]) {
    $token = $_SESSION["accessToken"];
    //
    //OPERAZIONI PER IL REFRESH//
    $oidc = new OpenIDConnectClient(
        $ssoEndpoint,
        $ssoClientId,
        $ssoClientSecret
    );
    $oidc->providerConfigParam([
        "token_endpoint" =>
            $oicd_address . "/auth/realms/master/protocol/openid-connect/token",
    ]);
    $tkn = $oidc->refreshToken($_SESSION["refreshToken"]);
    $accessToken = $tkn->access_token;
    $_SESSION["refreshToken"] = $tkn->refresh_token;
    //
    //
    if (isset($_REQUEST["action"]) && !empty($_REQUEST["action"])) {
        $action = filter_var($_REQUEST["action"], FILTER_SANITIZE_STRING);
    } else {
        echo json_encode([
            "code" => "404",
            "message" => "Required action parameter",
        ]);
        exit();
    }

    if (isset($_SESSION["username"])) {
        $role_att = $_SESSION["role"];
    } else {
        $role_att = "";
    }

    if ($action === "list_data") {
        $url_api = $host_trafficflowmanager . "trafficflowmanager/api/metadata";
        $json_api = file_get_contents($url_api);

        if ($json_api === false) {
            echo json_encode([
                "code" => "500",
                "message" => "Failed to fetch data",
            ]);
            exit();
        }
        //

        if ($role_att !== "RootAdmin") {
            $json_api_old = $json_api;
            $json_api = filter_by_seach(
                $url_api_filter,
                $json_api_old,
                $accessToken
            );
        }

        $list_api = json_decode($json_api);
        echo json_encode($list_api);
    } elseif ($action === "delete_metadata") {
        $url = $host_trafficflowmanager . "trafficflowmanager/api/metadata";

        $id_heat = filter_var($_REQUEST["id"], FILTER_SANITIZE_STRING);
        $data = [
            "id" => $id_heat,
            "action" => "delete_metadata",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Converte array in stringa URL-encoded
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Per ricevere la risposta
        $response = curl_exec($ch);
        // Controllo errori
        if (curl_errno($ch)) {
            echo "Errore cURL: " . curl_error($ch);
        } else {
            echo "Successfully deleted";
            /////////
            $iot_directory_api = $iotDirectoryBaseApiUrl;
            $iot_directory_model = $iot_directory_api . "/api/model.php";
            $iot_directory_device = $iot_directory_api . "/api/device.php";
            ////////////
            //
            $array = explode('_', $id_heat);
            $iot_broker = $array[0];
            $devicetype = 'Traffic_flow';
            //
            $id_device = $id_heat.'_TFR_1';
            //
            ////////DELETE DEL DEVICE
            $data_array = [
                "action" => "delete",
                "id" => $id_device,
                "type" => $devicetype,
                "contextbroker" => $iot_broker,
                "kind" => "sensor",
                "format" => "json",
                "frequency" => "600",
                "producer" => "",
                "model" => "trafficFlowModel",
                "k1" => "",
                "k2" => "",
                "token" => $accessToken,
                "nodered" => "access",
            ];
            $data_array0 = json_encode($data_array);
            /////
            $curl = curl_init();
            $url_device = sprintf(
                "%s?%s",
                $iot_directory_device,
                http_build_query($data_array)
            );
            curl_setopt($curl, CURLOPT_URL, $url_device);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Authorization: Bearer " . $accessToken,
            ]);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $deviceCall = curl_exec($curl);
            if ($deviceCall === false) {
                echo "Errore cURL: " . curl_error($curl);
            }
            $response = json_decode($deviceCall, true);
            $status = $response["status"];
            $message_output = $response["status"];
            //
            curl_close($curl);
            echo json_encode($response);
            /////
        }

        curl_close($ch);
    } else {
        echo json_encode([
            "code" => "404",
            "message" => "Not recognized action",
        ]);
        exit();
    }
} else {
    echo json_encode(["code" => "401", "message" => "Required accesstoken"]);
    exit();
}

function filter_by_seach($url_api_filter, $json_api, $accessToken)
{
    //
    $array = [];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_api_filter);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Per ottenere la risposta come stringa
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $accessToken,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $apiCall = curl_exec($ch);
    $apiArray = json_decode($apiCall, true);
    $count = $apiArray["fullCount"];
    $features = $apiArray["features"];
    //
    //echo json_encode($apiArray);
//
    for ($i = 0; $i < $count; $i++) {
        $data = $features[$i]["properties"];
        if ($data["values"]["scenarioName"] !== "") {
            $row = $data["values"]["scenarioName"];
            //$row['output']=$data;
            array_push($array, $row);
        }else{
            $row = str_replace("_TFR_1", "", $data['deviceName']);
            array_push($array, $row);
        }
    }
    //
    curl_close($ch);
    $data_filter = json_decode($json_api, true);
    $filteredData = array_filter($data_filter, function ($item) use ($array) {
        return in_array($item["scenarioID"], $array);
    });
    $filteredDataOutput = json_encode(
        array_values($filteredData),
        JSON_PRETTY_PRINT
    );
    //
    return $filteredDataOutput;
}
?>
