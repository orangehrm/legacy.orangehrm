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
require_once ROOT_PATH . '/lib/models/budget/Budget.php';

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

switch ($_GET['budgetcode']) {		
		
		case 'Budgets' :
	
			$srchlist = array( "-$lang_Leave_Common_Select-", $lang_Budget_Id, $lang_Budget_Type, $lang_Budget_Unit, 
				$lang_Budget_Value, $lang_Budget_StartDate, $lang_Budget_EndDate, $lang_Budget_Status);
				
			$headings = array($lang_Budget_Id, $lang_Budget_Type, $lang_Budget_Unit, 
				$lang_Budget_Value, $lang_Budget_StartDate, $lang_Budget_EndDate, $lang_Budget_Status);
				
			$valueMap = array(null, 
								array(Budget::BUDGET_TYPE_SALARY => $lang_Budget_Salary,
					 			 Budget::BUDGET_TYPE_TRAINING => $lang_Budget_Training,
					 			 Budget::BUDGET_TYPE_EMPLOYEE => $lang_Budget_Employee,
					 			 Budget::BUDGET_TYPE_COMPANY => $lang_Budget_Company),
							null, null, null, null,
							array(Budget::STATUS_CREATED => $lang_Budget_Created,
								  Budget::STATUS_SUBMITTED_FOR_APPROVAL => $lang_Budget_Submitted,
								  Budget::STATUS_NOT_APPROVED => $lang_Budget_NotApproved,
								  Budget::STATUS_APPROVED => $lang_Budget_Approved));				
	
			$title = $lang_Budget_ListHeading;
			$deletePrompt = $lang_Budget_DeleteMessage;
			break;	
}

?>
