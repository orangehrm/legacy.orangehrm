<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TimeExternalPrerequisiteLoader
 *
 * @author Faris
 */
class TimeExternalPrerequisiteLoader {

    public $myArray;
    public $dataArray;

    public function __construct() {

        $fixture = sfConfig::get('sf_plugins_dir') . "/../data/fixtures/data.yml";
        $this->dataArray = sfYaml::load($fixture);
        $this->myArray["Customer"] = $this->dataArray["Customer"];
        $this->myArray["Project"] = $this->dataArray["Project"];
        $this->myArray["ProjectActivity"] = $this->dataArray["ProjectActivity"];
        $this->myArray["Employee"] = $this->dataArray["Employee"];
        $this->myArray["JobTitle"] = $this->dataArray["JobTitle"];
        $this->myArray["Users"] = $this->dataArray["Users"];
        $this->myArray["ReportTo"] = $this->dataArray["ReportTo"];
        $myFixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/time/testdata/TimeSheetExternalPrerequisites.yml";
        $handle = fopen($myFixture, "w");
        fwrite($handle, sfYaml::dump($this->myArray));
        fclose($handle);
    }

}