<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CandidatePrerequisiteHandler
 *
 * @author madusani
 */
class CandidatePrerequisiteHandler extends PrerequisiteHandler {

    private $selenium;
    private $listOfCandidatesInDB;

    //private static $externalDependenciesSet = false;

    public function __construct($selenium) {
        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/candidatePrerequisites.yml";
        $loadedPrerequisites = sfYaml::load($fixture);
        $this->selenium = $selenium;
        $allCandidates = new CandidateService();
        $this->listOfCandidatesInDB = $allCandidates->getCandidateList(null);
        parent::__construct("recruitment", $loadedPrerequisites);
    }

    protected function isPrerequisiteInDB($prerequisiteRecord) {

        foreach ($this->listOfCandidatesInDB as $candidate) {

            if ($prerequisiteRecord['firstName'] == $candidate->getFirstName() &&
                    $prerequisiteRecord['middleName'] == $candidate->getMiddleName() &&
                    $prerequisiteRecord['lastName'] == $candidate->getLastName()
            ) {
                return true;
            }
        }

        return false;
    }

    public function addPrerequisite($prerequisiteRecord) {
        if ($prerequisiteRecord) {
            $candidateObject = $this->createCandidateObjectFromArray($prerequisiteRecord);
        } else {
            echo "Candidate is not found in Prerequisites YML\n";
        }
        Helper::loginUser($this->selenium, "admin", "admin");
        $viewCandidate = Menu::goToCandidateList($this->selenium);
        $addCandidate = $viewCandidate->goToAddCandidate();
        $addCandidate->saveCandidate($candidateObject);
    }

    private function createCandidateObjectFromArray($record) {
        $addCandidateFilter = new AddCandidateFilter($record["firstName"], $record["lastName"], $record["email"]);
        $addCandidateFilter->CandidateOptionalOfficialFields($record["vacancy"], $record["resume"], $record["keywords"], $record["comment"], $record["dateOfApplication"]);
        $addCandidateFilter->candidateOptionalPersonalFields($record["middleName"], $record["contactNumber"]);
        return $addCandidateFilter;
    }

    public function deletePrerequisite($prerequisiteRecordID) {

        foreach ($prerequisiteRecordID as $candidate) {
            $prerequisiteRecord = $this->extractPrerequisiteRecordFromYML($candidate);

            Helper::loginUser($this->selenium, 'admin', 'admin');
            $viewCandidate = Menu::goToCandidateList($this->selenium);
            $candidateName = Helper::getFullName($prerequisiteRecord['firstName'], $prerequisiteRecord['lastName'], $prerequisiteRecord['middleName']);
            if ($candidateName)
                $viewCandidate->deleteOneCandidate("Candidate", $candidateName);
        }
    }

//    private function setExternalDependencies() {
//        $externalDependencyHandler = new ExternalDependencyHandler();
//        $externalDependencyHandler->ensureRecruitmentCandidateDependencies();
//        }
}