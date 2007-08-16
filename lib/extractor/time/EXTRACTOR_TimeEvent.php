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

				$projectId = $postArr['cmbProject'][$i];
				if (empty($projectId)) {
					continue;
				}

				$tmpObj->setProjectId($projectId);

				$txtStartTime = trim($postArr['txtStartTime'][$i]);
				if (!empty($txtStartTime)) {
					$tmpObj->setStartTime($txtStartTime);
				}

				$txtEndTime = trim($postArr['txtEndTime'][$i]);
				if (!empty($txtEndTime)) {
					$tmpObj->setEndTime($txtEndTime);
				}

				$txtReportedDate = trim($postArr['txtReportedDate'][$i]);
				$tmpObj->setReportedDate($txtReportedDate);

				if (isset($postArr['txtDuration'][$i])) {

					$txtDuration = trim($postArr['txtDuration'][$i]);
					if (!empty($txtDuration)) {
						$tmpObj->setDuration($txtDuration*3600);
					}
				}

				$tmpObj->setDescription(stripslashes($postArr['txtDescription'][$i]));

				if (isset($postArr['txtTimeEventId'][$i])) {
					$tmpObj->setTimeEventId(trim($postArr['txtTimeEventId'][$i]));
				}
				$tmpObj->setEmployeeId(trim($postArr['txtEmployeeId']));
				$tmpObj->setTimesheetId(trim($postArr['txtTimesheetId']));

				$tmpArr[] = $tmpObj;
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
