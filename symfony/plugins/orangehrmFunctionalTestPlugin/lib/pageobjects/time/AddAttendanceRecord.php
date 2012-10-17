<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddAttendanceRecord
 *
 * @author Faris
 */
class AddAttendanceRecord extends Page {

    public $attendanceDate;
    public $attendanceTime;
    public $attendanceTimezone;
    public $attendanceNote;
    public $btnPunch;
    public $config;
    public $btnAttendance;
    public $list;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->attendanceDate = "//input[@id='attendance_date']";
        $this->attendanceTime = "//form[@id='punchTimeForm']/table/tbody/tr/td//input[@id='attendance_time']";
        $this->attendanceTimezone = "//form[@id='punchTimeForm']/table/tbody/tr[3]/td[2]//input[@id='attendance_timezone']";
        $this->attendanceNote = "//form[@id='punchTimeForm']/table/tbody/tr/td/textarea[@id='attendance_note']";
        $this->btnPunch = "btnPunch";
        $this->btnAttendance = "//input[@id='attendance_date_Button']";
        $this->list = new BasicList($selenium, "//div[@id='recordsTable1']", True);
    }

    public function punchIn($attendanceDate=null, $attendanceTime=null, $attendanceTimezone=null, $attendanceNote=null) {
        if ($attendanceDate != null) {
            
            //echo "came here";
            $this->selenium->type($this->attendanceDate, "");
            Calender::selectDateUsingCalendar($this->selenium, $this->btnAttendance, $attendanceDate);
            
            
        }

        if ($attendanceTime != null) {
            //$this->selenium->click($this->attendanceTime);
            $this->selenium->type($this->attendanceTime, $attendanceTime);
            //$this->selenium->click($this->attendanceTime);
        }

        if ($attendanceTimezone != null) {
            
            $this->selenium->select($this->attendanceTimezone, $attendanceTimezone);
        }

        if ($attendanceNote != null) {
            $this->selenium->click($this->attendanceNote);
            $this->selenium->type($this->attendanceNote, $attendanceNote);
        }
        
        $this->selenium->clickAndWait($this->btnPunch);
        sleep(5);
    }

    public function punchOut($attendanceDate=null, $attendanceTime=null, $attendanceTimezone=null, $attendanceNote=null) {
        if ($attendanceDate != null) {
            
            $this->selenium->type($this->attendanceDate, "");
           Calender::selectDateUsingCalendar($this->selenium, $this->btnAttendance, $attendanceDate);
            
        }

        if ($attendanceTime != null) {
           //$this->selenium->click($this->attendanceTime);
            $this->selenium->type($this->attendanceTime, $attendanceTime);
            //$this->selenium->click($this->attendanceTime);
        }

        if ($attendanceTimezone != null) {
            $this->selenium->select($this->attendanceTimezone, $attendanceTimezone);
        }

        if ($attendanceNote != null) {
            $this->selenium->click($this->attendanceNote);
            $this->selenium->type($this->attendanceNote, $attendanceNote);
        }
        
        $this->selenium->clickAndWait($this->btnPunch);
        sleep(5);
        
        
    }

    public function verify($header, $data) {
        if ($this->list->isItemPresentInColumn($header, $data))
            return TRUE;
        else
            return FALSE;
    }
    
    
    public function verifyPunchIn($value, $sub=null)
    {
             //echo "id('b')/div[3]/div[2]/div/div[2]/.[text()=contains(.,'" . $value . "')]";

        if($sub==null)
        {
 
        if ($this->selenium->isElementPresent("//body[@id='b']/div[3]/div[2]/div/div[2]")) {
            return TRUE;
        } else {
            return FALSE;
        }
        }
        else
        {
  
         if ($this->selenium->isElementPresent("//div[@id='b']/div[3]/div[2]/div/div[2]/.[text()=contains(.,'".$value."')]")) {
            return TRUE;
        } else {
            return FALSE;
        }
        }
        
    }
    
    public function verifyPunchOut($value)
    {
        
        
   
        
        if ($this->selenium->isElementPresent("//div[@id='messagebar']/.[text()=contains(.,'" . $value . "')]")) {
            return TRUE;
        } else {
            return FALSE;
        }
        
        
        
    }

}