<?php 
include 'common.php';
if(!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
	header('Location: login.php');
}

include 'include/header.php';
?>
<div id="top_bg_container_all">
<div id="container_all">
<div id="container_content">
<?php include 'include/menu.php'; ?>
<?php
if(isset($_POST["operation"]) && $_POST["operation"] != '') {
	$arrTwitter=$_POST["twitter"];
	$qryString = "0";
	for($i=0;$i<count($arrTwitter); $i++) {
		$qryString .= ",".$arrTwitter[$i];
	}
		
	switch($_POST["operation"]) {
		case "approve":
			$twitterObj->approve($qryString);
			break;
		case "disapprove":
			$twitterObj->disapprove($qryString);
			break;
		
			
	}
	
	?>
    <script>
		document.location.href="";
	</script>
    <?php
}




?>

<script language="javascript" type="text/javascript">	
	
	
	function isApprove(twitter) {
		$.ajax({
		  url: "ajax/publishTwitter.php?twitter_user_id="+twitter,
		  success: function(data) {
			document.location.reload();
		  }

		});
	}
	
	function isSpam(twitter) {
		$.ajax({
		  url: "ajax/unpublishTwitter.php?twitter_user_id="+twitter,
		  success: function(data) {
			document.location.href="twitter_spam.php";
		  }

		});
	}
	
	function addUsername() {
		
		if(Trim($('#twitter_username').val()) == '') {
			alert("<?php echo $lang["TWITTER_INSERT_USERNAME_ALERT"]; ?>");
			
		} else {
			$.ajax({
			  url: 'ajax/addTwitterUser.php?username='+$('#twitter_username').val(),
			  success: function(data) {
				
				  $(data).hide().appendTo('#table').fadeIn(2000);							 
				  $('#twitter_username').val('');
			  }
			});
		}
		
	}
</script>

<!-- 
=======================
=== insert user ==
=======================
-->  
<div class="add_calendar_container">
    <div class="create_calendar"><strong><?php echo $lang["TWITTER_ADD_USER_LABEL"]; ?></strong>: <?php echo $lang["TWITTER_ADD_USER_SUBLABEL"]; ?></div> 
    <div class="create_calendar"><input type="text" id="twitter_username" name="twitter_username"></div>   
    <div class="create_calendar"><a href="javascript:addUsername();"><?php echo $lang["ADD"]; ?></a></div>
</div>    
<div id="action_bar">
     <div id="action"><a onclick="javascript:delItems('manage_twitter_approve','twitter[]','approve','<?php echo $lang["TWITTER_MULTIPLE_APPROVE_CONFIRM"]; ?>')"><?php echo $lang["APPROVE"]; ?></a></div>
     <div id="action"><a onclick="javascript:delItems('manage_twitter_approve','twitter[]','disapprove','<?php echo $lang["TWITTER_MULTIPLE_SPAM_CONFIRM"]; ?>')"><?php echo $lang["SIGN_AS_SPAM"]; ?></a></div>
	
</div>
<form name="manage_twitter_approve" action="" method="post" onsubmit="return false;">
<input type="hidden" name="operation" />
<input type="hidden" name="twitter[]" value=0 />
<div id="table_container">
	<div id="table">
    	<div class="spam_title_col1">
        	<div id="table_cell">#</div>
        </div>
        <div class="spam_title_col2">
        	<div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_twitter_approve','twitter[]');" /></div>
        </div>
        <div class="spam_title_col3">
        	<div id="table_cell"><?php echo $lang["TWITTER_USER_LABEL"]; ?></div>
        </div>        
        <div class="spam_title_col4">
        	<div id="table_cell"><?php echo $lang["TWITTER_APPROVED_LIST_LABEL"]; ?></div>
        </div>
       
        <div id="empty"></div>
        
        <?php 
		$arrayTwitter = $listObj->getTwitterList();
		$i=1;
		foreach($arrayTwitter as $twitterId => $twitter) {
			if($i % 2) {
				$class="alternate_table_row_white";
			} else {
				$class="alternate_table_row_grey";
			}
		?>
		<div class="spam_row_col1 <?php echo $class; ?>">
			<div id="table_cell"><?php echo $i; ?></div>
		</div>
		<div class="spam_row_col2 <?php echo $class; ?>">
			<div id="table_cell"><input type="checkbox" name="twitter[]" value="<?php echo $twitterId;?>" onclick="disableSelectAll('manage_twitter_approve',this.checked);" /></div>
		</div>
		<div class="spam_row_col3 <?php echo $class;?>">
			<div id="table_cell"><?php echo $twitter["twitter_username"];?></div>
		</div>
		<div class="spam_row_col4 <?php echo $class;?>">
			<div id="table_cell"><?php if($twitter["twitter_approved"]=='0') { ?><a href="javascript:isApprove(<?php echo $twitterId;?>);"><img src="images/icons/unpublished.png" border=0 /></a><?php } else { ?><a href="javascript:isSpam(<?php echo $twitterId;?>);"><img src="images/icons/published.png" border=0 /></a><?php } ?></div>
		</div>
		
		<div id="empty"></div>
		<?php 
		$i++;
		} 
		?>
        
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