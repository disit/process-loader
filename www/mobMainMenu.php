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
 ?>

<i id="mobMainMenuBtn" data-shown="false" class="fa fa-navicon"></i>

<div id="mobMainMenuCnt">
    <div id="mobMainMenuPortraitCnt">
        <div class="row">
            <div class="col-xs-12 centerWithFlex" id="mobMainMenuIconCnt">
                <img src="img/mainMenuIcons/user.ico" />
            </div>
            <div class="col-xs-12 centerWithFlex" id="mobMainMenuUsrCnt">
                <?php /*echo $_SESSION['loggedUsername'];*/ ?>
				<span aria-hidden="true" data-toggle="modal" data-target="#login-modal" id="utente_att"><?=$utente_att;?></span>
            </div>
            <div class="col-xs-12 centerWithFlex" id="mobMainMenuUsrDetCnt">
                <?php /*echo $_SESSION['loggedRole'] . " | " . $_SESSION['loggedType'];*/ ?>
				<span aria-hidden="true" data-toggle="modal" data-target="#" id="role_att"><?=$role_att;?></span>
            </div>
            <div class="col-xs-12 centerWithFlex" id="mobMainMenuUsrLogoutCnt">
                <button type="button" id="mobMainMenuUsrLogoutBtn" class="editDashBtn">logout</button>
            </div>
        </div>
        <hr>
        <?php
        /*if(isset($_SESSION['loggedRole'])&&isset($_SESSION['loggedType']))
        {
            if($_SESSION['loggedRole'] == "ToolAdmin")
            {*/
        ?>
				<a class="internalLink moduleLink" href="page.php" id="viewProcessesLink">
                        <div class="col-xs-12 mobMainMenuItemCnt">
                            <i class="fa fa-eye"></i>&nbsp;&nbsp;&nbsp;View Resources
                        </div>
                    </a>
                <a href="Process_loader.php" id="processloaderLink" class="internalLink moduleLink" hidden>
                    <div class="col-xs-12 mobMainMenuItemCnt">
                        <i class="fa fa-gear"></i>&nbsp;&nbsp;&nbsp;Processes in Execution
                    </div>
                </a>
        <?php        
                /*}
            }*/
        ?>
<!--		
        <a href="upload.php" id="UploadLink" class="internalLink moduleLink" hidden>
            <div class="col-xs-12 mobMainMenuItemCnt">
                <i class="fa fa-upload"></i>&nbsp;&nbsp;&nbsp;Upload new file
            </div>
        </a>
