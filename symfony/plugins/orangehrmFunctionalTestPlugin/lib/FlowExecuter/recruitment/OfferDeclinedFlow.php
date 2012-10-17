<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OfferDeclinedFlow
 *
 * @author Faris
 */
class OfferDeclinedFlow extends flow {

    public $dataArray;
    public $selenium;
    public $OfferDeclinedPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {

        $this->dataArray = $dataArray;

        if (!is_null($this->dataArray['firstName']) && !is_null($this->dataArray['lastName'])) {
            $this->dataArray['candidateName'] = Helper::getFullName($this->dataArray['firstName'],
                            $this->dataArray['lastName'], $this->dataArray['middleName']);
        }
    }

    public function execute($verify=true) {

        $viewCandidate = Menu::goToCandidateList($this->selenium);
        $editCandidate = $viewCandidate->list->clickOnCandidateListItem("Candidate", $this->dataArray['candidateName']);
        $this->OfferDeclinedPageObject = $editCandidate->editStatus($this->dataArray['status']);


        $this->OfferDeclinedPageObject->declineOffer($this->dataArray['notes']);
        $this->OfferDeclinedPageObject->clickBackBtn();

        if ($verify)
            return $this->verify();
        else
            return true;
    }

    public function verify() {

        $candidateFullName = Helper::getFullName($this->dataArray['firstName'], $this->dataArray['lastName'], $this->dataArray['middleName']);
        return RecruitmentHelper::isCandidateStatus($this->selenium, $candidateFullName, "Offer Declined");
    }

}