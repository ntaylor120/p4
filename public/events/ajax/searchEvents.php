<?php
include '../common.php';
$calendarObj->setCalendar($_GET["calendar_id"]);
$arrayEvents=$listObj->searchEvents($_GET["text"],$_GET["calendar_id"],$settingObj->getDateFormat());
$totEvents = count($arrayEvents);
$pag = $_GET["pag"];
$numperpag = 5;
$numpages=ceil($totEvents/$numperpag);
$from=($pag-1)*$numperpag;
if($numpages>1) {
	$arrayEvents=$listObj->searchEvents($_GET["text"],$_GET["calendar_id"],$settingObj->getDateFormat(),$from,$numperpag);
}

?>
<div class="event_nav_buttons_container">
	<?php
	if($numpages>1) {
		if($pag>1) {
			?>
			<div class="event_link"><a class="page_list_nav_button page_list_button" href="javascript:searchEventsList('<?php echo $_GET["text"]; ?>',<?php echo $calendarObj->getCalendarId(); ?>,1);"><?php echo $lang["SEARCHEVENTS_FIRST"]; ?></a></div>
			<div class="event_link"><a class="page_list_nav_button page_list_button" href="javascript:searchEventsList('<?php echo $_GET["text"]; ?>',<?php echo $calendarObj->getCalendarId(); ?>,<?php echo ($pag-1);?>);"><?php echo $lang["SEARCHEVENTS_PREV"]; ?></a></div>
			<?php		
		} else {
			?>
			<div class="event_link"><a class="page_list_nav_button_disabled page_list_button"><?php echo $lang["SEARCHEVENTS_FIRST"]; ?></a></div>
			<div class="event_link"><a class="page_list_nav_button_disabled page_list_button"><?php echo $lang["SEARCHEVENTS_PREV"]; ?></a></div>
			<?php	
		}
		?>
		
		
		<?php
		if($numpages<=6) {
			for($i=1;$i<=$numpages;$i++) {
				$style="";
				if($i==$pag) {
					$style="background-position:-630px -100px";
				}
				?>
				<a class="page_list_number_button page_list_button" style="<?php echo $style; ?>" href="javascript:searchEventsList('<?php echo $_GET["text"]; ?>',<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $i; ?>);"><?php echo ($i); ?></a>
				<?php
			}
		} else {
			if($pag<4) {
				if($numpages>=7) {
					$to = 7;
				} else {
					$to=$numpages;
				}
				for($i=1;$i<=$to;$i++) {
					$style="";
					if($i==$pag) {
						$style="background-position:-630px -100px";
					}
					?>
					<a class="page_list_number_button page_list_button" style="<?php echo $style; ?>" href="javascript:searchEventsList('<?php echo $_GET["text"]; ?>',<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $i; ?>);"><?php echo ($i); ?></a>
					<?php
				}
			} else {
				if(($pag+3)<=$numpages) {
					$to = $pag+3;
					$from = $pag-3;
				} else {
					$to=$numpages;
					$from=$numpages-6;
				}
				for($i=$from;$i<=$to;$i++) {
					$style="";
					if($i==$pag) {
						$style="background-position:-630px -100px";
					}
					?>
					<a class="page_list_number_button page_list_button" style="<?php echo $style; ?>" href="javascript:searchEventsList('<?php echo $_GET["text"]; ?>',<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $i; ?>);"><?php echo ($i); ?></a>
					<?php
				}
			}
			
			
			
		}
		?>
		
		<?php
		if($pag<$numpages) {
			?>
			<div class="event_link"><a class="page_list_nav_button page_list_button" href="javascript:searchEventsList('<?php echo $_GET["text"]; ?>',<?php echo $calendarObj->getCalendarId(); ?>,<?php echo ($pag+1); ?>);"><?php echo $lang["SEARCHEVENTS_NEXT"]; ?></a></div>
			<div class="event_link"><a class="page_list_nav_button page_list_button" href="javascript:searchEventsList('<?php echo $_GET["text"]; ?>',<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $numpages; ?>);"><?php echo $lang["SEARCHEVENTS_LAST"]; ?></a></div>
			
			<?php		
		} else {
			?>
			<div class="event_link"><a class="page_list_nav_button_disabled page_list_button"><?php echo $lang["SEARCHEVENTS_NEXT"]; ?></a></div>
			<div class="event_link"><a class="page_list_nav_button_disabled page_list_button"><?php echo $lang["SEARCHEVENTS_LAST"]; ?></a></div>
			
			<?php	
		}
		
	}
	?>
    
    <!-- close -->
    <div class="font_custom close_booking"><a href="#" onclick="javascript:closeEvents(<?php echo date('Y'); ?>,<?php echo date('m'); ?>,<?php echo date('d'); ?>,<?php echo $calendarObj->getCalendarId(); ?>);"><?php echo $lang["GETEVENTSLIST_CLOSE"]; ?>&nbsp; X</a></div>
    
</div>



