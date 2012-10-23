<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JobCategoriesPage
 *
 * @author chinthani
 */
class JobCategoriesPage  {
    public $txtJobCategory = "jobCategory_name";
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
           
       public function addJobCategories($JobCategory){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtJobCategory, $JobCategory);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  } 
        public function editJobCategories($JobCategory){
        $this->selenium->type($this->txtJobCategory, $JobCategory);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  } 
        public function addJobCategoriesWithOutData(){
        $this->selenium->click($this->btnAdd);
        $this->selenium->click($this->btnSave);
        
        }
        
        
        public  function getValidationMessage(){
          echo 'a';
         return $this->selenium->getText("//form[@id='frmSave']/fieldset/ol/li/span");
          echo 'b';

    }
            public  function getStatusMessage(){
          echo 'a';
         return $this->selenium->getText("//form[@id='recordsListTable']/tbody/tr/td");
          echo 'b';

    }
        
          
  
  
       public function clickDelete(){
         $this->selenium->click($this->btnDelete);
         $this->selenium->waitForPageToLoad(10);
         $this->selenium->click($this->btnDialogDelete);      
       
    }
        public function deleteAllJobCategories() {
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
    
    
}

?>
