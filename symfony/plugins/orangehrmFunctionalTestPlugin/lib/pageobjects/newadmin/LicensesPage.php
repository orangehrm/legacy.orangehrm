<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LicensesPage
 *
 * @author chinthani
 */
class LicensesPage {
   public $txtLicenses = "license_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDel";
    public $btnDialogDelete = "dialogDeleteBtn";
    public $btnDialogCancel = "//div[@class='modal-footer']/input[@class='btn reset']";
    public $btnSelectAll = "checkAll";
    
    
    
    
     public function __construct(FunctionalTestcase $selenium){
        $this->selenium = $selenium;
        $this->list = new BasicList($this->selenium, "//form[@id='frmList']");
           }
           
       public function addLicenses($Licenses){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtLicenses, $Licenses);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  }  
        public function editLicenses($Licenses){
        $this->selenium->type($this->txtLicenses, $Licenses);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  }  
  
       public function clickDelete(){
         $this->selenium->click($this->btnDelete);
//         $this->selenium->waitForPageToLoad(10);
//         $this->selenium->click($this->btnDialogDelete);      
       
    }
        public function deleteAllLicenses() {
         $this->selenium->click($this->btnSelectAll);
         $this->selenium->click($this->btnDelete);
//         $this->selenium->waitForPageToLoad(10);
//         $this->selenium->click($this->btnDialogDelete);
    }
    
  
         public function clickCancelDelete(){
         $this->selenium->click($this->btnDelete);
         $this->selenium->waitForPageToLoad(10);
         $this->selenium->click($this->btnDialogCancel);      
        
    }     
          public  function getStatusMessage(){
          return $this->selenium->getText("//table[@id='recordsListTable']/tbody/tr/td");
          

    }
     
}

?>
