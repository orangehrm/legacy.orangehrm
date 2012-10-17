<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TimePrerequisiteHandler
 *
 * @author Faris
 */
class TimePrerequisiteHandler {

    public function __construct($fixturePath) {

        $fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmFunctionalTestPlugin/test/time/testdata/TimePrerequisites.yml';
        $this->ensurePrerequisites($fixture);
    }

    public function ensurePrerequisites($fixturePath) {
        $externalPrerequisites = new ExternalDependencyHandler();
        $externalPrerequisites->ensureDependencies("time");
        TestDataService::populate($fixturePath);
    }

}