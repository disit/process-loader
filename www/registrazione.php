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

include("config.php"); // includere la connessione al database
include('control.php');
//include('functionalities.php');
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
	$default_title = "Process Loader: Local Users Register";
}else{
	$default_title = "";
}

if (isset($_REQUEST['result'])){
	if ($_REQUEST['result'] == 'OK'){
		$result=$_REQUEST['result'];
	}else{
		$result="";
	}
}else{
	$result="";	
}
 ?>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Process Loader</title>
		
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
        
    </head>
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
                        <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt"><?php echo urldecode($_GET['pageTitle']); ?></div>
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php include "mobMainMenu.php" ?></div>
                    </div>
			<div class="row" >
			<div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1); margin-top:45px'>
						    <div class="col-sm-8 text-left">
		<!--<h2>New User Registration<span class="fa fa-info-circle" data-toggle="modal" data-target="#info-modal"  style="color:#337ab7;font-size:24px;"></h2>-->
		<div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-0" id="loginRightCol"> 
		
</div>
<!-- Button-->
<div class="col-xs-12 col-md-6 col-md-offset-3" id="loginFormContainer">
						<form enctype="multipart/form-data" method="post" action="insertUser.php" id="insertUser">
                            <div class="col-xs-12" id="loginFormTitle" class="centerWithFlex">
                               New User
                            </div>
												<div class="form-group">
<label class="control-label">User Name *</label>
<input class="form-control"type="text" name="username_reg" required="required">
    </div>

    <div class="form-group">
<label class="control-label">Password *</label>
<input class="form-control"type="password" name="password_reg" required="required">
    </div>
        <div class="form-group">
<label class="control-label">Repeat Password *</label>
<input class="form-control"type="password" name="password_reg2" required="required">
    </div>
	        <div class="form-group">
<label class="control-label">User e-mail *</label>
<input class="form-control"type="mail" name="mail" required="required">
    </div>

	<div class="form-group">
<label class="control-label">Role User *</label>
 <select class="form-control" name="role_reg">
   <option value="RootAdmin">RootAdmin</option>
  <option value="ToolAdmin">ToolAdmin</option>
  <option value="AreaManager">AreaManager</option>
  <option value="Manager">Manager</option>
</select> 
    </div>
			        <div class="form-group">
<label class="control-label">User notes</label>
<input class="form-control"type="text" name="notes" >
    </div>
                            <div class="col-xs-12 centerWithFlex" id="loginFormFooter">
                               <!--<button type="reset" id="loginCancelBtn_reset" class="btn cancelBtn" data-dismiss="modal">Reset</button>-->
                               <button type="submit" id="loginConfirmBtn_confirm" name="login" class="btn confirmBtn internalLink">Confirm</button>
                            </div>
                        </form>    
                    </div>

     
    </div>
			</div>
			</div>
		</div>

<script type='text/javascript'>

$(document).ready(function () {
	
	var role="<?=$role_att; ?>";
	var result="<?=$result; ?>";
	
	if (result == "OK"){
		alert("New Local user created");
	}
	
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
	
	//Copiare e incollare la parte js
var nascondi= "<?=$hide_menu; ?>";
		if (nascondi == 'hide'){
			$('#mainMenuCnt').hide();
			$('#title_row').hide();
			$('#insertUser').attr('action','insertUser.php?showFrame=false');
		}
		
var titolo_default = "<?=$default_title; ?>";
				if (titolo_default != ""){
					$('#headerTitleCnt').text(titolo_default);
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
				
});
</script>
</body>
</html>