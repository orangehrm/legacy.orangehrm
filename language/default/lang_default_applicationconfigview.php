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
require_once ROOT_PATH . '/lib/models/recruitment/JobVacancy.php';

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

switch ($_GET['recruitcode']) {

		case 'Application_Config' :

			$srchlist = array( "-$lang_Leave_Common_Select-" , $lang_Recruit_VacancyID , $lang_Recruit_JobTitleName, $lang_Recruit_HiringManager, $lang_Recruit_VacancyStatus);
			$headings = array("ID", "Question","Type","Order");
			$valueMap = array(null, null, null,null);
			$title = $lang_Recruit_Applicaton_configure_Title;
			$deletePrompt = "Are you sure you wont to delete";
			break;
}

?>
