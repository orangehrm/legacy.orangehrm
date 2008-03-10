<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */

// Call EmpSimpleBenefit::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "EmpSimpleBenefit::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once "testConf.php";
require_once ROOT_PATH."/lib/confs/Conf.php";
require_once ROOT_PATH."/lib/confs/sysConf.php";
require_once ROOT_PATH."/lib/models/eimadmin/SimpleBenefit.php";
require_once ROOT_PATH."/lib/models/hrfunct/EmpSimpleBenefit.php";
require_once ROOT_PATH."/lib/common/UniqueIDGenerator.php";

/**
 * Test class for EmpSimpleBenefit
 */
class EmpSimpleBenefitTest extends PHPUnit_Framework_TestCase {

	private $benefits;
    private $empBenefits;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("EmpSimpleBenefitTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, making sure table is empty and creating database
     * entries needed during test.
     *
     * @access protected
     */
    protected function setUp() {

    	$conf = new Conf();
    	$this->connection = mysql_connect($conf->dbhost.":".$conf->dbport, $conf->dbuser, $conf->dbpass);
        mysql_select_db($conf->dbname);

        $this->_deleteTables();

		// Insert data for tests
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
                    "VALUES(11, '0011', 'Rajasinghe', 'Saman', 'Marlon')");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
                    "VALUES(12, '0022', 'Jayasinghe', 'Aruna', 'Shantha')");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
                    "VALUES(13, '0042', 'Jayaweera', 'Nimal', 'T')");

		$this->benefits[1] = $this->_getSimpleBenefit(1, 'Health Insurance');
        $this->benefits[2] = $this->_getSimpleBenefit(2, 'Tuition Assistance');
		$this->benefits[3] = $this->_getSimpleBenefit(3, 'Training');
		$this->benefits[4] = $this->_getSimpleBenefit(4, 'Travel');
		$this->_createSimpleBenefits($this->benefits);

        $this->empBenefits[] = $this->_getEmpSimpleBenefit(11, 1, 'Medicine', 121.19, 'USD');
        $this->empBenefits[] = $this->_getEmpSimpleBenefit(11, 1, 'Lab Tests', 100, 'USD');
        $this->empBenefits[] = $this->_getEmpSimpleBenefit(11, 3, 'Leadership Training', 202.20, 'USD');
        $this->empBenefits[] = $this->_getEmpSimpleBenefit(12, 1, 'Travel to conference', '393.29', 'AUD');
        $this->_createEmpSimpleBenefits($this->empBenefits);

		UniqueIDGenerator::getInstance()->resetIDs();
    }

    /**
     * Tears down the fixture, removed database entries created during test.
     *
     * @access protected
     */
    protected function tearDown() {
        $this->_deleteTables();
		UniqueIDGenerator::getInstance()->resetIDs();
    }

    /**
     * Delete data created during test
     */
    private function _deleteTables() {
        $this->_runQuery("TRUNCATE TABLE `hs_hr_emp_benefit_simple`");
        $this->_runQuery("TRUNCATE TABLE `hs_hr_benefit_simple`");
        $this->_runQuery("TRUNCATE TABLE `hs_hr_employee`");
    }

	/**
	 * test the EmpSimpleBenefit delete function.
	 */
	public function testDelete() {

		$before = $this->_getNumRows();

        foreach ($this->empBenefits as $empBen) {
            $ids[] = $empBen->getId();
        }

        // find array of id's that are not available in database
        $notIds = array_values(array_diff(range(1, 14), $ids));

		// invalid params
		try {
			EmpSimpleBenefit::delete(34);
			$this->fail("Exception not thrown");
		} catch (EmpSimpleBenefitException $e) {

		}

		// invalid params
		try {
			EmpSimpleBenefit::delete(array(1, 'w', 12));
			$this->fail("Exception not thrown");
		} catch (EmpSimpleBenefitException $e) {

		}

		// empty array
		$res = EmpSimpleBenefit::delete(array());
		$this->assertEquals(0, $res);
        $this->assertEquals($before, $this->_getNumRows());

		// no matches
		$res = EmpSimpleBenefit::delete(array($notIds[1], $notIds[4]));
		$this->assertEquals(0, $res);
        $this->assertEquals($before, $this->_getNumRows());

		// one match
		$res = EmpSimpleBenefit::delete(array($ids[0], $notIds[3]));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// one more the rest
		$res = EmpSimpleBenefit::delete(array($ids[2]));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// rest
		$res = EmpSimpleBenefit::delete(array($ids[1], $ids[3]));
		$this->assertEquals(2, $res);
		$this->assertEquals(2, $before - $this->_getNumRows());

	}

	/**
	 * Test the save function
	 */
	public function testSave() {

		// empNum missing
		$before = $this->_getNumRows();
		$benefit = $this->_getEmpSimpleBenefit(null, 1, 'Tst', 100.12, 'AUD');

		try {
			$benefit->save();
			$this->fail('Should throw exception');
		} catch (EmpSimpleBenefitException $e) {
		}
		$this->assertEquals($before, $this->_getNumRows());

        // benefit id missing
        $benefit = $this->_getEmpSimpleBenefit(13, null, 'Tst', 100.12, 'AUD');

        try {
            $benefit->save();
            $this->fail('Should throw exception');
        } catch (EmpSimpleBenefitException $e) {
        }
        $this->assertEquals($before, $this->_getNumRows());

        // amount missing
        $benefit = $this->_getEmpSimpleBenefit(13, 1, 'Tst', null, 'AUD');

        try {
            $benefit->save();
            $this->fail('Should throw exception');
        } catch (EmpSimpleBenefitException $e) {
        }
        $this->assertEquals($before, $this->_getNumRows());

        // currency id missing
        $benefit = $this->_getEmpSimpleBenefit(13, 1, 'Tst', 292, null);

        try {
            $benefit->save();
            $this->fail('Should throw exception');
        } catch (EmpSimpleBenefitException $e) {
        }
        $this->assertEquals($before, $this->_getNumRows());

        // Invalid amount
        $benefit = $this->_getEmpSimpleBenefit(13, 1, 'Tst', '12A', 'AUD');

        try {
            $benefit->save();
            $this->fail('Should throw exception');
        } catch (EmpSimpleBenefitException $e) {
        }
        $this->assertEquals($before, $this->_getNumRows());

		// new
		$before = $this->_getNumRows();
		$benefit = $this->_getEmpSimpleBenefit(13, 1, 'A benefit', 121.93, 'USD');
		$id = $benefit->save();
		$this->assertEquals(($before + 1), $this->_getNumRows());
		$this->assertEquals(1, $this->_getNumRows("emp_number = 13 AND benefit_id = 1 AND amount = 121.93 AND description = 'A benefit' AND id = $id AND currency_id = 'USD'"));

		// update
		$before = $this->_getNumRows();
		$benefit = $this->_getEmpSimpleBenefit(13, 1, 'New desc', 122.11, 'AUD');
        $benefit->setId($id);
		$newId = $benefit->save();
		$this->assertEquals($id, $newId);
		$this->assertEquals($before, $this->_getNumRows());
		$this->assertEquals(1, $this->_getNumRows("emp_number = 13 AND benefit_id = 1 AND amount = 122.11 AND description = 'New desc' AND id = $id AND currency_id = 'AUD'"));

		// update without amount
		$before = $this->_getNumRows();
		$benefit = $this->_getEmpSimpleBenefit(13, 1, 'Abc', null, 'USD');
		try {
			$benefit->save();
			$this->fail('Should throw exception');
		} catch (EmpSimpleBenefitException $e) {
		}
		$this->assertEquals($before, $this->_getNumRows());

        // Add second benefit of same type for same employee
        $before = $this->_getNumRows();
        $benefit = $this->_getEmpSimpleBenefit(13, 1, 'A second benefit', 11.93, 'USD');
        $id = $benefit->save();
        $this->assertEquals(($before + 1), $this->_getNumRows());
        $this->assertEquals(1, $this->_getNumRows("emp_number = 13 AND benefit_id = 1 AND amount = 11.93 AND description = 'A second benefit' AND id = $id AND currency_id = 'USD'"));
        $this->assertEquals(2, $this->_getNumRows("emp_number = 13 AND benefit_id = 1"));

	}

	/**
	 * Test the getEmpBenefit function
	 */
	public function testGetEmpBenefit() {

        foreach ($this->empBenefits as $empBen) {
            $ids[] = $empBen->getId();
        }
        $notIds = array_values(array_diff(range(1, 14), $ids));

		// unknown id
		$benefit = EmpSimpleBenefit::getEmpBenefit($notIds[1]);
		$this->assertNull($benefit);

		// invalid id
		try {
			$benefit = EmpSimpleBenefit::getEmpBenefit('7da');
			$this->fail('Should throw exception');
		} catch (EmpSimpleBenefitException $e) {
		}

		// available benefit
		$benefit = EmpSimpleBenefit::getEmpBenefit($ids[1]);
		$this->assertNotNull($benefit);
		$this->assertTrue($this->empBenefits[1] == $benefit);

        // check getName
        $this->assertEquals('Health Insurance', $benefit->getBenefitName());
	}


	/**
	 * test the getBenefitsForEmployee function
	 */
	public function testGetBenefitsForEmployee() {

        // Invalid ID
        try {
            $list = EmpSimpleBenefit::getBenefitsForEmployee('a2a');
            $this->fail('Should throw exception');
        } catch (EmpSimpleBenefitException $e) {
        }

		// Employee with multiple benefits
		$list = EmpSimpleBenefit::getBenefitsForEmployee(11);
		$this->assertTrue(is_array($list));
		$this->assertEquals(3, count($list));
		$this->_compareEmpBenefits(array($this->empBenefits[0], $this->empBenefits[1], $this->empBenefits[2]), $list);

        // Employee with single benefit
        $list = EmpSimpleBenefit::getBenefitsForEmployee(12);
        $this->assertTrue(is_array($list));
        $this->assertEquals(1, count($list));
        $this->_compareEmpBenefits(array($this->empBenefits[3]), $list);

		// Employee with no benefits
        $list = EmpSimpleBenefit::getBenefitsForEmployee(13);
        $this->assertTrue(is_array($list));
        $this->assertEquals(0, count($list));
	}

    /**
     * Returns the number of rows in the hs_hr_benefit_simple table
     *
     * @param  string $where where clause
     * @return int number of rows
     */
    private function _getNumRows($where = null) {

    	$sql = "SELECT COUNT(*) FROM hs_hr_emp_benefit_simple";
    	if (!empty($where)) {
    		$sql .= " WHERE " . $where;
    	}
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result, MYSQL_NUM);
        $count = $row[0];
		return $count;
    }

    /**
     * Compares two arrays of EmpSimpleBenefit objects verifing they contain the same
     * objects
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareEmpBenefits($expected, $result) {
    	$this->assertEquals(count($expected), count($result));

        $i = 0;
		foreach ($result as $empBenefit) {
			$this->assertTrue($empBenefit instanceof EmpSimpleBenefit, "Should return EmpSimpleBenefit objects");
			$this->assertEquals($expected[$i], $empBenefit);
            $i++;
		}
    }

    /**
     * Checks that the attributes of the EmpSimpleBenefit object and the database row match.
     *
     * @param EmpSimpleBenefit $benefit
     * @param array  $row
     */
    private function _checkRow($benefit, $row) {
		$this->assertEquals($benefit->getId(), $row['id']);
		$this->assertEquals($benefit->getEmpNumber(), $row['emp_number']);
        $this->assertEquals($benefit->getBenefitId(), $row['benefit_id']);
        $this->assertEquals($benefit->getDescription(), $row['description']);
        $this->assertEquals($benefit->getAmount(), $row['amount']);
        $this->assertEquals($benefit->getCurrencyId(), $row['currency_id']);
    }

    /**
     * Create a SimpleBenefit object with the passed parameters
     */
    private function _getEmpSimpleBenefit($empNum, $benefitId, $description, $amount, $currencyId) {

        $empBenefit = new EmpSimpleBenefit();
        $empBenefit->setEmpNumber($empNum);
        $empBenefit->setBenefitId($benefitId);
        $empBenefit->setDescription($description);
        $empBenefit->setAmount($amount);
        $empBenefit->setCurrencyId($currencyId);
        return $empBenefit;
    }


    /**
     * Saves the given EmpSimpleBenefit objects in the database
     *
     * @param array $empBenefits Array of EmpSimpleBenefit objects to
     * save.
     */
    private function _createEmpSimpleBenefits(&$empBenefits) {
        foreach ($empBenefits as $empBenefit) {
            $sql = sprintf("INSERT INTO hs_hr_emp_benefit_simple(emp_number, benefit_id, " .
                           "description, amount, currency_id) " .
                           "VALUES(%d, %d, '%s', %f, '%s')",
                           $empBenefit->getEmpNumber(), $empBenefit->getBenefitId(),
                           $empBenefit->getDescription(), $empBenefit->getAmount(), $empBenefit->getCurrencyId());
            $this->_runQuery($sql);
            $empBenefit->setId(mysql_insert_id());
        }
    }


    /**
     * Create a SimpleBenefit object with the passed parameters
     */
    private function _getSimpleBenefit($id, $name) {
    	$benefit = new SimpleBenefit($id);
    	$benefit->setName($name);
    	return $benefit;
    }

    /**
     * Saves the given SimpleBenefit objects in the database
     *
     * @param array $benefits Array of SimpleBenefit objects to save.
     */
    private function _createSimpleBenefits($benefits) {
		foreach ($benefits as $benefit) {
			$sql = sprintf("INSERT INTO hs_hr_benefit_simple(id, name) " .
                           "VALUES(%d, '%s')",
                           $benefit->getId(), $benefit->getName());
            $this->_runQuery($sql);
		}
        UniqueIDGenerator::getInstance()->initTable();
    }

    /**
     * Run given sql query, checking the return value
     */
    private function _runQuery($sql) {
        $this->assertTrue(mysql_query($sql), mysql_error());
    }

}

// Call EmpSimpleBenefit::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "EmpSimpleBenefit::main") {
    EmpSimpleBenefit::main();
}
?>
