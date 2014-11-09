<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	
	//edit calendar
	mysql_query("UPDATE events_mobile_browsers SET browser_name= '".mysql_real_escape_string($_REQUEST["name"])."' WHERE browser_id=".$_REQUEST["item_id"]);
		
		
	
}


?>
