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
	<!-- back to event list button -->
	<div class="event_link_nav"><a class="preview_event_button back_list_button" href="#" onclick="javascript:searchEventsList('<?php echo $searchText; ?>',<?php echo $calendarObj->getCalendarId(); ?>,<?php echo $pag; ?>);"><?php echo $lang["GETEVENT_BACK_TO_EVENTS_LIST"]; ?></a></div>
    <!-- close -->
    <div class="font_custom close_booking"><a href="#" onclick="javascript:closeEvents(<?php echo $_GET["year"]; ?>,<?php echo $_GET["month"]; ?>,<?php echo $_GET["day"]; ?>,<?php echo $calendarObj->getCalendarId(); ?>);"><?php echo $lang["GETEVENT_CLOSE"]; ?>&nbsp; X</a></div>
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
		$('.gallery_lightbox').lightBox();
		$('.gallery_lightbox').each(function() {
			$(this).bind('mouseover',function() {
				
				$(this).effect("pulsate", { times:1 }, 500);
			});
		});
		$('#gallery_container').css({"position":"absolute"});
		$('#map_container').css({"position":"absolute"});
		$('#video_container').css({"position":"absolute"});
		$('#twitter_container').css({"position":"absolute"});
		$('#flickr_container').css({"position":"absolute"});
		$('#text_container').css({"position":"absolute"});
		$('#text_button').css("background-position","-260px -290px");
		$('.event_content_container').css({"position":"relative","height":$('#text_container').height()+"px"});
		<?php
		if($eventObj->getEventDescription()!='') {
			//description default
			?>
			closeContent();
			<?php
		} else if(count($eventObj->getEventImages())>0) {
			//gallery default
			?>
			openGallery();
			<?php
		} else if($eventObj->getEventVideo()!='') {
			?>
			openVideo();
			<?php
		} else if($eventObj->getEventMap()!='') {
			?>
			openMap();
			<?php
		} else if($eventObj->getEventHashtag()!='') {
			?>
			openTwitter();
			<?php
		} else if($settingObj->getFlickrApiKey()!='' && $eventObj->getEventFlickrSearch()!='') {
			?>
			openFlickr();
			<?php
		}
		?>
	});
	
	function openGallery() {
		$('.event_content_container').css({"position":"relative","height":"300px"});
		$('#gallery_button').css("background-position","-260px -290px");
		$('#gallery_button').removeAttr("href");
		$('#gallery_container').animate({"opacity": "show"},500,"",function() { $('.event_content_container').css({"height":$('#gallery_container').height()+"px"}); });
		if($('#map_container').css("display") == "block") {
			$('#map_container').animate({"opacity": "hide"},500);
			$('#map_button').removeAttr("style");
			$('#map_button').attr("href","javascript:openMap();");
		}
		if($('#twitter_container').css("display") == "block") {
			$('#twitter_container').animate({"opacity": "hide"},500);
			$('#twitter_button').removeAttr("style");
			$('#twitter_button').attr("href","javascript:openTwitter();");
		}
		if($('#flickr_container').css("display") == "block") {
			$('#flickr_container').animate({"opacity": "hide"},500);
			$('#flickr_button').removeAttr("style");
			$('#flickr_button').attr("href","javascript:openFlickr();");
		}
		if($('#video_container').css("display") == "block") {
			$('#video_container').animate({"opacity": "hide"},500);
			$('#video_button').removeAttr("style");
			$('#video_button').attr("href","javascript:openVideo();");
		}
		if($('#text_container').css("display") == "block") {
			$('#text_container').animate({"opacity": "hide"},500);
			$('#text_button').removeAttr("style");
		}
		
		
	}
	
	function openMap() {
		$('.event_content_container').css({"position":"relative","height":"300px"});
		$('#map_button').css("background-position","-260px -290px");
		$('#map_button').removeAttr("href");
		$('#map_container').animate({"opacity": "show"},500,"",function() { $('.event_content_container').css({"height":$('#map_container').height()+"px"}); });
		if($('#gallery_container').css("display") == "block") {
			$('#gallery_container').animate({"opacity": "hide"},500);
			$('#gallery_button').removeAttr("style");
			$('#gallery_button').attr("href","javascript:openGallery();");
		}
		if($('#video_container').css("display") == "block") {
			$('#video_container').animate({"opacity": "hide"},500);
			$('#video_button').removeAttr("style");
			$('#video_button').attr("href","javascript:openVideo();");
		}
		if($('#twitter_container').css("display") == "block") {
			$('#twitter_container').animate({"opacity": "hide"},500);
			$('#twitter_button').removeAttr("style");
			$('#twitter_button').attr("href","javascript:openTwitter();");
		}
		if($('#flickr_container').css("display") == "block") {
			$('#flickr_container').animate({"opacity": "hide"},500);
			$('#flickr_button').removeAttr("style");
			$('#flickr_button').attr("href","javascript:openFlickr();");
		}
		if($('#text_container').css("display") == "block") {
			$('#text_container').animate({"opacity": "hide"},500);
			$('#text_button').removeAttr("style");
		}
		
		
	}
	
	function openVideo() {
		$('.event_content_container').css({"position":"relative","height":"300px"});
		$('#video_button').css("background-position","-260px -290px");
		$('#video_button').removeAttr("href");
		$('#video_container').animate({"opacity": "show"},500,"",function() { $('.event_content_container').css({"height":$('#video_container').height()+"px"}); });
		if($('#gallery_container').css("display") == "block") {
			$('#gallery_container').animate({"opacity": "hide"},500);
			$('#gallery_button').removeAttr("style");
			$('#gallery_button').attr("href","javascript:openGallery();");
		}
		if($('#map_container').css("display") == "block") {
			$('#map_container').animate({"opacity": "hide"},500);
			$('#map_button').removeAttr("style");
			$('#map_button').attr("href","javascript:openMap();");
		}
		if($('#twitter_container').css("display") == "block") {
			$('#twitter_container').animate({"opacity": "hide"},500);
			$('#twitter_button').removeAttr("style");
			$('#twitter_button').attr("href","javascript:openTwitter();");
		}
		if($('#flickr_container').css("display") == "block") {
			$('#flickr_container').animate({"opacity": "hide"},500);
			$('#flickr_button').removeAttr("style");
			$('#flickr_button').attr("href","javascript:openFlickr();");
		}
		if($('#text_container').css("display") == "block") {
			$('#text_container').animate({"opacity": "hide"},500);
			$('#text_button').removeAttr("style");
		}
		
		
	}
	
	function openTwitter() {
		$('.event_content_container').css({"position":"relative","height":"300px"});
		if($('#gallery_container').css("display") == "block") {
			$('#gallery_container').animate({"opacity": "hide"},500);
			$('#gallery_button').removeAttr("style");
			$('#gallery_button').attr("href","javascript:openGallery();");
		}
		if($('#map_container').css("display") == "block") {
			$('#map_container').animate({"opacity": "hide"},500);
			$('#map_button').removeAttr("style");
			$('#map_button').attr("href","javascript:openMap();");
		}
		if($('#video_container').css("display") == "block") {
			$('#video_container').animate({"opacity": "hide"},500);
			$('#video_button').removeAttr("style");
			$('#video_button').attr("href","javascript:openVideo();");
		}
		if($('#flickr_container').css("display") == "block") {
			$('#flickr_container').animate({"opacity": "hide"},500);
			$('#flickr_button').removeAttr("style");
			$('#flickr_button').attr("href","javascript:openFlickr();");
		}
		if($('#text_container').css("display") == "block") {
			$('#text_container').animate({"opacity": "hide"},500);
			$('#text_button').removeAttr("style");
		}
		
		$('#twitter_loading').fadeIn();
		//ajax request to read tweets
		$.ajax({
		  url: 'ajax/getTweets.php?event_id=<?php echo $eventObj->getEventId(); ?>',
		  success: function(data) {
			$('#twitter_loading').fadeOut();
	    	
			$('#twitter_button').css("background-position","-260px -290px");
			$('#twitter_button').removeAttr("href");
			$('#twitter_container').html(data);
			$('#twitter_container').animate({"opacity": "show"},500,"",function() { $('.event_content_container').css({"height":$('#twitter_container').height()+"px"}); });
			
			
			
		  }
		});
		
	}
	
	function openFlickr() {
		$('.event_content_container').css({"position":"relative","height":"300px"});
		if($('#gallery_container').css("display") == "block") {
			$('#gallery_container').animate({"opacity": "hide"},500);
			$('#gallery_button').removeAttr("style");
			$('#gallery_button').attr("href","javascript:openGallery();");
		}
		if($('#map_container').css("display") == "block") {
			$('#map_container').animate({"opacity": "hide"},500);
			$('#map_button').removeAttr("style");
			$('#map_button').attr("href","javascript:openMap();");
		}
		if($('#video_container').css("display") == "block") {
			$('#video_container').animate({"opacity": "hide"},500);
			$('#video_button').removeAttr("style");
			$('#video_button').attr("href","javascript:openVideo();");
		}
		if($('#twitter_container').css("display") == "block") {
			$('#twitter_container').animate({"opacity": "hide"},500);
			$('#twitter_button').removeAttr("style");
			$('#twitter_button').attr("href","javascript:openTwitter();");
		}
		if($('#text_container').css("display") == "block") {
			$('#text_container').animate({"opacity": "hide"},500);
			$('#text_button').removeAttr("style");
		}
		
		$('#flickr_loading').fadeIn();
		//ajax request to read pics
		$.ajax({
		  url: 'ajax/getFlickrPics.php?event_id=<?php echo $eventObj->getEventId(); ?>&num=1',
		  success: function(data) {
			$('#flickr_loading').fadeOut();
	    	
			$('#flickr_button').css("background-position","-260px -290px");
			$('#flickr_button').removeAttr("href");
			$('#flickr_container').html(data);
			$('#flickr_container').animate({"opacity": "show"},500,"",function() { $('.event_content_container').css({"height":$('#flickr_container').height()+"px"});});
			$('#flickr_loading').css({"top":"250px"}).fadeIn();
			//second request
			$.ajax({
			  url: 'ajax/getFlickrPics.php?event_id=<?php echo $eventObj->getEventId(); ?>&num=2',
			  success: function(data) {
				$('#flickr_loading').fadeOut();
				
				$('#flickr_container').append(data);
				$('.event_content_container').delay(1000).css({"height":$('#flickr_container').height()+"px"});
				
				$('#flickr_loading').css({"top":"400px"}).fadeIn();
				//third request
				$.ajax({
				  url: 'ajax/getFlickrPics.php?event_id=<?php echo $eventObj->getEventId(); ?>&num=3',
				  success: function(data) {
					$('#flickr_loading').fadeOut();
					
					$('#flickr_container').append(data);
					$('.event_content_container').delay(1000).css({"height":$('#flickr_container').height()+"px"});
					
					
					
				  }
				});
				
			  }
			});
			
			
			
			
			
		  }
		});
		
	}
	
	function closeContent() {
		
		
		if($('#gallery_container').css("display") == "block") {
			$('#gallery_container').animate({"opacity": "hide"},500);
			$('#gallery_button').removeAttr("style");
			$('#gallery_button').attr("href","javascript:openGallery();");
		}
		if($('#map_container').css("display") == "block") {
			$('#map_button').removeAttr("style");
			$('#map_button').attr("href","javascript:openMap();");
			$('#map_container').animate({"opacity": "hide"},500);
		}
		if($('#video_container').css("display") == "block") {
			$('#video_container').animate({"opacity": "hide"},500);
			$('#video_button').removeAttr("style");
			$('#video_button').attr("href","javascript:openVideo();");
		}
		if($('#twitter_container').css("display") == "block") {
			$('#twitter_button').removeAttr("style");
			$('#twitter_button').attr("href","javascript:openTwitter();");
			$('#twitter_container').animate({"opacity": "hide"},500);
		}
		if($('#flickr_container').css("display") == "block") {
			$('#flickr_container').animate({"opacity": "hide"},500);
			$('#flickr_button').removeAttr("style");
			$('#flickr_button').attr("href","javascript:openFlickr();");
		}
		$('#text_container').animate({"opacity": "show"},500,"", function () { $('.event_content_container').css({"height":$('#text_container').height()+"px"}); });
		$('#text_button').css("background-position","-260px -290px");
		
		
	}
	
	
