<?php

class twitter_spam {
	private static $spam_id;
	private static $qrySpam;
	
	
	public function setSpam($id) {
		
		
		$rsSpam = mysql_query("SELECT * FROM events_twitter_spam WHERE spam_id=".$id);
		
		$rowSpam = mysql_fetch_array($rsSpam);
		twitter_spam::$qrySpam = $rowSpam;
		twitter_spam::$spam_id=$rowSpam["spam_id"];
		
		
	}
	
	public function getSpamId() {
		return twitter_spam::$spam_id;
	}
	
	public function getSpamUsername() {
		return twitter_spam::$qrySpam["spam_username"];
	}
	
	public function getSpamActive() {
		return twitter_spam::$qrySpam["spam_active"];
	}	
	
	/*public function deleteSpam($listIds) {
		mysql_query("DELETE FROM events_twitter_spam WHERE spam_id IN (".$listIds.")");
	}*/
	
	public function reportAsSpam($listIds) {
		
		mysql_query("UPDATE events_twitter_spam SET spam_active = 1 WHERE spam_id IN (".$listIds.")");
	}
	
	public function reportAsNotSpam($listIds) {
		//remove from spam list and put in approved list
		$spamQry=mysql_query("SELECT * FROM events_twitter_spam WHERE spam_id IN (".$listIds.")");
		
		while($spamRow = mysql_fetch_array($spamQry)) {
			$username = $spamRow["spam_username"];
			mysql_query("DELETE FROM events_twitter_spam WHERE spam_id=".$spamRow["spam_id"]);
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
	}
	
	public function insertSpam($username) {
		//delete from whitelist
		mysql_query("DELETE FROM events_twitter_users WHERE twitter_username='".$username."'");
		//insert
		mysql_query("INSERT INTO events_twitter_spam ( spam_username,spam_active) VALUES('".mysql_real_escape_string($username)."',1)");
		return mysql_insert_id();
					 
	}
	
	public function getSpamRecordcount() {
		$spamQry = mysql_query("SELECT * FROM events_twitter_spam");
		return mysql_num_rows($spamQry);
	}
	
	
	
	
}

?>