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
class EditVacancy extends Page {

    //public $btnShortlist = "//div[@id='addCandidate']/div/div[2]//div[@id='0']/select[@class='actionDrpDown']";
    public $btnSave;
    public $btnShortlist;
    public $chkActive;
    public $chkPublish;
    public $config;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->btnShortlist = "//div[@id='addVacancy']/div/div[2]//div[@id='0']/select[@class='actionDrpDown']";
        $this->btnSave = "btnSave";
        $this->chkActive = "addJobVacancy_status";
        $this->chkPublish = "addJobVacancy_publishedInFeed";
    }

    // public function saveCandidate($inputDataArray){
    //parent::saveCandidate($inputDataArray);
    //}

    public function goToShortlistPage() {
        
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnSave);
        
        $this->selenium->select($this->btnShortlist, "label=Shortlist");
        
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());


        return new Shortlist($this->selenium);
    }

    public function clickOnActive() {
        $this->selenium->click($this->btnSave);
        $this->selenium->click($this->chkActive);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function clickOnPublish() {
        $this->selenium->click($this->btnSave);
        $this->selenium->click($this->chkPublish);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

}