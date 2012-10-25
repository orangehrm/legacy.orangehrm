<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Menu
 *
 * @author OrangeHRM
 */


class Menu {

    //put your code here

    public static $mnuEmployeeList = "//li[@id='pim']//a[@class='l2_link emplist']";
    public static $mnuPIM = "pim";
    public static $mnuESS = "//li[@id='ess']/a";
    public static $mnuOptionalFields = "//li[@id='pim']//a[@class='pimconfig']";
    public static $mnuCustomFilds = "//li[@id='pim']//a[@class='customfields']";
    public static $mnuVacancyList = "//li[@id='recruit']//li[2]/a/span";
    public static $mnuViewCandidate = "//li[@id='recruit']/ul/li[1]//a[@class='l2_link recruit']";
    
    public static $mnuViewVacancies = "//li[@id='recruit']/ul/li[2]//a[@class='l2_link recruit']";
    
    //public static $btnAdd = "btnAdd";
    //Leave module
    public static $mnuLeaveModule = "//li[@id='leave']/a";
    public static $mnuLeavePeriod = "//li[@id='leave']/ul/li[1]/ul/li[1]/a[@class='leaveperiod']";
    public static $mnuViewLeaveTypes = "//li[@id='leave']/ul/li[1]/ul/li[2]/a[@class='leavetypes']";
    public static $mnuWorkWeek = "//li[@id='leave']/ul/li[1]/ul/li[3]/a[@class='daysoff']";
    public static $mnuViewHolidays = "//li[@id='leave']/ul/li[1]/ul/li[4]/a[@class='daysoff']";
    public static $mnuViewLeaveSummary = "//li[@id='leave']//ul/li/a[contains(.,'Leave Summary')]";
    public static $mnuViewLeaveList = "//li[@id='leave']//ul/li/a[contains(.,'Leave List')]";
    public static $mnuAssignLeave = "//li[@id='leave']//ul/li/a[contains(.,'Assign Leave')]";
    public static $mnuMyLeaveList = "//li[@id='leave']//ul/li/a[contains(.,'My Leave')]";
    public static $mnuApplyForLeave = "//li[@id='leave']//ul/li/a[contains(.,'Apply')]";
    //Time Related Links
    public static $mnuViewEmployeeTimeSheet = "//li[@id='time']/ul/li[1]//a[@class='timesheets']";
    public static $mnuViewEmployeeAttendanceRecord = "//li[@id='time']/ul/li[2]/ul/li[1]/a/span";
    public static $mnuViewAttendanceConfiguration = "//li[@id='time']/ul/li[2]/ul/li[2]/a/span";
    public static $mnuViewProjectReport = "//li[@id='time']/ul/li[3]/ul/li[1]/a/span";
    public static $mnuViewEmployeeReport = "//li[@id='time']/ul/li[3]/ul/li[2]/a/span";
    public static $mnuViewAttendanceSummary = "//li[@id='time']/ul/li[3]/ul/li[3]/a/span";
    public static $mnuPunchInPunchOut = "//li[@id='time']/ul/li[2]/ul/li[2]/a/span";
    public static $mnuViewEmployeeAttendanceRecordAsSupervisor = "//li[@id='time']/ul/li[2]/ul/li[3]/a/span";
    public static $mnuPunchMyAttendanceRecord = "//li[@id='time']/ul/li[2]/ul/li[1]/a/span";
    public static $mneViewAttendanceSummaryAsSupervisor = "//li[@id='time']/ul/li[3]/ul/li[2]/a/span";
    public static $mnuViewEmployeeReportAsSupervisor = "//li[@id='time']/ul/li[3]/ul/li[1]/a/span";
    //Admin module related Links
    public static $mnuViewOrganizationGeneralInformation = "//li[@id='admin']/ul/li[1]/ul/li[1]/a[@class='companyinfo']";
    public static $mnuViewOrganizationLocations = "//li[@id='admin']/ul/li[1]/ul/li[2]/a[@class='companyinfo']";
    public static $mnuViewCompanyStructure = "//li[@id='admin']/ul/li[1]/ul/li[3]/a[@class='companyinfo']";
    public static $mnuViewJobTitles = "//li[@id='admin']/ul/li[2]/ul/li[1]/a[@class='job']";
    public static $mnuViewPayGrades = "//li[@id='admin']/ul/li[2]/ul/li[2]/a[@class='job']";
    public static $mnuViewEmploymentStatus = "//li[@id='admin']/ul/li[2]/ul/li[3]/a[@class='job']";
    public static $mnuViewJobCategories = "//li[@id='admin']/ul/li[2]/ul/li[4]/a[@class='job']";
    public static $mnuViewWorkShifts = "//li[@id='admin']/ul/li[2]/ul/li[5]/a[@class='job']";
    public static $mnuViewQualificationSkills = "//li[@id='admin']/ul/li[3]/ul/li[1]/a[@class='qualifications']";
    public static $mnuViewQualificationEducation = "//li[@id='admin']/ul/li[3]/ul/li[2]/a[@class='qualifications']";
    public static $mnuViewQualificationLicenses = "//li[@id='admin']/ul/li[3]/ul/li[3]/a[@class='qualifications']";
    public static $mnuViewQualificationLanguages = "//li[@id='admin']/ul/li[3]/ul/li[4]/a[@class='qualifications']";
    public static $mnuViewMemberships = "//li[@id='admin']/ul/li[4]/a[@class='l2_link memberships']";
    public static $mnuViewNationalities = "//li[@id='admin']/ul/li[5]/a[@class='l2_link nationalities']";
    public static $mnuViewSystemUsers = "//id('wrapper')/div[2]/ul/li[1]/ul/li[6]/a ";
    public static $mnuEmailConfiguration = "//li[@id='admin']/ul/li[7]/ul/li[1]/a[@class='email']";
    public static $mnuEmailSubscribers = "//li[@id='admin']/ul/li[7]/ul/li[2]/a[@class='email']";
    public static $mnuViewCustomers = "//li[@id='admin']/ul/li[8]/ul/li[1]/a[@class='project']";
    public static $mnuViewProjects = "//li[@id='admin']/ul/li[8]/ul/li[2]/a[@class='project']";
    public static $mnuConfigureLocalizaion = "//li[@id='admin']/ul/li[9]/ul/li[1]/a[@class='configuration']";
    public static $config;


