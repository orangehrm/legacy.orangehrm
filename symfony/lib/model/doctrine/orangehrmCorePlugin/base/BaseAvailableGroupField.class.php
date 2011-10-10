<?php

/**
 * BaseAvailableGroupField
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $reportGroupId
 * @property integer $groupFieldId
 * @property ReportGroup $ReportGroup
 * @property GroupField $GroupField
 * 
 * @method integer             getReportGroupId() Returns the current record's "reportGroupId" value
 * @method integer             getGroupFieldId()  Returns the current record's "groupFieldId" value
 * @method ReportGroup         getReportGroup()   Returns the current record's "ReportGroup" value
 * @method GroupField          getGroupField()    Returns the current record's "GroupField" value
 * @method AvailableGroupField setReportGroupId() Sets the current record's "reportGroupId" value
 * @method AvailableGroupField setGroupFieldId()  Sets the current record's "groupFieldId" value
 * @method AvailableGroupField setReportGroup()   Sets the current record's "ReportGroup" value
 * @method AvailableGroupField setGroupField()    Sets the current record's "GroupField" value
 * 
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAvailableGroupField extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_available_group_field');
        $this->hasColumn('report_group_id as reportGroupId', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('group_field_id as groupFieldId', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('ReportGroup', array(
             'local' => 'report_group_id',
             'foreign' => 'reportGroupId',
             'onDelete' => 'cascade'));

        $this->hasOne('GroupField', array(
             'local' => 'group_field_id',
             'foreign' => 'groupFieldId'));
    }
}