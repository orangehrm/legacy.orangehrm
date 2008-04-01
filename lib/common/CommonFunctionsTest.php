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
 */

// Call CommonFunctionsTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "CommonFunctionsTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'testConf.php';
require_once 'CommonFunctions.php';

/**
 * Test class for CommonFunctions.
 * Generated by PHPUnit_Util_Skeleton on 2007-07-16 at 12:29:14.
 */
class CommonFunctionsTest extends PHPUnit_Framework_TestCase {
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("CommonFunctionsTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
    }

    /**
     * Test case for getCssClassForMessage method
     */
    public function testGetCssClassForMessage() {

    	$this->assertEquals("success", CommonFunctions::getCssClassForMessage("ADD_SUCCESS"));
    	$this->assertEquals("failure", CommonFunctions::getCssClassForMessage("JUST_ANOTHER_Failure"));
    	$this->assertEquals("required", CommonFunctions::getCssClassForMessage("REQUIRED"));
    	$this->assertEquals("", CommonFunctions::getCssClassForMessage(""));
    	$this->assertEquals("", CommonFunctions::getCssClassForMessage(null));
    }

    /**
     * Test getTimeInHours() method
     */
    public function testGetTimeInHours() {

    	$seconds = 10102; // 10102s = 2.806111 hours

    	// Default: 2 decimals
    	$this->assertEquals("2.81", CommonFunctions::getTimeInHours($seconds));

    	// Zero decimals
    	$this->assertEquals("3", CommonFunctions::getTimeInHours($seconds, 0));

    	// One decimal
    	$this->assertEquals("2.8", CommonFunctions::getTimeInHours($seconds, 1));

    	// 3 decimals
    	$this->assertEquals("2.806", CommonFunctions::getTimeInHours($seconds, 3));

    	$seconds = 1500; // 25s = 0.416666667 hours
    	$this->assertEquals("0", CommonFunctions::getTimeInHours($seconds, 0));
    	$this->assertEquals("0.4", CommonFunctions::getTimeInHours($seconds, 1));
    	$this->assertEquals("0.42", CommonFunctions::getTimeInHours($seconds, 2));
    	$this->assertEquals("0.417", CommonFunctions::getTimeInHours($seconds, 3));
    }

    /**
     * Test isValidId() method
     */
    public function testIsValidId() {

		$this->assertFalse(CommonFunctions::IsValidId(-1));
		$this->assertFalse(CommonFunctions::IsValidId("-1"));

		$this->assertTrue(CommonFunctions::IsValidId(0));
		$this->assertTrue(CommonFunctions::IsValidId("0"));
		$this->assertTrue(CommonFunctions::IsValidId("000"));

		$this->assertFalse(CommonFunctions::IsValidId(null));
		$this->assertFalse(CommonFunctions::IsValidId(""));

		$this->assertFalse(CommonFunctions::IsValidId("asdf"));
		$this->assertFalse(CommonFunctions::IsValidId("'-+'"));

		$this->assertFalse(CommonFunctions::IsValidId("2.11"));
		$this->assertFalse(CommonFunctions::IsValidId("2.00"));
		$this->assertFalse(CommonFunctions::IsValidId("0.00"));
		$this->assertFalse(CommonFunctions::IsValidId("0.10"));

		// Try scientific notation. Shouldn't work
		$this->assertFalse(CommonFunctions::IsValidId("2e5"));

		// Valid numbers
		$this->assertTrue(CommonFunctions::IsValidId(100));
		$this->assertTrue(CommonFunctions::IsValidId("100"));

		$this->assertTrue(CommonFunctions::IsValidId(3));
		$this->assertTrue(CommonFunctions::IsValidId("03"));
		$this->assertTrue(CommonFunctions::IsValidId("031"));

        // With Prefixes
        $this->assertTrue(CommonFunctions::IsValidId('LOC101', 'LOC'));
        $this->assertFalse(CommonFunctions::IsValidId('LOC101', 'EMP'));
        $this->assertFalse(CommonFunctions::IsValidId('101', 'LOC'));
        $this->assertFalse(CommonFunctions::IsValidId('LOCA1', 'LOC'));
        $this->assertFalse(CommonFunctions::IsValidId('LOC', 'LOC'));
        $this->assertTrue(CommonFunctions::IsValidId('EMP010', 'EMP'));
    }

    /**
     * Test method for formatMinutesAsHoursAndMinutes()
     */
    public function testFormatMinutesAsHoursAndMinutes() {

    	// Zero
    	$this->assertEquals("0h", CommonFunctions::formatMinutesAsHoursAndMinutes(0));
    	$this->assertEquals("0m", CommonFunctions::formatMinutesAsHoursAndMinutes(0, "0m"));

    	// Minutes only
    	$this->assertEquals("34m", CommonFunctions::formatMinutesAsHoursAndMinutes(34));
    	$this->assertEquals("34 minutes", CommonFunctions::formatMinutesAsHoursAndMinutes(34, "0", " minutes"));

		// Minutes and hours
    	$this->assertEquals("1h 10m", CommonFunctions::formatMinutesAsHoursAndMinutes(70));
    	$this->assertEquals("1hours 40minutes", CommonFunctions::formatMinutesAsHoursAndMinutes(100, "0", "minutes", "hours"));

		// Only hours
    	$this->assertEquals("2h", CommonFunctions::formatMinutesAsHoursAndMinutes(120));
    	$this->assertEquals("2hours", CommonFunctions::formatMinutesAsHoursAndMinutes(120, "0", "minutes", "hours"));

    	// Check negative times
    	$this->assertEquals("-34m", CommonFunctions::formatMinutesAsHoursAndMinutes(-34));
    	$this->assertEquals("-2h", CommonFunctions::formatMinutesAsHoursAndMinutes(-120));
		$this->assertEquals("-1h 10m", CommonFunctions::formatMinutesAsHoursAndMinutes(-70));

    	// Check non-integer minutes
    	$this->assertEquals("33m", CommonFunctions::formatMinutesAsHoursAndMinutes(33.19));
    	$this->assertEquals("1h 11m", CommonFunctions::formatMinutesAsHoursAndMinutes(70.78));

    }

