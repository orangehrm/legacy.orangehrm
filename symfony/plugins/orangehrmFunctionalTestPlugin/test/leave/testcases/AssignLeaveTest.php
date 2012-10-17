<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** TestCase Description
 * Description of AssignLeaveTest
 *
 * @author madusani
 */
class AssignLeaveTest extends FunctionalTestcase {

    public $fixture;

    public function setUp() {

        $helper = new Helper();
        $testConfig = new TestConfig();
        $menu = new Menu();

        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($testConfig->getBrowserURL());

        $this->fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/leave/testdata/LeaveTestData.yml";
        //TestDataService::populate($fixture);
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> employees, 
     *                leave period, 
     *                leave type, 
     *                leave quotas
     * <br><b>steps:</b> Admin assigns leave for employee,
     *        Admin verifies the leave list
     *         
     * <br><b>Outcome:</b> In leave summary, the scheduled leave shold be increased 
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleAdmin_AssignLeaveRequestForTwoPeriods() {

        $controller = new LeaveTestController($this, $this->fixture, "Path5");
        $this->assertTrue($controller->execute());
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> employee should have default work-shift, 
     *                leave period, 
     *                leave type, 
     *                  
     * <br><b>steps:</b> Admin assigns leave for employee,
     *        Admin verifies the leave list and leave summary
     *         
     * <br><b>Outcome:</b> In leave summary, the taken leave should be increased
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleAdmin_AssignLeavePastDates() {

        $controller = new LeaveTestController($this, $this->fixture, "Path7");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> employee should have default work-shift, 
     *                leave period, 
     *                leave type, 
     *                  
     * <br><b>steps:</b> Admin assigns leave for employee,
     *        Admin verifies the leave list and leave summary
     *         
     * <br><b>Outcome:</b> In leave summary, the scheduled leave should be increased
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleAdmin_AssignLeaveForFutureDates() {
        $controller = new LeaveTestController($this, $this->fixture, "Path8");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> employee should have default work-shift, 
     *                leave period, 
     *                leave type, 
     *                  
     * <br><b>steps:</b> supervisor assigns leave for employee,
     *        supervisor verifies the leave list and leave summary
     *         
     * <br><b>Outcome:</b> In leave summary, the taken leave should be increased
     * <br><b>Status:</b> Passing</pre>
     */
    //DROPPED DUE TO 2.7
//    public function testUserRoleSupervisor_AssignLeaveforPastDates() {
//        $controller = new LeaveTestController($this, $this->fixture, "Path9");
//        $this->assertTrue($controller->execute(true));
//    }

    /** TestCase Description
     * 
     * <br><b>Prerequisites:</b>   employee should have default work-shift, 
     *                  leave period, 
     *                  leave type, 
     *                  
     * <br><b>steps:</b>   Admin assigns 2 leave requests for same day,
     *          Admin verifies the leave list and leave summary
     *         
     * <br><b>Outcome:</b> In leave summary, the scheduled leave should be increased by 1
     * <br><b>Status:</b> not passing
     */
    //PROBLEM
    //public function testUserRoleAdmin_AssignTwoHalfDaysForSameDate() {
    //    $controller = new LeaveTestController($this, $this->fixture, "Path16");
    //    $this->assertTrue($controller->execute(true));
    //}



    /** TestCase Description
     * 
     * <br><b>Prerequisites:</b>   employee should have default work-shift, 
     *                  leave period, 
     *                  leave type, 
     *                  
     * <br><b>steps:</b>   supervisor assigns 2 leave requests for same day,
     *          supervisor verifies the leave list and leave summary
     *         
     * <br><b>Outcome:</b> In leave summary, the scheduled leave should be increased by 1
     * <br><b>Status:</b> not passing
     */
    //PROBLEM
    //public function testUserRoleSupervisor_AssignTwoHalfDaysForSameDate() {
    //    $controller = new LeaveTestController($this, $this->fixture, "Path26");
    //    $this->assertTrue($controller->execute(true));
    //}

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> employee should have default work-shift, 
     *                leave period, 
     *                leave type, 
     *                holidays must be defined
     * <br><b>steps:</b> Admin assigns leave for range of days,
     *        Admin verifies the detailed list and leave summary
     *         
     * <br><b>Outcome:</b> In detailed leave list the holiday should be shown as "Non Working day"
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleAdmin_AssignLeaveIncludingHolidaysAndVerify() {
        $controller = new LeaveTestController($this, $this->fixture, "Path17");
        $this->assertTrue($controller->execute(true));
    }

    /** TestCase Description
     * 
     * <br><b>Prerequisites:</b>   A canceled leave request, 
     *                  leave period, 
     *                  leave type, 
     *                  
     * <br><b>steps:</b>   Supervisor assigns leave for a canceled one,
     *          Supervisor verifies leave list and leave summary,
     * <br><b>Outcome:</b> leave list should show the leave request as "taken" 
     * <br><b>Status:</b> Passing
     */
//    public function testUserRoleSupervisor_AssignLeaveForCanceledLeave() {
//        $controller = new LeaveTestController($this, $this->fixture, "Path19");
//        $this->assertTrue($controller->execute(true));
//    }

    /** TestCase Description
     * 
     * <br><b>Prerequisites:</b>   A rejected leave request, 
     *                  leave period, 
     *                  leave type, 
     *                  
     * <br><b>steps:</b>   Supervisor assigns leave for a rejected one,
     *          Supervisor verifies leave list and leave summary,
     * <br><b>Outcome:</b> leave list should show the leave request as "Taken" 
     * <br><b>Status:</b> Passing
     */
//    public function testUserRoleSupervisor_AssignLeaveForRejectedLeave() {
//        $controller = new LeaveTestController($this, $this->fixture, "Path21");
//        $this->assertTrue($controller->execute(true));
//    }

    /** TestCase Description
     * 
     * <br><b>Prerequisites:</b>   employee's workshift should be 10 hours, 
     *                  leave period, 
     *                  leave type, 
     *                  leave quotas
     * <br><b>steps:</b>   Supervisor assigns a leave for one day,
     *          Supervisor verifies detailed leave list,
     * <br><b>Outcome:</b> detailed leave list should show the hours as 10 
     * <br><b>Status:</b> Passing
     */
//    public function testUserRoleSupervisor_AssignOneDayLeave() {
//        $controller = new LeaveTestController($this, $this->fixture, "Path24");
//        $this->assertTrue($controller->execute(true));
//    }

}