<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JobCategoriesTest
 *
 * @author chinthani
 */
class JobCategoriesTest extends FunctionalTestcase {
    

     public function setUp() {
        $prerequisites = new NewAdminPrerequisiteHandler();
        $prerequisites->ensurePrerequisites("NewAdminPrerequisites.yml");
        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());
               
    }
    public function testAddJobCategories() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_JobCategories($this);
            $addJobCategories = new JobCategoriesPage($this);
            $addJobCategories->addJobCategories("SSC");
            $this->assertTrue($addJobCategories->list->isItemPresentInColumn("Job Category", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    }  
    
        public function testAddSpecialJobCategories() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_JobCategories($this);
            $addJobCategories = new JobCategoriesPage($this);
            $addJobCategories->addJobCategories("S!@J*&hs-(01)");
            $this->assertTrue($addJobCategories->list->isItemPresentInColumn("Job Category", "S!@J*&hs-(01)"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
        public function testAddUnicodeJobCategories() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_JobCategories($this);
            $addJobCategories = new JobCategoriesPage($this);
            $addJobCategories->addJobCategories("あごの猫");
            $this->assertTrue($addJobCategories->list->isItemPresentInColumn("Job Category", "あごの猫"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
    public function testEditJobCategories() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_JobCategories($this);
            $viewJobCategories = new JobCategoriesPage($this);
            $viewJobCategories->list->clickOnTheItem("Job Category", "Operatives");
            $viewJobCategories->editJobCategories("Administration");
            $this->assertTrue($viewJobCategories->list->isItemPresentInColumn("Job Category", "Administration"));
            Helper::logOutIfLoggedIn($this);
    
    }
    public function testDeleteJobCategories() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_JobCategories($this);
            $viewJobCategories = new JobCategoriesPage($this);
            $viewJobCategories->list->select("Job Category", "Craft Workers");
            $viewJobCategories->clickDelete();
            $this->assertFalse($viewJobCategories->list->isItemPresentInColumn("Job Category", "Craft Workers"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
    public function testDeleteAllJobCategories() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_JobCategories($this);
            $viewJobCategories = new JobCategoriesPage($this);
            $viewJobCategories->deleteAllJobCategories();
            $jobCategoriesist = array("Craft Workers", "Laborers and Helpers", "Office and Clerical Workers", "Officials and Managers", "Operatives", "Professionals");
            $this->assertFalse($viewJobCategories->list->isOnlyItemsListed($jobCategoriesist, "Job Category"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
     public function testCancelDeleteJobCategoriesAndVerify() {
        Helper::loginUser($this, "admin", "admin");
        Menu::goToJob_JobCategories($this);
        $viewJobCategories = new JobCategoriesPage($this);
        $viewJobCategories->list->select("Job Category", "Professionals");
        $viewJobCategories->clickCancelDelete();
        $jobCategoriesist = array("Craft Workers", "Laborers and Helpers", "Office and Clerical Workers", "Officials and Managers", "Operatives", "Professionals");
        $this->assertTrue($viewJobCategories->list->isOnlyItemsListed($jobCategoriesist, "Job Category"));
        Helper::logOutIfLoggedIn($this);
    } 
    
    
//    public function testAddJobCategoriesWithoutData() {
//            Helper::loginUser($this, "admin", "admin");
//            Menu::goToJob_JobCategories($this);
//            $addJobCategories = new JobCategoriesPage($this);
//            echo '1';
//            $addJobCategories->addJobCategoriesWithOutData();
//             echo '2';
//            $this->assertEquals($addJobCategories->getValidationMessage(), "Required");
//             echo '3';
//            Helper::logOutIfLoggedIn($this);
//    
//   }  
    
    
    
    
    
    
    
}

?>
