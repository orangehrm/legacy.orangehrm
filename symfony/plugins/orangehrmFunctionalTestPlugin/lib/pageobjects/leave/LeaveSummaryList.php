<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeaveSummaryList
 *
 * @author madusani
 */
class LeaveSummaryList extends BasicList {

    public $btnEdit = "btnEdit";
    public $btnReset = "btnReset";

    public function __construct($selenium, $xpathOfList, $selectAllPresent = FALSE) {
        parent::__construct($selenium, $xpathOfList, $selectAllPresent);
    }

    public function clickOnNavigationOption($navigationOption) {
        $this->selenium->selectFrame("realtive=top");

        if ($navigationOption == '1') {
            $this->selenium->click($this->xpathOfList . "//strong[text()='" . $navigationOption . "']");
        } else {
            $this->selenium->click($this->xpathOfList . "//a[text()='" . $navigationOption . "']");
        }

        return $this;
    }

    public function editLeaveEntitledDays($employeeName, $NoOfDays) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnEdit);


        $rowNumber = $this->getRowNumber($employeeName);
        if ($rowNumber != false) {
            $this->selenium->type($this->xpathOfList . "//tr[" . $rowNumber . "]/td[3]/input", $NoOfDays);
            $this->selenium->clickAndWait($this->btnEdit);
            return $this;
        }
        return false;
    }

    public function clickOntheItem($header, $item) {

        $columnNumber = $this->getColumnNumber($header);
        if ($columnNumber != FALSE) {
            $this->selenium->isTextPresent($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");
            $this->selenium->click($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");

            if ($header != 'Employee Name') {
                return new ViewLeaveListPageObject($this->selenium);
            } else {
                return new PersonalDetails($this->selenium);
            }
        }
        else
            return FALSE;
    }

    public function getSuccessfullySavedMessage() {
        return $this->selenium->getText("//form[@id='frmLeaveSummarySearch']/div[2]");
    }

    public function getLeaveSummaryDetails($leaveType, $employeeName=null) {
        $LeaveSummaryDetails['LeaveEntitled'] = $this->getLeaveEntitled($leaveType, $employeeName = null);
        $LeaveSummaryDetails['LeaveScheduled'] = $this->getLeaveScheduled($leaveType, $employeeName = null);
        $LeaveSummaryDetails['LeaveTaken'] = $this->getLeaveTaken($leaveType, $employeeName = null);
        $LeaveSummaryDetails['LeaveBalance'] = $this->getLeaveBalance($leaveType, $employeeName = null);

        return $LeaveSummaryDetails;
    }

    public function getLeaveEntitled($leaveType, $employeeName=null) {

        try {
            if ($employeeName) {
                $xpath = "//table[@class='data-table']//tr//td[contains(text(),'" . $leaveType . "')]/..//td/a[contains(text(),'" . $employeeName . "')]/../..//td[3]/input";
                return $this->selenium->getValue($xpath);
            } else {
                $xpath = "//table[@class='data-table']//tr//td[contains(text(),'" . $leaveType . "')]/..//td[3]";
                return $this->selenium->getText($xpath);
            }
        } catch (Exception $e) {
            
            return $this->selenium->getValue($xpath);
        }
    }

    public function getLeaveTaken($leaveType, $employeeName=null) {
        if ($employeeName) {
            $xpath = "//table[@class='data-table']//tr//td[contains(text(),'" . $leaveType . "')]/..//td/a[contains(text(),'" . $employeeName . "')]/../..//td[5]";
        } else {
            $xpath = "//table[@class='data-table']//tr//td[contains(text(),'" . $leaveType . "')]/..//td[5]";
        }


        try {
            return $this->selenium->getText($xpath);
        } catch (Exception $e) {
            
            return $this->selenium->getText($xpath);
        }
    }

    public function getLeaveScheduled($leaveType, $employeeName=null) {
        if ($employeeName) {
            $xpath = "//table[@class='data-table']//tr//td[contains(text(),'" . $leaveType . "')]/..//td/a[contains(text(),'" . $employeeName . "')]/../..//td[4]";
        } else {
            $xpath = "//table[@class='data-table']//tr//td[contains(text(),'" . $leaveType . "')]/..//td[4]";
        }


        try {
            return $this->selenium->getText($xpath);
        } catch (Exception $e) {
            
            return $this->selenium->getText($xpath);
        }
    }

    public function getLeaveBalance($leaveType, $employeeName=null) {
        if ($employeeName) {
            $xpath = "//table[@class='data-table']//tr//td[contains(text(),'" . $leaveType . "')]/..//td/a[contains(text(),'" . $employeeName . "')]/../..//td[6]";
        } else {
            $xpath = "//table[@class='data-table']//tr//td[contains(text(),'" . $leaveType . "')]/..//td[6]";
        }


        try {
            return $this->selenium->getText($xpath);
        } catch (Exception $e) {
            
            return $this->selenium->getText($xpath);
        }
    }

    /* public function setLeaveEntitled($employeeName, $leaveType) {

      $xpath = "//table[@class='data-table']//tr//td[contains(text(),'" . $inputArray[$this->cmbLeaveType] . "')]/..//td[contains(text(),'" . $inputArray[$this->txtEmpName] . "')]/../td[4]/input[@class='formInputText formEntitlementText']";


      try {
      $this->selenium->type($xpath, $inputArray[$this->LeaveEntitledTOSet]);
      $this->selenium->clickAndWait($this->btnSave);
      } catch (Exception $e) {
      
      $this->selenium->type($xpath, $inputArray[$this->LeaveEntitledTOSet]);
      $this->selenium->clickAndWait($this->btnSave);
      }
      }

     */

    public function getItemOfLeaveSummaryRecord($item) {
        $rowNumber = $this->getRowNumber($item);
        //if ($column == "LeaveType") {
        //echo "value ". $this->selenium->getText($this->xpathOfList . "//table[@class='data-table']//tr/td[2]");
        return $this->selenium->getText($this->xpathOfList . "//table[@class='data-table']//tr[" . $rowNumber . "]/td[2]");
        //}
    }

}