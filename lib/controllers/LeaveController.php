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

//the model objects are included here

require_once ROOT_PATH . '/lib/models/leave/Leave.php';
require_once ROOT_PATH . '/lib/models/leave/LeaveType.php';
require_once ROOT_PATH . '/lib/models/leave/LeaveQuota.php';
require_once ROOT_PATH . '/lib/models/leave/LeaveSummary.php';
require_once ROOT_PATH . '/lib/models/leave/Holidays.php';
require_once ROOT_PATH . '/lib/models/leave/Weekends.php';
require_once ROOT_PATH . '/lib/models/leave/mail/MailNotifications.php';

require_once ROOT_PATH . '/lib/models/hrfunct/EmpRepTo.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpInfo.php';

require_once ROOT_PATH . '/lib/common/TemplateMerger.php';
require_once ROOT_PATH . '/lib/common/authorize.php';

class LeaveController {

	private $indexCode;
	private $id;
	private $leaveTypeId;
	private $objLeave;
	private $authorize;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setLeaveTypeId($leaveTypeId) {
		$this->leaveTypeId = $leaveTypeId;
	}

	public function getLeaveTypeId() {
		return $this->leaveTypeId;
	}

	public function setObjLeave($obj) {
		$this->objLeave = $obj;
	}

	public function getObjLeave() {
		return $this->objLeave;
	}

	public function setAuthorize($obj) {
		$this->authorize = $obj;
	}

	public function getAuthorize() {
		return $this->authorize;
	}


	public function __construct() {
		$authorizeObj = new authorize($_SESSION['empID'], $_SESSION['isAdmin']);

		$this->setAuthorize($authorizeObj);

		$tmpLeaveObj = new Leave();

		$tmpLeaveObj->takeLeave();
	}

	//public function

	public function viewLeaves($modifier="employee", $year=null, $details=false, $sortField = null, $sortOrder = null) {

		if ($details) {
			switch ($modifier) {
				case "employee": $this->setObjLeave(new Leave());
								 $this->_viewLeavesEmployee($details);
								 break;
				case "suprevisor": $this->setObjLeave(new Leave());
								 $this->_viewLeavesSupervisor($details);
								 break;
				case "taken"	: $this->setObjLeave(new Leave());
								  $this->_viewLeavesTaken($year, $details);
								 break;
				case "summary" : $this->setObjLeave(new LeaveSummary());
								 $this->_displayLeaveSummary("display", $year, $details, $sortField, $sortOrder);
								 break;
			}
		} else {
			switch ($modifier) {
				case "employee": $this->setObjLeave(new LeaveRequests());
								 $this->_viewLeavesEmployee($details);
								 break;
				case "suprevisor": 	$this->setObjLeave(new LeaveRequests());
								 	$this->_viewLeavesSupervisor($details);
								 	break;
				case "taken"	: $this->setObjLeave(new LeaveRequests());
								 $this->_viewLeavesTaken($year, $details);
								 break;
				case "summary" : $this->setObjLeave(new LeaveSummary());
								 $this->_displayLeaveSummary("display", $year, null, $sortField, $sortOrder);
								 break;
			}
		}
	}

	public function editLeaveTypes($leaveTypes) {

		$changedCount = 0;

		if (isset($leaveTypes)) {

			// Test for duplicate names
			$leaveTypeNames = array();
			foreach ($leaveTypes as $leaveType) {

				$name = $leaveType->getLeaveTypeName();
				if (in_array($name, $leaveTypeNames)) {
					$this->redirect("DUPLICATE_LEAVE_TYPE_ERROR");
					return;
				}
				$leaveTypeNames[] = $name;
			}

			foreach ($leaveTypes as $leaveType) {
				$this->setObjLeave($leaveType);
				$res = $this->editLeaveType();
				if ($res === false) {
					$this->redirect("LEAVE_TYPE_EDIT_ERROR");
					return;
				} else {
					$changedCount += $res;
				}
			}
		}

		if ($changedCount > 0) {
			$this->redirect("LEAVE_TYPE_EDIT_SUCCESS");
		} else {
			$this->redirect("NO_CHANGES_TO_SAVE_WARNING");
		}
	}

