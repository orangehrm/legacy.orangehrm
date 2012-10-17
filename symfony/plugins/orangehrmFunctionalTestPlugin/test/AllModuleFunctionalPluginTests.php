<?php

require_once 'PHPUnit/Framework.php';

//require_once 'leave/LeaveFunctionalPluginAllTests.php';
require_once 'pim/PIMFunctionalPluginAllTests.php';
//require_once 'recruitment/RecruitmentFunctionalPluginAllTests.php';
require_once 'time/TimeFunctionalPluginAllTests.php';
//require_once 'admin/AdminFunctionalPluginAllTests.php';

class AllModuleFunctionalPluginTests {

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Project');

        $result = new PHPUnit_Framework_TestResult;
        //$result->addListener(new SimpleTestListener);
        //$suite->addTest(AdminFunctionalPluginAllTests::suite());
        //$suite->addTest(PIMFunctionalPluginAllTests::suite());
        //$suite->addTest(LeaveFunctionalPluginAllTests::suite());
        //$suite->addTest(RecruitmentFunctionalPluginAllTests::suite());
        //$suite->addTest(TimeFunctionalPluginAllTests::suite());
        $suite->addTest(NewPimFunctionalPluginAllTests::suite());
        $suite->run($result);
        
        return $suite;
    }

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

}

