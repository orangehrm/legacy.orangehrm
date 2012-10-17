<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** TestCase Description
 * Description of ConfigureMenuTest
 *
 * @author madusani
 */
class ConfigureMenuTest extends FunctionalTestcase {

    private $menu;

    public function setUp() {

        $helper = new Helper();
        $testConfig = new TestConfig();
        if (!isset($this->menu)) {
            $this->menu = new Menu();
        }
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($testConfig->getBrowserURL());
        $testData = sfYaml::load(sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/leave/testdata/LeaveTestData.yml");
        $leavePrerequisites = new LeavePrerequisiteHandler($testData["PrerequisiteDetails"]["fileName"]);
        //$fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/leave/testdata/LeaveModulePrerequisites.yml";
        //TestDataService::populate($fixture);   
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave period should be defined
     *                  
     * <br><b>steps:</b> Admin adds a leave type
     *        Admin verifies the leave type list
     * <br><b>Outcome:</b> leave type list should contain the leave type
     * <br><b>Status:</b> Passing</pre>
     */
    public function testAddLeaveTypeAndVerify() {
        Helper::loginUser($this, 'admin', 'admin');
        $viewLeaveType = $this->menu->goToConfigure_LeaveTypes($this);
        $viewLeaveType->addLeaveType("Casual2");
        $this->assertTrue($viewLeaveType->list->isItemPresentInColumn("Leave Type", "Casual2"));

        $this->assertEquals("Successfully Saved", $viewLeaveType->getSuccessfullMessage());
        //Helper::logOutIfLoggedIn($this);
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave period should be defined,
     *                leave types
     * <br><b>steps:</b> Admin edits a leave type
     *        Admin verifies the leave type list
     * <br><b>Outcome:</b> leave type list should contain the new leave type
     * <br><b>Status:</b> Passing</pre>
     */
    public function testEditLeaveTypeAndVerify() {
        Helper::loginUser($this, 'admin', 'admin');
        $viewLeaveType = $this->menu->goToConfigure_LeaveTypes($this);
        $viewLeaveType->list->clickOnTheItem("Leave Type", "Sick");
        
        $viewLeaveType->editLeaveType("Birthday");
        $this->assertTrue($viewLeaveType->list->isItemPresentInColumn("Leave Type", "Birthday"));
        //Helper::logOutIfLoggedIn($this);
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave period should be defined,
     *                leave types
     * <br><b>steps:</b> Admin deletes a leave type
     *        Admin verifies the leave type list
     * <br><b>Outcome:</b> deleted leave type should not be in the list
     * <br><b>Status:</b> Passing</pre>
     */
    public function testDeleteLeaveTypeAndVerify() {
        Helper::loginUser($this, 'admin', 'admin');
        $viewLeaveType = $this->menu->goToConfigure_LeaveTypes($this);
        $viewLeaveType->deleteLeaveType("Sick");
        $this->assertEquals("Successfully Deleted", $viewLeaveType->getSuccessfullMessage());
        //Helper::logOutIfLoggedIn($this);
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave period should be defined,
     *                leave type
     * <br><b>steps:</b> Admin deletes a leave type,
     *        Admin adds another leave type with same name,
     *        Admin verifies the leave type list
     * <br><b>Outcome:</b> leave type list should contain the new leave type
     * <br><b>Status:</b> Passing</pre>
     */
    public function testDeleteLeaveTypeAndAddLeaveTypeWithSameName() {
        Helper::loginUser($this, 'admin', 'admin');
        $viewLeaveType = $this->menu->goToConfigure_LeaveTypes($this);
        $viewLeaveType->deleteLeaveType("Sick");
        $viewLeaveType->addLeaveType("Sick", "Yes");
        $this->assertTrue($viewLeaveType->list->isItemPresentInColumn("Leave Type", "Sick"));
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave period should be defined
     *                   
     * <br><b>steps:</b> Admin adds a holiday
     *        Admin verifies the holiday list
     * <br><b>Outcome:</b> holiday list should contain the defined holiday
     * <br><b>Status:</b> Passing</pre>
     */
    public function testAddHolidayAndVerify() {
        Helper::loginUser($this, 'admin', 'admin');
        $viewHoliday = Menu::goToConfigure_Holidays($this);
        $addHoliday = $viewHoliday->goToAddHoliday();
        $viewHoliday = $addHoliday->addHoliday("Vesak", "2011-05-06", true, "Half Day");
        
        $this->assertTrue($viewHoliday->list->isItemPresentInColumn("Name", "Vesak"));
        //Helper::logOutIfLoggedIn($this);
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave period should be defined
     *                holidays
     * <br><b>steps:</b> Admin edits a holiday
     *        Admin verifies the holiday list
     * <br><b>Outcome:</b> holiday list should contain the new holiday
     * <br><b>Status:</b> Passing</pre>
     */
    public function testEditHoliday() {
        Helper::loginUser($this, 'admin', 'admin');
        $viewHoliday = Menu::goToConfigure_Holidays($this);

        $viewHoliday->list->clickOnTheItem("Name", "Thaipongal");
        
        $viewHoliday->addHoliday->editHoliday("Thaipongal", "2011-08-07", false, "Half Day");
        
        $this->assertEquals("Successfully Updated", $viewHoliday->getSuccessfullMessage());
        
        $viewHoliday->list->clickOnTheItem("Name", "Thaipongal");
        
        $viewHoliday->addHoliday->editHoliday("Thaipongal", "2011-08-07", false, "Full Day");
        
        $this->assertEquals("Successfully Updated", $viewHoliday->getSuccessfullMessage());
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave period should be defined
     *                holidays
     * <br><b>steps:</b> Admin deletes a holiday
     *        Admin verifies the holiday list
     * <br><b>Outcome:</b> holiday list should not contain the deleted holiday
     * <br><b>Status:</b> Passing</pre>
     */
    public function testDeleteHolidayAndVerify() {
        Helper::loginUser($this, 'admin', 'admin');
        $viewHoliday = $this->menu->goToConfigure_Holidays($this);
        $viewHoliday->deleteHoliday("Thaipongal");

        $this->assertEquals("Successfully Deleted", $viewHoliday->getSuccessfullMessage());
        //Helper::logOutIfLoggedIn($this);
    }

    /** TestCase Description
     * 
     * <br><b>Prerequisites:</b>    leave period should be defined
     *                  
     * <br><b>steps:</b>   Admin edits the leave period
     *          Admin verifies the leave period
     * <br><b>Outcome:</b> leave period should be correct for non leap years and leap years
     * <br><b>Status:</b> Passing
     */
    //AJAX Calls
//    public function testEditLeavePeriodForNonLeapYears() {
//        Helper::loginUser($this, 'admin', 'admin');
//        $viewLeavePeriod = Menu::goToConfigure_LeavePeriod($this);
//        $viewLeavePeriod->editLeavePeriod("February", "29", "February", "1");
//        
//        $this->assertEquals("2011-01-01 to 2012-02-28", $viewLeavePeriod->getCurrentLeavePeriod());
//    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave period should be defined
     *                  
     * <br><b>steps:</b> Admin edits the work week and verify
     *
     * <br><b>Outcome:</b> A successful message should be shown
     * <br><b>Status:</b> Passing</pre>
     */
    public function testEditWorkWeekAndVerify() {
        Helper::loginUser($this, 'admin', 'admin');
        $viewWorkWeek = Menu::goToConfigure_WorkWeek($this);
        $viewWorkWeek->editDaysOfWorkingWeek(null, null, null, null, "Non-working Day", "Half Day", "Full Day");
        $this->assertEquals("Successfully Saved", $viewWorkWeek->getSavedSuccessfullyMessage());
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave period should be defined,
     *                employee,
     *                leave type,
     *                leave requests defined for the leave type
     * <br><b>steps:</b> Admin deletes a leave type
     *        Admin verifies the leave list and leave summary
     * <br><b>Outcome:</b> leave list and leave summary should show the suffix of the leave type as "(deleted)"
     * <br><b>Status:</b> Passing</pre>
     */
    public function testDeleteLeaveTypeAndVerifyLeaveRequests() {
        Helper::loginUser($this, 'admin', 'admin');
        $viewLeaveType = Menu::goToConfigure_LeaveTypes($this);
        $viewLeaveType->deleteLeaveType("Sick");
        //first verification
        $viewLeaveList = Menu::goToLeaveList($this);
        $viewLeaveList->searchLeaveRecords("2012-05-08", "2012-05-10", true);
        $this->assertEquals("Sick (Deleted)", $viewLeaveList->list->getItemOfFirstRecord("LeaveType"));
        //$this->assertEquals("Cancel", $viewLeaveList->list->getItemOfFirstRecord("Action"));
        //second verification
        $viewLeaveSummary = Menu::goToLeaveSummary($this);
        $viewLeaveSummary->viewLeaveSummaryRecords("2012-01-01 to 2012-12-31", "Kayla Abbey");
        $this->assertEquals("Sick (Deleted)", $viewLeaveSummary->list->getItemOfLeaveSummaryRecord("Sick (Deleted)"));
        $viewLeaveSummary->viewLeaveSummaryRecords("2012-01-01 to 2012-12-31", "John De Soyza");
        $this->assertNotEquals("Sick (Deleted)", $viewLeaveSummary->list->getItemOfLeaveSummaryRecord("Sick (Deleted)"));
    }

}