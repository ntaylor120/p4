<?php
include '../common.php';
//check date format settings
if($settingObj->getDateFormat() == "UK" || $settingObj->getDateFormat() == "EU") {
	$startDay=1;
	$weekday_format="N";
	$lastWeekDay=7;
} else {
	$startDay=0;
	$weekday_format="w";
	$lastWeekDay=6;
}

if($_GET["calendar_id"] > 0) {
	
	$calendarObj->setCalendar($_GET["calendar_id"]);
	$arrayMonth = $listObj->getMonthCalendar($_GET["month"],$_GET["year"]);
	
	$i = 0;
	$events_popup_enabled = $settingObj->getEventsPopupEnabled();
	foreach($arrayMonth as $daynum => $daydata) {
		if($i == 0) {
			//check what's first week day and add cells
			for($j=$startDay;$j<$daydata["dayofweek"];$j++) {
				?>
				<div class="day_container day_grey"><a href="#"></a></div>
				<?php
			}
		}
		$numevents = $listObj->getEventsPerDay($_GET["year"],$_GET["month"],$daynum,$_GET["calendar_id"]);
		$background = "day_white";
		$newstyle='';
		$over=1;
		
		if($numevents == 0) {
			$background="day_white";
			$over = 0;
		} else if($daydata["yearnum"].$daydata["monthnum"].$daydata["daynum"] == date('Ymd')) {
			$background="day_black";
		}
		if($daydata["dayofweek"] == $lastWeekDay) {
			
			?>
			<div class="day_container <?php echo $background; ?>" style="margin-right: 0px;"><a href="#" year="<?php echo $_GET["year"]; ?>" month="<?php echo $_GET["month"]; ?>" day="<?php echo $daynum; ?>" popup="<?php echo $events_popup_enabled; ?>" over="<?php echo $over; ?>">
				<div class="day_number" <?php echo $newstyle; ?>><?php echo $daynum; ?></div>
                <div class="day_book">
					<?php
					if($numevents>0 && $daydata["yearnum"].$daydata["monthnum"].$daydata["daynum"] >= date('Ymd')) {
						echo $lang["GETMONTHCALENDAR_CHECK_NOW"];
					} 
					?>
				</div>
                <?php
				if($numevents>0) {
					?>
                    <div class="day_slots" <?php echo $newstyle; ?>>
						<?php
                        echo $lang["GETMONTHCALENDAR_EVENTS_AVAILABLE"].": ".$numevents;
                        ?>
                    </div>
                    <?php
				} else {
					$newstyle='style="color:#DDD"';
					?>
                    <div class="day_slots" <?php echo $newstyle; ?>>
						<?php
						echo $lang["GETMONTHCALENDAR_NO_EVENTS_AVAILABLE"];
						 ?>
                    </div>
                    <?php
				}
				?>
				
				
				
			</a></div>
			<?php
		} else {
			?>
			<div class="day_container <?php echo $background; ?>"><a href="#" year="<?php echo $_GET["year"]; ?>" month="<?php echo $_GET["month"]; ?>" day="<?php echo $daynum; ?>" popup="<?php echo $events_popup_enabled; ?>" over="<?php echo $over; ?>">
				<div class="day_number" <?php echo $newstyle; ?>><?php echo $daynum; ?></div>
                <div class="day_book">
					<?php
					if($numevents>0) {
						echo $lang["GETMONTHCALENDAR_CHECK_NOW"];
					} 
					?>
				</div>
				<?php
				if($numevents>0) {
					?>
                    <div class="day_slots" <?php echo $newstyle; ?>>
						<?php
                        echo $lang["GETMONTHCALENDAR_EVENTS_AVAILABLE"].": ".$numevents;
                        ?>
                    </div>
                    <?php
				} else {
					$newstyle='style="color:#DDD"';
					?>
                    <div class="day_slots" <?php echo $newstyle; ?>>
						<?php
						echo $lang["GETMONTHCALENDAR_NO_EVENTS_AVAILABLE"];
						 ?>
                    </div>
                    <?php
				}
				?>
				
			</a></div>
			<?php
		}
		
		$i++;
		if($i == count($arrayMonth)) {
			$lastDay=$daydata["dayofweek"];
		}
	}
	//check what's last week day and add cells
	for($j=$lastWeekDay;$j>$lastDay;$j--) {
		?>
		<div class="day_container day_grey"><a href="#"></a></div>
		<?php
	}
	?>
	<script>
		$(function() {
			$('#month_nav_prev').html("<a href=\"javascript:getPreviousMonth(<?php echo $calendarObj->getCalendarId(); ?>);\" class=\"month_nav_button month_nav_button_prev\"></a>");
			$('#month_nav_next').html("<a href=\"javascript:getNextMonth(<?php echo $calendarObj->getCalendarId(); ?>);\" class=\"month_nav_button month_nav_button_next\"></a>");
			$('#list_button').html('<a href="javascript:getEventsHomeList(<?php echo $calendarObj->getCalendarId(); ?>,1,\'future\');"><?php echo $lang["VIEW_LIST"]; ?></a>');
		});
	</script>
<?php
} else {
	
    $arrayMonth = $listObj->getMonthCalendar($_GET["month"],$_GET["year"]);
	
	$i = 0;
	foreach($arrayMonth as $daynum => $daydata) {
		if($i == 0) {
			//check what's first week day and add cells
			for($j=$startDay;$j<$daydata["dayofweek"];$j++) {
				?>
				<div class="day_container day_grey"><a href="#"></a></div>
				<?php
			}
		}
		
		$background = "day_white";
		$newstyle='';
		$over=0;
		
		if($daydata["dayofweek"] == $lastWeekDay) {
			
			?>
			<div class="day_container <?php echo $background; ?>" style="margin-right: 0px;"><a href="#" year="<?php echo $_GET["year"]; ?>" month="<?php echo $_GET["month"]; ?>" day="<?php echo $daynum; ?>" popup="<?php echo $events_popup_enabled; ?>" over="<?php echo $over; ?>">
				<div class="day_number" <?php echo $newstyle; ?>><?php echo $daynum; ?></div>
                <div class="day_slots" <?php echo $newstyle; ?>>
					
				</div>
                <div class="day_book">
					
				</div>
				
				
			</a></div>
			<?php
		} else {
			?>
			<div class="day_container <?php echo $background; ?>"><a href="#" year="<?php echo $_GET["year"]; ?>" month="<?php echo $_GET["month"]; ?>" day="<?php echo $daynum; ?>" popup="<?php echo $events_popup_enabled; ?>" over="<?php echo $over; ?>">
				<div class="day_number" <?php echo $newstyle; ?>><?php echo $daynum; ?></div>
                <div class="day_slots" <?php echo $newstyle; ?>>
					
				</div>
                <div class="day_book">
					
				</div>
				
				
			</a></div>
			<?php
		}
		
		$i++;
		if($i == count($arrayMonth)) {
			$lastDay=$daydata["dayofweek"];
		}
	}
	//check what's last week day and add cells
	for($j=$lastWeekDay;$j>$lastDay;$j--) {
		?>
		<div class="day_container day_grey"><a href="#"></a></div>
		<?php
	}
	?>
	<script>
		$(function() {
			$('#month_nav_prev').html("<a href=\"javascript:getPreviousMonth('<?php echo $calendarObj->getCalendarId(); ?>');\" class=\"month_nav_button month_nav_button_prev\"></a>");
			$('#month_nav_next').html("<a href=\"javascript:getNextMonth('<?php echo $calendarObj->getCalendarId(); ?>');\" class=\"month_nav_button month_nav_button_next\"></a>");
			$('#list_button').html('<a href="javascript:getEventsHomeList(<?php echo $calendarObj->getCalendarId(); ?>,1,\'future\');"><?php echo $lang["VIEW_LIST"]; ?></a>');
		});
	</script>
    <?php
}
?>