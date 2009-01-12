<?php
/*
 * Licensee: Anonymous
 * License Type: Purchased
 */

/**
 * @orm Fluency
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
		
		$sql_builder = new SQLQBuilder();
		$fields[]=self::DESCRIPTION;
		$values[]=$this->getDescripton();
		$sqlQString=$sql_builder->simpleInsert(self::TABLE,$values,$fields);
		$dbConnection = new DMLFunctions();
		return $dbConnection -> executeQuery($sqlQString); 
		
	}
	
	public function getFluencyCodes () {
		$sql_builder = new SQLQBuilder();		
		$arrFieldList[0] = self::FLUENCY_CODE;
		$arrFieldList[1] = self::DESCRIPTION;

		$sql_builder->table_name = self::TABLE;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;
		$sqlQString = $sql_builder->passResultSetMessage();

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



}

?>
