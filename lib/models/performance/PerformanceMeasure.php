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

class PerformanceMeasure {

	const TABLE_NAME = 'hs_hr_perf_measure';
	const MEASURE_JOBTITLE_TABLE_NAME = 'hs_hr_perf_measure_jobtitle';

	/** Database fields */
	const DB_FIELD_ID = 'id';
	const DB_FIELD_NAME = 'name';
	const DB_FIELD_PERF_MEASURE_ID = 'perf_measure_id';
	const DB_FIELD_JOB_TITLE_CODE = 'jobtit_code';

	const FIELD_JOB_TITLE_NAME = 'jobtit_name';

	/** Field order */
	const SORT_FIELD_NONE = -1;
	const SORT_FIELD_ID = 0;
	const SORT_FIELD_NAME = 1;
	
	private $id;
	private $name;
	private $jobTitles;

	/**
	 * Constructor
	 *
	 * @param int $id ID (can be null for newly created objects)
	 */
	public function __construct($id = null) {
		$this->id = $id;
		$this->jobTitles = array();
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setName($name) {
	    $this->name = $name;
	}

	public function setJobTitles($jobTitles) {
		$this->jobTitles = $jobTitles;
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
	    return $this->name;
	}
	
	public function getJobTitles() {
		return $this->jobTitles;
	}

	/**
	 * Save Performance Measure object to database
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
	 * Delete given performance measures
	 * @param array $ids Array of Performance Measure ID's to delete
	 */
	public static function delete($ids) {

		$count = 0;
		if (!is_array($ids)) {
			throw new PerformanceMeasureException("Invalid parameter to delete(): ids should be an array", PerformanceMeasureException::INVALID_PARAMETER);
		}

		foreach ($ids as $id) {
			if (!CommonFunctions::isValidId($id)) {
				throw new PerformanceMeasureException("Invalid parameter to delete(): id = $id", PerformanceMeasureException::INVALID_PARAMETER);
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
	 * Get all performance measures available in the system
	 *
	 * @return array Array of Performance Measure objects
	 */
	public static function getAll() {
		return self::_getList();
	}

	/**
	 * Get Performance Measure with given ID
	 * @param int $id The Performance Measure ID
	 * @return PerformanceMeasure Performance Measure object with given id or null if not found
	 */
	public static function getPerformanceMeasure($id) {

		if (!CommonFunctions::isValidId($id)) {
			throw new PerformanceMeasureException("Invalid parameters to getPerformanceMeasure(): id = $id", PerformanceMeasureException::INVALID_PARAMETER);
		}

		$selectCondition[] = self::DB_FIELD_ID . " = $id";
		$list = self::_getList($selectCondition);
		
		$measure = null;
		if (count($list) > 0) {
			$measure = $list[0];
			$measure->setJobTitles(self::_fetchJobTitles($id));
		}
		
		return $measure;
	}
	
	/**
	 * Get list of job vacancies in a format suitable for view.php
	 * TODO: To be implemented
	 *
	 * @param int $pageNo The page number. 0 to fetch all
	 * @param string $searchStr The search string
	 * @param int $searchfieldNo which field to search on
	 * @param int $sortField The field to sort by
	 * @param string $sortOrder Sort Order (one of ASC or DESC)
	 */
	public static function getListForView($pageNO = 0, $searchStr = '', $searchFieldNo = self::SORT_FIELD_NONE, $sortField = self::SORT_FIELD_VACANCY_ID, $sortOrder = 'ASC') {

		$list = self::_getList();
		
		$i = 0;
		$arrayDispList = null;
		
		foreach($list as $measure) {
			$arrayDispList[$i][0] = $measure->getId();
	    	$arrayDispList[$i][1] = $measure->getName();
	    	$i++;
	     }

		return $arrayDispList;
	}

	/**
	 * Count performance measures with given search conditions
	 * 
	 * TODO: To be implemented
	 * 
	 * @param string $searchStr Search string
	 * @param string $searchFieldNo Integer giving which field to search on
	 */
	public static function getCount($searchStr = '', $searchFieldNo = self::SORT_FIELD_NONE) {

		$count = 0;		
		$sql = sprintf('SELECT count(*) FROM %s', self::TABLE_NAME);

		$sqlBuilder = new SQLQBuilder();
		$dbConnection = new DMLFunctions();
		$result = $dbConnection->executeQuery($sql);

		if ($result) {
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$count = $line[0];
		}

	    return $count;
	}
		
	/**
	 * Get list of job titles assigned to this performance measure
	 */
	private static function _fetchJobTitles($performanceMeasureId) {

		$fields[0] = "a. " . self::DB_FIELD_JOB_TITLE_CODE;
		$fields[1] = "b.jobtit_name AS " . self::FIELD_JOB_TITLE_NAME;

		$tables[0] = self::MEASURE_JOBTITLE_TABLE_NAME . ' a';
		$tables[1] = 'hs_hr_job_title b';

		$joinConditions[1] = 'a.jobtit_code = b.jobtit_code';

		$selectCondition[] = "a." . self::DB_FIELD_PERF_MEASURE_ID . " = " . $performanceMeasureId;
		
		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition);

		$jobTitles = array();

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		while ($result && ($row = mysql_fetch_assoc($result))) {
			$jobTitleCode = $row[self::DB_FIELD_JOB_TITLE_CODE];
			$jobTitles[$jobTitleCode] = $row;
		}

		return $jobTitles;	
	}
	
	/**
	 * Save job titles assigned to this performance measure
	 */
	private function _saveJobTitles() {
		
		// Delete existing job title assignments		
		$sql = sprintf("DELETE FROM %s WHERE %s = %s", self::MEASURE_JOBTITLE_TABLE_NAME,
		                self::DB_FIELD_PERF_MEASURE_ID, $this->id);
		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);
				
		// Assign new job titles
		if (!empty($this->jobTitles)) {
			$sql = sprintf("INSERT INTO %s (%s, %s) VALUES " , self::MEASURE_JOBTITLE_TABLE_NAME, 
				self::DB_FIELD_PERF_MEASURE_ID, self::DB_FIELD_JOB_TITLE_CODE);
			
			$valueSql = "";
			foreach ($this->jobTitles as $jobTitle) {
				$jobTitleCode = $jobTitle['jobtit_code'];
				
				if (!empty($valueSql)) {
					$valueSql .= ', ';
				}
				$valueSql .= sprintf("(%d, '%s')", $this->id, $jobTitleCode);				
			}
			
			$sql .= $valueSql;
			$result = $conn->executeQuery($sql);
			if (!$result) {
				throw new PerformanceMeasureException("Save job titles failed. SQL=$sql", PerformanceMeasureException::DB_ERROR);
			}			
		}		
	}

	/**
	 * Get a list of performance measures with the given conditions.
	 *
	 * @param array   $selectCondition Array of select conditions to use.				const  = 'hs_hr_perf_measure_jobtitle';
	 * @return array  Array of Performance Measure objects. Returns an empty (length zero) array if none found.
	 */
	private static function _getList($selectCondition = null) {

		$fields[0] = self::DB_FIELD_ID;
		$fields[1] = self::DB_FIELD_NAME;

		$arrTable = self::TABLE_NAME;
		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->simpleSelect($arrTable, $fields, $selectCondition, $fields[0], 'ASC');

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
		$fields[0] = self::DB_FIELD_ID;
		$fields[1] = self::DB_FIELD_NAME;

		$values[0] = $this->id;
		$values[1] = "'{$this->name}'";

		$sqlBuilder = new SQLQBuilder();
		$sqlBuilder->table_name = self::TABLE_NAME;
		$sqlBuilder->flg_insert = 'true';
		$sqlBuilder->arr_insert = $values;
		$sqlBuilder->arr_insertfield = $fields;

		$sql = $sqlBuilder->addNewRecordFeature2();

		$conn = new DMLFunctions();

		$result = $conn->executeQuery($sql);
		if (!$result || (mysql_affected_rows() != 1)) {
			throw new PerformanceMeasureException("Insert failed. ", PerformanceMeasureException::DB_ERROR);
		}

		$this->_saveJobTitles();
		return $this->id;
	}

	/**
	 * Update existing object
	 */
	private function _update() {

		$fields[0] = self::DB_FIELD_ID;
		$fields[1] = self::DB_FIELD_NAME;

		$values[0] = $this->id;
		$values[1] = "'{$this->name}'";

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
			throw new PerformanceMeasureException("Update failed. SQL=$sql", PerformanceMeasureException::DB_ERROR);
		}
		$this->_saveJobTitles();		
		return $this->id;
	}

    /**
     * Creates a Performance Measure object from a resultset row
     *
     * @param array $row Resultset row from the database.
     * @return PerformanceMeasure PerformanceMeasure object.
     */
    private static function _createFromRow($row) {

    	$measure = new PerformanceMeasure($row[self::DB_FIELD_ID]);
		$measure->setName($row[self::DB_FIELD_NAME]);
	    return $measure;
    }

}

class PerformanceMeasureException extends Exception {
	const INVALID_PARAMETER = 0;
	const DB_ERROR = 1;
}

?>
