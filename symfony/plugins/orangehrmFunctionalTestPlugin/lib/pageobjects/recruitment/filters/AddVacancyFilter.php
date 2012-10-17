<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VacancySearchFilter
 *
 * @author Faris
 */
class AddVacancyFilter {

    public $jobTitle;
    public $vacancyName;
    public $hiringManager;
    public $noOfPositions;
    public $desc;
    public $active;

    public function __construct($jobTitle, $vacancyName, $hiringManager) {
        $this->jobTitle = $jobTitle;
        $this->vacancyName = $vacancyName;
        $this->hiringManager = $hiringManager;
    }

    public function vacancyOptionalFields($noOfPositions=null, $desc=null, $activeStatus=null) {

        $this->noOfPositions = $noOfPositions;
        $this->desc = $desc;
        $this->active = $activeStatus;
    }

}