	public function editLeaves($modifier="summary", $year=null, $esp=null, $sortField = null, $sortOrder = null) {
		switch ($modifier) {
			case "summary" : $this->setObjLeave(new LeaveSummary());
							 $this->_displayLeaveSummary("edit", $year, $esp, $sortField, $sortOrder);
							 break;
		}
	}

	/**
	 * Changes the status of the leave
	 *
	 * @param [String $modifier]
	 * @return String
	 */
	public function changeStatus($modifier="cancel") {

		switch ($modifier) {
			case "cancel": $res = $this->_cancelLeave();
						   break;
			case "change": $res = $this->_changeLeaveStatus();
						   break;
		}

		if ($res) {
			$message=true;
		} else {
			$message=false;
		}

		return $message;
	}

	private function _changeLeaveStatus() {
		$this->_authenticateChangeLeaveStatus();

		$tmpObj = $this->getObjLeave();

		return $tmpObj->changeLeaveStatus($this->getId());
	}

	/**
	 * Checks whether the id is untampered
	 *
	 */
	private function _authenticateViewLeaveDetails() {

		if ($_REQUEST['digest'] != md5($this->getId().SALT)) {
			trigger_error("Unauthorized access", E_USER_NOTICE);
		}
	}

	private function _viewLeavesEmployee($details) {
		$tmpObj = $this->getObjLeave();

		if (!$details) {
			$tmpObj = $tmpObj->retriveLeaveRequestsEmployee($this->getId());
			$path = "/templates/leave/leaveRequestList.php";
		} else {
			$this->_authenticateViewLeaveDetails();
			$tmpObj = $tmpObj->retrieveLeave($this->getId());
			$path = "/templates/leave/leaveList.php";
		}

		$template = new TemplateMerger($tmpObj, $path);

		$template->display();
	}

	/**
	 * Cancelled leave notification
	 *
	 * @param mixed $obj
	 * @param boolean $request
	 */
	public function sendCancelledLeaveNotification($obj, $request=false) {
		$this->_sendChangedLeaveNotification($obj, $request, MailNotifications::MAILNOTIFICATIONS_ACTION_CANCEL);
	}

	/**
	 * Workhorse function for sendChangedLeaveNotification and sendCancelledLeaveNotification
	 *
	 * @param mixed $obj
	 * @param boolean $request
	 * @param String $action
	 */
	private function _sendChangedLeaveNotification($obj, $request=false, $action) {
		$mailNotificaton = new MailNotifications();

		if ($request) {
			$mailNotificaton->setLeaveRequestObj($obj);
		} else {
			$mailNotificaton->setLeaveObjs($obj);
		}

		$mailNotificaton->setAction($action);
		$mailNotificaton->send();
	}

	/**
	 * Sending mail notification when leave status change
	 *
	 * @param mixed $objs
	 * @param boolean $request
	 * @return boolean
	 */
	public function sendChangedLeaveNotification($objs, $request=false) {
		if (!isset($objs)) {
			return false;
		}

		$approveObj = null;
		$rejectedObj = null;

		if ($request) {
			switch ($objs->getLeaveStatus()) {
				case Leave::LEAVE_STATUS_LEAVE_APPROVED : $approveObj = $objs;
														  break;
				case Leave::LEAVE_STATUS_LEAVE_REJECTED : $rejectedObj = $objs;
														  break;
			}
		} else {
			if (!is_array($objs)) {
				return false;
			}
			foreach ($objs as $obj) {
				if ($obj && is_a($obj, 'Leave')) {
					switch ($obj->getLeaveStatus()) {
						case Leave::LEAVE_STATUS_LEAVE_APPROVED : $approveObj[] = $obj;
																  break;
						case Leave::LEAVE_STATUS_LEAVE_REJECTED : $rejectedObj[] = $obj;
																  break;
					}
				}
			}
		}

		$this->_sendChangedLeaveNotification($approveObj, $request, MailNotifications::MAILNOTIFICATIONS_ACTION_APPROVE);
		$this->_sendChangedLeaveNotification($rejectedObj, $request, MailNotifications::MAILNOTIFICATIONS_ACTION_REJECT);

		return true;
	}

