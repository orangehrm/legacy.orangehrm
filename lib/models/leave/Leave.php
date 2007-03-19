<?php
/**
 *
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 hSenid Software International Pvt. Ltd, http://www.hsenid.com
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

require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';

require_once ROOT_PATH . '/lib/models/leave/LeaveType.php';
require_once ROOT_PATH . '/lib/models/leave/Holidays.php';
require_once ROOT_PATH . '/lib/models/leave/Weekends.php';

/**
 * Leave Class
 *
 * This class handles taking/request/approve of leave, and list leaves
 *
 */
class Leave {

	/*
	 *	Leave Status Constants
	 *
	 **/
	const LEAVE_LENGTH_FULL_DAY = 8;
	const LEAVE_LENGTH_HALF_DAY_MORNING = -4;
	const LEAVE_LENGTH_HALF_DAY_AFTERNOON = 4;
	const LEAVE_LENGTH_HALF_DAY = 4;

	const LEAVE_STATUS_LEAVE_REJECTED = -1;
	const LEAVE_STATUS_LEAVE_CANCELLED = 0;
	const LEAVE_STATUS_LEAVE_PENDING_APPROVAL = 1;
	const LEAVE_STATUS_LEAVE_APPROVED = 2;
	const LEAVE_STATUS_LEAVE_TAKEN = 3;

	public $statusLeaveRejected = -1;
	public $statusLeaveCancelled = 0;
	public $statusLeavePendingApproval = 1;
	public $statusLeaveApproved = 2;
	public $statusLeaveTaken = 3;

	/*
	 *	Leave Length Constants
	 *
	 **/
	public $lengthFullDay = 8;
	public $lengthHalfDayMorning = -4;
	public $lengthHalfDayAfternoon = 4;

	/*
	 *	Class Attributes
	 *
	 **/

	private $leaveId;
	private $leaveRequestId;
	private $employeeId;
	private $leaveTypeId;
	private $leaveTypeName;
	private $dateApplied;
	private $leaveDate;
	private $leaveLength;
	private $leaveStatus;
	private $leaveComments;
	private $employeeName;

	protected $weekends;


	/*
	 *
	 *	Class Constructor
	 *
	 **/

	public function __construct() {
		$weekendObj = new Weekends();
		$this->weekends = $weekendObj->fetchWeek();
	}

	/*
	 *	Setter method followed by getter method for each
	 *	attribute
	 *
	 **/
	public function setLeaveId($leaveId) {
		$this->leaveId = $leaveId;
	}

	public function getLeaveId() {
		return $this->leaveId;
	}

	public function setLeaveRequestId($leaveId) {
		$this->leaveRequestId = $leaveId;
	}

	public function getLeaveRequestId() {
		return $this->leaveRequestId;
	}

	public function setEmployeeId($employeeId) {
		$this->employeeId = $employeeId;
	}

	public function getEmployeeId() {
		return $this->employeeId;
	}

	public function setLeaveTypeId($leaveTypeId) {
		$this->leaveTypeId = $leaveTypeId;
	}

	public function getLeaveTypeId() {
		return $this->leaveTypeId;
	}

	public function setLeaveTypeName($leaveTypeName) {
		$this->leaveTypeName = $leaveTypeName;
	}

	public function getLeaveTypeName() {
		return $this->leaveTypeName;
	}

	public function setDateApplied($dateApplied) {
		$this->dateApplied = $dateApplied;
	}

	public function getDateApplied() {
		return $this->dateApplied;
	}

	public function setLeaveDate($leaveDate) {
		$this->leaveDate = $leaveDate;
	}

	public function getLeaveDate() {
		return $this->leaveDate;
	}

	public function setLeaveLength($leaveLength) {
		$this->leaveLength = $leaveLength;
	}

	public function getLeaveLength() {
		return $this->leaveLength;
	}

	public function setLeaveStatus($leaveStatus) {
		$this->leaveStatus = $leaveStatus;
	}

	public function getLeaveStatus() {
		return $this->leaveStatus;
	}

	public function setLeaveComments($leaveComments) {
		$this->leaveComments = $leaveComments;
	}

	public function getLeaveComments() {
		return $this->leaveComments;
	}

