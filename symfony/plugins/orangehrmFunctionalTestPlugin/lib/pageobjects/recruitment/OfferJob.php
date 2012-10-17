<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OfferJob
 *
 * @author Faris
 */
class OfferJob extends Page {

    public $txtNotes;
    public $btnOfferJob;
    public $btnBack;
    public $config;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->txtNotes = "candidateVacancyStatus[notes]";
        $this->btnOfferJob = "actionBtn";
        $this->btnBack = "cancelBtn";
    }

    public function offerTheJob($notes) {
        // print_r($inputDataArray[$this->txtNotes]);
        //$this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtNotes, $notes);
        $this->selenium->click($this->btnOfferJob);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        /*
          if($this->selenium->isElementPresent("//label[@class='error']")){
          return $this;
          }
          else{
          $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
          //echo "successfully saved";
          return new AddCandidate($this->selenium);
          }
         * 
         */
        return new AddCandidate($this->selenium);
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