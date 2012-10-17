<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LocationsList
 *
 * @author intel
 */
class LocationsList extends BasicList {

    public function __construct($selenium, $xpathOfList, $selectAllPresent = FALSE) {
        parent::__construct($selenium, $xpathOfList, $selectAllPresent);
        $this->selectAllPresent = $this->xpathOfList . "//thead/tr/th[1]";
    }

    public function selectAllInTheList() {
        $this->selenium->click("ohrmList_chkSelectAll");
    }

    public function getColumnNumber($header) {
        $columnNumber = null;

        //echo " xpath is : " . $this->xpathOfList . "//table/thead/tr/td[1]//.[text()='" . $header . "']";
        for ($columnNumber = 1; $columnNumber <= 10; $columnNumber++) {


            if ($this->selenium->isElementPresent($this->xpathOfList . "//table/thead/tr/th[$columnNumber]//.[text()='" . $header . "']")) {

                return $columnNumber;
            }
        }
        return 0;
    }

}