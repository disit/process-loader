<!DOCTYPE html>
<?php
include('control.php');
include('config_healthiness.php');
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

$message ="";
if (isset ($_REQUEST['message'])){
$message=$_REQUEST['message'];
}

if (isset($_REQUEST['showFrame'])){
if ($_REQUEST['showFrame'] == 'false'){
//echo ('true');
$hide_menu= "hide";
}else{
$hide_menu= "";
}
}else{$hide_menu= "";}

if (!isset($_GET['pageTitle'])){
$default_title = "Process Loader: Device healthiness"; //
}else{
$default_title = "";
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Healthiness Devices</title>

    <!-- jQuery -->
    <script src="jquery/jquery-1.10.1.min.js"></script>

    <!-- Bootstrap toggle button -->
    <link href="bootstrapToggleButton/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="bootstrapToggleButton/js/bootstrap-toggle.min.js"></script>

    <script type="text/javascript" charset="utf8" src="lib/datatables.js"></script>


    <!--  -->
        <script type="text/javascript" charset="utf8" src="lib/datatables.js"></script>
		<script type="text/javascript" src="lib/dataTables.responsive.min.js"></script>
		<script type="text/javascript" src="lib/dataTables.bootstrap4.min.js"></script>
		<script type="text/javascript" src="lib/jquery.dataTables.min.js"></script>
		<link href="lib/responsive.dataTables.css" rel="stylesheet" />

        <!-- -->


		<!-- Bootstrap Core JavaScript -->
        <script src="bootstrap/bootstrap.min.js"></script>
			
        <!-- Bootstrap Core CSS -->
        <link href="bootstrap/bootstrap.css" rel="stylesheet">

    <!-- Custom CSS-->
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/dashboardList.css" rel="stylesheet">
<!--  -->

<!-- -->
    <style>

        .hidden{
            display: none;
        }
        th {
            color: white;
            text-align: left; /* Allinea il testo a sinistra */
            position: relative;

        }

       #main-table {
            width: auto;
            max-width: 100%;
        }

        #main-table th {
            padding-right:30px;

        }

        .order-icon {
            position: absolute;
            right: 10;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 20px;
            opacity: 0.7;
        }
        option {
            font-size: 1vw;
        }
        .max-width-cell {

            max-width: 200px; /* Imposta la larghezza massima desiderata */
            overflow: hidden; /* Nasconde il contenuto in eccesso oltre la larghezza massima */
            white-space: nowrap; /* Impedisce il testo di andare a capo */
            text-overflow: ellipsis; /* Aggiunge "..." se il contenuto supera la larghezza massima */
        }

        td {  overflow: hidden;   text-overflow: ellipsis;  word-wrap: break-word;}

        .col-md-10{
            max-width: 98%;
        }
        .max-width-cell:hover {
            overflow: visible;
            white-space:normal;
        }
        #pagination {
            text-align: left;
            margin: 20px;
        }
        #pageNumbers {
            display: inline;
        }
        a {
            margin: 0 5px;
        }
        .selected {
            color: darkblue; /* Colore del testo blu scuro */
            text-decoration: underline; /* Cambia il colore del testo in bianco */
        }

    </style>

</head>


