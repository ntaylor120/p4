<?php
include '../common.php';
$event_id=$_GET["event_id"];
$eventObj->setEvent($event_id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta content="<?php echo $settingObj->getMetatagTitle(); ?>" name="title">
<meta content="<?php echo $settingObj->getMetatagDescription(); ?>" name="description">
<meta content="<?php echo $settingObj->getMetatagKeywords(); ?>" name="keywords">
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" />

<script language="javascript" type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>
<link href="../css/photoswipe.css" type="text/css" rel="stylesheet" />
<link type="text/css" rel="stylesheet" href="../../css/style.css" />
<link type="text/css" rel="stylesheet" href="../css/style.css" />

<!--[if IE]> 
<link rel="stylesheet" type="text/css" href="explorer.css" media="all" /> 
<![endif]-->
<script language="javascript" type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>
<link type="text/css" rel="stylesheet" href="../../css/style.css" />
</head>
<body>
<script>
	$(function() {
		$('#flickr_loading').fadeIn();
		//ajax request to read pics
		$.ajax({
		  url: 'getFlickrPics.php?event_id=<?php echo $eventObj->getEventId(); ?>&num=1',
		  success: function(data) {
			$('#flickr_loading').fadeOut();
	    	
			
			$('#flickr_container').html(data);
			$('#flickr_container').animate({"opacity": "show"},500);
			$('#flickr_loading').css({"top":"250px"}).fadeIn();
			//second request
			$.ajax({
			  url: 'getFlickrPics.php?event_id=<?php echo $eventObj->getEventId(); ?>&num=2',
			  success: function(data) {
				$('#flickr_loading').fadeOut();
				
				$('#flickr_container').append(data);
				
				
				$('#flickr_loading').css({"top":"400px"}).fadeIn();
				//third request
				$.ajax({
				  url: 'getFlickrPics.php?event_id=<?php echo $eventObj->getEventId(); ?>&num=3',
				  success: function(data) {
					$('#flickr_loading').fadeOut();
					
					$('#flickr_container').append(data);
					
					
					
				  }
				});
				
			  }
			});
			
			
			
			
			
		  }
		});
	});
</script>
<div class="m_month_navigation_container float_left" style="margin-bottom: 10px;">
        
    <!-- month -->
    <div class="m_month_container float_left bg_black" id="month_label">
        <div class="month_name mark_white bebas font_medium" id="month_name">FLICKR</div>
    </div>
    
</div>

<!-- search -->
<div class="m_search_container float_right">
    <div class="m_event_back float_right"><a href="javascript:history.go(-1);" class="float_right bg_grey_ccc radius mark_grey padding_button_small"><?php echo $lang["BACK"]; ?></a></div>   
</div>

<div class="cleardiv"></div>

<div id="flickr_loading" class="modal_loading_flickr" style="display:none;">
    <div style="width:100%;text-align:center;text-decoration:blink"><?php echo $lang["FLICKR_LOADING"];?></div>
    <img src="../../images/small_loading.gif" border=0 />
</div>
<div class="event_flickr" id="flickr_container" style="display:none"></div>


</body>
</html>



