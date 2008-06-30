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

// Call TrainingTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "TrainingTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once "testConf.php";
require_once ROOT_PATH."/lib/confs/Conf.php";
require_once ROOT_PATH."/lib/confs/sysConf.php";
require_once ROOT_PATH."/lib/models/training/Training.php";
require_once ROOT_PATH."/lib/common/UniqueIDGenerator.php";
require_once ROOT_PATH."/lib/common/LocaleUtil.php";

/**
 * Test class for Training
 */
class TrainingTest extends PHPUnit_Framework_TestCase {

	private $trainings;
	
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("TrainingTest");
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

		// Create employees
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
        			"VALUES(11, '0011', 'Rajasinghe', 'Saman', 'Marlon')");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
        			"VALUES(12, '0022', 'Jayasinghe', 'Aruna', 'Shantha')");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
        			"VALUES(13, '0023', 'Karunarathne', 'John', 'Kamal')");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
        			"VALUES(14, '0024', 'Silva', 'Pushpa', 'Malini')");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
        			"VALUES(15, '0025', 'Perera', 'Janith', 'Prasanna')");
				
		// Create Employee Trainings
		$state = Training::STATE_REQUESTED;
		for ($i=1; $i<11; $i++) {
			$type = ($i % 2) ? "odd" : "even";
				 
			
			$description = "Description $i $type";
			$state++;
			if ($state > Training::STATE_COMPLETED) {
				$state = Training::STATE_REQUESTED;
			}
			$trainingCourse = "training $i $type";
			$cost = "$i.10";
			$company = "Company $i $type";
			$notes = "Notes $i $type";
			$timeOffWork = "$i";
			$result = "Result $i $type";
   			$employees = "";
   		
   			$usrDefinedId = "USR-" . ($i-1); 
			$this->trainings[$i] = $this->_getTraining($i, $usrDefinedId, $description, $state, $trainingCourse, $cost, $company, $notes, $employees);
		}  														
		$this->_createTrainings($this->trainings);

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
		$this->_runQuery("TRUNCATE TABLE `hs_hr_training`");
		$this->_runQuery("TRUNCATE TABLE `hs_hr_training_employee`");	
		$this->_runQuery("TRUNCATE TABLE `hs_hr_employee`");		
	}
	
	/**
	 * Test the save function
	 */
	public function testSave() {

		// new - no employees assigned
		$before = $this->_getNumRows();
		
		$training = $this->_getTraining(null, "test 1", "desc 1", Training::STATE_REQUESTED, "abc", "12.00", "def", "ghi"); 
		$id = $training->save();
		$this->assertEquals(($before + 1), $this->_getNumRows());
		$this->_checkExistsInDb($training);

		// update
		$before = $this->_getNumRows();
		$training = $this->_getTraining($id, "test 2", 'Training test again', Training::STATE_COMPLETED, "daaa", "11.00", "aaa", "bbb");

		$newId = $training->save();
		$this->assertEquals($id, $newId);
		$this->assertEquals($before, $this->_getNumRows());
		$this->_checkExistsInDb($training);

		// Invalid ID	
		$training = $this->_getTraining("1x", "test 3", 'Training test again', Training::STATE_COMPLETED, "daaa", "11.00", "aaa", "bbb");	
		try {
			$training->save();
			$this->fail("Exception expected");
		} catch (TrainingException $e) {
			$this->assertEquals(TrainingException::INVALID_PARAMETER, $e->getCode());
		}

		// With employees assigned		
		$before = $this->_getNumRows();
		
		$employees = array(array('emp_number'=>11), array('emp_number'=>14));
		$training = $this->_getTraining(null, "test 1", "desc 1", Training::STATE_REQUESTED, "abc", "12.00", "def", "ghi", $employees); 
		$id = $training->save();
		$this->assertEquals(($before + 1), $this->_getNumRows());
		$this->_checkExistsInDb($training);		
	}

    /**
     * Test for function getAll()
     */
    public function testGetAll() {

        $list = Training::getAll();
        $this->_compareAssessments($this->trainings, $list);
        
        // Delete all
        $this->_runQuery("Delete from hs_hr_training");
        $list = Training::getAll();
        $this->assertTrue(is_array($list));
        $this->assertEquals(0, count($list));
    }

    /**
     * Test the GetTraining function
     */
    public function testGetTraining() {

		// Invalid id
		try {
        	$training = Training::getTraining('3e');
			$this->fail("Exception expected");
		} catch (TrainingException $e) {
			$this->assertEquals(TrainingException::INVALID_PARAMETER, $e->getCode());
		}		
		
		// Existing id's
        $training = Training::getTraining(1);
        $this->assertEquals($this->trainings[1], $training);


		// Non existing id
        $training = Training::getTraining(111);
        $this->assertNull($training);
        
        // Training with employees assigned.
        $employees = array(array('emp_number'=>12, 'emp_name' => 'Aruna Jayasinghe'), array('emp_number'=>14, 'emp_name' => 'Pushpa Silva'));
		$trainings[] = $this->_getTraining(111, "Test xi", "desc 1", Training::STATE_COMPLETED, " a course", "110.00", "training company", "some notes", $employees);  														
		$this->_createTrainings($trainings);

        $training2 = Training::getTraining(111);
        $this->assertEquals($trainings[0], $training2);		        
    }

	/**
	 * test the Training delete function.
	 */
	public function testDelete() {

		$before = $this->_getNumRows();

		// invalid params
		try {
			Training::delete(34);
			$this->fail("Exception not thrown");
		} catch (TrainingException $e) {
			$this->assertEquals(TrainingException::INVALID_PARAMETER, $e->getCode());
		}

		// invalid params
		try {
			Training::delete(array(1, 'w', 12));
			$this->fail("Exception not thrown");
		} catch (TrainingException $e) {
			$this->assertEquals(TrainingException::INVALID_PARAMETER, $e->getCode());
		}

		// empty array
		$res = Training::delete(array());
		$this->assertEquals(0, $res);
		$this->assertEquals($before, $this->_getNumRows());

		// no matches
		$res = Training::delete(array(12, 22));
		$this->assertEquals(0, $res);
		$this->assertEquals($before, $this->_getNumRows());

		// one match
		$res = Training::delete(array(1, 21));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// one more
		$res = Training::delete(array(3));
		$this->assertEquals(1, $res);
		$this->assertEquals(1, $before - $this->_getNumRows());

		$before = $this->_getNumRows();

		// rest
		$res = Training::delete(array(2, 4, 5, 6, 7, 8, 9, 10));
		$this->assertEquals(8, $res);
		$this->assertEquals(8, $before - $this->_getNumRows());
		$this->assertEquals(0, $this->_getNumRows());

	}

	/**
	 * Test the getListForView function
	 */
	public function testGetListForView() {

		// Get all
		$list = Training::getListForView();
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$this->_compareAssessmentsArrayWithOrder($this->trainings, $list);

		// Get all in reverse order of description
		$list = Training::getListForView(0, '', Training::SORT_FIELD_NONE, Training::SORT_FIELD_DESCRIPTION, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$expected = array($this->trainings[9],$this->trainings[8],$this->trainings[7],$this->trainings[6],$this->trainings[5],
				$this->trainings[4],$this->trainings[3],$this->trainings[2],$this->trainings[10],$this->trainings[1]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Get all in state order
		$list = Training::getListForView(0, '', Training::SORT_FIELD_NONE, Training::SORT_FIELD_STATE, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));

		$expected = array($this->trainings[3],$this->trainings[6],$this->trainings[9],$this->trainings[1],$this->trainings[4],
				$this->trainings[7],$this->trainings[10],$this->trainings[2],$this->trainings[5], $this->trainings[8]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Get all in user defined id order
		$list = Training::getListForView(0, '', Training::SORT_FIELD_NONE, Training::SORT_FIELD_USER_DEFINED_ID, 'DESC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(10, count($list));
		$expected = array($this->trainings[10],$this->trainings[9],$this->trainings[8],$this->trainings[7],$this->trainings[6],$this->trainings[5],
				$this->trainings[4],$this->trainings[3],$this->trainings[2],$this->trainings[1]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by user defined id 
		$list = Training::getListForView(0, 'USR-2', Training::SORT_FIELD_USER_DEFINED_ID, Training::SORT_FIELD_USER_DEFINED_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->trainings[3]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by user defined id with no match
		$list = Training::getListForView(0, '12', Training::SORT_FIELD_USER_DEFINED_ID, Training::SORT_FIELD_USER_DEFINED_ID, 'ASC');
		$this->assertNull($list);

		// Search by description
		$list = Training::getListForView(0, "even", Training::SORT_FIELD_DESCRIPTION, Training::SORT_FIELD_USER_DEFINED_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(5, count($list));
		$expected = array($this->trainings[2],$this->trainings[4],$this->trainings[6],$this->trainings[8],$this->trainings[10]);		
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by description, single match
		$list = Training::getListForView(0, "Description 4 even", Training::SORT_FIELD_DESCRIPTION, Training::SORT_FIELD_USER_DEFINED_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(1, count($list));
		$expected = array($this->trainings[4]);		
		$this->_compareAssessmentsArrayWithOrder($expected, $list);

		// Search by state
		$list = Training::getListForView(0,  Training::STATE_REQUESTED, Training::SORT_FIELD_STATE, Training::SORT_FIELD_USER_DEFINED_ID, 'ASC');
		$this->assertTrue(is_array($list));
		$this->assertEquals(3, count($list));
		$expected = array($this->trainings[3],$this->trainings[6],$this->trainings[9]);
		$this->_compareAssessmentsArrayWithOrder($expected, $list);
			
		// when no trainings injuries are available
		$this->_runQuery('DELETE from hs_hr_training');
		$list = Training::getListForView();
		$this->assertNull($list);
	}

	/**
	 * Test count method
	 */
	public function testCount() {

		// Count all
		$count = Training::getCount();
		$this->assertEquals(10, $count);

		// Get all
		$count = Training::getCount('', Training::SORT_FIELD_NONE);
		$this->assertEquals(10, $count);

		// Search by user defined id 
		$count = Training::getCount('USR-2', Training::SORT_FIELD_USER_DEFINED_ID);
		$this->assertEquals(1, $count);

		// Search by user defined id with no match
		$count = Training::getCount('12', Training::SORT_FIELD_USER_DEFINED_ID);
		$this->assertEquals(0, $count);

		// Search by description
		$count = Training::getCount("even", Training::SORT_FIELD_DESCRIPTION);
		$this->assertEquals(5, $count);		

		// Search by description, single match
		$count = Training::getCount("Description 4 even", Training::SORT_FIELD_DESCRIPTION);
		$this->assertEquals(1, $count);		

		// Search by state
		$count = Training::getCount(Training::STATE_REQUESTED, Training::SORT_FIELD_STATE);
		$this->assertEquals(3, $count);				
				
		// delete all
		$this->_runQuery('DELETE from hs_hr_training');
		$count = Training::getCount();
		$this->assertEquals(0, $count);
	}

	/**
	 * Check's that the passed Ergonomic Assessment exists in the database
	 *
	 * @param Training Ergonomic Assessment to check
	 */
	private function _checkExistsInDb($training) {

		$id = $training->getId();				
		$query = "id = {$id} ";
		
		$userDefinedId = $training->getUserDefinedId();		
		if (!empty($userDefinedId)) {
			$query .= " AND user_defined_id = '{$userDefinedId}'";
		}
		
		$description = $training->getDescription();
		if (!empty($description)) {
			$query .= " AND description = '{$description}'";
		}

		$state = $training->getState();
		$query .= " AND state = '{$state}'";

		$trainingCourse = $training->getTrainingCourse();
		if (!empty($trainingCourse)) {
			$query .= " AND training_course = '{$trainingCourse}'";
		}
		
		$cost = $training->getCost();
		if (!empty($cost)) {
			$query .= " AND cost = '{$cost}'";
		}

		$company = $training->getCompany();
		if (!empty($company)) {
			$query .= " AND company = '{$company}'";
		}

		$notes = $training->getNotes();
		if (!empty($notes)) {
			$query .= " AND notes = '{$notes}'";
		}

	    $this->assertEquals(1, $this->_getNumRows($query));
	    
	    // check employees
	    $employees = $training->getEmployees();
	    if (!empty($employees)) {
	    	$count = count($employees);
	    	$empNumbers = array();
	    	foreach ($employees as $emp) {
	    		$empNumbers[] = $emp['emp_number'];
	    	}
	    	$sql = "training_id = {$id} AND emp_number IN (" . implode(',', $empNumbers) . ")";
	    	$this->assertEquals($count, $this->_getNumRows($sql, 'hs_hr_training_employee'));
	    }
	}

    /**
     * Returns the number of rows in the hs_hr_training table
     *
     * @param  string $where where clause
     * @return int number of rows
     */
    private function _getNumRows($where = null, $table = 'hs_hr_training') {

    	$sql = "SELECT COUNT(*) FROM $table";
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
     * Compares two array of Training objects verifing they contain the same
     * objects, without considering the order
     *
     * Objects in first array should be indexed by their id's
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareAssessments($expected, $result) {
    	$this->assertEquals(count($expected), count($result));

		foreach ($result as $training) {
			$this->assertTrue($training instanceof Training, "Should return Training objects");

			$id = $training->getId();
			$this->assertEquals($expected[$id], $training);
		}
    }

    /**
     * Compares two array of Training objects verifing they contain the same
     * objects and considering the order
     *
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareAssessmentsWithOrder($expected, $result) {
        $this->assertEquals(count($expected), count($result));
        $i = 0;
        foreach ($expected as $training) {
            $this->assertEquals($training, $result[$i]);
            $i++;
        }
    }

    /**
     * Compares an array of Training objects with an array containing 
     * ergonomic training data.
     *
     * @param array $expected Expected
     * @param array $result Result
     */
    private function _compareAssessmentsArrayWithOrder($expected, $result) {
        $this->assertEquals(count($expected), count($result));
        $i = 0;
        foreach ($expected as $training) {
			$this->assertEquals($training->getId(), $result[$i][0]);
			$this->assertEquals($training->getUserDefinedId(), $result[$i][1]);
			$this->assertEquals($training->getDescription(), $result[$i][2]);
			$this->assertEquals($training->getState(), $result[$i][3]);
            $i++;
        }
    }

    /**
     * Create a Training object with the passed parameters
     */ 
    private function _getTraining($id, $userDefinedId, $description, $state, $trainingCourse, $cost, $company, $notes, $employees = null) {
    			
    	$trainingObj = new Training($id);
		$trainingObj->setUserDefinedId($userDefinedId);
		$trainingObj->setDescription($description);		
		$trainingObj->setState($state);
		$trainingObj->setTrainingCourse($trainingCourse);
		$trainingObj->setCost($cost);
		$trainingObj->setCompany($company);
		$trainingObj->setNotes($notes);		

		if (empty($employees)) {
			$employees = array();
		}		
		$trainingObj->setEmployees($employees);
		
    	return $trainingObj;
    }

    /**
     * Saves the given Training objects in the database
     *
     * @param array $trainings Array of Training objects to save.
     */
    private function _createTrainings($trainings) {
		foreach ($trainings as $training) { 
			$sql = sprintf("INSERT INTO hs_hr_training(id, user_defined_id, description, state, training_course, cost, company, notes) ".
                        "VALUES(%d, '%s', '%s', %d, '%s', '%s', '%s', '%s')",
                        $training->getId(), $training->getUserDefinedId(), $training->getDescription(),
                        $training->getState(), $training->getTrainingCourse(),
                        $training->getCost(), $training->getCompany(),
                        $training->getNotes());
            $this->_runQuery($sql);
            
            $employees = $training->getEmployees();
            if (!empty($employees)) {
            	foreach ($employees as $emp) {
					$sql = sprintf("INSERT INTO hs_hr_training_employee(training_id, emp_number) ".
		                        "VALUES(%d, %d)", $training->getId(), $emp['emp_number']);
		            $this->_runQuery($sql);            		
            	}
            }
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

// Call TrainingTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "TrainingTest::main") {
    TrainingTest::main();
}
?>
