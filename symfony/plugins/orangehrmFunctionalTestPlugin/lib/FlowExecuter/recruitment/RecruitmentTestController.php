<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RecruitmentTestController
 *
 * @author irshad
 */
class RecruitmentTestController extends TestController {

    private $candidatePrequisites;
    private $vacancyPrerequisites;
    private $flowData;

    public function __construct($selenium, $fixture, $section) {
        $this->candidatePrequisites = new CandidatePrerequisiteHandler($selenium);
        $this->vacancyPrerequisites = new VacancyPrerequisiteHandler($selenium);

        $this->flowData = sfYaml::load($fixture);
        $this->flowData = $this->flowData[$section];
        if ($this->flowData["prerequisite"]) {
            $this->ensurePrerequisites($this->flowData["prerequisite"]);
        }
        $this->substituteWithPrerequisiteDetails();

        $flowMapper = new RecruitmentFlowMapper($selenium);
        parent::__construct($selenium, $this->flowData, $flowMapper);
    }

    private function ensurePrerequisites($prerequisiteArray) {
        foreach ($prerequisiteArray["vacancy"] as $vacancyNeeded) {
            $this->vacancyPrerequisites->ensurePrerequisites(array($vacancyNeeded));
        }

        foreach ($prerequisiteArray["candidate"] as $candidateNeeded) {
            $this->candidatePrequisites->ensurePrerequisites(array($candidateNeeded));
        }
    }

    private function substituteWithPrerequisiteDetails() {
        foreach ($this->flowData as $key => $value) {

            $prerequisiteIndex = $this->getPrerequisiteIndex($value);
            if ($prerequisiteIndex) {
                $detail = $this->getPrerequisiteDetails($prerequisiteIndex);
                $this->flowData[$key] = array_merge($detail, $this->flowData[$key]);
            }
        }
        // print_r($this->flowData);
    }

    private function getPrerequisiteIndex($array) {

        $keys = array_keys($array);
        foreach ($keys as $key) {
            if (substr($key, 0, 13) == "prerequisite_") {
                //echo "pattern identified" . $key;
                return $key;
            }
        }
    }

    private function getPrerequisiteDetails($prerequisiteIndex) {
        $exploded = explode("_", $prerequisiteIndex);
        $prerequisite = $this->flowData[$exploded[0]][$exploded[1]][$exploded[2]];
        // print_r($prerequisite);
        if ($exploded[1] == "vacancy") {
            return $this->vacancyPrerequisites->extractPrerequisiteRecordFromYML($prerequisite);
        } elseif ($exploded[1] == "candidate") {
            return $this->candidatePrequisites->extractPrerequisiteRecordFromYML($prerequisite);
        } else {
            echo "wrong prerequisite index name: " . $prerequisiteIndex;
            return null;
        }
    }

}