	/**
	 * Suprevisor's view of the leaves of subordinates
	 *
	 * @return void
	 */
	private function _viewLeavesSupervisor($details) {
		$tmpObj = $this->getObjLeave();

		if (!$details) {
			$tmpObj = $tmpObj->retriveLeaveRequestsSupervisor($this->getId());
			$path = "/templates/leave/leaveRequestList.php";
		} else {
			$this->_authenticateViewLeaveDetails();
			$tmpObj = $tmpObj->retrieveLeave($this->getId());
			$path = "/templates/leave/leaveList.php";
		}

		$template = new TemplateMerger($tmpObj, $path);

		$modifiers[] = "SUP";

		$template->display($modifiers);
	}

	private function _cancelLeave() {
		$tmpObj = $this->getObjLeave();

		return $tmpObj->cancelLeave($this->getId());
	}

	public function redirect($message=null, $url = null, $id = null, $cust=null) {
		if (isset($message)) {

			preg_replace('/[&|?]+id=[A-Za-z0-9]*/', "", $_SERVER['HTTP_REFERER']);

			if (preg_match('/&/', $_SERVER['HTTP_REFERER']) > 0) {
				$message = "&message=".$message;
				$url = preg_split('/(&||\?)message=[A-Za-z0-9]*/', $_SERVER['HTTP_REFERER']);
			} else {
				$message = "?message=".$message;
			}

			if (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && !is_array($_REQUEST['id'])) {
				$id = "&id=".$_REQUEST['id'];
			} else if (isset($id)) {
				$id="&id={$id}";
			} else {
				$id="";
			}
		} else {
			if (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && (preg_match('/&/', $_SERVER['HTTP_REFERER']) > 0)) {
				$id = "&id=".$_REQUEST['id'];
			} else if (preg_match('/&/', $_SERVER['HTTP_REFERER']) == 0){
				$id = "?id=".$_REQUEST['id'];
			} else {
				$id="";
			}
		}

		if (isset($cust)) {
			$url[0].=$cust;
		}

		header("Location: {$url[0]}{$message}{$id}");
	}

	public function addLeave() {
		$tmpObj = $this->getObjLeave();
		$res = $tmpObj->applyLeaveRequest();

		$mailNotificaton = new MailNotifications();

		$mailNotificaton->setLeaveRequestObj($tmpObj);

		$mailNotificaton->setAction(MailNotifications::MAILNOTIFICATIONS_ACTION_APPLY);
		$mailNotificaton->send();

		$message = ($res) ? "APPLY_SUCCESS" : "APPLY_FAILURE";

		return $message;
	}

	public function adminApproveLeave() {
		$tmpObj = $this->getObjLeave();
		$tmpObj->setLeaveStatus(Leave::LEAVE_STATUS_LEAVE_APPROVED);
		$res = $tmpObj->changeLeaveStatus();
		$message = ($res) ? "APPROVE_SUCCESS" : "APPROVE_FAILURE";
		return $message;
	}

	public function displayLeaveInfo($admin=false) {
		$authorizeObj = $this->authorize;

		if ($admin) {
			if ($authorizeObj->getIsAdmin() == 'Yes') {
				$empObj = new EmpInfo();
				$tmpObjs[0] = array(true);
			} else if ($authorizeObj->isSupervisor()) {
				$empRepToObj = new EmpRepTo();
				$tmpObjs[0] = $empRepToObj->getEmpSubDetails($authorizeObj->getEmployeeId());
			}

			$roles = array(authorize::AUTHORIZE_ROLE_ADMIN, authorize::AUTHORIZE_ROLE_SUPERVISOR);
			$role = $authorizeObj->firstRole($roles);

			$previousLeave = null;

			if (isset($_GET['id'])) {
				$leaveObj = new Leave();

				$previousLeaves = $leaveObj->retrieveLeave($_GET['id']);
				$previousLeave = $previousLeaves[0];

				if ($authorizeObj->isSupervisor() && !($authorizeObj->isTheSupervisor($previousLeave->getEmployeeId()))) {
					$previousLeave=null;
				}
			}

			$this->setId($_SESSION['empID']);
			$tmpObj = new LeaveType();
			$tmpObjs[1] = $tmpObj->fetchLeaveTypes();
			$tmpObjs[2] = $role;
			$tmpObjs[3] = $previousLeave;
		} else {

			$this->setId($_SESSION['empID']);
			$tmpObj = new LeaveQuota();
			$tmpObjs[1] = $tmpObj->fetchLeaveQuota($this->getId());
		}

		$this->setObjLeave($tmpObjs);

		$path = "/templates/leave/leaveApply.php";

		$template = new TemplateMerger($tmpObjs, $path);

		$template->display();
	}

