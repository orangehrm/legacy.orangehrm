<?php


/*
// OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
// all the essential functionalities required for any enterprise.
// Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com

// OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
// the GNU General Public License as published by the Free Software Foundation; either
// version 2 of the License, or (at your option) any later version.

// OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
// without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU General Public License for more details.

// You should have received a copy of the GNU General Public License along with this program;
// if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
// Boston, MA  02110-1301, USA
*/

require_once ROOT_PATH . '/lib/confs/Conf.php';
require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';
require_once ROOT_PATH . '/lib/common/LocaleUtil.php';
require_once ROOT_PATH . '/lib/common/UniqueIDGenerator.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpLocation.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpInfo.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpRepTo.php';

/**
 * Class representing a Training Request
 */
class Training {

	const TABLE_NAME = 'hs_hr_training';
	const TRAINING_EMP_TABLE_NAME = 'hs_hr_training_employee';

	/** Database fields */
	const DB_FIELD_ID = 'id';
	const DB_FIELD_USER_DEFINED_ID = 'user_defined_id';
	const DB_FIELD_DESCRIPTION = 'description';
	const DB_FIELD_STATE = 'state';
	const DB_FIELD_TRAINING_COURSE = 'training_course';
	const DB_FIELD_COST = 'cost';
	const DB_FIELD_COMPANY = 'company';
	const DB_FIELD_NOTES = 'notes';

	const DB_FIELD_TRAINING_ID = 'training_id';
	const DB_FIELD_EMP_NUMBER = 'emp_number';

	/** Fields retrieved from other tables */
	const FIELD_EMP_NAME = 'employee_name';

	/** STATE constants */
	const STATE_REQUESTED = 0;
	const STATE_ARRANGED = 1;
	const STATE_COMPLETED = 2;

	/** Field order */
	const SORT_FIELD_NONE = -1;
	const SORT_FIELD_USER_DEFINED_ID = 0;
	const SORT_FIELD_DESCRIPTION = 1;
	const SORT_FIELD_STATE = 2;

	private $dbFields = array (
		self :: DB_FIELD_ID,
		self :: DB_FIELD_USER_DEFINED_ID,
		self :: DB_FIELD_DESCRIPTION,
		self :: DB_FIELD_STATE,
		self :: DB_FIELD_TRAINING_COURSE,
		self :: DB_FIELD_COST,
		self :: DB_FIELD_COMPANY,
		self :: DB_FIELD_NOTES,		
	);

	private $id;
	private $userDefinedId;
	private $description;
	private $state;
	private $trainingCourse;
	private $cost;
	private $company;
	private $notes;

	private $employees;

	/**
	 * Constructor
	 *
	 * @param int $id ID can be null for newly created trainings.
	 */
	public function __construct($id = null) {
		$this->id = $id;
		$this->employees = array ();
	}

	/**
	 * Retrieves the value of id.
	 * @return id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Sets the value of id.
	 * @param id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * Retrieves the value of userDefinedId.
	 * @return userDefinedId
	 */
	public function getUserDefinedId() {
		return $this->userDefinedId;
	}

	/**
	 * Sets the value of userDefinedId.
	 * @param userDefinedId
	 */
	public function setUserDefinedId($userDefinedId) {
		$this->userDefinedId = $userDefinedId;
	}

	/**
	 * Retrieves the value of description.
	 * @return description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the value of description.
	 * @param description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Retrieves the value of state.
	 * @return state
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * Sets the value of state.
	 * @param state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * Retrieves the value of trainingCourse.
	 * @return trainingCourse
	 */
	public function getTrainingCourse() {
		return $this->trainingCourse;
	}

	/**
	 * Sets the value of trainingCourse.
	 * @param trainingCourse
	 */
	public function setTrainingCourse($trainingCourse) {
		$this->trainingCourse = $trainingCourse;
	}