</script>


<div class="events_container">

    <div class="title_share_container">
        <!-- event title -->
        <div class="font_custom events_title_inside"><?php echo $_GET["day"]."&nbsp;".$weekdays[intval(date('w',mktime(0,0,0,$_GET["month"],$_GET["day"],$_GET["year"])))]; ?>&nbsp;-&nbsp;<span style="color:#567BD2;"><?php echo $eventObj->getEventTitle(); ?></span></div>
        
        <div class="cleardiv"></div>
        
        <!-- AddThis Button BEGIN -->
        <div class="addthis_toolbox addthis_default_style">
        <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
        <a class="addthis_button_tweet"></a>
        <a class="addthis_button_pinterest_pinit"></a>
        <a class="addthis_counter addthis_pill_style"></a>
        </div>
        <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50e55b295a97139b"></script>
        <!-- AddThis Button END -->
    
    </div>
    
    <?php
	if($eventObj->getEventLink()!='') {
		?>
		<!-- buy tickets -->
		<div class="event_link_buy"><a class="preview_event_button_buy buy_tickets_button_big" id="buytickets_button" href="<?php echo $eventObj->getEventLink(); ?>" target="_blank"><?php echo $lang["GETEVENT_BUY_TICKETS"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
		<?php
	}
	?>
    <div class="cleardiv"></div>
    <div class="div_height"></div>
    
    
    <!-- ======= event leftside ====== -->
    <div class="event_leftside">
    	
        <!-- cover image -->
    	<?php
        if($eventObj->getEventCoverVisible()==1) {
        ?>
        <div class="event_image_container">
            <div class="event_image">
                <?php
                if($eventObj->getEventImage()!='') {
                    ?>
                    <img src="contents/events/<?php echo $eventObj->getEventId(); ?>/<?php echo $eventObj->getEventImage(); ?>" alt="<?php echo $eventObj->getEventTitle(); ?>" />
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        }
        ?>
    
        <!-- all the information -->
        <div class="event_info_container">
        
        	<!-- Time -->
        	<div class="event_info_box">
                <div class="event_info_title"><?php echo $lang["GETEVENT_TIME"]; ?></div>
                <?php
                //check if there'a any custom time for this date
                $arrayCustomTimes=$eventObj->getEventCustomTimes($eventObj->getEventId(),$_GET["year"]."-".$_GET["month"]."-".$_GET["day"]);
                if(count($arrayCustomTimes)>0) {
                    
                    foreach($arrayCustomTimes as $dayId =>$day) {
                        
                        ?>
                        <div class="event_info_row">
                        	<?php
							if($settingObj->getTimeFormat() == '24') {
								echo substr($day["day_time_from"],0,5);
							} else {
								echo date('h:i a',strtotime(substr($day["day_time_from"],0,5)));
							}	
							echo " ".$lang["GETEVENT_START_TIME"]; 
							?>
                        </div>
                        <?php
                        if($day["day_time_to_flag"] == 0) {
                            ?>
                            <div class="event_info_row">
                            	<?php
								if($settingObj->getTimeFormat() == '24') {
									echo substr($day["day_time_to"],0,5);
								} else {
									echo date('h:i a',strtotime(substr($day["day_time_to"],0,5)));
								}	
								echo " ".$lang["GETEVENT_END_TIME"]; 
								?>
                            </div>
                            <?php
                        }
                        
                    }
                } else {
					if($eventObj->getEventStartTime()!='00:00:00') {
                    ?>
                    <div class="event_info_row">
                    	<?php
						if($settingObj->getTimeFormat() == '24') {
							echo substr($eventObj->getEventStartTime(),0,5);
						} else {
							echo date('h:i a',strtotime(substr($eventObj->getEventStartTime(),0,5)));
						}	
						echo " ".$lang["GETEVENT_START_TIME"]; ?></div>
                    <?php
					} else {
						?>
                        <div class="event_info_row">---</div>
                        <?php
					}
                    if($eventObj->getEventEndtimeFlag()==0) {
                        ?>
                        <div class="event_info_row">
                        	<?php
							if($settingObj->getTimeFormat() == '24') {
								echo substr($eventObj->getEventEndtime(),0,5);
							} else {
								echo date('h:i a',strtotime(substr($eventObj->getEventEndtime(),0,5)));
							}	
							echo " ".$lang["GETEVENT_END_TIME"]; 
							?>
                        </div>
                        <?php
                    }
                } ?>
            </div>
                
            <!-- Venue -->
			<?php
            if($eventObj->getEventVenue()!='') {
                ?>
                <div class="event_info_box">
                    <div class="event_info_title"><?php echo $lang["GETEVENT_VENUE"]; ?></div>
                    <div class="event_info_row"><?php echo str_replace("\n","<br>",$eventObj->getEventVenue()); ?></div>
                </div>
                <?php
            }
            ?>
            
            <!-- Admission -->
            <div class="event_info_box">
            	<div class="event_info_title"><?php echo $lang["GETEVENT_ADMISSION"]; ?></div>
                <div class="event_info_row">
                    
                    <?php
                    if($eventObj->getEventFree() == 1) {
                        echo $lang["GETEVENT_FREE_ENTRANCE"]."<br>".$eventObj->getEventEntrance();
                    } else {
                        echo $eventObj->getEventEntrance();
                    }
                    ?>
                    
                    <div class="cleardiv"></div>
                </div>
            </div>
            
            <!-- Tickets -->
			<?php
            if($eventObj->getEventSeats()>0) {
                ?>
                <div class="event_info_box">
                    <div class="event_info_title"><?php echo $lang["GETEVENT_TICKETS"]; ?></div>
                    <div class="event_info_row"><?php echo $eventObj->getEventSeats(); ?></div>
                </div>
                <?php
            }
            ?>
            
            <!-- Website -->
			<?php
            if($eventObj->getEventSite()!='') {
                ?>
                <div class="event_info_box">
                    <div class="event_info_title"><?php echo $lang["GETEVENT_WEBSITE"]; ?></div>
                    <div class="event_info_row"><a class="external_link" href="<?php echo $eventObj->getEventSite(); ?>" target="_blank"><?php echo $eventObj->getEventSite(); ?></a></div>
                </div>
                <?php
            }
            ?>
            
            <div class="cleardiv"></div>
        </div>
        
        </div>
        
        
        <!-- ======= event rightside ====== -->
        
        <div class="event_rightside">
        
        	<div class="event_buttons_container">
            	<?php
				if($eventObj->getEventDescription()!='') {
					?>
                    <!-- description -->
                    <div class="event_link"><a class="preview_event_button readmore_button" style="background-position:-260px -290px" id="text_button" href="javascript:closeContent();"><?php echo $lang["GETEVENT_DESCRIPTION"]; ?></a></div>
                    <?php
				}
				?>
                <?php
				if(count($eventObj->getEventImages())>0) {
					?>
                    <!-- gallery -->
                    <div class="event_link"><a class="preview_event_button readmore_button" id="gallery_button" href="javascript:openGallery();"><?php echo $lang["GETEVENT_GALLERY"]; ?></a></div>
                    <?php
				}
				?>
                <?php
				if($eventObj->getEventVideo()!='') {
					?>
					<!-- video -->
					<div class="event_link"><a class="preview_event_button readmore_button" id="video_button" href="javascript:openVideo();"><?php echo $lang["GETEVENT_VIDEO"]; ?></a></div>
					<?php
				}
				?>
                <?php
				if($eventObj->getEventMap()!='') {
					?>
                    <!-- map -->
                    <div class="event_link"><a class="preview_event_button readmore_button" id="map_button" href="javascript:openMap();"><?php echo $lang["GETEVENT_MAP"]; ?></a></div>
                    <?php
				}
				?>
                
                <?php
				if($eventObj->getEventHashtag()!='') {
					?>
                    <!-- twitter -->
                    <div class="event_link"><a class="preview_event_button readmore_button" id="twitter_button" href="javascript:openTwitter();">TWITTER</a></div>
                    <?php
				}
				?>
                <?php
				if($settingObj->getFlickrApiKey()!='' && $eventObj->getEventFlickrSearch()!='') {
					?>
                    <!-- twitter -->
                    <div class="event_link"><a class="preview_event_button readmore_button" id="flickr_button" href="javascript:openFlickr();">FLICKR</a></div>
                    <?php
				}
				?>
                
                
            </div>
            
            <div class="event_content_container">
        		<div id="twitter_loading" class="modal_loading_twitter" style="display:none">
                    <img src="images/small_loading.gif" border=0 />
                </div>
                
                <div id="flickr_loading" class="modal_loading_flickr" style="display:none;">
                	<div style="width:100%;text-align:center;text-decoration:blink"><?php echo $lang["FLICKR_LOADING"];?></div>
                    <img src="images/small_loading.gif" border=0 />
                </div>
                
                <div class=" event_text" id="text_container">            	
                    <?php echo $eventObj->getEventDescription(); ?>
                </div>
                <div class="event_map" id="map_container" style="display:none">
                    
                    <?php echo $eventObj->getEventMap(); ?><br />
                    <?php echo $eventObj->getEventMapText(); ?>
                </div>
                
                <div class="event_gallery" id="gallery_container" style="display:none">
                    
                    <?php
                    $arrImg = $eventObj->getEventImages();
                    for($i = 1;$i<count($arrImg); $i++) {
                        ?>
                        <a class="gallery_lightbox" style="float:left; margin:5px;" href="contents/events/<?php echo $eventObj->getEventId(); ?>/<?php echo $arrImg[$i]; ?>"><img src="contents/events/<?php echo $eventObj->getEventId(); ?>/thumbs/<?php echo $arrImg[$i]; ?>" border="0" /></a>
                        <?php
                    }
                    ?>
                </div>
                
                <div class="event_video" id="video_container" style="display:none">
                    
                    <?php echo $eventObj->getEventVideo(); ?><br />
                    <?php echo $eventObj->getEventVideoText(); ?>
                </div>
            	
                <div class="event_twitter" id="twitter_container" style="display:none">
                
                
                <?php
                   
                   
                    ?>
            
                </div>
                
                <div class="event_flickr" id="flickr_container" style="display:none">
                
                
                <?php
                   
                   
                    ?>
            
                </div>
            </div>
            
        </div>
        
        <div class="cleardiv"></div>

	
</div>

