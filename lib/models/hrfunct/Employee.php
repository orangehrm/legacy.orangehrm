<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 * Ruchira
 */

require_once ROOT_PATH . '/lib/common/search/AbstractSearch.php';
require_once ROOT_PATH . '/lib/common/search/SearchSqlHelper.php';
require_once ROOT_PATH . '/lib/common/search/SearchField.php';
require_once ROOT_PATH . '/lib/common/search/SelectOption.php';
require_once ROOT_PATH . '/lib/common/search/SearchOperator.php';
require_once ROOT_PATH . '/lib/models/eimadmin/EmployStat.php';   
require_once ROOT_PATH . '/lib/models/hrfunct/EmpLocation.php';
 
/**
 * Class representing an employee
 * 
 * **************************************************************************
 * IMPORTANT NOTE:
 * **************************************************************************
 * This class is intended to replace EmpInfo eventually and is more
 * object oriented.
 * 
 * (Eg: functions return objects instead of multi dimentional arrays like EmpInfo)
 * 
 * But for now it only has a few features, and we should continue using the
 * existing functions in EmpInfo.php
 * **************************************************************************
 */
class Employee {
    
    /* Field Name constants. Used in search. Values should match property names */
    const FIELD_EMP_NUMBER = 'empNumber';
    const FIELD_EMP_ID = 'empId';
    const FIELD_FIRSTNAME = 'firstName';
    const FIELD_MIDDLENAME = 'middleName';
    const FIELD_LASTNAME = 'lastName';
    const FIELD_NICKNAME = 'nickName';     
    const FIELD_NAME = 'name';
   
    const FIELD_SSN_NO = 'ssnNo';
    const FIELD_SIN_NO = 'sinNo';
    const FIELD_OTHER_ID = 'otherId';
    const FIELD_SMOKER = 'smoker';
    const FIELD_DRIVING_LICENSE_NO = 'drivingLicenceNo';
    const FIELD_MILITARY_SERVICE = 'militaryService';
            
    const FIELD_NATIONALITY = 'nationality';            
    const FIELD_DOB = 'dob';
    const FIELD_MARITAL_STATUS = 'maritalStatus';
    const FIELD_GENDER = 'gender';
    const FIELD_DRIVING_LICENSE_EXP_DATE = 'licenceExpiryDate';
    const FIELD_RACE = 'ethnicRace';

    const FIELD_JOB_TITLE = 'jobTitle';
    const FIELD_EMP_STATUS = 'employeStatus';
    const FIELD_EEO_CATEGORY = 'eeoCategory';
    const FIELD_SUB_DIVISION = 'subDivision';
    const FIELD_LOCATIONS = 'locations';
    const FIELD_TERMINATED_DATE = 'terminatedDate';
    const FIELD_TERMINATED_REASON = 'terminatedReason';

    const FIELD_COUNTRY = 'country';
    const FIELD_STREET1 = 'street1';
    const FIELD_CITY = 'city';
    const FIELD_ZIP = 'zipCode';
    const FIELD_HOME_PHONE = 'homePhone';    
    const FIELD_WORK_PHONE = 'workPhone';
    const FIELD_WORK_EMAIL = 'workEmail';
    
    const FIELD_STREET2 = 'street2';
    const FIELD_PROVINCE = 'province';
    const FIELD_MOBILE = 'mobilePhone';
    const FIELD_OTHER_EMAIL = 'otherEmail';
    
    // Database related constants
    const DB_TABLE_EMPLOYEE = 'hs_hr_employee';
    
    const DB_FIELD_EMP_NUMBER =  'emp_number';
    const DB_FIELD_EMP_ID =  'employee_id';
    const DB_FIELD_LASTNAME = 'emp_lastname';
    const DB_FIELD_FIRSTNAME = 'emp_firstname';
    const DB_FIELD_MIDDLENAME = 'emp_middle_name';
    const DB_FIELD_NICKNAME = 'emp_nick_name';    
    const DB_FIELD_NAME = "CONCAT(emp_firstname, ' ', emp_lastname)";
                   
