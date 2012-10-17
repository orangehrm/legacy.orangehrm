<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Shortlist
 *
 * @author madusani
 */
class Shortlist extends Page {

    public $txtNotes;
    public $btnShortlist;
    public $btnBack;
    public $config;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->txtNotes = "//textarea[@id='candidateVacancyStatus_notes']";
        $this->btnShortlist = "//input[@id='actionBtn']";
        $this->btnBack = "//input[@id='cancelBtn']";
    }

    public function shortlistTheCandidate($inputDataArray) {

        
        $this->selenium->type($this->txtNotes,"hello");
        
        //$this->selenium->type($this->txtNotes, $inputDataArray);
        $this->selenium->click($this->btnShortlist);
        
        if ($this->selenium->isElementPresent("//label[@class='error']")) {
           
            return $this;
        } else {
            $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
            //echo "successfully saved";
            return new AddCandidate($this->selenium);
        }
    }

    public function goToCandidatePage() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnBack);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function clickBackBtn() {
        $this->selenium->click($this->btnBack);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

}