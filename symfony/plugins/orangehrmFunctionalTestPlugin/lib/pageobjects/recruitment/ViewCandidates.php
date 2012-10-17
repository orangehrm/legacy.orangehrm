<?php

/**
 * 2011-06-23
 */
class ViewCandidates extends Page {

    /**
     *
     * @var BasicList $list
     */
    public $cmbJobTitle = "candidateSearch_jobTitle";
    public $cmbVacancy = "candidateSearch_jobVacancy";
    public $cmbHiringManager = "candidateSearch_hiringManager";
    public $txtCandidateName = "candidateSearch_candidateName";
    public $txtKeywords = "candidateSearch_keywords";
    public $cmbStatus = "candidateSearch_status";
    public $cmbMethodofApp = "candidateSearch_modeOfApplication";
    public $txtFromDate = "candidateSearch_fromDate";
    public $txtToDate = "candidateSearch_toDate";
    public $btnSearch = "btnSrch";
    public $pageUrl;
    public $list;
    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $config;

    public function __construct($selenium) {
        $this->config = new TestConfig();
        parent::__construct($selenium);
         
        $this->pageUrl = $this->config->getLoginURL() . "/symfony/web/index.php/recruitment/viewCandidates";
        $this->list = new CandidateList($selenium, "//div[@id='candidatesSrchResults']", true);
    }
    
//    public function waitForAjax($timeout=20000) {
//    $jsCondition = "selenium.browserbot.getUserWindow().$.active == 0";
//    $this->selenium->waitForCondition($jsCondition,$timeout);
//      }
    
    


    public function search($searchCriteriaArray) {

        $this->selenium->selectFrame("relative=top");

        if ($searchCriteriaArray[$this->cmbJobTitle]) {
            $this->selenium->select($this->cmbJobTitle, "label=" . $searchCriteriaArray[$this->cmbJobTitle]);
           
            //sleep(10);
        }
        if ($searchCriteriaArray[$this->cmbVacancy]) {
            
            $this->selenium->select($this->cmbVacancy, "label=" . $searchCriteriaArray[$this->cmbVacancy]);
            
            //sleep(10);
        }
        if ($searchCriteriaArray[$this->cmbHiringManager]) {
            
            
            $this->selenium->select($this->cmbHiringManager, "label=" . $searchCriteriaArray[$this->cmbHiringManager]);
            //sleep(10);
        }


        if ($searchCriteriaArray[$this->txtCandidateName]) {
            
            $this->selenium->type($this->txtCandidateName, $searchCriteriaArray[$this->txtCandidateName]);
        }
        if ($searchCriteriaArray[$this->txtKeywords]) {
            $this->selenium->type($this->txtKeywords, $searchCriteriaArray[$this->txtKeywords]);
        }
        if ($searchCriteriaArray[$this->cmbStatus]) {
            $this->selenium->select($this->cmbStatus, "label=" . $searchCriteriaArray[$this->cmbStatus]);
        }

        if ($searchCriteriaArray[$this->cmbMethodofApp]) {
            $this->selenium->select($this->cmbMethodofApp, "label=" . $searchCriteriaArray[$this->cmbMethodofApp]);
        }
        if ($searchCriteriaArray[$this->txtFromDate]) {
            $this->selenium->type($this->txtFromDate, $searchCriteriaArray[$this->txtFromDate]);
        }
        if ($searchCriteriaArray[$this->txtToDate]) {
            $this->selenium->type($this->txtToDate, $searchCriteriaArray[$this->txtToDate]);
        }


        $this->selenium->click($this->btnSearch);


        // if(! $this->isErrorPresent())                
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function getArrayOfValidationMessages() {
        $error[$this->txtCandidateName] = null;
        $error[$this->txtFromDate] = null;
        $error[$this->txtToDate] = null;

        foreach ($error as $key => $value) {
            //echo $key;
            //echo "xpath is: ". "//label[@class='error']/.[@for='". $key. "']" ."\n";
            //echo "value is: ". $this->selenium->getText("//label[@class='error']/.[@for='". $key. "']") ."\n";

            $error[$key] = $this->selenium->getText("//label[@class='error']/.[@for='" . $key . "']");
            //print_r($error);
        }
        return $error;
    }

    private function isErrorPresent() {

        return $this->selenium->isVisible("//label[@class='error']");
    }

    public function gotoAddCandidate() {
        $this->selenium->click($this->btnAdd);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        return new AddCandidate($this->selenium);
    }

    public function deleteOneCandidate($locator, $value) {
        $this->selenium->selectFrame("relative=top");

        $this->list->select($locator, $value);

        $this->selenium->click($this->btnDelete);
        $this->selenium->click($this->dialogDeleteBtn);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function clickOnDeleteButton() {

        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnDelete);
        $this->selenium->click($this->dialogDeleteBtn);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function isElementPresent($locator) {
        if ($this->selenium->isElementPresent($locator)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}