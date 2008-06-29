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

// Call RiskAssessmentTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "RiskAssessmentTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once "testConf.php";
require_once ROOT_PATH."/lib/confs/Conf.php";
require_once ROOT_PATH."/lib/confs/sysConf.php";
require_once ROOT_PATH."/lib/models/healthAndSafety/RiskAssessment.php";
require_once ROOT_PATH."/lib/common/UniqueIDGenerator.php";
require_once ROOT_PATH."/lib/common/LocaleUtil.php";

/**
 * Test class for RiskAssessment
 */
class RiskAssessmentTest extends PHPUnit_Framework_TestCase {

	private $riskAssessments;
	
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("RiskAssessmentTest");
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

        // Insert location
        $this->_runQuery("INSERT INTO hs_hr_location(loc_code, loc_name, loc_country, loc_add, loc_zip) " .
                       "VALUES('LOC001', 'Test', 'LK', '111 Main street', '20000')");

        // Insert sub divisions
        // Company - ID 1
        $this->_runQuery("INSERT INTO hs_hr_compstructtree(title, description, loc_code, lft, rgt, id, parnt, dept_id) VALUES " .
                "('A Company','',NULL,1,10,1,0,'')");
        // ID 2
        $this->_runQuery("INSERT INTO hs_hr_compstructtree(title, description, loc_code, lft, rgt, id, parnt, dept_id) VALUES " .
                "('Test Division','safsaf','LOC001',2,3,2,1,'001')");

        // ID 3
        $this->_runQuery("INSERT INTO hs_hr_compstructtree(title, description, loc_code, lft, rgt, id, parnt, dept_id) VALUES " .
                "('Three Division','test','LOC001',4,5,3,1,'002')");
		
