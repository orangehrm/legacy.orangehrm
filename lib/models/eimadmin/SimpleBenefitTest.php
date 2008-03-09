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

// Call SimpleBenefitTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "SimpleBenefitTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once "testConf.php";
require_once ROOT_PATH."/lib/confs/Conf.php";
require_once ROOT_PATH."/lib/confs/sysConf.php";
require_once ROOT_PATH."/lib/models/eimadmin/SimpleBenefit.php";
require_once ROOT_PATH."/lib/common/UniqueIDGenerator.php";

/**
 * Test class for SimpleBenefit
 */
class SimpleBenefitTest extends PHPUnit_Framework_TestCase {

	private $benefits;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("SimpleBenefitTest");
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

		$this->_runQuery("TRUNCATE TABLE `hs_hr_benefit_simple`");

		// Insert data for tests
		$this->benefits[1] = $this->_getSimpleBenefit(1, 'Health Insurance');
        $this->benefits[2] = $this->_getSimpleBenefit(2, 'Tuition Assistance');
		$this->benefits[3] = $this->_getSimpleBenefit(3, 'Training');
		$this->benefits[4] = $this->_getSimpleBenefit(4, 'Travel');
		$this->_createSimpleBenefits($this->benefits);
		UniqueIDGenerator::getInstance()->resetIDs();
    }

    /**
     * Tears down the fixture, removed database entries created during test.
     *
     * @access protected
     */
    protected function tearDown() {
		$this->_runQuery("TRUNCATE TABLE `hs_hr_benefit_simple`");
		UniqueIDGenerator::getInstance()->resetIDs();
    }

	/**
	 * test the SimpleBenefit delete function.
	 */
	public function testDelete() {

		$before = $this->_getNumRows();

		// invalid params
		try {
			SimpleBenefit::delete(34);
			$this->fail("Exception not thrown");
		} catch (SimpleBenefitException $e) {

		}

		// invalid params
		try {
			SimpleBenefit::delete(array(1, 'w', 12));
			$this->fail("Exception not thrown");
		} catch (SimpleBenefitException $e) {

		}

		// empty array
		$res = SimpleBenefit::delete(array());
		$this->assertEquals(0, $res);
        $this->assertEquals($before, $this->_getNumRows());

		// no matches
		$res = SimpleBenefit::delete(array(12, 22));
		$this->assertEquals(0, $res);
        $this->assertEquals($before, $this->_getNumRows());

		// one match
		$res = SimpleBenefit::delete(array(1, 21));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// one more the rest
		$res = SimpleBenefit::delete(array(3));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// rest
		$res = SimpleBenefit::delete(array(4, 2));
		$this->assertEquals(2, $res);
		$this->assertEquals(2, $before - $this->_getNumRows());

	}

	/**
	 * Test the save function
	 */
	public function testSave() {

		// no name defined
		$before = $this->_getNumRows();
		$benefit = $this->_getSimpleBenefit(null, null);

		try {
			$benefit->save();
			$this->fail('Should throw exception');
		} catch (SimpleBenefitException $e) {
		}
		$this->assertEquals($before, $this->_getNumRows());

		// new
		$before = $this->_getNumRows();
		$benefit = $this->_getSimpleBenefit(null, 'A test Benefit');
		$id = $benefit->save();
		$this->assertEquals(($before + 1), $this->_getNumRows());
		$this->assertEquals(1, $this->_getNumRows("name = 'A test Benefit' AND id = $id"));

		// update
		$before = $this->_getNumRows();
		$benefit = $this->_getSimpleBenefit(1, 'XYZ');
		$id = $benefit->save();
		$this->assertEquals(1, $id);
		$this->assertEquals($before, $this->_getNumRows());
		$this->assertEquals(1, $this->_getNumRows("name = 'XYZ' AND id = $id"));

		// update without name
		$before = $this->_getNumRows();
		$benefit = $this->_getSimpleBenefit(2, null);
		try {
			$benefit->save();
			$this->fail('Should throw exception');
		} catch (SimpleBenefitException $e) {
		}
		$this->assertEquals($before, $this->_getNumRows());

	}

	/**
	 * Test count method
	 */
	public function testCount() {

		// Count all
		$count = SimpleBenefit::getCount();
		$this->assertEquals(4, $count);

		// Match of ID
		$count = SimpleBenefit::getCount(2, 0);
		$this->assertEquals(1, $count);

		// ID - no match
		$count = SimpleBenefit::getCount(21, 0);
		$this->assertEquals(0, $count);

		// no match
		$count = SimpleBenefit::getCount('XYZ', 1);
		$this->assertEquals(0, $count);

		// Partial match of name
		$count = SimpleBenefit::getCount('T', 1);
		$this->assertEquals(4, $count);

		// Full match of name
		$count = SimpleBenefit::getCount('Health Insurance', 1);
		$this->assertEquals(1, $count);
	}

	/**
	 * Test the getSimpleBenefit function
	 */
	public function testGetSimpleBenefit() {

		// unknown id
		$benefit = SimpleBenefit::getSimpleBenefit(383);
		$this->assertNull($benefit);

		// invalid id
		try {
			$benefit = SimpleBenefit::getSimpleBenefit('7da');
			$this->fail('Should throw exception');
		} catch (SimpleBenefitException $e) {
		}

		// available benefit
		$benefit = SimpleBenefit::getSimpleBenefit(2);
		$this->assertNotNull($benefit);
		$this->assertTrue($this->benefits[2] == $benefit);
	}

	/**
	 * Test the getListForView function
	 */
	public function testGetListForView() {

		// Get all
		$list = SimpleBenefit::getListForView();
		$this->assertTrue(is_array($list));
		$this->assertEquals(4, count($list));
		$this->_compareBenefitsWithOrder($this->benefits, $list);

		// Get all in reverse order by name
		$list = SimpleBenefit::getListForView(0, '', -1, 1, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(4, count($list));
		$expected = array($this->benefits[2],$this->benefits[4],$this->benefits[3],$this->benefits[1]);
		$this->_compareBenefitsWithOrder($expected, $list);

		// Search by name with exact match
		$list = SimpleBenefit::getListForView(0, 'Training', 1, 1, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->benefits[3]);
		$this->_compareBenefitsWithOrder($expected, $list);

		// Search by name with multiple matches
		$list = SimpleBenefit::getListForView(0, 'T', 1, 1, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(4, count($list));
		$expected = array($this->benefits[2],$this->benefits[4],$this->benefits[3],$this->benefits[1]);
		$this->_compareBenefitsWithOrder($expected, $list);

		// Search by id with one match
		$list = SimpleBenefit::getListForView(0, '3', 0, 0, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->benefits[3]);
		$this->_compareBenefitsWithOrder($expected, $list);

		// when no job benefits available
		$this->_runQuery('DELETE from hs_hr_benefit_simple');
		$list = SimpleBenefit::getAll();
		$this->assertTrue(is_array($list));
		$this->assertEquals(0, count($list));

		// Insert data for tests
		for ($i=1; $i<51; $i++) {

			$inc = 100 + $i;
			if ($i % 2 == 0) {
				$desc = "Even ";
				$even = true;
			} else {
				$desc = "Odd ";
				$even = false;
			}
			$benefit = $this->_getSimpleBenefit($i, "{$desc}-{$inc}");
			$benefits[] = $benefit;

			if ($even) {
				$evenBenefits[] = $benefit;
			} else {
				$oddBenefits[] = $benefit;
			}
		}

		$this->_createSimpleBenefits($benefits);

		$sysConf = new sysConf();
		$pageSize = $sysConf->itemsPerPage;

		// check paging - without search

		// page 1
		$list = SimpleBenefit::getListForView(1, '', -1, 0, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals($pageSize, count($list));
		$pages = array_chunk($benefits, $pageSize);
		$this->_compareBenefitsWithOrder($pages[0], $list);

		// page 3
		$list = SimpleBenefit::getListForView(3, '', -1, 0, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals($pageSize, count($list));
		$this->_compareBenefitsWithOrder($pages[2], $list);

		// paging with search

		// Separate even rows to pages
		$pages = array_chunk($evenBenefits, $pageSize);

		// Search only for even rows and check page 1
		$list = SimpleBenefit::getListForView(1, 'Even', 1, 0, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(count($pages[0]), count($list));
		$this->_compareBenefitsWithOrder($pages[0], $list);

		$list = SimpleBenefit::getListForView(3, 'Even', 1, 0, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(count($pages[2]), count($list));
		$this->_compareBenefitsWithOrder($pages[2], $list);
	}

	/**
	 * test the getAll function
	 */
	public function testGetAll() {

		// Get all
		$list = SimpleBenefit::getAll();
		$this->assertTrue(is_array($list));
		$this->assertEquals(4, count($list));
		$this->_compareBenefits($this->benefits, $list);

		// when no benefits available
		$this->_runQuery('DELETE from hs_hr_benefit_simple');
		$list = SimpleBenefit::getAll();
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

    	$sql = "SELECT COUNT(*) FROM hs_hr_benefit_simple";
    	if (!empty($where)) {
    		$sql .= " WHERE " . $where;
    	}
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result, MYSQL_NUM);
        $count = $row[0];
		return $count;
    }

    /**
     * Compares two array of SimpleBenefit objects verifing they contain the same
     * objects, without considering the order
     *
     * Objects in first array should be indexed by their id's
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareBenefits($expected, $result) {
    	$this->assertEquals(count($expected), count($result));

		foreach ($result as $benefit) {
			$this->assertTrue($benefit instanceof SimpleBenefit, "Should return SimpleBenefit objects");

			$id = $benefit->getId();
			$this->assertEquals($expected[$id], $benefit);
		}
    }

    /**
     * Compares two array of SimpleBenefit objects verifing they contain the same
     * objects and considering the order
     *
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareBenefitsWithOrder($expected, $result) {
    	$this->assertEquals(count($expected), count($result));

		$i = 0;
		foreach ($expected as $benefit) {
			$this->assertEquals($benefit->getId(), $result[$i][0]);
			$this->assertEquals($benefit->getName(), $result[$i][1]);
			$i++;
		}

    }

    /**
     * Checks that the attributes of the Simple Benefit object and the database row match.
     *
     * @param SimpleBenefit $benefit
     * @param array  $row
     */
    private function _checkRow($benefit, $row) {
		$this->assertEquals($benefit->getName(), $row['name'], "Name not correct");
		$this->assertEquals($benefit->getId(), $row['id'], "ID not correct");
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
     * Saves the given SimpleBenefit objects in the databas
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

// Call SimpleBenefitTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "SimpleBenefitTest::main") {
    SimpleBenefitTest::main();
}
?>
