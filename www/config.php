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


$username = getenv("PL_DB_USER") ?: "username";
$password = getenv("PL_DB_PASSWORD") ?: "password";
$dbname = getenv("PL_DATABASE") ?: "processloader_db";
$host = getenv("PL_DB_HOST") ?: "localhost";

$host_valueunit = getenv("PL_VALUEUNIT_HOST") ?: $host;
$username_valueunit = getenv("PL_VALUEUNIT_USER") ?: $username;
$password_valueunit = getenv("PL_VALUEUNIT_PASSWORD") ?: $password;
$dbname_valueunit = getenv("PL_VALUEUNIT_DATABASE") ?: $dbname;

//SERVER LDAP
$ldapServer = getenv("PL_LDAPSERVER") ?: 'localhost';
$ldapParamters = getenv("PL_LDAPBASE") ?: 'dc=ldap,dc=organization,dc=com';
$ldapAdminDN = getenv("PL_LDAPADMINDN") ?: 'cn=admin,'.$ldapParamters;
$ldapAdminPwd = getenv("PL_LDAPADMINPWD") ?: 'password';

$baseUrl = getenv("PL_BASE_URL") ?: 'http://localhost';
$ssoEndpoint = $oicd_address = getenv("PL_SSO_ENDPOINT") ?: $baseUrl;
$ssoClientId = getenv("PL_SSO_CLIENTID") ?: 'php-dashboard-builder';
$ssoClientSecret = getenv("PL_SSO_CLIENTSECRET") ?: '---';
$personalDataApiBaseUrl = getenv("PL_PERSONAL_DATA_API_BASE_URL") ?: $baseUrl . '/ownership-api';
$delegationDataApiUrl = getenv("PL_DELEGATION_DATA_API_URL") ?: $baseUrl . '/datamanager/api';

$access_token_userinfo = $baseUrl . '/auth/realms/master/protocol/openid-connect/userinfo';
$appUrl= $baseUrl . '/processloader';

$colormap_external_path = getenv("PL_COLORMAP_EXTERNAL_PATH") ?: '/var/www/html/dashboardSmartCity/img/';
$colormap_external_link = getenv("PL_COLORMAP_EXTERNAL_LINK") ?: $baseUrl . '/dashboardSmartCity/img/';
$colormap_external_directory = getenv("PL_COLORMAP_EXTERNAL_DIRECTORY") ?: 'heatmapsGradientLegends/';

$preview_path = getenv("PL_PREVIEW_PATH") ?: $baseUrl . '/dashboardSmartCity/view/preview.php';
$host_trafficflowmanager = getenv("PL_TRAFFICFLOWMANAGER_PATH") ?: $baseUrl . '/trafficflowmanager';

//nome repository files
$repository_files = 'jobs/';
$repository_destination = '/srv/share/jobs/';
$server_share = '/srv/share/jobs/';
$ip_SCE = 'localhost';
$SCE_dbname = "quartz";

//JAVA PARAMETERS
$java_path ='/usr/lib/jvm/java-8-openjdk-amd64/bin/java';

//ETL PARAMETERS
$DDI_HOME = '-DDI_HOME=/home/ubuntu/programs/data-integration/';
$classpath= '-classpath';
$pentaho = 'org.pentaho.di.kitchen.Kitchen';
$xms = '-Xmx512m';
$data_integration_path = ':/home/ubuntu/programs/data-integration/lib/*';
$level = 'Debug';

//R PARAMETERS
$r_path = '/usr/bin/Rscript';

$storeSessionOnDB = getenv("PL_SESSIONONDB") ?: 'no';
include_once __DIR__ . "/init_session.php";
session_start();

$connessione_al_server= mysqli_connect($host, $username, $password) or die("Errore di Connessione!!");
if(!$connessione_al_server){
die ('Non riesco a connettermi: errore '.mysqli_error()); // questo apparirà solo se ci sarà un errore
}
 
$db_selected=mysqli_select_db($connessione_al_server,$dbname); // dove io ho scritto "prova" andrà inserito il nome del db
if(!$db_selected){
die ('Errore nella selezione del database: errore '.mysqli_error()); // se la connessione non andrà a buon fine apparirà questo messaggio
}
