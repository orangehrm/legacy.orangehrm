<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** TestCase Description
 * Description of ViewLeaveTest
 *
 * @author madusani
 */
class ViewLeaveTest extends FunctionalTestcase {

    public $fixture;

    public function setUp() {

        $helper = new Helper();
        $testConfig = new TestConfig();
        $menu = new Menu();

        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($testConfig->getBrowserURL());
        $this->fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/leave/testdata/LeaveTestData.yml";
        //LeavePrerequisiteHandler::addPrerequisites();
        // $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/leave/testdata/LeaveModulePrerequisites.yml";
        //TestDataService::populate($fixture);
    }


    
    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> aprroved leave request, 
     *                leave period, 
     *                leave type, 
     *                leave quotas
     * <br><b>steps:</b> Admin cancels an approved leave request,
     *        Admin verifies leave summary
     * <br><b>Outcome:</b> leave summary scheduled leave should be decreased
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleAdmin_CancelAnApprovedLeaveRequest() {

        $controller = new LeaveTestController($this, $this->fixture, "Path3");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <br><b>Prerequisites:</b>   an applied leave request, 
     *                  leave period, 
     *                  leave type, 
     *                  leave quotas
     * <br><b>steps:</b>   Ess user cancels an applied leave request,
     *          Ess user verifies leave list and leave summary
     * <br><b>Outcome:</b> leave list status of the applied leave request should show as "Canceled"
     * <br><b>Status:</b> Passing
     */
    //DROPPED DUE TO 2.7
//    public function testUserRoleEss_CancelAnAppliedLeave() {
//        $controller = new LeaveTestController($this, $this->fixture, "Path12");
//        $this->assertTrue($controller->execute(true));
//    }

    /** TestCase Description
     * 
     * <br><b>Prerequisites:</b>   an applied leave request, 
     *                  leave period, 
     *                  leave type, 
     *                  leave quotas
     * <br><b>steps:</b>   Supervisor approves the leave request,
     *          Supervisor verifies leave list and leave summary
     * <br><b>Outcome:</b> leave list status of the leave should be shown as "Taken"
     * <br><b>Status:</b> Passing
     */
    //same flow path and data
//    public function testUserRoleSupervisor_ApproveALeaveRequest() {
//        $controller = new LeaveTestController($this, $this->fixture, "Path13");
//        $this->assertTrue($controller->execute(true));
//    }

    /** TestCase Description
     * 
     * <br><b>Prerequisites:</b>   an applied leave request, 
     *                  leave period, 
     *                  leave type, 
     *                  leave quotas
     * <br><b>steps:</b>   Supervisor rejects the leave request,
     *          Supervisor verifies leave list and leave summary
     * <br><b>Outcome:</b> leave list status of the leave should be shown as "Rejected"
     * <br><b>Status:</b> Passing
     */
//    public function testUserRoleSupervisor_RejectALeaveRequest() {
//        $controller = new LeaveTestController($this, $this->fixture, "Path14");
//        $this->assertTrue($controller->execute(true));
//    }

    /** TestCase Description
     * 
     * <br><b>Prerequisites:</b>   an applied leave request, 
     *                  leave period, 
     *                  leave type, 
     *                  leave quotas
     * <br><b>steps:</b>   Ess user cancels the leave request,
     *          Ess user verifies leave list and leave summary
     * <br><b>Outcome:</b> leave list status of the leave should be shown as "Canceled"
     * <br><b>Status:</b> Passing
     */
//    public function testUserRoleEss_CancelALeaveRequest() {
//        $controller = new LeaveTestController($this, $this->fixture, "Path15");
//        $this->assertTrue($controller->execute(true));
//    }

    /** TestCase Description
     *      
     * <pre><br><b>Prerequisites:</b> a taken leave request, 
     *                leave period, 
     *                leave type, 
     *                leave quotas
     * <br><b>steps:</b> Admin cancels the leave request,
     *        Admin verifies leave summary
     * <br><b>Outcome:</b> leave summary taken leave should be decreased
     * <br><b>Status:</b> Passing</pre>
     */

    public function testUserRoleAdmin_CancelAnAssignedAndApprovedLeaveRequest() {

        $controller = new LeaveTestController($this, $this->fixture, "Path3");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> An applied leave request for 3 days, 
     *                leave period, 
     *                leave type, 
     *                leave quotas
     * <br><b>steps:</b> Admin approves the first date in detailed list and verify,
     *        Admin rejects the second date in detailed list and verify,
     *        Admin cancels the third date in detailed list and verify,
     * <br><b>Outcome:</b> the first date status should be shown as "Scheduled",
     *          the second date status should be shown as "Rejected",
     *          the third date status should be shown as "Canceled"
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleAdmin_ApproveAndRejectAndCancelOneLeaveRequestWhichHas3Days() {
        $controller = new LeaveTestController($this, $this->fixture, "Path18");
        $this->assertTrue($controller->execute(true));
    }

}