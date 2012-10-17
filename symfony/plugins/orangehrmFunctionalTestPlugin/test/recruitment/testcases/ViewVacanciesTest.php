<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** TestCase Description
 * Description of ViewVacanciesTest
 *
 * @author madusani
 */
class ViewVacanciesTest extends FunctionalTestcase {

    private static $fixture;
    private static $isTablesLoaded;

    protected function setUp() {
        $menu = new Menu();
        $testConfig = new TestConfig();
        $helper = new Helper();

        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($testConfig->getBrowserURL());
        //Helper::deleteAllFromTable("ohrm_job_candidate"); 
        if (!self::$isTablesLoaded) {
            //self::$fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmRecruitmentPlugin/test/fixtures/CandidateDao.yml';
            // TestDataService::populate(self::$fixture);
            self::$isTablesLoaded = true;
        }
    }

//    public function testValidVacancySearch() {
//        
//        
//        
//                
//        RecruitmentHelper::loadRecruitmentPrerequisites($this);
//        $viewVacancy = new ViewVacancies($this);
//        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/ViewVacancyTest.yml";
//        $inputData = RecruitmentHelper::loadFixtureToInputArray($fixture, "TestValidsearch",$viewVacancy, "Input");
//        
//        
//        $employeeInformation = Helper::loginUser($this, 'admin', 'admin');        
//        $i=0;
//         foreach($inputData as $record){
//        
//        $section= null;
//        $viewVacancy = Menu::goToVacancyList($this);
//        
//        $viewVacancy->search($record);
//        foreach($record as $arrvalue){
//            if($arrvalue)
//            $section = $section . $arrvalue;
//            
//        }
//        
//        
//        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/ViewVacancyTest.yml";
//        //echo "\n\nThe section: " . $section . "\n\n";
//        $expected = RecruitmentHelper::loadFixtureToInputArray($fixture, $section,$viewVacancy, "Output");
//        
//        print_r($expected);
//        //$viewVacancies = new ViewVacancies($this);
//        //$viewVacancy->list->isRecordsPresentInList($expected);
//       // print_r($result);
//        $this->assertTrue($viewVacancy->list->isRecordsPresentInList($expected), "does not match");
//        $i++;
//         }
//         
//         
//        
//    }

    /** TestCase Description
     * 
     * <pre><br><b>Prerequisites:</b> Job Titles should be defined,
     *                Employees should be defined
     *                vacnacies should be added to the system          
     *
     * <br><b>steps:</b> Admin searches the job vacancies in the system
     *
     * <br><b>Outcome:</b> correct vacancies should be shown according to the differenet values given for the searchings
     * <br><b>Status:</b> Passing</pre>
     */
   
    //test case fails
    public function testVacancySearch() {
        //add vacancy and candidate records
        $vacanciesNeeded = array("Technical Assistant - Washington", "Technical Assistant - Washington2", "Technical Assistant - Washington3", "Technical Assistant - Washington4", "Technical Assistant - Washington5", "Technical Assistant - Washington6");
        $prerequisites = new VacancyPrerequisiteHandler($this);
        $prerequisites->ensurePrerequisites($vacanciesNeeded);

        //add search criterias
        $viewVacancy = new ViewVacancies($this);
        $fixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/recruitment/testdata/ViewVacancyTest.yml";
        $inputData = RecruitmentHelper::loadFixtureToInputArray($fixture, "TestValidsearch", $viewVacancy, "Input");


        Helper::loginUser($this, 'admin', 'admin');

        foreach ($inputData as $record) {

            //$section= null;
            $viewVacancy = Menu::goToVacancyList($this);

            $viewVacancy->search($record);
            $section = $record["ExpectedResult"];

            /* foreach($record as $arrvalue){
             * if($arrvalue)
             * $section = $section . $arrvalue;
              } */


            $expected = RecruitmentHelper::loadFixtureToInputArray($fixture, $section, $viewVacancy, "Output");


            $this->assertTrue($viewVacancy->list->isRecordsPresentInList($expected, true), "does not match");
        }

        Helper::logOutIfLoggedIn($this);
    }

}