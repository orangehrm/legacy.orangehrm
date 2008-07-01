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
require_once ROOT_PATH . '/lib/models/budget/Budget.php';

class EXTRACTOR_Budget {

	public function __construct() {
		// nothing to do
	}

	public function parseUpdateData($postArr) {


		$tmpObj = new Budget();

		if (!empty($postArr['txtId'])) {
			$tmpObj->setId($postArr['txtId']);
		}				

		$tmpObj->setBudgetType($postArr['cmbBudgetType']);

		if (!empty($postArr['txtBudgetUnit'])) {
			$tmpObj->setBudgetUnit($postArr['txtBudgetUnit']);
		}
		if (!empty($postArr['txtBudgetValue'])) {
			$tmpObj->setBudgetValue($postArr['txtBudgetValue']);
		}
		if (!empty($postArr['txtStartDate'])) {
			$startDate = LocaleUtil::getInstance()->convertToStandardDateFormat($postArr['txtStartDate']);
			$tmpObj->setStartDate($startDate);
		}
		if (!empty($postArr['txtEndDate'])) {
			$endDate = LocaleUtil::getInstance()->convertToStandardDateFormat($postArr['txtEndDate']);			
			$tmpObj->setEndDate($endDate);
		}

		$tmpObj->setStatus($postArr['cmbStatus']);

		if (!empty($postArr['txtNotes'])) {
			$tmpObj->setNotes($postArr['txtNotes']);
		}
				
		return $tmpObj;
	}

}
?>
