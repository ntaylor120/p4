<?php 
/******************************************************/
/******** SECTION 1 TO EMBED AT THE TOP OF YOUR PAGE **/
/******************************************************/
include 'common.php';

if(!isset($_GET["calendar_id"])) {
	$result = $calendarObj->getDefaultCalendar();
} else {
	$result = $calendarObj->setCalendar($_GET["calendar_id"]);
}

/**************************/
/******** END SECTION 1 ***/
/**************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
/******************************************************/
/******** SECTION 2 TO EMBED IN HEAD TAG OF YOUR PAGE */
/******************************************************/
?>
<?php

if($is_mobile && $settingObj->getMobile() == '1') {
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="<?php echo $settingObj->getMetatagTitle(); ?>" name="title">
	<meta content="<?php echo $settingObj->getMetatagDescription(); ?>" name="description">
	<meta content="<?php echo $settingObj->getMetatagKeywords(); ?>" name="keywords">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" />
	
	
	<title><?php echo $settingObj->getPageTitle(); ?></title>
	
	<link type="text/css" rel="stylesheet" href="mobile/css/style.css" />
	
	<script language="javascript" type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery-ui-1.8.16.custom.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery.easing.1.3.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery.easing.compatibility.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery.bxSlider.min.js"></script>
	
	<script language="javascript" type="text/javascript" src="js/jquery.lightbox-0.5.js"></script>
	<script language="javascript" type="text/javascript" src="js/tmt_libs/tmt_core.js"></script>
	<script language="javascript" type="text/javascript" src="js/tmt_libs/tmt_form.js"></script>
	<script language="javascript" type="text/javascript" src="js/tmt_libs/tmt_validator.js"></script>
	<script language="javascript" type="text/javascript" src="mobile/js/wachevents.calendar.js"></script>
	<?php 
} else {
	?>
	<title><?php echo $settingObj->getPageTitle(); ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="<?php echo $settingObj->getMetatagTitle(); ?>" name="title">
	<meta content="<?php echo $settingObj->getMetatagDescription(); ?>" name="description">
	<meta content="<?php echo $settingObj->getMetatagKeywords(); ?>" name="keywords">
	
	<link rel="stylesheet" href="css/mainstyle.css" type="text/css" />
	<link rel="stylesheet" href="css/jquery.lightbox-0.5.css" type="text/css" />
	
	<!--[if IE 7]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" />
	<![endif]-->
	<!--[if IE 8]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" />
	<![endif]-->
	
	
	<script language="javascript" type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery-ui-1.8.16.custom.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery.easing.1.3.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery.easing.compatibility.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery.bxSlider.min.js"></script>
	
	<script language="javascript" type="text/javascript" src="js/jquery.lightbox-0.5.js"></script>
	<script language="javascript" type="text/javascript" src="js/tmt_libs/tmt_core.js"></script>
	<script language="javascript" type="text/javascript" src="js/tmt_libs/tmt_form.js"></script>
	<script language="javascript" type="text/javascript" src="js/tmt_libs/tmt_validator.js"></script>
	<script language="javascript" type="text/javascript" src="js/wachevents.calendar.js"></script>
	<?php
}
?>
<?php
/**************************/
/******** END SECTION 2 ***/
/**************************/
?>
</head>

