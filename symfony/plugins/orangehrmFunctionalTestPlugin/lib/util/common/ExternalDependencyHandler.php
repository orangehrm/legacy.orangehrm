<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExternalDependencyHandler
 *
 * @author madusani
 */
class ExternalDependencyHandler {

    private $configuration;
    private $moduleName;
    private $configPath;
    public $leaveFixture;
    public $recruitmentFixture;
    public $timeFixture;
    public $adminFixture;

    public function __construct() {
        $this->configPath = sfConfig::get('sf_plugins_dir') . '/orangehrmFunctionalTestPlugin/config/FunctionalTestConfig.yml';
        $this->configuration = sfYaml::load($this->configPath);

        //Fixture initialization
        $this->leaveFixture = sfConfig::get('sf_plugins_dir') . '/orangehrmFunctionalTestPlugin/test/leave/testdata/LeaveModulePrerequisites.yml';
        $this->recruitmentFixture = sfConfig::get('sf_plugins_dir') . '/orangehrmFunctionalTestPlugin/test/recruitment/testdata/AddVacancyPrerequisites.yml';
        $this->timeFixture = sfConfig::get('sf_plugins_dir') . '/orangehrmFunctionalTestPlugin/test/time/testdata/TimeSheetExternalPrerequisites.yml';
        $this->adminFixture = sfConfig::get('sf_plugins_dir') . '/orangehrmFunctionalTestPlugin/test/admin/testdata/AdminExternalPrerequisites.yml';
    }

    public function ensureDependencies($moduleName) {

        $this->moduleName = $moduleName;
        switch ($moduleName) {
            case "recruitment":
                $fixture = $this->recruitmentFixture;
                break;
            case "leave":
                $fixture = $this->leaveFixture;
                break;
            case "time":
                $fixture = $this->timeFixture;
                break;
            case "admin":
                $fixture = $this->adminFixture;
                break;
            default:
                echo "External Dependency Handler: unknown module";
        }

        $this->ensureModuleDependencies($fixture);
    }

    private function isDependenciesRun() {
        return $this->configuration["ExternalDependenciesCreated"][$this->moduleName];
    }

    private function writeToFile($filePath, $ymlArray) {

        $handle = fopen($filePath, "w");
        fwrite($handle, sfYaml::dump($ymlArray));
        fclose($handle);
    }

    private function ensureModuleDependencies($prerequisiteFilePath, $runOnce=true) {

        if ($runOnce && $this->isDependenciesRun($prerequisiteFilePath)) {
            return;
        }

        $this->runExternalDependencies($prerequisiteFilePath);
    }

    private function runExternalDependencies($prerequisiteFilePath) {
        $fixture = $prerequisiteFilePath;

        TestDataService::populate($fixture);




        //set the dependency condition to true and write it to the YML file
        $this->configuration["ExternalDependenciesCreated"][$this->moduleName] = true;
        $this->writeToFile($this->configPath, $this->configuration);
    }

    private function ensureTimeDependencies() {
        //load the dependency condition to the array

        if ($externalDependencyConditions["ExternalDependenciesCreated"]["time"] == false) {

            //fixture below should be timeoPrerequisites
            $fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmFunctionalTestPlugin/test/time/testdata/TimeSheetExternalPrerequisites.yml';
            TestDataService::populate($fixture);

            $externalDependencyConditions["ExternalDependenciesCreated"]["time"] = true;
            $handle = fopen($externalDependencies, "w");
            fwrite($handle, sfYaml::dump($externalDependencyConditions));
            fclose($handle);
        }
    }

}