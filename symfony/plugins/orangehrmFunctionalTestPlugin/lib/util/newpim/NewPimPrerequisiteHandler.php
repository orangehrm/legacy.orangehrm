<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PimPrerequisiteHandler
 *
 * @author chinthani
 */
class NewPimPrerequisiteHandler {
 
    
        public function ensurePrerequisites($prerequisiteFixturePath) {

        $fixturePath = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/newpim/testdata/" . $prerequisiteFixturePath;
        
        TestDataService::populate($fixturePath);
        
    } 
    
    
    
    
    
}

?>
