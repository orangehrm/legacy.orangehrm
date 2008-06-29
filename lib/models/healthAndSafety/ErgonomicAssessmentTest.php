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

// Call ErgonomicAssessmentTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "ErgonomicAssessmentTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once "testConf.php";
require_once ROOT_PATH."/lib/confs/Conf.php";
require_once ROOT_PATH."/lib/confs/sysConf.php";
require_once ROOT_PATH."/lib/models/healthAndSafety/ErgonomicAssessment.php";
require_once ROOT_PATH."/lib/common/UniqueIDGenerator.php";
require_once ROOT_PATH."/lib/common/LocaleUtil.php";

/**
 * Test class for ErgonomicAssessment
 */
class ErgonomicAssessmentTest extends PHPUnit_Framework_TestCase {

	private $ergonomicAssessments;
	
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("ErgonomicAssessmentTest");
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

        // Insert locations
        $this->_runQuery("INSERT INTO hs_hr_location(loc_code, loc_name, loc_country, loc_add, loc_zip) " .
                       "VALUES('LOC001', 'Test', 'LK', '111 Main street', '20000')");
        $this->_runQuery("INSERT INTO hs_hr_location(loc_code, loc_name, loc_country, loc_add, loc_zip) " .
                       "VALUES('LOC002', 'Test2', 'GB', '112 Baker street', '2SK')");
        $this->_runQuery("INSERT INTO hs_hr_location(loc_code, loc_name, loc_country, loc_add, loc_zip) " .
                       "VALUES('LOC003', 'Test3', 'US', '111 Willow Av', '999393')");

        // Insert sub divisions
        // Company - ID 1
        $this->_runQuery("INSERT INTO hs_hr_compstructtree(title, description, loc_code, lft, rgt, id, parnt, dept_id) VALUES " .
                "('A Company','',NULL,1,10,1,0,'')");
        // ID 2
        $this->_runQuery("INSERT INTO hs_hr_compstructtree(title, description, loc_code, lft, rgt, id, parnt, dept_id) VALUES " .
                "('Test Division','safsaf','LOC001',2,3,2,1,'001')");

        // ID 3
        $this->_runQuery("INSERT INTO hs_hr_compstructtree(title, description, loc_code, lft, rgt, id, parnt, dept_id) VALUES " .
                "('Three Division','test','LOC002',4,5,3,1,'002')");
		