-->
        <?php
            /*if(isset($_SESSION['loggedRole'])&&isset($_SESSION['loggedType']))
            {
                if($_SESSION['loggedType'] == "local")
                {*/
        ?>
                    <a class="internalLink moduleLink" href="job_type.php" id="processTypeLink" hidden>
                        <div class="col-xs-12 mobMainMenuItemCnt">
                            <i class="fa fa-edit"></i>&nbsp;&nbsp;&nbsp;Process Models
                        </div>
                    </a>
        <?php        
                /*}*/
        ?>  

        <?php
                /*if($_SESSION['loggedRole'] == "ToolAdmin")
                {*/
        ?>
                    <a class="internalLink moduleLink" href="file_archive.php" id="uploadFileListLink" hidden>    
                        <div class="col-xs-12 mobMainMenuItemCnt">
                            <i class="fa fa-archive"></i>&nbsp;&nbsp;&nbsp;Uploaded files list
                        </div>
                    </a>
                    <a class="internalLink moduleLink" href="archive.php" id="archiveLink" hidden>
                        <div class="col-xs-12 mobMainMenuItemCnt">
                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;Activity Archive
                        </div>
                    </a>
                    
                    <a class="internalLink moduleLink" href="schedulers_mng.php" id="nodesLink" hidden>
                        <div class="col-xs-12 mobMainMenuItemCnt">
                            <i class="fa fa-database"></i>&nbsp;&nbsp;&nbsp;Scheduler nodes management
                        </div>
                    </a> 
        <?php        
                /*}*/
        ?>

        <?php
                /*if(($_SESSION['loggedRole'] == "ToolAdmin") || ($_SESSION['loggedRole'] == "AreaManager"))
                {*/
        ?>
                <a href="upload_process_production.php" class="internalLink moduleLink" hidden>
                    <div class="col-xs-12 mobMainMenuItemCnt">
                        <i class="fa fa-upload"></i>&nbsp;&nbsp;&nbsp;Upload test in production
                    </div>
                </a>
        <?php        
                /*}*/
        ?>

        <?php        
            /*}*/
        ?>    

        <a href="transfer_file_property.php" id="modifyPropertyLink" class="internalLink moduleLink" hidden>
            <div class="col-xs-12 mobMainMenuItemCnt">
                <i class="fa fa-gears"></i>&nbsp;&nbsp;&nbsp;Modify process property
            </div>
        </a>
		
		<a href="registrazione.php" id="registerLink" class="internalLink moduleLink" hidden>
        <div class="col-xs-12 mobMainMenuItemCnt">
            <i class="fa fa-user-plus"></i>&nbsp;&nbsp;&nbsp;User Register
			</div>
    </a>
    </div>
    
    <div id="mobMainMenuLandCnt">
        <div class="row">
            <div class="col-xs-4 centerWithFlex" id="mobMainMenuUsrCnt">
                <img src="img/mainMenuIcons/user.ico" />&nbsp;&nbsp;<?php /*echo $_SESSION['loggedUsername'];*/ ?>
				<span aria-hidden="true" data-toggle="modal" data-target="#login-modal" id="utente_att"><?=$utente_att;?></span>
            </div>
            <div class="col-xs-4 centerWithFlex" id="mobMainMenuUsrDetCnt">
                <?php /*echo $_SESSION['loggedRole'] . " | " . $_SESSION['loggedType']; */?>
				<span aria-hidden="true" data-toggle="modal" data-target="#" id="role_att"><?=$role_att;?></span>
            </div>
            <div class="col-xs-4 centerWithFlex" id="mobMainMenuUsrLogoutCnt">
                <button type="button" id="mobMainMenuUsrLogoutBtn" class="editDashBtn">logout</button>
            </div>
        </div>
       
        <a href="Process_loader.php" id="setupLink" class="internalLink moduleLink" hidden>
            <div class="col-xs-4 mobMainMenuItemCnt">
                <i class="fa fa-gear"></i>&nbsp;&nbsp;&nbsp;Process Execution
            </div>
        </a>
        <a href="upload.php" id="UploadLink" class="internalLink moduleLink" hidden>
            <div class="col-xs-4 mobMainMenuItemCnt">
                <i class="fa fa-upload"></i>&nbsp;&nbsp;&nbsp;Upload new file
            </div>
        </a>

        <?php
            /*if(isset($_SESSION['loggedRole'])&&isset($_SESSION['loggedType']))
            {
                if($_SESSION['loggedType'] == "local")
                {*/
        ?>
                    <a class="internalLink moduleLink" href="job_type.php" id="processTypeLink" hidden>
                        <div class="col-xs-4 mobMainMenuItemCnt">
                            <i class="fa fa-edit"></i>&nbsp;&nbsp;&nbsp;Process Types list
                        </div>
                    </a>
        <?php        
                /*}*/
        ?>  

        <?php
                /*if($_SESSION['loggedRole'] == "ToolAdmin")
                {*/
        ?>
                    <a class="internalLink moduleLink" href="file_archive.php" id="uploadFileListLink" hidden>     
                        <div class="col-xs-4 mobMainMenuItemCnt">
                            <i class="fa fa-archive"></i>&nbsp;&nbsp;&nbsp;Uploaded files list
                        </div>
                    </a>
                    <a class="internalLink moduleLink" href="archive.php" id="archiveLink" hidden>
                        <div class="col-xs-4 mobMainMenuItemCnt">
                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;Activity Archive
                        </div>
                    </a>
                    <a class="internalLink moduleLink" href="page.php" id="viewProcessesLink">
                        <div class="col-xs-4 mobMainMenuItemCnt">
                            <i class="fa fa-eye"></i>&nbsp;&nbsp;&nbsp;View public processes
                        </div>
                    </a>
                    <a class="internalLink moduleLink" href="schedulers_mng.php" id="nodesLink" hidden>
                        <div class="col-xs-4 mobMainMenuItemCnt">
                            <i class="fa fa-database"></i>&nbsp;&nbsp;&nbsp;Scheduler nodes
                        </div>
                    </a> 
        <?php        
                /*}*/
        ?>

        <?php
                /*if(($_SESSION['loggedRole'] == "ToolAdmin") || ($_SESSION['loggedRole'] == "AreaManager"))
                {*/
        ?>
                <a href="upload_process_production.php" class="internalLink moduleLink" hidden>
                    <div class="col-xs-4 mobMainMenuItemCnt">
                        <i class="fa fa-upload"></i>&nbsp;&nbsp;&nbsp;Upload test in production
                    </div>
                </a>
        <?php        
                /*}*/
        ?>

        <?php        
            /*}*/
        ?>    

        <a href="transfer_file_property.php" id="modifyPropertyLink" class="internalLink moduleLink" hidden>
            <div class="col-xs-4 mobMainMenuItemCnt">
                <i class="fa fa-gears"></i>&nbsp;&nbsp;&nbsp;Modify process property
            </div>
        </a>
		<a href="registrazione.php" id="registerLink" class="internalLink moduleLink" hidden>
        <div class="col-xs-4 mobMainMenuItemCnt">
            <i class="fa fa-user-plus"></i>&nbsp;&nbsp;&nbsp;User Register
			</div>
    </a> 
    </div>  
