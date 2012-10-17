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
class ViewQualificationEducationPageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDel";
    public $list;

    public function __construct($selenium) {
        parent::__construct($selenium, "View Educational Qualifications");
        $this->list = new QualificationEducationList($selenium, "//form[@id='frmList']", true);
    }

    public function goToAddQualificationEducation() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddQualificationEducationPageObject($this->selenium);
    }

    public function deleteQualificationEducation($educationLevel) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Level", $educationLevel);
        $this->selenium->clickAndWait($this->btnDelete);
    }

    public function deleteAllEducationQualifications() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->clickAndWait($this->btnDelete);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}