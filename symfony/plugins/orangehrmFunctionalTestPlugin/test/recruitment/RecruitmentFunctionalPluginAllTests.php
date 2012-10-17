<?php

require_once 'PHPUnit/Framework.php';
//require_once 'util/TestDataService.php';

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__FILE__) . '/../../../../');
}
if (!defined('SF_APP_NAME')) {
    define('SF_APP_NAME', 'orangehrm');
}
if (!defined('SF_ENV')) {
    define('SF_ENV', 'test');
}
if (!defined('SF_CONN')) {
    define('SF_CONN', 'doctrine');
}

if (!defined('TEST_ENV_CONFIGURED')) {

    require_once(dirname(__FILE__) . '/../../../../config/ProjectConfiguration.class.php');
    RecruitmentFunctionalPluginAllTests::$configuration = ProjectConfiguration::getApplicationConfiguration(SF_APP_NAME, SF_ENV, true);
    sfContext::createInstance(RecruitmentFunctionalPluginAllTests::$configuration);

    define('TEST_ENV_CONFIGURED', TRUE);
}

class RecruitmentFunctionalPluginAllTests {

    public static $configuration = null;
    public static $databaseManager = null;
    public static $connection = null;

    protected function setUp() {
        
    }

    public static function suite() {

        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');




        $suite->addTestFile(dirname(__FILE__) . '/testcases/AddVacancyTest.php');
        //$suite->addTestFile(dirname(__FILE__) . '/testcases/ViewCandidatesTest.php');       //ok
        
        //$suite->addTestFile(dirname(__FILE__) . '/testcases/recruitmentFlowTest.php');      //ok



        return $suite;
    }

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

}
