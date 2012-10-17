<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddHoliday
 *
 * @author madusani
 */
class AddHolidayPageObject extends Component {

    public $txtholidayName = "//input[@id='holiday_description']";
    public $txtholidayDate = "//input[@id='holiday_date']";
    public $checkAnnualRepetiton = "//input[@id='holiday_recurring']";
    public $cmbDayType = "//select[@id='holiday_length']";
    public $btnSave = "saveBtn";
    public $btnReset = "btnReset";
    public $btnBack = "btnBack";
    public $calBtnHolidayDate = "//input[@id='holiday_date_Button']";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Holiday");
    }

    public function addHoliday($holidayName, $holidayDate, $annualRepetition=false, $dayType=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtholidayName, $holidayName);
        
        Calender::selectDateUsingCalendar($this->selenium, $this->calBtnHolidayDate, $holidayDate);
        if ($annualRepetition)
            $this->selenium->click($this->checkAnnualRepetiton);
        if ($dayType)
            $this->selenium->select($this->cmbDayType, $dayType);
        $this->selenium->click($this->btnSave);
        
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        return new ViewHolidaysPageObject($this->selenium);
    }

    public function editHoliday($holidayName, $holidayDate, $annualRepetition=false, $dayType=null) {
        $this->addHoliday($holidayName, $holidayDate, $annualRepetition, $dayType);
    }

    public function getWarningMessage() {
        return $this->selenium->getText("//div[@id='messageBalloon_warning']");
    }

   

}