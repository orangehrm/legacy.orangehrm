<?php

/**
 * @orm ApplicantLicenseInformation
 */
class ApplicantLicenseInformation {
	
	const TABLE = 'applicant_license_information';
	/**
	 * @orm expiry_date date
	 */
	const EXPIRY_DATE = 'expiry_date';
	private $expiryDate;
	
	/**
	 * @orm ID int
	 * @dbva id(autogenerate) 
	 */
	const ID = 'id';
	private $iD;
	
	/**
	 * @orm has one License inverse(applicantLicenseInfo)
	 * @dbva fk(license_code) 
	 */
	const LICENSE_CODE = 'license_code';
	private $lecenseCode;
	private $license;
	
	/**
	 * @orm has one Application inverse(_applicantLicenseInfo)
	 * @dbva fk(application_id) 
	 */
	const APPLICATION_ID = 'application_id';
	private $applicationId;
	private $application;
	
	public function getExpiryDate() {
		return $this->expiryDate;
	}
	
	public function setExpiryDate($expiryDate) {
		$this->expiryDate = $expiryDate;
	}
	
	public function getID() {
		return $this->iD;
	}
	
	public function setID($iD) {
		$this->iD = $iD;
	}
	
	public function getLicense() {
		return $this->license;
	}
	
	public function setLicense($license) {
		$this->license = $license;
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
	
	public function getLecenseCode() {
		return $this->lecenseCode;
	}
	
	public function setLecenseCode($lecenseCode) {
		$this->lecenseCode = $lecenseCode;
	}
	
	public function getApplicantLicenseInformation() {
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
		$insetFields [] = self::EXPIRY_DATE;
		$insetFields [] = self::ID;
		$insetFields [] = self::LICENSE_CODE;
		
		$values [] = $this->getApplicationId ();
		$values [] = $this->getExpiryDate ();
		$values [] = $this->getID ();
		
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
			$obj = new ApplicantLicenseInformation ( );
			$obj->setApplicationId ( $row [self::APPLICATION_ID] );
			$obj->setExpiryDate ( $row [self::EXPIRY_DATE] );
			$obj->setID ( $row [self::ID] );
			$obj->setLecenseCode ( $row [self::LICENSE_CODE] );
			$objectArray [] = $obj;
		}
		return $objectArray;
	}

}

?>
