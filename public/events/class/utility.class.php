<?php

class utility {
	
	
	
	public function isTwitterSpam($username) {
		$rsSpam = mysql_query("SELECT * FROM events_twitter_spam WHERE spam_username = '".$username."' AND spam_active = 1");
		if(mysql_num_rows($rsSpam) >0) {
			//it's spam
			return true;
		} else {
			//it's not spam
			return false;
		}
	}
	
	public function isTwitterApproved($username) {
		$rsSpam = mysql_query("SELECT * FROM events_twitter_users WHERE twitter_username = '".$username."' AND twitter_approved = 1");
		if(mysql_num_rows($rsSpam) >0) {
			//it's spam
			return true;
		} else {
			//it's not spam
			return false;
		}
	}
	
	public function addTwitterUser($username) {		
		//check tweet is not already registered
		$rsTwitter = mysql_query("SELECT * FROM events_twitter_users WHERE twitter_username='".$username."'");
		if(mysql_num_rows($rsTwitter)== 0) {
			$token=md5(rand());
			mysql_query("INSERT INTO events_twitter_users (twitter_username,twitter_token,twitter_approved) VALUES('".mysql_real_escape_string($username)."','".$token."',0)");
			return $token;
		} else {
			return '0';
		}
	}
	
	public function checkTwitterToken($token) {
		$rsTwitter=mysql_query("SELECT * FROM events_twitter_users WHERE twitter_token='".$token."'");
		if(mysql_num_rows($rsTwitter)>0) {
			return mysql_result($rsTwitter,0,'twitter_user_id');
		} else {
			return 0;
		}
	}
}

?>