	/**
	 * Retrieves the value of cost.
	 * @return cost
	 */
	public function getCost() {
		return $this->cost;
	}

	/**
	 * Sets the value of cost.
	 * @param cost
	 */
	public function setCost($cost) {
		$this->cost = $cost;
	}

	/**
	 * Retrieves the value of company.
	 * @return company
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * Sets the value of company.
	 * @param company
	 */
	public function setCompany($company) {
		$this->company = $company;
	}

	/**
	 * Retrieves the value of notes.
	 * @return notes
	 */
	public function getNotes() {
		return $this->notes;
	}

	/**
	 * Sets the value of notes.
	 * @param notes
	 */
	public function setNotes($notes) {
		$this->notes = $notes;
	}

	/**
	 * Retrieves the value of employees.
	 * @return employees
	 */
	public function getEmployees() {
		return $this->employees;
	}

	/**
	 * Sets the value of employees.
	 * @param employees
	 */
	public function setEmployees($employees) {
		$this->employees = $employees;
	}

	/**
	 * Save Training object to database
	 *
	 * If a new Training, inserts into the database, otherwise, updates
	 * the existing entry.
	 *
	 * @return int Returns the ID of the Training
	 */
	public function save() {

		if (isset ($this->id)) {

			if (!CommonFunctions :: isValidId($this->id)) {
				throw new TrainingException("Invalid id", TrainingException :: INVALID_PARAMETER);
			}
			return $this->_update();
		} else {
			return $this->_insert();
		}
	}

	/**
	 * Get Training Request with given id
	 *
	 * @param int $id Training Request ID
	 * @return Training Training object
	 */
	public static function getTraining($id) {

		if (!CommonFunctions :: isValidId($id)) {
			throw new TrainingException("Invalid id", TrainingException :: INVALID_PARAMETER);
		}

		$conditions[] = self :: DB_FIELD_ID . ' = ' . $id;
		$list = self :: _getList($conditions);
		$training = (count($list) == 1) ? $list[0] : null;

		if (!empty ($training)) {
			$training->setEmployees(self :: getAssignedEmployees($id));
		}

		return $training;
	}

	/**
	 * Get list of employee injuries
	 * @return Array Array of Training objects.
	 */
	public static function getAll() {
		return self :: _getList();
	}

	/**
	 * Get list of Training Requests in a format suitable for view.php
	 *
	 * @param int $pageNo The page number. 0 to fetch all
	 * @param string $searchStr The search string
	 * @param int $searchfieldNo which field to search on
	 * @param int $sortField The field to sort by
	 * @param string $sortOrder Sort Order (one of ASC or DESC)
	 */
	public static function getListForView($pageNO = 0, $searchStr = '', $searchFieldNo = self :: SORT_FIELD_NONE, $sortField = self :: SORT_FIELD_USER_DEFINED_ID, $sortOrder = 'ASC', $supervisorEmpNum = null) {

		$selectCondition = null;
		$dbConnection = new DMLFunctions();

		$condition = self :: _getSelectCondition($searchFieldNo, $searchStr);
		if (!empty ($condition)) {
			$selectCondition[] = $condition;
		}

		$sysConst = new sysConf();
		$limit = null;
		if ($pageNO > 0) {
			$pageNO--;
			$pageNO *= $sysConst->itemsPerPage;
			$limit = "{$pageNO}, {$sysConst->itemsPerPage}";
		}

		$sortBy = null;

		switch ($sortField) {

			case self :: SORT_FIELD_USER_DEFINED_ID :
				$sortBy = self :: DB_FIELD_USER_DEFINED_ID;
				break;
			case self :: SORT_FIELD_DESCRIPTION :
				$sortBy = self :: DB_FIELD_DESCRIPTION;
				break;
			case self :: SORT_FIELD_STATE :
				$sortBy = self :: DB_FIELD_STATE;
				break;

		}

		$list = self :: _getList($selectCondition, $sortBy, $sortOrder, $limit, $supervisorEmpNum);

		$i = 0;
		$arrayDispList = null;

		foreach ($list as $training) {					
			$arrayDispList[$i][0] = $training->getId();
			$arrayDispList[$i][1] = $training->getUserDefinedId();
			$arrayDispList[$i][2] = $training->getDescription();
			$arrayDispList[$i][3] = $training->getState();
			$i++;			
		}

		return $arrayDispList;
	}
	
