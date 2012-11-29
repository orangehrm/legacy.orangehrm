<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */

/**
 * Description of EmailDaoTest
 */
class EmailDaoTest extends PHPUnit_Framework_TestCase {

    private $emailDao;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->emailDao = new EmailDao();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCorePlugin/test/fixtures/EmailDao.yml';
        TestDataService::populate($this->fixture);
    }

    public function testGetEmailByName() {
        
        $name = 'leave.cancel.subscriber';
        $email = $this->emailDao->getEmailByName($name);
        
        $this->assertTrue($email instanceof OhrmEmail);
        $this->assertEquals(11, $email->getId());
    }
    
    public function testGetEmailByNameNonExisting() {
        
        $name = 'leave.test.subscriber';
        $email = $this->emailDao->getEmailByName($name);
        
        $this->assertFalse($email);
    }    

}