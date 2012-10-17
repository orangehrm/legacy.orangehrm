<?php

class AddVacancyTest extends FunctionalTestcase {

    public static $loadedFixture;
    public static $isTablesLoaded;

    public function setUp() {

        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());

        if (!self::$isTablesLoaded) {

            self::$isTablesLoaded = true;
        }
    }

    

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job Titles should be defined,
     *                Employees should be defined
     * <br><b>steps:</b> Admin adds a job vacancy,
     *        Admin verifies the added job vacancy in vacancy list
     *          
     * <br><b>Outcome:</b> Added job vacancy should be displayed in the vacancy list
     * <br><b>Status:</b> Passing</pre>
     */
    public function testAddJobVacanciesAndVerify() {

        $vacanciesNeeded = array("Technical Assistant - Washington");
        $prerequisites = new VacancyPrerequisiteHandler($this);
        $prerequisites->ensurePrerequisites($vacanciesNeeded);
        Helper::loginUser($this, 'admin', 'admin');



        $vacancyFilterObjectOne = new AddVacancyFilter("Engineering Manager", "Development Manager", "Kayla Abbey");
        $vacancyFilterObjectOne->vacancyOptionalFields(2, NULL, yes);

        $vacancyFilterObjectTwo = new AddVacancyFilter("Engineering Manager", "QA Manager", "Ashley Abel");
        $vacancyFilterObjectTwo->vacancyOptionalFields(2, NULL, yes);

        $viewVacancy = Menu::goToVacancyList($this);
        $addVacancyPageObject = $viewVacancy->goToAddVacancy();
        $addVacancyPageObject->saveVacancy($vacancyFilterObjectOne);

        $viewVacancy = Menu::goToVacancyList($this);
        $addVacancyPageObject = $viewVacancy->goToAddVacancy();
        $addVacancyPageObject->saveVacancy($vacancyFilterObjectTwo);

        $viewVacancy = Menu::goToVacancyList($this);

        if ($record[$addVacancy->chkActive] == 'no') {
            $vacancyName = $record[$addVacancy->txtVacancyName] . " " . "(Closed)";
            $expected[0] = array("Vacancy" => $vacancyName);
            $this->assertTrue($viewVacancy->list->isRecordsPresentInList($expected), $record[$addVacancy->txtVacancyName] . " did not get saved");
        }
        if ($record[$addVacancy->chkActive] == 'yes') {
            $vacancyName = $record[$addVacancy->txtVacancyName];
            $expected[0] = array("Vacancy" => $vacancyName);
            $this->assertTrue($viewVacancy->list->isRecordsPresentInList($expected), $record[$addVacancy->txtVacancyName] . " did not get saved");
        }


        $viewVacancy = Menu::goToVacancyList($this);
        $VacancyViewPage = new VacancyViewPage($this);
        $VacancyViewPage->vacancyListComponent->selectAllInTheList();

        $viewVacancy->deleteAllVancancies();


        Helper::logOutIfLoggedIn($this);
    }


    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job Titles should be defined,
     *                Employees should be defined
     *                vacancies should be added to the system
     * <br><b>steps:</b> Admin adds a job vacancy,
     *        Admin edits the job vacancy,
     *        Admin verifies the edited job vacancy in vacancy list
     *
     * <br><b>Outcome:</b> Edited job vacancy should be displayed in the vacancy list with the edited values
     * <br><b>Status:</b> Passing</pre>
     */
    //Fixed Mushthaq
    public function testEditJobVacancyAndVerify() {
        $vacanciesNeeded = array("Technical Assistant - Washington");
        $prerequisites = new VacancyPrerequisiteHandler($this);
        $prerequisites->ensurePrerequisites($vacanciesNeeded);
        Helper::loginUser($this, 'admin', 'admin');
        $viewVacancy = Menu::goToVacancyList($this);
        $addVacancy = $viewVacancy->list->clickOnVacancyListItem("Vacancy", "Technical Assistant - Washington");
        $vacancyObject2 = new AddVacancyFilter("Supervisor", "Technical Assistant - Washington", "Ashley Abel");
        $vacancyObject2->vacancyOptionalFields("2", "Fun with working");
        $addVacancy->editVacancy($vacancyObject2);
        $this->assertEquals("Successfully Saved", $addVacancy->getSavedSuccessfullyMessage());

        Helper::logOutIfLoggedIn($this);

        $prerequisites->deletePrerequisite($vacanciesNeeded);
    }


    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job Titles should be defined,
     *                Employees should be defined
     *                vacancies should be added to the system
     * <br><b>steps:</b> Admin adds a job vacancy,
     *        Admin verifies the added job vacancy in the vacancy web page
     *
     * <br><b>Outcome:</b> Added job vacancy should be displayed in the vacancy web page
     * <br><b>Status:</b> Passing</pre>
     */
    //Fixed Mushthaq
    public function testAddedVacancyInWebPageAndRssFeed() {
        //$addVacancy = new AddVacancy($this);
        //$fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/AddVacancyTest.yml";
        //$inputData = RecruitmentHelper::loadFixtureToInputArray($fixture, "ValidVacancies", $addVacancy, "Input");

        $vacanciesNeeded = array("Technical Assistant - Washington");
        $prerequisites = new VacancyPrerequisiteHandler($this);
        $prerequisites->ensurePrerequisites($vacanciesNeeded);


        $page = new FootnoteComponent($this);

        $page->loadURLOfPage("Web");
        
        $this->assertTrue($page->getVacancyIsPresent("Web"));
        //$page->loadURLOfPage("Rss");
        //$this->assertTrue($page->getVacancyIsPresent("Rss"));
    }


    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job Titles should be defined,
     *                Employees should be defined
     *                vacancies should be added to the system
     * <br><b>steps:</b> Admin adds a job vacancy,
     *        Admin Deletes the added job vacancy
     *          
     *
     * <br><b>Outcome:</b> A message should be appear as Successfully Deleted
     * <br><b>Status:</b> Passing</pre>
     */
    //Fixed Mushthaq
    public function testDeleteJobVacancy() {

        $vacanciesNeeded = array("Technical Assistant - Washington", "Technical Assistant - Washington2");
        $prerequisites = new VacancyPrerequisiteHandler($this);
        $prerequisites->ensurePrerequisites($vacanciesNeeded);
        Helper::loginUser($this, 'admin', 'admin');
        $viewVacancy = Menu::goToVacancyList($this);
        $viewVacancy->deleteOneVacancy("Vacancy", "Technical Assistant - Washington");
        $this->assertEquals("Successfully Deleted", $viewVacancy->getSavedSuccessfullyMessage());
        Helper::logOutIfLoggedIn($this);
    }


    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job Titles should be defined,
     *                Employees should be defined,
     *                vacancies should be added to the system
     *                candidates should be added to the system
     * <br><b>steps:</b> System will load the vacancy web page,
     *        A candidate will apply for a vacancy,
     *        Admin will verify whether the applied candidate is displayed in the system
     *
     * <br><b>Outcome:</b> The candidate should be displayed in the candidate list
     * <br><b>Status:</b> Passing</pre>
     */
    //Fixes Mushthaq
    public function testCandidateApplyThroughWebForm() {

        Helper::loginUser($this, 'admin', 'admin');

        $viewVacancy = Menu::goToVacancyList($this);
        $VacancyViewPage = new VacancyViewPage($this);
        $VacancyViewPage->vacancyListComponent->selectAllInTheList();
        $viewVacancy->deleteAllVancancies();
        Helper::logOutIfLoggedIn($this);

        $vacanciesNeeded = array("Technical Assistant - Washington");
        $prerequisites = new VacancyPrerequisiteHandler($this);
        $prerequisites->ensurePrerequisites($vacanciesNeeded);



        Helper::deleteAllFromTable("ohrm_job_candidate");
        $candidateObject = new AddCandidateFilter("Janaka", "Silva", "janak@yahoo.com");
        $candidateObject->candidateOptionalOfficialFields("Technical Assistant", sfConfig::get('sf_root_dir') . "\\plugins\\orangehrmFunctionalTestPlugin\\test\\recruitment\\testdata\\data.docx");

        $page = new FootnoteComponent($this);
        $page->loadURLOfPage("Web");
        
        //rename add candidate as $webform
        $addCandidate = $page->applyForVacancy($this);
        //create another component class WebForm and call method of that class
        $addCandidate->saveCandidate($candidateObject);
        //getSavedSuccessfullyMessage method should be in WebForm component not not in FootnoteComponent
        $actualMessage = $page->getSavedSuccessfullyMessage();
        $this->assertEquals("Application Received", $actualMessage);
        Helper::loginUser($this, 'admin', 'admin');
        $viewCandidate = Menu::goToCandidateList($this);
        

        $this->assertTrue($viewCandidate->list->isItemPresentInColumn("Candidate", "Janaka  Silva"));
        Helper::logOutIfLoggedIn($this);
        Helper::deleteAllFromTable("ohrm_job_candidate");
    }




    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job Titles should be defined,
     *                Employees should be defined,
     *                vacancies should be added to the system
     *                candidates should be added to the system
     * <br><b>steps:</b> Admin shortlists a candidate for a vacancy,
     *        Admin closes / inactivates the vacancy,
     *        Admin schedules an interview to the shortlisted candidate for the closed vacancy
     *
     * <br><b>Outcome:</b> Admin won't be able to schedule an interview because the vacancy is closed
     * <br><b>Status:</b> Passing</pre>
     */
    //Fixed Mushthaq
    public function testCheckIfCandidateCanBeScheduledForClosedVacancy() {

        //adding a vacancy
        $vacanciesNeeded = array("Technical Assistant - Washington");
        $prerequisitesVacancy = new VacancyPrerequisiteHandler($this);
        $prerequisitesVacancy->ensurePrerequisites($vacanciesNeeded);
        //adding a vacancy
        //adding a candidate
        $candidatesNeeded = array("Emma");
        $prerequisitesCandidate = new CandidatePrerequisiteHandler($this);
        $prerequisitesCandidate->ensurePrerequisites($candidatesNeeded);
        //adding a candidate
        //shortlisting change the fixture thing into normal parameter passing
        Helper::loginUser($this, 'admin', 'admin');
        $viewCandidate = Menu::goToCandidateList($this);
        $editCandidate = $viewCandidate->list->clickOnCandidateListItem("Candidate", "Emma Hayly Watson");
        $shortlistPage = $editCandidate->editStatus("Shortlist");        
        $shortlistPage->shortlistTheCandidate("ShortListed");
        $shortlistPage->clickBackBtn();
        //shortlisting

        
        $viewVacancy = Menu::goToVacancyList($this);
        $viewVacancy->list->clickOnVacancyListItem("Vacancy", "Technical Assistant - Washington");
        $editVacancy = new EditVacancy($this);
        $editVacancy->clickOnActive();
        $viewCandidate = Menu::goToCandidateList($this);
        $editCandidate = $viewCandidate->list->clickOnCandidateListItem("Candidate", "Emma Hayly Watson");
        $this->assertFalse($editCandidate->editStatus("No Actions"));



        Helper::logOutIfLoggedIn($this);

        $sample = array("Technical Assistant - Washington (Closed)");
        $prerequisitesVacancy->deletePrerequisite($sample);
        $prerequisitesCandidate->deletePrerequisite($candidatesNeeded);
    }


    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Hiring manager should be defined
     * <br><b>steps:</b> Hiring Manager goes to the add vacancy form
     *
     *
     * <br><b>Outcome:</b> Hiring Manager won't be able to access the vacancy page
     * <br><b>Status:</b> Passing</pre>
     */
    public function testUserRoleHiringManager_AddJobVacancy() {
        Helper::loginUser($this, 'kayla', 'kayla');
        $this->assertFalse($this->isElementPresent(Menu::$mnuViewVacancies));
        Helper::logOutIfLoggedIn($this);
    }

    
   
    
    
}