	/**
	 * Get suggested id
	 */
	public static function getSuggestedId() {
		$suggestedId = UniqueIDGenerator :: getInstance()->getLastID(self :: TABLE_NAME, self :: DB_FIELD_ID) + 1;
		
		// Double check if this id is in use.
		$count = Training::getCount($suggestedId, self::SORT_FIELD_USER_DEFINED_ID);
		while ($count > 0) {
			$suggestedId++;
			$count = Training::getCount($suggestedId, self::SORT_FIELD_USER_DEFINED_ID);
		}
		
		return $suggestedId;
	}
	
	/**
	 * Count Training Requests with given search conditions
	 * 
	 * 
	 * @param string $searchStr Search string
	 * @param string $searchFieldNo Integer giving which field to search on
	 */
	public static function getCount($searchStr = '', $searchFieldNo = self :: SORT_FIELD_NONE, $supervisorEmpNum = null) {

		$selectCondition = null;
		$dbConnection = new DMLFunctions();

		$condition = self :: _getSelectCondition($searchFieldNo, $searchStr);
		if (!empty ($condition)) {
			$selectCondition[] = $condition;
		}

		$subordinateIds = array();
		if (!empty($supervisorEmpNum)) {
			$repObj = new EmpRepTo();
			$subordinates = $repObj->getEmpSubDetails($supervisorEmpNum);

			foreach ($subordinates as $subordinate) {
				$subordinateIds[] = $subordinate[0];
			}
		}
		
		$count = 0;
		$sql = sprintf('SELECT count(*) FROM '. self :: TABLE_NAME);
			
		$whereNeeded = true;
		
		if (!empty($supervisorEmpNum)) {
			
			$empIdList =  implode(',', $subordinateIds);			
			$filterEmp = sprintf(', %s WHERE %s = %s AND %s IN (%s) ', 
				self::TRAINING_EMP_TABLE_NAME, self::DB_FIELD_TRAINING_ID, self::DB_FIELD_ID, self::DB_FIELD_EMP_NUMBER, $empIdList);
			$sql .= $filterEmp;
			
			// Where is no longer needed since already added here.
			$whereNeeded = false;						
		}

		if (!empty ($selectCondition)) {

			$whereClause = '';
			
			foreach ($selectCondition as $condition) {

				// Add a 'AND' if not the first clause or if already added clause beforehand (for supervisor)				
				if (!empty($whereClause) || !$whereNeeded) {
					$whereClause .= ' AND ';
				} 
				$whereClause .= ' ( ' . $condition . ' )';
			}
			
			if ($whereNeeded) {
				$sql .= ' WHERE ';
			}		
			$sql .= $whereClause;
		}
		$sqlBuilder = new SQLQBuilder();
		$result = $dbConnection->executeQuery($sql);

		if ($result) {
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$count = $line[0];
		}

		return $count;
	}

