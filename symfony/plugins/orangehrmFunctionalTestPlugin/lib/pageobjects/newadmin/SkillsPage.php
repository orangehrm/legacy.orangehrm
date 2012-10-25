<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SkillsPage
 *
 * @author chinthani
 */
class SkillsPage {
    public $txtName = "skill_name";
    public $txtDescription = "skill_description";
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
           
       public function addSkills($Skills, $Description){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtName, $Skills);
        $this->selenium->type($this->txtDescription, $Description);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  }   
  
        public function editSkills($Skills, $Description){
        $this->selenium->type($this->txtName, $Skills);
        $this->selenium->type($this->txtDescription, $Description);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);
        
          
  } 
  
       public function clickDelete(){
         $this->selenium->click($this->btnDelete);
         //$this->selenium->waitForPageToLoad(10);
         //$this->selenium->click($this->btnDialogDelete);      
       
    }
        public function deleteAllSkills() {
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
    public function addCancelSkills($Skills, $Description){
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->txtName, $Skills);
        $this->selenium->type($this->txtDescription, $Description);
        $this->selenium->click($this->btnCancel);
        $this->selenium->waitForPageToLoad(10);
        
          
  }   
    
        public function addSkillsWithOutData(){
        $this->selenium->click($this->btnAdd);
        $this->selenium->click($this->btnSave);
        
        }
        
        
        public  function getValidationMessage(){
        return $this->selenium->getText("//form[@id='frmSave']/fieldset/ol/li/span");
          }
          
        public function getSkillsList(){   
        return $this->list;
        
    }
          
}

?>
