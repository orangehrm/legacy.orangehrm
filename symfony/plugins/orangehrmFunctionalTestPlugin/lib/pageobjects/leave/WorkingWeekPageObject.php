<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WorkWeekPageObject
 *
 * @author madusani
 */
class WorkingWeekPageObject extends Component {

    public $btnSave = "saveBtn";
    public $btnReset = "";
    public $cmbMonday = "//select[@id='WorkWeek_day_length_Monday']";
    public $cmbTuesday = "//select[@id='WorkWeek_day_length_Tuesday']";
    public $cmbWednesday = "//select[@id='WorkWeek_day_length_Wednesday']";
    public $cmbThursday = "//select[@id='WorkWeek_day_length_Thursday']";
    public $cmbFriday = "//select[@id='WorkWeek_day_length_Friday']";
    public $cmbSaturday = "//select[@id='WorkWeek_day_length_Saturday']";
    public $cmbSunday = "//select[@id='WorkWeek_day_length_Sunday']";
    public $config;

    public function __construct($selenium) {
        parent::__construct($selenium, "Working Week");
    }

    public function defineDaysOfWorkingWeek($monday=null, $tuesday=null, $wednesday=null, $thursday=null, $friday=null, $saturday=null, $sunday=null) {
        $this->selenium->selectFrame("relative=top");
        if ($monday)
            $this->selenium->select($this->cmbMonday, $monday);
        if ($tuesday)
            $this->selenium->select($this->cmbTuesday, $tuesday);
        if ($wednesday)
            $this->selenium->select($this->cmbWednesday, $wednesday);
        if ($thursday)
            $this->selenium->select($this->cmbThursday, $thursday);
        if ($friday)
            $this->selenium->select($this->cmbFriday, $friday);
        if ($saturday)
            $this->selenium->select($this->cmbSaturday, $saturday);
        if ($sunday)
            $this->selenium->select($this->cmbSunday, $sunday);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editDaysOfWorkingWeek($monday=null, $tuesday=null, $wednesday=null, $thursday=null, $friday=null, $saturday=null, $sunday=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnSave);
        $this->defineDaysOfWorkingWeek($monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday);
    }

    public function getSavedSuccessfullyMessage() {
        return $this->selenium->getText("//div[@id='messageBalloon_success']");
    }

}