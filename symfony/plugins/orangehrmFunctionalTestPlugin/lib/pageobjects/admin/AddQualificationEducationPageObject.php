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
class AddQualificationEducationPageObject extends Component {

    public $txtEducationLevel = "education_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Educational Qualification");
    }

    public function addQualificationEducation($educationLevel) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtEducationLevel, $educationLevel);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editQualificationEducation($educationLevel) {
        $this->addQualificationEducation($educationLevel);
    }

}