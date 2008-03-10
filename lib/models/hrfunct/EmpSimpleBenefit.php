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

/**
 * Class representing benefits assigned to employees
 */
class EmpSimpleBenefit {

	const TABLE_NAME = 'hs_hr_emp_benefit_simple';
    const DB_FIELD_ID = 'id';
	const DB_FIELD_EMP_NUMBER = 'emp_number';
	const DB_FIELD_BENEFIT_ID = 'benefit_id';
    const DB_FIELD_DESC = 'description';
    const DB_FIELD_AMOUNT = 'amount';
    const DB_FIELD_CURRENCY_ID = 'currency_id';

	private $id;
    private $empNumber;
    private $benefitId;
    private $description;
    private $amount;
    private $currencyId;

    private $benefitName;

	/**
	 * Constructor
	 */
	public function __construct() {
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
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Retrieves the value of benefit Id.
     * @return benefitId
     */
    public function getBenefitId() {
        return $this->benefitId;
    }

    /**
     * Sets the value of benefit Id.
     * @param benefitId
     */
    public function setBenefitId($benefitId) {
        $this->benefitId = $benefitId;
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
     * Retrieves the value of amount.
     * @return amount
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Sets the value of amount.
     * @param amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * Retrieves the value of currencyId.
     * @return currencyId
     */
    public function getCurrencyId() {
        return $this->currencyId;
    }

    /**
     * Sets the value of currencyId.
     * @param currencyId
     */
    public function setCurrencyId($currencyId) {
        $this->currencyId = $currencyId;
    }

    /**
     * Gets the benefit name
     * @return String Benefit name
     */
    public function getBenefitName() {
        if (is_null($this->benefitName) && !empty($this->benefitId)) {
            $benefit = SimpleBenefit::getSimpleBenefit($this->benefitId);
            $this->benefitName = $benefit->getName();
        }

        return $this->benefitName;
    }

	/**
	 * save a employee benefit to the database
	 */
    public function save() {
		if (!CommonFunctions::isValidId($this->empNumber)) {
			throw new EmpSimpleBenefitException("Invalid emp number", EmpSimpleBenefitException::INVALID_PARAMETER);
		}

        if (!CommonFunctions::isValidId($this->benefitId)) {
            throw new EmpSimpleBenefitException("Benefit ID invalid", EmpSimpleBenefitException::INVALID_PARAMETER);
        }

        if (!is_numeric($this->amount)) {
            throw new EmpSimpleBenefitException("Missing amount", EmpSimpleBenefitException::INVALID_PARAMETER);
        }

        if (empty($this->currencyId)) {
            throw new EmpSimpleBenefitException("Missing currencyId", EmpSimpleBenefitException::INVALID_PARAMETER);
        }

		if (isset($this->id)) {
			return $this->_update();
		} else {
			return $this->_insert();
		}
    }


	/**
	 * Get list of benefits for the given employee
     *
     * @param int $empNumber Employee Number
     * @return array Array of EmpSimpleBenefit objects
	 */
	public static function getBenefitsForEmployee($empNumber) {

        if (!CommonFunctions::isValidId($empNumber)) {
            throw new EmpSimpleBenefitException("Invalid empNumber: $empNumber", EmpSimpleBenefitException::INVALID_PARAMETER);
        }

        $selectCondition[] = self::DB_FIELD_EMP_NUMBER . " = $empNumber";
        return self::_getList($selectCondition);
	}

	/**
	 * Delete given Employee Benefits
	 * @param array $ids Array of EmpSimpleBenefit ids to delete
	 */
	public static function delete($ids) {

		$count = 0;
		if (!is_array($ids)) {
			throw new EmpSimpleBenefitException("Invalid parameter to delete(): ids should be an array", EmpSimpleBenefitException::INVALID_PARAMETER);
		}

		foreach ($ids as $id) {
			if (!CommonFunctions::isValidId($id)) {
				throw new EmpSimpleBenefitException("Invalid parameter to delete(): id = $id", EmpSimpleBenefitException::INVALID_PARAMETER);
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
	 * Get EmpSimpleBenefit with given ID
	 * @param int $id The EmpSimpleBenefit ID
	 * @return EmpSimpleBenefit EmpSimpleBenefit object with given id or null if not found
	 */
	public static function getEmpBenefit($id) {

		if (!CommonFunctions::isValidId($id)) {
			throw new EmpSimpleBenefitException("Invalid parameters to getEmpSimpleBenefit(): id = $id", EmpSimpleBenefitException::INVALID_PARAMETER);
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
        $fields[1] = self::DB_FIELD_EMP_NUMBER;
        $fields[2] = self::DB_FIELD_BENEFIT_ID;
        $fields[3] = self::DB_FIELD_DESC;
        $fields[4] = self::DB_FIELD_AMOUNT;
        $fields[5] = self::DB_FIELD_CURRENCY_ID;

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

		$fields[0] = self::DB_FIELD_EMP_NUMBER;
		$fields[1] = self::DB_FIELD_BENEFIT_ID;
        $fields[2] = self::DB_FIELD_DESC;
        $fields[3] = self::DB_FIELD_AMOUNT;
        $fields[4] = self::DB_FIELD_CURRENCY_ID;

		$values[0] = $this->empNumber;
        $values[1] = $this->benefitId;
        $values[2] = "'{$this->description}'";
        $values[3] = $this->amount;
        $values[4] = "'{$this->currencyId}'";

		$sqlBuilder = new SQLQBuilder();
		$sqlBuilder->table_name = self::TABLE_NAME;
		$sqlBuilder->flg_insert = 'true';
		$sqlBuilder->arr_insert = $values;
		$sqlBuilder->arr_insertfield = $fields;

		$sql = $sqlBuilder->addNewRecordFeature2();

		$conn = new DMLFunctions();

		$result = $conn->executeQuery($sql);
		if (!$result || (mysql_affected_rows() != 1)) {
			throw new EmpSimpleBenefitException("Insert failed. ", EmpSimpleBenefitException::DB_ERROR);
		}
        $this->id = mysql_insert_id();

		return $this->id;
	}

	/**
	 * Update existing object
	 */
	private function _update() {

        $fields[0] = self::DB_FIELD_ID;
        $fields[1] = self::DB_FIELD_EMP_NUMBER;
        $fields[2] = self::DB_FIELD_BENEFIT_ID;
        $fields[3] = self::DB_FIELD_DESC;
        $fields[4] = self::DB_FIELD_AMOUNT;
        $fields[5] = self::DB_FIELD_CURRENCY_ID;

        $values[0] = $this->id;
        $values[1] = $this->empNumber;
        $values[2] = $this->benefitId;
        $values[3] = "'{$this->description}'";
        $values[4] = $this->amount;
        $values[5] = "'{$this->currencyId}'";

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
			throw new EmpSimpleBenefitException("Update failed. SQL=$sql", EmpSimpleBenefitException::DB_ERROR);
		}
		return $this->id;
	}

    /**
     * Creates a EmpSimpleBenefit object from a resultset row
     *
     * @param array $row Resultset row from the database.
     * @return EmpSimpleBenefit EmpSimpleBenefit object.
     */
    private static function _createFromRow($row) {
    	$empBenefit = new EmpSimpleBenefit();
        $empBenefit->setId($row['id']);
        $empBenefit->setEmpNumber($row['emp_number']);
        $empBenefit->setBenefitId($row['benefit_id']);
        $empBenefit->setDescription($row['description']);
        $empBenefit->setAmount($row['amount']);
        $empBenefit->setCurrencyId($row['currency_id']);

        return $empBenefit;
    }

}

class EmpSimpleBenefitException extends Exception {
	const INVALID_PARAMETER = 0;
	const DB_ERROR = 1;
}

?>
