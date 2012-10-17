<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VacancyPrerequisiteHandler
 *
 * @author madusani
 */
class VacancyPrerequisiteHandler extends PrerequisiteHandler {

    private $selenium;
    private $listOfVacanciesInDB;

    //private static $externalDependenciesSet = false;

    public function __construct($selenium) {

        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/vacancyPrerequisites.yml";
        $loadedPrerequisites = sfYaml::load($fixture);
        $this->selenium = $selenium;
        $allVacancies = new VacancyService();
        $this->listOfVacanciesInDB = $allVacancies->getAllVacancies();
        parent::__construct("recruitment", $loadedPrerequisites);
    }

    protected function isPrerequisiteInDB($prerequisiteRecord) {
        $vacancyName = $prerequisiteRecord['vacancyName'];
        foreach ($this->listOfVacanciesInDB as $vacancy) {
            if ($vacancyName == $vacancy->getName()) {
                return true;
            }
        }

        return false;
    }

    public function addPrerequisite($prerequisiteRecord) {

        if ($prerequisiteRecord) {
            $vacancyObject = $this->createVacancyObjectFromArray($prerequisiteRecord);
        } else {
            echo "Vacancy  is not found in Prerequisites YML\n";
        }
        Helper::loginUser($this->selenium, "admin", "admin");
        $viewVacancy = Menu::goToVacancyList($this->selenium);
        $addVacancy = $viewVacancy->goToAddVacancy();
        $addVacancy->saveVacancy($vacancyObject);
    }

    private function createVacancyObjectFromArray($record) {
        $addVacancyFilter = new AddVacancyFilter($record["jobTitle"], $record["vacancyName"], $record["hiringManager"]);
        $addVacancyFilter->vacancyOptionalFields($record["noOfPositions"], $record["description"], $record["active"]);
        return $addVacancyFilter;
    }

    public function deletePrerequisite($recordID) {
        foreach ($recordID as $vacancy) {
            $exploded = explode(" ", $vacancy);
            $prerequisite = $exploded[0];

            $prerequisiteRecord = $this->extractPrerequisiteRecordFromYML($prerequisite);
            Helper::loginUser($this->selenium, "admin", "admin");
            $viewVacancy = Menu::goToVacancyList($this->selenium);
            $viewVacancy->deleteOneVacancy("Vacancy", $vacancy);
        }
    }

}