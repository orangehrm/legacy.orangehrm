<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewHolidays
 *
 * @author madusani
 */
class ViewHolidaysPageObject extends Component {

    public $cmbLeavePeriod = "leavePeriod";
    public $btnSearch = "btnSearch";
    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $list;
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";
    public $addHoliday;

    public function __construct($selenium) {
        parent::__construct($selenium, "View Holiday Records");
        $this->list = new HolidaysList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
        $this->addHoliday = new AddHolidayPageObject($selenium);
    }

    public function viewHolidayRecords($leavePeriod) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->select($this->cmbLeavePeriod, $leavePeriod);
        $this->selenium->clickAndWait($this->btnSearch);
    }

    public function goToAddHoliday() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->clickAndWait($this->btnAdd);
        return new AddHolidayPageObject($this->selenium);
    }

    public function deleteHoliday($holidayName) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Name", $holidayName);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}