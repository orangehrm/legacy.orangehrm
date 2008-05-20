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

class JobTitleConfig {

	const TABLE_NAME = 'hs_hr_job_title_config';

	/** Database fields */
	const DB_FIELD_ROLE = 'role';
	const DB_FIELD_JOB_TITLE_CODE = 'jobtit_code';

	const FIELD_JOB_TITLE_NAME = 'jobtit_name';
	
	const ROLE_REVIEW_APPROVER = 1;

	private $role;
	private $jobTitles;

	/**
	 * Constructor
	 *
	 * @param int $role
	 */
	public function __construct($role) {
		$this->role = $role;
		$this->jobTitles = array();		
	}

	public function setJobTitles($jobTitles) {
		$this->jobTitles = $jobTitles;
	}

	public function getRole() {
		return $this->role;
	}

	public function getJobTitles() {
		return $this->jobTitles;
	}

	/**
	 * Save Job title config object to database
	 */
    public function save() {
    	
		// Delete existing job title assignments		
		$sql = sprintf("DELETE FROM %s WHERE %s = %s", self::TABLE_NAME,
		                self::DB_FIELD_ROLE, $this->role);
		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);
				
		// Assign new job titles
		if (!empty($this->jobTitles)) {
			$sql = sprintf("INSERT INTO %s (%s, %s) VALUES " , self::TABLE_NAME, 
				self::DB_FIELD_ROLE, self::DB_FIELD_JOB_TITLE_CODE);
			
			$valueSql = "";
			foreach ($this->jobTitles as $jobTitle) {
				$jobTitleCode = $jobTitle['jobtit_code'];
				
				if (!empty($valueSql)) {
					$valueSql .= ', ';
				}
				$valueSql .= sprintf("(%d, '%s')", $this->role, $jobTitleCode);				
			}
			
			$sql .= $valueSql;
			$result = $conn->executeQuery($sql);
			if (!$result) {
				throw new JobTitleConfigException("Assign job titles to role failed. SQL=$sql", JobTitleConfigException::DB_ERROR);
			}			
		}    	
    }
    
    /**
     * Check if given role is a valid role.
     */
    private static function _isValidRole($role) {
    	$allRoles = self::getAllRoles();
    	return (in_array($role, $allRoles));
    } 

	public static function getAllRoles() {
		return array(self::ROLE_REVIEW_APPROVER);
	}
	
	/**
	 * Get Job title config for given role
	 * @param int $role The Job title config role
	 * @return JobTitleConfig JobTitleConfig object with given role or null if not found
	 */
	public static function getJobTitleConfig($role) {

		if (!JobTitleConfig::_isValidRole($role)) {
			throw new JobTitleConfigException("Invalid parameters to getJobTitleConfig(): role = $role", JobTitleConfigException::INVALID_PARAMETER);
		}

		$jobTitleConfig = new JobTitleConfig($role);
		$jobTitleConfig->setJobTitles(self::_fetchJobTitles($role));
		return $jobTitleConfig;
	}
		
	/**
	 * Get list of job titles assigned to this role
	 */
	private static function _fetchJobTitles($role) {

		$fields[0] = "a. " . self::DB_FIELD_JOB_TITLE_CODE;
		$fields[1] = "b.jobtit_name AS " . self::FIELD_JOB_TITLE_NAME;

		$tables[0] = self::TABLE_NAME . ' a';
		$tables[1] = 'hs_hr_job_title b';

		$joinConditions[1] = 'a.jobtit_code = b.jobtit_code';

		$selectCondition[] = "a." . self::DB_FIELD_ROLE . " = " . $role;
		
		$sqlBuilder = new SQLQBuilder();
		$sql = $sqlBuilder->selectFromMultipleTable($fields, $tables, $joinConditions, $selectCondition);

		$jobTitles = array();

		$conn = new DMLFunctions();
		$result = $conn->executeQuery($sql);

		while ($result && ($row = mysql_fetch_assoc($result))) {
			$jobTitleCode = $row[self::DB_FIELD_JOB_TITLE_CODE];
			$jobTitles[$jobTitleCode] = $row;
		}

		return $jobTitles;	
	}	
}

class JobTitleConfigException extends Exception {
	const INVALID_PARAMETER = 0;
	const DB_ERROR = 1;
}

?>
