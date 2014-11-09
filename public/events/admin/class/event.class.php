<?php
error_reporting(E_ALL);
class event {
	private static $event_id;
	private static $eventQry;
	
	public function setEvent($id) {
		$eventQry = mysql_query("SELECT * FROM events_events WHERE event_id = ".$id);
		
		$eventRow = mysql_fetch_array($eventQry);
		event::$eventQry = $eventRow;
		event::$event_id=$eventRow["event_id"];
	}
	
	public function getEventId() {
		return event::$event_id;
	}
	
	public function getEventCalendarId() {
		return event::$eventQry["calendar_id"];
	}
	
	public function getEventTitle() {
		return stripslashes(event::$eventQry["event_title"]);
	}
	
	public function getEventDateFrom() {
		return event::$eventQry["event_date_from"];
	}
	
	public function getEventDateTo() {
		return event::$eventQry["event_date_to"];
	}
	
	public function getEventStarttime() {
		return event::$eventQry["event_starttime"];
	}
	
	public function getEventEndtime() {
		return event::$eventQry["event_endtime"];
	}
	
	public function getEventEndtimeFlag() {
		return event::$eventQry["event_endtime_flag"];
	}
	
	public function getEventVenue() {
		return stripslashes(event::$eventQry["event_venue"]);
	}
	
	public function getEventEntrance() {
		return event::$eventQry["event_entrance"];
	}
	
	public function getEventDescription() {
		return stripslashes(event::$eventQry["event_description"]);
	}
	
	public function getEventMap() {
		return stripslashes(event::$eventQry["event_map"]);
	}
	
	public function getEventMapText() {
		return stripslashes(event::$eventQry["event_map_text"]);
	}
	
	public function getEventImage() {
		return event::$eventQry["event_image"];
	}
	
	public function getEventCoverVisible() {
		return event::$eventQry["event_cover_visible"];
	}
	
	public function getEventImages() {
		if(event::$eventQry["event_images"]=="0" || event::$eventQry["event_images"]=='') {
			$arrayImages=Array();
		} else {
			$arrayImages=explode("|",event::$eventQry["event_images"]);
		}
		return $arrayImages;
	}
	
	public function getEventVideo() {
		return stripslashes(event::$eventQry["event_video"]);
	}
	
	public function getEventVideoText() {
		return stripslashes(event::$eventQry["event_video_text"]);
	}
	
	public function getEventLink() {
		return stripslashes(event::$eventQry["event_link"]);
	}
	
	public function getEventHashtag() {
		return stripslashes(event::$eventQry["event_hashtag"]);
	}
	
	public function getEventFlickrSearch() {
		return stripslashes(event::$eventQry["event_flickr_search"]);
	}
	
	public function getEventSite() {
		return stripslashes(event::$eventQry["event_site"]);
	}
	
	public function getEventSeats() {
		return event::$eventQry["event_seats"];
	}
	
	public function getEventFree() {
		return event::$eventQry["event_free"];
	}
	
	public function getEventActive() {
		return event::$eventQry["event_active"];
	}
	
	public function getEventsCustomTimes() {
		$arrayTimes = Array();
		$customTimesQry = mysql_query("SELECT * FROM events_days WHERE event_id = ".$this->getEventId()." ORDER BY day_date");
		while($customTimesRow=mysql_fetch_array($customTimesQry)) {
			$arrayTimes[$customTimesRow["day_id"]]=Array();
			$arrayTimes[$customTimesRow["day_id"]]["day_date"] = $customTimesRow["day_date"];
			$arrayTimes[$customTimesRow["day_id"]]["day_time_from"] = $customTimesRow["day_time_from"];
			$arrayTimes[$customTimesRow["day_id"]]["day_time_to"] = $customTimesRow["day_time_to"];
			$arrayTimes[$customTimesRow["day_id"]]["day_time_to_flag"] = $customTimesRow["day_time_to_flag"];
		}
		return $arrayTimes;
	}
	
	public function getEventPageTitle() {
		return stripslashes(event::$eventQry["event_page_title"]);
	}
	
	public function getEventMetatagTitle() {
		return stripslashes(event::$eventQry["event_metatag_title"]);
	}
	
	public function getEventMetatagDescription() {
		return stripslashes(event::$eventQry["event_metatag_description"]);
	}
	
	public function getEventMetatagKeywords() {
		return stripslashes(event::$eventQry["event_metatag_keywords"]);
	}
	
	public function publishEvents($listIds) {
		mysql_query("UPDATE events_events SET event_active = 1 WHERE event_id IN (".$listIds.")");
	}
	
	public function unpublishEvents($listIds) {
		mysql_query("UPDATE events_events SET event_active = 0 WHERE event_id IN (".$listIds.")");
	}
	
	public function delEvents($listIds) {
		$arrIds = explode(",",$listIds);
		for($i=0;$i<count($arrIds);$i++) {
			//have to delete folders too
			if($arrIds[$i]!=0) {
				if(is_dir("../contents/events/".$arrIds[$i])) {
					$this->recurse_delete("../contents/events/".$arrIds[$i]);
					rmdir("../contents/events/".$arrIds[$i]);
				}
			}
		}
		
		mysql_query("DELETE FROM events_events WHERE event_id IN (".$listIds.")");
		mysql_query("DELETE FROM events_days WHERE event_id IN (".$listIds.")");
		
	}
	
