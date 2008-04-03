<?php
// Call SearchSqlHelperTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "SearchSqlHelperTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'SearchSqlHelper.php';
require_once 'SearchField.php';

/**
 * Test class for SearchSqlHelper.
 * Generated by PHPUnit_Util_Skeleton on 2008-04-01 at 17:51:27.
 */
class SearchSqlHelperTest extends PHPUnit_Framework_TestCase {
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("SearchSqlHelperTest");
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
     * Unit test for getSqlOperator().
     */
    public function testGetSqlOperator() {        
        $this->assertEquals('<', SearchSqlHelper::getSqlOperator(SearchOperator::getOperator(SearchOperator::OPERATOR_LESSTHAN)));
        $this->assertEquals('>', SearchSqlHelper::getSqlOperator(SearchOperator::getOperator(SearchOperator::OPERATOR_GREATERTHAN)));
        $this->assertEquals('=', SearchSqlHelper::getSqlOperator(SearchOperator::getOperator(SearchOperator::OPERATOR_EQUAL)));
        $this->assertEquals('<>', SearchSqlHelper::getSqlOperator(SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EQUAL)));
        $this->assertEquals('LIKE', SearchSqlHelper::getSqlOperator(SearchOperator::getOperator(SearchOperator::OPERATOR_STARTSWITH)));
        $this->assertEquals('LIKE', SearchSqlHelper::getSqlOperator(SearchOperator::getOperator(SearchOperator::OPERATOR_ENDSWITH)));                
        $this->assertEquals('LIKE', SearchSqlHelper::getSqlOperator(SearchOperator::getOperator(SearchOperator::OPERATOR_CONTAINS)));
        $this->assertEquals('NOT LIKE', SearchSqlHelper::getSqlOperator(SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_CONTAINS)));
        $this->assertEquals('IS NULL', SearchSqlHelper::getSqlOperator(SearchOperator::getOperator(SearchOperator::OPERATOR_EMPTY)));
        $this->assertEquals('IS NOT NULL', SearchSqlHelper::getSqlOperator(SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EMPTY)));                        
        // invalid operator
        try {
            SearchSqlHelper::getSqlOperator('XY');
        } catch (SearchSqlHelperException $e) {
            $this->assertEquals(SearchSqlHelperException::INVALID_OPERATOR, $e->getCode());
        }                        
    }

    /**
     * Unit test for getSqlCondition().
     */
    public function testGetSqlCondition() {
        $dbField = 'db_test_field';
        
        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_LESSTHAN), '5', 
            SearchField::FIELD_TYPE_INT);
        $this->assertEquals($sql, '(db_test_field < 5)');

        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_LESSTHAN), '5', 
            SearchField::FIELD_TYPE_INT, false);
        $this->assertEquals($sql, 'db_test_field < 5');

        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_EQUAL), 'how are you', 
            SearchField::FIELD_TYPE_STRING, true);
        $this->assertEquals($sql, "(db_test_field = 'how are you')");

        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EQUAL), 'how are you', 
            SearchField::FIELD_TYPE_STRING, true);
        $this->assertEquals($sql, "((db_test_field IS NULL) OR (db_test_field <> 'how are you'))");
        
        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_STARTSWITH), 'Jo', 
            SearchField::FIELD_TYPE_STRING, true);
        $this->assertEquals($sql, "(db_test_field LIKE 'Jo%')");

        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_ENDSWITH), 'hn', 
            SearchField::FIELD_TYPE_STRING, true);
        $this->assertEquals($sql, "(db_test_field LIKE '%hn')");

        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_CONTAINS), 'oh', 
            SearchField::FIELD_TYPE_STRING, true);
        $this->assertEquals($sql, "(db_test_field LIKE '%oh%')");

        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_CONTAINS), 'oh', 
            SearchField::FIELD_TYPE_STRING, true);
        $this->assertEquals($sql, "((db_test_field IS NULL) OR (db_test_field NOT LIKE '%oh%'))");
        
        /* LIKE on a numeric field (This is allowed in MySQL) */
        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_STARTSWITH), '10', 
            SearchField::FIELD_TYPE_INT, true);
        $this->assertEquals($sql, "(db_test_field LIKE '10%')");
        
        // Check EMPTY for int
        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_EMPTY), null, 
            SearchField::FIELD_TYPE_SELECT, true);
        $this->assertEquals($sql, "(db_test_field IS NULL)");
        
        // Check NOT EMPTY for int
        $sql = SearchSqlHelper::getSqlCondition($dbField, SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EMPTY), null, 
            SearchField::FIELD_TYPE_SELECT, true);
        $this->assertEquals($sql, "(db_test_field IS NOT NULL)");                
    }
}

// Call SearchSqlHelperTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "SearchSqlHelperTest::main") {
    SearchSqlHelperTest::main();
}
?>