    const DB_FIELD_SSN_NO  = 'emp_ssn_num';
    const DB_FIELD_SIN_NO = 'emp_sin_num';
    const DB_FIELD_OTHER_ID = 'emp_other_id';
    const DB_FIELD_SMOKER  = 'emp_smoker';        
    const DB_FIELD_DRIVING_LICENSE_NO = 'emp_dri_lice_num';
    const DB_FIELD_MILITARY_SERVICE = 'emp_military_service';
        
    const DB_FIELD_NATIONALITY = 'nation_code';            
    const DB_FIELD_DOB = 'emp_birthday';
    const DB_FIELD_MARITAL_STATUS = 'emp_martial_status';
    const DB_FIELD_GENDER = 'emp_gender';
    const DB_FIELD_DRIVING_LICENSE_EXP_DATE = 'emp_dri_lice_exp_date';
    const DB_FIELD_RACE = 'ethnic_race_code';  
                        
    const DB_FIELD_STREET1 = 'emp_street1';
    const DB_FIELD_STREET2 = 'emp_street2';
    const DB_FIELD_CITY = 'city_code';
    const DB_FIELD_COUNTRY = 'coun_code';
    const DB_FIELD_PROVINCE = 'provin_code';
    const DB_FIELD_ZIP = 'emp_zipcode';
    const DB_FIELD_HOME_PHONE = 'emp_hm_telephone';
    const DB_FIELD_MOBILE = 'emp_mobile';
    const DB_FIELD_WORK_PHONE = 'emp_work_telephone';
    const DB_FIELD_WORK_EMAIL = 'emp_work_email';
    const DB_FIELD_OTHER_EMAIL = 'emp_oth_email';

    const DB_FIELD_JOB_TITLE = 'job_title_code';
    const DB_FIELD_EMP_STATUS = 'emp_status';
    const DB_FIELD_EEO_CATEGORY = 'eeo_cat_code';
    const DB_FIELD_SUB_DIVISION = 'work_station';
    const DB_FIELD_JOINED_DATE = 'joined_date';
    const DB_FIELD_TERMINATED_DATE = 'terminated_date';
    const DB_FIELD_TERMINATED_REASON = 'terminated_reason';
    
    /* This is a field in hs_hr_emp_location */
    const DB_FIELD_LOCATION = 'loc_code';

    private static $fieldMap = array (
    
     self::FIELD_EMP_NUMBER => self::DB_FIELD_EMP_NUMBER,
     self::FIELD_EMP_ID => self::DB_FIELD_EMP_ID,
     self::FIELD_FIRSTNAME => self::DB_FIELD_FIRSTNAME,
     self::FIELD_MIDDLENAME => self::DB_FIELD_MIDDLENAME,
     self::FIELD_LASTNAME => self::DB_FIELD_LASTNAME,
     self::FIELD_NICKNAME => self::DB_FIELD_NICKNAME,
     self::FIELD_NAME => self::DB_FIELD_NAME,
     self::FIELD_SSN_NO => self::DB_FIELD_SSN_NO,
     self::FIELD_SIN_NO => self::DB_FIELD_SIN_NO,
     self::FIELD_OTHER_ID => self::DB_FIELD_OTHER_ID,
     self::FIELD_SMOKER => self::DB_FIELD_SMOKER,
     self::FIELD_DRIVING_LICENSE_NO => self::DB_FIELD_DRIVING_LICENSE_NO,
     self::FIELD_MILITARY_SERVICE => self::DB_FIELD_MILITARY_SERVICE,

     self::FIELD_NATIONALITY => self::DB_FIELD_NATIONALITY, 
     self::FIELD_DOB => self::DB_FIELD_DOB,
     self::FIELD_MARITAL_STATUS => self::DB_FIELD_MARITAL_STATUS, 
     self::FIELD_GENDER => self::DB_FIELD_GENDER,
     self::FIELD_DRIVING_LICENSE_EXP_DATE => self::DB_FIELD_DRIVING_LICENSE_EXP_DATE,
     self::FIELD_RACE => self::DB_FIELD_RACE,
 
     self::FIELD_JOB_TITLE => self::DB_FIELD_JOB_TITLE,
     self::FIELD_EMP_STATUS => self::DB_FIELD_EMP_STATUS,
     self::FIELD_EEO_CATEGORY => self::DB_FIELD_EEO_CATEGORY,
     self::FIELD_SUB_DIVISION => self::DB_FIELD_SUB_DIVISION,
     //self::FIELD_JOINED_DATE => self::DB_FIELD_JOINED_DATE,
     self::FIELD_LOCATIONS => self::DB_FIELD_LOCATION,
     self::FIELD_TERMINATED_DATE => self::DB_FIELD_TERMINATED_DATE,
     self::FIELD_TERMINATED_REASON => self::DB_FIELD_TERMINATED_REASON,

     self::FIELD_COUNTRY => self::DB_FIELD_COUNTRY,
     self::FIELD_STREET1 => self::DB_FIELD_STREET1,
     self::FIELD_CITY => self::DB_FIELD_CITY,
     self::FIELD_ZIP => self::DB_FIELD_ZIP,
     self::FIELD_HOME_PHONE => self::DB_FIELD_HOME_PHONE,
     self::FIELD_WORK_PHONE => self::DB_FIELD_WORK_PHONE,
     self::FIELD_WORK_EMAIL => self::DB_FIELD_WORK_EMAIL,
    
     self::FIELD_STREET2 => self::DB_FIELD_STREET2,
     self::FIELD_PROVINCE => self::DB_FIELD_PROVINCE,
     self::FIELD_MOBILE => self::DB_FIELD_MOBILE,
     self::FIELD_OTHER_EMAIL => self::DB_FIELD_OTHER_EMAIL,    
    );   
    
