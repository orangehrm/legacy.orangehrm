<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//
//PAGE: SEARCH

    
    



//
//............TEST
//        public function testSearchEmployeebyName1(){     
//        $viewEmployee = new EmployeeListPage($this);
//        $fixture = sfConfig::get('sf_plugins_dir') . "//orangehrmFunctionalTestPlugin//test//newpim//testdata//EmployeeList.yml";
//        $section1 = $record["Testsearch"];
//        $inputData = Helper::loadFixtureToInputArray($fixture, $section1, $viewEmployee, "Input");
//        print_r($inputData);
//        $employeeInformation = Helper::loginUser($this, 'admin', 'admin');
//        foreach ($inputData as $record) {
//            $viewEmployee = new EmployeeListPage($this);
//            $viewEmployee->search2($record);
//            $section = $record["ExpectedResult"];
//            $expected = Helper::loadFixtureToInputArray($fixture, $section, $viewEmployee, "Output");
//            $this->assertTrue($viewEmployee->list->isRecordsPresentInList($expected, true), "No Records Found");        
//        }
//       Helper::logOutIfLoggedIn($this);
//
//                
//        
//    }
//        public function testSearchEmployeebyName1(){     
//        $viewEmployee = new EmployeeListPage($this);
//        $fixture = sfConfig::get('sf_plugins_dir') . "//orangehrmFunctionalTestPlugin//test//newpim//testdata//EmployeeList.yml";
//        $section1 = $record["Testsearch"];
//        $inputData = Helper::loadFixtureToInputArray($fixture, $section1, $viewEmployee, "Input");
//        print_r($inputData);
//        $employeeInformation = Helper::loginUser($this, 'admin', 'admin');
//        foreach ($inputData as $record) {
//            $viewEmployee = new EmployeeListPage($this);
//            $viewEmployee->search2($record);
//            $section = $record["ExpectedResult"];
//            $expected = Helper::loadFixtureToInputArray($fixture, $section, $viewEmployee, "Output");
//            $this->assertTrue($viewEmployee->list->isRecordsPresentInList($expected, true), "No Records Found");        
//        }
//      Helper::logOutIfLoggedIn($this);
//        }
     

    
//     public function testSearchEmployeebyEmploymentStatus(){
//        
//        
//        Helper::loginUser($this, "admin", "admin");
//        $searchEmployee = new EmployeeListPage($this);
//        $searchEmployee->search(NULL, NULL, "Full Time Permanent", NULL, NULL, NULL, NULL);   
//        $expected = array("Full Time Permanent");
//        $this->assertTrue($searchEmployee->getEmployeeList()->isOnlyItemsListed($expected, "Employment Status"));
//        
//        
//        Helper::logOutIfLoggedIn($this);
//    }
    
//    public function testSearchEmployeebyJobTitle(){
//        
//        Helper::loginUser($this, "admin", "admin");
//        $searchEmployee = new EmployeeListPage($this);
//        $searchEmployee->search(NULL, NULL, NULL, NULL, NULL, "CEO", NULL);
//        $expected = array("0002");
//        $this->assertTrue($searchEmployee->getEmployeeList()->isOnlyItemsListed($expected, "Id")); 
//        Helper::logOutIfLoggedIn($this);
//    }
    
    
        
//     public function testSearchEmployeebyTerminate(){
//        
//       
//        Helper::loginUser($this, "admin", "admin");
//        $searchEmployee = new EmployeeListPage($this);
//        $searchEmployee->search(NULL, NULL, NULL, "Past Employees Only", NULL, NULL, NULL);   
//        $expected = array("Employee (Past Employee)");
//        $this->assertTrue($searchEmployee->getEmployeeList()->isOnlyItemsListed($expected, "Last Name"));
//        Helper::logOutIfLoggedIn($this);
//    }
//    
    
//    public function testSearchEmployeebySubUnit(){
//        
//        
//        Helper::loginUser($this, "admin", "admin");
//        $searchEmployee = new EmployeeListPage($this);
//        $searchEmployee->search(NULL, NULL, NULL, NULL, NULL, NULL, "Software Development Department");
//        $expected = array("Software Development Department");
//        $this->assertTrue($searchEmployee->getEmployeeList()->isOnlyItemsListed($expected,"Sub Unit"));      
//        Helper::logOutIfLoggedIn($this);
//    }
    
    
    
    
    
//    NEWPrereqisit
//    
    #EthnicRace:
 # EthnicRace_ETH001: { ethnic_race_desc: 'EthnicRace 01' }