<body>
<?php
/******************************************************/
/******** SECTION 3 TO EMBED IN BODY TAG OF YOUR PAGE */
/******************************************************/
?>
<?php
if($is_mobile && $settingObj->getMobile() == '1') {
	?>
	<div class="m_container_all">
		<!-- ===============================================================
			js
		================================================================ -->
		
		<script language="javascript" type="text/javascript">
			var today= new Date();
			var currentMonth;
			var currentYear = today.getFullYear();
			var pageX;
			var pageY;
			$(function() {
				setTimeout(function() { window.scrollTo(0, 1) }, 100);
				<?php
				if(isset($_GET["_escaped_fragment_"]) && $_GET["_escaped_fragment_"]!='') {
					?>
					queryStr = '<?php echo $_GET["_escaped_fragment_"];?>';
					arrParam = queryStr.split("_");
					openEvent(arrParam[1],arrParam[2],arrParam[3],arrParam[4],arrParam[5],arrParam[6]);
					getMonthName(arrParam[2]);
					<?php
				} else {
				?>
				//check pathname to load events from external link
				var pathname = window.location.toString();
				var n = pathname.indexOf("!");
				var queryStr = "";
				if(n >=0) {
					queryStr=pathname.substring((n+1),pathname.length);
					arrParam = queryStr.split("_");
					
					openEvent(arrParam[1],arrParam[2],arrParam[3],arrParam[4],arrParam[5],arrParam[6]);
					getMonthName(arrParam[2]);
				}  else {
					<?php
					if($settingObj->getEventsDefaultView() == '1') {
						?>
						getMonthCalendar((today.getMonth()+1),today.getFullYear(),'<?php echo $calendarObj->getCalendarId(); ?>');
						<?php
					} else {
						?>
						getMonthName(today.getMonth()+1);
						//getMonthCalendar((today.getMonth()+1),today.getFullYear(),'<?php echo $calendarObj->getCalendarId(); ?>');
						getEventsHomeList(<?php echo $calendarObj->getCalendarId(); ?>,1,'<?php echo $settingObj->getListViewOption(); ?>');
						<?php
					}
					?>
					//getMonthCalendar((today.getMonth()+1),today.getFullYear(),'<?php echo $calendarObj->getCalendarId(); ?>');
					$('#calendar_id').val('<?php echo $calendarObj->getCalendarId(); ?>');
				}
				<?php 
				}
				?>
				$("#search_field").keyup(function(event){
					if(event.keyCode == 13){
						$("#search_button").click();
					}
				});
				$('#search_button').bind('click',function() {
					searchEventsList($('#search_field').val(),$('#calendar_id').val(),1) ;
					$('#search_field_container').fadeOut();
					$('#calendar_container_all').fadeIn();
					$('#month_nav').fadeIn();
					$('#calendar_select').fadeIn();
					$('#view_buttons').fadeIn();
				});
				
			  
			});
			function openSearch() {
				if($('#search_field_container').css("display")=="none") {
					$('#search_field_container').fadeIn();
					$('#calendar_container_all').fadeOut();
					$('#month_nav').fadeOut();
					$('#calendar_select').fadeOut();
					$('#view_buttons').fadeOut();
				} else {
					$('#search_field_container').fadeOut();
					$('#calendar_container_all').fadeIn();
					$('#month_nav').fadeIn();
					$('#calendar_select').fadeIn();
					$('#view_buttons').fadeIn();
				}
			}
			function getMonthName(month) {
				var m = new Array();
				m[0] ="<?php echo $lang["JANUARY"]; ?>";
				m[1] ="<?php echo $lang["FEBRUARY"]; ?>";
				m[2] ="<?php echo $lang["MARCH"]; ?>";
				m[3] ="<?php echo $lang["APRIL"]; ?>";
				m[4] ="<?php echo $lang["MAY"]; ?>";
				m[5] ="<?php echo $lang["JUNE"]; ?>";
				m[6] ="<?php echo $lang["JULY"]; ?>";
				m[7] ="<?php echo $lang["AUGUST"]; ?>";
				m[8] ="<?php echo $lang["SEPTEMBER"]; ?>";
				m[9] ="<?php echo $lang["OCTOBER"]; ?>";
				m[10] ="<?php echo $lang["NOVEMBER"]; ?>";
				m[11] ="<?php echo $lang["DECEMBER"]; ?>";
				$('#month_name').html(m[(month-1)]);
				currentMonth = month;
			}
			
			function setCalendar(calendar_id) {
				getMonthCalendar((today.getMonth()+1),today.getFullYear(),calendar_id);
				$('#calendar_id').val(calendar_id);
			}
			
		</script>
		<!-- ========= TOP ==================== -->
		<div class="m_top_container">
		
			<!-- month, navigation, filter -->
			<div class="m_month_navigation_container float_left" id="calendar_navigation">
			
				<!-- month -->
				<div class="m_month_container float_left bg_black" id="month_label">
					<div class="month_name mark_white bebas font_medium" id="month_name"></div>
					<div class="month_year bebas mark_white font_small" id="month_year"></div>
				</div>
				<!-- navigation -->
				<div id="month_nav" class="m_month_nav_container float_left">
					<div id="month_nav_prev"><a class="m_month_nav_button float_left bg_black" href="javascript:getPreviousMonth(<?php echo $calendarObj->getCalendarId(); ?>);"><img src="mobile/images/prev.png" /></a></div>
					<div id="month_nav_next"><a class="m_month_nav_button float_left bg_black" href="javascript:getNextMonth(<?php echo $calendarObj->getCalendarId(); ?>);"><img src="mobile/images/next.png" /></a></div>
				</div>
				
				<div class="cleardiv"></div> 
				
                <?php
				if($settingObj->getShowViewButtons() == 1) {
				?>
				<!-- type view -->   
				<div id="view_buttons" class="view_type_container">
					<div class="view_type_button float_left"><a id="view_calendar" class="mark_white bg_black" style="background-color:#567BD2" href="javascript:getCalendarView((today.getMonth()+1),today.getFullYear(),$('#calendar_id').val());"><?php echo $lang["VIEW_CALENDAR"]; ?></a></div>
					<div class="view_type_button float_left"><a id="view_list" class="mark_white bg_black" href="javascript:getEventsHomeList($('#calendar_id').val(),1,'<?php echo $settingObj->getListViewOption(); ?>');" style="background-color: rgb(51, 51, 51);"><?php echo $lang["VIEW_LIST"]; ?></a></div>
					<div class="cleardiv"></div>
				</div>
				<?php
				}
				?>
				<div class="cleardiv"></div> 
                <input type="hidden" name="calendar_id" id="calendar_id" value="" /> 
				<?php
				if($settingObj->getShowCalendarSelection() == 1) {
				?>
				<!-- filter -->
				<div id="calendar_select" class="select_calendar_container float_left">
					
					<?php
					$arrayCalendars = $listObj->getCalendarsList('ORDER BY calendar_order');
					if(count($arrayCalendars)>0) {
						?>
						<select name="calendar" onchange="javascript:setCalendar(this.options[this.selectedIndex].value);">
							<?php
							foreach($arrayCalendars as $calendarId => $calendar) {
								?>
								<option value="<?php echo $calendarId; ?>" <?php if($calendarId == $calendarObj->getCalendarId()) { echo "selected"; }?>><?php echo $calendar["calendar_title"]; ?></option>
								<?php
								}
								?>
							</select>
							<?php
						}
						?>
				</div>     
				<?php
				}
				?>
			</div>
			
			<?php
			if($settingObj->getShowSearchBox() == 1) {
			?>
			
			<!-- search -->
			<div class="m_search_container float_right" id="search_event_field">
				<a href="javascript:openSearch();"><img src="mobile/images/search.png" /></a>
				
			</div>
			<?php
			}
			?>
		   
			
			<div class="cleardiv"></div>
		
		</div>
		<!-- end top container -->
		
		<!-- search input  -->
		<div id="search_field_container" class="margin_tb" style="display:none">
			<div class="m_search_input_button_container float_left"><input type="button" id="search_button" class="margin_t bg_black mark_white m_search_input_button" value="<?php echo $lang["SEARCH"]?>" /></div>
			<div class="search_input_container float_left"><input type="text" name="search_field" id="search_field" class="m_search_input bg_grey"/></div>
		</div>
		
		<!-- ========= END TOP ==================== -->
		
		
		
		<!-- ========= CALENDAR ==================== -->
		
		
		<div class="m_calendar_container" id="calendar_container_all">
		
			<!-- days name -->
			<div id="name_days_container" class="name_days_container">
				<?php
				if($settingObj->getDateFormat() == "UK" || $settingObj->getDateFormat() == "EU") {
					?>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["MONDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["TUESDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["WEDNESDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["THURSDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["FRIDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["SATURDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name" style="margin-right: 0px;"><?php echo $lang["SUNDAY_M"]; ?></div>
					<?php
				} else {
					?>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["SUNDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["MONDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["TUESDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["WEDNESDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["THURSDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name"><?php echo $lang["FRIDAY_M"]; ?></div>
					<div class="bebas mark_grey float_left font_small day_name" style="margin-right: 0px;"><?php echo $lang["SATURDAY_M"]; ?></div>
					<?php
				}
				?>
				
			</div>
			
			<!-- calendar -->
			<div id="calendar_container" class="days_container_all">
				
					
			</div>
			<div class="events_container_all" id="events_container" style="display:none">
			
		   
			 </div>
			  <div class="events_container_all" id="event_container" style="display:none">
			
		   
			 </div>
			 <div class="events_container_all" id="events_home_container" style="display:none">
			
		   
			 </div>
			 <div class="cleardiv"></div>
			
			
			
		</div>
		
		
	
	</div>
	
	<!-- ===============================================================
		events calendar ends here
	================================================================ -->
	
	<!-- preloader -->
	<div id="modal_loading" class="modal_loading" style="display:none">
		<img src="images/loading.png" border=0 />
	</div>
	<?php
} else {
	?>
	<!-- ===============================================================
		js
	================================================================ -->
	
	<script language="javascript" type="text/javascript">
		var today= new Date();
		var currentMonth;
		var currentYear = today.getFullYear();
		var pageX;
		var pageY;
		
		$(function() {
			<?php
			if(isset($_GET["_escaped_fragment_"]) && $_GET["_escaped_fragment_"]!='') {
				?>
				queryStr = '<?php echo $_GET["_escaped_fragment_"];?>';
				arrParam = queryStr.split("_");
				openEvent(arrParam[1],arrParam[2],arrParam[3],arrParam[4],arrParam[5],arrParam[6]);
				getMonthName(arrParam[2]);
				<?php
			} else {
			?>
			//check pathname to load events from external link
			var pathname = window.location.toString();
			var n = pathname.indexOf("!");
			var queryStr = "";
			if(n >=0) {
				queryStr=pathname.substring((n+1),pathname.length);
				arrParam = queryStr.split("_");
				
				openEvent(arrParam[1],arrParam[2],arrParam[3],arrParam[4],arrParam[5],arrParam[6]);
				getMonthName(arrParam[2]);
			}  else {
				<?php
				if($settingObj->getEventsDefaultView() == '1') {
					?>
					getMonthCalendar((today.getMonth()+1),today.getFullYear(),'<?php echo $calendarObj->getCalendarId(); ?>');
					<?php
				} else {
					?>
					getMonthName(today.getMonth()+1);
					//getMonthCalendar((today.getMonth()+1),today.getFullYear(),'<?php echo $calendarObj->getCalendarId(); ?>');
					getEventsHomeList(<?php echo $calendarObj->getCalendarId(); ?>,1,'<?php echo $settingObj->getListViewOption(); ?>');
					<?php
				}
				?>
				$('#calendar_id').val('<?php echo $calendarObj->getCalendarId(); ?>');
				
			}
			<?php 
			}
			?>
			$("#search_field").keyup(function(event){
				if(event.keyCode == 13){
					$("#search_button").click();
				}
			});
			$('#search_button').bind('click',function() {
				searchEventsList($('#search_field').val(),$('#calendar_id').val(),1) ;
			});
			
		});
		function getMonthName(month) {
			var m = new Array();
			m[0] ="<?php echo $lang["JANUARY"]; ?>";
			m[1] ="<?php echo $lang["FEBRUARY"]; ?>";
			m[2] ="<?php echo $lang["MARCH"]; ?>";
			m[3] ="<?php echo $lang["APRIL"]; ?>";
			m[4] ="<?php echo $lang["MAY"]; ?>";
			m[5] ="<?php echo $lang["JUNE"]; ?>";
			m[6] ="<?php echo $lang["JULY"]; ?>";
			m[7] ="<?php echo $lang["AUGUST"]; ?>";
			m[8] ="<?php echo $lang["SEPTEMBER"]; ?>";
			m[9] ="<?php echo $lang["OCTOBER"]; ?>";
			m[10] ="<?php echo $lang["NOVEMBER"]; ?>";
			m[11] ="<?php echo $lang["DECEMBER"]; ?>";
			$('#month_name').html(m[(month-1)]);
			currentMonth = month;
		}
		
		function setCalendar(calendar_id) {
			getMonthCalendar((today.getMonth()+1),today.getFullYear(),calendar_id);
			$('#calendar_id').val(calendar_id);
		}
		
	</script>
	
	<!-- ===============================================================
		box preview available time slots
	================================================================ -->
	
	<div class="box_preview_container_all" id="box_slots" style="display:none">
		<div class="box_preview_title" id="popup_title"><?php echo $lang["INDEX_EVENTS_PREVIEW"]; ?></div>
		<div class="box_preview_events_container" id="events_popup">
			
		</div>
	</div>
	
	
	<!-- ===============================================================
		events calendar begins here
	================================================================ -->
	<div class="main_container" id="container_all">
		
		<!-- =======================================
			header (month + navigation + select)
		======================================== -->
		<div class="header_container">
			<!-- month and navigation -->
			<div class="month_container_all">
				<!-- month -->
				<div class="month_container">
					<div class="font_custom month_name" id="month_name"></div>
					<div class="font_custom month_year" id="month_year"></div>
				</div>
				<!-- navigation -->
				<div class="month_nav_container" id="month_nav">
					<div class="mont_nav_button_container" id="month_nav_prev"><a href="javascript:getPreviousMonth(<?php echo $calendarObj->getCalendarId(); ?>);" class="month_nav_button month_nav_button_prev"></a></div>
					<div class="mont_nav_button_container" id="month_nav_next"><a href="javascript:getNextMonth(<?php echo $calendarObj->getCalendarId(); ?>);" class="month_nav_button month_nav_button_next"></a></div>
				</div>
				<div class="cleardiv"></div>
                <?php
				if($settingObj->getShowViewButtons()==1) {
				?>
				<!-- view type -->
				<div class="view_type_container" id="view_buttons">
					<div class="view_type_button"><a href="javascript:getCalendarView((today.getMonth()+1),today.getFullYear(),$('#calendar_id').val());" style="background-color:#567BD2" id="view_calendar"><?php echo $lang["VIEW_CALENDAR"]; ?></a></div>
					<div class="view_type_button"><a href="javascript:getEventsHomeList($('#calendar_id').val(),1,'<?php echo $settingObj->getListViewOption(); ?>');" id="view_list"><?php echo $lang["VIEW_LIST"]; ?></a></div>
					<div class="cleardiv"></div>
				</div>
				<?php
				}
				?>
			</div>
			<input type="hidden" name="calendar_id" id="calendar_id" value="" />
			<div class="select_search_container">
            	<?php
				if($settingObj->getShowCalendarSelection() == 1) {
				?>
				<!-- select -->
				<div class="select_container_all">
					
					<div class="select_calendar_container" id="calendar_select">
					<?php
					$arrayCalendars = $listObj->getCalendarsList('ORDER BY calendar_order');
					if(count($arrayCalendars)>0) {
						?>
						<select name="calendar" onchange="javascript:setCalendar(this.options[this.selectedIndex].value);">
							<?php
							foreach($arrayCalendars as $calendarId => $calendar) {
								?>
								<option value="<?php echo $calendarId; ?>" <?php if($calendarId == $calendarObj->getCalendarId()) { echo "selected"; }?>><?php echo $calendar["calendar_title"]; ?></option>
								<?php
								}
								?>
							</select>
							<?php
						}
						?>
					</div>
				
					<!-- select message -->
					<div class="select_calendar_message" id="calendar_select_label"><?php echo $lang["SELECT_CALENDAR"]; ?></div>
					<div class="cleardiv"></div>
					
				</div>
                <?php
				}
				?>
				<?php
				if($settingObj->getShowSearchBox()==1) {
				?>
				<!-- search -->
				<div class="search_container"  id="search_event_field">
					<div class="search_input_button"><input type="button" id="search_button" class="preview_event_button readmore_button" value="<?php echo $lang["SEARCH"]?>" /></div>
					<div class="search_input"><input type="text" name="search_field" id="search_field" style="background:#FFFFFF;width:150px;float:left;margin-right:10px"/></div>
					<!-- select message -->
					<div class="select_calendar_message" id="search_event_label" ><?php echo $lang["SEARCH_EVENT"]; ?></div>
				
				</div>
				<?php
				}
				?>
			</div>
		</div>
		
		<div class="cleardiv"></div>
		
		
		<!-- =======================================
			calendar
		======================================== -->
		<div class="calendar_container_all">
			<!-- days name -->
			<div class="name_days_container" id="name_days_container">
				<?php
				if($settingObj->getDateFormat() == "UK" || $settingObj->getDateFormat() == "EU") {
					?>
					<div class="font_custom day_name"><?php echo $lang["MONDAY"]; ?></div>
					<div class="font_custom day_name"><?php echo $lang["TUESDAY"]; ?></div>
					<div class="font_custom day_name"><?php echo $lang["WEDNESDAY"]; ?></div>
					<div class="font_custom day_name"><?php echo $lang["THURSDAY"]; ?></div>
					<div class="font_custom day_name"><?php echo $lang["FRIDAY"]; ?></div>
					<div class="font_custom day_name"><?php echo $lang["SATURDAY"]; ?></div>
					<div class="font_custom day_name" style="margin-right: 0px;"><?php echo $lang["SUNDAY"]; ?></div>
					<?php
				} else {
					?>
					<div class="font_custom day_name"><?php echo $lang["SUNDAY"]; ?></div>
					<div class="font_custom day_name"><?php echo $lang["MONDAY"]; ?></div>
					<div class="font_custom day_name"><?php echo $lang["TUESDAY"]; ?></div>
					<div class="font_custom day_name"><?php echo $lang["WEDNESDAY"]; ?></div>
					<div class="font_custom day_name"><?php echo $lang["THURSDAY"]; ?></div>
					<div class="font_custom day_name"><?php echo $lang["FRIDAY"]; ?></div>
					<div class="font_custom day_name" style="margin-right: 0px;"><?php echo $lang["SATURDAY"]; ?></div>
					<?php
				}
				?>
				<div class="cleardiv"></div>
			</div>
			
			<!-- days -->
			<div class="days_container_all" id="calendar_container">
				
				
			</div>
			 <div class="events_container_all" id="events_container" style="display:none">
			
		   
			 </div>
			  <div class="events_container_all" id="event_container" style="display:none">
			
		   
			 </div>
			 <div class="events_container_all" id="events_home_container" style="display:none">
			
		   
			 </div>
			 <div class="cleardiv"></div>
		</div>
		
	   <div class="cleardiv"></div>
	   
	   
	</div>
	
	
	<!-- ===============================================================
		events calendar ends here
	================================================================ -->
	
	<!-- preloader -->
	<div id="modal_loading" class="modal_loading" style="display:none">
		<img src="images/loading.png" border=0 />
	</div>
	<?php
}
?>

<?php
/**************************/
/******** END SECTION 3 ***/
/**************************/
?>
</body>
</html>
    

