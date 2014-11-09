// JavaScript Document

var currentMousePos = { x: -1, y: -1 };
$(document).mousemove(function(event) {
	currentMousePos.x = event.pageX;
	currentMousePos.y = event.pageY;
});

var xhr = new Array();
function getMonthCalendar(month, year, calendar_id) {
	for(var i = 0; i < xhr.length; i++) {
    	xhr[i].abort();
	}
	$('body').prepend('<div id="sfondo" class="modal_sfondo"></div>');
	$('#modal_loading').fadeIn();
	xhr.push($.ajax({
	  url: 'mobile/ajax/getMonthCalendar.php?month='+month+"&year="+year+"&calendar_id="+calendar_id,
	  success: function(data) {
		$('#modal_loading').fadeOut();
	    $('#sfondo').remove();
		
		$('#calendar_container').html(data);
		
		$('.bg_white a').each(function() {
			if($(this).attr("over")==1) {
				
				$(this).bind('click', function() {
					
					getEventsList($(this).attr("year"),$(this).attr("month"),$(this).attr("day"),calendar_id,1);
				});
			}
		});
		$('.bg_black a').each(function() {
			if($(this).attr("over")==1) {
				
				$(this).bind('click', function() {
					
					getEventsList($(this).attr("year"),$(this).attr("month"),$(this).attr("day"),calendar_id,1);
				});
			}
			
		});
		
	  }
	})
	);
	getMonthName(month);
	getYearName(year);
	$('#view_list').css({"background-color":"#333333"});
	$('#view_calendar').css({"background-color":"#567BD2"});
	$('#view_calendar').hover(function() {
		$('#view_calendar').css({"background-color":"#567BD2"});
	},
	function() {
		$('#view_calendar').css({"background-color":"#567BD2"});
	});
	
	$('#view_list').hover(function() {
		$('#view_list').css({"background-color":"#567BD2"});
	},
	function() {
		$('#view_list').css({"background-color":"#333333"});
	});
	
}

function getCalendarView(month,year,calendar_id) {
	if(navigator.appName == 'Microsoft Internet Explorer') {
		var target = $('#events_home_container');
			  var h = target.height();
				var cssHeight=target.css('height');
				target.animate( 
				{ height: '1px' }, 100, function() { 
				  target.hide();
				  target.height(h);
				  target.css('height',cssHeight);
				  
				});
		
		$('#calendar_container').fadeIn();
	} else {
		$('#calendar_container').slideDown();
		$('#events_home_container').slideUp();
	}
	
	$('#month_nav').fadeIn();
	$('#calendar_select').fadeIn();
	$('#calendar_select_label').fadeIn();
	$('#name_days_container').fadeIn();
	$('#search_event_field').fadeIn();
	$('#search_event_label').fadeIn();
	var today= new Date();
	if(month>-1 && year>0) {
		getMonthCalendar(month,year,calendar_id);
	} else {
		getMonthCalendar((today.getMonth()+1),today.getFullYear(),calendar_id);
	}
	$('#calendar_id').val(calendar_id);
	
}

function getYearName(year) {
	$('#month_year').html(year);
	currentYear = year;
}
function getPreviousMonth(calendar_id) {
	 if(currentMonth == 1) {
		 newYear = currentYear-1;
		 newMonth = 12;
	 } else {
		 newYear = currentYear;
		 newMonth = currentMonth-1;
	 }
	 getMonthCalendar(newMonth,newYear,calendar_id);
	 $('#calendar_id').val(calendar_id);
}
function getNextMonth( calendar_id) {
	 if(currentMonth == 12) {
		 newYear = currentYear+1;
		 newMonth = 1;
	 } else {
		 newYear = currentYear;
		 newMonth = currentMonth+1;
	 }
	 getMonthCalendar(newMonth,newYear,calendar_id);
	 $('#calendar_id').val(calendar_id);
}


