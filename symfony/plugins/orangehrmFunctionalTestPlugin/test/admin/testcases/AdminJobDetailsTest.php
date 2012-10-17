<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** TestCase Description
 *
 *
 * @author madusani
 */
class AdminJobDetailsTest extends FunctionalTestcase {

    public $testConfig;
    public $list;
    public $menu;
    public function setUp() {

        $helper = new Helper();
        $this->testConfig = new TestConfig();
        //$menu = new Menu();
       
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->testConfig->getBrowserURL());
        $externalPrerequisites = new ExternalDependencyHandler();
        $externalPrerequisites->ensureDependencies('admin');
        if (!isset($this->menu)) {
            $this->menu = new Menu();
        }
    }

    //Job Titles
    public function testAddJobTitle() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $viewJobTitles = Menu::goToJob_JobTitles($this);
        $addJobTitle = $viewJobTitles->goToAddJobTitle();
        $filePath = $this->testConfig->getAbsolutePath() . Helper::convertPathToCurrentPlatform("/admin/testdata/photos/images.jpeg");
        $addJobTitle->addJobTitle("Business Analyst", "Analyse the Business activities", $filePath, null, "Gain Knowledge about the business");
        $this->assertEquals("Job Title Added Successfully", $viewJobTitles->getSuccessfullMessage());
        $this->assertTrue($viewJobTitles->list->isItemPresentInColumn("Job Title", "Business Analyst"));
        Helper::logOutIfLoggedIn($this);
    }

    public function testEditJobTitle() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $viewJobTitles = Menu::goToJob_JobTitles($this);
        $viewJobTitles->list->clickOnTheItem("Job Title", "QA Engineer");
        //$editJobTitle = new AddJobTitlePageObject($this);
        $filePath = $this->testConfig->getAbsolutePath() . Helper::convertPathToCurrentPlatform("/admin/testdata/photos/image2.jpeg");
        $editJobTitle->editJobTitle("Associate QA Engineer", "Supporting the Main QA Engineer", $filePath, "Replace", "Should be able to work as a team");
        $this->assertEquals("Job Title Updated Successfully", $viewJobTitles->getSuccessfullMessage());
        $this->assertTrue($viewJobTitles->list->isItemPresentInColumn("Job Title", "Associate QA Engineer"));
        Helper::logOutIfLoggedIn($this);
    }

    public function testDeleteOneJobTitle() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $viewJobTitles = Menu::goToJob_JobTitles($this);
        $viewJobTitles->deleteJobTitle("QA Engineer");
        $this->assertEquals("Selected Job Title(s) Deleted Successfully", $viewJobTitles->getSuccessfullMessage());
        $this->assertFalse($viewJobTitles->list->isItemPresentInColumn("Job Title", "QA Engineer"));
        Helper::logOutIfLoggedIn($this);
    }

    public function testDeleteAllJobTitles() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $viewJobTitles = Menu::goToJob_JobTitles($this);
        $viewJobTitles->deleteAllJobTitles();
        $this->assertEquals("Selected Job Title(s) Deleted Successfully", $viewJobTitles->getSuccessfullMessage());
        Helper::logOutIfLoggedIn($this);
    }
    public function testDeleteAllJobTitles2() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
    }
   
     public function testAddJobCategory() {
       AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        $addminJob = Helper::loginUser($this, 'admin', 'admin');
        $jobcategory = new ViewJobTitlesPageObject($this);
        $viewJobTitles = Menu::goToJob_JobTitles($this);
        if($this->menu instanceof Menu){
            $menu = new Menu();
            $pageob=$menu->goToEmployeeAttendanceRecords123($this);
            $pageob->addJobTitle("ESS User", "All the out source projects are done by this persons");            
            $pageob->editJobTitle("ESS User", "This is the updated one","", "Nothing");
            //$this->click("ESS User");
        }
        $this->assertEquals("Successfully Saved",$pageob->getStatusMessage());
               
     }    
}