</div>

<script type='text/javascript'>
var user_active = $("#utente_att").text();
var role_active = $("#role_att").text();

    $(document).ready(function () 
    {
        $('#mobMainMenuCnt').css("top", parseInt($('#mobHeaderClaimCnt').height() + $('#headerMenuCnt').height()) + "px");
        
        $( window ).on( "orientationchange", function( event ) {
            if($('#mobMainMenuCnt').is(':visible'))
            {
                if($(window).width() < $(window).height())
                {
                    $('#mobMainMenuPortraitCnt').hide();
                    $('#mobMainMenuLandCnt').show();
                }
                else
                {
                    $('#mobMainMenuLandCnt').hide();
                    $('#mobMainMenuPortraitCnt').show();
                }
            }
        });
        
        $('#mobMainMenuBtn').parent().click(function(){
            if($('#mobMainMenuBtn').attr("data-shown") === "false")
            {
                $('#mobMainMenuCnt').show();
                if($(window).width() < $(window).height())
                {
                    $('#mobMainMenuLandCnt').hide();
                    $('#mobMainMenuPortraitCnt').show();
                }
                else
                {
                    $('#mobMainMenuPortraitCnt').hide();
                    $('#mobMainMenuLandCnt').show();
                }
                
                
                $('#mobMainMenuBtn').attr("data-shown", "true");
                setTimeout(function(){
                    $('#mobMainMenuCnt').css("opacity", "1");
                }, 50);
            }
            else
            {
                
                $('#mobMainMenuCnt').css("opacity", "0");
                $('#mobMainMenuBtn').attr("data-shown", "false");
                setTimeout(function(){
                    $('#mobMainMenuCnt').hide();
                }, 350);
            }
        });
        
        
        $('#mobMainMenuPortraitCnt #mobMainMenuUsrLogoutBtn').click(function(){
            location.href = "logout.php";
        });
        
        $('#mobMainMenuLandCnt #mobMainMenuUsrLogoutBtn').click(function(){
            location.href = "logout.php";
        });
		
		
		
		if (role_active =="Manager"){
			$("#nodesLink").hide();
			$("#modifyPropertyLink").hide();
			$("#registerLink").hide();
			$("#upoladProcess").hide();
			$("#processloaderLink").show();
			$("#UploadLink").show();
			$("#processTypeLink").show();
			$("#archiveLink").show();
			
		}
		
		if (role_active =="ToolAdmin"){
			$("#nodesLink").show();
			$("#modifyPropertyLink").show();
			$("#registerLink").show();
			$("#upoladProcess").show();
			
		}
		
		if (role_active ==""){
			$("#nodesLink").hide();
			$("#modifyPropertyLink").hide();
			$("#registerLink").hide();
			$("#upoladProcess").hide();
			$("#processloaderLink").hide();
			$("#UploadLink").hide();
			$("#processTypeLink").hide();
			$("#uploadFileListLink").hide();
			$("#archiveLink").hide();
			
		}
        
    });
</script>    

