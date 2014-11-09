<?php 
include 'common.php';
if(!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
	header('Location: login.php');
}

include 'include/header.php';

$twitterObj->setUser($_GET["id"]);
?>
<div id="top_bg_container_all">
<div id="container_all">
<div id="container_content">
<?php include 'include/menu.php'; ?>
<script>
	function approve() {
		$.ajax({
		  url: 'ajax/approveTwitter.php?id=<?php echo $_GET["id"]; ?>',
		  success: function(data) {
			alert('<?php echo $lang["TWITTER_APPROVED_USER_ALERT"]; ?>');
			document.location.href="twitter_approve.php";
		  }
		});
	}
	function disapprove() {
		$.ajax({
		  url: 'ajax/disapproveTwitter.php?id=<?php echo $_GET["id"]; ?>',
		  success: function(data) {
			alert('<?php echo $lang["TWITTER_SPAM_USER_ALERT"]; ?>');
			document.location.href="twitter_spam.php";
		  }
		});
	}
</script>
<div style="padding-top:100px;padding-bottom:100px;text-align:center;width:960px;">
<strong><?php echo $twitterObj->getUsername(); ?></strong><br /><br />
<input type="button" value="Approve" onclick="javascript:approve();"/><input type="button" value="<?php echo $lang["TWITTER_SPAM_BUTTON"]; ?>" onclick="javascript:disapprove();" />
</div>
</div>
</div>
</div>
<?php
include 'include/footer.php';
?>