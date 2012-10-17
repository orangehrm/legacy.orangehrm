<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewAdminPrerequisiteHandler
 *
 * @author chinthani
 */
class NewAdminPrerequisiteHandler {
        
    public function ensurePrerequisites($prerequisiteFixturePath) {

        $fixturePath = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/newadmin/testdata/" . $prerequisiteFixturePath;
        TestDataService::populate($fixturePath);
    } 
    
    
}

?>
