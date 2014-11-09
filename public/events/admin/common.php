<?php 
ini_set("memory_limit",-1);
set_time_limit(0);
include_once dirname(__FILE__).'/include/db_conn.php';
include_once dirname(__FILE__).'/class/settings.class.php';
$settingObj = new setting();
date_default_timezone_set($settingObj->getTimezone());
$now = new DateTime();
$mins = $now->getOffset() / 60;
$sgn = ($mins < 0 ? -1 : 1);
$mins = abs($mins);
$hrs = floor($mins / 60);
$mins -= $hrs * 60;
$offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
mysql_query("SET time_zone='$offset';");
mysql_query("SET sql_mode = '';");

define('CALENDAR_PATH',$settingObj->getSiteDomain());

include_once dirname(__FILE__).'/include/lang.php';
include_once dirname(__FILE__).'/include/utils.php';
$func = new functions();

include_once dirname(__FILE__).'/class/admin.class.php';
include_once dirname(__FILE__).'/class/list.class.php';
include_once dirname(__FILE__).'/class/calendar.class.php';
include_once dirname(__FILE__).'/class/event.class.php';
include_once dirname(__FILE__).'/class/twitter.approve.class.php';
include_once dirname(__FILE__).'/class/twitter.spam.class.php';
include_once dirname(__FILE__).'/class/mobile.class.php';

$listObj = new lists();
$adminObj = new admin();
$calendarObj = new calendar();
$eventObj = new event();
$twitterObj = new twitter_approve();
$spamObj = new twitter_spam();
$mobileObj = new mobile();


session_start();

?>
