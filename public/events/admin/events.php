<?php 
include 'common.php';
if(!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
	header('Location: login.php');
}



$calendarObj->setCalendar($_GET["calendar_id"]);

if(isset($_POST["operation"]) && $_POST["operation"] != '') {
	$arrEvents=$_POST["events"];
	$qryString = "0";
	for($i=0;$i<count($arrEvents); $i++) {
		$qryString .= ",".$arrEvents[$i];
	}
		
	switch($_POST["operation"]) {
		case "publishEvents":
			$eventObj->publishEvents($qryString);
			break;
		case "unpublishEvents":
			$eventObj->unpublishEvents($qryString);
			break;
		case "delEvents":
			$eventObj->delEvents($qryString);
			break;
		case "duplicateEvents":
			$eventObj->duplicateEvents($qryString);
			break;
	}                
	//header('Location: events.php?calendar_id='.$_GET["calendar_id"]);
}

include 'include/header.php';
?>

<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
			<?php
            include 'include/menu.php';
            ?>
           <?php
			$background = "";
			$status = "";
			if($calendarObj->getCalendarActive() == 1) {
				$background = "#00B478";
				$status = $lang["PUBLISHED"];
			} else {
				$background = "#E05B5B";
				$status = $lang["UNPUBLISHED"];
			}
			?>
            <!-- calendar status -->
            <div class="calendar_status" style="background:<?php echo $background; ?>"><?php echo $lang["CALENDAR_ACTUAL_STATUS"]; ?> <span style="text-transform:uppercase; font-weight: bold;"><?php echo $status; ?></span></div>
            
            <div id="cleardiv"></div>
            <div id="action_bar">
            	<script>
					
					function publishEvent(itemId) {
						if(confirm("<?php echo $lang["EVENTS_SINGLE_PUBLISH_CONFIRM"]; ?>")) {
							$.ajax({
							  url: 'ajax/publishEvent.php?event_id='+itemId,
							  success: function(data) {
								  $('#publish_'+itemId).html('<a href="javascript:unpublishEvent('+itemId+');"><img src="images/icons/published.png" border=0 /></a>');								 							 
								
							  }
							});
						} 
					}
					function unpublishEvent(itemId) {
						if(confirm("<?php echo $lang["EVENTS_SINGLE_UNPUBLISH_CONFIRM"]; ?>")) {
							$.ajax({
							  url: 'ajax/unpublishEvent.php?event_id='+itemId,
							  success: function(data) {
								  $('#publish_'+itemId).html('<a href="javascript:publishEvent('+itemId+');"><img src="images/icons/unpublished.png" border=0 /></a>');								 							 
								
							  }
							});
						} 
					}
					
					
				</script>
                <!--form to add a calendar--> 
                <div class="breadcrumb"><?php echo $lang["YOU_ARE_IN"]; ?> <a href="calendars.php"><?php echo $lang["CALENDARS"]; ?></a> > <?php echo $calendarObj->getCalendarTitle(); ?> - <strong><?php echo $lang["EVENTS_LIST"]; ?></strong></div> 
                <div id="action"><a onclick="javascript:delItems('manage_events','events[]','duplicateEvents','<?php echo $lang["EVENTS_MULTIPLE_DUPLICATE_CONFIRM"]; ?>')"><?php echo $lang["DUPLICATE"]; ?></a></div>
                <div id="action"><a onclick="javascript:delItems('manage_events','events[]','delEvents','<?php echo $lang["EVENTS_MULTIPLE_DELETE_CONFIRM"]; ?>')"><?php echo $lang["DELETE"]; ?></a></div>
                <div id="action"><a onclick="javascript:delItems('manage_events','events[]','unpublishEvents','<?php echo $lang["EVENTS_MULTIPLE_UNPUBLISH_CONFIRM"]; ?>')"><?php echo $lang["UNPUBLISH"]; ?></a></div>
                <div id="action"><a onclick="javascript:delItems('manage_events','events[]','publishEvents','<?php echo $lang["EVENTS_MULTIPLE_PUBLISH_CONFIRM"]; ?>')"><?php echo $lang["PUBLISH"]; ?></a></div>
                <div id="action"><a href="new_event.php?calendar_id=<?php echo $calendarObj->getCalendarId(); ?>"><?php echo $lang["ADD"]; ?></a></div>
                
            </div>
            
            <form name="manage_events" action="" method="post">
                <input type="hidden" name="operation" />
                <input type="hidden" name="events[]" value=0 />
                <div id="table_container">
                    <div id="table">
                        <div class="event_title_col1">
                            <div id="table_cell">#</div>
                        </div>
                        <div class="event_title_col2">
                            <div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_events','events[]');" /></div>
                        </div>
                        <div class="event_title_col3">
                            <div id="table_cell"><?php echo $lang["EVENTS_TITLE"]; ?></div>
                        </div>
                        <div class="event_title_col4">
                            <div id="table_cell"><?php echo $lang["EVENTS_DATE"]; ?></div>
                        </div>
                        <div class="event_title_col5">
                            <div id="table_cell"><?php echo $lang["EVENTS_START_TIME"]; ?></div>
                        </div>
                        <div class="event_title_col6">
                            <div id="table_cell"><?php echo $lang["EVENTS_PUBLISHED"]; ?></div>
                        </div>
                        <div class="event_title_col7">
                            <div id="table_cell"></div>
                        </div>
                        <div id="empty"></div>
                        <?php                         
                        $arrayEvents = $listObj->getEventsList($_GET["calendar_id"]);                        
						$i=1;
						foreach($arrayEvents as $eventId => $event) {																			
							if($i % 2) {
								$class="alternate_table_row_white";
							} else {
								$class="alternate_table_row_grey";
							}
							
						?>
						<div id="row_<?php echo $eventId; ?>">
                            <div class="event_row_col1 <?php echo $class; ?>">
                                <div id="table_cell"><?php echo $i; ?></div>
                            </div>
                            <div class="event_row_col2 <?php echo $class; ?>">
                                <div id="table_cell"><input type="checkbox" name="events[]" value="<?php echo $eventId; ?>" onclick="javascript:disableSelectAll('manage_events',this.checked);" /></div>
                            </div>                    
                            <div class="event_row_col3 <?php echo $class; ?>">
                                <div id="table_cell">
									<?php echo $event["event_title"]; ?>								
                                </div>
                            </div>
                            <div class="event_row_col4 <?php echo $class; ?>">
                                <div id="table_cell">
									<?php echo strftime('%B %d %Y',strtotime($event["event_date_from"])); ?>
                                    <?php
									if($event["event_date_to"]!='0000-00-00') {
										?>
										<br /><?php echo strftime('%B %d %Y',strtotime($event["event_date_to"])); ?>	
										<?php
									}
									?>
                                </div>
                            </div>
                            <div class="event_row_col5 <?php echo $class; ?>">
                                <div id="table_cell">
                                	<?php
									if($event["event_starttime"] != '00:00:00') {
									  echo substr($event["event_starttime"],0,-3); 
									} else {
										echo "---"; 
									}?>								
                                </div>
                            </div>
                            <div class="event_row_col6 <?php echo $class; ?>">
                                <div id="table_cell"><span id="publish_<?php echo $eventId; ?>"><?php if($event["event_active"]=='1') { ?><a href="javascript:unpublishEvent(<?php echo $eventId; ?>);"><img src="images/icons/published.png" border=0 /></a><?php } else { ?><a href="javascript:publishEvent(<?php echo $eventId; ?>);"><img src="images/icons/unpublished.png" border=0 /></a><?php } ?></span>
                                </div>
                            </div>                       
                            <div class="event_row_col7 <?php echo $class; ?>">
                                <div id="table_cell"><a href="new_event.php?event_id=<?php echo $eventId; ?>&calendar_id=<?php echo $_GET["calendar_id"]; ?>"><?php echo $lang["MODIFY"]; ?></a></div>
                            </div>
                                                       
                           
                            <div id="empty"></div>
						</div>
						<?php 
						$i++;
						} ?>
                    </div>
                </div>
            </form>
            <div id="cleardiv"></div>
            <div id="rowspace"></div>
        </div>
    </div>
</div>
<?php
include 'include/footer.php';
?>
