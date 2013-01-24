<?php

/**
 * BaseWorkWeek
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $operational_country_id
 * @property integer $mon
 * @property integer $tue
 * @property integer $wed
 * @property integer $thu
 * @property integer $fri
 * @property integer $sat
 * @property integer $sun
 * @property OperationalCountry $OperationalCountry
 * 
 * @method integer            getId()                     Returns the current record's "id" value
 * @method integer            getOperationalCountryId()   Returns the current record's "operational_country_id" value
 * @method integer            getMon()                    Returns the current record's "mon" value
 * @method integer            getTue()                    Returns the current record's "tue" value
 * @method integer            getWed()                    Returns the current record's "wed" value
 * @method integer            getThu()                    Returns the current record's "thu" value
 * @method integer            getFri()                    Returns the current record's "fri" value
 * @method integer            getSat()                    Returns the current record's "sat" value
 * @method integer            getSun()                    Returns the current record's "sun" value
 * @method OperationalCountry getOperationalCountry()     Returns the current record's "OperationalCountry" value
 * @method WorkWeek           setId()                     Sets the current record's "id" value
 * @method WorkWeek           setOperationalCountryId()   Sets the current record's "operational_country_id" value
 * @method WorkWeek           setMon()                    Sets the current record's "mon" value
 * @method WorkWeek           setTue()                    Sets the current record's "tue" value
 * @method WorkWeek           setWed()                    Sets the current record's "wed" value
 * @method WorkWeek           setThu()                    Sets the current record's "thu" value
 * @method WorkWeek           setFri()                    Sets the current record's "fri" value
 * @method WorkWeek           setSat()                    Sets the current record's "sat" value
 * @method WorkWeek           setSun()                    Sets the current record's "sun" value
 * @method WorkWeek           setOperationalCountry()     Sets the current record's "OperationalCountry" value
 * 
 * @package    orangehrm
 * @subpackage model\coreleave\base
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseWorkWeek extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_week');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('operational_country_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => 4,
             ));
        $this->hasColumn('mon', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('tue', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('wed', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('thu', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('fri', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('sat', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('sun', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('OperationalCountry', array(
             'local' => 'operational_country_id',
             'foreign' => 'id'));
    }
}