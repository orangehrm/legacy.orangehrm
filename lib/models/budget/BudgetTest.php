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

// Call BudgetTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "BudgetTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once "testConf.php";
require_once ROOT_PATH."/lib/confs/Conf.php";
require_once ROOT_PATH."/lib/confs/sysConf.php";
require_once ROOT_PATH."/lib/models/budget/Budget.php";
require_once ROOT_PATH."/lib/common/UniqueIDGenerator.php";
require_once ROOT_PATH."/lib/common/LocaleUtil.php";

/**
 * Test class for Budget
 */
class BudgetTest extends PHPUnit_Framework_TestCase {

	private $budgets;
	
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("BudgetTest");
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

		// Create Budgets
		$budgetType = Budget::BUDGET_TYPE_SALARY;
				
		for ($i=1; $i<11; $i++) {
			$startDiff = $i + 1;
			$endDiff = $i + 5;
	        $startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-{$startDiff} days"));
    	    $endDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+{$endDiff} days"));
			$budgetValue = $i;
			$budgetUnit = "U_{$i}";
			$notes = "NOTES $i";
			if ($budgetType > Budget::BUDGET_TYPE_COMPANY) {
				$budgetType = Budget::BUDGET_TYPE_SALARY;
			}

			if ($i % 2) {
				// Odd
				$status = Budget::STATUS_APPROVED;
			} else {	
				// Even			
				$status = Budget::STATUS_NOT_APPROVED;
			}

			$this->budget[$i] = $this->_getBudget($i, $budgetType, $budgetUnit, $budgetValue, $startDate, $endDate, $status, $notes);
			$budgetType++;
		}  														
		$this->_createBudgets($this->budget);

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

	private function _deleteTables() {
		$this->_runQuery("TRUNCATE TABLE `hs_hr_budgets`");		
	}

	/**
	 * Test the save function
	 */
	public function testSave() {

		// new
		$before = $this->_getNumRows();
		$startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-5 days"));
		$endDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-1 days"));
		
		$budget = $this->_getBudget(null, Budget::BUDGET_TYPE_SALARY, "\$", '100', $startDate, $endDate, Budget::STATUS_APPROVED, 'notes');

		$id = $budget->save();
		$this->assertEquals(($before + 1), $this->_getNumRows());
		$this->_checkExistsInDb($budget);

		// update
		$before = $this->_getNumRows();
		$startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$endDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-2 days"));
		
		$budget = $this->_getBudget($id, Budget::BUDGET_TYPE_COMPANY, "Xyz", '1100', $startDate, $endDate, Budget::STATUS_NOT_APPROVED, 'more notes');

		$newId = $budget->save();
		$this->assertEquals($id, $newId);
		$this->assertEquals($before, $this->_getNumRows());
		$this->_checkExistsInDb($budget);

		// Invalid ID	
		$budget = $this->_getBudget('a1', Budget::BUDGET_TYPE_COMPANY, "Xyz", '1100', $startDate, $endDate, Budget::STATUS_NOT_APPROVED, 'nooote');	
		try {
			$budget->save();
			$this->fail("Exception expected");
		} catch (BudgetException $e) {
			$this->assertEquals(BudgetException::INVALID_PARAMETER, $e->getCode());
		}
	}

    /**
     * Test for function getAll()
     */
    public function testGetAll() {

        $list = Budget::getAll();
        $this->_compareBudgets($this->budget, $list);
        
        // Delete all
        $this->_runQuery("Delete from hs_hr_budgets");
        $list = Budget::getAll();
        $this->assertTrue(is_array($list));
        $this->assertEquals(0, count($list));
    }

    /**
     * Test the GetBudget function
     */
    public function testGetBudget() {

		// Invalid id
		try {
        	$budget = Budget::getBudget('3e');
			$this->fail("Exception expected");
		} catch (BudgetException $e) {
			$this->assertEquals(BudgetException::INVALID_PARAMETER, $e->getCode());
		}		
		
		// Existing id's
        $budget = Budget::getBudget(1);
        $this->assertEquals($this->budget[1], $budget);


		// Non existing id
        $budget = Budget::getBudget(111);
        $this->assertNull($budget);
    }

