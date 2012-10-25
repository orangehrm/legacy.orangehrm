<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SkillsTest
 *
 * @author chinthani
 */
class SkillsTest extends FunctionalTestcase {
    

      public function setUp() {
        $prerequisites = new NewAdminPrerequisiteHandler();
        $prerequisites->ensurePrerequisites("NewAdminPrerequisites.yml");
        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());
               
    }
    public function testAddSkills() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Skills($this);
            $addSkills = new SkillsPage($this);
            $addSkills->addSkills("Presentation Skill", "Doing presentations to customers");
            $expected[] = array("Name" => "Presentation Skill", "Description" => "Doing presentations to customers" );
            $this->assertTrue($addSkills->getSkillsList()->isRecordsPresentInList($expected, FALSE));        
            Helper::logOutIfLoggedIn($this);
    
    }  
    public function testAddandCancelSkills() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Skills($this);
            $addSkills = new SkillsPage($this);
            $addSkills->addCancelSkills("Report Writing", "Report formats");
            $skillslist = array("AI Programming", "Management" );
            $this->assertTrue($addSkills->getSkillsList()->isOnlyItemsListed($skillslist, "Name"));
            Helper::logOutIfLoggedIn($this);
    
    }  
    
    public function testAddSpecialSkills() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Skills($this);
            $addSkills = new SkillsPage($this);
            $addSkills->addSkills("S!@J*&hs-(01)", "#$%^&*(-]");
            $this->assertTrue($addSkills->list->isItemPresentInColumn("Name", "S!@J*&hs-(01)"));
            $this->assertTrue($addSkills->list->isItemPresentInColumn("Description", "#$%^&*(-]"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
        public function testAddUnicodeSkills() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Skills($this);
            $addSkills = new SkillsPage($this);
            $addSkills->addSkills("अपरिभाषित", "未定義");
            $this->assertTrue($addSkills->list->isItemPresentInColumn("Name", "अपरिभाषित"));
            $this->assertTrue($addSkills->list->isItemPresentInColumn("Description", "未定義"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
    public function testEditSkills() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Skills($this);
            $viewSkills = new SkillsPage($this);
            $viewSkills->list->clickOnTheItem("Name", "Management");
            $viewSkills->editSkills("Communication", "Presentaion");
            $this->assertTrue($viewSkills->list->isItemPresentInColumn("Name", "Communication"));
            $this->assertTrue($viewSkills->list->isItemPresentInColumn("Description", "Presentaion"));
            Helper::logOutIfLoggedIn($this);
    
    }
    public function testDeleteSkills() {
             Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Skills($this);
            $viewSkills = new SkillsPage($this);
            $viewSkills->list->select("Name", "Management");
            $viewSkills->clickDelete();
            $this->assertFalse($viewSkills->list->isItemPresentInColumn("Name", "Management"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
    public function testDeleteAllSkills() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Skills($this);
            $viewSkills = new SkillsPage($this);
            $viewSkills->deleteAllSkills();
            $Skillslistname = array("AI Programming", "Management");
            $this->assertFalse($viewSkills->list->isOnlyItemsListed($Skillslistname, "Name"));
            $Skillslistdescription = array("AI Programming", "Management Skills");
            $this->assertFalse($viewSkills->list->isOnlyItemsListed($Skillslistdescription, "Description"));
            $this->assertEquals($viewSkills->getStatusMessage(), "No Records Found");
            Helper::logOutIfLoggedIn($this);
    
    }
    
//     public function testCancelDeleteSkillsAndVerify() {
//        Helper::loginUser($this, "admin", "admin");
//        Menu::goToQualification_Skills($this);
//        $viewSkills = new SkillsPage($this);
//        $viewSkills->list->select("Level", "BICT");
//        $viewSkills->clickCancelDelete();
//        $Skillslist = array("BICT", "BIT");
//        $this->assertTrue($viewSkills->list->isOnlyItemsListed($Skillslist, "Level"));
//        Helper::logOutIfLoggedIn($this);
//    } 
    
            public function testAddSkillsWithoutData() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToQualification_Skills($this);
            $viewSkills = new SkillsPage($this);
            $viewSkills->addSkillsWithOutData();
            $this->assertEquals($viewSkills->getValidationMessage(), "Required");
            $Skillslist = array("AI Programming", "Management");
            $this->assertTrue($viewSkills->list->isOnlyItemsListed($Skillslist, "Name"));
            Helper::logOutIfLoggedIn($this);
       }
       
}

?>
