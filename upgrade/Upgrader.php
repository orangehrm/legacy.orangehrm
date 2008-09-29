<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

abstract class Upgrader {

	public $errorArray = array();
	protected $dbHost;
	protected $dbPort;
	protected $dbUsername;
	protected $dbPassword;
	protected $oldDbName;
	protected $conn;

	public function __construct(Conf $confObj) {
		$this->dbHost = $confObj->dbhost;
		$this->dbport = $confObj->dbport;
		$this->dbUsername = $confObj->dbuser;
		$this->dbPassword = $confObj->dbpass;
		$this->oldDbName = $confObj->dbname;
		$this->conn = mysql_connect($confObj->dbhost, $confObj->dbuser, $confObj->dbpass);
		mysql_select_db($confObj->dbname);
	}

	/**
	* Check for data incompatibilities.
	* This function checks whether changes in new database schema
	* can affect existing data.
	* @return bool true if no conflicts, false otherwise
	*/
	public abstract function isDataCompatible();

	/**
	 * Check whether the given database is accessible.
	 * This function checks whether the given database
	 * is under current database user and is empty.
	 * @param string $dbName Name of database
	 * @return bool Returns true if accessible, false other wise
	 */
	public function isDatabaseAccessible($dbName) {

		$errorArray = array();

		if(!mysql_select_db($dbName)) {
		    $errorArray[] = "Cannot connect to the database";
		    return false;
		}

		mysql_select_db($dbName);
		$result = mysql_query("SHOW TABLES");

		if (mysql_num_rows($result) > 0) {
		    $errorArray[] = "Given database is not empty";
		    return false;
		}

		$this->errorArray = $errorArray;

		return true;

	}

	/**
	* Executes commands in given SQL file.
	* This function accepts an SQL file and executes commands in it
	* on the given database.
	* @param string $sqlPath Path of SQL file
	* @param string $dbName Name of database
	* @return bool Returns true on success and false on failure
	*/
	public function executeSql($sqlPath, $dbName) {

		$flag = true;
		mysql_select_db($dbName);

		if (!is_readable($sqlPath)) {
		    throw new Exception('Given SQL path is not readable');
		}

		$fp = fopen($sqlPath, 'r');
		$query = fread($fp, filesize($sqlPath));
		fclose($fp);
		$sqlStatements = explode(";", $query);

		$count = count($sqlStatements)-1;
		for($i=0;$i<$count;$i++)
			if(!@mysql_query($sqlStatements[$i])) {
				$flag = false;
			}

		return $flag;

	}

	/**
	* Imports data from a table in another database.
	* This function imports data from a table in another databse.
	* Table schema should be same in both databases.
	* @param string $tableName Name of the table
	* @param string $fromDb Name of the source database
	* @param string $toDb Name of the importing database
	* @return bool Returns true on success and false on failure
	*/
	public function importDataFromTable($tableName, $fromDb, $toDb) {

		mysql_select_db($toDb);
		$query = "INSERT $tableName SELECT * FROM $fromDb.$tableName";
		if (mysql_query($query)) {
		    return true;
		} else {
		    return false;
		}

	}

	/**
	 * Returns all the tables in Given database as an array.
	 * @param $dbName Name of the database
	 * @return array Tables in given database
	 */
	public function getAllTables($dbName) {

	    $tablesArray = array();

	    mysql_select_db($dbName);
	    $query = "SHOW TABLES";
	    $result = mysql_query($query);

	    while ($row = mysql_fetch_array($result)) {
	        $tablesArray[] = $row[0];
	    }

		return $tablesArray;

	}

	/**
	 * Applies constraints in current database.
	 * @param string $sqlFile Path of SQL file that contains constraints
	 * @param string $dbName Name of database where constraints are applied
	 * @return bool Returns true on success and false on failure
	 */
	public abstract function applyConstraints($sqlPath, $dbName);

	/**
	 * Create new tables that were introduced in new version.
	 * @param string $sqlPath Path of SQL file that contains SQL for new tables
	 * @param string $dbName Name of the database of new installation
	 * @return bool Returns true on success and false on failure
	 */
	public abstract function createNewTables($sqlPath, $dbName);

	/**
	 * Applies database alterations to comply with new version.
	 * This can include new constraints, adding new data columns etc.
	 * @param string $sqlPath Path of SQL file that contains alterations
	 * @param string $dbName Name of the database of new installation
	 * @return bool Returns true on success and false on failure
	 */
	public abstract function applyDbAlterations($sqlPath, $dbName);

	/**
	 * Stores default data specific to new version.
	 * @param string $sqlPath Path of SQL file that contains SQL for default data
	 * @param string $dbName Name of the database of new installation
	 * @return bool Returns true on success and false on failure
	 */
	public abstract function storeDefaultData($sqlPath, $dbName);

	/**
	 * Applies database value changes like encryption
	 * @param string $dbName Name of the database to apply value changes
	 * @param array $choiceArr Array containing user choices
	 * @return bool Returns true on success and false on failure
	 */
	public abstract function changeExistingData($dbName, $choiceArr);

	/**
	 * Creates /lib/confs/Conf.php file of new installation
	 * @param string $newDbName Name of database of new installation
	 * @return bool Returns true on success and false on failure
	 */
	public abstract function createConfFile($newDbName);

	/**
	 * Creates /lib/confs/upgradeConf.php file of new installation
	 * @return bool Returns true on success and false on failure
	 */
	public abstract function createUpgradeConfFile();

	/**
	 * Copy given file to given location
	 * @param string $filePath File path of file to be copied
	 * @param string $newFilePath File path of copied file
	 */
	public function copyFile($filePath, $newFilePath) {

		$result = @copy($filePath, $newFilePath);

		if (!$result) {
		    return false;
		}

		return true;

	}

}

?>
