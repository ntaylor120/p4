<?php
include '../common.php';
$calendarObj->setCalendar($_GET["calendar_id"]);

//preparing week days
$weekdays=Array();
$weekdays[0] = $lang["SUNDAY"];
$weekdays[1] = $lang["MONDAY"];
$weekdays[2] = $lang["TUESDAY"];
$weekdays[3] = $lang["WEDNESDAY"];
$weekdays[4] = $lang["THURSDAY"];
$weekdays[5] = $lang["FRIDAY"];
$weekdays[6] = $lang["SATURDAY"];


$arrayEvents = $listObj->getEventsHomeList($_GET["year"],$_GET["month"],$_GET["calendar_id"],$_GET["filter"],$settingObj->getDateFormat());
$totEvents = count($arrayEvents);
$pag = $_GET["pag"];
$numperpag = 5;
$numpages=ceil($totEvents/$numperpag);
$from=($pag-1)*$numperpag;
if($numpages>1) {
	$arrayEvents=$listObj->getEventsHomeList($_GET["year"],$_GET["month"],$_GET["calendar_id"],$_GET["filter"],$settingObj->getDateFormat(),$from,$numperpag);
}

?>


    
<!-- close -->
<div class="m_list_buttons_container padding_small margin_t">
	<!-- pagination -->
	<?php
	if($numpages>1) {
		if($pag>1) {
			?>
			
			<div class="event_link"><a class="m_page_list_nav_button m_page_list_button bg_black radius padding_small" href="javascript:getEventsHomeList(<?php echo $calendarObj->getCalendarId(); ?>,<?php echo ($pag-1);?>,'<?php echo $_GET["filter"]; ?>');"><?php echo $lang["SEARCHEVENTS_PREV"]; ?></a></div>
			<?php		
		} else {
			?>
			<div class="event_link"><a class="m_page_list_nav_button m_page_list_button bg_grey radius padding_small"><?php echo $lang["SEARCHEVENTS_PREV"]; ?></a></div>
			<?php	
		}
		?>
		
		
		
		
		<?php
		if($pag<$numpages) {
			?>
			<div class="event_link"><a class="m_page_list_nav_button m_page_list_button bg_black radius padding_small" href="javascript:getEventsHomeList(<?php echo $calendarObj->getCalendarId(); ?>,<?php echo ($pag+1); ?>,'<?php echo $_GET["filter"]; ?>');"><?php echo $lang["SEARCHEVENTS_NEXT"]; ?></a></div>
			
			
			<?php		
		} else {
			?>
			<div class="event_link"><a class="m_page_list_nav_button m_page_list_button bg_grey radius padding_small"><?php echo $lang["SEARCHEVENTS_NEXT"]; ?></a></div>
			
			<?php	
		}
		
	}
	?>
    <div>
        <a class="float_right bebas mark_grey" href="#" onclick="javascript:closeHomeEvents(<?php echo $_GET["year"]; ?>,<?php echo $_GET["month"]; ?>,<?php echo $calendarObj->getCalendarId(); ?>);"><?php echo $lang["GETEVENTSLIST_CLOSE"]; ?>&nbsp; X</a>
    </div>
    <div class="cleardiv"></div>
</div>


