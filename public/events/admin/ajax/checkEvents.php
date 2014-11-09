<?php
include '../common.php';
$rsCheck=mysql_query("SELECT * FROM events_events WHERE event_starttime='".$_GET["time"].":00' AND event_id!=".$_GET["current"]);
echo mysql_num_rows($rsCheck);
?>