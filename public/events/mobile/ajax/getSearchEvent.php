<?php
include '../common.php';
$calendarObj->setCalendar($_GET["calendar_id"]);
$searchText = $_GET["text"];

//preparing week days
$weekdays=Array();
$weekdays[0] = $lang["SUNDAY"];
$weekdays[1] = $lang["MONDAY"];
$weekdays[2] = $lang["TUESDAY"];
$weekdays[3] = $lang["WEDNESDAY"];
$weekdays[4] = $lang["THURSDAY"];
$weekdays[5] = $lang["FRIDAY"];
$weekdays[6] = $lang["SATURDAY"];

$pag=$_GET["pag"];
$eventObj->setEvent($_GET["event_id"]);
?>

<div class="event_nav_buttons_container">
	 <!-- back -->
        <div class="m_event_back float_right"><a href="#" onclick="javascript:searchEventsList('<?php echo $searchText; ?>',<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $pag; ?>);" class="float_right bg_grey_ccc radius mark_grey padding_button_small"><?php echo $lang["GETEVENT_BACK_TO_EVENTS_LIST"]; ?></a></div>
	<!-- close -->
    <div class="m_list_buttons_container padding_small margin_t"><a href="#" class="float_right bebas mark_grey" onclick="javascript:closeEvents(<?php echo $_GET["year"]; ?>,<?php echo $_GET["month"]; ?>,<?php echo $_GET["day"]; ?>,<?php echo $calendarObj->getCalendarId(); ?>);"><?php echo $lang["GETEVENT_CLOSE"]; ?>&nbsp; X</a></div>
    
    <div class="cleardiv"></div>
</div>


<div class="cleardiv"></div>

<!-- ========== JS Functions =========== -->

<script language="javascript" type="text/javascript">
	$(function() {
		<?php
		$title = $eventObj->getEventPageTitle();
		if($title == '') {
			$title = $eventObj->getEventTitle();
		}
		?>
		document.title="<?php echo $title; ?>";
		<?php
		if($eventObj->getEventMetatagTitle() != '') {
			?>
			$('meta[name=title]').remove();
    		$('head').append( '<meta name="title" content="<?php echo $eventObj->getEventMetatagTitle(); ?>">' );
			<?php
		}
		if($eventObj->getEventMetatagDescription() != '') {
			?>
			$('meta[name=description]').remove();
    		$('head').append( '<meta name="description" content="<?php echo $eventObj->getEventMetatagDescription(); ?>">' );
			<?php
		}
		if($eventObj->getEventMetatagKeywords() != '') {
			?>
			$('meta[name=keywords]').remove();
    		$('head').append( '<meta name="keywords" content="<?php echo $eventObj->getEventMetatagKeywords(); ?>">' );
			<?php
		}
		?>
		
	});
	
