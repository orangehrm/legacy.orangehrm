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

/**
 * Class representing a Ergonomic Assessment
 */
class ErgonomicAssessment {

	const TABLE_NAME = 'hs_hr_emp_ergonomic_assessments';

	/** Database fields */
	const DB_FIELD_ID = 'id';
	const DB_FIELD_EMP_NUMBER = 'emp_number';
	const DB_FIELD_START_DATE = 'start_date';
	const DB_FIELD_END_DATE = 'end_date';
	const DB_FIELD_STATUS = 'status';
	const DB_FIELD_NOTES = 'notes';

	/**
	 * Ergonomic Assessment status
	 */
	const STATUS_INCOMPLETE = 0;
	const STATUS_COMPLETE = 1;

	/** Fields retrieved from other tables */
	const FIELD_EMP_NAME = 'employee_name';
	const FIELD_EMP_SUBDIVISION_NAME = 'title';
	const FIELD_EMP_LOCATIONS = 'locations';

	/** Field order */
	const SORT_FIELD_NONE = -1;
	const SORT_FIELD_ID = 0;
	const SORT_FIELD_EMP_NAME = 1;
	const SORT_FIELD_EMP_SUBDIVISION_NAME = 2;	
	const SORT_FIELD_EMP_LOCATIONS = 3;
	const SORT_FIELD_START_DATE = 4;
	const SORT_FIELD_END_DATE = 5;
	const SORT_FIELD_STATUS = 6;

	private $dbFields = array (
		self :: DB_FIELD_ID,
		self :: DB_FIELD_EMP_NUMBER,
		self :: DB_FIELD_START_DATE,
		self :: DB_FIELD_END_DATE,
		self :: DB_FIELD_STATUS,
		self :: DB_FIELD_NOTES,		
	);

	private $id;
	private $empNumber;
	private $startDate;
	private $endDate;
	private $status;
	private $notes;
	
	/**
	 * Attributes retrieved from other objects
	 */
	private $empName;
	private $subDivisionName;
	private $locations;

	/**
	 * Constructor
	 *
	 * @param int $id ID can be null for newly created ergonomic assessments
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
     * Retrieves the value of empNumber.
     * @return empNumber
     */
    public function getEmpNumber() {
        return $this->empNumber;
    }

