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

require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';

class LeaveQuota {

	/**
	 * Class constants
	 */
	const LEAVEQUOTA_DB_TABLE_EMPLOYEE_LEAVE_QUOTA = "hs_hr_employee_leave_quota";

	const LEAVEQUOTA_DB_FIELD_YEAR = "year";
	const LEAVEQUOTA_DB_FIELD_LEAVE_TYPE_ID = "leave_type_id";
	const LEAVEQUOTA_DB_FIELD_EMPLOYEE_ID = "employee_id";
	const LEAVEQUOTA_DB_FIELD_NO_OF_DAYS_ALLOTED = "no_of_days_allotted";

	const LEAVEQUOTA_CRITERIA_ALL = 0;

	/**
	 *	Class atributes
	 */
	private $year;
	private $leaveTypeId;
	private $employeeId;
	private $noOfDaysAllotted;
	private $leaveTypeName;

	/**
	 *
	 *	Class contructor
	 */
	public function __construct() {
		//nothing to do
	}

	/**
	 *	Getter method followed by setter method for each
	 *	attribute
	 */
	public function getYear() {
		return $this->year;
	}

	public function setYear($year) {
		$this->year = $year;
	}

	public function getLeaveTypeId() {
		return $this->leaveTypeId;
	}

	public function setLeaveTypeId($leaveTypeId) {
		$this->leaveTypeId = $leaveTypeId;
	}

	public function getEmployeeId() {
		return $this->employeeId;
	}

	public function setEmployeeId($employeeId) {
		$this->employeeId = $employeeId;
	}

	public function getNoOfDaysAllotted() {
		return $this->noOfDaysAllotted;
	}

	public function setNoOfDaysAllotted($noOfDaysAlotted) {
		$this->noOfDaysAllotted = $noOfDaysAlotted;
	}

	public function getLeaveTypeName() {
		return $this->leaveTypeName;
	}

	public function setLeaveTypeName($leaveTypeName) {
		$this->leaveTypeName = $leaveTypeName;
	}

	/**
	 * Copy leave quota between years
	 *
	 * Copy leave quota from $fromYear to $toYear
	 *
	 * @param int $fromYear
	 * @param int $toYear
	 * @return boolean
	 */
	public function copyQuota($fromYear, $toYear) {

		$sqlBuilder = new SQLQBuilder();

		$table = "`".self::LEAVEQUOTA_DB_TABLE_EMPLOYEE_LEAVE_QUOTA."`";

		$insertFields[0] = "`".self::LEAVEQUOTA_DB_FIELD_YEAR."`";
		$insertFields[1] = "`".self::LEAVEQUOTA_DB_FIELD_LEAVE_TYPE_ID."`";
		$insertFields[2] = "`".self::LEAVEQUOTA_DB_FIELD_EMPLOYEE_ID."`";
		$insertFields[3] = "`".self::LEAVEQUOTA_DB_FIELD_NO_OF_DAYS_ALLOTED."`";

		$selectFields[0] = "{$toYear}";
		$selectFields[1] = "`".self::LEAVEQUOTA_DB_FIELD_LEAVE_TYPE_ID."`";
		$selectFields[2] = "`".self::LEAVEQUOTA_DB_FIELD_EMPLOYEE_ID."`";
		$selectFields[3] = "`".self::LEAVEQUOTA_DB_FIELD_NO_OF_DAYS_ALLOTED."`";

		$selectConditions[0] = "`".self::LEAVEQUOTA_DB_FIELD_YEAR."` = '{$fromYear}'";

		$selectQuery = $sqlBuilder->simpleSelect($table, $selectFields, $selectConditions);

		$query = $sqlBuilder->simpleInsert($table, $selectQuery, $insertFields);

		$dbConnection = new DMLFunctions();

		$result = $dbConnection->executeQuery($query);

		if ($result) {
			return true;
		}

		return false;
	}

	/**
	 * Add Leave Quota of an employee
	 *
	 * @param String $employeeId
	 * @return boolean
	 * @access public
	 */
	public function addLeaveQuota($employeeId) {

		$this->setEmployeeId($employeeId);

		$sqlBuilder = new SQLQBuilder();

		$insertTable = "`".self::LEAVEQUOTA_DB_TABLE_EMPLOYEE_LEAVE_QUOTA."`";

		$insertFields[0] = "`".self::LEAVEQUOTA_DB_FIELD_YEAR."`";
		$insertFields[1] = "`".self::LEAVEQUOTA_DB_FIELD_LEAVE_TYPE_ID."`";
		$insertFields[2] = "`".self::LEAVEQUOTA_DB_FIELD_EMPLOYEE_ID."`";
		$insertFields[3] = "`".self::LEAVEQUOTA_DB_FIELD_NO_OF_DAYS_ALLOTED."`";

		$insertValues[0] = "'" . $this->getYear() . "'";
		$insertValues[1] = "'" . $this->getLeaveTypeId() . "'";
		$insertValues[2] = "'" . $this->getEmployeeId() . "'";
		$insertValues[3] = $this->getNoOfDaysAllotted();

		$query = $sqlBuilder->simpleInsert($insertTable, $insertValues, $insertFields);

		$dbConnection = new DMLFunctions();

		$result = $dbConnection->executeQuery($query);

		if ($result) {
			return true;
		}

		return false;
	}

	/**
	 * Edit leave quota of an employee
	 *
	 * @return boolean
	 * @access public
	 */
	public function editLeaveQuota() {
		if ($this->checkRecordExsist()) {
			return $this->updateLeaveQuota();
		}

		return $this->addLeaveQuota($this->getEmployeeId());
	}

