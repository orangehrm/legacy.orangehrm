<?php

/**
 * Licensee: Anonymous
 */

/**
 * @orm EmployementInfo
 */
class ApplicantEmployementInfo {
	
	const TABLE = 'hs_hr_applicant_employement_info';
	/**
	 * @orm employer char
	 */
	const EMPLOYER = 'employer';
	private $employer;
	
	/**
	 * @orm job_title char
	 */
	const JOB_TITLE = 'job_title';
	private $jobTitle;
	
	/**
	 * @orm start_date char
	 */
	const START_DATE = 'start_date';
	private $startDate;
	
	/**
	 * @orm end_date char
	 */
	const END_DATE = 'end_date';
	private $endDate;
	
	/**
	 * @orm duties char
	 */
	const DUTIES = 'duties';
	private $duties;
	
	/**
	 * @orm ID int
	 * @dbva id(autogenerate) 
	 */
	const ID = 'id';
	private $iD;
	
	/**
	 * @orm has one Application inverse(emplyeementInfo)
	 * @dbva fk(application_id) 
	 */
	const APPLICATION_ID = 'application_id';
	private $applicationId;
	private $application;
	
	public function getEmployer() {
		return $this->employer;
	}
	
	public function setEmployer($employer) {
		$this->employer = $employer;
	}
	
	public function getJobTitle() {
		return $this->jobTitle;
	}
	
	public function setJobTitle($jobTitle) {
		$this->jobTitle = $jobTitle;
	}
	
	public function getStartDate() {
		return $this->startDate;
	}
	
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}
	
	public function getEndDate() {
		return $this->endDate;
	}
	
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}
	
	public function getDuties() {
		return $this->duties;
	}
	
	public function setDuties($duties) {
		$this->duties = $duties;
	}
	
	public function getID() {
		return $this->iD;
	}
	
	public function setID($iD) {
		$this->iD = $iD;
	}
	
	public function getApplication() {
		return $this->application;
	}
	
	public function setApplication($application) {
		$this->application = $application;
	}
	
	public function getApplicationId() {
		return $this->applicationId;
	}
	
	public function setApplicationId($applicationId) {
		$this->applicationId = $applicationId;
	}

	public function save() {
		$sqlBuilder = new SQLQBuilder ( );
		$insetFields [] = self::APPLICATION_ID;
		$insetFields [] = self::DUTIES;
		$insetFields [] = self::EMPLOYER;
		$insetFields [] = self::END_DATE;
		//$insetFields [] = self::ID;
		$insetFields [] = self::JOB_TITLE;
		$insetFields [] = self::START_DATE;	
		
		$values [] = $this->getApplicationId();
		$values [] = $this->getDuties();
		$values [] = $this->getEmployer();
		$values [] = $this->getEndDate();
		//$values [] = $this->getID();
		$values [] = $this->getJobTitle();
		$values [] = $this->getStartDate();		
		
		$sql = $sqlBuilder->simpleInsert ( self::TABLE, $values, $insetFields );
		$conn = new DMLFunctions ( );
		$result = $conn->executeQuery ( $sql );
		return $result;
	}
	
	public function delete() {
		$sqlBuilder = new SQLQBuilder ( );
		$deleteCondtions [] = self::ID . "=" . $this->getID ();
		$sql = $sqlBuilder->simpleDelete ( self::TABLE, $deleteCondtions );
		$conn = new DMLFunctions ( );
		$result = $conn->executeQuery ( $sql );
		return $result;
	}
	
	public function update() {
		//TODO: Implement Method
	}
	
	private function _buildObjArr($result) {
		$objectArray = array ();
		while ( $result ( $row = mysql_fetch_assoc ( $result ) ) ) {
			$obj = new ApplicantEmployementInfo();
			$obj->setApplicationId($row [self::APPLICATION_ID]);
			$obj->setDuties($row [self::DUTIES]);
			$obj->setEmployer($row [self::EMPLOYER]);
			$obj->setEndDate($row [self::END_DATE]);
			$obj->setID($row [self::ID]);
			$obj->setJobTitle($row [self::JOB_TITLE]);
			$obj->setStartDate($row [self::START_DATE]);
			$objectArray [] = $obj;
		}
		return $objectArray;
	}

}

?>
