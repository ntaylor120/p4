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

	public function getCalendarsList($order_by) {
		$arrayCalendars = Array();
		$calendarsQry = mysql_query("SELECT * FROM events_calendars WHERE calendar_active = 1 ".$order_by);
		
		while($calendarRow=mysql_fetch_array($calendarsQry)) {
			$arrayCalendars[$calendarRow["calendar_id"]] = Array();
			$arrayCalendars[$calendarRow["calendar_id"]]["calendar_title"] = $calendarRow["calendar_title"];
			$arrayCalendars[$calendarRow["calendar_id"]]["calendar_active"] = $calendarRow["calendar_active"];
		}
		return $arrayCalendars;
	}
	
	public function getMonthCalendar($month,$year,$weekday_format = 'N') {
		//calendartype: N or w
		$arrayMonth=Array();
		$date = mktime(0,0,0,$month,1,$year); 
		for($n=1;$n <= date('t',$date);$n++){
			$arrayMonth[$n] = Array();
			$arrayMonth[$n]["dayofweek"] = date($weekday_format,mktime(0,0,0,$month,$n,$year));
			$arrayMonth[$n]["daynum"] = date('d',mktime(0,0,0,$month,$n,$year));
			$arrayMonth[$n]["monthnum"] = date('m',mktime(0,0,0,$month,$n,$year));
			$arrayMonth[$n]["yearnum"] = date('Y',mktime(0,0,0,$month,$n,$year));
		}
		return $arrayMonth;
	}
	
	public function getEventsPerDay($year,$month,$daynum, $calendar_id) {
		if(strlen($month) == 1) {
			$month="0".$month;
		}
		if(strlen($daynum) == 1) {
			$daynum="0".$daynum;
		}
		
		$eventsQry = mysql_query("SELECT * FROM events_events WHERE event_date_from <= '".$year."-".$month."-".$daynum."' AND event_date_to>='".$year."-".$month."-".$daynum."' AND event_active = 1 AND calendar_id=".$calendar_id);
		
		$tot = mysql_num_rows($eventsQry);
		
		return $tot;
	}
	
	public function getEventsPerDayList($year,$month,$daynum,$calendar_id,$from=-1,$limit=1) {
		if(strlen($month) == 1) {
			$month="0".$month;
		}
		if(strlen($daynum) == 1) {
			$daynum="0".$daynum;
		}
		$arrayEvents=Array();
		if($from == -1) {
			$eventsQry = mysql_query("SELECT * FROM events_events WHERE event_date_from <= '".$year."-".$month."-".$daynum."' AND event_date_to>='".$year."-".$month."-".$daynum."' AND event_active = 1 AND calendar_id=".$calendar_id." ORDER BY event_starttime");
		} else {
			$eventsQry = mysql_query("SELECT * FROM events_events WHERE event_date_from <= '".$year."-".$month."-".$daynum."' AND event_date_to>='".$year."-".$month."-".$daynum."' AND event_active = 1 AND calendar_id=".$calendar_id." ORDER BY event_starttime LIMIT ".$from.",".$limit);
		}
		
		while($eventRow = mysql_fetch_array($eventsQry)) {			
			$arrayEvents[$eventRow["event_id"]] = Array();
			$arrayEvents[$eventRow["event_id"]]["event_starttime"] = $eventRow["event_starttime"];
			$arrayEvents[$eventRow["event_id"]]["event_endtime"] = $eventRow["event_endtime"];
			$arrayEvents[$eventRow["event_id"]]["event_title"] = stripslashes($eventRow["event_title"]);
			$arrayEvents[$eventRow["event_id"]]["event_seats"] = $eventRow["event_seats"];
			$arrayEvents[$eventRow["event_id"]]["event_description"] = stripslashes($eventRow["event_description"]);
			$arrayEvents[$eventRow["event_id"]]["event_image"] = $eventRow["event_image"];
			$arrayEvents[$eventRow["event_id"]]["event_free"] = $eventRow["event_free"];
			$arrayEvents[$eventRow["event_id"]]["event_link"] = $eventRow["event_link"];
		}
		return $arrayEvents;
	}
	
	public function searchEvents($text,$calendar_id,$date_format,$from=-1,$limit=1) {
		if($date_format=="UK") {
			$sql_date_format="%d/%m/%Y";
		} else if($date_format=="EU") {
			$sql_date_format="%Y/%m/%d";
		} else {
			$sql_date_format="%m/%d/%Y";
		}
		$arrayEvents=Array();
		if($from == -1) {
			$eventsQry = mysql_query("SELECT e.*,DATE_FORMAT(e.event_date_from,'".$sql_date_format."') as date_from,DATE_FORMAT(e.event_date_to,'".$sql_date_format."') as date_to FROM events_events e WHERE (e.event_title LIKE'%".$text."%' OR e.event_description LIKE '%".$text."%') AND e.event_active=1 AND e.calendar_id = ".$calendar_id." ORDER BY e.event_date_from,e.event_starttime");
		} else {
			$eventsQry = mysql_query("SELECT e.*,DATE_FORMAT(e.event_date_from,'".$sql_date_format."') as date_from,DATE_FORMAT(e.event_date_to,'".$sql_date_format."') as date_to FROM events_events e WHERE (e.event_title LIKE'%".$text."%' OR e.event_description LIKE '%".$text."%') AND e.event_active=1 AND e.calendar_id = ".$calendar_id." ORDER BY e.event_date_from,e.event_starttime LIMIT ".$from.",".$limit."");
		}
		while($eventRow = mysql_fetch_array($eventsQry)) {			
			$arrayEvents[$eventRow["event_id"]] = Array();
			$arrayEvents[$eventRow["event_id"]]["event_starttime"] = $eventRow["event_starttime"];
			$arrayEvents[$eventRow["event_id"]]["event_endtime"] = $eventRow["event_endtime"];
			$arrayEvents[$eventRow["event_id"]]["event_title"] = stripslashes($eventRow["event_title"]);
			$arrayEvents[$eventRow["event_id"]]["event_seats"] = $eventRow["event_seats"];
			$arrayEvents[$eventRow["event_id"]]["event_description"] = stripslashes($eventRow["event_description"]);
			$arrayEvents[$eventRow["event_id"]]["event_image"] = $eventRow["event_image"];
			$arrayEvents[$eventRow["event_id"]]["event_free"] = $eventRow["event_free"];
			$arrayEvents[$eventRow["event_id"]]["date_from"] = $eventRow["date_from"];
			$arrayEvents[$eventRow["event_id"]]["date_to"] = $eventRow["date_to"];
			$arrayEvents[$eventRow["event_id"]]["event_date_from"] = $eventRow["event_date_from"];
			$arrayEvents[$eventRow["event_id"]]["event_date_to"] = $eventRow["event_date_to"];
			$arrayEvents[$eventRow["event_id"]]["event_link"] = $eventRow["event_link"];
		}
		return $arrayEvents;
	}
	
	public function getEventsHomeList($year,$month,$calendar_id,$filter,$date_format,$from=-1,$limit=1) {
		if(strlen($month) == 1) {
			$month="0".$month;
		}
		if($date_format=="UK") {
			$sql_date_format="%d/%m/%Y";
		} else if($date_format=="EU") {
			$sql_date_format="%Y/%m/%d";
		} else {
			$sql_date_format="%m/%d/%Y";
		}
		$arrayEvents=Array();
		switch($filter) {
			case 'future':
				if($month == date('m')) {
					$datefilter=$year.$month.date('d');
				} else {
					$datefilter = $year.$month."01";
				}
				if($from == -1) {
					$eventsQry = mysql_query("SELECT e.*,DATE_FORMAT(e.event_date_from,'".$sql_date_format."') as date_from,DATE_FORMAT(e.event_date_to,'".$sql_date_format."') as date_to FROM events_events e WHERE (DATE_FORMAT(event_date_from,'%Y%m%d') >= '".$datefilter."' OR DATE_FORMAT(event_date_to,'%Y%m%d')>='".$datefilter."') AND event_active = 1 AND calendar_id=".$calendar_id." ORDER BY event_date_from,event_starttime");
					
				} else {
					$eventsQry = mysql_query("SELECT e.*,DATE_FORMAT(e.event_date_from,'".$sql_date_format."') as date_from,DATE_FORMAT(e.event_date_to,'".$sql_date_format."') as date_to FROM events_events e WHERE (DATE_FORMAT(event_date_from,'%Y%m%d') >= '".$datefilter."' OR DATE_FORMAT(event_date_to,'%Y%m%d')>='".$datefilter."') AND event_active = 1 AND calendar_id=".$calendar_id." ORDER BY event_date_from,event_starttime LIMIT ".$from.",".$limit);
					
					
				}
				break;
			case 'past':
				if($month == date('m')) {
					$datefilter=$year.$month.date('d');
				} else {
					$datefilter = $year.$month."01";
				}
				if($from == -1) {
					$eventsQry = mysql_query("SELECT e.*,DATE_FORMAT(e.event_date_from,'".$sql_date_format."') as date_from,DATE_FORMAT(e.event_date_to,'".$sql_date_format."') as date_to FROM events_events e WHERE DATE_FORMAT(event_date_from,'%Y%m%d') < '".$datefilter."' AND DATE_FORMAT(event_date_to,'%Y%m%d')<'".$datefilter."' AND event_active = 1 AND calendar_id=".$calendar_id." ORDER BY event_date_from,event_starttime");
					
				} else {
					$eventsQry = mysql_query("SELECT e.*,DATE_FORMAT(e.event_date_from,'".$sql_date_format."') as date_from,DATE_FORMAT(e.event_date_to,'".$sql_date_format."') as date_to FROM events_events e WHERE DATE_FORMAT(event_date_from,'%Y%m%d') < '".$datefilter."' AND DATE_FORMAT(event_date_to,'%Y%m%d')<'".$datefilter."' AND event_active = 1 AND calendar_id=".$calendar_id." ORDER BY event_date_from,event_starttime LIMIT ".$from.",".$limit);
					
				}
				break;
			case 'all':
				if($from == -1) {
					$eventsQry = mysql_query("SELECT e.*,DATE_FORMAT(e.event_date_from,'".$sql_date_format."') as date_from,DATE_FORMAT(e.event_date_to,'".$sql_date_format."') as date_to FROM events_events e WHERE event_active = 1 AND calendar_id=".$calendar_id." ORDER BY event_date_from,event_starttime");
					
				} else {
					$eventsQry = mysql_query("SELECT e.*,DATE_FORMAT(e.event_date_from,'".$sql_date_format."') as date_from,DATE_FORMAT(e.event_date_to,'".$sql_date_format."') as date_to FROM events_events e WHERE event_active = 1 AND calendar_id=".$calendar_id." ORDER BY event_date_from,event_starttime LIMIT ".$from.",".$limit);
					
				}
				break;
			default:
				if($from == -1) {
					$eventsQry = mysql_query("SELECT e.*,DATE_FORMAT(e.event_date_from,'".$sql_date_format."') as date_from,DATE_FORMAT(e.event_date_to,'".$sql_date_format."') as date_to FROM events_events e WHERE (DATE_FORMAT(event_date_from,'%Y%m') >= '".$year.$month."' OR DATE_FORMAT(event_date_to,'%Y%m')>='".$year.$month."') AND event_active = 1 AND calendar_id=".$calendar_id." ORDER BY event_date_from,event_starttime");
					
				} else {
					$eventsQry = mysql_query("SELECT e.*,DATE_FORMAT(e.event_date_from,'".$sql_date_format."') as date_from,DATE_FORMAT(e.event_date_to,'".$sql_date_format."') as date_to FROM events_events e WHERE (DATE_FORMAT(event_date_from,'%Y%m') >= '".$year.$month."' OR DATE_FORMAT(event_date_to,'%Y%m')>='".$year.$month."') AND event_active = 1 AND calendar_id=".$calendar_id." ORDER BY event_date_from,event_starttime LIMIT ".$from.",".$limit);
					
				}
				break;
				
		}
		
		
		while($eventRow = mysql_fetch_array($eventsQry)) {			
			$arrayEvents[$eventRow["event_id"]] = Array();
			$arrayEvents[$eventRow["event_id"]]["event_starttime"] = $eventRow["event_starttime"];
			$arrayEvents[$eventRow["event_id"]]["event_endtime"] = $eventRow["event_endtime"];
			$arrayEvents[$eventRow["event_id"]]["event_title"] = stripslashes($eventRow["event_title"]);
			$arrayEvents[$eventRow["event_id"]]["event_seats"] = $eventRow["event_seats"];
			$arrayEvents[$eventRow["event_id"]]["event_description"] = stripslashes($eventRow["event_description"]);
			$arrayEvents[$eventRow["event_id"]]["event_image"] = $eventRow["event_image"];
			$arrayEvents[$eventRow["event_id"]]["event_free"] = $eventRow["event_free"];
			$arrayEvents[$eventRow["event_id"]]["date_from"] = $eventRow["date_from"];
			$arrayEvents[$eventRow["event_id"]]["date_to"] = $eventRow["date_to"];
			$arrayEvents[$eventRow["event_id"]]["event_date_from"] = $eventRow["event_date_from"];
			$arrayEvents[$eventRow["event_id"]]["event_date_to"] = $eventRow["event_date_to"];
			$arrayEvents[$eventRow["event_id"]]["event_link"] = $eventRow["event_link"];
		}
		return $arrayEvents;
	}
	
	public function getBrowserArray() {
		$arrayMobile = Array();
		$mobileQry = mysql_query("SELECT * FROM events_mobile_browsers ORDER BY browser_name");
		
		while($mobileRow=mysql_fetch_array($mobileQry)) {
			array_push($arrayMobile,$mobileRow["browser_name"]);
		}
		return $arrayMobile;
	}
	
	
	
	
}

?>