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
class ViewEmploymentStatusPageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $list;
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";

    public function __construct($selenium) {
        parent::__construct($selenium, "View Employment Status");
        $this->list = new EmploymentStatusList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function goToAddEmploymentStatus() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddEmploymentStatusPageObject($this->selenium);
    }

    public function deleteEmploymentStatus($employmentStatus) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Employment Status", $employmentStatus);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function deleteAllEmploymentStatus() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}