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

/**
 * Class representing a Budget
 */
class Budget {

	const TABLE_NAME = 'hs_hr_budgets';

	/** Database fields */
	const DB_FIELD_ID = 'id';
	const DB_FIELD_BUDGET_TYPE = 'budget_type';
	const DB_FIELD_BUDGET_UNIT = 'budget_unit';
	const DB_FIELD_BUDGET_VALUE = 'budget_value';
	const DB_FIELD_START_DATE = 'start_date';
	const DB_FIELD_END_DATE = 'end_date';
	const DB_FIELD_STATUS = 'status';
	const DB_FIELD_NOTES = 'notes';

	/**
	 * Budget Status
	 */
	const STATUS_CREATED = 0;
	const STATUS_SUBMITTED_FOR_APPROVAL = 1;
	const STATUS_NOT_APPROVED = 2;
	const STATUS_APPROVED = 3;
	
	/**
	 * Budget Type
	 */
	 const BUDGET_TYPE_SALARY = 0;
	 const BUDGET_TYPE_TRAINING = 1;
	 const BUDGET_TYPE_EMPLOYEE = 2;
	 const BUDGET_TYPE_COMPANY = 3;

	/** Field order */
	const SORT_FIELD_NONE = -1;
	const SORT_FIELD_ID = 0;
	const SORT_FIELD_BUDGET_TYPE = 1;
	const SORT_FIELD_BUDGET_UNIT = 2;
	const SORT_FIELD_BUDGET_VALUE = 3;
	const SORT_FIELD_START_DATE = 4;
	const SORT_FIELD_END_DATE = 5;
	const SORT_FIELD_STATUS = 6;

	private $dbFields = array (
		self :: DB_FIELD_ID,
		self :: DB_FIELD_BUDGET_TYPE,
		self :: DB_FIELD_BUDGET_UNIT,
		self :: DB_FIELD_BUDGET_VALUE,		
		self :: DB_FIELD_START_DATE,
		self :: DB_FIELD_END_DATE,
		self :: DB_FIELD_STATUS,
		self :: DB_FIELD_NOTES	
	);

	private $id;
	private $budgetType;
	private $budgetUnit;
	private $budgetValue;
	private $startDate;
	private $endDate;
	private $status;
	private $notes;

