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
require_once ROOT_PATH . '/lib/models/performance/PerformanceScore.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpRepTo.php';
require_once ROOT_PATH . '/lib/confs/sysConf.php';

class PerformanceReview {
	
	const TABLE_NAME = 'hs_hr_perf_review';
	const REVIEW_MEASURE_TABLE_NAME = 'hs_hr_perf_review_measure';

	const DEFAULT_REVIEW_PERIOD = 3;
	
	/** Database fields */
	const DB_FIELD_ID = 'id';
	const DB_FIELD_EMP_NUMBER = 'emp_number';
	const DB_FIELD_REVIEW_DATE = 'review_date';
	const DB_FIELD_STATUS = 'status';
	const DB_FIELD_REVIEW_NOTES = 'review_notes';
	const DB_FIELD_NOTIFICATION_SENT = 'notification_sent';

	const DB_FIELD_REVIEW_ID = 'review_id';
	const DB_FIELD_PERF_MEASURE_ID = 'perf_measure_id';
	const DB_FIELD_SCORE = 'score';
	
	const FIELD_EMPLOYEE_NAME = 'emp_name';

	/** Field order */
	const SORT_FIELD_NONE = -1;
	const SORT_FIELD_ID = 0;
	const SORT_FIELD_EMPLOYEE_NAME = 1;	
	const SORT_FIELD_REVIEW_YEAR = 2;
	const SORT_FIELD_REVIEW_STATUS = 3;

	/** Status */
	const STATUS_SCHEDULED = 0;
	const STATUS_COMPLETED = 1;
	const STATUS_SUBMITTED_FOR_APPROVAL = 2;
	const STATUS_APPROVED = 3;	
		
	const NOTIFICATION_NOT_SENT = 0;
	const NOTIFICATION_SENT = 1;
	
	private $id;
	private $empNumber;
	private $reviewDate;
	private $status = self::STATUS_SCHEDULED;
	private $reviewNotes;
	private $performanceMeasures;
	private $employeeName;
	private $notificationSent;

