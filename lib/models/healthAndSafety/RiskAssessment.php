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
 * Class representing a Risk Assessment
 */
class RiskAssessment {

	const TABLE_NAME = 'hs_hr_risk_assessments';

	/** Database fields */
	const DB_FIELD_ID = 'id';
	const DB_FIELD_SUBDIVISION_ID = 'subdivision_id';
	const DB_FIELD_START_DATE = 'start_date';
	const DB_FIELD_END_DATE = 'end_date';
	const DB_FIELD_DESCRIPTION = 'description';
	const DB_FIELD_STATUS = 'status';

	/**
	 * Risk Assessment status
	 */
	const STATUS_UNRESOLVED = 0;
	const STATUS_RESOLVED = 1;

	/** Fields retrieved from other tables */
	const FIELD_SUBDIVISION_NAME = 'title';

	/** Field order */
	const SORT_FIELD_NONE = -1;
	const SORT_FIELD_ID = 0;
	const SORT_FIELD_SUBDIVISION_NAME = 1;
	const SORT_FIELD_START_DATE = 2;
	const SORT_FIELD_END_DATE = 3;
	const SORT_FIELD_DESCRIPTION = 4;
	const SORT_FIELD_STATUS = 5;

	private $dbFields = array (
		self :: DB_FIELD_ID,
		self :: DB_FIELD_SUBDIVISION_ID,
		self :: DB_FIELD_START_DATE,
		self :: DB_FIELD_END_DATE,
		self :: DB_FIELD_DESCRIPTION,
		self :: DB_FIELD_STATUS
	);

	private $id;
	private $subDivisionId;
	private $startDate;
	private $endDate;
	private $description;
	private $status;

	/**
	 * Attributes retrieved from other objects
	 */
	private $subdivisionName;

	/**
	 * Constructor
	 *
	 * @param int $id ID can be null for newly created risk assessments
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
	 * Retrieves the value of subDivisionId.
	 * @return subDivisionId
	 */
	public function getSubDivisionId() {
		return $this->subDivisionId;
	}