    public function __construct() {
        self::$config = new TestConfig();
        
    }

    /**
     *
     * @param FunctionalTestcase $selenium
     * @return EmployeeInformation 
     */
    //Admin Module Related Actions
    public static function goToOrganization_GeneralInformation(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuViewOrganizationGeneralInformation);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new EditGeneralInformationPageObject($selenium);
    }
 
    
    public static function goToOrganization_Locations(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuViewOrganizationLocations);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new ViewOrganizationLocationsPageObject($selenium);
    }

    public static function goToOrganization_Structure(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuViewCompanyStructure);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new ViewCompanyStructurePageObject($selenium);
    }

    public static function goToJob_JobTitles(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuViewJobTitles);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new ViewJobTitlesPageObject($selenium);
    }

    public static function goToJob_PayGrades(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuViewPayGrades);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new ViewPayGradePageObject($selenium);
    }

  

    public static function goToJob_WorkShifts(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuViewWorkShifts);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new ViewWorkShiftPageObject($selenium);
    }

   //Go to Admin-> User
    
    public static function goToUsers(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('menu_admin_viewSystemUsers').click();",
             'args' => array()));
        $selenium->selectFrame("relative=top");
        return new UsersListPage($selenium);
    }
    
        public static function goToEmployeeList(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('menu_pim_viewPimModule').click();",
             'args' => array()));
        $selenium->selectFrame("relative=top");
        return new UsersListPage($selenium);
    }
    
        public static function goToMemberships(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('menu_admin_membership').click();",
             'args' => array()));
        $selenium->selectFrame("relative=top");
        return new UsersListPage($selenium);
    }
    
            public static function goToNationalities(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('menu_admin_nationality').click();",
             'args' => array()));
        $selenium->selectFrame("relative=top");
        return new UsersListPage($selenium);
    }
    
        public static function goToJob_EmploymentStatus(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('menu_admin_employmentStatus').click();",
             'args' => array()));
        $selenium->selectFrame("relative=top");
        return new UsersListPage($selenium);
    }
    
           public static function goToJob_JobCategories(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('menu_admin_jobCategory').click();",
             'args' => array()));
        $selenium->selectFrame("relative=top");
        return new UsersListPage($selenium);
    }
    
               public static function goToQualification_Education(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('menu_admin_viewEducation').click();",
             'args' => array()));
        $selenium->selectFrame("relative=top");
        return new UsersListPage($selenium);
    }
    
                  public static function goToQualification_Licenses(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('menu_admin_viewLicenses').click();",
             'args' => array()));
        $selenium->selectFrame("relative=top");
        return new UsersListPage($selenium);
    }
    
        public static function goToQualification_Language(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('menu_admin_viewLanguages').click();",
             'args' => array()));
        $selenium->selectFrame("relative=top");
        return new UsersListPage($selenium);
    }
    
    
        public static function goToQualification_Skills(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('menu_admin_viewSkills').click();",
             'args' => array()));
        $selenium->selectFrame("relative=top");
        return new UsersListPage($selenium);
    }

    public static function goToEmailConfiguration(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuEmailConfiguration);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new EmailConfigurationPageObject($selenium);
    }

    public static function goToEmailSubscribe(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuEmailSubscribers);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new ViewEmailNotificationTypesPageObject($selenium);
    }

    public static function goToProjectInfo_Customers(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuViewCustomers);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new ViewCustomersPageObject($selenium);
    }

    public static function goToProjectInfo_Projects(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuViewProjects);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new ViewProjectsPageObject($selenium);
    }

    public static function goToLocalization(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->click(self::$mnuConfigureLocalizaion);
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        return new EditLocalizationPageObject($selenium);
    }

    //Leave Module Related Actions
    public static function goToLeaveModule(FunctionalTestcase $selenium) {
        
         $selenium->selectFrame();
         $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[0].click();",
             'args' => array())); 
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new LeavePeriodPageObject($selenium);
    }

    public static function goToConfigure_LeavePeriod(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[2].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new LeavePeriodPageObject($selenium);
    }

    public static function goToConfigure_LeaveTypes(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[3].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new LeaveTypePageObject($selenium);
    }

    public static function goToConfigure_WorkWeek(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[4].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new WorkingWeekPageObject($selenium);
    }

    public static function goToConfigure_Holidays(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[5].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new ViewHolidaysPageObject($selenium);
    }

    public static function goToLeaveSummary(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        try{
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[6].click();",
             'args' => array()));
        } catch(Exception $e){
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[1].click();",
             'args' => array()));
        }
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new ViewLeaveSummaryPageObject($selenium);
    }

    public static function goToLeaveList(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        try{
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[7].click();",
             'args' => array()));
        }
        catch(Exception $e){
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[2].click();",
             'args' => array()));
        }
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new ViewLeaveListPageObject($selenium);
    }

    public static function goToAssignLeave(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        try{
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[8].click();",
             'args' => array()));
        } catch(Exception $e){
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[3].click();",
             'args' => array()));
        }
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new AssignLeavePageObject($selenium);
    }

    public static function goToMyLeaveList(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        try{
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[4].click();",
             'args' => array()));
        } Catch(Exception $e){

        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[2].click();",
             'args' => array()));
        }
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new ViewMyLeaveListPageObject($selenium);
    }

    public static function goToApplyForLeave(FunctionalTestcase $selenium) {
        
        $selenium->selectFrame();
        try{
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[5].click();",
             'args' => array()));
        } catch(Exception $e)
        {
            $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('leave').getElementsByTagName('a');  elements[3].click();",
             'args' => array()));
        }
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new ApplyForLeavePageObject($selenium);
    }

