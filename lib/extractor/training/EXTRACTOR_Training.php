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
require_once ROOT_PATH . '/lib/models/training/Training.php';

class EXTRACTOR_Training {

	public function __construct() {
		// nothing to do
	}

	public function parseUpdateData($postArr) {


		$tmpObj = new Training();

		if (!empty($postArr['txtId'])) {
			$tmpObj->setId($postArr['txtId']);
		}
					
		if (!empty($postArr['txtUserDefinedID'])) {
			$tmpObj->setUserDefinedId($postArr['txtUserDefinedID']);
		}

		if (!empty($postArr['txtDescription'])) {
			$tmpObj->setDescription($postArr['txtDescription']);
		}
		if (!empty($postArr['cmbState'])) {
			$tmpObj->setState($postArr['cmbState']);
		}
		if (!empty($postArr['txtTrainingCourse'])) {
			$tmpObj->setTrainingCourse($postArr['txtTrainingCourse']);
		}
		if (!empty($postArr['txtCost'])) {
			$tmpObj->setCost($postArr['txtCost']);
		}
		if (!empty($postArr['txtCompany'])) {
			$tmpObj->setCompany($postArr['txtCompany']);
		}
		if (!empty($postArr['txtNotes'])) {
			$tmpObj->setNotes($postArr['txtNotes']);
		}
				
		$assignedEmployees = array();
		if (isset($postArr['cmbAssignedEmployees']) && is_array($postArr['cmbAssignedEmployees'])) {
			foreach ($postArr['cmbAssignedEmployees'] as $emp) {
				$employee = array();
				$employee['emp_number'] = $emp;
				$assignedEmployees[] = $employee;
			}
		}
		$tmpObj->setEmployees($assignedEmployees);
					
		return $tmpObj;
	}

}
?>
