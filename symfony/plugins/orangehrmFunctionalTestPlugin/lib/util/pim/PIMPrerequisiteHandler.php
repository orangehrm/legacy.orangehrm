<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeavePrerequisiteHandler
 *
 * @author madusani
 */
class PIMPrerequisiteHandler {

    public static $fixture;
    public static $fixturePath;

    public static function restorePrerequisites($prerequisiteFixturePath) {

        self::$fixturePath = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/pim/testdata/" . $prerequisiteFixturePath;
        self::$fixture = sfYaml::load(self::$fixturePath);
        self::ensurePrerequisites();
    }

    public static function ensurePrerequisites() {
    Helper::deleteAllFromTable("hs_hr_emp_picture");
    //Helper::deleteAllFromTable($tableName)
        //$externalPrerequisites = new ExternalDependencyHandler();
        // $externalPrerequisites->ensureDependencies("pim");


        TestDataService::populate(self::$fixturePath);
    }

}