#Organization:
 # CompanyGeninfo_001: { code: '001', geninfo_keys: COMPANY|COUNTRY|STREET1|STREET2|STATE|CITY|ZIP|PHONE|FAX|TAX|NAICS|COMMENTS, geninfo_values: OrangeHRM|LK|||||||||| }

#JobSpecificationAttachment:
 # JobSpecifications_1: { jobspec_id: 1, jobspec_name: JS01, jobspec_desc: '', jobspec_duties: '' }
 # JobSpecifications_2: { jobspec_id: 2, jobspec_name: JS02, jobspec_desc: '', jobspec_duties: '' }
 # JobSpecifications_3: { jobspec_id: 3, jobspec_name: JS03, jobspec_desc: '', jobspec_duties: '' }
 # JobSpecifications_4: { jobspec_id: 4, jobspec_name: JS04, jobspec_desc: '', jobspec_duties: '' }

#JobCategory:
#  JobCategory_001: {id: 001, name: 'OFFICIALS AND ADMINISTRATORS'}
#  JobCategory_002: {id: 002, name: 'PROFESSIONALS'}
#  JobCategory_003: {id: 003, name: 'TECHNICIANS'}
#  JobCategory_004: {id: 004, name: 'PROTECTIVE SERVICE WORKERS'}
#  JobCategory_005: {id: 005, name: 'PARAPROFESSIONALS'}
#  JobCategory_006: {id: 006, name: 'ADMINISTRATIVE SUPPORT'}
#  JobCategory_007: {id: 007, name: 'SKILLED CRAFT WORKERS'}
#  JobCategory_008: {id: 008, name: 'SERVICE-MAINTENANCE'}

#PayGrade:
#  SalaryGrade_SAL001: { id: 001, name: 'Grade A' }

#PayGradeCurrency:
#  SalaryCurrencyDetail_SAL001_LKR: { pay_grade_id: 001, currency_id: LKR, min_salary: '15000', max_salary: '65000' }
#  SalaryCurrencyDetail_SAL001_USD: { pay_grade_id: 001, currency_id: USD, min_salary: '2000',  max_salary: '10000' }

    #Nationality:
#  Nationality_NAT001: { id: 001, name: 'Nationality 01' }
#
#Education:
#  Education_EDU001: { id: 001, name: BICT }
#  Education_EDU002: { id: 002, name: BIT }
#
#License:
#  Licenses_LIC001: { id: 001, name: 'BCS License' }
#  Licenses_LIC002: { id: 002, name: 'Driving License' }

#MembershipType:
 # MembershipType_MEM001: { membershipTypeCode: MEM001, membershipTypeName: Social }
 # MembershipType_MEM002: { membershipTypeCode: MEM002, membershipTypeName: Sport }

#Membership:
#  Membership_MME001: { id: 001, name: 'Lions Club' }
#  Membership_MME002: { id: 002, name: SCC }
#
#Skill:
#  Skill_SKI001: { id: 001, name: Management, description: 'Management Skills' }
#  Skill_SKI002: { id: 002, name: 'AI Programming', description: 'AI Programming' }
#
#Language:
#  Language_LAN001: { id: 001, name: 'English' }
#  Language_LAN002: { id: 002, name: 'Sinhala' }
    
    #EmployeeEducation:
#  EmpEducation1: { id: 1, emp_number: 1, education_id: '001', major: 'Data base Management', year: '2003', score: '3.4', start_date: '2000-01-01', end_date: '2004-01-01'}
#  EmpEducation2: { id: 2, emp_number: 1, education_id: '002', major: 'Programming', year: '2006', score: '2.5', start_date: '2006-01-01', end_date: '2008-01-01'}
#  EmpEducation3: { id: 3, emp_number: 3, education_id: '001', major: 'Data base Management', year: '2003', score: '3.4', start_date: '2000-01-01', end_date: '2004-01-01'}
#  EmpEducation4: { id: 4, emp_number: 3, education_id: '002', major: 'Programming', year: '2006', score: '2.5', start_date: '2006-01-01', end_date: '2008-01-01'}
#
#EmployeeSkill:
#  EmpSkills1: {emp_number: 1, skill_id: '001', years_of_exp: '2', comments: 'Strategic management'}
#  EmpSkills2: {emp_number: 1, skill_id: '002', years_of_exp: '3', comments: 'Artificial Intelligence with Robotics'}
#  EmpSkills3: {emp_number: 3, skill_id: '001', years_of_exp: '2', comments: 'Strategic management'}
#  EmpSkills4: {emp_number: 3, skill_id: '002', years_of_exp: '3', comments: 'Artificial Intelligence with Robotics'}
#
#EmployeeLicense:
#  EmpLicense1: {emp_number: 1, license_id: '001', license_no: '	1234567SA', license_issued_date: '2010-01-01', license_expiry_date: '2012-12-31'}
#  EmpLicense2: {emp_number: 1, license_id: '002', license_no: '	67891022A', license_issued_date: '2011-01-01', license_expiry_date: '2012-12-31'}
#  EmpLicense3: {emp_number: 3, license_id: '001', license_no: '	1234567SA', license_issued_date: '2010-01-01', license_expiry_date: '2012-12-31'}
#  EmpLicense4: {emp_number: 3, license_id: '002', license_no: '	67891022A', license_issued_date: '2011-01-01', license_expiry_date: '2012-12-31'}
#  EmpLicense5: {emp_number: 4, license_id: '001', license_no: '	1234567SA', license_issued_date: '2010-01-01', license_expiry_date: '2012-12-31'}
#  EmpLicense6: {emp_number: 4, license_id: '002', license_no: '	67891022A', license_issued_date: '2011-01-01', license_expiry_date: '2012-12-31'}

    #EmpDependent:
