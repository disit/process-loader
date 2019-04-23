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


include ('config.php');
include ('external_service.php');
include ('functionalities.php');
require 'sso/autoload.php';

use Jumbojett\OpenIDConnectClient; 


if(isset($_REQUEST['action'])){
	$action = $_REQUEST['action'];
	$response = [];
if($action == 'addDelegation'){
			//
			$heatmap= $_REQUEST['heatmap'];
			$newDelegated= $_REQUEST['newDelegation'];
			$user= $_REQUEST['user'];
			$callBody = [];
				
						$dashboardAuthor = $user;
						if (isset($_SESSION['refreshToken'])){
							//
							$token = $_SESSION['accessToken'];
							$list_api = array();
							$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);	
							$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
							$tkn = $oidc->refreshToken($_SESSION['refreshToken']);
							$accessToken = $tkn->access_token;	
							$_SESSION['refreshToken'] = $tkn->refresh_token;
							//
							$callBody = ["usernameDelegated" => $newDelegated, "elementId" => $heatmap, "elementType" => "HeatmapID"];
							$apiUrl = $delegationDataApiUrl  . "/v1/username/" . $dashboardAuthor . "/delegation?accessToken=" . $accessToken . "&sourceRequest=ProcessLoader";
							$options = array(
								'http' => array(
									'header' => "Content-type: application/json\r\n",
									'method' => 'POST',
									'timeout' => 30,
									'content' => json_encode($callBody),
									'ignore_errors' => true
								));
							try{
								$context = stream_context_create($options);
								$callResult = file_get_contents($apiUrl, false, $context);
								//print($callResult);
								if (strpos($http_response_header[0], '200') !== false){
										echo('OK');
									}else{
										echo('ApiCallKo');
									}
								}
							catch(Exception $ex){
								echo('ApiCallKo');
								$response['detail'] = 'ApiCallKo';
								}
							}else{
								echo('NO REFRESHTOKEN');
							}
			echo $response;
			
}else if ($action == 'changePublic'){
		//
	$heatmap=$_REQUEST['heatmap'];
    $newVisibility=$_REQUEST['newVisibility'];
	$dashboardAuthor = $_REQUEST['user'];
	
	if ($newVisibility == 'private'){
		$dbVisibility = 'author';
		} else{
		$dbVisibility = 'public';
		}

	if (isset($_SESSION['refreshToken'])){
			
			$token = $_SESSION['accessToken'];
			$list_api = array();
			$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);	
			$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
			$tkn = $oidc->refreshToken($_SESSION['refreshToken']);
			$accessToken = $tkn->access_token;	
			$_SESSION['refreshToken'] = $tkn->refresh_token;
			
			if ($newVisibility == 'public')
				{

				// Da privata a pubblica: 1) Cancelliamo deleghe pregresse;

				$apiUrl = $delegationDataApiUrl . "/v1/apps/" . $heatmap . "/delegations?accessToken=" . $accessToken . "&sourceRequest=ProcessLoader";
				//echo($apiUrl);
				$options = array(
					'http' => array(
						'header' => "Content-type: application/json\r\n",
						'method' => 'DELETE',
						'timeout' => 30,
						'ignore_errors' => true
					)
				);
				$context = stream_context_create($options);
				$delegatedDashboardsJson = file_get_contents($apiUrl, false, $context);
				if (strpos($http_response_header[0], '200') !== false)
					{

					// 2) Aggiungiamo delega anonima;

					$callBody = ["usernameDelegated" => "ANONYMOUS", "elementId" => $heatmap, "elementType" => "HeatmapID"];
					$apiUrl = $delegationDataApiUrl . "/v1/username/" . $dashboardAuthor . "/delegation?accessToken=" . $accessToken . "&sourceRequest=ProcessLoader";
					echo($apiUrl);
					$options = array(
						'http' => array(
							'header' => "Content-type: application/json\r\n",
							'method' => 'POST',
							'timeout' => 30,
							'content' => json_encode($callBody) ,
							'ignore_errors' => true
						)
					);
					try
						{
						$context = stream_context_create($options);
						$callResult = file_get_contents($apiUrl, false, $context);
						if (strpos($http_response_header[0], '200') !== false)
							{
							$response['detail'] = 'Ok';
							}
						  else
							{
							$response['detail'] = 'AddPublicDelegationApiCallKo';
							$response['detail2'] = $http_response_header[0];
							}
						}

					catch(Exception $ex)
						{
						$response['detail'] = 'DelOldDelegationsApiCallKo';
						}
					}
				  else
					{
					print_r($http_response_header[0]);
					$response['detail'] = 'DeleteOldDelegationsKo';
					}
				} else{

				// Da pubblica a privata: 1) Cancelliamo deleghe (anche quella pubblica);

				$apiUrl = $delegationDataApiUrl  . "/v1/apps/" . $heatmap . "/delegations?accessToken=" . $accessToken . "&sourceRequest=ProcessLoader";
				//
				$options = array(
					'http' => array(
						'header' => "Content-type: application/json\r\n",
						'method' => 'DELETE',
						'timeout' => 30,
						'ignore_errors' => true
					)
				);
				$context = stream_context_create($options);
				$delegatedDashboardsJson = file_get_contents($apiUrl, false, $context);
				if (strpos($http_response_header[0], '200') !== false){
					$response['detail'] = 'Ok';
					}else{
					print_r($http_response_header[0]);
					$response['detail'] = 'DeleteOldDelegationsKo_2';
					}
				}
			
		}

	echo json_encode($response);
}else if ($action == 'changeOwnership'){
		$heatmap = $_REQUEST['heatmap'];
		$user = $_REQUEST['user'];
		$newOwner = $_REQUEST['newOwner'];	
		//$ldapUsername = "cn=" . $newOwner . "," . $ldapParamters;
		$ldapUsername = "cn=". $user .  ",".$ldapParamters;
		if (isset($_SESSION['refreshToken'])){
						$token = $_SESSION['accessToken'];
						$list_api = array();
						$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);	
						$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
						$tkn = $oidc->refreshToken($_SESSION['refreshToken']);
						$accessToken = $tkn->access_token;	
						$_SESSION['refreshToken'] = $tkn->refresh_token;
						$url_api =($personalDataApiBaseUrl.'/v1/list/?type=ProcessLoader&accessToken='.$accessToken);
						$callBody = ["elementId" => $heatmap, "elementType" => "HeatmapID", "username" => $newOwner, "elementName" => ""];
						$apiUrl = $personalDataApiBaseUrl . "/v1/register/?accessToken=" . $accessToken;
						$options = array(
										'http' => array(
												 'header'  => "Content-type: application/json\r\n",
												 'method'  => 'POST',
												 'timeout' => 30,
												 'content' => json_encode($callBody),
												 'ignore_errors' => true
											 )
										);
				
						try{
							$context  = stream_context_create($options);
							$callResult = @file_get_contents($apiUrl, false, $context);
							//
							print_r($callResult);
						    //
							if(strpos($http_response_header[0], '200') !== false) {
								echo('Ok');  
							} else {
								echo('ApiCallKo1');
								print_r($http_response_header[0]);
							}
						}catch (Exception $ex){
								echo('ApiCallKo2');
								print_r($http_response_header[0]);
						}
				
			} else{
				echo('checkUserKo');
			}

		}else if ($action == 'delegateGroup'){
					//echo ('Delegete Group');
					$newDelegationOrg = $_REQUEST['newDelegationOrg'];
					$newDelegated = $_REQUEST['newDelegationGroup'];
					$user = $_REQUEST['user'];
					$heatmap = $_REQUEST['heatmap'];
					$role = $_REQUEST['role'];
					$oldDelegationGroup = $REQUEST['oldDelegationGroup'];
					
					////
							if(isset($_SESSION['refreshToken'])){
								$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);
								$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
								$tkn = $oidc->refreshToken($_SESSION['refreshToken']);
								$accessToken = $tkn->access_token;	
								$_SESSION['refreshToken'] = $tkn->refresh_token;
								//
								//$newDelegatedEncoded = urlencode($newDelegated);
								$newDelegatedEncoded =$newDelegated;
								if ($role == 'RootAdmin'){
										$newDelegatedEncoded = "cn=".$newDelegatedEncoded.",ou=".$newDelegationOrg.",".$ldapParamters;
										//
									}else{
										$newDelegatedEncoded = "ou=".$newDelegationOrg.",".$ldapParamters;
										//
									}
								//echo($newDelegatedEncoded);
								$callBody = ["groupnameDelegated" => $newDelegatedEncoded, "elementId" => $heatmap , "elementType" => "HeatmapID"];
								//
								$apiUrl =  $delegationDataApiUrl . "/v1/username/" . $user  . "/delegation?accessToken=" . $accessToken . "&sourceRequest=ProcessLoader";
								//echo($apiUrl);
								//
								$options = array(
									'http' => array(
										'header'  => "Content-type: application/json\r\n",
										'method'  => 'POST',
										'timeout' => 30,
										'content' => json_encode($callBody),
										'ignore_errors' => true
									)
								);
								//
								try{
									$context  = stream_context_create($options);
									$callResult = file_get_contents($apiUrl, false, $context);

									if(strpos($http_response_header[0], '200') !== false){
										//
											if($newDelegationOrg != $oldDelegationGroup){
													$link = mysqli_connect($host_heatmap, $username_heatmap, $password_heatmap) or die("failed to connect to server !!");
															mysqli_set_charset($link, 'utf8');
															mysqli_select_db($link, $dbname_heatmap);
														$query1 = "UPDATE heatmap.metadata SET org='" . $newDelegationOrg . "' WHERE map_name='" . $heatmap . "'";
														$result1 = mysqli_query($link, $query1) or die(mysqli_error($link));
											}			
										//
										echo 'Ok';
									}else{
										echo 'ApiCallKo';
									}
								}catch(Exception $ex){
									echo 'ApiCallKo_NO';
								}
							}
					////
					echo($response['detail']);
					//var newDelegationGroup = $('#newDelegationGroup').text();
		}else if ($action == 'getDelegation'){
					$heatmap = $_REQUEST['heatmap'];
					
					//LISTA DELLE DELEGHE
					if(isset($_SESSION['refreshToken'])){
								$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);
								$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
								$tkn = $oidc->refreshToken($_SESSION['refreshToken']);
								$accessToken = $tkn->access_token;	
								$_SESSION['refreshToken'] = $tkn->refresh_token;
									//
									//echo($heatmap);
									$apiUrl =  $delegationDataApiUrl . "/v1/apps/" . $heatmap  . "/delegator?accessToken=" . $accessToken . "&sourceRequest=ProcessLoader";
									$callResult = file_get_contents($apiUrl);
									//$result = json_encode($callResult);
									echo json_encode($callResult);
									//print_r($result);
									//
					}
					//
		}else if($action == 'deleteDelegation'){
						$heatmap = $_REQUEST['heatmap'];
						$delegated = $_REQUEST['delegated'];
						$delegator =  $_REQUEST['delegator'];
						$id= $_REQUEST['id'];
						//
						$token = $_SESSION['accessToken'];
						$list_api = array();
						$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);	
						$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
						$tkn = $oidc->refreshToken($_SESSION['refreshToken']);
						$accessToken = $tkn->access_token;	
						$_SESSION['refreshToken'] = $tkn->refresh_token;

									$apiUrl = $delegationDataApiUrl . "/v1/username/".$delegated."/delegation/".$id."?accessToken=" . $accessToken . "&sourceRequest=ProcessLoader";
									echo($apiUrl);
									$options = array(
										'http' => array(
											'header' => "Content-type: application/json\r\n",
											'method' => 'DELETE',
											'timeout' => 30,
											'ignore_errors' => true
										)
									);	
									///////////
									try
										{
										$context = stream_context_create($options);
										$callResult = file_get_contents($apiUrl, false, $context);
										if (strpos($http_response_header[0], '200') !== false)
											{
											$response['detail'] = 'Ok';
											}
										  else
											{
											$response['detail'] = 'AddPublicDelegationApiCallKo';
											$response['detail2'] = $http_response_header[0];
											}
										}

									catch(Exception $ex)
										{
										$response['detail'] = 'DelOldDelegationsApiCallKo';
										}	
								print_r($response);
						////
				}else if($action == 'getGroups'){
					
						$ldapPort = 389;
						$ldapBaseDN = $ldapParamters;
						$connection = ldap_connect($ldapServer, $ldapPort);
						$resultldap = ldap_search($connection, $ldapBaseDN, '(objectClass=organizationalUnit)');
						$entries = ldap_get_entries($connection, $resultldap);
						$result=array("status"=>"","msg"=>"","content"=>"","log"=>"");	

						if (ldap_count_entries($connection,$resultldap)==0){
								error_log("No LDAP organization Unit found at all");
								$result['status'] = 'ko'; 
								$result['msg'] = 'Error: No LDAP organization Unit found at all <br/>';
								$result['log'] .= '\n\r action:get_all_ou. Error: No LDAP organization Unit found at all ';
						}
						else{
								for ($i = 0; $i<$entries["count"]; $i++) {
										$allOu[$i]=$entries[$i]["ou"][0];
								}
							$result['status'] = 'ok';
								$result['content'] =  $allOu;
								$result['log'] .= "\n\r action:get_all_ou. Ok, got n-entries:".count($allOu);
						}	
						//my_log($result);
						echo json_encode($result);								
					/**************/								
				}else if($action == 'getDelegatedGroups'){
						////
						$result=array("status"=>"","msg"=>"","content"=>"","log"=>"");	
					//
								$ldapPort = 389;
								$ldapBaseDN = $ldapParamters;
								$connection = ldap_connect($ldapServer, $ldapPort);
								$resultldap = ldap_search($connection, $ldapBaseDN, '(&(objectClass=groupOfNames)(ou='.$_REQUEST['ou'].'))');
								$entries = ldap_get_entries($connection, $resultldap);
								$allGroupsUserOu=array();

								for ($i = 0; $i<$entries["count"]; $i++) {
										$allGroupsUserOu[$i]=$entries[$i]["cn"][0];
								}
								
								$result['status'] = 'ok';
								$result['content'] =  $allGroupsUserOu;
								$result['log'] .= "\n\r action:get_group_for_ou. Ok, got n-entries: ". count($allGroupsUserOu);
								echo json_encode($result);
						////
				}else if($action == 'getDelegatedGroupsTable'){
						//
						$heatmap = $_REQUEST['heatmap'];
						$user = $_REQUEST['user'];
						$ou = $_REQUEST['ou'];
						$delegated = [];
						//
						$response=array("detail"=>"","delegationId"=>"","ldapCheck"=>"","detail"=>"","detail2"=>"");	
						$token = $_SESSION['accessToken'];
						$list_api = array();
						$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);	
						$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
						$tkn = $oidc->refreshToken($_SESSION['refreshToken']);
						$accessToken = $tkn->access_token;	
						$_SESSION['refreshToken'] = $tkn->refresh_token;

								$apiUrl = $delegationDataApiUrl . "/v2/username/" . $user . "/delegator?accessToken=" . $accessToken . "&sourceRequest=ProcessLoader";
				
								$options = array(
									'http' => array(
											'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
											'method'  => 'GET',
											'timeout' => 30,
											'ignore_errors' => true
									)
								);

								$context  = stream_context_create($options);
								$delegatedDashboardsJson = file_get_contents($apiUrl, false, $context);

								$delegatedDashboards = json_decode($delegatedDashboardsJson);

								for($i = 0; $i < count($delegatedDashboards); $i++) 
									{
										if($delegatedDashboards[$i]->elementId == $heatmap)
										{
											if (!is_null($delegatedDashboards[$i]->usernameDelegated)) {
												$newDelegation = ["delegationId" => $delegatedDashboards[$i]->id, "delegatedUser" => $delegatedDashboards[$i]->usernameDelegated];
											} else if (!is_null($delegatedDashboards[$i]->groupnameDelegated)) {
												$auxString = "";
												$auxString2 = "";
												if (explode("cn=", $delegatedDashboards[$i]->groupnameDelegated)!= "") {
													$auxString = explode("cn=", $delegatedDashboards[$i]->groupnameDelegated)[1];
													$auxString = explode(",", $auxString)[0];
													$auxString2 = explode("ou=", $delegatedDashboards[$i]->groupnameDelegated)[1];
													$auxString2 = explode(",", $auxString2)[0];
													if ($auxString != "") {
														$auxString = $auxString2 . " - " . $auxString;
													} else {
														$auxString = $auxString2 . " - All Groups";
													}
												} else if (explode("ou=", $delegatedDashboards[$i]->groupnameDelegated) != "") {
													$auxString = explode("ou=", $delegatedDashboards[$i]->groupnameDelegated)[1];
													$auxString = explode(",", $auxString)[0];
												}
												$newDelegation = ["delegationId" => $delegatedDashboards[$i]->id, "delegatedGroup" => $auxString];
											}
											array_push($delegated, $newDelegation);
										}
									}

									echo json_encode($delegated);
				}else if($action == 'deleteGroups'){
									//
									$delegated = $_REQUEST['delegated'];
									$id= $_REQUEST['id'];
									$response=array("detail"=>"","delegationId"=>"","ldapCheck"=>"","detail"=>"","detail2"=>"");
									//
									$token = $_SESSION['accessToken'];
									$list_api = array();
									$oidc = new OpenIDConnectClient($ssoEndpoint, $ssoClientId, $ssoClientSecret);	
									$oidc->providerConfigParam(array('token_endpoint' => $oicd_address.'/auth/realms/master/protocol/openid-connect/token'));
									$tkn = $oidc->refreshToken($_SESSION['refreshToken']);
									$accessToken = $tkn->access_token;	
									$_SESSION['refreshToken'] = $tkn->refresh_token;
									////
												//$apiUrl = $delegationDataApiUrl . "/v1/apps/" . $heatmap . "/delegations?accessToken=" . $accessToken . "&sourceRequest=ProcessLoader";
												$apiUrl = $delegationDataApiUrl . "/v1/username/".$delegated."/delegation/".$id."?accessToken=" . $accessToken . "&sourceRequest=ProcessLoader";
												$options = array(
													'http' => array(
														'header' => "Content-type: application/json\r\n",
														'method' => 'DELETE',
														'timeout' => 30,
														'ignore_errors' => true
													)
												);	
												///////////
												try
													{
													$context = stream_context_create($options);
													$callResult = file_get_contents($apiUrl, false, $context);
													if (strpos($http_response_header[0], '200') !== false)
														{
														$response['detail'] = 'Ok';
														}
													  else
														{
														$response['detail'] = 'AddPublicDelegationApiCallKo';
														$response['detail2'] = $http_response_header[0];
														}
													}

												catch(Exception $ex)
													{
													$response['detail'] = 'DelOldDelegationsApiCallKo';
													}	
											print_r($response);
							}else{
								echo ('ERROR');
						}

}else{
	echo('ERROR');	
}
?>