	/**
	 * test the Budget delete function.
	 */
	public function testDelete() {

		$before = $this->_getNumRows();

		// invalid params
		try {
			Budget::delete(34);
			$this->fail("Exception not thrown");
		} catch (BudgetException $e) {
			$this->assertEquals(BudgetException::INVALID_PARAMETER, $e->getCode());
		}

		// invalid params
		try {
			Budget::delete(array(1, 'w', 12));
			$this->fail("Exception not thrown");
		} catch (BudgetException $e) {
			$this->assertEquals(BudgetException::INVALID_PARAMETER, $e->getCode());
		}

		// empty array
		$res = Budget::delete(array());
		$this->assertEquals(0, $res);
		$this->assertEquals($before, $this->_getNumRows());

		// no matches
		$res = Budget::delete(array(12, 22));
		$this->assertEquals(0, $res);
		$this->assertEquals($before, $this->_getNumRows());

		// one match
		$res = Budget::delete(array(1, 21));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// one more
		$res = Budget::delete(array(3));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// rest
		$res = Budget::delete(array(2, 4, 5, 6, 7, 8, 9, 10));
		$this->assertEquals(8, $res);
		$this->assertEquals(8, $before - $this->_getNumRows());
		$this->assertEquals(0, $this->_getNumRows());

	}

