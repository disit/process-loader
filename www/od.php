<!DOCTYPE html>
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

require 'sso/autoload.php';
use Jumbojett\OpenIDConnectClient;

include('config.php'); // Includes Login Script
include('control.php');
include('external_service.php');
include('functionalities.php');

if (isset ($_SESSION['username'])){
    $utente_att = $_SESSION['username'];
}else{
    $utente_att= "Login";
}

if (isset ($_SESSION['username'])){
    $role_att = $_SESSION['role'];
}else{
    $role_att= "";
}

if (isset($_REQUEST['showFrame'])){
    if ($_REQUEST['showFrame'] == 'false'){
        $hide_menu= "hide";
        $sf="false";
    }else{
        $hide_menu= "";
        $sf="true";
    }
}else{
    $hide_menu= "";
    $sf="true";
}

if (isset($_REQUEST['pageTitle'])){
    $default_title = $_REQUEST['pageTitle'];
}else{
    $default_title = "OD Manager";
}
$utente_ownership = "";
$pagina_attuale = $_SERVER['REQUEST_URI'];
$link = new PDO("pgsql:host=".$host_od.";dbname=".$dbname_od, $username_od, $password_od) or die("failed to connect to server !!");

if (isset($_REQUEST['orderBy'])){
    $order = $_REQUEST['orderBy'];
}else{
    $order = 'od_id';
}

if (isset($_REQUEST['order'])){
    $by = $_REQUEST['order'];
}else{
    $by = 'DESC';
}

$start_from = 0;
if (isset($_REQUEST['limit'])|| $_REQUEST['limit']!==""){
    $limit=$_REQUEST['limit'];
}else{
    $limit = 10;
}

if ($_REQUEST['limit'] == ""){
    $limit = 10;
}

if (isset($_REQUEST["page"])) {
    $page  = $_REQUEST["page"];
} else {
    $page=1;
}
$start_from = ($page-1) * $limit;


$query_n =	"SELECT table_id, od_metadata.od_id,value_type, value_unit, description, organization, kind, mode, transport, purpose, precision
    FROM od_metadata
	FULL OUTER JOIN (SELECT 'od_data' as table_id, od_id, precision
              FROM od_data 
	          GROUP BY od_id, precision
	          UNION ALL
          SELECT 'od_data_mgrs' as table_id, od_id, precision
              FROM od_data_mgrs
	          GROUP BY od_id, precision) AS table_union 
			  ON od_metadata.od_id = table_union.od_id";
$query_n_count = "SELECT COUNT(od_id) FROM od_metadata";

///
$count_number = 0;
$result = $link->query($query_n) or die($link->errorInfo());

$process_list = array();
$num_rows     = $link->query($query_n_count)->fetchColumn();
$num_r = 0;

if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}

