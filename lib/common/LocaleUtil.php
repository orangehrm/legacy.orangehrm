<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */

require_once ROOT_PATH . '/lib/confs/sysConf.php';

/**
 * Class to handle locale specific formatting tasks
 * Implemented as a singleton.
 */
 class LocaleUtil {

	/** This singleton instance */
	private static $instance;

	private $sysConf;

	/**
	 * Set the sysConf instance used by this object.
	 *
	 * @param sysConf $sysConf sysConf object
	 */
	public function setSysConf($sysConf) {
		$this->sysConf = $sysConf;
	}

	/**
	 * Get the sysConf instance used by this object.
	 *
	 * @return sysConf object used by this Locale Util
	 */
	public function getSysConf() {
		return $this->sysConf;
	}

	public function getDateFormat() {
		return $this->sysConf->getDateFormat();
	}

	/**
	 * Private construct
	 */
	private function __construct() {
		$this->sysConf = new sysConf();
	}

	/**
	 * Get the singleton instance of this class
	 */
	 public static function getInstance() {

	 	if (!is_a(self::$instance, 'LocaleUtil')) {
	 		self::$instance = new LocaleUtil();
	 	}

		return self::$instance;
	 }

	 /**
	  * Converts the date format to the format configured in the system
	  *
	  * @param string date
	  * @return date formatted according to configured format
	  */
	 public function formatDate($date, $customFormat=null) {

	 	 if (empty($date) || ($date == "0000-00-00")) {
	 	 	return "";
	 	 }

		 $timeStamp = strtotime($date);

		 // Check if properly converted.
		 if (($timeStamp === false) || $timeStamp == -1 ) {
		 	return $date;
		 }

		 $format = $this->sysConf->getDateFormat();

		 if (empty($customFormat)) {

			 if (empty($format)) {
			 	return $date;
			 }
		 	$formattedValue = date($format, $timeStamp);
		 } else {
		 	$formattedValue = date($customFormat, $timeStamp);
		 }

		 return $formattedValue;
	 }

	/**
	  * Converts the time format to the format configured in the system
	  *
	  * @param string time
	  * @return time formatted according to configured format
	  */
	 public function formatTime($time, $customFormat=null) {

	 	 if (empty($time)) {
	 	 	return "";
	 	 }

		 $timeStamp = strtotime($time);

		 // Check if properly converted.
		 if (($timeStamp === false) || $timeStamp == -1 ) {
		 	return $time;
		 }

		 $format = $this->sysConf->getTimeFormat();

		 if (empty($customFormat)) {

			 if (empty($format)) {
			 	return $time;
			 }
		 	$formattedValue = date($format, $timeStamp);
		 } else {
		 	$formattedValue = date($customFormat, $timeStamp);
		 }

		 return $formattedValue;
	 }

	 /**
	  * Converts a date and time value to the format configured in the system
	  *
	  * @param string date and time to be converted
	  * @param string customFormat Custom date time format to be used instead of the system configured format
	  * @return date and time formatted according to configured format
	  */
	 public function formatDateTime($dateTime, $customFormat=null) {

	 	 if (empty($dateTime)) {
	 	 	return "";
	 	 }

		 $timeStamp = strtotime($dateTime);
		 // Check if properly converted.
		 if (($timeStamp === false) || $timeStamp == -1 ) {
		 	return $dateTime;
		 }

		 $dateFormat = $this->sysConf->getDateFormat();
		 $timeFormat = $this->sysConf->getTimeFormat();

		 if (empty($customFormat)) {

			 if (empty($dateFormat) || empty($timeFormat)) {
			 	return $dateTime;
			 }
			 $format = $dateFormat . " " . $timeFormat;
		 } else {
			 $format = $customFormat;
		 }

		 $formattedValue = date($format, $timeStamp);

		 return $formattedValue;
	 }

	 /**
	  * String date will be converted from the custom format to YYYY-mm-dd
	  *
	  * Right now only English dates will be convered.
	  *
	  * @param String date
	  * @param String customFormat(Optional)
	  * @return String standardDate
	  */
	 public function convertToStandardDateFormat($date, $customFormat=null) {
	 	if ($customFormat == null) {
	 		$format = LocaleUtil::convertToXpDateFormat($this->sysConf->getDateFormat());
	 	} else {
	 		$format = LocaleUtil::convertToXpDateFormat($customFormat);
	 	}

		$timeStamp=$this->_customFormatStringToTimeStamp($date, $format);
	 	if (!$timeStamp) {
	 		return null;
	 	}

	 	$standardDate = date('Y-m-d', $timeStamp);

	 	return $standardDate;
	 }

	 /**
	  * String time will be converted from the custom format to HH:MM
	  *
	  * Right now only English dates will be convered.
	  *
	  * @param String time
	  * @param String customFormat(Optional)
	  * @return String standardDate
	  */
	 public function convertToStandardTimeFormat($time, $customFormat=null) {
	 	if ($customFormat == null) {
	 		$format = LocaleUtil::convertToXpDateFormat($this->sysConf->getTimeFormat());
	 	} else {
	 		$format = LocaleUtil::convertToXpDateFormat($customFormat);
	 	}

	 	$timeStamp=$this->_customFormatStringToTimeStamp($time, $format);
	 	if (!$timeStamp) {
	 		return null;
	 	}

	 	$standardDate = date('H:i', $timeStamp);

	 	return $standardDate;
	 }

	 /**
	  * String time will be converted from the custom format to YYY-mm-dd HH:MM
	  *
	  * Right now only English dates will be convered.
	  *
	  * @param String time
	  * @param String customFormat (Optional)
	  * @return String standardDate time
	  */
	 public function convertToStandardDateTimeFormat($time, $customFormat=null) {
	 	if ($customFormat == null) {
	 		$format = LocaleUtil::convertToXpDateFormat("{$this->sysConf->getDateFormat()} {$this->sysConf->getTimeFormat()}");
	 	} else {
	 		$format = LocaleUtil::convertToXpDateFormat($customFormat);
	 	}

	 	$timeStamp=$this->_customFormatStringToTimeStamp($time, $format);

	 	if (!$timeStamp) {
	 		return null;
	 	}

	 	$standardDate = date('Y-m-d H:i', $timeStamp);

	 	return $standardDate;
	 }

	 /**
	  * Convert the date/time sting $time in the given $format to the UNIX timestamp
	  *
	  * @param String time
	  * @param String format
	  * @return Integer timestamp
	  */
	 private function _customFormatStringToTimeStamp($time, $format) {
		$yearVal = '';
		$monthVal = '';
		$dateVal = '';
		$hourVal = '';
		$minuteVal = '';
		$aVal = '';

		$format = str_split($format, 1);
		$time = str_split($time, 1);
		$j=0;

		for ($i=0; $i<count($time); $i++) {

			$ch = $format[$j];
			$sCh = $time[$i];

			if ($ch == 'd') {
		        $dateVal = $dateVal.$sCh;
		    } else if ($ch == 'M') {
		        $monthVal = $monthVal.$sCh;
		    } else if ($ch == 'y') {
		        $yearVal = $yearVal.$sCh;
		    } else if ($ch == 'H') {
		    	$hourVal = $hourVal.$sCh;
		    } else if ($ch == 'h') {
		        $hourVal = $hourVal.$sCh;
		        if ($hourVal > 12) return false;
		    } else if ($ch == 'm') {
		        $minuteVal = $minuteVal.$sCh;
		    } else if ($ch == 'a') {
		    	$i++;
		        $sCh.=$time[$i];
		        if ($sCh == 'PM') {
		        	$hourVal+=12;
		        } else if ($sCh != 'AM') {
		        	return false;
		        }
		    } else if ($ch == 'd') {
		        $dateVal = $dateVal.$sCh;
		    } else if ($ch == 'M') {
		        $monthVal = $monthVal.$sCh;
		    } else if ($ch == 'y') {
		        $yearVal = $yearVal.$sCh;
		    } else {
		    	if ($ch != $sCh) {
		    		return false;
		    	}
		    }

		    $j++;
		}

		if (($monthVal < 0) || ($monthVal > 12) || ($dateVal < 0) || ($dateVal > 31) || ($hourVal < 0) || ($hourVal > 24) || ($minuteVal < 0) || ($minuteVal > 59)) {
			return false;
		}

		if ($yearVal == "") {
			$yearVal="0000";
		}
		if ($monthVal == "") {
			$monthVal="00";
		}
		if ($dateVal == "") {
			$dateVal="00";
		}
		if ($hourVal == "") {
			$hourVal="00";
		}
		if ($minuteVal == "") {
			$minuteVal="00";
		}

		$timeStamp = strtotime("$yearVal-$monthVal-$dateVal $hourVal:$minuteVal");

		return $timeStamp;
	 }

	 /**
	  * Convert the PHP date format string to the Javascript time function format string
	  *
	  * @param String dateFormat;
	  * @return String Javascript date format string
	  */
	 public static function convertToXpDateFormat($dateFormat) {
		$map = array(// Day
					 'd'=>'dd',
					 'j'=>'d',
					 // Month
					 'm'=>'MM',
					 'n'=>'M',
					 // Year
					 'Y'=>'yyyy',
					 'y'=>'yy',
					 // Hours
					 'H'=>'HH',
					 'h'=>'hh',
					 'G'=>'H',
					 'g'=>'h',
					 // Minutes
					 'i'=>'mm',
					 // Seconds
					 's'=>'ss',
					 // AM/PM
					 'A'=>'a');

		$chars = str_split($dateFormat, 1);
		$conv = '';

		for ($i=0; $i<count($chars); $i++) {
			if (isset($map[$chars[$i]])) {
				$conv.=$map[$chars[$i]];
			} else {
				$conv.=$chars[$i];
			}
		}

		return $conv;
	}
}

class LocaleException extends Exception {
}

?>
