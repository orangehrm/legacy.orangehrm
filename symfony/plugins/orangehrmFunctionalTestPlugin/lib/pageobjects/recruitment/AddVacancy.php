<?php

class AddVacancy extends Component {

    public $cmbJobTitle;
    public $txtVacancyName;
    public $txtHiringManager;
    public $txtNoOfPositions;
    public $txtDesc;
    public $chkActive;
    public $btnSave;
    public $list;
    public $pageURL;
    public $chkPublish;
    public $config;

    public function __construct($selenium) {
        parent::__construct($selenium, "AddVacancyComponent");
        $this->config = new TestConfig();
        $this->pageURL = $this->config->getLoginURL() . "symfony/web/index.php/recruitment/addJobVacancy";
        $this->cmbJobTitle = "addJobVacancy_jobTitle";
        $this->txtVacancyName = "addJobVacancy_name";
        $this->txtHiringManager = "addJobVacancy_hiringManager";
        $this->txtNoOfPositions = "addJobVacancy_noOfPositions";
        $this->txtDesc = "addJobVacancy_description";
        $this->chkActive = "addJobVacancy_status";
        $this->chkPublish = "addJobVacancy_publishedInFeed";
        $this->btnSave = "btnSave";
        $this->list = "";
    }

    public function saveVacancy($vacancyObject) {

        //$this->selenium->open($this->pageURL);
        //$this->selenium->selectFrame("relative=top");

        $this->selenium->select($this->cmbJobTitle, $vacancyObject->jobTitle);
        $this->selenium->type($this->txtVacancyName, $vacancyObject->vacancyName);
        $this->selenium->type($this->txtHiringManager, $vacancyObject->hiringManager);
        $this->selenium->type($this->txtNoOfPositions, $vacancyObject->noOfPositions);
        $this->selenium->type($this->txtDesc, $vacancyObject->desc);
        if ($vacancyObject->active == 'yes') {
            $this->selenium->check($this->chkActive);
        }
        if ($vacancyObject->active == 'no') {
            $this->selenium->uncheck($this->chkActive);
        }

        $this->selenium->click($this->btnSave);

        if ($this->selenium->isElementPresent("//label[@class='error']")) {
            return $this;
        } else {
            $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());

            return $this;
        }
    }

    public function editVacancy($inputDataArray) {
        $this->selenium->click($this->btnSave);
        $this->saveVacancy($inputDataArray);
    }

    public function getSavedSuccessfullyMessage() {
        return $this->selenium->getText("//div[@id='messagebar']/span");
    }

    public function getSavedVacancyDetails() {
        return True;
    }

    public function getArrayOfValidationMessages() {
        $error[$this->cmbJobTitle] = null;
        $error[$this->txtVacancyName] = null;
        $error[$this->txtHiringManager] = null;
        $error[$this->txtNoOfPositions] = null;
        $error[$this->txtDesc] = null;

        foreach ($error as $key => $value) {
            //echo "xpath is: ". "//label[@class='error']/.[@for='". $key. "']" ."\n";
            //echo "value is: ". $this->selenium->getText("//label[@class='error']/.[@for='". $key. "']") ."\n";

            $error[$key] = $this->selenium->getText("//label[@class='error']/.[@for='" . $key . "']");
        }
        return $error;
    }

}