</script>
<div class="m_calendar_container">
    
    <div class="m_event_list_container bg_white">
        <!-- date -->
        <div class="event_date font_medium float_left"><?php echo $_GET["day"]."&nbsp;".$weekdays[intval(date('w',mktime(0,0,0,$_GET["month"],$_GET["day"],$_GET["year"])))]; ?></div>
         <?php
		if($eventObj->getEventLink()!='') {
			?>
        	<div><a href="<?php echo $eventObj->getEventLink(); ?>" target="_blank" class="bebas bg_green float_right mark_white padding_button_big font_medium"><?php echo $lang["GETEVENT_BUY_TICKETS"]; ?></a></div>
        	<?php
        }
        ?>
        <div class="cleardiv"></div>
        <div class="m_line_divide"></div>
        
        <!-- event -->
        <div class="m_single_event_container">
        
            <!-- event title -->
            <div class="font_big"><?php echo $eventObj->getEventTitle(); ?></div>
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style"> 
            	<a class="addthis_button_preferred_1"></a> 
                <a class="addthis_button_preferred_2"></a> 
                <a class="addthis_button_preferred_3"></a> 
                <a class="addthis_button_preferred_4"></a> 
                <a class="addthis_button_compact"></a> 
                <a class="addthis_counter addthis_bubble_style"></a> 
             </div>
            
            <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50e55b295a97139b"></script>
            <!-- AddThis Button END -->
            
            <!-- photo -->
            <?php
			if($eventObj->getEventCoverVisible()==1) {
				?>
			
				<div class="m_event_photo margin_tb">
					<?php
					if($eventObj->getEventImage()!='') {
						?>
						<img src="contents/events/<?php echo $eventObj->getEventId(); ?>/<?php echo $eventObj->getEventImage(); ?>" alt="<?php echo $eventObj->getEventTitle(); ?>" />
						<?php
					}
					?>
				</div>
			
				<?php
			}
			?>
            
            
            <!-- information -->
            <div class="float_left m_event_information_column">
                <!-- label -->
                <div class="bg_black mark_white bebas font_medium m_event_information_label padding_button_big"><?php echo $lang["GETEVENT_TIME"]; ?></div>
                <?php
                //check if there'a any custom time for this date
                $arrayCustomTimes=$eventObj->getEventCustomTimes($eventObj->getEventId(),$_GET["year"]."-".$_GET["month"]."-".$_GET["day"]);
                if(count($arrayCustomTimes)>0) {
                    
                    foreach($arrayCustomTimes as $dayId =>$day) {
                        
                        ?>
                        <div class="margin_tb_medium">
                        	<?php
							if($settingObj->getTimeFormat() == '24') {
								echo substr($day["day_time_from"],0,5);
							} else {
								echo date('h:i a',strtotime(substr($day["day_time_from"],0,5)));
							}							
							echo " ".$lang["GETEVENT_START_TIME"]; ?>
                        
							<?php
                            if($day["day_time_to_flag"] == 0) {
                                ?>
                                <br />
                                    <?php
                                    if($settingObj->getTimeFormat() == '24') {
                                        echo substr($day["day_time_to"],0,5);
                                    } else {
                                        echo date('h:i a',strtotime(substr($day["day_time_to"],0,5)));
                                    }	
                                    echo " ".$lang["GETEVENT_END_TIME"];
                                    ?>
                                
                                <?php
                        	}
							?>
                        </div>
                        <?php
                        
                    }
                } else {
					if($eventObj->getEventStartTime()!='00:00:00') {
                    ?>
                    
                    <div class="margin_tb_medium">
                    	<?php
						if($settingObj->getTimeFormat() == '24') {
							echo substr($eventObj->getEventStartTime(),0,5);
						} else {
							echo date('h:i a',strtotime(substr($eventObj->getEventStartTime(),0,5)));
						}	
						echo " ".$lang["GETEVENT_START_TIME"]; 
						?>
                    
                    	<?php
						if($eventObj->getEventEndtimeFlag()==0) {
							?>
							<br />
								<?php
								if($settingObj->getTimeFormat() == '24') {
									echo substr($eventObj->getEventEndtime(),0,5);
								} else {
									echo date('h:i a',strtotime(substr($eventObj->getEventEndtime(),0,5)));
								}	
								echo " ".$lang["GETEVENT_END_TIME"];
								?>
							
							<?php
						}
						?>
                        </div>
                        <?php
						
					} else {
						?>
                        <div class="margin_tb_medium">---</div>
                        <?php
					}
                    
                } ?>
               
            </div>
            <?php
            if($eventObj->getEventVenue()!='') {
                ?>
                <div class="float_left m_event_information_column">
                    <!-- label -->
                    <div class="bg_black mark_white bebas font_medium m_event_information_label padding_button_big"><?php echo $lang["GETEVENT_VENUE"]; ?></div>
                    <!-- text -->
                    <div class="margin_tb_medium"><?php echo str_replace("\n","<br>",$eventObj->getEventVenue()); ?></div>
                </div>
                <?php
			}
			?>
            
            <div class="float_left m_event_information_column">
                <!-- label -->
                <div class="bg_black mark_white bebas font_medium m_event_information_label padding_button_big"><?php echo $lang["GETEVENT_ADMISSION"]; ?></div>
                <!-- text -->
                <div class="margin_tb_medium">
                	<?php
                    if($eventObj->getEventFree() == 1) {
                        echo $lang["GETEVENT_FREE_ENTRANCE"]."<br>".$eventObj->getEventEntrance();
                    } else {
                        echo $eventObj->getEventEntrance();
                    }
                    ?>
                </div>
            </div>
            
            
            <div class="cleardiv"></div>
            
            <div class="m_line_divide"></div>
            
            <?php
				if($eventObj->getEventDescription()!='') {
					?>
                    <!-- description -->
                    <!-- label -->
                    <div class="bg_black mark_white bebas font_medium m_event_information_label padding_button_big"><?php echo $lang["GETEVENT_DESCRIPTION"]; ?></div>
                    <!-- text -->
                    <div class="margin_tb"><?php echo $eventObj->getEventDescription(); ?></div>
                    
                    <div class="cleardiv"></div>
                    <?php
				}
				?>
                <?php
				if(count($eventObj->getEventImages())>0) {
					?>
                    <!-- link -->
                    <div class="margin_tb_medium"><a href="ajax/foto.php?event_id=<?php echo $eventObj->getEventId(); ?>" class="bg_black mark_white padding_button_small bebas m_link_button"><?php echo $lang["GETEVENT_GALLERY"]; ?></a></div>
                    <?php
				}
				?>
                <?php
				if($eventObj->getEventVideo()!='') {
					?>			
            
            		<div class="margin_tb_medium"><a href="ajax/video.php?event_id=<?php echo $eventObj->getEventId(); ?>" class="bg_black mark_white padding_button_small bebas m_link_button"><?php echo $lang["GETEVENT_VIDEO"]; ?></a></div>
                    <?php
				}
				?>
                <?php
				if($eventObj->getEventMap()!='') {
					?>
            
            		<div class="margin_tb_medium"><a href="ajax/map.php?event_id=<?php echo $eventObj->getEventId(); ?>" class="bg_black mark_white padding_button_small bebas m_link_button"><?php echo $lang["GETEVENT_MAP"]; ?></a></div>
                    <?php
				}
				?>
            	<?php
				if($eventObj->getEventHashtag()!='') {
					?>
            		<div class="margin_tb_medium"><a href="ajax/twitter.php?event_id=<?php echo $eventObj->getEventId(); ?>" class="bg_black mark_white padding_button_small bebas m_link_button">twitter</a></div>
                    <?php
				}
				?>
            	<?php
				if($settingObj->getFlickrApiKey()!='' && $eventObj->getEventFlickrSearch()!='') {
					?>
            		<div class="margin_tb_medium"><a href="ajax/flickr.php?event_id=<?php echo $eventObj->getEventId(); ?>" class="bg_black mark_white padding_button_small bebas m_link_button">flickr</a></div>
                    <?php
				}
				?>
            
        </div>
        
        <div class="margin_tb"></div>
        
        
        
       
        
    </div>

</div>


