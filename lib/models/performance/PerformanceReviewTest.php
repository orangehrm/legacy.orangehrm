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
if (! defined ( 'PHPUnit_MAIN_METHOD' )) {
    define ( 'PHPUnit_MAIN_METHOD', 'PerformanceReviewTest::main' );
}

require_once 'PHPUnit/Framework.php';

require_once ROOT_PATH."/lib/confs/Conf.php";
require_once 'PerformanceReview.php';

class PerformanceReviewTest extends PHPUnit_Framework_TestCase {
	
/**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once 'PHPUnit/TextUI/TestRunner.php';
        
        $suite = new PHPUnit_Framework_TestSuite ( 'PerformanceReviewTest' );
        $result = PHPUnit_TextUI_TestRunner::run ( $suite );
    }
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
    	$conf = new Conf();
        $this->connection = mysql_connect($conf->dbhost.":".$conf->dbport, $conf->dbuser, $conf->dbpass);
        mysql_select_db($conf->dbname);
        $this->_deleteTables();
        
        //Create job specs
        $this->_runQuery("INSERT INTO hs_hr_job_spec(jobspec_id,jobspec_name,jobspec_desc,jobspec_duties)".
                          "VALUES(1,'Quality Work','Quality Work','Check the quality')");
        //Create salary grade
        $this->_runQuery("INSERT INTO hs_pr_salary_grade(sal_grd_code,sal_grd_name) VALUES('SAL001','HIGH')");

        //Create job titles
        $this->_runQuery("INSERT INTO hs_hr_job_title(jobtit_code,jobtit_name,jobtit_desc,jobtit_comm,sal_grd_code,jobspec_id )".
                " VALUES('JOB001','Software Engineer','Software Engineer','SE','SAL001','1')" );
        //Create employees
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
                    "VALUES(1, '0011', 'Rajasinghe', 'Saman', 'Marlon')");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
                    "VALUES(2, '0022', 'Jayasinghe', 'Aruna', 'Shantha')");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
                    "VALUES(3, '0023', 'Karunarathne', 'John', 'Kamal')");
        $this->_runQuery("INSERT INTO hs_hr_employee(emp_number, employee_id, emp_lastname, emp_firstname, emp_middle_name) " .
                    "VALUES(4, '0024', 'Silva', 'Pushpa', 'Malini')");
        
        //Create performance reviews
        $this->_runQuery("INSERT INTO hs_hr_perf_review(id, emp_number,review_date,status,review_notes,notification_sent)".
                   "VALUES(1,1,'2009-01-23',1,'cmt1',0)");
        $this->_runQuery("INSERT INTO hs_hr_perf_review(id, emp_number,review_date,status,review_notes,notification_sent)".
                   "VALUES(2,1,'2009-01-27',1,'cmt2',0)");
        $this->_runQuery("INSERT INTO hs_hr_perf_review(id, emp_number,review_date,status,review_notes,notification_sent)".
                   "VALUES(3,1,'2009-01-23',2,'cmt3',0)");
        $this->_runQuery("INSERT INTO hs_hr_perf_review(id, emp_number,review_date,status,review_notes,notification_sent)".
                   "VALUES(4,3,'2009-02-23',1,'cmt4',0)");
        $this->_runQuery("INSERT INTO hs_hr_perf_review(id, emp_number,review_date,status,review_notes,notification_sent)".
                   "VALUES(5,4,'2009-02-27',3,'cmt5',0)");
        
        //Create performance measures
        $this->_runQuery("INSERT INTO hs_hr_perf_measure(id, name)".
                         "VALUES(1,'HIGH')");
        $this->_runQuery("INSERT INTO hs_hr_perf_measure(id, name)".
                         "VALUES(2,'LOW')");
        //Create performance review measures
        $this->_runQuery("INSERT INTO hs_hr_perf_review_measure(review_id, perf_measure_id,score)".
                         "VALUES(1,1,80)");
        $this->_runQuery("INSERT INTO hs_hr_perf_review_measure(review_id, perf_measure_id,score)".
                         "VALUES(2,2,30)");
        $this->_runQuery("INSERT INTO hs_hr_perf_review_measure(review_id, perf_measure_id,score)".
                         "VALUES(3,2,20)");
        $this->_runQuery("INSERT INTO hs_hr_perf_review_measure(review_id, perf_measure_id,score)".
                         "VALUES(4,1,90)");
        //Create performance measures job titles
        $this->_runQuery("INSERT INTO hs_hr_perf_measure_jobtitle(perf_measure_id, jobtit_code)".
                         "VALUES(1,'JOB001')");
        $this->_runQuery("INSERT INTO hs_hr_perf_measure_jobtitle(perf_measure_id, jobtit_code)".
                         "VALUES(2,'JOB001')");
    }
    
    private function _deleteTables() {        
        $this->_runQuery("TRUNCATE TABLE `hs_hr_perf_measure`");
        $this->_runQuery("TRUNCATE TABLE `hs_hr_perf_review_measure`");
        $this->_runQuery("TRUNCATE TABLE `hs_hr_perf_review`");         
        $this->_runQuery("TRUNCATE TABLE `hs_hr_perf_measure_jobtitle`"); 
        $this->_runQuery("TRUNCATE TABLE `hs_hr_employee`");
        $this->_runQuery("TRUNCATE TABLE `hs_hr_job_spec`");
        $this->_runQuery("TRUNCATE TABLE `hs_pr_salary_grade`");
        $this->_runQuery("TRUNCATE TABLE `hs_hr_job_title`");   
    }
    
    protected function tearDown() {
        $this->_deleteTables();        
    }
       
	/**
     * @todo Implement testGetCompltedPerformanceReviews().
     */
    public function testGetCompltedPerformanceReviews(){
    	$performanceReview = new PerformanceReview();
    	
    	//Testing for an employee who has the complted performance reviews.
    	$performanceReview->setEmpNumber(1);    	    	
    	$performanceReviewArr=$performanceReview ->getCompltedPerformanceReviews();
    	$expected = array (array(1,'Software Engineer','2009-01-23',1,'cmt1','HIGH',80),
    	                   array(2,'Software Engineer','2009-01-27',1,'cmt2','LOW',30) );
    	  
    	$this->assertEquals ( $expected, $performanceReviewArr, 'Testing for completed performance reviews' );   

    	//Testing for an employee who does not have the complted performance reviews.
    	$performanceReview->setEmpNumber(2);                
        $performanceReviewArr=$performanceReview ->getCompltedPerformanceReviews();
        $expected = array ();
          
        $this->assertEquals ( $expected, $performanceReviewArr, 'Testing for the status not equal to completed' );   
    	
    	
    }
     
	private function _runQuery($sql) {
        $this->assertTrue(mysql_query($sql), mysql_error());
    }
    
}

if (PHPUnit_MAIN_METHOD == 'PerformanceReviewTest::main') {
    PerformanceReviewTest::main ();
}
?>