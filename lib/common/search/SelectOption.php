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
 * Ruchira
 */
 
class SelectOption {
      
    /** The value of the option (eg. code or ID) */
    private $value;
   
    /** The name of the option (The text that should be displayed)*/
    private $name;
    
    /**
     * The language variable containing the option name
     * Only used if $name is not set.
     * eg: lang_common_Yes 
     */
    private $nameVar;
    
    /**
     * Constructor
     * 
     * @param String $value Option Value
     * @param String $name Option name
     * @param String $nameVar Language variable containing option name 
     *               (used only if option not defined)
     */
    public function __construct($value, $name, $nameVar = null) {
        $this->value = $value;
        $this->name = $name;
        $this->nameVar = $nameVar;
    }
    
    /**
     * Retrieves the value of value.
     * @return value
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Sets the value of value.
     * @param value
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * Retrieves the value of name.
     * @return name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the value of name.
     * @param name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Retrieves the value of nameVar.
     * @return nameVar
     */
    public function getNameVar() {
        return $this->nameVar;
    }

    /**
     * Sets the value of nameVar.
     * @param nameVar
     */
    public function setNameVar($nameVar) {
        $this->nameVar = $nameVar;
    }
}

?>
