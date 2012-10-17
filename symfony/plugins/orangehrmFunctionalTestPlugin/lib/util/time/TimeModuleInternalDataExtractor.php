<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyClass
 *
 * @author Faris
 */
class TimeModuleInternalDataExtractor {

    public $myArray;
    public $dataArray;

    public function __construct() {

        $fixture = sfConfig::get('sf_plugins_dir') . "/../data/fixtures/data.yml";
        $this->dataArray = sfYaml::load($fixture);
        $this->myArray["TimesheetItem"] = $this->dataArray["TimesheetItem"];
        $this->myArray["Timesheet"] = $this->dataArray["Timesheet"];
        $this->myArray["TimesheetActionLog"] = $this->dataArray["TimesheetActionLog"];
        //$this->myArray["WorkWeek"] = $this->dataArray["WorkWeek"];
        //$this->myArray["LeaveRequest"] = $this->dataArray["LeaveRequest"];
        //$this->myArray["Leave"] = $this->dataArray["Leave"];
        //$this->myArray["EmployeeLeaveEntitlement"] = $this->dataArray["EmployeeLeaveEntitlement"];
        $myFixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/time/testdata/TimePrerequisites.yml";
        $handle = fopen($myFixture, "w");
        fwrite($handle, sfYaml::dump($this->myArray));
        fclose($handle);
    }

}