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
class AddNationalityPageObject extends Component {

    public $txtNationality = "nationality_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $viewNationalities;

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Nationality");
        $this->viewNationalities = new ViewNationalitiesPageObject($this->selenium);
    }

    public function addNationality($nationality) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtNationality, $nationality);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editNationality($nationality) {
        $this->addNationality($nationality);
    }

}