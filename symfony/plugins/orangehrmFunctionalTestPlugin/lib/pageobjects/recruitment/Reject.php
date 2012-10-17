<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reject
 *
 * @author Faris
 */
class Reject extends Page {

    public $txtNotes;
    public $btnReject;
    public $btnBack;
    public $config;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->txtNotes = "candidateVacancyStatus[notes]";
        $this->btnReject = "actionBtn";
        $this->btnBack = "cancelBtn";
    }

    public function rejectTheCandidate($notes) {
        // print_r($inputDataArray[$this->txtNotes]);
        //$this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtNotes, $notes);
        $this->selenium->click($this->btnReject);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());

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