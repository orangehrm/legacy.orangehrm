<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MembershipsTest
 *
 * @author chinthani
 */
class MembershipsTest extends FunctionalTestcase {
    
          public static function setUpBeforeClass() {
       // $prerequisites = new NewPimPrerequisiteHandler();
       // $prerequisites->ensurePrerequisites("NewPIMPrerequisites.yml");
      
     }
     public function setUp() {
       $prerequisites = new NewAdminPrerequisiteHandler();
       $prerequisites->ensurePrerequisites("NewAdminPrerequisites.yml");
        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());
               
    }
    public function testAddMembership() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToMemberships($this);
            $Membership = new MembershipsPage($this);
            $Membership->addMembership("SSC");
            Helper::logOutIfLoggedIn($this);
    
    }
    
        
   }

?>