<div class="cleardiv"></div>
<div class="m_calendar_container">
	<div class="m_event_list_container bg_white">
    	<!-- filters -->
        <div class="event_filters_container">
            <select name="filter" onchange="javascript:getEventsHomeList(<?php echo $calendarObj->getCalendarId(); ?>,1,this.options[this.selectedIndex].value);">
                <option value="future" <?php if($_GET["filter"] == 'future') { echo 'selected'; } ?>><?php echo $lang["EVENTSHOMELIST_FUTURE"]; ?></option>
                <option value="past" <?php if($_GET["filter"] == 'past') { echo 'selected'; } ?>><?php echo $lang["EVENTSHOMELIST_PAST"]; ?></option>
                <option value="all" <?php if($_GET["filter"] == 'all') { echo 'selected'; } ?>><?php echo $lang["EVENTSHOMELIST_ALL"]; ?></option>
            </select>
        </div>
        
        
        
        <?php
		if(count($arrayEvents)>0) {
			foreach($arrayEvents as $eventId => $event) {
				//get date elements separately
				$arrDateFrom = explode('-',$event["event_date_from"]);
				
			  ?>
				<!-- event -->
				<div class="m_single_event_list_container">
					<!-- photo -->
					<div class="float_left m_event_list_photo">
						<?php
						if($event["event_image"] != '') {
							?>
                            <a href="#!event_<?php echo $arrDateFrom[0]; ?>_<?php echo $arrDateFrom[1]; ?>_<?php echo $arrDateFrom[2]; ?>_<?php echo $calendarObj->getCalendarId(); ?>_<?php echo $eventId; ?>_<?php echo $pag; ?>_<?php echo urlencode($event["event_title"]); ?>" onclick="javascript:openHomeEvent(<?php echo $arrDateFrom[0]; ?>,<?php echo $arrDateFrom[1]; ?>,<?php echo $arrDateFrom[2]; ?>,<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $eventId; ?>,<?php echo $pag; ?>,'<?php echo $_GET["filter"]; ?>');"><img src="contents/events/<?php echo $eventId; ?>/thumbs/<?php echo $event["event_image"]; ?>" alt="<?php echo $event["event_title"]; ?> " border="0"/></a>
							<?php
						}
						?>
					</div>
                    
					<!-- information -->
					<div class="float_right m_event_list_information">
						<!-- event title -->
						<div class="font_medium"><?php echo $event["event_title"]; ?> </div>
						<!--date info, in search no time info is displayed-->
                  		<div class="bg_grey padding_small margin_tb_small"><span style="color: #666;font-weight: bold;"><?php echo $event["date_from"]; ?></span>-<span style="color: #666;font-weight: bold;"><?php echo $event["date_to"]; ?></span></div>
						<!-- admission -->
						<?php
						  if($event["event_free"] == -1) {
							  ?>
							  <div class="bg_grey padding_small margin_tb_small"><?php echo $lang["EVENTPOPUP_ADMISSION"]; ?>: <span style="font-weight: bold"><?php echo $lang["EVENTPOPUP_WITH_FEE"]; ?></span></div>
							  <?php			
						  } else if($event["event_free"] == 1) {
							  ?>
							  <div class="bg_grey padding_small margin_tb_small"><?php echo $lang["EVENTPOPUP_ADMISSION"]; ?>: <span style="font-weight: bold"><?php echo $lang["EVENTPOPUP_FREE"]; ?></span></div>
							  <?php
						  }
						  ?>  
						
						<!-- tickets -->
						<?php
						  if($event["event_seats"] != -1) {
							  ?>
							  <div class="bg_grey padding_small margin_tb_small"><?php echo $lang["GETEVENTSLIST_AVAILABLE_SEATS"]; ?>: <span style="font-weight: bold"><?php echo $event["event_seats"]; ?></span></div>
							  <?php			
						  } 
						  ?> 
						
					</div>
					<div class="cleardiv"></div>
					<!-- description -->
					<div class="margin_tb">
					  <?php
					  if(strlen($event["event_description"]) > 200) {
						  echo strip_tags(substr($event["event_description"],0,200)."...");
					  } else {
						  echo strip_tags($event["event_description"]);
					  }
					  ?>
				 </div>
				 
				   
					<!-- buttons -->
					
					<div class="m_list_buttons_container">
						<div><a href="#!event_<?php echo $arrDateFrom[0]; ?>_<?php echo $arrDateFrom[1]; ?>_<?php echo $arrDateFrom[2]; ?>_<?php echo $calendarObj->getCalendarId(); ?>_<?php echo $eventId; ?>_<?php echo $pag; ?>_<?php echo urlencode($event["event_title"]); ?>" onclick="javascript:openHomeEvent(<?php echo $arrDateFrom[0]; ?>,<?php echo $arrDateFrom[1]; ?>,<?php echo $arrDateFrom[2]; ?>,<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $eventId; ?>,<?php echo $pag; ?>,'<?php echo $_GET["filter"]; ?>');" class="bebas bg_black radius float_left mark_white padding_button_small"><?php echo $lang["GETEVENTSLIST_READ_MORE"]; ?></a></div>
						<?php
						if($event["event_link"]!='') {
							?>
							<div><a class="bebas bg_green radius float_left mark_white padding_button_small margin_l" id="buytickets_button" href="<?php echo $event["event_link"]; ?>" target="_blank"><?php echo $lang["GETEVENT_BUY_TICKETS"]; ?></a></div>
							<?php
						}
						?>
						<div class="cleardiv"></div>
					 </div>
					
					<div class="cleardiv"></div>
					
				</div>
				
				<div class="m_line_divide margin_tb"></div>
			
			<?php
			}
		}  else {
		?>
        <div class="font_custom events_title"><span style="text-align:center"><?php echo $lang["EVENTSHOMELIST_NO_RESULTS"]; ?></span></div>
        
        <?php
		}
	
	
		?>
        
        
        
    </div>