<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AttendanceRecordEditView
 *
 * @author Faris
 */
class AttendanceRecordEditView extends Page {

    public $btnSave;
    public $btnCancel;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->btnSave = "btnSave";
        $this->btnCancel = "btnCancel";
        $this->btnPenRequest = "pen_request";
    }

    public function editEmployeeAttendanceRecords($dataArray) {

        $this->selenium->selectFrame("relative=top");
        $rowCount = count($dataArray);
        $tableRow = 1;

        for ($i = 0; $i <= $rowCount; $i++) {

            $activityRow = $dataArray[$i];
            $tableColumn = 2;
            $activityRowCount = count($activityRow);



            for ($y = 0; $y < $activityRowCount; $y++) {
                if (key($activityRow) == "In") {
                    $header = "Punch In";
                } else {
                    $header = "Punch Out";
                }
                $columnNumber = $this->getColumnNumber($header);

                $xpath = "//form[@id='employeeRecordsForm']/table/tbody/tr[" . $tableRow . "]/td[" . $columnNumber . "]/input[2]";
                //form[@id='employeeRecordsForm']/table/tbody/tr[". $tableRow ."]/td[". $columnNumber ."]/input[2]
                $this->selenium->type($xpath, $activityRow[key($activityRow)]);
                $this->selenium->click($this->btnPenRequest);

                //the below sleep is not needed.
                //sleep(8);

                next($activityRow);
            }
            next($dataArray);
            $tableRow++;
        }
        sleep(5);
            //this was originally just click. now changed to clickAndWait.
        $this->selenium->clickAndWait($this->btnSave);
        sleep(10);
    }

    public function clickCancel() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnCancel);
    }

    public function getColumnNumber($header) {
        $columnNumber = null;


        for ($columnNumber = 1; $columnNumber <= 10; $columnNumber++) {


            if ($this->selenium->isElementPresent("//form[@id='employeeRecordsForm']/table/thead//tr//td[$columnNumber]//.[text()='" . $header . "']")) {

                return $columnNumber;
            }
        }
        return 0;
    }
    
    public function verifyData($array)
    {
        //print_r($array);
        foreach ($array as $data) {
            
            if(!$this->selenium->isElementPresent($this->xpathOfList . "//div[@id='recordsTable1']/table/tbody/tr/td/.[contains(text(),'". $data ."')]"))
            {
                return false;
            }

        }
        
        return TRUE;
        
    }

}