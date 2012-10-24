<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanguageTest
 *
 * @author chinthani
 */
class LanguageTest extends FunctionalTestcase {
    

     public function setUp() {
        $prerequisites = new NewAdminPrerequisiteHandler();
        $prerequisites->ensurePrerequisites("NewAdminPrerequisites.yml");
        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());
               
    }
    public function testAddLanguage() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Language($this);
            $addLanguage = new LanguagePage($this);
            $addLanguage->addLanguage("German");
            $this->assertTrue($addLanguage->list->isItemPresentInColumn("Name", "German"));
            Helper::logOutIfLoggedIn($this);
    
    }  
    public function testAddSpecialLanguage() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Language($this);
            $addLanguage = new LanguagePage($this);
            $addLanguage->addLanguage("S!@J*&hs-(01)");
            $this->assertTrue($addLanguage->list->isItemPresentInColumn("Name", "S!@J*&hs-(01)"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
        public function testAddUnicodeLanguage() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Language($this);
            $addLanguage = new LanguagePage($this);
            $addLanguage->addLanguage("あごの猫");
            $this->assertTrue($addLanguage->list->isItemPresentInColumn("Name", "あごの猫"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
    public function testEditLanguage() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Language($this);
            $viewLanguage = new LanguagePage($this);
            $viewLanguage->list->clickOnTheItem("Name", "Sinhala");
            $viewLanguage->editLanguage("Japanees");
            $this->assertTrue($viewLanguage->list->isItemPresentInColumn("Name", "Japanees"));
            Helper::logOutIfLoggedIn($this);
    
    }
    public function testDeleteLanguage() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Language($this);
            $viewLanguage = new LanguagePage($this);
            $viewLanguage->list->select("Name", "Sinhala");
            $viewLanguage->clickDelete();
            $this->assertFalse($viewLanguage->list->isItemPresentInColumn("Name", "Sinhala"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
    public function testDeleteAllLanguage() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Language($this);
            $viewLanguage = new LanguagePage($this);
            $viewLanguage->deleteAllLanguage();
            $Languagelist = array("English", "Sinhala");
            $this->assertFalse($viewLanguage->list->isOnlyItemsListed($Languagelist, "Name"));
            $this->assertEquals($viewLanguage->getStatusMessage(), "No Records Found");
            Helper::logOutIfLoggedIn($this);
    
    }
    
        public function testAddandCancelLanguage() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Language($this);
            $viewLanguage = new LanguagePage($this);
            $viewLanguage->addCancelLanguage("ABC");
            $this->assertFalse($viewLanguage->list->isItemPresentInColumn("Name", "ABC"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
//     public function testCancelDeleteLanguageAndVerify() {
//       Helper::loginUser($this, "admin", "admin");
//        Menu::goToQualification_Language($this);
//        $viewLanguage = new LanguagePage($this);
//        $viewLanguage->list->select("Name", "Professionals");
//        $viewLanguage->clickCancelDelete();
//        $Languagelist = array("BICT", "BIT");
//        $this->assertTrue($viewLanguage->list->isOnlyItemsListed($Languagelist, "Name"));
//        Helper::logOutIfLoggedIn($this);
//    } 
    
            public function testAddLanguageWithoutData() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Language($this);
            $viewLanguage = new LanguagePage($this);
            $viewLanguage->addLanguageWithOutData();
            $this->assertEquals($viewLanguage->getValidationMessage(), "Required");
            $Languagelist = array("English", "Sinhala");
            $this->assertTrue($viewLanguage->list->isOnlyItemsListed($Languagelist, "Name"));
            Helper::logOutIfLoggedIn($this);
       }
    
    
}

?>