function closeEvents(year,month,day,calendar_id) {
	if(navigator.appName == 'Microsoft Internet Explorer') {
		var target = $('#events_container');
			  var h = target.height();
				var cssHeight=target.css('height');
				target.animate( 
				{ height: '1px' }, 100, function() { 
				  target.hide();
				  target.height(h);
				  target.css('height',cssHeight);
				  
				});
		var target2 = $('#event_container');
			  var h2 = target2.height();
				var cssHeight2=target2.css('height');
				target2.animate( 
				{ height: '1px' }, 100, function() { 
				  target2.hide();
				  target2.height(h2);
				  target2.css('height',cssHeight2);
				  
				});
				
		var target3 = $('#events_home_container');
			  var h3 = target3.height();
				var cssHeight3=target3.css('height');
				target3.animate( 
				{ height: '1px' }, 100, function() { 
				  target3.hide();
				  target3.height(h3);
				  target3.css('height',cssHeight3);
				  
				});
		$('#calendar_container').fadeIn();
	} else {
		$('#calendar_container').slideDown();
		$('#events_container').slideUp();
		$('#event_container').slideUp();
		$('#events_home_container').slideUp();
	}
	
	$('#month_nav').fadeIn();
	$('#calendar_select').fadeIn();
	$('#calendar_select_label').fadeIn();
	$('#name_days_container').fadeIn();
	$('#search_event_field').fadeIn();
	$('#search_event_label').fadeIn();
	$('#view_buttons').fadeIn();
	//$('#back_event_button').fadeOut();
   
	var today= new Date();
	if(month>-1 && year>0) {
		getMonthCalendar(month,year,calendar_id);
	} else {
		getMonthCalendar((today.getMonth()+1),today.getFullYear(),calendar_id);
	}
	$('#calendar_id').val(calendar_id);
	
}


function getEventsList(year,month,day,calendar_id,pag) {
	for(var i = 0; i < xhr.length; i++) {
    	xhr[i].abort();
	}
	$('body').prepend('<div id="sfondo" class="modal_sfondo"></div>');
	$('#modal_loading').fadeIn();
	xhr.push($.ajax({
	  url: 'mobile/ajax/getEventsList.php?year='+year+"&month="+month+"&day="+day+"&calendar_id="+calendar_id+"&pag="+pag,
	  success: function(data) {
		  $('#modal_loading').fadeOut();
		  $('#sfondo').remove();		  
		  if(navigator.appName == 'Microsoft Internet Explorer') {
			  var target = $('#calendar_container');
			  var h = target.height();
				var cssHeight=target.css('height');
				target.animate( 
				{ height: '1px' }, 100, function() { 
				  target.hide();
				  target.height(h);
				  target.css('height',cssHeight);
				} 
				);
				var target2 = $('#event_container');
			  var h2 = target2.height();
				var cssHeight2=target2.css('height');
				target2.animate( 
				{ height: '1px' }, 100, function() { 
				  target2.hide();
				  target2.height(h2);
				  target2.css('height',cssHeight2);
				  
				});
				
				var target3 = $('#events_home_container');
			  var h3 = target3.height();
				var cssHeight3=target3.css('height');
				target3.animate( 
				{ height: '1px' }, 100, function() { 
				  target3.hide();
				  target3.height(h3);
				  target3.css('height',cssHeight3);
				  
				});
			
		      $('#events_container').fadeIn();
		  } else {
			  $('#calendar_container').slideUp();
			  $('#event_container').slideUp();
			   $('#events_home_container').slideUp();
			  $('#events_container').slideDown();
		  }
		  dataArr=data.split("|");
		 
		  $('#events_container').html(dataArr[0]);
		  
		  $('#month_nav').fadeOut();
		  $('#calendar_select').fadeOut();
		  $('#calendar_select_label').fadeOut();
		  $('#name_days_container').fadeOut();
		  $('#search_event_field').fadeOut();
		  $('#search_event_label').fadeOut();
		  $('#view_buttons').fadeOut();
			
			
	  }
	}));
}

