<?php
include '../common.php';
$calendarObj->setCalendar($_GET["calendar_id"]);
$arrayEvents = $listObj->getEventsPerDayList($_GET["year"],$_GET["month"],$_GET["day"],$_GET["calendar_id"]);
//first loop on events to get the longer title
$longest=0;
foreach($arrayEvents as $eventId => $event) {
	if($longest<strlen($event["event_title"])) {
		$longest=strlen($event["event_title"]);
	}
}
$mid_column_width=$longest*8;
//calculate how many columns we need
$columns=ceil(count($arrayEvents)/8);
//max number columns is 9, so if there are too many slots, have to add lines instead of columns
if($settingObj->getTimeFormat() == "12") {
	$maxColumn = 3;
	$styleFirstColumn="width:60px";
} else {
	$maxColumn = 3;
	$styleFirstColumn="width:40px";
}
$lines=8;
if($columns>$maxColumn) {
	$columns=$maxColumn;
	$lines=9;
	do {
		$lines++;
	} while(ceil(count($arrayEvents)/$lines)>$maxColumn);
}

$totCols=0;

?>
<div class="box_preview_column">
	<!-- header column list -->
    <div class="box_preview_header_list">
        <div class="box_preview_header_time" style="<?php echo $styleFirstColumn; ?>"><?php echo $lang["EVENTPOPUP_TIME"]; ?></div>
        <div class="box_preview_header_name"><?php echo $lang["EVENTPOPUP_NAME"]; ?></div>
        <div class="box_preview_header_available"><?php echo $lang["EVENTPOPUP_ADMISSION"]; ?></div>
    </div>
	<?php
	$z=1;
    foreach($arrayEvents as $eventId => $event) {
		
	  ?>
	  <div class="box_preview_row">
      	<div class="box_preview_row_time"  style="<?php echo $styleFirstColumn; ?>">
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
        <div class="box_preview_row_name"><?php echo $event["event_title"]; ?></div>
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
	  if($z % $lines == 0 && $z<count($arrayEvents)) {
		  $totCols++;
		  if($totCols == $columns-1) {
			  $styleAttr='style="margin-right: 20px;margin-left: 20px;"';
		  } else {
			  $styleAttr='style="margin-left: 20px;"';
		  }
		  ?>
          </div>
          <div class="box_preview_column" <?php echo $styleAttr; ?>>
          	<div class="box_preview_header_list">
                <div class="box_preview_header_time"><?php echo $lang["EVENTPOPUP_TIME"]; ?></div>
                <div class="box_preview_header_name"><?php echo $lang["EVENTPOPUP_NAME"]; ?></div>
                <div class="box_preview_header_available"><?php echo $lang["EVENTPOPUP_ADMISSION"]; ?></div>
            </div>
          <?php
	  }
	  $z++;
    }
    ?>
</div>
