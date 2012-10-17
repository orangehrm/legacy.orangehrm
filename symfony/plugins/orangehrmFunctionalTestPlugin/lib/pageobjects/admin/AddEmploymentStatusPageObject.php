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
class AddEmploymentStatusPageObject extends Component {

    public $txtEmploymentStatusName = "empStatus_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $viewEmploymentStatus;

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Employment Status");
        $this->viewEmploymentStatus = new ViewEmploymentStatusPageObject($this->selenium);
    }

    public function addEmploymentStatus($employmentStatus) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtEmploymentStatusName, $employmentStatus);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editEmploymentStatus($employmentStatus) {
        $this->addEmploymentStatus($employmentStatus);
    }

}