function searchEventsList(text,calendar_id,pag) {
	for(var i = 0; i < xhr.length; i++) {
    	xhr[i].abort();
		if($('#modal_loading').css("display") == "block") {
			$('#modal_loading').fadeOut();
		}
	}
	$('body').prepend('<div id="sfondo" class="modal_sfondo"></div>');
	$('#modal_loading').fadeIn();
	xhr.push($.ajax({
	  url: 'mobile/ajax/searchEvents.php?text='+text+"&calendar_id="+calendar_id+"&pag="+pag,
	  success: function(data) {
		  $('#modal_loading').fadeOut();
		  $('#sfondo').remove();		  
		  if(navigator.appName == 'Microsoft Internet Explorer') {
			  var target = $('#calendar_container');
			  var h = target.height();
				var cssHeight=target.css('height');
				target.animate( 
				{ height: '1px' }, 100, function() { 
				  target.hide();
				  target.height(h);
				  target.css('height',cssHeight);
				} 
				);
				var target2 = $('#event_container');
			  var h2 = target2.height();
				var cssHeight2=target2.css('height');
				target2.animate( 
				{ height: '1px' }, 100, function() { 
				  target2.hide();
				  target2.height(h2);
				  target2.css('height',cssHeight2);
				  
				});
				
				var target3 = $('#events_home_container');
			  var h3 = target3.height();
				var cssHeight3=target3.css('height');
				target3.animate( 
				{ height: '1px' }, 100, function() { 
				  target3.hide();
				  target3.height(h3);
				  target3.css('height',cssHeight3);
				  
				});
			
		      $('#events_container').fadeIn();
		  } else {
			  $('#calendar_container').slideUp();
			  $('#event_container').slideUp();
			  $('#events_home_container').slideUp();
			  $('#events_container').slideDown();
		  }
		  dataArr=data.split("|");
		 
		  $('#events_container').html(dataArr[0]);
		  
		  $('#month_nav').fadeOut();
		  $('#calendar_select').fadeOut();
		  $('#calendar_select_label').fadeOut();
		  $('#search_event_field').fadeOut();
		  $('#search_event_label').fadeOut();
		  $('#name_days_container').fadeOut();
		  $('#view_buttons').fadeOut();
			
			
	  }
	}));
}
function openEvent(year,month,day,calendar_id,event_id,pag) {
	for(var i = 0; i < xhr.length; i++) {
    	xhr[i].abort();
	}
	$('body').prepend('<div id="sfondo" class="modal_sfondo"></div>');
	$('#modal_loading').fadeIn();
	xhr.push($.ajax({
	  url: 'mobile/ajax/getEvent.php?year='+year+"&month="+month+"&day="+day+"&calendar_id="+calendar_id+"&event_id="+event_id+"&pag="+pag,
	  success: function(data) {
		  $('#modal_loading').fadeOut();
		  $('#sfondo').remove();	
		  if(navigator.appName == 'Microsoft Internet Explorer') {
			  var target = $('#events_container');
			  var h = target.height();
				var cssHeight=target.css('height');
				target.animate( 
				{ height: '1px' }, 100, function() { 
				  target.hide();
				  target.height(h);
				  target.css('height',cssHeight);
				} 
				);
				
				var target2 = $('#events_home_container');
			  var h2 = target2.height();
				var cssHeight2=target2.css('height');
				target2.animate( 
				{ height: '1px' }, 100, function() { 
				  target2.hide();
				  target2.height(h2);
				  target2.css('height',cssHeight2);
				} 
				);
				
			
		      $('#event_container').fadeIn();
		  } else {
			  $('#events_container').slideUp();
			  $('#event_container').slideDown();
		  }
		  dataArr=data.split("|");
		 
		  $('#event_container').html(dataArr[0]);
		  
		  $('#month_nav').fadeOut();
		  $('#calendar_select').fadeOut();
		  $('#calendar_select_label').fadeOut();
		  $('#search_event_field').fadeOut();
		  $('#search_event_label').fadeOut();
		  $('#name_days_container').fadeOut();
		  $('#view_buttons').fadeOut();
		   //$('#back_event_button').fadeIn();
		  
	  }
	}));
}