    // Main employee information
    private $empNumber;
    private $empId;
    private $firstName;
    private $middleName;
    private $lastName;
    private $nickName;
   
    // Personal information
    private $ssnNo;
    private $sinNo;
    private $otherId;
    private $smoker;
    private $drivingLicenceNo;
    private $militaryService;
            
    private $nationality;            
    private $dob;
    private $maritalStatus;
    private $gender;
    private $licenceExpiryDate;
    private $ethnicRace;

    // Job info
    private $jobTitle;
    private $employeStatus;
    private $eeoCategory;
    private $subDivision;
    private $locations;
    private $terminatedDate;
    private $terminatedReason;

    // Contact
    private $country;
    private $street1;
    private $city;
    private $zipCode;
    private $homePhone;    
    private $workPhone;
    private $workEmail;
    
    private $street2;
    private $province;
    private $mobilePhone;
    private $otherEmail;
    
    /**
     * Retrieves the value of empNumber.
     * @return empNumber
     */
    public function getEmpNumber() {
        return $this->empNumber;
    }

    /**
     * Sets the value of empNumber.
     * @param empNumber
     */
    public function setEmpNumber($empNumber) {
        $this->empNumber = $empNumber;
    }

    /**
     * Retrieves the value of empId.
     * @return empId
     */
    public function getEmpId() {
        return $this->empId;
    }

    /**
     * Sets the value of empId.
     * @param empId
     */
    public function setEmpId($empId) {
        $this->empId = $empId;
    }

    /**
     * Retrieves the value of firstName.
     * @return firstName
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Sets the value of firstName.
     * @param firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    /**
     * Retrieves the value of middleName.
     * @return middleName
     */
    public function getMiddleName() {
        return $this->middleName;
    }

    /**
     * Sets the value of middleName.
     * @param middleName
     */
    public function setMiddleName($middleName) {
        $this->middleName = $middleName;
    }

    /**
     * Retrieves the value of lastName.
     * @return lastName
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Sets the value of lastName.
     * @param lastName
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    /**
     * Retrieves the value of Nick name
     * @return nickName
     */
    public function getNickName() {
        return $this->nickName;
    }

    /**
     * Sets the value of nick name
     * @param lastName
     */
    public function setNickName($nickName) {
        $this->nickName = $nickName;
    }

    /**
     * Get name (first name + last name)
     */
    public function getName() {
        return $this->firstName . ' ' . $this->lastName;
    }
    
    /**
     * Retrieves the value of ssnNo.
     * @return ssnNo
     */
    public function getSsnNo() {
        return $this->ssnNo;
    }

    /**
     * Sets the value of ssnNo.
     * @param ssnNo
     */
    public function setSsnNo($ssnNo) {
        $this->ssnNo = $ssnNo;
    }