	function recurse_delete($src) { 
		$dir = opendir($src); 
		while(false !== ( $file = @readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) { 
					
					
					$this->recurse_delete($src . '/' . $file); 
					rmdir($src . '/' . $file);
				} 
				else { 
					@unlink($src . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
	}
	
	function recurse_copy($src,$dst) { 
		$dir = opendir($src); 
		@mkdir($dst); 
		while(false !== ( $file = @readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) { 
					$this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
				} 
				else { 
					@copy($src . '/' . $file,$dst . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
	} 
	public function duplicateEvents($listIds) {
		
		$eventsQry = mysql_query("SELECT * FROM events_events WHERE event_id IN (".$listIds.")");
		while($eventRow=mysql_fetch_array($eventsQry)) {
			mysql_query("INSERT INTO events_events (calendar_id,event_title,event_date_from,event_date_to,event_starttime,event_endtime,event_endtime_flag,event_venue,event_entrance,event_seats,event_description,event_map,event_map_text,event_image,event_cover_visible,event_images,event_video,event_video_text,event_link,event_hashtag,event_flickr_search,event_site,event_free,event_active,event_page_title,event_metatag_title,event_metatag_description,event_metatag_keywords) VALUES(".$eventRow["calendar_id"].",'duplicate of ".mysql_real_escape_string($eventRow["event_title"])."','".$eventRow["event_date_from"]."','".$eventRow["event_date_to"]."','".$eventRow["event_starttime"]."', '".$eventRow["event_endtime"]."', '".$eventRow["event_endtime_flag"]."', '".mysql_real_escape_string($eventRow["event_venue"])."', '".mysql_real_escape_string($eventRow["event_entrance"])."', '".$eventRow["event_seats"]."', '".mysql_real_escape_string($eventRow["event_description"])."', '".mysql_real_escape_string($eventRow["event_map"])."', '".mysql_real_escape_string($eventRow["event_map_text"])."', '".$eventRow["event_image"]."', '".$eventRow["event_cover_visible"]."', '".$eventRow["event_images"]."', '".mysql_real_escape_string($eventRow["event_video"])."', '".mysql_real_escape_string($eventRow["event_video_text"])."', '".$eventRow["event_link"]."','".$eventRow["event_hashtag"]."','".mysql_real_escape_string($eventRow["event_flickr_search"])."', '".$eventRow["event_site"]."', '".$eventRow["event_free"]."',0,'".mysql_real_escape_string($eventRow["event_page_title"])."','".mysql_real_escape_string($eventRow["event_metatag_title"])."','".mysql_real_escape_string($eventRow["event_metatag_description"])."','".mysql_real_escape_string($eventRow["event_metatag_keywords"])."')");
			
			$last_id = mysql_insert_id();
			//duplicate daytimes
			mysql_query("INSERT INTO events_days(event_id,day_date,day_time_from,day_time_to,day_time_to_flag) SELECT '".$last_id."',day_date,day_time_from,day_time_to,day_time_to_flag FROM events_days WHERE event_id = ".$eventRow["event_id"]." ORDER BY day_time_from");
			
			//duplicate images on disk
			mkdir('../contents/events/'.$last_id);
			
			if(is_dir('../contents/events/'.$eventRow["event_id"])) {
				$this->recurse_copy('../contents/events/'.$eventRow["event_id"],'../contents/events/'.$last_id);
			}
			//copy('../contents/events/'.$eventRow["event_id"],'../contents/events/'.$last_id);
			
		}
		
	}
	
	public function insertEvent($settingObj,$func,$cover_image_from='') {
		if(isset($_POST["event_recurring"]) && $_POST["event_recurring"] == 1) {
			
			if(count($_POST["event_custom_dates"])>1 || $_POST["event_custom_dates"][0]!='') {
				$resultDate = array();
				$arrayDates = $_POST["event_custom_dates"];
				for($i=0;$i<count($arrayDates);$i++) {
					array_push($resultDate,str_replace(",","-",$arrayDates[$i]));
				}
			} else if(count($_POST["event_weekday"])<7 && count($_POST["event_weekday"])>0) {
				//special insert and update when recurring event
				
				/**********analyzing weekdays through date range*********/
				//separate day, month and year
				$arrDateFrom=explode(",",$_POST["event_date_from"]);
				if($_POST["event_date_to"]!='') {
					$arrDateTo=explode(",",$_POST["event_date_to"]);
				} else {
					$arrDateTo=explode(",",$_POST["event_date_from"]);
				}
				//get an int for the two dates
				$dateFrom=str_replace(",","",$_POST["event_date_from"]);
				if($_POST["event_date_to"]!='') {
					$dateTo=str_replace(",","",$_POST["event_date_to"]);
				} else {
					$dateTo=str_replace(",","",$_POST["event_date_from"]);
				}
				//loop over weekdays selected
				$resultDate = array();	
				
				for($i=0;$i<count($_POST["event_weekday"]);$i++) {
					
					$newdateFrom=$dateFrom;
					
					
					$year=$arrDateFrom[0];			
					$day = $arrDateFrom[2];
					$mo = $arrDateFrom[1];
					
					$date = strtotime(date('Y-m-d',mktime(0,0,0,$mo,$day,$year)));
					$weekday = date("N", $date);
					
					$j = 1;
					
					while ($weekday != $_POST["event_weekday"][$i] && date("Ymd",$date)<$dateTo) {
						
						$date=strtotime(date("Y-m-d", $date) . "+ 1 day");
						
						$weekday = date("N", $date);
						
					}
					
					if(date("N", $date) == $_POST["event_weekday"][$i]) {
						array_push($resultDate,date('Y-m-d',$date));
					}
					
					while ($newdateFrom <= $dateTo) {
						
						$test =  strtotime(date("Y-m-d", $date) . "+" . $j . " week");
						$j++;
						if(date("Ymd",$test) <= $dateTo) {
							array_push($resultDate,date("Y-m-d", $test));
						}
						
						$newdateFrom = date("Ymd",$test);
					}
					
				}
				
			}
			//manage first cover image
			$cover_image = "";
			if(isset($_FILES["event_image"]["tmp_name"]) && $_FILES["event_image"]["tmp_name"] != '') {
				if(move_uploaded_file($_FILES["event_image"]["tmp_name"], "../contents/events/temp/".str_replace(" ","",$_FILES["event_image"]["name"]))) {
						$cover_image = str_replace(" ","",$_FILES["event_image"]["name"]);
				}
			}
			//loop through dates
			for($j=0;$j<count($resultDate);$j++) {
				//check for free event
				$event_free =$_POST["event_free"];
				$event_entrance =$_POST["event_entrance"];
				//check date to and time to
				$event_date_to = $resultDate[$j];
				$event_date_from = $resultDate[$j];
				
				
				if($_POST["event_starttime"] == '') {
					$event_starttime = '';
				} else {
					$event_starttime = $_POST["event_starttime"].':00';
					
				}
				
				if($_POST["event_endtime"] == '') {
					$event_endtime = '';
					$endtime_flag = 1;
				} else {
					$event_endtime = $_POST["event_endtime"].':00';
					$endtime_flag = 0;
				}
				
				if(isset($_POST["event_cover_visible"]) && $_POST["event_cover_visible"] == 1) {
					$cover_visible = 1;
				} else {
					$cover_visible = 0;
				}
				
				//insert the event
				mysql_query("INSERT INTO events_events(calendar_id,event_title,event_date_from,event_date_to,event_starttime,event_endtime, event_endtime_flag,event_venue,event_entrance,event_seats,event_description,event_map,event_link,event_hashtag,event_flickr_search,event_site,event_free,event_active,event_cover_visible,event_video,event_video_text,event_map_text,event_page_title,event_metatag_title,event_metatag_description,event_metatag_keywords) 
							 VALUES(".$_POST["calendar_id"].",'".mysql_real_escape_string($_POST["event_title"])."','".str_replace(",","-",$event_date_from)."','".str_replace(",","-",$event_date_to)."','".$event_starttime."','".$event_endtime."',".$endtime_flag.",'".mysql_real_escape_string($_POST["event_venue"])."','".mysql_real_escape_string($event_entrance)."',".$_POST["event_seats"].",'".mysql_real_escape_string($_POST["event_description"])."','".mysql_real_escape_string($_POST["event_map"])."','".mysql_real_escape_string($_POST["event_link"])."','".mysql_real_escape_string($_POST["event_hashtag"])."','".mysql_real_escape_string($_POST["event_flickr_search"])."','".mysql_real_escape_string($_POST["event_site"])."',".$event_free.",".$_POST["event_active"].",".$cover_visible.",'".mysql_real_escape_string($_POST["event_video"])."','".mysql_real_escape_string($_POST["event_video_text"])."','".mysql_real_escape_string($_POST["event_map_text"])."','".mysql_real_escape_string($_POST["event_page_title"])."','".mysql_real_escape_string($_POST["event_metatag_title"])."','".mysql_real_escape_string($_POST["event_metatag_description"])."','".mysql_real_escape_string($_POST["event_metatag_keywords"])."')");
				$event_id=mysql_insert_id();
				
				if(isset($_POST["events_days"]) && $_POST["events_days"] == 1) {
					//custom times
					$arrayDays = $_POST["day_date"];
					for($i=0;$i<count($arrayDays);$i++) {
						
						$day_date = $_POST["day_date"][$i];
						if($day_date == $resultDate[$j]) {
							$day_time_from = $_POST["day_time_from"][$i];
							$day_time_to = $_POST["day_time_to"][$i];
							if($day_date != '' && $day_time_from!='') {
								if($day_time_to == '') {
									$day_time_to_flag = 1;
								} else {
									$day_time_to_flag = 0;
								}
								mysql_query("INSERT INTO events_days(event_id,day_date,day_time_from,day_time_to,day_time_to_flag) VALUES(".$event_id.",'".$day_date."','".$day_time_from.":00','".$day_time_to.":00',".$day_time_to_flag.")");
							}
						}
					}
				} 
				
				if($cover_image!='') {
					//create folder for the images of the event if exist, full & thumb
					if(!is_dir('../contents/events/'.$event_id.'/')) {
						mkdir('../contents/events/'.$event_id.'/');
					}
					
					if(!is_dir('../contents/events/'.$event_id.'/thumbs/')) {
						mkdir('../contents/events/'.$event_id.'/thumbs/');
					}
					
					
					list($width,$height) = getimagesize("../contents/events/temp/".str_replace(" ","",$_FILES["event_image"]["name"]));
					$newWidth = 250;
					$newHeight=250*$height/$width;
					$func->CreateThumbs("../contents/events/temp/".str_replace(" ","",$_FILES["event_image"]["name"]),"../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),$newWidth,$newHeight);
					
					$func->CreateThumbs("../contents/events/temp/".str_replace(" ","",$_FILES["event_image"]["name"]),"../contents/events/".$event_id."/thumbs/".str_replace(" ","",$_FILES["event_image"]["name"]),100,75);
					
					mysql_query("UPDATE events_events SET event_image = '".$cover_image."' WHERE event_id=".$event_id); 
					
				} else if($cover_image_from!='') {
					//create folder for the images of the event if exist, full & thumb
					if(!is_dir('../contents/events/'.$event_id.'/')) {
						mkdir('../contents/events/'.$event_id.'/');
					}
					
					if(!is_dir('../contents/events/'.$event_id.'/thumbs/')) {
						mkdir('../contents/events/'.$event_id.'/thumbs/');
					}
					
					
					list($width,$height) = getimagesize("../contents/events/temp/".$cover_image_from);
					$newWidth = 250;
					$newHeight=250*$height/$width;
					$func->CreateThumbs("../contents/events/temp/".$cover_image_from,"../contents/events/".$event_id."/".$cover_image_from,$newWidth,$newHeight);
					
					$func->CreateThumbs("../contents/events/temp/".$cover_image_from,"../contents/events/".$event_id."/thumbs/".$cover_image_from,100,75);
					
					mysql_query("UPDATE events_events SET event_image = '".$cover_image_from."' WHERE event_id=".$event_id); 
				}
				/*if(isset($_FILES["event_image"]["tmp_name"]) && $_FILES["event_image"]["tmp_name"] != '') {
					//create folder for the images of the event if exist, full & thumb
					if(!is_dir('../contents/events/'.$event_id.'/')) {
						mkdir('../contents/events/'.$event_id.'/');
					}
					
					if(!is_dir('../contents/events/'.$event_id.'/thumbs/')) {
						mkdir('../contents/events/'.$event_id.'/thumbs/');
					}
					
					//upload and resize image
					if(move_uploaded_file($_FILES["event_image"]["tmp_name"], "../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]))) {
						list($width,$height) = getimagesize("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]));
						$newWidth = 250;
						$newHeight=250*$height/$width;
						$func->CreateThumbs("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),"../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),$newWidth,$newHeight);
						
						$func->CreateThumbs("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),"../contents/events/".$event_id."/thumbs/".str_replace(" ","",$_FILES["event_image"]["name"]),100,75);
						
						mysql_query("UPDATE events_events SET event_image = '".str_replace(" ","",$_FILES["event_image"]["name"])."' WHERE event_id=".$event_id); 
					} 
				} else if(isset($_POST["old_event_image"]) && $_POST["old_event_image"]!= '') {
					if(!is_dir('../contents/events/'.$event_id.'/')) {
						mkdir('../contents/events/'.$event_id.'/');
					}
					
					if(!is_dir('../contents/events/'.$event_id.'/thumbs/')) {
						mkdir('../contents/events/'.$event_id.'/thumbs/');
					}
					mysql_query("UPDATE events_events SET event_image = '".$_POST["old_event_image"]."' WHERE event_id=".$event_id);
					
					copy('../contents/events/temp/'.$_POST["old_event_image"],'../contents/events/'.$event_id.'/'.$_POST["old_event_image"]);
					copy('../contents/events/temp/thumbs/'.$_POST["old_event_image"],'../contents/events/'.$event_id.'/thumbs/'.$_POST["old_event_image"]); 
					
					
				}*/
				
				if(isset($_POST["photos"]) && count($_POST["photos"])>0) {
					$listPhoto="0";
					$arrPhoto = $_POST["photos"];
					//manage photos
					if(!is_dir('../contents/events/'.$event_id.'/')) {
						mkdir('../contents/events/'.$event_id.'/');
					}
					
					if(!is_dir('../contents/events/'.$event_id.'/thumbs/')) {
						mkdir('../contents/events/'.$event_id.'/thumbs/');
					}
					
					for($i=0;$i<count($arrPhoto);$i++) {
						$func->CreateThumbs("../contents/events/temp/".$arrPhoto[$i],"../contents/events/".$event_id."/".$arrPhoto[$i],800,600);
						//crop thumbs
						$func->CreateCrop("../contents/events/temp/".$arrPhoto[$i],"../contents/events/".$event_id."/thumbs/".$arrPhoto[$i],100,100);
						
						//@unlink('../contents/events/temp/'.$arrPhoto[$i]);
						
						$listPhoto.="|".$arrPhoto[$i];
					}
					
					//update db
					mysql_query("UPDATE events_events SET event_images = '".$listPhoto."' WHERE event_id=".$event_id);
					
				}
				
			}
			
		} else {
			//normal insert
			
			//check for free event
			$event_free =$_POST["event_free"];
			$event_entrance =$_POST["event_entrance"];
			//check date to and time to
			if($_POST["event_date_to"] == '') {
				$event_date_to = $_POST["event_date_from"];
			} else {
				$event_date_to = $_POST["event_date_to"];
			}
			
			if($_POST["event_starttime"] == '') {
				$event_starttime = '';
			} else {
				$event_starttime = $_POST["event_starttime"].':00';
				
			}
			
			if($_POST["event_endtime"] == '') {
				$event_endtime = '';
				$endtime_flag = 1;
			} else {
				$event_endtime = $_POST["event_endtime"].':00';
				$endtime_flag = 0;
			}
			
			if(isset($_POST["event_cover_visible"]) && $_POST["event_cover_visible"] == 1) {
				$cover_visible = 1;
			} else {
				$cover_visible = 0;
			}
			
			//insert the event
			mysql_query("INSERT INTO events_events(calendar_id,event_title,event_date_from,event_date_to,event_starttime,event_endtime, event_endtime_flag,event_venue,event_entrance,event_seats,event_description,event_map,event_link,event_hashtag,event_flickr_search,event_site,event_free,event_active,event_cover_visible,event_video,event_video_text,event_map_text,event_page_title,event_metatag_title, event_metatag_description,event_metatag_keywords) 
						 VALUES(".$_POST["calendar_id"].",'".mysql_real_escape_string($_POST["event_title"])."','".str_replace(",","-",$_POST["event_date_from"])."','".str_replace(",","-",$event_date_to)."','".$event_starttime."','".$event_endtime."',".$endtime_flag.",'".mysql_real_escape_string($_POST["event_venue"])."','".mysql_real_escape_string($event_entrance)."',".$_POST["event_seats"].",'".mysql_real_escape_string($_POST["event_description"])."','".mysql_real_escape_string($_POST["event_map"])."','".mysql_real_escape_string($_POST["event_link"])."','".mysql_real_escape_string($_POST["event_hashtag"])."','".mysql_real_escape_string($_POST["event_flickr_search"])."','".mysql_real_escape_string($_POST["event_site"])."',".$event_free.",".$_POST["event_active"].",".$cover_visible.",'".mysql_real_escape_string($_POST["event_video"])."','".mysql_real_escape_string($_POST["event_video_text"])."','".mysql_real_escape_string($_POST["event_map_text"])."','".mysql_real_escape_string($_POST["event_page_title"])."','".mysql_real_escape_string($_POST["event_metatag_title"])."','".mysql_real_escape_string($_POST["event_metatag_description"])."','".mysql_real_escape_string($_POST["event_metatag_keywords"])."')");
			$event_id=mysql_insert_id();
			
			if(isset($_POST["events_days"]) && $_POST["events_days"] == 1) {
				//custom times
				$arrayDays = $_POST["day_date"];
				for($i=0;$i<count($arrayDays);$i++) {
					$day_date = $_POST["day_date"][$i];
					$day_time_from = $_POST["day_time_from"][$i];
					$day_time_to = $_POST["day_time_to"][$i];
					if($day_date != '' && $day_time_from!='') {
						if($day_time_to == '') {
							$day_time_to_flag = 1;
						} else {
							$day_time_to_flag = 0;
						}
						mysql_query("INSERT INTO events_days(event_id,day_date,day_time_from,day_time_to,day_time_to_flag) VALUES(".$event_id.",'".$day_date."','".$day_time_from.":00','".$day_time_to.":00',".$day_time_to_flag.")");
					}
				}
			} 
			
			if(isset($_FILES["event_image"]["tmp_name"]) && $_FILES["event_image"]["tmp_name"] != '') {
				//create folder for the images of the event if exist, full & thumb
				if(!is_dir('../contents/events/'.$event_id.'/')) {
					mkdir('../contents/events/'.$event_id.'/');
				}
				
				if(!is_dir('../contents/events/'.$event_id.'/thumbs/')) {
					mkdir('../contents/events/'.$event_id.'/thumbs/');
				}
				
				//upload and resize image
				if(move_uploaded_file($_FILES["event_image"]["tmp_name"], "../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]))) {
					list($width,$height) = getimagesize("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]));
					$newWidth = 250;
					$newHeight=250*$height/$width;
					$func->CreateThumbs("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),"../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),$newWidth,$newHeight);
					
					$func->CreateThumbs("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),"../contents/events/".$event_id."/thumbs/".str_replace(" ","",$_FILES["event_image"]["name"]),100,75);
					
					mysql_query("UPDATE events_events SET event_image = '".str_replace(" ","",$_FILES["event_image"]["name"])."' WHERE event_id=".$event_id); 
				}
			} 
			
			if(isset($_POST["photos"]) && count($_POST["photos"])>0) {
				$listPhoto="0";
				$arrPhoto = $_POST["photos"];
				//manage photos
				if(!is_dir('../contents/events/'.$event_id.'/')) {
					mkdir('../contents/events/'.$event_id.'/');
				}
				
				if(!is_dir('../contents/events/'.$event_id.'/thumbs/')) {
					mkdir('../contents/events/'.$event_id.'/thumbs/');
				}
				
				for($i=0;$i<count($arrPhoto);$i++) {
					$func->CreateThumbs("../contents/events/temp/".$arrPhoto[$i],"../contents/events/".$event_id."/".$arrPhoto[$i],800,600);
					//crop thumbs
					$func->CreateCrop("../contents/events/temp/".$arrPhoto[$i],"../contents/events/".$event_id."/thumbs/".$arrPhoto[$i],100,100);
					
					@unlink('../contents/events/temp/'.$arrPhoto[$i]);
					
					$listPhoto.="|".$arrPhoto[$i];
				}
				
				//update db
				mysql_query("UPDATE events_events SET event_images = '".$listPhoto."' WHERE event_id=".$event_id);
				
			}
		}
		if(is_dir("../contents/events/temp")) {
			$this->recurse_delete("../contents/events/temp");
		}
		
	}
	
	public function updateEvent($settingObj,$func) {
		if(isset($_POST["event_recurring"]) && $_POST["event_recurring"] == 1) {
			
			if(count($_POST["event_custom_dates"])>1 || $_POST["event_custom_dates"][0]!='') {
				//put images in temp folder first
				if(is_dir("../contents/events/".$_POST["event_id"])) {
					$this->recurse_copy("../contents/events/".$_POST["event_id"],"../contents/events/temp");
				}
				//get cover image
				$coverQry=mysql_query("SELECT event_image FROM events_events WHERE event_id=".$_POST["event_id"]);
				$cover_image_from = mysql_result($coverQry,0,'event_image');
				$this->insertEvent($settingObj,$func,$cover_image_from);
				$this->delEvents($_POST["event_id"]);
				
				
			} else if(count($_POST["event_weekday"])<7 && count($_POST["event_weekday"])>0) {
				//put images in temp folder first
				if(is_dir("../contents/events/".$_POST["event_id"])) {
					$this->recurse_copy("../contents/events/".$_POST["event_id"],"../contents/events/temp");
				}
				//get cover image
				$coverQry=mysql_query("SELECT event_image FROM events_events WHERE event_id=".$_POST["event_id"]);
				$cover_image_from = mysql_result($coverQry,0,'event_image');
				$this->insertEvent($settingObj,$func,$cover_image_from);
				$this->delEvents($_POST["event_id"]);
			} else {
				$event_id=$_POST["event_id"];
			
				if(isset($_POST["events_days"]) && $_POST["events_days"] == 1) {
					//delete old
					mysql_query("DELETE FROM events_days WHERE event_id = ".$event_id);
					//custom times
					$arrayDays = $_POST["day_date"];
					
					for($i=0;$i<count($arrayDays);$i++) {
						$day_date = $_POST["day_date"][$i];
						$day_time_from = $_POST["day_time_from"][$i];
						$day_time_to = $_POST["day_time_to"][$i];
						if($day_date != '' && $day_time_from!='') {
							if($day_time_to == '') {
								$day_time_to_flag = 1;
							} else {
								$day_time_to_flag = 0;
							}
							mysql_query("INSERT INTO events_days(event_id,day_date,day_time_from,day_time_to,day_time_to_flag) VALUES(".$event_id.",'".$day_date."','".$day_time_from.":00','".$day_time_to.":00',".$day_time_to_flag.")");
						}
					}//
				} else {
					//no custom times, delete old
					mysql_query("DELETE FROM events_days WHERE event_id = ".$event_id);
				}
				
				//manage photos
				if(!is_dir('../contents/events/'.$event_id.'/')) {
					mkdir('../contents/events/'.$event_id.'/');
				}
				
				if(!is_dir('../contents/events/'.$event_id.'/thumbs/')) {
					mkdir('../contents/events/'.$event_id.'/thumbs/');
				}
				if(isset($_POST["list_img_to_del"]) && $_POST["list_img_to_del"] != "0") {
					
					
					//there are photos to delete
					$arrImgToDel = explode("|",$_POST["list_img_to_del"]);
					for($i=1;$i<count($arrImgToDel);$i++) {
						
						@unlink('../contents/events/temp/'.$arrImgToDel[$i]);
						@unlink('../contents/events/'.$event_id.'/'.$arrImgToDel[$i]);
						@unlink('../contents/events/'.$event_id.'/thumbs/'.$arrImgToDel[$i]);
					}
					
				}
				
				//upload and resize image
				if(move_uploaded_file($_FILES["event_image"]["tmp_name"], "../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]))) {
					list($width,$height) = getimagesize("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]));
					$newWidth = 250;
					$newHeight=250*$height/$width;
					$func->CreateThumbs("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),"../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),$newWidth,$newHeight);
					$func->CreateThumbs("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),"../contents/events/".$event_id."/thumbs/".str_replace(" ","",$_FILES["event_image"]["name"]),100,75);
					
					//delete physical old images
					$imageQry = mysql_query("SELECT event_image FROM events_events WHERE event_id=".$event_id);
					
					if(mysql_result($imageQry,0,'event_image') != str_replace(" ","",$_FILES["event_image"]["name"])) {
						@unlink('../contents/events/'.$event_id.'/'.mysql_result($imageQry,0,'event_image'));
						@unlink('../contents/events/'.$event_id.'/thumbs/'.mysql_result($imageQry,0,'event_image'));
					}
					
					//update db with new photo
					mysql_query("UPDATE events_events SET event_image = '".str_replace(" ","",$_FILES["event_image"]["name"])."' WHERE event_id=".$event_id);
					
					
				}
					
					
				if(isset($_POST["photos"]) && count($_POST["photos"])>0) {
					$listPhoto="0";
					$arrPhoto = $_POST["photos"];
					
					
					for($i=0;$i<count($arrPhoto);$i++) {
						if(file_exists("../contents/events/temp/".$arrPhoto[$i])) {
							$func->CreateThumbs("../contents/events/temp/".$arrPhoto[$i],"../contents/events/".$event_id."/".$arrPhoto[$i],800,600);
							//crop thumb
							$func->CreateCrop("../contents/events/temp/".$arrPhoto[$i],"../contents/events/".$event_id."/thumbs/".$arrPhoto[$i],100,100);
							
							@unlink('../contents/events/temp/'.$arrPhoto[$i]);
						}
						$listPhoto.="|".$arrPhoto[$i];
					}
					mysql_query("UPDATE events_events SET event_images = '".$listPhoto."' WHERE event_id=".$event_id);
				} else {
					mysql_query("UPDATE events_events SET event_images = '' WHERE event_id=".$event_id);
				}
				
				//check for free event
				$event_free =$_POST["event_free"];
				$event_entrance =$_POST["event_entrance"];
				
				//check date to and time to
				if($_POST["event_date_to"] == '') {
					$event_date_to = $_POST["event_date_from"];
				} else {
					$event_date_to = $_POST["event_date_to"];
				}
				
				if($_POST["event_starttime"] == '') {
					$event_starttime = '';
					
				} else {
					$event_starttime = $_POST["event_starttime"].':00';
					
				}
				
				if($_POST["event_endtime"] == '') {
					$event_endtime = '';
					$endtime_flag = 1;
				} else {
					$event_endtime = $_POST["event_endtime"].':00';
					$endtime_flag = 0;
				}
				
				if(isset($_POST["event_cover_visible"]) && $_POST["event_cover_visible"] == 1) {
					$cover_visible = 1;
				} else {
					$cover_visible = 0;
				}
				//update other information
				
				mysql_query("UPDATE events_events
								SET event_title = '".mysql_real_escape_string($_POST["event_title"])."',
									event_date_from = '".str_replace(",","-",$_POST["event_date_from"])."',
									event_date_to = '".str_replace(",","-",$event_date_to)."',
									event_starttime = '".$event_starttime."',
									event_endtime = '".$event_endtime."',
									event_endtime_flag = ".$endtime_flag.",
									event_map = '".mysql_real_escape_string($_POST["event_map"])."',
									event_venue = '".mysql_real_escape_string($_POST["event_venue"])."',
									event_free = ".$event_free.",
									event_entrance = '".mysql_real_escape_string($event_entrance)."',
									event_seats = ".$_POST["event_seats"].",
									event_description = '".mysql_real_escape_string($_POST["event_description"])."',
									event_link = '".mysql_real_escape_string($_POST["event_link"])."',
									event_hashtag = '".mysql_real_escape_string($_POST["event_hashtag"])."',
									event_flickr_search = '".mysql_real_escape_string($_POST["event_flickr_search"])."',
									event_site = '".mysql_real_escape_string($_POST["event_site"])."',
									event_active = ".$_POST["event_active"].",
									event_cover_visible = ".$cover_visible.",
									event_video = '".mysql_real_escape_string($_POST["event_video"])."',
									event_video_text = '".mysql_real_escape_string($_POST["event_video_text"])."',
									event_map_text = '".mysql_real_escape_string($_POST["event_video_text"])."',
									event_page_title='".mysql_real_escape_string($_POST["event_page_title"])."',
									event_metatag_title='".mysql_real_escape_string($_POST["event_metatag_title"])."',
									event_metatag_description='".mysql_real_escape_string($_POST["event_metatag_description"])."',
									event_metatag_keywords='".mysql_real_escape_string($_POST["event_metatag_keywords"])."'
							  WHERE event_id = ".$event_id);
									
							  
				
			
			}
		} else {
			$event_id=$_POST["event_id"];
			
			if(isset($_POST["events_days"]) && $_POST["events_days"] == 1) {
				//delete old
				mysql_query("DELETE FROM events_days WHERE event_id = ".$event_id);
				//custom times
				$arrayDays = $_POST["day_date"];
				
				for($i=0;$i<count($arrayDays);$i++) {
					$day_date = $_POST["day_date"][$i];
					$day_time_from = $_POST["day_time_from"][$i];
					$day_time_to = $_POST["day_time_to"][$i];
					if($day_date != '' && $day_time_from!='') {
						if($day_time_to == '') {
							$day_time_to_flag = 1;
						} else {
							$day_time_to_flag = 0;
						}
						mysql_query("INSERT INTO events_days(event_id,day_date,day_time_from,day_time_to,day_time_to_flag) VALUES(".$event_id.",'".$day_date."','".$day_time_from.":00','".$day_time_to.":00',".$day_time_to_flag.")");
					}
				}//
			} else {
				//no custom times, delete old
				mysql_query("DELETE FROM events_days WHERE event_id = ".$event_id);
			}
			
			//manage photos
			if(!is_dir('../contents/events/'.$event_id.'/')) {
				mkdir('../contents/events/'.$event_id.'/');
			}
			
			if(!is_dir('../contents/events/'.$event_id.'/thumbs/')) {
				mkdir('../contents/events/'.$event_id.'/thumbs/');
			}
			if(isset($_POST["list_img_to_del"]) && $_POST["list_img_to_del"] != "0") {
				
				
				//there are photos to delete
				$arrImgToDel = explode("|",$_POST["list_img_to_del"]);
				for($i=1;$i<count($arrImgToDel);$i++) {
					
					@unlink('../contents/events/temp/'.$arrImgToDel[$i]);
					@unlink('../contents/events/'.$event_id.'/'.$arrImgToDel[$i]);
					@unlink('../contents/events/'.$event_id.'/thumbs/'.$arrImgToDel[$i]);
				}
				
			}
			
			//upload and resize image
			if(move_uploaded_file($_FILES["event_image"]["tmp_name"], "../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]))) {
				list($width,$height) = getimagesize("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]));
				$newWidth = 250;
				$newHeight=250*$height/$width;
				$func->CreateThumbs("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),"../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),$newWidth,$newHeight);
				$func->CreateThumbs("../contents/events/".$event_id."/".str_replace(" ","",$_FILES["event_image"]["name"]),"../contents/events/".$event_id."/thumbs/".str_replace(" ","",$_FILES["event_image"]["name"]),100,75);
				
				//delete physical old images
				$imageQry = mysql_query("SELECT event_image FROM events_events WHERE event_id=".$event_id);
				
				if(mysql_result($imageQry,0,'event_image') != str_replace(" ","",$_FILES["event_image"]["name"])) {
					@unlink('../contents/events/'.$event_id.'/'.mysql_result($imageQry,0,'event_image'));
					@unlink('../contents/events/'.$event_id.'/thumbs/'.mysql_result($imageQry,0,'event_image'));
				}
				
				//update db with new photo
				mysql_query("UPDATE events_events SET event_image = '".str_replace(" ","",$_FILES["event_image"]["name"])."' WHERE event_id=".$event_id);
				
				
			}
				
				
			if(isset($_POST["photos"]) && count($_POST["photos"])>0) {
				$listPhoto="0";
				$arrPhoto = $_POST["photos"];
				
				
				for($i=0;$i<count($arrPhoto);$i++) {
					if(file_exists("../contents/events/temp/".$arrPhoto[$i])) {
						$func->CreateThumbs("../contents/events/temp/".$arrPhoto[$i],"../contents/events/".$event_id."/".$arrPhoto[$i],800,600);
						//crop thumb
						$func->CreateCrop("../contents/events/temp/".$arrPhoto[$i],"../contents/events/".$event_id."/thumbs/".$arrPhoto[$i],100,100);
						
						@unlink('../contents/events/temp/'.$arrPhoto[$i]);
					}
					$listPhoto.="|".$arrPhoto[$i];
				}
				mysql_query("UPDATE events_events SET event_images = '".$listPhoto."' WHERE event_id=".$event_id);
			} else {
				mysql_query("UPDATE events_events SET event_images = '' WHERE event_id=".$event_id);
			}
			
			//check for free event
			$event_free =$_POST["event_free"];
			$event_entrance =$_POST["event_entrance"];
			
			//check date to and time to
			if($_POST["event_date_to"] == '') {
				$event_date_to = $_POST["event_date_from"];
			} else {
				$event_date_to = $_POST["event_date_to"];
			}
			
			if($_POST["event_starttime"] == '') {
				$event_starttime = '';
				
			} else {
				$event_starttime = $_POST["event_starttime"].':00';
				
			}
			
			if($_POST["event_endtime"] == '') {
				$event_endtime = '';
				$endtime_flag = 1;
			} else {
				$event_endtime = $_POST["event_endtime"].':00';
				$endtime_flag = 0;
			}
			
			if(isset($_POST["event_cover_visible"]) && $_POST["event_cover_visible"] == 1) {
				$cover_visible = 1;
			} else {
				$cover_visible = 0;
			}
			//update other information
			
			mysql_query("UPDATE events_events
							SET event_title = '".mysql_real_escape_string($_POST["event_title"])."',
								event_date_from = '".str_replace(",","-",$_POST["event_date_from"])."',
								event_date_to = '".str_replace(",","-",$event_date_to)."',
								event_starttime = '".$event_starttime."',
								event_endtime = '".$event_endtime."',
								event_endtime_flag = ".$endtime_flag.",
								event_map = '".mysql_real_escape_string($_POST["event_map"])."',
								event_venue = '".mysql_real_escape_string($_POST["event_venue"])."',
								event_free = ".$event_free.",
								event_entrance = '".mysql_real_escape_string($event_entrance)."',
								event_seats = ".$_POST["event_seats"].",
								event_description = '".mysql_real_escape_string($_POST["event_description"])."',
								event_link = '".mysql_real_escape_string($_POST["event_link"])."',
								event_hashtag = '".mysql_real_escape_string($_POST["event_hashtag"])."',
								event_flickr_search = '".mysql_real_escape_string($_POST["event_flickr_search"])."',
								event_site = '".mysql_real_escape_string($_POST["event_site"])."',
								event_active = ".$_POST["event_active"].",
								event_cover_visible = ".$cover_visible.",
								event_video = '".mysql_real_escape_string($_POST["event_video"])."',
								event_video_text = '".mysql_real_escape_string($_POST["event_video_text"])."',
								event_map_text = '".mysql_real_escape_string($_POST["event_video_text"])."',
								event_page_title='".mysql_real_escape_string($_POST["event_page_title"])."',
								event_metatag_title='".mysql_real_escape_string($_POST["event_metatag_title"])."',
								event_metatag_description='".mysql_real_escape_string($_POST["event_metatag_description"])."',
								event_metatag_keywords='".mysql_real_escape_string($_POST["event_metatag_keywords"])."'
						  WHERE event_id = ".$event_id);
								
						  
			
		}
	}
	
	

}

?>
