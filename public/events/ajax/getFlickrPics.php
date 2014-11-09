<?php
include '../common.php';
$num = $_GET["num"];
$eventObj->setEvent($_GET["event_id"]);  
$arrayPics=$eventObj->getFlickrRequest($settingObj->getFlickrApiKey(),$num);


foreach($arrayPics as $picId => $pic) {
	?>
	<a href="<?php echo $pic["linkPic"];?>" target="_blank" style="margin-right:10px;margin-bottom:10px;float:left"><img src="<?php echo $pic["urlPic"];?>" border=0 /></a>
    <?php
}
?>

			
