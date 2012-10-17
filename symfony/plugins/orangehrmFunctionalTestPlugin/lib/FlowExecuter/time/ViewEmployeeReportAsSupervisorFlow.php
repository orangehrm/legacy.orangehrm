<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewEmployeeReportAsSupervisorFlow
 *
 * @author Arsh
 */
class ViewEmployeeReportAsSupervisorFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $projectReportPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->projectReportPageObject = menu::goToEmployeeReportsAsSupervisor($this->selenium);
        $this->projectReportPageObject->viewEmployeeReport($this->dataArray['employee'], $this->dataArray['projectName'], $this->dataArray['activityName'], $this->dataArray['dateRangeFrom'], $this->dataArray['dateRangeTo'], $this->dataArray['chkOnlyApprovedSheets']);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        return true;
    }

}