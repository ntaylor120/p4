<?php

class twitter_approve {
	private static $user_id;
	private static $qryUser;
	
	
	public function setUser($id) {
		
		
		$rsUser = mysql_query("SELECT * FROM events_twitter_users WHERE twitter_user_id=".$id);
		
		$rowUser = mysql_fetch_array($rsUser);
		twitter_approve::$qryUser = $rowUser;
		twitter_approve::$user_id=$rowUser["twitter_user_id"];
		
		
	}
	
	public function getUserId() {
		return twitter_approve::$user_id;
	}
	
	public function getUsername() {
		return twitter_approve::$qryUser["twitter_username"];
	}
	
	public function getToken() {
		return twitter_approve::$qryUser["twitter_token"];
	}
	
	public function getApproved() {
		return twitter_approve::$qryUser["twitter_approved"];
	}	
	
	public function approve($listIds) {
		mysql_query("UPDATE events_twitter_users SET twitter_approved = 1, twitter_token = '' WHERE twitter_user_id IN (".$listIds.")");
	}
	
	public function disapprove($listIds) {
		$rsTwitter=mysql_query("SELECT * FROM events_twitter_users WHERE twitter_user_id IN (".$listIds.")");
		while($rowTwitter=mysql_fetch_array($rsTwitter)) {
			//metto nello spam e tolgo dalla tabella di verifica
			mysql_query("INSERT INTO events_twitter_spam ( spam_username,spam_active) VALUES('".mysql_real_escape_string($rowTwitter["twitter_username"])."',1)");			
		}
		mysql_query("DELETE FROM events_twitter_users WHERE twitter_user_id IN (".$listIds.")");
	}
	
	
	public function insertUser($username) {
		$newid=0;
		//check blacklist, then remove
		mysql_query("DELETE FROM events_twitter_spam WHERE spam_username='".$username."'");
		//check if exists
		$rsUser=mysql_query("SELECT * FROM events_twitter_users WHERE twitter_username='".$username."'");
		if(mysql_num_rows($rsUser)>0) {
			//update
			mysql_query("UPDATE events_twitter_users SET twitter_approved = 1 WHERE twitter_username='".$username."'");
			//get id back
			$idQry = mysql_query("SELECT * FROM events_twitter_users WHERE twitter_username='".$username."'");
			$newid=mysql_result($idQry,'0','twitter_user_id');
		} else {
			//insert
			mysql_query("INSERT INTO events_twitter_users ( twitter_username,twitter_approved) VALUES('".mysql_real_escape_string($username)."',1)");
			$newid=mysql_insert_id();
		}
					 
		return $newid;
	}
	
	public function getUsersRecordcount() {
		$qryUser = mysql_query("SELECT * FROM events_twitter_users");
		return mysql_num_rows($qryUser);
	}
	
	
	
	
	
	
}

?>