<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$item_id = $_REQUEST["item_id"];	
	
	mysql_query("DELETE FROM events_calendars WHERE calendar_id = ".$item_id);
	
	//delete events
	
	mysql_query("DELETE FROM events_events WHERE calendar_id=".$item_id);
	
	
}


?>
<div class="calendar_title_col1">
    <div id="table_cell">#</div>
</div>
<div class="calendar_title_col2">
    <div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_calendars','calendars[]');" /></div>
</div>
<div class="calendar_title_col3">
    <div id="table_cell"><?php echo $lang["CALENDAR_TITLE"]; ?></div>
</div>
<div class="calendar_title_col4">
    <div id="table_cell"><?php echo $lang["CALENDAR_LINK"]; ?></div>
</div>
<div class="calendar_title_col5">
    <div id="table_cell"><?php echo $lang["CALENDAR_PUBLISHED"]; ?></div>
</div>
<div class="calendar_title_col6">
    <div id="table_cell"></div>
</div>
<div class="calendar_title_col7">
    <div id="table_cell"></div>
</div>
<div class="calendar_title_col8">
    <div id="table_cell"></div>
</div>
<div id="empty"></div>
<?php                         
$arrayCalendars = $listObj->getCalendarsList();                        
$i=1;
foreach($arrayCalendars as $calendarId => $calendar) {																			
    if($i % 2) {
        $class="alternate_table_row_white";
    } else {
        $class="alternate_table_row_grey";
    }
    
?>
<div id="row_<?php echo $calendarId; ?>">
    <div class="calendar_row_col1 <?php echo $class; ?>">
        <div id="table_cell"><?php echo $i; ?></div>
    </div>
    <div class="calendar_row_col2 <?php echo $class; ?>">
        <div id="table_cell"><input type="checkbox" name="calendars[]" value="<?php echo $calendarId; ?>" onclick="javascript:disableSelectAll('manage_calendars',this.checked);" /></div>
    </div>                    
    <div class="calendar_row_col3 <?php echo $class; ?>">
        <div id="table_cell">
            <span id="title_display_<?php echo $calendarId; ?>"><?php echo $calendar["calendar_title"]; ?></span>
            <span id="title_input_<?php echo $calendarId; ?>" style="display:none"><input type="text" name="calendar_title" id="calendar_title_<?php echo $calendarId; ?>" value="<?php echo $calendar["calendar_title"]; ?>" ></span>
        
        </div>
    </div>
    <div class="calendar_row_col4 <?php echo $class; ?>">
        <div id="table_cell"><?php echo $settingObj->getSiteDomain()."/index.php?calendar_id=".$calendarId; ?></div>
    </div>  
    <div class="calendar_row_col5 <?php echo $class; ?>">
        <div id="table_cell"><span id="publish_<?php echo $calendarId; ?>"><?php if($calendar["calendar_active"]=='1') { ?><a href="javascript:unpublishCalendar(<?php echo $calendarId; ?>);"><img src="images/icons/published.png" border=0 /></a><?php } else { ?><a href="javascript:publishCalendar(<?php echo $calendarId; ?>);"><img src="images/icons/unpublished.png" border=0 /></a><?php } ?></span>
        <?php
        if($calendar["calendar_order"] > 0) {
        ?>
        <br /><input type="button" value="<?php echo $lang["SET_DEFAULT_CALENDAR"]; ?>" onclick="javascript:setDefaultCalendar(<?php echo $calendarId; ?>);"/>
        <?php
        }
        ?>
        </div>
    </div>                       
    <div class="calendar_row_col6 <?php echo $class; ?>">
        <div id="table_cell"><span id="modify_<?php echo $calendarId; ?>"><a href="javascript:editItem(<?php echo $calendarId; ?>);"><?php echo $lang["MODIFY_NAME"]; ?></a></span></div>
    </div>
    <div class="calendar_row_col7 <?php echo $class; ?>">
        <div id="table_cell"><a href="events.php?calendar_id=<?php echo $calendarId; ?>"><?php echo $lang["EVENTS"]; ?></a></div>
    </div>
    <div class="calendar_row_col8 <?php echo $class; ?>">
        <div id="table_cell"><a href="javascript:delItem(<?php echo $calendarId; ?>,'calendars','calendar_id');"><?php echo $lang["DELETE"]; ?></a></div>
    </div>                            
    
    <div id="empty"></div>
</div>
<?php 
$i++;
} ?>|<?php echo count($arrayCalendars);?>
