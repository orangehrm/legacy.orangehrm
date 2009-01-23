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
require_once ROOT_PATH . '/lib/common/UniqueIDGenerator.php';
require_once ROOT_PATH . '/lib/common/SearchObject.php';
require_once ROOT_PATH . '/lib/common/LocaleUtil.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpRepTo.php';

class SalaryReview {

	const TABLE_NAME = 'hs_hr_salary_review';

	/** Database fields */
	const DB_FIELD_ID = 'id';
	const DB_FIELD_EMP_NUMBER = 'emp_number';
	const DB_FIELD_INCREASE = 'increase';
	const DB_FIELD_STATUS = 'status';
	const DB_FIELD_CREATED_BY = 'created_by';
	const DB_FIELD_APPROVED_BY = 'approved_by';
	const DB_FIELD_CREATED_TIME = 'created_time';
	const DB_FIELD_APPROVED_TIME = 'approved_time';
	const DB_FIELD_DESCRIPTION = 'description';

	const FIELD_EMP_NAME = 'emp_name';
	const FIELD_CREATED_BY_NAME = 'created_by_name';
	const FIELD_APPROVED_BY_NAME = 'approved_by_name';

	/** Status */
	const STATUS_PENDING_APPROVAL = 0;
	const STATUS_APPROVED = 1;
	const STATUS_REJECTED = 2;

	/** Field order */
	const SORT_FIELD_NONE = -1;
	const SORT_FIELD_EMPLOYEE_NAME = 0;
	const SORT_FIELD_REVIEW_DATE = 1;

	private $id;
	private $empNumber;
	private $increase;
	private $status = self::STATUS_PENDING_APPROVAL;
	private $createdBy;
	private $approvedBy;
	private $createdTime;
	private $approvedTime;
	private $description;
	private $employeeName;
	private $createdByName;
	private $approvedByName;

	/**
	 * Constructor
	 *
	 * @param int $id ID (can be null for newly created objects)
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
     * Retrieves the value of increase.
     * @return increase
     */
    public function getIncrease() {
        return $this->increase;
    }

