<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewJobTitlesPageObject
 *
 * @author intel
 */
class ViewQualificationLicensePageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDel";
    public $list;

    public function __construct($selenium) {
        parent::__construct($selenium, "View License Qualifications");
        $this->list = new QualificationLicenseList($selenium, "//form[@id='frmList']", true);
    }

    public function goToAddQualificationLicense() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddQualificationLicensePageObject($this->selenium);
    }

    public function deleteQualificationLicense($license) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Name", $license);
        $this->selenium->clickAndWait($this->btnDelete);
    }

    public function deleteAllLicenseQualifications() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->clickAndWait($this->btnDelete);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}