	public function setEmployeeName($employeeName) {
		$this->employeeName = $employeeName;
	}

	public function  getEmployeeName() {
		return $this->employeeName;
	}

	/**
	 * Retrieves leave taken for supervisors and
	 * HRAdmin
	 *
	 * @param String $year, String $employeeId
	 * @return Leave[][] $leaveArr A 2D array of the leaves
	 */
	public function retrieveTakenLeave($year, $employeeId) {

		$this->setEmployeeId($employeeId);

		$sqlBuilder = new SQLQBuilder();

		$arrFields[0] = 'a.`leave_date`';
		$arrFields[1] = 'a.`leave_status`';
		$arrFields[2] = 'a.`leave_length`';
		$arrFields[3] = 'a.`leave_comments`';
		$arrFields[4] = 'a.`leave_id`';
		$arrFields[5] = 'd.`emp_firstname`';
		$arrFields[6] = 'a.`employee_id`';
		$arrFields[7] = 'b.`leave_type_name` as leave_type_name';

		$arrTables[0] = "`hs_hr_leave` a";
		$arrTables[1] = "`hs_hr_employee` d";
		$arrTables[2] = "`hs_hr_leave_requests` b";

		$joinConditions[1] = "a.`employee_id` = d.`emp_number`";
		$joinConditions[2] = "a.`leave_request_id` = b.`leave_request_id`";

		$selectConditions[1] = "a.`employee_id` = '".$employeeId."'";
		$selectConditions[2] = "a.`leave_status` = ".$this->statusLeaveTaken;
		$selectConditions[3] = "a.`leave_date` >= '".$year."-01-01'";

		$query = $sqlBuilder->selectFromMultipleTable($arrFields, $arrTables, $joinConditions, $selectConditions);

		//echo $query;

		$dbConnection = new DMLFunctions();

		$result = $dbConnection -> executeQuery($query);

		$leaveArr = $this->_buildObjArr($result, true);

		return $leaveArr;
	}

	public function retrieveLeave($requestId) {

		$this->setLeaveRequestId($requestId);

		$sqlBuilder = new SQLQBuilder();

		$arrFields[0] = 'a.`leave_date` as leave_date';
		$arrFields[1] = 'a.`leave_status` as leave_status';
		$arrFields[2] = 'a.`leave_length` as leave_length';
		$arrFields[3] = 'a.`leave_comments` as leave_comments';
		$arrFields[4] = 'a.`leave_id` as leave_id';
		$arrFields[5] = 'b.`leave_type_name` as leave_type_name';
		$arrFields[6] = 'c.`emp_firstname` as emp_firstname';
		$arrFields[7] = 'a.`employee_id` as employee_id';
		$arrFields[8] = 'a.`leave_request_id` as leave_request_id';

		$arrTables[0] = "`hs_hr_leave` a";
		$arrTables[1] = "`hs_hr_leave_requests` b";
		$arrTables[2] = "`hs_hr_employee` c";

		$selectConditions[1] = "a.`leave_request_id` = '".$requestId."'";
		$selectConditions[2] = "a.`leave_date` >= '".date('Y')."-01-01'";

		$joinConditions[1] = "a.`leave_request_id` = b.`leave_request_id`";
		$joinConditions[2] = "a.`employee_id` = c.`emp_number`";

		$query = $sqlBuilder->selectFromMultipleTable($arrFields, $arrTables, $joinConditions, $selectConditions);
		//echo $query."<br>";
		$dbConnection = new DMLFunctions();

		$result = $dbConnection -> executeQuery($query);

		$leaveArr = $this->_buildObjArr($result, true);

		return $leaveArr;
	}

	/**
	 *	Retrieves Leave Details of all leave that have been applied for but
	 *	not yet taken of the employee.
	 *
	 * @return Leave[][] $leaveArr A 2D array of the leaves
	 */
	public function retrieveLeaveEmployee($employeeId) {

		$sqlBuilder = new SQLQBuilder();

		$arrFields[0] = '`leave_date`';
		$arrFields[1] = '`leave_status`';
		$arrFields[2] = '`leave_length`';
		$arrFields[3] = '`leave_comments`';
		$arrFields[4] = '`leave_id`';

		$arrTable = "`hs_hr_leave`";

		$selectConditions[1] = "`employee_id` = '".$employeeId."'";
		$selectConditions[2] = "`leave_date` >= '".date('Y')."-01-01'";

		$query = $sqlBuilder->simpleSelect($arrTable, $arrFields, $selectConditions, $arrFields[0], 'ASC');

		$dbConnection = new DMLFunctions();

		$result = $dbConnection -> executeQuery($query);

		$leaveArr = $this->_buildObjArr($result);

		return $leaveArr;
	}

