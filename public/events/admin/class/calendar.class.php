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
	
	public function getCalendarOrder() {
		return calendar::$calendarQry["calendar_order"];
	}
	
	public function publishCalendars($listIds) {
		mysql_query("UPDATE events_calendars SET calendar_active = 1 WHERE calendar_id IN (".$listIds.")");
	}
	
	public function unpublishCalendars($listIds) {
		mysql_query("UPDATE events_calendars SET calendar_active = 0 WHERE calendar_id IN (".$listIds.")");
	}
	
	public function delCalendars($listIds) {
		mysql_query("DELETE FROM events_calendars WHERE calendar_id IN (".$listIds.")");
		mysql_query("DELETE FROM events_events WHERE calendar_id IN (".$listIds.")");
	}
	
	function recurse_copy($src,$dst) { 
		$dir = opendir($src); 
		@mkdir($dst); 
		while(false !== ( $file = readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) { 
					$this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
				} 
				else { 
					copy($src . '/' . $file,$dst . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
	} 
	
	public function duplicateCalendars($listIds) {
		$newOrder = 0;
		//check order of last calendar
		$calOrderQry = mysql_query("SELECT calendar_order as max FROM events_calendars ORDER BY calendar_order DESC LIMIT 1");
		if(mysql_num_rows($calOrderQry)>0) {
			$newOrder=mysql_result($calOrderQry,0,'max')+1;
		}
		$calendarsQry = mysql_query("SELECT * FROM events_calendars WHERE calendar_id IN (".$listIds.")");
		while($calendarRow=mysql_fetch_array($calendarsQry)) {
			mysql_query("INSERT INTO events_calendars (calendar_title,calendar_order,calendar_active) VALUES('duplicate of ".mysql_real_escape_string($calendarRow["calendar_title"])."',".$newOrder.",0)");
			$last_id = mysql_insert_id();
			//duplicate events
			$eventsQry = mysql_query("SELECT * FROM events_events WHERE calendar_id =".$calendarRow["calendar_id"]);
			while($eventRow=mysql_fetch_array($eventsQry)) {
				mysql_query("INSERT INTO events_events (calendar_id,event_title,event_date_from,event_date_to,event_starttime,event_endtime,event_endtime_flag,event_venue,event_entrance,event_seats,event_description,event_map,event_image,event_cover_visible,event_images,event_video,event_link,event_site,event_free,event_active) SELECT '".$last_id."',event_title,event_date_from,event_date_to,event_starttime, event_endtime, event_endtime_flag, event_venue, event_entrance, event_seats, event_description, event_map, event_image, event_cover_visible, event_images, event_video, event_link, event_site, event_free,event_active FROM events_events WHERE event_id = ".$eventRow["event_id"]);
				
				$last_event_id = mysql_insert_id();
				//duplicate daytimes
				mysql_query("INSERT INTO events_days(event_id,day_date,day_time_from,day_time_to,day_time_to_flag) SELECT '".$last_event_id."',day_date,day_time_from,day_time_to,day_time_to_flag FROM events_days WHERE event_id = ".$eventRow["event_id"]." ORDER BY day_time_from");
				
				//duplicate images on disk
				mkdir('../contents/events/'.$last_event_id);
				if(is_dir('../contents/events/'.$eventRow["event_id"])) {
					$this->recurse_copy('../contents/events/'.$eventRow["event_id"],'../contents/events/'.$last_event_id);
				}
				//copy('../contents/events/'.$eventRow["event_id"],'../contents/events/'.$last_id);
				
			}
			
		}
	}
	
	public function addCalendar($title) {
		$newOrder = 0;
		//check order of last calendar
		$calOrderQry = mysql_query("SELECT calendar_order as max FROM events_calendars ORDER BY calendar_order DESC LIMIT 1");
		if(mysql_num_rows($calOrderQry)>0) {
			$newOrder=mysql_result($calOrderQry,0,'max')+1;
		}
		mysql_query("INSERT INTO events_calendars (calendar_title,calendar_order,calendar_active) VALUES('".mysql_real_escape_string($title)."',".$newOrder.",0)");
		return mysql_insert_id();
	}
	
	public function getCalendarRecordcount() {
		return mysql_num_rows(mysql_query("SELECT * FROM events_calendars"));
	}
	
	public function setDefaultCalendar($calendar_id) {
		mysql_query("UPDATE events_calendars SET calendar_order = 0, calendar_active = 1 WHERE calendar_id=".$calendar_id);
		mysql_query("UPDATE events_calendars SET calendar_order = calendar_order +1 WHERE calendar_id <> ".$calendar_id);
	}

}

?>