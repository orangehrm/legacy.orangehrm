<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PrerequisiteHandler
 *
 * @author irshad
 */
abstract class PrerequisiteHandler {

    protected $moduleName;

    public function __construct($moduleName, $loadedPrerequisitesArray) {
        $this->loadedPrerequisitesArray = $loadedPrerequisitesArray;
        $this->moduleName = $moduleName;
    }

    public function ensurePrerequisites($arrayOfPrerequisiteIDs) {
        $isLoggedIn = false;

        foreach ($arrayOfPrerequisiteIDs as $prerequisiteID) {
            $prerequisiteRecord = $this->extractPrerequisiteRecordFromYML($prerequisiteID);

            if (!$this->isPrerequisiteInDB($prerequisiteRecord)) {
                $this->ensureExternalDependencies($this->moduleName);
                $this->addPrerequisite($prerequisiteRecord);
            }else
                continue;
        }
    }

    abstract protected function isPrerequisiteInDB($prerequisiteRecord);

    abstract public function addPrerequisite($prerequisiteRecord);

    abstract public function deletePrerequisite($prerequisiteRecord);

    public function extractPrerequisiteRecordFromYML($prerequisiteID) {
        foreach ($this->loadedPrerequisitesArray["Prerequisites"] as $record) {
            if ($record["vacancyName"] == $prerequisiteID) {
                return $record;
            } else if ($record["firstName"] == $prerequisiteID) {
                return $record;
            }
        }
        return null;
    }

    private function ensureExternalDependencies() {
        $externalDependencyHandler = new ExternalDependencyHandler();
        $externalDependencyHandler->ensureDependencies($this->moduleName);
    }

}