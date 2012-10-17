<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author nusky
 */
class TestConfig {

    private $loginURL;
    private $mysqlAdminUser;
    private $mysqlAdminPassword;
    private $dbname;
    private $currentBrowser;
    private $browserPath;
    private $browserURL;
    private $timeoutValue;
    private $absolutePath;
    private $operatingSystem;
    private $configFilePath;

    public function __construct() {

        $this->setDBParameters();
        $this->configFilePath = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/config/FunctionalTestConfig.yml";
        $settings = sfYaml::load($this->configFilePath);
        
        $this->setLoginURL($settings["Login"]["url"]);
        $this->setOperatingSystem($settings["SystemConfig"]["os"]);
        $this->setTimeoutValue($settings["TestSettings"]["timeOut"]);
        $this->setAbsolutePath($settings["TestSettings"]["absolutePath"]);
        $this->setBrowserURL($settings["TestSettings"]["browserURL"]);
        
        $this->setBrowserPath($settings["TestSettings"]["browserPath"]);
        $this->setCurrentBrowser($settings["TestSettings"]["currentBrowser"]);
    }

    private function setDBParameters() {
        $databaseSettingsFile = sfConfig::get('sf_config_dir') . "/databases.yml";
        $dbSettings = sfYaml::load($databaseSettingsFile);
        $dsn = $dbSettings["all"]["doctrine"]["param"]["dsn"];

        $DB = substr($dsn, 38);
        
        $this->setDBname($DB);
        $this->setMySqlAdminUser($dbSettings["all"]["doctrine"]["param"]["username"]);
        $this->setMySqlAdminPassword($dbSettings["all"]["doctrine"]["param"]["password"]);
    }

    public function setLoginURL($value) {

        $this->loginURL = $value;
    }

    public function getLoginURL() {
        if($this->browserURL){
        return $this->browserURL . $this->loginURL;
        }
    }

    public function setMySqlAdminUser($value) {

        $this->mysqlAdminUser = $value;
    }

    public function getMySqlAdminUser() {

        return $this->mysqlAdminUser;
    }

    public function setMySqlAdminPassword($value) {

        $this->mysqlAdminPassword = $value;
    }

    public function getMySqlAdminPassword() {

        return $this->mysqlAdminPassword;
    }

    public function setDBname($value) {

        $this->dbname = $value;
    }

    public function getDBname() {

        return $this->dbname;
    }

    public function setCurrentBrowser($value) {

        $this->currentBrowser = $value;
    }

    public function getCurrentBrowser() {

        return $this->currentBrowser;
    }

    public function setBrowserPath($value) {

        $this->browserPath = $value;
    }

    public function getBrowserPath() {

        return $this->browserPath;
    }

    public function setBrowserURL($value) {

        $this->browserURL = $value;
    }

    public function getBrowserURL() {

        return $this->browserURL;
    }

    public function setTimeoutValue($value) {

        $this->timeoutValue = $value;
    }

    public function getTimeoutValue() {

        return $this->timeoutValue;
    }

    public function setAbsolutePath($value) {

        $this->absolutePath = $value;
    }

    public function getAbsolutePath() {

        return $this->absolutePath;
    }

    public function setOperatingSystem($value) {

        $this->operatingSystem = $value;
    }

    public function getOperatingSystem() {

        return $this->operatingSystem;
    }

}