	/**
	 * Test the getListForView function
	 */
	public function testGetListForView() {

		// Get all
		$list = Budget::getListForView();
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$this->_compareBudgetsArrayWithOrder($this->budget, $list);

		// Get all in reverse order by budget type
		$list = Budget::getListForView(0, '', Budget::SORT_FIELD_NONE, Budget::SORT_FIELD_BUDGET_TYPE, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$expected = array($this->budget[4],$this->budget[8],$this->budget[3],$this->budget[7],$this->budget[2],
			$this->budget[6],$this->budget[10],$this->budget[1],$this->budget[5],$this->budget[9]);
		$this->_compareBudgetsArrayWithOrder($expected, $list);

		// Search by start_date with exact match
		$searchDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$list = Budget::getListForView(0, $searchDate, Budget::SORT_FIELD_START_DATE, Budget::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->budget[3]);
		$this->_compareBudgetsArrayWithOrder($expected, $list);

		// Search by end_date with exact match
		$searchDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+10 days"));
		$list = Budget::getListForView(0, $searchDate, Budget::SORT_FIELD_END_DATE, Budget::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->budget[5]);
		$this->_compareBudgetsArrayWithOrder($expected, $list);

		// Search by id with one match
		$list = Budget::getListForView(0, '3', Budget::SORT_FIELD_ID, Budget::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->budget[3]);
		$this->_compareBudgetsArrayWithOrder($expected, $list);

		// Search by id with no matches
		$list = Budget::getListForView(0, '13', Budget::SORT_FIELD_ID, Budget::SORT_FIELD_ID, 'ASC');
		$this->assertNull($list);

		// Search by status matches
		$list = Budget::getListForView(0, Budget::STATUS_NOT_APPROVED, Budget::SORT_FIELD_STATUS, Budget::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->budget[2],$this->budget[4],$this->budget[6],$this->budget[8],$this->budget[10]);
		$this->_compareBudgetsArrayWithOrder($expected, $list);

		// Search by status matches, different order
		$list = Budget::getListForView(0, Budget::STATUS_APPROVED, Budget::SORT_FIELD_STATUS, Budget::SORT_FIELD_ID, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->budget[9],$this->budget[7],$this->budget[5],$this->budget[3],$this->budget[1]);
		$this->_compareBudgetsArrayWithOrder($expected, $list);

		// Search by budget type
		$list = Budget::getListForView(0, Budget::BUDGET_TYPE_SALARY, Budget::SORT_FIELD_BUDGET_TYPE, Budget::SORT_FIELD_ID, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(3, count($list));
		$expected = array($this->budget[9], $this->budget[5],$this->budget[1]);
		$this->_compareBudgetsArrayWithOrder($expected, $list);

		// Search by budget unit
		$list = Budget::getListForView(0, 'U_5', Budget::SORT_FIELD_BUDGET_UNIT, Budget::SORT_FIELD_ID, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->budget[5]);
		$this->_compareBudgetsArrayWithOrder($expected, $list);

		// Search by budget value
		$list = Budget::getListForView(0, '6', Budget::SORT_FIELD_BUDGET_VALUE, Budget::SORT_FIELD_ID, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->budget[6]);
		$this->_compareBudgetsArrayWithOrder($expected, $list);

		// when no budgets are available
		$this->_runQuery('DELETE from hs_hr_budgets');
		$list = Budget::getListForView();
		$this->assertNull($list);

	}

	/**
	 * Test count method
	 */
	public function testCount() {

		// Count all
		$count = Budget::getCount();
		$this->assertEquals(10, $count);

		// Get all
		$count = Budget::getCount('', Budget::SORT_FIELD_NONE);
		$this->assertEquals(10, $count);

		// Search by start_date with exact match
		$searchDate = $startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$count = Budget::getCount($searchDate, Budget::SORT_FIELD_START_DATE);
		$this->assertEquals(1, $count);

		// Search by end_date with exact match
		$searchDate = $startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+10 days"));
		$count = Budget::getCount($searchDate, Budget::SORT_FIELD_END_DATE);
		$this->assertEquals(1, $count);

		// Search by id with one match
		$count = Budget::getCount('3', Budget::SORT_FIELD_ID);
		$this->assertEquals(1,$count);

		// Search by id with no matches
		$count = Budget::getCount('13', Budget::SORT_FIELD_ID);
		$this->assertEquals(0,$count);

		// Search by status matches
		$count = Budget::getCount(Budget::STATUS_NOT_APPROVED, Budget::SORT_FIELD_STATUS);
		$this->assertEquals(5,$count);

		// Search by status matches
		$count = Budget::getCount(Budget::STATUS_APPROVED, Budget::SORT_FIELD_STATUS);
		$this->assertEquals(5,$count);

		// Search by budget type
		$count = Budget::getCount(Budget::BUDGET_TYPE_SALARY, Budget::SORT_FIELD_BUDGET_TYPE);
		$this->assertEquals(3,$count);

		// Search by unit
		$count = Budget::getCount('U_10', Budget::SORT_FIELD_BUDGET_UNIT);
		$this->assertEquals(1,$count);

		// Search by value
		$count = Budget::getCount('5', Budget::SORT_FIELD_BUDGET_VALUE);
		$this->assertEquals(1,$count);

		// delete all
		$this->_runQuery('DELETE from hs_hr_budgets');
		$count = Budget::getCount();
		$this->assertEquals(0, $count);
	}

	/**
	 * Check's that the passed Budget exists in the database
	 *
	 * @param Budget Budget to check
	 */
	private function _checkExistsInDb($budget) {

		$id = $budget->getId();		
		
		$query = "id = {$id} ";

		$type = $budget->getBudgetType();
		if (!empty($type)) {
			$query .= " AND budget_type = '{$type}'";
		}
		
		$unit = $budget->getBudgetUnit();
		if (!empty($unit)) {
			$query .= " AND budget_unit = '{$unit}'";
		}

		$value = $budget->getBudgetValue();
		if (!empty($value)) {
			$query .= " AND budget_value = '{$value}'";
		}

		$startDate = $budget->getStartDate();		
		if (!empty($startDate)) {
			$query .= " AND start_date = '{$startDate}'";
		}
		
		$endDate = $budget->getEndDate();
		if (!empty($endDate)) {
			$query .= " AND end_date = '{$endDate}'";
		}
		
		$status = $budget->getStatus();
		if (!empty($status)) {
			$query .= " AND status = {$status}";
		}

		$notes = $budget->getNotes();
		if (!empty($notes)) {
			$query .= " AND notes = '{$notes}'";
		}

	    $this->assertEquals(1, $this->_getNumRows($query));
	}

    /**
     * Returns the number of rows in the hs_hr_budgets table
     *
     * @param  string $where where clause
     * @return int number of rows
     */
    private function _getNumRows($where = null) {

    	$sql = "SELECT COUNT(*) FROM hs_hr_budgets";
    	if (!empty($where)) {
    		$sql .= " WHERE " . $where;
		}

		$result = mysql_query($sql);
		if ($result) {
			$row = mysql_fetch_array($result, MYSQL_NUM);
		} else {
			$this->fail(mysql_error() . ' SQL = ' . $sql);
		}
        $count = $row[0];
		return $count;
    }

    /**
     * Compares two array of Budget objects verifing they contain the same
     * objects, without considering the order
     *
     * Objects in first array should be indexed by their id's
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareBudgets($expected, $result) {
    	$this->assertEquals(count($expected), count($result));

		foreach ($result as $budget) {
			$this->assertTrue($budget instanceof Budget, "Should return Budget objects");

			$id = $budget->getId();
			$this->assertEquals($expected[$id], $budget);
		}
    }

    /**
     * Compares two array of Budget objects verifing they contain the same
     * objects and considering the order
     *
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareBudgetsWithOrder($expected, $result) {
        $this->assertEquals(count($expected), count($result));
        $i = 0;
        foreach ($expected as $budget) {
            $this->assertEquals($budget, $result[$i]);
            $i++;
        }
    }

    /**
     * Compares an array of Budget objects with an array containing 
     * budget data.
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareBudgetsArrayWithOrder($expected, $result) {
        $this->assertEquals(count($expected), count($result));
        $i = 0;
        foreach ($expected as $budget) {
        	
			$this->assertEquals($budget->getId(), $result[$i][0]);
			$this->assertEquals($budget->getBudgetType(), $result[$i][1]);
			$this->assertEquals($budget->getBudgetUnit(), $result[$i][2]);
			$this->assertEquals($budget->getBudgetValue(), $result[$i][3]);
			$this->assertEquals($budget->getStartDate(), $result[$i][4]);
			$this->assertEquals($budget->getEndDate(), $result[$i][5]);
			$this->assertEquals($budget->getStatus(), $result[$i][6]);
			        	
            $i++;
        }
    }

    /**
     * Create a Budget object with the passed parameters
     */
    private function _getBudget($id, $budgetType, $budgetUnit, $budgetValue, $startDate, $endDate, $status, $notes) {
    	$budget = new Budget($id);
		$budget->setBudgetType($budgetType);
		$budget->setBudgetUnit($budgetUnit);
		$budget->setBudgetValue($budgetValue);
		$budget->setStartDate($startDate);
		$budget->setEndDate($endDate);
		$budget->setStatus($status);
		$budget->setNotes($notes);
    	return $budget;
    }

    /**
     * Saves the given Budget objects in the database
     *
     * @param array $budgets Array of Budget objects to save.
     */
    private function _createBudgets($budgets) {
		foreach ($budgets as $budget) {

			$sql = sprintf("INSERT INTO hs_hr_budgets(id, budget_type, budget_unit, budget_value, start_date, end_date, status, notes) " .
                        "VALUES(%d, %d, '%s', '%s', '%s', '%s', %d, '%s')",
                        $budget->getId(), $budget->getBudgetType(), $budget->getBudgetUnit(), $budget->getBudgetValue(), 
                        $budget->getStartDate(), $budget->getEndDate(), $budget->getStatus(), $budget->getNotes());
            $this->assertTrue(mysql_query($sql), mysql_error());
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

// Call BudgetTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "BudgetTest::main") {
    BudgetTest::main();
}
?>
