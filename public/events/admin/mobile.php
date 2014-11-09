<?php 
include 'common.php';
if(!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
	header('Location: login.php');
}

if(isset($_POST["operation"]) && $_POST["operation"] != '') {
	$arrMobile=$_POST["mobile"];
	$qryString = "0";
	for($i=0;$i<count($arrMobile); $i++) {
		$qryString .= ",".$arrMobile[$i];
	}
		
	switch($_POST["operation"]) {
		case "delMobile":
			$mobileObj->delMobile($qryString);
			break;
	}                
	header('Location: mobile.php');
}
include 'include/header.php';
?>
<!-- 
=====================================================================
=====================================================================
-->

<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
        
        	<!-- 
            =======================
            === menu ==
            =======================
            -->
			<?php
            include 'include/menu.php';
            ?>
            <?php
			$arrayMobile = $listObj->getMobileList(); 
			
			?>
           
        
            <script>
                
                function delItem(itemId) {
                    if(confirm("<?php echo $lang["MOBILE_SINGLE_DELETE_CONFIRM"]; ?>")) {
                        $.ajax({
                          url: 'ajax/delMobileItem.php?item_id='+itemId,
                          success: function(data) {
							  dataArr=data.split("|");
                              $('#table').hide().html(dataArr[0]).fadeIn(2000);
                              if(Trim(dataArr[1]) == "0") {
								  hideActionBar();
							  }
                            
                          }
                        });
                    } 
                }
               
                
                function addMobile() {
                    if(Trim($('#mobile_name').val())!= '') {
                        $('#filter_submit').html('<img src="images/loading.gif">');
                        $.ajax({
                          url: 'ajax/addMobile.php?mobile_name='+$('#mobile_name').val(),
                          success: function(data) {
                              $('#filter_submit').html('<a href="javascript:addMobile();"><?php echo $lang["ADD"]; ?></a>');
                              $(data).hide().appendTo('#table').fadeIn(2000);							 
                              $('#mobile_name').val('');
							  showActionBar();
                            
                          }
                        });
                    } else {
                        alert("<?php echo $lang["MOBILE_ADD_ALERT"]; ?>");
                    }
                }
                function editItem(mobile) {
                    $('#modify_'+mobile).html('<a href="javascript:saveItem('+mobile+');"><?php echo $lang["SAVE"]; ?></a>');
                    $('#title_display_'+mobile).css({"display":"none"});
                    $('#title_input_'+mobile).css({"display":"block"});
                    
                }
                function saveItem(mobile) {
                    if(Trim($('#mobile_name_'+mobile).val()) != '') {
                        $.ajax({
                          url: 'ajax/saveMobile.php?item_id='+mobile+"&name="+$('#mobile_name_'+mobile).val(),
                          success: function(data) {
                             
                              $('#title_display_'+mobile).css({"display":"block"});
                              $('#title_input_'+mobile).css({"display":"none"});
                              $('#title_display_'+mobile).html($('#mobile_name_'+mobile).val());
                              $('#modify_'+mobile).html('<a href="javascript:editItem('+mobile+');"><?php echo $lang["MODIFY"]; ?></a>');
                              $('#row_'+mobile).hide().fadeIn(2000);
                              
                             
                            
                          }
                        });
                    }
                }
               
                $(function() {
                    <?php
                    if(count($arrayMobile)>0) {
                        ?>
                        showActionBar();
                        <?php
                    }
                    ?>
                });
                
                function showActionBar() {
                    $('#action_bar').slideDown();
                }
                function hideActionBar() {
                    $('#action_bar').slideUp();
                }
            </script>
             
            <!-- 
            =======================
            === create mobile ==
            =======================
            -->  
            <div class="add_calendar_container">
                <div class="create_calendar"><strong><?php echo $lang["ADD_MOBILE_BROWSER"]; ?></strong>: <?php echo $lang["TYPE_NAME"]; ?></div> 
                <div class="create_calendar"><input type="text" id="mobile_name" name="mobile_name"></div>   
                <div class="create_calendar"><a href="javascript:addMobile();"><?php echo $lang["ADD"]; ?></a></div>
            </div>    
            <!-- 
            =======================
            === action bar ==
            =======================
            -->
            <?php
			
			?>
            <div id="action_bar" style="display:none"> 
            	
                <div id="action"><a onclick="javascript:delItems('manage_mobile','mobile[]','delMobile','<?php echo $lang["MOBILE_MULTIPLE_DELETE_CONFIRM"]; ?>')"><?php echo $lang["DELETE"]; ?></a></div>
                
                <div class="title_action"><?php echo $lang["SELECTED_ITEMS"]; ?>:</div>
            </div>
             <!-- 
            =======================
            === table mobile ==
            =======================
            -->
            <form name="manage_mobile" action="" method="post">
                <input type="hidden" name="operation" />
                <input type="hidden" name="mobile[]" value=0 />
                <div id="table_container">
                    <div id="table">
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
						} ?>
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