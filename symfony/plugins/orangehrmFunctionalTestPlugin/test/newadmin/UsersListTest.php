<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserListTest
 *
 * @author chinthani
 */
class UsersListTest extends FunctionalTestcase {
    
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
    
        public function testSearchCombination(){
            Helper::loginUser($this, "admin", "admin");
            $Users = new UsersListPage($this);
            $Users = Menu::goToUsers($this);
            $searchUser = new UsersListPage($this);
            $searchTesterYML = sfYaml::load(sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/newadmin/testdata/UsersList.yml");
            $criteria = $searchTesterYML["SearchCriteria"];
            $results = $searchTesterYML["Results"];
            foreach($criteria as $criterion){
            $searchUser->searchBy($criterion["Username"], $criterion["UserType"],$criterion["EmployeeName"],$criterion["Status"] );
            $expected[0] = $results[$criterion["TestName"]];
            $this->assertTrue($searchUser->getUsersList()->isRecordsPresentInList($expected, true));
            }
            Helper::logOutIfLoggedIn($this);
        }
     public function testSortByUsername() {
        Helper::loginUser($this, "admin ", "admin");
        $Users = new UsersListPage($this);
        $Users = Menu::goToUsers($this);
        $SystemUsers = new UsersListPage($this);
        $SystemUsers->sortByFieldName("Username");
        $usrlist = array("admin", "ashan", "chuck", "kamal", "samanf", "samank" );
        $this->assertTrue($SystemUsers->list->verifySortingOrder($usrlist, "Username"));
        $SystemUsers->sortByFieldName("Username");
        $usrlist = array("samank", "samanf","kamal","chuck", "ashan" , "admin" );
        $this->assertTrue($SystemUsers->list->verifySortingOrder($usrlist, "Username"));
        Helper::logOutIfLoggedIn($this);
    }
   public function testSortByUserType() {
        Helper::loginUser($this, "admin ", "admin");
        $Users = new UsersListPage($this);
        $Users = Menu::goToUsers($this);
        $SystemUsers = new UsersListPage($this);
        $SystemUsers->sortByFieldName("User Type");
        $usrlist = array("admin", "samank", "ashan", "kamal", "chuck" ,"samanf" );
        $this->assertTrue($SystemUsers->list->verifySortingOrder($usrlist, "Username"));
        $SystemUsers->sortByFieldName("User Type");
        $usrlist = array("samanf", "chuck", "kamal", "ashan", "samank" , "admin");
        $this->assertTrue($SystemUsers->list->verifySortingOrder($usrlist, "Username"));
     

 }
      public function testDeleteUserAndVerify() {
        $SystemUsers = Helper::loginUser($this, "admin", "admin");
        $Users = new UsersListPage($this);
        $Users = Menu::goToUsers($this);
        $SystemUsers->list->select("Username", "ashan");
        $SystemUsers->clickDelete();
        //verify sucessfully deleted message
        $usrlist = array("admin", "chuck", "kamal", "samanf", "samank");
        $this->assertTrue($SystemUsers->list->isOnlyItemsListed($usrlist, "Username"));
        Helper::logOutIfLoggedIn($this);
    }
    
     public function testCancelDeleteUserAndVerify() {
        $SystemUsers = Helper::loginUser($this, "admin", "admin");
        $Users = new UsersListPage($this);
        $Users = Menu::goToUsers($this);
        $SystemUsers->list->select("Username", "ashan");
        $SystemUsers->clickCancelDelete();
        $usrlist = array("admin", "ashan", "chuck", "kamal", "samanf", "samank");
        $this->assertTrue($SystemUsers->list->isOnlyItemsListed($usrlist, "Username"));
        Helper::logOutIfLoggedIn($this);
    }
    
     public function testAddUser() {
        Helper::loginUser($this, "admin", "admin");
        $Users = new UsersListPage($this);
        $Users = Menu::goToUsers($this);
        $Users->goToAddUser();
        $SystemUser = new AddUsersPage($this);
        $SystemUser->addUser("Admin", "Kamal Harsha Silva", "harsha", "Enabled", "harsha", "harsha");
        $employeeInformation = new UsersListPage($this);
        //verify sucessfully saved message
        //$employeeInformation->goToNewEmployeeList() ;
        $expected[] = array("Username" => "harsha", "User Type" => "Admin","Employee Name" => "Kamal Harsha Silva" ,"Status" => "Enabled" );
        $this->assertTrue($employeeInformation->getUsersList()->isRecordsPresentInList($expected, FALSE));
        Helper::logOutIfLoggedIn($this);
    }
    
        public function testSearchUserbyInvalidUsername(){
        Helper::loginUser($this, "admin", "admin");
        $Users = new UsersListPage($this);
        $Users = Menu::goToUsers($this);
        $SerchUser = new UsersListPage($this);;
        $SerchUser->searchBy("12345", NULL, NULL, NULL); 
        $this->assertEquals($SerchUser->getStatusMessage(), "No Records Found");
        Helper::logOutIfLoggedIn($this);
     
    }   
    
    public function testSearchAndReset(){
        Helper::loginUser($this, "admin", "admin");
        $Users = new UsersListPage($this);
        $Users = Menu::goToUsers($this);
        $Users->searchAndReset("ashan", NULL, NULL, NULL );
        $usrlist = array("admin",  "ashan", "chuck" ,"kamal", "samanf", "samank");
        $this->assertTrue($Users->getUsersList()->isOnlyItemsListed($usrlist, "Username"));
        Helper::logOutIfLoggedIn($this);   
        
    }
    
    
     public function testDeleteAllUser() {
        Helper::loginUser($this, "admin", "admin");
        $Users = new UsersListPage($this);
        $Users = Menu::goToUsers($this);
        $Users->deleteAllUsers();
        $userlist = array("admin");
        $this->assertTrue($Users->list->isOnlyItemsListed($userlist, "Username"));
        Helper::logOutIfLoggedIn($this);
    
    }
            public function testAddandCancelUsers() {
            Helper::loginUser($this, "admin", "admin");
            $Users = new UsersListPage($this);
            $Users = Menu::goToUsers($this);
            $Users->goToAddUser();
            $AddUser = new AddUsersPage($this);
            $AddUser->cancelAddUser("Admin", "Kamal Harsha Silva", "harsha", "Enabled", "harsha", "harsha");
            $userlist = array("harsha");
            $this->assertFalse($Users->list->isOnlyItemsListed($userlist, "Username"));
            Helper::logOutIfLoggedIn($this);
    
    } 
    
    
    
    
    
    
    
    
    
    
    
    
}

?>
