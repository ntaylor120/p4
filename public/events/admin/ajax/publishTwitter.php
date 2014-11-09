<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$item_id = $_REQUEST["twitter_user_id"];	
	
	mysql_query("UPDATE events_twitter_users SET twitter_approved = 1 WHERE twitter_user_id = ".$item_id);
	
}

?>