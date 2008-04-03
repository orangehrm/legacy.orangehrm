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
 
/**
 * Class representing search operators.
 * This class somewhat resembles the prototype design pattern
 */ 
class SearchOperator {
    
    /** Operator type constants */
    const OPERATOR_LESSTHAN = 'lt';
    const OPERATOR_GREATERTHAN = 'gt';
    const OPERATOR_EQUAL = 'eq';
    const OPERATOR_NOT_EQUAL = 'neq';    
    const OPERATOR_STARTSWITH = 'starts';
    const OPERATOR_ENDSWITH = 'ends';
    const OPERATOR_CONTAINS = 'contains';
    const OPERATOR_NOT_CONTAINS = 'not_contains';    
    const OPERATOR_EMPTY = 'empty';
    const OPERATOR_NOT_EMPTY = 'not_empty';        
    
    /** The type of the operator */
    private $type;
    
    /** 
     * Is the operator a binary operator? (considered unary if not) 
     * Eg: Binary operators: <, >
     * Unary: Is Empty 
     */
    private $binary;
    
    /**
     * Static array containing list of all possible operators
     */
    private static $operators;
    
    /**
     * Constructor
     * 
     * @param String $operatorType The Operator type
     * @param bool $binary Is this a binary operator?
     */
    private function __construct($type, $binary = true) {
        $this->type = $type;
        $this->binary = $binary;
    }

    /**
     * Get operator of given type
     * @param $type Type of operator
     * 
     * @return SearchOperator Search Operator object of given type
     */
    public static function getOperator($type) {
        
        if (empty(self::$operators)) {
            self::_createOperators(); 
        }
        
        $operator = null;
        if (isset(self::$operators[$type])) {            
            $operator = self::$operators[$type];
        }
        return $operator;
    }
    
    /**
     * Return all operators
     * @param Array Array of all SearchOperator's
     */
    public static function getAll() {
        return self::$operators;
    }
    
    /**
     * Create objects of all known operator types
     */
    private static function _createOperators() {
        
        /* Binary operators */
        self::$operators[self::OPERATOR_LESSTHAN] = new SearchOperator(self::OPERATOR_LESSTHAN);
        self::$operators[self::OPERATOR_GREATERTHAN] = new SearchOperator(self::OPERATOR_GREATERTHAN);
        self::$operators[self::OPERATOR_EQUAL] = new SearchOperator(self::OPERATOR_EQUAL);
        self::$operators[self::OPERATOR_NOT_EQUAL] = new SearchOperator(self::OPERATOR_NOT_EQUAL);
        self::$operators[self::OPERATOR_STARTSWITH] = new SearchOperator(self::OPERATOR_STARTSWITH);
        self::$operators[self::OPERATOR_ENDSWITH] = new SearchOperator(self::OPERATOR_ENDSWITH);
        self::$operators[self::OPERATOR_CONTAINS] = new SearchOperator(self::OPERATOR_CONTAINS);
        self::$operators[self::OPERATOR_NOT_CONTAINS] = new SearchOperator(self::OPERATOR_NOT_CONTAINS);
        
        /* Unary operators */
        self::$operators[self::OPERATOR_EMPTY] = new SearchOperator(self::OPERATOR_EMPTY, false);
        self::$operators[self::OPERATOR_NOT_EMPTY] = new SearchOperator(self::OPERATOR_NOT_EMPTY, false);                        
    }
    
    /**
     * Retrieves the value of type.
     * @return type
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Is this a binary operator?
     * @return bool binary
     */
    public function isBinary() {
        return $this->binary;
    }
}

?>
