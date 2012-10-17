<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewJobTitlesPageObject
 *
 * @author intel
 */
class ViewJobTitlesPageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $list;
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";

    public function __construct($selenium) {
        parent::__construct($selenium, "View Job Titles");
        $this->list = new JobTitlesList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function goToAddJobTitle() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddJobTitlePageObject($this->selenium);
    }

    public function deleteJobTitle($jobTitle) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Job Title", $jobTitle);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function deleteAllJobTitles() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }
    
    public function addJobTitle($jobTitle, $jobDescription=null, $jobSpecFilePath=null, $option=null, $note=null) {
        $this->selenium->selectFrame("relative=top");
        
        $this->selenium->type($this->txtJobTitle, $jobTitle);
        if ($jobDescription)
            $this->selenium->type($this->txtDescription, $jobDescription);
        if ($this->selenium->isElementPresent($this->radioBtnKeepCurrent)) {
            if ($jobSpecFilePath && $option) {
                switch ($option) {
                    case "Keep":
                        $this->selenium->click($this->radioBtnKeepCurrent);
                        break;
                    case "Delete":
                        $this->selenium->click($this->radioBtnDeleteCurrent);
                        break;
                    case "Replace":
                        $this->selenium->click($this->radioBtnReplaceCurrent);
                        $this->selenium->type($this->txtJobSpecFile, $jobSpecFilePath);
                        break;
                    default:
                        break;
                }
            }
        } else {
            if ($jobSpecFilePath)
                $this->selenium->type($this->txtJobSpecFile, $jobSpecFilePath);
        }


        if ($note)
            $this->selenium->type($this->txtNote, $note);
        $this->selenium->clickAndWait($this->btnSave);
    }
    public function getList(){
        
        return $this->list;
    }
}