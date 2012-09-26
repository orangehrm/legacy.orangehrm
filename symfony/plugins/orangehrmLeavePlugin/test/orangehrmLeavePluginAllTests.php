<?php

class orangehrmLeavePluginAllTests {

    protected function setUp() {

    }

    public static function suite() {

        $suite = new PHPUnit_Framework_TestSuite('orangehrmLeavePluginAllTest');

        /* Dao Test Cases */
        //$suite->addTestFile(dirname(__FILE__) . '/model/dao/WorkWeekDaoTest.php');      

        /* Service Test Cases */
        //$suite->addTestFile(dirname(__FILE__) . '/model/service/WorkWeekServiceTest.php');
        return $suite;

    }

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

}

if (PHPUnit_MAIN_METHOD == 'orangehrmLeavePluginAllTests::main') {
    orangehrmCoreLeavePluginAllTests::main();
}
