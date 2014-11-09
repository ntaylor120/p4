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
	$arrSpam=$_POST["spam"];
	$qryString = "0";
	for($i=0;$i<count($arrSpam); $i++) {
		$qryString .= ",".$arrSpam[$i];
	}
		
	switch($_POST["operation"]) {
		/*case "deleteSpam":
			$spamObj->deleteSpam($qryString);
			break;
		case "reportAsSpam":
			$spamObj->reportAsSpam($qryString);
			break;
			*/
		case "reportAsNotSpam":
			$spamObj->reportAsNotSpam($qryString);
			break;
		
			
	}
	
	?>
    <script>
		document.location.href="twitter_approve.php";
	</script>
    <?php
}




?>

<script language="javascript" type="text/javascript">	
	
	function isSpam(spam) {
		$.ajax({
		  url: "ajax/publishSpam.php?spam_id="+spam,
		  success: function(data) {
			document.location.reload();
		  }

		});
	}
	function notSpam(spam) {
		$.ajax({
		  url: "ajax/unpublishSpam.php?spam_id="+spam,
		  success: function(data) {
			document.location.href="twitter_approve.php";
		  }

		});
	}
	function addUsername() {
		
		if(Trim($('#spam_username').val()) == '') {
			alert("<?php echo $lang["TWITTER_INSERT_USERNAME_ALERT"]; ?>");
			
		} else {
			$.ajax({
			  url: 'ajax/addTwitterSpam.php?username='+$('#spam_username').val(),
			  success: function(data) {
				
				  $(data).hide().appendTo('#table').fadeIn(2000);							 
				  $('#spam_username').val('');
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
    <div class="create_calendar"><input type="text" id="spam_username" name="spam_username"></div>   
    <div class="create_calendar"><a href="javascript:addUsername();"><?php echo $lang["ADD"]; ?></a></div>
</div>    
<div id="action_bar">
     
     <div id="action"><a onclick="javascript:delItems('manage_spam','spam[]','reportAsNotSpam','<?php echo $lang["TWITTER_MULTIPLE_REMOVE_SPAM_CONFIRM"]; ?>')"><?php echo $lang["REMOVE_FROM_SPAM_LIST"]; ?></a></div>
</div>
<form name="manage_spam" action="" method="post" onsubmit="return false;">
<input type="hidden" name="operation" />
<input type="hidden" name="spam[]" value=0 />
<div id="table_container">
	<div id="table">
    	<div class="spam_title_col1">
        	<div id="table_cell">#</div>
        </div>
        <div class="spam_title_col2">
        	<div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_spam','spam[]');" /></div>
        </div>
        <div class="spam_title_col3">
        	<div id="table_cell"><?php echo $lang["TWITTER_USER_LABEL"]; ?></div>
        </div>        
        <div class="spam_title_col4">
        	<div id="table_cell"><?php echo $lang["TWITTER_SPAM_LIST_LABEL"]; ?></div>
        </div>
       
        <div id="empty"></div>
        
        <?php 
		$arraySpam = $listObj->getSpamList();
		$i=1;
		foreach($arraySpam as $spamId => $spam) {
			if($i % 2) {
				$class="alternate_table_row_white";
			} else {
				$class="alternate_table_row_grey";
			}
		?>
		<div class="spam_row_col1 <?php echo $class; ?>">
			<div id="table_cell"><?php echo $i; ?></div>
		</div>
		<div class="spam_row_col2 <?php echo $class;?>">
			<div id="table_cell"><input type="checkbox" name="spam[]" value="<?php echo $spamId; ?>" onclick="disableSelectAll('manage_spam',this.checked);" /></div>
		</div>
		<div class="spam_row_col3 <?php echo $class; ?>">
			<div id="table_cell"><?php echo $spam["spam_username"];?></div>
		</div>
		<div class="spam_row_col4 <?php echo $class; ?>">
			<div id="table_cell"><?php if($spam["spam_active"]=='1') { ?><a href="javascript:notSpam(<?php echo $spamId;?>);"><img src="images/icons/unpublished.png" border=0 /></a><?php } else { ?><a href="javascript:isSpam(<?php echo $spamId; ?>);"><img src="images/icons/published.png" border=0 /></a><?php } ?></div>
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