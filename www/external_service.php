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

//EXTERNAL SERVICES//
$profiledb_host= getenv('PL_PROFILEDB_HOST') ?: 'localhost';
$profiledb_username= getenv('PL_PROFILEDB_USERNAME') ?: 'user';
$profiledb_password= getenv('PL_PROFILEDB_PASSWORD') ?: 'password';
$profiledb_name= getenv('PL_PROFILEDB_NAME') ?: 'profiledb';
$snap4city_API= getenv('PL_SNAP4CITY_API') ?: 'localhost';

//PHOTO SERVICE//
$host_photo= getenv('PL_PHOTO_HOST') ?: 'localhost';
$username_photo= getenv('PL_PHOTO_USERNAME') ?: 'user';
$password_photo= getenv('PL_PHOTO_PASSWORD') ?: 'password';
$dbname_photo= getenv('PL_PHOTO_DBNAME') ?: 'ServiceMap';
$photo_api='http://localhost/ServiceMap/api/v1/photo/thumbs/';
$default_longitude = 11.252;
$default_latitude = 43.773;
$default_serviceUri = 'http://localhost/ServiceMap/api/v1/?serviceUri=';
$photo_service_api = 'http://localhost/ServiceMap/ajax/';

//KPI EDITOR//
$host_kpi= getenv('PL_KPI_HOST') ?: 'localhost';
$username_kpi= getenv('PL_KPI_USERNAME') ?: 'user';
$password_kpi= getenv('PL_KPI_PASSWORD') ?: 'password';
$dbname_kpi= getenv('PL_KPI_DBNAME') ?: 'processloader_db';

//heatmap server
$host_heatmap = getenv('PL_HEATMAP_HOST') ?: 'localhost';
$username_heatmap = getenv('PL_HEATMAP_USERNAME') ?: 'user';
$password_heatmap = getenv('PL_HEATMAP_PASSWORD') ?: 'password';
$dbname_heatmap = getenv('PL_HEATMAP_DBNAME') ?: 'heatmap';

//userlimits
$host_limits = getenv('PL_LIMITS_HOST') ?: $profiledb_host;
$username_limits = getenv('PL_LIMITS_USERNAME') ?: $profiledb_username;
$password_limits = getenv('PL_LIMITS_PASSWORD') ?: $profiledb_password;
$dbname_limits = getenv('PL_LIMITS_DBNAME') ?: 'profiledb';

$org_limits_api = getenv('PL_LIMITS_ORG_API') ?: $baseUrl . '/dashboardSmartCity/api/organizations.php';
$types_limits = ["IOTID","DashboardID","ModelID","AppID","DAAppID","DeviceGroupID","HeatmapID","PortiaID","SynopticID","SynopticTmplID","BrokerID"];

$host_od = getenv('PL_OD_HOST') ?: 'od-postgis';
$username_od = getenv('PL_OD_USERNAME') ?: 'postgres';
$password_od = getenv('PL_OD_PASSWORD') ?: '';
$dbname_od = getenv('PL_OD_DBNAME') ?: 'postgres';

$host_dashboardbuilder = getenv('PL_DASHBOARDBUILDER_HOST') ?: $host;
$username_dashboardbuilder = getenv('PL_DASHBOARDBUILDER_USERNAME') ?: $username;
$password_dashboardbuilder = getenv('PL_DASHBOARDBUILDER_PASSWORD') ?: $password;
$db_dashboardbuilder = getenv('PL_DASHBOARDBUILDER_DBNAME') ?: 'Dashboard';