if ($num_rows > 0) {
    foreach ($result as $row) {
        $od_id = $row['od_id'];
        $value_type = $row['value_type'];
        $value_unit = $row['value_unit'];
        $description = $row['description'];
        $organization = $row['organization'];
        $shape = "";
        $precision = $row['precision'];
        $kind = $row['kind'];
        $mode = $row['mode'];
        $transport = $row['transport'];
        $purpose = $row['purpose'];
        $metric_name = "ODcolormap1";
        if($row['table_id'] == "od_data") { // modifica per considerare poi, ace, ecc...
            $shape = "communes";
            if(str_contains($od_id,"poi")){
                $shape = "POIs";
            } else if (str_contains($od_id,"section")){
                $shape = "Sections";
            } else if (str_contains($od_id,"ace")){
                $shape = "ACEs";
            } else if (str_contains($od_id,"municipality")){
                $shape = "Municipalities";
            } else if (str_contains($od_id,"province")){
                $shape = "Provinces";
            } else if (str_contains($od_id,"region")){
                $shape = "Regions";
            } 
        }else if($row['table_id'] == "od_data_mgrs"){
            $shape = "square";
        }

        $listFile = array("od_id" => $od_id,
            "value_type" => $value_type,
            "value_unit" => $value_unit,
            "description" => $description,
            "organization" => $organization,
            "shape" => $shape,
            "precision" => $precision,
            "kind" => $kind,
            "mode" => $mode,
            "transport" => $transport,
            "purpose" => $purpose,
            "metric_name" => $metric_name
        );
        array_push($process_list, $listFile);
    }
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard Management System</title>

    <!-- jQuery -->
    <script src="jquery/jquery-1.10.1.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/bootstrap.min.js"></script>

    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/bootstrap.css" rel="stylesheet">

    <!-- Bootstrap toggle button -->
    <link href="bootstrapToggleButton/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="bootstrapToggleButton/js/bootstrap-toggle.min.js"></script>

    <!-- Dynatable -->
    <link rel="stylesheet" href="dynatable/jquery.dynatable.css">
    <script src="dynatable/jquery.dynatable.js"></script>

    <!-- Font awesome icons -->
    <link rel="stylesheet" href="fontAwesome/css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/dashboardList.css" rel="stylesheet">

    <!-- Color Picker -->
    <!--
    <link href="//cdn.rawgit.com/twbs/bootstrap/v4.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    -->
    <script src="bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>
    <script src="bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <!-- -->
    <link href="bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css" rel="stylesheet"/>
    <link href="bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet"/>
    <!-- -->

    <!-- Tabella -->
    <!--<link rel="stylesheet" type="text/css" href="lib/datatables.css">-->
    <script type="text/javascript" charset="utf8" src="lib/datatables.js"></script>
    <script type="text/javascript" src="lib/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="lib/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="lib/jquery.dataTables.min.js"></script>
    <link href="lib/responsive.dataTables.css" rel="stylesheet"/>
    <!--
    <script src="lib/jquery-3.3.1.js"></script>
    -->
    <!-- -->
</head>
<style>
    .hidden {
        display: none;
    }

    .paginate_button {
        padding: 2px;
    }

    td {
        vertical-align: middle;
    }

    #od_table {
        margin-bottom: 0px;
    }

    #view-modal {
        width: auto;
    }

    #value_table {
        width: 100%;
    }

    #typology_table {
        text-align: center;
    }

    .fa-circle{
        font-size: 24px;
    }

    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        position: absolute;
        left: 50%;
        right: 50%;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<body class="guiPageBody">