	/**
	 * Delete given Training Requests
	 * @param array $ids Array of Training Request ID's to delete
	 */
	public static function delete($ids) {

		$count = 0;
		if (!is_array($ids)) {
			throw new TrainingException("Invalid parameter to delete(): ids should be an array", TrainingException :: INVALID_PARAMETER);
		}

		foreach ($ids as $id) {
			if (!CommonFunctions :: isValidId($id)) {
				throw new TrainingException("Invalid parameter to delete(): id = $id", TrainingException :: INVALID_PARAMETER);
			}
		}

		if (!empty ($ids)) {

			$sql = sprintf("DELETE FROM %s WHERE %s IN (%s)", self :: TABLE_NAME, self :: DB_FIELD_ID, implode(",", $ids));

			$conn = new DMLFunctions();
			$result = $conn->executeQuery($sql);
			if ($result) {
				$count = mysql_affected_rows();
			}
		}
		return $count;
	}
	/**
	 * Insert new object to database
	 */
	private function _insert() {

		$this->id = UniqueIDGenerator :: getInstance()->getNextID(self :: TABLE_NAME, self :: DB_FIELD_ID);

		$sqlBuilder = new SQLQBuilder();
		$sqlBuilder->table_name = self :: TABLE_NAME;
		$sqlBuilder->flg_insert = 'true';
		$sqlBuilder->arr_insert = $this->_getFieldValuesAsArray();
		$sqlBuilder->arr_insertfield = $this->dbFields;
		$sql = $sqlBuilder->addNewRecordFeature2();

		$conn = new DMLFunctions();

		$result = $conn->executeQuery($sql);
		if (!$result || (mysql_affected_rows() != 1)) {
			throw new TrainingException("Insert failed. ", TrainingException :: DB_ERROR);
		}
		$this->_assignEmployees();
		return $this->id;
	}

	/**
	 * Update existing object
	 */
	private function _update() {

		$values = $this->_getFieldValuesAsArray();
		$sqlBuilder = new SQLQBuilder();
		$sqlBuilder->table_name = self :: TABLE_NAME;
		$sqlBuilder->flg_update = 'true';
		$sqlBuilder->arr_update = $this->dbFields;
		$sqlBuilder->arr_updateRecList = $this->_getFieldValuesAsArray();

		$sql = $sqlBuilder->addUpdateRecord1(0);

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		// Here we don't check mysql_affected_rows because update may be called
		// without any changes.
		if (!$result) {
			throw new TrainingException("Update failed. SQL=$sql", TrainingException :: DB_ERROR);
		}
		$this->_assignEmployees();
		return $this->id;
	}

	/**
	 * Save employees assigned to this training
	 */
	private function _assignEmployees() {

		// Delete existing job title assignments		
		$sql = sprintf("DELETE FROM %s WHERE %s = %s", self :: TRAINING_EMP_TABLE_NAME, self :: DB_FIELD_TRAINING_ID, $this->id);
		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		// Assign new employees
		if (!empty ($this->employees)) {
			$sql = sprintf("INSERT INTO %s (%s, %s) VALUES ", self :: TRAINING_EMP_TABLE_NAME, self :: DB_FIELD_TRAINING_ID, self :: DB_FIELD_EMP_NUMBER);

			$valueSql = "";
			foreach ($this->employees as $emp) {
				$empNumber = $emp['emp_number'];

				if (!empty ($valueSql)) {
					$valueSql .= ', ';
				}
				$valueSql .= sprintf("(%d, '%s')", $this->id, $empNumber);
			}

			$sql .= $valueSql;
			$result = $conn->executeQuery($sql);
			if (!$result) {
				throw new TrainingException("Save employees saved. SQL=$sql", TrainingException :: DB_ERROR);
			}
		}
	}

