<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$title = $_REQUEST["mobile_name"];
	
	$newid=$mobileObj->addMobile($title);
	$mobileObj->setMobile($newid);
	$newnum=$mobileObj->getMobileRecordcount();
	if($newnum % 2) {
		$newclass="alternate_table_row_white";
	} else {
		$newclass="alternate_table_row_grey";
	}
	
	?>
    <div id="row_<?php echo $newid; ?>">
        <div class="mobile_row_col1 <?php echo $newclass; ?>">
            <div id="table_cell"><?php echo $newnum; ?></div>
        </div>
        <div class="mobile_row_col2 <?php echo $newclass; ?>">
            <div id="table_cell"><input type="checkbox" name="mobile[]" value="<?php echo $newid; ?>" onclick="javascript:disableSelectAll('manage_mobile',this.checked);" /></div>
        </div>                    
        <div class="mobile_row_col3 <?php echo $newclass; ?>">
            <div id="table_cell">
                <span id="title_display_<?php echo $newid; ?>"><?php echo $mobileObj->getMobileName(); ?></span>
                <span id="title_input_<?php echo $newid; ?>" style="display:none"><input type="text" name="mobile_name" id="mobile_name_<?php echo $newid; ?>" value="<?php echo $mobileObj->getMobileName(); ?>" ></span>
            
            </div>
        </div>
                          
        <div class="mobile_row_col4 <?php echo $newclass; ?>">
            <div id="table_cell"><span id="modify_<?php echo $newid; ?>"><a href="javascript:editItem(<?php echo $newid; ?>);"><?php echo $lang["MODIFY"]; ?></a></span></div>
        </div>
         
        <div class="mobile_row_col5 <?php echo $newclass; ?>">
            <div id="table_cell"><a href="javascript:delItem(<?php echo $newid; ?>,'mobile','mobile_id');"><?php echo $lang["DELETE"]; ?></a></div>
        </div>                            
       
        <div id="empty"></div>
    </div>
	
<?php
}
?>