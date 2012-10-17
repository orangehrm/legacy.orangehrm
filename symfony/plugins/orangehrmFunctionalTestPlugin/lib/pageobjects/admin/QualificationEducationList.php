<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JobTitlesList
 *
 * @author intel
 */
class QualificationEducationList extends BasicList {

    public function __construct($selenium, $xpathOfList, $selectAllPresent = FALSE) {
        parent::__construct($selenium, $xpathOfList, $selectAllPresent);
    }

    public function selectAllInTheList() {
        $this->selenium->click("checkAll");
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