    /**
     * Sets the value of increase.
     * @param increase
     */
    public function setIncrease($increase) {
        $this->increase = $increase;
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
     * Retrieves the value of createdBy.
     * @return createdBy
     */
    public function getCreatedBy() {
        return $this->createdBy;
    }

    /**
     * Sets the value of createdBy.
     * @param createdBy
     */
    public function setCreatedBy($createdBy) {
        $this->createdBy = $createdBy;
    }

    /**
     * Retrieves the value of approvedBy.
     * @return approvedBy
     */
    public function getApprovedBy() {
        return $this->approvedBy;
    }

    /**
     * Sets the value of approvedBy.
     * @param approvedBy
     */
    public function setApprovedBy($approvedBy) {
        $this->approvedBy = $approvedBy;
    }

    /**
     * Retrieves the value of createdTime.
     * @return createdTime
     */
    public function getCreatedTime() {
        return $this->createdTime;
    }

    /**
     * Sets the value of createdTime.
     * @param createdTime
     */
    public function setCreatedTime($createdTime) {
        $this->createdTime = $createdTime;
    }

    /**
     * Retrieves the value of approvedTime.
     * @return approvedTime
     */
    public function getApprovedTime() {
        return $this->approvedTime;
    }

    /**
     * Sets the value of approvedTime.
     * @param approvedTime
     */
    public function setApprovedTime($approvedTime) {
        $this->approvedTime = $approvedTime;
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
     * Retrieves the value of employeeName.
     * @return employeeName
     */
    public function getEmployeeName() {
        return $this->employeeName;
    }

    /**
     * Sets the value of employeeName.
     * @param employeeName
     */
    public function setEmployeeName($employeeName) {
        $this->employeeName = $employeeName;
    }

    /**
     * Retrieves the value of createdByName.
     * @return createdByName
     */
    public function getCreatedByName() {
        return $this->createdByName;
    }

    /**
     * Sets the value of createdByName.
     * @param createdByName
     */
    public function setCreatedByName($createdByName) {
        $this->createdByName = $createdByName;
    }

    /**
     * Retrieves the value of approvedByName.
     * @return approvedByName
     */
    public function getApprovedByName() {
        return $this->approvedByName;
    }

    /**
     * Sets the value of approvedByName.
     * @param approvedByName
     */
    public function setApprovedByName($approvedByName) {
        $this->approvedByName = $approvedByName;
    }

	/**
	 * Save Salary Review object to database
	 * @return int Returns the ID of the object
	 */
    public function save() {
		if (isset($this->id)) {
			return $this->_update();
		} else {
			return $this->_insert();
		}
    }

	/**
	 * Delete given salary reviews
	 * @param array $ids Array of Salary Review ID's to delete
	 */
	public static function delete($ids) {

		$count = 0;
		if (!is_array($ids)) {
			throw new SalaryReviewException("Invalid parameter to delete(): ids should be an array", SalaryReviewException::INVALID_PARAMETER);
		}

		foreach ($ids as $id) {
			if (!CommonFunctions::isValidId($id)) {
				throw new SalaryReviewException("Invalid parameter to delete(): id = $id", SalaryReviewException::INVALID_PARAMETER);
			}
		}

		if (!empty($ids)) {

			$sql = sprintf("DELETE FROM %s WHERE %s IN (%s)", self::TABLE_NAME,
			                self::DB_FIELD_ID, implode(",", $ids));

			$conn = new DMLFunctions();
			$result = $conn->executeQuery($sql);
			if ($result) {
				$count = mysql_affected_rows();
			}
		}
		return $count;
	}


	/**
	 * Get all salary reviews available in the system
	 *
	 * @return array Array of Salary Review objects
	 */
	public static function getAll() {
		return self::_getList();
	}

	/**
	 * Get Salary Review with given ID
	 * @param int $id The Salary Review ID
	 * @return SalaryReview Salary Review object with given id or null if not found
	 */
	public static function getSalaryReview($id) {

		if (!CommonFunctions::isValidId($id)) {
			throw new SalaryReviewException("Invalid parameters to getSalaryReview(): id = $id", SalaryReviewException::INVALID_PARAMETER);
		}

		$selectCondition[] = "a." . self::DB_FIELD_ID . " = $id";
		$list = self::_getList($selectCondition);

		$review = null;
		if (count($list) > 0) {
			$review = $list[0];
		}

		return $review;
	}

	/**
	 * Get condition to compare date with date salary review was created.
	 */
	private static function _getDateComparisionCondition($value) {

		// Check for search by full date or or just year
		if (CommonFunctions::isInt($value)) {
			$condition = " YEAR(a." . self::DB_FIELD_CREATED_TIME . ") = '{$value}' ";
		} else {
			$date = LocaleUtil::getInstance()->convertToStandardDateFormat($value);
			$condition = " DATE(a." . self::DB_FIELD_CREATED_TIME . ") = '{$date}' ";
		}
		return $condition;
	}

	/**
	 * Get list of Salary Reviews in a format suitable for view.php
	 * TODO: To be implemented
	 *
	 * @param int $pageNo The page number. 0 to fetch all
	 * @param string $searchStr The search string
	 * @param int $searchfieldNo which field to search on
	 * @param int $sortField The field to sort by
	 * @param string $sortOrder Sort Order (one of ASC or DESC)
	 * @param string $supervisorEmpNum Supervisors employee number (or null if not a supervisor);
	 */
	public static function getListForView($pageNO = 0, $searchStr = '', $searchFieldNo = self::SORT_FIELD_NONE, $sortField = self::SORT_FIELD_VACANCY_ID, $sortOrder = 'ASC', $supervisorEmpNum = null) {

		$selectCondition = null;
		$dbConnection = new DMLFunctions();
		$escapedVal = mysql_real_escape_string($searchStr);

		switch ($searchFieldNo) {
			case self::SORT_FIELD_REVIEW_DATE:

				$selectCondition[] = self::_getDateComparisionCondition($escapedVal);
				break;
			case self::SORT_FIELD_EMPLOYEE_NAME:
				// TODO: Fix. This doesn't match when searching for full name. Eg: "John Adams"'
				$selectCondition[] = "( b.`emp_firstname` LIKE '{$escapedVal}%' OR " .
										"b.`emp_lastname` LIKE '{$escapedVal}%' OR " .
										"b.`emp_middle_name` LIKE '{$escapedVal}%') ";
				break;
		}

		if (!empty($supervisorEmpNum)) {
			$repObj = new EmpRepTo();
			$subordinates = $repObj->getEmpSubDetails($_SESSION['empID']);
			$subordinateIds = array();
			foreach ($subordinates as $subordinate) {
				$subordinateIds[] = $subordinate[0];
			}

			if (!empty($subordinateIds)) {
				$selectCondition[] = "a.emp_number IN (" . implode(',', $subordinateIds). ")";
			}
		}

		$list = self::_getList($selectCondition);

		$i = 0;
		$arrayDispList = null;

		foreach($list as $review) {
			$arrayDispList[$i][0] = $review->getId();
	    	$arrayDispList[$i][1] = $review->getEmployeeName();
	    	$arrayDispList[$i][2] = $review->getCreatedTime();
	    	$arrayDispList[$i][3] = $review->getStatus();
	    	$i++;
	     }

		return $arrayDispList;
	}

	/**
	 * Count Salary reviews with given search conditions
	 *
	 * TODO: To be implemented
	 *
	 * @param string $searchStr Search string
	 * @param string $searchFieldNo Integer giving which field to search on
	 */
	public static function getCount($searchStr = '', $searchFieldNo = self::SORT_FIELD_NONE, $supervisorEmpNum = null) {

		$selectCondition = null;
		$dbConnection = new DMLFunctions();
		$escapedVal = mysql_real_escape_string($searchStr);

		switch ($searchFieldNo) {
			case self::SORT_FIELD_REVIEW_DATE:
				$selectCondition[] = self::_getDateComparisionCondition($escapedVal);
				break;
			case self::SORT_FIELD_EMPLOYEE_NAME:
				$selectCondition[] = "b.`emp_firstname` LIKE '{$escapedVal}%' OR " .
								     "b.`emp_lastname` LIKE '{$escapedVal}%' OR " .
									 "b.`emp_middle_name` LIKE '{$escapedVal}%' ";
				break;
		}

		if (!empty($supervisorEmpNum)) {
			$repObj = new EmpRepTo();
			$subordinates = $repObj->getEmpSubDetails($_SESSION['empID']);
			$subordinateIds = array();
			foreach ($subordinates as $subordinate) {
				$subordinateIds[] = $subordinate[0];
			}

			if (!empty($subordinateIds)) {
				$selectCondition[] = "a.emp_number IN (" . implode(',', $subordinateIds). ")";
			}
		}

		$count = 0;
		$sql = sprintf('SELECT count(*) FROM %s a, %s b WHERE a.emp_number = b.emp_number', self::TABLE_NAME, 'hs_hr_employee');

		if (!empty($selectCondition)) {
			$where = "";
			foreach ($selectCondition as $condition) {
				$where .= ' AND ( ' . $condition . ' )';
			}
			$sql .= $where;
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
	 * Get a list of salary reviews with the given conditions.
	 *
	 * @param array   $selectCondition Array of select conditions to use.				const  = 'hs_hr_perf_measure_jobtitle';
	 * @return array  Array of Salary Review objects. Returns an empty (length zero) array if none found.
	 */
	private static function _getList($selectCondition = null) {

	    $fields[0] = 'a.' . self::DB_FIELD_ID;
	    $fields[1] = 'a.' . self::DB_FIELD_EMP_NUMBER;
	    $fields[2] = 'a.' . self::DB_FIELD_INCREASE;
    	$fields[3] = 'a.' . self::DB_FIELD_STATUS;
        $fields[4] = 'a.' . self::DB_FIELD_CREATED_BY;
        $fields[5] = 'a.' . self::DB_FIELD_APPROVED_BY;
        $fields[6] = 'a.' . self::DB_FIELD_CREATED_TIME;
        $fields[7] = 'a.' . self::DB_FIELD_APPROVED_TIME;
	    $fields[8] = 'a.' . self::DB_FIELD_DESCRIPTION;
        $fields[9] = "CONCAT(b.`emp_firstname`, ' ', b.`emp_lastname`) AS " . self::FIELD_EMP_NAME;
        $fields[10] = "c.user_name AS " . self::FIELD_CREATED_BY_NAME;
		$fields[11] = "d.user_name AS " . self::FIELD_APPROVED_BY_NAME;

        $tables[0] = self::TABLE_NAME . ' a';
        $tables[1] = 'hs_hr_employee b';
        $tables[2] = 'hs_hr_users c';
        $tables[3] = 'hs_hr_users d';

        $joinConditions[1] = 'a.' . self::DB_FIELD_EMP_NUMBER . ' = b.emp_number';
		$joinConditions[2] = 'a.' . self::DB_FIELD_CREATED_BY . ' = c.id';
		$joinConditions[3] = 'a.' . self::DB_FIELD_CREATED_BY . ' = d.id';

		//Restrict ess user from changing status  his own salary review
		$selectCondition[]="a.emp_number !='".$_SESSION['empID']."'";

        $orderBy = 'a.' . self::DB_FIELD_ID;
        $order = 'ASC';

        $sqlBuilder = new SQLQBuilder();
        $sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition, null, $orderBy, $order);

        $list = array();

        $conn = new DMLFunctions();
        $result = $conn->executeQuery($sql);

        while ($result && ($row = mysql_fetch_assoc($result))) {
            $list[] = self::_createFromRow($row);
        }

        return $list;

	}

	/**
	 * Insert new object to database
	 */
	private function _insert() {

		$this->id = UniqueIDGenerator::getInstance()->getNextID(self::TABLE_NAME, self::DB_FIELD_ID);

	    $fields[] = self::DB_FIELD_ID;
	    $values[] = $this->id;

	    $fields[] = self::DB_FIELD_EMP_NUMBER;
	    $values[] = $this->empNumber;

	    $fields[] = self::DB_FIELD_INCREASE;
	    $values[] = $this->increase;


    	$fields[] = self::DB_FIELD_STATUS;
    	$values[] = $this->status;

	    if (!empty($this->createdBy)) {
		    $fields[] = self::DB_FIELD_CREATED_BY;
		    $values[] = $this->createdBy;
	    }

	    if (!empty($this->approvedBy)) {
		    $fields[] = self::DB_FIELD_APPROVED_BY;
		    $values[] = $this->approvedBy;
	    }

	    if (!empty($this->createdTime)) {
		    $fields[] = self::DB_FIELD_CREATED_TIME;
			$values[] = $this->createdTime;
	    }

	    if (!empty($this->approvedTime)) {
		    $fields[] = self::DB_FIELD_APPROVED_TIME;
		    $values[] = $this->approvedTime;
	    }

	    $fields[] = self::DB_FIELD_DESCRIPTION;
	    $values[] = $this->description;

		$sqlBuilder = new SQLQBuilder();
		$sqlBuilder->table_name = self::TABLE_NAME;
		$sqlBuilder->flg_insert = 'true';
		$sqlBuilder->arr_insert = $values;
		$sqlBuilder->arr_insertfield = $fields;

		$sql = $sqlBuilder->addNewRecordFeature2();

		$conn = new DMLFunctions();

		$result = $conn->executeQuery($sql);
		if (!$result || (mysql_affected_rows() != 1)) {
			throw new SalaryReviewException("Insert failed. ", SalaryReviewException::DB_ERROR);
		}

		return $this->id;
	}

	/**
	 * Update existing object
	 */
	private function _update() {

	    $fields[] = self::DB_FIELD_ID;
	    $values[] = $this->id;

	    $fields[] = self::DB_FIELD_EMP_NUMBER;
	    $values[] = $this->empNumber;

	    $fields[] = self::DB_FIELD_INCREASE;
	    $values[] = $this->increase;


    	$fields[] = self::DB_FIELD_STATUS;
    	$values[] = $this->status;

	    if (!empty($this->createdBy)) {
		    $fields[] = self::DB_FIELD_CREATED_BY;
		    $values[] = $this->createdBy;
	    }

	    if (!empty($this->approvedBy)) {
		    $fields[] = self::DB_FIELD_APPROVED_BY;
		    $values[] = $this->approvedBy;
	    }

	    if (!empty($this->createdTime)) {
		    $fields[] = self::DB_FIELD_CREATED_TIME;
			$values[] = $this->createdTime;
	    }

	    if (!empty($this->approvedTime)) {
		    $fields[] = self::DB_FIELD_APPROVED_TIME;
		    $values[] = $this->approvedTime;
	    }

	    $fields[] = self::DB_FIELD_DESCRIPTION;
	    $values[] = $this->description;

		$sqlBuilder = new SQLQBuilder();
		$sqlBuilder->table_name = self::TABLE_NAME;
		$sqlBuilder->flg_update = 'true';
		$sqlBuilder->arr_update = $fields;
		$sqlBuilder->arr_updateRecList = $values;

		$sql = $sqlBuilder->addUpdateRecord1(0);

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		// Here we don't check mysql_affected_rows because update may be called
		// without any changes.
		if (!$result) {
			throw new SalaryReviewException("Update failed. SQL=$sql", SalaryReviewException::DB_ERROR);
		}
		return $this->id;
	}

    /**
     * Creates a Salary Review object from a resultset row
     *
     * @param array $row Resultset row from the database.
     * @return SalaryReview SalaryReview object.
     */
    private static function _createFromRow($row) {

    	$review = new SalaryReview($row[self::DB_FIELD_ID]);
	    $review->setEmpNumber($row[self::DB_FIELD_EMP_NUMBER]);
	    $review->setIncrease($row[self::DB_FIELD_INCREASE]);
    	$review->setStatus($row[self::DB_FIELD_STATUS]);
        $review->setCreatedBy($row[self::DB_FIELD_CREATED_BY]);
	    $review->setApprovedBy($row[self::DB_FIELD_APPROVED_BY]);
	    $review->setCreatedTime($row[self::DB_FIELD_CREATED_TIME]);
	    $review->setApprovedTime($row[self::DB_FIELD_APPROVED_TIME]);
	    $review->setDescription($row[self::DB_FIELD_DESCRIPTION]);
	    if (isset($row[self::FIELD_EMP_NAME])) {
	    	$review->setEmployeeName($row[self::FIELD_EMP_NAME]);
	    }
	    if (isset($row[self::FIELD_CREATED_BY_NAME])) {
	    	$review->setCreatedByName($row[self::FIELD_CREATED_BY_NAME]);
	    }
	    if (isset($row[self::FIELD_APPROVED_BY_NAME])) {
	    	$review->setApprovedByName($row[self::FIELD_APPROVED_BY_NAME]);
	    }

	    return $review;
    }
}

class SalaryReviewException extends Exception {
	const INVALID_PARAMETER = 0;
	const DB_ERROR = 1;
}

?>