//    public static function goToEmployeeList(FunctionalTestcase $selenium) {
//        $selenium->selectFrame();
//        try{
//        $selenium->session->execute(array(
//             'script' => "var elements = window.document.getElementById('pim').getElementsByTagName('a');  elements[7].click();",
//             'args' => array()));
//        } catch(Exception $e)
//        {
//            $selenium->session->execute(array(
//             'script' => "var elements = window.document.getElementById('pim').getElementsByTagName('a');  elements[1].click();",
//             'args' => array()));
//        }
//        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
//        $selenium->selectFrame("relative=top");
//        return new EmployeeInformation($selenium);
//    }

    public static function goToEssTab(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('ess').getElementsByTagName('a');  elements[0].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new PersonalDetails($selenium);
    }
    
     
    
    public static function goToVacancyList(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('recruit').getElementsByTagName('a');  elements[2].click();",
             'args' => array())); 
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new ViewVacancies($selenium);
    }
    
    
    
    

    public static function enableTaxExemptions(FunctionalTestcase $selenium) {
//
//        Helper::loginUser($selenium, "admin", "admin");
//
//        $selenium->session->execute(array(
//             'script' => "var elements = window.document.getElementById('pim').getElementsByTagName('a');  elements[2].click();",
//             'args' => array()));
//
//        $selenium->selectFrame("relative=top");
//        $selenium->click("btnSave");
//        if (!$selenium->isChecked("configPim_chkShowTax")) {
//            $selenium->click("configPim_chkShowTax");
//        }
//        $selenium->click("btnSave");
//        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
//        $selenium->selectFrame();
//        Helper::logOutIfLoggedIn($selenium);
//
//        return new Login($selenium);
    }

    /*     * public static function goToAddVacancy(FunctionalTestcase $selenium){
      $selenium->click(self::$mnuViewVacancies);
      $selenium->waitForPageToLoad(Config::$timeoutValue);
      $selenium->click(self::$btnAdd);
      $selenium->waitForPageToLoad(Config::$timeoutValue);
      return new AddVacancy($selenium);

      }* */

    public static function goToCandidateList(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('recruit').getElementsByTagName('a');  elements[1].click();",
             'args' => array()));
        
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new ViewCandidates($selenium);
    }

    //--Time Module Related Functions
    public static function goToEmployeeTimeSheet(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[2].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new viewEmployeeTimeSheet($selenium);
    }

    public static function goToEmployeeAttendanceRecords(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[4].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new EmployeeTimeSheet($selenium);
    }

    public static function goToAttendanceConfiguration(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[5].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new EmployeeTimeSheet($selenium);
    }

    public static function goToProjectReports(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[7].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new ProjectReportPageObject($selenium);
    }

    public static function goToEmployeeReports(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[8].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new EmployeeReport($selenium);
    }

    public static function goToEmployeeReportsAsSupervisor(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[9].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new EmployeeReport($selenium);
    }

    public static function goToAttendanceSummaryReport(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        
        
        if ($selenium->isElementPresent(self::$mnuViewAttendanceSummary)) {
            $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[9].click();",
             'args' => array()));
        } else {
            $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[10].click();",
             'args' => array()));
        }

        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new AttendaceSummaryReport($selenium);
    }

    public static function goToPunchInPunchOut(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        try{
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[6].click();",
             'args' => array()));
        } catch(Exception $e){
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[5].click();",
             'args' => array()));
        }
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new AddAttendanceRecord($selenium);
    }

    public static function goToPMyAttendanceRecord(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[5].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new ViewAttendanceRecord($selenium);
    }

    public static function goToEmployeeAttendanceRecordAsSupervisor(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('time').getElementsByTagName('a');  elements[7].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new ViewAttendanceRecord($selenium);
    }

    //--End Of Time Module Related Functions



    public static function enableOptionalFields() {

//        Helper::loginUser($selenium, "admin", "admin");
//
//        $selenium->session->execute(array(
//             'script' => "var elements = window.document.getElementById('pim').getElementsByTagName('a');  elements[2].click();",
//             'args' => array()));
//        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
//        $selenium->selectFrame("relative=top");
//        $selenium->click("btnSave");
//        $selenium->click("configPim_chkDeprecateFields");
//        $selenium->click("configPim_chkShowSSN");
//        $selenium->click("configPim_chkShowSIN");
//        $selenium->click("btnSave");
//
//        
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
    }

    public static function goToCustomField(FunctionalTestcase $selenium) {

        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('pim').getElementsByTagName('a');  elements[3].click();",
             'args' => array()));
        $selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
    }
    
     public static function goToEmployeeAttendanceRecords123(FunctionalTestcase $selenium) {
        $selenium->selectFrame();
        $selenium->session->execute(array(
             'script' => "var elements = window.document.getElementById('admin').getElementsByTagName('a');  elements[6].click();",
             'args' => array()));
        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
        $selenium->selectFrame("relative=top");
        return new AddJobTitlePageObject($selenium);
    }
    
//    public static function goToNewEmployeeList(FunctionalTestcase $selenium) {
//        echo '1';
//        $selenium->selectFrame();
//        echo '2';
//        try{
//            echo '3';
//        $selenium->session->execute(array('script' => "var elements = window.document.getElementById('pim').getElementsByTagName('a');  elements[7].click();",
//             'args' => array()));
//         echo '4';
//        } catch(Exception $e){
//             echo '5';
//        $selenium->session->execute(array(
//             'script' => "var elements = window.document.getElementById('pim').getElementsByTagName('a');  elements[7].click();",
//             'args' => array()));
//         echo '6';
//        }
//        //$selenium->waitForPageToLoad(self::$config->getTimeoutValue());
//        echo '7';
//        $selenium->selectFrame("relative=top");
//         echo '8';
//        return new EmployeeListPage($selenium);
//         echo '9';
//    }

}