	/**
	 * Constructor
	 *
	 * @param int $id ID (can be null for newly created objects)
	 */
	public function __construct($id = null) {
		$this->id = $id;
		$this->performanceMeasures = array();
		$this->notificationSent = self::NOTIFICATION_NOT_SENT;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setEmpNumber($empNumber) {
	    $this->empNumber = $empNumber;
	}

	public function setReviewDate($reviewDate) {
		$this->reviewDate = $reviewDate;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function setReviewNotes($reviewNotes) {
		$this->reviewNotes = $reviewNotes;
	}
	
	public function setPerformanceMeasures($performanceMeasures) {
		$this->performanceMeasures = $performanceMeasures;
	}

	public function setEmployeeName($employeeName) {
		$this->employeeName = $employeeName;
	}

	public function getId() {
		return $this->id;
	}

	public function getEmpNumber() {
	    return $this->empNumber;
	}
	
	public function getReviewDate() {
		return $this->reviewDate;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getReviewNotes() {
		return $this->reviewNotes;
	}

	public function getEmployeeName() {
		return $this->employeeName;
	}

	public function getPerformanceMeasures() {
		return $this->performanceMeasures;
	}

	public function isNotificationSent() {
		return $this->notificationSent;
	}
	
	public function setNotificationSent() {
		$this->notificationSent = self::NOTIFICATION_SENT;
	}
	
	/**
	 * Save Performance Review object to database
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
	 * Delete given performance reviews
	 * @param array $ids Array of Performance Review ID's to delete
	 */
	public static function delete($ids) {

		$count = 0;
		if (!is_array($ids)) {
			throw new PerformanceReviewException("Invalid parameter to delete(): ids should be an array", PerformanceReviewException::INVALID_PARAMETER);
		}

		foreach ($ids as $id) {
			if (!CommonFunctions::isValidId($id)) {
				throw new PerformanceReviewException("Invalid parameter to delete(): id = $id", PerformanceReviewException::INVALID_PARAMETER);
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
	
	public static function getReviewsPendingNotification() {
		
		$selectCondition[] = self::DB_FIELD_NOTIFICATION_SENT . " = " . self::NOTIFICATION_NOT_SENT;
		$selectCondition[] = self::DB_FIELD_STATUS . " = " . self::STATUS_SCHEDULED;
		$selectCondition[] = "(DATEDIFF(". self::DB_FIELD_REVIEW_DATE . ", CURDATE()) BETWEEN 0 AND 7)";

		$list = self::_getList($selectCondition);
		return $list;		
	}

	/**
	 * Get all performance reviews available in the system
	 *
	 * @return array Array of Performance Review objects
	 */
	public static function getAll() {
		return self::_getList();
	}

	/**
	 * Get Performance Review with given ID
	 * @param int $id The Performance Review ID
	 * @return PerformanceReview Performance Review object with given id or null if not found
	 */
	public static function getPerformanceReview($id) {

		if (!CommonFunctions::isValidId($id)) {
			throw new PerformanceReviewException("Invalid parameters to getPerformanceReview(): id = $id", PerformanceReviewException::INVALID_PARAMETER);
		}

		$selectCondition[] = self::DB_FIELD_ID . " = $id";
		$list = self::_getList($selectCondition);
		
		$review = null;
		if (count($list) > 0) {
			$review = $list[0];
			$review->setPerformanceMeasures(self::_fetchPerformanceMeasures($id));
		}
		
		return $review;
	}
	
	/**
	 * Get list of Performance Reviews in a format suitable for view.php
	 * TODO: To be implemented
	 *
	 * @param int $pageNo The page number. 0 to fetch all
	 * @param string $searchStr The search string
	 * @param int $searchfieldNo which field to search on
	 * @param int $sortField The field to sort by
	 * @param string $sortOrder Sort Order (one of ASC or DESC)
	 * @param string $supervisorEmpNum Supervisors employee number (or null if not a supervisor);
	 */
	public static function getListForView($pageNO = 0, $searchStr = array(), $searchFieldNo = self::SORT_FIELD_NONE, $sortField = self::SORT_FIELD_VACANCY_ID, $sortOrder = 'ASC', $supervisorEmpNum = null) {

		$selectCondition = null;
		$dbConnection = new DMLFunctions();
		
		for ($i = 0; $i < count($searchStr); $i++) {
			
			if (!empty($searchStr[$i])) {
				$escapedVal = mysql_real_escape_string($searchStr[$i]);
		
				switch ($searchFieldNo[$i]) {
					case self::SORT_FIELD_ID:
						$selectCondition[] = self::DB_FIELD_ID . " = '{$escapedVal}' ";
						break;
					case self::SORT_FIELD_REVIEW_STATUS:
						$selectCondition[] = self::DB_FIELD_STATUS . " = '{$escapedVal}' ";
						break;				
					
					case self::SORT_FIELD_REVIEW_YEAR:
						$selectCondition[] = "YEAR(a." . self::DB_FIELD_REVIEW_DATE . ") = '{$escapedVal}' ";
						break;
					case self::SORT_FIELD_EMPLOYEE_NAME:
						$selectCondition[] = "( b.`emp_firstname` LIKE '{$escapedVal}%' OR " .
												"b.`emp_lastname` LIKE '{$escapedVal}%' OR " .
												"b.`emp_middle_name` LIKE '{$escapedVal}%') "; 			
						break;				
				}
			}
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
			
		$sysConst = new sysConf();
		$limit = null;
		if ($pageNO > 0) {
			$pageNO--;
			$pageNO *= $sysConst->itemsPerPage;
			$limit = "{$pageNO}, {$sysConst->itemsPerPage}";
		}

		$sortBy = null;

		switch ($sortField) {
			case self::SORT_FIELD_ID:
				$sortBy = self::DB_FIELD_ID;
				break;
			case self::SORT_FIELD_EMPLOYEE_NAME:
				$sortBy = self::FIELD_EMPLOYEE_NAME;
				break;	
			case self::SORT_FIELD_REVIEW_YEAR:
				$sortBy =  self::DB_FIELD_REVIEW_DATE;
				break;
			case self::SORT_FIELD_REVIEW_STATUS:
				$sortBy =  self::DB_FIELD_STATUS;
				break;	
		}
				
		$list = self::_getList($selectCondition, $sortBy, $sortOrder, $limit);
		
		$i = 0;
		$arrayDispList = null;
		
		foreach($list as $review) {
			$arrayDispList[$i][0] = $review->getId();
	    	$arrayDispList[$i][1] = $review->getEmployeeName();
	    	$arrayDispList[$i][2] = $review->getReviewDate();
	    	$arrayDispList[$i][3] = $review->getStatus();
	    	$i++;
	     }

		return $arrayDispList;
	}

	/**
	 * Count performance reviews with given search conditions
	 * 
	 * TODO: To be implemented
	 * 
	 * @param string $searchStr Search string
	 * @param string $searchFieldNo Integer giving which field to search on
	 */
	public static function getCount($searchStr = '', $searchFieldNo = self::SORT_FIELD_NONE, $supervisorEmpNum = null) {

		$selectCondition = null;
		$dbConnection = new DMLFunctions();
		
		for ($i = 0; $i < count($searchStr); $i++) {
			
			if (!empty($searchStr[$i])) {
				$escapedVal = mysql_real_escape_string($searchStr[$i]);
		
				switch ($searchFieldNo[$i]) {
					case self::SORT_FIELD_ID:
						$selectCondition[] = self::DB_FIELD_ID . " = '{$escapedVal}' ";
						break;
					case self::SORT_FIELD_REVIEW_STATUS:
						$selectCondition[] = self::DB_FIELD_STATUS . " = '{$escapedVal}' ";
						break;				
					
					case self::SORT_FIELD_REVIEW_YEAR:
						$selectCondition[] = "YEAR(a." . self::DB_FIELD_REVIEW_DATE . ") = '{$escapedVal}' ";
						break;
					case self::SORT_FIELD_EMPLOYEE_NAME:
						$selectCondition[] = "( b.`emp_firstname` LIKE '{$escapedVal}%' OR " .
												"b.`emp_lastname` LIKE '{$escapedVal}%' OR " .
												"b.`emp_middle_name` LIKE '{$escapedVal}%') "; 			
						break;				
				}
			}
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
	 * Get list of performance measures assigned to this performance review
	 */
	private static function _fetchPerformanceMeasures($performanceReviewId) {

		$fields[0] = "a." . self::DB_FIELD_PERF_MEASURE_ID;
		$fields[1] = "a." . self::DB_FIELD_SCORE;
		$fields[2] = "b." . PerformanceMeasure::DB_FIELD_NAME;

		$tables[0] = self::REVIEW_MEASURE_TABLE_NAME . ' a';
		$tables[1] = PerformanceMeasure::TABLE_NAME . ' b';

		$joinConditions[1] = 'a.' . self::DB_FIELD_PERF_MEASURE_ID . ' = b.' . PerformanceMeasure::DB_FIELD_ID;

		$selectCondition[] = "a." . self::DB_FIELD_REVIEW_ID . " = " . $performanceReviewId;
		
		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition);

		$measures = array();

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		while ($result && ($row = mysql_fetch_assoc($result))) {
			$perfScore = new PerformanceScore();
			$perfScore->setId($row[self::DB_FIELD_PERF_MEASURE_ID]);
			$perfScore->setName($row[PerformanceMeasure::DB_FIELD_NAME]);
			$score = $row[self::DB_FIELD_SCORE];
			
			if (!is_null($score)) {
				$perfScore->setScore($score);
			}
			
			$measures[$perfScore->getId()] = $perfScore;
		}

		return $measures;	
	}
	
	/**
	 * Save performance measures assigned to this performance review
	 */
	private function _savePerformanceMeasures() {
		
				
		// Delete existing job title assignments		
		$sql = sprintf("DELETE FROM %s WHERE %s = %s", self::REVIEW_MEASURE_TABLE_NAME,
		                self::DB_FIELD_REVIEW_ID, $this->id);
		  
		// Extra condition              
		if (!empty($this->performanceMeasures) && count($this->performanceMeasures) > 0) {
			
			$assignedMeasureIds = array();
			foreach ($this->performanceMeasures as $measure) {
				$assignedMeasureIds[] = $measure->getId();
			}
			
			$idStr = implode(',', $assignedMeasureIds); 
			$extraCondition = sprintf(" AND %s not in (%s)", self::DB_FIELD_PERF_MEASURE_ID, $idStr);
			$sql .= $extraCondition;
		}
				                
		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);
				
		// Assign new performance reviews
		if (!empty($this->performanceMeasures)) {
			
			$scoresAvailable = $this->performanceMeasures[0] instanceof PerformanceScore;
			
			if ($scoresAvailable) {
				$sql = sprintf("INSERT INTO %s (%s, %s, %s) VALUES " , self::REVIEW_MEASURE_TABLE_NAME, 
					self::DB_FIELD_REVIEW_ID, self::DB_FIELD_PERF_MEASURE_ID, self::DB_FIELD_SCORE);

			} else {
				$sql = sprintf("INSERT IGNORE INTO %s (%s, %s) VALUES " , self::REVIEW_MEASURE_TABLE_NAME, 
					self::DB_FIELD_REVIEW_ID, self::DB_FIELD_PERF_MEASURE_ID);
			}
			
			
			$valueSql = "";
			foreach ($this->performanceMeasures as $measure) {
				
				if (!empty($valueSql)) {
					$valueSql .= ', ';
				}

				if ($scoresAvailable) {				
					$score = $measure->getScore(); 
					if (is_null($score)) {
						$scoreStr = 'null';
					} else {
						$scoreStr = sprintf('%f', $score);
					}					
					$valueSql .= sprintf("(%d, %d, %s)", $this->id, $measure->getId(), $scoreStr);
				} else {
					$valueSql .= sprintf("(%d, %d)", $this->id, $measure->getId());
				}				
			}
			
			$sql .= $valueSql;
			if ($scoresAvailable) {
				$sql .= sprintf(' ON DUPLICATE KEY UPDATE %s = VALUES(%s)', self::DB_FIELD_SCORE, self::DB_FIELD_SCORE);
			}
			
			$result = $conn->executeQuery($sql);
			if (!$result) {
				throw new PerformanceReviewException("Save Performance measures failed. SQL=$sql", PerformanceReviewException::DB_ERROR);
			}			
		}		
	}


	/**
	 * Get a list of performance reviews with the given conditions.
	 *
	 * @param array   $selectCondition Array of select conditions to use.
	 * @return array  Array of Performance Review objects. Returns an empty (length zero) array if none found.
	 */
	private static function _getList($selectCondition = null, $sortBy = null, $sortOrder = null, $limit = null) {

		$fields[0] = "a. " . self::DB_FIELD_ID;
		$fields[1] = "a. " . self::DB_FIELD_EMP_NUMBER;
		$fields[2] = "a. " . self::DB_FIELD_REVIEW_DATE;
		$fields[3] = "CONCAT(b.`emp_firstname`, ' ', b.`emp_lastname`) AS " . self::FIELD_EMPLOYEE_NAME;
		$fields[4] = "a. " . self::DB_FIELD_STATUS;
		$fields[5] = "a. " . self::DB_FIELD_REVIEW_NOTES;
		$fields[6] = "a. " . self::DB_FIELD_NOTIFICATION_SENT;

		$tables[0] = self::TABLE_NAME . ' a';
		$tables[1] = 'hs_hr_employee b';

		$joinConditions[1] = 'a.' . self::DB_FIELD_EMP_NUMBER . ' = b.emp_number';

		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition, null, $sortBy, $sortOrder, $limit);

		$actList = array();

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		while ($result && ($row = mysql_fetch_assoc($result))) {
			$actList[] = self::_createFromRow($row);
		}

		return $actList;	
	}

	/**
	 * Insert new object to database
	 */
	private function _insert() {

		$this->id = UniqueIDGenerator::getInstance()->getNextID(self::TABLE_NAME, self::DB_FIELD_ID);
		
		$fields[0] = self::DB_FIELD_ID;
		$fields[1] = self::DB_FIELD_EMP_NUMBER;
		$fields[2] = self::DB_FIELD_REVIEW_DATE;
		$fields[3] = self::DB_FIELD_STATUS;
		$fields[4] = self::DB_FIELD_REVIEW_NOTES;
		$fields[5] = self::DB_FIELD_NOTIFICATION_SENT;

		$values[0] = $this->id;
		$values[1] = $this->empNumber;
		$values[2] = "'{$this->reviewDate}'";
		$values[3] = $this->status;
		$values[4] = "'{$this->reviewNotes}'";
		$values[5] = $this->notificationSent;
		
		$sqlBuilder = new SQLQBuilder();
		$sqlBuilder->table_name = self::TABLE_NAME;
		$sqlBuilder->flg_insert = 'true';
		$sqlBuilder->arr_insert = $values;
		$sqlBuilder->arr_insertfield = $fields;

		$sql = $sqlBuilder->addNewRecordFeature2();
		$conn = new DMLFunctions();

		$result = $conn->executeQuery($sql);
		if (!$result || (mysql_affected_rows() != 1)) {
			throw new PerformanceReviewException("Insert failed. ", PerformanceReviewException::DB_ERROR);
		}

		$this->_savePerformanceMeasures();
		return $this->id;
	}

	/**
	 * Update existing object
	 */
	private function _update() {

		$fields[0] = self::DB_FIELD_ID;
		$fields[1] = self::DB_FIELD_EMP_NUMBER;
		$fields[2] = self::DB_FIELD_REVIEW_DATE;
		$fields[3] = self::DB_FIELD_STATUS;
		$fields[4] = self::DB_FIELD_REVIEW_NOTES;
		$fields[5] = self::DB_FIELD_NOTIFICATION_SENT;

		$values[0] = $this->id;
		$values[1] = $this->empNumber;
		$values[2] = "'{$this->reviewDate}'";
		$values[3] = $this->status;
		$values[4] = "'{$this->reviewNotes}'";
		$values[5] = $this->notificationSent;

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
			throw new PerformanceReviewException("Update failed. SQL=$sql", PerformanceReviewException::DB_ERROR);
		}
		$this->_savePerformanceMeasures();		
		return $this->id;
	}

    /**
     * Creates a Performance Review object from a resultset row
     *
     * @param array $row Resultset row from the database.
     * @return PerformanceReview PerformanceReview object.
     */
    private static function _createFromRow($row) {

		$fields[0] = "a. " . self::DB_FIELD_ID;
		$fields[1] = "a. " . self::DB_FIELD_EMP_NUMBER;
		$fields[2] = "a. " . self::DB_FIELD_REVIEW_DATE;
		$fields[3] = "CONCAT(b.`emp_firstname`, ' ', b.`emp_lastname`) AS " . self::FIELD_EMPLOYEE_NAME;
		$fields[4] = "a. " . self::DB_FIELD_STATUS;
		$fields[5] = "a. " . self::DB_FIELD_REVIEW_NOTES;
		
    	$review = new PerformanceReview($row[self::DB_FIELD_ID]);
    	
		$review->setId($row[self::DB_FIELD_ID]);
		$review->setEmpNumber($row[self::DB_FIELD_EMP_NUMBER]);
		$review->setReviewDate($row[self::DB_FIELD_REVIEW_DATE]);
		$review->setStatus($row[self::DB_FIELD_STATUS]);
		$review->setReviewNotes($row[self::DB_FIELD_REVIEW_NOTES]);
		$review->setEmployeeName($row[self::FIELD_EMPLOYEE_NAME]);
		$review->setNotificationSent($row[self::DB_FIELD_NOTIFICATION_SENT]);
	    return $review;
    }

}

class PerformanceReviewException extends Exception {
	const INVALID_PARAMETER = 0;
	const DB_ERROR = 1;
}

?>
