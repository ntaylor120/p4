<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	if($_GET["customdates"]!='') {
		$resultDate = array();	
		$arrayDates =explode(",", $_GET["customdates"]);
		for($i=0;$i<count($arrayDates);$i++) {
			array_push($resultDate,str_replace("-","",$arrayDates[$i]));
		}
	} else {
		$weekdaysArr=explode(",",$_GET["weekdays"]);
		/**********analyzing weekdays through date range*********/
		//separate day, month and year
		$arrDateFrom=explode(",",$_GET["date_from"]);
		if($_GET["date_to"]!='') {
			$arrDateTo=explode(",",$_GET["date_to"]);
		} else {
			$arrDateTo=explode(",",$_GET["date_from"]);
		}
		//get an int for the two dates
		$dateFrom=str_replace(",","",$_GET["date_from"]);
		if($_GET["date_to"]!='') {
			$dateTo=str_replace(",","",$_GET["date_to"]);
		} else {
			$dateTo=str_replace(",","",$_GET["date_from"]);
		}
		//loop over weekdays selected
		$resultDate = array();	
		
		for($i=0;$i<count($weekdaysArr);$i++) {
			
			$newdateFrom=$dateFrom;
			
			
			$year=$arrDateFrom[0];			
			$day = $arrDateFrom[2];
			$mo = $arrDateFrom[1];
			
			$date = strtotime(date('Y-m-d',mktime(0,0,0,$mo,$day,$year)));
			$weekday = date("N", $date);
			
			$j = 1;
			
			while ($weekday != $weekdaysArr[$i] && date("Ymd",$date)<$dateTo) {
				
				$date=strtotime(date("Y-m-d", $date) . "+ 1 day");
				
				$weekday = date("N", $date);
				
			}
			
			if(date("N", $date) == $weekdaysArr[$i]) {
				array_push($resultDate,date('Ymd',$date));
			}
			
			while ($newdateFrom <= $dateTo) {
				
				$test =  strtotime(date("Y-m-d", $date) . "+" . $j . " week");
				$j++;
				if(date("Ymd",$test) <= $dateTo) {
					array_push($resultDate,date("Ymd", $test));
				}
				
				$newdateFrom = date("Ymd",$test);
			}
			
		}
	}
	sort($resultDate,SORT_NUMERIC);
	echo implode(",",$resultDate);
}
?>
