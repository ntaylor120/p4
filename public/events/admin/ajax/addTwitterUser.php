<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$username = $_REQUEST["username"];
	
	$newid=$twitterObj->insertUser($username);
	$twitterObj->setUser($newid);
	$newnum=$twitterObj->getUsersRecordcount();
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
            <div id="table_cell"><input type="checkbox" name="twitter[]" value="<?php echo $newid; ?>" onclick="javascript:disableSelectAll('manage_twitter',this.checked);" /></div>
        </div>                    
        <div class="spam_row_col3 <?php echo $newclass; ?>">
            <div id="table_cell">
            	<?php echo $twitterObj->getUsername(); ?>
            	
            </div>
        </div>
        <div class="spam_row_col4 <?php echo $newclass; ?>">
            <div id="table_cell"><span id="publish_<?php echo $newid; ?>"><?php if($twitterObj->getApproved()=='1') { ?><a href="javascript:isApprove(<?php echo $newid; ?>);"><img src="images/icons/published.png" border=0 /></a><?php } else { ?><a href="javascript:isSpam(<?php echo $newid; ?>);"><img src="images/icons/unpublished.png" border=0 /></a><?php } ?></span>
            </div>
        </div>                                                 
        
        <div id="empty"></div>
    </div>
	
<?php
}
?>