<?php
require_once ROOT_PATH . '/lib/common/LocaleUtil.php';
/**
 * @orm ApplicantLicenseInformation
 */
class ApplicantLicenseInformation {

	const TABLE = 'hs_hr_applicant_license_information';
	/**
	 * @orm expiry_date date
	 */
	const EXPIRY_DATE = 'expiry_date';
	private $expiryDate;

	/**
	 * @orm ID int
	 * @dbva id(autogenerate)
	 */
	const ID = 'ID';
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

		public static function getApplicantLicensesInformation($appId){
		$sqlBuilder = new SQLQBuilder ( );
		$selectFields [] = " * ";
		$selectConditions [] = self::APPLICATION_ID . "=" . $appId;
		$sql = $sqlBuilder->simpleSelect ( self::TABLE, $selectFields, $selectConditions );
		//echo $sql;
		$conn = new DMLFunctions ( );
		$result = $conn->executeQuery ( $sql );
		$tempObj = new ApplicantLicenseInformation();
		$objArray = $tempObj->_buildObjArr ( $result );
		//echo "<pre>"; print_r($objArray);exit;
		return $objArray;

	}

	public function save() {
		$sqlBuilder = new SQLQBuilder ( );

		$insetFields [] = self::APPLICATION_ID;
		$values [] = $this->getApplicationId ();

		$insetFields [] = self::LICENSE_CODE;
		$values [] = $this->getLecenseCode();

		if(strlen( $this->getExpiryDate ()>0)){
			$insetFields [] = self::EXPIRY_DATE;
			$values [] = LocaleUtil::getInstance()->convertToStandardDateFormat($this->getExpiryDate ());
		}
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
		while ( $row = mysql_fetch_assoc ( $result )  ) {
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
