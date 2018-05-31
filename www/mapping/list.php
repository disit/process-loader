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
   
include_once("../config.php");
$table = "mappingtable";
$query = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA` = '" . $dbname . "' AND `TABLE_NAME` = '" . $table . "'";
$result = mysqli_query($connessione_al_server, $query);
$fields = array();
$th = '';
/* while ($row = mysqli_fetch_array($result)) {
  //$output .= '<option value="' . $row["type"] . '">' . $row["type"] . '</option>';
  } */
/*while ($row = $result->fetch_row()) {
    $th .= '<th data-column-id="' . $row[0] . '" data-type="numeric">' . $row[0] . '</th>';
    $fields[] = $row[0];
}*/
?>
<html>
    <head>
        <title><?php
            if (isset($_REQUEST["title"])) {
                echo $_REQUEST["title"];
            } else {
                echo "Mapping Data";
            }
            ?>
        </title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.1/jquery.bootgrid.css" />
		
        <!--<link rel="stylesheet" type="text/css" href="css/typography.css" />-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.1/jquery.bootgrid.js"></script>
		
		<!-- Font awesome icons -->
        <link rel="stylesheet" href="../fontAwesome/css/font-awesome.min.css">
		
		<!-- Custom CSS -->
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/dashboardList.css" rel="stylesheet">		
        <style>
            body {
                margin:0;
                padding:0;
                background-color:#f1f1f1;
            }
            .box {
                width:1270px;
                padding:20px;
                background-color:#fff;
                border:1px solid #ccc;
                border-radius:5px;
                margin-top:25px;
            }
        </style>
        <style>
            .cgb-header-name {
                width: 50%;
            }
        </style>
    </head>
    <body class="guiPageBody">
	<?php include ("../mainMenu.php") ?>
	<div class="col-xs-12 col-md-10" id="mainCnt">
		<div class="row hidden-md hidden-lg">
                        <div id="mobHeaderClaimCnt" class="col-xs-12 hidden-md hidden-lg centerWithFlex">
                            Snap4City
                        </div>
                    </div>
                    <div class="row" id="title_row">
                        <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt"><?php echo urldecode($_GET['pageTitle']); ?></div>
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php 
						include "mobMainMenu.php" 
						?></div>
                    </div>
			<div class="row" >
					<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:45px'>
        <div class="container box">
            <h1 align="center"><?php
                if (isset($_REQUEST["title"])) {
                    echo $_REQUEST["title"];
                } else {
                    echo "Mapping Data";
                }
                ?>
            </h1>
            <br />
            <div align="right">
                <button type="button" id="add_button" data-toggle="modal" data-target="#productModal" class="btn btn-info btn-lg">Add</button>
            </div>
            <div class="table-responsive">
                <table id="product_data" data-header-css-class="cbg-header-name" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th data-column-id="source">Source: Service Uri</th>
							<th data-column-id="source">Destination: Service Uri</th>
							<th data-column-id="source">Id</th>
                            <th data-column-id="actions" data-width="30%" data-formatter="actions" data-sortable="false">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
		</div>
		</div>
		</div>
    </body>
</html>
<script type="text/javascript" language="javascript">
    $(document).ready(function () {
        $('#add_button').click(function () {
            $('#form')[0].reset();
            $('.modal-title').text("Add Row");
            $('#action').val("Add");
            $('#operation').val("Add");
        });

        var table = $('#product_data').bootgrid({
            ajax: true,
            rowSelect: true,
            post: function () {
                return {
                    id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
                };
            },
            url: "fetch.php?fields=<?php echo urlencode(json_encode($fields)); ?>",
            formatters: {
                "actions": function (column, row) {
                    return "<button type='button' class='btn btn-warning btn-xs update' data-row-id='" + row.id + "'>Edit</button>" +
                            "&nbsp; <button type='button' class='btn btn-danger btn-xs delete' data-row-id='" + row.id + "'>Delete</button>";
                }
            }
        });

        $(document).on('submit', '#form', function (event) {
            event.preventDefault();
            var fields = "<?php echo urlencode(json_encode($fields)); ?>";
<?php
foreach ($fields as $field) {
    echo "var " . $field . "=" . "$('#" . $field . "').val();";
}
?>
            var form_data = $(this).serialize();
            $.ajax({
                url: "insert.php",
                method: "POST",
                data: form_data,
                success: function (data) {
                    //alert(data);
                    $('#form')[0].reset();
                    $('#productModal').modal('hide');
                    $('#product_data').bootgrid('reload');
                }
            });
        });

        $(document).on("loaded.rs.jquery.bootgrid", function () {
            table.find(".update").on("click", function (event) {
                var id = $(this).data("row-id");
                $.ajax({
                    url: "fetchSingle.php",
                    method: "POST",
                    data: {id: id, fields: '<?php echo urlencode(json_encode($fields)); ?>'},
                    dataType: "json",
                    success: function (data) {
                        $('#productModal').modal('show');
<?php
foreach ($fields as $field) {
    echo "$('#" . $field . "').val(data." . $field . ");";
}
?>
                        $('#id').val(id);
                        $('.modal-title').text("Edit");
                        $('#action').val("Edit");
                        $('#operation').val("Edit");
                    }
                });
            });
        });

        $(document).on("loaded.rs.jquery.bootgrid", function () {
            table.find(".delete").on("click", function (event) {
                if (confirm("Are you sure you want to delete this?")) {
                    var id = $(this).data("row-id");
                    $.ajax({
                        url: "delete.php",
                        method: "POST",
                        data: {id: id},
                        success: function (data) {
                            alert(data);
                            $('#product_data').bootgrid('reload');
                        }
                    })
                }
                else {
                    return false;
                }
            });
        });
    });
</script>
<div id="productModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add</h4>
                </div>
                <div class="modal-body">
                    <?php
                    foreach ($fields as $field) {
                        if ($field != 'id') {
                            echo '<label>' . ucfirst($field) . '</label>';
                            echo '<input type="text" name="' . $field . '" id="' . $field . '" class="form-control"/>';
                            echo '<br />';
                            //echo '}';
                        }
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id" />
                    <input type="hidden" name="operation" id="operation" />
                    <input type="hidden" name="fieldNames" id="fieldNames" value="<?php echo urlencode(json_encode($fields)); ?>"/>
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
                </div>
            </div>
        </form>
    </div>
</div>