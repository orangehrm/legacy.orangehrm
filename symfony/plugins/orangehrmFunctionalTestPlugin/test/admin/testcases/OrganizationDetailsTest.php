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
class OrganizationDetailsTest extends FunctionalTestcase {

    public function setUp() {

        $helper = new Helper();
        $testConfig = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($testConfig->getBrowserURL());
        $externalPrerequisites = new ExternalDependencyHandler();
        $externalPrerequisites->ensureDependencies('admin');
    }

    //Company general Information
    public function testEditGeneralInformation() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $generalInformation = Menu::goToOrganization_GeneralInformation($this);
        $generalInformation->editGeneralInformation("OrangeHRM Inc", "7234", "20001", "011-2344344", "98728782", "support@orange.com", "25A", "Fairlyne Road", "Colombo01", "Western", "0094", "Sri Lanka", "Company is established in 1990");
        $this->assertEquals("General Information Saved Successfully", $generalInformation->getSuccessfullyAssignedMessage());
        Helper::logOutIfLoggedIn($this);
    }

    //Locations
    public function testAddCompanyLocation() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $viewLocations = Menu::goToOrganization_Locations($this);
        $addLocation = $viewLocations->goToAddLocation();
        $addLocation->addLocation("Sub Location 10", "Sri Lanka", "Western", "Colombo 05", "12B, Museum Avenue", "0094", "011-2555555", "98923121", "The branch handles all the Customer Related Operations");
        $this->assertTrue($viewLocations->list->isItemPresentInColumn("Name", "Sub Location 10"));
        $this->assertEquals("Location Added Successfully", $viewLocations->getSuccessfullMessage());
        Helper::logOutIfLoggedIn($this);
    }

    public function testEditCompanyLocation() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $viewLocations = Menu::goToOrganization_Locations($this);
        $viewLocations->list->clickOntheItem("Name", "Sub Location 01");
        
        $editLocation = new AddOrganizationLocationPageObject($this);
        $editLocation->editLocation("Sub Location 101", "United Kingdom", null, "Heathrow", "12M, Maitland Place", "12233", "87872381", null, null);
        $this->assertTrue($viewLocations->list->isItemPresentInColumn("Name", "Sub Location 101"));
        $this->assertEquals("Location Updated Successfully", $viewLocations->getSuccessfullMessage());
        Helper::logOutIfLoggedIn($this);
    }

    public function testSearchCompanyLocation() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $viewLocations = Menu::goToOrganization_Locations($this);
        $viewLocations->viewLocationRecords("Main Branch");
        $locationsList = array("Main Branch");
        $this->assertTrue($viewLocations->list->isOnlyItemsListed($locationsList, "Name"));

        $viewLocations->viewLocationRecords("Sub Location", "Los Angeles");
        $locationsList = array("Sub Location 04");
        $this->assertTrue($viewLocations->list->isOnlyItemsListed($locationsList, "Name"));

        $viewLocations->viewLocationRecords("Sub Location", "New York", "United States");
        $locationsList = array("Sub Location 05");
        $this->assertTrue($viewLocations->list->isOnlyItemsListed($locationsList, "Name"));
        Helper::logOutIfLoggedIn($this);
    }

    public function testDeleteOneLocation() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $viewLocations = Menu::goToOrganization_Locations($this);
        $viewLocations->deleteLocation("Sub Location 01");
        $this->assertEquals("Selected Location(s) Deleted Successfully", $viewLocations->getSuccessfullMessage());
        $this->assertFalse($viewLocations->list->isItemPresentInColumn("Name", "Sub Location 01"));
        Helper::logOutIfLoggedIn($this);
    }

    public function testDeleteAllLocations() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $viewLocations = Menu::goToOrganization_Locations($this);
        $viewLocations->deleteAllLocations();
        $this->assertEquals("Selected Location(s) Deleted Successfully", $viewLocations->getSuccessfullMessage());
        Helper::logOutIfLoggedIn($this);
    }

    //PROBLEM: Company Strcuture
    public function testAddUnitForCompanyStructure() {
        AdminPrerequisiteHandler::restorePrerequisites("AdminInternalPrerequisites.yml");
        Helper::loginUser($this, 'admin', 'admin');
        $viewStructure = Menu::goToOrganization_Structure($this);
        $addCopmanyUnit = $viewStructure->goToAddCompanyStructure("OrangeHRM");
        $addCopmanyUnit->addCompanyUnit("002", "Human Resource Department", "Employee Records are maintained");
        $this->assertEquals("Subunit Saved Successfully", $viewStructure->getSuccessfullMessage());

        Helper::logOutIfLoggedIn($this);
    }

}