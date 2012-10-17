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
class AddCustomerPageObject extends Component {

    public $txtCustomerName = "addCustomer_customerName";
    public $txtDescription = "addCustomer_description";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $dialogBtnSave = "dialogSave";
    public $dialogBtnCancel = "dialogCancel";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Project Customers");
    }

    public function addCustomer($customer, $description=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtCustomerName, $customer);
        if ($description)
            $this->selenium->type($this->txtDescription, $description);
        if ($this->selenium->isElementPresent($this->dialogBtnSave)) {
            $this->selenium->clickAndWait($this->dialogBtnSave);
        } else {
            $this->selenium->clickAndWait($this->btnSave);
        }
    }

    public function editCustomer($customer, $description=null) {
        $this->selenium->click($this->btnSave);
        $this->addCustomer($customer, $description);
    }

}