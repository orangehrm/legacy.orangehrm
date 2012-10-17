<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Calender
 *
 * @author intel
 */
class Calender {

    public static $cmbYear = "//select[@class='ui-datepicker-year']";
    public static $cmbMonth = "//select[@class='ui-datepicker-month']";
    public static $lnkDate = "//table[@class='ui-datepicker-calendar']/tbody";
    public static $monthsInCalender = Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

    public static function selectDateUsingCalendar($selenium, $buttonID, $fullDate) {
        //echo 'd ' . $fullDate;
        $selenium->selectFrame("relative=top");
        $selenium->click($buttonID);
        $monthNumber = substr($fullDate, 5, 2);
        if (substr($monthNumber, 0, 1) == "0")
            $monthNumber = substr($monthNumber, 1, 1);
        for ($i = 1; $i < 13; $i++) {
            if ($monthNumber == $i) {
                $month = self::$monthsInCalender[$i - 1];
                break;
            }
        }
        
        //echo 'came nuskli1';
        //echo $month;
        
        $selenium->select(self::$cmbMonth, $month);
        $year = substr($fullDate, 0, 4);
        //echo 'came nuskli12';
        
        $selenium->select(self::$cmbYear, $year);
        //echo 'came nuskli3';
        $date = substr($fullDate, 8, 2);
        if (substr($date, 0, 1) == "0")
            $date = substr($date, 1, 1);
        $selenium->click(self::$lnkDate . "//a[text()='" . $date . "']");
    }

}