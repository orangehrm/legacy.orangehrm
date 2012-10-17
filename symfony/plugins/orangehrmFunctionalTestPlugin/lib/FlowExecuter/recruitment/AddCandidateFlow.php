<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of addCandidateFlow
 *
 * @author Faris
 */
class AddCandidateFlow extends flow {

    public $dataArray;
    public $candidateFilterObject;
    public $selenium;
    public $addCandidatePageObject;
    public $menu;

    public function __construct($selenium) {
        $this->selenium = $selenium;
        $this->menu = new Menu();
    }

    public function init($dataArray) {

        $this->dataArray = $dataArray;

        $this->candidateFilterObject = new AddCandidateFilter($this->dataArray['firstName'], $this->dataArray['lastName'], $this->dataArray['email']);
        $this->candidateFilterObject->candidateOptionalPersonalFields($this->dataArray['middleName'], $this->dataArray['contactNumber']);
        $this->candidateFilterObject->candidateOptionalOfficialFields($this->dataArray['vacancy'], $this->dataArray['resume'], $this->dataArray['keywords'], $this->dataArray['comment'], $this->dataArray['dateOfApplication']);
    }

    public function execute($verify=true) {

        $viewCandidate = Menu::goToCandidateList($this->selenium);
        $this->addCandidatePageObject = $viewCandidate->gotoAddCandidate();
        $this->addCandidatePageObject->saveCandidate($this->candidateFilterObject);

        if ($verify)
            return $this->verify();
        else
            return true;
    }

    public function verify() {
        return TRUE;
    }

}