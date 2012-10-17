<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class RecruitmentHelper {

    public static $isPrerequisitesLoaded = false;

    public static function loadRecruitmentPrerequisites($selenium) {

        if (!self::$isPrerequisitesLoaded) {
            Helper::deleteAllFromTable("ohrm_job_candidate");
            if (self::addPrerequisiteVacancies($selenium))
                self::$isPrerequisitesLoaded = true;
        }
    }

    public static function addPrerequisiteVacancies($selenium) {
        return self::createPrerequisiteVacancies($selenium);
    }

    public static function addPrerequisiteCandidates($selenium) {
        return self::createPrerequisiteCandidates($selenium);
    }

    public static function loadFixtureToInputArray($fixturePath, $section, $pageobject, $arrayType) {
        $mapper = self::getFixtureToInputArrayMapping($pageobject, $arrayType);
        //echo "printing mapper "; print_r($mapper);

        $loadedFixture = sfYaml::load($fixturePath);

        // echo "\n\n\n\n" . sfYaml::dump($loadedFixture["ValidVacancies"]["all_fields"]["jobTitle"]) . "\n\n\n\n";
        if (count($mapper['fixture']) != count($mapper['inputData'])) {
            echo "number of fixture fields and number of inputData fields are different \n";
            echo "fixture count: " . count($mapper['fixture']) . "\t inputData count: " . count($mapper['inputData']);
            exit();
        }

        $recordNumber = 0;
        //reset($loadedFixture[$section]);
        for ($i = 0; $i < count($loadedFixture[$section]); $i++) {
            //foreach ($loadedFixture[$section] as $record) {
            $record = current($loadedFixture[$section]);
            //$recordNumber = key($loadedFixture[$section]) . "\n";


            for ($columnNumber = 0; $columnNumber < count($mapper['inputData']); $columnNumber++) {
                //$recordName =key($loadedFixture[$section]);
                $inputData[$recordNumber][$mapper['inputData'][$columnNumber]] = $record[$mapper['fixture'][$columnNumber]];
            }

            $recordNumber++;
            next($loadedFixture[$section]);
        }

        return $inputData;
    }

    public static function createPrerequisiteVacancies($selenium) {

        $fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmFunctionalTestPlugin/test/recruitment/testdata/AddVacancyPrerequisites.yml';

        TestDataService::populate($fixture);

        $fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmFunctionalTestPlugin/test/recruitment/testdata/AddVacancyTest.yml';
        $addVacancy = new AddVacancy($selenium);
        $inputData = self::loadFixtureToInputArray($fixture, "ValidVacancies", $addVacancy, "Input");
        //print_r($inputData);

        Helper::logOutIfLoggedIn($selenium);
        Helper::loginUser($selenium, "admin", "admin");
        foreach ($inputData as $record) {
            try {
                $vacancyObject = new AddVacancyFilter($record[$addVacancy->cmbJobTitle], $record[$addVacancy->txtVacancyName], $record[$addVacancy->txtHiringManager]);
                $vacancyObject->vacancyOptionalFields($record[$addVacancy->txtNoOfPositions], $record[$addVacancy->txtDesc], $record[$addVacancy->chkActive]);
                $viewVacancy = Menu::goToVacancyList($selenium);
                $viewVacancy->goToAddVacancy();
                $addVacancy->saveVacancy($vacancyObject);
                $vacancyName = $selenium->getValue($addVacancy->txtVacancyName);
                //echo $vacancyName;
                if ($record[$addVacancy->txtVacancyName] != $vacancyName) {

                    echo "\n the following vacancy was not created successfully";
                    print_r($record);
                    exit(-1);
                }
                /** if ("Job Vacancy Saved Successfully" != $addVacancy->getSavedSuccessfullyMessage()) {
                  echo "\n the following vacancy was not created successfully";
                  print_r($record);
                  exit(-1);
                  }* */
            } catch (Exception $e) {
                echo " Error while creating vacancy of the record ";
                print_r($record);
                exit(-1);
            }
        }
        Helper::logOutIfLoggedIn($selenium);
        return true;
    }

    public static function createPrerequisiteCandidates($selenium) {
        //$fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmRecruitmentPlugin/test/fixtures/AddVacancyPrerequisites.yml';
        //TestDataService::populate($fixture);
        $fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmFunctionalTestPlugin/test/recruitment/testdata/AddCandidateTest.yml';
        $addCandidate = new AddCandidate($selenium);
        $inputData = self::loadFixtureToInputArray($fixture, "ValidCandidate", $addCandidate, $arrayType);

        Helper::logOutIfLoggedIn($selenium);
        Helper::loginUser($selenium, "admin", "admin");
        foreach ($inputData as $record) {
            try {
                $candidateObject = new AddCandidateFilter($record[$addCandidate->txtFirstName], $record[$addCandidate->txtLastName], $record[$addCandidate->txtEmail]);
                $candidateObject->candidateOptionalOfficialFields($record[$addCandidate->cmbJobVacancy], $record[$addCandidate->txtresume], $record[$addCandidate->txtKeywords], $record[$addCandidate->txtComment], $record[$addCandidate->txtDateOfApplication]);
                $viewCandidate = Menu::goToCandidateList($selenium);
                $addCandidate = $viewCandidate->gotoAddCandidate();
                $addCandidate->saveCandidate($candidateObject);
                if ("Job Candidate Saved Successfully" != $addCandidate->getSavedSuccessfullyMessage()) {
                    echo "\n the following candidate was not created successfully";
                    print_r($record);
                    exit(-1);
                }
            } catch (Exception $e) {
                echo " Error while creating vacancy of the record ";
                print_r($record);
                exit(-1);
            }
        }
        Helper::logOutIfLoggedIn($selenium);
        return true;
    }

    public static function getFixtureToInputArrayMapping($pageobject, $arrayType) {

        $fixtureFields = null;
        $inputData = null;

        if (($pageobject instanceof AddVacancy) && ($arrayType == "Input")) {
            $fixtureFields = array('jobTitle', 'vacancyName', 'hiringManager', 'numberOfPositions', 'description', 'active');
            $inputData = array($pageobject->cmbJobTitle, $pageobject->txtVacancyName, $pageobject->txtHiringManager, $pageobject->txtNoOfPositions, $pageobject->txtDesc, $pageobject->chkActive);
        }
        if ($pageobject instanceof AddCandidate) {
            $fixtureFields = array('firstName', 'middleName', 'lastName', 'contactNumber', 'keywords', 'dateOfApplication', 'email', 'comment', 'vacancy', 'resume');
            $inputData = array($pageobject->txtFirstName, $pageobject->txtMiddleName, $pageobject->txtLastName, $pageobject->txtContactNo, $pageobject->txtKeywords, $pageobject->txtDateOfApplication,
                $pageobject->txtEmail, $pageobject->txtComment, $pageobject->cmbJobVacancy, $pageobject->txtresume);
        }

        if (($pageobject instanceof ViewCandidates) && ($arrayType == "Input")) {
            $fixtureFields = array('JobTitle', 'Vacancy', 'HiringManager', 'CandidateName', 'Keywords', 'Status', 'MethodofApplication', 'From', 'To', 'ExpectedResult');
            $inputData = array($pageobject->cmbJobTitle, $pageobject->cmbVacancy, $pageobject->cmbHiringManager, $pageobject->txtCandidateName, $pageobject->txtKeywords, $pageobject->cmbStatus,
                $pageobject->cmbMethodofApp, $pageobject->txtFromDate, $pageobject->txtToDate, 'ExpectedResult');
        }

        if (($pageobject instanceof ViewCandidates) && ($arrayType == "Output")) {
            $fixtureFields = array('Vacancy', 'CandidateName');
            $inputData = array('Vacancy', 'Candidate');
        }

        if (($pageobject instanceof ViewVacancies) && ($arrayType == "Input")) {
            $fixtureFields = array('JobTitle', 'Vacancy', 'HiringManager', 'Status', 'ExpectedResult');
            $inputData = array($pageobject->cmbJobTitle, $pageobject->cmbVacancy, $pageobject->cmbHiringManager, $pageobject->cmbStatus, 'ExpectedResult');
        }

        if (($pageobject instanceof ViewVacancies) && ($arrayType == "Output")) {
            $fixtureFields = array('Vacancy', 'JobTitle');
            $inputData = array('Vacancy', 'Job Title');
        }

        if (($pageobject instanceof Shortlist) && ($arrayType == "Input")) {
            $fixtureFields = array('Notes');
            $inputData = array($pageobject->txtNotes);
        }



        $mapper['fixture'] = $fixtureFields;
        $mapper['inputData'] = $inputData;
        //print_r($mapper);
        return $mapper;
    }

    public static function isCandidateStatus($selenium, $candidateFullName, $status) {
        $viewCandidate = Menu::goToCandidateList($selenium);

        $searchCondition = array($viewCandidate->txtCandidateName => $candidateFullName);
        $viewCandidate->search($searchCondition);
        $expected[0] = array("Candidate" => $candidateFullName, "Status" => $status);
        try {
            return $viewCandidate->list->isRecordsPresentInList($expected);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public static function isCandidatePresent($selenium, $candidateFullName) {
        $viewCandidate = Menu::goToCandidateList($selenium);

        $searchCondition = array($viewCandidate->txtCandidateName => $candidateFullName);
        $viewCandidate->search($searchCondition);
        $expected[0] = array("Candidate" => $candidateFullName);
        try {
            return $viewCandidate->list->isRecordsPresentInList($expected);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

}