    /**
     * Sets the value of empNumber.
     * @param empNumber
     */
    public function setEmpNumber($empNumber) {
        $this->empNumber = $empNumber;
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
     * Retrieves the value of empName.
     * @return empName
     */
    public function getEmpName() {
        return $this->empName;
    }

    /**
     * Sets the value of empName.
     * @param empName
     */
    public function setEmpName($empName) {
        $this->empName = $empName;
    }

    /**
     * Retrieves the value of subDivisionName.
     * @return subDivisionName
     */
    public function getSubDivisionName() {
        return $this->subDivisionName;
    }

    /**
     * Sets the value of subDivisionName.
     * @param subDivisionName
     */
    public function setSubDivisionName($subDivisionName) {
        $this->subDivisionName = $subDivisionName;
    }

    /**
     * Retrieves the value of locations.
     * @return locations
     */
    public function getLocations() {
        return $this->locations;
    }

    /**
     * Sets the value of locations.
     * @param locations
     */
    public function setLocations($locations) {
        $this->locations = $locations;
    }


	/**
	 * Save ErgonomicAssessment object to database
	 *
	 * If a new ErgonomicAssessment, inserts into the database, otherwise, updates
	 * the existing entry.
	 *
	 * @return int Returns the ID of the ErgonomicAssessment
	 */
	public function save() {

		if (empty ($this->empNumber)) {
			throw new ErgonomicAssessmentException("Attributes not set", ErgonomicAssessmentException :: MISSING_PARAMETERS);
		}

		if (!CommonFunctions :: isValidId($this->empNumber)) {
			throw new ErgonomicAssessmentException("Invalid employee number", ErgonomicAssessmentException :: INVALID_PARAMETER);
		}

		if (isset ($this->id)) {

			if (!CommonFunctions :: isValidId($this->id)) {
				throw new ErgonomicAssessmentException("Invalid id", ErgonomicAssessmentException :: INVALID_PARAMETER);
			}
			return $this->_update();
		} else {
			return $this->_insert();
		}
	}

	/**
	 * Get Ergonomic Assessment with given id
	 *
	 * @param int $id Ergonomic Assessment ID
	 * @return ErgonomicAssessment ErgonomicAssessment object
	 */
	public static function getErgonomicAssessment($id) {

		if (!CommonFunctions :: isValidId($id)) {
			throw new ErgonomicAssessmentException("Invalid id", ErgonomicAssessmentException :: INVALID_PARAMETER);
		}

		$conditions[] = 'a.' . self :: DB_FIELD_ID . ' = ' . $id;
		$list = self :: _getList($conditions);
		$assessment = (count($list) == 1) ? $list[0] : null;

		return $assessment;
	}

	/**
	 * Get list of ergonomic assessments
	 * @return Array Array of ErgonomicAssessment objects.
	 */
	public static function getAll() {
		return self :: _getList();
	}

	/**
	 * Get list of Ergonomic Assessments in a format suitable for view.php
	 *
	 * @param int $pageNo The page number. 0 to fetch all
	 * @param string $searchStr The search string
	 * @param int $searchfieldNo which field to search on
	 * @param int $sortField The field to sort by
	 * @param string $sortOrder Sort Order (one of ASC or DESC)
	 */
	public static function getListForView($pageNO = 0, $searchStr = '', $searchFieldNo = self :: SORT_FIELD_NONE, $sortField = self :: SORT_FIELD_ID, $sortOrder = 'ASC') {

		$selectCondition = null;
		$dbConnection = new DMLFunctions();
		$locationSearch = null;

		if ($searchFieldNo == self::SORT_FIELD_EMP_LOCATIONS) {
			$locationSearch = mysql_real_escape_string($searchStr);			
		} else {
			$condition = self::_getSelectCondition($searchFieldNo, $searchStr);
			if (!empty ($condition)) {
				$selectCondition[] = $condition;
			}
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
				$sortBy = 'a.' . self :: DB_FIELD_ID;
				break;

			case self :: SORT_FIELD_EMP_NAME :
				$sortBy = "CONCAT(b.`emp_firstname`, ' ', b.`emp_lastname`)";
				break;

			case self :: SORT_FIELD_EMP_SUBDIVISION_NAME :
				$sortBy = "c.`title`";
				break;

			case self :: SORT_FIELD_EMP_LOCATIONS :
				$sortBy = self :: FIELD_EMP_LOCATIONS;
				break;

			case self :: SORT_FIELD_START_DATE :
				$sortBy = 'a.' . self :: DB_FIELD_START_DATE;
				break;

			case self :: SORT_FIELD_END_DATE :
				$sortBy = 'a.' . self :: DB_FIELD_END_DATE;
				break;

			case self :: SORT_FIELD_STATUS :
				$sortBy = 'a.' . self :: DB_FIELD_STATUS;
				break;
		}

		$list = self :: _getList($selectCondition, $sortBy, $sortOrder, $limit, $locationSearch);

		$i = 0;
		$arrayDispList = null;

		foreach ($list as $assessment) {
			$arrayDispList[$i][0] = $assessment->getId();
			$arrayDispList[$i][1] = $assessment->getEmpName();
			$arrayDispList[$i][2] = $assessment->getSubDivisionName();
			$arrayDispList[$i][3] = $assessment->getLocations();
			$arrayDispList[$i][4] = $assessment->getStartDate();
			$arrayDispList[$i][5] = $assessment->getEndDate();
			$arrayDispList[$i][6] = $assessment->getStatus();
			$i++;
		}

		return $arrayDispList;
	}
	
	/**
	 * Count Ergonomic Assessments with given search conditions
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

		$fields[0] = 'a.' . self :: DB_FIELD_ID;
		$fields[1] = 'GROUP_CONCAT(e.loc_name) AS ' . self::FIELD_EMP_LOCATIONS;
		
		$tables[0] = self :: TABLE_NAME . ' a';
		$tables[1] = 'hs_hr_employee b';
		$tables[2] = 'hs_hr_compstructtree c';
		$tables[3] = 'hs_hr_emp_locations d';
		$tables[4] = 'hs_hr_location e';
		
		$joinConditions[1] = 'a.' . self :: DB_FIELD_EMP_NUMBER . ' = b.emp_number ';
		$joinConditions[2] = 'b.work_station = c.id ';
		$joinConditions[3] = 'a.' . self :: DB_FIELD_EMP_NUMBER . ' = d.emp_number ';
		$joinConditions[4] = 'd.loc_code = e.loc_code';
		
		$groupBy = 'a.' . self :: DB_FIELD_ID;

		$count = 0;
		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition, null, null, null, null, $groupBy);
		
		$sql = 'SELECT count(*) FROM (' . $sql . ') AS sub';
		if ($searchFieldNo == self::SORT_FIELD_EMP_LOCATIONS) {
			$sql .= " WHERE " . self::FIELD_EMP_LOCATIONS . " LIKE '%{$searchStr}%'";
		}
		$result = $dbConnection->executeQuery($sql);

		if ($result) {
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$count = $line[0];
		}

		return $count;
	}

	/**
	 * Delete given Ergonomic Assessments
	 * @param array $ids Array of Ergonomic Assessment ID's to delete
	 */
	public static function delete($ids) {

		$count = 0;
		if (!is_array($ids)) {
			throw new ErgonomicAssessmentException("Invalid parameter to delete(): ids should be an array", ErgonomicAssessmentException :: INVALID_PARAMETER);
		}

		foreach ($ids as $id) {
			if (!CommonFunctions :: isValidId($id)) {
				throw new ErgonomicAssessmentException("Invalid parameter to delete(): id = $id", ErgonomicAssessmentException :: INVALID_PARAMETER);
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
			throw new ErgonomicAssessmentException("Insert failed. ", ErgonomicAssessmentException :: DB_ERROR);
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
			throw new ErgonomicAssessmentException("Update failed. SQL=$sql", ErgonomicAssessmentException :: DB_ERROR);
		}
		return $this->id;
	}

	/**
	 * Get a list of Ergonomic Assessments with the given conditions.
	 *
	 * @param array  $selectCondition Array of select conditions to use.
	 * @return array Array of ErgonomicAssessment objects. Returns an empty (length zero) array if none found.
	 */
	private static function _getList($selectCondition = null, $sortBy = null, $sortOrder = null, $limit = null, $locationSearch = null) {

		$fields[0] = 'a.' . self :: DB_FIELD_ID;
		$fields[1] = 'a.' . self :: DB_FIELD_EMP_NUMBER;
		$fields[2] = 'a.' . self :: DB_FIELD_START_DATE;
		$fields[3] = 'a.' . self :: DB_FIELD_END_DATE;
		$fields[4] = 'a.' . self :: DB_FIELD_STATUS;
		$fields[5] = 'a.' . self :: DB_FIELD_NOTES;
		$fields[6] = "CONCAT(b.`emp_firstname`, ' ', b.`emp_lastname`) AS " . self::FIELD_EMP_NAME;				
		$fields[7] = 'c.title AS ' . self :: FIELD_EMP_SUBDIVISION_NAME;
		$fields[8] = 'GROUP_CONCAT(e.loc_name) AS ' . self::FIELD_EMP_LOCATIONS;

		$tables[0] = self :: TABLE_NAME . ' a';
		$tables[1] = 'hs_hr_employee b';
		$tables[2] = 'hs_hr_compstructtree c';
		$tables[3] = 'hs_hr_emp_locations d';
		$tables[4] = 'hs_hr_location e';
		
		$joinConditions[1] = 'a.' . self :: DB_FIELD_EMP_NUMBER . ' = b.emp_number ';
		$joinConditions[2] = 'b.work_station = c.id ';
		$joinConditions[3] = 'a.' . self :: DB_FIELD_EMP_NUMBER . ' = d.emp_number ';
		$joinConditions[4] = 'd.loc_code = e.loc_code';
		
		$groupBy = 'a.' . self :: DB_FIELD_ID;		
		
		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition, null, $sortBy, $sortOrder, $limit, $groupBy);
	
		if (!empty($locationSearch)) {
			$sql = "SELECT * FROM ({$sql}) as sub WHERE " .  self::FIELD_EMP_LOCATIONS . " LIKE '%{$locationSearch}%' ";
		}
		$actList = array ();

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		while ($result && ($row = mysql_fetch_assoc($result))) {
			$actList[] = self :: _createFromRow($row);
		}

		return $actList;
	}

	/**
	 * Returns the db field values as an array
	 *
	 * @return Array Array containing field values in correct order.
	 */
	private function _getFieldValuesAsArray() {

		$values[0] = $this->id;
		$values[1] = $this->empNumber;
		$values[2] = is_null($this->startDate) ? 'null' : $this->startDate;
		$values[3] = is_null($this->endDate) ? 'null' : $this->endDate;		
		$values[4] = is_null($this->status) ? self :: STATUS_INCOMPLETE : $this->status;
		$values[5] = $this->notes;		
		return $values;
	}

	/**
	 * Creates a ErgonomicAssessment object from a resultset row
	 *
	 * @param array $row Resultset row from the database.
	 * @return ErgonomicAssessment ErgonomicAssessment object.
	 */
	private static function _createFromRow($row) {

		$assessment = new ErgonomicAssessment($row[self :: DB_FIELD_ID]);
		$assessment->setEmpNumber($row[self :: DB_FIELD_EMP_NUMBER]);
		$assessment->setStartDate($row[self :: DB_FIELD_START_DATE]);
		$assessment->setEndDate($row[self :: DB_FIELD_END_DATE]);
		$assessment->setNotes($row[self :: DB_FIELD_NOTES]);
		$assessment->setStatus($row[self :: DB_FIELD_STATUS]);

		if (isset ($row[self :: FIELD_EMP_SUBDIVISION_NAME])) {
			$assessment->setSubdivisionName($row[self :: FIELD_EMP_SUBDIVISION_NAME]);
		}
		if (isset ($row[self :: FIELD_EMP_NAME])) {
			$assessment->setEmpName($row[self :: FIELD_EMP_NAME]);
		}
		
		if (!empty($row[self :: FIELD_EMP_LOCATIONS])) {
			$assessment->setLocations($row[self :: FIELD_EMP_LOCATIONS]);
		}
		return $assessment;
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
					$selectCondition = "a." . self :: DB_FIELD_ID . " = '{$escapedVal}' ";
					break;

				case self :: SORT_FIELD_EMP_NAME :
					$selectCondition = "((b.`emp_firstname` LIKE '{$escapedVal}%') OR (b.`emp_lastname` LIKE '{$escapedVal}%') OR (CONCAT(b.`emp_firstname`, ' ', b.`emp_lastname`) LIKE '{$escapedVal}%')) ";
					break;

				case self :: SORT_FIELD_EMP_SUBDIVISION_NAME :
					$selectCondition = "c.`title` LIKE '{$escapedVal}%'  ";
					break;

				case self :: SORT_FIELD_EMP_LOCATIONS :
					// Do nothing.
					break;

				case self :: SORT_FIELD_START_DATE :
					$selectCondition = "DATE(a." . self :: DB_FIELD_START_DATE . ") = '{$escapedVal}' ";
					break;

				case self :: SORT_FIELD_END_DATE :
					$selectCondition = "DATE(a." . self :: DB_FIELD_END_DATE . ") = '{$escapedVal}' ";
					break;

				case self :: SORT_FIELD_STATUS :
					$selectCondition = "a." . self :: DB_FIELD_STATUS . " = '{$escapedVal}' ";
					break;
			}
			return $selectCondition;
		}
	}
}

class ErgonomicAssessmentException extends Exception {
	const INVALID_PARAMETER = 0;
	const MISSING_PARAMETERS = 1;
	const DB_ERROR = 2;
	const INVALID_STATUS = 3;
}
?>

