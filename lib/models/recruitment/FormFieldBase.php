<?php

/**
 * Licensee: Anonymous
 * License Type: Purchased
 */

/**
 * @orm JobApplicationField
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

class FormFieldBase {

  protected $id;
  protected $lable;
  protected $fieldType;
  protected $length;
  protected $validation;
  protected $errorMessage;
  protected $fieldValue;
  protected $filedValueText; 
  protected $toolTip;
  protected $height;
  protected $required;
  protected $tabOrder;
  protected $enabled=true;
  protected $postFixForFields="_dynamic";
  protected $subFieldLogic;
  
 
  //@orm has many ApplicationFieldValue inverse(field)
  protected $fieldValues;
	
		
	public function setFieldValue($fieldValue) {
		$this->fieldValue = $fieldValue;
	}
	
	public function getFieldValues() {
		return $this->fieldValues;
	}
	
	public function getErrorMessage() {
		return $this->errorMessage;
	}
	
	public function setErrorMessage($errorMessage) {
		$this->errorMessage = $errorMessage;
	}
	
	public function getFieldValue() {
		return $this->fieldValue;
	}
	
	public function setFieldValues($fieldValues) {
		$this->fieldValues = $fieldValues;
	}
	
	public function getFiledValueText() {
		return $this->filedValueText;
	}
	
	public function setFiledValueText($filedValueText) {
		$this->filedValueText = $filedValueText;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getLable() {
		return $this->lable;
	}
	
	public function setLable($lable) {
		$this->lable = $lable;
	}
	
	public function getLength() {
		return $this->length;
	}
	
	public function setLength($length) {
		$this->length = $length;
	}
	
	public function getFieldType() {
		return $this->fieldType;
	}
	
	public function setFieldType($filedType) {
		$this->fieldType = $filedType;
	}
	
	public function getValidation() {
		return $this->validation;
	}
	
	public function setValidation($validation) {
		$this->validation = $validation;
	}
		
	public function getHeight() {
		return $this->height;
	}
	
	public function setHeight($height) {
		$this->height = $height;
	}
	
	public function getToolTip() {
		return $this->toolTip;
	}
	
	public function setToolTip($toolTip) {
		$this->toolTip = $toolTip;
	}
	
	public function getRequired() {
		return $this->required;
	}
	
	public function setRequired($required) {
		$this->required = $required;
	}
	
	public function getTabOrder() {
		return $this->tabOrder;
	}
	
	public function setTabOrder($tabOrder) {
		$this->tabOrder = $tabOrder;
	}	
	public function getEnabled() {
		return $this->enabled;
	}	
	public function setEnabled($enabled) {
		$this->enabled = $enabled;
	}
	
	public function getPostFixForFields() {
		return $this->postFixForFields;
	}
	
	public function setPostFixForFields($postFixForFields) {
		$this->postFixForFields = $postFixForFields;
	}

	public function fetchApplicationFields($table=null){		
    	
    }
	
	public function getSubFieldLogic() {
		return $this->subFieldLogic;
	}
	
	public function setSubFieldLogic($subFieldLogic) {
		$this->subFieldLogic = $subFieldLogic;
	}

    
    public function drawElement(){    	
    	$str="";
    	$enable="";
		if(!$this->getEnabled()) $enable="disabled=\"disabled\"";
    	switch ($this->getFieldType()) {
			case 'text':				
				$str="<input type=\"text\" width=\"".$this->getLength()."\" value=\"".$this->getFieldValue()."\" id=\"".$this->getId()."_dynamic\" name=\"".$this->getId()."_dynamic\" title=\"".$this->getToolTip()."\" tabindex=\"".$this->getTabOrder()."\" ".$enable."/>";
			break;
			case 'datefield':				
				$str="<input type=\"text\" readonly width=\"".$this->getLength()."\" value=\"".$this->getFieldValue()."\" id=\"".$this->getId()."_dynamic\" name=\"".$this->getId()."_dynamic\" title=\"".$this->getToolTip()."\" tabindex=\"".$this->getTabOrder()."\" ".$enable."  onchange=\"fillToDate();\" onfocus=\"fillToDate();\"/>";
				$str.="<input type=\"button\" name=\"Submit\" value=\"  \" class=\"calendarBtn\" style=\"width:20px;\" />";
			break;
			case 'checkbox':
				$selected=''; 
				if($this->getFieldValue()==1) $selected="selected='selected";					
				$str="<input type=\"checkbox\" value=\"\" id=\"".$this->getId()."_dynamic\" name=\"".$this->getId()."_dynamic\"  ".$selected." title=\"".$this->getToolTip()."\" tabindex=\"".$this->getTabOrder()."\" ".$enable." />";
				if($this->getSubFieldLogic()!=null){
					$str.="<input style=\"margin-left: 8px;\" type=\"text\"  value=\"".$this->getFiledValueText()."\" id=\"".$this->getId()."_dynamic_sub\" name=\"".$this->getId()."_dynamic_sub\" title=\"".$this->getToolTip()."\"  ".$enable."/>";
				}
			break;
			case 'radio':
				$selected=''; 
				$selected="checked";					
				$str="Yes&nbsp;&nbsp; <input type=\"radio\" value=\"1\" id=\"".$this->getId()."_dynamic\" name=\"".$this->getId()."_dynamic\"  ".$selected." title=\"".$this->getToolTip()."\" tabindex=\"".$this->getTabOrder()."\" ".$enable." />";
				$str.="No <input style=\"margin-left: 8px;\" type=\"radio\" value=\"0\" id=\"".$this->getId()."_dynamic\" name=\"".$this->getId()."_dynamic\"  title=\"".$this->getToolTip()."\" tabindex=\"".$this->getTabOrder()."\" ".$enable." />";
				if($this->getSubFieldLogic()!=null){
					$str.="<input style=\"margin-left: 8px;\" type=\"text\"  value=\"".$this->getFiledValueText()."\" id=\"".$this->getId()."_dynamic_sub\" name=\"".$this->getId()."_dynamic_sub\" title=\"".$this->getToolTip()."\"  ".$enable."/>";
				}
			break;
			case 'select':
				$str="<select name=\"".$this->getId()."_dynamic\" id=\"".$this->getId()."_dynamic\" title=\"".$this->getToolTip()."\" tabindex=\"".$this->getTabOrder()."\" />".
						$this->getFieldValue().
					"</select>";				
			case 'textarea': 
				$str="<textarea name=\"".$this->getId()."_dynamic\" id=\"".$this->getId()."_dynamic\" row=\"".$this->getHeight()."\" col=\"".$this->getLength()."\"   title=\"".$this->getToolTip()."\" tabindex=\"".$this->getTabOrder()."\" ".$enable." >".$this->getFieldValue()."</textarea>";
			break;
				default:						
				break;
			}
		return $str;
    }
   

}

?>
