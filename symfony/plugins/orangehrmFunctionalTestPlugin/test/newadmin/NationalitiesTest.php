<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NationalitiesTest
 *
 * @author chinthani
 */
class NationalitiesTest extends FunctionalTestcase {
    
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
    public function testAddNationality() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToNationalities($this);
            $addNationality = new NationalitiesPage($this);
            $addNationality->addNationality("SSC");
            $this->assertTrue($addNationality->list->isItemPresentInColumn("Nationality", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    public function testAddSpecialNationality() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToNationalities($this);
            $ViewNationality = new NationalitiesPage($this);
            $ViewNationality->addNationality("SSC@colombo,(01)");
            $this->assertTrue($ViewNationality->list->isItemPresentInColumn("Nationality", "SSC@colombo,(01)"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
            public function testAddUnicodeNationality() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToNationalities($this);
            $ViewNationality = new NationalitiesPage($this);
            $ViewNationality->addNationality("ची चा ना");
            $this->assertTrue($ViewNationality->list->isItemPresentInColumn("Nationality", "ची चा ना"));
            Helper::logOutIfLoggedIn($this);
                
    }
    
         public function testEditNationality() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToNationalities($this);
            $ViewNationality = new NationalitiesPage($this);
            $ViewNationality->list->clickOnTheItem("Nationality", "Albanian");
            $ViewNationality->editNationality("SSC");
            $this->assertTrue($ViewNationality->list->isItemPresentInColumn("Nationality", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
     public function testDeleteNationality() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToNationalities($this);
            $ViewNationality = new NationalitiesPage($this);
            $ViewNationality->list->select("Nationality", "Albanian");
            $ViewNationality->clickDelete();
            $this->assertFalse($ViewNationality->list->isItemPresentInColumn("Nationality", "Albanian"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
    public function testDeleteAllNationality() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToNationalities($this);
            $ViewNationality = new NationalitiesPage($this);
            $ViewNationality->deleteAllNationality();
            $nationalitylist = array("Afghan", "Albanian", "Algerian", "American", "Andorran");
            $this->assertFalse($ViewNationality->list->isOnlyItemsListed($nationalitylist, "Nationality"));
            $this->assertEquals($ViewNationality->getStatusMessage(), "No Records Found");
            Helper::logOutIfLoggedIn($this);
    
    }
    
     public function testCancelDeleteNationalityAndVerify() {
        Helper::loginUser($this, "admin", "admin");
        Menu::goToNationalities($this);
        $ViewNationality = new NationalitiesPage($this);
        $ViewNationality->list->select("Nationality", "Albanian");
        $ViewNationality->clickCancelDelete();
        $nationalitylist = array("Afghan", "Albanian", "Algerian", "American", "Andorran");
        $this->assertTrue($ViewNationality->list->isOnlyItemsListed($nationalitylist, "Nationality"));
        Helper::logOutIfLoggedIn($this);
    }   
            public function testAddandCancelNationality() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToNationalities($this);
            $ViewNationality = new NationalitiesPage($this);
            $ViewNationality->addCancelNationality("SSC");
            $this->assertFalse($ViewNationality->list->isItemPresentInColumn("Nationality", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    } 
        public function testAddNationalityWithoutData() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToNationalities($this);
            $addNationality = new NationalitiesPage($this);
            $addNationality->addNationalityWithOutData();
            $this->assertEquals($addNationality->getValidationMessage(), "Required");
            $nationalitylist = array("Afghan", "Albanian", "Algerian", "American", "Andorran");
            $this->assertTrue($addNationality->list->isOnlyItemsListed($nationalitylist, "Nationality"));
            Helper::logOutIfLoggedIn($this);     
   } 
   
   
        public function testDuplicateNationality() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToNationalities($this);
            $addNationality = new NationalitiesPage($this);
            $addNationality->addNationality("Afghan");
            $this->assertEquals($addNationality->getValidationMessage(), "Already exists");
            $nationalitylist = array("Afghan", "Albanian", "Algerian", "American", "Andorran");
            $this->assertTrue($addNationality->list->isOnlyItemsListed($nationalitylist, "Nationality"));
            Helper::logOutIfLoggedIn($this);
        }
    
    
    }

?>
