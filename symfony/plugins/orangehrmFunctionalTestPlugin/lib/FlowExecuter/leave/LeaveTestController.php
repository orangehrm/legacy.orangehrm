<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeaveTestController
 *
 * @author madusani
 */
class LeaveTestController extends TestController {

    private $flowData;
    private $leavePrerequisites;
    public $sharedData;
    public $recordInTheYML;

    public function __construct($selenium, $fixture, $section) {

        $this->flowData = sfYaml::load($fixture);
        $this->leavePrerequisites = new LeavePrerequisiteHandler($this->flowData["PrerequisiteDetails"]["fileName"]);

        $this->flowData = $this->flowData[$section];
        $this->substituteWithPrerequisiteDetails();


        $flowMapper = new LeaveFlowMapper($selenium);
        parent::__construct($selenium, $this->flowData, $flowMapper);
    }

    private function substituteWithPrerequisiteDetails() {
        foreach ($this->flowData as $key => $value) {

            $prerequisiteIndex = $this->getPrerequisiteIndex($value);
            if ($prerequisiteIndex) {
                $detail = $this->getPrerequisiteDetails($prerequisiteIndex);
                $this->flowData[$key] = array_merge($detail, $this->flowData[$key]);
            }
        }
    }

    private function getPrerequisiteIndex($array) {

        $keys = array_keys($array);
        foreach ($keys as $key) {
            if (substr($key, 0, 13) == "prerequisite_") {
                //echo "pattern identified" . $key;
                return $key;
            }
        }
        return null;
    }

    private function getPrerequisiteDetails($prerequisiteIndex) {
        $exploded = explode("_", $prerequisiteIndex);
        $prerequisite = $this->flowData[$exploded[0]][$exploded[1]][$exploded[2]];

        if ($exploded[1] == "LeaveRequest") {

            return $this->leavePrerequisites->getPrerequisiteDetailsIntoAMergedArray($prerequisite);
        }
    }

}