<body class="guiPageBody" >
<?php include('functionalities.php'); ?>
<div class="container-fluid" >
    <div class="row mainRow" style="background-color: rgba(138, 159, 168, 1);">
    <?php include "mainMenu.php" ?>
        <div class="col-xs-12 col-md-10" id="mainCnt">

            <div class="row hidden-md hidden-lg">
                <div id="mobHeaderClaimCnt" class="col-xs-12 hidden-md hidden-lg centerWithFlex" style="">
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


        <div class="row" >
        <div class="col-xs-12" id="mainContentCnt"
                     style='background-color: rgba(138, 159, 168, 1); padding-top:20px;'>

        <div style="margin-bottom: 20px;">

                <button id="refresh" class="btn btn-warning">Refresh</button>
            <div id="pagination" >
                <button class="btn btn-primary" id="first">First</button>
                <button class="btn btn-primary" id="prev">&lt;<</button>
                <div id="pageNumbers">

                </div>
                <button class="btn btn-primary" id="next">&gt;></button>
                <button class="btn btn-primary" id="last">Last</button>
            </div>

            <div >
                <input id="device-tick" type="checkbox" >
                <label>View for devices</label>
            </div>
            <!--
            <button id = "prev" class="btn btn-warning">Previous Records</button>
            <button id = "next" class="btn btn-warning">Next Records</button>
            <button id = "first" class="btn btn-warning">First Page</button>
            <button id = "last" style="margin-right: 30px;" class="btn btn-warning">Last Page</button>
            -->
            <label style="color: #333333; padding-left: 20px;">
                <span>Filter devices for</span>
                <select id= "filter-col" style = "font-size: 13px; height: 21px; margin-right: 10px;margin-left: 5px; width: 120px; padding-left:2px;">
                    <option value="FailuresNumber"> Failures </option>
                    <option value="Delta"> Minutes since expected date</option>
                    <option value="MaxDelta"> Max minutes of notHealthy</option>
                    <option value="Percentage">Percentage of increment</option>
                </select>

                <select id="filter-type" style = "font-size: 13px; height: 21px; margin-right: 40px;width: 120px; padding-left:2px;">
                    <option value="under">Below</option>
                    <option value="over">Above</option>
                </select>

                <span>Define a threshold:</span>
                <input  type="search" id="threshold-value" style="height: 25px;" placeholder="Insert value" >
                <button id="filter" class="btn btn-primary">Filter</button>

            </label>
        </div>

        <div class="pull-right" style="margin-bottom: 5px;">
            <div style="padding-right: 20px;">
                <label style="color: #333333">Select a column:
                    <select id="columnSelect" style = "font-size: 13px; height: 21px; margin-right: 10px;width: 120px; padding-left:2px;">
                        <option value="ServiceURI">Service URI</option>
                        <option value="Organization">Organization</option>
                        <option value="Broker">Broker</option>
                        <option value="DeviceName">Device Name</option>
                        <option value="DeviceModel">Device Model</option>
                        <option value="Nature">Nature</option>
                        <option value="Subnature">Subnature</option>
                        <option value="VariableName">Variable Name</option>
                    </select>
                    Search for:
                    <input type="search" id="search-value" style="height: 25px;" placeholder="Insert value" >
                    <button id="search" class="btn btn-primary">Search</button>
                </label>
            </div>
        </div>

        <div class="pull-left" >
            <div style="padding-left: 10px;">
                <label>Show
                    <select id = "lengthSelect" >
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                    </select>
                </label>
            </div>
        </div>

        <table id = "main-table" class="table table-striped table-bordered display responsive "  style="width: 100%;">

            <thead class="dashboardsTableHeader">
            <tr role="row">
                <th tabindex="0"  rowspan="1" colspan="1"  >  Service URI <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1"  >  Organization <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Broker <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Device Name <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Device Model <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Nature <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Subnature <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Variable Name <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Variable Value <span class="order-icon"></th>
                <th  tabindex="0"  rowspan="1" colspan="1">Expected Date <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Last Date <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Failures <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Last notHealthy <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Minutes since expected date <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Max minutes of notHealthy <span class="order-icon"></th>
                <th tabindex="0"  rowspan="1" colspan="1" >Percentage of increment <span class="order-icon"></th>
                <script>

                </script>
            </tr>
            </thead>
            <tbody id="myTable" class="odd">
            </tbody>
        </table>
        <br>

        <br>
        <div>

            <p id = "descr"></p>
            <p id = "count"></p>

        </div>
    </div>


</div>

</div>
</div>


