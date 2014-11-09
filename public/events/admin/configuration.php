<?php
include 'common.php';
if(!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
	header('Location: login.php');
}
if(isset($_POST["timezone"])) {	
	$settingObj->updateSettings();
	header('Location: welcome.php');	
}

$arrayTimezones = $listObj->getTimezonesList();
include 'include/header.php';
?>

<script>
	function showListViewOptions(val) {
		if(val==0) {
			$('#list_view_options').css("display","block");
		} else {
			$('#list_view_options').css("display","none");
		}
	}
	
	$(function() {
		showListViewOptions(<?php echo $settingObj->getEventsDefaultView(); ?>);
	});
</script>
<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
        <?php
        include 'include/menu.php'; 
        ?>
        <div id="form_container">
        	<form name="editsettings" action="" method="post" tmt:validate="true" enctype="multipart/form-data">           
                
                <div id="label_input">
                    <div class="label_title"><label for="site_domain"><?php echo $lang["CONFIGURATION_SITE_DOMAIN_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_SITE_DOMAIN_SUBLABEL"]; ?></div>
                </div>
                <div id="input_box">
                    <input type="text" class="long_input_box" id="site_domain" name="site_domain" value="<?php echo $settingObj->getSiteDomain(); ?>">
                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <div class="label_title"><label for="timezone"><?php echo $lang["CONFIGURATION_TIMEZONE_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_TIMEZONE_SUBLABEL"]; ?></div>
                </div>
                <div id="input_box">
                	<select name="timezone" id="timezone">
                    	<?php
						foreach($arrayTimezones as $timezoneid => $timezone) {
						?>
                        	<option value="<?php echo $timezone["timezone_value"]; ?>" <?php if(trim($settingObj->getTimezone()) == trim($timezone["timezone_value"])) { echo 'selected="selected"'; }?>><?php echo $timezone["timezone_name"]; ?></option>
						<?php
						}
						?>
                    </select>
                  
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                <div id="label_input">
                    <div class="label_title"><label for="date_format"><?php echo $lang["CONFIGURATION_DATE_FORMAT_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_DATE_FORMAT_SUBLABEL"]; ?></div>
                </div>
                <div id="input_box">
                   <select name="date_format" style="width:300px">
                   	 <option value="UK" <?php if($settingObj->getDateFormat()=="UK") { echo "selected"; }?>><?php echo $lang["CONFIGURATION_DATE_FORMAT_UK"]; ?></option>
                     <option value="US" <?php if($settingObj->getDateFormat()=="US") { echo "selected"; }?>><?php echo $lang["CONFIGURATION_DATE_FORMAT_US"]; ?></option>
                     <option value="EU" <?php if($settingObj->getDateFormat()=="EU") { echo "selected"; }?>><?php echo $lang["CONFIGURATION_DATE_FORMAT_EU"]; ?></option>
                   </select>
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                <!-- 
                =======================
                === Time format ==
                =======================
                -->
                <div id="label_input">
                    <div class="label_title"><label for="time_format"><?php echo $lang["CONFIGURATION_TIME_FORMAT_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_TIME_FORMAT_SUBLABEL"]; ?></div>
                </div>
                <div id="input_box">
                   <select name="time_format" style="width:300px">
                   	 <option value="12" <?php if($settingObj->getTimeFormat()=="12") { echo "selected"; }?>><?php echo $lang["CONFIGURATION_TIME_FORMAT_12"]; ?></option>
                     <option value="24" <?php if($settingObj->getTimeFormat()=="24") { echo "selected"; }?>><?php echo $lang["CONFIGURATION_TIME_FORMAT_24"]; ?></option>
                   </select>
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                <script>
					function toggleEmailField(approvation) {
						if(approvation == 1) {
							$('#approvation_email').fadeIn();
						} else {
							$('#approvation_email').fadeOut();
						}
					}
					$(function() {
						<?php 
						if($settingObj->getTwitterApprovation() == 1) {
							?>
							$('#approvation_email').fadeIn();
							<?php
						}
						?>
					});
				</script>
                <div id="label_input">
                    <div class="label_title"><label for="twitter_approvation"><?php echo $lang["CONFIGURATION_TWITTER_APPROVATION_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_TWITTER_APPROVATION_SUBLABEL"]; ?></div>
                </div>
                <div id="input_box">
                   <select name="twitter_approvation" onchange="javascript:toggleEmailField(this.options[this.selectedIndex].value);">
                   	 <option value="1" <?php if($settingObj->getTwitterApprovation()=="1") { echo "selected"; }?>><?php echo $lang["YES"]; ?></option>
                     <option value="0" <?php if($settingObj->getTwitterApprovation()=="0") { echo "selected"; }?>><?php echo $lang["NO"]; ?></option>
                   </select>
                   <div id="approvation_email" style="display:none">
                   		<?php echo $lang["CONFIGURATION_TWITTER_EMAIL_LABEL"]; ?>&nbsp;<input type="text" name="twitter_email" value="<?php echo $settingObj->getTwitterEmail(); ?>" />
                   </div>
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <div class="label_title"><label for="flickr_api_key">Flickr API Key</label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_FLICKR_API_KEY_SUBLABEL1"]; ?> <a href="http://www.flickr.com/services/apps/create/apply" target="_blank">Flickr API Key</a> <?php echo $lang["CONFIGURATION_FLICKR_API_KEY_SUBLABEL2"]; ?></div>
                </div>
                <div id="input_box">
                	 <input type="text" class="long_input_box" id="flickr_api_key" name="flickr_api_key" value="<?php echo $settingObj->getFlickrApiKey(); ?>">
                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                <!-- 
                =======================
                === events popup ==
                =======================
                -->
                <div id="label_input">
                    <div class="label_title"><label for="events_popup_enabled"><?php echo $lang["CONFIGURATION_EVENTS_POPUP_ENABLED_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_EVENTS_POPUP_ENABLED_SUBLABEL"]; ?></div>
                </div>
                <div id="rowspace"></div>
                <div >
                	<input type="radio" name="events_popup_enabled" id="events_popup_enabled" value="1" <?php if($settingObj->getEventsPopupEnabled() == 1) { echo "checked"; } ?>/>&nbsp;<?php echo $lang["CONFIGURATION_EVENTS_POPUP_ENABLED_ENABLED"]; ?>&nbsp;&nbsp;<input type="radio" name="events_popup_enabled" id="events_popup_enabled" value="0" <?php if($settingObj->getEventsPopupEnabled() == 0) { echo "checked"; } ?>/>&nbsp;<?php echo $lang["CONFIGURATION_EVENTS_POPUP_ENABLED_DISABLED"]; ?>
                </div>
                
                
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <!-- 
                ===================
                === default view ==
                ===================
                -->
                <div id="label_input">
                    <div class="label_title"><label for="default_view"><?php echo $lang["CONFIGURATION_EVENTS_DEFAULT_VIEW_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_EVENTS_DEFAULT_VIEW_SUBLABEL"]; ?></div>
                </div>
                <div id="rowspace"></div>
                <div >
                	<input type="radio" name="default_view" id="default_view" value="1" <?php if($settingObj->getEventsDefaultView() == 1) { echo "checked"; } ?>  onclick="javascript:showListViewOptions(1);"/>&nbsp;<?php echo $lang["CONFIGURATION_EVENTS_DEFAULT_VIEW_CALENDAR"]; ?>&nbsp;&nbsp;<input type="radio" name="default_view" id="default_view" value="0" <?php if($settingObj->getEventsDefaultView() == 0) { echo "checked"; } ?> onclick="javascript:showListViewOptions(0);"/>&nbsp;<?php echo $lang["CONFIGURATION_EVENTS_DEFAULT_VIEW_LIST"]; ?>
                    <div id="list_view_options" style="display:none">
                    	<?php echo $lang["CONFIGURATION_LIST_VIEW_OPTION_LABEL"]; ?>&nbsp;
                        <select name="list_view_option">
                        	<option value="future" <?php if($settingObj->getListViewOption() == "future") { echo "selected"; } ?>><?php echo $lang["CONFIGURATION_LIST_VIEW_OPTION_FUTURE"]; ?></option>
                            <option value="past" <?php if($settingObj->getListViewOption() == "past") { echo "selected"; } ?>><?php echo $lang["CONFIGURATION_LIST_VIEW_OPTION_PAST"]; ?></option>
                            <option value="all" <?php if($settingObj->getListViewOption() == "all") { echo "selected"; } ?>><?php echo $lang["CONFIGURATION_LIST_VIEW_OPTION_ALL"]; ?></option>
                        </select>
                    	
                    </div>
                </div>
                
                
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <!-- 
                =======================
                === show calendar selection ==
                =======================
                -->
                <div id="label_input">
                    <div class="label_title"><label for="show_calendar_selection"><?php echo $lang["CONFIGURATION_SHOW_CALENDAR_SELECTION_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_SHOW_CALENDAR_SELECTION_SUBLABEL"]; ?></div>
                </div>
                <div id="rowspace"></div>
                <div >
                	<input type="radio" name="show_calendar_selection" id="show_calendar_selection" value="1" <?php if($settingObj->getShowCalendarSelection() == 1) { echo "checked"; } ?>/>&nbsp;<?php echo $lang["CONFIGURATION_SHOW_CALENDAR_SELECTION_YES"]; ?>&nbsp;&nbsp;<input type="radio" name="show_calendar_selection" id="show_calendar_selection" value="0" <?php if($settingObj->getShowCalendarSelection() == 0) { echo "checked"; } ?>/>&nbsp;<?php echo $lang["CONFIGURATION_SHOW_CALENDAR_SELECTION_NO"]; ?>
                </div>
                
                
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <!-- 
                =======================
                === show search box ==
                =======================
                -->
                <div id="label_input">
                    <div class="label_title"><label for="show_search_box"><?php echo $lang["CONFIGURATION_SHOW_SEARCH_BOX_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_SHOW_SEARCH_BOX_SUBLABEL"]; ?></div>
                </div>
                <div id="rowspace"></div>
                <div >
                	<input type="radio" name="show_search_box" id="show_search_box" value="1" <?php if($settingObj->getShowSearchBox() == 1) { echo "checked"; } ?>/>&nbsp;<?php echo $lang["CONFIGURATION_SHOW_SEARCH_BOX_YES"]; ?>&nbsp;&nbsp;<input type="radio" name="show_search_box" id="show_search_box" value="0" <?php if($settingObj->getShowSearchBox() == 0) { echo "checked"; } ?>/>&nbsp;<?php echo $lang["CONFIGURATION_SHOW_SEARCH_BOX_NO"]; ?>
                </div>
                
                
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <!-- 
                =======================
                === show view buttons ==
                =======================
                -->
                <div id="label_input">
                    <div class="label_title"><label for="show_view_buttons"><?php echo $lang["CONFIGURATION_SHOW_VIEW_BUTTONS_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_SHOW_VIEW_BUTTONS_SUBLABEL"]; ?></div>
                </div>
                <div id="rowspace"></div>
                <div >
                	<input type="radio" name="show_view_buttons" id="show_view_buttons" value="1" <?php if($settingObj->getShowViewButtons() == 1) { echo "checked"; } ?>/>&nbsp;<?php echo $lang["CONFIGURATION_SHOW_VIEW_BUTTONS_YES"]; ?>&nbsp;&nbsp;<input type="radio" name="show_view_buttons" id="show_view_buttons" value="0" <?php if($settingObj->getShowViewButtons() == 0) { echo "checked"; } ?>/>&nbsp;<?php echo $lang["CONFIGURATION_SHOW_VIEW_BUTTONS_NO"]; ?>
                </div>
                
                
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <!-- 
                =======================
                === mobile ==
                =======================
                -->
                <div id="label_input">
                    <div class="label_title"><label for="mobile"><?php echo $lang["CONFIGURATION_MOBILE_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["CONFIGURATION_MOBILE_SUBLABEL"]; ?></div>
                </div>
                <div id="rowspace"></div>
                <div >
                	<input type="radio" name="mobile" id="mobile" value="1" <?php if($settingObj->getMobile() == 1) { echo "checked"; } ?>/>&nbsp;<?php echo $lang["CONFIGURATION_MOBILE_YES"]; ?>&nbsp;&nbsp;<input type="radio" name="mobile" id="mobile" value="0" <?php if($settingObj->getMobile() == 0) { echo "checked"; } ?>/>&nbsp;<?php echo $lang["CONFIGURATION_MOBILE_NO"]; ?>
                </div>
                
                
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <!-- bridge buttons -->
                <div class="bridge_buttons_container">
                    <!-- cancel -->
                    <div class="admin_button cancel_button" ><a href="javascript:document.location.href='welcome.php';"></a></div>
                    
                    <!-- save -->
                    <div class="admin_button" style="margin-left:750px;"><input type="submit" id="apply_button" style="background-color: #333;" name="saveunpublish" value=""></div>
                    
                </div>
                <div id="rowspace"></div>
             </form>
            
         </div>
        
        
        </div>
    </div>
</div>
<?php 
include 'include/footer.php';
?>