	public function gotoLeaveHomeSupervisor() {
		$tmpObj = new LeaveRequests();

		$tmpObj = $tmpObj->retriveLeaveRequestsSupervisor($this->getId());

		if ($tmpObj == null) {
			$this->displayLeaveTypeSummary();
			return true;
		}

		$this->viewLeaves("suprevisor");
		return true;
	}

	public function copyLeaveQuotaFromLastYear($currYear) {
		if ($_SESSION['isAdmin'] !== 'Yes') {
			trigger_error("Unauthorized access", E_USER_NOTICE);
		}

		$leaveQuotaObj = new LeaveQuota();

		$res = false;

		if ($this->_validToCopyQuotaFromLastYear($currYear)) {
			$res = $leaveQuotaObj->copyQuota($currYear-1, $currYear);
		}

		if ($res) {
			$this->redirect("LEAVE_QUOTA_COPY_SUCCESS", null, null, "&year=$currYear&id=0");
		} else {
			$this->redirect("LEAVE_QUOTA_COPY_FAILURE", null, null, "&year=$currYear&id=0");
		}
	}

	private function _validToCopyQuotaFromLastYear($currYear) {
		if ($_SESSION['isAdmin'] !== 'Yes') {
			return false;
		}

		$leaveQuotaObj = new LeaveQuota();

		$leaveQuotaObj->setYear($currYear);
		$currYearQuota = $leaveQuotaObj->fetchLeaveQuota(0);

		$leaveQuotaObj->setYear($currYear-1);
		$prevYearQuota = $leaveQuotaObj->fetchLeaveQuota(0);

		$copyQuota = false;

		if ((count($currYearQuota) == 0) && (count($prevYearQuota) > 0)) {
			$copyQuota = true;
		}

		return $copyQuota;
	}

	/**
	 * Displays the Leave Summary
	 *
	 */
	private function _displayLeaveSummary($modifier='display', $year = null, $esp=null, $sortField = null, $sortOrder = null) {
		if (!isset($year)) {
			$year = date('Y');
		}

		$auth = $this->_authenticateViewLeaveSummary();

		$copyQuota = $this->_validToCopyQuotaFromLastYear($year);

		$modifier = array($modifier, $auth, $year, $copyQuota);

		$empInfoObj = new EmpInfo();

		$tmpObj = $this->getObjLeave();
		$tmpObjX[] = $tmpObj->fetchAllEmployeeLeaveSummary($this->getId(), $year, $this->getLeaveTypeId(), $esp, $sortField, $sortOrder);
		$tmpObjX[] = $empInfoObj->filterEmpMain($this->getId());

		$path = "/templates/leave/leaveSummary.php";

		$template = new TemplateMerger($tmpObjX, $path);

		$template->display($modifier);
	}

	/**
	 * Checks whether the user is allowed to
	 * view the particular employee's Leave Summary
	 *
	 */
	private function _authenticateViewLeaveSummary() {
		$id = $this->getId();

		if (($_SESSION['isAdmin'] !== 'Yes') && ($id !== $_SESSION['empID'])){

			$objReportTo = new EmpRepTo();

			$subordinates = $objReportTo->getEmpSub($_SESSION['empID']);

			for ($i=0; $i < count($subordinates); $i++) {
				if (in_array($id, $subordinates[$i])) {
					$subordinate = true;
					break;
				}
			}

			if (!$subordinate) {
				trigger_error("Unauthorized access", E_USER_NOTICE);
			} else {
				return "supervisor";
			}
		} else if ($_SESSION['isAdmin'] === 'Yes') {
			return "admin";
		} else if ($id === $_SESSION['empID']) {
			return "self";
		}

		trigger_error("Unauthorized access", E_USER_NOTICE);
	}

