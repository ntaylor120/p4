<?php 
include 'common.php';
if(!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
	header('Location: login.php');
}

if(isset($_POST["operation"]) && $_POST["operation"] != '') {
	$arrCalendars=$_POST["calendars"];
	$qryString = "0";
	for($i=0;$i<count($arrCalendars); $i++) {
		$qryString .= ",".$arrCalendars[$i];
	}
		
	switch($_POST["operation"]) {
		case "publishCalendars":
			$calendarObj->publishCalendars($qryString);
			break;
		case "unpublishCalendars":
			$calendarObj->unpublishCalendars($qryString);
			break;
		case "delCalendars":
			$calendarObj->delCalendars($qryString);
			break;
		case "duplicateCalendars":
			$calendarObj->duplicateCalendars($qryString);
			break;
	}                
	header('Location: calendars.php');
}
include 'include/header.php';
?>
<!-- 
=====================================================================
=====================================================================
-->

<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
        
        	<!-- 
            =======================
            === menu ==
            =======================
            -->
			<?php
            include 'include/menu.php';
            ?>
            <?php
			$arrayCalendars = $listObj->getCalendarsList(); 
			
			?>
           
        
            <script>
                
                
                function orderby(column,type) {
                
                    $.ajax({
                      url: 'ajax/setCalendarsOrderby.php?order_by='+column+'&type='+type,
                      success: function(data) {
                          $('#table').hide().html(data).fadeIn(2000);						 
                        
                      }
                    });
                    
                }
                
                function delItem(itemId) {
                    if(confirm("<?php echo $lang["CALENDARS_SINGLE_DELETE_CONFIRM"]; ?>")) {
                        $.ajax({
                          url: 'ajax/delCalendarItem.php?item_id='+itemId,
                          success: function(data) {
							  dataArr=data.split("|");
                              $('#table').hide().html(dataArr[0]).fadeIn(2000);
                              if(Trim(dataArr[1]) == "0") {
								  hideActionBar();
							  }
                            
                          }
                        });
                    } 
                }
                function publishCalendar(itemId) {
                    if(confirm("<?php echo $lang["CALENDARS_SINGLE_PUBLISH_CONFIRM"]; ?>")) {
                        $.ajax({
                          url: 'ajax/publishCalendar.php?calendar_id='+itemId,
                          success: function(data) {
                              $('#publish_'+itemId).html('<a href="javascript:unpublishCalendar('+itemId+');"><img src="images/icons/published.png" border=0 /></a>');								 							 
                            
                          }
                        });
                    } 
                }
                function unpublishCalendar(itemId) {
                    if(confirm("<?php echo $lang["CALENDARS_SINGLE_UNPUBLISH_CONFIRM"]; ?>")) {
                        $.ajax({
                          url: 'ajax/unpublishCalendar.php?calendar_id='+itemId,
                          success: function(data) {
                              $('#publish_'+itemId).html('<a href="javascript:publishCalendar('+itemId+');"><img src="images/icons/unpublished.png" border=0 /></a>');								 							 
                            
                          }
                        });
                    } 
                }
                
                function addCalendar() {
                    if(Trim($('#calendar_title').val())!= '') {
                        $('#filter_submit').html('<img src="images/loading.gif">');
                        $.ajax({
                          url: 'ajax/addCalendar.php?calendar_title='+$('#calendar_title').val(),
                          success: function(data) {
                              $('#filter_submit').html('<a href="javascript:addCalendar();"><?php echo $lang["ADD"]; ?></a>');
                              $(data).hide().appendTo('#table').fadeIn(2000);							 
                              $('#calendar_title').val('');
							  showActionBar();
                            
                          }
                        });
                    } else {
                        alert("<?php echo $lang["CALENDARS_ADD_ALERT"]; ?>");
                    }
                }
                function editItem(calendar) {
                    $('#modify_'+calendar).html('<a href="javascript:saveItem('+calendar+');"><?php echo $lang["SAVE"]; ?></a>');
                    $('#title_display_'+calendar).css({"display":"none"});
                    $('#title_input_'+calendar).css({"display":"block"});
                    
                }
                function saveItem(calendar) {
                    if(Trim($('#calendar_title_'+calendar).val()) != '') {
                        $.ajax({
                          url: 'ajax/saveCalendar.php?item_id='+calendar+"&title="+$('#calendar_title_'+calendar).val(),
                          success: function(data) {
                             
                              $('#title_display_'+calendar).css({"display":"block"});
                              $('#title_input_'+calendar).css({"display":"none"});
                              $('#title_display_'+calendar).html($('#calendar_title_'+calendar).val());
                              $('#modify_'+calendar).html('<a href="javascript:editItem('+calendar+');"><?php echo $lang["MODIFY_NAME"]; ?></a>');
                              $('#row_'+calendar).hide().fadeIn(2000);
                              
                             
                            
                          }
                        });
                    }
                }
                function setDefaultCalendar(calendar) {
                    $.ajax({
                      url: 'ajax/setDefaultCalendar.php?calendar_id='+calendar,
                      success: function(data) {
                        document.location.reload();
                      }
                    });
                }
                $(function() {
                    <?php
                    if(count($arrayCalendars)>0) {
                        ?>
                        showActionBar();
                        <?php
                    }
                    ?>
                });
                
                function showActionBar() {
                    $('#action_bar').slideDown();
                }
                function hideActionBar() {
                    $('#action_bar').slideUp();
                }
            </script>
             
            <!-- 
            =======================
            === create calendar ==
            =======================
            -->  
            <div class="add_calendar_container">
                <div class="create_calendar"><strong><?php echo $lang["CREATE_CALENDAR"]; ?></strong>: <?php echo $lang["TYPE_NAME"]; ?></div> 
                <div class="create_calendar"><input type="text" id="calendar_title" name="calendar_title"></div>   
                <div class="create_calendar"><a href="javascript:addCalendar();"><?php echo $lang["ADD"]; ?></a></div>
            </div>    
            <!-- 
            =======================
            === action bar ==
            =======================
            -->
            <?php
			
			?>
            <div id="action_bar" style="display:none"> 
            	<div id="action"><a onclick="javascript:delItems('manage_calendars','calendars[]','duplicateCalendars','<?php echo $lang["CALENDARS_MULTIPLE_DUPLICATE_CONFIRM"]; ?>')"><?php echo $lang["DUPLICATE"]; ?></a></div>
                <div id="action"><a onclick="javascript:delItems('manage_calendars','calendars[]','delCalendars','<?php echo $lang["CALENDARS_MULTIPLE_DELETE_CONFIRM"]; ?>')"><?php echo $lang["DELETE"]; ?></a></div>
                <div id="action"><a onclick="javascript:delItems('manage_calendars','calendars[]','unpublishCalendars','<?php echo $lang["CALENDARS_MULTIPLE_UNPUBLISH_CONFIRM"]; ?>')"><?php echo $lang["UNPUBLISH"]; ?></a></div>
                <div id="action"><a onclick="javascript:delItems('manage_calendars','calendars[]','publishCalendars','<?php echo $lang["CALENDARS_MULTIPLE_PUBLISH_CONFIRM"]; ?>')"><?php echo $lang["PUBLISH"]; ?></a></div>
                <div class="title_action"><?php echo $lang["SELECTED_ITEMS"]; ?>:</div>
            </div>
             <!-- 
            =======================
            === table calendars ==
            =======================
            -->
            <form name="manage_calendars" action="" method="post">
                <input type="hidden" name="operation" />
                <input type="hidden" name="calendars[]" value=0 />
                <div id="table_container">
                    <div id="table">
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
                                <div id="table_cell"><a href="events.php?calendar_id=<?php echo $calendarId; ?>"><?php echo $lang["MANAGE_EVENTS"]; ?></a></div>
                            </div>
                            <div class="calendar_row_col8 <?php echo $class; ?>">
                                <div id="table_cell"><a href="javascript:delItem(<?php echo $calendarId; ?>,'calendars','calendar_id');"><?php echo $lang["DELETE"]; ?></a></div>
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