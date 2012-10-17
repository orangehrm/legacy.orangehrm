<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeaveTypeList
 *
 * @author madusani
 */
class LeaveTypeList extends BasicList {

    public function __construct($selenium, $xpathOfList, $selectAllPresent = FALSE) {
        parent::__construct($selenium, $xpathOfList, $selectAllPresent);
    }

    public function select($header, $itemName) {

        $columnNumber = $this->getLeaveTypeColumnNumber($header);


        if ($columnNumber != FALSE) {
            $this->selenium->click($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']//../../td/input[@type!='hidden']");

            return $this->selenium->isChecked($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']//../../td/input[@type!='hidden']");
        }
        else
            return FALSE;
    }

    public function getLeaveTypeColumnNumber($header) {
        $columnNumber = null;

        //echo " xpath is : " . $this->xpathOfList . "//table/thead/tr/td[1]//.[text()='" . $header . "']";
        for ($columnNumber = 1; $columnNumber <= 10; $columnNumber++) {


            if ($this->selenium->isElementPresent($this->xpathOfList . "//table/thead/tr/th[$columnNumber]//.[text()='" . $header . "']")) {

                return $columnNumber;
            }
        }
        return 0;
    }

    public function isItemPresentInColumn($header, $itemName) {


        $columnNumber = $this->getLeaveTypeColumnNumber($header);



        if ($columnNumber != FALSE) {
            //echo "xpath for item : " . $this->xpathOfList . "//*/tr/td[$columnNumber]//.[text()='" . $itemName . "']";

            return $this->selenium->isElementPresent($this->xpathOfList . "//*/tr/td[$columnNumber]//.[text()='" . $itemName . "']");
        }
        else
            return FALSE;
    }

    public function clickOntheItem($header, $itemName) {

        $columnNumber = $this->getLeaveTypeColumnNumber($header);


        if ($columnNumber != FALSE) {
            $this->selenium->isTextPresent($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");
            $this->selenium->click($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");
            $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
            return $this;
        }
        else
            return FALSE;
    }

}