	/**
	 * Sets the value of subDivisionId.
	 * @param subDivisionId
	 */
	public function setSubDivisionId($subDivisionId) {
		$this->subDivisionId = $subDivisionId;
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
	 * Retrieves the value of subdivisionName.
	 * @return subdivisionName
	 */
	public function getSubdivisionName() {
		return $this->subdivisionName;
	}

	/**
	 * Sets the value of subdivisionName.
	 * @param subdivisionName
	 */
	public function setSubdivisionName($subdivisionName) {
		$this->subdivisionName = $subdivisionName;
	}

	/**
	 * Save RiskAssessment object to database
	 *
	 * If a new RiskAssessment, inserts into the database, otherwise, updates
	 * the existing entry.
	 *
	 * @return int Returns the ID of the RiskAssessment
	 */
	public function save() {

		if (empty ($this->subDivisionId)) {
			throw new RiskAssessmentException("Attributes not set", RiskAssessmentException :: MISSING_PARAMETERS);
		}

		if (!CommonFunctions :: isValidId($this->subDivisionId)) {
			throw new RiskAssessmentException("Invalid sub division Id", RiskAssessmentException :: INVALID_PARAMETER);
		}

		if (isset ($this->id)) {

			if (!CommonFunctions :: isValidId($this->id)) {
				throw new RiskAssessmentException("Invalid id", RiskAssessmentException :: INVALID_PARAMETER);
			}
			return $this->_update();
		} else {
			return $this->_insert();
		}
	}

	/**
	 * Get Risk Assessment with given id
	 *
	 * @param int $id Risk Assessment ID
	 * @return RiskAssessment RiskAssessment object
	 */
	public static function getRiskAssessment($id) {

		if (!CommonFunctions :: isValidId($id)) {
			throw new RiskAssessmentException("Invalid id", RiskAssessmentException :: INVALID_PARAMETER);
		}

		$conditions[] = 'a.' . self :: DB_FIELD_ID . ' = ' . $id;
		$list = self :: _getList($conditions);
		$assessment = (count($list) == 1) ? $list[0] : null;

		return $assessment;
	}

	/**
	 * Get list of risk assessments
	 * @return Array Array of RiskAssessment objects.
	 */
	public static function getAll() {
		return self :: _getList();
	}

	/**
	 * Get list of Risk Assessments in a format suitable for view.php
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

		$condition = self::_getSelectCondition($searchFieldNo, $searchStr);
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

			case self :: SORT_FIELD_ID :
				$sortBy = 'a.' . self :: DB_FIELD_ID;
				break;

			case self :: SORT_FIELD_SUBDIVISION_NAME :
				$sortBy = 'b.' . self :: FIELD_SUBDIVISION_NAME;
				break;

			case self :: SORT_FIELD_START_DATE :
				$sortBy = 'a.' . self :: DB_FIELD_START_DATE;
				break;

			case self :: SORT_FIELD_END_DATE :
				$sortBy = 'a.' . self :: DB_FIELD_END_DATE;
				break;

			case self :: SORT_FIELD_DESCRIPTION :
				$sortBy = 'a.' . self :: DB_FIELD_DESCRIPTION;
				break;

			case self :: SORT_FIELD_REVIEW_STATUS :
				$sortBy = 'a.' . self :: DB_FIELD_STATUS;
				break;
		}

		$list = self :: _getList($selectCondition, $sortBy, $sortOrder, $limit);

		$i = 0;
		$arrayDispList = null;

		foreach ($list as $assessment) {
			$arrayDispList[$i][0] = $assessment->getId();
			$arrayDispList[$i][1] = $assessment->getSubdivisionName();
			$arrayDispList[$i][2] = $assessment->getStartDate();
			$arrayDispList[$i][3] = $assessment->getEndDate();
			$arrayDispList[$i][4] = $assessment->getDescription();
			$arrayDispList[$i][5] = $assessment->getStatus();
			$i++;
		}

		return $arrayDispList;
	}

	/**
	 * Count Risk Assessments with given search conditions
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
		$sql = sprintf('SELECT count(*) FROM %s a, %s b WHERE a.' . self :: DB_FIELD_SUBDIVISION_ID . ' = b.id', self :: TABLE_NAME, 'hs_hr_compstructtree');

		if (!empty ($selectCondition)) {
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
	 * Delete given Risk Assessments
	 * @param array $ids Array of Risk Assessment ID's to delete
	 */
	public static function delete($ids) {

		$count = 0;
		if (!is_array($ids)) {
			throw new RiskAssessmentException("Invalid parameter to delete(): ids should be an array", RiskAssessmentException :: INVALID_PARAMETER);
		}

		foreach ($ids as $id) {
			if (!CommonFunctions :: isValidId($id)) {
				throw new RiskAssessmentException("Invalid parameter to delete(): id = $id", RiskAssessmentException :: INVALID_PARAMETER);
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
			throw new RiskAssessmentException("Insert failed. ", RiskAssessmentException :: DB_ERROR);
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
			throw new RiskAssessmentException("Update failed. SQL=$sql", RiskAssessmentException :: DB_ERROR);
		}
		return $this->id;
	}

	/**
	 * Get a list of Risk Assessments with the given conditions.
	 *
	 * @param array  $selectCondition Array of select conditions to use.
	 * @return array Array of RiskAssessment objects. Returns an empty (length zero) array if none found.
	 */
	private static function _getList($selectCondition = null, $sortBy = null, $sortOrder = null, $limit = null) {

		$fields[0] = 'a.' . self :: DB_FIELD_ID;
		$fields[1] = 'a.' . self :: DB_FIELD_SUBDIVISION_ID;
		$fields[2] = 'a.' . self :: DB_FIELD_START_DATE;
		$fields[3] = 'a.' . self :: DB_FIELD_END_DATE;
		$fields[4] = 'a.' . self :: DB_FIELD_DESCRIPTION;
		$fields[5] = 'a.' . self :: DB_FIELD_STATUS;
		$fields[6] = 'b.title AS ' . self :: FIELD_SUBDIVISION_NAME;

		$tables[0] = self :: TABLE_NAME . ' a';
		$tables[1] = 'hs_hr_compstructtree b';

		$joinConditions[1] = 'a.' . self :: DB_FIELD_SUBDIVISION_ID . ' = b.id ';

		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition, null, $sortBy, $sortOrder, $limit);

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
		$values[1] = $this->subDivisionId;
		$values[2] = is_null($this->startDate) ? 'null' : $this->startDate;
		$values[3] = is_null($this->endDate) ? 'null' : $this->endDate;
		$values[4] = $this->description;
		$values[5] = is_null($this->status) ? self :: STATUS_UNRESOLVED : $this->status;

		return $values;
	}

	/**
	 * Creates a RiskAssessment object from a resultset row
	 *
	 * @param array $row Resultset row from the database.
	 * @return RiskAssessment RiskAssessment object.
	 */
	private static function _createFromRow($row) {

		$assessment = new RiskAssessment($row[self :: DB_FIELD_ID]);
		$assessment->setSubDivisionId($row[self :: DB_FIELD_SUBDIVISION_ID]);
		$assessment->setStartDate($row[self :: DB_FIELD_START_DATE]);
		$assessment->setEndDate($row[self :: DB_FIELD_END_DATE]);
		$assessment->setDescription($row[self :: DB_FIELD_DESCRIPTION]);
		$assessment->setStatus($row[self :: DB_FIELD_STATUS]);

		if (isset ($row[self :: FIELD_SUBDIVISION_NAME])) {
			$assessment->setSubdivisionName($row[self :: FIELD_SUBDIVISION_NAME]);
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

				case self :: SORT_FIELD_SUBDIVISION_NAME :
					$selectCondition = "b.`title` LIKE '{$escapedVal}%'  ";
					break;

				case self :: SORT_FIELD_START_DATE :
					$selectCondition = "DATE(a." . self :: DB_FIELD_START_DATE . ") = '{$escapedVal}' ";
					break;

				case self :: SORT_FIELD_END_DATE :
					$selectCondition = "DATE(a." . self :: DB_FIELD_END_DATE . ") = '{$escapedVal}' ";
					break;

				case self :: SORT_FIELD_DESCRIPTION :
					$selectCondition = "a.`description` LIKE '{$escapedVal}%'  ";
					break;

				case self :: SORT_FIELD_STATUS :
					$selectCondition = "a." . self :: DB_FIELD_STATUS . " = '{$escapedVal}' ";
					break;
			}
			return $selectCondition;
		}
	}
}

class RiskAssessmentException extends Exception {
	const INVALID_PARAMETER = 0;
	const MISSING_PARAMETERS = 1;
	const DB_ERROR = 2;
	const INVALID_STATUS = 3;
}
?>

