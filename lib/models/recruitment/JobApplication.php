<?php
/*
// OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
// all the essential functionalities required for any enterprise.
// Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com

// OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
// the GNU General Public License as published by the Free Software Foundation; either
// version 2 of the License, or (at your option) any later version.

// OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
// without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU General Public License for more details.

// You should have received a copy of the GNU General Public License along with this program;
// if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
// Boston, MA  02110-1301, USA
*/

require_once ROOT_PATH . '/lib/confs/Conf.php';
require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';
require_once ROOT_PATH . '/lib/common/LocaleUtil.php';
require_once ROOT_PATH . '/lib/common/UniqueIDGenerator.php';
require_once ROOT_PATH . '/lib/common/SearchObject.php';
require_once ROOT_PATH . '/lib/models/recruitment/JobVacancy.php';
require_once ROOT_PATH . '/lib/models/recruitment/JobApplicationEvent.php';
require_once ROOT_PATH . '/lib/models/recruitment/JobApplicationField.php';
require_once ROOT_PATH . '/lib/models/recruitment/ApplicantEducationInfo.php';
require_once ROOT_PATH . '/lib/models/recruitment/ApplicantEmploymentInfo.php';
require_once ROOT_PATH . '/lib/models/recruitment/ApplicantSkills.php';
require_once ROOT_PATH . '/lib/models/recruitment/ApplicantLicenseInformation.php';

/**
 * Class representing a Job Application
 */
class JobApplication {

	const TABLE_NAME = 'hs_hr_job_application';

	/** Database fields */
	const DB_FIELD_ID = 'application_id';
	const DB_FIELD_VACANCY_ID = 'vacancy_id';
	const DB_FIELD_FIRSTNAME = 'firstname';
	const DB_FIELD_MIDDLENAME = 'middlename';
	const DB_FIELD_LASTNAME = 'lastname';
	const DB_FIELD_STREET1 = 'street1';
	const DB_FIELD_STREET2 = 'street2';
	const DB_FIELD_CITY = 'city';
	const DB_FIELD_COUNTRY_CODE = 'country_code';
	const DB_FIELD_PROVINCE = 'province';
	const DB_FIELD_ZIP = 'zip';
	const DB_FIELD_PHONE = 'phone';
	const DB_FIELD_MOBILE = 'mobile';
	const DB_FIELD_EMAIL = 'email';
	const DB_FIELD_QUALIFICATIONS = 'qualifications';
    const DB_FIELD_STATUS = 'status';
    const DB_FIELD_APPLIED_DATETIME = 'applied_datetime';
    const DB_FIELD_EMP_NUMBER = 'emp_number';
    const DB_FIELD_DATE_OF_BIRTH = 'date_of_birth';
    const DB_FIELD_GENDER ='gender';
    const DB_FIELD_SALARY_EXPECTED = 'salary_expected';
    const DB_FIELD_IT_EXPERIENCE = 'IT_experience';
    const DB_FIELD_AVALIABLIITY_TO_START = 'availability_to_start';
    const DB_FIELD_BASIS_OF_EMPLOYMENT = 'basis_of_employemnet';
    const DB_FIELD_DO_YOU_HAVE_A_CAR = 'do_you_have_a_car';

    /**
     * Job application status
     */
    const STATUS_SUBMITTED = 0;
    const STATUS_SHORTLISTED = 1;
    const STATUS_FIRST_INTERVIEW_SCHEDULED = 2;
    const STATUS_SECOND_INTERVIEW_SCHEDULED = 3;
    const STATUS_JOB_OFFERED = 4;
    const STATUS_OFFER_DECLINED = 5;
    const STATUS_PENDING_APPROVAL = 6;
    const STATUS_HIRED = 7;
    const STATUS_REJECTED = 8;

    /**
     * Actions that can be performed on Job Application
     */
    const ACTION_REJECT = 'Reject';
    const ACTION_SHORTLIST = 'ShortList';
    const ACTION_SCHEDULE_FIRST_INTERVIEW = 'FirstInterview';
    const ACTION_SCHEDULE_SECOND_INTERVIEW = 'SecondInterview';
    const ACTION_OFFER_JOB = 'OfferJob';
    const ACTION_MARK_OFFER_DECLINED = 'MarkDeclined';
    const ACTION_SEEK_APPROVAL = 'SeekApproval';
    const ACTION_APPROVE = 'Approve';

