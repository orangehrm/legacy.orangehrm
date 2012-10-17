<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeaveList
 *
 * @author madusani
 */
class LeaveList extends BasicList {

    public $btnSave = "btnSave";
    public $commentEditBtn = "commentSave";
    public $commentCancelBtn = "commentCancel";
    public $commentTextArea = "leaveComment";

    public function __construct($selenium, $xpathOfList, $selectAllPresent = FALSE) {
        parent::__construct($selenium, $xpathOfList, $selectAllPresent);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getTetx("//div[@class='messageBalloon_success']");
    }

    public function clickOntheItem($header, $itemName) {

        $columnNumber = $this->getLeaveListColumnNumber($header);


        if ($columnNumber != FALSE) {

            $this->selenium->isTextPresent($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");
            $this->selenium->clickAndWait($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");

            if ($header != 'Employee Name') {

                return new EmployeeLeaveRequestsList($this->selenium);
            } else {
                return new PersonalDetails($this->selenium);
            }
        }
        else
            return FALSE;
    }

    public function getLeaveListColumnNumber($header) {
        $columnNumber = null;
        for ($columnNumber = 1; $columnNumber < 11; $columnNumber++) {
            if ($this->selenium->isElementPresent($this->xpathOfList . "//table/thead/tr/th[" . $columnNumber . "]//.[text()='" . $header . "']")) {
                return $columnNumber;
            }
        }
        return false;
    }

    public function performActionOnLeaveRequest($employeeName, $date, $action, $comment=null) {

        $this->selenium->selectFrame("relative=top");

        try {
            if ($comment && $this->commentEditBtn) {


            $this->selenium->click($this->xpathOfList . "//tr/td[contains(.,'" . $employeeName . "')]//..//td[contains(.,'" . $date . "')]//..//img");
            $this->selenium->click($this->commentEditBtn);
            $this->selenium->type($this->commentTextArea, $comment);
            $this->selenium->clickAndWait($this->commentEditBtn);
               sleep(5);
            
             }

            
            $xpathOfComboBox = $this->xpathOfList . "//tr/td[contains(.,'" . $employeeName . "')]//..//td[contains(.,'" . $date . "')]//..//select";
            //echo "\nstarting to perform the action\n";
            //echo "xpath of combo box is " . $xpathOfComboBox;
            $isClicked  = $this->selenium->select($xpathOfComboBox, $action);
            

        $this->selenium->clickAndWait($this->btnSave);
        
            return $isClicked;
            
        } catch (Exception $e) {
            
            return false;
        }



        
        //return $this;
    }

    public function getItemOfFirstRecord($column) {
        if ($column == "LeaveType") {
            //echo " dfdfd " . $this->selenium->getText($this->xpathOfList . "//tr/td[3]");
            return $this->selenium->getText($this->xpathOfList . "//tr/td[3]");
        }
        if ($column == "NumberOfDays")
            return $this->selenium->getText($this->xpathOfList . "//tr/td[5]");
        if ($column == "Status") {

            $status = explode("(", $this->selenium->getText($this->xpathOfList . "//tr/td[6]"));
            return $status[0];
        }
        if ($column == "Action") {

            $action = explode(" ", $this->selenium->getText($this->xpathOfList . "//tr/td[8]"));
            

            return $action[2];
        }
    }

}