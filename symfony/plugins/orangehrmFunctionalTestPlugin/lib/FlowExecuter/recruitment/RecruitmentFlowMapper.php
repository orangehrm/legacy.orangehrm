<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RecruitmentFlowMapper
 *
 * @author Faris
 */
class RecruitmentFlowMapper {

    private $selenium;

    public function __construct($selenuim) {
        $this->selenium = $selenuim;
    }

    public function getFlowObject($string) {

        switch ($string) {
            case 'AddJobVacancy':
                $addJobVacancy = new AddVacancyFlow($this->selenium);
                return $addJobVacancy;
            case 'AddCandidate':
                $addCandidate = new AddCandidateFlow($this->selenium);
                return $addCandidate;
            case 'Shortlist':
                $shortlist = new ShortListFlow($this->selenium);
                return $shortlist;
            case 'Schedule Interview':
                $interview = new InterviewFlow($this->selenium);
                return $interview;
            case 'InterviewStatus':
                $interviewStatus = new InterviewStatusFlow($this->selenium);
                return $interviewStatus;
            case 'Offer Job':
                $offerJob = new OfferJobFlow($this->selenium);
                return $offerJob;
            case 'Hire':
                $hireCandidate = new HireCandidateFlow($this->selenium);
                return $hireCandidate;
            case 'LogIn':
                $LogIn = new LogInFlow($this->selenium);
                return $LogIn;
            case 'LogOut':
                $LogOut = new LogOutFlow($this->selenium);
                return $LogOut;
            case 'Decline Offer':
                $offerDeclined = new OfferDeclinedFlow($this->selenium);
                return $offerDeclined;
            case 'Reject':
                $reject = new RejectFlow($this->selenium);
                return $reject;
            case 'DeleteCandidate':
                return new DeleteCandidateFlow($this->selenium);
        }
    }

}