	/**
	 * Get a list of Training Requests with the given conditions.
	 *
	 * @param array  $selectCondition Array of select conditions to use.
	 * @return array Array of Training objects. Returns an empty (length zero) array if none found.
	 */
	private static function _getList($selectCondition = null, $sortBy = null, $sortOrder = null, $limit = null, $supervisorEmpNum = null) {
		
		$fields[0] = self :: DB_FIELD_ID;
		$fields[1] = self :: DB_FIELD_USER_DEFINED_ID;
		$fields[2] = self :: DB_FIELD_DESCRIPTION;
		$fields[3] = self :: DB_FIELD_STATE;
		$fields[4] = self :: DB_FIELD_TRAINING_COURSE;
		$fields[5] = self :: DB_FIELD_COST;
		$fields[6] = self :: DB_FIELD_COMPANY;
		$fields[7] = self :: DB_FIELD_NOTES;

		$sqlBuilder = new SQLQBuilder();
		
		if (empty($supervisorEmpNum)) {
			$sql = $sqlBuilder->simpleSelect(self :: TABLE_NAME, $fields, $selectCondition, $sortBy, $sortOrder, $limit);
		} else {
			
			$subordinateIds = array();
			if (!empty($supervisorEmpNum)) {
				$repObj = new EmpRepTo();
				$subordinates = $repObj->getEmpSubDetails($supervisorEmpNum);
	
				foreach ($subordinates as $subordinate) {
					$subordinateIds[] = $subordinate[0];
				}
			}
					
			$tables[0] = self :: TABLE_NAME . ' a';
			$tables[1] = self::TRAINING_EMP_TABLE_NAME . ' b';
										
			$joinConditions[1] = 'a.' . self :: DB_FIELD_ID . ' = b.' . self :: DB_FIELD_TRAINING_ID;

			$selectCondition[] = ' b.' . self::DB_FIELD_EMP_NUMBER . ' IN (' . implode(',', $subordinateIds) . ') ';
						
			$groupBy = 'a.' . self :: DB_FIELD_ID;	
					
			$sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition, null, $sortBy, $sortOrder, $limit, $groupBy);															
		}	
			
		$trainingList = array ();

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		while ($result && ($row = mysql_fetch_assoc($result))) {
			$trainingList[] = self :: _createFromRow($row);
		}