		// Create employees
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name, work_station) " .
        			"VALUES(11, '0011', 'Rajasinghe', 'Saman', 'Marlon', 2)");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name, work_station) " .
        			"VALUES(12, '0022', 'Jayasinghe', 'Aruna', 'Shantha', 3)");
		
		// Assign employee locations
        $this->_runQuery("INSERT INTO hs_hr_emp_locations(emp_number, loc_code) VALUES(11, 'LOC001')");
        $this->_runQuery("INSERT INTO hs_hr_emp_locations(emp_number, loc_code) VALUES(11, 'LOC003')");
        $this->_runQuery("INSERT INTO hs_hr_emp_locations(emp_number, loc_code) VALUES(12, 'LOC001')");
		
		// Create Ergonomic Assessments
		for ($i=1; $i<11; $i++) {
			$startDiff = $i + 1;
			$endDiff = $i + 5;
	        $startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-{$startDiff} days"));
    	    $endDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+{$endDiff} days"));

			if ($i % 2) {
				// Odd
				$empNumber = 11;
				$empName = 'Saman Rajasinghe';
				$subDivisionName = 'Test Division';
				$locations = 'Test,Test3';
				$status = ErgonomicAssessment::STATUS_COMPLETE;
			} else {	
				// Even			
				$empNumber = 12;
				$empName = 'Aruna Jayasinghe';
				$subDivisionName = 'Three Division';
				$locations = 'Test';
				$status = ErgonomicAssessment::STATUS_INCOMPLETE;
			}
				
			$this->ergonomicAssessments[$i] = $this->_getErgonomicAssessment($i, $empNumber, $startDate, $endDate, $status, 
				"Test ergonomic {$i}", $empName, $subDivisionName, $locations);
		}  														
		$this->_createErgonomicAssessments($this->ergonomicAssessments);

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
		$this->_runQuery("TRUNCATE TABLE `hs_hr_compstructtree`");		
        $this->_runQuery("TRUNCATE TABLE `hs_hr_location`");
		$this->_runQuery("TRUNCATE TABLE `hs_hr_emp_ergonomic_assessments`");
		$this->_runQuery("TRUNCATE TABLE `hs_hr_emp_locations`");		
		$this->_runQuery("TRUNCATE TABLE `hs_hr_employee`");		
	}
	
	/**
	 * Test the save function
	 */
	public function testSave() {

		// new
		$before = $this->_getNumRows();
		$startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-5 days"));
		$endDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-1 days"));
		
		$assessment = $this->_getErgonomicAssessment(null, 11, $startDate, $endDate, ErgonomicAssessment::STATUS_INCOMPLETE,
			'A test ergonomic');
 				
		$id = $assessment->save();
		$this->assertEquals(($before + 1), $this->_getNumRows());
		$this->_checkExistsInDb($assessment);

		// update
		$before = $this->_getNumRows();
		$startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$endDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-2 days"));

		$assessment = $this->_getErgonomicAssessment($id, 12, $startDate, $endDate, ErgonomicAssessment::STATUS_COMPLETE,
			'Another test ergonomic');

		$newId = $assessment->save();
		$this->assertEquals($id, $newId);
		$this->assertEquals($before, $this->_getNumRows());
		$this->_checkExistsInDb($assessment);

		// without sub emp number
		$assessment = $this->_getErgonomicAssessment(null, null, $startDate, $endDate, ErgonomicAssessment::STATUS_COMPLETE,
			'Another test ergonomic');
		
		try {
			$assessment->save();
			$this->fail("Exception expected");
		} catch (ErgonomicAssessmentException $e) {
			$this->assertEquals(ErgonomicAssessmentException::MISSING_PARAMETERS, $e->getCode());
		}
		

		// Invalid emp number
		$assessment = $this->_getErgonomicAssessment(null, 'x1', $startDate, $endDate, ErgonomicAssessment::STATUS_COMPLETE,
			'Another test ergonomic');
		
		try {
			$assessment->save();
			$this->fail("Exception expected");
		} catch (ErgonomicAssessmentException $e) {
			$this->assertEquals(ErgonomicAssessmentException::INVALID_PARAMETER, $e->getCode());
		}

		// Invalid ID	
		$assessment = $this->_getErgonomicAssessment('1E', 11, $startDate, $endDate, ErgonomicAssessment::STATUS_COMPLETE,
			'Another test ergonomic');
		
		try {
			$assessment->save();
			$this->fail("Exception expected");
		} catch (ErgonomicAssessmentException $e) {
			$this->assertEquals(ErgonomicAssessmentException::INVALID_PARAMETER, $e->getCode());
		}
	}

    /**
     * Test for function getAll()
     */
    public function testGetAll() {

        $list = ErgonomicAssessment::getAll();
        $this->_compareAssessments($this->ergonomicAssessments, $list);
        
        // Delete all
        $this->_runQuery("Delete from hs_hr_emp_ergonomic_assessments");
        $list = ErgonomicAssessment::getAll();
        $this->assertTrue(is_array($list));
        $this->assertEquals(0, count($list));
    }

    /**
     * Test the GetErgonomicAssessment function
     */
    public function testGetErgonomicAssessment() {

		// Invalid id
		try {
        	$assessment = ErgonomicAssessment::getErgonomicAssessment('3e');
			$this->fail("Exception expected");
		} catch (ErgonomicAssessmentException $e) {
			$this->assertEquals(ErgonomicAssessmentException::INVALID_PARAMETER, $e->getCode());
		}		
		
		// Existing id's
        $assessment = ErgonomicAssessment::getErgonomicAssessment(1);
        $this->assertEquals($this->ergonomicAssessments[1], $assessment);


		// Non existing id
        $assessment = ErgonomicAssessment::getErgonomicAssessment(111);
        $this->assertNull($assessment);
    }

	/**
	 * test the ErgonomicAssessment delete function.
	 */
	public function testDelete() {

		$before = $this->_getNumRows();

		// invalid params
		try {
			ErgonomicAssessment::delete(34);
			$this->fail("Exception not thrown");
		} catch (ErgonomicAssessmentException $e) {
			$this->assertEquals(ErgonomicAssessmentException::INVALID_PARAMETER, $e->getCode());
		}

		// invalid params
		try {
			ErgonomicAssessment::delete(array(1, 'w', 12));
			$this->fail("Exception not thrown");
		} catch (ErgonomicAssessmentException $e) {
			$this->assertEquals(ErgonomicAssessmentException::INVALID_PARAMETER, $e->getCode());
		}

		// empty array
		$res = ErgonomicAssessment::delete(array());
		$this->assertEquals(0, $res);
		$this->assertEquals($before, $this->_getNumRows());

		// no matches
		$res = ErgonomicAssessment::delete(array(12, 22));
		$this->assertEquals(0, $res);
		$this->assertEquals($before, $this->_getNumRows());

		// one match
		$res = ErgonomicAssessment::delete(array(1, 21));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// one more
		$res = ErgonomicAssessment::delete(array(3));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// rest
		$res = ErgonomicAssessment::delete(array(2, 4, 5, 6, 7, 8, 9, 10));
		$this->assertEquals(8, $res);
		$this->assertEquals(8, $before - $this->_getNumRows());
		$this->assertEquals(0, $this->_getNumRows());

	}

	/**
	 * Test the getListForView function
	 */
	public function testGetListForView() {

		// Get all
		$list = ErgonomicAssessment::getListForView();
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$this->_compareAssessmentsArrayWithOrder($this->ergonomicAssessments, $list);

		// Get all in reverse order by sub division name
		$list = ErgonomicAssessment::getListForView(0, '', ErgonomicAssessment::SORT_FIELD_NONE, ErgonomicAssessment::SORT_FIELD_EMP_SUBDIVISION_NAME, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$expected = array($this->ergonomicAssessments[2],$this->ergonomicAssessments[4],$this->ergonomicAssessments[6],$this->ergonomicAssessments[8],$this->ergonomicAssessments[10],
				$this->ergonomicAssessments[1],$this->ergonomicAssessments[3],$this->ergonomicAssessments[5],$this->ergonomicAssessments[7],$this->ergonomicAssessments[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Get all in employee name order
		$list = ErgonomicAssessment::getListForView(0, '', ErgonomicAssessment::SORT_FIELD_NONE, ErgonomicAssessment::SORT_FIELD_EMP_NAME, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$expected = array($this->ergonomicAssessments[2],$this->ergonomicAssessments[4],$this->ergonomicAssessments[6],$this->ergonomicAssessments[8],$this->ergonomicAssessments[10],
				$this->ergonomicAssessments[1],$this->ergonomicAssessments[3],$this->ergonomicAssessments[5],$this->ergonomicAssessments[7],$this->ergonomicAssessments[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Get all in location name order
		$list = ErgonomicAssessment::getListForView(0, '', ErgonomicAssessment::SORT_FIELD_NONE, ErgonomicAssessment::SORT_FIELD_EMP_LOCATIONS, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$expected = array($this->ergonomicAssessments[2],$this->ergonomicAssessments[4],$this->ergonomicAssessments[6],$this->ergonomicAssessments[8],$this->ergonomicAssessments[10],
				$this->ergonomicAssessments[1],$this->ergonomicAssessments[3],$this->ergonomicAssessments[5],$this->ergonomicAssessments[7],$this->ergonomicAssessments[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by start_date with exact match
		$searchDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$list = ErgonomicAssessment::getListForView(0, $searchDate, ErgonomicAssessment::SORT_FIELD_START_DATE, ErgonomicAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->ergonomicAssessments[3]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by end_date with exact match
		$searchDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+10 days"));
		$list = ErgonomicAssessment::getListForView(0, $searchDate, ErgonomicAssessment::SORT_FIELD_END_DATE, ErgonomicAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->ergonomicAssessments[5]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by subdivision name
		$list = ErgonomicAssessment::getListForView(0, "Three Division", ErgonomicAssessment::SORT_FIELD_EMP_SUBDIVISION_NAME, ErgonomicAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->ergonomicAssessments[2],$this->ergonomicAssessments[4],$this->ergonomicAssessments[6],$this->ergonomicAssessments[8],$this->ergonomicAssessments[10]);		
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by employee name
		$list = ErgonomicAssessment::getListForView(0,  "Saman Rajasinghe", ErgonomicAssessment::SORT_FIELD_EMP_NAME, ErgonomicAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->ergonomicAssessments[1],$this->ergonomicAssessments[3],$this->ergonomicAssessments[5],$this->ergonomicAssessments[7],$this->ergonomicAssessments[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by id with one match
		$list = ErgonomicAssessment::getListForView(0, '3', ErgonomicAssessment::SORT_FIELD_ID, ErgonomicAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->ergonomicAssessments[3]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by id with no matches
		$list = ErgonomicAssessment::getListForView(0, '13', ErgonomicAssessment::SORT_FIELD_ID, ErgonomicAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertNull($list);

		// Search by status matches
		$list = ErgonomicAssessment::getListForView(0, ErgonomicAssessment::STATUS_INCOMPLETE, ErgonomicAssessment::SORT_FIELD_STATUS, ErgonomicAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->ergonomicAssessments[2],$this->ergonomicAssessments[4],$this->ergonomicAssessments[6],$this->ergonomicAssessments[8],$this->ergonomicAssessments[10]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by status matches, different order
		$list = ErgonomicAssessment::getListForView(0, ErgonomicAssessment::STATUS_COMPLETE, ErgonomicAssessment::SORT_FIELD_STATUS, ErgonomicAssessment::SORT_FIELD_ID, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->ergonomicAssessments[9],$this->ergonomicAssessments[7],$this->ergonomicAssessments[5],$this->ergonomicAssessments[3],$this->ergonomicAssessments[1]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by Location
		$list = ErgonomicAssessment::getListForView(0, 'Test3', ErgonomicAssessment::SORT_FIELD_EMP_LOCATIONS, ErgonomicAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->ergonomicAssessments[1],$this->ergonomicAssessments[3],$this->ergonomicAssessments[5],$this->ergonomicAssessments[7],$this->ergonomicAssessments[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);
				
		// when no ergonomic assessments are available
		$this->_runQuery('DELETE from hs_hr_emp_ergonomic_assessments');
		$list = ErgonomicAssessment::getListForView();
		$this->assertNull($list);

	}

	/**
	 * Test count method
	 */
	public function testCount() {

		// Count all
		$count = ErgonomicAssessment::getCount();
		$this->assertEquals(10, $count);

		// Get all
		$count = ErgonomicAssessment::getCount('', ErgonomicAssessment::SORT_FIELD_NONE);
		$this->assertEquals(10, $count);

		// Search by start_date with exact match
		$searchDate = $startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$count = ErgonomicAssessment::getCount($searchDate, ErgonomicAssessment::SORT_FIELD_START_DATE);
		$this->assertEquals(1, $count);

		// Search by end_date with exact match
		$searchDate = $startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+10 days"));
		$count = ErgonomicAssessment::getCount($searchDate, ErgonomicAssessment::SORT_FIELD_END_DATE);
		$this->assertEquals(1, $count);

		// Search by subdivision with multiple matches
		$count = ErgonomicAssessment::getCount("Three Division", ErgonomicAssessment::SORT_FIELD_EMP_SUBDIVISION_NAME);
		$this->assertEquals(5, $count);

		// Search by employee 
		$count = ErgonomicAssessment::getCount("Saman Rajasinghe", ErgonomicAssessment::SORT_FIELD_EMP_NAME);
		$this->assertEquals(5,$count);

		// Search by id with one match
		$count = ErgonomicAssessment::getCount('3', ErgonomicAssessment::SORT_FIELD_ID);
		$this->assertEquals(1,$count);

		// Search by id with no matches
		$count = ErgonomicAssessment::getCount('13', ErgonomicAssessment::SORT_FIELD_ID);
		$this->assertEquals(0,$count);

		// Search by status matches
		$count = ErgonomicAssessment::getCount(ErgonomicAssessment::STATUS_INCOMPLETE, ErgonomicAssessment::SORT_FIELD_STATUS);
		$this->assertEquals(5,$count);

		// Search by status matches
		$count = ErgonomicAssessment::getCount(ErgonomicAssessment::STATUS_COMPLETE, ErgonomicAssessment::SORT_FIELD_STATUS);
		$this->assertEquals(5,$count);
		
		// Search by location
		$count = ErgonomicAssessment::getCount('Test3', ErgonomicAssessment::SORT_FIELD_EMP_LOCATIONS);
		$this->assertEquals(5,$count);		

		// delete all
		$this->_runQuery('DELETE from hs_hr_emp_ergonomic_assessments');
		$count = ErgonomicAssessment::getCount();
		$this->assertEquals(0, $count);
	}

	/**
	 * Check's that the passed Ergonomic Assessment exists in the database
	 *
	 * @param ErgonomicAssessment Ergonomic Assessment to check
	 */
	private function _checkExistsInDb($assessment) {

		$id = $assessment->getId();		
		$empNumber = $assessment->getEmpNumber();
		
		$query = "id = {$id} AND emp_number = {$empNumber} ";
		$startDate = $assessment->getStartDate();
		
		if (!empty($startDate)) {
			$query .= " AND start_date = '{$startDate}'";
		}
		$endDate = $assessment->getEndDate();
		if (!empty($endDate)) {
			$query .= " AND end_date = '{$endDate}'";
		}

		$status = $assessment->getStatus();
		if (!empty($status)) {
			$query .= " AND status = {$status}";
		}
		
		$notes = $assessment->getNotes();
		if (!empty($notes)) {
			$query .= " AND notes = '{$notes}'";
		}		

	    $this->assertEquals(1, $this->_getNumRows($query));
	}

    /**
     * Returns the number of rows in the hs_hr_emp_ergonomic_assessments table
     *
     * @param  string $where where clause
     * @return int number of rows
     */
    private function _getNumRows($where = null) {

    	$sql = "SELECT COUNT(*) FROM hs_hr_emp_ergonomic_assessments";
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
     * Compares two array of ErgonomicAssessment objects verifing they contain the same
     * objects, without considering the order
     *
     * Objects in first array should be indexed by their id's
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareAssessments($expected, $result) {
    	$this->assertEquals(count($expected), count($result));

		foreach ($result as $assessment) {
			$this->assertTrue($assessment instanceof ErgonomicAssessment, "Should return ErgonomicAssessment objects");

			$id = $assessment->getId();
			$this->assertEquals($expected[$id], $assessment);
		}
    }

    /**
     * Compares two array of ErgonomicAssessment objects verifing they contain the same
     * objects and considering the order
     *
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareAssessmentsWithOrder($expected, $result) {
        $this->assertEquals(count($expected), count($result));
        $i = 0;
        foreach ($expected as $assessment) {
            $this->assertEquals($assessment, $result[$i]);
            $i++;
        }
    }

    /**
     * Compares an array of ErgonomicAssessment objects with an array containing 
     * ergonomic assessment data.
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareAssessmentsArrayWithOrder($expected, $result) {
        $this->assertEquals(count($expected), count($result));
        $i = 0;
        foreach ($expected as $assessment) {
			$this->assertEquals($assessment->getId(), $result[$i][0]);
			$this->assertEquals($assessment->getEmpName(), $result[$i][1]);
			$this->assertEquals($assessment->getSubdivisionName(), $result[$i][2]);
			$this->assertEquals($assessment->getLocations(), $result[$i][3]);
			$this->assertEquals($assessment->getStartDate(), $result[$i][4]);
			$this->assertEquals($assessment->getEndDate(), $result[$i][5]);
			$this->assertEquals($assessment->getStatus(), $result[$i][6]);
			        	
            $i++;
        }
    }

    /**
     * Create a ErgonomicAssessment object with the passed parameters
     */ 
    private function _getErgonomicAssessment($id, $empNumber, $startDate, $endDate, $status, $notes, 
    		$empName = null, $subdivisionName = null, $locations = null) {
    	$assessment = new ErgonomicAssessment($id);
		$assessment->setEmpNumber($empNumber);
		$assessment->setStartDate($startDate);
		$assessment->setEndDate($endDate);
		$assessment->setStatus($status);
		$assessment->setNotes($notes);		

		if (!empty($empName)) {		
			$assessment->setEmpName($empName);
		}
		if (!empty($subdivisionName)) {		
			$assessment->setSubDivisionName($subdivisionName);
		}
		if (!empty($locations)) {		
			$assessment->setLocations($locations);
		}
		
    	return $assessment;
    }

    /**
     * Saves the given ErgonomicAssessment objects in the database
     *
     * @param array $assessments Array of ErgonomicAssessment objects to save.
     */
    private function _createErgonomicAssessments($assessments) {
		foreach ($assessments as $assessment) {

			$sql = sprintf("INSERT INTO hs_hr_emp_ergonomic_assessments(id, emp_number, start_date, end_date, ".
						"status, notes) " .
                        "VALUES(%d, %d, '%s', '%s', %d, '%s')",
                        $assessment->getId(), $assessment->getEmpNumber(), $assessment->getStartDate(),
                        $assessment->getEndDate(),
                        $assessment->getStatus(), $assessment->getNotes());
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

// Call ErgonomicAssessmentTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "ErgonomicAssessmentTest::main") {
    ErgonomicAssessmentTest::main();
}
?>
