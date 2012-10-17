<?php

class VacancyList extends BasicList {

    public $xpathOfList = '';
    public $selectAllPresentVacancy = null;
    public $chkboxName = '';
    protected $selenium;
    private $var = "ohrmList_chkSelectAll";

    function __construct(FunctionalTestcase $selenium, $xpathOfList, $selectAllPresent=FALSE) {
        $this->selenium = $selenium;
        $this->xpathOfList = $xpathOfList;
        //  if ($selectAllPresent)
        $this->selectAllPresentVacancy = $this->xpathOfList . "//thead/tr/th[1]/*[@type='checkbox']";
        parent::__construct($this->selenium, "//div[@id='vacancySrchResults']", true);
    }

    public function selectAllInTheList() {
        $this->selenium->selectFrame("relative=top");
        //$this->selenium->click($this->selectAllPresent);
        $this->selenium->click($this->var);
        return $this->selenium->isChecked($this->selectAllPresentVacancy);
    }

    public function clickOnVacancyListItem($header, $itemName) {

        $columnNumber = $this->getVacancyListColumnNumber($header);

        if ($columnNumber != FALSE) {
            $this->selenium->isTextPresent($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");
            $this->selenium->click($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");
            $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
            $editVacancy = new AddVacancy($this->selenium);
            return $editVacancy;
        }
        else
            return FALSE;
    }

    public function getVacancyListColumnNumber($header) {
        $columnNumber = null;

        //echo " xpath is : " . $this->xpathOfList . "//table/thead/tr/td[1]//.[text()='" . $header . "']";
        for ($columnNumber = 1; $columnNumber <= 10; $columnNumber++) {


            if ($this->selenium->isElementPresent($this->xpathOfList . "//table/thead/tr/th[$columnNumber]//a[text()='" . $header . "']")) {

                return $columnNumber;
            }
        }
        return FALSE;
    }

}