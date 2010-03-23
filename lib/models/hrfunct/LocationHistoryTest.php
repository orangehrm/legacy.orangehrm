<?php
// Call LocationHistoryTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "LocationHistoryTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once "testConf.php";
require_once ROOT_PATH."/lib/confs/Conf.php";
require_once ROOT_PATH."/lib/confs/sysConf.php";
require_once ROOT_PATH."/lib/common/LocaleUtil.php";
require_once ROOT_PATH."/lib/common/UniqueIDGenerator.php";

require_once 'LocationHistory.php';

/**
 * Test class for LocationHistory.
 * Generated by PHPUnit_Util_Skeleton on 2008-03-11 at 20:06:47.
 */
class LocationHistoryTest extends PHPUnit_Framework_TestCase {

    private $subDivisionHistory;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("LocationHistoryTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
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
                       "VALUES('LOC001', 'Kandy', 'LK', '111 Main street', '20000')");
        $this->_runQuery("INSERT INTO hs_hr_location(loc_code, loc_name, loc_country, loc_add, loc_zip) " .
                       "VALUES('LOC002', 'Colombo', 'LK', '111 Main street', '20000')");
        $this->_runQuery("INSERT INTO hs_hr_location(loc_code, loc_name, loc_country, loc_add, loc_zip) " .
                       "VALUES('LOC003', 'England', 'LK', '111 Main street', '20000')");
        $this->_runQuery("INSERT INTO hs_hr_location(loc_code, loc_name, loc_country, loc_add, loc_zip) " .
                       "VALUES('LOC004', 'Japan', 'LK', '111 Main street', '20000')");

