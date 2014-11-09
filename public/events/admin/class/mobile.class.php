<?php

class mobile {
	private static $mobile_id;
	private static $mobileQry;
	
	public function setMobile($id) {
		$mobileQry = mysql_query("SELECT * FROM events_mobile_browsers WHERE browser_id = ".$id);
		
		$mobileRow = mysql_fetch_array($mobileQry);
		mobile::$mobileQry = $mobileRow;
		mobile::$mobile_id=$mobileRow["browser_id"];
	}
	
	public function getMobileId() {
		return mobile::$mobile_id;
	}
	
	public function getMobileName() {
		return stripslashes(mobile::$mobileQry["browser_name"]);
	}
	
	public function delMobile($listIds) {
		mysql_query("DELETE FROM events_mobile_browsers WHERE browser_id IN (".$listIds.")");
		
	}
	
	
	public function addMobile($title) {
		
		
		mysql_query("INSERT INTO events_mobile_browsers (browser_name) VALUES('".mysql_real_escape_string($title)."')");
		return mysql_insert_id();
	}
	
	public function getMobileRecordcount() {
		return mysql_num_rows(mysql_query("SELECT * FROM events_mobile_browsers"));
	}
	
	

}

?>