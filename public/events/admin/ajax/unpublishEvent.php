<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$item_id = $_REQUEST["event_id"];	
	
	mysql_query("UPDATE events_events SET event_active = 0 WHERE event_id = ".$item_id);
	
}

?>