    /** Fields retrieved from other tables */
    const JOB_TITLE_NAME = 'job_title_name';
    const HIRING_MANAGER_NAME = 'hiring_manager_name';

    /* Cv fields */
    const CV_DATA = 'cv_data';
    const CV_TYPE = 'cv_type';
    const CV_EXTENSION = 'cv_extenstion';

	private $dbFields = array(self::DB_FIELD_ID, self::DB_FIELD_VACANCY_ID, self::DB_FIELD_FIRSTNAME,
		self::DB_FIELD_MIDDLENAME, self::DB_FIELD_LASTNAME,	self::DB_FIELD_STREET1,	self::DB_FIELD_STREET2,
		self::DB_FIELD_CITY, self::DB_FIELD_COUNTRY_CODE, self::DB_FIELD_PROVINCE, self::DB_FIELD_ZIP,
		self::DB_FIELD_PHONE, self::DB_FIELD_MOBILE, self::DB_FIELD_EMAIL, self::DB_FIELD_QUALIFICATIONS,
        self::DB_FIELD_STATUS, self::DB_FIELD_APPLIED_DATETIME, self::DB_FIELD_EMP_NUMBER,self::CV_DATA,self::CV_TYPE,self::CV_EXTENSION,
        self::DB_FIELD_DATE_OF_BIRTH,self::DB_FIELD_GENDER, self::DB_FIELD_SALARY_EXPECTED, self::DB_FIELD_IT_EXPERIENCE, self::DB_FIELD_AVALIABLIITY_TO_START,
        self::DB_FIELD_BASIS_OF_EMPLOYMENT, self::DB_FIELD_DO_YOU_HAVE_A_CAR
        );

	private $id;
	private $vacancyId;
	private $firstName;
	private $middleName;
	private $lastName;
	private $street1;
	private $street2;
	private $city;
	private $province;
	private $country;
	private $zip;
	private $phone;
	private $mobile;
	private $email;
	private $qualifications;
    private $status = self::STATUS_SUBMITTED;
    private $appliedDateTime;
    private $empNumber;
    private $cvData;
	private $cvType;
	private $cvExtention;
    private $events;
    private $dateOfbirth;
    private $gender;
    private $salaryExpectation;
    private $availabilityToStart;
    private $basisOfemployment;
    private $doYouHaveACar;
    private $iTExperience;


    /**
     * Attributes retrieved from other objects
     */
    private $hiringManagerName;
    private $jobTitleName;

