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
class ViewNationalitiesPageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $list;
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";

    public function __construct($selenium) {
        parent::__construct($selenium, "View Nationalities");
        $this->list = new NationalitiesList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function goToAddNationality() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddNationalityPageObject($this->selenium);
    }

//    public function deleteNationality($nationality) {
//        $this->selenium->selectFrame("relative=top");
//        $this->list->select("Nationality", $nationality);
//        $this->selenium->click($this->btnDelete);
//        $this->selenium->clickAndWait($this->dialogDeleteBtn);
//    }
//
//    public function deleteAllNationalities() {
//        $this->selenium->selectFrame("relative=top");
//        $this->list->selectAllInTheList();
//        $this->selenium->click($this->btnDelete);
//        $this->selenium->clickAndWait($this->dialogDeleteBtn);
//    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}