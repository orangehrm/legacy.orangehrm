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
class AddQualificationLicensePageObject extends Component {

    public $txtLicense = "license_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add License Qulaification");
    }

    public function addQualificationLicense($license) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtLicense, $license);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editQualificationLicense($license) {
        $this->addQualificationLicense($license);
    }

}