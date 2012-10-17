<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddCompanyStructureUnitPageObject
 *
 * @author intel
 */
class AddProjectPageObject extends Component {

    public $txtCustomerName = "addProject_customerName";
    public $linkAddCustomer = "//span[@id='addCustomerLink']";
    public $linkRemoveProjectAdmin = "//span[@id='removeButton2']";
    public $linkAddAnotherProjectAdmin = "//a[@id='addButton']";
    public $txtProjectName = "addProject_projectName";
    public $txtProjectAdmin1 = "addProject_projectAdmin_1";
    public $txtProjectAdmin2 = "addProject_projectAdmin_2";
    public $txtDescription = "addProject_description";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $addCustomerDialogBox;
    public $projectActivityPageObject;

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Project");
        $this->addCustomerDialogBox = new AddCustomerPageObject($this->selenium);
        $this->projectActivityPageObject = new AddProjectActivityPageObject($this->selenium);
    }

    public function addProject($addNewCustomer, $customer, $project, $projectAdmin1=null, $projectAdmin2=null, $removeProjectAdmin2=null, $description=null) {
        $this->selenium->selectFrame("relative=top");
        if ($addNewCustomer == "yes") {
            $this->selenium->click($this->linkAddCustomer);
            $this->addCustomerDialogBox->addCustomer($customer);
        } else if ($addNewCustomer == "no") {
            $this->selenium->type($this->txtCustomerName, $customer);
        }
        $this->selenium->type($this->txtProjectName, $project);
        if ($projectAdmin1)
            $this->selenium->type($this->txtProjectAdmin1, $projectAdmin1);
        if ($projectAdmin2) {
            $this->selenium->click($this->linkAddAnotherProjectAdmin);
            $this->selenium->type($this->txtProjectAdmin2, $projectAdmin2);
        }
        if ($removeProjectAdmin2 == "yes" && $this->selenium->isElementPresent($this->linkRemoveProjectAdmin)) {
            $this->selenium->click($this->linkRemoveProjectAdmin);
        }
        if ($description)
            $this->selenium->type($this->txtDescription, $description);

        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editProject($addNewCustomer, $customer, $project, $projectAdmin1=null, $projectAdmin2=null, $removeProjectAdmin2=null, $description=null) {
        $this->selenium->click($this->btnSave);
        $this->addProject($addNewCustomer, $customer, $project, $projectAdmin1, $projectAdmin2, $removeProjectAdmin2, $description);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}