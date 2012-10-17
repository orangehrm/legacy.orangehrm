<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** TestCase Description
 * 
 * Description of recruitmentFlowTest
 *
 * @author Faris
 */
class RecruitmentFlowTest extends FunctionalTestcase {

    private $fixture;

    public function setUp() {
        $helper = new Helper();
        $config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $browser = $config->getBrowserURL();
        $this->setBrowserUrl($browser);
        $this->fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/RecruitmentFlowData.yml";
    }

    public function testHappyPath() {
        // Rename the method name as "testHappyPathFlowOne"
        $employeeInformation = Helper::loginUser($this, 'admin', 'admin');
        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/RecruitmentFlowData.yml";
        //use the $this->fixture instead of $fixture
        $controller = new RecruitmentTestController($this, $fixture, "HappyPath");
        $this->assertTrue($controller->execute(), "Happy path failed");
        Helper::deleteAllFromTable("ohrm_job_candidate");
        //$prerequisite = new CandidatePrerequisiteHandler($this);        
        //$prerequisite->deletePrerequisite("Fathima");
    }

    
    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job titles should be defined,
     *                Employees should be deifned,
     *                Vacancies should be added to the system,
     *                Candidates should be added to the system
     * <br><b>steps:</b> Admin shortlists a candidate for a vacancy,
     *        Admin schedules an interview for the candidate with an interviewer,
     *        The interviewer / Hiring Manager edits the candidate's details
     * <br><b>Outcome:</b> The interviewer / hiring manager won't be able edit the candidate's details
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleInterviewerAndHiringManager_EditCandidateDetailsAndVerify() {
        
        $controller = new RecruitmentTestController($this, $this->fixture, "Path2");
        $this->assertTrue($controller->execute(), "Could not shortlist and schedule");

        Helper::loginUser($this, 'ashley', 'ashley');
        $viewCandidate = Menu::goToCandidateList($this);
        $editCandidate = $viewCandidate->list->clickOnCandidateListItem("Candidate", "Fathima Hasath Fernando");
        $this->assertFalse($editCandidate->isElementPresent("btnSave"), "A hiring manager of another vacancy can edit the candidate!");


        $CandidatedNeeded = array("Fathima");
        $prerequisite = new CandidatePrerequisiteHandler($this);
        $prerequisite->deletePrerequisite($CandidatedNeeded);
    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job titles should be defined,
     *                Employees should be deifned,
     *                Vacancies should be added to the system,
     *                Candidates should be added to the system
     * <br><b>steps:</b> Admin shortlists a candidate for a vacancy,
     *        Admin schedules an interview for the candidate with an interviewer,
     *        The interviewer  edits the candidate's details
     * <br><b>Outcome:</b> The interviewer  won't be able edit the candidate's details
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleInterviewer_EditCandidateDetailsAndVerify() {
        
        $controller = new RecruitmentTestController($this, $this->fixture, "ShortListAndScheduleInterview");
        $this->assertTrue($controller->execute(), "ShortList And Schedule Interview failed");

        Helper::loginUser($this, 'johnde', 'johnde');
        $viewCandidate = Menu::goToCandidateList($this);
        $editCandidate = $viewCandidate->list->clickOnCandidateListItem("Candidate", "Fathima Hasath Fernando");
        $this->assertFalse($editCandidate->isElementPresent("btnSave"), "Path3 failed");

        $CandidatedNeeded = array("Fathima");
        $prerequisite = new CandidatePrerequisiteHandler($this);
        $prerequisite->deletePrerequisite($CandidatedNeeded);
    }

    public function testUserRoleAdmin_DeleteCandidateAndVerify() {
        
        $controller = new RecruitmentTestController($this, $this->fixture, "DeleteCandidate");
        $this->assertTrue($controller->execute(), "Path4 failed");
    }


    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job titles should be defined,
     *                Employees should be deifned,
     *                Vacancies should be added to the system,
     *                Candidates should be added to the system
     * <br><b>steps:</b> Admin shortlists a candidate for a vacancy,
     *        Admin schedules an interview for the candidate with an interviewer,
     *        The interviewer deletes the candidate
     * <br><b>Outcome:</b> The interviewer won't be able delete the candidate
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleInterviewer_DeleteCandidateAndVerify() {
        
        $controller = new RecruitmentTestController($this, $this->fixture, "ShortListAndScheduleInterview");
        $this->assertTrue($controller->execute(), "Path5 failed");

        $personalDetails = Helper::loginUser($this, 'johnde', 'johnde');
        $viewCandidate = Menu::goToCandidateList($this);
        $this->assertFalse($viewCandidate->isElementPresent("btnDelete"), "Path5 failed");

        $CandidatedNeeded = array("Fathima");
        $prerequisite = new CandidatePrerequisiteHandler($this);
        $prerequisite->deletePrerequisite($CandidatedNeeded);
    }

    public function testUserRoleAdmin_MarkFirstInterviewFailAndVerify() {

        

        $employeeInformation = Helper::loginUser($this, 'admin', 'admin');
        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/RecruitmentFlowData.yml";
        $controller = new RecruitmentTestController($this, $fixture, "MarkFirstInterviewFail");
        $this->assertTrue($controller->execute(), "Mark First Interview Fail failed");

        $CandidatedNeeded = array("Fathima");
        $prerequisite = new CandidatePrerequisiteHandler($this);
        $prerequisite->deletePrerequisite($CandidatedNeeded);
    }

    public function testUserRoleInterviewer_MarkSecondInterviewFailAndVerify() {

        
        //$fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/RecruitmentFlowData.yml";
        $controller = new RecruitmentTestController($this, $this->fixture, "MarkSecondInterviewFail");
        $this->assertTrue($controller->execute(), "Mark Second Interview Fail failed");

        $CandidatedNeeded = array("Fathima");
        $prerequisite = new CandidatePrerequisiteHandler($this);
        $prerequisite->deletePrerequisite($CandidatedNeeded);
    }

    public function testUserRoleAdmin_MarkOfferDeclinedAndVerify() {

        
        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/RecruitmentFlowData.yml";
        $controller = new RecruitmentTestController($this, $fixture, "MarkOfferDeclined");
        $this->assertTrue($controller->execute(), "Mark Offer Declined failed");

        $CandidatedNeeded = array("Fathima");
        $prerequisite = new CandidatePrerequisiteHandler($this);
        $prerequisite->deletePrerequisite($CandidatedNeeded);
    }

    public function testUserRoleAdmin_RejectsCandidateAndverify() {

        
        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/RecruitmentFlowData.yml";
        $controller = new RecruitmentTestController($this, $fixture, "RejectCandidate");
        $this->assertTrue($controller->execute(), "Reject Candidate failed");

        $CandidatedNeeded = array("Fathima");
        $prerequisite = new CandidatePrerequisiteHandler($this);
        $prerequisite->deletePrerequisite($CandidatedNeeded);
    }

    public function testUserRoleAdmin_RejectsCandidateAfterShortListingAndverify() {
        
        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/RecruitmentFlowData.yml";
        $controller = new RecruitmentTestController($this, $fixture, "ShortListAndReject");
        $this->assertTrue($controller->execute(), "ShortList And Reject failed");

        $CandidatedNeeded = array("Fathima");
        $prerequisite = new CandidatePrerequisiteHandler($this);
        $prerequisite->deletePrerequisite($CandidatedNeeded);
    }

    //PROblem
    public function testUserRoleAdmin_RejectsCandidateAfterOfferingJobAndverify() {
        
        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/RecruitmentFlowData.yml";
        $controller = new RecruitmentTestController($this, $fixture, "OfferJobAndReject");
        $this->assertTrue($controller->execute(), "OfferJob And Reject failed");

        $CandidatedNeeded = array("Fathima");
        $prerequisite = new CandidatePrerequisiteHandler($this);
        $prerequisite->deletePrerequisite($CandidatedNeeded);
    }

   public function testUserRoleAdmin_DeleteCandidateAndVerify2() {
        
        $controller = new RecruitmentTestController($this, $this->fixture, "DeleteCandidateTest");
        $this->assertTrue($controller->execute(), "Path4 failed");
    } 
    
   public function testaddCandidate() {
        
        $controller = new RecruitmentTestController($this, $this->fixture, "AddCandidateTest");
        $this->assertTrue($controller->execute(), "Add Candidate Test fails");
    } 


}