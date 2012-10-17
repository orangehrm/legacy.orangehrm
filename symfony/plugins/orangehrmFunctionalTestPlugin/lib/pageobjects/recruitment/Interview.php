<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Interview
 *
 * @author Faris
 */
class Interview extends Page {

    //put your code here

    public $txtNotes;
    public $btnShortlist;
    public $btnSave;
    public $interviewTitle;
    public $interviewer1;
    public $interviewer2;
    public $btnAddInterviewer;
    public $jobInterviewDate;
    public $jobInterviewTime;
    public $config;
    public $btnCancel;
    public $calBtnJobInterviewDate;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->interviewTitle = "jobInterview_name";
        $this->interviewer1 = "jobInterview_interviewer_1";
        $this->interviewer2 = "jobInterview_interviewer_2";
        $this->btnAddInterviewer = "addButton";
        $this->jobInterviewDate = "jobInterview_date";
        $this->jobInterviewTime = "jobInterview_time";
        $this->txtNotes = "jobInterview[note]";
        $this->btnSave = "saveBtn";
        $this->btnCancel = "cancelButton";
        $this->calBtnJobInterviewDate = "//input[@id='jobInterview_date_Button']";
        
    }

    public function addInterviewDeatils($interviewTitle, $interviewer1, $date, $time=null, $notes=null) {
        $this->selenium->type($this->interviewTitle, $interviewTitle);
        $this->selenium->type($this->interviewer1, $interviewer1);
        $this->selenium->type($this->jobInterviewDate, $date);
        //Calender::selectDateUsingCalendar($this->selenium, $this->calBtnJobInterviewDate, $date);
        // $this->selenium->type($this->interviewer2, $interviewer2);
        $this->selenium->type($this->jobInterviewTime, $time);
        $this->selenium->type($this->txtNotes, $notes);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());

        /* if ($this->selenium->isElementPresent("//label[@class='error']")) {
          return $this;
          } else {
          $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
          //echo "successfully saved";
          return new AddCandidate($this->selenium);
          }
         * 
         */


        return new AddCandidate($this->selenium);
    }

    public function clickBackBtn() {
        $this->selenium->click($this->btnCancel);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

}