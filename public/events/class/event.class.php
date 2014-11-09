<?php

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
	
	public function getEventMap() {
		return stripslashes(event::$eventQry["event_map"]);
	}
	
	public function getEventMapText() {
		return stripslashes(event::$eventQry["event_map_text"]);
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
	
	public function getPrevEvent($current,$year,$month,$daynum,$calendar_id) {
		if(strlen($month) == 1) {
			$month="0".$month;
		}
		if(strlen($daynum) == 1) {
			$daynum="0".$daynum;
		}
		$prev_event_id = 0;
		$this->setEvent($current);
		
		$eventQry=mysql_query("SELECT * FROM events_events WHERE event_date_from <= '".$year."-".$month."-".$daynum."'  AND event_date_to >= '".$year."-".$month."-".$daynum."' AND event_starttime <= '".$this->getEventStarttime()."' AND event_id != ".$current." AND calendar_id=".$calendar_id." ORDER BY event_starttime DESC LIMIT 1");
			
		
		
		if(mysql_num_rows($eventQry)>0) {
			$prev_event_id = mysql_result($eventQry,0,'event_id');
		} 
		
		return $prev_event_id;
	}
	
	public function getNextEvent($current,$year,$month,$daynum,$calendar_id) {
		if(strlen($month) == 1) {
			$month="0".$month;
		}
		if(strlen($daynum) == 1) {
			$daynum="0".$daynum;
		}
		$next_event_id = 0;
		$this->setEvent($current);
		
		$eventQry=mysql_query("SELECT * FROM events_events WHERE event_date_from <= '".$year."-".$month."-".$daynum."' AND event_date_to >= '".$year."-".$month."-".$daynum."' AND event_starttime >= '".$this->getEventStarttime()."' AND event_id != ".$current." AND calendar_id=".$calendar_id." ORDER BY event_starttime ASC LIMIT 1");
		
		if(mysql_num_rows($eventQry)>0) {
			$next_event_id = mysql_result($eventQry,0,'event_id');
		} 
		
		return $next_event_id;
	}
	
	public function getEventCustomTimes($event_id,$date) {
		$arrayTimes = Array();
		$customTimesQry = mysql_query("SELECT * FROM events_days WHERE event_id = ".$event_id." AND day_date = '".$date."' ORDER BY day_time_from");
		
		while($customTimesRow=mysql_fetch_array($customTimesQry)) {
			$arrayTimes[$customTimesRow["day_id"]]=Array();
			$arrayTimes[$customTimesRow["day_id"]]["day_date"] = $customTimesRow["day_date"];
			$arrayTimes[$customTimesRow["day_id"]]["day_time_from"] = $customTimesRow["day_time_from"];
			$arrayTimes[$customTimesRow["day_id"]]["day_time_to"] = $customTimesRow["day_time_to"];
			$arrayTimes[$customTimesRow["day_id"]]["day_time_to_flag"] = $customTimesRow["day_time_to_flag"];
		}
		return $arrayTimes;
	}
	
	public function getTwitterRequest() {
		if(file_exists('../contents/events/'.$this->getEventId().'/twitterSearch.json')) {
			$jsonSearch=file_get_contents('../contents/events/'.$this->getEventId().'/twitterSearch.json');
		} else {
			mysql_query("UPDATE events_twitter_time SET time_request = NOW()");
			$jsonSearch =file_get_contents('http://search.twitter.com/search.json?q='.urlencode($this->getEventHashtag()).'&include_entities=true&page=1&count=20');
			$search=fopen('../contents/events/'.$this->getEventId().'/twitterSearch.json', "w");
			fwrite($search,$jsonSearch);
			fclose($search);
		}
		//check last request on db
		$requestQry = mysql_query("SELECT * FROM events_twitter_time");
	
		$lastTime = mysql_result($requestQry,0,'time_request');
		$fiveminutes = date('Y-m-d H:i:s',time() - (60*5));
		
	
		
		if($lastTime < $fiveminutes) {
			
			
			mysql_query("UPDATE events_twitter_time SET time_request = NOW()");
			
			$jsonSearch =file_get_contents('http://search.twitter.com/search.json?q='.urlencode($this->getEventHashtag()).'&include_entities=true&page=1&count=20');
			//var_dump($jsonSearch);
			//save tweets
			$search=fopen('../contents/events/'.$this->getEventId().'/twitterSearch.json', "w");
			fwrite($search,$jsonSearch);
			fclose($search);
			
			
		}
		
		return $jsonSearch;

	}
	
	public function getTwitterRequestMobile() {
		if(file_exists('../../contents/events/'.$this->getEventId().'/twitterSearch.json')) {
			$jsonSearch=file_get_contents('../../contents/events/'.$this->getEventId().'/twitterSearch.json');
		} else {
			mysql_query("UPDATE events_twitter_time SET time_request = NOW()");
			$jsonSearch =file_get_contents('http://search.twitter.com/search.json?q='.urlencode($this->getEventHashtag()).'&include_entities=true&page=1&count=20');
			$search=fopen('../../contents/events/'.$this->getEventId().'/twitterSearch.json', "w");
			fwrite($search,$jsonSearch);
			fclose($search);
		}
		//check last request on db
		$requestQry = mysql_query("SELECT * FROM events_twitter_time");
	
		$lastTime = mysql_result($requestQry,0,'time_request');
		$fiveminutes = date('Y-m-d H:i:s',time() - (60*5));
		
	
		
		if($lastTime < $fiveminutes) {
			
			
			mysql_query("UPDATE events_twitter_time SET time_request = NOW()");
			
			$jsonSearch =file_get_contents('http://search.twitter.com/search.json?q='.urlencode($this->getEventHashtag()).'&include_entities=true&page=1&count=20');
			//var_dump($jsonSearch);
			//save tweets
			$search=fopen('../../contents/events/'.$this->getEventId().'/twitterSearch.json', "w");
			fwrite($search,$jsonSearch);
			fclose($search);
			
			
		}
		
		return $jsonSearch;

	}
	
	public function getFlickrRequest($api_key,$num) {
		
		$arrayPics = Array();
		if($num == 1) {
			if(!is_dir('../contents/events/'.$this->getEventId().'/flickr/')) {
				mkdir('../contents/events/'.$this->getEventId().'/flickr/');
			} else {
				$this->recurse_delete('../contents/events/'.$this->getEventId().'/flickr/');
			}
	    	$flickrSearch = file_get_contents("http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=".$api_key."&tags=".urlencode($this->getEventFlickrSearch())."&per_page=12&page=1&format=json&nojsoncallback=1");
			//save localfile
			if(!is_dir('../contents/events/'.$this->getEventId().'/flickr')) {
				mkdir('../contents/events/'.$this->getEventId().'/flickr');
			}
			$searchFlickr=fopen('../contents/events/'.$this->getEventId().'/flickr/flickrSearch.json',"w");
			fwrite($searchFlickr,$flickrSearch);
			fclose($searchFlickr);
		} else {
			$flickrSearch = file_get_contents('../contents/events/'.$this->getEventId().'/flickr/flickrSearch.json');
			
		}
		//two request for every pic
		$arraySearch=json_decode($flickrSearch);
		
		for($i=(($num-1)*4);$i<((($num-1)*4)+4);$i++) {
			//two request for measures and link
			$jsonSizes = json_decode(file_get_contents("http://api.flickr.com/services/rest/?method=flickr.photos.getSizes&api_key=".$api_key."&photo_id=".$arraySearch->photos->photo[$i]->id."&format=json&nojsoncallback=1"));
			
			$arrayPics[$arraySearch->photos->photo[$i]->id]=Array();
			$arrayPics[$arraySearch->photos->photo[$i]->id]["urlPic"] = stripslashes($jsonSizes->sizes->size[1]->source);
			
			/*$search=fopen('../contents/events/'.$this->getEventId().'/flickr/flickrSizes_'.$arraySearch->photos->photo[$i]->id.'.json', "w");
			fwrite($search,$jsonSizes);
			fclose($search);*/
			
			$jsonDetails = json_decode(file_get_contents("http://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=".$api_key."&photo_id=".$arraySearch->photos->photo[$i]->id."&format=json&nojsoncallback=1"));
			$arrayPics[$arraySearch->photos->photo[$i]->id]["linkPic"] = stripslashes($jsonDetails->photo->urls->url[0]->_content);
			
			
			/*$search=fopen('../contents/events/'.$this->getEventId().'/flickr/flickrDetails_'.$arraySearch->photos->photo[$i]->id.'.json', "w");
			fwrite($search,$jsonDetails);
			fclose($search);*/
			
		}
		//var_dump($arrayPics);
		return $arrayPics;
	}
	
	public function getFlickrRequestMobile($api_key,$num) {
		
		$arrayPics = Array();
		if($num == 1) {
			if(!is_dir('../../contents/events/'.$this->getEventId().'/flickr/')) {
				mkdir('../../contents/events/'.$this->getEventId().'/flickr/');
			} else {
				$this->recurse_delete('../../contents/events/'.$this->getEventId().'/flickr/');
			}
	    	$flickrSearch = file_get_contents("http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=".$api_key."&tags=".urlencode($this->getEventFlickrSearch())."&per_page=12&page=1&format=json&nojsoncallback=1");
			//save localfile
			if(!is_dir('../../contents/events/'.$this->getEventId().'/flickr')) {
				mkdir('../../contents/events/'.$this->getEventId().'/flickr');
			}
			$searchFlickr=fopen('../../contents/events/'.$this->getEventId().'/flickr/flickrSearch.json',"w");
			fwrite($searchFlickr,$flickrSearch);
			fclose($searchFlickr);
		} else {
			$flickrSearch = file_get_contents('../../contents/events/'.$this->getEventId().'/flickr/flickrSearch.json');
			
		}
		//two request for every pic
		$arraySearch=json_decode($flickrSearch);
		
		for($i=(($num-1)*4);$i<((($num-1)*4)+4);$i++) {
			//two request for measures and link
			$jsonSizes = json_decode(file_get_contents("http://api.flickr.com/services/rest/?method=flickr.photos.getSizes&api_key=".$api_key."&photo_id=".$arraySearch->photos->photo[$i]->id."&format=json&nojsoncallback=1"));
			
			$arrayPics[$arraySearch->photos->photo[$i]->id]=Array();
			$arrayPics[$arraySearch->photos->photo[$i]->id]["urlPic"] = stripslashes($jsonSizes->sizes->size[1]->source);
			
			/*$search=fopen('../contents/events/'.$this->getEventId().'/flickr/flickrSizes_'.$arraySearch->photos->photo[$i]->id.'.json', "w");
			fwrite($search,$jsonSizes);
			fclose($search);*/
			
			$jsonDetails = json_decode(file_get_contents("http://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=".$api_key."&photo_id=".$arraySearch->photos->photo[$i]->id."&format=json&nojsoncallback=1"));
			$arrayPics[$arraySearch->photos->photo[$i]->id]["linkPic"] = stripslashes($jsonDetails->photo->urls->url[0]->_content);
			
			
			/*$search=fopen('../contents/events/'.$this->getEventId().'/flickr/flickrDetails_'.$arraySearch->photos->photo[$i]->id.'.json', "w");
			fwrite($search,$jsonDetails);
			fclose($search);*/
			
		}
		//var_dump($arrayPics);
		return $arrayPics;
	}
	
	function recurse_delete($src) { 
		@$dir = opendir($src); 
		while(false !== ( $file = @readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) { 
					$this->recurse_delete($src . '/' . $file); 
				} 
				else { 
					@unlink($src . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
	} 

}

?>
