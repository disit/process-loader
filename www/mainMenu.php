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
<div class="hidden-xs hidden-sm col-md-2" id="mainMenuCnt">
    <div id="headerClaimCnt" class="col-md-12 centerWithFlex">Snap4City</div>
    <div class="col-md-12 mainMenuUsrCnt">
        <div class="row">
            <div class="col-md-12 centerWithFlex" id="mainMenuIconCnt">
                <img src="img/mainMenuIcons/user.ico" />
            </div>
            <div class="col-md-12 centerWithFlex" id="mainMenuUsrCnt">
				<span id="utente_att"><?=$utente_att;?></span>
				
				<!--<a data-toggle="modal" href="#login-modal">
					<span id="utente_att"><?=$utente_att;?></span>
				</a>-->
            </div>
            <div class="col-md-12 centerWithFlex" id="mainMenuUsrDetCnt">
				<span aria-hidden="true" data-toggle="modal" data-target="#" id="role_att"><?=$role_att;?></span>
            </div>
            <div class="col-md-12 centerWithFlex" id="mainMenuUsrLogoutCnt">
                Logout
            </div>
        </div>
    </div>
	<!--
		<a href="page.php" id="page" data-externalApp="no" data-openMode="newTab" data-linkUrl="page.php" data-pageTitle="View Resources" data-submenuVisible="false" class="internalLink moduleLink mainMenuLink">
                                    <div class="col-md-12 mainMenuItemCnt">
                                        <i class="fa fa-eye" style="color: #ff9933"></i>&nbsp;&nbsp;&nbsp;View Resources</div></a>
	-->								
    <?php
       // include 'config.php';
        
        error_reporting(E_ERROR | E_NOTICE);
        date_default_timezone_set('Europe/Rome');
        
        $link = mysqli_connect($host, $username, $password);
        mysqli_select_db($link, $dbname);
        
        $menuQuery = "SELECT * FROM processloader_db.MainMenu ORDER BY id ASC";
		//$menuQuery = "SELECT * FROM processloader_db.MainMenu, processloader_db.functionalities WHERE MainMenu.linkUrl = functionalities.link group by MainMenu.linkUrl ORDER BY MainMenu.id ASC;";
        $r = mysqli_query($link, $menuQuery);

        if($r)
        {
            while($row = mysqli_fetch_assoc($r))
            {
                $menuItemId = $row['id'];
                $linkUrl = $row['linkUrl'];
                
                if($linkUrl == 'submenu')
                {
                    $linkUrl = '#';
                }
                
                $linkId = $row['linkId'];
                $icon = $row['icon'];  
                $text = $row['text'];
                $privileges = $row['privileges'];      
                $userType = $row['userType']; 
                $externalApp = $row['externalApp'];
                $openMode = $row['openMode'];
                $iconColor = $row['iconColor'];
                $pageTitle = $row['pageTitle'];
                $externalApp = $row['externalApp'];
                
				
                    $newItem =  '<a href="' . $linkUrl . '" id="' . $linkId . '" data-externalApp="' . $externalApp . '" data-openMode="' . $openMode . '" data-linkUrl="' . $linkUrl . '" data-pageTitle="' . $pageTitle . '" data-submenuVisible="false" class="internalLink moduleLink mainMenuLink">' .
                                    '<div class="col-md-12 mainMenuItemCnt">' .
                                        '<i class="' . $icon . '" style="color: ' . $iconColor . '"></i>&nbsp;&nbsp;&nbsp;' . $text .
                                    '</div>' .
                                '</a>';

				
				$exist = 0;
				//DECODIFICA DEL CAMPO privileges//
				$privileges = str_replace("{", "", $privileges);
				$privileges = str_replace("}", "", $privileges);
				$pieces = explode(",", $privileges);
				if (in_array($_SESSION['role'], $pieces)) {
					$exist = 1;
				}else{
					$exist = 0;
				}
				
						
				
                //if((strpos($privileges, 'Public') !== false)||(strpos($privileges, $_SESSION['role']) !== false))
				if((strpos($privileges, 'Public') !== false)|| $exist == 1)
                {
                    echo $newItem;
                }

            }
            
        }
    ?>
  </div>  
    
    

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form name="form_login" method="post" action="login.php">
				<div class="modalHeader centerWithFlex" id="loginFormTitle">
					Login
				</div>

				<div id="loginFormBody" class="modal-body modalBody">   
					<div class="col-xs-12 modalCell">
						<div class="modalFieldCnt">
							<input type="text" class="modalInputTxt" id="inputUsername" name="username" required> 
						</div>
						<div class="modalFieldLabelCnt">Username</div>
					</div>
					<div class="col-xs-12 modalCell">
						<div class="modalFieldCnt">
							<input type="password" class="modalInputTxt" id="inputPassword" name="password" required> 
						</div>
						<div class="modalFieldLabelCnt">Password</div>
					</div>
					<div class="col-xs-12 modalCell">
						<div id="loginFormMessage"></div>
					</div>
				</div>


				<div id="loginFormFooter" class="modal-footer">
				   <button type="reset" id="loginCancelBtn" class="btn cancelBtn" data-dismiss="modal">Reset</button>
				   <button type="submit" id="loginConfirmBtn" class="btn confirmBtn">Confirm</button>
				</div>
			</form>	
		</div>    <!-- Fine modal content -->
	</div> <!-- Fine modal dialog -->