	/**
	 * Checks whether the user is allowed to
	 * change the particular employee's Leave status
	 *
	 */
	private function _authenticateChangeLeaveStatus() {
		$status = $this->getObjLeave()->getLeaveStatus();

		if ($status != $this->getObjLeave()->statusLeaveCancelled) {
			$id = $this->getObjLeave()->getEmployeeId();
		}

		if (isset($id)) {

			$objReportTo = new EmpRepTo();

			$subordinates = $objReportTo->getEmpSub($_SESSION['empID']);

			$subordinate = false;

			for ($i=0; $i < count($subordinates); $i++) {
				if (in_array($id, $subordinates[$i])) {
					$subordinate = true;
					break;
				}
			}

			if (!$subordinate) {
				trigger_error("Unauthorized access", E_USER_NOTICE);
			}
		} else if (isset($id) && ($id === $_SESSION['empID'])) {
			trigger_error("Unauthorized access1", E_USER_NOTICE);
		}
	}

	public function displayLeaveTypeDefine() {

		$leaveType = new LeaveType();

		$this->setObjLeave($leaveType);

		$path = "/templates/leave/leaveTypeDefine.php";

		$tmpObj[0] = $leaveType;
		$tmpObj[1] = $leaveType->fetchLeaveTypes(true);
		$template = new TemplateMerger($tmpObj, $path);

		$template->display();
	}


	public function addLeaveType() {

		$tmpObj = $this->getObjLeave();
		$newName = $tmpObj->getLeaveTypeName();

		$action = "Leave_Type_View_Define";

		if ($tmpObj->getLeaveTypeWithName($newName)) {
			$message = "NAME_IN_USE_ERROR";
		} else {
			$res = $tmpObj->addLeaveType();
			if ($res) {
				$action = "Leave_Type_Summary";
				$message="ADD_SUCCESS";
			} else {
				$message="ADD_FAILURE";
			}
		}

		$this->redirect(null, array("?leavecode=Leave&action={$action}&message={$message}"));
	}

	/**
	 * Undelete Leave type
	 */
	public function undeleteLeaveType() {

		$tmpObj = $this->getObjLeave();
		$newName = $tmpObj->getLeaveTypeName();

		$action = "Leave_Type_View_Define";

		$leaveTypes = $tmpObj->getLeaveTypeWithName($newName, true);
		if ($leaveTypes) {
			foreach($leaveTypes as $leaveType) {
				$leaveType->undeleteLeaveType();
			}
			$message = "UNDELETE_SUCCESS";
			$action = "Leave_Type_Summary";
		} else {
			$message="LEAVE_TYPE_NOT_FOUND";
		}

		$this->redirect(null, array("?leavecode=Leave&action={$action}&message={$message}"));
	}

	public function displayLeaveTypeSummary(){

		$tmpObj = new LeaveType();

		$this->setObjLeave($tmpObj);

		$tmpObjArr = $tmpObj->fetchLeaveTypes(true);

		$path = "/templates/leave/leaveTypeSummary.php";

		$template = new TemplateMerger($tmpObjArr, $path);

		$template->display();
	}


	public function displayLeaveEditTypeDefine(){

		$tmpObj = new LeaveType();

		$this->setObjLeave($tmpObj);

		$tmpOb[0] = $tmpObj->retriveLeaveType($this->getId());
		$tmpObj[1] = $leaveType->fetchLeaveTypes();

		$path = "/templates/leave/leaveTypeDefine.php";

		$template = new TemplateMerger($tmpOb, $path);

		$template->display();
	}

	public function editLeaveType() {

		$tmpObj = $this->getObjLeave();
		$res = $tmpObj->editLeaveType();
		return $res;
	}

	public function LeaveTypeDelete() {

		$tmpObj = $this->getObjLeave();

		$res = $tmpObj->deleteLeaveType();

		if ($res) {
			$message="";
		} else {
			$message="FAILURE";
		}

		return $message;
	}


	public function saveLeaveQuota() {
		$tmpObj = $this->getObjLeave();

		$res = $tmpObj->editLeaveQuota();

		if ($res) {
			$message="";
		} else {
			$message="FAILURE";
		}

		return $message;
	}