    /**
     * Test method for formatSiUnitPrefix()
     */
    public function testFormatSiUnitPrefix() {
    	// No Unit prefix
		$this->assertEquals("100", CommonFunctions::formatSiUnitPrefix(100), CommonFunctions::formatSiUnitPrefix(100));
		$this->assertEquals(152, CommonFunctions::formatSiUnitPrefix(152));
		$this->assertEquals(94, CommonFunctions::formatSiUnitPrefix(94));

		// kilo
		$this->assertEquals("1 k", CommonFunctions::formatSiUnitPrefix(1000));
		$this->assertEquals("1.52 k", CommonFunctions::formatSiUnitPrefix(1520));
		$this->assertEquals("1.52 k", CommonFunctions::formatSiUnitPrefix(1523));

		// Mega
		$this->assertEquals("1 M", CommonFunctions::formatSiUnitPrefix(1000000));
		$this->assertEquals("1.53 M", CommonFunctions::formatSiUnitPrefix(1530000));
		$this->assertEquals("1.53 M", CommonFunctions::formatSiUnitPrefix(1525000));
		$this->assertEquals("1.54 M", CommonFunctions::formatSiUnitPrefix(1536750));

		// Giga
		$this->assertEquals("1 G", CommonFunctions::formatSiUnitPrefix(1000000000));
		$this->assertEquals("1.93 G", CommonFunctions::formatSiUnitPrefix(1930000000));
		$this->assertEquals("1.54 G", CommonFunctions::formatSiUnitPrefix(1542000000));
		$this->assertEquals("1.55 G", CommonFunctions::formatSiUnitPrefix(1546000000));

		//Tera
		$this->assertEquals("1 T", CommonFunctions::formatSiUnitPrefix(1000000000000));
		$this->assertEquals("1.45 T", CommonFunctions::formatSiUnitPrefix(1450000000000));
		$this->assertEquals("1.52 T", CommonFunctions::formatSiUnitPrefix(1523000000000));
		$this->assertEquals("1.53 T", CommonFunctions::formatSiUnitPrefix(1525000000000));
    }

    public function testCheckTimeOverlap() {
    	$this->assertFalse(CommonFunctions::checkTimeOverlap('08:00', '09:00', '09:01', '13:00'));

    	// start 2 = end 1 -> no overlap
    	$this->assertFalse(CommonFunctions::checkTimeOverlap('08:00', '09:00', '09:00', '13:00'));

		$this->assertTrue(CommonFunctions::checkTimeOverlap('13:00', '21:00', '09:00', '14:00'));
		$this->assertTrue(CommonFunctions::checkTimeOverlap('11:00', '15:00', '13:00', '18:00'));
		$this->assertTrue(CommonFunctions::checkTimeOverlap('11:00', '15:00', '10:00', '18:00'));
		$this->assertTrue(CommonFunctions::checkTimeOverlap('17:00', '21:00', '18:00', '18:30'));
    }

	public function testExtractNumericId() {
		$this -> assertEquals("001", CommonFunctions :: extractNumericId("SKI001"));
		$this -> assertEquals("002", CommonFunctions :: extractNumericId("SKI002"));
		$this -> assertEquals("010", CommonFunctions :: extractNumericId("SKI010"));
		$this -> assertEquals("100", CommonFunctions :: extractNumericId("SKI100"));
		$this -> assertEquals("0", CommonFunctions :: extractNumericId("SKI0"));
	}

    public function testGetFirstNChars() {
        $this->assertEquals('A des..', CommonFunctions::getFirstNChars("A description", 5, '..'));
        $this->assertEquals('A des', CommonFunctions::getFirstNChars("A description", 5, ''));
        $this->assertEquals('A des', CommonFunctions::getFirstNChars("A description", 5));
        $this->assertEquals('A description', CommonFunctions::getFirstNChars("A description", 25));
        $this->assertEquals('A description', CommonFunctions::getFirstNChars("A description", 25, '...'));
    }
        
    public function testGetObjectProperty() {       
        $testObj = new commonFunctionsTest_Class('John', 'Male', 24);
        
        // property that is available
        $this->assertEquals('Male', CommonFunctions::getObjectProperty($testObj, 'gender'));
        
        // property that is not available in object
        
        // property available, but no getter
        
        // property available, but getter is private    
    }
}

/**
 * Class used in unit test
 */
class commonFunctionsTest_Class {
    
    private $name;
    private $gender;
    private $age;
    
    public function __construct($name, $gender, $age) {
        $this->name = $name;
        $this->gender = $gender;
        $this->age = $age;
    }
    
    private function getName() {
        return $this->name;
    }
    
    public function getGender() {
        return $this->gender;    
    }
                
}

// Call CommonFunctionsTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "CommonFunctionsTest::main") {
    CommonFunctionsTest::main();
}
?>
