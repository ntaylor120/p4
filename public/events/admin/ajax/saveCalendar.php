<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	
	//edit calendar
	mysql_query("UPDATE events_calendars SET calendar_title= '".mysql_real_escape_string($_REQUEST["title"])."' WHERE calendar_id=".$_REQUEST["item_id"]);
		
		
	
}


?>
