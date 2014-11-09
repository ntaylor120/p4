<?php

class setting {
	
	private function doSettingQuery($setting) {
		$settingQry = mysql_query("SELECT * FROM events_config WHERE config_name='".$setting."'");
		return mysql_result($settingQry,0,'config_value');
	}
	
	public function getTimezone() {
		return setting::doSettingQuery('timezone');
	}
	
	public function getSiteDomain() {
		return setting::doSettingQuery('site_domain');
	}
	
	public function getEventsPopupEnabled() {
		return setting::doSettingQuery('events_popup_enabled');
	}
	
	public function getDateFormat() {
		return setting::doSettingQuery('date_format');
	}
	
	public function getTimeFormat() {
		return setting::doSettingQuery('time_format');
	}
	
	public function getTwitterApprovation() {
		return setting::doSettingQuery('twitter_approvation');
	}
	
	public function getTwitterEmail() {
		return setting::doSettingQuery('twitter_email');
	}
	
	public function getFlickrApiKey() {
		return setting::doSettingQuery('flickr_api_key');
	}
	
	public function getEventsDefaultView() {
		return setting::doSettingQuery('default_view');
	}
	
	public function getListViewOption() {
		return setting::doSettingQuery('list_view_option');
	}
	
	public function getMobile() {
		return setting::doSettingQuery('mobile');
	}
	
	public function getShowCalendarSelection() {
		return setting::doSettingQuery('show_calendar_selection');
	}
	
	public function getShowSearchBox() {
		return setting::doSettingQuery('show_search_box');
	}
	
	public function getShowViewButtons() {
		return setting::doSettingQuery('show_view_buttons');
	}
	
	public function updateSettings() {
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["timezone"]."'
					 WHERE config_name='timezone'");
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["site_domain"]."'
					 WHERE config_name='site_domain'");
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["events_popup_enabled"]."'
					 WHERE config_name='events_popup_enabled'");
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["date_format"]."'
					 WHERE config_name='date_format'");
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["time_format"]."'
					 WHERE config_name='time_format'");
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["twitter_approvation"]."'
					 WHERE config_name='twitter_approvation'");
		if($_POST["twitter_approvation"] == 1) {
			mysql_query("UPDATE events_config
						 SET config_value='".$_POST["twitter_email"]."'
						 WHERE config_name='twitter_email'");
		}
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["flickr_api_key"]."'
					 WHERE config_name='flickr_api_key'");
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["default_view"]."'
					 WHERE config_name='default_view'");
		if($_POST["default_view"] == '0') {
			mysql_query("UPDATE events_config
					 SET config_value='".$_POST["list_view_option"]."'
					 WHERE config_name='list_view_option'");
			
		}
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["mobile"]."'
					 WHERE config_name='mobile'");
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["show_calendar_selection"]."'
					 WHERE config_name='show_calendar_selection'");
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["show_search_box"]."'
					 WHERE config_name='show_search_box'");
		mysql_query("UPDATE events_config
					 SET config_value='".$_POST["show_view_buttons"]."'
					 WHERE config_name='show_view_buttons'");
		
	
	}
	/***METATAGS SECTION***/
	public function getPageTitle() {
		return stripslashes(setting::doSettingQuery('page_title'));
	}
	
	public function getMetatagTitle() {
		return stripslashes(setting::doSettingQuery('metatag_title'));
	}
	
	public function getMetatagDescription() {
		return stripslashes(setting::doSettingQuery('metatag_description'));
	}
	
	public function getMetatagKeywords() {
		return stripslashes(setting::doSettingQuery('metatag_keywords'));
	}
	
	public function updateMetatags() {
		mysql_query("UPDATE events_config
					 SET config_value='".mysql_real_escape_string($_POST["page_title"])."'
					 WHERE config_name='page_title'");
		mysql_query("UPDATE events_config
					 SET config_value='".mysql_real_escape_string($_POST["metatag_title"])."'
					 WHERE config_name='metatag_title'");
		mysql_query("UPDATE events_config
					 SET config_value='".mysql_real_escape_string($_POST["metatag_description"])."'
					 WHERE config_name='metatag_description'");
		mysql_query("UPDATE events_config
					 SET config_value='".mysql_real_escape_string($_POST["metatag_keywords"])."'
					 WHERE config_name='metatag_keywords'");
		
	}

}

?>