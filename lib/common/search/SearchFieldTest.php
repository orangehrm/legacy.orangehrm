<?php
// Call SearchFieldTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "SearchFieldTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'SearchField.php';

/**
 * Test class for SearchField.
 * Generated by PHPUnit_Util_Skeleton on 2008-04-01 at 17:34:36.
 */
class SearchFieldTest extends PHPUnit_Framework_TestCase {
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("SearchFieldTest");
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
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    }

    /** 
     * Test the SearchField constructor
     */
    public function testConstructor() {
        
        /* With only required options */
        $searchField = new SearchField('testField', 'lang_test_field_name', 'string');
        $this->assertEquals('testField', $searchField->getFieldName());
        $this->assertEquals('lang_test_field_name', $searchField->getDisplayNameVar());
        $this->assertEquals('string', $searchField->getFieldType());
        $this->assertNull($searchField->getSelectOptions());
        
        // verify default operators for string were set.
        $operators = $searchField->getOperators();
        $expected = array(SearchOperator::getOperator(SearchOperator::OPERATOR_EQUAL),
                          SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EQUAL),
                          SearchOperator::getOperator(SearchOperator::OPERATOR_STARTSWITH),
                          SearchOperator::getOperator(SearchOperator::OPERATOR_ENDSWITH),
                          SearchOperator::getOperator(SearchOperator::OPERATOR_CONTAINS),
                          SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_CONTAINS));
                            
        $this->assertEquals($expected, $operators);
        
        // pass operators to constructor
        $searchField = new SearchField('testField2', 'lang_test_field_name2', 'int', array(SearchOperator::getOperator(SearchOperator::OPERATOR_EQUAL)));
        $this->assertEquals('testField2', $searchField->getFieldName());
        $this->assertEquals('lang_test_field_name2', $searchField->getDisplayNameVar());
        $this->assertEquals('int', $searchField->getFieldType());
        $this->assertNull($searchField->getSelectOptions());
        
        $operators = $searchField->getOperators();
        $expected = array(SearchOperator::getOperator(SearchOperator::OPERATOR_EQUAL));
                            
        $this->assertEquals($expected, $operators);
        
        // Select type search field
        $selectOptions = array('one', 'two', 'three');
        $searchField = new SearchField('testField3', 'lang_test_field_name3', 'select', null, $selectOptions);
        $this->assertEquals('testField3', $searchField->getFieldName());
        $this->assertEquals('lang_test_field_name3', $searchField->getDisplayNameVar());
        $this->assertEquals('select', $searchField->getFieldType());

        $this->assertEquals($selectOptions, $searchField->getSelectOptions());
        
        // verify default operators for select were set.
        $operators = $searchField->getOperators();
        $expected = array(SearchOperator::getOperator(SearchOperator::OPERATOR_EQUAL),
                          SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EQUAL),
                          SearchOperator::getOperator(SearchOperator::OPERATOR_EMPTY),
                          SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EMPTY));                            
        $this->assertEquals($expected, $operators);
                                           
    }
}

// Call SearchFieldTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "SearchFieldTest::main") {
    SearchFieldTest::main();
}
?>
