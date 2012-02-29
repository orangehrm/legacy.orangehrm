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
class LocationDao extends BaseDao {

	/**
	 *
	 * @param type $locationId
	 * @return type 
	 */
	public function getLocationById($locationId) {

		try {
			return Doctrine :: getTable('Location')->find($locationId);
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @param type $srchClues
	 * @return type 
	 */
	public function getSearchLocationListCount($srchClues) {

		try {
			$q = $this->_buildSearchQuery($srchClues);
			return $q->count();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @param type $srchClues
	 * @return type 
	 */
	public function searchLocations($srchClues) {

		$sortField = ($srchClues['sortField'] == "") ? 'name' : $srchClues['sortField'];
		$sortOrder = ($srchClues['sortOrder'] == "") ? 'ASC' : $srchClues['sortOrder'];
		$offset = ($srchClues['offset'] == "") ? 0 : $srchClues['offset'];
		$limit = ($srchClues['limit'] == "") ? 50 : $srchClues['limit'];

		try {
			$q = $this->_buildSearchQuery($srchClues);
			$q->orderBy($sortField . ' ' . $sortOrder)
				->offset($offset)
				->limit($limit);
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @param type $srchClues
	 * @return type 
	 */
	private function _buildSearchQuery($srchClues) {

		$q = Doctrine_Query::create()
			->from('Location');

		if (!empty($srchClues['name'])) {
			$q->addWhere('name LIKE ?', "%" . trim($srchClues['name']) . "%");
		}
		if (!empty($srchClues['city'])) {
			$q->addWhere('city LIKE ?', "%" . trim($srchClues['city']) . "%");
		}
		if (!empty($srchClues['country'])) {
			$q->addWhere('country_code = ?', $srchClues['country']);
		}
		return $q;
	}

	/**
	 *
	 * @param type $locationId
	 * @return type 
	 */
	public function getNumberOfEmplyeesForLocation($locationId) {

		try {
			$q = Doctrine_Query :: create()
				->from('EmpLocations')
				->where('location_id = ?', $locationId);
			return $q->count();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @return type 
	 */
	public function getLocationList() {
		
		try {
			$q = Doctrine_Query :: create()
				->from('Location l')
                                ->orderBy('l.name ASC');
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}
}

?>
