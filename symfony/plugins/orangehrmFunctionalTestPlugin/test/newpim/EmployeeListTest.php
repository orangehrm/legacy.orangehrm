<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeListTest
 *
 * @author Chinthani
 */
class EmployeeListTest extends FunctionalTestcase{
    
     //public static function setUpBeforeClass() {
       // $prerequisites = new NewPimPrerequisiteHandler();
       // $prerequisites->ensurePrerequisites("NewPIMPrerequisites.yml");
      
     //}
    
    public function setUp() {
        $prerequisites = new NewPimPrerequisiteHandler();
        $prerequisites->ensurePrerequisites("NewPIMPrerequisites.yml");
        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());
       
        }
    
    
      public function testDeleteEmployeeAndVerify() {
        $employeeInformation = Helper::loginUser($this, "admin", "admin");
        $employeeInformation->list->select("First (& Middle) Name", "Saman Kumara");
        $employeeInformation->clickDelete();
        $emplist = array("Chuck Neel", "Ashan Kumara", "Pasindu Malin" ,"Saman Nishan");
        $this->assertTrue($employeeInformation->list->isOnlyItemsListed($emplist, "First (& Middle) Name"));
        //add to verify sucesful deleted msg.currently it is fading out. :symfony/apps/orangehrm/templates-> freshorange.php
        Helper::logOutIfLoggedIn($this);
    }
    
     public function testCancelDeleteEmployeeAndVerify() {
        $employeeInformation = Helper::loginUser($this, "admin", "admin");
        $employeeInformation->list->select("First (& Middle) Name", "Saman Kumara");
        $employeeInformation->clickCancelDelete();
        $emplist = array("Chuck Neel", "Ashan Kumara", "Pasindu Malin" ,"Saman Nishan", "Saman Kumara");
        $this->assertTrue($employeeInformation->list->isOnlyItemsListed($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);
    }
    
    public function testSearchCombination(){
            Helper::loginUser($this, "admin", "admin");
            $searchEmploye = new EmployeeListPage($this);
            $searchTesterYML = sfYaml::load(sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/newpim/testdata/EmployeeList.yml");
            $criteria = $searchTesterYML["SearchCriteria"];
            $results = $searchTesterYML["Results"];
            foreach($criteria as $criterion){
            $searchEmploye->searchBy($criterion["EmployeeName"], $criterion["Id"],$criterion["EmploymentStatus"],$criterion["EmployeeTermination"], $criterion["SupervisorName"],$criterion["JobTitle"], $criterion["SubUnit"]  );
            $expected[0] = $results[$criterion["TestName"]];
            $this->assertTrue($searchEmploye->getEmployeeList()->isRecordsPresentInList($expected, true));
            }
            
            Helper::logOutIfLoggedIn($this);
        }
           
        public function testSearchCombinationSupervisor(){
            Helper::loginUser($this, "chuck", "chuck");
            $employeeInformation = new EmployeeListPage($this);
            $employeeInformation->goToNewEmployeeList() ;
            $searchEmploye = new EmployeeListPage($this);
            $searchTesterYML = sfYaml::load(sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/newpim/testdata/EmployeeListSupervisor.yml");
            $criteria = $searchTesterYML["SearchCriteria"];
            $results = $searchTesterYML["Results"];
            foreach($criteria as $criterion){
            $searchEmploye->searchBy($criterion["EmployeeName"], $criterion["Id"],$criterion["EmploymentStatus"],$criterion["EmployeeTermination"], $criterion["SupervisorName"],$criterion["JobTitle"], $criterion["SubUnit"]  );
            $expected[0] = $results[$criterion["TestName"]];
            $this->assertTrue($searchEmploye->getEmployeeList()->isRecordsPresentInList($expected, true));
            }
            Helper::logOutIfLoggedIn($this);
        }
    
  public function testSortById() {
        $employeeInformation = Helper::loginUser($this, "admin", "admin");
        $employeeInformation->sortByFieldName("Id");
        $emplist = array("Saman Kumara", "Saman Nishan", "Ashan Kumara", "Chuck Neel","Pasindu Malin");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);
    }
    
    
   public function testSortByName() {
        $employeeInformation = Helper::loginUser($this, "admin", "admin");
        $employeeInformation->sortByFieldName("First (& Middle) Name");
        $emplist = array("Ashan Kumara",  "Chuck Neel", "Pasindu Malin" ,"Saman Kumara", "Saman Nishan");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        $employeeInformation->sortByFieldName("First (& Middle) Name");
        $emplist = array("Saman Nishan", "Saman Kumara","Pasindu Malin" ,"Chuck Neel", "Ashan Kumara");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);
    }
    
    public function testSortByLastName() {
        $employeeInformation = Helper::loginUser($this, "admin", "admin");
        $employeeInformation->sortByFieldName("Last Name");
        $emplist = array("Saman Nishan", "Chuck Neel", "Saman Kumara","Pasindu Malin","Ashan Kumara");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        $employeeInformation->sortByFieldName("Last Name");
        $emplist = array("Ashan Kumara","Pasindu Malin" , "Saman Kumara", "Saman Nishan", "Chuck Neel");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);
    }
    
   public function testSortByJobTitle() {
        $employeeInformation = Helper::loginUser($this, "admin", "admin");
        $employeeInformation->sortByFieldName("Job Title");
        $emplist = array("Saman Nishan", "Chuck Neel", "Pasindu Malin" ,"Saman Kumara", "Ashan Kumara");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        $employeeInformation->sortByFieldName("Job Title");
        $emplist = array("Ashan Kumara", "Saman Kumara", "Saman Nishan","Pasindu Malin" , "Chuck Neel");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);
    } 
    
    public function testSortByEmploymentStatus() {
        $employeeInformation = Helper::loginUser($this, "admin", "admin");
        $employeeInformation->sortByFieldName("Employment Status");
        $emplist = array("Saman Kumara", "Ashan Kumara", "Saman Nishan", "Chuck Neel","Pasindu Malin" );
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        $employeeInformation->sortByFieldName("Employment Status");
        $emplist = array("Saman Nishan", "Chuck Neel","Pasindu Malin" , "Ashan Kumara", "Saman Kumara");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);
    }
    
        public function testSortBySubUnit() {
        $employeeInformation = Helper::loginUser($this, "admin", "admin");
        $employeeInformation->sortByFieldName("Sub Unit");
        $emplist = array("Saman Kumara", "Ashan Kumara", "Chuck Neel", "Pasindu Malin" ,"Saman Nishan");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        $employeeInformation->sortByFieldName("Sub Unit");
        $emplist = array("Saman Nishan", "Chuck Neel","Pasindu Malin" , "Saman Kumara", "Ashan Kumara");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);
    }
    
    public function testSortBySupervisor() {
        $employeeInformation = Helper::loginUser($this, "admin", "admin");
        $employeeInformation->sortByFieldName("Supervisor");
        $emplist = array("Saman Nishan", "Pasindu Malin" ,"Saman Kumara", "Ashan Kumara", "Chuck Neel");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        $employeeInformation->sortByFieldName("Supervisor");
        $emplist = array("Chuck Neel", "Saman Kumara", "Ashan Kumara", "Saman Nishan","Pasindu Malin");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);
    }

        public function testEmployeeListSupervisorRights() {
        $employeeInformation = Helper::loginUser($this, "chuck ", "chuck");
        $employeeInformation = new EmployeeListPage($this);
        $employeeInformation->goToNewEmployeeList() ;
        $this->assertFalse($this->isVisible($employeeInformation->btnDelete));
        $this->assertFalse($this->isVisible($employeeInformation->btnAdd));
        Helper::logOutIfLoggedIn($this);
    }

      public function testSortByIdAsSupervisor() {
        $employeeInformation = Helper::loginUser($this, "chuck ", "chuck");
        $employeeInformation = new EmployeeListPage($this);
        $employeeInformation->goToNewEmployeeList() ;
        $employeeInformation->sortByFieldName("Id");
        $emplist = array("Ashan Kumara", "Saman Kumara");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        $employeeInformation->sortByFieldName("Id");
        $emplist = array("Saman Kumara", "Ashan Kumara");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);
    }
 public function testSortBySubUnitAsSupervisor() {
        $employeeInformation = Helper::loginUser($this, "chuck ", "chuck");
        $employeeInformation = new EmployeeListPage($this);
        $employeeInformation->goToNewEmployeeList() ;
        $employeeInformation->sortByFieldName("Sub Unit");
        $emplist = array("Saman Kumara", "Ashan Kumara");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        $employeeInformation->sortByFieldName("Sub Unit");
        $emplist = array("Saman Kumara", "Ashan Kumara");
        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);
    }
    
    public function testSerchAndReset() {
       
        Helper::loginUser($this, "admin", "admin"); 
        $searchEmployee = new EmployeeListPage($this);
        $searchEmployee->searchResetSearch("Ashan Kumara Perera", NULL, NULL, NULL, NULL, NULL, NULL);
        $emplist = array("Saman Nishan",  "Chuck Neel", "Saman Kumara" ,"Pasindu Malin", "Ashan Kumara");
        $this->assertTrue($searchEmployee->getEmployeeList()->isOnlyItemsListed($emplist, "First (& Middle) Name"));
        Helper::logOutIfLoggedIn($this);   
        
    }
    
    public function testAddNewEmployeeWithOutLoginDetailsAndPhotograph() {

        Helper::loginUser($this, "admin", "admin");
        $employeeInformation = new EmployeeListPage($this);
        $employeeInformation->goToAddEmployee();
        $personalDetails = new AddEmployeePage($this);
        $personalDetails ->addEmployee("Nethmi", "Dissanayaka");
        $employeeInformation = new EmployeeListPage($this);
        $employeeInformation->goToNewEmployeeList() ;
        $expected[] = array("Last Name" => "Dissanayaka", "First (& Middle) Name" => "Nethmi");
        $this->assertTrue($employeeInformation->getEmployeeList()->isRecordsPresentInList($expected, FALSE));
        Helper::logOutIfLoggedIn($this);
    }
             
    public function testSearchEmployeebyInvalidId(){
        Helper::loginUser($this, "admin", "admin");
        $EmployeeListPage = new EmployeeListPage($this);
        $EmployeeListPage->searchBy(NULL, "12345", NULL, NULL, NULL, NULL, NULL); 
        $this->assertEquals($EmployeeListPage->getStatusMessage(), "No Records Found");
        Helper::logOutIfLoggedIn($this);
     
    }   
    
}

?>
