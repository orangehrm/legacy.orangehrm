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


require_once ROOT_PATH . '/lib/common/Language.php';
require_once ROOT_PATH . '/lib/models/performance/PerformanceReview.php';
require_once ROOT_PATH . '/lib/models/performance/SalaryReview.php';

$lan = new Language();

require_once($lan->getLangPath("full.php"));

$Previous = $lang_empview_previous;
$Next     = $lang_empview_next;
$dispMessage = "$lang_empview_norecorddisplay!";
$SearchBy = $lang_empview_searchby;
$description = $lang_empview_description;
$search = $lang_empview_search;
$ADD_SUCCESS = $lang_empview_ADD_SUCCESS;
$UPDATE_SUCCESS = $lang_empview_UPDATE_SUCCESS;
$DELETE_SUCCESS = $lang_empview_DELETE_SUCCESS;

$ADD_FAILURE = $lang_empview_ADD_FAILURE;
$UPDATE_FAILURE = $lang_empview_UPDATE_FAILURE;
$DELETE_FAILURE = $lang_empview_DELETE_FAILURE;
$UNKNOWN_FAILURE = $lang_Common_UNKNOWN_FAILURE;

switch ($_GET['healthcode']) {

	
	
	
		case 'ErgonomicAssessments' :

			$srchlist = array( "-$lang_Leave_Common_Select-", $lang_Health_ErgonomicAssessment_ID, $lang_Health_ErgonomicAssessment_EmpName, 
				$lang_Health_ErgonomicAssessment_Subdivision, $lang_Health_ErgonomicAssessment_Locations,
				$lang_Health_ErgonomicAssessment_StartDate,	$lang_Health_ErgonomicAssessment_EndDate, 
				$lang_Health_ErgonomicAssessment_Status);
			$headings = array($lang_Health_ErgonomicAssessment_ID, $lang_Health_ErgonomicAssessment_EmpName, 
				$lang_Health_ErgonomicAssessment_Subdivision, $lang_Health_ErgonomicAssessment_Locations,
				$lang_Health_ErgonomicAssessment_StartDate,	$lang_Health_ErgonomicAssessment_EndDate, 
				$lang_Health_ErgonomicAssessment_Status);
			$valueMap = array(null, null, null, null, null, null, 
							array(ErgonomicAssessment::STATUS_INCOMPLETE => $lang_Health_ErgonomicAssessment_Incomplete,
							  ErgonomicAssessment::STATUS_COMPLETE => $lang_Health_ErgonomicAssessment_Complete));				

			$title = $lang_Health_ErgonomicAssessment_ListHeading;
			$deletePrompt = $lang_Health_ErgonomicAssessment_DeleteMessage;
			break;

		case 'Injuries' :
	
			$srchlist = array( "-$lang_Leave_Common_Select-", $lang_Health_Injury_ID, $lang_Health_Injury_EmpName, 
					$lang_Health_Injury_Subdivision, $lang_Health_Injury_Locations,
					$lang_Health_Injury_DateOfIncident, $lang_Health_Injury_DateReported,
					$lang_Health_Injury_Injury, $lang_Health_Injury_TimeOffWork,
					$lang_Health_Injury_Result);
			
			$headings = array($lang_Health_Injury_ID, $lang_Health_Injury_EmpName, 
					$lang_Health_Injury_Subdivision, $lang_Health_Injury_Locations,
					$lang_Health_Injury_DateOfIncident, $lang_Health_Injury_DateReported,
					$lang_Health_Injury_Injury, $lang_Health_Injury_TimeOffWork,
					$lang_Health_Injury_Result);
			$valueMap = array(null, null, null, null, null, null, null, null, null);
			$title = $lang_Health_Injury_ListHeading;
			$deletePrompt = $lang_Health_Injury_DeleteMessage;
			break;

		case 'RiskAssessments' :
		
			$srchlist = array( "-$lang_Leave_Common_Select-" , $lang_Health_RiskAssessment_ID, $lang_Health_RiskAssessment_Subdivision, 
				$lang_Health_RiskAssessment_StartDate, $lang_Health_RiskAssessment_EndDate, 
				$lang_Health_RiskAssessment_Description, $lang_Health_RiskAssessment_Status);
	
			$headings = array($lang_Health_RiskAssessment_ID, $lang_Health_RiskAssessment_Subdivision, 
				$lang_Health_RiskAssessment_StartDate, $lang_Health_RiskAssessment_EndDate, 
				$lang_Health_RiskAssessment_Description, $lang_Health_RiskAssessment_Status);
				
			$valueMap = array(null, null, null, null, null, 
				array(RiskAssessment::STATUS_UNRESOLVED => $lang_Health_RiskAssessment_Unresolved,
					  RiskAssessment::STATUS_RESOLVED => $lang_Health_RiskAssessment_Resolved));				
			$title = $lang_Health_RiskAssessment_ListHeading;
			$deletePrompt = $lang_Health_RiskAssessment_DeleteMessage;
			break;	
}

?>