	/**
	 * Display select employee
	 *
	 * @param String $action
	 */
	public function viewSelectEmployee($action) {
		$tmpObj = new Leave();
		$this->setObjLeave($tmpObj);

		$tmpOb[0] = $tmpObj->getLeaveYears();

		$authorizeObj = $this->authorize;

		if ($this->getAuthorize()->isAdmin()) {
			$empObj = new EmpInfo();
			$tmpOb[1] = array(true);
			$leaveTypeObj = new LeaveType();
			$tmpOb[2] = $leaveTypeObj->fetchLeaveTypes();
		} else {
			$repObj = new EmpRepTo();
			$tmpOb[1] = $repObj->getEmpSubDetails($_SESSION['empID']);
		}

		$roles = array(authorize::AUTHORIZE_ROLE_ADMIN, authorize::AUTHORIZE_ROLE_SUPERVISOR);
		$role = $authorizeObj->firstRole($roles);

		$tmpOb[3] = $role;

		$path = "/templates/leave/leaveSelectEmployeeAndYear.php";

		$template = new TemplateMerger($tmpOb, $path);

		$template->display($action);
	}

	private function _viewLeavesTaken($year = null) {
		$authorizeObj  = new authorize($_SESSION['empID'], $_SESSION['isAdmin']);


		if ($authorizeObj->isAdmin()) {

			$employeeId = $this->getId();
			$tmpObj = $this->getObjLeave();

			$empInfoObj = new EmpInfo();

			$res[] = $tmpObj->retrieveTakenLeave($year, $employeeId);
			$res[] = $empInfoObj->filterEmpMain($this->getId());

			$path = "/templates/leave/leaveList.php";

			$template = new TemplateMerger($res, $path);

			$modifiers[] = "Taken";
			$modifiers[] = $year;

			$template->display($modifiers);

		} else {
			trigger_error("Unauthorized access1", E_USER_NOTICE);
		}
	}


	/**
	 * Holidays and week end list viewing
	 *
	 * @param String $modifier
	 */
	public function viewHoliday($modifier="specific") {
		$this->_authenticateViewHoliday();
		switch ($modifier) {
			case "specific" : $this->_displaySpecificHoliday($modifier);
							 break;
			case "weekend" : $this->_displayWeekend();
							 break;
		}
	}

	public function addHoliday() {
		$this->_authenticateViewHoliday();

		$this->getObjLeave()->add();
	}

	/**
	 * Wrpper to edit holidays
	 *
	 * @param unknown_type $modifier
	 */
	public function editHoliday($modifier="specific") {
		$this->_authenticateViewHoliday();

		switch ($modifier) {
			case "specific" : $this->getObjLeave()->edit();
							  break;
			case "weekend" 	: $this->getObjLeave()->editDay();
							  break;
		}
	}

	private function _displaySpecificHoliday($modifier) {
		if (!isset($year)) {
			$year = date('Y');
		}

		$modifier = array($modifier, $year);

		$tmpObj = new Holidays();

		$tmpObjX = $tmpObj->listHolidays();

		$path = "/templates/leave/specificHolidaysList.php";

		$template = new TemplateMerger($tmpObjX, $path);

		$template->display($modifier);

	}

	private function _displayWeekend() {

	}

	private function _authenticateViewHoliday() {
		$res = $this->getAuthorize()->isAdmin();

		if ($res) {
			return $res;
		}

		trigger_error("Unauthorized access", E_USER_NOTICE);
	}

	public function holidaysDelete() {
		$this->getObjLeave()->delete();

		return "";
	}

	public function displayDefineHolidays($modifier="specific", $edit=false) {
		$this->_authenticateViewHoliday();

		$record = null;
		if ($edit) {
			$holidayObj = new Holidays();

			$record = $holidayObj->fetchHoliday($this->getId());
		}

		switch ($modifier) {
			case "specific"	:	$path = "/templates/leave/specificHolidaysDefine.php";
								break;
			case "weekend"	:	$path = "/templates/leave/weekendHolidaysDefine.php";
								$weekendsObj = new Weekends();
								$record = $weekendsObj->fetchWeek();
								break;
		}

		$template = new TemplateMerger($record, $path);

		$modifier = $edit;

		$template->display($modifier);
	}

}
?>