#  EmpDependent_4_1: { emp_number: 4, seqno: '1', name: 'Morgen Grimes', relationship_type: child, relationship: '', date_of_birth: '2001-01-02' }
#  EmpDependent_4_2: { emp_number: 4, seqno: '2', name: Beckmen, relationship_type: other, relationship: Uncle, date_of_birth: '1963-06-05' }
#  EmpDependent_4_3: { emp_number: 4, seqno: '3', name: Alex, relationship_type: child, relationship: '', date_of_birth: '2001-06-07' }
#
#EmpEmergencyContact:
#  EmpEmergencyContact_3_1: { emp_number: 3, seqno: '1', name: Tharushi, relationship: Daughter, home_phone: '875345454', mobile_phone: '', office_phone: '' }
#  EmpEmergencyContact_3_2: { emp_number: 3, seqno: '2', name: Geeth, relationship: Son, home_phone: '8365734757', mobile_phone: '', office_phone: '' }
#  EmpEmergencyContact_4_1: { emp_number: 4, seqno: '1', name: Sara, relationship: Daughter, home_phone: '983784788', mobile_phone: '', office_phone: '' }
#  EmpEmergencyContact_4_2: { emp_number: 4, seqno: '2', name: John, relationship: Uncle, home_phone: '765784654', mobile_phone: '', office_phone: '' }
#  EmpEmergencyContact_4_3: { emp_number: 4, seqno: '3', name: Issebella, relationship: Aunty, home_phone: '6465775', mobile_phone: '', office_phone: '' }
#  EmpEmergencyContact_4_4: { emp_number: 4, seqno: '4', name: Jill, relationship: Daughter, home_phone: '6456456456', mobile_phone: '', office_phone: '' }
#  EmpEmergencyContact_4_5: { emp_number: 4, seqno: '5', name: Morgen, relationship: Son, home_phone: '675677', mobile_phone: '', office_phone: '' }
#
#EmpPassport:
#  EmpPassport_4_1: { emp_number: 4, seqno: '1', number: '45654444', i9_status: Status, passport_issue_date: '2003-06-11 00:00:00', passport_expire_date: '2012-06-07 00:00:00', comments: Comments, type_flag: '2', i9_review_date: '2011-06-15', country: LK }
#  EmpPassport_4_2: { emp_number: 4, seqno: '2', number: '872345', i9_status: Status, passport_issue_date: '2010-06-01 00:00:00', passport_expire_date: '2011-06-30 00:00:00', comments: Comments, type_flag: '1', i9_review_date: '2011-06-22', country: CA }




//    public function testSortBySupervisorAsSupervisor() {
//        echo '1';
//         $employeeInformation = Helper::loginUser($this, "chuck ", "chuck");
//         echo '2';
//        $employeeInformation = new EmployeeListPage($this);
//        echo '3';
//        $employeeInformation->goToNewEmployeeList() ;
//        echo '4';
//        $employeeInformation->sortByFieldName("Supervisor");
//        echo '5';
//        $emplist = array("Saman Kumara", "Ashan Kumara");
//       echo '6'; 
//        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
//       echo '7';
//        $employeeInformation->sortByFieldName("Supervisor");
//        echo '8';
//        $emplist = array("Saman Kumara", "Ashan Kumara");
//        echo '9';
//        $this->assertTrue($employeeInformation->list->verifySortingOrder($emplist, "First (& Middle) Name"));
//        echo '10';
//        Helper::logOutIfLoggedIn($this);
//        echo '11';
//    }