	/**
	 * Constructor
	 *
	 * @param int $id ID can be null for newly created job applications
	 */
	public function __construct($id = null) {
		$this->id = $id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setVacancyId($vacancyId) {
		$this->vacancyId = $vacancyId;
	}

	public function getVacancyId() {
		return $this->vacancyId;
	}

	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	public function getFirstName() {
		return $this->firstName;
	}

	public function setMiddleName($middleName) {
		$this->middleName = $middleName;
	}

	public function getMiddleName() {
		return $this->middleName;
	}

	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	public function getLastName() {
		return $this->lastName;
	}

	public function setStreet1($street1) {
		$this->street1 = $street1;
	}

	public function getStreet1() {
		return $this->street1;
	}

	public function setStreet2($street2) {
		$this->street2 = $street2;
	}

	public function getStreet2() {
		return $this->street2;
	}

	public function setCity($city) {
		$this->city = $city;
	}

	public function getCity() {
		return $this->city;
	}

	public function setProvince($province) {
		$this->province = $province;
	}

	public function getProvince() {
		return $this->province;
	}

	public function setCountry($country) {
		$this->country = $country;
	}

	public function getCountry() {
		return $this->country;
	}

	public function setZip($zip) {
		$this->zip = $zip;
	}

	public function getZip() {
		return $this->zip;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}

	public function getPhone() {
		return $this->phone;
	}

	public function setMobile($mobile) {
		$this->mobile = $mobile;
	}

	public function getMobile() {
		return $this->mobile;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setQualifications($qualifications) {
		$this->qualifications = $qualifications;
	}

	public function getQualifications() {
	    return $this->qualifications;
	}

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getEvents() {

        if (!isset($this->events) && isset($this->id)) {

            // Get application events
            $events = JobApplicationEvent::getEvents($this->id);
            $this->events = $events;
        }
        return $this->events;
    }

    public function setEvents($events) {
        $this->events = $events;
    }

    /**
     * Retrieves the value of hiringManagerName.
     * @return hiringManagerName
     */
    public function getHiringManagerName() {
        return $this->hiringManagerName;
    }

    /**
     * Sets the value of hiringManagerName.
     * @param hiringManagerName
     */
    public function setHiringManagerName($hiringManagerName) {
        $this->hiringManagerName = $hiringManagerName;
    }

    /**
     * Retrieves the value of jobTitleName.
     * @return jobTitleName
     */
    public function getJobTitleName() {
        return $this->jobTitleName;
    }

    /**
     * Sets the value of jobTitleName.
     * @param jobTitleName
     */
    public function setJobTitleName($jobTitleName) {
        $this->jobTitleName = $jobTitleName;
    }

    /**
     * Get the applied date and time
     */
    public function getAppliedDateTime() {
        return $this->appliedDateTime;
    }

    /**
     * Set the applied date and time
     */
    public function setAppliedDateTime($date) {
        $this->appliedDateTime = $date;
    }

    /**
     * Set the employee number of employee created after hiring
     * @param int $empNumber The employee number
     */
    public function setEmpNumber($empNumber) {
        $this->empNumber = $empNumber;
    }

    /**
     * Get the employee number of employee created after hiring
     * @return int The employee number of the employee created or null
     */
    public function getEmpNumber($empNumber) {
        return $this->empNumber;
    }

    /**
     * Returns the latest event
     * @return JobApplicationEvent The latest event, or null if no events
     */
    public function getLatestEvent() {

        $latestEvent = null;
        $events = $this->getEvents();
        if (!empty($events)) {
            $latestEvent = $events[count($events) - 1];
        }

        return $latestEvent;
    }

	public function getCvData() {
		return $this->cvData;
	}

	public function setCvData($cvData) {
		$this->cvData = $cvData;
	}

	public function getCvType() {
		return $this->cvType;
	}

	public function setCvType($cvType) {
		$this->cvType = $cvType;
	}

	public function getCvExtention() {
		return $this->cvExtention;
	}

	public function setCvExtention($cvExtention) {
		$this->cvExtention = $cvExtention;
	}

	public function getAvailabilityToStart() {
		return $this->availabilityToStart;
	}

	public function getBasisOfemployment() {
		return $this->basisOfemployment;
	}

	public function getDateOfbirth() {
		return $this->dateOfbirth;
	}

	public function getDoYouHaveACar() {
		return $this->doYouHaveACar;
	}

	public function getGender() {
		return $this->gender;
	}

	public function getITExperience() {
		return $this->iTExperience;
	}

	public function getSalaryExpectation() {
		return $this->salaryExpectation;
	}

	public function setAvailabilityToStart($availabilityToStart) {
		$this->availabilityToStart = $availabilityToStart;
	}

	public function setBasisOfemployment($basisOfemployment) {
		$this->basisOfemployment = $basisOfemployment;
	}

	public function setDateOfbirth($dateOfbirth) {
		$this->dateOfbirth = $dateOfbirth;
	}

	public function setDoYouHaveACar($doYouHaveACar) {
		$this->doYouHaveACar = $doYouHaveACar;
	}

	public function setGender($gender) {
		$this->gender = $gender;
	}

	public function setITExperience($iTExperience) {
		$this->iTExperience = $iTExperience;
	}

	public function setSalaryExpectation($salaryExpectation) {
		$this->salaryExpectation = $salaryExpectation;
	}

    public function setEducationInfo($educationInfo) {
        $this->educationInfo = $educationInfo;
    }

    public function getEducationInfo() {
        return $this->educationInfo;
    }
               
    public function setEmploymentInfo($employmentInfo) {
        $this->employmentInfo = $employmentInfo;
    }

    public function getEmploymentInfo() {
        return $this->employmentInfo;
    }
            
    public function setLicenseInfo($licenseInfo) {
        $this->licenseInfo = $licenseInfo;
    }

    public function getLicenseInfo() {
        return $this->licenseInfo;
    }
            
    public function setSkillInfo($skillInfo) {
        $this->skillInfo = $skillInfo;
    }

    public function getSkillInfo() {
        return $this->skillInfo;
    }
            
    public function setLanguageInfo($langInfo) {
        $this->langInfo = $langInfo;
    }
            
    public function getLanguageInfo() {
        return $this->langInfo;
    }

    /**
     * Returns event of given type
     * @param $eventType The event type
     * @return JobApplicationEvent The latest event of given type or null if not found
     */
    public function getEventOfType($eventType) {
        $event = null;

        $events = $this->getEvents();
        if (!empty($events)) {

            for($i = count($events) - 1; $i >= 0; $i--) {
                if ($events[$i]->getEventType() == $eventType) {
                    $event = $events[$i];
                    break;
                }
            }
        }

        return $event;
    }

	/**
	 * Save JobApplication object to database
	 *
	 * If a new JobApplication, inserts into the database, otherwise, updates
	 * the existing entry.
	 *
	 * @return int Returns the ID of the JobApplication
	 */
    public function save() {

		if (empty($this->firstName) || empty($this->lastName) || empty($this->email) || empty($this->vacancyId)) {
			throw new JobApplicationException("Attributes not set", JobApplicationException::MISSING_PARAMETERS);
		}
		if (!CommonFunctions::isValidId($this->vacancyId)) {
		    throw new JobApplicationException("Invalid vacancy id", JobApplicationException::INVALID_PARAMETER);
		}

		if (isset($this->id)) {

			if (!CommonFunctions::isValidId($this->id)) {
			    throw new JobApplicationException("Invalid id", JobApplicationException::INVALID_PARAMETER);
			}
			return $this->_update();
		} else {
			return $this->_insert();
		}
    }


    /**
     * Get job application with given id
     *
     * @param int $id Job Application ID
     * @return JobApplication JobApplication object
     */
    public static function getJobApplication($id) {

        if (!CommonFunctions::isValidId($id)) {
            throw new JobApplicationException("Invalid id", JobApplicationException::INVALID_PARAMETER);
        }

        $conditions[] = 'a.' . self::DB_FIELD_ID . ' = ' . $id;
        $list = self::_getList($conditions);
        
        if (count($list) == 1) {
            $application = $list[0];
            
            $eduInfoArray = ApplicantEducationInfo::getApplicantEducationInfo($id);
            $application->setEducationInfo($eduInfoArray);
            
            $employmentInfoArray = ApplicantEmployementInfo::getApplicantEmploymentInfo($id);    
            $application->setEmploymentInfo($employmentInfoArray);
            
            $licenseInfoArray = ApplicantLicenseInformation::getApplicantLicensesInformation($id);
            $application->setLicenseInfo($licenseInfoArray);
            
            $skillInfoArray = ApplicantSkills::getApplicantSkills($id);
            $application->setSkillInfo($skillInfoArray);
            
            $langInfoArray = AppicantLanguageInformation::getAppicantLanguageInformation($id);
            $application->setLanguageInfo($langInfoArray);
        }
        $application = (count($list) == 1) ? $list[0] : null;

        return $application;
    }

    /**
     * Get list of job applications.
     * If optional emp number is given, only job applications associated with given manager
     * are returned.
     *
     * @param int $managerEmpNum Employee number of manager.
     * @return Array Array of JobApplication objects.
     */
    public static function getList($managerEmpNum = null, $sortField = 0, $sortOrder = 'ASC') {

        if (!empty($managerEmpNum) && !CommonFunctions::isValidId($managerEmpNum)) {
            throw new JobApplicationException("Invalid id", JobApplicationException::INVALID_PARAMETER);
        }

        return self::_getList(null, $managerEmpNum, $sortField, $sortOrder);
    }

    /**
     * Get list of short listed applications that are pending
     */
    public static function getPendingShortListedApplications() {

        $fields[0] = 'a.' . self::DB_FIELD_ID;
        $fields[1] = 'a.' . self::DB_FIELD_VACANCY_ID;
        $fields[2] = 'a.' . self::DB_FIELD_FIRSTNAME;
        $fields[3] = 'a.' . self::DB_FIELD_MIDDLENAME;
        $fields[4] = 'a.' . self::DB_FIELD_LASTNAME;
        $fields[5] = 'a.' . self::DB_FIELD_STREET1;
        $fields[6] = 'a.' . self::DB_FIELD_STREET2;
        $fields[7] = 'a.' . self::DB_FIELD_CITY;
        $fields[8] = 'a.' . self::DB_FIELD_COUNTRY_CODE;
        $fields[9] = 'a.' . self::DB_FIELD_PROVINCE;
        $fields[10] = 'a.' . self::DB_FIELD_ZIP;
        $fields[11] = 'a.' . self::DB_FIELD_PHONE;
        $fields[12] = 'a.' . self::DB_FIELD_MOBILE;
        $fields[13] = 'a.' . self::DB_FIELD_EMAIL;
        $fields[14] = 'a.' . self::DB_FIELD_QUALIFICATIONS;
        $fields[15] = 'a.' . self::DB_FIELD_STATUS;
        $fields[16] = 'a.' . self::DB_FIELD_APPLIED_DATETIME;
        $fields[17] = 'a.' . self::DB_FIELD_EMP_NUMBER;
        $fields[18] = 'c.jobtit_name AS ' . self::JOB_TITLE_NAME;
        $fields[19] = "CONCAT(d.`emp_firstname`, ' ', d.`emp_lastname`) AS " . self::HIRING_MANAGER_NAME;
        $fields[20] = 'a.' . self::CV_TYPE;


        $tables[0] = self::TABLE_NAME . ' a';
        $tables[1] = JobVacancy::TABLE_NAME .' b';
        $tables[2] = 'hs_hr_job_title c';
        $tables[3] = 'hs_hr_employee d';
        $tables[4] = JobApplicationEvent::TABLE_NAME . ' e';

        $joinConditions[1] = 'a.' . self::DB_FIELD_VACANCY_ID . ' = b.' . JobVacancy::DB_FIELD_VACANCY_ID;
        $joinConditions[2] = 'b.jobtit_code = c.jobtit_code';
        $joinConditions[3] = 'b.' . JobVacancy::DB_FIELD_MANAGER_ID . ' = d.emp_number';
        $joinConditions[4] = 'a.' . self::DB_FIELD_ID . ' = e.' . JobApplicationEvent::DB_FIELD_APPLICATION_ID;


        $selectCondition[] = '((a.' . self::DB_FIELD_STATUS . ' = ' . self::STATUS_SHORTLISTED . ') AND ' .
                '(e.' . JobApplicationEvent::DB_FIELD_EVENT_TYPE . ' = ' . JobApplicationEvent::EVENT_SHORTLIST.') AND ' .
                '(e.' . JobApplicationEvent::DB_FIELD_NOTIFICATION_STATUS . ' = ' . JobApplicationEvent::NOTIFICATION_STATUS_NONE.') AND ' .
                '(datediff(now(), e.' . JobApplicationEvent::DB_FIELD_CREATED_TIME . ') >= 7 ))' ;

        $sqlBuilder = new SQLQBuilder();
        $sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition);

        $conn = new DMLFunctions();
        $result = $conn->executeQuery($sql);
        $actList = array();

        while ($result && ($row = mysql_fetch_assoc($result))) {
            $actList[] = self::_createFromRow($row);
        }

        return $actList;
    }

	/**
	 * Insert new object to database
	 */
	private function _insert() {

		$this->id = UniqueIDGenerator::getInstance()->getNextID(self::TABLE_NAME, self::DB_FIELD_ID);
        if (empty($this->appliedDateTime)) {
            $this->appliedDateTime = date(LocaleUtil::STANDARD_TIMESTAMP_FORMAT);
        }

		$sqlBuilder = new SQLQBuilder();
		$sqlBuilder->table_name = self::TABLE_NAME;
		$sqlBuilder->flg_insert = 'true';
		$sqlBuilder->arr_insert = $this->_getFieldValuesAsArray();
		$sqlBuilder->arr_insertfield = $this->dbFields;

		$sql = $sqlBuilder->addNewRecordFeature2(false);
		$conn = new DMLFunctions();

        $maxPacketSize = $conn->getMaxAllowedPacketSize();
        if (($maxPacketSize > 0) && (strlen($sql) > $maxPacketSize)) {
            throw new JobApplicationException("File too large. Check MySQL max_allowed_packet configuration in my.ini", JobApplicationException::FILE_TOO_LARGE);
        }

		$result = $conn->executeQuery($sql);
		if (!$result || (mysql_affected_rows() != 1)) {
			throw new JobApplicationException("Insert failed. ", JobApplicationException::DB_ERROR);
		}

		return $this->id;
	}

	/**
	 * Update existing object
	 */
	private function _update() {

		$sqlBuilder = new SQLQBuilder();
		$sqlBuilder->table_name = self::TABLE_NAME;
		$sqlBuilder->flg_update = 'true';
		$sqlBuilder->arr_update = $this->dbFields;
		$sqlBuilder->arr_updateRecList = $this->_getFieldValuesAsArray();

		$sql = $sqlBuilder->addUpdateRecord1(0, false);

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

        $maxPacketSize = $conn->getMaxAllowedPacketSize();
        if (($maxPacketSize > 0) && (strlen($sql) > $maxPacketSize)) {
            throw new JobApplicationException("File too large. Check MySQL max_allowed_packet configuration in my.ini", JobApplicationException::FILE_TOO_LARGE);
        }

		// Here we don't check mysql_affected_rows because update may be called
		// without any changes.
		if (!$result) {
			throw new JobApplicationException("Update failed. SQL=$sql", JobApplicationException::DB_ERROR);
		}
		return $this->id;
	}

    /**
     * Get a list of jobs applications with the given conditions.
     *
     * @param array  $selectCondition Array of select conditions to use.
     * @param String $filterForManagerId Filter by the given manager
     * @return array Array of JobApplication objects. Returns an empty (length zero) array if none found.
     */
    private static function _getList($selectCondition = null, $filterForManagerId = null, $sortField = 0, $sortOrder = 'ASC') {

        $fields[0] = 'a.' . self::DB_FIELD_ID;
        $fields[1] = 'a.' . self::DB_FIELD_VACANCY_ID;
        $fields[2] = 'a.' . self::DB_FIELD_FIRSTNAME;
        $fields[3] = 'a.' . self::DB_FIELD_MIDDLENAME;
        $fields[4] = 'a.' . self::DB_FIELD_LASTNAME;
        $fields[5] = 'a.' . self::DB_FIELD_STREET1;
        $fields[6] = 'a.' . self::DB_FIELD_STREET2;
        $fields[7] = 'a.' . self::DB_FIELD_CITY;
        $fields[8] = 'a.' . self::DB_FIELD_COUNTRY_CODE;
        $fields[9] = 'a.' . self::DB_FIELD_PROVINCE;
        $fields[10] = 'a.' . self::DB_FIELD_ZIP;
        $fields[11] = 'a.' . self::DB_FIELD_PHONE;
        $fields[12] = 'a.' . self::DB_FIELD_MOBILE;
        $fields[13] = 'a.' . self::DB_FIELD_EMAIL;
        $fields[14] = 'a.' . self::DB_FIELD_QUALIFICATIONS;
        $fields[15] = 'a.' . self::DB_FIELD_STATUS;
        $fields[16] = 'a.' . self::DB_FIELD_APPLIED_DATETIME;
        $fields[17] = 'a.' . self::DB_FIELD_EMP_NUMBER;
        $fields[18] = 'c.jobtit_name AS ' . self::JOB_TITLE_NAME;
        $fields[19] = "CONCAT(d.`emp_firstname`, ' ', d.`emp_lastname`) AS " . self::HIRING_MANAGER_NAME;
        $fields[20] = 'a.' . self::CV_TYPE;
        
        $fields[21] = 'a.' . self::DB_FIELD_DATE_OF_BIRTH;
        $fields[22] = 'a.' . self::DB_FIELD_GENDER;
        $fields[23] = 'a.' . self::DB_FIELD_SALARY_EXPECTED;
        $fields[24] = 'a.' . self::DB_FIELD_IT_EXPERIENCE;
        $fields[25] = 'a.' . self::DB_FIELD_AVALIABLIITY_TO_START;
        $fields[26] = 'a.' . self::DB_FIELD_BASIS_OF_EMPLOYMENT;
        $fields[27] = 'a.' . self::DB_FIELD_DO_YOU_HAVE_A_CAR;
        
        $tables[0] = self::TABLE_NAME . ' a';
        $tables[1] = JobVacancy::TABLE_NAME .' b';
        $tables[2] = 'hs_hr_job_title c';
        $tables[3] = 'hs_hr_employee d';

        $joinConditions[1] = 'a.' . self::DB_FIELD_VACANCY_ID . ' = b.' . JobVacancy::DB_FIELD_VACANCY_ID;
        $joinConditions[2] = 'b.jobtit_code = c.jobtit_code';
        $joinConditions[3] = 'b.' . JobVacancy::DB_FIELD_MANAGER_ID . ' = d.emp_number';

        $groupBy = null;

        if (!empty($filterForManagerId)) {
            $tables[4] = JobApplicationEvent::TABLE_NAME . ' e';
            $joinConditions[4] = 'a.' . self::DB_FIELD_ID . ' = e.' . JobApplicationEvent::DB_FIELD_APPLICATION_ID;
            $selectCondition[] = '((b.' . JobVacancy::DB_FIELD_MANAGER_ID . ' = ' . $filterForManagerId . ') OR ' .
                    '(e.' . JobApplicationEvent::DB_FIELD_OWNER . ' = '.$filterForManagerId.'))' ;
            $groupBy = 'a.' . self::DB_FIELD_ID;
        }

        $sqlBuilder = new SQLQBuilder();
        $sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition, null, null, null, null, $groupBy);

        $orderBy = self::_getOrderBy($sortField, $sortOrder);
        if (!empty($orderBy)) {
            $sql .= ' ' . $orderBy;
        }

        $conn = new DMLFunctions();
        $result = $conn->executeQuery($sql);
        $actList = array();

        while ($result && ($row = mysql_fetch_assoc($result))) {
            $actList[] = self::_createFromRow($row);
        }

        return $actList;
    }

