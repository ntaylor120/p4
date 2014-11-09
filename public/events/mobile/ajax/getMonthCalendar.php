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
				<div class="day_container float_left bg_grey"><a href="#"></a></div>
				<?php
			}
		}
		$numevents = $listObj->getEventsPerDay($_GET["year"],$_GET["month"],$daynum,$_GET["calendar_id"]);
		$background = "bg_white";
		$newstyle='';
		$over=1;
		
		if($numevents == 0) {
			$background="bg_white";
			$over = 0;
		} else if($daydata["yearnum"].$daydata["monthnum"].$daydata["daynum"] == date('Ymd')) {
			$background="bg_black";
		} 
		if($daydata["dayofweek"] == $lastWeekDay) {
			
			?>
			<div class="day_container float_left <?php echo $background; ?>" style="margin-right: 0px;"><a href="#" year="<?php echo $_GET["year"]; ?>" month="<?php echo $_GET["month"]; ?>" day="<?php echo $daynum; ?>" popup="<?php echo $events_popup_enabled; ?>" over="<?php echo $over; ?>">
				<div class="m_day_number mark_grey_less" <?php echo $newstyle; ?>><?php echo $daynum; ?></div>
                
                <?php
				if($numevents>0) {
					?>
                    <div class="bg_green m_number_event mark_white" <?php echo $newstyle; ?>>
						<?php
                        echo $numevents;
                        ?>
                    </div>
                    <?php
				} 
				?>
				
				
				
			</a></div>
			<?php
		} else {
			?>
			<div class="day_container float_left <?php echo $background; ?>"><a href="#" year="<?php echo $_GET["year"]; ?>" month="<?php echo $_GET["month"]; ?>" day="<?php echo $daynum; ?>" popup="<?php echo $events_popup_enabled; ?>" over="<?php echo $over; ?>">
				<div class="m_day_number mark_grey_less" <?php echo $newstyle; ?>><?php echo $daynum; ?></div>
                
				<?php
				if($numevents>0) {
					?>
                    <div class="bg_green m_number_event mark_white" <?php echo $newstyle; ?>>
						<?php
                        echo $numevents;
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
		
		if($j == ($lastDay+1)) {
			
			?>
            <div class="day_container float_left bg_grey"  style="margin-right: 0px;"><a href="#"></a></div>
            <?php
		} else {
			?>
            <div class="day_container float_left bg_grey" ><a href="#"></a></div>
            <?php
		}
		
	}
	?>
	<script>
		$(function() {
			$('#month_nav_prev').html("<a href=\"javascript:getPreviousMonth(<?php echo $calendarObj->getCalendarId(); ?>);\" class=\"m_month_nav_button float_left bg_black\"><img src=\"mobile/images/prev.png\" /></a>");
			$('#month_nav_next').html("<a href=\"javascript:getNextMonth(<?php echo $calendarObj->getCalendarId(); ?>);\" class=\"m_month_nav_button float_left bg_black\"><img src=\"mobile/images/next.png\" /></a>");
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
				<div class="day_container float_left bg_grey"><a href="#"></a></div>
				<?php
			}
		}
		
		$background = "bg_white";
		$newstyle='';
		$over=0;
		
		if($daydata["dayofweek"] == $lastWeekDay) {
			
			?>
			<div class="day_container float_left <?php echo $background; ?>" style="margin-right: 0px;"><a href="#" year="<?php echo $_GET["year"]; ?>" month="<?php echo $_GET["month"]; ?>" day="<?php echo $daynum; ?>" popup="<?php echo $events_popup_enabled; ?>" over="<?php echo $over; ?>">
				<div class="m_day_number mark_grey_less" <?php echo $newstyle; ?>><?php echo $daynum; ?></div>
                
               
				
			</a></div>
			<?php
		} else {
			?>
			<div class="day_container float_left <?php echo $background; ?>"><a href="#" year="<?php echo $_GET["year"]; ?>" month="<?php echo $_GET["month"]; ?>" day="<?php echo $daynum; ?>" popup="<?php echo $events_popup_enabled; ?>" over="<?php echo $over; ?>">
				<div class="m_day_number mark_grey_less" <?php echo $newstyle; ?>><?php echo $daynum; ?></div>
                
				
				
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
		if($j == ($lastDay+1)) {
			
			?>
            <div class="day_container float_left bg_grey"  style="margin-right: 0px;"><a href="#"></a></div>
            <?php
		} else {
			?>
            <div class="day_container float_left bg_grey" ><a href="#"></a></div>
            <?php
		}
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