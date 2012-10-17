<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InterviewFlow
 *
 * @author Faris
 */
class InterviewFlow extends flow {

    public $dataArray;
    public $selenium;
    public $inrterviewPageObject;

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

        $editCandidate = $viewCandidate->list->clickOnCandidateListItem("Candidate", $this->dataArray['candidateName']); //parameter candidate and the name of the candidate
        $this->inrterviewPageObject = $editCandidate->editStatus($this->dataArray['status']); // edit as Schedule Interview for the candidate specified above

        $this->inrterviewPageObject->addInterviewDeatils($this->dataArray['interviewTitle'], $this->dataArray['interviewer'], $this->dataArray['date'], $this->dataArray['time'], $this->dataArray['notes']);
        $this->inrterviewPageObject->clickBackBtn();
        if ($verify)
            return $this->verify();
        else
            return true;
    }

    public function verify() {
        $candidateFullName = $this->dataArray['candidateName'];
        return RecruitmentHelper::isCandidateStatus($this->selenium, $candidateFullName, "Interview Scheduled");
    }

}