<?php

class orangehrmLeavePluginAllTests {

    protected function setUp() {

    }

    public static function suite() {

        $suite = new PHPUnit_Framework_TestSuite('orangehrmLeavePluginAllTest');

        /* Dao Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/LeaveEntitlementDaoTest.php'); 
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/NewLeaveTypeDaoTest.php');    
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/LeaveRequestDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/LeavePeriodDaoTest.php');

        /* Service Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/service/LeaveEntitlementServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/LeaveConfigurationServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/WorkScheduleServiceTest.php');        
        $suite->addTestFile(dirname(__FILE__) . '/model/service/LeavePeriodServiceGenerateEndDateTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/LeavePeriodServiceGenerateStartDateTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/LeavePeriodServiceTest.php');
        
        /* Other test cases */
        $suite->addTestFile(dirname(__FILE__) . '/entitlement/FIFOEntitlementConsumptionStrategyTest.php');
        
        
        
        return $suite;

    }

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

}

if (PHPUnit_MAIN_METHOD == 'orangehrmLeavePluginAllTests::main') {
    orangehrmCoreLeavePluginAllTests::main();
}
