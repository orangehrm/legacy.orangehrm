<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanguagePage
 *
 * @author chinthani
 */
class LanguagePage {
    public $txtLanguage = "language_name";
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
           
       public function addLanguage($Language){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtLanguage, $Language);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  }  
        public function editLanguage($Language){
        $this->selenium->type($this->txtLanguage, $Language);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  }  
  
       public function clickDelete(){
         $this->selenium->click($this->btnDelete);
//         $this->selenium->waitForPageToLoad(10);
//         $this->selenium->click($this->btnDialogDelete);      
       
    }
        public function deleteAllLanguage() {
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
        public function addCancelLanguage($Language){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtLanguage, $Language);
        $this->selenium->click($this->btnCancel);
        $this->selenium->waitForPageToLoad(10);
        
          
  }
  
        public function addLanguageWithOutData(){
        $this->selenium->click($this->btnAdd);
        $this->selenium->click($this->btnSave);
        
        }
        
        
        public  function getValidationMessage(){
          return $this->selenium->getText("//form[@id='frmSave']/fieldset/ol/li/span");
                  }
}

?>