</div><!-- Fine modale -->    
    
<script type='text/javascript'>
var user_active = $("#nome_ut").text();
var role_active = $("#role_att").text();

    $(document).ready(function () 
    {
		if("<?= isset($_SESSION['username']) ?>" === "1")
		{
			//Caso in cui l'utente è collegato: devono rispondere i gestori di logout
			$('div.mainMenuUsrCnt').hover(function(){
				$(this).css("background", "rgba(0, 162, 211, 1)");
				$(this).css("cursor", "pointer");
				$('#mainMenuUsrDetCnt').hide();
				$('#mainMenuUsrLogoutCnt').show();
			}, function(){
				$(this).css("background", "transparent");
				$(this).css("cursor", "normal");
				$('#mainMenuUsrLogoutCnt').hide();
				$('#mainMenuUsrDetCnt').show();
			});
			
			$('div.mainMenuUsrCnt').click(function(){
				location.href = "logout.php";
			});
		}
		else
		{
			//Caso in cui l'utente non è collegato: devono rispondere i gestori di login
			$('div.mainMenuUsrCnt').hover(function(){
				$(this).css("background", "rgba(0, 162, 211, 1)");
				$(this).css("cursor", "pointer");
			}, function(){
				$(this).css("background", "transparent");
				$(this).css("cursor", "normal");
			});
			
			$('div.mainMenuUsrCnt').click(function(event)
			{
				$("#login-modal").modal('show');
			});
			
		}
		
        
        $('#mainMenuCnt a.mainMenuSubItemLink').hide();
        
        $('#mainMenuCnt .mainMenuLink').click(function(event){
			//Distinguiamo fra elementi con sottomenu e privi di sottomenu
			if($(this).attr('data-linkUrl') === 'submenu')
            {
				//Gestisce apertura e chiusura del sottomenu
				if($(this).attr('data-submenuVisible') === 'false')
                {
                    $(this).attr('data-submenuVisible', 'true');
                    $('#mainMenuCnt a.mainMenuSubItemLink[data-fatherMenuId=' + $(this).attr('id') + ']').show();
                }
                else
                {
                    $(this).attr('data-submenuVisible', 'false');
                    $('#mainMenuCnt a.mainMenuSubItemLink[data-fatherMenuId=' + $(this).attr('id') + ']').hide();
                }
			}
			else
			{
				var pageTitle = $(this).attr('data-pageTitle');
				var linkId = $(this).attr('id');
				var linkUrl = $(this).attr('data-linkUrl');
				
				//Elemento privo di sottomenu
				if(($(this).attr('data-openMode') === 'iframe')||($(this).attr('data-openMode') === 'newTab'))
				{
					//To be done - Apertura in un iframe o in nuovo tab	
					if(($(this).attr('data-openMode') === 'iframe') && ($(this).attr('data-externalApp') === 'yes'))
					{
						event.preventDefault();
						
						
						location.href = "iframeApp.php?linkUrl=" + encodeURI(linkUrl) + "&linkId=" + linkId + "&pageTitle=" + pageTitle;
					}
					else
					{
						
					}
				}
				else
				{
					//Apertura nello stesso tab/finestra, cioé classica navigazione intra applicazione
					event.preventDefault();
					console.log("Page mode: " + $(this).attr('data-openMode'));
					//Apriamo la pagina come prima, però passandole il titolo della pagina stessa, di modo che sia dinamico e impostato da database
					location.href = encodeURI(linkUrl) + "?pageTitle=" + pageTitle;
				}
				
				
				
				
			}
			
			
            
        });
    });
</script>    

