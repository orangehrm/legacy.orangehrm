<?php

/**
 * BaseEmployeeMemberDetail
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $empNumber
 * @property integer $membershipCode
 * @property decimal $subscriptionAmount
 * @property string $subscriptionPaidBy
 * @property string $subscriptionCurrency
 * @property date $subscriptionCommenceDate
 * @property date $subscriptionRenewalDate
 * @property Membership $Membership
 * @property Employee $Employee
 * 
 * @method integer              getEmpNumber()                Returns the current record's "empNumber" value
 * @method integer              getMembershipCode()           Returns the current record's "membershipCode" value
 * @method decimal              getSubscriptionAmount()       Returns the current record's "subscriptionAmount" value
 * @method string               getSubscriptionPaidBy()       Returns the current record's "subscriptionPaidBy" value
 * @method string               getSubscriptionCurrency()     Returns the current record's "subscriptionCurrency" value
 * @method date                 getSubscriptionCommenceDate() Returns the current record's "subscriptionCommenceDate" value
 * @method date                 getSubscriptionRenewalDate()  Returns the current record's "subscriptionRenewalDate" value
 * @method Membership           getMembership()               Returns the current record's "Membership" value
 * @method Employee             getEmployee()                 Returns the current record's "Employee" value
 * @method EmployeeMemberDetail setEmpNumber()                Sets the current record's "empNumber" value
 * @method EmployeeMemberDetail setMembershipCode()           Sets the current record's "membershipCode" value
 * @method EmployeeMemberDetail setSubscriptionAmount()       Sets the current record's "subscriptionAmount" value
 * @method EmployeeMemberDetail setSubscriptionPaidBy()       Sets the current record's "subscriptionPaidBy" value
 * @method EmployeeMemberDetail setSubscriptionCurrency()     Sets the current record's "subscriptionCurrency" value
 * @method EmployeeMemberDetail setSubscriptionCommenceDate() Sets the current record's "subscriptionCommenceDate" value
 * @method EmployeeMemberDetail setSubscriptionRenewalDate()  Sets the current record's "subscriptionRenewalDate" value
 * @method EmployeeMemberDetail setMembership()               Sets the current record's "Membership" value
 * @method EmployeeMemberDetail setEmployee()                 Sets the current record's "Employee" value
 * 
 * @package    orangehrm
 * @subpackage model\pim\base
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseEmployeeMemberDetail extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('hs_hr_emp_member_detail');
        $this->hasColumn('emp_number as empNumber', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('membship_code as membershipCode', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('ememb_subscript_amount as subscriptionAmount', 'decimal', 15, array(
             'type' => 'decimal',
             'scale' => false,
             'length' => 15,
             ));
        $this->hasColumn('ememb_subscript_ownership as subscriptionPaidBy', 'string', 30, array(
             'type' => 'string',
             'default' => '',
             'length' => 30,
             ));
        $this->hasColumn('ememb_subs_currency as subscriptionCurrency', 'string', 13, array(
             'type' => 'string',
             'default' => '',
             'length' => 13,
             ));
        $this->hasColumn('ememb_commence_date as subscriptionCommenceDate', 'date', 25, array(
             'type' => 'date',
             'length' => 25,
             ));
        $this->hasColumn('ememb_renewal_date as subscriptionRenewalDate', 'date', 25, array(
             'type' => 'date',
             'length' => 25,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Membership', array(
             'local' => 'membershipCode',
             'foreign' => 'id'));

        $this->hasOne('Employee', array(
             'local' => 'empNumber',
             'foreign' => 'empNumber'));
    }
}