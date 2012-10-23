<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EducationPage
 *
 * @author chinthani
 */
class EducationPage {
    public $txtEducation = "education_name";
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
           
       public function addEducation($Education){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtEducation, $Education);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  }   
  
        public function editEducation($Education){
        $this->selenium->type($this->txtEducation, $Education);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  } 
  
       public function clickDelete(){
         $this->selenium->click($this->btnDelete);
         //$this->selenium->waitForPageToLoad(10);
         //$this->selenium->click($this->btnDialogDelete);      
       
    }
        public function deleteAllEducation() {
         $this->selenium->click($this->btnSelectAll);
         $this->selenium->click($this->btnDelete);
         //$this->selenium->waitForPageToLoad(10);
         //$this->selenium->click($this->btnDialogDelete);
    }
    
  
         public function clickCancelDelete(){
         $this->selenium->click($this->btnDelete);
         $this->selenium->waitForPageToLoad(10);
         $this->selenium->click($this->btnDialogCancel);      
         }
         
         public  function getStatusMessage(){
          return $this->selenium->getText("//table[@id='recordsListTable']/tbody/tr/td");
          

    }
    public function addCancelEducation($Education){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtEducation, $Education);
        $this->selenium->click($this->btnCancel);
        $this->selenium->waitForPageToLoad(10);
        
          
  }   
         
         
      }

?>
