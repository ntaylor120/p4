<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$item_id = $_REQUEST["spam_id"];	
	
	mysql_query("UPDATE events_twitter_spam SET spam_active = 1 WHERE spam_id = ".$item_id);
	
}

?>