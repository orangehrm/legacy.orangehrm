<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewVacancies
 *
 * @author madusani
 */
class ViewVacancies extends Component {

    public $list;
    public $btnAdd = "btnAdd";
    public $cmbJobTitle = "vacancySearch_jobTitle";
    public $cmbVacancy = "vacancySearch_jobVacancy";
    public $cmbHiringManager = "vacancySearch_hiringManager";
    public $cmbStatus = "vacancySearch_status";
    public $btnSearch = "btnSrch";
    public $btnReset = "btnRst";
    public $config;
    public $btnDelete = "btnDelete";
    public $dialogueDeleteBtn = "dialogDeleteBtn";

    public function __construct($selenium) {
        $this->config = new TestConfig();
        parent::__construct($selenium, "ViewVacancyComponent");
        //$this->pageUrl = Config::$loginURL . "/symfony/web/index.php/recruitment/viewCandidates";
        $this->list = new VacancyList($selenium, "//div[@id='vacancySrchResults']", true);
    }

    public function search($searchCriteriaArray) {
        $this->selenium->selectFrame("relative=top");
        if ($this->cmbJobTitle) {
            $this->selenium->select($this->cmbJobTitle, "label=" . $searchCriteriaArray[$this->cmbJobTitle]);
            //sleep(10);
        }
        if ($this->cmbVacancy) {

            $this->selenium->select($this->cmbVacancy, "label=" . $searchCriteriaArray[$this->cmbVacancy]);
            //sleep(10);
        }
        if ($this->cmbHiringManager) {
            $this->selenium->select($this->cmbHiringManager, "label=" . $searchCriteriaArray[$this->cmbHiringManager]);
            //sleep(10);
        }
        if ($this->cmbStatus) {
            $this->selenium->select($this->cmbStatus, "label=" . $searchCriteriaArray[$this->cmbStatus]);
            //sleep(10);
        }



        $this->selenium->click($this->btnSearch);
        //if(! $this->isErrorPresent())                
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function goToAddVacancy() {
        $this->selenium->click($this->btnAdd);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        return new AddVacancy($this->selenium);
    }

    private function isErrorPresent() {

        return $this->selenium->isVisible("//label[@class='error']");
    }

    public function deleteOneVacancy($locator, $value) {
        $this->selenium->selectFrame("relative=top");

        $this->list->select($locator, $value);

        $this->selenium->click($this->btnDelete);
        $this->selenium->click($this->dialogueDeleteBtn);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function deleteAllVancancies() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnDelete);
        $this->selenium->click($this->dialogueDeleteBtn);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function getSavedSuccessfullyMessage() {
        return $this->selenium->getText("//div[@id='messagebar']/span");
    }

}