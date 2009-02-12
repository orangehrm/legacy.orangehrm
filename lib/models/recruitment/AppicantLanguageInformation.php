<?php
/**
 * "Visual Paradigm: DO NOT MODIFY THIS FILE!"
 *
 * This is an automatic generated file. It will be regenerated every time
 * you generate persistence class.
 *
 * Modifying its content may cause the program not work, or your work may lost.
 */
require_once ROOT_PATH . '/lib/confs/Conf.php';
require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';
require_once ROOT_PATH . '/lib/common/LocaleUtil.php';
require_once ROOT_PATH . '/lib/common/UniqueIDGenerator.php';
/**
 * Licensee: Anonymous
 * License Type: Purchased
 */

/**
 * @orm AppicantLanguageInformation
 */
class AppicantLanguageInformation {

	const TABLE = 'hs_hr_appicant_language_information';

	const ID = 'ID';
	private $iD;
	/**
	 * @orm has one Lang inverse(applicantLangInfo)
	 * @dbva fk(langlang_code)
	 */
	const LANG_CODE = 'lang_code';
	private $langCode;
	private $lang;

	/**
	 * @orm has one Fluency inverse(applicantLangInfo)
	 * @dbva fk(fluency_code)
	 */
	const FLUENCY_CODE = 'fluency_code';
	private $fluencyCode;
	private $fluency;

	/**
	 * @orm has one Application inverse(applicantLangInfo)
	 * @dbva fk(application_id)
	 */
	const APPLICATION_ID = 'application_id';
	private $applicationId;
	private $application;

	public function getID() {
		return $this->iD;
	}

	public function setID($iD) {
		$this->iD = $iD;
	}

	public function getLang() {
		return $this->lang;
	}

	public function setLang($lang) {
		$this->lang = $lang;
	}

	public function getFluency() {
		return $this->fluency;
	}

	public function setFluency($fluency) {
		$this->fluency = $fluency;
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

	public function getFluencyCode() {
		return $this->fluencyCode;
	}

	public function setFluencyCode($fluencyCode) {
		$this->fluencyCode = $fluencyCode;
	}

	public function getLangCode() {
		return $this->langCode;
	}

	public function setLangCode($langCode) {
		$this->langCode = $langCode;
	}

	public function save() {
		$sqlBuilder = new SQLQBuilder ( );
		$insetFields [] = self::APPLICATION_ID;
		$insetFields [] = self::FLUENCY_CODE;
		$insetFields [] = self::LANG_CODE;

		$values [] = $this->getApplicationId ();
		$values [] = $this->getFluencyCode ();
		$values [] = $this->getLangCode ();

		$sql = $sqlBuilder->simpleInsert ( self::TABLE, $values, $insetFields );
		$conn = new DMLFunctions ( );
		$result = $conn->executeQuery ( $sql );
		return $result;
	}

	public static function getAppicantLanguageInformation($appId) {
		$sqlBuilder = new SQLQBuilder ( );
		$selectFields [] = " * ";
		$selectConditions [] = self::APPLICATION_ID . "=" . $appId;
		$sql = $sqlBuilder->simpleSelect ( self::TABLE, $selectFields, $selectConditions );
		$conn = new DMLFunctions ( );
		$result = $conn->executeQuery ( $sql );
		$tempObj = new AppicantLanguageInformation();
		$objArray = $tempObj->_buildObjArr ( $result );
		//echo "<pre>"; print_r($objArray);exit;
		return $objArray;
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
		while ( $row = mysql_fetch_assoc ( $result ) ) {
			$obj = new AppicantLanguageInformation ( );
			$obj->setApplicationId ( $row [self::APPLICATION_ID] );
			$obj->setFluencyCode ( $row [self::FLUENCY_CODE] );
			$obj->setID ( $row [self::ID] );
			$obj->setLangCode ( $row [self::LANG_CODE] );
			$objectArray [] = $obj;
		}
		return $objectArray;
	}
}

?>
