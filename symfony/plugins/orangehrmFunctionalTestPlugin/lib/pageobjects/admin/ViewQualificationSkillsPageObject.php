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
class ViewQualificationSkillsPageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDel";
    public $list;

    public function __construct($selenium) {
        parent::__construct($selenium, "View Skill Qualifications");
        $this->list = new QualificationSkillsList($selenium, "//form[@id='frmList']", true);
    }

    public function goToAddQualificationSkill() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddQualificationSkillPageObject($this->selenium);
    }

    public function deleteQualificationSkill($skill) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Name", $skill);
        $this->selenium->clickAndWait($this->btnDelete);
    }

    public function deleteAllSkillQualifications() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->clickAndWait($this->btnDelete);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}