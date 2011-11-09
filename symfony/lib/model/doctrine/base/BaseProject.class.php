<?php

/**
 * BaseProject
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $project_id
 * @property integer $customer_id
 * @property integer $deleted
 * @property string $name
 * @property string $description
 * @property Customer $Customer
 * @property Doctrine_Collection $ProjectActivity
 * @property Doctrine_Collection $ProjectAdmin
 * @property Doctrine_Collection $TimesheetItem
 * 
 * @method integer             getProjectId()       Returns the current record's "project_id" value
 * @method integer             getCustomerId()      Returns the current record's "customer_id" value
 * @method integer             getDeleted()         Returns the current record's "deleted" value
 * @method string              getName()            Returns the current record's "name" value
 * @method string              getDescription()     Returns the current record's "description" value
 * @method Customer            getCustomer()        Returns the current record's "Customer" value
 * @method Doctrine_Collection getProjectActivity() Returns the current record's "ProjectActivity" collection
 * @method Doctrine_Collection getProjectAdmin()    Returns the current record's "ProjectAdmin" collection
 * @method Doctrine_Collection getTimesheetItem()   Returns the current record's "TimesheetItem" collection
 * @method Project             setProjectId()       Sets the current record's "project_id" value
 * @method Project             setCustomerId()      Sets the current record's "customer_id" value
 * @method Project             setDeleted()         Sets the current record's "deleted" value
 * @method Project             setName()            Sets the current record's "name" value
 * @method Project             setDescription()     Sets the current record's "description" value
 * @method Project             setCustomer()        Sets the current record's "Customer" value
 * @method Project             setProjectActivity() Sets the current record's "ProjectActivity" collection
 * @method Project             setProjectAdmin()    Sets the current record's "ProjectAdmin" collection
 * @method Project             setTimesheetItem()   Sets the current record's "TimesheetItem" collection
 * 
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProject extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('hs_hr_project');
        $this->hasColumn('project_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('customer_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('deleted', 'integer', 1, array(
             'type' => 'integer',
             'default' => '0',
             'length' => 1,
             ));
        $this->hasColumn('name', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('description', 'string', 250, array(
             'type' => 'string',
             'length' => 250,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Customer', array(
             'local' => 'customer_id',
             'foreign' => 'customer_id'));

        $this->hasMany('ProjectActivity', array(
             'local' => 'project_id',
             'foreign' => 'project_id'));

        $this->hasMany('ProjectAdmin', array(
             'local' => 'project_id',
             'foreign' => 'project_id'));

        $this->hasMany('TimesheetItem', array(
             'local' => 'project_id',
             'foreign' => 'projectId'));
    }
}