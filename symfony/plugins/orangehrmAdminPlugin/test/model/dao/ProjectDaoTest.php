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
 */
require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

/**
 * @group Admin
 */
class ProjectDaoTest extends PHPUnit_Framework_TestCase {

	private $projectDao;
	protected $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {

		$this->projectDao = new ProjectDao();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/ProjectDao.yml';
		TestDataService::populate($this->fixture);
	}

	public function testSearchProjectsForNullArray() {
		$srchClues = array();
		$allowedProjectList = array(1, 2);
		$result = $this->projectDao->searchProjects($srchClues, $allowedProjectList);
		$this->assertEquals(count($result), 2);
	}

	public function testSearchProjectsForProjectName() {
		$srchClues = array(
		    'project' => 'project 1'
		);
		$allowedProjectList = array(1);
		$result = $this->projectDao->searchProjects($srchClues, $allowedProjectList);
		$this->assertEquals(count($result), 1);
		$this->assertEquals($result[0]->getProjectId(), 1);
	}

	public function testSearchProjectsForCustomerName() {
		$srchClues = array(
		    'customer' => 'customer 1'
		);
		$allowedProjectList = array(1, 2);
		$result = $this->projectDao->searchProjects($srchClues, $allowedProjectList);
		$this->assertEquals(count($result), 2);
		$this->assertEquals($result[1]->getCustomerName(), 'customer 1');
	}

	public function testSearchProjectsForProjectAdmin() {
		$srchClues = array(
		    'projectAdmin' => 'Kayla Abbey'
		);
		$allowedProjectList = array(1);
		$result = $this->projectDao->searchProjects($srchClues, $allowedProjectList);
		$this->assertEquals(count($result), 1);
		$this->assertEquals($result[0]->getProjectId(), 1);
	}

	public function testGetProjectCountWithActiveOnly() {
		$result = $this->projectDao->getProjectCount();
		$this->assertEquals($result, 2);
	}
	
	public function testGetProjectCount() {
		$result = $this->projectDao->getProjectCount(false);
		$this->assertEquals($result, 3);
	}

	public function testDeleteProject() {
		$this->projectDao->deleteProject(1);
		$result = $this->projectDao->getProjectById(1);
		$this->assertEquals($result->getIsDeleted(), 1);
	}

	public function testGetProjectActivityById() {
		$result = $this->projectDao->getProjectActivityById(1);
		$this->assertEquals($result->getName(), 'project activity 1');
	}

	public function testGetProjectById() {
		$result = $this->projectDao->getProjectById(1);
		$this->assertEquals($result->getName(), 'project 1');
	}

	public function testGetAllActiveProjectsWithActiveOnly() {
		$result = $this->projectDao->getAllProjects();
		$this->assertEquals(count($result), 2);
	}
	
	public function testGetAllActiveProjects() {
		$result = $this->projectDao->getAllProjects(false);
		$this->assertEquals(count($result), 3);
	}

//	public function testGetActivityListByProjectId() {
//		$result = $this->projectDao->getActivityListByProjectId(1);
//		$this->assertEquals(count($result), 2);
//		$this->assertEquals($result[0], 'project activity 1');
//	}

	public function testGetSearchProjectListCount() {
		$srchClues = array(
		    'projectAdmin' => 'Kayla Abbey'
		);
		$allowedProjectList = array(1);
		$result = $this->projectDao->getSearchProjectListCount($srchClues,$allowedProjectList);
		$this->assertEquals($result, 1);
	}

	public function testGetActiveProjectList() {

		$activeProjects = $this->projectDao->getActiveProjectList();
		$this->assertTrue($activeProjects[0] instanceof Project);
		$this->assertEquals(2, count($activeProjects));
	}

	public function testGetProjectsByProjectIdsWithActiveOnly() {

		$projectIdArray = array(1, 2);
		$activeProjects = $this->projectDao->getProjectsByProjectIds($projectIdArray);
		$this->assertTrue($activeProjects[0] instanceof Project);
		$this->assertEquals(2, count($activeProjects));
	}
	
	public function testGetActiveProjectsByProjectIds() {

		$projectIdArray = array(1, 2);
		$activeProjects = $this->projectDao->getProjectsByProjectIds($projectIdArray);
		$this->assertTrue($activeProjects[0] instanceof Project);
		$this->assertEquals(2, count($activeProjects));
	}

	public function testGetProjectAdminRecordsByEmpNo() {

		$empNo = 1;
		$projectAdmin = $this->projectDao->getProjectAdminByEmpNumber($empNo);
		$this->assertTrue($projectAdmin[0] instanceof ProjectAdmin);
		$this->assertEquals(1, count($projectAdmin));
	}
	
	public function testGetProjectAdminByProjectId() {

		$projectAdmin = $this->projectDao->getProjectAdminByProjectId(1);
		$this->assertTrue($projectAdmin[0] instanceof ProjectAdmin);
		$this->assertEquals(2, count($projectAdmin));
	}
	
	public function testDeleteProjectActivities() {

		$this->projectDao->deleteProjectActivities(1);
		$projectActivity = $this->projectDao->getProjectActivityById(1);
		$this->assertEquals($projectActivity->getIsDeleted(), 1);
	}

	public function testHasProjectGotTimesheetItems() {

		$result = $this->projectDao->hasProjectGotTimesheetItems(2);
		$this->assertTrue($result);
	}
	
	public function testHasActivityGotTimesheetItems() {

		$result = $this->projectDao->hasActivityGotTimesheetItems(1);
		$this->assertTrue($result);
	}
	
	public function testGetProjectsByCustomerId() {

		$result = $this->projectDao->getProjectsByCustomerId(1);
		$this->assertEquals(count($result), 2);
		$this->assertTrue($result[0] instanceof Project);
	}
	
	public function testGetProjectListForUserRole() {

		$result = $this->projectDao->getProjectListForUserRole(AdminUserRoleDecorator::ADMIN_USER, null);
		$this->assertEquals(count($result), 3);
	}

}
