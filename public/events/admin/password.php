<?php
include 'common.php';
if(!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
	header('Location: login.php');
}
if(isset($_POST["password_confirm"])) {	
	$adminObj->updatePassword();
	?>
    <script>
		alert("<?php echo $lang["PASSWORD_CHANGED_ALERT"]; ?>");
		document.location.href="welcome.php";
	</script>
    <?php		
}

include 'include/header.php';
?>
<script language="javascript" type="text/javascript">
	function checkData(frm) {
		with(frm) {
			if(Trim(old_password.value)== '') {
				alert("<?php echo $lang["OLD_PASSWORD_ALERT"]; ?>");
				return false;
			} else if(Trim(password.value) == '' || Trim(password_confirm.value) == '' || Trim(password.value) != Trim(password_confirm.value)) {
				alert("<?php echo $lang["NEW_PASSWORD_ALERT"]; ?>");
				return false;
			} else {
				$.ajax({
				  url: 'ajax/checkOldPassword.php?old='+old_password.value,
				  success: function(data) {
					if(data == 1) {
						frm.submit();
					} else {
						alert("<?php echo $lang["OLD_PASSWORD_NOT_MATCH_ALERT"]; ?>");
					}
				  }
				});
				return false;
			}
		}
	}
</script>
<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
        <?php
        include 'include/menu.php'; 
        ?>
        <div id="form_container">
        	<form name="editsettings" action="" method="post" onsubmit="return checkData(this);">           
                
                <div id="label_input">
                    <div class="label_title"><label for="old_password"><?php echo $lang["PASSWORD_OLD_PASSWORD_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["PASSWORD_OLD_PASSWORD_SUBLABEL"]; ?></div>
                </div>
                <div id="input_box">
                    <input type="password" class="long_input_box" id="old_password" name="old_password" value="">
                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <div class="label_title"><label for="old_password"><?php echo $lang["PASSWORD_NEW_PASSWORD_LABEL"]; ?></label></div>
                </div>
                <div id="input_box">
                    <input type="password" class="long_input_box" id="password" name="password" value="">
                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <div class="label_title"><label for="old_password"><?php echo $lang["PASSWORD_CONFIRM_LABEL"]; ?></label></div>
                    <div class="label_subtitle"><?php echo $lang["PASSWORD_CONFIRM_SUBLABEL"]; ?></div>
                </div>
                <div id="input_box">
                    <input type="password" class="long_input_box" id="password_confirm" name="password_confirm" value="">
                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                
                <!-- bridge buttons -->
                <div class="bridge_buttons_container">
                    <!-- cancel -->
                    <div class="admin_button cancel_button" ><a href="javascript:document.location.href='';"></a></div>
                    
                    <!-- save -->
                    <div class="admin_button" style="margin-left:750px"><input type="submit" id="apply_button" name="saveunpublish" value=""></div>
                    
                </div>
                <div id="rowspace"></div>
             </form>
            
         </div>
        
        
        </div>
    </div>
</div>
<?php 
include 'include/footer.php';
?>