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
    NewAdminFunctionalPluginAllTests::$configuration = ProjectConfiguration::getApplicationConfiguration(SF_APP_NAME, SF_ENV, true);
    sfContext::createInstance(NewAdminFunctionalPluginAllTests::$configuration);

    define('TEST_ENV_CONFIGURED', TRUE);
}

class NewAdminFunctionalPluginAllTests {

    public static $configuration = null;
    public static $databaseManager = null;
    public static $connection = null;

    protected function setUp() {

    }

    public static function suite() {

        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');
        //$suite->addTestFile(dirname(__FILE__) . '/UsersListTest.php'); 
        //$suite->addTestFile(dirname(__FILE__) . '/MembershipsTest.php');
        //$suite->addTestFile(dirname(__FILE__) . '/NationalitiesTest.php'); 
      //  $suite->addTestFile(dirname(__FILE__) . '/EmploymentStatusTest.php');
         //$suite->addTestFile(dirname(__FILE__) . '/JobCategoriesTest.php');
        //$suite->addTestFile(dirname(__FILE__) . '/EducationTest.php');
        //$suite->addTestFile(dirname(__FILE__) . '/LicenseTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/LanguageTest.php');
        return $suite;
    }

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
      
    }

}