	/**
	 * Add Leave record to for a employee.
	 *
	 * @access public
	 * @return void
	 */
	public function applyLeave() {
		$this->_addLeave();
	}

	public function cancelLeave($id = null) {
		if (isset($id)) {
			$this->setLeaveId($id);
		}
		$this->setLeaveStatus($this->statusLeaveCancelled);
		return $this->_changeLeaveStatus();
	}

	public function changeLeaveStatus($id = null) {
		if (isset($id)) {
			$this->setLeaveId($id);
		}
		return $this->_changeLeaveStatus();
	}

	/**
	 *	Counts Leaves taken of particular Leave type
	 *
	 * 	@return int
	 *	@param String LeaveTypeId, [int status]
	 *
	 */
	public function countLeave($leaveTypeId, $year=null, $status=null) {
		if ($year == null) {
			$year = date('Y');
		}

		if ($status == null) {
			$status = $this->statusLeaveTaken;
		}

		$totalLeaveLength = 0;

		$leaveLengths = array($this->lengthFullDay, $this->lengthHalfDayAfternoon, $this->lengthHalfDayMorning);

		$sqlBuilder = new SQLQBuilder();

		$arrFields[0] = 'SUM(ABS(`leave_length`))';

		$arrTable = "`hs_hr_leave`";

		$selectConditions[1] = "`employee_id` = '".$this->getEmployeeId()."'";
		$selectConditions[2] = "`leave_status` = ".$status;
		$selectConditions[3] = "`leave_type_id` = '".$leaveTypeId."'";
		$selectConditions[4] = "`leave_date` BETWEEN DATE('".$year."-01-01') AND DATE('".$year."-12-31')";

		$query = $sqlBuilder->simpleSelect($arrTable, $arrFields, $selectConditions);

		//echo $query;

		$dbConnection = new DMLFunctions();

		$result = $dbConnection->executeQuery($query);

		$count = mysql_fetch_row($result);

		$totalLeaveLength = $count[0];
		/*
		foreach ($leaveLengths as $length) {
			$selectConditions[5] = "`leave_length` = '".$length."'";

			$query = $sqlBuilder->simpleSelect($arrTable, $arrFields, $selectConditions);

			//echo $query;

			$dbConnection = new DMLFunctions();

			$result = $dbConnection->executeQuery($query);

			$count = mysql_fetch_row($result);

			if ($length < 0) {
				$length *= -1;
			}

			$totalLeaveLength += $count[0]*$length;
		}		*/

		return ($totalLeaveLength/$this->lengthFullDay);
	}


	/**
	 * Adds Leave
	 *
	 * @access protected
	 */
	protected function _addLeave() {

		$this->_getNewLeaveId();
		$this->_getLeaveTypeName();
		$this->setDateApplied(date('Y-m-d'));

		$arrRecordsList[0] = $this->getLeaveId();
		$arrRecordsList[1] = "'". $this->getLeaveDate()."'";
		$arrRecordsList[2] = "'". $this->getLeaveLength()."'";
		$arrRecordsList[3] = $this->statusLeavePendingApproval;
		$arrRecordsList[4] = "'".$this->getLeaveComments()."'";
		$arrRecordsList[5] = "'". $this->getLeaveRequestId(). "'";
		$arrRecordsList[6] = "'".$this->getLeaveTypeId()."'";
		$arrRecordsList[7] = "'". $this->getEmployeeId() . "'";

		$arrTable = "`hs_hr_leave`";

		$sqlBuilder = new SQLQBuilder();

		//print_r($arrRecordsList);

		$query = $sqlBuilder->simpleInsert($arrTable, $arrRecordsList);

		//echo  $query;

		$dbConnection = new DMLFunctions();

		$result = $dbConnection -> executeQuery($query);
	}

