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
require_once ROOT_PATH . '/lib/common/LocaleUtil.php';
require_once ROOT_PATH . '/lib/models/healthAndSafety/EmpInjury.php';

class EXTRACTOR_Injury {

	public function __construct() {
		// nothing to do
	}

	public function parseUpdateData($postArr) {


		$tmpObj = new EmpInjury();

		if (!empty($postArr['txtId'])) {
			$tmpObj->setId($postArr['txtId']);
		}
					
		if (!empty($postArr['txtRepEmpID'])) {
			$tmpObj->setEmpNumber($postArr['txtRepEmpID']);
		}

		if (!empty($postArr['txtInjury'])) {
			$tmpObj->setInjury($postArr['txtInjury']);
		}

		if (!empty($postArr['txtDescription'])) {
			$tmpObj->setDescription($postArr['txtDescription']);
		}
		
		if (!empty($postArr['txtIncidentDate'])) {
			$incidentDate = LocaleUtil::getInstance()->convertToStandardDateFormat($postArr['txtIncidentDate']);
			$tmpObj->setIncidentDate($incidentDate);
		}
		
		if (!empty($postArr['txtReportedDate'])) {
			$reportedDate = LocaleUtil::getInstance()->convertToStandardDateFormat($postArr['txtReportedDate']);			
			$tmpObj->setReportedDate($reportedDate);
		}
		
		if (!empty($postArr['txtTimeOffWork'])) {
			$tmpObj->setTimeOffWork($postArr['txtTimeOffWork']);
		}
		if (!empty($postArr['txtResult'])) {
			$tmpObj->setResult($postArr['txtResult']);
		}
		return $tmpObj;
	}

}
?>
