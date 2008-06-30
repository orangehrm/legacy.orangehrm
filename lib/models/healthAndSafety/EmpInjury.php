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
 * Class representing a Employee Injury
 */
class EmpInjury {

	const TABLE_NAME = 'hs_hr_emp_injury';

	/** Database fields */
	const DB_FIELD_ID = 'id';
	const DB_FIELD_EMP_NUMBER = 'emp_number';	
	const DB_FIELD_INJURY = 'injury';
	const DB_FIELD_DESCRIPTION = 'description';
	const DB_FIELD_INCIDENT_DATE = 'incident_date';
	const DB_FIELD_REPORTED_DATE = 'reported_date';
	const DB_FIELD_TIME_OFF_WORK = 'time_off_work';
	const DB_FIELD_RESULT = 'result';

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
	const SORT_FIELD_DATE_OF_INCIDENT = 4;
	const SORT_FIELD_DATE_REPORTED = 5;
	const SORT_FIELD_INJURY = 6;
	const SORT_FIELD_TIME_OFF_WORK = 7;
	const SORT_FIELD_RESULT = 8;

	private $dbFields = array (
		self :: DB_FIELD_ID,
		self :: DB_FIELD_EMP_NUMBER,
		self :: DB_FIELD_INJURY,
		self :: DB_FIELD_DESCRIPTION,
		self :: DB_FIELD_INCIDENT_DATE,
		self :: DB_FIELD_REPORTED_DATE,
		self :: DB_FIELD_TIME_OFF_WORK,
		self :: DB_FIELD_RESULT,				
	);

	private $id;
	private $empNumber;
	private $injury;
	private $description;
	private $incidentDate;
	private $reportedDate;
	private $timeOffWork;
	private $result;
	
	/**
	 * Attributes retrieved from other objects
	 */
	private $empName;
	private $subDivisionName;
	private $locations;

	/**
	 * Constructor
	 *
	 * @param int $id ID can be null for newly created employeeinjuries
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
     * Retrieves the value of injury.
     * @return injury
     */
    public function getInjury() {
        return $this->injury;
    }

