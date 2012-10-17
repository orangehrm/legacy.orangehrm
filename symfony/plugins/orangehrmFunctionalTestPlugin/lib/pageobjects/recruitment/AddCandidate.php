<?php

class AddCandidate extends Component {

    public $txtFirstName;
    public $txtMiddleName;
    public $txtLastName;
    public $txtEmail;
    public $txtContactNo;
    public $cmbJobVacancy;
    public $txtresume;
    public $txtKeywords;
    public $txtComment;
    public $txtDateOfApplication;
    public $btnSave;
    public $list;
    public $pageURL;
    public $config;
    public $btnBack;
    public $calBtnDateOfApplication;

    public function __construct($selenium) {
        parent::__construct($selenium, "AddCandidate");
        $this->config = new TestConfig();
        $this->txtFirstName = "addCandidate_firstName";
        $this->txtMiddleName = "addCandidate_middleName";
        $this->txtLastName = "addCandidate_lastName";
        $this->txtEmail = "addCandidate_email";
        $this->txtContactNo = "addCandidate_contactNo";
        $this->cmbJobVacancy = "addCandidate_vacancy";
        $this->txtresume = "addCandidate_resume";
        $this->txtKeywords = "addCandidate_keyWords";
        $this->txtComment = "addCandidate_comment";
        $this->txtDateOfApplication = "addCandidate_appliedDate";
        $this->btnSave = "btnSave";
        $this->list = "";
        $this->pageURL = $this->config->getLoginURL() . "/symfony/web/orangehrm_dev.php/recruitment/addCandidate";
        $this->btnBack = "btnBack";
        $this->calBtnDateOfApplication = "//input[@id='addCandidate_appliedDate_Button']";
    }

    /**
     *
     * @param <type> $candidateObject
     * @return  ViewCandidates
     */
    public function saveCandidate($candidateObject) {

        //$this->selenium->open($this->pageURL);

        //$this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtFirstName, $candidateObject->firstName);
        $this->selenium->type($this->txtMiddleName, $candidateObject->middleName);
        $this->selenium->type($this->txtLastName, $candidateObject->lastName);
        $this->selenium->type($this->txtEmail, $candidateObject->eMail);
        $this->selenium->type($this->txtContactNo, $candidateObject->contactNo);
        if ($this->selenium->isElementPresent($this->cmbJobVacancy))
            $this->selenium->select($this->cmbJobVacancy, $candidateObject->jobVacancy);
        $this->selenium->type($this->txtresume, $candidateObject->resume);
        $this->selenium->type($this->txtKeywords, $candidateObject->keywords);
        $this->selenium->type($this->txtComment, $candidateObject->comment);
        $this->selenium->type($this->txtDateOfApplication, $candidateObject->dateOfApplication);
        //Calender::selectDateUsingCalendar($this->selenium, $this->calBtnDateOfApplication, $candidateObject->dateOfApplication);
        $this->selenium->click($this->btnSave);
        
        if ($this->selenium->isElementPresent("//label[@class='error']")) {
            //echo "not saved";
            return $this;
        } else {
            $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
            //echo "successfully saved";
            return $this;
        }
    }

    public function editTheCandidate($inputDataArray) {
        $this->selenium->click($this->btnSave);
        $this->saveCandidate($inputDataArray);
    }

    public function getSavedSuccessfullyMessage() {
        return $this->selenium->getText("//div[@id='messagebar']/span");
    }

    public function getFileSIzeValidationMessage() {
        return $this->selenium->getText("//div[@id='messageBalloon_warning']");
    }

    public function getArrayOfValidationMessages() {
        $error[$this->txtFirstName] = null;
        $error[$this->txtLastName] = null;
        $error[$this->txtEmail] = null;
        $error[$this->txtContactNo] = null;
        $error[$this->txtDateOfApplication] = null;



        foreach ($error as $key => $value) {
            //echo "xpath is: ". "//label[@class='error']/.[@for='". $key. "']" ."\n";
            //echo "value is: ". $this->selenium->getText("//label[@class='error']/.[@for='". $key. "']") ."\n";

            $error[$key] = $this->selenium->getText("//label[@class='error']/.[@for='" . $key . "']");
        }
        return $error;
    }

    public function clickBack() {

        $this->selenium->click($this->btnBack);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

}