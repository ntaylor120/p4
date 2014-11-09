<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$username = $_REQUEST["username"];
	
	$newid=$spamObj->insertSpam($username);
	$spamObj->setSpam($newid);
	$newnum=$spamObj->getSpamRecordcount();
	if($newnum % 2) {
		$newclass="alternate_table_row_white";
	} else {
		$newclass="alternate_table_row_grey";
	}
	
	?>
    <div id="row_<?php echo $newid; ?>">
        <div class="spam_row_col1 <?php echo $newclass; ?>">
            <div id="table_cell"><?php echo $newnum; ?></div>
        </div>
        <div class="spam_row_col2 <?php echo $newclass; ?>">
            <div id="table_cell"><input type="checkbox" name="spam[]" value="<?php echo $newid; ?>" onclick="javascript:disableSelectAll('manage_spam',this.checked);" /></div>
        </div>                    
        <div class="spam_row_col3 <?php echo $newclass; ?>">
            <div id="table_cell">
            	<?php echo $spamObj->getSpamUsername(); ?>
            	
            </div>
        </div>
        <div class="spam_row_col4 <?php echo $newclass; ?>">
            <div id="table_cell"><span id="publish_<?php echo $newid; ?>"><?php if($spamObj->getSpamActive()=='1') { ?><a href="javascript:notSpam(<?php echo $newid; ?>);"><img src="images/icons/published.png" border=0 /></a><?php } else { ?><a href="javascript:isSpam(<?php echo $newid; ?>);"><img src="images/icons/unpublished.png" border=0 /></a><?php } ?></span>
            </div>
        </div>                                                 
        
        <div id="empty"></div>
    </div>
	
<?php
}
?>