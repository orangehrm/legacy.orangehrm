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
 */
 require_once ROOT_PATH . '/lib/models/recruitment/JobApplication.php';

 class EXTRACTOR_ApplicationField {

	/**
	 * Parse data from interface and return JobApplication Object
	 * @param Array $postArr Array containing POST values
	 * @return JobApplication Job Application object
	 */
	public function parseData($postArr) {

		$field = new JobApplicationField();
		if (isset($postArr['txtLable']) && !empty($postArr['txtLable'])) {
			$field->setLable(trim($postArr['txtLable']));
		}
		if (isset($postArr['txtId']) && !empty($postArr['txtId'])) {
			$field->setId(trim($postArr['txtId']));
		}		
		$field->setTabOrder(trim($postArr['txtTabOrder']));
		$field->setFieldType(trim($postArr['cmbType']));
		$field->setToolTip(trim($postArr['txtTooltip']));	
		return $field;
	}

}
?>
