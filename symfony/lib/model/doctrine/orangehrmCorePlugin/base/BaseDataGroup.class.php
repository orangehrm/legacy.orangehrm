<?php

/**
 * BaseDataGroup
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $canRead
 * @property integer $canCreate
 * @property integer $canUpdate
 * @property integer $canDelete
 * @property Doctrine_Collection $DataGroupPermission
 * 
 * @method integer             getId()                  Returns the current record's "id" value
 * @method string              getName()                Returns the current record's "name" value
 * @method string              getDescription()         Returns the current record's "description" value
 * @method integer             getCanRead()             Returns the current record's "canRead" value
 * @method integer             getCanCreate()           Returns the current record's "canCreate" value
 * @method integer             getCanUpdate()           Returns the current record's "canUpdate" value
 * @method integer             getCanDelete()           Returns the current record's "canDelete" value
 * @method Doctrine_Collection getDataGroupPermission() Returns the current record's "DataGroupPermission" collection
 * @method DataGroup           setId()                  Sets the current record's "id" value
 * @method DataGroup           setName()                Sets the current record's "name" value
 * @method DataGroup           setDescription()         Sets the current record's "description" value
 * @method DataGroup           setCanRead()             Sets the current record's "canRead" value
 * @method DataGroup           setCanCreate()           Sets the current record's "canCreate" value
 * @method DataGroup           setCanUpdate()           Sets the current record's "canUpdate" value
 * @method DataGroup           setCanDelete()           Sets the current record's "canDelete" value
 * @method DataGroup           setDataGroupPermission() Sets the current record's "DataGroupPermission" collection
 * 
 * @package    orangehrm
 * @subpackage model\core\base
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDataGroup extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_data_group');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name as name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('description as description', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('can_read as canRead', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             ));
        $this->hasColumn('can_create as canCreate', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             ));
        $this->hasColumn('can_update as canUpdate', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             ));
        $this->hasColumn('can_delete as canDelete', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('DataGroupPermission', array(
             'local' => 'id',
             'foreign' => 'data_group_id'));
    }
}