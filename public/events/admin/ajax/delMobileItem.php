<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$item_id = $_REQUEST["item_id"];	
	
	mysql_query("DELETE FROM events_mobile_browsers WHERE browser_id = ".$item_id);
	
	
	
}


?>
<div class="mobile_title_col1">
    <div id="table_cell">#</div>
</div>
<div class="mobile_title_col2">
    <div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_mobile','mobile[]');" /></div>
</div>
<div class="mobile_title_col3">
    <div id="table_cell"><?php echo $lang["MOBILE_NAME"]; ?></div>
</div>
<div class="mobile_title_col4">
    <div id="table_cell"></div>
</div>
<div class="mobile_title_col5">
    <div id="table_cell"></div>
</div>

<div id="empty"></div>
<?php                         
$arrayMobile = $listObj->getMobileList();                        
$i=1;
foreach($arrayMobile as $mobileId => $mobile) {																			
    if($i % 2) {
        $class="alternate_table_row_white";
    } else {
        $class="alternate_table_row_grey";
    }
    
?>
<div id="row_<?php echo $mobileId; ?>">
    <div class="mobile_row_col1 <?php echo $class; ?>">
        <div id="table_cell"><?php echo $i; ?></div>
    </div>
    <div class="mobile_row_col2 <?php echo $class; ?>">
        <div id="table_cell"><input type="checkbox" name="mobile[]" value="<?php echo $mobileId; ?>" onclick="javascript:disableSelectAll('manage_mobile',this.checked);" /></div>
    </div>                    
    <div class="mobile_row_col3 <?php echo $class; ?>">
        <div id="table_cell">
            <span id="title_display_<?php echo $mobileId; ?>"><?php echo $mobile["browser_name"]; ?></span>
            <span id="title_input_<?php echo $mobileId; ?>" style="display:none"><input type="text" name="mobile_name" id="mobile_name_<?php echo $mobileId; ?>" value="<?php echo $mobile["browser_name"]; ?>" ></span>
        
        </div>
    </div>
                      
    <div class="mobile_row_col4 <?php echo $class; ?>">
        <div id="table_cell"><span id="modify_<?php echo $mobileId; ?>"><a href="javascript:editItem(<?php echo $mobileId; ?>);"><?php echo $lang["MODIFY"]; ?></a></span></div>
    </div>
     
    <div class="mobile_row_col5 <?php echo $class; ?>">
        <div id="table_cell"><a href="javascript:delItem(<?php echo $mobileId; ?>,'mobile','mobile_id');"><?php echo $lang["DELETE"]; ?></a></div>
    </div>                            
   
    <div id="empty"></div>
</div>
<?php 
$i++;
} ?>|<?php echo count($arrayMobile);?>