function openSearchEvent(year,month,day,calendar_id,event_id,text,pag) {
	for(var i = 0; i < xhr.length; i++) {
    	xhr[i].abort();
	}
	$('body').prepend('<div id="sfondo" class="modal_sfondo"></div>');
	$('#modal_loading').fadeIn();
	xhr.push($.ajax({
	  url: 'mobile/ajax/getSearchEvent.php?year='+year+"&month="+month+"&day="+day+"&calendar_id="+calendar_id+"&event_id="+event_id+"&text="+text+"&pag="+pag,
	  success: function(data) {
		  $('#modal_loading').fadeOut();
		  $('#sfondo').remove();	
		  if(navigator.appName == 'Microsoft Internet Explorer') {
			  var target = $('#events_container');
			  var h = target.height();
				var cssHeight=target.css('height');
				target.animate( 
				{ height: '1px' }, 100, function() { 
				  target.hide();
				  target.height(h);
				  target.css('height',cssHeight);
				} 
				);
				
				var target3 = $('#events_home_container');
			  var h3 = target3.height();
				var cssHeight3=target3.css('height');
				target3.animate( 
				{ height: '1px' }, 100, function() { 
				  target3.hide();
				  target3.height(h3);
				  target3.css('height',cssHeight3);
				  
				});
				
			
		      $('#event_container').fadeIn();
		  } else {
			  $('#events_container').slideUp();
			  $('#events_home_container').slideUp();
			  $('#event_container').slideDown();
		  }
		  dataArr=data.split("|");
		 
		  $('#event_container').html(dataArr[0]);
		  
		  $('#month_nav').fadeOut();
		  $('#calendar_select').fadeOut();
		  $('#calendar_select_label').fadeOut();
		  $('#search_event_field').fadeOut();
		  $('#search_event_label').fadeOut();
		  $('#name_days_container').fadeOut();
		  $('#view_buttons').fadeOut();
		  
		  
			
	  }
	}));
}

function getEventsHomeList(calendar_id,pag,filter) {
	for(var i = 0; i < xhr.length; i++) {
    	xhr[i].abort();
	}
	$('body').prepend('<div id="sfondo" class="modal_sfondo"></div>');
	$('#modal_loading').fadeIn();
	xhr.push($.ajax({
	  url: 'mobile/ajax/getEventsHomeList.php?year='+currentYear+"&month="+currentMonth+"&calendar_id="+calendar_id+"&pag="+pag+"&filter="+filter,
	  success: function(data) {
		  $('#modal_loading').fadeOut();
		  $('#sfondo').remove();		  
		  if(navigator.appName == 'Microsoft Internet Explorer') {
			  var target = $('#calendar_container');
			  var h = target.height();
				var cssHeight=target.css('height');
				target.animate( 
				{ height: '1px' }, 100, function() { 
				  target.hide();
				  target.height(h);
				  target.css('height',cssHeight);
				} 
				);
				var target2 = $('#event_container');
			  var h2 = target2.height();
				var cssHeight2=target2.css('height');
				target2.animate( 
				{ height: '1px' }, 100, function() { 
				  target2.hide();
				  target2.height(h2);
				  target2.css('height',cssHeight2);
				  
				});
				
				var target3 = $('#events_container');
			  var h3 = target3.height();
				var cssHeight3=target3.css('height');
				target3.animate( 
				{ height: '1px' }, 100, function() { 
				  target3.hide();
				  target3.height(h3);
				  target3.css('height',cssHeight3);
				  
				});
			
		      $('#events_home_container').fadeIn();
		  } else {
			  $('#calendar_container').slideUp();
			  $('#event_container').slideUp();
			  $('#events_container').slideUp();
			  $('#events_home_container').slideDown();
		  }
		  dataArr=data.split("|");
		 
		  $('#events_home_container').html(dataArr[0]);
		  
		  $('#month_nav').fadeOut();
		  $('#calendar_select').fadeOut();
		  $('#calendar_select_label').fadeOut();
		  $('#name_days_container').fadeOut();
		  $('#search_event_field').fadeOut();
		  $('#search_event_label').fadeOut();
		 
			
		$('#view_calendar').css({"background-color":"#333333"});
		$('#view_list').css({"background-color":"#567BD2"});	
		
		$('#view_calendar').hover(function() {
			$('#view_calendar').css({"background-color":"#567BD2"});
		},
		function() {
			$('#view_calendar').css({"background-color":"#333333"});
		});
		
		$('#view_list').hover(function() {
			$('#view_list').css({"background-color":"#567BD2"});
		},
		function() {
			$('#view_list').css({"background-color":"#567BD2"});
		});
	  }
	}));
	
}

