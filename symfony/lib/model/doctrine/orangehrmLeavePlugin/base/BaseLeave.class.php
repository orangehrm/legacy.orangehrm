<?php

/**
 * BaseLeave
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property date $date
 * @property decimal $length_hours
 * @property decimal $length_days
 * @property integer $status
 * @property string $comments
 * @property integer $leave_request_id
 * @property integer $leave_type_id
 * @property integer $emp_number
 * @property integer $entitlement_id
 * @property time $start_time
 * @property time $end_time
 * @property LeaveEntitlement $LeaveEntitlement
 * @property LeaveRequest $LeaveRequest
 * @property LeaveType $LeaveType
 * 
 * @method integer          getId()               Returns the current record's "id" value
 * @method date             getDate()             Returns the current record's "date" value
 * @method decimal          getLengthHours()      Returns the current record's "length_hours" value
 * @method decimal          getLengthDays()       Returns the current record's "length_days" value
 * @method integer          getStatus()           Returns the current record's "status" value
 * @method string           getComments()         Returns the current record's "comments" value
 * @method integer          getLeaveRequestId()   Returns the current record's "leave_request_id" value
 * @method integer          getLeaveTypeId()      Returns the current record's "leave_type_id" value
 * @method integer          getEmpNumber()        Returns the current record's "emp_number" value
 * @method integer          getEntitlementId()    Returns the current record's "entitlement_id" value
 * @method time             getStartTime()        Returns the current record's "start_time" value
 * @method time             getEndTime()          Returns the current record's "end_time" value
 * @method LeaveEntitlement getLeaveEntitlement() Returns the current record's "LeaveEntitlement" value
 * @method LeaveRequest     getLeaveRequest()     Returns the current record's "LeaveRequest" value
 * @method LeaveType        getLeaveType()        Returns the current record's "LeaveType" value
 * @method Leave            setId()               Sets the current record's "id" value
 * @method Leave            setDate()             Sets the current record's "date" value
 * @method Leave            setLengthHours()      Sets the current record's "length_hours" value
 * @method Leave            setLengthDays()       Sets the current record's "length_days" value
 * @method Leave            setStatus()           Sets the current record's "status" value
 * @method Leave            setComments()         Sets the current record's "comments" value
 * @method Leave            setLeaveRequestId()   Sets the current record's "leave_request_id" value
 * @method Leave            setLeaveTypeId()      Sets the current record's "leave_type_id" value
 * @method Leave            setEmpNumber()        Sets the current record's "emp_number" value
 * @method Leave            setEntitlementId()    Sets the current record's "entitlement_id" value
 * @method Leave            setStartTime()        Sets the current record's "start_time" value
 * @method Leave            setEndTime()          Sets the current record's "end_time" value
 * @method Leave            setLeaveEntitlement() Sets the current record's "LeaveEntitlement" value
 * @method Leave            setLeaveRequest()     Sets the current record's "LeaveRequest" value
 * @method Leave            setLeaveType()        Sets the current record's "LeaveType" value
 * 
 * @package    orangehrm
 * @subpackage model\leave\base
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseLeave extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_leave');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('date', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('length_hours', 'decimal', 6, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 6,
             'scale' => '2',
             ));
        $this->hasColumn('length_days', 'decimal', 4, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             'scale' => '2',
             ));
        $this->hasColumn('status', 'integer', 2, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2,
             ));
        $this->hasColumn('comments', 'string', 256, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 256,
             ));
        $this->hasColumn('leave_request_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('leave_type_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('emp_number', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('entitlement_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('start_time', 'time', 25, array(
             'type' => 'time',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('end_time', 'time', 25, array(
             'type' => 'time',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('LeaveEntitlement', array(
             'local' => 'entitlement_id',
             'foreign' => 'id'));

        $this->hasOne('LeaveRequest', array(
             'local' => 'leave_request_id',
             'foreign' => 'id'));

        $this->hasOne('LeaveType', array(
             'local' => 'leave_type_id',
             'foreign' => 'id'));
    }
}