	/**
	 * Constructor
	 *
	 * @param int $id ID can be null for newly created budgets
	 */
	public function __construct($id = null) {
		$this->id = $id;
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
     * Retrieves the value of budgetType.
     * @return budgetType
     */
    public function getBudgetType() {
        return $this->budgetType;
    }

    /**
     * Sets the value of budgetType.
     * @param budgetType
     */
    public function setBudgetType($budgetType) {
        $this->budgetType = $budgetType;
    }

    /**
     * Retrieves the value of budgetUnit.
     * @return budgetUnit
     */
    public function getBudgetUnit() {
        return $this->budgetUnit;
    }

    /**
     * Sets the value of budgetUnit.
     * @param budgetUnit
     */
    public function setBudgetUnit($budgetUnit) {
        $this->budgetUnit = $budgetUnit;
    }

    /**
     * Retrieves the value of budgetValue.
     * @return budgetValue
     */
    public function getBudgetValue() {
        return $this->budgetValue;
    }

    /**
     * Sets the value of budgetValue.
     * @param budgetValue
     */
    public function setBudgetValue($budgetValue) {
        $this->budgetValue = $budgetValue;
    }

    /**
     * Retrieves the value of startDate.
     * @return startDate
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * Sets the value of startDate.
     * @param startDate
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    /**
     * Retrieves the value of endDate.
     * @return endDate
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * Sets the value of endDate.
     * @param endDate
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    /**
     * Retrieves the value of status.
     * @return status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Sets the value of status.
     * @param status
     */
    public function setStatus($status) {
        $this->status = $status;
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
	 * Save Budget object to database
	 *
	 * If a new Budget, inserts into the database, otherwise, updates
	 * the existing entry.
	 *
	 * @return int Returns the ID of the Budget
	 */
	public function save() {

		if (isset ($this->id)) {

			if (!CommonFunctions :: isValidId($this->id)) {
				throw new BudgetException("Invalid id", BudgetException :: INVALID_PARAMETER);
			}
			return $this->_update();
		} else {
			return $this->_insert();
		}
	}

	/**
	 * Get Budget with given id
	 *
	 * @param int $id Budget ID
	 * @return Budget Budget object
	 */
	public static function getBudget($id) {

		if (!CommonFunctions :: isValidId($id)) {
			throw new BudgetException("Invalid id", BudgetException :: INVALID_PARAMETER);
		}

		$conditions[] = self :: DB_FIELD_ID . ' = ' . $id;
		$list = self :: _getList($conditions);
		$budget = (count($list) == 1) ? $list[0] : null;

		return $budget;
	}

	/**
	 * Get list of budgets
	 * @return Array Array of Budget objects.
	 */
	public static function getAll() {
		return self :: _getList();
	}

	/**
	 * Get list of Budgets in a format suitable for view.php
	 *
	 * @param int $pageNo The page number. 0 to fetch all
	 * @param string $searchStr The search string
	 * @param int $searchfieldNo which field to search on
	 * @param int $sortField The field to sort by
	 * @param string $sortOrder Sort Order (one of ASC or DESC)
	 */
	public static function getListForView($pageNO = 0, $searchStr = '', $searchFieldNo = self :: SORT_FIELD_NONE, $sortField = self :: SORT_FIELD_ID, $sortOrder = 'ASC', $approverMode) {

		$selectCondition = null;
		$dbConnection = new DMLFunctions();

		$condition = self::_getSelectCondition($searchFieldNo, $searchStr);
		if (!empty ($condition)) {
			$selectCondition[] = $condition;
		}
		
		if ($approverMode) {
			$selectCondition[] = self :: DB_FIELD_STATUS . " = " . self::STATUS_SUBMITTED_FOR_APPROVAL . " ";			
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

			case self :: SORT_FIELD_ID :
				$sortBy = self :: DB_FIELD_ID;
				break;

			case self :: SORT_FIELD_BUDGET_TYPE :
				$sortBy = self :: DB_FIELD_BUDGET_TYPE;
				break;

			case self :: SORT_FIELD_BUDGET_UNIT :
				$sortBy = self :: DB_FIELD_BUDGET_UNIT;
				break;

			case self :: SORT_FIELD_BUDGET_VALUE :
				$sortBy = self :: DB_FIELD_BUDGET_VALUE;
				break;

			case self :: SORT_FIELD_START_DATE :
				$sortBy = self :: DB_FIELD_START_DATE;
				break;

			case self :: SORT_FIELD_END_DATE :
				$sortBy = self :: DB_FIELD_END_DATE;
				break;

			case self :: SORT_FIELD_STATUS :
				$sortBy = self :: DB_FIELD_STATUS;
				break;
		}

		$list = self :: _getList($selectCondition, $sortBy, $sortOrder, $limit);

		$i = 0;
		$arrayDispList = null;

		foreach ($list as $budget) {
			$arrayDispList[$i][0] = $budget->getId();
			$arrayDispList[$i][1] = $budget->getBudgetType();
			$arrayDispList[$i][2] = $budget->getBudgetUnit();
			$arrayDispList[$i][3] = $budget->getBudgetValue();			
			$arrayDispList[$i][4] = $budget->getStartDate();
			$arrayDispList[$i][5] = $budget->getEndDate();
			$arrayDispList[$i][6] = $budget->getStatus();
			$i++;
		}

		return $arrayDispList;
	}

	/**
	 * Count Budgets with given search conditions
	 * 
	 * 
	 * @param string $searchStr Search string
	 * @param string $searchFieldNo Integer giving which field to search on
	 */
	public static function getCount($searchStr = '', $searchFieldNo = self :: SORT_FIELD_NONE) {

		$selectCondition = null;
		$dbConnection = new DMLFunctions();

		$condition = self::_getSelectCondition($searchFieldNo, $searchStr);
		if (!empty ($condition)) {
			$selectCondition[] = $condition;
		}

		$count = 0;
		$sql = sprintf('SELECT count(*) FROM '. self :: TABLE_NAME);

		if (!empty ($selectCondition)) {
			$where = '';
			foreach ($selectCondition as $condition) {
				if (!empty($where)) {
					$where .= ' AND ';
				}
				$where .= ' ( ' . $condition . ' )';
			}
			$sql .= ' WHERE ' . $where;
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
	 * Delete given Budgets
	 * @param array $ids Array of Budget ID's to delete
	 */
	public static function delete($ids) {

		$count = 0;
		if (!is_array($ids)) {
			throw new BudgetException("Invalid parameter to delete(): ids should be an array", BudgetException :: INVALID_PARAMETER);
		}

		foreach ($ids as $id) {
			if (!CommonFunctions :: isValidId($id)) {
				throw new BudgetException("Invalid parameter to delete(): id = $id", BudgetException :: INVALID_PARAMETER);
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
			throw new BudgetException("Insert failed. ", BudgetException :: DB_ERROR);
		}

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
			throw new BudgetException("Update failed. SQL=$sql", BudgetException :: DB_ERROR);
		}
		return $this->id;
	}

	/**
	 * Get a list of Budgets with the given conditions.
	 *
	 * @param array  $selectCondition Array of select conditions to use.
	 * @return array Array of Budget objects. Returns an empty (length zero) array if none found.
	 */
	private static function _getList($selectCondition = null, $sortBy = null, $sortOrder = null, $limit = null) {
		
		$fields[0] = self :: DB_FIELD_ID;
		$fields[1] = self :: DB_FIELD_BUDGET_TYPE;
		$fields[2] = self :: DB_FIELD_BUDGET_UNIT;
		$fields[3] = self :: DB_FIELD_BUDGET_VALUE;		
		$fields[4] = self :: DB_FIELD_START_DATE;
		$fields[5] = self :: DB_FIELD_END_DATE;
		$fields[6] = self :: DB_FIELD_STATUS;		
		$fields[7] = self :: DB_FIELD_NOTES;
		
		$table = self :: TABLE_NAME;
		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->simpleSelect($table, $fields, $selectCondition, $sortBy, $sortOrder, $limit);

		$budgetList = array ();

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		while ($result && ($row = mysql_fetch_assoc($result))) {
			$budgetList[] = self :: _createFromRow($row);
		}

		return $budgetList;
	}

	/**
	 * Returns the db field values as an array
	 *
	 * @return Array Array containing field values in correct order.
	 */
	private function _getFieldValuesAsArray() {

		$values[0] = $this->id;
		$values[1] = is_null($this->budgetType) ? 'null' : $this->budgetType;
		$values[2] = is_null($this->budgetUnit) ? 'null' : $this->budgetUnit;
		$values[3] = is_null($this->budgetValue) ? 'null' : $this->budgetValue;				
		$values[4] = is_null($this->startDate) ? 'null' : $this->startDate;
		$values[5] = is_null($this->endDate) ? 'null' : $this->endDate;
		$values[6] = is_null($this->status) ? self :: STATUS_CREATED : $this->status;
		$values[7] = is_null($this->notes) ? 'null' : $this->notes;

		return $values;
	}

	/**
	 * Creates a Budget object from a resultset row
	 *
	 * @param array $row Resultset row from the database.
	 * @return Budget Budget object.
	 */
	private static function _createFromRow($row) {

		$budget = new Budget($row[self :: DB_FIELD_ID]);
		$budget->setBudgetType($row[self :: DB_FIELD_BUDGET_TYPE]);
		$budget->setBudgetUnit($row[self :: DB_FIELD_BUDGET_UNIT]);
		$budget->setBudgetValue($row[self :: DB_FIELD_BUDGET_VALUE]);
		$budget->setStartDate($row[self :: DB_FIELD_START_DATE]);
		$budget->setEndDate($row[self :: DB_FIELD_END_DATE]);
		$budget->setStatus($row[self :: DB_FIELD_STATUS]);
		$budget->setNotes($row[self :: DB_FIELD_NOTES]);

		return $budget;
	}

	/**
	 * Get select condition for given field and search string
	 */
	private static function _getSelectCondition($searchField, $searchStr) {

		$selectCondition = null;
		if (!is_null($searchStr) && ($searchStr !== '')) {
			$escapedVal = mysql_real_escape_string($searchStr);

			switch ($searchField) {
				case self :: SORT_FIELD_ID :
					$selectCondition = self :: DB_FIELD_ID . " = '{$escapedVal}' ";
					break;

				case self :: SORT_FIELD_BUDGET_TYPE :
					$selectCondition = self:: DB_FIELD_BUDGET_TYPE . " = '{$escapedVal}'  ";
					break;

				case self :: SORT_FIELD_BUDGET_UNIT :
					$selectCondition = self:: DB_FIELD_BUDGET_UNIT . " = '{$escapedVal}'  ";
					break;

				case self :: SORT_FIELD_BUDGET_VALUE :
					$selectCondition = self:: DB_FIELD_BUDGET_VALUE . " = '{$escapedVal}'  ";
					break;

				case self :: SORT_FIELD_START_DATE :
					$selectCondition = "DATE(" . self :: DB_FIELD_START_DATE . ") = '{$escapedVal}' ";
					break;

				case self :: SORT_FIELD_END_DATE :
					$selectCondition = "DATE(" . self :: DB_FIELD_END_DATE . ") = '{$escapedVal}' ";
					break;

				case self :: SORT_FIELD_STATUS :
					$selectCondition = self :: DB_FIELD_STATUS . " = '{$escapedVal}' ";
					break;
			}
			return $selectCondition;
		}
	}
}

class BudgetException extends Exception {
	const INVALID_PARAMETER = 0;
	const MISSING_PARAMETERS = 1;
	const DB_ERROR = 2;
	const INVALID_STATUS = 3;
}
?>

