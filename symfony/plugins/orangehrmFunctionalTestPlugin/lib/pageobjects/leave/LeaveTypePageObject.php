<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeaveTypePageObject
 *
 * @author madusani
 */
class LeaveTypePageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $btnSave = "//input[@id='saveButton']";
    public $btnReset = "resetButton";
    public $btnBack = "backButton";
    public $txtleaveTypeName = "leaveType_txtLeaveTypeName";
    public $config;
    public $list;
    public $promptYesBtn = "undeleteYes";
    public $promptNoBtn = "undeleteNo";
    public $promptCancelBtn = "undeleteCancel";
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";

    public function __construct($selenium) {
        parent::__construct($selenium, "Leave type");
        $this->config = new TestConfig();
        $this->list = new LeaveTypeList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function addLeaveType($leaveTypeName, $promptBtn=null) {
        $this->selenium->selectFrame("relative=top");

        $this->selenium->clickAndWait($this->btnAdd);

        $this->selenium->type($this->txtleaveTypeName, $leaveTypeName);
        $this->selenium->click($this->btnSave);

        if ($this->selenium->isElementPresent($this->promptYesBtn) && $promptBtn) {
            $this->clickThePromptBtn($promptBtn);
        } else {

            $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        }
    }

    public function editLeaveType($leaveTypeName, $promptBtn=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtleaveTypeName, $leaveTypeName);
        $this->selenium->click($this->btnSave);
        if ($this->selenium->isElementPresent($this->promptYesBtn) && $promptBtn) {
            $this->clickThePromptBtn($promptBtn);
        } else {
            $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        }
    }

    public function clickThePromptBtn($promptBtn) {
        if ($promptBtn == "Yes")
            $this->selenium->clickAndWait($this->promptYesBtn);
        if ($promptBtn == "No")
            $this->selenium->clickAndWait($this->promptNoBtn);
        if ($promptBtn == "Cancel")
            $this->selenium->clickAndWait($this->promptCancelBtn);
    }

    public function getSuccessfullMessage() {
        
        return $this->selenium->getText("//div[@id='messagebar']");
    }

    public function deleteLeaveType($leaveTypeName) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Leave Type", $leaveTypeName);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

}