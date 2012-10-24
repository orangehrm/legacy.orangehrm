<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NationalitiesPage
 *
 * @author chinthani
 */
class NationalitiesPage {
       
    public $txtNationality = "nationality_name";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $btnDialogDelete = "dialogDeleteBtn";
    public $btnDialogCancel = "//div[@class='modal-footer']/input[@class='btn reset']";
    public $btnSelectAll = "ohrmList_chkSelectAll";


       public function __construct(FunctionalTestcase $selenium){
        $this->selenium = $selenium;
        $this->list = new BasicList($this->selenium, "//form[@id='frmList_ohrmListComponent']");
           }
           
       public function addNationality($Nationalityname){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtNationality, $Nationalityname);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  }   
         public function editNationality($Nationalityname){
        $this->selenium->type($this->txtNationality, $Nationalityname);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  } 
  
       public function clickDelete(){
         $this->selenium->click($this->btnDelete);
         $this->selenium->waitForPageToLoad(10);
         $this->selenium->click($this->btnDialogDelete);      
       
    }
        public function deleteAllNationality() {
         $this->selenium->click($this->btnSelectAll);
         $this->selenium->click($this->btnDelete);
         $this->selenium->waitForPageToLoad(10);
         $this->selenium->click($this->btnDialogDelete);
    }
    
  
         public function clickCancelDelete(){
         $this->selenium->click($this->btnDelete);
         $this->selenium->waitForPageToLoad(10);
         $this->selenium->click($this->btnDialogCancel);      
         }
       
        public  function getStatusMessage(){
          
         return $this->selenium->getText("//form[@id='frmList_ohrmListComponent']/table/tbody/tr/td");
          

    }
        public function addCancelNationality($Nationality){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtNationality, $Nationality);
        $this->selenium->click($this->btnCancel);
        $this->selenium->waitForPageToLoad(10);
        
          
  }
        public function addNationalityWithOutData(){
        $this->selenium->click($this->btnAdd);
        $this->selenium->click($this->btnSave);
        
        }
        
        
         public  function getValidationMessage(){
         return $this->selenium->getText("//form[@id='frmNationality']/fieldset/ol/li/span");
          

    } 
         
         
    }  

?>
