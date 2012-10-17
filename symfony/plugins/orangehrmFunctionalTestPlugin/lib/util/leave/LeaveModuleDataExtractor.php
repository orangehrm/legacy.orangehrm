<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyClass
 *
 * @author madusani
 */
class LeaveModuleDataExtractor {

    public $myArray;
    public $dataArray;

    public function __construct() {

        $fixture = sfConfig::get('sf_plugins_dir') . "/../data/fixtures/data.yml";
        $this->dataArray = sfYaml::load($fixture);
        $this->myArray["LeavePeriod"] = $this->dataArray["LeavePeriod"];
        $this->myArray["LeaveType"] = $this->dataArray["LeaveType"];
        $this->myArray["Holiday"] = $this->dataArray["Holiday"];
        $this->myArray["WorkWeek"] = $this->dataArray["WorkWeek"];
        $this->myArray["LeaveRequest"] = $this->dataArray["LeaveRequest"];
        $this->myArray["Leave"] = $this->dataArray["Leave"];
        $this->myArray["EmployeeLeaveEntitlement"] = $this->dataArray["EmployeeLeaveEntitlement"];
        $this->myArray["WorkShift"] = $this->dataArray["WorkShift"];
        $this->myArray["EmployeeWorkShift"] = $this->dataArray["EmployeeWorkShift"];
        $myFixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/leave/testdata/LeavePrerequisites.yml";
        $handle = fopen($myFixture, "w");
        fwrite($handle, sfYaml::dump($this->myArray));
        fclose($handle);
    }

}