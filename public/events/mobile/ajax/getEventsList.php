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


$arrayEvents = $listObj->getEventsPerDayList($_GET["year"],$_GET["month"],$_GET["day"],$_GET["calendar_id"]);
$totEvents = count($arrayEvents);
$pag = $_GET["pag"];
$numperpag = 5;
$numpages=ceil($totEvents/$numperpag);
$from=($pag-1)*$numperpag;
if($numpages>1) {
	$arrayEvents=$listObj->getEventsPerDayList($_GET["year"],$_GET["month"],$_GET["day"],$_GET["calendar_id"],$from,$numperpag);
}

?>

<!-- pagination -->
<div class="m_list_buttons_container padding_small margin_t">
	<?php
	if($numpages>1) {
		if($pag>1) {
			?>
			
			<div class="m_event_link"><a class="m_page_list_nav_button m_page_list_button bg_black radius padding_small" href="javascript:getEventsList('<?php echo $_GET["year"]; ?>','<?php echo $_GET["month"]; ?>','<?php echo $_GET["day"]; ?>',<?php echo $calendarObj->getCalendarId(); ?>,<?php echo ($pag-1);?>);"><?php echo $lang["SEARCHEVENTS_PREV"]; ?></a></div>
			<?php		
		} else {
			?>
			
			<div class="m_event_link"><a class="m_page_list_nav_button m_page_list_button bg_grey radius padding_small"><?php echo $lang["SEARCHEVENTS_PREV"]; ?></a></div>
			<?php	
		}
		?>
		
		
		
		
		<?php
		if($pag<$numpages) {
			?>
			<div class="m_event_link"><a class="m_page_list_nav_button m_page_list_button bg_black radius padding_small" href="javascript:getEventsList('<?php echo $_GET["year"]; ?>','<?php echo $_GET["month"]; ?>','<?php echo $_GET["day"]; ?>',<?php echo $calendarObj->getCalendarId(); ?>,<?php echo ($pag+1); ?>);"><?php echo $lang["SEARCHEVENTS_NEXT"]; ?></a></div>
			
			
			<?php		
		} else {
			?>
			<div class="m_event_link"><a class="m_page_list_nav_button m_page_list_button bg_grey radius padding_small"><?php echo $lang["SEARCHEVENTS_NEXT"]; ?></a></div>
			
			<?php	
		}
		
	}
	?>
    <!-- close -->
    <div><a class="float_right bebas mark_grey" href="#" onclick="javascript:closeEvents(<?php echo $_GET["year"]; ?>,<?php echo $_GET["month"]; ?>,<?php echo $_GET["day"]; ?>,<?php echo $calendarObj->getCalendarId(); ?>);"><?php echo $lang["GETEVENTSLIST_CLOSE"]; ?>&nbsp; X</a></div>
    <div class="cleardiv"></div>
</div>

<div class="m_calendar_container">
    
    <div class="m_event_list_container bg_white">
        <!-- date -->
        <div class="event_date font_medium"><?php echo $_GET["day"]."&nbsp;".$weekdays[intval(date('w',mktime(0,0,0,$_GET["month"],$_GET["day"],$_GET["year"])))]; ?></div>
        <div class="m_line_divide"></div>
        
        <?php
		foreach($arrayEvents as $eventId => $event) {
			
		  ?>
            <!-- event -->
            <div class="m_single_event_list_container">
                <!-- photo -->
                <div class="float_left m_event_list_photo">
                	<?php
					if($event["event_image"] != '') {
						?>
						<a href="#!event_<?php echo $_GET["year"]; ?>_<?php echo $_GET["month"]; ?>_<?php echo $_GET["day"]; ?>_<?php echo $calendarObj->getCalendarId(); ?>_<?php echo $eventId; ?>_<?php echo $pag;?>_<?php echo urlencode($event["event_title"]); ?>" onclick="javascript:openEvent(<?php echo $_GET["year"]; ?>,<?php echo $_GET["month"]; ?>,<?php echo $_GET["day"]; ?>,<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $eventId; ?>,<?php echo $pag;?>);"><img src="contents/events/<?php echo $eventId; ?>/thumbs/<?php echo $event["event_image"]; ?>" alt="<?php echo $event["event_title"]; ?> " border="0"/></a>
						<?php
					}
					?>
                </div>
                <!-- information -->
                <div class="float_right m_event_list_information">
                    <!-- event title -->
                    <div class="font_medium"><?php echo $event["event_title"]; ?> </div>
                    <!-- event time -->
                    <!-- Start time info -->
              <?php
                  $arrayCustomTimes=$eventObj->getEventCustomTimes($eventId,$_GET["year"]."-".$_GET["month"]."-".$_GET["day"]);
                  if(count($arrayCustomTimes)>0) {
                      $i = 0;
                      foreach($arrayCustomTimes as $dayId =>$day) {
                          if($i == 0) {
                              //can see just first time
                              ?>
                              <div class="bg_grey padding_small margin_tb_small">
							  	<?php echo $lang["GETEVENT_START_TIME"]; ?>: 
                                <span style="font-weight: bold">
                                	<?php
									if($settingObj->getTimeFormat() == '24') {
										echo substr($day["day_time_from"],0,5);
									} else {
										echo date('h:i a',strtotime(substr($day["day_time_from"],0,5)));
									}	
									?>
                                </span>
                             </div>			
                              <?php
                          }
                          $i++;
                      }
                  } else {
					  if($event["event_starttime"]!='00:00:00') {
                      ?>
                      <div class="bg_grey padding_small margin_tb_small">
					  	<?php echo $lang["GETEVENT_START_TIME"]; ?>: 
                        <span style="font-weight: bold">
                        	<?php
							if($settingObj->getTimeFormat() == '24') {
								echo substr($event["event_starttime"],0,5);
							} else {
								echo date('h:i a',strtotime(substr($event["event_starttime"],0,5)));
							}	
							?>
                        </span>
                      </div>
                      <?php
					  } else {
						  ?>
                          <div class="bg_grey padding_small margin_tb_small"><?php echo $lang["GETEVENT_START_TIME"]; ?>: <span style="color: #333;">---</span></div>
                          <?php
					  }
                  }
                  ?>
                    
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
                    <div><a href="#!event_<?php echo $_GET["year"]; ?>_<?php echo $_GET["month"]; ?>_<?php echo $_GET["day"]; ?>_<?php echo $calendarObj->getCalendarId(); ?>_<?php echo $eventId; ?>_<?php echo $pag;?>_<?php echo urlencode($event["event_title"]); ?>" onclick="javascript:openEvent(<?php echo $_GET["year"]; ?>,<?php echo $_GET["month"]; ?>,<?php echo $_GET["day"]; ?>,<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $eventId; ?>,<?php echo $pag;?>);" class="bebas bg_black radius float_left mark_white padding_button_small"><?php echo $lang["GETEVENTSLIST_READ_MORE"]; ?></a></div>
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
		?>
        
        
        
    </div>

</div>