<div class="cleardiv"></div>
<div class="events_container">
    <!-- title -->
    <div class="font_custom events_title"><span style="color:#567BD2;"><?php echo $lang["SEARCHEVENTS_RESULTS"]; ?></span></div>
    <div class="cleardiv"></div>
    <?php
	if(count($arrayEvents)>0) {
		foreach($arrayEvents as $eventId => $event) {
			
			//get date elements separately
			$arrDateFrom = explode('-',$event["event_date_from"]);
			
		  ?>
		  <div class="preview_event_container">
            <!-- ===== leftside - event thumb =====-->
            <div class="preview_event_leftside">  
                <div class="preview_event_thumb">
                    <?php
                    if($event["event_image"] != '') {
                        ?>
                        <a href="#!event_<?php echo $arrDateFrom[0]; ?>_<?php echo $arrDateFrom[1]; ?>_<?php echo $arrDateFrom[2]; ?>_<?php echo $calendarObj->getCalendarId(); ?>_<?php echo $eventId; ?>_<?php echo $pag; ?>_<?php echo urlencode($event["event_title"]); ?>" onclick="javascript:openSearchEvent(<?php echo $arrDateFrom[0]; ?>,<?php echo $arrDateFrom[1]; ?>,<?php echo $arrDateFrom[2]; ?>,<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $eventId; ?>,'<?php echo $_GET["text"]; ?>',<?php echo $pag; ?>);"><img src="contents/events/<?php echo $eventId; ?>/thumbs/<?php echo $event["event_image"]; ?>" alt="<?php echo $event["event_title"]; ?> " border="0"/></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
            
            <!-- ===== rightside - event information ===== --> 
            <div class="preview_event_rightside">
            
                  <!-- event title -->
                  <div class="preview_event_title">
                  	  
                      <?php echo $event["event_title"]; ?> 
                  </div>
                  
                  <div class="preview_event_information">
                  
                  <!--date info, in search no time info is displayed-->
                  <div class="preview_event_information_box"><span style="color: #666;"><?php echo $event["date_from"]; ?></span>-<span style="color: #666;"><?php echo $event["date_to"]; ?></span></div>
                 
                  <!-- Admission info -->
                   <?php
                      if($event["event_free"] == -1) {
                          ?>
                          <div class="preview_event_information_box"><?php echo $lang["EVENTPOPUP_ADMISSION"]; ?>: <span style="color: #333;"><?php echo $lang["EVENTPOPUP_WITH_FEE"]; ?></span></div>
                          <?php			
                      } else if($event["event_free"] == 1) {
                          ?>
                          <div class="preview_event_information_box"><?php echo $lang["EVENTPOPUP_ADMISSION"]; ?>: <span style="color: #333;"><?php echo $lang["EVENTPOPUP_FREE"]; ?></span></div>
                          <?php
                      }
                      ?>  
                      
                   <!-- Tickets available info --> 
                   <?php
                      if($event["event_seats"] != -1) {
                          ?>
                          <div class="preview_event_information_box"><?php echo $lang["GETEVENTSLIST_AVAILABLE_SEATS"]; ?>: <span style="color: #333;"><?php echo $event["event_seats"]; ?></span></div>
                          <?php			
                      } 
                      ?> 
                      
                  </div>
                  
                  <!-- preview description --> 
                  <div class="preview_event_text">
                      <?php
                      if(strlen($event["event_description"]) > 200) {
						  echo strip_tags(substr($event["event_description"],0,200)."...");
					  } else {
						  echo strip_tags($event["event_description"]);
					  }
                      ?>
                 </div>
                 
                 
                 <div class="preview_event_buttons_container">
                    <div class="event_link"><a class="preview_event_button readmore_button" href="#!event_<?php echo $arrDateFrom[0]; ?>_<?php echo $arrDateFrom[1]; ?>_<?php echo $arrDateFrom[2]; ?>_<?php echo $calendarObj->getCalendarId(); ?>_<?php echo $eventId; ?>_<?php echo $pag; ?>_<?php echo urlencode($event["event_title"]); ?>" onclick="javascript:openSearchEvent(<?php echo $arrDateFrom[0]; ?>,<?php echo $arrDateFrom[1]; ?>,<?php echo $arrDateFrom[2]; ?>,<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $eventId; ?>,'<?php echo $_GET["text"]; ?>',<?php echo $pag; ?>);"><?php echo $lang["GETEVENTSLIST_READ_MORE"]; ?></a></div>
                    <?php
                    if($event["event_link"]!='') {
                        ?>
                        <div class="event_link"><a class="preview_event_button buy_tickets_button" id="buytickets_button" href="<?php echo $event["event_link"]; ?>" target="_blank"><?php echo $lang["GETEVENT_BUY_TICKETS"]; ?></a></div>
                        <?php
                    }
                    ?>
                    <div class="cleardiv"></div>
                 </div>
                  
                  
                  
             </div> 
             <div class="cleardiv"></div>
             
			
			
			
		</div>
        <div class="cleardiv"></div>
        <div class="list_event_line_divide"></div>
		  <?php
		} 
	} else {
		?>
        <div class="font_custom events_title"><span style="text-align:center"><?php echo $lang["SEARCHEVENTS_NO_RESULTS"]; ?></span></div>
        <?php
	}
	?>
    
	
    
</div>

<div class="cleardiv"></div>
