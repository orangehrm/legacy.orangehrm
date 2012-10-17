<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** TestCase Description
 * Description of TimeFlowTest
 *
 * @author Faris
 */
class TimeFlowTest extends FunctionalTestcase {

    private $fixture;

    public function setUp() {
        $helper = new Helper();
        $config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $browser = $config->getBrowserURL();
        $this->setBrowserUrl($browser);
        $this->fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/time/testdata/TimeFlowData.yml";
    }

    

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> NONE <br>
     * <b>Description :</b> Admin creates and saves TimeSheet <br>
     * <b>OutCome :</b> TimeSheet Present <br>
     * <b>status :</b> passing <br>
     */
   
    
    
    public function testAdminRole_AdminSaveTimeSheetAndVerify() {

        $controller = new TimeTestController($this, $this->fixture, "SaveTimeSheetAsAdmin");
        $this->assertTrue($controller->execute(), "SaveTimeSheetAsAdmin failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> NONE <br>
     * <b>Description :</b> Supervisor creates and saves TimeSheet <br>
     * <b>OutCome :</b> TimeSheet Present <br>
     * <b>status :</b> passing <br>
     */
    public function testSupervisorRole_SupervisorSaveTimeSheetAndVerify() {

        $controller = new TimeTestController($this, $this->fixture, "SaveTimeSheetAsSuperVisor");
        $this->assertTrue($controller->execute(), "SaveTimeSheetAsSuperVisor failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> NONE <br>
     * <b>Description :</b> Admin save and submit TimeSheet <br>
     * <b>OutCome :</b> Submitted TimeSheet Present <br>
     * <b>status :</b> passing <br>
     */
    public function testAdminRole_AdminSaveAndSubmitTimeSheetAndVerify() {

        $controller = new TimeTestController($this, $this->fixture, "SaveAndSubmitTimeSheetAsAdmin");
        $this->assertTrue($controller->execute(), "SaveAndSubmitTimeSheetAsAdmin failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> NONE <br>
     * <b>Description :</b> Supervisor save and submit TimeSheet <br>
     * <b>OutCome :</b> Submitted TimeSheet Present <br>
     * <b>status :</b> passing <br>
     */
    public function testSupervisorRole_SupervisorSaveAndSubmitTimeSheetAndVerify() {

        $controller = new TimeTestController($this, $this->fixture, "SaveAndSubmitTimeSheetAsSupervisor");
        $this->assertTrue($controller->execute(), "SaveAndSubmitTimeSheetAsSupervisor failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> NONE <br>
     * <b>Description :</b> ESS user adds attendance Record <br>
     * <b>OutCome :</b> Attendance Record Available <br>
     * <b>status :</b> passing <br>
     */
    public function testESSRole_ESSUserPunchInAndPunchOut() {

        $controller = new TimeTestController($this, $this->fixture, "PunchInAndPunchOutAsESSUser");
        $this->assertTrue($controller->execute(), "PunchInAndPunchOutAsESSUser failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> NONE <br>
     * <b>Description :</b> Supervisor adds attendance Record with time given <br>
     * <b>OutCome :</b> Attendance Record Available <br>
     * <b>status :</b> passing <br>
     */
    public function testSupervisorRole_PunchInAndPunchOutWithTimeGiven() {
        $controller = new TimeTestController($this, $this->fixture, "PunchInAndPunchOutWithTimeGivenAsSupervisorUser");
        $this->assertTrue($controller->execute(), "PunchInAndPunchOutWithTimeGivenAsSupervisorUser failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> NONE <br>
     * <b>Description :</b> ESS user add and edits attendace record <br>
     * <b>OutCome :</b> Edited Attendance Record Available <br>
     * <b>status :</b> passing <br>
     */
    public function testESSRole_EditAttendanceRecord() {
        $controller = new TimeTestController($this, $this->fixture, "EditAttendanceRecordESS");
        $this->assertTrue($controller->execute(), "EditAttendanceRecordESS failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> TimeSheet <br>
     * <b>Description :</b> ESS user submit TimeSheet and Admin Apprives TimeSheet <br>
     * <b>OutCome :</b> Approved TimeSheet Present <br>
     * <b>status :</b> passing <br>
     */
    public function testAdminRole_EssUserSubmitesAndAdminApprovesTimeSheet() {
        $controller = new TimeTestController($this, $this->fixture, "ESSSubmitAndAdminApproveTimeSheet");
        $this->assertTrue($controller->execute(), "ESSSubmitAndAdminApproveTimeSheet failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> TimeSheet <br>
     * <b>Description :</b> ESS user submit TimeSheet and Admin Rejects TimeSheet <br>
     * <b>OutCome :</b> Rejected TimeSheet Present <br>
     * <b>status :</b> passing <br>
     */
    public function testAdminRole_EssUserSubmitesAndAdminRejectsTimeSheet() {
        $controller = new TimeTestController($this, $this->fixture, "ESSSubmitAndAdminRejectsTimeSheet");
        $this->assertTrue($controller->execute(), "ESSSubmitAndAdminRejectsTimeSheet failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> TimeSheet <br>
     * <b>Description :</b> View Porject Reports of Only Approved TimeSheets <br>
     * <b>OutCome :</b> Project Report Present <br>
     * <b>status :</b> passing <br>
     */
    public function testAdminRole_ViewApprovedProjectReport() {
        $controller = new TimeTestController($this, $this->fixture, "viewApprovedProjectReport");
        $this->assertTrue($controller->execute(), "viewApprovedProjectReport failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> TimeSheet <br>
     * <b>Description :</b> View Porject Reports of Non Approved TimeSheets <br>
     * <b>OutCome :</b> Project Report Present <br>
     * <b>status :</b> passing <br>
     */
    public function testAdminRole_ViewNonApprovedProjectReport() {
        $controller = new TimeTestController($this, $this->fixture, "viewNonApprovedProjectReport");
        $this->assertTrue($controller->execute(), "viewNonApprovedProjectReport failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> TimeSheet <br>
     * <b>Description :</b> View Employee Report <br>
     * <b>OutCome :</b> Employee Report Present <br>
     * <b>status :</b> passing <br>
     */
    public function testAdminRole_ViewEmployeeReport() {
        $controller = new TimeTestController($this, $this->fixture, "viewEmployeeReport");
        $this->assertTrue($controller->execute(), "viewEmployeeReport failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> NONE <br>
     * <b>Description :</b> Edit Attendance Records as Supervisor Addded by ESS user <br>
     * <b>OutCome :</b> Edited Attendance Record Available <br>
     * <b>status :</b> passing <br>
     */
    public function testSupervisorRole_EditAttendanceRecordAsSupervisor() {
        $controller = new TimeTestController($this, $this->fixture, "EditAttendanceRecordSupervisor");
        $this->assertTrue($controller->execute(), "EditAttendanceRecordSupervisor failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> NONE <br>
     * <b>Description :</b> Add Attendace Record as Supervisor for SubOrdinates <br>
     * <b>OutCome :</b> Attendance Record Available <br>
     * <b>status :</b> passing <br>
     */
    public function testSupervisorRole_AddAttendanceRecordAsSupervisorForSubOrdinates() {
        $controller = new TimeTestController($this, $this->fixture, "AddAttendanceRecordAsSupervisor");
        $this->assertTrue($controller->execute(), "AddAttendanceRecordAsSupervisor failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> NONE <br>
     * <b>Description :</b> Add Attendace Record as Supervisor for SubOrdinates with Diffrent Time Zones <br>
     * <b>OutCome :</b> Attendance Record Available <br>
     * <b>status :</b> passing <br>
     */
    public function testSupervisorRole_AddAttendanceRecordAsSupervisorWithTimeZoneForSubOrdinates() {
        $controller = new TimeTestController($this, $this->fixture, "AddAttendanceRecordAsSupervisorWithTimeZone");
        $this->assertTrue($controller->execute(), "AddAttendanceRecordAsSupervisorWithTimeZone failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> TimeSheet <br>
     * <b>Description :</b> View Attendance Summary Report as Admin <br>
     * <b>OutCome :</b> Employee Report Present <br>
     * <b>status :</b> passing <br>
     */
    public function testAdminRole_ViewAttendanceSummaryReportAsAdmin() {
        $controller = new TimeTestController($this, $this->fixture, "AttendanceSummaryReportAsAdmin");
        $this->assertTrue($controller->execute(), "AttendanceSummaryReportAsAdmin failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> TimeSheet <br>
     * <b>Description :</b> View Attendance Summary Report as Supervisor <br>
     * <b>OutCome :</b> Employee Report Present <br>
     * <b>status :</b> passing <br>
     */
    public function testAdminRole_ViewAttendanceSummaryReportAsSupervisor() {
        $controller = new TimeTestController($this, $this->fixture, "AttendanceSummaryReportAsSupervisor");
        $this->assertTrue($controller->execute(), "AttendanceSummaryReportAsSupervisor failed");
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> TimeSheet <br>
     * <b>Description :</b> View Employee Report as Supervisor with defaults <br>
     * <b>OutCome :</b> Employee Report Present <br>
     * <b>status :</b> passing <br>
     */
    public function testAdminSupervisor_ViewEmployeeReportReportAsSupervisor() {
        $controller = new TimeTestController($this, $this->fixture, "ViewEmployeeeportAsSupervisor");
        $this->assertTrue($controller->execute(), "ViewEmployeeeportAsSupervisor failed");
        
    }

    /** TestCase Description
     * 
     * <b>Prerequisites :</b> TimeSheet <br>
     * <b>Description :</b> View Employee Report as Supervisor Without defaults <br>
     * <b>OutCome :</b> Employee Report Present <br>
     * <b>status :</b> passing <br>
     */
    public function testAdminSupervisor_ViewEmployeeReportReportAsSupervisorWithValues() {
        $controller = new TimeTestController($this, $this->fixture, "ViewEmployeeeportAsSupervisorWithValues");
        $this->assertTrue($controller->execute(), "ViewEmployeeeportAsSupervisorWithValues failed");
    }

  

}