    /**
     * Retrieves the value of sinNo.
     * @return sinNo
     */
    public function getSinNo() {
        return $this->sinNo;
    }

    /**
     * Sets the value of sinNo.
     * @param sinNo
     */
    public function setSinNo($sinNo) {
        $this->sinNo = $sinNo;
    }

    /**
     * Retrieves the value of otherId.
     * @return otherId
     */
    public function getOtherId() {
        return $this->otherId;
    }

    /**
     * Sets the value of otherId.
     * @param otherId
     */
    public function setOtherId($otherId) {
        $this->otherId = $otherId;
    }

    /**
     * Retrieves the value of smoker.
     * @return smoker
     */
    public function getSmoker() {
        return $this->smoker;
    }

    /**
     * Sets the value of smoker.
     * @param smoker
     */
    public function setSmoker($smoker) {
        $this->smoker = $smoker;
    }

    /**
     * Retrieves the value of drivingLicenceNo.
     * @return drivingLicenceNo
     */
    public function getDrivingLicenceNo() {
        return $this->drivingLicenceNo;
    }

    /**
     * Sets the value of drivingLicenceNo.
     * @param drivingLicenceNo
     */
    public function setDrivingLicenceNo($drivingLicenceNo) {
        $this->drivingLicenceNo = $drivingLicenceNo;
    }

    /**
     * Retrieves the value of militaryService.
     * @return militaryService
     */
    public function getMilitaryService() {
        return $this->militaryService;
    }

    /**
     * Sets the value of militaryService.
     * @param militaryService
     */
    public function setMilitaryService($militaryService) {
        $this->militaryService = $militaryService;
    }

    /**
     * Retrieves the value of nationality.
     * @return nationality
     */
    public function getNationality() {
        return $this->nationality;
    }

    /**
     * Sets the value of nationality.
     * @param nationality
     */
    public function setNationality($nationality) {
        $this->nationality = $nationality;
    }

    /**
     * Retrieves the value of dob.
     * @return dob
     */
    public function getDob() {
        return $this->dob;
    }

    /**
     * Sets the value of dob.
     * @param dob
     */
    public function setDob($dob) {
        $this->dob = $dob;
    }

    /**
     * Retrieves the value of maritalStatus.
     * @return maritalStatus
     */
    public function getMaritalStatus() {
        return $this->maritalStatus;
    }

    /**
     * Sets the value of maritalStatus.
     * @param maritalStatus
     */
    public function setMaritalStatus($maritalStatus) {
        $this->maritalStatus = $maritalStatus;
    }

    /**
     * Retrieves the value of gender.
     * @return gender
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Sets the value of gender.
     * @param gender
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }

    /**
     * Retrieves the value of licenceExpiryDate.
     * @return licenceExpiryDate
     */
    public function getLicenceExpiryDate() {
        return $this->licenceExpiryDate;
    }

    /**
     * Sets the value of licenceExpiryDate.
     * @param licenceExpiryDate
     */
    public function setLicenceExpiryDate($licenceExpiryDate) {
        $this->licenceExpiryDate = $licenceExpiryDate;
    }

    /**
     * Retrieves the value of ethnicRace.
     * @return ethnicRace
     */
    public function getEthnicRace() {
        return $this->ethnicRace;
    }

    /**
     * Sets the value of ethnicRace.
     * @param ethnicRace
     */
    public function setEthnicRace($ethnicRace) {
        $this->ethnicRace = $ethnicRace;
    }

    /**
     * Retrieves the value of jobTitle.
     * @return jobTitle
     */
    public function getJobTitle() {
        return $this->jobTitle;
    }

