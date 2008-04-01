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
 * Class containing helpful utility functions for searching.
 */
class SearchSqlHelper {

    /* Maps search operators to SQL operators */
    private static $operatorMap = array(
        SearchField::OPERATOR_LESSTHAN => '<',
        SearchField::OPERATOR_GREATERTHAN => '>',
        SearchField::OPERATOR_EQUAL => '=',
        SearchField::OPERATOR_NOT_EQUAL => '<>',    
        SearchField::OPERATOR_STARTSWITH => 'LIKE',
        SearchField::OPERATOR_ENDSWITH => 'LIKE',
        SearchField::OPERATOR_CONTAINS => 'LIKE',
        SearchField::OPERATOR_NOT_CONTAINS => 'NOT LIKE');    

    /**
     * Get SQL Operator for given operator constant from SearchField class
     * 
     * @param String $operator Operator constant from SearchField
     * @return String SQL operator
     */
    public static function getSqlOperator($operator) {
        if (isset(self::$operatorMap[$operator])) {
            return self::$operatorMap[$operator];
        } else {
            throw new SearchSqlHelperException("Invalid operator", SearchSqlHelperException::INVALID_OPERATOR);   
        }
    }
    
    /**
     * Get SQL condition for given parameters
     * 
     * NOTE: Values should be escaped if needed using mysql_real_escape_string before
     *       passing to this method. 
     * 
     * @param String $dbField The database field name
     * @param String $operator Operator constant from SearchField class
     * @param String $value Search Value
     * @param String $fieldType Field type constant from SearchField class
     * @param boolean $addBrackets Should sql condition be surrounded by brackets
     * 
     * @return String SQL Condition
     */
    public static function getSqlCondition($dbField, $operator, $value, $fieldType, $addBrackets = true) {
        
        $sqlOperator = self::getSqlOperator($operator);
        
        /* LIKE is allowed in numeric fields in MySQL */        
        switch ($operator) {
            case SearchField::OPERATOR_STARTSWITH:
                $value = "'" . $value . "%'";
                break;
            case SearchField::OPERATOR_ENDSWITH:
                $value =  "'%" . $value . "'";
                break;
            case SearchField::OPERATOR_CONTAINS: /* Fall through */
            case SearchField::OPERATOR_NOT_CONTAINS:            
                $value =  "'%" . $value . "%'";
                break;                
            default:
                /* Quote all fields except int */
                if ($fieldType != SearchField::FIELD_TYPE_INT) {
                    
                    // Note: value should be escaped with mysql_real_escape before passing to method.
                    $value = "'" . $value . "'"; 
                }
                break;            
        }
        
        
        $sql = $dbField . ' ' . $sqlOperator . ' ' . $value;
        if ($addBrackets) {
            $sql = '(' . $sql . ')';
        }
       
        return $sql;
    }
                
}

class SearchSqlHelperException extends Exception {
    const INVALID_OPERATOR = 1;
}

?>