        // Insert employees
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
                    "VALUES(11, '0011', 'Rajasinghe', 'Saman', 'Marlon')");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
                    "VALUES(12, '0022', 'Jayasinghe', 'Aruna', 'Shantha')");

        // location history for employee 11
        $this->subDivisionHistory[1] = $this->_getLocationHistory(11, 'LOC003', 'England', '-4 years', '-2 years');
        $this->subDivisionHistory[2] = $this->_getLocationHistory(11, 'LOC004', 'Japan', '-2 years', '-1 years');
        $this->subDivisionHistory[3] = $this->_getLocationHistory(11, 'LOC001', 'Kandy', '-1 years', '-1 month');
        $this->subDivisionHistory[4] = $this->_getLocationHistory(11, 'LOC002', 'Colombo', '-1 month', null);

        // location history for employee 12
        $this->subDivisionHistory[5] = $this->_getLocationHistory(12, 'LOC002', 'Colombo', '-5 years', '-2 year');
        $this->subDivisionHistory[6] = $this->_getLocationHistory(12, 'LOC003', 'Japan', '-2 years', null);

        foreach($this->subDivisionHistory as $his) {
            $this->_insertLocationHistory($his);
        }

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
        $this->_runQuery("TRUNCATE TABLE `hs_hr_emp_location_history`");
        $this->_runQuery("TRUNCATE TABLE `hs_hr_compstructtree`");
        $this->_runQuery("TRUNCATE TABLE `hs_hr_employee`");
        $this->_runQuery("TRUNCATE TABLE `hs_hr_location`");
    }

    /**
     * Test case for updateHistory().
     */
    public function testUpdateHistory() {
        $history = new LocationHistory();

        // invalid emp number
        try {
            $history->updateHistory('ab1', 'LOC003');
            $this->fail("Exception expected");
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::INVALID_PARAMETER, $e->getCode());
        }

        // invalid location code
        try {
            $history->updateHistory(11, 'JOBA003');
            $this->fail("Exception expected");
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::INVALID_PARAMETER, $e->getCode());
        }

        // No change
        $this->assertEquals(1, $this->_getNumRows("emp_number = 12 AND code = 'LOC003' AND end_date IS NULL"));
        $before = $this->_getNumRows();
        $result = $history->updateHistory(12, 'LOC003');
        $this->assertFalse($result);
        $this->assertEquals($before, $this->_getNumRows());
        $this->assertEquals(1, $this->_getNumRows("emp_number = 12 AND code = 'LOC003' AND end_date IS NULL"));

        // Employee with 2 current items, verify allowed
        $this->_runQuery('UPDATE hs_hr_emp_location_history SET end_date = null WHERE id=' . $this->subDivisionHistory[3]->getId());

        $this->assertEquals(2, $this->_getNumRows("emp_number = 11 AND end_date IS NULL"));
        $before = $this->_getNumRows();

        // Update location already one of the current location - no change expected
        $result = $history->updateHistory(11, 'LOC001');
        $this->assertFalse($result);
        $this->assertEquals(2, $this->_getNumRows("emp_number = 11 AND end_date IS NULL"));
        $this->assertEquals($before, $this->_getNumRows());

        // Update new location, should be added to list of current locations
        $before = $this->_getNumRows();
        $result = $history->updateHistory(11, 'LOC003');
        $this->assertTrue($result);
        $this->assertEquals(3, $this->_getNumRows("emp_number = 11 AND end_date IS NULL"));
        $this->assertEquals($before + 1, $this->_getNumRows());

        // Change location
        $before = $this->_getNumRows();
        $result = $history->updateHistory(12, 'LOC001');
        $this->assertTrue($result);
        $this->assertEquals($before + 1, $this->_getNumRows());

        // Verify that existing current item's end date is not set (since multiple current items are allowed)
        $this->assertEquals(1, $this->_getNumRows("emp_number = 12 AND code = 'LOC003' AND end_date IS NULL"));
        $this->assertEquals(1, $this->_getNumRows("emp_number = 12 AND code = 'LOC001' AND end_date IS NULL"));

        // validate end date of old location not set
        $result = $this->_getMatchingRows('id = ' . $this->subDivisionHistory[6]->getId());
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
        $this->assertNull($result[0]['end_date']);

        // validate start date of new location correctly set
        $result = $this->_getMatchingRows("emp_number = 12 AND code = 'LOC001' AND end_date IS NULL");
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
        $this->assertNotNull($result[0]['start_date']);

        // Verify the start time is correct
        $startDate = $result[0]['start_date'];
        $this->assertTrue((time() - strtotime($startDate)) < 30);

        // Verify name is current
        $this->assertEquals('Kandy', $result[0]['name']);

        // Update history for employee with no current history items.
        $this->_runQuery('DELETE from hs_hr_emp_location_history');

        $this->assertEquals(0, $this->_getNumRows());
        $result = $history->updateHistory(12, 'LOC003');
        $this->assertTrue($result);
        $this->assertEquals(1, $this->_getNumRows());
        $this->assertEquals(1, $this->_getNumRows("emp_number = 12 AND code = 'LOC003' AND end_date IS NULL"));
    }

    /**
     * Test case for getHistory().
     */
    public function testGetHistory() {

        $history = new LocationHistory();

        // invalid emp number
        try {
            $list = $history->getHistory('A22');
            $this->fail("Exception expected");
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::INVALID_PARAMETER, $e->getCode());
        }

        // non existent emp number
        $list = $history->getHistory(14);
        $this->assertTrue(is_array($list));
        $this->assertEquals(0, count($list));

        // emp with 1 history item and one current items
        $list = $history->getHistory(12);
        $this->assertTrue(is_array($list));
        $this->assertEquals(1, count($list));
        $this->_compareHistory(array($this->subDivisionHistory[5]), $list);

        // emp with 3 history items and one current items
        $list = $history->getHistory(11);
        $this->assertTrue(is_array($list));
        $this->assertEquals(3, count($list));
        $this->_compareHistory(array($this->subDivisionHistory[1], $this->subDivisionHistory[2], $this->subDivisionHistory[3]), $list);

        // emp with 2 history items and 2 current items
        /*$this->_runQuery('UPDATE hs_hr_emp_location_history SET end_date = null WHERE id=' . $this->subDivisionHistory[3]->getId());
        $list = $history->getHistory(11);
        $this->assertTrue(is_array($list));
        $this->assertEquals(2, count($list));
        $this->_compareHistory(array($this->subDivisionHistory[1], $this->subDivisionHistory[2]), $list);*/


        // emp with 1 history item only
        $this->_runQuery('DELETE from hs_hr_emp_location_history WHERE emp_number = 12 AND end_date is null');
        $list = $history->getHistory(12);
        $this->assertTrue(is_array($list));
        $this->assertEquals(1, count($list));
        $this->_compareHistory(array($this->subDivisionHistory[5]), $list);

        // emp number with no history
        $this->_runQuery('DELETE from hs_hr_emp_location_history WHERE emp_number = 12');
        $list = $history->getHistory(14);
        $this->assertTrue(is_array($list));
        $this->assertEquals(0, count($list));
    }

    /**
     * Test delete() method
     */
    public function testDelete() {

        // find array of id's that are not available in database
        foreach ($this->subDivisionHistory as $hist) {
            $ids[] = $hist->getId();
        }
        $notIds = array_values(array_diff(range(1, 14), $ids));

        $before = $this->_getNumRows();
        $history = new LocationHistory();

        // invalid params
        try {
            $history->delete(34);
            $this->fail("Exception not thrown");
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::INVALID_PARAMETER, $e->getCode());
        }

        // invalid params
        try {
            $history->delete(array(1, 'w', 12));
            $this->fail("Exception not thrown");
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::INVALID_PARAMETER, $e->getCode());
        }

        // empty array
        $res = $history->delete(array());
        $this->assertEquals(0, $res);
        $this->assertEquals($before, $this->_getNumRows());

        // no matches
        $res = $history->delete(array($notIds[1], $notIds[4]));
        $this->assertEquals(0, $res);
        $this->assertEquals($before, $this->_getNumRows());

        // one match
        $res = $history->delete(array($ids[0], $notIds[3]));
        $this->assertEquals(1, $res);
        $this->assertEquals(1, $before - $this->_getNumRows());

        $before = $this->_getNumRows();

        // one more the rest
        $res = $history->delete(array($ids[2]));
        $this->assertEquals(1, $res);
        $this->assertEquals(1, $before - $this->_getNumRows());

        $before = $this->_getNumRows();

        // rest
        $res = $history->delete(array($ids[1], $ids[3], $ids[4], $ids[5]));
        $this->assertEquals(4, $res);
        $this->assertEquals(4, $before - $this->_getNumRows());

        $this->assertEquals(0, $this->_getNumRows());
    }

    /**
     * Test save() method
     */
    public function testSave() {

        // empNum missing
        $before = $this->_getNumRows();
        $history = $this->_getLocationHistory(null, 'LOC003', 'England', '-4 years', '-2 years');

        try {
            $history->save();
            $this->fail('Should throw exception');
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::INVALID_PARAMETER, $e->getCode());
        }

        $this->assertEquals($before, $this->_getNumRows());

        // code missing
        $history = $this->_getLocationHistory(11, null, 'England', '-4 years', '-2 years');

        try {
            $history->save();
            $this->fail('Should throw exception');
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::INVALID_PARAMETER, $e->getCode());
        }

        $this->assertEquals($before, $this->_getNumRows());

        // start time missing
        $history = $this->_getLocationHistory(11, 'LOC001', 'England', null, '-2 years');

        try {
            $history->save();
            $this->fail('Should throw exception');
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::INVALID_PARAMETER, $e->getCode());
        }

        $this->assertEquals($before, $this->_getNumRows());

        // Invalid emp number
        $history = $this->_getLocationHistory('X1', 'LOC001', 'England', '-3 years', '-2 years');

        try {
            $history->save();
            $this->fail('Should throw exception');
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::INVALID_PARAMETER, $e->getCode());
        }

        $this->assertEquals($before, $this->_getNumRows());

        // Invalid location code
        $history = $this->_getLocationHistory(11, 'DIV1', 'England', '-3 years', '-2 years');

        try {
            $history->save();
            $this->fail('Should throw exception');
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::INVALID_PARAMETER, $e->getCode());
        }
        $this->assertEquals($before, $this->_getNumRows());

        // Start date greater than end date
        $history = $this->_getLocationHistory(11, 'LOC001', 'England', '-3 years', '-4 years');

        try {
            $history->save();
            $this->fail('Should throw exception');
        } catch (EmpHistoryException $e) {
            $this->assertEquals(EmpHistoryException::END_BEFORE_START, $e->getCode(), $e->getMessage());
        }
        $this->assertEquals($before, $this->_getNumRows());

        // new
        $before = $this->_getNumRows();
        $history = $this->_getLocationHistory(11, 'LOC001', 'England', '-4 years', '-3 years');
        $id = $history->save();
        $this->assertEquals(($before + 1), $this->_getNumRows());
        $this->assertEquals(1, $this->_getNumRows("emp_number = 11 AND id = $id AND code = 'LOC001'"));

        // update
        $before = $this->_getNumRows();
        $history = $this->_getLocationHistory(11, 'LOC004', 'England', '-7 years', '-5 years');
        $history->setId($id);
        $newId = $history->save();
        $this->assertEquals($id, $newId);
        $this->assertEquals($before, $this->_getNumRows());
        $this->assertEquals(1, $this->_getNumRows("emp_number = 11 AND id = $id AND code = 'LOC004'"));

        // update without location code
        $before = $this->_getNumRows();
        $history = $this->_getLocationHistory(11, null, 'England', '-7 years', '-5 years');
        $history->setId($id);

        try {
            $id = $history->save();
            $this->fail('Should throw exception');
        } catch (EmpHistoryException $e) {
        }
        $this->assertEquals($before, $this->_getNumRows());

        // Add second item of same type for same employee
        $this->assertEquals(0, $this->_getNumRows("emp_number = 12 AND code = '4'"));

        $before = $this->_getNumRows();
        $history = $this->_getLocationHistory(12, 'LOC004', 'England', '-6 years', '-2 years');
        $id = $history->save();

        $history = $this->_getLocationHistory(12, 'LOC004', 'England', '-4 years', '-2 years');
        $id = $history->save();

        $this->assertEquals($before + 2, $this->_getNumRows());
        $this->assertEquals(2, $this->_getNumRows("emp_number = 12 AND code = 'LOC004'"));

        // New item without end date allowed.
        $before = $this->_getNumRows();
        $history = $this->_getLocationHistory(12, 'LOC003', 'England', '-4 years', null);
        $id = $history->save();
        $this->assertEquals(($before + 1), $this->_getNumRows());
        $this->assertEquals(1, $this->_getNumRows("emp_number = 12 AND id = $id AND code = 'LOC003' AND name='England'"));

        // New item with name not set allowed. Verify correct name is taken from location table
        $before = $this->_getNumRows();
        $history = $this->_getLocationHistory(12, 'LOC002', null, '-4 years', '-1 years');
        $id = $history->save();
        $this->assertEquals(($before + 1), $this->_getNumRows());
        $this->assertEquals(1, $this->_getNumRows("emp_number = 12 AND id = $id AND code = 'LOC002' AND name='Colombo'"));

        // Update with name null, verify name updated.
        $before = $this->_getNumRows();
        $history = $this->_getLocationHistory(12, 'LOC002', null, '-4 years', '-1 years');
        $history->setId($id);
        $newId = $history->save();
        $this->assertEquals($id, $newId);
        $this->assertEquals($before, $this->_getNumRows());

        $result = $this->_getMatchingRows('id = ' . $newId);
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
        $this->assertEquals('Colombo', $result[0]['name']);
    }

    /**
     * Compares two arrays of history objects
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareHistory($expected, $result) {
        $this->assertEquals(count($expected), count($result));

        $i = 0;
        foreach ($result as $empLocation) {
            $this->assertTrue($empLocation instanceof LocationHistory, "Should return LocationHistory objects");
            $this->assertEquals($expected[$i], $empLocation);
            $i++;
        }
    }

    /**
     * Gets a location history object with the given parameters
     */
    private function _getLocationHistory($empNum, $subDivisionCode, $locationName, $startDate, $endDate) {

        if (!empty($startDate)) {
            $startDate = date(LocaleUtil::STANDARD_TIMESTAMP_FORMAT, strtotime($startDate));
        }

        if (!empty($endDate)) {
            $endDate = date(LocaleUtil::STANDARD_TIMESTAMP_FORMAT, strtotime($endDate));
        }

        $locationHis = new LocationHistory();
        $locationHis->setEmpNumber($empNum);
        $locationHis->setCode($subDivisionCode);
        $locationHis->setName($locationName);
        $locationHis->setStartDate($startDate);
        $locationHis->setEndDate($endDate);

        return $locationHis;
    }

    /**
     * Insert given location history item to the database
     */
    private function _insertLocationHistory(&$locationHistory) {

        $startDate = $locationHistory->getStartDate();
        $endDate = $locationHistory->getEndDate();
        $startDate = is_null($startDate) ? 'null' : "'{$startDate}'";
        $endDate = is_null($endDate) ? 'null' : "'{$endDate}'";

        $sql = sprintf("INSERT INTO hs_hr_emp_location_history(emp_number,code, name," .
                        "start_date, end_date) VALUES (%d, '%s', '%s', %s, %s)",
                        $locationHistory->getEmpNumber(), $locationHistory->getCode(),
                        $locationHistory->getName(), $startDate,
                        $endDate);

        $this->_runQuery($sql);
        $id = mysql_insert_id();
        $locationHistory->setId($id);
    }

    /**
     * Returns the number of rows in the hs_hr_emp_location_history table
     *
     * @param  string $where where clause
     * @return int number of rows
     */
    private function _getNumRows($where = null) {

        $sql = "SELECT COUNT(*) FROM hs_hr_emp_location_history";
        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $result = $this->_runQuery($sql);

        $row = mysql_fetch_array($result, MYSQL_NUM);
        $count = $row[0];
        return $count;
    }

    /**
     * Returns rows that match the given query from the database.
     *
     * @param  string $where where clause
     * @return Array 2D associative array with results. Null if no matching results found
     */
    private function _getMatchingRows($where = null) {

        $sql = "SELECT * FROM hs_hr_emp_location_history";
        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $list = null;

        $result = mysql_query($sql);
        while ($result && ($row = mysql_fetch_assoc($result))) {
            $list[] = $row;
        }

        return $list;
    }

    private function _runQuery($sql) {
        $result = mysql_query($sql);

        if ($result === false) {
            $error = mysql_error();
            $error .= 'SQL = ' . $sql;
            $this->fail($error);
        }
        return $result;
    }
}

// Call LocationHistoryTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "LocationHistoryTest::main") {
    LocationHistoryTest::main();
}
?>
