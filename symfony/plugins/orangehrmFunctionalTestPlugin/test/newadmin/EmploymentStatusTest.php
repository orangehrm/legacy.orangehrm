<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmploymentStatusTest
 *
 * @author chinthani
 */
class EmploymentStatusTest extends FunctionalTestcase {
    

     public function setUp() {
        $prerequisites = new NewAdminPrerequisiteHandler();
        $prerequisites->ensurePrerequisites("NewAdminPrerequisites.yml");
        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());
               
    }
    public function testAddEmploymentStatus() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_EmploymentStatus($this);
            $addEmploymentStatus = new EmploymentStatusPage($this);
            $addEmploymentStatus->addEmploymentStatus("SSC");
            $this->assertTrue($addEmploymentStatus->list->isItemPresentInColumn("Employment Status", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    }  
    
      public function testAddSpecialEmploymentStatus() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_EmploymentStatus($this);
            $ViewEmploymentStatus = new EmploymentStatusPage($this);
            $ViewEmploymentStatus->addEmploymentStatus("SSC@colombo,(01)");
            $this->assertTrue($ViewEmploymentStatus->list->isItemPresentInColumn("Employment Status", "SSC@colombo,(01)"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
            public function testAddUnicodeEmploymentStatus() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_EmploymentStatus($this);
            $ViewEmploymentStatus = new EmploymentStatusPage($this);
            $ViewEmploymentStatus->addEmploymentStatus("ची चा ना");
            $this->assertTrue($ViewEmploymentStatus->list->isItemPresentInColumn("Employment Status", "ची चा ना"));
            Helper::logOutIfLoggedIn($this);
                
    }
    
    
         public function testEditEmploymentStatus() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_EmploymentStatus($this);
            $ViewEmploymentStatus = new EmploymentStatusPage($this);
            $ViewEmploymentStatus->list->clickOnTheItem("Employment Status", "Full Time Contract");
            $ViewEmploymentStatus->editEmploymentStatus("Temporary");
            $this->assertTrue($ViewEmploymentStatus->list->isItemPresentInColumn("Employment Status", "Temporary"));
            Helper::logOutIfLoggedIn($this);
    
    }
    public function testDeleteEmploymentStatus() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_EmploymentStatus($this);
            $ViewEmploymentStatus = new EmploymentStatusPage($this);
            $ViewEmploymentStatus->list->select("Employment Status", "Part Time Permanent");
            $ViewEmploymentStatus->clickDelete();
            $this->assertFalse($ViewEmploymentStatus->list->isItemPresentInColumn("Employment Status", "Part Time Permanent"));
            Helper::logOutIfLoggedIn($this);
    
    }
    
    public function testDeleteAllEmploymentStatus() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_EmploymentStatus($this);
            $ViewEmploymentStatus = new EmploymentStatusPage($this);
            $ViewEmploymentStatus->deleteAllEmploymentStatus();
            $employmentStatuslist = array("Full Time Contract", "Full Time Internship", "Full Time Permanent", "Part Time Contract", "Part Time Internship", "Part Time Permanent", "Terminated");
            $this->assertFalse($ViewEmploymentStatus->list->isOnlyItemsListed($employmentStatuslist, "Employment Status"));
            $this->assertEquals($ViewEmploymentStatus->getStatusMessage(), "No Records Found");
            Helper::logOutIfLoggedIn($this);
    
    }
    
     public function testCancelDeleteEmploymentStatusAndVerify() {
        Helper::loginUser($this, "admin", "admin");
        Menu::goToJob_EmploymentStatus($this);
        $ViewEmploymentStatus = new EmploymentStatusPage($this);
        $ViewEmploymentStatus->list->select("Employment Status", "Full Time Internship");
        $ViewEmploymentStatus->clickCancelDelete();
        $employmentStatuslist = array("Full Time Contract", "Full Time Internship", "Full Time Permanent", "Part Time Contract", "Part Time Internship", "Part Time Permanent", "Terminated");
        $this->assertTrue($ViewEmploymentStatus->list->isOnlyItemsListed($employmentStatuslist, "Employment Status"));
        Helper::logOutIfLoggedIn($this);
    }   
   
        public function testAddEmploymentStatusWithoutData() {
            Helper::loginUser($this, "admin", "admin");
            Menu::goToJob_EmploymentStatus($this);
            $ViewEmploymentStatus = new EmploymentStatusPage($this);
            echo '1';
            $ViewEmploymentStatus->addEmploymentStatusWithOutData();
             echo '2';
            $this->assertEquals($ViewEmploymentStatus->getValidationMessage(), "Required");
            $employmentStatuslist = array("Full Time Contract", "Full Time Internship", "Full Time Permanent", "Part Time Contract", "Part Time Internship", "Part Time Permanent", "Terminated");
            $this->assertTrue($ViewEmploymentStatus->list->isOnlyItemsListed($employmentStatuslist, "Employment Status"));
             echo '3';
            Helper::logOutIfLoggedIn($this);
    
   }
   
           public function testAddandCancelEmploymentStatus() {
           Helper::loginUser($this, "admin", "admin");
           Menu::goToJob_EmploymentStatus($this);
           $ViewEmploymentStatus = new EmploymentStatusPage($this);
            $ViewEmploymentStatus->addCancelEmploymentStatus("SSC");
            $this->assertFalse($ViewEmploymentStatus->list->isItemPresentInColumn("Employment Status", "SSC"));
            Helper::logOutIfLoggedIn($this);
    
    } 
   
}

?>
