<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyClass
 *
 * @author madusani
 */
class PIMModuleDataExtractor {

    public $myArray;
    public $dataArray;

    public function __construct() {

        $fixture = sfConfig::get('sf_plugins_dir') . "/../data/fixtures/data.yml";
        $this->dataArray = sfYaml::load($fixture);
        $this->myArray["CompanyStructure"] = $this->dataArray["CompanyStructure"];
        $this->myArray["EthnicRace"] = $this->dataArray["EthnicRace"];
        $this->myArray["CompanyGeninfo"] = $this->dataArray["CompanyGeninfo"];
        $this->myArray["JobSpecifications"] = $this->dataArray["JobSpecifications"];
        $this->myArray["JobTitle"] = $this->dataArray["JobTitle"];
        $this->myArray["JobTitleEmployeeStatus"] = $this->dataArray["JobTitleEmployeeStatus"];
        $this->myArray["Location"] = $this->dataArray["Location"];
        $this->myArray["Nationality"] = $this->dataArray["Nationality"];
        $this->myArray["Education"] = $this->dataArray["Education"];
        $this->myArray["Licenses"] = $this->dataArray["Licenses"];
        $this->myArray["MembershipType"] = $this->dataArray["MembershipType"];
        $this->myArray["Membership"] = $this->dataArray["Membership"];
        $this->myArray["Skill"] = $this->dataArray["Skill"];
        $this->myArray["Language"] = $this->dataArray["Language"];
        $this->myArray["Employee"] = $this->dataArray["Employee"];
        $this->myArray["EmpPicture"] = $this->dataArray["EmpPicture"];
        $this->myArray["EmpJobtitleHistory"] = $this->dataArray["EmpJobtitleHistory"];
        $this->myArray["EmpLocationHistory"] = $this->dataArray["EmpLocationHistory"];
        $this->myArray["EmpSubdivisionHistory"] = $this->dataArray["EmpSubdivisionHistory"];
        $this->myArray["ReportTo"] = $this->dataArray["ReportTo"];
        $this->myArray["EmpDependent"] = $this->dataArray["EmpDependent"];
        $this->myArray["EmpEmergencyContact"] = $this->dataArray["EmpEmergencyContact"];
        $this->myArray["EmpPassport"] = $this->dataArray["EmpPassport"];
        $this->myArray["SalaryGrade"] = $this->dataArray["SalaryGrade"];
        $this->myArray["SalaryCurrencyDetail"] = $this->dataArray["SalaryCurrencyDetail"];
        $this->myArray["Users"] = $this->dataArray["Users"];
        $myFixture = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/pim/testdata/PIMPrerequisites.yml";
        $handle = fopen($myFixture, "w");
        fwrite($handle, sfYaml::dump($this->myArray));
        fclose($handle);
    }

}