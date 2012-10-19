<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MembershipsPage
 *
 * @author chinthani
 */
class MembershipsPage {
    
    public $txtMembership = "membership_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    
    
    
    
     public function __construct(FunctionalTestcase $selenium){
        $this->selenium = $selenium;
        $this->list = new BasicList($this->selenium, "//form[@id='frmList_ohrmListComponent']");
           }
           
       public function addMembership($Membershipname){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtMembership, $Membershipname);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  }    
           
           
}

?>
