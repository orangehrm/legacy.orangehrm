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
class AddQualificationLanguagePageObject extends Component {

    public $txtLanguage = "language_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Language Qualification");
    }

    public function addQualificationLanguage($language) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtLanguage, $language);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editQualificationLanguage($language) {
        $this->addQualificationLanguage($language);
    }

}