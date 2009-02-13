<?php
/**
 * Licensee: Anonymous
 */

/**
 * @orm ApplicantSkills
 */
class ApplicantSkills {
	const TABLE = 'hs_hr_applicant_skills';
	/**
	 * @orm years_of_experience int
	 */
	const YEARS_OF_EXP = 'years_of_experience';
	private $yearsOfExperience;

	/**
	 * @orm comments char
	 */
	const COMMENTS = 'comments';
	private $comments;

	/**
	 * @orm ID int
	 * @dbva id(autogenerate)
	 */
	const ID = 'ID';
	private $iD;

	/**
	 * @orm has one Application inverse(applicantSkills)
	 * @dbva fk(application_id)
	 */
	const APPLICATION_ID = 'application_id';
	private $applicationId;
	private $application;

	/**
	 * @orm has one Skill inverse(applicantSkills)
	 * @dbva fk(skill_code)
	 */
	const SKILL_CODE = 'skill_code';
	private $skillCode;
	private $skill;

	public function getYearsOfExperience() {
		return $this->yearsOfExperience;
	}

	public function setYearsOfExperience($yearsOfExperience) {
		$this->yearsOfExperience = $yearsOfExperience;
	}

	public function getComments() {
		return $this->comments;
	}

	public function setComments($comments) {
		$this->comments = $comments;
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

	public function getSkill() {
		return $this->skill;
	}

	public function setSkill($skill) {
		$this->skill = $skill;
	}

	public function getApplicationId() {
		return $this->applicationId;
	}

	public function setApplicationId($applicationId) {
		$this->applicationId = $applicationId;
	}

	public function getSkillCode() {
		return $this->skillCode;
	}

	public function setSkillCode($skillCode) {
		$this->skillCode = $skillCode;
	}

	public static function getApplicantSkills($appId) {
            
        $fields[] = 'a.' . self::ID;
        $fields[] = 'a.' . self::APPLICATION_ID;
        $fields[] = 'a.' . self::YEARS_OF_EXP;
        $fields[] = 'a.' . self::COMMENTS;        
        $fields[] = 'a.' . self::SKILL_CODE;
        $fields[] = 'b.skill_name';
        
        $tables[0] = self::TABLE . ' a';
        $tables[1] = 'hs_hr_skill b';

        $joinConditions[1] = 'a.' . self::SKILL_CODE . ' = b.skill_code';
        $selectCondition[] = 'a.' . self::APPLICATION_ID . ' = ' . $appId;
        
        $sqlBuilder = new SQLQBuilder();
        $sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition);

        $conn = new DMLFunctions();
        $result = $conn->executeQuery($sql);
        return self::_buildObjArr($result);
	}

	public function save() {
		$sqlBuilder = new SQLQBuilder ( );
		$insetFields [] = self::APPLICATION_ID;
		$insetFields [] = self::COMMENTS;
		$insetFields [] = self::ID;
		$insetFields [] = self::SKILL_CODE;
		$insetFields [] = self::YEARS_OF_EXP;

		$values [] = $this->getApplicationId();
		$values [] = $this->getComments();
		$values [] = $this->getID();
		$values [] = $this->getSkillCode();
		$values [] = $this->getYearsOfExperience();

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
		while ( $row = mysql_fetch_assoc ( $result ) ) {
			$obj = new ApplicantSkills ( );
			$obj->setApplicationId ( $row [self::APPLICATION_ID] );
			$obj->setComments ( $row [self::COMMENTS] );
			$obj->setID ( $row [self::ID] );
			$obj->setSkillCode ( $row [self::SKILL_CODE] );
			$obj->setYearsOfExperience ( $row [self::YEARS_OF_EXP] );
            $obj->setSkill($row ['skill_name'] );
			$objectArray [] = $obj;
		}
		return $objectArray;
	}

}

?>
