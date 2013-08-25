<?php

namespace MDLeadership\lib\utils;

class DateUtils {
	
	static function dateBefore($firstDateString, $secondDateString=null) {
		$victoriaTimeZone = new DateTimeZone("America/Vancouver");
		$firstDate = new DateTime($firstDateString, $victoriaTimeZone);
		$secondDate = new DateTime($secondDateString, $victoriaTimeZone);
		
		return $firstDate < $secondDate;
	} // dateBefore
	
	static function dateAfter($firstDate, $secondDate=null) {
		$victoriaTimeZone = new DateTimeZone("America/Vancouver");
		$firstDate = new DateTime($firstDateString, $victoriaTimeZone);
		$secondDate = new DateTime($secondDateString, $victoriaTimeZone);
		
		return $firstDate > $secondDate;
	} // dateAfter
	
	static function getPrettyDate($dateString=null) {
		$victoriaTimeZone = new DateTimeZone("America/Vancouver");
		$date = new DateTime($dateString, $victoriaTimeZone);
			
		return $date->format("l, F j, Y");
	} // getPrettyDate
	
} // DateUtils