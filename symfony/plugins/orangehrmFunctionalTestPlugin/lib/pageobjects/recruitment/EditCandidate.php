<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EditCandidate
 *
 * @author chamila
 */
class EditCandidate extends Page {

    //public $btnShortlist = "//div[@id='addCandidate']/div/div[2]//div[@id='0']/select[@class='actionDrpDown']";
    public $btnSave;
    //public $btnShortlist;
    public $config;
    public $statusDropDown;
    public $btnBack;


    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        //$this->btnShortlist = "//div[@id='addCandidate']/div/div[2]//div[@id='0']/select[@class='actionDrpDown']";
        $this->btnSave = "btnSave";
        $this->btnBack = "btnBack";
        $this->statusDropDown = "//select[@class='actionDrpDown']";
    }

    // public function saveCandidate($inputDataArray){
    //parent::saveCandidate($inputDataArray);
    //}
    public function editTheCandidate($candidateObject) {
        $addCandidate = new AddCandidate($this->selenium);
        $addCandidate->editTheCandidate($candidateObject);
    }
    
    private function waitForValueInComboBox($locator, $expectedValue, $timeOut){
        
        for ($i=0; $i < $timeOut; $i++){
            $values = $this->selenium->getSelectOptions($locator);
            if(!in_array($expectedValue, $values)){
                sleep(1);
                
            }  else {
                return true;
            }
            
        }
        return false;
        
    }

    public function editStatus($status) {
        $this->selenium->selectFrame("relative=top");
        if ($this->isElementPresent($this->btnSave)) {
            $this->selenium->click($this->btnSave);
        }
        
        if ($status != "No Actions") {

            if ($this->waitForValueInComboBox($this->statusDropDown, $status, $this->config->getTimeoutValue())){

            $this->selenium->select($this->statusDropDown, "label=" . $status);
            $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
            //$this->selenium->select($this->statusDropDown, "label=" . $status);
            }
        }

        //there are five status
        if ($status == "Shortlist") {
            
            return new Shortlist($this->selenium);
        } elseif ($status == "Schedule Interview") {
            return new Interview($this->selenium);
        } elseif (($status == "Mark Interview Passed") || ($status == "Mark Interview Failed")) {
            return new InterviewStatus($this->selenium);
        } elseif ($status == "Offer Job") {
            return new OfferJob($this->selenium);
        } elseif ($status == "Hire") {
            return new Hire($this->selenium);
        } elseif ($status == "Decline Offer") {
            return new OfferDeclined($this->selenium);
        } elseif ($status == "Reject") {
            return new Reject($this->selenium);
        } elseif ($status == "No Actions") {
            return false;
        }
    }

    public function getSavedSuccessfullyMessage() {
        $addCandidate = new AddCandidate($this->selenium);
        return $addCandidate->getSavedSuccessfullyMessage();
    }

    public function isElementPresent($locator) {
        if ($this->selenium->isElementPresent($locator)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}