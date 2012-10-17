<?php

/** TestCase Description
 * 2011-06-23
 */
class ViewCandidatesTest extends FunctionalTestcase {

    //private static $isTablesLoaded;
    private static $fixture;

    protected function setUp() {
        $testConfig = new TestConfig();
        $menu = new Menu();
        $helper = new Helper();

        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($testConfig->getBrowserURL());
        //Helper::deleteAllFromTable("ohrm_job_candidate"); 
        /* if(!self::$isTablesLoaded){
          self::$fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmRecruitmentPlugin/test/fixtures/CandidateDao.yml';
          TestDataService::populate(self::$fixture);
          self::$isTablesLoaded = true;

          } */
    }



    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job Titles should be defined,
     *                Employees should be defined
     *                vacancies should be added to the system
     *                candidates should be added to the system
     * <br><b>steps:</b> Admin searches the candidates in the system
     *          
     *
     * <br><b>Outcome:</b> correct candidates should be shown according to the differenet values given for the searchings
     * <br><b>Status:</b> Passing</pre>
     */
    //test case fails
    //resumes are not getting attached in candidate 
    public function testValidCandidateSearch() {


        $vacanciesNeeded = array("Technical Assistant - Washington", "Technical Assistant - Washington2", "Technical Assistant - Washington3", "Technical Assistant - Washington4", "Technical Assistant - Washington5", "Technical Assistant - Washington6");
        $prerequisites = new VacancyPrerequisiteHandler($this);
        $prerequisites->ensurePrerequisites($vacanciesNeeded);

        $candidatesNeeded = array("Iresha", "Fathima", "Emma", "Amila", "Chamila", "Chinthani", "Hiroo");
        $prerequisites = new CandidatePrerequisiteHandler($this);
        $prerequisites->ensurePrerequisites($candidatesNeeded);



        $viewCandidate = new ViewCandidates($this);
        $fixture = sfConfig::get('sf_plugins_dir') . "//orangehrmFunctionalTestPlugin//test//recruitment//testdata//ViewCandidateTest.yml";
        $inputData = RecruitmentHelper::loadFixtureToInputArray($fixture, "TestValidsearch", $viewCandidate, "Input");

        $employeeInformation = Helper::loginUser($this, 'admin', 'admin');
        //$i = 0;
        //$expected = sfYaml::load($fixture);
        //reset($inputData);
        //for($i=0; $i<count($inputData); $i++){
        //$record = current($inputData);

        foreach ($inputData as $record) {

            $viewCandidates = Menu::goToCandidateList($this);
            $viewCandidates->search($record);
            $section = $record["ExpectedResult"];
            //echo "Section " . $record["ExpectedResult"];
            //$expectedValueSection = key($inputData);

            $expected = RecruitmentHelper::loadFixtureToInputArray($fixture, $section, $viewCandidates, "Output");


            $this->assertTrue($viewCandidates->list->isRecordsPresentInList($expected, true), "does not match");

            //$i++;
            //next($inputData);
        }
        Helper::logOutIfLoggedIn($this);
    }

//    public function testInvalidSearch() {
//
//        //RecruitmentHelper::loadRecruitmentPrerequisites($this);
//        //RecruitmentHelper::addPrerequisiteCandidates($this);
//
//        $viewCandidate = new ViewCandidates($this);
//
//        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/ViewCandidateTest.yml";
//        $inputData = RecruitmentHelper::loadFixtureToInputArray($fixture, "TestInvalidsearch", $viewCandidate, "Input");
//        //print_r($inputData);
//
//        $employeeInformation = Helper::loginUser($this, 'admin', 'admin');
//        $i = 0;
//        foreach ($inputData as $record) {
//            //print_r($record);
//            $viewCandidates = Menu::goToViewCandidate($this);
//            $viewCandidates->search($record);
//
//            $validations = $viewCandidate->getArrayOfValidationMessages();
//            //print_r($validations);
//
//            if ($record[$viewCandidate->cmbCandidateName]) {
//                $searchFound = is_string(array_search("Enter valid candidate name", $validations, "Candidate Name validation failed for" . $record[$viewCandidate->cmbCandidateName]));
//                $this->assertTrue($searchFound);
//            }
//
//            if (($record[$viewCandidate->txtFromDate]) && (!$record[$viewCandidate->txtToDate])) {
//                $searchFound = is_string(array_search("Please enter a valid date in YYYY-MM-DD format", $validations, "Candidate Name validation failed for" . $record[$viewCandidate->cmbCandidateName]));
//                $this->assertTrue($searchFound);
//            }
//
//            if ((!$record[$viewCandidate->txtFromDate]) && ($record[$viewCandidate->txtToDate])) {
//                $searchFound = is_string(array_search("Please enter a valid date in YYYY-MM-DD format", $validations, "Candidate Name validation failed for" . $record[$viewCandidate->cmbCandidateName]));
//                $this->assertTrue($searchFound);
//            }
//
//            if (($record[$viewCandidate->txtFromDate]) && ($record[$viewCandidate->txtToDate])) {
//                $searchFound = is_string(array_search("From date should be less than To date", $validations, "Candidate Name validation failed for" . $record[$viewCandidate->cmbCandidateName]));
//                $this->assertTrue($searchFound);
//            }
//        }
//    }
//    public function testSearch() {
//        echo "printing loading fixture: \n";
//        print_r(sfYaml::load(self::$fixture));
//    }
}