<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$title = $_REQUEST["calendar_title"];
	
	$newid=$calendarObj->addCalendar($title);
	$calendarObj->setCalendar($newid);
	$newnum=$calendarObj->getCalendarRecordcount();
	if($newnum % 2) {
		$newclass="alternate_table_row_white";
	} else {
		$newclass="alternate_table_row_grey";
	}
	
	?>
    <div id="row_<?php echo $newid; ?>">
        <div class="calendar_row_col1 <?php echo $newclass; ?>">
            <div id="table_cell"><?php echo $newnum; ?></div>
        </div>
        <div class="calendar_row_col2 <?php echo $newclass; ?>">
            <div id="table_cell"><input type="checkbox" name="calendars[]" value="<?php echo $newid; ?>" onclick="javascript:disableSelectAll('manage_calendars',this.checked);" /></div>
        </div>                    
        <div class="calendar_row_col3 <?php echo $newclass; ?>">
            <div id="table_cell">
            	<span id="title_display_<?php echo $newid; ?>"><?php echo $calendarObj->getCalendarTitle(); ?></span>
            	<span id="title_input_<?php echo $newid; ?>" style="display:none"><input type="text" name="calendar_title" id="calendar_title_<?php echo $newid; ?>" value="<?php echo $calendarObj->getCalendarTitle(); ?>" ></span>	
            </div>
        </div>
        <div class="calendar_row_col4 <?php echo $newclass; ?>">
            <div id="table_cell"><?php echo $settingObj->getSiteDomain()."/index.php?calendar_id=".$newid; ?></div>
        </div>
        <div class="calendar_row_col5 <?php echo $newclass; ?>">
            <div id="table_cell"><span id="publish_<?php echo $newid; ?>"><?php if($calendarObj->getCalendarActive()=='1') { ?><a href="javascript:unpublishCalendar(<?php echo $newid; ?>);"><img src="images/icons/published.png" border=0 /></a><?php } else { ?><a href="javascript:publishCalendar(<?php echo $newid; ?>);"><img src="images/icons/unpublished.png" border=0 /></a><?php } ?></span>
            <?php
			if($calendarObj->getCalendarOrder() > 0) {
			?>
			<br /><input type="button" value="<?php echo $lang["SET_DEFAULT_CALENDAR"]; ?>" onclick="javascript:setDefaultCalendar(<?php echo $calendarObj->getCalendarId(); ?>);"/>
			<?php
			}
			?></div>
        </div>                       
        <div class="calendar_row_col6 <?php echo $newclass; ?>">
            <div id="table_cell"><span id="modify_<?php echo $newid; ?>"><a href="javascript:editItem(<?php echo $newid; ?>);"><?php echo $lang["MODIFY_NAME"]; ?></a></span></div>
        </div>
        <div class="calendar_row_col7 <?php echo $newclass; ?>">
            <div id="table_cell"><a href="events.php?calendar_id=<?php echo $newid; ?>"><?php echo $lang["MANAGE_EVENTS"]; ?></a></div>
        </div>
        <div class="calendar_row_col8 <?php echo $newclass; ?>">
            <div id="table_cell"><a href="javascript:delItem(<?php echo $newid; ?>,'calendars','calendar_id');"><?php echo $lang["DELETE"]; ?></a></div>
        </div>                            
        
        <div id="empty"></div>
    </div>
	
<?php
}
?>