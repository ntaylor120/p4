<?php
include 'common.php';
if(!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
	header('Location: login.php');
}
$calendarObj->setCalendar($_GET["calendar_id"]);

$event_id = 0;
if(isset($_GET["event_id"])) {
	$event_id=$_GET["event_id"];
}
$eventObj->setEvent($event_id);
if(isset($_POST["event_active"])) {
	
	if($eventObj->getEventId()>0) {
			
		$eventObj->updateEvent($settingObj,$func);
		
	} else {
		
		$eventObj->insertEvent($settingObj,$func);
	}
	
	//header('Location: events.php?calendar_id='.$_GET["calendar_id"]);	
	
	$eventObj->setEvent($event_id);
}

include 'include/header.php';


?>
<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
			<?php
			 include 'include/menu.php'; 
			?>
            
            <div id="cleardiv"></div>
            
            <!-- breadcrumbs -->
            <div id="action_bar">
                <div class="breadcrumb"><?php echo $lang["YOU_ARE_IN"]; ?> <a href="calendars.php"><?php echo $lang["CALENDARS"]; ?></a> > <a href="events.php?calendar_id=<?php echo $calendarObj->getCalendarId(); ?>"><?php echo $calendarObj->getCalendarTitle(); ?></a> > <strong><?php if($eventObj->getEventId()>0) { echo $eventObj->getEventTitle(); } else { echo $lang["NEW_EVENT"]; } ?></strong></div>
            </div>

            <!-- TinyMCE -->
			<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
            <script type="text/javascript">
                tinyMCE.init({
                    mode : "exact",
                    elements : "event_description, event_entrance,event_video_text,event_map_text",
                    theme : "advanced",
                    plugins:"paste",
                    theme_advanced_buttons1 : "pastetext,|,bold,italic,underline,strikethrough,|,bullist,numlist,|,indent,outdent,|,undo,redo,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,charmap",
                    theme_advanced_buttons2 : "",
                    theme_advanced_buttons3 :"",
                    theme_advanced_disable : "image,anchor,cleanup,help,code,hr,removeformat,sub,sup",
                    paste_text_use_dialog : true
            
                });
            </script>
            <!-- /TinyMCE -->
            
            
            <!-- ======= JS functions ========== -->
            <script>
				$(function() {
					
					<?php
					if($eventObj->getEventId()>0 && count($eventObj->getEventsCustomTimes())>0) {
						?>
						$('#events_days').attr("checked","checked");
						$('#custom_times').fadeIn();
						$('#plusbutton').fadeIn();
						updateCustomTimes();
						<?php
					}
					?>
					$('.lightbox').lightBox();
					<?php
					if($settingObj->getDateFormat() == "UK") {
						?>
						$.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );
						<?php
					} else if($settingObj->getDateFormat() == "EU") {
						?>
						$.datepicker.setDefaults( $.datepicker.regional[ "eu-EU" ] );
						<?php
					} else {
						?>
						$.datepicker.setDefaults( $.datepicker.regional[ "us-US" ] );
						<?php
					}
					?>
					arrDateFrom=$('#event_date_from').val().split(",");
					$( "#date_to").datepicker({
						altField: "#event_date_to",
						altFormat: "yy,mm,dd",
						//minDate: new Date(arrDateFrom[0],(arrDateFrom[1]-1),arrDateFrom[2]),
						onClose: function(selectedDate) {
							//$( "#date_from" ).datepicker( "option", "maxDate", selectedDate );
							updateCustomTimes();
							
						}
						
					 });
					
					$( "#date_from").datepicker({
						altField: "#event_date_from",
						altFormat: "yy,mm,dd",
						//minDate: new Date(),
						 onClose: function(selectedDate) { 
						 	updateCustomTimes();
			 	 			//$( "#date_to" ).datepicker( "option", "minDate", selectedDate );
							  $( "#date_to").datepicker({
								altField: "#event_date_to",
								altFormat: "yy,mm,dd",
								//minDate: selectedDate,
								onClose: function( selectedDate ) {
									//$( "#date_from" ).datepicker( "option", "maxDate", selectedDate );
									updateCustomTimes();
								}
										
							 });
				 		 
						}
						 
					});
					
					$('#event_starttime').timepicker({
						onClose: function() {
							
						},
						onSelect: function() {
							
							
							arrTimeFrom = $('#event_starttime').val().split(":");
							$('#event_endtime').timepicker({	
								hourMin: parseInt(arrTimeFrom[0]),
								minuteMin: 0,
								secondMin: 0
							});
						}
					});
					
					
					
				});
				
				var uploader;
				function removeImage(filename,inputhidden,li) {
					
					if(li!= "") { 
						document.getElementById(inputhidden).value="";
						$('#'+li).remove();
						uploader._uploadedFiles--;
					}
					$.ajax({
					  type: "POST",
					  url: 'ajax/delEventImage.php',
					  data: "image="+filename,
					  success: function(data) {
						$('#upload_button').hide();
					  }
					});
					
					
					
					
				}
				
				function removeAllPhotos(ins_det_list_id) {
					if(confirm('<?php echo $lang["NEW_EVENT_PHOTOS_DELETE_CONFIRM"]; ?>'))
					{
						var photoEl = document.getElementsByName("photos[]");
						
						for(var i=0;i<photoEl.length; i++) {
							valore=document.getElementsByName("photos[]").item(i).value;
							//alert(document.getElementById(elId).value);
							document.getElementsByName("photos[]").item(i).value="";
							$.ajax({
							   type: "POST",
							   url: "ajax/delEventImage.php",
							   data: "image="+valore,
							   success: function(data){
								
							   }
							 });
							
							
						}
						$('.qq-upload-list').html("");
						$('#delete_all').css({"display": "none"});
						
						uploader._uploadedFiles=0;
						$('#upload_button').show();
						if(ins_det_list_id != '') {
							document.forms[0].list_img_to_del.value = ins_det_list_id;
						}
						$('#file-uploader').show();
					}
					
				}
				
				function DelImage(idphoto,iddiv)
				{
					
					document.getElementById(idphoto).value="";
					document.getElementById(idphoto).name="";
					
					
					uploader._uploadedFiles--;
					if(uploader._uploadedFiles == 0) {
						$('#delete_all').css({"display": "none"});
						
					}
					document.getElementById('file-uploader').style.display = "block";
					
					
						
					document.addevent.list_img_to_del.value = document.addevent.list_img_to_del.value + "|" + iddiv;
						
					
					
					document.getElementById(iddiv).getElementsByTagName('a').item(0).className = "empty";
					document.getElementById(iddiv).style.display = "none";
						
						
					
				}
				
				
				function clearTimeTo() {
					$('#event_endtime').val('');
				}
				
				function clearDateTo() {
					$('#date_to').val('');
					$('#event_date_to').val('');
					$('#weekday_div').fadeOut();
					updateCustomTimes();
				}
				
				function addDays(myDate,days) {
					return new Date(myDate.getTime() + days*24*60*60*1000);
				}
				
				function updateCustomTimes() {
					var arrayDays = new Array();
					var arrDateFrom = $('#event_date_from').val().split(",");
					var dateFrom = new Date(arrDateFrom[0],(arrDateFrom[1]-1),arrDateFrom[2]);
					var dateTo = "";
					if($('#event_date_to').val() == "") {						
						dateTo = new Date(arrDateFrom[0],(arrDateFrom[1]-1),arrDateFrom[2]);
					} else {
						var arrDateTo = $('#event_date_to').val().split(",");
						dateTo = new Date(arrDateTo[0],(arrDateTo[1]-1),arrDateTo[2]);
					}
					var customdatesList = "";
					if($("#event_custom_date_1").val() != '') {
						var customdatesLen = document.getElementsByName('event_custom_dates[]').length;
						
						//custom dates
						for(w=0;w<customdatesLen;w++) {
							if(customdatesList == '') {
								customdatesList = document.getElementsByName('event_custom_dates[]').item(w).value.replace(/,/g,"-");
							} else {
								customdatesList += ","+document.getElementsByName('event_custom_dates[]').item(w).value.replace(/,/g,"-");
							}
							
						}
					}
					//get weekday values
					var weekdaysLen = document.getElementsByName("event_weekday[]").length;
					var weekdayList = "";
					for(var o=0;o<weekdaysLen;o++) {
						if(document.getElementsByName("event_weekday[]").item(o).checked) {
							if(weekdayList == '') {
								weekdayList = document.getElementsByName("event_weekday[]").item(o).value;
							} else {
								weekdayList += ","+document.getElementsByName("event_weekday[]").item(o).value;
							}
						}
					}
					$.ajax({
					  url: 'ajax/updateCustomTimes.php?date_from='+$('#event_date_from').val()+'&date_to='+$('#event_date_to').val()+"&weekdays="+weekdayList+"&customdates="+customdatesList,
					  success: function(data) {
						arrayDays=data.split(",");
						var num_rows = 0;
						$('#custom_times').find("select").each(function() {
							var selected_value = $(this).val();
							/*var newHtml = "";
							newHtml+='<option value="0">Choose day</option>';
							*/
							$(this).html('<option value="0"><?php echo $lang["CHOOSE_DAY"]; ?></option>');
							for(var j=0;j<arrayDays.length;j++) {
								
								var formatted_date = arrayDays[j].substring(6,8)+"/"+arrayDays[j].substring(4,6) + "/" + arrayDays[j].substring(0,4);
								var value_date = arrayDays[j].substring(0,4)+"-"+arrayDays[j].substring(4,6) + "-" + arrayDays[j].substring(6,8);
								if(value_date == selected_value) {
									//newHtml+='<option value="'+value_date+'" selected>'+formatted_date+'</option>';
									$(this).append('<option value="'+value_date+'" selected>'+formatted_date+'</option>');
								} else {
									//newHtml+='<option value="'+value_date+'">'+formatted_date+'</option>';
									$(this).append('<option value="'+value_date+'">'+formatted_date+'</option>');
								}
							}
							//$(this).html(newHtml);
							num_rows++;
						});
						//get from and to
						$('#custom_times').find("input").each(function() {
							if($(this).attr("type") == "text") {
								$(this).timepicker({	
										hourMin: parseInt(0),
										minuteMin: parseInt(0),
										secondMin: 0
								});
								
							}
						});
						$('#custom_times').find("input").each(function() {
							if($(this).attr("type") == "text") {
								
								if($(this).attr("name") == "day_time_from[]") {
									//alert($(this).attr("id")+":"+$(this).val());
									$(this).timepicker('destroy');
									$(this).timepicker({	
										hourMin: parseInt(0),
										minuteMin: 0,
										secondMin: 0,
										onSelect: function() {
								
										arrTimeFrom = $(this).val().split(":");
										//get id of to
										var toid = $(this).attr("id").replace("from","to");
										//alert(toid);
										$('#'+toid).timepicker('destroy');
										$('#'+toid).timepicker({	
											hourMin: parseInt(arrTimeFrom[0]),
											minuteMin: 0,
											secondMin: 0
										});
									}
									});
									<?php
									if($eventObj->getEventId()>0 && count($eventObj->getEventsCustomTimes())>0) {
										?>
										arrTimeFrom = $(this).val().split(":");
										//get id of to
										var toid = $(this).attr("id").replace("from","to");
										//alert(toid);
										$('#'+toid).timepicker('destroy');
										$('#'+toid).timepicker({	
											hourMin: parseInt(arrTimeFrom[0]),
											minuteMin: 0,
											secondMin: 0
										});
										<?php
									}
									?>
								} 
							}
						});
					  }
					});
					//alert(dateFrom+" "+dateTo);
					/*for(var i = dateFrom;i<=dateTo;i = addDays(i,1)) {
						//alert(i.getFullYear()+""+(i.getMonth()+1)+""+i.getDate());
						var day = i.getDate()+"";
						var month = (i.getMonth()+1)+"";
						
						if(day.length==1) {
							day = "0"+day;
						}
						if(month.length==1) {
							month = "0"+month;
						}
						//alert(i.getFullYear()+""+month+""+day);
						arrayDays.push(i.getFullYear()+""+month+""+day);
					}*/
					
				}
				function showCustomTimes(el) {
					if(el.checked == true) {
						$('#custom_times').fadeIn();
						updateCustomTimes();
						if($('#sub_custom_time_1').css("display") == "block") {
							$('#plusbutton').fadeIn();
						}
						/*$("#day_time_from_1").timepicker({	
							onSelect: function() {
					
							arrTimeFrom = $("#day_time_from_1").val().split(":");
							
							$("#day_time_to_1").timepicker({	
								hourMin: parseInt(arrTimeFrom[0]),
								minuteMin: parseInt(arrTimeFrom[1]),
								secondMin: 0
							});
						}
						});*/
					} else {
						$('#custom_times').fadeOut();
						$('#plusbutton').fadeOut();
						//reset custom times in some way
					}
				}
				
				function addCustomTime(lastnum) {
					var arrayDays = new Array();
					var dateFrom = new Date($('#event_date_from').val());
					var dateTo = new Date($('#event_date_to').val());
					for(var i = dateFrom;i<=dateTo;i = addDays(i,1)) {
						arrayDays.push(i.getFullYear()+""+(i.getMonth()+1)+""+i.getDate());
					}
					var newnum = lastnum+1;
					$('#custom_times').append('<div id="cleardiv"></div><div id="custom_time_'+newnum+'" style="margin-top: 10px;" class="custom_times_div"><?php echo $lang["DAY"]; ?>:&nbsp;<select name="day_date[]"  onchange="javascript:showSub(this.options[this.selectedIndex].value,'+newnum+');"><option value="0"><?php echo $lang["CHOOSE_DAY"]; ?></option></select><div id="sub_custom_time_'+newnum+'" style="display:none; margin-top:10px;"><div class="input_container"><?php echo $lang["TIME_FROM"]; ?>:&nbsp;<input type="text" name="day_time_from[]" id="day_time_from_'+newnum+'" value="" readonly="readonly" size ="10" /></div><div class="input_container"><?php echo $lang["TO"]; ?>:&nbsp;<input type="text" name="day_time_to[]" id="day_time_to_'+newnum+'" value="" readonly="readonly" size ="10" /></div><div class="input_container"><a class="trash_button" style="margin-top: 3px;" href="javascript:clearDayTimeTo(\'day_time_to_'+newnum+'\');"></a></div><div class="input_container"><input type="button" id="erase_row" value="<?php echo $lang["ERASE"]; ?>" onclick="javascript:removeCustomTime('+newnum+');"></div></div></div>');
					$('#plusbutton').attr("onclick","javascript:addCustomTime("+newnum+");");
					
					/*$("#day_time_from_"+newnum).timepicker({	
						onSelect: function() {
				
						arrTimeFrom = $("#day_time_from_"+newnum).val().split(":");
						
						$("#day_time_to_"+newnum).timepicker({	
							hourMin: parseInt(arrTimeFrom[0]),
							minuteMin: parseInt(arrTimeFrom[1]),
							secondMin: 0
						});
					}
					});*/
					updateCustomTimes();
					$('#plusbutton').fadeOut();
				}
				function removeCustomTime(num) {
					$('#custom_time_'+num).remove();
					var i = 0;
					$('.custom_times_div').each(function() {
						i++;
					});
					if(i==0) {
						addCustomTime(0);
						$('#plusbutton').fadeOut();
					}
				}
				function clearDayTimeTo(iddiv) {
					$('#'+iddiv).val('');
				}
				function showSub(val,num) {
					
					$('#plusbutton').fadeIn();
					
					if(val != 0) {
						$('#sub_custom_time_'+num).show();
					} else {
						$('#sub_custom_time_'+num).hide();
						$('#plusbutton').fadeOut();
					}
				}
				function showLoader() {
					$('body').prepend('<div id="sfondo" class="modal_sfondo"></div>');
					$('#modal_loading').fadeIn();
				}
				
				function hideLoader() {
					$('#sfondo').remove();
					$('#modal_loading').fadeOut();
				}
				
				function checkTimesEvent() {
					var error = 0;
					$('input[name="event_starttime"]').each(function() {
						var from_value =$(this).val().replace(":","");
						var to_value =$("#"+$(this).attr("id").replace("start","end")).val().replace(":","");
						if(from_value.substring(0,1) == "0") {
							from_value = parseInt(from_value.substring(1,4));
						}
						if(to_value.substring(0,1) == "0") {
							to_value = parseInt(to_value.substring(1,4));
						}
						if(to_value != '' && to_value<from_value) {
							error = 1;
						}
					});
					return error;
				}
				
				function checkCustomTimes() {
					var error = 0;
					$('input[name="day_time_from[]"]').each(function() {
						var from_value =$(this).val().replace(":","");
						var to_value =$("#"+$(this).attr("id").replace("from","to")).val().replace(":","");
						if(from_value.substring(0,1) == "0") {
							from_value = parseInt(from_value.substring(1,4));
						}
						if(to_value.substring(0,1) == "0") {
							to_value = parseInt(to_value.substring(1,4));
						}
						if(to_value != '' && to_value<from_value) {
							error = 1;
						}
					});
					return error;
				}
				function checkEventFree() {
					var checked = 0;
					var formObj = document.forms[0];
					for (var i = 0; i < formObj.event_free.length; i++ ) {
						if (formObj.event_free[i].checked == true) {
							checked++;
						}
					}
					return checked;
				}
				
				function setRecurring(el) {
					if(el.checked) {
						$('#weekday_div').fadeIn();
					} else {
						$('#weekday_div').fadeOut();
					}
				}
				function checkData(frm) {
					with(frm) {
						if(Trim(event_title.value)=='') {
							alert("<?php echo $lang["NEW_EVENT_TITLE_ALERT"]; ?>");
							hideLoader();
							return false;
						} else if(Trim(date_from.value) == '') {
							alert("<?php echo $lang["NEW_EVENT_DATE_ALERT"]; ?>");
							hideLoader();
							return false;
						} else if(parseInt($('#event_date_from').val().replace(",","")) > parseInt($('#event_date_to').val().replace(",",""))) {
							alert("<?php echo $lang["NEW_EVENT_DATE_RANGE_ALERT"]; ?>");
							hideLoader();
							return false;
						} else if(checkTimesEvent() == 1) {
							alert("<?php echo $lang["NEW_EVENT_TIME_RANGE_ALERT"]; ?>");
							hideLoader();
							return false;
						} else if(events_days.checked == true && checkCustomTimes() == 1) {
							alert("<?php echo $lang["NEW_EVENT_CUSTOM_TIME_RANGE_ALERT"]; ?>");
							hideLoader();
							return false;
						} else if(checkEventFree() == 0) {
							alert("<?php echo $lang["NEW_EVENT_ENTRANCE_ALERT"]; ?>");
							hideLoader();
							return false;
						} else if(!isNumeric(Trim(event_seats.value)) || parseInt(Trim(event_seats.value))<-1) {
							alert("<?php echo $lang["NEW_EVENT_SEATS_ALERT"]; ?>");
							hideLoader();
							return false;
						} else {
							return true;
						}
					}
				}
			</script>
            
            
			<!-- =======================================================
            ====== form begins here ========
            ============================================================ -->
            
			<div id="form_container">
			<form name="addevent" action="" method="post" enctype="multipart/form-data" onsubmit="return checkData(this);">
				<input type="hidden" name="calendar_id" value="<?php echo $_GET["calendar_id"]; ?>" />
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>" />
                
                
                
                
                <!-- event title -->
                <div id="label_input">
					<div class="label_title"><label for="event_title"><?php echo $lang["NEW_EVENT_TITLE_LABEL"]; ?> </label></div>
				</div>
				<div id="input_box">
					<input type="text" class="long_input_box" name="event_title" id="event_title" value="<?php echo $eventObj->getEventTitle(); ?>" >
				</div>
				<div id="rowspace"></div>
				<div id="rowline"></div>
				<div id="rowspace"></div>
                
                
                
                
                
                <!-- event date -->
				<div id="label_input">
					<div class="label_title"><label for="date"><?php echo $lang["NEW_EVENT_DATE_LABEL"]; ?></label></div>
				</div>
				<div id="input_box">
					<div class="input_container"><?php echo $lang["FROM"]; ?>:&nbsp;<input type="text" class="short_input_box" name="date_from" id="date_from" readonly="readonly"  value="<?php if($eventObj->getEventId() >0 ) {if($settingObj->getDateFormat() == "UK") { echo strftime('%d/%m/%Y',strtotime($eventObj->getEventDateFrom())); } else if($settingObj->getDateFormat() == "EU") { echo strftime('%Y/%m/%d',strtotime($eventObj->getEventDateFrom())); } else { echo strftime('%m/%d/%Y',strtotime($eventObj->getEventDateFrom())); } } ?>" >
					<input type="hidden" name="event_date_from" id="event_date_from" value="<?php  echo str_replace("-",",",$eventObj->getEventDateFrom()); ?>"></div>
                    
                    <div class="input_container"><?php echo $lang["TO"]; ?>:&nbsp;<input type="text" class="short_input_box" name="date_to" id="date_to" readonly="readonly"  value="<?php if($eventObj->getEventId() >0 && $eventObj->getEventDateTo() != '0000-00-00') {  if($settingObj->getDateFormat() == "UK") {echo strftime('%d/%m/%Y',strtotime($eventObj->getEventDateTo()));} else if($settingObj->getDateFormat() == "EU") { echo strftime('%Y/%m/%d',strtotime($eventObj->getEventDateTo()));} else {echo strftime('%m/%d/%Y',strtotime($eventObj->getEventDateTo()));} } ?>" ></div>
                    
                    <div class="input_container"><a class="trash_button" href="javascript:clearDateTo();"></a></div>
                    
					<input type="hidden" name="event_date_to" id="event_date_to" value="<?php echo str_replace("-",",",$eventObj->getEventDateTo()); ?>">
				</div>
				<div id="rowspace"></div>
				<div id="rowline"></div>
				<div id="rowspace"></div>
                
                <!-- recurring events -->
                <div id="label_input">
					<div class="label_title"><label for="event_recurring"><?php echo $lang["NEW_EVENT_EVENT_RECURRING_LABEL"]; ?></label></div>
				</div>
				<div id="input_box">
					<?php echo $lang["NEW_EVENT_EVENT_RECURRING_SUBLABEL"]; ?>&nbsp;<input type="checkbox" name="event_recurring" id="event_recurring" value="1" onclick="javascript:setRecurring(this);">
				</div>
               <script>
			   		$(function() {
						$( "#custom_date_1").datepicker({
							altField: "#event_custom_date_1",
							altFormat: "yy,mm,dd",
							onClose: function(selectedDate) {
								$('#weekday_choice').fadeOut();
								$('#date_to').val('');
								$('#event_date_to').val('');
								updateCustomTimes();
								$('#plusbutton_date').fadeIn();
							}
						 });
					});
			   		function addCustomDate(num) {
						$('#plusbutton_date').fadeOut();
						$('#custom_dates').append('<div id="cleardiv"></div><div id="date_'+num+'"><div class="input_container"><input type="text" id="custom_date_'+num+'" /></div><input type="hidden" name="event_custom_dates[]" id="event_custom_date_'+num+'" /><div class="input_container"><input type="button" id="erase_row" value="<?php echo $lang["ERASE"]; ?>" onclick="javascript:removeCustomDate('+num+');"></div></div>');
						$("#custom_date_"+num).datepicker({
							altField: "#event_custom_date_"+num,
							altFormat: "yy,mm,dd",
							onClose: function(selectedDate) {
								updateCustomTimes();
								$('#plusbutton_date').fadeIn();
							}
						 });
						
						 $('#plusbutton_date').attr('onclick','javascript:addCustomDate('+(num+1)+');');
						 
					}
					function removeCustomDate(num) {
						$('#event_custom_date_'+num).val('');
						$('#custom_date_'+num).val('');
						$('#date_'+num).remove();
						if($('input[name="event_custom_dates[]"]').length == 1 && $('#event_custom_date_1').val() == '') {
							$('#weekday_choice').fadeIn();
						}
					}
			   </script>
                <div id="weekday_div" style="display:none">
                	<div id="weekday_choice">
                        <div id="rowspace"></div>
                        <div id="label_input">
                            <div class="label_title"><label for="event_weekday"><?php echo $lang["NEW_EVENT_WEEKDAYS_LABEL"]; ?></label></div>
                        </div>
                        <div id="input_box">
                            <input type="checkbox" name="selectAll" id="event_weekday" value="0" onclick="javascript:selectCheckbox('addevent','event_weekday[]');updateCustomTimes();" checked="checked">&nbsp;<?php echo $lang["NEW_EVENT_WEEKDAYS_EVERYDAY"]; ?><br>
                            <input type="checkbox" name="event_weekday[]" id="event_weekday" value="1" onclick="javascript:disableSelectAll('addevent',this.checked);updateCustomTimes();" checked="checked">&nbsp;<?php echo $lang["NEW_EVENT_WEEKDAYS_MON"]; ?><br>
                            <input type="checkbox" name="event_weekday[]" id="event_weekday" value="2" onclick="javascript:disableSelectAll('addevent',this.checked);updateCustomTimes();" checked="checked">&nbsp;<?php echo $lang["NEW_EVENT_WEEKDAYS_TUE"]; ?><br>
                            <input type="checkbox" name="event_weekday[]" id="event_weekday" value="3" onclick="javascript:disableSelectAll('addevent',this.checked);updateCustomTimes();" checked="checked">&nbsp;<?php echo $lang["NEW_EVENT_WEEKDAYS_WED"]; ?><br>
                            <input type="checkbox" name="event_weekday[]" id="event_weekday" value="4" onclick="javascript:disableSelectAll('addevent',this.checked);updateCustomTimes();" checked="checked">&nbsp;<?php echo $lang["NEW_EVENT_WEEKDAYS_THU"]; ?><br>
                            <input type="checkbox" name="event_weekday[]" id="event_weekday" value="5" onclick="javascript:disableSelectAll('addevent',this.checked);updateCustomTimes();" checked="checked">&nbsp;<?php echo $lang["NEW_EVENT_WEEKDAYS_FRI"]; ?><br>
                            <input type="checkbox" name="event_weekday[]" id="event_weekday" value="6" onclick="javascript:disableSelectAll('addevent',this.checked);updateCustomTimes();" checked="checked">&nbsp;<?php echo $lang["NEW_EVENT_WEEKDAYS_SAT"]; ?><br>
                            <input type="checkbox" name="event_weekday[]" id="event_weekday" value="7" onclick="javascript:disableSelectAll('addevent',this.checked);updateCustomTimes();" checked="checked">&nbsp;<?php echo $lang["NEW_EVENT_WEEKDAYS_SUN"]; ?>
                        </div>
                        <div id="label_input">
                            <div class="label_title"><label for="event_custom_dates"><?php echo $lang["OR"]; ?></label></div>
                        </div>
                    </div>
                    <div id="rowspace"></div>
                    <div id="label_input">
                        <div class="label_title"><label for="event_custom_dates"><?php echo $lang["NEW_EVENT_CUSTOM_DATES_LABEL"]; ?></label></div>
                    </div>
                    <div id="input_box">
                        <div id="custom_dates">
                        	<div class="input_container"><input type="text" id="custom_date_1" /></div>
                            <input type="hidden" name="event_custom_dates[]" id="event_custom_date_1" />
                            <div class="input_container"><input type="button" id="erase_row" value="<?php echo $lang["ERASE"]; ?>" onclick="javascript:removeCustomDate(1);"></div>
                        </div>
                        <div id="cleardiv"></div>
                        <div class="input_container" style="margin-top: 10px;"><input type="button" name="plusbutton" id="plusbutton_date" class="plusbutton" style="display:none" onclick="javascript:addCustomDate(2);" value=""/></div>
                        <div id="cleardiv"></div>
                    </div>
                </div>
                
                <div id="rowspace"></div>
				<div id="rowline"></div>
				<div id="rowspace"></div>
                
                <!-- event time -->
				<div id="label_input">
                    <div class="label_title"><label for="event_starttime"><?php echo $lang["NEW_EVENT_TIME_LABEL"]; ?></label></div>
                </div>
                <div id="input_box">
                	<!-- from -->
                    <div class="input_container"><?php echo $lang["FROM"]; ?>:&nbsp;<input type="text" class="short_input_box" name="event_starttime" id="event_starttime"  readonly="readonly"  value="<?php echo $eventObj->getEventStarttime(); ?>" ></div>
                    <!-- to -->
                    <div class="input_container"><?php echo $lang["TO"]; ?>:&nbsp;<input type="text" class="short_input_box" name="event_endtime" id="event_endtime"  readonly="readonly" value="<?php if($eventObj->getEventEndtimeFlag() == 0) { echo $eventObj->getEventEndtime();} ?>" ></div>
                    <!-- cancel -->
                    <div class="input_container"><a class="trash_button" href="javascript:clearTimeTo();"></a></div>
                    <div id="cleardiv"></div>
                    
                    <!-- custom time -->
                    <div class="custom_time_container">
                    	<div class="custom_check"><?php echo $lang["NEW_EVENT_CUSTOM_TIMES_LABEL"]; ?>&nbsp;<input type="checkbox" id="events_days" name="events_days" value="1" onclick="javascript:showCustomTimes(this);" /></div>
                    
                    	<?php
						
						if($eventObj->getEventId()>0 && count($eventObj->getEventsCustomTimes())>0) {
							?>
                            <div id="custom_times" style="display:none">
							<?php
							$i = 0;
							$arrayTimes = $eventObj->getEventsCustomTimes();
							foreach($arrayTimes as $dayId => $day) {
								?>
                                <div id="custom_time_<?php echo ($i+1);?>" style="margin-top: 10px;" class="custom_times_div">
                                    <?php echo $lang["DAY"]; ?>:&nbsp;
                                    <select name="day_date[]"  onchange="javascript:showSub(this.options[this.selectedIndex].value,<?php echo ($i+1);?>);">
                                        <option value="0"><?php echo $lang["CHOOSE_DAY"]; ?></option>
                                        <option value="<?php echo $day["day_date"];?>" selected><?php if($settingObj->getDateFormat() == "UK") {echo strftime('%d/%m/%Y',strtotime($day["day_date"])); } else if($settingObj->getDateFormat() == "EU") { echo strftime('%Y/%m/%d',strtotime($day["day_date"]));} else {echo strftime('%m/%d/%Y',strtotime($day["day_date"]));}?></option>
                                    </select>
                                    <div id="sub_custom_time_<?php echo ($i+1);?>" style="margin-top: 10px;">
                                       
                                        <div class="input_container"><?php echo $lang["TIME_FROM"]; ?>:&nbsp;<input type="text" name="day_time_from[]" id="day_time_from_<?php echo ($i+1);?>" value="<?php echo $day["day_time_from"]?>" size ="10" readonly="readonly" /></div>
                                        <div class="input_container"><?php echo $lang["TO"]; ?>:&nbsp;<input type="text" name="day_time_to[]" value="<?php if($day["day_time_to_flag"]==0) { echo $day["day_time_to"]; }?>" size ="10" id="day_time_to_<?php echo ($i+1);?>" readonly="readonly" /></div>
                                        <div class="input_container"><a class="trash_button" style="margin-top:3px;" href="javascript:clearDayTimeTo('day_time_to_<?php echo ($i+1);?>');"></a></div>
                                        
                                        <div class="input_container"><input type="button" id="erase_row" value="<?php echo $lang["ERASE"]; ?>" onclick="javascript:removeCustomTime(<?php echo ($i+1);?>);"></div>
                                        
                                    </div>
                                </div>
                                <div id="cleardiv"></div>
								<?php
								$i++;
							}
							?>
                            </div>
                            <div id="cleardiv"></div>
                            <div class="input_container" style="margin-top: 10px;"><input type="button" name="plusbutton" id="plusbutton" class="plusbutton" onclick="javascript:addCustomTime(<?php echo count($arrayTimes);?>)" value="" style="display:none"/></div>
                            <div id="cleardiv"></div>
                            
                            <?php
						} else {
							?>
                            <div id="custom_times" style="display:none; margin-top: 10px;">
                                <div id="custom_time_1" class="custom_times_div">
                                    <?php echo $lang["DAY"]; ?>:&nbsp;
                                    <select name="day_date[]" onchange="javascript:showSub(this.options[this.selectedIndex].value,1);">
                                        <!--<option value="0">Choose day</option>-->
                                        
                                    </select>
                                    <div id="sub_custom_time_1" style="display:none; margin-top: 10px;">
                                        <div class="input_container"><?php echo $lang["TIME_FROM"]; ?>:&nbsp;<input type="text" name="day_time_from[]" id="day_time_from_1" value="" size ="10" readonly="readonly" /></div>
                                        <div class="input_container"><?php echo $lang["TO"]; ?>:&nbsp;<input type="text" name="day_time_to[]" id="day_time_to_1" value="" size ="10" readonly="readonly" /></div>
                                        <div class="input_container"><a class="trash_button" style="margin-top:3px;" href="javascript:clearDayTimeTo('day_time_to_1');"></a></div>
                                    </div>
                                </div>
                            </div>
                            <div id="cleardiv"></div>
                            <div class="input_container" style="margin-top: 10px;"><input type="button" onclick="javascript:addCustomTime(1);" value="" name="plusbutton" id="plusbutton" class="plusbutton" style="display:none" /></div>
                            <div id="cleardiv"></div>
                            <?php
						}
						?>
                        </div>
                        
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                
                
                
                
                <!-- event venue -->
				<div id="label_input">
					<div class="label_title"><label for="event_venue"><?php echo $lang["NEW_EVENT_VENUE_LABEL"]; ?></label></div>
				</div>
				<div id="input_box"><textarea  name="event_venue" id="event_venue"><?php echo $eventObj->getEventVenue(); ?></textarea></div>
				<div id="rowspace"></div>
				<div id="rowline"></div>
				<div id="rowspace"></div>
				
                
                
                
                <!-- event admission -->
				<div id="label_input">
					<div class="label_title"><label for="event_entrance"><?php echo $lang["NEW_EVENT_ENTRANCE_RULES_LABEL"]; ?></label></div>
				</div>
				<div id="input_box">
                	<?php echo $lang["FREE"]; ?>&nbsp;<input type="radio" name="event_free" value="1" <?php if($eventObj->getEventFree() == 1) { echo "checked"; }?> />&nbsp;<?php echo $lang["WITH_FEE"]; ?>&nbsp;<input type="radio" name="event_free" value="-1" <?php if($eventObj->getEventFree() == -1) { echo "checked"; }?> />
					<textarea name="event_entrance" id="event_entrance" style="background-color:#FFF; height: 150px;" ><?php echo $eventObj->getEventEntrance(); ?></textarea>
					
                </div>
				<div id="rowspace"></div>
				<div id="rowline"></div>
				<div id="rowspace"></div>
                
                
                
                
                <!-- event tickets available -->
                <div id="label_input">
					<div class="label_title"><label for="event_seats"><?php echo $lang["NEW_EVENT_SEATS_LABEL"]; ?></label></div>
				</div>
				<div id="input_box">
					<input type="text" class="short_input_box" name="event_seats" id="event_seats" tmt:pattern="integer" value="<?php if($eventObj->getEventId() > 0) { echo $eventObj->getEventSeats(); } else { echo -1; } ?>" >
				</div>
				<div id="rowspace"></div>
				<div id="rowline"></div>
				<div id="rowspace"></div>
				
                
                <!-- event cover image -->
				<div id="label_input">
					<div class="label_title"><label for="event_image"><?php echo $lang["NEW_EVENT_COVER_IMAGE_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["NEW_EVENT_COVER_IMAGE_SUBLABEL"]; ?></div>
				</div>
				<div id="input_box">
					<input type="file"  name="event_image" value="">
				   <?php 
				   if($eventObj->getEventId()>0 && $eventObj->getEventImage()!='') {  
				   ?>
                       <div id="preview_image">
                           <img src="../contents/events/<?php echo $eventObj->getEventId();?>/<?php echo $eventObj->getEventImage();?>" border=0 />
                           <input type="hidden" name="old_event_image" value="<?php echo $eventObj->getEventImage();?>">
                       </div>
                   <?php 
				   } else {
				   ?>
                   		<input type="hidden" name="old_event_image" value="">
                   <?php
				   } 
				   ?>
                   <br />
                   <div class="label_title" style="margin-top: 15px;"><?php echo $lang["NEW_EVENT_SHOW_COVER_IMAGE_LABEL"]; ?></div>
                   <div class="label_subtitle"><?php echo $lang["NEW_EVENT_SHOW_COVER_IMAGE_SUBLABEL"]; ?></div> <input type="checkbox" name="event_cover_visible" value="1"  <?php if($eventObj->getEventId()>0 && $eventObj->getEventCoverVisible()==0) { } else { echo "checked";}  ?>/>
				</div>
				<div id="rowspace"></div>
				<div id="rowline"></div>
				<div id="rowspace"></div>
                
                <!-- event description -->
			    <div id="label_input">
					<div class="label_title"><label for="event_description"><?php echo $lang["NEW_EVENT_DESCRIPTION_LABEL"]; ?></label></div>
				</div>
				<div id="input_box">
					<textarea name="event_description" id="event_description" style="background-color:#FFF; height: 200px;"><?php echo $eventObj->getEventDescription(); ?></textarea>
				</div>
				<div id="rowspace"></div>
				<div id="rowline"></div>
				<div id="rowspace"></div>
                
                 
                <!-- event images -->
                <div id="label_input">
					<div class="label_title"><label for="event_image"><?php echo $lang["NEW_EVENT_IMAGES_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["NEW_EVENT_IMAGES_SUBLABEL"]; ?></div>
				</div>
				<div id="input_box">
    			<?php 
					if($eventObj->getEventId()>0 && count($eventObj->getEventImages()) > 0) {
						$stringTodel = "0";
						 ?>
						<div class="qq-uploader">
							   
								<ul class="qq-upload-list">
								<?php
								$arrImg = $eventObj->getEventImages();
								
								
								for($i = 1;$i<count($arrImg);$i++) {
									$stringTodel .= "|".$arrImg[$i];
									?>
									<li class=" qq-upload-success qq-upload-del" id="<?php echo $arrImg[$i];?>"><span class="qq-upload-file"><a href="../contents/events/<?php echo $event_id;?>/<?php echo $arrImg[$i];?>" class="lightbox"><?php echo $lang["IMAGE"]; ?> <?php echo $i;?></a></span><span class="qq-upload-delete"><input type="hidden" value="<?php echo $arrImg[$i];?>" id="photos_<?php echo $arrImg[$i];?>" name="photos[]"><a id="link2file" class="qq-upload-del" href="javascript:DelImage('photos_<?php echo $arrImg[$i];?>','<?php echo $arrImg[$i];?>');"><?php echo $lang["DELETE"]; ?></a></span><div id="cleardiv"></div>
								</li>
								<?php
							   
								}
								?>
							  
							  
								</ul>
							</div>
					<div id="file-uploader">       
							<noscript>          
								<p>Enable Javascript to use fileuploader</p>
								<!-- or put a simple form for upload here -->
							</noscript>  
								  
						</div>
						  
						<div id="delete_all" style="display:none"><a href="javascript:removeAllPhotos('<?php echo $stringTodel;?>');"><?php echo $lang["DELETE_ALL"]; ?></a></div> 
						<input type="hidden" name="list_img_to_del" value="0" />
					<?php } else { ?>
						<div id="file-uploader">       
							<noscript>          
								<p>Enable Javascript to use fileuploader</p>
								<!-- or put a simple form for upload here -->
							</noscript>         
						</div>
					   
						<div id="delete_all" style="display:none"><a href="javascript:removeAllPhotos('');"><?php echo $lang["DELETE_ALL"]; ?></a></div>
					<?php } ?>
						<script>
							
							$(document).ready(function() {
									uploader = new qq.FileUploader({
									
									element: document.getElementById('file-uploader'),
									action: 'upload_event_image.php',
									debug: true,
									allowedExtensions: ["jpg","jpeg","gif","png"]
								});
								<?php if($eventObj->getEventId()>0) { ?>
									uploader._uploadedFiles = document.getElementsByName("photos[]").length;
								
									if(uploader._uploadedFiles>0) {
										
										$('#delete_all').css({"display": "block"});
									}
									
									<?php } ?>
									
									
								
							});
						</script>
						
					
			</div>
			<div id="rowspace"></div>
			<div id="rowline"></div>
			<div id="rowspace"></div>
            
            <!-- event video -->
            <div id="label_input">
                <div class="label_title"><label for="event_video"><?php echo $lang["NEW_EVENT_VIDEO_LABEL"]; ?></label></div>
            </div>
            <div id="input_box">
                <textarea name="event_video" id="event_video" style="background-color:#FFF"><?php echo $eventObj->getEventVideo(); ?></textarea>
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            
            <!-- event video text -->
            <div id="label_input">
                <div class="label_title"><label for="event_video_text"><?php echo $lang["NEW_EVENT_VIDEO_TEXT_LABEL"]; ?></label></div>
            </div>
            <div id="input_box">
                <textarea name="event_video_text" id="event_video_text" style="background-color:#FFF"><?php echo $eventObj->getEventVideoText(); ?></textarea>
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
                
            <!-- event map -->
            <div id="label_input">
                <div class="label_title"><label for="event_map"><?php echo $lang["NEW_EVENT_MAP_LABEL"]; ?></label></div>
            </div>
            <div id="input_box">
                <textarea name="event_map" id="event_map" style="background-color:#FFF"><?php echo $eventObj->getEventMap(); ?></textarea>
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            
            <!-- event map text -->
            <div id="label_input">
                <div class="label_title"><label for="event_map_text"><?php echo $lang["NEW_EVENT_MAP_TEXT_LABEL"]; ?></label></div>
            </div>
            <div id="input_box">
                <textarea name="event_map_text" id="event_map_text" style="background-color:#FFF"><?php echo $eventObj->getEventMapText(); ?></textarea>
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
                
            
            <!-- buy tickets link -->    
            <div id="label_input">
                <div class="label_title"><label for="event_link"><?php echo $lang["NEW_EVENT_LINK_LABEL"]; ?></label></div>
            </div>
            <div id="input_box">
                <input type="text" class="long_input_box" name="event_link" id="event_link" value="<?php if($eventObj->getEventId() > 0) { echo $eventObj->getEventLink(); } ?>" >
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            
            
            
            
            <!-- website link -->
            <div id="label_input">
                <div class="label_title"><label for="event_site"><?php echo $lang["NEW_EVENT_SITE_LABEL"]; ?></label></div>
            </div>
            <div id="input_box">
                <input type="text" class="long_input_box" name="event_site" id="event_site" value="<?php if($eventObj->getEventId() > 0) { echo $eventObj->getEventSite(); } ?>" >
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            
            <!-- twitter hashtag -->    
            <div id="label_input">
                <div class="label_title"><label for="event_hashtag"><?php echo $lang["NEW_EVENT_HASHTAG_LABEL"]; ?></label></div>
                <div class="label_subtitle"><?php echo $lang["NEW_EVENT_HASHTAG_SUBLABEL"]; ?></div>
            </div>
            <div id="input_box">
                <input type="text" class="long_input_box" name="event_hashtag" id="event_hashtag" value="<?php if($eventObj->getEventId() > 0) { echo $eventObj->getEventHashtag(); } ?>" >
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            
            <!-- flickr search -->    
            <div id="label_input">
                <div class="label_title"><label for="event_flickr_search"><?php echo $lang["NEW_EVENT_FLICKR_SEARCH_LABEL"]; ?> </label></div>
            </div>
            <div id="input_box">
                <input type="text" class="long_input_box" name="event_flickr_search" id="event_flickr_search" value="<?php if($eventObj->getEventId() > 0) { echo $eventObj->getEventFlickrSearch(); } ?>" >
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            
            
            <!-- 
            =======================
            === Page Title ==
            =======================
            -->
            <div id="label_input">
                <div class="label_title"><label for="event_page_title"><?php echo $lang["NEW_EVENT_PAGE_TITLE_LABEL"]; ?></label></div>
                <div class="label_subtitle"></div>
            </div>
            <div id="input_box">
                <input type="text" class="long_input_box" id="event_page_title" name="event_page_title" value="<?php echo $eventObj->getEventPageTitle(); ?>">
               
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            <!-- 
            =======================
            === Title ==
            =======================
            -->
            <div id="label_input">
                <div class="label_title"><label for="event_metatag_title"><?php echo $lang["NEW_EVENT_METATAG_TITLE_LABEL"]; ?></label></div>
                <div class="label_subtitle"></div>
            </div>
            <div id="input_box">
                <input type="text" class="long_input_box" id="event_metatag_title" name="event_metatag_title" value="<?php echo $eventObj->getEventMetatagTitle(); ?>">
               
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            
            <!-- 
            =======================
            === Description ==
            =======================
            -->
            <div id="label_input">
                <div class="label_title"><label for="event_metatag_description"><?php echo $lang["NEW_EVENT_METATAG_DESCRIPTION_LABEL"]; ?></label></div>
                <div class="label_subtitle"></div>
            </div>
            <div id="input_box">
                <input type="text" class="long_input_box" id="event_metatag_description" name="event_metatag_description" value="<?php echo $eventObj->getEventMetatagDescription(); ?>">
               
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            
            <!-- 
            =======================
            === Keywords ==
            =======================
            -->
            <div id="label_input">
                <div class="label_title"><label for="event_metatag_keywords"><?php echo $lang["NEW_EVENT_METATAG_KEYWORDS_LABEL"]; ?></label></div>
                <div class="label_subtitle"></div>
            </div>
            <div id="input_box">
                <input type="text" class="long_input_box" id="event_metatag_keywords" name="event_metatag_keywords" value="<?php echo $eventObj->getEventMetatagKeywords(); ?>">
               
            </div>
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            
           
            
            <!-- bridge buttons -->
            <div class="bridge_buttons_container">
                <!-- cancel -->
                <div class="admin_button cancel_button" ><a href="javascript:document.location.href='events.php?calendar_id=<?php echo $_GET["calendar_id"]; ?>';"></a></div>
                
                <!-- save -->
                <!---->
                <div class="admin_button"><input type="submit" id="apply_button" name="saveunpublish" value="" style="float:right;margin-right:10px;background-color:#333;" onclick="javascript:showLoader();"></div>
                
                <!-- save -->
                <!--<div class="admin_button"><input type="submit" id="publish_button" name="savepublish" value="" onclick="javascript:setActive(<?php echo $event_id; ?>,1);"></div>-->
                <input type="hidden" name="event_active" id="event_active" value="<?php if($eventObj->getEventId()>0) { echo $eventObj->getEventActive(); } else { echo 1; }?>" />
            </div>
            <div id="rowspace"></div>
            </form>
            
         </div>
            
        
        </div>
    </div>
</div>
<div id="modal_loading" class="modal_loading" style="display:none">
	<img src="images/loading.png" border=0 />
</div>
<?php 
include 'include/footer.php';
?>
