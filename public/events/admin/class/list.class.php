<?php
class lists {	
	public function getTimezonesList() {
		$arrayTimezones = Array();
		$timezonesQry = mysql_query("SELECT * FROM events_timezones ORDER BY timezone_name");
		
		while($timezoneRow=mysql_fetch_array($timezonesQry)) {
			$arrayTimezones[$timezoneRow["timezone_id"]] = Array();
			$arrayTimezones[$timezoneRow["timezone_id"]]["timezone_name"] = $timezoneRow["timezone_name"];
			$arrayTimezones[$timezoneRow["timezone_id"]]["timezone_value"] = $timezoneRow["timezone_value"];
		}
		return $arrayTimezones;
	}	
	
	public function getCalendarsList() {
		$arrayCalendars = Array();
		$calendarsQry = mysql_query("SELECT * FROM events_calendars ORDER BY calendar_order");
		
		while($calendarRow=mysql_fetch_array($calendarsQry)) {
			$arrayCalendars[$calendarRow["calendar_id"]] = Array();
			$arrayCalendars[$calendarRow["calendar_id"]]["calendar_title"] = stripslashes($calendarRow["calendar_title"]);
			$arrayCalendars[$calendarRow["calendar_id"]]["calendar_order"] = $calendarRow["calendar_order"];
			$arrayCalendars[$calendarRow["calendar_id"]]["calendar_active"] = $calendarRow["calendar_active"];
		}
		return $arrayCalendars;
	}
	
	public function getEventsList($calendar_id) {
		$arrayEvents = Array();
		$eventsQry = mysql_query("SELECT * FROM events_events WHERE calendar_id=".$calendar_id." ORDER BY event_date_from DESC, event_starttime ASC");
		
		while($eventRow=mysql_fetch_array($eventsQry)) {
			$arrayEvents[$eventRow["event_id"]] = Array();
			$arrayEvents[$eventRow["event_id"]]["event_title"] = stripslashes($eventRow["event_title"]);
			$arrayEvents[$eventRow["event_id"]]["event_date_from"] = $eventRow["event_date_from"];
			$arrayEvents[$eventRow["event_id"]]["event_date_to"] = $eventRow["event_date_to"];
			$arrayEvents[$eventRow["event_id"]]["event_starttime"] = $eventRow["event_starttime"];
			$arrayEvents[$eventRow["event_id"]]["event_active"] = $eventRow["event_active"];
		}
		return $arrayEvents;
	}
	
	public function getSpamList() {
		$arraySpam = Array();
		$spamQry = mysql_query("SELECT * FROM events_twitter_spam");
		
		while($spamRow=mysql_fetch_array($spamQry)) {
			$arraySpam[$spamRow["spam_id"]] = Array();
			$arraySpam[$spamRow["spam_id"]]["spam_username"] =$spamRow["spam_username"];
			$arraySpam[$spamRow["spam_id"]]["spam_active"] =$spamRow["spam_active"];
			
		}
		
		return $arraySpam;
	}
	
	public function getTwitterList() {
		$arrayTwitter = Array();
		$twitterQry = mysql_query("SELECT * FROM events_twitter_users");
		
		while($twitterRow=mysql_fetch_array($twitterQry)) {
			$arrayTwitter[$twitterRow["twitter_user_id"]] = Array();
			$arrayTwitter[$twitterRow["twitter_user_id"]]["twitter_username"] =$twitterRow["twitter_username"];
			$arrayTwitter[$twitterRow["twitter_user_id"]]["twitter_approved"] =$twitterRow["twitter_approved"];
			
		}
		
		return $arrayTwitter;
	}
	
	public function getMobileList() {
		$arrayMobile = Array();
		$mobileQry = mysql_query("SELECT * FROM events_mobile_browsers ORDER BY browser_name");
		
		while($mobileRow=mysql_fetch_array($mobileQry)) {
			$arrayMobile[$mobileRow["browser_id"]] = Array();
			$arrayMobile[$mobileRow["browser_id"]]["browser_name"] = stripslashes($mobileRow["browser_name"]);
		}
		return $arrayMobile;
	}
	
	
	
	
}

?>