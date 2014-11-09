<?php 

set_time_limit(0);
include_once dirname(__FILE__).'/admin/include/db_conn.php';
include_once dirname(__FILE__).'/include/lang.php';
include_once dirname(__FILE__).'/class/settings.class.php';
$settingObj = new setting();
date_default_timezone_set($settingObj->getTimezone());
define('CALENDAR_PATH',$settingObj->getSiteDomain());
include_once dirname(__FILE__).'/class/list.class.php';
include_once dirname(__FILE__).'/class/calendar.class.php';
include_once dirname(__FILE__).'/class/event.class.php';
include_once dirname(__FILE__).'/class/utility.class.php';


$listObj = new lists();
$calendarObj = new calendar();
$eventObj = new event();
$utilityObj = new utility();


$is_mobile = null;

if (!isset($_SERVER["HTTP_USER_AGENT"])) {
	//$mobile = true;
	$is_mobile = false;
}

$browsers = $listObj->getBrowserArray();
if (is_null($is_mobile) && count($browsers)) {
	foreach ($browsers as $browser) {
		if (!empty($browser) && strpos($_SERVER["HTTP_USER_AGENT"], trim($browser)) !== false) {
			$is_mobile = true;
		}
	}
}
if (is_null($is_mobile)) {
	$is_mobile = false;
	//$mobile = true;
}

/*if($is_mobile) {
	header('Location: mobile/index.php');
}*/

session_start();

?>
