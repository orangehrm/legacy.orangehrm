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

class fileVersions {
	var $tableName = 'hs_hr_file_version';
	
	var $id;
	var $fileVersionName;
	var $dateEntered;
	var $dateModified;
	var $modifiedUser;
	var $createdUser;
	var $description;
	var $module;
	
	var $arrayDispList;
		
	function fileVersions(){ 
		$this->sql_builder = new SQLQBuilder();
		$this->dbConnection = new DMLFunctions();		
	}
	
	function setFileVersionId($id){
		$this->id = $id;
	}
	
	function setFileVersionName($fileVersionName){
		$this->fileVersionName = $fileVersionName;
	}
	
	function setDateEntered($dateEntered){
		$this->dateEntered = $dateEntered;
	}
	
	function setDateModified($dateModified){
		$this->dateModified = $dateModified;
	}
	
	function setModifiedUser($modifiedUser){
		$this->modifiedUser = $modifiedUser;
	}
	
	function setCreatedUser($createdUser){
		$this->createdUser = $createdUser;
	}
	
	function setDescription($description){
		$this->description = $description;
	}
	
	function setModule($module){
		$this->module = $module;
	}
	
	
		
	//////
	function getFileVersionId(){
		return $this->id;
	}
	
	function getFileVersionName(){
		return $this->fileVersionName;
	}
	
	function getDateEntered(){
		return $this->dateEntered;
	}
	
	function getDateModified(){
		return $this->dateModified;
	}
	
	function getCreatedUser(){
		return $this->createdUser;
	}
	
	function getModifiedUser(){
		return $this->modifiedUser;
	}
	
	function getDescription(){
		return $this->description;
	}
	
	function getModule(){
		return $this->module;
	}
	///
		