	/**
	 *
	 * function _getNewLeaveId, access is private, will not be documented
	 *
	 * @access private
	 *
	 **/

	private function _getNewLeaveId() {

		$sql_builder = new SQLQBuilder();

		$selectTable = "`hs_hr_leave`";
		$selectFields[0] = '`leave_id`';
		$selectOrder = "DESC";
		$selectLimit = 1;
		$sortingField = '`leave_id`';

		$query = $sql_builder->simpleSelect($selectTable, $selectFields, null, $sortingField, $selectOrder, $selectLimit);
		//echo $query;
		$dbConnection = new DMLFunctions();

		$result = $dbConnection -> executeQuery($query);

		$row = mysql_fetch_row($result);

		$this->setLeaveId($row[0]+1);
	}

	/**
	 *
	 * function _changeLeaveStatus, access is private, will not be documented
	 *
	 * @access private
	 */
	protected function _changeLeaveStatus() {

		$sqlBuilder = new SQLQBuilder();

		$table = "`hs_hr_leave`";

		$changeFields[0] = "`leave_status`";
		$changeFields[1] = "`leave_comments`";

		$changeValues[0] = $this->getLeaveStatus();
		$changeValues[1] = "'".$this->getLeaveComments()."'";

		//print_r($changeValues);
		$updateConditions[0] = "`leave_id` = ".$this->getLeaveId();

		$query = $sqlBuilder->simpleUpdate($table, $changeFields, $changeValues, $updateConditions);

		//echo $query."\n";

		$dbConnection = new DMLFunctions();

		$result = $dbConnection->executeQuery($query);

		if (isset($result) && (mysql_affected_rows() > 0)) {
			return true;
		};

		return false;
	}

	/**
	 *
	 * function _getLeaveTypeName, access is private, will not be documented
	 *
	 * @access private
	 *
	 */
	protected function _getLeaveTypeName() {

		$sqlBuilder = new SQLQBuilder();
		$leave_Type  = new LeaveType();

		$selectTable = "`hs_hr_leavetype`";
		$selectFields[0] = '`leave_type_name`';
    	$updateConditions[1] = "`leave_type_id` = '".$this->getLeaveTypeId()."'";

    	$query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $updateConditions, null, null, null);
		//echo $query;
		$dbConnection = new DMLFunctions();

		$result = $dbConnection -> executeQuery($query);

		$row = mysql_fetch_row($result);

