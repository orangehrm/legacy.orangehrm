<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddJobTitlePageObject
 *
 * @author intel
 */
class AddJobTitlePageObject extends Component {

    public $txtJobTitle = "jobTitle_jobTitle";
    public $txtDescription = "jobTitle_jobDescription";
    public $txtJobSpecFile = "jobTitle_jobSpec";
    public $txtNote = "jobTitle_note";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $btnAdd = "btnAdd";
    public $radioBtnKeepCurrent = "jobTitle_jobSpecUpdate_1";
    public $radioBtnDeleteCurrent = "jobTitle_jobSpecUpdate_2";
    public $radioBtnReplaceCurrent = "jobTitle_jobSpecUpdate_3";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Job Title");
    }

    public function addJobTitle($jobTitle, $jobDescription=null, $jobSpecFilePath=null, $option=null, $note=null) {
        
        $this->selenium->selectFrame("relative=top");
        echo $jobTitle;
        $this->selenium->clickAndWait($this->btnAdd);
        
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

    public function editJobTitle($jobTitle, $jobDescription=null, $jobSpecFilePath=null, $option=null, $note=null) {
        
        $this->selenium->click($this->btnSave);
        $this->addJobTitle($jobTitle, $jobDescription, $jobSpecFilePath, $option, $note);
    }
    
    public function getStatusMessage() {

        return $this->selenium->getText("messagebar");
    }
    

}