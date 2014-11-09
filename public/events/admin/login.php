<?php
include 'common.php';
if(isset($_POST["username"]) && $_POST["username"] != '') {
	$result = $adminObj->doLogin($_POST["username"],$_POST["password"]);
	if($result>0) {
		
		
		$_SESSION["admin_id"] = $result;
		$_SESSION["admin_name"] = $adminObj->getAdminUsername();
		$_SESSION["numperpag"] = 10;
		
		
		
		header("Location: welcome.php");
	}  else {
		include 'include/header.php';
	}
} else {
	include 'include/header.php';
}

?>
<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
			<?php
            include 'include/menu.php'; 
            ?>          
            <div id="login_container">
                <div id="login_left"></div>
                <div id="login_right">
                    <form name="login" action="" method="post" tmt:validate="true">
                    <div id="form_field"><label for="username"><?php echo $lang["LOGIN_USERNAME"]; ?></label></div>
                    <div id="form_input"><input type="text" class="inputtext" id="username" name="username" tmt:required="true" tmt:message="<?php echo $lang["LOGIN_USERNAME_ALERT"]; ?>" tmt:errorclass="error_validate" tmt:filters="ltrim,rtrim" /></div>
                    <div id="cleardiv" style="height: 20px;"></div>
                    <div id="form_field"><label for="password"><?php echo $lang["LOGIN_PASSWORD"]; ?></label></div>
                    <div id="form_input"><input type="password" class="inputtext" name="password" id="password" tmt:required="true" tmt:message="<?php echo $lang["LOGIN_PASSWORD_ALERT"]; ?>" tmt:errorclass="error_validate" tmt:filters="ltrim,rtrim" /></div>
                    <div id="cleardiv"></div>
                    <div id="login_label"><?php echo $lang["LOGIN"]; ?></div>
                    <div id="login_button_container"><input type="submit" id="login_button" value=""></div>
                    </form>
                </div>
                <div id="cleardiv"></div>
                <?php 
				if(isset($_POST["username"])) {
				?>
                	<div id="alert_login"><?php echo $lang["LOGIN_ERROR"]; ?></div>
                <?php 
				} 
				?>
            </div>
            <div id="cleardiv"></div>
            <div id="margintopdiv" style="height: 30px;"></div>
        </div>
    </div>
</div>
<?php
include 'include/footer.php';
?>