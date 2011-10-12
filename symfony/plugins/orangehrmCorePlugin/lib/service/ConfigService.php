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

/**
 * Config Service: Manages configuration entries in hs_hr_config
 *
 */
class ConfigService extends BaseService {

    private $configDao;

    const KEY_LEAVE_PERIOD_DEFINED = "leave_period_defined";
    const KEY_PIM_SHOW_DEPRECATED = "pim_show_deprecated_fields";
    const KEY_PIM_SHOW_SSN = 'pim_show_ssn';
    const KEY_PIM_SHOW_SIN = 'pim_show_sin';
    const KEY_PIM_SHOW_TAX_EXEMPTIONS = 'pim_show_tax_exemptions';
    const KEY_TIMESHEET_TIME_FORMAT = 'timesheet_time_format';
    const KEY_TIMESHEET_PERIOD_AND_START_DATE = 'timesheet_period_and_start_date';
    const KEY_TIMESHEET_PERIOD_SET = 'timesheet_period_set';

    /**
     * Get ConfigDao
     * @return ConfigDao
     */
    public function getConfigDao() {

        if ($this->configDao instanceof ConfigDao) {
            return $this->configDao;
        } else {
            $this->configDao = new ConfigDao();
        }

        return $this->configDao;
    }

    /**
     * Set ConfigDao
     * @param ConfigDao $configDao
     * @return void
     */
    public function setConfigDao(ConfigDao $configDao) {
        $this->configDao = $configDao;
    }

    /**
     * Constructor
     */
    public function __construct() {
        
    }

    /**
     * Get Logger instance. Creates if not already created.
     *
     * @return Logger
     */
    protected function getLogger() {
        if (is_null($this->logger)) {
            $this->logger = Logger::getLogger('core.ConfigService');
        }

        return($this->logger);
    }

    /**
     *
     * @param type $key 
     */
    protected function _getConfigValue($key) {

        try {
            return $this->getConfigDao()->getValue($key);
        } catch (DaoException $e) {
            $this->getLogger()->error("Exception in _getConfigValue:" . $e);
            throw new CoreServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *
     * @param type $key
     * @param type $value 
     */
    protected function _setConfigValue($key, $value) {
        try {
            $this->getConfigDao()->setValue($key, $value);
        } catch (DaoException $e) {
            $this->getLogger()->error("Exception in _setConfigValue:" . $e);
            throw new CoreServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function setIsLeavePeriodDefined($value) {
        if ($value != 'Yes' && $value != 'No') {
            throw new Exception("Given value for setIsLeavePriodDefined should be 'Yes' or 'No'");
        }
        $this->_setConfigValue(self::KEY_LEAVE_PERIOD_DEFINED, $value);
    }

    /**
     * Get Value: Whether leave period has been set
     * @return bool Returns true if leave period has been set
     */
    public function isLeavePeriodDefined() {
        $val = $this->_getConfigValue(self::KEY_LEAVE_PERIOD_DEFINED);
        return ($val == 'Yes');
    }

    /**
     * Set show deprecated fields config value
     * @param boolean $value true or false
     */
    public function setShowPimDeprecatedFields($value) {
        $flag = $value ? 1 : 0;
        $this->_setConfigValue(self::KEY_PIM_SHOW_DEPRECATED, $flag);
    }

    public function showPimDeprecatedFields() {
        $val = $this->_getConfigValue(self::KEY_PIM_SHOW_DEPRECATED);
        return ($val == 1);
    }

    public function setShowPimSSN($value) {
        $flag = $value ? 1 : 0;
        $this->_setConfigValue(self::KEY_PIM_SHOW_SSN, $flag);
    }

    /**
     * Show PIM Deprecated Fields
     * @return bool
     */
    public function showPimSSN() {
        $val = $this->_getConfigValue(self::KEY_PIM_SHOW_SSN);
        return ($val == 1);
    }

    public function setShowPimSIN($value) {
        $flag = $value ? 1 : 0;
        $this->_setConfigValue(self::KEY_PIM_SHOW_SIN, $flag);
    }

    /**
     * Show PIM Deprecated Fields
     * @return bool
     */
    public function showPimSIN() {
        $val = $this->_getConfigValue(self::KEY_PIM_SHOW_SIN);
        return ($val == 1);
    }

    /**
     * @param boolean $value 
     * @return void
     */
    public function setShowPimTaxExemptions($value) {
        $flag = $value ? 1 : 0;
        $this->_setConfigValue(self::KEY_PIM_SHOW_TAX_EXEMPTIONS, $flag);
    }

    public function showPimTaxExemptions() {
        $val = $this->_getConfigValue(self::KEY_PIM_SHOW_TAX_EXEMPTIONS);
        return ($val == 1);
    }

}