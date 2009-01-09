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
require_once ROOT_PATH . '/lib/models/recruitment/FormFieldBase.php';

class JobApplicationField extends FormFieldBase {
	
	private $applicationId;
	
	const TABLE_FIELD = 'hs_hr_job_application_field';
	const id = 'id';
	const lable = 'lable';
	const fieldType = 'field_type';
	const length = 'length';
	const validation = 'validation';
	const errorMessage = 'error_message';
	const fieldValue = 'field_value';
	const filedValueText = 'field_value_text';
	const applicationId = 'application_id';
	const toolTip = "tool_tip";
	const height = "height";
	const required = "required";
	const tabOrder = "tab_order";
	const subFieldLogic = "sub_field_logic";
	
	/* this constant for the field data table many to many table relationship*/
	const TABLE_FIELD_DATA = 'hs_hr_job_application_data';
	const applicationDataFieldValue = 'field_value';
	const applicationDataFieldId = 'application_field_id';
	const applicationDataFieldApplicationId = 'application_Id';
	const applicationDataFieldSubValues = 'field_sub_value';
	
	// @dbva jointable(JobApplication_JobApplicationField) fk(JobApplicationFieldid) inversefk(JobApplicationapplication_Id) 
	private $application;
	
	//@orm has many ApplicationFieldValue inverse(field)
	

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
	
	public function fetchApplicationFields() {
		$sqlBuilder = new SQLQBuilder ( );
		$fields [] = '*';
		$sql = $sqlBuilder->simpleSelect ( self::TABLE_FIELD, $fields, null, JobApplicationField::tabOrder, "ASC" );
		$conn = new DMLFunctions ( );
		$result = $conn->executeQuery ( $sql );
		$objectArray = $this->_buildObjArr ( $result );
		return $objectArray;
	}
	
	public function fetchApplicationField() {
		$sqlBuilder = new SQLQBuilder ( );
		$fields [] = '*';
		$conditions [] = self::id . "='" . $this->getId () . "'";
		$sql = $sqlBuilder->simpleSelect ( self::TABLE_FIELD, $fields, $conditions, JobApplicationField::tabOrder, "ASC" );
		$conn = new DMLFunctions ( );
		$result = $conn->executeQuery ( $sql );
		$objectArray = $this->_buildObjArr ( $result );
		return $objectArray;
	}
	
	public function saveFieldData() {
		$sqlBuilder = new SQLQBuilder ( );
		$insetFields [] = self::applicationDataFieldId;
		$insetFields [] = self::applicationDataFieldApplicationId;
		$insetFields [] = self::applicationDataFieldValue;
		$insetFields [] = self::applicationDataFieldSubValues;
		$values [] = $this->getId ();
		$values [] = $this->getApplicationId ();
		$values [] = $this->getFieldValue ();
		$values [] = $this->getFiledValueText ();
		
		$sql = $sqlBuilder->simpleInsert ( self::TABLE_FIELD_DATA, $values, $insetFields );
		$conn = new DMLFunctions ( );
		$result = $conn->executeQuery ( $sql );
		return $result;
	}
	
	public function filterDynamicFields($requests) {
		$dynamicData = array ();
		/*check wether it is a dynamic field by a postfix defined*/
		foreach ( $requests as $key => $request ) {
			if (! is_array ( $request )) {
				if ((preg_match ( "\"" . $this->getPostFixForFields () . "\"", $key )) > 0) {
					$field = new JobApplicationField ( );
					$id = str_replace ( $this->postFixForFields, '', $key );
					$field->setId ( $id );
					$field = $field->fetchApplicationField ();
					$field = $field [0];
					$field->setFieldValue ( $request );
					if (isset ( $_REQUEST [$id . $this->getPostFixForFields () . "_sub"] )) {
						$field->setFiledValueText ( $_REQUEST [$id . $this->getPostFixForFields () . "_sub"] );
					}
					$dynamicData [$id] = $field;
				}
			}
		}
		return $dynamicData;
	}
	
	protected function _buildObjArr($result) {
		$objectArray = array ();
		while ( $result && ($row = mysql_fetch_assoc ( $result )) ) {
			$obj = new JobApplicationField ( );
			$obj->setId ( $row [JobApplicationField::id] );
			$obj->setLable ( $row [JobApplicationField::lable] );
			$obj->setErrorMessage ( $row [JobApplicationField::errorMessage] );
			$obj->setFieldType ( $row [JobApplicationField::fieldType] );
			$obj->setFieldValue ( $row [JobApplicationField::fieldValue] );
			$obj->setFiledValueText ( $row [JobApplicationField::filedValueText] );
			$obj->setLength ( $row [JobApplicationField::length] );
			$obj->setValidation ( $row [JobApplicationField::validation] );
			$obj->setToolTip ( $row [JobApplicationField::toolTip] );
			$obj->setHeight ( $row [JobApplicationField::height] );
			$obj->setRequired ( $row [JobApplicationField::required] );
			$obj->setTabOrder ( $row [JobApplicationField::tabOrder] );
			$obj->setSubFieldLogic ( $row [JobApplicationField::subFieldLogic] );
			$objectArray [] = $obj;
		}
		return $objectArray;
	}
}

?>