	/**
	 * Update leave quota of an employee
	 *
	 * @return boolean
	 * @access public
	 */
	private function updateLeaveQuota() {
		$sqlBuilder = new SQLQBuilder();

		$updateTable = "`".self::LEAVEQUOTA_DB_TABLE_EMPLOYEE_LEAVE_QUOTA."`";

		$updateFileds[0] = "`".self::LEAVEQUOTA_DB_FIELD_NO_OF_DAYS_ALLOTED."`";

		$updateValues[0] = "'" . $this->getNoOfDaysAllotted() . "'";

		$updateConditions[0] = "`".self::LEAVEQUOTA_DB_FIELD_YEAR."` = '".$this->getYear()."'";
		$updateConditions[1] = "`".self::LEAVEQUOTA_DB_FIELD_LEAVE_TYPE_ID."` = '".$this->getLeaveTypeId()."'";
		$updateConditions[2] = "`".self::LEAVEQUOTA_DB_FIELD_EMPLOYEE_ID."` = '".$this->getEmployeeId()."'";

		$query = $sqlBuilder->simpleUpdate($updateTable, $updateFileds, $updateValues, $updateConditions);

		//echo $query."\n";

		$dbConnection = new DMLFunctions();

		$result = $dbConnection->executeQuery($query);

		if ($result) {
			return true;
		}

		return false;
	}

	/**
	 * Checks whether an employee has a quota record
	 * already for particular leave type to decide whether
	 * to add or edit the quota.
	 *
	 * @access private
	 * @return boolean
	 */
	private function checkRecordExsist() {
		$sqlBuilder = new SQLQBuilder();

		$selectTable = "`".self::LEAVEQUOTA_DB_TABLE_EMPLOYEE_LEAVE_QUOTA."`";

		$selectFields[0] = "COUNT(*)";

		$selectConditions[0] = "`".self::LEAVEQUOTA_DB_FIELD_YEAR."` = '".$this->getYear()."'";
		$selectConditions[1] = "`".self::LEAVEQUOTA_DB_FIELD_LEAVE_TYPE_ID."` = '".$this->getLeaveTypeId()."'";
		$selectConditions[2] = "`".self::LEAVEQUOTA_DB_FIELD_EMPLOYEE_ID."` = '".$this->getEmployeeId()."'";

		$query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $selectConditions);

		$dbConnection = new DMLFunctions();

		$result = $dbConnection->executeQuery($query);

		$count = mysql_fetch_row($result);

		if ($count[0] > 0) {
			return true;
		}

		return false;
	}

	/**
	 *	Retrieves Leave Quota Details of all Leave Quota
	 *	available to the employee.
	 *
	 * 	@param String $employeeId
	 * 	@return LeaveQuota[][]
	 * 	@access public
	 */
	public function fetchLeaveQuota($employeeId) {
		$sqlBuilder = new SQLQBuilder();

		$arrFields[0] = "a.`".self::LEAVEQUOTA_DB_FIELD_YEAR."`";
		$arrFields[1] = "a.`".self::LEAVEQUOTA_DB_FIELD_LEAVE_TYPE_ID."`";
		$arrFields[2] = "b.`leave_type_name`";
		$arrFields[3] = "a.`".self::LEAVEQUOTA_DB_FIELD_NO_OF_DAYS_ALLOTED."`";
		$arrFields[4] = "a.`".self::LEAVEQUOTA_DB_FIELD_EMPLOYEE_ID."`";

		$arrTables[0] = "`".self::LEAVEQUOTA_DB_TABLE_EMPLOYEE_LEAVE_QUOTA."` a";
		$arrTables[1] = "`hs_hr_leavetype` b";

		$joinConditions[1] = "a.`".self::LEAVEQUOTA_DB_FIELD_LEAVE_TYPE_ID."` = b.`leave_type_id`";

		$selectConditions = null;

		$selectOrderBy = $arrFields[4];

		if ($this->getYear() != null) {
			$selectConditions[] = "a.`".self::LEAVEQUOTA_DB_FIELD_YEAR."` = '{$this->getYear()}'";
		}

		if ($employeeId != 0) {
			$selectConditions[] = "a.`".self::LEAVEQUOTA_DB_FIELD_EMPLOYEE_ID."` = '" . $employeeId . "'";
			$selectOrderBy = $arrFields[1];
		}

		$selectConditions[] = "a.`".self::LEAVEQUOTA_DB_FIELD_NO_OF_DAYS_ALLOTED."` > 0";

		$joinTypes[1] = "LEFT";

		$selectOrderBy="$arrFields[0], $selectOrderBy";
		$selectOrder = "DESC";

		$query = $sqlBuilder->selectFromMultipleTable($arrFields, $arrTables, $joinConditions, $selectConditions, $joinTypes, $selectOrderBy, $selectOrder);

		$dbConnection = new DMLFunctions();

		$result = $dbConnection->executeQuery($query);

		$leaveTypeArr = $this->_buildObjArr($result);

		return $leaveTypeArr;
	}

	protected function _buildObjArr($result) {

		$objArr = null;

		while ($row = mysql_fetch_row($result)) {

			$tmpLeaveArr = new LeaveQuota();

			$tmpLeaveArr->setYear($row[0]);
			$tmpLeaveArr->setLeaveTypeId($row[1]);
			$tmpLeaveArr->setLeaveTypeName($row[2]);
			$tmpLeaveArr->setNoOfDaysAllotted($row[3]);

			$objArr[] = $tmpLeaveArr;
		}

		return $objArr;
	}
}
?>