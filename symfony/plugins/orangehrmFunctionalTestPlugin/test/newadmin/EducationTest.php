<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EducationTest
 *
 * @author chinthani
 */
class EducationTest extends FunctionalTestcase {
    

     public function setUp() {
        $prerequisites = new NewAdminPrerequisiteHandler();
        $prerequisites->ensurePrerequisites("NewAdminPrerequisites.yml");
        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());
               
    }
    public function testAddEducation() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Education($this);
            $addEducation = new EducationPage($this);
            $addEducation->addEducation("SSC");
            $this->assertTrue($addEducation->list->isItemPresentInColumn("Level", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    }  
    public function testAddSpecialEducation() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Education($this);
            $addEducation = new EducationPage($this);
            $addEducation->addEducation("S!@J*&hs-(01)");
            $this->assertTrue($addEducation->list->isItemPresentInColumn("Level", "S!@J*&hs-(01)"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
        public function testAddUnicodeEducation() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Education($this);
            $addEducation = new EducationPage($this);
            $addEducation->addEducation("あごの猫");
            $this->assertTrue($addEducation->list->isItemPresentInColumn("Level", "あごの猫"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
    public function testEditEducation() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Education($this);
            $viewEducation = new EducationPage($this);
            $viewEducation->list->clickOnTheItem("Level", "BICT");
            $viewEducation->editEducation("IELTS");
            $this->assertTrue($viewEducation->list->isItemPresentInColumn("Level", "IELTS"));
            Helper::logOutIfLoggedIn($this);
    
    }
    public function testDeleteEducation() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Education($this);
            $viewEducation = new EducationPage($this);
            $viewEducation->list->select("Level", "BICT");
            $viewEducation->clickDelete();
            $this->assertFalse($viewEducation->list->isItemPresentInColumn("Level", "BICT"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
    public function testDeleteAllEducation() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Education($this);
            $viewEducation = new EducationPage($this);
            $viewEducation->deleteAllEducation();
            $Educationlist = array("BICT", "BIT");
            $this->assertFalse($viewEducation->list->isOnlyItemsListed($Educationlist, "Level"));
            $this->assertEquals($viewEducation->getStatusMessage(), "No Records Found");
            Helper::logOutIfLoggedIn($this);
    
    }
    
//     public function testCancelDeleteEducationAndVerify() {
//        Helper::loginUser($this, "admin", "admin");
//        Menu::goToQualification_Education($this);
//        $viewEducation = new EducationPage($this);
//        $viewEducation->list->select("Level", "BICT");
//        $viewEducation->clickCancelDelete();
//        $Educationlist = array("BICT", "BIT");
//        $this->assertTrue($viewEducation->list->isOnlyItemsListed($Educationlist, "Level"));
//        Helper::logOutIfLoggedIn($this);
//    } 

}

?>