function closeHomeEvents(year,month,calendar_id) {
	if(navigator.appName == 'Microsoft Internet Explorer') {
		var target2 = $('#event_container');
			  var h2 = target2.height();
				var cssHeight2=target2.css('height');
				target2.animate( 
				{ height: '1px' }, 100, function() { 
				  target2.hide();
				  target2.height(h2);
				  target2.css('height',cssHeight2);
				  
				});
		var target3 = $('#events_home_container');
			  var h3 = target3.height();
				var cssHeight3=target3.css('height');
				target3.animate( 
				{ height: '1px' }, 100, function() { 
				  target3.hide();
				  target3.height(h3);
				  target3.css('height',cssHeight3);
				  
				});
		$('#calendar_container').fadeIn();
	} else {
		$('#calendar_container').slideDown();
		$('#event_container').slideUp();
		$('#events_home_container').slideUp();
	}
	
	$('#month_nav').fadeIn();
	$('#calendar_select').fadeIn();
	$('#calendar_select_label').fadeIn();
	$('#name_days_container').fadeIn();
	$('#search_event_field').fadeIn();
	$('#search_event_label').fadeIn();
	$('#view_buttons').fadeIn();
	
	
	var today= new Date();
	if(month>-1 && year>0) {
		getMonthCalendar(month,year,calendar_id);
	} else {
		getMonthCalendar((today.getMonth()+1),today.getFullYear(),calendar_id);
	}
	$('#calendar_id').val(calendar_id);
	
}

function openHomeEvent(year,month,day,calendar_id,event_id,pag,filter) {
	for(var i = 0; i < xhr.length; i++) {
    	xhr[i].abort();
	}
	$('body').prepend('<div id="sfondo" class="modal_sfondo"></div>');
	$('#modal_loading').fadeIn();
	xhr.push($.ajax({
	  url: 'mobile/ajax/getEventHome.php?year='+year+"&month="+month+"&day="+day+"&calendar_id="+calendar_id+"&event_id="+event_id+"&filter="+filter+"&pag="+pag,
	  success: function(data) {
		  $('#modal_loading').fadeOut();
		  $('#sfondo').remove();	
		  if(navigator.appName == 'Microsoft Internet Explorer') {
			  var target = $('#events_container');
			  var h = target.height();
				var cssHeight=target.css('height');
				target.animate( 
				{ height: '1px' }, 100, function() { 
				  target.hide();
				  target.height(h);
				  target.css('height',cssHeight);
				} 
				);
				
				var target3 = $('#events_home_container');
			  var h3 = target3.height();
				var cssHeight3=target3.css('height');
				target3.animate( 
				{ height: '1px' }, 100, function() { 
				  target3.hide();
				  target3.height(h3);
				  target3.css('height',cssHeight3);
				  
				});
				
			
		      $('#event_container').fadeIn();
		  } else {
			  $('#events_container').slideUp();
			  $('#events_home_container').slideUp();
			  $('#event_container').slideDown();
		  }
		  dataArr=data.split("|");
		 
		  $('#event_container').html(dataArr[0]);
		  
		  $('#month_nav').fadeOut();
		  $('#calendar_select').fadeOut();
		  $('#calendar_select_label').fadeOut();
		  $('#search_event_field').fadeOut();
		  $('#search_event_label').fadeOut();
		  $('#name_days_container').fadeOut();
		  $('#view_buttons').fadeOut();
			
		
			
	  }
	}));
}