    /**
     * Return ORDER BY SQL clause
     */
    private function _getOrderBy($sortField, $sortOrder) {

        if (($sortOrder !== 'ASC') && ($sortOrder !== 'DESC')) {
            $sortOrder = 'ASC';
        }

        // default: sort by applicant name
        $orderBy = 'a.' . self::DB_FIELD_FIRSTNAME . ' ' . $sortOrder . ', ' .
            'a.' . self::DB_FIELD_LASTNAME . ' ' . $sortOrder;

        switch ($sortField) {

            case 0:
                // applicant name: default
                break;

            case 1:
                // position applied
                $orderBy = self::JOB_TITLE_NAME . ' ' . $sortOrder;
                break;

            case 2:
                // Hiring Manager name
                $orderBy = self::HIRING_MANAGER_NAME . ' ' . $sortOrder;
                break;

            case 3:
                // Application status
                $orderBy = 'a.' .self::DB_FIELD_STATUS . ' ' . $sortOrder;
                break;

            default:
                break;
        }
        return 'ORDER BY ' . $orderBy;
    }

	/**
	 * Returns the db field values as an array
	 *
	 * @return Array Array containing field values in correct order.
	 */
	private function _getFieldValuesAsArray() {

		$values[0] = $this->_escapeField($this->id);
		$values[1] = $this->_escapeField($this->vacancyId);
		$values[2] = $this->_escapeField($this->firstName);
		$values[3] = $this->_escapeField($this->middleName);
		$values[4] = $this->_escapeField($this->lastName);
		$values[5] = $this->_escapeField($this->street1);
		$values[6] = $this->_escapeField($this->street2);
		$values[7] = $this->_escapeField($this->city);
		$values[8] = $this->_escapeField($this->country);
		$values[9] = $this->_escapeField($this->province);
		$values[10] = $this->_escapeField($this->zip);
		$values[11] = $this->_escapeField($this->phone);
		$values[12] = $this->_escapeField($this->mobile);
		$values[13] = $this->_escapeField($this->email);
		$values[14] = $this->_escapeField($this->qualifications);
        $values[15] = is_null($this->status) ? self::STATUS_SUBMITTED : $this->_escapeField($this->status);
        $values[16] = is_null($this->appliedDateTime) ? 'null' : $this->_escapeField($this->appliedDateTime);
        $values[17] = empty($this->empNumber) ? 'null' : $this->_escapeField($this->empNumber);
        $values[18] = $this->_prepareAttachmentData($this->cvData);
        $values[19] = $this->_escapeField($this->cvType);
        $values[20] = $this->_escapeField($this->cvExtention);

        $values[21] = $this->_escapeField($this->dateOfbirth);
        $values[22] = $this->_escapeField($this->gender);
        $values[23] = $this->_escapeField($this->salaryExpectation);
        $values[24] = $this->_escapeField($this->iTExperience);
        $values[25] = $this->_escapeField($this->availabilityToStart);
        $values[26] = $this->_escapeField($this->basisOfemployment);
        $values[27] = $this->_escapeField($this->doYouHaveACar);

		return $values;
	}

