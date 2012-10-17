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
class ViewQualificationLanguagesPageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDel";
    public $list;

    public function __construct($selenium) {
        parent::__construct($selenium, "View Language Qualifications");
        $this->list = new QualificationLanguagesList($selenium, "//form[@id='frmList']", true);
    }

    public function goToAddQualificationLanguage() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddQualificationLanguagePageObject($this->selenium);
    }

    public function deleteQualificationLanguage($language) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Name", $language);
        $this->selenium->clickAndWait($this->btnDelete);
    }

    public function deleteAllLanguageQualifications() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->clickAndWait($this->btnDelete);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}