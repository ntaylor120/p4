<?php
include '../common.php';
$old = $_GET["old"];
$rsCheck=mysql_query("SELECT * FROM events_admins WHERE admin_id=".$_SESSION["admin_id"]." AND admin_password='".md5($old)."'");
echo mysql_num_rows($rsCheck);
?>