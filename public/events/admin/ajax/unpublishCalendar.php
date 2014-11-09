<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$item_id = $_REQUEST["calendar_id"];	
	
	mysql_query("UPDATE events_calendars SET calendar_active = 0 WHERE calendar_id = ".$item_id);
	
}

?>