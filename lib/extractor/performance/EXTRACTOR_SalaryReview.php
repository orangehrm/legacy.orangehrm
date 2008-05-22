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
require_once ROOT_PATH . '/lib/models/performance/SalaryReview.php';

class EXTRACTOR_SalaryReview {

	public function __construct() {
		// nothing to do
	}

	public function parseUpdateData($postArr) {

		$salaryReview = new SalaryReview();
					
		if (!empty($postArr['txtReviewId'])) {
			$salaryReview->setId($postArr['txtReviewId']);
		} else {
			
			// New salary review. Set created date and created by fields
			$createdTime = date(LocaleUtil::STANDARD_DATETIME_FORMAT);
			$salaryReview->setCreatedTime($createdTime);	
			
			$userId = $_SESSION['user'];
			$salaryReview->setCreatedBy($userId);
		}
		
		if (isset($postArr['txtRepEmpID'])) {
			$salaryReview->setEmpNumber($postArr['txtRepEmpID']);
		}
		
		if (isset($postArr['txtNotes'])) {
        	$salaryReview->setDescription($postArr['txtNotes']);
		}

		if (isset($postArr['txtIncrease'])) {
			$salaryReview->setIncrease($postArr['txtIncrease']);
		}
		        		
		return $salaryReview;
	}

}
?>