		return $trainingList;
	}

	/**
	 * Get list of employees assigned to this Training
	 */
	public static function getAssignedEmployees($trainingId) {

		$fields[0] = "a." . self :: DB_FIELD_TRAINING_ID;
		$fields[1] = "a." . self :: DB_FIELD_EMP_NUMBER;
		$fields[2] = "CONCAT(b.`emp_firstname`, ' ', b.`emp_lastname`) AS " . self :: FIELD_EMP_NAME;

		$tables[0] = self :: TRAINING_EMP_TABLE_NAME . ' a';
		$tables[1] = 'hs_hr_employee b';

		$joinConditions[1] = 'a.' . self :: DB_FIELD_EMP_NUMBER . ' = b.emp_number';

		$selectCondition[] = "a." . self :: DB_FIELD_TRAINING_ID . " = " . $trainingId;

		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition);

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		return self::_getEmployeesFromResults($result);
	}

	/**
	 * Return list of employees who are not assigned to this training
	 * @return array Array of employees
	 */
	public static function getUnAssignedEmployees($trainingId = null, $supervisorEmpNum = null) {

		$fields[0] = self :: DB_FIELD_EMP_NUMBER;
		$fields[1] = "CONCAT(`emp_firstname`, ' ', `emp_lastname`) AS " . self :: FIELD_EMP_NAME;

		$sqlBuilder = new SQLQBuilder();

		if (!empty($trainingId)) {
			$sqlBuilder->table_name = EmpInfo::EMPLOYEE_TABLE_NAME;
			$sqlBuilder->flg_select = 'true';
			$sqlBuilder->arr_select = $fields;
			$sqlBuilder->field = EmpInfo::EMPLOYEE_FIELD_EMP_NUMBER;
			$sqlBuilder->field2 = self::DB_FIELD_EMP_NUMBER;
			$sqlBuilder->table2_name = self::TRAINING_EMP_TABLE_NAME;
	
			$joinConditions[] = array(self::DB_FIELD_TRAINING_ID, $trainingId);
	
			$sql = $sqlBuilder->selectFilter($joinConditions);
		} else {
			$selectConditions[] = "(emp_status != 'EST000' OR emp_status IS NULL)";  
			$sql = $sqlBuilder->simpleSelect(EmpInfo::EMPLOYEE_TABLE_NAME, $fields, $selectConditions);						
		}
		
		if (!empty($supervisorEmpNum)) {
			$repObj = new EmpRepTo();
			$subordinates = $repObj->getEmpSubDetails($supervisorEmpNum);

			foreach ($subordinates as $subordinate) {
				$subordinateIds[] = $subordinate[0];
			}
			
			if (!empty($subordinateIds)) {
				$outerQuery = " emp_number IN (" . implode(',', $subordinateIds). ") ";
				
				$sql = "SELECT * FROM ({$sql}) sub WHERE {$outerQuery}";
			}			
		}			

		$connection = new DMLFunctions();
		$result = $connection->executeQuery($sql);
		if ($result === false) {
			throw new WorkshiftException("Error in db query:" . $sql, WorkshiftException::ERROR_IN_DB_QUERY);
		}

		return self::_getEmployeesFromResults($result);

	}
	
	/**
	 * Get employees from result
	 */
	private static function _getEmployeesFromResults($result) {
		$employees = array ();
				
		while ($result && ($row = mysql_fetch_assoc($result))) {
			$employees[] = array (
				'emp_number' => $row[self :: DB_FIELD_EMP_NUMBER],
				'emp_name' => $row[self :: FIELD_EMP_NAME]
			);
		}

		return $employees;		
	}
	
	/**
	 * Returns the db field values as an array
	 *
	 * @return Array Array containing field values in correct order.
	 */
	private function _getFieldValuesAsArray() {

		$values[0] = $this->id;
		$values[1] = $this->userDefinedId;
		$values[2] = $this->description;
		$values[3] = is_null($this->state) ? self :: STATE_REQUESTED : $this->state;
		$values[4] = $this->trainingCourse;
		$values[6] = $this->cost;
		$values[7] = $this->company;
		$values[8] = $this->notes;
		return $values;
	}

	/**
	 * Creates a Training object from a resultset row
	 *
	 * @param array $row Resultset row from the database.
	 * @return Training Training object.
	 */
	private static function _createFromRow($row) {

		$training = new Training($row[self :: DB_FIELD_ID]);
		$training->setUserDefinedId($row[self :: DB_FIELD_USER_DEFINED_ID]);
		$training->setDescription($row[self :: DB_FIELD_DESCRIPTION]);
		$training->setState($row[self :: DB_FIELD_STATE]);
		$training->setTrainingCourse($row[self :: DB_FIELD_TRAINING_COURSE]);
		$training->setCost($row[self :: DB_FIELD_COST]);
		$training->setCompany($row[self :: DB_FIELD_COMPANY]);
		$training->setNotes($row[self :: DB_FIELD_NOTES]);
		return $training;
	}

	/**
	 * Get select condition for given field and search string
	 */
	private static function _getSelectCondition($searchField, $searchStr) {

		$selectCondition = null;
		if (!is_null($searchStr) && ($searchStr !== '')) {
			$escapedVal = mysql_real_escape_string($searchStr);

			switch ($searchField) {

				case self :: SORT_FIELD_USER_DEFINED_ID :
					$selectCondition = self :: DB_FIELD_USER_DEFINED_ID . " = '{$escapedVal}' ";
					break;

				case self :: SORT_FIELD_DESCRIPTION :
					$selectCondition = self :: DB_FIELD_DESCRIPTION . " LIKE '%{$escapedVal}%' ";
					break;

				case self :: SORT_FIELD_STATE :
					$selectCondition = self :: DB_FIELD_STATE . " = '{$escapedVal}' ";
					break;
			}
			return $selectCondition;
		}
	}
}

class TrainingException extends Exception {
	const INVALID_PARAMETER = 0;
	const MISSING_PARAMETERS = 1;
	const DB_ERROR = 2;
	const INVALID_STATUS = 3;
}
?>