<script type='text/javascript' >
    var action;
    var check_user= false;
    var count = 0;
    var nothealthy_count = 0;
    var table;
    var loadedRecords = 0;
    var offset = 0;
    var pageSize = 10;
    var searching = false;
    var ordering = false;
    var orderDirection;
    var filtering = false;
    var filterColumn;
    var filterType;
    var threshold;
    var columnIndex;
    var selectedColumn;
    var searchValue;
    var searchError = false;
    var colClick = 0;
    var buttonsToShow = 10;
    var currentPage;
    var deviceView = false;
    //$('#mainContentCnt').hide();
    $(document).ready(function () {
        $.ajax({
            url: "gethealthiness.php",
            type: "GET",
            data: {action:'auth'},
            async: true,
            //dataType: "text",
            success: function(data){
                //console.log(data)
                if (data == 'Allowed'){
                    check_user=true;
                    $('#mainContentCnt').show();
                    preparePage(0,pageSize);
                }
                else{
                    check_user=false;
                    var divElement = document.getElementById("mainContentCnt");
                    divElement.innerHTML = "";
                    //console.log("check user"+ check_user)

                    divElement.appendChild(document.createTextNode("You are not allowed"));
                    $('#mainContentCnt').show();
                }

            },
            error: function(xhr, status, error) {
                //table.clear().draw();
                console.log('Request error: ' + error + xhr);
            }
        });

        var nascondi= "<?=$hide_menu; ?>";
        if (nascondi == 'hide'){
            $('#mainMenuCnt').hide();
            $('#title_row').hide();
            $('.col-md-10').css('width', '98%');
        }

        //
        //controllo login
        utente_attivo=$("#nome_ut").text();

        if (utente_attivo=='Login'){
            $(document).empty();
            alert("You have to log in!");
        }
        //
        var role_active = $("#role_att").text();
        if (role_active == 'ToolAdmin'){
            $('#sc_mng').show();
        }
        //

        //redirect//
        var role="<?=$role_att; ?>";

        if (role == ""){
            $(document).empty();
            //window.alert("You need to log in to access to this page!");
            if(window.self !== window.top){
                //window.location.href = 'page.php?showFrame=false&pageTitle=Process%20Loader:%20View%20Resources';
                window.location.href='https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';
            }
            else{
                window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
            }
        }

        var role_active = "<?=$process['functionalities'][$role]; ?>";

        //console.log(role_active);
        if (role_active == 0){
            $(document).empty();
            if(window.self !== window.top){
                //window.location.href = 'page.php?showFrame=false&pageTitle=Process%20Loader:%20View%20Resources';
                window.location.href='https://www.snap4city.org/auth/realms/master/protocol/openid-connect/auth?response_type=code&redirect_uri=https%3A%2F%2Fmain.snap4city.org%2Fmanagement%2FssoLogin.php%3Fredirect%3DiframeApp.php%253FlinkUrl%253Dhttps%253A%252F%252Fwww.snap4city.org%252Fdrupal%252Fopenid-connect%252Flogin%2526linkId%253Dsnap4cityPortalLink%2526pageTitle%253Dwww.snap4city.org%2526fromSubmenu%253Dfalse&client_id=php-dashboard-builder&nonce=be3aea0a2bb852217929cbb639370c9e&state=d090eb830f9abb504fd7012f6a12389c&scope=openid+username+profile';
            }
            else{
                window.location.href = 'page.php?pageTitle=Process%20Loader:%20View%20Resources';
            }
        }
        //
        ////
        var titolo_default = "<?=$default_title; ?>";
        if (titolo_default != ""){
            $('#headerTitleCnt').text(titolo_default);
        }

        // Setup DataTable
        table = $('#main-table').DataTable({
            "searching": false,
            "paging": false,
            "select":false,
            "ordering": false,
            "sort":false,
            "autoWidth": false,
            "info": false,
            "responsive": true,
            /*"columnDefs": [

                {"targets": "_all", "orderable": false},
                { responsivePriority: 10, targets: 0 }, //uri
                { responsivePriority: 2, targets: 1 },// organization
                { responsivePriority: 2, targets: 2 },// broker
                { responsivePriority: 1, targets: 3 },// name
                { responsivePriority: 2, targets: 4 },// model
                { responsivePriority: 10, targets: 5 },// nature
                { responsivePriority: 10, targets: 6 },// subnature
                { responsivePriority: 1, targets: 7 },// variable
                { responsivePriority: 1, targets: 8 },// value
                { responsivePriority: 1, targets: 9 },// expected
                { responsivePriority: 1, targets: 10 },// lase
                { responsivePriority: 1, targets: -5 }, // failures
                { responsivePriority: 1, targets: -4 },// last update
                { responsivePriority: 1, targets: -3 },// delta
                { responsivePriority: 1, targets: -2 },// maxdelta
                { responsivePriority: 1, targets: -1 },// percentage


            ],*/
            "lengthMenu": [10,15,20,30],
            "pageLength": 10,
            "pagingType": "simple",
            "language":{"paginate": {
                    "first":      "",
                    "last":       "",
                    "next":       "",
                    "previous":   ""
                },
                "lengthMenu":     "Show	_MENU_ ",

            }
        });

        // Recall the function to setup the page
        

        // When click refresh return to default settings
        $('#refresh').on('click', function() {
            searching = false;
            ordering = false;
            filtering = false;
            removeOrder()
            offset = 0;
            pageSize =table.page.len();
            preparePage(0,pageSize);
            searchError = false;
            document.getElementById("threshold-value").value = "";
            document.getElementById("search-value").value = "";

        });

        // Change the number of records shown with respect of the choosen length
        $("#lengthSelect").change(function (){
            console.log(offset)
            var length = $("#lengthSelect").val();
            if(searchError === false) {
                table.page.len(length).draw();
                pageSize = length;
                UniversalUpdate(offset,table.page.len(), columnIndex);
                updateDesc(offset);
            }

        });

        // Go to the last page of results
        $("#last").click(function (){

            if (searchError === false) {
                offset = Math.floor(count/table.page.len())*table.page.len();

                UniversalUpdate(offset,table.page.len(), columnIndex);
                updateDesc(offset);
                currentPage=Math.ceil(count/table.page.len());
                createPageButtons(currentPage,count,buttonsToShow);

                /*
                offset = Math.floor(count/table.page.len())*table.page.len();
                //offset = count - table.page.len();
                UniversalUpdate(offset,table.page.len(), columnIndex);
                updateDesc(offset);

                 */
            }

        });

        $("#device-tick").click(function (){

            deviceView = !deviceView;
            offset=0;
            ordering=false;
            removeOrder()
            UniversalUpdate(offset, table.page.len()).then(function (data) {
                currentPage = 1;
                createPageButtons(currentPage, Math.ceil(count / pageSize), 10);

            }).catch(function (error) {
                    console.error("Errore durante UniversalUpdate:", error);
            });

        });

        // Go to the first page of results
        $("#first").click(function (){

            if (searchError === false) {
                offset = 0;

                UniversalUpdate(offset,table.page.len(), columnIndex);
                updateDesc(offset);

                currentPage=1;
                createPageButtons(currentPage,count,buttonsToShow);
                /*
                offset = 0;
                UniversalUpdate(offset,table.page.len(), columnIndex);
                updateDesc(offset);
                */
            }

        });

        // Go to the next page of results
        $("#next").click(function() {

            if (searchError === false) {
                if (offset + table.page.len() < count) {
                    offset += table.page.len();
                    currentPage += 1;

                    $("a.selected").removeClass("selected");
                    $("a[data-page='" + currentPage + "']").addClass("selected");

                    createPageButtons(currentPage,count,buttonsToShow);
                    UniversalUpdate(offset,table.page.len(), columnIndex);
                    updateDesc(offset);
                    /*
                    offset += table.page.len();
                    UniversalUpdate(offset,table.page.len(), columnIndex);
                    updateDesc(offset);
                     */
                }
            }
        });

        // Go to the previous pafe of results
        $("#prev").click(function() {
            if (searchError === false) {
                if (offset >= table.page.len()) {
                    offset -= table.page.len();
                    var nextPage = currentPage - 1;
                } else if (offset !== 0) {
                    offset = 0;
                    var nextPage = 1;
                } else {
                    return;
                }

                currentPage--;

                $("a.selected").removeClass("selected");
                $("a[data-page='" + nextPage + "']").addClass("selected");

                createPageButtons(currentPage,count,buttonsToShow);
                UniversalUpdate(offset,table.page.len(), columnIndex);
                updateDesc(offset);
                /*
                UniversalUpdate(offset,table.page.len(), columnIndex);
                updateDesc(offset);
                */
            }
        });

        // Search for a term in the database
        $('#search').on('click', function() {
            offset = 0;
            searching = true;
            //UniversalUpdate(0,table.page.len());
            UniversalUpdate(offset, table.page.len()).then(function (data) {
                currentPage = 1;
                createPageButtons(1, Math.ceil(count / pageSize), 10);
            }).catch(function (error) {
                    console.error("Errore durante UniversalUpdate:", error);
            });

        });

        // Filter the records with respect to some threshold
        $('#filter').on('click', function() {
            offset = 0;
            filtering = true;
            //UniversalUpdate(0,table.page.len(), columnIndex);
            UniversalUpdate(offset, table.page.len()).then(function (data) {
                currentPage = 1;
                createPageButtons(1, Math.ceil(count / pageSize), 10);
            }).catch(function (error) {
                    console.error("Errore durante UniversalUpdate:", error);
            });

        });

        // Resize the table when the window change size
        $(window).on('resize', function() {
           // table.responsive.recalc();
           table.columns.adjust().draw();
        });

        // Order the record with respect to the choosen column
        table.on('click', 'th', function() {
            ordering = true;
            colClick = 1- colClick;
            offset = 0;

            currentPage=1;
            createPageButtons(currentPage,count,buttonsToShow);

            columnIndex = $(this).index();
            UniversalUpdate(offset,pageSize, columnIndex);

            var icon = colClick === 1 ? '▲' : '▼';
            $(this).find('.order-icon').text(icon);
            $('th').not(this).find('.order-icon').text('');

        });

        // Reset the search if the user clears the input form
        const searchInput = document.getElementById("search-value");
        searchInput.addEventListener("input", function() {
            if (searchInput.value === "") {
                searching = false;

            }
        });

        const filterInput = document.getElementById("threshold-value");
        filterInput.addEventListener("input", function() {
            if (filterInput.value === "") {
                filtering = false;

            }
        });


    });
    function removeOrder(){
        const orderIcons = document.querySelectorAll('.order-icon');
        for (let i = 0; i < orderIcons.length; i++) {
            orderIcons[i].textContent = '';
        }
    }

    // Function that allows to filter, search and order the records
    function UniversalUpdate(offs, len, col = 0){
        return new Promise(function (resolve, reject) {
        //console.log(check_user)
        // Identify the use case
        if(searching === true && ordering === true && filtering === true){
            console.log("filter,search and order")
            // Searching params
            selectedColumn = $('#columnSelect').val();
            searchValue = $('#search-value').val();

            // Filtering params
            filterColumn = $('#filter-col').val();
            filterType = $('#filter-type').val();
            threshold = $('#threshold-value').val();

            // Ordering params
            if (colClick === 0){
                orderDirection = 'desc';
            }
            else{
                orderDirection = 'asc';
            }

            inputs = { device: deviceView, search: searching, order: ordering, filter:filtering,
                offset: offs, limit: len, column:selectedColumn, value: searchValue,
                filtercol: filterColumn, type: filterType , threshold: threshold,
                clickedColumn: col, direction: orderDirection}

        }
        else if(searching === true && ordering === true){
            console.log("search and order")
            // Searching params
            selectedColumn = $('#columnSelect').val();
            searchValue = $('#search-value').val();

            // Ordering params
            if (colClick === 0){
                orderDirection = 'desc';
            }
            else{
                orderDirection = 'asc';
            }

            inputs = {device: deviceView, search: searching, order: ordering, filter:filtering,
                offset: offs, limit: len, column:selectedColumn, value: searchValue,
                clickedColumn: col, direction: orderDirection }

        }
        else if(ordering === true && filtering === true){
            console.log("filter and order")
            // Filtering params
            filterColumn = $('#filter-col').val();
            filterType = $('#filter-type').val();
            threshold = $('#threshold-value').val();
            // Ordering params
            if (colClick === 0){
                orderDirection = 'desc';
            }
            else{
                orderDirection = 'asc';
            }

            inputs = {device: deviceView, search: searching, order: ordering, filter:filtering,
                filtercol: filterColumn, type: filterType , threshold: threshold,
                clickedColumn: col, direction: orderDirection, offset: offs, limit: len }

        }
        else if(searching === true && filtering === true){
            console.log("filter and search")
            // Searching params
            selectedColumn = $('#columnSelect').val();
            searchValue = $('#search-value').val();

            // Filtering params
            filterColumn = $('#filter-col').val();
            filterType = $('#filter-type').val();
            threshold = $('#threshold-value').val();

            inputs = {device: deviceView, search: searching, order: ordering, filter:filtering,
                offset: offs, limit: len, column:selectedColumn, value: searchValue,
                filtercol: filterColumn, type: filterType , threshold: threshold}
        }
        else if(filtering === true){
            console.log("just filter")
            // Filtering params
            filterColumn = $('#filter-col').val();
            filterType = $('#filter-type').val();
            threshold = $('#threshold-value').val();
            action= "filter";
            inputs = {device: deviceView, search: searching, order: ordering, filter:filtering,
                filtercol: filterColumn, type: filterType , threshold: threshold, offset: offs, limit: len}

        }
        else if(ordering === true){
            console.log("just order")
            // Ordering params
            if (colClick === 0){
                orderDirection = 'desc';
            }
            else{
                orderDirection = 'asc';
            }
            action= "order";
            inputs = {device: deviceView, search: searching, order: ordering, filter:filtering,
                clickedColumn: col, direction: orderDirection ,offset: offs, limit: len }

        }
        else if (searching === true){
            console.log("just search")
            // Searching params
            searchError = false;
            selectedColumn = $('#columnSelect').val();
            searchValue = $('#search-value').val();
            action ="search";

            inputs = {device: deviceView, search: searching, order: ordering, filter:filtering,
                offset: offs, limit: len, column:selectedColumn, value: searchValue }
        }
        else{
            console.log("simple")
            inputs = {device: deviceView, search: searching, order: ordering, filter:filtering, offset: offs, limit: len }//search: searching, order: ordering, filter:filtering,

        }
        // ajax call to gethealthiness.php
        $.ajax({
            url: "gethealthiness.php",
            type: "GET",
            data: inputs,
            dataType: "json",
            success: function(data) {

                updateTable(data);
                updateDesc(offs);
                resolve(data);
                table.columns.adjust().draw();

            },
            error: function(xhr, status, error) {
                table.clear().draw();
                console.log('Request error: ' + error);
                if(action == "search"){
                    searchError = true;
                }
                updateDesc(offs);
                reject(error);
            }
        });

        $('#main-table').removeClass('hidden');
        //table.responsive.recalc();
        table.columns.adjust().draw();
    });
    }


    // Function to update the rows of the shown table
    function updateTable(data){

        if (data && data.length > 0) {
            var tableBody = $('#main-table tbody');
            table.rows().remove().draw();
            $.each(data, function(_, record) {

                var newRow = $('<tr>');

                newRow.append('<td >' + record.record.uri + '</td>');
                newRow.append('<td >' + record.record.organization + '</td>');
                newRow.append('<td >' + record.record.broker + '</td>');
                newRow.append('<td >' + record.record.name + '</td>');
                newRow.append('<td >' + record.record.model + '</td>');
                newRow.append('<td >' + record.record.nature + '</td>');
                newRow.append('<td >' + record.record.subnature + '</td>');
                if(deviceView === false){
                    newRow.append('<td >' + record.record.variable + '</td>');
                    newRow.append('<td  >' + record.record.value + '</td>');
                }
                else{
                    newRow.append('<td >' + '</td>');
                    newRow.append('<td  >'  + '</td>');
                }
                newRow.append('<td >' + record.record.exp_date + '</td>');
                newRow.append('<td >' + record.record.last_date + '</td>');
                newRow.append('<td >' + record.record.failures + '</td>');

                var date = new Date(record.record.timestamp * 1000);
                var formattedDate = date.getUTCFullYear() + '-' +
                    (date.getMonth() + 1).toString().padStart(2, '0') +
                    '-' + date.getUTCDate().toString().padStart(2, '0') +
                    ' ' + date.getUTCHours().toString().padStart(2, '0') +
                    ':' + date.getUTCMinutes().toString().padStart(2, '0') +
                    ':' + date.getUTCSeconds().toString().padStart(2, '0');

                newRow.append('<td >' + formattedDate + '</td>');
                newRow.append('<td  >' + record.record.delta + '</td>');
                newRow.append('<td >' + record.record.max_delta + '</td>');
                newRow.append('<td >' + record.record.percentage + '</td>');

                newRow.find('td').addClass('max-width-cell');
                // For the not updated records a grey background is shown
                if (record.record.failures == 0){
                    newRow.css({"background":"lightgrey"});
                }

                table.row.add(newRow).draw();

            });

            count = data[0].record.count ;
            nothealthy_count = data[0].record.nothealthy;

        }
        else { console.log('No results found'); }

        //table.responsive.recalc();
        table.columns.adjust().draw();
    }

    // Function to update the description of the page
    function updateDesc(offset) {
        var numPages = Math.ceil(count/pageSize);
        var pageCount = Math.ceil(offset/pageSize);
        var newOffset = parseInt(offset) + parseInt(pageSize);
        currentPage = pageCount +1 ;
        if(deviceView == true){
            var string = "Entities";
        }
        else{
            var string="Variables";
        }
        if (searchError === false) {
            if (newOffset > count) {
                $("#descr").text(string+ "  from " + (offset+1) + " to " + count);
            } else {
                $("#descr").text(string+ "  from " + (offset+1) + " to " + newOffset);
            }

            $("#count").text(string+ "  from the storage: " + count +
                " ,   page " + (pageCount + 1) + " of " + numPages);
        }
        else{
            $("#descr").text("No "+ string+ "  retrieved");
            $("#count").text('');
        }
        $("#count").append('<br> Number of not healthy entities: ').append(nothealthy_count);
    }


    // Function to setup the page the first time is rendered
    function preparePage(offset, size){
        $("#myTable").empty();
        //UniversalUpdate(offset,table.page.len());
        UniversalUpdate(offset, size).then(function (data) {
            currentPage = 1;
            createPageButtons(1, Math.ceil(count / pageSize), 10);
        }).catch(function (error) {
            console.error("Errore durante UniversalUpdate:", error);
        });
    }

    function createPageButtons(currentPage,pageNum,buttonsToShow) {
        var pageNumbers = document.getElementById("pageNumbers");
        pageNumbers.innerHTML = "";

        var start_button = Math.max(1, currentPage - Math.floor(buttonsToShow / 2));
        var end_button = Math.min(Math.ceil(count/pageSize), start_button + buttonsToShow - 1);

        for (var i = start_button; i <= end_button; i++) {(function (pageNumber) {

            var link = document.createElement("a");
            link.textContent = pageNumber;
            link.setAttribute("data-page", pageNumber); //
            link.href = "#";

            link.addEventListener("click", function (event) {

                event.preventDefault(); // Impedisce il comportamento predefinito del link
                currentPage = pageNumber;

                // Rimuovi la classe "selected" da tutti i link
                var links = pageNumbers.getElementsByTagName("a");
                for (var j = 0; j < links.length; j++) {
                    links[j].classList.remove("selected");
                }
                currentPage = parseInt(this.textContent);
                link.classList.add("selected");

                offset= (currentPage-1)*table.page.len()

                createPageButtons(currentPage, pageNum, buttonsToShow);
                UniversalUpdate((currentPage-1)*table.page.len(),table.page.len(), columnIndex);
                updateDesc((currentPage-1)*table.page.len());

            });

            pageNumbers.appendChild(link);

        })(i);
        }

        var currentLink = pageNumbers.querySelector("a.selected");
        if (currentLink) {
            currentLink.classList.remove("selected");
        }
        var newCurrentLink = pageNumbers.querySelector("a[data-page='" + currentPage + "']");
        if (newCurrentLink) {
            newCurrentLink.classList.add("selected");
        }

    }


</script>
</body>
</html>

