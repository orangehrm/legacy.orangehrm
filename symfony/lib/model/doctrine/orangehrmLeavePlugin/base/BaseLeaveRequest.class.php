<?php

/**
 * BaseLeaveRequest
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $leave_type_id
 * @property date $date_applied
 * @property integer $emp_number
 * @property string $comments
 * @property LeaveType $LeaveType
 * @property Employee $Employee
 * @property Doctrine_Collection $Leave
 * 
 * @method integer             getId()            Returns the current record's "id" value
 * @method integer             getLeaveTypeId()   Returns the current record's "leave_type_id" value
 * @method date                getDateApplied()   Returns the current record's "date_applied" value
 * @method integer             getEmpNumber()     Returns the current record's "emp_number" value
 * @method string              getComments()      Returns the current record's "comments" value
 * @method LeaveType           getLeaveType()     Returns the current record's "LeaveType" value
 * @method Employee            getEmployee()      Returns the current record's "Employee" value
 * @method Doctrine_Collection getLeave()         Returns the current record's "Leave" collection
 * @method LeaveRequest        setId()            Sets the current record's "id" value
 * @method LeaveRequest        setLeaveTypeId()   Sets the current record's "leave_type_id" value
 * @method LeaveRequest        setDateApplied()   Sets the current record's "date_applied" value
 * @method LeaveRequest        setEmpNumber()     Sets the current record's "emp_number" value
 * @method LeaveRequest        setComments()      Sets the current record's "comments" value
 * @method LeaveRequest        setLeaveType()     Sets the current record's "LeaveType" value
 * @method LeaveRequest        setEmployee()      Sets the current record's "Employee" value
 * @method LeaveRequest        setLeave()         Sets the current record's "Leave" collection
 * 
 * @package    orangehrm
 * @subpackage model\leave\base
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseLeaveRequest extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_leave_request');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
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
        $this->hasColumn('date_applied', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 25,
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
        $this->hasColumn('comments', 'string', 256, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 256,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('LeaveType', array(
             'local' => 'leave_type_id',
             'foreign' => 'id'));

        $this->hasOne('Employee', array(
             'local' => 'emp_number',
             'foreign' => 'emp_number'));

        $this->hasMany('Leave', array(
             'local' => 'id',
             'foreign' => 'leave_request_id'));
    }
}