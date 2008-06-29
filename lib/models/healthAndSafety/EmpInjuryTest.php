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

// Call EmpInjuryTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "EmpInjuryTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once "testConf.php";
require_once ROOT_PATH."/lib/confs/Conf.php";
require_once ROOT_PATH."/lib/confs/sysConf.php";
require_once ROOT_PATH."/lib/models/healthAndSafety/EmpInjury.php";
require_once ROOT_PATH."/lib/common/UniqueIDGenerator.php";
require_once ROOT_PATH."/lib/common/LocaleUtil.php";

/**
 * Test class for EmpInjury
 */
class EmpInjuryTest extends PHPUnit_Framework_TestCase {

	private $employeeInjuries;
	
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("EmpInjuryTest");
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
		
		// Create Employee Injuries
		for ($i=1; $i<11; $i++) {
			$startDiff = $i + 1;
			$endDiff = $i + 5;
	        $incidentDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-{$startDiff} days"));
    	    $reportedDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+{$endDiff} days"));

			if ($i % 2) {
				
				// Odd
				$empNumber = 11;
				$empName = 'Saman Rajasinghe';
				$subDivisionName = 'Test Division';
				$locations = 'Test,Test3';
				$type = "odd";
				
			} else {
					
				// Even			
				$empNumber = 12;
				$empName = 'Aruna Jayasinghe';
				$subDivisionName = 'Three Division';
				$locations = 'Test';
				$type = "even";
			}
				 
			$injury = "injury $i $type";
			$description = "Description $i $type";
			$timeOffWork = "$i";
			$result = "Result $i $type";
   		
			$this->employeeInjuries[$i] = $this->_getEmpInjury($i, $empNumber, $injury, $description, $incidentDate, $reportedDate, $timeOffWork, $result, 
				$empName, $subDivisionName, $locations);
		}  														
		$this->_createEmpInjuries($this->employeeInjuries);

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
		$this->_runQuery("TRUNCATE TABLE `hs_hr_emp_injury`");
		$this->_runQuery("TRUNCATE TABLE `hs_hr_emp_locations`");		
		$this->_runQuery("TRUNCATE TABLE `hs_hr_employee`");		
	}
	
	/**
	 * Test the save function
	 */
	public function testSave() {

		// new
		$before = $this->_getNumRows();
		$incidentDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-5 days"));
		$reportedDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-1 days"));
		
		$injury = $this->_getEmpInjury(null, 11, 'Injury test 1', 'Decription 1', $incidentDate, $reportedDate, '3.4', 'OK');
		$id = $injury->save();
		$this->assertEquals(($before + 1), $this->_getNumRows());
		$this->_checkExistsInDb($injury);

		// update
		$before = $this->_getNumRows();
		$incidentDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$reportedDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-2 days"));

		$injury = $this->_getEmpInjury($id, 12, 'Injury test again', 'Decription aaa', $incidentDate, $reportedDate, '4', 'NOT OK');

		$newId = $injury->save();
		$this->assertEquals($id, $newId);
		$this->assertEquals($before, $this->_getNumRows());
		$this->_checkExistsInDb($injury);

		// without emp number
		$injury = $this->_getEmpInjury(null, null, 'Injury test a1', 'Decriptionaaa 1', $incidentDate, $reportedDate, '3.4', 'OK');
		
		try {
			$injury->save();
			$this->fail("Exception expected");
		} catch (EmpInjuryException $e) {
			$this->assertEquals(EmpInjuryException::MISSING_PARAMETERS, $e->getCode());
		}
		

		// Invalid emp number
		$injury = $this->_getEmpInjury(null, '1a', 'Injury test a1', 'Decriptionaaa 1', $incidentDate, $reportedDate, '3.4', 'OK');
				
		try {
			$injury->save();
			$this->fail("Exception expected");
		} catch (EmpInjuryException $e) {
			$this->assertEquals(EmpInjuryException::INVALID_PARAMETER, $e->getCode());
		}

		// Invalid ID	
		$injury = $this->_getEmpInjury('x1', 11, 'Injury test a1', 'Decriptionaaa 1', $incidentDate, $reportedDate, '3.4', 'OK');		
		try {
			$injury->save();
			$this->fail("Exception expected");
		} catch (EmpInjuryException $e) {
			$this->assertEquals(EmpInjuryException::INVALID_PARAMETER, $e->getCode());
		}
	}

    /**
     * Test for function getAll()
     */
    public function testGetAll() {

        $list = EmpInjury::getAll();
        $this->_compareAssessments($this->employeeInjuries, $list);
        
        // Delete all
        $this->_runQuery("Delete from hs_hr_emp_injury");
        $list = EmpInjury::getAll();
        $this->assertTrue(is_array($list));
        $this->assertEquals(0, count($list));
    }

    /**
     * Test the GetEmpInjury function
     */
    public function testGetEmpInjury() {

		// Invalid id
		try {
        	$injury = EmpInjury::getEmpInjury('3e');
			$this->fail("Exception expected");
		} catch (EmpInjuryException $e) {
			$this->assertEquals(EmpInjuryException::INVALID_PARAMETER, $e->getCode());
		}		
		
		// Existing id's
        $injury = EmpInjury::getEmpInjury(1);
        $this->assertEquals($this->employeeInjuries[1], $injury);


		// Non existing id
        $injury = EmpInjury::getEmpInjury(111);
        $this->assertNull($injury);
    }

	/**
	 * test the EmpInjury delete function.
	 */
	public function testDelete() {

		$before = $this->_getNumRows();

		// invalid params
		try {
			EmpInjury::delete(34);
			$this->fail("Exception not thrown");
		} catch (EmpInjuryException $e) {
			$this->assertEquals(EmpInjuryException::INVALID_PARAMETER, $e->getCode());
		}

		// invalid params
		try {
			EmpInjury::delete(array(1, 'w', 12));
			$this->fail("Exception not thrown");
		} catch (EmpInjuryException $e) {
			$this->assertEquals(EmpInjuryException::INVALID_PARAMETER, $e->getCode());
		}

		// empty array
		$res = EmpInjury::delete(array());
		$this->assertEquals(0, $res);
		$this->assertEquals($before, $this->_getNumRows());

		// no matches
		$res = EmpInjury::delete(array(12, 22));
		$this->assertEquals(0, $res);
		$this->assertEquals($before, $this->_getNumRows());

		// one match
		$res = EmpInjury::delete(array(1, 21));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// one more
		$res = EmpInjury::delete(array(3));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// rest
		$res = EmpInjury::delete(array(2, 4, 5, 6, 7, 8, 9, 10));
		$this->assertEquals(8, $res);
		$this->assertEquals(8, $before - $this->_getNumRows());
		$this->assertEquals(0, $this->_getNumRows());

	}

	/**
	 * Test the getListForView function
	 */
	public function testGetListForView() {

		// Get all
		$list = EmpInjury::getListForView();
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$this->_compareAssessmentsArrayWithOrder($this->employeeInjuries, $list);

		// Get all in reverse order by sub division name
		$list = EmpInjury::getListForView(0, '', EmpInjury::SORT_FIELD_NONE, EmpInjury::SORT_FIELD_EMP_SUBDIVISION_NAME, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$expected = array($this->employeeInjuries[2],$this->employeeInjuries[4],$this->employeeInjuries[6],$this->employeeInjuries[8],$this->employeeInjuries[10],
				$this->employeeInjuries[1],$this->employeeInjuries[3],$this->employeeInjuries[5],$this->employeeInjuries[7],$this->employeeInjuries[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Get all in employee name order
		$list = EmpInjury::getListForView(0, '', EmpInjury::SORT_FIELD_NONE, EmpInjury::SORT_FIELD_EMP_NAME, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$expected = array($this->employeeInjuries[2],$this->employeeInjuries[4],$this->employeeInjuries[6],$this->employeeInjuries[8],$this->employeeInjuries[10],
				$this->employeeInjuries[1],$this->employeeInjuries[3],$this->employeeInjuries[5],$this->employeeInjuries[7],$this->employeeInjuries[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Get all in location name order
		$list = EmpInjury::getListForView(0, '', EmpInjury::SORT_FIELD_NONE, EmpInjury::SORT_FIELD_EMP_LOCATIONS, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$expected = array($this->employeeInjuries[2],$this->employeeInjuries[4],$this->employeeInjuries[6],$this->employeeInjuries[8],$this->employeeInjuries[10],
				$this->employeeInjuries[1],$this->employeeInjuries[3],$this->employeeInjuries[5],$this->employeeInjuries[7],$this->employeeInjuries[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by incident date with exact match
		$searchDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$list = EmpInjury::getListForView(0, $searchDate, EmpInjury::SORT_FIELD_DATE_OF_INCIDENT, EmpInjury::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->employeeInjuries[3]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by reported date with exact match
		$searchDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+10 days"));
		$list = EmpInjury::getListForView(0, $searchDate, EmpInjury::SORT_FIELD_DATE_REPORTED, EmpInjury::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->employeeInjuries[5]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by subdivision name
		$list = EmpInjury::getListForView(0, "Three Division", EmpInjury::SORT_FIELD_EMP_SUBDIVISION_NAME, EmpInjury::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->employeeInjuries[2],$this->employeeInjuries[4],$this->employeeInjuries[6],$this->employeeInjuries[8],$this->employeeInjuries[10]);		
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by employee name
		$list = EmpInjury::getListForView(0,  "Saman Rajasinghe", EmpInjury::SORT_FIELD_EMP_NAME, EmpInjury::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->employeeInjuries[1],$this->employeeInjuries[3],$this->employeeInjuries[5],$this->employeeInjuries[7],$this->employeeInjuries[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by id with one match
		$list = EmpInjury::getListForView(0, '3', EmpInjury::SORT_FIELD_ID, EmpInjury::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->employeeInjuries[3]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by id with no matches
		$list = EmpInjury::getListForView(0, '13', EmpInjury::SORT_FIELD_ID, EmpInjury::SORT_FIELD_ID, 'ASC');
		$this->assertNull($list);

		// Search by result 
		$list = EmpInjury::getListForView(0, 'Result 5 odd', EmpInjury::SORT_FIELD_RESULT, EmpInjury::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->employeeInjuries[5]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by time_off_work matches, different order
		$list = EmpInjury::getListForView(0, '8', EmpInjury::SORT_FIELD_TIME_OFF_WORK, EmpInjury::SORT_FIELD_ID, 'DESC');

		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->employeeInjuries[8]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by Location
		$list = EmpInjury::getListForView(0, 'Test3', EmpInjury::SORT_FIELD_EMP_LOCATIONS, EmpInjury::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->employeeInjuries[1],$this->employeeInjuries[3],$this->employeeInjuries[5],$this->employeeInjuries[7],$this->employeeInjuries[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);
				
		// when no ergonomic injuries are available
		$this->_runQuery('DELETE from hs_hr_emp_injury');
		$list = EmpInjury::getListForView();
		$this->assertNull($list);

	}

	/**
	 * Test count method
	 */
	public function testCount() {

		// Count all
		$count = EmpInjury::getCount();
		$this->assertEquals(10, $count);

		// Get all
		$count = EmpInjury::getCount('', EmpInjury::SORT_FIELD_NONE);
		$this->assertEquals(10, $count);

		// Search by incident date with exact match
		$searchDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$count = EmpInjury::getCount($searchDate, EmpInjury::SORT_FIELD_DATE_OF_INCIDENT);
		$this->assertEquals(1, $count);

		// Search by reported date with exact match
		$searchDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+10 days"));
		$count = EmpInjury::getCount($searchDate, EmpInjury::SORT_FIELD_DATE_REPORTED);
		$this->assertEquals(1, $count);

		// Search by subdivision name
		$count = EmpInjury::getCount("Three Division", EmpInjury::SORT_FIELD_EMP_SUBDIVISION_NAME);
		$this->assertEquals(5, $count);		

		// Search by employee name
		$count = EmpInjury::getCount("Saman Rajasinghe", EmpInjury::SORT_FIELD_EMP_NAME);
		$this->assertEquals(5, $count);		

		// Search by id with one match
		$count = EmpInjury::getCount('3', EmpInjury::SORT_FIELD_ID);
		$this->assertEquals(1, $count);

		// Search by id with no matches
		$count = EmpInjury::getCount('13', EmpInjury::SORT_FIELD_ID);
		$this->assertEquals(0, $count);

		// Search by result 
		$count = EmpInjury::getCount('Result 5 odd', EmpInjury::SORT_FIELD_RESULT);
		$this->assertEquals(1, $count);

		// Search by time_off_work matches, different order
		$count = EmpInjury::getCount('8', EmpInjury::SORT_FIELD_TIME_OFF_WORK);
		$this->assertEquals(1, $count);
	
		// Search by location
		$count = EmpInjury::getCount('Test3', EmpInjury::SORT_FIELD_EMP_LOCATIONS);
		$this->assertEquals(5,$count);		

		// delete all
		$this->_runQuery('DELETE from hs_hr_emp_injury');
		$count = EmpInjury::getCount();
		$this->assertEquals(0, $count);
	}

	/**
	 * Check's that the passed Ergonomic Assessment exists in the database
	 *
	 * @param EmpInjury Ergonomic Assessment to check
	 */
	private function _checkExistsInDb($injury) {

		$id = $injury->getId();		
		$empNumber = $injury->getEmpNumber();
		
		$query = "id = {$id} AND emp_number = {$empNumber} ";
		$injuryVal = $injury->getInjury();
		
		if (!empty($injuryVal)) {
			$query .= " AND injury = '{$injuryVal}'";
		}
		
		$description = $injury->getDescription();
		if (!empty($description)) {
			$query .= " AND description = '{$description}'";
		}

		$incidentDate = $injury->getIncidentDate();
		if (!empty($incidentDate)) {
			$query .= " AND incident_date = '{$incidentDate}'";
		}

		$reportedDate = $injury->getReportedDate();
		if (!empty($reportedDate)) {
			$query .= " AND reported_date = '{$reportedDate}'";
		}

		$timeOffWork = $injury->getTimeOffWork();
		if (!empty($timeOffWork)) {
			$query .= " AND time_off_work = {$timeOffWork}";
		}
		
		$result = $injury->getResult();
		if (!empty($result)) {
			$query .= " AND result = '{$result}'";
		}		

	    $this->assertEquals(1, $this->_getNumRows($query));
	}

    /**
     * Returns the number of rows in the hs_hr_emp_injury table
     *
     * @param  string $where where clause
     * @return int number of rows
     */
    private function _getNumRows($where = null) {

    	$sql = "SELECT COUNT(*) FROM hs_hr_emp_injury";
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
     * Compares two array of EmpInjury objects verifing they contain the same
     * objects, without considering the order
     *
     * Objects in first array should be indexed by their id's
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareAssessments($expected, $result) {
    	$this->assertEquals(count($expected), count($result));

		foreach ($result as $injury) {
			$this->assertTrue($injury instanceof EmpInjury, "Should return EmpInjury objects");

			$id = $injury->getId();
			$this->assertEquals($expected[$id], $injury);
		}
    }

    /**
     * Compares two array of EmpInjury objects verifing they contain the same
     * objects and considering the order
     *
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareAssessmentsWithOrder($expected, $result) {
        $this->assertEquals(count($expected), count($result));
        $i = 0;
        foreach ($expected as $injury) {
            $this->assertEquals($injury, $result[$i]);
            $i++;
        }
    }

    /**
     * Compares an array of EmpInjury objects with an array containing 
     * ergonomic injury data.
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareAssessmentsArrayWithOrder($expected, $result) {
        $this->assertEquals(count($expected), count($result));
        $i = 0;
        foreach ($expected as $injury) {
			$this->assertEquals($injury->getId(), $result[$i][0]);
			$this->assertEquals($injury->getEmpName(), $result[$i][1]);
			$this->assertEquals($injury->getSubdivisionName(), $result[$i][2]);
			$this->assertEquals($injury->getLocations(), $result[$i][3]);
			$this->assertEquals($injury->getIncidentDate(), $result[$i][4]);
			$this->assertEquals($injury->getReportedDate(), $result[$i][5]);
			$this->assertEquals($injury->getInjury(), $result[$i][6]);
			$this->assertEquals($injury->getTimeOffWork(), $result[$i][7]);
			$this->assertEquals($injury->getResult(), $result[$i][8]);
	
            $i++;
        }
    }

    /**
     * Create a EmpInjury object with the passed parameters
     */ 
    private function _getEmpInjury($id, $empNumber, $injury, $description, $incidentDate, $reportedDate, $timeOffWork, $result, 
    		$empName = null, $subdivisionName = null, $locations = null) {
    	$injuryObj = new EmpInjury($id);
		$injuryObj->setEmpNumber($empNumber);
		$injuryObj->setInjury($injury);
		$injuryObj->setDescription($description);
		$injuryObj->setIncidentDate($incidentDate);
		$injuryObj->setReportedDate($reportedDate);
		$injuryObj->setTimeOffWork($timeOffWork);
		$injuryObj->setResult($result);		

		if (!empty($empName)) {		
			$injuryObj->setEmpName($empName);
		}
		if (!empty($subdivisionName)) {		
			$injuryObj->setSubDivisionName($subdivisionName);
		}
		if (!empty($locations)) {		
			$injuryObj->setLocations($locations);
		}
		
    	return $injuryObj;
    }

    /**
     * Saves the given EmpInjury objects in the database
     *
     * @param array $injuries Array of EmpInjury objects to save.
     */
    private function _createEmpInjuries($injuries) {
		foreach ($injuries as $injury) {


  
  
			$sql = sprintf("INSERT INTO hs_hr_emp_injury(id, emp_number, injury, description, ".
						"incident_date, reported_date, time_off_work, result) " .
                        "VALUES(%d, %d, '%s', '%s', '%s', '%s', '%s', '%s')",
                        $injury->getId(), $injury->getEmpNumber(), $injury->getInjury(),
                        $injury->getDescription(), $injury->getIncidentDate(),
                        $injury->getReportedDate(), $injury->getTimeOffWork(),
                        $injury->getResult());
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

// Call EmpInjuryTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "EmpInjuryTest::main") {
    EmpInjuryTest::main();
}
?>
