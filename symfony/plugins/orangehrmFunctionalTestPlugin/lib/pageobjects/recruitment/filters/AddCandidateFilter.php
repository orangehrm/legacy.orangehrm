<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CandidateSearchFilter
 *
 * @author Faris
 */
class AddCandidateFilter {

    public $firstName;
    public $middleName;
    public $lastName;
    public $eMail;
    public $contactNo;
    public $jobVacancy;
    public $resume;
    public $keywords;
    public $comment;
    public $dateOfApplication;

    public function __construct($fName, $lName, $eMail) {

        $this->firstName = $fName;
        $this->lastName = $lName;
        $this->eMail = $eMail;
    }

    public function candidateOptionalPersonalFields($mName=null, $contactNo=null) {

        $this->middleName = $mName;
        $this->contactNo = $contactNo;
    }

    public function candidateOptionalOfficialFields($jobVacancy=null, $resume=null, $keywords=null, $comment=null, $dateOFApplication=null) {

        $this->jobVacancy = $jobVacancy;
        $this->resume = $resume;
        $this->keywords = $keywords;
        $this->comment = $comment;
        $this->dateOfApplication = $dateOFApplication;
    }

}