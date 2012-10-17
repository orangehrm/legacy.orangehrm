<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProjectReport
 *
 * @author Faris
 */
class ProjectReport extends Page {

    public $projectName;
    public $projectFromDate;
    public $projectToDate;
    public $chkOnlyApprovedSheets;
    public $btnView;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->projectName = "time_project_name";
        $this->projectFromDate = "project_date_range_from_date";
        $this->projectToDate = "project_date_range_to_date";
        $this->chkOnlyApprovedSheets = "only_include_approved_timesheets";
        $this->btnView = "viewbutton";
    }

    public function viewProjectReport($projectName, $dateFrom=null, $dateTo=null, $check=false) {

        $this->selenium->selectFrame("relative=top");

        $this->selenium->type($this->projectName, $projectName);

        if ($dateFrom != null) {
            $this->selenium->type($this->projectFromDate, $dateFrom);
        }

        if ($dateRangeTo) {
            $this->selenium->type($this->projectToDate, $dateTo);
        }

        if ($check) {
            $this->selenium->click($this->chkOnlyApprovedSheets);
        }
        $this->selenium->click($this->btnView);
    }

}