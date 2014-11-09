<?php
include '../common.php';
$calendarObj->setCalendar($_GET["calendar_id"]);
$arrayEvents = $listObj->getEventsPerDayList($_GET["year"],$_GET["month"],$_GET["day"],$_GET["calendar_id"]);

if($settingObj->getTimeFormat() == "12") {
	
	$classFirstColumnHead="box_preview_header_time_am";
	$classFirstColumn="box_preview_row_time_am";
	
} else {
	$classFirstColumnHead="box_preview_header_time";
	$classFirstColumn="box_preview_row_time";
}


?>
<div class="box_preview_column">
	<!-- header column list -->
    <div class="box_preview_header_list">
        <div class="<?php echo $classFirstColumnHead; ?>"><?php echo $lang["EVENTPOPUP_TIME"]; ?></div>
        <div class="box_preview_header_name"><?php echo $lang["EVENTPOPUP_NAME"]; ?></div>
        <div class="box_preview_header_available"><?php echo $lang["EVENTPOPUP_ADMISSION"]; ?></div>
        <div class="cleardiv"></div>
    </div>
	<?php
    foreach($arrayEvents as $eventId => $event) {
	  ?>
	  <div class="box_preview_row">
      	<div class="<?php echo $classFirstColumn; ?>">
        	<?php
			$arrayCustomTimes=$eventObj->getEventCustomTimes($eventId,$_GET["year"]."-".$_GET["month"]."-".$_GET["day"]);
			if(count($arrayCustomTimes)>0) {
				$i=0;
				foreach($arrayCustomTimes as $dayId =>$day) {
					if($i == 0) {
						//can see just first time
						if($settingObj->getTimeFormat() == '24') {
							echo substr($day["day_time_from"],0,5);
						} else {
							echo date('h:i a',strtotime(substr($day["day_time_from"],0,5)));
						}
					}
					$i++;
				}
			} else {
				if($event["event_starttime"]!='00:00:00') {
					if($settingObj->getTimeFormat() == '24') {
						echo substr($event["event_starttime"],0,5);
					} else {
						echo date('h:i a',strtotime(substr($event["event_starttime"],0,5)));
					}
					
				} else {
					echo "---";
				}
			}
            ?>
			
        </div>
        <div class="box_preview_row_name">
        	<?php
			if(strlen($event["event_title"])>40) {
				echo substr($event["event_title"],0,40)."...";
			} else {
				echo $event["event_title"];
			}
			?>
        </div>
        <div class="box_preview_row_available">
        	<?php
			if($event["event_free"] == -1) {
				?>
                <span style="color: #C30;"><?php echo $lang["EVENTPOPUP_WITH_FEE"]; ?></span>
                <?php			
			} else if($event["event_free"] == 1) {
				?>
                <span style="color: #0C3;"><?php echo $lang["EVENTPOPUP_FREE"]; ?></span>
                <?php
			} 
			?>
        </div>
      </div>
	  <?php
    }
    ?>
</div>
