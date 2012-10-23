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
            $addMembership = new MembershipsPage($this);
            $addMembership->addMembership("SSC");
            $this->assertTrue($addMembership->list->isItemPresentInColumn("Membership", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
        public function testAddSpecialMembership() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToMemberships($this);
            $addMembership = new MembershipsPage($this);
            $addMembership->addMembership("SSC@colombo,(01)");
            $this->assertTrue($addMembership->list->isItemPresentInColumn("Membership", "SSC@colombo,(01)"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
            public function testAddUnicodeMembership() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToMemberships($this);
            $addMembership = new MembershipsPage($this);
            $addMembership->addMembership("ची चा ना");
            $this->assertTrue($addMembership->list->isItemPresentInColumn("Membership", "ची चा ना"));
            Helper::logOutIfLoggedIn($this);
    
    }
     public function testEditMembership() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToMemberships($this);
            $viewMembership = new MembershipsPage($this);
            $viewMembership->list->clickOnTheItem("Membership", "Lions Club");
            $viewMembership->editMembership("SSC");
            $this->assertTrue($viewMembership->list->isItemPresentInColumn("Membership", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    }
           
     public function testDeleteMembership() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToMemberships($this);
            $viewMembership = new MembershipsPage($this);
            $viewMembership->list->select("Membership", "Lions Club");
            $viewMembership->clickDelete();
            $this->assertFalse($viewMembership->list->isItemPresentInColumn("Membership", "Lions Club"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
    public function testDeleteAllMembership() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToMemberships($this);
            $viewMembership = new MembershipsPage($this);
            $viewMembership->deleteAllMemeberships();
        $membershiplist = array("Abc", "Lions Club");
        $this->assertFalse($viewMembership->list->isOnlyItemsListed($membershiplist, "Membership"));
        Helper::logOutIfLoggedIn($this);
    
    }
    
     public function testCancelDeleteMembershipAndVerify() {
        Helper::loginUser($this, "admin", "admin");
        Menu::goToMemberships($this);
        $viewMembership = new MembershipsPage($this);
        $viewMembership->list->select("Membership", "Lions Club");
        $viewMembership->clickCancelDelete();
        $membershiplist = array("Abc", "Lions Club");
        $this->assertTrue($viewMembership->list->isOnlyItemsListed($membershiplist, "Membership"));
        Helper::logOutIfLoggedIn($this);
    }   
    
                 
   }

?>
