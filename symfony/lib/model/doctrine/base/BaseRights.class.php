<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseRights extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('hs_hr_rights');
        $this->hasColumn('userg_id', 'string', 36, array(
             'type' => 'string',
             'primary' => true,
             'length' => '36',
             ));
        $this->hasColumn('mod_id', 'string', 36, array(
             'type' => 'string',
             'primary' => true,
             'length' => '36',
             ));
        $this->hasColumn('addition', 'integer', 2, array(
             'type' => 'integer',
             'unsigned' => '1',
             'default' => '0',
             'length' => '2',
             ));
        $this->hasColumn('editing', 'integer', 2, array(
             'type' => 'integer',
             'unsigned' => '1',
             'default' => '0',
             'length' => '2',
             ));
        $this->hasColumn('deletion', 'integer', 2, array(
             'type' => 'integer',
             'unsigned' => '1',
             'default' => '0',
             'length' => '2',
             ));
        $this->hasColumn('viewing', 'integer', 2, array(
             'type' => 'integer',
             'unsigned' => '1',
             'default' => '0',
             'length' => '2',
             ));
    }

    public function setUp()
    {
        $this->hasOne('UserGroup', array(
             'local' => 'userg_id',
             'foreign' => 'userg_id'));

        $this->hasOne('Module', array(
             'local' => 'mod_id',
             'foreign' => 'mod_id'));
    }
}