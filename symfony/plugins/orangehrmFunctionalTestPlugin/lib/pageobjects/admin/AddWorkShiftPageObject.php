<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddJobTitlePageObject
 *
 * @author intel
 */
class AddWorkShiftPageObject extends Component {

    public $txtWorkShift = "workShift_name";
    public $txtHours = "workShift_hours";
    public $viewWorkShift;
    public $multiCmbAvailableEmployees = "workShift_availableEmp";
    public $multiCmbAssignedEmployees = "workShift_assignedEmp";
    public $btnAddEmployees = "btnAssignEmployee";
    public $btnRemoveEmployees = "btnRemoveEmployee";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Work Shift");
        $this->viewWorkShift = new ViewWorkShiftPageObject($this->selenium);
    }

    public function addWorkShift($workShiftName, $hours, $assignEmployees =null, $removeEmployees=null) {
        $this->selenium->selectFrame("relatve=top");
        $this->selenium->type($this->txtWorkShift, $workShiftName);
        $this->selenium->type($this->txtHours, $hours);
        if ($assignEmployees) {
            $this->selenium->shiftKeyDown();
            for ($i = 0; $i < sizeof($assignEmployees); $i++) {
                $this->selenium->select($this->multiCmbAvailableEmployees, $assignEmployees[$i]);
            }
            $this->selenium->shiftKeyUp();
            $this->selenium->click($this->btnAddEmployees);
        }
        if ($removeEmployees) {
            $this->selenium->shiftKeyDown();
            for ($i = 0; $i < sizeof($removeEmployees); $i++) {
                $this->selenium->select($this->multiCmbAssignedEmployees, $removeEmployees[$i]);
            }
            $this->selenium->shiftKeyUp();
            $this->selenium->click($this->btnRemoveEmployees);
        }
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editWorkShift($workShiftName, $hours, $assignEmployees =null, $removeEmployees=null) {
        $this->addWorkShift($workShiftName, $hours, $assignEmployees, $removeEmployees);
    }

}