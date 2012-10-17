<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of addVacancyFlow
 *
 * @author Faris
 */
class AddVacancyFlow extends flow {

    public $dataArray;
    public $vacancyFilterObject;
    public $selenium;
    public $addVacancyPageObject;
    public $menu;

    public function __construct($selenium) {
        /* @var $selenium type */
        $this->selenium = $selenium;
        $this->menu = new Menu();
    }

    public function init($dataArray) {

        $this->dataArray = $dataArray;

        //for($i=0; $i<count($this->dataArray); $i++){
        //echo key($this->dataArray) . "\n";
        //echo $this->dataArray['jobTitle'] . "\n";
        //echo $this->dataArray['vacancyName'] . "\n";
        //next($this->dataArray);
        //}

        $this->vacancyFilterObject = new AddVacancyFilter($this->dataArray['jobTitle'], $this->dataArray['vacancyName'], $this->dataArray['hiringManager']);
        $this->vacancyFilterObject->vacancyOptionalFields($this->dataArray['numberOfPositions'], $this->dataArray['description'], $this->dataArray['active']);
    }

    public function execute($verify=true) {


        $viewVacancy = Menu::goToVacancyList($this->selenium);
        $this->addVacancyPageObject = $viewVacancy->goToAddVacancy();
        $this->addVacancyPageObject->saveVacancy($this->vacancyFilterObject);

        if ($verify)
            return $this->verify();
        else
            return true;
    }

    public function verify() {
        $viewVacancy = Menu::goToVacancyList($this->selenium);

        if ($this->dataArray['active'] == 'no') {
            $vacancyName = $this->vacancyFilterObject->vacancyName . " " . "(Closed)";
            $expected[0] = array("Vacancy" => $vacancyName);
            //$this->verifier->assertTrue($viewVacancy->list->isRecordsPresentInList($expected), $this->vacancyFilterObject->vacancyName . " did not get saved");
            if (!$viewVacancy->list->isRecordsPresentInList($expected)) {
                echo $this->vacancyFilterObject->vacancyName . " did not get saved12";
                return FALSE;
            }
        }
        if ($this->dataArray['active'] == 'yes') {
            $vacancyName = $this->vacancyFilterObject->vacancyName;
            $expected[0] = array("Vacancy" => $vacancyName);
            //$this->verifier->assertTrue($viewVacancy->list->isRecordsPresentInList($expected), $this->vacancyFilterObject->vacancyName . " did not get saved");
            if (!$viewVacancy->list->isRecordsPresentInList($expected)) {
                echo $this->vacancyFilterObject->vacancyName . " did not get saved";
                return FALSE;
                //also check where its gonna take
            }
        }
        return true;
    }

}