    /**
     * Sets the value of injury.
     * @param injury
     */
    public function setInjury($injury) {
        $this->injury = $injury;
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
     * Retrieves the value of incidentDate.
     * @return incidentDate
     */
    public function getIncidentDate() {
        return $this->incidentDate;
    }

    /**
     * Sets the value of incidentDate.
     * @param incidentDate
     */
    public function setIncidentDate($incidentDate) {
        $this->incidentDate = $incidentDate;
    }

    /**
     * Retrieves the value of reportedDate.
     * @return reportedDate
     */
    public function getReportedDate() {
        return $this->reportedDate;
    }

    /**
     * Sets the value of reportedDate.
     * @param reportedDate
     */
    public function setReportedDate($reportedDate) {
        $this->reportedDate = $reportedDate;
    }

    /**
     * Retrieves the value of timeOffWork.
     * @return timeOffWork
     */
    public function getTimeOffWork() {
        return $this->timeOffWork;
    }

    /**
     * Sets the value of timeOffWork.
     * @param timeOffWork
     */
    public function setTimeOffWork($timeOffWork) {
        $this->timeOffWork = $timeOffWork;
    }

    /**
     * Retrieves the value of result.
     * @return result
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * Sets the value of result.
     * @param result
     */
    public function setResult($result) {
        $this->result = $result;
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
	 * Save EmpInjury object to database
	 *
	 * If a new EmpInjury, inserts into the database, otherwise, updates
	 * the existing entry.
	 *
	 * @return int Returns the ID of the EmpInjury
	 */
	public function save() {

		if (empty ($this->empNumber)) {
			throw new EmpInjuryException("Attributes not set", EmpInjuryException :: MISSING_PARAMETERS);
		}

		if (!CommonFunctions :: isValidId($this->empNumber)) {
			throw new EmpInjuryException("Invalid employee number", EmpInjuryException :: INVALID_PARAMETER);
		}

		if (isset ($this->id)) {

			if (!CommonFunctions :: isValidId($this->id)) {
				throw new EmpInjuryException("Invalid id", EmpInjuryException :: INVALID_PARAMETER);
			}
			return $this->_update();
		} else {
			return $this->_insert();
		}
	}

	/**
	 * Get Employee Injury with given id
	 *
	 * @param int $id Employee Injury ID
	 * @return EmpInjury EmpInjury object
	 */
	public static function getEmpInjury($id) {

		if (!CommonFunctions :: isValidId($id)) {
			throw new EmpInjuryException("Invalid id", EmpInjuryException :: INVALID_PARAMETER);
		}

		$conditions[] = 'a.' . self :: DB_FIELD_ID . ' = ' . $id;
		$list = self :: _getList($conditions);
		$injury = (count($list) == 1) ? $list[0] : null;

		return $injury;
	}

	/**
	 * Get list of employee injuries
	 * @return Array Array of EmpInjury objects.
	 */
	public static function getAll() {
		return self :: _getList();
	}

	/**
	 * Get list of Employee Injurys in a format suitable for view.php
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

			case self :: SORT_FIELD_DATE_OF_INCIDENT :
				$sortBy = 'a.' . self :: DB_FIELD_INCIDENT_DATE;
				break;

			case self :: SORT_FIELD_DATE_REPORTED :
				$sortBy = 'a.' . self :: DB_FIELD_REPORTED_DATE;
				break;

			case self :: SORT_FIELD_INJURY :
				$sortBy = 'a.' . self :: DB_FIELD_INJURY;
				break;
				
			case self :: SORT_FIELD_TIME_OFF_WORK :
				$sortBy = 'a.' . self :: DB_FIELD_TIME_OFF_WORK;
				break;
								
			case self :: SORT_FIELD_RESULT :
				$sortBy = 'a.' . self :: DB_FIELD_RESULT;
				break;
		}

		$list = self :: _getList($selectCondition, $sortBy, $sortOrder, $limit, $locationSearch);

		$i = 0;
		$arrayDispList = null;

		foreach ($list as $injury) {
			$arrayDispList[$i][0] = $injury->getId();
			$arrayDispList[$i][1] = $injury->getEmpName();
			$arrayDispList[$i][2] = $injury->getSubDivisionName();
			$arrayDispList[$i][3] = $injury->getLocations();
			$arrayDispList[$i][4] = $injury->getIncidentDate();
			$arrayDispList[$i][5] = $injury->getReportedDate();
			$arrayDispList[$i][6] = $injury->getInjury();
			$arrayDispList[$i][7] = $injury->getTimeOffWork();
			$arrayDispList[$i][8] = $injury->getResult();
			$i++;
		}

		return $arrayDispList;
	}
	
	/**
	 * Count Employee Injurys with given search conditions
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
	 * Delete given Employee Injurys
	 * @param array $ids Array of Employee Injury ID's to delete
	 */
	public static function delete($ids) {

		$count = 0;
		if (!is_array($ids)) {
			throw new EmpInjuryException("Invalid parameter to delete(): ids should be an array", EmpInjuryException :: INVALID_PARAMETER);
		}

		foreach ($ids as $id) {
			if (!CommonFunctions :: isValidId($id)) {
				throw new EmpInjuryException("Invalid parameter to delete(): id = $id", EmpInjuryException :: INVALID_PARAMETER);
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
			throw new EmpInjuryException("Insert failed. ", EmpInjuryException :: DB_ERROR);
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
			throw new EmpInjuryException("Update failed. SQL=$sql", EmpInjuryException :: DB_ERROR);
		}
		return $this->id;
	}

	/**
	 * Get a list of Employee Injurys with the given conditions.
	 *
	 * @param array  $selectCondition Array of select conditions to use.
	 * @return array Array of EmpInjury objects. Returns an empty (length zero) array if none found.
	 */
	private static function _getList($selectCondition = null, $sortBy = null, $sortOrder = null, $limit = null, $locationSearch = null) {

		$fields[0] = 'a.' . self :: DB_FIELD_ID;
		$fields[1] = 'a.' . self :: DB_FIELD_EMP_NUMBER;
		$fields[2] = 'a.' . self :: DB_FIELD_INJURY;
		$fields[3] = 'a.' . self :: DB_FIELD_DESCRIPTION;
		$fields[4] = 'a.' . self :: DB_FIELD_INCIDENT_DATE;
		$fields[5] = 'a.' . self :: DB_FIELD_REPORTED_DATE;
		$fields[6] = 'a.' . self :: DB_FIELD_TIME_OFF_WORK;
		$fields[7] = 'a.' . self :: DB_FIELD_RESULT;
		
		$fields[8] = "CONCAT(b.`emp_firstname`, ' ', b.`emp_lastname`) AS " . self::FIELD_EMP_NAME;				
		$fields[9] = 'c.title AS ' . self :: FIELD_EMP_SUBDIVISION_NAME;
		$fields[10] = 'GROUP_CONCAT(e.loc_name) AS ' . self::FIELD_EMP_LOCATIONS;

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
		$values[2] = $this->injury;
		$values[3] = $this->description;
		$values[4] = is_null($this->incidentDate) ? 'null' : $this->incidentDate;
		$values[5] = is_null($this->reportedDate) ? 'null' : $this->reportedDate;		
		$values[6] = $this->timeOffWork;		
		$values[7] = $this->result;		
		return $values;
	}

	/**
	 * Creates a EmpInjury object from a resultset row
	 *
	 * @param array $row Resultset row from the database.
	 * @return EmpInjury EmpInjury object.
	 */
	private static function _createFromRow($row) {

		$injury = new EmpInjury($row[self :: DB_FIELD_ID]);
		$injury->setEmpNumber($row[self :: DB_FIELD_EMP_NUMBER]);
		$injury->setInjury($row[self :: DB_FIELD_INJURY]);
		$injury->setDescription($row[self :: DB_FIELD_DESCRIPTION]);
		$injury->setIncidentDate($row[self :: DB_FIELD_INCIDENT_DATE]);
		$injury->setReportedDate($row[self :: DB_FIELD_REPORTED_DATE]);
		$injury->setTimeOffWork($row[self :: DB_FIELD_TIME_OFF_WORK]);
		$injury->setResult($row[self :: DB_FIELD_RESULT]);

		if (isset ($row[self :: FIELD_EMP_SUBDIVISION_NAME])) {
			$injury->setSubdivisionName($row[self :: FIELD_EMP_SUBDIVISION_NAME]);
		}
		if (isset ($row[self :: FIELD_EMP_NAME])) {
			$injury->setEmpName($row[self :: FIELD_EMP_NAME]);
		}
		
		if (!empty($row[self :: FIELD_EMP_LOCATIONS])) {
			$injury->setLocations($row[self :: FIELD_EMP_LOCATIONS]);
		}
		return $injury;
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
	
					case self :: SORT_FIELD_DATE_OF_INCIDENT :
						$selectCondition = "DATE(a." . self :: DB_FIELD_INCIDENT_DATE . ") = '{$escapedVal}' ";
						break;
	
					case self :: SORT_FIELD_DATE_REPORTED :
						$selectCondition = "DATE(a." . self :: DB_FIELD_REPORTED_DATE . ") = '{$escapedVal}' ";
						break;
						
					case self :: SORT_FIELD_INJURY :
						$selectCondition = "a." . self :: DB_FIELD_INJURY . " LIKE '%{$escapedVal}%' ";
						break;
						
					case self :: SORT_FIELD_TIME_OFF_WORK :
						$selectCondition = "a." . self :: DB_FIELD_TIME_OFF_WORK . " = '{$escapedVal}' ";
						break;
						
					case self :: SORT_FIELD_RESULT :
						$selectCondition = "a." . self :: DB_FIELD_RESULT . " LIKE '%{$escapedVal}%' ";
						break;
			}
			return $selectCondition;
		}
	}
}

class EmpInjuryException extends Exception {
	const INVALID_PARAMETER = 0;
	const MISSING_PARAMETERS = 1;
	const DB_ERROR = 2;
	const INVALID_STATUS = 3;
}
?>

