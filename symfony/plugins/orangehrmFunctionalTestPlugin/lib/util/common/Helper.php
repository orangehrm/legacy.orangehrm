<?php

class Helper {

    public static $selenium;
    public static $config;

    function __construct() {
        //$this->selenium = $selenium;
        self::$config = new TestConfig();
    }

    //table[@id='emp_list']/tbody//td[$columnNumber]/a[text()=$empFirstName]
    //table[@id='emp_list']/tbody//td[$columnNumber]/a[text()=$empLastName]

    public static function getFullName($empFirstName, $empLastName, $empMiddleName=NULL) {
        $fullName = $empFirstName;

        if ($empMiddleName)
            $fullName = $fullName . " " . $empMiddleName;
        $fullName = $fullName . " " . $empLastName;

        return $fullName;
    }

    public static function getTitle() {
        //      return $this->selenium->getText("//div[@class='mainHeading']/h2");

        try {
            return self::$selenium->getText("//div[@class='mainHeading']/h2");
        } catch (Exception $e) {
            return "Could not retrieve the heading";
            //$e->getMessage();
        }
    }

    //Issue:Where is the value $text coming from?
    public static function verifyHeading() {
        return self::$selenium->isTextPresent($text);
    }

    public static function isLoggedIn($selenium) {
        $selenium->selectFrame();
        $result = $selenium->isElementPresent("//ul[@id='option-menu']/li[1]");
        $selenium->selectFrame("rightMenu");
        return $result;
    }

    public static function logOutIfLoggedIn($selenium) {
        if (self::isLoggedIn($selenium)) {
            $selenium->selectFrame();
            $selenium->clickAndWait("link=Logout");            
        }
    }

    public static function getLoggedInUser($selenium) {
        $selenium->selectFrame();
        $welcomeText = $selenium->getText("//ul[@id='option-menu']/li[contains(text(),'Welcome')]");
        $user = substr($welcomeText, strlen("Welcome "));
        $selenium->selectFrame("rightMenu");
        return trim($user);
    }

    public static function loginUser($selenium, $user, $pass) {
        
        if (self::isLoggedIn($selenium)) {
            if (strtoupper($user) == strtoupper(self::getLoggedInUser($selenium))) {
                return;
            }
        }

        $selenium->open(self::$config->getLoginURL() . "/symfony/web/index.php/auth/logout");
        $login = new Login($selenium);
        if ($login->homePageLogin($user, $pass));
        return new EmployeeListPage($selenium);
    }

    public static function restoreDBDump($dumpName) {

//        $commandString = "mysql" . " -u" . self::$config->getMySqlAdminUser();
//        if (self::$config->getMySqlAdminPassword())
//            $commandString = $commandString . " -p" . self::$config->getMySqlAdminPassword();
//        $commandString = $commandString . " " . self::$config->getDBname() . " < " . $dumpName;
//        system($commandString);
    }

    public static function deleteAllFromTable($tableName) {

        if (self::$config->getMySqlAdminPassword() == null) {

            $commandString = "mysql -u" . self::$config->getMySqlAdminUser() . " -e \"use " . self::$config->getDBname() . "; DELETE FROM " . $tableName . ";\"";
            //echo $commandString;

            system($commandString);
        }
        if (self::$config->getMySqlAdminPassword() != null) {
            $commandString = "mysql -u" . self::$config->getMySqlAdminUser() . " -p" . self::$config->getMySqlAdminPassword() .
                    " -e \"use " . self::$config->getDBname() . "; DELETE FROM " . $tableName . ";\"";

            //echo $commandString;

            system($commandString);
        }
    }

    public static function getBrowserString() {
        $browserString = self::$config->getCurrentBrowser();
        if (self::$config->getBrowserPath())
            $browserString = $browserString . " " . self::$config->getBrowserPath();
        return $browserString;
    }

    public static function convertPathToCurrentPlatform($path) {
        $config = new TestConfig();
        if (strtoupper($config->getOperatingSystem()) == "WINDOWS") {

            return str_replace("/", "\\", $path);
        }else {
            return $path;
        }
    }
    
     public static function loadFixtureToInputArray($fixturePath, $section, $pageobject, $arrayType) {
        $mapper = self::getFixtureToInputArrayMapping($pageobject, $arrayType);
        echo "printing mapper "; print_r($mapper);

        $loadedFixture = sfYaml::load($fixturePath);
        print_r($loadedFixture);

        // echo "\n\n\n\n" . sfYaml::dump($loadedFixture["ValidVacancies"]["all_fields"]["jobTitle"]) . "\n\n\n\n";
        if (count($mapper['fixture']) != count($mapper['inputData'])) {
            echo "number of fixture fields and number of inputData fields are different \n";
            echo "fixture count: " . count($mapper['fixture']) . "\t inputData count: " . count($mapper['inputData']);
            exit();
        }

        $recordNumber = 0;
        //reset($loadedFixture[$section]);
        for ($i = 0; $i < count($loadedFixture[$section]); $i++) {
            //foreach ($loadedFixture[$section] as $record) {
            $record = current($loadedFixture[$section]);
            //$recordNumber = key($loadedFixture[$section]) . "\n";


            for ($columnNumber = 0; $columnNumber < count($mapper['inputData']); $columnNumber++) {
                //$recordName =key($loadedFixture[$section]);
                $inputData[$recordNumber][$mapper['inputData'][$columnNumber]] = $record[$mapper['fixture'][$columnNumber]];
            }

            $recordNumber++;
            next($loadedFixture[$section]);
        }

        return $inputData;
    }
    
    
        public static function getFixtureToInputArrayMapping($pageobject, $arrayType) {

        $fixtureFields = null;
        $inputData = null;

       

        if (($pageobject instanceof EmployeeListPage) && ($arrayType == "Input")) {
            $fixtureFields = array('Employee Name' ,'Id', 'Employment Status', 'Include', 'Supervisor Name', 'Job Title', 'Sub Unit' );
            $inputData = array($pageobject->txtEmployeeName, $pageobject->txtEmployeeId, $pageobject->cmbEmployeeStatus, $pageobject->cmbEmployeeTermination, $pageobject->txtSupervisorName, $pageobject->cmbJobTitle,$pageobject->cmbSubUnit );
            
            
        }
        
        if (($pageobject instanceof EmployeeListPage) && ($arrayType == "Output")) {
            $fixtureFields = array('Id', 'First (& Middle) Name', 'Last Name', 'Job Title', 'Employment Status', 'Sub Unit', 'Supervisor');
            $inputData = array('Id', 'First (& Middle) Name', 'Last Name', 'Job Title', 'Employment Status', 'Sub Unit', 'Supervisor');
        }

        $mapper['fixture'] = $fixtureFields;
        $mapper['inputData'] = $inputData;
        //print_r($mapper);
        return $mapper;
    }

}