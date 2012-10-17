<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DeleteCandidateFlow
 *
 * @author irshad
 */
class DeleteCandidateFlow extends Flow {

    public $dataArray;
    public $selenium;

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
        $viewCandidate->deleteOneCandidate("Candidate", $this->dataArray['candidateName']);

        if ($verify) {
            return $this->verify();
        }else
            return true;
    }

    public function verify() {

//        if (RecruitmentHelper::isCandidatePresent($this->selenium, $this->dataArray['candidateName'])) {
//            return false;
//        }else
            return true;
    }

}