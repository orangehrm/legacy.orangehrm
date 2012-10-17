<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TimeSheetEditView
 *
 * @author Faris
 */
class TimeSheetEditView extends Page {

    public $btnSave;
    public $btnReset;
    public $btnCancel;
    public $btnAddrow;
    public $btnRemoveRow;
    public $timeSheetList;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->btnSave = "submitSave";
        $this->btnReset = "btnReset";
        $this->btnCancel = "btnBack";
        $this->btnAddrow = "btnAddRow";
        $this->btnRemoveRow = "submitRemoveRows";
        //$this->timeSheetList = BasicList($selenium, "");
    }

    public function addTimeSheetRecord($dataArray) {
        $this->selenium->selectFrame("relative=top");
        $rowCount = count($dataArray);
        $tableColumn = 2;
        $tableRow = 2;

        for ($i = 0; $i < $rowCount; $i++) {
            $activityRow = $dataArray[$i];
            $tableColumn = 2;


            if ($tableRow != 2) {
                $this->selenium->click($this->btnAddrow);
            }

            for ($y = 0; $y < 9; $y++) {

                if ($tableRow == 2) {
                    $xpath = "//form[@id='timesheetForm']/table/tbody/tr[" . $tableRow . "]/td[" . $tableColumn . "]";
                } else {

                    $xpath = "//div[@id='extraRows']/table/tbody/tr/td[" . $tableColumn . "]";
                }


                if (key($activityRow) == 'ProjectName') {
                    $this->selenium->type($xpath . "/input", $activityRow[key($activityRow)]);
                    $this->selenium->click($xpath . "/input");

                    $this->selenium->click('//div[@class="ac_results"]/ul/li[@class="ac_even ac_over"]');
                    sleep(5);
                } else if (key($activityRow) == 'ActivityName') {

                    $this->selenium->select($xpath . "/select", "label=" . $activityRow[key($activityRow)]);
                } else {
                    $this->selenium->type($xpath . "/div/input", $activityRow[key($activityRow)]);
                }

                next($activityRow);
                $tableColumn++;
            }
            next($dataArray);
            $tableRow++;
        }
    }

    public function editTimeSheetRecord($dataArray) {
        $this->selenium->selectFrame("relative=top");
        $rowCount = count($dataArray);
        $tableRow = 2;

        for ($i = 0; $i < $rowCount; $i++) {
            $activityRow = $dataArray[$i];
            $tableColumn = 2;
            $activityRowCount = count($activityRow);

            for ($y = 0; $y < $activityRowCount; $y++) {
                $columnNumber = $this->getColumnNumber(key($activityRow));
                $xpath = "//form[@id='timesheetForm']/table/tbody/tr[" . $tableRow . "]/td[" . $columnNumber . "]";

                if (key($activityRow) == 'ProjectName') {
                    $this->selenium->type($xpath . "/input", $activityRow[key($activityRow)]);
//                    $this->selenium->click($xpath . "/input");
//
//                    $this->selenium->click('//div[@class="ac_results"]/ul/li[@class="ac_even ac_over"]');
                    sleep(5);
                } else if (key($activityRow) == 'ActivityName') {

                    $this->selenium->select($xpath . "/select", "label=" . $activityRow[key($activityRow)]);
                } else {
                    $this->selenium->type($xpath . "/div/input", $activityRow[key($activityRow)]);
                }
                next($activityRow);
            }

            next($dataArray);
            $tableRow++;
        }
    }

    //This Row Wont be needed anymore
    public function clickAddRows($noOfRows) {

        $this->selenium->selectFrame("relative=top");
        for ($y = 1; $y < $noOfRows; $y++) {
            $this->selenium->click($this->btnAddrow);
        }
    }

    public function clickSave() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function clickReset() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnReset);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function clickCancel() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnCancel);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function getColumnNumber($header) {
        $columnNumber = null;


        for ($columnNumber = 1; $columnNumber <= 10; $columnNumber++) {


            if ($this->selenium->isElementPresent("//form[@id='timesheetForm']/table/thead/tr/td[$columnNumber]//.[text()=contains(.,'" . $header . "')]")) {
                //form[@id='timesheetForm']/table/thead/tr[1]/td[2]//.[text()=contains(.,'Project')]
                return $columnNumber;
            }
        }
        return 0;
    }
    
    
        public function verifyMessage($message) {
            //echo "//div[@id='messageBalloon_success']/.[text()=contains(.,'" . $message . "')]";
        if ($this->selenium->isElementPresent("//div[@id='messageBalloon_success']/.[text()=contains(.,'" . $message . "')]")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}