    /**
     * Sets the value of jobTitle.
     * @param jobTitle
     */
    public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
    }

    /**
     * Retrieves the value of employeStatus.
     * @return employeStatus
     */
    public function getEmployeStatus() {
        return $this->employeStatus;
    }

    /**
     * Sets the value of employeStatus.
     * @param employeStatus
     */
    public function setEmployeStatus($employeStatus) {
        $this->employeStatus = $employeStatus;
    }

    /**
     * Retrieves the value of eeoCategory.
     * @return eeoCategory
     */
    public function getEeoCategory() {
        return $this->eeoCategory;
    }

    /**
     * Sets the value of eeoCategory.
     * @param eeoCategory
     */
    public function setEeoCategory($eeoCategory) {
        $this->eeoCategory = $eeoCategory;
    }

    /**
     * Retrieves the value of subDivision.
     * @return subDivision
     */
    public function getSubDivision() {
        return $this->subDivision;
    }

    /**
     * Sets the value of subDivision.
     * @param subDivision
     */
    public function setSubDivision($subDivision) {
        $this->subDivision = $subDivision;
    }

    /**
     * Retrieves the value of locations.
     * @return locations
     */
    public function getLocations() {
        return $this->locations;
    }

    /**
     * Sets the value of locations.
     * @param locations
     */
    public function setLocations($locations) {
        $this->locations = $locations;
    }

    /**
     * Retrieves the value of terminatedDate.
     * @return terminatedDate
     */
    public function getTerminatedDate() {
        return $this->terminatedDate;
    }

    /**
     * Sets the value of terminatedDate.
     * @param terminatedDate
     */
    public function setTerminatedDate($terminatedDate) {
        $this->terminatedDate = $terminatedDate;
    }

    /**
     * Retrieves the value of terminatedReason.
     * @return terminatedReason
     */
    public function getTerminatedReason() {
        return $this->terminatedReason;
    }

    /**
     * Sets the value of terminatedReason.
     * @param terminatedReason
     */
    public function setTerminatedReason($terminatedReason) {
        $this->terminatedReason = $terminatedReason;
    }

    /**
     * Retrieves the value of country.
     * @return country
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Sets the value of country.
     * @param country
     */
    public function setCountry($country) {
        $this->country = $country;
    }

    /**
     * Retrieves the value of street1.
     * @return street1
     */
    public function getStreet1() {
        return $this->street1;
    }

    /**
     * Sets the value of street1.
     * @param street1
     */
    public function setStreet1($street1) {
        $this->street1 = $street1;
    }

    /**
     * Retrieves the value of city.
     * @return city
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Sets the value of city.
     * @param city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * Retrieves the value of zipCode.
     * @return zipCode
     */
    public function getZipCode() {
        return $this->zipCode;
    }

    /**
     * Sets the value of zipCode.
     * @param zipCode
     */
    public function setZipCode($zipCode) {
        $this->zipCode = $zipCode;
    }

    /**
     * Retrieves the value of homePhone.
     * @return homePhone
     */
    public function getHomePhone() {
        return $this->homePhone;
    }

    /**
     * Sets the value of homePhone.
     * @param homePhone
     */
    public function setHomePhone($homePhone) {
        $this->homePhone = $homePhone;
    }

    /**
     * Retrieves the value of workPhone.
     * @return workPhone
     */
    public function getWorkPhone() {
        return $this->workPhone;
    }

    /**
     * Sets the value of workPhone.
     * @param workPhone
     */
    public function setWorkPhone($workPhone) {
        $this->workPhone = $workPhone;
    }

    /**
     * Retrieves the value of workEmail.
     * @return workEmail
     */
    public function getWorkEmail() {
        return $this->workEmail;
    }

    /**
     * Sets the value of workEmail.
     * @param workEmail
     */
    public function setWorkEmail($workEmail) {
        $this->workEmail = $workEmail;
    }

    /**
     * Retrieves the value of street2.
     * @return street2
     */
    public function getStreet2() {
        return $this->street2;
    }

    /**
     * Sets the value of street2.
     * @param street2
     */
    public function setStreet2($street2) {
        $this->street2 = $street2;
    }

    /**
     * Retrieves the value of province.
     * @return province
     */
    public function getProvince() {
        return $this->province;
    }

    /**
     * Sets the value of province.
     * @param province
     */
    public function setProvince($province) {
        $this->province = $province;
    }

    /**
     * Retrieves the value of mobilePhone.
     * @return mobilePhone
     */
    public function getMobilePhone() {
        return $this->mobilePhone;
    }

    /**
     * Sets the value of mobilePhone.
     * @param mobilePhone
     */
    public function setMobilePhone($mobilePhone) {
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * Retrieves the value of otherEmail.
     * @return otherEmail
     */
    public function getOtherEmail() {
        return $this->otherEmail;
    }

    /**
     * Sets the value of otherEmail.
     * @param otherEmail
     */
    public function setOtherEmail($otherEmail) {
        $this->otherEmail = $otherEmail;
    }
    
    /**
     * Search employees based on the passed parameters 
     * 
     * @param Array $filters Array of SearchFilter objects
     * @param String $matchType One of AbstractSearch::MATCH_ALL or AbstractSearch::MATCH_ANY
     * @param String $sortField Field to sort on
     * @param String $sortOrder Sort order. One of AbstractSearch::SORT_ASCENDING or AbstractSearch::SORT_DESCENDING
     * @param int $pageNo The page number to fetch
     * @param int $itemsPerPage The number of items per page
     * @return Array Array of Employee objects
     */
    public static function search($filters, $matchType, $sortField, $sortOrder, $pageNo, $itemsPerPage) {
                
        $fields[0] = self::DB_FIELD_EMP_NUMBER;
        $fields[1] = self::DB_FIELD_EMP_ID;
        $fields[2] = self::DB_FIELD_FIRSTNAME;
        $fields[3] = self::DB_FIELD_MIDDLENAME;
        $fields[4] = self::DB_FIELD_LASTNAME;
        $fields[5] = self::DB_FIELD_NICKNAME;

        $sql_builder = new SQLQBuilder();

        $tables[0] = "hs_hr_employee a";
        $tables[1] = "hs_hr_compstructtree b";
                
        //$tables[1] = "hs_hr_job_title b";
        //$tables[3] = "hs_hr_location d";
        /*$tables[3] = "`hs_hr_empstat` e";
        $tables[4] = "`hs_hr_emp_reportto` f";
        $tables[5] = "`hs_hr_employee` g"; */

        //$joinConditions[1] = "a.`job_title_code` = b.`jobtit_code`";
        $joinConditions[1] = "a.`work_station` = b.`id`";
        //$joinConditions[3] = 'a.' . self::DB_FIELD_LOCATION . ' = b.loc_code';        
        /*$joinConditions[3] = "a.`emp_status` = e.`estat_code`";
        $joinConditions[4] = "a.`emp_number` = f.`erep_sub_emp_number`";
        $joinConditions[5] = "f.`erep_sup_emp_number` = g.`emp_number`";*/

        // HIDE terminated employees by default? OR ??
        
        $selectConditions = self::_getSelectConditions($filters, $matchType);
        //var_dump($selectConditions);die;
        /* Select Conditions */
        
        /* Result order */
        if (isset(self::$fieldMap[$sortField])) {
            $sortDbField = self::$fieldMap[$sortField];
            $sortDbOrder = $sortOrder;
        } else {
            $sortDbField = null;
            $sortDbOrder = null;
        }
                
        /* Calculate row limit */
        $limit = null;
        if ($pageNo > 0) {                
            $offset = ($pageNo - 1) * $itemsPerPage;
            $limit = "{$offset}, {$itemsPerPage}";
        }

        $sql = $sql_builder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectConditions, null, $sortDbField, $sortDbOrder, $limit, null);        

        $conn = new DMLFunctions();
        $result = $conn->executeQuery($sql);

        $empList = array();
        $inverseFieldMap = array_flip(self::$fieldMap);
        while ($result && ($row = mysql_fetch_assoc($result))) {
            $empList[] = self::_createFromRow($row, $inverseFieldMap);
        }

        return $empList;        
    }
    

    /**
     * Get matching employee count based on the passed parameters 
     * 
     * @param Array $filters Array of SearchFilter objects
     * @param String $matchType One of AbstractSearch::MATCH_ALL or AbstractSearch::MATCH_ANY
     * @return Int number of matching employees
     */
    public static function countResults($filters, $matchType) {

        $fields[0] = 'COUNT(' . self::DB_FIELD_EMP_NUMBER . ') AS COUNT';

        $sql_builder = new SQLQBuilder();

        $tables[0] = "`hs_hr_employee` a";
        $tables[1] = "hs_hr_compstructtree b";
                
        $joinConditions[1] = "a.`work_station` = b.`id`";
        
        $selectConditions = self::_getSelectConditions($filters, $matchType);
        
        $sql = $sql_builder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectConditions);        

        $conn = new DMLFunctions();
        $result = $conn->executeQuery($sql);

        $count = 0;
        if ($result && ($row = mysql_fetch_array($result))) {
            $count = $row[0];
        }

        return $count;        
    }
    

    /**
     * Return SQL select conditions based on passed filter array
     * 
     * @param Array $filters Array of SearchFilter objects
     * @param String $matchType Match Type (AbstractSearch::MATCH_ALL or AbstractSearch::MATCH_ANY)
     * @return Array Array containing SQL Select conditions.
     */
    private function _getSelectConditions($filters, $matchType) {
        
        $includeTerminated = false;
                        
        if (!empty($filters)) {
        
            $conditions = array();
            foreach ($filters as $filter) {
    
                $searchField = $filter->getSearchField();                        
                if (isset(self::$fieldMap[$searchField->getFieldName()])) {
                    
                    $dbField = self::$fieldMap[$searchField->getFieldName()];
                    $value = $filter->getSearchValue();
                    
                    if (get_magic_quotes_gpc()) {
                        $value = stripslashes($value);
                    }
                    $value = mysql_real_escape_string($value);
                    
                    if ($dbField == self::DB_FIELD_LOCATION) {
                        
                        $operatorType = $filter->getOperator()->getType(); 
                        if ($operatorType == SearchOperator::OPERATOR_NOT_EMPTY) {
                            $conditions[] = "(EXISTS (SELECT * FROM " . EmpLocation::TABLE_NAME . " el WHERE " . 
                                "el." . EmpLocation::DB_FIELD_EMP_NUMBER . " = a." . self::DB_FIELD_EMP_NUMBER . "))";                            
                        } else if ($operatorType == SearchOperator::OPERATOR_EMPTY) {
                            $conditions[] = "(NOT EXISTS (SELECT * FROM " . EmpLocation::TABLE_NAME . " el WHERE " . 
                                "el." . EmpLocation::DB_FIELD_EMP_NUMBER . " = a." . self::DB_FIELD_EMP_NUMBER . "))";                            
                        } else {
                            $conditions[] = "(EXISTS (SELECT * FROM " . EmpLocation::TABLE_NAME . " el WHERE " . 
                                "el." . EmpLocation::DB_FIELD_LOC_CODE . " = '{$value}' AND " .
                                "el." . EmpLocation::DB_FIELD_EMP_NUMBER . " = a." . self::DB_FIELD_EMP_NUMBER . "))";
                        }                                                        
                    } else {
                    
                        $conditions[] = SearchSqlHelper::getSqlCondition($dbField, $filter->getOperator(), $value, 
                            $searchField->getFieldType());
                    }
                        
                    if (($searchField->getFieldName() == self::FIELD_EMP_STATUS) && 
                            (($value == EmploymentStatus::EMPLOYMENT_STATUS_ID_TERMINATED) ||
                            ($filter->getOperator()->getType() == SearchOperator::OPERATOR_EMPTY) ||
                            ($filter->getOperator()->getType() == SearchOperator::OPERATOR_NOT_EMPTY) )) {
                                
                        $includeTerminated = true;
                    }                     
                }            
            }
            
            if (!empty($conditions)) {
                $joinWith = ($matchType == AbstractSearch::MATCH_ANY) ? ' OR ' : ' AND ';
                $selectConditions[] = implode($joinWith, $conditions);
            }                    
        }
        
        /* If not specifically searching for terminated employees, exclude them */
        if (!$includeTerminated) { 
            $selectConditions[] = "(" . self::DB_FIELD_EMP_STATUS . " <> '". EmploymentStatus::EMPLOYMENT_STATUS_ID_TERMINATED. "' OR " .self::DB_FIELD_EMP_STATUS. " IS NULL) ";
        }      
        return $selectConditions;
    }
    
    /**
     * Create an Employee from from database row 
     * 
     * @return Array Array of Employee objects
     */    
    private static function _createFromRow($row, $map) {
        
        $employee = new Employee();
        
        foreach($row as $key=>$value) {
            if (isset($map[$key])) {
                $field = $map[$key];
                $employee->$field = $value;
            }    
        }
        
        return $employee;
    }
}
?>