	function getListOfFileVersions($pageNO,$schStr,$mode){
		
		$arrFieldList[0] = 'ID';
		$arrFieldList[1] = 'name';
	
		
		$this->sql_builder->table_name = $this->tableName;
		$this->sql_builder->flg_select = 'true';
		$this->sql_builder->arr_select = $arrFieldList;
		
//		$sqlQString =$this->sql_builder->passResultSetMessage($pageNO,$schStr,$mode);
		$sqlQString =$this->sql_builder->passResultSetMessage($pageNO,$schStr,$mode);
		
		$message2 = $this->dbConnection -> executeQuery($sqlQString);
		
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
	
	function countFileVersions($schStr,$mode) {
		
		$arrFieldList[0] = 'ID';
		$arrFieldList[1] = 'name';
	
		
		$sql_builder = new SQLQBuilder();
		$sql_builder->table_name = $this->tableName;
		
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;		
			
		$sqlQString = $sql_builder->countResultset($schStr,$mode);
		
		//echo $sqlQString;		
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		$line = mysql_fetch_array($message2, MYSQL_NUM);
		 	
	    	return $line[0];
	}

	function addFileVersions(){
		$arrFieldList[0] = "'". $this->getFileVersionId() . "'";
		$arrFieldList[1] = "'". $this->getFileVersionName() . "'";
		$arrFieldList[2] = "'". $this->getDateEntered() . "'";
		$arrFieldList[3] = "'". $this->getCreatedUser() . "'";
		$arrFieldList[4] = "'". $this->getModifiedUser() . "'";
		$arrFieldList[5] = "'". $this->getDescription() . "'";
		$arrFieldList[6] = "'". $this->getModule() . "'";
				
		$arrRecordsList[0] = 'id';
		$arrRecordsList[1] = 'name';
		$arrRecordsList[2] = 'entered_date';
		$arrRecordsList[3] = 'entered_by';
		$arrRecordsList[4] = 'modified_by';
		$arrRecordsList[5] = 'description';
		$arrRecordsList[6] = 'altered_module';
								
		$this->sql_builder->table_name = $this->tableName;
		$this->sql_builder->flg_insert = 'true';
		$this->sql_builder->arr_insertfield = $arrRecordsList;
		$this->sql_builder->arr_insert = $arrFieldList;		
	
		$sqlQString = $this->sql_builder->addNewRecordFeature2();
		
		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		 return $message2;
		 echo $message2;
	}
	
	function updateFileVersions(){
		$arrFieldList[0] = "'". $this->getFileVersionId() . "'";
		$arrFieldList[1] = "'". $this->getFileVersionName() . "'";
		$arrFieldList[2] = "'". $this->getDateModified() ."'";
		$arrFieldList[3] = "'". $this->getModifiedUser() . "'";
		$arrFieldList[4] = "'". $this->getDescription() ."'";
		$arrFieldList[5] = "'". $this->getModule() ."'";
						
	    $arrRecordsList[0] = 'id';
		$arrRecordsList[1] = 'name';
		$arrRecordsList[2] = 'modified_date';
		$arrRecordsList[3] = 'modified_by';
		$arrRecordsList[4] = 'description';		
		$arrRecordsList[5] = 'altered_module';				
		
		$this->sql_builder->table_name = $this->tableName;
		$this->sql_builder->flg_update = 'true';
		$this->sql_builder->arr_update = $arrRecordsList;	
		$this->sql_builder->arr_updateRecList = $arrFieldList;	
	
		$sqlQString = $this->sql_builder->addUpdateRecord1();
		
		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		return $message2;
	}
	
	function getLastRecord(){
		$arrFieldList[0] = 'id';
		
		$this->sql_builder->table_name = $this->tableName;
		$this->sql_builder->flg_select = 'true';
		$this->sql_builder->arr_select = $arrFieldList;		
	
		$sqlQString = $this->sql_builder->selectOneRecordOnly();
		
		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		$common_func = new CommonFunctions();
		
		if (isset($message2)) {
			
			$i=0;
		
		while ($line = mysql_fetch_array($message2, MYSQL_ASSOC)) {		
			foreach ($line as $col_value) {
			$this->singleField = $col_value;
			}		
		}
			
		return $common_func->explodeString($this->singleField,"FVR");
				
		}		
	}
	
	function filterFileVersions($getID) {
		
		$this->ID = $getID;
		$arrFieldList[0] = 'id';
		$arrFieldList[1] = 'name';
		$arrFieldList[2] = 'entered_date';
		$arrFieldList[3] = 'modified_date';
		$arrFieldList[4] = 'modified_by';
		$arrFieldList[5] = 'entered_by';
		$arrFieldList[6] = 'description';
		$arrFieldList[7] = 'altered_module';
						
		$this->sql_builder->table_name = $this->tableName;
		$this->sql_builder->flg_select = 'true';
		$this->sql_builder->arr_select = $arrFieldList;		
			
		$sqlQString = $this->sql_builder->selectOneRecordFiltered($this->ID);
		
		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		$i=0;
		
		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {
		 	
	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$arrayDispList[$i][2] = $line[2];
	    	$arrayDispList[$i][3] = $line[3];
	    	$arrayDispList[$i][4] = $line[4];
	    	$arrayDispList[$i][5] = $line[5];
	    	$arrayDispList[$i][6] = $line[6];
	    	$arrayDispList[$i][7] = $line[7];
	    		    	
	    	$i++;
	    	
	     }
	     
	     if (isset($arrayDispList)) {
	     
			return $arrayDispList;
			
		} else {
		
			$arrayDispList = '';
			return $arrayDispList;
			
		}
				
	}
	
		
	function getUsersList(){
		$tabName='hs_hr_users';
		$arrFieldList[0] = 'id';
		$arrFieldList[1] = 'user_name';

		$this->sql_builder->table_name = $tabName;
		$this->sql_builder->flg_select = 'true';
		$this->sql_builder->arr_select = $arrFieldList;

		$sqlQString = $this->sql_builder->passResultSetMessage();

		$message2 = $this->dbConnection-> executeQuery($sqlQString); //Calling the addData() function

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
	
	function getModuleList(){
		$tabName='hs_hr_module';
		$arrFieldList[0] = 'MOD_ID';
		$arrFieldList[1] = 'name';

		$this->sql_builder->table_name = $tabName;
		$this->sql_builder->flg_select = 'true';
		$this->sql_builder->arr_select = $arrFieldList;

		$sqlQString = $this->sql_builder->passResultSetMessage();

		$message2 = $this->dbConnection-> executeQuery($sqlQString); //Calling the addData() function

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

	function delFileVersions($arrList) {

		$arrFieldList[0] = 'id';

		$this->sql_builder->table_name = $this->tableName;
		$this->sql_builder->flg_delete = 'true';
		$this->sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $this->sql_builder->deleteRecord($arrList);

		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function

	}

	function getDate(){
		$date = getdate();
		$textDate = $date['year']. "-".$date['mon']."-".$date['mday'] ;
		
		return $textDate;
	}
	
		
}
?>