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
require_once ROOT_PATH . '/lib/models/hrfunct/EmpSimpleBenefit.php';

class SimpleBenefit {

	const TABLE_NAME = 'hs_hr_benefit_simple';
	const DB_FIELD_ID = 'id';
	const DB_FIELD_NAME = 'name';

	/** Field order */
	const SORT_FIELD_NONE = -1;
	const SORT_FIELD_ID = 0;
	const SORT_FIELD_NAME = 1;

	private $id;
	private $name;

	/**
	 * Constructor
	 *
	 * @param int     $id ID (can be null for newly created Benefits)
	 */
	public function __construct($id = null) {
		$this->id = $id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * Save SimpleBenefit object to database
	 * @return int Returns the ID of the SimpleBenefit
	 */
    public function save() {
		if (empty($this->name)) {
			throw new SimpleBenefitException("Attributes not set", SimpleBenefitException::INVALID_PARAMETER);
		}

		if (isset($this->id)) {
			return $this->_update();
		} else {
			return $this->_insert();
		}
    }

	/**
	 * Get list of benefits in a format suitable for view.php
	 */
	public static function getListForView($pageNO = 0, $searchStr = '', $searchFieldNo = self::SORT_FIELD_NONE, $sortField = self::SORT_FIELD_ID, $sortOrder = 'ASC') {

		$dbConnection = new DMLFunctions();
		
		$fields[0] = 'a.' . self::DB_FIELD_ID;
		$fields[1] = 'a.' . self::DB_FIELD_NAME;
		$fields[2] = 'COUNT(b.' . EmpSimpleBenefit::DB_FIELD_EMP_NUMBER . ') as NUMINUSE';

		$tables[0] = self::TABLE_NAME . ' a';
		$tables[1] = EmpSimpleBenefit::TABLE_NAME . ' b';

		$joinConditions[1] = 'a.' . self::DB_FIELD_ID . ' = b.' . EmpSimpleBenefit::DB_FIELD_BENEFIT_ID;
		
		$sysConst = new sysConf();
		$limit = null;
		if ($pageNO > 0) {
			$pageNO--;
			$pageNO *= $sysConst->itemsPerPage;
			$limit = "{$pageNO}, {$sysConst->itemsPerPage}";
		}

		$sortBy = null;
		if (($sortField >= 0) && ($sortField < count($fields))) {
			$sortBy = $fields[$sortField];
		}

		$selectConditions = null;
        if (($searchFieldNo >= 0) && ($searchFieldNo < count($fields)) && (trim($searchStr) != '')) {
	    	$filteredSearch = mysql_real_escape_string($searchStr);
	    	$selectConditions[] = "{$fields[$searchFieldNo]} LIKE '%" . $filteredSearch . "%'";
        }

		$groupBy =  'a.' . self::DB_FIELD_ID . ', ' . 'a.' . self::DB_FIELD_NAME;
		$sqlBuilder = new SQLQBuilder();
		$sqlQString = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectConditions, null, $sortBy, $sortOrder, $limit, $groupBy);
		
		$result = $dbConnection->executeQuery($sqlQString);

		$i = 0;
		$arrayDispList = null;
		while ($line = mysql_fetch_assoc($result)) {
			$arrayDispList[$i][0] = $line[self::DB_FIELD_ID];
	    	$arrayDispList[$i][1] = $line[self::DB_FIELD_NAME];
	    	$arrayDispList[$i]['inuse'] = ($line['NUMINUSE'] > 0) ? true : false;
	    	$i++;
	     }

		return $arrayDispList;
	}

	/**
	 * Count benefits with given search conditions
	 * @param string $schStr Search string
	 * @param string $mode Integer giving which field to search on
	 */
	public static function getCount($schStr = '', $mode = -1) {

		$count = 0;

		$arrFieldList[0] = self::DB_FIELD_ID;
		$arrFieldList[1] = self::DB_FIELD_NAME;

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = self::TABLE_NAME;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->countResultset($schStr,$mode);

		$dbConnection = new DMLFunctions();

		$result = $dbConnection->executeQuery($sqlQString);

		if ($result) {
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$count = $line[0];
		}

	    return $count;
	}

	/**
	 * Delete given benefits
	 * @param array $ids Array of benefit ids to delete
	 */
	public static function delete($ids) {

		$count = 0;
		if (!is_array($ids)) {
			throw new SimpleBenefitException("Invalid parameter to delete(): ids should be an array", SimpleBenefitException::INVALID_PARAMETER);
		}

		foreach ($ids as $id) {
			if (!CommonFunctions::isValidId($id)) {
				throw new SimpleBenefitException("Invalid parameter to delete(): id = $id", SimpleBenefitException::INVALID_PARAMETER);
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
	 * Get all benefits available in the system
	 *
	 * @return array Array of SimpleBenefit objects
	 */
	public static function getAll() {
		return self::_getList();
	}

	/**
	 * Get benefit with given ID
	 * @param int $id The benefit ID
	 * @return SimpleBenefit SimpleBenefitc object with given id or null if not found
	 */
	public static function getSimpleBenefit($id) {

		if (!CommonFunctions::isValidId($id)) {
			throw new SimpleBenefitException("Invalid parameters to getSimpleBenefit(): id = $id", SimpleBenefitException::INVALID_PARAMETER);
		}

		$selectCondition[] = self::DB_FIELD_ID . " = $id";
		$actList = self::_getList($selectCondition);
		$obj = count($actList) == 0 ? null : $actList[0];
		return $obj;
	}


	/**
	 * Get a list of benefits with the given conditions.
	 *
	 * @param array   $selectCondition Array of select conditions to use.
	 * @return array  Array of SimpleBenefit objects. Returns an empty (length zero) array if none found.
	 */
	private static function _getList($selectCondition = null) {

		$fields[0] = self::DB_FIELD_ID;
		$fields[1] = self::DB_FIELD_NAME;

		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->simpleSelect(self::TABLE_NAME, $fields, $selectCondition);

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

		$fields[0] = self::DB_FIELD_ID;
		$fields[1] = self::DB_FIELD_NAME;

		$this->id = UniqueIDGenerator::getInstance()->getNextID(self::TABLE_NAME, self::DB_FIELD_ID);
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
			throw new SimpleBenefitException("Insert failed. ", SimpleBenefitException::DB_ERROR);
		}

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
			throw new SimpleBenefitException("Update failed. SQL=$sql", SimpleBenefitException::DB_ERROR);
		}
		return $this->id;
	}

    /**
     * Creates a SimpleBenefit object from a resultset row
     *
     * @param array $row Resultset row from the database.
     * @return SimpleBenefit SimpleBenefit object.
     */
    private static function _createFromRow($row) {
    	$benefit = new SimpleBenefit($row[self::DB_FIELD_ID]);
        $benefit->setName($row[self::DB_FIELD_NAME]);
        return $benefit;
    }

}

class SimpleBenefitException extends Exception {
	const INVALID_PARAMETER = 0;
	const DB_ERROR = 1;
}

?>
