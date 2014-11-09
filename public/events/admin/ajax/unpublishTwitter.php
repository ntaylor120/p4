<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$item_id = $_REQUEST["twitter_user_id"];	
	
	$twitterObj->disapprove($item_id);
	
}

?>