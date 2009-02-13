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
require_once ROOT_PATH . '/lib/models/eimadmin/Fluency.php';

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


    /**
     * Values retrieved from other tables.
     */
    private $langName;
    private $fluencyName;

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
    
    public function getLangName() {
        return $this->langName;
    }

    public function setLangName($langName) {
        $this->langName = $langName;
    }    

    public function getFluencyName() {
        return $this->fluencyName;
    }

    public function setFluencyName($fluencyName) {
        $this->fluencyName = $fluencyName;
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
        
        $fields[] = 'a.' . self::ID;
        $fields[] = 'a.' . self::APPLICATION_ID;
        $fields[] = 'a.' . self::LANG_CODE;
        $fields[] = 'a.' . self::FLUENCY_CODE;
        $fields[] = 'b.lang_name';
        $fields[] = 'c.' . Fluency::DESCRIPTION;
        
        $tables[0] = self::TABLE . ' a';
        $tables[1] = 'hs_hr_language b';
        $tables[2] = Fluency::TABLE . ' c';

        $joinConditions[1] = 'a.' . self::LANG_CODE . ' = b.lang_code';
        $joinConditions[2] = 'a.' . self::FLUENCY_CODE . ' = c.' . Fluency::FLUENCY_CODE;

        $selectCondition[] = 'a.' . self::APPLICATION_ID . ' = ' . $appId;
        
        $sqlBuilder = new SQLQBuilder();
        $sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition);

        $conn = new DMLFunctions();
        $result = $conn->executeQuery($sql);
		return self::_buildObjArr($result);
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

	private static function _buildObjArr($result) {
		$objectArray = array ();
		while ( $row = mysql_fetch_assoc ( $result ) ) {
			$obj = new AppicantLanguageInformation ( );
            $obj->setID ( $row [self::ID] );
			$obj->setApplicationId ( $row [self::APPLICATION_ID] );
			$obj->setFluencyCode ( $row [self::FLUENCY_CODE] );
			$obj->setLangCode ( $row [self::LANG_CODE] );
            $obj->setLangName ( $row ['lang_name'] );
            $obj->setFluencyName ( $row [Fluency::DESCRIPTION] );
			$objectArray [] = $obj;
		}
		return $objectArray;
	}
}

?>
