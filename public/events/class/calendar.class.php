<?php

class calendar {
	private static $calendar_id;
	private static $calendarQry;
	
	public function setCalendar($id) {
		$calendarQry = mysql_query("SELECT * FROM events_calendars WHERE calendar_id = ".$id);
		
		$calendarRow = mysql_fetch_array($calendarQry);
		calendar::$calendarQry = $calendarRow;
		calendar::$calendar_id=$calendarRow["calendar_id"];
	}
	
	public function getCalendarId() {
		return calendar::$calendar_id;
	}
	
	public function getCalendarTitle() {
		return stripslashes(calendar::$calendarQry["calendar_title"]);
	}
	
	public function getCalendarActive() {
		return calendar::$calendarQry["calendar_active"];
	}
	
	public function getCalendarRecordcount() {
		return mysql_num_rows(mysql_query("SELECT * FROM events_calendars"));
	}
	
	public function getDefaultCalendar() {
		$calendarQry = mysql_query("SELECT * FROM events_calendars WHERE calendar_order = 0 AND calendar_active = 1");
		if(mysql_num_rows($calendarQry) > 0) {
			$calendarRow = mysql_fetch_array($calendarQry);
			$this->setCalendar($calendarRow["calendar_id"]);
			return true;
		} else {
			return false;
		}
		
	}
	
}

?>