<?php include('functionalities.php'); ?>
<div class="container-fluid">
    <div class="row mainRow" style='background-color: rgba(138, 159, 168, 1)'>
        <?php include "mainMenu.php" ?>
        <div class="col-xs-12 col-md-10" id="mainCnt">
            <div class="row hidden-md hidden-lg">
                <div id="mobHeaderClaimCnt" class="col-xs-12 hidden-md hidden-lg centerWithFlex">
                    Snap4City
                </div>
            </div>
            <div class="row" id="title_row">
                <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt">
                    <?php echo urldecode($_REQUEST['pageTitle']); ?>
                </div>
                <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt">
                    <?php
                    include "mobMainMenu.php"
                    ?>
                </div>
            </div>
            <div class="row">
                <!-- -->
                <div class="col-xs-12" id="mainContentCnt"
                     style='background-color: rgba(138, 159, 168, 1); padding-top:20px;'>
                    <table id="od_table" class="table table-striped table-bordered display responsive no-wrap"
                           style="width: 100%;">
                        <thead class="dashboardsTableHeader">
                        <?php
                        //
                        $icon_by = '<i class="fa fa-caret-down" style="color:white"></i>';
                        //
                        if ($by == 'DESC') {
                            $by_par = 'ASC';

                            $icon_by = '<i class="fa fa-caret-down" style="color:white"></i>';
                        } else {
                            $by_par = 'DESC';
                            $icon_by = '<i class="fa fa-caret-up" style="color:white"></i>';
                        }
                        $corr_page1 = $_REQUEST["page"];
                        $pagina_attuale2 = 'od.php?showFrame=' . $sf . '&page=' . $corr_page1 . '&orderBy=id&order=' . $by_par . '&limit=' . $limit . '';

                        echo('<tr>		
													<th class="od_id"><div><a>Od id</a></div></th>
													<th class="value_type"><div><a>Value type</a></div></th>
													<th class="value_unit"><div><a>Value unit</a></div></th>
													<th class="description"><div><a>Description</a></div></th>
													<th class="organization"><div><a>Organization</a></div></th>
													<th class="shape"><div><a>Shape</a></div></th>
													<th class="precision"><div><a>Precision</a></div></th>
													<th class="kind"><div><a>Kind</a></div></th>
													<th class="mode"><div><a>Mode</a></div></th>
													<th class="transport"><div><a>Transport</a></div></th>
													<th class="purpose"><div><a>Purpose</a></div></th>
													<th class="metric_name"><div><a>Color Map</a></div></th>
													<th class="other_info"><div><a>Other info</a></div></th>
												</tr>');
                        ?>
                        </thead>
                        <tbody>
                        <?php
                        for ($i = 0; $i <= $num_rows; $i++) {
                            if ($process_list[$i]['od_id']) {
                                if ($process_list[$i] != $process_list[$i - 1]) {
                                    echo("<tr>
																<td>" . $process_list[$i]['od_id'] . "</td>
																<td>" . $process_list[$i]['value_type'] . "</td>");
                                    echo("<td>" . $process_list[$i]['value_unit'] . "</td>");
                                    echo("<td>" . $process_list[$i]['description'] . "</td>");
                                    echo("<td>" . $process_list[$i]['organization'] . "</td>");
                                    echo("<td>" . $process_list[$i]['shape'] . "</td>");
                                    echo("<td>" . $process_list[$i]['precision'] . "</td>");
                                    echo("<td>" . $process_list[$i]['kind'] . "</td>");
                                    echo("<td>" . $process_list[$i]['mode'] . "</td>");
                                    echo("<td>" . $process_list[$i]['transport'] . "</td>");
                                    echo("<td>" . $process_list[$i]['purpose'] . "</td>");
                                    echo("<td><button type='button' class='viewDashBtn viewType' data-target='#typology-modal' data-toggle='modal' value='".$process_list[$i]['metric_name']."'>VIEW</button></td>");
                                    echo("<td><button type='button' class='viewDashBtn viewList' data-target='#view-modal' data-toggle='modal' value='".$process_list[$i]['od_id']."' shape='".$process_list[$i]['shape']."' precision='".$process_list[$i]['precision']."'>VIEW</button></td>");
                                    echo("</tr>");
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <!----->
                <div class="modal fade bd-example-modal-lg" id="view-modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form name="Modify Metadata" method="post" action="#" id="od_info">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: white" id="list_header">Other information: </div>
                                <div class="modal-body" style="background-color: white">
                                    <div>
                                        <table id="value_table" class="table table-striped table-bordered" style="width: 100%;">
                                            <thead class="dashboardsTableHeader">
                                            <th class="min_date">
                                                <div><a>Minimum date</a></div>
                                            </th>
                                            <th class="max_date">
                                                <div><a>Maximum date</a></div>
                                            </th>
                                            <th class="instances">
                                                <div><a>Instances</a></div>
                                            </th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div>
                                        <p id="data_content"></p>
                                    </div>
                                </div>
                                <div class="loader"></div>
                                <p id="corrent" style="display:none;"></p>
                                <div id="link_list" style="text-align: center;">
                                </div>
                                <div class="modal-footer" style="background-color: white">
                                    <button type="button" class="btn cancelBtn" id="list_close" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!----->
                <div class="modal fade bd-example-modal-lg" id="typology-modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog">
                        <form name="Modify Metadata" method="post" action="#" id="typology_Heatmap">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: white" id="typology_header">Color map </div>
                                <div class="modal-body" style="background-color: white">
                                    <div>
                                        <table id="typology_table" class="table table-striped table-bordered" style="width: 100%;">
                                            <thead class="dashboardsTableHeader">
                                            <th class="min">
                                                <div><a>Minimum</a></div>
                                            </th>
                                            <th class="max">
                                                <div><a>Maximum</a></div>
                                            </th>
                                            <th class="rgb">
                                                <div><a>Rgb</a></div>
                                            </th>
                                            <th class="color">
                                                <div><a>Color</a></div>
                                            </th>
                                            <th class="order">
                                                <div><a>Order</a></div>
                                            </th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer" style="background-color: white">
                                    <button type="button" class="btn cancelBtn" id="typology_close" data-dismiss="modal">Cancel</button>
                                    <!--<input type="submit" value="Confirm" class="btn confirmBtn"/>-->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>
    var nascondi = "<?=$hide_menu; ?>";
    var corrent_page = "<?=$page; ?>";
    var titolo_default = "<?=$default_title; ?>";
    var start_from = "<?=$start_from; ?>";
    var limit_val = $('#limit_select').val();
    var role = "<?=$role_att; ?>";

    // console.log("In script");

    $(document).ready(function () {

        var table = $('#od_table').DataTable({
            "searching": true,
            "paging": true,
            "ordering": true,
            "info": false,
            "responsive": true,
            "lengthMenu": [5, 10, 15],
            "iDisplayLength": 10,
            "pagingType": "full_numbers",
            "dom": '<"pull-left"l><"pull-right"f>tip',
            "language": {
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next >>",
                    "previous": "<< Prev"
                },
                "lengthMenu": "Show	_MENU_ ",
            }
        });

        if (nascondi == 'hide') {
            $('#mainMenuCnt').hide();
            $('#title_row').hide();
        }

        var role_active = $("#role_att").text();

        if (role_active == 'ToolAdmin') {
            $('#sc_mng').show();
        }

        var limit_default = "<?=$limit; ?>";
        $('#limit_select').val(limit_default);

        var role = "<?=$role_att; ?>";

        if (role == "") {
            $(document).empty();
            if (window.self !== window.top) {
                window.location.href = 'https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';
            } else {
                window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
            }
        }

        if (titolo_default != "") {
            $('#headerTitleCnt').text(titolo_default);
        }

        $(document).on('click', '.viewList', function() {
            var od_id = $(this).val();
            var precision;
            var table_id;
            if($(this).attr('shape') == 'communes' || $(this).attr('shape') == 'Sections' || $(this).attr('shape') == 'ACEs' || $(this).attr('shape') == 'Municipalities' || $(this).attr('shape') == 'Provinces' || $(this).attr('shape') == 'Regions' || $(this).attr('shape') == 'POIs'){
                table_id = 'od_data';
                precision = 'is null';
            }else {
                table_id = 'od_data_mgrs';
                precision = $(this).attr('precision');
            }
            $('#list_header').text('Other information on: ' + od_id);
            $.ajax({
                url: 'get_od.php',
                data: {
                    od_id: od_id,
                    precision: precision,
                    table_id: table_id,
                    action: 'get_date_info'
                },
                type: "POST",
                async: true,
                dataType: 'json',
                success: function(data) {
                    $('#value_table tbody').empty();
                    $('#link_list').empty();
                    $('#data_content').empty();
                    $('.loader').hide();
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            var min_date = data[i]['min_date'];
                            var max_date = data[i]['max_date'];
                            var count_number = data[i]['count_number'];
                            $('#value_table tbody').append('<tr><td>' + min_date + '</td><td>' + max_date + '</td><td>' + count_number + '</td></tr>');
                        }
                    }else {
                        $('#data_content').append('<div class="panel panel-default"><div class="panel-body">There are no data</div></div>');
                    }
                }
            });
        });

        $(document).on('click', '#list_close', function() {
            $('#value_table tbody').empty();
            $('#list_header').empty();
            $('#link_list').empty();
            $('.modal-backdrop').remove();
            $('.loader').show();
        });

        $(document).on('click', '.viewType', function() {
            var metric = $(this).val();
            $('#typology_header').text('Color Map:	' + metric);
            var array = new Array();
            $.ajax({
                url: 'get_heatmap.php',
                data: {
                    metric: metric,
                    action: 'color_map'
                },
                type: "POST",
                async: true,
                dataType: 'json',
                success: function(data) {
                    for (var i = 0; i < data.length; i++) {
                        var data_id = data[i]['id'];
                        var rgb = data[i]['rgb'];
                        var color = data[i]['color'];
                        var order = data[i]['order'];
                        var min = data[i]['min'];
                        var max = data[i]['max'];
                        if (min == null) {
                            min = '';
                        }
                        if (max == null) {
                            max = '';
                        }

                        rgb0 = rgb.replace("[", "(");
                        rgb = rgb0.replace("]", ")");

                        $('#typology_table tbody').append('<tr><td>' + min + '</td><td>' + max + '</td><td>' + rgb + ' <p><i class="fa fa-circle" style="color: rgb' + rgb + '"></i></p></td><td>' + color + '</td><td>' + order + '</td></tr>');
                    }
                    var table2 = $('#typology_table').DataTable({
                        "searching": false,
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "responsive": true,
                        "bDestroy": true
                    });
                }
            });
        });

        $(document).on('click', '#typology_close', function() {
            $('#typology_table tbody').empty();
        });
    });

    function removeParam(key, sourceURL) {
        var rtn = sourceURL.split("?")[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
        if (queryString !== "") {
            params_arr = queryString.split("&");
            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split("=")[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }
            rtn = rtn + "?" + params_arr.join("&");
        }
        return rtn;
    }


    $(window).on('load', function () {
        var sf = ''
        if ((window.self !== window.top) || (nascondi == 'hide')) {
            sf = 'false';
        } else {
            sf = 'true';
        }
        var originalURL = window.location.href;
        var alteredURL = removeParam("order", originalURL);
        alteredURL = removeParam("orderBy", alteredURL);
        window.location.replace = alteredURL;

    });
</script>
</body>
</html>
<?php
$link=null;
?>


