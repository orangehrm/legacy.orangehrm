<?php
/**
 * "Visual Paradigm: DO NOT MODIFY THIS FILE!"
 * 
 * This is an automatic generated file. It will be regenerated every time 
 * you generate persistence class.
 * 
 * Modifying its content may cause the program not work, or your work may lost.
 */

/**
 * Licensee: Anonymous
 * License Type: Purchased
 */

/**
 * @orm ApplicantEducationInfo
 */
class ApplicantEducationInfo {
	
	const TABLE = 'hs_hr_applicant_education_info';
	/**
	 * @orm major_specialization char
	 */
	const MAJOR_SPECISLIZATION = 'major_specialization';
	private $majorSpecialization;
	
	/**
	 * @orm year_completed int
	 */
	const YEAR_COMPLEATED = 'year_completed';
	private $yearCompleted;
	
	/**
	 * @orm average_score float
	 */
	const AVERAGE_SCORE = 'average_score';
	private $averageScore;
	
	const ID = 'id';
	private $iD;
	
	/**
	 * @orm has one Education inverse(applicantEducationInfo)
	 * @dbva fk(edu_code) 
	 */
	const EDU_CODE = 'edu_code';
	private $eduCode;
	private $education;
	
	/**
	 * @orm has one Application inverse(applicatnEducationInfo)
	 * @dbva fk(application_id) 
	 */
	const APPLICATION_ID = 'application_id';
	private $applicationId;
	private $application;
	
	public function getMajorSpecialization() {
		return $this->majorSpecialization;
	}
	
	public function setMajorSpecialization($majorSpecialization) {
		$this->majorSpecialization = $majorSpecialization;
	}
	
	public function getYearCompleted() {
		return $this->yearCompleted;
	}
	
	public function setYearCompleted($yearCompleted) {
		$this->yearCompleted = $yearCompleted;
	}
	
	public function getAverageScore() {
		return $this->averageScore;
	}
	
	public function setAverageScore($averageScore) {
		$this->averageScore = $averageScore;
	}
	
	public function getID() {
		return $this->iD;
	}
	
	public function setID($iD) {
		$this->iD = $iD;
	}
	
	public function getEducation() {
		return $this->education;
	}
	
	public function setEducation($education) {
		$this->education = $education;
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
	
	public function getEduCode() {
		return $this->eduCode;
	}
	
	public function setEduCode($eduCode) {
		$this->eduCode = $eduCode;
	}
	
	public function getApplicantEducationInfo() {
		$sqlBuilder = new SQLQBuilder ( );
		$selectFields [] = " * ";
		$selectConditions [] = self::ID . "=" . $this->getID ();
		$sql = $sqlBuilder->simpleSelect ( self::TABLE, $selectFields, $selectConditions );
		$conn = new DMLFunctions ( );
		$result = $conn->executeQuery ( $sql );
		$objArray = $this->_buildObjArr ( $result );
		return $objArray;
	}
	
	public function save() {
		$sqlBuilder = new SQLQBuilder ( );
		$insetFields [] = self::APPLICATION_ID;
		$insetFields [] = self::AVERAGE_SCORE;
		$insetFields [] = self::EDU_CODE;		
		$insetFields [] = self::YEAR_COMPLEATED;
		
		$values [] = $this->getApplicationId ();
		$values [] = $this->getAverageScore ();
		$values [] = $this->getEduCode ();
		$values [] = $this->getYearCompleted ();
		
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
			$obj = new ApplicantEducationInfo ( );
			$obj->setApplicationId ( $row [self::APPLICATION_ID] );
			$obj->setEduCode ( $row [self::EDU_CODE] );
			$obj->setID ( $row [self::ID] );
			$obj->setMajorSpecialization ( $row [self::MAJOR_SPECISLIZATION] );
			$obj->setYearCompleted ( $row [self::YEAR_COMPLEATED] );
			$objectArray [] = $obj;
		}
		return $objectArray;
	}

}

?>
