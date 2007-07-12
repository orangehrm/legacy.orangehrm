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

require_once ROOT_PATH . '/lib/models/time/TimeEvent.php';

class EXTRACTOR_TimeEvent {

	public function __construct() {
		//nothing to do
	}

	public function parseEditData($postArr) {
		$tmpArr = null;

		for ($i=0; $i<count($postArr['cmbCustomer']); $i++) {
				$tmpObj = new TimeEvent();

				$tmpObj->setProjectId($postArr['cmbProject'][$i]);

				if (!empty($postArr['txtStartTime'][$i])) {
					$tmpObj->setStartTime($postArr['txtStartTime'][$i]);
				}

				if (!empty($postArr['txtEndTime'][$i])) {
					$tmpObj->setEndTime($postArr['txtEndTime'][$i]);
				}

				$tmpObj->setReportedDate($postArr['txtReportedDate'][$i]);

				if (isset($postArr['txtDuration'][$i]) && !empty($postArr['txtDuration'][$i])) {
					$tmpObj->setDuration($postArr['txtDuration'][$i]*3600);
				} else if (isset($postArr['txtStartTime'][$i]) && isset($postArr['txtEndTime'][$i])){
					$startTime=strtotime($postArr['txtStartTime'][$i]);
					$endTime=strtotime($postArr['txtEndTime'][$i]);
					if ($endTime > $startTime) {
						$tmpObj->setDuration($endTime-$startTime);
					} else {
						$tmpObj->setDuration(0);
					}
				}

				$tmpObj->setDescription(stripslashes($postArr['txtDescription'][$i]));

				if (isset($postArr['txtTimeEventId'][$i])) {
					$tmpObj->setTimeEventId($postArr['txtTimeEventId'][$i]);
				}
				$tmpObj->setEmployeeId($postArr['txtEmployeeId']);
				$tmpObj->setTimesheetId($postArr['txtTimesheetId']);

				if (!empty($postArr['cmbProject'][$i])) {
					$tmpArr[] = $tmpObj;
				}
		}

		return $tmpArr;
	}

	public function parseDeleteData($postArr) {
		$tmpArr = null;

		for ($i=0; $i<count($postArr['deleteEvent']); $i++) {
			$tmpObj = new TimeEvent();

			$tmpObj->setTimeEventId($postArr['deleteEvent'][$i]);

			$tmpArr[] = $tmpObj;
		}

		return $tmpArr;
	}
}
?>