    private function _escapeField($value) {

        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        $value = mysql_real_escape_string($value);
        return "'" . $value . "'";
    }

    private function _prepareAttachmentData($value) {
        return "'" . addslashes($value) . "'";
    }

    /**
     * Creates a JobApplication object from a resultset row
     *
     * @param array $row Resultset row from the database.
     * @return JobApplication JobApplication object.
     */
    private static function _createFromRow($row) {

        $application = new JobApplication($row[self::DB_FIELD_ID]);
        $application->setVacancyId($row[self::DB_FIELD_VACANCY_ID]);
        $application->setFirstName($row[self::DB_FIELD_FIRSTNAME]);
        $application->setMiddleName($row[self::DB_FIELD_MIDDLENAME]);
        $application->setLastName($row[self::DB_FIELD_LASTNAME]);
        $application->setStreet1($row[self::DB_FIELD_STREET1]);
        $application->setStreet2($row[self::DB_FIELD_STREET2]);
        $application->setCity($row[self::DB_FIELD_CITY]);
        $application->setCountry($row[self::DB_FIELD_COUNTRY_CODE]);
        $application->setProvince($row[self::DB_FIELD_PROVINCE]);
        $application->setZip($row[self::DB_FIELD_ZIP]);
        $application->setPhone($row[self::DB_FIELD_PHONE]);
        $application->setMobile($row[self::DB_FIELD_MOBILE]);
        $application->setEmail($row[self::DB_FIELD_EMAIL]);
        $application->setQualifications($row[self::DB_FIELD_QUALIFICATIONS]);
        $application->setStatus($row[self::DB_FIELD_STATUS]);
        $application->setAppliedDateTime($row[self::DB_FIELD_APPLIED_DATETIME]);
        $application->setEmpNumber($row[self::DB_FIELD_EMP_NUMBER]);
        $application->setCvType($row[self::CV_TYPE]);
        
        $application->setDateOfbirth($row[self::DB_FIELD_DATE_OF_BIRTH]);
        $application->setGender($row[self::DB_FIELD_GENDER]);
        $application->setSalaryExpectation($row[self::DB_FIELD_SALARY_EXPECTED]);
        $application->setAvailabilityToStart($row[self::DB_FIELD_AVALIABLIITY_TO_START]);
        $application->setBasisOfemployment($row[self::DB_FIELD_BASIS_OF_EMPLOYMENT]);
        $application->setITExperience($row[self::DB_FIELD_IT_EXPERIENCE]);
        $application->setDoYouHaveACar($row[self::DB_FIELD_DO_YOU_HAVE_A_CAR]);      

        if (isset($row[self::JOB_TITLE_NAME])) {
            $application->setJobTitleName($row[self::JOB_TITLE_NAME]);
        }

        if (isset($row[self::HIRING_MANAGER_NAME])) {
            $application->setHiringManagerName($row[self::HIRING_MANAGER_NAME]);
        }

        return $application;
    }
    public function fetchCvDataObject(){
    	$sqlBuilder=new SQLQBuilder();
    	$fields[]=self::DB_FIELD_ID;
    	$fields[]=self::CV_TYPE;
    	$fields[]=self::CV_DATA;
    	$fields[]=self::CV_EXTENSION;

    	$condition[]=self::DB_FIELD_ID."='".$this->getId()."'";

    	$sql=$sqlBuilder->simpleSelect(self::TABLE_NAME,$fields,$condition);
    	$conn = new DMLFunctions();
        $result = $conn->executeQuery($sql);

     	while ($result && ($row = mysql_fetch_assoc($result))) {
        	$obj=new JobApplication();
        	$obj->setId($row[self::DB_FIELD_ID]);
        	$obj->setCvType($row[self::CV_TYPE]);
        	$obj->setCvData($row[self::CV_DATA]);
        	$obj->setCvExtention($row[self::CV_EXTENSION]);
        	break;
        }
        return $obj;
    }

}

class JobApplicationException extends Exception {
	const INVALID_PARAMETER = 0;
	const MISSING_PARAMETERS = 1;
	const DB_ERROR = 2;
    const INVALID_STATUS = 3;
    const FILE_TOO_LARGE = 4;
}

