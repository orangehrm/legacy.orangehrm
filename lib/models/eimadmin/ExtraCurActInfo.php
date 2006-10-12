<?
/*
// OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures 
// all the essential functionalities required for any enterprise. 
// Copyright (C) 2006 hSenid Software International Pvt. Ltd, http://www.hsenid.com

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

class ExtraCurActInfo {

	var $tableName;
	var $extracurId;
	var $extracurDesc;
	var $extracatId;
	
	var $arrayDispList;
	var $singleField;
	
	
	function ExtraCurActInfo() {
		
	}
	
	function setExtraCurActInfoId($extracurId) {
	
		$this->extracurId = $extracurId;
	
	}
	
	function setExtraCurActInfoDesc($extracurDesc) {
	
		$this->extracurDesc = $extracurDesc;

	}
	
	function setExtraCatId($extracatId) {
	
		$this->extracatId = $extracatId;

	}
	
		
	function getExtraCurActInfoId() {
	
		return $this->extracurId;
	
	}
	
	function getExtraCurActInfoDesc() {
	
		return $this->extracurDesc;
		
	}
	
	function getExtraCatId() {
	
		return $this->extracatId;
		//echo $this->extracatId;
		
	}
	
	
	function getListofExtraCurActInfo($pageNO,$schStr,$mode) {
		
		$tableName = 'HS_HR_EXTRA_ACTIVITY_TYPE';			
		$arrFieldList[0] = 'EATYPE_CODE';
		$arrFieldList[1] = 'EATYPE_NAME';
		
		$sql_builder = new SQLQBuilder();
		
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;		
			
		$sqlQString = $sql_builder->passResultSetMessage($pageNO,$schStr,$mode);
		
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
	
	function countExtraCurActInfo($schStr,$mode) {
		
		$tableName = 'HS_HR_EXTRA_ACTIVITY_TYPE';			
		$arrFieldList[0] = 'EATYPE_CODE';
		$arrFieldList[1] = 'EATYPE_NAME';
		
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

	function delExtraCurActInfo($arrList) {

		$tableName = 'HS_HR_EXTRA_ACTIVITY_TYPE';
		$arrFieldList[0] = 'EATYPE_CODE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

	}

	
	function addExtraCurActInfo() {
		
		$this->getExtraCurActInfoId();
		$arrFieldList[0] = "'". $this->getExtraCurActInfoId() . "'";
		$arrFieldList[1] = "'". $this->getExtraCurActInfoDesc() . "'";
		$arrFieldList[2] = "'". $this->getExtraCatId() . "'";
	
		$tableName = 'HS_HR_EXTRA_ACTIVITY_TYPE';			
	
		$sql_builder = new SQLQBuilder();
		
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;		
		
		$sqlQString = $sql_builder->addNewRecordFeature1();
		
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		 return $message2;
					
	}
	
	function updateExtraCurActInfo() {
		
		$this->getExtraCurActInfoId();
		$arrRecordsList[0] = "'". $this->getExtraCurActInfoId() . "'";
		$arrRecordsList[1] = "'". $this->getExtraCurActInfoDesc() . "'";
		$arrRecordsList[2] = "'". $this->getExtraCatId() . "'";
		$arrFieldList[0] = 'EATYPE_CODE';
		$arrFieldList[1] = 'EATYPE_NAME';
		$arrFieldList[2] = 'EACAT_CODE';
		
		$tableName = 'HS_HR_EXTRA_ACTIVITY_TYPE';			
	
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
	
	
	function filterExtraCurActInfo($getID) {
		
		$this->getID = $getID;
		$tableName = 'HS_HR_EXTRA_ACTIVITY_TYPE';			
		$arrFieldList[0] = 'EATYPE_CODE';
		$arrFieldList[1] = 'EATYPE_NAME';
		$arrFieldList[2] = 'EACAT_CODE';
		
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
		 	
	    	$arrayDispList[$i][0] = $line[0]; // Province Code
	    	$arrayDispList[$i][1] = $line[1]; // Provicne Name
	    	$arrayDispList[$i][2] = $line[2]; // Country ID
	    	$i++;
	    	
	     }
	     
	     if (isset($arrayDispList)) {
	     
			return $arrayDispList;
			
		} else {
		
			$arrayDispList = '';
			return $arrayDispList;
			
		}
				
	}
	

	
	
	
	function getLastRecord() {
		
		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_EXTRA_ACTIVITY_TYPE';		
		$arrFieldList[0] = 'EATYPE_CODE';
				
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;		
	
		$sqlQString = $sql_builder->selectOneRecordOnly();
	
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		$common_func = new CommonFunctions();
		
		if (isset($message2)) {
			
			$i=0;
		
		while ($line = mysql_fetch_array($message2, MYSQL_ASSOC)) {		
			foreach ($line as $col_value) {
			$this->singleField = $col_value;
			}		
		}
			
		return $common_func->explodeString($this->singleField,"EXA"); 
				
		}
		
	}

	function getExCurTypeCodes($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_EXTRA_ACTIVITY_TYPE';
		$arrFieldList[0] = 'EACAT_CODE';
		$arrFieldList[1] = 'EATYPE_CODE';
		$arrFieldList[2] = 'EATYPE_NAME';

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
	    		for($c=0; count($arrFieldList) > $c ; $c++)
					$arrayDispList[$i][$c] = $line[$c];

	    		$i++;
	     }

	     if (isset($arrayDispList)) {

			return $arrayDispList;

		} else {

			$arrayDispList = '';
			return $arrayDispList;

		}
	}

	function getUnAssExCurTypeCodes($eno,$typ) {

		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_EXTRA_ACTIVITY_TYPE';			
		$arrFieldList[0] = 'EATYPE_CODE';
		$arrFieldList[1] = 'EATYPE_NAME';
		$arrFieldList[2] = 'EACAT_CODE';

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;
		$sql_builder->field='EATYPE_CODE';
		$sql_builder->table2_name= 'HS_HR_EMP_EXTRA_ACTIVITY';
		$arr1[0][0]='EMP_NUMBER';
		$arr1[0][1]=$eno;
		$arr2[0][0]='EACAT_CODE';
		$arr2[0][1]=$typ;

		$sqlQString = $sql_builder->selectFilter($arr1,$arr2);

		$dbConnection = new DMLFunctions();
       		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

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
	     	//Handle Exceptions
	     	//Create Logs
	     }
	}
	
	function getAllExtraCurActInfo() {
		
		$tableName = 'HS_HR_EXTRA_ACTIVITY_TYPE';			
		$arrFieldList[0] = 'EATYPE_CODE';
		$arrFieldList[1] = 'EATYPE_NAME';
		
		$sql_builder = new SQLQBuilder();
		
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;		
			
		$sqlQString = $sql_builder->passResultSetMessage();
		
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
	
	
}

?>
