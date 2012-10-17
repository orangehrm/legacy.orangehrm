<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Candidate
 *
 * @author chamila
 */
class Candidate extends Page {

    public $Candidate;
    public $CandidateHistory;

    public function __construct($selenium) {
        parent::__construct($selenium);

        $this->Candidate = new AddCandidate();
    }

}