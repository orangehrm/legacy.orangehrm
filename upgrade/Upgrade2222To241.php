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

require_once 'Upgrader.php';

class Upgrade2222To241 extends Upgrader {

	public function isDataCompatible() {

	/* By 2.4.1, hs_hr_empreport.rep_name and hs_pr_salary_grade.sal_grd_name were made unique */

		$flag = true;
		$errorArray = array();

		// This query check whether there are duplicate report names
		$query = "SELECT COUNT(*) as repetitions, `rep_name` FROM `hs_hr_empreport` GROUP BY `rep_name` HAVING repetitions > 1";
		$result = mysql_query($query, $this->conn);

		if (mysql_num_rows($result) > 0) {
			$flag = false;
			$errorArray[] = "You have duplicate report names in Report Module (Reports > View Reports)";
		}

		// This query check whether there are duplicate pay grades
		$query = "SELECT COUNT(*) as repetitions, `sal_grd_name` FROM `hs_pr_salary_grade` GROUP BY `sal_grd_name` HAVING repetitions > 1";
		$result = mysql_query($query, $this->conn);

		if (mysql_num_rows($result) > 0) {
			$flag = false;
			$errorArray[] = "You have duplicate pay grade names in Admin Module (Admin > Job > Pay Grades)";
		}

		$this->errorArray = $errorArray;

		return $flag;

	}

}

?>
