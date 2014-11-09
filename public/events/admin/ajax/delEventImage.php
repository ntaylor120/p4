<?php
include '../common.php';
$image="../../contents/events/temp/".$_POST["image"];
unlink($image);
?>