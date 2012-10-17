<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeInformationTest
 *
 * @author chinthani
 */

//INCORRECT
class EmployeeInformationTest extends FunctionalTestcase {
    
    public static function setUpBeforeClass() {
        echo 'test1';
        $prerequisites = new NewPimPrerequisiteHandler();
        echo 'test2';
        $prerequisites->ensurePrerequisites("NewPIMPrerequisites.yml");
        echo 'test3';
        //print_r($prerequisites);
    }
    
    public function setUp() {
        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
		
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());
        
    }
     public function testSearchCombination(){
                     Helper::loginUser($this, "admin", "admin");
          //  Menu::goToViewTraining($this);
            $trainingInfo = new EmployeeInformationPage($this);
            $searchTesterYML = sfYaml::load(sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/newpim/testdata/EmployeeList.yml");
            
            $criteria = $searchTesterYML["SearchCriteria"];
            $results = $searchTesterYML["Results"];
            foreach($criteria as $criterion){
                $trainingInfo->searchBy($criterion["Field"], $criterion["Value"]);
                $expected[0] = $results[$criterion["TestName"]];
                
                $this->assertTrue($trainingInfo->getEmployeList()->isRecordsPresentInList($expected, $true));
            }
            
            
        }
}

?>
