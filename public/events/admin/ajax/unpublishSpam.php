<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$item_id = $_REQUEST["spam_id"];	
	//remove from spam list and put in approved list
	$spamQry=mysql_query("SELECT spam_username FROM events_twitter_spam WHERE spam_id = ".$item_id);
	$spamRow=mysql_fetch_array($spamQry);
	$username = $spamRow["spam_username"];
	mysql_query("DELETE FROM events_twitter_spam WHERE spam_id=".$item_id);
	//check if not exists
	$approveQry=mysql_query("SELECT * FROM events_twitter_users WHERE twitter_username='".$username."'");
	if(mysql_num_rows($approveQry)>0) {
		//exists, update
		mysql_query("UPDATE events_twitter_users SET twitter_approved = 1 WHERE twitter_username='".$username."'");
	} else {
		//insert
		mysql_query("INSERT INTO events_twitter_users (twitter_username,twitter_token,twitter_approved) VALUES('".$username."','',1)");
	}
	
}

?>