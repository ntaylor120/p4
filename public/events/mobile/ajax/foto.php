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


<!-- photo swipe -->
<script type="text/javascript" src="../lib/klass.min.js"></script>
<script type="text/javascript" src="../js/code.photoswipe-3.0.4.min.js"></script>

</head>
<body>
<script type="text/javascript">

    //jQuery version
    $(document).ready(function(){
    
        var myPhotoSwipe = $("#Gallery a.photo").photoSwipe({ enableMouseWheel: false , enableKeyboard: false });
		
    
    });
    
	
</script>
<!-- end photo swipe -->


<div class="m_month_navigation_container float_left" style="margin-bottom: 10px;">
        
    <!-- month -->
    <div class="m_month_container float_left bg_black" id="month_label">
        <div class="month_name mark_white bebas font_medium" id="month_name"><?php echo $lang["GETEVENT_GALLERY"]; ?></div>
    </div>
    
</div>


<!-- search -->
<div class="m_search_container float_right">
    <div class="m_event_back float_right"><a href="javascript:history.go(-1);" class="float_right bg_grey_ccc radius mark_grey padding_button_small"><?php echo $lang["BACK"]; ?></a></div>   
</div>

<div class="cleardiv"></div>


<div id="Gallery" class="gallery">
 <?php
	$arrImg = $eventObj->getEventImages();
	for($i = 1;$i<count($arrImg); $i++) {
		?>
		<a style="float:left; margin:1%;" class="photo" href="../../contents/events/<?php echo $eventObj->getEventId(); ?>/<?php echo $arrImg[$i]; ?>"><img src="../../contents/events/<?php echo $eventObj->getEventId(); ?>/thumbs/<?php echo $arrImg[$i]; ?>" border="0" /></a>
		<?php
	}
	?>


</div>
</body>
</html>