		$this->setLeaveTypeName($row[0]);
	}

	/**
	 * Calculates the time off for a particular date
	 *
	 * @param String $date
	 * @return integer $timeOff
	 */
	protected function _timeOffLength($date) {
		$timeOff = 0;
		if (isset($this->weekends[date('N', strtotime($date))-1])) {
			$timeOff = $this->weekends[date('N', strtotime($date))-1]->getLength();
		}

		if ($timeOff != Weekends::WEEKENDS_LENGTH_WEEKEND) {
			$holidaysObj = new Holidays();

			$length = $holidaysObj->isHoliday($date);

			if ($length > $timeOff) {
				return $length;
			}
		}

		return $timeOff;
	}

	/**
	 * Calculates required length of leave.
	 *
	 * @param integer $length - leave lenth
	 * @param integer $timeOff - time off for that day
	 * @return integer $reqiredLength - length of leave required.
	 */
	protected function _leaveLength($length, $timeOff) {
		$factor = 1;
		if ($length < 0) {
			$factor = -1;
		}

		$length = abs($length);
		if ($timeOff > $length) {
			return 0;
		}
		$requiredLength = $length-$timeOff;

		return $requiredLength*$factor;
	}

	/**
	 *
	 * function _buildObjArr, access is private, will not be documented
	 *
	 * @access protected
	 */
	protected function _buildObjArr($result, $supervisor=false) {

		$objArr = null;

		while ($row = mysql_fetch_assoc($result)) {

			$tmpLeaveArr = new Leave();

			$tmpLeaveArr->setLeaveDate($row['leave_date']);
			$tmpLeaveArr->setLeaveStatus($row['leave_status']);

			if ($row['leave_status'] == self::LEAVE_STATUS_LEAVE_TAKEN) {
				$leaveLength = $row['leave_length'];
			} else {
				$leaveLength = $this->_leaveLength($row['leave_length'], $this->_timeOffLength($row['leave_date']));
			}

			$tmpLeaveArr->setLeaveLength($leaveLength);
			$tmpLeaveArr->setLeaveComments($row['leave_comments']);
			$tmpLeaveArr->setLeaveId($row['leave_id']);

			if (isset($row['leave_type_name'])) {
				$tmpLeaveArr->setLeaveTypeName($row['leave_type_name']);
			}

			if (isset($row['leave_request_id'])) {
				$tmpLeaveArr->setLeaveRequestId($row['leave_request_id']);
			}

			if ($supervisor || isset($row['employee_id'])) {
				$tmpLeaveArr->setEmployeeName($row['emp_firstname']);
				$tmpLeaveArr->setEmployeeId($row['employee_id']);
			}

			$objArr[] = $tmpLeaveArr;
		}

		return $objArr;
	}

	/**
	 * Retrieve the years where there are any leave records
	 * returns at least current year
	 *
	 * @return String[]
	 * @access public
	 */
	public function getLeaveYears() {

		$sqlBuilder = new SQLQBuilder();

		$selectTable = "`hs_hr_leave`";

		$selectFields[] = "DISTINCT YEAR(`leave_date`) ";

		$selectConditions[] = "`leave_date` < '".date('Y')."-01-01'";

		$selectOrder = "ASC";

		$selectOrderBy = "`leave_date`";

		$query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $selectConditions, $selectOrderBy, $selectOrder, 1);

		$dbConnection = new DMLFunctions();

		$result = $dbConnection -> executeQuery($query);

		if ($row = mysql_fetch_row($result)) {
			$firstYears = $row[0];

			for ($i=$firstYears; $i<date('Y'); $i++) {
				$years[] = $i;
			}
		}

		$years[] = date('Y');

		$years[] = date('Y')+1;

		$years = array_unique($years);

		rsort($years);

		return $years;

	}

	/**
	 * Changes the leave status to taken if the date is before
	 * or on today
	 *
	 * @access public
	 */
	 public function takeLeave() {

		$sqlBuilder = new SQLQBuilder();

		$selectFields[0] = '`leave_date`';
		$selectFields[1] = '`leave_status`';
		$selectFields[2] = '`leave_length`';
		$selectFields[3] = '`leave_comments`';
		$selectFields[4] = '`leave_id`';

		$selectTable = '`hs_hr_leave`';

		$selectConditions[] = "`leave_status` = ".$this->statusLeaveApproved;
		$selectConditions[] = "`leave_date` <= NOW()";

		$query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $selectConditions);

		$dbConnection = new DMLFunctions();

		$result = $dbConnection -> executeQuery($query);

		if (isset($result) && !empty($result)) {
			if (mysql_num_rows($result) > 0) {

				$leaveObjs = $this->_buildObjArr($result);

				foreach ($leaveObjs as $leaveObj) {
					$leaveObj->setLeaveStatus(self::LEAVE_STATUS_LEAVE_TAKEN);
					$leaveObj->changeLeaveToTaken();
				}

				return true;
			}
		}

		return false;
	 }

	 /**
	  * This is the workhorse function for takeLeave() function.
	  * This needs to be publicly accessible, still this is expected
	  * to be called from takeLeave()
	  *
	  * @return boolean
	  */
	 public function changeLeaveToTaken() {
	 	$sqlBuilder = new SQLQBuilder();

		$table = "`hs_hr_leave`";

		$changeFields[0] = "`leave_status`";
		$changeFields[1] = "`leave_length`";

		$changeValues[0] = $this->getLeaveStatus();
		$changeValues[1] = "'".$this->getLeaveLength()."'";

		$updateConditions[0] = "`leave_id` = ".$this->getLeaveId();

		$query = $sqlBuilder->simpleUpdate($table, $changeFields, $changeValues, $updateConditions);

		//echo $query."\n";

		$dbConnection = new DMLFunctions();

		$result = $dbConnection->executeQuery($query);

		if (isset($result) && (mysql_affected_rows() > 0)) {
			return true;
		};

		return false;
	 }

}

?>