		// Create Risk Assessments
		for ($i=1; $i<11; $i++) {
			$startDiff = $i + 1;
			$endDiff = $i + 5;
	        $startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-{$startDiff} days"));
    	    $endDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+{$endDiff} days"));

			if ($i % 2) {
				// Odd
				$subDivisionId = 3;
				$subDivisionName = 'Three Division';
				$status = RiskAssessment::STATUS_RESOLVED;
			} else {	
				// Even			
				$subDivisionId = 2;
				$subDivisionName = 'Test Division';
				$status = RiskAssessment::STATUS_UNRESOLVED;
			}
			$this->riskAssessments[$i] = $this->_getRiskAssessment($i, $subDivisionId, $startDate, $endDate, "Test risk {$i}", $status, $subDivisionName);
		}  														
		$this->_createRiskAssessments($this->riskAssessments);

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
		$this->_runQuery("TRUNCATE TABLE `hs_hr_risk_assessments`");		
	}

	/**
	 * Test the save function
	 */
	public function testSave() {

		// new
		$before = $this->_getNumRows();
		$startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-5 days"));
		$endDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-1 days"));
		
		$assessment = $this->_getRiskAssessment(null, 2, $startDate, $endDate, 'A test risk', 0);

		$id = $assessment->save();
		$this->assertEquals(($before + 1), $this->_getNumRows());
		$this->_checkExistsInDb($assessment);

		// update
		$before = $this->_getNumRows();
		$startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$endDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-2 days"));
		
		$assessment = $this->_getRiskAssessment($id, 3, $startDate, $endDate, 'Another risk', 1);

		$newId = $assessment->save();
		$this->assertEquals($id, $newId);
		$this->assertEquals($before, $this->_getNumRows());
		$this->_checkExistsInDb($assessment);

		// without sub division id
		$assessment = $this->_getRiskAssessment(null, null, $startDate, $endDate, 'Another risk', 1);
		
		try {
			$assessment->save();
			$this->fail("Exception expected");
		} catch (RiskAssessmentException $e) {
			$this->assertEquals(RiskAssessmentException::MISSING_PARAMETERS, $e->getCode());
		}

		// Invalid sub division id
		$assessment = $this->_getRiskAssessment(null, 'ax', $startDate, $endDate, 'Another risk', 1);
		
		try {
			$assessment->save();
			$this->fail("Exception expected");
		} catch (RiskAssessmentException $e) {
			$this->assertEquals(RiskAssessmentException::INVALID_PARAMETER, $e->getCode());
		}

		// Invalid ID	
		$assessment = $this->_getRiskAssessment('a1', 2, $startDate, $endDate, 'Another risk', 1);
		
		try {
			$assessment->save();
			$this->fail("Exception expected");
		} catch (RiskAssessmentException $e) {
			$this->assertEquals(RiskAssessmentException::INVALID_PARAMETER, $e->getCode());
		}
	}

    /**
     * Test for function getAll()
     */
    public function testGetAll() {

        $list = RiskAssessment::getAll();
        $this->_compareAssessments($this->riskAssessments, $list);
        
        // Delete all
        $this->_runQuery("Delete from hs_hr_risk_assessments");
        $list = RiskAssessment::getAll();
        $this->assertTrue(is_array($list));
        $this->assertEquals(0, count($list));
    }

    /**
     * Test the GetRiskAssessment function
     */
    public function testGetRiskAssessment() {

		// Invalid id
		try {
        	$assessment = RiskAssessment::getRiskAssessment('3e');
			$this->fail("Exception expected");
		} catch (RiskAssessmentException $e) {
			$this->assertEquals(RiskAssessmentException::INVALID_PARAMETER, $e->getCode());
		}		
		
		// Existing id's
        $assessment = RiskAssessment::getRiskAssessment(1);
        $this->assertEquals($this->riskAssessments[1], $assessment);


		// Non existing id
        $assessment = RiskAssessment::getRiskAssessment(111);
        $this->assertNull($assessment);
    }

	/**
	 * test the RiskAssessment delete function.
	 */
	public function testDelete() {

		$before = $this->_getNumRows();

		// invalid params
		try {
			RiskAssessment::delete(34);
			$this->fail("Exception not thrown");
		} catch (RiskAssessmentException $e) {
			$this->assertEquals(RiskAssessmentException::INVALID_PARAMETER, $e->getCode());
		}

		// invalid params
		try {
			RiskAssessment::delete(array(1, 'w', 12));
			$this->fail("Exception not thrown");
		} catch (RiskAssessmentException $e) {
			$this->assertEquals(RiskAssessmentException::INVALID_PARAMETER, $e->getCode());
		}

		// empty array
		$res = RiskAssessment::delete(array());
		$this->assertEquals(0, $res);
		$this->assertEquals($before, $this->_getNumRows());

		// no matches
		$res = RiskAssessment::delete(array(12, 22));
		$this->assertEquals(0, $res);
		$this->assertEquals($before, $this->_getNumRows());

		// one match
		$res = RiskAssessment::delete(array(1, 21));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// one more
		$res = RiskAssessment::delete(array(3));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// rest
		$res = RiskAssessment::delete(array(2, 4, 5, 6, 7, 8, 9, 10));
		$this->assertEquals(8, $res);
		$this->assertEquals(8, $before - $this->_getNumRows());
		$this->assertEquals(0, $this->_getNumRows());

	}

	/**
	 * Test the getListForView function
	 */
	public function testGetListForView() {

		// Get all
		$list = RiskAssessment::getListForView();
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$this->_compareAssessmentsArrayWithOrder($this->riskAssessments, $list);

		// Get all in reverse order by sub division name
		$list = RiskAssessment::getListForView(0, '', RiskAssessment::SORT_FIELD_NONE, RiskAssessment::SORT_FIELD_SUBDIVISION_NAME, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$expected = array($this->riskAssessments[1],$this->riskAssessments[3],$this->riskAssessments[5],$this->riskAssessments[7],$this->riskAssessments[9],
			$this->riskAssessments[2],$this->riskAssessments[4],$this->riskAssessments[6],$this->riskAssessments[8],$this->riskAssessments[10]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by start_date with exact match
		$searchDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$list = RiskAssessment::getListForView(0, $searchDate, RiskAssessment::SORT_FIELD_START_DATE, RiskAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->riskAssessments[3]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by end_date with exact match
		$searchDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+10 days"));
		$list = RiskAssessment::getListForView(0, $searchDate, RiskAssessment::SORT_FIELD_END_DATE, RiskAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->riskAssessments[5]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by description with multiple matches
		$list = RiskAssessment::getListForView(0, "Test risk", RiskAssessment::SORT_FIELD_DESCRIPTION, RiskAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$this->_compareAssessmentsArrayWithOrder($this->riskAssessments, $list);

		// Search by description with one match
		$list = RiskAssessment::getListForView(0, "Test risk 5", RiskAssessment::SORT_FIELD_DESCRIPTION, RiskAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->riskAssessments[5]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by id with one match
		$list = RiskAssessment::getListForView(0, '3', RiskAssessment::SORT_FIELD_ID, RiskAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->riskAssessments[3]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by id with no matches
		$list = RiskAssessment::getListForView(0, '13', RiskAssessment::SORT_FIELD_ID, RiskAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertNull($list);

		// Search by status matches
		$list = RiskAssessment::getListForView(0, RiskAssessment::STATUS_UNRESOLVED, RiskAssessment::SORT_FIELD_STATUS, RiskAssessment::SORT_FIELD_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->riskAssessments[2],$this->riskAssessments[4],$this->riskAssessments[6],$this->riskAssessments[8],$this->riskAssessments[10]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by status matches, different order
		$list = RiskAssessment::getListForView(0, RiskAssessment::STATUS_RESOLVED, RiskAssessment::SORT_FIELD_STATUS, RiskAssessment::SORT_FIELD_ID, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->riskAssessments[9],$this->riskAssessments[7],$this->riskAssessments[5],$this->riskAssessments[3],$this->riskAssessments[1]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// when no risk assessments are available
		$this->_runQuery('DELETE from hs_hr_risk_assessments');
		$list = RiskAssessment::getListForView();
		$this->assertNull($list);

	}

	/**
	 * Test count method
	 */
	public function testCount() {

		// Count all
		$count = RiskAssessment::getCount();
		$this->assertEquals(10, $count);

		// Get all
		$count = RiskAssessment::getCount('', RiskAssessment::SORT_FIELD_NONE);
		$this->assertEquals(10, $count);

		// Search by start_date with exact match
		$searchDate = $startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("-4 days"));
		$count = RiskAssessment::getCount($searchDate, RiskAssessment::SORT_FIELD_START_DATE);
		$this->assertEquals(1, $count);

		// Search by end_date with exact match
		$searchDate = $startDate = date(LocaleUtil::STANDARD_DATE_FORMAT, strtotime("+10 days"));
		$count = RiskAssessment::getCount($searchDate, RiskAssessment::SORT_FIELD_END_DATE);
		$this->assertEquals(1, $count);

		// Search by description with multiple matches
		$count = RiskAssessment::getCount("Test risk", RiskAssessment::SORT_FIELD_DESCRIPTION);
		$this->assertEquals(10, $count);

		// Search by description with one match
		$count = RiskAssessment::getCount("Test risk 5", RiskAssessment::SORT_FIELD_DESCRIPTION);
		$this->assertEquals(1,$count);

		// Search by id with one match
		$count = RiskAssessment::getCount('3', RiskAssessment::SORT_FIELD_ID);
		$this->assertEquals(1,$count);

		// Search by id with no matches
		$count = RiskAssessment::getCount('13', RiskAssessment::SORT_FIELD_ID);
		$this->assertEquals(0,$count);

		// Search by status matches
		$count = RiskAssessment::getCount(RiskAssessment::STATUS_UNRESOLVED, RiskAssessment::SORT_FIELD_STATUS);
		$this->assertEquals(5,$count);

		// Search by status matches
		$count = RiskAssessment::getCount(RiskAssessment::STATUS_RESOLVED, RiskAssessment::SORT_FIELD_STATUS);
		$this->assertEquals(5,$count);

		// delete all
		$this->_runQuery('DELETE from hs_hr_risk_assessments');
		$count = RiskAssessment::getCount();
		$this->assertEquals(0, $count);
	}

	/**
	 * Check's that the passed Risk Assessment exists in the database
	 *
	 * @param RiskAssessment Risk Assessment to check
	 */
	private function _checkExistsInDb($assessment) {

		$id = $assessment->getId();		
		$subDivisionId = $assessment->getSubDivisionId();
		
		$query = "id = {$id} AND subdivision_id = {$subDivisionId} ";
		$startDate = $assessment->getStartDate();
		
		if (!empty($startDate)) {
			$query .= " AND start_date = '{$startDate}'";
		}
		$endDate = $assessment->getEndDate();
		if (!empty($endDate)) {
			$query .= " AND end_date = '{$endDate}'";
		}
		
		$description = $assessment->getDescription();
		if (!empty($description)) {
			$query .= " AND description = '{$description}'";
		}
		
		$status = $assessment->getStatus();
		if (!empty($status)) {
			$query .= " AND status = {$status}";
		}

	    $this->assertEquals(1, $this->_getNumRows($query));
	}

    /**
     * Returns the number of rows in the hs_hr_risk_assessments table
     *
     * @param  string $where where clause
     * @return int number of rows
     */
    private function _getNumRows($where = null) {

    	$sql = "SELECT COUNT(*) FROM hs_hr_risk_assessments";
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
     * Compares two array of RiskAssessment objects verifing they contain the same
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
			$this->assertTrue($assessment instanceof RiskAssessment, "Should return RiskAssessment objects");

			$id = $assessment->getId();
			$this->assertEquals($expected[$id], $assessment);
		}
    }

    /**
     * Compares two array of RiskAssessment objects verifing they contain the same
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
     * Compares an array of RiskAssessment objects with an array containing 
     * risk assessment data.
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareAssessmentsArrayWithOrder($expected, $result) {
        $this->assertEquals(count($expected), count($result));
        $i = 0;
        foreach ($expected as $assessment) {
        	
			$this->assertEquals($assessment->getId(), $result[$i][0]);
			$this->assertEquals($assessment->getSubdivisionName(), $result[$i][1]);
			$this->assertEquals($assessment->getStartDate(), $result[$i][2]);
			$this->assertEquals($assessment->getEndDate(), $result[$i][3]);
			$this->assertEquals($assessment->getDescription(), $result[$i][4]);
			$this->assertEquals($assessment->getStatus(), $result[$i][5]);
			        	
            $i++;
        }
    }

    /**
     * Create a RiskAssessment object with the passed parameters
     */
    private function _getRiskAssessment($id, $subDivisionId, $startDate, $endDate, $description, $status, $subdivisionName = null) {
    	$assessment = new RiskAssessment($id);
		$assessment->setSubDivisionId($subDivisionId);
		$assessment->setStartDate($startDate);
		$assessment->setEndDate($endDate);
		$assessment->setDescription($description);
		$assessment->setStatus($status);
		
		if (!empty($subdivisionName)) {
			$assessment->setSubdivisionName($subdivisionName);			
		}
		
    	return $assessment;
    }

    /**
     * Saves the given RiskAssessment objects in the database
     *
     * @param array $assessments Array of RiskAssessment objects to save.
     */
    private function _createRiskAssessments($assessments) {
		foreach ($assessments as $assessment) {

			$sql = sprintf("INSERT INTO hs_hr_risk_assessments(id, subdivision_id, start_date, end_date, ".
						"description, status) " .
                        "VALUES(%d, %d, '%s', '%s', '%s', %d)",
                        $assessment->getId(), $assessment->getSubDivisionId(), $assessment->getStartDate(),
                        $assessment->getEndDate(),
                        $assessment->getDescription(), $assessment->getStatus());
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

// Call RiskAssessmentTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "RiskAssessmentTest::main") {
    RiskAssessmentTest::main();
}
?>
