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

require_once ROOT_PATH . '/lib/models/performance/PerformanceReview.php';
require_once ROOT_PATH . '/lib/models/performance/PerformanceScore.php';
require_once ROOT_PATH . '/lib/common/LocaleUtil.php';

class EXTRACTOR_PerfReview {

	public function __construct() {
		// nothing to do
	}

	public function parseUpdateData($postArr) {

		$tmpObj = new PerformanceReview();
					
		if (!empty($postArr['txtReviewId'])) {
			$tmpObj->setId($postArr['txtReviewId']);
		}
		
		if (isset($postArr['txtRepEmpID'])) {
			$tmpObj->setEmpNumber($postArr['txtRepEmpID']);
		}
		
		if (isset($postArr['txtReviewDate'])) {
        	$date = LocaleUtil::getInstance()->convertToStandardDateFormat($postArr['txtReviewDate']);
        	$tmpObj->setReviewDate($date);
		}

		if (isset($postArr['cmbStatus'])) {
			$tmpObj->setStatus($postArr['cmbStatus']);
		}
        		
		if (isset($postArr['txtNotes'])) {
			$tmpObj->setReviewNotes($postArr['txtNotes']);
		}

		$assignedPerfMeasures = array();
		if (isset($postArr['cmbAssignedPerfMeasures']) && is_array($postArr['cmbAssignedPerfMeasures'])) {
			
			$scoresAvailable = false;
			if (isset($postArr['perfScores']) && is_array($postArr['perfScores']) && 
					(count($postArr['perfScores']) == count($postArr['cmbAssignedPerfMeasures'])) ) {
				$scoresAvailable = true;
			}
			
			foreach ($postArr['cmbAssignedPerfMeasures'] as $perfMeasureCode) {
				$perfMeasureScore = ($scoresAvailable) ? new PerformanceScore($perfMeasureCode) : new PerformanceMeasure($perfMeasureCode);
				$assignedPerfMeasures[] = $perfMeasureScore;
			}
			
			if ($scoresAvailable) {
				
				for ($i = 0; $i < count($postArr['perfScores']); $i++) {
					$assignedPerfMeasures[$i]->setScore($postArr['perfScores'][$i]);
				}
			}
		}
		$tmpObj->setPerformanceMeasures($assignedPerfMeasures);
		return $tmpObj;
	}

}
?>
