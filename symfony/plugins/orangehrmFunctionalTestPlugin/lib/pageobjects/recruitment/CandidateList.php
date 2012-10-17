<?php

class CandidateList extends BasicList {

    public $xpathOfList = '';
    public $selectAllPresent = null;
    public $chkboxName = '';
    protected $selenium;
    private $var = "ohrmList_chkSelectAll";
    private $btnDelete = 'btnDelete';

    function __construct($selenium, $xpathOfList, $selectAllPresent=FALSE) {
        $this->selenium = $selenium;
        $this->xpathOfList = $xpathOfList;
        //  if ($selectAllPresent)
        $this->selectAllPresent = $this->xpathOfList . "//thead/tr/th[1]/*[@type='checkbox']";
        parent::__construct($this->selenium, "//div[@id='candidatesSrchResults']", true);
    }

    public function selectAllInTheList() {
        $this->selenium->selectFrame("relative=top");
        //$this->selenium->click($this->selectAllPresent);
        $this->selenium->click($this->var);
        return $this->selenium->isChecked($this->selectAllPresent);
    }

    public function clickOnCandidateListItem($header, $itemName) {

        $columnNumber = $this->getCandidateListColumnNumber($header);
        if ($columnNumber != FALSE) {
            $this->selenium->isTextPresent($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");
            $this->selenium->click($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");
            $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
            $editCandidate = new EditCandidate($this->selenium);
            return $editCandidate;
        }
        else
            return FALSE;
    }

    public function getCandidateListColumnNumber($header) {
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

        $columnNumber = $this->getCandidateListColumnNumber($header);


        //echo "\n Column number is : $columnNumber \n";

        if ($columnNumber != FALSE) {
            //echo "xpath for item : " . $this->xpathOfList . "//*/tr/td[$columnNumber]//.[text()='" . $itemName . "']";

            return $this->selenium->isElementPresent($this->xpathOfList . "//*/tr/td[$columnNumber]//.[text()='" . $itemName . "']");
        }
        else
            return FALSE;
    }

}