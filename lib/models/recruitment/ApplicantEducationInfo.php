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

/*
 * @orm ApplicantEducationInfo
 */
class ApplicantEducationInfo {

	const TABLE = 'hs_hr_applicant_education_info';
	/**
	 * @orm major_specialization char
	 */
	const MAJOR_SPECIALIZATION = 'major_specialization';
	private $majorSpecialization;

	/**
	 * @orm year_completed int
	 */
	const YEAR_COMPLETED = 'year_completed';
	private $yearCompleted;

	/**
	 * @orm average_score float
	 */
	const AVERAGE_SCORE = 'average_score';
	private $averageScore;

	const ID = 'ID';
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

	public static function getApplicantEducationInfo($appId) {
		$sqlBuilder = new SQLQBuilder ( );
		$selectFields [] = " * ";
		$selectConditions [] = self::APPLICATION_ID . "=" . $appId;
		$sql = $sqlBuilder->simpleSelect ( self::TABLE, $selectFields, $selectConditions );
		//echo $sql;
		$conn = new DMLFunctions ( );
		$result = $conn->executeQuery ( $sql );
		$tempObj = new ApplicantEducationInfo();
		$objArray = $tempObj->_buildObjArr ( $result );
		//print_r($objArray);
		return $objArray;
	}

	public function save() {
		$sqlBuilder = new SQLQBuilder ( );
		$insetFields [] = self::APPLICATION_ID;
		$insetFields [] = self::AVERAGE_SCORE;
		$insetFields [] = self::EDU_CODE;
		$insetFields [] = self::YEAR_COMPLETED;
        $insetFields [] = self::MAJOR_SPECIALIZATION;

		$values [] = $this->getApplicationId ();
		$values [] = $this->getAverageScore ();
		$values [] = $this->getEduCode ();
		$values [] = $this->getYearCompleted ();
        $values [] = $this->getMajorSpecialization ();

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
		//echo self::APPLICATION_ID;exit;
		while ( $row = mysql_fetch_assoc ( $result ) ) {
			$obj = new ApplicantEducationInfo ( );
			$obj->setApplicationId ( $row[self::APPLICATION_ID] );
			$obj->setEduCode ( $row[self::EDU_CODE] );
			$obj->setID ( $row[self::ID] );
			$obj->setMajorSpecialization ( $row[self::MAJOR_SPECIALIZATION] );
			$obj->setYearCompleted ( $row[self::YEAR_COMPLETED] );
			$obj->setAverageScore( $row[self::AVERAGE_SCORE] );
			$objectArray [] = $obj;
		}
		//echo "<pre>"; print_r($objectArray);
		return $objectArray;
	}

}

?>
