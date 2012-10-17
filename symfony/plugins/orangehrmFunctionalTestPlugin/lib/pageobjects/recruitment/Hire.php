<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Hire
 *
 * @author Faris
 */
class Hire extends Page {

    //put your code here



    public $txtNotes;
    public $btnHire;
    public $btnBack;
    public $config;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->txtNotes = "candidateVacancyStatus[notes]";
        $this->btnHire = "actionBtn";
        $this->btnBack = "cancelBtn";
    }

    public function hireTheCandidate($notes) {
        // print_r($inputDataArray[$this->txtNotes]);
        //$this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtNotes, $notes);
        $this->selenium->click($this->btnHire);
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