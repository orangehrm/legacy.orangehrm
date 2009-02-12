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

class Fluency {

	const TABLE='hs_hr_fluency';
	/**
	 * @orm fluency_code int
	 * @dbva id(autogenerate)
	 */
	const FLUENCY_CODE='fluency_code';
	private $fluencyCode;

	/**
	 * @orm descripton varchar
	 */
	const DESCRIPTION='description';
	private $descripton;

	/**
	 * @orm has many AppicantLanguageInformation inverse(fluency)
	 * @dbva inverse(fluency_code)
	 */
	private $applicantLangInfo;

	public function  getFluencyCode() {
		return $this->fluencyCode;
	}

	public function setFluencyCode( $fluencyCode) {
		$this->fluencyCode = $fluencyCode;
	}

	public function getApplicantLangInfo() {
		return $this->applicantLangInfo;
	}

	public function setApplicantLangInfo($applicantLangInfo) {
		$this->applicantLangInfo = $applicantLangInfo;
	}

	public function getDescripton() {
		return $this->descripton;
	}

	public function setDescripton($descripton) {
		$this->descripton = $descripton;
	}

	public function save(){

		$this->setFluencyCode(UniqueIDGenerator::getInstance()->getNextID(self::TABLE, 'fluency_code', 'FLU'));
		$sql_builder = new SQLQBuilder();
		$fields[0]=self::FLUENCY_CODE;
		$fields[1]=self::DESCRIPTION;
		$values[0]=$this->getFluencyCode();
		$values[1]=$this->getDescripton();
		$sqlQString=$sql_builder->simpleInsert(self::TABLE,$values,$fields);
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString);
		return $message2;

	}

	public function getFluencyCodes ($pageNO,$schStr,$mode, $sortField = 0, $sortOrder = 'ASC') {
		$sql_builder = new SQLQBuilder();
		$arrFieldList[0] = self::FLUENCY_CODE;
		$arrFieldList[1] = self::DESCRIPTION;

		$sql_builder->table_name = self::TABLE;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;
		$sqlQString = $sql_builder->passResultSetMessage($pageNO,$schStr,$mode, $sortField, $sortOrder);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString);
		$common_func = new CommonFunctions();

		$i=0;
		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {
	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$i++;
	     }

	     if (isset($arrayDispList)) {
	       	return $arrayDispList;
	     } else {

	     }

	}

	public function filterFluencyCodes () {
		$sql_builder = new SQLQBuilder();
		$arrFieldList[0] = self::FLUENCY_CODE;
		$arrFieldList[1] = self::DESCRIPTION;

		$sqlQString = $sql_builder->simpleSelect(self::TABLE,$arrFieldList);
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString);
		$common_func = new CommonFunctions();

		$i=0;
		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {
	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$i++;
	     }

	     if (isset($arrayDispList)) {
	       	return $arrayDispList;
	     } else {

	     }

	}

	function filterFluency($getID) {

		$this->getID = $getID;
		$tableName = self::TABLE;
		$arrFieldList[0] = self::FLUENCY_CODE;
		$arrFieldList[1] = self::DESCRIPTION;

		$sql_builder = new SQLQBuilder();
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered($this->getID);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$i++;

	     }
	    if (isset($arrayDispList)) {
			return $arrayDispList;
		} else {
			$arrayDispList = '';
			return $arrayDispList;
		}
	}

	function updateFluency() {
		$this->getFluencyCode();
		$arrRecordsList[0] = "'". $this->getFluencyCode(). "'";
		$arrRecordsList[1] = "'". $this->getDescripton(). "'";

		$arrFieldList[0] = self::FLUENCY_CODE;
		$arrFieldList[1] = self::DESCRIPTION;

		$tableName = self::TABLE;

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_update = 'true';
		$sql_builder->arr_update = $arrFieldList;
		$sql_builder->arr_updateRecList = $arrRecordsList;

		$sqlQString = $sql_builder->addUpdateRecord1();

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}

	function delFluency($arrList) {

		$tableName = self::TABLE;
		$arrFieldList[0] = self::FLUENCY_CODE;

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}
	
	function countFluency($schStr,$mode) {

		$tableName = self::TABLE;
		$arrFieldList[0] = self::FLUENCY_CODE;
		$arrFieldList[1] = self::DESCRIPTION;		

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->countResultset($schStr,$mode);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$line = mysql_fetch_array($message2, MYSQL_NUM);

	    return $line[0];
	}

}

?>
