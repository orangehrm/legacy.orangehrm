<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** TestCase Description
 * Description of ApplyLeaveTest
 *
 * @author madusani
 */
class ApplyLeaveTest extends FunctionalTestcase {

    public $fixture;

    public function setUp() {

        $helper = new Helper();
        $testConfig = new TestConfig();
        $menu = new Menu();
        $this->fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/leave/testdata/LeaveTestData.yml";
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($testConfig->getBrowserURL());
    }



    

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave types,
     *                employees,
     *                leave quotas for the periods,
     *                leave period should be defined, 
     *
     * <br><b>steps:</b> Ess user applies for leave,
     *        admin verifies the leave list,
     * <br><b>Outcome:</b> In leave summary scheduled leave of the employee should not be increased
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleEss_ApplyLeaveRequestForTwoPeriods() {

        $controller = new LeaveTestController($this, $this->fixture, "Path2");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave type, 
     *                employee, 
     *                  leave period should be defined, 
     * <br><b>steps:</b> Ess user applies for leave,
     *        admin deletes the leave type,
     * <br><b>Outcome:</b> The action to be performed on the leave request must be listed as "Cancel" only 
     * <br><b>Status:</b> Passing</pre>
     */
    public function testLeaveTypeDeleteAfterEssUserAppliedLeave() {
        $controller = new LeaveTestController($this, $this->fixture, "Path6");
        $this->assertTrue($controller->execute(true));

        Helper::loginUser($this, 'admin', 'admin');
        $viewLeaveType = Menu::goToConfigure_LeaveTypes($this);

        $viewLeaveType->deleteLeaveType("Annual");
        $viewLeaveList = Menu::goToLeaveList($this);
        $viewLeaveList->searchLeaveRecords("2011-02-08", "2011-02-11", true, false, false, false, false, false, "Ashley Abel");

        $this->assertFalse($viewLeaveList->list->performActionOnLeaveRequest("Ashley Abel", "2011-02-08 to 2011-02-11", "Approve"));
        Helper::logOutIfLoggedIn($this);
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave type, 
     *                leave period, 
     *                leave quotas, 
     *                  
     * <br><b>steps:</b> Ess user applies for leave,
     *        Ess User verifies the leave list and leave summary,
     * <br><b>Outcome:</b> The leave request should be shown as "pending approval" in leave list
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleEss_ApplyLeaveForPastDate() {
        $controller = new LeaveTestController($this, $this->fixture, "Path10");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> leave type, 
     *                leave period, 
     *                leave quotas, 
     *                  
     * <br><b>steps:</b> Ess user applies for leave,
     *        Ess user verifies leave list and leave summary,
     * <br><b>Outcome:</b> The leave request should be shown as "pending approval" in leave list
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleEss_ApplyLeaveForFutureDate() {
        $controller = new LeaveTestController($this, $this->fixture, "Path11");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> A canceled leave request, 
     *                leave period, 
     *                leave type, 
     *                  
     * <br><b>steps:</b> Ess user applies leave for a canceled one,
     *        Ess user verifies leave list and leave summary,
     * <br><b>Outcome:</b> leave list should show the leave request as pending approval 
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleEss_ApplyLeaveForCanceledLeave() {
        $controller = new LeaveTestController($this, $this->fixture, "Path20");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> A rejected leave request, 
     *                leave period, 
     *                leave type, 
     *                  
     * <br><b>steps:</b> Ess user applies leave for a rejected one,
     *        Ess user verifies leave list and leave summary,
     * <br><b>Outcome:</b> leave list should show the leave request as pending approval 
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleEss_ApplyLeaveForRejectedLeave() {
        $controller = new LeaveTestController($this, $this->fixture, "Path22");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> employee's workshift should be 10 hours, 
     *                leave period, 
     *                leave type, 
     *                leave quotas
     * <br><b>steps:</b> Ess user applies for leave,
     *        Ess user verifies detailed leave list,
     * <br><b>Outcome:</b> detailed leave list should show the hours as 10 
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleEss_ApplyOneDayLeave() {
        $controller = new LeaveTestController($this, $this->fixture, "Path23");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> employee's workshift should be 10 hours, 
     *                leave period, 
     *                leave type, 
     *                leave quotas
     * <br><b>steps:</b> Ess user applies 3 leave requests for same day,
     *         
     * <br><b>Outcome:</b> after applying the 3rd leave request, the system should show an error message 
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleEss_ApplyLeaveExceedingWorkShift() {
        $controller = new LeaveTestController($this, $this->fixture, "Path25");
        $this->assertTrue($controller->execute(true));
    }

}