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

require_once ROOT_PATH . '/lib/common/search/SearchOperator.php';
 
/**
 * Class containing helpful utility functions for searching.
 */
class SearchSqlHelper {

    /* Maps search operators to SQL operators */
    private static $operatorMap = array(
        SearchOperator::OPERATOR_LESSTHAN => '<',
        SearchOperator::OPERATOR_GREATERTHAN => '>',
        SearchOperator::OPERATOR_EQUAL => '=',
        SearchOperator::OPERATOR_NOT_EQUAL => '<>',    
        SearchOperator::OPERATOR_STARTSWITH => 'LIKE',
        SearchOperator::OPERATOR_ENDSWITH => 'LIKE',
        SearchOperator::OPERATOR_CONTAINS => 'LIKE',
        SearchOperator::OPERATOR_NOT_CONTAINS => 'NOT LIKE',
        SearchOperator::OPERATOR_EMPTY => 'IS NULL',
        SearchOperator::OPERATOR_NOT_EMPTY => 'IS NOT NULL');    

    /**
     * Get SQL Operator for given SearchOperator object
     * 
     * @param String $operator SearchOperator object
     * @return String SQL operator
     */
    public static function getSqlOperator($operator) {
        if (($operator instanceof SearchOperator) && isset(self::$operatorMap[$operator->getType()])) {
            return self::$operatorMap[$operator->getType()];
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
     * @param String $operator SearchOperator object
     * @param String $value Search Value
     * @param String $fieldType Field type constant from SearchField class
     * @param boolean $addBrackets Should sql condition be surrounded by brackets
     * 
     * @return String SQL Condition
     */
    public static function getSqlCondition($dbField, $operator, $value, $fieldType, $addBrackets = true) {
        
        $operatorType = $operator->getType();        
        $sqlOperator = self::getSqlOperator($operator);
        
        if (($operatorType == SearchOperator::OPERATOR_EMPTY) ||
                ($operatorType == SearchOperator::OPERATOR_NOT_EMPTY)) {
            
            /* Here $sqlOperator is "IS NULL" or "IS NOT NULL" */
            $sql = "{$dbField} {$sqlOperator}";            
        } else {
            
            /* LIKE is allowed in numeric fields in MySQL */        
            switch ($operatorType) {
                case SearchOperator::OPERATOR_STARTSWITH:
                    $value = "'" . $value . "%'";
                    break;
                case SearchOperator::OPERATOR_ENDSWITH:
                    $value =  "'%" . $value . "'";
                    break;
                case SearchOperator::OPERATOR_CONTAINS: /* Fall through */
                case SearchOperator::OPERATOR_NOT_CONTAINS:            
                    $value =  "'%" . $value . "%'";
                    break;                
                default:
                    /* Quote all fields except int */
                    if ($fieldType != DataField::FIELD_TYPE_INT) {
                        
                        // Note: value should be escaped with mysql_real_escape before passing to method.
                        $value = "'" . $value . "'"; 
                    }
                    break;            
            }
        
            $sql = $dbField . ' ' . $sqlOperator . ' ' . $value;
        }
        
        /* 
         * Should null fields be matched.
         * For <> and NOT LIKE comparisons we need to specifically match NULL fields by
         * adding a IS NULL in front, otherwise NULL fields are not matched. 
         */
        if (($operatorType == SearchOperator::OPERATOR_NOT_EQUAL) || 
                ($operatorType == SearchOperator::OPERATOR_NOT_CONTAINS)) {
                    
            $sql = "({$dbField} IS NULL) OR ({$sql})";                                
        }

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
