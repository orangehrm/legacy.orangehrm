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
class AddJobCategoryPageObject extends Component {

    public $txtJobCategory = "jobCategory_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $viewJobCategory;

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Job Category");
        $this->viewJobCategory = new ViewJobCategoryPageObject($this->selenium);
    }

    public function addJobCategory($jobCategory) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtJobCategory, $jobCategory);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editJobCategory($jobCategory) {
        $this->addJobCategory($jobCategory);
    }

}