//     public function testSearchEmployeebyName(){
//        
//        
//        Helper::loginUser($this, "admin", "admin");
//        $searchEmployee = new EmployeeListPage($this);
//        $searchEmployee->search("Ashan Kumara Perera", NULL, NULL, NULL, NULL, NULL, NULL);
//        $expected = array("Ashan Kumara");
//        $this->assertTrue($searchEmployee->getEmployeeList()->isOnlyItemsListed($expected, "First (& Middle) Name"));
//        Helper::logOutIfLoggedIn($this);
//        
//        
//    }
//       public function testSearchEmployeebyId(){
//        
//        
//        Helper::loginUser($this, "admin", "admin");
//        $searchEmployee = new EmployeeListPage($this);
//        $searchEmployee->search(NULL, "0001", NULL, NULL, NULL, NULL, NULL);
//        $expected = array("0001");
//        $this->assertTrue($searchEmployee->getEmployeeList()->isOnlyItemsListed($expected, "Id"));
//        Helper::logOutIfLoggedIn($this);
//        
//        
//    }
//    
//       
//      
//        public function testSearchEmployeebyNameJobTitle(){
//        
//        
//        Helper::loginUser($this, "admin", "admin");
//        $searchEmployee = new EmployeeListPage($this);
//        $searchEmployee->search("Chuck Neel Fernando", NULL, NULL, NULL, NULL, "QA Engineer", NULL);
//        $expected[] = array("First (& Middle) Name" => "Chuck Neel", "Job Title" => "QA Engineer");
//        $this->assertTrue($searchEmployee->getEmployeeList()->isRecordsPresentInList($expected, FALSE ),"does not match");     
//               
//        
//        Helper::logOutIfLoggedIn($this);
//    }
//        
//     public function testSearchEmployeebyNameJobTitleSubUnit(){
//        
//       
//        Helper::loginUser($this, "admin", "admin");
//        
//        $searchEmployee = new EmployeeListPage($this);
 //     Menu::goToNewEmployeeList($this);
//        $searchEmployee->search("Chuck Neel Fernando", NULL, NULL, NULL, NULL, "QA Engineer", "QA Division");
//      
//        $expected[] = array("First (& Middle) Name" => "Chuck Neel", "Job Title" => "QA Engineer", "Sub Unit" => "QA Division" );
//        $this->assertTrue($searchEmployee->getEmployeeList()->isRecordsPresentInList($expected, FALSE ),"does not match");     
//               
//        //log out
//        Helper::logOutIfLoggedIn($this);
//    }
//    
//    public function testSearchEmployeebyNameIdJobTitleSubUnit(){
//        
//        //login
//        Helper::loginUser($this, "admin", "admin");
//        $searchEmployee = new EmployeeListPage($this);
//        $searchEmployee->search("Chuck Neel Fernando", "0004", NULL, NULL, NULL, "QA Engineer", "QA Division");
//        $expected[] = array("First (& Middle) Name" => "Chuck Neel", "Id" => "0004","Job Title" => "QA Engineer", "Sub Unit" => "QA Division" );
//        $this->assertTrue($searchEmployee->getEmployeeList()->isRecordsPresentInList($expected, FALSE ),"does not match");     
//               
//        //log out
//        Helper::logOutIfLoggedIn($this);
//    }
//    
//    public function testSearchEmployeebyInvalidId(){
//        
//        //login
//        Helper::loginUser($this, "admin", "admin");
//        //open employee list page
//        $EmployeeListPage = new EmployeeListPage($this); //object
//        $EmployeeListPage->search(NULL, "12345", NULL, NULL, NULL, NULL, NULL, NULl); 
//        $this->assertEquals($EmployeeListPage->getStatusMessage(), "No Records Found");
//        //log out
//        Helper::logOutIfLoggedIn($this);
//    }
//    
////        public function testSearchEmployeebyName1(){     
////        $viewEmployee = new EmployeeListPage($this);
////        $fixture = sfConfig::get('sf_plugins_dir') . "//orangehrmFunctionalTestPlugin//test//newpim//testdata//EmployeeList.yml";
////        $section1 = $record["Testsearch"];
////        $inputData = Helper::loadFixtureToInputArray($fixture, $section1, $viewEmployee, "Input");
////        print_r($inputData);
////        $employeeInformation = Helper::loginUser($this, 'admin', 'admin');
////        foreach ($inputData as $record) {
////            $viewEmployee = new EmployeeListPage($this);
////            $viewEmployee->search2($record);
////            $section = $record["ExpectedResult"];
// //           $expected = Helper::loadFixtureToInputArray($fixture, $section, $viewEmployee, "Output");
////            $this->assertTrue($viewEmployee->list->isRecordsPresentInList($expected, true), "No Records Found");        
////        }
////      Helper::logOutIfLoggedIn($this);
////        }
//                   
//    
//    
//    
//    
//    
//    
   