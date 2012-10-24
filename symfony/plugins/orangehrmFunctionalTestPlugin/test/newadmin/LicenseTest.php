<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LicenseTest
 *
 * @author chinthani
 */
class LicenseTest extends FunctionalTestcase {
    

     public function setUp() {
        $prerequisites = new NewAdminPrerequisiteHandler();
        $prerequisites->ensurePrerequisites("NewAdminPrerequisites.yml");
        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());
               
    }
    public function testAddLicenses() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Licenses($this);
            $addLicenses = new LicensesPage($this);
            $addLicenses->addLicenses("SSC");
            $this->assertTrue($addLicenses->list->isItemPresentInColumn("Name", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    }  
    public function testAddSpecialLicenses() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Licenses($this);
            $addLicenses = new LicensesPage($this);
            $addLicenses->addLicenses("S!@J*&hs-(01)");
            $this->assertTrue($addLicenses->list->isItemPresentInColumn("Name", "S!@J*&hs-(01)"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
        public function testAddUnicodeLicenses() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Licenses($this);
            $addLicenses = new LicensesPage($this);
            $addLicenses->addLicenses("あごの猫");
            $this->assertTrue($addLicenses->list->isItemPresentInColumn("Name", "あごの猫"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
    public function testEditLicenses() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Licenses($this);
            $viewLicenses = new LicensesPage($this);
            $viewLicenses->list->clickOnTheItem("Name", "BCS License");
            $viewLicenses->editLicenses("IELTS License");
            $this->assertTrue($viewLicenses->list->isItemPresentInColumn("Name", "IELTS License"));
            Helper::logOutIfLoggedIn($this);
    
    }
    public function testDeleteLicenses() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Licenses($this);
            $viewLicenses = new LicensesPage($this);
            $viewLicenses->list->select("Name", "BICT");
            $viewLicenses->clickDelete();
            $this->assertFalse($viewLicenses->list->isItemPresentInColumn("Name", "BICT"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
    public function testDeleteAllLicenses() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Licenses($this);
            $viewLicenses = new LicensesPage($this);
            $viewLicenses->deleteAllLicenses();
            $Licenseslist = array("BICT", "BIT");
            $this->assertFalse($viewLicenses->list->isOnlyItemsListed($Licenseslist, "Name"));
            $this->assertEquals($viewLicenses->getStatusMessage(), "No Records Found");
            Helper::logOutIfLoggedIn($this);
    
    }
    
        public function testAddandCancelLicenses() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Licenses($this);
            $viewLicenses = new LicensesPage($this);
            $viewLicenses->addCancelLicenses("SSC");
            $this->assertFalse($viewLicenses->list->isItemPresentInColumn("Name", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
//     public function testCancelDeleteLicensesAndVerify() {
//        Helper::loginUser($this, "admin", "admin");
//        Menu::goToQualification_Licenses($this);
//        $viewLicenses = new LicensesPage($this);
//        $viewLicenses->list->select("Name", "Professionals");
//        $viewLicenses->clickCancelDelete();
//        $Licenseslist = array("BICT", "BIT");
//        $this->assertTrue($viewLicenses->list->isOnlyItemsListed($Licenseslist, "Name"));
//        Helper::logOutIfLoggedIn($this);
//    } 
}

?>
