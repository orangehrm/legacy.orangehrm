<?php
/**
 * "Visual Paradigm: DO NOT MODIFY THIS FILE!"
 * 
 * This is an automatic generated file. It will be regenerated every time 
 * you generate persistence class.
 * 
 * Modifying its content may cause the program not work, or your work may lost.
 */

/**
 * Licensee: Anonymous
 * License Type: Purchased
 */

/**
 * @orm ApplicationFieldValue
 */

require_once ROOT_PATH . '/lib/confs/Conf.php';
require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';
require_once ROOT_PATH . '/lib/common/LocaleUtil.php';
require_once ROOT_PATH . '/lib/common/UniqueIDGenerator.php';
require_once ROOT_PATH . '/lib/common/SearchObject.php';
require_once ROOT_PATH . '/lib/models/recruitment/JobVacancy.php';
require_once ROOT_PATH . '/lib/models/recruitment/JobApplicationEvent.php';

class ApplicationFieldValue{
	
	  
  /**
   * @orm id int
   * @dbva id(autogenerate) 
   */
  private $id;
  private $value;
  private $field;
  private $fieldId;
	
  const TABLE_FIELD_VALUE='hs_hr_job_application_field_value';  
  const id='id';
  const value='value';
  const fieldId='fieldId'; 
	public function getField() {
		return $this->field;
	}
	
	public function setField($field) {
		$this->field = $field;
	}
	
	public function getFieldId() {
		return $this->fieldId;
	}
	
	public function setFieldId($fieldId) {
		$this->fieldId = $fieldId;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = $value;
	}  
}

?>
