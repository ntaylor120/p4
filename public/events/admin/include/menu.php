<!-- header -->
<div id="header_container">    
	<?php
    if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] != 0) { 
	?>    
        <div class="header_left">
            <div class="header_title"><h1><?php echo $lang["CONTROL_PANEL"]; ?></h1></div>
            <div class="link_website"><a href="../" target="_blank"><?php echo $lang["GO_TO_SITE"]; ?></a></div>
        </div>
        <div class="header_identity_container">
            <div class="header_identity"><?php echo $lang["LOGGED_AS"]; ?> <strong><?php echo $_SESSION["admin_name"]?></strong></div>
            <div class="header_logout"><strong><a href="logout.php"><?php echo $lang["LOGOUT"]; ?></a></strong></div>
        </div>        
        <div id="cleardiv"></div>        
        <div class="line_dotted"></div>
    
    <?php
	} else { 
	?>
        <div class="header_left">
            <div class="header_title"><h1><?php echo $lang["CONTROL_PANEL"]; ?></h1></div>
        </div>        
        <div id="cleardiv"></div>        
        <div class="line_dotted"></div>    
    <?php 
	} 
	?>    
</div>
<div id="cleardiv"></div>    
<!-- menu -->
<div id="menu_container">
    <div id="menu">
    <ul>
        <?php
        if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] != 0) { 
		
		?>
        	<li><a href="welcome.php" class="home_button"></a></li>
            <li><a href="configuration.php" <?php if(stristr($_SERVER["SCRIPT_NAME"],"configuration.php")) { echo "style='background-color: #666;'"; }?>><?php echo $lang["MENU_SETTINGS"]; ?></a></li>
            <?php
			if($settingObj->getSiteDomain() == '') {
				?>
            	<li><a href="#" style='color: #666;'><?php echo $lang["MENU_CALENDARS"]; ?></a></li>
                <li><a href="#" style='color: #666;'><?php echo $lang["MENU_PASSWORD"]; ?></a></li>
                <li><a href="#" style='color: #666;'><?php echo $lang["MENU_META_TAGS"]; ?></a></li>
                <li><a href="#" style='color: #666;'><?php echo $lang["MENU_TWITTER"]; ?></a></li>
                <li><a href="#" style='color: #666;'><?php echo $lang["MENU_MOBILE_BROWSERS"]; ?></a></li>
            <?php
			} else {
				?>
                <script>
					 $(function() {
						// nascondo tutti i sottomenu
						$("#s1").hide();
						
						// mostro i sottomenu del blocco principale 1
						$("#p1").mouseenter(
						  function() {
							if ($("#s1").is(":hidden")) $("#s1").slideDown(); else $("#s1").slideUp();
						  }
						);	
						
						
						// mostro i sottomenu del blocco principale 1
						$("#p1").mouseleave(
						  function() {
							$("#s1").slideUp();
						  }
						);	
						
								
					  }
					);
				</script>
                <li><a href="calendars.php" <?php if(stristr($_SERVER["SCRIPT_NAME"],"calendars.php")) { echo "style='background-color: #666;'"; }?>><?php echo $lang["MENU_CALENDARS"]; ?></a></li>
                <li><a href="password.php" <?php if(stristr($_SERVER["SCRIPT_NAME"],"password.php")) { echo "style='background-color: #666;'"; }?>><?php echo $lang["MENU_PASSWORD"]; ?></a></li>
                <li><a href="metatags.php" <?php if(stristr($_SERVER["SCRIPT_NAME"],"metatags.php")) { echo "style='background-color: #666;'"; }?>><?php echo $lang["MENU_META_TAGS"]; ?></a></li>
                <li id="p1"><a href="#" <?php if(stristr($_SERVER["SCRIPT_NAME"],"twitter_spam.php") || stristr($_SERVER["SCRIPT_NAME"],"twitter_approve.php")) { echo "style='background-color: #666;'"; }?>><?php echo $lang["MENU_TWITTER"]; ?></a>
                	<ul id="s1">
                        <li><a href="twitter_spam.php"><?php echo $lang["MENU_TWITTER_SPAM"]; ?></a></li>
                        <li><a href="twitter_approve.php"><?php echo $lang["MENU_TWITTER_APPROVAL"]; ?></a></li>              
                    </ul>
                </li>
                <li><a href="mobile.php" <?php if(stristr($_SERVER["SCRIPT_NAME"],"mobile.php")) { echo "style='background-color: #666;'"; }?>><?php echo $lang["MENU_MOBILE_BROWSERS"]; ?></a></li>
                <?php
			}
			?>
           
            
        <?php
		}
		?>
    </ul>
   </div>
</div>
